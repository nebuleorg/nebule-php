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
 * Ce module permet de gérer les languages.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleLang extends Module
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'lang';
    const MODULE_DEFAULT_VIEW = 'list';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260109';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2026';
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



    public function getHookList(string $hookName, ?\Nebule\Library\Node $instance = null):array
    {
        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuLang':
                $hookArray[] = array(
                    'name' => '::AppTitle1',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '::AppDesc1',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . self::MODULE_DEFAULT_VIEW
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
        }
        return $hookArray;
    }



    public function displayModule(): void { $this->_displayLanguages(); }

    public function displayModuleInline(): void { $this->_display_InlineLanguages(); }



    private function _displayLanguages(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::display:List', $this::MODULE_LOGO);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('langs');
    }

    private function _display_InlineLanguages(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $list = $this->_applicationInstance->getApplicationModulesInstance()->getModulesTranslateListName();
        foreach ($list as $moduleName) {
            try {
                $lang = $moduleName::MODULE_LANGUAGE;
                if ($lang != '' && strlen($lang) != 5 && substr($lang, 2, 1) != '-')
                    continue;
                $name = $moduleName::TRANSLATE_TABLE[$lang][$moduleName::MODULE_NAME];
                if (!is_string($name) || $name == '')
                    continue;
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                if ($lang == $this->_translateInstance->getCurrentLanguage())
                    $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_OK);
                else
                    $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_MESSAGE);
                $instanceIcon = $this->_cacheInstance->newNodeByType($moduleName::MODULE_LOGO);
                if ($instanceIcon == '0')
                    continue;
                $instance->setIcon($instanceIcon);
                $instance->setIconText($lang);
                $instance->setMessage($name);
                if ($lang != $this->_translateInstance->getCurrentLanguage()) {
                    $instance->setLinkEnable(true);
                    $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . Displays::COMMAND_DISPLAY_LANG . '=' . $lang
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    );
                }
                $instanceList->addItem($instance);
            } catch (\Throwable $e) {
                $this->_metrologyInstance->addLog('display language error (' . $e->getCode() . ') : ' . $e->getFile() . '(' . $e->getLine() . ') : ' . $e->getMessage() . "\n" . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c0ae4709');
            }
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setOnePerLine(true);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des langues',
            '::MenuName' => 'Langages',
            '::ModuleDescription' => 'Module de gestion des langues',
            '::ModuleHelp' => 'Ce module permet de sélectionner une langue pour les applications.',
            '::AppTitle1' => 'Langues',
            '::AppDesc1' => 'Gestion des langues',
            '::display:Current' => 'Language sélectionné',
            '::display:List' => 'Liste des langues',
        ],
        'en-en' => [
            '::ModuleName' => 'Languages module',
            '::MenuName' => 'Languages',
            '::ModuleDescription' => 'Languages management module',
            '::ModuleHelp' => 'This module permit to manage and change languages.',
            '::AppTitle1' => 'Languages',
            '::AppDesc1' => 'Manage languages',
            '::display:Current' => 'Selected language',
            '::display:List' => 'List of languages',
        ],
        'es-co' => [
            '::ModuleName' => 'Languages module',
            '::MenuName' => 'Languages',
            '::ModuleDescription' => 'Languages management module.',
            '::ModuleHelp' => 'This module permit to manage and change  languages.',
            '::AppTitle1' => 'Languages',
            '::AppDesc1' => 'Manage languages',
            '::display:Current' => 'Selected language',
            '::display:List' => 'List of languages',
        ],
    ];
}
