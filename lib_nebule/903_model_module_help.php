<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Application\Neblog\Application;
use Nebule\Application\Neblog\Display;

/**
 * This module is a model for modules manage the help pages and default first vue.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModelModuleHelp extends \Nebule\Library\Modules {
    const MODULE_TYPE = 'Model';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'hlp';
    const MODULE_DEFAULT_VIEW = '1st';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260101';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2026';
    const MODULE_LOGO = '5b3afdf2eb971ce185930ad4accda23942d4495a638c2bdf27ae3e8e4537c1434697.none.272';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('1st', 'hlp', 'about');
    const MODULE_REGISTERED_ICONS = array(
        '5b3afdf2eb971ce185930ad4accda23942d4495a638c2bdf27ae3e8e4537c1434697.none.272', // 0 : icône d'aide.
        '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c.sha2.256',     // 1 : module
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('5b3afdf2eb971ce185930ad4accda23942d4495a638c2bdf27ae3e8e4537c1434697.none.272');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('hlp');



    /**
     * {@inheritDoc}
     * @see Modules::getHookList()
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array {
        $hookArray = array();
        switch ($hookName) {
            case 'menu':
                $hookArray[0]['name'] = '::AppTitle1';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1];
                break;
            case 'selfMenu':
                // Affiche l'aide.
                $hookArray[0]['name'] = '::AppTitle1';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1];

                // A propos.
                $hookArray[2]['name'] = '::About';
                $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                $hookArray[2]['desc'] = '';
                $hookArray[2]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2];
                break;
        }
        return $hookArray;
    }

    /**
     * {@inheritDoc}
     * @see Modules::displayModule()
     */
    public function displayModule(): void {
        $this->_displayHlpHeader();
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case 'hlp':
                $this->_displayHlpHelp();
                break;
            case 'about':
                $this->_displayHlpAbout();
                break;
            default:
                $this->_displayHlpFirst();
                break;
        }
    }

    /**
     * {@inheritDoc}
     * @see Modules::displayModuleInline()
     */
    public function displayModuleInline(): void {}

    /**
     * {@inheritDoc}
     * @see Modules::headerStyle()
     */
    public function headerStyle(): void {
        echo ".moduleHelpText1stI1 { position:absolute; left:50%; top:33%; margin-left:-32px; }\n";
        echo ".moduleHelpText1stI2 { position:absolute; left:50%; top:50%; margin-left:-32px; }\n";
        echo ".moduleHelpText1stT { position:absolute; right:33%; bottom:0; height:54px; min-width:33%; padding:5px; background:rgba(0,0,0,0.1); background-origin:border-box; }\n";
        echo ".moduleHelpText1stT p { position:relative; top:30%; color:#ffffff; text-align:center; font-size:1.5em; }\n";
    }



    private function _displayHlpHeader(): void {}

    private function _displayHlpFirst(): void { $this->_displayNotImplemented(); }

    private function _displayHlpHelp(): void {
        ?>
        <div class="text">
            <p>
                <?php echo $this->_translateInstance->getTranslate('::AideGenerale:Text') ?>
            </p>
        </div>
        <?php
    }

    /**
     * Affichage de la page à propos.
     */
    private function _displayHlpAbout(): void {
        $linkApplicationWebsite = $this->_applicationInstance::APPLICATION_WEBSITE;
        if (!str_contains($this->_applicationInstance::APPLICATION_WEBSITE, '://'))
            $linkApplicationWebsite = 'https://' . $this->_applicationInstance::APPLICATION_WEBSITE;

        $linkBootstrapWebsite = \Nebule\Bootstrap\BOOTSTRAP_WEBSITE;
        if (!str_contains(\Nebule\Bootstrap\BOOTSTRAP_WEBSITE, '://'))
            $linkBootstrapWebsite = 'https://' . \Nebule\Bootstrap\BOOTSTRAP_WEBSITE;

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);

        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage($this->_applicationInstance::APPLICATION_SURNAME);
        $instance->setType(DisplayItemIconMessage::TYPE_INFORMATION);
        $instance->setIconText($this->_applicationInstance::APPLICATION_NAME);
        $instanceList->addItem($instance);

        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage($this->_applicationInstance::APPLICATION_WEBSITE);
        $instance->setType(DisplayItemIconMessage::TYPE_INFORMATION);
        $instance->setIconText($this->_applicationInstance::APPLICATION_NAME);
        $instance->setLink($linkApplicationWebsite);
        $instanceList->addItem($instance);

        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage($this->_translateInstance->getTranslate('::Version') . ' : ' . $this->_applicationInstance::APPLICATION_VERSION);
        $instance->setType(DisplayItemIconMessage::TYPE_INFORMATION);
        $instance->setIconText($this->_applicationInstance::APPLICATION_NAME);
        $instanceList->addItem($instance);

        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage($this->_applicationInstance::APPLICATION_LICENCE . ' (c) ' . $this->_applicationInstance::APPLICATION_AUTHOR);
        $instance->setType(DisplayItemIconMessage::TYPE_INFORMATION);
        $instance->setIconText($this->_applicationInstance::APPLICATION_NAME);
        $instanceList->addItem($instance);

        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage(\Nebule\Bootstrap\BOOTSTRAP_SURNAME);
        $instance->setType(DisplayItemIconMessage::TYPE_INFORMATION);
        $instance->setIconText(\Nebule\Bootstrap\BOOTSTRAP_NAME);
        $instanceList->addItem($instance);

        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage(\Nebule\Bootstrap\BOOTSTRAP_WEBSITE);
        $instance->setType(DisplayItemIconMessage::TYPE_INFORMATION);
        $instance->setIconText(\Nebule\Bootstrap\BOOTSTRAP_NAME);
        $instance->setLink($linkBootstrapWebsite);
        $instanceList->addItem($instance);

        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage($this->_translateInstance->getTranslate('::Version') . ' : ' . \Nebule\Bootstrap\BOOTSTRAP_VERSION);
        $instance->setType(DisplayItemIconMessage::TYPE_INFORMATION);
        $instance->setIconText(\Nebule\Bootstrap\BOOTSTRAP_NAME);
        $instanceList->addItem($instance);

        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage(\Nebule\Bootstrap\BOOTSTRAP_LICENCE . ' (c) ' . \Nebule\Bootstrap\BOOTSTRAP_AUTHOR);
        $instance->setType(DisplayItemIconMessage::TYPE_INFORMATION);
        $instance->setIconText(\Nebule\Bootstrap\BOOTSTRAP_NAME);
        $instanceList->addItem($instance);

        $instanceList->display();

        ?>
        <div class="text">
            <p>
                <?php echo $this->_translateInstance->getTranslate('::APropos:Text') ?>
            </p>
        </div>
        <?php
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => "Module d'aide",
            '::MenuName' => 'Aide',
            '::ModuleDescription' => "Module d'aide en ligne.",
            '::ModuleHelp' => "Ce module permet d'afficher de l'aide générale sur l'interface.",
            '::AppTitle1' => 'Aide',
            '::AppDesc1' => "Affiche l'aide en ligne.",
            '::Bienvenue' => 'Bienvenue.',
            '::About' => 'A propos',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Démarrage',
            '::AideGenerale' => 'Aide générale',
            '::APropos' => 'A propos',
            '::APropos:Text' => 'Todo',
        ],
        'en-en' => [
            '::ModuleName' => 'Help module',
            '::MenuName' => 'Help',
            '::ModuleDescription' => 'Online help module.',
            '::ModuleHelp' => 'This module permit to display general help about the interface.',
            '::AppTitle1' => 'Help',
            '::AppDesc1' => 'Display online help.',
            '::Bienvenue' => 'Welcome.',
            '::About' => 'About',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Start',
            '::AideGenerale' => 'General help',
            '::APropos' => 'About',
            '::APropos:Text' => 'Todo',
        ],
        'es-co' => [
            '::ModuleName' => 'Módulo de ayuda',
            '::MenuName' => 'Ayuda',
            '::ModuleDescription' => 'Módulo de ayuda en línea.',
            '::ModuleHelp' => 'Esta modulo te permite ver la ayuda general sobre la interfaz.',
            '::AppTitle1' => 'Ayuda',
            '::AppDesc1' => 'Muestra la ayuda en línea.',
            '::Bienvenue' => 'Bienviedo.',
            '::About' => 'About',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Comienzo',
            '::AideGenerale' => 'Ayuda general',
            '::APropos' => 'Acerca',
            '::APropos:Text' => 'Todo',
        ],
    ];
}
