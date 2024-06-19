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
    const LINK_MAX_RL_SIZE = 4096;

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

        $this->_metrology->addLog(substr($blocLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

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
        $this->_metrology->addLog(substr($link, 0, 512), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

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
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        return $this->_rawBlocLink;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getLinks()
     * @return array:linkInterface
     */
    public function getLinks(): array
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        return $this->_links;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getSigners()
     * @return array
     */
    public function getSigners(): array
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

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
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        return $this->_parsedLink;
    }

    /**
     * Get if the link have been checked entirely.
     * @return boolean
     */
    public function getCheckCompleted(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        return $this->_checkCompleted;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getValid()
     * @return boolean
     */
    public function getValid(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        return $this->_valid;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getValidStructure()
     * @return boolean
     */
    public function getValidStructure(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        return $this->_validStructure;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getSigned()
     * @return boolean
     */
    public function getSigned(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        return $this->_signed;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getVersion()
     * @return string
     */
    public function getVersion(): string
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        /*if (!isset($this->_parsedLink['bl/rv']))
            return '';

        return $this->_parsedLink['bl/rv'];*/
        return $this->_parsedLink['bl/rv'] ?: '';
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getDate()
     * @return string
     */
    public function getDate(): string
    {
        $this->_metrology->addLog(substr($this->_rawBlocLink, 0, 256), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        //if (!isset($this->_parsedLink['bl/rc/chr']))
        //    return '';

        //return $this->_parsedLink['bl/rc/chr'];
        return $this->_parsedLink['bl/rc/chr'] ?: '';
    }



    /**
     * Check block BH on link.
     *
     * @param string $bh
     * @return bool
     */
    protected function _checkBH(string &$bh): bool
    {
        $this->_metrology->addLog(substr($bh, 0, 512), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        if (strlen($bh) > 15)
        {
            $this->_metrology->addLog('BH size overflow '.substr($bh, 0, 1000) . '+',
                Metrology::LOG_LEVEL_ERROR, __METHOD__, '84cf4447');
            return false;
        }

        $rf = strtok($bh, '/');
        if (is_bool($rf)) return false;
        $rv = strtok('/');
        if (is_bool($rv)) return false;

        // Check bloc overflow
        if (strtok('/') !== false) return false;

        // Check RF and RV.
        if (!$this->_checkRF($rf)) $this->_metrology->addLog('check link BH/RF failed '.$bh,
            Metrology::LOG_LEVEL_ERROR, __METHOD__, '3c0b5c4f');
        if (!$this->_checkRF($rf)) return false;
        if (!$this->_checkRV($rv)) $this->_metrology->addLog('check link BH/RV failed '.$bh,
            Metrology::LOG_LEVEL_ERROR, __METHOD__, '80c5975c');
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
        $this->_metrology->addLog(substr($rf, 0, 512), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

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
        $this->_metrology->addLog(substr($rv, 0, 512), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

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
        $this->_metrology->addLog(substr($bl, 0, 512), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        if (strlen($bl) > self::LINK_MAX_BL_SIZE)
        {
            $this->_metrology->addLog('BL size overflow '.substr($bl, 0, 1000) . '+',
                Metrology::LOG_LEVEL_ERROR, __METHOD__, '9d3f9bda');
            return false;
        }

        $rc = strtok($bl, '/');
        if (is_bool($rc)) return false;
        if (!$this->_checkRC($rc)) $this->_metrology->addLog('check link BL/RC failed '.$bl,
            Metrology::LOG_LEVEL_ERROR, __METHOD__, '86a58996');
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
                $this->_metrology->addLog('BL overflow '.substr($bl, 0, 1000) . '+ maxRL='
                    . $this->_maxRL, Metrology::LOG_LEVEL_ERROR, __METHOD__, '6a777706');
                return false;
            }
            $rl = strtok('/');
        }
        foreach ($list as $i => $rl)
        {
            if (!$this->_checkRL($rl, (string)$i)) $this->_metrology->addLog('check link BL/RL failed '.$bl,
            Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd865ee87');
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
        $this->_metrology->addLog(substr($rc, 0, 512), Metrology::LOG_LEVEL_FUNCTION,
            __METHOD__, '1111c0de');

        if (strlen($rc) > 27)
        {
            $this->_metrology->addLog('BL/RC size overflow '.substr($rc, 0, 1000) . '+',
                Metrology::LOG_LEVEL_ERROR, __METHOD__, '7282e54a');
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
            $this->_metrology->addLog('BL/RC overflow '.substr($rc, 0, 1000) . '+',
                Metrology::LOG_LEVEL_ERROR, __METHOD__, '84cf4447');
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
        $this->_metrology->addLog(substr($rl, 0, 512) . ' / ' . $i,
            Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($rl) > blocLink::LINK_MAX_RL_SIZE)
        {
            $this->_metrology->addLog('BL/RL size overflow '.substr($rl, 0, 1000) . '+',
                Metrology::LOG_LEVEL_ERROR, __METHOD__, '4835c147');
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
                $this->_metrology->addLog('BL/RL overflow '.substr($rl, 0, 1000) . '+ maxRLUID='
                    . $this->_maxRLUID, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd0c9961a');
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
        $this->_metrology->addLog(substr($req, 0, 512) . ' / ' . $i,
            Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrology->addLog(substr($bh_bl, 0, 256) . ' / ' . substr($bs, 0, 512),
            Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($bs) > self::LINK_MAX_BS_SIZE)
        {
            $this->_metrology->addLog('BS size overflow '.substr($bs, 0, 1000) . '+',
                Metrology::LOG_LEVEL_ERROR, __METHOD__, 'efc551d7');
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
                $this->_metrology->addLog('BS overflow '.substr($bs, 0, 1000) . '+',
                    Metrology::LOG_LEVEL_ERROR, __METHOD__, '9f2e670f');
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
        $this->_metrology->addLog(substr($bh_bl, 0, 256) . ' / ' . substr($rs, 0, 512)
            . ' / ' . $i, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrology->addLog(substr($bh_bl, 0, 512) . ' / ' . $sig . ' / ' . $nid . ' / ' . $i,
            Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrology->addLog($oid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrology->addLog('sign ' . substr($this->_rawBlocLink, 0, 128),
            Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'b6e89674');

        // Si autorisé à signer.
        if (!$this->_newLink
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Can not sign link',
                Metrology::LOG_LEVEL_DEBUG, __METHOD__, '09c33dba');
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
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Sign link for ' . $publicKeyID,
                Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd3c9521d');

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
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Invalid link',
                Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cd989943');
        return false;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::write()
     * @return boolean
     */
    public function write(): bool
    {
        $this->_metrology->addLog('write ' . substr($this->_rawBlocLink, 0, 128),
            Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '50faf214');

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
                            $this->_io->setBlockLink($this->_parsedLink['bs/rs'.$j.'/eid'] . '-'
                                . $this->_parsedLink['bl/rl' . $i . '/nid1'], $this->_rawBlocLink);
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
        $this->_metrology->addLog('sign write ' . substr($this->_rawBlocLink, 0, 128),
            Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '51a338d4');

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

        <li><a href="#b">B / Bloc de liens</a>
            <ul>
                <li><a href="#bd">BD / Description</a></li>
                <li><a href="#bc">BC / Construction</a>
                    <ul>
                        <li><a href="#bcb">BCB / Blocs</a>
                            <ul>
                                <li><a href="#bcbh">BCBH / Bloc d'entête</a></li>
                                <li><a href="#bcbl">BCBL / Bloc de liens</a></li>
                                <li><a href="#bcbs">BCBS / Bloc de signature</a></li>
                            </ul>
                        </li>
                        <li><a href="#bcr">BCR / Registres</a>
                            <ul>
                                <li><a href="#bcrf">BCRF / Registre de forme</a></li>
                                <li><a href="#bcrv">BCRV / Registre de version</a></li>
                                <li><a href="#bcrc">BCRC / Registre de chronologie</a></li>
                                <li><a href="#bcrl">BCRL / Registre de liens</a></li>
                                <li><a href="#bcrs">BCRS / Registre de signature</a></li>
                            </ul>
                        </li>
                        <li><a href="#bce">BCE / Eléments</a>
                            <ul>
                                <li><a href="#bceapp">BCEAPP / Application</a></li>
                                <li><a href="#bcetyp">BCETYP / Type de contenu</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#bt">BT / Transfert</a></li>
                <li><a href="#bv">BV / Vérification</a></li>
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

        <h1 id="b">B / Bloc de liens</h1>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p style="color:red;">Cette partie est périmée avec la nouvelle version de liens !</p>
        <p>Le lien est la matérialisation dans un graphe d’une relation entre deux objets pondéré par un troisième
            objet.</p>

        <h2 id="bd">BD / Description</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h2 id="bc">BC / Construction</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="bcb">BCB / Blocs</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="bcbh">BCBH / Bloc d'entête</h4>
        <p>Le bloc d'entête contient les registres <a href="#bcrf">RF</a> et <a href="#bcrv">RV</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="bcbl">BCBL / Bloc de liens</h4>
        <p>Le bloc de liens contient les registres <a href="#bcrc">RC</a> et <a href="#bcrl">RL</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="bcbs">BCBS / Bloc de signature</h4>
        <p>Le bloc d'entête contient les registres <a href="#bcrs">RS</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="bcr">BCR / Registres</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="bcrf">BCRF / Registre de forme</h4>
        <p>Le registre de forme est le premier registre du <a href="#BCBH">bloc d'entête</a>.
            Il contient les éléments <a href="#bceapp">APP</a> et <a href="#bcetyp">TYP</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="bcrv">BCRV / Registre de version</h4>
        <p>Le registre de version est le second et dernier registre du <a href="#BCBH">bloc d'entête</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="bcrc">BCRC / Registre de chronologie</h4>
        <p>Le registre de chronologie est le premier registre du <a href="#BCBL">bloc de liens</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="bcrl">BCRL / Registre de liens</h4>
        <p>Le registre de liens est le second registre et les suivants du <a href="#BCBL">bloc de liens</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="bcrs">BCRS / Registre de signature</h4>
        <p>Le registre de signature est le ou les registres du <a href="#BCBL">bloc de signature</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="bce">BCE / Eléments</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="bceapp">BCEAPP / Application</h4>
        <p>L'élément application (APP) est le premier élément du <a href="#BCBH">Registre de forme</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h2 id="bt">BT / Transfert</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h2 id="bv">BV / Vérification</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php
    }
}
