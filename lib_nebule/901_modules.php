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
abstract class Module extends Functions implements ModuleInterface {
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
    const COMMAND_SELECT_ITEM = '';
    const COMMAND_SYNC_ALL = 'synchro_all';

    protected ?Applications $_applicationInstance = null;
    protected ?Displays $_displayInstance = null;
    protected ?Translates $_translateInstance = null;
    protected bool $_unlocked = false;
    protected string $_socialClass = '';
    protected ?\Nebule\Library\Node $_instanceCurrentItem = null;

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
     * @param Node|null $instance
     * @return array
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $instance = null): array { return array(); }
    public function getCommonHookList(
        string $hookName,
        string $nid,
        string $names,
        string $name,
        int    $indexList = 0,
        int    $iconList = 1,
        int    $indexAdd = 2,
        int    $iconAdd = 2,
        int    $indexGet = 5,
        int    $iconGet = 6,
    ): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenu' . $name:
                if ($this->_socialClass != 'myself') {
                    $hookArray[] = array(
                        'name' => '::my' . $names,
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
                        'name' => '::other' . $names,
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
                        'name' => '::all' . $names,
                        'icon' => $this::MODULE_REGISTERED_ICONS[$iconList],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexList]
                            . '&' . Displays::COMMAND_SOCIAL . '=all'
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
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
                        $hookArray[] = array(
                            'name' => '::get' . $name,
                            'icon' => $this::MODULE_REGISTERED_ICONS[$iconGet],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexGet]
                                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                    }
                }
                break;
            case 'typeMenuEntity':
                $hookArray[] = array(
                    'name' => '::my' . $names,
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexList]
                        . '&' . Displays::COMMAND_SOCIAL . '=myself'
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;

            case 'rightsOwner':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'unlocked'))
                    && $this->_instanceCurrentItem->getID() !== null) {
                    $hookArray[] = array(
                        'name' => '::removeAsOwner',
                        'icon' => Displays::DEFAULT_ICON_LX,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . \Nebule\Library\ActionsGroups::REMOVE_MEMBER . '=' . $nid
                            . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . References::RID_OWNER
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
                break;
            case 'rightsWriter':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'unlocked')) && isset($this->_currentItemListOwners[$this->_entitiesInstance->getConnectedEntityEID()])
                    && $this->_instanceCurrentItem->getID() !== null) {
                    $hookArray[] = array(
                        'name' => '::addAsOwner',
                        'icon' => Displays::DEFAULT_ICON_ADDENT,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $nid
                            . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . References::RID_OWNER
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                    $hookArray[] = array(
                        'name' => '::removeAsWriter',
                        'icon' => Displays::DEFAULT_ICON_LX,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . \Nebule\Library\ActionsGroups::REMOVE_MEMBER . '=' . $nid
                            . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . References::RID_WRITER
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
                break;
            case 'rightsFollower':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'unlocked')) && isset($this->_currentItemListOwners[$this->_entitiesInstance->getConnectedEntityEID()])
                    && $this->_instanceCurrentItem->getID() !== null) {
                    $hookArray[] = array(
                        'name' => '::addAsWriter',
                        'icon' => Displays::DEFAULT_ICON_ADDENT,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $nid
                            . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . References::RID_WRITER
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                    $hookArray[] = array(
                        'name' => '::removeAsFollower',
                        'icon' => Displays::DEFAULT_ICON_LX,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . \Nebule\Library\ActionsGroups::REMOVE_MEMBER . '=' . $nid
                            . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . References::RID_FOLLOWER
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
                break;
            case 'rightsAny':
                $this->_metrologyInstance->addLog('DEBUGGING rightsAny MARK1', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'unlocked')) && isset($this->_currentItemListOwners[$this->_entitiesInstance->getConnectedEntityEID()])
                    && $this->_instanceCurrentItem->getID() !== null) {
                    $this->_metrologyInstance->addLog('DEBUGGING rightsAny MARK2', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                    $hookArray[] = array(
                        'name' => '::addAsWriter',
                        'icon' => Displays::DEFAULT_ICON_ADDENT,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $nid
                            . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . References::RID_WRITER
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                    $hookArray[] = array(
                        'name' => '::addAsFollower',
                        'icon' => Displays::DEFAULT_ICON_ADDOBJ,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentItem->getID()
                            . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $nid
                            . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . References::RID_FOLLOWER
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
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

    protected function _displayNotPermit(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage('::err_NotPermit');
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
        $instance->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->display();
    }



    protected function _getCurrentItem(string $command, string $name, ?\Nebule\Library\Node &$instance, string $defaultNID = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->getFilterInput($command, FILTER_FLAG_ENCODE_LOW);
        if ($nid == '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrent' . $name);
        if ($nid == '' && \Nebule\Library\Node::checkNID($defaultNID))
            $nid = $defaultNID;
        if ($nid == '')
            return;
        $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_GROUP);
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrent' . $name, $nid);
        $this->_metrologyInstance->addLog('extract current ' . $name . ' nid=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '565f123c');
    }

    protected function _displayListItems(string $name, string $names, int $indexAdd = 2, int $iconAdd = 2, int $indexGet = 5, int $indexSync = 6, int $indexOption = 8, int $iconItem = 0, int $iconGet = 6): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $this->_displaySimpleTitle('::create' . $name, $this::MODULE_REGISTERED_ICONS[$iconAdd]);
            $this->_displayItemCreateNew($name, $iconItem);
        }
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getSynchro()) {
            $this->_displaySimpleTitle('::get' . $name, $this::MODULE_REGISTERED_ICONS[$iconGet]);
            $this->_displayItemGetNew($name, $iconItem);
        }

        $message = match ($this->_socialClass) {
            'all' => '::all' . $names,
            'notmyself' => '::other' . $names,
            default => '::my' . $names,
        };
        $this->_displaySimpleTitle($message, $this::MODULE_LOGO);

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
            $instance->setMessage('::get' . $name);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexGet]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['synobj']);
            $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
            $instance->setIcon($instanceIcon2);
            $instance->setMessage('::synchroAll');
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexSync]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . $this::COMMAND_SYNC_ALL
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['module']);
            $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
            $instance->setIcon($instanceIcon2);
            $instance->setMessage('::options');
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$indexOption]
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

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('list');
    }

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
                $this->_getListByRight($links, References::RID_OWNER);
                $this->_getListByRight($links, References::RID_WRITER);
                $this->_getListByRight($links, References::RID_FOLLOWER);
                $this->_displayListOfItems($links, 'myself', 'my' . $name);
        }
    }
    protected function _displayListOfItems(array $links, string $socialClass = 'all', string $hookName = ''): void {}

    protected function _getListByRight(array &$links, string $right): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid2' => $this->_entitiesInstance->getGhostEntityEID(),
            'bl/rl/nid3' => $right,
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter, 'all'); // FIXME $socialClass = self?
    }



    protected function _displayItem(string $name, int $icon = 0): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (is_a($this->_instanceCurrentItem, 'Nebule\Library\Group')) {
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial('self');
            $instance->setNID($this->_instanceCurrentItem);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableRefs(false);
            $instance->setEnableNID(false);
            $instance->setEnableFlags(true);
            $instance->setEnableFlagProtection(false);
            $instance->setEnableFlagObfuscate(false);
            $instance->setEnableFlagState(true);
            $instance->setEnableFlagEmotions(true);
            $instance->setEnableStatus(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableLink(true);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            //$instance->setStatus('');
            $instance->setEnableFlagUnlocked(false);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[$icon]);
            //$instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
            $instance->setIcon($instanceIcon);
            $instance->setSelfHookName('self' . $name);
            $instance->setTypeHookName('type' . $name);
            $instance->display();

            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID($name);
        } else
            $this->_displayNotSupported();
    }



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
        } else
            $this->_displayNotPermit();
    }

    protected function _displayItemCreateNew(string $name, int $iconItem = 0): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            if (!$this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateError()) {
                $instance->setMessage('::create' . $name . 'OK');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_OK);
                $instance->setIconText('::OK');
                $instanceList->addItem($instance);

                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('myself');
                //$instance->setNID($this->_displayFolderInstance); FIXME
                $instance->setNID($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateInstance());
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instance->setEnableName(true);
                $instance->setEnableRefs(false);
                $instance->setEnableNID(false);
                $instance->setEnableFlags(false);
                $instance->setEnableFlagProtection(false);
                $instance->setEnableFlagObfuscate(false);
                $instance->setEnableFlagState(false);
                $instance->setEnableFlagEmotions(false);
                $instance->setEnableStatus(false);
                $instance->setEnableContent(false);
                $instance->setEnableJS(false);
                $instance->setEnableLink(true);
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setStatus('');
                $instance->setEnableFlagUnlocked(false);
                $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[$iconItem]);
                $instance->setIcon($instanceIcon);
            } else {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::create' . $name . 'NOK');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setIconText('::ERROR');
            }
            $instanceList->addItem($instance);
            $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
            //$instanceList->setOnePerLine();
            $instanceList->display();
        }
        echo '<br />' . "\n";
    }

    protected function _displayItemGetNew(string $name, int $iconItem = 0): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getSynchro()) {
            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            if (!$this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getSynchroError()) {
                $instance->setMessage('::get' . $name . 'OK');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_OK);
                $instance->setIconText('::OK');
                $instanceList->addItem($instance);

                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('myself');
                //$instance->setNID($this->_displayFolderInstance); FIXME
                $instanceNID = new \Nebule\Library\Group($this->_nebuleInstance, $this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getSynchroNID());
                $instance->setNID($instanceNID);
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instance->setEnableName(true);
                $instance->setEnableRefs(false);
                $instance->setEnableNID(false);
                $instance->setEnableFlags(false);
                $instance->setEnableFlagProtection(false);
                $instance->setEnableFlagObfuscate(false);
                $instance->setEnableFlagState(false);
                $instance->setEnableFlagEmotions(false);
                $instance->setEnableStatus(false);
                $instance->setEnableContent(false);
                $instance->setEnableJS(false);
                $instance->setEnableLink(true);
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setStatus('');
                $instance->setEnableFlagUnlocked(false);
                $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[$iconItem]);
                $instance->setIcon($instanceIcon);
            } else {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::get' . $name . 'NOK');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setIconText('::ERROR');
            }
            $instanceList->addItem($instance);
            $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
            //$instanceList->setOnePerLine();
            $instanceList->display();
        }
        echo '<br />' . "\n";
    }



    protected function _displayModifyItem(string $name, int $icon = 3, int $returnView = 1): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayBackItemOrLogin($name, $returnView);
        if ($this->_unlocked)
            $this->_displayNotImplemented(); // TODO
        else
            $this->_displayNotPermit();
    }



    protected function _displayRemoveItem(string $name, int $iconDelete = 7, int $iconItem = 0, int $returnView = 1): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::remove' . $name, $this::MODULE_REGISTERED_ICONS[$iconDelete]);
        $this->_displayNotImplemented(); // TODO

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_BACK);
        $instance->setMessage('::returnTo' . $name);
        $link = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$returnView]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                if ($this->_instanceCurrentItem !== null)
                        $link .= '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID();
        $instance->setLink($link);
        $instanceList->addItem($instance);
        if (is_a($this->_instanceCurrentItem, 'Nebule\Library\Group')) {
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial('myself');
            $instance->setNID($this->_instanceCurrentItem);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableRefs(false);
            $instance->setEnableNID(false);
            $instance->setEnableFlags(false);
            $instance->setEnableFlagProtection(false);
            $instance->setEnableFlagObfuscate(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableStatus(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableLink(true);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            $instance->setStatus('');
            $instance->setEnableFlagUnlocked(false);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[$iconItem]);
            $instance->setIcon($instanceIcon);
            $instanceList->addItem($instance);
            if ($this->_unlocked) {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
                $instance->setMessage('::remove' . $name);
                $link = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME // FIXME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$returnView]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                if ($this->_instanceCurrentItem !== null)
                        $link .= '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID();
                $instance->setLink($link);
                $instanceList->addItem($instance);
            } else {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
                $instance->setMessage('::login');
                $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                    . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID());
                $instanceList->addItem($instance);
            }
        } else {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setMessage('::notSupported');
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_WARN);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            $instance->setIconText('::ERROR');
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->setOnePerLine();
        $instanceList->display();


        /*$this->_displaySimpleTitle('::remove' . $name, $this::MODULE_REGISTERED_ICONS[$iconDel]);

        if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::sylabe:module:group:remove');
        } else {
            $this->_applicationInstance->getDisplayInstance()->displayMessageError('::thisIsNotGroup');
        }*/
    }



    protected function _displayGetItem(string $name, string $names, string $commandNID, string $commandURL, int $icon = 6, int $returnView = 0): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayBackItems($names, $returnView);
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeItem')) {
            $this->_displaySimpleTitle('::get' . $name, $this::MODULE_REGISTERED_ICONS[$icon]);
            $commonLink = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$returnView]
                . '&' . \Nebule\Library\ActionsGroups::SYNCHRO
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName($commandNID);
            $instance->setIconText('NID');
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setLink($commonLink);
            $instance->setWithSubmit(false);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_WARN_RID);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName($commandURL);
            $instance->setIconText('URL');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_WARN_RID);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_TEXT);
            $instance->setMessage('::get' . $name);
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::getThe' . $name));
            $instance->setIconText('::confirm');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(true);
            $instance->setWithSubmit(true);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_PLAY_RID);
            $instanceList->addItem($instance);

            $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
            $instanceList->setOnePerLine();
            $instanceList->display();

        }
        else
            $this->_displayNotPermit();
    }



    protected function _displaySynchroItem(string $name, string $commandNID, int $icon = 8, int $returnView = 1): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayBackItemOrLogin($name, $returnView);
        if ($this->_unlocked)
            $this->_displayNotImplemented(); // TODO
        else
            $this->_displayNotPermit();
    }



    protected array $_currentItemListFounders = array();
    protected array $_currentItemListOwners = array();
    protected array $_currentItemWritersList = array();
    protected array $_currentItemFollowersList = array();

    protected function _getCurrentItemFounders(?\Nebule\Library\Node $currentItem): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($currentItem == null)
            return;
        $oid = $currentItem->getID();
        if ($oid == '0')
            return;
        $content = $this->_ioInstance->getObject($oid);
        if ($content == '')
            return;
        $eid = '';
        foreach (explode("\n", $content) as $line) {
            $l = trim($line);

            if ($l == '' || str_starts_with($l, '#'))
                continue;

            $nameOnFile = trim((string)filter_var(strtok($l, '='), FILTER_SANITIZE_STRING));
            $value = trim((string)filter_var(strtok('='), FILTER_SANITIZE_STRING));
            if ($nameOnFile == 'eid')
                $eid = $value;
        }
        if (! \Nebule\Library\Node::checkNID($eid, false, false))
            return;
        $this->_metrologyInstance->addLog('extract current blog owner eid=' . $eid, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '0cdd6bb5');
        $this->_currentItemListOwners = array($eid => $eid);
        $this->_currentItemListFounders = array($eid => $eid);
    }

    protected function _getCurrentItemSocialList(?\Nebule\Library\Node $currentItem): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($currentItem == null)
            return;
        if (sizeof($this->_currentItemListFounders) == 0)
            return;

        $instance = new \Nebule\Library\Group($this->_nebuleInstance, $currentItem->getID());
        //if (!$instance->getMarkClosedGroup())
        //    return;
        $this->_currentItemListOwners = $instance->getListTypedMembersID(References::RID_OWNER, 'onlist', $this->_currentItemListFounders);
        foreach ($this->_currentItemListFounders as $eid)
            $this->_currentItemListOwners[$eid] = $eid;
        foreach ($this->_currentItemListOwners as $eid)
            $this->_metrologyInstance->addLog('DEBUGGING blog owner eid=' . $eid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $this->_currentItemWritersList = $instance->getListTypedMembersID(References::RID_WRITER, 'onlist', $this->_currentItemListOwners);
        foreach ($this->_currentItemWritersList as $eid)
            $this->_metrologyInstance->addLog('DEBUGGING blog writer eid=' . $eid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $this->_currentItemFollowersList = $instance->getListTypedMembersID(References::RID_FOLLOWER, 'onlist', $this->_currentItemListOwners);
        foreach ($this->_currentItemFollowersList as $eid)
            $this->_metrologyInstance->addLog('DEBUGGING blog follower eid=' . $eid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
    }

    protected function _displayRightsItem(string $name, int $icon = 3, int $returnView = 1): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::rights', $this::MODULE_REGISTERED_ICONS[$icon]);
        $this->_displayBackItemOrLogin($name, $returnView);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('rights');
    }

    protected function _display_InlineRightsItem(string $name): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
        foreach ($this->_entitiesInstance->getListEntitiesInstances() as $entityInstance) {
            $eid = $entityInstance->getID();
            if (($this->_entitiesInstance->getConnectedEntityIsUnlocked() && isset($this->_currentItemListOwners[$this->_entitiesInstance->getConnectedEntityEID()]))
                || isset($this->_currentItemListOwners[$eid])
                || isset($this->_currentItemWritersList[$eid])
                || isset($this->_currentItemFollowersList[$eid])
            ) {
                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setNID($entityInstance);
                $instance->setIcon($instanceIcon);
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instance->setEnableName(true);
                $instance->setEnableFlags(false);
                $instance->setEnableFlagState(false);
                $instance->setEnableFlagEmotions(false);
                $instance->setEnableStatus(true);
                $instance->setEnableContent(false);
                $instance->setEnableJS(false);
                if (isset($this->_currentItemListFounders[$eid])) {
                    $instance->setSelfHookName('rightsFounder');
                    $instance->setStatus('::founder');
                }
                elseif (isset($this->_currentItemListOwners[$eid])) {
                    $instance->setSelfHookName('rightsOwner');
                    $instance->setStatus('::owner');
                }
                elseif (isset($this->_currentItemWritersList[$eid])) {
                    $instance->setSelfHookName('rightsWriter');
                    $instance->setStatus('::writer');
                }
                elseif (isset($this->_currentItemFollowersList[$eid])) {
                    $instance->setSelfHookName('rightsFollower');
                    $instance->setStatus('::follower');
                }
                else
                    $instance->setSelfHookName('rightsAny');
                $instanceList->addItem($instance);
            }
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }

    protected function _displayBackItemOrLogin(string $name, int $returnView = 1, string $addURL = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_BACK);
        $instance->setMessage('::returnTo' . $name);
        $link = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$returnView]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . $addURL;
        if ($this->_instanceCurrentItem !== null)
            $link .= '&' . $this::COMMAND_SELECT_ITEM . '=' . $this->_instanceCurrentItem->getID();
        $instance->setLink($link);
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
    }

    protected function _displayBackItems(string $name, int $returnView = 0, string $addURL = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_BACK);
        $instance->setMessage('::my' . $name);
        $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[$returnView]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . $addURL);
        $instanceList->addItem($instance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();
    }

    protected function _getDefaultItem(string $rid): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (! is_a($this->_entitiesInstance->getGhostEntityInstance(), '\Nebule\Library\Node'))
            return '';
        return $this->_entitiesInstance->getGhostEntityInstance()->getPropertyID($rid, 'self');
    }

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
