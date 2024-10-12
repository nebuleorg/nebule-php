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
    const NEBULE_LICENCE_DATE = '2010-2023';
    const NEBULE_FUNCTION_VERSION = '020241012';
    const NEBULE_ENVIRONMENT_FILE = 'c'; // Into folder /
    const NEBULE_BOOTSTRAP_FILE = 'index.php'; // Into folder /
    const NEBULE_LOCAL_ENTITY_FILE = 'e'; // Into folder /
    const NEBULE_LOCAL_OBJECTS_FOLDER = 'o'; // Into folder /
    const NEBULE_LOCAL_LINKS_FOLDER = 'l'; // Into folder /
    const NEBULE_LOCAL_HISTORY_FILE = 'f'; // Into folder /l/
    const PUPPETMASTER_URL = 'http://puppetmaster.nebule.org';
    const SECURITY_MASTER_URL = 'http://security.master.nebule.org';
    const CODE_MASTER_URL = 'http://code.master.nebule.org';

    // Les commandes.
    const COMMAND_SWITCH_APPLICATION = 'a';
    const COMMAND_FLUSH = 'f';
    const COMMAND_RESCUE = 'r';
    const COMMAND_AUTH_ENTITY_MOD = 'auth';
    const COMMAND_AUTH_ENTITY_INFO = 'info';
    const COMMAND_AUTH_ENTITY_LOGIN = 'login';
    const COMMAND_AUTH_ENTITY_LOGOUT = 'logout';
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
        'nebule/objet/interface/web/php/applications/modules/traduction',
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

    private $_loadingStatus = false;
    private ?nebule $_nebuleInstance = null;
    private ?Metrology $_metrologyInstance = null;
    private ?Configuration $_configurationInstance = null;
    private ?Rescue $_rescueInstance = null;
    private ?Authorities $_authoritiesInstance = null;
    private ?Entities $_entitiesInstance = null;
    private ?Recovery $_recoveryInstance = null;
    private ?Cache $_cacheInstance = null;
    private ?Session $_sessionInstance = null;
    private ?Ticketing $_ticketingInstance = null;
    private ?ioInterface $_ioInstance = null;
    private ?CryptoInterface $_cryptoInstance = null;
    private ?SocialInterface $_socialInstance = null;



    public function __construct()
    {
        global $metrologyStartTime;
        syslog(LOG_INFO, 'LogT=' . sprintf('%01.6f', microtime(true) - $metrologyStartTime) . ' LogL="info" LogI="1452ed72" LogF="include nebule library" LogM="Loading nebule library"');

        $this->_initialisation();
    }

    public function __wakeup()
    {
        global $metrologyStartTime;
        syslog(LOG_INFO, 'LogT=' . sprintf('%01.6f', microtime(true) - $metrologyStartTime) . ' LogL="info" LogI="2d9358d5" LogF="include nebule library" LogM="Reloading nebule library"');

        $this->_initialisation();
    }

    public function __destruct()
    {
        $this->_cacheInstance->saveCacheOnSessionBuffer();
        return true;
    }

    public function __toString()
    {
        return self::NEBULE_LICENCE_NAME;
    }

    private function _initialisation(): void
    {
        global $nebuleInstance;

        $this->_nebuleInstance = $this;
        $nebuleInstance = $this;
        $this->_metrologyInstance = new Metrology($this);
        $this->_configurationInstance = new Configuration($this);
        $this->_setEnvironmentInstances();
        $this->_rescueInstance = new Rescue($this);
        $this->_setEnvironmentInstances();
        $this->_sessionInstance = new Session($this);
        $this->_setEnvironmentInstances();
        $this->_cacheInstance = new Cache($this);
        $this->_setEnvironmentInstances();
        $this->_ioInstance = new io($this);
        $this->_setEnvironmentInstances();
        $this->_cryptoInstance = new Crypto($this);
        $this->_setEnvironmentInstances();

        if (!$this->_nebuleCheckEnvironment())
            $this->_nebuleInitEnvironment();

        $this->_authoritiesInstance = new Authorities($this);
        $this->_setEnvironmentInstances();
        $this->_recoveryInstance = new Recovery($this);
        $this->_setEnvironmentInstances();
        $this->_entitiesInstance = new Entities($this);
        $this->_setEnvironmentInstances();
        $this->_ticketingInstance = new Ticketing($this);
        $this->_setEnvironmentInstances();
        //$this->_ioInstance = new io($this);
        //$this->_setEnvironmentInstances();
        //$this->_cryptoInstance = new Crypto($this);
        //$this->_setEnvironmentInstances();
        $this->_socialInstance = new Social($this);
        $this->_setEnvironmentInstances();

        $this->_metrologyInstance->addLog('First step init nebule instance', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '64154189');

        $this->_configurationInstance->setPermitOptionsByLinks(true);
        $this->_configurationInstance->flushCache();

        //$this->_findFlushCache();

        $this->_getSubordinationEntity();
        $this->_cacheInstance->readCacheOnSessionBuffer();

        $this->_checkWriteableIO();

        //$this->_findPuppetmaster(); TODO cleaning...
        //$this->_findGlobalAuthorities();
        //$this->_findLocalAuthorities();
        //$this->_findInstanceEntity();
        //$this->_findDefaultEntity();
        //$this->_addInstanceEntityAsAuthorities();
        //$this->_addDefaultEntityAsAuthorities();
        //$this->_getCurrentEntity();
        //$this->_addLocalAuthorities();
        //$this->_findRecoveryEntities();
        //$this->_addInstanceEntityAsRecovery();
        //$this->_addDefaultEntityAsRecovery();

        $this->_findCurrentObjet();
        //$this->_getCurrentEntityPrivateKey();
        //$this->_getCurrentEntityPassword();
        $this->_findCurrentGroup();
        $this->_findCurrentConversation();
        $this->_findCurrentCurrency();
        $this->_findCurrentTokenPool();
        $this->_findCurrentToken();

        $this->_loadingStatus = true;
        $this->_metrologyInstance->addLog('End init nebule instance', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '474676ed');
    }

    /**
     * Reload all instances in all library components.
     */
    private function _setEnvironmentInstances(): void
    {
        $this->_metrologyInstance->setEnvironment();
        $this->_configurationInstance->setEnvironment();
        if ($this->_rescueInstance !== null)
            $this->_rescueInstance->setEnvironment();
        $this->_authoritiesInstance?->setEnvironment();
        $this->_entitiesInstance?->setEnvironment();
        $this->_recoveryInstance?->setEnvironment();
        $this->_cacheInstance?->setEnvironment();
        $this->_sessionInstance?->setEnvironment();
        $this->_ticketingInstance?->setEnvironment();
        $this->_ioInstance?->setEnvironment();
        $this->_cryptoInstance?->setEnvironment();
        $this->_socialInstance?->setEnvironment();
    }

    public function getLoadingStatus(): bool
    {
        return $this->_loadingStatus;
    }


    public function getMetrologyInstance(): ?Metrology
    {
        return $this->_metrologyInstance;
    }

    public function getConfigurationInstance(): ?Configuration
    {
        return $this->_configurationInstance;
    }

    public function getRescueInstance(): ?Rescue
    {
        return $this->_rescueInstance;
    }

    public function getAuthoritiesInstance(): ?Authorities
    {
        return $this->_authoritiesInstance;
    }

    public function getEntitiesInstance(): ?Entities
    {
        return $this->_entitiesInstance;
    }

    public function getRecoveryInstance(): ?Recovery
    {
        return $this->_recoveryInstance;
    }

    public function getCacheInstance(): ?Cache
    {
        return $this->_cacheInstance;
    }

    public function getSessionInstance(): ?Session
    {
        return $this->_sessionInstance;
    }

    public function getTicketingInstance(): ?Ticketing
    {
        return $this->_ticketingInstance;
    }

    public function getIoInstance(): ?ioInterface
    {
        return $this->_ioInstance;
    }

    public function getCryptoInstance(): ?CryptoInterface
    {
        return $this->_cryptoInstance;
    }

    public function getSocialInstance(): ?SocialInterface
    {
        return $this->_socialInstance;
    }



    /**
     * Entité de subordination des options de l'entité en cours.
     * Par défaut vide.
     *
     * @var node|null
     */
    private ?node $_subordinationEntity = null;

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
        $this->_metrologyInstance->addLog('Get subordination entity = ' . $this->_subordinationEntity, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2300b439');
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
            $this->_metrologyInstance->addLog('objects ro not rw', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '865076e1');
        if (!$this->_configurationInstance->getOptionAsBoolean('permitWriteLink'))
            $this->_metrologyInstance->addLog('links ro not rw', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'f2e738b1');
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



    private string $_currentObject = '';
    private ?Node $_currentObjectInstance = null;

    private function _findCurrentObjet()
    {
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
            $this->_currentObjectInstance =$this->_cacheInstance->newNode($arg_obj);
            // Ecrit l'objet dans la session.
            $this->_sessionInstance->setSessionStore('nebuleSelectedObject', $arg_obj);
        } else {
            // Sinon vérifie si une valeur n'est pas mémorisée dans la session.
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedObject');
            // Si il existe une variable de session pour l'objet en cours, la lit.
            if ($cache !== false && $cache != '') {
                $this->_currentObject = $cache;
                $this->_currentObjectInstance =$this->_cacheInstance->newNode($cache);
            } else // Sinon selectionne l'entite courante par défaut.
            {
                $this->_currentObject = $this->_entitiesInstance->getCurrentEntityID();
                $this->_currentObjectInstance =$this->_cacheInstance->newNode($this->_entitiesInstance->getCurrentEntityID());
                $this->_sessionInstance->setSessionStore('nebuleSelectedObject', $this->_entitiesInstance->getCurrentEntityID());
            }
            unset($cache);
        }
        unset($arg_obj);

        $this->_metrologyInstance->addLog('Find current object ' . $this->_currentObject, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '7b4f89ef');
        $this->_currentObjectInstance->getMarkProtected();
    }

    public function getCurrentObject(): string
    {
        return $this->_currentObject;
    }

    public function getCurrentObjectInstance(): ?Node
    {
        return $this->_currentObjectInstance;
    }



    private string $_instanceEntity_DEPRECATED = '';
    private ?Entity $_instanceEntityInstance_DEPRECATED = null;



    private string $_defaultEntity_DEPRECATED = '';
    private ?Entity $_defaultEntityInstance_DEPRECATED = null;



    private string $_currentEntityID = '';
    private ?Entity $_currentEntityInstance = null;
    private string $_currentEntityPrivateKey = '';
    private ?Node $_currentEntityPrivateKeyInstance = null;
    private bool $_currentEntityIsUnlocked = false;



    private array $_listEntitiesUnlocked = array();
    private array $_listEntitiesUnlockedInstances = array();

    public function getListEntitiesUnlocked(): array
    {
        return $this->_listEntitiesUnlocked;
    }

    public function getListEntitiesUnlockedInstances(): array
    {
        return $this->_listEntitiesUnlockedInstances;
    }

    public function addListEntitiesUnlocked(Entity $entity): void
    {
        if ($entity->getID() == '0')
            return;
        $eid = $entity->getID();

        $this->_listEntitiesUnlocked[$eid] = $eid;
        $this->_listEntitiesUnlockedInstances[$eid] = $entity;
    }

    public function removeListEntitiesUnlocked(Entity $entity)
    {
        unset($this->_listEntitiesUnlocked[$entity->getID()]);
        unset($this->_listEntitiesUnlockedInstances[$entity->getID()]);
    }

    public function flushListEntitiesUnlocked()
    {
        $this->_listEntitiesUnlocked = array();
        $this->_listEntitiesUnlockedInstances = array();
    }


    private string $_currentGroupID = '';
    private ?Group $_currentGroupInstance = null;

    private function _findCurrentGroup()
    {
        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_GROUP))
            $arg_grp = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg_grp = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg_grp, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg_grp)
                || $this->getIoInstance()->checkLinkPresent($arg_grp)
                || $arg_grp == '0'
            )
        ) {
            $this->_currentGroupID = $arg_grp;
            $this->_currentGroupInstance = $this->_cacheInstance->newGroup($arg_grp);
            $this->_sessionInstance->setSessionStore('nebuleSelectedGroup', $arg_grp);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedGroup');
            if ($cache !== false && $cache != '') {
                $this->_currentGroupID = $cache;
                $this->_currentGroupInstance = $this->_cacheInstance->newGroup($cache);
            } else
            {
                $this->_currentGroupID = '0';
                $this->_currentGroupInstance = $this->_cacheInstance->newGroup('0');
                $this->_sessionInstance->setSessionStore('nebuleSelectedGroup', $this->_currentGroupID);
            }
            unset($cache);
        }
        unset($arg_grp);

        $this->_metrologyInstance->addLog('Find current group ' . $this->_currentGroupID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'adca3827');
    }

    public function getCurrentGroupID(): string
    {
        return $this->_currentGroupID;
    }

    public function getCurrentGroupInstance(): ?Group
    {
        return $this->_currentGroupInstance;
    }



    private string $_currentConversationID = '';
    private ?Conversation $_currentConversationInstance = null;

    private function _findCurrentConversation()
    {
        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_CONVERSATION))
            $arg_cvt = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg_cvt = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg_cvt, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg_cvt)
                || $this->getIoInstance()->checkLinkPresent($arg_cvt)
                || $arg_cvt == '0'
            )
        ) {
            $this->_currentConversationID = $arg_cvt;
            $this->_currentConversationInstance = $this->_cacheInstance->newConversation($arg_cvt);
            $this->_sessionInstance->setSessionStore('nebuleSelectedConversation', $arg_cvt);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedConversation');
            if ($cache !== false && $cache != '') {
                $this->_currentConversationID = $cache;
                $this->_currentConversationInstance = $this->_cacheInstance->newConversation($cache);
            } else {
                $this->_currentConversationID = '0';
                $this->_currentConversationInstance = $this->_cacheInstance->newConversation('0');
                $this->_sessionInstance->setSessionStore('nebuleSelectedConversation', $this->_currentConversationID);
            }
            unset($cache);
        }
        unset($arg_cvt);

        $this->_metrologyInstance->addLog('Find current conversation ' . $this->_currentConversationID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'adf0b5df');
    }

    public function getCurrentConversationID(): string
    {
        return $this->_currentConversationID;
    }

    public function getCurrentConversationInstance(): ?Conversation
    {
        return $this->_currentConversationInstance;
    }



    private string $_currentCurrencyID = '';
    private ?Currency $_currentCurrencyInstance = null;

    private function _findCurrentCurrency()
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentCurrencyID = '0';
            $this->_currentCurrencyInstance = $this->_cacheInstance->newCurrency('0');
            $this->_sessionInstance->setSessionStore('nebuleSelectedCurrency', $this->_currentCurrencyID);
            return;
        }

        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_CURRENCY))
            $arg = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_CURRENCY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_CURRENCY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentCurrencyID = $arg;
            $this->_currentCurrencyInstance = $this->_cacheInstance->newCurrency($arg);
            $this->_sessionInstance->setSessionStore('nebuleSelectedCurrency', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedCurrency');
            if ($cache !== false && $cache != '') {
                $this->_currentCurrencyID = $cache;
                $this->_currentCurrencyInstance = $this->_cacheInstance->newCurrency($cache);
            } else {
                $this->_currentCurrencyID = '0';
                $this->_currentCurrencyInstance = $this->_cacheInstance->newCurrency('0');
                $this->_sessionInstance->setSessionStore('nebuleSelectedCurrency', $this->_currentCurrencyID);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrologyInstance->addLog('Find current currency ' . $this->_currentCurrencyID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '952d5651');
    }

    public function getCurrentCurrencyID(): string
    {
        return $this->_currentCurrencyID;
    }

    public function getCurrentCurrencyInstance(): ?Currency
    {
        return $this->_currentCurrencyInstance;
    }



    private string $_currentTokenPool = '';
    private ?TokenPool $_currentTokenPoolInstance = null;

    private function _findCurrentTokenPool()
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentTokenPool = '0';
            $this->_currentTokenPoolInstance = $this->_cacheInstance->newTokenPool('0');
            $this->_sessionInstance->setSessionStore('nebuleSelectedTokenPool', $this->_currentTokenPool);
            return;
        }

        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_TOKENPOOL))
            $arg = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_TOKENPOOL, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_TOKENPOOL, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentTokenPool = $arg;
            $this->_currentTokenPoolInstance = $this->_cacheInstance->newTokenPool($arg);
            $this->_sessionInstance->setSessionStore('nebuleSelectedTokenPool', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedTokenPool');
            if ($cache !== false && $cache != '') {
                $this->_currentTokenPool = $cache;
                $this->_currentTokenPoolInstance = $this->_cacheInstance->newTokenPool($cache);
            } else {
                $this->_currentTokenPool = '0';
                $this->_currentTokenPoolInstance = $this->_cacheInstance->newTokenPool('0');
                $this->_sessionInstance->setSessionStore('nebuleSelectedTokenPool', $this->_currentTokenPool);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrologyInstance->addLog('Find current token pool ' . $this->_currentTokenPool, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c8485d55');
    }

    public function getCurrentTokenPool(): string
    {
        return $this->_currentTokenPool;
    }

    public function getCurrentTokenPoolInstance(): ?TokenPool
    {
        return $this->_currentTokenPoolInstance;
    }



    private string $_currentTokenID = '';
    private ?Token $_currentTokenInstance = null;

    private function _findCurrentToken()
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentTokenID = '0';
            $this->_currentTokenInstance = $this->_cacheInstance->newToken('0');
            $this->_sessionInstance->setSessionStore('nebuleSelectedToken', $this->_currentTokenID);
            return;
        }

        if (filter_has_var(INPUT_GET, References::COMMAND_SELECT_TOKEN))
            $arg = trim(' ' . filter_input(INPUT_GET, References::COMMAND_SELECT_TOKEN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg = trim(' ' . filter_input(INPUT_POST, References::COMMAND_SELECT_TOKEN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg, false, true)
            && ($this->_ioInstance->checkObjectPresent($arg)
                || $this->_ioInstance->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentTokenID = $arg;
            $this->_currentTokenInstance = $this->_cacheInstance->newToken($arg);
            $this->_sessionInstance->setSessionStore('nebuleSelectedToken', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedToken');
            if ($cache !== false  && $cache != '') {
                $this->_currentTokenID = $cache;
                $this->_currentTokenInstance = $this->_cacheInstance->newToken($cache);
            } else {
                $this->_currentTokenID = '0';
                $this->_currentTokenInstance = $this->_cacheInstance->newToken('0');
                $this->_sessionInstance->setSessionStore('nebuleSelectedToken', $this->_currentTokenID);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrologyInstance->addLog('Find current token ' . $this->_currentTokenID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0ccb0886');
    }

    public function getCurrentTokenID(): string
    {
        return $this->_currentTokenID;
    }

    public function getCurrentTokenInstance(): ?Token
    {
        return $this->_currentTokenInstance;
    }



    /**
     * Calculate the level of usability of entities.
     *
     * @return integer
     */
    public function checkInstances(): int
    {
        if (!$this->_authoritiesInstance->getPuppetmasterInstance() instanceof Entity) return 1;
        if ($this->_authoritiesInstance->getPuppetmasterEID() == '0') return 2;
        if ($this->_authoritiesInstance->getPuppetmasterEID() != $this->_configurationInstance->getOptionUntyped('puppetmaster')) return 3; // TODO à retirer

        if (sizeof($this->_authoritiesInstance->getSecurityAuthoritiesInstance()) == 0) return 11;
        foreach ($this->_authoritiesInstance->getSecurityAuthoritiesInstance() as $instance)
        {
            if (!$instance instanceof Entity) return 12;
            if ($instance->getID() == '0') return 13;
        }

        if (sizeof($this->_authoritiesInstance->getCodeAuthoritiesInstance()) == 0) return 21;
        foreach ($this->_authoritiesInstance->getCodeAuthoritiesInstance() as $instance)
        {
            if (!$instance instanceof Entity) return 22;
            if ($instance->getID() == '0') return 23;
        }

        if (sizeof($this->_authoritiesInstance->getDirectoryAuthoritiesInstance()) == 0) return 31;
        foreach ($this->_authoritiesInstance->getDirectoryAuthoritiesInstance() as $instance)
        {
            if (!$instance instanceof Entity) return 32;
            if ($instance->getID() == '0') return 33;
        }

        if (sizeof($this->_authoritiesInstance->getTimeAuthoritiesInstance()) == 0) return 41;
        foreach ($this->_authoritiesInstance->getTimeAuthoritiesInstance() as $instance)
        {
            if (!$instance instanceof Entity) return 42;
            if ($instance->getID() == '0') return 43;
        }

        // Vérifie que l'entité de l'instance nebule est une entité et a été trouvée.
        if (!$this->_instanceEntityInstance_DEPRECATED instanceof Entity) return 51;
        if ($this->_instanceEntityInstance_DEPRECATED->getID() == '0') return 52;

        // Vérifie qu'une entité courante existe et est une entité.
        if (!$this->_entitiesInstance->getCurrentEntityInstance() instanceof Entity) return 61;
        if ($this->_entitiesInstance->getCurrentEntityInstance()->getID() == '0') return 62;

        // Tout est bon et l'instance est utilisée.
        return 128;
    }



    /**
     * Vérifie le minimum vital pour nebule.
     * @return boolean
     * @todo
     *
     */
    private function _nebuleCheckEnvironment(): bool
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
        $result = array();
        $hashType = '';
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
            $type =$this->_cacheInstance->newNode($hashType);
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
            $result[$i] = $l->getParsed()['bl/rl/nid1'];

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
        $this->_metrologyInstance->addLog('Extract display next object', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bccbff7a');

        $arg = trim(' ' . filter_input(INPUT_GET, Displays::DEFAULT_NEXT_COMMAND, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            return $arg;
        return '';
    }
}
