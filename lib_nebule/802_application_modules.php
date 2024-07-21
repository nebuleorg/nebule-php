<?php
declare(strict_types=1);
namespace Nebule\Library;

use Nebule\Application\Autent\Application;

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
    protected ?Applications $_applicationInstance = null;
    protected ?Configuration $_configurationInstance = null;
    protected ?Metrology $_metrologyInstance = null;
    protected ?Displays $_displayInstance = null;
    protected ?Actions $_actionInstance = null;
    protected ?Translates $_translateInstance = null;
    protected string $_applicationNamespace = '';
    protected array $_listInternalModules = array();
    protected array $_listExternalModules = array();
    protected array $_listModulesName = array();
    protected array $_listModulesInstance = array();
    protected array $_listModulesSignerRID = array();
    protected array $_listModulesInitRID = array();
    protected array $_listModulesRID = array();
    protected array $_listModulesTranslateRID = array();
    protected array $_listModulesTranslateOID = array();
    protected array $_listModulesTranslateInstance = array();
    protected array $_listModulesID = array();
    protected array $_listModulesValid = array();
    protected array $_listModulesEnabled = array();
    protected ?Modules $_currentModuleInstance = null;
    protected bool $_loadModulesOK = false;
    private bool $_useModules = false;
    private bool $_useTranslateModules = false;
    private bool $_useExternalModules = false;

    public function __construct(Applications $applicationInstance, array $listInternalModules)
    {
        $this->_applicationInstance = $applicationInstance;
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_configurationInstance = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_applicationNamespace = '\\Nebule\\Application\\' . strtoupper(substr(Application::APPLICATION_NAME, 0, 1)) . strtolower(substr(Application::APPLICATION_NAME, 1));

        $this->_useModules = $this->_applicationInstance->getUseModules();
        $this->_useTranslateModules = $this->_applicationInstance->getUseTranslateModules();
        $this->_useExternalModules = $this->_applicationInstance->getUseExternalModules();

        $this->_listInternalModules = $listInternalModules;
        $this->_loadModules();
    }

    protected function _loadModules(): void {
        if ($this->_loadModulesOK)
            return;
        $this->_loadModulesOK = true;

        if (!$this->_useModules) {
            $this->_metrologyInstance->addLog('Do not load internal modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bcc98872');
            return;
        }
        $this->_loadDefaultModules();

        if (!$this->_useTranslateModules) {
            $this->_metrologyInstance->addLog('Do not load external translate modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b2f83669');
            return;
        }
        $this->_listModulesTranslateRID = $this->_findModulesTranslateRID(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_TRANSLATE);
        $this->_listModulesTranslateOID = $this->_findModulesTranslateUpdateID();
        $this->_listModulesTranslateInstance = $this->_initModulesTranslate();

        if (!$this->_useExternalModules) {
            $this->_metrologyInstance->addLog('Do not load external modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '7b3af452');
            return;
        }

        $this->_findModulesRID();
        $this->_findModulesUpdateID();
        $this->_initModules();
    }

    protected function _loadDefaultModules(): void {
        if (!$this->_useModules)
            return;
        $this->_metrologyInstance->addLog('Load default modules on NameSpace=' . $this->_applicationNamespace, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '93eb59aa');

        foreach ($this->_listInternalModules as $moduleName) {
            $this->_metrologyInstance->addTime();
            $moduleFullName = $this->_applicationNamespace . '\\' . $moduleName;
            $this->_metrologyInstance->addLog('Loaded module ' . $moduleFullName . ' (' . $moduleName . ')', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '4879c453');
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

    protected function _findModulesTranslateRID(string $rid): array {
        global $bootstrapApplicationIID;

        $result = array();
        $object = new Node($this->_nebuleInstance, $bootstrapApplicationIID);
        $hashRef = $this->_nebuleInstance->getCryptoInstance()->hash($rid);
        $links = $object->getLinksOnFields('', '', 'f', $bootstrapApplicationIID, '', $hashRef);

        foreach ($links as $link) {
            $module = $link->getParsed()['bl/rl/nid2'];
            foreach ($this->_nebuleInstance->getLocalAuthorities() as $authority) {
                if (isset($link->getParsed()['bs/rs1/eid'])
                    && $link->getParsed()['bs/rs1/eid'] == $authority
                    && $module != '0'
                    && $this->_nebuleInstance->getIoInstance()->checkLinkPresent($module)
                ) {
                    $this->_metrologyInstance->addLog('Find modules translate ' . $module, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8babe773');
                    $result[$module] = $module;
                    $this->_listModulesSignerRID[$module] = $link->getParsed()['bs/rs1/eid'];
                    break;
                }
            }
        }
        return $result;
    }

    protected function _findModulesTranslateUpdateID(): array {
        return array(); // TODO
    }

    protected function _initModulesTranslate(): array {
        return array(); // TODO
    }

    protected function _findModulesRID(): void
    {
        global $bootstrapApplicationIID;

        if (!$this->_useModules || !$this->_useExternalModules)
            return;

        $this->_metrologyInstance->addLog('Find option modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '226ce8be');

        $object = $this->_nebuleInstance->newObject($bootstrapApplicationIID);
        $hashRef = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES);
        $links = $object->getLinksOnFields('', '', 'f', $bootstrapApplicationIID, '', $hashRef);

        foreach ($links as $link) {
            $ok = false;
            $module = $link->getParsed()['bl/rl/nid2'];
            foreach ($this->_nebuleInstance->getLocalAuthorities() as $authority) {
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

    protected function _findModulesUpdateID(): void
    {
        if (!$this->_useModules || !$this->_useExternalModules)
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

            $instanceModule = $this->_nebuleInstance->newObject($moduleID);
            $listed[$moduleID] = $moduleID;

            // Cherche une mise à jour.
            $updateModule = $moduleID;
            if ($okNotListed) {
                $updateModule = $instanceModule->getReferencedObjectID(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES, 'authority');
                $updateSigner = $instanceModule->getReferencedSignerID(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES, 'authority');
            }
            if ($updateModule != $moduleID
                && $updateModule != '0'
            ) {
                $instanceModule = $this->_nebuleInstance->newObject($updateModule);
                if ($instanceModule->getType('authority') == 'application/x-php'
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
                        $this->_metrologyInstance->addLog('Load module ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6d2f16cb');
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
            $this->_metrologyInstance->addLog('End load module ' . $moduleID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ef4207a5');
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
     * Some modules are loaded before by default with _loadDefaultModules() depending on the list '_listInternalModules' give by le app.
     *
     * TODO gérer le chargement uniquement des modules listés dans _listExternalModules, si disponibles.
     * TODO prévoir un renforcement de la recherche avec les RID des modules...
     *
     * @return void
     */
    protected function _initModules(): void
    {
        if (!$this->_useModules || !$this->_useExternalModules)
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
                && $class != 'Nebule\\Library\\Modules'
                && $class != 'Modules'
                && $class != $searchModuleHead . 's'
                && !isset($this->_listModulesName[$moduleName])
            ) {
                $this->_metrologyInstance->addTime();
                $this->_metrologyInstance->addLog('New ' . $moduleFullName, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5703836f');
                $instance = new $class($this->_applicationInstance);

                $this->_listModulesEnabled[$moduleFullName] = false; // @todo à revoir...

                // Vérifie si c'est une dépendance de la classe Modules.
                if (is_a($instance, 'Nebule\\Library\\Modules')) {
                    $this->_metrologyInstance->addLog('loaded module ' . $moduleFullName, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '130bc586');
                    $instance->initialisation();
                    $this->_listModulesName[$moduleName] = $moduleFullName;
                    $this->_listModulesInstance[$moduleFullName] = $instance;
                    $this->_listModulesEnabled[$moduleFullName] = true; // @todo à revoir...
                    if ($instance->getType() == 'Traduction') {
                        $this->_metrologyInstance->addLog('add translate module', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6770ac3f');
                        $this->_listModulesTranslateInstance[$moduleFullName] = $instance;
                    }
                }
            }
        }

        $this->_metrologyInstance->addLog('Optionals modules loaded', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '0237217e');
    }

    public function getIsModuleActivated(Node $module): bool
    {
        $hashActivation = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MOD_ACTIVE);

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

    public function getUseModules(): bool { return $this->_useModules; }
    public function getUseTranslateModules(): bool { return $this->_useTranslateModules; }
    public function getUseExternalModules(): bool { return $this->_useExternalModules; }

    public function getModulesListNames(): array
    {
        return $this->_listModulesName;
    }

    public function getModulesListInstances(): array
    {
        return $this->_listModulesInstance;
    }

    public function getModulesTranslateListInstances(): array
    {
        return $this->_listModulesTranslateInstance;
    }

    public function getModulesListOID(): array
    {
        return $this->_listModulesID;
    }

    public function getModulesListRID(): array
    {
        return $this->_listModulesRID;
    }

    public function getModulesListSignersRID(): array
    {
        return $this->_listModulesSignerRID;
    }

    public function getModulesListValid(): array
    {
        return $this->_listModulesValid;
    }

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
    public function getIsModuleLoaded(string $name): bool
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
     * If not found, return null... but sure this will be a problem after...
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
}
