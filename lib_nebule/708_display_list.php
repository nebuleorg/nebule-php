<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayList
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
            if ($item instanceof DisplayInformation) {
                $item->setSize($this->_sizeCSS);
                $item->setDisplayAlone(false);
                $result .= $item->getHTML();
            } elseif ($item instanceof DisplayObject) {
                $item->setSize($this->_sizeCSS);
                // $item->setDisplayAlone(false); TODO
                $result .= $item->getHTML();
            } elseif ($item instanceof DisplaySecurity) {
                $item->setSize($this->_sizeCSS);
                $item->setDisplayAlone(false);
                $result .= $item->getHTML();
            } elseif ($item instanceof DisplayQuery) {
                $item->setSize($this->_sizeCSS);
                $item->setDisplayAlone(false);
                $result .= $item->getHTML();
            }
            $result .= "\n";
        }
        $result .= '</div>';
        $result .= '</div>';
        $result .= "\n";

        return $result;
    }

    public function addItem(DisplayItem $item): void {
        if ($item instanceof DisplayInformation
            || $item instanceof DisplayObject
            || $item instanceof DisplaySecurity
            || $item instanceof DisplayQuery
        )
            $this->_list[] = $item;
    }

    private bool $_enableWarnIfEmpty = false;

    public function setEnableWarnIfEmpty(bool $enable = true): void
    {
        $this->_enableWarnIfEmpty = $enable;
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
