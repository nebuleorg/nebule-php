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
class DisplayColor extends DisplayItemCSS implements DisplayInterface
{
    const ICON_ALPHA_COLOR_OID = '87b260416aa0f50736d3ca51bcb6aae3eff373bf471d5662883b8b6797e73e85.sha2.256';

    private ?Node $_nid = null;
    private string $_name = '';
    private bool $_displayActions = false;
    private string $_actionsID = '';
    private bool $_displayJS = true;

    protected function _initialisation(): void
    {
        $this->setSocial();
        $this->setEnableJS();
        $this->setActionsID();
        $this->setSize();
    }

    public function getHTML(): string
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_nid === null) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('nid null', Metrology::LOG_LEVEL_ERROR, __METHOD__, '4d5732c9');
            return '';
        }

        $result = '<img title="' . $this->_name . '"' . $this->_styleCSS;
        $result .= ' alt="[C]" src="o/' . self::ICON_ALPHA_COLOR_OID . '" class="iconColor' . $this->_sizeCSS . '"';
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
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('set nid ' . gettype($nid), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($nid === null)
            $this->_nid = null;
        elseif ($nid->getID() != '0' && is_a($nid, 'Nebule\Library\Node')) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('set nid ' . $nid->getID(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'af41d8bd');
            $this->_nid = $nid;
            $this->setName();
            $this->setStyleCSS();
        }
    }

    public function setName(string $name = ''): void
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('set name ' . $name, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_nid === null)
            return;
        if ($name != '')
            $this->_name = trim((string)filter_var($name, FILTER_SANITIZE_STRING));
        else
            $this->_name = $this->_nid->getFullName($this->_social);
    }

    public function setStyleCSS(string $style = ''): void
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('set style CSS ' . $style, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_nid === null)
            return;
        $this->_styleCSS = ' style="background:#'
            . $this->_nid->getPrimaryColor()
            . trim((string)filter_var($style, FILTER_SANITIZE_STRING))
            . ';"';
    }

    public function setEnableActions(bool $enable = true): void // TODO rétrocompatibilité, en double de enableJS, à supprimer.
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('set enable actions ' . (string)$enable, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayActions = $enable;
    }

    public function setActionsID(string $id = '')
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('set actions id ' . $id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($id == '')
            $this->_actionsID = bin2hex($this->_nebuleInstance->getCryptoInstance()->getRandom(8, Crypto::RANDOM_PSEUDO));
        else
            $this->_actionsID = trim((string)filter_var($id, FILTER_SANITIZE_STRING));

        if ($this->_actionsID != '')
            $this->_displayActions = true;
    }

    public function setEnableJS(bool $enable = true): void
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('set enable JS ' . (string)$enable, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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

            .iconColorTiny {
                height: 16px;
                font-size: 16px;
            }

            .iconColorSmall {
                height: 32px;
                font-size: 32px;
            }

            .iconColorMedium {
                height: 64px;
                font-size: 64px;
            }

            .iconColorLarge {
                height: 128px;
                font-size: 128px;
            }

            .iconColorFull {
                height: 256px;
                font-size: 256px;
            }

        <?php
    }
}
