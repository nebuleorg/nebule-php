<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplaySecurity
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
class DisplaySecurity extends DisplayItemIconMessageSizeable implements DisplayInterface
{
    private bool $_displayAlone = true;
    private bool $_displayOk = true;
    private bool $_displayFull = false;

    public function getHTML(): string
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $result = '';
        $error = 'ok';

        if ($this->_displayAlone)
            $result .= '<div class="layoutList"><div class="listContent">';

        $instance = new DisplayInformation($this->_applicationInstance);
        $instance->setSocial($this->_social);
        $instance->setType(DisplayItemIconMessage::TYPE_WARN);
        $instance->setDisplayAlone(false);
        $instance->setRatio($this->_ratioCSS);
        $instance->setSize($this->_sizeCSS);

        if ($this->_rescueInstance->getModeRescue()) {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $instance->setMessage('::RESCUE');
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $instance->setMessage('::RESCUE');
            $result .= $instance->getHTML();
        }

        $instance->setMessage($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
        if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'WARN') {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'ERROR') {
            $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
            $error = 'er';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) { // TODO revoir tous les messages OK avec de vrais messages...
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $result .= $instance->getHTML();
        }

        $instance->setMessage($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
        if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'WARN') {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'ERROR') {
            $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
            $error = 'er';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $result .= $instance->getHTML();
        }

        $instance->setMessage($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
        if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'WARN') {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'ERROR') {
            $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
            $error = 'er';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $result .= $instance->getHTML();
        }

        $instance->setMessage($this->_applicationInstance->getCheckSecurityBootstrapMessage());
        if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'ERROR') {
            $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
            $error = 'er';
            $result .= $instance->getHTML();
        } elseif ($this->_applicationInstance->getCheckSecurityBootstrap() == 'WARN') {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $result .= $instance->getHTML();
        }

        $instance->setMessage($this->_applicationInstance->getCheckSecuritySignMessage());
        if ($this->_applicationInstance->getCheckSecuritySign() == 'WARN') {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_applicationInstance->getCheckSecuritySign() == 'ERROR') {
            $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
            $error = 'er';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $result .= $instance->getHTML();
        }

        $instance->setMessage($this->_applicationInstance->getCheckSecurityURLMessage());
        if ($this->_applicationInstance->getCheckSecurityURL() == 'WARN') {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $result .= $instance->getHTML();
        }

        $instance->setMessage('::warn_ServNotPermitWrite');
        if (!$this->_configurationInstance->getOptionAsBoolean('permitWrite')) {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $result .= $instance->getHTML();
        }

        $instance->setMessage('::warn_flushSessionAndCache');
        if ($this->_nebuleInstance->getCacheInstance()->getFlushCache()) {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $result .= $instance->getHTML();
        }

        if ( $this->_displayOk && $error == 'ok')
        {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $instance->setMessage('::SecurityChecks');
            $result .= $instance->getHTML();
        }

        if ($this->_displayAlone)
            $result .= '</div></div>';

        return $result;
    }

    public function setDisplayAlone(bool $enable): void // TODO peut être mis en commun.
    {
        $this->_displayAlone = $enable;
    }

    public function setDisplayOK(bool $enable): void
    {
        $this->_displayOk = $enable;
    }

    public function setDisplayFull(bool $enable): void
    {
        $this->_displayFull = $enable;
    }

    public static function displayCSS(): void
    {
        ?>

/*
* CSS from <?= basename(str_replace('\\', '/', __CLASS__)) ?>::<?= __FUNCTION__ ?>() on nebule library.
*
* (c) <?= \Nebule\Library\nebule::NEBULE_LICENCE ?> <?= \Nebule\Library\nebule::NEBULE_AUTHOR ?> - <?php echo \Nebule\Library\nebule::NEBULE_WEBSITE . "\n"; ?>
*
* This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
*   License as published by the Free Software Foundation, either version 3 of the License or (at your option) any
*   later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
*   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
*   details.
*
* You should have received a copy of the GNU General Public License along with this program.
*   See at the end of the code file. If not, see https://www.gnu.org/licenses/.
*/
/* Nothing to do. */
        <?php
    }
}
