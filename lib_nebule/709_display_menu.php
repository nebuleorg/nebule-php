<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayMenu
 *       ---
 *  Example:
 *   FIXME
 *       ---
 *  Usage:
 *   FIXME
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class DisplayMenu extends DisplayItem implements DisplayInterface
{
    public function getHTML(): string
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return ''; // TODO
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayObject(). */
            .layoutMenuList {
                font-size: 0;
                padding-top: 4px;
                width: 100%;
            }

            .menuListContent {
                margin: auto;
                text-align: center;
            }

            .menuListContentActionDiv {
                display: inline-block;
            }

            .menuListContentActionDiv a:link, .menuListContentActionDiv a:visited {
                font-weight: normal;
                text-decoration: none;
            }

            .menuListContentActionDiv a:hover, .menuListContentActionDiv a:active {
                font-weight: bold;
                text-decoration: none;
            }

            .menuListContentAction {
                height: 64px;
                margin-bottom: 5px;
                margin-right: 5px;
                text-align: left;
                color: #545454;
            }

            .menuListContentActionSmall {
                width: 128px;
            }

            .menuListContentActionMedium {
                width: 295px;
            }

            .menuListContentActionLarge {
                width: 395px;
            }

            .menuListContentAction-content {
                padding: 5px;
            }

            .menuListContentAction-icon {
                float: left;
                height: 64px;
                width: 64px;
                margin-right: 5px;
            }

            .menuListContentAction-ref {
                font-size: 0.6rem;
                font-style: italic;
                overflow: hidden;
                white-space: nowrap;
            }

            .menuListContentAction-title {
                font-size: 1.1rem;
                font-weight: bold;
                overflow: hidden;
                white-space: normal;
            }

            .menuListContentAction-desc {
                font-size: 0.8rem;
                overflow: hidden;
                white-space: nowrap;
            }

            .menuListContentActionModules {
                background: rgba(0, 0, 0, 0.5);
                color: #ffffff;
            }

            .menuListContentActionHooks {
                background: rgba(255, 255, 255, 0.66);
            }
        </style>
        <?php
    }
}
