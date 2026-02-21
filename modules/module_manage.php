<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Library\nebule;
use Nebule\Library\References;
use Nebule\Library\Metrology;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Actions;
use Nebule\Library\Translates;
use Nebule\Library\ModuleInterface;
use Nebule\Library\Module;
use Nebule\Library\ModelModuleHelp;
use Nebule\Library\ModuleTranslates;

/**
 * Ce module permet de gérer les applications.
 *
 * Les modules sont référencés par leur RID à l'exception de certains modules intégrés à l'application.
 * Il faut faire un tri pour l'affichage par rapport au nom de la classe des modules pour les distinguer correctement.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleManage extends Module
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'modmanager';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260106';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2026';
    const MODULE_LOGO = '8dc6a54b72778131a427e2b36df04d4a3fa036b1275868bd060e9dbf8b7493e4.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('list', 'disp', 'add', 'cod');
    const MODULE_REGISTERED_ICONS = array(
        '8dc6a54b72778131a427e2b36df04d4a3fa036b1275868bd060e9dbf8b7493e4.sha2.256',    // 0 Module
        '37be5ba2a53e9835dbb0ff67a0ece1cc349c311660e4779680ee2daa4ac45636.sha2.256',    // 1 Ajout d'un module
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('8dc6a54b72778131a427e2b36df04d4a3fa036b1275868bd060e9dbf8b7493e4.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    // Constantes spécifiques à la création de liens.
    const DEFAULT_COMMAND_ACTION_NOM = 'actaddnam';
    const DEFAULT_COMMAND_ACTION_DESC = 'actadddsc';
    const DEFAULT_COMMAND_ACTION_RID = 'actaddrid';
    const DEFAULT_COMMAND_ACTION_RIDC = 'actchrid';
    const DEFAULT_COMMAND_ACTION_ID = 'actaddid';



    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    protected function _initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string                $hookName
     * @param ?\Nebule\Library\Node $instance
     * @return array
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $instance = null): array
    {
        $nid = $this->_applicationInstance->getCurrentObjectID();
        if ($instance !== null)
            $nid = $instance->getID();

        $listModulesRID = $this->_applicationInstance->getModulesListRID();

        $hookArray = array();
        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuManage':
                // Affichage des applications.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[0]) {
                    $hookArray[0]['name'] = '::AppTitle1';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '::AppDesc1';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // Synchronisation des applications.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[0]
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeObject')
                    && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeApplication')
                    && ($this->_configurationInstance->getOptionAsBoolean('permitPublicSynchronizeApplication')
                        || $this->_unlocked
                    )
                ) {
                    $hookArray[0]['name'] = '::syncModules';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '/?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $nid
                        . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $this->getExtractCommandDisplayModule()
                        . '&' . \Nebule\Library\ActionsApplications::SYNCHRONIZE . '=0'
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                }

                // Ajout d'un module.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[0]
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitUploadLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    && $this->_unlocked
                ) {
                    $hookArray[1]['name'] = '::create:createModule';
                    $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // Synchronisation du module.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeObject')
                    && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeApplication')
                    && isset($listModulesRID[$this->getExtractCommandDisplayModule()])
                    && ($this->_configurationInstance->getOptionAsBoolean('permitPublicSynchronizeApplication')
                        || $this->_unlocked
                    )
                ) {
                    $hookArray[2]['name'] = '::syncModule';
                    $hookArray[2]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $nid
                        . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $this->getExtractCommandDisplayModule()
                        . '&' . \Nebule\Library\ActionsApplications::SYNCHRONIZE . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                }

                // Modification du code du module.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && isset($listModulesRID[$this->getExtractCommandDisplayModule()])
                    && $this->_unlocked
                ) {
                    $hookArray[3]['name'] = '::changeCode';
                    $hookArray[3]['icon'] = Displays::DEFAULT_ICON_LU;
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $nid
                        . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $this->getExtractCommandDisplayModule()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     *
     * @return void
     */
    public function displayModule(): void
    {
        switch ($this->_displayInstance->getCurrentDisplayView()) {
//            case $this::MODULE_REGISTERED_VIEWS[0]:
//                $this->_displayModules();
//                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayModule();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayCreateModule();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayChangeCode();
                break;
            default:
                $this->_displayModules();
                break;
        }
    }

    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    public function displayModuleInline(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineModules();
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineModule();
                break;
        }
    }

    /**
     * Affichage de surcharges CSS.
     *
     * @return void
     */
    public function headerStyle(): void
    {
        ?>

        .moduleModuleListOneAction { margin:2px; padding:5px; float:left; background:rgba(255,255,255,0.7); width:386px; min-height:114px; }
        .moduleModuleListOneAction-state { float:right; margin-left:5px; }
        #moduleModuleListOneAction-text { clear:both; padding-top:5px; }
        .moduleManageFlags img { float:none; }
        <?php
    }


    /**
     * Action principale.
     *
     * @return void
     */
    public function actions(): void
    {
        // Création d'un module.
        $this->_extractActionAddModule();
        if ($this->_actionChangeModule) {
            $this->_actionAddModule();
        }

        // Changement du code d'un module.
        $this->_extractActionAddModuleCode();
        if ($this->_actionAddModuleCode) {
            $this->_actionAddModuleCode();
        }
    }


    private $_actionAddModuleName = '';
    private $_actionAddModuleRID = '';
    private $_actionAddModuleID = '';
    private $_actionChangeModule = false;
    private $_actionAddModuleCode = false;

    /**
     * Extrait pour action si on doit créer et signer un lien à partir de ces composants.
     */
    private function _extractActionAddModule(): void
    {
        // Vérifie que la création de liens soit authorisé.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add module', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '468a2957');

            $arg_name = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_NOM);
            $arg_rid = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_RID, FILTER_FLAG_ENCODE_LOW);

            // Écriture des variables.
            if ($arg_name != '') {
                $this->_actionAddModuleName = $arg_name;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add module NAME:' . $arg_name, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '8e89341e');
            }
            if (\Nebule\Library\Node::checkNID($arg_rid)) {
                $this->_actionAddModuleRID = $arg_rid;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add module RID:' . $arg_rid, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '8b454112');
            }

            // Vérification du minimum pour la création.
            if ($this->_actionAddModuleName != ''
                && $this->_actionAddModuleRID != ''
            ) {
                $this->_actionChangeModule = true;
            }
        }
    }

    /**
     * Extrait pour action si on doit changer le code d'un module.
     */
    private function _extractActionAddModuleCode(): void
    {
        // Vérifie que la crétion de liens soit authorisé.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add code module', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9e20fc27');

            $arg_rid = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_RIDC, FILTER_FLAG_ENCODE_LOW);
            $arg_id = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ID, FILTER_FLAG_ENCODE_LOW);

            if (\Nebule\Library\Node::checkNID($arg_rid)) {
                $this->_actionAddModuleRID = $arg_rid;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add code module RID:' . $arg_id, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'c7317945');
            }
            if (\Nebule\Library\Node::checkNID($arg_id)
                && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($arg_id)
                && $this->_nebuleInstance->getIoInstance()->checkLinkPresent($arg_id)
                && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($arg_id)
                && $this->_nebuleInstance->getIoInstance()->checkLinkPresent($arg_id)
                && $arg_id != $arg_rid
            ) {
                $this->_actionAddModuleID = $arg_id;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add code module ID:' . $arg_id, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '86688bd0');
            }

            // Vérification du minimum pour la création.
            if ($this->_actionAddModuleRID != ''
                && $this->_actionAddModuleID != ''
            )
                $this->_actionAddModuleCode = true;
        }
    }

    /**
     * Génère un lien depuis ses composants.
     */
    private function _actionAddModule(): void
    {
        global $bootstrapApplicationIID;

        // Vérifie que la création de liens soit authorisée.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Action add module', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '25403222');

            // Crée l'objet de la référence de l'application.
            $instance = new \Nebule\Library\Node($this->_nebuleInstance, $this->_actionAddModuleRID, '', false, false);

            // Création du type mime.
            $instance->setType(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES);

            // Crée le lien de hash.
            $date = date(DATE_ATOM);
            $signer = $this->_entitiesInstance->getGhostEntityEID();
            $action = 'l';
            $source = $this->_actionAddModuleRID;
            $target = $this->getNidFromData($this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm'));
            $meta = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_HASH);
            $this->_createLink_DEPRECATED($signer, $date, $action, $source, $target, $meta, false);

            // Crée l'objet du nom.
            $instance->setName($this->_actionAddModuleName);

            // Crée le lien de référence.
            $action = 'f';
            $source = References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES;
            $target = $this->_actionAddModuleRID;
            $meta = References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES;
            $this->_createLink_DEPRECATED($signer, $date, $action, $source, $target, $meta, false);

            // Crée le lien d'activation dans l'application.
            $source = $bootstrapApplicationIID;
            $this->_createLink_DEPRECATED($signer, $date, $action, $source, $target, $meta, false);
        }
    }

    /**
     * Génère un lien depuis ses composants.
     */
    private function _actionAddModuleCode(): void
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Action add code ' . $this->_actionAddModuleID . ' to module ' . $this->_actionAddModuleRID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '128f5522');

            // Crée le lien du code pour l'application.
            $date = date(DATE_ATOM);
            $signer = $this->_entitiesInstance->getGhostEntityEID();
            $action = 'f';
            $source = $this->_actionAddModuleRID;
            $target = $this->_actionAddModuleID;
            $meta = References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES;
            $this->_createLink_DEPRECATED($signer, $date, $action, $source, $target, $meta, false);

            // Flush le cache de l'objet du module pour que les changements soient pris en compte tout de suite.
            $this->_cacheInstance->unsetObjectOnCache($this->_actionAddModuleRID); // @todo ne marche pas...
        }
    }


    /**
     * Affichage de la liste des modules.
     *
     * @return void
     */
    private function _displayModules(): void
    {
        $icon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[0]);
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::Modules');
        $instance->setIcon($icon);
        $instance->display();

        // Affichage la liste des modules.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('modlist');
    }


    /**
     * Affichage de la liste des modules, en ligne.
     *
     * @return void
     */
    private function _display_InlineModules(): void
    {
        // Vérifie que l'entité est connectée.
        if (!$this->_unlocked && false) { // FIXME déblocage pour test...
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'informationType' => 'error',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::err_NotPermit', $param);

            return;
        }

        $listModules = $this->_applicationInstance->getModulesListInstances();
        $listModulesValid = $this->_applicationInstance->getModulesListValid();
        $listModulesEnabled = $this->_applicationInstance->getModulesListEnabled();
        $listModulesRID = $this->_applicationInstance->getModulesListRID();
        $listModulesSignerRID = $this->_applicationInstance->getModulesListSignersRID();
        $listOkModules = array();


        $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('Size ' . sizeof($listModules));

        ?>

        <div class="layoutObjectsList">
            <div class="objectsListContent">
                <?php
                // Affiche les différentes applications.
                foreach ($listModules as $moduleName => $instance) {
                    $name = $instance::MODULE_NAME;
                    if (is_a($instance, '\Nebule\Library\Module')
                        && !isset($listOkModules[$name])
                    ) {
                        $className = get_class($instance);
                        $rid = '0';
                        if (isset($listModulesRID[$className])
                            && $listModulesRID[$className] != ''
                            && $listModulesRID[$className] != '0'
                        )
                            $rid = $listModulesRID[$className];

                        $param = array(
                            'enableDisplayColor' => false,
                            'enableDisplayIcon' => true,
                            'enableDisplayName' => true,
                            'enableDisplayID' => false,
                            'enableDisplayRefs' => true,
                            'enableDisplayFlags' => true,
                            'enableDisplayFlagEmotions' => false,
                            'enableDisplayFlagProtection' => false,
                            'enableDisplayFlagObfuscate' => false,
                            'enableDisplayFlagUnlocked' => false,
                            'enableDisplayFlagActivated' => true,
                            'enableDisplayLink2Object' => true,
                            'enableDisplayObjectActions' => false,
                            'enableDisplayJS' => true,
                            'link2Object' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                                . '&' . References::COMMAND_SELECT_OBJECT . '=' . $rid
                                . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $className,
                            'objectName' => $instance->getTranslate($name, $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage()),
                            'objectRefs' => array(),
                            'objectIcon' => $instance::MODULE_LOGO,
                            'displaySize' => 'medium',
                            'displayRatio' => 'short',
                        );

                        // Module (non) intégré à l'application.
                        if (isset($listModulesSignerRID[$className])
                            && $listModulesSignerRID[$className] != ''
                            && $listModulesSignerRID[$className] != '0'
                        )
                            $param['objectRefs'][] = $listModulesSignerRID[$className];

                        // Module (non) intégré à l'application.
                        if (isset($listModulesRID[$className])
                            && $listModulesRID[$className] != ''
                            && $listModulesRID[$className] != '0'
                        )
                            $param['enableDisplayFlagState'] = true;
                        else
                            $param['enableDisplayFlagState'] = false;

                        // Module valide (si non intégré).
                        if ($param['enableDisplayFlagState']) {
                            if (isset($listModulesValid[$className])
                                && $listModulesValid[$className]
                            ) {
                                $param['flagState'] = 'o';
                                $param['flagStateDesc'] = '::ModuleValid';
                            } else {
                                $param['flagState'] = 'e';
                                $param['flagStateDesc'] = '::ModuleInvalid';
                            }
                        }

                        // Module activé.
                        if (isset($listModulesEnabled[$className])
                            && $listModulesEnabled[$className]
                        ) {
                            $param['flagActivated'] = true;
                            $param['flagActivatedDesc'] = '::ModuleEnabled';
                        } else {
                            $param['flagActivated'] = false;
                            $param['flagActivatedDesc'] = '::ModuleDisabled';
                        }
                        $instance = $this->_cacheInstance->newNodeByType('0'); // FIXME
                        echo $this->_displayInstance->getDisplayObject_DEPRECATED($instance, $param);

                        // Marque comme vu.
                        $listOkModules[$name] = true;
                    } else
                        $this->_applicationInstance->getDisplayInstance()->displayMessageWarning('Try ' . $moduleName);
                }
                ?>

            </div>
        </div>
        <?php
    }


    /**
     * Affichage du module et de ses caractéristiques.
     *
     * @return void
     */
    private function _displayModule(): void
    {
        $icon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[0]);
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::Module');
        $instance->setIcon($icon);
        $instance->display();

        // Affichage du module avec transmission de la variable d'affichage.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('moddisp', self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $this->getExtractCommandDisplayModule());
    }


    /**
     * Affichage du module et de ses caractéristiques, en ligne.
     *
     * @return void
     */
    private function _display_InlineModule(): void
    {
        // Extrait le module à afficher.
        $classNameCommand = $this->getExtractCommandDisplayModule();

        $listModulesInstances = $this->_applicationInstance->getModulesListInstances();
        $listModulesID = $this->_applicationInstance->getModulesListOID();
        $listModulesRID = $this->_applicationInstance->getModulesListRID();
        $listModulesEnabled = $this->_applicationInstance->getModulesListEnabled();
        $listModulesValid = $this->_applicationInstance->getModulesListValid();
        $listModulesSignersRID = $this->_applicationInstance->getModulesListSignersRID();

        // Vérifie que c'est un objet.
        if (!isset($listModulesValid[$classNameCommand])) {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'informationType' => 'error',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::display:noModule', $param);

            return;
        }

        // Vérifie que l'entité est connectée.
        if (!$this->_unlocked) {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'informationType' => 'error',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::err_NotPermit', $param);

            return;
        }

        // Recherche le module à afficher.
        $className = '';
        foreach ($listModulesInstances as $instance) {
            $className = get_class($instance);
            if ($className == $classNameCommand) {
                break;
            }
        }
        if ($className == '') {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'informationType' => 'error',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::err_NotPermit', $param);

            return;
        }

        $rid = '0';
        $id = '0';
        $instance = $listModulesInstances[$classNameCommand];

        // RID.
        if (isset($listModulesRID[$className])
            && $listModulesRID[$className] != ''
            && $listModulesRID[$className] != '0'
        ) {
            $rid = $listModulesRID[$className];
        }

        // ID.
        if (isset($listModulesID[$className])
            && $listModulesID[$className] != ''
            && $listModulesID[$className] != '0'
        ) {
            $id = $listModulesID[$className];
        }

        ?>

        <div class="layoutObjectsList">
            <div class="objectsListContent">
                <?php

                // Affichage du titre du module.
                $param = array(
                    'enableDisplayColor' => false,
                    'enableDisplayIcon' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayRefs' => true,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagEmotions' => false,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagActivated' => false,
                    'enableDisplayLink2Object' => false,
                    'enableDisplayObjectActions' => false,
                    'enableDisplayJS' => false,
                    'objectName' => $instance->getTraduction($instance::MODULE_DESCRIPTION),
                    'objectIcon' => $instance::MODULE_LOGO,
                    'objectRefs' => $instance->getTraduction($instance::MODULE_NAME, $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage()),
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                );
                echo $this->_displayInstance->getDisplayObject_DEPRECATED($instance, $param);

                // description du module.
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'informationType' => 'information',
                );
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED($instance->getTraduction($instance::MODULE_HELP), $param);

                // Affiche l'application (RID).
                if ($rid != '0') {
                    $object = $this->_cacheInstance->newNodeByType($rid);
                    $param = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => true,
                        'enableDisplayName' => true,
                        'enableDisplayID' => true,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => false,
                        'enableDisplayFlagState' => false,
                        'enableDisplayFlagEmotions' => true,
                        'enableDisplayStatus' => true,
                        'enableDisplayContent' => false,
                        'objectName' => $instance->getTraduction($instance::MODULE_NAME, $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage()),
                        'objectIcon' => Displays::DEFAULT_ICON_LO,
                        'displaySize' => 'medium',
                        'displayRatio' => 'long',
                        'enableDisplayLink2Object' => true,
                        'enableDisplayObjectActions' => true,
                        'enableDisplaySelfHook' => false,
                        'enableDisplayTypeHook' => false,
                        'objectRefs' => array(
                            '0' => $listModulesSignersRID[$className]),
                        'enableDisplayJS' => false,
                        'status' => 'RID',
                    );
                    if ($this->_applicationInstance->getApplicationModulesInstance()->getIsModuleLoaded('ModuleReferences'))
                    {
                        $param['link2Object'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getModule('ModuleReferences')::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getModule('ModuleReferences')::MODULE_REGISTERED_VIEWS[0]
                            . '&' . $this->_applicationInstance->getModule('ModuleReferences')::MODULE_COMMAND_NAME . '=' . References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES
                            . '&' . References::COMMAND_SELECT_OBJECT . '=' . $rid;
                        $param['objectIcon'] = $this->_applicationInstance->getModule('ModuleReferences')::MODULE_LOGO;
                    }
                    echo $this->_displayInstance->getDisplayObject_DEPRECATED($object, $param);
                } else {
                    $param = array(
                        'enableDisplayIcon' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'long',
                        'informationType' => 'warn',
                    );
                    echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::display:integratedModule', $param);
                }

                // ID
                if ($id != '0') {
                    $object = $this->_cacheInstance->newNodeByType($id);
                    $param['objectName'] = '';
                    $param['status'] = '';
                    $param['link2Object'] = '';
                    $param['objectRefs'] = array(
                        '0' => $listModulesSignersRID[$id],
                    );
                    $param['objectIcon'] = $this::MODULE_LOGO;
                    $param['enableDisplayFlagState'] = true;
                    $param['enableDisplayFlagProtection'] = true;
                    echo $this->_displayInstance->getDisplayObject_DEPRECATED($object, $param);
                }

                ?>

            </div>
            <div class="objectsListContent">
                <?php
                // Etat de validité du module.
                if (isset($listModulesValid[$className])
                    && $listModulesValid[$className]
                ) {
                    $param = array(
                        'enableDisplayIcon' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                        'informationType' => 'ok',
                    );
                    echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::ModuleValid', $param);
                } else {
                    $param = array(
                        'enableDisplayIcon' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                        'informationType' => 'error',
                    );
                    echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::ModuleInvalid', $param);
                }

                // Activation du module.
                if ($this->_unlocked
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && ($this->_entitiesInstance->getGhostEntityEID() == $this->_authoritiesInstance->getCodeMaster()
                        || ($this->_entitiesInstance->getGhostEntityEID() == $this->_entitiesInstance->getDefaultEntityEID()
                            && $this->_configurationInstance->getOptionAsBoolean('permitServerEntityAsAuthority') )
                        || ($this->_entitiesInstance->getGhostEntityEID() == $this->_entitiesInstance->getDefaultEntityEID()
                            && $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority'))
                    )
                    && $rid != '0'
                ) {
                    $dispHook = array(
                        'hookType' => 'Self',
                        'cssid' => '',
                        'moduleName' => $this->_translateInstance->getTranslate($this::MODULE_NAME),
                    );
                    if (isset($listModulesEnabled[$className])
                        && $listModulesEnabled[$className]
                    ) {
                        $dispHook['link'] = '/?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $className
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=x>' . $rid . '>' . References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_ACTIVE . '>' . $rid // FIXME
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                        $dispHook['icon'] = Displays::DEFAULT_ICON_IOK;
                        $dispHook['name'] = $this->_translateInstance->getTranslate('::ModuleEnabled');
                        $dispHook['desc'] = $this->_translateInstance->getTranslate('::ModuleDisable');
                    } else {
                        $dispHook['link'] = '/?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $className
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=f>' . $rid . '>' . References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_ACTIVE . '>' . $rid // FIXME
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                        $dispHook['icon'] = Displays::DEFAULT_ICON_IERR;
                        $dispHook['name'] = $this->_translateInstance->getTranslate('::ModuleDisabled');
                        $dispHook['desc'] = $this->_translateInstance->getTranslate('::ModuleEnable');
                    }
                    echo $this->_displayInstance->getDisplayHookAction($dispHook, true, 'MediumShort');
                } else {
                    $param = array(
                        'enableDisplayIcon' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                    );
                    if (isset($listModulesEnabled[$className])
                        && $listModulesEnabled[$className]
                    ) {
                        $param['informationType'] = 'ok';
                        echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::ModuleEnabled', $param);
                    } else {
                        $param['informationType'] = 'error';
                        echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::ModuleDisabled', $param);
                    }
                }

                // Classe.
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'informationType' => 'information',
                );
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED('Class : ' . $className, $param);

                // Version.
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'informationType' => 'information',
                );
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED('Version : ' . $instance::MODULE_VERSION, $param);

                // Interface.
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'informationType' => 'information',
                );
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED('Interface : ' . $instance::MODULE_INTERFACE, $param);

                // Licence.
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'informationType' => 'information',
                );
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED($instance->getDevelopper() . ' <br />' . $instance::MODULE_LICENCE, $param);

                // Synchronisation de l'application.
                /*		if ( $this->_configuration->getOptionAsString('permitWrite')
				&& $this->_configuration->getOptionAsString('permitWriteObject')
				&& $this->_configuration->getOptionAsString('permitWriteLink')
				&& $this->_configuration->getOptionAsString('permitSynchronizeObject')
				&& $this->_configuration->getOptionAsString('permitSynchronizeLink')
				&& $this->_configuration->getOptionAsString('permitSynchronizeApplication')
				&& ( $this->_configuration->getOptionAsString('permitPublicSynchronizeApplication')
						|| $this->_unlocked
					)
				&& $rid != '0'
			)
		{
			$dispHook = array(
					'hookType' => 'Self',
					'cssid' => '',
					'moduleName' => $this->_translateInstance->getTranslate($this::MODULE_NAME),
					'link' => '/?'.Displays::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this::MODULE_COMMAND_NAME
							.'&'.Displays::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this::MODULE_REGISTERED_VIEWS[1]
							.'&'.self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE.'='.$className
							.'&'.Action::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION.'='.$rid
							.$this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(),
					'icon' => Display::DEFAULT_ICON_SYNOBJ,
					'name' => $this->_translateInstance->getTranslate('::syncModule'),
					'desc' => '',
			);
			echo $this->_display->getDisplayHookAction($dispHook, true, 'MediumShort');
		}*/

                ?>

            </div>
        </div>
        <?php
    }


    /**
     * Affichage de l'ajout d'un module.
     *
     * @return void
     */
    private function _displayCreateModule(): void
    {
        $icon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[0]);
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::create:createModule');
        $instance->setIcon($icon);
        $instance->display();

        // Si autorisé à créer des liens.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_unlocked
        ) {
            ?>

            <div class="layoutObjectsList">
                <div class="objectsListContent">
                    <form enctype="multipart/form-data" method="post"
                          action="<?php echo '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                              . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                              . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(); ?>">
                        <?php echo $this->_translateInstance->getTranslate('::create:nom'); ?><br/>
                        <input type="text"
                               name="<?php echo self::DEFAULT_COMMAND_ACTION_NOM; ?>"
                               size="80" value="<?php echo $this->_translateInstance->getTranslate('::create:nom'); ?>"/><br/>
                        RID<br/>
                        <input type="text"
                               name="<?php echo self::DEFAULT_COMMAND_ACTION_RID; ?>"
                               size="80" value=""/><br/>
                        <input type="submit"
                               value="<?php echo $this->_translateInstance->getTranslate('::create:SubmitCreate') ?>"/>
                    </form>
                </div>
            </div>
            <?php
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::err_NotPermit', $param);
        }
    }


    /**
     * Affichage de l'ajout d'un code à un module.
     *
     * @return void
     */
    private function _displayChangeCode(): void
    {
        $icon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[0]);
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::create:addModuleCode');
        $instance->setIcon($icon);
        $instance->display();

        // Extrait le RID si nouveau module créé.
        $arg_rid = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_RID, FILTER_FLAG_ENCODE_LOW);
        if (\Nebule\Library\Node::checkNID($arg_rid)) {
            $rid = $arg_rid;
            $newCode = true;
        } else {
            $rid = $this->_nebuleInstance->getCurrentObjectOID();
            $newCode = false;
        }

        // Si autorisé à créer des liens.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $rid != '0'
            && $this->_unlocked
        ) {
            if ($rid != '0'
                && ($newCode
                    || isset($this->_applicationsList[$rid])
                )
            ) {
                // Affichage du module concerné.
                $ridInstance = $this->_cacheInstance->newNodeByType($rid);
                $param = array(
                    'enableDisplayColor' => false,
                    'enableDisplayIcon' => false,
                    'enableDisplayIconApp' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayRefs' => true,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagEmotions' => false,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagActivated' => false,
                    'enableDisplayLink2Object' => false,
                    'enableDisplayObjectActions' => false,
                    'enableDisplayJS' => false,
                    'objectRefs' => $this->_applicationsSignersList[$rid],
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );
                echo $this->_displayInstance->getDisplayObject_DEPRECATED($ridInstance, $param);
                ?>

                <div class="layoutObjectsList">
                    <div class="objectsListContent">
                        <form enctype="multipart/form-data" method="post"
                              action="<?php echo '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                  . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                                  . '&' . References::COMMAND_SELECT_OBJECT . '=' . $rid
                                  . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(); ?>">
                            <input type="hidden"
                                   name="<?php echo self::DEFAULT_COMMAND_ACTION_RIDC; ?>"
                                   value="<?php echo $rid; ?>"/><br/>
                            ID<br/>
                            <input type="text"
                                   name="<?php echo self::DEFAULT_COMMAND_ACTION_ID; ?>"
                                   size="80" value=""/><br/>
                            <input type="submit"
                                   value="<?php echo $this->_translateInstance->getTranslate('::create:SubmitChange') ?>"/>
                        </form>
                    </div>
                </div>
                <?php
            } else {
                $param = array(
                    'enableDisplayAlone' => true,
                    'enableDisplayIcon' => true,
                    'informationType' => 'error',
                    'displayRatio' => 'short',
                );
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::display:noModule', $param);
            }
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'short',
            );
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::err_NotPermit', $param);
        }
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des modules',
            '::MenuName' => 'Modules',
            '::ModuleDescription' => 'Module de gestion des modules.',
            '::ModuleHelp' => 'Ce module permet de voir les modules détectés par une application.',
            '::AppTitle1' => 'Modules',
            '::AppDesc1' => 'Module de gestion des modules.',
            '::Module' => 'Le module',
            '::Modules' => 'Les modules',
            '::display:noModule' => 'Pas de module.',
            '::display:integratedModule' => "Ce module est intégré à l'application, il ne peut pas être modifié.",
            '::ModuleValid' => 'Module valide.',
            '::ModuleInvalid' => 'Module invalide !',
            '::ModuleEnabled' => 'Module activé.',
            '::ModuleDisabled' => 'Module désactivé !',
            '::ModuleEnable' => 'Activer ?.',
            '::ModuleDisable' => 'Désactivé ?',
            '::display:noCode' => 'Pas de code !',
            '::syncModule' => 'Synchroniser le module',
            '::syncModules' => 'Synchroniser les modules',
            '::changeCode' => 'Mettre à jour le code',
            '::create:createModule' => 'Créer un module',
            '::create:addModuleCode' => 'Ajouter un code',
            '::create:nom' => 'Nom',
            '::create:Desc' => 'Description',
            '::create:SubmitCreate' => 'Créer',
            '::create:SubmitChange' => 'Changer',
        ],
        'en-en' => [
            '::ModuleName' => 'Module of modules',
            '::MenuName' => 'Modules',
            '::ModuleDescription' => 'Module to manage modules.',
            '::ModuleHelp' => 'This module permit to see modules detected by an application.',
            '::AppTitle1' => 'Modules',
            '::AppDesc1' => 'Manage modules.',
            '::Module' => 'The module',
            '::Modules' => 'The modules',
            '::display:noModule' => 'No module.',
            '::display:integratedModule' => "This module is integrated to the application, it can't be modified.",
            '::ModuleValid' => 'Valid module',
            '::ModuleInvalid' => 'Invalid module!',
            '::ModuleEnabled' => 'Module enabled.',
            '::ModuleDisabled' => 'Module disabled!',
            '::ModuleEnable' => 'Enable?',
            '::ModuleDisable' => 'Disable?',
            '::display:noCode' => 'No code!',
            '::syncModule' => 'Synchronize the module',
            '::syncModules' => 'Synchronize the modules',
            '::changeCode' => 'Update the code',
            '::create:createModule' => 'Create a module',
            '::create:addModuleCode' => 'Add a code',
            '::create:nom' => 'Name',
            '::create:Desc' => 'Description',
            '::create:SubmitCreate' => 'Create',
            '::create:SubmitChange' => 'Change',
        ],
        'es-co' => [
            '::ModuleName' => 'Module of modules',
            '::MenuName' => 'Modules',
            '::ModuleDescription' => 'Module to manage modules.',
            '::ModuleHelp' => 'This module permit to see modules detected by an application.',
            '::AppTitle1' => 'Modules',
            '::AppDesc1' => 'Manage modules.',
            '::Module' => 'El modulo',
            '::Modules' => 'Los modulos',
            '::display:noModule' => 'No modulo.',
            '::display:integratedModule' => "This module is integrated to the application, it can't be modified.",
            '::ModuleValid' => 'Valid module',
            '::ModuleInvalid' => 'Invalid module!',
            '::ModuleEnabled' => 'Module enabled.',
            '::ModuleDisabled' => 'Module disabled!',
            '::ModuleEnable' => 'Enable?',
            '::ModuleDisable' => 'Disable?',
            '::display:noCode' => 'No code!',
            '::syncModule' => 'Synchronize the module',
            '::syncModules' => 'Synchronize the modules',
            '::changeCode' => 'Update the code',
            '::create:createModule' => 'Create a module',
            '::create:addModuleCode' => 'Add a code',
            '::create:nom' => 'Name',
            '::create:Desc' => 'Description',
            '::create:SubmitCreate' => 'Create',
            '::create:SubmitChange' => 'Change',
        ],
    ];
}
