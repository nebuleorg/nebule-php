<?php
declare(strict_types=1);
namespace Nebule\Library;

use Nebule\Application\Atrium\Display;
use Throwable;

/**
 * Classe DisplayList
 *       ---
 * Display a list of mix with DisplayInformation, DisplayObject, DisplaySecurity, DisplayQuery, DisplayBlankLine and children classes.
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
class DisplayList extends DisplayItem implements DisplayInterface {
    protected array $_fullList = array();
    protected array $_listAdd = array();
    protected array $_listItems = array();
    protected array $_listItemInfos = array();
    protected bool $_onePerLine = false;
    protected float $_currentPage = 0;
    protected float $_lastPage = 0;
    protected float $_fullSize = 0;

    public function getHTML(): string {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_prepareList();
        if ($this->_listSize == 0) {
            if ($this->_enableWarnIfEmpty) {
                $instanceWarn = new DisplayInformation($this->_applicationInstance);
                $instanceWarn->setType(DisplayItemIconMessage::TYPE_MESSAGE);
                $instanceWarn->setMessage('::list:empty');
                $instanceWarn->setRatio(DisplayItem::RATIO_SHORT);
                $instanceWarn->display();
            }
            return '';
        } else {
            $result = '<div class="layoutList">' . "\n";
            if ($this->_onePerLine)
                $this->_pageColumns = 1;
            $result .= '<div class="listNavigate">' . "\n";
            $result .= $this->_getPageNavigateHTML();
            $result .= '</div>' . "\n";
            $result .= '<div class="listContent" style="grid-template-columns: repeat(' . $this->_pageColumns . ', auto); gap: ' . $this->_pageGap . 'px">' . "\n";
            foreach ($this->_fullList as $item) {
                $this->_nebuleInstance->getMetrologyInstance()->addLog('get code from ' . get_class($item), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '52d6f3ea');
                $result .= '<div class="listItemContent">' . "\n";
                if ($item instanceof DisplayInformation) {
                    try {
                        $item->setSize($this->_sizeCSS);
                        $item->setDisplayAlone(false);
                        $result .= $item->getHTML();
                    } catch (Throwable $e) {
                        $this->_metrologyInstance->addLog('error get display information (' . $e->getCode() . ') : ' . $e->getFile()
                                . '(' . $e->getLine() . ') : ' . $e->getMessage() . "\n"
                                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '3a188875');
                    }
                } elseif ($item instanceof DisplayObject) {
                    try {
                        $item->setSize($this->_sizeCSS);
                        $result .= $item->getHTML();
                    } catch (Throwable $e) {
                        $this->_metrologyInstance->addLog('error get display object (' . $e->getCode() . ') : ' . $e->getFile()
                                . '(' . $e->getLine() . ') : ' . $e->getMessage() . "\n"
                                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'aa1f3719');
                    }
                } elseif ($item instanceof DisplaySecurity) {
                    try {
                        $item->setSize($this->_sizeCSS);
                        $item->setDisplayAlone(false);
                        $result .= $item->getHTML();
                    } catch (Throwable $e) {
                        $this->_metrologyInstance->addLog('error get display security (' . $e->getCode() . ') : ' . $e->getFile()
                                . '(' . $e->getLine() . ') : ' . $e->getMessage() . "\n"
                                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'a3a050a4');
                    }
                } elseif ($item instanceof DisplayBlankLine)
                    $result .= $item->getHTML();
                else
                    continue;
                $result .= '</div>' . "\n";
            }
            $result .= '</div>' . "\n";
            $result .= '<div class="listNavigate">' . "\n";
            $result .= $this->_getPageNavigateHTML();
            $result .= '</div></div>' . "\n";
        }

        return $result;
    }

    protected function _getLayoutSize(): int { // FIXME pour toutes les sizeCSS. FIXME bugg
        if ( $this->_fullSize > $this->_pageColumns)
            return 2 + ($this->_pageColumns * 384 + 4) + 2;
        else
            return 2 + ($this->_fullSize * 384 + 4) + 2;
    }

    protected function _getPageNavigateHTML(): string {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $result = '';
        if ($this->_lastPage > 1) {
            $url = $this->_prepareURL();
            if ($this->_currentPage > 1) {
                $instance = new DisplayInformation($this->_applicationInstance);
                $instance->setType(DisplayItemIconMessage::TYPE_BACK);
                $instance->setMessage('::previousPage', (string)$this->_currentPage, (string)$this->_lastPage);
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setSize(DisplayItem::SIZE_SMALL);
                $instance->setIconText('');
                $instance->setLink($url . '&' . Displays::COMMAND_DISPLAY_PAGE_LIST . '=' . ($this->_currentPage - 1));
                $result .= $instance->getHTML();
                $result .= "\n";
            }
            $instance = new DisplayInformation($this->_applicationInstance);
            $instance->setType(DisplayItemIconMessage::TYPE_MESSAGE);
            $first = ($this->_currentPage * $this->_listSize) - $this->_listSize + 1;
            $last = $this->_currentPage * $this->_listSize;
            if ($last > $this->_fullSize) $last = $this->_fullSize;
            $instance->setMessage('::page%s%s%s%s%s', (string)$this->_currentPage, (string)$this->_lastPage, (string)$first, (string)$last, (string)$this->_fullSize);
            $instance->setRatio(DisplayItem::RATIO_SHORT);
            $instance->setSize(DisplayItem::SIZE_SMALL);
            $instance->setIconText('');
            $result .= $instance->getHTML();
            $result .= "\n";
            if ($this->_currentPage < $this->_lastPage) {
                $instance = new DisplayInformation($this->_applicationInstance);
                $instance->setType(DisplayItemIconMessage::TYPE_PLAY);
                $instance->setMessage('::nextPage', (string)$this->_currentPage, (string)$this->_lastPage);
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setSize(DisplayItem::SIZE_SMALL);
                $instance->setIconText('');
                $instance->setLink($url . '&' . Displays::COMMAND_DISPLAY_PAGE_LIST . '=' . ($this->_currentPage + 1));
                $result .= $instance->getHTML();
            }
            $result .= "<br />\n";
        }
        return $result;
    }

    public function addItem(DisplayItem $item): void {
        if ($item instanceof DisplayInformation
            || $item instanceof DisplayObject
            || $item instanceof DisplaySecurity // and DisplayQuery
            || $item instanceof DisplayBlankLine
        )
            $this->_listAdd[] = $item;
        else
            $this->_nebuleInstance->getMetrologyInstance()->addLog('invalid instance ' . get_class($item) . ' to add on display list', Metrology::LOG_LEVEL_ERROR, __METHOD__, '93aa0cae');
    }

    protected bool $_enableWarnIfEmpty = false;
    protected string $_listHookName = '';
    protected int $_listSize = Displays::DEFAULT_DISPLAY_SIZE_LIST;
    protected int $_pageColumns = Displays::DEFAULT_DISPLAY_PAGE_COLUMN;
    protected int $_pageGap = Displays::DEFAULT_DISPLAY_GAP_COLUMN;

    public function setEnableWarnIfEmpty(bool $enable = true): void { $this->_enableWarnIfEmpty = $enable; }
    public function setOnePerLine(bool $onePerLine = true): void { $this->_onePerLine = $onePerLine; }
    public function setListHookName(string $hookName): void { $this->_listHookName = $hookName; }
    public function setListSize(int $size = Displays::DEFAULT_DISPLAY_SIZE_LIST): void { $this->_listSize = $size; }
    public function setPageColumns(int $columns = Displays::DEFAULT_DISPLAY_PAGE_COLUMN): void { $this->_pageColumns = $columns; }
    public function setGap(int $gap = Displays::DEFAULT_DISPLAY_GAP_COLUMN): void { $this->_pageGap = $gap; }
    public function setListItems(array $list): void { $this->_listItems = $list; }
    public function setListItemInfos(array $infos): void { $this->_listItemInfos = $infos; }

    protected function _prepareList(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $infos = array();
        $this->_currentPage = $this->_displayInstance->getCurrentPage();
        if ($this->_currentPage < 1)
            $this->_currentPage = 1;
        $this->_fullSize = sizeof($this->_listItems) + sizeof($this->_listAdd);
        $this->_lastPage = ceil($this->_fullSize / $this->_listSize);
        if ($this->_currentPage > $this->_lastPage)
            $this->_currentPage = $this->_lastPage;

        $count = 0;
        foreach ($this->_listAdd as $instance) {
            if (intval($count / $this->_listSize) == ($this->_currentPage - 1))
                $this->_fullList[$count] = $instance;
            $count++;
        }
        foreach ($this->_listItems as $item) {
            if (intval($count / $this->_listSize) == ($this->_currentPage - 1)) {
                try {
                    $instance = $this->_routerInstance->getApplicationInstance()->getCurrentModuleInstance()->getHookFunction($this->_listHookName, $item, $this->_listItemInfos);
                } catch (Throwable $e) {
                    $this->_metrologyInstance->addLog('error get hook function ' . $this->_listHookName .' ('  . $e->getCode() . ') : ' . $e->getFile()
                            . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                            . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'efa38425');
                    $instance = null;
                }
                if ($instance != null)
                    $this->_fullList[$count] = $instance;
            }
            $count++;
        }
        $this->_listAdd = array();
        $this->_listItems = array();
    }

    protected function _prepareURL(): string  {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $url = '?';
        foreach ($_GET as $key => $value) {
            if (str_starts_with($key, 'action_') || $key == References::COMMAND_TOKEN || $key == Displays::COMMAND_INLINE)
                continue;
            if ($url != '?')
                $url .= '&';
            $url .= $key;
            if ($value != '')
                $url .= '=' . $value;
        }
        return $url;
    }

    public static function displayCSS(): void {
        ?>

        <style>
            /* CSS de la fonction DisplayList(). */
            .layoutList { background: rgb(69 69 69 / 15%); }
            .listContent { padding: <?php echo Displays::DEFAULT_DISPLAY_GAP_COLUMN; ?>px; display: grid; }
            /* .listItemContent { padding: 0; margin: 0; } */
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
class DisplayBlankLine extends DisplayItem implements DisplayInterface {
    public function getHTML(): string { return "<br />&nbsp;<br />\n"; }
    public static function displayCSS(): void {}
}
