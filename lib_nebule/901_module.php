<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe Modules
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Modules extends Functions implements ModuleInterface
{
    const MODULE_TYPE = 'Model'; // Model | Application | Traduction
    const MODULE_NAME = 'None';
    const MODULE_MENU_NAME = 'None';
    const MODULE_COMMAND_NAME = 'none';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = 'Description';
    const MODULE_VERSION = '020250111';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2025';
    const MODULE_LOGO = '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c.sha2.256';
    const MODULE_HELP = 'Help';
    const MODULE_INTERFACE = '3.0';
    const MODULE_REGISTERED_VIEWS = array('disp');
    const MODULE_REGISTERED_ICONS = array();
    const MODULE_APP_TITLE_LIST = array();
    const MODULE_APP_ICON_LIST = array();
    const MODULE_APP_DESC_LIST = array();
    const MODULE_APP_VIEW_LIST = array();

    const DEFAULT_COMMAND_ACTION_DISPLAY_MODULE = 'name';

    protected ?Applications $_applicationInstance = null;
    protected ?Displays $_displayInstance = null;
    protected ?Translates $_translateInstance = null;
    protected bool $_unlocked = false;

    public function __toString(): string
    {
        return $this::MODULE_NAME;
    }

    protected function _initialisation(): void
    {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
    }

    public function getClassName(): string
    {
        return static::class;
    }

    /**
     * Add functionalities by hooks on menu (by module) and nodes (by type).
     *  - On menu:
     *    - selfMenu: inside menu on current module, in white;
     *    - selfMenu<View>: inside menu on current module only on 'View', in white;
     *    - <Module>SelfMenu: inside menu on another module with the name 'Module', in white;
     *    - menu: inside menu on every module, on the end of list, in dark;
     *  - On node:
     *    - typeMenu<Type>: inside menu on node with the 'Type', in white;
     *    - ... FIXME
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array
    {
        return array();
    }


    /**
     * Part from this module to display on browser.
     *
     * @return void
     */
    public function displayModule(): void
    {
    }

    /**
     * Inline part from this module to display on browser, called by primary page on displayModule().
     *
     * @return void
     */
    public function displayModuleInline(): void
    {
    }

    private ?string $_commandActionDisplayModuleCache = null;

    public function getExtractCommandDisplayModule(): string
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $return = '';

        if ($this->_commandActionDisplayModuleCache != null)
            return $this->_commandActionDisplayModuleCache;

        if ($this->_displayInstance->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]) {
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE, FILTER_SANITIZE_STRING));

            if ($arg != '')
                $return = $arg;

            unset($arg);
        }

        $this->_commandActionDisplayModuleCache = $return;

        return $return;
    }

    public function getCSS(): void
    {
        echo '<style type="text/css">' . "\n";
        $this->headerStyle();
        echo '</style>' . "\n";
    }

    /**
     * Part of CSS from this module to display on browser.
     *
     * @return void
     */
    public function headerStyle(): void
    {
    }

    /**
     * Part of JS script from this module to display on browser.
     *
     * @return void
     */
    public function headerScript(): void
    {
    }

    /**
     * Part of actions from this module to run before display.
     *
     * @return void
     */
    public function actions(): void
    {
    }

    public function getTranslate(string $text, string $lang = ''): string
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $result = $text;
        if ($this->_translateInstance === null)
            $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();

        if ($lang == '')
            $lang = $this->_translateInstance->getCurrentLanguage();

        if (isset(self::TRANSLATE_TABLE[$lang][$text]))
            $result = self::TRANSLATE_TABLE[$lang][$text];
        return $result;
    }


    /**
     * Créer un lien.
     *
     * @param string  $signer
     * @param string  $date
     * @param string  $action
     * @param string  $source
     * @param string  $target
     * @param string  $meta
     * @param boolean $obfuscate
     * @return void
     */
    protected function _createLink_DEPRECATED(string $signer, string $date, string $action, string $source, string $target, string $meta, bool $obfuscate = false): void
    {
        /*$link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign($signer);
        if ($obfuscate)
            $link->obfuscate();
        $newLink->write();*/
    }

    protected function _displaySimpleTitle(string $title, string $icon = ''): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle($title);
        if ($icon != '')
            $instance->setIconRID($icon);
        $instance->display();
    }

    protected function _displayNotImplemented(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage('::notImplemented');
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_WARN);
        $instance->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->display();
    }

    protected function _displayNotSupported(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage('::notSupported');
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_WARN);
        $instance->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->display();
    }

    const TRANSLATE_TABLE = [
            'fr-fr' => [
                    '::nebule:modules::ModuleName' => 'Module des modules',
                    '::nebule:modules::MenuName' => 'Modules',
                    '::nebule:modules::ModuleDescription' => 'Module de gestion des modules.',
                    '::nebule:modules::ModuleHelp' => 'Cette application permet de voir les modules détectés par sylabe.',
                    '::nebule:modules::AppTitle1' => 'Modules',
                    '::nebule:modules::AppDesc1' => 'Module de gestion des modules.',
            ],
            'en-en' => [
                    '::nebule:modules::ModuleName' => 'Module of modules',
                    '::nebule:modules::MenuName' => 'Modules',
                    '::nebule:modules::ModuleDescription' => 'Module to manage modules.',
                    '::nebule:modules::ModuleHelp' => 'This application permit to see modules detected by sylabe.',
                    '::nebule:modules::AppTitle1' => 'Modules',
                    '::nebule:modules::AppDesc1' => 'Manage modules.',
            ],
            'es-co' => [
                    '::nebule:modules::ModuleName' => 'Module of modules',
                    '::nebule:modules::MenuName' => 'Modules',
                    '::nebule:modules::ModuleDescription' => 'Module to manage modules.',
                    '::nebule:modules::ModuleHelp' => 'This application permit to see modules detected by sylabe.',
                    '::nebule:modules::AppDesc1' => 'Manage modules.',
                    '::nebule:modules::AppTitle1' => 'Modules',
            ],
    ];
}
