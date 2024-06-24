<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayColor
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
class DisplayColor extends DisplayItem implements DisplayInterface
{
    const DEFAULT_ICON_ALPHA_COLOR = '87b260416aa0f50736d3ca51bcb6aae3eff373bf471d5662883b8b6797e73e85.sha2.256';

    private $_nid = null;
    private $_social = '';
    private $_name = '';
    private $_class = '';
    private $_displayActions = false;
    private $_displayJS = true;
    private $_actionsID = '';

    protected function _init(): void
    {
        $this->setSocial('');
        $this->setActionsID('');
    }

    public function getHTML(): string
    {
        if ($this->_nid === null)
            return '';

        $result = '<img title="' . $this->_name . '"';
        $result .= ' style="background:#' . $this->_nid->getPrimaryColor() . ';"';
        $result .= ' alt="[C]" src="o/' . self::DEFAULT_ICON_ALPHA_COLOR . '"';
        if ($this->_class != '')
            $result .= ' class="' . $this->_class . '"';
        if ($this->_displayActions && $this->_displayJS)
            $result .= " onclick=\"display_menu('objectTitleMenu-" . $this->_actionsID . "');\" ";
        $result .= '/>';

        return $result;
    }

    public function setNID(?Node $nid): void
    {
        if ($nid === null)
            $this->_nid = null;
        elseif ($nid->getID() != '0' && is_a($nid, 'Nebule\Library\Node') && $nid->checkPresent()) {
            $this->_nid = $nid;
            $this->setName('');
        }
    }

    public function setName(string $name): void
    {
        if ($name != '')
            $this->_name = trim(filter_var($name, FILTER_SANITIZE_STRING));
        else
            $this->_name = $this->_nid->getFullName($this->_social);
    }

    public function setSocial(string $social): void
    {
        if ($social == '')
        {
            $this->_social = 'all';
            return;
        }
        $socialList = $this->_nebuleInstance->getSocialInstance()->getSocialNames();
        foreach ($socialList as $s) {
            if ($social == $s) {
                $this->_social = $social;
                break;
            }
        }
        if ($this->_social == '')
            $this->_social = 'all';
    }

    public function setClass(string $class): void
    {
        $this->_class = trim(filter_var($class, FILTER_SANITIZE_STRING));
    }

    public function setEnableActions(bool $enable): void
    {
        $this->_displayActions = $enable;
    }

    public function setActionsID(string $id)
    {
        if ($id == '')
            $this->_actionsID = bin2hex($this->_nebuleInstance->getCryptoInstance()->getRandom(8, Crypto::RANDOM_PSEUDO));
        else
            $this->_actionsID = trim(filter_var($id, FILTER_SANITIZE_STRING));
    }
    public function setEnableJS(bool $enable): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitJavaScript'))
            $this->_displayJS = $enable;
    }

    public static function displayCSS(): void
    {
        echo ''; // TODO
    }
}
