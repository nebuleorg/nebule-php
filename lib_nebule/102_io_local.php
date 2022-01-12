<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * La classe ioUnixFileSystem.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ioLocal extends io implements ioInterface
{
    /**
     * Localisation par défaut de ce module I/O.
     *
     * @var string
     */
    const DEFAULT_LOCALISATION = 'file://';

    /**
     * Nombre maximum de liens à lire.
     *
     * @var number
     */
    private $_maxLink;

    /**
     * Quantité maximum de données à lire dans un objet.
     *
     * @var number
     */
    private $_maxData;

    /**
     * Le mode d'accès reconnu.
     * Doit être RO ou RW, ou vide au début.
     *
     * @var string
     */
    private $_mode = '';

    /**
     * Valeur de la clé de transcodage des noms des fichiers de liens dissimulés.
     *
     * @var string
     */
    private $_filesTrancodeKey = '';

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        if (!file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER))
            mkdir(nebule::NEBULE_LOCAL_LINKS_FOLDER);
        if (!file_exists(nebule::NEBULE_LOCAL_OBJECTS_FOLDER))
            mkdir(nebule::NEBULE_LOCAL_OBJECTS_FOLDER);

        $this->_nebuleInstance = $nebuleInstance;
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_maxLink = $this->_configuration->getOptionUntyped('ioReadMaxLinks');
        $this->_maxData = $this->_configuration->getOptionUntyped('ioReadMaxData');
    }

    public function __sleep()
    {
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_filesTrancodeKey = '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $this->_filesTrancodeKey = '';
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getType()
     */
    public function getType(): string
    {
        return 'FileSystem';
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getFilterString()
     */
    public function getFilterString(): string
    {
        return '/^\//';
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getMode()
     */
    public function getMode(): string
    {
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
    public function setFilesTranscodeKey(string &$key): void
    {
        $this->_filesTrancodeKey = $key;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetFilesTranscodeKey()
     */
    public function unsetFilesTranscodeKey(): void
    {
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_filesTrancodeKey = '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $this->_filesTrancodeKey = '';
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getDefaultLocalisation()
     */
    public function getDefaultLocalisation(): string
    {
        return self::DEFAULT_LOCALISATION;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getInstanceEntityID()
     */
    public function getInstanceEntityID(string $localisation = ''): string
    {
        $filesize = filesize(nebule::NEBULE_LOCAL_ENTITY_FILE);
        return file_get_contents(nebule::NEBULE_LOCAL_ENTITY_FILE, false, null, 0, $filesize);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksDirectory()
     */
    public function checkLinksDirectory(string $localisation = ''): bool
    {
        if ($localisation != ''
            || !file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER)
            || !is_dir(nebule::NEBULE_LOCAL_LINKS_FOLDER)
        )
            return false;

        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsDirectory()
     */
    public function checkObjectsDirectory(string $localisation = ''): bool
    {
        if ($localisation != ''
            || !file_exists(nebule::NEBULE_LOCAL_OBJECTS_FOLDER)
            || !is_dir(nebule::NEBULE_LOCAL_OBJECTS_FOLDER)
        ) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksRead()
     */
    public function checkLinksRead(string $localisation = ''): bool
    {
        if ($localisation != '')
            return false;

        $file = nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . Configuration::OPTIONS_DEFAULT_VALUE['puppetmaster'];
        $result = false;

        $data = file_get_contents($file, false, null, 0, 16);
        if ($data === false)
            return false;

        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksWrite()
     */
    public function checkLinksWrite(string $localisation = ''): bool
    {
        if ($localisation != '')
            return false;

        $file = nebule::NEBULE_LOCAL_LINKS_FOLDER . '/0';
        $resultCreate = false;
        $resultDelete = false;

        // Test la création si pas déjà présent.
        if (file_exists($file))
            $resultCreate = true;
        else {
            $resultCreate = file_put_contents(
                $file,
                'checkLinksWrite');
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
     * @see ioInterface::checkObjectsRead()
     */
    public function checkObjectsRead(string $localisation = ''): bool
    {
        if ($localisation != '')
            return false;

        $file = nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . Configuration::OPTIONS_DEFAULT_VALUE['puppetmaster'];
        $result = false;

        $data = file_get_contents($file, false, null, 0, 16);
        if ($data === false)
            return false;

        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsWrite()
     */
    public function checkObjectsWrite(string $localisation = ''): bool
    {
        if ($localisation != '')
            return false;

        $file = nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/0';
        $resultCreate = false;
        $resultDelete = false;

        // Test la création si pas déjà présent.
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
    public function checkLinkPresent(string $object, string $localisation = ''): bool
    {
        if ($localisation != ''
            || $object == '0'
            || $object == ''
            || !ctype_xdigit($object)
            || !file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object)
            || is_dir(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object)
        )
            return false;

        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectPresent()
     */
    public function checkObjectPresent(string $object, string $localisation = ''):bool
    {
        if ($localisation != ''
            || $object == '0'
            || $object == ''
            || !ctype_xdigit($object)
            || !file_exists(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object)
            || is_dir(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object)
        )
            return false;

        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::linksRead()
     */
    public function linksRead(string $object, string $localisation = '')
    {
        /**
         * Compteur de liens lus.
         * @var double $linkRead
         */
        $linkRead = 0;

        /**
         * Table des liens lus.
         * @var array $linkList
         */
        $linkList = array();

        if ($localisation != ''
            || $object == '0'
            || $object == ''
            || !ctype_xdigit($object)
            || !file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object)
            || is_dir(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object)
        )
            return false;

        /**
         * Descripteur du fichier en cours de lecture.
         * @var finfo $file
         */
        $file = file(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object);
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
     * @see ioInterface::ObfuscatedLinksRead()
     */
    public function obfuscatedLinksRead(string $entity, string $signer = '0', string $localisation = ''): array
    {
        /**
         * Compteur de liens lus.
         * @var double $linkRead
         */
        $linkRead = 0;

        /**
         * Liste des fichiers de liens à lire.
         * @var array $fileList
         */
        $fileList = array();

        /**
         * Table des liens lus.
         * @var array $linkList
         */
        $linkList = array();

        // Vérifie la présence d'une clé de transcodage des noms des fichiers.
        if ($this->_filesTrancodeKey == '')
            return $linkList; // TODO ou return false ???

        // Vérifie l'entité destinataire des liens dissimulés.
        if ($localisation != ''
            || $entity == '0'
            || $entity == ''
            || !ctype_xdigit($entity)
            || !file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $entity)
            || is_dir(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $entity)
        )
            return $linkList; // TODO ou return false ???

        // Vérifie l'entité signataire des liens dissimulés.
        if (!is_string($signer)
            || $signer == ''
            || !ctype_xdigit($signer)
            || !file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $signer)
            || is_dir(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $signer)
        )
            $signer = '0';

        if ($signer == '0') {
            // Si aucun signataire particulier n'est demandé, lit tous les fichiers de liens attachés à l'entité destinataire.
            $fileList = glob(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $entity . '-*', GLOB_NOSORT);

            // Vérifie la validité du nom du fichier.
            /* TODO
             * foreach ($list as $l) {
             * if (preg_match("~^a+\.php$~",$file))
             * $files[] = $l;
             * }
             */
        } elseif (file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $entity . '-' . $signer)
            && !is_dir(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $entity . '-' . $signer)
        ) {
            // Si un signataire particulier est demandé, lit uniquement le fichier de liens concerné.
            $fileList[0] = $entity . '-' . $signer;
        } else {
            // Sinon il n'y a pas de liens dissimulés entre ces deux entités.
            return $linkList;
        }

        // Pour chaque fichier listé, lit les liens.
        foreach ($fileList as $filename) {
            $file = file(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $filename);
            foreach ($file as $link) {
                // @todo vérifier regex que le lien est de type c ...
                if (true)
                    $linkList[$linkRead] = $link;
                // Vérifie que le nombre maximum de liens à lire n'est pas dépassé.
                $linkRead++;
                if ($linkRead > $this->_maxLink)
                    break 1;
            }
        }

        return $linkList;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::objectRead()
     */
    public function objectRead(string $object, int $maxsize = 0, string $localisation = '')
    {
        if ($localisation != ''
            || $object == '0'
            || $object == ''
            || !ctype_xdigit($object)
            || !file_exists(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object)
            || is_dir(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object)
        )
            return false;

        if ($maxsize == 0)
            $maxsize = $this->_maxData;

        $filesize = filesize(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object);
        if ($filesize > $maxsize)
            $filesize = $maxsize;

        return file_get_contents(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object, false, null, 0, $filesize);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::writeLink()
     */
    public function writeLink(string $object, string &$link, string $localisation = ''): bool
    {
        // Vérifie les arguments.
        if ($localisation != ''
            || $object == '0'
            || $object == ''
            || $link == ''
            || !$this->_checkFileLink($object, $link)
            || !$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || $this->getMode() != 'RW'
            || is_dir(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object)
        )
            return false;

        if (file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object)) {
            // Si le fichier de lien est présent, teste la présence du lien.
            // Extrait un tableau avec une ligne par élément.
            $l = file(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object);
            foreach ($l as $k) {
                // Si déjà présent, on quitte.
                if (trim($k) == trim($link))
                    return true;
            }
        } else {
            // Si le fichier de lien n'est pas présent, le crée.
            file_put_contents(
                nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object,
                'nebule/liens/version/' . $this->_configuration->getOptionUntyped('defaultLinksVersion') . "\n");
        }

        if (file_put_contents(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object, $link . "\n", FILE_APPEND) !== false)
            return true;

        $this->_mode = 'RO';
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::writeObject()
     */
    public function writeObject(string &$data, string $localisation = '')
    {
        if ($localisation != ''
            || !$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteObject')
            || $this->getMode() != 'RW'
        )
            return false;

        $hash = $this->_nebuleInstance->getCryptoInstance()->hash($data);

        if (file_exists(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $hash))
            return $hash;
        if (file_put_contents(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $hash, $data) !== false)
            return $hash;

        $this->_mode = 'RO';
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::deleteObject()
     */
    public function deleteObject(string $object, string $localisation = ''): bool
    {
        if ($localisation != ''
            || $object == '0'
            || $object == ''
            || !ctype_xdigit($object)
            || !$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteObject')
            || $this->getMode() != 'RW'
            || is_dir(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object)
        )
            return false;

        if (!file_exists(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object))
            return true;

        // Essaye de supprimer le fichier de l'objet.
        unlink(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object);

        if (file_exists(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object)) {
            $this->_mode = 'RO';
            return false;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::deleteLink()
     */
    public function deleteLink(string $object, string &$link, string $localisation = ''): bool
    {
        if ($localisation != ''
            || $object == '0'
            || $object == ''
            || !$this->_checkFileLink($object, $link)
            || $link == ''
            || !$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || $this->getMode() != 'RW'
            || is_dir(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object)
        )
            return false;

        // Prépare le fichier temporaire de travail des liens.
        if (!file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object . '.rmlnk'))
            return true;

        // TODO

        return true;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::flushLinks()
     */
    public function flushLinks(string $object, string $localisation = ''): bool
    {
        if ($localisation != ''
            || $object == '0'
            || $object == ''
            || !ctype_xdigit($object)
            || !$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || $this->getMode() != 'RW'
            || is_dir(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object)
        )
            return false;

        if (!file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object))
            return true;

        // Essaye de supprimer le fichier des liens de l'objet.
        unlink(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object);

        if (file_exists(nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object)) {
            $this->_mode = 'RO';
            return false;
        }
        return true;
    }

    /**
     * Retourne l'identifiant traduit d'un objet en fonction de la clé de translation.
     * La traduction se fait par la prise d'empreinte de la concaténation de l'identifiant de l'objet, de l'identifiant de l'entité et de la clé de translation.
     * L'empreinte sur juste l'identifiant de l'objet est déjà non réversible en elle-même.
     * Même sans clé de translation, la translation varie d'une entité à l'autre. Et c'est pareil si deux enttiés utilisent la même clé de translation.
     * La valeur retournée dépend aussi de l'algorithme utilisé, c'est à dire celui par défaut pour les prises d'empreinte.
     * La fonction est non réversible.
     *
     * TODO à revoir avec ajout d'une option TranslateLinkHashAlgorithm
     *
     * @param string $id
     * @return string
     */
    private function _getTranlateID(string $id): string
    {
        return $this->_nebuleInstance->getCryptoInstance()->hash($id . $this->_nebuleInstance->getCurrentEntity() . $this->_filesTrancodeKey);
    }

    /**
     * Vérification du fichier de liens que l'on doit utiliser.
     * Si le lien est de type c, le fichier à la forme "hash-hash".
     * Si non il a la forme "hash".
     *
     * TODO à revoir !
     *
     * @param string $object
     * @param string $link
     * @return number|boolean
     */
    private function _checkFileLink(string &$object, string &$link)
    {
        /**
         * Indice du champs lu, de 1 à 7.
         * @var integer $j
         */
        $j = 1;

        /**
         * Action detectée.
         * @var string $action
         */
        $action = '';

        /**
         * Première lecture des champs, premier champs.
         * @var string $e
         */
        $e = strtok(trim($link), '_');

        // Extrait si lien type c.
        while ($e !== false) {
            if ($j == 4) {
                $action = trim($e);
            }
            if ($j < 8) {
                // Lecture de la suite des champs, champs suivant.
                $e = strtok('_');
            } else {
                // Ne doit pas avoir plus de 7 champs.
                return false;
            }
            $j++;
        }

        // Vérifie l'objet si lien d'offuscation ou non.
        if ($action != 'c') {
            if (!ctype_xdigit($object)) {
                return false;
            }
        } else {
            $hashentsign = '';
            $hashentdest = '';
            $j = 1;
            $e = strtok(trim($link), '-');
            // Extrait les deux hashs.
            while ($e !== false) {
                if ($j == 1) {
                    $hashentsign = trim($e);
                } else {
                    $hashentdest = trim($e);
                }
                if ($j < 3) {
                    // Lecture de la suite des champs, champs suivant.
                    $e = strtok('-');
                } else {
                    // Ne doit pas avoir plus de 2 champs.
                    return false;
                }
                $j++;
            }

            if ($hashentsign = ''
                || $hashentdest = ''
                    || !ctype_xdigit($hashentsign)
                    || !ctype_xdigit($hashentdest)
                    || $hashentsign . '-' . $hashentdest != $object
            ) {
                return false;
            }
        }

        // Si on arrive là c'est que c'estbon.
        return true;
    }

    /**
     * Destructeur.
     * Fin de traitement - Rien à fermer sur un fs.
     */
    public function __destruct()
    {
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_filesTrancodeKey = '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $this->_filesTrancodeKey = '';
    }
}
