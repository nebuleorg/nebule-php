<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe Modules
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Modules extends Functions implements ModuleInterface {
    const MODULE_TYPE = 'Model'; // Model | Application | Traduction
    const MODULE_NAME = 'None';
    const MODULE_MENU_NAME = 'None';
    const MODULE_COMMAND_NAME = 'none';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = 'Description';
    const MODULE_VERSION = '020260101';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2026';
    const MODULE_LOGO = '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c.sha2.256';
    const MODULE_HELP = 'Help';
    const MODULE_INTERFACE = '3.0';
    const MODULE_REGISTERED_VIEWS = array('disp');
    const MODULE_REGISTERED_ICONS = array();
    const MODULE_APP_TITLE_LIST = array();
    const MODULE_APP_ICON_LIST = array();
    const MODULE_APP_DESC_LIST = array();
    const MODULE_APP_VIEW_LIST = array();

    const DEFAULT_COMMAND_ACTION_DISPLAY_MODULE = 'name';

    const RESTRICTED_TYPE = '';
    const RESTRICTED_CONTEXT = '';

    protected ?Applications $_applicationInstance = null;
    protected ?Displays $_displayInstance = null;
    protected ?Translates $_translateInstance = null;
    protected bool $_unlocked = false;
    protected string $_socialClass = '';

    public function __toString(): string { return $this::MODULE_NAME; }



    protected function _initialisation(): void {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_socialClass = $this->getFilterInput(Displays::COMMAND_SOCIAL, FILTER_FLAG_ENCODE_LOW);
    }



    public function getClassName(): string { return static::class; }

    /**
     * Add functionalities by hooks on menu (by module) and nodes (by type).
     *  - On menu:
     *    - selfMenu: inside menu on current module, in white;
     *    - selfMenu<View>: inside menu on current module only on 'View', in white;
     *    - <Module>SelfMenu: inside menu on another module with the name 'Module', in white;
     *    - menu: inside menu on every module, on the end of list, in dark;
     *  - On node:
     *    - typeMenu<Type>: inside menu on node with the 'Type', in white;
     *    - ... FIXME
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array { return array(); }
    public function getCommonHookList(
        string $hookName,
        string $nid,
        string $name,
        int    $indexList = 0,
        int    $iconList = 0,
        int    $indexAdd = 2,
        int    $iconAdd = 1,
    ): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenu' . $name:
                if ($this->_socialClass != 'myself') {
                    $hookArray[] = array(
                        'name' => '::my' . $name,
                        'icon' => $this::MODULE_REGISTERED_ICONS[$iconList],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexList]
                            . '&' . Displays::COMMAND_SOCIAL . '=myself'
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_socialClass != 'notmyself') {
                    $hookArray[] = array(
                        'name' => '::other' . $name,
                        'icon' => $this::MODULE_REGISTERED_ICONS[$iconList],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexList]
                            . '&' . Displays::COMMAND_SOCIAL . '=notmyself'
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_socialClass != 'all') {
                    $hookArray[] = array(
                        'name' => '::all' . $name,
                        'icon' => $this::MODULE_REGISTERED_ICONS[$iconList],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexList]
                            . '&' . Displays::COMMAND_SOCIAL . '=all'
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_unlocked) {
                    if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[$indexList]) {
                        $hookArray[] = array(
                            'name' => '::create' . $name,
                            'icon' => $this::MODULE_REGISTERED_ICONS[$iconAdd],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexAdd]
                                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                    }
                }
                break;
            case 'typeMenuEntity':
                $hookArray[] = array(
                    'name' => '::my' . $name,
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexList]
                        . '&' . Displays::COMMAND_SOCIAL . '=myself'
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;
        }
        return $hookArray;
    }

    public function getHookFunction(string $hookName, string $item): ?\Nebule\Library\DisplayItemIconMessageSizeable { return null; }

    /**
     * Part from this module to display on browser.
     *
     * @return void
     */
    public function displayModule(): void {}

    /**
     * Inline part from this module to display on browser, called by primary page on displayModule().
     *
     * @return void
     */
    public function displayModuleInline(): void {}

    private ?string $_commandActionDisplayModuleCache = null;

    public function getExtractCommandDisplayModule(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $return = '';

        if ($this->_commandActionDisplayModuleCache != null)
            return $this->_commandActionDisplayModuleCache;

        if ($this->_displayInstance->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]) {
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE, FILTER_SANITIZE_STRING));

            if ($arg != '')
                $return = $arg;

            unset($arg);
        }

        $this->_commandActionDisplayModuleCache = $return;

        return $return;
    }

    public function getCSS(): void {
        echo '<style type="text/css">' . "\n";
        $this->headerStyle();
        echo '</style>' . "\n";
    }

    /**
     * Part of CSS from this module to display on browser.
     *
     * @return void
     */
    public function headerStyle(): void {}

    /**
     * Part of JS script from this module to display on browser.
     *
     * @return void
     */
    public function headerScript(): void {}

    /**
     * Part of the actions from this module to run before display.
     *
     * @return void
     */
    public function actions(): void {}

    public function getTranslate(string $text, string $lang = ''): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $result = $text;
        if ($this->_translateInstance === null)
            $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();

        if ($lang == '')
            $lang = $this->_translateInstance->getCurrentLanguage();

        if (isset($this::TRANSLATE_TABLE[$lang][$text]))
            $result = $this::TRANSLATE_TABLE[$lang][$text];
        return $result;
    }


    /**
     * Créer un lien.
     *
     * @param string  $signer
     * @param string  $date
     * @param string  $action
     * @param string  $source
     * @param string  $target
     * @param string  $meta
     * @param boolean $obfuscate
     * @return void
     */
    protected function _createLink_DEPRECATED(string $signer, string $date, string $action, string $source, string $target, string $meta, bool $obfuscate = false): void {
        /*$link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign($signer);
        if ($obfuscate)
            $link->obfuscate();
        $newLink->write();*/
    }

    protected function _displaySimpleTitle(string $title, string $icon = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle($title);
        if ($icon != '')
            $instance->setIconRID($icon);
        $instance->display();
    }

    protected function _displayNotImplemented(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage('::notImplemented');
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_WARN);
        $instance->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->display();
    }

    protected function _displayNotSupported(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage('::notSupported');
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_WARN);
        $instance->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->display();
    }

    protected function _displayListItems(string $name, int $indexAdd = 2, int $iconAdd = 1, int $indexGet = 5, int $iconGet = 6): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $this->_displaySimpleTitle('::create' . $name, $this::MODULE_REGISTERED_ICONS[$iconAdd]);
            $this->_displayItemCreateNew();
        }

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[$iconAdd]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::create' . $name);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexAdd]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['ll']);
            $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
            $instance->setIcon($instanceIcon2);
            $instance->setMessage('::getExisting' . $name);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexGet]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
        } else {
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
        }
        $instanceList->addItem($instance);

        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['synobj']);
        $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
        $instance->setIcon($instanceIcon2);
        $instance->setMessage('::refreshList');
        $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
            . '&' . Displays::COMMAND_SOCIAL . $this->_socialClass
            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
        $instanceList->addItem($instance);

        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();

        $message = match ($this->_socialClass) {
            'all' => '::all' . $name . 's',
            'notmyself' => '::other' . $name . 's',
            default => '::my' . $name . 's',
        };
        $this->_displaySimpleTitle($message, $this::MODULE_LOGO);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('list');
    }
    protected function _displayItemCreateNew(): void {}

    protected function _display_InlineMyItems(string $name): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($this->_socialClass) {
            case 'all':
                $links = $this->_nebuleInstance->getListLinksByType(References::REFERENCE_NEBULE_OBJET_GROUPE, $this::RESTRICTED_CONTEXT, 'all');
                $this->_displayListOfItems($links, 'all', 'all' . $name);
                break;
            case 'notmyself':
                $links = $this->_nebuleInstance->getListLinksByType(References::REFERENCE_NEBULE_OBJET_GROUPE, $this::RESTRICTED_CONTEXT, 'notmyself');
                $this->_displayListOfItems($links, 'notmyself', 'other' . $name);
                break;
            default:
                $links = $this->_nebuleInstance->getListLinksByType(References::REFERENCE_NEBULE_OBJET_GROUPE, $this::RESTRICTED_CONTEXT, 'myself');
                $this->_displayListOfItems($links, 'myself', 'my' . $name);
        }
    }
    protected function _displayListOfItems(array $links, string $socialClass = 'all', string $hookName = ''): void {}

    // Copy of ModuleGroups::_displayGroupCreateForm()
    protected function _displayItemCreateForm(
        string $name,
        int    $returnView = 0,
        int    $iconAdd = 1,
        bool   $withContent = true
    ): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::create' . $name, $this::MODULE_REGISTERED_ICONS[$iconAdd]);
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $commonLink = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$returnView]
                . '&' . \Nebule\Library\ActionsGroups::CREATE
                . '&' . \Nebule\Library\ActionsGroups::CREATE_CONTEXT . '=' . $this::RESTRICTED_CONTEXT
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
            if ($withContent)
                $commonLink .= '&' . \Nebule\Library\ActionsGroups::CREATE_WITH_CONTENT;

            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsGroups::CREATE_NAME);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_NOM);
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setLink($commonLink);
            $instance->setWithSubmit(false);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_WARN_RID);
            $instanceList->addItem($instance);

            if ($this::RESTRICTED_CONTEXT != '') {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::limitedType', $this->_translateInstance->getTranslate('::' . $this::RESTRICTED_TYPE));
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_INFORMATION);
                $instanceList->addItem($instance);
            } /*else {
                $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
                $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
                $instance->setInputName(\Nebule\Library\ActionsGroups::CREATE_CONTEXT);
                $instance->setIconText('::membersType');
                $instance->setSelectList(array(
                    ModuleGroups::RESTRICTED_CONTEXT => $this->_translateInstance->getTranslate('::' . ModuleGroups::RESTRICTED_TYPE),
                    ModuleGroupEntities::RESTRICTED_CONTEXT => $this->_translateInstance->getTranslate('::' . ModuleGroupEntities::RESTRICTED_TYPE),
                ));
                $instance->setWithFormOpen(false);
                $instance->setWithFormClose(false);
                $instance->setWithSubmit(false);
                $instanceList->addItem($instance);
            }*/

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsGroups::CREATE_CLOSED);
            $instance->setIconText('::createClosed' . $name);
            $instance->setSelectList(array(
                'y' => $this->_translateInstance->getTranslate('::yes'),
                'n' => $this->_translateInstance->getTranslate('::no'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsGroups::CREATE_OBFUSCATED);
            $instance->setIconText('::createObfuscated' . $name);
            $instance->setSelectList(array(
                'n' => $this->_translateInstance->getTranslate('::no'),
                'y' => $this->_translateInstance->getTranslate('::yes'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_TEXT);
            $instance->setMessage('::createThe' . $name);
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::createThe' . $name));
            $instance->setIconText('::confirm');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(true);
            $instance->setWithSubmit(true);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_PLAY_RID);
            $instanceList->addItem($instance);

            $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
            $instanceList->setOnePerLine();
            $instanceList->display();
        } else {
            $instance = new \Nebule\Library\DisplayNotify($this->_applicationInstance);
            $instance->setMessage('::err_NotPermit');
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
            $instance->display();
        }
    }

    /*protected function _displayBackOrLogin(string $backMessage, string $backView, string $addURL = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_BACK);
        $instance->setMessage($backMessage);
        $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $backView
            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
            . $addURL);
        $instanceList->addItem($instance);
        if (!$this->_unlocked) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID());
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();
    }*/

    const TRANSLATE_TABLE = [
            'en-en' => [
                    '::nebule:modules::ModuleName' => 'Module of modules',
                    '::nebule:modules::MenuName' => 'Modules',
                    '::nebule:modules::ModuleDescription' => 'Module to manage modules.',
                    '::nebule:modules::ModuleHelp' => 'This application permit to see modules detected by sylabe.',
                    '::nebule:modules::AppTitle1' => 'Modules',
                    '::nebule:modules::AppDesc1' => 'Manage modules.',
            ],
    ];
}
