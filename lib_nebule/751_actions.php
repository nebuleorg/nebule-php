<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Common class for actions.
 * All actions the user ask are detected and processed by different subclasses depending on the theme of the action.
 * There's five parts:
 * - 1 _initSubActions(): preparing the subclasses.
 * - 2 specialActions(): proceed with special actions.
 * - 3 genericActions(): proceed with generic actions.
 * - 4 applicationsActions(): search and proceed with actions on the current application.
 * - 5 modulesActions(): search and proceed with actions on all modules of the current application.
 * The segmentation between generic and special actions is applied on applications and modules.
 * All generique actions need a unlocked entity and a valid token.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Actions extends Functions implements ActionsInterface {
    private ?ActionsLinks $_instanceActionsLinks = null;
    private ?ActionsObjects $_instanceActionsObjects = null;
    private ?ActionsEntities $_instanceActionsEntities = null;
    private ?ActionsGroups $_instanceActionsGroups = null;
    private ?ActionsLocations $_instanceActionsLocations = null;
    private ?ActionsApplications $_instanceActionsApplications = null;
    private ?ActionsMarks $_instanceActionsMarks = null;

    public function __construct(nebule $nebuleInstance) { parent::__construct($nebuleInstance); }
    public function __toString() { return 'Action'; }
    public function __sleep() { return array(); } // TODO do not cache



    protected function _initialisation(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('initialisation action', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4b67de69');
        $this->_initSubActions();
    }

    private function _initSubActions(): void {
        try {
            $this->_metrologyInstance->addLog('init actions links', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'afa36f67');
            $this->_instanceActionsLinks = new ActionsLinks($this->_nebuleInstance);
            $this->_instanceActionsLinks->setEnvironmentLibrary($this->_nebuleInstance);
            $this->_instanceActionsLinks->setEnvironmentApplication($this->_applicationInstance);
            //$this->_instanceActionsLinks->initialisation();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error init actions links ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'b4747eb7');
        }
        try {
            $this->_metrologyInstance->addLog('init actions objects', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e490371d');
            $this->_instanceActionsObjects = new ActionsObjects($this->_nebuleInstance);
            $this->_instanceActionsObjects->setEnvironmentLibrary($this->_nebuleInstance);
            $this->_instanceActionsObjects->setEnvironmentApplication($this->_applicationInstance);
            //$this->_instanceActionsObjects->initialisation();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error init actions objects ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '7dedc636');
        }
        try {
            $this->_metrologyInstance->addLog('init actions entities', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'a56598d5');
            $this->_instanceActionsEntities = new ActionsEntities($this->_nebuleInstance);
            $this->_instanceActionsEntities->setEnvironmentLibrary($this->_nebuleInstance);
            $this->_instanceActionsEntities->setEnvironmentApplication($this->_applicationInstance);
            //$this->_instanceActionsEntities->initialisation();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error init actions entities ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '37d646db');
        }
        try {
            $this->_metrologyInstance->addLog('init actions groups', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8f629f04');
            $this->_instanceActionsGroups = new ActionsGroups($this->_nebuleInstance);
            $this->_instanceActionsGroups->setEnvironmentLibrary($this->_nebuleInstance);
            $this->_instanceActionsGroups->setEnvironmentApplication($this->_applicationInstance);
            //$this->_instanceActionsGroups->initialisation();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error init actions groups ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'db43cf5f');
        }
        try {
            $this->_metrologyInstance->addLog('init actions locations', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4dde2a79');
            $this->_instanceActionsLocations = new ActionsLocations($this->_nebuleInstance);
            $this->_instanceActionsLocations->setEnvironmentLibrary($this->_nebuleInstance);
            $this->_instanceActionsLocations->setEnvironmentApplication($this->_applicationInstance);
            //$this->_instanceActionsLocations->initialisation();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error init actions locations ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'afb6250a');
        }
        try {
            $this->_metrologyInstance->addLog('init actions applications', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '85e057f9');
            $this->_instanceActionsApplications = new ActionsApplications($this->_nebuleInstance);
            $this->_instanceActionsApplications->setEnvironmentLibrary($this->_nebuleInstance);
            $this->_instanceActionsApplications->setEnvironmentApplication($this->_applicationInstance);
            //$this->_instanceActionsApplications->initialisation();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error init actions applications ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '8d8d0b27');
        }
        try {
            $this->_metrologyInstance->addLog('init actions marks', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '82a6ada5');
            $this->_instanceActionsMarks = new ActionsMarks($this->_nebuleInstance);
            $this->_instanceActionsMarks->setEnvironmentLibrary($this->_nebuleInstance);
            $this->_instanceActionsMarks->setEnvironmentApplication($this->_applicationInstance);
            //$this->_instanceActionsMarks->initialisation();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error init actions marks ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '44813857');
        }
    }



    public function getInstanceActionsLinks(): ?ActionsLinks { return $this->_instanceActionsLinks; }
    public function getInstanceActionsObjects(): ?ActionsObjects { return $this->_instanceActionsObjects; }
    public function getInstanceActionsEntities(): ?ActionsEntities { return $this->_instanceActionsEntities; }
    public function getInstanceActionsGroups(): ?ActionsGroups { return $this->_instanceActionsGroups; }
    public function getInstanceActionsLocations(): ?ActionsLocations { return $this->_instanceActionsLocations; }
    public function getInstanceActionsApplications(): ?ActionsApplications { return $this->_instanceActionsApplications; }
    public function getInstanceActionsMarks(): ?ActionsMarks { return $this->_instanceActionsMarks; }



    public function getActions(): void {
        $this->specialActions();
        $this->genericActions();
        $this->applicationsActions();
        $this->modulesActions();
    }



    public function specialActions(): void {
        $this->_metrologyInstance->addLog('call special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6f9dfb64');
        try {
            $this->_metrologyInstance->addLog('call special actions links', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '435ee7f6');
            $this->_instanceActionsLinks->specialActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call special actions links ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '211aa041');
        }
        try {
            $this->_metrologyInstance->addLog('call special actions objects', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5070a01f');
            $this->_instanceActionsObjects->specialActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call special actions objects ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e85d6df2');
        }
        try {
            $this->_metrologyInstance->addLog('call special actions entities', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9684d61b');
            $this->_instanceActionsEntities->specialActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call special actions entities ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '8000113d');
        }
        try {
            $this->_metrologyInstance->addLog('call special actions groups', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4299833c');
            $this->_instanceActionsGroups->specialActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call special actions groups ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '18dd4a7c');
        }
        try {
            $this->_metrologyInstance->addLog('call special actions locations', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '26a04f10');
            $this->_instanceActionsLocations->specialActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call special actions locations ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '5d2b7c0b');
        }
        try {
            $this->_metrologyInstance->addLog('call special actions applications', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '792ea58c');
            $this->_instanceActionsApplications->specialActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call special actions applications ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'cc89cc54');
        }
        try {
            $this->_metrologyInstance->addLog('call special actions marks', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '3553b65b');
            $this->_instanceActionsMarks->specialActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call special actions marks ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '327969ed');
        }
        $this->_metrologyInstance->addLog('call special actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '88ff0291');
    }

    public function genericActions(): void {
        $this->_metrologyInstance->addLog('call generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '84b627f1');
        if (!$this->_tokenizeInstance->checkActionToken() || !$this->_entitiesInstance->getConnectedEntityIsUnlocked())
            return; // Do not accept action without a valid token or an unlocked entity.
        try {
            $this->_metrologyInstance->addLog('call generic actions links', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '2c57d443');
            $this->_instanceActionsLinks->genericActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call generic actions links ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e1fc5a04');
        }
        try {
            $this->_metrologyInstance->addLog('call generic actions objects', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '7f459074');
            $this->_instanceActionsObjects->genericActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call generic actions objects ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '9008f242');
        }
        try {
            $this->_metrologyInstance->addLog('call generic actions entities', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '41f64673');
            $this->_instanceActionsEntities->genericActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call generic actions entities ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '06dc58f3');
        }
        try {
            $this->_metrologyInstance->addLog('call generic actions groups', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4ea9f4d7');
            $this->_instanceActionsGroups->genericActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call generic actions groups ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '7f12f7b9');
        }
        try {
            $this->_metrologyInstance->addLog('call generic actions locations', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c187a9f3');
            $this->_instanceActionsLocations->genericActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call generic actions locations ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e0c4cc52');
        }
        try {
            $this->_metrologyInstance->addLog('call generic actions applications', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5deb30a7');
            $this->_instanceActionsApplications->genericActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call generic actions applications ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '12dcf93c');
        }
        try {
            $this->_metrologyInstance->addLog('call generic actions marks', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5469549b');
            $this->_instanceActionsMarks->genericActions();
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('error call generic actions marks ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '1d9d6581');
        }
        $this->_metrologyInstance->addLog('call generic actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b2046b14');
    }

    public function applicationsActions():void {}

    public function modulesActions():void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('call modules actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9476cec8');
        if (!$this->_tokenizeInstance->checkActionToken())
            return; // Do not accept module action without a valid token.
        $module = $this->_applicationInstance->getApplicationModulesInstance()->getCurrentModuleInstance();
        if ($module::MODULE_COMMAND_NAME == $this->_displayInstance->getCurrentDisplayMode()) {
            try {
                $this->_metrologyInstance->addLog('actions for module ' . $module::MODULE_COMMAND_NAME, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '55fba077');
                $module->actions();
            } catch (\Throwable $e) {
                $this->_metrologyInstance->addLog('error actions for module ' . $module::MODULE_COMMAND_NAME
                    . ' ('  . $e->getCode() . ') : ' . $e->getFile()
                    . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                    . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c48b1d8c');
            }
        }
        $this->_metrologyInstance->addLog('call modules actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '27b77f2d');
    }

    public function getDisplayActions(): void
    {
        $this->_displayInstance->displayInlineLastAction(); // FIXME
    }



    // Common functions for subclasses
    protected function _changeProperty(string $nameInput, string $nameProperty, string $social = 'self'): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateLink'))
            return false;
        $instance = $this->_nebuleInstance->getCurrentEntityInstance();
        $oldValue = match ($nameProperty) {
            References::REFERENCE_NEBULE_OBJET_NOM => $instance->getName($social),
            References::REFERENCE_NEBULE_OBJET_PRENOM => $instance->getFirstname($social),
            References::REFERENCE_NEBULE_OBJET_SURNOM => $instance->getSurname($social),
            References::REFERENCE_NEBULE_OBJET_PREFIX => $instance->getPrefixName($social),
            References::REFERENCE_NEBULE_OBJET_SUFFIX => $instance->getSuffixName($social),
            default => '',
        };
        $newValue = $this->getFilterInput($nameInput, FILTER_FLAG_NO_ENCODE_QUOTES);
        if ($newValue == $oldValue) {
            $this->_metrologyInstance->addLog('same ' . $nameProperty . ', no change', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '69a9562e');
            return true;
        }
        if ($newValue != '' && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')) {
            $this->_metrologyInstance->addLog('change ' . $nameProperty . '=' . $newValue, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '83fb3258');
            $this->_deleteProperty($nameProperty);
            return $instance->setProperty($nameProperty, $newValue);
        } else
            return $this->_deleteProperty($nameProperty);
    }
    protected function _deleteProperty(string $name): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_nebuleInstance->getCurrentEntityInstance();
        $inputLinks = $instance->getPropertiesLinks($name, 'all');
        if (sizeof($inputLinks) == 0)
            return false;
        $this->_metrologyInstance->addLog('delete property ' . $name, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'c92cb974');
        foreach ($inputLinks as $inputLink) {
            $bloclink = new BlocLink($this->_nebuleInstance, 'new');
            $nid1 = $instance->getID();
            $nid2 = $inputLink->getParsed()['bl/rl/nid2'];
            $nid3 = $inputLink->getParsed()['bl/rl/nid3'];
            $bloclink->addLink('x>' . $nid1 . '>' . $nid2 . '>' . $nid3);
            $bloclink->signWrite();
            $this->_metrologyInstance->addLog('add delete link ' . 'x>' . $nid1 . '>' . $nid2 . '>' . $nid3, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        }
        return true;
    }
}
