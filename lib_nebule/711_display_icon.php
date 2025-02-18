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
    private ?Node $_nid = null;
    private string $_name = '';
    private string $_type = '';
    private bool $_displayActions = false;
    private string $_actionsID = '';
    private bool $_displayJS = true;

    protected function _init(): void
    {
        $this->setSocial();
        $this->setEnableJS();
        $this->setActionsID();
        $this->setIconText('[I]');
        $this->setSize();
    }

    public function getHTML(): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_nid === null) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('nid null', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f94ff81b');
            return '';
        }

        $result = '<img title="' . $this->_name . '"' . $this->_styleCSS;
        $result .= ' alt="' . $this->_iconText . '" src="' . $this->_getNidIconHTML($this->_nid, $this->_icon) . '" class="iconFace' . $this->_sizeCSS . '"';
        if ($this->_classCSS != '')
            $result .= ' class="' . $this->_classCSS . '"';
        if ($this->_idCSS != '')
            $result .= ' id="' . $this->_idCSS . '"';
        if ($this->_displayActions && $this->_displayJS)
            $result .= " onclick=\"display_menu('objectTitleMenu-" . $this->_actionsID . "');\"";
        $result .= '/>';

        return $result;
    }

    public function setNID(?Node $nid): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set nid ' . gettype($nid), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($nid === null)
            $this->_nid = null;
        elseif ($nid->getID() != '0' && is_a($nid, 'Nebule\Library\Node')) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('set nid ' . $nid->getID(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b73c6a2e');
            $this->_nid = $nid;
            $this->setType();
            $this->setName();
            $this->setStyleCSS();
        }
    }

    public function setType(string $type = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set type ' . $type, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_nid === null)
            return;
        if ($type == '') {
            $this->_type = $this->_nid->getType($this->_social);
            $this->setIconRID($this->_nid::DEFAULT_ICON_RID);
        }
        else {
            $this->_type = $type;
            $this->setIconRID(Node::DEFAULT_ICON_RID); // FIXME
        }
    }

    public function setName(string $name = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set name ' . $name, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_nid === null)
            return;
        if ($name != '')
            $this->_name = trim((string)filter_var($name, FILTER_SANITIZE_STRING));
        else
            $this->_name = $this->_nid->getFullName($this->_social);
    }

    public function setStyleCSS(string $style = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set style CSS ' . $style, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_nid === null)
            return;
        $this->_styleCSS = ' style="background:#'
            . $this->_nid->getPrimaryColor()
            . trim((string)filter_var($style, FILTER_SANITIZE_STRING))
            . ';"';
    }

    public function setEnableActions(bool $enable = true): void // TODO rétrocompatibilité, en double de enableJS, à supprimer.
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set enable actions ' . (string)$enable, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayActions = $enable;
    }

    public function setActionsID(string $id = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set actions id ' . $id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($id == '')
            $this->_actionsID = bin2hex($this->_nebuleInstance->getCryptoInstance()->getRandom(8, Crypto::RANDOM_PSEUDO));
        else
            $this->_actionsID = trim((string)filter_var($id, FILTER_SANITIZE_STRING));

        if ($this->_actionsID != '')
            $this->_displayActions = true;
    }

    public function setEnableJS(bool $enable = true): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set enable JS ' . (string)$enable, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->getOptionAsBoolean('permitJavaScript'))
            $this->_displayJS = $enable;
        else
            $this->_displayJS = false;
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS for DisplayColor(). */

            .iconFaceTiny {
                height: 16px;
                font-size: 16px;
            }

            .iconFaceSmall {
                height: 32px;
                font-size: 32px;
            }

            .iconFaceMedium {
                height: 64px;
                font-size: 64px;
            }

            .iconFaceLarge {
                height: 128px;
                font-size: 128px;
            }

            .iconFaceFull {
                height: 256px;
                font-size: 256px;
            }

        <?php
    }
}
