<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * La classe ioHTTP.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ioHTTP extends io implements ioInterface
{
    /**
     * Localisation par défaut de ce module I/O.
     *
     * @var string
     */
    const DEFAULT_LOCALISATION = 'http://localhost';

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
     * Localisation par défaut à utiliser.
     *
     * @var string
     */
    private $_defaultLocalisation;

    /**
     * Valeur de la clé de transcodage des noms des fichiers de liens dissimulés.
     *
     * @var number
     */
    private $_filesTrancodeKey = '0';

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        global $nebuleLibVersion;

        $this->_nebuleInstance = $nebuleInstance;
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_maxLink = $this->_configuration->getOptionUntyped('ioReadMaxLinks');
        $this->_maxData = $this->_configuration->getOptionUntyped('ioReadMaxData');
        // Détermination de l'URL par défaut.
        $this->_defaultLocalisation = self::DEFAULT_LOCALISATION;
        // Environnement PHP.
        ini_set('allow_url_fopen', 'true');
        ini_set('allow_url_include', 'true');
        ini_set('user_agent', 'nebule/ioHTTP/' . $nebuleLibVersion);
        ini_set('default_socket_timeout', $this->_configuration->getOptionUntyped('ioTimeout'));
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
        return 'HTTP';
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getFilterString()
     */
    public function getFilterString(): string
    {
        return '/^http:/i';
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getMode()
     */
    public function getMode(): string
    {
        return 'RO';
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
        if ($localisation == '')
            $localisation = $this->_defaultLocalisation;
        $localisation = $localisation . '/' . nebule::NEBULE_LOCAL_ENTITY_FILE;
        if ($this->_checkExistOverHTTP($localisation))
            return file_get_contents($localisation);
        return '0';
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksDirectory()
     */
    public function checkLinksDirectory(string $localisation = ''): bool
    {
        if ($localisation == '')
            $localisation = $this->_defaultLocalisation;
        $localisation = $localisation . '/' . nebule::NEBULE_LOCAL_LINKS_FOLDER . '/';
        return $this->_checkExistOverHTTP($localisation);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsDirectory()
     */
    public function checkObjectsDirectory(string $localisation = ''): bool
    {
        if ($localisation == '')
            $localisation = $this->_defaultLocalisation;
        $localisation = $localisation . '/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/';
        return $this->_checkExistOverHTTP($localisation);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksRead()
     */
    public function checkLinksRead(string $localisation = ''): bool
    {
        if ($localisation == '')
            $localisation = $this->_defaultLocalisation;
        $localisation = $localisation . '/' . nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . Configuration::OPTIONS_DEFAULT_VALUE['puppetmaster'];
        return $this->_checkExistOverHTTP($localisation);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksWrite()
     */
    public function checkLinksWrite(string $localisation = ''): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsRead()
     */
    public function checkObjectsRead(string $localisation = ''): bool
    {
        if ($localisation == '')
            $localisation = $this->_defaultLocalisation;
        $localisation = $localisation . '/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . Configuration::OPTIONS_DEFAULT_VALUE['puppetmaster'];
        return $this->_checkExistOverHTTP($localisation);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsWrite()
     */
    public function checkObjectsWrite(string $localisation = ''): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinkPresent()
     */
    public function checkLinkPresent($object, string $localisation = ''): bool
    {
        if ($localisation == '')
            $localisation = $this->_defaultLocalisation;
        if (!is_string($object)
            || $object == '0'
            || $object == ''
            || !ctype_xdigit($object)
        )
            return false;
        $localisation = $localisation . '/' . nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object;
        return $this->_checkExistOverHTTP($localisation);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectPresent()
     */
    public function checkObjectPresent($object, string $localisation = ''): bool
    {
        if ($localisation == '')
            $localisation = $this->_defaultLocalisation;
        if (!is_string($object)
            || $object == '0'
            || $object == ''
            || !ctype_xdigit($object)
        )
            return false;
        $localisation = $localisation . '/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object;
        return $this->_checkExistOverHTTP($localisation);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::linksRead()
     */
    public function linksRead(string $object, string $localisation = '')
    {
        if ($localisation == '')
            $localisation = $this->_defaultLocalisation;
        if ($object == '0'
            || $object == ''
            || !ctype_xdigit($object)
        )
            return false;
        if (!$this->_configuration->getOptionAsBoolean('permitSynchronizeLink'))
            return false;
        $localisation = $localisation . '/' . nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $object;
        if (!$this->_checkExistOverHTTP($localisation))
            return false;
        $n = 0;
        $t = array();

        // Lecture et extraction des liens.
        $c = file_get_contents($localisation);
        $l = array_filter(explode(' ', strtr($c, "\n", ' ')));
        unset($c);

        // Filtre le nombre de liens lus.
        foreach ($l as $k) {
            $t[$n] = $k;
            if ($n > $this->_maxLink)
                break 1;
            $n++;
        }
        unset($l, $k, $n);
        return $t;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::ObfuscatedLinksRead()
     */
    public function obfuscatedLinksRead(string $entity, string $signer = '0', string $localisation = ''): array
    {
        // @todo
        return array();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::objectRead()
     */
    public function objectRead(string $object, int $maxsize = 0, string $localisation = '')
    {
        if ($localisation == '')
            $localisation = $this->_defaultLocalisation;
        if ($object == '0'
            || $object == ''
            || !ctype_xdigit($object)
        )
            return false;
        if (!$this->_configuration->getOptionAsBoolean('permitSynchronizeObject'))
            return false;
        $localisation = $localisation . '/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object;
        if ($this->_checkExistOverHTTP($localisation))
            return file_get_contents($localisation);
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::writeLink()
     */
    public function writeLink(string $object, string &$link, string $localisation = ''): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::writeObject()
     */
    public function writeObject(string &$data, string $localisation = ''): bool
    {
        // Disabled on HTTP
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::deleteObject()
     */
    public function deleteObject(string $object, $localisation = ''): bool
    {
        // Disabled on HTTP
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::deleteLink()
     */
    public function deleteLink(string $object, string &$link, $localisation = ''): bool
    {
        // Disabled on HTTP
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::flushLinks()
     */
    public function flushLinks(string $object, $localisation = ''): bool
    {
        // Disabled on HTTP
        return false;
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

    /**
     * Vérifie la présence d'un fichier ou dossier via HTTP.
     *
     * @param string $localisation
     * @return boolean
     */
    private function _checkExistOverHTTP(string $localisation): bool
    {
        $url = parse_url($localisation);

        $handle = fsockopen($url['host'], 80, $errno, $errstr, 1);
        if ($handle === false)
            return false;

        $out = "HEAD " . $url['path'] . " HTTP/1.1\r\n"
            . "Host: " . $url['host'] . "\r\n"
            . "Connection: Close\r\n\r\n";
        $response = '';

        fwrite($handle, $out);
        while (!feof($handle)) {
            $response .= fgets($handle, 20);
            if (strlen($response) > 0)
                break;
        }
        fclose($handle);

        $pos = strpos($response, ' ');
        if ($pos === false)
            return false;

        $code = substr($response, $pos + 1, 3);
        if ($code == '200')
            return true;

        return false;
    }
}
