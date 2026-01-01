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
use Nebule\Library\Modules;
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
class ModuleGalleries extends Modules {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'msg';
    const MODULE_DEFAULT_VIEW = 'galleries';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020251230';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2016-2025';
    const MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
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
    );
    const MODULE_REGISTERED_ICONS = array(
        Displays::DEFAULT_ICON_LO,
        Displays::DEFAULT_ICON_LSTOBJ,
        Displays::DEFAULT_ICON_ADDOBJ,
        Displays::DEFAULT_ICON_IMODIFY,
        Displays::DEFAULT_ICON_LD,
        Displays::DEFAULT_ICON_HELP,
        Displays::DEFAULT_ICON_LL,
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    const RESTRICTED_TYPE = 'Conversation';
    const RESTRICTED_CONTEXT = '';



    protected function _initialisation(): void {}



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array {
        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuGalleries':
                $hookArray[] = array(
                    'name' => '::AppTitle1',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '::AppDesc1',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . self::MODULE_DEFAULT_VIEW
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;
        }

        return $hookArray;
    }



    public function displayModule(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayGallery();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayCreateGallery();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModifyGallery();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayDeleteGallery();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGetGallery();
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displaySynchroGallery();
                break;
            default:
                $this->_displayMyGalleries();
                break;
        }
    }

    public function displayModuleInline(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyGalleries();
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineGallery();
                break;
        }
    }



    private function _displayMyGalleries(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $this->_displaySimpleTitle('::createGroup', $this::MODULE_REGISTERED_ICONS[1]);
            $this->_displayGalleryCreateNew();
        }

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::createConversation');
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[6]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::gallery:getExisting');
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
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
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();

        $this->_displaySimpleTitle('::myConversations', $this::MODULE_LOGO);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('list');
    }

    private function _display_InlineMyGalleries(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_nebuleInstance->getListConversationsLinks('', 'myself'); // FIXME get galleries by links
        $this->_listOfGalleries($links, 'myself', 'myGroups');
    }



    private function _displayGallery(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }

    private function _display_InlineGallery(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    private function _displayCreateGallery(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::createGroup', $this::MODULE_REGISTERED_ICONS[1]);
        $this->_displayGalleryCreateForm();
        // MyGalleries() view displays the result of the creation
    }

    // Copy of ModuleGroups::_displayGroupCreateNew()
    protected function _displayGalleryCreateNew(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            if (!$this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateError()) {
                $instance->setMessage('::createGroupOK');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_OK);
                $instance->setIconText('::OK');
                $instanceList->addItem($instance);

                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('self');
                //$instance->setNID($this->_displayGroupInstance); FIXME
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
                $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['grpobj']); // FIXME
                $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
                $instance->setIcon($instanceIcon2);
            } else {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::createGroupNOK');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setIconText('::ERROR');
            }
            $instanceList->addItem($instance);
            $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
            $instanceList->setOnePerLine();
            $instanceList->display();
        }
        echo '<br />' . "\n";
    }

    // Copy of ModuleGroups::_displayGroupCreateForm()
    protected function _displayGalleryCreateForm(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateConversation')) {
            $commonLink = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                . '&' . \Nebule\Library\ActionsGroups::CREATE
                . '&' . \Nebule\Library\ActionsGroups::CREATE_TYPE_MIME . '=Conversation'
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

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsGroups::CREATE_CLOSED);
            $instance->setIconText('::createGroupClosed');
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
            $instance->setIconText('::createGroupObfuscated');
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
    }



    private function _displayModifyGallery(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    private function _displayDeleteGallery(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    private function _displayGetGallery(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    private function _displaySynchroGallery(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    // Copy of ModuleGroups::_listOfGroups()
    protected function _listOfGalleries(array $links, string $socialClass = 'all', string $hookName = 'notMyConversations'): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $galleriesNID = array();
        $galleriesSigners = array();
        foreach ($links as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            if (!$this->_filterGalleryByType($nid))
                continue;
            $signers = $link->getSignersEID(); // FIXME get all signers
            $galleriesNID[$nid] = $nid;
            foreach ($signers as $signer) {
                $galleriesSigners[$nid][$signer] = $signer;
            }
        }
        $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_LOGO);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($galleriesNID as $nid) {
            $instanceGroup = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_GROUP);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial($socialClass);
            $instance->setNID($instanceGroup);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableFlagUnlocked(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            if (isset($galleriesSigners[$nid]) && sizeof($galleriesSigners[$nid]) > 0) {
                $instance->setEnableRefs(true);
                $instance->setRefs($galleriesSigners[$nid]);
            } else
                $instance->setEnableRefs(false);
            $instance->setSelfHookName($hookName);
            $instance->setIcon($instanceIcon);
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty(true);
        $instanceList->display();
    }

    protected function _filterGalleryByType(string $nid): bool { return true; }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des galeries',
            '::MenuName' => 'Galeries',
            '::ModuleDescription' => 'Module de gestion des galeries',
            '::ModuleHelp' => 'Ce module permet de voir et de gérer les galeries.',
            '::AppTitle1' => 'Galeries',
            '::AppDesc1' => 'Gestion des galeries',
            '::myGalleries' => 'Liste des galeries',
            '::listMessages' => 'Liste des galeries',
        ],
        'en-en' => [
            '::ModuleName' => 'Galleries module',
            '::MenuName' => 'Galleries',
            '::ModuleDescription' => 'Galleries management module',
            '::ModuleHelp' => 'This module permit to see and manage galleries.',
            '::AppTitle1' => 'Galleries',
            '::AppDesc1' => 'Manage galleries',
            '::myGalleries' => 'List of galleries',
            '::listMessages' => 'List of galleries',
        ],
        'es-co' => [
            '::ModuleName' => 'Galleries module',
            '::MenuName' => 'Galleries',
            '::ModuleDescription' => 'Galleries management module',
            '::ModuleHelp' => 'This module permit to see and manage galleries.',
            '::AppTitle1' => 'Galleries',
            '::AppDesc1' => 'Manage galleries',
            '::myGalleries' => 'List of galleries',
            '::listMessages' => 'List of galleries',
        ],
    ];
}
