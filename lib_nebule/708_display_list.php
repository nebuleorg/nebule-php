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
class DisplayList extends DisplayItem implements DisplayInterface {
    protected array $_fullList = array();
    protected array $_listAdd = array();
    protected array $_listItem = array();
    protected bool $_onPerLine = false;
    protected float $_currentPage = 0;
    protected float $_lastPage = 0;
    protected float $_fullSize = 0;

    public function getHTML(): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_prepareList();
        if (sizeof($this->_fullList) == 0) {
            if ($this->_enableWarnIfEmpty) {
                $instanceWarn = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instanceWarn->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_MESSAGE);
                $instanceWarn->setMessage('::list:empty');
                $instanceWarn->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instanceWarn->display();
            }
            return '';
        }

        $result = '<div class="layoutList">' . "\n";
        $result .= '<div class="listContent">' . "\n";
        $this->_getNavHTML($result);
        $column = 0;
        foreach ($this->_fullList as $item){
            $this->_nebuleInstance->getMetrologyInstance()->addLog('get code from ' . get_class($item), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '52d6f3ea');
            if ($item instanceof \Nebule\Library\DisplayInformation) {
                try {
                    $item->setSize($this->_sizeCSS);
                    $item->setDisplayAlone(false);
                    $result .= $item->getHTML();
                } catch (\Exception $e) {
                    $this->_metrologyInstance->addLog('error get display information ('  . $e->getCode() . ') : ' . $e->getFile()
                        . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                        . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '3a188875');
                }
            } elseif ($item instanceof \Nebule\Library\DisplayObject) {
                try {
                    $item->setSize($this->_sizeCSS);
                    $result .= $item->getHTML();
                } catch (\Exception $e) {
                    $this->_metrologyInstance->addLog('error get display object ('  . $e->getCode() . ') : ' . $e->getFile()
                        . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                        . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'aa1f3719');
                }
            } elseif ($item instanceof \Nebule\Library\DisplaySecurity) {
                try {
                    $item->setSize($this->_sizeCSS);
                    $item->setDisplayAlone(false);
                    $result .= $item->getHTML();
                } catch (\Exception $e) {
                    $this->_metrologyInstance->addLog('error get display security ('  . $e->getCode() . ') : ' . $e->getFile()
                        . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                        . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'a3a050a4');
                }
            } elseif ($item instanceof \Nebule\Library\DisplayBlankLine)
                $result .= $item->getHTML();
            else
                continue;
            $column++;
            if ($this->_onPerLine || $column >= $this->_pageColumns) {
                $result .= '<br />';
                $column = 0;
            }
            $result .= "\n";
        }
        $this->_getNavHTML($result, ($column != 0));
        $result .= '</div>';
        $result .= '</div>';
        $result .= "\n";

        return $result;
    }

    protected function _getNavHTML(string &$result, bool $needBR = false): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_lastPage > 1) {
            if ($needBR)
                $result .= "<br />\n";
            $url = $this->_prepareURL();
            if ($this->_currentPage > 1) {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_BACK);
                $instance->setMessage('::previousPage', (string)$this->_currentPage, (string)$this->_lastPage);
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
                $instance->setIconText('');
                $instance->setLink($url . '&' . Displays::COMMAND_DISPLAY_PAGE_LIST . '=' . ($this->_currentPage - 1));
                $result .= $instance->getHTML();
                $result .= "\n";
            }
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_MESSAGE);
            $first = ($this->_currentPage * $this->_listSize) - $this->_listSize + 1;
            $last = $this->_currentPage * $this->_listSize;
            if ($last > $this->_fullSize) $last = $this->_fullSize;
            $instance->setMessage('::page%s%s%s%s%s', (string)$this->_currentPage, (string)$this->_lastPage, (string)$first, (string)$last, (string)$this->_fullSize);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            $instance->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
            $instance->setIconText('');
            $result .= $instance->getHTML();
            $result .= "\n";
            if ($this->_currentPage < $this->_lastPage) {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
                $instance->setMessage('::nextPage', (string)$this->_currentPage, (string)$this->_lastPage);
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
                $instance->setIconText('');
                $instance->setLink($url . '&' . Displays::COMMAND_DISPLAY_PAGE_LIST . '=' . ($this->_currentPage + 1));
                $result .= $instance->getHTML();
            }
            $result .= "<br />\n";
        }
    }

    public function addItem(DisplayItem $item): void {
        if ($item instanceof \Nebule\Library\DisplayInformation
            || $item instanceof \Nebule\Library\DisplayObject
            || $item instanceof \Nebule\Library\DisplaySecurity // and \Nebule\Library\DisplayQuery
            || $item instanceof \Nebule\Library\DisplayBlankLine
        )
            $this->_listAdd[] = $item;
        else
            $this->_nebuleInstance->getMetrologyInstance()->addLog('invalid instance ' . get_class($item) . ' to add on display list', Metrology::LOG_LEVEL_ERROR, __METHOD__, '93aa0cae');
    }

    protected bool $_enableWarnIfEmpty = false;
    protected string $_listHookName = '';
    protected int $_listSize = Displays::DEFAULT_DISPLAY_SIZE_LIST;
    protected int $_pageColumns = Displays::DEFAULT_DISPLAY_PAGE_COLUMN;

    public function setEnableWarnIfEmpty(bool $enable = true): void { $this->_enableWarnIfEmpty = $enable; }

    public function setOnePerLine(bool $onePerLine = true): void { $this->_onPerLine = $onePerLine; }

    public function setListHookName(string $hookName): void { $this->_listHookName = $hookName; }

    public function setListSize(int $size = Displays::DEFAULT_DISPLAY_SIZE_LIST): void { $this->_listSize = $size; }

    public function setPageColumns(int $columns = Displays::DEFAULT_DISPLAY_PAGE_COLUMN): void { $this->_pageColumns = $columns; }

    public function setListItems(array $list): void { $this->_listItem = $list; }

    protected function _prepareList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_currentPage = $this->_displayInstance->getCurrentPage();
        if ($this->_currentPage < 1)
            $this->_currentPage = 1;
        $this->_fullSize = sizeof($this->_listItem) + sizeof($this->_listAdd);
        $this->_lastPage = ceil($this->_fullSize / $this->_listSize);
        if ($this->_currentPage > $this->_lastPage)
            $this->_currentPage = $this->_lastPage;

        $count = 0;
        foreach ($this->_listAdd as $instance) {
            if (intval($count / $this->_listSize) == ($this->_currentPage - 1))
                $this->_fullList[$count] = $instance;
            $count++;
        }
        foreach ($this->_listItem as $item) {
            if (intval($count / $this->_listSize) == ($this->_currentPage - 1)) {
                try {
                    $instance = $this->_routerInstance->getApplicationInstance()->getCurrentModuleInstance()->getHookFunction($this->_listHookName, $item);
                } catch (\Exception $e) {
                    $this->_metrologyInstance->addLog('error get hook function ' . $this->_listHookName .' ('  . $e->getCode() . ') : ' . $e->getFile()
                            . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                            . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                    $instance = null;
                }
                if ($instance != null)
                    $this->_fullList[$count] = $instance;
            }
            $count++;
        }
        $this->_listAdd = array();
        $this->_listItem = array();
    }

    protected function _prepareURL(): string  {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
class DisplayBlankLine extends DisplayItem implements DisplayInterface {
    public function getHTML(): string { return "<br />&nbsp;<br />\n"; }
    public static function displayCSS(): void {}
}
