<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Displays;
use Nebule\Library\Metrology;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\References;

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
class ModuleManage extends Modules
{
    protected string $MODULE_TYPE = 'Application';
    protected string $MODULE_NAME = '::sylabe:module:manage:ModuleName';
    protected string $MODULE_MENU_NAME = '::sylabe:module:manage:MenuName';
    protected string $MODULE_COMMAND_NAME = 'modmanager';
    protected string $MODULE_DEFAULT_VIEW = 'disp';
    protected string $MODULE_DESCRIPTION = '::sylabe:module:manage:ModuleDescription';
    protected string $MODULE_VERSION = '020240721';
    protected string $MODULE_AUTHOR = 'Projet nebule';
    protected string $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2024';
    protected string $MODULE_LOGO = '8dc6a54b72778131a427e2b36df04d4a3fa036b1275868bd060e9dbf8b7493e4.sha2.256';
    protected string $MODULE_HELP = '::sylabe:module:manage:ModuleHelp';
    protected string $MODULE_INTERFACE = '3.0';

    protected array $MODULE_REGISTERED_VIEWS = array('list', 'disp', 'add', 'cod');
    protected array $MODULE_REGISTERED_ICONS = array(
        '8dc6a54b72778131a427e2b36df04d4a3fa036b1275868bd060e9dbf8b7493e4.sha2.256',    // 0 Module
        '37be5ba2a53e9835dbb0ff67a0ece1cc349c311660e4779680ee2daa4ac45636.sha2.256',    // 1 Ajout d'un module
    );
    protected array $MODULE_APP_TITLE_LIST = array('::sylabe:module:manage:AppTitle1');
    protected array $MODULE_APP_ICON_LIST = array('8dc6a54b72778131a427e2b36df04d4a3fa036b1275868bd060e9dbf8b7493e4.sha2.256');
    protected array $MODULE_APP_DESC_LIST = array('::sylabe:module:manage:AppDesc1');
    protected array $MODULE_APP_VIEW_LIST = array('list');

    // Constantes spécifiques à la création de liens.
    const DEFAULT_COMMAND_ACTION_NOM = 'actaddnam';
    const DEFAULT_COMMAND_ACTION_DESC = 'actadddsc';
    const DEFAULT_COMMAND_ACTION_RID = 'actaddrid';
    const DEFAULT_COMMAND_ACTION_RIDC = 'actchrid';
    const DEFAULT_COMMAND_ACTION_ID = 'actaddid';


    /**
     * Le hash de la référence des modules.
     * @var string
     */
    private $_hashModule = null;


    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getCurrentEntityIsUnlocked();
        $this->_hashModule = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES);
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $listModulesRID = $this->_applicationInstance->getModulesListRID();

        $hookArray = array();
        switch ($hookName) {
            case 'selfMenu':
                // Affichage des applications.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[0]) {
                    $hookArray[0]['name'] = '::sylabe:module:manage:AppTitle1';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '::sylabe:module:manage:AppDesc1';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0];
                }

                // Synchronisation des applications.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[0]
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
                    $hookArray[0]['name'] = '::sylabe:module:manage:syncModules';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '/?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $object
                        . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $this->getExtractCommandDisplayModule()
                        . '&' . Action::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION . '=0'
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }

                // Ajout d'un module.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[0]
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitUploadLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    && $this->_unlocked
                ) {
                    $hookArray[1]['name'] = '::sylabe:module:manage:create:createModule';
                    $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];
                }

                // Synchronisation du module.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[1]
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
                    $hookArray[2]['name'] = '::sylabe:module:manage:syncModule';
                    $hookArray[2]['icon'] = Display::DEFAULT_ICON_SYNOBJ;
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $object
                        . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $this->getExtractCommandDisplayModule()
                        . '&' . Action::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION . '=' . $object
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }

                // Modification du code du module.
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[1]
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && isset($listModulesRID[$this->getExtractCommandDisplayModule()])
                    && $this->_unlocked
                ) {
                    $hookArray[3]['name'] = '::sylabe:module:manage:changeCode';
                    $hookArray[3]['icon'] = Display::DEFAULT_ICON_LU;
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $object
                        . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $this->getExtractCommandDisplayModule();
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
//            case $this->MODULE_REGISTERED_VIEWS[0]:
//                $this->_displayModules();
//                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayModule();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayCreateModule();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
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
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineModules();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
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
    public function action(): void
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

            // Lit et nettoye le contenu de la variable GET.
            $arg_name = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_NOM, FILTER_SANITIZE_STRING));
            $arg_rid = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_RID, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Écriture des variables.
            if ($arg_name != '') {
                $this->_actionAddModuleName = $arg_name;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add module NAME:' . $arg_name, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '8e89341e');
            }
            if (Node::checkNID($arg_rid)) {
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

            // Lit et nettoye le contenu de la variable GET.
            $arg_rid = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_RIDC, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $arg_id = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ID, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            if (Node::checkNID($arg_rid)) {
                $this->_actionAddModuleRID = $arg_rid;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add code module RID:' . $arg_id, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'c7317945');
            }
            if (Node::checkNID($arg_id)
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
            $instance = new Node($this->_nebuleInstance, $this->_actionAddModuleRID, '', false, false);

            // Création du type mime.
            $instance->setType($this->_hashModule);

            // Crée le lien de hash.
            $date = date(DATE_ATOM);
            $signer = $this->_entitiesInstance->getCurrentEntityID();
            $action = 'l';
            $source = $this->_actionAddModuleRID;
            $target = $this->_nebuleInstance->getCryptoInstance()->hash($this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm'));
            $meta = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_HASH);
            $this->_createLink_DEPRECATED($signer, $date, $action, $source, $target, $meta, false);

            // Crée l'objet du nom.
            $instance->setName($this->_actionAddModuleName);

            // Crée le lien de référence.
            $action = 'f';
            $source = $this->_hashModule;
            $target = $this->_actionAddModuleRID;
            $meta = $this->_hashModule;
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
            $signer = $this->_entitiesInstance->getCurrentEntityID();
            $action = 'f';
            $source = $this->_actionAddModuleRID;
            $target = $this->_actionAddModuleID;
            $meta = $this->_hashModule;
            $this->_createLink_DEPRECATED($signer, $date, $action, $source, $target, $meta, false);

            // Flush le cache de l'objet du module pour que les changements soient pris en compte tout de suite.
            $this->_cacheInstance->unsetObjectCache($this->_actionAddModuleRID); // @todo ne marche pas...
        }
    }


    /**
     * Affichage de la liste des modules.
     *
     * @return void
     */
    private function _displayModules(): void
    {
        // Titre.
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[0]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::sylabe:module:manage:Modules', $icon, false);

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
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);

            return;
        }

        $listModules = $this->_applicationInstance->getModulesListInstances();
        $listModulesValid = $this->_applicationInstance->getModulesListValid();
        $listModulesEnabled = $this->_applicationInstance->getModulesListEnabled();
        $listModulesRID = $this->_applicationInstance->getModulesListRID();
        $listModulesSignerRID = $this->_applicationInstance->getModulesListSignersRID();
        $listOkModules = array();


        $this->_applicationInstance->getDisplayInstance()->displayMessageInformation_DEPRECATED('Size ' . sizeof($listModules));

        ?>

        <div class="layoutObjectsList">
            <div class="objectsListContent">
                <?php
                // Affiche les différentes applications.
                foreach ($listModules as $moduleName => $instance) {
                    $name = $instance->getName();
                    if (is_a($instance, '\Nebule\Library\Modules')
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
                            'link2Object' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                                . '&' . References::COMMAND_SELECT_OBJECT . '=' . $rid
                                . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $className,
                            'objectName' => $instance->getTranslateInstance($name, $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage()),
                            'objectRefs' => array(),
                            'objectIcon' => $instance->getLogo(),
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
                                $param['flagStateDesc'] = '::sylabe:module:manage:ModuleValid';
                            } else {
                                $param['flagState'] = 'e';
                                $param['flagStateDesc'] = '::sylabe:module:manage:ModuleInvalid';
                            }
                        }

                        // Module activé.
                        if (isset($listModulesEnabled[$className])
                            && $listModulesEnabled[$className]
                        ) {
                            $param['flagActivated'] = true;
                            $param['flagActivatedDesc'] = '::sylabe:module:manage:ModuleEnabled';
                        } else {
                            $param['flagActivated'] = false;
                            $param['flagActivatedDesc'] = '::sylabe:module:manage:ModuleDisabled';
                        }
                        $instance = $this->_cacheInstance->newNode('0'); // FIXME
                        echo $this->_displayInstance->getDisplayObject_DEPRECATED($instance, $param);

                        // Marque comme vu.
                        $listOkModules[$name] = true;
                    } else
                        $this->_applicationInstance->getDisplayInstance()->displayMessageWarning_DEPRECATED('Try ' . $moduleName);
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
        // Titre.
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[0]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::sylabe:module:manage:Module', $icon, false);

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
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::sylabe:module:manage:display:noModule', $param);

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
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);

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
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);

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
                    'objectName' => $instance->getTraduction($instance->getDescription()),
                    'objectIcon' => $instance->getLogo(),
                    'objectRefs' => $instance->getTraduction($instance->getName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage()),
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
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED($instance->getTraduction($instance->getHelp()), $param);

                // Affiche l'application (RID).
                if ($rid != '0') {
                    $object = $this->_cacheInstance->newNode($rid);
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
                        'objectName' => $instance->getTraduction($instance->getName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage()),
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
                    if ($this->_applicationInstance->isModuleLoaded('ModuleReferences')) // Si le module des références éxiste.
                    {
                        $param['link2Object'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleReferences')->getCommandName()
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleReferences')->getRegisteredViews()[0]
                            . '&' . $this->_applicationInstance->getModule('ModuleReferences')->getCommandName() . '=' . $this->_hashModule
                            . '&' . References::COMMAND_SELECT_OBJECT . '=' . $rid;
                        $param['objectIcon'] = $this->_applicationInstance->getModule('ModuleReferences')->getLogo();
                    }
                    echo $this->_displayInstance->getDisplayObject_DEPRECATED($object, $param);
                } else {
                    $param = array(
                        'enableDisplayIcon' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'long',
                        'informationType' => 'warn',
                    );
                    echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::sylabe:module:manage:display:integratedModule', $param);
                }

                // ID
                if ($id != '0') {
                    $object = $this->_cacheInstance->newNode($id);
                    $param['objectName'] = '';
                    $param['status'] = '';
                    $param['link2Object'] = '';
                    $param['objectRefs'] = array(
                        '0' => $listModulesSignersRID[$id],
                    );
                    $param['objectIcon'] = $this->MODULE_LOGO;
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
                    echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::sylabe:module:manage:ModuleValid', $param);
                } else {
                    $param = array(
                        'enableDisplayIcon' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                        'informationType' => 'error',
                    );
                    echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::sylabe:module:manage:ModuleInvalid', $param);
                }

                // Activation du module.
                if ($this->_unlocked
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && ($this->_entitiesInstance->getCurrentEntityID() == $this->_authoritiesInstance->getCodeMaster()
                        || ($this->_entitiesInstance->getCurrentEntityID() == $this->_entitiesInstance->getDefaultEntityID()
                            && $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority') )
                        || ($this->_entitiesInstance->getCurrentEntityID() == $this->_entitiesInstance->getDefaultEntityID()
                            && $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority'))
                    )
                    && $rid != '0'
                ) {
                    $hashActivation = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MOD_ACTIVE);
                    $dispHook = array(
                        'hookType' => 'Self',
                        'cssid' => '',
                        'moduleName' => $this->_translateInstance->getTranslate($this->MODULE_NAME),
                    );
                    if (isset($listModulesEnabled[$className])
                        && $listModulesEnabled[$className]
                    ) {
                        $dispHook['link'] = '/?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                            . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $className
                            . '&' . Action::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $rid . '_' . $hashActivation . '_' . $rid
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        $dispHook['icon'] = Displays::DEFAULT_ICON_IOK;
                        $dispHook['name'] = $this->_translateInstance->getTranslate('::sylabe:module:manage:ModuleEnabled');
                        $dispHook['desc'] = $this->_translateInstance->getTranslate('::sylabe:module:manage:ModuleDisable');
                    } else {
                        $dispHook['link'] = '/?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                            . '&' . self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE . '=' . $className
                            . '&' . Action::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_' . $rid . '_' . $hashActivation . '_' . $rid
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        $dispHook['icon'] = Displays::DEFAULT_ICON_IERR;
                        $dispHook['name'] = $this->_translateInstance->getTranslate('::sylabe:module:manage:ModuleDisabled');
                        $dispHook['desc'] = $this->_translateInstance->getTranslate('::sylabe:module:manage:ModuleEnable');
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
                        echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::sylabe:module:manage:ModuleEnabled', $param);
                    } else {
                        $param['informationType'] = 'error';
                        echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::sylabe:module:manage:ModuleDisabled', $param);
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
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED('Version : ' . $instance->getVersion(), $param);

                // Interface.
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'informationType' => 'information',
                );
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED('Interface : ' . $instance->getInterface(), $param);

                // Licence.
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'informationType' => 'information',
                );
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED($instance->getDevelopper() . ' <br />' . $instance->getLicence(), $param);

                // Synchronisation de l'application.
                /*		if ( $this->_configuration->getOption('permitWrite')
				&& $this->_configuration->getOption('permitWriteObject')
				&& $this->_configuration->getOption('permitWriteLink')
				&& $this->_configuration->getOption('permitSynchronizeObject')
				&& $this->_configuration->getOption('permitSynchronizeLink')
				&& $this->_configuration->getOption('permitSynchronizeApplication')
				&& ( $this->_configuration->getOption('permitPublicSynchronizeApplication')
						|| $this->_unlocked
					)
				&& $rid != '0'
			)
		{
			$dispHook = array(
					'hookType' => 'Self',
					'cssid' => '',
					'moduleName' => $this->_translateInstance->getTranslate($this->MODULE_NAME),
					'link' => '/?'.Displays::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this->MODULE_COMMAND_NAME
							.'&'.Displays::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this->MODULE_REGISTERED_VIEWS[1]
							.'&'.self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE.'='.$className
							.'&'.Action::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION.'='.$rid
							.$this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(),
					'icon' => Display::DEFAULT_ICON_SYNOBJ,
					'name' => $this->_translateInstance->getTranslate('::sylabe:module:manage:syncModule'),
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
        // Titre.
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[0]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::sylabe:module:manage:create:createModule', $icon, false);

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
                          action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                              . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                              . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                        <?php echo $this->_translateInstance->getTranslate('::sylabe:module:manage:create:nom'); ?><br/>
                        <input type="text"
                               name="<?php echo self::DEFAULT_COMMAND_ACTION_NOM; ?>"
                               size="80" value="<?php echo $this->_translateInstance->getTranslate('::sylabe:module:manage:create:nom'); ?>"/><br/>
                        RID<br/>
                        <input type="text"
                               name="<?php echo self::DEFAULT_COMMAND_ACTION_RID; ?>"
                               size="80" value=""/><br/>
                        <input type="submit"
                               value="<?php echo $this->_translateInstance->getTranslate('::sylabe:module:manage:create:SubmitCreate') ?>"/>
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
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
        }
    }


    /**
     * Affichage de l'ajout d'un code à un module.
     *
     * @return void
     */
    private function _displayChangeCode(): void
    {
        // Titre.
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[0]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::sylabe:module:manage:create:addModuleCode', $icon, false);

        // Extrait le RID si nouveau module créé.
        $arg_rid = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_RID, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if (Node::checkNID($arg_rid)) {
            $rid = $arg_rid;
            $newCode = true;
        } else {
            $rid = $this->_nebuleInstance->getCurrentObject();
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
                $ridInstance = $this->_cacheInstance->newNode($rid);
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
                              action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                  . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                                  . '&' . References::COMMAND_SELECT_OBJECT . '=' . $rid
                                  . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                            <input type="hidden"
                                   name="<?php echo self::DEFAULT_COMMAND_ACTION_RIDC; ?>"
                                   value="<?php echo $rid; ?>"/><br/>
                            ID<br/>
                            <input type="text"
                                   name="<?php echo self::DEFAULT_COMMAND_ACTION_ID; ?>"
                                   size="80" value=""/><br/>
                            <input type="submit"
                                   value="<?php echo $this->_translateInstance->getTranslate('::sylabe:module:manage:create:SubmitChange') ?>"/>
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
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::sylabe:module:manage:display:noModule', $param);
            }
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'short',
            );
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
        }
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::sylabe:module:manage:ModuleName' => 'Module des modules',
            '::sylabe:module:manage:MenuName' => 'Modules',
            '::sylabe:module:manage:ModuleDescription' => 'Module de gestion des modules.',
            '::sylabe:module:manage:ModuleHelp' => 'Ce module permet de voir les modules détectés par une application.',
            '::sylabe:module:manage:AppTitle1' => 'Modules',
            '::sylabe:module:manage:AppDesc1' => 'Module de gestion des modules.',
            '::sylabe:module:manage:Module' => 'Le module',
            '::sylabe:module:manage:Modules' => 'Les modules',
            '::sylabe:module:manage:display:noModule' => 'Pas de module.',
            '::sylabe:module:manage:display:integratedModule' => "Ce module est intégré à l'application, il ne peut pas être modifié.",
            '::sylabe:module:manage:ModuleValid' => 'Module valide.',
            '::sylabe:module:manage:ModuleInvalid' => 'Module invalide !',
            '::sylabe:module:manage:ModuleEnabled' => 'Module activé.',
            '::sylabe:module:manage:ModuleDisabled' => 'Module désactivé !',
            '::sylabe:module:manage:ModuleEnable' => 'Activer ?.',
            '::sylabe:module:manage:ModuleDisable' => 'Désactivé ?',
            '::sylabe:module:manage:display:noCode' => 'Pas de code !',
            '::sylabe:module:manage:syncModule' => 'Synchroniser le module',
            '::sylabe:module:manage:syncModules' => 'Synchroniser les modules',
            '::sylabe:module:manage:changeCode' => 'Mettre à jour le code',
            '::sylabe:module:manage:create:createModule' => 'Créer un module',
            '::sylabe:module:manage:create:addModuleCode' => 'Ajouter un code',
            '::sylabe:module:manage:create:nom' => 'Nom',
            '::sylabe:module:manage:create:Desc' => 'Description',
            '::sylabe:module:manage:create:SubmitCreate' => 'Créer',
            '::sylabe:module:manage:create:SubmitChange' => 'Changer',
        ],
        'en-en' => [
            '::sylabe:module:manage:ModuleName' => 'Module of modules',
            '::sylabe:module:manage:MenuName' => 'Modules',
            '::sylabe:module:manage:ModuleDescription' => 'Module to manage modules.',
            '::sylabe:module:manage:ModuleHelp' => 'This module permit to see modules detected by an application.',
            '::sylabe:module:manage:AppTitle1' => 'Modules',
            '::sylabe:module:manage:AppDesc1' => 'Manage modules.',
            '::sylabe:module:manage:Module' => 'The module',
            '::sylabe:module:manage:Modules' => 'The modules',
            '::sylabe:module:manage:display:noModule' => 'No module.',
            '::sylabe:module:manage:display:integratedModule' => "This module is integrated to the application, it can't be modified.",
            '::sylabe:module:manage:ModuleValid' => 'Valid module',
            '::sylabe:module:manage:ModuleInvalid' => 'Invalid module!',
            '::sylabe:module:manage:ModuleEnabled' => 'Module enabled.',
            '::sylabe:module:manage:ModuleDisabled' => 'Module disabled!',
            '::sylabe:module:manage:ModuleEnable' => 'Enable?',
            '::sylabe:module:manage:ModuleDisable' => 'Disable?',
            '::sylabe:module:manage:display:noCode' => 'No code!',
            '::sylabe:module:manage:syncModule' => 'Synchronize the module',
            '::sylabe:module:manage:syncModules' => 'Synchronize the modules',
            '::sylabe:module:manage:changeCode' => 'Update the code',
            '::sylabe:module:manage:create:createModule' => 'Create a module',
            '::sylabe:module:manage:create:addModuleCode' => 'Add a code',
            '::sylabe:module:manage:create:nom' => 'Name',
            '::sylabe:module:manage:create:Desc' => 'Description',
            '::sylabe:module:manage:create:SubmitCreate' => 'Create',
            '::sylabe:module:manage:create:SubmitChange' => 'Change',
        ],
        'es-co' => [
            '::sylabe:module:manage:ModuleName' => 'Module of modules',
            '::sylabe:module:manage:MenuName' => 'Modules',
            '::sylabe:module:manage:ModuleDescription' => 'Module to manage modules.',
            '::sylabe:module:manage:ModuleHelp' => 'This module permit to see modules detected by an application.',
            '::sylabe:module:manage:AppTitle1' => 'Modules',
            '::sylabe:module:manage:AppDesc1' => 'Manage modules.',
            '::sylabe:module:manage:Module' => 'El modulo',
            '::sylabe:module:manage:Modules' => 'Los modulos',
            '::sylabe:module:manage:display:noModule' => 'No modulo.',
            '::sylabe:module:manage:display:integratedModule' => "This module is integrated to the application, it can't be modified.",
            '::sylabe:module:manage:ModuleValid' => 'Valid module',
            '::sylabe:module:manage:ModuleInvalid' => 'Invalid module!',
            '::sylabe:module:manage:ModuleEnabled' => 'Module enabled.',
            '::sylabe:module:manage:ModuleDisabled' => 'Module disabled!',
            '::sylabe:module:manage:ModuleEnable' => 'Enable?',
            '::sylabe:module:manage:ModuleDisable' => 'Disable?',
            '::sylabe:module:manage:display:noCode' => 'No code!',
            '::sylabe:module:manage:syncModule' => 'Synchronize the module',
            '::sylabe:module:manage:syncModules' => 'Synchronize the modules',
            '::sylabe:module:manage:changeCode' => 'Update the code',
            '::sylabe:module:manage:create:createModule' => 'Create a module',
            '::sylabe:module:manage:create:addModuleCode' => 'Add a code',
            '::sylabe:module:manage:create:nom' => 'Name',
            '::sylabe:module:manage:create:Desc' => 'Description',
            '::sylabe:module:manage:create:SubmitCreate' => 'Create',
            '::sylabe:module:manage:create:SubmitChange' => 'Change',
        ],
    ];
}
