<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * The nebule core library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class nebule
{
    const NEBULE_LICENCE_NAME = 'nebule';
    const NEBULE_LICENCE_LINK = 'http://www.nebule.org/';
    const NEBULE_LICENCE_DATE = '2010-2025';
    const NEBULE_FUNCTION_VERSION = '020241123';

    const SESSION_SAVED_VARS = array(
        '_authoritiesInstance',
        '_recoveryInstance',
        '_cacheInstance',
        '_sessionInstance',
        '_tokenizeInstance',
        '_cryptoInstance',
    );

    private bool $_loadingStatus = false;
    private ?nebule $_nebuleInstance = null;
    private ?Metrology $_metrologyInstance = null;
    private ?Configuration $_configurationInstance = null;
    private ?Rescue $_rescueInstance = null;
    private ?Authorities $_authoritiesInstance = null;
    private ?Entities $_entitiesInstance = null;
    private ?Recovery $_recoveryInstance = null;
    private ?Cache $_cacheInstance = null;
    private ?Session $_sessionInstance = null;
    private ?Tokenize $_tokenizeInstance = null;
    private ?Router $_routerInstance = null;
    private ?ioInterface $_ioInstance = null;
    private ?CryptoInterface $_cryptoInstance = null;
    private ?SocialInterface $_socialInstance = null;



    public function __construct() {
        global $nebuleInstance ,$metrologyStartTime;
        $this->_nebuleInstance = $this;
        $nebuleInstance = $this;
        syslog(LOG_INFO, 'LogT=' . sprintf('%01.6f', microtime(true) - $metrologyStartTime) . ' LogL="info" LogI="1452ed72" LogF="include nebule library" LogM="Loading nebule library"');

        $this->_initialisation();
    }

    public function __toString(): string { return self::NEBULE_LICENCE_NAME; }
    public function __sleep(): array { return self::SESSION_SAVED_VARS; }

    public function __wakeup(): void {
        global $nebuleInstance ,$metrologyStartTime;
        $this->_nebuleInstance = $this;
        $nebuleInstance = $this;
        syslog(LOG_INFO, 'LogT=' . sprintf('%01.6f', microtime(true) - $metrologyStartTime) . ' LogL="info" LogI="2d9358d5" LogF="include nebule library" LogM="Reloading nebule library"');

        $this->_initialisation();
    }

    private function _initialisation(): void {
        $this->_initMetrology();
        $this->_metrologyInstance->addLog('first step init nebule instance', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '64154189');
        $this->_initConfiguration();
        $this->_initRescue();
        $this->_initSession();
        $this->_initCrypto();
        $this->_initIO();
        $this->_initCache();
        $this->_initSocial();
        $this->_initAuthorities();
        $this->_initRecovery();
        $this->_initEntities();
        $this->_initTokenize();
        $this->_initRouter();
        $this->_setEnvironmentInstances();
        $this->_initAllInstances();

        $this->_configurationInstance->setPermitOptionsByLinks(true);
        $this->_configurationInstance->flushCache();

        $this->_getSubordinationEntity();
        $this->_checkWriteableIO();
        $this->_findCurrentObjet();
        $this->_findCurrentEntity();
        $this->_findCurrentGroup();
        $this->_findCurrentConversation();
        $this->_findCurrentCurrency();
        $this->_findCurrentTokenPool();
        $this->_findCurrentToken();
        $this->_getArgUpdate();

        $this->_loadingStatus = true;
        $this->_metrologyInstance->addLog('end init nebule instance', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '474676ed');
    }

    private function _initMetrology(): void { $this->_metrologyInstance = new Metrology($this); }
    private function _initConfiguration(): void { $this->_configurationInstance = new Configuration($this); }
    private function _initRescue(): void { $this->_rescueInstance = new Rescue($this); }
    private function _initSession(): void { if ($this->_sessionInstance === null) $this->_sessionInstance = new Session($this); }
    private function _initCache(): void { if ($this->_cacheInstance === null) $this->_cacheInstance = new Cache($this); }
    private function _initIO(): void { $this->_ioInstance = new io($this); }
    private function _initCrypto(): void { if ($this->_cryptoInstance === null) $this->_cryptoInstance = new Crypto($this); }
    private function _initSocial(): void { $this->_socialInstance = new Social($this); }
    private function _initAuthorities(): void { if ($this->_authoritiesInstance === null) $this->_authoritiesInstance = new Authorities($this); }
    private function _initRecovery(): void { if ($this->_recoveryInstance === null) $this->_recoveryInstance = new Recovery($this); }
    private function _initEntities(): void { $this->_entitiesInstance = new Entities($this); }
    private function _initTokenize(): void { if ($this->_tokenizeInstance === null) $this->_tokenizeInstance = new Tokenize($this); }
    private function _initRouter(): void { if ($this->_routerInstance === null) $this->_routerInstance = new Router($this); }

    /**
     * Reload all instances in all library components.
     * TODO use vars by address instead for (null) instances.
     */
    private function _setEnvironmentInstances(): void {
        $this->_metrologyInstance->setEnvironmentLibrary($this);
        $this->_configurationInstance->setEnvironmentLibrary($this);
        $this->_rescueInstance->setEnvironmentLibrary($this);
        $this->_sessionInstance->setEnvironmentLibrary($this);
        $this->_cryptoInstance->setEnvironmentLibrary($this);
        $this->_ioInstance->setEnvironmentLibrary($this);
        $this->_cacheInstance->setEnvironmentLibrary($this);
        $this->_socialInstance->setEnvironmentLibrary($this);
        $this->_authoritiesInstance->setEnvironmentLibrary($this);
        $this->_entitiesInstance->setEnvironmentLibrary($this);
        $this->_recoveryInstance->setEnvironmentLibrary($this);
        $this->_tokenizeInstance->setEnvironmentLibrary($this);
        $this->_routerInstance->setEnvironmentLibrary($this);
    }

    private function _initAllInstances(): void {
        $this->_metrologyInstance->initialisation();
        $this->_configurationInstance->initialisation();
        $this->_rescueInstance->initialisation();
        $this->_sessionInstance->initialisation();
        $this->_cryptoInstance->initialisation();
        $this->_ioInstance->initialisation();
        $this->_cacheInstance->initialisation();
        $this->_socialInstance->initialisation();
        $this->_authoritiesInstance->initialisation();
        $this->_entitiesInstance->initialisation();
        $this->_recoveryInstance->initialisation();
        $this->_tokenizeInstance->initialisation();
        $this->_routerInstance->initialisation();
    }

    public function getLoadingStatus(): bool { return $this->_loadingStatus; }
    public function getMetrologyInstance(): ?Metrology { return $this->_metrologyInstance; }
    public function getConfigurationInstance(): ?Configuration { return $this->_configurationInstance; }
    public function getRescueInstance(): ?Rescue { return $this->_rescueInstance; }
    public function getAuthoritiesInstance(): ?Authorities { return $this->_authoritiesInstance; }
    public function getEntitiesInstance(): ?Entities { return $this->_entitiesInstance; }
    public function getRecoveryInstance(): ?Recovery { return $this->_recoveryInstance; }
    public function getCacheInstance(): ?Cache { return $this->_cacheInstance; }
    public function getSessionInstance(): ?Session { return $this->_sessionInstance; }
    public function getTokenizeInstance(): ?Tokenize { return $this->_tokenizeInstance; }
    public function getRouterInstance(): ?Router { return $this->_routerInstance; }
    public function getIoInstance(): ?ioInterface { return $this->_ioInstance; }
    public function getCryptoInstance(): ?CryptoInterface { return $this->_cryptoInstance; }
    public function getSocialInstance(): ?SocialInterface { return $this->_socialInstance; }



    private ?node $_subordinationEntity = null;

    /**
     * Retourne l'entité de subordination si définie.
     * Retourne une chaine vide sinon.
     *
     * @return node|null
     */
    public function getSubordinationEntity(): ?node { return $this->_subordinationEntity; }

    /**
     * Extrait l'entité de subordination des options si présente.
     *
     * Utilise la fonction getOptionFromEnvironment() pour extraire l'option du fichier d'environnement.
     *
     * @return void
     */
    private function _getSubordinationEntity(): void {
        //$this->_subordinationEntity = new Entity($this->_nebuleInstance, (string)Configuration::getOptionFromEnvironmentUntypedStatic('subordinationEntity'));
        $this->_subordinationEntity = new Entity($this->_nebuleInstance, $this->_configurationInstance->getOptionFromEnvironmentAsString('subordinationEntity'));
        $this->_metrologyInstance->addLog('get subordination entity = ' . $this->_subordinationEntity, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '2300b439');
    }

    /**
     * Vérifie que le système d'entrée/sortie par défaut est lecture/écriture.
     * Force les options permitWrite permitWriteObject et permitWriteLink au besoin.
     *
     * @return void
     * @todo ne fonctionne pas correctement mais non bloquant.
     *
     */
    private function _checkWriteableIO(): void {
        if ($this->_ioInstance->getMode() == 'RW') {
            if (!$this->_ioInstance->checkObjectsWrite())
                $this->_configurationInstance->lockPermitWriteObject();;
            if (!$this->_ioInstance->checkLinksWrite())
                $this->_configurationInstance->lockPermitWriteLink();;
        } else {
            $this->_configurationInstance->lockPermitWrite();
            $this->_configurationInstance->lockPermitWriteObject();
            $this->_configurationInstance->lockPermitWriteLink();
        }

        if (!$this->_configurationInstance->getOptionAsBoolean('permitWriteObject'))
            $this->_metrologyInstance->addLog('objects ro not rw', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '865076e1');
        if (!$this->_configurationInstance->getOptionAsBoolean('permitWriteLink'))
            $this->_metrologyInstance->addLog('links ro not rw', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'f2e738b1');
    }



    /**
     * Object - Calculate NID for data with hash algo.
     * @param string $data
     * @param string $algo
     * @return string
     */
    public function getFromDataNID(string $data, string $algo = ''): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($algo == '')
            $algo = $this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm');
        return $this->_cryptoInstance->hash($data, $algo) . '.' . $algo;
    }



    private function _getArgCurrentNode(string $reference): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (filter_has_var(INPUT_GET, $reference))
            $arg = trim(' ' . filter_input(INPUT_GET, $reference, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg = trim(' ' . filter_input(INPUT_POST, $reference, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if ($arg != '')
            $this->_metrologyInstance->addLog('user input ' . $reference . '=' . $arg, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '8eb05394');
        return $arg;
    }



    private bool $_needUpdate = false;

    /**
     * Get if codes need to be reloaded after an update of objects.
     * @return bool
     */
    public function getNeedUpdate(): bool { return $this->_needUpdate; }

    private function _getArgUpdate(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_needUpdate = $this->getHaveInput(References::COMMAND_UPDATE);
    }


    private string $_currentObject = '';
    private ?Node $_currentObjectInstance = null;
    public function getCurrentObjectOID(): string { return $this->_currentObject; }
    public function getCurrentObjectInstance(): ?Node { return $this->_currentObjectInstance; }
    public function setCurrentObjectInstance(Node $nid): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_currentObjectInstance = $nid;
        $this->_currentObject = $nid->getID();
        $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedObject', $this->_currentObject);
        $this->_metrologyInstance->addLog('set current object ' . $this->_currentObject, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '75cb6bf4');
    }
    private function _findCurrentObjet(): void {
        $arg = $this->_getArgCurrentNode(References::COMMAND_SELECT_OBJECT);

        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
            )
        ) {
            $this->_currentObject = $arg;
            $this->_currentObjectInstance =$this->_cacheInstance->newNode($arg);
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedObject', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStoreAsString('nebuleSelectedObject');
            if ($cache != '') {
                $this->_currentObject = $cache;
                $this->_currentObjectInstance =$this->_cacheInstance->newNode($cache);
            } else {
                $this->_currentObject = $this->_entitiesInstance->getGhostEntityEID();
                $this->_currentObjectInstance =$this->_cacheInstance->newNode($this->_entitiesInstance->getGhostEntityEID());
                $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedObject', $this->_entitiesInstance->getGhostEntityEID());
            }
        }

        $this->_metrologyInstance->addLog('find current object ' . $this->_currentObject, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '7b4f89ef');
        $this->_currentObjectInstance->getMarkProtected();
    }



    private string $_currentEntityID = '';
    private ?Entity $_currentEntityInstance = null;
    public function getCurrentEntityEID(): string { return $this->_currentEntityID; }
    public function getCurrentEntityInstance(): ?Entity { return $this->_currentEntityInstance; }
    public function setCurrentEntityInstance(Entity $entity): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if ($entity->getIsEntity()) {
            $this->_currentEntityInstance = $entity;
            $this->_currentEntityID = $entity->getID();
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedEntity', $this->_currentEntityID);
            $this->_metrologyInstance->addLog('set current entity ' . $this->_currentEntityID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'a63baf64');
        }
    }
    private function _findCurrentEntity(): void {
        $arg = $this->_getArgCurrentNode(References::COMMAND_SELECT_ENTITY);

        $instance = $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_ENTITY);

        if ($instance->getIsEntity()) {
            $this->_currentEntityID = $arg;
            $this->_currentEntityInstance = $instance;
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedEntity', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStoreAsString('nebuleSelectedEntity');
            if ($cache != '') {
                $this->_currentEntityID = $cache;
                $this->_currentEntityInstance = $this->_cacheInstance->newNode($cache, \Nebule\Library\Cache::TYPE_ENTITY);
            } else
            {
                $this->_currentEntityID = '0';
                $this->_currentEntityInstance = $this->_cacheInstance->newNode('0', \Nebule\Library\Cache::TYPE_ENTITY);
                $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedEntity', $this->_currentEntityID);
            }
        }

        $this->_metrologyInstance->addLog('find current entity ' . $this->_currentEntityID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'adca3827');
    }



    private string $_currentGroupID = '';
    private ?Group $_currentGroupInstance = null;
    public function getCurrentGroupOID(): string { return $this->_currentGroupID; }
    public function getCurrentGroupInstance(): ?Group { return $this->_currentGroupInstance; }
    private function _findCurrentGroup(): void {
        $arg = $this->_getArgCurrentNode(References::COMMAND_SELECT_GROUP);

        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentGroupID = $arg;
            $this->_currentGroupInstance = $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_GROUP);
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedGroup', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStoreAsString('nebuleSelectedGroup');
            if ($cache != '') {
                $this->_currentGroupID = $cache;
                $this->_currentGroupInstance = $this->_cacheInstance->newNode($cache, \Nebule\Library\Cache::TYPE_GROUP);
            } else
            {
                $this->_currentGroupID = '0';
                $this->_currentGroupInstance = $this->_cacheInstance->newNode('0', \Nebule\Library\Cache::TYPE_GROUP);
                $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedGroup', $this->_currentGroupID);
            }
        }

        $this->_metrologyInstance->addLog('find current group ' . $this->_currentGroupID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'adca3827');
    }



    private string $_currentConversationID = '';
    private ?Conversation $_currentConversationInstance = null;
    public function getCurrentConversationOID(): string { return $this->_currentConversationID; }
    public function getCurrentConversationInstance(): ?Conversation { return $this->_currentConversationInstance; }
    private function _findCurrentConversation(): void {
        $arg = $this->_getArgCurrentNode(References::COMMAND_SELECT_CONVERSATION);

        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentConversationID = $arg;
            $this->_currentConversationInstance = $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_CONVERSATION);
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedConversation', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStoreAsString('nebuleSelectedConversation');
            if ($cache != '') {
                $this->_currentConversationID = $cache;
                $this->_currentConversationInstance = $this->_cacheInstance->newNode($cache, \Nebule\Library\Cache::TYPE_CONVERSATION);
            } else {
                $this->_currentConversationID = '0';
                $this->_currentConversationInstance = $this->_cacheInstance->newNode('0', \Nebule\Library\Cache::TYPE_CONVERSATION);
                $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedConversation', $this->_currentConversationID);
            }
        }

        $this->_metrologyInstance->addLog('find current conversation ' . $this->_currentConversationID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'adf0b5df');
    }



    private string $_currentCurrencyID = '';
    private ?Currency $_currentCurrencyInstance = null;
    public function getCurrentCurrencyOID(): string { return $this->_currentCurrencyID; }
    public function getCurrentCurrencyInstance(): ?Currency { return $this->_currentCurrencyInstance; }
    private function _findCurrentCurrency(): void {
/*        $arg = $this->_getArgCurrentNode(References::COMMAND_SELECT_CURRENCY);
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentCurrencyID = '0';
            $this->_currentCurrencyInstance = $this->_cacheInstance->newNode('0', \Nebule\Library\Cache::TYPE_CURRENCY);
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedCurrency', $this->_currentCurrencyID);
            return;
        }

        if (filter_has_var(INPUT_GET, References::COMMAND_SELECT_CURRENCY))
            $arg = trim(' ' . filter_input(INPUT_GET, References::COMMAND_SELECT_CURRENCY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg = trim(' ' . filter_input(INPUT_POST, References::COMMAND_SELECT_CURRENCY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if ($arg != '')
            $this->_metrologyInstance->addLog('user input ' . References::COMMAND_SELECT_CURRENCY . '=' . $arg, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '69f274eb');

        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentCurrencyID = $arg;
            $this->_currentCurrencyInstance = $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_CURRENCY);
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedCurrency', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStoreAsString('nebuleSelectedCurrency');
            if ($cache != '') {
                $this->_currentCurrencyID = $cache;
                $this->_currentCurrencyInstance = $this->_cacheInstance->newNode($cache, \Nebule\Library\Cache::TYPE_CURRENCY);
            } else {
                $this->_currentCurrencyID = '0';
                $this->_currentCurrencyInstance = $this->_cacheInstance->newNode('0', \Nebule\Library\Cache::TYPE_CURRENCY);
                $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedCurrency', $this->_currentCurrencyID);
            }
        }

        $this->_metrologyInstance->addLog('find current currency ' . $this->_currentCurrencyID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '952d5651');*/
    }



    private string $_currentTokenPool = '';
    private ?TokenPool $_currentTokenPoolInstance = null;
    public function getCurrentTokenPoolNID(): string { return $this->_currentTokenPool; }
    public function getCurrentTokenPoolInstance(): ?TokenPool { return $this->_currentTokenPoolInstance; }
    private function _findCurrentTokenPool(): void {
/*        $arg = $this->_getArgCurrentNode(References::COMMAND_SELECT_TOKENPOOL);
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentTokenPool = '0';
            $this->_currentTokenPoolInstance = $this->_cacheInstance->newNode('0', \Nebule\Library\Cache::TYPE_TOKENPOOL);
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedTokenPool', $this->_currentTokenPool);
            return;
        }

        if (filter_has_var(INPUT_GET, References::COMMAND_SELECT_TOKENPOOL))
            $arg = trim(' ' . filter_input(INPUT_GET, References::COMMAND_SELECT_TOKENPOOL, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg = trim(' ' . filter_input(INPUT_POST, References::COMMAND_SELECT_TOKENPOOL, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if ($arg != '')
            $this->_metrologyInstance->addLog('user input ' . References::COMMAND_SELECT_TOKENPOOL . '=' . $arg, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'e1874657');

        if (Node::checkNID($arg, false, true)
            && ($this->getIoInstance()->checkObjectPresent($arg)
                || $this->getIoInstance()->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentTokenPool = $arg;
            $this->_currentTokenPoolInstance = $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_TOKENPOOL);
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedTokenPool', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStoreAsString('nebuleSelectedTokenPool');
            if ($cache != '') {
                $this->_currentTokenPool = $cache;
                $this->_currentTokenPoolInstance = $this->_cacheInstance->newNode($cache, \Nebule\Library\Cache::TYPE_TOKENPOOL);
            } else {
                $this->_currentTokenPool = '0';
                $this->_currentTokenPoolInstance = $this->_cacheInstance->newNode('0', \Nebule\Library\Cache::TYPE_TOKENPOOL);
                $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedTokenPool', $this->_currentTokenPool);
            }
        }

        $this->_metrologyInstance->addLog('find current token pool ' . $this->_currentTokenPool, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c8485d55');*/
    }



    private string $_currentTokenID = '';
    private ?Token $_currentTokenInstance = null;
    public function getCurrentTokenOID(): string { return $this->_currentTokenID; }
    public function getCurrentTokenInstance(): ?Token { return $this->_currentTokenInstance; }
    private function _findCurrentToken(): void {
/*        $arg = $this->_getArgCurrentNode(References::COMMAND_SELECT_TOKENCOIN);
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_currentTokenID = '0';
            $this->_currentTokenInstance = $this->_cacheInstance->newNode('0', \Nebule\Library\Cache::TYPE_TOKEN);
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedToken', $this->_currentTokenID);
            return;
        }

        if (filter_has_var(INPUT_GET, References::COMMAND_SELECT_TOKENCOIN))
            $arg = trim(' ' . filter_input(INPUT_GET, References::COMMAND_SELECT_TOKENCOIN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $arg = trim(' ' . filter_input(INPUT_POST, References::COMMAND_SELECT_TOKENCOIN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if ($arg != '')
            $this->_metrologyInstance->addLog('user input ' . References::COMMAND_SELECT_TOKENCOIN . '=' . $arg, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '9504b976');

        if (Node::checkNID($arg, false, true)
            && ($this->_ioInstance->checkObjectPresent($arg)
                || $this->_ioInstance->checkLinkPresent($arg)
                || $arg == '0'
            )
        ) {
            $this->_currentTokenID = $arg;
            $this->_currentTokenInstance = $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_TOKEN);
            $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedToken', $arg);
        } else {
            $cache = $this->_sessionInstance->getSessionStoreAsString('nebuleSelectedToken');
            if ($cache != '') {
                $this->_currentTokenID = $cache;
                $this->_currentTokenInstance = $this->_cacheInstance->newNode($cache, \Nebule\Library\Cache::TYPE_TOKEN);
            } else {
                $this->_currentTokenID = '0';
                $this->_currentTokenInstance = $this->_cacheInstance->newNode('0', \Nebule\Library\Cache::TYPE_TOKEN);
                $this->_sessionInstance->setSessionStoreAsString('nebuleSelectedToken', $this->_currentTokenID);
            }
        }

        $this->_metrologyInstance->addLog('find current token ' . $this->_currentTokenID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0ccb0886');*/
    }



    /**
     * Calculate the usability level of entities.
     *
     * @return integer
     */
    public function checkInstances(): int
    {
        if (!$this->_authoritiesInstance->getPuppetmasterInstance() instanceof \Nebule\Library\Entity) return 1;
        if ($this->_authoritiesInstance->getPuppetmasterEID() == '0') return 2;
        if ($this->_authoritiesInstance->getPuppetmasterEID() != $this->_configurationInstance->getOptionUntyped('puppetmaster')) return 3;

        if (sizeof($this->_authoritiesInstance->getSecurityAuthoritiesInstance()) == 0) return 11;
        foreach ($this->_authoritiesInstance->getSecurityAuthoritiesInstance() as $instance)  {
            if (!$instance instanceof \Nebule\Library\Entity) return 12;
            if ($instance->getID() == '0') return 13;
        }

        if (sizeof($this->_authoritiesInstance->getCodeAuthoritiesInstance()) == 0) return 21;
        foreach ($this->_authoritiesInstance->getCodeAuthoritiesInstance() as $instance)  {
            if (!$instance instanceof \Nebule\Library\Entity) return 22;
            if ($instance->getID() == '0') return 23;
        }

        if (sizeof($this->_authoritiesInstance->getDirectoryAuthoritiesInstance()) == 0) return 31;
        foreach ($this->_authoritiesInstance->getDirectoryAuthoritiesInstance() as $instance)  {
            if (!$instance instanceof \Nebule\Library\Entity) return 32;
            if ($instance->getID() == '0') return 33;
        }

        if (sizeof($this->_authoritiesInstance->getTimeAuthoritiesInstance()) == 0) return 41;
        foreach ($this->_authoritiesInstance->getTimeAuthoritiesInstance() as $instance)  {
            if (!$instance instanceof \Nebule\Library\Entity) return 42;
            if ($instance->getID() == '0') return 43;
        }

        if (!$this->_entitiesInstance->getServerEntityInstance() instanceof \Nebule\Library\Entity) return 51;
        if ($this->_entitiesInstance->getServerEntityEID() == '0') return 52;

        if (!$this->_entitiesInstance->getDefaultEntityInstance() instanceof \Nebule\Library\Entity) return 61;
        if ($this->_entitiesInstance->getDefaultEntityInstance()->getID() == '0') return 62;

        if (!$this->_entitiesInstance->getGhostEntityInstance() instanceof \Nebule\Library\Entity) return 71;
        if ($this->_entitiesInstance->getGhostEntityInstance()->getID() == '0') return 72;

        return 128;
    }



    /**
     * Recherche des liens par rapport à une référence qui est le type d'objet.
     * La recherche se fait dans les liens de l'objet type.
     * Cette recherche est utilisée pour retrouver les groupes et les conversations.
     * Toutes les références et propriétés sont hachées avec un algorithme fixe.
     * $entity Permet de ne sélectionner que les liens générés par une entité.
     *
     * @param string|Node   $type // FIXME
     * @param string        $socialClass
     * @param string|Entity $entity // FIXME
     * @return array:Link
     * TODO ajouter un filtre sur le type mime des objets.
     */
    public function getListLinksByType(Node|string $type, string|Entity $entity = '', string $socialClass = ''): array {
        $result = array();
        $hashType = '';
        $hashEntity = '';

        // Si le type est une instance, récupère l'ID de l'instance de l'objet du type.
        if (is_a($type, 'Node')) {
            $hashType = $type->getID();
        } else {
            // Si le type est un ID, l'utilise directement. Sinon calcul l'empreinte du type.
            if (Node::checkNID($type))
                $hashType = $type;
            else
                $hashType = $this->getFromDataNID($type, References::REFERENCE_CRYPTO_HASH_ALGORITHM);
            // $type doit être une instance d'objet au final.
            $type =$this->_cacheInstance->newNode($hashType);
        }
        // Si l'ID de l'instance du type est null ou vide, quitte en renvoyant un résultat vide.
        if ($hashType == '0'
            || $hashType == ''
        )
            return $result;

        // Si l'entité est une instance, récupère l'ID de l'instance de l'entité.
        if (is_a($entity, 'Node')) {
            $hashEntity = $entity->getID();
        } else {
            // Si l'entité est un ID, l'utilise directement. Sinon calcul l'empreinte de l'entité.
            if (Node::checkNID($entity)
                && $this->getIoInstance()->checkLinkPresent($entity)
                && $this->getIoInstance()->checkObjectPresent($entity)
            )
                $hashEntity = $entity;
            else
                $hashEntity = '';
        }

        // Lit les liens de l'objet de référence.
        $result = $type->getLinksOnFields(
            $hashEntity,
            '',
            'l',
            '',
            $hashType,
            $this->getFromDataNID(References::REFERENCE_NEBULE_OBJET_TYPE, References::REFERENCE_CRYPTO_HASH_ALGORITHM)
        );

        // Fait un tri par pertinence sociale.
        $this->_socialInstance->arraySocialFilter($result, $socialClass);

        // retourne le résultat.
        return $result;
    }

    /**
     * Recherche des ID d'objets par rapport à une référence qui est le type d'objet.
     * $entity Permet de ne sélectionner que les liens générés par une entité.
     *
     * @param string|Node   $type
     * @param string        $socialClass
     * @param string|Entity $entity
     * @return array:Link
     */
    public function getListIdByType($type = '', $entity = '', string $socialClass = ''): array {
        /**
         * Résultat de la recherche de liens à retourner.
         * @var array:Link $result
         */
        $result = $this->getListLinksByType($type, $entity, $socialClass);

        // Extrait les ID.
        foreach ($result as $i => $l)
            $result[$i] = $l->getParsed()['bl/rl/nid1'];

        // retourne le résultat.
        return $result;
    }

    /**
     * Extrait la liste des liens définissant les groupes d'objets.
     * $entity Permet de ne sélectionner que les groupes générés par une entité.
     *
     * @param string $socialClass
     * @param Entity $entity
     * @return array:Link
     */
    public function getListGroupsLinks(Entity $entity, string $socialClass = ''): array {
        return $this->getListLinksByType(References::REFERENCE_NEBULE_OBJET_GROUPE, $entity, $socialClass);
    }

    /**
     * Extrait la liste des ID des groupes d'objets.
     * $entity Permet de ne sélectionner que les groupes générés par une entité.
     *
     * @param Entity $entity
     * @param string $socialClass
     * @return array
     */
    public function getListGroupsID(Entity $entity, string $socialClass = ''): array {
        return $this->getListIdByType(References::REFERENCE_NEBULE_OBJET_GROUPE, $entity, $socialClass);
    }

    /**
     * Extrait la liste des liens définissant les conversations.
     * Précalcul le hash de l'objet définissant une conversation.
     * Extrait l'ID de l'entité, si demandé.
     * Liste les liens définissants les différentes conversations.
     * Retourne la liste.
     * $entity : Permet de ne sélectionner que les conversations générés par une entité.
     *
     * @param Entity $entity
     * @param string $socialClass
     * @return array
     */
    public function getListConversationsLinks(Entity $entity, string $socialClass = ''): array {
        return $this->getListLinksByType(References::REFERENCE_NEBULE_OBJET_CONVERSATION, $entity, $socialClass);
    }

    /**
     * Extrait la liste des ID des conversations.
     * Géré comme des groupes d'objets.
     * $entity Permet de ne sélectionner que les conversations générées par une entité.
     *
     * @param Entity $entity
     * @param string $socialClass
     * @return array
     */
    public function getListConversationsID(Entity $entity, string $socialClass = ''): array {
        return $this->getListIdByType(References::REFERENCE_NEBULE_OBJET_CONVERSATION, $entity, $socialClass);
    }



    public function getHaveInput(string $name): bool {
        if ($name == '')
            return false;
        if (filter_has_var(INPUT_GET, $name) || filter_has_var(INPUT_POST, $name))
            return true;
        return false;
    }



    /**
     * Extrait l'argument pour continuer un affichage en ligne à partir d'un objet particulier.
     * Retourne tout type de chaine de texte nécessaire à l'affichage
     * ou une chaine vide si pas d'argument valable trouvé.
     *
     * @return string
     */
    public function getDisplayNextObject(): string {
        $this->_metrologyInstance->addLog('extract display next object', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bccbff7a');

        $arg = trim(' ' . filter_input(INPUT_GET, Displays::DEFAULT_NEXT_COMMAND, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            return $arg;
        return '';
    }

    public function getIsRID(Node $nid): bool {
        return str_contains($nid->getID(), '.none');
    }

    public function runApplication(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_routerInstance->router();
    }
}
