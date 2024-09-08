<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Token class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Tokenizing extends Functions
{
    private string $_currentTokenID = '';
    private ?Token $_currentTokenInstance = null;

    protected function _initialisation()
    {
        $this->_findCurrentToken();
    }



    private function _findCurrentToken()
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentTokenID = '0';
            $this->_currentTokenInstance = $this->_cacheInstance->newToken('0');
            $this->_sessionInstance->setSessionStore('nebuleSelectedToken', $this->_currentTokenID);
            return;
        }

        if (filter_has_var(INPUT_GET, References::COMMAND_SELECT_TOKEN))
            $arg = trim(' ' . filter_input(INPUT_GET, References::COMMAND_SELECT_TOKEN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg = trim(' ' . filter_input(INPUT_POST, References::COMMAND_SELECT_TOKEN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg, false, true)
            && ($this->_ioInstance->checkObjectPresent($arg)
                || $this->_ioInstance->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentTokenID = $arg;
            $this->_currentTokenInstance = $this->_cacheInstance->newToken($arg);
            $this->_sessionInstance->setSessionStore('nebuleSelectedToken', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStore('nebuleSelectedToken');
            if ($cache !== false  && $cache != '') {
                $this->_currentTokenID = $cache;
                $this->_currentTokenInstance = $this->_cacheInstance->newToken($cache);
            } else {
                $this->_currentTokenID = '0';
                $this->_currentTokenInstance = $this->_cacheInstance->newToken('0');
                $this->_sessionInstance->setSessionStore('nebuleSelectedToken', $this->_currentTokenID);
            }
            unset($cache);
        }
        unset($arg);

        $this->_metrologyInstance->addLog('Find current token ' . $this->_currentTokenID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0ccb0886');
    }

    public function getCurrentTokenID(): string
    {
        return $this->_currentTokenID;
    }

    public function getCurrentTokenInstance(): ?Token
    {
        return $this->_currentTokenInstance;
    }
}
