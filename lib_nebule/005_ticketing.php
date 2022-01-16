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
class Ticketing
{    
    /**
     * Instance de la librairie en cours.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance de la métrologie.
     *
     * @var Metrology
     */
    private $_metrology;

    /**
     * Instance de gestion de la cryptographie.
     *
     * @var CryptoInterface
     */
    private $_crypto;

    /**
     * Instance de gestion du cache.
     *
     * @var Cache
     */
    protected $_cache;

    /**
     * Contient l'état de validité du ticket des actions.
     * @var boolean
     */
    private $_validTicket = false;

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();
        $this->_findActionTicket();
    }

    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();
        $this->_findActionTicket();
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
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
 		 *  ------------------------------------------------------------------------------------------
		 */
        $ticket = '0';
        // Lit et nettoye le contenu de la variable GET.
        $arg_get = trim(' ' . filter_input(INPUT_GET, nebule::COMMAND_SELECT_TICKET, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        // Lit et nettoye le contenu de la variable POST.
        $arg_post = trim(' ' . filter_input(INPUT_POST, nebule::COMMAND_SELECT_TICKET, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        // Vérifie les variables.
        if ($arg_get != ''
            && strlen($arg_get) >= nebule::NEBULE_MINIMUM_ID_SIZE
            && ctype_xdigit($arg_get)
        ) {
            $ticket = $arg_get;
        } elseif ($arg_post != ''
            && strlen($arg_post) >= nebule::NEBULE_MINIMUM_ID_SIZE
            && ctype_xdigit($arg_post)
        ) {
            $ticket = $arg_post;
        }
        unset($arg_get, $arg_post);

        // Vérifie le ticket.
        session_start();
        if ($ticket == '0'
            //|| $this->_flushCache TODO gérer le cas du flush de cache
        ) {
            // Le ticket est null, aucun ticket trouvé en argument.
            // Aucune action ne doit être réalisée.
            $this->_metrology->addLog('Ticket: none', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
            $this->_validTicket = false;
        } elseif (isset($_SESSION['Ticket'][$ticket])
            && $_SESSION['Ticket'][$ticket] !== true
        ) {
            // Le ticket est déjà connu mais est déjà utilisé, c'est un rejeu.
            // Aucune action ne doit être réalisée.
            $this->_metrology->addLog('Ticket: replay ' . $ticket, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000'); // Log
            $this->_validTicket = false;
            $_SESSION['Ticket'][$ticket] = false;
        } elseif (isset($_SESSION['Ticket'][$ticket])
            && $_SESSION['Ticket'][$ticket] === true
        ) {
            // Le ticket est connu et n'est pas utilisé, c'est bon.
            // Il est marqué maintenant comme utilisé.
            // Les actions peuvent être réalisées.
            $this->_metrology->addLog('Ticket: valid ' . $ticket, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
            $this->_validTicket = true;
            $_SESSION['Ticket'][$ticket] = false;
        } else {
            // Le ticket est inconnu.
            // Pas de mémorisation.
            // Aucune action ne doit être réalisée.
            $this->_metrology->addLog('Ticket: error ' . $ticket, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000'); // Log
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
    public function getActionTicket(): string
    {
        return '&' . nebule::COMMAND_SELECT_TICKET . '=' . $this->getActionTicketValue();
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
        session_start();
        $data = $this->_crypto->getRandom(2048, Crypto::RANDOM_PSEUDO);
        $ticket = $this->_crypto->hash($data);
        unset($data);
        $_SESSION['Ticket'][$ticket] = true;
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
