<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Metrology class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Metrology
{
    const DEFAULT_ACTION_STATE_SIZE = 64;

    /**
     * Niveau de log normal, minimal.
     * @var integer
     */
    const LOG_LEVEL_NORMAL = 0;

    /**
     * Niveau de log des erreurs.
     * @var integer
     */
    const LOG_LEVEL_ERROR = 1;

    /**
     * Niveau de log des erreurs.
     * @var integer
     */
    const LOG_LEVEL_DEVELOP = 2;

    /**
     * Niveau de log des fonctions traversées.
     * @var integer
     */
    const LOG_LEVEL_FUNCTION = 4;

    /**
     * Niveau de log complet.
     * @var integer
     */
    const LOG_LEVEL_DEBUG = 8;

    /**
     * Niveau de log par défaut.
     * @var integer
     */
    const DEFAULT_LOG_LEVEL = 1;

    /**
     * Instance de la librairie en cours.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Compteur de liens lus.
     * @var integer
     */
    private $_countLinkRead = 0;

    /**
     * Compteur de liens vérifiés.
     * @var integer
     */
    private $_countLinkVerify = 0;

    /**
     * Compteur d'objets lus.
     * @var integer
     */
    private $_countObjectRead = 0;

    /**
     * Compteur d'objets vérifiés.
     * @var integer
     */
    private $_countObjectVerify = 0;

    /**
     * Temps au démarrage du compteur.
     * @var integer
     */
    private $_timeStart = 0;

    /**
     * Nombre de temps intermédiares.
     * @var integer
     */
    private $_timeCount = 0;

    /**
     * Temps intermédiaire précédent.
     * @var integer
     */
    private $_timeTemp = 0;

    /**
     * Tableau des temps intermédiaires.
     * @var array
     */
    private $_timeArray = array();

    /**
     * Nombre d'actions enregistées.
     * @var integer
     */
    private $_actionCount = 0;

    /**
     * Tableau des actions enregistrées.
     * @var array
     */
    private $_actionArray = array();

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        $this->_timeStart(); // Démarre le compteur de temps.
        $this->_nebuleInstance = $nebuleInstance;
        $this->_setDefaultLogsLevel();
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Metrology';
    }

    /**
     * Incrémente le compteur de liens lus.
     *
     * @return void
     */
    public function addLinkRead(): void
    {
        $this->_countLinkRead++;
    }

    /**
     * Lit le compteur de liens lus.
     *
     * @return integer
     */
    public function getLinkRead(): int
    {
        return $this->_countLinkRead;
    }

    /**
     * Incrémente le compteur de liens vérifiés.
     *
     * @return void
     */
    public function addLinkVerify(): void
    {
        $this->_countLinkVerify++;
    }

    /**
     * Lit le compteur de liens vérifiés.
     *
     * @return integer
     */
    public function getLinkVerify(): int
    {
        return $this->_countLinkVerify;
    }

    /**
     * Incrémente le compteur d'objets lus.
     *
     * @return void
     */
    public function addObjectRead(): void
    {
        $this->_countObjectRead++;
    }

    /**
     * Lit le compteur d'objets lus.
     *
     * @return integer
     */
    public function getObjectRead(): int
    {
        return $this->_countObjectRead;
    }

    /**
     * Incrémente le compteur d'objets vérifiés.
     *
     * @return void
     */
    public function addObjectVerify(): void
    {
        $this->_countObjectVerify++;
    }

    /**
     * Lit le compteur d'objets vérifiés.
     *
     * @return integer
     */
    public function getObjectVerify(): int
    {
        return $this->_countObjectVerify;
    }


    /**
     * Démarre le compteur de temps.
     *
     * @return void
     */
    private function _timeStart(): void
    {
        global $metrologyStartTime;

        $this->_timeStart = $metrologyStartTime;
        $this->_timeTemp = $this->_timeStart;
    }

    /**
     * Retourne le temps depuis le démarrage.
     *
     * @return number
     */
    public function getTime(): int
    {
        return microtime(true) - $this->_timeStart;
    }

    /**
     * Ajoute un temps au tableau des temps intermédiaires.
     *
     * @return void
     */
    public function addTime(): void
    {
        $time = sprintf('%01.6f', microtime(true));
        $this->_timeArray[$this->_timeCount] = $time - $this->_timeTemp;
        $this->_timeTemp = $time;
        $this->_timeCount++;
    }

    /**
     * Retourne le tableau des temps intermédiaires.
     *
     * @return array:double
     */
    public function getTimeArray(): array
    {
        return $this->_timeArray;
    }


    /**
     * Variable si la journalisation est authorisée.
     *
     * @var string
     */
    private $_permitLogs = false;

    /**
     * Variable du niveau de journalisation.
     *
     * @var integer
     */
    private $_logsLevel = self::LOG_LEVEL_NORMAL;

    /**
     * Restaure le niveau de log par défaut.
     *
     * @return void
     */
    private function _setDefaultLogsLevel(): void
    {
        $getPermitLogs = Configuration::getOptionFromEnvironmentAsStringStatic('permitLogs');
        if ($getPermitLogs == 'true')
            $this->_permitLogs = true;
        elseif ($getPermitLogs == 'false')
            $this->_permitLogs = false;
        elseif (Configuration::OPTIONS_DEFAULT_VALUE['permitLogs'] == 'true')
            $this->_permitLogs = true;
        else
            $this->_permitLogs = false;

        $level = Configuration::getOptionFromEnvironmentAsStringStatic('logsLevel');
        if ($level == '')
            $level = Configuration::OPTIONS_DEFAULT_VALUE['logsLevel'];

        $this->setLogsLevel($level);
    }

    /**
     * Change le niveau de log courant.
     *
     * @param string $level
     * @return void
     */
    public function setLogsLevel(string $level): void
    {
        switch ($level) {
            case 'NORMAL':
                $this->_logsLevel = self::LOG_LEVEL_NORMAL;
                break;
            case 'ERROR':
                $this->_logsLevel = self::LOG_LEVEL_ERROR;
                break;
            case 'DEVELOP':
                $this->_logsLevel = self::LOG_LEVEL_DEVELOP;
                break;
            case 'FUNCTION':
                $this->_logsLevel = self::LOG_LEVEL_FUNCTION;
                break;
            case 'DEBUG':
                $this->_logsLevel = self::LOG_LEVEL_DEBUG;
                break;
            default:
                $this->_logsLevel = self::DEFAULT_LOG_LEVEL;
        }

        if ($this->_permitLogs && $this->_logsLevel > self::LOG_LEVEL_ERROR)
            syslog(LOG_INFO, 'LogT=' . sprintf('%01.6f', (float)microtime(true) - $this->_timeStart) . ' LogLset="' . $this->_logsLevel . '(' . $level . ')"');
    }

    /**
     * Ajoute une ligne dans les logs système.
     * Le niveau doit être :
     *  - Metrology::LOG_LEVEL_NORMAL
     *  - Metrology::LOG_LEVEL_ERROR
     *  - Metrology::LOG_LEVEL_DEVELOP
     *  - Metrology::LOG_LEVEL_FUNCTION
     *  - Metrology::LOG_LEVEL_DEBUG
     *
     * @param string $message
     * @param int    $level
     * @param string $function
     * @param string $luid
     * @return void
     * @todo modifier la gestion de la journalisation pour avoir plusieurs niveaux concurrents.
     */
    public function addLog(string $message, int $level = self::LOG_LEVEL_ERROR, string $function = '', string $luid = '00000000'): void
    {
        if (!$this->_permitLogs)
            return;

        // Extrait le niveau de log demandé.
        $logLevel = $level;

        // Si le niveau de log est suffisant, écrit le message.
        if ($logLevel <= $this->_logsLevel) {
            $message = 'LogT=' . sprintf('%01.6f', (float)microtime(true) - $this->_timeStart) . ' LogL="' . $logLevel . '" LogI="' . $luid . '" LogF="' . $function . '" LogM="' . $message . '"';

            if ($logLevel == self::LOG_LEVEL_DEBUG)
                $message = $message . ' LogMem="' . memory_get_usage() . '"';

            syslog(LOG_INFO, $message);
        }
    }


    /**
     * Ajoute une ligne dans les actions mémorisées.
     * Type : addlnk addobj addent delobj
     *
     * @param string  $type
     * @param string  $action
     * @param boolean $result
     */
    public function addAction(string $type, string $action, bool $result): void
    {
        // Vérifie si on a atteint la limite de remplissage.
        if ($this->_actionCount >= self::DEFAULT_ACTION_STATE_SIZE)
            $this->getFirstAction();

        // Ajoute une nouvelle action.
        $this->_actionArray[$this->_actionCount]['type'] = $type;
        $this->_actionArray[$this->_actionCount]['action'] = $action;
        $this->_actionArray[$this->_actionCount]['result'] = $result;
        $this->_actionCount++;

        $this->addLog($type . ' ' . $action, self::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
    }

    /**
     * Supprime les actions mémorisées.
     */
    public function flushActions(): void
    {
        $this->_actionCount = 0;
        $this->_actionArray = array();
    }

    /**
     * Lit la dernière action mémorisée, la plus récente.
     */
    public function getLastAction()
    {
        if ($this->_actionCount > 0) {
            $this->_actionCount--;
            $r = $this->_actionArray[$this->_actionCount];
            $this->_actionArray[$this->_actionCount] = null;
            return $r;
        } else
            return false;
    }

    /**
     * Lit la première action mémorisée, la plus ancienne.
     */
    public function getFirstAction()
    {
        if ($this->_actionCount > 0) {
            $this->_actionCount--;
            $r = $this->_actionArray[0];
            for ($i = 0; $i < $this->_actionCount; $i++)
                $this->_actionArray[$i] = $this->_actionArray[$i + 1];
            return $r;
        } else
            return false;
    }
}
