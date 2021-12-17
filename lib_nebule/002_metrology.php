<?php
declare(strict_types=1);
namespace Nebule\Library;

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
     * Instance de la bibliothèque nebule.
     * @var nebule
     */
    private $_nebuleInstance;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    private $_configuration;

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        $this->_timeStart(); // Démarre le compteur de temps.
        $this->_nebuleInstance = $nebuleInstance;
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_readLogsLevel();
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return 'Metrology';
    }


    /**
     * Incrémente le compteur de liens lus.
     *
     * @return null
     */
    public function addLinkRead()
    {
        $this->_countLinkRead++;
    }

    /**
     * Lit le compteur de liens lus.
     *
     * @return integer
     */
    public function getLinkRead()
    {
        return $this->_countLinkRead;
    }

    /**
     * Incrémente le compteur de liens vérifiés.
     *
     * @return null
     */
    public function addLinkVerify()
    {
        $this->_countLinkVerify++;
    }

    /**
     * Lit le compteur de liens vérifiés.
     *
     * @return integer
     */
    public function getLinkVerify()
    {
        return $this->_countLinkVerify;
    }

    /**
     * Incrémente le compteur d'objets lus.
     *
     * @return null
     */
    public function addObjectRead()
    {
        $this->_countObjectRead++;
    }

    /**
     * Lit le compteur d'objets lus.
     *
     * @return integer
     */
    public function getObjectRead()
    {
        return $this->_countObjectRead;
    }

    /**
     * Incrémente le compteur d'objets vérifiés.
     *
     * @return null
     */
    public function addObjectVerify()
    {
        $this->_countObjectVerify++;
    }

    /**
     * Lit le compteur d'objets vérifiés.
     *
     * @return integer
     */
    public function getObjectVerify()
    {
        return $this->_countObjectVerify;
    }


    /**
     * Démarre le compteur de temps.
     *
     * @return null
     */
    private function _timeStart()
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
    public function getTime()
    {
        return microtime(true) - $this->_timeStart;
    }

    /**
     * Ajoute un temps au tableau des temps intermédiaires.
     *
     * @return null
     */
    public function addTime()
    {
        $time = microtime(true);
        $this->_timeArray[$this->_timeCount] = $time - $this->_timeTemp;
        $this->_timeTemp = $time;
        $this->_timeCount++;
    }

    /**
     * Retourne le tableau des temps intermédiaires.
     *
     * @return array:double
     */
    public function getTimeArray()
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
    private $_logsLevel = 0;

    /**
     * Lit le niveau de log à utiliser par défaut.
     *
     * @return void
     */
    private function _readLogsLevel()
    {
        $this->_permitLogs = $this->_configuration->getOption('permitLogs');
        $level = $this->_configuration->getOption('logsLevel');
        $this->_logsLevel = self::LOG_LEVEL_NORMAL;

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

        if ($this->_logsLevel > self::LOG_LEVEL_ERROR) {
            syslog(LOG_INFO, 'LogT=' . (microtime(true) - $this->_timeStart) . ' LogLdef=' . $this->_logsLevel . '(' . $level . ')');
        }
    }

    /**
     * Change le niveau de log courant.
     *
     * @param integer $level
     * @return void
     */
    public function setLogsLevel($level)
    {
        // Vérifie le droit de journaliser.
        if (!$this->_permitLogs) {
            return;
        }

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

        syslog(LOG_INFO, 'LogT=' . (microtime(true) - $this->_timeStart) . ' LogLset=' . $this->_logsLevel . '(' . $level . ')');
    }

    /**
     * Restaure le niveau de log par défaut.
     *
     * @return void
     */
    public function setDefaultLogsLevel()
    {
        // Vérifie le droit de journaliser.
        if (!$this->_permitLogs) {
            return;
        }

        $level = $this->_configuration->getOption('logsLevel');

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

        syslog(LOG_INFO, 'LogT=' . (microtime(true) - $this->_timeStart) . ' LogLrst=' . $this->_logsLevel . '(' . $level . ')');
    }

    /**
     * Ajoute une ligne dans les logs système.
     *
     * Le niveau doit être :
     *  - Metrology::LOG_LEVEL_NORMAL
     *  - Metrology::LOG_LEVEL_ERROR
     *  - Metrology::LOG_LEVEL_DEVELOP
     *  - Metrology::LOG_LEVEL_FUNCTION
     *  - Metrology::LOG_LEVEL_DEBUG
     *
     * @param string $message
     * @param string $level
     * @return void
     * @todo modifier la gestion de la journalisation pour avoir plusieurs niveaux concurrents.
     *
     */
    public function addLog($message, $level = self::LOG_LEVEL_ERROR)
    {
        // Vérifie le droit de journaliser.
        if (!$this->_permitLogs) {
            return;
        }

        // Extrait le niveau de log demandé.
        $logLevel = self::DEFAULT_LOG_LEVEL;
        if (is_int($level)) {
            $logLevel = $level;
        }
        /*	elseif ( is_string($level) )
		{
			$level = strtoupper($level);
			switch ( $level )
			{
				case 'NORMAL':
					$logLevel = self::LOG_LEVEL_NORMAL;
					break;
				case 'ERROR':
					$logLevel = self::LOG_LEVEL_ERROR;
					break;
				case 'DEVELOP':
					$logLevel = self::LOG_LEVEL_DEVELOP;
					break;
				case 'FUNCTION':
					$logLevel = self::LOG_LEVEL_FUNCTION;
					break;
				case 'DEBUG':
					$logLevel = self::LOG_LEVEL_DEBUG;
					break;
				default:
					$logLevel = self::DEFAULT_LOG_LEVEL;
			}
		}*/

        // Si le niveau de log est suffisant, écrit le message.
        if ($logLevel <= $this->_logsLevel) {
            // Ajoute le niveau du log.
            $message = 'LogL=' . $logLevel . ' ' . $message;

            // Ajout de l'état système en debug.
            if ($logLevel == self::LOG_LEVEL_DEBUG) {
                $message = 'LogM=' . memory_get_usage() . ' ' . $message;
            }

            // Ajoute la marque de temps.
            $message = 'LogT=' . (microtime(true) - $this->_timeStart) . ' LogL=L LogM="' . $message . '"';

            syslog(LOG_INFO, $message);
        }
    }


    /**
     * Ajoute une ligne dans les actions mémorisées.
     * @param string $type
     * @param undefined $action
     * @param boolean $result
     *
     * Type : addlnk addobj addent delobj
     */
    public function addAction($type, $action, $result)
    {
        // Vérifie si on a atteint la limite de remplissage.
        if ($this->_actionCount >= self::DEFAULT_ACTION_STATE_SIZE)
            $this->getFirstAction();

        // Ajoute une nouvelle action.
        $this->_actionArray[$this->_actionCount]['type'] = $type;
        $this->_actionArray[$this->_actionCount]['action'] = $action;
        $this->_actionArray[$this->_actionCount]['result'] = $result;
        $this->_actionCount++;

        $this->addLog($type . ' ' . $action, self::LOG_LEVEL_DEBUG);
    }

    /**
     * Supprime les actions mémorisées.
     */
    public function flushActions()
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
            $this->_actionArray[$this->_actionCount]['type'] = null;
            $this->_actionArray[$this->_actionCount]['action'] = null;
            $this->_actionArray[$this->_actionCount]['result'] = null;
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
