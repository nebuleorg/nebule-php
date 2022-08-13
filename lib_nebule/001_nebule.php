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
    const NEBULE_ENVIRONMENT_FILE = 'c';
    const NEBULE_BOOTSTRAP_FILE = 'index.php';
    const NEBULE_MINIMUM_ID_SIZE = 6;
    const NEBULE_MAXIMUM_ID_SIZE = 8192;
    const NEBULE_MINIMUM_ALGO_SIZE = 2;
    const NEBULE_MAXIMUM_ALGO_SIZE = 12;
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
    const LIB_RID_SECURITY_AUTHORITY = 'a4b210d4fb820a5b715509e501e36873eb9e27dca1dd591a98a5fc264fd2238adf4b489d.none.288';
    const LIB_RID_CODE_AUTHORITY = '2b9dd679451eaca14a50e7a65352f959fc3ad55efc572dcd009c526bc01ab3fe304d8e69.none.288';
    const LIB_RID_TIME_AUTHORITY = 'bab7966fd5b483f9556ac34e4fac9f778d0014149f196236064931378785d81cae5e7a6e.none.288';
    const LIB_RID_DIRECTORY_AUTHORITY = '0a4c1e7930a65672379616a2637b84542049b416053ac0d9345300189791f7f8e05f3ed4.none.288';

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
    const RESERVED_OBJECTS_LIST = array(
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
    private $_metrologyInstance;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    private $_configurationInstance;

    /**
     * Instance de gestion du cache.
     *
     * @var Cache
     */
    private $_cacheInstance;

    /**
     * Instance de gestion des tickets.
     *
     * @var Ticketing
     */
    private $_ticketingInstance;

    /**
     * Instance des entrées/sorties.
     *
     * @var ioInterface
     */
    private $_ioInstance;

    /**
     * Instance de gestion de la cryptographie.
     *
     * @var CryptoInterface
     */
    private $_cryptoInstance;

    /**
     * Instance de gestion des relations sociales des liens.
     *
     * @var SocialInterface
     */
    private $_socialInstance;

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
        $this->_cacheInstance->saveCacheOnSessionBuffer();
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
        $this->_metrologyInstance = new Metrology($this->_nebuleInstance);
        $this->_configurationInstance = new Configuration($this->_nebuleInstance);
        $this->_cacheInstance = new Cache($this->_nebuleInstance);
        $this->_ticketingInstance = new Ticketing($this->_nebuleInstance);

        $this->_findModeRescue();

        if (!$this->_nebuleCheckEnvironment())
            $this->_nebuleInitEnvironment();

        $this->_ioInstance = new io($this->_nebuleInstance);
        $this->_cryptoInstance = new Crypto($this->_nebuleInstance);
        $this->_socialInstance = new Social($this->_nebuleInstance);

        $this->_metrologyInstance->addLog('First step init nebule instance', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '64154189'); // Log

        $this->_configurationInstance->setPermitOptionsByLinks(true);
        $this->_configurationInstance->flushCache();

        $this->_findFlushCache();

        $this->_getSubordinationEntity();
        $this->_cacheInstance->readCacheOnSessionBuffer();

        $this->_checkWriteableIO();

        $this->_findPuppetmaster();
        $this->_findGlobalAuthorities();
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

        $this->_metrologyInstance->addLog('End init nebule instance', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }



    /**
     * Export l'objet de la métrologie.
     *
     * @return Metrology
     */
    public function getMetrologyInstance(): Metrology
    {
        return $this->_metrologyInstance;
    }

    /**
     * Export l'objet de la configuration.
     *
     * @return Configuration
     */
    public function getConfigurationInstance(): Configuration
    {
        return $this->_configurationInstance;
    }

    /**
     * Export l'objet de la métrologie.
     *
     * @return Cache
     */
    public function getCacheInstance(): Cache
    {
        return $this->_cacheInstance;
    }

    /**
     * Export l'objet de la gestion des tickets.
     *
     * @return Ticketing
     */
    public function getTicketingInstance(): Ticketing
    {
        return $this->_ticketingInstance;
    }

    /**
     * Export l'objet des entrées/sorties.
     *
     * @return io
     */
    public function getIoInstance(): ioInterface
    {
        return $this->_ioInstance;
    }

    /**
     * Export l'objet de la crypto.
     *
     * @return CryptoInterface
     */
    public function getCryptoInstance(): CryptoInterface
    {
        return $this->_cryptoInstance;
    }

    /**
     * Export l'objet du calcul social.
     *
     * @return SocialInterface
     */
    public function getSocialInstance(): SocialInterface
    {
        return $this->_socialInstance;
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
        //$this->_subordinationEntity = new Entity($this->_nebuleInstance, (string)Configuration::getOptionFromEnvironmentUntypedStatic('subordinationEntity'));
        $this->_subordinationEntity = new Entity($this->_nebuleInstance, $this->_configurationInstance->getOptionFromEnvironmentAsString('subordinationEntity'));
        $this->_metrologyInstance->addLog('Get subordination entity = ' . $this->_subordinationEntity, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '2300b439');
    }

    /**
     * Retourne l'entité de subordination si défini.
     * Retourne une chaine vide sinon.
     *
     * @return node
     */
    public function getSubordinationEntity(): ?node
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
        if ($this->_ioInstance->getMode() == 'RW') {
            if (!$this->_ioInstance->checkObjectsWrite())
                $this->_configurationInstance->lockPermitWriteObject();;
            if (!$this->_ioInstance->checkLinksWrite())
                $this->_configurationInstance->lockPermitWriteLink();;
        } else {
            $this->_configurationInstance->lockPermitWrite();
            $this->_configurationInstance->lockPermitWriteObject();
            $this->_configurationInstance->lockPermitWriteLink();
        }

        if (!$this->_configurationInstance->getOptionAsBoolean('permitWriteObject'))
            $this->_metrologyInstance->addLog('objects ro not rw', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
        if (!$this->_configurationInstance->getOptionAsBoolean('permitWriteLink'))
            $this->_metrologyInstance->addLog('links ro not rw', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
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
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
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
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
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
        $this->_metrologyInstance->addLog('Flush session store', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
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
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
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
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
        )
            return false;

        session_start();
        $_SESSION['Buffer'][$name] = serialize($content);
        session_write_close();
        return true;
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
     * Object - Calculate NID for data with hash algo.
     *
     * @param string $data
     * @param string $algo
     * @return string
     */
    public function getNIDfromData(string $data, string $algo = ''): string
    {
        if ($algo == '')
            $algo = $this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm');
        return $this->_cryptoInstance->hash($data, $algo) . '.' . $algo;
    }

    /**
     * Nouvelle instance d'un objet.
     *
     * @param string $oid
     * @return Node|nodeInterface
     */
    public function newObject(string $oid): Node
    {
        return $this->_cacheInstance->newNode($oid, Cache::TYPE_NODE);
    }


    /**
     * Nouvelle instance d'une entité.
     *
     * @param string $nid
     * @return Entity|nodeInterface
     */
    public function newEntity_DEPRECATED(string $nid): Entity
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_ENTITY);
    }

    /**
     * Nouvelle instance d'un groupe.
     *
     * @param string $nid
     * @return Group|nodeInterface
     */
    public function newGroup_DEPRECATED(string $nid): Group
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_GROUP);
    }

    /**
     * Nouvelle instance d'une conversation.
     *
     * @param string $nid
     * @return Conversation|nodeInterface
     */
    public function newConversation_DEPRECATED(string $nid): Conversation
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_CONVERSATION);
    }

    /**
     * Nouvelle instance d'une monnaie.
     *
     * @param string $nid
     * @return Currency|nodeInterface
     */
    public function newCurrency_DEPRECATED(string $nid): Currency
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_CURRENCY);
    }

    /**
     * Nouvelle instance d'un jeton.
     *
     * @param string $nid
     * @return Token|nodeInterface
     */
    public function newToken_DEPRECATED(string $nid): Token
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_TOKEN);
    }

    /**
     * Nouvelle instance d'un sac de jetons.
     *
     * @param string $nid
     * @return TokenPool|nodeInterface
     */
    public function newTokenPool_DEPRECATED(string $nid): TokenPool
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_TOKENPOOL);
    }

    /**
     * Nouvelle instance d'un portefeuille.
     *
     * @param string $nid
     * @return Wallet|nodeInterface
     */
    public function newWallet_DEPRECATED(string $nid): Wallet
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_WALLET);
    }

    /**
     * Nouvelle instance d'un lien.
     *
     * @param string $link
     * @return BlocLink
     */
    public function newLink_DEPRECATED(string $link): BlocLink
    {
        return $this->_cacheInstance->newLink($link, Cache::TYPE_LINK);
    }

    /**
     * Nouvelle instance d'une transaction.
     * Attention, c'est un lien !
     *
     * @param string $link
     * @return BlocLink
     */
    public function newTransaction_DEPRECATED(string $link): BlocLink
    {
        return $this->_cacheInstance->newLink($link, Cache::TYPE_TRANSACTION);
    }



    // Gestion des modules.

    /**
     * Liste les modules.
     *
     * @param string $name
     * @return boolean
     * @todo
     */
    public function listModule(string $name): bool
    {
        if ($name == '') {
            return false;
        }

        // ...

        return true;
    }

    /**
     * Charge un module.
     *
     * @param string $name
     * @return boolean|Modules
     * @todo
     */
    public function loadModule(string $name)
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
        if (Node::checkNID($arg_obj, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg_obj)
                || $this->getIoInstance()->checkLinkPresent($arg_obj)
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

        $this->_metrologyInstance->addLog('Find current object ' . $this->_currentObject, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        $this->_currentObjectInstance->getMarkProtected();
    }

    /**
     * Donne l'ID de l'objet en cours.
     *
     * @return string
     */
    public function getCurrentObject(): string
    {
        return $this->_currentObject;
    }

    /**
     * Donne l'instance de l'objet en cours.
     *
     * @return Node|null
     */
    public function getCurrentObjectInstance(): ?Node
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

            if (Node::checkNID($id, false, false)
                && $this->_ioInstance->checkObjectPresent($id)
                && $this->_ioInstance->checkLinkPresent($id)
            ) {
                $this->_instanceEntity = $id;
                $this->_instanceEntityInstance = $this->_cacheInstance->newNode($id, Cache::TYPE_ENTITY);
            } else {
                // Sinon utilise l'instance du maître du code.
                $this->_instanceEntity = $this->_puppetmaster;
                $this->_instanceEntityInstance = $this->_puppetmasterInstance;
            }

            // Log
            $this->_metrologyInstance->addLog('Find server entity ' . $this->_instanceEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

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
    public function getInstanceEntity(): string
    {
        return $this->_instanceEntity;
    }

    /**
     * Donne l'instance de l'entité de l'instance de serveur.
     *
     * @return Entity|null
     */
    public function getInstanceEntityInstance(): ?Entity
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
            $id = $this->_configurationInstance->getOptionUntyped('defaultCurrentEntity');

            if (Node::checkNID($id, false, false)
                && $this->_ioInstance->checkObjectPresent($id)
                && $this->_ioInstance->checkLinkPresent($id)
            ) {
                $this->_defaultEntity = $id;
                $this->_defaultEntityInstance = $this->_cacheInstance->newNode($id, Cache::TYPE_ENTITY);
            } else {
                // Sinon utilise l'instance du serveur hôte.
                $this->_defaultEntity = $this->_instanceEntity;
                $this->_defaultEntityInstance = $this->_instanceEntityInstance;
            }

            // Log
            $this->_metrologyInstance->addLog('Find default entity ' . $this->_defaultEntity, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');

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
    public function getDefaultEntity(): string
    {
        return $this->_defaultEntity;
    }

    /**
     * Donne l'instance de l'entité par défaut.
     *
     * @return Entity|null
     */
    public function getDefaultEntityInstance(): ?Entity
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
            && Node::checkNID($arg_ent, false, false)
            && $this->_ioInstance->checkObjectPresent($arg_ent)
            && $this->_ioInstance->checkLinkPresent($arg_ent)
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
            $this->_metrologyInstance->addLog('New current entity ' . $this->_currentEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
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
                $this->_metrologyInstance->addLog('Reuse current entity ' . $this->_currentEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
            } else // Sinon essaie de la trouver ailleurs.
            {
                $itc_ent = '';
                $ext_ent = $this->_configurationInstance->getOptionUntyped('defaultCurrentEntity');
                if (Node::checkNID($ext_ent, false, false)
                    && $this->_ioInstance->checkObjectPresent($ext_ent)
                    && $this->_ioInstance->checkLinkPresent($ext_ent)) {
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
                    $this->_metrologyInstance->addLog('Find default current entity ' . $this->_currentEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
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
                    $this->_metrologyInstance->addLog('Find current (instance) entity ' . $this->_currentEntity, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
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
            $this->_metrologyInstance->addLog('Reuse current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        } // Sinon essaie de la trouver ailleurs
        else {
            if (is_a($this->_currentEntityInstance, 'Entity')) {
                $this->_currentEntityPrivateKey = $this->_currentEntityInstance->getPrivateKeyID();
                if ($this->_currentEntityPrivateKey != '') {
                    $this->_currentEntityPrivateKeyInstance = $this->newObject($this->_currentEntityPrivateKey);
                    // Log
                    $this->_metrologyInstance->addLog('Find current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                } else {
                    $this->_currentEntityPrivateKeyInstance = '';
                    // Log
                    $this->_metrologyInstance->addLog('Cant find current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
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
                $this->_metrologyInstance->addLog('Logout ' . $this->_currentEntity, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
            }
        } else {
            // Ajoute le mot de passe à l'entité en cours.
            $this->_currentEntityInstance->setPrivateKeyPassword($arg_pwd);
            $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
            // Test si le mot de passe est bon.
            $this->_currentEntityUnlocked = $this->_currentEntityInstance->checkPrivateKeyPassword();
            if ($this->_currentEntityUnlocked) {
                $this->_metrologyInstance->addLog('Login password ' . $this->_currentEntity . ' OK', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
            } else {
                $this->_metrologyInstance->addLog('Login password ' . $this->_currentEntity . ' NOK', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000'); // Log
            }
        }
        unset($arg_logout, $arg_pwd, $arg_get_pwd, $arg_post_pwd);
    }

    /**
     * Lit l'ID de l'entité en cours.
     *
     * @return string
     */
    public function getCurrentEntity(): string
    {
        return $this->_currentEntity;
    }

    /**
     * Lit l'instance de l'entité en cours.
     *
     * @return Entity|null
     */
    public function getCurrentEntityInstance(): ?Entity
    {
        return $this->_currentEntityInstance;
    }

    /**
     * Lit l'ID de la clé privée de l'entité en cours.
     *
     * @return string
     */
    public function getCurrentEntityPrivateKey(): string
    {
        return $this->_currentEntityPrivateKey;
    }

    /**
     * Lit l'instance de la clé privée de l'entité en cours.
     *
     * @return Node|null
     */
    public function getCurrentEntityPrivateKeyInstance(): ?Node
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
    public function getCurrentEntityUnlocked(): bool
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
    public function setCurrentEntity(Entity $entity): bool
    {
        if (!$entity instanceof Entity) return false;
        // Reouvre une nouvelle session pour la suite.
        session_start();

        //$this->_flushSessionStore();
        $this->_cacheInstance->flushBufferStore();

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
    public function setTempCurrentEntity(Entity $entity): bool
    {
        if (!$entity instanceof Entity) return false;
        // Reouvre une nouvelle session pour la suite.
        session_start();

        //$this->_flushSessionStore();
        $this->_cacheInstance->flushBufferStore();

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
    public function unsetTempCurrentEntity(): bool
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
        $this->_cacheInstance->flushBufferStore();

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
     * @return array
     */
    public function getListEntitiesUnlocked(): array
    {
        return $this->_listEntitiesUnlocked;
    }

    /**
     * Lit la liste des instances des entités déverrouillées.
     *
     * @return array
     */
    public function getListEntitiesUnlockedInstances(): array
    {
        return $this->_listEntitiesUnlockedInstances;
    }

    /**
     * Ajoute une entité à la liste des entités déverrouillées.
     *
     * @param Entity $entity
     * @return void
     */
    public function addListEntitiesUnlocked(Entity $entity): void
    {
        if ($entity->getID() == '0')
            return;
        $eid = $entity->getID();

        $this->_listEntitiesUnlocked[$eid] = $eid;
        $this->_listEntitiesUnlockedInstances[$eid] = $entity;
    }

    /**
     * Retire une entité de la liste des entités déverrouillées.
     *
     * @param Entity $entity
     * @return void
     */
    public function removeListEntitiesUnlocked(Entity $entity)
    {
        unset($this->_listEntitiesUnlocked[$entity->getID()]);
        unset($this->_listEntitiesUnlockedInstances[$entity->getID()]);
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
        if (Node::checkNID($arg_grp, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg_grp)
                || $this->getIoInstance()->checkLinkPresent($arg_grp)
                || $arg_grp == '0'
            )
        ) {
            // Ecrit le groupe dans la variable.
            $this->_currentGroup = $arg_grp;
            $this->_currentGroupInstance = $this->newGroup_DEPRECATED($arg_grp);
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
                $this->_currentGroupInstance = $this->newGroup_DEPRECATED($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentGroup = '0'; // $this->_currentObject;
                $this->_currentGroupInstance = $this->newGroup_DEPRECATED('0'); // $this->_currentObjectInstance;
                // Ecrit le groupe dans la session.
                $this->setSessionStore('nebuleSelectedGroup', $this->_currentGroup);
            }
            unset($cache);
        }
        unset($arg_grp);

        $this->_metrologyInstance->addLog('Find current group ' . $this->_currentGroup, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID du groupe en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentGroup(): string
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
    public function getCurrentGroupInstance(): ?Group
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
        if (Node::checkNID($arg_cvt, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg_cvt)
                || $this->getIoInstance()->checkLinkPresent($arg_cvt)
                || $arg_cvt == '0'
            )
        ) {
            // Ecrit la conversation dans la variable.
            $this->_currentConversation = $arg_cvt;
            $this->_currentConversationInstance = $this->newConversation_DEPRECATED($arg_cvt);
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
                $this->_currentConversationInstance = $this->newConversation_DEPRECATED($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentConversation = '0'; // $this->_currentObject;
                $this->_currentConversationInstance = $this->newConversation_DEPRECATED('0'); // $this->_currentObjectInstance;
                // Ecrit la conversation dans la session.
                $this->setSessionStore('nebuleSelectedConversation', $this->_currentConversation);
            }
            unset($cache);
        }
        unset($arg_cvt);

        $this->_metrologyInstance->addLog('Find current conversation ' . $this->_currentConversation, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID de la conversation en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentConversation(): string
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
    public function getCurrentConversationInstance(): ?Conversation
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
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentCurrency = '0';
            $this->_currentCurrencyInstance = $this->newCurrency_DEPRECATED('0');
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
        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            // Ecrit la monnaie dans la variable.
            $this->_currentCurrency = $arg;
            $this->_currentCurrencyInstance = $this->newCurrency_DEPRECATED($arg);
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
                $this->_currentCurrencyInstance = $this->newCurrency_DEPRECATED($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentCurrency = '0';
                $this->_currentCurrencyInstance = $this->newCurrency_DEPRECATED('0');
                // Ecrit la monnaie dans la session.
                $this->setSessionStore('nebuleSelectedCurrency', $this->_currentCurrency);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrologyInstance->addLog('Find current currency ' . $this->_currentCurrency, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID de la monnaie en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentCurrency(): string
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
    public function getCurrentCurrencyInstance(): ?Currency
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
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentTokenPool = '0';
            $this->_currentTokenPoolInstance = $this->newTokenPool_DEPRECATED('0');
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
        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            // Ecrit le sac de jetons dans la variable.
            $this->_currentTokenPool = $arg;
            $this->_currentTokenPoolInstance = $this->newTokenPool_DEPRECATED($arg);
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
                $this->_currentTokenPoolInstance = $this->newTokenPool_DEPRECATED($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentTokenPool = '0';
                $this->_currentTokenPoolInstance = $this->newTokenPool_DEPRECATED('0');
                // Ecrit le sac de jetons dans la session.
                $this->setSessionStore('nebuleSelectedTokenPool', $this->_currentTokenPool);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrologyInstance->addLog('Find current token pool ' . $this->_currentTokenPool, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID du sac de jetons en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentTokenPool(): string
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
    public function getCurrentTokenPoolInstance(): ?TokenPool
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
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentToken = '0';
            $this->_currentTokenInstance = $this->newToken_DEPRECATED('0');
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
        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            // Ecrit le jeton dans la variable.
            $this->_currentToken = $arg;
            $this->_currentTokenInstance = $this->newToken_DEPRECATED($arg);
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
                $this->_currentTokenInstance = $this->newToken_DEPRECATED($cache);
            } else // Sinon désactive le cache.
            {
                $this->_currentToken = '0';
                $this->_currentTokenInstance = $this->newToken_DEPRECATED('0');
                // Ecrit le jeton dans la session.
                $this->setSessionStore('nebuleSelectedToken', $this->_currentToken);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrologyInstance->addLog('Find current token ' . $this->_currentToken, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
    }

    /**
     * Donne l'ID du jeton en cours.
     *
     * Si non définit, l'ID est à 0.
     *
     * @return string
     */
    public function getCurrentToken(): string
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
    public function getCurrentTokenInstance(): ?Token
    {
        return $this->_currentTokenInstance;
    }


    /**
     * Calculate the level of usability of entities.
     *
     * @return integer
     */
    public function checkInstance(): int
    {
        if (!$this->_puppetmasterInstance instanceof Entity) return 1;
        if ($this->_puppetmasterInstance->getID() == '0') return 2;
        if ($this->_puppetmasterInstance->getID() != $this->_configurationInstance->getOptionUntyped('puppetmaster')) return 3; // TODO à retirer
        // Vérifie que le maître de la sécurité est une entité et a été trouvé.
        if (!is_array($this->_securityAuthoritiesInstance)) return 11;
        foreach ($this->_securityAuthoritiesInstance as $instance)
        {
            if (!$instance instanceof Entity) return 12;
            if ($instance->getID() == '0') return 13;
        }
        // Vérifie que le maître du code est une entité et a été trouvé.
        if (!is_array($this->_codeAuthoritiesInstance)) return 21;
        foreach ($this->_codeAuthoritiesInstance as $instance)
        {
            if (!$instance instanceof Entity) return 22;
            if ($instance->getID() == '0') return 23;
        }
        // Vérifie que le maître de l'annuaire est une entité et a été trouvé.
        if (!is_array($this->_directoryAuthoritiesInstance)) return 31;
        foreach ($this->_directoryAuthoritiesInstance as $instance)
        {
            if (!$instance instanceof Entity) return 32;
            if ($instance->getID() == '0') return 33;
        }
        // Vérifie que le maître du temps est une entité et a été trouvé.
        if (!is_array($this->_timeAuthoritiesInstance)) return 41;
        foreach ($this->_timeAuthoritiesInstance as $instance)
        {
            if (!$instance instanceof Entity) return 42;
            if ($instance->getID() == '0') return 43;
        }

        // Vérifie que l'entité de l'instance nebule est une entité et a été trouvée.
        if (!$this->_instanceEntityInstance instanceof Entity) return 51;
        if ($this->_instanceEntityInstance->getID() == '0') return 52;

        // Vérifie qu'une entité courante existe et est une entité.
        if (!$this->_currentEntityInstance instanceof Entity) return 61;
        if ($this->_currentEntityInstance->getID() == '0') return 62;

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
     * L'ID du maître de la sécurité.
     */
    private $_securityAuthorities = array();

    /**
     * L'instance du maître de la sécurité.
     */
    private $_securityAuthoritiesInstance = array();

    /**
     * L'ID du maître du code.
     */
    private $_codeAuthorities = array();

    /**
     * L'instance du maître du code.
     */
    private $_codeAuthoritiesInstance = array();

    /**
     * Le maître de l'annuaire.
     */
    private $_directoryAuthorities = array();

    /**
     * L'instance du maître de l'annuaire.
     */
    private $_directoryAuthoritiesInstance = array();

    /**
     * Le maître du temps.
     */
    private $_timeAuthorities = array();

    /**
     * L'instance du maître du temps.
     */
    private $_timeAuthoritiesInstance = array();

    /**
     * Récupération du maître.
     *
     * Définit par une option ou en dur dans une constante.
     *
     * @return void
     */
    private function _findPuppetmaster()
    {
        $this->_puppetmaster = $this->_configurationInstance->getOptionUntyped('puppetmaster');
        $this->_puppetmasterInstance = $this->_cacheInstance->newNode($this->_puppetmaster, Cache::TYPE_ENTITY);
        $this->_metrologyInstance->addLog('Find puppetmaster ' . $this->_puppetmaster, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '88848d09');
    }



    /**
     * Find all global authorities entities after the puppetmaster.
     *
     * @return void
     */
    private function _findGlobalAuthorities()
    {
        $this->_findEntityByType(self::LIB_RID_SECURITY_AUTHORITY,
            $this->_securityAuthorities,
            $this->_securityAuthoritiesInstance,
            'security');
        $this->_findEntityByType(self::LIB_RID_CODE_AUTHORITY,
            $this->_codeAuthorities,
            $this->_codeAuthoritiesInstance,
            'code');
        $this->_findEntityByType(self::LIB_RID_DIRECTORY_AUTHORITY,
            $this->_directoryAuthorities,
            $this->_directoryAuthoritiesInstance,
            'directory');
        $this->_findEntityByType(self::LIB_RID_TIME_AUTHORITY,
            $this->_timeAuthorities,
            $this->_timeAuthoritiesInstance,
            'time');
    }

    /**
     * Find authorities by their roles.
     * Only follow puppetmaster links.
     *
     * @param string $rid
     * @param array  $listEID
     * @param array  $listInstances
     * @param string $name
     * @return void
     */
    private function _findEntityByType(string $rid, array &$listEID, array &$listInstances, string $name): void
    {
        $instance = $this->getCacheInstance()->newNode($rid, Cache::TYPE_NODE);
        $list = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $rid,
            'bl/rl/nid3' => $rid,
            'bs/rs1/eid' => $this->_puppetmaster,
            );
        $instance->getLinks($list, $filter, null);

        if (sizeof($list) == 0)
        {
            $listEID[$this->_puppetmaster] = $this->_puppetmaster;
            $listInstances[$this->_puppetmaster] = $this->_puppetmasterInstance;
            return;
        }

        foreach ($list as $item)
        {
            $eid = $item->getParsed()['bl/rl/nid2'];
            $instance = $this->getCacheInstance()->newNode($eid, Cache::TYPE_ENTITY);
            $listEID[$eid] = $eid;
            $listInstances[$eid] = $instance;
            $this->_metrologyInstance->addLog('Find ' . $name . ' authority ' . $eid, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'e6f75b5e');
        }
    }

    /**
     * Donne l'ID du maître du tout.
     *
     * @return string
     */
    public function getPuppetmaster(): string
    {
        return $this->_puppetmaster;
    }

    /**
     * Donne l'instance du maître du tout.
     *
     * @return Entity
     */
    public function getPuppetmasterInstance(): Entity
    {
        return $this->_puppetmasterInstance;
    }

    public function getSecurityAuthorities(): array
    {
        return $this->_securityAuthorities;
    }

    public function getSecurityAuthoritiesInstance(): array
    {
        return $this->_securityAuthoritiesInstance;
    }

    public function getCodeAuthorities(): array
    {
        return $this->_codeAuthorities;
    }

    public function getCodeAuthoritiesInstance(): array
    {
        return $this->_codeAuthoritiesInstance;
    }

    public function getDirectoryAuthorities(): array
    {
        return $this->_directoryAuthorities;
    }

    public function getDirectoryAuthoritiesInstance(): array
    {
        return $this->_directoryAuthoritiesInstance;
    }

    public function getTimeAuthorities(): array
    {
        return $this->_timeAuthorities;
    }

    public function getTimeAuthoritiesInstance(): array
    {
        return $this->_timeAuthoritiesInstance;
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
     * Liste des instances des autorités locales primaires.
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
        foreach ($this->_securityAuthorities as $item)
        {
            $this->_authorities[$item] = $item;
            $this->_authoritiesInstances[$item] = $this->_securityAuthoritiesInstance[$item];
            $this->_specialEntities[$item] = $item;
        }
        foreach ($this->_codeAuthorities as $item)
        {
            $this->_authorities[$item] = $item;
            $this->_authoritiesInstances[$item] = $this->_codeAuthoritiesInstance[$item];
            $this->_specialEntities[$item] = $item;
            $this->_localAuthorities[$item] = $item;
            $this->_localAuthoritiesInstances[$item] =$this->_codeAuthoritiesInstance[$item];
            $this->_localAuthoritiesSigners[$item] = $this->_puppetmaster;
        }
        foreach ($this->_directoryAuthorities as $item)
        {
            $this->_specialEntities[$item] = $item;
        }
        foreach ($this->_timeAuthorities as $item)
        {
            $this->_specialEntities[$item] = $item;
        }
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
            $this->_permitInstanceEntityAsAuthority = $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority');
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

            $this->_metrologyInstance->addLog('Add instance entity as authority', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
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
            $this->_permitDefaultEntityAsAuthority = $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority');
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

            $this->_metrologyInstance->addLog('Add default entity as authority', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
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
        if (!$this->_configurationInstance->getOptionAsBoolean('permitLocalSecondaryAuthorities'))
            return;

        $refAuthority = $this->getNIDfromData(self::REFERENCE_NEBULE_OBJET_ENTITE_AUTORITE_LOCALE);

        // Liste les liens de l'entité instance du serveur.
        $list = array();
        if ($this->_permitInstanceEntityAsAuthority) {
            $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $refAuthority,
                'bl/rl/nid2' => $this->_instanceEntity,
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
                'bs/rs1/eid' => $this->_instanceEntity,
            );
            $this->_instanceEntityInstance->getLinks($list, $filter, null);
        }

        // Liste les liens de l'entité par défaut.
        if ($this->_permitDefaultEntityAsAuthority) {
            $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $refAuthority,
                'bl/rl/nid2' => $this->_instanceEntity,
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
                'bs/rs1/eid' => $this->_defaultEntity,
            );
            $this->_instanceEntityInstance->getLinks($list, $filter, null);
        }

        foreach ($list as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            $instance = $this->newEntity_DEPRECATED($nid);
            $this->_localAuthorities[$nid] = $nid;
            $this->_localAuthoritiesInstances[$nid] = $instance;
            $this->_specialEntities[$nid] = $nid;
            $this->_localAuthoritiesSigners[$nid] = $link->getParsed()['bs/rs1/eid'];
            $this->_authorities[$nid] = $nid;
            $this->_authoritiesInstances[$nid] = $instance;
        }
    }

    /**
     * Lit la liste des ID des autorités.
     *
     * @return array:string
     */
    public function getAuthorities(): array
    {
        return $this->_authorities;
    }

    /**
     * Lit la liste des instance des autorités.
     *
     * @return array:Entity
     */
    public function getAuthoritiesInstance(): array
    {
        return $this->_authoritiesInstances;
    }

    /**
     * Lit la liste des ID des autorités locales.
     *
     * @return array:string
     */
    public function getLocalAuthorities(): array
    {
        return $this->_localAuthorities;
    }

    /**
     * Lit la liste des instance des autorités locales.
     *
     * @return array:Entity
     */
    public function getLocalAuthoritiesInstance(): array
    {
        return $this->_localAuthoritiesInstances;
    }

    /**
     * Lit la liste des autorités locales déclarants des autorités locales.
     *
     * @return array:string
     */
    public function getLocalAuthoritiesSigners(): array
    {
        return $this->_localAuthoritiesSigners;
    }

    /**
     * Lit la liste des ID des autorités locales.
     *
     * @return array:string
     */
    public function getLocalPrimaryAuthorities(): array
    {
        return $this->_localPrimaryAuthorities;
    }

    /**
     * Lit la liste des instance des autorités locales.
     *
     * @return array:Entity
     */
    public function getLocalPrimaryAuthoritiesInstance(): array
    {
        return $this->_localPrimaryAuthoritiesInstances;
    }

    /**
     * Lit la liste des ID des entités avec des rôles.
     *
     * @return array:string
     */
    public function getSpecialEntities(): array
    {
        return $this->_specialEntities;
    }

    /**
     * Retourne si l'entité est autorité locale.
     *
     * @param Entity|string $entity
     * @return boolean
     */
    public function getIsLocalAuthority($entity): bool
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
        if (!$this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities'))
            return;

        $this->_permitInstanceEntityAsRecovery = $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsRecovery');

        if ($this->_permitInstanceEntityAsRecovery) {
            $this->_recoveryEntities[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_recoveryEntitiesInstances[$this->_instanceEntity] = $this->_instanceEntityInstance;
            $this->_recoveryEntitiesSigners[$this->_instanceEntity] = '0';

            $this->_metrologyInstance->addLog('Add instance entity as recovery', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
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
        if (!$this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities'))
            return;

        $this->_permitDefaultEntityAsRecovery = $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsRecovery');

        if ($this->_permitDefaultEntityAsRecovery) {
            $this->_recoveryEntities[$this->_defaultEntity] = $this->_defaultEntity;
            $this->_recoveryEntitiesInstances[$this->_defaultEntity] = $this->_defaultEntityInstance;
            $this->_recoveryEntitiesSigners[$this->_defaultEntity] = '0';

            $this->_metrologyInstance->addLog('Add default entity as recovery', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        }
    }

    /**
     * Recherche les entités de recouvrement valables.
     *
     * @return void
     */
    private function _findRecoveryEntities(): void
    {
        $this->_recoveryEntities = array();
        $this->_recoveryEntitiesInstances = array();

        // Vérifie si les entités de recouvrement sont autorisées.
        if (!$this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities'))
            return;

        $refRecovery = $this->getNIDfromData(self::REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT);

        // Liste les liens de l'entité instance du serveur..
        $list = array();
        if ($this->_permitInstanceEntityAsAuthority) {
            $list = $this->_instanceEntityInstance->getLinksOnFields(
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
            $instance = $this->newEntity_DEPRECATED($target);
            $this->_recoveryEntities[$target] = $target;
            $this->_recoveryEntitiesInstances[$target] = $instance;
            $this->_recoveryEntitiesSigners[$target] = $link->getHashSigner();
        }

        // Liste les liens de l'entité instance du serveur..
        $list = array();
        if ($this->_permitDefaultEntityAsAuthority) {
            $list = $this->_instanceEntityInstance->getLinksOnFields(
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
            $instance = $this->newEntity_DEPRECATED($target);
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
    public function getRecoveryEntities(): array
    {
        return $this->_recoveryEntities;
    }

    /**
     * Lit la liste des instance des entités de recouvrement.
     *
     * @return array:Entity
     */
    public function getRecoveryEntitiesInstance(): array
    {
        return $this->_recoveryEntitiesInstances;
    }

    /**
     * Lit la liste des autorités locales déclarants les entités de recouvrement.
     *
     * @return array:string
     */
    public function getRecoveryEntitiesSigners(): array
    {
        return $this->_recoveryEntitiesSigners;
    }

    /**
     * Retourne si l'entité est entité de recouvrement.
     *
     * @param Entity|string $entity
     * @return boolean
     */
    public function getIsRecoveryEntity($entity): bool
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
     * Retourne la liste des instances de toutes les entités loacles.
     *
     * @return array:Entity
     */
    public function getListEntitiesInstances(): array
    {
        $hashType = $this->getNIDfromData(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $hashEntity = $this->getNIDfromData('application/x-pem-file');
        $hashEntityObject = $this->newObject($hashEntity);

        // Liste les liens.
        $links = $hashEntityObject->getLinksOnFields('', '', 'l', '', $hashEntity, $hashType);
        unset($hashType, $hashEntity, $hashEntityObject);

        // Filtre les entités sur le contenu de l'objet de la clé publique. @todo
        $result = array();
        $id = '';
        $instance = null;
        foreach ($links as $link) {
            $id = $link->getHashSource();
            $instance = $this->newEntity_DEPRECATED($id);
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
    public function getListEntitiesID(): array
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
     * @return void
     */
    private function _findModeRescue(): void
    {
        if ($this->_configurationInstance->getOptionUntyped('modeRescue')
            || ($this->_configurationInstance->getOptionAsBoolean('permitOnlineRescue')
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
    public function getModeRescue(): bool
    {
        return $this->_modeRescue;
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
     * @return void
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
            $this->_metrologyInstance->addLog('Ask flush cache', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
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
     * FIXME
     *
     * @param string  $text
     * @param boolean $protect
     * @param bool    $obfuscate
     * @return false|string
     */
    public function createTextAsObject(string &$text, bool $protect = false, bool $obfuscate = false)
    {
        // Vérifie que l'écriture est autorisée.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_currentEntityUnlocked
            && strlen($text) != 0
        ) {
            // Calcule l'ID de l'objet à créer.
            $id = $this->getNIDfromData($text);

            // Vérifie si l'ID n'existe pas déjà.
            if ($this->_ioInstance->checkObjectPresent($id))
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
            $target = $this->getNIDfromData('text/plain');
            $meta = $this->getNIDfromData(self::REFERENCE_NEBULE_OBJET_TYPE);
            $link = '_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new Link($this->_nebuleInstance, $link);

            // Signe le lien.
            $newLink->sign();

            // Si besoin, obfuscation du lien.
            if ($obfuscate)
                $newLink->setObfuscate();
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
     * Toutes les références et propriétés sont hachées avec un algorithme fixe.
     * $entity Permet de ne sélectionner que les liens générés par une entité.
     *
     * @param string|Node   $type
     * @param string        $socialClass
     * @param string|Entity $entity
     * @return array:Link
     * @todo ajouter un filtre sur le type mime des objets.
     */
    public function getListLinksByType($type, $entity = '', string $socialClass = ''): array
    {
        /**
         * Résultat de la recherche de liens à retourner.
         * @var array:Link $result
         */
        $result = array();

        /**
         * Empreinte du type d'objet à rechercher.
         */
        $hashType = '';

        /**
         * Empreinte de l'entité pour la recherche.
         */
        $hashEntity = '';

        // Si le type est une instance, récupère l'ID de l'instance de l'objet du type.
        if (is_a($type, 'Node')) {
            $hashType = $type->getID();
        } else {
            // Si le type est un ID, l'utilise directement. Sinon calcul l'empreinte du type.
            if (Node::checkNID($type))
                $hashType = $type;
            else
                $hashType = $this->getNIDfromData($type, self::REFERENCE_CRYPTO_HASH_ALGORITHM);
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
            // Si l'entité est un ID, l'utilise directement. Sinon calcul l'empreinte de l'entité.
            if (Node::checkNID($entity)
                && $this->getIoInstance()->checkLinkPresent($entity)
                && $this->getIoInstance()->checkObjectPresent($entity)
            )
                $hashEntity = $entity;
            else
                $hashEntity = '';
        }

        // Lit les liens de l'objet de référence.
        $result = $type->getLinksOnFields(
            $hashEntity,
            '',
            'l',
            '',
            $hashType,
            $this->getNIDfromData(self::REFERENCE_NEBULE_OBJET_TYPE, self::REFERENCE_CRYPTO_HASH_ALGORITHM)
        );

        // Fait un tri par pertinance sociale.
        $this->_socialInstance->arraySocialFilter($result, $socialClass);

        // retourne le résultat.
        return $result;
    }

    /**
     * Recherche des ID d'objets par rapport à une référence qui est le type d'objet.
     * $entity Permet de ne sélectionner que les liens générés par une entité.
     *
     * @param string|Node   $type
     * @param string        $socialClass
     * @param string|Entity $entity
     * @return array:Link
     */
    public function getListIdByType($type = '', $entity = '', string $socialClass = ''): array
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
     * $entity Permet de ne sélectionner que les groupes générés par une entité.
     *
     * @param string        $socialClass
     * @param string|Entity $entity
     * @return array:Link
     */
    public function getListGroupsLinks($entity = '', string $socialClass = ''): array
    {
        return $this->getListLinksByType(self::REFERENCE_NEBULE_OBJET_GROUPE, $entity, $socialClass);
    }

    /**
     * Extrait la liste des ID des groupes d'objets.
     * $entity Permet de ne sélectionner que les groupes générés par une entité.
     *
     * @param string|entity $entity
     * @param string        $socialClass
     * @return array
     */
    public function getListGroupsID($entity = '', string $socialClass = ''): array
    {
        return $this->getListIdByType(self::REFERENCE_NEBULE_OBJET_GROUPE, $entity, $socialClass);
    }

    /**
     * Extrait la liste des liens définissant les conversations.
     * Précalcul le hash de l'objet définissant une conversation.
     * Extrait l'ID de l'entité, si demandé.
     * Liste les liens définissants les différentes conversations.
     * Retourne la liste.
     * $entity : Permet de ne sélectionner que les conversations générés par une entité.
     *
     * @param string|entity $entity
     * @param string        $socialClass
     * @return array
     */
    public function getListConversationsLinks($entity = '', string $socialClass = ''): array
    {
        return $this->getListLinksByType(self::REFERENCE_NEBULE_OBJET_CONVERSATION, $entity, $socialClass);
    }

    /**
     * Extrait la liste des ID des conversations.
     * Géré comme des groupes d'objets.
     * $entity Permet de ne sélectionner que les conversations générées par une entité.
     *
     * @param string|entity $entity
     * @param string        $socialClass
     * @return array
     */
    public function getListConversationsID($entity = '', string $socialClass = ''): array
    {
        return $this->getListIdByType(self::REFERENCE_NEBULE_OBJET_CONVERSATION, $entity, $socialClass);
    }



    /**
     * Extrait l'argument pour continuer un affichage en ligne à partir d'un objet particulier.
     * Retourne tout type de chaine de texte nécessaire à l'affichage
     * ou une chaine vide si pas d'argument valable trouvé.
     *
     * @return string
     */
    public function getDisplayNextObject(): string
    {
        $this->_metrologyInstance->addLog('Extract display next object', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        $arg = trim(' ' . filter_input(INPUT_GET, Displays::DEFAULT_NEXT_COMMAND, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            return $arg;
        return '';
    }



    /**
     * Détermine si c'est un objet.
     * Retourne une instance appropriée en fonction du type d'objet.
     *
     * @param string|Node|Conversation|Group|Entity|Currency|TokenPool|Token|Wallet $nid
     * @return Node|Conversation|Group|Entity|Currency|TokenPool|Token|Wallet
     */
    public function convertIdToTypedObjectInstance($nid)
    {
        if (is_a($nid, 'Node')
            || is_a($nid, 'Nebule\Library\Node')
            || is_a($nid, 'Group')
            || is_a($nid, 'Nebule\Library\Group')
            || is_a($nid, 'Entity')
            || is_a($nid, 'Nebule\Library\Entity')
            || is_a($nid, 'Conversation')
            || is_a($nid, 'Nebule\Library\Conversation')
            || is_a($nid, 'Currency')
            || is_a($nid, 'Nebule\Library\Currency')
            || is_a($nid, 'TokenPool')
            || is_a($nid, 'Nebule\Library\TokenPool')
            || is_a($nid, 'Token')
            || is_a($nid, 'Nebule\Library\Token')
            || is_a($nid, 'Wallet')
            || is_a($nid, 'Nebule\Library\Wallet')
        )
            return $nid;

        if (!is_string($nid))
            $nid = '0';

        $social = 'all';

        if ($nid == '0'
            || $nid == ''
        )
            $instance = $this->_nebuleInstance->newObject('0');
        else {
            $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_NODE);
            if ($instance->getIsEntity($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_ENTITY);
            elseif ($instance->getIsWallet($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_WALLET);
            elseif ($instance->getIsToken($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_TOKEN);
            elseif ($instance->getIsTokenPool($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_TOKENPOOL);
            elseif ($instance->getIsCurrency($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_CURRENCY);
            elseif ($instance->getIsConversation($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_CONVERSATION);
            elseif ($instance->getIsGroup($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_GROUP);
            else {
                $protected = $instance->getMarkProtected();
                if ($protected)
                    $instance = $this->_cacheInstance->newNode($instance->getID(), Cache::TYPE_NODE);
                if ($instance->getType('all') == nebule::REFERENCE_OBJECT_ENTITY)
                    $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_ENTITY);
            }
        }

        return $instance;
    }
}
