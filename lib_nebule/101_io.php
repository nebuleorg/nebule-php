<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * La classe io.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * C'est une classe qui ne fait aucune action par elle-même.
 * Elle recense les classes de communication pour différents protocoles.
 * Elle est ensuite utilisée comme routeur pour recevoir les requêtes et les rediriger vers
 *   les bonnes classes par rapport aux protocoles utilisés dans les requêtes...
 */
class io implements ioInterface
{
    const DEFAULT_CLASS = 'Disk';

    /**
     * I/O type supported.
     *
     * @var string
     */
    private const TYPE = '';

    /**
     * I/O filter supported.
     *
     * @var string
     */
    private const FILTER = '';

    /**
     * Default localisation for this I/O module.
     *
     * @var string
     */
    private const LOCALISATION = '';

    /**
     * @var ?ioInterface
     */
    private $_defaultInstance = null;
    private $_ready = false;
    private $_listClasses = array();
    private $_listInstances = array();
    private $_listTypes = array();
    private $_listLocalisations = array();
    private $_listFilterStrings = array();
    private $_listModes = array();

    /**
     * Instance de la bibliothèque nebule.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance métrologie en cours.
     *
     * @var Metrology
     */
    protected $_metrology;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    protected $_configuration;

    /**
     * Instance de gestion du cache.
     *
     * @var Cache
     */
    protected $_cache;

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();

//$this->_metrology->addLog('MARK1 class=' . get_class($this), Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');

        $this->_initialisation($nebuleInstance);
    }

    public function __toString(): string
    {
        return self::TYPE;
    }

    /**
     * Load all classes on theme.
     *
     * @param nebule $nebuleInstance
     * @return void
     */
    protected function _initialisation(nebule $nebuleInstance): void
    {
        $myClass = get_class($this);
        $size = strlen($myClass);
        $list = get_declared_classes();
        foreach ($list as $class) {
            if (substr($class, 0, $size) == $myClass && $class != $myClass)
                $this->_initSubClass($class, $nebuleInstance);
        }

        $this->_initDefault('ioLibrary');
    }

    /**
     * Init instance for a module class.
     *
     * @param string $class
     * @param nebule $nebuleInstance
     * @return void
     */
    protected function _initSubClass(string $class, nebule $nebuleInstance): void
    {
        $instance = new $class($nebuleInstance);
        $type = $instance->getType();
        $filterString = $instance->getFilterString();
        $url = $instance->getLocalisation();
        $mode = $instance->getMode();

        $this->_listClasses[$class] = $class;
        $this->_listTypes[$class] = $type;
        $this->_listInstances[$type] = $instance;
        $this->_listLocalisations[$url] = $instance;
        $this->_listFilterStrings[$type] = $filterString;
        $this->_listModes[$type] = $mode;
    }

    /**
     * Select default instance and set ready.
     *
     * @param string $name
     * @return void
     */
    protected function _initDefault(string $name): void
    {
        $option = $this->_configuration->getOptionAsString($name);
        if (isset($this->_listClasses[get_class($this) . $option]))
        {
            $this->_defaultInstance = $this->_listInstances[$option];
            $this->_ready = true;
        }
        elseif (isset($this->_listClasses[get_class($this) . self::DEFAULT_CLASS]))
        {
            $this->_defaultInstance = $this->_listInstances[self::DEFAULT_CLASS];
            $this->_ready = true;
        }
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getType()
     */
    public function getType(): string
    {
        //if (self::TYPE == '' && ! is_null($this->_defaultInstance))
        //    return $this->_defaultInstance->getType();
        return get_class($this)::TYPE;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getReady()
     */
    public function getReady(): bool
    {
        return $this->_ready;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getFilterString()
     */
    public function getFilterString(): string
    {
        //if (self::FILTER == '' && ! is_null($this->_defaultInstance))
        //    return $this->_defaultInstance->getFilterString();
        return get_class($this)::FILTER;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getLocalisation()
     */
    public function getLocalisation(): string
    {
        if (get_class($this)::LOCALISATION == '' && ! is_null($this->_defaultInstance))
            return $this->_defaultInstance->getLocalisation();
        return get_class($this)::LOCALISATION;
    }

    /**
     * Recherche l'instance d'un module IO par rapport à son type de localisation.
     *
     * @param string $url
     * @return ioInterface
     */
    private function _findType(string $url): ioInterface
    {
        if ($url == '')
            return $this->_defaultInstance;

        // Fait le tour des modules IO et de leurs filtres pour déterminer le protocole.
        foreach ($this->_listFilterStrings as $type => $pattern) {
            if (preg_match($pattern, $url))
                return $this->_listInstances[$type];
        }
        return $this->_defaultInstance;
    }

    /**
     * List modules names sorted by getType().
     *
     * @return array:string
     */
    public function getModulesList(): array
    {
        return $this->_listTypes;
    }

    /**
     * List modules instances sorted by getType().
     *
     * @param string $type
     * @return ioInterface
     */
    public function getModule(string $type): ioInterface
    {
        if ($type != ''
            && isset($this->_listInstances[$type])
        )
            return $this->_listInstances[$type];
        return $this->_defaultInstance;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getMode()
     */
    public function getMode(): string
    {
        return $this->_defaultInstance->getMode();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setFilesTranscodeKey()
     */
    public function setFilesTranscodeKey(string &$key): void
    {
        // Fait le tour des modules IO pour injecter la clé.
        foreach ($this->_listClasses as $instance)
            $instance->setFilesTranscodeKey($key);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetFilesTranscodeKey()
     */
    public function unsetFilesTranscodeKey(): void
    {
        // Fait le tour des modules IO pour supprimer la clé.
        foreach ($this->_listClasses as $instance)
            $instance->unsetFilesTranscodeKey();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getInstanceEntityID()
     */
    public function getInstanceEntityID(string $url = ''): string
    {
        return $this->_defaultInstance->getInstanceEntityID($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksDirectory()
     */
    public function checkLinksDirectory(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkLinksDirectory($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsDirectory()
     */
    public function checkObjectsDirectory(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkObjectsDirectory($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksRead()
     */
    public function checkLinksRead(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkLinksRead($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksWrite()
     */
    public function checkLinksWrite(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkLinksWrite($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsRead()
     */
    public function checkObjectsRead(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkObjectsRead($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsWrite()
     */
    public function checkObjectsWrite(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkObjectsWrite($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinkPresent()
     */
    public function checkLinkPresent(string $oid, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkLinkPresent($oid, $url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectPresent()
     */
    public function checkObjectPresent(string $oid, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkObjectPresent($oid, $url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getBlockLinks()
     */
    public function getBlockLinks(string $oid, string $url = '', int $offset = 0): array
    {
        $instance = $this->_findType($url);
        return $instance->getBlockLinks($oid, $url, $offset);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObfuscatedLinks()
     */
    public function getObfuscatedLinks(string $entity, string $signer = '0', string $url = ''): array
    {
        $instance = $this->_findType($url);
        return $instance->getObfuscatedLinks($entity, $signer, $url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObject()
     */
    public function getObject(string $oid, int $maxsize = 0, string $url = '')
    {
        $instance = $this->_findType($url);
        return $instance->getObject($oid, $maxsize, $url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setBlockLink()
     */
    public function setBlockLink(string $oid, string &$link, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->setBlockLink($oid, $link, $url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setObject()
     */
    public function setObject(string $oid, string &$data, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->setObject($oid, $data, $url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetLink()
     */
    public function unsetLink(string $oid, string &$link, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->unsetLink($oid, $link, $url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::flushLinks()
     */
    public function flushLinks(string $oid, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->flushLinks($oid, $url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetObject()
     */
    public function unsetObject(string $oid, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->unsetObject($oid, $url);
    }
}
