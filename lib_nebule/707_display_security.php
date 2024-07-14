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
    private $_displayAlone = true;
    private $_displayOk = true;
    private $_displayFull = false;

    public function getHTML(): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $result = '';
        $error = 'ok';

        if ($this->_displayAlone)
            $result .= '<div class="layoutList"><div class="listContent">';

        $instance = new DisplayInformation($this->_applicationInstance);
        $instance->setType(DisplayItemIconMessage::TYPE_WARN);
        $instance->setDisplayAlone(false);
        $instance->setRatio($this->_ratioCSS);
        $instance->setSize($this->_sizeCSS);

        if ($this->_nebuleInstance->getModeRescue()) {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $instance->setMessage('::::RESCUE');
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $instance->setMessage('::::RESCUE');
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

        $instance->setMessage(':::warn_ServNotPermitWrite');
        if (!$this->_configurationInstance->getOptionAsBoolean('permitWrite')) {
            $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $error = 'wr';
            $result .= $instance->getHTML();
        } elseif ($this->_displayFull) {
            $instance->setType(DisplayItemIconMessage::TYPE_OK);
            $result .= $instance->getHTML();
        }

        $instance->setMessage(':::warn_flushSessionAndCache');
        if ($this->_nebuleInstance->getFlushCache()) {
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
            $instance->setMessage('::::SecurityChecks');
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
        echo ''; // TODO
    }
}
