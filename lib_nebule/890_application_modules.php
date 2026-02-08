<?php
declare(strict_types=1);
namespace Nebule\Library;

//use Nebule\Application\Autent\Application;

/**
 * Classe de référence de gestion des modules des applications.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ApplicationModules
{
    protected ?nebule $_nebuleInstance = null;
    protected ?Rescue $_rescueInstance = null;
    protected ?Applications $_applicationInstance = null;
    protected ?Configuration $_configurationInstance = null;
    protected ?Metrology $_metrologyInstance = null;
    protected ?Displays $_displayInstance = null;
    protected ?Actions $_actionInstance = null;
    protected ?Translates $_translateInstance = null;
    protected ?Cache $_cacheInstance = null;
    protected ?Session $_sessionInstance = null;
    protected ?Authorities $_authoritiesInstance = null;
    protected ?Entities $_entitiesInstance = null;
    protected ?Recovery $_recoveryInstance = null;
    protected string $_applicationNamespace = '';
    protected array $_listModulesName = array();
    protected array $_listModulesInstance = array();
    protected array $_listModulesSignerRID = array();
    protected array $_listModulesInitRID = array();
    protected array $_listModulesRID = array();
    protected array $_listModulesOID = array();
    protected array $_listModulesTranslateRID = array();
    protected array $_listModulesTranslateOID = array();
    protected array $_listModulesTranslateName = array();
    protected array $_listModulesValid = array();
    protected array $_listModulesEnabled = array();
    protected ?Module $_currentModuleInstance = null;
    protected bool $_loadModulesOK = false;

    public function __construct(Applications $applicationInstance)
    {
        $this->_applicationInstance = $applicationInstance;
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_configurationInstance = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_rescueInstance = $this->_nebuleInstance->getRescueInstance();
        $this->_applicationNamespace = $applicationInstance->getNamespace();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_cacheInstance = $this->_nebuleInstance->getCacheInstance();
        $this->_sessionInstance = $this->_nebuleInstance->getSessionInstance();
        $this->_authoritiesInstance = $this->_nebuleInstance->getAuthoritiesInstance();
        $this->_entitiesInstance = $this->_nebuleInstance->getEntitiesInstance();
        $this->_recoveryInstance = $this->_nebuleInstance->getRecoveryInstance();

        $this->_initInternalModules();
        $this->_initInternalDeadModules();
        $this->_initExternalModules();
        $this->_initExternalTranslateModules();
        //$this->_findCurrentModule();
    }

    protected function _initInternalModules(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_applicationInstance::USE_MODULES
            || !$this->_configurationInstance->getOptionAsBoolean('permitApplicationModules')
        ) {
            $this->_metrologyInstance->addLog('do not load modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bcc98872');
            return;
        }
        $this->_metrologyInstance->addLog('loading internal modules on NameSpace=' . $this->_applicationNamespace, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b2bc7fc3');

        foreach ($this->_applicationInstance::LIST_MODULES_INTERNAL as $moduleName) {
            $this->_metrologyInstance->addTime();
            $moduleFullName = $this->_applicationNamespace . '\\' . $moduleName;
            try {
                $classImplement = class_implements($moduleFullName);
            } catch (\Throwable $e) {
                $this->_metrologyInstance->addLog('module ' . $moduleFullName . ' (' . $moduleName . ')' . ' lost ('  . $e->getCode() . ') : ' . $e->getFile()
                    . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                    . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '993617b1');
                $classImplement = array();
            }
            if (! is_array($classImplement))
                $classImplement = array();

            if (str_starts_with($moduleName, 'ModuleTranslate')
                && in_array('Nebule\Library\ModuleTranslateInterface', $classImplement)
                && get_parent_class($moduleFullName) == 'Nebule\Library\ModuleTranslates'
            )
                $this->_initInternalModuleTranslate($moduleName, $moduleFullName);
            elseif (str_starts_with($moduleName, 'Module') && in_array('Nebule\Library\ModuleInterface', $classImplement))
                $this->_initInternalModuleApplication($moduleName, $moduleFullName);
            else
                $this->_metrologyInstance->addLog('unsupported module ' . $moduleFullName . ' (' . $moduleName . ')', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e8543fd4');
        }
        $this->_metrologyInstance->addLog('internal modules loaded', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '050783df');
    }

    protected function _initInternalDeadModules(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        foreach (get_declared_classes() as $class) {
            if (str_starts_with($class, 'Module') && $class != 'Module') {
                $this->_metrologyInstance->addLog('dead module ' . $class, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '050783df');
                $this->_listModulesName[$class::MODULE_LANGUAGE] = $class;
            }
        }
    }

    protected function _initInternalModuleTranslate(string $moduleName, string $moduleFullName): void {
        $this->_metrologyInstance->addLog('loading internal translate modules ' . $moduleFullName . ' (' . $moduleName . ')', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0b396e08');
        try {
            $lang = $moduleFullName::MODULE_LANGUAGE;
            $this->_listModulesName[$moduleName] = $moduleFullName;
            $this->_listModulesTranslateName[$lang] = $moduleFullName;
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('translate module ' . $moduleFullName . ' (' . $moduleName . ')' . ' cannot been loaded ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '57947e3f');
        }
    }

    protected function _initInternalModuleApplication(string $moduleName, string $moduleFullName): void {
        $this->_metrologyInstance->addLog('loading internal modules ' . $moduleFullName . ' (' . $moduleName . ')', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4879c453');
        try {
            //$instance = new $moduleFullName($this->_applicationInstance);
            $instance = new $moduleFullName($this->_nebuleInstance);
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error instancing class=' . $moduleFullName .' ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '6e8ba898');
            return;
        }
        if (! $instance instanceof \Nebule\Library\ModuleInterface) {
            $this->_metrologyInstance->addLog('module ' . $moduleFullName . ' (' . $moduleName . ')' . ' do not have module interface, cannot init', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'b9742fd8');
            return;
        }
        $instance->setEnvironmentLibrary($this->_nebuleInstance);
        $instance->setEnvironmentApplication($this->_applicationInstance);
        $this->_metrologyInstance->addLog('init internal module ' . $moduleFullName . ' (' . $moduleName . ')', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'a6c894e7');
        try {
            $instance->initialisation();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error initialisation class=' . $moduleFullName .' ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '0d36b043');
            return;
        }
        $this->_listModulesName[$moduleName] = $moduleFullName;
        $this->_listModulesInstance[$moduleFullName] = $instance;
        $this->_listModulesInitRID[$moduleFullName] = '0';
        $this->_listModulesOID[$moduleFullName] = '0';
        $this->_listModulesSignerRID[$moduleFullName] = '0';
        $this->_listModulesValid[$moduleFullName] = true;
    }

    protected function _initExternalModules(): void {
        if (!$this->_applicationInstance::USE_MODULES
            || !$this->_applicationInstance::USE_MODULES_EXTERNAL
            || !$this->_configurationInstance->getOptionAsBoolean('permitApplicationModules')
            || !$this->_configurationInstance->getOptionAsBoolean('permitApplicationModulesExternal')
        )
            return;
//        $this->_metrologyInstance->addLog('load external modules on NameSpace=' . $this->_applicationNamespace, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $instanceRID = $this->_cacheInstance->newNodeByType(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES);
        $links = $instanceRID->getReferencedLinks(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES, 'authority');
        foreach ($links as $link) {
            $moduleInstanceRID = $this->_cacheInstance->newNodeByType($link->getParsed()['bl/rl/nid2']);
            if ($moduleInstanceRID->getID() == '0')
                continue;
            $moduleInstanceOID = $this->_cacheInstance->newNodeByType($moduleInstanceRID->getReferencedOrSelfNID(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES));
            if ($moduleInstanceOID->getID() == '0'
                || !$moduleInstanceOID->checkPresent()
            )
                continue;
            $moduleID = $moduleInstanceOID->getID();
            $this->_metrologyInstance->addLog('Load external module ' . $moduleID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '06a13897');
            try {
                include('o/' . $moduleID);// @todo A modifier, passer par IO.
            } catch (\Throwable $e) {
                $this->_metrologyInstance->addLog('error include external moduleID=' . $moduleID .' ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '6cbb4fdd');
            }
            $this->_listModulesRID[] = $moduleInstanceRID->getID();
            $this->_listModulesOID[] = $moduleID;
        }
        $this->_metrologyInstance->addLog('external modules loaded', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'a95de198');
    }

    protected function _initExternalTranslateModules(): void {
        if (!$this->_applicationInstance::USE_MODULES
            || !$this->_applicationInstance::USE_MODULES_TRANSLATE
            || !$this->_configurationInstance->getOptionAsBoolean('permitApplicationModules')
            || !$this->_configurationInstance->getOptionAsBoolean('permitApplicationModulesTranslate')
        )
            return;
//        $this->_metrologyInstance->addLog('load translate modules on NameSpace=' . $this->_applicationNamespace, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $instanceRID = $this->_cacheInstance->newNodeByType(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES);
        $links = $instanceRID->getReferencedLinks(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_TRANSLATE, 'authority');
        foreach ($links as $link) {
            $moduleInstanceRID = $this->_cacheInstance->newNodeByType($link->getParsed()['bl/rl/nid2']);
            $moduleInstanceOID = $this->_cacheInstance->newNodeByType($moduleInstanceRID->getReferencedOrSelfNID(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES));
            if ($moduleInstanceRID->getID() == '0'
                || $moduleInstanceOID->getID() == '0'
                || !$moduleInstanceOID->checkPresent()
            )
                continue;
            $moduleID = $moduleInstanceOID->getID();
            $this->_metrologyInstance->addLog('Load translate module ' . $moduleID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '6d2f16cb');
            try {
                include('o/' . $moduleID);// @todo A modifier, passer par IO.
            } catch (\Throwable $e) {
                $this->_metrologyInstance->addLog('error include translate moduleID=' . $moduleID .' ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '6ed36db6');
            }
            $this->_listModulesTranslateRID[] = $moduleInstanceRID->getID();
            $this->_listModulesTranslateOID[] = $moduleID;
        }
        $list = get_declared_classes();

        $this->_metrologyInstance->addLog('translate modules loaded', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '7e7aeaed');
    }



    protected function _findModulesRID(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        global $bootstrapApplicationIID;

        if (!$this->_applicationInstance::USE_MODULES || !$this->_applicationInstance::USE_MODULES_EXTERNAL)
            return;

        $this->_metrologyInstance->addLog('Find option modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '226ce8be');

        $object = $this->_cacheInstance->newNodeByType($bootstrapApplicationIID);
        $links = $object->getLinksOnFields('', '', 'f', $bootstrapApplicationIID, '', References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES);

        foreach ($links as $link) {
            $ok = false;
            $module = $link->getParsed()['bl/rl/nid2'];
            foreach ($this->_authoritiesInstance->getLocalAuthoritiesID() as $authority) {
                if (isset($link->getParsed()['bs/rs1/eid'])
                    && $link->getParsed()['bs/rs1/eid'] == $authority
                    && $module != '0'
                    && $this->_nebuleInstance->getIoInstance()->checkLinkPresent($module)
                ) {
                    $ok = true;
                    break;
                }
            }
            if ($ok) {
                $this->_metrologyInstance->addLog('Find modules ' . $module, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1520ed55');
                $this->_listModulesInitRID[$module] = $module;
                $this->_listModulesSignerRID[$module] = $link->getParsed()['bs/rs1/eid'];
            }
        }
    }

    protected function _findModulesUpdateID(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_applicationInstance::USE_MODULES || !$this->_applicationInstance::USE_MODULES_EXTERNAL)
            return;

        $this->_metrologyInstance->addLog('Find modules updates', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '19029717');

        // Recherche la mise à jour et vérifie les objets des modules avant de les charger.
        $listed = array();
        foreach ($this->_listModulesInitRID as $moduleID) {
            // Vérifie l'ID. Un module chargé par défaut est déjà chargé et à un ID = 0.
            if ($moduleID == '0' || $moduleID == '' )
                continue;

            $moduleRID = $moduleID;

            $this->_metrologyInstance->addLog('Ask load module ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cd99e217');
            $okValid = false;
            $okActivated = false;
            $okNotListed = true;

            // Vérifie que l'objet n'est pas déjà appelé.
            foreach ($listed as $element) {
                if ($element == $moduleID) {
                    $this->_metrologyInstance->addLog('Module already listed ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8a077f11');
                    $okNotListed = false;
                }
            }

            $instanceModule = $this->_cacheInstance->newNodeByType($moduleID);
            $listed[$moduleID] = $moduleID;

            // Cherche une mise à jour.
            $updateModule = $moduleID;
            if ($okNotListed) {
                $updateModule = $instanceModule->getReferencedOrSelfNID(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES, 'authority');
                $updateSigner = $instanceModule->getReferencedSID(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES, 'authority');
            }
            if ($updateModule != $moduleID
                && $updateModule != '0'
            ) {
                $instanceModule = $this->_cacheInstance->newNodeByType($updateModule);
                if ($instanceModule->getType('authority') == References::REFERENCE_OBJECT_APP_PHP
                    && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($updateModule)
                ) {
                    $this->_metrologyInstance->addLog('Find module update ' . $updateModule, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cabf8ebd');
                    $okValid = true;
                    // Vérifie que l'objet n'est pas déjà appelé.
                    foreach ($listed as $element) {
                        if ($element == $updateModule) {
                            $this->_metrologyInstance->addLog('Module update already listed ' . $updateModule, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'fa4a752c');
                            $okNotListed = false;
                        }
                    }

                    $moduleID = $updateModule;
                    $listed[$updateModule] = $updateModule;
                } else {
                    $this->_metrologyInstance->addLog('Module updated type mime not valid ' . $moduleID, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f852ca44');
                    $okValid = false;
                }
            }

            // Vérifie si le module est activé.
            $okActivated = $this->getIsModuleActivated($instanceModule);
            unset($instanceModule, $updateModule, $element);

            // @todo Vérifier le contenu.
            // A faire, DANGEREUX !!!

            // Enregistre le module.
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
                        $this->_metrologyInstance->addLog('Load module ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6d2f16cb');
                        include('o/' . $moduleID);                // @todo A modifier, passer par IO.
                        $this->_listModulesEnabled[$name] = true;
                    }

                    // Enregistre le module.
                    $this->_listModulesOID[$name] = $moduleID;
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
            $this->_metrologyInstance->addLog('End load module ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ef4207a5');
        }
    }

    /**
     * Recherche le nom de la classe dans un objet.
     *
     * @param string $id
     * @return null|string
     */
    protected function _getObjectClassName(string $id): ?string {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $readValue = $this->_nebuleInstance->getIoInstance()->getObject($id);
        $startValue = strpos($readValue, 'class');
        $trimLine = substr($readValue, $startValue, 128);
        $arrayValue = explode(' ', $trimLine);
        return $arrayValue[1];
    }

    /**
     * Load and init external modules for the application.
     * Some modules are loaded before by default with _loadDefaultModules() depending on the list '_listInternalModules' give by le app.
     *
     * TODO gérer le chargement uniquement des modules listés dans _listExternalModules, si disponibles.
     * TODO prévoir un renforcement de la recherche avec les RID des modules...
     *
     * @return void
     */
    protected function _initModules(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_applicationInstance::USE_MODULES || !$this->_applicationInstance::USE_MODULES_EXTERNAL)
            return;

        $this->_metrologyInstance->addLog('load optionals modules on NameSpace=' . $this->_applicationNamespace, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2df08836');

        // Liste toutes les classes module* et les charges une à une.
        $list = get_declared_classes();
        $searchModuleHead = $this->_applicationNamespace . '\\' . 'Module';
        $sizeModuleHead = strlen($searchModuleHead);
        foreach ($list as $i => $class) {
            $moduleFullName = '\\' . $class;
            $moduleNamespace = preg_replace('/(.+\W)\w+/', '$1', $class);
            $moduleName = preg_replace('/.+\W(\w+)/', '$1', $class);
            $this->_metrologyInstance->addLog('find on list class=' . $moduleFullName . ' (' . $moduleName . ')', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9a744ec7');
            // Ne regarde que les classes qui sont des modules d'après le nom.
            if (substr('\\' . $class, 0, $sizeModuleHead) == $searchModuleHead
                && ($moduleNamespace == $this->_applicationNamespace || $moduleNamespace == 'Nebule\\Modules\\')
                && $class != 'Nebule\\Library\\Module'
                && $class != 'Modules'
                && $class != $searchModuleHead . 's'
                && !isset($this->_listModulesName[$moduleName])
            ) {
                $this->_metrologyInstance->addTime();
                $this->_metrologyInstance->addLog('New ' . $moduleFullName, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5703836f');
                $instance = new $class($this->_applicationInstance);

                $this->_listModulesEnabled[$moduleFullName] = false; // @todo à revoir...

                // Vérifie si c'est une dépendance de la classe Modules.
                if (is_a($instance, 'Nebule\\Library\\Module')) {
                    $this->_metrologyInstance->addLog('loaded module ' . $moduleFullName, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '130bc586');
                    $instance->initialisation();
                    $this->_listModulesName[$moduleName] = $moduleFullName;
                    $this->_listModulesInstance[$moduleFullName] = $instance;
                    $this->_listModulesEnabled[$moduleFullName] = true; // @todo à revoir...
                    if ($instance::MODULE_TYPE == 'Traduction') {
                        $this->_metrologyInstance->addLog('add translate module', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6770ac3f');
                        //$this->_listModulesTranslateInstance[$moduleFullName] = $instance; // FIXME do no load translate module as instance
                    }
                }
            }
        }

        $this->_metrologyInstance->addLog('Optionals modules loaded', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '0237217e');
    }

    public function getIsModuleActivated(Node $module): bool {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $linksList = $module->getLinksOnFields('', '', 'f', $module->getID(), References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_ACTIVE, $module->getID());
        foreach ($linksList as $link)
            if ($this->_authoritiesInstance->getIsLocalAuthorityEID($link->getParsed()['bs/rs1/eid']))
                return true;
        return false;
    }

    public function getModulesListNames(): array { return $this->_listModulesName; }
    public function getModulesListInstances(): array { return $this->_listModulesInstance; }
    public function getModulesTranslateListName(): array { return $this->_listModulesTranslateName; }
    public function getModulesListOID(): array { return $this->_listModulesOID; }
    public function getModulesListRID(): array { return $this->_listModulesRID; }
    public function getModulesListSignersRID(): array { return $this->_listModulesSignerRID; }
    public function getModulesListValid(): array { return $this->_listModulesValid; }
    public function getModulesListEnabled(): array { return $this->_listModulesEnabled; }

    /**
     * Vérifie si le module est chargé. Le module est recherché sur le nom de sa classe.
     * Si non trouvé, retourne false.
     *
     * @param string $name
     * @return boolean
     */
    public function getIsModuleLoaded(string $name): bool {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_applicationInstance::USE_MODULES)
            return false;

        if ($name == ''
            || !str_starts_with($name, 'Module')
            || $name == 'Modules'
        )
            return false;

        $classes = get_declared_classes();

        $fullname = substr($this->_applicationNamespace, 1) . '\\' . $name;
        if (in_array($fullname, $classes))
            return true;
        return false;
    }

    protected function _findCurrentModule(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance::USE_MODULES) { // FIXME verifier
            foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                if ($module::MODULE_COMMAND_NAME == $this->_displayInstance->getCurrentDisplayMode() && strtolower($module::MODULE_TYPE) == 'application') {
                    $this->_metrologyInstance->addLog('find current module name : ' . $module::MODULE_COMMAND_NAME, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '67aaf050');
                    $this->_currentModuleInstance = $this->_applicationInstance->getModulesListInstances()['\\' . $module->getClassName()];
                }
            }
        }
    }

    /**
     * Retourne le module en cours d'utilisation.
     * Si les modules ne sont pas utilisés, retourne null.
     *
     * @return Module|null
     */
    public function getCurrentModuleInstance(): ?Module {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_applicationInstance::USE_MODULES)
            return null;

        if ($this->_currentModuleInstance != null
            && is_a($this->_currentModuleInstance, 'Nebule\Library\Module')
        )
            return $this->_currentModuleInstance;

        $result = null;
        foreach ($this->_listModulesInstance as $module) {
            if ($module::MODULE_COMMAND_NAME == $this->_displayInstance->getCurrentDisplayMode()) {
                $result = $module;
                break;
            }
        }
        return $result;
    }

    /**
     * Return the instance of a module with this name.
     * Can use long name (with namespace) or short name.
     * If not found, return null... but sure this will be a problem after...
     *
     * @param string $name
     * @return Module|null
     */
    public function getModule(string $name): ?Module {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_applicationInstance::USE_MODULES || $name == '')
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
}
