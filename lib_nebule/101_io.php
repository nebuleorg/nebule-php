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
        $list = get_declared_classes();
        foreach ($list as $i => $class) {
            if (substr($class, 0, 2) == 'io' && $class != 'io') {
                $instance = new $class;
                $type = $instance->getType();
                $filterString = $instance->getFilterString();
                $localisation = $instance->getDefaultLocalisation();
                $mode = $instance->getMode();
                // Renseigne les tableaux de suivi.
                $this->_listClasses[$i] = $class;
                $this->_listTypes[$i] = $type;
                $this->_listLocalisations[$localisation] = $instance;
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
        $list = $this->_listClasses;
        foreach ($list as $i => $class) {
            $instance = new $class;
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
     * @param string $localisation
     * @return ioInterface
     */
    private function _findType(string $localisation)
    {
        if ($localisation == '')
            return $this->_defaultIO;

        // Fait le tour des modules IO et de leurs filtres pour déterminer le protocole.
        foreach ($this->_listFilterStrings as $type => $pattern) {
            if (preg_match($pattern, $localisation))
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
    public function getInstanceEntityID(string $localisation = ''): string
    {
        return $this->_defaultIO->getInstanceEntityID($localisation);
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
    public function checkLinksDirectory(string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->checkLinksDirectory();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsDirectory()
     */
    public function checkObjectsDirectory(string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->checkObjectsDirectory();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksRead()
     */
    public function checkLinksRead(string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->checkLinksRead();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksWrite()
     */
    public function checkLinksWrite(string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->checkLinksWrite();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsRead()
     */
    public function checkObjectsRead(string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->checkObjectsRead();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsWrite()
     */
    public function checkObjectsWrite(string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->checkObjectsWrite();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinkPresent()
     */
    public function checkLinkPresent(string $object, string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->checkLinkPresent($object);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectPresent()
     */
    public function checkObjectPresent(string $object, string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->checkObjectPresent($object);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::linksRead()
     */
    public function linksRead(string $object, string $localisation = '')
    {
        $instance = $this->_findType($localisation);
        return $instance->linksRead($object);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::ObfuscatedLinksRead()
     */
    public function obfuscatedLinksRead(string $entity, string $signer = '0', string $localisation = ''): array
    {
        $instance = $this->_findType($localisation);
        return $instance->obfuscatedLinksRead($entity);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::objectRead()
     */
    public function objectRead(string $object, int $maxsize = 0, string $localisation = '')
    {
        $instance = $this->_findType($localisation);
        return $instance->objectRead($object, $maxsize);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::writeLink()
     */
    public function writeLink(string $object, string &$link, string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->writeLink($object, $link);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::writeObject()
     */
    public function writeObject(string &$data, string $localisation = '')
    {
        $instance = $this->_findType($localisation);
        return $instance->writeObject($data);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::deleteLink()
     */
    public function deleteLink(string $object, string &$link, string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->deleteLink($object, $link);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::flushLinks()
     */
    public function flushLinks(string $object, string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->flushLinks($object);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::deleteObject()
     */
    public function deleteObject(string $object, string $localisation = ''): bool
    {
        $instance = $this->_findType($localisation);
        return $instance->deleteObject($object);
    }
}
