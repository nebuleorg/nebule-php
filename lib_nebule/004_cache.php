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
class Cache
{
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
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    private $_configuration;

    /**
     * Le tableau de mise en cache des noeuds.
     *
     * @var array
     */
    private $_cache = array();

    /**
     * Le tableau de mémorisation de la date de mise en cache des objets/entités/groupes/conversations/liens.
     *
     * @var array
     */
    private $_cacheDateInsertion = array();

    /**
     * Taille du cache.
     *
     * @var int
     */
    private $_sessionBufferLimit;

    private $_flushCache;

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_sessionBufferLimit = $this->_configuration->getOptionUntyped('sessionBufferSize');
        $this->_flushCache = $this->_nebuleInstance->getFlushCache();
    }

    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_sessionBufferLimit = $this->_configuration->getOptionUntyped('sessionBufferSize');
        $this->_flushCache = $this->_nebuleInstance->getFlushCache();
    }

    /**
     * Extrait les instances du buffer de session vers le cache.
     *
     * @return void
     */
    public function readCacheOnSessionBuffer(): void
    {
        if ($this->_flushCache
            || !$this->_configuration->getOptionUntyped('permitSessionBuffer')
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

    /**
     * Sauvegarde les instances du cache vers le buffer de session.
     *
     * @return void
     */
    public function saveCacheOnSessionBuffer(): void
    {
        if ($this->_flushCache
            || !$this->_configuration->getOptionUntyped('permitSessionBuffer')
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

    /**
     * Vide le buffer dans la session php.
     *
     * @return boolean
     */
    public function flushBufferStore(): bool
    {
        $this->_metrology->addLog('Flush buffer store', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        session_start();
        unset($_SESSION['Buffer']);
        $_SESSION['Buffer'] = array();
        session_write_close();
        return true;
    }

    /**
     * Nettoye le cache du nombre d'entrés demandées.
     *
     * @param int $c
     * @return void
     */
    private function _cleanCacheOverflow(int $c = 0): void
    {
        if (!$this->_configuration->getOptionUntyped('permitSessionBuffer')
            || !is_numeric($c)
            || $c <= 0
        )
            return;

        if ($c > 100)
            $this->_metrology->addLog(__METHOD__ . ' cache need flush ' . $c . '/' . sizeof($this->_cacheDateInsertion), Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '5bdb6961'); // Log

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

    /**
     * Retourne la taille totale de tous les caches.
     * Cette taille ne doit pas exéder la taille définie dans l'option sessionBufferSize.
     *
     * @return int
     */
    private function _getAllCachesSize(): int
    {
        return sizeof($this->_cache);
    }

    /**
     * Vérifie si il faut libérer une place pour l'ajout en cache d'un nouvel objet/lien.
     * C'est à dire que la taille maximum du cache est atteinte.
     * Libère au moins une place si besoin.
     *
     * @return void
     */
    private function _getCacheNeedOnePlace(): void
    {
        if (!$this->_configuration->getOptionUntyped('permitSessionBuffer'))
            return;

        $size = $this->_getAllCachesSize();
        $limit = $this->_sessionBufferLimit;
        if ($size >= $limit)
            $this->_cleanCacheOverflow($size - $limit + 1);
    }

    /**
     * Vérifie si il faut libérer de la place en cache.
     * C'est à dire que la taille maximum du cache est atteinte.
     * Libère de la place si besoin.
     *
     * @return void
     */
    private function _getCacheNeedCleaning(): void
    {
        if (!$this->_configuration->getOptionUntyped('permitSessionBuffer'))
            return;

        $size = $this->_getAllCachesSize();
        $limit = $this->_sessionBufferLimit;
        if ($size >= $limit)
            $this->_cleanCacheOverflow($size - $limit);
    }

    private function _filterNodeType(string &$type): void
    {
        if ($type != self::TYPE_NODE
            && $type != self::TYPE_GROUP
            && $type != self::TYPE_ENTITY
            && $type != self::TYPE_LOCATION
            && $type != self::TYPE_CONVERSATION
            && $type != self::TYPE_CURRENCY
            && $type != self::TYPE_TOKENPOOL
            && $type != self::TYPE_TOKEN
            && $type != self::TYPE_WALLET
        )
            $type = self::TYPE_NODE;
    }

    /**
     * Nouvelle instance d'un objet.
     *
     * @param string $nid
     * @param string $type
     * @return nodeInterface
     */
    public function newNode(string $nid, string $type = self::TYPE_NODE): nodeInterface
    {
        if ($nid == '')
            $nid = '0';

        $this->_filterNodeType($type);

        if (!$this->_flushCache
            && isset($this->_cache[$type][$nid])
        ) {
            $this->_cacheDateInsertion[$type][$nid] = microtime(true);
            $instance = $this->_cache[$type][$nid];
        } else {
            $this->_getCacheNeedOnePlace();

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
                    $instance = new Node($this->_nebuleInstance, $nid, '');
            }

            if ($this->_configuration->getOptionUntyped('permitSessionBuffer')) {
                $this->_cache[$type][$nid] = $instance;
                $this->_cacheDateInsertion[$type][$nid] = microtime(true);
            }
        }
        return $instance;
    }

    /**
     * Supprime le cache d'un noeud.
     *
     * @param string $item
     * @param string $type
     * @return boolean
     */
    public function unsetCache(string $item, string $type = self::TYPE_NODE): bool
    {
        $this->_filterNodeType($type);

        if ($item == '' && isset($this->_cache[$type][$item])){
            unset($this->_cache[$type][$item], $this->_cacheDateInsertion[$type][$item]);
            return true;
        }
        return false;
    }

    /**
     * Retire le cache d'un objet.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture de l'objet.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetObjectCache(string $id): bool
    {
        return $this->unsetCache($id, self::TYPE_NODE);
    }

    /**
     * Retourne le nombre d'objets dans le cache.
     *
     * @return integer
     */
    public function getCacheObjectSize(): int
    {
        return sizeof($this->_cache[self::TYPE_NODE]);
    }

    /**
     * Retire le cache d'une entité.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture de l'entité.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetCacheEntity(string $id): bool
    {
        return $this->unsetCache($id, self::TYPE_ENTITY);
    }

    /**
     * Retourne le nombre d'entités dans le cache.
     *
     * @return integer
     */
    public function getCacheEntitySize(): int
    {
        return sizeof($this->_cache[self::TYPE_ENTITY]);
    }

    /**
     * Retire le cache d'un groupe.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture du groupe.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetCacheGroup(string $id): bool
    {
        return $this->unsetCache($id, self::TYPE_GROUP);
    }

    /**
     * Retourne le nombre de groupes dans le cache.
     *
     * @return integer
     */
    public function getCacheGroupSize(): int
    {
        return sizeof($this->_cache[self::TYPE_GROUP]);
    }

    /**
     * Supprime le cache d'une conversation.
     *
     * @param string $id
     * @return boolean
     */
    public function unsetCacheConversation(string $id): bool
    {
        return $this->unsetCache($id, self::TYPE_CONVERSATION);
    }

    /**
     * Retourne le nombre de conversations dans le cache.
     *
     * @return integer
     */
    public function getCacheConversationSize(): int
    {
        return sizeof($this->_cache[self::TYPE_CONVERSATION]);
    }

    /**
     * Retire le cache d'une monnaie.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture de la monnaie.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetCacheCurrency(string $id): bool
    {
        return $this->unsetCache($id, self::TYPE_CURRENCY);
    }

    /**
     * Retourne le nombre de monnaies dans le cache.
     *
     * @return integer
     */
    public function getCacheCurrencySize(): int
    {
        return sizeof($this->_cache[self::TYPE_CURRENCY]);
    }

    /**
     * Retire le cache d'un sac de jetons.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture du sac de jetons.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetCacheTokenPool(string $id): bool
    {
        return $this->unsetCache($id, self::TYPE_TOKEN);
    }

    /**
     * Retourne le nombre de jetons dans le cache.
     *
     * @return integer
     */
    public function getCacheTokenSize(): int
    {
        return sizeof($this->_cache[self::TYPE_TOKEN]);
    }

    /**
     * Retire le cache d'un jeton.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture du jeton.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetCacheToken(string $id): bool
    {
        return $this->unsetCache($id, self::TYPE_TOKENPOOL);
    }

    /**
     * Retourne le nombre de sacs de jetons dans le cache.
     *
     * @return integer
     */
    public function getCacheTokenPoolSize(): int
    {
        return sizeof($this->_cache[self::TYPE_TOKENPOOL]);
    }

    /**
     * Retire le cache d'un poretfeuille.
     * C'est utilisé lorsque l'on modifie une propriété et que l'on souhaite forcer la relecture du portefeuille.
     *
     * @param $id string
     * @return boolean
     */
    public function unsetCacheWallet(string $id): bool
    {
        return $this->unsetCache($id, self::TYPE_WALLET);
    }

    /**
     * Retourne le nombre de portefeuilles dans le cache.
     *
     * @return integer
     */
    public function getCacheWalletSize(): int
    {
        return sizeof($this->_cache[self::TYPE_WALLET]);
    }



    private function _filterLinkType(string &$type): void
    {
        if ($type != self::TYPE_LINK
            && $type != self::TYPE_TRANSACTION
        )
            $type = self::TYPE_LINK;
    }

    /**
     * Nouvelle instance d'un lien.
     *
     * @param string $link
     * @param string $type
     * @return bloclinkInterface
     */
    public function newLink(string $link, string $type = self::TYPE_LINK): bloclinkInterface
    {
        if ($link == '')
            $link = 'invalid';

        $this->_filterLinkType($type);

        if (!$this->_flushCache
            && isset($this->_cache[$type][$link])
        ) {
            $this->_cacheDateInsertion[$type][$link] = microtime(true);
            $instance = $this->_cache[$type][$link];
        } else {
            $this->_getCacheNeedOnePlace();

            $instance = new Bloclink($this->_nebuleInstance, $link, $type);

            if ($this->_configuration->getOptionUntyped('permitSessionBuffer')) {
                $this->_cache[$type][$link] = $instance;
                $this->_cacheDateInsertion[$type][$link] = microtime(true);
            }
        }
        return $instance;
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
        return $this->unsetCache($link, self::TYPE_LINK);
    }

    /**
     * Retourne le nombre de liens dans le cache.
     *
     * @return integer
     */
    public function getCacheLinkSize(): int
    {
        return sizeof($this->_cache[self::TYPE_LINK]);
    }
}