<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayList
 *       ---
 * Display a list of mix with DisplayInformation, DisplayObject, DisplaySecurity, DisplayQuery and DisplayBlankLine.
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
class DisplayList extends DisplayItem implements DisplayInterface
{
    private array $_list = array();
    private bool $_onPerLine = false;

    public function getHTML(): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (sizeof($this->_list) == 0 && $this->_enableWarnIfEmpty)
        {
            $instanceWarn = new DisplayInformation($this->_applicationInstance);
            $instanceWarn->setType(DisplayItemIconMessage::TYPE_MESSAGE);
            $instanceWarn->setMessage('::::list:empty');
            $instanceWarn->setRatio(DisplayItem::RATIO_SHORT);
            $this->_list[] = $instanceWarn;
        }
        if (sizeof($this->_list) == 0)
            return '';

        $result = '<div class="layoutList">' . "\n";
        $result .= '<div class="listContent">' . "\n";
        $result .= "\n";
        foreach ($this->_list as $item){
            $this->_nebuleInstance->getMetrologyInstance()->addLog('get code from ' . get_class($item), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '52d6f3ea');
            if ($item instanceof \Nebule\Library\DisplayInformation) {
                $item->setSize($this->_sizeCSS);
                $item->setDisplayAlone(false);
                $result .= $item->getHTML();
            } elseif ($item instanceof \Nebule\Library\DisplayObject) {
                $item->setSize($this->_sizeCSS);
                $result .= $item->getHTML();
            } elseif ($item instanceof \Nebule\Library\DisplaySecurity) {
                $item->setSize($this->_sizeCSS);
                $item->setDisplayAlone(false);
                $result .= $item->getHTML();
            /*} elseif ($item instanceof \Nebule\Library\DisplayQuery) {
                $item->setSize($this->_sizeCSS);
                $item->setDisplayAlone(false);
                $result .= $item->getHTML();*/
            } elseif ($item instanceof \Nebule\Library\DisplayBlankLine)
                $result .= $item->getHTML();
            else
                continue;
            if ($this->_onPerLine)
                $result .= '<br />';
            $result .= "\n";
        }
        $result .= '</div>';
        $result .= '</div>';
        $result .= "\n";

        return $result;
    }

    public function addItem(DisplayItem $item): void {
        if ($item instanceof \Nebule\Library\DisplayInformation
            || $item instanceof \Nebule\Library\DisplayObject
            || $item instanceof \Nebule\Library\DisplaySecurity
            //|| $item instanceof \Nebule\Library\DisplayQuery
            || $item instanceof \Nebule\Library\DisplayBlankLine
        )
            $this->_list[] = $item;
        else
            $this->_nebuleInstance->getMetrologyInstance()->addLog('invalid instance ' . get_class($item) . ' to add on display list', Metrology::LOG_LEVEL_ERROR, __METHOD__, '93aa0cae');
    }

    private bool $_enableWarnIfEmpty = false;

    public function setEnableWarnIfEmpty(bool $enable = true): void
    {
        $this->_enableWarnIfEmpty = $enable;
    }

    public function setOnePerLine(bool $onePerLine = true): void {
        $this->_onPerLine = $onePerLine;
    }

    public static function displayCSS(): void {
        ?>

        <style type="text/css">
            /* CSS de la fonction DisplayList(). */
            .layoutList {
                padding: 3px;
            }
        </style>
        <?php
    }
}


/**
 * Classe DisplayBlankLine
 *       ---
 * Force display a blank line in DisplayList.
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
class DisplayBlankLine extends DisplayItem implements DisplayInterface
{
    public function getHTML(): string {
        return "<br />&nbsp;<br />\n";
    }

    public static function displayCSS(): void {}
}
