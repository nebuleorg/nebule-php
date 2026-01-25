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
    const COMMAND_SELECT_GALLERY = 'glr';
    const COMMAND_SELECT_ITEM = 'glr';
    const COMMAND_ACTION_GET_GLR_NID = 'actiongetnid';
    const COMMAND_ACTION_GET_GLR_URL = 'actiongeturl';

    protected ?\Nebule\Library\Group $_instanceCurrentGallery = null;



    protected function _initialisation(): void {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_socialClass = $this->getFilterInput(Displays::COMMAND_SOCIAL, FILTER_FLAG_ENCODE_LOW);
        $this->_getCurrentItem(self::COMMAND_SELECT_GALLERY, 'Gallery', $this->_instanceCurrentGallery);
        if (! is_a($this->_instanceCurrentGallery, 'Nebule\Library\Node') || $this->_instanceCurrentGallery->getID() == '0')
            $this->_instanceCurrentGallery = null;
        $this->_getCurrentItemFounders($this->_instanceCurrentGallery);
        $this->_getCurrentItemSocialList($this->_instanceCurrentGallery);
        $this->_instanceCurrentItem = $this->_instanceCurrentGallery;
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
                    if ($this->_unlocked) {
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
                    && $this->_unlocked) {
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

            /*case 'typeMenuGallery':
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

        }

        return $hookArray;
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



    private function _display_InlineGallery(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!is_a($this->_instanceCurrentItem, 'Nebule\Library\Group')) {
            $this->_displayNotSupported();
            return;
        }

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        if ($this->_instanceCurrentItem->getMarkClosed())
            $memberLinks = $this->_instanceCurrentItem->getListTypedMembersLinks(References::REFERENCE_NEBULE_OBJET_GROUPE, 'myself');
        else
            $memberLinks = $this->_instanceCurrentItem->getListTypedMembersLinks(References::REFERENCE_NEBULE_OBJET_GROUPE, 'all');


        $this->_displayNotImplemented(); // TODO
    }

    protected function _displayOptions(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented();
    }



    // Called by Modules::_display_InlineMyItems()
    protected function _displayListOfItems(array $links, string $socialClass = 'all', string $hookName = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $galleriesNID = array();
        $galleriesSigners = array();
        foreach ($links as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            if (!$this->_filterItemByType($nid))
                continue;
            $signers = $link->getSignersEID(); // FIXME get all signers
            $galleriesNID[$nid] = $nid;
            foreach ($signers as $signer) {
                $galleriesSigners[$nid][$signer] = $signer;
            }
        }
        $instanceIcon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[0]);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($galleriesNID as $nid) {
            $instanceGallery = $this->_cacheInstance->newNodeByType($nid, \Nebule\Library\Cache::TYPE_GROUP);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial($socialClass);
            $instance->setNID($instanceGallery);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_GALLERY . '=' . $nid
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            //$instance->setName($instanceGallery->getName('all'));
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
            //$instance->setSelfHookName($hookName);
            $instance->setIcon($instanceIcon);
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty(true);
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
