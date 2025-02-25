<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * This module can manage messengers. Messages can be attached to groups (or objects) in a related conversation.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleMessages extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::module:messages:ModuleName';
    const MODULE_MENU_NAME = '::module:messages:MenuName';
    const MODULE_COMMAND_NAME = 'msg';
    const MODULE_DEFAULT_VIEW = 'groups';
    const MODULE_DESCRIPTION = '::module:messages:ModuleDescription';
    const MODULE_VERSION = '020250225';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2025-2025';
    const MODULE_LOGO = '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256';
    const MODULE_HELP = '::module:messages:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('groups', 'list');
    const MODULE_REGISTERED_ICONS = array(
        '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256',    // 0 : World.
    );
    const MODULE_APP_TITLE_LIST = array('::module:messages:AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::module:messages:AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');



    protected function _initialisation(): void {}



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array
    {
        $hookArray = array();

        switch ($hookName) {
            case 'menu':
                $hookArray[0]['name'] = '::module:messages:AppTitle1';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::module:messages:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::MODULE_DEFAULT_VIEW;
                break;
        }

        return $hookArray;
    }



    public function displayModule(): void
    {
        //switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
        //    case $this::MODULE_REGISTERED_VIEWS[0]:
        //        $this->_displayLangs();
        //        break;
        //    default:
        $this->_displayList();
        //        break;
        //}
    }

    public function displayModuleInline(): void
    {
        //switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
        //    case $this::MODULE_REGISTERED_VIEWS[0]:
        $this->_display_InlineList();
        //        break;
        //}
    }



    private function _displayList(): void
    {
        $this->_displaySimpleTitle('::module:messages:display:Current', $this::MODULE_LOGO);

        $this->_displayNotImplemented(); // TODO

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('list');
    }

    /**
     * Affiche les groupes de l'entité en cours de visualisation, en ligne.
     *
     * @return void
     */
    private function _display_InlineList(): void
    {
        $this->_displaySimpleTitle('::module:messages:display:List', $this::MODULE_LOGO);

        $this->_displayNotImplemented(); // TODO
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::module:messages:ModuleName' => 'Messages module',
            '::module:messages:MenuName' => 'Messages',
            '::module:messages:ModuleDescription' => 'Messages management module.',
            '::module:messages:ModuleHelp' => 'This module permit to see and manage messages.',
            '::module:messages:AppTitle1' => 'Messages',
            '::module:messages:AppDesc1' => 'Manage messages.',
            '::module:messages:display:Current' => 'Selected messages',
            '::module:messages:display:List' => 'List of messagess',
        ],
        'en-en' => [
            '::module:messages:ModuleName' => 'Messages module',
            '::module:messages:MenuName' => 'Messages',
            '::module:messages:ModuleDescription' => 'Messages management module.',
            '::module:messages:ModuleHelp' => 'This module permit to see and manage messages.',
            '::module:messages:AppTitle1' => 'Messages',
            '::module:messages:AppDesc1' => 'Manage messages.',
            '::module:messages:display:Current' => 'Selected messages',
            '::module:messages:display:List' => 'List of messagess',
        ],
        'es-co' => [
            '::module:messages:ModuleName' => 'Messages module',
            '::module:messages:MenuName' => 'Messages',
            '::module:messages:ModuleDescription' => 'Messages management module.',
            '::module:messages:ModuleHelp' => 'This module permit to see and manage messages.',
            '::module:messages:AppTitle1' => 'Messages',
            '::module:messages:AppDesc1' => 'Manage messages.',
            '::module:messages:display:Current' => 'Selected messages',
            '::module:messages:display:List' => 'List of messagess',
        ],
    ];
}
