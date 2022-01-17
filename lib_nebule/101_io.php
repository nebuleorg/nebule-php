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
    private $_defaultIO;
    private $_listClasses = array();
    private $_listType = array();
    private $_listLocalisations = array();
    private $_listFilterStrings = array();
    private $_listMode = array();
    private $_listInstances = array();
    private $_listTypes = array();
    private $_listModes = '';

    /**
     * Instance de la bibliothèque nebule.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    protected $_configuration;

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        // Liste toutes les classes io* et les charges une à une.
        $myClass = get_class($this);
        $size = strlen($myClass);
        $list = get_declared_classes();
        foreach ($list as $i => $class) {
            if (substr($class, 0, $size) == $myClass && $class != $myClass) {
                $instance = new $class($nebuleInstance);
                $type = $instance->getType();
                $filterString = $instance->getFilterString();
                $url = $instance->getDefaultLocalisation();
                $mode = $instance->getMode();
                // Renseigne les tableaux de suivi.
                $this->_listClasses[$i] = $class;
                $this->_listTypes[$i] = $type;
                $this->_listLocalisations[$url] = $instance;
                $this->_listFilterStrings[$type] = $filterString;
                $this->_listModes[$type] = $mode;
                $this->_listInstances[$type] = $instance;
            }
        }

        $this->_defaultIO = new ioLocal($nebuleInstance);
    }

    public function __sleep()
    {
        return array('_defaultIO', '_listClasses');
    }

    public function __wakeup()
    {
        global $nebuleInstance;

        $list = $this->_listClasses;
        foreach ($list as $i => $class) {
            $instance = new $class($nebuleInstance);
            $type = $instance->getType();
            $filterString = $instance->getFilterString();
            $mode = $instance->getMode();
            // Renseigne les tableaux de suivi.
            $this->_listClasses[$i] = $class;
            $this->_listTypes[$i] = $type;
            $this->_listFilterStrings[$type] = $filterString;
            $this->_listModes[$type] = $mode;
            $this->_listInstances[$type] = $instance;
        }
    }

    /**
     * Recherche l'instance d'un module IO par rapport à son type de localisation.
     *
     * @param string $url
     * @return ioInterface
     */
    private function _findType(string $url)
    {
        if ($url == '')
            return $this->_defaultIO;

        // Fait le tour des modules IO et de leurs filtres pour déterminer le protocole.
        foreach ($this->_listFilterStrings as $type => $pattern) {
            if (preg_match($pattern, $url))
                return $this->_listInstances[$type];
        }
        return $this->_defaultIO;
    }

    /**
     * Liste les modules IO disponibles. Les noms sont ceux générés par getType().
     *
     * @return array:string
     */
    public function getModulesList(): array
    {
        return $this->_listTypes;
    }

    /**
     * Recherche l'instance d'un module IO par rapport à son type de protocole, celui généré par getType().
     *
     * @param string $type
     * @return ioInterface
     */
    public function getModule(string $type)
    {
        if ($type != ''
            && isset($this->_listInstances[$type])
        )
            return $this->_listInstances[$type];
        return $this->_defaultIO;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getType()
     */
    public function getType(): string
    {
        return $this->_defaultIO->getType();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getFilterString()
     */
    public function getFilterString(): string
    {
        return $this->_defaultIO->getFilterString();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getMode()
     */
    public function getMode(): string
    {
        return $this->_defaultIO->getMode();
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
        return $this->_defaultIO->getInstanceEntityID($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getDefaultLocalisation()
     */
    public function getDefaultLocalisation(): string
    {
        return $this->_defaultIO->getDefaultLocalisation();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksDirectory()
     */
    public function checkLinksDirectory(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkLinksDirectory();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsDirectory()
     */
    public function checkObjectsDirectory(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkObjectsDirectory();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksRead()
     */
    public function checkLinksRead(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkLinksRead();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksWrite()
     */
    public function checkLinksWrite(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkLinksWrite();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsRead()
     */
    public function checkObjectsRead(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkObjectsRead();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsWrite()
     */
    public function checkObjectsWrite(string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkObjectsWrite();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinkPresent()
     */
    public function checkLinkPresent(string $oid, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkLinkPresent($oid);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectPresent()
     */
    public function checkObjectPresent(string $oid, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->checkObjectPresent($oid);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getLinks()
     */
    public function getLinks(string $oid, string $url = '')
    {
        $instance = $this->_findType($url);
        return $instance->getLinks($oid);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::ObfuscatedLinksRead()
     */
    public function obfuscatedLinksRead(string $entity, string $signer = '0', string $url = ''): array
    {
        $instance = $this->_findType($url);
        return $instance->obfuscatedLinksRead($entity);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObject()
     */
    public function getObject(string $oid, int $maxsize = 0, string $url = '')
    {
        $instance = $this->_findType($url);
        return $instance->getObject($oid, $maxsize);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setLink()
     */
    public function setLink(string $oid, string &$link, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->setLink($oid, $link);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setObject()
     */
    public function setObject(string $oid, string &$data, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->setObject($oid, $data);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetLink()
     */
    public function unsetLink(string $oid, string &$link, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->unsetLink($oid, $link);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::flushLinks()
     */
    public function flushLinks(string $oid, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->flushLinks($oid);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetObject()
     */
    public function unsetObject(string $oid, string $url = ''): bool
    {
        $instance = $this->_findType($url);
        return $instance->unsetObject($oid);
    }
}
