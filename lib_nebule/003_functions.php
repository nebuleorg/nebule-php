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
    const SESSION_SAVED_VARS = array(); // Replace on children classes.

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

    public function __construct(nebule $nebuleInstance){}

    public function __sleep(){
        return $this::SESSION_SAVED_VARS;
    }

    public function __wakeup(){}

    public function setEnvironment(nebule $nebuleInstance): void{
        $nebuleInstance->getMetrologyInstance()->addLog('set class environment', Metrology::LOG_LEVEL_NORMAL, $this::class . '::' . __FUNCTION__, '6b424a34');
        $this->_nebuleInstance = $nebuleInstance;
        //if ($this->_nebuleInstance === null)
        //    return;
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

    public function initialisation(): void{
        // Replace on children classes.
        $this->_metrologyInstance->addLog('class initialisation', Metrology::LOG_LEVEL_NORMAL, $this::class . '::' . __FUNCTION__, '165707c8');
        $this->_initialisation();
    }

    protected function _initialisation(): void{}



    static public function signReferences($nebuleInstance): bool
    {
        $ok = true;

        // Load entity before sign.
        // TODO

        // Generate links for icons.
        foreach ( References::OBJ_IMG as $name => $content) {
            $instance = new Node($nebuleInstance, '0');
            $decoded = (string)base64_decode($content, false);
            if (!$instance->setContent($decoded))
                $ok = false; // FIXME

            $reference = $nebuleInstance->getNIDfromData(References::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE);
            if (References::REF_IMG[$name] != '') {
                $nebuleInstance->getMetrologyInstance()->addLog('sign ref icon ' . 'f>' . References::REF_IMG[$name] . '>' . $instance->getID() . '>' . $reference, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '08d23b22');

                // Use credentials on the first run with local entity. FIXME peut être refait avec Entities::setTempCurrentEntity()
                $newLink = \Nebule\Bootstrap\blk_generateSign('',
                    'f',
                    References::REF_IMG[$name],
                    $instance->getID(),
                    $reference
                );
                if (!\Nebule\Bootstrap\blk_write($newLink))
                    $ok = false;
            }
        }
        return $ok;
    }

    public function getTypedInstanceFromNID(string $nid): Node
    {
        $social = 'all';

        if ($nid == '0'
            || $nid == ''
        )
            $instance = $this->_cacheInstance->newNode('0');
        else {
            $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_NODE);
            if ($instance->getIsEntity($social))
                $instance = $this->_cacheInstance->newEntity($nid);
            elseif ($instance->getIsWallet($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_WALLET);
            elseif ($instance->getIsToken($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_TOKEN);
            elseif ($instance->getIsTokenPool($social))
                $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_TOKENPOOL);
            elseif ($instance->getIsCurrency($social))
                $instance = $this->_cacheInstance->newCurrency($nid);
            elseif ($instance->getIsConversation($social))
                $instance = $this->_cacheInstance->newConversation($nid);
            elseif ($instance->getIsGroup($social))
                $instance = $this->_cacheInstance->newGroup($nid);
            else {
                $protected = $instance->getMarkProtected();
                if ($protected)
                    $instance = $this->_cacheInstance->newNode($instance->getID(), Cache::TYPE_NODE);
                if ($instance->getType('all') == References::REFERENCE_OBJECT_ENTITY)
                    $instance = $this->_cacheInstance->newNode($nid, Cache::TYPE_ENTITY);
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
        $newBlockLink = new blocLink($this->_nebuleInstance, 'new');
        $newLink = new Link($this->_nebuleInstance, $link, $newBlockLink);
        if ($obfuscate && !$newLink->setObfuscate())
            return '';
        $newBlockLink->signwrite($this->_entitiesInstance->getCurrentEntityID());

        return $textOID;
    }

    public function getNidFromData(string $data, string $algo = ''): string
    {
        if ($algo == '')
            $algo = $this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm');
        return $this->_cryptoInstance->hash($data, $algo) . '.' . $algo;
    }
}
