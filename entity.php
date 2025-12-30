<?php
declare(strict_types=1);
namespace Nebule\Application\Entity;
use Nebule\Library\nebule;
use Nebule\Library\References;
use Nebule\Library\Metrology;
use Nebule\Library\ApplicationInterface;
use Nebule\Library\Applications;
use Nebule\Library\DisplayInterface;
use Nebule\Library\Displays;
use Nebule\Library\ActionsInterface;
use Nebule\Library\Actions;
use Nebule\Library\ModuleTranslateInterface;
use Nebule\Library\Translates;
use Nebule\Library\ModuleInterface;
use Nebule\Library\Modules;
use Nebule\Library\ModelModuleHelp;
use Nebule\Library\ModuleTranslates;

/*
|------------------------------------------------------------------------------------------
| /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
|------------------------------------------------------------------------------------------
|
|  [FR] Toute modification de ce code entraînera une modification de son empreinte
|       et entraînera donc automatiquement son invalidation !
|  [EN] Any modification of this code will result in a modification of its hash digest
|       and will therefore automatically result in its invalidation!
|  [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo
|       tanto lugar automáticamente a su anulación!
|  [UA] Будь-яка модифікація цього коду призведе до зміни його відбитку пальця і,
|       відповідно, автоматично призведе до його анулювання!
|
|------------------------------------------------------------------------------------------
*/



/**
 * Class Application for entity
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications
{
    const APPLICATION_NAME = 'entity';
    const APPLICATION_SURNAME = 'nebule/entity';
    const APPLICATION_AUTHOR = 'Project nebule';
    const APPLICATION_VERSION = '020251230';
    const APPLICATION_LICENCE = 'GNU GPL v3 2025-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '206090aec4ba9e2eaa66737d34ced59cfe73b8342fc020efbd321eded7c8b46440e0875a.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = true;
    const LIST_MODULES_INTERNAL = array(
        'ModuleHelp',
        'ModuleEntities',
        'ModuleGroupEntities',
        'ModuleLang',
        'ModuleTranslateFRFR',
        'ModuleTranslateENEN',
        'ModuleTranslateESCO',
    );
    const LIST_MODULES_EXTERNAL = array();
    public function __construct(nebule $nebuleInstance) { parent::__construct($nebuleInstance); }
    // All default.
}



/**
 * Classe Display
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Display extends Displays
{
    const DEFAULT_DISPLAY_MODE = 'ent';
    const DEFAULT_DISPLAY_VIEW = 'disp';
    const DEFAULT_LINK_COMMAND = 'lnk';
    const DEFAULT_APPLICATION_LOGO = "data:image/png;base64,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
fW/bt1ivWIOCEgAAAAASUVORK5CYII=";
    const DEFAULT_APPLICATION_LOGO_LINK = '?dm=log&dv=about';
    const DEFAULT_LOGO_MENUS = '15eb7dcf0554d76797ffb388e4bb5b866e70a3a33e7d394a120e68899a16c690.sha2.256';
    const DEFAULT_CSS_BACKGROUND = 'f6bc46330958c60be02d3d43613790427523c49bd4477db8ff9ca3a5f392b499.sha2.256';

    // Icônes de marquage.
    const DEFAULT_ICON_MARK = '65fb7dbaaa90465da5cb270da6d3f49614f6fcebb3af8c742e4efaa2715606f0.sha2.256';
    const DEFAULT_ICON_UNMARK = 'ee1d761617468ade89cd7a77ac96d4956d22a9d4cbedbec048b0c0c1bd3d00d2.sha2.256';
    const DEFAULT_ICON_UNMARKALL = 'fa40e3e73b9c11cb5169f3916b28619853023edbbf069d3bd9be76387f03a859.sha2.256';

    const APPLICATION_LICENCE_LOGO = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABg2lDQ1BJQ0MgcHJvZmlsZQAAKJF9
kT1Iw0AcxV/TiiIVh2YQcchQnayIijhqFYpQIdQKrTqYXPoFTRqSFBdHwbXg4Mdi1cHFWVcHV0EQ/ABxdnBSdJES/9cUWsR4cNyPd/ced+8AoV5muh0aB3TDsVKJuJTJrkrdrxAgIoIxhBRmm3OynITv
+LpHgK93MZ7lf+7P0aflbAYEJOJZZloO8Qbx9KZjct4nFllR0YjPiUctuiDxI9dVj984F5os8EzRSqfmiUViqdDBagezoqUTTxFHNd2gfCHjscZ5i7NerrLWPfkLwzljZZnrNIeQwCKWIEOCiipKKMNB
jFaDFBsp2o/7+AebfplcKrlKYORYQAU6lKYf/A9+d2vnJye8pHAc6Hpx3Y9hoHsXaNRc9/vYdRsnQPAZuDLa/kodmPkkvdbWokdA/zZwcd3W1D3gcgcYeDIVS2lKQZpCPg+8n9E3ZYHILdC75vXW2sfp
A5CmrpI3wMEhMFKg7HWfd/d09vbvmVZ/P1facpxfT0PtAAAABmJLR0QAAAAAAAD5Q7t/AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH6AIUEzoRwgFDRQAAAu5JREFUWMPdV0FL40AUfpkMuIEV
V0HUEA9egseA4imFNdBToAe9pIqQf1DwUoMg9RCas/4BIZiKh956WFy6B+ltlcJCWUVc6FpXESzdiywmkz3stsTYtEmtyu4HAwnMzPfNe2/ee0MBQAZeEQheGTjqAo7jBmRZnhAEYXR4eJgBAKjX63fl
cvmmUCj8uLi4+NV3ARhjStO06WQyOcPz/BRN020t5zgOOT09/ZbL5Y6y2exX27bdbntT3WJAVdVJXddllmXHopzs8vLyen19vbCzs/O9JwEIITBNM6YoyjxCiOrFv4QQd29v79PKysohISS8AIQQHBwc
yJIkzfYj0IrF4ud4PF5oJ6KtL03TjPWLHABAkqRZ0zRjoa6hqqqTiqLMt0xEUYFDFEUQRbHjnCYURZlXVXWyowCMMaXrutyrzzsmHIQoXddljDEVKEDTtOmo0R4FLMuOaZo2HSggmUzOPHfm83Mgb4bj
eX7quQXwPD/FcdzAIwGyLE8EZbh+gqZpJMvyxCMBgiCMvlQB8nK1BDQLy0vAy4WfstHCwgIAAJRKpaeX43q9fhdloWEYsLq6CgAA9/f3sLa2Fnqtl6vlgnK5fBOFPJ1Ot/7T6TQYhhFagJ8rAwAZjuOy
tm07rg8A8GAYhuEGwTCMR/P9sG3b4Tgu2+QFz0emUqmcdRLQiTxIhB+VSuXMy/ng3udyuaOwZg9CN3e042ipwRhv1mq1K78Fwpw8yBJe1Gq1K4zxppeTBoD3ng4Gbm9vrxOJhED9raUMw4Q6uR+iKALD
MCCKYqs7SqVS+8fHx42uHdHu7m5saWlJajQaMDQ09KSk09zDsqzi8vLy4b/RkhFCIB6PFyzLKhJC3F6JCSGuZVnFIHIAgAcx4IXrupDP56vVavV8bm6OHRwcfBu1LU+lUvsbGxtf/sQyRBPgyVo/t7a2
jmzbvh4fH38zMjLyLqhlcxyHnJycnG9vb39cXFz84A+4nh4mz/00iyzgv3sd/wY9bBdgOXr2vwAAAABJRU5ErkJggg==';


    /**
     * Liste des objets nécessaires au bon fonctionnement.
     *
     * @var array
     */
    protected array $_neededObjectsList = array( // FIXME
        self::DEFAULT_LOGO_MENUS,
        self::DEFAULT_ICON_ALPHA_COLOR,
        self::DEFAULT_ICON_LC,
        self::DEFAULT_ICON_LD,
        self::DEFAULT_ICON_LE,
        self::DEFAULT_ICON_LF,
        self::DEFAULT_ICON_LK,
        self::DEFAULT_ICON_LL,
        self::DEFAULT_ICON_LO,
        self::DEFAULT_ICON_LS,
        self::DEFAULT_ICON_LU,
        self::DEFAULT_ICON_LX,
        self::DEFAULT_ICON_IOK,
        self::DEFAULT_ICON_IWARN,
        self::DEFAULT_ICON_IERR,
        self::DEFAULT_ICON_IINFO,
        self::DEFAULT_ICON_IMLOG,
        self::DEFAULT_ICON_IMODIFY,
        self::DEFAULT_ICON_IDOWNLOAD,
        self::DEFAULT_ICON_HELP,
        self::DEFAULT_ICON_WORLD);

    protected function _initUrlLinks(): void
    {
        $this->setUrlLinkPrefix('Nebule\Library\Node', '?a=4&l=');
        $this->setUrlLinkPrefix('Nebule\Library\Entity', '?a=' . $this->_applicationInstance::APPLICATION_NODE
            . '&'. self::COMMAND_DISPLAY_MODE . '=' . self::DEFAULT_DISPLAY_MODE
            . '&'. self::COMMAND_DISPLAY_VIEW . '=' . self::DEFAULT_DISPLAY_VIEW
            . '&' . References::COMMAND_SELECT_ENTITY . '=');
    }



    /*
	 * --------------------------------------------------------------------------------
	 * La personnalisation.
	 * --------------------------------------------------------------------------------

    /**
     * Affichage du style CSS.
     */
    public function displayCSS(): void
    {
        // Recherche l'image de fond.
        $bgobj = $this->_cacheInstance->newNode($this::DEFAULT_CSS_BACKGROUND);
        if ($this->_nebuleInstance->getNodeIsRID($bgobj))
            $bgobj = $bgobj->getReferencedObjectInstance(References::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE, 'authority');
        $background = $bgobj->getUpdateNID(true, false);
        ?>

        <style type="text/css">
            html, body {
                font-family: Sans-Serif, monospace, Arial, Helvetica;
                font-stretch: condensed;
                font-size: 0.9em;
                text-align: left;
                min-height: 100%;
                width: 100%;
            }

            @media screen {
                body {
                    background: #f0f0f0 url("<?php echo 'o/'.$background; ?>") no-repeat;
                    background-position: center center;
                    background-size: cover;
                    background-attachment: fixed;
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    -o-background-size: cover;
                }
            }

            @media print {
                body {
                    background: #ffffff;
                }
            }

            .logoFloatLeft {
                float: left;
                margin: 5px;
                height: 64px;
                width: 64px;
            }

            .floatLeft {
                float: left;
                margin-right: 5px;
            }

            .floatRight {
                float: right;
                margin-left: 5px;
            }

            .textAlignLeft {
                text-align: left;
            }

            .textAlignRight {
                text-align: right;
            }

            .textAlignCenter {
                text-align: center;
            }

            .iconInlineDisplay {
                height: 16px;
                width: 16px;
            }

            .iconNormalDisplay {
                height: 64px;
                width: 64px;
            }

            .hideOnSmallMedia {
                display: block;
            }

            .divHeaderH1, .divHeaderH2 {
                overflow: hidden;
            }

            .layout-header {
                height: 74px;
                background: rgba(34, 34, 34, 0.8);
                color: #ffffff;
            }

            .layout-header {
                border-bottom-style: solid;
                border-bottom-color: #222222;
                border-bottom-width: 5px;
            }

            .layout-footer {
                height: auto;
                background: rgba(34, 34, 34, 0.8);
                color: #ffffff;
            }

            .layout-footer {
                border-top-style: solid;
                border-top-color: #222222;
                border-top-width: 5px;
            }

            .header-left {
                margin: 5px 0 5px 5px;
            }

            .header-left2 {
                margin: 5px;
                height: 64px;
                width: 207px;
                float: left;
            }

            .header-left2 img {
                margin-right: 5px;
            }

            .layout-header .layoutObject {
                float: left;
            }

            .layout-header .layoutObject .objectTitle .objectTitleText .objectTitleMediumName {
                overflow: visible;
            }

            .layout-header .layoutObject .objectTitle .objectTitleText {
                background: none;
                color: #ffffff;
            }

            .layout-header .layoutObject .objectTitle .objectTitleText a {
                color: #ffffff;
            }

            .header-right {
                margin: 5px;
            }

            .text a {
                color: #000000;
            }

            /* Liserets de verrouillage. */
            .headerUnlock {
                border-bottom-color: #e0000e;
            }

            .footerUnlock {
                border-top-color: #e0000e;
            }

            /* Le menu de gauche, contextuel et toujours visible même partiellement. */
            .layout-menu-left {
                display: inline;
                min-width: 217px;
                max-width: 217px;
                min-height: 100px;
                margin: 0;
                margin-right: 10px;
                margin-bottom: 20px;
                margin-top: 120px;
            }

            @media screen and (max-width: 1020px) {
                .layout-menu-left {
                    min-width: 74px;
                    max-width: 74px;
                    margin-right: 10px;
                }
            }

            @media screen and (max-height: 500px) {
                .layout-menu-left {
                    min-width: 32px;
                    max-width: 32px;
                    margin-right: 5px;
                }
            }

            @media screen and (max-width: 575px) {
                .layout-menu-left {
                    min-width: 32px;
                    max-width: 32px;
                    margin-right: 5px;
                }
            }

            .menu-left {
                width: 217px;
                color: #000000;
                background: rgba(255, 255, 255, 0.2);
                background-origin: border-box;
            }

            .menu-left img {
                height: 64px;
                width: 64px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left {
                    width: 74px;;
                }

                .menu-left img {
                    height: 64px;
                    width: 64px;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left {
                    width: 32px;
                }

                .menu-left img {
                    height: 32px;
                    width: 32px;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left {
                    width: 32px;
                }

                .menu-left img {
                    height: 32px;
                    width: 32px;
                }
            }

            .menu-left-module {
                height: 64px;
                width: 207px;
                padding: 5px;
                margin-bottom: 0;
                background: rgba(0, 0, 0, 0.7);
                background-origin: border-box;
                color: #ffffff;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-module {
                    height: 64px;
                    width: 64px;
                    padding: 5px;
                    margin-bottom: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-module {
                    height: 32px;
                    width: 32px;
                    padding: 0;
                    margin-bottom: 5px;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-module {
                    height: 32px;
                    width: 32px;
                    padding: 0;
                    margin-bottom: 5px;
                }
            }

            .menu-left-links {
                width: 207px;
                padding: 5px;
                padding-bottom: 0;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-links {
                    width: 64px;
                    padding: 5px;
                    padding-bottom: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-links {
                    width: 32px;
                    padding: 0;
                    padding-bottom: 0;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-links {
                    width: 32px;
                    padding: 0;
                    padding-bottom: 0;
                }
            }

            .menu-left-interlinks {
                height: 15px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-interlinks {
                    height: 10px;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-interlinks {
                    height: 5px;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-interlinks {
                    height: 5px;
                }
            }

            .menu-left-one {
                clear: both;
                padding-bottom: 5px;
                width: 207px;
                min-height: 64px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-one {
                    min-height: 64px;
                    width: 64px;
                    padding-bottom: 5px;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-one {
                    min-height: 32px;
                    width: 32px;
                    padding-bottom: 0;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-one {
                    min-height: 32px;
                    width: 32px;
                    padding-bottom: 0;
                }
            }

            .menu-left-icon {
                float: left;
                margin-right: 5px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-icon {
                    margin-right: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-icon {
                    margin-right: 0;
                }
            }

            .menu-left-modname p {
                font-size: 0.6em;
                font-style: italic;
            }

            .menu-left-title p {
                font-size: 1.1em;
                font-weight: bold;
            }

            .menu-left-text p {
                font-size: 0.8em;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-modname, .menu-left-title, .menu-left-text {
                    display: none;
                }

                .menu-left-icon {
                    margin-right: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-modname, .menu-left-title, .menu-left-text {
                    display: none;
                }

                .menu-left-icon {
                    margin-right: 0;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-modname, .menu-left-title, .menu-left-text {
                    display: none;
                }

                .menu-left-icon {
                    margin-right: 0;
                }
            }

            /* Correction affichage objets */
            .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                width: 256px;
            }

            @media screen and (min-width: 320px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 256px;
                }
            }

            @media screen and (min-width: 480px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 256px;
                }
            }

            @media screen and (min-width: 600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 343px;
                }
            }

            @media screen and (min-width: 768px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 511px;
                }
            }

            @media screen and (min-width: 1024px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 767px;
                }
            }

            @media screen and (min-width: 1200px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 943px;
                }
            }

            @media screen and (min-width: 1600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1343px;
                }
            }

            @media screen and (min-width: 1920px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1663px;
                }
            }

            @media screen and (min-width: 2048px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1791px;
                }
            }

            @media screen and (min-width: 2400px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2143px;
                }
            }

            @media screen and (min-width: 3840px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3583px;
                }
            }

            @media screen and (min-width: 4096px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3839px;
                }
            }

            .objectsListContent {
                background: rgba(255, 255, 255, 0.1);
            }

            .informationDisplayMessage {
                background: rgba(0, 0, 0, 0.5);
            }

            .informationDisplayInfo {
                background: rgba(255, 255, 255, 0.5);
            }

            .titleContentDiv {
                background: rgba(255, 255, 255, 0.5);
            }

            .titleContent h1 {
                color: #333333;
            }
        </style>
        <?php

        // Ajout de la partie CSS du module en cours d'utilisation, si présent.
        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module::MODULE_COMMAND_NAME == $this->_currentDisplayMode) {
                $module->getCSS();
            }
        }
    }
}



/**
 * Classe Action
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Action extends Actions {}



/**
 * Classe Traduction
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Translate extends Translates
{
    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            ':::version' => 'Version',
        ],
        'en-en' => [
            ':::version' => 'Version',
        ],
        'es-co' => [
            ':::version' => 'Version',
        ],
    ];
}



/**
 * This module manages the help pages and default first vue.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleHelp extends \Nebule\Library\ModelModuleHelp
{
    const MODULE_TYPE = 'Application';
    const MODULE_VERSION = '020251227';

    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => "Module d'aide",
            '::MenuName' => 'Aide',
            '::ModuleDescription' => "Module d'aide en ligne.",
            '::ModuleHelp' => "Ce module permet d'afficher de l'aide générale sur l'interface.",
            '::AppTitle1' => 'Aide',
            '::AppDesc1' => "Affiche l'aide en ligne",
            '::Bienvenue' => 'Bienvenue sur <b>weblog</b>.',
            '::About' => 'A propos',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Démarrage',
            '::AideGenerale' => 'Aide générale',
            '::APropos' => 'A propos',
            '::APropos:Text' => "Le projet <i>entity</i> est une implémentation logicielle de gestion des identités basée sur le projet nebule.<br />
Cette implémentation en php est voulue comme une référence des possibilités offertes par les objets et les liens tels que définis dans nebule.",
            '::AideGenerale:Text' => "Le logiciel est composé de trois parties :<br />
1. le bandeau du haut qui contient le menu de l'application et l'entité en cours.<br />
2. la partie centrale qui contient le contenu à afficher, les objets, les actions, etc...<br />
3. le bandeau du bas qui apparaît lorsqu'une action est réalisée.<br />
<br />
D'un point de vue général, tout ce qui est sur fond clair concerne une action en cours ou l'objet en cours d'utilisation. Et tout ce qui est sur fond noir concerne l'interface globale ou d'autres actions non liées à ce que l'on fait.<br />
Le menu en haut à gauche est le meilleur moyen de se déplacer dans l'interface.",
        ],
        'en-en' => [
            '::ModuleName' => 'Help module',
            '::MenuName' => 'Help',
            '::ModuleDescription' => 'Online help module.',
            '::ModuleHelp' => 'This module permit to display general help about the interface.',
            '::AppTitle1' => 'Help',
            '::AppDesc1' => 'Display online help',
            '::Bienvenue' => 'Welcome to <b>weblog</b>.',
            '::About' => 'About',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Start',
            '::AideGenerale' => 'General help',
            '::APropos' => 'About',
            '::APropos:Text' => 'The <i>entity</i> project is a software implementation to manage identities based on the nebule project.<br />
This PHP implementation is intended to serve as a reference for the possibilities offered by objects and links as defined in nebule.',
            '::AideGenerale:Text' => "The software is composed of three parts:<br />
1. The top banner, which contains the application menu and the current entity.<br />
2. The central part, which contains the content to display, objects, actions, etc...<br />
3. The bottom banner, which appears when an action is performed.<br />
<br />
From a general point of view, everything on a light background relates to an ongoing action or the object currently in use. And everything on a dark background relates to the global interface or other actions unrelated to what you're doing.<br />
The menu at the top left is the best way to navigate the interface.",
        ],
        'es-co' => [
            '::ModuleName' => 'Módulo de ayuda',
            '::MenuName' => 'Ayuda',
            '::ModuleDescription' => 'Módulo de ayuda en línea.',
            '::ModuleHelp' => 'Esta modulo te permite ver la ayuda general sobre la interfaz.',
            '::AppTitle1' => 'Ayuda',
            '::AppDesc1' => 'Muestra la ayuda en línea',
            '::Bienvenue' => 'Bienviedo en <b>weblog</b>.',
            '::About' => 'About',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Comienzo',
            '::AideGenerale' => 'Ayuda general',
            '::APropos' => 'Acerca',
            '::APropos:Text' => 'El proyecto <i>entity</i> es una implementación de software de gestión de identidad basado en el proyecto nebule.<br />
Esta implementación en PHP está diseñada como una referencia de las posibilidades que ofrecen los objetos y los enlaces tal como se definen en nebule.',
            '::AideGenerale:Text' => "El software se compone de tres partes:<br />
1. La banda superior, que contiene el menú de la aplicación y la entidad actual.<br />
2. La parte central, que contiene el contenido a mostrar, los objetos, las acciones, etc...<br />
3. La banda inferior, que aparece cuando se realiza una acción.<br />
<br />
Desde un punto de vista general, todo lo que está sobre un fondo claro está relacionado con una acción en curso o el objeto que se está utilizando. Y todo lo que está sobre un fondo oscuro se refiere a la interfaz global u otras acciones no relacionadas con lo que se está haciendo.<br />
El menú en la parte superior izquierda es la mejor manera de navegar por la interfaz.",
        ],
    ];
}
