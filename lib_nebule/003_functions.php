<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Functions for nebule library and more.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Functions
{
    const SESSION_SAVED_VARS = array(); // Replaced on children classes.
    const DEFAULT_CLASS = ''; // Replaced on children classes.
    const TYPE = ''; // Replaced on children classes.

    protected ?nebule $_nebuleInstance = null;
    protected ?Metrology $_metrologyInstance = null;
    protected ?Configuration $_configurationInstance = null;
    protected ?Rescue $_rescueInstance = null;
    protected ?Authorities $_authoritiesInstance = null;
    protected ?Entities $_entitiesInstance = null;
    protected ?Recovery $_recoveryInstance = null;
    protected ?Cache $_cacheInstance = null;
    protected ?Session $_sessionInstance = null;
    protected ?Ticketing $_ticketingInstance = null;
    protected ?ioInterface $_ioInstance = null;
    protected ?CryptoInterface $_cryptoInstance = null;
    protected ?SocialInterface $_socialInstance = null;
    protected ?Applications $_applicationInstance = null;
    protected ?Displays $_displayInstance = null;
    protected ?Actions $_actionInstance = null;
    protected ?Translates $_translateInstance = null;
    protected bool $_environmentLibrarySet = false;
    protected bool $_environmentApplicationSet = false;
    protected bool $_initialisationSet = false;
    protected array $_listClasses = array();
    protected array $_listTypes = array();
    protected array $_listInstances = array();
    //private ?Functions $_defaultInstance = null;
    protected bool $_ready = false;

    public function __construct(nebule $nebuleInstance){}

    public function __sleep(){
        return $this::SESSION_SAVED_VARS;
    }

    public function __wakeup(){}

    public function setEnvironmentLibrary(nebule $nebuleInstance): void {
        $nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, $this::class . '::' . __FUNCTION__, '1111c0de');
        if ($this->_environmentLibrarySet)
            return;
        $this->_environmentLibrarySet = true;

        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_configurationInstance = $this->_nebuleInstance->getConfigurationInstance();
        $this->_rescueInstance = $this->_nebuleInstance->getRescueInstance();
        $this->_authoritiesInstance = $this->_nebuleInstance->getAuthoritiesInstance();
        $this->_entitiesInstance = $this->_nebuleInstance->getEntitiesInstance();
        $this->_recoveryInstance = $this->_nebuleInstance->getRecoveryInstance();
        $this->_cacheInstance = $this->_nebuleInstance->getCacheInstance();
        $this->_sessionInstance = $this->_nebuleInstance->getSessionInstance();
        $this->_ticketingInstance = $this->_nebuleInstance->getTicketingInstance();
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_cryptoInstance = $this->_nebuleInstance->getCryptoInstance();
        $this->_socialInstance = $this->_nebuleInstance->getSocialInstance();
    }

    public function setEnvironmentApplication(Applications $applicationInstance): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, $this::class . '::' . __FUNCTION__, '1111c0de');
        if ($this->_environmentApplicationSet)
            return;
        $this->_environmentApplicationSet = true;

        $this->_applicationInstance = $applicationInstance;
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_actionInstance = $this->_applicationInstance->getActionInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
    }

    public function initialisation(): void {
        $this->_metrologyInstance->addLog('track functions ' . get_class($this), Metrology::LOG_LEVEL_FUNCTION, $this::class . '::' . __FUNCTION__, '1111c0de');
        if ($this->_initialisationSet)
            return;
        $this->_initialisationSet = true;

        $this->_initialisation();
    }

    protected function _initialisation(): void{}

    protected function _initSubInstance(string $class): functions
    {
        $this->_metrologyInstance->addLog('track functions ' . get_class($this), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new $class($this->_nebuleInstance);
        $instance->setEnvironmentLibrary($this->_nebuleInstance);
        $instance->initialisation();
        $type = strtolower($instance::TYPE);

        $this->_metrologyInstance->addLog('add sub-instance ' . $class . ' as ' . $type, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '68634e7f');
        $this->_listClasses[$type] = $class;
        $this->_listTypes[$class] = $type;
        $this->_listInstances[$type] = $instance;

        return $instance;
    }

    protected function _getDefaultSubInstance(string $name): Functions
    {
        $this->_metrologyInstance->addLog('track functions ' . get_class($this), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $option = strtolower($this->_configurationInstance->getOptionAsString($name));
        if (isset($this->_listClasses[$option])) {
            $this->_metrologyInstance->addLog('get default instance with option', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '04260a5e');
            $defaultInstance = $this->_listInstances[$option];
            $this->_ready = true;
        } elseif (isset($this->_listClasses[$this::DEFAULT_CLASS])) {
            $this->_metrologyInstance->addLog('get default instance with default value', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'f2f81904');
            $defaultInstance = $this->_listInstances[$this::DEFAULT_CLASS];
            $this->_ready = true;
        } else {
            $defaultInstance = $this;
            $this->_metrologyInstance->addLog('no default ' . get_class($this) . ' class found', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '72cc9a1b');
        }
        $this->_metrologyInstance->addLog('set default class ' . get_class($defaultInstance), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4233cb6c');
        return $defaultInstance;
    }

    public function getReady(): bool
    {
        return $this->_ready;
    }



    public function getTypedInstanceFromNID(string $nid): Node
    {
        $social = 'all';

        if ($nid == '0'
            || $nid == ''
        )
            $instance = $this->_cacheInstance->newNode('0');
        else {
            $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_NODE);
            if ($instance->getIsEntity($social))
                $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_ENTITY);
            /*elseif ($instance->getIsWallet($social))
                $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_WALLET);
            elseif ($instance->getIsToken($social))
                $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_TOKEN);
            elseif ($instance->getIsTokenPool($social))
                $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_TOKENPOOL);
            elseif ($instance->getIsCurrency($social))
                $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_CURRENCY);*/
            elseif ($instance->getIsConversation($social))
                $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_CONVERSATION);
            elseif ($instance->getIsGroup($social))
                $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_GROUP);
            else {
                $protected = $instance->getMarkProtected();
                if ($protected)
                    $instance = $this->_cacheInstance->newNode($instance->getID(), \Nebule\Library\Cache::TYPE_NODE);
                if ($instance->getType('all') == References::REFERENCE_OBJECT_ENTITY)
                    $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_ENTITY);
            }
        }

        return $instance;
    }

    public function dateCompare($date1, $date2): bool|int
    {
        if ($date1 == '') return false;
        if ($date2 == '') return false;
        // Extrait les éléments d'une date.
        $d1 = date_parse($date1);
        if ($d1 === false) return 0;
        $d2 = date_parse($date2);
        if ($d2 === false) return 0;
        // Année
        if ($d1['year'] > $d2['year']) return 1;
        if ($d1['year'] < $d2['year']) return -1;
        // Mois
        if ($d1['month'] === false) return 0;
        if ($d2['month'] === false) return 0;
        if ($d1['month'] > $d2['month']) return 1;
        if ($d1['month'] < $d2['month']) return -1;
        // Jour
        if ($d1['day'] === false) return 0;
        if ($d2['day'] === false) return 0;
        if ($d1['day'] > $d2['day']) return 1;
        if ($d1['day'] < $d2['day']) return -1;
        // Heure
        if ($d1['hour'] === false) return 0;
        if ($d2['hour'] === false) return 0;
        if ($d1['hour'] > $d2['hour']) return 1;
        if ($d1['hour'] < $d2['hour']) return -1;
        // Minute
        if ($d1['minute'] === false) return 0;
        if ($d2['minute'] === false) return 0;
        if ($d1['minute'] > $d2['minute']) return 1;
        if ($d1['minute'] < $d2['minute']) return -1;
        // Seconde
        if ($d1['second'] === false) return 0;
        if ($d2['second'] === false) return 0;
        if ($d1['second'] > $d2['second']) return 1;
        if ($d1['second'] < $d2['second']) return -1;
        // Fraction de seconde
        if ($d1['fraction'] === false) return 0;
        if ($d2['fraction'] === false) return 0;
        if ($d1['fraction'] > $d2['fraction']) return 1;
        if ($d1['fraction'] < $d2['fraction']) return -1;
        return 0;
        // A faire... comparateur universel sur multiples formats de dates...
    }

    /**
     * Create and write object from text.
     * TODO protection
     *
     * @param string  $text
     * @param boolean $protect
     * @param bool    $obfuscate
     * @return string
     */
    public function createTextAsObject(string &$text, bool $protect = false, bool $obfuscate = false): string
    {
        if (!$this->_configurationInstance->checkBooleanOptions(array('unlocked','permitWrite','permitWriteObject','permitWriteLink'))
            || strlen($text) == 0
            || $protect // TODO
        )
            return '';

        $textOID = $this->getNidFromData($text);
        $this->_ioInstance->setObject($textOID, $text);
        $propertyOID = $this->_nebuleInstance->getNIDfromData('text/plain');
        $propertyRID = $this->_nebuleInstance->getNIDfromData(References::REFERENCE_NEBULE_OBJET_TYPE);
        $link = 'l>' . $textOID . '>' . $propertyOID . '>' . $propertyRID;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newLink = new LinkRegister($this->_nebuleInstance, $link, $newBlockLink);
        if ($obfuscate && !$newLink->setObfuscate())
            return '';
        $newBlockLink->signwrite($this->_entitiesInstance->getGhostEntityID());

        return $textOID;
    }

    public function getNidFromData(string $data, string $algo = ''): string
    {
        if ($algo == '')
            $algo = $this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm');
        return $this->_cryptoInstance->hash($data, $algo) . '.' . $algo;
    }

    public function getFilterInput(string $name, int $flag=0): string {
        $arg = '';
        $type = 'POST';
        try {
            if (filter_has_var(INPUT_POST, $name)) {
                $arg = filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING, $flag);
                if ($arg === false || $arg === null)
                    $arg = '';
                $arg = trim($arg);
            } else
                $this->_metrologyInstance->addLog("get input '$name' type $type not present", Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd15e6dba');
        } catch (\Exception $e) {
            $this->_metrologyInstance->addLog("error reading '$name' on POST "
                . ' ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '9afa9eda');
        }
        if ($arg == '') {
            $type = 'GET';
            try {
                if (filter_has_var(INPUT_GET, $name)) {
                    $arg = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING, $flag);
                    if ($arg === false || $arg === null)
                        $arg = '';
                    $arg = trim($arg);
                } else
                    $this->_metrologyInstance->addLog("get input '$name' type $type not present", Metrology::LOG_LEVEL_DEBUG, __METHOD__, '3f409bc1');
            } catch (\Exception $e) {
                $this->_metrologyInstance->addLog("error reading '$name' on GET "
                    . ' ('  . $e->getCode() . ') : ' . $e->getFile()
                    . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                    . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '40aeb7cd');
            }
        }
        if ($arg != '')
            $this->_metrologyInstance->addLog("get filtered input '$name' type $type result=$arg", Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cf05e78e');
        return $arg;
    }
}
