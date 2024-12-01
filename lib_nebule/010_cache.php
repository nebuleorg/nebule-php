<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Configuration class for the nebule library.
 * Must be serialized on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Cache extends Functions
{
    const SESSION_SAVED_VARS = array(
        '_cache',
        '_cacheDateInsertion',
        '_flushCache',
    );

    const TYPE_NODE = 'node';
    const TYPE_GROUP = 'group';
    const TYPE_ENTITY = 'entity';
    const TYPE_LOCATION = 'location';
    const TYPE_CONVERSATION = 'conversation';
    const TYPE_CURRENCY = 'currency';
    const TYPE_TOKENPOOL = 'tokenpool';
    const TYPE_TOKEN = 'token';
    const TYPE_WALLET = 'wallet';
    const TYPE_BLOCLINK = 'bloclink';
    const TYPE_LINK = 'link';
    const TYPE_TRANSACTION = 'transaction';

    const KNOWN_TYPE = array(
        self::TYPE_NODE,
        self::TYPE_GROUP,
        self::TYPE_ENTITY,
        self::TYPE_LOCATION,
        self::TYPE_CONVERSATION,
        self::TYPE_CURRENCY,
        self::TYPE_TOKENPOOL,
        self::TYPE_TOKEN,
        self::TYPE_WALLET,
        self::TYPE_BLOCLINK,
        self::TYPE_LINK,
        self::TYPE_TRANSACTION,
    );

    private array $_cache = array();
    private array $_cacheDateInsertion = array();
    private int $_sessionBufferLimit = 0;
    private bool $_flushCache = false;

    protected function _initialisation(): void
    {
        $this->_sessionBufferLimit = $this->_configurationInstance->getOptionAsInteger('sessionBufferSize');
        $this->_getFlushCache();
        foreach ($this->_cache as $type => $table) {
            foreach ($table as $item => $instance) {
                $instance->setEnvironmentLibrary($this->_nebuleInstance);
                $instance->initialisation();
            }
        }
    }

    public function __wakeup()
    {
        /*$this->_sessionBufferLimit = $this->_configurationInstance->getOptionAsInteger('sessionBufferSize');
        foreach ($this->_cache as $type => $table) {
            foreach ($table as $item => $instance) {
                $instance->setEnvironmentLibrary($this->_nebuleInstance);
                $instance->initialisation();
            }
        }*/
    }

    private function _getFlushCache(): void
    {
        if (filter_has_var(INPUT_GET, References::COMMAND_FLUSH)
            || filter_has_var(INPUT_POST, References::COMMAND_FLUSH)
        ) {
            $this->_metrologyInstance->addLog('ask flush cache', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '3aeed4ed');
            $this->_cache = array();
            $this->_cacheDateInsertion = array();
            $this->_flushCache = true;
        }
    }

    public function getFlushCache(): bool
    {
        return $this->_flushCache;
    }

    public function readCacheOnSessionBuffer_DEPRECATED(): void
    {
        if ($this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
        )
            return;

        session_start();

        // Extrait les objets/liens du cache.
        $list = array();
        if (isset($_SESSION['Buffer']))
            $list = $_SESSION['Buffer'];
        if (sizeof($list) > 0) {
            foreach ($list as $type => $table)
            {
                foreach ($table as $item => $value)
                {
                    $instance = unserialize($value);
                    $this->_cache[$type][$item] = $instance;
                }
            }
        }
        if (isset($_SESSION['BufferDateInsertion']))
            $this->_cacheDateInsertion = $_SESSION['BufferDateInsertion'];

        session_write_close();

        // Vérifie si le cache n'est pas trop gros. Le vide au besoin.
        $this->_getCacheNeedCleaning();
    }

    public function saveCacheOnSessionBuffer_DEPRECATED(): void
    {
        if ($this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
        )
            return;

        $this->_getCacheNeedCleaning();
        session_start();
        $_SESSION['Buffer'] = array();

        foreach ($this->_cache as $type => $table) {
            foreach ($table as $item => $instance) {
                if (isset($this->_cacheDateInsertion[$type][$item])) {
                    $_SESSION['Buffer'][$type][$item] = serialize($instance);
                    $_SESSION['BufferDateInsertion'][$type][$item] = $this->_cacheDateInsertion[$type][$item];
                }
            }
        }

        session_write_close();
    }

    public function flushBufferStore(): bool
    {
        $this->_metrologyInstance->addLog('flush buffer store', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
        session_start();
        unset($_SESSION['Buffer']);
        $_SESSION['Buffer'] = array();
        session_write_close();
        return true;
    }

    private function _cleanCacheOverflow(int $c = 0): void
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
            || !is_numeric($c)
            || $c <= 0
        )
            return;

        if ($c > 100)
            $this->_metrologyInstance->addLog(__METHOD__ . ' cache need flush ' . $c . '/' . sizeof($this->_cacheDateInsertion), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '5bdb6961'); // Log

        for ($i = 0; $i < $c; $i++)
        {
            $refTime = 0;
            $refType = '';
            $refItem = '';
            foreach ($this->_cacheDateInsertion as $type => $table)
            {
                foreach ($table as $item => $value)
                {
                    if ($value >= $refTime)
                    {
                        $refTime = $value;
                        $refItem = $item;
                        $refType = $type;
                    }
                }
            }
            if ($refItem != '')
            {
                unset($this->_cache[$refType][$refItem]);
                unset($this->_cacheDateInsertion[$refType][$refItem]);
            }
        }
    }

    private function _getAllCachesSize(): int
    {
        return sizeof($this->_cache);
    }

    private function _getCacheNeedOnePlace(): void
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer'))
            return;

        $size = $this->_getAllCachesSize();
        $limit = $this->_sessionBufferLimit;
        if ($size >= $limit)
            $this->_cleanCacheOverflow($size - $limit + 1);
    }

    private function _getCacheNeedCleaning(): void
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer'))
            return;

        $size = $this->_getAllCachesSize();
        $limit = $this->_sessionBufferLimit;
        if ($size >= $limit)
            $this->_cleanCacheOverflow($size - $limit);
    }

    private function _checkKnownNodeType(string &$type): bool
    {
        if (in_array($type, self::KNOWN_TYPE))
            return true;
        return false;
    }

    private function _filterNodeType(string &$type): void
    {
        if (!$this->_checkKnownNodeType($type))
            $type = self::TYPE_NODE;
    }

    public function getIsOnCache(string $nid, string $type = ''): bool
    {
        if ($this->_flushCache || $nid == '' || $nid == '0') return false;

        if ($type == '') {
            foreach (self::KNOWN_TYPE as $known) {
                if (isset($this->_cache[$known][$nid])) return true;
            }
        } else {
            $this->_filterNodeType($type);
            if (isset($this->_cache[$type][$nid])) return true;
        }

        return false;
    }

    public function newNode(string $nid, string $type = Cache::TYPE_NODE): node
    {
        $this->_filterNodeType($type);

        if ($this->getIsOnCache($nid, $type)) {
            $instance = $this->_cache[$type][$nid];
            $this->_writeCacheTimestamp($nid, $type);
        } else {
            switch ($type)
            {
                case self::TYPE_GROUP:
                    $instance = new Group($this->_nebuleInstance, $nid);
                    break;
                case self::TYPE_ENTITY:
                    $instance = new Entity($this->_nebuleInstance, $nid);
                    break;
                case self::TYPE_LOCATION:
                    $instance = new Localisation($this->_nebuleInstance, $nid);
                    break;
                case self::TYPE_CONVERSATION:
                    $instance = new Conversation($this->_nebuleInstance, $nid);
                    break;
                case self::TYPE_CURRENCY:
                    $instance = new Currency($this->_nebuleInstance, $nid);
                    break;
                case self::TYPE_TOKENPOOL:
                    $instance = new TokenPool($this->_nebuleInstance, $nid);
                    break;
                case self::TYPE_TOKEN:
                    $instance = new Token($this->_nebuleInstance, $nid);
                    break;
                case self::TYPE_WALLET:
                    $instance = new Wallet($this->_nebuleInstance, $nid);
                    break;
                default:
                    $type = self::TYPE_NODE;
                    $instance = new Node($this->_nebuleInstance, $nid);
            }
            $this->_writeCacheNodes($instance, $type);
        }
        return $instance;
    }

    public function newEntity(string $nid): Entity
    {
        if ($this->getIsOnCache($nid, Cache::TYPE_ENTITY)) {
            $instance = $this->_cache[Cache::TYPE_ENTITY][$nid];
            $this->_writeCacheTimestamp($nid, Cache::TYPE_ENTITY);
        } else {
            $instance = new Entity($this->_nebuleInstance, $nid);
            $this->_writeCacheNodes($instance, Cache::TYPE_ENTITY);
        }
        return $instance;
    }

    public function newGroup(string $nid): Group
    {
        if ($this->getIsOnCache($nid, Cache::TYPE_GROUP)) {
            $instance = $this->_cache[Cache::TYPE_GROUP][$nid];
            $this->_writeCacheTimestamp($nid, Cache::TYPE_GROUP);
        } else {
            $instance = new Group($this->_nebuleInstance, $nid);
            $this->_writeCacheNodes($instance, Cache::TYPE_GROUP);
        }
        return $instance;
    }

    public function newConversation(string $nid): Conversation
    {
        if ($this->getIsOnCache($nid, Cache::TYPE_CONVERSATION)) {
            $instance = $this->_cache[Cache::TYPE_CONVERSATION][$nid];
            $this->_writeCacheTimestamp($nid, Cache::TYPE_CONVERSATION);
        } else {
            $instance = new Conversation($this->_nebuleInstance, $nid);
            $this->_writeCacheNodes($instance, Cache::TYPE_CONVERSATION);
        }
        return $instance;
    }

    public function newCurrency(string $nid): Currency
    {
        if ($this->getIsOnCache($nid, Cache::TYPE_CURRENCY)) {
            $instance = $this->_cache[Cache::TYPE_CURRENCY][$nid];
            $this->_writeCacheTimestamp($nid, Cache::TYPE_CURRENCY);
        } else {
            $instance = new Currency($this->_nebuleInstance, $nid);
            $this->_writeCacheNodes($instance, Cache::TYPE_CURRENCY);
        }
        return $instance;
    }

    public function newTokenPool(string $nid): TokenPool
    {
        if ($this->getIsOnCache($nid, Cache::TYPE_TOKENPOOL)) {
            $instance = $this->_cache[Cache::TYPE_TOKENPOOL][$nid];
            $this->_writeCacheTimestamp($nid, Cache::TYPE_TOKENPOOL);
        } else {
            $instance = new TokenPool($this->_nebuleInstance, $nid);
            $this->_writeCacheNodes($instance, Cache::TYPE_TOKENPOOL);
        }
        return $instance;
    }

    public function newToken(string $nid): Token
    {
        if ($this->getIsOnCache($nid, Cache::TYPE_TOKEN)) {
            $instance = $this->_cache[Cache::TYPE_TOKEN][$nid];
            $this->_writeCacheTimestamp($nid, Cache::TYPE_TOKEN);
        } else {
            $instance = new Token($this->_nebuleInstance, $nid);
            $this->_writeCacheNodes($instance, Cache::TYPE_TOKEN);
        }
        return $instance;
    }

    public function newBlockLink(string $link, string $type = cache::TYPE_BLOCLINK): blocLinkInterface
    {
        // FIXME
        if (!$this->_flushCache
            && isset($this->_cache[$type][$link])
        ) {
            $this->_cacheDateInsertion[$type][$link] = microtime(true);
            $instance = $this->_cache[$type][$link];
        } else {
            $instance = new BlocLink($this->_nebuleInstance, $link, $type);
            $this->_writeCacheBlockLinks($instance, Cache::TYPE_BLOCLINK);
        }
        return $instance;
    }

    private function _writeCacheTimestamp(string $nid, string $type): void
    {
        $this->_cacheDateInsertion[$type][$nid] = microtime(true);
    }

    private function _writeCacheNodes(Node $instance, string $type): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
            && $instance->getID() != '0'
        ) {
            $this->_getCacheNeedOnePlace();
            $this->_cache[$type][$instance->getID()] = $instance;
            $this->_writeCacheTimestamp($instance->getID(), $type);
        }
    }

    private function _writeCacheBlockLinks(BlocLink $instance, string $type): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
            && !$instance->getNew()
        ) {
            $this->_getCacheNeedOnePlace();
            $this->_cache[$type][$instance->getLID()] = $instance;
            $this->_writeCacheTimestamp($instance->getLID(), $type);
        }
    }

    public function unsetOnCache(string $item, string $type = Cache::TYPE_NODE): bool
    {
        $this->_filterNodeType($type);

        if ($item == '' && isset($this->_cache[$type][$item])){
            unset($this->_cache[$type][$item], $this->_cacheDateInsertion[$type][$item]);
            return true;
        }
        return false;
    }

    public function unsetObjectOnCache(string $id): bool
    {
        return $this->unsetOnCache($id, self::TYPE_NODE);
    }

    public function getCacheObjectSize(): int
    {
        if (isset($this->_cache[self::TYPE_NODE]))
            return sizeof($this->_cache[self::TYPE_NODE]);
        else
            return 0;
    }

    public function unsetEntityOnCache(string $id): bool
    {
        return $this->unsetOnCache($id, self::TYPE_ENTITY);
    }

    public function getCacheEntitySize(): int
    {
        if (isset($this->_cache[self::TYPE_ENTITY]))
            return sizeof($this->_cache[self::TYPE_ENTITY]);
        else
            return 0;
    }

    public function unsetGroupOnCache(string $id): bool
    {
        return $this->unsetOnCache($id, self::TYPE_GROUP);
    }

    public function getCacheGroupSize(): int
    {
        if (isset($this->_cache[self::TYPE_GROUP]))
            return sizeof($this->_cache[self::TYPE_GROUP]);
        else
            return 0;
    }

    public function unsetConversationOnCache(string $id): bool
    {
        return $this->unsetOnCache($id, self::TYPE_CONVERSATION);
    }

    public function getCacheConversationSize(): int
    {
        if (isset($this->_cache[self::TYPE_CONVERSATION]))
            return sizeof($this->_cache[self::TYPE_CONVERSATION]);
        else
            return 0;
    }

    public function unsetCurrencyOnCache(string $id): bool
    {
        return $this->unsetOnCache($id, self::TYPE_CURRENCY);
    }

    public function getCacheCurrencySize(): int
    {
        if (isset($this->_cache[self::TYPE_CURRENCY]))
            return sizeof($this->_cache[self::TYPE_CURRENCY]);
        else
            return 0;
    }

    public function unsetTokenPoolOnCache(string $id): bool
    {
        return $this->unsetOnCache($id, self::TYPE_TOKEN);
    }

    public function getCacheTokenSize(): int
    {
        if (isset($this->_cache[self::TYPE_TOKEN]))
            return sizeof($this->_cache[self::TYPE_TOKEN]);
        else
            return 0;
    }

    public function unsetTokenOnCache(string $id): bool
    {
        return $this->unsetOnCache($id, self::TYPE_TOKENPOOL);
    }

    public function getCacheTokenPoolSize(): int
    {
        if (isset($this->_cache[self::TYPE_TOKENPOOL]))
            return sizeof($this->_cache[self::TYPE_TOKENPOOL]);
        else
            return 0;
    }

    public function unsetWalletOnCache(string $id): bool
    {
        return $this->unsetOnCache($id, self::TYPE_WALLET);
    }

    public function getCacheWalletSize(): int
    {
        if (isset($this->_cache[self::TYPE_WALLET]))
            return sizeof($this->_cache[self::TYPE_WALLET]);
        else
            return 0;
    }



    private function _filterLinkType(string &$type): void
    {
        if ($type != self::TYPE_LINK
            && $type != self::TYPE_TRANSACTION
        )
            $type = self::TYPE_LINK;
    }

    /**
     * Retire le cache d'une transaction.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture de la transaction.
     *
     * @param $link string
     * @return boolean
     */
    public function unsetCacheLink(string $link): bool
    {
        return $this->unsetOnCache($link, self::TYPE_LINK);
    }

    /**
     * Retourne le nombre de liens dans le cache.
     *
     * @return integer
     */
    public function getCacheLinkSize(): int
    {
        if (isset($this->_cache[self::TYPE_LINK]))
            return sizeof($this->_cache[self::TYPE_LINK]);
        else
            return 0;
    }
}