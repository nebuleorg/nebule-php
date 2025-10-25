<?php
declare(strict_types=1);
namespace Nebule\Application\Entity;
use Nebule\Library\ModelModuleHelp;
use Nebule\Library\applicationInterface;
use Nebule\Library\DisplayInformation;
use Nebule\Library\DisplayList;
use Nebule\Library\DisplayItem;
use Nebule\Library\DisplayTitle;
use Nebule\Library\DisplayQuery;
use Nebule\Library\DisplayItemIconMessage;
use Nebule\Library\DisplayObject;
use Nebule\Library\Entity;
use Nebule\Library\Metrology;
use Nebule\Library\nebule;
use Nebule\Library\Actions;
use Nebule\Library\ActionsEntities;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Modules;
use Nebule\Library\Node;
use Nebule\Library\References;
use Nebule\Library\Translates;
use Nebule\Library\ModuleTranslates;
use const Nebule\Bootstrap\BOOTSTRAP_NAME;
use const Nebule\Bootstrap\BOOTSTRAP_SURNAME;
use const Nebule\Bootstrap\BOOTSTRAP_WEBSITE;
use const Nebule\Bootstrap\LIB_BOOTSTRAP_ICON;

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
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020251025';
    const APPLICATION_LICENCE = 'GNU GPL 2025-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '206090aec4ba9e2eaa66737d34ced59cfe73b8342fc020efbd321eded7c8b46440e0875a.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = true;
    const LIST_MODULES_INTERNAL = array(
        'ModuleHelp',
        'ModuleEntities',
        'ModuleLang',
        'ModuleTranslateFRFR'
    );
    const LIST_MODULES_EXTERNAL = array();

    public function __construct(nebule $nebuleInstance)
    {
        parent::__construct($nebuleInstance);
    }

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
    const DEFAULT_APPLICATION_LOGO = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAABg2lDQ1BJQ0MgcHJvZmlsZQAAKJF9
kT1Iw0AcxV/TiiIVh2YQcchQnayIijhqFYpQIdQKrTqYXPoFTRqSFBdHwbXg4Mdi1cHFWVcHV0EQ/ABxdnBSdJES/9cUWsR4cNyPd/ced+8AoV5muh0aB3TDsVKJuJTJrkrdrxAgIoIxhBRmm3OynITv
+LpHgK93MZ7lf+7P0aflbAYEJOJZZloO8Qbx9KZjct4nFllR0YjPiUctuiDxI9dVj984F5os8EzRSqfmiUViqdDBagezoqUTTxFHNd2gfCHjscZ5i7NerrLWPfkLwzljZZnrNIeQwCKWIEOCiipKKMNB
jFaDFBsp2o/7+AebfplcKrlKYORYQAU6lKYf/A9+d2vnJye8pHAc6Hpx3Y9hoHsXaNRc9/vYdRsnQPAZuDLa/kodmPkkvdbWokdA/zZwcd3W1D3gcgcYeDIVS2lKQZpCPg+8n9E3ZYHILdC75vXW2sfp
A5CmrpI3wMEhMFKg7HWfd/d09vbvmVZ/P1facpxfT0PtAAAABmJLR0QAAAAAAAD5Q7t/AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH6AIUEzUQMp5vHAAABltJREFUeNrtm09oE1kcx3/zOrbT
F52QNiUuoVUjsrYW04NV7CnBhsKIgqSOCx4WYi7x0B68esghh3oopZeGigS3HlYGYsGawPYPcS9dNLBspNZFMYoSaLAlJt0MSTvO7MFVgpukkz8zSVN/EAgJvJnv5/3ee7/3e79HAIAb9rAh2OP2HcBe
B0CqThwhIEmSQAgRCH3mL4oiiKIoCYIgiaLYWABomm5iGMZgtVo7e3p6DhqNxnatVnsAY0yRJLkPAEAQhG2e5zPJZHIzFottrK6uroVCoffBYDCeSqU+Kfl+hBKrAMYYOZ3OIyzL9prN5mMajUZDEERJ
bUiSBOl0Oh2JRF5xHLdy586dNzzPi3UNwGAwNLvd7j673X5ar9e3lyq6GIz19fUNv9//1O12/xWPx7fqCgBFUcjj8Zx0OBwWnU6nVdJlE4lE0ufzPb558+azTCYj1hyAzWbTe73eCyaTqataPS7HI6LR
6DuXyzW3sLCwXklbTQBgKXc2v3XrlnlycvKnjo6ONrXEAwAQBAFtbW1almX7aJreXFpaikuSpJ4HYIwRx3HnGIY5S6ipPL83SMFg8A+WZZfKmSRLBkDTdNPi4uKl/v7+E/UU0ITD4eeDg4OzpS6bqNSe
r0fxAAD9/f0nFhcXL2GMkSIAEELAcdy5ehSfC4HjuHNfIsyqAhgbGzMzDHP228moks/MzAzMzMxU3E6uMQxzdmxszFxVADabTT8yMsJUe8KLRqMQjUarvUIQIyMjjM1m01cFAEVRyOv1XmhpaWneLTu8
lpaWZq/Xe4GiKFQxAI/Hc9JkMnXttm2uyWTq8ng8JysCYDAYmh0Oh6XGS33ZwZLD4bAYDIbmsgG43e4+pWN7JU2n02ndbndfWQAwxshut5/e7Rkfu91+ulhsUPAPp9N5RK/Xt+92AHq9vt3pdB4pGQDL
sr27ceznmwtYlu0tCQBN001ms/kYNIiZzeZjNE03yQbAMIxBo9FoGgWARqPRMAxjkA3AarV2NoL75w4Dq9XaKRtAT0/PQWgwK6QJ5dv1GY3G9kYDYDQa2/PtEv/3C0mShFarPdBoALRa7QGSJAk5HkBg
jKlGA4AxphBCsgDAlxObRjKSJPflGwJkrV4IIQTHjx//+l3tM8GCAERRBEEQtkmSbFJS/Pj4OFy5cgUAANbW1uDGjRuKQhAEYTtf+ygPAInn+YzS4kdHR7+mtEZHR2F8fBxKyeWVajzPZ0RRlHYEIAiC
lEwmN9UQnxuoKA0hmUxuCoIgyfEAiMViG2qJVwtCLBbbkDUEAABWV1fX1BSvBoRCmvI+JRQKvS/3rK1c8UpCkCQJQqHQe9kAgsFgPJ1Op9UWrxSEdDqdDgaDcdkAUqnUp0gk8qoW4pWAEIlEXhU6MyzY
MsdxK+UOg0rFVxOCJEnAcdxKwWdAgdNhjDF6+/bt9Y6OjvZiL6iU+G9FTE5OFgyWinXUhw8fNg4fPjxV6OgcFQkcRL/f/7QWPV9NT/D7/U+L1Q0UrQ8wGAzNL168uF7obCBXpFLi5XhCIQ9IJBLJ7u7u
qWJFVUVxxuPxLZ/P93inuUAN8aV6giRJ4PP5Hu9UUbZjhQhFUWhlZeXno0ePduV7IbXEF/OEfB30+vXrd729vb/sVEm244DKZDKiy+Way2azeUmqLf5bT8hn2Wx2y+Vyzckpo5NVJRaNRnmapjcHBgZ+
zK0RGBoaguHhYahFBpkgCDhz5gwMDQ1BZ2dnrndIExMTj6anp9/IagdkFkkhhODhw4e28+fPD9Rz5icQCCxfvHhxQW5uQfaaIooisCy7FA6Hn9er+HA4/Jxl2aVSEislLao8z4uDg4Oz9QjhS5lcqbWC
JceXqVTqk8VieRAIBJalam0ZK1sRpEAgsGyxWB6UU1pfVqns9va2dP/+/SjG+OOpU6dMSuYPi1k2m92amJh4dO3ateWtra2yOqPsWmFJkmBhYSH+5MmTvwcGBg7qdDqt2sXSV69e/XV6evpNJY5YNoDc
JfL27dvPWltbP3Z3d//Q2tqq6KFKIpFITk1N/TY8PDz/8uXLinMWFQMA+JxInZ+fX7t79+6f+/fv/+fQoUNtGGNc7QsT9+7d+/3y5ctzs7OzsXwJzrLiCfh+ZUbZm6N78tLUThHlnro2ly+i/G/JqnkM
UVYg1Gj2HcBeB/AvE200xGvbl50AAAAASUVORK5CYII=";
    const DEFAULT_APPLICATION_LOGO_LINK = '?mod=log&view=about';
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
            . '&'. self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . ModuleEntities::MODULE_COMMAND_NAME
            . '&'. self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . ModuleEntities::MODULE_DEFAULT_VIEW
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
 * This module manage the help pages and default first vue.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleHelp extends \Nebule\Library\ModelModuleHelp
{
    const MODULE_TYPE = 'Application';
    const MODULE_VERSION = '020250921';

    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::module:help:ModuleName' => "Module d'aide",
            '::module:help:MenuName' => 'Aide',
            '::module:help:ModuleDescription' => "Module d'aide en ligne.",
            '::module:help:ModuleHelp' => "Ce module permet d'afficher de l'aide générale sur l'interface.",
            '::module:help:AppTitle1' => 'Aide',
            '::module:help:AppDesc1' => "Affiche l'aide en ligne.",
            '::module:help:Bienvenue' => 'Bienvenue sur <b>weblog</b>.',
            '::module:help:About' => 'A propos',
            '::module:help:Bootstrap' => 'Bootstrap',
            '::module:help:Demarrage' => 'Démarrage',
            '::module:help:AideGenerale' => 'Aide générale',
            '::module:help:APropos' => 'A propos',
            '::module:help:APropos:Text' => "Le projet <i>entity</i> est une implémentation logicielle de gestion des identités basée sur le projet nebule.<br />
Cette implémentation en php est voulue comme une référence des possibilités offertes par les objets et les liens tels que définis dans nebule.",
            '::module:help:AideGenerale:Text' => "Le logiciel est composé de trois parties :<br />
1. le bandeau du haut qui contient le menu de l'application et l'entité en cours.<br />
2. la partie centrale qui contient le contenu à afficher, les objets, les actions, etc...<br />
3. le bandeau du bas qui apparaît lorsqu'une action est réalisée.<br />
<br />
D'un point de vue général, tout ce qui est sur fond clair concerne une action en cours ou l'objet en cours d'utilisation. Et tout ce qui est sur fond noir concerne l'interface globale ou d'autres actions non liées à ce que l'on fait.<br />
Le menu en haut à gauche est le meilleur moyen de se déplacer dans l'interface.",
        ],
        'en-en' => [
            '::module:help:ModuleName' => 'Help module',
            '::module:help:MenuName' => 'Help',
            '::module:help:ModuleDescription' => 'Online help module.',
            '::module:help:ModuleHelp' => 'This module permit to display general help about the interface.',
            '::module:help:AppTitle1' => 'Help',
            '::module:help:AppDesc1' => 'Display online help.',
            '::module:help:Bienvenue' => 'Welcome to <b>weblog</b>.',
            '::module:help:About' => 'About',
            '::module:help:Bootstrap' => 'Bootstrap',
            '::module:help:Demarrage' => 'Start',
            '::module:help:AideGenerale' => 'General help',
            '::module:help:APropos' => 'About',
            '::module:help:APropos:Text' => 'The <i>entity</i> project is a software implementation to manage identities based on the nebule project.<br />
This PHP implementation is intended to serve as a reference for the possibilities offered by objects and links as defined in nebule.',
            '::module:help:AideGenerale:Text' => "The software is composed of three parts:<br />
1. The top banner, which contains the application menu and the current entity.<br />
2. The central part, which contains the content to display, objects, actions, etc...<br />
3. The bottom banner, which appears when an action is performed.<br />
<br />
From a general point of view, everything on a light background relates to an ongoing action or the object currently in use. And everything on a dark background relates to the global interface or other actions unrelated to what you're doing.<br />
The menu at the top left is the best way to navigate the interface.",
        ],
        'es-co' => [
            '::module:help:ModuleName' => 'Módulo de ayuda',
            '::module:help:MenuName' => 'Ayuda',
            '::module:help:ModuleDescription' => 'Módulo de ayuda en línea.',
            '::module:help:ModuleHelp' => 'Esta modulo te permite ver la ayuda general sobre la interfaz.',
            '::module:help:AppTitle1' => 'Ayuda',
            '::module:help:AppDesc1' => 'Muestra la ayuda en línea.',
            '::module:help:Bienvenue' => 'Bienviedo en <b>weblog</b>.',
            '::module:help:About' => 'About',
            '::module:help:Bootstrap' => 'Bootstrap',
            '::module:help:Demarrage' => 'Comienzo',
            '::module:help:AideGenerale' => 'Ayuda general',
            '::module:help:APropos' => 'Acerca',
            '::module:help:APropos:Text' => 'El proyecto <i>entity</i> es una implementación de software de gestión de identidad basado en el proyecto nebule.<br />
Esta implementación en PHP está diseñada como una referencia de las posibilidades que ofrecen los objetos y los enlaces tal como se definen en nebule.',
            '::module:help:AideGenerale:Text' => "El software se compone de tres partes:<br />
1. La banda superior, que contiene el menú de la aplicación y la entidad actual.<br />
2. La parte central, que contiene el contenido a mostrar, los objetos, las acciones, etc...<br />
3. La banda inferior, que aparece cuando se realiza una acción.<br />
<br />
Desde un punto de vista general, todo lo que está sobre un fondo claro está relacionado con una acción en curso o el objeto que se está utilizando. Y todo lo que está sobre un fondo oscuro se refiere a la interfaz global u otras acciones no relacionadas con lo que se está haciendo.<br />
El menú en la parte superior izquierda es la mejor manera de navegar por la interfaz.",
        ],
    ];
}
