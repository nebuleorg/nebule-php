<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Application\Neblog\Application;
use Nebule\Application\Neblog\Display;
use const Nebule\Bootstrap\BOOTSTRAP_SURNAME;
use const Nebule\Bootstrap\BOOTSTRAP_WEBSITE;
use const Nebule\Bootstrap\LIB_BOOTSTRAP_ICON;

/**
 * This module is a model for modules manage the help pages and default first vue.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModelModuleHelp extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Model';
    const MODULE_NAME = '::module:help:ModuleName';
    const MODULE_MENU_NAME = '::module:help:MenuName';
    const MODULE_COMMAND_NAME = 'hlp';
    const MODULE_DEFAULT_VIEW = '1st';
    const MODULE_DESCRIPTION = '::module:help:ModuleDescription';
    const MODULE_VERSION = '020250222';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2025';
    const MODULE_LOGO = '5b3afdf2eb971ce185930ad4accda23942d4495a638c2bdf27ae3e8e4537c1434697.none.272';
    const MODULE_HELP = '::module:help:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('1st', 'hlp', 'about');
    const MODULE_REGISTERED_ICONS = array(
        '5b3afdf2eb971ce185930ad4accda23942d4495a638c2bdf27ae3e8e4537c1434697.none.272', // 0 : icône d'aide.
        '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c.sha2.256',     // 1 : module
    );
    const MODULE_APP_TITLE_LIST = array('::module:help:AppTitle1');
    const MODULE_APP_ICON_LIST = array('5b3afdf2eb971ce185930ad4accda23942d4495a638c2bdf27ae3e8e4537c1434697.none.272');
    const MODULE_APP_DESC_LIST = array('::module:help:AppDesc1');
    const MODULE_APP_VIEW_LIST = array('hlp');



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array {
        $hookArray = array();
        switch ($hookName) {
            case 'menu':
                $hookArray[0]['name'] = '::module:help:AppTitle1';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::module:help:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1];
                break;
            case 'selfMenu':
                // Affiche l'aide.
                $hookArray[0]['name'] = '::module:help:AppTitle1';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::module:help:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1];

                // A propos.
                $hookArray[2]['name'] = '::module:help:About';
                $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                $hookArray[2]['desc'] = '';
                $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2];
                break;
        }
        return $hookArray;
    }

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

    public function displayModuleInline(): void {}

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
                <?php echo $this->_translateInstance->getTranslate('::module:help:AideGenerale:Text') ?>
            </p>
        </div>
        <?php
    }

    /**
     * Affichage de la page à propos.
     */
    private function _displayHlpAbout(): void {
        $linkApplicationWebsite = $this->_applicationInstance::APPLICATION_WEBSITE;
        if (strpos($this->_applicationInstance::APPLICATION_WEBSITE, '://') === false)
            $linkApplicationWebsite = 'http://' . $this->_applicationInstance::APPLICATION_WEBSITE;

        // Affiche les informations de l'application.
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => false,
            'informationType' => 'information',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        $list = array();
        $list[0]['information'] = $this->_applicationInstance::APPLICATION_SURNAME;
        $list[0]['param'] = $param;
        $list[0]['param']['icon'] = $this->_displayInstance::DEFAULT_APPLICATION_LOGO;
        //$list[0]['object'] = '1';
        $list[1]['information'] = '<a href="' . $linkApplicationWebsite . '" target="_blank">' . $this->_applicationInstance::APPLICATION_WEBSITE . '</a>';
        $list[1]['param'] = $param;
        $list[2]['information'] = $this->_translateInstance->getTranslate('::Version') . ' : ' . $this->_applicationInstance::APPLICATION_VERSION;
        $list[2]['param'] = $param;
        $list[3]['information'] = $this->_applicationInstance::APPLICATION_LICENCE . ' ' . $this->_applicationInstance::APPLICATION_AUTHOR;
        $list[3]['param'] = $param;
        $param['informationType'] = 'information';
        $list[4]['information'] = BOOTSTRAP_SURNAME;
        $list[4]['param'] = $param;
        $list[4]['param']['icon'] = LIB_BOOTSTRAP_ICON;
        //$list[4]['object'] = '1';
        $list[5]['information'] = '<a href="http://' . BOOTSTRAP_WEBSITE . '" target="_blank">' . BOOTSTRAP_WEBSITE . '</a>';
        $list[5]['param'] = $param;
        echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'Medium', false);

        ?>
        <div class="text">
            <p>
                <?php echo $this->_translateInstance->getTranslate('::module:help:APropos:Text') ?>
            </p>
            <p>
                <?php echo $this->_translateInstance->getTranslate('::module:help:APropos:Liens') ?>
            </p>
        </div>
        <?php
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::module:help:ModuleName' => "Module d'aide",
            '::module:help:MenuName' => 'Aide',
            '::module:help:ModuleDescription' => "Module d'aide en ligne.",
            '::module:help:ModuleHelp' => "Ce module permet d'afficher de l'aide générale sur l'interface.",
            '::module:help:AppTitle1' => 'Aide',
            '::module:help:AppDesc1' => "Affiche l'aide en ligne.",
            '::module:help:Bienvenue' => 'Bienvenue.',
            '::module:help:About' => 'A propos',
            '::module:help:Bootstrap' => 'Bootstrap',
            '::module:help:Demarrage' => 'Démarrage',
            '::module:help:AideGenerale' => 'Aide générale',
            '::module:help:APropos' => 'A propos',
            '::module:help:APropos:Text' => 'Todo',
        ],
        'en-en' => [
            '::module:help:ModuleName' => 'Help module',
            '::module:help:MenuName' => 'Help',
            '::module:help:ModuleDescription' => 'Online help module.',
            '::module:help:ModuleHelp' => 'This module permit to display general help about the interface.',
            '::module:help:AppTitle1' => 'Help',
            '::module:help:AppDesc1' => 'Display online help.',
            '::module:help:Bienvenue' => 'Welcome.',
            '::module:help:About' => 'About',
            '::module:help:Bootstrap' => 'Bootstrap',
            '::module:help:Demarrage' => 'Start',
            '::module:help:AideGenerale' => 'General help',
            '::module:help:APropos' => 'About',
            '::module:help:APropos:Text' => 'Todo',
        ],
        'es-co' => [
            '::module:help:ModuleName' => 'Módulo de ayuda',
            '::module:help:MenuName' => 'Ayuda',
            '::module:help:ModuleDescription' => 'Módulo de ayuda en línea.',
            '::module:help:ModuleHelp' => 'Esta modulo te permite ver la ayuda general sobre la interfaz.',
            '::module:help:AppTitle1' => 'Ayuda',
            '::module:help:AppDesc1' => 'Muestra la ayuda en línea.',
            '::module:help:Bienvenue' => 'Bienviedo.',
            '::module:help:About' => 'About',
            '::module:help:Bootstrap' => 'Bootstrap',
            '::module:help:Demarrage' => 'Comienzo',
            '::module:help:AideGenerale' => 'Ayuda general',
            '::module:help:APropos' => 'Acerca',
            '::module:help:APropos:Text' => 'Todo',
        ],
    ];
}
