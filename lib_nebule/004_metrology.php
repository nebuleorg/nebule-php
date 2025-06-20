<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Metrology class for the nebule library.
 * Do not serialize on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 * TODO use LOG_LEVEL_AUDIT to facilitate audit of instance and what people do.
 */
class Metrology extends Functions
{
    const DEFAULT_ACTION_STATE_SIZE = 64;
    const LOG_LEVEL_NORMAL = 0;
    const LOG_LEVEL_ERROR = 1;
    const LOG_LEVEL_AUDIT = 2;
    const LOG_LEVEL_DEVELOP = 4;
    const LOG_LEVEL_FUNCTION = 8;
    const LOG_LEVEL_DEBUG = 16;
    const LOG_LEVEL_NAMES = array(
        '1' => 'error',
        '2' => 'audit',
        '4' => 'develop',
        '8' => 'function',
        '16' => 'debug',
    );
    const DEFAULT_DEBUG_FILE = 'debug';

    private int $_countLinkRead = 0;
    private int $_countLinkVerify = 0;
    private int $_countObjectRead = 0;
    private int $_countObjectVerify = 0;
    private float $_timeStart = 0;
    private float $_timeLastEvent = 0;
    private int $_timeCount = 0;
    private array $_timeArray = array();
    private int $_actionCount = 0;
    private array $_actionArray = array();

    public function __construct(nebule $nebuleInstance){
        parent::__construct($nebuleInstance);
        $this->_setTimeStart();
        $this->_getPermitLogsOnDebugFile();
        $this->_getPermitLogs();
        $this->_getDefaultLogsLevel();
    }

    public function __toString(): string
    {
        return 'Metrology';
    }

    public function addLinkRead(): void
    {
        $this->_countLinkRead++;
    }

    public function getLinkRead(): int
    {
        return $this->_countLinkRead;
    }

    public function addLinkVerify(): void
    {
        $this->_countLinkVerify++;
    }

    public function getLinkVerify(): int
    {
        return $this->_countLinkVerify;
    }

    public function addObjectRead(): void
    {
        $this->_countObjectRead++;
    }

    public function getObjectRead(): int
    {
        return $this->_countObjectRead;
    }

    public function addObjectVerify(): void
    {
        $this->_countObjectVerify++;
    }

    public function getObjectVerify(): int
    {
        return $this->_countObjectVerify;
    }


    private function _setTimeStart(): void
    {
        global $metrologyStartTime;

        $this->_timeStart = $metrologyStartTime;
        $this->_timeLastEvent = $this->_timeStart;
    }

    public function getTime(): float
    {
        return microtime(true) - $this->_timeStart;
    }

    public function addTime(): void
    {
        $time = (float)sprintf('%01.6f', microtime(true));
        $this->_timeArray[$this->_timeCount] = $time - $this->_timeLastEvent;
        $this->_timeLastEvent = $time;
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


    private bool $_permitLogs = false;
    private int $_logsLevel = self::LOG_LEVEL_NORMAL;
    private bool $_permitLogsOnDebugFile = false;

    private function _getPermitLogsOnDebugFile(): void
    {
        // If permitted, write debug level logs to debug file.
        if (Configuration::getOptionFromEnvironmentAsBooleanStatic('permitLogsOnDebugFile'))
        {
            file_put_contents(References::OBJECTS_FOLDER . '/' . Metrology::DEFAULT_DEBUG_FILE, 'START on library ' . $this->_timeStart . "\n", FILE_APPEND);
            $this->_permitLogsOnDebugFile = true;
        }
    }

    private function _getPermitLogs(): void
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

        $this->_addLog('permitLogs=' . (string)$this->_permitLogs, self::LOG_LEVEL_DEBUG, __METHOD__, '10c133be');
    }

    private function _getDefaultLogsLevel(): void
    {
        $level = Configuration::getOptionFromEnvironmentAsStringStatic('logsLevel');
        if ($level == '')
            $level = Configuration::OPTIONS_DEFAULT_VALUE['logsLevel'];

        $this->setLogsLevel($level);
    }

    public function setLogsLevel(string $level): void
    {
        switch ($level) {
            case 'NORMAL':
            case self::LOG_LEVEL_NORMAL:
                $this->_logsLevel = self::LOG_LEVEL_NORMAL;
                $levelName = 'NORMAL';
                break;
            case 'ERROR':
            case self::LOG_LEVEL_ERROR:
                $this->_logsLevel = self::LOG_LEVEL_ERROR;
                $levelName = 'ERROR';
                break;
            case 'DEVELOP':
            case self::LOG_LEVEL_DEVELOP:
                $this->_logsLevel = self::LOG_LEVEL_DEVELOP;
                $levelName = 'DEVELOP';
                break;
            case 'AUDIT':
            case self::LOG_LEVEL_AUDIT:
                $this->_logsLevel = self::LOG_LEVEL_AUDIT;
                $levelName = 'AUDIT';
                break;
            case 'FUNCTION':
            case self::LOG_LEVEL_FUNCTION:
                $this->_logsLevel = self::LOG_LEVEL_FUNCTION;
                $levelName = 'FUNCTION';
                break;
            case 'DEBUG':
            case self::LOG_LEVEL_DEBUG:
                $this->_logsLevel = self::LOG_LEVEL_DEBUG;
                $levelName = 'DEBUG';
                break;
            default:
                $this->_logsLevel = self::LOG_LEVEL_ERROR;
                $levelName = 'ERROR';
                $this->addLog('invalid logs level : '  . $level, self::LOG_LEVEL_ERROR, __METHOD__, '2e57f5d2');
        }

        $this->addLog('logsLevel=' . (string)$this->_logsLevel. ' (' . $levelName . ')', self::LOG_LEVEL_DEBUG, __METHOD__, '63efc8ea');
    }

    /**
     * Add message line to system logs
     *
     * @param string $message
     * @param int    $level Metrology::LOG_LEVEL_xxx
     * @param string $function
     * @param string $luid
     * @return void
     */
    public function addLog(string $message, int $level = self::LOG_LEVEL_ERROR, string $function = '', string $luid = '00000000'): void
    {
        if (!$this->_permitLogs)
            return;
        $this->_addLog($message, $level, $function, $luid);
    }

    private function _addLog(string $message, int $level = self::LOG_LEVEL_ERROR, string $function = '', string $luid = '00000000'): void
    {
        $levelName = 'normal';
        foreach (self::LOG_LEVEL_NAMES as $i => $l)
            if (($level / $i) >= 1 )
                $levelName = $l;

        if ($level <= $this->_logsLevel) {
            $logM = 'LogT=' . sprintf('%01.6f', (float)microtime(true) - $this->_timeStart) . ' LogL="' . $levelName . '(' . $level . ')" LogI="' . $luid . '" LogF="' . $function . '" LogM="' . $message . '"';

            if ($level == self::LOG_LEVEL_DEBUG)
                $logM = $logM . ' LogMem="' . memory_get_usage() . '"';

            syslog(LOG_INFO, $logM);
        }

        if ($this->_permitLogsOnDebugFile)
        {
            $logM = 'LogT=' . sprintf('%01.6f', (float)microtime(true) - $this->_timeStart) . ' LogL="' . $levelName . '(' . $level . ')" LogI="' . $luid . '" LogF="' . $function . '" LogM="' . $message . '"';
            if (file_exists(References::OBJECTS_FOLDER . '/' . Metrology::DEFAULT_DEBUG_FILE))
                file_put_contents(References::OBJECTS_FOLDER . '/' . Metrology::DEFAULT_DEBUG_FILE, $logM . "\n", FILE_APPEND);
        }
    }


    /**
     * Add line in memorized actions.
     *
     * @param string  $type : addlnk addobj addent delobj
     * @param string  $action
     * @param boolean $result
     */
    public function addAction(string $type, string $action, bool $result): void
    {
        if ($this->_actionCount >= self::DEFAULT_ACTION_STATE_SIZE)
            $this->getFirstAction();

        $this->_actionArray[$this->_actionCount]['type'] = $type;
        $this->_actionArray[$this->_actionCount]['action'] = $action;
        $this->_actionArray[$this->_actionCount]['result'] = $result;
        $this->_actionCount++;

        $this->addLog($type . ' ' . $action, self::LOG_LEVEL_DEBUG, __METHOD__, 'cbc2bc52');
    }

    public function flushActions(): void
    {
        $this->_actionCount = 0;
        $this->_actionArray = array();
    }

    public function getLastAction(): array
    {
        if ($this->_actionCount == 0)
            return array();

        $this->_actionCount--;
        $r = $this->_actionArray[$this->_actionCount];
        $this->_actionArray[$this->_actionCount] = null;
        return $r;
    }

    public function getFirstAction(): array
    {
        if ($this->_actionCount == 0)
            return array();

        $this->_actionCount--;
        $r = $this->_actionArray[0];
        for ($i = 0; $i < $this->_actionCount; $i++)
            $this->_actionArray[$i] = $this->_actionArray[$i + 1];
        return $r;
    }

    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#m">M / Métrologie</a>
            <ul>
                <li><a href="#mc">MC / Chronométrage</a></li>
                <li><a href="#ms">MS / Statistiques</a></li>
                <li><a href="#mj">MJ / Journalisation</a>
                    <ul>
                        <li><a href="#mjs">MJS / Journalisation Système</a></li>
                        <li><a href="#mjd">MJD / Debug</a></li>
                        <li><a href="#mjc">MJC / Liste de Codes</a></li>
                    </ul>
                </li>
            </ul>
        </li>

        <?php
    }

    static public function echoDocumentationCore(): void
    {
        ?>

        <?php Displays::docDispTitle(1, 'm', 'Métrologie'); ?>
        <p>Cette partie métrologie rassemble tout ce qui concerne les mesures de performances internes ainsi que la
            journalisation.</p>

        <?php Displays::docDispTitle(2, 'mc', 'Chronométrage'); ?>
        <p>Les mesures de temps sont indiquées avec chaque trace de journalisation :</p>
        <ul>
            <li>Marque temporelle absolue en début de trace (log).</li>
            <li>Marque temporelle relative dans le champ LogT avec en référence le début d'exécution du code.</li>
        </ul>
        <p>Des mesures de temps relatives internes sont écrites avec la toute dernière trace laissée par le bootstrap :</p>
        <ul>
            <li><code>tB</code> : Temps de chargement du boostrap après la bibliothèque PP.</li>
            <li><code>tL</code> : Temps de chargement du boostrap après la bibliothèque POO.</li>
            <li><code>tA</code> : Temps de chargement du boostrap après l'application.</li>
        </ul>
        <p>Ces mesures de temps internes permettent de faire des comparaisons de performances entre serveurs.</p>

        <?php Displays::docDispTitle(2, 'ms', 'Statistiques'); ?>
        <p>Les statistiques d'usage des objets et des liens sont alimentées par certaines fonctions. Les résultats sont
            affichés dans la journalisation avec la toute dernière trace laissée par le bootstrap.</p>
        <p>Les statistiques :</p>
        <ul>
            <li>Mémoire utilisée :
                <ul>
                    <li><code>Mp</code> : Maximum mémoire utilisée lors de l'exécution du code comparée au maximum
                        autorisé, en MBytes. Une valeur au-delà du maximum autorisé dans la configuration PHP (php.ini,
                        option <code>memory_limit</code>) entraîne un arrêt automatique du code.</li>
                </ul>
            </li>
            <li>Mesures de temps : voir <a href="#mc">MC</a></li>
            <li>Mesures d'utilisation :
                <ul>
                    <li><code>Lr</code> : Liens lus (PP+POO).</li>
                    <li><code>Lv</code> : Liens vérifiés (PP+POO).</li>
                    <li><code>Or</code> : Objets lus (PP+POO).</li>
                    <li><code>Ov</code> : Objets vérifiés (PP+POO).</li>
                    <li><code>LC</code> : Liens en cache.</li>
                    <li><code>OC</code> : Objets en cache.</li>
                    <li><code>EC</code> : Entités en cache.</li>
                    <li><code>GC</code> : Groupes en cache.</li>
                    <li><code>CC</code> : Conversations en cache.</li>
                </ul>
            </li>
        </ul>
        <p>Certaines valeurs sont indiquées PP+POO, c'est-à-dire la valeur retournée par la bibliothèque PP avant le +,
            et par la bibliothèque POO après le +.</p>
        <p>La bibliothèque PP ne gère pas de cache, les valeurs de cache concernent uniquement la bibliothèque POO.</p>

        <?php Displays::docDispTitle(2, 'mj', 'Journalisation'); ?>
        <?php Displays::docDispTitle(3, 'mjs', 'Journalisation Système'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'mjd', 'Debug'); ?>
        <p>Si l'option <b>permitLogsOnDebugFile</b> est activée, un fichier
            <i><?php echo References::OBJECTS_FOLDER . '/' . Metrology::DEFAULT_DEBUG_FILE; ?></i>
            est créé puis alimenté avec toutes les traces générées par le bootstrap et les applications indépendamment
            du niveau de journalisation demandé. Ce fichier est utilisé pour du dépannage uniquement. Et ce fichier est
            systématiquement supprimé à chaque exécution du code, au début, quelque soit l'état de l'option
            <b>permitLogsOnDebugFile</b>.</p>

        <?php Displays::docDispTitle(3, 'mjc', 'Liste de Codes'); ?>
        <?php Displays::docDispTitle(4, 'mjcg', 'Codes génériques'); ?>
        <ul>
            <li><code>100000XX</code> : Error. Code générique de traçage des interruptions du bootstrap avec XX le code associé.</li>
            <li><code>1111c0de</code> : Function. Code générique de traçage des appels des fonctions.</li>
        </ul>
        <?php Displays::docDispTitle(4, 'mjcc', 'Codes communs'); ?>
        <p>Liste non exhaustive de codes de journalisation :</p>
        <ul>
            <li><code>76941959</code> : Info. Démarrage du bootstrap.</li>
            <li><code>50615f80</code> : Info. Création du fichier de débug.</li>
            <li><code>41ba02a9</code> : Error. Erreur lors de l'initialisation de l'instance de l'application par le bootstrap.</li>
            <li><code>d121af4c</code> : Error. Erreur lors de l'initialisation de l'instance de traduction de l'application par le bootstrap.</li>
            <li><code>4bb6af65</code> : Error. Erreur lors de l'initialisation de l'instance d'affichage de l'application par le bootstrap.</li>
            <li><code>308b8a96</code> : Error. Erreur lors de l'initialisation de l'instance des actions de l'application par le bootstrap.</li>
            <li style="color: red; font-weight: bold">A compléter...</li>
        </ul>
        <?php Displays::docDispTitle(4, 'mjct', 'Codes ticketing'); ?>
        <ul>
            <li><code>d396f0a9</code> : Debug. Ticket vide.</li>
            <li><code>d516f0d4</code> : Error. Ticket déjà utilisé, tentative de rejeu de ticket.</li>
            <li><code>7083b07d</code> : Audit. Ticket valide.</li>
            <li><code>b221e760</code> : Error. Ticket inconnu.</li>
            <li><code>8957de86</code> : Debug. Valeur du nouveau ticket généré.</li>
            <li><code>d767b2ca</code> : Debug. Option permitActionWithoutTicket=true ticket always valid.</li>
            <li><code>80fa0154</code> : Error. Cannot get ticket from GET.</li>
            <li><code>65b5e0cc</code> : Error. Cannot get ticket from POST.</li>
        </ul>

        <?php
	}
}
