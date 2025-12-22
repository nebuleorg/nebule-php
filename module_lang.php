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
 * Ce module permet de gérer les languages.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleLang extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'lang';
    const MODULE_DEFAULT_VIEW = 'list';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020251116';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2025-2025';
    const MODULE_LOGO = '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('list');
    const MODULE_REGISTERED_ICONS = array(
        '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256',    // 0 : World.
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');



    protected function _initialisation(): void {}



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array
    {
        $hookArray = array();

        switch ($hookName) {
            case 'menu':
                $hookArray[0]['name'] = '::AppTitle1';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::AppDesc1';
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
                $this->_displayLanguages();
        //        break;
        //}
    }

    public function displayModuleInline(): void
    {
        //switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
        //    case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineLanguages();
        //        break;
        //}
    }



    private function _displayLanguages(): void
    {
        $this->_displaySimpleTitle('::display:Current', $this::MODULE_LOGO);

        $this->_displayNotImplemented(); // TODO

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('langs');
    }

    /**
     * Affiche les groupes de l'entité en cours de visualisation, en ligne.
     *
     * @return void
     */
    private function _display_InlineLanguages(): void
    {
        $this->_displaySimpleTitle('::display:List', $this::MODULE_LOGO);

        $this->_displayNotImplemented(); // TODO

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $list = $this->_applicationInstance->getApplicationModulesInstance()->getModulesTranslateListName();
        foreach ($list as $moduleName) {
            $blogInstance = $moduleName::MODULE_LANGUAGE;
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance); // FIXME
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                . '&' . References::COMMAND_SELECT_LANG . '=' . $moduleName);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            //$instance->setRefs(array($blogSID));
            $instance->setSelfHookName('selfMenuBlogs');
            $instance->setEnableStatus(true);
            /*$instance->setStatus(
                $this->_translateInstance->getTranslate('::pages') . ':' . $this->_getCountBlogPages($blogInstance)
                . ' '
                . $this->_translateInstance->getTranslate('::posts') . ':' . $this->_getCountBlogPosts($blogInstance)
            );*/
            $instanceList->addItem($instance);
        }
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des langues',
            '::MenuName' => 'Langages',
            '::ModuleDescription' => 'Module de gestion des langues.',
            '::ModuleHelp' => "Ce module permet de sélectionner une langue pour les applications.",
            '::AppTitle1' => 'Langues',
            '::AppDesc1' => 'Gestion des langues.',
            '::display:Current' => 'Language sélectionné',
            '::display:List' => 'Liste des langues',
        ],
        'en-en' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module.',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups.',
            '::display:Current' => 'Selected language',
            '::display:List' => 'List of languages',
        ],
        'es-co' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module.',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups.',
            '::display:Current' => 'Selected language',
            '::display:List' => 'List of languages',
        ],
    ];
}
