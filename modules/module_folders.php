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
 * This module can manage objects. Objects can be attached to groups (or objects) in a related folder.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleFolders extends Modules {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'fld';
    const MODULE_DEFAULT_VIEW = 'roots';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260102';
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

    const RESTRICTED_TYPE = 'Folders';
    const RESTRICTED_CONTEXT = '2afeddf82c8f4171fc67b9073ba5be456abb2f4da7720bb6f0c903fb0b0a4231f7e3.none.272';



    protected string $_socialClass = '';



    protected function _initialisation(): void {
        $this->_socialClass = $this->getFilterInput(Displays::COMMAND_SOCIAL, FILTER_FLAG_ENCODE_LOW);
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();
        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuFolders':
                if ($this->_socialClass != 'myself') {
                    $hookArray[] = array(
                        'name' => '::myFolders',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_socialClass != 'notmyself') {
                    $hookArray[] = array(
                        'name' => '::otherFolders',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_socialClass != 'all') {
                    $hookArray[] = array(
                        'name' => '::allFolders',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                break;

            case 'typeMenuEntity':
                $hookArray[] = array(
                    'name' => '::myFolders',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;

        }
        return $hookArray;
    }



    public function getHookFunction(string $hookName, string $item): ?\Nebule\Library\DisplayItemIconMessageSizeable {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        /*switch ($hookName) {
            case 'addMember':
                $node = $this->_cacheInstance->newNode($item);
                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('self');
                $instance->setNID($node);
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
                $instance->setIcon($instanceIcon);
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
            default:*/
                return null;
        //}
    }



    public function displayModule(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayRoot();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayCreateRoot();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModifyRoot();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayDeleteRoot();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGetRoot();
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displaySynchroRoot();
                break;
            default:
                $this->_displayMyRoots();
                break;
        }
    }

    public function displayModuleInline(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyRoots();
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineRoot();
                break;
        }
    }



    private function _displayMyRoots(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $this->_displaySimpleTitle('::createFolder', $this::MODULE_REGISTERED_ICONS[1]);
            $this->_displayRootCreateNew();
        }

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::createFolder');
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[6]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::folder:getExisting');
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

        $message = match ($this->_socialClass) {
            'all' => '::allFolders',
            'notmyself' => '::otherFolders',
            default => '::myFolders',
        };
        $this->_displaySimpleTitle($message, $this::MODULE_LOGO);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('list');
    }

    private function _display_InlineMyRoots(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($this->_socialClass) {
            case 'all':
                $links = $this->_nebuleInstance->getListLinksByType(References::REFERENCE_NEBULE_OBJET_GROUPE, $this::RESTRICTED_CONTEXT, 'all');
                $this->_listOfRoots($links, 'all', 'allFolders');
                break;
            case 'notmyself':
                $links = $this->_nebuleInstance->getListLinksByType(References::REFERENCE_NEBULE_OBJET_GROUPE, $this::RESTRICTED_CONTEXT, 'notmyself');
                $this->_listOfRoots($links, 'notmyself', 'otherFolders');
                break;
            default:
                $links = $this->_nebuleInstance->getListLinksByType(References::REFERENCE_NEBULE_OBJET_GROUPE, $this::RESTRICTED_CONTEXT, 'myself');
                $this->_listOfRoots($links, 'myself', 'myFolders');
        }
    }



    private function _displayRoot(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }

    private function _display_InlineRoot(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    private function _displayCreateRoot(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::createFolder', $this::MODULE_REGISTERED_ICONS[1]);
        $this->_displayRootCreateForm();
        // MyFolders() view displays the result of the creation
    }

    // Copy of ModuleGroups::_displayGroupCreateNew()
    protected function _displayRootCreateNew(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            if (!$this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateError()) {
                $instance->setMessage('::createFolderOK');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_OK);
                $instance->setIconText('::OK');
                $instanceList->addItem($instance);

                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('self');
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
                $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['grpobj']); // FIXME
                $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
                $instance->setIcon($instanceIcon2);
            } else {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::createFolderNOK');
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
    protected function _displayRootCreateForm(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $commonLink = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                . '&' . \Nebule\Library\ActionsGroups::CREATE
                . '&' . \Nebule\Library\ActionsGroups::CREATE_CONTEXT . '=' . $this::RESTRICTED_CONTEXT
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
            $instance->setIconText('::createFolderClosed');
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
            $instance->setIconText('::createFolderObfuscated');
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
        } else {
            $instance = new \Nebule\Library\DisplayNotify($this->_applicationInstance);
            $instance->setMessage('::err_NotPermit');
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
            $instance->display();
        }
    }



    private function _displayModifyRoot(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    private function _displayDeleteRoot(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    private function _displayGetRoot(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    private function _displaySynchroRoot(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }



    // Copy of ModuleGroups::_listOfGroups()
    protected function _listOfRoots(array $links, string $socialClass = 'all', string $hookName = 'notMyFolders'): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $foldersNID = array();
        $foldersSigners = array();
        foreach ($links as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            if (!$this->_filterRootFolderByType($nid))
                continue;
            $signers = $link->getSignersEID(); // FIXME get all signers
            $foldersNID[$nid] = $nid;
            foreach ($signers as $signer) {
                $foldersSigners[$nid][$signer] = $signer;
            }
        }
        $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_LOGO);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($foldersNID as $nid) {
            $instanceFolder = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_GROUP);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial($socialClass);
            $instance->setNID($instanceFolder);
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
            if (isset($foldersSigners[$nid]) && sizeof($foldersSigners[$nid]) > 0) {
                $instance->setEnableRefs(true);
                $instance->setRefs($foldersSigners[$nid]);
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

    protected function _filterRootFolderByType(string $nid): bool { return true; } // FIXME maybe unused



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des dossiers',
            '::MenuName' => 'Dossiers',
            '::ModuleDescription' => 'Module de gestion des dossiers',
            '::ModuleHelp' => 'Ce module permet de voir et de gérer les dossiers.',
            '::AppTitle1' => 'Dossiers',
            '::AppDesc1' => 'Gestion des dossiers',
            '::myFolders' => 'Liste des dossiers',
            '::allFolders' => 'Tous les groupes',
            '::otherFolders' => 'Les groupes des autres entités',
            '::listFolders' => 'Liste des dossiers',
            '::createFolderClosed' => 'Créer un groupe fermé',
            '::createFolderObfuscated' => 'Créer un groupe dissimulé',
            '::addMarkedObjects' => 'Ajouter les objets marqués',
            '::addToFolder' => 'Ajouter au groupe',
            '::addMember' => 'Ajouter un membre',
            '::deleteFolder' => 'Supprimer le groupe',
            '::createFolder' => 'Créer un dossier',
            '::createFolderOK' => 'Le groupe a été créé',
            '::createFolderNOK' => "Le groupe n'a pas été créé ! %s",
        ],
        'en-en' => [
            '::ModuleName' => 'Folders module',
            '::MenuName' => 'Folders',
            '::ModuleDescription' => 'Folders management module',
            '::ModuleHelp' => 'This module permit to see and manage folders.',
            '::AppTitle1' => 'Folders',
            '::AppDesc1' => 'Manage folders',
            '::myFolders' => 'List of folders',
            '::allFolders' => 'All groups',
            '::otherFolders' => 'Folders of other entities',
            '::listFolders' => 'List of folders',
            '::createFolderClosed' => 'Create a closed group',
            '::createFolderObfuscated' => 'Create an obfuscated group',
            '::addMarkedObjects' => 'Add marked objects',
            '::addToFolder' => 'Add to group',
            '::addMember' => 'Add a member',
            '::deleteFolder' => 'Delete group',
            '::createFolder' => 'Create a folder',
            '::createFolderOK' => 'The group have been created',
            '::createFolderNOK' => 'The group have not been created! %s',
        ],
        'es-co' => [
            '::ModuleName' => 'Folders module',
            '::MenuName' => 'Folders',
            '::ModuleDescription' => 'Folders management module',
            '::ModuleHelp' => 'This module permit to see and manage folders.',
            '::AppTitle1' => 'Folders',
            '::AppDesc1' => 'Manage folders',
            '::myFolders' => 'List of folders',
            '::allFolders' => 'All groups',
            '::otherFolders' => 'Folders of other entities',
            '::listFolders' => 'List of folders',
            '::createFolderClosed' => 'Create a closed group',
            '::createFolderObfuscated' => 'Create an obfuscated group',
            '::addMarkedObjects' => 'Add marked objects',
            '::addToFolder' => 'Add to group',
            '::addMember' => 'Add a member',
            '::deleteFolder' => 'Delete group',
            '::createFolder' => 'Create a folder',
            '::createFolderOK' => 'The group have been created',
            '::createFolderNOK' => 'The group have not been created! %s',
        ],
    ];
}
