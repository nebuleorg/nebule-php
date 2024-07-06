<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayIcon
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
class DisplayIcon extends DisplayItemIconable implements DisplayInterface
{
    private $_nid = null;
    private $_social = '';
    private $_name = '';
    private $_classCSS = '';
    private $_idCSS = '';
    private $_styleCSS = '';
    private $_type = '';
    private $_displayActions = false;
    private $_displayJS = true;

    protected function _init(): void
    {
        $this->setSocial();
        $this->setEnableJS();
        $this->setStyleCSS();
    }

    public function getHTML(): string
    {
        if ($this->_nid === null)
            return '';

        if ($this->_icon === null)
            return ''; // TODO if no icon, display face instead.

        $result = '<img title="' . $this->_name . '"';
        $result .= $this->_styleCSS;
        $result .= ' alt="[I]" src="o/' . $this->_getObjectIconHTML($this->_nid, $this->_icon) . '"';
        if ($this->_classCSS != '')
            $result .= ' class="' . $this->_classCSS . '"';
        if ($this->_idCSS != '')
            $result .= ' id="' . $this->_idCSS . '"';
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
            $this->setType();
            $this->setName();
        }
    }

    public function setType(string $type = ''): void
    {
        if ($type == '')
            $this->_type = $this->_nid->getType($this->_social);
        else
            $this->_type = $type;
    }

    public function setName(string $name = ''): void
    {
        if ($name != '')
            $this->_name = trim(filter_var($name, FILTER_SANITIZE_STRING));
        else
            $this->_name = $this->_nid->getFullName($this->_social);
    }

    public function setSocial(string $social = ''): void
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

    public function setClassCSS(string $class = ''): void
    {
        $this->_classCSS = trim(filter_var($class, FILTER_SANITIZE_STRING));
    }

    public function setIdCSS(string $id = ''): void
    {
        $this->_idCSS = trim(filter_var($id, FILTER_SANITIZE_STRING));
    }

    public function setStyleCSS(string $style = ''): void
    {
        $this->_styleCSS = ' style="background:#'
            . $this->_nid->getPrimaryColor()
            . trim(filter_var($style, FILTER_SANITIZE_STRING))
            . ';"';
    }

    public function setEnableActions(bool $enable = true): void
    {
        $this->_displayActions = $enable;
    }

    public function setEnableJS(bool $enable = true): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitJavaScript'))
            $this->_displayJS = $enable;
        else
            $this->_displayJS = false;
    }

    public static function displayCSS(): void
    {
        echo ''; // TODO
    }
}
