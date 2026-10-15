<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayTitle
 *       ---
 * Display or prepare a title message to the interface with the user.
 *       ---
 * Example:
 *  $instance = new DisplayTitle($this->_applicationInstance);
 *  $instance->setTitle('Title', 'arg1', 'arg2', 'arg3', 'arg4', 'arg5');
 *  $icon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_IOK);
 *  $instance->setIcon($icon);
 *  $instance->setEnableEntity(false);
 *  $instance->display();
 *       ---
 * Usage:
 *  FIXME
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class DisplayTitle extends DisplayItemIconable implements DisplayInterface
{
    private string $_title = '';
    private bool $_displayEntity = false;

    public function getHTML(): string
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_title == '')
            return '';

        $result = '<div class="layoutTitle">' . "\n";
        $result .= ' <div class="titleContent">' . "\n";
        $result .= '  <div class="titleContentDiv">' . "\n";
        $this->_getIconHTML($result);
        $this->_getTitleHTML($result);
        $result .= "  </div>\n";
        $this->_getEntityHTML($result);
        $result .= " </div>\n";
        $result .= "</div>\n";

        return $result;
    }

    private function _getIconHTML(&$result): void
    {
        if ($this->_icon === null || $this->_icon->getID() == '0')
            return;

        $result .= '<div class="titleContentIcon">';
        $result .= $this->_displayInstance->convertUpdateImage($this->_icon, $this->_title);
        $result .= '</div>' . "\n";
    }

    private function _getTitleHTML(&$result): void
    {
        $result .= '<h1>' . $this->_title . "</h1>\n";
    }

    private function _getEntityHTML(&$result): void
    {
        if ($this->_entitiesInstance->getGhostEntityEID() != $this->_entitiesInstance->getConnectedEntityEID()
            || $this->_configurationInstance->getOptionUntyped('forceDisplayEntityOnTitle')
            || $this->_displayEntity
        ) {
            $result .= '<div class="titleContentEntity">';

            // TODO rewrite this...
            $instance = new DisplayObject($this->_applicationInstance);
            $instance->setNID($this->_entitiesInstance->getGhostEntityInstance());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableRefs(false);
            $instance->setEnableName(true);
            $instance->setEnableNID(false);
            $instance->setEnableFlags(false);
            $instance->setEnableStatus(false);
            $instance->setEnableContent(false);
            $instance->setSize(DisplayItem::SIZE_SMALL);
            $instance->setRatio(DisplayItem::RATIO_SHORT);
            $instance->setEnableJS(false);
            $instance->setEnableActions(false);
            $result .= $instance->getHTML();

            $result .= '</div>' . "\n";
        }
    }

    public function setTitle(string $title,
                             string $arg1 = '',
                             string $arg2 = '',
                             string $arg3 = '',
                             string $arg4 = '',
                             string $arg5 = ''): void
    {
        $this->_title = sprintf($this->_translateInstance->getTranslate($title), $arg1, $arg2, $arg3, $arg4, $arg5);
    }

    public function setEnableEntity(bool $enable): void
    {
        $this->_displayEntity = $enable;
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
.layoutTitle {
    margin-bottom: 10px;
    margin-top: 32px;
    width: 100%;
    height: 32px;
    text-align: center;
}

.titleContent {
    margin: auto;
    text-align: center;
}

.titleContentDiv {
    display: inline-block;
    background: #333333;
    height: 32px;
    width: 384px;
}

.titleContentEntity {
    display: inline-block;
}

.titleContentEntity .layoutObject {
    margin: -11px 0 0 0;
}

.titleContentIcon {
    float: left;
}

.titleContentIcon img {
    height: 32px;
    width: 32px;
    margin-right: 5px;
}

.titleContent h1 {
    font-size: 1.2rem;
    font-weight: bold;
    color: #ababab;
    overflow: hidden;
    white-space: nowrap;
    margin-top: 5px;
}

        <?php
    }
}
