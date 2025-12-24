<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Actions;
use Nebule\Library\ActionsEntities;
use Nebule\Library\ActionsGroups;
use Nebule\Library\ActionsLinks;
use Nebule\Library\Cache;
use Nebule\Library\DisplayInformation;
use Nebule\Library\DisplayItem;
use Nebule\Library\DisplayItemIconMessage;
use Nebule\Library\DisplayList;
use Nebule\Library\DisplayNotify;
use Nebule\Library\DisplayObject;
use Nebule\Library\DisplayQuery;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Group;
use Nebule\Library\Metrology;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * This module can manage groups of everything.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleGroups extends \Nebule\Library\Modules {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'grp';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020251224';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2025';
    const MODULE_LOGO = '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'list',         // 0
        'alist',        // 1
        'setgroup',     // 2
        'unsetgroup',   // 3
        'addmarked',    // 4
        'disp',         // 5
        'olist',        // 6
    );
    const MODULE_REGISTERED_ICONS = array(
        '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256',    // 0 : Icône des groupes.
        '819babe3072d50f126a90c982722568a7ce2ddd2b294235f40679f9d220e8a0a.sha2.256',    // 1 : Créer un groupe.
        'a269514d2b940d8269993a6f0138f38bbb86e5ac387dcfe7b810bf871002edf3.sha2.256',    // 2 : Ajouter objets marqués.
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    const RESTRICTED_TYPE = '';

    protected string $_hashGroup;
    protected string $_hashGroupClosed;
    protected Node $_hashGroupObject;
    protected Node $_hashGroupClosedObject;
    
    protected function _initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_hashGroup = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE);
        $this->_hashGroupObject = $this->_cacheInstance->newNode($this->_hashGroup);
        $this->_hashGroupClosed = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
        $this->_hashGroupClosedObject = $this->_cacheInstance->newNode($this->_hashGroupClosed);
    }


    
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();

        switch ($hookName) {
            /*case 'menu':
                $hookArray[0]['name'] = '::myGroups';
                $hookArray[0]['icon'] = $this::MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;*/
            case 'selfMenu':
            case 'typeMenuGroup':
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[0]) {
                    $hookArray[] = array(
                        'name' => '::myGroups',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[6]) {
                    $hookArray[] = array(
                        'name' => '::otherGroups',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[6]
                                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[] = array(
                        'name' => '::allGroups',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_unlocked) {
                    if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[0]) {
                        $hookArray[] = array(
                            'name' => '::createGroup',
                            'icon' => $this::MODULE_REGISTERED_ICONS[1],
                            'desc' => '',
                            'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                    }
                    /*if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[5]) {
                        $hookArray[] = array(
                            'name' => '::unmakeGroup',
                            'icon' => Displays::DEFAULT_ICON_LX,
                            'desc' => '',
                            'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroupOID()
                                    . '&' . \Nebule\Library\ActionsGroups::DELETE
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(),
                        );
                    }*/
                }
                break;

            case 'selfMenuGroup':
                // Refuser l'objet comme un groupe.
                $hookArray[1]['name'] = '::unmakeGroup';
                $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                $hookArray[1]['desc'] = '';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                    . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                break;

            case 'selfMenuObject':
                // Affiche si l'objet courant est un groupe.
                if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('myself')) {
                    // Voir comme groupe.
                    $hookArray[0]['name'] = '::seeAsGroup';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    if ($this->_unlocked) {
                        // Refuser l'objet comme un groupe.
                        $hookArray[1]['name'] = '::unmakeGroup';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                            . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                } // Ou si c'est un groupe pour une autre entité.
                elseif ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
                    // Voir comme groupe.
                    $hookArray[0]['name'] = '::seeAsGroup';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    if ($this->_unlocked) {
                        // Faire de l'objet un groupe pour moi aussi.
                        $hookArray[1]['name'] = '::makeGroupMe';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . ActionsLinks::SIGN1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

                        // Refuser l'objet comme un groupe.
                        $hookArray[2]['name'] = '::refuseGroup';
                        $hookArray[2]['icon'] = Displays::DEFAULT_ICON_LX;
                        $hookArray[2]['desc'] = '';
                        $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                            . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
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
                        $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . ActionsLinks::SIGN1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                }
                break;

            case 'selfMenuEntity':
                $hookArray[0]['name'] = '::myGroups';
                $hookArray[0]['icon'] = $this::MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;

            /*case 'typeMenuEntity':
                $hookArray[0]['name'] = '::groups';
                $hookArray[0]['icon'] = $this::MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;*/
        }
        return $hookArray;
    }


    
    public function displayModule(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            /*case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_displayMyGroups();
                break;*/
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayAllGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayCreateGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayRemoveGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayAddMarkedObjects();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displayOtherGroups();
                break;
            default:
                $this->_displayMyGroups();
                break;
        }
    }
    
    public function displayModuleInline(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineAllGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_display_InlineAddMarkedObjects();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_display_InlineGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_display_InlineOtherGroups();
                break;
        }
    }



    protected function _displayMyGroups(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $this->_displaySimpleTitle('::createGroup', $this::MODULE_REGISTERED_ICONS[1]);
            $this->_displayGroupCreateNew();
        }
        $this->_displaySimpleTitle('::myGroups', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_displayInstance->registerInlineContentID('my_groups');
    }

    protected function _display_InlineMyGroups(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    protected function _displayOtherGroups(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::otherGroups', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('other_groups');
    }

    protected function _display_InlineOtherGroups(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    protected function _displayAllGroups(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::allGroups', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('all_groups');
    }

    protected function _display_InlineAllGroups(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_cacheInstance->newNode($this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE), \Nebule\Library\Cache::TYPE_NODE);
$this->_metrologyInstance->addLog('DEBUGGING group nid=' . $instance->getID(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => '',
            'bl/rl/nid2' => $instance->getID(),
            'bl/rl/nid3' => $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_TYPE),
        );
        $instance->getLinks($links, $filter, 'all', false);
$this->_metrologyInstance->addLog('DEBUGGING group links size=' . sizeof($links), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $groupsGID = array();
        $groupsSigners = array();
        foreach ($links as $link) {
            $gid = $link->getParsed()['bl/rl/nid1'];
$this->_metrologyInstance->addLog('DEBUGGING group gid=' . $gid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
            if (!$this->_filterGroupByType($gid))
                continue;
            $signers = array($link->getParsed()['bs/rs1/eid']); // FIXME get all signers
            $groupsGID[$gid] = $gid;
            foreach ($signers as $signer)
$this->_metrologyInstance->addLog('DEBUGGING group signer=' . $signer, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                $groupsSigners[$gid][$signer] = $signer;
        }
$this->_metrologyInstance->addLog('DEBUGGING group groupsGID size=' . sizeof($groupsGID), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
$this->_metrologyInstance->addLog('DEBUGGING group groupsSigners size=' . sizeof($groupsSigners), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($groupsGID as $gid) {
$this->_metrologyInstance->addLog('DEBUGGING group gid=' . $gid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
            $instanceGroup = $this->_cacheInstance->newNode($gid, \Nebule\Library\Cache::TYPE_GROUP);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial('self');
            $instance->setNID($instanceGroup);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setIcon($instanceIcon);
            $instance->setEnableName(true);
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
            $instance->setSelfHookName('FIXME'); // FIXME
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty(true);
        $instanceList->display();
    }



    protected function _displayCreateGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::createGroup', $this::MODULE_REGISTERED_ICONS[1]);
        $this->_displayGroupCreateForm();
        // MyGroups() view displays the result of the creation
    }

    protected function _displayGroupCreateNew(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $instanceList = new DisplayList($this->_applicationInstance);
            $instance = new DisplayInformation($this->_applicationInstance);
            $instance->setRatio(DisplayItem::RATIO_SHORT);
            if (!$this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateError()) {
                $instance->setMessage('::createGroupOK');
                $instance->setType(DisplayItemIconMessage::TYPE_OK);
                $instance->setIconText('::::OK');
                $instanceList->addItem($instance);

                $instance = new DisplayObject($this->_applicationInstance);
                $instance->setSocial('self');
                //$instance->setNID($this->_displayGroupInstance); FIXME
                $instance->setNID($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateInstance());
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
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setStatus('');
                $instance->setEnableFlagUnlocked(false);
                $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['grpobj']); // FIXME
                $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
                $instance->setIcon($instanceIcon2);
            } else {
                $instance = new DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::notOKCreateGroup');
                $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setIconText('::::ERROR');
            }
            $instanceList->addItem($instance);
            $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
            $instanceList->setOnePerLine();
            $instanceList->display();
        }
        echo '<br />' . "\n";
    }

    protected function _displayGroupCreateForm(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->checkBooleanOptions(array('unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteGroup'))) {
            $commonLink = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . ActionsGroups::CREATE
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

            $instanceList = new DisplayList($this->_applicationInstance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(ActionsGroups::CREATE_NAME);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_NOM);
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setLink($commonLink);
            $instance->setWithSubmit(false);
            $instance->setIconRID(DisplayItemIconMessage::ICON_WARN_RID);
            $instanceList->addItem($instance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_SELECT);
            $instance->setInputName(ActionsGroups::CREATE_CLOSED);
            $instance->setIconText('::createGroupClosed');
            $instance->setSelectList(array(
                    'y' => $this->_translateInstance->getTranslate('::::yes'),
                    'n' => $this->_translateInstance->getTranslate('::::no'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_SELECT);
            $instance->setInputName(ActionsGroups::CREATE_OBFUSCATED);
            $instance->setIconText('::createGroupObfuscated');
            $instance->setSelectList(array(
                    'n' => $this->_translateInstance->getTranslate('::::no'),
                    'y' => $this->_translateInstance->getTranslate('::::yes'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_TEXT);
            $instance->setMessage('::createTheGroup');
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::createTheGroup'));
            $instance->setIconText('::confirm');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(true);
            $instance->setWithSubmit(true);
            //$instance->setLink($commonLink);
            $instanceList->addItem($instance);

            $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
            $instanceList->setOnePerLine();
            $instanceList->display();
        } else {
            $instance = new DisplayNotify($this->_applicationInstance);
            $instance->setMessage('::::err_NotPermit');
            $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
            $instance->display();
        }
    }

    protected function _displayRemoveGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Affichage de l'entête.
        $this->_applicationInstance->getDisplayInstance()->displayObjectDivHeaderH1($this->_applicationInstance->getCurrentObjectInstance());

        if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
            // Affichage les actions possibles.
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::sylabe:module:group:remove');
        } else {
            // Ce n'est pas un groupe.
            $this->_applicationInstance->getDisplayInstance()->displayMessageError('::thisIsNotGroup');
        }
    }


    
    protected function _displayAddMarkedObjects(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }

    protected function _display_InlineAddMarkedObjects(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }


    
    protected function _displayGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_nebuleInstance->getCurrentGroupInstance();

        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => true,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );
        $param['objectRefs'] = $instance->getPropertySigners(References::REFERENCE_NEBULE_OBJET_GROUPE);
        echo $this->_displayInstance->getDisplayObject_DEPRECATED($instance, $param);
        unset($instance);

        // Affichage des membres du groupe.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('display_group');
    }

    protected function _display_InlineGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO

        // Détermine si c'est un groupe fermé.
        /*if ($this->_nebuleInstance->getCurrentGroupInstance()->getMarkClosedGroup()) {
            // Liste tous les objets du groupe fermé.
            $groupListID = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersID('self', null);

            //Prépare l'affichage.
            if (sizeof($groupListID) != 0) {
                $list = array();
                $listOkItems = array($this->_hashGroup => true, $this->_hashGroupClosed => true);
                $i = 0;
                foreach ($groupListID as $item) {
                    if (!isset($listOkItems[$item])) {
                        $instance = $this->_applicationInstance->getTypedInstanceFromNID($item);

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = '';
                        $list[$i]['icon'] = '';
                        $list[$i]['htlink'] = '';
                        $list[$i]['desc'] = '';
                        $list[$i]['actions'] = array();

                        // Supprimer le groupe.
                        if ($this->_unlocked
                            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                        ) {
                            $list[$i]['actions'][0]['name'] = '::removeFromGroup';
                            $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                                . '&' . $this->_nebuleInstance->getCurrentGroupOID()
                                . '&' . ActionsGroups::REMOVE_MEMBER . '=' . $item
                                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                        }

                        // Marque comme vu.
                        $listOkItems[$item] = true;
                        $i++;
                    }
                }
                unset($groupListID, $listOkItems, $item, $instance);
                // Affichage
                if (sizeof($list) != 0)
                    $this->_applicationInstance->getDisplayInstance()->displayItemList($list);

                // Liste tous les objets du groupe ouvert.
                $groupListLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersLinks('self'); // @todo à vérifier self.

                //Prépare l'affichage.
                if (sizeof($groupListLinks) != 0) {
                    $hashGroupPriv = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
                    $list = array();
                    $listOkItems = array();
                    $i = 0;
                    foreach ($groupListLinks as $item) {
                        // Vérifie si le couple membre/signataire n'est pas déjà pris en compte.
                        // Vérifie si le signataire n'est pas l'entité en cours.
                        if (!isset($listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']])
                            && $item->getParsed()['bs/rs1/eid'] != $this->_entitiesInstance->getGhostEntityEID()
                        ) {
                            $instance = $this->_applicationInstance->getTypedInstanceFromNID($item->getParsed()['bl/rl/nid1']);
                            $instanceSigner = $this->_cacheInstance->newNode($item->getParsed()['bs/rs1/eid'], \Nebule\Library\Cache::TYPE_ENTITY);
                            $closed = '::GroupeOuvert';
                            if ($item->getParsed()['bl/rl/nid3'] == $hashGroupPriv)
                                $closed = '::GroupeFerme';

                            $list[$i]['object'] = $instance;
                            $list[$i]['entity'] = $instanceSigner;
                            $list[$i]['icon'] = '';
                            $list[$i]['htlink'] = '';
                            $list[$i]['desc'] = $this->_translateInstance->getTranslate($closed);
                            $list[$i]['actions'] = array();

                            // Supprimer le groupe.
                            if ($this->_unlocked
                                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                                && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                            ) {
                                $list[$i]['actions'][0]['name'] = '::removeFromGroup';
                                $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                                $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroupOID()
                                    . '&' . ActionsGroups::REMOVE_MEMBER . '=' . $item->getParsed()['bl/rl/nid1']
                                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                            }

                            // Marque comme vu.
                            $listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']] = true;
                            $i++;
                        }
                    }
                    unset($groupListLinks, $listOkItems, $item, $instance, $hashGroupPriv, $closed);
                    // Affichage
                    if (sizeof($list) != 0) {
                        echo "<div class=\"sequence\"></div>\n";
                        $iconNID = $this->_cacheInstance->newNode($this::MODULE_LOGO);
                        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
                        $instance->setTitle('::seenFromOthers');
                        $instance->setIcon($iconNID);
                        $instance->display();
                        $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
                    }
                }
                unset($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::noGroupMember');
            }
        } // Sinon c'est un groupe ouvert.
        else {
            // Liste tous les groupes.
            $groupListLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersLinks('self'); // @todo à vérifier self.

            //Prépare l'affichage.
            if (sizeof($groupListLinks) != 0) {
                $hashGroupPriv = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
                $list = array();
                $listOkItems = array();
                $i = 0;
                foreach ($groupListLinks as $item) {
                    // Vérifie si le couple membre/signataire n'est pas déjà pris en compte.
                    if (!isset($listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']])) {
                        $instance = $this->_applicationInstance->getTypedInstanceFromNID($item->getParsed()['bl/rl/nid1']);
                        $instanceSigner = $this->_cacheInstance->newNode($item->getParsed()['bs/rs1/eid'], \Nebule\Library\Cache::TYPE_ENTITY);
                        $closed = '::GroupeOuvert';
                        if ($item->getParsed()['bl/rl/nid3'] == $hashGroupPriv)
                            $closed = '::GroupeFerme';

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = $instanceSigner;
                        $list[$i]['icon'] = '';
                        $list[$i]['htlink'] = '';
                        $list[$i]['desc'] = $this->_translateInstance->getTranslate($closed);
                        $list[$i]['actions'] = array();

                        // Supprimer le groupe.
                        if ($this->_unlocked
                            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                        ) {
                            $list[$i]['actions'][0]['name'] = '::removeFromGroup';
                            $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                                . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroupOID()
                                . '&' . ActionsGroups::REMOVE_MEMBER . '=' . $item->getParsed()['bl/rl/nid1']
                                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                        }

                        // Marque comme vu.
                        $listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']] = true;
                        $i++;
                    }
                }
                unset($groupListLinks, $listOkItems, $item, $instance, $hashGroupPriv, $closed);
                // Affichage
                if (sizeof($list) != 0)
                    $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
                unset($list);
            } else {
                // Pas d'entité.
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::noGroupMember');
            }
        }*/
    }



    protected function _filterGroupByType(string $gid): bool { return true; }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des groupes',
            '::MenuName' => 'Groupes',
            '::ModuleDescription' => 'Module de gestion des groupes.',
            '::ModuleHelp' => "Ce module permet de voir et de gérer les groupes.",
            '::AppTitle1' => 'Groupes',
            '::AppDesc1' => 'Gestion des groupes.',
            '::groups' => 'Les groupes',
            '::myGroups' => 'Mes groupes',
            '::allGroups' => 'Tous les groupes',
            '::seeAsGroup' => 'Voir comme groupe',
            '::seenFromOthers' => 'Vu depuis les autres entités',
            '::otherGroups' => 'Les groupes des autres entités',
            '::createGroup' => 'Créer un groupe',
            '::createGroupClosed' => 'Créer un groupe fermé',
            '::createGroupObfuscated' => 'Créer un groupe dissimulé',
            '::addMarkedObjects' => 'Ajouter les objets marqués',
            '::deleteGroup' => 'Supprimer le groupe',
            '::createTheGroup' => 'Créer le groupe',
            '::nom' => 'Nom',
            '::createGroupOK' => 'Le groupe a été créé.',
            '::notOKCreateGroup' => "Le groupe n'a pas été créé ! %s",
            '::noGroup' => 'Pas de groupe.',
            '::noGroupMember' => 'Pas de membre.',
            '::makeGroup' => 'Faire de cet objet un groupe',
            '::makeGroupMe' => 'Faire de cet objet un groupe pour moi aussi',
            '::unmakeGroup' => 'Ne plus faire de cet objet un groupe',
            '::useAsGroupOpened' => 'Utiliser comme groupe ouvert',
            '::useAsGroupClosed' => 'Utiliser comme groupe fermé',
            '::refuseGroup' => 'Refuser cet objet comme un groupe',
            '::removeFromGroup' => 'Retirer du groupe',
            '::isGroup' => 'est un groupe.',
            '::isGroupToOther' => 'est un groupe de',
            '::isNotGroup' => "n'est pas un groupe.",
            '::thisIsGroup' => "C'est un groupe.",
            '::thisIsNotGroup' => "Ce n'est pas un groupe.",
            '::confirm' => 'Confirmation',
        ],
        'en-en' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module.',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups.',
            '::groups' => 'The groups',
            '::myGroups' => 'My groups',
            '::allGroups' => 'All groups',
            '::seeAsGroup' => 'See as group',
            '::seenFromOthers' => 'Seen from others entities',
            '::otherGroups' => 'Groups of other entities',
            '::createGroup' => 'Create a group',
            '::createGroupClosed' => 'Create a closed group',
            '::createGroupObfuscated' => 'Create an obfuscated group',
            '::addMarkedObjects' => 'Add marked objects',
            '::deleteGroup' => 'Delete group',
            '::createTheGroup' => 'Create the group',
            '::nom' => 'Name',
            '::createGroupOK' => 'The group have been created.',
            '::notOKCreateGroup' => 'The group have not been created! %s',
            '::noGroup' => 'No group.',
            '::noGroupMember' => 'No member.',
            '::makeGroup' => 'Make this object a group',
            '::makeGroupMe' => 'Make this object a group for me too',
            '::unmakeGroup' => 'Unmake this object a group',
            '::useAsGroupOpened' => 'Use as group opened',
            '::useAsGroupClosed' => 'Use as group closed',
            '::refuseGroup' => 'Refuse this object as group',
            '::removeFromGroup' => 'Remove from group',
            '::isGroup' => 'is a group.',
            '::isGroupToOther' => 'is a group of',
            '::isNotGroup' => 'is not a group.',
            '::thisIsGroup' => 'This is a group.',
            '::thisIsNotGroup' => 'This is not a group.',
            '::confirm' => 'Confirm',
        ],
        'es-co' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module.',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups.',
            '::groups' => 'The groups',
            '::myGroups' => 'My groups',
            '::allGroups' => 'All groups',
            '::seeAsGroup' => 'See as group',
            '::seenFromOthers' => 'Seen from others entities',
            '::otherGroups' => 'Groups of other entities',
            '::createGroup' => 'Create a group',
            '::createGroupClosed' => 'Create a closed group',
            '::createGroupObfuscated' => 'Create an obfuscated group',
            '::addMarkedObjects' => 'Add marked objects',
            '::deleteGroup' => 'Delete group',
            '::createTheGroup' => 'Create the group',
            '::nom' => 'Name',
            '::createGroupOK' => 'The group have been created.',
            '::notOKCreateGroup' => 'The group have not been created! %s',
            '::noGroup' => 'No group.',
            '::noGroupMember' => 'No member.',
            '::makeGroup' => 'Make this object a group',
            '::makeGroupMe' => 'Make this object a group for me too',
            '::unmakeGroup' => 'Unmake this object a group',
            '::useAsGroupOpened' => 'Use as group opened',
            '::useAsGroupClosed' => 'Use as group closed',
            '::refuseGroup' => 'Refuse this object as group',
            '::removeFromGroup' => 'Remove from group',
            '::isGroup' => 'is a group.',
            '::isGroupToOther' => 'is a group of',
            '::isNotGroup' => 'is not a group.',
            '::thisIsGroup' => 'This is a group.',
            '::thisIsNotGroup' => 'This is not a group.',
            '::confirm' => 'Confirm',
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
            '425d033815bd76844fc5100ad0ccb2c3d6cd981315794b95210d6c6ec8da22e8faaf.none.272', // 0 : Icône des groupes.
            '819babe3072d50f126a90c982722568a7ce2ddd2b294235f40679f9d220e8a0a.sha2.256',     // 1 : Créer un groupe.
            'a269514d2b940d8269993a6f0138f38bbb86e5ac387dcfe7b810bf871002edf3.sha2.256',     // 2 : Ajouter objets marqués.
    );
    const MODULE_APP_ICON_LIST = array('425d033815bd76844fc5100ad0ccb2c3d6cd981315794b95210d6c6ec8da22e8faaf.none.272');
    const RESTRICTED_TYPE = 'Entity';

    protected function _filterGroupByType(string $gid): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return true; // FIXME for group of entities
    }
}