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

/*
* CSS from <?= basename(str_replace('\\', '/', __CLASS__)) ?>::<?= __FUNCTION__ ?>() on nebule library.
*
* (c) <?= \Nebule\Library\nebule::NEBULE_LICENCE ?> <?= \Nebule\Library\nebule::NEBULE_AUTHOR ?> - <?php echo \Nebule\Library\nebule::NEBULE_WEBSITE . "\n"; ?>
*
* This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
*   License as published by the Free Software Foundation, either version 3 of the License or (at your option) any
*   later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
*   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
*   details.
*
* You should have received a copy of the GNU General Public License along with this program.
*   See at the end of the code file. If not, see https://www.gnu.org/licenses/.
*/
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

        <?php
    }
}
