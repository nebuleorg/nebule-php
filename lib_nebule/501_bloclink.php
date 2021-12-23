<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\linkInterface;

/**
 * La classe Link.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant le lien ;
 * - un texte contenant la version du lien.
 *
 * Le nombre de champs du lien doit être de 7 exactement.
 * Le champs signature peut être vide ou à 0 si c'est pour un nouveau lien à signer.
 * La signature doit être explicitement demandée par l'appelle de sign() après la création du lien.
 *
 * La version du lien n'est pas prise en compte.
 *
 * Un lien peut être valide si sa structure est correcte, et peut être signé si la signature est valide.
 *
 * Un lien dissimulé peut ne pas pouvoir être lu si l'entité n'est pas destinataire, donc n'a pas accès à la clé de déchiffrement.
 * Il reste dans ce cas géré comme un lien normal mais de type c.
 * Cependant, si l'entité destinataire est déverrouillée mais ne peut déchiffrer le lien, alors le lien est considéré corrompu.
 */
class Bloclink implements linkInterface
{
    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_fullLink',
        '_signe',
        '_signeValue',
        '_signeAlgo',
        '_signed',
        '_hashSigner',
        '_date',
        '_action',
        '_hashSource',
        '_hashTarget',
        '_hashMeta',
        '_obfuscated',
        '_verified',
        '_valid',
        '_validStructure',
        '_verifyNumError',
        '_verifyTextError',
    );

    const LINK_VERSION = '2:0';
    const NID_MIN_HASH_SIZE = 64;
    const NID_MAX_HASH_SIZE = 8192;
    const NID_MIN_ALGO_SIZE = 2;
    const NID_MAX_ALGO_SIZE = 12;

    /**
     * Instance nebule en cours.
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
     * Instance io en cours.
     *
     * @var io
     */
    protected $_io;

    /**
     * Instance crypto en cours.
     *
     * @var CryptoInterface $_crypto
     */
    protected $_crypto;

    /**
     * Instance métrologie en cours.
     *
     * @var Metrology
     */
    protected $_metrology;

    /**
     * Instance de gestion du cache.
     *
     * @var Cache
     */
    protected $_cache;

    /**
     * @var string $_rawBloclink
     */
    protected $_rawBloclink = '';

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
     * Booléen si le lien a été vérifié.
     *
     * @var boolean $_verified
     */
    protected $_verified = false;

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

    /**
     * Nombre représentant un code d'erreur de vérification.
     *
     * @var integer $_verifyNumError
     */
    protected $_verifyNumError = 0;

    /**
     * Texte de la description de l'erreur de vérification.
     *
     * @var string $_verifyTextError
     */
    protected $_verifyTextError = 'Initialisation';

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @param string $bloclink
     * @return boolean
     */
    public function __construct(nebule $nebuleInstance, string $bloclink)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_metrology->addLinkRead();

        // Vérifie la validité du lien.
        if (!$this->_parse($bloclink))
            return false;

        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_rawBloclink;
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
     * Fonction de réveil de l'instance et de ré-initialisation de certaines variables non sauvegardées.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
    }

    /**
     * Link - Verify link consistency.
     * Limites :
     * L : 1 BH + 1 BL + 1 BS
     * BH : 1 RF + 1 RV
     * BL : 1 RC + 1 RL
     * BS : 1 RS
     * RF : 1 APP + 1 TYP
     * APP : 'nebule'
     * TYP : 'link'
     * MOD : '0'
     *
     * @param string $link
     * @return bool
     */
    protected function _parse(string $link):bool
    {
        $this->_validStructure = false;
        if (strlen($link) > 4096) return false; // TODO à revoir.
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
        //if (!$this->_checkBH($bh)) log_add('check link BH failed '.$link, 'error', __FUNCTION__, '80cbba4b');
        if (!$this->_checkBH($bh)) return false;
        //if (!$this->_checkBL($bl)) log_add('check link BL failed '.$link, 'error', __FUNCTION__, 'c5d22fda');
        if (!$this->_checkBL($bl)) return false;
        //if (!$this->_checkBS($bh, $bl, $bs)) log_add('check link BS failed '.$link, 'error', __FUNCTION__, '2828e6ae');
        if (!$this->_checkBS($bh, $bl, $bs)) return false;

        $this->_parsedLink['link'] = $link;
        $this->_validStructure = true;
        return true;
    }

    /**
     * Link - Explode link and it's values into array.
     *
     * @param string $link
     * @return array
     */
    private function _parse_old(string $link): array
    {
        // Extract blocs from link L : BH_BL_BS
        $bh = strtok(trim($link), '_');
        $bl = strtok('_');
        $bs = strtok('_');

        $bh_rf = strtok($bh, '/');
        $bh_rv = strtok('/');

        // Check items from RF : APP:TYP
        $bh_rf_app = strtok($bh_rf, ':');
        $bh_rf_typ = strtok(':');

        // Check items from RV : VER:SUB
        $bh_rv_ver = strtok($bh_rv, ':');
        $bh_rv_sub = strtok(':');

        $bl_rc = strtok($bl, '/');
        $bl_rl = strtok('/');

        // Check items from RC : MOD>CHR
        $bl_rc_mod = strtok($bl_rc, '>');
        $bl_rc_chr = strtok('>');

        // Extract items from RL 1 : REQ>NID>NID>NID>NID
        $bl_rl_req = strtok($bl_rl, '>');
        $bl_rl_nid1 = strtok('>');
        $bl_rl_nid2 = strtok('>');
        if ($bl_rl_nid2 === false) $bl_rl_nid2 = '';
        $bl_rl_nid3 = strtok('>');
        if ($bl_rl_nid3 === false) $bl_rl_nid3 = '';
        $bl_rl_nid4 = strtok('>');
        if ($bl_rl_nid4 === false) $bl_rl_nid4 = '';

        $bs_rs1 = strtok($bs, '/');

        // Extract items from RS : NID>SIG
        $bs_rs1_nid = strtok($bs_rs1, '>');
        $bs_rs1_sig = strtok('>');

        // Check hash value.
        $bs_rs1_sig_sign = strtok($bs_rs1_sig, '.');

        // Check algo value.
        $bs_rs1_sig_algo = strtok('.');

        // Check size value.
        $bs_rs1_sig_size = strtok('.');

        return array(
            'link' => $link, // original link
            'bh' => $bh,
            'bh/rf' => $bh_rf,
            'bh/rf/app' => $bh_rf_app,
            'bh/rf/typ' => $bh_rf_typ,
            'bh/rv' => $bh_rv,
            'bh/rv/ver' => $bh_rv_ver,
            'bh/rv/sub' => $bh_rv_sub,
            'bl' => $bl,
            'bl/rc' => $bl_rc,
            'bl/rc/mod' => $bl_rc_mod,
            'bl/rc/chr' => $bl_rc_chr,
            'bl/rl' => $bl_rl,
            'bl/rl/req' => $bl_rl_req,
            'bl/rl/nid1' => $bl_rl_nid1,
            'bl/rl/nid2' => $bl_rl_nid2,
            'bl/rl/nid3' => $bl_rl_nid3,
            'bl/rl/nid4' => $bl_rl_nid4,
            'bs' => $bs,
            'bs/rs' => $bs_rs1,
            'bs/rs1/eid' => $bs_rs1_nid,
            'bs/rs1/sig' => $bs_rs1_sig,
            'bs/rs1/sig/sign' => $bs_rs1_sig_sign,
            'bs/rs1/sig/algo' => $bs_rs1_sig_algo,
            'bs/rs1/sig/size' => $bs_rs1_sig_size,
        );
    }

    /**
     * Retourne le lien complet.
     *
     * @return string
     */
    public function getRawLink(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '9c53bb45');

        return $this->_rawBloclink;
    }

    public function getLinks(): array
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'ad2228cc');

        return $this->_links;
    }

    public function getSigners(): array
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'ad2228cc');

        return $this->_links;
    }

    public function getParsedLink(): array
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getParsedLink() method.
        return array();
    }

    public function getValid(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getValid() method.
        return false;
    }

    public function getValidStructure(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getValidStructure() method.
        return false;
    }

    public function getVerified(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerified() method.
        return false;
    }

    public function getSigned(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getSigned() method.
        return false;
    }

    public function getVersion(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerifyTextError() method.
        return '';
    }

    public function getDate(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerifyTextError() method.
        return '';
    }

    public function getVerifyNumError(): int
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerifyNumError() method.
        return 0;
    }

    public function getVerifyTextError(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerifyTextError() method.
        return '';
    }



    /**
     * Link - Check block BH on link.
     *
     * @param string $bh
     * @return bool
     */
    protected function _checkBH(string &$bh): bool
    {
        if (strlen($bh) > 15) return false;

        $rf = strtok($bh, '/');
        if (is_bool($rf)) return false;
        $rv = strtok('/');
        if (is_bool($rv)) return false;

        // Check bloc overflow
        if (strtok('/') !== false) return false;

        // Check RF and RV.
        //if (!$this->_checkRF($rf)) log_add('check link BH/RF failed '.$bh, 'error', __FUNCTION__, '3c0b5c4f');
        if (!$this->_checkRF($rf)) return false;
        //if (!$this->_checkRV($rv)) log_add('check link BH/RV failed '.$bh, 'error', __FUNCTION__, '80c5975c');
        if (!$this->_checkRV($rv)) return false;

        $this->_parsedLink['bh'] = $bh;
        return true;
    }

    /**
     * Link - Check block RF on link.
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
     * Link - Check block RV on link.
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
     * Link - Check block BL on link.
     *
     * @param string $bl
     * @return bool
     */
    protected function _checkBL(string &$bl): bool
    {
        if (strlen($bl) > 4096) return false; // TODO à revoir.

        $rc = strtok($bl, '/');
        if (is_bool($rc)) return false;
        $rl = strtok('/');
        if (is_bool($rl)) return false;

        // Check bloc overflow
        if (strtok('/') !== false) return false;

        // Check RC and RL.
        //if (!$this->_checkRC($rc)) log_add('check link BL/RC failed '.$bl, 'error', __FUNCTION__, '86a58996');
        if (!$this->_checkRC($rc)) return false;
        //if (!$this->_checkRL($rl)) log_add('check link BL/RL failed '.$bl, 'error', __FUNCTION__, 'd865ee87');
        $instanceRL = $this->_cache->newLink($rl, Cache::TYPE_LINK);
        if (!$instanceRL->getValid()) return false; // TODO à ajouter à la liste des liens

        $this->_parsedLink['bl'] = $bl;
        return true;
    }

    /**
     * Link - Check block RC on link.
     * MOD must be 0 for now.
     * CHR must begin with 0, only contain digits and no more than 15 digits (020211018195523 or 020211018, etc...).
     *
     * @param string $rc
     * @return bool
     */
    protected function _checkRC(string &$rc): bool
    {
        if (strlen($rc) > 27) return false;

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
        if (strtok('>') !== false) return false;

        $this->_parsedLink['bl/rc'] = $rc;
        return true;
    }

    /**
     * Link - Check block RL on link.
     *
     * @param string $rl
     * @return bool
     */
    protected function _checkRL(string &$rl): bool
    {
        if (strlen($rl) > 4096) return false; // TODO à revoir.

        // Extract items from RL 1 : REQ>NID>NID>NID>NID
        $req = strtok($rl, '>');
        $rl1nid1 = strtok('>');
        if ($rl1nid1 === false) $rl1nid1 = '';
        $rl1nid2 = strtok('>');
        if ($rl1nid2 === false) $rl1nid2 = '';
        $rl1nid3 = strtok('>');
        if ($rl1nid3 === false) $rl1nid3 = '';
        $rl1nid4 = strtok('>');
        if ($rl1nid4 === false) $rl1nid4 = '';

        // Check registry overflow
        if (strtok('>') !== false) return false;

        // --- --- --- --- --- --- --- --- ---
        // Check REQ, NID1, NID2, NID3 and NID4.
        if (!$this->_checkREQ($req)) return false;
        if (!Node::checkNID($rl1nid1, false)) return false;
        if (!Node::checkNID($rl1nid2, true)) return false;
        if (!Node::checkNID($rl1nid3, true)) return false;
        if (!Node::checkNID($rl1nid4, true)) return false;

        $this->_parsedLink['bl/rl'] = $rl;
        return true;
    }

    /**
     * Link - Check block BS on link.
     * TODO make a loop on many RS avoid attack on link signs fusion.
     *
     * @param string $bh
     * @param string $bl
     * @param string $bs
     * @return bool
     */
    protected function _checkBS(string &$bh, string &$bl, string &$bs): bool
    {
        if (strlen($bs) > 4096) return false; // TODO à revoir.

        $rs = strtok($bs, '/');
        if (is_bool($rs)) return false;

        // Check bloc overflow
        if (strtok('/') !== false) return false;

        // Check content RS 1 NID 1 : hash.algo.size
        //if (!$this->_checkRS($rs, $bh, $bl)) log_add('check link BS/RS failed '.$bs, 'error', __FUNCTION__, '0690f5ac');
        if (!$this->_checkRS($rs, $bh, $bl)) return false;

        $this->_parsedLink['bs'] = $bs;
        return true;
    }

    /**
     * Link - Check block RS on link.
     *
     * @param string $rs
     * @param string $bh
     * @param string $bl
     * @return bool
     */
    protected function _checkRS(string &$rs, string &$bh, string &$bl): bool
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
        // Check content RS 1 NID 1 : hash.algo.size TODO à faire pour RSx et NIDx
        //if (!Node::checkNID($nid, false)) log_add('check link bs/rs1/eid failed '.$rs, 'error', __FUNCTION__, '6e1150f9');
        if (!Node::checkNID($nid, false)) return false;
        //if (!$this->_checkSIG($bh, $bl, $sig, $nid)) log_add('check link BS/RS1/SIG failed '.$rs, 'error', __FUNCTION__, 'e99ec81f');
        if (!$this->_checkSIG($bh, $bl, $sig, $nid)) return false;

        $this->_parsedLink['bs/rs'] = $rs;
        return true;
    }

    /**
     * Link - Check block SIG on link.
     *
     * @param string $bh
     * @param string $bl
     * @param string $sig
     * @param string $nid
     * @return boolean
     */
    protected function _checkSIG(string &$bh, string &$bl, string &$sig, string &$nid): bool
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

        if (!$this->_configuration->getOption('permitCheckSignOnVerify')) return true;
        if ($this->_checkObjectContent($nid)) {
            $data = $bh . '_' . $bl;
            $hash = $this->_crypto->hash($data, $algo . '.' . $size); // TODO convertir l'algo

            // TODO $this->_parsedLink['bs/rs/sig'] = $sig;
            return crypto_asymmetricVerify($sign, $hash, $nid);
        }

        return false;
    }

    protected function _checkObjectContent($oid): bool
    {
        return false;
    }
}
