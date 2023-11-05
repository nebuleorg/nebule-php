<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class blocLink implements blocLinkInterface
{
    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_rawBlocLink',
        '_linksType',
        '_links',
        '_parsedLink',
        '_signed',
        '_checkCompleted',
        '_valid',
        '_validStructure',
        '_maxRL',
        '_maxRLUID',
        '_maxRS',
    );

    const LINK_VERSION = '2:0';
    const NID_MIN_HASH_SIZE = 64;
    const NID_MAX_HASH_SIZE = 8192;
    const NID_MIN_ALGO_SIZE = 2;
    const NID_MAX_ALGO_SIZE = 12;
    const LINK_MAX_BL_SIZE = 16384;
    const LINK_MAX_BS_SIZE = 16384;

    /**
     * Instance nebule en cours.
     *
     * @var nebule $_nebuleInstance
     */
    protected $_nebuleInstance;

    /**
     * Instance métrologie en cours.
     *
     * @var Metrology $_metrology
     */
    protected $_metrology;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration $_configuration
     */
    protected $_configuration;

    /**
     * Instance de gestion du cache.
     *
     * @var Cache $_cache
     */
    protected $_cache;

    /**
     * Instance io en cours.
     *
     * @var io $_io
     */
    protected $_io;

    /**
     * Instance crypto en cours.
     *
     * @var CryptoInterface $_crypto
     */
    protected $_crypto;

    /**
     * @var string $_rawBlocLink
     */
    protected $_rawBlocLink = '';

    /**
     * @var string $_linksType
     */
    protected $_linksType = '';

    /**
     * @var array $_links
     */
    protected $_links = array();

    /**
     * Parsed link contents.
     *
     * @var array $_parsedLink
     */
    protected $_parsedLink = array();

    /**
     * Booléen si le lien est en cours de création.
     *
     * @var boolean $_newLink
     */
    protected $_newLink = false;

    /**
     * Nombre de liens ajoutés.
     *
     * @var int $_newLinkCount
     */
    protected $_newLinkCount = 0;

    /**
     * Booléen si le lien a été vérifié.
     *
     * @var boolean $_checkCompleted
     */
    protected $_checkCompleted = false;

    /**
     * Booléen si le lien est vérifié et valide.
     *
     * @var boolean $_valid
     */
    protected $_valid = false;

    /**
     * Booléen si le lien a une structure valide.
     *
     * @var boolean $_validStructure
     */
    protected $_validStructure = false;

    /**
     * Booléen si le lien est signé.
     *
     * @var boolean $_signed
     */
    protected $_signed = false;

    protected $_maxRL = 1;
    protected $_maxRLUID = 3;
    protected $_maxRS = 1;

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @param string $blocLink
     * @param string $linkType
     * @return void
     */
    public function __construct(nebule $nebuleInstance, string $blocLink, string $linkType = Cache::TYPE_LINK)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_linksType = $linkType;
        $this->_maxRL = $this->_configuration->getOptionAsInteger('linkMaxRL');
        $this->_maxRLUID = $this->_configuration->getOptionAsInteger('linkMaxRLUID');
        $this->_maxRS = $this->_configuration->getOptionAsInteger('linkMaxRS');

        $this->_metrology->addLinkRead();

        if ($blocLink == 'new')
            $this->_new();
        else
            $this->_parse($blocLink);
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_rawBlocLink;
    }

    /**
     * Mise en sommeil de l'instance.
     *
     * @return string[]
     */
    public function __sleep()
    {
        return self::SESSION_SAVED_VARS;
    }

    /**
     * Fonction de réveil de l'instance et de réinitialisation de certaines variables non sauvegardées.
     *
     * @return void
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();

        // Reload current bloclink on links.
        foreach ($this->_links as $link)
            $link->setBlocInstance($this);
    }

    /**
     * Verify and parse links on bloc.
     * After parsing, a bloc link is valid only if structure and one signe are both valid.
     *
     * @param string $link
     * @return bool
     */
    protected function _parse(string $link): bool
    {
        $this->_validStructure = false;
        if (strlen($link) > (self::LINK_MAX_BL_SIZE + self::LINK_MAX_BS_SIZE + 1)) return false;
        if (strlen($link) == 0) return false;

        // Extract blocs from link L : BH_BL_BS
        $bh = strtok(trim($link), '_');
        if (is_bool($bh)) return false;
        $bl = strtok('_');
        if (is_bool($bl)) return false;
        $bs = strtok('_');
        if (is_bool($bs)) return false;

        // Check link overflow
        if (strtok('_') !== false) return false;

        // Check BH, BL and BS.
        //if (!$this->_checkBH($bh)) $this->_metrology->addLog('check link BH failed '.$link, Metrology::LOG_LEVEL_ERROR, __METHOD__, '80cbba4b');
        if (!$this->_checkBH($bh)) return false;
        //if (!$this->_checkBL($bl)) $this->_metrology->addLog('check link BL failed '.$link, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c5d22fda');
        if (!$this->_checkBL($bl)) return false;
        //if (!$this->_checkBS($bh, $bl, $bs)) $this->_metrology->addLog('check link BS failed '.$link, Metrology::LOG_LEVEL_ERROR, __METHOD__, '2828e6ae');
        $bh_bl = $bh . '_' . $bl;
        if (!$this->_newLink && !$this->_checkBS($bh_bl, $bs)) return false;

        $this->_parsedLink['link'] = $link;
        $this->_validStructure = true;
        $this->_checkCompleted = true;
        if ($this->_signed) $this->_valid = true;
        return true;
    }

    protected function _new(): void
    {
        $this->_rawBlocLink = 'nebule:link/' . $this->_configuration->getOptionAsString('defaultLinksVersion') . '_';
        $this->_newLink = true;
        $this->_newLinkCount = 0;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getRaw()
     * @return string
     */
    public function getRaw(): string
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '9c53bb45');

        return $this->_rawBlocLink;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getLinks()
     * @return array:linkInterface
     */
    public function getLinks(): array
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'ad2228cc');

        return $this->_links;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getSigners()
     * @return array
     */
    public function getSigners(): array
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'ad2228cc');

        $result = array();
        for ($i = 1; $i <= $this->_parsedLink['bs/count']; $i++)
            $result[] = $this->_parsedLink["bs/rs$i/eid"];

        return $result;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getParsed()
     * @return array
     */
    public function getParsed(): array
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '51d42c1b');

        return $this->_parsedLink;
    }

    /**
     * Get if the link have been checked entirely.
     * @return boolean
     */
    public function getCheckCompleted(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '513a5ba1');

        return $this->_checkCompleted;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getValid()
     * @return boolean
     */
    public function getValid(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '513a5ba1');

        return $this->_valid;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getValidStructure()
     * @return boolean
     */
    public function getValidStructure(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'bd937ab3');

        return $this->_validStructure;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getSigned()
     * @return boolean
     */
    public function getSigned(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '58ce3724');

        return $this->_signed;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getVersion()
     * @return string
     */
    public function getVersion(): string
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '614d794c');

        if (!isset($this->_parsedLink['bl/rv']))
            return '';

        return $this->_parsedLink['bl/rv'];
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getDate()
     * @return string
     */
    public function getDate(): string
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'e76bbadb');

        if (!isset($this->_parsedLink['bl/rc/chr']))
            return '';

        return $this->_parsedLink['bl/rc/chr'];
    }



    /**
     * Check block BH on link.
     *
     * @param string $bh
     * @return bool
     */
    protected function _checkBH(string &$bh): bool
    {
        if (strlen($bh) > 15)
        {
            $this->_metrology->addLog('BH size overflow '.substr($bh, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '84cf4447');
            return false;
        }

        $rf = strtok($bh, '/');
        if (is_bool($rf)) return false;
        $rv = strtok('/');
        if (is_bool($rv)) return false;

        // Check bloc overflow
        if (strtok('/') !== false) return false;

        // Check RF and RV.
        //if (!$this->_checkRF($rf)) $this->_metrology->addLog('check link BH/RF failed '.$bh, Metrology::LOG_LEVEL_ERROR, __METHOD__, '3c0b5c4f');
        if (!$this->_checkRF($rf)) return false;
        //if (!$this->_checkRV($rv)) $this->_metrology->addLog('check link BH/RV failed '.$bh, Metrology::LOG_LEVEL_ERROR, __METHOD__, '80c5975c');
        if (!$this->_checkRV($rv)) return false;

        $this->_parsedLink['bh'] = $bh;
        return true;
    }

    /**
     * Check block RF on link.
     *
     * @param string $rf
     * @return bool
     */
    protected function _checkRF(string &$rf): bool
    {
        if (strlen($rf) > 11) return false;

        // Check items from RF : APP:TYP
        $app = strtok($rf, ':');
        if (is_bool($app)) return false;
        if ($app != 'nebule') return false;
        $typ = strtok(':');
        if (is_bool($typ)) return false;
        if ($typ != 'link') return false;

        // Check registry overflow
        if (strtok(':') !== false) return false;

        $this->_parsedLink['bh/rf'] = $rf;
        return true;
    }

    /**
     * Check block RV on link.
     *
     * @param string $rv
     * @return bool
     */
    protected function _checkRV(string &$rv): bool
    {
        if (strlen($rv) > 3) return false;

        // Check items from RV : VER:SUB
        $ver = strtok($rv, ':');
        if (is_bool($ver)) return false;
        $sub = strtok(':');
        if (is_bool($sub)) return false;
        if ("$ver:$sub" != self::LINK_VERSION) return false;

        // Check registry overflow
        if (strtok(':') !== false) return false;

        $this->_parsedLink['bh/rv'] = $rv;
        return true;
    }

    /**
     * Check block BL on link.
     *
     * @param string $bl
     * @return bool
     */
    protected function _checkBL(string &$bl): bool
    {
        if (strlen($bl) > self::LINK_MAX_BL_SIZE)
        {
            $this->_metrology->addLog('BL size overflow '.substr($bl, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '9d3f9bda');
            return false;
        }

        $rc = strtok($bl, '/');
        if (is_bool($rc)) return false;
        //if (!$this->_checkRC($rc)) $this->_metrology->addLog('check link BL/RC failed '.$bl, Metrology::LOG_LEVEL_ERROR, __METHOD__, '86a58996');
        if (!$this->_checkRC($rc)) return false;

        $rc = strtok($bl, '/');
        $rl = strtok('/');
        if (is_bool($rl)) return false;

        $list = array();
        $i = 0;
        while (!is_bool($rl))
        {
            $list[$i] = $rl;
            $i++;
            if ($i > $this->_maxRL)
            {
                $this->_metrology->addLog('BL overflow '.substr($bl, 0, 1000) . '+ maxRL=' . $this->_maxRL, Metrology::LOG_LEVEL_ERROR, __METHOD__, '6a777706');
                return false;
            }
            $rl = strtok('/');
        }
        foreach ($list as $i => $rl)
        {
            //if (!$this->_checkRL($rl, (string)$i))) $this->_metrology->addLog('check link BL/RL failed '.$bl, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd865ee87');
            if (!$this->_checkRL($rl, (string)($i+1))) return false;
            if ($this->_linksType == Cache::TYPE_TRANSACTION)
                $instanceRL = new Transaction($this->_nebuleInstance, $rl, $this);
            else
                $instanceRL = new Link($this->_nebuleInstance, $rl, $this); // FIXME ne fonctionne pas correctement !
            if (!$instanceRL->getValid()) return false;

            $this->_links[] = $instanceRL;
        }

        $this->_parsedLink['bl'] = $bl;
        return true;
    }

    /**
     * Check block RC on link.
     * MOD must be 0 for now.
     * CHR must begin with 0, only contain digits and no more than 15 digits (020211018195523 or 020211018, etc...).
     *
     * @param string $rc
     * @return bool
     */
    protected function _checkRC(string &$rc): bool
    {
        if (strlen($rc) > 27)
        {
            $this->_metrology->addLog('BL/RC size overflow '.substr($rc, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '7282e54a');
            return false;
        }

        // Check items from RC : MOD>CHR
        $mod = strtok($rc, '>');
        if ($mod != '0') return false;
        $chr = strtok('>');
        if (is_bool($chr)) return false;
        if (strlen($chr) > 15) return false;
        if (!ctype_digit($chr)) return false;
        if ($chr[0] != '0') return false;
        // TODO faire un filtrage plus fin...

        // Check registry overflow
        if (strtok('>') !== false)
        {
            $this->_metrology->addLog('BL/RC overflow '.substr($rc, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '84cf4447');
            return false;
        }

        $this->_parsedLink['bl/rc'] = $rc;
        $this->_parsedLink['bl/rc/mod'] = $mod;
        $this->_parsedLink['bl/rc/chr'] = $chr;
        return true;
    }

    /**
     * Check block RL on link.
     *
     * @param string $rl
     * @param string $i
     * @return bool
     */
    protected function _checkRL(string &$rl, string $i): bool
    {
        if (strlen($rl) > 4096) // TODO maybe can be a constant
        {
            $this->_metrology->addLog('BL/RL size overflow '.substr($rl, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '4835c147');
            return false;
        }

        // Extract items from RL : REQ>NID>NID>NID>NID...
        $req = strtok($rl, '>');
        if (is_bool($req)) return false;
        if (!$this->_checkREQ($req, $i)) return false;
        $this->_parsedLink["bl/rl$i/req"] = $req;

        $rl1nid = strtok('>');
        if (is_bool($rl1nid)) return false;

        $list = array();
        $j = 0;
        while (!is_bool($rl1nid))
        {
            $list[$j] = $rl1nid;
            $j++;
            if ($j > $this->_maxRLUID)
            {
                $this->_metrology->addLog('BL/RL overflow '.substr($rl, 0, 1000) . '+ maxRLUID=' . $this->_maxRLUID,
                    Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd0c9961a');
                return false;
            }
            $rl1nid = strtok('>');
        }
        foreach ($list as $j => $nid)
        {
            if (!Node::checkNID($nid, $j > 0)) return false;
            $this->_parsedLink['bl/rl'.$i.'/nid'.$j] = $nid;
        }

        $this->_parsedLink['bl/rl'.$i] = $rl;
        return true;
    }

    /**
     * Check block REQ on link.
     *
     * @param string $req
     * @param string $i
     * @return bool
     */
    protected function _checkREQ(string &$req, string $i): bool
    {
        if ($req != 'l'
            && $req != 'f'
            && $req != 'u'
            && $req != 'd'
            && $req != 'e'
            && $req != 'c'
            && $req != 'k'
            && $req != 's'
            && $req != 'x'
        )
            return false;

        $this->_parsedLink["bl/rl$i/req"] = $req;
        return true;
    }

    /**
     * Check block BS on link.
     * TODO make a loop on many RS avoid attack on link signs fusion.
     *
     * @param string $bh_bl
     * @param string $bs
     * @return bool
     */
    protected function _checkBS(string &$bh_bl, string &$bs): bool
    {
        if (strlen($bs) > self::LINK_MAX_BS_SIZE)
        {
            $this->_metrology->addLog('BS size overflow '.substr($bs, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'efc551d7');
            return false;
        }

        $rs = strtok($bs, '/');
        if (is_bool($rs)) return false;

        $i = 0;
        while (!is_bool($rs))
        {
            $i++;
            if ($i > $this->_maxRS)
            {
                $this->_metrology->addLog('BS overflow '.substr($bs, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '9f2e670f');
                return false;
            }
            //if (!$this->_checkRS($rs, $bh, $bl)) $this->_metrology->addLog('check link BS/RS failed '.$bs, Metrology::LOG_LEVEL_ERROR, __METHOD__, '0690f5ac');
            //if (!$this->_checkRS($rs, $bh_bl, (string)$i)) return false;
            $this->_checkRS($rs, $bh_bl, (string)$i); // Do not quit on invalid sign, just need one of them ok.

            $rs = strtok('/');
        }

        $this->_parsedLink['bs'] = $bs;
        $this->_parsedLink['bs/count'] = $i;
        return true;
    }

    /**
     * Check block RS on link.
     *
     * @param string $rs
     * @param string $bh_bl
     * @param string $i
     * @return bool
     */
    protected function _checkRS(string &$rs, string &$bh_bl, string $i): bool
    {
        if (strlen($rs) > 4096) return false; // TODO à revoir.

        // Extract items from RS : NID>SIG
        $nid = strtok($rs, '>');
        if (is_bool($nid)) return false;
        $sig = strtok('>');
        if (is_bool($sig)) return false;

        // Check registry overflow
        if (strtok('>') !== false) return false;

        // --- --- --- --- --- --- --- --- ---
        // Check content RS 1 NID 1 : hash.algo.size
        //if (!Node::checkNID($nid, false)) $this->_metrology->addLog('check link bs/rs1/eid failed '.$rs, Metrology::LOG_LEVEL_ERROR, __METHOD__, '6e1150f9');
        if (!Node::checkNID($nid, false)) return false;
        $this->_parsedLink["bs/rs$i/eid"] = $nid;

        //if (!$this->_checkSIG($bh, $bl, $sig, $nid)) $this->_metrology->addLog('check link BS/RS1/SIG failed '.$rs, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e99ec81f');
        if (!$this->_checkSIG($bh_bl, $sig, $nid, $i)) return false;

        $this->_parsedLink["bs/rs$i"] = $rs;
        $this->_signed = true;
        return true;
    }

    /**
     * Check block SIG on link.
     *
     * @param string $bh_bl
     * @param string $sig
     * @param string $nid
     * @param string $i
     * @return boolean
     */
    protected function _checkSIG(string &$bh_bl, string &$sig, string &$nid, string $i): bool
    {
        if (strlen($sig) > 4096) return false; // TODO à revoir.

        // Check hash value.
        $sign = strtok($sig, '.');
        if (is_bool($sign)) return false;
        if (strlen($sign) < self::NID_MIN_HASH_SIZE) return false;
        if (strlen($sign) > self::NID_MAX_HASH_SIZE) return false;
        if (!ctype_xdigit($sign)) return false;

        // Check algo value.
        $algo = strtok('.');
        if (is_bool($algo)) return false;
        if (strlen($algo) < self::NID_MIN_ALGO_SIZE) return false;
        if (strlen($algo) > self::NID_MAX_ALGO_SIZE) return false;
        if (!ctype_alnum($algo)) return false;

        // Check size value.
        $size = strtok('.');
        if (is_bool($size)) return false;
        if (!ctype_digit($size)) return false; // Check content before!
        if ((int)$size < self::NID_MIN_HASH_SIZE) return false;
        if ((int)$size > self::NID_MAX_HASH_SIZE) return false;
        //if (strlen($sign) != (int)$size) return false; // TODO can't be checked ?

        // Check item overflow
        if (strtok('.') !== false) return false;

        if (!$this->_configuration->getOptionAsBoolean('permitCheckSignOnVerify')) return true;
        if ($this->_checkObjectContent($nid)) {
            $hash = $this->_crypto->hash($bh_bl, $algo . '.' . $size);
            $publicKey = $this->_io->getObject($nid);

            if ($this->_crypto->verify($hash, $sign, $publicKey))
            {
                $this->_parsedLink["bs/rs$i/sig"] = $sig;
                return true;
            }
        }
        return false;
    }

    protected function _checkObjectContent($oid): bool
    {
        if (!Node::checkNID($oid, false)
            || !$this->_io->checkObjectPresent($oid)
        )
            return false;
        return true;
    }

    /**
     * Add a link (RL) on new bloc of links (BL).
     *
     * @param string $rl
     * @return bool
     */
    public function addLink(string $rl): bool
    {
        if (!$this->_newLink
            || $rl == ''
        )
            return false;

        $instance = new Link($this->_nebuleInstance, $rl, $this);
        if ($instance->getValidStructure()
            && $this->_newLinkCount <= $this->_configuration->getOptionAsInteger('linkMaxRL')
        )
        {
            if ($this->_newLinkCount > 0)
                $this->_rawBlocLink .= '/';
            $this->_rawBlocLink .= $rl;
            $this->_newLinkCount++;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::sign()
     * @param string $publicKey
     * @param string $date
     * @return boolean
     */
    public function sign(string $publicKey = '0', string $date = ''): bool
    {
        $this->_metrology->addLog('sign ' . substr($this->_rawBlocLink, 0, 128), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'b6e89674');

        // Si autorisé à signer.
        if (!$this->_newLink
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Can not sign link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '09c33dba');
            return false;
        }

        // TODO vérifier que la table des RL est > 0

        // Prepare new link to sign.
        $this->_rawBlocLink .= '_';
        $this->_parse($this->_rawBlocLink);
        $this->_newLink = false;

        if ($date == '')
            $date = '0' . date(DATE_ATOM);

        $bh = 'nebule:link/2:0';
        $this->_parsedLink['bh'] = $bh;
        $this->_parsedLink['bh/rf'] = 'nebule:link';
        $this->_parsedLink['bh/rf/app'] = 'nebule';
        $this->_parsedLink['bh/rf/typ'] = 'nebule';
        $this->_parsedLink['bh/rv'] = '2:0';
        $this->_parsedLink['bh/rv/ver'] = '2';
        $this->_parsedLink['bh/rv/sub'] = '0';

        $bl = '0>' . $date . '/'; // FIXME
        $this->_parsedLink['bl'] = $bl;
        $this->_parsedLink['bl/rc'] = '0>' . $date;
        $this->_parsedLink['bl/rc/mod'] = '0';
        $this->_parsedLink['bl/rc/chr'] = $date;

        return false; // FIXME continuer !



        if ($this->_validStructure) {
            if ($publicKey == '0') {
                $publicKeyID = $this->_nebuleInstance->getCurrentEntity();
                $publicKeyInstance = $this->_nebuleInstance->getCurrentEntityInstance();
            } elseif ($publicKey == $this->_nebuleInstance->getCurrentEntity()) {
                $publicKeyInstance = $this->_nebuleInstance->getCurrentEntityInstance();
                $publicKeyID = $publicKey;
            } else {
                $publicKeyInstance = $this->_nebuleInstance->newEntity($publicKey);
                $publicKeyID = $publicKey;
            }
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Sign link for ' . $publicKeyID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd3c9521d');

            // Récupère l'algorithme de hash.
            $hashAlgo = $this->_configuration->getOptionAsString('cryptoHashAlgorithm');

            // Génère le lien sans signature et son hash pour vérification.
            $shortLink = $bh . '_' . $bl;

            // Génère la signature.
            $sign = $publicKeyInstance->signLink($shortLink, $hashAlgo);
            if ($sign !== null)
            {
                $bs = $publicKeyID . '>' . $sign;
                $this->_parsedLink['bs'] = $bs;
                $bh_bl = $bh . '_' . $bl;
                $this->_checkBS($bh_bl, $bs);
                $this->_rawBlocLink .= $bs;
                $this->_parsedLink['link'] = $this->_rawBlocLink;
                $this->_signed = true;
                $this->_valid = true;
                return true;
            }
        } else
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Invalid link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cd989943');
        return false;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::write()
     * @return boolean
     */
    public function write(): bool
    {
        $this->_metrology->addLog('write ' . substr($this->_rawBlocLink, 0, 128), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '50faf214');

        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_valid
            || !$this->_signed
        )
            return false;

        $this->_nebuleInstance->getMetrologyInstance()->addAction('addlnk', $this->_rawBlocLink, $this->_valid);

        // If needed, in history.
        if ($this->_configuration->getOptionAsBoolean('permitHistoryLinksSign'))
            $this->_io->setBlockLink(nebule::NEBULE_LOCAL_HISTORY_FILE, $this->_rawBlocLink);

        // If needed, in signers.
        for ($j = 0; $j > $this->_maxRS; $j++) {
            if (isset($this->_parsedLink['bs/rs'.$j.'/eid']))
                $this->_io->setBlockLink($this->_parsedLink['bs/rs'.$j.'/eid'], $this->_rawBlocLink);
            else
                break;
        }

        for ($i = 0 ; $i > $this->_maxRL; $i++)
        {
            if (isset($this->_parsedLink['bl/rl'.$i.'/req'])) {
                if ($this->_parsedLink['bl/rl' . $i . '/req'] != 'c') {
                    for ($j = 0; $j > $this->_maxRLUID; $j++) {
                        if (isset($this->_parsedLink['bl/rl' . $i . '/nid' . $j]))
                            $this->_io->setBlockLink($this->_parsedLink['bl/rl' . $i . '/nid' . $j], $this->_rawBlocLink);
                    }
                } elseif ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                    for ($j = 0; $j > $this->_maxRS; $j++) {
                        if (isset($this->_parsedLink['bs/rs'.$j.'/eid']))
                            $this->_io->setBlockLink($this->_parsedLink['bs/rs'.$j.'/eid'] . '-' . $this->_parsedLink['bl/rl' . $i . '/nid1'], $this->_rawBlocLink);
                        else
                            break;
                    }
                }
            } else
                break;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::signWrite()
     * @param string $publicKey
     * @param string $date
     * @return boolean
     */
    public function signWrite(string $publicKey = '0', string $date = ''): bool
    {
        $this->_metrology->addLog('sign write ' . substr($this->_rawBlocLink, 0, 128), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '51a338d4');

        if ($this->sign($publicKey, $date))
            return $this->write();
        return false;
    }



    /**
     * Affiche la partie du menu de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#l">B / Bloc de liens</a>
            <ul>
                <li><a href="#lelpo">BELPO / Liens à Propos d’un Objet</a></li>
                <li><a href="#lelco">BELCO / Liens Contenu dans un Objet</a></li>
                <li><a href="#le">BE / Entête</a></li>
                <li><a href="#lr">BR / Registre</a>
                    <ul>
                        <li><a href="#lrsi">BRSI / Le champs <code>Signature</code></a></li>
                        <li><a href="#lrusi">BRHSI / Le champs <code>HashSignataire</code></a></li>
                        <li><a href="#lrt">BRT / Le champs <code>TimeStamp</code></a></li>
                        <li><a href="#lra">BRA / Le champs <code>Action</code></a>
                            <ul>
                                <li><a href="#lral">BRAL / Action <code>l</code> – Lien entre objets</a></li>
                                <li><a href="#lraf">BRAF / Action <code>f</code> – Dérivé d’objet</a></li>
                                <li><a href="#lrau">BRAU / Action <code>u</code> – Mise à jour d’objet</a></li>
                                <li><a href="#lrad">BRAD / Action <code>d</code> – Suppression d’objet</a></li>
                                <li><a href="#lrae">BRAE / Action <code>e</code> – Équivalence d’objets</a></li>
                                <li><a href="#lrac">BRAC / Action <code>c</code> – Chiffrement de lien</a></li>
                                <li><a href="#lrak">BRAK / Action <code>k</code> – Chiffrement d’objet</a></li>
                                <li><a href="#lras">BRAS / Action <code>s</code> – Subdivision d’objet</a></li>
                                <li><a href="#lrax">BRAX / Action <code>x</code> – Suppression de lien</a></li>
                            </ul>
                        </li>
                        <li><a href="#lrhs">BRHS / Le champs <code>HashSource</code></a></li>
                        <li><a href="#lrhc">BRHC / Le champs <code>HashCible</code></a></li>
                        <li><a href="#lrhm">BRHM / Le champs <code>HashMeta</code></a></li>
                    </ul>
                </li>
                <li><a href="#l1">B1 / Lien simple</a></li>
                <li><a href="#l2">B2 / Lien double</a></li>
                <li><a href="#l3">B3 / Lien triple</a></li>
                <li><a href="#ls">BS / Stockage</a>
                    <ul>
                        <li><a href="#lsa">BSA / Arborescence</a></li>
                        <li><a href="#lsd">BSD / Dissimulation</a>
                            <ul>
                                <li><a href="#lsdrp">BSDRP / Registre public</a></li>
                                <li><a href="#lsdrd">BSDRD / Registre dissimulé</a></li>
                                <li><a href="#lsda">BSDA / Attaque sur la dissimulation</a></li>
                                <li><a href="#lsds">BSDS / Stockage et transcodage</a>
                                    <ul>
                                        <li><a href="#lsdst">BSDST / Translation de lien</a></li>
                                        <li><a href="#lsdsp">BSDSP / Protection de translation</a></li>
                                    </ul>
                                </li>
                                <li><a href="#lsdt">BSDT / Transfert et partage</a></li>
                                <li><a href="#lsdc">BSDC / Compromission</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#lt">BT / Transfert</a></li>
                <li><a href="#lv">BV / Vérification</a></li>
                <li><a href="#lo">BO / Oubli</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationCore(): void
    {
        ?>

        <h1 id="l">B / Bloc de liens</h1>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p style="color:red;">Cette partie est périmée avec la nouvelle version de liens !</p>
        <p>Le lien est la matérialisation dans un graphe d’une relation entre deux objets pondéré par un troisième
            objet.</p>

        <h5 id="lelpo">BELPO / Liens à Propos d’un Objet</h5>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les liens d’un objet sont consultables séquentiellement. Ils doivent être perçus comme des méta-données d’un
            objet.</p>
        <p>Les liens sont séparés soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;». Un
            lien est donc une suite de caractères ininterrompue, c'est-à-dire sans espace ou retour à la ligne.</p>
        <p>La taille du lien dépend de la taille de chaque champ.</p>
        <p>Chaque localisation contenant des liens doit avoir un entête de version.</p>

        <h5 id="lelco">BELCO / Liens Contenu dans un Objet</h5>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Certains liens d’un objet peuvent être contenus dans un autre objet.</p>
        <p>Cette forme de stockage des liens permet de les transmettre et de les manipuler sous la forme d’un objet. On
            peut ainsi profiter du découpage et du chiffrement. Plusieurs liens peuvent être stockés sans être
            nécessairement en rapport avec les mêmes objets.</p>
        <p>Les liens stockés dans un objet ne peuvent pas faire référence à ce même objet.</p>
        <p>Tout ajout de lien crée implicitement un nouvel objet de mise à jour, c'est-à-dire lié par un lien de type
            u.</p>
        <p>Chaque fichier contenant des liens doit avoir un entête de version.</p>
        <p>Les objets contenants des liens ne sont pas reconnus et exploités lors de la lecture des liens. Ceux-ci
            doivent d’abord être extraits et injectés dans les liens des objets concernés. En clair, on ne peut pas s’en
            servir facilement pour de l’anonymisation.</p>

        <h2 id="le">BE / Entête</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’entête des liens est constitué du texte <code>nebule/liens/version/1.2</code>. Il est séparé du premier
            lien soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Il doit être transmis avec les liens, en premier.</p>

        <h2 id="lr">BR / Registre</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien décrit la syntaxe du lien :</p>
        <p style="text-align:center">
            <code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>
        <p>Ce registre a un nombre de champs fixe. Chaque champ a une place fixe dans le lien. Les champs ont une
            taille variable. Le séparateur de champs est l’underscore «&nbsp;_&nbsp;». Les champs ne peuvent contenir ni
            l’underscore «&nbsp;_&nbsp;» ni l’espace &nbsp;» &nbsp;» ni le retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Tout lien qui ne respecte pas cette syntaxe est à considérer comme invalide et à supprimer. Tout lien dont la
            <code>Signature</code> est invalide est à considérer comme invalide et à supprimer. La vérification peut
            être réalisée en réassemblant les champs après nettoyage.</p>

        <h4 id="lrsi">BRSI / Le champs <code>Signature</code></h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champs <code>Signature</code> est représenté en deux parties séparées par un point «&nbsp;.&nbsp;» . La
            première partie contient la valeur de la signature. La deuxième partie contient le nom court de la fonction
            de prise d’empreinte utilisée.</p>
        <p>La signature est calculée sur l’empreinte du lien réalisée avec la fonction de prise d’empreinte désignée
            dans la deuxième partie. L’empreinte du lien est calculée sur tout le lien sauf le champs
            <code>signature</code>, c'est-à-dire sur «&nbsp;<code>_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code>&nbsp;»
            avec le premier underscore inclus.</p>
        <p>La signature ne contient que des caractères hexadécimaux, c'est-à-dire de «&nbsp;0&nbsp;» à «&nbsp;9&nbsp;»
            et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule. La fonction de prise d’empreinte est notée en
            caractères alpha-numériques en minuscule.</p>

        <h5 id="lrusi">BRHSI / Le champs <code>HashSignataire</code></h5>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champs <code>HashSignataire</code> désigne l’objet de l’entité qui génère le lien et le signe.</p>
        <p>Il ne contient que des caractères hexadécimaux, c'est-à-dire de «&nbsp;0&nbsp;» à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;»
            à «&nbsp;f&nbsp;» en minuscule.</p>

        <h3 id="lrt">BRT / Le champs <code>TimeStamp</code></h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champs <code>TimeStamp</code> est une marque de temps qui donne un ordre temporel aux liens. Ce champs peut
            être une date et une heure au format <a class="external text" title="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    href="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    rel="nofollow">ISO8601</a> ou simplement un compteur incrémental.
        </p>

        <h3 id="lra">BRA / Le champs <code>Action</code></h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champs <code>Action</code> détermine la façon dont le lien doit être utilisé.</p>
        <p>Quand on parle du type d’un lien, on fait référence à son champs <code>Action</code>.</p>
        <p>L’interprétation de ce champ est limité au premier caractère. Des caractères alpha-numériques supplémentaires
            sont autorisés mais ignorés.</p>
        <p>Cette interprétation est basée sur un vocabulaire particulier. Ce vocabulaire est spécifique à <i>nebule
                v1.2</i> (et <i>nebule v1.1</i>).</p>
        <p>Le vocabulaire ne reconnaît que les 8 caractères <code>l</code>, <code>f</code>, <code>u</code>,
            <code>d</code>, <code>e</code>, <code>x</code>, <code>k</code> et <code>s</code>, en minuscule.</p>

        <h4 id="lral">BRAL / Action <code>l</code> – Lien entre objets</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Met en place une relation entre deux objets. Cette relation a un sens de mise en place et peut être pondérée
            par un objet méta.</p>
        <p>Les liens de type <code>l</code> ne devraient avoir ni <code>HashMeta</code> nul ni <code>HashCible</code>
            nul.</p>

        <h4 id="lraf">BRAF / Action <code>f</code> – Dérivé d’objet</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le nouvel objet est considéré comme enfant ou parent suivant le sens du lien.</p>
        <p>Le champs <code>ObjetMeta</code> doit être vu comme le contexte du lien. Par exemple, deux objets contenants
            du texte peuvent être reliés simplement sans contexte, c'est-à-dire reliés de façon simplement hiérarchique.
            Ces deux mêmes textes peuvent être plutôt (ou en plus) reliés avec un contexte comme celui d’une discussion
            dans un blog. Dans ce deuxième cas, la relation entre les deux textes n’a pas de sens en dehors de cette
            discussion sur ce blog. Il est même probable que le blog n’affichera pas les autres textes en relations si
            ils n’ont pas un contexte appartenant à ce blog.</p>
        <p><code>f</code> comme <i>fork</i>.</p>

        <h4 id="lrau">BRAU / Action <code>u</code> – Mise à jour d’objet</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Mise à jour d’un objet dérivé qui remplace l’objet parent.</p>
        <p><code>u</code> comme <i>update</i>.</p>

        <h4 id="lrad">BRAD / Action <code>d</code> – Suppression d’objet</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’objet est marqué comme à supprimer d’un ou de tous ses emplacements de stockage.</p>
        <p><code>d</code> comme <i>delete</i>.</p>
        <p>Le champs <code>HashCible</code> <span style="text-decoration: underline;">peut</span> être nuls, c'est-à-dire égal à <code>0</code>.
            Si non nul, ce champ doit contenir une entité destinataire de <i>l’ordre</i> de
            suppression. C’est utilisé pour demander à une entité relaie de supprimer un objet spécifique. Cela peut
            être utilisé pour demander à une entité en règle générale de bien vouloir supprimer l’objet, ce qui n’est
            pas forcément exécuté.</p>
        <p>Le champs <code>HashMeta</code> <span style="text-decoration: underline;">doit</span> être nuls, c'est-à-dire
            égal à <code>0</code>.</p>
        <p>Un lien de suppression sur un objet ne veut pas forcément dire qu’il a été supprimé. Même localement, l’objet
            est peut-être encore présent. Si le lien de suppression vient d’une autre entité, on ne va sûrement pas par
            défaut en tenir compte.</p>
        <p>Lorsque le lien de suppression est généré, le serveur sur lequel est généré le lien doit essayer par défaut
            de supprimer l’objet. Dans le cas d’un serveur hébergeant plusieurs entités, un objet ne sera pas supprimé
            s'il est encore utilisé par une autre entité, c'est-à-dire si une entité a un lien qui le concerne et n’a
            pas de lien de suppression.</p>

        <h4 id="lrae">BRAE / Action <code>e</code> – Équivalence d’objets</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Définit des objets jugés équivalents, et donc interchangeables par exemple pour une traduction.</p>

        <h4 id="lrac">BRAC / Action <code>c</code> – Chiffrement de lien</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Ce lien de dissimulation contient un lien dissimulé sans signature. Il permet d’offusquer des liens entre
            objets et donc d’anonymiser certaines actions de l’entité (cf <a href="#ckl">CKL</a>).</p>
        <p>Le champs <code>HashSource</code> fait référence à l’entité destinataire du lien, celle qui peut le
            déchiffrer. A part le champ de l’entité signataire, c’est le seul champ qui fait référence à un objet.</p>
        <p>Le champs <code>HashCible</code> ne contient pas la référence d’un objet mais le lien chiffré et encodé en
            hexadécimal. Le chiffrement est de type symétrique avec la clé de session. Le lien offusqué n’a pas grand
            intérêt en lui-même, c’est le lien déchiffré qui en a.</p>
        <p>Le champs <code>HashMeta</code> ne contient pas la référence d’un objet mais la clé de chiffrement du lien,
            dite clé de session. Cette clé est chiffrée (asymétrique) pour l’entité destinataire et encodée en
            hexadécimal. Chaque entité destinataires d'un lien de dissimulé doit disposer d'un lien de dissimulation
            qui lui est propre.</p>
        <p>Lors du traitement des liens, si une entité est déverrouillée, les liens offusqués pour cette entité doivent
            être déchiffrés et utilisés en remplacement des liens offusqués originels. Les liens offusqués doivent être
            vérifiés avant déchiffrement. Les liens déchiffrés doivent être vérifiés avant exploitation.</p>
        <p>Les liens de dissimulations posent un problème pour être efficacement utilisés par les entités émettrices et
            destinataires. Pour résoudre ce problème sans risquer de révéler les identifiants des objets utilisés dans
            un lien dissimulé, les liens de dissimulation sont attachés à des objets virtuels translatés depuis les
            identifiants des objets originaux (cf <a href="#ld">BD</a>).</p>
        <p>L'option <code>permitObfuscatedLink</code> permet de désactiver la dissimulation (offuscation) des liens des
            objets. Dans ce cas le lien de type <code>c</code> est rejeté comme invalide avec le code erreur 43.</p>

        <h4 id="lrak">BRAK / Action <code>k</code> – Chiffrement d’objet</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Désigne la version chiffrée de l’objet (cf <a href="#cko">CKO</a>).</p>
        <p>L'option <code>permitProtectedObject</code> permet de désactiver la protection (chiffrement) des objets. Dans
            ce cas le lien de type <code>k</code> est rejeté comme invalide avec le code erreur 42.</p>

        <h4 id="lras">BRAS / Action <code>s</code> – Subdivision d’objet</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Désigne un fragment de l’objet.</p>
        <p>Ce champs nécessite un objet méta qui précise l'intervalle de contenu de l’objet d’origine. Le contenu de
            l’objet méta doit être de la forme <code>x-y</code> avec :</p>
        <ul>
            <li><code>x</code> et <code>y</code> exprimé en octet sans zéro et sans unité ;</li>
            <li><code>x</code> strictement supérieur à zéro ;</li>
            <li><code>y</code> strictement inférieur ou égal à la taille de l’objet (lien vers
                <i>nebule/objet/taille</i>) ;
            </li>
            <li><code>x</code> inférieur à <code>y</code> ;</li>
            <li>sans espace, tabulation ou retour chariot.</li>
        </ul>

        <h4 id="lrax">BRAX / Action <code>x</code> – Suppression de lien</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Supprime un ou plusieurs liens précédemment mis en place.</p>
        <p>Les liens concernés par la suppression sont les liens antérieurs de type <code>l</code>, <code>f</code>,
            <code>u</code>, <code>d</code>, <code>e</code>, <code>k</code> et <code>s</code>. Ils sont repérés par les 3
            derniers champs, c'est-à-dire sur <code>HashSource_HashCible_HashMeta</code>. Les champs nuls sont
            strictement pris en compte.</p>
        <p>Le champs <code>TimeStamp</code> permet de déterminer l’antériorité du lien et donc de déterminer sa
            suppression ou pas.</p>
        <p>C’est la seule action sur les liens et non sur les objets.</p>

        <h4 id="lrhs">BRHS / Le champs <code>HashSource</code></h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champs <code>HashSource</code> désigne l’objet source du lien.</p>
        <p>Le champs <code>signataire</code> ne contient que des caractères hexadécimaux, c'est-à-dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>

        <h4 id="lrhc">BRHC / Le champs <code>HashCible</code></h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champs <code>HashCible</code> désigne l’objet destination du lien.</p>
        <p>Le champs <code>signataire</code> ne contient que des caractères hexadécimaux, c'est-à-dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c'est-à-dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <h4 id="lrhm">BRHM / Le champs <code>HashMeta</code></h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champs <code>HashMeta</code> désigne l’objet contenant une caractérisation du lien entre l’objet source et
            l’objet destination.</p>
        <p>Le champs <code>signataire</code> ne contient que des caractères hexadécimaux, c'est-à-dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c'est-à-dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <h2 id="l1">B1 / Lien simple</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien simple a ses champs <code>HashCible</code> et <code>HashMeta</code> égaux à «&nbsp;0&nbsp;».
        </p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_0_0</code></p>

        <h2 id="l2">B2 / Lien double</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien double a son champs <code>HashMeta</code> égal à «&nbsp;0&nbsp;».</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_0</code></p>

        <h2 id="l3">B3 / Lien triple</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien triple est complètement utilisé.</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>

        <h2 id="ls">BS / Stockage</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Tous les liens sont stockés dans un même emplacement où sont visibles comme étant dans un même emplacement.
            Cet emplacement ne contient pas les contenus des objets (cf <a href="#oos">OOS</a>).</p>
        <p>Le lien dissimulé est stocké dans le même emplacement mais dispose de fichiers de stockages différents du
            fait de la spécificité (cf <a href="#lsds">BSDS</a>).</p>

        <h3 id="lsa">BSA / Arborescence</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Sur un système de fichiers, tous les liens sont stockés dans des fichiers contenus dans le dossier <code>pub/l/</code>
            (<code>l</code> comme lien).</p>
        <p>A faire...</p>

        <h3 id="lsd">BSD / Dissimulation</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le lien de dissimulation, de type <code>c</code>, contient un lien dissimulé sans signature (cf <a
                href="#lrac">BRAC</a>). Il permet d’offusquer des liens entre objets et donc d’anonymiser certaines
            actions de l’entité (cf <a href="#ckl">CKL</a>).</p>

        <h5 id="lsdrp">BSDRP / Registre public</h5>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien de dissimulation, public par nature, est conforme au registre des autres liens (cf <a
                href="#lr">BR</a>). Si ce lien ne respectait pas cette structure, il serait automatiquement ignoré ou
            rejeté. Son stockage et sa transmission ont cependant quelques particularités.</p>
        <p>Les champs <code>Signature</code> (cf <a href="#lrsi">BRSI</a>) et <code>HashSignataire</code> (cf <a
                href="#lrhsi">BRHSI</a>) du registre sont conformes aux autres liens. Ils assurent la protection du
            lien. Le champ signataire fait office d'émeteur du lien dissimulé.</p>
        <p>Le champs <code>TimeStamp</code> (cf <a href="#lrt">BRT</a>) du registre est conformes aux autres liens. Il a
            cependant une valeur non significative et sourtout pas liée au <code>TimeStamp</code> du lien dissimulé.</p>
        <p>Le champs <code>Action</code> (cf <a href="#lrt">BRT</a>) du registre est de type <code>c</code> (cf <a
                href="#lra">BRA</a> et <a href="#lrac">BRAC</a>).</p>
        <p>Le champs <code>HashSource</code> fait référence à l’entité destinataire du lien, celle qui peut le
            déchiffrer. A part le champ de l’entité signataire, c’est le seul champ qui fait référence à un objet.</p>
        <p>Le champs <code>HashCible</code> ne contient pas la référence d’un objet mais le lien chiffré et encodé en
            hexadécimal. Le chiffrement est de type symétrique avec la clé de session. Le lien offusqué n’a pas grand
            intérêt en lui-même, c’est le lien déchiffré qui en a.</p>
        <p>Le champs <code>HashMeta</code> ne contient pas la référence d’un objet mais la clé de chiffrement du lien,
            dite clé de session. Cette clé est chiffrée (asymétrique) pour l’entité destinataire et encodée en
            hexadécimal. Chaque entité destinataires d'un lien de dissimulé doit disposer d'un lien de dissimulation
            qui lui est propre.</p>
        <p>Le registre du lien de dissimulation :</p>
        <ul>
            <li>Signature du lien</li>
            <li>Identifiant du signataire</li>
            <li>Horodatage non significatif</li>
            <li>action : <code>c</code></li>
            <li>source : hash(destinataire)</li>
            <li>cible : Lien dissimulé chiffré</li>
            <li>méta : clé de déchiffrement du lien, chiffrée pour le destinataire</li>
        </ul>

        <h5 id="lsdrd">BSDRD / Registre dissimulé</h5>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien dissimulé est la partie utile du lien qui est protégée dans le lien de dissimulation.</p>
        <p>L'extraction du lien dissimulé se fait depuis le lien de dissimulation :</p>
        <ol>
            <li>L'entité destinataire vérifie que son identifiant est bien celui présenté par le champs
                <code>HashSource</code>.
            </li>
            <li>Le champs <code>HashMeta</code> est déchiffré (asymétrique) avec la clé privée de l'entité destinataire
                pour obtenir la clé de session.
            </li>
            <li>Le champs <code>HashCible</code> est déchiffré (symétrique) avec la clé de session pour obtenir le lien
                dissimulé.
            </li>
            <li>Le lien dissimulé obtenu ne contient pas les champs <code>Signature</code> et
                <code>HashSignataire</code> mais on peut garder ceux du lien de dissimulation 'pour affichage'.
            </li>
        </ol>
        <p>A faire...</p>
        <p>Le registre du lien dissimulé :</p>
        <ul>
            <li>Horodatage significatif</li>
            <li>action : tout sauf <code>c</code></li>
            <li>source : hash(objet source)</li>
            <li>cible : hash(objet cible)</li>
            <li>méta : hash(objet méta)</li>
        </ul>

        <h4 id="lsda">BSDA / Attaque sur la dissimulation</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le fait qu’une entité synchronise des liens dissimulés que d’autres entités partagent et les range dans des
            fichiers transcodés peut révéler l’ID de l’objet transcodé. Et par tâtonnement on peut retourner ainsi le
            transcodage de tous les objets.</p>
        <p>Il suffit qu’une entité attaquante génère un lien dissimulé à destination d’une entité attaquée concernant un
            objet en particulier. L’entité attaquée va alors ranger le lien dissimulé dans le fichier transcodé.
            L’entité attaquante peut alors rechercher quel fichier transcodé contient sont lien dissimulé et en déduire
            que ce fichier transcodé correspond à l’objet.</p>
        <p>En plus, si le lien dissimulé n’a aucune action valable, il ne sera pas exploité, donc pas détecté par
            l’entité attaquée.</p>
        <p>La solution implémentée pour palier à ce problème c'est la méthode dite de translation des liens
            dissimulés.</p>

        <h4 id="lsds">BSDS / Stockage et transcodage</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les liens dissimulés sont camouflés dans des liens de dissimulation, ils ne sont donc plus utilisables pour
            assurer le transfert entre entités et le tri dans les fichiers de stockage des liens.</p>
        <p>De plus, les liens de dissimulations ne doivent pas être stockés directement dans des fichiers de stockage
            des liens directement rattachés aux objets concernés, comme les autres liens, sous peine de dévoiler assez
            rapidement les identifiants des objets utilisés... et donc assez facilement le lien dissimulé correspondant.
            Cela poserait en plus un problème lors du nettoyage des liens parce qu'il faut avoir accès aux liens
            dissimulés pour correctement les ranger.</p>
        <p>Le nommage des fichiers contenant ces liens doit aussi être différent des entités signataires et
            destinataires des liens, et ce nommage peut par facilité faire référence simultanément à ces deux entités.
            Ainsi ces fichiers sont stockés dans le dossier des liens. Cette organisation et cette séparation des liens
            dans des fichiers clairement distincts répond au besoin d'utilisation. Et lors du nettoyage des liens, le
            traitement peut être différencié par rapport à la structure du nom des fichiers.</p>

        <h5 id="lsdst">BSDST / Translation de lien</h5>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La répartition des liens de dissimulation dans des fichiers attachés à l'entité émettrice et l'entité
            destinataire ne permet pas une exmploitation efficace et rapide des liens dissimulés. Il faut trouver un
            moyen d'associer les liens de dissimulations aux objets concernés par les liens dissimulés sans révéler
            publiquement ce lien. Une translation va permettre de camoufler cette association.</p>
        <p>La translation des liens dissimulés signifie la dissimulation par translation des identifiants des objets
            auxquels s'appliquent des liens dissimulés moyennant une clé de translation. Cette translation doit
            permettre de préserver la dissociation entre l'identifiant d'un objet et l'identifiant 'virtuel' auquel sont
            attachés les liens dissimulés.</p>
        <p>Le système de translation est basé sur une clé unique de translation par entité. Cette translation doit être
            une fonction à sens unique, donc à base de prise d’empreinte (hash). Elle doit maintenir la non association
            entre identifiants virtuels et réels des objets, y compris lorsqu’une ou plusieurs translations sont
            connues. Enfin, la translation doit être dépendante de l’entité qui les utilise, c’est à dire qu’une même
            clé peut être commune à plusieurs entités sans donner les mêmes translations.</p>
        <p>A faire...</p>

        <h5 id="lsdsp">BSDSP / Protection de translation</h5>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="lsdt">BSDT / Transfert et partage</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="lsdc">BSDC / Compromission</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h2 id="lt">BT / Transfert</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h2 id="lv">BV / Vérification</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La signature d’un lien doit être vérifiée lors de la fin de la réception du lien. La signature d’un lien
            devrait être vérifiée avant chaque utilisation de ce lien. Un lien avec une signature invalide doit être
            supprimé. Lors de la suppression d’un lien, les autres liens de cet objet ne sont pas supprimés et l'objet
            n'est pas supprimé. La vérification de la validité des objets est complètement indépendante de celle des
            liens, et inversement (cf <a href="#cl">CL</a> et <a href="#oov">OOV</a>).</p>
        <p>Toute modification de l’un des champs du lien entraîne l’invalidation de tout le lien.</p>

        <h2 id="lo">BO / Oubli</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}
