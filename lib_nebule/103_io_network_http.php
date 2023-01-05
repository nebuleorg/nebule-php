<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * La classe ioNetworkHTTP.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ioNetworkHTTP extends io implements ioInterface
{
    /**
     * I/O type supported.
     *
     * @var string
     */
    const TYPE = 'HTTP';

    /**
     * I/O filter supported.
     *
     * @var string
     */
    const FILTER = '/^http:/i';

    /**
     * Default localisation for this I/O module.
     *
     * @var string
     */
    const LOCALISATION = 'http://localhost';

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

    protected function _initialisation(nebule $nebuleInstance): void
    {
        global $nebuleLibVersion;

        $this->_maxLink = $this->_configuration->getOptionUntyped('ioReadMaxLinks');
        $this->_maxData = $this->_configuration->getOptionUntyped('ioReadMaxData');
        // Détermination de l'URL par défaut.
        //$this->_defaultLocalisation = self::LOCALISATION;
        $this->_defaultLocalisation = 'http' . '://' . $_SERVER['SERVER_NAME'];
        // Environnement PHP.
        ini_set('allow_url_fopen', 'true');
        ini_set('allow_url_include', 'true');
        ini_set('user_agent', 'nebule/ioHTTP/' . $nebuleLibVersion);
        ini_set('default_socket_timeout', $this->_configuration->getOptionAsString('ioTimeout'));
    }

    public function __sleep()
    {
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_filesTrancodeKey = '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $this->_filesTrancodeKey = '';
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
     * @see ioInterface::getLocalisation()
     */
    public function getLocalisation(): string
    {
        //return self::LOCALISATION;
        return $this->_defaultLocalisation;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getInstanceEntityID()
     */
    public function getInstanceEntityID(string $url = ''): string
    {
        if ($url == '')
            $url = $this->_defaultLocalisation;
        $url = $url . '/' . nebule::NEBULE_LOCAL_ENTITY_FILE;
        if ($this->_checkExistOverHTTP($url))
            return file_get_contents($url);
        return '0';
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksDirectory()
     */
    public function checkLinksDirectory(string $url = ''): bool
    {
        if ($url == '')
            $url = $this->_defaultLocalisation;
        $url = $url . '/' . nebule::NEBULE_LOCAL_LINKS_FOLDER . '/';
        return $this->_checkExistOverHTTP($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsDirectory()
     */
    public function checkObjectsDirectory(string $url = ''): bool
    {
        if ($url == '')
            $url = $this->_defaultLocalisation;
        $url = $url . '/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/';
        return $this->_checkExistOverHTTP($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksRead()
     */
    public function checkLinksRead(string $url = ''): bool
    {
        if ($url == '')
            $url = $this->_defaultLocalisation;
        $url = $url . '/' . nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $this->_configuration->getOptionAsString('puppetmaster');
        return $this->_checkExistOverHTTP($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksWrite()
     */
    public function checkLinksWrite(string $url = ''): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsRead()
     */
    public function checkObjectsRead(string $url = ''): bool
    {
        if ($url == '')
            $url = $this->_defaultLocalisation;
        $url = $url . '/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $this->_configuration->getOptionAsString('puppetmaster');
        return $this->_checkExistOverHTTP($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsWrite()
     */
    public function checkObjectsWrite(string $url = ''): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinkPresent()
     */
    public function checkLinkPresent(string $oid, string $url = ''): bool
    {
        if ($url == '')
            $url = $this->_defaultLocalisation;
        if (!Node::checkNID($oid, false))
            return false;
        $url = $url . '/' . nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $oid;
        return $this->_checkExistOverHTTP($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectPresent()
     */
    public function checkObjectPresent(string $oid, string $url = ''): bool
    {
        if ($url == '')
            $url = $this->_defaultLocalisation;
        if (!Node::checkNID($oid, false))
            return false;
        $url = $url . '/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $oid;
        return $this->_checkExistOverHTTP($url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getLinks()
     */
    public function getLinks(string $oid, string $url = '', int $offset = 0): array
    {
        if ($url == '')
            $url = $this->_defaultLocalisation;
        if (!Node::checkNID($oid, false)
            || !$this->_configuration->getOptionAsBoolean('permitSynchronizeLink')
        )
            return array();
        $url = $url . '/' . nebule::NEBULE_LOCAL_LINKS_FOLDER . '/' . $oid;
        if (!$this->_checkExistOverHTTP($url))
            return array();

        $n = 0;
        $t = array();

        // Lecture et extraction des liens. TODO faire $offset
        $c = file_get_contents($url);
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
     * @see ioInterface::getObfuscatedLinks()
     */
    public function getObfuscatedLinks(string $entity, string $signer = '0', string $url = ''): array
    {
        // @todo
        return array();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObject()
     */
    public function getObject(string $oid, int $maxsize = 0, string $url = '')
    {
        if ($url == '')
            $url = $this->_defaultLocalisation;
        if (!Node::checkNID($oid, false)
            || !$this->_configuration->getOptionAsBoolean('permitSynchronizeObject')
        )
            return false;
        $url = $url . '/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $oid;
        if ($this->_checkExistOverHTTP($url))
            return file_get_contents($url);
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setLink()
     */
    public function setLink(string $oid, string &$link, string $url = ''): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::setObject()
     */
    public function setObject(string $oid, string &$data, string $url = ''): bool
    {
        // Disabled on HTTP
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetObject()
     */
    public function unsetObject(string $oid, $url = ''): bool
    {
        // Disabled on HTTP
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetLink()
     */
    public function unsetLink(string $oid, string &$link, $url = ''): bool
    {
        // Disabled on HTTP
        return false;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::flushLinks()
     */
    public function flushLinks(string $oid, $url = ''): bool
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
     * @param string $url
     * @return boolean
     */
    private function _checkExistOverHTTP(string $url): bool
    {
        $url = parse_url($url);

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
