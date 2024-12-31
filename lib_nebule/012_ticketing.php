<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Configuration class for the nebule library.
 * Must be serialized on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Ticketing extends Functions
{
    const SESSION_SAVED_VARS = array(
        '_validTicket',
    );

    const TICKET_SIZE = 256; // Octet

    private bool $_validTicket = false;

    protected function _initialisation(): void
    {
        $this->_findActionTicket();
    }



    /**
     * Extract ticket from URL and check validity.
     *
     * @return void
     */
    private function _findActionTicket(): void
    {
        $ticket = '';
        try {
            $arg_get = (string)filter_input(INPUT_GET, References::COMMAND_SELECT_TICKET, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW | FILTER_NULL_ON_FAILURE);
            $arg_get = trim($arg_get);
        } catch (\Exception $e) {
            $arg_get = '';
        }
        try {
            $arg_post = (string)filter_input(INPUT_POST, References::COMMAND_SELECT_TICKET, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW | FILTER_NULL_ON_FAILURE);
            $arg_post = trim($arg_post);
        } catch (\Exception $e) {
            $arg_post = '';
        }

        if ($arg_get != '' && strlen($arg_get) >= self::TICKET_SIZE && ctype_xdigit($arg_get)
        )
            $ticket = $arg_get;
        elseif ($arg_post != '' && strlen($arg_post) >= self::TICKET_SIZE && ctype_xdigit($arg_post)
        )
            $ticket = $arg_post;

        // Verify ticket.
        if ($ticket == '') {
            // No ticket or null, no action.
            $this->_metrologyInstance->addLog('check ticket: none', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd396f0a9');
            $this->_validTicket = false;
            return;
        }
        session_start();
        if (isset($_SESSION['Ticket'][$ticket])
            && $_SESSION['Tickets'][$ticket] !== true
        ) {
            // Ticket already used, refused, no action.
            $this->_metrologyInstance->addLog('check ticket: replay ' . $ticket, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd516f0d4');
            $this->_validTicket = false;
            $_SESSION['Ticket'][$ticket] = false;
        } elseif (isset($_SESSION['Ticket'][$ticket])
            && $_SESSION['Tickets'][$ticket] === true
        ) {
            // Valid and not already used ticket, accepted.
            $this->_metrologyInstance->addLog('check ticket: valid ' . $ticket, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '7083b07d');
            $this->_validTicket = true;
            $_SESSION['Tickets'][$ticket] = false;
        } else {
            // Unknown ticket, refused, no action.
            $this->_metrologyInstance->addLog('check ticket: error ' . $ticket, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'b221e760');
            $this->_validTicket = false;
        }
        session_write_close();
    }

    /**
     * Return a URL part with ticket.
     *
     * @return string
     */
    public function getActionTicketCommand(): string
    {
        return '&' . References::COMMAND_SELECT_TICKET . '=' . $this->getActionTicketValue();
    }

    /**
     * Return the value of a ticket to validate an action after. The value is stored to be compared after and refuse
     * unvalidated action or double played ticket. The value must be included on URL (GET or POST).
     *
     * @return string
     */
    public function getActionTicketValue(): string
    {
        //$data = $this->_cryptoInstance->getRandom(self::TICKET_SIZE, Crypto::RANDOM_PSEUDO);
        $data = $this->_cryptoInstance->getRandom(self::TICKET_SIZE / 8, Crypto::RANDOM_PSEUDO);
        //$ticket = $this->_cryptoInstance->hash($data);
        $ticket = bin2hex($data);
        $this->_metrologyInstance->addLog('new ticket ' . $ticket, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8957de86');
        session_start();
        $_SESSION['Tickets'][$ticket] = true;
        session_write_close();
        return $ticket;
    }

    /**
     * Return if a valide ticket have been detected on URL.
     *
     * @return boolean
     */
    public function checkActionTicket(): bool
    {
        return $this->_validTicket;
    }

}
