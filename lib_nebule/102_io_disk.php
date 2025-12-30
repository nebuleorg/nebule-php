<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * The class ioDisk.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ioDisk extends io implements ioInterface
{
    const TYPE = 'Disk';
    const FILTER = '/^\//';
    const LOCALISATION = 'file://';

    private int $_maxLink = 0;
    private int $_maxData = 0;
    private string $_mode = '';

    protected function _initialisation(): void
    {
        if (!file_exists(References::LINKS_FOLDER))
            mkdir(References::LINKS_FOLDER);
        if (!file_exists(References::OBJECTS_FOLDER))
            mkdir(References::OBJECTS_FOLDER);

        $this->_maxLink = $this->_configurationInstance->getOptionAsInteger('ioReadMaxLinks');
        $this->_maxData = $this->_configurationInstance->getOptionAsInteger('ioReadMaxData');
        $this->_metrologyInstance->addLog('instancing class ioDisk', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'e4958dd2');
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getMode()
     */
    public function getMode(): string {
        if ($this->_mode == '') {
            $this->_mode = 'RO';
            if ($this->checkObjectsWrite()
                && $this->checkLinksWrite()
            )
                $this->_mode = 'RW';
        }
        return $this->_mode;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setFilesTranscodeKey()
     */
    public function setFilesTranscodeKey(string &$key): void { $this->_filesTranscodeKey = $key; }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetFilesTranscodeKey()
     */
    public function unsetFilesTranscodeKey(): void {
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_filesTranscodeKey = '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $this->_filesTranscodeKey = '';
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getLocation()
     */
    public function getLocation(): string { return self::LOCALISATION; }

    /**
     * {@inheritDoc}
     * @see ioInterface::getInstanceEntityID()
     */
    public function getInstanceEntityID(string $url = ''): string {
        $filesize = filesize(References::LOCAL_ENTITY_FILE);
        return file_get_contents(References::LOCAL_ENTITY_FILE, false, null, 0, $filesize);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksDirectory()
     */
    public function checkLinksDirectory(string $url = ''): bool {
        if (!file_exists(References::LINKS_FOLDER)
            || !is_dir(References::LINKS_FOLDER)
        )
            return false;
        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsDirectory()
     */
    public function checkObjectsDirectory(string $url = ''): bool {
        if (!file_exists(References::OBJECTS_FOLDER)
            || !is_dir(References::OBJECTS_FOLDER)
        )
            return false;
        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksRead()
     */
    public function checkLinksRead(string $url = ''): bool {
        $file = References::LINKS_FOLDER . '/' . $this->_configurationInstance->getOptionAsString('puppetmaster');

        if (!file_exists($file))
            return false;
        $data = file_get_contents($file, false, null, 0, 16);
        if ($data === false)
            return false;
        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksWrite()
     */
    public function checkLinksWrite(string $url = ''): bool {
        $file = References::LINKS_FOLDER . '/0';
        $resultDelete = false;

        if (file_exists($file))
            $resultCreate = true;
        else {
            $resultCreate = file_put_contents(
                $file,
                'checkLinksWrite');
        }

        if (file_exists($file))
            unlink($file);
        if (!file_exists($file))
            $resultDelete = true;

        return ($resultCreate && $resultDelete);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsRead()
     */
    public function checkObjectsRead(string $url = ''): bool {
        $file = References::OBJECTS_FOLDER . '/' . $this->_configurationInstance->getOptionAsString('puppetmaster');

        if (!file_exists($file))
            return false;
        $data = file_get_contents($file, false, null, 0, 16);
        if ($data === false)
            return false;
        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsWrite()
     */
    public function checkObjectsWrite(string $url = ''): bool {
        $file = References::OBJECTS_FOLDER . '/0';
        $resultDelete = false;

        // Check if the object is already present.
        if (file_exists($file))
            $resultCreate = true;
        else {
            $resultCreate = file_put_contents(
                $file,
                'checkObjectsWrite');
        }

        // Test la suppression si le fichier a pu être créé.
        if (file_exists($file))
            unlink($file);
        if (!file_exists($file))
            $resultDelete = true;

        return ($resultCreate && $resultDelete);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinkPresent()
     */
    public function checkLinkPresent(string $oid, string $url = ''): bool {
        if (!Node::checkNID($oid, false)
            || !file_exists(References::LINKS_FOLDER . '/' . $oid)
            || is_dir(References::LINKS_FOLDER . '/' . $oid)
        )
            return false;
        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectPresent()
     */
    public function checkObjectPresent(string $oid, string $url = ''):bool {
        if (!Node::checkNID($oid, false)
            || !file_exists(References::OBJECTS_FOLDER . '/' . $oid)
            || is_dir(References::OBJECTS_FOLDER . '/' . $oid)
        )
            return false;
        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getBlockLinks()
     */
    public function getBlockLinks(string $oid, string $url = '', int $offset = 0): array {
        $linkRead = 0;
        $linkList = array();

        if (!Node::checkNID($oid, false)
            || !file_exists(References::LINKS_FOLDER . '/' . $oid)
            || is_dir(References::LINKS_FOLDER . '/' . $oid)
        )
            return array();

        $file = file(References::LINKS_FOLDER . '/' . $oid);
        foreach ($file as $link) {
            $linkList[$linkRead] = $link;
            // Vérifie que le nombre maximum de liens à lire n'est pas dépassé.
            $linkRead++;
            if ($linkRead > $this->_maxLink)
                break 1;
        }

        return $linkList;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObfuscatedLinks()
     */
    public function getObfuscatedLinks(string $entity, string $signer = '0', string $url = ''): array {
        $linksRead = 0;
        $fileList = array();
        $linksList = array();

        // Check if there is a file name transcoding key.
        if ($this->_filesTranscodeKey == '')
            return $linksList;

        // Check the entity recipient of the hidden links.
        if (!Node::checkNID($entity, false)
            || !file_exists(References::LINKS_FOLDER . '/' . $entity)
            || is_dir(References::LINKS_FOLDER . '/' . $entity)
        )
            return $linksList;

        // Check the signing entity of the hidden links.
        if (!is_string($signer)
            || $signer == ''
            || !file_exists(References::LINKS_FOLDER . '/' . $signer)
            || is_dir(References::LINKS_FOLDER . '/' . $signer)
        )
            $signer = '0';

        if ($signer == '0') {
            // If no specific signer requested, read all link files attached to the recipient entity.
            $fileList = glob(References::LINKS_FOLDER . '/' . $entity . '-*', GLOB_NOSORT);

            // Check the validity of file names.
            /* TODO
             * foreach ($list as $l) {
             * if (preg_match("~^a+\.php$~",$file))
             * $files[] = $l;
             * }
             */
        } elseif (file_exists(References::LINKS_FOLDER . '/' . $entity . '-' . $signer)
            && !is_dir(References::LINKS_FOLDER . '/' . $entity . '-' . $signer)
        ) {
            // If a specific signer is requested, only read the concerned link file.
            $fileList[0] = $entity . '-' . $signer;
        } else {
            // Otherwise, there are no hidden links between these two entities.
            return $linksList;
        }

        // For each file listed, reads the links.
        foreach ($fileList as $filename) {
            $file = file(References::LINKS_FOLDER . '/' . $filename);
            foreach ($file as $link) {
                // @todo verify with regex that the link is of type c ...
                if (true)
                    $linksList[$linksRead] = $link;
                // Check if the maximum number of links to read has not been exceeded.
                $linksRead++;
                if ($linksRead > $this->_maxLink)
                    break 1;
            }
        }

        return $linksList;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObject()
     */
    public function getObject(string $oid, int $maxsize = 0, string $url = ''): bool|string {
        if (!Node::checkNID($oid, false)
            || !file_exists(References::OBJECTS_FOLDER . '/' . $oid)
            || is_dir(References::OBJECTS_FOLDER . '/' . $oid)
        )
            return false;

        if ($maxsize == 0)
            $maxsize = $this->_maxData;

        $filesize = filesize(References::OBJECTS_FOLDER . '/' . $oid);
        if ($filesize > $maxsize)
            $filesize = $maxsize;

        return file_get_contents(References::OBJECTS_FOLDER . '/' . $oid, false, null, 0, $filesize);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setBlockLink()
     */
    public function setBlockLink(string $oid, string &$link, string $url = ''): bool {
        if (!Node::checkNID($oid, false)
            || $link == ''
            || !$this->_checkFileLink($oid, $link)
            || !$this->_configurationInstance->getOptionAsBoolean('permitWrite')
            || !$this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            || $this->getMode() != 'RW'
            || is_dir(References::LINKS_FOLDER . '/' . $oid)
        )
            return false;

        if (file_exists(References::LINKS_FOLDER . '/' . $oid)) {
            $l = file(References::LINKS_FOLDER . '/' . $oid);
            foreach ($l as $k) {
                // Si déjà présent, on quitte.
                if (trim($k) == trim($link))
                    return true;
            }
        }

        if (file_put_contents(References::LINKS_FOLDER . '/' . $oid, $link . "\n", FILE_APPEND) !== false)
            return true;

        $this->_mode = 'RO';
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setObject()
     */
    public function setObject(string $oid, string &$data, string $url = ''): bool {
        if (!Node::checkNID($oid, false)
            || !$this->_configurationInstance->getOptionAsBoolean('permitWrite')
            || !$this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            || $this->getMode() != 'RW'
        )
            return false;

        if (file_exists(References::OBJECTS_FOLDER . '/' . $oid))
            return true;
        if (file_put_contents(References::OBJECTS_FOLDER . '/' . $oid, $data) !== false) {
            $this->_metrologyInstance->addLog('ok write oid=' . $oid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e2f2baa1');
            return true;
        }

        $this->_mode = 'RO';
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetObject()
     */
    public function unsetObject(string $oid, string $url = ''): bool {
        if (!Node::checkNID($oid, false)
            || !$this->_configurationInstance->getOptionAsBoolean('permitWrite')
            || !$this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            || $this->getMode() != 'RW'
            || is_dir(References::OBJECTS_FOLDER . '/' . $oid)
        )
            return false;

        if (!file_exists(References::OBJECTS_FOLDER . '/' . $oid))
            return true;

        // Try to delete the object file.
        unlink(References::OBJECTS_FOLDER . '/' . $oid);

        if (file_exists(References::OBJECTS_FOLDER . '/' . $oid)) {
            $this->_mode = 'RO';
            return false;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetLink()
     */
    public function unsetLink(string $oid, string &$link, string $url = ''): bool {
        if (!Node::checkNID($oid, false)
            || !$this->_checkFileLink($oid, $link)
            || $link == ''
            || !$this->_configurationInstance->getOptionAsBoolean('permitWrite')
            || !$this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            || $this->getMode() != 'RW'
            || is_dir(References::LINKS_FOLDER . '/' . $oid)
        )
            return false;

        // Prepare a temporary working file for links.
        if (!file_exists(References::LINKS_FOLDER . '/' . $oid . '.rmlnk'))
            return true;

        // TODO

        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::flushLinks()
     */
    public function flushLinks(string $oid, string $url = ''): bool {
        if (!Node::checkNID($oid, false)
            || !$this->_configurationInstance->getOptionAsBoolean('permitWrite')
            || !$this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            || $this->getMode() != 'RW'
            || is_dir(References::LINKS_FOLDER . '/' . $oid)
        )
            return false;

        if (!file_exists(References::LINKS_FOLDER . '/' . $oid))
            return true;

        // Essaye de supprimer le fichier des liens de l'objet.
        unlink(References::LINKS_FOLDER . '/' . $oid);

        if (file_exists(References::LINKS_FOLDER . '/' . $oid)) {
            $this->_mode = 'RO';
            return false;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getList()
     */
    public function getList(string $url = ''): array { return array_diff(scandir(References::LINKS_FOLDER . '/'), array('.', '..')); }

    /**
     * Returns a translated object identifier based on the translation key.
     * Translation is done by hashing the concatenation of object ID, entity ID and translation key.
     * The hash of just the object ID is already irreversible by itself.
     * Even without a translation key, the translation varies from one entity to another. And it's the same if two entities use the same translation key.
     * The returned value also depends on the algorithm used, which is the default one for hashing.
     * The function is irreversible.
     * TODO to review with addition of a TranslateLinkHashAlgorithm option
     *
     * @param string $id
     * @return string
     */
    private function _getTranslateID(string $id): string { return $this->getNidFromData($id . $this->_entitiesInstance->getGhostEntityEID() . $this->_filesTranscodeKey); }

    /**
     * Checking the links file we need to use.
     * If the link is type c, the file has the form "hash-hash".
     * Otherwise it has the form "hash".
     * TODO to review !
     *
     * @param string $oid
     * @param string $link
     * @return boolean
     */
    private function _checkFileLink(string &$oid, string &$link): bool {
        $j = 1;
        $action = '';
        $e = strtok(trim($link), '_');

        while ($e !== false) {
            if ($j == 4)
                $action = trim($e);
            if ($j < 8)
                $e = strtok('_');
            else
                return false;
            $j++;
        }

        if ($action == 'c') {
            $hashentsign = '';
            $hashentdest = '';
            $j = 1;
            $e = strtok(trim($link), '-');
            while ($e !== false) {
                if ($j == 1)
                    $hashentsign = trim($e);
                else
                    $hashentdest = trim($e);
                if ($j < 3)
                    $e = strtok('-');
                else
                    return false;
                $j++;
            }

            if ($hashentsign == ''
                || $hashentdest == ''
                || $hashentsign . '-' . $hashentdest != $oid
            )
                return false;
        }
        return true;
    }
}
