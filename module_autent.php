<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
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
    protected string $MODULE_TYPE = 'Application';
    protected string $MODULE_NAME = '::sylabe:module:objects:ModuleName';
    protected string $MODULE_MENU_NAME = '::sylabe:module:objects:MenuName';
    protected string $MODULE_COMMAND_NAME = 'autent';
    protected string $MODULE_DEFAULT_VIEW = 'desc';
    protected string $MODULE_DESCRIPTION = '::sylabe:module:objects:ModuleDescription';
    protected string $MODULE_VERSION = '020240829';
    protected string $MODULE_AUTHOR = 'Projet nebule';
    protected string $MODULE_LICENCE = '(c) GLPv3 nebule 2024-2024';
    protected string $MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    protected string $MODULE_HELP = '::sylabe:module:objects:ModuleHelp';
    protected string $MODULE_INTERFACE = '3.0';

    protected array $MODULE_REGISTERED_VIEWS = array('desc', 'unlock', 'logout');
    protected array $MODULE_REGISTERED_ICONS = array(
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 0 : Objet.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 1 : Objet.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 2 : Objet.
    );
    protected array $MODULE_APP_TITLE_LIST = array();
    protected array $MODULE_APP_ICON_LIST = array();
    protected array $MODULE_APP_DESC_LIST = array();
    protected array $MODULE_APP_VIEW_LIST = array();

    private string $_comebackAppId = '';

    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?Node $nid = null): array {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuBlog':
                $instance = $this->_nebuleInstance->newObject($object);
                $id = $instance->getID();

                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     *
     * @return void
     */
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
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayLogin();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayLogout();
                break;
            default:
                $this->_displayInfo();
                break;
        }
    }

    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
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

        if (! $this->_configurationInstance->getOptionAsBoolean('permitAuthenticateEntity')
            || $this->_applicationInstance->getCheckSecurityAll() != 'OK'
        ) {
            $urlLink = '/?f';
            $title = '::::err_NotPermit';
            $type = DisplayItemIconMessage::TYPE_ERROR;
        } elseif ($this->_unlocked) {
            $urlLink = '/?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_comebackAppId
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '='. $this->MODULE_REGISTERED_VIEWS[2]
                . '&' . References::COMMAND_SWITCH_TO_ENTITY;
            $title = ':::logout';
            $type = DisplayItemIconMessage::TYPE_ERROR;
        } else {
            $urlLink = '/?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_comebackAppId
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '='. $this->MODULE_REGISTERED_VIEWS[1]
                . '&' . References::COMMAND_SWITCH_TO_ENTITY;
            $title = ':::login';
            $type = DisplayItemIconMessage::TYPE_PLAY;
        }

        $instanceList = new DisplayList($this->_applicationInstance);
        $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
        $this->_displayAddSecurity($instanceList, false);
        $this->_displayAddEID($instanceList, $this->_applicationInstance->getCurrentObjectInstance(), false);
        $this->_displayAddButton($instanceList, $title, $type, $urlLink);
        $this->_displayAddButton($instanceList, ':::return', DisplayItemIconMessage::TYPE_BACK, '/?'. References::COMMAND_SWITCH_APPLICATION . '=' . $this->_comebackAppId);
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
        $this->_displayAddEID($instanceList, $this->_applicationInstance->getCurrentObjectInstance(), false);
        $this->_displayAddEID($instanceList, $this->_entitiesInstance->getCurrentEntityPrivateKeyInstance(), true);
        if ($this->_configurationInstance->getOptionAsBoolean('permitAuthenticateEntity')
            && $this->_applicationInstance->getCheckSecurityAll() == 'OK')
            $this->_displayAddButtonQuery($instanceList,
                '::::Password',
                DisplayQuery::QUERY_PASSWORD,
                '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_comebackAppId
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getInstanceEntity()
                . '&' . References::COMMAND_SWITCH_TO_ENTITY);
        else
            $this->_displayAddButton($instanceList, '::::err_NotPermit', DisplayItemIconMessage::TYPE_ERROR, '');
        $this->_displayAddButton($instanceList,
            ':::return',
            DisplayItemIconMessage::TYPE_BACK,
            '/?'. References::COMMAND_SWITCH_APPLICATION . '=' . $this->_comebackAppId);
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
        $this->_displayAddEID($instanceList, $this->_applicationInstance->getCurrentObjectInstance(), false);
        $this->_displayAddButton($instanceList, ':::logout', DisplayItemIconMessage::TYPE_PLAY, '/?f');
        $this->_displayAddButton($instanceList, ':::return', DisplayItemIconMessage::TYPE_BACK, '/?'. References::COMMAND_SWITCH_APPLICATION . '=' . $this->_comebackAppId);
        $instanceList->display();
    }

    private function _displayAddEID(DisplayList $instanceList, Node $eid, bool $isKey): void {
        $instanceObject = new DisplayObject($this->_applicationInstance);
        $instanceObject->setNID($eid);
        $instanceObject->setEnableColor(true);
        $instanceObject->setEnableIcon(true);
        $instanceObject->setEnableName(true);
        $instanceObject->setEnableRefs(false);
        $instanceObject->setEnableNID(false);
        $instanceObject->setEnableFlags(true);
        $instanceObject->setEnableFlagProtection(false);
        $instanceObject->setEnableFlagObfuscate(false);
        $instanceObject->setEnableFlagState(true);
        $instanceObject->setEnableFlagEmotions(false);
        $instanceObject->setEnableStatus(true);
        $instanceObject->setEnableContent(false);
        $instanceObject->setEnableJS(false);
        $instanceObject->setEnableLink(true);
        $instanceObject->setRatio(DisplayItem::RATIO_SHORT);
        $instanceObject->setStatus('');
        if ($isKey)
            $instanceObject->setEnableFlagUnlocked(false);
        else
        {
            $instanceObject->setEnableFlagUnlocked(true);
            $instanceObject->setFlagUnlocked($this->_unlocked);
        }
        $instanceList->addItem($instanceObject);
    }

    private function _displayAddSecurity(DisplayList $instanceList, bool $displayFull): void {
        $instance = new DisplaySecurity($this->_applicationInstance);
        $instance->setDisplayOK(!$displayFull);
        $instance->setDisplayFull($displayFull);
        $instanceList->addItem($instance);
    }

    private function _displayAddButton(DisplayList $instanceList, string $message, string $type, string $link): void {
        $instance = new DisplayInformation($this->_applicationInstance);
        $instance->setMessage($message);
        $instance->setType($type);
        $instance->setRatio(DisplayItem::RATIO_SHORT);
        $instance->setLink($link);
        $instanceList->addItem($instance);
    }

    private function _displayAddButtonQuery(DisplayList $instanceList, string $message, string $type, string $link): void {
        $instancePassword = new DisplayQuery($this->_applicationInstance);
        $instancePassword->setMessage($message);
        $instancePassword->setType($type);
        $instancePassword->setLink($link);
        $instancePassword->setHiddenName('id');
        $instancePassword->setHiddenValue($this->_nebuleInstance->getInstanceEntity());
        $instanceList->addItem($instancePassword);
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
        ],
        'en-en' => [
        ],
        'es-co' => [
        ],
    ];
}
