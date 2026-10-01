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

    public function __construct(nebule $nebuleInstance)
    {
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

    static public function log_reopen(string $name): void
    {
        global $loggerSessionID;
        closelog();
        openlog($name . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
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
        if (Configuration::getOptionFromEnvironmentAsBooleanStatic('permitLogsOnDebugFile')) {
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
                $this->addLog('invalid logs level : ' . $level, self::LOG_LEVEL_ERROR, __METHOD__, '2e57f5d2');
        }

        $this->addLog('logsLevel=' . (string)$this->_logsLevel . ' (' . $levelName . ')', self::LOG_LEVEL_DEBUG, __METHOD__, '63efc8ea');
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
            if (($level / $i) >= 1)
                $levelName = $l;

        if ($level <= $this->_logsLevel) {
            $logM = 'LogT=' . sprintf('%01.6f', (float)microtime(true) - $this->_timeStart) . ' LogL="' . $levelName . '(' . $level . ')" LogI="' . $luid . '" LogF="' . $function . '" LogM="' . $message . '"';

            if ($level == self::LOG_LEVEL_DEBUG)
                $logM = $logM . ' LogMem="' . memory_get_usage() . '"';

            syslog(LOG_INFO, $logM);
        }

        if ($this->_permitLogsOnDebugFile) {
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
}



abstract class HelpMetrology {
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
                        <li><a href="#mjc">MJC / Liste de Codes</a>
                            <ul>
                                <li><a href="#mjcg">MJCG / Codes génériques</a></li>
                                <li><a href="#mjcb">MJCB / Codes bootstrap</a></li>
                                <li><a href="#mjcc">MJCC / Codes communs</a></li>
                                <li><a href="#mjct">MJCT / Codes tokenize</a></li>
                                <li><a href="#mjcl">MJCL / Codes librairie</a></li>
                            </ul>
                        </li>
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
            <li><code>tP</code> : Temps de chargement du boostrap après préchargement de l'application.</li>
            <li><code>tA</code> : Temps de chargement du boostrap après l'application sans préchargement.</li>
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
        <?php Displays::docDispTitle(4, 'mjcb', 'Codes bootstrap'); ?>
        <p>Codes spécifiques au bootstrap.php (librairie procédurale) :</p>
        <ul>
            <li><code>df175c8c</code> : Info. 1: on error.</li>
            <li><code>a844ce2c</code> : Info. 2: bootstrap.</li>
            <li><code>76c10058</code> : Info. 3: library PP.</li>
            <li><code>811b1513</code> : Info. 4: library POO.</li>
            <li><code>7f1941a1</code> : Info. 5: application.</li>
            <li><code>03a39126</code> : Info. 6: end.</li>
            <li><code>82311cae</code> : Info. Affichage inline demandé.</li>
            <li><code>529d21e0</code> : Info. Loading.</li>
            <li><code>63d9bc00</code> : Info. Chargement initial (load first).</li>
            <li><code>4abf554b</code> : Info. Interruption de chargement (load break).</li>
            <li><code>555ec326</code> : Info. Écriture du puppetmaster par défaut.</li>
            <li><code>01fc1f8f</code> : Info. Sérialisation de la classe Nebule\Library\nebule.</li>
            <li><code>76941959</code> : Info. Démarrage du bootstrap.</li>
            <li><code>50615f80</code> : Info. Création du fichier de debug.</li>
            <li><code>f060a74c</code> : Info. Initialisation du fichier de debug.</li>
            <li><code>e0a8d6a5</code> : Info. Track functions (POO).</li>
            <li><code>75d12813</code> : Info. Track functions.</li>
            <li><code>82655082</code> : Info. Track functions.</li>
            <li><code>2afa52df</code> : Info. Track functions.</li>
            <li><code>3da38181</code> : Info. Track functions.</li>
            <li><code>c43675e6</code> : Info. Track functions.</li>
            <li><code>bc32d2f4</code> : Info. Track functions.</li>
            <li><code>bfe82100</code> : Info. Track functions.</li>
            <li><code>68c50ba0</code> : Info. Dossiers OK.</li>
            <li><code>91e9b5bd</code> : Info. Fichier de configuration présent (ok have options file).</li>
            <li><code>5c7be016</code> : Info. Objets OK.</li>
            <li><code>ba3772d3</code> : Info. Objets OK.</li>
            <li><code>c5b55957</code> : Info. Autorité de synchronisation OK.</li>
            <li><code>4473358f</code> : Info. Synchronisation des objets OK.</li>
            <li><code>549a608d</code> : Normal. Synchronisation OK.</li>
            <li><code>70a99b83</code> : Info. Requête de l'EID du puppetmaster.</li>
            <li><code>213a735c</code> : Info. Requête de l'EID de subordination.</li>
            <li><code>7f3a8e2d</code> : Debug. Retour de la branche mise en cache (returning cached branch).</li>
            <li><code>d7aa7cac</code> : Info. Recherche de la branche de code.</li>
            <li><code>d8570c45</code> : Info. NID de branche de code direct trouvé.</li>
            <li><code>bfcb9967</code> : Debug. Recherche du RID de branche de code.</li>
            <li><code>9f1bf579</code> : Info. NID de branche de code trouvé.</li>
            <li><code>83af2589</code> : Error. Aucune branche de code trouvée dans la configuration.</li>
            <li><code>83af258a</code> : Warning. Aucun lien de branche de code trouvé.</li>
            <li><code>83af258b</code> : Warning. Aucune branche de code correspondante trouvée pour le nom.</li>
        </ul>
        <?php Displays::docDispTitle(4, 'mjcc', 'Codes communs'); ?>
        <p>Liste non exhaustive de codes de journalisation (applications) :</p>
        <ul>
            <li><code>41ba02a9</code> : Error. Erreur lors de l'initialisation de l'instance de l'application par le bootstrap.</li>
            <li><code>d121af4c</code> : Error. Erreur lors de l'initialisation de l'instance de traduction de l'application par le bootstrap.</li>
            <li><code>4bb6af65</code> : Error. Erreur lors de l'initialisation de l'instance d'affichage de l'application par le bootstrap.</li>
            <li><code>308b8a96</code> : Error. Erreur lors de l'initialisation de l'instance des actions de l'application par le bootstrap.</li>
            <li style="color: red; font-weight: bold">A compléter...</li>
        </ul>
        <?php Displays::docDispTitle(4, 'mjct', 'Codes tokenize'); ?>
        <ul>
            <li><code>d396f0a9</code> : Debug. Ticket vide.</li>
            <li><code>d516f0d4</code> : Error. Ticket déjà utilisé, tentative de rejeu de ticket.</li>
            <li><code>7083b07d</code> : Audit. Ticket valide.</li>
            <li><code>b221e760</code> : Error. Ticket inconnu.</li>
            <li><code>8957de86</code> : Debug. Generate new token.</li>
            <li><code>d767b2ca</code> : Debug. Option permitActionWithoutToken=true token always valid.</li>
            <li><code>80fa0154</code> : Error. Cannot get token from GET.</li>
            <li><code>65b5e0cc</code> : Error. Cannot get token from POST.</li>
        </ul>

        <?php Displays::docDispTitle(4, 'mjcl', 'Codes librairie'); ?>
        <p>Liste non exhaustive de codes de journalisation (librairie orientée objet) :</p>
        <ul>
            <li><code>0074f2c6</code> : .</li>
            <li><code>00c8aa17</code> : .</li>
            <li><code>00fde855</code> : .</li>
            <li><code>0237217e</code> : .</li>
            <li><code>024f57bf</code> : .</li>
            <li><code>04260a5e</code> : .</li>
            <li><code>046d378a</code> : .</li>
            <li><code>04a98c79</code> : .</li>
            <li><code>050783df</code> : .</li>
            <li><code>06a13897</code> : .</li>
            <li><code>07a97058</code> : .</li>
            <li><code>07edae7d</code> : .</li>
            <li><code>081f9805</code> : .</li>
            <li><code>09c0ab8c</code> : .</li>
            <li><code>09c33dba</code> : .</li>
            <li><code>0a2485f6</code> : .</li>
            <li><code>0ac7d800</code> : .</li>
            <li><code>0ac7ff91</code> : .</li>
            <li><code>0acf655b</code> : .</li>
            <li><code>0af33bed</code> : .</li>
            <li><code>0bcdaa0d</code> : .</li>
            <li><code>0bd80061</code> : .</li>
            <li><code>0c070b7e</code> : .</li>
            <li><code>0ccb0886</code> : .</li>
            <li><code>0cdd6bb5</code> : .</li>
            <li><code>0db13a8a</code> : .</li>
            <li><code>0edc11b2</code> : .</li>
            <li><code>0ee97bc0</code> : .</li>
            <li><code>0f7aa932</code> : .</li>
            <li><code>0f80807b</code> : .</li>
            <li><code>0fba3fab</code> : .</li>
            <li><code>1080aa16</code> : .</li>
            <li><code>10d4a3fc</code> : .</li>
            <li><code>11a6587c</code> : .</li>
            <li><code>11b0f733</code> : .</li>
            <li><code>11d4fafb</code> : .</li>
            <li><code>12ba7b66</code> : .</li>
            <li><code>130bc586</code> : .</li>
            <li><code>13ac7fd6</code> : .</li>
            <li><code>13cb1fd7</code> : .</li>
            <li><code>13f7b8f9</code> : .</li>
            <li><code>13fab565</code> : .</li>
            <li><code>148b111d</code> : .</li>
            <li><code>1520ed55</code> : .</li>
            <li><code>16aa56f1</code> : .</li>
            <li><code>17bc6adc</code> : .</li>
            <li><code>18852ac4</code> : .</li>
            <li><code>18dbcb17</code> : .</li>
            <li><code>18ee9ded</code> : .</li>
            <li><code>19029717</code> : .</li>
            <li><code>1913eb68</code> : .</li>
            <li><code>1b83a0d1</code> : .</li>
            <li><code>1c7bd988</code> : .</li>
            <li><code>1ccc4fe2</code> : .</li>
            <li><code>1dc46c1a</code> : .</li>
            <li><code>1ddcee4c</code> : .</li>
            <li><code>2067738b</code> : .</li>
            <li><code>21d5ead8</code> : .</li>
            <li><code>21dc60cc</code> : .</li>
            <li><code>226ce8be</code> : .</li>
            <li><code>229b08c9</code> : .</li>
            <li><code>2300b439</code> : .</li>
            <li><code>23a4e2d9</code> : .</li>
            <li><code>2446015f</code> : .</li>
            <li><code>253cd7cf</code> : .</li>
            <li><code>2618d5b0</code> : .</li>
            <li><code>2644d3a7</code> : .</li>
            <li><code>26a04f10</code> : .</li>
            <li><code>27b77f2d</code> : .</li>
            <li><code>27c698ab</code> : .</li>
            <li><code>27d21ac9</code> : .</li>
            <li><code>28feadb6</code> : .</li>
            <li><code>293d8170</code> : .</li>
            <li><code>2a04d29d</code> : .</li>
            <li><code>2a6da5e1</code> : .</li>
            <li><code>2b4c9b6b</code> : .</li>
            <li><code>2c57d443</code> : .</li>
            <li><code>2df08836</code> : .</li>
            <li><code>2f5d9e5a</code> : .</li>
            <li><code>30344086</code> : .</li>
            <li><code>30d08e02</code> : .</li>
            <li><code>314e6e9b</code> : .</li>
            <li><code>319eab8f</code> : .</li>
            <li><code>3262ce87</code> : .</li>
            <li><code>32b4a8f1</code> : .</li>
            <li><code>331f2c5b</code> : .</li>
            <li><code>34ce6d2c</code> : .</li>
            <li><code>34ef9500</code> : .</li>
            <li><code>3553b65b</code> : .</li>
            <li><code>36e5871a</code> : .</li>
            <li><code>376c6fa5</code> : .</li>
            <li><code>38022519</code> : .</li>
            <li><code>380638fc</code> : .</li>
            <li><code>38f0db2c</code> : .</li>
            <li><code>397ce035</code> : .</li>
            <li><code>3998a68d</code> : .</li>
            <li><code>3a4c8867</code> : .</li>
            <li><code>3a5c4178</code> : .</li>
            <li><code>3ab22f3a</code> : .</li>
            <li><code>3ab8726b</code> : .</li>
            <li><code>3ae7eea2</code> : .</li>
            <li><code>3aeed4ed</code> : .</li>
            <li><code>3b3255d5</code> : .</li>
            <li><code>3b3440f7</code> : .</li>
            <li><code>3bbb0aa7</code> : .</li>
            <li><code>3c5e617d</code> : .</li>
            <li><code>3e39271b</code> : .</li>
            <li><code>3e508b5f</code> : .</li>
            <li><code>3e9b68d3</code> : .</li>
            <li><code>3f8451cb</code> : .</li>
            <li><code>40b3486e</code> : .</li>
            <li><code>41e23a37</code> : .</li>
            <li><code>41f64673</code> : .</li>
            <li><code>41f84b7f</code> : .</li>
            <li><code>425694ce</code> : .</li>
            <li><code>4299833c</code> : .</li>
            <li><code>435ee7f6</code> : .</li>
            <li><code>43714573</code> : .</li>
            <li><code>43c10796</code> : .</li>
            <li><code>43e6baa9</code> : .</li>
            <li><code>44f2509d</code> : .</li>
            <li><code>451a8518</code> : .</li>
            <li><code>4555fd63</code> : .</li>
            <li><code>46f04cd0</code> : .</li>
            <li><code>46fcbf07</code> : .</li>
            <li><code>474676ed</code> : .</li>
            <li><code>486f6813</code> : .</li>
            <li><code>48b7be4e</code> : .</li>
            <li><code>49374854</code> : .</li>
            <li><code>49eedd04</code> : .</li>
            <li><code>4b67de69</code> : .</li>
            <li><code>4b9d4a4f</code> : .</li>
            <li><code>4c897dd6</code> : .</li>
            <li><code>4c921139</code> : .</li>
            <li><code>4d5732c9</code> : .</li>
            <li><code>4dde2a79</code> : .</li>
            <li><code>4df6e410</code> : .</li>
            <li><code>4ea9f4d7</code> : .</li>
            <li><code>4ef37f6e</code> : .</li>
            <li><code>4efbc71f</code> : .</li>
            <li><code>4f299627</code> : .</li>
            <li><code>4f637f32</code> : .</li>
            <li><code>5070a01f</code> : .</li>
            <li><code>50a09db3</code> : .</li>
            <li><code>53556863</code> : .</li>
            <li><code>535b1337</code> : .</li>
            <li><code>5469549b</code> : .</li>
            <li><code>558f764f</code> : .</li>
            <li><code>55fba077</code> : .</li>
            <li><code>56a98331</code> : .</li>
            <li><code>5703836f</code> : .</li>
            <li><code>5737927d</code> : .</li>
            <li><code>58104e81</code> : .</li>
            <li><code>585b4766</code> : .</li>
            <li><code>5a30c149</code> : .</li>
            <li><code>5a444198</code> : .</li>
            <li><code>5b2dc1ed</code> : .</li>
            <li><code>5bb68dab</code> : .</li>
            <li><code>5c979fa2</code> : .</li>
            <li><code>5d4eb6f0</code> : .</li>
            <li><code>5deb30a7</code> : .</li>
            <li><code>5e464992</code> : .</li>
            <li><code>5edb0ddf</code> : .</li>
            <li><code>5f83d258</code> : .</li>
            <li><code>60c4aa2a</code> : .</li>
            <li><code>61d0b13c</code> : .</li>
            <li><code>634519a2</code> : .</li>
            <li><code>63781bce</code> : .</li>
            <li><code>63de2900</code> : .</li>
            <li><code>64154189</code> : .</li>
            <li><code>652ba93f</code> : .</li>
            <li><code>6636179a</code> : .</li>
            <li><code>6770ac3f</code> : .</li>
            <li><code>67aaf050</code> : .</li>
            <li><code>67d18e91</code> : .</li>
            <li><code>68634e7f</code> : .</li>
            <li><code>695d463e</code> : .</li>
            <li><code>6993176f</code> : .</li>
            <li><code>69a9562e</code> : .</li>
            <li><code>6bcf2c7e</code> : .</li>
            <li><code>6c89868a</code> : .</li>
            <li><code>6d2f16cb</code> : .</li>
            <li><code>6efad36a</code> : .</li>
            <li><code>6f9dfb64</code> : .</li>
            <li><code>6fcb7d18</code> : .</li>
            <li><code>70704574</code> : .</li>
            <li><code>70b97981</code> : .</li>
            <li><code>71301675</code> : .</li>
            <li><code>72a3452d</code> : .</li>
            <li><code>73233c1c</code> : .</li>
            <li><code>742f5e81</code> : .</li>
            <li><code>74686ed7</code> : .</li>
            <li><code>75819b9c</code> : .</li>
            <li><code>75cb6bf4</code> : .</li>
            <li><code>780fd82a</code> : .</li>
            <li><code>78517959</code> : .</li>
            <li><code>792ea58c</code> : .</li>
            <li><code>7af6f9fe</code> : .</li>
            <li><code>7b4f89ef</code> : .</li>
            <li><code>7c70c571</code> : .</li>
            <li><code>7cd85d87</code> : .</li>
            <li><code>7e21187d</code> : .</li>
            <li><code>7e720681</code> : .</li>
            <li><code>7e7aeaed</code> : .</li>
            <li><code>7f03457f</code> : .</li>
            <li><code>7f459074</code> : .</li>
            <li><code>7f72506b</code> : .</li>
            <li><code>80048058</code> : .</li>
            <li><code>80db7d9f</code> : .</li>
            <li><code>811a12be</code> : .</li>
            <li><code>81385b9d</code> : .</li>
            <li><code>82a6ada5</code> : .</li>
            <li><code>82b83c17</code> : .</li>
            <li><code>82cd76b7</code> : .</li>
            <li><code>8318122c</code> : .</li>
            <li><code>83445a74</code> : .</li>
            <li><code>83a27d1e</code> : .</li>
            <li><code>83fb3258</code> : .</li>
            <li><code>84b627f1</code> : .</li>
            <li><code>84c305e5</code> : .</li>
            <li><code>85e057f9</code> : .</li>
            <li><code>865076e1</code> : .</li>
            <li><code>8683e195</code> : .</li>
            <li><code>884a3959</code> : .</li>
            <li><code>88ff0291</code> : .</li>
            <li><code>89f37a59</code> : .</li>
            <li><code>8a077f11</code> : .</li>
            <li><code>8a83ad5f</code> : .</li>
            <li><code>8e3d67f6</code> : .</li>
            <li><code>8eb05394</code> : .</li>
            <li><code>8f30c4c0</code> : .</li>
            <li><code>8f629f04</code> : .</li>
            <li><code>8f65f688</code> : .</li>
            <li><code>8fc40a38</code> : .</li>
            <li><code>90d82779</code> : .</li>
            <li><code>91353b7d</code> : .</li>
            <li><code>91b305b1</code> : .</li>
            <li><code>928d8203</code> : .</li>
            <li><code>92c42c34</code> : .</li>
            <li><code>936812be</code> : .</li>
            <li><code>9476cec8</code> : .</li>
            <li><code>953c5254</code> : .</li>
            <li><code>954a99c4</code> : .</li>
            <li><code>95cc6196</code> : .</li>
            <li><code>965d71cf</code> : .</li>
            <li><code>9684d61b</code> : .</li>
            <li><code>9715d88e</code> : .</li>
            <li><code>976483ad</code> : .</li>
            <li><code>9786e672</code> : .</li>
            <li><code>98045f5f</code> : .</li>
            <li><code>983d3318</code> : .</li>
            <li><code>98b648b1</code> : .</li>
            <li><code>98d5ee6d</code> : .</li>
            <li><code>99ed783e</code> : .</li>
            <li><code>99f390f9</code> : .</li>
            <li><code>9a0b2492</code> : .</li>
            <li><code>9a2113b9</code> : .</li>
            <li><code>9aa7e372</code> : .</li>
            <li><code>9ace1a1c</code> : .</li>
            <li><code>9afc5da2</code> : .</li>
            <li><code>9d57a71d</code> : .</li>
            <li><code>9d8c59bb</code> : .</li>
            <li><code>9e55ac4a</code> : .</li>
            <li><code>a1613ff2</code> : .</li>
            <li><code>a346f4b3</code> : .</li>
            <li><code>a4031625</code> : .</li>
            <li><code>a4e4acfe</code> : .</li>
            <li><code>a4f9a7e0</code> : .</li>
            <li><code>a52b9363</code> : .</li>
            <li><code>a56598d5</code> : .</li>
            <li><code>a63baf64</code> : .</li>
            <li><code>a65d3e7e</code> : .</li>
            <li><code>a6c88b6b</code> : .</li>
            <li><code>a7404bcd</code> : .</li>
            <li><code>a8578fbf</code> : .</li>
            <li><code>a8a5401d</code> : .</li>
            <li><code>a95de198</code> : .</li>
            <li><code>a9621d90</code> : .</li>
            <li><code>aa19c70a</code> : .</li>
            <li><code>aa7f02d2</code> : .</li>
            <li><code>aa8a205b</code> : .</li>
            <li><code>aab236ff</code> : .</li>
            <li><code>ac1493b0</code> : .</li>
            <li><code>ac640a99</code> : .</li>
            <li><code>ac6a7b0b</code> : .</li>
            <li><code>acc2b1c1</code> : .</li>
            <li><code>adca3827</code> : .</li>
            <li><code>afa36f67</code> : .</li>
            <li><code>b10ae177</code> : .</li>
            <li><code>b10ec132</code> : .</li>
            <li><code>b17970cf</code> : .</li>
            <li><code>b1d7feb6</code> : .</li>
            <li><code>b20294b1</code> : .</li>
            <li><code>b2046b14</code> : .</li>
            <li><code>b2bc7fc3</code> : .</li>
            <li><code>b4f17c9d</code> : .</li>
            <li><code>b5568936</code> : .</li>
            <li><code>b5dacb0d</code> : .</li>
            <li><code>b5f2f3f2</code> : .</li>
            <li><code>b6dc60cc</code> : .</li>
            <li><code>b8484404</code> : .</li>
            <li><code>b8f416f7</code> : .</li>
            <li><code>b924d3be</code> : .</li>
            <li><code>bab20bf3</code> : .</li>
            <li><code>bb110e27</code> : .</li>
            <li><code>bb3085fc</code> : .</li>
            <li><code>bca6ad1b</code> : .</li>
            <li><code>bcc98872</code> : .</li>
            <li><code>bd674f44</code> : .</li>
            <li><code>bda64a7b</code> : .</li>
            <li><code>be65246d</code> : .</li>
            <li><code>be8d3098</code> : .</li>
            <li><code>be97740a</code> : .</li>
            <li><code>bf78846c</code> : .</li>
            <li><code>bf91c623</code> : .</li>
            <li><code>bfcdcd6d</code> : .</li>
            <li><code>c04e8d5b</code> : .</li>
            <li><code>c063ad79</code> : .</li>
            <li><code>c10459a6</code> : .</li>
            <li><code>c187a9f3</code> : .</li>
            <li><code>c23f3303</code> : .</li>
            <li><code>c2b1d5ff</code> : .</li>
            <li><code>c3cdf3de</code> : .</li>
            <li><code>c465016b</code> : .</li>
            <li><code>c52dcb51</code> : .</li>
            <li><code>c7aac0dd</code> : .</li>
            <li><code>c845beb4</code> : .</li>
            <li><code>c8b9734c</code> : .</li>
            <li><code>c92cb974</code> : .</li>
            <li><code>ca6f5f59</code> : .</li>
            <li><code>cabf8ebd</code> : .</li>
            <li><code>cb4450a2</code> : .</li>
            <li><code>cbf78f11</code> : .</li>
            <li><code>ccd082e7</code> : .</li>
            <li><code>cd5ec83d</code> : .</li>
            <li><code>cd989943</code> : .</li>
            <li><code>cd99e217</code> : .</li>
            <li><code>cd9c4b1b</code> : .</li>
            <li><code>cddb5083</code> : .</li>
            <li><code>cdfd0e02</code> : .</li>
            <li><code>ce685199</code> : .</li>
            <li><code>ceca7fb5</code> : .</li>
            <li><code>cf05e78e</code> : .</li>
            <li><code>cf459003</code> : .</li>
            <li><code>d026d625</code> : .</li>
            <li><code>d11dc438</code> : .</li>
            <li><code>d15e6dba</code> : .</li>
            <li><code>d23d6cb3</code> : .</li>
            <li><code>d2e4f3be</code> : .</li>
            <li><code>d2fb1fef</code> : .</li>
            <li><code>d2fd4284</code> : .</li>
            <li><code>d340eb03</code> : .</li>
            <li><code>d3478bd7</code> : .</li>
            <li><code>d3c9521d</code> : .</li>
            <li><code>d5318e9f</code> : .</li>
            <li><code>d5a5dcc0</code> : .</li>
            <li><code>d8bcdd46</code> : .</li>
            <li><code>d8d70c0f</code> : .</li>
            <li><code>daff00e0</code> : .</li>
            <li><code>db9a2f07</code> : .</li>
            <li><code>dc1eb20f</code> : .</li>
            <li><code>dcfc2e74</code> : .</li>
            <li><code>ddcc0850</code> : .</li>
            <li><code>de5c0565</code> : .</li>
            <li><code>dee3e8bd</code> : .</li>
            <li><code>df11e69f</code> : .</li>
            <li><code>df362406</code> : .</li>
            <li><code>df3680d3</code> : .</li>
            <li><code>df913e73</code> : .</li>
            <li><code>e0171206</code> : .</li>
            <li><code>e01ea813</code> : .</li>
            <li><code>e064cd5c</code> : .</li>
            <li><code>e19f2105</code> : .</li>
            <li><code>e2f2baa1</code> : .</li>
            <li><code>e3a3864c</code> : .</li>
            <li><code>e3c2b608</code> : .</li>
            <li><code>e3c9845c</code> : .</li>
            <li><code>e490371d</code> : .</li>
            <li><code>e4958dd2</code> : .</li>
            <li><code>e56f08da</code> : .</li>
            <li><code>e5c4817d</code> : .</li>
            <li><code>e6f75b5e</code> : .</li>
            <li><code>e7e5cfd0</code> : .</li>
            <li><code>e8b0aea5</code> : .</li>
            <li><code>ea998a6d</code> : .</li>
            <li><code>eb306b18</code> : .</li>
            <li><code>ecbbd1de</code> : .</li>
            <li><code>ece92f46</code> : .</li>
            <li><code>ed4a39cf</code> : .</li>
            <li><code>eda92267</code> : .</li>
            <li><code>ee98026b</code> : .</li>
            <li><code>eec8ce0b</code> : .</li>
            <li><code>ef4207a5</code> : .</li>
            <li><code>f096bcb8</code> : .</li>
            <li><code>f0a3c2f6</code> : .</li>
            <li><code>f0deaee8</code> : .</li>
            <li><code>f2e738b1</code> : .</li>
            <li><code>f2f81904</code> : .</li>
            <li><code>f3c64d77</code> : .</li>
            <li><code>f462ce6c</code> : .</li>
            <li><code>f4e5015c</code> : .</li>
            <li><code>f4f0eb13</code> : .</li>
            <li><code>f4fa20bd</code> : .</li>
            <li><code>f5231ed0</code> : .</li>
            <li><code>f5869664</code> : .</li>
            <li><code>f5b1872d</code> : .</li>
            <li><code>f7433d1d</code> : .</li>
            <li><code>f74be2e8</code> : .</li>
            <li><code>f8234249</code> : .</li>
            <li><code>f852ca44</code> : .</li>
            <li><code>f8873320</code> : .</li>
            <li><code>f94ff81b</code> : .</li>
            <li><code>fa06c443</code> : .</li>
            <li><code>fa22c32d</code> : .</li>
            <li><code>fa4a752c</code> : .</li>
            <li><code>fac72c30</code> : .</li>
            <li><code>fb75b804</code> : .</li>
            <li><code>fbaec5ee</code> : .</li>
            <li><code>fd932a98</code> : .</li>
            <li><code>fddb0694</code> : .</li>
            <li><code>fe5308a8</code> : .</li>
            <li><code>ff07a565</code> : .</li>
            <li><code>ff1d7167</code> : .</li>
            <li style="color: red; font-weight: bold">A compléter...</li>
        </ul>


        <?php
	}
}
