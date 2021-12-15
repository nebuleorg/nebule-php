<?php
declare(strict_types=1);
namespace Nebule\Library;













/**
 * ------------------------------------------------------------------------------------------
 * La classe Transaction.
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
 * Un lien peut être valide si sa structure est correcte, et peut être signé si la signature est valide.
 *
 * Une transaction est un lien entre une monnaie, un jeton et un objet contenant la description de la transaction.
 * La description de la transaction peut en fait contenir plusieurs transactions élémentaires.
 * ------------------------------------------------------------------------------------------
 */
class Transaction extends Link
{
    /**
     * Booléen si le lien est une transaction ET qu'elle est valide.
     *
     * @var boolean
     */
    private bool $_isTransaction = false;

    /**
     * ID de l'objet contenant les transactions.
     * Reste à null en mode LNS.
     *
     * @var string
     */
    private string $_transactionsObjectID = '';

    /**
     * Mode de transaction, si valide dans la monnaie.
     *
     * @var string
     */
    private string $_transactionsMode = '';

    /**
     * Date des transactions.
     *
     * @var DateTime|null
     */
    private DateTime|null $_transactionsTimestamp = null;

    /**
     * Table des transactions unitaires.
     *
     * Les transactions unitaires sont incrémentées à partir de 0.
     * Chaque transaction unitaire est un sous-tableau :
     * - CID
     * - D : horodatage
     * - S : ID détenteur
     * - D : ID destinataire
     * - TID
     * - R : ratio du jeton utilisé, de 0 à 1, 1 = tout
     * - TRS : mode de transaction
     *
     * @var array
     */
    private array $_transactionsArray = array();

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
        '_isTransaction',
    );

    /**
     * Initialisation post-constructeur.
     *
     * @return void
     */
    protected function _initialisation()
    {
        // Vérifications de base.
        if ($this->_action != 'f'
            || $this->_hashSource == '0'
            || $this->_hashTarget == '0'
            || $this->_hashMeta == '0'
        ) {
            return;
        }

        $this->_extractByMode();
    }

    /**
     * Tri par mode de transaction détecté.
     *
     * @return void
     */
    private function _extractByMode()
    {
        // Si transaction en mode mode LNS.
        $hashLNS = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_TRANSACTION);
        if ($this->_hashMeta == $hashLNS) {
            $this->_extractModeLNS();
        } else {
            $this->_isTransaction = false;
        }
    }

    /**
     * Extrait une transaction en mode LNS.
     *
     * Une transaction en mode LNS est marquée par un lien :
     * - hashsign : signataire et entité source disposant du jeton.
     * - date : date de la prise en compte de la transaction.
     * - action : type f.
     * - hashsource : ID du jeton.
     * - hashtarget : ID du destinataire.
     * - hashmeta : hash de 'nebule/objet/monnaie/transaction', marque que c'est une transaction.
     * Elle est repérée par rapport au champs méta.
     * Il faut vérifier que la source est un jeton et en extraire la monnaie affairante.
     * Il faut vérifier que la destination est une entité ou un dérivé.
     *
     * @return void
     */
    private function _extractModeLNS()
    {
        $this->_transactionsMode = 'LNS';

        // Vérifie si l'objet source est un jeton.
        $instanceSource = $this->_nebuleInstance->newToken($this->_hashSource);
        $instanceTarget = $this->_nebuleInstance->newWallet($this->_hashTarget);
        if ($instanceSource->getID() != '0'
            && $instanceSource->getIsToken('all') // @todo modifier le filtre social.
            && $instanceSource->getID() != '0'
            && $instanceSource->getIsWallet('all') // @todo modifier le filtre social.
        ) {
            // Extrait la monnaie.
            $CID = $instanceSource->getParam('CID');
            $instanceCID = $this->_nebuleInstance->newCurrency($CID);
            $modesCID = ' ' . $instanceCID->getParam('TRS') . ' ';

            // Vérifie que le mode LNS est présent dans les modes supportés par la monnaie
            if ($modesCID != '  '
                && strpos($modesCID, ' LNS ') !== false
            ) {
                $this->_transactionsArray[0]['CID'] = $CID;
                $this->_transactionsArray[0]['D'] = $this->_date;
                $this->_transactionsArray[0]['S'] = $this->_hashSigner;
                $this->_transactionsArray[0]['T'] = $this->_hashTarget;
                $this->_transactionsArray[0]['TID'] = $this->_hashSource;
                $this->_transactionsArray[0]['R'] = '1';
                $this->_transactionsArray[0]['TRS'] = 'LNS';
                $this->_isTransaction = true;
            } else {
                $this->_isTransaction = false;
            }
        } else {
            $this->_isTransaction = false;
        }
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Retourne si le lien est bien une transaction.
     *
     * @return boolean
     */
    public function getIsTransaction()
    {
        return $this->_isTransaction;
    }

    /**
     * Retourne l'ID de l'objet des transactions.
     * Retourne null en mode LNS
     *
     * @return string
     */
    public function getTransactionsObjetID()
    {
        return $this->_transactionsObjectID;
    }

    /**
     * Retourne la marque de temps des transactions.
     * Retourne null en mode LNS
     *
     * @return string
     */
    public function getTransactionsTimestamp()
    {
        return $this->_transactionsTimestamp;
    }

    /**
     * Retourne la liste des transactions unitaires.
     * Retourne null en mode LNS
     *
     * @return string
     */
    public function getTransactionsArray()
    {
        return $this->_transactionsArray;
    }
}


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
class Link
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

    /**
     * Instance nebule en cours.
     *
     * @var nebule
     */
    protected nebule $_nebuleInstance;

    /**
     * Instance io en cours.
     *
     * @var io
     */
    protected ioInterface $_io;

    /**
     * Instance crypto en cours.
     *
     * @var CryptoInterface $_crypto
     */
    protected CryptoInterface $_crypto;

    /**
     * Instance métrologie en cours.
     *
     * @var Metrology
     */
    protected Metrology $_metrology;

    /**
     * Texte lien complet "s.a_s_d_a_s_t_m" .
     *
     * @var string
     */
    protected string $_fullLink = '';

    /**
     * Parsed link contents.
     *
     * @var array $_parsedLink
     */
    protected array $_parsedLink = array();

    /**
     * Texte signature avec algorithme.
     *
     * @var string $_signe
     */
    protected string $_signe = '0';

    /**
     * Texte valeur hexa de la signature.
     *
     * @var string $_signeValue
     */
    protected string $_signeValue = '';

    /**
     * Texte algorithme de signature.
     *
     * @var string $_signeAlgo
     */
    protected string $_signeAlgo = '';

    /**
     * Texte hexa entité signataire.
     *
     * @var string $_hashSigner
     */
    protected string $_hashSigner = '0';

    /**
     * Texte date du lien.
     *
     * @var string $_date
     */
    protected string $_date = '';

    /**
     * Texte action du lien, sur un octet.
     *
     * @var string $_action
     */
    protected string $_action = '';

    /**
     * Texte hexa objet source.
     *
     * @var string $_hashSource
     */
    protected string $_hashSource = '0';

    /**
     * Texte hexa objet destination.
     *
     * @var string $_hashTarget
     */
    protected string $_hashTarget = '0';

    /**
     * Texte hexa objet méta.
     *
     * @var string $_hashMeta
     */
    protected string $_hashMeta = '0';

    /**
     * Booléen si le lien est dissimulé.
     *
     * @var boolean $_obfuscated
     */
    protected bool $_obfuscated = false;

    /**
     * Booléen si le lien a été vérifié.
     *
     * @var boolean $_verified
     */
    protected bool $_verified = false;

    /**
     * Booléen si le lien est vérifié et valide.
     *
     * @var boolean $_valid
     */
    protected bool $_valid = false;

    /**
     * Booléen si le lien a une structure valide.
     *
     * @var boolean $_validStructure
     */
    protected bool $_validStructure = false;

    /**
     * Booléen si le lien est signé.
     *
     * @var boolean $_signed
     */
    protected bool $_signed = false;

    /**
     * Nombre représentant un code d'erreur de vérification.
     *
     * @var integer $_verifyNumError
     */
    protected int $_verifyNumError = 0;

    /**
     * Texte de la description de l'erreur de vérification.
     *
     * @var string $_verifyTextError
     */
    protected string $_verifyTextError = 'Initialisation';

    /**
     * Booléen si la dissimulation de lien est autorisée.
     *
     * @var boolean $_permitObfuscated
     */
    protected bool $_permitObfuscated = false;


    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @param string $link
     * @return boolean
     */
    public function __construct(nebule $nebuleInstance, string $link)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_permitObfuscated = (bool)$nebuleInstance->getOption('permitObfuscatedLink');
        $this->_metrology->addLinkRead(); // Metrologie.

        // Extrait le lien et vérifie sa structure.
        if (!$this->_extract($link))
            return false;

        // Vérifie la validité du lien.
        if (!$this->_verify())
            return false;

        // Détecte si c'est un lien dissimulé.
        $this->_obfuscated = false;
        if ($this->_action == 'c') {
            // Extrait la partie dissimulée du lien si la clé de déchiffrement est accessible à l'entité.
            if (!$this->_extractObfuscated())
                return false;

            // Vérifie la validité de la partie dissimulée du lien.
            if ($this->_obfuscated
                && !$this->_verifyObfuscated()
            )
                return false;
        }

        // Actions supplémentaires pour les dérivés de liens.
        $this->_initialisation();

        return true;
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_fullLink;
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
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_permitObfuscated = (bool)$nebuleInstance->getOption('permitObfuscatedLink');
    }


    /**
     * Initialisation post-constructeur.
     *
     * @return void
     */
    protected function _initialisation(): void
    {
        // Rien à faire ici.
    }

    /**
     * Retourne le lien complet.
     *
     * @return string
     */
    public function getFullLink(): string
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_fullLink;
    }

    /**
     * Retourne le lien pré-décomposé.
     *
     * @return array
     */
    public function getParsedLink(): array
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_parsedLink;
    }

    /**
     * Retourne la valeur de la signature.
     *
     * @return string
     */
    public function getSigneValueAlgo_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_signeValue . '.' . $this->_signeAlgo;
    }

    /**
     * Retourne la valeur de la signature.
     *
     * @return string
     */
    public function getSigneValue_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_signeValue;
    }

    /**
     * Retourne l'algorithme de la signature.
     *
     * @return string
     */
    public function getSigneAlgo_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_signeAlgo;
    }

    /**
     * Retourne l'entité signataire du lien.
     *
     * @return string
     */
    public function getHashSigner_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_hashSigner;
    }

    /**
     * Retourne l'action du lien.
     *
     * @return string
     */
    public function getAction_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_action;
    }

    /**
     * Retourne la date du lien.
     *
     * @return string
     */
    public function getDate_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_date;
    }

    /**
     * Retourne l'objet source du lien.
     *
     * @return string
     */
    public function getHashSource_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_hashSource;
    }

    /**
     * Retourne l'objet cible du lien.
     *
     * @return string
     */
    public function getHashTarget_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_hashTarget;
    }

    /**
     * Retourne l'objet méta (contextualisation) du lien.
     *
     * @return string
     */
    public function getHashMeta_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_hashMeta;
    }

    /**
     * Retourne l'état de vérification et de validité du lien.
     *
     * @return boolean
     */
    public function getValid(): bool
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_valid;
    }

    /**
     * Retourne l'état de validité de la forme syntaxique du lien.
     *
     * @return boolean
     */
    public function getValidStructure(): bool
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_validStructure;
    }

    /**
     * Retourne si le lien a été vérifié dans sa forme syntaxique.
     *
     * @return boolean
     */
    public function getVerified(): bool
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_verified;
    }

    /**
     * Retourne le code d'erreur de vérification.
     * -1 : option activée de demande de ne pas tester les liens lors de la vérification - DANGER !!!
     * 0 : Le lien est valide.
     * 1 : Le lien a une structure invalide.
     * 2 : La signature a une structure invalide.
     * 3 : Le lien a une structure sale, sa reconstruction ne donne pas le même lien.
     * 11 : La signature est vide.
     * 12 : La valeur de la signature est invalide.
     * 13 : l'algorithme de signature est invalide.
     * 14 : La valeur de la signature est inconnue.
     * 15 : La signature est invalide.
     * 16 : Mode récupération, l'entité signataire n'est pas le puppetmaster.
     * 21 : L'identifiant du signataire est invalide.
     * 22 : L'objet du signataire n'est pas disponible.
     * 31 : La date est vide.
     * 32 : La date contient des carctères invalides.
     * 41 : L'action est invalide.
     * 51 : L'identifiant de l'objet source est invalide.
     * 52 : L'identifiant de l'objet source est null.
     * 61 : L'identifiant de l'objet cible est invalide.
     * 62 : L'identifiant de l'objet cible est null avec une action f/u/e/c/k.
     * 71 : L'identifiant de l'objet méta est invalide.
     * 72 : L'identifiant de l'objet méta est null avec une action c/k.
     *
     * @return number
     */
    public function getVerifyNumError()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_verifyNumError;
    }

    /**
     * Retourne le texte de description de l'erreur de vérification.
     * -1 : Option ask to not permit check sign on verify - DANGER !!!
     * 0 : Link is valid.
     * 1 : Link have invalid structure.
     * 2 : Signe have invalid structure.
     * 3 : Link have insane structure.
     * 11 : Signe is null.
     * 12 : Signe value is invalid.
     * 13 : Signe algorithm is invalid.
     * 14 : Signe value is unknown.
     * 15 : Signe is invalid.
     * 16 : RESCUE mode, signer is not code master.
     * 21 : Signer ID is invalid.
     * 22 : Signer object is not available.
     * 31 : Date is null.
     * 32 : Date have invalid char.
     * 41 : Action is invalid.
     * 51 : Source object ID is invalid.
     * 52 : Source object ID is null.
     * 61 : Target object ID is invalid.
     * 62 : Target object ID is null with action f/u/e/c/k.
     * 71 : Meta object ID is invalid.
     * 72 : Meta object ID is null with action c/k.
     *
     * @return string
     */
    public function getVerifyTextError()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_verifyTextError;
    }

    /**
     * Retourne si le lien est signé et si la signature est valide.
     * @return boolean
     */
    public function getSigned()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_signed;
    }

    /**
     * Retourne si le lien est dissimulé.
     * Dans ce cas les informations retournées sont les informations du lien non dissimulé.
     *
     * @return boolean
     */
    public function getObfuscated()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_obfuscated;
    }

    /**
     * Retourne la version avec laquelle est exploité le lien.
     * TODO à supprimer !
     * @return string
     */
    public function getVersion_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return '';
    }


    protected function _parse(string $link): array
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
     * Extraction du lien.
     * Extrait les champs d'un lien après avoir vérifié la cohérence de sa forme.
     * Ne vérifie pas la cohérence ou la validité des champs !
     *
     * Le nombre de champs doit être de 7.
     * Le champs signature peut être vide ou à 0 si c'est pour un nouveau lien à signer.
     *
     * @param string $link
     * @return boolean
     */
    protected function _extract($link)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr(trim($link), 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Doit être un texte.
        if (!is_string($link)) {
            return false;
        }

        $link = trim($link);
        $this->_valid = false;
        $this->_verified = false;

        // Indice du champs lu, de 1 à 7.
        $j = 1;

        // Tableau temporaire des champs du lien.
        $a = array();

        // Vérifie le nombre de champs, doit avoir 7 champs.
        $ok = false;

        // Première lecture des champs, premier champs.
        $e = strtok($link, '_');

        // Extrait le lien.
        while ($e !== false) {
            if ($j == 1) {
                $this->_signe = trim($e);
            } elseif ($j == 2) {
                $this->_hashSigner = trim($e);
            } elseif ($j == 3) {
                $this->_date = trim($e);
            } elseif ($j == 4) {
                $this->_action = trim($e);
            } elseif ($j == 5) {
                $this->_hashSource = trim($e);
            } elseif ($j == 6) {
                $this->_hashTarget = trim($e);
            } elseif ($j == 7) {
                $this->_hashMeta = trim($e);
                $ok = true;
            } else {
                // Ne doit pas avoir plus de 7 champs.
                $ok = false;
            }

            if ($j < 8) {
                // Lecture de la suite des champs, champs suivant.
                $e = strtok('_');
            } else {
                // Ne doit pas avoir plus de 7 champs.
                $e = false;
            }

            $j++;
        }
        unset($j, $a, $e);

        // Si erreur de lecture, quitte immédiatement et retourne le lien en erreur.
        if (!$ok) {
            $this->_verifyNumError = 1;
            $this->_verifyTextError = 'Link have invalid structure.';
            return false;
        }

        // Si le lien n'est pas un nouveau lien à signer.
        if ($this->_signe != '0'
            && $this->_signe != ''
        ) {
            // Extrait la signature et l'algorithme utilisé. Vérifie qu'ils sont présents.
            $this->_signeValue = trim(strtok(trim($this->_signe), '.'));
            $this->_signeAlgo = trim(strtok('.'));
            if ($this->_signeValue == ''
                || $this->_signeAlgo == ''
            ) {
                $this->_verifyNumError = 2;
                $this->_verifyTextError = 'Signe have invalid structure.';
                return false;
            }
        } else {
            $this->_signe = '0';
            $this->_signeValue = '0';
            $this->_signeAlgo = '';
            $this->_verifyNumError = 11;
            $this->_verifyTextError = 'Signe is null.';
        }

        // Reconstitue le lien pour vérification.
        if ($this->_signe == '0') {
            $rebuildLink = '0';
        } else {
            $rebuildLink = $this->_signeValue . '.' . $this->_signeAlgo;
        }
        $rebuildLink .= '_' . $this->_hashSigner;
        $rebuildLink .= '_' . $this->_date;
        $rebuildLink .= '_' . $this->_action;
        $rebuildLink .= '_' . $this->_hashSource;
        $rebuildLink .= '_' . $this->_hashTarget;
        $rebuildLink .= '_' . $this->_hashMeta;

        // Vérifie que le lien initial correspond au lien nettoyé reconstitué, sinon quitte et retourne le lien en erreur.
        if ($rebuildLink != $link) {
            $this->_verifyNumError = 3;
            $this->_verifyTextError = 'Link have insane structure.';
            return false;
        }

        // On mémorise le lien complet.
        $this->_fullLink = $link;

        // La structure du lien est valide.
        $this->_validStructure = true;

        return true;
    }


    /**
     * Vérification du lien.
     * Vérifie la cohérence et la validité des champs du lien.
     *
     * @return boolean
     */
    protected function _verify()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Tant que le lien n'est pas complètement vérifé, il est marqué invalide.
        $this->_valid = false;
        $this->_signed = false;
        $this->_verified = false;

        // Vérifie les différents champs.
        if (!$this->_verifyHashSigner()) {
            return false;
        }
        if (!$this->_verifyDate()) {
            return false;
        }
        if (!$this->_verifyAction()) {
            return false;
        }
        if (!$this->_verifyHashSource()) {
            return false;
        }
        if (!$this->_verifyHashTarget()) {
            return false;
        }
        if (!$this->_verifyHashMeta()) {
            return false;
        }

        // Ce lien est maintenant marqué comme ayant été vérifié et valide dans sa structure même si sa signature n'est pas encore reconnu valide.
        $this->_valid = true;
        $this->_verified = true;

        //         La vérification est-elle permise ?
        //   / \   DANGER !!! Si non permit, c'est très dangereux !!!
        //  / ! \
        //   ---   Is verify permitted ?
        //         DANGER !!! If not permitted, it's very dangerous !!!
        if (!$this->_nebuleInstance->getOption('permitCheckSignOnVerify')) {
            $this->_signed = false;
            $this->_verifyNumError = -1;
            $this->_verifyTextError = 'Option ask to not permit check sign on verify - DANGER !!!';
            return false;
        }

        // En dernier.
        if (!$this->_verifySign()) {
            return false;
        }

        // Fin de vérification.
        // Tout est bon.
        $this->_metrology->addLinkVerify(); // Metrologie.
        $this->_signed = true;     // Le lien est marqué avec signature valide.
        $this->_verifyNumError = 0;
        $this->_verifyTextError = 'Link is valid.';

        return true;
    }

    /**
     * Vérifie l'empreinte de l'objet signataire.
     *
     * @return boolean
     */
    protected function _verifyHashSigner()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // L'ID du signataire doit être en hexadécimal.
        if (!ctype_xdigit($this->_hashSigner)) {
            $this->_verifyNumError = 21;
            $this->_verifyTextError = 'Signer ID is invalid.';
            return false;
        }

        // L'objet du signataire doit être présent pour que la signature puisse être vérifiée.
        if (!$this->_io->checkObjectPresent($this->_hashSigner)) {
            $this->_verifyNumError = 22;
            $this->_verifyTextError = 'Signer object is not available.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie la date.
     *
     * @return boolean
     */
    protected function _verifyDate()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_date == '') {
            $this->_verifyNumError = 31;
            $this->_verifyTextError = 'Date is null.';
            return false;
        }

        $d = strlen($this->_date);
        for ($i = 0; $i < $d; $i++) {
            // Filtre sur les caractères 0-9 T W Z R Y P M D , : + - / et .
            // Spécifique au format de date ISO 8601:2004.
            $a = ord($this->_date[$i]);
            if (($a < 48
                    || $a > 57
                )
                && $a != 84 // T
                && $a != 87 // W
                && $a != 90 // Z
                && $a != 82 // R
                && $a != 89 // Y
                && $a != 80 // P
                && $a != 77 // M
                && $a != 68 // D
                && $a != 44 // ,
                && $a != 58 // :
                && $a != 43 // +
                && $a != 45 // -
                && $a != 47 // /
                && $a != 46 // .
            ) {
                $this->_verifyNumError = 32;
                $this->_verifyTextError = 'Date have invalid char. ' . $i . '(' . $a . '=' . $this->_date[$i] . ')' . $this->_date;
                return false;
            }
        }
        unset($d);

        return true;
    }

    /**
     * Vérifie l'action.
     *
     * @return boolean
     */
    protected function _verifyAction()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que l'action est d'un type connu.
        if ($this->_action != 'l'
            && $this->_action != 'f'
            && $this->_action != 'u'
            && $this->_action != 'd'
            && $this->_action != 'e'
            && $this->_action != 'c'
            && $this->_action != 'k'
            && $this->_action != 's'
            && $this->_action != 'x'
        ) {
            $this->_verifyNumError = 41;
            $this->_verifyTextError = 'Action is invalid.';
            return false;
        }

        // Vérifie que l'action de dissimulation de lien est autorisée.
        if ($this->_action == 'k'
            && !$this->_nebuleInstance->getOption('permitProtectedObject')
        ) {
            $this->_verifyNumError = 42;
            $this->_verifyTextError = 'Action k is not autorized.';
            return false;
        }

        // Vérifie que l'action de dissimulation de lien est autorisée.
        if ($this->_action == 'c'
            && !$this->_nebuleInstance->getOption('permitObfuscatedLink')
        ) {
            $this->_verifyNumError = 43;
            $this->_verifyTextError = 'Action c is not autorized.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie l'empreinte de l'objet source.
     *
     * @return boolean
     */
    protected function _verifyHashSource()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // L'ID de l'objet source doit être en hexadécimal.
        if (!ctype_xdigit($this->_hashSource)) {
            $this->_verifyNumError = 51;
            $this->_verifyTextError = 'Source object ID is invalid.';
            return false;
        }

        // L'ID de l'objet source ne doit pas être nul.
        if ($this->_hashSource == '0'
            || $this->_hashSource == ''
        ) {
            $this->_verifyNumError = 52;
            $this->_verifyTextError = 'Source object ID is null.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie l'empreinte de l'objet cible.
     *
     * @return boolean
     */
    protected function _verifyHashTarget()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // L'ID de l'objet cible doit être en hexadécimal.
        if (!ctype_xdigit($this->_hashTarget)) {
            $this->_verifyNumError = 61;
            $this->_verifyTextError = 'Target object ID is invalid.';
            return false;
        }

        // L'ID de l'objet cible ne doit pas être nul si c'est un lien f/u/e/c/k.
        if (($this->_hashTarget == '0'
                && ($this->_action == 'f'
                    || $this->_action == 'u'
                    || $this->_action == 'e'
                    || $this->_action == 'c'
                    || $this->_action == 'k'
                )
            )
            || $this->_hashTarget == ''
        ) {
            $this->_verifyNumError = 62;
            $this->_verifyTextError = 'Target object ID is null with action f/u/e/c/k.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie l'empreinte de l'objet méta.
     *
     * @return boolean
     */
    protected function _verifyHashMeta()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // L'ID de l'objet méta doit être en hexadécimal.
        if (!ctype_xdigit($this->_hashMeta)) {
            $this->_verifyNumError = 71;
            $this->_verifyTextError = 'Meta object ID is invalid.';
            return false;
        }

        // L'ID de l'objet méta ne doit pas être nul si c'est un lien c/k.
        if (($this->_hashMeta == '0'
                && ($this->_action == 'c'
                    || $this->_action == 'k'
                )
            )
            || $this->_hashMeta == ''
        ) {
            $this->_verifyNumError = 72;
            $this->_verifyTextError = 'Meta object ID is null with action c/k.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie la signature.
     * Doit être à la fin des vérifications !
     *
     * @return boolean
     */
    protected function _verifySign()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // La valeur de la signature ne doit pas être nulle.
        if ($this->_signe == '0') {
            $this->_verifyNumError = 11;
            $this->_verifyTextError = 'Signe is null.';
            return false;
        }

        // La valeur de la signature doit être en hexadécimal.
        if (!ctype_xdigit($this->_signeValue)) {
            $this->_verifyNumError = 12;
            $this->_verifyTextError = 'Signe value is invalid.';
            return false;
        }

        // La valeur de l'algorithme de signature doit être en alphadécimal.
        $s = strlen($this->_signeAlgo);
        for ($i = 0; $i < $s; $i++) {
            $a = ord($this->_signeAlgo[$i]);
            if ($a < 48
                || $a > 122
                || ($a > 57
                    && $a < 97
                )
            ) {
                $this->_verifyNumError = 13;
                $this->_verifyTextError = 'Signe algorithm is invalid.';
                return false;
            }
        }
        unset($s);

        // L'aglorithme doit être reconnu.
        if (!$this->_crypto->checkHashAlgorithm($this->_signeAlgo)) {
            $this->_verifyNumError = 14;
            $this->_verifyTextError = 'Signe value is unknown.';
            return false;
        }

        // Lit la clé publique.
        $pubkey = $this->_io->objectRead($this->_hashSigner, Entity::ENTITY_MAX_SIZE);

        // Génère le lien sans signature et son hash pour vérification.
        $shortLink = '_' . $this->_hashSigner . '_' . $this->_date . '_' . $this->_action . '_' . $this->_hashSource . '_' . $this->_hashTarget . '_' . $this->_hashMeta;
        $hashShortLink = $this->_crypto->hash($shortLink, $this->_signeAlgo);
        // Vérifie la signature avec la clé publique du signataire.
        if (!$this->_crypto->verify($hashShortLink, $this->_signeValue, $pubkey)) {
            $this->_verifyNumError = 15;
            $this->_verifyTextError = 'Signe is invalid.';
            return false;
        }
        unset($pubkey, $shortLink, $hashShortLink);

        // Si mode rescue, vérifie que le lien est du code master.
        if ($this->_nebuleInstance->getModeRescue()
            && $this->_hashSigner != $this->_nebuleInstance->getCodeAuthority()
        ) {
            $this->_verifyNumError = 16;
            $this->_verifyTextError = 'RESCUE mode, signer is not code master.';
            return false;
        }

        // Tout est bon.
        return true;
    }


    /**
     * Extraction de la partie dissimulée du lien.
     * Ne vérifie pas la cohérence ou la validité des champs !
     *
     * @return boolean
     */
    protected function _extractObfuscated()
    {
        // @todo
    }


    /**
     * Vérification de la partie dissimulée du lien.
     * Vérifie la cohérence et la validité des champs du lien extraits de la partie dissimulée.
     *
     * @return boolean
     */
    protected function _verifyObfuscated()
    {
        // @todo
    }


    /**
     * Signature du lien par l'entité en cours.
     *
     * @param string $publicKey
     * @return boolean
     */
    public function sign($publicKey = '0')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si autorisé à signer.
        if (!$this->_nebuleInstance->getOption('permitCreateLink')) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Can not sign link', Metrology::LOG_LEVEL_DEBUG); // Log
            return false;
        }

        // Si le lien est valide.
        if ($this->_validStructure
            && $this->_verified
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
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Sign link for ' . $pubkeyID, Metrology::LOG_LEVEL_DEBUG); // Log

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
                $this->_fullLink = $this->_signe . $shortLink;
                $this->_signed = true;
                $this->_valid = true;
                $this->_verified = true;
                $this->_verifyNumError = 0;
                $this->_verifyTextError = 'Signe is valid.';
                return true;
            }
        } else {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Invalid link', Metrology::LOG_LEVEL_DEBUG); // Log
        }

        return false;
    }

    /**
     * Ecrit le lien pour les objets concernés.
     *
     * @return boolean
     */
    public function write()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si autorisé à écrire.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
        ) {
            return false;
        }

        // Métrologie.
        $this->_nebuleInstance->getMetrologyInstance()->addAction('addlnk', $this->_fullLink, $this->_verified);

        // Si le lien n'est pas valide, quitte.
        if (!$this->_validStructure
            || !$this->_verified
            || !$this->_valid
            || !$this->_signed
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link unsigned', Metrology::LOG_LEVEL_DEBUG); // Log
            return false;
        }

        // Ecrit l'historique.
        if ($this->_nebuleInstance->getOption('permitHistoryLinksSign')) {
            $history = nebule::NEBULE_LOCAL_HISTORY_FILE;
            $this->_io->linkWrite($history, $this->_fullLink);
        }

        // Ecrit le lien pour l'objet de l'entité signataire.
        if ($this->_nebuleInstance->getOption('permitAddLinkToSigner')) {
            $this->_io->linkWrite($this->_hashSigner, $this->_fullLink);
        }

        if ($this->_action != 'c') {
            // Ecrit le lien pour l'objet source.
            $this->_io->linkWrite($this->_hashSource, $this->_fullLink);

            // Ecrit le lien pour l'objet cible.
            if ($this->_hashTarget != $this->_hashSource
                && $this->_hashTarget != '0'
            ) {
                $this->_io->linkWrite($this->_hashTarget, $this->_fullLink);
            }

            // Ecrit le lien pour l'objet méta.
            if ($this->_hashMeta != $this->_hashSource
                && $this->_hashMeta != $this->_hashTarget
                && $this->_hashMeta != '0'
            ) {
                $this->_io->linkWrite($this->_hashMeta, $this->_fullLink);
            }
        } elseif ($this->_nebuleInstance->getOption('permitObfuscatedLink')) {
            // Ecrit le lien dissimulé.
            $this->_io->linkWrite($this->_hashSigner . '-' . $this->_hashSource, $this->_fullLink); // @todo
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
    public function signWrite($publicKey = '0')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->sign($publicKey)) {
            return $this->write();
        }
        return false;
    }


    /**
     * Offusque le lien. Ne pas oublier de l'écrire.
     * @return boolean
     * @todo
     *
     * Le lien à dissimulé est concaténé avec un bourrage (padding) d'espace de taille aléatoire compris entre 3 et 5 fois la taille du champs source.
     *
     */
    public function obfuscate()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if (!$this->_obfuscated
            && $this->_verified
            && $this->_valid
            && $this->_verifyNumError == 0
            && $this->_permitObfuscated
        ) {
            // @todo
        }

        return false;
    }

    /**
     * Offusque et écrit le lien.
     *
     * @return boolean
     */
    public function obfuscateWrite()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie si autorisé à dissimuler des liens.
        if (!$this->_permitObfuscated)
            return false;

        $this->obfuscate();
        return $this->write();
    }

    /**
     * Désoffusque le lien. Ne pas oublier de l'écrire.
     * @return boolean
     * @todo
     *
     */
    public function deobfuscate()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_obfuscated
            && $this->_verified
            && $this->_valid
            && $this->_verifyNumError == 0
        ) {
            // @todo
        }

        return false;
    }

    /**
     * Désoffusque et écrit le lien.
     *
     * @return boolean
     */
    public function deobfuscateWrite()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->deobfuscate();
        return $this->write();
    }

    /**
     * Extrait le lien offusqué.
     * @return boolean
     * @todo
     *
     */
    public function decrypt()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_obfuscated
            && $this->_verified
            && $this->_valid
            && $this->_verifyNumError == 0
        ) {
            // @todo
        }
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#l">L / Lien</a>
            <ul>
                <li><a href="#lelpo">LELPO / Liens à Propos d’un Objet</a></li>
                <li><a href="#lelco">LELCO / Liens Contenu dans un Objet</a></li>
                <li><a href="#le">LE / Entête</a></li>
                <li><a href="#lr">LR / Registre</a>
                    <ul>
                        <li><a href="#lrsi">LRSI / Le champ <code>Signature</code></a></li>
                        <li><a href="#lrusi">LRHSI / Le champ <code>HashSignataire</code></a></li>
                        <li><a href="#lrt">LRT / Le champ <code>TimeStamp</code></a></li>
                        <li><a href="#lra">LRA / Le champ <code>Action</code></a>
                            <ul>
                                <li><a href="#lral">LRAL / Action <code>l</code> – Lien entre objets</a></li>
                                <li><a href="#lraf">LRAF / Action <code>f</code> – Dérivé d’objet</a></li>
                                <li><a href="#lrau">LRAU / Action <code>u</code> – Mise à jour d’objet</a></li>
                                <li><a href="#lrad">LRAD / Action <code>d</code> – Suppression d’objet</a></li>
                                <li><a href="#lrae">LRAE / Action <code>e</code> – Équivalence d’objets</a></li>
                                <li><a href="#lrac">LRAC / Action <code>c</code> – Chiffrement de lien</a></li>
                                <li><a href="#lrak">LRAK / Action <code>k</code> – Chiffrement d’objet</a></li>
                                <li><a href="#lras">LRAS / Action <code>s</code> – Subdivision d’objet</a></li>
                                <li><a href="#lrax">LRAX / Action <code>x</code> – Suppression de lien</a></li>
                            </ul>
                        </li>
                        <li><a href="#lrhs">LRHS / Le champ <code>HashSource</code></a></li>
                        <li><a href="#lrhc">LRHC / Le champ <code>HashCible</code></a></li>
                        <li><a href="#lrhm">LRHM / Le champ <code>HashMeta</code></a></li>
                    </ul>
                </li>
                <li><a href="#l1">L1 / Lien simple</a></li>
                <li><a href="#l2">L2 / Lien double</a></li>
                <li><a href="#l3">L3 / Lien triple</a></li>
                <li><a href="#ls">LS / Stockage</a>
                    <ul>
                        <li><a href="#lsa">LSA / Arborescence</a></li>
                        <li><a href="#lsd">LSD / Dissimulation</a>
                            <ul>
                                <li><a href="#lsdrp">LSDRP / Registre public</a></li>
                                <li><a href="#lsdrd">LSDRD / Registre dissimulé</a></li>
                                <li><a href="#lsda">LSDA / Attaque sur la dissimulation</a></li>
                                <li><a href="#lsds">LSDS / Stockage et transcodage</a>
                                    <ul>
                                        <li><a href="#lsdst">LSDST / Translation de lien</a></li>
                                        <li><a href="#lsdsp">LSDSP / Protection de translation</a></li>
                                    </ul>
                                </li>
                                <li><a href="#lsdt">LSDT / Transfert et partage</a></li>
                                <li><a href="#lsdc">LSDC / Compromission</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#lt">LT / Transfert</a></li>
                <li><a href="#lv">LV / Vérification</a></li>
                <li><a href="#lo">LO / Oubli</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h1 id="l">L / Lien</h1>
        <p>Le lien est la matérialisation dans un graphe d’une relation entre deux objets pondéré par un troisième
            objet.</p>

        <h5 id="lelpo">LELPO / Liens à Propos d’un Objet</h5>
        <p>Les liens d’un objet sont consultables séquentiellement. Il doivent être perçus comme des méta-données d’un
            objet.</p>
        <p>Les liens sont séparés soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;». Un
            lien est donc une suite de caractères ininterrompue, c’est à dire sans espace ou retour à la ligne.</p>
        <p>La taille du lien dépend de la taille de chaque champs.</p>
        <p>Chaque localisation contenant des liens doit avoir un entête de version.</p>

        <h5 id="lelco">LELCO / Liens Contenu dans un Objet</h5>
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

        <h2 id="le">LE / Entête</h2>
        <p>L’entête des liens est constitué du texte <code>nebule/liens/version/1.2</code>. Il est séparé du premier
            lien soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Il doit être transmit avec les liens, en premier.</p>

        <h2 id="lr">LR / Registre</h2>
        <p>Le registre du lien décrit la syntaxe du lien :</p>
        <p style="text-align:center">
            <code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>
        <p>Ce registre a un nombre de champs fixe. Chaque champs a une place fixe dans le lien. Les champs ont une
            taille variable. Le séparateur de champs est l’underscore «&nbsp;_&nbsp;». Les champs ne peuvent contenir ni
            l’underscore «&nbsp;_&nbsp;» ni l’espace &nbsp;» &nbsp;» ni le retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Tout lien qui ne respecte pas cette syntaxe est à considérer comme invalide et à supprimer. Tout lien dont la
            <code>Signature</code> est invalide est à considérer comme invalide et à supprimer. La vérification peut
            être réalisée en ré-assemblant les champs après nettoyage.</p>

        <h4 id="lrsi">LRSI / Le champ <code>Signature</code></h4>
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

        <h5 id="lrusi">LRHSI / Le champ <code>HashSignataire</code></h5>
        <p>Le champ <code>HashSignataire</code> désigne l’objet de l’entité qui génère le lien et le signe.</p>
        <p>Il ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;» à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;»
            à «&nbsp;f&nbsp;» en minuscule.</p>

        <h3 id="lrt">LRT / Le champ <code>TimeStamp</code></h3>
        <p>Le champ <code>TimeStamp</code> est une marque de temps qui donne un ordre temporel aux liens. Ce champs peut
            être une date et une heure au format <a class="external text" title="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    href="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    rel="nofollow">ISO8601</a> ou simplement un compteur incrémental.
        </p>

        <h3 id="lra">LRA / Le champ <code>Action</code></h3>
        <p>Le champ <code>Action</code> détermine la façon dont le lien doit être utilisé.</p>
        <p>Quand on parle du type d’un lien, on fait référence à son champ <code>Action</code>.</p>
        <p>L’interprétation de ce champ est limité au premier caractère. Des caractères alpha-numériques supplémentaires
            sont autorisés mais ignorés.</p>
        <p>Cette interprétation est basée sur un vocabulaire particulier. Ce vocabulaire est spécifique à <i>nebule
                v1.2</i> (et <i>nebule v1.1</i>).</p>
        <p>Le vocabulaire ne reconnaît que les 8 caractères <code>l</code>, <code>f</code>, <code>u</code>,
            <code>d</code>, <code>e</code>, <code>x</code>, <code>k</code> et <code>s</code>, en minuscule.</p>

        <h4 id="lral">LRAL / Action <code>l</code> – Lien entre objets</h4>
        <p>Met en place une relation entre deux objets. Cette relation a un sens de mise en place et peut être pondérée
            par un objet méta.</p>
        <p>Les liens de type <code>l</code> ne devraient avoir ni <code>HashMeta</code> nul ni <code>HashCible</code>
            nul.</p>

        <h4 id="lraf">LRAF / Action <code>f</code> – Dérivé d’objet</h4>
        <p>Le nouvel objet est considéré comme enfant ou parent suivant le sens du lien.</p>
        <p>Le champs <code>ObjetMeta</code> doit être vu comme le contexte du lien. Par exemple, deux objets contenants
            du texte peuvent être reliés simplement sans contexte, c’est à dire reliés de façon simplement hiérarchique.
            Ces deux mêmes textes peuvent être plutôt (ou en plus) reliés avec un contexte comme celui d’une discussion
            dans un blog. Dans ce deuxième cas, la relation entre les deux textes n’a pas de sens en dehors de cette
            discussion sur ce blog. Il est même probable que le blog n’affichera pas les autres textes en relations si
            ils n’ont pas un contexte appartenant à ce blog.</p>
        <p><code>f</code> comme <i>fork</i>.</p>

        <h4 id="lrau">LRAU / Action <code>u</code> – Mise à jour d’objet</h4>
        <p>Mise à jour d’un objet dérivé qui remplace l’objet parent.</p>
        <p><code>u</code> comme <i>update</i>.</p>

        <h4 id="lrad">LRAD / Action <code>d</code> – Suppression d’objet</h4>
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

        <h4 id="lrae">LRAE / Action <code>e</code> – Équivalence d’objets</h4>
        <p>Définit des objets jugés équivalents, et donc interchangeables par exemple pour une traduction.</p>

        <h4 id="lrac">LRAC / Action <code>c</code> – Chiffrement de lien</h4>
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
            identifiants des objets originaux (cf <a href="#ld">LD</a>).</p>
        <p>L'option <code>permitObfuscatedLink</code> permet de désactiver la dissimulation (offuscation) des liens des
            objets. Dans ce cas le lien de type <code>c</code> est rejeté comme invalide avec le code erreur 43.</p>

        <h4 id="lrak">LRAK / Action <code>k</code> – Chiffrement d’objet</h4>
        <p>Désigne la version chiffrée de l’objet (cf <a href="#cko">CKO</a>).</p>
        <p>L'option <code>permitProtectedObject</code> permet de désactiver la protection (chiffrement) des objets. Dans
            ce cas le lien de type <code>k</code> est rejeté comme invalide avec le code erreur 42.</p>

        <h4 id="lras">LRAS / Action <code>s</code> – Subdivision d’objet</h4>
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

        <h4 id="lrax">LRAX / Action <code>x</code> – Suppression de lien</h4>
        <p>Supprime un ou plusieurs liens précédemment mis en place.</p>
        <p>Les liens concernés par la suppression sont les liens antérieurs de type <code>l</code>, <code>f</code>,
            <code>u</code>, <code>d</code>, <code>e</code>, <code>k</code> et <code>s</code>. Ils sont repérés par les 3
            derniers champs, c’est à dire sur <code>HashSource_HashCible_HashMeta</code>. Les champs nuls sont
            strictement pris en compte.</p>
        <p>Le champ <code>TimeStamp</code> permet de déterminer l’antériorité du lien et donc de déterminer sa
            suppression ou pas.</p>
        <p>C’est la seule action sur les liens et non sur les objets.</p>

        <h4 id="lrhs">LRHS / Le champ <code>HashSource</code></h4>
        <p>Le champ <code>HashSource</code> désigne l’objet source du lien.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>

        <h4 id="lrhc">LRHC / Le champ <code>HashCible</code></h4>
        <p>Le champ <code>HashCible</code> désigne l’objet destination du lien.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c’est à dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <h4 id="lrhm">LRHM / Le champ <code>HashMeta</code></h4>
        <p>Le champ <code>HashMeta</code> désigne l’objet contenant une caractérisation du lien entre l’objet source et
            l’objet destination.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c’est à dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <h2 id="l1">L1 / Lien simple</h2>
        <p>Le registre du lien simple a ses champs <code>HashCible</code> et <code>HashMeta</code> égaux à «&nbsp;0&nbsp;».
        </p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_0_0</code></p>

        <h2 id="l2">L2 / Lien double</h2>
        <p>Le registre du lien double a son champ <code>HashMeta</code> égal à «&nbsp;0&nbsp;».</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_0</code></p>

        <h2 id="l3">L3 / Lien triple</h2>
        <p>Le registre du lien triple est complètement utilisé.</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>

        <h2 id="ls">LS / Stockage</h2>
        <p>Tous les liens sont stockés dans un même emplacement ou sont visible comme étant dans un même emplacement.
            Cet emplacement ne contient pas les contenus des objets (cf <a href="#oos">OOS</a>).</p>
        <p>Le lien dissimulé est stocké dans le même emplacement mais dispose de fichiers de stockages différents du
            fait de la spécificité (cf <a href="#lsds">LSDS</a>).</p>

        <h3 id="lsa">LSA / Arborescence</h3>
        <p>Sur un système de fichiers, tous les liens sont stockés dans des fichiers contenus dans le dossier <code>pub/l/</code>
            (<code>l</code> comme lien).</p>
        <p>A faire...</p>

        <h3 id="lsd">LSD / Dissimulation</h3>
        <p>Le lien de dissimulation, de type <code>c</code>, contient un lien dissimulé sans signature (cf <a
                    href="#lrac">LRAC</a>). Il permet d’offusquer des liens entre objets et donc d’anonymiser certaines
            actions de l’entité (cf <a href="#ckl">CKL</a>).</p>

        <h5 id="lsdrp">LSDRP / Registre public</h5>
        <p>Le registre du lien de dissimulation, public par nature, est conforme au registre des autres liens (cf <a
                    href="#lr">LR</a>). Si ce lien ne respectait pas cette structure il serait automatiquement ignoré ou
            rejeté. Son stockage et sa transmission ont cependant quelques particularités.</p>
        <p>Les champs <code>Signature</code> (cf <a href="#lrsi">LRSI</a>) et <code>HashSignataire</code> (cf <a
                    href="#lrhsi">LRHSI</a>) du registre sont conformes aux autres liens. Ils assurent la protection du
            lien. Le champs signataire fait office d'émeteur du lien dissimulé.</p>
        <p>Le champs <code>TimeStamp</code> (cf <a href="#lrt">LRT</a>) du registre est conformes aux autres liens. Il a
            cependant une valeur non significative et sourtout pas liée au <code>TimeStamp</code> du lien dissimulé.</p>
        <p>Le champs <code>Action</code> (cf <a href="#lrt">LRT</a>) du registre est de type <code>c</code> (cf <a
                    href="#lra">LRA</a> et <a href="#lrac">LRAC</a>).</p>
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

        <h5 id="lsdrd">LSDRD / Registre dissimulé</h5>
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

        <h4 id="lsda">LSDA / Attaque sur la dissimulation</h4>
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

        <h4 id="lsds">LSDS / Stockage et transcodage</h4>
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

        <h5 id="lsdst">LSDST / Translation de lien</h5>
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

        <h5 id="lsdsp">LSDSP / Protection de translation</h5>
        <p>A faire...</p>

        <h4 id="lsdt">LSDT / Transfert et partage</h4>
        <p>A faire...</p>

        <h4 id="lsdc">LSDC / Compromission</h4>
        <p>A faire...</p>

        <h2 id="lt">LT / Transfert</h2>
        <p>A faire...</p>

        <h2 id="lv">LV / Vérification</h2>
        <p>La signature d’un lien doit être vérifiée lors de la fin de la réception du lien. La signature d’un lien
            devrait être vérifiée avant chaque utilisation de ce lien. Un lien avec une signature invalide doit être
            supprimé. Lors de la suppression d’un lien, les autres liens de cet objet ne sont pas supprimés et l'objet
            n'est pas supprimé. La vérification de la validité des objets est complètement indépendante de celle des
            liens, et inversement (cf <a href="#cl">CL</a> et <a href="#oov">OOV</a>).</p>
        <p>Toute modification de l’un des champs du lien entraîne l’invalidation de tout le lien.</p>

        <h2 id="lo">LO / Oubli</h2>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}
























