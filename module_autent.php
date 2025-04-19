<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Library\DisplayBlankLine;
use Nebule\Library\DisplayInformation;
use Nebule\Library\DisplayItem;
use Nebule\Library\DisplayItemIconMessage;
use Nebule\Library\DisplayList;
use Nebule\Library\DisplayObject;
use Nebule\Library\DisplayQuery;
use Nebule\Library\Displays;
use Nebule\Library\DisplaySecurity;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Metrology;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * Ce module permet gérer les objets.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleAutent extends \Nebule\Library\Modules {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::autent:module:objects:ModuleName';
    const MODULE_MENU_NAME = '::autent:module:objects:MenuName';
    const MODULE_COMMAND_NAME = 'autent';
    const MODULE_DEFAULT_VIEW = 'login';
    const MODULE_DESCRIPTION = '::autent:module:objects:ModuleDescription';
    const MODULE_VERSION = '020250419';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2024-2025';
    const MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    const MODULE_HELP = '::autent:module:objects:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('desc', 'login', 'logout');
    const MODULE_REGISTERED_ICONS = array(
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 0 : Objet.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 1 : Objet.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 2 : Objet.
    );
    const MODULE_APP_TITLE_LIST = array();
    const MODULE_APP_ICON_LIST = array();
    const MODULE_APP_DESC_LIST = array();
    const MODULE_APP_VIEW_LIST = array();

    private string $_comebackAppId = '';

    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuBlog':
                $instance = $this->_cacheInstance->newNode($object);
                $id = $instance->getID();

                break;
        }
        return $hookArray;
    }


    public function displayModule(): void {
        if (filter_has_var(INPUT_GET, References::COMMAND_APPLICATION_BACK)) {
            $this->_comebackAppId = trim(filter_input(INPUT_GET, References::COMMAND_APPLICATION_BACK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $this->_metrologyInstance->addLog('input ' . References::COMMAND_APPLICATION_BACK . ' ask application to come back rid=' . $this->_comebackAppId, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'dade09c2');
        }
        else
            $this->_comebackAppId = '1';
        if ($this->_comebackAppId == '')
            $this->_comebackAppId = '1';

        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayLogin();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayLogout();
                break;
            default:
                $this->_displayInfo();
                break;
        }
    }

    public function displayModuleInline(): void {
        // Nothing
    }


    /**
     * Display view to describe entity state.
     */
    private function _displayInfo(): void {
        $this->_metrologyInstance->addLog('display desc ' . $this->_applicationInstance->getCurrentObjectInstance()->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '1f00a8b1');

        $title = new DisplayTitle($this->_applicationInstance);
        $title->setTitle('::::INFO');
        $title->display();

        $this->_unlocked = $this->_entitiesInstance->getCurrentEntityIsUnlocked();

        if (! $this->_configurationInstance->getOptionAsBoolean('permitAuthenticateEntity')
            || $this->_applicationInstance->getCheckSecurityAll() != 'OK'
        ) {
            $urlLink = '/?f';
            $title = '::::err_NotPermit';
            $type = DisplayItemIconMessage::TYPE_ERROR;
        } elseif ($this->_unlocked) {
            $urlLink = '/?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_comebackAppId
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '='. $this::MODULE_REGISTERED_VIEWS[2];
            $title = ':::logout';
            $type = DisplayItemIconMessage::TYPE_PLAY;
        } else {
            $urlLink = '/?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_comebackAppId
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '='. $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . References::COMMAND_SWITCH_TO_ENTITY;
            $title = ':::login';
            $type = DisplayItemIconMessage::TYPE_PLAY;
        }

        $instanceList = new DisplayList($this->_applicationInstance);
        $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
        $this->_displayAddSecurity($instanceList, false);
        $this->_addBlankLine($instanceList);
        $this->_displayAddEID($instanceList, $this->_applicationInstance->getCurrentObjectInstance(), false);
        $this->_displayAddButton($instanceList, $title, $type, $urlLink, ':::connexion');
        $this->_addBlankLine($instanceList);
        $this->_displayAddButton($instanceList, ':::return', DisplayItemIconMessage::TYPE_BACK, '/?'. References::COMMAND_SWITCH_APPLICATION . '=' . $this->_comebackAppId);
        $instanceList->setOnePerLine();
        $instanceList->display();
    }

    /**
     * Display view to unlocking entity.
     */
    private function _displayLogin(): void {
        $this->_metrologyInstance->addLog('Display login ' . $this->_applicationInstance->getCurrentObjectInstance()->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '61a2b0dd');

        $title = new DisplayTitle($this->_applicationInstance);
        $title->setTitle(':::login');
        $title->display();

        $instanceList = new DisplayList($this->_applicationInstance);
        $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
        $this->_displayAddSecurity($instanceList, false);
        $this->_addBlankLine($instanceList);
        $this->_displayAddEID($instanceList, $this->_applicationInstance->getCurrentObjectInstance(), false);
        $this->_displayAddEID($instanceList, $this->_entitiesInstance->getCurrentEntityPrivateKeyInstance(), true);
        if ($this->_configurationInstance->getOptionAsBoolean('permitAuthenticateEntity')
            && $this->_applicationInstance->getCheckSecurityAll() == 'OK')
            $this->_displayAddButtonQuery($instanceList,
                '::::Password',
                DisplayQuery::QUERY_PASSWORD,
                '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_comebackAppId
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getServerEntityID()
                . '&' . References::COMMAND_SWITCH_TO_ENTITY);
        else
            $this->_displayAddButton($instanceList, '::::err_NotPermit', DisplayItemIconMessage::TYPE_ERROR, '');
        $this->_addBlankLine($instanceList);
        $this->_displayAddButton($instanceList,
            ':::return',
            DisplayItemIconMessage::TYPE_BACK,
            '/?'. References::COMMAND_SWITCH_APPLICATION . '=' . $this->_comebackAppId);
        $instanceList->setOnePerLine();
        $instanceList->display();
    }

    /**
     * Display view to locking entity.
     */
    private function _displayLogout(): void {
        $this->_metrologyInstance->addLog('Display logout ' . $this->_applicationInstance->getCurrentObjectInstance()->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '833de289');

        $title = new DisplayTitle($this->_applicationInstance);
        $title->setTitle(':::logout');
        $title->display();

        $instanceList = new DisplayList($this->_applicationInstance);
        $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
        $this->_displayAddSecurity($instanceList, false);
        $this->_addBlankLine($instanceList);
        $this->_displayAddEID($instanceList, $this->_applicationInstance->getCurrentObjectInstance(), false);
        $this->_displayAddButton($instanceList, ':::logout', DisplayItemIconMessage::TYPE_PLAY, '/?f');
        $this->_addBlankLine($instanceList);
        $this->_displayAddButton($instanceList, ':::return', DisplayItemIconMessage::TYPE_BACK, '/?'. References::COMMAND_SWITCH_APPLICATION . '=' . $this->_comebackAppId);
        $instanceList->setOnePerLine();
        $instanceList->display();
    }

    private function _displayAddEID(DisplayList $instanceList, Node $eid, bool $isKey): void {
        $instance = new DisplayObject($this->_applicationInstance);
        $instance->setNID($eid);
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
        $instance->setEnableStatus(true);
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        $instance->setEnableLink(true);
        $instance->setRatio(DisplayItem::RATIO_SHORT);
        $instance->setStatus('');
        if ($isKey) {
            $instance->setEnableFlagUnlocked(false);
            $instance->setName($this->_translateInstance->getTranslate('::privateKey'));
            $instance->setType(References::REFERENCE_OBJECT_TEXT);
            $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['lo']); // FIXME
            $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
            $instance->setIcon($instanceIcon2);
        }
        else
        {
            $instance->setEnableFlagUnlocked(true);
            $instance->setFlagUnlocked($this->_unlocked);
        }
        $instanceList->addItem($instance);
    }

    private function _displayAddSecurity(DisplayList $instanceList, bool $displayFull): void {
        $instance = new DisplaySecurity($this->_applicationInstance);
        $instance->setDisplayOK(!$displayFull);
        $instance->setDisplayFull($displayFull);
        $instanceList->addItem($instance);
    }

    private function _displayAddButton(DisplayList $instanceList, string $message, string $type, string $link, string $title = ''): void {
        $instance = new DisplayInformation($this->_applicationInstance);
        $instance->setMessage($message);
        $instance->setType($type);
        $instance->setRatio(DisplayItem::RATIO_SHORT);
        $instance->setLink($link);
        if ($title != '')
            $instance->setIconText($title);
        $instanceList->addItem($instance);
    }

    private function _displayAddButtonQuery(DisplayList $instanceList, string $message, string $type, string $link): void {
        $instance = new DisplayQuery($this->_applicationInstance);
        $instance->setMessage($message);
        $instance->setType($type);
        $instance->setLink($link);
        $instance->setHiddenName('id');
        $instance->setHiddenValue($this->_entitiesInstance->getServerEntityID());
        $instanceList->addItem($instance);
    }

    private function _addBlankLine(DisplayList $instanceList): void
    {
        $instance = new \Nebule\Library\DisplayBlankLine($this->_applicationInstance);
        $instanceList->addItem($instance);
    }

    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::privateKey' => 'Clé privée',
        ],
        'en-en' => [
            '::privateKey' => 'Private key',
        ],
        'es-co' => [
            '::privateKey' => 'Private key',
        ],
    ];
}
