<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * The primary class io.
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
class io extends Functions implements ioInterface {
    const DEFAULT_CLASS = 'disk';
    const FILTER = '';
    const LOCALISATION = '';

    protected ?ioInterface $_defaultInstance = null;
    private array $_listLocalisations = array();
    private array $_listFilterStrings = array();
    private array $_listModes = array();
    protected string $_filesTranscodeKey = '';

    public function __sleep() {
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_filesTranscodeKey = '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $this->_filesTranscodeKey = '';
        return array();
    }

    public function __destruct() {
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_filesTranscodeKey = '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $this->_filesTranscodeKey = '';
    }

    public function __toString(): string { return self::TYPE; }

    protected function _initialisation(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $myClass = get_class($this);
        $size = strlen($myClass);
        $list = get_declared_classes();
        foreach ($list as $class) {
            if (substr($class, 0, $size) == $myClass && $class != $myClass) {
                $this->_metrologyInstance->addLog('add class ' . $class, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b8f416f7');
                $instance = $this->_initSubInstance($class);
                $filterString = $instance->getFilterString();
                $url = $instance->getLocation();
                $mode = $instance->getMode();
                $this->_listLocalisations[$url] = $instance;
                $this->_listFilterStrings[$instance::TYPE] = $filterString;
                $this->_listModes[$instance::TYPE] = $mode;
            }
        }
        $this->_defaultInstance = $this->_getDefaultSubInstance('ioLibrary');
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getFilterString()
     */
    public function getFilterString(): string { return get_class($this)::FILTER; }

    /**
     * {@inheritDoc}
     * @see ioInterface::getLocation()
     */
    public function getLocation(): string {
        if (get_class($this)::LOCALISATION == '' && ! is_null($this->_defaultInstance))
            return $this->_defaultInstance->getLocation();
        return get_class($this)::LOCALISATION;
    }

    private function _getInstanceByURL(string $url): ioInterface {
        $return = $this->_defaultInstance;
        foreach ($this->_listFilterStrings as $type => $pattern)
            if (preg_match($pattern, $url))
                $return =  $this->_listInstances[$type];
        if (!is_a($return, 'Nebule\Library\io')) {
            $return = $this;
        }
        return $return;
    }

    public function getModulesList(): array { return $this->_listTypes; }

    public function getModuleByType(string $type): ioInterface {
        if (isset($this->_listInstances[$type]))
            return $this->_listInstances[$type];
        return $this->_defaultInstance;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getMode()
     */
    public function getMode(): string { return $this->_defaultInstance->getMode(); }

    /**
     * {@inheritDoc}
     * @see ioInterface::setFilesTranscodeKey()
     */
    public function setFilesTranscodeKey(string &$key): void {
        foreach ($this->_listClasses as $instance)
            $instance->setFilesTranscodeKey($key);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetFilesTranscodeKey()
     */
    public function unsetFilesTranscodeKey(): void {
        foreach ($this->_listClasses as $instance)
            $instance->unsetFilesTranscodeKey();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getInstanceEntityID()
     */
    public function getInstanceEntityID(string $url = ''): string { return $this->_defaultInstance->getInstanceEntityID($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksDirectory()
     */
    public function checkLinksDirectory(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkLinksDirectory($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsDirectory()
     */
    public function checkObjectsDirectory(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkObjectsDirectory($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksRead()
     */
    public function checkLinksRead(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkLinksRead($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksWrite()
     */
    public function checkLinksWrite(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkLinksWrite($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsRead()
     */
    public function checkObjectsRead(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkObjectsRead($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsWrite()
     */
    public function checkObjectsWrite(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkObjectsWrite($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinkPresent()
     */
    public function checkLinkPresent(string $oid, string $url = ''): bool { return $this->_getInstanceByURL($url)->checkLinkPresent($oid, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectPresent()
     */
    public function checkObjectPresent(string $oid, string $url = ''): bool { return $this->_getInstanceByURL($url)->checkObjectPresent($oid, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::getBlockLinks()
     */
    public function getBlockLinks(string $oid, string $url = '', int $offset = 0): array { return $this->_getInstanceByURL($url)->getBlockLinks($oid, $url, $offset); }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObfuscatedLinks()
     */
    public function getObfuscatedLinks(string $entity, string $signer = '0', string $url = ''): array { return $this->_getInstanceByURL($url)->getObfuscatedLinks($entity, $signer, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObject()
     */
    public function getObject(string $oid, int $maxsize = 0, string $url = ''): bool|string { return $this->_getInstanceByURL($url)->getObject($oid, $maxsize, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::setBlockLink()
     */
    public function setBlockLink(string $oid, string &$link, string $url = ''): bool { return $this->_getInstanceByURL($url)->setBlockLink($oid, $link, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::setObject()
     */
    public function setObject(string $oid, string &$data, string $url = ''): bool { return $this->_getInstanceByURL($url)->setObject($oid, $data, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetLink()
     */
    public function unsetLink(string $oid, string &$link, string $url = ''): bool { return $this->_getInstanceByURL($url)->unsetLink($oid, $link, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::flushLinks()
     */
    public function flushLinks(string $oid, string $url = ''): bool { return $this->_getInstanceByURL($url)->flushLinks($oid, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetObject()
     */
    public function unsetObject(string $oid, string $url = ''): bool { return $this->_getInstanceByURL($url)->unsetObject($oid, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::getList()
     */
    public function getList(string $url = ''): array { return $this->_getInstanceByURL($url)->getList($url); }
}
