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
class DisplayIcon extends DisplayItem implements DisplayInterface
{
    private $_nid = null;
    private $_social = '';
    private $_name = '';
    private $_type = '';
    private $_displayActions = false;
    private $_displayJS = true;

    protected function _init(): void
    {
        $this->setSocial('');
    }

    public function getHTML(): string
    {
        if ($this->_nid === null)
            return '';

        return ''; // TODO
    }

    public function setNID(?Node $nid): void
    {
        if ($nid === null)
            $this->_nid = null;
        elseif ($nid->getID() != '0' && is_a($nid, 'Nebule\Library\Node') && $nid->checkPresent()) {
            $this->_nid = $nid;
            $this->setType('');
            $this->setName('');
        }
    }

    public function setType(string $type): void
    {
        if ($type == '')
            $this->_type = $this->_nid->getType($this->_social);
        else
            $this->_type = $type;
    }

    public function setName(string $name): void
    {
        if ($name != '')
            $this->_name = trim(filter_var($name, FILTER_SANITIZE_STRING));
        else {
            if ($this->_displayIconApp)
                $this->_name = $this->_nid->getName($this->_social);
            else
                $this->_name = $this->_nid->getFullName($this->_social);
        }
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

    public function setEnableActions(bool $enable): void
    {
        $this->_displayActions = $enable;
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
