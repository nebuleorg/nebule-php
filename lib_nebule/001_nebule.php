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
    const NEBULE_FUNCTION_VERSION = '020240225';
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

    private nebule $_nebuleInstance;
    private ?Metrology $_metrologyInstance = null;
    private ?Configuration $_configurationInstance = null;
    private ?Session $_sessionInstance = null;
    private ?Cache $_cacheInstance = null;
    private ?Ticketing $_ticketingInstance = null;
    private ?ioInterface $_ioInstance = null;
    private ?CryptoInterface $_cryptoInstance = null;
    private ?SocialInterface $_socialInstance = null;
    private ?Entities $_entitiesInstance = null;
    private ?Authorities $_authoritiesInstance = null;
    private ?Recovery $_recoveryInstance = null;



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

    private function _initialisation()
    {
        global $nebuleInstance;

        $this->_nebuleInstance = $this;
        $nebuleInstance = $this;
        $this->_metrologyInstance = new Metrology($this);
        $this->_configurationInstance = new Configuration($this);
        $this->_sessionInstance = new Session($this);
        $this->_cacheInstance = new Cache($this);
        $this->_ticketingInstance = new Ticketing($this);

        $this->_findModeRescue();

        if (!$this->_nebuleCheckEnvironment())
            $this->_nebuleInitEnvironment();

        $this->_ioInstance = new io($this);
        $this->_cryptoInstance = new Crypto($this);
        $this->_socialInstance = new Social($this);
        $this->_authoritiesInstance = new Authorities($this);
        $this->_recoveryInstance = new Recovery($this);
        $this->_entitiesInstance = new Entities($this);

        $this->_metrologyInstance->addLog('First step init nebule instance', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '64154189');

        $this->_configurationInstance->setPermitOptionsByLinks(true);
        $this->_configurationInstance->flushCache();

        //$this->_findFlushCache();

        $this->_getSubordinationEntity();
        $this->_cacheInstance->readCacheOnSessionBuffer();

        $this->_checkWriteableIO();

        $this->_findPuppetmaster();
        $this->_findGlobalAuthorities();
        $this->_findLocalAuthorities();
        $this->_findInstanceEntity();
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

        $this->_metrologyInstance->addLog('End init nebule instance', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '474676ed');
    }



    public function getMetrologyInstance(): ?Metrology
    {
        return $this->_metrologyInstance;
    }

    public function getConfigurationInstance(): ?Configuration
    {
        return $this->_configurationInstance;
    }

    public function getCacheInstance(): ?Cache
    {
        return $this->_cacheInstance;
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

    public function getSessionInstance(): ?Session
    {
        return $this->_sessionInstance;
    }

    public function getEntitiesInstance(): ?Entities
    {
        return $this->_entitiesInstance;
    }

    public function getAuthoritiesInstance(): ?Authorities
    {
        return $this->_authoritiesInstance;
    }

    public function getRecoveryInstance(): ?Recovery
    {
        return $this->_recoveryInstance;
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

    /**
     * Nouvelle instance d'un objet.
     * FIXME à supprimer.
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
     * FIXME à supprimer.
     *
     * @param string $nid
     * @return Entity|nodeInterface
     */
    public function newEntity(string $nid): Entity
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_ENTITY);
    }

    /**
     * Nouvelle instance d'un groupe.
     * FIXME à supprimer.
     *
     * @param string $nid
     * @return Group|nodeInterface
     */
    public function newGroup(string $nid): Group
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_GROUP);
    }

    /**
     * Nouvelle instance d'une conversation.
     * FIXME à supprimer.
     *
     * @param string $nid
     * @return Conversation|nodeInterface
     */
    public function newConversation(string $nid): Conversation
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_CONVERSATION);
    }

    /**
     * Nouvelle instance d'une monnaie.
     * FIXME à supprimer.
     *
     * @param string $nid
     * @return Currency|nodeInterface
     */
    public function newCurrency(string $nid): Currency
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_CURRENCY);
    }

    /**
     * Nouvelle instance d'un jeton.
     * FIXME à supprimer.
     *
     * @param string $nid
     * @return Token|nodeInterface
     */
    public function newToken(string $nid): Token
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_TOKEN);
    }

    /**
     * Nouvelle instance d'un sac de jetons.
     * FIXME à supprimer.
     *
     * @param string $nid
     * @return TokenPool|nodeInterface
     */
    public function newTokenPool(string $nid): TokenPool
    {
        return $this->_cacheInstance->newNode($nid, Cache::TYPE_TOKENPOOL);
    }

    /**
     * Nouvelle instance d'un lien.
     * FIXME à supprimer.
     *
     * @param string $link
     * @return BlocLink
     */
    public function newBlockLink(string $link): BlocLink
    {
        return $this->_cacheInstance->newBlockLink($link, Cache::TYPE_LINK);
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
            $this->_currentObjectInstance = $this->newObject($arg_obj);
            // Ecrit l'objet dans la session.
            $this->_sessionInstance->setSessionStore('nebuleSelectedObject', $arg_obj);
        } else {
            // Sinon vérifie si une valeur n'est pas mémorisée dans la session.
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedObject');
            // Si il existe une variable de session pour l'objet en cours, la lit.
            if ($cache !== false && $cache != '') {
                $this->_currentObject = $cache;
                $this->_currentObjectInstance = $this->newObject($cache);
            } else // Sinon selectionne l'entite courante par défaut.
            {
                $this->_currentObject = $this->_entitiesInstance->getCurrentEntityID();
                $this->_currentObjectInstance = $this->newObject($this->_entitiesInstance->getCurrentEntityID());
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



    private string $_instanceEntity = '';
    private ?Entity $_instanceEntityInstance = null;

    private function _findInstanceEntity() // TODO migrate to 008_entities.php
    {
        $instance = null;
        // Vérifie si une valeur n'est pas mémorisée dans la session.
        $id = $this->_sessionInstance->getSessionStore('nebuleHostEntity');

        // Si il existe une variable de session pour l'hôte en cours, la lit.
        if ($id !== false
            && $id != ''
        ) {
            $instance = unserialize($this->_sessionInstance->getSessionStore('nebuleHostEntityInstance'));
        }

        if ($id !== false
            && $id != ''
            && $instance !== false
            && $instance !== ''
            && is_a($instance, 'Nebule\Library\Entity')
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

            if (is_string($id) && Node::checkNID($id, false, false)
                && $this->_ioInstance->checkObjectPresent($id)
                && $this->_ioInstance->checkLinkPresent($id)
            ) {
                $this->_instanceEntity = $id;
                $this->_instanceEntityInstance = $this->_cacheInstance->newEntity($id);
            } else {
                // Sinon utilise l'instance du maître du code.
                $this->_instanceEntity = $this->_puppetmasterID;
                $this->_instanceEntityInstance = $this->_puppetmasterInstance;
            }

            // Log
            $this->_metrologyInstance->addLog('Find server entity ' . $this->_instanceEntity, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5347c940');

            // Mémorisation.
            $this->_sessionInstance->setSessionStore('nebuleHostEntity', $this->_instanceEntity);
            $this->_sessionInstance->setSessionStore('nebuleHostEntityInstance', serialize($this->_instanceEntityInstance));
        }
        unset($id, $instance);
    }

    public function getInstanceEntity(): string
    {
        return $this->_instanceEntity;
    }

    public function getInstanceEntityInstance(): ?Entity
    {
        return $this->_instanceEntityInstance;
    }



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
            $this->_currentGroupInstance = $this->newGroup($arg_grp);
            $this->_sessionInstance->setSessionStore('nebuleSelectedGroup', $arg_grp);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedGroup');
            if ($cache !== false && $cache != '') {
                $this->_currentGroupID = $cache;
                $this->_currentGroupInstance = $this->newGroup($cache);
            } else
            {
                $this->_currentGroupID = '0';
                $this->_currentGroupInstance = $this->newGroup('0');
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
            $this->_currentConversationInstance = $this->newConversation($arg_cvt);
            $this->_sessionInstance->setSessionStore('nebuleSelectedConversation', $arg_cvt);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedConversation');
            if ($cache !== false && $cache != '') {
                $this->_currentConversationID = $cache;
                $this->_currentConversationInstance = $this->newConversation($cache);
            } else {
                $this->_currentConversationID = '0';
                $this->_currentConversationInstance = $this->newConversation('0');
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
            $this->_currentCurrencyInstance = $this->newCurrency('0');
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
            $this->_currentCurrencyInstance = $this->newCurrency($arg);
            $this->_sessionInstance->setSessionStore('nebuleSelectedCurrency', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedCurrency');
            if ($cache !== false && $cache != '') {
                $this->_currentCurrencyID = $cache;
                $this->_currentCurrencyInstance = $this->newCurrency($cache);
            } else {
                $this->_currentCurrencyID = '0';
                $this->_currentCurrencyInstance = $this->newCurrency('0');
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
            $this->_currentTokenPoolInstance = $this->newTokenPool('0');
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
            $this->_currentTokenPoolInstance = $this->newTokenPool($arg);
            $this->_sessionInstance->setSessionStore('nebuleSelectedTokenPool', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedTokenPool');
            if ($cache !== false && $cache != '') {
                $this->_currentTokenPool = $cache;
                $this->_currentTokenPoolInstance = $this->newTokenPool($cache);
            } else {
                $this->_currentTokenPool = '0';
                $this->_currentTokenPoolInstance = $this->newTokenPool('0');
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
            $this->_currentTokenInstance = $this->newToken('0');
            $this->_sessionInstance->setSessionStore('nebuleSelectedToken', $this->_currentTokenID);
            return;
        }

        if (filter_has_var(INPUT_GET, self::COMMAND_SELECT_TOKEN))
            $arg = trim(' ' . filter_input(INPUT_GET, self::COMMAND_SELECT_TOKEN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg = trim(' ' . filter_input(INPUT_POST, self::COMMAND_SELECT_TOKEN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentTokenID = $arg;
            $this->_currentTokenInstance = $this->newToken($arg);
            $this->_sessionInstance->setSessionStore('nebuleSelectedToken', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedToken');
            if ($cache !== false  && $cache != '') {
                $this->_currentTokenID = $cache;
                $this->_currentTokenInstance = $this->newToken($cache);
            } else {
                $this->_currentTokenID = '0';
                $this->_currentTokenInstance = $this->newToken('0');
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
        if (!$this->_entitiesInstance->getCurrentEntityInstance() instanceof Entity) return 61;
        if ($this->_entitiesInstance->getCurrentEntityInstance()->getID() == '0') return 62;

        // Tout est bon et l'instance est utilisée.
        return 128;
    }



    private string $_puppetmasterID = '';
    private ?Entity $_puppetmasterInstance = null;
    private array $_securityAuthoritiesID = array();
    private array $_securityAuthoritiesInstance = array();
    private array $_securitySignersInstance = array();
    private array $_codeAuthoritiesID = array();
    private array $_codeAuthoritiesInstance = array();
    private array $_codeSignersInstance = array();
    private array $_directoryAuthoritiesID = array();
    private array $_directoryAuthoritiesInstance = array();
    private array $_directorySignersInstance = array();
    private array $_timeAuthoritiesID = array();
    private array $_timeAuthoritiesInstance = array();
    private array $_timeSignersInstance = array();

    /**
     * Récupération du maître.
     *  TODO move to 007_authorities.php
     *
     * Définit par une option ou en dur dans une constante.
     *
     * @return void
     */
    private function _findPuppetmaster()
    {
        $this->_puppetmasterID = $this->_configurationInstance->getOptionUntyped('puppetmaster');
        $this->_puppetmasterInstance = $this->_cacheInstance->newEntity($this->_puppetmasterID);
        $this->_metrologyInstance->addLog('Find puppetmaster ' . $this->_puppetmasterID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '88848d09');
    }



    /**
     * Find all global authorities entities after the puppetmaster.
     *  TODO move to 007_authorities.php
     *
     * @return void
     */
    private function _findGlobalAuthorities()
    {
        $this->_findEntityByType(self::LIB_RID_SECURITY_AUTHORITY,
            $this->_securityAuthoritiesID,
            $this->_securityAuthoritiesInstance,
            $this->_securitySignersInstance,
            'security');
        $this->_findEntityByType(self::LIB_RID_CODE_AUTHORITY,
            $this->_codeAuthoritiesID,
            $this->_codeAuthoritiesInstance,
            $this->_codeSignersInstance,
            'code');
        $this->_findEntityByType(self::LIB_RID_DIRECTORY_AUTHORITY,
            $this->_directoryAuthoritiesID,
            $this->_directoryAuthoritiesInstance,
            $this->_directorySignersInstance,
            'directory');
        $this->_findEntityByType(self::LIB_RID_TIME_AUTHORITY,
            $this->_timeAuthoritiesID,
            $this->_timeAuthoritiesInstance,
            $this->_timeSignersInstance,
            'time');
    }

    /**
     * Find authorities by their roles.
     *  TODO move to 007_authorities.php
     *
     * @param string $rid
     * @param array  $listEID
     * @param array  $listInstances
     * @param array  $signersInstances
     * @param string $name
     * @return void
     */
    private function _findEntityByType(string $rid, array &$listEID, array &$listInstances, array &$signersInstances, string $name): void
    {
        $instance = $this->getCacheInstance()->newNode($rid, Cache::TYPE_NODE);
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $rid,
            'bl/rl/nid3' => $rid,
            //'bl/rl/nid4' => '',
            //'bs/rs1/eid' => $this->_puppetmaster,
            );
        $instance->getLinks($links, $filter, false);

        if (sizeof($links) == 0)
        {
            $listEID[$this->_puppetmasterID] = $this->_puppetmasterID;
            $listInstances[$this->_puppetmasterID] = $this->_puppetmasterInstance;
            $signersInstances[$this->_puppetmasterID] = $this->_puppetmasterInstance;
            return;
        }

        foreach ($links as $link)
        {
            $eid = $link->getParsed()['bl/rl/nid2'];
            $instance = $this->getCacheInstance()->newEntity($eid);
            $listEID[$eid] = $eid;
            $listInstances[$eid] = $instance;
            $signersInstances[$eid] = $link->getSigners();
            $this->_metrologyInstance->addLog('Find ' . $name . ' authority ' . $eid, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'e6f75b5e');
        }
    }

    public function getPuppetmasterEID(): string
    {
        return $this->_puppetmasterID;
    }
    public function getPuppetmasterInstance(): ?Entity
    {
        return $this->_puppetmasterInstance;
    }

    public function getSecurityAuthoritiesEID(): array
    {
        return $this->_securityAuthoritiesID;
    }

    public function getSecurityAuthoritiesInstance(): array
    {
        return $this->_securityAuthoritiesInstance;
    }

    public function getSecuritySignersInstance(): array
    {
        return $this->_securitySignersInstance;
    }

    public function getCodeAuthoritiesEID(): array
    {
        return $this->_codeAuthoritiesID;
    }

    public function getCodeAuthoritiesInstance(): array
    {
        return $this->_codeAuthoritiesInstance;
    }

    public function getCodeSignersInstance(): array
    {
        return $this->_codeSignersInstance;
    }

    public function getDirectoryAuthoritiesEID(): array
    {
        return $this->_directoryAuthoritiesID;
    }

    public function getDirectoryAuthoritiesInstance(): array
    {
        return $this->_directoryAuthoritiesInstance;
    }

    public function getDirectorySignersInstance(): array
    {
        return $this->_directorySignersInstance;
    }

    public function getTimeAuthoritiesEID(): array
    {
        return $this->_timeAuthoritiesID;
    }

    public function getTimeAuthoritiesInstance(): array
    {
        return $this->_timeAuthoritiesInstance;
    }

    public function getTimeSignersInstance(): array
    {
        return $this->_timeSignersInstance;
    }



    private array $_authoritiesID = array();
    private array $_authoritiesInstances = array();
    private array $_localAuthoritiesID = array();
    private array $_localAuthoritiesInstances = array();
    private array $_localPrimaryAuthoritiesID = array();
    private array $_localPrimaryAuthoritiesInstances = array();
    private array $_localAuthoritiesSigners = array();
    private array $_specialEntitiesID = array();
    private bool $_permitInstanceEntityAsAuthority = false;
    private bool $_permitDefaultEntityAsAuthority = false;

    /**
     * Ajoute les autorités locales par défaut.
     * TODO move to 007_authorities.php
     *
     * @return void
     */
    private function _findLocalAuthorities()
    {
        $this->_authoritiesID[$this->_puppetmasterID] = $this->_puppetmasterID;
        $this->_authoritiesInstances[$this->_puppetmasterID] = $this->_puppetmasterInstance;
        $this->_specialEntitiesID[$this->_puppetmasterID] = $this->_puppetmasterID;
        foreach ($this->_securityAuthoritiesID as $item)
        {
            $this->_authoritiesID[$item] = $item;
            $this->_authoritiesInstances[$item] = $this->_securityAuthoritiesInstance[$item];
            $this->_specialEntitiesID[$item] = $item;
        }
        foreach ($this->_codeAuthoritiesID as $item)
        {
            $this->_authoritiesID[$item] = $item;
            $this->_authoritiesInstances[$item] = $this->_codeAuthoritiesInstance[$item];
            $this->_specialEntitiesID[$item] = $item;
            $this->_localAuthoritiesID[$item] = $item;
            $this->_localAuthoritiesInstances[$item] =$this->_codeAuthoritiesInstance[$item];
            $this->_localAuthoritiesSigners[$item] = $this->_puppetmasterID;
        }
        foreach ($this->_directoryAuthoritiesID as $item)
            $this->_specialEntitiesID[$item] = $item;
        foreach ($this->_timeAuthoritiesID as $item)
            $this->_specialEntitiesID[$item] = $item;
    }

    /**
     * Ajoute si autorisé l'entité instance du serveur comme autorité locale.
     * Désactivé automatiquement en mode récupération.
     * TODO move to 007_authorities.php
     *
     * @return void
     */
    private function _addInstanceEntityAsAuthorities()
    {
        if (!$this->_modeRescue)
            $this->_permitInstanceEntityAsAuthority = $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority');
        else
            $this->_permitInstanceEntityAsAuthority = false;

        if ($this->_permitInstanceEntityAsAuthority) {
            $this->_authoritiesID[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_authoritiesInstances[$this->_instanceEntity] = $this->_instanceEntityInstance;
            $this->_specialEntitiesID[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_localAuthoritiesID[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_localAuthoritiesInstances[$this->_instanceEntity] = $this->_instanceEntityInstance;
            $this->_localAuthoritiesSigners[$this->_instanceEntity] = '0';
            $this->_localPrimaryAuthoritiesID[$this->_instanceEntity] = $this->_instanceEntity;
            $this->_localPrimaryAuthoritiesInstances[$this->_instanceEntity] = $this->_instanceEntityInstance;

            $this->_metrologyInstance->addLog('Add instance entity as authority', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0ccb0886');
        }
    }

    /**
     * Ajoute si autorisé l'entité par défaut comme autorité locale.
     * Désactivé automatiquement en mode récupération.
     * TODO move to 007_authorities.php
     *
     * @return void
     */
    private function _addDefaultEntityAsAuthorities()
    {
        if (!$this->_modeRescue)
            $this->_permitDefaultEntityAsAuthority = $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority');
        else
            $this->_permitDefaultEntityAsAuthority = false;

        if ($this->_permitDefaultEntityAsAuthority) {
            $this->_authoritiesID[$this->_defaultEntity_DEPRECATED] = $this->_defaultEntity_DEPRECATED;
            $this->_authoritiesInstances[$this->_defaultEntity_DEPRECATED] = $this->_defaultEntityInstance_DEPRECATED;
            $this->_specialEntitiesID[$this->_defaultEntity_DEPRECATED] = $this->_defaultEntity_DEPRECATED;
            $this->_localAuthoritiesID[$this->_defaultEntity_DEPRECATED] = $this->_defaultEntity_DEPRECATED;
            $this->_localAuthoritiesInstances[$this->_defaultEntity_DEPRECATED] = $this->_defaultEntityInstance_DEPRECATED;
            $this->_localAuthoritiesSigners[$this->_defaultEntity_DEPRECATED] = '0';
            $this->_localPrimaryAuthoritiesID[$this->_defaultEntity_DEPRECATED] = $this->_defaultEntity_DEPRECATED;
            $this->_localPrimaryAuthoritiesInstances[$this->_defaultEntity_DEPRECATED] = $this->_defaultEntityInstance_DEPRECATED;

            $this->_metrologyInstance->addLog('Add default entity as authority', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '95cc6196');
        }
    }

    /**
     * Ajoute des autres entités marquées comme autorités locales.
     * TODO move to 007_authorities.php
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
            $this->_instanceEntityInstance->getLinks($list, $filter, false);
        }

        // Liste les liens de l'entité par défaut.
        if ($this->_permitDefaultEntityAsAuthority) {
            $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $refAuthority,
                'bl/rl/nid2' => $this->_instanceEntity,
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
                'bs/rs1/eid' => $this->_defaultEntity_DEPRECATED,
            );
            $this->_instanceEntityInstance->getLinks($list, $filter, false);
        }

        foreach ($list as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_ENTITY);
            $this->_localAuthoritiesID[$nid] = $nid;
            $this->_localAuthoritiesInstances[$nid] = $instance;
            $this->_specialEntitiesID[$nid] = $nid;
            $this->_localAuthoritiesSigners[$nid] = $link->getParsed()['bs/rs1/eid'];
            $this->_authoritiesID[$nid] = $nid;
            $this->_authoritiesInstances[$nid] = $instance;
        }
    }

    /**
     * Lit la liste des ID des autorités.
     * TODO move to 007_authorities.php
     *
     * @return array:string
     */
    public function getAuthoritiesID(): array
    {
        return $this->_authoritiesID;
    }

    /**
     * Lit la liste des instances des autorités.
     * TODO move to 007_authorities.php
     *
     * @return array:Entity
     */
    public function getAuthoritiesInstance_DEPRECATED(): array
    {
        return $this->_authoritiesInstances;
    }

    /**
     * Lit la liste des ID des autorités locales.
     * TODO move to 007_authorities.php
     *
     * @return array:string
     */
    public function getLocalAuthoritiesID(): array
    {
        return $this->_localAuthoritiesID;
    }

    /**
     * Lit la liste des instances des autorités locales.
     * TODO move to 007_authorities.php
     *
     * @return array:Entity
     */
    public function getLocalAuthoritiesInstance(): array
    {
        return $this->_localAuthoritiesInstances;
    }

    /**
     * Lit la liste des autorités locales déclarants des autorités locales.
     * TODO move to 007_authorities.php
     *
     * @return array:string
     */
    public function getLocalAuthoritiesSigners(): array
    {
        return $this->_localAuthoritiesSigners;
    }

    /**
     * Lit la liste des ID des autorités locales.
     * TODO move to 007_authorities.php
     *
     * @return array:string
     */
    public function getLocalPrimaryAuthoritiesID(): array
    {
        return $this->_localPrimaryAuthoritiesID;
    }

    /**
     * Lit la liste des instances des autorités locales.
     * TODO move to 007_authorities.php
     *
     * @return array:Entity
     */
    public function getLocalPrimaryAuthoritiesInstance(): array
    {
        return $this->_localPrimaryAuthoritiesInstances;
    }

    /**
     * Lit la liste des ID des entités avec des rôles.
     *  TODO move to 007_authorities.php
     *
     * @return array:string
     */
    public function getSpecialEntitiesID(): array
    {
        return $this->_specialEntitiesID;
    }

    /**
     * Retourne si l'entité est autorité locale.
     *  TODO move to 007_authorities.php
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

        foreach ($this->_localAuthoritiesID as $authority) {
            if ($entity == $authority)
                return true;
        }
        return false;
    }


    private array $_recoveryEntities = array();
    private array $_recoveryEntitiesInstances = array();
    private array $_recoveryEntitiesSigners = array();
    private bool $_permitInstanceEntityAsRecovery = false;
    private bool $_permitDefaultEntityAsRecovery = false;

    /**
     * Ajoute si autorisé l'entité instance du serveur comme entité de recouvrement locale.
     *  TODO move to 009_recovery.php
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

            $this->_metrologyInstance->addLog('Add instance entity as recovery', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'fa22c32d');
        }
    }

    /**
     * Ajoute si autorisé l'entité par défaut comme entité de recouvrement locale.
     *  TODO move to 009_recovery.php
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
            $this->_recoveryEntities[$this->_defaultEntity_DEPRECATED] = $this->_defaultEntity_DEPRECATED;
            $this->_recoveryEntitiesInstances[$this->_defaultEntity_DEPRECATED] = $this->_defaultEntityInstance_DEPRECATED;
            $this->_recoveryEntitiesSigners[$this->_defaultEntity_DEPRECATED] = '0';

            $this->_metrologyInstance->addLog('Add default entity as recovery', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'fa22c32d');
        }
    }

    /**
     * Recherche les entités de recouvrement valables.
     *  TODO move to 009_recovery.php
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
            $target = $link->getParsed()['bl/rl/nid2'];
            $instance = $this->_cacheInstance->newNode($target, Cache::TYPE_ENTITY);
            $this->_recoveryEntities[$target] = $target;
            $this->_recoveryEntitiesInstances[$target] = $instance;
            $this->_recoveryEntitiesSigners[$target] = $link->getParsed()['bs/rs1/eid'];
        }

        // Liste les liens de l'entité instance du serveur..
        $list = array();
        if ($this->_permitDefaultEntityAsAuthority) {
            $list = $this->_instanceEntityInstance->getLinksOnFields(
                $this->_defaultEntity_DEPRECATED,
                '',
                'f',
                $this->_instanceEntity,
                '',
                $refRecovery
            );
        }

        foreach ($list as $link) {
            $target = $link->getParsed()['bl/rl/nid2'];
            $instance = $this->_cacheInstance->newNode($target, Cache::TYPE_ENTITY);
            $this->_recoveryEntities[$target] = $target;
            $this->_recoveryEntitiesInstances[$target] = $instance;
            $this->_recoveryEntitiesSigners[$target] = $link->getParsed()['bs/rs1/eid'];
        }
        unset($list);
    }

    /**
     * Lit la liste des ID des entités de recouvrement.
     *  TODO move to 009_recovery.php
     *
     * @return array:string
     */
    public function getRecoveryEntities(): array
    {
        return $this->_recoveryEntities;
    }

    /**
     * Lit la liste des instance des entités de recouvrement.
     *  TODO move to 009_recovery.php
     *
     * @return array:Entity
     */
    public function getRecoveryEntitiesInstance(): array
    {
        return $this->_recoveryEntitiesInstances;
    }

    /**
     * Lit la liste des autorités locales déclarants les entités de recouvrement.
     *  TODO move to 009_recovery.php
     *
     * @return array:string
     */
    public function getRecoveryEntitiesSigners(): array
    {
        return $this->_recoveryEntitiesSigners;
    }

    /**
     * Retourne si l'entité est entité de recouvrement.
     *  TODO move to 009_recovery.php
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
            $id = $link->getParsed()['bl/rl/nid1'];
            $instance = $this->_cacheInstance->newNode($id, Cache::TYPE_ENTITY);
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
    private bool $_modeRescue = false;

    /**
     * Extrait si on est en mode de récupération.
     *
     * @return void
     */
    private function _findModeRescue(): void
    {
        if ($this->_configurationInstance->getOptionUntyped('modeRescue')
            || ($this->_configurationInstance->getOptionAsBoolean('permitOnlineRescue')
                && (filter_has_var(INPUT_GET, References::COMMAND_RESCUE)
                    || filter_has_var(INPUT_POST, References::COMMAND_RESCUE)
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
     * Crée et écrit un objet avec du texte.
     * TODO protection
     *
     * @param string  $text
     * @param boolean $protect
     * @param bool    $obfuscate
     * @return string
     */
    public function createTextAsObject(string &$text, bool $protect = false, bool $obfuscate = false): string
    {
        if (!$this->_configurationInstance->checkBooleanOptions(array('unlocked','permitWrite','permitWriteObject','permitWriteLink'))
            || strlen($text) == 0
            || $protect // TODO
        )
            return '';

        $textOID = $this->getNIDfromData($text);
        $this->_ioInstance->setObject($textOID, $text);
        $propertyOID = $this->_nebuleInstance->getNIDfromData('text/plain');
        $propertyRID = $this->_nebuleInstance->getNIDfromData(self::REFERENCE_NEBULE_OBJET_TYPE);
        $link = 'l>' . $textOID . '>' . $propertyOID . '>' . $propertyRID;
        $newBlockLink = new blocLink($this, 'new');
        $newLink = new Link($this->_nebuleInstance, $link, $newBlockLink);
        if ($obfuscate && !$newLink->setObfuscate())
            return '';
        $newBlockLink->signwrite($this->_entitiesInstance->getCurrentEntityID());

        return $textOID;
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
            || is_a($nid, '\Nebule\Library\Node')
            || is_a($nid, 'Group')
            || is_a($nid, '\Nebule\Library\Group')
            || is_a($nid, 'Entity')
            || is_a($nid, '\Nebule\Library\Entity')
            || is_a($nid, 'Conversation')
            || is_a($nid, '\Nebule\Library\Conversation')
            || is_a($nid, 'Currency')
            || is_a($nid, '\Nebule\Library\Currency')
            || is_a($nid, 'TokenPool')
            || is_a($nid, '\Nebule\Library\TokenPool')
            || is_a($nid, 'Token')
            || is_a($nid, '\Nebule\Library\Token')
            || is_a($nid, 'Wallet')
            || is_a($nid, '\Nebule\Library\Wallet')
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
