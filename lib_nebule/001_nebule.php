<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * The nebule core library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class nebule
{
    // Définition des constantes.
    const NEBULE_LICENCE_NAME = 'nebule';
    const NEBULE_LICENCE_LINK = 'http://www.nebule.org/';
    const NEBULE_LICENCE_DATE = '2010-2020';
    const NEBULE_ENVIRONMENT_FILE = 'nebule.env';
    const NEBULE_BOOTSTRAP_FILE = 'index.php';
    const NEBULE_MINIMUM_ID_SIZE = 6;
    const NEBULE_LOCAL_ENTITY_FILE = 'e';
    const NEBULE_LOCAL_OBJECTS_FOLDER = 'o';
    const NEBULE_LOCAL_LINKS_FOLDER = 'l';
    const NEBULE_LOCAL_HISTORY_FILE = 'f';    // Dans le dossier /l/
    const PUPPETMASTER_URL = 'http://puppetmaster.nebule.org';
    const SECURITY_MASTER_URL = 'http://security.master.nebule.org';
    const CODE_MASTER_URL = 'http://code.master.nebule.org';

    // Les commandes.
    const COMMAND_SWITCH_APPLICATION = 'a';
    const COMMAND_FLUSH = 'f';
    const COMMAND_RESCUE = 'r';
    const COMMAND_LOGOUT_ENTITY = 'logout';
    const COMMAND_SWITCH_TO_ENTITY = 'switch';
    const COMMAND_SELECT_OBJECT = 'obj';
    const COMMAND_SELECT_LINK = 'lnk';
    const COMMAND_SELECT_ENTITY = 'ent';
    const COMMAND_SELECT_GROUP = 'grp';
    const COMMAND_SELECT_CONVERSATION = 'cvt';
    const COMMAND_SELECT_CURRENCY = 'cur';
    const COMMAND_SELECT_TOKENPOOL = 'tkp';
    const COMMAND_SELECT_TOKEN = 'tkn';
    const COMMAND_SELECT_WALLET = 'wal';
    const COMMAND_SELECT_TRANSACTION = 'trs';
    const COMMAND_SELECT_PASSWORD = 'pwd';
    const COMMAND_SELECT_TICKET = 'tkt';

    // Les références.
    const REFERENCE_OBJECT_TEXT = 'text/plain';
    const REFERENCE_OBJECT_HTML = 'text/html';
    const REFERENCE_OBJECT_CSS = 'text/css';
    const REFERENCE_OBJECT_PHP = 'text/x-php';
    const REFERENCE_OBJECT_APP_PHP = 'application/x-php';
    const REFERENCE_OBJECT_PNG = 'image/png';
    const REFERENCE_OBJECT_JPEG = 'image/jpeg';
    const REFERENCE_OBJECT_MP3 = 'audio/mpeg';
    const REFERENCE_OBJECT_OGG = 'audio/x-vorbis+ogg';
    const REFERENCE_OBJECT_CRYPT_RSA = 'application/x-encrypted/rsa';
    const REFERENCE_OBJECT_ENTITY = 'application/x-pem-file';
    const REFERENCE_ENTITY_HEADER = '-----BEGIN PUBLIC KEY-----';
    const REFERENCE_CRYPTO_HASH_ALGORITHM = 'sha2.256';

    // Les objets références de nebule.
    const REFERENCE_NEBULE_OBJET = 'nebule/objet';
    const REFERENCE_NEBULE_OBJET_HASH = 'nebule/objet/hash';
    const REFERENCE_NEBULE_OBJET_HOMOMORPHE = 'nebule/objet/homomorphe';
    const REFERENCE_NEBULE_OBJET_TYPE = 'nebule/objet/type';
    const REFERENCE_NEBULE_OBJET_LOCALISATION = 'nebule/objet/localisation';
    const REFERENCE_NEBULE_OBJET_TAILLE = 'nebule/objet/taille';
    const REFERENCE_NEBULE_OBJET_PRENOM = 'nebule/objet/prenom';
    const REFERENCE_NEBULE_OBJET_NOM = 'nebule/objet/nom';
    const REFERENCE_NEBULE_OBJET_SURNOM = 'nebule/objet/surnom';
    const REFERENCE_NEBULE_OBJET_PREFIX = 'nebule/objet/prefix';
    const REFERENCE_NEBULE_OBJET_SUFFIX = 'nebule/objet/suffix';
    const REFERENCE_NEBULE_OBJET_LIEN = 'nebule/objet/lien';
    const REFERENCE_NEBULE_OBJET_DATE = 'nebule/objet/date';
    const REFERENCE_NEBULE_OBJET_DATE_ANNEE = 'nebule/objet/date/annee';
    const REFERENCE_NEBULE_OBJET_DATE_MOIS = 'nebule/objet/date/mois';
    const REFERENCE_NEBULE_OBJET_DATE_JOUR = 'nebule/objet/date/jour';
    const REFERENCE_NEBULE_OBJET_DATE_HEURE = 'nebule/objet/date/heure';
    const REFERENCE_NEBULE_OBJET_DATE_MINUTE = 'nebule/objet/date/minute';
    const REFERENCE_NEBULE_OBJET_DATE_SECONDE = 'nebule/objet/date/seconde';
    const REFERENCE_NEBULE_OBJET_DATE_ZONE = 'nebule/objet/date/zone';
    const REFERENCE_NEBULE_OBJET_ENTITE = 'nebule/objet/entite';
    const REFERENCE_NEBULE_OBJET_ENTITE_TYPE = 'nebule/objet/entite/type';
    const REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION = 'nebule/objet/entite/localisation';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI = 'nebule/objet/entite/suivi';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_SECONDE = 'nebule/objet/entite/suivi/seconde';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_MINUTE = 'nebule/objet/entite/suivi/minute';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_HEURE = 'nebule/objet/entite/suivi/heure';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_JOUR = 'nebule/objet/entite/suivi/jour';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_SEMAINE = 'nebule/objet/entite/suivi/semaine';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_MOIS = 'nebule/objet/entite/suivi/mois';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_ANNEE = 'nebule/objet/entite/suivi/annee';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE = 'nebule/objet/entite/maitre';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_SECURITE = 'nebule/objet/entite/maitre/securite';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_CODE = 'nebule/objet/entite/maitre/code';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_ANNUAIRE = 'nebule/objet/entite/maitre/annuaire';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_TEMPS = 'nebule/objet/entite/maitre/temps';
    const REFERENCE_NEBULE_OBJET_ENTITE_AUTORITE_LOCALE = 'nebule/objet/entite/autorite/locale';
    const REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT = 'nebule/objet/entite/recouvrement';
    const REFERENCE_NEBULE_OBJET_INTERFACE_BOOTSTRAP = 'nebule/objet/interface/web/php/bootstrap';
    const REFERENCE_NEBULE_OBJET_INTERFACE_BIBLIOTHEQUE = 'nebule/objet/interface/web/php/bibliotheque';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APPLICATIONS = 'nebule/objet/interface/web/php/applications';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APP_DIRECT = 'nebule/objet/interface/web/php/applications/direct';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APP_ACTIVE = 'nebule/objet/interface/web/php/applications/active';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES = 'nebule/objet/interface/web/php/applications/modules';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APP_MOD_ACTIVE = 'nebule/objet/interface/web/php/applications/modules/active';
    const REFERENCE_NEBULE_OBJET_NOEUD = 'nebule/objet/noeud';
    const REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE = 'nebule/objet/image/reference';
    const REFERENCE_NEBULE_OBJET_EMOTION = 'nebule/objet/emotion';
    const REFERENCE_NEBULE_OBJET_EMOTION_JOIE = 'nebule/objet/emotion/joie';
    const REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE = 'nebule/objet/emotion/confiance';
    const REFERENCE_NEBULE_OBJET_EMOTION_PEUR = 'nebule/objet/emotion/peur';
    const REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE = 'nebule/objet/emotion/surprise';
    const REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE = 'nebule/objet/emotion/tristesse';
    const REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT = 'nebule/objet/emotion/degout';
    const REFERENCE_NEBULE_OBJET_EMOTION_COLERE = 'nebule/objet/emotion/colere';
    const REFERENCE_NEBULE_OBJET_EMOTION_INTERET = 'nebule/objet/emotion/interet';
    const REFERENCE_NEBULE_OBJET_GROUPE = 'nebule/objet/groupe';
    const REFERENCE_NEBULE_OBJET_GROUPE_SUIVI = 'nebule/objet/groupe/suivi';
    const REFERENCE_NEBULE_OBJET_GROUPE_FERME = 'nebule/objet/groupe/ferme';
    const REFERENCE_NEBULE_OBJET_GROUPE_PROTEGE = 'nebule/objet/groupe/protege';
    const REFERENCE_NEBULE_OBJET_GROUPE_DISSIMULE = 'nebule/objet/groupe/dissimule';
    const REFERENCE_NEBULE_OBJET_CONVERSATION = 'nebule/objet/conversation';
    const REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE = 'nebule/objet/conversation/suivie';
    const REFERENCE_NEBULE_OBJET_CONVERSATION_FERMEE = 'nebule/objet/conversation/fermee';
    const REFERENCE_NEBULE_OBJET_CONVERSATION_PROTEGEE = 'nebule/objet/conversation/protegee';
    const REFERENCE_NEBULE_OBJET_CONVERSATION_DISSIMULEE = 'nebule/objet/conversation/dissimulee';
    const REFERENCE_NEBULE_OBJET_ARBORESCENCE = 'nebule/objet/arborescence';
    const REFERENCE_NEBULE_OBJET_MONNAIE = 'nebule/objet/monnaie';
    const REFERENCE_NEBULE_OBJET_MONNAIE_JETON = 'nebule/objet/monnaie/jeton';
    const REFERENCE_NEBULE_OBJET_MONNAIE_SAC = 'nebule/objet/monnaie/sac';
    const REFERENCE_NEBULE_OBJET_MONNAIE_PORTEFEUILLE = 'nebule/objet/monnaie/portefeuille';
    const REFERENCE_NEBULE_OBJET_MONNAIE_TRANSACTION = 'nebule/objet/monnaie/transaction';
    const REFERENCE_NEBULE_OPTION = 'nebule/option';
    const REFERENCE_NEBULE_DANGER = 'nebule/danger';
    const REFERENCE_NEBULE_WARNING = 'nebule/warning';
    const REFERENCE_NEBULE_REFERENCE = 'nebule/reference';

    const ACTIVE_APPLICATIONS_WHITELIST = array(
        '2121510000000000006e6562756c65206170706c69636174696f6e73000000000000212151.non.296',
    );

    /**
     * Liste des objets à usage réservé.
     */
    static private $RESERVED_OBJECTS_LIST = array(
        'nebule/objet',
        'nebule/objet/hash',
        'nebule/objet/homomorphe',
        'nebule/objet/type',
        'nebule/objet/localisation',
        'nebule/objet/taille',
        'nebule/objet/prenom',
        'nebule/objet/nom',
        'nebule/objet/surnom',
        'nebule/objet/prefix',
        'nebule/objet/suffix',
        'nebule/objet/lien',
        'nebule/objet/date',
        'nebule/objet/date/annee',
        'nebule/objet/date/mois',
        'nebule/objet/date/jour',
        'nebule/objet/date/heure',
        'nebule/objet/date/minute',
        'nebule/objet/date/seconde',
        'nebule/objet/date/zone',
        'nebule/objet/entite',
        'nebule/objet/entite/type',
        'nebule/objet/entite/localisation',
        'nebule/objet/entite/suivi',
        'nebule/objet/entite/suivi/seconde',
        'nebule/objet/entite/suivi/minute',
        'nebule/objet/entite/suivi/heure',
        'nebule/objet/entite/suivi/jour',
        'nebule/objet/entite/suivi/mois',
        'nebule/objet/entite/suivi/annee',
        'nebule/objet/entite/maitre',
        'nebule/objet/entite/maitre/securite',
        'nebule/objet/entite/maitre/code',
        'nebule/objet/entite/maitre/annuaire',
        'nebule/objet/entite/maitre/temps',
        'nebule/objet/entite/autorite/locale',
        'nebule/objet/entite/recouvrement',
        'nebule/objet/interface/web/php/bootstrap',
        'nebule/objet/interface/web/php/bibliotheque',
        'nebule/objet/interface/web/php/applications',
        'nebule/objet/interface/web/php/applications/direct',
        'nebule/objet/interface/web/php/applications/active',
        'nebule/objet/interface/web/php/applications/modules',
        'nebule/objet/interface/web/php/applications/modules/active',
        'nebule/objet/noeud',
        'nebule/objet/image/reference',
        'nebule/objet/emotion',
        'nebule/objet/emotion/joie',
        'nebule/objet/emotion/confiance',
        'nebule/objet/emotion/peur',
        'nebule/objet/emotion/surprise',
        'nebule/objet/emotion/tristesse',
        'nebule/objet/emotion/degout',
        'nebule/objet/emotion/colere',
        'nebule/objet/emotion/interet',
        'nebule/objet/groupe',
        'nebule/objet/groupe/suivi',
        'nebule/objet/groupe/ferme',
        'nebule/objet/groupe/protege',
        'nebule/objet/groupe/dissimule',
        'nebule/objet/conversation',
        'nebule/objet/conversation/suivie',
        'nebule/objet/conversation/fermee',
        'nebule/objet/conversation/protegee',
        'nebule/objet/conversation/dissimulee',
        'nebule/objet/arborescence',
        'nebule/objet/monnaie',
        'nebule/objet/monnaie/jeton',
        'nebule/objet/monnaie/sac',
        'nebule/objet/monnaie/portefeuille',
        'nebule/objet/monnaie/transaction',
        'nebule/option',
        'nebule/danger',
        'nebule/warning',
        'nebule/reference',
    );

    // Définition des variables.
    /**
     * Auto-référence de l'instance de la bibliothèque nebule.
     *
     * @var nebule
     */
    private $_nebuleInstance;

    /**
     * Instance de gestion de la métrologie, des journaux et des statistiques internes.
     *
     * @var Metrology
     */
    private $_metrology;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    private $_configuration;

    /**
     * Instance des entrées/sorties.
     *
     * @var ioInterface
     */
    private $_io;

    /**
     * Instance de gestion de la cryptographie.
     *
     * @var CryptoInterface
     */
    private $_crypto;

    /**
     * Instance de gestion des relations sociales des liens.
     *
     * @var SocialInterface
     */
    private $_social;

    /**
     * Le tableau de mise en cache des liens.
     *
     * @var array
     */
    private $_cacheLinks = array();

    /**
     * Le tableau de mise en cache des objets.
     *
     * @var array
     */
    private $_cacheObjects = array();

    /**
     * Le tableau de mise en cache des entités.
     *
     * @var array
     */
    private $_cacheEntities = array();

    /**
     * Le tableau de mise en cache des groupes.
     *
     * @var array
     */
    private $_cacheGroups = array();

    /**
     * Le tableau de mise en cache des conversations.
     *
     * @var array
     */
    private $_cacheConversations = array();

    /**
     * Le tableau de mise en cache des monnaies.
     *
     * @var array
     */
    private $_cacheCurrencies = array();

    /**
     * Le tableau de mise en cache des sacs de jetons.
     *
     * @var array
     */
    private $_cacheTokenPools = array();

    /**
     * Le tableau de mise en cache des jetons.
     *
     * @var array
     */
    private $_cacheTokens = array();

    /**
     * Le tableau de mise en cache des portefeuille.
     *
     * @var array
     */
    private $_cacheWallets = array();

    /**
     * Le tableau de mise en cache des transactions.
     *
     * @var array
     */
    private $_cacheTransactions = array();

    /**
     * Le tableau de mémorisation de la date de mise en cache des objets/entités/groupes/conversations/liens.
     *
     * @var array
     */
    private $_cacheDateInsertion = array();

    /**
     * Taille du cache.
     *
     * @var int
     */
    private $_sessionBufferLimit = 0;

    private $_flushCache = false;



    /**
     * Constructeur.
     */
    public function __construct()
    {
        global $metrologyStartTime;
        syslog(LOG_INFO, 'LogT=' . (microtime(true) - $metrologyStartTime) . ' LogL="info" LogI="1452ed72" LogF="include nebule library" LogM="Loading nebule library"');

        $this->_initialisation();
    }

    public function __wakeup()
    {
        global $metrologyStartTime;
        syslog(LOG_INFO, 'LogT=' . (microtime(true) - $metrologyStartTime) . ' LogL="info" LogI="2d9358d5" LogF="include nebule library" LogM="Reloading nebule library"');

        $this->_initialisation();
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        $this->_saveCurrentsObjectsOnSessionBuffer();
        $this->_saveCacheOnSessionBuffer();
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return self::NEBULE_LICENCE_NAME;
    }

    public function __sleep()
    {
        // TODO
        /*return array(
            // '_flushCache',
            //	'_optionCheckedWriteableIO',
            //	'_referenceObjectConversation',
            //	'_referenceObjectConversationClosed',
            //	'_referenceObjectConversationProtected',
            //	'_referenceObjectConversationObfuscated',
        );*/
    }

    private function _initialisation()
    {
        global $nebuleInstance;

        $this->_nebuleInstance = $this;
        $nebuleInstance = $this;
        $this->_metrology = new Metrology($this->_nebuleInstance);
        $this->_configuration = new Configuration($this->_nebuleInstance);
        $this->_metrology->setConfigurationInstance($this->_configuration);

        $this->_findModeRescue();

        if (!$this->_nebuleCheckEnvironment())
            $this->_nebuleInitEnvironment();

        $this->_io = new io($this->_nebuleInstance);
        $this->_crypto = new CryptoOpenssl($this->_nebuleInstance);
        $this->_social = new Social($this->_nebuleInstance);

        $this->_metrology->addLog('First step init nebule instance', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log

        $this->_configuration->setPermitOptionsByLinks(true);
        $this->_configuration->flushCache();

        $this->_findFlushCache();

        $this->_getSubordinationEntity();
        $this->_sessionBufferLimit = $this->_configuration->getOption('sessionBufferSize');
        $this->_readCacheOnSessionBuffer();

        $this->_findActionTicket();

        $this->_checkWriteableIO();

        $this->_findPuppetmaster();
        $this->_findSecurityMaster();
        $this->_findCodeMaster();
        $this->_findDirectoryMaster();
        $this->_findTimeMaster();
        $this->_findLocalAuthorities();
        $this->_findInstanceEntity();
        $this->_findDefaultEntity();
        $this->_addInstanceEntityAsAuthorities();
        $this->_addDefaultEntityAsAuthorities();
        $this->_findCurrentEntity();
        $this->_addLocalAuthorities();
        $this->_findRecoveryEntities();
        $this->_addInstanceEntityAsRecovery();
        $this->_addDefaultEntityAsRecovery();

        $this->_findCurrentObjet();
        $this->_findCurrentEntityPrivateKey();
        $this->_findCurrentEntityPassword();
        $this->_findCurrentGroup();
        $this->_findCurrentConversation();
        $this->_findCurrentCurrency();
        $this->_findCurrentTokenPool();
        $this->_findCurrentToken();

        $this->_metrology->addLog('End init nebule instance', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }



    /**
     * Entité de subordination des options de l'entité en cours.
     * Par défaut vide.
     *
     * @var node|null
     */
    private $_subordinationEntity = null;

    /**
     * Extrait l'entité de subordination des options si présente.
     *
     * Utilise la fonction getOptionFromEnvironment() pour extraire l'option du fichier d'environnement.
     *
     * @return void
     */
    private function _getSubordinationEntity()
    {
        $this->_subordinationEntity = new Entity($this->_nebuleInstance, (string)Configuration::getOptionFromEnvironmentStatic('subordinationEntity'));

        if ($this->_metrology !== null)
            $this->_metrology->addLog('Get subordination entity = ' . $this->_subordinationEntity, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Retourne l'entité de subordination si défini.
     * Retourne une chaine vide sinon.
     *
     * @return node
     */
    public function getSubordinationEntity(): node
    {
        return $this->_subordinationEntity;
    }

    /**
     * Vérifie que le système d'entrée/sortie par défaut est lecture/écriture.
     * Force les options permitWrite permitWriteObject et permitWriteLink au besoin.
     *
     * @return void
     * @todo ne fonctionne pas correctement mais non bloquant.
     *
     */
    private function _checkWriteableIO(): void
    {
        if ($this->_io->getMode() == 'RW') {
            if (!$this->_io->checkObjectsWrite())
                $this->_configuration->lockWriteObject();;
            if (!$this->_io->checkLinksWrite())
                $this->_configuration->lockWriteLink();;
        } else {
            $this->_configuration->lockWrite();
            $this->_configuration->lockWriteObject();
            $this->_configuration->lockWriteLink();
        }

        if (!$this->_configuration->getOption('permitWriteObject'))
            $this->_metrology->addLog('objects ro not rw', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
        if (!$this->_configuration->getOption('permitWriteLink'))
            $this->_metrology->addLog('links ro not rw', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
    }



    /*
	 * --------------------------------------------------------------------------------
	 * Gestion du stockage des options et du buffer dans la session php.
	 * --------------------------------------------------------------------------------
	 */
    /**
     * Lit la valeur d'une option dans la session php.
     * Si l'option n'est pas renseignée, retourne false.
     *
     * @param string $name
     * @return int|string|bool|null
     */
    public function getSessionStore(string $name)
    {
        session_start();

        if ($name == ''
            || $this->_flushCache
            || !$this->_configuration->getOption('permitSessionOptions')
            || !isset($_SESSION['Option'][$name])
        ) {
            session_write_close();
            return false;
        }
        $val = $_SESSION['Option'][$name];

        session_write_close();

        return $val;
    }

    /**
     * Ecrit la valeur d'une option dans la session php.
     *
     * @param string $name
     * @param int|string|bool|null $content
     * @return boolean
     */
    public function setSessionStore(string $name, $content): bool
    {
        if ($name == ''
            || $this->_flushCache
            || !$this->_configuration->getOption('permitSessionOptions')
        )
            return false;

        session_start();
        $_SESSION['Option'][$name] = $content;
        session_write_close();
        return true;
    }

    /**
     * Vide les options dans la session php.
     *
     * @return boolean
     */
    private function _flushSessionStore(): bool
    {
        $this->_metrology->addLog('Flush session store', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        session_start();
        unset($_SESSION['Option']);
        session_write_close();
        return true;
    }

    /**
     * Lit la valeur d'un contenu mémorisé dans la session php.
     * Si le contenu mémorisé n'est pas renseigné, retourne false.
     *
     * @param string $name
     * @return int|string|bool|null
     */
    private function _getSessionBuffer(string $name)
    {
        session_start();

        if ($name == ''
            || $this->_flushCache
            || !$this->_configuration->getOption('permitSessionBuffer')
            || !isset($_SESSION['Buffer'][$name])
        ) {
            session_write_close();
            return false;
        }

        $val = unserialize($_SESSION['Buffer'][$name]);
        session_write_close();
        return $val;
    }

    /**
     * Ecrit la valeur d'un contenu mémorisé dans la session php.
     * Le nombre de contenus mémorisés n'est pas comptabilisé par cette fonction.
     *
     * @param string $name
     * @param int|string|bool|null $content
     * @return boolean
     */
    private function _setSessionBuffer(string $name, $content): bool
    {
        if ($name == ''
            || $this->_flushCache
            || !$this->_configuration->getOption('permitSessionBuffer')
        )
            return false;

        session_start();
        $_SESSION['Buffer'][$name] = serialize($content);
        session_write_close();
        return true;
    }

    /**
     * Supprime un contenu mémorisé dans la session php.
     *
     * Fonction désactivée !
     *
     * @param string $name
     * @return boolean
     */
    public function unsetSessionBuffer(string $name): bool
    {
/*        if ($name == ''
            || $this->_flushCache
            || !$this->_configuration->getOption('permitSessionBuffer')
        )
            return false;

        session_start();
        if (isset($_SESSION['Buffer'][$name]))
            unset($_SESSION['Buffer'][$name]);
        session_write_close();*/
        return true;
    }

    /**
     * Extrait les instances du buffer de session vers le cache.
     *
     * @return void
     */
    private function _readCacheOnSessionBuffer(): void
    {
        if ($this->_flushCache
            || !$this->_configuration->getOption('permitSessionBuffer')
        )
            return;

        session_start();

        // Extrait les objets/liens du cache.
        $list = array();
        if (isset($_SESSION['Buffer']))
            $list = $_SESSION['Buffer'];
        if (sizeof($list) > 0) {
            foreach ($list as $string) {
                $instance = unserialize($string); // TODO à simplifier par rapport à la class parente
                if (is_a($instance, 'Transaction')) {
                    $id = $instance->getFullLink();
                    $this->_cacheTransactions[$id] = $instance;
                } elseif (is_a($instance, 'Wallet')) {
                    $id = $instance->getID();
                    $this->_cacheWallets[$id] = $instance;
                } elseif (is_a($instance, 'Token')) {
                    $id = $instance->getID();
                    $this->_cacheTokens[$id] = $instance;
                } elseif (is_a($instance, 'TokenPool')) {
                    $id = $instance->getID();
                    $this->_cacheTokenPools[$id] = $instance;
                } elseif (is_a($instance, 'Currency')) {
                    $id = $instance->getID();
                    $this->_cacheCurrencies[$id] = $instance;
                } elseif (is_a($instance, 'Conversation')) {
                    $id = $instance->getID();
                    $this->_cacheConversations[$id] = $instance;
                } elseif (is_a($instance, 'Group')) {
                    $id = $instance->getID();
                    $this->_cacheGroups[$id] = $instance;
                } elseif (is_a($instance, 'Entity')) {
                    $id = $instance->getID();
                    $this->_cacheEntities[$id] = $instance;
                } elseif (is_a($instance, 'Node')) {
                    $id = $instance->getID();
                    $this->_cacheObjects[$id] = $instance;
                } elseif (is_a($instance, 'Link')) {
                    $id = $instance->getFullLink();
                    $this->_cacheLinks[$id] = $instance;
                }
            }

            // Extrait les objets/liens du cache.
            $list = array();
            if (isset($_SESSION['BufferDateInsertion']))
                $list = $_SESSION['BufferDateInsertion'];
            if (sizeof($list) > 0) {
                foreach ($list as $id => $string)
                    $this->_cacheDateInsertion[$id] = $string;
            }
        }

        session_write_close();

        // Vérifie si le cache n'est pas trop gros. Le vide au besoin.
        $this->_getCacheNeedCleaning();
    }

    /**
     * Re-sauvegarde les instances des certains objets avant la sauvegarde du cache vers le buffer de session.
     * Ces objets sont potentiellement modifiés depuis leur première instanciation.
     *
     * @return void
     */
    private function _saveCurrentsObjectsOnSessionBuffer(): void
    {
        $this->setSessionStore('nebuleHostEntityInstance', serialize($this->_instanceEntityInstance));
        $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
    }

    /**
     * Sauvegarde les instances du cache vers le buffer de session.
     *
     * @return void
     */
    private function _saveCacheOnSessionBuffer(): void
    {
        if ($this->_flushCache
            || !$this->_configuration->getOption('permitSessionBuffer')
        )
            return;

        $this->_getCacheNeedCleaning();
        session_start();
        $_SESSION['Buffer'] = array();

        // Mémorise les objets/liens.
        foreach ($this->_cacheLinks as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getFullLink()])) {
                $_SESSION['Buffer'][$instance->getFullLink()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getFullLink()] = $this->_cacheDateInsertion[$instance->getFullLink()];
            }
        }
        foreach ($this->_cacheObjects as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getID()])) {
                $_SESSION['Buffer'][$instance->getID()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getID()] = $this->_cacheDateInsertion[$instance->getID()];
            }
        }
        foreach ($this->_cacheEntities as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getID()])) {
                $_SESSION['Buffer'][$instance->getID()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getID()] = $this->_cacheDateInsertion[$instance->getID()];
            }
        }
        foreach ($this->_cacheGroups as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getID()])) {
                $_SESSION['Buffer'][$instance->getID()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getID()] = $this->_cacheDateInsertion[$instance->getID()];
            }
        }
        foreach ($this->_cacheConversations as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getID()])) {
                $_SESSION['Buffer'][$instance->getID()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getID()] = $this->_cacheDateInsertion[$instance->getID()];
            }
        }
        foreach ($this->_cacheCurrencies as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getID()])) {
                $_SESSION['Buffer'][$instance->getID()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getID()] = $this->_cacheDateInsertion[$instance->getID()];
            }
        }
        foreach ($this->_cacheTokenPools as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getID()])) {
                $_SESSION['Buffer'][$instance->getID()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getID()] = $this->_cacheDateInsertion[$instance->getID()];
            }
        }
        foreach ($this->_cacheTokens as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getID()])) {
                $_SESSION['Buffer'][$instance->getID()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getID()] = $this->_cacheDateInsertion[$instance->getID()];
            }
        }
        foreach ($this->_cacheWallets as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getID()])) {
                $_SESSION['Buffer'][$instance->getID()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getID()] = $this->_cacheDateInsertion[$instance->getID()];
            }
        }
        foreach ($this->_cacheTransactions as $instance) {
            if (isset($this->_cacheDateInsertion[$instance->getFullLink()])) {
                $_SESSION['Buffer'][$instance->getFullLink()] = serialize($instance);
                $_SESSION['BufferDateInsertion'][$instance->getFullLink()] = $this->_cacheDateInsertion[$instance->getFullLink()];
            }
        }

        session_write_close();
    }

    /**
     * Vide le buffer dans la session php.
     *
     * @return boolean
     */
    private function _flushBufferStore(): bool
    {
        $this->_metrology->addLog('Flush buffer store', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        session_start();
        unset($_SESSION['Buffer']);
        $_SESSION['Buffer'] = array();
        session_write_close();
        return true;
    }

    /**
     * Nettoye le cache du nombre d'entrés demandées.
     *
     * @param int $c
     * @return void
     */
    private function _cleanCacheOverflow(int $c = 0): void
    {
        if (!$this->_configuration->getOption('permitSessionBuffer')
            || !is_numeric($c)
            || $c <= 0
        )
            return;

        if ($c > 100)
            $this->_metrology->addLog(__METHOD__ . ' cache need flush ' . $c . '/' . sizeof($this->_cacheDateInsertion), Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log

        // Tri le tableau des temps. Les plus anciens sont au début.
        asort($this->_cacheDateInsertion);
        $i = 1;
        foreach ($this->_cacheDateInsertion as $id => $item) {
            if ($i > $c)
                break;

            // Nettoie le temps.
            unset($this->_cacheDateInsertion[$id]);

            // Nettoie un objet.
            if (isset($this->_cacheObjects[$id])) {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_obj=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheObjects[$id]);
            }

            // Nettoie un lien.
            if (isset($this->_cacheLinks[$id])) // @todo bugg de suppression des liens !?
            {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_lnk=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheLinks[$id]);
            }

            // Nettoie une entité.
            if (isset($this->_cacheEntities[$id])) {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_ent=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheEntities[$id]);
            }

            // Nettoie un groupe.
            if (isset($this->_cacheGroups[$id])) {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_grp=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheGroups[$id]);
            }

            // Nettoie une conversation.
            if (isset($this->_cacheConversations[$id])) {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_cvt=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheConversations[$id]);
            }

            // Nettoie une monnaie.
            if (isset($this->_cacheCurrencies[$id])) {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_cur=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheCurrencies[$id]);
            }

            // Nettoie un sac de jetons.
            if (isset($this->_cacheTokenPools[$id])) {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_tkp=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheTokenPools[$id]);
            }

            // Nettoie un jeton.
            if (isset($this->_cacheTokens[$id])) {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_tkn=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheTokens[$id]);
            }

            // Nettoie un portefeuille.
            if (isset($this->_cacheWallets[$id])) {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_wlt=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheWallets[$id]);
            }

            // Nettoie une transaction.
            if (isset($this->_cacheTransactions[$id])) {
                $this->_metrology->addLog(__METHOD__ . ' cache_supp_trt=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                unset($this->_cacheTransactions[$id]);
            }

            $i++;
        }
        unset($list);
    }

    /**
     * Retourne la taille totale de tous les caches.
     * Cette taille ne doit pas exéder la taille définie dans l'option sessionBufferSize.
     *
     * @return int
     */
    private function _getAllCachesSize(): int
    {
        return sizeof($this->_cacheObjects) + sizeof($this->_cacheLinks) + sizeof($this->_cacheEntities) + sizeof($this->_cacheGroups) + sizeof($this->_cacheConversations) + sizeof($this->_cacheCurrencies) + sizeof($this->_cacheTokenPools) + sizeof($this->_cacheTokens) + sizeof($this->_cacheWallets) + sizeof($this->_cacheTransactions);
    }

    /**
     * Vérifie si il faut libérer une place pour l'ajout en cache d'un nouvel objet/lien.
     * C'est à dire que la taille maximum du cache est atteinte.
     * Libère au moins une place si besoin.
     *
     * @return void
     */
    private function _getCacheNeedOnePlace(): void
    {
        if (!$this->_configuration->getOption('permitSessionBuffer'))
            return;

        $size = $this->_getAllCachesSize();
        $limit = $this->_sessionBufferLimit;
        if ($size >= $limit)
            $this->_cleanCacheOverflow($size - $limit + 1);
    }

    /**
     * Vérifie si il faut libérer de la place en cache.
     * C'est à dire que la taille maximum du cache est atteinte.
     * Libère de la place si besoin.
     *
     * @return void
     */
    private function _getCacheNeedCleaning(): void
    {
        if (!$this->_configuration->getOption('permitSessionBuffer'))
            return;

        $size = $this->_getAllCachesSize();
        $limit = $this->_sessionBufferLimit;
$this->_metrology->addLog('MARK ' . $size . '-' . $limit, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        if ($size >= $limit)
            $this->_cleanCacheOverflow($size - $limit);
    }


    /**
     * Nouvelle instance d'un objet.
     *
     * @param string  $id
     * @param boolean $protect
     * @param boolean $obfuscated
     * @return Node
     */
    public function newObject(string $id, bool $protect = false, bool $obfuscated = false): Node
    {
        if ($id == '')
            $id = '0';

        if (!$this->_flushCache
            && isset($this->_cacheObjects[$id])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$id] = microtime(true);
            return $this->_cacheObjects[$id];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new Node($this, $id, '', $protect, $obfuscated);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheObjects[$id] = $instance;
                $this->_cacheDateInsertion[$id] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'un objet.
     *
     * @param string $id
     * @return boolean
     */
    public function removeCacheObject(string $id): bool
    {
        if (isset($this->_cacheObjects[$id]))
            unset($this->_cacheObjects[$id], $this->_cacheDateInsertion[$id]);
        return true;
    }

    /**
     * Retourne le nombre d'objets dans le cache.
     *
     * @return integer
     */
    public function getCacheObjectSize(): int
    {
        return sizeof($this->_cacheObjects);
    }


    /**
     * Nouvelle instance d'un lien.
     *
     * @param string $link
     * @return Link
     */
    public function newLink(string $link): Link
    {
        if ($link == '')
            $link = 'invalid';

        if (!$this->_flushCache
            && isset($this->_cacheLinks[$link])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$link] = microtime(true);
            return $this->_cacheLinks[$link];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new Link($this, $link);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheLinks[$link] = $instance;
                $this->_cacheDateInsertion[$link] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'un lien.
     *
     * @param string $link
     * @return boolean
     */
    public function removeCacheLink(string $link): bool
    {
        if (isset($this->_cacheLinks[$link]))
            unset($this->_cacheLinks[$link], $this->_cacheDateInsertion[$link]);
        return true;
    }

    /**
     * Retourne le nombre de liens dans le cache.
     *
     * @return integer
     */
    public function getCacheLinkSize(): int
    {
        return sizeof($this->_cacheLinks);
    }


    /**
     * Nouvelle instance d'une entité.
     *
     * @param string $id
     * @return Entity
     */
    public function newEntity(string $id): Entity
    {
        if (!is_string($id)
            || $id == ''
        )
            $id = '0';

        if (!$this->_flushCache
            && isset($this->_cacheEntities[$id])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$id] = microtime(true);
            return $this->_cacheEntities[$id];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new Entity($this, $id);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheEntities[$id] = $instance;
                $this->_cacheDateInsertion[$id] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'une entité.
     *
     * @param string $id
     * @return boolean
     */
    public function removeCacheEntity(string $id): bool
    {
        if (isset($this->_cacheEntities[$id]))
            unset($this->_cacheEntities[$id], $this->_cacheDateInsertion[$id]);
        return true;
    }

    /**
     * Retourne le nombre d'entités dans le cache.
     *
     * @return integer
     */
    public function getCacheEntitySize(): int
    {
        return sizeof($this->_cacheEntities);
    }


    /**
     * Nouvelle instance d'un groupe.
     *
     * @param string $id
     * @return Group
     */
    public function newGroup(string $id): Group
    {
        if ($id == '')
            $id = '0';

        if (!$this->_flushCache
            && isset($this->_cacheGroups[$id])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$id] = microtime(true);
            return $this->_cacheGroups[$id];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new Group($this, $id);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheGroups[$id] = $instance;
                $this->_cacheDateInsertion[$id] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'un groupe.
     *
     * @param string $id
     * @return boolean
     */
    public function removeCacheGroup(string $id): bool
    {
        if (isset($this->_cacheGroups[$id]))
            unset($this->_cacheGroups[$id], $this->_cacheDateInsertion[$id]);
        return true;
    }

    /**
     * Retourne le nombre de groupes dans le cache.
     *
     * @return integer
     */
    public function getCacheGroupSize(): int
    {
        return sizeof($this->_cacheGroups);
    }


    /**
     * Nouvelle instance d'une conversation.
     *
     * @param string  $id
     * @param boolean $closed
     * @param boolean $protect
     * @param boolean $obfuscated
     * @return Conversation
     */
    public function newConversation(string $id, bool $closed = false, bool $protected = false, bool $obfuscated = false): Conversation
    {
        if ($id == '')
            $id = '0';

        if (!$this->_flushCache
            && isset($this->_cacheConversations[$id])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$id] = microtime(true);
            return $this->_cacheConversations[$id];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new Conversation($this, $id, $closed, $protected, $obfuscated);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheConversations[$id] = $instance;
                $this->_cacheDateInsertion[$id] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'une conversation.
     *
     * @param string $id
     * @return boolean
     */
    public function removeCacheConversation(string $id): bool
    {
        if (isset($this->_cacheConversations[$id]))
            unset($this->_cacheConversations[$id], $this->_cacheDateInsertion[$id]);
        return true;
    }

    /**
     * Retourne le nombre de conversations dans le cache.
     *
     * @return integer
     */
    public function getCacheConversationSize(): int
    {
        return sizeof($this->_cacheConversations);
    }


    /**
     * Nouvelle instance d'une monnaie.
     *
     * @param string  $id
     * @param array   $param
     * @param boolean $protect
     * @param boolean $obfuscated
     * @return Currency
     */
    public function newCurrency(string $id, array $param = array(), bool $protected = false, bool $obfuscated = false): Currency
    {
        if ($id == '')
            $id = '0';

        if (!$this->_flushCache
            && isset($this->_cacheCurrencies[$id])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$id] = microtime(true);
            return $this->_cacheCurrencies[$id];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new Currency($this, $id, $param, $protected, $obfuscated);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheCurrencies[$id] = $instance;
                $this->_cacheDateInsertion[$id] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'une monnaie.
     *
     * @param string $id
     * @return boolean
     */
    public function removeCacheCurrency(string $id): bool
    {
        if (isset($this->_cacheCurrencies[$id])) {
            unset($this->_cacheCurrencies[$id], $this->_cacheDateInsertion[$id]);
        }
        return true;
    }

    /**
     * Retourne le nombre de monnaies dans le cache.
     *
     * @return integer
     */
    public function getCacheCurrencySize(): int
    {
        return sizeof($this->_cacheCurrencies);
    }


    /**
     * Nouvelle instance d'un jeton.
     *
     * @param string  $id
     * @param array   $param
     * @param boolean $protect
     * @param boolean $obfuscated
     * @return Token
     */
    public function newToken(string $id, array $param = array(), bool $protected = false, bool $obfuscated = false): Token
    {
        if ($id == '')
            $id = '0';

        if (!$this->_flushCache
            && isset($this->_cacheTokens[$id])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$id] = microtime(true);
            return $this->_cacheTokens[$id];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new Token($this, $id, $param, $protected, $obfuscated);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheTokens[$id] = $instance;
                $this->_cacheDateInsertion[$id] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'un jeton.
     *
     * @param string $id
     * @return boolean
     */
    public function removeCacheToken(string $id): bool
    {
        if (isset($this->_cacheTokens[$id]))
            unset($this->_cacheTokens[$id], $this->_cacheDateInsertion[$id]);
        return true;
    }

    /**
     * Retourne le nombre de jetons dans le cache.
     *
     * @return integer
     */
    public function getCacheTokenSize(): int
    {
        return sizeof($this->_cacheTokens);
    }


    /**
     * Nouvelle instance d'un sac de jetons.
     *
     * @param string  $id
     * @param array   $param
     * @param boolean $protect
     * @param boolean $obfuscated
     * @return TokenPool
     */
    public function newTokenPool(string $id, array $param = array(), bool $protected = false, bool $obfuscated = false): TokenPool
    {
        if ($id == '')
            $id = '0';

        if (!$this->_flushCache
            && isset($this->_cacheTokenPools[$id])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$id] = microtime(true);
            return $this->_cacheTokenPools[$id];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new TokenPool($this, $id, $param, $protected, $obfuscated);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheTokenPools[$id] = $instance;
                $this->_cacheDateInsertion[$id] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'un sac de jetons.
     *
     * @param string $id
     * @return boolean
     */
    public function removeCacheTokenPool(string $id): bool
    {
        if (isset($this->_cacheTokenPools[$id]))
            unset($this->_cacheTokenPools[$id], $this->_cacheDateInsertion[$id]);
        return true;
    }

    /**
     * Retourne le nombre de sacs de jetons dans le cache.
     *
     * @return integer
     */
    public function getCacheTokenPoolSize(): int
    {
        return sizeof($this->_cacheTokenPools);
    }


    /**
     * Nouvelle instance d'un portefeuille.
     *
     * @param string  $id
     * @param array   $param
     * @param boolean $protect
     * @param boolean $obfuscated
     * @return Wallet
     */
    public function newWallet(string $id, array $param = array(), bool $protected = false, bool $obfuscated = false): Wallet
    {
        if ($id == '')
            $id = '0';

        if (!$this->_flushCache
            && isset($this->_cacheWallets[$id])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$id] = microtime(true);
            return $this->_cacheWallets[$id];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new Wallet($this, $id, $param, $protected, $obfuscated);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheWallets[$id] = $instance;
                $this->_cacheDateInsertion[$id] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'un portefeuille.
     *
     * @param string $id
     * @return boolean
     */
    public function removeCacheWallet(string $id): bool
    {
        if (isset($this->_cacheWallets[$id]))
            unset($this->_cacheWallets[$id], $this->_cacheDateInsertion[$id]);
        return true;
    }

    /**
     * Retourne le nombre de portefeuilles dans le cache.
     *
     * @return integer
     */
    public function getCacheWalletSize(): int
    {
        return sizeof($this->_cacheWallets);
    }


    /**
     * Nouvelle instance d'une transaction.
     * Attention, c'est un lien !
     *
     * @param string $link
     * @return Transaction
     */
    public function newTransaction(string $link): Transaction
    {
        if ($link == '')
            $link = 'invalid';

        if (!$this->_flushCache
            && isset($this->_cacheTransactions[$link])
        ) {
            // Surchage la date d'ajout.
            $this->_cacheDateInsertion[$link] = microtime(true);
            return $this->_cacheTransactions[$link];
        } else {
            // Regarde si la limite de taille du cache est atteinte.
            $this->_getCacheNeedOnePlace();

            // Génère une instance.
            $instance = new Transaction($this, $link);

            // Si le cache est activé.
            if ($this->_configuration->getOption('permitSessionBuffer')) {
                // Ajoute l'instance au cache.
                $this->_cacheTransactions[$link] = $instance;
                $this->_cacheDateInsertion[$link] = microtime(true);
            }

            return $instance;
        }
    }

    /**
     * Supprime le cache d'une transaction.
     *
     * @param string $link
     * @return boolean
     */
    public function removeCacheTransaction(string $link): bool
    {
        if (isset($this->_cacheTransactions[$link]))
            unset($this->_cacheTransactions[$link], $this->_cacheDateInsertion[$link]);
        return true;
    }

    /**
     * Retourne le nombre de transactions dans le cache.
     *
     * @return integer
     */
    public function getCacheTransactionSize(): int
    {
        return sizeof($this->_cacheTransactions);
    }



    /**
     * Retire le cache d'un objet.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture de l'objet.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetObjectCache($id)
    {
        if (is_a($id, 'Node')) {
            $id = $id->getID();
        }

        if ($id == ''
            || $id == '0'
        ) {
            return false;
        }

        if (!$this->_flushCache
            && isset($this->_cacheObjects[$id])
        ) {
            unset($this->_cacheObjects[$id], $this->_cacheDateInsertion[$id]);
        }
        return true;
    }

    /**
     * Retire le cache d'une entité.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture de l'entité.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetEntityCache($id)
    {
        if (is_a($id, 'Node')) {
            $id = $id->getID();
        }

        if ($id == ''
            || $id == '0'
        ) {
            return false;
        }

        if (!$this->_flushCache
            && isset($this->_cacheEntities[$id])
        ) {
            unset($this->_cacheEntities[$id], $this->_cacheDateInsertion[$id]);
        }
        return true;
    }

    /**
     * Retire le cache d'un groupe.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture du groupe.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetGroupCache($id)
    {
        if (is_a($id, 'Node')) {
            $id = $id->getID();
        }

        if ($id == ''
            || $id == '0'
        ) {
            return false;
        }

        if (!$this->_flushCache
            && isset($this->_cacheGroups[$id])
        ) {
            unset($this->_cacheGroups[$id], $this->_cacheDateInsertion[$id]);
        }
        return true;
    }

    /**
     * Retire le cache d'une conversation.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture de la conversation.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetConversationCache($id)
    {
        if (is_a($id, 'Node')) {
            $id = $id->getID();
        }

        if ($id == ''
            || $id == '0'
        ) {
            return false;
        }

        if (!$this->_flushCache
            && isset($this->_cacheConversations[$id])
        ) {
            unset($this->_cacheConversations[$id], $this->_cacheDateInsertion[$id]);
        }
        return true;
    }

    /**
     * Retire le cache d'une monnaie.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture de la monnaie.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetCurrencyCache($id)
    {
        if (is_a($id, 'Node')) {
            $id = $id->getID();
        }

        if ($id == ''
            || $id == '0'
        ) {
            return false;
        }

        if (!$this->_flushCache
            && isset($this->_cacheCurrencies[$id])
        ) {
            unset($this->_cacheCurrencies[$id], $this->_cacheDateInsertion[$id]);
        }
        return true;
    }

    /**
     * Retire le cache d'un sac de jetons.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture du sac de jetons.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetTokenPoolCache($id)
    {
        if (is_a($id, 'Node')) {
            $id = $id->getID();
        }

        if ($id == ''
            || $id == '0'
        ) {
            return false;
        }

        if (!$this->_flushCache
            && isset($this->_cacheTokenPools[$id])
        ) {
            unset($this->_cacheTokenPools[$id], $this->_cacheDateInsertion[$id]);
        }
        return true;
    }

    /**
     * Retire le cache d'un jeton.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture du jeton.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetTokenCache($id)
    {
        if (is_a($id, 'Node')) {
            $id = $id->getID();
        }

        if ($id == ''
            || $id == '0'
        ) {
            return false;
        }

        if (!$this->_flushCache
            && isset($this->_cacheTokens[$id])
        ) {
            unset($this->_cacheTokens[$id], $this->_cacheDateInsertion[$id]);
        }
        return true;
    }

    /**
     * Retire le cache d'un poretfeuille.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture du poretfeuille.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetWalletCache($id)
    {
        if (is_a($id, 'Node')) {
            $id = $id->getID();
        }

        if ($id == ''
            || $id == '0'
        ) {
            return false;
        }

        if (!$this->_flushCache
            && isset($this->_cacheWallets[$id])
        ) {
            unset($this->_cacheWallets[$id], $this->_cacheDateInsertion[$id]);
        }
        return true;
    }

    /**
     * Retire le cache d'une transaction.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture de la transaction.
     *
     * @param $link string
     * @return boolean
     */
    public function unsetTransactionCache($link)
    {
        if (is_a($link, 'Link')) {
            $link = $link->getID();
        }

        if ($link == ''
            || $link == '0'
        ) {
            return false;
        }

        if (!$this->_flushCache
            && isset($this->_cacheTransactions[$link])
        ) {
            unset($this->_cacheTransactions[$link], $this->_cacheDateInsertion[$link]);
        }
        return true;
    }



    // Gestion des modules.

    /**
     * Liste les modules.
     * @param string $name
     * @return boolean
     * @todo
     *
     */
    public function listModule($name)
    {
        if ($name == '') {
            return false;
        }

        // ...

        return true;
    }

    /**
     * Charge un module.
     * @param string $name
     * @return boolean|Modules
     * @todo
     *
     */
    public function loadModule($name)
    {
        if ($name == '') {
            return false;
        }

        // ...

        $module = new $name;

        return $module;
    }



    // Gestion de l'objet en cours.

    /**
     * ID de l'objet en cours.
     *
     * @var string
     */
    private $_currentObject = '';

    /**
     * Instance de l'objet en cours.
     *
     * @var Node|null
     */
    private $_currentObjectInstance = null;

    /**
     * Recherche l'objet en cours d'utilisation.
     *
     * @return void
     */
    private function _findCurrentObjet()
    {
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
 		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        $arg_obj = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        // Si la variable est un objet avec ou sans liens.
        if ($arg_obj != ''
            && strlen($arg_obj) >= self::NEBULE_MINIMUM_ID_SIZE
            && ctype_xdigit($arg_obj)
            && ($this->getIO()->checkObjectPresent($arg_obj)
                || $this->getIO()->checkLinkPresent($arg_obj)
            )
        ) {
            // Ecrit l'objet dans la variable.
            $this->_currentObject = $arg_obj;
            $this->_currentObjectInstance = $this->newObject($arg_obj);
            // Ecrit l'objet dans la session.
            $this->setSessionStore('nebuleSelectedObject', $arg_obj);
        } else {
            // Sinon vérifie si une valeur n'est pas mémorisée dans la session.
            $cache = $this->getSessionStore('nebuleSelectedObject');
            // Si il existe une variable de session pour l'objet en cours, la lit.
            if ($cache !== false && $cache != '') {
                $this->_currentObject = $cache;
                $this->_currentObjectInstance = $this->newObject($cache);
            } else // Sinon selectionne l'entite courante par défaut.
            {
                $this->_currentObject = $this->getCurrentEntity();
                $this->_currentObjectInstance = $this->newObject($this->getCurrentEntity());
                $this->setSessionStore('nebuleSelectedObject', $this->getCurrentEntity());
            }
            unset($cache);
        }
        unset($arg_obj);

        $this->_metrology->addLog('Find current object ' . $this->_currentObject, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        $this->_currentObjectInstance->getMarkProtected();
    }

    /**
     * Donne l'ID de l'objet en cours.
     *
     * @return string
     */
    public function getCurrentObject()
    {
        return $this->_currentObject;
    }

    /**
     * Donne l'instance de l'objet en cours.
     *
     * @return Node|null
     */
    public function getCurrentObjectInstance()
    {
        return $this->_currentObjectInstance;
    }



    /*
	 * Gestion de l'entité de l'instance du serveur et donc de l'application.
	 */
    /**
     * Clé publique de l'entité hôte de l'instance du serveur.
     *
     * @var string
     */
    private $_instanceEntity = '';

    /**
     * Instance de l'entité hôte de l'instance du serveur.
     *
     * @var Entity|null
     */
    private $_instanceEntityInstance = null;

    /**
     * Recherche l'entité hôte sur le serveur.
     *
     * @return void
     */
    private function _findInstanceEntity()
    {
        $instance = null;
        // Vérifie si une valeur n'est pas mémorisée dans la session.
        $id = $this->getSessionStore('nebuleHostEntity');

        // Si il existe une variable de session pour l'hôte en cours, la lit.
        if ($id !== false
            && $id != ''
        ) {
            $instance = unserialize($this->getSessionStore('nebuleHostEntityInstance'));
        }

        if ($id !== false
            && $id != ''
            && $instance !== false
            && $instance !== ''
            && is_a($instance, 'Entity')
        ) {
            $this->_instanceEntity = $id;
            $this->_instanceEntityInstance = $instance;
        } else {
            // Sinon recherche une entité pour l'instance.
            // C'est le fichier 'e' qui contient normalement l'ID de cette entité.
            if (file_exists(self::NEBULE_LOCAL_ENTITY_FILE)
                && is_file(self::NEBULE_LOCAL_ENTITY_FILE)
            ) {
                $id = filter_var(trim(strtok(file_get_contents(self::NEBULE_LOCAL_ENTITY_FILE), "\n")), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
            }

            if ($id != ''
                && strlen($id) >= self::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($id)
                && $this->_io->checkObjectPresent($id)
                && $this->_io->checkLinkPresent($id)
            ) {
                $this->_instanceEntity = $id;
                $this->_instanceEntityInstance = $this->newEntity($id);
            } else {
                // Sinon utilise l'instance du maître du code.
                $this->_instanceEntity = $this->_codeMaster;
                $this->_instanceEntityInstance = $this->_codeMasterInstance;
            }

            // Log
            $this->_metrology->addLog('Find server entity ' . $this->_instanceEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

            // Mémorisation.
            $this->setSessionStore('nebuleHostEntity', $this->_instanceEntity);
            $this->setSessionStore('nebuleHostEntityInstance', serialize($this->_instanceEntityInstance));
        }
        unset($id, $instance);
    }

    /**
     * Donne l'ID de l'entité de l'instance de serveur.
     *
     * @return string
     */
    public function getInstanceEntity()
    {
        return $this->_instanceEntity;
    }

    /**
     * Donne l'instance de l'entité de l'instance de serveur.
     *
     * @return Entity|NULL
     */
    public function getInstanceEntityInstance()
    {
        return $this->_instanceEntityInstance;
    }



    /*
	 * Gestion de l'entité par défaut.
	 */
    /**
     * Clé publique de l'entité par défaut.
     *
     * @var string $_defaultEntity
     */
    private $_defaultEntity = '';

    /**
     * Instance de l'entité par défaut.
     *
     * @var Entity|null $_defaultEntityInstance
     */
    private $_defaultEntityInstance = null;

    /**
     * Recherche l'entité par défaut.
     *
     * @return void
     */
    private function _findDefaultEntity()
    {
        $instance = null;
        // Vérifie si une valeur n'est pas mémorisée dans la session.
        $id = $this->getSessionStore('nebuleDefaultEntity');

        // Si il existe une variable de session pour l'hôte en cours, la lit.
        if ($id !== false
            && $id != ''
        ) {
            $instance = unserialize($this->getSessionStore('nebuleDefaultEntityInstance'));
        }

        if ($id !== false
            && $id != ''
            && $instance !== false
            && $instance !== ''
            && is_a($instance, 'Entity')
        ) {
            $this->_defaultEntity = $id;
            $this->_defaultEntityInstance = $instance;
        } else {
            // Sinon recherche une entité par défaut.
            // C'est définit comme une option.
            $id = $this->_configuration->getOption('defaultCurrentEntity');

            if ($id != ''
                && strlen($id) >= self::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($id)
                && $this->_io->checkObjectPresent($id)
                && $this->_io->checkLinkPresent($id)
            ) {
                $this->_defaultEntity = $id;
                $this->_defaultEntityInstance = $this->newEntity($id);
            } else {
                // Sinon utilise l'instance du serveur hôte.
                $this->_defaultEntity = $this->_instanceEntity;
                $this->_defaultEntityInstance = $this->_instanceEntityInstance;
            }

            // Log
            $this->_metrology->addLog('Find default entity ' . $this->_defaultEntity, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');

            // Mémorisation.
            $this->setSessionStore('nebuleDefaultEntity', $this->_defaultEntity);
            $this->setSessionStore('nebuleDefaultEntityInstance', serialize($this->_defaultEntityInstance));
        }
        unset($id, $instance);
    }

    /**
     * Donne l'ID de l'entité par défaut.
     *
     * @return string
     */
    public function getDefaultEntity()
    {
        return $this->_defaultEntity;
    }

    /**
     * Donne l'instance de l'entité par défaut.
     *
     * @return Entity|NULL
     */
    public function getDefaultEntityInstance()
    {
        return $this->_defaultEntityInstance;
    }



    // Gestion de l'entité en cours d'utilisation par l'application.

    /**
     * ID de la clé publique de l'entité.
     *
     * @var string
     */
    private $_currentEntity = '';

    /**
     * Instance de la clé publique de l'entité.
     *
     * @var Entity|null
     */
    private $_currentEntityInstance = null;

    /**
     * ID de la clé privée de l'entité.
     *
     * @var string
     */
    private $_currentEntityPrivateKey = '';

    /**
     * Instance de la clé privée de l'entité.
     *
     * @var Node|null
     */
    private $_currentEntityPrivateKeyInstance = null;

    /**
     * Etat de verrouillage de l'entité courante.
     *
     * @var boolean
     */
    private $_currentEntityUnlocked = false;

    private function _findCurrentEntity()
    {
        $itc_ent = null;

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        // Regarde si demande de changement d'entité, si la variable GET ou POST existe (true/false).
        $arg_switch = (filter_has_var(INPUT_GET, self::COMMAND_SWITCH_TO_ENTITY)
            || filter_has_var(INPUT_POST, self::COMMAND_SWITCH_TO_ENTITY));
        // Lit et nettoye le contenu de la variable GET de la nouvelle entité attendue.
        $arg_ent = filter_input(INPUT_GET, self::COMMAND_SELECT_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

        // Si la variable est une entité avec ou sans liens.
        if ($arg_switch
            && $arg_ent != ''
            && strlen($arg_ent) >= self::NEBULE_MINIMUM_ID_SIZE
            && ctype_xdigit($arg_ent)
            && $this->_io->checkObjectPresent($arg_ent)
            && $this->_io->checkLinkPresent($arg_ent)
        ) {
            $itc_ent = $this->newObject($arg_ent);
        }

        if ($arg_switch
            && is_a($itc_ent, 'Node')
            && $itc_ent->getType('all') == Entity::ENTITY_TYPE
        ) {
            unset($itc_ent);
            // Vide le mot de passe de l'entité en cours.
            if (is_a($this->_currentEntityInstance, 'Entity')) {
                $this->_currentEntityInstance->unsetPrivateKeyPassword();
                $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
            }
            // Ecrit l'entité dans la session et dans la variable globale.
            $this->_currentEntity = $arg_ent;
            $this->_currentEntityInstance = new Entity($this->_nebuleInstance, $arg_ent);
            $this->setSessionStore('nebulePublicEntity', $this->_currentEntity);
            $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
            // Vide la clé privée connue.
            $this->_currentEntityPrivateKey = '';
            $this->_currentEntityPrivateKeyInstance = '';
            $this->setSessionStore('nebulePrivateEntity', '');
            $this->_metrology->addLog('New current entity ' . $this->_currentEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        } else {
            $tID = $this->getSessionStore('nebulePublicEntity');
            $sInstance = $this->getSessionStore('nebulePublicEntityInstance');
            if ($sInstance !== false)
                $tInstance = unserialize($sInstance);
            // Si il existe une variable de session pour l'entité, la lit.
            if ($tID !== false
                && $tID != ''
                && $tInstance !== false
                && is_a($tInstance, 'Entity')
            ) {
                $this->_currentEntity = $tID;
                $this->_currentEntityInstance = $tInstance;
                $this->_metrology->addLog('Reuse current entity ' . $this->_currentEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
            } else // Sinon essaie de la trouver ailleurs.
            {
                $itc_ent = '';
                $ext_ent = $this->_configuration->getOption('defaultCurrentEntity');
                if ($ext_ent != ''
                    && strlen($ext_ent) >= self::NEBULE_MINIMUM_ID_SIZE
                    && ctype_xdigit($ext_ent)
                    && $this->_io->checkObjectPresent($ext_ent)
                    && $this->_io->checkLinkPresent($ext_ent)) {
                    $itc_ent = $this->newObject($ext_ent);
                }
                if (is_a($itc_ent, 'Node') && $itc_ent->getType('all') == Entity::ENTITY_TYPE) {
                    unset($itc_ent);
                    // Ecrit l'entité dans la session et dans la variable globale.
                    $this->_currentEntity = $ext_ent;
                    $this->_currentEntityInstance = new Entity($this->_nebuleInstance, $ext_ent);
                    $this->setSessionStore('nebulePublicEntity', $this->_currentEntity);
                    $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
                    // Vide la clé privée connue.
                    $this->_currentEntityPrivateKey = '';
                    $this->_currentEntityPrivateKeyInstance = '';
                    $this->setSessionStore('nebulePrivateEntity', '');
                    $this->_metrology->addLog('Find default current entity ' . $this->_currentEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                } // Sinon utilise l'entité de l'instance.
                else {
                    $this->_currentEntity = $this->_instanceEntity;
                    $this->_currentEntityInstance = $this->_instanceEntityInstance;
                    $this->setSessionStore('nebulePublicEntity', $this->_currentEntity);
                    $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
                    // Vide la clé privée connue.
                    $this->_currentEntityPrivateKey = '';
                    $this->_currentEntityPrivateKeyInstance = '';
                    $this->setSessionStore('nebulePrivateEntity', '');
                    $this->_metrology->addLog('Find current (instance) entity ' . $this->_currentEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                }
                unset($ext_ent);
            }
            unset($tID, $tInstance);
        }
        unset($arg_switch, $arg_ent);
    }

    private function _findCurrentEntityPrivateKey()
    {
        // Si il existe une variable de session pour l'entite, la lit
        if ($this->getSessionStore('nebulePrivateEntity') !== false
            && $this->getSessionStore('nebulePrivateEntity') != ''
        ) {
            $this->_currentEntityPrivateKey = $this->getSessionStore('nebulePrivateEntity');
            $this->_currentEntityPrivateKeyInstance = $this->newObject($this->_currentEntityPrivateKey);
            // Log
            $this->_metrology->addLog('Reuse current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        } // Sinon essaie de la trouver ailleurs
        else {
            if (is_a($this->_currentEntityInstance, 'Entity')) {
                $this->_currentEntityPrivateKey = $this->_currentEntityInstance->getPrivateKeyID();
                if ($this->_currentEntityPrivateKey != '') {
                    $this->_currentEntityPrivateKeyInstance = $this->newObject($this->_currentEntityPrivateKey);
                    // Log
                    $this->_metrology->addLog('Find current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                } else {
                    $this->_currentEntityPrivateKeyInstance = '';
                    // Log
                    $this->_metrology->addLog('Cant find current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                }
                $this->setSessionStore('nebulePrivateEntity', $this->_currentEntityPrivateKey);
            } else {
                $this->_currentEntityPrivateKey = '';
                $this->_currentEntityPrivateKeyInstance = '';
            }
        }
    }

    private function _findCurrentEntityPassword()
    {
        $arg_pwd = '';

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        // Regarde si demande de changement fermeture d'entité, si la variable GET ou POST existe (true/false).
        $arg_logout = (filter_has_var(INPUT_GET, self::COMMAND_LOGOUT_ENTITY)
            || filter_has_var(INPUT_POST, self::COMMAND_LOGOUT_ENTITY));
        // Lit et nettoye le contenu des variables GET et POST pour le mot de passe.
        $arg_get_pwd = filter_input(INPUT_GET, self::COMMAND_SELECT_PASSWORD, FILTER_SANITIZE_STRING);
        $arg_post_pwd = filter_input(INPUT_POST, self::COMMAND_SELECT_PASSWORD, FILTER_SANITIZE_STRING);

        // Extrait un des mots de passes.
        if ($arg_get_pwd != '' && $arg_post_pwd == '') {
            $arg_pwd = $arg_get_pwd;
        }

        if ($arg_post_pwd != '') {
            $arg_pwd = $arg_post_pwd;
        }

        // Supprime le mdp et ferme la session si l'argment logout est présent.
        // Si c'est une demande de fermeture d'entité.
        if ($arg_logout) {
            // Supprime le mot de passe de l'entité en cours.
            if (is_a($this->_currentEntityInstance, 'Entity')) {
                $this->_currentEntityInstance->unsetPrivateKeyPassword();
                $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
                $this->_metrology->addLog('Logout ' . $this->_currentEntity, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
            }
        } else {
            // Ajoute le mot de passe à l'entité en cours.
            $this->_currentEntityInstance->setPrivateKeyPassword($arg_pwd);
            $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
            // Test si le mot de passe est bon.
            $this->_currentEntityUnlocked = $this->_currentEntityInstance->checkPrivateKeyPassword();
            if ($this->_currentEntityUnlocked) {
                $this->_metrology->addLog('Login password ' . $this->_currentEntity . ' OK', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
            } else {
                $this->_metrology->addLog('Login password ' . $this->_currentEntity . ' NOK', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000'); // Log
            }
        }
        unset($arg_logout, $arg_pwd, $arg_get_pwd, $arg_post_pwd);
    }

    /**
     * Lit l'ID de l'entité en cours.
     *
     * @return string
     */
    public function getCurrentEntity()
    {
        return $this->_currentEntity;
    }

    /**
     * Lit l'instance de l'entité en cours.
     *
     * @return Entity
     */
    public function getCurrentEntityInstance()
    {
        return $this->_currentEntityInstance;
    }

    /**
     * Lit l'ID de la clé privée de l'entité en cours.
     *
     * @return string
     */
    public function getCurrentEntityPrivateKey()
    {
        return $this->_currentEntityPrivateKey;
    }

    /**
     * Lit l'instance de la clé privée de l'entité en cours.
     *
     * @return Node
     */
    public function getCurrentEntityPrivateKeyInstance()
    {
        return $this->_currentEntityPrivateKeyInstance;
    }

    /**
     * Lit le status de verrouillage de l'entité en cours.
     * - true : entité déverrouillée.
     * - false : entité verrouillée.
     *
     * @return boolean
     */
    public function getCurrentEntityUnlocked()
    {
        return $this->_currentEntityUnlocked;
    }

    /**
     * Change l'entité courante. Le changement est permanent et les caches sont effacés.
     * Penser à lui pousser son mot de passe pour qu'elle soit déverrouillée.
     *
     * @param Entity $entity
     * @return boolean
     */
    public function setCurrentEntity(Entity $entity)
    {
        if (!$entity instanceof Entity) return false;
        // Reouvre une nouvelle session pour la suite.
        session_start();

        //$this->_flushSessionStore();
        $this->_flushBufferStore();

        // Change l'entité en cours.
        $this->_currentEntityInstance = $entity;
        $this->_currentEntity = $this->_currentEntityInstance->getID();
        $this->setSessionStore('nebulePublicEntity', $this->_currentEntity);
        $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
        // Cherche la clé privée.
        $this->_findCurrentEntityPrivateKey();
        // Test si le mot de passe est bon.
        $this->_currentEntityUnlocked = $this->_currentEntityInstance->checkPrivateKeyPassword();

        session_write_close();
        return true;
    }

    /**
     * Change l'entité courante de façon temporaire. Les caches sont effacés.
     * Penser à lui pousser son mot de passe pour qu'elle soit déverrouillée.
     *
     * L'ancienne entité est mémorisée pour être restaurée en fin de période temporaire.
     *
     * La fonction est utilisée par la génération d'une nouvelle entité
     *   afin de faire signer les liens par la nouvelle entité.
     *
     * @param Entity $entity
     * @return boolean
     */
    public function setTempCurrentEntity(Entity $entity)
    {
        if (!$entity instanceof Entity) return false;
        // Reouvre une nouvelle session pour la suite.
        session_start();

        //$this->_flushSessionStore();
        $this->_flushBufferStore();

        // Enregistre l'ancienne entité.
        $this->setSessionStore('nebuleTempPublicEntityInstance', serialize($this->_currentEntityInstance));

        // Change l'entité en cours.
        $this->_currentEntityInstance = $entity;
        $this->_currentEntity = $this->_currentEntityInstance->getID();
        $this->setSessionStore('nebulePublicEntity', $this->_currentEntity);
        $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
        // Cherche la clé privée.
        $this->_findCurrentEntityPrivateKey();
        // Test si le mot de passe est bon.
        $this->_currentEntityUnlocked = $this->_currentEntityInstance->checkPrivateKeyPassword();

        session_write_close();
        return true;
    }

    /**
     * Annule le changement temporaire de l'entité. Les caches sont effacés.
     * Elle contient déjà son mot de passe si elle était déverrouillée.
     *
     * L'ancienne entité est restaurée. L'entité actuelle est retirée.
     *
     * @return boolean
     */
    public function unsetTempCurrentEntity()
    {
        // Reouvre une nouvelle session pour la suite.
        session_start();

        // Restaure l'ancienne entité.
        $entity = $this->getSessionStore('nebuleTempPublicEntityInstance');
        if ($entity === false) {
            session_write_close();
            return false;
        }

        //$this->_flushSessionStore();
        $this->_flushBufferStore();

        // Change l'entité en cours.
        $this->_currentEntityInstance = unserialize($entity);
        $this->_currentEntity = $this->_currentEntityInstance->getID();
        $this->setSessionStore('nebulePublicEntity', $this->_currentEntity);
        $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
        // Cherche la clé privée.
        $this->_findCurrentEntityPrivateKey();
        // Test si le mot de passe est bon.
        $this->_currentEntityUnlocked = $this->_currentEntityInstance->checkPrivateKeyPassword();

        session_write_close();
        return true;
    }


    /**
     * Liste des ID des entitées déverrouillées.
     *
     * @var array:string
     */
    private $_listEntitiesUnlocked = array();

    /**
     * Liste des instances des entitées déverrouillées.
     *
     * @var array:Entity
     */
    private $_listEntitiesUnlockedInstances = array();

    /**
     * Lit la liste des ID des entités déverrouillées.
     *
     * @return string
     */
    public function getListEntitiesUnlocked()
    {
        return $this->_listEntitiesUnlocked;
    }

    /**
     * Lit la liste des instances des entités déverrouillées.
     *
     * @return Entity
     */
    public function getListEntitiesUnlockedInstances()
    {
        return $this->_listEntitiesUnlockedInstances;
    }

    /**
     * Ajoute une entité à la liste des entités déverrouillées.
     *
     * @param string|Entity $id
     * @return void
     */
    public function addListEntitiesUnlocked($id)
    {
        $instance = $this->convertIdToTypedObjectInstance($id);
        if (!is_a($id, 'Entity')) {
            return;
        }
        $id = $instance->getID();

        $this->_listEntitiesUnlocked[$id] = $id;
        $this->_listEntitiesUnlockedInstances[$id] = $instance;
    }

    /**
     * Retire une entité de la liste des entités déverrouillées.
     *
     * @param string|Entity $id
     * @return void
     */
    public function removeListEntitiesUnlocked($id)
    {
        if (is_a($id, 'Node')) {
            $id = $id->getID();
        }

        unset($this->_listEntitiesUnlocked[$id]);
        unset($this->_listEntitiesUnlockedInstances[$id]);
    }

    /**
     * Efface toute la liste des entités déverrouillées.
     *
     * @return void
     */
    public function flushListEntitiesUnlocked()
    {
        $this->_listEntitiesUnlocked = array();
        $this->_listEntitiesUnlockedInstances = array();
    }


    /**
     * ID du groupe en cours.
     *
     * @var string
     */
    private $_currentGroup = '';

    /**
     * Instance du groupe en cours.
     *
     * @var Group|null
     */
    private $_currentGroupInstance = null;

    /**
     * Recherche la group en cours d'utilisation.
     *
     * Si pas d'argument de définition de l'objet, et si pas dans le cache, l'ID est mis à 0.
     *
     * @return void
     */
    private function _findCurrentGroup()
    {
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
 		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_GROUP)) {
            $arg_grp = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        } else {
            $arg_grp = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        }

        // Si la variable est un objet avec ou sans liens.
        if ($arg_grp != ''
            && (strlen($arg_grp) >= self::NEBULE_MINIMUM_ID_SIZE
                || $arg_grp == '0'
            )
            && ctype_xdigit($arg_grp)
            && ($this->getIO()->checkObjectPresent($arg_grp)
                || $this->getIO()->checkLinkPresent($arg_grp)
                || $arg_grp == '0'
            )
        ) {
            // Ecrit le groupe dans la variable.
            $this->_currentGroup = $arg_grp;
            $this->_currentGroupInstance = $this->newGroup($arg_grp);
            // Ecrit le groupe dans la session.
            $this->setSessionStore('nebuleSelectedGroup', $arg_grp);
        } else {
            // Sinon vérifie si une valeur n'est pas mémorisée dans la session.
            $cache = $this->getSessionStore('nebuleSelectedGroup');
            // Si il existe une variable de session pour le groupe en cours, la lit.
            if ($cache !== false
                && $cache != ''
            ) {
                $this->_currentGroup = $cache;
                $this->_currentGroupInstance = $this->newGroup($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentGroup = '0'; // $this->_currentObject;
                $this->_currentGroupInstance = $this->newGroup('0'); // $this->_currentObjectInstance;
                // Ecrit le groupe dans la session.
                $this->setSessionStore('nebuleSelectedGroup', $this->_currentGroup);
            }
            unset($cache);
        }
        unset($arg_grp);

        $this->_metrology->addLog('Find current group ' . $this->_currentGroup, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID du groupe en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentGroup()
    {
        return $this->_currentGroup;
    }

    /**
     * Donne l'instance du groupe en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return Group
     */
    public function getCurrentGroupInstance()
    {
        return $this->_currentGroupInstance;
    }



    // Gestion de la conversation en cours.

    /**
     * ID de la conversation en cours.
     *
     * @var string
     */
    private $_currentConversation = '';

    /**
     * Instance de la conversation en cours.
     *
     * @var Conversation|null
     */
    private $_currentConversationInstance = null;

    /**
     * Recherche la conversation en cours d'utilisation.
     *
     * Si pas d'argument de définition de l'objet, et si pas dans le cache, l'ID est mis à 0.
     *
     * @return void
     */
    private function _findCurrentConversation()
    {
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
 		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_CONVERSATION)) {
            $arg_cvt = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        } else {
            $arg_cvt = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        }

        // Si la variable est un objet avec ou sans liens.
        if ($arg_cvt != ''
            && (strlen($arg_cvt) >= self::NEBULE_MINIMUM_ID_SIZE
                || $arg_cvt == '0'
            )
            && ctype_xdigit($arg_cvt)
            && ($this->getIO()->checkObjectPresent($arg_cvt)
                || $this->getIO()->checkLinkPresent($arg_cvt)
                || $arg_cvt == '0'
            )
        ) {
            // Ecrit la conversation dans la variable.
            $this->_currentConversation = $arg_cvt;
            $this->_currentConversationInstance = $this->newConversation($arg_cvt);
            // Ecrit la conversation dans la session.
            $this->setSessionStore('nebuleSelectedConversation', $arg_cvt);
        } else {
            // Sinon vérifie si une valeur n'est pas mémorisée dans la session.
            $cache = $this->getSessionStore('nebuleSelectedConversation');
            // Si il existe une variable de session pour la conversation en cours, la lit.
            if ($cache !== false
                && $cache != ''
            ) {
                $this->_currentConversation = $cache;
                $this->_currentConversationInstance = $this->newConversation($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentConversation = '0'; // $this->_currentObject;
                $this->_currentConversationInstance = $this->newConversation('0'); // $this->_currentObjectInstance;
                // Ecrit la conversation dans la session.
                $this->setSessionStore('nebuleSelectedConversation', $this->_currentConversation);
            }
            unset($cache);
        }
        unset($arg_cvt);

        $this->_metrology->addLog('Find current conversation ' . $this->_currentConversation, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID de la conversation en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentConversation()
    {
        return $this->_currentConversation;
    }

    /**
     * Donne l'instance de la conversation en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return Conversation
     */
    public function getCurrentConversationInstance()
    {
        return $this->_currentConversationInstance;
    }



    // Gestion de la monnaie en cours.

    /**
     * ID de la monnaie en cours.
     *
     * @var string
     */
    private $_currentCurrency = '';

    /**
     * Instance de la monnaie en cours.
     *
     * @var Currency|null
     */
    private $_currentCurrencyInstance = null;

    /**
     * Recherche la monnaie en cours d'utilisation.
     *
     * Si pas d'argument de définition de l'objet, et si pas dans le cache, l'ID est mis à 0.
     *
     * @return void
     */
    private function _findCurrentCurrency()
    {
        // Si pas autorisé, retourne ID=0.
        if (!$this->_configuration->getOption('permitCurrency')) {
            $this->_currentCurrency = '0';
            $this->_currentCurrencyInstance = $this->newCurrency('0');
            // Ecrit la monnaie dans la session.
            $this->setSessionStore('nebuleSelectedCurrency', $this->_currentCurrency);
            return;
        }

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
 		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_CURRENCY)) {
            $arg = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_CURRENCY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        } else {
            $arg = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_CURRENCY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        }

        // Si la variable est un objet avec ou sans liens.
        if ($arg != ''
            && (strlen($arg) >= self::NEBULE_MINIMUM_ID_SIZE
                || $arg == '0'
            )
            && ctype_xdigit($arg)
            && ($this->getIO()->checkObjectPresent($arg)
                || $this->getIO()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            // Ecrit la monnaie dans la variable.
            $this->_currentCurrency = $arg;
            $this->_currentCurrencyInstance = $this->newCurrency($arg);
            // Ecrit la monnaie dans la session.
            $this->setSessionStore('nebuleSelectedCurrency', $arg);
        } else {
            // Sinon vérifie si une valeur n'est pas mémorisée dans la session.
            $cache = $this->getSessionStore('nebuleSelectedCurrency');
            // Si il existe une variable de session pour la monnaie en cours, la lit.
            if ($cache !== false
                && $cache != ''
            ) {
                $this->_currentCurrency = $cache;
                $this->_currentCurrencyInstance = $this->newCurrency($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentCurrency = '0';
                $this->_currentCurrencyInstance = $this->newCurrency('0');
                // Ecrit la monnaie dans la session.
                $this->setSessionStore('nebuleSelectedCurrency', $this->_currentCurrency);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrology->addLog('Find current currency ' . $this->_currentCurrency, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID de la monnaie en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentCurrency()
    {
        return $this->_currentCurrency;
    }

    /**
     * Donne l'instance de la monnaie en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return Currency
     */
    public function getCurrentCurrencyInstance()
    {
        return $this->_currentCurrencyInstance;
    }



    // Gestion du sac de jetons en cours.

    /**
     * ID du sac de jetons en cours.
     *
     * @var string
     */
    private $_currentTokenPool = '';

    /**
     * Instance du sac de jetons en cours.
     *
     * @var TokenPool|null
     */
    private $_currentTokenPoolInstance = null;

    /**
     * Recherche le sac de jetons en cours d'utilisation.
     *
     * Si pas d'argument de définition de l'objet, et si pas dans le cache, l'ID est mis à 0.
     *
     * @return void
     */
    private function _findCurrentTokenPool()
    {
        // Si pas autorisé, retourne ID=0.
        if (!$this->_configuration->getOption('permitCurrency')) {
            $this->_currentTokenPool = '0';
            $this->_currentTokenPoolInstance = $this->newTokenPool('0');
            // Ecrit le sac de jetons dans la session.
            $this->setSessionStore('nebuleSelectedTokenPool', $this->_currentTokenPool);
            return;
        }

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
 		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_TOKENPOOL)) {
            $arg = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_TOKENPOOL, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        } else {
            $arg = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_TOKENPOOL, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        }

        // Si la variable est un objet avec ou sans liens.
        if ($arg != ''
            && (strlen($arg) >= self::NEBULE_MINIMUM_ID_SIZE
                || $arg == '0'
            )
            && ctype_xdigit($arg)
            && ($this->getIO()->checkObjectPresent($arg)
                || $this->getIO()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            // Ecrit le sac de jetons dans la variable.
            $this->_currentTokenPool = $arg;
            $this->_currentTokenPoolInstance = $this->newTokenPool($arg);
            // Ecrit le sac de jetons dans la session.
            $this->setSessionStore('nebuleSelectedTokenPool', $arg);
        } else {
            // Sinon vérifie si une valeur n'est pas mémorisée dans la session.
            $cache = $this->getSessionStore('nebuleSelectedTokenPool');
            // Si il existe une variable de session pour le sac de jetons en cours, la lit.
            if ($cache !== false
                && $cache != ''
            ) {
                $this->_currentTokenPool = $cache;
                $this->_currentTokenPoolInstance = $this->newTokenPool($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentTokenPool = '0';
                $this->_currentTokenPoolInstance = $this->newTokenPool('0');
                // Ecrit le sac de jetons dans la session.
                $this->setSessionStore('nebuleSelectedTokenPool', $this->_currentTokenPool);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrology->addLog('Find current token pool ' . $this->_currentTokenPool, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID du sac de jetons en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentTokenPool()
    {
        return $this->_currentTokenPool;
    }

    /**
     * Donne l'instance du sac de jetons en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return TokenPool
     */
    public function getCurrentTokenPoolInstance()
    {
        return $this->_currentTokenPoolInstance;
    }



    // Gestion du jeton en cours.

    /**
     * ID du jeton en cours.
     *
     * @var string
     */
    private $_currentToken = '';

    /**
     * Instance du jeton en cours.
     *
     * @var Token|null
     */
    private $_currentTokenInstance = null;

    /**
     * Recherche le jeton en cours d'utilisation.
     *
     * Si pas d'argument de définition de l'objet, et si pas dans le cache, l'ID est mis à 0.
     *
     * @return void
     */
    private function _findCurrentToken()
    {
        // Si pas autorisé, retourne ID=0.
        if (!$this->_configuration->getOption('permitCurrency')) {
            $this->_currentToken = '0';
            $this->_currentTokenInstance = $this->newToken('0');
            // Ecrit le jeton dans la session.
            $this->setSessionStore('nebuleSelectedToken', $this->_currentToken);
            return;
        }

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
 		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_TOKEN)) {
            $arg = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_TOKEN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        } else {
            $arg = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_TOKEN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        }

        // Si la variable est un objet avec ou sans liens.
        if ($arg != ''
            && (strlen($arg) >= self::NEBULE_MINIMUM_ID_SIZE
                || $arg == '0'
            )
            && ctype_xdigit($arg)
            && ($this->getIO()->checkObjectPresent($arg)
                || $this->getIO()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            // Ecrit le jeton dans la variable.
            $this->_currentToken = $arg;
            $this->_currentTokenInstance = $this->newToken($arg);
            // Ecrit le jeton dans la session.
            $this->setSessionStore('nebuleSelectedToken', $arg);
        } else {
            // Sinon vérifie si une valeur n'est pas mémorisée dans la session.
            $cache = $this->getSessionStore('nebuleSelectedToken');
            // Si il existe une variable de session pour le jeton en cours, la lit.
            if ($cache !== false
                && $cache != ''
            ) {
                $this->_currentToken = $cache;
                $this->_currentTokenInstance = $this->newToken($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentToken = '0';
                $this->_currentTokenInstance = $this->newToken('0');
                // Ecrit le jeton dans la session.
                $this->setSessionStore('nebuleSelectedToken', $this->_currentToken);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrology->addLog('Find current token ' . $this->_currentToken, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID du jeton en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentToken()
    {
        return $this->_currentToken;
    }

    /**
     * Donne l'instance du jeton en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return Token
     */
    public function getCurrentTokenInstance()
    {
        return $this->_currentTokenInstance;
    }


    /**
     * Vérification du niveau de cohérence et d'utilisation de l'instance.
     * - à 0, il y a un problème grave, le maître manque ;
     * - de 1 à 4, il manque une entité importante ;
     * - à 32, il manque l'entité de l'instance locale ;
     * - à 64, l'instance est prête mais non utilisée ;
     * - à 128, l'instance est prête et utilisée. Une entité courante a été trouvée.
     *
     * @return integer
     */
    public function checkInstance()
    {
        // Vérifie que le maître est une entité et est bien celui définit par la constante.
        if (!$this->_puppetmasterInstance instanceof Entity) return 0;
        if ($this->_puppetmasterInstance->getID() != $this->_configuration->getOption('puppetmaster')) return 0;
        // Vérifie que le maître de la sécurité est une entité et a été trouvé.
        if (!$this->_securityMasterInstance instanceof Entity) return 1;
        if ($this->_securityMasterInstance->getID() == '0') return 1;
        // Vérifie que le maître du code est une entité et a été trouvé.
        if (!$this->_codeMasterInstance instanceof Entity) return 2;
        if ($this->_codeMasterInstance->getID() == '0') return 2;
        // Vérifie que le maître de l'annuaire est une entité et a été trouvé.
        if (!$this->_directoryMasterInstance instanceof Entity) return 3;
        if ($this->_directoryMasterInstance->getID() == '0') return 3;
        // Vérifie que le maître du temps est une entité et a été trouvé.
        if (!$this->_timeMasterInstance instanceof Entity) return 4;
        if ($this->_timeMasterInstance->getID() == '0') return 4;

        // Vérifie que l'entité de l'instance nebule est une entité et a été trouvée.
        if (!$this->_instanceEntityInstance instanceof Entity) return 32;
        if ($this->_instanceEntityInstance->getID() == '0') return 32;

        // Vérifie qu'une entité courante existe et est une entité.
        if (!$this->_currentEntityInstance instanceof Entity) return 64;
        if ($this->_currentEntityInstance->getID() == '0') return 64;

        // Tout est bon et l'instance est utilisée.
        return 128;
    }


    /**
     * Le maître du tout.
     *
     * @var string
     */
    private $_puppetmaster = '';

    /**
     * L'instance du maître du tout.
     *
     * @var Entity|null
     */
    private $_puppetmasterInstance = null;

    /**
     * Récupération du maître.
     *
     * Définit par une option ou en dur dans une constante.
     *
     * @return null
     */
    private function _findPuppetmaster()
    {
        $this->_puppetmaster = $this->_configuration->getOption('puppetmaster');
        $this->_puppetmasterInstance = $this->newEntity($this->_puppetmaster);
        $this->_metrology->addLog('Find puppetmaster ' . $this->_puppetmaster, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID du maître du tout.
     *
     * @return string
     */
    public function getPuppetmaster()
    {
        return $this->_puppetmaster;
    }

    /**
     * Donne l'instance du maître du tout.
     *
     * @return Entity|NULL
     */
    public function getPuppetmasterInstance()
    {
        return $this->_puppetmasterInstance;
    }


    /**
     * Recherche une entité pour un rôle.
     *
     * Ne tien compte que des liens du puppetmaster uniquement.
     *
     * @param $type string
     * @return string
     */
    private function _findEntityByType($type)
    {
        if ($type == '')
            return $this->_puppetmaster;

        $typeID = $this->_crypto->hash($type);
        if ($typeID === false)
            return $this->_puppetmaster;

        $typeInstance = $this->newObject($typeID);

        // Recherche les liens signés du maître du tout de type f avec source et méta le rôle recherché.
        $list = $typeInstance->readLinksFilterFull(
            $this->_puppetmaster,
            '',
            'f',
            $typeID,
            '',
            $typeID);

        if (sizeof($list) == 0)
            return $this->_puppetmaster;

        $link = reset($list);
        unset($list);
        return $link->getHashTarget();
    }


    /**
     * L'ID du maître de la sécurité.
     */
    private $_securityMaster = '';

    /**
     * L'instance du maître de la sécurité.
     */
    private $_securityMasterInstance = null;

    /**
     * Récupération du maître de la sécurité.
     */
    private function _findSecurityMaster()
    {
        if ($this->_securityMaster != '') {
            return;
        }

        $type = self::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_SECURITE;
        $this->_securityMaster = $this->_findEntityByType($type);
        $this->_securityMasterInstance = $this->newEntity($this->_securityMaster);

        $this->_metrology->addLog('Find security master ' . $this->_securityMaster, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    public function getSecurityAuthority()
    {
        return $this->_securityMaster;
    }

    public function getSecurityAuthorityInstance()
    {
        return $this->_securityMasterInstance;
    }

    /**
     * L'ID du maître du code.
     */
    private $_codeMaster;

    /**
     * L'instance du maître du code.
     */
    private $_codeMasterInstance;

    /**
     * Récupération du maître du code.
     */
    private function _findCodeMaster()
    {
        if ($this->_codeMaster != '') {
            return;
        }

        $type = self::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_CODE;
        $this->_codeMaster = $this->_findEntityByType($type);
        $this->_codeMasterInstance = $this->newEntity($this->_codeMaster);

        $this->_metrology->addLog('Find code master ' . $this->_codeMaster, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    public function getCodeAuthority()
    {
        return $this->_codeMaster;
    }

    public function getCodeAuthorityInstance()
    {
        return $this->_codeMasterInstance;
    }

    /**
     * Le maître de l'annuaire.
     */
    private $_directoryMaster;

    /**
     * L'instance du maître de l'annuaire.
     */
    private $_directoryMasterInstance;

    /**
     * Récupération du maître de l'annuaire.
     */
    private function _findDirectoryMaster()
    {
        if ($this->_directoryMaster != '') {
            return;
        }

        $type = self::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_ANNUAIRE;
        $this->_directoryMaster = $this->_findEntityByType($type);
        $this->_directoryMasterInstance = $this->newEntity($this->_directoryMaster);

        $this->_metrology->addLog('Find directory master ' . $this->_directoryMaster, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    public function getDirectoryAuthority()
    {
        return $this->_directoryMaster;
    }

    public function getDirectoryAuthorityInstance()
    {
        return $this->_directoryMasterInstance;
    }

    /**
     * Le maître du temps.
     */
    private $_timeMaster;

    /**
     * L'instance du maître du temps.
     */
    private $_timeMasterInstance;

    /**
     * Récupération du maître du temps.
     */
    private function _findTimeMaster()
    {
        if ($this->_timeMaster != '') {
            return;
        }

        $type = self::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_TEMPS;
        $this->_timeMaster = $this->_findEntityByType($type);
        $this->_timeMasterInstance = $this->newEntity($this->_timeMaster);

        $this->_metrology->addLog('Find time master ' . $this->_timeMaster, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    public function getTimeAuthority()
    {
        return $this->_timeMaster;
    }

    public function getTimeAuthorityInstance()
    {
        return $this->_timeMasterInstance;
    }

    /**
     * Liste des ID des autorités.
     * @var array
     */
    private $_authorities = array();

    /**
     * Liste des instances des autorités.
     * @var array
     */
    private $_authoritiesInstances = array();

    /**
     * Liste des ID des autorités locales.
     * @var array:string
     */
    private $_localAuthorities = array();

    /**
     * Liste des instance des autorités locales.
     * @var array:Entity
     */
    private $_localAuthoritiesInstances = array();

    /**
     * Liste des ID des autorités locales primaires.
     * @var array:string
     */
    private $_localPrimaryAuthorities = array();

    /**
     * Liste des instance des autorités locales primaires.
     * @var array:Entity
     */
    private $_localPrimaryAuthoritiesInstances = array();

    /**
     * Liste des entités signataires des autorités locales.
     * @var array:string
     */
    private $_localAuthoritiesSigners = array();

    /**
     * Liste des ID des entités avec des rôles.
     * @var array
     */
    private $_specialEntities = array();


    /**
     * L'entité locale du serveur est-elle autorité locale ?
     * @var boolean
     */
    private $_permitInstanceEntityAsAuthority = false;

    /**
     * L'entité locale par défaut est-elle autorité locale ?
     * @var boolean
     */
    private $_permitDefaultEntityAsAuthority = false;

    /**
     * Ajoute les autorités locales par défaut.
     *
     * @return void
     */
    private function _findLocalAuthorities()
    {
        // Ajoute les entités de nebule.
        $this->_authorities[$this->_puppetmaster] = $this->_puppetmaster;
        $this->_authoritiesInstances[$this->_puppetmaster] = $this->_puppetmasterInstance;
        $this->_specialEntities[$this->_puppetmaster] = $this->_puppetmaster;
        $this->_authorities[$this->_securityMaster] = $this->_securityMaster;
        $this->_authoritiesInstances[$this->_securityMaster] = $this->_securityMasterInstance;
        $this->_specialEntities[$this->_securityMaster] = $this->_securityMaster;
        $this->_authorities[$this->_codeMaster] = $this->_codeMaster;
        $this->_authoritiesInstances[$this->_codeMaster] = $this->_codeMasterInstance;
        $this->_specialEntities[$this->_codeMaster] = $this->_codeMaster;
        $this->_localAuthorities[$this->_codeMaster] = $this->_codeMaster;
        $this->_localAuthoritiesInstances[$this->_codeMaster] = $this->_codeMasterInstance;
        $this->_localAuthoritiesSigners[$this->_codeMaster] = $this->_puppetmaster;
        $this->_specialEntities[$this->_directoryMaster] = $this->_directoryMaster;
        $this->_specialEntities[$this->_timeMaster] = $this->_timeMaster;
    }

    /**
     * Ajoute si autorisé l'entité instance du serveur comme autorité locale.
     * Désactivé automatiquement en mode récupération.
     *
     * @return void
     */
    private function _addInstanceEntityAsAuthorities()
    {
        // Ajoute si nécessaire l'entité du serveur.
        if (!$this->_modeRescue)
            $this->_permitInstanceEntityAsAuthority = $this->_configuration->getOption('permitInstanceEntityAsAuthority');
        else
            $this->_permitInstanceEntityAsAuthority = false;

        if ($this->_permitInstanceEntityAsAuthority) {
            $this->_authorities[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_authoritiesInstances[$this->_instanceEntity] = $this->_instanceEntityInstance;
            $this->_specialEntities[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_localAuthorities[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_localAuthoritiesInstances[$this->_instanceEntity] = $this->_instanceEntityInstance;
            $this->_localAuthoritiesSigners[$this->_instanceEntity] = '0';
            $this->_localPrimaryAuthorities[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_localPrimaryAuthoritiesInstances[$this->_instanceEntity] = $this->_instanceEntityInstance;

            $this->_metrology->addLog('Add instance entity as authority', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        }
    }

    /**
     * Ajoute si autorisé l'entité par défaut comme autorité locale.
     * Désactivé automatiquement en mode récupération.
     *
     * @return void
     */
    private function _addDefaultEntityAsAuthorities()
    {
        // Ajoute si nécessaire l'entité par défaut.
        if (!$this->_modeRescue)
            $this->_permitDefaultEntityAsAuthority = $this->_configuration->getOption('permitDefaultEntityAsAuthority');
        else
            $this->_permitDefaultEntityAsAuthority = false;

        if ($this->_permitDefaultEntityAsAuthority) {
            $this->_authorities[$this->_defaultEntity] = $this->_defaultEntity;
            $this->_authoritiesInstances[$this->_defaultEntity] = $this->_defaultEntityInstance;
            $this->_specialEntities[$this->_defaultEntity] = $this->_defaultEntity;
            $this->_localAuthorities[$this->_defaultEntity] = $this->_defaultEntity;
            $this->_localAuthoritiesInstances[$this->_defaultEntity] = $this->_defaultEntityInstance;
            $this->_localAuthoritiesSigners[$this->_defaultEntity] = '0';
            $this->_localPrimaryAuthorities[$this->_defaultEntity] = $this->_defaultEntity;
            $this->_localPrimaryAuthoritiesInstances[$this->_defaultEntity] = $this->_defaultEntityInstance;

            $this->_metrology->addLog('Add default entity as authority', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        }
    }

    /**
     * Ajoute des autres entité marqués comme autorités locales.
     *
     * @return void
     */
    private function _addLocalAuthorities()
    {
        // Vérifie si les entités autorités locales sont autorisées.
        if (!$this->_configuration->getOption('permitLocalSecondaryAuthorities'))
            return;

        $refAuthority = $this->_crypto->hash(self::REFERENCE_NEBULE_OBJET_ENTITE_AUTORITE_LOCALE);

        // Liste les liens de l'entité instance du serveur..
        $list = array();
        if ($this->_permitInstanceEntityAsAuthority) {
            $list = $this->_instanceEntityInstance->readLinksFilterFull(
                $this->_instanceEntity,
                '',
                'f',
                $this->_instanceEntity,
                '',
                $refAuthority
            );
        }

        foreach ($list as $link) {
            $target = $link->getHashTarget();
            $instance = $this->newEntity($target);
            $this->_localAuthorities[$target] = $target;
            $this->_localAuthoritiesInstances[$target] = $instance;
            $this->_specialEntities[$target] = $target;
            $this->_localAuthoritiesSigners[$target] = $link->getHashSigner();
            $this->_authorities[$target] = $target;
            $this->_authoritiesInstances[$target] = $instance;
        }

        // Liste les liens de l'entité instance du serveur..
        $list = array();
        if ($this->_permitDefaultEntityAsAuthority) {
            $list = $this->_instanceEntityInstance->readLinksFilterFull(
                $this->_defaultEntity,
                '',
                'f',
                $this->_instanceEntity,
                '',
                $refAuthority
            );
        }

        foreach ($list as $link) {
            $target = $link->getHashTarget();
            $instance = $this->newEntity($target);
            $this->_localAuthorities[$target] = $target;
            $this->_localAuthoritiesInstances[$target] = $instance;
            $this->_specialEntities[$target] = $target;
            $this->_localAuthoritiesSigners[$target] = $link->getHashSigner();
            $this->_authorities[$target] = $target;
            $this->_authoritiesInstances[$target] = $instance;
        }
        unset($list);
    }

    /**
     * Lit la liste des ID des autorités.
     *
     * @return array:string
     */
    public function getAuthorities()
    {
        return $this->_authorities;
    }

    /**
     * Lit la liste des instance des autorités.
     *
     * @return array:Entity
     */
    public function getAuthoritiesInstance()
    {
        return $this->_authoritiesInstances;
    }

    /**
     * Lit la liste des ID des autorités locales.
     *
     * @return array:string
     */
    public function getLocalAuthorities()
    {
        return $this->_localAuthorities;
    }

    /**
     * Lit la liste des instance des autorités locales.
     *
     * @return array:Entity
     */
    public function getLocalAuthoritiesInstance()
    {
        return $this->_localAuthoritiesInstances;
    }

    /**
     * Lit la liste des autorités locales déclarants des autorités locales.
     *
     * @return array:string
     */
    public function getLocalAuthoritiesSigners()
    {
        return $this->_localAuthoritiesSigners;
    }

    /**
     * Lit la liste des ID des autorités locales.
     *
     * @return array:string
     */
    public function getLocalPrimaryAuthorities()
    {
        return $this->_localPrimaryAuthorities;
    }

    /**
     * Lit la liste des instance des autorités locales.
     *
     * @return array:Entity
     */
    public function getLocalPrimaryAuthoritiesInstance()
    {
        return $this->_localPrimaryAuthoritiesInstances;
    }

    /**
     * Lit la liste des ID des entités avec des rôles.
     *
     * @return array:string
     */
    public function getSpecialEntities()
    {
        return $this->_specialEntities;
    }

    /**
     * Retourne si l'entité est autorité locale.
     *
     * @param Entity|string $entity
     * @return boolean
     */
    public function getIsLocalAuthority($entity)
    {
        if (is_a($entity, 'Node'))
            $entity = $entity->getID();
        if ($entity == '0')
            return false;

        foreach ($this->_localAuthorities as $authority) {
            if ($entity == $authority)
                return true;
        }
        return false;
    }


    /**
     * Liste des entités de recouvrement.
     * @var array:string
     */
    private $_recoveryEntities = array();

    /**
     * Liste des instances des entités de recouvrement.
     * @var array:Entity
     */
    private $_recoveryEntitiesInstances = array();

    /**
     * Liste des autorités locales déclarants les entités de recouvrement.
     * @var array:string
     */
    private $_recoveryEntitiesSigners = array();

    /**
     * L'entité locale du serveur est-elle entité de recouvrement locale ?
     * @var boolean
     */
    private $_permitInstanceEntityAsRecovery = false;

    /**
     * L'entité locale par défaut est-elle entité de recouvrement locale ?
     * @var boolean
     */
    private $_permitDefaultEntityAsRecovery = false;

    /**
     * Ajoute si autorisé l'entité instance du serveur comme entité de recouvrement locale.
     *
     * @return void
     */
    private function _addInstanceEntityAsRecovery()
    {
        // Vérifie si les entités de recouvrement sont autorisées.
        if (!$this->_configuration->getOption('permitRecoveryEntities'))
            return;

        $this->_permitInstanceEntityAsRecovery = $this->_configuration->getOption('permitInstanceEntityAsRecovery');

        if ($this->_permitInstanceEntityAsRecovery) {
            $this->_recoveryEntities[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_recoveryEntitiesInstances[$this->_instanceEntity] = $this->_instanceEntityInstance;
            $this->_recoveryEntitiesSigners[$this->_instanceEntity] = '0';

            $this->_metrology->addLog('Add instance entity as recovery', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        }
    }

    /**
     * Ajoute si autorisé l'entité par défaut comme entité de recouvrement locale.
     *
     * @return void
     */
    private function _addDefaultEntityAsRecovery()
    {
        // Vérifie si les entités de recouvrement sont autorisées.
        if (!$this->_configuration->getOption('permitRecoveryEntities'))
            return;

        $this->_permitDefaultEntityAsRecovery = $this->_configuration->getOption('permitDefaultEntityAsRecovery');

        if ($this->_permitDefaultEntityAsRecovery) {
            $this->_recoveryEntities[$this->_defaultEntity] = $this->_defaultEntity;
            $this->_recoveryEntitiesInstances[$this->_defaultEntity] = $this->_defaultEntityInstance;
            $this->_recoveryEntitiesSigners[$this->_defaultEntity] = '0';

            $this->_metrology->addLog('Add default entity as recovery', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        }
    }

    /**
     * Recherche les entités de recouvrement valables.
     *
     * @return void
     */
    private function _findRecoveryEntities()
    {
        $this->_recoveryEntities = array();
        $this->_recoveryEntitiesInstances = array();

        // Vérifie si les entités de recouvrement sont autorisées.
        if (!$this->_configuration->getOption('permitRecoveryEntities'))
            return;

        $refRecovery = $this->_crypto->hash(self::REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT);

        // Liste les liens de l'entité instance du serveur..
        $list = array();
        if ($this->_permitInstanceEntityAsAuthority) {
            $list = $this->_instanceEntityInstance->readLinksFilterFull(
                $this->_instanceEntity,
                '',
                'f',
                $this->_instanceEntity,
                '',
                $refRecovery
            );
        }

        foreach ($list as $link) {
            $target = $link->getHashTarget();
            $instance = $this->newEntity($target);
            $this->_recoveryEntities[$target] = $target;
            $this->_recoveryEntitiesInstances[$target] = $instance;
            $this->_recoveryEntitiesSigners[$target] = $link->getHashSigner();
        }

        // Liste les liens de l'entité instance du serveur..
        $list = array();
        if ($this->_permitDefaultEntityAsAuthority) {
            $list = $this->_instanceEntityInstance->readLinksFilterFull(
                $this->_defaultEntity,
                '',
                'f',
                $this->_instanceEntity,
                '',
                $refRecovery
            );
        }

        foreach ($list as $link) {
            $target = $link->getHashTarget();
            $instance = $this->newEntity($target);
            $this->_recoveryEntities[$target] = $target;
            $this->_recoveryEntitiesInstances[$target] = $instance;
            $this->_recoveryEntitiesSigners[$target] = $link->getHashSigner();
        }
        unset($list);
    }

    /**
     * Lit la liste des ID des entités de recouvrement.
     *
     * @return array:string
     */
    public function getRecoveryEntities()
    {
        return $this->_recoveryEntities;
    }

    /**
     * Lit la liste des instance des entités de recouvrement.
     *
     * @return array:Entity
     */
    public function getRecoveryEntitiesInstance()
    {
        return $this->_recoveryEntitiesInstances;
    }

    /**
     * Lit la liste des autorités locales déclarants les entités de recouvrement.
     *
     * @return array:string
     */
    public function getRecoveryEntitiesSigners()
    {
        return $this->_recoveryEntitiesSigners;
    }

    /**
     * Retourne si l'entité est entité de recouvrement.
     *
     * @param Entity|string $entity
     * @return boolean
     */
    public function getIsRecoveryEntity($entity)
    {
        if (is_a($entity, 'Node'))
            $entity = $entity->getID();
        if ($entity == '0')
            return false;

        foreach ($this->_recoveryEntities as $recovery) {
            if ($entity == $recovery)
                return true;
        }
        return false;
    }



    /**
     * Export l'objet de la configuration.
     *
     * @return Configuration
     */
    public function getConfigurationInstance()
    {
        return $this->_configuration;
    }

    /**
     * Export l'objet des entrées/sorties.
     *
     * @return io
     */
    public function getIO()
    {
        return $this->_io;
    }

    /**
     * Export l'objet de la crypto.
     *
     * @return Crypto
     */
    public function getCrypto()
    {
        return $this->_crypto;
    }

    /**
     * Export l'objet du calcul social.
     *
     * @return Social
     */
    public function getSocial()
    {
        return $this->_social;
    }

    /**
     * Export l'objet de la métrologie.
     *
     * @return Metrology
     */
    public function getMetrologyInstance()
    {
        return $this->_metrology;
    }



    /**
     * Retourne la liste des instances de toutes les entités loacles.
     *
     * @return array:Entity
     */
    public function getListEntitiesInstances()
    {
        $hashType = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $hashEntity = $this->_crypto->hash('application/x-pem-file');
        $hashEntityObject = $this->newObject($hashEntity);

        // Liste les liens.
        $links = $hashEntityObject->readLinksFilterFull('', '', 'l', '', $hashEntity, $hashType);
        unset($hashType, $hashEntity, $hashEntityObject);

        // Filtre les entités sur le contenu de l'objet de la clé publique. @todo
        $result = array();
        $id = '';
        $instance = null;
        foreach ($links as $link) {
            $id = $link->getHashSource();
            $instance = $this->newEntity($id);
            if ($instance->getIsPublicKey())
                $result[$id] = $instance;
        }

        unset($links, $link, $id, $instance);
        return $result;
    }

    /**
     * Retourne la liste des ID de toutes les entités loacles.
     *
     * @return array:string
     */
    public function getListEntitiesID()
    {
        // Liste les instances.
        $list = $this->getListEntitiesInstances();
        $result = array();

        // Filtre les entités sur le contenu de l'objet de la clé publique. @todo
        foreach ($list as $instance) {
            $id = $instance->getID();
            $result[$id] = $id;
        }

        unset($list, $instance);
        return $result;
    }


    /**
     * Variable de mode de récupération.
     *
     * @var boolean
     */
    private $_modeRescue = false;

    /**
     * Extrait si on est en mode de récupération.
     *
     * @return null
     */
    private function _findModeRescue()
    {
        if ($this->_configuration->getOption('modeRescue')
            || ($this->_configuration->getOption('permitOnlineRescue')
                && (filter_has_var(INPUT_GET, nebule::COMMAND_RESCUE)
                    || filter_has_var(INPUT_POST, nebule::COMMAND_RESCUE)
                )
            )
        )
            $this->_modeRescue = true;
    }

    /**
     * Retourne si le mode de récupération est activé.
     * @return boolean
     */
    public function getModeRescue()
    {
        return $this->_modeRescue;
    }


    /**
     * Extrait et analyse un lien.
     *
     * Accepte une chaine de caractère représentant un lien.
     * En fonction du nombre de champs, c'est interprété :
     * 2 champs : 0_0_0_action_source_0_0
     * 3 champs : 0_0_0_action_source_target_0
     * 4 champs : 0_0_0_action_source_target_meta
     * 5 champs : 0_0_date_action_source_target_meta
     * 6 champs : 0_signer_date_action_source_target_meta
     * 7 champs : signe_signer_date_action_source_target_meta
     * Sinon    : 0_0_0_0_0_0_0
     *
     * Retourne un tableau des constituants du lien :
     * [signature, signataire, date, action, source, destination, méta]
     * Les champs non renseignés sont à '0'.
     *
     * @param string $link : lien à extraire.
     * @return array:string : un tableau des champs (signature, signataire, date, action, source, destination, méta).
     */
    public function flatLinkExtractAsArray($link)
    {
        // Variables.
        $list = array();
        $date = date(DATE_ATOM);
        $ent = $this->_currentEntity;

        // Extraction du lien.
        $arg1 = strtok($link, '_');
        $arg2 = strtok('_');
        $arg3 = strtok('_');
        $arg4 = strtok('_');
        $arg5 = strtok('_');
        $arg6 = strtok('_');
        $arg7 = strtok('_');

        // Nettoyage du lien.
        if ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 != '' && $arg5 != '' && $arg6 != '' && $arg7 != '') {
            // Forme : signe_signer_date_action_source_target_meta
            $list = array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7);
        } elseif ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 != '' && $arg5 != '' && $arg6 != '' && $arg7 == '') {
            // Forme : 0_signer_date_action_source_target_meta
            $list = array('0', $arg1, $arg2, $arg3, $arg4, $arg5, $arg6);
        } elseif ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 != '' && $arg5 != '' && $arg6 == '' && $arg7 == '') {
            // Forme : 0_0_date_action_source_target_meta
            $list = array('0', $ent, $arg1, $arg2, $arg3, $arg4, $arg5);
        } elseif ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 != '' && $arg5 == '' && $arg6 == '' && $arg7 == '') {
            // Forme : 0_0_0_action_source_target_meta
            $list = array('0', $ent, $date, $arg1, $arg2, $arg3, $arg4);
        } elseif ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 == '' && $arg5 == '' && $arg6 == '' && $arg7 == '') {
            // Forme : 0_0_0_action_source_target_0
            $list = array('0', $ent, $date, $arg1, $arg2, $arg3, '0');
        } elseif ($arg1 != '' && $arg2 != '' && $arg3 == '' && $arg4 == '' && $arg5 == '' && $arg6 == '' && $arg7 == '') {
            // Forme : 0_0_0_action_source_0_0 : le minimum !
            $list = array('0', $ent, $date, $arg1, $arg2, '0', '0');
        } else {
            $list = array('0', '0', '0', '0', '0', '0', '0');
        }

        unset($date, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7);
        return $list;
    }

    /**
     * Extrait et analyse un lien.
     * @param string $link : lien à extraire.
     * @return Link : une instance de lien.
     * @todo
     *
     * Accepte une chaine de caractère représentant un lien.
     * En fonction du nombre de champs, c'est interprété :
     * 2 champs : 0_0_0_action_source_0_0
     * 3 champs : 0_0_0_action_source_target_0
     * 4 champs : 0_0_0_action_source_target_meta
     * 5 champs : 0_0_date_action_source_target_meta
     * 6 champs : 0_signer_date_action_source_target_meta
     * 7 champs : signe_signer_date_action_source_target_meta
     * Sinon    : 0_0_0_0_0_0_0
     *
     * Retourne une instance du lien.
     *
     */
    public function flatLinkExtractAsInstance($link)
    {
        // Vérifier compatibilité avec liens incomplets...

        // Extrait le lien.
        $linkArray = $this->flatLinkExtractAsArray($link);

        // Création du lien.
        $flatLink = $linkArray[0] . '_' . $linkArray[1] . '_' . $linkArray[2] . '_' . $linkArray[3] . '_' . $linkArray[4] . '_' . $linkArray[5] . '_' . $linkArray[6];
        $linkInstance = $this->newLink($flatLink);

        unset($linkArray, $flatLink);
        return $linkInstance;
    }


    /**
     * Comparateur de dates.
     * @param string $date1
     * @param string $date2
     * @todo
     *
     */
    public function dateCompare($date1, $date2)
    {
        if ($date1 == '') return false;
        if ($date2 == '') return false;
        // Extrait les éléments d'une date.
        $d1 = date_parse($date1);
        if ($d1 === false) return 0;
        $d2 = date_parse($date2);
        if ($d2 === false) return 0;
        // Année
        if ($d1['year'] > $d2['year']) return 1;
        if ($d1['year'] < $d2['year']) return -1;
        // Mois
        if ($d1['month'] === false) return 0;
        if ($d2['month'] === false) return 0;
        if ($d1['month'] > $d2['month']) return 1;
        if ($d1['month'] < $d2['month']) return -1;
        // Jour
        if ($d1['day'] === false) return 0;
        if ($d2['day'] === false) return 0;
        if ($d1['day'] > $d2['day']) return 1;
        if ($d1['day'] < $d2['day']) return -1;
        // Heure
        if ($d1['hour'] === false) return 0;
        if ($d2['hour'] === false) return 0;
        if ($d1['hour'] > $d2['hour']) return 1;
        if ($d1['hour'] < $d2['hour']) return -1;
        // Minute
        if ($d1['minute'] === false) return 0;
        if ($d2['minute'] === false) return 0;
        if ($d1['minute'] > $d2['minute']) return 1;
        if ($d1['minute'] < $d2['minute']) return -1;
        // Seconde
        if ($d1['second'] === false) return 0;
        if ($d2['second'] === false) return 0;
        if ($d1['second'] > $d2['second']) return 1;
        if ($d1['second'] < $d2['second']) return -1;
        // Fraction de seconde
        if ($d1['fraction'] === false) return 0;
        if ($d2['fraction'] === false) return 0;
        if ($d1['fraction'] > $d2['fraction']) return 1;
        if ($d1['fraction'] < $d2['fraction']) return -1;
        return 0;
        // A faire... comparateur universel sur multiples formats de dates...
    }


    /**
     * Détermine si on doit vider le cache des objets et effacer la session utilisateur.
     *
     * @return null
     */
    private function _findFlushCache()
    {
        global $bootstrap_flush;

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        //if ( filter_has_var(INPUT_GET, self::COMMAND_FLUSH) )
        if ($bootstrap_flush) {
            $this->_metrology->addLog('Ask flush cache', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
            // Enregistre la demande de le pas alimenter le cache des entités/objets et liens.
            $this->_flushCache = true;

            /*$this->_metrology->addLog('Flush user session', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
			// Flush la session utilisateur.
			session_start();
			session_unset();
			session_destroy();
			session_write_close();
			setcookie(session_name(), '', 0, '/');
			session_regenerate_id(true);
			// Reouvre une nouvelle session pour la suite.
			session_start();
			session_write_close();*/
        }
    }

    /**
     * Retourne si le cache des objets et la session utilisateur ont été effacés.
     *
     * @return boolean
     */
    public function getFlushCache()
    {
        return $this->_flushCache;
    }


    /**
     * Crée et écrit un objet avec du texte.
     * @param string $text
     * @param boolean $protect
     */
    public function createTextAsObject(string &$text, bool $protect = false, bool $obfuscate = false)
    {
        // Vérifie que l'écriture est autorisée.
        if ($this->_configuration->getOption('permitWrite')
            && $this->_configuration->getOption('permitWriteObject')
            && $this->_configuration->getOption('permitWriteLink')
            && $this->_currentEntityUnlocked
            && strlen($text) != 0
        ) {
            // Calcule l'ID de l'objet à créer.
            $id = $this->_crypto->hash($text);

            // Vérifie si l'ID n'existe pas déjà.
            if ($this->_io->checkObjectPresent($id))
                return $id;

            // Ecrit l'objet.
            $instance = new Node($this, '0', $text, $protect, $obfuscate);
            $id = $instance->getID();
            if ($id == '0')
                return false;

            // Définition de la date.
            $date = date(DATE_ATOM);
            $signer = $this->_currentEntity;

            // Crée le lien de type d'objet.
            $action = 'l';
            $source = $id;
            $target = $this->getCrypto()->hash('text/plain');
            $meta = $this->getCrypto()->hash(self::REFERENCE_NEBULE_OBJET_TYPE);
            $link = '_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new Link($this->_nebuleInstance, $link);

            // Signe le lien.
            $newLink->sign();

            // Si besoin, obfuscation du lien.
            if ($obfuscate)
                $link->obfuscate();
            // Ecrit le lien.
            $newLink->write();

            unset($signer, $date, $action, $source, $target, $meta, $link, $newLink);

            return $id;
        } else
            return false;
    }


    /**
     * Vérifie le minimum vital pour nebule.
     * @return boolean
     * @todo
     *
     */
    private function _nebuleCheckEnvironment()
    {
        return true;
        // A faire...
    }

    /**
     * Initialise l'environnement minimum pour nebule.
     * @return void
     * @todo
     *
     */
    private function _nebuleInitEnvironment()
    {
        // A faire...
    }


    /**
     * Recherche des liens par rapport à une référence qui est le type d'objet.
     * La recherche se fait dans les liens de l'objet type.
     * Cette recherche est utilisée pour retrouver les groupes et les conversations.
     *
     * Toutes les références et propriétés sont hachées avec un algorithme fixe.
     *
     * $entity Permet de ne sélectionner que les liens générés par une entité.
     *
     * @param string|Node $type
     * @param string $socialClass
     * @param string|Entity $entity
     * @return array:Link
     * @todo ajouter un filtre sur le type mime des objets.
     *
     */
    public function getListLinksByType($type, $entity = '', $socialClass = '')
    {
        /**
         * Résultat de la recherche de liens à retourner.
         * @var array:Link $result
         */
        $result = array();

        /**
         * Empreinte du type d'objet à rechercher.
         * @var string $hashType
         */
        $hashType = '';

        /**
         * Empreinte de l'entité pour la recherche.
         * @var string $hashEntity
         */
        $hashEntity = '';

        // Si le type est une instance, récupère l'ID de l'instance de l'objet du type.
        if (is_a($type, 'Node')) {
            $hashType = $type->getID();
        } else {
            // Si le type est un ID (héxadécimal), l'utilise directement. Sinon calcul l'empreinte du type.
            if (ctype_xdigit($type))
                $hashType = $type;
            else
                $hashType = $this->_crypto->hash($type, self::REFERENCE_CRYPTO_HASH_ALGORITHM);
            // $type doit être une instance d'objet au final.
            $type = $this->newObject($hashType);
        }
        // Si l'ID de l'instance du type est null ou vide, quitte en renvoyant un résultat vide.
        if ($hashType == '0'
            || $hashType == ''
        )
            return $result;

        // Si l'entité est une instance, récupère l'ID de l'instance de l'entité.
        if (is_a($entity, 'Node')) {
            $hashEntity = $entity->getID();
        } else {
            // Si l'entité est un ID (héxadécimal), l'utilise directement. Sinon calcul l'empreinte de l'entité.
            if (ctype_xdigit($entity)
                && $this->getIO()->checkLinkPresent($entity)
                && $this->getIO()->checkObjectPresent($entity)
            )
                $hashEntity = $entity;
            else
                $hashEntity = '';
        }

        // Lit les liens de l'objet de référence.
        $result = $type->readLinksFilterFull(
            $hashEntity,
            '',
            'l',
            '',
            $hashType,
            $this->_crypto->hash(self::REFERENCE_NEBULE_OBJET_TYPE, self::REFERENCE_CRYPTO_HASH_ALGORITHM)
        );

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($result, $socialClass);

        // retourne le résultat.
        return $result;
    }

    /**
     * Recherche des ID d'objets par rapport à une référence qui est le type d'objet.
     *
     * $entity Permet de ne sélectionner que les liens générés par une entité.
     *
     * @param string|Node $type
     * @param string $socialClass
     * @param string|Entity $entity
     * @return array:Link
     */
    public function getListIdByType($type = '', $entity = '', $socialClass = '')
    {
        /**
         * Résultat de la recherche de liens à retourner.
         * @var array:Link $result
         */
        $result = $this->getListLinksByType($type, $entity, $socialClass);

        // Extrait les ID.
        foreach ($result as $i => $l)
            $result[$i] = $l->getHashSource();

        // retourne le résultat.
        return $result;
    }

    /**
     * Extrait la liste des liens définissant les groupes d'objets.
     *
     * $entity Permet de ne sélectionner que les groupes générés par une entité.
     *
     * @param string $socialClass
     * @param string|Entity $entity
     * @return array:Link
     */
    public function getListGroupsLinks($entity = '', $socialClass = '')
    {
        return $this->getListLinksByType(self::REFERENCE_NEBULE_OBJET_GROUPE, $entity, $socialClass);
    }

    /**
     * Extrait la liste des ID des groupes d'objets.
     *
     * $entity Permet de ne sélectionner que les groupes générés par une entité.
     *
     * @param string|entity $entity
     * @param string $socialClass
     * @return array
     */
    public function getListGroupsID($entity = '', $socialClass = '')
    {
        return $this->getListIdByType(self::REFERENCE_NEBULE_OBJET_GROUPE, $entity, $socialClass);
    }

    /**
     * Extrait la liste des liens définissant les conversations.
     *
     * Précalcul le hash de l'objet définissant une conversation.
     * Extrait l'ID de l'entité, si demandé.
     * Liste les liens définissants les différentes conversations.
     * Retourne la liste.
     *
     * $entity : Permet de ne sélectionner que les conversations générés par une entité.
     *
     * @param string|entity $entity
     * @return array
     */
    public function getListConversationsLinks($entity = '', $socialClass = '')
    {
        return $this->getListLinksByType(self::REFERENCE_NEBULE_OBJET_CONVERSATION, $entity, $socialClass);
    }

    /**
     * Extrait la liste des ID des conversations.
     * Géré comme des groupes d'objets.
     *
     * $entity Permet de ne sélectionner que les conversations générées par une entité.
     *
     * @param string|entity $entity
     * @param string $socialClass
     * @return array
     */
    public function getListConversationsID($entity = '', $socialClass = '')
    {
        return $this->getListIdByType(self::REFERENCE_NEBULE_OBJET_CONVERSATION, $entity, $socialClass);
    }


    /**
     * Contient l'état de validité du ticket des actions.
     * @var boolean
     */
    private $_validTicket = false;

    /**
     * Lit le ticket pour les actions et le valide si il est connu et non utilisé.
     * Le ticket reconnu est marqué dans la liste des ticket afin d'interdire le rejeu.
     * Le ticket inconnu n'est pas marqué afin d'empêcher une attaque par remplissage.
     *
     * Pour que certaines actions puissent être validées, un ticket doit être présenté dans l'URL.
     * Le ticket doit être connu, valide et non utilisé.
     *
     * @return null
     */
    private function _findActionTicket()
    {
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
 		 *  ------------------------------------------------------------------------------------------
		 */
        $ticket = '0';
        // Lit et nettoye le contenu de la variable GET.
        $arg_get = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_TICKET, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        // Lit et nettoye le contenu de la variable POST.
        $arg_post = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_TICKET, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        // Vérifie les variables.
        if ($arg_get != ''
            && strlen($arg_get) >= self::NEBULE_MINIMUM_ID_SIZE
            && ctype_xdigit($arg_get)
        ) {
            $ticket = $arg_get;
        } elseif ($arg_post != ''
            && strlen($arg_post) >= self::NEBULE_MINIMUM_ID_SIZE
            && ctype_xdigit($arg_post)
        ) {
            $ticket = $arg_post;
        }
        unset($arg_get, $arg_post);

        // Vérifie le ticket.
        session_start();
        if ($ticket == '0'
            || $this->_flushCache
        ) {
            // Le ticket est null, aucun ticket trouvé en argument.
            // Aucune action ne doit être réalisée.
            $this->_metrology->addLog('Ticket: none', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
            $this->_validTicket = false;
        } elseif (isset($_SESSION['Ticket'][$ticket])
            && $_SESSION['Ticket'][$ticket] !== true
        ) {
            // Le ticket est déjà connu mais est déjà utilisé, c'est un rejeu.
            // Aucune action ne doit être réalisée.
            $this->_metrology->addLog('Ticket: replay ' . $ticket, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000'); // Log
            $this->_validTicket = false;
            $_SESSION['Ticket'][$ticket] = false;
        } elseif (isset($_SESSION['Ticket'][$ticket])
            && $_SESSION['Ticket'][$ticket] === true
        ) {
            // Le ticket est connu et n'est pas utilisé, c'est bon.
            // Il est marqué maintenant comme utilisé.
            // Les actions peuvent être réalisées.
            $this->_metrology->addLog('Ticket: valid ' . $ticket, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
            $this->_validTicket = true;
            $_SESSION['Ticket'][$ticket] = false;
        } else {
            // Le ticket est inconnu.
            // Pas de mémorisation.
            // Aucune action ne doit être réalisée.
            $this->_metrology->addLog('Ticket: error ' . $ticket, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000'); // Log
            $this->_validTicket = false;
        }
        session_write_close();
        unset($ticket);
    }

    /**
     * Génère un ticket pour valider une action et interdire le rejeu d'action.
     * Stock le ticket pour vérification ultérieure.
     * Retourne le ticket avec la ligne pour insertion directe dans une url.
     *
     * Pour que certaines actions puissent être validées, un ticket doit être présenté dans l'URL.
     * Le ticket doit être connu, valide et non utilisé.
     *
     * @return string
     */
    public function getActionTicket()
    {
        return '&' . self::COMMAND_SELECT_TICKET . '=' . $this->getActionTicketValue();
    }

    /**
     * Génère un ticket pour valider une action et interdire le rejeu d'action.
     * Stock le ticket pour vérification ultérieure.
     * Retourne la valeur du ticket.
     *
     * La valeur de référence est pseudo-aléatoire mais suffisante pour résister
     *   à une attaque le temps d'une session utilisateur.
     *
     * Pour que certaines actions puissent être validées, un ticket doit être présenté dans l'URL.
     * Le ticket doit être connu, valide et non utilisé.
     *
     * @return string
     */
    public function getActionTicketValue()
    {
        session_start();
        $data = $this->_crypto->getPseudoRandom(2048);
        $ticket = $this->_crypto->hash($data);
        unset($data);
        $_SESSION['Ticket'][$ticket] = true;
        session_write_close();
        return $ticket;
    }

    /**
     * Vérifie que le ticket est connu, valide et non utilisé.
     *
     * Pour que certaines actions puissent être validées, un ticket doit être présenté dans l'URL.
     * Le ticket doit être connu, valide et non utilisé.
     *
     * @return boolean
     */
    public function checkActionTicket()
    {
        return $this->_validTicket;
    }


    /**
     * Extrait l'argument pour continuer un affichage en ligne à partir d'un objet particulier.
     * Rtourne tout type de chaine de texte nécessaire à l'affichage,
     *   ou une chaine vide si pas d'argument valable trouvé.
     *
     * @return string
     */
    public function getDisplayNextObject()
    {
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        $this->_metrology->addLog('Extract display next object', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        // Lit et nettoye le contenu de la variable GET.
        $arg = trim(' ' . filter_input(INPUT_GET, Displays::DEFAULT_NEXT_COMMAND, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        // Extraction du lien et stockage pour traitement.
        if ($arg != ''
            && strlen($arg) >= self::NEBULE_MINIMUM_ID_SIZE
            && ctype_xdigit($arg)
        ) {
            return $arg;
        }
        return '';
    }


    /**
     * Détermine si c'est un objet.
     * Retourne une instance appropriée en fonction du type d'objet.
     *
     * @param string|Node|Conversation|Group|Entity|Currency|TokenPool|Token|Wallet $id
     * @return Node|Conversation|Group|Entity|Currency|TokenPool|Token|Wallet
     */
    public function convertIdToTypedObjectInstance($id)
    {
        if (is_a($id, 'Node')
            || is_a($id, 'Group')
            || is_a($id, 'Entity')
            || is_a($id, 'Conversation')
            || is_a($id, 'Currency')
            || is_a($id, 'TokenPool')
            || is_a($id, 'Token')
            || is_a($id, 'Wallet')
        ) {
            return $id;
        }

        $social = 'all';

        if ($id == '0'
            || $id == ''
        ) {
            $instance = $this->_nebuleInstance->newObject('0');
        } else {
            $instance = $this->_nebuleInstance->newObject($id);
            if ($instance->getIsEntity($social)) {
                $instance = $this->_nebuleInstance->newEntity($id);
            } elseif ($instance->getIsWallet($social)) {
                $instance = $this->_nebuleInstance->newWallet($id);
            } elseif ($instance->getIsToken($social)) {
                $instance = $this->_nebuleInstance->newToken($id);
            } elseif ($instance->getIsTokenPool($social)) {
                $instance = $this->_nebuleInstance->newTokenPool($id);
            } elseif ($instance->getIsCurrency($social)) {
                $instance = $this->_nebuleInstance->newCurrency($id);
            } elseif ($instance->getIsConversation($social)) {
                $instance = $this->_nebuleInstance->newConversation($id);
            } elseif ($instance->getIsGroup($social)) {
                $instance = $this->_nebuleInstance->newGroup($id);
            } else {
                $protected = $instance->getMarkProtected();
                if ($protected) {
                    $instance = $this->_nebuleInstance->newObject($instance->getID());
                }
                if ($instance->getType('all') == nebule::REFERENCE_OBJECT_ENTITY) {
                    $instance = $this->_nebuleInstance->newEntity($id);
                }
            }
        }

        return $instance;
    }


    /**
     * Extrait une valeur associée à une clé dans un objet, typiquement une variable dans un code.
     *
     * Retourne false si non trouvé.
     *
     * @param string $id
     * @param string $key
     * @return string
     */
    public function _readFileValue($id, $key, $size = 0)
    {
        if ($key == '') {
            return false;
        }

        if ($size == 0) {
            $size = $this->_configuration->getOption('ioReadMaxData');
        }

        $value = false;
        $readValue = $this->_nebuleInstance->getIO()->objectRead($id);
        $startValue = strpos($readValue, $key);
        $trimLine = substr($readValue, $startValue, $size);
        $arrayValue = explode('"', $trimLine);
        if ($arrayValue[1] != null) {
            $value = $arrayValue[1];
        } else {
            $arrayValue = explode("'", $trimLine);
            if ($arrayValue[1] != null) {
                $value = $arrayValue[1];
            }
        }
        return $value;
    }
}
