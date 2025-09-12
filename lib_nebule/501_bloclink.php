<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class BlocLink extends Functions implements blocLinkInterface
{
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
        '_lid',
    );

    const LINK_VERSION = '2:0';
    const NID_MIN_HASH_SIZE = 64;
    const NID_MIN_NONE_SIZE = 8;
    const NID_MAX_HASH_SIZE = 8192;
    const NID_MIN_ALGO_SIZE = 2;
    const NID_MAX_ALGO_SIZE = 12;
    const LINK_MAX_BL_SIZE = 16384;
    const LINK_MAX_BS_SIZE = 16384;
    const LINK_MAX_RL_SIZE = 4096;

    protected string $_rawBlocLink = '';
    protected string $_linksType = '';
    protected array $_links = array();
    protected array $_parsedLink = array();
    protected bool $_isNewLink = false;
    protected int $_newLinkCount = 0;
    protected bool $_checkCompleted = false;
    protected bool $_valid = false;
    protected bool $_validStructure = false;
    protected bool $_signed = false;
    protected int $_maxRL = 1;
    protected int $_maxRLUID = 3;
    protected int $_maxRS = 1;
    protected ?string $_lid = null;
    private string $_newBL = '';

    public function __construct(nebule $nebuleInstance, string $blocLink, string $linkType = Cache::TYPE_LINK)
    {
        parent::__construct($nebuleInstance);
        $this->setEnvironmentLibrary($nebuleInstance);

        $this->_linksType = $linkType;
        $this->_maxRL = $this->_configurationInstance->getOptionAsInteger('linkMaxRL');
        $this->_maxRLUID = $this->_configurationInstance->getOptionAsInteger('linkMaxRLUID');
        $this->_maxRS = $this->_configurationInstance->getOptionAsInteger('linkMaxRS');

        $this->_metrologyInstance->addLog(substr($blocLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_metrologyInstance->addLinkRead();

        if ($blocLink == 'new')
            $this->_new();
        else
            $this->_parse($blocLink);

        $this->initialisation();
    }

    public function __toString()
    {
        return $this->_rawBlocLink;
    }

    public function __sleep()
    {
        return self::SESSION_SAVED_VARS;
    }

    public function __wakeup()
    {
        /*global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrologyInstance = $nebuleInstance->getMetrologyInstance();
        $this->_configurationInstance = $nebuleInstance->getConfigurationInstance();
        $this->_ioInstance = $nebuleInstance->getIoInstance();
        $this->_cryptoInstance = $nebuleInstance->getCryptoInstance();*/
    }

    protected function _initialisation(): void
    {
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
        $this->_metrologyInstance->addLog('parse ' . substr($link, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_lid = $this->_cryptoInstance->hash($link);

        $this->_validStructure = false;
        if (strlen($link) > (self::LINK_MAX_BL_SIZE + self::LINK_MAX_BS_SIZE + 1)) return false;
        if (strlen($link) == 0) return false;

        // Extract blocs from link L : BH_BL_BS
        $bh = strtok(trim($link), '_');
        if (is_bool($bh)) return false;
        //$this->_metrologyInstance->addLog('check link BH=' . $bh, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '36e5871a');
        $bl = strtok('_');
        if (is_bool($bl)) return false;
        //$this->_metrologyInstance->addLog('check link BL=' . $bl, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'dc1eb20f');
        $bs = strtok('_');
        if (!$this->_isNewLink && is_bool($bs)) return false;
        //$this->_metrologyInstance->addLog('check link BS=' . $bs, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '41e23a37');

        // Check link overflow
        if (strtok('_') !== false) return false;

        // Check BH, BL and BS.
        if (!$this->_checkBH($bh)) {
            $this->_metrologyInstance->addLog('check link BH failed ' . $link, Metrology::LOG_LEVEL_ERROR, __METHOD__, '80cbba4b');
            return false;
        }
        if (!$this->_checkBL($bl)) {
            $this->_metrologyInstance->addLog('check link BL failed ' . $link, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c5d22fda');
            return false;
        }
        $bh_bl = $bh . '_' . $bl;
        // Do not check on new link before sign.
        if (!$this->_isNewLink && !$this->_checkBS($bh_bl, $bs)) {
            $this->_metrologyInstance->addLog('check link BS failed '.$link, Metrology::LOG_LEVEL_ERROR, __METHOD__, '2828e6ae');
            return false;
        }

        $this->_parsedLink['link'] = $link;
        $this->_validStructure = true;
        $this->_checkCompleted = true;
        if ($this->_signed) $this->_valid = true;
        return true;
    }

    protected function _new(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_rawBlocLink = 'nebule:link/' . $this->_configurationInstance->getOptionAsString('defaultLinksVersion') . '_';
        $this->_newBL = '';
        $this->_isNewLink = true;
        $this->_metrologyInstance->addLog('DEBUGGING 1', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $this->_newLinkCount = 0;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getRaw()
     * @return string
     */
    public function getRaw(): string
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        return $this->_rawBlocLink;
    }
    
    public function getLID(): string
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        return $this->_lid;
    }

    public function getNew(): bool
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        return $this->_isNewLink;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getLinks()
     * @return array:linkInterface
     */
    public function getLinks(): array
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        return $this->_links;
    }

    /**
     * {@inheritDoc}
     * @return array
     *@see blocLinkInterface::getSignersEID()
     */
    public function getSignersEID(): array
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        return $this->_parsedLink;
    }

    /**
     * Get if the link have been checked entirely.
     * @return boolean
     */
    public function getCheckCompleted(): bool
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        return $this->_checkCompleted;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getValid()
     * @return boolean
     */
    public function getValid(): bool
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        return $this->_valid;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getValidStructure()
     * @return boolean
     */
    public function getValidStructure(): bool
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        return $this->_validStructure;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getSigned()
     * @return boolean
     */
    public function getSigned(): bool
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        return $this->_signed;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::getVersion()
     * @return string
     */
    public function getVersion(): string
    {
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrologyInstance->addLog('LID=' . $this->_lid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrologyInstance->addLog(substr($bh, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($bh) > 15)
        {
            $this->_metrologyInstance->addLog('BH size overflow '.substr($bh, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '84cf4447');
            return false;
        }

        $rf = strtok($bh, '/');
        if (is_bool($rf)) return false;
        $rv = strtok('/');
        if (is_bool($rv)) return false;

        // Check bloc overflow
        if (strtok('/') !== false) return false;

        // Check RF and RV.
        if (!$this->_checkRF($rf)) {
            $this->_metrologyInstance->addLog('check link BH/RF failed ' . $bh, Metrology::LOG_LEVEL_ERROR, __METHOD__, '3c0b5c4f');
            return false;
        }
        if (!$this->_checkRV($rv)) {
            $this->_metrologyInstance->addLog('check link BH/RV failed ' . $bh, Metrology::LOG_LEVEL_ERROR, __METHOD__, '80c5975c');
            return false;
        }

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
        $this->_metrologyInstance->addLog(substr($rf, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrologyInstance->addLog(substr($rv, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrologyInstance->addLog(substr($bl, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($bl) > self::LINK_MAX_BL_SIZE)
        {
            $this->_metrologyInstance->addLog('BL size overflow '.substr($bl, 0, 1000) . '+',
                Metrology::LOG_LEVEL_ERROR, __METHOD__, '9d3f9bda');
            return false;
        }

        $rc = strtok($bl, '/');
        if (is_bool($rc)) return false;
        if (!$this->_checkRC($rc)) {
            $this->_metrologyInstance->addLog('check link BL/RC failed ' . $bl, Metrology::LOG_LEVEL_ERROR, __METHOD__, '86a58996');
            return false;
        }

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
                $this->_metrologyInstance->addLog('BL overflow '.substr($bl, 0, 1000) . '+ maxRL=' . $this->_maxRL, Metrology::LOG_LEVEL_ERROR, __METHOD__, '6a777706');
                return false;
            }
            $rl = strtok('/');
        }
        foreach ($list as $i => $rl)
        {
            if (!$this->_checkRL($rl, (string)$i)) $this->_metrologyInstance->addLog('check link BL/RL failed '.$bl, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd865ee87');
            if (!$this->_checkRL($rl, (string)($i+1))) return false;
            if ($this->_linksType == Cache::TYPE_TRANSACTION)
                $instanceRL = new Transaction($this->_nebuleInstance, $rl, $this);
            else
                $instanceRL = new LinkRegister($this->_nebuleInstance, $rl, $this); // FIXME ne fonctionne pas correctement !
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
        $this->_metrologyInstance->addLog(substr($rc, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($rc) > 27)
        {
            $this->_metrologyInstance->addLog('BL/RC size overflow '.substr($rc, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '7282e54a');
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
            $this->_metrologyInstance->addLog('BL/RC overflow '.substr($rc, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '84cf4447');
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
        $this->_metrologyInstance->addLog(substr($rl, 0, 512) . ' / ' . $i, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($rl) > BlocLink::LINK_MAX_RL_SIZE)
        {
            $this->_metrologyInstance->addLog('BL/RL size overflow '.substr($rl, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '4835c147');
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
                $this->_metrologyInstance->addLog('BL/RL overflow '.substr($rl, 0, 1000) . '+ maxRLUID=' . $this->_maxRLUID, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd0c9961a');
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
        $this->_metrologyInstance->addLog(substr($req, 0, 512) . ' / ' . $i, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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
        $this->_metrologyInstance->addLog(substr($bs, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($bs) > self::LINK_MAX_BS_SIZE)
        {
            $this->_metrologyInstance->addLog('BS size overflow '.substr($bs, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'efc551d7');
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
                $this->_metrologyInstance->addLog('BS overflow '.substr($bs, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '9f2e670f');
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
        $this->_metrologyInstance->addLog(substr($rs, 0, 512) . ' / ' . $i, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($rs) > 4096) return false; // TODO à revoir.

        // Extract items from RS : NID>SIG
        $nid = strtok($rs, '>');
        if (is_bool($nid))
            return false;
        $sig = strtok('>');
        if (is_bool($sig))
            return false;

        // Check registry overflow
        if (strtok('>') !== false) return false;

        // --- --- --- --- --- --- --- --- ---
        // Check content RS 1 NID 1 : hash.algo.size
        if (!Node::checkNID($nid, false)) {
            $this->_metrologyInstance->addLog('check link bs/rs1/eid failed '.$rs, Metrology::LOG_LEVEL_ERROR, __METHOD__, '6e1150f9');
            return false;
        }
        $this->_parsedLink["bs/rs$i/eid"] = $nid;

        if (!$this->_checkSIG($bh_bl, $sig, $nid, $i)) {
            $this->_metrologyInstance->addLog('check link BS/RS1/SIG failed '.$rs, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e99ec81f');
            return false;
        }

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
        $this->_metrologyInstance->addLog($sig . ' / ' . $nid . ' / ' . $i, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

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

        if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckSignOnVerify')) return true;
        if ($this->_checkObjectContent($nid)) {
            $hash = $this->_cryptoInstance->hash($bh_bl, $algo . '.' . $size);
            $publicKey = $this->_ioInstance->getObject($nid);

            if ($this->_cryptoInstance->verify($hash, $sign, $publicKey))
            {
                $this->_parsedLink["bs/rs$i/sig"] = $sig;
                return true;
            }
        }
        return false;
    }

    protected function _checkObjectContent($oid): bool
    {
        $this->_metrologyInstance->addLog($oid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (!Node::checkNID($oid, false)
            || !$this->_ioInstance->checkObjectPresent($oid)
        )
            return false;
        return true;
    }

    /**
     * Add a link (RL) on new bloc of links (BL).
     *
     * @param string $rl
     * @param bool   $obfuscate
     * @return bool
     */
    public function addLink(string $rl, bool $obfuscate = false): bool
    {
        if ($rl == '') {
            $this->_metrologyInstance->addLog('can not add empty link register', Metrology::LOG_LEVEL_ERROR, __METHOD__, '8f65f688');
            return false;
        }

        if (!$this->_isNewLink) {
            $this->_metrologyInstance->addLog('can not add link register on signed bloc link', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'b20294b1');
            return false;
        }

        if ($this->_newLinkCount >= $this->_configurationInstance->getOptionAsInteger('linkMaxRL')) {
            $this->_metrologyInstance->addLog('can not add new link, limited by linkMaxRL', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c7aac0dd');
            return false;
        }

        $instance = new LinkRegister($this->_nebuleInstance, $rl, $this);
        if ($instance->getValidStructure())
        {
            if ($obfuscate)
                $instance->setObfuscate();
            $this->_newBL .= '/' . $rl;
            $this->_newLinkCount++;
        }
        $this->_lid = $this->_cryptoInstance->hash($this->_rawBlocLink);

        return true;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::sign()
     * @param ?Entity $publicKey
     * @param string $date
     * @return boolean
     */
    public function sign(?Entity $publicKey = null, string $date = '', string $privateKey = '', string $password = ''): bool
    {
        $this->_metrologyInstance->addLog('sign ' . substr($this->_rawBlocLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (!$this->_isNewLink) {
            $this->_metrologyInstance->addLog('can not sign link already signed ' . $this->_lid, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f7433d1d');
            return false;
        }

        if (!$this->_configurationInstance->getOptionAsBoolean('permitCreateLink')) {
            $this->_metrologyInstance->addLog('can not sign link, permitCreateLink=false', Metrology::LOG_LEVEL_ERROR, __METHOD__, '976483ad');
            return false;
        }

        if ($this->_newLinkCount == 0) {
            $this->_metrologyInstance->addLog('can not sign empty link', Metrology::LOG_LEVEL_ERROR, __METHOD__, '09c33dba');
            return false;
        }

        if ($date == '')
            $date = '0>0' . date('YmdHis');

        // Prepare new link to sign.
        $this->_rawBlocLink .= $date . $this->_newBL;
        $this->_parse($this->_rawBlocLink);
        $this->_isNewLink = false;

        if (!$this->_validStructure) {
            $this->_metrologyInstance->addLog('can not sign invalid link', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'cd989943');
            return false;
        }

        if ($publicKey === null || $publicKey->getID() == '0')
            $publicKey = $this->_entitiesInstance->getConnectedEntityInstance();
        if ($publicKey === null)
            return false;
        $publicKeyID = $publicKey->getID();
        if ($publicKeyID == '0')
            return false;
        //$this->_metrologyInstance->addLog('sign link for ' . $publicKeyID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd3c9521d');

        $hashAlgo = $this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm');

        $sign = $publicKey->signLink($this->_rawBlocLink, $hashAlgo, $privateKey, $password);
        if ($sign !== null) {
            $bs = $publicKeyID . '>' . $sign;
            $this->_parsedLink['bs'] = $bs;
            if (!$this->_checkBS($this->_rawBlocLink, $bs))
                return false;
            $this->_rawBlocLink .= '_' . $bs;
            $this->_parsedLink['link'] = $this->_rawBlocLink;
            $this->_signed = true;
            $this->_valid = true;
            $this->_lid = $this->_cryptoInstance->hash($this->_rawBlocLink);
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     * @see blocLinkInterface::write()
     * @return boolean
     */
    public function write(): bool
    {
        $this->_metrologyInstance->addLog('write ' . substr($this->_rawBlocLink, 0, 128), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (!$this->_configurationInstance->getOptionAsBoolean('permitWrite')
            || !$this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            || !$this->_valid
            || !$this->_signed
        )
            return false;

        $this->_metrologyInstance->addAction('addlnk', $this->_rawBlocLink, $this->_valid);

        // If needed, in history.
        if ($this->_configurationInstance->getOptionAsBoolean('permitHistoryLinksSign'))
            $this->_ioInstance->setBlockLink(References::HISTORY_FILE, $this->_rawBlocLink);

        // If needed, in signers.
        for ($j = 0; $j > $this->_maxRS; $j++) {
            if (isset($this->_parsedLink['bs/rs'.$j.'/eid'])) {
                //$this->_metrologyInstance->addLog('add link on bs/rs' . $j . '/eid' . $j, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e8b0aea5');
                $this->_ioInstance->setBlockLink($this->_parsedLink['bs/rs' . $j . '/eid'], $this->_rawBlocLink);
            } else
                break;
        }

        for ($i = 0 ; $i < $this->_maxRL; $i++) {
            if (isset($this->_parsedLink['bl/rl' . $i . '/req'])) {
                if ($this->_parsedLink['bl/rl' . $i . '/req'] != 'c') {
                    for ($j = 0; $j < $this->_maxRLUID; $j++) {
                        if (isset($this->_parsedLink['bl/rl' . $i . '/nid' . $j])) {
                            //$this->_metrologyInstance->addLog('add link on bl/rl' . $i . '/nid' . $j, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c2b1d5ff');
                            $this->_ioInstance->setBlockLink($this->_parsedLink['bl/rl' . $i . '/nid' . $j], $this->_rawBlocLink);
                        }
                    }
                } elseif ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')) { // TODO verify
                    for ($j = 0; $j < $this->_maxRS; $j++) {
                        if (isset($this->_parsedLink['bs/rs' . $j . '/eid']))
                            $this->_ioInstance->setBlockLink($this->_parsedLink['bs/rs' . $j . '/eid'] . '-' . $this->_parsedLink['bl/rl' . $i . '/nid1'], $this->_rawBlocLink);
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
     * @param ?Entity $publicKey
     * @param string $date
     * @return boolean
     */
    public function signWrite(?Entity $publicKey = null, string $date = '', string $privateKey = '', string $password = ''): bool
    {
        $this->_metrologyInstance->addLog('sign write ' . substr($this->_rawBlocLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if ($this->sign($publicKey, $date, $privateKey, $password)) {
            $this->_metrologyInstance->addLog('signed ' . substr($this->_rawBlocLink, 0, 512), Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e8e59a93');
            return $this->write();
        }
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

        <?php Displays::docDispTitle(1, 'b', 'Bloc de liens'); ?>
        <p>Le bloc de liens permet d'agréger un ou plusieurs registres de liens, de le rendre transferable et de le
            sécuriser. Voir <a href="#l">L</a>.</p>
        <p>On parlera par facilité de bloc de liens BL pour la partie opérationnelle contenant uniquement les registres
            de liens RL. Cependant, le bloc de liens doit être compris aussi avec le bloc d'entête BH permettant sa
            transmission et le loc de signature permettant sa sécurisation. Sa sureté de fonctionnement est gérée par sa
            structure rigide prédéfinit par le bloc d'entête.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p style="color:red;">Cette partie est périmée avec la nouvelle version de liens !</p>
        <p>Le lien est la matérialisation dans un graphe d’une relation entre deux objets pondéré par un troisième
            objet.</p>

        <?php Displays::docDispTitle(2, 'bd', 'Description'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(2, 'bs', 'Structure'); ?>
        <p>Le bloc de liens est enregistré dans une structure avec un format définit et contraint afin de pouvoir être
            stocké et échangé de façon sûre et sécurisée.</p>
        <p>Cette structure a quatre niveaux de profondeur avec pour chaque niveau un séparateur de champs spécifique. Il
            n'y a pas de risque de confusion lors de la navigation dans les champs avec ces séparateurs spécifiques.</p>
        <p>La structure du bloc de liens :</p>
        <ul>
            <li>L : BH_BL_BS
                <ul>
                    <li>BH : RF/RV
                        <ul>
                            <li>RF : APP:TYP
                                <ul>
                                    <li>APP : nebule</li>
                                    <li>TYP : link</li>
                                </ul>
                            </li>
                            <li>RV : VERS:SUB
                                <ul>
                                    <li>VER : 2</li>
                                    <li>SUB : 0</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>BL : RC/RL/RL...
                        <ul>
                            <li>RC : MOD>CHR</li>
                            <li>RL : REQ>NID>NID>NID...
                                <ul>
                                    <li>REQ</li>
                                    <li>NID : hash.algo.size</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>BS : RS/RS...
                        <ul>
                            <li>RS : EID>SIG
                                <ul>
                                    <li>EID : hash.algo.size</li>
                                    <li>SIG : sign.algo.size</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>

        <?php Displays::docDispTitle(2, 'bc', 'Construction'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'bcb', 'Blocs'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'bcbh', "Bloc d'entête"); ?>
        <p>Le bloc d'entête contient les registres <a href="#bcrf">RF</a> et <a href="#bcrv">RV</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'bcbl', 'Bloc de liens'); ?>
        <p>Le bloc de liens contient les registres <a href="#bcrc">RC</a> et <a href="#bcrl">RL</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'bcbs', 'Bloc de signature'); ?>
        <p>Le bloc d'entête contient les registres <a href="#bcrs">RS</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'bcr', 'Registres'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'bcrf', 'Registre de forme'); ?>
        <p>Le registre de forme est le premier registre du <a href="#BCBH">bloc d'entête</a>.
            Il contient les éléments <a href="#bceapp">APP</a> et <a href="#bcetyp">TYP</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'bcrv', 'Registre de version'); ?>
        <p>Le registre de version est le second et dernier registre du <a href="#BCBH">bloc d'entête</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'bcrc', 'Registre de chronologie'); ?>
        <p>Le registre de chronologie est le premier registre du <a href="#BCBL">bloc de liens</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'bcrl', 'Registre de liens'); ?>
        <p>Le registre de liens est le second registre et les suivants du <a href="#BCBL">bloc de liens</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'bcrs', 'Registre de signature'); ?>
        <p>Le registre de signature est le ou les registres du <a href="#BCBL">bloc de signature</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'bce', 'Eléments'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'bceapp', 'Application'); ?>
        <p>L'élément application (APP) est le premier élément du <a href="#BCBH">Registre de forme</a>.</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(2, 'bt', 'Transfert'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(2, 'bv', 'Vérification'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php
    }
}
