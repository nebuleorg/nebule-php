<?php
declare(strict_types=1);
namespace Nebule\Application\Messae;
use Nebule\Library\DisplayInformation;
use Nebule\Library\Entity;
use Nebule\Library\Metrology;
use Nebule\Library\nebule;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Modules;
use Nebule\Library\Node;
use Nebule\Library\References;
use Nebule\Library\Translates;
use const Nebule\Bootstrap\BOOTSTRAP_NAME;

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
 * Class Application for messae
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications
{
    const APPLICATION_NAME = 'messae';
    const APPLICATION_SURNAME = 'nebule/messae';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020240815';
    const APPLICATION_LICENCE = 'GNU GPL 2016-2024';
    const APPLICATION_WEBSITE = 'www.messae.org';
    const APPLICATION_NODE = '2060a0d21853a42093f01d2e4809c2a5e9300b4ec31afbaf18af66ec65586d6c78b2823a.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = false;
    const LIST_MODULES_INTERNAL = array(
        'module_admin',
        'module_objects',
        'module_groups',
        'module_lang_fr-fr',
    );
    const LIST_MODULES_EXTERNAL = array();

    const APPLICATION_ENVIRONMENT_FILE = 'nebule.env';
    const APPLICATION_DEFAULT_DISPLAY_ONLINE_HELP = true;
    const APPLICATION_DEFAULT_DISPLAY_ONLINE_OPTIONS = false;
    const APPLICATION_DEFAULT_DISPLAY_METROLOGY = false;
    const APPLICATION_DEFAULT_DISPLAY_UNSECURE_URL = true;
    const APPLICATION_DEFAULT_DISPLAY_UNVERIFY_LARGE_CONTENT = false;
    const APPLICATION_DEFAULT_DISPLAY_NAME_SIZE = 128;
    const APPLICATION_DEFAULT_IO_READ_MAX_DATA = 1000000;
    const APPLICATION_DEFAULT_PERMIT_UPLOAD_OBJECT = false;
    const APPLICATION_DEFAULT_PERMIT_UPLOAD_LINKS = false;
    const APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_OBJECT = false;
    const APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_LINKS = false;
    const APPLICATION_DEFAULT_LOG_UNLOCK_ENTITY = false;
    const APPLICATION_DEFAULT_LOG_LOCK_ENTITY = false;
    const APPLICATION_DEFAULT_LOAD_MODULES = 'd6105350a2680281474df5438ddcb3979e5575afba6fda7f646886e78394a4fb.sha2.256';



    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @return void
     */
    public function __construct(nebule $nebuleInstance)
    {
        parent::__construct($nebuleInstance);
    }
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
    const DEFAULT_DISPLAY_MODE = 'hlp';
    const DEFAULT_DISPLAY_VIEW = '1st';
    const DEFAULT_LINK_COMMAND = 'lnk';
    const DEFAULT_APPLICATION_LOGO = "data:image/png;base64,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";
    const DEFAULT_APPLICATION_LOGO_LINK = '?mod=hlp&view=about';
    const DEFAULT_LOGO_MENUS = '15eb7dcf0554d76797ffb388e4bb5b866e70a3a33e7d394a120e68899a16c690';
    const DEFAULT_LOGO_MODULE = '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c';
    const DEFAULT_CSS_BACKGROUND = 'f6bc46330958c60be02d3d43613790427523c49bd4477db8ff9ca3a5f392b499';

    // Icônes de marquage.
    const DEFAULT_ICON_MARK = '65fb7dbaaa90465da5cb270da6d3f49614f6fcebb3af8c742e4efaa2715606f0';
    const DEFAULT_ICON_UNMARK = 'ee1d761617468ade89cd7a77ac96d4956d22a9d4cbedbec048b0c0c1bd3d00d2';
    const DEFAULT_ICON_UNMARKALL = 'fa40e3e73b9c11cb5169f3916b28619853023edbbf069d3bd9be76387f03a859';

    const APPLICATION_LICENCE_LOGO = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAQAAADZc7J/AAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAACxMAAA
sTAQCanBgAAAAHdElNRQfeDAYWDCX7YSGrAAABn0lEQVRIx62VPU8CQRCGn7toYWKCBRY0FNQe9CZ+dPwAt6AxkmgrJYX2JsaOVgtNTKQYfwCdYGKvUFOYGAooxJiYWHgWd5wL3C0Lc
aqbnZ1nd2923nUwmMqRBgbSTZ7jxCbuUKZIRhvq0eBamhYAVaLGesJyfSpSNwBUihZ5zPbCtgxjAWqDNn78oTTzcfCkMwVQG7SxtwjhRJt/Zz5LyQfAUui2ws1/cs+KIe2HTbKAzyOF
CKBK0a9b5U1OjXfjjizgkFclqYMLQE2bcaLOjVt3o69a6KrdibpXZyBGtq52At7BVMgWUQ4AxZiQHaIYADKxQRtEBlyVSwxbIFTOJW2IV9Xl1Nj3mJdemgjfyv7EGlccmqs6GPOXJyf
IEReG/IFrUpsQUU1GSNcFegsjekEZG7MLnoBoBIAbm0sXi7gGF+SB/kKIvjRHvVXR+t2MONPcY12RnvFwgFeetIadti/2WAV82lLQFWk7lLQsWas+dNgakwcZ4s2liF6giJq+SAcP8G
em+rom6wKFdFizkPY2qb/0/37a/uVxnfd5/wWNcHiC0uUMVAAAAABJRU5ErkJggg==';


    /**
     * Liste des objets nécessaires au bon fonctionnement.
     *
     * @var array
     */
    protected array $_neededObjectsList = array(
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

    /**
     * Initialisation des variables et instances interdépendantes.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_metrologyInstance->addLog('Load displays', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000'); // Log
        $this->_traductionInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_actionInstance = $this->_applicationInstance->getActionInstance();
        $this->_unlocked = $this->_entitiesInstance->getCurrentEntityIsUnlocked();

        // Vide, est surchargé juste avant l'affichage.
        $this->setUrlLinkObjectPrefix('?');
        $this->setUrlLinkGroupPrefix('?');
        $this->setUrlLinkConversationPrefix('?');
        $this->setUrlLinkEntityPrefix('?');
        $this->setUrlLinkCurrencyPrefix('?');
        $this->setUrlLinkTokenPoolPrefix('?');
        $this->setUrlLinkTokenPrefix('?');
        $this->setUrlLinkTransactionPrefix('?');
        $this->setUrlLinkWalletPrefix('?');

        $this->_findLogoApplication();
        $this->_findLogoApplicationLink();
        $this->_findLogoApplicationName();
        $this->_findCurrentDisplayMode();
        $this->_findCurrentModule();
        $this->_findCurrentDisplayView();
        $this->_findInlineContentID();

        // Si en mode téléchargement d'objet ou de lien, pas de traduction.
        if ($this->_traductionInstance !== null) {
            $this->_currentDisplayLanguage = $this->_traductionInstance->getCurrentLanguage();
            $this->_currentDisplayLanguageInstance = $this->_traductionInstance->getCurrentLanguageInstance();
            $this->_displayLanguageList = $this->_traductionInstance->getLanguageList();
            $this->_displayLanguageInstanceList = $this->_traductionInstance->getLanguageModuleInstanceList();
        }
    }



    /*
	 * --------------------------------------------------------------------------------
	 * La personnalisation.
	 * --------------------------------------------------------------------------------
	 *
	 * Pour l'instant, rien n'est personnalisable dans le style, mais ça viendra...
	 * @todo
	 */
    /**
     * Variable du logo de l'application.
     * @var string
     */
    private $_logoApplication = '';

    /**
     * Recherche le logo de l'application.
     */
    private function _findLogoApplication(): void
    {
        $this->_logoApplication = self::DEFAULT_APPLICATION_LOGO;
        // @todo
    }

    /**
     * Variable du lien du logo de l'application.
     * @var string
     */
    private $_logoApplicationLink = '';

    /**
     * Recherche le lien du logo de l'application.
     */
    private function _findLogoApplicationLink(): void
    {
        $this->_logoApplicationLink = self::DEFAULT_APPLICATION_LOGO_LINK;
        // @todo
    }

    /**
     * Variable du nom de l'application.
     * @var string
     */
    private $_logoApplicationName = '';

    /**
     * Recherche le nom de l'application.
     */
    private function _findLogoApplicationName(): void
    {
        $this->_logoApplicationName = Application::APPLICATION_NAME;
        // @todo
    }


    /**
     * Vérifie que toutes les icônes déclarées soient présentes.
     * Sinon les synchronise.
     */
    private function _checkDefinedIcons(): void
    {
        // @todo
    }

    /**
     * Code before display.
     */
    protected function _preDisplay(): void
    {
        // Préfix pour les objets. Les modules sont chargés, on peut les utiliser.
        $this->setUrlLinkObjectPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        // Préfix pour les groupes.
        if ($this->_applicationInstance->isModuleLoaded('ModuleGroups')) {
            $this->setUrlLinkGroupPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleGroups')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleGroups')->getDefaultView()
                . '&' . References::COMMAND_SELECT_GROUP . '=');
        } else {
            $this->setUrlLinkGroupPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
        }
        // Préfix pour les conversations.
        if ($this->_applicationInstance->isModuleLoaded('ModuleMessenger')) {
            $this->setUrlLinkConversationPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleMessenger')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleMessenger')->getDefaultView()
                . '&' . References::COMMAND_SELECT_CONVERSATION . '=');
        } else {
            $this->setUrlLinkConversationPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
        }
        // Préfix pour les entités.
        $this->setUrlLinkEntityPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getDefaultView()
            . '&' . References::COMMAND_SELECT_ENTITY . '=');
        // Préfix pour les monnaies.
        if ($this->_applicationInstance->isModuleLoaded('Moduleqantion')) {
            $this->setUrlLinkCurrencyPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[3]
                . '&' . References::COMMAND_SELECT_CURRENCY . '=');
            $this->setUrlLinkTokenPoolPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[8]
                . '&' . References::COMMAND_SELECT_TOKENPOOL . '=');
            $this->setUrlLinkTokenPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[13]
                . '&' . References::COMMAND_SELECT_TOKEN . '=');
            $this->setUrlLinkTransactionPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[19]
                . '&' . References::COMMAND_SELECT_TRANSACTION . '=');
            $this->setUrlLinkWalletPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[23]
                . '&' . References::COMMAND_SELECT_WALLET . '=');
        } else {
            $this->setUrlLinkCurrencyPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
            $this->setUrlLinkTokenPoolPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
            $this->setUrlLinkTokenPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
            $this->setUrlLinkTransactionPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
            $this->setUrlLinkWalletPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
        }
    }


    /**
     * Display full page.
     */
    protected function _displayFull(): void
    {
        $this->_metrologyInstance->addLog('Display full', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000'); // Log
        ?>
        <!DOCTYPE html>
        <html lang="<?php echo $this->_currentDisplayLanguage; ?>">
        <head>
            <meta charset="utf-8"/>
            <title><?php echo Application::APPLICATION_NAME . ' - ' . $this->_entitiesInstance->getCurrentEntityInstance()->getFullName('all'); ?></title>
            <link rel="icon" type="image/png" href="favicon.png"/>
            <meta name="keywords" content="<?php echo Application::APPLICATION_SURNAME; ?>"/>
            <meta name="description" content="<?php echo Application::APPLICATION_NAME . ' - ';
            echo $this->_traductionInstance->getTranslate('::::HtmlHeadDescription'); ?>"/>
            <meta name="author" content="<?php echo Application::APPLICATION_AUTHOR . ' - ' . Application::APPLICATION_WEBSITE; ?>"/>
            <meta name="licence" content="<?php echo Application::APPLICATION_LICENCE; ?>"/>
            <?php
            $this->_metrologyInstance->addLog('Display css', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
            $this->commonCSS();
            $this->displayCSS();

            $this->_metrologyInstance->addLog('Display vbs', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
            $this->_displayScripts();
            ?>

        </head>
        <body>
        <?php
        $this->_metrologyInstance->addLog('Display actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
        $this->_displayActions();

        $this->_metrologyInstance->addLog('Display header', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
        $this->_displayHeader();

        $this->_metrologyInstance->addLog('Display menu apps', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
        $this->_displayMenuApplications();
        ?>

        <div class="layout-main">
            <div class="layout-content">
                <div id="curseur" class="infobulle"></div>
                <div class="content">
                    <?php
                    $this->_metrologyInstance->addLog('Display checks', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
                    $this->_displayChecks();

                    $this->_metrologyInstance->addLog('Display content', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
                    $this->_displayContent();

                    $this->_metrologyInstance->addLog('Display metrology', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
                    $this->_displayMetrology();
                    ?>

                </div>
            </div>
        </div>
        <?php
        $this->_metrologyInstance->addLog('Display footer', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
        $this->_displayFooter();
    }


    /**
     * Affichage du style CSS.
     */
    public function displayCSS(): void
    {
        // Recherche l'image de fond.
        $bgobj = $this->_cacheInstance->newNode(self::DEFAULT_CSS_BACKGROUND);
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

            .menuListContentAction {
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
            }

            .messaeMenuListContentActionModules {
                background: rgba(0, 0, 0, 0.5);
                color: #ffffff;
            }

            .messaeMenuListContentActionHooks {
                background: rgba(255, 255, 255, 0.66);
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
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                $module->getCSS();
            }
        }
    }


    /**
     * Affichage des scripts JS.
     */
    protected function _displayScripts(): void
    {
        $this->commonScripts();

        // Ajout de la partie script JS du module en cours d'utilisation, si présent.
        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                $module->headerScript();
                echo "\n";
            }
        }
    }


    /**
     * Affichage des actions.
     */
    private function _displayActions(): void
    {
        ?>

        <div class="layout-footer footer<?php if ($this->_unlocked) {
            echo 'Unlock';
        } ?>">
            <p>
                <?php
                // Vérifie le ticket.
                if ($this->_nebuleInstance->getTicketingInstance()->checkActionTicket()) {
                    // Appelle les actions spéciales.
                    $this->_actionInstance->specialActions();

                    // Appelle les actions génériques.
                    $this->_actionInstance->genericActions();

                    // Appelle les actions du module concerné par le mode d'affichage.
                    foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                        if ($module->getCommandName() == $this->_currentDisplayMode) {
                            $this->_metrologyInstance->addLog('Actions for module ' . $module->getCommandName(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000'); // Log
                            $module->action();
                        }
                    }
                }
                ?>

            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la barre supérieure.
     *
     * La partie gauche présente le menu et l'entité en cours.
     *
     * La partie droite présente :
     * - Un ok si tout se passe bien.
     * - Un warning si il y a un problème. En mode rescue, on peut quand même verrouiller/déverrouiller l'entité.
     * - Une erreur si il y a un gros problème. Il n'est pas possible de déverrouiller l'entité.
     */
    private function _displayHeader(): void
    {
        ?>

        <div class="layout-header header<?php if ($this->_unlocked) {
            echo 'Unlock';
        } ?>">
            <div class="header-left">
                <?php
                if ($this->_configurationInstance->getOptionAsBoolean('permitJavaScript')) {
                    ?>
                    <img src="<?php echo $this->_logoApplication; ?>" alt="[M]"
                         title="<?php echo $this->_traductionInstance->getTranslate('::menu'); ?>"
                         onclick="display_menu('layout-menu-applications');"/>
                    <?php
                } else {
                    ?>
                    <a href="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->getCurrentDisplayMode() . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW; ?>=menu">
                        <img src="<?php echo $this->_logoApplication; ?>" alt="[M]"
                             title="<?php echo $this->_traductionInstance->getTranslate('::menu'); ?>"/>
                    </a>
                    <?php
                }
                ?>
            </div>
            <?php
            // Affiche l'entité et son image.
            $param = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => false,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => true,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'enableDisplayJS' => false,
                'enableDisplayObjectActions' => false,
            );
            if ($this->_unlocked) {
                $param['flagUnlockedLink'] = '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . References::COMMAND_AUTH_ENTITY_MOD
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_AUTH_ENTITY_INFO
                    . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                    . '&' . References::COMMAND_FLUSH;
            } else {
                $param['flagUnlockedLink'] = '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . References::COMMAND_AUTH_ENTITY_MOD
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_AUTH_ENTITY_LOGIN
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getCurrentEntityID();
            }
            echo $this->getDisplayObject_DEPRECATED($this->_entitiesInstance->getCurrentEntityInstance(), $param);
            ?>

            <div class="header-right">
                <?php
                if ($this->_applicationInstance->getCheckSecurityAll() == 'OK') {
                    echo "&nbsp;\n";
                } // Si un test est en warning maximum.
                elseif ($this->_applicationInstance->getCheckSecurityAll() == 'WARN') {
                    // Si mode rescue et en warning.
                    if ($this->_rescueInstance->getModeRescue()) {
                        // Si l'entité est déverrouillées.
                        if ($this->_unlocked) {
                            // Affiche le lien de verrouillage sans les effets.
                            $this->displayHypertextLink(
                                $this->convertUpdateImage(
                                    $this->_cacheInstance->newNode(DisplayInformation::ICON_WARN_RID), 'Etat déverrouillé, verrouiller ?',
                                    '',
                                    '',
                                    'name="ico_lock"'),
                                '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . References::COMMAND_AUTH_ENTITY_MOD
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_AUTH_ENTITY_INFO
                                . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                                . '&' . References::COMMAND_FLUSH);
                        } else {
                            // Affiche de lien de déverrouillage sans les effets.
                            $this->displayHypertextLink(
                                $this->convertUpdateImage(
                                    $this->_cacheInstance->newNode(DisplayInformation::ICON_WARN_RID), 'Etat verrouillé, déverrouiller ?',
                                    '',
                                    '',
                                    'name="ico_lock"'),
                                '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . References::COMMAND_AUTH_ENTITY_MOD
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_AUTH_ENTITY_LOGIN
                                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getCurrentEntityID());
                        }
                    } // Sinon affiche le warning.
                    else {
                        $this->displayHypertextLink(
                            $this->convertUpdateImage(
                                $this->_cacheInstance->newNode(DisplayInformation::ICON_WARN_RID),
                                'WARNING'),
                            '?' . References::COMMAND_AUTH_ENTITY_LOGOUT
                            . '&' . References::COMMAND_SWITCH_TO_ENTITY);
                    }
                } // Sinon c'est une erreur.
                else {
                    $this->displayHypertextLink(
                        $this->convertUpdateImage(
                            $this->_cacheInstance->newNode(DisplayInformation::ICON_ERROR_RID),
                            'ERROR'),
                        '?' . References::COMMAND_AUTH_ENTITY_LOGOUT
                        . '&' . References::COMMAND_FLUSH);
                }
                ?>

            </div>
            <div class="header-center">
                <?php $this->_displayHeaderCenter(); ?>

            </div>
        </div>
        <?php
    }


    /**
     * Affiche la partie centrale de l'entête.
     * Non utilisé.
     */
    private function _displayHeaderCenter(): void
    {
        //...
    }


    /**
     * Affiche le menu des applications.
     */
    private function _displayMenuApplications(): void
    {
        $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
        if (strpos(Application::APPLICATION_WEBSITE, '://') === false) {
            $linkApplicationWebsite = 'http://' . Application::APPLICATION_WEBSITE;
        }
        ?>

        <div class="layout-menu-applications" id="layout-menu-applications">
            <div class="menu-applications-sign">
                <img alt="<?php echo Application::APPLICATION_NAME; ?>" src="<?php echo $this->_logoApplication; ?>"/><br/>
                <?php echo Application::APPLICATION_NAME; ?><br/>
                (c) <?php echo Application::APPLICATION_LICENCE . ' ' . Application::APPLICATION_AUTHOR; ?><br/>
                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::Version');
                echo ' : ' . Application::APPLICATION_VERSION; ?><br/>
                <a href="<?php echo $linkApplicationWebsite; ?>" target="_blank"><?php echo Application::APPLICATION_WEBSITE; ?></a>
            </div>
            <div class="menu-applications-logo">
                <img src="<?php echo $this->_logoApplication; ?>" alt="[M]"
                     title="<?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::menu'); ?>"
                     onclick="display_menu('layout-menu-applications');"/>
            </div>
            <div class="menu-applications">
                <?php
                $this->_displayInternalMenuApplications();
                ?>

            </div>
        </div>
        <?php
    }

    /**
     * Affiche le menu des applications.
     */
    private function _displayInternalMenuApplications(): void
    {
        $modules = $this->_applicationInstance->getModulesListInstances();
        $list = array();
        $j = 0;
        $currentModuleName = 'noModuleFind-';

        // Appelle les actions du module concerné par le mode d'affichage.
        foreach ($modules as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                // Extrait le nom du module.
                $moduleName = $module->getTraduction($module->getMenuName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());

                // Mémorise le nom du module trouvé.
                $currentModuleName = $module->getMenuName();

                // Affiche le lien du menu seul (sans JS).
                if ($this->_currentDisplayView != 'menu') {
                    $list[$j]['icon'] = self::DEFAULT_LOGO_MODULE;
                    $list[$j]['title'] = $this->_applicationInstance->getTranslateInstance()->getTranslate('::menu', $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                    $list[$j]['htlink'] = '?' . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $module->getCommandName()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=menu';
                    $list[$j]['desc'] = $this->_applicationInstance->getTranslateInstance()->getTranslate('::menuDesc', $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                    $list[$j]['ref'] = Application::APPLICATION_NAME;
                    $list[$j]['class'] = 'messaeMenuListContentActionModules';
                    $j++;
                }

                // Liste les points d'encrages à afficher.
                $appHookList = $module->getHookList('selfMenu');
                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $icon = $appHook['icon'];
                            if ($icon == '') {
                                $icon = $module->getLogo();
                            }
                            if ($icon == '') {
                                $icon = self::DEFAULT_ICON_IMLOG;
                            }
                            $desc = $module->getTraduction($appHook['desc'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            if ($desc == '') {
                                $desc = '&nbsp;';
                            }

                            $list[$j]['icon'] = $icon;
                            $list[$j]['title'] = $module->getTraduction($appHook['name'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            $list[$j]['htlink'] = $appHook['link'];
                            $list[$j]['desc'] = $desc;
                            $list[$j]['ref'] = $moduleName;
                            $list[$j]['class'] = 'messaeMenuListContentActionHooks';
                            $j++;
                        }
                    }
                }
            }
        }

        // Appelle les actions d'autres modules pour le module concerné par le mode d'affichage.
        foreach ($modules as $module) {
            if ($module->getCommandName() != $this->_currentDisplayMode) {
                // Extrait le nom du module.
                $moduleName = $module->getTraduction($module->getMenuName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());

                // Liste les points d'encrages à afficher.
                $appHookList = $module->getHookList($currentModuleName . 'SelfMenu');
                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $icon = $appHook['icon'];
                            if ($icon == '') {
                                $icon = $module->getLogo();
                            }
                            if ($icon == '') {
                                $icon = self::DEFAULT_ICON_IMLOG;
                            }
                            $desc = $module->getTraduction($appHook['desc'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            if ($desc == '') {
                                $desc = '&nbsp;';
                            }

                            $list[$j]['icon'] = $icon;
                            $list[$j]['title'] = $module->getTraduction($appHook['name'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            $list[$j]['htlink'] = $appHook['link'];
                            $list[$j]['desc'] = $desc;
                            $list[$j]['ref'] = $moduleName;
                            $list[$j]['class'] = 'messaeMenuListContentActionHooks';
                            $j++;
                        }
                    }
                }
            }
        }

        // Appelle les actions d'autres modules pour le mode d'affichage.
        foreach ($modules as $module) {
            if ($module->getCommandName() != $this->_currentDisplayMode) {
                // Extrait le nom du module.
                $moduleName = $module->getTraduction($module->getMenuName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());

                // Liste les points d'encrages à afficher.
                $appHookList = $module->getHookList('menu');
                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $icon = $appHook['icon'];
                            if ($icon == '') {
                                $icon = $module->getLogo();
                            }
                            if ($icon == '') {
                                $icon = self::DEFAULT_ICON_IMLOG;
                            }
                            $desc = $module->getTraduction($appHook['desc'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            if ($desc == '') {
                                $desc = '&nbsp;';
                            }

                            $list[$j]['icon'] = $icon;
                            $list[$j]['title'] = $module->getTraduction($appHook['name'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            $list[$j]['htlink'] = $appHook['link'];
                            $list[$j]['desc'] = $desc;
                            $list[$j]['ref'] = $moduleName;
                            $list[$j]['class'] = 'messaeMenuListContentActionHooks';
                            $j++;
                        }
                    }
                }
            }
        }

        // Appelle la liste des modules.
        foreach ($modules as $module) {
            // Extrait le nom du module.
            $moduleName = $module->getTraduction($module->getName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());

            // Liste les options à afficher.
            $appTitleList = $module->getAppTitleList();
            if (sizeof($appTitleList) != 0) {
                $appIconList = $module->getAppIconList();
                $appDescList = $module->getAppDescList();
                $appViewList = $module->getAppViewList();
                for ($i = 0; $i < sizeof($appTitleList); $i++) {
                    $icon = $appIconList[$i];
                    if ($icon == '') {
                        $icon = $module->getLogo();
                    }
                    if ($icon == '') {
                        $icon = self::DEFAULT_ICON_LSTOBJ;
                    }
                    $desc = $module->getTraduction($appDescList[$i], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                    if ($desc == '') {
                        $desc = '&nbsp;';
                    }

                    $list[$j]['icon'] = $icon;
                    $list[$j]['title'] = $module->getTraduction($appTitleList[$i], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                    $list[$j]['htlink'] = '?' . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $module->getCommandName()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $appViewList[$i];
                    $list[$j]['desc'] = $desc;
                    $list[$j]['ref'] = $moduleName;
                    $list[$j]['class'] = 'messaeMenuListContentActionModules';
                    $j++;
                }
            }
        }

        // Ajoute l'application 0.
        $list[$j]['icon'] = parent::DEFAULT_APPLICATION_LOGO;
        $list[$j]['title'] = BOOTSTRAP_NAME;
        $list[$j]['htlink'] = '?' . Actions::DEFAULT_COMMAND_NEBULE_BOOTSTRAP;
        $list[$j]['desc'] = $this->_applicationInstance->getTranslateInstance()->getTranslate('::appSwitch', $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
        $list[$j]['ref'] = 'nebule';
        $list[$j]['class'] = 'messaeMenuListContentActionModules';

        echo $this->getDisplayMenuList($list, 'Medium');
    }


    /**
     * Affiche les alertes.
     */
    private function _displayChecks(): void
    {
        if ($this->_rescueInstance->getModeRescue()) {
            $this->displayMessageWarning_DEPRECATED('::::RESCUE');
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityBootstrapMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityBootstrapMessage());
        }
        if ($this->_applicationInstance->getCheckSecuritySign() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecuritySignMessage());
        }
        if ($this->_applicationInstance->getCheckSecuritySign() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecuritySignMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityURL() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityURLMessage());
        }
        if (!$this->_configurationInstance->getOptionAsBoolean('permitWrite')) {
            $this->displayMessageWarning_DEPRECATED('::::warn_ServNotPermitWrite');
        }
        if ($this->_nebuleInstance->getCacheInstance()->getFlushCache()) {
            $this->displayMessageWarning_DEPRECATED('::::warn_flushSessionAndCache');
        }
    }

    /**
     * Contenu de la page.
     *
     * Affiche le contenu des pages en fonction du mode demandé.
     * Un seul mode est pris en compte pour l'affichage, les autres sont ignorés.
     *
     * Le traitement de l'affichage de la vue est faite par le module gérant le mode d'affichage.
     *
     * Seule exception, la vue 'menu' est traitée comme un affichage du menu sans JS et sans passer directement par un module.
     * Le contenu du menu est lui dépendant du module en cours et de certaines réponses des autres modules.
     */
    private function _displayContent(): void
    {
        if ($this->_currentDisplayView == 'menu') {
            $this->_displayInternalMenuApplications();
        } else {
            foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                if ($module->getCommandName() == $this->_currentDisplayMode) {
                    $module->displayModule();
                }
            }
        }
        $this->_displayInlineContentID();
    }


    /**
     * Affiche la métrologie.
     */
    private function _displayMetrology(): void
    {
        if ($this->_configurationInstance->getOptionUntyped('messaeDisplayMetrology')) {
            ?>

            <?php $this->displayDivTextTitle(self::DEFAULT_ICON_IMLOG, 'Métrologie', 'Mesures quantitatives et temporelles.') ?>
            <div class="text">
                <p>
                    <?php
                    //		aff_title('::bloc_metrolog','imlog');
                    // Affiche les valeurs de la librairie.
                    /*		echo 'Bootstrap : ';
		$this->_traductionInstance->echoTraduction('%01.0f liens lus,','',$this->_bootstrapInstance->getMetrologyInstance()->getLinkRead()); echo ' ';
		$this->_traductionInstance->echoTraduction('%01.0f liens vérifiés,','',$this->_bootstrapInstance->getMetrologyInstance()->getLinkVerify()); echo ' ';
		$this->_traductionInstance->echoTraduction('%01.0f objets vérifiés.','',$this->_bootstrapInstance->getMetrologyInstance()->getObjectVerify()); echo "<br />\n";*/
                    echo 'Lib nebule : ';
                    echo $this->_traductionInstance->getTranslate('%01.0f liens lus,', (string)$this->_metrologyInstance->getLinkRead());
                    echo ' ';
                    echo $this->_traductionInstance->getTranslate('%01.0f liens vérifiés,', (string)$this->_metrologyInstance->getLinkVerify());
                    echo ' ';
                    echo $this->_traductionInstance->getTranslate('%01.0f objets lus.', (string)$this->_metrologyInstance->getObjectRead());
                    echo ' ';
                    echo $this->_traductionInstance->getTranslate('%01.0f objets vérifiés.', (string)$this->_metrologyInstance->getObjectVerify());
                    echo "<br />\n";
                    // Calcul de temps de chargement de la page - stop
                    /*		$bootstrapTimeList = $this->_bootstrapInstance->getMetrologyInstance()->getTimeArray();
		$bootstrap_time_total = 0;
		foreach ( $bootstrapTimeList as $time )
		{
			$bootstrap_time_total = $bootstrap_time_total + $time;
		}
		$this->_traductionInstance->echoTraduction('Le bootstrap à pris %01.4fs pour appeler la page.','',$bootstrap_time_total);
		echo ' (';
		foreach ( $bootstrapTimeList as $time )
		{
			echo sprintf(" %1.4fs", $time);
		}
		echo " )\n";
		echo "<br />\n";*/
                    $this->_metrologyInstance->addTime();
                    $messaeTimeList = $this->_metrologyInstance->getTimeArray();
                    $messae_time_total = 0;
                    foreach ($messaeTimeList as $time) {
                        $messae_time_total = $messae_time_total + $time;
                    }
                    echo $this->_traductionInstance->getTranslate('Le serveur à pris %01.4fs pour calculer la page.', $messae_time_total);
                    echo ' (';
                    foreach ($messaeTimeList as $time) {
                        echo sprintf(" %1.4fs", $time);
                    }
                    echo " )\n";
                    ?>

                </p>
            </div>
            <?php
        }
    }

    // Affiche la fin de page.
    private function _displayFooter(): void
    {
        ?>

        </body>
        </html>
        <?php
    }


    /* --------------------------------------------------------------------------------
	 *  Affichage des objets.
	 * -------------------------------------------------------------------------------- */
    public function displayObjectDivHeaderH1($object, $help = '', $desc = ''): void
    {
        $object = $this->_applicationInstance->getTypedInstanceFromNID($object);
        // Prépare le type mime.
        $typemime = $object->getType('all');
        if ($desc == '') {
            $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($typemime);
        }

        // Détermine si c'est une entité.
        $objHead = $object->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        $isEntity = ($typemime == Entity::ENTITY_TYPE && strpos($objHead, nebule::REFERENCE_ENTITY_HEADER) !== false);

        // Détermine si c'est un groupe.
        $isGroup = $object->getIsGroup('all');

        // Détermine si c'est une conversation.
        $isConversation = $object->getIsConversation('all');

        // Modifie le type au besoin.
        if ($isEntity && !is_a($object, 'Entity')) {
            $object = $this->_cacheInstance->newEntity($object->getID());
        }
        if ($isGroup && !is_a($object, 'Group')) {
            $object = $this->_cacheInstance->newGroup($object->getID());
        }
        if ($isConversation && !is_a($object, 'Conversation')) {
            $object = $this->_cacheInstance->newConversation($object->getID());
        }

        $name = $object->getFullName('all');
        $present = $object->checkPresent();

        // Si le contenu est présent.
        if ($present) {
            // Prépare l'état de l'objet.
            $status = 'ok';
            $content = $object->getContent(0);
            $tooBig = false;
            if ($content == null) {
                $status = 'tooBig';
            }
            unset($content);
        } elseif ($isConversation
            || $isGroup
        ) {
            $status = 'notAnObject';
        } else {
            $status = 'notPresent';
        }
        if ($object->getMarkWarning()) {
            $status = 'warning';
        }
        if ($object->getMarkDanger()) {
            $status = 'danger';
        }
        // Prépare l'aide en ligne.
        if ($help == '') {
            if ($isEntity) {
                $help = ':::display:default:help:Entity';
            } elseif ($isConversation) {
                $help = ':::display:default:help:Conversation';
            } elseif ($isGroup) {
                $help = ':::display:default:help:Group';
            } else {
                $help = ':::display:default:help:Object';
            }
        }
        ?>

        <div class="textTitle">
            <?php
            $this->_displayDivOnlineHelp($help);
            ?>

            <div class="floatRight">
                <?php
                switch ($status) {
                    case 'notPresent':
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:errorNotAvailable');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'tooBig':
                        if ($this->_configurationInstance->getOptionUntyped('messaeDisplayUnverifyLargeContent')) {
                            $msg = $this->_traductionInstance->getTranslate('::::display:content:warningTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        } else {
                            $msg = $this->_traductionInstance->getTranslate(':::display:content:errorTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        }
                        break;
                    case 'warning':
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:warningTaggedWarning');
                        $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        break;
                    case 'danger':
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:errorBan');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'notAnObject':
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:notAnObject');
                        $this->displayIcon(self::DEFAULT_ICON_ALPHA_COLOR, $msg, 'iconNormalDisplay');
                        break;
                    default:
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:OK');
                        $this->displayIcon(self::DEFAULT_ICON_IOK, $msg, 'iconNormalDisplay');
                        break;
                }
                unset($msg);
                ?>

            </div>
            <div style="float:left;">
                <?php
                $this->displayObjectColorIcon($object);
                ?>

            </div>
            <h1 class="divHeaderH1"><?php echo $name; ?></h1>
            <p class="hideOnSmallMedia"><?php echo $desc; ?></p>
        </div>
        <?php
        unset($name, $typemime, $isEntity, $isGroup, $isConversation);
    }

    /**
     * Affiche dans les barres de titres l'icône d'aide contextuelle.
     * @param string $help
     */
    private function _displayDivOnlineHelp($help): void
    {
        // Si authorisé à afficher l'aide.
        if ($this->_configurationInstance->getOptionUntyped('messaeDisplayOnlineHelp')) {
            // Prépare le texte à afficher dans la bulle.
            $txt = $this->_applicationInstance->getTranslateInstance()->getTranslate($help);
            $txt = str_replace('&', '&amp;', $txt);
            $txt = str_replace('"', '&quot;', $txt);
            $txt = str_replace("'", '&acute;', $txt);
            //$txt = str_replace('<','&lt;',$txt);
            $txt = str_replace("\n", ' ', $txt);
            // Prépare l'extension de lien.
            $linkext = 'onmouseover="montre(\'<b>' . $this->_applicationInstance->getTranslateInstance()->getTranslate('Aide') . ' :</b><br />' . $txt . '\');" onmouseout="cache();"';
            unset($txt);
            // Affiche la bulle et le texte.
            ?>

            <div style="float:right;">
                <?php
                $image = $this->prepareIcon(self::DEFAULT_ICON_HELP);
                ?>

                <img alt="[]" src="<?php echo $image; ?>" class="iconNormalDisplay"
                     id="curseur" <?php echo $linkext; ?> />
            </div>
            <?php
            unset($linkext, $image);
        }
    }


    /**
     * Affiche le titre pour un paragraphe de texte. Par défaut, affiche le titre H1.
     *
     * @param string $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     * @return void
     */
    public function displayDivTextTitle($icon, $title = '', $desc = '', $help = ''): void
    {
        $this->displayDivTextTitleH1($icon, $title, $desc, $help);
    }

    /**
     * Affiche le titre H1 pour un paragraphe de texte.
     *
     * @param string $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     * @return void
     */
    public function displayDivTextTitleH1($icon, $title = '', $desc = '', $help = ''): void
    {
        ?>

        <div class="textTitle">
            <?php
            if ($title != '') {
                $title = $this->_applicationInstance->getTranslateInstance()->getTranslate($title);
            }

            if ($desc == '') {
                $desc = '-';
            } else {
                $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($desc);
            }

            $this->_displayDivOnlineHelp($help);
            ?>

            <div style="float:left;">
                <?php $this->displayUpdateImage($icon, $title, 'iconegrandepuce'); ?>

            </div>
            <h1 class="divHeaderH1"><?php echo $title; ?></h1>
            <p class="hideOnSmallMedia"><?php echo $desc; ?></p>
        </div>
        <?php

    }

    /**
     * Affiche le titre H2 pour un paragraphe de texte.
     *
     * @param string $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     * @return void
     */
    public function displayDivTextTitleH2($icon, $title = '', $desc = '', $help = ''): void
    {
        ?>

        <div class="textTitle2">
            <?php
            if ($title != '') {
                $title = $this->_applicationInstance->getTranslateInstance()->getTranslate($title);
            }

            if ($desc == '') {
                $desc = '-';
            } else {
                $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($desc);
            }

            $this->_displayDivOnlineHelp($help);
            ?>

            <div style="float:left;">
                <?php $this->displayUpdateImage($icon, $title, 'iconegrandepuce'); ?>

            </div>
            <h2 class="divHeaderH2"><?php echo $title; ?></h2>
            <p class="hideOnSmallMedia"><?php echo $desc; ?></p>
        </div>
        <?php

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
class Action extends Actions
{
    // Tout par défaut.
}


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
    private $_configuration;

    public function __construct(Application $applicationInstance)
    {
        parent::__construct($applicationInstance);
        $this->_configuration = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
    }
}


/**
 * Cette application permet d'afficher l'aide et la page par défaut de messae.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleHelp extends Modules
{
    protected string $MODULE_TYPE = 'Application';
    protected string $MODULE_NAME = '::messae:module:help:ModuleName';
    protected string $MODULE_MENU_NAME = '::messae:module:help:MenuName';
    protected string $MODULE_COMMAND_NAME = 'hlp';
    protected string $MODULE_DEFAULT_VIEW = '1st';
    protected string $MODULE_DESCRIPTION = '::messae:module:help:ModuleDescription';
    protected string $MODULE_VERSION = '020230110';
    protected string $MODULE_AUTHOR = 'Projet nebule';
    protected string $MODULE_LICENCE = '(c) GLPv3 nebule 2016-2020';
    protected string $MODULE_LOGO = '1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d';
    protected string $MODULE_HELP = '::messae:module:help:ModuleHelp';
    protected string $MODULE_INTERFACE = '3.0';

    protected array $MODULE_REGISTERED_VIEWS = array('1st', 'hlp', 'lang', 'about');
    protected array $MODULE_REGISTERED_ICONS = array(
        '1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d',    // 0 : icône d'aide.
        '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c',    // 1 : module
        'd7f68db0a1d0977fb8e521fd038b18cd601946aa0e26071ff8c02c160549633b',    // 2 : bootstrap (metrologie)
        '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6',    // 3 : world
        '3edf52669e7284e4cefbdbb00a8b015460271765e97a0d6ce6496b11fe530ce1',    // 4 : lister entités
    );
    protected array $MODULE_APP_TITLE_LIST = array('::messae:module:help:AppTitle1');
    protected array $MODULE_APP_ICON_LIST = array('1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d');
    protected array $MODULE_APP_DESC_LIST = array('::messae:module:help:AppDesc1');
    protected array $MODULE_APP_VIEW_LIST = array('hlp');


    /**
     * Configuration spécifique au module.
     */
    protected function _initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getCurrentEntityIsUnlocked();
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();
        switch ($hookName) {
            case 'menu':
                $hookArray[0]['name'] = '::messae:module:help:AppTitle1';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::messae:module:help:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];
                break;
            case 'selfMenu':
                // Affiche l'aide.
                $hookArray[0]['name'] = '::messae:module:help:AppTitle1';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::messae:module:help:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];

                // Choix de la langue.
                $hookArray[1]['name'] = '::messae:module:help:Langue';
                $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[3];
                $hookArray[1]['desc'] = '::messae:module:help:ChangerLangue';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];

                // A propos.
                $hookArray[2]['name'] = '::messae:module:help:About';
                $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                $hookArray[2]['desc'] = '';
                $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3];

                // Bootstrap.
                $hookArray[3]['name'] = '::messae:module:help:Bootstrap';
                $hookArray[3]['icon'] = $this->MODULE_REGISTERED_ICONS[2];
                $hookArray[3]['desc'] = '';
                $hookArray[3]['link'] = '?' . Actions::DEFAULT_COMMAND_NEBULE_BOOTSTRAP;
                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     */
    public function displayModule(): void
    {
        $this->_displayHlpHeader();
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case '1st':
                $this->_displayHlpFirst();
                break;
            case 'hlp':
                $this->_displayHlpHelp();
                break;
            case 'lang':
                $this->_displayHlpLang();
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
     * Affichage en ligne comme élément inseré dans une page web.
     */
    public function displayModuleInline(): void
    {
        // Rien à faire.
    }

    /**
     * Affichage de surcharges CSS.
     */
    public function headerStyle(): void
    {
        ?>

        .messaeModuleHelpText1st { margin:10%; }
        <?php
    }


    /**
     * Affichage de l'entête des pages.
     */
    private function _displayHlpHeader(): void
    {
    }


    /**
     * Affichage de la page par défaut.
     */
    private function _displayHlpFirst(): void
    {
        // Affiche le titre.
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED($this->_applicationInstance->getTranslateInstance()->getTranslate('::SelectUser'), $this->MODULE_REGISTERED_ICONS[4]);

        // Liste des entités déjà affichées.
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités marquées comme connu.
        $links = $this->_applicationInstance->getCurrentEntityInstance()->getLinksOnFields(
            $this->_applicationInstance->getCurrentEntityID(),
            '',
            'f',
            $this->_applicationInstance->getCurrentEntityID(),
            '',
            '');

        // Prépare l'affichage.
        $list = array();
        $i = 0;
        foreach ($links as $link) {
            $instance = $this->_cacheInstance->newEntity($link->getParsed()['bl/rl/nid2']);
            if (!isset($listOkEntities[$link->getParsed()['bl/rl/nid2']])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );

                // Marque comme vu.
                $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
                $i++;
            }
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_displayInstance->getDisplayObjectsList($list, 'medium');

        unset($list, $links, $listOkEntities);
    }


    /**
     * Affichage de la page de choix de la langue.
     */
    private function _displayHlpLang(): void
    {
        $module = $this->_applicationInstance->getTranslateInstance()->getCurrentLanguageInstance();

        // Affiche la langue en cours.
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => true,
            'informationType' => 'information',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
            'icon' => $module->getLogo(),
        );
        echo $this->_displayInstance->getDisplayInformation_DEPRECATED($module->getTraduction($module->getName()), $param);

        // Affiche le titre.
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED($this->_applicationInstance->getTranslateInstance()->getTranslate('::ChangeLanguage'), $this->MODULE_REGISTERED_ICONS[3]);

        // Affiche la liste des langues.
        echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('helpLanguages', 'Medium');
    }


    /**
     * Affichage de l'aide générale.
     */
    private function _displayHlpHelp(): void
    {
        ?>
        <div class="text">
            <p>
                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::messae:module:help:AideGenerale:Text') ?>
            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la page à propos.
     */
    private function _displayHlpAbout(): void
    {
        $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
        if (strpos(Application::APPLICATION_WEBSITE, '://') === false)
            $linkApplicationWebsite = 'http://' . Application::APPLICATION_WEBSITE;

        // Affiche les informations de l'application.
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => false,
            'informationType' => 'information',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        $list = array();
        $list[0]['information'] = Application::APPLICATION_NAME;
        $list[0]['param'] = $param;
        $list[0]['param']['icon'] = Displays::DEFAULT_APPLICATION_LOGO;
        $list[0]['object'] = '1';
        $list[1]['information'] = $this->_applicationInstance->getTranslateInstance()->getTranslate('::Version') . ' : ' . Application::APPLICATION_VERSION;
        $list[1]['param'] = $param;
        $list[2]['information'] = Application::APPLICATION_LICENCE . ' ' . Application::APPLICATION_AUTHOR;
        $list[2]['param'] = $param;
        $list[3]['information'] = '<a href="' . $linkApplicationWebsite . '" target="_blank">' . Application::APPLICATION_WEBSITE . '</a>';
        $list[3]['param'] = $param;
        echo $this->_displayInstance->getDisplayObjectsList($list, 'Medium');

        ?>
        <div class="text">
            <p>
                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::messae:module:help:APropos:Text') ?>
            </p>
            <p>
                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::messae:module:help:APropos:Liens') ?>
            </p>
        </div>
        <?php
        if ($this->_unlocked && $this->_configurationInstance->getOptionUntyped('messaeDisplayOnlineOptions')) {
            ?>
            <div class="text">
                <p>
                    <b>messae :</b><br/>
                    <?php
                    $options = array('messaeDisplayOnlineHelp', 'messaeDisplayOnlineOptions',
                        'messaeDisplayMetrology', 'messaeDisplayUnsecureURL',
                        'messaeDisplayUnverifyLargeContent', 'messaeDisplayNameSize',
                        'messaeIOReadMaxDataPHP', 'messaePermitUploadObject', 'messaePermitUploadLinks',
                        'messaePermitPublicUploadObject', 'messaePermitPublicUploadLinks', 'messaeLogUnlockEntity',
                        'messaeLogLockEntity', 'messaeLoadModules', 'default',
                    );
                    foreach ($options as $option) {
                        echo $option . ' : ';
                        var_dump($this->_configurationInstance->getOptionUntyped($option));
                        echo "<br />\n";
                    }
                    ?>
                </p>
            </div>
            <?php
        }
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::messae:module:help:ModuleName' => "Module d'aide",
            '::messae:module:help:MenuName' => 'Aide',
            '::messae:module:help:ModuleDescription' => "Module d'aide en ligne.",
            '::messae:module:help:ModuleHelp' => "Cette application permet d'afficher de l'aide générale sur l'interface.",
            '::messae:module:help:AppTitle1' => 'Aide',
            '::messae:module:help:AppDesc1' => "Affiche l'aide en ligne.",
            '::messae:module:help:Bienvenue' => 'Bienvenue sur <b>messae</b>.',
            '::messae:module:help:Langue' => 'Langue',
            '::messae:module:help:ChangerLangue' => 'Changer de langue',
            '::messae:module:help:About' => 'A propos',
            '::messae:module:help:Bootstrap' => 'Bootstrap',
            '::messae:module:help:Demarrage' => 'Démarrage',
            '::messae:module:help:AideGenerale' => 'Aide générale',
            '::messae:module:help:APropos' => 'A propos',
            '::messae:module:help:APropos:Text' => "Le projet messae est une implémentation logicielle basée sur le projet nebule.<br />
Cette implémentation en php est voulue comme une référence des possibilités offertes par les objets et les liens tels que définis dans nebule.<br />
<br />
Les systèmes informatiques actuels sont incapables de gérer directement les objets et les liens. Il n’est donc pas possible d’utiliser nebule nativement.
Le projet messae permet un accès à cette nouvelle façon de gérer nos informations sans remettre en question fondamentalement l’organisation et notamment les systèmes d’exploitation de notre système d’information.<br />
<br />
L’interface messae est une page web destinée à être mise en place sur un serveur pour manipuler des objets et des liens.
Cela s’apparente tout à fait à ce qui se fait déjà communément : Google (et sa galaxie de sites), Facebook, Twitter, Outlook, Yahoo et leurs innombrables concurrents et prétendants…
Tous ces sites sont globalement plus concurrents que complémentaires, et tous sans exception sont fermés à leurs concurrents.
Cela se traduit pour l’utilisateur par la nécessité de, soit disposer d’un compte sur chaque site, soit de ne fréquenter que certains d’entre eux, voir un seul.
Cela se traduit aussi par l’impossibilité d’échanger directement des données et informations d’un site à l’autre.<br />
<br />
Le projet messae reproduit la concentration des données vers des serveurs sur l’Internet.
Il est nativement prévu pour pouvoir être implanté sur n’importe quel serveur web.
Et, se basant sur les principes de nebule, tout serveur hébergeant messae peut nativement :<br />
 1. gérer les identités générées par les autres serveurs, que ce soit un utilisateur ou un robot ;<br />
 2. échanger des données et des informations avec tous les autres serveurs implémentant nebule ;<br />
 3. relayer les données et les informations d’autres serveurs ;<br />
 4. permettre à tout utilisateur (connu du serveur) de s’y connecter.<br />
<br />
Grâce à IPv6, nous avons la possibilité de réellement connecter toutes les machines sur l’Internet.
Chacun peut ainsi mettre en place simplement messae chez lui, ou continuer à l’utiliser sur un autre serveur de l’Internet.
Chacun peut devenir individuellement actif.<br />
<br />
Enfin, si un jour nebule s’étend à toutes les machines et que toutes ces machines l’implémentent nativement, alors le projet messae disparaîtra.
Il aura rempli sa mission : permettre une transition douce vers nebule.<br />
Il sera vu comme bizarrerie symptomatique d’une époque.",
            '::messae:module:help:APropos:Liens' => 'Voir aussi :<br /><a href="http://blog.messae.org/">Le blog du projet messae</a><br /><a href="http://blog.nebule.org/">Le blog du projet nebule</a>',
            '::messae:module:help:AideGenerale:Text' => "Le logiciel est composé de trois parties :<br />
1. le bandeau du haut qui contient le menu de l'application et l'entité en cours.<br />
2. la partie centrale qui contient le contenu à afficher, les objets, les actions, etc...<br />
3. le bandeau du bas qui apparaît lorsqu'une action est réalisée.<br />
<br />
D'un point de vue général, tout ce qui est sur fond clair concerne une action en cours ou l'objet en cours d'utilisation. Et tout ce qui est sur fond noir concerne l'interface globale ou d'autres actions non liées à ce que l'on fait.<br />
Le menu est le meilleur moyen de se déplacer dans l'interface.",
        ],
        'en-en' => [
            '::messae:module:help:ModuleName' => 'Help module',
            '::messae:module:help:MenuName' => 'Help',
            '::messae:module:help:ModuleDescription' => 'Online help module.',
            '::messae:module:help:ModuleHelp' => 'This application permit to display general help about the interface.',
            '::messae:module:help:AppTitle1' => 'Help',
            '::messae:module:help:AppDesc1' => 'Display online help.',
            '::messae:module:help:Bienvenue' => 'Welcome to <b>messae</b>.',
            '::messae:module:help:Langue' => 'Language',
            '::messae:module:help:ChangerLangue' => 'Change language',
            '::messae:module:help:About' => 'About',
            '::messae:module:help:Bootstrap' => 'Bootstrap',
            '::messae:module:help:Demarrage' => 'Start',
            '::messae:module:help:AideGenerale' => 'General help',
            '::messae:module:help:APropos' => 'About',
            '::messae:module:help:APropos:Text' => 'The messae project is a software implementation based on nebule project.<br />
This php implementation is intended to be a reference of the potential of objects and relationships as defined in nebule.<br />
<br />
Current computer systems are unable to directly manage objects and links. It is thus not possible to use native nebule.
The messae project provides access to this new way of managing our information without questioning fundamentally the organization including the operating system of our information systems.<br />
<br />
The messae interface is a web page to be set up on a server to handle objects and links.
This all sounds a lot to what is already commonly exist: Google (and its galaxy of sites), Facebook, Twitter, Outlook, Yahoo and countless competitors and pretenders…
All these sites are generally more competitive than complementary, and all without exception are closed to competitors.
This means to the user by the need to either have an account on each site, or attend only some of them, to see one.
This also results in the inability to directly exchange data and information from one site to another.<br />
<br />
The project messae reproduced concentration data to servers on the Internet.
It is expected to be natively installed on any web server.
And, based on the principles of nebule, any server hosting messae can natively:<br />
 1. manage the identities generated by the other server, whether a user or a robot;<br />
 2. exchange data and information with all other servers implementing nebule;<br />
 3. relaying the data and the other data servers;<br />
 4. allow any user (known to the server) to connect to it.<br />
<br />
With IPv6, we have the ability to actually connect all the machines on the Internet.
Everyone can simply set up messae at home, or continue using another Internet server.
Each individual can become active.<br />
<br />
Finally, if one day nebule extends to all machines and all these machines implement it natively, then the project messae will disappear.
He will have served its purpose: to allow a smooth transition to nebule.<br />
It will be seen as symptomatic of an era oddity.',
            '::messae:module:help:APropos:Liens' => 'See also :<br /><a href="http://blog.messae.org/">The blog of messae projet</a><br /><a href="http://blog.nebule.org/">the blog of nebule projet</a>',
            '::messae:module:help:AideGenerale:Text' => 'The software is composed of three parts:<br />
1. el banner superior que contiene el menu de la aplicacion y la entidad actual.<br />
2. <br />
3. <br />
<br />
<br />
',
        ],
        'es-co' => [
            '::messae:module:help:ModuleName' => 'Módulo de ayuda',
            '::messae:module:help:MenuName' => 'Ayuda',
            '::messae:module:help:ModuleDescription' => 'Módulo de ayuda en línea.',
            '::messae:module:help:ModuleHelp' => 'Esta aplicatión te permite ver la ayuda general sobre la interfaz.',
            '::messae:module:help:AppTitle1' => 'Ayuda',
            '::messae:module:help:AppDesc1' => 'Muestra la ayuda en línea.',
            '::messae:module:help:Bienvenue' => 'Bienviedo en <b>messae</b>.',
            '::messae:module:help:Langue' => 'Idioma',
            '::messae:module:help:ChangerLangue' => 'Cambio de idioma',
            '::messae:module:help:About' => 'About',
            '::messae:module:help:Bootstrap' => 'Bootstrap',
            '::messae:module:help:Demarrage' => 'Comienzo',
            '::messae:module:help:AideGenerale' => 'Ayuda general',
            '::messae:module:help:APropos' => 'Acerca',
            '::messae:module:help:APropos:Text' => 'Sylabe El proyecto es un proyecto basado nebule implementación de software.<br />
Esta aplicación php está pensado como una referencia del potencial de los objetos y las relaciones como se define en nebule.<br />
<br />
Sistemas informáticos actuales son incapaces de gestionar directamente los objetos y enlaces. Por tanto, no es posible utilizar nebule nativo.
El proyecto messae proporciona acceso a esta nueva forma de gestionar nuestra información sin cuestionar en profundidad la organización incluyendo el sistema operativo de nuestros sistemas de información.<br />
<br />
La interfaz messae es una página web que se creará en el servidor para manejar objetos y enlaces.
Todo esto suena muy parecido a lo que ya es común: Google (y su galaxia de sitios), Facebook, Twitter, Outlook, Yahoo e innumerables competidores y pretendientes…
Todos estos sitios son generalmente más competitivas que complementarias, y todo sin excepción están cerrados a la competencia.
Esto se traduce en el usuario por la necesidad, ya sea tener una cuenta en cada sitio, o asistir sólo a algunos de ellos, para ver uno.
Esto también resulta en la incapacidad para intercambiar directamente datos y la información de un sitio a otro.<br />
<br />
El messae proyecto reproduce datos de concentración a los servidores de Internet.
Se espera que esté instalado de forma nativa en cualquier servidor web.
Y, en base a los principios de nebule, cualquier servidor de alojamiento messae puede nativa:<br />
 1. gestionar las identidades generadas por el otro servidor, si un usuario o un robot;<br />
 2. el intercambio de datos e información con el resto de servidores de aplicación nebule;<br />
 3. la retransmisión de los datos y los otros servidores de datos;<br />
 4. permitir a cualquier usuario (conocidos por el servidor) para conectarse a él.<br />
<br />
Con IPv6, tenemos la capacidad de conectarse en realidad todas las máquinas en Internet.
Todo el mundo puede simplemente configurar messae casa, o continuar utilizando otro servidor de Internet.
Cada individuo puede llegar a ser activo.<br />
<br />
Por último, si un día nebule se extiende a todas las máquinas y todas estas máquinas implementar de forma nativa, entonces el proyecto messae desaparecer.
Él habrá cumplido su propósito: permitir una transición suave a nebule.
Se verá como un síntoma de una rareza era.',
            '::messae:module:help:APropos:Liens' => 'Ver también :<br /><a href="http://blog.messae.org/">El blog del proyecto messae</a><br /><a href="http://blog.nebule.org/">El blog del proyecto nebule</a>',
            '::messae:module:help:AideGenerale:Text' => 'El software se compone de tres partes:<br />
1. el banner superior que contiene el menu de la aplicacion y la entidad actual.<br />
2. <br />
3. <br />
<br />
<br />
',
        ],
    ];

}
