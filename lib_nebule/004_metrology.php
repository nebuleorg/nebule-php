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
    const LOG_LEVEL_DEVELOP = 2;
    const LOG_LEVEL_AUDIT = 4;
    const LOG_LEVEL_FUNCTION = 8;
    const LOG_LEVEL_DEBUG = 16;
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
            file_put_contents(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . Metrology::DEFAULT_DEBUG_FILE, 'START on library ' . $this->_timeStart . "\n", FILE_APPEND);
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
        $logLevel = $level;

        if ($logLevel <= $this->_logsLevel) {
            $logM = 'LogT=' . sprintf('%01.6f', (float)microtime(true) - $this->_timeStart) . ' LogL="' . $logLevel . '" LogI="' . $luid . '" LogF="' . $function . '" LogM="' . $message . '"';

            if ($logLevel == self::LOG_LEVEL_DEBUG)
                $logM = $logM . ' LogMem="' . memory_get_usage() . '"';

            syslog(LOG_INFO, $logM);
        }

        if ($this->_permitLogsOnDebugFile)
        {
            $logM = 'LogT=' . sprintf('%01.6f', (float)microtime(true) - $this->_timeStart) . ' LogL="' . $logLevel . '" LogI="' . $luid . '" LogF="' . $function . '" LogM="' . $message . '"';
            if (file_exists(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . Metrology::DEFAULT_DEBUG_FILE))
                file_put_contents(nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . Metrology::DEFAULT_DEBUG_FILE, $logM . "\n", FILE_APPEND);
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
