<?php
declare(strict_types=1);
namespace Nebule\Application\Qantion;
use Nebule\Library\DisplayInformation;
use Nebule\Library\Entity;
use Nebule\Library\Metrology;
use Nebule\Library\nebule;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
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
 * Class Application for qantion
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications
{
    const APPLICATION_NAME = 'qantion';
    const APPLICATION_SURNAME = 'nebule/qantion';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250111';
    const APPLICATION_LICENCE = 'GNU GPL 2019-2025';
    const APPLICATION_WEBSITE = 'www.qantion.org';
    const APPLICATION_NODE = '20a04016698cd3c996fa69e90bbf3e804c582b8946a5d60e9880cdb24b36b5d376208939.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = false;
    const LIST_MODULES_INTERNAL = array();
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
    const DEFAULT_APPLICATION_LOGO = "data:image/png;base64,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
	";
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

    protected function _initialisation(): void
    {
        /*$this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_metrologyInstance->addLog('Load displays', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000'); // Log
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_actionInstance = $this->_applicationInstance->getActionInstance();
        $this->_unlocked = $this->_entitiesInstance->getCurrentEntityIsUnlocked();*/

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
        if ($this->_translateInstance !== null) {
            $this->_currentDisplayLanguage = $this->_translateInstance->getCurrentLanguage();
            $this->_currentDisplayLanguageInstance = $this->_translateInstance->getCurrentLanguageInstance();
            $this->_displayLanguageList = $this->_translateInstance->getLanguageList();
            $this->_displayLanguageInstanceList = $this->_translateInstance->getLanguageModuleInstanceList();
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
    private function _findLogoApplication()
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
    private function _findLogoApplicationLink()
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
    private function _findLogoApplicationName()
    {
        $this->_logoApplicationName = Application::APPLICATION_NAME;
        // @todo
    }


    /**
     * Vérifie que toutes les icônes déclarées soient présentes.
     * Sinon les synchronises.
     */
    private function _checkDefinedIcons()
    {
        // @todo
    }

    /**
     * Code before display.
     */
    protected function _preDisplay(): void
    {
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
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
            echo $this->_translateInstance->getTranslate('::::HtmlHeadDescription'); ?>"/>
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

            .qantionMenuListContentActionModules {
                background: rgba(0, 0, 0, 0.5);
                color: #ffffff;
            }

            .qantionMenuListContentActionHooks {
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
    private function _displayActions()
    {
        ?>

        <div class="layout-footer footer<?php if ($this->_unlocked) {
            echo 'Unlock';
        } ?>">
            <p>
                <?php
                $this->_actionInstance->getDisplayActions();
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
    private function _displayHeader()
    {
        ?>

        <div class="layout-header header<?php if ($this->_unlocked) {
            echo 'Unlock';
        } ?>">
            <div class="header-left">
                <?php
                if ($this->_configurationInstance->getOptionAsBoolean('permitJavaScript')) {
                    ?>
                    <img src="<?php echo $this->_logoApplication; ?>" alt="[Q]"
                         title="<?php echo $this->_translateInstance->getTranslate('::menu'); ?>"
                         onclick="display_menu('layout-menu-applications');"/>
                    <?php
                } else {
                    ?>
                    <a href="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->getCurrentDisplayMode() . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW; ?>=menu">
                        <img src="<?php echo $this->_logoApplication; ?>" alt="[Q]"
                             title="<?php echo $this->_translateInstance->getTranslate('::menu'); ?>"/>
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
    private function _displayHeaderCenter()
    {
        //...
    }


    /**
     * Affiche le menu des applications.
     */
    private function _displayMenuApplications()
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
                <img src="<?php echo $this->_logoApplication; ?>" alt="[Q]"
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
    private function _displayInternalMenuApplications()
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
                    $list[$j]['class'] = 'qantionMenuListContentActionModules';
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
                            $list[$j]['class'] = 'qantionMenuListContentActionHooks';
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
                            $list[$j]['class'] = 'qantionMenuListContentActionHooks';
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
                            $list[$j]['class'] = 'qantionMenuListContentActionHooks';
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
                    $list[$j]['class'] = 'qantionMenuListContentActionModules';
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
        $list[$j]['class'] = 'qantionMenuListContentActionModules';

        echo $this->getDisplayMenuList($list, 'Medium');
    }


    /**
     * Affiche les alertes.
     */
    private function _displayChecks()
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
    private function _displayContent()
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
    private function _displayMetrology()
    {
        if ($this->_configurationInstance->getOptionUntyped('qantionDisplayMetrology')) {
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
                    echo $this->_translateInstance->getTranslate('%01.0f liens lus,', (string)$this->_metrologyInstance->getLinkRead());
                    echo ' ';
                    echo $this->_translateInstance->getTranslate('%01.0f liens vérifiés,', (string)$this->_metrologyInstance->getLinkVerify());
                    echo ' ';
                    echo $this->_translateInstance->getTranslate('%01.0f objets lus.', (string)$this->_metrologyInstance->getObjectRead());
                    echo ' ';
                    echo $this->_translateInstance->getTranslate('%01.0f objets vérifiés.', (string)$this->_metrologyInstance->getObjectVerify());
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
                    $qantionTimeList = $this->_metrologyInstance->getTimeArray();
                    $qantion_time_total = 0;
                    foreach ($qantionTimeList as $time) {
                        $qantion_time_total = $qantion_time_total + $time;
                    }
                    echo $this->_translateInstance->getTranslate('Le serveur à pris %01.4fs pour calculer la page.', $qantion_time_total);
                    echo ' (';
                    foreach ($qantionTimeList as $time) {
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
    private function _displayFooter()
    {
        ?>

        </body>
        </html>
        <?php
    }


    /* --------------------------------------------------------------------------------
	 *  Affichage des objets.
	 * -------------------------------------------------------------------------------- */
    public function displayObjectDivHeaderH1(string $object, string $help = '', string $desc = '')
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
                        $msg = $this->_translateInstance->getTranslate('::::display:content:errorNotAvailable');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'tooBig':
                        if ($this->_configurationInstance->getOptionUntyped('qantionDisplayUnverifyLargeContent')) {
                            $msg = $this->_translateInstance->getTranslate('::::display:content:warningTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        } else {
                            $msg = $this->_translateInstance->getTranslate(':::display:content:errorTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        }
                        break;
                    case 'warning':
                        $msg = $this->_translateInstance->getTranslate('::::display:content:warningTaggedWarning');
                        $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        break;
                    case 'danger':
                        $msg = $this->_translateInstance->getTranslate('::::display:content:errorBan');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'notAnObject':
                        $msg = $this->_translateInstance->getTranslate('::::display:content:notAnObject');
                        $this->displayIcon(self::DEFAULT_ICON_ALPHA_COLOR, $msg, 'iconNormalDisplay');
                        break;
                    default:
                        $msg = $this->_translateInstance->getTranslate('::::display:content:OK');
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
    private function _displayDivOnlineHelp($help)
    {
        // Si authorisé à afficher l'aide.
        if ($this->_configurationInstance->getOptionUntyped('qantionDisplayOnlineHelp')) {
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
    public function displayDivTextTitle($icon, $title = '', $desc = '', $help = '')
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
    public function displayDivTextTitleH1($icon, $title = '', $desc = '', $help = '')
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
    public function displayDivTextTitleH2($icon, $title = '', $desc = '', $help = '')
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
    // Tout par défaut.
}
