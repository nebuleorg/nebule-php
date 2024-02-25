<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use const Nebule\Bootstrap\LIB_ARG_BOOTSTRAP_BREAK;
use const Nebule\Bootstrap\LIB_ARG_FLUSH_SESSION;
use const Nebule\Bootstrap\LIB_ARG_INLINE_DISPLAY;
use const Nebule\Bootstrap\LIB_ARG_RESCUE_MODE;
use const Nebule\Bootstrap\LIB_ARG_SWITCH_APPLICATION;
use const Nebule\Bootstrap\LIB_ARG_UPDATE_APPLICATION;
use const Nebule\Bootstrap\LIB_LOCAL_ENTITY_FILE;
use const Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER;

/**
 * Classe de référence des applications.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Applications implements applicationInterface
{
    const APPLICATION_NAME = 'undef';
    const APPLICATION_SURNAME = 'undef';
    const APPLICATION_AUTHOR = 'undef';
    const APPLICATION_VERSION = 'undef';
    const APPLICATION_LICENCE = 'undef';
    const APPLICATION_WEBSITE = 'undef';
    const APPLICATION_NODE = 'undef';
    const APPLICATION_CODING = 'application/x-httpd-php';

    /* ---------- ---------- ---------- ---------- ----------
	 * Variables.
	 *
	 * Les valeurs par défaut sont indicatives. Ne pas les replacer.
	 * Les variables sont systématiquement recalculées.
	 */
    /**
     * Instance en cours (application).
     *
     * @var Applications
     */
    protected $_applicationInstance;

    /**
     * @var string
     */
    protected $_applicationNamespace;

    /**
     * Instance de la librairie en cours.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    protected $_configurationInstance;

    /**
     * Instance de la métrologie.
     *
     * @var Metrology
     */
    protected $_metrologyInstance;

    /**
     * Instance de l'affichage de l'application.
     *
     * @var Displays
     */
    protected $_displayInstance;

    /**
     * Instance des actions de l'application.
     *
     * @var Actions
     */
    protected $_actionInstance;

    /**
     * Instance de traduction linguistique de l'application.
     *
     * @var Traductions
     */
    protected $_traductionInstance;

    /**
     * Paramètre d'activation de la gestion des modules dans l'application et la traduction.
     *
     * Par défault les applications n'utilisent pas les modules.
     *
     * @var boolean
     */
    protected $_useModules = false;

    /**
     * Paramètre d'activation de la gestion des modules externes (dans des objets) dans l'application et la traduction.
     *
     * Par défault les applications n'utilisent pas les modules externes.
     *
     * @var boolean
     */
    protected $_useExternalModules = false;


    
    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @return void
     */
    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_configurationInstance = $nebuleInstance->getConfigurationInstance();
        $this->_applicationNamespace = '\\Nebule\\Application\\' . strtoupper(substr(static::APPLICATION_NAME, 0, 1)) . strtolower(substr(static::APPLICATION_NAME, 1));
    }

    /**
     * Initialisation des variables et instances.
     *
     * @return void
     */
    public function initialisation(): void
    {
        global $applicationTraductionInstance, $applicationDisplayInstance, $applicationActionInstance;

        // S'autoréférence pour être capable de se transmettre aux objets.
        $this->_applicationInstance = $this;
        $this->_applicationNamespace = '\\Nebule\\Application\\' . strtoupper(substr(static::APPLICATION_NAME, 0, 1)) . strtolower(substr(static::APPLICATION_NAME, 1));

        // Charge l'instance de métrologie et de journalisation.
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();

        // Retrouve tout le nécessaire au fonctionnement de l'application sauf les instances.
        $this->_findEnvironment();

        // Si c'est le téléchargement d'un objet ou de ses liens, on ne fait pas le chargement de l'affichage.
        if ($this->_findAskDownload())
            return;

        // Récupère les instances.
        $this->_traductionInstance = $applicationTraductionInstance;
        $this->_displayInstance = $applicationDisplayInstance;
        $this->_actionInstance = $applicationActionInstance;

        // Charge les modules au besoin. Avant les initialisations.
        $this->_loadModules();
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
    public function __toString(): string
    {
        global $applicationName;
        return $applicationName;
    }

    /**
     * Fonction de mise en sommeil.
     *
     * Vide par défaut, est remplacé par l'application.
     *
     * @return array:string
     */
    public function __sleep(): array
    {
        return array();
    }

    /**
     * Fonction de réveil.
     *
     * Récupère l'instance de la librairie nebule.
     *
     * @return void
     */
    public function __wakeup()
    {
        global $nebuleInstance;

        $this->_nebuleInstance = $nebuleInstance;
        $this->_configurationInstance = $nebuleInstance->getConfigurationInstance();
    }


    /**
     * Retourne le nom de la classe de l'application.
     *
     * @return string
     */
    public function getClassName(): string
    {
        //global $applicationName;
        //return $applicationName;
        return static::class;
    }

    /**
     * Get the app name.
     * @return string
     */
    public function getName(): string
    {
        return static::APPLICATION_NAME;
    }

    /**
     * Get the app namespace.
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->_applicationNamespace;
    }

    /**
     * Lit l'instance de la librairie nebule.
     *
     * @return nebule
     */
    public function getNebuleInstance(): nebule
    {
        return $this->_nebuleInstance;
    }

    /**
     * Lit l'instance d'affichage de l'application.
     *
     * @return Displays
     */
    public function getDisplayInstance(): Displays
    {
        return $this->_displayInstance;
    }

    /**
     * Lit l'instance de traduction linguistique de l'application.
     *
     * @return Traductions
     */
    public function getTraductionInstance(): Traductions
    {
        return $this->_traductionInstance;
    }

    /**
     * Lit l'instance de métrologie.
     *
     * @return Metrology
     */
    public function getMetrologyInstance(): Metrology
    {
        return $this->_metrologyInstance;
    }

    /**
     * Lit l'instance des actions de l'application.
     *
     * @return Actions
     */
    public function getActionInstance(): Actions
    {
        return $this->_actionInstance;
    }

    /**
     * Retourne l'objet en cours d'utilisation.
     *
     * @return string
     */
    public function getCurrentObjectID(): string
    {
        return $this->_nebuleInstance->getCurrentObject();
    }

    /**
     * Retourne l'instance de l'objet en cours d'utilisation.
     *
     * @return Node
     */
    public function getCurrentObjectInstance(): Node
    {
        return $this->_nebuleInstance->getCurrentObjectInstance();
    }

    /**
     * Retourne si les modules sont activés dans l'application.
     *
     * @return bool
     */
    public function getUseModules(): bool
    {
        return $this->_useModules;
    }

    /**
     * Retourne si les modules externes sont activés dans l'application.
     *
     * @return bool
     */
    public function getUseExternalModules(): bool
    {
        return $this->_useExternalModules;
    }


    /**
     * Recherche toutes les données nécessaires aux applications.
     *
     * @return void
     */
    protected function _findEnvironment(): void
    {
        $this->_findURL();
        $this->_findCurrentEntity();
    }

    /**
     * Le protocol de l'URL HTTP demandée.
     *
     * @var string
     */
    protected $_urlProtocol;
    /**
     * Le nom de serveur public de l'URL HTTP demandée.
     *
     * @var string
     */
    protected $_urlHost;
    /**
     * Le nom de fichier de l'URL HTTP demandée.
     *
     * @var string
     */
    protected $_urlBasename;
    /**
     * Le chemin de l'URL HTTP demandée.
     *
     * @var string
     */
    protected $_urlPath;
    /**
     * Le nom de serveur public de l'URL HTTP demandée.
     *
     * @var string
     */
    protected $_urlHostname;

    /**
     * Extrait l'URL de connexion au serveur.
     *
     * @return void
     */
    protected function _findURL(): void
    {
        if (isset($_SERVER['HTTPS'])
            && $_SERVER['HTTPS']
        )
            $this->_urlProtocol = 'https';
        else
            $this->_urlProtocol = 'http';
        //$this->_urlHost	= $_SERVER['HTTP_HOST'];
        $this->_urlHost = $this->_configurationInstance->getOptionUntyped('hostURL');
        $explodeBaseName = explode('/', $_SERVER['REQUEST_URI']);
        $this->_urlBasename = end($explodeBaseName);
        $this->_urlPath = substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strlen($this->_urlBasename) - 1);
        $this->_urlHostname = $this->_urlProtocol . '://' . $this->_urlHost . $this->_urlPath;
    }

    /**
     * Retourne le protocol de l'URL HTTP demandée.
     *
     * @return string
     */
    public function getUrlProtocol(): string
    {
        return $this->_urlProtocol;
    }

    /**
     * Retourne le nom de serveur public de l'URL HTTP demandée.
     *
     * @return string
     */
    public function getUrlHost(): string
    {
        return $this->_urlHost;
    }

    /**
     * Retourne le chemin de l'URL HTTP demandée.
     *
     * @return string
     */
    public function getUrlBasename(): string
    {
        return $this->_urlBasename;
    }

    /**
     * Retourne le nom de serveur public de l'URL HTTP demandée.
     *
     * @return string
     */
    public function getUrlPath(): string
    {
        return $this->_urlPath;
    }

    /**
     * Extrait l'URL de connexion au serveur.
     *
     * @return string
     */
    public function getUrlHostname(): string
    {
        return $this->_urlHostname;
    }


    protected $_currentEntity, $_currentEntityInstance;

    /**
     * Recherche l'entité en cours d'utilisation.
     */
    protected function _findCurrentEntity(): void
    {
        $this->_metrologyInstance->addLog('Find current entity', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        
        $arg_ent = filter_input(INPUT_GET, nebule::COMMAND_SELECT_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
        if ($arg_ent === false || $arg_ent === null)
            $arg_ent = '';
        $arg_ent = trim($arg_ent);
        if ($arg_ent != '' && Node::checkNID($arg_ent, false, false)
            && ($this->_nebuleInstance->getIoInstance()->checkObjectPresent($arg_ent)
                || $this->_nebuleInstance->getIoInstance()->checkLinkPresent($arg_ent)
            )
        ) // Si la variable est un objet avec ou sans liens.
        {
            // Ecrit l'objet dans la variable.
            $this->_currentEntity = $arg_ent;
            $this->_currentEntityInstance = $this->_nebuleInstance->newEntity($arg_ent);
            // Ecrit l'objet dans la session.
            $this->_nebuleInstance->setSessionStore('sylabeSelectedEntity', $arg_ent);
        } else {
            // Sinon vérifie si une valeur n'est pas mémorisée dans la session.
            $cache = $this->_nebuleInstance->getSessionStore('sylabeSelectedEntity');
            // Si il existe une variable de session pour l'objet en cours, la lit.
            if ($cache !== false && $cache != '') {
                $this->_currentEntity = $cache;
                $this->_currentEntityInstance = $this->_nebuleInstance->newEntity($cache);
            } else // Sinon selectionne l'entite courante par défaut.
            {
                $this->_currentEntity = $this->_nebuleInstance->getCurrentEntity();
                $this->_currentEntityInstance = $this->_nebuleInstance->newEntity($this->_nebuleInstance->getCurrentEntity());
                $this->_nebuleInstance->setSessionStore('sylabeSelectedEntity', $this->_nebuleInstance->getCurrentEntity());
            }
            unset($cache);
        }
        unset($arg_ent);
    }

    public function getCurrentEntityID(): string
    {
        return $this->_currentEntity;
    }

    public function getCurrentEntityInstance(): Node
    {
        return $this->_currentEntityInstance;
    }


    /**
     * Un téléchargement est demandé.
     *
     * @var boolean
     */
    protected $_askDownload = false;
    /**
     * ID de l'objet demandé au téléchargement.
     *
     * @var string
     */
    protected $_askDownloadObject = '';
    /**
     * ID de l'objet dont les liens sont demandés au téléchargement.
     *
     * @var string
     */
    protected $_askDownloadLinks = '';

    /**
     * Retourne si la requête web est un téléchargement d'objet ou de lien.
     * Des accélérations pruvent être prévues dans ce cas.
     *
     * @return boolean
     */
    public function askDownload(): bool
    {
        return $this->_askDownload;
    }

    /**
     * Gestion des variables pour le téléchargement d'objets et de liens.
     *
     * @return boolean
     */
    protected function _findAskDownload(): bool
    {
        $arg_dwlobj = trim((string)filter_input(INPUT_GET, nebule::NEBULE_LOCAL_OBJECTS_FOLDER, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if (Node::checkNID($arg_dwlobj)) {
            $this->_askDownload = true;
            $this->_askDownloadObject = trim($arg_dwlobj);
            $this->_metrologyInstance->addLog('Ask for download object ' . $arg_dwlobj, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'df913e73');
        }
        $arg_dwllnk = trim((string)filter_input(INPUT_GET, nebule::NEBULE_LOCAL_LINKS_FOLDER, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if (Node::checkNID($arg_dwllnk)) {
            $this->_askDownload = true;
            $this->_askDownloadLinks = trim($arg_dwllnk);
            $this->_metrologyInstance->addLog('Ask for download links ' . $arg_dwllnk, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '98d5ee6d');
        }
        return $this->_askDownload;
    }

    /**
     * Fonction de téléchargement d'objets ou de liens.
     * Le téléchargement se fait sous forme de fichier pour dé-nebulisation d'un objet
     *   ou affichage dans un navigateur.
     * C'est la seule façon de télécharger le contenu d'un objet protégé.
     *
     * @return void
     */
    protected function _download(): void
    {
        $err404 = false;
        if ($this->_askDownloadLinks != '') // Détermine si c'est un lien à télécharger.
        {
            if ($this->_nebuleInstance->getIoInstance()->checkLinkPresent($this->_askDownloadLinks)) {
                $this->_metrologyInstance->addLog('Sending links ' . $this->_askDownloadLinks, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '91b305b1');
                // Flush des erreurs.
                ob_end_clean();
                // Transmission.
                header('Content-Description: File Transfer');
                header('Content-type: text/plain');
                header('Content-Disposition: attachment; filename="' . $this->_askDownloadLinks . '.neb.lnk"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');

                $this->_metrologyInstance->addLog('End sending links ' . $this->_askDownloadLinks, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd5318e9f');
            } else {
                $err404 = true;
                $this->_metrologyInstance->addLog('Error 404 sending links ' . $this->_askDownloadLinks, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'df11e69f');
            }
        } else // Sinon c'est un objet à télécharger.
        {
            $instance = $this->_nebuleInstance->newObject($this->_askDownloadObject);
            $data = $instance->getContent(0);
            if ($data != null) {
                $this->_metrologyInstance->addLog('Sending object ' . $this->_askDownloadObject, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '18852ac4');
                // Calcul type mime, nom et suffixe de fichier pour l'utilisateur final.
                $downloadmime = $instance->getType('all');
                $downloadname = $instance->getName('all');
                $downloadsuffix = $instance->getSuffixName('all');
                if ($downloadsuffix != '') {
                    $downloadname .= '.' . $downloadsuffix;
                }
                // Flush des erreurs.
                ob_end_clean();
                // Transmission.
                header('Content-Description: File Transfer');
                header('Content-type: ' . $downloadmime);
                header('Content-Disposition: attachment; filename="' . $downloadname . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                echo $data;

                $this->_metrologyInstance->addLog('End sending object ' . $this->_askDownloadObject, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '99f390f9');
            } else {
                $err404 = true;
                $this->_metrologyInstance->addLog('Error 404 sending object ' . $this->_askDownloadObject, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f8234249');
            }
        }

        if ($err404) {
            $this->_metrologyInstance->addLog('Sending error 404 ', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6efad36a');
            // Transmission.
            ob_end_clean();
            ob_clean();
            header('HTTP/1.0 404 Not Found');
            echo "<h1>404 Not Found</h1>\nThe page that you have requested could not be found.";
        }
    }


    /**
     * Liste des noms des modules par défaut.
     * Cette liste est à fournir par l'application en cours.
     *
     * Ces modules ne sont pas des objets à part entière mais ils sont intégrés à l'objet de l'application.
     *
     * @var array of string
     */
    protected $_listDefaultModules = array();

    /**
     * Liste des noms des modules chargés.
     * Trié par ordre numérique d'arrivée.
     *
     * @var array of string
     */
    protected $_listModulesName = array();

    /**
     * Liste des instances des modules chargés.
     * Trié par noms de modules.
     *
     * @var array of Modules
     */
    protected $_listModulesInstance = array();

    /**
     * Liste des signataires des modules (RID).
     * Trié par noms de modules.
     * A 0 si le module est chargé par défaut.
     *
     * @var array of string
     */
    protected $_listModulesSignerRID = array();

    /**
     * Liste des ID originaux (de référence) des modules chargés.
     * Trié par noms de modules.
     * A 0 si le module est chargé par défaut.
     *
     * @var array of string
     */
    protected $_listModulesInitRID = array();

    /**
     * Liste des ID de référence finaux des modules chargés.
     * Trié par noms de modules.
     * A 0 si le module est chargé par défaut.
     *
     * @var array of string
     */
    protected $_listModulesRID = array();

    /**
     * Liste des ID mis à jour des modules chargés.
     * Trié par noms de modules.
     * A 0 si le module est chargé par défaut.
     *
     * @var array of string
     */
    protected $_listModulesID = array();

    /**
     * Liste la validité des modules chargés.
     * Trié par noms de modules.
     *
     * @var array of string
     */
    protected $_listModulesValid = array();

    /**
     * Liste l'activation des modules chargés.
     * Trié par noms de modules.
     *
     * @var array of string
     */
    protected $_listModulesEnabled = array();

    /**
     * Le module en cours d'utilisation.
     * Cette variable ne peut pas être initialisée avec l'instance de l'application
     *   parce qu'il faut l'instance Display qui n'est pas encore prête.
     *
     * @var Modules
     */
    protected $_currentModuleInstance = null;

    /**
     * Variable de suivi du chargement des modules pour éviter des doublons.
     *
     * @var boolean
     */
    protected $_loadModulesOK = false;

    /**
     * Chargement des modules.
     *
     * @return void
     */
    protected function _loadModules(): void
    {
        if ($this->_loadModulesOK)
            return;
        $this->_loadModulesOK = true;

        // Vérifie si les modules internes sont activés.
        if (!$this->_useModules) {
            $this->_metrologyInstance->addLog('Do not load internal modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bcc98872');
            return;
        }

        // Charge les modules internes.
        $this->_loadDefaultModules();

        // Vérifie si les modules externes sont activés.
        if (!$this->_useExternalModules) {
            $this->_metrologyInstance->addLog('Do not load external modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '7b3af452');
            return;
        }

        // Charge les modules externes.
        $this->_findModulesRID();
        $this->_findModulesUpdateID();
        $this->_initModules();
    }

    /**
     * Charge et initialise les modules internes par défaut.
     *
     * @return void
     */
    protected function _loadDefaultModules(): void
    {
        if (!$this->_useModules)
            return;

        $this->getMetrologyInstance()->addLog('Load default modules on NameSpace=' . $this->_applicationNamespace, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '93eb59aa');

        foreach ($this->_listDefaultModules as $moduleName) {
            $this->getMetrologyInstance()->addTime();
            $moduleFullName = $this->_applicationNamespace . '\\' . $moduleName;
            $this->getMetrologyInstance()->addLog('Loaded module ' . $moduleFullName . ' (' . $moduleName . ')', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '4879c453');
            $instance = new $moduleFullName($this->_applicationInstance);
            $instance->initialisation();
            $this->_listModulesName[$moduleName] = $moduleFullName;
            $this->_listModulesInstance[$moduleFullName] = $instance;
            $this->_listModulesInitRID[$moduleFullName] = '0';
            $this->_listModulesID[$moduleFullName] = '0';
            $this->_listModulesSignerRID[$moduleFullName] = '0';
            $this->_listModulesValid[$moduleFullName] = true;
        }

        $this->_metrologyInstance->addLog('Default modules loaded', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '050783df');
    }

    /**
     * Recherche les ID de référence des modules externes configurés.
     * Extrait la liste des modules depuis les liens de l'objet de référence.
     *
     * @return void
     */
    protected function _findModulesRID(): void
    {
        global $bootstrapApplicationIID;

        // Vérifie si les modules sont activés.
        if (!$this->_useModules || !$this->_useExternalModules)
            return;

        $this->getMetrologyInstance()->addLog('Find option modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '226ce8be');

        // Extrait les modules référencés.
        $object = $this->_nebuleInstance->newObject($bootstrapApplicationIID);
        $hashRef = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES);
        $links = $object->getLinksOnFields('', '', 'f', $bootstrapApplicationIID, '', $hashRef);

        // Lit les ID des modules.
        foreach ($links as $link) {
            // Filtre sur le signataire.
            $ok = false;
            $module = $link->getParsed()['bl/rl/nid2'];
            foreach ($this->_nebuleInstance->getLocalAuthorities() as $autority) {
                if (isset($link->getParsed()['bs/rs1/eid'])
                    && $link->getParsed()['bs/rs1/eid'] == $autority
                    && $module != '0'
                    && $this->_nebuleInstance->getIoInstance()->checkLinkPresent($module)
                ) {
                    $ok = true;
                    break;
                }
            }
            if ($ok) {
                $this->getMetrologyInstance()->addLog('Find modules ' . $module, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1520ed55');
                $this->_listModulesInitRID[$module] = $module;
                $this->_listModulesSignerRID[$module] = $link->getParsed()['bs/rs1/eid'];
            }
        }
    }

    /**
     * Recherche les mises à jour des modules externes à partir des ID de référence.
     * @return void
     * @todo
     *
     */
    protected function _findModulesUpdateID(): void
    {
        if (!$this->_useModules || !$this->_useExternalModules)
            return;

        $this->getMetrologyInstance()->addLog('Find modules updates', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '19029717');

        // Recherche la mise à jour et vérifie les objets des modules avant de les charger.
        $listed = array();
        foreach ($this->_listModulesInitRID as $moduleID) {
            // Vérifie l'ID. Un module chargé par défaut est déjà chargé et à un ID = 0.
            if ($moduleID == '0'
                || $moduleID == ''
            ) {
                continue;
            }

            $moduleRID = $moduleID;

            $this->getMetrologyInstance()->addLog('Ask load module ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cd99e217');
            $okValid = false;
            $okActivated = false;
            $okNotListed = true;

            // Vérifie que l'objet n'est pas déjà appelé.
            foreach ($listed as $element) {
                if ($element == $moduleID) {
                    $this->getMetrologyInstance()->addLog('Module already listed ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8a077f11');
                    $okNotListed = false;
                }
            }

            $instanceModule = $this->_nebuleInstance->newObject($moduleID);
            $listed[$moduleID] = $moduleID;

            // Cherche une mise à jour.
            $updateModule = $moduleID;
            if ($okNotListed) {
                $updateModule = $instanceModule->getReferencedObjectID(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES, 'authority');
                $updateSigner = $instanceModule->getReferencedSignerID(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES, 'authority');
            }
            if ($updateModule != $moduleID
                && $updateModule != '0'
            ) {
                $instanceModule = $this->_nebuleInstance->newObject($updateModule);
                if ($instanceModule->getType('authority') == 'application/x-php'
                    && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($updateModule)
                ) {
                    $this->getMetrologyInstance()->addLog('Find module update ' . $updateModule, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cabf8ebd');
                    $okValid = true;
                    // Vérifie que l'objet n'est pas déjà appelé.
                    foreach ($listed as $element) {
                        if ($element == $updateModule) {
                            $this->getMetrologyInstance()->addLog('Module update already listed ' . $updateModule, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'fa4a752c');
                            $okNotListed = false;
                        }
                    }

                    $moduleID = $updateModule;
                    $listed[$updateModule] = $updateModule;
                } else {
                    $this->getMetrologyInstance()->addLog('Module updated type mime not valid ' . $moduleID, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f852ca44');
                    $okValid = false;
                }
            }

            // Vérifie si le module est activé.
            $okActivated = $this->getIsModuleActivated($instanceModule);
            unset($instanceModule, $updateModule, $element);

            // @todo Vérifier le contenu.
            // A faire, DANGEREUX !!!

            // Enregirstre le module.
            if ($okValid
                && $okNotListed
            ) {
                // Recherche le nom du module.
                $name = $this->_getObjectClassName($moduleID);

                // Vérifie le nom.
                if ($name !== false
                    && $name != ''
                    && substr($name, 0, 6) == 'Module'
                ) {
                    // Charge le code php si le module est activé..
                    if ($okActivated || true) // @todo à revoir...
                    {
                        $this->getMetrologyInstance()->addLog('Load module ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6d2f16cb');
                        include('o/' . $moduleID);                // @todo A modifier, passer par IO.
                        $this->_listModulesEnabled[$name] = true;
                    }

                    // Enregistre le module.
                    $this->_listModulesID[$name] = $moduleID;
                    $this->_listModulesRID[$name] = $moduleRID;
                    $this->_listModulesValid[$name] = true;
                    $this->_listModulesSignerRID[$name] = $this->_listModulesSignerRID[$moduleRID];
                    $this->_listModulesSignerRID[$moduleID] = $updateSigner;
                    if ($this->_listModulesSignerRID[$moduleID] == '0') {
                        $this->_listModulesSignerRID[$moduleID] = $this->_listModulesSignerRID[$moduleRID];
                    }
                    $this->_listModulesName[] = $name;
                }
            } else {
                $this->_listModulesValid[$name] = false;
            }
            $this->getMetrologyInstance()->addLog('End load module ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ef4207a5');
        }
    }

    /**
     * Recherche le nom de la classe dans un objet.
     *
     * @param string $id
     * @return null|string
     */
    protected function _getObjectClassName(string $id): ?string
    {
        $readValue = $this->_nebuleInstance->getIoInstance()->getObject($id);
        $startValue = strpos($readValue, 'class');
        $trimLine = substr($readValue, $startValue, 128);
        $arrayValue = explode(' ', $trimLine);
        return $arrayValue[1];
    }

    /**
     * Load and init external modules for the application.
     * Some modules are loaded before by default with _loadDefaultModules() depending on the list '_listDefaultModules' give by le app.
     *
     * @return void
     */
    protected function _initModules(): void
    {
        if (!$this->_useModules || !$this->_useExternalModules)
            return;

        $this->getMetrologyInstance()->addLog('Load optionals modules on NameSpace=' . $this->_applicationNamespace, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2df08836');

        // Liste toutes les classes module* et les charges une à une.
        $list = get_declared_classes();
        $searchModuleHead = $this->_applicationNamespace . '\\' . 'Module';
        $sizeModuleHead = strlen($searchModuleHead);
        foreach ($list as $i => $class) {
            $moduleFullName = '\\' . $class;
            $moduleNamespace = preg_replace('/(.+\W)\w+/', '$1', $class);
            $moduleName = preg_replace('/.+\W(\w+)/', '$1', $class);
            $this->getMetrologyInstance()->addLog('Find on list ' . $moduleFullName . ' (' . $moduleName . ')', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9a744ec7');
            // Ne regarde que les classes qui sont des modules d'après le nom.
            if (substr('\\' . $class, 0, $sizeModuleHead) == $searchModuleHead
                && ($moduleNamespace == $this->_applicationNamespace || $moduleNamespace == 'Nebule\\Modules\\')
                && $class != 'Nebule\\Library\\Modules'
                && $class != 'Modules'
                && $class != $searchModuleHead . 's'
                && !isset($this->_listModulesName[$moduleName])
            ) {
                $this->getMetrologyInstance()->addTime();
                $this->getMetrologyInstance()->addLog('New ' . $moduleFullName, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5703836f');
                $instance = new $class($this->_applicationInstance);

                $this->_listModulesEnabled[$moduleFullName] = false; // @todo à revoir...

                // Vérifie si c'est une dépendance de la classe Modules.
                if (is_a($instance, 'Nebule\\Library\\Modules')) {
                    $this->getMetrologyInstance()->addLog('Loaded module ' . $moduleFullName, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '130bc586');
                    $instance->initialisation();
                    $this->_listModulesName[$moduleName] = $moduleFullName;
                    $this->_listModulesInstance[$moduleFullName] = $instance;
                    $this->_listModulesEnabled[$moduleFullName] = true; // @todo à revoir...
                }
            }
        }

        $this->_metrologyInstance->addLog('Optionals modules loaded', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '0237217e');
    }

    /**
     * Lit si le module est activé.
     * Ne pend en charge que les modules activables, c'est-à-dire non intégrés à l'application.
     *
     * @param Node $module
     * @return boolean
     */
    public function getIsModuleActivated(Node $module): bool
    {
        $hashActivation = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MOD_ACTIVE);

        // Liste les modules reconnue par une entité locale.
        $linksList = $module->getLinksOnFields('', '', 'f', $module->getID(), $hashActivation, $module->getID());
        $link = null;
        foreach ($linksList as $link) {
            // Vérifie que le signataire est une entité locale.
            if ($this->_nebuleInstance->getIsLocalAuthority($link->getParsed()['bs/rs1/eid'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Liste les noms des modules disponibles.
     *
     * @return array of string
     */
    public function getModulesListNames(): array
    {
        return $this->_listModulesName;
    }

    /**
     * Liste les instances des modules disponibles. Les noms sont ceux générés par getType().
     *
     * @return array of Modules
     */
    public function getModulesListInstances(): array
    {
        return $this->_listModulesInstance;
    }

    /**
     * Liste les ID des modules disponibles.
     *
     * @return array of string
     */
    public function getModulesListID(): array
    {
        return $this->_listModulesID;
    }

    /**
     * Liste les ID de référence des modules disponibles.
     *
     * @return array of string
     */
    public function getModulesListRID(): array
    {
        return $this->_listModulesRID;
    }

    /**
     * Liste les signataires des modules disponibles (RID).
     *
     * @return array of string
     */
    public function getModulesListSignersRID(): array
    {
        return $this->_listModulesSignerRID;
    }

    /**
     * Liste la validité des modules disponibles.
     *
     * @return array of string
     */
    public function getModulesListValid(): array
    {
        return $this->_listModulesValid;
    }

    /**
     * Liste l'activation des modules disponibles.
     *
     * @return array of string
     */
    public function getModulesListEnabled(): array
    {
        return $this->_listModulesEnabled;
    }

    /**
     * Vérifie si le module est chargé. Le module est recherché sur le nom de sa classe.
     * Si non trouvé, retourne false.
     *
     * @param string $name
     * @return boolean
     */
    public function isModuleLoaded(string $name): bool
    {
        if (!$this->_useModules)
            return false;

        if ($name == ''
            || substr($name, 0, 6) != 'Module'
            || $name == 'Modules'
        )
            return false;

        $classes = get_declared_classes();

        $fullname = substr($this->_applicationNamespace, 1) . '\\' . $name;
        if (in_array($fullname, $classes))
            return true;
        return false;
    }

    /**
     * Retourne le module en cours d'utilisation.
     * Si les modules ne sont pas utilisés, retourne false.
     *
     * @return Modules|null
     */
    public function getCurrentModuleInstance(): ?Modules
    {
        if (!$this->_useModules)
            return null;

        if ($this->_currentModuleInstance != null
            && is_a($this->_currentModuleInstance, 'Nebule\Library\Modules')
        )
            return $this->_currentModuleInstance;

        $result = null;
        foreach ($this->_listModulesInstance as $module) {
            if ($module->getCommandName() == $this->_displayInstance->getCurrentDisplayMode()) {
                $result = $module;
                break;
            }
        }
        return $result;
    }

    /**
     * Return the instance of a module with this name.
     * Can use long name (with namespace) or short name.
     *
     * If not found, return false... but sure this will be a problem after...
     *
     * @param string $name
     * @return Modules|null
     */
    public function getModule(string $name): ?Modules
    {
        if (!$this->_useModules || $name == '')
            return null;

        // Try with long name.
        if (isset($this->_listModulesInstance[$name]))
        {
            $this->_metrologyInstance->addLog('Module ' . $name . ' found', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '2446015f');
            return $this->_listModulesInstance[$name];
        }
        // Search on list with short name
        if (!isset($this->_listModulesName[$name]))
            return null;
        $moduleName = $this->_listModulesName[$name];
        // Try with long name extract from short name.
        if (isset($this->_listModulesInstance[$moduleName]))
        {
            $this->_metrologyInstance->addLog('Module ' . $name . ' found (' . $moduleName . ')', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b44f071b');
            return $this->_listModulesInstance[$moduleName];
        }
        // If not... bad day for the reste!
        $this->_metrologyInstance->addLog('Module ' . $name . ' not found', Metrology::LOG_LEVEL_ERROR, __METHOD__, '82cd76b7');
        return null;
    }


    /**
     * Routage.
     */
    public function router(): void
    {
        global $applicationTraductionInstance, $applicationDisplayInstance, $applicationActionInstance;

        $this->_metrologyInstance->addLog('Running application', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'cd5ec83d');

        if ($this->_askDownload) {
            $this->_download();
        } else {
            // Affichage.
            $this->_metrologyInstance->addLog('Running display', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '13cb1fd7');

            // Récupère les instances.
            $this->_traductionInstance = $applicationTraductionInstance;
            $this->_displayInstance = $applicationDisplayInstance;
            $this->_actionInstance = $applicationActionInstance;

            // Affichage !
            $this->_displayInstance->display();

            $this->_metrologyInstance->addLog('End display', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '07edae7d');
        }
    }



    /*
	 * Tests de sécurité génériques.
	 */
    /**
     * Etat général de la sécurité.
     * Si au moins un des états de sécurité change, l'état général prend la valeur la plus critique des états.
     *
     * @var string
     */
    protected $_checkSecurityAll = "OK";

    /**
     * Retourne l'état de sécurité général.
     *
     * @return string
     */
    public function getCheckSecurityAll(): string
    {
        return $this->_checkSecurityAll;
    }

    /**
     * Fait un état complet de la sécurité.
     *
     * Nécessite la métrologie et la traduction.
     *
     * @return void
     */
    public function checkSecurity(): void
    {
        $this->_checkSecurity();
    }

    /**
     * Fait un état complet de la sécurité.
     *
     * Nécessite la métrologie et la traduction.
     *
     * @return void
     */
    protected function _checkSecurity(): void
    {
        $this->_checkSecurityBootstrap();
        $this->_checkSecurityCryptoHash();
        $this->_checkSecurityCryptoSym();
        $this->_checkSecurityCryptoAsym();
        $this->_checkSecuritySign();
        $this->_checkSecurityURL();

        $this->_checkSecurityAll = 'OK';

        if ($this->_checkSecurityBootstrap == 'WARN'
            || $this->_checkSecurityCryptoHash == 'WARN'
            || $this->_checkSecurityCryptoSym == 'WARN'
            || $this->_checkSecurityCryptoAsym == 'WARN'
            || $this->_checkSecuritySign == 'WARN'
            || $this->_checkSecurityURL == 'WARN'
        ) {
            $this->_checkSecurityAll = 'WARN';
            $this->_metrologyInstance->addLog('general security WARN', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'bb110e27');
        }

        if ($this->_checkSecurityBootstrap == 'ERROR'
            || $this->_checkSecurityCryptoHash == 'ERROR'
            || $this->_checkSecurityCryptoSym == 'ERROR'
            || $this->_checkSecurityCryptoAsym == 'ERROR'
            || $this->_checkSecuritySign == 'ERROR'
            || $this->_checkSecurityURL == 'ERROR'
        ) {
            $this->_checkSecurityAll = 'ERROR';
            $this->_metrologyInstance->addLog('general security ERROR', Metrology::LOG_LEVEL_ERROR, __METHOD__, '7f72506b');
        }
    }

    // Test de consistance du bootstrap.

    /**
     * Etat de sécurité du bootstrap.
     *
     * @var string
     */
    protected $_checkSecurityBootstrap = "ERROR";
    /**
     * Message de l'état de sécurité du bootstrap.
     *
     * @var string
     */
    protected $_checkSecurityBootstrapMessage = ":::act_chk_errBootstrap";

    /**
     * Retourne l'état de sécurité du bootstrap.
     *
     * @return string
     */
    public function getCheckSecurityBootstrap(): string
    {
        return $this->_checkSecurityBootstrap;
    }

    /**
     * Retourne le message de l'état de sécurité du bootstrap.
     *
     * @return string
     */
    public function getCheckSecurityBootstrapMessage(): string
    {
        return $this->_checkSecurityBootstrapMessage;
    }

    /**
     * Recherche l'état de sécurité du bootstrap.
     *
     * @return void
     */
    protected function _checkSecurityBootstrap(): void
    {

        $this->_checkSecurityBootstrap = 'OK';
        $this->_checkSecurityBootstrapMessage = "OK";

/*        $this->_checkSecurityBootstrap = 'ERROR';
        $data = file_get_contents(nebule::NEBULE_BOOTSTRAP_FILE);
        $hash = $this->_nebuleInstance->getCryptoInstance()->hash($data);

        // Recherche les liens de validation.
        $hashRef = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_BOOTSTRAP);
        $object = $this->_nebuleInstance->newObject($hashRef);
        $links = $object->getLinksOnFields('', '', 'f', $hashRef, $hash, $hashRef);

        // Trie sur les autorités locales.
        $ok = false;
        foreach ($links as $link) {
            foreach ($this->_nebuleInstance->getLocalAuthorities() as $autority) {
                if ($link->getParsed()['bs/rs1/eid'] == $autority) {
                    $ok = true;
                    break 2;
                }
            }
        }
        unset($data, $hash, $object, $links, $link);

        if ($ok) {
            $this->_checkSecurityBootstrap = 'OK';
            $this->_checkSecurityBootstrapMessage = "OK";
            $this->_metrologyInstance->addLog('SECURITY OK Bootstrap', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'a8578fbf');
        }*/
        // Modification pour le mode rescue afin de permettre un déverrouillage sur un boostrap inconnu. Le mode rescue est dangereux. @todo bof...
        /*		if ( $this->_nebuleInstance->getModeRescue()
				&& ! $ok
			)
		{
			$this->_checkSecurityBootstrap = 'WARN';
			$this->_checkSecurityBootstrapMessage = ":::act_chk_errBootstrap";
			$this->_metrologyInstance->addLog('SECURITY WARN Bootstrap', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
		}*/
    }

    // Test de la crypto de prise d'empreinte.

    /**
     * Etat de sécurité des fonctions de prise d'empreinte cryptographique.
     *
     * @var string
     */
    protected $_checkSecurityCryptoHash = 'WARN';
    /**
     * Message de l'état de sécurité des fonctions de prise d'empreinte cryptographique.
     *
     * @var string
     */
    protected $_checkSecurityCryptoHashMessage = 'HASH Unchecked';

    /**
     * Retourne l'état de sécurité des fonctions de prise d'empreinte cryptographique.
     *
     * @return string
     */
    public function getCheckSecurityCryptoHash(): string
    {
        return $this->_checkSecurityCryptoHash;
    }

    /**
     * Retourne le message de l'état de sécurité des fonctions de prise d'empreinte cryptographique.
     *
     * @return string
     */
    public function getCheckSecurityCryptoHashMessage(): string
    {
        return $this->_checkSecurityCryptoHashMessage;
    }

    /**
     * Recherche l'état de sécurité des fonctions de prise d'empreinte cryptographique.
     *
     * @return void
     */
    protected function _checkSecurityCryptoHash(): void
    {
        if (true || $this->_nebuleInstance->getCryptoInstance()->checkHashFunction()) { // TODO
            $this->_checkSecurityCryptoHash = 'OK';
            $this->_checkSecurityCryptoHashMessage = 'OK';
            $this->_metrologyInstance->addLog('SECURITY OK Hash Crypto', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '46f04cd0');
        } else {
            $this->_checkSecurityCryptoHash = 'ERROR';
            $this->_checkSecurityCryptoHashMessage = ':::act_chk_errCryptHash';
            $this->_metrologyInstance->addLog('SECURITY ERROR Hash Crypto', Metrology::LOG_LEVEL_ERROR, __METHOD__, '3b3440f7');
        }
    }

    // Test de la crypto symétrique.

    /**
     * Etat de sécurité des fonctions cryptographiques symétriques.
     *
     * @var string
     */
    protected $_checkSecurityCryptoSym = 'WARN';
    /**
     * Message de l'état de sécurité des fonctions cryptographiques symétriques.
     *
     * @var string
     */
    protected $_checkSecurityCryptoSymMessage = 'SYM Unchecked';

    /**
     * Retourne l'état de sécurité des fonctions cryptographiques symétriques.
     *
     * @return string
     */
    public function getCheckSecurityCryptoSym(): string
    {
        return $this->_checkSecurityCryptoSym;
    }

    /**
     * Retourne le message de l'état de sécurité des fonctions cryptographiques symétriques.
     *
     * @return string
     */
    public function getCheckSecurityCryptoSymMessage(): string
    {
        return $this->_checkSecurityCryptoSymMessage;
    }

    /**
     * Recherche l'état de sécurité des fonctions cryptographiques symétriques.
     *
     * @return void
     */
    protected function _checkSecurityCryptoSym(): void
    {
        if (true || $this->_nebuleInstance->getCryptoInstance()->checkSymmetricFunction()) { // TODO
            $this->_checkSecurityCryptoSym = 'OK';
            $this->_checkSecurityCryptoSymMessage = 'OK';
            $this->_metrologyInstance->addLog('SECURITY OK Sym Crypto', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'acc2b1c1');
        } else {
            $this->_checkSecurityCryptoSym = 'ERROR';
            $this->_checkSecurityCryptoSymMessage = ':::act_chk_errCryptSym';
            $this->_metrologyInstance->addLog('SECURITY ERROR Sym Crypto', Metrology::LOG_LEVEL_ERROR, __METHOD__, '50a09db3');
        }
    }

    // Test de la crypto asymétrique.

    /**
     * Etat de sécurité des fonctions cryptographiques asymétriques.
     *
     * @var string
     */
    protected $_checkSecurityCryptoAsym = 'WARN';
    /**
     * Message de l'état de sécurité des fonctions cryptographiques asymétriques.
     *
     * @var string
     */
    protected $_checkSecurityCryptoAsymMessage = 'ASYM Unchecked';

    /**
     * Retourne l'état de sécurité des fonctions cryptographiques asymétriques.
     *
     * @return string
     */
    public function getCheckSecurityCryptoAsym(): string
    {
        return $this->_checkSecurityCryptoAsym;
    }

    /**
     * Retourne le message de l'état de sécurité des fonctions cryptographiques asymétriques.
     *
     * @return string
     */
    public function getCheckSecurityCryptoAsymMessage(): string
    {
        return $this->_checkSecurityCryptoAsymMessage;
    }

    /**
     * Recherche l'état de sécurité des fonctions cryptographiques asymétriques.
     *
     * @return void
     */
    protected function _checkSecurityCryptoAsym(): void
    {
        if (true || $this->_nebuleInstance->getCryptoInstance()->checkAsymmetricFunction()) { // TODO
            $this->_checkSecurityCryptoAsym = 'OK';
            $this->_checkSecurityCryptoAsymMessage = 'OK';
            $this->_metrologyInstance->addLog('SECURITY OK Asym Crypto', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0af33bed');
        } else {
            $this->_checkSecurityCryptoAsym = 'ERROR';
            $this->_checkSecurityCryptoAsymMessage = ':::act_chk_errCryptAsym';
            $this->_metrologyInstance->addLog('SECURITY ERROR Asym Crypto', Metrology::LOG_LEVEL_ERROR, __METHOD__, '12ba7b66');
        }
    }

    // Test de la signature.

    /**
     * Etat de sécurité des fonctions de signature.
     *
     * @var string
     */
    protected $_checkSecuritySign = 'WARN';
    /**
     * Message de l'état de sécurité des fonctions de signature.
     *
     * @var string
     */
    protected $_checkSecuritySignMessage = 'SIGN Unchecked';

    /**
     * Retourne l'état de sécurité des fonctions de signature.
     *
     * @return string
     */
    public function getCheckSecuritySign(): string
    {
        return $this->_checkSecuritySign;
    }

    /**
     * Retourne le message de l'état de sécurité des fonctions de signature.
     *
     * @return string
     */
    public function getCheckSecuritySignMessage(): string
    {
        return $this->_checkSecuritySignMessage;
    }

    /**
     * Recherche l'état de sécurité des fonctions de signature.
     *
     * @return void
     */
    protected function _checkSecuritySign(): void
    {
        if ($this->_nebuleInstance->getCryptoInstance()->checkFunction($this->_configurationInstance->getOptionAsString('cryptoAsymmetricAlgorithm'), Crypto::TYPE_ASYMMETRIC)) {
            $this->_checkSecuritySign = 'OK';
            $this->_checkSecuritySignMessage = 'OK';
            $this->_metrologyInstance->addLog('SECURITY OK Sign', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '148b111d');
        } else {
            $this->_checkSecuritySign = 'ERROR';
            $this->_checkSecuritySignMessage = ':::act_chk_errSigns';
            $this->_metrologyInstance->addLog('SECURITY ERROR Sign', Metrology::LOG_LEVEL_ERROR, __METHOD__, '70b97981');
        }
 /*       $this->_checkSecuritySign = 'WARN';
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckSignOnVerify')) {
            $this->_checkSecuritySign = 'WARN';
            $this->_checkSecuritySignMessage = ':::act_chk_warnSigns';
        } else {
            $validLink = 'nebule:link/2:0_0>020210714/l>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>5d5b09f6dcb2d53a5fffc60c4ac0d55fabdf556069d6631545f42aa6e3500f2e.sha2.256>8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>4fcc946ef03dff882c0b6a1717c99c0ce57639e99d1f52509e846874c98dad5abd28685c9d065b4ef0e9fefbbee217e91fc4a72ecac81712e1e2c14bd06612e71e9afdb09ef1c10e68117fe8edc4f93510318719d0a6d7436a1802cd38f814cba8503ef24d50aeca961825bc39b169acbe52240fa8528a44f387ee5dff0e096a2ab49a0b181fa688678540dfc409000104a6ab77c44a4495ac98d48f35658238c99f5b1f83d04c3309412ebf26b7b23c18bdde43b964ebb6b28b60393b4c343f567137461743153091039c07e35432fa7d0b46b729f65c11960cbda5cb78f3d8da52aaf662724e771125cce2fb99ef1409fbb23840872c6557fe63f2b25c8fc49b6b5663a44cdf2e829ffa9698cc121648136fd102333a556a97ac5b208a6b6fa584e239a35237fe9c38fd09fbe4c0580ca538d92c4e29d5e22ce4846df2563dc4cb39a599b92f22018b4973b768cf59cb8f517f3adae3ee21b7c43a812ec6c245fe548e6187a0e07ce6a0af38c40ccd24383216cbd312322e1583d5d358ccdc9911b67fdbf7d13b9f57a0a17a42f736be9dbd383fd9e7c0ce2589fbd6550a8e07ab90618302956a1bf69e76aaf3da829e1af4f7c7ceff169ce5e698ebe1987fa1b694c6b25130c0be5bbfdfe4a8594e54067abe235bf796cf455a84906d02ebc79e3feaa069db7c4adac872c104bfcbc08b2dfbcc3c9fd6aa465fb9d86c7f26.sha2.512';
            $invalidLink = 'nebule:link/2:0_0>020210714/l>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>5d5b09f6dcb2d53a5fffc60c4ac0d55fabdf556069d6631545f42aa6e3500f2e.sha2.256>8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>4fcc946ef03dff882c0b6a1717c99c0ce57639e99d1f52509e846874c98dad5abd28685c9d065b4ef0e9fefbbee217e91fc4a72ecac81712e1e2c14bd06612e71e9afdb09ef1c10e68117fe8edc4f93510318719d0a6d7436a1802cd38f814cba8503ef24d50aeca961825bc39b169acbe52240fa8528a44f387ee5dff0e096a2ab49a0b181fa688678540dfc409000104a6ab77c44a4495ac98d48f35658238c99f5b1f83d04c3309412ebf26b7b23c18bdde43b964ebb6b28b60393b4c343f567137461743153091039c07e35432fa7d0b46b729f65c11960cbda5cb78f3d8da52aaf662724e771125cce2fb99ef1409fbb23840872c6557fe63f2b25c8fc49b6b5663a44cdf2e829ffa9698cc121648136fd102333a556a97ac5b208a6b6fa584e239a35237fe9c38fd09fbe4c0580ca538d92c4e29d5e22ce4846df2563dc4cb39a599b92f22018b4973b768cf59cb8f517f3adae3ee21b7c43a812ec6c245fe548e6187a0e07ce6a0af38c40ccd24383216cbd312322e1583d5d358ccdc9911b67fdbf7d13b9f57a0a17a42f736be9dbd383fd9e7c0ce2589fbd6550a8e07ab90618302956a1bf69e76aaf3da829e1af4f7c7ceff169ce5e698ebe1987fa1b694c6b25130c0be5bbfdfe4a8594e54067abe235bf796cf455a84906d02ebc79e3feaa069db7c4adac872c104bfcbc08b2dfbcc3c9fd6aa465fb9d86c7f27.sha2.512';
            $instanceValidLink = $this->_nebuleInstance->newLink($validLink);
            $instanceInvalidLink = $this->_nebuleInstance->newLink($invalidLink);

            if ($instanceValidLink->getSigned() === false
                || $instanceInvalidLink->getSigned() === true
            ) {
                $this->_checkSecuritySign = 'ERROR';
                $this->_checkSecuritySignMessage = ':::act_chk_errSigns';
                $this->_metrologyInstance->addLog('SECURITY ERROR Sign', Metrology::LOG_LEVEL_ERROR, __METHOD__, '70b97981');
            } else {
                $this->_checkSecuritySign = 'OK';
                $this->_checkSecuritySignMessage = 'OK';
                $this->_metrologyInstance->addLog('SECURITY OK Sign', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '148b111d');
            }
            unset($validLink, $instanceValidLink, $invalidLink, $instanceInvalidLink);
        }*/
    }

    // Test de l'URL.

    /**
     * Etat de sécurité de l'URL.
     *
     * @var string
     */
    protected $_checkSecurityURL = 'OK';
    /**
     * Message de l'état de sécurité de l'URL.
     *
     * @var string
     */
    protected $_checkSecurityURLMessage = 'OK';

    /**
     * Retourne l'état de sécurité de l'URL.
     *
     * @return string
     */
    public function getCheckSecurityURL(): string
    {
        return $this->_checkSecurityURL;
    }

    /**
     * Retourne le message de l'état de sécurité de l'URL.
     *
     * @return string
     */
    public function getCheckSecurityURLMessage(): string
    {
        return $this->_checkSecurityURLMessage;
    }

    /**
     * Recherche l'état de sécurité de l'URL.
     *
     * @return void
     */
    protected function _checkSecurityURL(): void
    {
        $this->_checkSecurityURL = 'OK';
        if ($this->_urlProtocol == 'http'
            && $this->_configurationInstance->getOptionUntyped('displayUnsecureURL')
        ) {
            $this->_checkSecurityURL = 'WARN';
            $this->_checkSecurityURLMessage = $this->_traductionInstance->getTraduction('Connexion non sécurisée')
                . '. ' . $this->_traductionInstance->getTraduction('Essayer plutôt')
                . ' <a href="https://' . $this->_urlHost . '/' . $this->_urlBasename . '">https://' . $this->_urlHost . '/</a>';
            $this->_metrologyInstance->addLog('SECURITY WARN URL', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
        } else {
            $this->_metrologyInstance->addLog('SECURITY OK URL', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        }
    }


    
    /**
     * Marque un objet.
     *
     * @param string $object
     * @return void
     */
    public function setMarkObject(string $object): void
    {
        $list = $this->_nebuleInstance->getSessionStore('objectsMarkList');
        if ($list === false)
            $list = array();
        $list[$object] = true;
        $this->_nebuleInstance->setSessionStore('objectsMarkList', $list);
        unset($list);
    }

    /**
     * Supprime la marque d'un objet.
     *
     * @param string $object
     * @return void
     */
    public function setUnmarkObject(string $object): void
    {
        $list = $this->_nebuleInstance->getSessionStore('objectsMarkList');
        if ($list === false)
            return;
        unset($list[$object]);
        $this->_nebuleInstance->setSessionStore('objectsMarkList', $list);
        unset($list);
    }

    /**
     * Supprime les marques de tous les objets.
     * 
     * @return void
     */
    public function setUnmarkAllObjects(): void
    {
        $list = array();
        $this->_nebuleInstance->setSessionStore('objectsMarkList', $list);
        unset($list);
    }

    /**
     * Lit si un objet est marqué.
     *
     * @param string $object
     * @return boolean
     */
    public function getMarkObject(string $object): bool
    {
        $list = $this->_nebuleInstance->getSessionStore('objectsMarkList');
        if ($list === false)
            return false;
        if (isset($list[$object]))
            return true;
        return false;
    }

    /**
     * Lit la liste des objets marqués.
     * @return array
     */
    public function getMarkObjectList(): array
    {
        $list = $this->_nebuleInstance->getSessionStore('objectsMarkList');
        if ($list === false)
            $list = array();
        return $list;
    }


    /**
     * Affiche la partie du menu de la documentation.
     * Inclu les modules.
     *
     * @return void
     */
    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#oa">OA / Application</a>
            <ul>
                <li><a href="#oaf">OAF / Fonctionnement</a></li>
                <li><a href="#oan">OAN / Nommage</a></li>
                <li><a href="#oap">OAP / Protection</a></li>
                <li><a href="#oad">OAD / Dissimulation</a></li>
                <li><a href="#oal">OAL / Liens</a></li>
                <li><a href="#oac">OAC / Création</a></li>
                <li><a href="#oas">OAS / Stockage</a></li>
                <li><a href="#oat">OAT / Transfert</a></li>
                <li><a href="#oar">OAR / Réservation</a></li>
                <li><a href="#oai">OAI / Interface</a>
                    <ul>
                        <li><a href="#oain">OAIN / Nommage</a></li>
                        <li><a href="#oaip">OAIP / Protection</a></li>
                        <li><a href="#oaid">OAID / Dissimulation</a></li>
                        <li><a href="#oail">OAIL / Liens</a></li>
                        <li><a href="#oaic">OAIC / Création</a>
                            <ul>
                                <li><a href="#oaicr">OAICR / Référence</a>
                                <li><a href="#oaicc">OAICC / Code</a>
                                <li><a href="#oaice">OAICE / Enregistrement</a>
                            </ul>
                        </li>
                        <li><a href="#oaiu">OAIU / Mise à Jour</a></li>
                        <li><a href="#oais">OAIS / Stockage</a></li>
                        <li><a href="#oait">OAIT / Transfert</a></li>
                        <li><a href="#oair">OAIR / Réservation</a></li>
                        <li><a href="#oaig">OAIG / Applications d'Interfaçage Génériques</a>
                            <ul>
                                <li><a href="#oaigb">OAIGB / Nb - bootstrap</a></li>
                                <li><a href="#oaiga">OAIGA / Au - authen</a></li>
                                <li><a href="#oaigs">OAIGS / Sy - sylabe</a></li>
                                <li><a href="#oaigk">OAIGK / Kl - klicty</a></li>
                                <li><a href="#oaigm">OAIGM / Me - messae</a></li>
                                <li><a href="#oaigo">OAIGO / No - option</a></li>
                                <li><a href="#oaigu">OAIGU / Nu - upload</a></li>
                            </ul>
                        </li>
                        <li><a href="#oaio">OAIO / Implémentation des Options</a></li>
                        <li><a href="#oaia">OAIA / Implémentation des Actions</a></li>
                    </ul>
                </li>
                <li><a href="#oab">OAB / Bootstrap</a>
                    <ul>
                        <li><a href="#oabd">OABD / Description</a></li>
                        <li><a href="#oabi">OABI / Installation</a></li>
                        <li><a href="#oabf">OABF / Premier démarrage</a></li>
                        <li><a href="#oabm">OABM / Commandes</a></li>
                        <li><a href="#oabc">OABC / Configuration</a></li>
                        <li><a href="#oabb">OABB / Interruption</a></li>
                        <li><a href="#oabe">OABE / Applications externes</a></li>
                        <li><a href="#oaba">OABA / Applications intégrées</a>
                            <ul>
                                <li><a href="#oaba0">OABA0 / Application 0</a></li>
                                <li><a href="#oaba1">OABA1 / Application 1</a></li>
                                <li><a href="#oaba2">OABA2 / Application 2</a></li>
                                <li><a href="#oaba3">OABA3 / Application 3</a></li>
                                <li><a href="#oaba4">OABA4 / Application 4</a></li>
                                <li><a href="#oaba5">OABA5 / Application 5</a></li>
                                <li><a href="#oaba6">OABA6 / Application 6</a></li>
                                <li><a href="#oaba7">OABA7 / Application 7</a></li>
                                <li><a href="#oaba8">OABA8 / Application 8</a></li>
                                <li><a href="#oaba9">OABA9 / Application 9</a></li>
                            </ul>
                        </li>
                        <li><a href="#oabn">OABN / Fonctionnement nominal</a></li>
                    </ul>
                </li>
                <li><a href="#oal">OAL / Librairie</a>
                    <ul>
                        <li><a href="#oald">OALD / Description</a></li>
                        <li><a href="#oalc">OALC / Configuration</a></li>
                    </ul>
                </li>
                <?php \Nebule\Library\Modules::echoDocumentationTitles(); ?>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     * Inclu les modules.
     *
     * @return void
     */
    static public function echoDocumentationCore(): void
    {
        ?>

        <h2 id="oa">OA / Application</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Une application permet d'interagir avec les objets et liens.</p>
        <p>Une application qui ne fait que lire des objets et liens, ou retransmettre des liens déjà signés, est dite
            passive. Si l'application à la capacité de générer des liens signés, donc avec une entité déverrouillée,
            alors elle est dite active.</p>
        <p>Si l'entité d'une instance d'application est par défaut et automatiquement déverrouillée, donc active, alors
            c'est aussi un robot. Le déverrouillage de cette entité peut cependant bénéficier de protections
            particulières.</p>

        <h3 id="oaf">OAF / Fonctionnement</h3>
        <p>Dans la construction du code, il y a quatre niveaux. Chaque niveau de code est constitué d’un et un seul
            objet nebule ou fichier utilisé. Une seule application est utilisée à un instant donné, mais il peut y avoir
            plusieurs modules utilisés par l’application. Les niveaux :</p>
        <ul>
            <li>le bootstrap, fichier ;</li>
            <li>la librairie en PHP orienté objet, objet ;</li>
            <li>une application au choix, objet ou intégré ;</li>
            <li>des modules au choix, facultatifs, objets.</li>
        </ul>
        <p>Les applications sont toutes construites sur le même modèle et dépendent (extend) toutes des mêmes classes de
            l’application de référence dans la librairie nebule.</p>
        <p>Chaque application doit mettre en place les variables personnalisées :</p>
        <ul>
            <li>$applicationName</li>
            <li>$applicationSurname</li>
            <li>$applicationDescription</li>
            <li>$applicationVersion</li>
            <li>$applicationLicence</li>
            <li>$applicationAuthor</li>
            <li>$applicationWebsite</li>
        </ul>
        <p>Chaque application doit mettre en place les classes :</p>
        <ul>
            <li>Application</li>
            <li>Display</li>
            <li>Action</li>
            <li>Traduction</li>
        </ul>
        <p>Elles dépendent respectivement des classes de l’application de référence Applications, Displays, Actions et
            Traductions dans la librairie nebule.</p>

        <h3 id="oan">OAN / Nommage</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oap">OAP / Protection</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oad">OAD / Dissimulation</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oal">OAL / Liens</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oac">OAC / Création</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oas">OAS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="oat">OAT / Transfert</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oar">OAR / Réservation</h3>
        <p>Les objets réservés spécifiquement pour les applications :</p>
        <ul>
            <li>nebule/objet/applications</li>
        </ul>

        <h3 id="oai">OAI / Interface</h3>
        <p>Une interface est un programme dédié aux interactions entre deux milieux différents.</p>
        <p>Une interface permet à une entité, c'est-à-dire un utilisateur ou un robot, d'interagir avec une application.
            Cela peut être vu comme une extension de l'application.</p>
        <p>Les applications développées dans le cadre de <i>nebule</i> :</p>
        <ul>
            <li><b>bootstrap</b> : le chargeur initial de la librairie et des applications, <a href="#oaigb">OAIGB</a>,
                <a href="http://blog.nebule.org">blog.nebule.org</a>.
            </li>
            <li><b>autent</b> : l’application d'authentification interactive, <a href="#oaiga">OAIGA</a>,
                <a href="http://blog.nebule.org">blog.nebule.org</a>.
            </li>
            <li><b>sylabe</b> : l’application de référence des possibilités de nebule, <a href="#oaigs">OAIGS</a>,
                <a href="http://blog.sylabe.org">blog.sylabe.org</a>.
            </li>
            <li><b>klicty</b> : l’application de partage d’objets à durée limitée, <a href="#oaigk">OAIGK</a>,
                <a href="http://blog.klicty.org">blog.klicty.org</a>.
            </li>
            <li><b>messae</b> : l’application de gestion des conversations et messages, <a href="#oaigm">OAIGM</a>,
                <a href="http://blog.messae.org">blog.messae.org</a>.
            </li>
            <li><b>neblog</b> : l’application de gestion des web logs, <a href="#oaign">OAIGN</a>,
                <a href="http://blog.neblog.org">blog.neblog.org</a>.
            </li>
            <li><b>qantion</b> : l’application de gestion des monnaies, <a href="#oaigq">OAIGQ</a>,
                <a href="http://blog.qantion.org">blog.qantion.org</a>.
            </li>
            <li><b>option</b> : l’application de gestion des options, <a href="#oaigo">OAIGO</a>,
                <a href="http://blog.nebule.org">blog.nebule.org</a>.
            </li>
            <li><b>upload</b> : l’application de chargement de mises à jour, <a href="#oaigu">OAIGU</a>,
                <a href="http://blog.nebule.org">blog.nebule.org</a>.
            </li>
        </ul>
        <div class="layout-main">
            <div class="layout-content">
                <div id="appslist">
                    <div class="apps" style="background:#000000;"><span class="appstitle">Nb</span><br/><span
                            class="appsname">break</span></div>
                    <div class="apps" style="background:#000000;"><span class="appstitle">N0</span><br/><span
                            class="appsname">defolt</span></div>
                    <div class="apps" style="background:#333333;"><span class="appstitle">N3</span><br/><span
                            class="appsname">doctech</span></div>
                    <div class="apps" style="background:#902060;"><span class="appstitle">Au</span><br/><span
                                class="appsname">autent</span></div>
                    <div class="apps" style="background:#c02030;"><span class="appstitle">Sy</span><br/><span
                                class="appsname">sylabe</span></div>
                    <div class="apps" style="background:#d0b020;"><span class="appstitle">Kl</span><br/><span
                                class="appsname">klicty</span></div>
                    <div class="apps" style="background:#2060a0;"><span class="appstitle">Me</span><br/><span
                            class="appsname">messae</span></div>
                    <div class="apps" style="background:#05c3dd;"><span class="appstitle">Ne</span><br/><span
                            class="appsname">neblog</span></div>
                    <div class="apps" style="background:#20a040;"><span class="appstitle">Qa</span><br/><span
                                class="appsname">qantion</span></div>
                    <div class="apps" style="background:#555555;"><span class="appstitle">Op</span><br/><span
                            class="appsname">option</span></div>
                    <div class="apps" style="background:#666666;"><span class="appstitle">Up</span><br/><span
                            class="appsname">upload</span></div>
                </div>
            </div>
        </div>

        <h4 id="oain">OAIN / Nommage</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oaip">OAIP / Protection</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oaid">OAID / Dissimulation</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oail">OAIL / Liens</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oaic">OAIC / Création</h4>
        <p>La création d'une application se passe en trois parties. Il faut créer un objet de référence de la nouvelle
            application. Il faut lui affecter un objet de code, objet de code qui sera mise à jour plus tard. Enfin il
            faut enregistrer l'application pour la rendre disponible.</p>

        <h5 id="oaicr">OAICR / Référence</h5>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Cette partie est à faire au début lorsque l’on veut rendre visible et utiliser la nouvelle application. Elle
            ne sera plus refaite par la suite. Le but est de permettre au <i>bootstrap</i> de retrouver l’application et
            de permettre à l’utilisateur de la sélectionner.</p>
        <div class="layout-main">
            <div class="layout-content">
                <div id="appslist">
                    <div class="apps" style="background:#c02030;"><span class="appstitle">Sy</span><br/><span
                            class="appsname">sylabe</span></div>
                </div>
            </div>
        </div>
        <p>On définit un objet de référence, un objet qui sera virtuel puisqu'il n’aura pas de contenu.
            Sa seule contrainte forte est que l’empreinte est exprimée en hexadécimal. Par convention, il est recommandé
            que la taille de l’empreinte des objets virtuels soit comprise en 129 et 191 bits. Cet objet de référence
            peut être généré aléatoirement ou au contraire avoir un contenu pré-déterminé, ou mixer les deux.</p>
        <p>Chaque application doit avoir un objet de référence qui lui est réservé. Utiliser l’objet de référence d’une
            autre application revient à tenter de mettre à jour l’application, non à en faire une nouvelle.</p>
        <p>Par exemple avec la commande : <code>openssl rand -hex 36</code></p>
        <p>Cela donne une valeur, notre objet de référence, qui ressemble à ça :</p>
        <code>f3c2e389d0ec1bd3f279410748ba352c205ca354cec396a5f9fa0f8c0dcc1f9900bfd9</code>
        <p>Pour finir avec l’objet de référence, la couleur de l’application dépend de lui. Cette couleur étant
            constituée des 6 premiers caractères de l’empreinte de l’objet de référence, il est possible de choisir
            volontairement cette couleur.</p>
        <p>L’application doit avoir un nom et un préfixe. Ces deux propriétés sont utilisées par le bootstrap pour
            l’affichage des applications dans l’application de sélection des applications.</p>
        <p>Le nom est libre, mais si il est trop grand, il sera tronqué pour tenir dans le carré de l’application.</p>
        <p>Le préfixe doit faire 2 caractères. Si ce sont des lettres, systématiquement la première sera transformée en
            majuscule et la deuxième en minuscule.</p>
        <p>Par exemple :</p>
        <ul>
            <li>sylabe</li>
            <li>Sy</li>
        </ul>

        <p>Lorsque l’on a défini notre objet de référence et le nom de l’application, on crée les liens.</p>
        <p>Liste des liens à générer lors de la création d'une application interface.</p>
        <ul>
            <li>Le lien de hash :
                <ul>
                    <li>REQ : <code>l</code></li>
                    <li>NID1 : ID de l'application</li>
                    <li>NID2 : hash du nom de l’algorithme de prise d’empreinte</li>
                    <li>NID3 : hash(‘nebule/objet/hash’)</li>
                </ul>
            </li>
            <li>Le lien de définition de type application :
                <ul>
                    <li>REQ : <code>l</code></li>
                    <li>NID1 : ID de l'application</li>
                    <li>NID2 : hash(‘nebule/objet/interface/web/php/applications’)</li>
                    <li>NID3 : hash(‘nebule/objet/type’)</li>
                </ul>
            </li>
            <li>Le lien de nommage long de l'application :
                <ul>
                    <li>REQ : <code>l</code></li>
                    <li>NID1 : ID de l'application</li>
                    <li>NID2 : hash(nom long de l'application)</li>
                    <li>NID3 : hash(‘nebule/objet/nom’)</li>
                </ul>
            </li>
            <li>Le lien de nommage court de l'application :
                <ul>
                    <li>REQ : <code>l</code></li>
                    <li>NID1 : ID de l'application</li>
                    <li>NID2 : hash(nom court de l'application)</li>
                    <li>NID3 : hash(‘nebule/objet/surnom’)</li>
                </ul>
            </li>
        </ul>
        <p>Pour que ces liens soient reconnus par le bootstrap, ils doivent tous être signés d’une autorité locale.</p>

        <h5 id="oaicc">OAICC / Code</h5>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La création de la base d’une application est simple, il suffit de copier le modèle d’application dans un
            nouveau fichier et dans un premier temps d’adapter les variables et la fonction d’affichage.</p>
        <p>Ensuite, ce fichier doit être nébulisé, c'est-à-dire transféré vers le serveur comme nouvel objet.</p>
        <p>Une fois nébulisé, l’objet peut être déclaré par un lien comme code pour l’objet de référence de
            l’application. Ainsi, l'objet référence point un code à exécuter.</p>
        <p>Le lien de pointage du code :</p>
        <ul>
            <li>REQ : <code>f</code></li>
            <li>NID1 :</li>
            <li>NID2 :</li>
            <li>NID3 :</li>
        </ul>

        <p>Exemple de modèle d'application :</p>
        <pre>
&lt;?php
// ------------------------------------------------------------------------------------------
$applicationName		= 'Share';
$applicationSurname		= 'Share All';
$applicationDescription	= 'Web page for sharing all you want.';
$applicationVersion		= '020210410';
$applicationLicence		= 'GNU GPL 2021';
$applicationAuthor		= 'Me';
$applicationWebsite		= 'notme.nebule.org';
// ------------------------------------------------------------------------------------------



/*
 ------------------------------------------------------------------------------------------
 /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
 ------------------------------------------------------------------------------------------

     .     [FR] Toute modification de ce code entrainera une modification de son empreinte
    / \           et entrainera donc automatiquement son invalidation !
   / V \   [EN] Any changes to this code will cause a chage in its footprint and therefore
  /__°__\         automatically result in its invalidation!
     N     [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo
     N            tanto lugar automáticamente a su anulación!
     N
     N                                                                       Projet nebule
 ----N-------------------------------------------------------------------------------------
 /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
 ------------------------------------------------------------------------------------------
 */



/**
 * Classe Application
 * @author Me
 *
 * Le coeur de l'application.
 *
 */
class Application extends Applications
{
	/**
	 * Constructeur.
	 *
	 * @param nebule $nebuleInstance
	 * @return void
	 */
	public function __construct(nebule $nebuleInstance)
	{
		$this->_nebuleInstance = $nebuleInstance;
	}

	// Tout par défaut.
}



/**
 * Classe Display
 * @author Me
 */
class Display extends Displays
{
	const DEFAULT_LOGO_BOOTSTRAP = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaX
HeAAABMElEQVR42u3ZQWqDQBTG8Tdp6VjEda5gyBnEeIkcxZ20mRuVrt0MIrhKyB3chFoIhULBalfZhCy6kBmI/7dVnPHHm3E+VC
LyKjOuhcy8AAAAAAAAAAAAAAAAAAAAAJhjPU7xkHEcX3xMXim1894Bvl5+qrHZAwAAAAAAfH+KfI6thP8CLAEAyAJkAbIAewAAAA
AAAFmADgAAALIAWYAswB4AAAAAAEAWoAMAAIAsMGEVRbEyxmz/c29VVYckSd5czOtBRDYuBrLWfmitP+M4XoZh+Hzrnr7vf621+z
zPbdu2P3cFICJSluUpCIKvLMvWt67XdX1M0/Td1cs7B7h0wjAMp2sEl23vFeB6OWitn1y3vXeAy3KIoui767rOGNM0TXP2cprkJM
hBCAAAAJhx/QGiUnc0nJCIeAAAAABJRU5ErkJggg==';

	/**
	 * Constructeur.
	 *
	 * @param Applications $applicationInstance
	 * @return void
	 */
	public function __construct(Applications $applicationInstance)
	{
		$this->_applicationInstance = $applicationInstance;
	}



	/**
	 * Affichage de la page.
	 */
	public function display()
	{
		global $applicationVersion, $applicationLicence, $applicationWebsite,
				$applicationName, $applicationSurname, $applicationAuthor;
		?>
&lt;!DOCTYPE html>
&lt;html>
	&lt;body>
		Hello
	&lt;/body>
&lt;/html>
&lt;?php
	}
}



/**
 * Classe Action
 * @author Me
 */
class Action extends Actions
{
	const ACTION_APPLY_DELAY = 5;
	/**
	 * Constructeur.
	 *
	 * @param Applications $applicationInstance
	 * @return void
	 */
	public function __construct(Applications $applicationInstance)
	{
		$this->_applicationInstance = $applicationInstance;
	}



	/**
	 * Traitement des actions génériques.
	 */
	public function genericActions()
	{
		$this->_metrology->addLog('Generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

		// Rien.

		$this->_metrology->addLog('Generic actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
	}



	/**
	 * Traitement des actions spéciales, qui peuvent être réalisées sans entité déverrouillée.
	 */
	public function specialActions()
	{
		$this->_metrology->addLog('Special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

		// Rien.

		$this->_metrology->addLog('Special actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
	}
}



/**
 * Classe Traduction
 * @author Me
 */
class Traduction extends Traductions
{
	/**
	 * Constructeur.
	 *
	 * @param Application $applicationInstance
	 * @return void
	 */
	public function __construct(Application $applicationInstance)
	{
		$this->_applicationInstance = $applicationInstance;
	}



	/**
	 * Initialisation de la table de traduction.
	 */
	protected function _initTable()
	{
		$this->_table['fr-fr']['::::INFO']='Information';
		$this->_table['en-en']['::::INFO']='Information';
		$this->_table['es-co']['::::INFO']='Information';
		$this->_table['fr-fr']['::::OK']='OK';
		$this->_table['en-en']['::::OK']='OK';
		$this->_table['es-co']['::::OK']='OK';
		$this->_table['fr-fr']['::::INFORMATION']='Message';
		$this->_table['en-en']['::::INFORMATION']='Message';
		$this->_table['es-co']['::::INFORMATION']='Mensaje';
		$this->_table['fr-fr']['::::WARN']='ATTENTION !';
		$this->_table['en-en']['::::WARN']='WARNING!';
		$this->_table['es-co']['::::WARN']='¡ADVERTENCIA!';
		$this->_table['fr-fr']['::::ERROR']='ERREUR !';
		$this->_table['en-en']['::::ERROR']='ERROR!';
		$this->_table['es-co']['::::ERROR']='¡ERROR!';

		$this->_table['fr-fr']['::::RESCUE']='Mode de sauvetage !';
		$this->_table['en-en']['::::RESCUE']='Rescue mode!';
		$this->_table['es-co']['::::RESCUE']='¡Modo de rescate!';
	}
}
</pre>

        <h5 id="oaice">OAICE / Enregistrement</h5>
        <p>Le lien d'enregistrement de l'application :</p>
        <ul>
            <li>Signature du lien</li>
            <li>Identifiant du signataire</li>
            <li>Horodatage</li>
            <li>action : <code>l</code></li>
            <li>source :</li>
            <li>cible :</li>
            <li>méta :</li>
        </ul>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oaiu">OAIU / Mise à Jour</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oais">OAIS / Stockage</h4>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h4 id="oait">OAIT / Transfert</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oair">OAIR / Réservation</h4>
        <p>Les objets réservés spécifiquement pour les applications :</p>
        <ul>
            <li>nebule/objet/interface/web/php/bootstrap</li>
            <li>nebule/objet/interface/web/php/bibliotheque</li>
            <li>nebule/objet/interface/web/php/applications</li>
            <li>nebule/objet/interface/web/php/applications/direct</li>
            <li>nebule/objet/interface/web/php/applications/active</li>
        </ul>

        <h4 id="oaig">OAIG / Applications d'Interfaçage Génériques</h4>
        <p>Ces applications sont développées dans le cadre de <i>nebule</i> et sont librement mises à disposition (sous
            license).</p>
        <p>Le nom de ces applications est toujours en minuscule.</p>

        <h5 id="oaigb">OAIGB / Nb - bootstrap</h5>
        <p>voir <a href="#oab">OAB</a></p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h5 id="oaiga">OAIGA / Au - authen</h5>
        <p>IID=9020606a70985a00f1cf73e6aed5cfd46399868871bd26d6c0bd7a202e01759c3d91b97e.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h5 id="oaigs">OAIGS / Sy - sylabe</h5>
        <p>IID=c02030d3b77c52b3e18f36ee9035ed2f3ff68f66425f2960f973ea5cd1cc0240a4d28de1.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h5 id="oaigk">OAIGK / Kl - klicty</h5>
        <p>IID=d0b02052a575f63a4e87ff320df443a8b417be1b99e8e40592f8f98cbd1adc58c221d501.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h5 id="oaigm">OAIGM / Me - messae</h5>
        <p>IID=2060a0d21853a42093f01d2e4809c2a5e9300b4ec31afbaf18af66ec65586d6c78b2823a.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h5 id="oaign">OAIGN / Ne - neblog</h5>
        <p>IID=05c3dd94a9ae4795c888cb9a6995d1e5a23b43816e2e7fb908b6841694784bc3ecda8adf.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h5 id="oaigq">OAIGQ / Qa - qantion</h5>
        <p>IID=20a04016698cd3c996fa69e90bbf3e804c582b8946a5d60e9880cdb24b36b5d376208939.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h5 id="oaigo">OAIGO / No - option</h5>
        <p>IID=555555712c23ff20740c50e6f15e275f695fe95728142c3f8ba2afa3b5a89b3cd0879211.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h5 id="oaigu">OAIGU / Nu - upload</h5>
        <p>IID=6666661d0923f08d50de4d70be7dc3014e73de3325b6c7b16efd1a6f5a12f5957b68336d.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oaio">OAIO / Implémentation des Options</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oaia">OAIA / Implémentation des Actions</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oab">OAB / Bootstrap</h3>
        <h4 id="oabd">OABD / Description</h4>
        <p>Le <i>bootstrap</i> est un programme autonome de <i>nebule</i> constitué d'un seul et unique fichier écrit en
            PHP.</p>
        <p>Il constitue le point d'entrée par défaut de toute connexion des utilisateurs ou robots vers l'instance,
            c'est-à-dire une localisation précise sur un serveur Web sur lequel on se connecte avec une URL dédiée.</p>
        <p>Il permet de gérer un certain nombre d'applications intégrées ou externes. Ces applications sont des
            interfaces entre les informations stockées dans les objets et reliées par des liens. En interagissant avec
            le <i>bootstrap</i>, il est possible d'accéder aux applications. Via une option, il est possible de modifier
            l'application par défaut afin de personnaliser une instance.</p>
        <p>Il est aussi en charge de trouver les dernières versions de la bibliothèque
            <?php echo \Nebule\Library\nebule::NEBULE_LICENCE_NAME; ?> et des applications de manière sécurisée.</p>

        <h4 id="oabi">OABI / Installation</h4>
        <p>Le <i>bootstrap</i> nécessite pour fonctionner un serveur Web avec PHP pré-installé. Il peut être hébergé sur
            le site principal, dans un sous-domaine ou un sous-dossier de site Web. Il nécessite le droit d'écriture sur
            le dossier le contenant au moins le temps du premier démarrage.</p>
        <p>Procédure :</p>
        <ul>
            <li>Installer un serveur Web (Linux/Apache) avec PHP. Aucun serveur de base de données n'est utilisé.</li>
            <li>Télécharger le fichier <i>bootstrap</i> sur le site Web
                <a href="<?php echo \Nebule\Library\nebule::NEBULE_LICENCE_LINK; ?>">
                <?php echo \Nebule\Library\nebule::NEBULE_LICENCE_LINK; ?></a>.
                <span style="color: red; font-weight: bold">FIXME téléchargement sécurisé !</span></li>
            <li>Positionner le fichier <i>bootstrap</i> dans le dossier Web du serveur Web. Au besoin, renommer le
                fichier en <i>index.php</i>.</li>
            <li>Donner les droits de lecture et d'écriture (755 groupe apache) sur le dossier Web contenant le fichier
                <i>bootstrap</i> et le droit de lecture sur le fichier <i>index.php</i> (644).</li>
            <li>Ouvrir un navigateur Web et donner en URL l'adresse du serveur Web.</li>
            <li>La partie premier démarrage du <i>bootstrap</i> est appelée. Voir <a href="#oabf">OABF</a>.</li>
            <li>Entrer un EID et une localisation pour le <i>puppetmaster</i> ou laisser celui par défaut. Valider.</li>
            <li>Entrer un EID et une localisation pour l'entité de subordination ou laisser celle par défaut.
                Valider.</li>
            <li>Noter l'empreinte EID de la nouvelle entité de l'instance et son mot de passe.</li>
            <li>Si tout se passe bien, on arrive sur l'application 1 de selection des applications.</li>
            <li>L'instance est prête.</li>
        </ul>
        <p>Après le premier démarrage, les droits de lecture sont suffisants sauf pour les dossiers <i>o</i> et <i>l</i>
            qui nécessitent le droit d'écriture.</p>
        <p>Il est possible sur une instance de ne laisser que les droits de lecture sur toute l'arborescence. Cependant,
            cela empêchera toute interaction nécessitant la création d'objets ou de liens. Il est préférable dans ce cas
            de positionner l'option <b>permitWrite</b> à <i>false</i> dans le fichier de configuration. Voir
            <a href="#cco">CCO</a> pour changer la configuration.</p>

        <h4 id="oabf">OABF / Premier démarrage</h4>
        <p>Lors du premier démarrage (firstboot), c'est-à-dire lorsque l'on appelle le <i>bootstrap</i> via le serveur
            Web PHP, il va se charger de préparer l'environnement nécessaire au bon fonctionnement d'une instance
            <i>nebule</i>.
            Cela va inclure :</p>
        <ul>
            <li>La création d'un fichier <i><?php echo \Nebule\Library\nebule::NEBULE_ENVIRONMENT_FILE; ?></i> de
                configuration générique.</li>
            <li>La création d'un dossier <i><?php echo \Nebule\Library\nebule::NEBULE_LOCAL_OBJECTS_FOLDER; ?></i> pour
                stocker les objets.</li>
            <li>La création d'un dossier <i><?php echo \Nebule\Library\nebule::NEBULE_LOCAL_LINKS_FOLDER; ?></i> pour
                stocker les liens.</li>
            <li>La génération d'une entité locale, dite entité de l'instance.</li>
            <li>La création d'un fichier <i><?php echo \Nebule\Library\nebule::NEBULE_LOCAL_ENTITY_FILE; ?></i>
                contenant l'empreinte <i>EID</i> de l'entité de l'instance.</li>
            <li>La création de différents objets dans le dossier des objets.</li>
            <li>La création de différents liens dans le dossier des liens.</li>
        </ul>
        <p>Au besoin, un certain nombre de ces dossiers et fichiers peuvent être pré-initialisé lorsque l'on dépose le
            <i>bootstrap</i> sur le serveur Web. Mais cela n'est pas le fonctionnement normal.</p>

        <h4 id="oabm">OABM / Commandes</h4>
        <p>Il est possible d'interagir avec le <i>bootstrap></i> au moyen de commandes dans l'URL.</p>
        <p>Liste des commandes :</p>
        <ul>
            <li><b><?php echo LIB_ARG_SWITCH_APPLICATION; ?></b> : Sélection d'une application.</li>
            <li><b><?php echo LIB_ARG_BOOTSTRAP_BREAK; ?></b> : Interruption du <i>bootstrap</i>.
                Voir <a href="#oabb">OABB</a>.</li>
            <li><b><?php echo LIB_LOCAL_ENTITY_FILE; ?></b> : Affichage de l'empreinte EID de l'entité de
                l'instance.</li>
            <li><b><?php echo LIB_ARG_FLUSH_SESSION; ?></b> : Réinitialisation de la session, suppression du cache,
                fermeture des applications et entités.</li>
            <li><b><?php echo LIB_ARG_INLINE_DISPLAY; ?></b> : Affichage en mode page intégrée d'une application.
                Ce n'est pas supporté partout.</li>
            <li><b><?php echo LIB_LOCAL_LINKS_FOLDER; ?></b> : Dans l'application 4, désigne le NID dont on veut
                afficher les blocs de liens. Non reconnu ailleurs.</li>
            <li><b><?php echo LIB_ARG_RESCUE_MODE; ?></b> : Charge en mode restreint pour dépannage.</li>
            <li><b><?php echo LIB_ARG_UPDATE_APPLICATION; ?></b> : Demande une mise à jour des applications et du cache
                associé.</li>
            <li><b>bootstrapfirstpuppetmastereid</b> : Dans le <i>firstboot</i>, donne l'EID de puppetmaster. Non
                reconnu ailleurs.</li>
            <li><b>bootstrapfirstpuppetmasterlocation</b> : Dans le <i>firstboot</i>, donne la localisation de
                téléchargement de puppetmaster. Non reconnu ailleurs.</li>
            <li><b>bootstrapfirstsubordinationeid</b> : Dans le <i>firstboot</i>, donne l'EID de l'entité de
                subordination. Non reconnu ailleurs.</li>
            <li><b>bootstrapfirstsubordinationlocation</b> : Dans le <i>firstboot</i>, donne la localisation de
                téléchargement de l'entité de subordination. Non reconnu ailleurs.</li>
        </ul>
        <p><b>ATTENTION</b> : si la bibliothèque ne peut être chargée, le <i>bootstrap</i> renverra systématiquement sur
            la page d'interruption ! Voir <a href="#oabb">OABB</a>.</p>

        <h4 id="oabc">OABC / Configuration</h4>
        <p>Le <i>bootstrap</i> obéit aux mêmes options que la bibliothèque et les applications. Voir
            <a href="#cco">CCO</a>.</p>

        <h4 id="oabb">OABB / Interruption</h4>
        <p>La présence de la commande <i><?php echo LIB_ARG_BOOTSTRAP_BREAK; ?></i> sur l'URL déclenche l'affichage
            d'une page d'interruption dédiée du <i>bootstrap</i>.</p>
        <p>En cas de problème lors de l'initialisation du <i>bootstrap</i> ou de la bibliothèque, le <i>bootstrap</i>
            renverra systématiquement sur la page d'interruption avec une erreur.</p>
        <p>Si la bibliothèque ne peut être chargée, quelque soit les arguments passés, le <i>bootstrap</i> renverra
            systématiquement sur la page d'interruption avec une erreur.</p>
        <p>Le résultat est une page contenant en partie centrale, par exemple :</p>
        <pre>
#1 bootstrap break on
- [11] user interrupt
tB=0.0161s
? Flush PHP session (9ccb83)

#2 bootstrap
bootstrap RID         : fc9bb365082ea3a3c8e8e9692815553ad9a70632fe12e9b6d54c8ae5e20959ce94fbb64f.none.288
bootstrap BID         : 81de9f10eb1479bbb219c166547b6d4eb690672feadf0f3841cacf58dbb21f537252b011.none.288
bootstrap CID         : a9e420daf12bc21278317e180fd51460fa786f275a2923d7a7b0cb0ac9c1ee2f.sha2.256
bootstrap IID         : 304f4431cd011211e8fbb57081cd8f1609a25a46ab30476e4b3bffb90d47e73832374176.none.288
bootstrap OID         : d0de067c522905d681cb6095ebad8f1bcea28fa6e0ca03a27ed9fd784d468221.sha2.256 OK
bootstrap SID         : 61c5907df8b34c33eaa7e3df8c0e6a483f949c7e004b0d192d2756d388d60434.sha2.256

#3 nebule library PP
library version       : 020231126
puppetmaster          : 702989efb9cb940605853652992399b077d0c4ee9f5a817387641a14838a77a1.sha2.256
security authority    : 488f70485b940ed254b05a4f312d948e4ff1d9a3821568e1c5709f51bf1d70b7.sha2.256
code authority        : 61c5907df8b34c33eaa7e3df8c0e6a483f949c7e004b0d192d2756d388d60434.sha2.256
directory authority   : af90bfb248ea1338b4c4aee5b2cf07d2479e6351f321760b15440b987ba4e731.sha2.256
time authority        : 288239c8ba88f43a6581f45e95152f5a4ac3ff560b3162bf97bd614a1c8659cd.sha2.256
server entity         : 5fe528863badf85aceb5337d7cf9f72262a7c5abc501b4f205b68ad179f5f06d.sha2.256
default entity        : 88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256
current entity        : 88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256
code branch           : 81de9f10eb1479bbb219c166547b6d4eb690672feadf0f3841cacf58dbb21f537252b011.none.288 (develop)
php version           : found 7.4.33, need >= 7.3.0

#4 nebule library POO
tL=0.0425s
library RID           : 780c5e2767e15ad2a92d663cf4fb0841f31fd302ea0fa97a53bfd1038a0f1c130010e15c.none.288
library IID           : 21f6396e921e4373a91d70d13895b04a359316fc269a1c0dc9268a71419ecfb41e88d58d.none.288
library OID           : 358f523ec5bbc7e2d5cb4d4fb3c83cd2659055c9b0eb8955cdcef88d1cf6491d.sha2.256 version 020231126
functional level      : found 020231125, need >= 020231125
library SID           : 61c5907df8b34c33eaa7e3df8c0e6a483f949c7e004b0d192d2756d388d60434.sha2.256
puppetmaster          : 702989efb9cb940605853652992399b077d0c4ee9f5a817387641a14838a77a1.sha2.256 OK
security authority    : 488f70485b940ed254b05a4f312d948e4ff1d9a3821568e1c5709f51bf1d70b7.sha2.256 OK
code authority        : 61c5907df8b34c33eaa7e3df8c0e6a483f949c7e004b0d192d2756d388d60434.sha2.256 OK
directory authority   : af90bfb248ea1338b4c4aee5b2cf07d2479e6351f321760b15440b987ba4e731.sha2.256 OK
time authority        : 288239c8ba88f43a6581f45e95152f5a4ac3ff560b3162bf97bd614a1c8659cd.sha2.256 OK
server entity         : 5fe528863badf85aceb5337d7cf9f72262a7c5abc501b4f205b68ad179f5f06d.sha2.256 OK (local authority)
default entity        : 88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256 OK (local authority)
current entity        : 5fe528863badf85aceb5337d7cf9f72262a7c5abc501b4f205b68ad179f5f06d.sha2.256 OK (local authority)
subordination         : cdc0daf378b1a425b5a3be326da558d663b7b2256112eae3dc39d3a41ab9faf3.sha2.256 OK
cryptography class    : Nebule\Library\CryptoOpenssl
cryptography          : hash sha2.256 OK
cryptography          : symmetric aes.256.ctr OK
cryptography          : asymmetric rsa.2048 OK
cryptography          : pseudo-random entropy 7.9127928330703/8 OK
i/o class             : Nebule\Library\io
i/o                   : Nebule\Library\ioDisk (RW) file://, links OK, objects OK
i/o                   : Nebule\Library\ioNetworkHTTP (RO) http://code.master.nebule.org, links OK no write., objects OK no write.
i/o                   : Nebule\Library\ioNetworkHTTPS (RO) https://code.master.nebule.org, links OK no write., objects OK no write.
social class          : Nebule\Library\Social
social                : Nebule\Library\SocialMySelf OK
social                : Nebule\Library\SocialNotMyself OK
social                : Nebule\Library\SocialSelf OK
social                : Nebule\Library\SocialNotself OK
social                : Nebule\Library\SocialAuthority OK
social                : Nebule\Library\SocialAll OK
social                : Nebule\Library\SocialNone OK
social                : Nebule\Library\SocialOnList OK
social                : Nebule\Library\SocialOffList OK
social                : Nebule\Library\SocialReputation OK
social                : Nebule\Library\SocialUnreputation OK
metrology inputs      : Lr=226+0 Lv=226+0 Or=233+0 Ov=233+0 (PP+POO)
metrology buffers     : Lc=73 Oc=53 Ec=7 Gc=0 Cc=0 CUc=0 CPc=0 CTc=0 CWc=0

#5 application
application RID       : 4046edc20127dfa1d99f645a7a4ca3db42e94feffa151319c406269bd6ede981c32b96e2.none.288
application IID       : c02030d3b77c52b3e18f36ee9035ed2f3ff68f66425f2960f973ea5cd1cc0240a4d28de1.none.288 load
application OID       : ecd05f820e0a3fb2acf95c36e04a40e95698ddc168a20bbb8350e4fe53b16738.sha2.256
application SID       :

#6 end bootstrap
tE=0.0632s
        </pre>
        <p style="color: red; font-weight: bold">À compléter avec la description des lignes...</p>

        <h4 id="oabe">OABE / Applications externes</h4>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=RID</i> sur l'URL permet de passer
            vers l'application référencée par ce RID.
            Pour les applications externes, le RID est l'objet de référence de l'application. Chaque application dispose
            d'un RID unique avec une valeur assez longue pour éviter toute collision avec une autre application.</p>
        <p>Voir <a href="#oa">OA</a>, <a href="#oail">OAIL</a> et <a href="#oabn">OABN</a>.</p>

        <h4 id="oaba">OABA / Applications intégrées</h4>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=RID</i> sur l'URL permet de passer
            vers l'application référencée par ce RID.
            Pour les applications internes, le RID est l'objet de référence interne de l'application sur un seul
            chiffre (0 à 9).</p>
        <p> Toutes les applications internes ne sont pas actives et donc ne sont pas accessibles. Certaines
            applications internes peuvent être bloquées par des options de configuration.</p>
        <p>Ci-dessous les différentes applications.</p>

        <h5 id="oaba0">OABA0 / Application 0</h5>
        <p>C'est l'application par défaut pour les instances sans interaction. La page Web délivrée est minimaliste.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=0</i> sur l'URL déclenche l'affichage
            de cette page dédiée du <i>bootstrap</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication1</b> à <i>false</i> ou un lien équivalent.</p>

        <h5 id="oaba1">OABA1 / Application 1</h5>
        <p>C'est l'application de sélection et de navigation et les différentes applications.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=1</i> sur l'URL déclenche l'affichage
            de cette page dédiée du <i>bootstrap</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication1</b> à <i>false</i> ou un lien équivalent.</p>

        <h5 id="oaba2">OABA2 / Application 2</h5>
        <p>C'est une application minimale d'authentification des utilisateurs sur les entités.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=2</i> sur l'URL déclenche l'affichage
            de cette page dédiée du <i>bootstrap</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication2</b> à <i>false</i> ou un lien équivalent.</p>

        <h5 id="oaba3">OABA3 / Application 3</h5>
        <p>C'est l'application d'affichage de la documentation technique.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=3</i> sur l'URL déclenche l'affichage
            de cette page dédiée du <i>bootstrap</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication3</b> à <i>false</i> ou un lien équivalent.</p>

        <h5 id="oaba4">OABA4 / Application 4</h5>
        <p>C'est une application qui permet de voir de façon simplifiée les blocs de liens.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=4</i> sur l'URL déclenche l'affichage
            de cette page dédiée du <i>bootstrap</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication4</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Elle est désactivée par défaut.</p>

        <h5 id="oaba5">OABA5 / Application 5</h5>
        <p>Son accès peut être bloqué par l'option <b>permitApplication5</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Non utilisé, redirigé vers l'application 0.</p>

        <h5 id="oaba6">OABA6 / Application 6</h5>
        <p>Son accès peut être bloqué par l'option <b>permitApplication6</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Non utilisé, redirigé vers l'application 0.</p>

        <h5 id="oaba7">OABA7 / Application 7</h5>
        <p>Son accès peut être bloqué par l'option <b>permitApplication7</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Non utilisé, redirigé vers l'application 0.</p>

        <h5 id="oaba8">OABA8 / Application 8</h5>
        <p>Son accès peut être bloqué par l'option <b>permitApplication8</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Non utilisé, redirigé vers l'application 0.</p>

        <h5 id="oaba9">OABA9 / Application 9</h5>
        <p>C'est une application utilisée pour le déverminage du code.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=9</i> sur l'URL déclenche l'affichage
            de cette page dédiée du <i>bootstrap</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication9</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Elle est désactivée par défaut.</p>

        <h4 id="oabn">OABN / Fonctionnement nominal</h4>
        <p>Le <i>bootstrap</i> mémorise la dernière application utilisée, externe ou intégrée, et va représenter la même
            application à l'utilisateur jusqu'à déconnexion de la session à l'instance ou vidage du cache ou problème.
            C'est le fonctionnement nominal pour un utilisateur.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=RID</i> sur l'URL permet de passer
            vers l'application référencée par ce RID.
            Tant que la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?></i> n'est pas de nouveau utilisée, on
            reste sur la même application.</p>
        <p>La recherche de l'application à utiliser par le <i>bootstrap</i> est faite dans l'ordre :</p>
        <ul>
            <li>1 : Si présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?></i>, il essaie de trouver
                l'application correspondante.</li>
            <li>2 : Si une application est mémorisée dans le cache, il la charge.</li>
            <li>3 : Sinon il charge l'application par défaut. C'est équivalent à
                <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=1</i>.</li>
        </ul>
        <p>Voir <a href="#oail">OAIL</a>.</p>

        <h3 id="oal">OAL / Librairie</h3>
        <h4 id="oald">OALD / Description</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oalc">OALC / Configuration</h4>
        <p>La bibliothèque obéit aux mêmes options que le <i>bootstrap</i> et les applications. Voir
            <a href="#cco">CCO</a>.</p>

        <?php Modules::echoDocumentationCore();
    }
}
