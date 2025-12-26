<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayInput
 *     ---
 * Display or prepare an input to the interface with the user.
 *     ---
 * Example for string:
 * $instance = new DisplayInput($this->_applicationInstance);
 * $instance->setType(DisplayQuery::QUERY_STRING);
 * $instance->setInputName('action');
 * $instance->setLink('?nid=0' . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand());
 * $instance->setIconText('My action');
 * $instance->display();
 *  TODO
 *     ---
 * Usage:
 *   - TODO
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class DisplayQuery extends DisplayInformation implements DisplayInterface
{
    public const QUERY_STRING = 'query';
    public const QUERY_PASSWORD = 'password';
    public const QUERY_BOOLEAN = 'boolean';
    public const QUERY_SELECT = 'select';
    public const QUERY_TEXT = 'text';
    public const ICON_QUERY_RID = '16e9a40a7f705f9c3871d13ce78b9f016f6166c2214b293e5a38964502a5ff9a05bb.none.272';
    public const ICON_PASSWORD_RID = 'ebde500081ce0916fb54efc3a900472be9fadee2dfcf988e3b5b721ebf00d687f655.none.272';

    protected string $_inputType = '';
    protected string $_inputValue = '';
    protected string $_inputName = '';
    protected string $_hiddenName1 = '';
    protected string $_hiddenValue1 = '';
    protected string $_hiddenName2 = '';
    protected string $_hiddenValue2 = '';
    protected bool $_withFormOpen = true;
    protected bool $_withFormClose = true;
    protected bool $_withSubmit = true;
    protected bool $_linkEnable = false;
    protected array $_selectList = array();

    public function setHiddenInput1(string $name, string $value = ''): void {
        $this->_hiddenName1 = trim($name);
        $this->_hiddenValue1 = trim($value);
    }
    public function setHiddenInput2(string $name, string $value = ''): void {
        $this->_hiddenName2 = trim($name);
        $this->_hiddenValue2 = trim($value);
    }
    public function setInputValue(string $value): void { $this->_inputValue = trim($value); }
    public function setInputName(string $name): void { $this->_inputName = trim($name); }

    /** Open the formular. Useful for a query in multiple parts on a list of DispayQuery() where the first one opens the formular and the last one closes it.
     * @param bool $withFormOpen
     * @return void
     */
    public function setWithFormOpen(bool $withFormOpen = true): void { $this->_withFormOpen = $withFormOpen; }

    /** Close the formular. Useful for a query in multiple parts on a list of DispayQuery() where the first one opens the formular and the last one closes it.
     * @param bool $withFormClose
     * @return void
     */
    public function setWithFormClose(bool $withFormClose = true): void { $this->_withFormClose = $withFormClose; }

    /** Show the submitting button on a formular for a query in multiple parts on a list of DispayQuery(). Maybe the best place is on the last one.
     * @param bool $withSubmit
     * @return void
     */
    public function setWithSubmit(bool $withSubmit = true): void { $this->_withSubmit = $withSubmit; }
    public function setSelectList(array $selectList): void { $this->_selectList = $selectList; }

    protected function _initialisation(): void {
        $this->setType(self::QUERY_STRING);
        $this->setSize();
        $this->setRatio();
        $this->_message = 'Not displayed';
    }

    protected function _getTinyHTML(): string {
        $result = ''; // TODO
        /*if ($this->_icon !== null)
            $result .= '<span style="font-size:1em" class="objectTitleIconsInline">' . $this->_getImageHTML($this->_icon, $this->_iconText) . '</span>';
        $result .= '<form method="post" action="' . $this->_link . '">';
        $result .= '<input type="hidden" name="' . $this->_hiddenName1 . '" value="' . $this->_hiddenValue1 . '">';
        $result .= '<label><input ' . $this->_inputType . ' name="' . References::COMMAND_PASSWORD . '"></label>';
        $result .= '<input type="submit" value="&gt;">';
        $result .= '</form>';*/
        return $result;
    }

    protected function _getNotTinyHTML(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_type == '')
            return '';
        $padding = 0;
        $result  = '';
        if ($this->_withFormOpen)
            $result .= '<form method="post" action="' . $this->_link . '">' . "\n";
        $result .= '<div class="layoutObject layoutInformation">';
        $result .= ' <div class="objectTitle objectDisplay' . $this->_sizeCSS . $this->_ratioCSS . ' queryDisplay queryDisplay' . $this->_sizeCSS . ' queryDisplay' . $this->_type . '">';
        $result .= '  <div class="objectTitleIcons queryTitleIcons queryTitleIcons' . $this->_type . '">';
        if ($this->_icon !== null) {
            $result .= $this->_getImageHTML($this->_icon, $this->_iconText);
            $padding = 1;
        }
        $result .= '  </div>' . "\n";
        $result .= '  <div class="objectTitleText' . $padding . ' queryTitleText queryTitle' . $this->_sizeCSS . 'Text">' . "\n";
        $result .= '    <div class="objectTitleRefs objectTitle' . $this->_sizeCSS . 'Refs queryTitleRefs queryTitleRefs"><label for="' . $this->_inputName . '">' .  $this->_iconText . '</label></div>' . "\n";
        $result .= '    <div class="queryTitleName queryTitleName' . $this->_type . ' queryTitle' . $this->_sizeCSS . 'Name">' . "\n";
        if ($this->_hiddenName1 != '')
            $result .= '     <input type="hidden" name="' . $this->_hiddenName1 . '" value="' . $this->_hiddenValue1 . '" />' . "\n";
        if ($this->_hiddenName2 != '')
            $result .= '     <input type="hidden" name="' . $this->_hiddenName2 . '" value="' . $this->_hiddenValue2 . '" />' . "\n";
        if ($this->_type == 'Query' || $this->_type == 'Password')
            $result .= '     <input type="' . $this->_inputType . '" name="' . $this->_inputName . '" id="' . $this->_inputName . '" value="' . $this->_inputValue . '" />' . "\n";
        elseif ($this->_type == 'Boolean')
            $result .= '     <input type="' . $this->_inputType . '" name="' . $this->_inputName . '" id="' . $this->_inputName . '" value="' . $this->_inputValue . '" /> ' . $this->_message . "\n"; // FIXME
        elseif ($this->_type == 'Select') {
            $result .= '     <select name="' . $this->_inputName . '" id="' . $this->_inputName . '">' . "\n";
            foreach ($this->_selectList as $key => $value)
                $result .= '      <option value="' . $key . '">' . $value . '</option>' . "\n";
            $result .= '     </select>' . "\n";
        } else
            $result .= $this->_message . "\n";
        if ($this->_withSubmit)
            $result .= '     <input type="submit" value="&gt;">' . "\n";
        $result .= '</div></div></div></div>' . "\n";
        if ($this->_withFormClose)
            $result .= '</form>' . "\n";
        return $result;
    }

    public function setType(string $type): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set type ' . $type, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch (strtolower($type)) {
            case self::QUERY_PASSWORD:
                $this->_type = 'Password';
                $this->_iconText = $this->_translateInstance->getTranslate('::::Password');
                $icon = self::ICON_PASSWORD_RID;
                $this->_inputType = 'password';
                break;
            case self::QUERY_BOOLEAN:
                $this->_type = 'Boolean';
                $this->_iconText = $this->_translateInstance->getTranslate('::::Switch');
                $icon = self::ICON_QUERY_RID;
                $this->_inputType = 'checkbox';
                break;
            case self::QUERY_SELECT:
                $this->_type = 'Select';
                $this->_iconText = $this->_translateInstance->getTranslate('::::Switch');
                $icon = self::ICON_QUERY_RID;
                $this->_inputType = 'select';
                break;
            case self::QUERY_STRING:
                $this->_type = 'Query';
                $this->_iconText = $this->_translateInstance->getTranslate('::::Query');
                $icon = self::ICON_QUERY_RID;
                $this->_inputType = 'text';
                break;
            case self::QUERY_TEXT:
                $this->_type = 'Text';
                $this->_iconText = $this->_translateInstance->getTranslate('::::Query');
                $icon = self::ICON_QUERY_RID;
                $this->_inputType = 'text';
                break;
            default:
                $this->_type = '';
                $icon = self::ICON_QUERY_RID;
                break;
        }
        $rid = $this->_cacheInstance->newNode($icon);
        $this->_icon = $rid->getReferencedObjectInstance(References::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE, $this->_social);
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS for DisplayQuery(). */
            .queryTitleIcons img {
                background: none;
            }

            .queryDisplay {
                height: auto;
            }

            .queryDisplayPassword {
                background: #ffe080;
            }

            .queryDisplayQuery, .queryDisplaySelect, .queryDisplayBoolean, .queryDisplayText {
                background: #ababab;
            }

            .queryTitleText {
                background: none;
                height: auto;
            }

            .queryDisplayTiny {
            }

            .queryDisplaySmall {
                min-height: 32px;
                font-size: 32px;
                border: 0;
            }

            .queryDisplayMedium {
                min-height: 64px;
                font-size: 64px;
                border: 0;
            }

            .queryDisplayLarge {
                min-height: 128px;
                font-size: 128px;
                border: 0;
            }

            .queryDisplayFull {
                min-height: 256px;
                font-size: 256px;
                border: 0;
            }

            .queryTitleTinyText {
                min-height: 16px;
                background: none;
            }

            .queryTitleSmallText {
                min-height: 30px;
                text-align: left;
                padding: 1px 0 1px 1px;
                color: #000000;
            }

            .queryTitleMediumText {
                min-height: 58px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .queryTitleLargeText {
                min-height: 122px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .queryTitleFullText {
                min-height: 246px;
                text-align: left;
                padding: 5px 0 5px 5px;
                color: #000000;
            }

            .queryTitleName {
                font-weight: normal;
                overflow: hidden;
                height: auto;
            }

            .queryTitleNamePassword {
                color: #ff8000;
                font-weight: bold;
            }

            .queryTitleNameQuery {
                color: #000000;
                font-weight: bold;
            }

            .queryTitleTinyName {
                height: 1rem;
                line-height: 1rem;
                font-size: 1rem;
            }

            .queryTitleSmallName {
                line-height: 14px;
                overflow: hidden;
                white-space: normal;
                font-size: 1rem;
            }

            .queryTitleMediumName {
                line-height: 22px;
                overflow: hidden;
                white-space: normal;
                font-size: 1.2rem;
            }

            .queryTitleLargeName {
                line-height: 30px;
                overflow: hidden;
                white-space: normal;
                font-size: 1.5rem;
            }

            .queryTitleFullName {
                line-height: 62px;
                overflow: hidden;
                white-space: normal;
                font-size: 2rem;
            }

            .queryTitleName input {
                margin: 10px 5px 0 0;
            }
            .queryTitleName input[type=text], input[type=email] {
                width: 260px;
            }
            .queryTitleName input[type=password] {
                width: 258px;
            }
            .queryTitleName input[type=submit] {
                width: 27px;
                font-weight: bold;
            }
        </style>
        <?php
    }
}
