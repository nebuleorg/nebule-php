<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Token generates and checks class for the nebule library.
 * Must be serialized on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Tokenize extends Functions
{
    const SESSION_SAVED_VARS = array(
        '_validToken',
    );

    const TOKEN_SIZE = 256; // Octets

    private bool $_validToken = false;

    protected function _initialisation(): void { $this->_findActionToken(); }



    /**
     * Extract the token from URL and check validity.
     *
     * @return void
     */
    private function _findActionToken(): void {
        $this->_metrologyInstance->addLog('find token', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->getOptionAsBoolean('permitActionWithoutToken')) {
            $this->_metrologyInstance->addLog('check token: permitActionWithoutToken=true', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd767b2ca');
            $this->_validToken = true;
            return;
        }

        $token = $this->getFilterInput(References::COMMAND_TOKEN);
        if (strlen($token) < (self::TOKEN_SIZE / 4) || !ctype_xdigit($token))
            $token = '';

        // Verify token.
        if ($token == '') {
            // No token or null, no action.
            $this->_metrologyInstance->addLog('check token: none', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd396f0a9');
            $this->_validToken = false;
            return;
        }
        session_start();
        if (isset($_SESSION['Tokens'][$token])
            && $_SESSION['Tokens'][$token] !== true
        ) {
            // Token already used, refused, no action.
            $this->_metrologyInstance->addLog('check token: replay ' . $token, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd516f0d4');
            $this->_validToken = false;
            $_SESSION['Tokens'][$token] = false;
        } elseif (isset($_SESSION['Tokens'][$token])
            && $_SESSION['Tokens'][$token] === true
        ) {
            // Valid and not already used token, accepted.
            $this->_metrologyInstance->addLog('check token: valid ' . $token, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '7083b07d');
            $this->_validToken = true;
            $_SESSION['Tokens'][$token] = false;
        } else {
            // Unknown token, refused, no action.
            $this->_metrologyInstance->addLog('check token: error ' . $token, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'b221e760');
            $this->_validToken = false;
        }
        session_write_close();
    }

    /**
     * Return a URL part with the token to validate an action after.
     *
     * @return string
     */
    public function getActionTokenCommand(): string { return '&' . References::COMMAND_TOKEN . '=' . $this->getActionTokenValue(); }

    /**
     * Return the value of a token to validate an action after. The value is stored to be compared after and refuse
     * unvalidated action or double-played token. The value must be included on URL (GET or POST).
     *
     * @return string
     */
    public function getActionTokenValue(): string {
        $this->_metrologyInstance->addLog('get token', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $data = $this->_cryptoInstance->getRandom(self::TOKEN_SIZE / 8, Crypto::RANDOM_PSEUDO);
        $token = bin2hex($data);
        $this->_metrologyInstance->addLog('new token ' . $token, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8957de86');
        session_start();
        $_SESSION['Tokens'][$token] = true;
        session_write_close();
        return $token;
    }

    /**
     * Return if a valide token has been detected on URL.
     *
     * @return boolean
     */
    public function checkActionToken(): bool { return $this->_validToken; }
}
