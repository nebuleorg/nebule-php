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
 * This module can manage objects. Objects can be attached to groups (or objects) in a related folder.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleFolders extends Module {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'fld';
    const MODULE_DEFAULT_VIEW = 'roots';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260112';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2025-2026';
    const MODULE_LOGO = '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'roots',
        'root',
        'new_root',
        'mod_root',
        'del_root',
        'get_root',
        'syn_root',
        'rights_root',
        'options',
        'add_folder',
        'add_file',
        'upload_file',
    );
    const MODULE_REGISTERED_ICONS = array(
        Displays::DEFAULT_ICON_LO,
        Displays::DEFAULT_ICON_LSTOBJ,
        Displays::DEFAULT_ICON_ADDOBJ,
        Displays::DEFAULT_ICON_IMODIFY,
        Displays::DEFAULT_ICON_LD,
        Displays::DEFAULT_ICON_HELP,
        Displays::DEFAULT_ICON_LL,
        Displays::DEFAULT_ICON_LX,
        Displays::DEFAULT_ICON_SYNOBJ,
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    const RESTRICTED_TYPE = 'Folder';
    const RESTRICTED_CONTEXT = '2afeddf82c8f4171fc67b9073ba5be456abb2f4da7720bb6f0c903fb0b0a4231f7e3.none.272';
    const FOLDER_CONTEXT = '967a0920b4091eda6c91a5234d0dc99a24b70c06740e24c8762995c6879a93b78c44.none.272';
    const COMMAND_SELECT_ROOT = 'root';
    const COMMAND_SELECT_ITEM = 'root';
    const COMMAND_SELECT_FOLDER = 'folder';
    const COMMAND_ACTION_GET_FLD_NID = 'actiongetnid';
    const COMMAND_ACTION_GET_FLD_URL = 'actiongeturl';

    protected ?\Nebule\Library\Group $_instanceCurrentRoot = null;
    protected ?\Nebule\Library\Group $_instanceCurrentFolder = null;
    protected array $_path = array();
    protected array $_listSigners = array();
    protected array $_listTypes = array();



    protected function _initialisation(): void {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_socialClass = $this->getFilterInput(Displays::COMMAND_SOCIAL, FILTER_FLAG_ENCODE_LOW);
        $this->_getCurrentItem(self::COMMAND_SELECT_ROOT, 'Root', $this->_instanceCurrentRoot);
        if (! is_a($this->_instanceCurrentRoot, 'Nebule\Library\Group') || $this->_instanceCurrentRoot->getID() == '0')
            $this->_instanceCurrentRoot = null;
        if (is_a($this->_instanceCurrentRoot, 'Nebule\Library\Group')) {
            $this->_getCurrentItem(self::COMMAND_SELECT_FOLDER, 'Folder', $this->_instanceCurrentFolder, $this->_instanceCurrentRoot->getID());
            if (!is_a($this->_instanceCurrentFolder, 'Nebule\Library\Group') || $this->_instanceCurrentFolder->getID() == '0')
                $this->_instanceCurrentFolder = $this->_instanceCurrentRoot;
        }
        $this->_getCurrentItemFounders($this->_instanceCurrentRoot);
        $this->_getCurrentItemSocialList($this->_instanceCurrentRoot);
        $this->_instanceCurrentItem = $this->_instanceCurrentFolder;
        $this->_setPath();
    }

    protected function _setPath(): void {
        if ($this->_instanceCurrentRoot === null)
            return;
        $cache = $this->_sessionInstance->getSessionStore('module:folders:path');
        if (is_array($cache))
            $this->_path = $cache;
        else
            $this->_path = array(0 => $this->_instanceCurrentRoot->getID());
        // TODO add when change root folder
        if ($this->_instanceCurrentFolder->getID() != $this->_instanceCurrentRoot->getID()) {
            $inPath = false;
            foreach ($this->_path as $i => $nid) {
                if ($nid == $this->_instanceCurrentFolder->getID()) {
                    $inPath = true;
                    continue;
                }
                if ($inPath)
                    unset($this->_path[$i]);
            }
            if (!$inPath)
                $this->_path[] = $this->_instanceCurrentFolder->getID();
        }
        $this->_sessionInstance->setSessionStore('module:folders:path', $this->_path);
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $instance = null):array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->_applicationInstance->getCurrentObjectID();
        if ($instance !== null)
            $nid = $instance->getID();
        $hookArray = $this->getCommonHookList($hookName, $nid, 'RootFolders', 'RootFolder');

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuFolders':
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[1]
                    && $this->_instanceCurrentRoot !== null) {
                    $hookArray[] = array(
                        'name' => '::rights',
                        'icon' => Displays::DEFAULT_ICON_IMODIFY,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                            . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                    if ($this->_unlocked && isset($this->_currentItemListOwners[$this->_entitiesInstance->getConnectedEntityEID()])) {
                        $hookArray[] = array(
                            'name' => '::modifyRootFolder',
                            'icon' => Displays::DEFAULT_ICON_IMODIFY,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                                . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                    }
                }
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[8]
                    && $this->_unlocked && isset($this->_currentItemWritersList[$this->_entitiesInstance->getConnectedEntityEID()])
                    && $this->_instanceCurrentRoot !== null) {
                    $hookArray[] = array(
                        'name' => '::removeRootFolder',
                        'icon' => Displays::DEFAULT_ICON_LX,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                            . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                break;

            case 'typeMenuEntity':
                $hookArray[] = array(
                    'name' => '::myRootFolders',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . Displays::COMMAND_SOCIAL . '=myself'
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;

            case 'typeRootFolders':
            case 'typeRootFolder':
                break;

            case 'selfFolder':
                if ($this->_instanceCurrentRoot !== null) {
                    if ($this->_unlocked && isset($this->_currentItemWritersList[$this->_entitiesInstance->getConnectedEntityEID()])) {
                        $hookArray[] = array(
                            'name' => '::addFolder',
                            'icon' => $this::MODULE_REGISTERED_ICONS[2],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
                                . '&' . $this::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                                . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                        $hookArray[] = array(
                            'name' => '::addFile',
                            'icon' => $this::MODULE_REGISTERED_ICONS[2],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                                . '&' . $this::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                                . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                        $hookArray[] = array(
                            'name' => '::addObject',
                            'icon' => $this::MODULE_REGISTERED_ICONS[2],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[10]
                                . '&' . $this::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                                . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                    }
                    $hookArray[] = array(
                        'name' => '::refreshList',
                        'icon' => References::REF_IMG['synobj'],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . $this::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                            . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                break;

            case 'typeFolder':
                $rootID = $nid;
                if ($this->_instanceCurrentRoot !== null)
                    $rootID = $this->_instanceCurrentRoot->getID();
                $hookArray[] = array(
                    'name' => '::seeTheFolder',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . $this::COMMAND_SELECT_ROOT . '=' . $rootID
                        . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                if ($this->_instanceCurrentFolder != null) {
                    $hookArray[] = array(
                        'name' => '::removeFolder',
                        'icon' => $this::MODULE_REGISTERED_ICONS[4],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . $this::COMMAND_SELECT_ROOT . '=' . $rootID
                            . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $this->_instanceCurrentFolder->getID()
                            . '&' . \Nebule\Library\ActionsGroups::REMOVE_MEMBER . '=' . $nid
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(),
                    );
                }
                break;

            case 'typeFile':
                $rootID = $nid;
                if ($this->_instanceCurrentRoot !== null)
                    $rootID = $this->_instanceCurrentRoot->getID();
                if ($this->_instanceCurrentFolder != null) {
                    $hookArray[] = array(
                        'name' => '::removeFile',
                        'icon' => $this::MODULE_REGISTERED_ICONS[4],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . $this::COMMAND_SELECT_ROOT . '=' . $rootID
                            . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $this->_instanceCurrentFolder->getID()
                            . '&' . \Nebule\Library\ActionsGroups::REMOVE_MEMBER . '=' . $nid
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(),
                    );
                }
                break;
        }
        return $hookArray;
    }



    public function getHookFunction(string $hookName, string $item): ?\Nebule\Library\DisplayItemIconMessageSizeable {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($hookName) {
            case 'displayFolderMembers':
                if (!\Nebule\Library\Node::checkNID($item))
                    return null;
                if ($this->_listTypes[$item] == 'folder') {
                    $instanceIcon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[1]);
                    $node = $this->_cacheInstance->newNodeByType($item);
                    $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                    $instance->setSocial('myself');
                    $instance->setNID($node);
                    $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                        . '&' . self::COMMAND_SELECT_FOLDER . '=' . $item
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
                    $instance->setEnableColor(true);
                    $instance->setEnableIcon(true);
                    $instance->setEnableName(true);
                    $instance->setEnableFlags(false);
                    $instance->setEnableContent(false);
                    $instance->setEnableJS(true);
                    if (sizeof($this->_listSigners[$item]) != 0) {
                        $instance->setEnableRefs(true);
                        $instance->setRefs($this->_listSigners[$item]);
                    } else
                        $instance->setEnableRefs(false);
                    $instance->setSelfHookName('typeFolder');
                    $instance->setIcon($instanceIcon);
                    return $instance;
                } elseif ($this->_listTypes[$item] == 'object') {
                    $instanceIcon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[0]);
                    $node = $this->_cacheInstance->newNodeByType($item);
                    $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                    $instance->setSocial('myself');
                    $instance->setNID($node);
                    $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . ModuleObjects::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $item
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
                    $instance->setEnableColor(true);
                    $instance->setEnableIcon(true);
                    $instance->setEnableName(true);
                    $instance->setEnableFlags(false);
                    $instance->setEnableContent(false);
                    $instance->setEnableJS(true);
                    if (sizeof($this->_listSigners[$item]) != 0) {
                        $instance->setEnableRefs(true);
                        $instance->setRefs($this->_listSigners[$item]);
                    } else
                        $instance->setEnableRefs(false);
                    $instance->setSelfHookName('typeFile');
                    $instance->setIcon($instanceIcon);
                    return $instance;
                } else
                    return null;
                break;
            case 'displayRootFolders':
                if (!\Nebule\Library\Node::checkNID($item))
                    return null;
                $instanceIcon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[1]);
                $node = $this->_cacheInstance->newNodeByType($item);
                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('myself');
                $instance->setNID($node);
                $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . self::COMMAND_SELECT_ROOT . '=' . $item
                    . '&' . self::COMMAND_SELECT_FOLDER . '=' . $item
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instance->setEnableName(true);
                $instance->setEnableFlags(false);
                $instance->setEnableContent(false);
                $instance->setEnableJS(true);
                if (sizeof($this->_listSigners[$item]) != 0) {
                    $instance->setEnableRefs(true);
                    $instance->setRefs($this->_listSigners[$item]);
                } else
                    $instance->setEnableRefs(false);
                $instance->setSelfHookName('typeFolder');
                $instance->setIcon($instanceIcon);
                return $instance;
                break;
            default:
                return null;
        }
    }



    public function displayModule(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayItem('Folder');
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayItemCreateForm('RootFolder');
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModifyItem('RootFolder');
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayRemoveItem('RootFolder');
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGetItem('RootFolder', 'RootFolders', $this::COMMAND_ACTION_GET_FLD_NID, $this::COMMAND_ACTION_GET_FLD_URL);
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displaySynchroItem('RootFolder', $this::COMMAND_ACTION_GET_FLD_NID);
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_displayRightsItem('RootFolder');
                break;
            case $this::MODULE_REGISTERED_VIEWS[8]:
                $this->_displayOptions();
                break;
            case $this::MODULE_REGISTERED_VIEWS[9]:
                $this->_displayAddFolder();
                break;
            case $this::MODULE_REGISTERED_VIEWS[10]:
                $this->_displayAddFile();
                break;
            case $this::MODULE_REGISTERED_VIEWS[11]:
                $this->_displayUploadFile();
                break;
            default:
                $this->_displayListItems('RootFolder', 'RootFolders');
                break;
        }
    }

    public function displayModuleInline(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyItems('RootFolders');
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineFolder();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlineRightsItem('RootFolder');
                break;
        }
    }



    protected function _preDisplayItem(): void { $this->_displayPath(); }
    protected function _displayPath(): void {
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($this->_path as $nid) {
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instancePath = $this->_cacheInstance->newNodeByType($nid);
            $instance->setNID($instancePath);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                . '&' . self::COMMAND_SELECT_FOLDER . '=' . $nid
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableNID(false);
            $instance->setEnableFlags(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(false);
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_TINY);
        $instanceList->setPageColumns(6);
        $instanceList->display();
    }

    protected function _display_InlineFolder(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!is_a($this->_instanceCurrentItem, 'Nebule\Library\Group')) {
            $this->_displayNotSupported();
            return;
        }

        $socialClass = 'onlist';
        $this->_socialInstance->setList($this->_currentItemWritersList, $socialClass);
        if (! $this->_instanceCurrentItem->getMarkClosed())
            $socialClass = 'all';
        $memberLinks = $this->_instanceCurrentItem->getListMembersLinks($socialClass, $this->_currentItemWritersList);

        $list = array();
        $this->_listSigners = array();
        foreach ($memberLinks as $link) {
            $nid = $link->getParsed()['bl/rl/nid2'];
            $instance = $this->_cacheInstance->newNode($nid);
            $list[$nid] = $nid;
            $this->_listSigners[$nid] = $link->getSignersEID();
            $this->_socialInstance->setList($this->_currentItemWritersList, $socialClass);
            if ($instance->getIsGroup($socialClass, $this::FOLDER_CONTEXT))
                $this->_listTypes[$nid] = 'folder';
            else
                $this->_listTypes[$nid] = 'object';
        }

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setListHookName('displayFolderMembers');
        $instanceList->setListSize(12);
        $instanceList->setListItems($list);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }



    protected function _displayOptions(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented();
    }

    protected function _displayAddFolder(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::createFolder', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackItemOrLogin('Folder');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $link = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                . '&' . self::COMMAND_SELECT_FOLDER . '=' . $this->_instanceCurrentFolder->getID()
                . '&' . \Nebule\Library\ActionsGroups::CREATE_MEMBER
                . '&' . \Nebule\Library\ActionsGroups::CREATE_MEMBER_IS_GROUP
                . '&' . \Nebule\Library\ActionsGroups::CREATE_MEMBER_CONTEXT . '=' . $this::FOLDER_CONTEXT
                . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentFolder->getID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsGroups::CREATE_MEMBER_NAME);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_NOM);
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setLink($link);
            $instance->setWithSubmit(false);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_WARN_RID);
            $instanceList->addItem($instance);

            if ($this::RESTRICTED_CONTEXT != '') {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::limitedType', $this->_translateInstance->getTranslate('::' . $this::RESTRICTED_TYPE));
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_INFORMATION);
                $instanceList->addItem($instance);
            }

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsGroups::CREATE_MEMBER_OBFUSCATED);
            $instance->setIconText('::createObfuscatedFolder');
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
            $instance->setMessage('::createTheFolder');
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::createTheFolder'));
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

    protected function _displayAddFile(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::addObject', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackItemOrLogin('Folder');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $link = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                . '&' . self::COMMAND_SELECT_FOLDER . '=' . $this->_instanceCurrentFolder->getID()
                . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentFolder->getID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsGroups::ADD_MEMBER);
            $instance->setIconText('::nid');
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setLink($link);
            $instance->setWithSubmit(false);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_WARN_RID);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsGroups::ADD_MEMBER_OBFUSCATED);
            $instance->setIconText('::createObfuscatedNode');
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
            $instance->setMessage('::addObject');
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::addObject'));
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

    protected function _displayUploadFile(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::addFile', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackItemOrLogin('Folder');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $link = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                    . '&' . self::COMMAND_SELECT_FOLDER . '=' . $this->_instanceCurrentFolder->getID()
                    . '&' . \Nebule\Library\ActionsObjects::UPLOAD_FILE
                    . '&' . \Nebule\Library\ActionsObjects::UPLOAD_FILE_GROUP . '=' . $this->_instanceCurrentFolder->getID()
                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentFolder->getID()
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_FILE);
            $instance->setInputName(\Nebule\Library\ActionsObjects::UPLOAD_FILE);
            $instance->setIconText('::addFile');
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setLink($link);
            $instance->setWithSubmit(false);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_WARN_RID);
            $instance->setHiddenInput1('', $this->_configurationInstance->getOptionAsString('ioReadMaxData'));
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsObjects::UPLOAD_FILE_PROTECT);
            $instance->setIconText('::createProtectedNode');
            $instance->setSelectList(array(
                    'n' => $this->_translateInstance->getTranslate('::no'),
                    'y' => $this->_translateInstance->getTranslate('::yes'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsObjects::UPLOAD_FILE_OBFUSCATED);
            $instance->setIconText('::createObfuscatedNode');
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
            $instance->setMessage('::addFile');
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::addFile'));
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



    // Called by Modules::_display_InlineMyItems()
    protected function _displayListOfItems(array $links, string $socialClass = 'all', string $hookName = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $foldersNID = array();
        $this->_listSigners = array();
        foreach ($links as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            if (!$this->_filterItemByType($nid))
                continue;
            $signers = $link->getSignersEID(); // FIXME get all signers
            $foldersNID[$nid] = $nid;
            foreach ($signers as $signer) {
                $this->_listSigners[$nid][$signer] = $signer;
            }
        }

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setListHookName('displayRootFolders');
        $instanceList->setListSize(12);
        $instanceList->setListItems($foldersNID);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }

    protected function _filterItemByType(string $nid): bool { return true; } // FIXME maybe unused



    CONST TRANSLATE_TABLE = [
        'en-en' => [
            '::ModuleName' => 'Folders module',
            '::MenuName' => 'Folders',
            '::ModuleDescription' => 'Folders management module',
            '::ModuleHelp' => 'This module permit to see and manage folders.',
            '::AppTitle1' => 'Folders',
            '::AppDesc1' => 'Manage folders',
        ],
    ];
}
