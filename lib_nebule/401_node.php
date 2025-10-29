<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * ------------------------------------------------------------------------------------------
 * Object class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *          To open a node as object, create instance with :
 *          - nebule instance;
 *          - valid node ID.
 *          To create a new object with specific content, create a instance with :
 *          - nebule instance;
 *          - node ID = '0'
 *          - call setContent() with data of the object.
 *          Don't forget to call write() if you want a persistant object on database /o.
 *          On error, return instance with ID = '0'.
 * ------------------------------------------------------------------------------------------
 */
class Node extends Functions implements nodeInterface
{
    const CRYPTO_SESSION_KEY_SIZE = 117; // FIXME utilisé par setProtected(), à refaire pour le cas général.
    const DEFAULT_ICON_RID = '6e6562756c652f6f626a657400000000000000000000000000000000000000000000.none.272';

    const SESSION_SAVED_VARS = array(
        '_id',
        '_fullName',
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
        '_usedUpdate',
        '_isEntity',
        '_isGroup',
        '_isConversation',
        '_isCurrency',
        '_isTokenPool',
        '_isToken',
        '_isWallet',
    );

    protected bool $_permitBuffer = false;
    protected string $_id = '0';
    protected string $_fullName = '';
    protected array $_cachePropertyLink = array();
    protected array $_cachePropertiesLinks = array();
    protected array $_cachePropertyID = array();
    protected array $_cachePropertiesID = array();
    protected array $_cacheProperty = array();
    protected array $_cacheProperties = array();
    protected bool $_cacheMarkDanger = false;
    protected bool $_cacheMarkWarning = false;
    protected bool $_cacheMarkProtected = false;
    protected string $_idProtected = '0';
    protected string $_idUnprotected = '0';
    protected string $_idProtectedKey = '0';
    protected string $_idUnprotectedKey = '0';
    protected bool $_markProtectedChecked = false;
    protected ?string $_data = null;
    protected bool $_haveData = false;
    protected string $_code = '';
    protected bool $_haveCode = false;
    protected array $_usedUpdate = array();
    protected bool $_isNew = false;

    /**
     * Create an instance of a node or derivative.
     * Always give a valid nebule instance.
     * For new node, set $id as '0' or 'new'. This is mandatory to add data (or other) after with dedicated function.
     * If $id is invalid, the instance return getID = '0', even if new but not initialised.
     *
     * @param nebule $nebuleInstance
     * @param string $nid
     */
    public function __construct(nebule $nebuleInstance, string $nid)
    {
        parent::__construct($nebuleInstance);
        $this->setEnvironmentLibrary($nebuleInstance);

        $id = trim(strtolower($nid));
        if (self::checkNID($id, false, false)
        ) {
            $this->_id = $id;
            $this->_metrologyInstance->addLog('new instance ' . get_class($this) . ' nid=' . $id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '7fb8f6e3');
        } else {
            $this->_id = '0';
            $this->_isNew = true;
            $this->_metrologyInstance->addLog('instance for new ' . get_class($this), Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'a16c2373');
        }

        $this->initialisation();
    }

    public function __toString(): string
    {
        return $this->_id;
    }

    public function __sleep(): array
    {
        return $this::SESSION_SAVED_VARS;
    }

    public function __wakeup()
    {
        //global $nebuleInstance;
        //$this->_nebuleInstance = $nebuleInstance;
        //$this->setEnvironmentLibrary($nebuleInstance);
        //$this->_initialisation();
    }

    /**
     * Local initialisation for a node or derivatives.
     * Called by function initialisation() but only one time, the first time.
     * @return void
     */
    protected function _initialisation(): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_permitBuffer = $this->_configurationInstance->getOptionAsBoolean('permitBufferIO');
    }

    /**
     * On new node (ID='0'), add content and recalculate ID.
     * Remember to write with write().
     *
     * @param string $data
     * @return bool
     */
    public function setContent(string &$data): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_isNew
            || $this->_id != '0'
            || strlen($data) == 0
            || $this->_haveData
            || get_class($this) != 'Nebule\Library\Node'
        )
            return false;

        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteObject', 'permitCreateObject'))) {
            $this->_metrologyInstance->addLog('Create object no authorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, '83a27d1e');
            $this->_id = '0';
            return false;
        }

        $this->_id = $this->_nebuleInstance->getFromDataNID($data);
        $this->_data = $data;
        $this->_haveData = true;
        return true;
    }

    public function setWriteContent(string &$data, bool $protect = false, bool $obfuscated = false): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->setContent($data))
            return false;

        // @FIXME
        if ($protect)
            $this->setProtected($obfuscated);
        else
            $this->write();

        return true;
    }

    /*public function setContentOld(string &$data, bool $protect = false, bool $obfuscated = false): bool
    {
        if (!$this->_isNew
            || $this->_id != '0'
            || strlen($data) == 0
            || get_class($this) != 'Nebule\Library\Node'
        )
            return false;

        if ($this->_configuration->checkBooleanOptions(array('unlocked','permitWrite','permitWriteObject','permitWriteLink'))) {
            // calcul l'ID.
            $this->_id = $this->_nebuleInstance->getFromDataNID($data);
            if ($protect)
                $this->_metrology->addLog('Create protected object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1434b3ed');
            else
                $this->_metrology->addLog('Create object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '52b9e412');

            // Mémorise les données.
            $this->_data = $data;
            $this->_haveData = true;

            // Création lien de hash.
            $date = '';
            if ($obfuscated)
                $date = '0';
            $target = $this->_nebuleInstance->getFromDataNID($this->_configuration->getOptionAsString('cryptoHashAlgorithm'));
            $meta = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_HASH);
            $this->_writeLink('l>' . $this->_id . '>' . $target . '>' . $meta, $obfuscated, $date);

            // Création du lien d'annulation de suppression.
            $this->_writeLink('x>' . $this->_id, $obfuscated);

            // Si l'objet doit être protégé.
            if ($protect)
                $this->setProtected($obfuscated);
            else
                $this->write();
        } else {
            $this->_metrology->addLog('Create object error no authorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, '83a27d1e');
            $this->_id = '0';
            return false;
        }
        return true;
    }*/

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
     * @param bool   $permitNull permit NID=''
     * @param bool   $permitZero permit NID='0'
     * @return boolean
     */
    static public function checkNID(string &$nid, bool $permitNull = false, bool $permitZero = false): bool
    {
        // May be empty or zero in some case.
        if ($permitNull && $nid == '') return true;
        if ($nid == '') return false;
        if ($permitZero && $nid == '0') return true;
        if ($nid == '0') return false;

        $hash = strtok($nid, '.');
        if ($hash === false) return false;
        $algo = strtok('.');
        if ($algo === false) return false;
        $size = strtok('.');
        if ($size === false) return false;

        // Check item overflow
        if (strtok('.') !== false) return false;

        if ($algo == 'none' || $algo == 'string')
            $minSize = BlocLink::NID_MIN_NONE_SIZE;
        else
            $minSize = BlocLink::NID_MIN_HASH_SIZE;

        // Check hash value. Hash size = (size value / 4) .
        if ((strlen($hash) * 4) < $minSize) return false;
        if ((strlen($hash) * 4) > BlocLink::NID_MAX_HASH_SIZE) return false;
        if (!ctype_xdigit($hash)) return false;

        // Check algo value.
        if (strlen($algo) < BlocLink::NID_MIN_ALGO_SIZE) return false;
        if (strlen($algo) > BlocLink::NID_MAX_ALGO_SIZE) return false;
        if (!ctype_alnum($algo)) return false;

        // Check size value.
        if (!ctype_digit($size)) return false; // Check content before!
        if ((int)$size < $minSize) return false;
        if ((int)$size > BlocLink::NID_MAX_HASH_SIZE) return false;
        if ((strlen($hash) * 4) != (int)$size) return false;

        return true;
    }

    /**
     * Object - Verify name structure of the virtual node : hash.algo.size
     * The part 'algo.size' is fixed.
     * There's a specific treatment for NID empty or '0'.
     *
     * @param string $nid
     * @param bool   $permitNull permit NID=''
     * @param bool   $permitZero permit NID='0'
     * @return boolean
     */
    static public function checkVirtualNID(string &$nid, bool $permitNull = false, bool $permitZero = false): bool
    {
        // May be empty or zero in some case.
        if ($permitNull && $nid == '') return true;
        if ($nid == '') return false;
        if ($permitZero && $nid == '0') return true;
        if ($nid == '0') return false;

        $hash = strtok($nid, '.');
        if ($hash === false) return false;
        $algo = strtok('.');
        if ($algo === false) return false;
        $size = strtok('.');
        if ($size === false) return false;

        // Check item overflow
        if (strtok('.') !== false) return false;

        if ($algo == 'none' || $algo == 'string')
            $minSize = BlocLink::NID_MIN_NONE_SIZE;
        else
            $minSize = BlocLink::NID_MIN_HASH_SIZE;

        // Check hash value. Hash size = (size value / 4) .
        if ((strlen($hash) * 4) < $minSize) return false;
        if ((strlen($hash) * 4) > BlocLink::NID_MAX_HASH_SIZE) return false;
        if (!ctype_xdigit($hash)) return false;

        // Check algo value.
        if (strlen($algo) < BlocLink::NID_MIN_ALGO_SIZE) return false;
        if (strlen($algo) > BlocLink::NID_MAX_ALGO_SIZE) return false;
        if (!ctype_alnum($algo)) return false;

        // Check size value.
        if (!ctype_digit($size)) return false; // Check content before!
        if ((int)$size < $minSize) return false;
        if ((int)$size > BlocLink::NID_MAX_HASH_SIZE) return false;
        if ((strlen($hash) * 4) != (int)$size) return false;

        // FIXME reduce the code with this limites
        if (strlen($nid) != ((References::VIRTUAL_NODE_SIZE * 2) + strlen('.none.' . (References::VIRTUAL_NODE_SIZE * 8))))
            return false;
        if (substr($nid, References::VIRTUAL_NODE_SIZE * 2) != '.none.' . (References::VIRTUAL_NODE_SIZE * 8))
            return false;

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $algo = $this->getProperty(References::REFERENCE_NEBULE_OBJET_HASH, 'all');
        $this->_metrologyInstance->addLog('Object ' . $this->_id . ' hash = ' . $algo, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '09c0ab8c');

        if ($algo != '')
            return $algo;
        return '';
    }

    public function checkPresent(): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_id == '0')
            return false;

        /*if ($this->_getMarkProtected())
            $result = $this->_nebuleInstance->getIoInstance()->checkObjectPresent($this->_idProtected);
        else*/
            $result = $this->_nebuleInstance->getIoInstance()->checkObjectPresent($this->_id);
        return $result;
    }

    public function checkObjectHaveLinks(): bool
    {
        return $this->_ioInstance->checkLinkPresent($this->_id);
    }

    /**
     * Faire une recherche de liens type 'l' en fonction de l'objet méta.
     * Typiquement utilisé pour une recherche de propriétés d'un objet.
     * Fait une recherche sur de multiples algorithmes de hash au besoin.
     *
     * @param array  $links
     * @param string $nid3
     * @param string $socialClass
     */
    private function _getLinksByNID3(array &$links, string $nid3, string $socialClass = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid3' => $nid3,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, $socialClass, false);
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($type == '')
            return array();

        if (!$this->checkNID($type))
            $type = $this->_nebuleInstance->getFromDataNID($type);

        $links = array();
        $this->_getLinksByNID3($links, $type, $socialClass);
        $this->_socialInstance->arraySocialFilter($links, $socialClass);

        if (sizeof($links) == 0)
            return array();

        // Trie la liste, pour les liens venants de plusieurs objets.
        /*$date = array();
        foreach ($links as $k => $r)
            $date[$k] = $r->getDate();
        array_multisort($date, SORT_ASC, SORT_STRING, $links);*/

        $sortedLinks = array();
        foreach ($links as $r) {
            $date = $r->getDate();
            if (isset($sortedLinks[$date]))
                $date = $date . bin2hex($this->_cryptoInstance->getRandom(4, Crypto::RANDOM_PSEUDO));
            $sortedLinks[$date] = $r;
        }
        unset($links);

        //krsort($sortedLinks, SORT_STRING);
        ksort($sortedLinks, SORT_STRING);

        return $sortedLinks;
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($type == '')
            return null;

        $links = $this->getPropertiesLinks($type, $socialClass);

        if (sizeof($links) == 0)
            return null;
//foreach ($links as $link)
//    $this->_metrologyInstance->addLog('DEBUGGING link=' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $link = end($links);
        if (!$link)
            return null;
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($type == '')
            return '';

        $link = $this->getPropertyLink($type, $socialClass);

        if (!is_a($link, 'Nebule\Library\LinkRegister'))
            return '';

        // Extrait l'ID de l'objet de propriété.
        $property = $link->getParsed()['bl/rl/nid2'];
        unset($link);

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($type == '')
            return array();

        $properties = array();

        $list = array();
        $this->_getLinksByNID3($list, $type, $socialClass);
        $this->_socialInstance->arraySocialFilter($list, $socialClass);

        if (sizeof($list) == 0)
            return array();

        // Extrait les ID des objets de propriété.
        foreach ($list as $i => $l)
            $properties[$i] = $l->getParsed()['bl/rl/nid2'];
        unset($list);

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($type == '')
            return '';

        $link = $this->getPropertyLink($type, $socialClass);

        $property = '';
        if ($link != null && is_a($link, 'Nebule\Library\LinkRegister'))
            $property = $this->_readOneLineOtherObject($link->getParsed()['bl/rl/nid2']);

        $this->_nebuleInstance->getMetrologyInstance()->addLog('property name=' . $type . ' value=' . $property, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '535b1337');
        return $property;
    }

    /**
     * Lit les propriétés de l'objet nebule dans ses liens.
     * Retourne un tableau de chaines de texte d'une seule ligne, ou un tableau d'une chaine vide si problème.
     *
     * @param string $type
     * @param string $socialClass
     * @return array
     */
    public function getProperties(string $type, string $socialClass = ''): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($type == '')
            return array();

        $properties = array();

        $links = array();
        $this->_getLinksByNID3($links, $type, $socialClass);
        $this->_socialInstance->arraySocialFilter($links, $socialClass);

        foreach ($links as $i => $l) {
            $properties[$i] = $this->_readOneLineOtherObject($l->getParsed()['bl/rl/nid2']);
            $this->_nebuleInstance->getMetrologyInstance()->addLog('list property name=' . $type . ' value=' . $properties[$i], Metrology::LOG_LEVEL_DEBUG, __METHOD__, '3bbb0aa7');
        }
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $list = $this->getProperties($type, $socialClass);

        if (in_array($property, $list))
            return true;

        return false;
    }

    /**
     * Lit si l'objet a une propriété particulière.
     *
     * @param string $type
     * @param string $propertyID
     * @param string $socialClass
     * @return boolean
     */
    public function getHavePropertyID(string $type, string $propertyID, string $socialClass = 'myself'): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $list = $this->getPropertiesID($type, $socialClass);

        if (in_array($propertyID, $list))
            return true;

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $signers = array();

        // Si le type de l'objet est précisé, le converti en ID.
        if ($type != '')
            $type = $this->_nebuleInstance->getFromDataNID($type, References::REFERENCE_CRYPTO_HASH_ALGORITHM);

        // Extraction des entités signataires.
        $links = $this->getPropertiesLinks(References::REFERENCE_NEBULE_OBJET_TYPE, 'all');

        foreach ($links as $link) {
            if ($type == '' || $link->getParsed()['bl/rl/nid2'] == $type)
                $signers[$link->getParsed()['bs/rs1/eid']] = $link->getParsed()['bs/rs1/eid'];
        }
        unset($links);

        return $signers;
    }

    /**
     * Recherche une propriété de l'objet est signée par une entité.
     * Si la propriété est vide, vérifie pour toute propriété.
     *
     * @param string $entity
     * @param string $type
     * @return boolean
     */
    public function getPropertySignedBy(string $entity, string $type = ''): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si le type de l'objet est précisé, le converti en ID.
        if ($type != '')
            $type = $this->_nebuleInstance->getFromDataNID($type, References::REFERENCE_CRYPTO_HASH_ALGORITHM);

        // extrait l'ID de l'entité si c'est un objet.
        if (is_a($entity, 'Nebule\Library\Node'))
            $entity = $entity->getID();

        // Extraction des entités signataires.
        $links = $this->getPropertiesLinks(References::REFERENCE_NEBULE_OBJET_TYPE, 'all');

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
     * Write a property to an object depending to the type of property.
     * TODO protection et vidage cache
     *
     * @param string  $type
     * @param string  $property
     * @param boolean $protect
     * @param boolean $obfuscated
     * @param array   $signer
     * @return boolean
     */
    public function setProperty(string $type, string $property, bool $protect = false, bool $obfuscated = false, array $signer = array()): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($type == ''
            || $property == ''
            //|| $protect // TODO
        )
            return false;

        $privateKey = '';
        $password = '';
        if (sizeof($signer) != 0) {
            $signerInstance = $signer['eid'];
            if (isset($signer['key']))
                $privateKey = $signer['key'];
            if (isset($signer['pwd']))
                $password = $signer['pwd'];
        } else {
            $signerInstance = $this->_entitiesInstance->getGhostEntityInstance();
        }

        $propertyOID = $this->_nebuleInstance->getFromDataNID($property);
        $this->_ioInstance->setObject($propertyOID, $property);

        $propertyRID = $this->_nebuleInstance->getFromDataNID($type);
        $link = 'l>' . $this->_id . '>' . $propertyOID . '>' . $propertyRID;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link);
        //if ($obfuscated && !$newLink->setObfuscate()) FIXME obfuscation
        //    return false;
        $newBlockLink->signwrite($signerInstance, '', $privateKey, $password);

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

    public function getTypeID(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getPropertyID(References::REFERENCE_NEBULE_OBJET_TYPE, $socialClass);
    }

    public function getType(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_TYPE, $socialClass);
    }
    public function setType(string $type, bool $protect = false, bool $obfuscated = false, array $signer = array()): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->setProperty(References::REFERENCE_NEBULE_OBJET_TYPE, $type, $protect, $obfuscated, $signer);
    }

    public function getSize(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return filter_var($this->getProperty(References::REFERENCE_NEBULE_OBJET_TAILLE, $socialClass), FILTER_SANITIZE_NUMBER_INT);
    }

    public function getHomomorpheFingerprint(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_HOMOMORPHE, $socialClass);
    }

    public function getDate(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_DATE, $socialClass);
    }

    public function getName(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $name = $this->getProperty(References::REFERENCE_NEBULE_OBJET_NOM, $socialClass);
        if ($name == '')
            $name = $this->_id;
        return $name;
    }
    public function setName(string $name, bool $protect = false, bool $obfuscated = false, array $signer = array()): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->setProperty(References::REFERENCE_NEBULE_OBJET_NOM, $name, $protect, $obfuscated, $signer);
    }

    public function getPrefixName(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_PREFIX, $socialClass);
    }
    public function setPrefixName(string $prefix, bool $protect = false, bool $obfuscated = false, array $signer = array()): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->setProperty(References::REFERENCE_NEBULE_OBJET_PREFIX, $prefix, $protect, $obfuscated, $signer);
    }

    public function getSuffixName(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_SUFFIX, $socialClass);
    }
    public function setSuffixName(string $suffix, bool $protect = false, bool $obfuscated = false, array $signer = array()): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->setProperty(References::REFERENCE_NEBULE_OBJET_SUFFIX, $suffix, $protect, $obfuscated, $signer);
    }

    public function getFirstname(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_PRENOM, $socialClass);
    }
    public function setFirstname(string $firstname, bool $protect = false, bool $obfuscated = false, array $signer = array()): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->setProperty(References::REFERENCE_NEBULE_OBJET_SUFFIX, $firstname, $protect, $obfuscated, $signer);
    }

    public function getSurname(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_SURNOM, $socialClass);
    }
    public function setSurname(string $surname, bool $protect = false, bool $obfuscated = false, array $signer = array()): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->setProperty(References::REFERENCE_NEBULE_OBJET_SURNOM, $surname, $protect, $obfuscated, $signer);
    }

    public function getFullName(string $socialClass = 'self'): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (isset($this->_fullName)
            && trim($this->_fullName) != ''
        )
            return $this->_fullName;

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

        $this->_fullName = $fullname;
        return $fullname;
    }

    public function getLocations(string $socialClass = ''): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getProperties(References::REFERENCE_NEBULE_OBJET_LOCALISATION, $socialClass);
    }
    public function getLocation(string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_LOCALISATION, $socialClass);
    }
    public function setLocation(string $localisation): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->setProperty(References::REFERENCE_NEBULE_OBJET_LOCALISATION, $localisation);
    }



    protected bool $_isEntity = false;

    /**
     * Check a node if it can be a valid entity:
     *  - NID not null;
     *  - have local links;
     *  - if $mustHaveContent, check if OID have content;
     *  - if $mustHaveContent, check if content of OID has a beginning of public cryptographique key;
     *  - if $type is PEM mimetype, check if we have a valid link to this type (try to find if entity node don't have link).
     * @param string $socialClass
     * @param bool   $mustHaveContent
     * @param string $type
     * @return bool
     */
    public function getIsEntity(string $socialClass = 'self', bool $mustHaveContent = true, string $type = References::REFERENCE_OBJECT_ENTITY): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_isEntity)
            return true;

        if ($this->_id == '0') {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('nul nid', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ff1d7167');
            return false;
        }

        if (!$this->checkObjectHaveLinks()) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('do not have link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9786e672');
            return false;
        }

        if ($mustHaveContent && !$this->checkPresent()) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('do not have content', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '983d3318');
            return false;
        }

        if ($mustHaveContent && !str_contains($this->readOneLineAsText(Entity::ENTITY_MAX_SIZE), References::REFERENCE_ENTITY_HEADER)) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('do not have header', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0bd80061');
            return false;
        }

        if ($type == References::REFERENCE_OBJECT_ENTITY) {
            $refEntityID = $this->getNidFromData($type);
            $refPropertyID = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_TYPE);
            $this->_isEntity = $this->getHavePropertyID($refPropertyID, $refEntityID, $socialClass);
        } else
            $this->_isEntity = true;

        return $this->_isEntity;
    }



    protected bool $_isGroup = false;
    public function getIsGroup(string $socialClass = 'myself'): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_isGroup)
            return true;

        $refPropertyID = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_TYPE);
        $refGroupID = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE);
        $this->_isGroup = $this->getHaveProperty($refPropertyID, $refGroupID, $socialClass);

        return $this->_isGroup;
    }

    public function getListIsMemberOnGroupLinks(string $socialClass = 'myself'): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, $socialClass);

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getParsed()['bl/rl/nid1'] != $link->getParsed()['bl/rl/nid3'])
                unset($links[$i]);
        }

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1'], \Nebule\Library\Cache::TYPE_GROUP);
            if (!$instance->getIsGroup('all'))
                unset($links[$i]);
        }

        return $links;
    }

    public function getListIsMemberOnGroupID(string $socialClass = 'myself'): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, $socialClass);

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getParsed()['bl/rl/nid1'] != $link->getParsed()['bl/rl/nid3'])
                unset($links[$i]);
        }

        // Tri les objets de type groupe.
        $list = array();
        foreach ($links as $i => $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1'], \Nebule\Library\Cache::TYPE_GROUP);
            if ($instance->getIsGroup('all'))
                $list[$link->getParsed()['bl/rl/nid1']] = $link->getParsed()['bl/rl/nid1'];
        }

        return $list;
    }



    protected bool $_isConversation = false;
    public function getIsConversation(string $socialClass = 'myself'): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isConversation)
            return true;

        $this->_isConversation = $this->getHaveProperty(References::REFERENCE_NEBULE_OBJET_TYPE, References::REFERENCE_NEBULE_OBJET_CONVERSATION, $socialClass);

        return $this->_isConversation;
    }

    public function getListIsMemberOnConversationLinks(string $socialClass = 'myself'): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, $socialClass);

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getParsed()['bl/rl/nid1'] != $link->getParsed()['bl/rl/nid3'])
                unset($links[$i]);
        }

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1'], \Nebule\Library\Cache::TYPE_CONVERSATION);
            if (!$instance->getIsConversation('all'))
                unset($links[$i]);
        }

        return $links;
    }

    public function getListIsMemberOnConversationID(string $socialClass = 'myself'): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, $socialClass);

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getParsed()['bl/rl/nid1'] != $link->getParsed()['bl/rl/nid3'])
                unset($links[$i]);
        }

        // Tri les objets de type groupe.
        $list = array();
        foreach ($links as $i => $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1'], \Nebule\Library\Cache::TYPE_CONVERSATION);
            if ($instance->getIsConversation('all'))
                $list[$link->getParsed()['bl/rl/nid1']] = $link->getParsed()['bl/rl/nid1'];
        }

        return $list;
    }



    protected bool $_isCurrency = false;
    public function getIsCurrency(string $socialClass = 'myself'): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isCurrency)
            return true;

        $this->_isCurrency = $this->getHaveProperty(References::REFERENCE_NEBULE_OBJET_TYPE, References::REFERENCE_NEBULE_OBJET_MONNAIE, $socialClass);

        return $this->_isCurrency;
    }



    protected bool $_isTokenPool = false;
    public function getIsTokenPool(string $socialClass = 'myself'): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isTokenPool)
            return true;

        $this->_isTokenPool = $this->getHaveProperty(References::REFERENCE_NEBULE_OBJET_TYPE, References::REFERENCE_NEBULE_OBJET_MONNAIE_SAC, $socialClass);

        return $this->_isTokenPool;
    }



    protected bool $_isToken = false;
    public function getIsToken(string $socialClass = 'myself'): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isToken)
            return true;

        $this->_isToken = $this->getHaveProperty(References::REFERENCE_NEBULE_OBJET_TYPE, References::REFERENCE_NEBULE_OBJET_MONNAIE_JETON, $socialClass);

        return $this->_isToken;
    }



    protected bool $_isWallet = false;
    public function getIsWallet(string $socialClass = 'myself'): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isWallet)
            return true;

        $this->_isWallet = $this->getHaveProperty(References::REFERENCE_NEBULE_OBJET_TYPE, References::REFERENCE_NEBULE_OBJET_MONNAIE_PORTEFEUILLE, $socialClass);

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkDanger)
            return true;

        $list = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_DANGER),
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, ''); // FIXME

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_configurationInstance->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkDanger)
            return true;

        // Création lien de groupe.
        $target = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_DANGER);
        $this->writeLink('l>' . $this->_id . '>' . $target);

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkWarning)
            return true;

        $list = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_WARNING),
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, ''); // FIXME

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_configurationInstance->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkWarning)
            return true;

        // Création lien de groupe.
        $target = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_WARNING);
        $this->writeLink('l>' . $this->_id . '>' . $target);

        $this->_cacheMarkWarning = true;
        return true;
    }


    /**
     * Lit les marques de protection, c'est-à-dire un lien de chiffrement pour l'objet.
     * Fait une recherche complète.
     * @return boolean
     */
    public function getMarkProtected(): bool
    {
        $this->_getMarkProtected();
        return $this->_cacheMarkProtected;
    }

    /**
     * Lit les marques de protection, c'est-à-dire un lien de chiffrement pour l'objet.
     * Fait une recherche sommaire et rapide.
     * @return boolean
     */
    public function getMarkProtectedFast(string $socialClass = ''): bool
    {
        // FIXME manage x links
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
        $this->getLinks($listS, $filter, $socialClass, false);
        $listT = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($listT, $filter, $socialClass, false);

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
    protected function _getMarkProtected(string $socialClass = ''): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return false; // FIXME disabled!

        /*if ($this->_markProtectedChecked === true
            && $this->_entitiesInstance->getCurrentEntityIsUnlocked() === $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            if ($this->_cacheMarkProtected === true) {
                $this->_metrology->addLog('Object protected - cache - protected', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '83445a74');
            } else {
                $this->_metrology->addLog('Object protected - cache - not protected', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'f4fa20bd');
            }
            return $this->_cacheMarkProtected;
        }

        if ($this->_id == '0') {
            return false;
        }

        // Mémorise l'état de connexion de l'entité courante.
        $this->_entitiesInstance->getCurrentEntityIsUnlocked() = $this->_nebuleInstance->getCurrentEntityUnlocked();

        // Liste les liens à la recherche de la propriété.
        $listS = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($listS, $filter, $socialClass, false);
        $listT = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($listT, $filter, $socialClass, false);

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
            $this->_metrology->addLog('Object protected - not protected', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1913eb68');
            return false;
        }

        // Sinon.
        $this->_markProtectedChecked = true;
        $result = false;

        if (sizeof($listS) == 0) {
            $this->_metrology->addLog('Object protected - id protected = ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'be8d3098');
            $this->_idProtected = $this->_id;

            // Recherche la clé utilisée pour l'entité en cours.
            foreach ($listT as $linkSym) {
                // Si lien de chiffrement et l'objet source est l'objet en cours non protégé.
                if ($linkSym->getParsed()['bl/rl/req'] == 'k'
                    && $linkSym->getParsed()['bl/rl/nid2'] == $this->_idProtected
                ) {
                    // Lit l'objet de clé de chiffrement symétrique et ses liens.
                    $instanceSym = $this->_cacheInstance->newNode($linkSym->getParsed()['bl/rl/nid3']);
                    $linksAsym = array();
                    $this->getLinks($linksAsym, array(), $socialClass, false);
                    unset($instanceSym);
                    foreach ($linksAsym as $linkAsym) {
                        // Si lien de chiffrement.
                        $targetA = $linkAsym->getParsed()['bl/rl/nid2'];
                        if ($linkAsym->getParsed()['bl/rl/req'] == 'k'
                            && $linkAsym->getParsed()['bl/rl/nid2'] != $this->_idProtected
                            && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($targetA)
                        ) {
                            $result = true;
                            $this->_idUnprotected = $linkSym->getParsed()['bl/rl/nid1'];
                            $this->_metrology->addLog('Object protected - id unprotected = ' . $this->_idUnprotected, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bf78846c');
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
            $this->_metrology->addLog('Object protected - id unprotected = ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5c979fa2');
            $this->_idUnprotected = $this->_id;

            // Recherche la clé utilisée pour l'entité en cours.
            foreach ($listS as $linkSym) {
                $targetS = $linkSym->getParsed()['bl/rl/nid2'];
                // Si lien de chiffrement et l'objet source est l'objet en cours non protégé.
                if ($linkSym->getParsed()['bl/rl/req'] == 'k'
                    && $linkSym->getParsed()['bl/rl/nid1'] == $this->_idUnprotected
                    && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($targetS)
                ) {
                    // Lit l'objet de clé de chiffrement symétrique et ses liens.
                    $instanceSym = $this->_cacheInstance->newNode($linkSym->getParsed()['bl/rl/nid3']);
                    $linksAsym = array();
                    $this->getLinks($linksAsym, array(), $socialClass, false);
                    unset($instanceSym);
                    foreach ($linksAsym as $linkAsym) {
                        $targetA = $linkAsym->getParsed()['bl/rl/nid2'];
                        // Si lien de chiffrement.
                        if ($linkAsym->getParsed()['bl/rl/req'] == 'k'
                            && $linkAsym->getParsed()['bl/rl/nid1'] != $this->_idUnprotected
                            && $linkAsym->getParsed()['bl/rl/nid3'] == $this->_nebuleInstance->getCurrentEntity()
                            && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($targetA)
                        ) {
                            $result = true;
                            $this->_idProtected = $targetS;
                            $this->_metrology->addLog('Object protected - id protected = ' . $this->_idProtected, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cd9c4b1b');
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
        return $result;*/
    }

    /**
     * Lit l'ID de l'objet chiffré.
     *
     * @return string
     */
    public function getProtectedID(): string
    {
        return ''; // FIXME disabled!

        //$this->_getMarkProtected();
        //return $this->_idProtected;
    }

    /**
     * Lit l'ID de l'objet non chiffré.
     * @return string
     */
    public function getUnprotectedID(): string
    {
        return ''; // FIXME disabled!

        //$this->_getMarkProtected();
        //return $this->_idUnprotected;
    }

    /**
     * Protège l'objet.
     *
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setProtected(bool $obfuscated = false, string $socialClass = ''): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return false; // FIXME disabled!

        /*if ($this->_id == '0')
            return false;
        if (!$this->_io->checkObjectPresent($this->_id)
            && !$this->_haveData
        )
            return false;

        // Vérifie si pas déjà protégé.
        if ($this->_getMarkProtected())
            return true;

        $this->_metrology->addLog('Ask protect object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4f637f32');

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
            $keyID = $this->_nebuleInstance->getFromDataNID($key);
            $this->_metrology->addLog('Protect object, key : ' . $keyID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '585b4766');

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
                $hash = $this->_nebuleInstance->getFromDataNID($data);
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
            $codeKey = $this->_crypto->encryptTo($key, $this->_entitiesInstance->getCurrentEntityInstance()->getPublicKey());

            // Vérification de bon chiffrement.
            if ($codeKey === false)
                return false;

            // Ecrit le contenu chiffré.
            $codeInstance = new Node($this->_nebuleInstance, '0');
            $codeInstance->setContent($code, false);
            $codeID = $codeInstance->getID();
            $this->_metrology->addLog('Protect object, code : ' . $codeID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '13ac7fd6');

            // Vérification de bonne écriture.
            if ($codeID == '0')
                return false;

            // Ecrit la clé de session chiffrée.
            $codeKeyInstance = new Node($this->_nebuleInstance, '0');
            $codeKeyInstance->setContent($codeKey, false);
            $codeKeyID = $codeKeyInstance->getID();
            $this->_metrology->addLog('Protect object, code key : ' . $codeKeyID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b17970cf');

            // Vérification de bonne écriture.
            if ($codeKeyID == '0')
                return false;

            $signer = $this->_nebuleInstance->getCurrentEntity();

            // Crée le lien de type d'empreinte de la clé.
            $target = $this->_nebuleInstance->getFromDataNID($this->_configuration->getOptionAsString('cryptoHashAlgorithm'));
            $meta = $this->_nebuleInstance->getFromDataNID('nebule/objet/hash');
            $this->writeLink('l>' . $keyID . '>' . $target . '>' . $meta);

            // Création du type mime des données chiffrées.
            $text = 'application/x-encrypted/' . $this->_configuration->getOptionAsString('cryptoSymmetricAlgorithm');
            $textID = $this->_nebuleInstance->createTextAsObject($text);
            if ($textID != '') {
                // Crée le lien de type d'empreinte.
                $meta = $this->_nebuleInstance->getFromDataNID('nebule/objet/type');
                $this->writeLink('l>' . $codeID . '>' . $textID . '>' . $meta, $obfuscated);
            }

            // Création du type mime de la clé chiffrée.
            $text = 'application/x-encrypted/' . $this->_configuration->getOptionAsString('cryptoAsymmetricAlgorithm');
            $textID = $this->_nebuleInstance->createTextAsObject($text);
            if ($textID != '') {
                // Crée le lien de type d'empreinte.
                $meta = $this->_nebuleInstance->getFromDataNID('nebule/objet/type');
                $this->writeLink('l>' . $codeKeyID . '>' . $textID . '>' . $meta, $obfuscated);
            }

            // Création du lien de chiffrement symétrique.
            $this->writeLink('k>' . $this->_id . '>' . $codeID . '>' . $keyID, $obfuscated);

            // Création du lien de chiffrement asymétrique.
            $this->writeLink('k>' . $keyID . '>' . $codekeyID . '>' . $signer, $obfuscated);

            // Supprime l'objet qui a été marqué protégé.
            $this->_metrology->addLog('Delete unprotected object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0db13a8a');
            $deleteObject = true;

            // Création lien.
            $this->writeLink('d>' . $this->_id);

            // Lit les liens.
            $links = array();
            $this->getLinks($links, array(), $socialClass, false);
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
                    if (is_a($entity, 'Nebule\Library\Entity')
                        && $entity->getID() != '0'
                        && $entity->getIsPublicKey()
                        && $entity != $this->_nebuleInstance->getCurrentEntity()
                    ) {
                        // Chiffrement (asymétrique) de la clé de chiffrement du contenu.
                        $codeKey = $this->_crypto->encryptTo($key, $entity->getPublicKey());

                        // Vérification de bon chiffrement.
                        if ($codeKey === false) {
                            $this->_metrology->addLog('Error (1) share protection to recovery ' . $entity->getID(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '89f1011d');
                            continue;
                        }

                        // Ecrit la clé de session chiffrée.
                        $codeKeyInstance = new Node($this->_nebuleInstance, '0');
                        $codeKeyInstance->setContent($codeKey, false);
                        $codeKeyID = $codeKeyInstance->getID();
                        $this->_metrology->addLog('Protect object, code key : ' . $codeKeyID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9aa7e372');

                        // Vérification de bonne écriture.
                        if ($codeKeyID == '0') {
                            $this->_metrology->addLog('Error (2) share protection to recovery ' . $entity->getID(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'ee87339f');
                            continue;
                        }

                        // Création du type mime de la clé chiffrée.
                        $text = 'application/x-encrypted/' . $this->_configuration->getOptionAsString('cryptoAsymmetricAlgorithm');
                        $textID = $this->_nebuleInstance->createTextAsObject($text);
                        if ($textID != '') {
                            // Crée le lien de type d'empreinte.
                            $meta = $this->_nebuleInstance->getFromDataNID('nebule/objet/type');
                            $this->writeLink('l>' . $codeKeyID . '>' . $textID . '>' . $meta, $obfuscated);
                        }

                        // Création du lien de chiffrement asymétrique.
                        $this->writeLink('k>' . $keyID . '>' . $codekeyID . '>' . $entity->getID(), $obfuscated);

                        $this->_metrology->addLog('Set protection shared to recovery ' . $entity->getID(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '38f8aab6');
                    }
                }
            }

            return true;
        }
        return false;*/
    }

    /**
     * Déprotège l'objet.
     * @return boolean
     */
    public function setUnprotected(): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return false; // FIXME disabled!

        /*// Vérifie que l'objet est protégé et que l'on peut y acceder.
        if (!$this->_getMarkProtected()
            || $this->_idProtected == '0'
            || $this->_idUnprotected == '0'
            || $this->_idProtectedKey == '0'
            || $this->_idUnprotectedKey == '0'
        )
            return false;

        $this->_metrology->addLog('Set unprotected ' . $this->_id, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '229b08c9');

        // TODO

        return false;*/
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
        //return false;
    }

    /**
     * Transmet la protection de l'objet à une entité.
     *
     * @param $entity entity|string
     * @return boolean
     */
    public function shareProtectionTo($entity): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return false; // FIXME disabled!

        /*if (is_string($entity)) {
            $entity = $this->_nebuleInstance->newNode($entity, \Nebule\Library\Cache::TYPE_ENTITY);
        }
        if (!is_a($entity, 'Nebule\Library\Entity'))
            $entity = $this->_nebuleInstance->newNode($entity->getID(), \Nebule\Library\Cache::TYPE_ENTITY);
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

        $this->_metrology->addLog('Set protected to ' . $entity->getID(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'eb194721');

        // Lit la clé chiffrée. @todo à remplacer par getContent ...
        $limit = $this->_configuration->getOptionUntyped('ioReadMaxData');
        $codeKey = $this->_nebuleInstance->getIoInstance()->getObject($this->_idProtectedKey, $limit);
        // Calcul l'empreinte de la clé chiffrée.
        $hash = $this->_nebuleInstance->getFromDataNID($codeKey);
        if ($hash != $this->_idProtectedKey) {
            $this->_metrology->addLog('Error get protected key content : ' . $this->_idProtectedKey, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'ac1493b0');
            $this->_metrology->addLog('Protected key content hash : ' . $hash, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '0f7aa932');
            return false;
        }

        // Déchiffrement (asymétrique) de la clé de chiffrement du contenu.
        $key = $this->_entitiesInstance->getCurrentEntityInstance()->decrypt($codeKey);
        // Calcul l'empreinte de la clé.
        $hash = $this->_nebuleInstance->getFromDataNID($key);
        if ($hash != $this->_idUnprotectedKey) {
            $this->_metrology->addLog('Error get unprotected key content : ' . $this->_idUnprotectedKey, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'eda92267');
            $this->_metrology->addLog('Unprotected key content hash : ' . $hash, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '11b0f733');
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
        $this->_metrology->addLog('Protect object, code key : ' . $codeKeyID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '80048058');

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
            $meta = $this->_nebuleInstance->getFromDataNID('nebule/objet/type');
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

        return true;*/
    }

    /**
     * Transmet l'annulation de la protection de l'objet à une entité.
     * Ne marche pas si il y a eu plusieurs protections/déprotections/protections/... !!!
     * @param $entity entity|string
     * @return boolean
     * @todo
     *
     */
    public function cancelShareProtectionTo($entity, string $socialClass = ''): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return false; // FIXME disabled!

        /*if (is_string($entity))
            $entity = $this->_nebuleInstance->newNode($entity, \Nebule\Library\Cache::TYPE_ENTITY);
        if (!is_a($entity, 'Nebule\Library\Entity'))
            $entity = $this->_nebuleInstance->newNode($entity->getID(), \Nebule\Library\Cache::TYPE_ENTITY);
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

        $this->_metrology->addLog('Cancel share protection to ' . $entity->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2bb8215d');

        // Recherche l'objet de clé de chiffrement pour l'entité.
        $links = array();
        $filter = array(
            'bl/rl/req' => 'k',
            'bl/rl/nid1' => $this->_idUnprotectedKey,
            'bl/rl/nid3' => $entity->getID(),
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, $socialClass, false);

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
                $object = $this->_cacheInstance->newNode($idProtectedKey);
                //$object->deleteObject();
                $signerLinks = array();
                $this->getLinks($signerLinks, array(), $socialClass, false);
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

        return true;*/
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
    public function getMarkEmotionList(string $emotion, string $socialClass = '', string $context = ''): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $list = array();

        // Nettoyage du contexte.
        if (is_a($context, 'Nebule\Library\Entity')
            || is_a($context, 'Nebule\Library\Node')
            || is_a($context, 'Nebule\Library\Group')
            #|| is_a($context, 'Nebule\Library\conversation')
        )
            $context = $context->getID();
        if (!is_string($context)
            || $context == '0'
            || !ctype_xdigit($context)
        )
            $context = '';

        // Vérifie que l'émotion existe.
        if ($emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET
        )
            return $list;

        $hashEmotion = $this->_nebuleInstance->getFromDataNID($emotion);

        // Liste les liens à la recherche de la propriété.
        $list = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid2' => $hashEmotion,
            'bl/rl/nid3' => $context,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, $socialClass);

        // Nettoyage.
        foreach ($list as $i => $link) {
            // Si méta à 0, supprime le lien.
            if ($link->getParsed()['bl/rl/nid3'] == '0')
                unset($list[$i]);
        }
        unset($link);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_configurationInstance->getOptionAsBoolean('permitListOtherHash')
        ) {
            // A faire...
        }

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $result = array();

        $result[References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE] = $this->getMarkEmotion(References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE, $socialClass, $context);
        $result[References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE] = $this->getMarkEmotion(References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE, $socialClass, $context);
        $result[References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR] = $this->getMarkEmotion(References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR, $socialClass, $context);
        $result[References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE] = $this->getMarkEmotion(References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE, $socialClass, $context);
        $result[References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE] = $this->getMarkEmotion(References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE, $socialClass, $context);
        $result[References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT] = $this->getMarkEmotion(References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT, $socialClass, $context);
        $result[References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE] = $this->getMarkEmotion(References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE, $socialClass, $context);
        $result[References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET] = $this->getMarkEmotion(References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET, $socialClass, $context);

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
        return false; // FIXME disabled

        /*$this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Vérifie que l'émotion existe.
        if ($emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET
        ) {
            return false;
        }

        // Nettoyage du contexte.
        if (is_a($context, 'Nebule\Library\Entity')
            || is_a($context, 'Nebule\Library\Node')
            || is_a($context, 'Nebule\Library\Group')
            #|| is_a($context, 'Nebule\Library\conversation')
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
        $target = $this->_nebuleInstance->getFromDataNID($emotion);
        $meta = $context;
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();
        if ($obfuscate)
            $newLink->setObfuscate();
        $newLink->write();

        return true;*/
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
        return false; // FIXME disabled

        /*$this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Vérifie que l'émotion existe.
        if ($emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE
            && $emotion != References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET
        )
            return false;

        // Nettoyage de l'entité demandé.
        if (is_a($context, 'Nebule\Library\Entity')
            || is_a($context, 'Nebule\Library\Node')
            || is_a($context, 'Nebule\Library\Group')
            #|| is_a($context, 'Nebule\Library\conversation')
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
        $target = $this->_nebuleInstance->getFromDataNID($emotion);
        $meta = $entity;
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();
        if ($obfuscate)
            $newLink->setObfuscate();
        $newLink->write();

        return true;*/
    }


    /**
     * Lit à quelles entités à été transmis la protection de l'objet.
     *
     * @return array:string
     */
    public function getProtectedTo(string $socialClass = ''): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return array(); // FIXME disabled!
        // FIXME manage x links

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
        $this->getLinks($linksSym, $filter, $socialClass, false);
        foreach ($linksSym as $linkSym) {
            // Si lien de chiffrement.
            if ($linkSym->getParsed()['bl/rl/nid3'] != '0') {
                // Lit l'objet de clé de chiffrement symétrique et ses liens.
                $instanceSym = $this->_cacheInstance->newNode($linkSym->getParsed()['bl/rl/nid3']);
                $linksAsym = array();
                $filter = array(
                    'bl/rl/req' => 'k',
                    'bl/rl/nid1' => $linkSym->getParsed()['bl/rl/nid3'],
                    'bl/rl/nid4' => '',
                );
                $this->getLinks($linksAsym, $filter, $socialClass, false);
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_haveData)
            return true;

        if (!$this->_ioInstance->checkObjectPresent($this->_id))
            return false;

        // Si c'est l'objet 0, le supprime.
        if ($this->_id == '0' || !$this->checkNID($this->_id)) {
            $this->_data = null;
            $this->_metrologyInstance->addLog('Delete object 0', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2644d3a7');
            $nid = '0';
            $this->_ioInstance->unsetObject($nid);
            return false;
        }

        if ($this->_configurationInstance->getOptionAsBoolean('permitSynchronizeLink'))
            $this->syncLinks(false);

        if ($this->_configurationInstance->getOptionAsBoolean('permitDeleteObjectOnUnknownHash'))
            $hashAlgo = $this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm');
        else
            return false;

        // Extrait le contenu de l'objet, si possible.
        $this->_metrologyInstance->addObjectRead(); // Metrologie.
        $this->_data = $this->_ioInstance->getObject($this->_id);
        if ($this->_data === false) {
            $this->_metrologyInstance->addLog('Cant read object ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __METHOD__, '4f299627');
            $this->_data = null;
            return false;
        }
        $limit = $this->_configurationInstance->getOptionUntyped('DEFAULT_IO_READ_MAX_DATA');
        $this->_metrologyInstance->addLog('Object size ' . $this->_id . ' ' . strlen($this->_data) . '/' . $limit, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1239f93e');

        // Vérifie la taille.
        if (strlen($this->_data) > $limit) {
            $this->_data = null;
            return false;
        }

        // Calcul l'empreinte.
        $hash = $this->_nebuleInstance->getFromDataNID($this->_data, $hashAlgo);
        if ($hash == $this->_id) // Si l'objet est valide.
        {
            $this->_metrologyInstance->addObjectVerify(); // Metrologie.
            $this->_haveData = true;
            return true;
        }

        // Si la vérification est désactivée, quitte.
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckObjectHash')) {
            $this->_metrologyInstance->addLog('Warning - Invalid object hash ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'fddb0694');
            $this->_haveData = true;
            return true;
        }

        // Sinon l'objet est présent mais invalide, le supprime.
        $this->_data = null;
        $this->_metrologyInstance->addLog('Delete unconsistency object ' . $this->_id . ' ' . $hashAlgo . ':' . $hash, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '928d8203');
        $this->_ioInstance->unsetObject($this->_id);
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_haveData)
            return $this->_data; // FIXME gérer aussi la taille dans le cache !

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_haveData)
            return $this->_data;

        if (!$this->_ioInstance->checkObjectPresent($this->_id))
            return null;

        // Si c'est l'objet 0, le supprime.
        if ($this->_id == '0' || !$this->checkNID($this->_id)) {
            $this->_data = null;
            $this->_metrologyInstance->addLog('Delete object 0', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'f8873320');
            $nid = '0';
            $this->_ioInstance->unsetObject($nid);
            return null;
        }

        if ($this->_configurationInstance->getOptionAsBoolean('permitSynchronizeLink'))
            $this->syncLinks(false);

        if ($this->_configurationInstance->getOptionAsBoolean('permitDeleteObjectOnUnknownHash'))
            $hashAlgo = $this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm');
        else
            return null;

        // Prépare la limite de lecture.
        $maxLimit = $this->_configurationInstance->getOptionAsInteger('ioReadMaxData');
        if ($limit == 0
            || $limit > $maxLimit
        )
            $limit = $maxLimit;

        // Extrait le contenu de l'objet, si possible.
        $this->_metrologyInstance->addObjectRead(); // Metrologie.
        $this->_data = $this->_ioInstance->getObject($this->_id, $limit);
        if ($this->_data === false) {
            $this->_metrologyInstance->addLog('Cant read object ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'aa7f02d2');
            $this->_data = null;
            return null;
        }
        $this->_metrologyInstance->addLog('Object read size ' . $this->_id . ' ' . strlen($this->_data) . '/' . $maxLimit, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b1ca0788');

        // Vérifie la taille. Si trop grand mais qu'une limite est imposé, quitte sans vérifier l'empreinte.
        if (strlen($this->_data) >= $limit
            && $limit < $maxLimit
        ) {
            $this->_data = null;
            return null;
        }

        // Calcul l'empreinte.
        $hash = $this->_nebuleInstance->getFromDataNID($this->_data, $hashAlgo);
        if ($hash == $this->_id) // Si l'objet est valide.
        {
            $this->_metrologyInstance->addObjectVerify(); // Metrologie.
            $this->_haveData = true;
            return $this->_data;
        }

        // Si la vérification est désactivée, quitte.
        if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckObjectHash')) {
            $this->_metrologyInstance->addLog('Warning - Invalid object hash ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd2e4f3be');
            $this->_haveData = true;
            return $this->_data;
        }

        // Sinon l'objet est présent mais invalide, le supprime.
        $this->_data = null;
        $this->_metrologyInstance->addLog('Delete unconsistency object ' . $this->_id . ' ' . $hashAlgo . ':' . $hash, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '8f30c4c0');
        $this->_ioInstance->unsetObject($this->_id);
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
//			$limit = $this->_configuration->getOptionAsString('ioReadMaxData');

        $permitTroncate = false; // @todo à retirer.

        $this->_metrologyInstance->addLog('Get protected content : ' . $this->_idUnprotected, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'f5b1872d');

        // Lit la clé chiffrée.
        $codeKey = $this->_ioInstance->getObject($this->_idProtectedKey, 0);
        // Calcul l'empreinte de la clé chiffrée.
        $hash = $this->_nebuleInstance->getFromDataNID($codeKey);
        if ($hash != $this->_idProtectedKey) {
            $this->_metrologyInstance->addLog('Error get protected key content : ' . $this->_idProtectedKey, Metrology::LOG_LEVEL_ERROR, __METHOD__, '21d5ead8');
            $this->_metrologyInstance->addLog('Protected key content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR, __METHOD__, '7f03457f');
            return null;
        }

        // Déchiffrement (asymétrique) de la clé de chiffrement du contenu.
        $key = $this->_entitiesInstance->getGhostEntityInstance()->decrypt($codeKey);
        // Calcul l'empreinte de la clé.
        $hash = $this->_nebuleInstance->getFromDataNID($key);
        if ($hash != $this->_idUnprotectedKey) {
            $this->_metrologyInstance->addLog('Error get unprotected key content : ' . $this->_idUnprotectedKey, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'daff00e0');
            $this->_metrologyInstance->addLog('Unprotected key content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'ee98026b');
            return null;
        }

        // Lit l'objet chiffré.
        $code = $this->_ioInstance->getObject($this->_idProtected, $limit);
        // Calcul l'empreinte des données.
        $hash = $this->_nebuleInstance->getFromDataNID($code);
        if ($hash != $this->_idProtected) {
            $this->_metrologyInstance->addLog('Error get protected data content : ' . $this->_idProtected, Metrology::LOG_LEVEL_ERROR, __METHOD__, '13f7b8f9');
            $this->_metrologyInstance->addLog('Protected data content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR, __METHOD__, '3998a68d');
            return null;
        }

        $data = $this->_cryptoInstance->decrypt($code, $this->_configurationInstance->getOptionAsString('cryptoSymmetricAlgorithm'), $key);
        // Calcul l'empreinte des données.
        $hash = $this->_nebuleInstance->getFromDataNID($data);
        if ($hash != $this->_idUnprotected) {
            $this->_metrologyInstance->addLog('Error get unprotected data content : ' . $this->_idUnprotected, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f4e5015c');
            $this->_metrologyInstance->addLog('Unprotected data content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR, __METHOD__, '5737927d');
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
        $this->_code = '';
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($id == ''
            || !$this->_ioInstance->checkObjectPresent($id)
        )
            return '';

        $instance = $this->_cacheInstance->newNode($id);
        $text = substr(trim(strtok(filter_var($instance->getContent(0), FILTER_SANITIZE_STRING), "\n")), 0, 1024);
        if (extension_loaded('mbstring'))
            $text = mb_convert_encoding($text, 'UTF-8');
        else
            $this->_metrologyInstance->addLog('mbstring extension not installed or activated!', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c2becfad');

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_ioInstance->checkObjectPresent($this->_id))
            return '';

        if ($limit == 0)
            $limit = $this->_configurationInstance->getOptionUntyped('ioReadMaxData');
        if ($limit < 4)
            $limit = 4;

        $text = trim(strtok(filter_var($this->getContent($limit), FILTER_SANITIZE_STRING), "\n"));
        if (extension_loaded('mbstring'))
            $text = mb_convert_encoding($text, 'UTF-8');
        else
            $this->_metrologyInstance->addLog('mbstring extension not installed or activated!', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c2becfad');

        if (strlen($text) > $limit)
            $text = substr($text, 0, ($limit - 3)) . '...';

        return $text;
    }

    /**
     * Lit le contenu de l'objet nebule, extrait une chaine de texte imprimable.
     *
     * @param integer $limit
     * @return string
     */
    public function readAsText(int $limit = 0): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_ioInstance->checkObjectPresent($this->_id))
            return '';
        if ($limit < 4)
            $limit = 4;

        $text = trim((string)filter_var($this->getContent($limit + 4), FILTER_SANITIZE_STRING));
        if (extension_loaded('mbstring'))
            $text = mb_convert_encoding($text, 'UTF-8');
        else
            $this->_metrologyInstance->addLog('mbstring extension not installed or activated!', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c2becfad');

        if (strlen($text) > $limit)
            $text = substr($text, 0, ($limit - 3)) . '...';
        return $text;
    }

    /**
     * Link - Read links, parse and filter each link.
     *
     * @param array  $links
     * @param array  $filter
     * @param string $socialClass
     * @param bool   $withInvalidLinks
     * @return void
     */
    public function getLinks(array &$links, array $filter, string $socialClass = '', bool $withInvalidLinks = false): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_id == '0'
            || !$this->_ioInstance->checkLinkPresent($this->_id)
        )
            return;

        $lines = $this->_ioInstance->getBlockLinks($this->_id, '', 0);
        if (sizeof($lines) == 0)
            return;

        if (!$this->_configurationInstance->getOptionAsBoolean('permitListInvalidLinks'))
            $withInvalidLinks = false;

        foreach ($lines as $line)
        {
            $bloc = $this->_cacheInstance->newBlockLink($line, Cache::TYPE_BLOCLINK);
            if ($bloc->getValidStructure()
                && ( $bloc->getValid() || $withInvalidLinks )
            ) {
                $newLinks = $bloc->getLinks(); // FIXME
                $this->_filterLinksByStructure($newLinks, $filter);
                $links = array_merge($links, $newLinks);
            }
            else
                $this->_cacheInstance->unsetOnCache($line, Cache::TYPE_BLOCLINK);
        }

        $this->_socialInstance->arraySocialFilter($links, $socialClass);
        $this->_filterLinksByX($links);
    }

    /*
     * Link - Read links, parse, filter and socially select each link.
     * If $social is empty or invalid, do not apply social filter.
     *
     * @param array  $links
     * @param array  $filter
     * @param string $socialClass
     * @return void
     */
    public function getSocialLinks(array &$links, array $filter, string $socialClass = ''): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->getLinks($links, $filter, $socialClass);
        $this->_socialInstance->arraySocialFilter($links, $socialClass);
    }

    protected function _filterLinksByX(array &$links): void {
        foreach ($links as $k => $r) {
            // FIXME manage x links
            if ($r->getParsed('bl/rl/req'))
                unset($k);
        }
    }

    /**
     * @param array $links
     * @param array $filter
     * @return void
     */
    protected function _filterLinksByStructure(array &$links, array $filter): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
     * @param LinkRegister $link
     * @param array        $filter
     * @return bool
     */
    protected function _filterLinkByStructure(LinkRegister &$link, array $filter): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $parsedLink = $link->getParsed();

        foreach ($filter as $fieldName => $filterValue)
        {
            $fieldValue = '';
            if (isset($parsedLink[$fieldName]))
                $fieldValue = $parsedLink[$fieldName];

            if (is_array($filterValue))
                $listFilterValues = $filterValue;
            elseif (is_string($filterValue))
                $listFilterValues = array($filterValue);
            else
                continue;

            if ($fieldName == 'bl/rl/req')
                $listFilterValues[] = 'x';

            $onList = false;
            foreach ($listFilterValues as $value)
            {
                if ($fieldValue == $value) {
                    $onList = true;
                    break;
                }
            }
            if (! $onList)
                return false;
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('get links for nid=' . $this->getID(), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_ioInstance->checkLinkPresent($this->_id))
            return array();

        $links = array();
        $filter = array();

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
        if ($nid4 != '')
            $filter['bl/rl/nid4'] = $nid4;

        $this->getLinks($links, $filter, '', false);
        return $links;
    }

    /**
     * Try to find an update NID for the current NID. Empty if not found.
     *
     * @param boolean $present
     * @param boolean $synchro
     * @param string  $social
     * @return string
     */
    public function getUpdateNID(bool $present = true, bool $synchro = false, string $social = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
     * @param string $socialClass
     * @param bool   $present
     * @param bool   $synchro
     * @return string
     */
    private function _findUpdate(string $nid, int $level, array &$exclude, string $socialClass, bool $present = true, bool $synchro = false): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $level++;
        $links = array();
        if ($level < $this->_configurationInstance->getOptionAsInteger('linkMaxFollowedUpdates'))
        {
            $filter = array(
                'bl/rl/req' => 'u',
                'bl/rl/nid1' => $nid,
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
            );
            $this->getLinks($links, $filter, $socialClass);
            $this->_arrayDateSort($links);
        }

        foreach ($links as $link)
        {
            $nid2 = $link->getParsed()['bl/rl/nid2'];
            $nid2Update = '';
            if (!isset($exclude[$nid2]))
                $nid2Update = $this->_findUpdate($nid2, $level, $exclude, $socialClass, $present, $synchro);
            if ($nid2Update != '')
                return $nid2Update;
            $exclude[$nid2] = null;
        }

        if (!$present || $this->_ioInstance->checkObjectPresent($nid))
            return $nid;
        elseif ($synchro) {
            $instance = $this->_cacheInstance->newNode($nid);
            $instance->syncObject();
            if ($this->_ioInstance->checkObjectPresent($nid))
                return $nid;
        }
        return '';
    }



    private function _getReferencedByLinks(string $reference = '', string $socialClass = ''): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if ($reference == '')
            $reference = References::REFERENCE_NEBULE_REFERENCE;

        if (!self::checkNID($reference))
            $reference = $this->_nebuleInstance->getFromDataNID($reference);

        $list = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $this->_id,
            'bl/rl/nid3' => $reference,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, $socialClass, false);

        return $list;
    }

    private function _getReferenceToLinks(string $reference = '', string $socialClass = ''): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if ($reference == '')
            $reference = References::REFERENCE_NEBULE_REFERENCE;

        if (!self::checkNID($reference))
            $reference = $this->_nebuleInstance->getFromDataNID($reference);

        $list = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid2' => $this->_id,
            'bl/rl/nid3' => $reference,
            'bl/rl/nid4' => '',
        );
        $this->getLinks($list, $filter, $socialClass, false);

        return $list;
    }

    public function getReferencedLinks(string $reference = '', string $socialClass = ''): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $links = $this->_getReferencedByLinks($reference);
        $this->_socialInstance->arraySocialFilter($links, $socialClass);
        return $links;
    }

    public function getReferencedNID(string $reference = '', string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $links = $this->getReferencedLinks($reference, $socialClass);
        if (sizeof($links) == 0)
            return '';

        $link = end($links);
        if (!is_a($link, '\Nebule\Library\LinkRegister'))
            return '';
        return $link->getParsed()['bl/rl/nid2'];
    }

    public function getReferencedOrSelfNID(string $reference = '', string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $nid = $this->getReferencedNID($reference, $socialClass);
        if ($nid == '')
            return $this->_id;
        return $nid;
    }

    public function getReferencedSID(string $reference = '', string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $links = $this->getReferencedLinks($reference, $socialClass);
        $link = end($links);
        if (!is_a($link, '\Nebule\Library\LinkRegister'))
            return '';
        return $link->getParsed()['bs/rs1/eid'];
    }

    public function getReferencedListSID(string $reference = '', string $socialClass = ''): array
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $links = $this->getReferencedLinks($reference, $socialClass);
        $link = end($links);
        $list = array();
        $listOK = array();
        if (!is_a($link, '\Nebule\Library\LinkRegister'))
            return array();
        foreach ($links as $link) {
            if (!isset($listOK[$link->getParsed()['bs/rs1/eid']])) {
                $list[] = $link->getParsed()['bs/rs1/eid'];
                $listOK[$link->getParsed()['bs/rs1/eid']] = true;
            }
        }
        return $list;
    }

    /**
     * Cherche l'instance de l'objet par référence.
     * Si pas trouvé, retourne l'instance de l'objet sur lequel s'effectue la recherche.
     * Si le type de référence $reference n'est pas précisée, utilise References::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return Node
     */
    public function getReferencedObjectInstance(string $reference = '', string $socialClass = ''): Node
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions ref=' . $reference, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getTypedInstanceFromNID($this->getReferencedOrSelfNID($reference, $socialClass));
    }

    /**
     * Cherche si l'objet est une référence.
     * Si le type de référence $reference n'est pas précisée, utilise References::REFERENCE_NEBULE_REFERENCE.
     * Les références sont converties en hash en hexadécimal.
     * Si la référence est un texte en hexadécimal, c'est-à-dire un ID d'objet, alors c'est utilisé directement.
     *
     * @param string $reference
     * @param string $socialClass
     * @return boolean
     */
    public function getIsReferencedBy(string $reference = '', string $socialClass = 'all'): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $links = $this->_getReferencedByLinks($reference);

        if (sizeof($links) == 0)
            return false;

        $this->_socialInstance->arraySocialFilter($links, $socialClass);

        if (sizeof($links) == 0)
            return false;
        return true;
    }

    /**
     * Cherche si l'objet est référencé par une autre objet.
     * Si le type de référence $reference n'est pas précisée, utilise References::REFERENCE_NEBULE_REFERENCE.
     * Les références sont converties en hash en hexadécimal.
     * Si la référence est un texte en hexadécimal, c'est-à-dire un ID d'objet, alors c'est utilisé directement.
     *
     * @param string $reference
     * @param string $socialClass
     * @return boolean
     */
    public function getIsReferenceTo(string $reference = '', string $socialClass = 'all'): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferenceToLinks($reference);

        if (sizeof($links) == 0)
            return false;

        // Fait un tri par pertinence sociale.
        $this->_socialInstance->arraySocialFilter($links, $socialClass);

        if (sizeof($links) == 0)
            return false;
        return true;
    }

    /**
     * Cherche l'ID de l'objet qui référence l'objet courant.
     * Si pas trouvé, retourne l'ID de l'objet sur lequel s'effectue la recherche.
     * Si le type de référence $reference n'est pas précisée, utilise References::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return string
     */
    public function getReferenceToObjectID(string $reference = '', string $socialClass = ''): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferenceToLinks($reference);

        if (sizeof($links) == 0)
            return $this->_id;

        // Fait un tri par pertinence sociale.
        $this->_socialInstance->arraySocialFilter($links, $socialClass);

        // Extrait le dernier de la liste.
        $link = end($links);
        unset($links);

        if (!is_a($link, 'Nebule\Library\LinkRegister'))
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
    public function syncObject(bool $hardSync = false, string $socialClass = ''): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($hardSync !== true)
            $hardSync = false;

        // Vérifie que l'objet ne soit pas déjà présent.
        if ($this->_ioInstance->checkObjectPresent($this->_id))
            return true;

        // Vérifie si autorisé.
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWriteObject','permitSynchronizeObject')))
            return false;

        // Liste les liens à la recherche de la propriété de localisation.
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' =>  $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION),
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, $socialClass, false);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($links) == 0
            && $this->_configurationInstance->getOptionAsBoolean('permitListOtherHash')
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
            $data = $this->_ioInstance->getObject($this->_id, 0, $localisation);
            // Ecriture de l'objet.
            $this->_ioInstance->setObject($this->_id, $data);
        }

        unset($localisations, $localisation);
        return true;
    }

    private $syncLinkAntiLoop = false;

    /**
     * Synchronisation des liens de l'objet.
     *
     * @param boolean $hardSync
     * @return boolean
     * @todo
     */
    public function syncLinks(bool $hardSync = false, string $socialClass = ''): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Anti loop.
        if ($this->syncLinkAntiLoop)
            return false;
        $this->syncLinkAntiLoop = true;

        // Vérifie si autorisé.
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWriteLink','permitSynchronizeLink')))
            return false;

        // Liste les liens à la recherche de la propriété de localisation.
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_nebuleInstance->getFromDataNID('nebule/objet/entite/localisation'),
            'bl/rl/nid4' => '',
        );
        $this->getLinks($links, $filter, $socialClass, false);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        /*if (sizeof($links) == 0
            && $this->_configuration->getOptionAsBoolean('permitListOtherHash')
        ) {
            // TODO
        }*/
        if (sizeof($links) == 0)
            return false;

        // Fait un tri par pertinance sociale.
        // A faire...

        // Extrait le contenu des objets de propriété.
        foreach ($links as $i => $l)
            $localisations[$i] = $this->_readOneLineOtherObject($l->getParsed()['bl/rl/nid2']);

        // Synchronisation
        foreach ($localisations as $localisation) {
            $links = $this->_ioInstance->getBlockLinks($this->_id, $localisation);
            $this->_metrologyInstance->addLog('Object links count read ' . $this->_id . ' ' . sizeof($links), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '88e0a52f');

            foreach ($links as $link) {
                $linkInstance = $this->_cacheInstance->newBlockLink($link);
                $linkInstance->write();
            }
        }

        $this->syncLinkAntiLoop = false;
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $deleteObject = true;

        // Détecte si l'objet est protégé.
        $this->_getMarkProtected();
        $protected = ($this->_markProtectedChecked && $this->_cacheMarkProtected);
        if ($protected)
            $id = $this->_idUnprotected;
        else
            $id = $this->_id;

        $this->writeLink('d>' . $this->_id);

        // Lit les liens.
        $links = array();
        $this->getLinks($links, array(), 'all', false);
        $entity = $this->_entitiesInstance->getGhostEntityEID();
        foreach ($links as $link) {
            // Vérifie si l'entité signataire du lien est l'entité courante.
            if ($link->getParsed()['bs/rs1/eid'] != $entity) {
                // Si ce n'est pas l'entité courante, quitte.
                $this->_metrologyInstance->addAction('delobj', $id, false);
                $deleteObject = false;
            }
        }

        if ($deleteObject) {
            // Supprime l'objet.
            $r1 = $this->_ioInstance->unsetObject($id); // FIXME declare vars r1 r2
            $r2 = true;

            // Métrologie.
            $this->_metrologyInstance->addAction('delobj', $id, $r1);
        }

        // Si protégé.
        if ($protected) {
            $this->_metrologyInstance->addLog('Delete protected object ' . $this->_id, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'df362406');
            $id = $this->_idProtected;

            $this->writeLink('d>' . $this->_id);

            // Lit les liens.
            $links = array();
            $this->getLinks($links, array(), 'all', false);
            $entity = $this->_entitiesInstance->getGhostEntityEID();
            foreach ($links as $link) {
                // Vérifie si l'entité signataire du lien est l'entité courante.
                if ($link->getParsed()['bs/rs1/eid'] != $entity) {
                    // Si ce n'est pas l'entité courante, quitte.
                    $this->_metrologyInstance->addAction('delobj', $id, false);
                    $deleteObject = false;
                }
            }

            if ($deleteObject) {
                // Supprime l'objet.
                $r2 = $this->_ioInstance->unsetObject($id);

                // Métrologie.
                $this->_metrologyInstance->addAction('delobj', $id, $r2);
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->writeLink('d>' . $this->_id);

        $links = array();
        $this->getLinks($links, array(), 'all', false);
        $entity = $this->_entitiesInstance->getGhostEntityEID();
        foreach ($links as $link) {
            if ($link->getParsed()['bs/rs1/eid'] != $entity) {
                unset($links, $entity, $link);
                return false;
            }
        }

        unset($links, $entity, $link);

        $r = $this->_ioInstance->unsetObject($this->_id);

        $this->_metrologyInstance->addAction('delobj', $this->_id, $r);

        $this->_ioInstance->linksDelect($this->_id);

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->writeLink('d>' . $this->_id);

        $r = $this->_ioInstance->unsetObject($this->_id);

        $this->_metrologyInstance->addAction('delobj', $this->_id, $r);

        return $r;
    }

    /**
     * Supprime un objet et ses liens.
     * Force l'opération si l'entité est autorisée à le faire.
     */
    public function deleteForceObjectLinks()
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Supprime l'objet.
        $this->_ioInstance->unsetObject($this->_id);

        // Supprime les liens de l'objet.
        $this->_ioInstance->flushLinks($this->_id);
    }

    /**
     * Trie un tableau de liens en fonction des dates des liens.
     *
     * @param array $links
     * @return boolean
     */
    protected function _arrayDateSort(array &$links): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite','permitWriteObject'))) {
            $this->_metrologyInstance->addLog('Write object no authorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f5869664');
            return false;
        } // @TODO

        // Si protégé.
        if ($this->_cacheMarkProtected) {
            $this->_metrologyInstance->addAction('addobj', $this->_id, false);
            $this->_metrologyInstance->addLog('Write objet error, protected.', Metrology::LOG_LEVEL_ERROR, __METHOD__, '3b3255d5');
            return false;
        }

        // Si pas de données.
        if (!$this->_haveData) {
            $this->_metrologyInstance->addAction('addobj', '0', false);
            $this->_metrologyInstance->addLog('Write objet error, no data.', Metrology::LOG_LEVEL_ERROR, __METHOD__, '41f84b7f');
            return false;
        }

        $ok = $this->_ioInstance->setObject($this->_id, $this->_data);

        // vide les données.
        if (strlen($this->_data) > 1000000)
        {
            $this->_data = '';
            $this->_haveData = false;
        }

        $this->_metrologyInstance->addAction('addobj', $this->_id, $ok);
        $this->_metrologyInstance->addLog('OK write objet ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cddb5083');

        return $ok;
    }

    public function writeLink(string $rl, bool $obfuscated = false, string $date = ''): bool
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('unlocked','permitWrite','permitWriteLink'))) {
            $this->_metrologyInstance->addLog('Write link not authorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, '48b7be4e');
            return false;
        }

        if ($rl == '')
            return false;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($rl);
        //if ($obfuscated && !$newLink->setObfuscate()) FIXME obfuscation
        //    return false;
        return $newBlockLink->signwrite($this->_entitiesInstance->getGhostEntityInstance(), $date);
    }


    /**
     * Affiche la partie du menu de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationTitles(): void
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
                <li><a href="#ooi">OOI / Implémentation</a>
                    <ul>
                        <li><a href="#ooio">OOIO / Implémentation des Options</a></li>
                        <li><a href="#ooia">OOIA / Implémentation des Actions</a></li>
                    </ul>
                </li>
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
                <li><a href="#ori">ORI / Implémentation</a>
                    <ul>
                        <li><a href="#orio">ORIO / Implémentation des Options</a></li>
                        <li><a href="#oria">ORIA / Implémentation des Actions</a></li>
                    </ul>
                </li>
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
    static public function echoDocumentationCore(): void
    {
        ?>

        <?php Displays::docDispTitle(1, 'o', 'Objet'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <p>L'objet est le contenant de toutes les informations.</p>

        <?php Displays::docDispTitle(2, 'oo', 'Object'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’objet est un agglomérat de données numériques.</p>
        <p>Un objet numérique est identifié par une empreinte ou condensat (hash) numérique de type cryptographique.
            Cette empreinte est à même d'empêcher la modification du contenu d'un objet, intentionnellement ou non (cf
            <a href="#co">CO</a>).</p>

        <?php Displays::docDispTitle(3, 'oon', 'Nommage'); ?>
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

        <?php Displays::docDispTitle(3, 'oop', 'Protection'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La protection d'un objet va permettre de cacher le contenu de l'objet.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(3, 'ood', 'Dissimulation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La dissimulation des liens d'un objet va permettre de cacher la présence ou l'usage d'un objet.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(3, 'ool', 'Liens'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'ooc', 'Création'); ?>
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

        <?php Displays::docDispTitle(3, 'oos', 'Stockage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Tous les contenus des objets sont stockés dans un même emplacement où sont visibles comme étant dans un même
            emplacement. Cet emplacement ne contient pas les liens (CF <a href="#ls">LS</a>).</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(4, 'oosa', 'Arborescence'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Sur un système de fichiers, tous les contenus des objets sont stockés dans des fichiers contenus dans le
            dossier <code>pub/o/</code> (<code>o</code> comme objet).</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(3, 'oot', 'Transfert'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oor', 'Réservation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les différents objets réservés pour les besoins de la bibliothèque nebule :</p>
        <ul>
            <?php
            $list = References::NODE_REFERENCES;
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

        <?php Displays::docDispTitle(3, 'ooi', 'Implémentation'); ?>
        <?php Displays::docDispTitle(4, 'ooio', 'Implémentation des Options'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'ooia', 'Implémentation des Actions'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oov', 'Vérification'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’empreinte d’un objet doit être vérifiée lors de la fin de la réception de l’objet. L’empreinte d’un objet
            devrait être vérifiée avant chaque utilisation de cet objet. Un contenu d'objet avec une empreinte qui ne
            lui correspond pas doit être supprimé. Lors de la suppression d’un objet, les liens de cet objet ne sont pas
            supprimés. La vérification de la validité des liens est complètement indépendante de celle des objets, et
            inversement (CF <a href="#co">CO</a> et <a href="#lv">LV</a>).</p>

        <?php Displays::docDispTitle(3, 'ooo', 'Oubli'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L'oubli volontaire de certains liens et objets n'est encore ni théorisé ni implémenté, mais deviendra
            indispensable lorsque l'espace viendra à manquer (CF <a href="#cn">CN</a>).</p>

        <?php Displays::docDispTitle(2, 'or', 'Référence'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'orn', 'Nommage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'orp', 'Protection'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'ord', 'Dissimulation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'orl', 'Liens'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'orc', 'Création'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Liste des liens à générer lors de la création d'une entité.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(3, 'ors', 'Stockage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <?php Displays::docDispTitle(3, 'ort', 'Transfert'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'orr', 'Réservation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'ori', 'Implémentation'); ?>
        <?php Displays::docDispTitle(4, 'orio', 'Implémentation des Options'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oria', 'Implémentation des Actions'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oro', 'Oubli'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L'oubli volontaire de certains liens et objets n'est encore ni théorisé ni implémenté, mais deviendra
            indispensable lorsque l'espace viendra à manquer (CF <a href="#cn">CN</a>).</p>

        <?php
    }
}
