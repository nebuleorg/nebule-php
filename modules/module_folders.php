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
    const MODULE_VERSION = '020260110';
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

    const RESTRICTED_TYPE = 'Folders';
    const RESTRICTED_CONTEXT = '2afeddf82c8f4171fc67b9073ba5be456abb2f4da7720bb6f0c903fb0b0a4231f7e3.none.272';
    const COMMAND_SELECT_ROOT = 'root';
    const COMMAND_SELECT_ITEM = 'root';
    const COMMAND_SELECT_FOLDER = 'folder';
    const COMMAND_ACTION_GET_FLD_NID = 'actiongetnid';
    const COMMAND_ACTION_GET_FLD_URL = 'actiongeturl';

    protected ?\Nebule\Library\Node $_instanceCurrentRoot = null;
    protected ?\Nebule\Library\Node $_instanceCurrentFolder = null;
    protected array $_listFolders = array();



    protected function _initialisation(): void {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_socialClass = $this->getFilterInput(Displays::COMMAND_SOCIAL, FILTER_FLAG_ENCODE_LOW);
        $this->_getCurrentItem(self::COMMAND_SELECT_ROOT, 'Root', $this->_instanceCurrentRoot);
        if (! is_a($this->_instanceCurrentRoot, 'Nebule\Library\Node') || $this->_instanceCurrentRoot->getID() == '0')
            $this->_instanceCurrentRoot = null;
        if (is_a($this->_instanceCurrentRoot, 'Nebule\Library\Node')) {
            $this->_getCurrentItem(self::COMMAND_SELECT_FOLDER, 'Folder', $this->_instanceCurrentFolder, $this->_instanceCurrentRoot->getID());
            if (!is_a($this->_instanceCurrentFolder, 'Nebule\Library\Node') || $this->_instanceCurrentFolder->getID() == '0')
                $this->_instanceCurrentFolder = $this->_instanceCurrentRoot;
        }
        $this->_getCurrentItemFounders($this->_instanceCurrentRoot);
        $this->_getCurrentItemSocialList($this->_instanceCurrentRoot);
        $this->_instanceCurrentItem = $this->_instanceCurrentFolder;
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $instance = null):array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->_applicationInstance->getCurrentObjectID();
        if ($instance !== null)
            $nid = $instance->getID();
        $hookArray = $this->getCommonHookList($hookName, $nid, 'Folders');

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuFolders':
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[] = array(
                        'name' => '::rights',
                        'icon' => Displays::DEFAULT_ICON_IMODIFY,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                            . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                    $hookArray[] = array(
                        'name' => '::modify',
                        'icon' => Displays::DEFAULT_ICON_IMODIFY,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                            . '&' . self::COMMAND_SELECT_ROOT . '=' . $this->_instanceCurrentRoot->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[7]) {
                    $hookArray[] = array(
                        'name' => '::remove',
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
                    'name' => '::myFolders',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . Displays::COMMAND_SOCIAL . '=myself'
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $nid
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
                $this->_displayItem('Folder');
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayItemCreateForm('Folder');
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModifyItem('Folder');
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayRemoveItem('Folder');
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGetItem('Folder', $this::COMMAND_ACTION_GET_FLD_NID, $this::COMMAND_ACTION_GET_FLD_URL);
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displaySynchroItem('Folder', $this::COMMAND_ACTION_GET_FLD_NID);
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_displayRightsItem('Folder');
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
            default:
                $this->_displayListItems('Folder', 'Folders');
                break;
        }
    }

    public function displayModuleInline(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyItems('Folders');
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineRoot();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlineRightsItem('Folder');
                break;
        }
    }



    private function _display_InlineRoot(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!is_a($this->_instanceCurrentItem, 'Nebule\Library\Group')) {
            $this->_displayNotSupported();
            return;
        }

        if ($this->_instanceCurrentItem->getMarkClosed())
            $memberLinks = $this->_instanceCurrentItem->getListTypedMembersLinks(References::REFERENCE_NEBULE_OBJET_GROUPE, 'myself');
        else
            $memberLinks = $this->_instanceCurrentItem->getListTypedMembersLinks(References::REFERENCE_NEBULE_OBJET_GROUPE, 'all');

        $list = array();
        foreach ($memberLinks as $link)
            $list[$link->getParsed()['bl/rl/nid1']] = $link->getSignersEID();

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
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



    protected function _displayOptions(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented();
    }

    protected function _displayAddFolder(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented();
    }

    protected function _displayAddFile(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented();
    }



    // Called by Modules::_display_InlineMyItems()
    protected function _displayListOfItems(array $links, string $socialClass = 'all', string $hookName = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $foldersNID = array();
        $foldersSigners = array();
        foreach ($links as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            if (!$this->_filterItemByType($nid))
                continue;
            $signers = $link->getSignersEID(); // FIXME get all signers
            $foldersNID[$nid] = $nid;
            foreach ($signers as $signer) {
                $foldersSigners[$nid][$signer] = $signer;
            }
        }
        $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[0]);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($foldersNID as $nid) {
            $instanceFolder = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_GROUP);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial($socialClass);
            $instance->setNID($instanceFolder);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_ROOT . '=' . $nid
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            //$instance->setName($instanceFolder->getName('all'));
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableFlagUnlocked(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            if (isset($conversationsSigners[$nid]) && sizeof($conversationsSigners[$nid]) > 0) {
                $instance->setEnableRefs(true);
                $instance->setRefs($conversationsSigners[$nid]);
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

    protected function _filterItemByType(string $nid): bool { return true; } // FIXME maybe unused



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des dossiers',
            '::MenuName' => 'Dossiers',
            '::ModuleDescription' => 'Module de gestion des dossiers',
            '::ModuleHelp' => 'Ce module permet de voir et de gérer les dossiers.',
            '::AppTitle1' => 'Dossiers',
            '::AppDesc1' => 'Gestion des dossiers',
            '::myFolders' => 'Mes dossiers',
            '::allFolders' => 'Tous les dossiers',
            '::otherFolders' => 'Les dossiers des autres entités',
            '::listFolders' => 'Liste des dossiers',
            '::createClosedFolder' => 'Créer un dossier fermé',
            '::createObfuscatedFolder' => 'Créer un dossier dissimulé',
            '::addMarkedObjects' => 'Ajouter les objets marqués',
            '::addToFolder' => 'Ajouter au dossier',
            '::addMember' => 'Ajouter un membre',
            '::deleteFolder' => 'Supprimer le dossier',
            '::createFolder' => 'Créer un dossier',
            '::createFolderOK' => 'Le dossier a été créé',
            '::createFolderNOK' => "Le dossier n'a pas été créé ! %s",
        ],
        'en-en' => [
            '::ModuleName' => 'Folders module',
            '::MenuName' => 'Folders',
            '::ModuleDescription' => 'Folders management module',
            '::ModuleHelp' => 'This module permit to see and manage folders.',
            '::AppTitle1' => 'Folders',
            '::AppDesc1' => 'Manage folders',
            '::myFolders' => 'My folders',
            '::allFolders' => 'All folders',
            '::otherFolders' => 'Folders of other entities',
            '::listFolders' => 'List of folders',
            '::createClosedFolder' => 'Create a closed folder',
            '::createObfuscatedFolder' => 'Create an obfuscated folder',
            '::addMarkedObjects' => 'Add marked objects',
            '::addToFolder' => 'Add to folder',
            '::addMember' => 'Add a member',
            '::deleteFolder' => 'Delete folder',
            '::createFolder' => 'Create a folder',
            '::createFolderOK' => 'The folder have been created',
            '::createFolderNOK' => 'The folder have not been created! %s',
        ],
        'es-co' => [
            '::ModuleName' => 'Folders module',
            '::MenuName' => 'Folders',
            '::ModuleDescription' => 'Folders management module',
            '::ModuleHelp' => 'This module permit to see and manage folders.',
            '::AppTitle1' => 'Folders',
            '::AppDesc1' => 'Manage folders',
            '::myFolders' => 'My folders',
            '::allFolders' => 'All folders',
            '::otherFolders' => 'Folders of other entities',
            '::listFolders' => 'List of folders',
            '::createClosedFolder' => 'Create a closed folder',
            '::createObfuscatedFolder' => 'Create an obfuscated folder',
            '::addMarkedObjects' => 'Add marked objects',
            '::addToFolder' => 'Add to folder',
            '::addMember' => 'Add a member',
            '::deleteFolder' => 'Delete folder',
            '::createFolder' => 'Create a folder',
            '::createFolderOK' => 'The folder have been created',
            '::createFolderNOK' => 'The folder have not been created! %s',
        ],
    ];
}
