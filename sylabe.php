<?php
declare(strict_types=1);
namespace Nebule\Application\Sylabe;
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
 * Class Application for sylabe
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications implements ApplicationInterface
{
    const APPLICATION_NAME = 'sylabe';
    const APPLICATION_SURNAME = 'nebule/sylabe';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020251231';
    const APPLICATION_LICENCE = 'GNU GPL v3 2013-2025';
    const APPLICATION_WEBSITE = 'www.sylabe.org';
    const APPLICATION_NODE = 'c02030d3b77c52b3e18f36ee9035ed2f3ff68f66425f2960f973ea5cd1cc0240a4d28de1.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = true;
    const LIST_MODULES_INTERNAL = array(
        'ModuleHelp',
        'ModuleManage',
        'ModuleAdmin',
        'ModuleObjects',
        'ModuleGroups',
        'ModuleGroupEntities',
        'ModuleEntities',
        'ModuleMessages',
        'ModuleNeblog',
        'ModuleQantion',
        'ModuleLang',
        'ModuleTranslateDEDE',
        'ModuleTranslateENEN',
        'ModuleTranslateESCO',
        'ModuleTranslateESES',
        'ModuleTranslateFRFR',
        'ModuleTranslateITIT',
        'ModuleTranslatePLPL',
        'ModuleTranslateUAUA',
    );
    const LIST_MODULES_EXTERNAL = array();

    const APPLICATION_DEFAULT_DISPLAY_ONLINE_HELP = true;
    const APPLICATION_DEFAULT_DISPLAY_ONLINE_OPTIONS = false;
    const APPLICATION_DEFAULT_DISPLAY_METROLOGY = false;
    const APPLICATION_DEFAULT_DISPLAY_UNVERIFY_LARGE_CONTENT = false;
    const APPLICATION_DEFAULT_DISPLAY_NAME_SIZE = 128;
    const APPLICATION_DEFAULT_IO_READ_MAX_DATA = 1000000;
    const APPLICATION_DEFAULT_PERMIT_UPLOAD_OBJECT = false;
    const APPLICATION_DEFAULT_PERMIT_UPLOAD_LINKS = false;
    const APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_OBJECT = false;
    const APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_LINKS = false;
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
    const DEFAULT_APPLICATION_LOGO = "data:image/png;base64,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";
    const DEFAULT_APPLICATION_LOGO_LINK = '?dm=hlp&dv=about';
    const DEFAULT_LOGO_MENUS = '15eb7dcf0554d76797ffb388e4bb5b866e70a3a33e7d394a120e68899a16c690.sha2.256';
    const DEFAULT_CSS_BACKGROUND = '906da8f91f664b5bff2b23fb3f8bad69d2641932031594a656de6ce618e3404d.sha2.256';

    // Icônes de marquage.
    const DEFAULT_ICON_MARK = '65fb7dbaaa90465da5cb270da6d3f49614f6fcebb3af8c742e4efaa2715606f0.sha2.256';
    const DEFAULT_ICON_UNMARK = 'ee1d761617468ade89cd7a77ac96d4956d22a9d4cbedbec048b0c0c1bd3d00d2.sha2.256';
    const DEFAULT_ICON_UNMARKALL = 'fa40e3e73b9c11cb5169f3916b28619853023edbbf069d3bd9be76387f03a859.sha2.256';

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

    protected function _initUrlLinks(): void
    {
        $this->setUrlLinkPrefix('Nebule\Library\Node',
            '?a=' . $this->_applicationInstance::APPLICATION_NODE . '&'
            . self::COMMAND_DISPLAY_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkPrefix('Nebule\Library\Group',
            '?a=' . $this->_applicationInstance::APPLICATION_NODE . '&'
            . self::COMMAND_DISPLAY_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkPrefix('Nebule\Library\Entity',
            '?a=' . $this->_applicationInstance::APPLICATION_NODE . '&'
            . self::COMMAND_DISPLAY_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_ENTITY . '='); // TODO verify
        // FIXME continue...
    }



    /*
	 * --------------------------------------------------------------------------------
	 * La personnalisation.
	 * --------------------------------------------------------------------------------

    /**
     * Affichage du style CSS.
     */
    public function displayCSS(): void {
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

    /* --------------------------------------------------------------------------------
	 *  Affichage des objets.
	 * -------------------------------------------------------------------------------- */
    public function displayObjectDivHeaderH1($object, $help = '', $desc = '')
    {
        $object = $this->_applicationInstance->getTypedInstanceFromNID($object);
        // Prépare le type mime.
        $typemime = $object->getType('all');
        if ($desc == '') {
            $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($typemime);
        }

        // Détermine si c'est une entité.
        $objHead = $object->readOneLineAsText(\Nebule\Library\Entity::ENTITY_MAX_SIZE);
        $isEntity = ($typemime == \Nebule\Library\Entity::ENTITY_TYPE && strpos($objHead, References::REFERENCE_ENTITY_HEADER) !== false);

        // Détermine si c'est un groupe.
        $isGroup = $object->getIsGroup('all');

        // Détermine si c'est une conversation.
        $isConversation = $object->getIsConversation('all');

        // Modifie le type au besoin.
        if ($isEntity && !is_a($object, 'Entity')) {
            $object = $this->_cacheInstance->newNode($object->getID(), \Nebule\Library\Cache::TYPE_ENTITY);
        }
        if ($isGroup && !is_a($object, 'Group')) {
            $object = $this->_cacheInstance->newNode($object->getID(), \Nebule\Library\Cache::TYPE_GROUP);
        }
        if ($isConversation && !is_a($object, 'Conversation')) {
            $object = $this->_cacheInstance->newNode($object->getID(), \Nebule\Library\Cache::TYPE_CONVERSATION);
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
                $help = '::display:default:help:Entity';
            } elseif ($isConversation) {
                $help = '::display:default:help:Conversation';
            } elseif ($isGroup) {
                $help = '::display:default:help:Group';
            } else {
                $help = '::display:default:help:Object';
            }
        }
        ?>

        <div class="textTitle">
            <?php
            $this->_displayDivOnlineHelp_DEPRECATED($help);
            ?>

            <div class="floatRight">
                <?php
                switch ($status) {
                    case 'notPresent':
                        $msg = $this->_translateInstance->getTranslate('::display:content:errorNotAvailable');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'tooBig':
                        if ($this->_configurationInstance->getOptionUntyped('sylabeDisplayUnverifyLargeContent')) {
                            $msg = $this->_translateInstance->getTranslate('::display:content:warningTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        } else {
                            $msg = $this->_translateInstance->getTranslate('::display:content:errorTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        }
                        break;
                    case 'warning':
                        $msg = $this->_translateInstance->getTranslate('::display:content:warningTaggedWarning');
                        $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        break;
                    case 'danger':
                        $msg = $this->_translateInstance->getTranslate('::display:content:errorBan');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'notAnObject':
                        $msg = $this->_translateInstance->getTranslate('::display:content:notAnObject');
                        $this->displayIcon(self::DEFAULT_ICON_ALPHA_COLOR, $msg, 'iconNormalDisplay');
                        break;
                    default:
                        $msg = $this->_translateInstance->getTranslate('::display:content:OK');
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
}



class Action extends Actions {}

class Translate extends Translates {}



class ModuleHelp extends ModelModuleHelp {
    const MODULE_TYPE = 'Application';
    const MODULE_VERSION = '020251227';

    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => "Module d'aide",
            '::MenuName' => 'Aide',
            '::ModuleDescription' => "Module d'aide en ligne.",
            '::ModuleHelp' => "Ce module permet d'afficher de l'aide générale sur l'interface.",
            '::AppTitle1' => 'Aide',
            '::AppDesc1' => "Affiche l'aide en ligne.",
            '::Bienvenue' => 'Bienvenue sur <b>sylabe</b>.',
            '::About' => 'A propos',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Démarrage',
            '::AideGenerale' => 'Aide générale',
            '::APropos' => 'A propos',
            '::APropos:Text' => "Le projet <i>sylabe</i> est une implémentation logicielle basée sur le projet nebule.<br />
Cette implémentation en php est voulue comme une référence des possibilités offertes par les objets et les liens tels
que définis dans nebule.<br />
<br />
Le projet <i>nebule</i> crée un réseau. Non un réseau de machines, mais un réseau de données.<br />
<br />
Les systèmes informatiques actuels sont incapables de gérer directement les objets et les liens. Il n’est donc pas
possible d’utiliser nebule nativement.
Le projet sylabe permet un accès à cette nouvelle façon de gérer nos informations sans remettre en question
fondamentalement l’organisation et notamment les systèmes d’exploitation de notre système d’information.<br />
<br />
L’interface sylabe est une page web destinée à être mise en place sur un serveur pour manipuler des objets et des liens.
Cela s’apparente tout à fait à ce qui se fait déjà communément : Google (et sa galaxie de sites), Facebook, Twitter,
Outlook, Yahoo et leurs innombrables concurrents et prétendants…
Tous ces sites sont globalement plus concurrents que complémentaires, et tous sans exception sont fermés à leurs
concurrents.
Cela se traduit pour l’utilisateur par la nécessité de, soit disposer d’un compte sur chaque site, soit de ne fréquenter
que certains d’entre eux, voir un seul.
Cela se traduit aussi par l’impossibilité d’échanger directement des données et informations d’un site à l’autre.<br />
<br />
Le projet sylabe reproduit la concentration des données vers des serveurs sur l’Internet.
Il est nativement prévu pour pouvoir être implanté sur n’importe quel serveur web.
Et, se basant sur les principes de nebule, tout serveur hébergeant sylabe peut nativement :<br />
 1. gérer les identités générées par les autres serveurs, que ce soit un utilisateur ou un robot ;<br />
 2. échanger des données et des informations avec tous les autres serveurs implémentant nebule ;<br />
 3. relayer les données et les informations d’autres serveurs ;<br />
 4. permettre à tout utilisateur (connu du serveur) de s’y connecter.<br />
<br />
Grâce à IPv6, nous avons la possibilité de réellement connecter toutes les machines sur l’Internet.
Chacun peut ainsi mettre en place simplement sylabe chez lui, ou continuer à l’utiliser sur un autre serveur de
l’Internet.
Chacun peut devenir individuellement actif.<br />
<br />
Enfin, si un jour nebule s’étend à toutes les machines et que toutes ces machines l’implémentent nativement, alors le
projet sylabe disparaîtra.
Il aura rempli sa mission : permettre une transition douce vers nebule.<br />
Il sera vu comme bizarrerie symptomatique d’une époque.",
            '::AideGenerale:Text' => "Le logiciel est composé de trois parties :<br />
1. le bandeau du haut qui contient le menu de l'application et l'entité en cours.<br />
2. la partie centrale qui contient le contenu à afficher, les objets, les actions, etc...<br />
3. le bandeau du bas qui apparaît lorsqu'une action est réalisée.<br />
<br />
D'un point de vue général, tout ce qui est sur fond clair concerne une action en cours ou l'objet en cours
d'utilisation. Et tout ce qui est sur fond noir concerne l'interface globale ou d'autres actions non liées à ce que l'on
fait.<br />
Le menu en haut à gauche est le meilleur moyen de se déplacer dans l'interface.",
        ],
        'en-en' => [
            '::ModuleName' => 'Help module',
            '::MenuName' => 'Help',
            '::ModuleDescription' => 'Online help module.',
            '::ModuleHelp' => 'This module permit to display general help about the interface.',
            '::AppTitle1' => 'Help',
            '::AppDesc1' => 'Display online help.',
            '::Bienvenue' => 'Welcome to <b>sylabe</b>.',
            '::About' => 'About',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Start',
            '::AideGenerale' => 'General help',
            '::APropos' => 'About',
            '::APropos:Text' => 'The <i>sylabe</i> project is a software implementation based on nebule project.<br />
This php implementation is intended to be a reference of the potential of objects and relationships as defined in nebule.<br />
<br />
The <i>nebule</i> project creates a network. Not a network of computers but a network of data.<br />
<br />
Current computer systems are unable to directly manage objects and links. It is thus not possible to use native nebule.
The sylabe project provides access to this new way of managing our information without questioning fundamentally the
organisation, including the operating system of our information systems.<br />
<br />
The sylabe interface is a web page to be set up on a server to handle objects and links.
This all sounds a lot to what is already commonly exists: Google (and its galaxy of sites), Facebook, Twitter, Outlook,
Yahoo and countless competitors and pretenders…
All these sites are generally more competitive than complementary, and all without exception are closed to competitors.
This means to the user by the need to either have an account on each site or attend only some of them, to see one.
This also results in the inability to directly exchange data and information from one site to another.<br />
<br />
The project sylabe reproduced concentration data to servers on the Internet.
It is expected to be natively installed on any web server.
And, based on the principles of nebule, any server hosting sylabe can natively:<br />
 1. manage the identities generated by the other server, whether a user or a robot;<br />
 2. exchange data and information with all other servers implementing nebule;<br />
 3. relaying the data and the other data servers;<br />
 4. allow any user (known to the server) to connect to it.<br />
<br />
With IPv6, we have the ability to actually connect all the machines on the Internet.
Everyone can simply set up sylabe at home or continue using another Internet server.
Each individual can become active.<br />
<br />
Finally, if one day nebule extends to all machines and all these machines implement it natively, then the project sylabe
will disappear.
He will have served its purpose: to allow a smooth transition to nebule.<br />
It will be seen as symptomatic of an era of oddity.',
            '::AideGenerale:Text' => "The software is composed of three parts:<br />
1. The top banner, which contains the application menu and the current entity.<br />
2. The central part, which contains the content to display, objects, actions, etc...<br />
3. The bottom banner, which appears when an action is performed.<br />
<br />
From a general point of view, everything on a light background relates to an ongoing action or the object currently in
use. And everything on a dark background relates to the global interface or other actions unrelated to what you're
doing.<br />
The menu at the top left is the best way to navigate the interface.",
        ],
        'es-co' => [
            '::ModuleName' => 'Módulo de ayuda',
            '::MenuName' => 'Ayuda',
            '::ModuleDescription' => 'Módulo de ayuda en línea.',
            '::ModuleHelp' => 'Esta modulo te permite ver la ayuda general sobre la interfaz.',
            '::AppTitle1' => 'Ayuda',
            '::AppDesc1' => 'Muestra la ayuda en línea.',
            '::Bienvenue' => 'Bienviedo en <b>sylabe</b>.',
            '::About' => 'About',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Comienzo',
            '::AideGenerale' => 'Ayuda general',
            '::APropos' => 'Acerca',
            '::APropos:Text' => 'El proyecto <i>sylabe</i> es un proyecto basado nebule implementación de software.<br />
Esta aplicación php está pensado como una referencia del potencial de los objetos y las relaciones como se define en
nebule.<br />
<br />
Sistemas informáticos actuales son incapaces de gestionar directamente los objetos y enlaces. Por tanto, no es posible
utilizar nebule nativo.
El proyecto sylabe proporciona acceso a esta nueva forma de gestionar nuestra información sin cuestionar en profundidad
la organización incluyendo el sistema operativo de nuestros sistemas de información.<br />
<br />
La interfaz sylabe es una página web que se creará en el servidor para manejar objetos y enlaces.
Todo esto suena muy parecido a lo que ya es común: Google (y su galaxia de sitios), Facebook, Twitter, Outlook, Yahoo e
innumerables competidores y pretendientes…
Todos estos sitios son generalmente más competitivas que complementarias, y todo sin excepción están cerrados a la
competencia.
Esto se traduce en el usuario por la necesidad, ya sea tener una cuenta en cada sitio, o asistir solo a algunos de
ellos, para ver uno.
Esto también resulta en la incapacidad para intercambiar directamente datos y la información de un sitio a otro.<br />
<br />
El sylabe proyecto reproduce datos de concentración a los servidores de Internet.
Se espera que esté instalado de forma nativa en cualquier servidor web.
Y, con base en los principios de nebule, cualquier servidor de alojamiento sylabe puede nativa:<br />
 1. gestionar las identidades generadas por el otro servidor, si un usuario o un robot;<br />
 2. el intercambio de datos e información con el resto de servidores de aplicación nebule;<br />
 3. la retransmisión de los datos y los otros servidores de datos;<br />
 4. permitir a cualquier usuario (conocidos por el servidor) para conectarse a él.<br />
<br />
Con IPv6, tenemos la capacidad de conectarse en realidad todas las máquinas en Internet.
Todo el mundo puede simplemente configurar sylabe casa, o continuar utilizando otro servidor de Internet.
Cada individuo puede llegar a ser activo.<br />
<br />
Por último, si un día nebule se extiende a todas las máquinas y todas estas máquinas implementar de forma nativa,
entonces el proyecto sylabe desaparecer.
Él habrá cumplido su propósito: permitir una transición suave a nebule.
Se verá como un síntoma de una rareza era.',
            '::AideGenerale:Text' => "El software se compone de tres partes:<br />
1. La banda superior, que contiene el menú de la aplicación y la entidad actual.<br />
2. La parte central, que contiene el contenido a mostrar, los objetos, las acciones, etc...<br />
3. La banda inferior, que aparece cuando se realiza una acción.<br />
<br />
Desde un punto de vista general, todo lo que está sobre un fondo claro está relacionado con una acción en curso o el
objeto que se está utilizando. Y todo lo que está sobre un fondo oscuro se refiere a la interfaz global u otras acciones
no relacionadas con lo que se está haciendo.<br />
El menú en la parte superior izquierda es la mejor manera de navegar por la interfaz.",
        ],
    ];
}
