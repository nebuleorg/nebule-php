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
 * This module can manage media supports. There can be attached to groups (or objects) in a related gallery.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleGalleries extends Module {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'glr';
    const MODULE_DEFAULT_VIEW = 'galleries';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260112';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2025-2026';
    const MODULE_LOGO = '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'galleries',
        'gallery',
        'new_gallery',
        'mod_gallery',
        'del_gallery',
        'get_gallery',
        'syn_gallery',
        'rights_gallery',
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

    const RESTRICTED_TYPE = 'Gallery';
    const RESTRICTED_CONTEXT = '583718a8303dbcb757a1d2acf463e2410c807ebd1e4f319d3a641d1a6686a096b018.none.272';
    const FOLDER_CONTEXT = '967a0920b4091eda6c91a5234d0dc99a24b70c06740e24c8762995c6879a93b78c44.none.272';
    const COMMAND_SELECT_GALLERY = 'glr';
    const COMMAND_SELECT_ITEM = 'glr';
    const COMMAND_SELECT_FOLDER = 'folder';
    const COMMAND_ACTION_GET_GLR_NID = 'actiongetnid';
    const COMMAND_ACTION_GET_GLR_URL = 'actiongeturl';

    protected ?\Nebule\Library\Group $_instanceCurrentGallery = null;
    protected ?\Nebule\Library\Group $_instanceCurrentFolder = null;
    protected array $_path = array();
    protected array $_listSigners = array();
    protected array $_listTypes = array();



    protected function _initialisation(): void {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_socialClass = $this->getFilterInput(Displays::COMMAND_SOCIAL, FILTER_FLAG_ENCODE_LOW);
        $this->_getCurrentItem(self::COMMAND_SELECT_GALLERY, 'Gallery', $this->_instanceCurrentGallery);
        if (! is_a($this->_instanceCurrentGallery, 'Nebule\Library\Group') || $this->_instanceCurrentGallery->getID() == '0')
            $this->_instanceCurrentGallery = null;
        if (is_a($this->_instanceCurrentGallery, 'Nebule\Library\Group')) {
            $this->_getCurrentItem(self::COMMAND_SELECT_FOLDER, 'Folder', $this->_instanceCurrentFolder, $this->_instanceCurrentGallery->getID());
            if (!is_a($this->_instanceCurrentFolder, 'Nebule\Library\Group') || $this->_instanceCurrentFolder->getID() == '0')
                $this->_instanceCurrentFolder = $this->_instanceCurrentGallery;
        }
        $this->_getCurrentItemFounders($this->_instanceCurrentGallery);
        $this->_getCurrentItemSocialList($this->_instanceCurrentGallery);
        $this->_instanceCurrentItem = $this->_instanceCurrentFolder;
        $this->_setPath();
    }

    protected function _setPath(): void {
        if ($this->_instanceCurrentGallery === null)
            return;
        $cache = $this->_sessionInstance->getSessionStore('module:galleries:path');
        if (is_array($cache))
            $this->_path = $cache;
        else
            $this->_path = array(0 => $this->_instanceCurrentGallery->getID());
        if ($this->_instanceCurrentFolder->getID() != $this->_instanceCurrentGallery->getID()) {
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
        } else
            $this->_path = array(0 => $this->_instanceCurrentGallery->getID());
        $this->_sessionInstance->setSessionStore('module:galleries:path', $this->_path);
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $instance = null):array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->_applicationInstance->getCurrentObjectID();
        if ($instance !== null)
            $nid = $instance->getID();
        $hookArray = $this->getCommonHookList($hookName, $nid, 'Galleries', 'Gallery');

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuGalleries':
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[] = array(
                        'name' => '::rights',
                        'icon' => Displays::DEFAULT_ICON_IMODIFY,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                            . '&' . self::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                    if ($this->_unlocked && isset($this->_currentItemListOwners[$this->_entitiesInstance->getConnectedEntityEID()])) {
                        $hookArray[] = array(
                            'name' => '::modifyGallery',
                            'icon' => Displays::DEFAULT_ICON_IMODIFY,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                                . '&' . self::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                    }
                }
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[8]
                    && $this->_unlocked && isset($this->_currentItemWritersList[$this->_entitiesInstance->getConnectedEntityEID()])
                    && $this->_instanceCurrentGallery !== null) {
                    $hookArray[] = array(
                        'name' => '::removeGallery',
                        'icon' => Displays::DEFAULT_ICON_LX,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                            . '&' . self::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                break;

            case 'typeMenuEntity':
                $hookArray[] = array(
                    'name' => '::myGalleries',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . Displays::COMMAND_SOCIAL . '=myself'
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;

            case 'typeGallery':
                $rootID = $nid;
                if ($this->_instanceCurrentGallery !== null)
                    $rootID = $this->_instanceCurrentGallery->getID();
                $hookArray[] = array(
                    'name' => '::seeTheGallery',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . $this::COMMAND_SELECT_GALLERY . '=' . $rootID
                        . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;

            case 'selfFolder':
            case 'selfGallery':
                if ($this->_instanceCurrentGallery !== null) {
                    if ($this->_unlocked && isset($this->_currentItemWritersList[$this->_entitiesInstance->getConnectedEntityEID()])) {
                        $hookArray[] = array(
                            'name' => '::addFolder',
                            'icon' => $this::MODULE_REGISTERED_ICONS[2],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
                                . '&' . $this::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
                                . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                        $hookArray[] = array(
                            'name' => '::addMedia',
                            'icon' => $this::MODULE_REGISTERED_ICONS[2],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                                . '&' . $this::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
                                . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                        $hookArray[] = array(
                            'name' => '::addObjectMedia',
                            'icon' => $this::MODULE_REGISTERED_ICONS[2],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[10]
                                . '&' . $this::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
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
                            . '&' . $this::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
                            . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                break;

            case 'typeFolder':
                $rootID = $nid;
                if ($this->_instanceCurrentGallery !== null)
                    $rootID = $this->_instanceCurrentGallery->getID();
                $hookArray[] = array(
                    'name' => '::seeTheFolder',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . $this::COMMAND_SELECT_GALLERY . '=' . $rootID
                        . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                if ($this->_instanceCurrentFolder != null
                    && $this->_unlocked && isset($this->_currentItemWritersList[$this->_entitiesInstance->getConnectedEntityEID()])) {
                    $hookArray[] = array(
                        'name' => '::removeFolder',
                        'icon' => $this::MODULE_REGISTERED_ICONS[4],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . $this::COMMAND_SELECT_GALLERY . '=' . $rootID
                            . '&' . $this::COMMAND_SELECT_FOLDER . '=' . $this->_instanceCurrentFolder->getID()
                            . '&' . \Nebule\Library\ActionsGroups::REMOVE_MEMBER . '=' . $nid
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(),
                    );
                }
                break;

            case 'typeFile':
                $rootID = $nid;
                if ($this->_instanceCurrentGallery !== null)
                    $rootID = $this->_instanceCurrentGallery->getID();
                if ($this->_instanceCurrentFolder != null) {
                    $hookArray[] = array(
                        'name' => '::removeFile',
                        'icon' => $this::MODULE_REGISTERED_ICONS[4],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . $this::COMMAND_SELECT_GALLERY . '=' . $rootID
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
            case 'displayGalleries':
                if (!\Nebule\Library\Node::checkNID($item))
                    return null;
                $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[1]);
                $node = $this->_cacheInstance->newNode($item);
                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $this->_getCurrentItemFounders($node);
                $this->_getCurrentItemSocialList($node);
                $this->_socialInstance->setList($this->_currentItemWritersList, $this->_socialClass);
                $instance->setSocial($this->_socialClass);
                $instance->setNID($node);
                $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . self::COMMAND_SELECT_GALLERY . '=' . $item
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
                $instance->setTypeHookName('typeGallery');
                $instance->setIcon($instanceIcon);
                return $instance;
                break;
            case 'displayFolderMembers':
                if (!\Nebule\Library\Node::checkNID($item))
                    return null;
                if ($this->_listTypes[$item] == 'folder') {
                    $instanceIcon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[1]);
                    $node = $this->_cacheInstance->newNodeByType($item);
                    $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                    $this->_socialInstance->setList($this->_currentItemWritersList, $this->_socialClass);
                    $instance->setSocial($this->_socialClass);
                    $instance->setNID($node);
                    $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . self::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
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
                    $instance->setTypeHookName('typeFolder');
                    $instance->setIcon($instanceIcon);
                    return $instance;
                } elseif ($this->_listTypes[$item] == 'object') {
                    $instanceIcon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[0]);
                    $node = $this->_cacheInstance->newNodeByType($item);
                    $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                    $this->_socialInstance->setList($this->_currentItemWritersList, $this->_socialClass);
                    $instance->setSocial($this->_socialClass);
                    $instance->setNID($node);
                    $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . ModuleObjects::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $item
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
                    $instance->setEnableColor(true);
                    $instance->setEnableIcon(true);
                    $instance->setEnableName(true);
                    $instance->setEnableFlags(false);
                    $instance->setEnableContent(true);
                    $instance->setEnableJS(true);
                    if (sizeof($this->_listSigners[$item]) != 0) {
                        $instance->setEnableRefs(true);
                        $instance->setRefs($this->_listSigners[$item]);
                    } else
                        $instance->setEnableRefs(false);
                    $instance->setTypeHookName('typeFile');
                    $instance->setIcon($instanceIcon);
                    return $instance;
                } else
                    return null;
                break;
            default:
                return null;
        }
    }



    public function displayModule(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayItem('Gallery');
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayItemCreateForm('Gallery');
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModifyItem('Gallery');
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayRemoveItem('Gallery');
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGetItem('Gallery', 'Galleries', $this::COMMAND_ACTION_GET_GLR_NID, $this::COMMAND_ACTION_GET_GLR_URL);
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displaySynchroItem('Gallery', $this::COMMAND_ACTION_GET_GLR_NID);
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_displayRightsItem('Gallery');
                break;
            case $this::MODULE_REGISTERED_VIEWS[8]:
                $this->_displayOptions();
                break;
            case $this::MODULE_REGISTERED_VIEWS[9]:
                $this->_displayAddFolder();
                break;
            case $this::MODULE_REGISTERED_VIEWS[10]:
                $this->_displayAddNode();
                break;
            case $this::MODULE_REGISTERED_VIEWS[11]:
                $this->_displayUploadFile();
                break;
            default:
                $this->_displayListItems('Gallery', 'Galleries');
                break;
        }
    }

    public function displayModuleInline(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyItems('Galleries');
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineGallery();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlineRightsItem('Gallery');
                break;
        }
    }



    protected function _preDisplayItem(): void { $this->_displayPath(); }
    protected function _displayPath(): void {
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        if ($this->_instanceCurrentItem === null)
            return;
        if ($this->_socialClass == '')
            $this->_socialClass = 'onlist';
        if (! $this->_instanceCurrentItem->getMarkClosed())
            $this->_socialClass = 'all';
        foreach ($this->_path as $nid) {
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instancePath = $this->_cacheInstance->newNodeByType($nid);
            $instance->setNID($instancePath);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
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
            $this->_socialInstance->setList($this->_currentItemWritersList, $this->_socialClass);
            $instance->setSocial($this->_socialClass);
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_TINY);
        $instanceList->setPageColumns(6);
        $instanceList->display();
    }

    private function _display_InlineGallery(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!is_a($this->_instanceCurrentItem, 'Nebule\Library\Group')) {
            $this->_displayNotSupported();
            return;
        }

        if ($this->_socialClass == '')
            $this->_socialClass = 'onlist';
        $this->_socialInstance->setList($this->_currentItemWritersList, $this->_socialClass);
        if (! $this->_instanceCurrentItem->getMarkClosed())
            $this->_socialClass = 'all';
        $memberLinks = $this->_instanceCurrentItem->getListMembersLinks($this->_socialClass, $this->_currentItemWritersList);

        $list = array();
        $this->_listSigners = array();
        $this->_listTypes = array();
        foreach ($memberLinks as $link) {
            $nid = $link->getParsed()['bl/rl/nid2'];
            $instance = $this->_cacheInstance->newNode($nid);
            $list[$nid] = $nid;
            $this->_listSigners[$nid] = $link->getSignersEID();
            $this->_socialInstance->setList($this->_currentItemWritersList, $this->_socialClass);
            if ($instance->getIsGroup($this->_socialClass, $this::FOLDER_CONTEXT))
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
                . '&' . self::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
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

            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')) {
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
            }

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

    protected function _displayAddNode(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::addObjectMedia', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackItemOrLogin('Folder');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $link = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
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

            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')) {
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
            }

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_TEXT);
            $instance->setMessage('::addObjectMedia');
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::addObjectMedia'));
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
        $this->_displaySimpleTitle('::addMedia', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackItemOrLogin('Folder');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $link = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_GALLERY . '=' . $this->_instanceCurrentGallery->getID()
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
            $instance->setIconText('::addMedia');
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setLink($link);
            $instance->setWithSubmit(false);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_WARN_RID);
            $instance->setHiddenInput1('', $this->_configurationInstance->getOptionAsString('ioReadMaxData'));
            $instanceList->addItem($instance);

            if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject')) {
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
            }

            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')) {
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
            }

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_TEXT);
            $instance->setMessage('::addMedia');
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::addMedia'));
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
        if ($this->_socialClass == '')
            $this->_socialClass = 'onlist';
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
        $instanceList->setListHookName('displayGalleries');
        $instanceList->setListSize(12);
        $instanceList->setListItems($foldersNID);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }

    protected function _filterItemByType(string $nid): bool { return true; }



    CONST TRANSLATE_TABLE = [
        'en-en' => [
            '::ModuleName' => 'Galleries module',
            '::MenuName' => 'Galleries',
            '::ModuleDescription' => 'Galleries management module',
            '::ModuleHelp' => 'This module permit to see and manage galleries.',
            '::AppTitle1' => 'Galleries',
            '::AppDesc1' => 'Manage galleries',
            '::myGalleries' => 'My galleries',
            '::allGalleries' => 'All galleries',
            '::otherGalleries' => 'Galleries of other entities',
            '::listGalleries' => 'List of galleries',
            '::createClosedGallery' => 'Create a closed gallery',
            '::createObfuscatedGallery' => 'Create an obfuscated gallery',
            '::addMarkedObjects' => 'Add marked objects',
            '::addToGallery' => 'Add to gallery',
            '::addMember' => 'Add a member',
            '::deleteGallery' => 'Delete gallery',
            '::createGallery' => 'Create a gallery',
            '::createGalleryOK' => 'The gallery have been created',
            '::createGalleryNOK' => 'The gallery have not been created! %s',
        ],
    ];
}
