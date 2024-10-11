<?php
declare(strict_types=1);
namespace Nebule\Library;

    /**
     * Configuration class for the nebule library.
     *
     * @author Projet nebule
     * @license GNU GPLv3
     * @copyright Projet nebule
     * @link www.nebule.org
     */
class Ticketing extends Functions
{
    const TICKET_SIZE = 256; // Octet

    private bool $_validTicket = false;

    protected function _initialisation(): void
    {
        $this->_findActionTicket();
        $this->_metrologyInstance->addLog('instancing class Ticketing', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, 'e1e0a7f3');
    }



    /**
     * Lit le ticket pour les actions et le valide si il est connu et non utilisé.
     * Le ticket reconnu est marqué dans la liste des ticket afin d'interdire le rejeu.
     * Le ticket inconnu n'est pas marqué afin d'empêcher une attaque par remplissage.
     *
     * Pour que certaines actions puissent être validées, un ticket doit être présenté dans l'URL.
     * Le ticket doit être connu, valide et non utilisé.
     *
     * @return void
     */
    private function _findActionTicket(): void
    {
        $ticket = '0';
        // Lit et nettoie le contenu de la variable GET.
        $arg_get = trim(' ' . filter_input(INPUT_GET, References::COMMAND_SELECT_TICKET, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        // Lit et nettoie le contenu de la variable POST.
        $arg_post = trim(' ' . filter_input(INPUT_POST, References::COMMAND_SELECT_TICKET, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        // Vérifie les variables.
        if ($arg_get != ''
            && strlen($arg_get) >= self::TICKET_SIZE
            && ctype_xdigit($arg_get)
        ) {
            $ticket = $arg_get;
        } elseif ($arg_post != ''
            && strlen($arg_post) >= self::TICKET_SIZE
            && ctype_xdigit($arg_post)
        ) {
            $ticket = $arg_post;
        }
        unset($arg_get, $arg_post);

        // Vérifie le ticket.
        session_start();
        if ($ticket == '0') {
            // Le ticket est null, aucun ticket trouvé en argument.
            // Aucune action ne doit être réalisée.
            $this->_metrologyInstance->addLog('Ticket: none', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'd396f0a9'); // Log
            $this->_validTicket = false;
        } elseif (isset($_SESSION['Ticket'][$ticket])
            && $_SESSION['Tickets'][$ticket] !== true
        ) {
            // Le ticket est déjà connu mais est déjà utilisé, c'est un rejeu.
            // Aucune action ne doit être réalisée.
            $this->_metrologyInstance->addLog('Ticket: replay ' . $ticket, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, 'd516f0d4'); // Log
            $this->_validTicket = false;
            $_SESSION['Ticket'][$ticket] = false;
        } elseif (isset($_SESSION['Ticket'][$ticket])
            && $_SESSION['Tickets'][$ticket] === true
        ) {
            // Le ticket est connu et n'est pas utilisé, c'est bon.
            // Il est marqué maintenant comme utilisé.
            // Les actions peuvent être réalisées.
            $this->_metrologyInstance->addLog('Ticket: valid ' . $ticket, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '7083b07d'); // Log
            $this->_validTicket = true;
            $_SESSION['Tickets'][$ticket] = false;
        } else {
            // Le ticket est inconnu.
            // Pas de mémorisation.
            // Aucune action ne doit être réalisée.
            $this->_metrologyInstance->addLog('Ticket: error ' . $ticket, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, 'b221e760'); // Log
            $this->_validTicket = false;
        }
        session_write_close();
        unset($ticket);
    }

    /**
     * Génère un ticket pour valider une action et interdire le rejeu d'action.
     * Stock le ticket pour vérification ultérieure.
     * Retourne le ticket avec la ligne pour insertion directe dans une url.
     *
     * Pour que certaines actions puissent être validées, un ticket doit être présenté dans l'URL.
     * Le ticket doit être connu, valide et non utilisé.
     *
     * @return string
     */
    public function getActionTicketCommand(): string
    {
        return '&' . References::COMMAND_SELECT_TICKET . '=' . $this->getActionTicketValue();
    }

    /**
     * Génère un ticket pour valider une action et interdire le rejeu d'action.
     * Stock le ticket pour vérification ultérieure.
     * Retourne la valeur du ticket.
     *
     * La valeur de référence est pseudo-aléatoire mais suffisante pour résister
     *   à une attaque le temps d'une session utilisateur.
     *
     * Pour que certaines actions puissent être validées, un ticket doit être présenté dans l'URL.
     * Le ticket doit être connu, valide et non utilisé.
     *
     * @return string
     */
    public function getActionTicketValue(): string
    {
        $data = $this->_nebuleInstance->getCryptoInstance()->getRandom(self::TICKET_SIZE, Crypto::RANDOM_PSEUDO);
        $ticket = $this->_nebuleInstance->getCryptoInstance()->hash($data);
        session_start();
        $_SESSION['Tickets'][$ticket] = true;
        session_write_close();
        return $ticket;
    }

    /**
     * Vérifie que le ticket est connu, valide et non utilisé.
     *
     * Pour que certaines actions puissent être validées, un ticket doit être présenté dans l'URL.
     * Le ticket doit être connu, valide et non utilisé.
     *
     * @return boolean
     */
    public function checkActionTicket(): bool
    {
        return $this->_validTicket;
    }

}
