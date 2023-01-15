<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * ------------------------------------------------------------------------------------------
 * La classe Object.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'un objet, ou '0' si c'est un objet nebule à créer.
 *
 * To create a new object with content :
 * - Create instance with NID=0
 * - Push content with $instance->setContent('content', ...)
 * ------------------------------------------------------------------------------------------
 */
class Node implements nodeInterface
{
    const CRYPTO_SESSION_KEY_SIZE = 117; // @todo utilisé par setProtected(), à refaire pour le cas général.

    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_id',
        '_fullname',
        '_cachePropertyLink',
        '_cachePropertiesLinks',
        '_cachePropertyID',
        '_cachePropertiesID',
        '_cacheProperty',
        '_cacheProperties',
        '_cacheMarkDanger',
        '_cacheMarkWarning',
        '_cacheMarkProtected',
        '_idProtected',
        '_idUnprotected',
        '_idProtectedKey',
        '_idUnprotectedKey',
        '_markProtectedChecked',
        '_cacheCurrentEntityUnlocked',
        '_usedUpdate',
        '_isEntity',
        '_isGroup',
        '_isConversation',
        '_isCurrency',
        '_isTokenPool',
        '_isToken',
        '_isWallet',
    );

    /**
     * Instance de la bilbiothèque nebule en PHP POO.
     *
     * @var nebule $_nebuleInstance
     */
    protected $_nebuleInstance;

    /**
     * Instance de la métrologie.
     *
     * @var Metrology $_metrology
     */
    protected $_metrology;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    protected $_configuration;

    /**
     * Instance de gestion du cache.
     *
     * @var Cache
     */
    protected $_cache;

    /**
     * Instance des I/O (entrées/sorties).
     *
     * @var ioInterface $_io
     */
    protected $_io;

    /**
     * Instance de la cryptographie.
     *
     * @var CryptoInterface $_crypto
     */
    protected $_crypto;

    /**
     * Instance sociale.
     *
     * @var SocialInterface $_social
     */
    protected $_social;

    /**
     * if buffering content is permitted.
     *
     * @var boolean $_permitBuffer
     */
    protected $_permitBuffer;

    /**
     * Identifiant de l'objet.
     * Si à 0, l'objet est invalide.
     *
     * @var string $_id
     */
    protected $_id = '0';

    /**
     * Le nom complet.
     * Forme : prénom préfix/nom.suffix surnom
     *
     * @var string $_fullname
     */
    protected $_fullname;

    /**
     * Cache.
     *
     * @var array $_cachePropertyLink
     */
    protected $_cachePropertyLink = array();

    /**
     * Cache.
     *
     * @var array $_cachePropertiesLinks
     */
    protected $_cachePropertiesLinks = array();

    /**
     * Cache.
     *
     * @var array $_cachePropertyID
     */
    protected $_cachePropertyID = array();

    /**
     * Cache.
     *
     * @var array $_cachePropertiesID
     */
    protected $_cachePropertiesID = array();

    /**
     * Cache.
     *
     * @var array $_cacheProperty
     */
    protected $_cacheProperty = array();

    /**
     * Cache.
     *
     * @var array $_cacheProperties
     */
    protected $_cacheProperties = array();

    /**
     * Cache.
     *
     * @var string $_cacheUpdate
     */
    protected $_cacheUpdate = '';

    /**
     * Cache.
     *
     * @var boolean $_cacheMarkDanger
     */
    protected $_cacheMarkDanger = false;

    /**
     * Cache.
     *
     * @var boolean $_cacheMarkWarning
     */
    protected $_cacheMarkWarning = false;

    /**
     * Cache.
     *
     * @var boolean $_cacheMarkProtected
     */
    protected $_cacheMarkProtected = false;

    /**
     * Identifiant de l'objet ayant le contenu protégé (chiffré).
     *
     * @var string $_idProtected
     */
    protected $_idProtected = '0';

    /**
     * Identifiant de l'objet ayant le contenu non protégé (en clair).
     *
     * @var string $_idUnprotected
     */
    protected $_idUnprotected = '0';

    /**
     * Identifiant de l'objet ayant le contenu de la clé protégée de déchiffrement de l'objet.
     *
     * @var string $_idProtectedKey
     */
    protected $_idProtectedKey = '0';

    /**
     * Identifiant de l'objet ayant le contenu de la clé non protégée de déchiffrement de l'objet.
     *
     * @var string $_idUnprotectedKey
     */
    protected $_idUnprotectedKey = '0';

    /**
     * Marqueur de détection de la protection de l'objet.
     *
     * @var boolean $_markProtectedChecked
     */
    protected $_markProtectedChecked = false;

    /**
     *
     * @var bool $_cacheCurrentEntityUnlocked
     */
    protected $_cacheCurrentEntityUnlocked = null;

    /**
     * Copie des données non encodées.
     * Ne pas mettre en cache de session PHP.
     *
     * @var string|null
     */
    protected $_data = null;

    /**
     * Des données non encodées sont présentes.
     * Ne pas mettre en cache de session PHP.
     *
     * @var string
     */
    protected $_haveData = false;

    /**
     * Copie des données encodées.
     * Ne pas mettre en cache de session PHP.
     *
     * @var string|null
     */
    protected $_code = '';

    /**
     * Des données encodées sont présentes.
     * Ne pas mettre en cache de session PHP.
     *
     * @var boolean
     */
    protected $_haveCode = false;

    /**
     *
     * @var array
     */
    protected $_usedUpdate = array();


    protected $_isNew = false;

    /**
     * Create instance of a node or derivative.
     * Always give a valid nebule instance.
     * For new node, set $id as '0'. This is mandatory to add data (or other) after with dedicated function.
     * If $id is invalid, the instance return getID = '0', even if new but not initialised.
     *
     * @param nebule  $nebuleInstance
     * @param string  $nid
     */
    public function __construct(nebule $nebuleInstance, string $nid)
    {
        // Common initialisation.
        $this->_initialisation($nebuleInstance);

        // ID processing.
        $id = trim(strtolower($nid));
        if (self::checkNID($id, false, false)
        ) {
            $this->_id = $id;
            $this->_metrology->addLog('New instance ' . get_class($this) . ' nid=' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '7fb8f6e3');
        } elseif ($id == '0')
        {
            $this->_id = '0';
            $this->_isNew = true;
        }

        // Load specific code for node derivative.
        $this->_localConstruct();
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->_id;
    }

    /**
     * Retourne les variables à sauvegarder dans la session php lors d'une mise en sommeil de l'instance.
     *
     * @return array:string
     */
    public function __sleep(): array
    {
        return self::SESSION_SAVED_VARS;
    }

    /**
     * Fonction de réveil de l'instance et de réinitialisation de certaines variables non sauvegardées.
     *
     * @return void
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_initialisation($nebuleInstance);

        $this->_cacheMarkDanger = false;
        $this->_cacheMarkWarning = false;
        $this->_data = '';
        $this->_haveData = false;
        $this->_cacheUpdate = '';
    }

    /**
     * Common initialisation of a node or derivative.
     * @param $nebuleInstance
     * @return void
     */
    protected function _initialisation($nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_social = $nebuleInstance->getSocialInstance();
        $this->_permitBuffer = $this->_configuration->getOptionAsBoolean('permitBufferIO');
    }

    /**
     * Specific part of constructor for a node.
     * @return void
     */
    protected function _localConstruct(): void
    {
        $this->_cacheCurrentEntityUnlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
    }

    /**
     * On new node (ID='0'), add content and recalculate ID.
     *
     * @param string $data
     * @param bool   $protect
     * @param bool   $obfuscated
     * @return bool
     */
    public function setContent(string &$data, bool $protect = false, bool $obfuscated = false): bool
    {
        if (!$this->_isNew
            || $this->_id != '0'
            || strlen($data) == 0
            || get_class($this) != 'Nebule\Library\Node'
        )
            return false;

        if ($this->_configuration->checkBooleanOptions(array('unlocked','permitWrite','permitWriteObject','permitWriteLink'))) {
            // calcul l'ID.
            $this->_id = $this->_nebuleInstance->getNIDfromData($data);
            if ($protect)
                $this->_metrology->addLog('Create protected object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '1434b3ed');
            else
                $this->_metrology->addLog('Create object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '52b9e412');

            // Mémorise les données.
            $this->_data = $data;
            $this->_haveData = true;

            // Création lien de hash.
            $date = '';
            if ($obfuscated)
                $date = '0';
            $target = $this->_nebuleInstance->getNIDfromData($this->_configuration->getOptionAsString('cryptoHashAlgorithm'));
            $meta = $this->_nebuleInstance->getNIDfromData(nebule::REFERENCE_NEBULE_OBJET_HASH);
            $this->_writeLink('l>' . $this->_id . '>' . $target . '>' . $meta, $obfuscated, $date);

            // Création du lien d'annulation de suppression.
            $this->_writeLink('x>' . $this->_id, $obfuscated);

            // Si l'objet doit être protégé.
            if ($protect)
                $this->setProtected($obfuscated);
            else
                $this->write();
        } else {
            $this->_metrology->addLog('Create object error no authorized', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '83a27d1e');
            $this->_id = '0';
            return false;
        }
        return true;
    }

    /**
     * Retourne l'ID de l'objet.
     *
     * @return string
     */
    public function getID(): string
    {
        return $this->_id;
    }

    /**
     * Object - Verify name structure of the node : hash.algo.size
     * There's a specific treatment for NID empty or '0'.
     *
     * @param string $nid
     * @param bool   $permitEmpty permit NID=''
     * @param bool   $permitZero  permit NID='0'
     * @return boolean
     */
    static public function checkNID(string &$nid, bool $permitEmpty = false, bool $permitZero = false): bool
    {
        // May be empty or zero in some case.
        if ($permitEmpty && $nid == '') return true;
        if ($permitZero && $nid == '0') return true;

        // Check hash value.
        $hash = strtok($nid, '.');
        if ($hash === false) return false;
        if (strlen($hash) < blocLink::NID_MIN_HASH_SIZE) return false;
        if (strlen($hash) > blocLink::NID_MAX_HASH_SIZE) return false;
        if (!ctype_xdigit($hash)) return false;

        // Check algo value.
        $algo = strtok('.');
        if ($algo === false) return false;
        if (strlen($algo) < blocLink::NID_MIN_ALGO_SIZE) return false;
        if (strlen($algo) > blocLink::NID_MAX_ALGO_SIZE) return false;
        if (!ctype_alnum($algo)) return false;

        // Check size value.
        $size = strtok('.');
        if ($size === false) return false;
        if (!ctype_digit($size)) return false; // Check content before!
        if ((int)$size < blocLink::NID_MIN_HASH_SIZE) return false;
        if ((int)$size > blocLink::NID_MAX_HASH_SIZE) return false;
        if ((strlen($hash) * 4) != (int)$size) return false;

        // Check item overflow
        if (strtok('.') !== false) return false;

        return true;
    }

    /**
     * Retourne la couleur primaire de l'objet.
     *
     * @return string
     */
    public function getPrimaryColor(): string
    {
        return substr($this->_id . '000000', 0, 6);
    }

    /**
     * Retourne l'algorithme de hash.
     * @return string
     * @todo
     *
     */
    public function getHashAlgo(): string
    {
        $algo = $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_HASH, 'all');
        $this->_metrology->addLog('Object ' . $this->_id . ' hash = ' . $algo, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        if ($algo != '')
            return $algo;
        return '';
    }

    /**
     * Test si l'objet est présent.
     *
     * @return boolean
     */
    public function checkPresent(): bool
    {
        if ($this->_id == '0')
            return false;

        /*if ($this->_getMarkProtected())
            $result = $this->_nebuleInstance->getIoInstance()->checkObjectPresent($this->_idProtected);
        else*/
            $result = $this->_nebuleInstance->getIoInstance()->checkObjectPresent($this->_id);
        return $result;
    }

    /**
     * Test si l'objet a des liens.
     *
     * @return boolean
     */
    public function checkObjectHaveLinks(): bool
    {
        return $this->_io->checkLinkPresent($this->_id);
    }

    /**
     * Faire une recherche de liens type 'l' en fonction de l'objet méta.
     * Typiquement utilisé pour une recherche de propriétés d'un objet.
     * Fait une recherche sur de multiples algorithmes de hash au besoin.
     *
     * @param array  $links
     * @param string $nid3
     */
    private function _getLinksByNID3(array &$links, string $nid3): void
    {
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid3' => $nid3,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, false);
    }

    /**
     * Lit une propriété de l'objet nebule dans ses liens.
     * Retourne la liste des liens définissants la propriété.
     *
     * @param string $type
     * @param string $socialClass
     * @return array
     */
    public function getPropertiesLinks(string $type, string $socialClass = ''): array
    {
        if ($type == '')
            return array();

        if (!$this->checkNID($type))
            $type = $this->_nebuleInstance->getNIDfromData($type);

        // Si déjà recherché, donne le résultat en cache.
//        if (isset($this->_cachePropertiesLinks[$type][$socialClass]))
//            return $this->_cachePropertiesLinks[$type][$socialClass];

        // Liste les liens à la recherche de la propriété.
        $links = array();
        $this->_getLinksByNID3($links, $type);

        if (sizeof($links) == 0)
            return array();

        // Trie la liste, pour les liens venants de plusieurs objets.
        $date = array();
        foreach ($links as $k => $r)
            $date[$k] = $r->getDate();
        array_multisort($date, SORT_STRING, SORT_ASC, $links);

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Mémorise le résultat dans le cache.
        //if ($this->_permitBuffer)
        //    $this->_cachePropertiesLinks[$type][$socialClass] = $links;

        // Résultat.
        return $links;
    }

    /**
     * Lit une propriété de l'objet nebule dans ses liens.
     * Retourne le lien unique définissant la propriété.
     * Retourne une chaine vide si pas de lien de propriété trouvé.
     *
     * @param string $type
     * @param string $socialClass
     * @return linkInterface|null
     */
    public function getPropertyLink(string $type, string $socialClass = ''): ?linkInterface
    {
        if ($type == '')
            return null;

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cachePropertyLink[$type][$socialClass]))
            return $this->_cachePropertyLink[$type][$socialClass];

        // Liste les liens à la recherche de la propriété.
        $links = $this->getPropertiesLinks($type, $socialClass);

        if (sizeof($links) == 0)
            return null;

        // Extrait le dernier de la liste.
        //$link = end($links);
        //$link = $links[count($links)-1];
        $link = $links[0];

        // Mémorise le résultat dans le cache.
        //if ($this->_permitBuffer)
        //    $this->_cachePropertyLink[$type][$socialClass] = $link;

        // Résultat.
        return $link;
    }

    /**
     * Lit une propriété de l'objet nebule dans ses liens.
     * Retourne une chaine de texte de _une seule_ ligne, ou une chaine vide si problème.
     *
     * @param string $type
     * @param string $socialClass
     * @return string
     */
    public function getPropertyID(string $type, string $socialClass = ''): string
    {
        if ($type == '')
            return '';

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cachePropertyID[$type][$socialClass]))
            return $this->_cachePropertyID[$type][$socialClass];

        $property = '';

        // Liste les liens à la recherche de la propriété.
        $link = $this->getPropertyLink($type, $socialClass);

        if (!is_a($link, 'Link'))
            return '';

        // Extrait l'ID de l'objet de propriété.
        $property = $link->getParsed()['bl/rl/nid2'];
        unset($link);

        // Mémorise le résultat dans le cache.
        //if ($this->_permitBuffer)
        //    $this->_cachePropertyID[$type][$socialClass] = $property;

        // Résultat.
        return $property;
    }

    /**
     * Lit les propriétés de l'objet nebule dans ses liens.
     * Retourne un tableau de chaines de texte de une seule ligne, ou un tableau de une chaine vide si problème.
     *
     * @param string $type
     * @param string $socialClass
     * @return array
     */
    public function getPropertiesID(string $type, string $socialClass = ''): array
    {
        if ($type == '')
            return array();

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cachePropertiesID[$type][$socialClass]))
            return $this->_cachePropertiesID[$type][$socialClass];

        $properties = array();

        // Liste les liens à la recherche de la propriété.
        $list = array();
        $this->_getLinksByNID3($list, $type);

        if (sizeof($list) == 0)
            return array();

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($list, $socialClass);

        // Extrait les ID des objets de propriété.
        foreach ($list as $i => $l)
            $properties[$i] = $l->getParsed()['bl/rl/nid2'];
        unset($list);

        // Mémorise le résultat dans le cache.
        //if ($this->_permitBuffer)
        //    $this->_cachePropertiesID[$type][$socialClass] = $properties;

        // Résultat.
        return $properties;
    }

    /**
     * Lit une propriété de l'objet nebule dans ses liens.
     * Retourne une chaine de texte de _une seule_ ligne, ou une chaine vide si problème.
     *
     * @param string $type
     * @param string $socialClass
     * @return string
     */
    public function getProperty(string $type, string $socialClass = ''): string
    {
        if ($type == '')
            return '';

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cacheProperty[$type][$socialClass]))
            return $this->_cacheProperty[$type][$socialClass];

        // Liste les liens à la recherche de la propriété.
        $link = $this->getPropertyLink($type, $socialClass);

        if ($link == '' || !is_a($link, 'Nebule\Library\Link'))
            return '';

        // Extrait le contenu de l'objet de propriété.
        $property = $this->_readOneLineOtherObject($link->getParsed()['bl/rl/nid2']);

        // Mémorise le résultat dans le cache.
        //if ($this->_permitBuffer)
        //    $this->_cacheProperty[$type][$socialClass] = $property;

        // Résultat.
        return $property;
    }

    /**
     * Lit les propriétés de l'objet nebule dans ses liens.
     * Retourne un tableau de chaines de texte de une seule ligne, ou un tableau de une chaine vide si problème.
     *
     * @param string $type
     * @param string $socialClass
     * @return array
     */
    public function getProperties(string $type, string $socialClass = ''): array
    {
        if ($type == '')
            return array();

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cacheProperties[$type][$socialClass]))
            return $this->_cacheProperties[$type][$socialClass];

        $properties = array();

        // Liste les liens à la recherche de la propriété.
        $links = array();
        $this->_getLinksByNID3($links, $type);

        if (sizeof($links) == 0)
            return array();

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Extrait le contenu des objets de propriété.
        foreach ($links as $i => $l)
            $properties[$i] = $this->_readOneLineOtherObject($l->getParsed()['bl/rl/nid2']);
        unset($links);

        // Mémorise le résultat dans le cache.
        //if ($this->_permitBuffer)
        //    $this->_cacheProperties[$type][$socialClass] = $properties;

        // Résultat.
        return $properties;
    }

    /**
     * Lit si l'objet a une propriété particulière.
     *
     * @param string $type
     * @param string $property
     * @param string $socialClass
     * @return boolean
     */
    public function getHaveProperty(string $type, string $property, string $socialClass = 'myself'): bool
    {
        // Extrait la liste des propriétés.
        $list = $this->getProperties($type, $socialClass);

        // Cherche dans la liste la propriété de groupe.
        foreach ($list as $item) {
            if ($item == $property)
                return true;
        }

        // Si la propriété n'est pas trouvée.
        return false;
    }

    /**
     * Recherche les ID des entités qui ont signé une propriété de l'objet.
     * Si la propriété est vide, retourne les signataires de toute propriété.
     *
     * @param string $type
     * @return array:string
     */
    public function getPropertySigners(string $type = ''): array
    {
        $signers = array();

        // Si le type de l'objet est précisé, le converti en ID.
        if ($type != '')
            $type = $this->_nebuleInstance->getNIDfromData($type, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);

        // Extraction des entités signataires.
        $links = $this->getPropertiesLinks(nebule::REFERENCE_NEBULE_OBJET_TYPE, 'all');

        foreach ($links as $link) {
            if ($type == ''
                || ($type != ''
                    && $link->getParsed()['bl/rl/nid2'] == $type
                )
            )
                $signers[$link->getParsed()['bs/rs1/eid']] = $link->getParsed()['bs/rs1/eid'];
        }
        unset($links);

        return $signers;
    }

    /**
     * Recherche une propriété de l'objet est sgnée par une entité.
     * Si la propriété est vide, vérifie pour toute propriété.
     *
     * @param string $entity
     * @param string $type
     * @return boolean
     */
    public function getPropertySignedBy(string $entity, string $type = ''): bool
    {
        // Si le type de l'objet est précisé, le converti en ID.
        if ($type != '')
            $type = $this->_nebuleInstance->getNIDfromData($type, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);

        // extrait l'ID de l'entité si c'est un objet.
        if (is_a($entity, 'Node'))
            $entity = $entity->getID();

        // Extraction des entités signataires.
        $links = $this->getPropertiesLinks(nebule::REFERENCE_NEBULE_OBJET_TYPE, 'all');

        foreach ($links as $link) {
            if ($type == ''
                || $link->getParsed()['bl/rl/nid2'] == $type
            ) {
                if ($link->getParsed()['bs/rs1/eid'] == $entity)
                    return true;
            }
        }
        unset($links);

        return false;
    }

    /**
     * Ecrit la propriété de l'objet correspondant au type.
     * TODO protection et vidage cache
     * @param string $type
     * @param string $property
     * @param boolean $protect
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setProperty(string $type, string $property, bool $protect = false, bool $obfuscated = false): bool
    {
        if ($type == ''
            || $property == ''
            || $protect // TODO
        )
            return false;

        $propertyOID = $this->_nebuleInstance->getNIDfromData($property);
        $this->_io->setObject($propertyOID, $property);
        $propertyRID = $this->_nebuleInstance->getNIDfromData($type);
        $link = 'l>' . $this->_id . '>' . $propertyOID . '>' . $propertyRID;
        $newBlockLink = new blocLink($this->_nebuleInstance, 'new');
        $newLink = new Link($this->_nebuleInstance, $link, $newBlockLink);
        if ($obfuscated && !$newLink->setObfuscate())
            return false;
        $newBlockLink->signwrite($this->_nebuleInstance->getCurrentEntity());

        // Supprime le résultat dans le cache.
        /*		if ( isset($this->_cacheProperty[$type]) )
		{
			foreach ( $this->_cacheProperty[$type] as $i => $v )
			{
				unset($this->_cacheProperty[$type][$i]);
			}
		}
		if ( isset($this->_cacheProperties[$type]) )
		{
			foreach ( $this->_cacheProperties[$type] as $i => $v )
			{
				unset($this->_cacheProperties[$type][$i]);
			}
		}*/
        // TODO ------------------------------------------------------------------------------- A revoir...
        $this->_cacheProperty = array();
        $this->_cacheProperties = array();

        return true;
    }

    /**
     * Lit l'ID du type mime.
     *
     * @param string $socialClass
     * @return string
     */
    public function getTypeID(string $socialClass = ''): string
    {
        return $this->getPropertyID(nebule::REFERENCE_NEBULE_OBJET_TYPE, $socialClass);
    }

    /**
     * Lit le type mime.
     *
     * @param string $socialClass
     * @return string
     */
    public function getType(string $socialClass = ''): string
    {
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, $socialClass);
    }

    /**
     * Ecriture du type mime.
     *
     * @param string $type
     * @return boolean
     */
    public function setType(string $type): bool
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '00000000');

        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, $type);
    }

    /**
     * Lit la taille.
     *
     * @param string $socialClass
     * @return string
     */
    public function getSize(string $socialClass = ''): string
    {
        return filter_var($this->getProperty(nebule::REFERENCE_NEBULE_OBJET_TAILLE, $socialClass), FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Lit l'empreinte homomorphe.
     *
     * @param string $socialClass
     * @return string
     */
    public function getHomomorphe(string $socialClass = ''): string
    {
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_HOMOMORPHE, $socialClass);
    }

    /**
     * Lit l'empreinte (ID).
     * @return string
     */
    public function getHash(): string
    {
        return $this->_id;
    }

    /**
     * Lit la date de création.
     *
     * @param string $socialClass
     * @return string
     */
    public function getDate(string $socialClass = ''): string
    {
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_DATE, $socialClass);
    }

    /**
     * Lecture du nom.
     *
     * @param string $socialClass
     * @return string
     */
    public function getName(string $socialClass = ''): string
    {
        $name = $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_NOM, $socialClass);
        if ($name == '')
            $name = $this->_id;
        return $name;
    }

    /**
     * Ecriture du nom.
     *
     * @param string $name
     * @return boolean
     */
    public function setName(string $name): bool
    {
        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_NOM, $name, $this->_getMarkProtected());
    }

    /**
     * Lecture du préfix.
     *
     * @param string $socialClass
     * @return string
     */
    public function getPrefixName(string $socialClass = ''): string
    {
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_PREFIX, $socialClass);
    }

    /**
     * Ecriture du préfix.
     *
     * @param string $prefix
     * @return boolean
     */
    public function setPrefix(string $prefix): bool
    {
        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_PREFIX, $prefix, $this->_getMarkProtected());
    }

    /**
     * Lecture du suffix.
     *
     * @param string $socialClass
     * @return string
     */
    public function getSuffixName(string $socialClass = ''): string
    {
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_SUFFIX, $socialClass);
    }

    /**
     * Ecriture du suffix.
     *
     * @param string $suffix
     * @return boolean
     */
    public function setSuffix(string $suffix): bool
    {
        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_SUFFIX, $suffix, $this->_getMarkProtected());
    }

    /**
     * Lecture du prénom.
     *
     * @param string $socialClass
     * @return string
     */
    public function getFirstname(string $socialClass = ''): string
    {
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_PRENOM, $socialClass);
    }

    /**
     * Ecriture du prénom.
     *
     * @param string $firstname
     * @return boolean
     */
    public function setFirstname(string $firstname): bool
    {
        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_SUFFIX, $firstname, $this->_getMarkProtected());
    }

    /**
     * Lecture du surnom.
     *
     * @param string $socialClass
     * @return string
     */
    public function getSurname(string $socialClass = ''): string
    {
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_SURNOM, $socialClass);
    }

    /**
     * Ecriture du surnom.
     *
     * @param string $surname
     * @return boolean
     */
    public function setSurname(string $surname): bool
    {
        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_SURNOM, $surname, $this->_getMarkProtected());
    }

    /**
     * Lecture du nom complet.
     *
     * @param string $socialClass
     * @return string
     */
    public function getFullName(string $socialClass = ''): string
    {
        if (isset($this->_fullname)
            && trim($this->_fullname) != ''
        )
            return $this->_fullname;

        $fullname = $this->getName($socialClass);

        $prefix = $this->getPrefixName($socialClass);
        if ($prefix != '')
            $fullname = $prefix . '/' . $fullname;

        $suffix = $this->getSuffixName($socialClass);
        if ($suffix != '')
            $fullname = $fullname . '.' . $suffix;

        $firstname = $this->getFirstname($socialClass);
        if ($firstname != '')
            $fullname = $firstname . ' ' . $fullname;

        $surname = $this->getSurname($socialClass);
        if ($surname != '')
            $fullname = $fullname . ' ' . $surname;

        $this->_fullname = $fullname;
        return $fullname;
    }

    /**
     * Lecture des multiples localisations.
     *
     * @param string $socialClass
     * @return array
     */
    public function getLocalisations(string $socialClass = ''): array
    {
        return $this->getProperties(nebule::REFERENCE_NEBULE_OBJET_LOCALISATION, $socialClass);
    }

    /**
     * Lecture de la localisation.
     *
     * @param string $socialClass
     * @return string
     */
    public function getLocalisation(string $socialClass = ''): string
    {
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_LOCALISATION, $socialClass);
    }

    /**
     * Ecriture de la localisation.
     *
     * @param string $localisation
     * @return boolean
     */
    public function setLocalisation(string $localisation): bool
    {
        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_LOCALISATION, $localisation);
    }


    /**
     * Variable si l'objet est marqué comme une entité.
     * @var boolean
     */
    protected $_isEntity = false;

    /**
     * Lit si l'objet est une entité.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function getIsEntity(string $socialClass = 'myself'): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isEntity)
            return true;

        $type = $this->getType($socialClass);
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        $this->_isEntity = ($type == nebule::REFERENCE_OBJECT_ENTITY && strpos($objHead, nebule::REFERENCE_ENTITY_HEADER) !== false);

        unset($objHead);
        return $this->_isEntity;
    }


    /**
     * Variable si l'objet est marqué comme un groupe.
     * @var boolean
     */
    protected $_isGroup = false;

    /**
     * Lit si l'objet est un groupe.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function getIsGroup(string $socialClass = 'myself'): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isGroup)
            return true;

        $this->_isGroup = $this->getHaveProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, nebule::REFERENCE_NEBULE_OBJET_GROUPE, $socialClass);

        return $this->_isGroup;
    }

    /**
     * Retourne la liste des liens vers les groupes dont l'objet est membre.
     *
     * @param string $socialClass
     * @return array:Link
     */
    public function getListIsMemberOnGroupLinks(string $socialClass = 'myself'): array
    {
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, false);

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getParsed()['bl/rl/nid1'] != $link->getParsed()['bl/rl/nid3'])
                unset($links[$i]);
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newGroup($link->getParsed()['bl/rl/nid1']);
            if (!$instance->getIsGroup('all'))
                unset($links[$i]);
        }

        return $links;
    }

    /**
     * Retourne la liste des ID vers les groupes dont l'objet est membre.
     *
     * @param string $socialClass
     * @return array:string
     */
    public function getListIsMemberOnGroupID(string $socialClass = 'myself'): array
    {
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, false);

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getParsed()['bl/rl/nid1'] != $link->getParsed()['bl/rl/nid3'])
                unset($links[$i]);
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newGroup($link->getParsed()['bl/rl/nid1']);
            if ($instance->getIsGroup('all'))
                $list[$link->getParsed()['bl/rl/nid1']] = $link->getParsed()['bl/rl/nid1'];
        }

        return $list;
    }


    /**
     * Variable si l'objet est marqué comme une conversation.
     * @var boolean
     */
    protected $_isConversation = false;

    /**
     * Lit si l'objet est une conversation.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function getIsConversation(string $socialClass = 'myself'): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isConversation)
            return true;

        $this->_isConversation = $this->getHaveProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, nebule::REFERENCE_NEBULE_OBJET_CONVERSATION, $socialClass);

        return $this->_isConversation;
    }

    /**
     * Retourne la liste des liens vers les conversations dont l'objet est membre.
     * S'appuie sur la fonction dédiée aux groupes.
     *
     * @param string $socialClass
     * @return array:Link
     */
    public function getListIsMemberOnConversationLinks(string $socialClass = 'myself'): array
    {
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, false);

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getParsed()['bl/rl/nid1'] != $link->getParsed()['bl/rl/nid3'])
                unset($links[$i]);
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newConversation($link->getParsed()['bl/rl/nid1']);
            if (!$instance->getIsConversation('all'))
                unset($links[$i]);
        }

        return $links;
    }

    /**
     * Retourne la liste des ID vers les conversations dont l'objet est membre.
     * S'appuie sur la fonction dédiée aux groupes.
     *
     * @param string $socialClass
     * @return array:string
     */
    public function getListIsMemberOnConversationID(string $socialClass = 'myself'): array
    {
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, false);

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getParsed()['bl/rl/nid1'] != $link->getParsed()['bl/rl/nid3'])
                unset($links[$i]);
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newConversation($link->getParsed()['bl/rl/nid1']);
            if ($instance->getIsConversation('all'))
                $list[$link->getParsed()['bl/rl/nid1']] = $link->getParsed()['bl/rl/nid1'];
        }

        return $list;
    }


    /**
     * Variable si l'objet est marqué comme une monnaie.
     * @var boolean
     */
    protected $_isCurrency = false;

    /**
     * Lit si l'objet est une monnaie.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function getIsCurrency(string $socialClass = 'myself'): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isCurrency)
            return true;

        $this->_isCurrency = $this->getHaveProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, nebule::REFERENCE_NEBULE_OBJET_MONNAIE, $socialClass);

        return $this->_isCurrency;
    }


    /**
     * Variable si l'objet est marqué comme un sac de jetons.
     * @var boolean
     */
    protected $_isTokenPool = false;

    /**
     * Lit si l'objet est un sac de jetons.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function getIsTokenPool(string $socialClass = 'myself'): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isTokenPool)
            return true;

        $this->_isTokenPool = $this->getHaveProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, nebule::REFERENCE_NEBULE_OBJET_MONNAIE_SAC, $socialClass);

        return $this->_isTokenPool;
    }


    /**
     * Variable si l'objet est marqué comme un jeton.
     * @var boolean
     */
    protected $_isToken = false;

    /**
     * Lit si l'objet est un jeton.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function getIsToken(string $socialClass = 'myself'): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isToken)
            return true;

        $this->_isToken = $this->getHaveProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, nebule::REFERENCE_NEBULE_OBJET_MONNAIE_JETON, $socialClass);

        return $this->_isToken;
    }


    /**
     * Variable si l'objet est marqué comme un portefeuille.
     * @var boolean
     */
    protected $_isWallet = false;

    /**
     * Lit si l'objet est un portefeuille.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function getIsWallet(string $socialClass = 'myself'): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isWallet)
            return true;

        $this->_isWallet = $this->getHaveProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, nebule::REFERENCE_NEBULE_OBJET_MONNAIE_PORTEFEUILLE, $socialClass);

        return $this->_isWallet;
    }


    /**
     * Lit les marques de Danger.
     * @return boolean
     * @todo
     *
     */
    public function getMarkDanger(): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkDanger)
            return true;

        $list = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_nebuleInstance->getNIDfromData(nebule::REFERENCE_NEBULE_DANGER),
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, false);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_configuration->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($list);

        // Si pas marqué, tout va bien. Résultat négatif.
        if (sizeof($list) == 0)
            return false;

        // Sinon.
        // Mémorise le résultat dans le cache.
        $this->_cacheMarkDanger = true;

        // Résultat positif.
        return true;
    }

    /**
     * Ecrit une marque de Danger.
     * @return boolean
     */
    public function setMarkDanger(): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkDanger)
            return true;

        // Création lien de groupe.
        $target = $this->_nebuleInstance->getNIDfromData(nebule::REFERENCE_NEBULE_DANGER);
        $this->_writeLink('l>' . $this->_id . '>' . $target);

        $this->_cacheMarkDanger = true;
        return true;
    }

    /**
     * Lit les marques de Warning.
     * @return boolean
     * @todo
     *
     */
    public function getMarkWarning(): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkWarning)
            return true;

        $list = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_nebuleInstance->getNIDfromData(nebule::REFERENCE_NEBULE_WARNING),
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, false);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_configuration->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($list);

        // Si pas marqué, tout va bien. Résultat négatif.
        if (sizeof($list) == 0)
            return false;

        // Sinon.
        // Mémorise le résultat dans le cache.
        $this->_cacheMarkWarning = true;

        // Résultat positif.
        return true;
    }

    /**
     * Ecrit une marque de Warning.
     * @return boolean
     */
    public function setMarkWarning(): bool
    {
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkWarning)
            return true;

        // Création lien de groupe.
        $target = $this->_nebuleInstance->getNIDfromData(nebule::REFERENCE_NEBULE_WARNING);
        $this->_writeLink('l>' . $this->_id . '>' . $target);

        $this->_cacheMarkWarning = true;
        return true;
    }


    /**
     * Lit les marques de protection, c'est à dire un lien de chiffrement pour l'objet.
     * Fait une recherche complète.
     * @return boolean
     */
    public function getMarkProtected(): bool
    {
        $this->_getMarkProtected();
        return $this->_cacheMarkProtected;
    }

    /**
     * Lit les marques de protection, c'est à dire un lien de chiffrement pour l'objet.
     * Fait une recherche sommaire et rapide.
     * @return boolean
     */
    public function getMarkProtectedFast(): bool
    {
        if ($this->_markProtectedChecked === true)
            return $this->_cacheMarkProtected;

        if ($this->_id == '0')
            return false;

        // Liste les liens à la recherche de la propriété.
        $listS = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($listS, $filter, false);
        $listT = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($listT, $filter, false);

        // Si pas marqué, résultat négatif.
        if (sizeof($listS) == 0
            && sizeof($listT) == 0
        )
            return false;
        return true;
    }

    /**
     * Lit les marques de protection, c'est à dire un lien de chiffrement pour l'objet.
     * Force la relecture de la marque de protection. A utiliser par exemple après une synchronisation.
     * @return boolean
     */
    public function getReloadMarkProtected(): bool
    {
        // Réinitialisation.
        $this->_markProtectedChecked = false;
        $this->_cacheMarkProtected = false;
        $this->_idProtected = '0';
        $this->_idUnprotected = '0';
        $this->_idProtectedKey = '0';
        $this->_idUnprotectedKey = '0';

        // Recherche.
        $this->_getMarkProtected();
        return $this->_cacheMarkProtected;
    }

    /**
     * Lit les marques de protection, c'est à dire un lien de chiffrement pour l'objet.
     * Extrait toutes les valeurs nécéssaires au déchiffrement.
     * @return boolean
     * @todo
     *
     */
    protected function _getMarkProtected(): bool
    {
        return false; // FIXME disabled!

        if ($this->_markProtectedChecked === true
            && $this->_cacheCurrentEntityUnlocked === $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            if ($this->_cacheMarkProtected === true) {
                $this->_metrology->addLog('Object protected - cache - protected', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            } else {
                $this->_metrology->addLog('Object protected - cache - not protected', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            }
            return $this->_cacheMarkProtected;
        }

        if ($this->_id == '0') {
            return false;
        }

        // Mémorise l'état de connexion de l'entité courante.
        $this->_cacheCurrentEntityUnlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        // Liste les liens à la recherche de la propriété.
        $listS = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($listS, $filter, false);
        $listT = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($listT, $filter, false);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($listS) == 0
            && $this->_configuration->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }
        if (sizeof($listT) == 0
            && $this->_configuration->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }

        // Si pas marqué, résultat négatif.
        if (sizeof($listS) == 0
            && sizeof($listT) == 0
        ) {
            $this->_cacheMarkProtected = false;
            $this->_idProtected = '0';
            $this->_idUnprotected = $this->_id;
            $this->_idProtectedKey = '0';
            $this->_idUnprotectedKey = '0';
            $this->_metrology->addLog('Object protected - not protected', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            return false;
        }

        // Sinon.
        $this->_markProtectedChecked = true;
        $result = false;

        if (sizeof($listS) == 0) {
            $this->_metrology->addLog('Object protected - id protected = ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            $this->_idProtected = $this->_id;

            // Recherche la clé utilisée pour l'entité en cours.
            foreach ($listT as $linkSym) {
                // Si lien de chiffrement et l'objet source est l'objet en cours non protégé.
                if ($linkSym->getAction() == 'k'
                    && $linkSym->getParsed()['bl/rl/nid2'] == $this->_idProtected
                ) {
                    // Lit l'objet de clé de chiffrement symétrique et ses liens.
                    $instanceSym = $this->_nebuleInstance->newObject($linkSym->getParsed()['bl/rl/nid3']);
                    $linksAsym = array();
                    $this->getLinks($linksAsym, array(), false);
                    unset($instanceSym);
                    foreach ($linksAsym as $linkAsym) {
                        // Si lien de chiffrement.
                        $targetA = $linkAsym->getParsed()['bl/rl/nid2'];
                        if ($linkAsym->getAction() == 'k'
                            && $linkAsym->getParsed()['bl/rl/nid2'] != $this->_idProtected
                            && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($targetA)
                        ) {
                            $result = true;
                            $this->_idUnprotected = $linkSym->getParsed()['bl/rl/nid1'];
                            $this->_metrology->addLog('Object protected - id unprotected = ' . $this->_idUnprotected, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                            $this->_idUnprotectedKey = $linkAsym->getParsed()['bl/rl/nid1'];
                            if ($linkAsym->getParsed()['bl/rl/nid3'] == $this->_nebuleInstance->getCurrentEntity()) {
                                $this->_idProtectedKey = $targetA;
                                break 2;
                            }
                        }
                    }
                    unset($linksAsym, $linkAsym);
                }
            }
            unset($listT, $linkSym);
        } else {
            $this->_metrology->addLog('Object protected - id unprotected = ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            $this->_idUnprotected = $this->_id;

            // Recherche la clé utilisée pour l'entité en cours.
            foreach ($listS as $linkSym) {
                $targetS = $linkSym->getParsed()['bl/rl/nid2'];
                // Si lien de chiffrement et l'objet source est l'objet en cours non protégé.
                if ($linkSym->getAction() == 'k'
                    && $linkSym->getParsed()['bl/rl/nid1'] == $this->_idUnprotected
                    && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($targetS)
                ) {
                    // Lit l'objet de clé de chiffrement symétrique et ses liens.
                    $instanceSym = $this->_nebuleInstance->newObject($linkSym->getParsed()['bl/rl/nid3']);
                    $linksAsym = array();
                    $this->getLinks($linksAsym, array(), false);
                    unset($instanceSym);
                    foreach ($linksAsym as $linkAsym) {
                        $targetA = $linkAsym->getParsed()['bl/rl/nid2'];
                        // Si lien de chiffrement.
                        if ($linkAsym->getAction() == 'k'
                            && $linkAsym->getParsed()['bl/rl/nid1'] != $this->_idUnprotected
                            && $linkAsym->getParsed()['bl/rl/nid3'] == $this->_nebuleInstance->getCurrentEntity()
                            && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($targetA)
                        ) {
                            $result = true;
                            $this->_idProtected = $targetS;
                            $this->_metrology->addLog('Object protected - id protected = ' . $this->_idProtected, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                            $this->_idUnprotectedKey = $linkAsym->getParsed()['bl/rl/nid1'];
                            if ($linkAsym->getParsed()['bl/rl/nid3'] == $this->_nebuleInstance->getCurrentEntity()) {
                                $this->_idProtectedKey = $targetA;
                                break 2;
                            }
                        }
                    }
                    unset($linksAsym, $linkAsym);
                }
            }
            unset($listS, $linkSym);
        }

        // Résultat.
        $this->_cacheMarkProtected = $result;
        return $result;
    }

    /**
     * Lit l'ID de l'objet chiffré.
     *
     * @return string
     */
    public function getProtectedID(): string
    {
        return ''; // FIXME disabled!

        $this->_getMarkProtected();
        return $this->_idProtected;
    }

    /**
     * Lit l'ID de l'objet non chiffré.
     * @return string
     */
    public function getUnprotectedID(): string
    {
        return ''; // FIXME disabled!

        $this->_getMarkProtected();
        return $this->_idUnprotected;
    }

    /**
     * Protège l'objet.
     *
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setProtected(bool $obfuscated = false): bool
    {
        return false; // FIXME disabled!

        if ($this->_id == '0')
            return false;
        if (!$this->_io->checkObjectPresent($this->_id)
            && !$this->_haveData
        )
            return false;

        // Vérifie si pas déjà protégé.
        if ($this->_getMarkProtected())
            return true;

        $this->_metrology->addLog('Ask protect object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        // Vérifie que l'écriture d'objets et de liens est permise.
        if ($this->_configuration->checkBooleanOptions(array('unlocked','permitWrite','permitWriteObject','permitWriteLink'))) {
            // Génération de la clé de chiffrement.
            // Doit être au maximum de la taille de la clé de l'entité cible (exprimé en bits) moins 11 octets.
            // CF : http://php.net/manual/fr/function.openssl-public-encrypt.php
            // @todo à faire pour le cas général.
            $keySize = self::CRYPTO_SESSION_KEY_SIZE; // En octets.
            $key = $this->_crypto->getRandom($keySize, Crypto::RANDOM_STRONG);
            if (strlen($key) != $keySize)
                return false;
            $keyID = $this->_nebuleInstance->getNIDfromData($key);
            $this->_metrology->addLog('Protect object, key : ' . $keyID, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

            // Si des donnnées sont disponibles, on les lit.
            if ($this->_haveData)
                $data = $this->_data;
            else {
                // Sinon, on lit le contenu de l'objet. @todo à remplacer par getContent...
                $limit = $this->_configuration->getOptionAsInteger('ioReadMaxData');
                $data = $this->_nebuleInstance->getIoInstance()->getObject($this->_id, $limit);

                // Vérification de quota de lecture. @todo à revoir...
                if (strlen($data) >= $limit) {
                    unset($data);
                    $this->_haveData = false;
                    $this->_data = null;
                    return false;
                }
                unset($limit);

                // Vérification de l'empreinte des données. Doit être identique à l'ID.
                // A faire pour le cas général.
                $hash = $this->_nebuleInstance->getNIDfromData($data);
                if ($hash != $this->_id) {
                    unset($data);
                    $this->_haveData = false;
                    $this->_data = null;
                    return false;
                }
                unset($hash);
            }

            // Chiffrement (symétrique) du contenu.
            $code = $this->_crypto->encrypt($data, $this->_configuration->getOptionAsString('cryptoSymmetricAlgorithm'), $key);
            unset($data, $keySize);

            // Vérification de bon chiffrement.
            if ($code == '')
                return false;

            // Chiffrement (asymétrique) de la clé de chiffrement du contenu.
            $codeKey = $this->_crypto->encryptTo($key, $this->_nebuleInstance->getCurrentEntityInstance()->getPublicKey());

            // Vérification de bon chiffrement.
            if ($codeKey === false)
                return false;

            // Ecrit le contenu chiffré.
            $codeInstance = new Node($this->_nebuleInstance, '0');
            $codeInstance->setContent($code, false);
            $codeID = $codeInstance->getID();
            $this->_metrology->addLog('Protect object, code : ' . $codeID, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

            // Vérification de bonne écriture.
            if ($codeID == '0')
                return false;

            // Ecrit la clé de session chiffrée.
            $codeKeyInstance = new Node($this->_nebuleInstance, '0');
            $codeKeyInstance->setContent($codeKey, false);
            $codeKeyID = $codeKeyInstance->getID();
            $this->_metrology->addLog('Protect object, code key : ' . $codeKeyID, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

            // Vérification de bonne écriture.
            if ($codeKeyID == '0')
                return false;

            $signer = $this->_nebuleInstance->getCurrentEntity();

            // Crée le lien de type d'empreinte de la clé.
            $target = $this->_nebuleInstance->getNIDfromData($this->_configuration->getOptionAsString('cryptoHashAlgorithm'));
            $meta = $this->_nebuleInstance->getNIDfromData('nebule/objet/hash');
            $this->_writeLink('l>' . $keyID . '>' . $target . '>' . $meta);

            // Création du type mime des données chiffrées.
            $text = 'application/x-encrypted/' . $this->_configuration->getOptionAsString('cryptoSymmetricAlgorithm');
            $textID = $this->_nebuleInstance->createTextAsObject($text);
            if ($textID != '') {
                // Crée le lien de type d'empreinte.
                $meta = $this->_nebuleInstance->getNIDfromData('nebule/objet/type');
                $this->_writeLink('l>' . $codeID . '>' . $textID . '>' . $meta, $obfuscated);
            }

            // Création du type mime de la clé chiffrée.
            $text = 'application/x-encrypted/' . $this->_configuration->getOptionAsString('cryptoAsymmetricAlgorithm');
            $textID = $this->_nebuleInstance->createTextAsObject($text);
            if ($textID != '') {
                // Crée le lien de type d'empreinte.
                $meta = $this->_nebuleInstance->getNIDfromData('nebule/objet/type');
                $this->_writeLink('l>' . $codeKeyID . '>' . $textID . '>' . $meta, $obfuscated);
            }

            // Création du lien de chiffrement symétrique.
            $this->_writeLink('k>' . $this->_id . '>' . $codeID . '>' . $keyID, $obfuscated);

            // Création du lien de chiffrement asymétrique.
            $this->_writeLink('k>' . $keyID . '>' . $codekeyID . '>' . $signer, $obfuscated);

            // Supprime l'objet qui a été marqué protégé.
            $this->_metrology->addLog('Delete unprotected object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            $deleteObject = true;

            // Création lien.
            $this->_writeLink('d>' . $this->_id);

            // Lit les liens.
            $links = array();
            $this->getLinks($links, array(), false);
            $entity = $this->_nebuleInstance->getCurrentEntity();
            foreach ($links as $link) {
                // Vérifie si l'entité signataire du lien est l'entité courante.
                if ($link->getParsed()['bs/rs1/eid'] != $entity) {
                    // Si ce n'est pas l'entité courante, quitte.
                    $this->_metrology->addAction('delobj', $this->_id, false);
                    $deleteObject = false;
                }
            }

            if ($deleteObject) {
                // Supprime l'objet.
                $r = $this->_io->unsetObject($this->_id);

                // Métrologie.
                $this->_metrology->addAction('delobj', $this->_id, $r);
            }

            // Mémorisation de l'état de protection.
            $this->_markProtectedChecked = true;
            $this->_cacheMarkProtected = true;
            $this->_idProtected = $codeID;
            $this->_idUnprotected = $this->_id;
            $this->_idProtectedKey = $codeKeyID;
            $this->_idUnprotectedKey = $keyID;

            // Si autorisé, partage la protection avec les entités de recouvrement.
            if ($this->_configuration->getOptionAsBoolean('permitRecoveryEntities')) {
                $listEntities = $this->_nebuleInstance->getRecoveryEntitiesInstance();
                foreach ($listEntities as $entity) {
                    if (is_a($entity, 'Entity')
                        && $entity->getID() != '0'
                        && $entity->getIsPublicKey()
                        && $entity != $this->_nebuleInstance->getCurrentEntity()
                    ) {
                        // Chiffrement (asymétrique) de la clé de chiffrement du contenu.
                        $codeKey = $this->_crypto->encryptTo($key, $entity->getPublicKey());

                        // Vérification de bon chiffrement.
                        if ($codeKey === false) {
                            $this->_metrology->addLog('Error (1) share protection to recovery ' . $entity->getID(), Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
                            continue;
                        }

                        // Ecrit la clé de session chiffrée.
                        $codeKeyInstance = new Node($this->_nebuleInstance, '0');
                        $codeKeyInstance->setContent($codeKey, false);
                        $codeKeyID = $codeKeyInstance->getID();
                        $this->_metrology->addLog('Protect object, code key : ' . $codeKeyID, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

                        // Vérification de bonne écriture.
                        if ($codeKeyID == '0') {
                            $this->_metrology->addLog('Error (2) share protection to recovery ' . $entity->getID(), Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
                            continue;
                        }

                        // Création du type mime de la clé chiffrée.
                        $text = 'application/x-encrypted/' . $this->_configuration->getOptionAsString('cryptoAsymmetricAlgorithm');
                        $textID = $this->_nebuleInstance->createTextAsObject($text);
                        if ($textID != '') {
                            // Crée le lien de type d'empreinte.
                            $meta = $this->_nebuleInstance->getNIDfromData('nebule/objet/type');
                            $this->_writeLink('l>' . $codeKeyID . '>' . $textID . '>' . $meta, $obfuscated);
                        }

                        // Création du lien de chiffrement asymétrique.
                        $this->_writeLink('k>' . $keyID . '>' . $codekeyID . '>' . $entity->getID(), $obfuscated);

                        $this->_metrology->addLog('Set protection shared to recovery ' . $entity->getID(), Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                    }
                }
            }

            return true;
        }
        return false;
    }

    /**
     * Déprotège l'objet.
     * @return boolean
     */
    public function setUnprotected(): bool
    {
        return false; // FIXME disabled!

        // Vérifie que l'objet est protégé et que l'on peut y acceder.
        if (!$this->_getMarkProtected()
            || $this->_idProtected == '0'
            || $this->_idUnprotected == '0'
            || $this->_idProtectedKey == '0'
            || $this->_idUnprotectedKey == '0'
        )
            return false;

        $this->_metrology->addLog('Set unprotected ' . $this->_id, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');

        // TODO

        return false;
    }

    /**
     * Protège l'objet pour une entité.
     *
     * L'objet devient illisible en verrouillant l'entité courante !
     *
     * @param $entity string
     * @return boolean
     */
    public function setProtectedTo(string $entity): bool
    {
        return false; // FIXME disabled!

        // TODO
        return false;
    }

    /**
     * Transmet la protection de l'objet à une entité.
     *
     * @param $entity entity|string
     * @return boolean
     */
    public function shareProtectionTo($entity): bool
    {
        return false; // FIXME disabled!

        if (is_string($entity)) {
            $entity = $this->_nebuleInstance->newEntity($entity);
        }
        if (!is_a($entity, 'entity'))
            $entity = $this->_nebuleInstance->newEntity($entity->getID());
        if (!$entity->getIsEntity('all'))
            return false;

        // Vérifie que l'objet est protégé et que l'on peut y acceder.
        if (!$this->_getMarkProtected()
            || $this->_idProtected == '0'
            || $this->_idUnprotected == '0'
            || $this->_idProtectedKey == '0'
            || $this->_idUnprotectedKey == '0'
        )
            return false;

        $this->_metrology->addLog('Set protected to ' . $entity->getID(), Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        // Lit la clé chiffrée. @todo à remplacer par getContent ...
        $limit = $this->_configuration->getOptionUntyped('ioReadMaxData');
        $codeKey = $this->_nebuleInstance->getIoInstance()->getObject($this->_idProtectedKey, $limit);
        // Calcul l'empreinte de la clé chiffrée.
        $hash = $this->_nebuleInstance->getNIDfromData($codeKey);
        if ($hash != $this->_idProtectedKey) {
            $this->_metrology->addLog('Error get protected key content : ' . $this->_idProtectedKey, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            $this->_metrology->addLog('Protected key content hash : ' . $hash, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            return false;
        }

        // Déchiffrement (asymétrique) de la clé de chiffrement du contenu.
        $key = $this->_nebuleInstance->getCurrentEntityInstance()->decrypt($codeKey);
        // Calcul l'empreinte de la clé.
        $hash = $this->_nebuleInstance->getNIDfromData($key);
        if ($hash != $this->_idUnprotectedKey) {
            $this->_metrology->addLog('Error get unprotected key content : ' . $this->_idUnprotectedKey, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            $this->_metrology->addLog('Unprotected key content hash : ' . $hash, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            return false;
        }

        // Chiffrement (asymétrique) de la clé de chiffrement du contenu.
        $codeKey = $this->_crypto->encryptTo($key, $entity->getPublicKey());

        // Vérification de bon chiffrement.
        if ($codeKey == '')
            return false;

        // Ecrit la clé chiffrée.
        $codeKeyInstance = new Node($this->_nebuleInstance, '0');
        $codeKeyInstance->setContent($codeKey, false);
        $codeKeyID = $codeKeyInstance->getID();
        $this->_metrology->addLog('Protect object, code key : ' . $codeKeyID, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');

        // Vérification de bonne écriture.
        if ($codeKeyID == '0')
            return false;

        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);

        // Création du type mime de la clé chiffrée.
        $text = 'application/x-encrypted/' . $this->_configuration->getOptionAsString('cryptoAsymmetricAlgorithm');
        $textID = $this->_nebuleInstance->createTextAsObject($text);
        if ($textID != '') {
            // Crée le lien de type d'empreinte.
            $action = 'l';
            $source = $codeKeyID;
            $target = $textID;
            $meta = $this->_nebuleInstance->getNIDfromData('nebule/objet/type');
            $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new BlocLink($this->_nebuleInstance, $link);
            // Signe le lien.
            $newLink->sign();
            // Ecrit le lien.
            $newLink->write();
        }

        // Création du lien de chiffrement asymétrique.
        $action = 'k';
        $source = $this->_idUnprotectedKey;
        $target = $codeKeyID;
        $meta = $entity->getID();
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        // Signe le lien.
        $newLink->sign();
        // Ecrit le lien.
        $newLink->write();

        return true;
    }

    /**
     * Transmet l'annulation de la protection de l'objet à une entité.
     * Ne marche pas si il y a eu plusieurs protections/déprotections/protections/... !!!
     * @param $entity entity|string
     * @return boolean
     * @todo
     *
     */
    public function cancelShareProtectionTo($entity): bool
    {
        return false; // FIXME disabled!

        if (is_string($entity))
            $entity = $this->_nebuleInstance->newEntity($entity);
        if (!is_a($entity, 'entity'))
            $entity = $this->_nebuleInstance->newEntity($entity->getID());
        if (!$entity->getIsEntity('all'))
            return false;

        // Vérifie que l'objet est protégé et que l'on peut y acceder.
        if (!$this->_getMarkProtected())
            return false;
        if ($this->_idProtected == '0')
            return false;
        if ($this->_idUnprotected == '0')
            return false;
        if ($this->_idProtectedKey == '0')
            return false;
        if ($this->_idUnprotectedKey == '0')
            return false;

        // Vérifie que la protection n'est pas partagée à une entité de recouvrement.
        if (!$this->_configuration->getOptionAsBoolean('permitRecoveryRemoveEntity')
            && $this->_nebuleInstance->getIsRecoveryEntity($entity->getID())
        )
            return false;

        $this->_metrology->addLog('Cancel share protection to ' . $entity->getID(), Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');

        // Recherche l'objet de clé de chiffrement pour l'entité.
        $links = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid1' => $this->_idUnprotectedKey,
            'bl/rl/nid3' => $entity->getID(),
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, false);

        if (sizeof($links) == 0)
            return true;

        foreach ($links as $item) {
            $idKey = $item->getParsed()['bl/rl/nid1'];
            $idProtectedKey = $item->getParsed()['bl/rl/nid2'];
            if ($idKey != '0' && $idProtectedKey != '0') {
                // Création du lien d'annulation de chiffrement asymétrique.
                $signer = $this->_nebuleInstance->getCurrentEntity();
                $date = date(DATE_ATOM);
                $action = 'x';
                $source = $idKey;
                $target = $idProtectedKey;
                $meta = $entity->getID();
                $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                $newLink = new Link($this->_nebuleInstance, $link);
                // Signe le lien.
                $newLink->sign();
                // Ecrit le lien.
                $newLink->write();

                // Suppression de la clé de chiffrement protégée.
                $object = $this->_nebuleInstance->newObject($idProtectedKey);
                //$object->deleteObject();
                $signerLinks = array();
                $this->getLinks($signerLinks, array(), false);
                $delete = true;
                foreach ($signerLinks as $itemSigner) {
                    // Si un lien a été généré par une autre entité, c'est que l'objet est encore utilisé.
                    if ($itemSigner->getParsed()['bs/rs1/eid'] != $signer
                        && $itemSigner->getParsed()['bs/rs1/eid'] != $this->_nebuleInstance->getCurrentEntity()
                    )
                        $delete = false;
                }
                if ($delete)
                    $this->_io->unsetObject($idProtectedKey);
                unset($object, $signerLinks, $itemSigner, $delete);
            }
        }
        unset($links);

        return true;
    }


    /**
     * Lit une émotion pour l'objet.
     * La sélection d'une classe sociale particulière permet de faire un filtre sur la recherche.
     * Les classes sociales intéressantes :
     *  - self : mes émotions sur l'objet ;
     *  - notself : les émotions de toutes les entités sauf moi sur l'objet ;
     *  - all : les émotions de toutes les entités sur l'objet.
     *
     * @param string      $emotion
     * @param string      $socialClass
     * @param string $context
     * @return boolean
     */
    public function getMarkEmotion(string $emotion, string $socialClass = '', string $context = ''): bool
    {
        if ($this->getMarkEmotionSize($emotion, $socialClass, $context) == 0)
            return false;
        return true;
    }

    /**
     * Lit la liste des entités qui ont marqué une émotion pour l'objet.
     * TODO hash alternatifs.
     * Par défaut, le contexte de recherche est vide.
     * Dans ce cas, on ne garde que les liens avec comme contexte l'entité qui a signé le lien.
     *
     * @param string $emotion
     * @param string $socialClass
     * @param string $context
     * @return array:Link
     */
    public function getMarkEmotionList(string $emotion, string $socialClass = '', string $context = '')
    {
        $list = array();

        // Nettoyage du contexte.
        if (is_a($context, 'entity')
            || is_a($context, 'Node')
            || is_a($context, 'group')
            || is_a($context, 'conversation')
        )
            $context = $context->getID();
        if (!is_string($context)
            || $context == '0'
            || !ctype_xdigit($context)
        )
            $context = '';

        // Vérifie que l'émotion existe.
        if ($emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET
        )
            return $list;

        $hashEmotion = $this->_nebuleInstance->getNIDfromData($emotion);

        // Liste les liens à la recherche de la propriété.
        $list = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid2' => $hashEmotion,
            'bl/rl/nid3' => $context,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, false);

        // Nettoyage.
        foreach ($list as $i => $link) {
            // Si méta à 0, supprime le lien.
            if ($link->getParsed()['bl/rl/nid3'] == '0')
                unset($list[$i]);
        }
        unset($link);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_configuration->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($list, $socialClass);

        return $list;
    }

    /**
     * Lit le nombre d'entités qui ont marqué une émotion pour l'objet.
     *
     * @param string $emotion
     * @param string $socialClass
     * @param string $context
     * @return int
     */
    public function getMarkEmotionSize(string $emotion, string $socialClass = '', string $context = ''): int
    {
        $list = $this->getMarkEmotionList($emotion, $socialClass, $context);
        return sizeof($list);
    }

    /**
     * Lit toutes les émotions pour l'objet.
     *
     * @param string $socialClass
     * @param string $context
     * @return array:string
     */
    public function getMarkEmotions(string $socialClass = '', string $context = ''): array
    {
        $result = array();

        $result[nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE] = $this->getMarkEmotion(nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE, $socialClass, $context);
        $result[nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE] = $this->getMarkEmotion(nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE, $socialClass, $context);
        $result[nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR] = $this->getMarkEmotion(nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR, $socialClass, $context);
        $result[nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE] = $this->getMarkEmotion(nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE, $socialClass, $context);
        $result[nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE] = $this->getMarkEmotion(nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE, $socialClass, $context);
        $result[nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT] = $this->getMarkEmotion(nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT, $socialClass, $context);
        $result[nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE] = $this->getMarkEmotion(nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE, $socialClass, $context);
        $result[nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET] = $this->getMarkEmotion(nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET, $socialClass, $context);

        return $result;
    }

    /**
     * Ecrit une émotion pour l'objet.
     * La marque d'un émotion sur l'objet est un lien de type f avec :
     *  - source : l'ID de l'objet
     *  - cible : le hash de l'émotion
     *  - méta : le signataire ou l'objet de contexte (conversation par exemple)
     * Le lien peut être dissimulé.
     * L'émotion peut être rattachée en contexte à
     *  - une autre entité @param string $emotion
     *
     * TODO à revoir...
     *  - ou un objet particulier
     * Cela permet par défaut de discriminer précisément lorsque l'émotion concerne l'objet
     *   ou si l'émotion se réfère à un contexte particulier de l'objet comme une conversation.
     * Par défaut le contexte est l'entité en cours, l'émotion est attaché à cet objet directement.
     *
     * @param boolean $obfuscate
     * @param string  $context
     * @return boolean
     */
    public function setMarkEmotion(string $emotion, bool $obfuscate = false, string $context = ''): bool
    {
        // Vérifie que l'émotion existe.
        if ($emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET
        ) {
            return false;
        }

        // Nettoyage du contexte.
        if (is_a($context, 'entity')
            || is_a($context, 'Node')
            || is_a($context, 'group')
            || is_a($context, 'conversation')
        ) {
            $context = $context->getID();
        }
        if (!is_string($context)
            || $context == '0'
            || $context == ''
            || !ctype_xdigit($context)
        ) {
            $context = $this->_nebuleInstance->getCurrentEntity();
        }

        // Création du lien.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'f';
        $source = $this->_id;
        $target = $this->_nebuleInstance->getNIDfromData($emotion);
        $meta = $context;
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();
        if ($obfuscate)
            $newLink->setObfuscate();
        $newLink->write();

        return true;
    }

    /**
     * Supprime une émotion pour l'objet.
     * Le lien de suppression peut être dissimulé et ainsi laisser publique l'émotion.
     * L'émotion peut être rattachée à une autre entité.
     *
     * @param string  $emotion
     * @param boolean $obfuscate
     * @param string  $entity
     * @return boolean
     */
    public function unsetMarkEmotion(string $emotion, bool $obfuscate = false, string $entity = ''): bool
    {
        // Vérifie que l'émotion existe.
        if ($emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE
            && $emotion != nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET
        )
            return false;

        // Nettoyage de l'entité demandé.
        if (is_a($entity, 'entity')
            || is_a($entity, 'Node')
            || is_a($entity, 'group')
            || is_a($entity, 'conversation')
        )
            $entity = $entity->getID();
        if (!is_string($entity)
            || $entity == '0'
            || $entity == ''
            || !ctype_xdigit($entity)
        )
            $entity = $this->_nebuleInstance->getCurrentEntity();

        // Création du lien.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'x';
        $source = $this->_id;
        $target = $this->_nebuleInstance->getNIDfromData($emotion);
        $meta = $entity;
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();
        if ($obfuscate)
            $newLink->setObfuscate();
        $newLink->write();

        return true;
    }


    /**
     * Lit à quelles entités à été transmis la protection de l'objet.
     *
     * @return array:string
     */
    public function getProtectedTo(): array
    {
        return array(); // FIXME disabled!

        $result = array();
        if (!$this->_getMarkProtected()) {
            return $result;
        }

        // Lit les liens de chiffrement de l'objet, chiffrement symétrique.
        $linksSym = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid1' => $this->_idUnprotected,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($linksSym, $filter, false);
        foreach ($linksSym as $linkSym) {
            // Si lien de chiffrement.
            if ($linkSym->getParsed()['bl/rl/nid3'] != '0') {
                // Lit l'objet de clé de chiffrement symétrique et ses liens.
                $instanceSym = $this->_nebuleInstance->newObject($linkSym->getParsed()['bl/rl/nid3']);
                $linksAsym = array();
                $filter = array(
                    'bl/rl/req' => 'k',
                    'bl/rl/nid1' => $linkSym->getParsed()['bl/rl/nid3'],
                    'bl/rl/nid4' => '',
                );
                $this->getLinks($linksAsym, $filter, false);
                unset($instanceSym);
                foreach ($linksAsym as $linkAsym) {
                    // Si lien de chiffrement.
                    if ($linkAsym->getParsed()['bl/rl/nid3'] != '0')
                        $result[] = $linkAsym->getParsed()['bl/rl/nid3'];
                }
                unset($linksAsym, $linkAsym);
            }
        }
        unset($linksSym, $linkSym);
        return $result;
    }


    /**
     * Vérifie la consistance de l'objet.
     *
     * Retourne true  si l'objet a déjà été vérifié.
     * Retourne false si l'objet n'est pas présent.
     * Retourne false si la fonction de hash n'est pas reconnue ou invalide, l'objet n'est pas vérifié.
     * Retourne false si l'extraction de l'objet échoue, l'objet n'est pas vérifié.
     * Retourne false si l'objet n'est pas valide, il est supprimé.
     * Retourne true  si l'empreinte de l'objet est valide.
     *
     * En cas de type de hash inconnu ou invalide, et si l'option permitDeleteObjectOnUnknownHash est à true,
     *   choisi l'algorithme de hash par défaut comme dernière chance.
     *   Si l'empreinte ne correspond pas, l'objet sera supprimé.
     *
     * @return boolean
     */
    public function checkConsistency(): bool
    {
        if ($this->_haveData)
            return true;

        if (!$this->_io->checkObjectPresent($this->_id))
            return false;

        // Si c'est l'objet 0, le supprime.
        if ($this->_id == '0' || !$this->checkNID($this->_id)) {
            $this->_data = null;
            $this->_metrology->addLog('Delete object 0', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            $nid = '0';
            $this->_io->unsetObject($nid);
            return false;
        }

        if ($this->_configuration->getOptionAsBoolean('permitSynchronizeLink'))
            $this->syncLinks(false);

        if ($this->_configuration->getOptionAsBoolean('permitDeleteObjectOnUnknownHash'))
            $hashAlgo = $this->_configuration->getOptionAsString('cryptoHashAlgorithm');
        else
            return false;

        // Extrait le contenu de l'objet, si possible.
        $this->_metrology->addObjectRead(); // Metrologie.
        $this->_data = $this->_io->getObject($this->_id);
        if ($this->_data === false) {
            $this->_metrology->addLog('Cant read object ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            $this->_data = null;
            return false;
        }
        $limit = $this->_configuration->getOptionUntyped('DEFAULT_IO_READ_MAX_DATA');
        $this->_metrology->addLog('Object size ' . $this->_id . ' ' . strlen($this->_data) . '/' . $limit, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        // Vérifie la taille.
        if (strlen($this->_data) > $limit) {
            $this->_data = null;
            return false;
        }

        // Calcul l'empreinte.
        $hash = $this->_nebuleInstance->getNIDfromData($this->_data, $hashAlgo);
        if ($hash == $this->_id) // Si l'objet est valide.
        {
            $this->_metrology->addObjectVerify(); // Metrologie.
            $this->_haveData = true;
            return true;
        }

        // Si la vérification est désactivée, quitte.
        if (!$this->_configuration->getOptionAsBoolean('permitCheckObjectHash')) {
            $this->_metrology->addLog('Warning - Invalid object hash ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            $this->_haveData = true;
            return true;
        }

        // Sinon l'objet est présent mais invalide, le supprime.
        $this->_data = null;
        $this->_metrology->addLog('Delete unconsistency object ' . $this->_id . ' ' . $hashAlgo . ':' . $hash, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        $this->_io->unsetObject($this->_id);
        return false;
    }

    /**
     * Lit le contenu de l'objet.
     * Retourne une chaine vide si l'empreinte des données diffère de l'ID.
     *
     * @param integer $limit limite de lecture du contenu de l'objet.
     * @return string
     */
    public function getContent(int $limit = 0): ?string
    {
        if ($this->_haveData)
            return $this->_data;

        /*if ($this->_getMarkProtected())
            return $this->_getProtectedContent($limit);
        else*/
            return $this->_getUnprotectedContent($limit);
    }

    /**
     * Lit le contenu de l'objet sans essayer de le déchiffrer.
     * Retourne une chaine vide si l'empreinte des données diffère de l'ID.
     *
     * @param integer $limit limite de lecture du contenu de l'objet.
     * @return string
     */
    public function getContentAsUnprotected(int $limit = 0): ?string
    {
        return $this->_getUnprotectedContent($limit);
    }

    /**
     * Lit sans déchiffrer un contenu (non protégé).
     *
     * @param integer $limit limite de lecture du contenu de l'objet.
     * @return string|null
     */
    protected function _getUnprotectedContent(int $limit = 0): ?string
    {
        if ($this->_haveData)
            return $this->_data;

        if (!$this->_io->checkObjectPresent($this->_id))
            return null;

        // Si c'est l'objet 0, le supprime.
        if ($this->_id == '0' || !$this->checkNID($this->_id)) {
            $this->_data = null;
            $this->_metrology->addLog('Delete object 0', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, 'f8873320');
            $nid = '0';
            $this->_io->unsetObject($nid);
            return null;
        }

        if ($this->_configuration->getOptionAsBoolean('permitSynchronizeLink'))
            $this->syncLinks(false);

        if ($this->_configuration->getOptionAsBoolean('permitDeleteObjectOnUnknownHash'))
            $hashAlgo = $this->_configuration->getOptionAsString('cryptoHashAlgorithm');
        else
            return null;

        // Prépare la limite de lecture.
        $maxLimit = $this->_configuration->getOptionUntyped('ioReadMaxData');
        if ($limit == 0
            || $limit > $maxLimit
        )
            $limit = $maxLimit;

        // Extrait le contenu de l'objet, si possible.
        $this->_metrology->addObjectRead(); // Metrologie.
        $this->_data = $this->_io->getObject($this->_id, $limit);
        if ($this->_data === false) {
            $this->_metrology->addLog('Cant read object ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            $this->_data = null;
            return null;
        }
        $this->_metrology->addLog('Object read size ' . $this->_id . ' ' . strlen($this->_data) . '/' . $maxLimit, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        // Vérifie la taille. Si trop grand mais qu'une limite est imposé, quitte sans vérifier l'empreinte.
        if (strlen($this->_data) >= $limit
            && $limit < $maxLimit
        ) {
            $this->_data = null;
            return null;
        }

        // Calcul l'empreinte.
        $hash = $this->_nebuleInstance->getNIDfromData($this->_data, $hashAlgo);
        if ($hash == $this->_id) // Si l'objet est valide.
        {
            $this->_metrology->addObjectVerify(); // Metrologie.
            $this->_haveData = true;
            return $this->_data;
        }

        // Si la vérification est désactivée, quitte.
        if (!$this->_configuration->getOptionAsBoolean('permitCheckObjectHash')) {
            $this->_metrology->addLog('Warning - Invalid object hash ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            $this->_haveData = true;
            return $this->_data;
        }

        // Sinon l'objet est présent mais invalide, le supprime.
        $this->_data = null;
        $this->_metrology->addLog('Delete unconsistency object ' . $this->_id . ' ' . $hashAlgo . ':' . $hash, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        $this->_io->unsetObject($this->_id);
        return null;
    }

    /**
     * Lit et déchiffre un contenu protégé.
     *
     * @param integer $limit          limite de lecture du contenu de l'objet.
     * @return string|null
     * @todo à revoir en entier !
     */
    protected function _getProtectedContent(int $limit = 0): ?string
    {
        return null; // FIXME disabled!

        if ($this->_haveData) {
            return $this->_data;
        }

        // Si non protégé, retourne le contenu de l'objet.
        if (!$this->_getMarkProtected()
            || $this->_idProtected == '0'
            || $this->_idUnprotected == '0'
            || $this->_idProtectedKey == '0'
            || $this->_idUnprotectedKey == '0'
        ) {
            return $this->_getUnprotectedContent($limit);
        }

//		if ( $limit == 0 )
//			$limit = $this->_configuration->getOption('ioReadMaxData');

        $permitTroncate = false; // @todo à retirer.

        $this->_metrology->addLog('Get protected content : ' . $this->_idUnprotected, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        // Lit la clé chiffrée.
        $codeKey = $this->_io->getObject($this->_idProtectedKey, 0);
        // Calcul l'empreinte de la clé chiffrée.
        $hash = $this->_nebuleInstance->getNIDfromData($codeKey);
        if ($hash != $this->_idProtectedKey) {
            $this->_metrology->addLog('Error get protected key content : ' . $this->_idProtectedKey, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            $this->_metrology->addLog('Protected key content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            return null;
        }

        // Déchiffrement (asymétrique) de la clé de chiffrement du contenu.
        $key = $this->_nebuleInstance->getCurrentEntityInstance()->decrypt($codeKey);
        // Calcul l'empreinte de la clé.
        $hash = $this->_nebuleInstance->getNIDfromData($key);
        if ($hash != $this->_idUnprotectedKey) {
            $this->_metrology->addLog('Error get unprotected key content : ' . $this->_idUnprotectedKey, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            $this->_metrology->addLog('Unprotected key content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            return null;
        }

        // Lit l'objet chiffré.
        $code = $this->_io->getObject($this->_idProtected, $limit);
        // Calcul l'empreinte des données.
        $hash = $this->_nebuleInstance->getNIDfromData($code);
        if ($hash != $this->_idProtected) {
            $this->_metrology->addLog('Error get protected data content : ' . $this->_idProtected, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            $this->_metrology->addLog('Protected data content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            return null;
        }

        $data = $this->_crypto->decrypt($code, $this->_configuration->getOptionAsString('cryptoSymmetricAlgorithm'), $key);
        // Calcul l'empreinte des données.
        $hash = $this->_nebuleInstance->getNIDfromData($data);
        if ($hash != $this->_idUnprotected) {
            $this->_metrology->addLog('Error get unprotected data content : ' . $this->_idUnprotected, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            $this->_metrology->addLog('Unprotected data content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            return null;
        }

        unset($code, $key, $codeKey, $hash);
        return $data;
    }

    /**
     * Vide le cache de données du contenu de l'objet.
     * Ca ne supprime pas l'objet ou son contenu mais juste la copie du contenu en mémoire.
     *
     * Cette fonction peut être utilisé lorsque l'on veut réduire la mémoire utilisée quand on manipule beaucoup d'objets un peu volumineux.
     *
     * @return void
     */
    public function flushDataCache(): void
    {
        $this->_data = null;
        $this->_haveData = false;
        $this->_code = null;
        $this->_haveCode = false;
    }

    /**
     * Lit la première ligne du contenu d'un (autre) objet nebule, extrait une chaine de texte imprimable.
     *
     * @param string $id
     * @return string
     */
    protected function _readOneLineOtherObject(string $id): string
    {
        if ($id == ''
            || !$this->_io->checkObjectPresent($id)
        ) {
            return '';
        }

        $instance = $this->_nebuleInstance->newObject($id);
        $text = substr(trim(strtok(filter_var($instance->getContent(0), FILTER_SANITIZE_STRING), "\n")), 0, 1024);
        if (extension_loaded('mbstring'))
            $text = mb_convert_encoding($text, 'UTF-8');
        else
            $this->_metrology->addLog('mbstring extension not installed or activated!', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'c2becfad');

        unset($instance);
        return $text;
    }

    /**
     * Lit la première ligne du contenu de l'objet nebule, extrait une chaine de texte imprimable.
     *
     * @param integer $limit
     * @return string
     */
    public function readOneLineAsText(int $limit = 0): string
    {
        if (!$this->_io->checkObjectPresent($this->_id)) {
            return '';
        }

        if ($limit == 0) {
            $limit = $this->_configuration->getOptionUntyped('ioReadMaxData');
        }
        if ($limit < 4) {
            $limit = 4;
        }

        $text = trim(strtok(filter_var($this->getContent($limit), FILTER_SANITIZE_STRING), "\n"));
        if (extension_loaded('mbstring'))
            $text = mb_convert_encoding($text, 'UTF-8');
        else
            $this->_metrology->addLog('mbstring extension not installed or activated!', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'c2becfad');

        if (strlen($text) > $limit) {
            $text = substr($text, 0, ($limit - 3)) . '...';
        }

        return $text;
    }

    /**
     * Lit le contenu de l'objet nebule, extrait une chaine de texte imprimable.
     *
     * @param integer $limit
     * @return string
     */
    public function readAsText(int $limit = 0): string
    {
        if (!$this->_io->checkObjectPresent($this->_id)) {
            return '';
        }
        if ($limit < 4) {
            $limit = 4;
        }

        $text = trim(filter_var($this->getContent($limit + 4), FILTER_SANITIZE_STRING));
        if (extension_loaded('mbstring'))
            $text = mb_convert_encoding($text, 'UTF-8');
        else
            $this->_metrology->addLog('mbstring extension not installed or activated!', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'c2becfad');

        if (strlen($text) > $limit) {
            $text = substr($text, 0, ($limit - 3)) . '...';
        }
        return $text;
    }

    /**
     * Link - Read links, parse and filter each links.
     *
     * @param array  $links
     * @param array  $filter
     * @param bool   $withInvalidLinks
     * @return void
     */
    public function getLinks(array &$links, array $filter, bool $withInvalidLinks = false): void
    {
        if ($this->_id == '0'
            || !$this->_io->checkLinkPresent($this->_id)
        )
            return;

        if (!$this->_configuration->getOptionAsBoolean('permitListInvalidLinks'))
            $withInvalidLinks = false;

        $lines = $this->_io->getLinks($this->_id, '', 0);
        if (sizeof($lines) == 0)
            return;

        foreach ($lines as $line)
        {
            $bloc = $this->_cache->newLink($line, Cache::TYPE_LINK);
            if ($bloc->getValidStructure()
                && ( $bloc->getValid() || $withInvalidLinks )
            )
            {
                $newLinks = $bloc->getLinks();
                $this->_filterLinksByStructure($newLinks, $filter);
                $links = array_merge($links, $newLinks);
            }
            else
                $this->_cache->unsetCache($line, Cache::TYPE_BLOCLINK);
        }
    }

    protected function _filterLinksByStructure(array &$links, array $filter): void
    {
        foreach ($links as $i => $link)
        {
            if (!$this->_filterLinkByStructure($link, $filter))
                unset($links[$i]);
        }
    }

    /**
     * Test if a link match a filter.
     * Filtering on have bl/rl/req, bl/rl/nid1, bl/rl/nid2, bl/rl/nid3, bl/rl/nid4, bl/rl/nid*, bs/rs1/eid, or not have.
     * TODO revoir pour les liens de type x...
     *
     * @param Link  $link
     * @param array $filter
     * @return bool
     */
    protected function _filterLinkByStructure(Link &$link, array $filter): bool
    {
        $parsedLink = $link->getParsed();

        foreach ($filter as $n => $f)
        {
            $a = $f;
            if (is_string($f))
                $a = array($f);
            foreach ($a as $v)
            {
                if (isset($parsedLink[$n]) && ($parsedLink[$n] != $v || $v == ''))
                    return false;
            }
        }
        return true;
    }


    /**
     * Retourne un tableau d'objets de type Link ou un tableau vide si ça se passe mal.
     * Un filtre simple est réalisé lors de l'extraction des liens.
     *
     * @param string $eid
     * @param string $chr
     * @param string $req
     * @param string $nid1
     * @param string $nid2
     * @param string $nid3
     * @param string $nid4
     * @return array:Link
     */
    public function getLinksOnFields(string $eid = '', string $chr = '', string $req = '', string $nid1 = '', string $nid2 = '', string $nid3 = '', string $nid4 = ''): array
    {
        if (!$this->_io->checkLinkPresent($this->_id))
            return array();

        $links = array();
        $filter = array( 'bl/rl/nid4' => $nid4, );

        if ($eid != '')
            $filter['bs/rs1/eid'] = $eid;
        if ($chr != '')
            $filter['bl/rc/chr'] = $chr;
        if ($req != '')
            $filter['bl/rl/req'] = $req;
        if ($nid1 != '')
            $filter['bl/rl/nid1'] = $nid1;
        if ($nid2 != '')
            $filter['bl/rl/nid2'] = $nid2;
        if ($nid3 != '')
            $filter['bl/rl/nid3'] = $nid3;

        $this->getLinks($links, $filter, false);
        return $links;
    }

    /**
     * Try to find an update NID for the current NID.
     *
     * @param boolean $present
     * @param boolean $synchro
     * @param string  $social
     * @return string
     */
    public function getUpdateNID(bool $present = true, bool $synchro = false, string $social = ''): string
    {
        $exclude = array();
        $oneLevelUpdate = $this->_findUpdate($this->_id, 0, $exclude, $social, $present, $synchro);
        if ($oneLevelUpdate != '')
            return $oneLevelUpdate;
        return '';
    }

    /**
     * Try to find a new level of update for an object.
     * Internal function which try to :
     * 1) If max update following is not reached, find list of update links ;
     * 2) Recall the function with each element of the list to search for deepest update, not empty ;
     * 3) If no deeper update found, check current NID validity. If OK, return NID, or return empty.
     *
     * @param string $nid
     * @param int    $level
     * @param array  $exclude
     * @param string $social
     * @param bool   $present
     * @param bool   $synchro
     * @return string
     */
    private function _findUpdate(string $nid, int $level, array &$exclude, string $social, bool $present = true, bool $synchro = false): string
    {
        $level++;
        $links = array();
        if ($level < $this->_configuration->getOptionAsInteger('linkMaxFollowedUpdates'))
        {
            $filter = array(
                'bl/rl/req' => 'u',
                'bl/rl/nid1' => $nid,
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
            );
            $this->getLinks($links, $filter, false);
            $this->_social->arraySocialFilter($links, $social);
            $this->_arrayDateSort($links);
        }

        foreach ($links as $link)
        {
            $nid2 = $link->getParsed()['bl/rl/nid2'];
            $nid2Update = '';
            if (!isset($exclude[$nid2]))
                $nid2Update = $this->_findUpdate($nid2, $level, $exclude, $social, $present, $synchro);
            if ($nid2Update != '')
                return $nid2Update;
            $exclude[$nid2] = null;
        }

        if (!$present || $this->_io->checkObjectPresent($nid))
            return $nid;
        elseif ($synchro) {
            $instance = $this->_cache->newNode($nid);
            $instance->syncObject();
            if ($this->_io->checkObjectPresent($nid))
                return $nid;
        }
        return '';
    }



    /**
     * Liste les liens des objets par référence, référencés par l'objet instancié.
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @return array:string
     */
    private function _getReferencedByLinks(string $reference = ''): array
    {
        // Si pas de référence, utilise la référence par défaut.
        if ($reference == '')
            $reference = nebule::REFERENCE_NEBULE_REFERENCE;

        if (!self::checkNID($reference))
            $reference = $this->_nebuleInstance->getNIDfromData($reference);

        $list = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid3' => $reference,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, false);

        // Liste les liens à la recherche de la propriété.
        $list = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid3' => $reference,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, false);

        return $list;
    }

    /**
     * Liste les liens des objets par référence, les objets qui référencent l'objet instancié.
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @return array:string
     */
    private function _getReferenceToLinks(string $reference = ''): array
    {
        // Si pas de référence, utilise la référence par défaut.
        if ($reference == '')
            $reference = nebule::REFERENCE_NEBULE_REFERENCE;

        // Converti au besoin en hash.
        if (!ctype_xdigit($reference))
            $reference = $this->_nebuleInstance->getNIDfromData($reference);

        // Liste les liens à la recherche de la propriété.
        $list = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid3' => $reference,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, false);

        return $list;
    }

    /**
     * Cherche l'ID de l'objet par référence.
     * Si pas trouvé, retourne l'ID de l'objet sur lequel s'effectue la recherche.
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return string
     */
    public function getReferencedObjectID(string $reference = '', string $socialClass = ''): string
    {
        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferencedByLinks($reference);

        if (sizeof($links) == 0)
            return $this->_id;

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Extrait le dernier de la liste.
        $link = end($links);
        unset($links);

        if (!is_a($link, 'link'))
            return $this->_id;

        return $link->getParsed()['bl/rl/nid2'];
    }

    /**
     * Cherche l'ID du signataire de l'objet par référence.
     * Si pas trouvé, retourne 0.
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return string
     */
    public function getReferencedSignerID(string $reference = '', string $socialClass = ''): string
    {
        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferencedByLinks($reference);

        if (sizeof($links) == 0)
            return '0';

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        /**
         * Extrait le dernier de la liste.
         *
         * @var Link
         */
        $link = end($links);
        unset($links);

        if (!is_a($link, 'link'))
            return '0';

        return $link->getParsed()['bs/rs1/eid'];
    }

    /**
     * Cherche la liste des ID des signataires de l'objet par référence.
     * Si pas trouvé, retourne 0.
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return array:string
     */
    public function getReferencedListSignersID(string $reference = '', string $socialClass = ''): array
    {
        $list = array();

        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferencedByLinks($reference);

        if (sizeof($links) == 0)
            return $list;

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Extrait les signataires de la liste.
        $listOK = array();
        foreach ($links as $link) {
            if (!isset($listOK[$link->getParsed()['bs/rs1/eid']])) {
                $list[] = $link->getParsed()['bs/rs1/eid'];
                $listOK[$link->getParsed()['bs/rs1/eid']] = true;
            }
        }
        unset($links, $listOK);

        return $list;
    }

    /**
     * Cherche l'instance de l'objet par référence.
     * Si pas trouvé, retourne l'instance de l'objet sur lequel s'effectue la recherche.
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return Node
     */
    public function getReferencedObjectInstance(string $reference, string $socialClass = ''): Node
    {
        return $this->_nebuleInstance->convertIdToTypedObjectInstance($this->getReferencedObjectID($reference, $socialClass));
    }

    /**
     * Cherche si l'objet est une référence.
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     * Les références sont converties en hash en hexadécimal.
     * Si la référence est un texte en hexadécimal, c'est à dire un ID d'objet, alors c'est utilisé directement.
     *
     * @param string $reference
     * @param string $socialClass
     * @return boolean
     */
    public function getIsReferencedBy(string $reference = '', string $socialClass = ''): bool
    {
        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferencedByLinks($reference);

        if (sizeof($links) == 0)
            return false;

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        if (sizeof($links) == 0)
            return false;
        return true;
    }

    /**
     * Cherche si l'objet est référencé par une autre objet.
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     * Les références sont converties en hash en hexadécimal.
     * Si la référence est un texte en hexadécimal, c'est à dire un ID d'objet, alors c'est utilisé directement.
     *
     * @param string $reference
     * @param string $socialClass
     * @return boolean
     */
    public function getIsReferenceTo(string $reference = '', string $socialClass = ''): bool
    {
        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferenceToLinks($reference);

        if (sizeof($links) == 0)
            return false;

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        if (sizeof($links) == 0)
            return false;
        return true;
    }

    /**
     * Cherche l'ID de l'objet qui référence l'objet courant.
     * Si pas trouvé, retourne l'ID de l'objet sur lequel s'effectue la recherche.
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return string
     */
    public function getReferenceToObjectID(string $reference = '', string $socialClass = ''): string
    {
        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferenceToLinks($reference);

        if (sizeof($links) == 0)
            return $this->_id;

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Extrait le dernier de la liste.
        $link = end($links);
        unset($links);

        if (!is_a($link, 'link'))
            return $this->_id;

        return $link->getParsed()['bl/rl/nid1'];
    }


    /**
     * Synchronisation de l'objet.
     *
     * @param boolean $hardSync
     * @return boolean
     * @todo
     */
    public function syncObject(bool $hardSync = false): bool
    {
        if ($hardSync !== true)
            $hardSync = false;

        // Vérifie que l'objet ne soit pas déjà présent.
        if ($this->_io->checkObjectPresent($this->_id))
            return true;

        // Vérifie si autorisé.
        if (!$this->_configuration->checkBooleanOptions(array('permitWriteObject','permitSynchronizeObject')))
            return false;

        // Liste les liens à la recherche de la propriété de localisation.
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' =>  $this->_nebuleInstance->getNIDfromData(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION),
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, false);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($links) == 0
            && $this->_configuration->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }
        if (sizeof($links) == 0)
            return false;

        // Fait un tri par pertinance sociale.
        // A faire...

        // Extrait le contenu des objets de propriété.
        foreach ($links as $i => $l) {
            $localisations[$i] = $this->_readOneLineOtherObject($l->getParsed()['bl/rl/nid2']);
        }

        // Synchronisation
        foreach ($localisations as $localisation) {
            // Lecture de l'objet.
            $data = $this->_io->getObject($this->_id, 0, $localisation);
            // Ecriture de l'objet.
            $this->_io->setObject($this->_id, $data);
        }

        unset($localisations, $localisation);
        return true;
    }

    /**
     * Synchronisation des liens de l'objet.
     *
     * @param boolean $hardSync
     * @return boolean
     * @todo
     */
    public function syncLinks(bool $hardSync = false): bool
    {
        if ($hardSync !== true)
            $hardSync = false;

        // Vérifie si autorisé.
        if (!$this->_configuration->checkBooleanOptions(array('permitWriteLink','permitSynchronizeLink')))
            return false;

        // Liste les liens à la recherche de la propriété de localisation.
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_nebuleInstance->getNIDfromData('nebule/objet/entite/localisation'),
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, false);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($links) == 0
            && $this->_configuration->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }
        if (sizeof($links) == 0)
            return false;

        // Fait un tri par pertinance sociale.
        // A faire...

        // Extrait le contenu des objets de propriété.
        foreach ($links as $i => $l)
            $localisations[$i] = $this->_readOneLineOtherObject($l->getParsed()['bl/rl/nid2']);

        // Synchronisation
        $link = null;
        $linkInstance = null;
        foreach ($localisations as $localisation) {
            $links = $this->_io->getLinks($this->_id, $localisation);
            $this->_metrology->addLog('Object links count read ' . $this->_id . ' ' . sizeof($links), Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

            foreach ($links as $link) {
                $linkInstance = $this->_nebuleInstance->newLink($link);
                $linkInstance->write();
            }
        }

        unset($localisations, $localisation, $links, $link, $linkInstance);
        return true;
    }

    /**
     * Supprime l'objet si plus aucune entité ne l'utilise.
     * Le lien de suppression est créé même si l'objet n'est pas supprimé.
     *
     * @return boolean
     */
    public function deleteObject(): bool
    {
        $deleteObject = true;

        // Détecte si l'objet est protégé.
        $this->_getMarkProtected();
        $protected = ($this->_markProtectedChecked && $this->_cacheMarkProtected);
        if ($protected)
            $id = $this->_idUnprotected;
        else
            $id = $this->_id;

        $this->_writeLink('d>' . $this->_id);

        // Lit les liens.
        $links = array();
        $this->getLinks($links, array(), false);
        $entity = $this->_nebuleInstance->getCurrentEntity();
        foreach ($links as $link) {
            // Vérifie si l'entité signataire du lien est l'entité courante.
            if ($link->getParsed()['bs/rs1/eid'] != $entity) {
                // Si ce n'est pas l'entité courante, quitte.
                $this->_metrology->addAction('delobj', $id, false);
                $deleteObject = false;
            }
        }

        if ($deleteObject) {
            // Supprime l'objet.
            $r1 = $this->_io->unsetObject($id); // FIXME declare vars r1 r2
            $r2 = true;

            // Métrologie.
            $this->_metrology->addAction('delobj', $id, $r1);
        }

        // Si protégé.
        if ($protected) {
            $this->_metrology->addLog('Delete protected object ' . $this->_id, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            $id = $this->_idProtected;

            $this->_writeLink('d>' . $this->_id);

            // Lit les liens.
            $links = array();
            $this->getLinks($links, array(), false);
            $entity = $this->_nebuleInstance->getCurrentEntity();
            foreach ($links as $link) {
                // Vérifie si l'entité signataire du lien est l'entité courante.
                if ($link->getParsed()['bs/rs1/eid'] != $entity) {
                    // Si ce n'est pas l'entité courante, quitte.
                    $this->_metrology->addAction('delobj', $id, false);
                    $deleteObject = false;
                }
            }

            if ($deleteObject) {
                // Supprime l'objet.
                $r2 = $this->_io->unsetObject($id);

                // Métrologie.
                $this->_metrology->addAction('delobj', $id, $r2);
            }
        }
        unset($links, $entity, $link, $deleteObject);

        return ($r1 && $r2);
    }

    /**
     * Supprime un objet et ses liens.
     *
     * @return boolean
     * @todo faire une suppression de ses propres liens uniquement.
     *
     */
    public function deleteObjectLinks(): bool
    {
        $this->_writeLink('d>' . $this->_id);

        // Lit les liens.
        $links = array();
        $this->getLinks($links, array(), false);
        $entity = $this->_nebuleInstance->getCurrentEntity();
        foreach ($links as $link) {
            // Vérifie si l'entité signataire du lien est l'entité courante.
            if ($link->getParsed()['bs/rs1/eid'] != $entity) {
                // Si ce n'est pas l'entité courante, quitte.
                unset($links, $entity, $link);
                return false;
            }
        }

        unset($links, $entity, $link);

        // Supprime l'objet.
        $r = $this->_io->unsetObject($this->_id);

        // Métrologie.
        $this->_metrology->addAction('delobj', $this->_id, $r);

        // Supprime les liens de l'objet.
        $this->_io->linksDelect($this->_id);

        return $r;
    }

    /**
     * Supprime un objet.
     * Force l'opération si l'entité est autorisée à le faire.
     *
     * @return boolean
     */
    public function deleteForceObject(): bool
    {
        $this->_writeLink('d>' . $this->_id);

        $r = $this->_io->unsetObject($this->_id);

        $this->_metrology->addAction('delobj', $this->_id, $r);

        return $r;
    }

    /**
     * Supprime un objet et ses liens.
     * Force l'opération si l'entité est autorisée à le faire.
     */
    public function deleteForceObjectLinks()
    {
        // Supprime l'objet.
        $this->_io->unsetObject($this->_id);

        // Supprime les liens de l'objet.
        $this->_io->flushLinks($this->_id);
    }

    /**
     * Trie un tableau de liens en fonction des dates des liens.
     *
     * @param array $links
     * @return boolean
     */
    protected function _arrayDateSort(array &$links): bool
    {
        if (sizeof($links) == 0)
            return false;
        foreach ($links as $n => $t)
            $linksDate[$n] = $t->getDate();
        array_multisort($linksDate, SORT_STRING, SORT_DESC, $links);
        return true;
    }


    /**
     * Ecrit l'objet si non présent.
     *
     * @return boolean
     */
    public function write(): bool
    {
        // Si protégé.
        if ($this->_cacheMarkProtected) {
            $this->_metrology->addAction('addobj', $this->_id, false);
            $this->_metrology->addLog('Write objet error, protected.', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            return false;
        }

        // Si pas de données.
        if (!$this->_haveData) {
            $this->_metrology->addAction('addobj', '0', false);
            $this->_metrology->addLog('Write objet error, no data.', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            return false;
        }

        $ok = $this->_io->setObject($this->_id, $this->_data);

        // vide les données.
        if (strlen($this->_data) > 1000000)
        {
            $this->_data = '';
            $this->_haveData = false;
        }

        $this->_metrology->addAction('addobj', $this->_id, $ok);
        $this->_metrology->addLog('OK write objet ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        return $ok;
    }

    protected function _writeLink(string $rl, bool $obfuscated = false, string $date = ''): bool
    {
        if ($rl == '')
            return false;
        $newBlockLink = new blocLink($this->_nebuleInstance, 'new');
        $newLink = new Link($this->_nebuleInstance, $rl, $newBlockLink);
        if ($obfuscated && !$newLink->setObfuscate())
            return false;
        $newBlockLink->signwrite($this->_nebuleInstance->getCurrentEntity(), $date);
        return true;
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#oo">OO / Objet</a>
            <ul>
                <li><a href="#oon">OON / Nommage</a></li>
                <li><a href="#oop">OOP / Protection</a></li>
                <li><a href="#ood">OOD / Dissimulation</a></li>
                <li><a href="#ool">OOL / Liens</a></li>
                <li><a href="#ooc">OOC / Création</a></li>
                <li><a href="#oos">OOS / Stockage</a>
                    <ul>
                        <li><a href="#oosa">OOSA / Arborescence</a></li>
                    </ul>
                </li>
                <li><a href="#oot">OOT / Transfert</a></li>
                <li><a href="#oor">OOR / Réservation</a></li>
                <li><a href="#ooio">OOIO / Implémentation des Options</a></li>
                <li><a href="#ooia">OOIA / Implémentation des Actions</a></li>
                <li><a href="#oov">OOV / Vérification</a></li>
                <li><a href="#ooo">OOO / Oubli</a></li>
            </ul>
        </li>
        <li><a href="#or">OR / Référence</a>
            <ul>
                <li><a href="#orn">ORN / Nommage</a></li>
                <li><a href="#orp">ORP / Protection</a></li>
                <li><a href="#ord">ORD / Dissimulation</a></li>
                <li><a href="#orl">ORL / Liens</a></li>
                <li><a href="#orc">ORC / Création</a></li>
                <li><a href="#ors">ORS / Stockage</a></li>
                <li><a href="#ort">ORT / Transfert</a></li>
                <li><a href="#orr">ORR / Réservation</a></li>
                <li><a href="#orio">ORIO / Implémentation des Options</a></li>
                <li><a href="#oria">ORIA / Implémentation des Actions</a></li>
                <li><a href="#oro">ORO / Oubli</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationCore()
    {
        ?>

        <h1 id="o">O / Objet</h1>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <p>L'objet est le contenant de toutes les informations.</p>

        <h2 id="oo">OO / Objet</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’objet est un agglomérat de données numériques.</p>
        <p>Un objet numérique est identifié par une empreinte ou condensat (hash) numérique de type cryptographique.
            Cette empreinte est à même d'empêcher la modification du contenu d'un objet, intentionnellement ou non (cf
            <a href="#co">CO</a>).</p>

        <h3 id="oon">OON / Nommage</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le nommage à l’affichage du nom des objets repose sur plusieurs propriétés :</p>
        <ol>
            <li>nom</li>
            <li>prénom</li>
            <li>surnom</li>
            <li>préfixe</li>
            <li>suffixe</li>
        </ol>
        <p>Ces propriétés sont matérialisées par des liens de type <code>l</code> avec comme objets méta, respectivement
            :</p>
        <ol>
            <li><code>nebule/objet/nom</code></li>
            <li><code>nebule/objet/prenom</code></li>
            <li><code>nebule/objet/surnom</code></li>
            <li><code>nebule/objet/prefix</code></li>
            <li><code>nebule/objet/suffix</code></li>
        </ol>
        <p>Par convention, voici le nommage des objets pour l’affichage :</p>
        <p class="pcenter"><code>prénom préfixe/nom.suffixe surnom</code></p>

        <h3 id="oop">OOP / Protection</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La protection d'un objet va permettre de cacher le contenu de l'objet.</p>
        <p>A faire...</p>

        <h3 id="ood">OOD / Dissimulation</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La dissimulation des liens d'un objet va permettre de cacher la présence ou l'usage d'un objet.</p>
        <p>A faire...</p>

        <h3 id="ool">OOL / Liens</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="ooc">OOC / Création</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’objet est identifié par un ID égal à la valeur de son empreinte.</p>
        <p>L’indication de la fonction de prise d’empreinte (hashage) est impératif. Elle est défini par le lien :</p>
        <ul>
            <li>Signature du lien</li>
            <li>Identifiant du signataire</li>
            <li>Horodatage</li>
            <li>action : <code>l</code></li>
            <li>source : ID de l’objet</li>
            <li>cible : hash du nom de l’algorithme de prise d’empreinte</li>
            <li>méta : hash(‘nebule/objet/hash’)</li>
        </ul>
        <p>Le lien de définition du type est optionnel. Le type est généralement le type mime reconnu de l’objet.</p>
        <ul>
            <li>Signature du lien</li>
            <li>Identifiant du signataire</li>
            <li>Horodatage</li>
            <li>action : <code>l</code></li>
            <li>source : ID de l'objet</li>
            <li>cible : hash(type de l'objet)</li>
            <li>méta : hash(‘nebule/objet/type’)</li>
        </ul>
        <p>A faire...</p>

        <h3 id="oos">OOS / Stockage</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Tous les contenus des objets sont stockés dans un même emplacement ou sont visible comme étant dans un même
            emplacement. Cet emplacement ne contient pas les liens (cf <a href="#ls">LS</a>).</p>
        <p>A faire...</p>

        <h3 id="oosa">OOSA / Arborescence</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Sur un système de fichiers, tous les contenus des objets sont stockés dans des fichiers contenus dans le
            dossier <code>pub/o/</code> (<code>o</code> comme objet).</p>
        <p>A faire...</p>

        <h3 id="oot">OOT / Transfert</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oor">OOR / Réservation</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les différentes objets réservés pour les besoins de la bibliothèque nebule :</p>
        <ul>
            <?php
            $list = nebule::RESERVED_OBJECTS_LIST;
            foreach ($list as $item) {
                echo "\t<li><code>$item</code></li>\n";
            }
            unset($list, $item);
            ?>
        </ul>

        <p>Les objets réservés périmés :</p>
        <ul>
            <li>nebule/objet/entite/web</li>
            <li>nebule/objet/entite/web/applications</li>
        </ul>

        <h4 id="ooio">OOIO / Implémentation des Options</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="ooia">OOIA / Implémentation des Actions</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oov">OOV / Vérification</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’empreinte d’un objet doit être vérifiée lors de la fin de la réception de l’objet. L’empreinte d’un objet
            devrait être vérifiée avant chaque utilisation de cet objet. Un contenu d'objet avec une empreinte qui ne
            lui correspond pas doit être supprimé. Lors de la suppression d’un objet, les liens de cet objet ne sont pas
            supprimés. La vérification de la validité des liens est complètement indépendante de celle des objets, et
            inversement (cf <a href="#co">CO</a> et <a href="#lv">LV</a>).</p>

        <h3 id="ooo">OOO / Oubli</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <h2 id="or">OR / Référence</h2>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="orn">ORN / Nommage</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="orp">ORP / Protection</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="ord">ORD / Dissimulation</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="orl">ORL / Liens</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="orc">ORC / Création</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Liste des liens à générer lors de la création d'une entité.</p>
        <p>A faire...</p>

        <h3 id="ors">ORS / Stockage</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="ort">ORT/ Transfert</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="orr">ORR / Réservation</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="orio">ORIO / Implémentation des Options</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h4 id="oria">ORIA / Implémentation des Actions</h4>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <h3 id="oro">ORO / Oubli</h3>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}
