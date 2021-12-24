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
class Bloclink implements bloclinkInterface
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
     * @var string
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

    protected $_maxRL = Configuration::OPTIONS_DEFAULT_VALUE['linkMaxRL'];
    protected $_maxRLUID = Configuration::OPTIONS_DEFAULT_VALUE['linkMaxRLUID'];
    protected $_maxRS = Configuration::OPTIONS_DEFAULT_VALUE['linkMaxRS'];

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
     * @param string $linkType
     * @return boolean
     */
    public function __construct(nebule $nebuleInstance, string $bloclink, string $linkType = Cache::TYPE_LINK)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_linksType = $linkType;
        $this->_maxRL = $this->_configuration->getOption('linkMaxRL');
        $this->_maxRLUID = $this->_configuration->getOption('linkMaxRLUID');
        $this->_maxRS = $this->_configuration->getOption('linkMaxRS');

        $this->_metrology->addLinkRead();

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
    }

    /**
     * Verify and parse links on bloc.
     *
     * @param string $link
     * @return bool
     */
    protected function _parse(string $link): bool
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
        //if (!$this->_checkBH($bh)) $this->_metrology->addLog('check link BH failed '.$link, Metrology::LOG_LEVEL_ERROR, __METHOD__, '80cbba4b');
        if (!$this->_checkBH($bh)) return false;
        //if (!$this->_checkBL($bl)) $this->_metrology->addLog('check link BL failed '.$link, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c5d22fda');
        if (!$this->_checkBL($bl)) return false;
        //if (!$this->_checkBS($bh, $bl, $bs)) $this->_metrology->addLog('check link BS failed '.$link, Metrology::LOG_LEVEL_ERROR, __METHOD__, '2828e6ae');
        if (!$this->_checkBS($bh, $bl, $bs)) return false;

        $this->_parsedLink['link'] = $link;
        $this->_validStructure = true;
        return true;
    }

    /**
     * Get full link content as text.
     *
     * @return string
     */
    public function getRaw(): string
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

    /**
     * array(
     *   'link'
     *   'bh'
     *   'bh/rf'
     *   'bh/rf/app'
     *   'bh/rf/typ'
     *   'bh/rv'
     *   'bh/rv/ver'
     *   'bh/rv/sub'
     *   'bl'
     *   'bl/rc'
     *   'bl/rc/mod'
     *   'bl/rc/chr'
     *   'bl/rl1'
     *   'bl/rl1/req'
     *   'bl/rl1/nid1'
     *   'bl/rl1/nid2'
     *   'bl/rl1/nid3'
     *           ...
     *   'bl/rl2'
     *       ...
     *   'bs' => $bs,
     *   'bs/rs1'
     *   'bs/rs1/eid'
     *   'bs/rs1/sig'
     *   'bs/rs2'
     *       ...
     * )
     *
     * @return array
     */
    public function getParsed(): array
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        return $this->_parsedLink;
    }

    public function getValid(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        return $this->_valid;
    }

    public function getValidStructure(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        return $this->_validStructure;
    }

    public function getSigned(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        return $this->_signed;
    }

    public function getVersion(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        return $this->_parsedLink['bl/rv'];
    }

    public function getDate(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        return $this->_parsedLink['bl/rc/chr'];
    }

    public function getVerifyNumError(): int
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        return $this->_verifyNumError;
    }

    public function getVerifyTextError(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        return $this->_verifyTextError;
    }



    /**
     * Check block BH on link.
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
        if (strlen($bl) > 4096) return false; // TODO à revoir.

        $rc = strtok($bl, '/');
        if (is_bool($rc)) return false;
        //if (!$this->_checkRC($rc)) $this->_metrology->addLog('check link BL/RC failed '.$bl, Metrology::LOG_LEVEL_ERROR, __METHOD__, '86a58996');
        if (!$this->_checkRC($rc)) return false;

        $rl = strtok('/');
        if (is_bool($rl)) return false;

        $i = 1;
        while (!is_bool($rl))
        {
            //if (!$this->_checkRL($rl, (string)$i))) $this->_metrology->addLog('check link BL/RL failed '.$bl, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd865ee87');
            if (!$this->_checkRL($rl, (string)$i)) return false;
            if ($this->_linksType == Cache::TYPE_TRANSACTION)
                $instanceRL = new Transaction($this->_nebuleInstance, $rl, $this);
            else
                $instanceRL = new Link($this->_nebuleInstance, $rl, $this);
            if (!$instanceRL->getValid()) return false;

            $this->_links[] = $instanceRL;

            $i++;
            if ($i > $this->_maxRL)
            {
                $this->_metrology->addLog('BL overflow '.substr($bl, 0, 60) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '6a777706');
                return false;
            }
            $rl = strtok('/');
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
     * Check block RL on link.
     *
     * @param string $rl
     * @param string $i
     * @return bool
     */
    protected function _checkRL(string &$rl, string $i): bool
    {
        if (strlen($rl) > 4096) return false; // TODO à revoir.

        // Extract items from RL : REQ>NID>NID>NID>NID...
        $req = strtok($rl, '>');
        if (is_bool($req)) return false;
        if (!$this->_checkREQ($req, (string)$i)) return false;

        $rl1nid = strtok('/');
        if (is_bool($rl1nid)) return false;

        $j = 1;
        while (!is_bool($rl1nid))
        {
            if (!Node::checkNID($rl1nid, $j > 0)) return false;
            $this->_parsedLink["bl/rl$i/nid$j"] = $rl1nid;

            $j++;
            if ($j > $this->_maxRLUID)
            {
                $this->_metrology->addLog('BL/RL overflow '.substr($rl, 0, 60) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '72920c39');
                return false;
            }
            $rl1nid = strtok('/');
        }

        $this->_parsedLink["bl/rl$i"] = $rl;
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

        $i = 1;
        while (!is_bool($rs))
        {
            //if (!$this->_checkRS($rs, $bh, $bl)) $this->_metrology->addLog('check link BS/RS failed '.$bs, Metrology::LOG_LEVEL_ERROR, __METHOD__, '0690f5ac');
            if (!$this->_checkRS($rs, $bh, $bl, (string)$i)) return false;

            $i++;
            if ($i > $this->_maxRS)
            {
                $this->_metrology->addLog('BS overflow '.substr($bs, 0, 60) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '9f2e670f');
                return false;
            }
            $rs = strtok('/');
        }

        $this->_parsedLink['bs'] = $bs;
        return true;
    }

    /**
     * Check block RS on link.
     *
     * @param string $rs
     * @param string $bh
     * @param string $bl
     * @param string $i
     * @return bool
     */
    protected function _checkRS(string &$rs, string &$bh, string &$bl, string $i): bool
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
        $this->_parsedLink["bs/rs$i/nid"] = $nid;

        //if (!$this->_checkSIG($bh, $bl, $sig, $nid)) $this->_metrology->addLog('check link BS/RS1/SIG failed '.$rs, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e99ec81f');
        if (!$this->_checkSIG($bh, $bl, $sig, $nid, (string)$i)) return false;

        $this->_parsedLink["bs/rs$i"] = $rs;
        return true;
    }

    /**
     * Check block SIG on link.
     *
     * @param string $bh
     * @param string $bl
     * @param string $sig
     * @param string $nid
     * @param string $i
     * @return boolean
     */
    protected function _checkSIG(string &$bh, string &$bl, string &$sig, string &$nid, string $i): bool
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

            if (crypto_asymmetricVerify($sign, $hash, $nid))
            {
                $this->_parsedLink["bs/rs$i/sig"] = $sig;
                return true;
            }
        }
        return false;
    }

    protected function _checkObjectContent($oid): bool
    {
        // TODO
        return false;
    }

    /**
     * Signature du lien par l'entité en cours.
     *
     * @param string $publicKey
     * @return boolean
     */
    public function sign(string $publicKey = '0'): bool
    {
        $this->_metrology->addLog(substr($this->_rawLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // Si autorisé à signer.
        if (!$this->_configuration->getOption('permitCreateLink')) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Can not sign link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
            return false;
        }

        // Si le lien est valide.
        if ($this->_validStructure
            && $this->_valid
            && $this->_verifyNumError == 11
            && $this->_signe == '0'
        ) {
            // Lit la clé publique.
            if (is_a($publicKey, 'Entity')) {
                $pubkeyInstance = $publicKey;
                $pubkeyID = $publicKey->getID();
            } else {
                if ($publicKey == '0') {
                    $pubkeyID = $this->_nebuleInstance->getCurrentEntity();
                    $pubkeyInstance = $this->_nebuleInstance->getCurrentEntityInstance();
                } elseif ($publicKey == $this->_nebuleInstance->getCurrentEntity()) {
                    $pubkeyInstance = $this->_nebuleInstance->getCurrentEntityInstance();
                    $pubkeyID = $publicKey;
                } else {
                    $pubkeyInstance = $this->_nebuleInstance->newEntity($publicKey);
                    $pubkeyID = $publicKey;
                }
            }
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Sign link for ' . $pubkeyID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

            // Récupère l'algorithme de hash.
            $hashAlgo = $this->_crypto->hashAlgorithmName();

            // Génère le lien sans signature et son hash pour vérification.
            $shortLink = '_' . $pubkeyID . '_' . $this->_date . '_' . $this->_action . '_' . $this->_hashSource . '_' . $this->_hashTarget . '_' . $this->_hashMeta;

            // Génère la signature.
            $sign = $pubkeyInstance->signLink($shortLink, $hashAlgo);

            if ($sign !== false) {
                $this->_signeValue = $sign;
                $this->_signeAlgo = $hashAlgo;
                $this->_signe = $sign . '.' . $hashAlgo;
                $this->_hashSigner = $pubkeyID;
                $this->_rawLink = $this->_signe . $shortLink;
                $this->_signed = true;
                $this->_valid = true;
                $this->_verifyNumError = 0;
                $this->_verifyTextError = 'Signe is valid.';
                return true;
            }
        } else {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Invalid link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        }

        return false;
    }

    /**
     * Ecrit le lien pour les objets concernés.
     *
     * @return boolean
     */
    public function write(): bool
    {
        $this->_metrology->addLog(substr($this->_rawLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // Si autorisé à écrire.
        if (!$this->_configuration->getOption('permitWrite')
            || !$this->_configuration->getOption('permitWriteLink')
        ) {
            return false;
        }

        // Métrologie.
        $this->_nebuleInstance->getMetrologyInstance()->addAction('addlnk', $this->_rawLink, $this->_valid);

        // Si le lien n'est pas valide, quitte.
        if (!$this->_valid
            || !$this->_signed
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link unsigned', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
            return false;
        }

        // Ecrit l'historique.
        if ($this->_configuration->getOption('permitHistoryLinksSign')) {
            $history = nebule::NEBULE_LOCAL_HISTORY_FILE;
            $this->_io->linkWrite($history, $this->_rawLink);
        }

        // Ecrit le lien pour l'objet de l'entité signataire.
        if ($this->_configuration->getOption('permitAddLinkToSigner')) {
            $this->_io->linkWrite($this->_hashSigner, $this->_rawLink);
        }

        if ($this->_action != 'c') {
            // Ecrit le lien pour l'objet source.
            $this->_io->linkWrite($this->_hashSource, $this->_rawLink);

            // Ecrit le lien pour l'objet cible.
            if ($this->_hashTarget != $this->_hashSource
                && $this->_hashTarget != '0'
            ) {
                $this->_io->linkWrite($this->_hashTarget, $this->_rawLink);
            }

            // Ecrit le lien pour l'objet méta.
            if ($this->_hashMeta != $this->_hashSource
                && $this->_hashMeta != $this->_hashTarget
                && $this->_hashMeta != '0'
            ) {
                $this->_io->linkWrite($this->_hashMeta, $this->_rawLink);
            }
        } elseif ($this->_configuration->getOption('permitObfuscatedLink')) {
            // Ecrit le lien dissimulé.
            $this->_io->linkWrite($this->_hashSigner . '-' . $this->_hashSource, $this->_rawLink); // @todo
        } else {
            return false;
        }

        return true;
    }

    /**
     * Signe et écrit le lien.
     *
     * @param string $publicKey
     * @return boolean
     */
    public function signWrite(string $publicKey = '0'): bool
    {
        $this->_metrology->addLog(substr($this->_rawLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        if ($this->sign($publicKey)) {
            return $this->write();
        }
        return false;
    }



    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#l">B / Bloc de liens</a>
            <ul>
                <li><a href="#lelpo">BELPO / Liens à Propos d’un Objet</a></li>
                <li><a href="#lelco">BELCO / Liens Contenu dans un Objet</a></li>
                <li><a href="#le">BE / Entête</a></li>
                <li><a href="#lr">BR / Registre</a>
                    <ul>
                        <li><a href="#lrsi">BRSI / Le champ <code>Signature</code></a></li>
                        <li><a href="#lrusi">BRHSI / Le champ <code>HashSignataire</code></a></li>
                        <li><a href="#lrt">BRT / Le champ <code>TimeStamp</code></a></li>
                        <li><a href="#lra">BRA / Le champ <code>Action</code></a>
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
                        <li><a href="#lrhs">BRHS / Le champ <code>HashSource</code></a></li>
                        <li><a href="#lrhc">BRHC / Le champ <code>HashCible</code></a></li>
                        <li><a href="#lrhm">BRHM / Le champ <code>HashMeta</code></a></li>
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
    public function echoDocumentationCore(): void
    {
        ?>

        <h1 id="l">B / Bloc de liens</h1>
        <p style="color:red;">Cette partie est périmée avec la nouvelle version de liens !</p>
        <p>Le lien est la matérialisation dans un graphe d’une relation entre deux objets pondéré par un troisième
            objet.</p>

        <h5 id="lelpo">BELPO / Liens à Propos d’un Objet</h5>
        <p>Les liens d’un objet sont consultables séquentiellement. Il doivent être perçus comme des méta-données d’un
            objet.</p>
        <p>Les liens sont séparés soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;». Un
            lien est donc une suite de caractères ininterrompue, c’est à dire sans espace ou retour à la ligne.</p>
        <p>La taille du lien dépend de la taille de chaque champs.</p>
        <p>Chaque localisation contenant des liens doit avoir un entête de version.</p>

        <h5 id="lelco">BELCO / Liens Contenu dans un Objet</h5>
        <p>Certains liens d’un objet peuvent être contenus dans un autre objet.</p>
        <p>Cette forme de stockage des liens permet de les transmettre et de les manipuler sous la forme d’un objet. On
            peut ainsi profiter du découpage et du chiffrement. Plusieurs liens peuvent être stockés sans être
            nécessairement en rapport avec les mêmes objets.</p>
        <p>Les liens stockés dans un objet ne peuvent pas faire référence à ce même objet.</p>
        <p>Tout ajout de lien crée implicitement un nouvel objet de mise à jour, c’est à dire lié par un lien de type
            u.</p>
        <p>Chaque fichier contenant des liens doit avoir un entête de version.</p>
        <p>Les objets contenants des liens ne sont pas reconnus et exploités lors de la lecture des liens. Ceux-ci
            doivent d’abord être extraits et injectés dans les liens des objets concernés. En clair, on ne peux pas s’en
            servir facilement pour de l’anonymisation.</p>

        <h2 id="le">BE / Entête</h2>
        <p>L’entête des liens est constitué du texte <code>nebule/liens/version/1.2</code>. Il est séparé du premier
            lien soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Il doit être transmit avec les liens, en premier.</p>

        <h2 id="lr">BR / Registre</h2>
        <p>Le registre du lien décrit la syntaxe du lien :</p>
        <p style="text-align:center">
            <code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>
        <p>Ce registre a un nombre de champs fixe. Chaque champs a une place fixe dans le lien. Les champs ont une
            taille variable. Le séparateur de champs est l’underscore «&nbsp;_&nbsp;». Les champs ne peuvent contenir ni
            l’underscore «&nbsp;_&nbsp;» ni l’espace &nbsp;» &nbsp;» ni le retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Tout lien qui ne respecte pas cette syntaxe est à considérer comme invalide et à supprimer. Tout lien dont la
            <code>Signature</code> est invalide est à considérer comme invalide et à supprimer. La vérification peut
            être réalisée en ré-assemblant les champs après nettoyage.</p>

        <h4 id="lrsi">BRSI / Le champ <code>Signature</code></h4>
        <p>Le champ <code>Signature</code> est représenté en deux parties séparées par un point «&nbsp;.&nbsp;» . La
            première partie contient la valeur de la signature. La deuxième partie contient le nom court de la fonction
            de prise d’empreinte utilisée.</p>
        <p>La signature est calculée sur l’empreinte du lien réalisée avec la fonction de prise d’empreinte désignée
            dans la deuxième partie. L’empreinte du lien est calculée sur tout le lien sauf le champs
            <code>signature</code>, c’est à dire sur «&nbsp;<code>_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code>&nbsp;»
            avec le premier underscore inclus.</p>
        <p>La signature ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;» à «&nbsp;9&nbsp;»
            et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule. La fonction de prise d’empreinte est notée en
            caractères alpha-numériques en minuscule.</p>

        <h5 id="lrusi">BRHSI / Le champ <code>HashSignataire</code></h5>
        <p>Le champ <code>HashSignataire</code> désigne l’objet de l’entité qui génère le lien et le signe.</p>
        <p>Il ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;» à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;»
            à «&nbsp;f&nbsp;» en minuscule.</p>

        <h3 id="lrt">BRT / Le champ <code>TimeStamp</code></h3>
        <p>Le champ <code>TimeStamp</code> est une marque de temps qui donne un ordre temporel aux liens. Ce champs peut
            être une date et une heure au format <a class="external text" title="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    href="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    rel="nofollow">ISO8601</a> ou simplement un compteur incrémental.
        </p>

        <h3 id="lra">BRA / Le champ <code>Action</code></h3>
        <p>Le champ <code>Action</code> détermine la façon dont le lien doit être utilisé.</p>
        <p>Quand on parle du type d’un lien, on fait référence à son champ <code>Action</code>.</p>
        <p>L’interprétation de ce champ est limité au premier caractère. Des caractères alpha-numériques supplémentaires
            sont autorisés mais ignorés.</p>
        <p>Cette interprétation est basée sur un vocabulaire particulier. Ce vocabulaire est spécifique à <i>nebule
                v1.2</i> (et <i>nebule v1.1</i>).</p>
        <p>Le vocabulaire ne reconnaît que les 8 caractères <code>l</code>, <code>f</code>, <code>u</code>,
            <code>d</code>, <code>e</code>, <code>x</code>, <code>k</code> et <code>s</code>, en minuscule.</p>

        <h4 id="lral">BRAL / Action <code>l</code> – Lien entre objets</h4>
        <p>Met en place une relation entre deux objets. Cette relation a un sens de mise en place et peut être pondérée
            par un objet méta.</p>
        <p>Les liens de type <code>l</code> ne devraient avoir ni <code>HashMeta</code> nul ni <code>HashCible</code>
            nul.</p>

        <h4 id="lraf">BRAF / Action <code>f</code> – Dérivé d’objet</h4>
        <p>Le nouvel objet est considéré comme enfant ou parent suivant le sens du lien.</p>
        <p>Le champs <code>ObjetMeta</code> doit être vu comme le contexte du lien. Par exemple, deux objets contenants
            du texte peuvent être reliés simplement sans contexte, c’est à dire reliés de façon simplement hiérarchique.
            Ces deux mêmes textes peuvent être plutôt (ou en plus) reliés avec un contexte comme celui d’une discussion
            dans un blog. Dans ce deuxième cas, la relation entre les deux textes n’a pas de sens en dehors de cette
            discussion sur ce blog. Il est même probable que le blog n’affichera pas les autres textes en relations si
            ils n’ont pas un contexte appartenant à ce blog.</p>
        <p><code>f</code> comme <i>fork</i>.</p>

        <h4 id="lrau">BRAU / Action <code>u</code> – Mise à jour d’objet</h4>
        <p>Mise à jour d’un objet dérivé qui remplace l’objet parent.</p>
        <p><code>u</code> comme <i>update</i>.</p>

        <h4 id="lrad">BRAD / Action <code>d</code> – Suppression d’objet</h4>
        <p>L’objet est marqué comme à supprimer d’un ou de tous ses emplacements de stockage.</p>
        <p><code>d</code> comme <i>delete</i>.</p>
        <p>Le champs <code>HashCible</code> <span style="text-decoration: underline;">peut</span> être nuls, c’est à
            dire égal à <code>0</code>. Si non nul, ce champs doit contenir une entité destinataire de <i>l’ordre</i> de
            suppression. C’est utilisé pour demander à une entité relaie de supprimer un objet spécifique. Cela peut
            être utilisé pour demander à une entité en règle générale de bien vouloir supprimer l’objet, ce qui n’est
            pas forcément exécuté.</p>
        <p>Le champs <code>HashMeta</code> <span style="text-decoration: underline;">doit</span> être nuls, c’est à dire
            égal à <code>0</code>.</p>
        <p>Un lien de suppression sur un objet ne veut pas forcément dire qu’il a été supprimé. Même localement, l’objet
            est peut-être encore présent. Si le lien de suppression vient d’une autre entité, on ne va sûrement pas par
            défaut en tenir compte.</p>
        <p>Lorsque le lien de suppression est généré, le serveur sur lequel est généré le lien doit essayer par défaut
            de supprimer l’objet. Dans le cas d’un serveur hébergeant plusieurs entités, un objet ne sera pas supprimé
            si il est encore utilisé par une autre entité, c’est à dire si une entité a un lien qui le concerne et n’a
            pas de lien de suppression.</p>

        <h4 id="lrae">BRAE / Action <code>e</code> – Équivalence d’objets</h4>
        <p>Définit des objets jugés équivalents, et donc interchangeables par exemple pour une traduction.</p>

        <h4 id="lrac">BRAC / Action <code>c</code> – Chiffrement de lien</h4>
        <p>Ce lien de dissimulation contient un lien dissimulé sans signature. Il permet d’offusquer des liens entre
            objets et donc d’anonymiser certaines actions de l’entité (cf <a href="#ckl">CKL</a>).</p>
        <p>Le champs <code>HashSource</code> fait référence à l’entité destinataire du lien, celle qui peut le
            déchiffrer. A part le champs de l’entité signataire, c’est le seul champs qui fait référence à un objet.</p>
        <p>Le champs <code>HashCible</code> ne contient pas la référence d’un objet mais le lien chiffré et encodé en
            hexadécimal. Le chiffrement est de type symétrique avec la clé de session. Le lien offusqué n’a pas grand
            intérêt en lui même, c’est le lien déchiffré qui en a.</p>
        <p>Le champs <code>HashMeta</code> ne contient pas la référence d’un objet mais la clé de chiffrement du lien,
            dite clé de session. Cette clé est chiffrée (asymétrique) pour l’entité destinataire et encodée en
            hexadécimal. Chaque entités destinataires d'un lien de dissimulé doit disposer d'un lien de dissimulation
            qui lui est propre.</p>
        <p>Lors du traitement des liens, si une entité est déverrouillée, les liens offusqués pour cette entité doivent
            être déchiffrés et utilisés en remplacement des liens offusqués originels. Les liens offusqués doivent être
            vérifiés avant déchiffrement. Les liens déchiffrés doivent être vérifiés avant exploitation.</p>
        <p>Les liens de dissimulations posent un problème pour être efficacement utilisés par les entités émetrices et
            destinataires. Pour résoudre ce problème sans risquer de révéler les identifiants des objets utilisés dans
            un lien dissimulé, les liens de dissimulation sont attachés à des objets virtuels translatés depuis les
            identifiants des objets originaux (cf <a href="#ld">BD</a>).</p>
        <p>L'option <code>permitObfuscatedLink</code> permet de désactiver la dissimulation (offuscation) des liens des
            objets. Dans ce cas le lien de type <code>c</code> est rejeté comme invalide avec le code erreur 43.</p>

        <h4 id="lrak">BRAK / Action <code>k</code> – Chiffrement d’objet</h4>
        <p>Désigne la version chiffrée de l’objet (cf <a href="#cko">CKO</a>).</p>
        <p>L'option <code>permitProtectedObject</code> permet de désactiver la protection (chiffrement) des objets. Dans
            ce cas le lien de type <code>k</code> est rejeté comme invalide avec le code erreur 42.</p>

        <h4 id="lras">BRAS / Action <code>s</code> – Subdivision d’objet</h4>
        <p>Désigne un fragment de l’objet.</p>
        <p>Ce champ nécessite un objet méta qui précise intervalle de contenu de l’objet d’origine. Le contenu de
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
        <p>Supprime un ou plusieurs liens précédemment mis en place.</p>
        <p>Les liens concernés par la suppression sont les liens antérieurs de type <code>l</code>, <code>f</code>,
            <code>u</code>, <code>d</code>, <code>e</code>, <code>k</code> et <code>s</code>. Ils sont repérés par les 3
            derniers champs, c’est à dire sur <code>HashSource_HashCible_HashMeta</code>. Les champs nuls sont
            strictement pris en compte.</p>
        <p>Le champ <code>TimeStamp</code> permet de déterminer l’antériorité du lien et donc de déterminer sa
            suppression ou pas.</p>
        <p>C’est la seule action sur les liens et non sur les objets.</p>

        <h4 id="lrhs">BRHS / Le champ <code>HashSource</code></h4>
        <p>Le champ <code>HashSource</code> désigne l’objet source du lien.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>

        <h4 id="lrhc">BRHC / Le champ <code>HashCible</code></h4>
        <p>Le champ <code>HashCible</code> désigne l’objet destination du lien.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c’est à dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <h4 id="lrhm">BRHM / Le champ <code>HashMeta</code></h4>
        <p>Le champ <code>HashMeta</code> désigne l’objet contenant une caractérisation du lien entre l’objet source et
            l’objet destination.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c’est à dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <h2 id="l1">B1 / Lien simple</h2>
        <p>Le registre du lien simple a ses champs <code>HashCible</code> et <code>HashMeta</code> égaux à «&nbsp;0&nbsp;».
        </p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_0_0</code></p>

        <h2 id="l2">B2 / Lien double</h2>
        <p>Le registre du lien double a son champ <code>HashMeta</code> égal à «&nbsp;0&nbsp;».</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_0</code></p>

        <h2 id="l3">B3 / Lien triple</h2>
        <p>Le registre du lien triple est complètement utilisé.</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>

        <h2 id="ls">BS / Stockage</h2>
        <p>Tous les liens sont stockés dans un même emplacement ou sont visible comme étant dans un même emplacement.
            Cet emplacement ne contient pas les contenus des objets (cf <a href="#oos">OOS</a>).</p>
        <p>Le lien dissimulé est stocké dans le même emplacement mais dispose de fichiers de stockages différents du
            fait de la spécificité (cf <a href="#lsds">BSDS</a>).</p>

        <h3 id="lsa">BSA / Arborescence</h3>
        <p>Sur un système de fichiers, tous les liens sont stockés dans des fichiers contenus dans le dossier <code>pub/l/</code>
            (<code>l</code> comme lien).</p>
        <p>A faire...</p>

        <h3 id="lsd">BSD / Dissimulation</h3>
        <p>Le lien de dissimulation, de type <code>c</code>, contient un lien dissimulé sans signature (cf <a
                href="#lrac">BRAC</a>). Il permet d’offusquer des liens entre objets et donc d’anonymiser certaines
            actions de l’entité (cf <a href="#ckl">CKL</a>).</p>

        <h5 id="lsdrp">BSDRP / Registre public</h5>
        <p>Le registre du lien de dissimulation, public par nature, est conforme au registre des autres liens (cf <a
                href="#lr">BR</a>). Si ce lien ne respectait pas cette structure il serait automatiquement ignoré ou
            rejeté. Son stockage et sa transmission ont cependant quelques particularités.</p>
        <p>Les champs <code>Signature</code> (cf <a href="#lrsi">BRSI</a>) et <code>HashSignataire</code> (cf <a
                href="#lrhsi">BRHSI</a>) du registre sont conformes aux autres liens. Ils assurent la protection du
            lien. Le champs signataire fait office d'émeteur du lien dissimulé.</p>
        <p>Le champs <code>TimeStamp</code> (cf <a href="#lrt">BRT</a>) du registre est conformes aux autres liens. Il a
            cependant une valeur non significative et sourtout pas liée au <code>TimeStamp</code> du lien dissimulé.</p>
        <p>Le champs <code>Action</code> (cf <a href="#lrt">BRT</a>) du registre est de type <code>c</code> (cf <a
                href="#lra">BRA</a> et <a href="#lrac">BRAC</a>).</p>
        <p>Le champs <code>HashSource</code> fait référence à l’entité destinataire du lien, celle qui peut le
            déchiffrer. A part le champs de l’entité signataire, c’est le seul champs qui fait référence à un objet.</p>
        <p>Le champs <code>HashCible</code> ne contient pas la référence d’un objet mais le lien chiffré et encodé en
            hexadécimal. Le chiffrement est de type symétrique avec la clé de session. Le lien offusqué n’a pas grand
            intérêt en lui même, c’est le lien déchiffré qui en a.</p>
        <p>Le champs <code>HashMeta</code> ne contient pas la référence d’un objet mais la clé de chiffrement du lien,
            dite clé de session. Cette clé est chiffrée (asymétrique) pour l’entité destinataire et encodée en
            hexadécimal. Chaque entités destinataires d'un lien de dissimulé doit disposer d'un lien de dissimulation
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
        <p>A faire...</p>

        <h4 id="lsdt">BSDT / Transfert et partage</h4>
        <p>A faire...</p>

        <h4 id="lsdc">BSDC / Compromission</h4>
        <p>A faire...</p>

        <h2 id="lt">BT / Transfert</h2>
        <p>A faire...</p>

        <h2 id="lv">BV / Vérification</h2>
        <p>La signature d’un lien doit être vérifiée lors de la fin de la réception du lien. La signature d’un lien
            devrait être vérifiée avant chaque utilisation de ce lien. Un lien avec une signature invalide doit être
            supprimé. Lors de la suppression d’un lien, les autres liens de cet objet ne sont pas supprimés et l'objet
            n'est pas supprimé. La vérification de la validité des objets est complètement indépendante de celle des
            liens, et inversement (cf <a href="#cl">CL</a> et <a href="#oov">OOV</a>).</p>
        <p>Toute modification de l’un des champs du lien entraîne l’invalidation de tout le lien.</p>

        <h2 id="lo">BO / Oubli</h2>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}
