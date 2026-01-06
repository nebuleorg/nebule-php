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
 * This module can manage groups of everything.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleGroups extends Module {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'grp';
    const MODULE_DEFAULT_VIEW = 'groups';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260106';
    const MODULE_AUTHOR = 'Project nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2026';
    const MODULE_LOGO = '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'groups',       // 0
        'group',        // 1
        'new_group',    // 2
        'mod_group',    // 3
        'del_group',    // 4
        'get_group',    // 5
        'sync_group',   // 6
        'rights_group', // 7
        'add_to_group', // 8
        'add_members',  // 9
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
    const MODULE_APP_VIEW_LIST = array('groups');

    const RESTRICTED_TYPE = 'Node';
    const RESTRICTED_CONTEXT = '';
    const COMMAND_SELECT_ITEM = References::COMMAND_SELECT_GROUP;

    protected string $_hashGroup;
    protected string $_hashGroupClosed;
    protected \Nebule\Library\Node $_hashGroupObject;
    protected \Nebule\Library\Node $_hashGroupClosedObject;
    protected ?\Nebule\Library\Node $_instanceCurrentGroup = null;

    protected function _initialisation(): void {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_socialClass = $this->getFilterInput(Displays::COMMAND_SOCIAL, FILTER_FLAG_ENCODE_LOW);
        $this->_hashGroup = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE);
        $this->_hashGroupObject = $this->_cacheInstance->newNode($this->_hashGroup);
        $this->_hashGroupClosed = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
        $this->_hashGroupClosedObject = $this->_cacheInstance->newNode($this->_hashGroupClosed);
        $this->_getCurrentItem(References::COMMAND_SELECT_GROUP, 'Group', $this->_instanceCurrentGroup);
        if (! is_a($this->_instanceCurrentGroup, 'Nebule\Library\Node') || $this->_instanceCurrentGroup->getID() == '0')
            $this->_instanceCurrentGroup = null;
        $this->_getCurrentItemFounders($this->_instanceCurrentGroup);
        $this->_getCurrentItemSocialList($this->_instanceCurrentGroup);
        $this->_instanceCurrentItem = $this->_instanceCurrentGroup;
    }


    
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();
        $hookArray = $this->getCommonHookList($hookName, $object, 'Groups');

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuGroups':
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[] = array(
                        'name' => '::rights',
                        'icon' => Displays::DEFAULT_ICON_IMODIFY,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentGroup->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                break;

            /*case 'typeMenuGroups':
                // Refuser l'objet comme un groupe.
                $hookArray[1]['name'] = '::unmakeGroup';
                $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                $hookArray[1]['desc'] = '';
                $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                    . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                break;*/

            /*case 'selfMenuObject':
                // Affiche si l'objet courant est un groupe.
                if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('myself')) {
                    // Voir comme groupe.
                    $hookArray[0]['name'] = '::seeAsGroup';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    if ($this->_unlocked) {
                        // Refuser l'objet comme un groupe.
                        $hookArray[1]['name'] = '::unmakeGroup';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                } // Ou si c'est un groupe pour une autre entité.
                elseif ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
                    // Voir comme groupe.
                    $hookArray[0]['name'] = '::seeAsGroup';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    if ($this->_unlocked) {
                        // Faire de l'objet un groupe pour moi aussi.
                        $hookArray[1]['name'] = '::makeGroupMe';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

                        // Refuser l'objet comme un groupe.
                        $hookArray[2]['name'] = '::refuseGroup';
                        $hookArray[2]['icon'] = Displays::DEFAULT_ICON_LX;
                        $hookArray[2]['desc'] = '';
                        $hookArray[2]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                } // Ou si ce n'est pas un groupe.
                else {
                    if ($this->_unlocked) {
                        // Faire de l'objet un groupe.
                        $hookArray[0]['name'] = '::makeGroup';
                        $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                        $hookArray[0]['desc'] = '';
                        $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                }
                break;*/

            /*case 'typeMenuEntity':
                if ($this->_unlocked) {
                    $hookArray[] = array(
                        'name' => '::addToGroup',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                break;*/

            case 'myGroups':
                $hookArray[] = array(
                    'name' => '::seeGroup',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                if ($this->_unlocked && is_a($object, 'Nebule\Library\Node'))
                    $hookArray[] = array(
                        'name' => '::deleteGroup',
                        'icon' => Displays::DEFAULT_ICON_LX,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                            . '&' . \Nebule\Library\ActionsGroups::DELETE . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                break;

            case 'notMyGroups':
                $hookArray[] = array(
                    'name' => '::seeGroup',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                if ($this->_unlocked) {
                    if (is_a($object, 'Nebule\Library\Node') && $object->getIsGroup('myself')) // FIXME
                        $hookArray[] = array(
                            'name' => '::refuseGroup',
                            'icon' => Displays::DEFAULT_ICON_LX,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                                . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                                . '&' . \Nebule\Library\ActionsGroups::DELETE . '=' . $object
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                . $this->_tokenizeInstance->getActionTokenCommand(),
                        );
                    else
                        $hookArray[] = array(
                            'name' => '::thisIsGroup4me',
                            'icon' => $this::MODULE_REGISTERED_ICONS[1],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                                . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                . $this->_tokenizeInstance->getActionTokenCommand(),
                        );
                }
                break;

            case 'addToGroup':
                if ($this->_unlocked) {
                    $hookArray[] = array(
                        'name' => '::addToGroup',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
                            . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $this->_nebuleInstance->getCurrentEntityEID()
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntityEID()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
                break;

            case 'addMember':
                if ($this->_unlocked) {
                    $hookArray[] = array(
                        'name' => '::addMember',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[8]
                            . '&' . Displays::COMMAND_DISPLAY_PAGE_LIST . '=' . $this->_displayInstance->getCurrentPage()
                            . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $object
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntityEID()
                            . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroupOID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
                break;
        }
        return $hookArray;
    }



    public function getHookFunction(string $hookName, string $item): ?\Nebule\Library\DisplayItemIconMessageSizeable {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($hookName) {
            case 'addMember':
                $node = $this->_cacheInstance->newNode($item);
                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('self');
                $instance->setNID($node);
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                if ($this::RESTRICTED_TYPE == 'Entity') {
                    $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
                    $instance->setIcon($instanceIcon);
                }
                $instance->setEnableName(true);
                $instance->setEnableFlags(false);
                $instance->setEnableFlagState(false);
                $instance->setEnableFlagEmotions(false);
                $instance->setEnableFlagUnlocked(false);
                $instance->setEnableContent(false);
                $instance->setEnableJS(false);
                $instance->setEnableRefs(true);
                $instance->setSelfHookName('addMember');
                $instance->setEnableStatus(false);
                return $instance;
                break;
            default:
                return null;
        }
    }


    
    public function displayModule(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayItemCreateForm('Group', 0, 1, ($this::RESTRICTED_CONTEXT != ''));
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModifyItem('Group');
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayRemoveItem('Group');
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGetItem('Group');
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displaySynchroItem('Group');
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_displayRightsItem('Group');
                break;
            case $this::MODULE_REGISTERED_VIEWS[8]:
                $this->_displayAddMembers();
                break;
            case $this::MODULE_REGISTERED_VIEWS[9]:
                $this->_displayAddToGroup();
                break;
            default:
                $this->_displayListItems('Group', 'Groups');
                break;
        }
    }
    
    public function displayModuleInline(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyItems('Groups');
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlineRightsItem('Group');
                break;
            case $this::MODULE_REGISTERED_VIEWS[8]:
                $this->_display_InlineAddMembers();
                break;
            case $this::MODULE_REGISTERED_VIEWS[9]:
                $this->_display_InlineAddToGroup();
                break;
        }
    }



    /*protected function _displayGroupCreateForm(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $add_to_link = '';
            if ($this::RESTRICTED_TYPE != ModuleGroups::RESTRICTED_TYPE)
                $add_to_link = '&' . \Nebule\Library\ActionsGroups::CREATE_WITH_CONTENT;
            $commonLink = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                . '&' . \Nebule\Library\ActionsGroups::CREATE
                . '&' . \Nebule\Library\ActionsGroups::CREATE_CONTEXT . '=' . $this::RESTRICTED_CONTEXT
                . $add_to_link
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

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
            
            if ($this::RESTRICTED_TYPE != ModuleGroups::RESTRICTED_TYPE) {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::limitedType', $this->_translateInstance->getTranslate('::' . $this::RESTRICTED_TYPE));
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_INFORMATION);
            } else {
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
            }
            $instanceList->addItem($instance);
            
            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsGroups::CREATE_CLOSED);
            $instance->setIconText('::createClosedGroup');
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
            $instance->setIconText('::createObfuscatedGroup');
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
            $instance->setMessage('::createTheGroup');
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::createTheGroup'));
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
    }*/



    private function _displayGetGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    private function _displaySynchroGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }


    
    protected function _displayAddMarkedObjects(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::addMarkedObjects', References::REF_IMG['grpobjadd']);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('add_marked_objects');
    }

    protected function _display_InlineAddMarkedObjects(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }


    
    protected function _displayGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (is_a($this->_instanceCurrentGroup, 'Nebule\Library\Node')) {
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial('self');
            //$instance->setNID($this->_displayGroupInstance); FIXME
            $instance->setNID($this->_nebuleInstance->getCurrentGroupInstance());
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
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_LOGO);
            $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
            $instance->setIcon($instanceIcon2);
            $instance->display();

            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('group');
        } else
            $this->_displayNotSupported();
    }

    protected function _display_InlineGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $closedGroup = $this->_nebuleInstance->getCurrentGroupInstance()->getMarkClosed();

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        if ($closedGroup)
            $memberLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListTypedMembersLinks(References::REFERENCE_NEBULE_OBJET_GROUPE, 'myself');
        else
            $memberLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListTypedMembersLinks(References::REFERENCE_NEBULE_OBJET_GROUPE, 'all');

        $list = array();
        foreach ($memberLinks as $link)
            $list[$link->getParsed()['bl/rl/nid1']] = $link->getSignersEID();

        foreach ($list as $nid => $signers) {
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instanceMember = $this->_cacheInstance->newNode($nid);
            $instance->setSocial('self');
            $instance->setNID($instanceMember);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            //$instance->setIcon($instanceIcon);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            $instance->setRefs($signers);
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }



    protected function _displayAddToGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instance->setSocial('self');
        $instance->setNID($this->_nebuleInstance->getCurrentEntityInstance());
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
        $instance->setEnableFlagUnlocked(true);
        //$instance->setSelfHookName('typeMenuEntity');
        $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['oent']);
        $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
        $instance->setIcon($instanceIcon2);
        $instance->display();

        $this->_displaySimpleTitle('::addToGroup', References::REF_IMG['grpobjadd']);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('add_to_group');
    }

    protected function _display_InlineAddToGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_cacheInstance->newNode(References::RID_OBJECT_GROUP, \Nebule\Library\Cache::TYPE_NODE);
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid2' => References::RID_OBJECT_GROUP,
            'bl/rl/nid3' => References::RID_OBJECT_TYPE,
        );
        $instance->getLinks($links, $filter, 'all', false); // FIXME
        $this->_displayListOfItems($links, 'all', 'addToGroup');
    }



    protected function _displayAddMembers(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instance->setSocial('self');
        //$instance->setNID($this->_displayGroupInstance); FIXME
        $instance->setNID($this->_nebuleInstance->getCurrentGroupInstance());
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setEnableName(true);
        $instance->setEnableRefs(false);
        $instance->setEnableNID(false);
        $instance->setEnableFlags(true);
        $instance->setEnableFlagProtection(false);
        $instance->setEnableFlagObfuscate(false);
        $instance->setEnableFlagState(true);
        $instance->setEnableFlagEmotions(false);
        $instance->setEnableStatus(false);
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        $instance->setEnableLink(true);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        //$instance->setStatus('');
        $instance->setEnableFlagUnlocked(false);
        $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_LOGO);
        $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
        $instance->setIcon($instanceIcon2);
        $instance->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('add_members');
    }

    protected function _display_InlineAddMembers(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
            $instanceList->setListHookName('addMember');
            $instanceList->setListSize(12);
            $instanceList->setListItems($this->_listMembersToAdd());
            $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
            $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            $instanceList->setEnableWarnIfEmpty(false);
            $instanceList->display();
        } else {
            $instance = new \Nebule\Library\DisplayNotify($this->_applicationInstance);
            $instance->setMessage('::err_NotPermit');
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
            $instance->display();
        }
    }

    protected function _listMembersToAdd(): array { return $this->_ioInstance->getList(); }



    // Called by Modules::_display_InlineMyItems()
    protected function _displayListOfItems(array $links, string $socialClass = 'all', string $hookName = 'notMyGroups'): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $groupsGID = array();
        $groupsSigners = array();
        foreach ($links as $link) {
            $gid = $link->getParsed()['bl/rl/nid1'];
            if (!$this->_filterItemByType($gid))
                continue;
            $signers = $link->getSignersEID(); // FIXME get all signers
            $groupsGID[$gid] = $gid;
            foreach ($signers as $signer) {
                $groupsSigners[$gid][$signer] = $signer;
            }
        }
        $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[0]);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($groupsGID as $gid) {
            $instanceGroup = $this->_cacheInstance->newNode($gid, \Nebule\Library\Cache::TYPE_GROUP);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial($socialClass);
            $instance->setNID($instanceGroup);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . References::COMMAND_SELECT_GROUP . '=' . $gid
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            //$instance->setName($instanceGroup->getName('all'));
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableFlagUnlocked(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            if (isset($groupsSigners[$gid]) && sizeof($groupsSigners[$gid]) > 0) {
                $instance->setEnableRefs(true);
                $instance->setRefs($groupsSigners[$gid]);
            } else
                $instance->setEnableRefs(false);
            //$instance->setSelfHookName($hookName);
            $instance->setIcon($instanceIcon);
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty(true);
        $instanceList->display();
    }

    protected function _filterItemByType(string $gid): bool { return true; }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des groupes',
            '::MenuName' => 'Groupes',
            '::ModuleDescription' => 'Module de gestion des groupes',
            '::ModuleHelp' => 'Ce module permet de voir et de gérer les groupes.',
            '::AppTitle1' => 'Groupes',
            '::AppDesc1' => 'Gestion des groupes',
            '::groups' => 'Les groupes',
            '::myGroups' => 'Mes groupes',
            '::allGroups' => 'Tous les groupes',
            '::seeGroup' => 'Voir le groupe',
            '::seeAsGroup' => 'Voir comme groupe',
            '::seenFromOthers' => 'Vu depuis les autres entités',
            '::otherGroups' => 'Les groupes des autres entités',
            '::createGroup' => 'Créer un groupe',
            '::createClosedGroup' => 'Créer un groupe fermé',
            '::createObfuscatedGroup' => 'Créer un groupe dissimulé',
            '::addMarkedObjects' => 'Ajouter les objets marqués',
            '::addToGroup' => 'Ajouter au groupe',
            '::addMember' => 'Ajouter un membre',
            '::deleteGroup' => 'Supprimer le groupe',
            '::createTheGroup' => 'Créer le groupe',
            '::createGroupOK' => 'Le groupe a été créé',
            '::createGroupNOK' => "Le groupe n'a pas été créé ! %s",
            '::membersType' => 'Type des membres',
            '::noGroup' => 'Pas de groupe',
            '::noGroupMember' => 'Pas de membre',
            '::makeGroup' => 'Faire de cet objet un groupe',
            '::makeGroupMe' => 'Faire de cet objet un groupe pour moi aussi',
            '::unmakeGroup' => 'Ne plus faire de cet objet un groupe',
            '::useAsGroupOpened' => 'Utiliser comme groupe ouvert',
            '::useAsGroupClosed' => 'Utiliser comme groupe fermé',
            '::refuseGroup' => 'Refuser cet objet comme un groupe',
            '::removeFromGroup' => 'Retirer du groupe',
            '::isGroup' => 'est un groupe',
            '::isGroupToOther' => 'est un groupe de',
            '::isNotGroup' => "n'est pas un groupe",
            '::thisIsGroup' => "C'est un groupe",
            '::thisIsGroup4me' => "C'est un groupe pour moi",
            '::thisIsNotGroup' => "Ce n'est pas un groupe",
        ],
        'en-en' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups',
            '::groups' => 'The groups',
            '::myGroups' => 'My groups',
            '::allGroups' => 'All groups',
            '::seeGroup' => 'See the group',
            '::seeAsGroup' => 'See as group',
            '::seenFromOthers' => 'Seen from others entities',
            '::otherGroups' => 'Groups of other entities',
            '::createGroup' => 'Create a group',
            '::createClosedGroup' => 'Create a closed group',
            '::createObfuscatedGroup' => 'Create an obfuscated group',
            '::addMarkedObjects' => 'Add marked objects',
            '::addToGroup' => 'Add to group',
            '::addMember' => 'Add a member',
            '::deleteGroup' => 'Delete group',
            '::createTheGroup' => 'Create the group',
            '::createGroupOK' => 'The group have been created',
            '::createGroupNOK' => 'The group have not been created! %s',
            '::membersType' => 'Type of members',
            '::noGroup' => 'No group',
            '::noGroupMember' => 'No member',
            '::makeGroup' => 'Make this object a group',
            '::makeGroupMe' => 'Make this object a group for me too',
            '::unmakeGroup' => 'Unmake this object a group',
            '::useAsGroupOpened' => 'Use as group opened',
            '::useAsGroupClosed' => 'Use as group closed',
            '::refuseGroup' => 'Refuse this object as group',
            '::removeFromGroup' => 'Remove from group',
            '::isGroup' => 'is a group',
            '::isGroupToOther' => 'is a group of',
            '::isNotGroup' => 'is not a group',
            '::thisIsGroup' => 'This is a group',
            '::thisIsGroup4me' => 'This is a group for me',
            '::thisIsNotGroup' => 'This is not a group',
        ],
        'es-co' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups',
            '::groups' => 'The groups',
            '::myGroups' => 'My groups',
            '::allGroups' => 'All groups',
            '::seeGroup' => 'See the group',
            '::seeAsGroup' => 'See as group',
            '::seenFromOthers' => 'Seen from others entities',
            '::otherGroups' => 'Groups of other entities',
            '::createGroup' => 'Create a group',
            '::createClosedGroup' => 'Create a closed group',
            '::createObfuscatedGroup' => 'Create an obfuscated group',
            '::addMarkedObjects' => 'Add marked objects',
            '::addToGroup' => 'Add to group',
            '::addMember' => 'Add a member',
            '::deleteGroup' => 'Delete group',
            '::createTheGroup' => 'Create the group',
            '::createGroupOK' => 'The group have been created',
            '::createGroupNOK' => 'The group have not been created! %s',
            '::membersType' => 'Type of members',
            '::noGroup' => 'No group',
            '::noGroupMember' => 'No member',
            '::makeGroup' => 'Make this object a group',
            '::makeGroupMe' => 'Make this object a group for me too',
            '::unmakeGroup' => 'Unmake this object a group',
            '::useAsGroupOpened' => 'Use as group opened',
            '::useAsGroupClosed' => 'Use as group closed',
            '::refuseGroup' => 'Refuse this object as group',
            '::removeFromGroup' => 'Remove from group',
            '::isGroup' => 'is a group',
            '::isGroupToOther' => 'is a group of',
            '::isNotGroup' => 'is not a group',
            '::thisIsGroup' => 'This is a group',
            '::thisIsGroup4me' => 'This is a group for me',
            '::thisIsNotGroup' => 'This is not a group',
        ],
    ];
}


/**
 * This module can manage groups of entities.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleGroupEntities extends ModuleGroups {
    const MODULE_COMMAND_NAME = 'grpent';
    const MODULE_LOGO = '425d033815bd76844fc5100ad0ccb2c3d6cd981315794b95210d6c6ec8da22e8faaf.none.272';
    const MODULE_REGISTERED_ICONS = array(
        Displays::DEFAULT_ICON_LO,
        Displays::DEFAULT_ICON_LSTENT,
        Displays::DEFAULT_ICON_ADDENT,
        Displays::DEFAULT_ICON_IMODIFY,
        Displays::DEFAULT_ICON_LD,
        Displays::DEFAULT_ICON_HELP,
        Displays::DEFAULT_ICON_LL,
    );
    const MODULE_APP_ICON_LIST = array('425d033815bd76844fc5100ad0ccb2c3d6cd981315794b95210d6c6ec8da22e8faaf.none.272');
    const RESTRICTED_TYPE = 'Entity';
    const RESTRICTED_CONTEXT = References::RID_OBJECT_GROUP_ENTITY;

    protected function _filterItemByType(string $gid): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return true; // FIXME for group of entities
    }

    protected function _listMembersToAdd(): array { return $this->_entitiesInstance->getListEntitiesID(); }

    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => "Module des groupes d'entités",
            '::MenuName' => "Groupes d'entités",
            '::ModuleDescription' => "Module de gestion des groupes d'entités",
            '::ModuleHelp' => "Ce module permet de voir et de gérer les groupes d'entités.",
            '::AppTitle1' => "Groupes d'entités",
            '::AppDesc1' => "Gestion des groupes d'entités",
            '::groups' => 'Les groupes',
            '::myGroups' => 'Mes groupes',
            '::allGroups' => 'Tous les groupes',
            '::seeGroup' => 'Voir le groupe',
            '::seeAsGroup' => 'Voir comme groupe',
            '::seenFromOthers' => 'Vu depuis les autres entités',
            '::otherGroups' => 'Les groupes des autres entités',
            '::createGroup' => "Créer un groupe d'entités",
            '::createClosedGroup' => 'Créer un groupe fermé',
            '::createObfuscatedGroup' => 'Créer un groupe dissimulé',
            '::addMarkedObjects' => 'Ajouter les entités marqués',
            '::addToGroup' => 'Ajouter au groupe',
            '::addMember' => 'Ajouter une entité membre',
            '::deleteGroup' => 'Supprimer le groupe',
            '::createTheGroup' => 'Créer le groupe',
            '::createGroupOK' => 'Le groupe a été créé',
            '::createGroupNOK' => "Le groupe n'a pas été créé ! %s",
            '::membersType' => 'Type des membres',
            '::noGroup' => 'Pas de groupe',
            '::noGroupMember' => 'Pas de membre',
            '::makeGroup' => 'Faire de cet objet un groupe',
            '::makeGroupMe' => 'Faire de cet objet un groupe pour moi aussi',
            '::unmakeGroup' => 'Ne plus faire de cet objet un groupe',
            '::useAsGroupOpened' => 'Utiliser comme groupe ouvert',
            '::useAsGroupClosed' => 'Utiliser comme groupe fermé',
            '::refuseGroup' => 'Refuser cet objet comme un groupe',
            '::removeFromGroup' => 'Retirer du groupe',
            '::isGroup' => 'est un groupe',
            '::isGroupToOther' => 'est un groupe de',
            '::isNotGroup' => "n'est pas un groupe",
            '::thisIsGroup' => "C'est un groupe",
            '::thisIsGroup4me' => "C'est un groupe pour moi",
            '::thisIsNotGroup' => "Ce n'est pas un groupe",
            '::confirm' => 'Confirmation',
        ],
        'en-en' => [
            '::ModuleName' => 'Entities groups module',
            '::MenuName' => 'Entities groups',
            '::ModuleDescription' => "Entities's groups management module",
            '::ModuleHelp' => 'This module permit to see and manage groups of entities.',
            '::AppTitle1' => 'Entities groups',
            '::AppDesc1' => 'Manage groups of entities',
            '::groups' => 'The groups',
            '::myGroups' => 'My groups',
            '::allGroups' => 'All groups',
            '::seeGroup' => 'See the group',
            '::seeAsGroup' => 'See as group',
            '::seenFromOthers' => 'Seen from others entities',
            '::otherGroups' => 'Groups of other entities',
            '::createGroup' => 'Create a group of entities',
            '::createClosedGroup' => 'Create a closed group',
            '::createObfuscatedGroup' => 'Create an obfuscated group',
            '::addMarkedObjects' => 'Add marked entities',
            '::addToGroup' => 'Add to group',
            '::addMember' => 'Add an entity as member',
            '::deleteGroup' => 'Delete group',
            '::createTheGroup' => 'Create the group',
            '::createGroupOK' => 'The group have been created',
            '::createGroupNOK' => 'The group have not been created! %s',
            '::membersType' => 'Type of members',
            '::noGroup' => 'No group',
            '::noGroupMember' => 'No member',
            '::makeGroup' => 'Make this object a group',
            '::makeGroupMe' => 'Make this object a group for me too',
            '::unmakeGroup' => 'Unmake this object a group',
            '::useAsGroupOpened' => 'Use as group opened',
            '::useAsGroupClosed' => 'Use as group closed',
            '::refuseGroup' => 'Refuse this object as group',
            '::removeFromGroup' => 'Remove from group',
            '::isGroup' => 'is a group',
            '::isGroupToOther' => 'is a group of',
            '::isNotGroup' => 'is not a group',
            '::thisIsGroup' => 'This is a group',
            '::thisIsGroup4me' => 'This is a group for me',
            '::thisIsNotGroup' => 'This is not a group',
            '::confirm' => 'Confirm',
        ],
        'es-co' => [
            '::ModuleName' => 'Módulo de grupos de entidades',
            '::MenuName' => 'Grupos de entidades',
            '::ModuleDescription' => 'Módulo de gestión de grupos de entidades',
            '::ModuleHelp' => 'Este módulo permite ver y gestionar grupos de entidades.',
            '::AppTitle1' => 'Grupos de entidades',
            '::AppDesc1' => 'Gestionar grupos de entidades',
            '::groups' => 'Los grupos',
            '::myGroups' => 'Mis grupos',
            '::allGroups' => 'Todos los grupos',
            '::seeGroup' => 'Ver el grupo',
            '::seeAsGroup' => 'Ver como grupo',
            '::seenFromOthers' => 'Visto desde otras entidades',
            '::otherGroups' => 'Grupos de otras entidades',
            '::createGroup' => 'Crear un grupo de entidades',
            '::createClosedGroup' => 'Crear un grupo cerrado',
            '::createObfuscatedGroup' => 'Crear un grupo oculto',
            '::addMarkedObjects' => 'Añadir objetos entidades',
            '::addToGroup' => 'Añadir al grupo',
            '::addMember' => 'Add an entity as member',
            '::deleteGroup' => 'Eliminar grupo',
            '::createTheGroup' => 'Crear el grupo',
            '::createGroupOK' => 'El grupo ha sido creado',
            '::createGroupNOK' => '¡El grupo no ha sido creado! %s',
            '::membersType' => 'Tipo de miembros',
            '::noGroup' => 'Sin grupo',
            '::noGroupMember' => 'Sin miembros',
            '::makeGroup' => 'Convertir este objeto en un grupo',
            '::makeGroupMe' => 'Convertir este objeto en un grupo para mí también',
            '::unmakeGroup' => 'Dejar de usar este objeto como un grupo',
            '::useAsGroupOpened' => 'Usar como grupo abierto',
            '::useAsGroupClosed' => 'Usar como grupo cerrado',
            '::refuseGroup' => 'Rechazar este objeto como grupo',
            '::removeFromGroup' => 'Eliminar del grupo',
            '::isGroup' => 'es un grupo',
            '::isGroupToOther' => 'es un grupo de',
            '::isNotGroup' => 'no es un grupo',
            '::thisIsGroup' => 'Esto es un grupo',
            '::thisIsGroup4me' => 'Esto es un grupo para mí',
            '::thisIsNotGroup' => 'Esto no es un grupo',
            '::confirm' => 'Confirmar',
        ],
    ];
}