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
 * - un texte contenant l'ID d'un objet, ou '0' si c'est un objet nebule à créer ;
 * - un texte contenant les données, si c'est un objet nebule à créer ;
 * - l'option de protection par défaut de l'objet à créer (booléen).
 *
 * L'ID d'un objet est forcément un texte en hexadécimal.
 * ------------------------------------------------------------------------------------------
 */
class Node
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
    protected nebule $_nebuleInstance;

    /**
     * Instance des I/O (entrées/sorties).
     *
     * @var ioInterface $_io
     */
    protected ioInterface $_io;

    /**
     * Instance de la cryptographie.
     *
     * @var CryptoInterface $_crypto
     */
    protected CryptoInterface $_crypto;

    /**
     * Instance sociale.
     *
     * @var SocialInterface $_social
     */
    protected SocialInterface $_social;

    /**
     * Instance de la métrologie.
     *
     * @var Metrology $_metrology
     */
    protected Metrology $_metrology;

    /**
     * Identifiant de l'objet.
     * Si à 0, l'objet est invalide.
     *
     * @var string $_id
     */
    protected string $_id;

    /**
     * Le nom complet.
     * Forme : prénom préfix/nom.suffix surnom
     *
     * @var string $_fullname
     */
    protected string $_fullname;

    /**
     * Cache.
     *
     * @var array $_cachePropertyLink
     */
    protected array $_cachePropertyLink = array();

    /**
     * Cache.
     *
     * @var array $_cachePropertiesLinks
     */
    protected array $_cachePropertiesLinks = array();

    /**
     * Cache.
     *
     * @var array $_cachePropertyID
     */
    protected array $_cachePropertyID = array();

    /**
     * Cache.
     *
     * @var array $_cachePropertiesID
     */
    protected array $_cachePropertiesID = array();

    /**
     * Cache.
     *
     * @var array $_cacheProperty
     */
    protected array $_cacheProperty = array();

    /**
     * Cache.
     *
     * @var array $_cacheProperties
     */
    protected array $_cacheProperties = array();

    /**
     * Cache.
     *
     * @var string $_cacheUpdate
     */
    protected string $_cacheUpdate = '';

    /**
     * Cache.
     *
     * @var boolean $_cacheMarkDanger
     */
    protected bool $_cacheMarkDanger = false;

    /**
     * Cache.
     *
     * @var boolean $_cacheMarkWarning
     */
    protected bool $_cacheMarkWarning = false;

    /**
     * Cache.
     *
     * @var boolean $_cacheMarkProtected
     */
    protected bool $_cacheMarkProtected = false;

    /**
     * Identifiant de l'objet ayant le contenu protégé (chiffré).
     *
     * @var string $_idProtected
     */
    protected string $_idProtected = '0';

    /**
     * Identifiant de l'objet ayant le contenu non protégé (en clair).
     *
     * @var string $_idUnprotected
     */
    protected string $_idUnprotected = '0';

    /**
     * Identifiant de l'objet ayant le contenu de la clé protégée de déchiffrement de l'objet.
     *
     * @var string $_idProtectedKey
     */
    protected string $_idProtectedKey = '0';

    /**
     * Identifiant de l'objet ayant le contenu de la clé non protégée de déchiffrement de l'objet.
     *
     * @var string $_idUnprotectedKey
     */
    protected string $_idUnprotectedKey = '0';

    /**
     * Marqueur de détection de la protection de l'objet.
     *
     * @var boolean $_markProtectedChecked
     */
    protected bool $_markProtectedChecked = false;

    /**
     *
     * @var bool $_cacheCurrentEntityUnlocked
     */
    protected bool|null $_cacheCurrentEntityUnlocked = null;

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

    /**
     * Constructeur.
     * Toujours transmettre l'instance de la librairie nebule.
     * Si l'objet existe, juste préciser l'ID de celui-ci.
     * Si c'est un nouvel objet à créer, mettre l'ID à 0 et transmettre les données du nouvel objet dans data.
     * Si c'est un nouvel objet, préciser si il doit être protégé tout de suite avec protect à true.
     *
     * @param nebule  $nebuleInstance
     * @param string  $id
     * @param string  $data
     * @param boolean $protect
     */
    public function __construct(nebule $nebuleInstance, string $id, string $data = '', bool $protect, bool $obfuscated = false)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();

        $id = trim(strtolower($id));

        if (is_string($id)
            && $id != '0'
            && $id != ''
            && ctype_xdigit($id)
        ) {
            $this->_id = $id;
            $this->_metrology->addLog('New instance object ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.
            $this->_getMarkProtected();
            $this->_cacheCurrentEntityUnlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

            // Pré-détermine certains contenus.
            $this->_getCommunContents();
        } elseif ($id == '0'
            && is_string($data)
            && $data != ''
        ) {
            $this->_createNewObject($data, $protect, $obfuscated);
            $this->_cacheCurrentEntityUnlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        } else {
            $this->_id = '0';
        }
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_id;
    }

    /**
     * Retourne les variables à sauvegarder dans la session php lors d'une mise en sommeil de l'instance.
     *
     * @return array:string
     */
    public function __sleep()
    {
        return self::SESSION_SAVED_VARS;
    }

    /**
     * Foncion de réveil de l'instance et de ré-initialisation de certaines variables non sauvegardées.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_cacheMarkDanger = false;
        $this->_cacheMarkWarning = false;
        $this->_data = '';
        $this->_haveData = false;
        $this->_cacheUpdate = '';

        // Pré-détermine certains contenus.
        $this->_getCommunContents();
    }

    protected function _createNewObject($data, $protect, $obfuscated)
    {
        $this->_metrology->addLog(__METHOD__, Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->_markProtectedChecked = false;

        // Vérifie que l'on puisse créer un objet.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // calcul l'ID.
            $this->_id = $this->_crypto->hash($data);
            if ($protect) {
                $this->_metrology->addLog('Create protected object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG);
            } else {
                $this->_metrology->addLog('Create object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG);
            }

            // Mémorise les données.
            $this->_data = $data;
            $this->_haveData = true;
            $data = null;

            // Création lien de hash.
            $signer = $this->_nebuleInstance->getCurrentEntity();
            $date = date(DATE_ATOM);

            // Création lien de hash.
            $date2 = $date;
            if ($obfuscated) {
                $date2 = '0';
            }
            $action = 'l';
            $source = $this->_id;
            $target = $this->_crypto->hash($this->_crypto->hashAlgorithmName());
            $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_HASH);
            $link = '0_' . $signer . '_' . $date2 . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new Link($this->_nebuleInstance, $link);
            $newLink->signWrite();

            // Création du lien d'annulation de suppression.
            $action = 'x';
            $source = $this->_id;
            $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_0_0';
            $newLink = new Link($this->_nebuleInstance, $link);
            $newLink->sign();
            if ($obfuscated) {
                $newLink->obfuscate();
            }
            $newLink->write();

            // Si l'objet doit être protégé.
            if ($protect) {
                $this->setProtected($obfuscated);
            } else {
                // Sinon écrit l'objet directement.
                $this->write();
            }
        } else {
            $this->_metrology->addLog('Create object error no autorized', Metrology::LOG_LEVEL_ERROR); // Log
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
    public function getID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_id;
    }

    /**
     * Retourne la couleur primaire de l'objet.
     *
     * @return string
     */
    public function getPrimaryColor()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return substr($this->_id . '000000', 0, 6);
    }

    /**
     * Retourne l'algorithme de hash.
     * @return string
     * @todo
     *
     */
    public function getHashAlgo()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $algo = $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_HASH, 'all');
        $this->_metrology->addLog('Object ' . $this->_id . ' hash = ' . $algo, Metrology::LOG_LEVEL_DEBUG); // Log

        if ($algo != '') {
            return $algo;
        }
        // else
        return '';
    }

    /**
     * Test si l'objet est présent.
     *
     * @return boolean
     */
    public function checkPresent()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $result = false;
        if ($this->_id == '0') {
            return false;
        }
        // Si l'objet est protégé.
        if ($this->_getMarkProtected()) {
            $result = $this->_nebuleInstance->getIO()->checkObjectPresent($this->_idProtected);
        } else {
            $result = $this->_nebuleInstance->getIO()->checkObjectPresent($this->_id);
        }
        return $result;
    }

    /**
     * Test si l'objet a des liens.
     *
     * @return boolean
     */
    public function checkObjectHaveLinks()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_io->checkLinkPresent($this->_id);
    }


    /**
     * Pré-détermine certains contenus.
     * Sans ces contenus, certaines fonctions ne se terminent pas bien pour ces objets.
     *
     * @return void
     */
    protected function _getCommunContents()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '5d5b09f6dcb2d53a5fffc60c4ac0d55fabdf556069d6631545f42aa6e3500f2e') {
            $this->_data = 'sha256';
            $this->_haveData = true;
        }
    }


    /**
     * Faire une recherche de liens type l en fonction de l'objet méta.
     * Typiquement utilisé pour une recherche de propriétés d'un objet.
     *
     * Fait une recherche sur de multiples algorithmes de hash au besoin.
     * @param array $list
     * @param string $meta
     * @todo
     *
     */
    private function _getLinksByMeta(&$list, $meta)
    {
        $this->_getLinksByMetaByHash($list, $meta);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_nebuleInstance->getOption('permitListOtherHash')
        ) {
            // A faire...
        }
    }

    /**
     * Faire une recherche de liens type l en fonction de l'objet méta et d'un algorithme de hash donné.
     *
     * @param array $list
     * @param string $meta
     * @param string $hashAlgo
     */
    private function _getLinksByMetaByHash(&$list, $meta)
    {
        $list = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $this->_id,
            '',
            $this->_crypto->hash($meta, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM)
        );
    }

    /**
     * Lit une propriété de l'objet nebule dans ses liens.
     * Retourne la liste des liens définissants la propriété.
     *
     * @param string $type
     * @param string $socialClass
     * @return array
     */
    public function getPropertiesLinks($type, $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($type == '') {
            return array();
        }

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cachePropertiesLinks[$type][$socialClass])) {
            return $this->_cachePropertiesLinks[$type][$socialClass];
        }

        // Liste les liens à la recherche de la propriété.
        $list = array();
        $this->_getLinksByMeta($list, $type);

        if (sizeof($list) == 0) {
            return array();
        }

        // Trie la liste, pour les liens venants de plusieurs objets.
        $date = array();
        foreach ($list as $k => $r) {
            $date[$k] = $r->getDate();
        }
        array_multisort($date, SORT_STRING, SORT_ASC, $list);

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($list, $socialClass);

        // Mémorise le résultat dans le cache.
        $this->_cachePropertiesLinks[$type][$socialClass] = $list;

        // Résultat.
        return $list;
    }

    /**
     * Lit une propriété de l'objet nebule dans ses liens.
     * Retourne le lien unique définissant la propriété.
     * Retourne une chaine vide si pas de lien de propriété trouvé.
     *
     * @param string $type
     * @param string $socialClass
     * @return link
     */
    public function getPropertyLink($type, $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($type == '') {
            return '';
        }

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cachePropertyLink[$type][$socialClass])) {
            return $this->_cachePropertyLink[$type][$socialClass];
        }

        $propertyLink = '';

        // Liste les liens à la recherche de la propriété.
        $list = $this->getPropertiesLinks($type, $socialClass);

        if (sizeof($list) == 0) {
            return '';
        }

        // Extrait le dernier de la liste.
        $propertyLink = end($list);
        unset($list);

        // Mémorise le résultat dans le cache.
        $this->_cachePropertyLink[$type][$socialClass] = $propertyLink;

        // Résultat.
        return $propertyLink;
    }

    /**
     * Lit une propriété de l'objet nebule dans ses liens.
     * Retourne une chaine de texte de _une seule_ ligne, ou une chaine vide si problème.
     *
     * @param string $type
     * @param string $socialClass
     * @return string
     */
    public function getPropertyID($type, $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($type == '') {
            return '';
        }

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cachePropertyID[$type][$socialClass])) {
            return $this->_cachePropertyID[$type][$socialClass];
        }

        $property = '';

        // Liste les liens à la recherche de la propriété.
        $link = $this->getPropertyLink($type, $socialClass);

        if (!is_a($link, 'link')) {
            return '';
        }

        // Extrait l'ID de l'objet de propriété.
        $property = $link->getHashTarget_disabled();
        unset($link);

        // Mémorise le résultat dans le cache.
        $this->_cachePropertyID[$type][$socialClass] = $property;

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
    public function getPropertiesID($type, $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($type == '') {
            return array();
        }

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cachePropertiesID[$type][$socialClass])) {
            return $this->_cachePropertiesID[$type][$socialClass];
        }

        $properties = array();

        // Liste les liens à la recherche de la propriété.
        $list = array();
        $this->_getLinksByMeta($list, $type);

        if (sizeof($list) == 0) {
            return array();
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($list, $socialClass);

        // Extrait les ID des objets de propriété.
        foreach ($list as $i => $l) {
            $properties[$i] = $l->getHashTarget();
        }
        unset($list);

        // Mémorise le résultat dans le cache.
        $this->_cachePropertiesID[$type][$socialClass] = $properties;

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
    public function getProperty($type, $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($type == '') {
            return '';
        }

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cacheProperty[$type][$socialClass])) {
            return $this->_cacheProperty[$type][$socialClass];
        }

        $property = '';

        // Liste les liens à la recherche de la propriété.
        $link = $this->getPropertyLink($type, $socialClass);

        if ($link == ''
            || !is_a($link, 'link')
        ) {
            return '';
        }

        // Extrait le contenu de l'objet de propriété.
        $property = $this->_readOneLineOtherObject($link->getHashTarget_disabled());
        unset($link);

        // Mémorise le résultat dans le cache.
        $this->_cacheProperty[$type][$socialClass] = $property;

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
    public function getProperties($type, $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($type == '') {
            return array();
        }

        // Si déjà recherché, donne le résultat en cache.
        if (isset($this->_cacheProperties[$type][$socialClass])) {
            return $this->_cacheProperties[$type][$socialClass];
        }

        $properties = array();

        // Liste les liens à la recherche de la propriété.
        $links = array();
        $this->_getLinksByMeta($links, $type);

        if (sizeof($links) == 0) {
            return array();
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Extrait le contenu des objets de propriété.
        foreach ($links as $i => $l) {
            $properties[$i] = $this->_readOneLineOtherObject($l->getHashTarget());
        }
        unset($links);

        // Mémorise le résultat dans le cache.
        if (sizeof($properties) != 0 && false) // @todo la mise en cache ne fonctionne pas !!!
        {
            $this->_cacheProperties[$type][$socialClass] = $properties;
        }

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
    public function getHaveProperty(string $type, string $property, string $socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Extrait la liste des propriétés.
        $list = $this->getProperties($type, $socialClass);

        // Cherche dans la liste la propriété de groupe.
        foreach ($list as $item) {
            if ($item == $property) {
                return true;
            }
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
    public function getPropertySigners($type = '')
    {
        $signers = array();

        // Si le type de l'objet est précisé, le converti en ID.
        if ($type != '') {
            $type = $this->_crypto->hash($type, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }

        $signers = array();

        // Extraction des entités signataires.
        $links = $this->getPropertiesLinks(nebule::REFERENCE_NEBULE_OBJET_TYPE, 'all');

        foreach ($links as $link) {
            if ($type == ''
                || ($type != ''
                    && $link->getHashTarget() == $type
                )
            ) {
                $signers[$link->getHashSigner()] = $link->getHashSigner();
            }
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
    public function getPropertySignedBy($entity, $type = '')
    {
        // Si le type de l'objet est précisé, le converti en ID.
        if ($type != '') {
            $type = $this->_crypto->hash($type, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }

        // extrait l'ID de l'entité si c'est un objet.
        if (is_a($entity, 'Node')) {
            $entity = $entity->getID();
        }

        // Extraction des entités signataires.
        $links = $this->getPropertiesLinks(nebule::REFERENCE_NEBULE_OBJET_TYPE, 'all');

        foreach ($links as $link) {
            if ($type == ''
                || ($type != ''
                    && $link->getHashTarget() == $type
                )
            ) {
                if ($link->getHashSigner() == $entity) {
                    return true;
                }
            }
        }
        unset($links);

        return false;
    }

    /**
     * Ecrit la propriété de l'objet correspondant au type.
     * @param string $type
     * @param string $property
     * @param boolean $protect
     * @param boolean $obfuscated
     * @return boolean
     * @todo
     *
     */
    public function setProperty(string $type, string $property, $protect = false, $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($type == '') {
            return false;
        }
        if ($property == '') {
            return false;
        }

        // Prépare l'objet de la propriété.
        $id = $this->_nebuleInstance->createTextAsObject($property, $protect, $obfuscated);
        if ($id === false) {
            return false;
        }

        // Création lien de propriété.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        $source = $this->_id;
        $target = $id;
        $meta = $this->_crypto->hash($type, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();
        if ($obfuscated) {
            $newLink->obfuscate();
        }
        $newLink->write();

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
        // @todo   ------------------------------------------------------------------------------- A revoir...
        $this->_cacheProperty = array();
        $this->_cacheProperties = array();

        // Résultat.
        return true;
    }

    /**
     * Lit l'ID du type mime.
     * @param string $socialClass
     * @return string
     */
    public function getTypeID($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getPropertyID(nebule::REFERENCE_NEBULE_OBJET_TYPE, $socialClass);
    }

    /**
     * Lit le type mime.
     * @param string $socialClass
     * @return string
     */
    public function getType($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, $socialClass);
    }

    /**
     * Ecriture du type mime.
     * @param string $type
     * @return boolean
     */
    public function setType($type)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, $type);
    }

    /**
     * Lit la taille.
     * @param string $socialClass
     * @return string
     */
    public function getSize($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return filter_var($this->getProperty(nebule::REFERENCE_NEBULE_OBJET_TAILLE, $socialClass), FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Lit l'empreinte homomorphe.
     * @param string $socialClass
     * @return string
     */
    public function getHomomorphe($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_HOMOMORPHE, $socialClass);
    }

    /**
     * Lit l'empreinte (ID).
     * @return string
     */
    public function getHash()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_id;
    }

    /**
     * Lit la date de création.
     * @param string $socialClass
     * @return string
     */
    public function getDate($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_DATE, $socialClass);
    }

    /**
     * Lecture du nom.
     * @param string $socialClass
     * @return string
     */
    public function getName($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $name = $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_NOM, $socialClass);
        if ($name == '') {
            // Si le nom n'est pas trouvé, retourne l'ID.
            $name = $this->_id;
        }
        return $name;
    }

    /**
     * Ecriture du nom.
     * @param string $name
     * @return boolean
     */
    public function setName($name)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_NOM, $name, $this->_getMarkProtected());
    }

    /**
     * Lecture du préfix.
     * @param string $socialClass
     * @return string
     */
    public function getPrefixName($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_PREFIX, $socialClass);
    }

    /**
     * Ecriture du préfix.
     * @param string $name
     * @return boolean
     */
    public function setPrefix($prefix)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_PREFIX, $prefix, $this->_getMarkProtected());
    }

    /**
     * Lecture du suffix.
     * @param string $socialClass
     * @return string
     */
    public function getSuffixName($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_SUFFIX, $socialClass);
    }

    /**
     * Ecriture du suffix.
     * @param string $name
     * @return boolean
     */
    public function setSuffix($suffix)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_SUFFIX, $suffix, $this->_getMarkProtected());
    }

    /**
     * Lecture du prénom.
     * @param string $socialClass
     * @return string
     */
    public function getFirstname($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_PRENOM, $socialClass);
    }

    /**
     * Ecriture du prénom.
     * @param string $name
     * @return boolean
     */
    public function setFirstname($firstname)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_SUFFIX, $firstname, $this->_getMarkProtected());
    }

    /**
     * Lecture du surnom.
     * @param string $socialClass
     * @return string
     */
    public function getSurname($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_SURNOM, $socialClass);
    }

    /**
     * Ecriture du surnom.
     * @param string $name
     * @return boolean
     */
    public function setSurname($surname)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->setProperty(nebule::REFERENCE_NEBULE_OBJET_SURNOM, $surname, $this->_getMarkProtected());
    }

    /**
     * Lecture du nom complet.
     *
     * @param string $socialClass
     * @return string
     */
    public function getFullName($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if (isset($this->_fullname)
            && trim($this->_fullname) != ''
        ) {
            return $this->_fullname;
        }

        // Recherche des éléments.
        $name = $this->getName($socialClass);
        $prefix = $this->getPrefixName($socialClass);
        $firstname = $this->getFirstname($socialClass);
        $suffix = $this->getSuffixName($socialClass);
        $surname = $this->getSurname($socialClass);

        // Reconstitution du nom complet : prénom préfixe/nom.suffixe surnom
        $fullname = $name;
        if ($prefix != '') {
            $fullname = $prefix . '/' . $fullname;
        }
        if ($suffix != '') {
            $fullname = $fullname . '.' . $suffix;
        }
        if ($firstname != '') {
            $fullname = $firstname . ' ' . $fullname;
        }
        if ($surname != '') {
            $fullname = $fullname . ' ' . $surname;
        }
        $this->_fullname = $fullname;

        // Resultat.
        return $fullname;
    }

    /**
     * Lecture des multiples localisations.
     *
     * @param string $socialClass
     * @return array
     */
    public function getLocalisations($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getProperties(nebule::REFERENCE_NEBULE_OBJET_LOCALISATION, $socialClass);
    }

    /**
     * Lecture de la localisation.
     * @param string $socialClass
     * @return string
     */
    public function getLocalisation($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_LOCALISATION, $socialClass);
    }

    /**
     * Ecriture de la localisation.
     * @param string $localisation
     * @return boolean
     */
    public function setLocalisation($localisation)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

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
    public function getIsEntity($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isEntity) {
            return true;
        }

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
    public function getIsGroup($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isGroup) {
            return true;
        }

        $this->_isGroup = $this->getHaveProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, nebule::REFERENCE_NEBULE_OBJET_GROUPE, $socialClass);

        return $this->_isGroup;
    }

    /**
     * Retourne la liste des liens vers les groupes dont l'objet est membre.
     *
     * @param string $socialClass
     * @return array:Link
     */
    public function getListIsMemberOnGroupLinks($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            '',
            $this->id,
            ''
        );

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getHashSource() != $link->getHashMeta()) {
                unset($links[$i]);
            }
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newGroup($link->getHashSource());
            if (!$instance->getIsGroup('all')) {
                unset($links[$i]);
            }
        }

        return $links;
    }

    /**
     * Retourne la liste des ID vers les groupes dont l'objet est membre.
     *
     * @param string $socialClass
     * @return array:string
     */
    public function getListIsMemberOnGroupID($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $list = array();

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            '',
            $this->id,
            ''
        );

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getHashSource() != $link->getHashMeta()) {
                unset($links[$i]);
            }
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newGroup($link->getHashSource());
            if ($instance->getIsGroup('all')) {
                $list[$link->getHashSource()] = $link->getHashSource();
            }
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
     * @return boolean
     */
    public function getIsConversation($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isConversation) {
            return true;
        }

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
    public function getListIsMemberOnConversationLinks($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            '',
            $this->id,
            ''
        );

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getHashSource() != $link->getHashMeta()) {
                unset($links[$i]);
            }
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newConversation($link->getHashSource());
            if (!$instance->getIsConversation('all')) {
                unset($links[$i]);
            }
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
    public function getListIsMemberOnConversationID($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $list = array();

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            '',
            $this->id,
            ''
        );

        // Tri sur les appartenances aux groupes ou équivalent.
        foreach ($links as $i => $link) {
            if ($link->getHashSource() != $link->getHashMeta()) {
                unset($links[$i]);
            }
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newConversation($link->getHashSource());
            if ($instance->getIsConversation('all')) {
                $list[$link->getHashSource()] = $link->getHashSource();
            }
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
     * @return boolean
     */
    public function getIsCurrency($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isCurrency) {
            return true;
        }

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
     * @return boolean
     */
    public function getIsTokenPool($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isTokenPool) {
            return true;
        }

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
     * @return boolean
     */
    public function getIsToken($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isToken) {
            return true;
        }

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
     * @return boolean
     */
    public function getIsWallet($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_isWallet) {
            return true;
        }

        $this->_isWallet = $this->getHaveProperty(nebule::REFERENCE_NEBULE_OBJET_TYPE, nebule::REFERENCE_NEBULE_OBJET_MONNAIE_PORTEFEUILLE, $socialClass);

        return $this->_isWallet;
    }


    /**
     * Lit les marques de Danger.
     * @return boolean
     * @todo
     *
     */
    public function getMarkDanger()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkDanger) {
            return true;
        }

        // Liste les liens à la recherche de la propriété.
        $list = $this->readLinksFilterFull(
            '',
            '',
            'f',
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_DANGER),
            $this->_id,
            '');

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_nebuleInstance->getOption('permitListOtherHash')
        ) {
            // A faire...
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($list);

        // Si pas marqué, tout va bien. Résultat négatif.
        if (sizeof($list) == 0) {
            return false;
        }

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
    public function setMarkDanger()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkDanger) {
            return true;
        }

        // Création lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        $source = $this->_id;
        $target = $this->_crypto->hash(nebule::REFERENCE_NEBULE_DANGER);
        $meta = '';
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->signWrite();

        $this->_cacheMarkDanger = true;
        return true;
    }

    /**
     * Lit les marques de Warning.
     * @return boolean
     * @todo
     *
     */
    public function getMarkWarning()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkWarning) {
            return true;
        }

        // Liste les liens à la recherche de la propriété.
        $list = $this->readLinksFilterFull(
            '',
            '',
            'f',
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_WARNING),
            $this->_id,
            '');

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_nebuleInstance->getOption('permitListOtherHash')
        ) {
            // A faire...
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($list);

        // Si pas marqué, tout va bien. Résultat négatif.
        if (sizeof($list) == 0) {
            return false;
        }

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
    public function setMarkWarning()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->_cacheMarkWarning) {
            return true;
        }

        // Création lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        $source = $this->_id;
        $target = $this->_crypto->hash(nebule::REFERENCE_NEBULE_WARNING);
        $meta = '';
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->signWrite();

        $this->_cacheMarkWarning = true;
        return true;
    }


    /**
     * Lit les marques de protection, c'est à dire un lien de chiffrement pour l'objet.
     * Fait une recherche complète.
     * @return boolean
     */
    public function getMarkProtected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->_getMarkProtected();
        return $this->_cacheMarkProtected;
    }

    /**
     * Lit les marques de protection, c'est à dire un lien de chiffrement pour l'objet.
     * Fait une recherche sommaire et rapide.
     * @return boolean
     */
    public function getMarkProtectedFast()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_markProtectedChecked === true) {
            return $this->_cacheMarkProtected;
        }

        if ($this->_id == '0') {
            return false;
        }

        // Liste les liens à la recherche de la propriété.
        $listS = $this->readLinksFilterFull('', '', 'k', $this->_id, '', '');
        $listT = $this->readLinksFilterFull('', '', 'k', '', $this->_id, '');

        // Si pas marqué, résultat négatif.
        if (sizeof($listS) == 0
            && sizeof($listT) == 0
        ) {
            return false;
        }
        return true;
    }

    /**
     * Lit les marques de protection, c'est à dire un lien de chiffrement pour l'objet.
     * Force la relecture de la marque de protection. A utiliser par exemple après une synchronisation.
     * @return boolean
     */
    public function getReloadMarkProtected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

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
    protected function _getMarkProtected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_markProtectedChecked === true
            && $this->_cacheCurrentEntityUnlocked === $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            if ($this->_cacheMarkProtected === true) {
                $this->_metrology->addLog('Object protected - cache - protected', Metrology::LOG_LEVEL_DEBUG); // Log
            } else {
                $this->_metrology->addLog('Object protected - cache - not protected', Metrology::LOG_LEVEL_DEBUG); // Log
            }
            return $this->_cacheMarkProtected;
        }

        if ($this->_id == '0') {
            return false;
        }

        // Mémorise l'état de connexion de l'entité courante.
        $this->_cacheCurrentEntityUnlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        // Liste les liens à la recherche de la propriété.
        $listS = $this->readLinksFilterFull(
            '',
            '',
            'k',
            $this->_id,
            '',
            '');
        $listT = $this->readLinksFilterFull(
            '',
            '',
            'k',
            '',
            $this->_id,
            '');

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($listS) == 0
            && $this->_nebuleInstance->getOption('permitListOtherHash')
        ) {
            // A faire...
        }
        if (sizeof($listT) == 0
            && $this->_nebuleInstance->getOption('permitListOtherHash')
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
            $this->_metrology->addLog('Object protected - not protected', Metrology::LOG_LEVEL_DEBUG); // Log
            return false;
        }

        // Sinon.
        $this->_markProtectedChecked = true;
        $result = false;

        if (sizeof($listS) == 0) {
            $this->_metrology->addLog('Object protected - id protected = ' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log
            $this->_idProtected = $this->_id;

            // Recherche la clé utilisée pour l'entité en cours.
            foreach ($listT as $linkSym) {
                // Si lien de chiffrement et l'objet source est l'objet en cours non protégé.
                if ($linkSym->getAction() == 'k'
                    && $linkSym->getHashTarget() == $this->_idProtected
                ) {
                    // Lit l'objet de clé de chiffrement symétrique et ses liens.
                    $instanceSym = $this->_nebuleInstance->newObject($linkSym->getHashMeta());
                    $linksAsym = $instanceSym->readLinksUnfiltred();
                    unset($instanceSym);
                    foreach ($linksAsym as $linkAsym) {
                        // Si lien de chiffrement.
                        $targetA = $linkAsym->getHashTarget();
                        if ($linkAsym->getAction() == 'k'
                            && $linkAsym->getHashTarget() != $this->_idProtected
                            && $this->_nebuleInstance->getIO()->checkObjectPresent($targetA)
                        ) {
                            $result = true;
                            $this->_idUnprotected = $linkSym->getHashSource();
                            $this->_metrology->addLog('Object protected - id unprotected = ' . $this->_idUnprotected, Metrology::LOG_LEVEL_DEBUG); // Log
                            $this->_idUnprotectedKey = $linkAsym->getHashSource();
                            if ($linkAsym->getHashMeta() == $this->_nebuleInstance->getCurrentEntity()) {
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
            $this->_metrology->addLog('Object protected - id unprotected = ' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log
            $this->_idUnprotected = $this->_id;

            // Recherche la clé utilisée pour l'entité en cours.
            foreach ($listS as $linkSym) {
                $targetS = $linkSym->getHashTarget();
                // Si lien de chiffrement et l'objet source est l'objet en cours non protégé.
                if ($linkSym->getAction() == 'k'
                    && $linkSym->getHashSource() == $this->_idUnprotected
                    && $this->_nebuleInstance->getIO()->checkObjectPresent($targetS)
                ) {
                    // Lit l'objet de clé de chiffrement symétrique et ses liens.
                    $instanceSym = $this->_nebuleInstance->newObject($linkSym->getHashMeta());
                    $linksAsym = $instanceSym->readLinksUnfiltred();
                    unset($instanceSym);
                    foreach ($linksAsym as $linkAsym) {
                        $targetA = $linkAsym->getHashTarget();
                        // Si lien de chiffrement.
                        if ($linkAsym->getAction() == 'k'
                            && $linkAsym->getHashSource() != $this->_idUnprotected
                            && $linkAsym->getHashMeta() == $this->_nebuleInstance->getCurrentEntity()
                            && $this->_nebuleInstance->getIO()->checkObjectPresent($targetA)
                        ) {
                            $result = true;
                            $this->_idProtected = $targetS;
                            $this->_metrology->addLog('Object protected - id protected = ' . $this->_idProtected, Metrology::LOG_LEVEL_DEBUG); // Log
                            $this->_idUnprotectedKey = $linkAsym->getHashSource();
                            if ($linkAsym->getHashMeta() == $this->_nebuleInstance->getCurrentEntity()) {
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
     * @return boolean
     */
    public function getProtectedID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->_getMarkProtected();
        return $this->_idProtected;
    }

    /**
     * Lit l'ID de l'objet non chiffré.
     * @return boolean
     */
    public function getUnprotectedID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->_getMarkProtected();
        return $this->_idUnprotected;
    }

    /**
     * Protège l'objet.
     *
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setProtected($obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return false;
        }
        if (!$this->_io->checkObjectPresent($this->_id)
            && !$this->_haveData
        ) {
            return false;
        }

        // Vérifie si pas déjà protégé.
        if ($this->_getMarkProtected()) {
            return true;
        }

        $this->_metrology->addLog('Ask protect object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG);

        // Vérifie que l'écriture d'objets et de liens est permise.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génération de la clé de chiffrement.
            // Doit être au maximum de la taille de la clé de l'entité cible (exprimé en bits) moins 11 octets.
            // CF : http://php.net/manual/fr/function.openssl-public-encrypt.php
            // @todo à faire pour le cas général.
            $keySize = self::CRYPTO_SESSION_KEY_SIZE; // En octets.
            $key = $this->_crypto->getStrongRandom($keySize);
            if (strlen($key) != $keySize) {
                return false;
            }
            $keyID = $this->_crypto->hash($key);
            $this->_metrology->addLog('Protect object, key : ' . $keyID, Metrology::LOG_LEVEL_DEBUG);

            // Si des donnnées sont disponibles, on les lit.
            if ($this->_haveData) {
                $data = $this->_data;
            } else {
                // Sinon, on lit le contenu de l'objet. @todo à remplacer par getContent...
                $limit = $this->_nebuleInstance->getOption('ioReadMaxData');
                $data = $this->_nebuleInstance->getIO()->objectRead($this->_id, $limit);

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
                $hash = $this->_crypto->hash($data);
                if ($hash != $this->_id) {
                    unset($data);
                    $this->_haveData = false;
                    $this->_data = null;
                    return false;
                }
                unset($hash);
            }

            // Chiffrement (symétrique) du contenu.
            $code = $this->_crypto->crypt($data, $key);
            unset($data, $keySize);

            // Vérification de bon chiffrement.
            if ($code === false) {
                return false;
            }

            // Chiffrement (asymétrique) de la clé de chiffrement du contenu.
            $codeKey = $this->_crypto->cryptTo($key, $this->_nebuleInstance->getCurrentEntityInstance()->getPublicKey());

            // Vérification de bon chiffrement.
            if ($codeKey === false) {
                return false;
            }

            // Ecrit le contenu chiffré.
            $codeInstance = new Node($this->_nebuleInstance, '0', $code, false);
            $codeID = $codeInstance->getID();
            $this->_metrology->addLog('Protect object, code : ' . $codeID, Metrology::LOG_LEVEL_DEBUG);

            // Vérification de bonne écriture.
            if ($codeID == '0') {
                return false;
            }

            // Ecrit la clé de session chiffrée.
            $codeKeyInstance = new Node($this->_nebuleInstance, '0', $codeKey, false);
            $codeKeyID = $codeKeyInstance->getID();
            $this->_metrology->addLog('Protect object, code key : ' . $codeKeyID, Metrology::LOG_LEVEL_DEBUG);

            // Vérification de bonne écriture.
            if ($codeKeyID == '0') {
                return false;
            }

            $signer = $this->_nebuleInstance->getCurrentEntity();
            $date = date(DATE_ATOM);

            // Crée le lien de type d'empreinte de la clé.
            $action = 'l';
            $source = $keyID;
            $target = $this->_crypto->hash($this->_crypto->hashAlgorithmName());
            $meta = $this->_crypto->hash('nebule/objet/hash');
            $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new Link($this->_nebuleInstance, $link);
            $newLink->signWrite();

            // Création du type mime des données chiffrées.
            $text = 'application/x-encrypted/' . $this->_crypto->symetricAlgorithmName();
            $textID = $this->_nebuleInstance->createTextAsObject($text);
            if ($textID !== false) {
                // Crée le lien de type d'empreinte.
                $action = 'l';
                $source = $codeID;
                $target = $textID;
                $meta = $this->_crypto->hash('nebule/objet/type');
                $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                $newLink = new Link($this->_nebuleInstance, $link);
                $newLink->sign();
                if ($obfuscated) {
                    $newLink->obfuscate();
                }
                $newLink->write();
            }

            // Création du type mime de la clé chiffrée.
            $text = 'application/x-encrypted/' . $this->_crypto->asymetricAlgorithmName();
            $textID = $this->_nebuleInstance->createTextAsObject($text);
            if ($textID !== false) {
                // Crée le lien de type d'empreinte.
                $action = 'l';
                $source = $codeKeyID;
                $target = $textID;
                $meta = $this->_crypto->hash('nebule/objet/type');
                $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                $newLink = new Link($this->_nebuleInstance, $link);
                $newLink->sign();
                if ($obfuscated) {
                    $newLink->obfuscate();
                }
                $newLink->write();
            }

            // Création du lien de chiffrement symétrique.
            $action = 'k';
            $source = $this->_id;
            $target = $codeID;
            $meta = $keyID;
            $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new Link($this->_nebuleInstance, $link);
            $newLink->sign();
            if ($obfuscated) {
                $newLink->obfuscate();
            }
            $newLink->write();

            // Création du lien de chiffrement asymétrique.
            $action = 'k';
            $source = $keyID;
            $target = $codeKeyID;
            $meta = $signer;
            $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new Link($this->_nebuleInstance, $link);
            $newLink->sign();
            if ($obfuscated) {
                $newLink->obfuscate();
            }
            $newLink->write();

            // Supprime l'objet qui a été marqué protégé.
            $this->_metrology->addLog('Delete unprotected object ' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log
            $deleteObject = true;

            // Création lien.
            $signer = $this->_nebuleInstance->getCurrentEntity();
            $date = date(DATE_ATOM);
            $source = $this->_id;
            $link = '0_' . $signer . '_' . $date . '_d_' . $source . '_0_0';
            $newLink = new Link($this->_nebuleInstance, $link);
            $newLink->sign();
            if ($obfuscated) {
                $newLink->obfuscate();
            }
            $newLink->write();

            // Lit les liens.
            $links = $this->readLinksUnfiltred();
            $entity = $this->_nebuleInstance->getCurrentEntity();
            foreach ($links as $link) {
                // Vérifie si l'entité signataire du lien est l'entité courante.
                if ($link->getHashSigner() != $entity) {
                    // Si ce n'est pas l'entité courante, quitte.
                    $this->_metrology->addAction('delobj', $this->_id, false);
                    $deleteObject = false;
                }
            }

            if ($deleteObject) {
                // Supprime l'objet.
                $r = $this->_io->objectDelete($this->_id);

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
            if ($this->_nebuleInstance->getOption('permitRecoveryEntities')) {
                $listEntities = $this->_nebuleInstance->getRecoveryEntitiesInstance();
                foreach ($listEntities as $entity) {
                    if (is_a($entity, 'Entity')
                        && $entity->getID() != '0'
                        && $entity->getIsPublicKey()
                        && $entity != $this->_nebuleInstance->getCurrentEntity()
                    ) {
                        // Chiffrement (asymétrique) de la clé de chiffrement du contenu.
                        $codeKey = $this->_crypto->cryptTo($key, $entity->getPublicKey());

                        // Vérification de bon chiffrement.
                        if ($codeKey === false) {
                            $this->_metrology->addLog('Error (1) share protection to recovery ' . $entity->getID(), Metrology::LOG_LEVEL_ERROR); // Log
                            continue;
                        }

                        // Ecrit la clé de session chiffrée.
                        $codeKeyInstance = new Node($this->_nebuleInstance, '0', $codeKey, false);
                        $codeKeyID = $codeKeyInstance->getID();
                        $this->_metrology->addLog('Protect object, code key : ' . $codeKeyID, Metrology::LOG_LEVEL_DEBUG);

                        // Vérification de bonne écriture.
                        if ($codeKeyID == '0') {
                            $this->_metrology->addLog('Error (2) share protection to recovery ' . $entity->getID(), Metrology::LOG_LEVEL_ERROR); // Log
                            continue;
                        }

                        // Création du type mime de la clé chiffrée.
                        $text = 'application/x-encrypted/' . $this->_crypto->asymetricAlgorithmName();
                        $textID = $this->_nebuleInstance->createTextAsObject($text);
                        if ($textID !== false) {
                            // Crée le lien de type d'empreinte.
                            $action = 'l';
                            $source = $codeKeyID;
                            $target = $textID;
                            $meta = $this->_crypto->hash('nebule/objet/type');
                            $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                            $newLink = new Link($this->_nebuleInstance, $link);
                            $newLink->sign();
                            if ($obfuscated) {
                                $newLink->obfuscate();
                            }
                            $newLink->write();
                        }

                        // Création du lien de chiffrement asymétrique.
                        $action = 'k';
                        $source = $keyID;
                        $target = $codeKeyID;
                        $meta = $entity->getID();
                        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                        $newLink = new Link($this->_nebuleInstance, $link);
                        $newLink->sign();
                        if ($obfuscated) {
                            $newLink->obfuscate();
                        }
                        $newLink->write();

                        $this->_metrology->addLog('Set protection shared to recovery ' . $entity->getID(), Metrology::LOG_LEVEL_DEBUG); // Log
                    }
                }
            }
            unset($links, $entity, $link, $deleteObject, $newLink, $signer, $date, $source);

            return true;
        }
    }

    /**
     * Déprotège l'objet.
     * @return boolean
     */
    public function setUnprotected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que l'objet est protégé et que l'on peut y acceder.
        if (!$this->_getMarkProtected()) {
            return false;
        }
        if ($this->_idProtected == '0') {
            return false;
        }
        if ($this->_idUnprotected == '0') {
            return false;
        }
        if ($this->_idProtectedKey == '0') {
            return false;
        }
        if ($this->_idUnprotectedKey == '0') {
            return false;
        }

        $this->_metrology->addLog('Set unprotected ' . $this->_id, Metrology::LOG_LEVEL_NORMAL); // Log

        // @todo

        return false;
    }

    /**
     * Protège l'objet pour une entité.
     *
     * L'objet devient illisible en verrouillant l'entité courante !
     *
     * @param $entity entity|string
     * @return boolean
     */
    public function setProtectedTo($entity)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // @todo
    }

    /**
     * Transmet la protection de l'objet à une entité.
     *
     * @param $entity entity|string
     * @return boolean
     */
    public function shareProtectionTo($entity)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if (is_string($entity)) {
            $entity = $this->_nebuleInstance->newEntity($entity);
        }
        if (!is_a($entity, 'entity')) {
            $entity = $this->_nebuleInstance->newEntity($entity->getID());
        }
        if (!$entity->getIsEntity('all')) {
            return false;
        }

        // Vérifie que l'objet est protégé et que l'on peut y acceder.
        if (!$this->_getMarkProtected()
            || $this->_idProtected == '0'
            || $this->_idUnprotected == '0'
            || $this->_idProtectedKey == '0'
            || $this->_idUnprotectedKey == '0'
        ) {
            return false;
        }

        $this->_metrology->addLog('Set protected to ' . $entity->getID(), Metrology::LOG_LEVEL_DEBUG); // Log

        // Lit la clé chiffrée. @todo à remplacer par getContent ...
        $limit = $this->_nebuleInstance->getOption('ioReadMaxData');
        $codeKey = $this->_nebuleInstance->getIO()->objectRead($this->_idProtectedKey, $limit);
        // Calcul l'empreinte de la clé chiffrée.
        $hash = $this->_crypto->hash($codeKey);
        if ($hash != $this->_idProtectedKey) {
            $this->_metrology->addLog('Error get protected key content : ' . $this->_idProtectedKey, Metrology::LOG_LEVEL_NORMAL); // Log
            $this->_metrology->addLog('Protected key content hash : ' . $hash, Metrology::LOG_LEVEL_NORMAL); // Log
            return false;
        }

        // Déchiffrement (asymétrique) de la clé de chiffrement du contenu.
        $key = $this->_nebuleInstance->getCurrentEntityInstance()->decrypt($codeKey);
        // Calcul l'empreinte de la clé.
        $hash = $this->_crypto->hash($key);
        if ($hash != $this->_idUnprotectedKey) {
            $this->_metrology->addLog('Error get unprotected key content : ' . $this->_idUnprotectedKey, Metrology::LOG_LEVEL_NORMAL); // Log
            $this->_metrology->addLog('Unprotected key content hash : ' . $hash, Metrology::LOG_LEVEL_NORMAL); // Log
            return false;
        }

        // Chiffrement (asymétrique) de la clé de chiffrement du contenu.
        $codeKey = $this->_crypto->cryptTo($key, $entity->getPublicKey());

        // Vérification de bon chiffrement.
        if ($codeKey === false) {
            return false;
        }

        // Ecrit la clé chiffrée.
        $codeKeyInstance = new Node($this->_nebuleInstance, '0', $codeKey, false);
        $codeKeyID = $codeKeyInstance->getID();
        $this->_metrology->addLog('Protect object, code key : ' . $codeKeyID, Metrology::LOG_LEVEL_NORMAL); // Log

        // Vérification de bonne écriture.
        if ($codeKeyID == '0') {
            return false;
        }

        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);

        // Création du type mime de la clé chiffrée.
        $text = 'application/x-encrypted/' . $this->_crypto->asymetricAlgorithmName();
        $textID = $this->_nebuleInstance->createTextAsObject($text);
        if ($textID !== false) {
            // Crée le lien de type d'empreinte.
            $action = 'l';
            $source = $codeKeyID;
            $target = $textID;
            $meta = $this->_crypto->hash('nebule/objet/type');
            $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new Link($this->_nebuleInstance, $link);
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
    public function cancelShareProtectionTo($entity)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if (is_string($entity)) {
            $entity = $this->_nebuleInstance->newEntity($entity);
        }
        if (!is_a($entity, 'entity')) {
            $entity = $this->_nebuleInstance->newEntity($entity->getID());
        }
        if (!$entity->getIsEntity('all')) {
            return false;
        }

        // Vérifie que l'objet est protégé et que l'on peut y acceder.
        if (!$this->_getMarkProtected()) {
            return false;
        }
        if ($this->_idProtected == '0') {
            return false;
        }
        if ($this->_idUnprotected == '0') {
            return false;
        }
        if ($this->_idProtectedKey == '0') {
            return false;
        }
        if ($this->_idUnprotectedKey == '0') {
            return false;
        }

        // Vérifie que la protection n'est pas partagée à une entité de recouvrement.
        if (!$this->_nebuleInstance->getOption('permitRecoveryRemoveEntity')
            && $this->_nebuleInstance->getIsRecoveryEntity($entity->getID())
        ) {
            return false;
        }

        $this->_metrology->addLog('Cancel share protection to ' . $entity->getID(), Metrology::LOG_LEVEL_NORMAL); // Log

        // Recherche l'objet de clé de chiffrement pour l'entité.
        $links = $entity->readLinksFilterFull(
            $this->_nebuleInstance->getCurrentEntity(),
            '',
            'k',
            $this->_idUnprotectedKey,
            '',
            $entity->getID());

        if (sizeof($links) == 0) {
            return true;
        }

        foreach ($links as $item) {
            $idKey = $item->getHashSource();
            $idProtectedKey = $item->getHashTarget();
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
                $signerLinks = $object->readLinksFilterFull('', '', '', '', '', '');
                $delete = true;
                foreach ($signerLinks as $itemSigner) {
                    // Si un lien a été généré par une autre entité, c'est que l'objet est encore utilisé.
                    if ($itemSigner->getHashSigner() != $signer && $itemSigner->getHashSigner() != $this->_nebuleInstance->getCurrentEntity()) {
                        $delete = false;
                    }
                }
                if ($delete) {
                    $this->_io->objectDelete($idProtectedKey);
                }
                unset($object, $signerLinks, $itemSigner, $delete);
            }
        }
        unset($links);

        return true;
    }


    /**
     * Lit une émotion pour l'objet.
     *
     * La sélection d'une classe sociale particulière permet de faire un filtre sur la recherche.
     * Les classes sociales intéressantes :
     *  - self : mes émotions sur l'objet ;
     *  - notself : les émotions de toutes les entités sauf moi sur l'objet ;
     *  - all : les émotions de toutes les entités sur l'objet.
     *
     * @param string $emotion
     * @param string $socialClass
     * @param string|Node $context
     * @return boolean
     */
    public function getMarkEmotion($emotion, $socialClass = '', $context = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->getMarkEmotionSize($emotion, $socialClass, $context) == 0) {
            return false;
        }
        return true;
    }

    /**
     * Lit la liste des entités qui ont marqué une émotion pour l'objet.
     * @param string $emotion
     * @param string $socialClass
     * @param string|Node $context
     * @return array:Link
     * @todo hash alternatifs.
     *
     * Par défaut, le contexte de recherche est vide.
     * Dans ce cas, on ne garde que les liens avec comme contexte l'entité qui a signé le lien.
     *
     */
    public function getMarkEmotionList($emotion, $socialClass = '', $context = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $list = array();

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
            || !ctype_xdigit($context)
        ) {
            $context = '';
        }

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
            return $list;
        }

        $hashEmotion = $this->_crypto->hash($emotion);

        // Liste les liens à la recherche de la propriété.
        $list = $this->readLinksFilterFull(
            '',
            '',
            'f',
            $this->_id,
            $hashEmotion,
            $context);

        // Nettoyage.
        foreach ($list as $i => $link) {
            // Si méta à 0, supprime le lien.
            if ($link->getHashMeta() == '0') {
                unset($list[$i]);
            }
        }
        unset($link);

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($list) == 0
            && $this->_nebuleInstance->getOption('permitListOtherHash')
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
     * @param string|Node $context
     * @return array:Link
     */
    public function getMarkEmotionSize($emotion, $socialClass = '', $context = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $list = $this->getMarkEmotionList($emotion, $socialClass, $context);
        return sizeof($list);
    }

    /**
     * Lit toute les émotions pour l'objet.
     *
     * @param string $socialClass
     * @param string|Node $context
     * @return array:string
     */
    public function getMarkEmotions($socialClass = '', $context = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

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
     *
     * La marque d'un émotion sur l'objet est un lien de type f avec :
     *  - source : l'ID de l'objet
     *  - cible : le hash de l'émotion
     *  - méta : le signataire ou l'objet de contexte (conversation par exemple)
     *
     * Le lien peut être dissimulé.
     *
     * L'émotion peut être rattachée en contexte à
     *  - une autre entité @param string $emotion
     * @param boolean $obfuscate
     * @param string $context
     * @return boolean
     * @todo à revoir...
     *  - ou un objet particulier
     * Cela permet par défaut de discriminer précisément lorsque l'émotion concerne l'objet
     *   ou si l'émotion se réfère à un contexte particulier de l'objet comme une conversation.
     * Par défaut le contexte est l'entité en cours, l'émotion est attaché à cet objet directement.
     *
     */
    public function setMarkEmotion($emotion, $obfuscate = false, $context = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

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
        $target = $this->_crypto->hash($emotion);
        $meta = $context;
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();
        if ($obfuscate) {
            $newLink->obfuscate();
        }
        $newLink->write();

        return true;
    }

    /**
     * Supprime une émotion pour l'objet.
     *
     * Le lien de suppression peut être dissimulé et ainsi laisser publique l'émotion.
     * L'émotion peut être rattachée à une autre entité.
     *
     * @param string $emotion
     * @param boolean $obfuscate
     * @param string $entity
     * @return boolean
     */
    public function unsetMarkEmotion($emotion, $obfuscate = false, $entity = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

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

        // Nettoyage de l'entité demandé.
        if (is_a($entity, 'entity')
            || is_a($entity, 'Node')
            || is_a($entity, 'group')
            || is_a($entity, 'conversation')
        ) {
            $entity = $entity->getID();
        }
        if (!is_string($entity)
            || $entity == '0'
            || $entity == ''
            || !ctype_xdigit($entity)
        ) {
            $entity = $this->_nebuleInstance->getCurrentEntity();
        }

        // Création du lien.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'x';
        $source = $this->_id;
        $target = $this->_crypto->hash($emotion);
        $meta = $entity;
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();
        if ($obfuscate) {
            $newLink->obfuscate();
        }
        $newLink->write();

        return true;
    }


    /**
     * Lit à quelles entités à été transmis la protection de l'objet.
     *
     * @return array:string
     */
    public function getProtectedTo()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $result = array();
        if (!$this->_getMarkProtected()) {
            return $result;
        }

        // Lit les liens de chiffrement de l'objet, chiffrement symétrique.
        $linksSym = $this->readLinksFilterFull('', '', 'k', $this->_idUnprotected, '', '');
        foreach ($linksSym as $linkSym) {
            // Si lien de chiffrement.
            if ($linkSym->getHashMeta() != '0') {
                // Lit l'objet de clé de chiffrement symétrique et ses liens.
                $instanceSym = $this->_nebuleInstance->newObject($linkSym->getHashMeta());
                $linksAsym = $instanceSym->readLinksFilterFull(
                    '',
                    '',
                    'k',
                    $linkSym->getHashMeta(),
                    '',
                    ''
                );
                unset($instanceSym);
                foreach ($linksAsym as $linkAsym) {
                    // Si lien de chiffrement.
                    if ($linkAsym->getHashMeta() != '0') {
                        $result[] = $linkAsym->getHashMeta();
                    }
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
    public function checkConsistency()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_haveData)
            return true;

        if (!$this->_io->checkObjectPresent($this->_id))
            return false;

        // Si c'est l'objet 0, le supprime.
        if ($this->_id == '0') {
            $this->_data = null;
            $this->_metrology->addLog('Delete object 0', Metrology::LOG_LEVEL_NORMAL); // Log
            $nid = '0';
            $this->_io->objectDelete($nid);
            return false;
        }

        // Détermine l'algorithme de hash.
        $hashAlgo = $this->getHashAlgo();
        if (!$this->_crypto->checkHashAlgorithm($hashAlgo)
            && $this->_nebuleInstance->getOption('permitSynchronizeLink')
        )
            $this->syncLinks(false);
        $hashAlgo = $this->getHashAlgo();
        if (!$this->_crypto->checkHashAlgorithm($hashAlgo)) {
            if ($this->_nebuleInstance->getOption('permitDeleteObjectOnUnknownHash'))
                $hashAlgo = $this->_crypto->hashAlgorithmName();
            else
                return false;
        }

        // Extrait le contenu de l'objet, si possible.
        $this->_metrology->addObjectRead(); // Metrologie.
        $this->_data = $this->_io->objectRead($this->_id);
        if ($this->_data === false) {
            $this->_metrology->addLog('Cant read object ' . $this->_id, Metrology::LOG_LEVEL_ERROR); // Log
            $this->_data = null;
            return false;
        }
        $limit = $this->_nebuleInstance->getOption('DEFAULT_IO_READ_MAX_DATA');
        $this->_metrology->addLog('Object size ' . $this->_id . ' ' . strlen($this->_data) . '/' . $limit, Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie la taille.
        if (strlen($this->_data) > $limit) {
            $this->_data = null;
            return false;
        }

        // Calcul l'empreinte.
        $hash = $this->_crypto->hash($this->_data, $hashAlgo);
        if ($hash == $this->_id) // Si l'objet est valide.
        {
            $this->_metrology->addObjectVerify(); // Metrologie.
            $this->_haveData = true;
            return true;
        }

        // Si la vérification est désactivée, quitte.
        if (!$this->_nebuleInstance->getOption('permitCheckObjectHash')) {
            $this->_metrology->addLog('Warning - Invalid object hash ' . $this->_id, Metrology::LOG_LEVEL_ERROR); // Log
            $this->_haveData = true;
            return true;
        }

        // Sinon l'objet est présent mais invalide, le supprime.
        $this->_data = null;
        $this->_metrology->addLog('Delete unconsistency object ' . $this->_id . ' ' . $hashAlgo . ':' . $hash, Metrology::LOG_LEVEL_NORMAL); // Log
        $this->_io->objectDelete($this->_id);
        return false;
    }

    /**
     * Lit le contenu de l'objet.
     * Retourne une chaine vide si l'empreinte des données diffère de l'ID.
     *
     * @param integer $limit limite de lecture du contenu de l'objet.
     * @return string
     */
    public function getContent($limit = 0)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_haveData) {
            return $this->_data;
        }

        if ($this->_getMarkProtected()) {
            return $this->_getProtectedContent($limit);
        } else {
            return $this->_getUnprotectedContent($limit);
        }
    }

    /**
     * Lit le contenu de l'objet sans essayer de le déchiffrer.
     * Retourne une chaine vide si l'empreinte des données diffère de l'ID.
     *
     * @param integer $limit limite de lecture du contenu de l'objet.
     * @return string
     */
    public function getContentAsUnprotected($limit = 0)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_getUnprotectedContent($limit);
    }

    /**
     * Lit sans déchiffrer un contenu (non protégé).
     *
     * @param integer $limit limite de lecture du contenu de l'objet.
     * @return string|null
     */
    protected function _getUnprotectedContent($limit = 0)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_haveData) {
            return $this->_data;
        }

        if (!$this->_io->checkObjectPresent($this->_id)) {
            return null;
        }

        // Si c'est l'objet 0, le supprime.
        if ($this->_id == '0') {
            $this->_data = null;
            $this->_metrology->addLog('Delete object 0', Metrology::LOG_LEVEL_NORMAL); // Log
            $nid = '0';
            $this->_io->objectDelete($nid);
            return null;
        }

        // Détermine l'algorithme de hash.
        $hashAlgo = $this->getHashAlgo();
        if (!$this->_crypto->checkHashAlgorithm($hashAlgo)
            && $this->_nebuleInstance->getOption('permitSynchronizeLink')
        ) {
            // Essaie une synchronisation rapide des liens.
            $this->syncLinks(false);
        }
        $hashAlgo = $this->getHashAlgo();
        if (!$this->_crypto->checkHashAlgorithm($hashAlgo)) {
            if ($this->_nebuleInstance->getOption('permitDeleteObjectOnUnknownHash')) {
                // Si pas trouvé d'algorithme valide, utilise celui par défaut.
                $hashAlgo = $this->_crypto->hashAlgorithmName();
            } else {
                return null;
            }
        }

        // Prépare la limite de lecture.
        $maxLimit = $this->_nebuleInstance->getOption('ioReadMaxData');
        if ($limit == 0
            || $limit > $maxLimit
        ) {
            $limit = $maxLimit;
        }

        // Extrait le contenu de l'objet, si possible.
        $this->_metrology->addObjectRead(); // Metrologie.
        $this->_data = $this->_io->objectRead($this->_id, $limit);
        if ($this->_data === false) {
            $this->_metrology->addLog('Cant read object ' . $this->_id, Metrology::LOG_LEVEL_ERROR); // Log
            $this->_data = null;
            return null;
        }
        $this->_metrology->addLog('Object read size ' . $this->_id . ' ' . strlen($this->_data) . '/' . $maxLimit, Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie la taille. Si trop grand mais qu'une limite est imposé, quitte sans vérifier l'empreinte.
        if (strlen($this->_data) >= $limit
            && $limit < $maxLimit
        ) {
            $this->_data = null;
            return null;
        }

        // Calcul l'empreinte.
        $hash = $this->_crypto->hash($this->_data, $hashAlgo);
        if ($hash == $this->_id) // Si l'objet est valide.
        {
            $this->_metrology->addObjectVerify(); // Metrologie.
            $this->_haveData = true;
            return $this->_data;
        }

        // Si la vérification est désactivée, quitte.
        if (!$this->_nebuleInstance->getOption('permitCheckObjectHash')) {
            $this->_metrology->addLog('Warning - Invalid object hash ' . $this->_id, Metrology::LOG_LEVEL_ERROR); // Log
            $this->_haveData = true;
            return $this->_data;
        }

        // Sinon l'objet est présent mais invalide, le supprime.
        $this->_data = null;
        $this->_metrology->addLog('Delete unconsistency object ' . $this->_id . ' ' . $hashAlgo . ':' . $hash, Metrology::LOG_LEVEL_NORMAL); // Log
        $this->_io->objectDelete($this->_id);
        return null;
    }

    /**
     * Lit et déchiffre un contenu protégé.
     * @param integer $limit limite de lecture du contenu de l'objet.
     * @param boolean $permitTroncate permet de faire une lecture partiel des gros objets, donc non vérifié.
     * @return string|null
     * @todo à revoir en entier !
     *
     */
    protected function _getProtectedContent($limit = 0)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

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
//			$limit = $this->_nebuleInstance->getOption('ioReadMaxData');

        $permitTroncate = false; // @todo à retirer.

        $this->_metrology->addLog('Get protected content : ' . $this->_idUnprotected, Metrology::LOG_LEVEL_DEBUG); // Log

        // Lit la clé chiffrée.
        $codeKey = $this->_io->objectRead($this->_idProtectedKey, 0);
        // Calcul l'empreinte de la clé chiffrée.
        $hash = $this->_crypto->hash($codeKey);
        if ($hash != $this->_idProtectedKey) {
            $this->_metrology->addLog('Error get protected key content : ' . $this->_idProtectedKey, Metrology::LOG_LEVEL_ERROR);
            $this->_metrology->addLog('Protected key content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR);
            return null;
        }

        // Déchiffrement (asymétrique) de la clé de chiffrement du contenu.
        $key = $this->_nebuleInstance->getCurrentEntityInstance()->decrypt($codeKey);
        // Calcul l'empreinte de la clé.
        $hash = $this->_crypto->hash($key);
        if ($hash != $this->_idUnprotectedKey) {
            $this->_metrology->addLog('Error get unprotected key content : ' . $this->_idUnprotectedKey, Metrology::LOG_LEVEL_ERROR);
            $this->_metrology->addLog('Unprotected key content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR);
            return null;
        }

        // Lit l'objet chiffré.
        $code = $this->_io->objectRead($this->_idProtected, $limit);
        // Calcul l'empreinte des données.
        $hash = $this->_crypto->hash($code);
        if ($hash != $this->_idProtected) {
            $this->_metrology->addLog('Error get protected data content : ' . $this->_idProtected, Metrology::LOG_LEVEL_ERROR);
            $this->_metrology->addLog('Protected data content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR);
            return null;
        }

        $data = $this->_crypto->decrypt($code, $key);
        // Calcul l'empreinte des données.
        $hash = $this->_crypto->hash($data);
        if ($hash != $this->_idUnprotected) {
            $this->_metrology->addLog('Error get unprotected data content : ' . $this->_idUnprotected, Metrology::LOG_LEVEL_ERROR);
            $this->_metrology->addLog('Unprotected data content hash : ' . $hash, Metrology::LOG_LEVEL_ERROR);
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
    public function flushDataCache()
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
    protected function _readOneLineOtherObject($id)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($id == ''
            || !$this->_io->checkObjectPresent($id)
        ) {
            return '';
        }

        $instance = $this->_nebuleInstance->newObject($id);
        $text = mb_convert_encoding(substr(trim(strtok(filter_var($instance->getContent(0), FILTER_SANITIZE_STRING), "\n")), 0, 1024), 'UTF-8');
        unset($instance);
        return $text;
    }

    /**
     * Lit la première ligne du contenu de l'objet nebule, extrait une chaine de texte imprimable.
     *
     * @param integer $limit
     * @return string
     */
    public function readOneLineAsText($limit = 0)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if (!$this->_io->checkObjectPresent($this->_id)) {
            return '';
        }

        if ($limit == 0) {
            $limit = $this->_nebuleInstance->getOption('ioReadMaxData');
        }
        if ($limit < 4) {
            $limit = 4;
        }

        $text = mb_convert_encoding(trim(strtok(filter_var($this->getContent($limit), FILTER_SANITIZE_STRING), "\n")), 'UTF-8');
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
    public function readAsText($limit = 0)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if (!$this->_io->checkObjectPresent($this->_id)) {
            return '';
        }
        if ($limit < 4) {
            $limit = 4;
        }

        $text = mb_convert_encoding(trim(filter_var($this->getContent($limit + 4), FILTER_SANITIZE_STRING)), 'UTF-8');
        if (strlen($text) > $limit) {
            $text = substr($text, 0, ($limit - 3)) . '...';
        }
        return $text;
    }

    /**
     * Lit les liens.
     * Retourne un tableau d'objets de type Link ou un tableau vide si ça se passe mal.
     * Pas de filtre sur l'extraction des liens.
     * Trie les liens par date.
     *
     * @return array|boolean
     */
    public function readLinksUnfiltred()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $permitListInvalidLinks = $this->_nebuleInstance->getOption('permitListInvalidLinks');
        $linkVersion = 'nebule/liens/version/' . $this->_nebuleInstance->getOption('defaultLinksVersion'); // Version de lien.
        $linksResult = array();
        if (!$this->_io->checkLinkPresent($this->_id)) {
            return $linksResult;
        }
        $i = 0;

        // Lit les liens.
        $links = $this->_io->linksRead($this->_id);
        $this->_metrology->addLog('Object links count read ' . $this->_id . ' ' . sizeof($links), Metrology::LOG_LEVEL_DEBUG); // Log

        // Analyse les liens et les convertis en tableau d'objets de type lien.
        foreach ($links as $link) {
            if (substr($link, 0, 21) == 'nebule/liens/version/') {
                $linkVersion = trim(substr($link, 0, 25)); // Mémorise la version mais ne valide pas la ligne comme lien.
            } else {
                $l = $this->_nebuleInstance->newLink($link, $linkVersion);
                // Si c'est bon,
                if ($l->getValid()
                    || $permitListInvalidLinks
                ) {
                    $linksResult[$i] = $l;        // on l'écrit dans le tableau des résultats.
                    $i++;
                } else unset($l);                    // Sinon un détruit le lien.
            }
        }
        unset($permitListInvalidLinks, $linkVersion, $i, $links, $link);

        // Tri les liens par date.
        if (sizeof($linksResult) != 0) {
            foreach ($linksResult as $n => $t) {
                $linkdate[$n] = $t->getDate_disabled();
            }
            array_multisort($linkdate, SORT_STRING, SORT_ASC, $linksResult);
            unset($n, $t);
        }

        return $linksResult;
    }


    /**
     * Lit les liens avec un filtrage simple.
     *
     * Retourne un tableau de liens ou un tableau vide si ça se passe mal.
     * Un filtre simple est réalisé lors de l'extraction des liens.
     * Les liens marqués supprimés, càd marqués par un autre lien type x à une date égale ou +, sont enlevés.
     * Les liens de type x ne sont pas retournés.
     * Le paramètre $filter doit correspondre à au moins un des champs :
     * - signataire
     * - action
     * - date
     * - source
     * - destination
     * - méta
     * Trie les liens par date.
     *
     * @param string $filter
     * @return array:Link
     */
    public function readLinksFilterOnce($filter)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $permitListInvalidLinks = $this->_nebuleInstance->getOption('permitListInvalidLinks');
        $linkVersion = 'nebule/liens/version/' . $this->_nebuleInstance->getOption('defaultLinksVersion'); // Version de lien.
        $linksResult = array();
        if (!$this->_io->checkLinkPresent($this->_id)) {
            return $linksResult;
        }
        $i = 0;

        // Lit les liens.
        $links = $this->_io->linksRead($this->_id);
        $this->_metrology->addLog('Object links count read ' . $this->_id . ' ' . sizeof($links), Metrology::LOG_LEVEL_DEBUG); // Log

        // Analyse les liens, les filtre et les convertis en tableau d'objets de type lien.
        // Si la liste des liens n'est pas vide.
        if (sizeof($links) != 0) {
            foreach ($links as $link) {
                // Ce traitement sur champs brutes est une optimisation du temps de traitement. Le gain est de 1 à 10.
                // Extrait les champs du lien.
                $j = 1;                        // Indice du champs lu, de 1 à 7.
                $c = array();                    // Table des champs lus.
                $e = strtok(trim($link), '_');    // Lecture du champs.
                while ($e !== false)            // Extrait le lien.
                {
                    $c[$j] = trim($e);
                    if ($j < 8) {
                        $e = strtok('_');
                    } else {
                        $e = false;
                    }
                    $j++;
                }

                if (substr($link, 0, 21) == 'nebule/liens/version/') {
                    $linkVersion = trim(substr($link, 0, 25)); // Mémorise la version mais ne valide pas la ligne comme lien.
                } elseif ($j == 8) {
                    // Filtre les liens.
                    if ($c[1] == $filter
                        || $c[2] == $filter
                        || $c[3] == $filter
                        || $c[4] == $filter
                        || $c[4] == 'x'
                        || $c[5] == $filter
                        || $c[6] == $filter
                        || $c[7] == $filter
                    ) {
                        // Crée une instance de lien.
                        $linkInstance = $this->_nebuleInstance->newLink($link, $linkVersion);
                        // Si le lien est valide ou que l'on permet les liens invalides.
                        if (is_a($linkInstance, 'Link') && ($linkInstance->getValid() || $permitListInvalidLinks)) {
                            $okWriteNew = true;
                            // Si la taille du tableau des résultats n'est pas nulle.
                            if (sizeof($linksResult) != 0) {
                                // On recherche et compare le nouveau lien avec chacun des anciens liens.
                                foreach ($linksResult as $k => $t) {
                                    // Si le nouveau lien est identique.
                                    if ( // $t->getHashSigner() == $c[2]				!!! A VOIR !!!
                                        $t->getAction() == $c[4]
                                        && $t->getHashSource() == $c[5]
                                        && $t->getHashTarget() == $c[6]
                                        && $t->getHashMeta() == $c[7]
                                    ) {
                                        // Si le nouveau lien est plus récent.
                                        if ($t->getHashSigner() == $c[2]
                                            && $this->_nebuleInstance->dateCompare($t->getDate(), $c[3]) <= 0
                                        ) {
                                            // Et si ce n'est pas exactement le même.
                                            if ($t->getDate() != $c[3]) {
                                                // On l'écrit dans le tableau des résultats à la place du lien plus ancien.
                                                $linksResult[$k] = $linkInstance;
                                            }
                                            // Le lien n'a pas besoin d'être écrit.
                                            $okWriteNew = false;
                                        }
                                        // Sinon c'est que le lien est plus ancien.
                                    }
                                }
                            }
                            // Si le lien est nouveau et non marqué supprimé.
                            if ($okWriteNew) {
                                // On écrit le lien dans le tableau des résultats.
                                $linksResult[$i] = $linkInstance;
                                $i++;
                            }
                        }
                    }
                    unset($okWriteNew);
                }
            }
            unset($link, $linkInstance, $c, $e, $j);
        }
        unset($permitListInvalidLinks, $linkVersion, $i, $links);

        // Tri les liens par date.
        if (sizeof($linksResult) != 0) {
            foreach ($linksResult as $n => $t) {
                $linkdate[$n] = $t->getDate_disabled();
            }
            array_multisort($linkdate, SORT_STRING, SORT_ASC, $linksResult);
            unset($linkdate, $n, $t);
        }

        // Supprime les liens marqués supprimés.
        if (sizeof($linksResult) != 0) {
            // Liste tous les liens.
            // Ils sont triés par date.
            foreach ($linksResult as $n1 => $t1) {
                if ($t1->getAction_disabled() != 'x') {
                    // Si ce n'est pas un lien x.
                    foreach ($linksResult as $t2) {
                        if ($t2->getAction_disabled() == 'x'
                            && $t1->getHashSource_disabled() == $t2->getHashSource_disabled()
                            && $t1->getHashTarget_disabled() == $t2->getHashTarget_disabled()
                            && $t1->getHashMeta_disabled() == $t2->getHashMeta_disabled()
                            && $this->_nebuleInstance->dateCompare($t1->getDate_disabled(), $t2->getDate_disabled()) <= 0
                        ) {
                            unset($linksResult[$n1]);
                        }
                    }
                }
            }
            unset($n1, $t1, $t2);
        }

        // Supprime les liens x.
        if (sizeof($linksResult) != 0) {
            // Liste tous les liens.
            foreach ($linksResult as $n => $t) {
                if ($t->getAction_disabled() == 'x') {
                    // Si lien x, le supprime.
                    unset($linksResult[$n]);
                }
            }
            unset($n, $t);
        }

        return $linksResult;
    }


    /** Lit les liens.
     * Retourne un tableau d'objets de type Link ou un tableau vide si ça se passe mal.
     * Un filtre simple est réalisé lors de l'extraction des liens.
     * Les liens marqués supprimés, càd marqués par un autre lien type x à une date égale ou +, sont enlevés.
     * Les liens de type x ne sont pas retournés.
     * Les paramètres doivent correspondre à la valeur des champs correspondants :
     * - signataire
     * - action
     * - date
     * - source
     * - destination
     * - méta
     * Un paramètre vide '' équivaut à toute valeur du champs.
     * Trie les liens par date.
     *
     * @param string $signer
     * @param string $date
     * @param string $action
     * @param string $source
     * @param string $target
     * @param string $meta
     * @return array|Link
     */
    public function readLinksFilterFull($signer = '', $date = '', $action = '', $source = '', $target = '', $meta = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $permitListInvalidLinks = $this->_nebuleInstance->getOption('permitListInvalidLinks');
        $linkVersion = 'nebule/liens/version/' . $this->_nebuleInstance->getOption('defaultLinksVersion'); // Version de lien par défaut.
        $linksResult = array();
        if (!$this->_io->checkLinkPresent($this->_id)) {
            return $linksResult;
        }
        $i = 0;

        // Analyse des champs à ne pas prendre en compte.
        $allSigner = false;
        $allDate = false;
        $allAction = false;
        $allSource = false;
        $allTarget = false;
        $allMeta = false;
        if ($signer == '') {
            $allSigner = true;
        }
        if ($date == '') {
            $allDate = true;
        }
        if ($action == '') {
            $allAction = true;
        }
        if ($source == '') {
            $allSource = true;
        }
        if ($target == '') {
            $allTarget = true;
        }
        if ($meta == '') {
            $allMeta = true;
        }

        // Lit les liens.
        $links = $this->_io->linksRead($this->_id);
        $this->_metrology->addLog('Object links count read ' . $this->_id . ' ' . sizeof($links), Metrology::LOG_LEVEL_DEBUG); // Log

        // Analyse les liens, les filtre et les convertis en tableau d'objets de type lien.
        // Si la liste des liens n'est pas vide.
        if (sizeof($links) != 0) {
            foreach ($links as $link) {
                // Ce traitement sur champs brutes est une optimisation du temps de traitement. Le gain est de 1 à 10.
                // Extrait les champs du lien.
                $j = 1;                        // Indice du champs lu, de 1 à 7.
                $c = array();                    // Table des champs lus.
                $e = strtok(trim($link), '_');    // Lecture du champs.
                while ($e !== false)            // Extrait le lien.
                {
                    $c[$j] = trim($e);
                    if ($j < 8) {
                        $e = strtok('_');
                    } else {
                        $e = false;
                    }
                    $j++;
                }

                if (substr($link, 0, 21) == 'nebule/liens/version/') {
                    // Mémorise la version mais ne valide pas la ligne comme lien.
                    $linkVersion = trim(substr($link, 0, 25));
                } elseif ($j == 8) {
                    // Filtre les liens.
                    if (($allSigner || $c[2] == $signer)
                        && ($allDate || $c[3] == $date)
                        && ($allAction || $c[4] == $action || $c[4] == 'x')
                        && ($allSource || $c[5] == $source)
                        && ($allTarget || $c[6] == $target)
                        && ($allMeta || $c[7] == $meta)
                    ) {
                        // Crée une instance de lien.
                        $linkInstance = $this->_nebuleInstance->newLink($link, $linkVersion);
                        // Si le lien est valide ou que l'on permet les liens invalides.
                        if (is_a($linkInstance, 'Link')
                            && ($linkInstance->getValid() || $permitListInvalidLinks)
                        ) {
                            $okWriteNew = true;
                            // Si la taille du tableau des résultats n'est pas nulle.
                            if (sizeof($linksResult) != 0) {
                                // On recherche et compare le nouveau lien avec chacun des anciens liens.
                                foreach ($linksResult as $k => $t) {
                                    // Si le nouveau lien est identique.
                                    if ($t->getHashSigner() == $c[2]
                                        && $t->getAction() == $c[4]
                                        && $t->getHashSource() == $c[5]
                                        && $t->getHashTarget() == $c[6]
                                        && $t->getHashMeta() == $c[7]
                                    ) {
                                        // Si le nouveau lien est plus récent.
                                        if ($t->getHashSigner() == $c[2]
                                            && $this->_nebuleInstance->dateCompare($t->getDate(), $c[3]) <= 0
                                        ) {
                                            // Et si ce n'est pas exactement le même.
                                            if ($t->getDate() != $c[3]) {
                                                // On l'écrit dans le tableau des résultats à la place du lien plus ancien.
                                                $linksResult[$k] = $linkInstance;
                                            }
                                            // Le lien n'a pas besoin d'être écrit.
                                            $okWriteNew = false;
                                        }
                                        // Sinon c'est que le lien est plus ancien.
                                    }
                                }
                            }
                            // Si le lien est nouveau et non marqué supprimé.
                            if ($okWriteNew) {
                                // On écrit le lien dans le tableau des résultats.
                                $linksResult[$i] = $linkInstance;
                                $i++;
                            }
                        }
                    }
                    unset($okWriteNew);
                }
            }
            unset($link, $linkInstance, $c, $e, $j);
        }
        unset($permitListInvalidLinks, $linkVersion, $i, $links);

        // Tri les liens par date.
        if (sizeof($linksResult) != 0) {
            $linkdate = array();
            foreach ($linksResult as $n => $t) {
                $linkdate[$n] = $t->getDate_disabled();
            }
            array_multisort($linkdate, SORT_STRING, SORT_ASC, $linksResult);
            unset($linkdate, $n, $t);
        }

        // Supprime les liens marqués supprimés.
        if (sizeof($linksResult) != 0) {
            // Liste tous les liens.
            // Ils sont triés par date.
            foreach ($linksResult as $n1 => $t1) {
                if ($t1->getAction_disabled() != 'x') {
                    // Si ce n'est pas un lien x.
                    foreach ($linksResult as $t2) {
                        if ($t2->getAction_disabled() == 'x'
                            && $t1->getHashSource_disabled() == $t2->getHashSource_disabled()
                            && $t1->getHashTarget_disabled() == $t2->getHashTarget_disabled()
                            && $t1->getHashMeta_disabled() == $t2->getHashMeta_disabled()
                            && $this->_nebuleInstance->dateCompare($t1->getDate_disabled(), $t2->getDate_disabled()) <= 0
                        ) {
                            unset($linksResult[$n1]);
                        }
                    }
                }
            }
            unset($n1, $t1, $t2);
        }

        // Supprime les liens x.
        if (sizeof($linksResult) != 0) {
            // Liste tous les liens.
            foreach ($linksResult as $n => $t) {
                if ($t->getAction_disabled() == 'x') {
                    // Si lien x, le supprime.
                    unset($linksResult[$n]);
                }
            }
            unset($n, $t);
        }

        return $linksResult;
    }

    /**
     * Recherche l'identifiant d'un objet final définit comme mise à jour de l'objet courant.
     * Résoud le graphe des mises à jours d'un objet.
     * - $present permet de controler si l'on veut que l'objet final soit bien présent localement.
     * - $synchro permet ou non la synchronisation des liens et objets auprès d'entités tierces, en clair on télécharge ce qui manque au besoin lors du parcours du graphe.
     * Retourne l'ID de l'objet à jour ou l'ID de l'objet de départ si pas de mise à jour.
     * @param boolean $present
     * @param boolean $synchro
     * @todo
     *
     */
    public function findUpdate($present = true, $synchro = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifier si authorisé à rechercher les mises à jours.
        if (!$this->_nebuleInstance->getOption('permitFollowUpdates')) {
            return $this->_id;
        }

        // Si déjà recherché, donne le résultat en cache.
        if ($this->_cacheUpdate != ''
            && !$synchro
        ) {
            return $this->_cacheUpdate;
        }

        $l = array();        // Liste des branches de l'arborescence que l'on a pris.
        $i = 0;            // Indice de position dans l'arborescence.
        $j = 0;            // Indice de position sur les mises à jour un objet.
        $s = '0';            // L'ID de l'objet suivant.
        $co = array();    // Cache des objets de l'arborescence.
        $cl = array();    // Cache des dérivés des objets de l'arborescence.
        $ov = array();    // Table des objets vus.
        $oi = array();    // Table de l'indice de lien utilisé pour les objets vus.
        $ok = false;        // Résultat pour la boucle.
        $id = $this->_id;    // ID de l'objet courant.

        // Faire une boucle...

        // Vérifie si pas déjà traité, anti boucle infinie.
        $this->_usedUpdate[$this->_id] = true;

        // Vérifie si pas dépassé le nombre max à traiter, anti trou noir.
        if (sizeof($this->_usedUpdate) > $this->_nebuleInstance->getOption('maxFollowedUpdates')) {
            return '0';
        }

        // Recherche la mise à jour de l'objet.
        while (!$ok) {
            // Extrait l'objet du cache...
            if (isset($co[$id])) {
                // Extrait l'objet.
                $object = $co[$id];
                // Extrait les dérivés connus.
                $sub = $cl[$id];
            } // ... ou le génère si nouveau.
            else {
                // Génère l'objet.
                $object = $this->_nebuleInstance->newObject($id);
                $co[$id] = $object;
                // Génère la liste de ses dérivés connus.
                $sub = $object->findOneLevelUpdates($present, $synchro);
                $cl[$id] = $sub;
                // Marque l'objet comme vu pour qu'il ne soit pas réutilisé.
                $ov[$id] = true;
            }

            // Calcul le dérivé suivant dans la liste.
            // Prend le plus récent non déjà vu.
            // Si aucun trouvé, retourn l'ID 0.
            $s = '0';
            foreach ($sub as $sid) {
                if (!isset($ov[$sid])) {
                    $s = $sid;
                    break;
                }
            }

            // Si pas de dérivé connu...
            if ($s == '0') {
                // Si objet présent ...
                if ($this->_io->checkObjectPresent($object)) {
                    $ok = true; // On a trouvé l'ID de la dernière mise à jour de l'objet.
                } // ... sinon on remonte d'un niveau.
                else {
                    if ($i == 0) {
                        $ok = true; // OK et retourne implicitement l'ID de l'objet dont on recherchait une mise à jour.
                    } else {
                        $i--;
                        $id = $l[$i]; // Reprend l'ID de la branche du dessus.
                    }
                }
            } else // ... sinon, on continue la recherche sur l'ID dérivé.
            {
                $id = $s;
            }
        }

        // A faire...
        unset($l, $i, $j, $c, $s, $ov, $oi, $ok);
        return $id;
    }

    /**
     * Recherche les identifiants des objets définits comme mises à jour de l'objet courant.
     * Résoud une seule branche du graphe des mises à jours d'un objet.
     * - $present permet de controler si l'on veut que l'objet final soit bien présent localement.
     * - $synchro permet ou non la synchronisation des liens et objets auprès d'entités tierces, en clair on télécharge ce qui manque au besoin lors du parcours du graphe.
     * On extrait les liens u pré-filtrés x et triés pas date.
     * Retourne un tableau des ID des objets, vide si aucun trouvé.
     *
     * @param boolean $present
     * @param boolean $synchro
     * @return array:string
     */
    public function findOneLevelUpdates($present = true, $synchro = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $x = $this->_nebuleInstance->getOption('permitListInvalidLinks');
        $v = 'nebule/liens/version/' . $this->_nebuleInstance->getOption('defaultLinksVersion'); // Version de lien.
        $r = array(); // Tableau des liens de mise à jour.
        $o = array(); // Tableau des objets résultats.
        if (!$this->_io->checkLinkPresent($this->_id)) {
            return $o;
        }
        $i = 0; // Indice de position dans le tableau des liens.

        // Lit les liens.
        $links = $this->_io->linksRead($this->_id);
        $this->_metrology->addLog('Object links count read ' . $this->_id . ' ' . sizeof($links), Metrology::LOG_LEVEL_DEBUG); // Log

        // Analyse les liens, les filtre et les convertis en tableau d'objets de type lien.
        foreach ($links as $link) {
            // Ce traitement sur champs brutes est une optimisation du temps de traitement. Le gain est de 1 à 10.
            // Extrait les champs du lien.
            $j = 1;                        // Indice du champs lu, de 1 à 7.
            $c = array();                    // Table des champs lus.
            $e = strtok(trim($link), '_');    // Lecture du champs.
            while ($e !== false)            // Extrait le lien.
            {
                $c[$j] = trim($e);
                if ($j < 8) {
                    $e = strtok('_');
                } else {
                    $e = false;
                }
                $j++;
            }

            if (substr($link, 0, 21) == 'nebule/liens/version/') {
                $v = trim(substr($link, 0, 25)); // Mémorise la version mais ne valide pas la ligne comme lien.
            } elseif ($j == 8) {
                // Filtre les liens. Ne tient pas compte du champs meta.
                if (($c[4] == 'u'
                        || $c[4] == 'x'
                    )
                    && $c[5] == $this->_id
                    && $c[6] != '0'
                ) {
                    $l = $this->_nebuleInstance->newLink($link, $v);
                    if ($l->getValid() || $x) {
                        $r[$i] = $l;        // on l'écrit dans le tableau des résultats.
                        $i++;
                    }
                }
            }
        }
        unset($links, $link, $j, $c, $e);

        // Tri les liens par date.
        $this->_arrayDateSort($r);

        // Supprime les liens marqués supprimés.
        $s = sizeof($r); // Nombre de liens.
        $links = array(); // Tableau des liens non marqués supprimés.
        foreach ($r as $l) {
            if ($l->getAction_disabled() == 'x') {
                continue;
            }
            $ok = true;
            for ($j = 0; $j < $s; $j++) {
                // Teste si le lien en cours (i) est supprimé par un lien plus récent (j).
                if ($r[$j]->getAction_disabled() == 'x'
                    && $l->getHashSource_disabled() == $r[$j]->getHashSource_disabled()
                    && $l->getHashTarget_disabled() == $r[$j]->getHashTarget_disabled()
                    && $this->_nebuleInstance->dateCompare($l->getDate_disabled(), $r[$j]->getDate_disabled()) <= 0)
                    $ok = false;
            }
            if ($ok) {
                $links[$l->getHashTarget_disabled()] = $l;
                // Ecrase les liens même source même destination à une date antérieure.
            }
        }
        unset($i, $s, $r);

        // Extrait les objets cibles des liens si les objets sont présents.
        if (sizeof($links) == 0) {
            return $o;
        }
        $i = 0;
        if ($present) // Si besoin, teste la présence des objets avant de les ajouter.
        {
            foreach ($links as $l) {
                if ($this->_io->checkObjectPresent($l->getHashTarget_disabled())) {
                    $o[$i] = $l->getHashTarget_disabled();
                    $i++;
                } else {
                    // Tentative de synchronisation.
                    $t = $this->_nebuleInstance->newObject($id);
                    $t->syncLinks();
                    $t->syncObject();
                    if ($this->_io->checkObjectPresent($l->getHashTarget_disabled())) {
                        $o[$i] = $l->getHashTarget_disabled();
                        $i++;
                    }
                }
            }
        } else // Sinon ajoute tous les objets.
        {
            foreach ($links as $l) {
                $o[$i] = $l->getHashTarget_disabled();
                $i++;
            }
        }
        unset($i, $l, $links);

        return $o;
    }

    /**
     * Extrait l'identifiant de l'objet (un seul) définit comme mise à jour de l'objet courant.
     * Dérivé de la fonction précédente findOneLevelUpdates().
     *
     * @param boolean $present
     * @param boolean $synchro
     * @return string
     */
    public function findOneLevelUpdate($present = true, $synchro = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Extrait les mises à jours.
        $objects = $this->findOneLevelUpdates($present, $synchro);

        // Vérifie si il y a des réponses.
        if (sizeof($objects) == 0) {
            return '0';
        }

        // Extrait le dernier objet.
        $object = end($objects);
        unset($objects);

        // Test la présence de l'objet.
        if ($this->_io->checkObjectPresent($object)) {
            return $object;
        }
        return '0';
    }


    /**
     * Liste les liens des objets par référence, référencés par l'objet instancié.
     *
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @return array:string
     */
    private function _getReferencedByLinks($reference = '')
    {
        // Si pas de référence, utilise la référence par défaut.
        if ($reference == '') {
            $reference = nebule::REFERENCE_NEBULE_REFERENCE;
        }

        // Converti au besoin en hash.
        if (!ctype_xdigit($reference)) {
            $reference = $this->_crypto->hash($reference);
        }

        // Liste les liens à la recherche de la propriété.
        $list = $this->readLinksFilterFull(
            '',
            '',
            'f',
            $this->_id,
            '',
            $reference
        );

        return $list;
    }

    /**
     * Liste les liens des objets par référence, les objets qui référencent l'objet instancié.
     *
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @return array:string
     */
    private function _getReferenceToLinks($reference = '')
    {
        // Si pas de référence, utilise la référence par défaut.
        if ($reference == '') {
            $reference = nebule::REFERENCE_NEBULE_REFERENCE;
        }

        // Converti au besoin en hash.
        if (!ctype_xdigit($reference)) {
            $reference = $this->_crypto->hash($reference);
        }

        // Liste les liens à la recherche de la propriété.
        $list = $this->readLinksFilterFull(
            '',
            '',
            'f',
            '',
            $this->_id,
            $reference
        );

        return $list;
    }

    /**
     * Cherche l'ID de l'objet par référence.
     * Si pas trouvé, retourne l'ID de l'objet sur lequel s'effectue la recherche.
     *
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return string
     */
    public function getReferencedObjectID($reference = '', $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferencedByLinks($reference);

        if (sizeof($links) == 0) {
            return $this->_id;
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Extrait le dernier de la liste.
        $link = end($links);
        unset($links);

        if (!is_a($link, 'link')) {
            return $this->_id;
        }

        return $link->getHashTarget();
    }

    /**
     * Cherche l'ID du signataire de l'objet par référence.
     * Si pas trouvé, retourne 0.
     *
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return string
     */
    public function getReferencedSignerID($reference = '', $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferencedByLinks($reference);

        if (sizeof($links) == 0) {
            return '0';
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        /**
         * Extrait le dernier de la liste.
         *
         * @var Link
         */
        $link = end($links);
        unset($links);

        if (!is_a($link, 'link')) {
            return '0';
        }

        return $link->getHashSigner();
    }

    /**
     * Cherche la liste des ID des signataires de l'objet par référence.
     * Si pas trouvé, retourne 0.
     *
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return array:string
     */
    public function getReferencedListSignersID($reference = '', $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $list = array();

        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferencedByLinks($reference);

        if (sizeof($links) == 0) {
            return $list;
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Extrait les signataires de la liste.
        $listOK = array();
        foreach ($links as $link) {
            if (!isset($listOK[$link->getHashSigner()])) {
                $list[] = $link->getHashSigner();
                $listOK[$link->getHashSigner()] = true;
            }
        }
        unset($links, $listOK);

        return $list;
    }

    /**
     * Cherche l'instance de l'objet par référence.
     * Si pas trouvé, retourne l'instance de l'objet sur lequel s'effectue la recherche.
     *
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return Node
     */
    public function getReferencedObjectInstance($reference, $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_nebuleInstance->convertIdToTypedObjectInstance($this->getReferencedObjectID($reference, $socialClass));
    }

    /**
     * Cherche si l'objet est une référence.
     *
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     * Les références sont converties en hash en hexadécimal.
     * Si la référence est un texte en hexadécimal, c'est à dire un ID d'objet, alors c'est utilisé directement.
     *
     * @param string $reference
     * @param string $socialClass
     * @return boolean
     */
    public function getIsReferencedBy($reference = '', $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferencedByLinks($reference);

        if (sizeof($links) == 0) {
            return false;
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        if (sizeof($links) == 0) {
            return false;
        }
        return true;
    }

    /**
     * Cherche si l'objet est référencé par une autre objet.
     *
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     * Les références sont converties en hash en hexadécimal.
     * Si la référence est un texte en hexadécimal, c'est à dire un ID d'objet, alors c'est utilisé directement.
     *
     * @param string $reference
     * @param string $socialClass
     * @return boolean
     */
    public function getIsReferenceTo($reference = '', $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferenceToLinks($reference);

        if (sizeof($links) == 0) {
            return false;
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        if (sizeof($links) == 0) {
            return false;
        }
        return true;
    }

    /**
     * Cherche l'ID de l'objet qui référence l'objet courant.
     * Si pas trouvé, retourne l'ID de l'objet sur lequel s'effectue la recherche.
     *
     * Si le type de référence $reference n'est pas précisée, utilise nebule::REFERENCE_NEBULE_REFERENCE.
     *
     * @param string $reference
     * @param string $socialClass
     * @return string
     */
    public function getReferenceToObjectID($reference = '', $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste les liens à la recherche de la propriété.
        $links = $this->_getReferenceToLinks($reference);

        if (sizeof($links) == 0) {
            return $this->_id;
        }

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Extrait le dernier de la liste.
        $link = end($links);
        unset($links);

        if (!is_a($link, 'link')) {
            return $this->_id;
        }

        return $link->getHashSource();
    }


    /**
     * Synchronisation de l'objet.
     * @param boolean $hardSync
     * @return boolean
     * @todo
     *
     */
    public function syncObject($hardSync = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($hardSync !== true) {
            $hardSync = false;
        }

        // Vérifie que l'objet ne soit pas déjà présent.
        if ($this->_io->checkObjectPresent($this->_id)) {
            return true;
        }

        // Vérifie si autorisé.
        if (!$this->_nebuleInstance->getOption('permitWriteObject')) {
            return false;
        }
        if (!$this->_nebuleInstance->getOption('permitSynchronizeObject')) {
            return false;
        }

        // Liste les liens à la recherche de la propriété de localisation.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            '',
            '',
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION));

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($links) == 0
            && $this->_nebuleInstance->getOption('permitListOtherHash')
        ) {
            // A faire...
        }
        if (sizeof($links) == 0) {
            return false;
        }

        // Fait un tri par pertinance sociale.
        // A faire...

        // Extrait le contenu des objets de propriété.
        foreach ($links as $i => $l) {
            $localisations[$i] = $this->_readOneLineOtherObject($l->getHashTarget());
        }

        // Synchronisation
        foreach ($localisations as $localisation) {
            // Lecture de l'objet.
            $data = $this->_io->objectRead($this->_id, 0, $localisation);
            // Ecriture de l'objet.
            $this->_io->objectWrite($data);
        }

        unset($localisations, $localisation);
        return true;
    }

    /**
     * Synchronisation des liens de l'objet.
     * @param boolean $hardSync
     * @return boolean
     * @todo
     *
     */
    public function syncLinks($hardSync = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($hardSync !== true) {
            $hardSync = false;
        }

        // Vérifie si autorisé.
        if (!$this->_nebuleInstance->getOption('permitWriteLink')) {
            return false;
        }
        if (!$this->_nebuleInstance->getOption('permitSynchronizeLink')) {
            return false;
        }

        // Liste les liens à la recherche de la propriété de localisation.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            '',
            '',
            $this->_crypto->hash('nebule/objet/entite/localisation'));

        // Fait une recherche sur d'autres types de hash si celui par défaut ne renvoie rien.
        if (sizeof($links) == 0
            && $this->_nebuleInstance->getOption('permitListOtherHash')
        ) {
            // A faire...
        }
        if (sizeof($links) == 0) {
            return false;
        }

        // Fait un tri par pertinance sociale.
        // A faire...

        // Extrait le contenu des objets de propriété.
        foreach ($links as $i => $l) {
            $localisations[$i] = $this->_readOneLineOtherObject($l->getHashTarget());
        }

        // Synchronisation
        $link = null;
        $linkInstance = null;
        foreach ($localisations as $localisation) {
            $links = $this->_io->linksRead($this->_id, $localisation);
            $this->_metrology->addLog('Object links count read ' . $this->_id . ' ' . sizeof($links), Metrology::LOG_LEVEL_DEBUG); // Log

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
    public function deleteObject()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $deleteObject = true;

        // Détecte si l'objet est protégé.
        $this->_getMarkProtected();
        $protected = ($this->_markProtectedChecked && $this->_cacheMarkProtected);
        if ($protected) {
            $id = $this->_idUnprotected;
        } else {
            $id = $this->_id;
        }

        // Création lien.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $source = $id;
        $link = '0_' . $signer . '_' . $date . '_d_' . $source . '_0_0';
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->signWrite();

        // Lit les liens.
        $links = $this->readLinksUnfiltred();
        $entity = $this->_nebuleInstance->getCurrentEntity();
        foreach ($links as $link) {
            // Vérifie si l'entité signataire du lien est l'entité courante.
            if ($link->getHashSigner() != $entity) {
                // Si ce n'est pas l'entité courante, quitte.
                $this->_metrology->addAction('delobj', $id, false);
                $deleteObject = false;
            }
        }

        if ($deleteObject) {
            // Supprime l'objet.
            $r1 = $this->_io->objectDelete($id); // FIXME declare vars r1 r2
            $r2 = true;

            // Métrologie.
            $this->_metrology->addAction('delobj', $id, $r1);
        }

        // Si protégé.
        if ($protected) {
            $this->_metrology->addLog('Delete protected object ' . $this->_id, Metrology::LOG_LEVEL_NORMAL); // Log
            $id = $this->_idProtected;

            // Création lien.
            $signer = $this->_nebuleInstance->getCurrentEntity();
            $date = date(DATE_ATOM);
            $source = $id;
            $link = '0_' . $signer . '_' . $date . '_d_' . $source . '_0_0';
            $newLink = new Link($this->_nebuleInstance, $link);
            $newLink->signWrite();

            // Lit les liens.
            $links = $this->readLinksUnfiltred();
            $entity = $this->_nebuleInstance->getCurrentEntity();
            foreach ($links as $link) {
                // Vérifie si l'entité signataire du lien est l'entité courante.
                if ($link->getHashSigner() != $entity) {
                    // Si ce n'est pas l'entité courante, quitte.
                    $this->_metrology->addAction('delobj', $id, false);
                    $deleteObject = false;
                }
            }

            if ($deleteObject) {
                // Supprime l'objet.
                $r2 = $this->_io->objectDelete($id);

                // Métrologie.
                $this->_metrology->addAction('delobj', $id, $r);
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
    public function deleteObjectLinks()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Création lien.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $source = $this->_id;
        $link = '0_' . $signer . '_' . $date . '_d_' . $source . '_0_0';
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->signWrite();

        // Lit les liens.
        $links = $this->readLinksUnfiltred();
        $entity = $this->_nebuleInstance->getCurrentEntity();
        foreach ($links as $link) {
            // Vérifie si l'entité signataire du lien est l'entité courante.
            if ($link->getHashSigner() != $entity) {
                // Si ce n'est pas l'entité courante, quitte.
                unset($links, $entity, $link);
                return false;
            }
        }

        unset($links, $entity, $link);

        // Supprime l'objet.
        $r = $this->_io->objectDelete($this->_id);

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
    public function deleteForceObject()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Création lien.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $source = $this->_id;
        $link = '0_' . $signer . '_' . $date . '_d_' . $source . '_0_0';
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->signWrite();

        // Supprime l'objet.
        $r = $this->_io->objectDelete($this->_id);

        // Métrologie.
        $this->_metrology->addAction('delobj', $this->_id, $r);

        return $r;
    }

    /**
     * Supprime un objet et ses liens.
     * Force l'opération si l'entité est autorisée à le faire.
     */
    public function deleteForceObjectLinks()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Supprime l'objet.
        $this->_io->objectDelete($this->_id);

        // Supprime les liens de l'objet.
        $this->_io->linksDelete($this->_id);
    }

    /**
     * Trie un tableau de liens en fonction des dates des liens.
     *
     * @param array $list
     * @return boolean
     */
    protected function _arrayDateSort(&$list)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if (sizeof($list) == 0) {
            return false;
        }
        foreach ($list as $n => $t) {
            $linkdate[$n] = $t->getDate();
        }
        array_multisort($linkdate, SORT_STRING, SORT_DESC, $list);
        unset($n, $t, $linkdate);
        return true;
    }


    /**
     * Ecrit l'objet si non présent.
     *
     * @return boolean
     */
    public function write()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si protégé.
        if ($this->_cacheMarkProtected) {
            $this->_metrology->addAction('addobj', $this->_id, false);
            $this->_metrology->addLog('Write objet error, protected.', Metrology::LOG_LEVEL_ERROR);
            return false;
        }

        // Si pas de données.
        if (!$this->_haveData) {
            $this->_metrology->addAction('addobj', '0', false);
            $this->_metrology->addLog('Write objet error, no data.', Metrology::LOG_LEVEL_ERROR);
            return false;
        }

        if (!$this->_io->checkObjectPresent($this->_id)) {
            // Si autorisé à écrire un nouvel objet.
            if ($this->_nebuleInstance->getOption('permitWriteObject')
                && $this->_nebuleInstance->getOption('permitCreateObject')
            ) {
                $id = $this->_io->objectWrite($this->_data);
            } else {
                $id = false;
            }
        } else {
            $id = $this->_id;
        }

        // vide les données.
        $this->_data = '';
        $this->_haveData = false;

        // Métrologie.
        $v = true;
        if ($id === false
            || $id != $this->_id
        ) {
            $v = false;
            // Si l'écriture échoue, on crée l'objet d'ID '0'. @todo à revoir si vraiment utile... pareil pour entities->write().
            $this->_id = '0';
        }
        $this->_metrology->addAction('addobj', $this->_id, $v);
        $this->_metrology->addLog('OK write objet ' . $this->_id, Metrology::LOG_LEVEL_DEBUG);

        return $v;
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#o">O / Objet</a>
            <ul>
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

                <?php Entity::echoDocumentationTitles(); ?>

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
                <?php Group::echoDocumentationTitles(); ?>

                <?php Conversation::echoDocumentationTitles(); ?>

                <?php Localisation::echoDocumentationTitles(); ?>

                <?php Applications::echoDocumentationTitles(); /* Inclu les modules. */ ?>

                <?php Currency::echoDocumentationTitles(); /* Inclu les sacs et jetons. */ ?>

            </ul>
        </li>
        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h1 id="o">O / Objet</h1>
        <p>L'objet est le contenant de toutes les informations.</p>

        <h2 id="oo">OO / Objet</h2>
        <p>L’objet est un agglomérat de données numériques.</p>
        <p>Un objet numérique est identifié par une empreinte ou condensat (hash) numérique de type cryptographique.
            Cette empreinte est à même d'empêcher la modification du contenu d'un objet, intentionnellement ou non (cf
            <a href="#co">CO</a>).</p>

        <h3 id="oon">OON / Nommage</h3>
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
        <p>La protection d'un objet va permettre de cacher le contenu de l'objet.</p>
        <p>A faire...</p>

        <h3 id="ood">OOD / Dissimulation</h3>
        <p>La dissimulation des liens d'un objet va permettre de cacher la présence ou l'usage d'un objet.</p>
        <p>A faire...</p>

        <h3 id="ool">OOL / Liens</h3>
        <p>A faire...</p>

        <h3 id="ooc">OOC / Création</h3>
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
        <p>Tous les contenus des objets sont stockés dans un même emplacement ou sont visible comme étant dans un même
            emplacement. Cet emplacement ne contient pas les liens (cf <a href="#ls">LS</a>).</p>
        <p>A faire...</p>

        <h3 id="oosa">OOSA / Arborescence</h3>
        <p>Sur un système de fichiers, tous les contenus des objets sont stockés dans des fichiers contenus dans le
            dossier <code>pub/o/</code> (<code>o</code> comme objet).</p>
        <p>A faire...</p>

        <h3 id="oot">OOT / Transfert</h3>
        <p>A faire...</p>

        <h3 id="oor">OOR / Réservation</h3>
        <p>Les différentes objets réservés pour les besoins de la bibliothèque nebule :</p>
        <ul>
            <?php
            $list = nebule::$RESERVED_OBJECTS_LIST;
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
        <p>A faire...</p>

        <h4 id="ooia">OOIA / Implémentation des Actions</h4>
        <p>A faire...</p>

        <h3 id="oov">OOV / Vérification</h3>
        <p>L’empreinte d’un objet doit être vérifiée lors de la fin de la réception de l’objet. L’empreinte d’un objet
            devrait être vérifiée avant chaque utilisation de cet objet. Un contenu d'objet avec une empreinte qui ne
            lui correspond pas doit être supprimé. Lors de la suppression d’un objet, les liens de cet objet ne sont pas
            supprimés. La vérification de la validité des liens est complètement indépendante de celle des objets, et
            inversement (cf <a href="#co">CO</a> et <a href="#lv">LV</a>).</p>

        <h3 id="ooo">OOO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php Entity::echoDocumentationCore(); ?>

        <h2 id="or">OR / Référence</h2>
        <p>A faire...</p>

        <h3 id="orn">ORN / Nommage</h3>
        <p>A faire...</p>

        <h3 id="orp">ORP / Protection</h3>
        <p>A faire...</p>

        <h3 id="ord">ORD / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="orl">ORL / Liens</h3>
        <p>A faire...</p>

        <h3 id="orc">ORC / Création</h3>
        <p>Liste des liens à générer lors de la création d'une entité.</p>
        <p>A faire...</p>

        <h3 id="ors">ORS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="ort">ORT/ Transfert</h3>
        <p>A faire...</p>

        <h3 id="orr">ORR / Réservation</h3>
        <p>A faire...</p>

        <h4 id="orio">ORIO / Implémentation des Options</h4>
        <p>A faire...</p>

        <h4 id="oria">ORIA / Implémentation des Actions</h4>
        <p>A faire...</p>

        <h3 id="oro">ORO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php Group::echoDocumentationCore(); ?>

        <?php Conversation::echoDocumentationCore(); ?>

        <?php Localisation::echoDocumentationCore(); ?>

        <?php Applications::echoDocumentationCore(); /* Inclu les modules. */ ?>

        <?php Currency::echoDocumentationCore(); /* Inclu les sacs et jetons. */ ?>

        <?php
    }
}


/**
 * ------------------------------------------------------------------------------------------
 * La classe Group.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'un groupe ou 'new' ;
 *
 * L'ID d'un groupe est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture du groupe ou lors de la création, assigne l'ID 0.
 *
 * Tout objet peut devenir un groupe sans avoir été préalablement marqué comme groupe.
 * Le simple faire de faire un lien pour désigner un objet comme membre du groupe d'un autre objet
 *   suffit à créer le groupe.
 * ------------------------------------------------------------------------------------------
 */
class Group extends Node
{
    // Suffixe d'identifiant de nouveaux groupes.
    const DEFAULT_SUFFIX_NEW_GROUP = '006e6562756c652f6f626a65742f67726f757065';

    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_id',
        '_fullname',
        '_cacheProperty',
        '_cacheProperties',
        '_cacheMarkProtected',
        '_idProtected',
        '_idUnprotected',
        '_idProtectedKey',
        '_idUnprotectedKey',
        '_markProtectedChecked',
        '_cacheCurrentEntityUnlocked',
        '_usedUpdate',
        '_isGroup',
        '_isConversation',
        '_isMarkClosed',
        '_isMarkProtected',
        '_isMarkObfuscated',
        '_referenceObject',
        '_referenceObjectClosed',
        '_referenceObjectProtected',
        '_referenceObjectObfuscated',
    );

    /**
     * Constructeur.
     *
     * Toujours transmettre l'instance de la librairie nebule.
     * Si le groupe existe, juste préciser l'ID de celle-ci.
     * Si c'est un nouveau groupe à créer, mettre l'ID à 'new'.
     *
     * @param nebule $nebuleInstance
     * @param string $id
     * @param boolean $closed
     */
    public function __construct(nebule $nebuleInstance, $id, $closed = false, $obfuscated = false)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance group ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id) && $id != '' && ctype_xdigit($id)) {
            // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
            $this->_loadGroup($id);
        } elseif (is_string($id) && $id == 'new') {
            // Si c'est un nouveau groupe à créer, renvoie à la création.
            $this->_createNewGroup($closed, $obfuscated);
        } else {
            // Sinon, le groupe est invalide, retourne 0.
            $this->_id = '0';
        }

        // Pré-calcul les références.
        $this->getReferenceObject();
        $this->getReferenceObjectClosed();
        $this->getReferenceObjectProtected();
        $this->getReferenceObjectObfuscated();
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_id;
    }

    /**
     * Retourne les variables à sauvegarder dans la session php lors d'une mise en sommeil de l'instance.
     *
     * @return array:string
     */
    public function __sleep()
    {
        return self::SESSION_SAVED_VARS;
    }

    /**
     * Foncion de réveil de l'instance et de ré-initialisation de certaines variables non sauvegardées.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_cacheMarkDanger = false;
        $this->_cacheMarkWarning = false;
        $this->_cacheUpdate = '';
    }

    /**
     * Chargement d'un groupe existant.
     *
     * @param string $id
     */
    private function _loadGroup($id)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que c'est bien un objet.
        if (!is_string($id)) {
            $id = '0';
        } elseif ($id == '') {
            $id = '0';
        } elseif (!ctype_xdigit($id)) {
            $id = '0';
        } elseif (!$this->_io->checkLinkPresent($id)) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrology->addLog('Load group ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log
        $this->getIsGroup();
    }

    /**
     * Création d'un nouveau groupe.
     *
     * @param boolean $closed
     */
    protected function _createNewGroup($closed, $obfuscated)
    {
        $this->_metrology->addLog(__METHOD__, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que l'on puisse créer un groupe et tous ses attributs.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitCreateObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCreateLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // calcul l'ID.
            $this->_id = $this->_nebuleInstance->getCrypto()->hash($this->_nebuleInstance->getCrypto()->getPseudoRandom(128)) . self::DEFAULT_SUFFIX_NEW_GROUP;

            // Log
            $this->_metrology->addLog('Create group ' . $this->_id, Metrology::LOG_LEVEL_DEBUG);

            // Mémorise les données.
            $this->_data = null;
            $this->_haveData = false;

            $signer = $this->_nebuleInstance->getCurrentEntity();
            $date = date(DATE_ATOM);
            $hashGroup = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE);

            // Création lien de hash.
            $date2 = $date;
            if ($obfuscated) {
                $date2 = '0';
            }
            $action = 'l';
            $source = $this->_id;
            $target = $this->_crypto->hash($this->_crypto->hashAlgorithmName());
            $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_HASH);
            $link = '0_' . $signer . '_' . $date2 . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new Link($this->_nebuleInstance, $link);
            $newLink->signWrite();

            // Création lien de groupe.
            $action = 'l';
            $source = $this->_id;
            $target = $hashGroup;
            $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
            $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
            $newLink = new Link($this->_nebuleInstance, $link);
            $newLink->sign();
            if ($obfuscated) {
                $newLink->obfuscate();
            }
            $newLink->write();

            if ($closed) {
                // Création lien de groupe fermé.
                $action = 'l';
                $source = $this->_id;
                $target = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_FERME, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
                $meta = $hashGroup;
                $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                $newLink = new Link($this->_nebuleInstance, $link);
                $newLink->sign();
                if ($obfuscated) {
                    $newLink->obfuscate();
                }
                $newLink->write();

                $this->_isMarkClosed = true;
            } else {
                $this->_isMarkClosed = false;
            }

            // Ecrit l'objet du groupe.
            $this->write();
            $this->_isGroup = true;
        } else {
            $this->_metrology->addLog('Create group error no autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }

    /**
     * Extrait l'ID de l'entité.
     * Filtre l'entité et s'assure que c'est une entité.
     *
     * @param string|Node|entity $entity
     * @return string
     */
    protected function _checkExtractEntityID($entity)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $entityInstance = null;
        if (is_string($entity)) {
            if ($entity == ''
                || $entity == '0'
                || !ctype_xdigit($entity)
                || !$this->_io->checkLinkPresent($entity)
            ) {
                $id = '';
            } else {
                $id = $entity;
                $entityInstance = $this->_nebuleInstance->newEntity($id);
            }
        } elseif (is_a($entity, 'Node')) {
            $id = $entity->getID();
            if ($id == '0') {
                $id = '';
            } else {
                $entityInstance = $entity;
            }
        } else {
            $id = '';
        }

        if ($id == '0') {
            $id = '';
        }

        if ($id != ''
            && !$entityInstance->getIsEntity('all')
        ) {
            $id = '';
        }
        unset($entityInstance);

        return $id;
    }

    /**
     * Filtre l'objet.
     *
     * @param string|Node $object
     * @return string
     */
    private function _checkExtractObjectID($object)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if (is_string($object)) {
            if ($object == ''
                || $object == '0'
                || !ctype_xdigit($object)
                || !$this->_io->checkLinkPresent($object)
            ) {
                $id = '';
            } else {
                $id = $object;
            }
        } elseif (is_a($object, 'Node')) {
            $id = $object->getID();
            if ($id == '0') {
                $id = '';
            }
        } else {
            $id = '';
        }

        if ($id == '0') {
            $id = '';
        }

        return $id;
    }



    // Désactivation des fonctions de protection et autres.

    /**
     * Vérifie la consistance de l'objet.
     *
     * Retourne toujours true pour une conversation.
     * Il n'y a pas de contenu à vérifier pour un objet de référence.
     *
     * @return boolean
     */
    public function checkConsistency()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return true;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function getReloadMarkProtected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return string
     */
    public function getProtectedID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return '0';
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return string
     */
    public function getUnprotectedID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_id;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function setProtected($obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function setUnprotected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function setProtectedTo($entity)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return array
     */
    public function getProtectedTo()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return array();
    }


    /**
     * Ecrit l'objet comme n'étant plus un groupe.
     * @todo détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @return boolean
     */
    public function unsetGroup()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si déjà marqué, donne le résultat tout de suite.
        if (!$this->_isGroup) {
            return true;
        }

        // Création lien de suppression de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'x';
        $source = $this->_id;
        $target = $this->getReferenceObject();
        $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->signWrite();

        $this->_isGroup = false;
        return true;
    }


    /**
     * Variable si l'objet est marqué comme un groupe fermé.
     * @var boolean
     */
    protected $_isMarkClosed = false;

    /**
     * Retourne si le groupe est marqué comme fermé.
     * En sélectionnant une entité, fait la recherche de marquage pour cette entité comme contributrice.
     *
     * @param string|Node|entity $entity
     * @return boolean
     */
    public function getMarkClosed($entity = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Liste tous mes liens de définition de groupe fermé.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $this->_id,
            $id,
            $this->getReferenceObjectClosed()
        );

        // Fait un tri par pertinance sociale. Forcé à myself.
        $this->_social->arraySocialFilter($links, 'myself');

        // Mémorise le r&sultat.
        if ($id == $this->_nebuleInstance->getCurrentEntity()) {
            if (sizeof($links) != 0) {
                $this->_isMarkClosed = true;
            } else {
                $this->_isMarkClosed = false;
            }
        }

        // Retourne le résultat.
        if (sizeof($links) != 0) {
            return true;
        }

        return false;
    }

    /**
     * Ecrit l'objet comme un groupe fermé.
     *
     * @param string|Node|entity $entity
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setMarkClosed($entity = '', $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated('')) {
            $obfuscated = true;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->getMarkClosed()) {
            return true;
        }

        // Création du lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        $source = $this->_id;
        $target = $id;
        $meta = $this->getReferenceObjectClosed();
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();

        // Si besoin, obfuscation du lien.
        if ($obfuscated) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        if ($newLink->write()) {
            if ($id == $this->_nebuleInstance->getCurrentEntity()) {
                $this->_isMarkClosed = true;
            }
            return true;
        }
        return false;
    }

    /**
     * Ecrit l'objet comme n'étant pas un groupe fermé.
     * @todo détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @param string|Node|entity $entity
     * @return boolean
     */
    public function unsetMarkClosed($entity = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Si déjà marqué, donne le résultat tout de suite.
        if (!$this->getMarkClosed()) {
            return true;
        }

        // Création lien de suppression de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'x';
        $source = $this->_id;
        $target = $id;
        $meta = $this->getReferenceObjectClosed();
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);

        if ($newLink->signWrite()) {
            if ($id == $this->_nebuleInstance->getCurrentEntity()) {
                $this->_isMarkClosed = false;
            }
            return true;
        }
        return false;
    }


    /**
     * Variable si l'objet est marqué comme un groupe protégé.
     * @var boolean
     */
    protected $_isMarkProtected = false;

    /**
     * Retourne si le groupe est marqué comme protégé.
     * En sélectionnant une entité, fait la recherche de marquage pour cette entité comme contributrice.
     *
     * @param string|Node|entity $entity
     * @return boolean
     */
    public function getMarkProtected($entity = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Liste tous mes liens de définition de groupe protégé.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $this->_id,
            $id,
            $this->getReferenceObjectProtected()
        );

        // Fait un tri par pertinance sociale. Forcé à myself.
        $this->_social->arraySocialFilter($links, 'myself');

        // Mémorise le r&sultat.
        if ($id == $this->_nebuleInstance->getCurrentEntity()) {
            if (sizeof($links) != 0) {
                $this->_isMarkProtected = true;
            } else {
                $this->_isMarkProtected = false;
            }
        }

        // Retourne le résultat.
        if (sizeof($links) != 0) {
            return true;
        }

        return false;
    }

    /**
     * Ecrit l'objet comme un groupe protégé.
     *
     * @param string|Node|entity $entity
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setMarkProtected($entity = '', $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated('')) {
            $obfuscated = true;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->getMarkProtected()) {
            return true;
        }

        // Création du lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        $source = $this->_id;
        $target = $id;
        $meta = $this->getReferenceObjectProtected();
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();

        // Si besoin, obfuscation du lien.
        if ($obfuscated) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        if ($newLink->write()) {
            if ($id == $this->_nebuleInstance->getCurrentEntity()) {
                $this->_isMarkProtected = true;
            }
            return true;
        }
        return false;
    }

    /**
     * Ecrit l'objet comme n'étant pas un groupe protégé.
     * @todo détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @param string|Node|entity $entity
     * @return boolean
     */
    public function unsetMarkProtected($entity = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Si déjà marqué, donne le résultat tout de suite.
        if (!$this->getMarkProtected()) {
            return true;
        }

        // Création lien de suppression de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'x';
        $source = $this->_id;
        $target = $id;
        $meta = $this->getReferenceObjectProtected();
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);

        if ($newLink->signWrite()) {
            if ($id == $this->_nebuleInstance->getCurrentEntity()) {
                $this->_isMarkProtected = false;
            }
            return true;
        }
        return false;
    }


    /**
     * Variable si l'objet est marqué comme un groupe dissimulé.
     * @var boolean
     */
    protected $_isMarkObfuscated = false;

    /**
     * Retourne si le groupe est marqué comme dissimulé.
     * En sélectionnant une entité, fait la recherche de marquage pour cette entité comme contributrice.
     *
     * @param string|Node|entity $entity
     * @return boolean
     */
    public function getMarkObfuscated($entity = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Désactivée si option à false.
        if (!$this->_nebuleInstance->getOption('permitObfuscatedLink')) {
            return false;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Liste tous mes liens de définition de groupe dissimulé.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $this->_id,
            $id,
            $this->getReferenceObjectObfuscated()
        );

        // Fait un tri par pertinance sociale. Forcé à myself.
        $this->_social->arraySocialFilter($links, 'myself');

        // Mémorise le r&sultat.
        if ($id == $this->_nebuleInstance->getCurrentEntity()) {
            if (sizeof($links) != 0) {
                $this->_isMarkObfuscated = true;
            } else {
                $this->_isMarkObfuscated = false;
            }
        }

        // Retourne le résultat.
        if (sizeof($links) != 0) {
            return true;
        }

        return false;
    }

    /**
     * Ecrit l'objet comme un groupe dissimulé.
     *
     * @param string|Node|entity $entity
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setMarkObfuscated($entity = '', $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Désactivée si option à false.
        if (!$this->_nebuleInstance->getOption('permitObfuscatedLink')) {
            return false;
        }

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated('')) {
            $obfuscated = true;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->getMarkObfuscated()) {
            return true;
        }

        // Création du lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        $source = $this->_id;
        $target = $id;
        $meta = $this->getReferenceObjectObfuscated();
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();

        // Si besoin, obfuscation du lien.
        if ($obfuscated) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        if ($newLink->write()) {
            if ($id == $this->_nebuleInstance->getCurrentEntity()) {
                $this->_isMarkObfuscated = true;
            }
            return true;
        }
        return false;
    }

    /**
     * Ecrit l'objet comme n'étant pas un groupe dissimulé.
     * @todo détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @param string|Node|entity $entity
     * @return boolean
     */
    public function unsetMarkObfuscated($entity = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Désactivée si option à false.
        if (!$this->_nebuleInstance->getOption('permitObfuscatedLink')) {
            return false;
        }

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Si déjà marqué, donne le résultat tout de suite.
        if (!$this->getMarkObfuscated()) {
            return true;
        }

        // Création lien de suppression de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'x';
        $source = $this->_id;
        $target = $id;
        $meta = $this->getReferenceObjectObfuscated();
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);

        if ($newLink->signWrite()) {
            if ($id == $this->_nebuleInstance->getCurrentEntity()) {
                $this->_isMarkObfuscated = false;
            }
            return true;
        }
        return false;
    }


    /**
     * Retourne si l'objet est membre du groupe.
     *
     * @param string|Node $object
     * @param string $socialClass
     * @return boolean
     */
    public function getIsMember($object, $socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Extrait l'ID de l'objet.
        $id = $this->_checkExtractObjectID($object);

        // Vérifie que c'est bien un objet.
        if ($id == '') {
            return false;
        }

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $this->_id,
            $id,
            $this->_id
        );

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        if (sizeof($links) != 0) {
            return true;
        }
        return false;
    }

    /**
     * Ajoute un objet comme membre dans le groupe.
     *
     * @param string|Node $object
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setMember($object, $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated('')) {
            $obfuscated = true;
        }

        // Extrait l'ID de l'objet.
        $id = $this->_checkExtractObjectID($object);

        // Vérifie que c'est bien un objet.
        if ($id == '') {
            return false;
        }

        // Création lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        $source = $this->_id;
        $target = $id;
        $meta = $this->_id;
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();

        // Si besoin, obfuscation du lien.
        if ($obfuscated) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
    }

    /**
     * Retire un membre du groupe.
     * @todo détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @todo retirer la dissimulation déjà faite dans le code.
     *
     * @param string|Node $object
     * @param boolean $obfuscated
     * @return boolean
     */
    public function unsetMember($object = '', $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated('')) {
            $obfuscated = true;
        }

        // Extrait l'ID de l'objet.
        $id = $this->_checkExtractObjectID($object);

        // Vérifie que c'est bien un objet.
        if ($id == '') {
            return false;
        }

        // Création lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'x';
        $source = $this->_id;
        $target = $id;
        $meta = $this->_id;
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();

        // Si besoin, obfuscation du lien.
        if ($obfuscated) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
    }


    /**
     * Extrait la liste des liens définissant les objets du groupe.
     *
     * Le calcul sociale se fait par rapport à la classe sociale demandée,
     *   et donc utilise l'entité de _nebuleInstance ou de _applicationInstance en fonction.
     *
     * @param string $socialClass
     * @param array:string $socialListID
     * @return array:Link
     */
    public function getListMembersLinks($socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste tous les liens des membres de la conversation.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $this->_id,
            '',
            $this->_id
        );

        // Fait un tri par pertinance sociale.
        $this->_social->setList($socialListID);
        $this->_social->arraySocialFilter($links, $socialClass);
        $this->_social->unsetList();

        return $links;
    }

    /**
     * Extrait la liste des ID des objets du groupe.
     *
     * @param string $socialClass
     * @param array:string $socialListID
     * @return array:string
     */
    public function getListMembersID($socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Extrait les liens des groupes.
        $links = $this->getListMembersLinks($socialClass, $socialListID);

        // Extraction des ID cibles.
        $list = array();
        foreach ($links as $link) {
            $list[$link->getHashTarget()] = $link->getHashTarget();
        }

        return $list;
    }

    /**
     * Retourne le nombre d'objets dans le groupe.
     *
     * @param string $socialClass
     * @param array:string $socialListID
     * @return float
     */
    public function getCountMembers($socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return sizeof($this->getListMembersLinks($socialClass, $socialListID));
    }


    /**
     * Retourne si l'entité est à l'écoute du groupe.
     *
     * @param string|Node $entity
     * @param string $socialClass
     * @param array:string $socialListID
     * @return boolean
     */
    public function getIsFollower($entity, $socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        // Vérifie que c'est bien une entité.
        if ($id == '') {
            return false;
        }

        // Liste tous les liens de définition des entités à l'écoutes du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $id,
            $this->_id,
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI)
        );

        // Fait un tri par pertinance sociale.
        $this->_social->setList($socialListID);
        $this->_social->arraySocialFilter($links, $socialClass);
        $this->_social->unsetList();

        if (sizeof($links) != 0) {
            return true;
        }
        return false;
    }

    /**
     * Ajoute une entité comme à l'écoute du groupe.
     *
     * @param string|Node $entity
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setFollower($entity, $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated('')) {
            $obfuscated = true;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        // Vérifie que c'est bien une entité.
        if ($id == '') {
            return false;
        }

        // Création lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        $source = $id;
        $target = $this->_id;
        $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI);
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();

        // Si besoin, obfuscation du lien.
        if ($obfuscated) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
    }

    /**
     * Retire un entité à l'écoute du groupe.
     * @todo détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @todo retirer la dissimulation déjà faite dans le code.
     *
     * @param string|Node $entity
     * @param boolean $obfuscated
     * @return boolean
     */
    public function unsetFollower($entity = '', $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteGroup')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated('')) {
            $obfuscated = true;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        // Vérifie que c'est bien une entité.
        if ($id == '') {
            return false;
        }

        // Création lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'x';
        $source = $id;
        $target = $this->_id;
        $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI);
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();

        // Si besoin, obfuscation du lien.
        if ($obfuscated) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
    }


    /**
     * Extrait la liste des liens définissant les entités à l'écoute du groupe.
     *
     * On ne peut pas voir un groupe comme fermé si on regarde pour une autre entité.
     * La pertinence sociale n'est pas utilisée pour un groupe fermé.
     *
     * @param string $socialClass
     * @param array:string $socialListID
     * @return array:Link
     */
    public function getListFollowersLinks($socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_getListFollowersLinks($this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI), $socialClass, $socialListID);
    }

    /**
     * Extrait la liste des liens définissant les entités à l'écoute d'un objet et définit par une référence de suivi.
     * L'objet définit par une référence de suivi doit se comporter comme un groupe.
     *
     * @param string $reference
     * @param string $socialClass
     * @param array:string $socialListID
     * @return array:Link
     */
    protected function _getListFollowersLinks($reference, $socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie la référence.
        if (!is_string($reference)
            && !ctype_xdigit($reference)
        ) {
            $reference = $this->_crypto->hash($reference);
        }

        // Liste tous les liens des entités à l'écoutes du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            '',
            $this->_id,
            $reference
        );

        // Fait un tri par pertinance sociale.
        $this->_social->setList($socialListID);
        $this->_social->arraySocialFilter($links, $socialClass);
        $this->_social->unsetList();

        return $links;
    }

    /**
     * Extrait la liste des ID des entités à l'écoute du groupe.
     *
     * @param string $socialClass
     * @param array:string $socialListID
     * @return array:string
     */
    public function getListFollowersID($socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Extrait les liens des groupes.
        $links = $this->_getListFollowersLinks($this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI), $socialClass, $socialListID);

        // Extraction des ID cibles.
        $list = array();
        foreach ($links as $link) {
            $list[$link->getHashSource()] = $link->getHashSource();
        }

        return $list;
    }

    /**
     * Retourne le nombre d'entités à l'écoute du groupe.
     *
     * @param string $socialClass
     * @param array:string $socialListID
     * @return float
     */
    public function getCountFollowers($socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return sizeof($this->_getListFollowersLinks($this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI), $socialClass, $socialListID));
    }

    /**
     * Retourne la liste des entités qui ont ajouté l'entité cité comme suiveuse du groupe.
     *
     * @param string $entity
     * @param string $socialClass
     * @param array:string $socialListID
     * @return array:string
     */
    public function getListFollowerAddedByID($entity, $socialClass = 'all', $socialListID = null)
    {
        // Extrait les liens des groupes.
        $links = $this->_getListFollowersLinks($this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI), $socialClass, $socialListID);

        // Extraction des ID cibles.
        $list = array();
        foreach ($links as $link) {
            if ($link->getHashSource() == $entity) {
                $list[$link->getHashSigner()] = $link->getHashSigner();
            }
        }

        return $list;
    }


    /**
     * ID de référence de l'objet.
     *
     * @var string
     */
    private $_referenceObject = '';

    /**
     * Calcule et retourne la référence de l'objet.
     *
     * @return string
     */
    public function getReferenceObject()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_referenceObject == '') {
            $this->_referenceObject = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }
        return $this->_referenceObject;
    }

    /**
     * ID de référence de l'objet de fermeture.
     *
     * @var string
     */
    private $_referenceObjectClosed = '';

    /**
     * Calcule et retourne la référence de l'objet de fermeture.
     *
     * @return string
     */
    public function getReferenceObjectClosed()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_referenceObjectClosed == '') {
            $this->_referenceObjectClosed = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_FERME, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }
        return $this->_referenceObjectClosed;
    }

    /**
     * ID de référence de l'objet de protection des membres.
     *
     * @var string
     */
    private $_referenceObjectProtected = '';

    /**
     * Calcule et retourne la référence de l'objet de protection des membres.
     *
     * @return string
     */
    public function getReferenceObjectProtected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_referenceObjectProtected == '') {
            $this->_referenceObjectProtected = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_PROTEGE, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }
        return $this->_referenceObjectProtected;
    }

    /**
     * ID de référence de l'objet de dissimulation des membres.
     *
     * @var string
     */
    private $_referenceObjectObfuscated = '';

    /**
     * Calcule et retourne la référence de l'objet de dissimulation des membres.
     *
     * @return string
     */
    public function getReferenceObjectObfuscated()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_referenceObjectObfuscated == '') {
            $this->_referenceObjectObfuscated = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_DISSIMULE, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }
        return $this->_referenceObjectObfuscated;
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#og">OG / Groupe</a>
            <ul>
                <li><a href="#ogo">OGO / Objet</a></li>
                <li><a href="#ogn">OGN / Nommage</a></li>
                <li><a href="#ogp">OGP / Protection</a></li>
                <li><a href="#ogd">OGD / Dissimulation</a></li>
                <li><a href="#ogf">OGF / Fermeture</a></li>
                <li><a href="#ogpm">OGPM / Protection des membres</a></li>
                <li><a href="#ogdm">OGDM / Dissimulation des membres</a></li>
                <li><a href="#ogl">OGL / Liens</a></li>
                <li><a href="#ogc">OGC / Création</a></li>
                <li><a href="#ogs">OGS / Stockage</a></li>
                <li><a href="#ogt">OGT / Transfert</a></li>
                <li><a href="#ogr">OGR / Réservation</a></li>
                <li><a href="#ogio">OGIO / Implémentation des Options</a></li>
                <li><a href="#ogia">OGIA / Implémentation des Actions</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h2 id="og">OG / Groupe</h2>
        <p>Le groupe est un objet définit comme tel, c’est à dire qu’il doit avoir un type mime <code>nebule/objet/groupe</code>.
        </p>
        <p>Fondamentalement, le groupe est un ensemble de plusieurs objets. C’est à dire, c’est le regroupement d’au
            moins deux objets. Le lien peut donc à ce titre être vu comme la matérialisation d’un groupe. Mais la
            définition du groupe doit être plus restrictive afin que celui-ci soit utilisable. Pour cela, dans <em>nebule</em>,
            le groupe n’est reconnu comme tel uniquement si il est marqué de son type mime. Il est cependant possible
            d’instancier explicitement un objet comme groupe et de l’utiliser comme tel en cas de besoin.</p>
        <p>Le groupe va permettre de regrouper, et donc d’associer et de retrouver, des objets. L’objet du groupe va
            avoir des liens vers d’autres objets afin de les définir comme membres du groupe.</p>
        <p>Un groupe peut avoir des liens de membres vers des objets définis aussi comme groupes. Ces objets peuvent
            être vus comme des sous-groupes. La bibliothèque <em>nebule</em> ne prend en compte qu’un seul niveau de
            groupe, c’est à dire que les sous-groupes sont gérés simplement comme des objets.</p>

        <h3 id="ogo">OGO / Objet</h3>
        <p>L’objet du groupe peut être de deux natures.</p>
        <p>Soit c’est un objet existant qui est en plus définit comme un groupe. L’objet peut avoir un contenu et a
            sûrement d’autres types mime propres. Dans ce cas l’identifiant de groupe est l’identifiant de l’objet
            utilisé.</p>
        <p>Soit c’est un objet dit virtuel qui n’a pas et n’aura jamais de contenu. Cela n’empêche pas qu’il puisse
            avoir d’autres types mime. Dans ce cas l’identifiant de groupe a une forme commune aux objets virtuels.</p>
        <p>La création d’un objet virtuel comme groupe se fait en créant pour identifiant la concaténation d’un hash
            (<em>sha256</em>) d’une valeur aléatoire de 128bits et de la chaîne <code>006e6562756c652f6f626a65742f67726f757065</code>.
            Soit un identifiant complet de la taille de 104 caractères.</p>

        <h3 id="ogn">OGN / Nommage</h3>
        <p>Le nommage à l’affichage du nom des groupes repose sur une seule propriété :</p>
        <ol>
            <li>nom</li>
        </ol>
        <p>Cette propriété est matérialisée par un lien de type <code>l</code> avec comme objets méta :</p>
        <ol>
            <li><code>nebule/objet/nom</code></li>
        </ol>
        <p>Par convention, voici le nommage des groupes :</p>
        <ul>
            <li><code>nom</code></li>
        </ul>

        <h3 id="ogp">OGP / Protection</h3>
        <p>En tant que tel le groupe ne nécessite pas de protection puisque soit l’objet du groupe n’a pas de contenu
            soit on n’utilise pas son contenu directement.</p>
        <p>La gestion de la protection est désactivée dans une instance de groupe.</p>

        <h3 id="ogd">OGD / Dissimulation</h3>
        <p>Le groupe peut en tant que tel être dissimulé, c’est à dire que l’on dissimule l’existence du groupe, donc sa
            création.</p>
        <p>La dissimulation devrait se faire lors de la création du groupe.</p>
        <p>L’annulation de la dissimulation d’un groupe revient à révéler le lien de création du groupe.</p>
        <p>La dissimulation peut se (re)faire après la création du groupe mais son efficacité est incertaine si les
            liens de création ont déjà été diffusés. En cas de dissimulation à posteriori, il faut générer un lien de
            suppression du groupe puis générer un nouveau lien dissimulé de création du groupe à une date postérieure au
            lien de suppression.</p>

        <h3 id="ogf">OGF / Fermeture</h3>
        <p>Le groupe va contenir un certain nombre de membres ajouter par différentes entités. Il est possible de
            limiter le nombre des membres à utiliser dans un groupe en restreignant artificiellement les entités
            contributrices du groupe. Ainsi on marque le groupe comme fermé et on filtre sur les membres uniquement
            ajoutés par des entités définies.</p>
        <p>Dans nebule, l’objet réservé <code>nebule/objet/groupe/ferme</code> est dédié à la gestion des groupes
            fermés. Un groupe est considéré fermé quand on a l’objet réservé en champs méta, l’entité en cours en champs
            cible et l’ID du groupe en champs source. Si au lieu d’utiliser l’entité en cours pour le champs cible on
            utilise une autre entité, cela revient à prendre aussi en compte ses liens dans le groupe fermé. Dans ce cas
            c’est une entité contributrice.</p>
        <p>C’est uniquement un affichage du groupe que l’on a et non la suppression de membres du groupe.</p>
        <p>Lorsque l’on a marqué un groupe comme fermé, on doit explicitement ajouter des entités que l’on veut voir
            contribuer.</p>
        <p>Il est possible indéfiniment de fermer et ouvrir un groupe.</p>
        <p>Il est possible de fermer un groupe qui ne nous appartient pas afin par exemple de le rendre plus
            lisible.</p>
        <p>Lorsque l’on a marqué un groupe comme fermé, on peut voir la liste des entités explicitement que l’on veut
            voir contribuer. On peut aussi voir les entités que les autres entités veulent voir contribuer et décider ou
            non de les ajouter.</p>
        <p>Lorsqu’un groupe est marqué comme fermé, l’interface de visualisation du groupe peut permettre de le
            visualiser temporairement comme un groupe ouvert.</p>
        <p>Le traitement des liens de fermeture d’un groupe doit être fait exclusivement avec le traitement social <em>self</em>.
        </p>

        <h4 id="ogpm">OGPM / Protection des membres</h4>
        <p>Le groupe va contenir un certain nombre de membres ajouter par différentes entités. Il est possible de
            limiter la visibilité du contenu des membres utilisés dans un groupe en restreignant artificiellement les
            entités destinataires qui pourront les consulter.</p>
        <p>Dans nebule, l’objet réservé <code>nebule/objet/groupe/protege</code> est dédié à la gestion des groupes
            protégés. Un groupe est considéré protégé quand on a l’objet réservé en champs méta, l’entité en cours en
            champs cible et l’ID du groupe en champs source. Si au lieu d’utiliser l’entité en cours pour le champs
            cible on utilise une autre entité, cela revient à partager aussi les objets protégés créés pour ce groupe.
            Cela ne repartage pas la protection des objets déjà protégés.</p>
        <p>Dans un groupe marqué protégé, tous les nouveaux membres ajoutés au groupe ont leur contenu protégé. Ce n’est
            valable que pour l’entité en cours et éventuellement celles qui lui font confiance.</p>
        <p>Lorsque l’on a marqué un groupe comme protégé, on doit explicitement ajouter des entités avec qui on veut
            partager les contenus.</p>
        <p>Il est possible indéfiniment de protéger et déprotéger un groupe.</p>
        <p>Il est possible de protéger un groupe qui ne nous appartient afin de masquer le contenu des membres que l’on
            y ajoute.</p>
        <p>Lorsque l’on a marqué un groupe comme protégé, on peut voir la liste des entités explicitement a qui on veut
            partager les contenus. On peut aussi voir les entités a qui les autres entités veulent partager les contenus
            et décider ou non de les ajouter.</p>
        <p>Le traitement des liens de protection d’un groupe doit être fait exclusivement avec le traitement social <em>self</em>.
        </p>

        <h4 id="ogdm">OGDM / Dissimulation des membres</h4>
        <p>Le groupe va contenir un certain nombre de membres ajouter par différentes entités. Il est possible de
            limiter la visibilité de l’appartenance des membres utilisés dans un groupe en restreignant artificiellement
            les entités destinataires qui pourront les voir.</p>
        <p>Dans nebule, l’objet réservé <code>nebule/objet/groupe/dissimule</code> est dédié à la gestion des groupes
            dissimulés. Un groupe est considéré dissimulé quand on a l’objet réservé en champs méta, l’entité en cours
            en champs cible et l’ID du groupe en champs source. Si au lieu d’utiliser l’entité en cours pour le champs
            cible on utilise une autre entité, cela revient à partager aussi les objets dissimulés créés pour ce groupe.
            Cela ne repartage pas la dissimulation des objets déjà dissimulés.</p>
        <p>Dans un groupe marqué dissimulé, tous les nouveaux membres ajoutés au groupe sont dissimulés. Ce n’est
            valable que pour l’entité en cours et éventuellement celles qui lui font confiance.</p>
        <p>Lorsque l’on a marqué un groupe comme dissimulé, on doit explicitement ajouter des entités avec qui on veut
            partager les membres du groupe.</p>
        <p>Il est possible indéfiniment de dissimuler et dé-dissimuler un groupe.</p>
        <p>Il est possible de dissimuler un groupe qui ne nous appartient afin de masquer le contenu des membres que
            l’on y ajoute.</p>
        <p>Lorsque l’on a marqué un groupe comme dissimulé, on peut voir la liste des entités explicitement a qui on
            veut partager les contenus. On peut aussi voir les entités a qui les autres entités veulent partager les
            contenus et décider ou non de les ajouter.</p>
        <p>Le traitement des liens de dissimulation d’un groupe doit être fait exclusivement avec le traitement social
            <em>self</em>.</p>

        <h3 id="ogl">OGL / Liens</h3>
        <p>Une entité doit être déverrouillée pour la création de liens.</p>
        <ul>
            <li>Le lien de définition du groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : hash(‘nebule/objet/groupe’)</li>
                    <li>méta : hash(‘nebule/objet/type’)</li>
                </ul>
            </li>
            <li>Le lien de suppression d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : hash(‘nebule/objet/groupe’)</li>
                    <li>méta : hash(‘nebule/objet/type’)</li>
                </ul>
            </li>
            <li>Le lien de suivi du groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de l'entité, par défaut l’entité signataire</li>
                    <li>cible : ID du groupe</li>
                    <li>méta : hash(‘nebule/objet/groupe/suivi’)</li>
                </ul>
            </li>
            <li>Le lien de suppression de suivi du groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID de l'entité, par défaut l’entité signataire</li>
                    <li>cible : ID du groupe</li>
                    <li>méta : hash(‘nebule/objet/groupe/suivi’)</li>
                </ul>
            </li>
            <li>Le lien de dissimulation d’un groupe est le lien de définition caché dans une lien de type
                <code>c</code>.
            </li>
            <li>Le lien de rattachement d’un membre du groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’objet</li>
                    <li>méta : ID du groupe</li>
                </ul>
            </li>
            <li>Le lien de suppression de rattachement d’un membre du groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’objet</li>
                    <li>méta : ID du groupe</li>
                </ul>
            </li>
            <li>Le lien de fermeture d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/groupe/ferme’)</li>
                </ul>
            </li>
            <li>Le lien de suppression de fermeture d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/groupe/ferme’)</li>
                </ul>
            </li>
            <li>Le lien de protection des membres d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/groupe/protege’)</li>
                </ul>
            </li>
            <li>Le lien de suppression de protection des membres d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/groupe/protege’)</li>
                </ul>
            </li>
            <li>Le lien de dissimulation des membres d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/groupe/dissimule’)</li>
                </ul>
            </li>
            <li>Le lien de suppression de dissimulation des membres d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/groupe/dissimule’)</li>
                </ul>
            </li>
        </ul>

        <h3 id="ogc">OGC / Création</h3>
        <p>Liste des liens à générer lors de la création d'un groupe :</p>
        <ul>
            <li>Le lien de définition du groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : hash(‘nebule/objet/groupe’)</li>
                    <li>méta : hash(‘nebule/objet/type’)</li>
                </ul>
            </li>
            <li>Le lien de nommage du groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : hash(nom du groupe)</li>
                    <li>méta : hash(‘nebule/objet/nom’)</li>
                </ul>
            </li>
            <li>Le lien de suivi du groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de l'entité, par défaut l’entité signataire</li>
                    <li>cible : ID du groupe</li>
                    <li>méta : hash(‘nebule/objet/groupe/suivi’)</li>
                </ul>
            </li>
        </ul>
        <p>On peut aussi au besoin ajouter ces liens :</p>
        <ul>
            <li>Le lien de fermeture d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/groupe/ferme’)</li>
                </ul>
            </li>
            <li>Le lien de protection des membres d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/groupe/protege’)</li>
                </ul>
            </li>
            <li>Le lien de dissimulation des membres d’un groupe :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID du groupe</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/groupe/dissimule’)</li>
                </ul>
            </li>
        </ul>

        <h3 id="ogs">OGS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="ogt">OGT / Transfert</h3>
        <p>A faire...</p>

        <h3 id="ogr">OGR / Réservation</h3>
        <p>Les objets réservés spécifiquement pour les groupes :</p>
        <ul>
            <li>nebule/objet/groupe</li>
            <li>nebule/objet/groupe/ferme</li>
            <li>nebule/objet/groupe/protege</li>
            <li>nebule/objet/groupe/dissimule</li>
        </ul>

        <h4 id="ogio">OGIO / Implémentation des Options</h4>
        <p>Les options spécifiques aux groupes :</p>
        <ul>
            <li><code>permitWriteGroup</code> : permet toute écriture de groupes.</li>
        </ul>
        <p>Les options qui ont une influence sur les groupes :</p>
        <ul>
            <li><code>permitWrite</code> : permet toute écriture d’objets et de liens ;</li>
            <li><code>permitWriteObject</code> : permet toute écriture d’objets ;</li>
            <li><code>permitCreateObject</code> : permet la création locale d’objets ;</li>
            <li><code>permitWriteLink</code> : permet toute écriture de liens ;</li>
            <li><code>permitCreateLink</code> : permet la création locale de liens.</li>
        </ul>
        <p>Il est nécessaire à la création d’un groupe de pouvoir écrire des objets comme le nom du groupe, même si
            l’objet du groupe ne sera pas créé.</p>

        <h4 id="ogia">OGIA / Implémentation des Actions</h4>
        <p>Dans les actions, on retrouve les chaînes :</p>
        <ul>
            <li><code>creagrp</code> : Crée un groupe.</li>
            <li><code>creagrpnam</code> : Nomme le groupe à créer.</li>
            <li><code>creagrpcld</code> : Marque fermé le groupe à créer.</li>
            <li><code>creagrpobf</code> : Dissimule les liens du groupe à créer.</li>
            <li><code>actdelgrp</code> : Supprime un groupe.</li>
            <li><code>actaddtogrp</code> : Ajoute l’objet courant membre à groupe.</li>
            <li><code>actremtogrp</code> : Retire l’objet courant membre d’un groupe.</li>
            <li><code>actadditogrp</code> : Ajoute un objet membre au groupe courant.</li>
            <li><code>actremitogrp</code> : Retire un objet membre du groupe courant.</li>
        </ul>

        <?php
    }
}


/**
 * ------------------------------------------------------------------------------------------
 * La classe Entity.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'une entité ou 'new' ;
 *
 * L'ID d'une entité est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture de l'entité ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class Entity extends Node
{
    const ENTITY_MAX_SIZE = 16000;
    const ENTITY_PASSWORD_SALT_SIZE = 128;
    const ENTITY_TYPE = 'application/x-pem-file';
    const ENTITY_PUBLIC_HEADER = '-----BEGIN PUBLIC KEY-----';
    const ENTITY_PRIVATE_HEADER = '-----BEGIN ENCRYPTED PRIVATE KEY-----';

    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_id',
        '_fullname',
        '_cacheProperty',
        '_cacheProperties',
        '_cacheMarkProtected',
        '_idProtected',
        '_idUnprotected',
        '_idProtectedKey',
        '_idUnprotectedKey',
        '_markProtectedChecked',
        '_code',
        '_haveCode',
        '_usedUpdate',
        '_publicKey',
        '_privateKeyID',
        '_privateKey',
        '_newPrivateKey',
        '_privateKeyPassword',
        '_privateKeyPasswordSalt',
        '_issetPrivateKeyPassword',
        '_faceCache',
    );

    private $_publicKey = '',
        $_privateKeyID = '0', $_privateKey = '', $_newPrivateKey = false,
        $_privateKeyPassword = '', $_privateKeyPasswordSalt = '', $_issetPrivateKeyPassword = false,
        $_faceCache = array();

    /**
     * Constructeur.
     *
     * Toujours transmettre l'instance de la librairie nebule.
     * Si l'entité existe, juste préciser l'ID de celle-ci.
     * Si c'est une nouvelle entité à créer, mettre l'ID à 'new'.
     *
     * @param nebule $nebuleInstance
     * @param string $id
     */
    public function __construct(nebule $nebuleInstance, $id)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance entity ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id)
            && $id != '' && ctype_xdigit($id)
        ) {
            $this->_loadEntity($id);        // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
        } elseif (is_string($id) && $id == 'new') {
            $this->_createNewEntity();        // Si c'est une nouvelle entité à créer, renvoie à la création.
        } else {
            $this->_id = '0';                // Sinon, l'entité est invalide, retourne 0.
        }
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_id;
    }

    /**
     * Retourne les variables à sauvegarder dans la session php lors d'une mise en sommeil de l'instance.
     *
     * @return array:string
     */
    public function __sleep()
    {
        return self::SESSION_SAVED_VARS;
    }

    /**
     * Foncion de réveil de l'instance et de ré-initialisation de certaines variables non sauvegardées.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_cacheMarkDanger = false;
        $this->_cacheMarkWarning = false;
        $this->_cacheUpdate = '';
    }

    // Chargement d'une entité existante.
    private function _loadEntity($id)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que c'est bien un objet.
        if (!is_string($id)) {
            $id = '0';
        } elseif ($id == '') {
            $id = '0';
        } elseif (!ctype_xdigit($id)) {
            $id = '0';
        } elseif (!$this->_io->checkObjectPresent($id)) {
            $id = '0';
        } elseif (!$this->_io->checkLinkPresent($id)) {
            $id = '0';
        }
        $this->_id = $id;
        $this->_metrology->addLog('Load entity ' . $id, Metrology::LOG_LEVEL_NORMAL); // Log
        if ($id == '0') {
            return false;
        }
        // Trouve la clé publique.
        $this->_findPublicKey();
    }

    /**
     * Création d'une nouvelle entité.
     * La création est légèrement différente de la création d'un objet parce que les liens de l'entité ne peuvent pas encore être vérifiés.
     * Une entité par nature ne peut pas être dissimulée.
     *
     * @return boolean
     */
    private function _createNewEntity()
    {
        $this->_metrology->addLog(__METHOD__, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que l'on puisse créer une entité.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteEntity')
            && ($this->_nebuleInstance->getCurrentEntityUnlocked()
                || $this->_nebuleInstance->getOption('permitPublicCreateEntity')
            )
        ) {
            $this->_metrology->addLog('Create entity ' . $this->_crypto->asymetricAlgorithmName(), Metrology::LOG_LEVEL_NORMAL); // Log

            // Génère un bi-clé cryptographique.
            $newPkey = $this->_crypto->newPkey();
            if ($newPkey !== false) {
                // Extraction des infos.
                $this->_publicKey = $this->_crypto->getPkeyPublic($newPkey);
                $this->_id = $this->_crypto->hash($this->_publicKey);
                $this->_metrology->addLog('Generated entity ' . $this->_id, Metrology::LOG_LEVEL_NORMAL); // Log
                $this->_privateKeyPassword = $this->_crypto->getStrongRandom(32);
                $this->_privateKeyPasswordSalt = '';
                $this->_privateKey = $this->_crypto->getPkeyPrivate($newPkey, $this->_privateKeyPassword);
                $this->_privateKeyID = $this->_crypto->hash($this->_privateKey);
                $this->_issetPrivateKeyPassword = true;
                $this->_newPrivateKey = true;

                // Ecriture de la clé publique.
                $this->write();
                // La clé privée n'est pas écrite. Son mot de passe doit être changé avant son écriture.

                // Définition de la date.
                $date = date(DATE_ATOM);

                // Création lien 1.
                $action = 'l';
                $source = $this->_id;
                $target = $this->_crypto->hash($this->_crypto->hashAlgorithmName());
                $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_HASH);
                $link = '_' . $this->_id . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                $this->_createNewEntityWriteLink($link, $source, $target, $meta);

                // Création lien 2.
                $action = 'l';
                $source = $this->_id;
                $target = $this->_crypto->hash(self::ENTITY_TYPE);
                $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
                $link = '_' . $this->_id . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                $this->_createNewEntityWriteLink($link, $source, $target, $meta);

                unset($date, $action, $source, $target, $meta, $link);

                // A faire : effacement sécurisé...
                unset($newPkey);
            } else {
                $this->_metrology->addLog('Create entity error on generation', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create entity error no autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }

    // Ecrit le lien pour les objets concernés.
    // Utilisé pour la création d'une nouvelle entité, càd dont la clé publique n'est pas encore reconnue.
    private function _createNewEntityWriteLink($link, $source, $target, $meta)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Signe le lien.
        $signe = $this->signLink($link);
        if ($signe === false) {
            return false;
        }
        $signedLink = $signe . '.' . $this->_crypto->hashAlgorithmName() . $link;

        // Ecrit le lien pour l'objet de l'entité signataire.
        if ($this->_nebuleInstance->getOption('NEBULE_DEFAULT_PERMIT_ADD_LINK_TO_SIGNER')) {
            $this->_io->linkWrite($this->_id, $signedLink);
        }

        // Ecrit le lien pour l'objet source.
        $this->_io->linkWrite($source, $signedLink);

        // Ecrit le lien pour l'objet cible.
        $this->_io->linkWrite($target, $signedLink);

        // Ecrit le lien pour l'objet méta.
        $this->_io->linkWrite($meta, $signedLink);

        unset($signe, $signedLink);
        return true;
    }


    /**
     * Vérifier que c'est en hexa, et que c'est une entité.
     * @return boolean
     */
    private function _verifyEntity()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $t = false;
        // Extrait le contenu de l'objet source.
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        // Vérifie si le contenu contient un entête de clé privée
        if ((strstr($objHead, self::ENTITY_PRIVATE_HEADER)) !== false) {
            $t = true;
        }
        // Vérifie si le contenu contient un entête de clé publique
        if ((strstr($objHead, self::ENTITY_PUBLIC_HEADER)) !== false) {
            $t = true;
        }
        unset($objHead);

        // Faire une vérif plus complète...

        $this->_typeVerified = $t;
        return $t;
    }

    private $_typeVerified = false;

    public function getKeyType()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return '';
        }
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_ENTITE_TYPE, 'all');
    }

    public function getTypeVerify()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->_verifyEntity();
        return $this->_typeVerified;
    }

    public function getIsPublicKey()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $t = false;
        // Extrait le contenu de l'objet source.
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        // Vérifie si le contenu contient un entête de clé public
        if ((strstr($objHead, self::ENTITY_PUBLIC_HEADER)) !== false) {
            $t = true;
        }
        unset($objHead);
        return $t;
    }

    public function getIsPrivateKey()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $t = false;
        // Extrait le contenu de l'objet source.
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        // Vérifie si le contenu contient un entête de clé public
        if ((strstr($objHead, self::ENTITY_PRIVATE_HEADER)) !== false) {
            $t = true;
        }
        unset($objHead);
        return $t;
    }


    // Retrouve et retourne la clé publique.
    public function getPublicKeyID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_id;
    }

    public function getPublicKey()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return '';
        }
        if ($this->_publicKey != '') {
            return $this->_publicKey;
        }
        return '';
    }

    // Retrouve la clé publique.
    private function _findPublicKey()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

//		$key = '';
        if ($this->_io->checkObjectPresent($this->_id)
            && $this->_io->checkLinkPresent($this->_id)
        ) {
//			$key = $this->_io->objectRead($this->_id, self::ENTITY_MAX_SIZE);
        } else {
            return;
        }
//		$hashKey = '';
//		$hashAlgo = $this->getHashAlgo();
//		$hashKey = hash($hashAlgo, $key);
        $this->_publicKey = $this->_io->objectRead($this->_id, self::ENTITY_MAX_SIZE);
    }


    // Retourne l'identifiant de la clé privée.
    public function getPrivateKeyID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie la présence d'un ID de clé privée. La recherche au besoin.
        if (!isset($this->_privateKeyID)
            || !$this->_privateKeyID != '0'
        ) {
            $this->_findPrivateKeyID();
        }
        // Retourne l'ID.
        return $this->_privateKeyID;
    }

    // Retrouve l'identifiant de la clé privée.
    private function _findPrivateKeyID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie la présence d'un ID de clé privée.
        if (isset($this->_privateKeyID)
            && $this->_privateKeyID != '0'
        ) {
            return true;
        }
        // Extrait les liens f vers la clé publique.
        $list = $this->readLinksFilterFull($this->_id, '', 'f', '', $this->_id, '0');
        if (sizeof($list) == 0) {
            return true;
        }
        // Boucle de recherche d'une clé privée.
        foreach ($list as $link) {
            $hashSource = $link->getHashSource();
            // Vérifie le lien et la présence de l'objet source.
            if ($link->getHashSigner() == $this->_id
                && $link->getAction() == 'f'
                && $link->getHashTarget() == $this->_id
                && $link->getHashMeta() == '0'
                && $this->_io->checkObjectPresent($hashSource)
            ) {
                // Extrait le contenu de l'objet source. @todo remplacer par Object::getContent ...
                $line = $this->_io->objectRead($hashSource, self::ENTITY_MAX_SIZE);
                // Vérifie si le contenu contient un entête de clé privée
                if (strstr($line, self::ENTITY_PRIVATE_HEADER) !== false) {
                    // Mémorise l'ID de la clé privée.
                    $this->_privateKeyID = $hashSource;
                }
            }
        }
        // Vérifie qu'une clé privée a été trouvée.
        if (isset($this->_privateKeyID)
            && $this->_privateKeyID != '0'
        ) {
            return true;
        }
        return false;
    }

    // Retrouve la clé privée.
    private function _findPrivateKey()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie la présence d'une clé privée.
        if (isset($this->_privateKey)
            && $this->_privateKey != ''
        ) {
            return true;
        }
        // Vérifie la présence d'un ID de clé privée. La recherche au besoin.
        if (!isset($this->_privateKeyID)
            || !$this->_privateKeyID != '0'
        ) {
            $this->_findPrivateKeyID();
        }
        // Extrait le contenu de l'objet.
        $this->_privateKey = $this->_io->objectRead($this->_privateKeyID, self::ENTITY_MAX_SIZE);
        return true;
        // A faire... vérifier que c'est bien une clé privée _pour_ cette clé publique.
    }

    // Définit le mot de passe de la clé privée.
    public function setPrivateKeyPassword($passwd)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->_findPrivateKey();
        // Vérifie le mot de passe sur la clé privée.
        $check = $this->_crypto->getPrivateKey($this->_privateKey, $passwd);
        if ($check === false) {
            return false;
        }
        $this->_privateKeyPasswordSalt = $this->_crypto->getStrongRandom(self::ENTITY_PASSWORD_SALT_SIZE);
        // A faire... le chiffrement du mot de passe avec le sel et l'ID de session php...
        $this->_privateKeyPassword = $passwd;
        $this->_issetPrivateKeyPassword = true;
        $this->_nebuleInstance->addListEntitiesUnlocked($this->_id);
        return true;
    }

    /**
     * Supprime le mot de passe de l'entité.
     * Cela verrouille l'entité.
     *
     * @return boolean
     */
    public function unsetPrivateKeyPassword()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return false;
        }
        $this->_privateKeyPassword = $this->_privateKeyPasswordSalt;
        $this->_privateKeyPassword = '';
        $this->_privateKeyPasswordSalt = '';
        $this->_issetPrivateKeyPassword = false;
        $this->_nebuleInstance->removeListEntitiesUnlocked($this->_id);
        return true;
    }

    public function checkPrivateKeyPassword()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_issetPrivateKeyPassword;
    }

    public function changePrivateKeyPassword($newPasswd)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return false;
        }
        // Vérifie que le mot de passe actuel est présent.
        if (!$this->_issetPrivateKeyPassword) {
            return false;
        }

        $this->_metrology->addLog('Change entity password - old ' . $this->_privateKeyID, Metrology::LOG_LEVEL_NORMAL); // Log
        $oldPrivateKeyID = $this->_privateKeyID;

        $privateKey = $this->_crypto->getPrivateKey($this->_privateKey, $this->_privateKeyPassword);
        if ($privateKey === false) {
            return false;
        }

        $newKey = $this->_crypto->getPkeyPrivate($privateKey, $newPasswd);
        if ($newKey === false) {
            return false;
        }

        $this->_privateKeyPasswordSalt = $this->_crypto->getStrongRandom(self::ENTITY_PASSWORD_SALT_SIZE);
        // A faire... le chiffrement du mot de passe avec le sel et l'ID de session php...
        $this->_privateKeyPassword = $newPasswd;
        $this->_privateKey = $newKey;
        $this->_privateKeyID = $this->_crypto->hash($this->_privateKey);
        $this->_issetPrivateKeyPassword = true;
        $this->_metrology->addLog('Change entity password - new ' . $this->_privateKeyID, Metrology::LOG_LEVEL_NORMAL); // Log

        unset($newKey, $privateKey);

        // Ecrit l'objet de la nouvelle clé privée.
        $this->_io->objectWrite($this->_privateKey);

        // Définition de la date.
        $date = date(DATE_ATOM);

        // Si ce n'est pas une création d'entité, fait les liens de mises à jours de clés privées.
        if (!$this->_newPrivateKey) {
            // Création lien 1.
            $source = $oldPrivateKeyID;
            $target = $this->_id;
            $meta = '0';
            $link = '_' . $this->_id . '_' . $date . '_x_' . $source . '_' . $target . '_' . $meta;
            $this->_createNewEntityWriteLink($link, $source, $target, $meta);

            // Création lien 2.
            $source = $oldPrivateKeyID;
            $target = $this->_privateKeyID;
            $meta = '0';
            $link = '_' . $this->_id . '_' . $date . '_u_' . $source . '_' . $target . '_' . $meta;
            $this->_createNewEntityWriteLink($link, $source, $target, $meta);
        }

        // Création lien 3.
        $source = $this->_privateKeyID;
        $target = $this->_crypto->hash($this->_crypto->hashAlgorithmName());
        $meta = $this->_crypto->hash('nebule/objet/hash');
        $link = '_' . $this->_id . '_' . $date . '_l_' . $source . '_' . $target . '_' . $meta;
        $this->_createNewEntityWriteLink($link, $source, $target, $meta);

        // Création lien 4.
        $source = $this->_privateKeyID;
        $target = $this->_crypto->hash(self::ENTITY_TYPE);
        $meta = $this->_crypto->hash('nebule/objet/type');
        $link = '_' . $this->_id . '_' . $date . '_l_' . $source . '_' . $target . '_' . $meta;
        $this->_createNewEntityWriteLink($link, $source, $target, $meta);

        // Création lien 5.
        $source = $this->_privateKeyID;
        $target = $this->_id;
        $meta = '0';
        $link = '_' . $this->_id . '_' . $date . '_f_' . $source . '_' . $target . '_' . $meta;
        $this->_createNewEntityWriteLink($link, $source, $target, $meta);

        unset($date, $source, $target, $meta, $link);

        $this->_newPrivateKey = false;
        return true;
    }


    /**
     * Signature de liens.
     *
     * @param string $link
     * @param string $algo
     * @return string
     */
    public function signLink($link, $algo = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_privateKey == '') {
            $this->_metrology->addLog('ERROR entity no private key', Metrology::LOG_LEVEL_NORMAL); // Log
            return false;
        }
        if ($this->_privateKeyPassword == '') {
            $this->_metrology->addLog('ERROR entity no password for private key', Metrology::LOG_LEVEL_NORMAL); // Log
            return false;
        }
        if ($algo == '') {
            $algo = $this->_crypto->hashAlgorithmName();
        }

        $hash = $this->_crypto->hash($link, $algo);
        return $this->_crypto->sign($hash, $this->_privateKey, $this->_privateKeyPassword);
    }

    /**
     * Signature et écriture de liens.
     *
     * @param string $link
     * @return string
     */
    public function signWriteLink($link)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $signe = $this->signLink($link);
        if ($signe === false) {
            return false;
        }
        $signedLink = $signe . $link;
        // A faire...
    }

    /**
     * Déchiffrement de données pour l'entité.
     * Déchiffrement asymétrique uniquement, càd avec la clé privée.
     *
     * @param string $data
     * @return string
     */
    public function decrypt($code)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_crypto->decryptTo($code, $this->_privateKey, $this->_privateKeyPassword);
    }


    /**
     * Lecture du nom complet.
     * La construction du nom complet d'une entité est légèrement différente d'un objet.
     *
     * @param string $socialClass
     * @return string
     */
    public function getFullName($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return '';
        }
        if (isset($this->_fullname)
            && trim($this->_fullname) != ''
        ) {
            return $this->_fullname;
        }

        // Recherche des éléments. Pas de suffix pris en compte.
        $name = $this->getName($socialClass);
        $prefix = $this->getPrefixName($socialClass);
        $suffix = $this->getSuffixName($socialClass);
        $firstname = $this->getFirstname($socialClass);
        $surname = $this->getSurname($socialClass);

        // Reconstitution du nom complet : préfixe prénom "surnom" nom suffixe
        $fullname = $name;
        if ($surname != '') {
            $fullname = '&ldquo;' . $surname . '&rdquo; ' . $fullname;
        }
        if ($firstname != '') {
            $fullname = $firstname . ' ' . $fullname;
        }
        if ($prefix != '') {
            $fullname = $prefix . ' ' . $fullname;
        }
        if ($suffix != '') {
            $fullname = $fullname . ' ' . $suffix;
        }
        $this->_fullname = $fullname;

        // Resultat.
        return $fullname;
    }

    // Retourne les localisations de l'entité.
    public function getLocalisations($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return '';
        }
        return $this->getProperties(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    public function getLocalisationsID($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return '';
        }
        return $this->getPropertiesID(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    public function getLocalisation($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return '';
        }
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    public function getLocalisationID($socialClass = '')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return '';
        }
        return $this->getPropertyID(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    /**
     * Recherche la miniature d'un image la plus proche possible de la dimension demandée. Recherche faite sur un seul niveau d'arborescence.
     *
     * @param number $size
     * @return string
     */
    public function getFaceID($size = 400)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_id == '0') {
            return '';
        }
        if ($size == '0') {
            return '';
        }

        $ximg = (int)($this->getProperty('EXIF/ImageWidth', 'all'));
        if ($ximg == 0) {
            $ximg = (int)($this->getProperty('COMPUTED.Width', 'all'));
        }

        $yimg = (int)($this->getProperty('EXIF/ImageHeight', 'all'));
        if ($yimg == 0) {
            $yimg = (int)($this->getProperty('COMPUTED.Height', 'all'));
        }

        if ($ximg == 0 || $yimg == 0) // Si pas de dimensions trouvées, continue avec des valeurs par défaut.
        {
            $ximg = 10000;
            $yimg = 10000;
        }

        // Si l'objet est plus petit que la 'miniature' demandée, retourne 0.
        $xyimg = sqrt($ximg * $yimg);
        if ($size >= $xyimg) {
            return '0';
        }

        $list = array();
        $links = array();
        //_l_fnd($this->_id, $links, 'f', $this->_id, '', '0');				// @todo Vérifier le bon fonctionnement.
        $links = $this->readLinksFilterFull('', '', 'f', $this->_id, '', '0');
        foreach ($links as $link) {
            $instance6 = $this->_nebuleInstance->newObject($link->getHashTarget());
            $type = $instance6->getType('all');
            if (($type == 'image/png'
                    || $type == 'image/jpeg')
                && $instance6->checkPresent()
            ) {
                $xsize = (int)($instance6->getProperty('EXIF/ImageWidth', 'all'));
                if ($xsize == 0) {
                    $xsize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                }
                $ysize = (int)($instance6->getProperty('EXIF/ImageHeight', 'all'));
                if ($ysize == 0) {
                    $ysize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                }
                if ($xsize != ''
                    && $ysize != ''
                    && $xsize != '0'
                    && $ysize != '0'
                ) {
                    $list[$instance6->getID()][0] = $instance6->getID();
                    $list[$instance6->getID()][1] = sqrt((int)$xsize * (int)$ysize);
                }
            }
        }

        // Recherche la résolution la plus proche.
        $best = $xyimg;
        $bestimg = '0';
        if (sizeof($list) != 0) {
            foreach ($list as $img) {
                if (abs($size - $img[1]) < $best) {
                    $bestimg = $img[0];
                    $best = abs($size - $img[1]);
                }
            }
        }
        if ($bestimg != $this->_id && $xyimg < $best) {
            return '0';
        }
        if ($bestimg != $this->_id) {
            return $bestimg;
        } else // Si pas trouvé d'objet aux dimmensions intéressantes, recherche si ça ne marche pas mieux avec l'objet parent.
        {
            $uplinks = array();
            //_l_fnd($this->_id, $uplinks, 'f', '', $this->_id, '0');							// @todo Vérifier le bon fonctionnement.
            $uplinks = $this->readLinksFilterFull('', '', 'f', '', $this->_id, '0');
            foreach ($uplinks as $uplink) {
                $instance5 = $this->_nebuleInstance->newObject($uplink->getHashSource());
                $type = $instance5->getType('all');
                if (($type == 'image/png' || $type == 'image/jpeg') && $instance5->checkPresent()) {
                    $list = array();
                    $links = array();
                    //_l_fnd($instance5->getID(), $links, 'f', $instance5->getID(), '', '0');          // @todo Vérifier le bon fonctionnement.
                    $links = $instance5->readLinksFilterFull('', '', 'f', $instance5->getID(), '', '0');
                    foreach ($links as $link) {
                        $instance6 = $this->_nebuleInstance->newObject($link->getHashTarget());
                        $type = $instance6->getType('all');
                        if ($type == 'image/png'
                            || $type == 'image/jpeg'
                        ) {
                            $xsize = (int)($instance6->getProperty('EXIF/ImageWidth', 'all'));
                            if ($xsize == 0) {
                                $xsize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                            }
                            $ysize = (int)($instance6->getProperty('EXIF/ImageHeight', 'all'));
                            if ($ysize == 0) {
                                $ysize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                            }
                            if ($xsize != ''
                                && $ysize != ''
                                && $xsize != '0'
                                && $ysize != '0'
                            ) {
                                $list[$instance6->getID()][0] = $instance6->getID();
                                $list[$instance6->getID()][1] = sqrt((int)$xsize * (int)$ysize);
                            }
                        }
                    }
                }
            }
        }
        if (sizeof($list) != 0) {
            foreach ($list as $img) {
                if (abs($size - $img[1]) < $best) {
                    $bestimg = $img[0];
                    $best = abs($size - $img[1]);
                }
            }
        }
        if ($xyimg < $best) {
            return '0';
        }
        return $bestimg;
    }


    // Ecrit l'objet si non présent.
    public function write()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if (!$this->_io->checkObjectPresent($this->_id)) {
            $id = $this->_io->objectWrite($this->_publicKey);
        } else {
            $id = $this->_id;
        }

        // Métrologie.
        $v = true;
        if ($id === false) {
            $v = false;
            // Si l'écriture échoue, on crée l'objet d'ID '0'. @todo à revoir si vraiment utile... pareil pour objects->write().
            $id = '0';
        }
        $this->_metrology->addAction('addent', $id, $v);

        return $v;
    }



    // Désactivation des fonctions de protection.

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function getMarkProtected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function getReloadMarkProtected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return string
     */
    public function getProtectedID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return '0';
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return string
     */
    public function getUnprotectedID()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_id;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function setProtected($obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function setUnprotected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function setProtectedTo($entity)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return array
     */
    public function getProtectedTo()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return array();
    }


    /**
     * Retourne si l'entité est une autorité locale.
     * Fait appel à la fonction dédiée de la classe nebule.
     *
     * @return boolean
     */
    public function getIsLocalAuthority()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_nebuleInstance->getIsLocalAuthority($this->_id);
    }


    /**
     * Retourne la liste des liens vers les groupes dont l'entité est à l'écoute.
     *
     * @param string $socialClass
     * @return array:Link
     */
    public function getListIsFollowerOfGroupLinks($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $this->id,
            '',
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI)
        );

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newGroup($link->getHashTarget());
            if (!$instance->getIsGroup('all')) {
                unset($links[$i]);
            }
        }

        return $links;
    }

    /**
     * Retourne la liste des ID vers les groupes dont l'entité est à l'écoute.
     *
     * @param string $socialClass
     * @return array:string
     */
    public function getListIsFollowerOnGroupID($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $list = array();

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $this->id,
            '',
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI)
        );

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newGroup($link->getHashTarget());
            if ($instance->getIsGroup('all')) {
                $list[$link->getHashTarget()] = $link->getHashTarget();
            }
        }

        return $list;
    }

    /**
     * Retourne la liste des liens vers les conversations dont l'entité est à l'écoute.
     * S'appuie sur la fonction dédiée aux groupes.
     *
     * @param string $socialClass
     * @return array:Link
     */
    public function getListIsFollowerOfConversationLinks($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $this->id,
            '',
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE)
        );

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newConversation($link->getHashTarget());
            if (!$instance->getIsConversation('all')) {
                unset($links[$i]);
            }
        }

        return $links;
    }

    /**
     * Retourne la liste des ID vers les conversations dont l'entité est à l'écoute.
     * S'appuie sur la fonction dédiée aux groupes.
     *
     * @param string $socialClass
     * @return array:string
     */
    public function getListIsFollowerOnConversationID($socialClass = 'myself')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $list = array();

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->getListIsFollowerOfConversationLinks($socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newConversation($link->getHashTarget());
            if ($instance->getIsConversation('all')) {
                $list[$link->getHashTarget()] = $link->getHashTarget();
            }
        }

        return $list;
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#oe">OE / Entité</a>
            <ul>
                <li><a href="#oen">OEM / Entités Maîtresses</a></li>
                <li><a href="#oen">OEN / Nommage</a></li>
                <li><a href="#oep">OEP / Protection</a></li>
                <li><a href="#oed">OED / Dissimulation</a></li>
                <li><a href="#oel">OEL / Liens</a></li>
                <li><a href="#oec">OEC / Création</a></li>
                <li><a href="#oes">OES / Stockage</a></li>
                <li><a href="#oet">OET / Transfert</a></li>
                <li><a href="#oer">OER / Réservation</a></li>
                <li><a href="#oeio">OEIO / Implémentation des Options</a></li>
                <li><a href="#oeia">OEIA / Implémentation des Actions</a></li>
                <li><a href="#oeo">OEO / Oubli</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h2 id="oe">OE / Entité</h2>
        <p>A faire...</p>
        <p>L’entité est un objet caractéristique. Elle dispose d’une clé publique, par laquelle elle est identifiée, et
            d’une clé privée.</p>
        <p>L’indication de la fonction de prise d’empreinte (hashage) ainsi que le type de bi-clé sont impératifs. Le
            lien est identique à celui défini pour un objet.</p>
        <p>Le type mime <code>mime-type:application/x-pem-file</code> est suffisant pour indiquer que cet objet est une
            entité. <i>Des valeurs équivalentes pourront être définies ultérieurement</i>.</p>
        <p>Toutes les autres indications sont optionnelles.</p>

        <h3 id="oem">OEM / Entités Maîtresses</h3>
        <p>La bibliothèque utilise actuellement plusieurs entités spéciales, dites autorités maîtresses, avec des rôles
            prédéfinis.</p>
        <ol>
            <li>Maître du tout. L'instance actuelle s'appelle <a href="http://puppetmaster.nebule.org">puppetmaster</a>.
                Voir <a href="#cam">CAM</a>.
            </li>
            <li>Maître de la sécurité. L'instance actuelle s'appelle <a href="http://cerberus.nebule.org">cerberus</a>.
                Voir <a href="#cams">CAMS</a>.
            </li>
            <li>Maître du code. L'instance actuelle s'appelle <a href="http://bachue.nebule.org">bachue</a>. Voir <a
                        href="#camc">CAMC</a>.
            </li>
            <li>Maître de l'annuaire. L'instance actuelle s'appelle <a href="http://asabiyya.nebule.org">assabyia</a>.
                Voir <a href="#cama">CAMA</a>.
            </li>
            <li>Maître du temps. L'instance actuelle s'appelle <a href="http://kronos.nebule.org">kronos</a>. Voir <a
                        href="#camt">CAMT</a>.
            </li>
        </ol>

        <h3 id="oen">OEN / Nommage</h3>
        <p>Le nommage à l’affichage du nom des entités repose sur plusieurs propriétés :</p>
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
        <p>Par convention, voici le nommage des entités :</p>
        <p><code>préfixe prénom "surnom" nom suffixe</code></p>

        <h3 id="oep">OEP / Protection</h3>
        <p>A faire...</p>

        <h3 id="oed">OED / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="oel">OEL / Liens</h3>
        <p>A faire...</p>

        <h3 id="oec">OEC / Création</h3>
        <p>La première étape consiste en la génération d’un bi-clé (public/privé) cryptographique. Ce bi-clé peut être
            de type RSA ou équivalent. Aujourd’hui, seul RSA est reconnu.</p>
        <p>On extrait la clé publique du bi-clé. Le calcul de l’empreinte cryptographique de la clé publique donne
            l’identifiant de l’entité. On écrit dans les objets (o/*) l’objet avec comme contenu la clé publique et
            comme id son empreinte cryptographique.</p>
        <p>On extrait la clé privée du bi-clé. Il est fortement conseillé lors de l’extraction de protéger tout de suite
            la clé privée avec un mot de passe. On écrit dans les objets (o/*) l’objet avec comme contenu la clé privée
            et comme id son empreinte cryptographique (différente de celle de la clé publique).</p>
        <p>A partir de maintenant, le bi-clé n’est plus nécessaire. Il faut le supprimer avec un effacement
            sécurisé.</p>
        <p>Pour que l’objet soit reconnu comme entité il faut créer les liens correspondants.</p>
        <ul>
            <li>Lien 1 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">publique</span></li>
                    <li>Empreinte de l’algorithme de hash utilisé pour le calcul des empreintes</li>
                    <li>Empreinte de ‘nebule/objet/hash’</li>
                </ul>
            </li>
            <li>Lien 2 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">privée</span></li>
                    <li>Empreinte de l’algorithme de hash utilisé pour le calcul des empreintes</li>
                    <li>Empreinte de ‘nebule/objet/hash’</li>
                </ul>
            </li>
            <li>Lien 3 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">publique</span></li>
                    <li>Empreinte de ‘application/x-pem-file’</li>
                    <li>Empreinte de ‘nebule/objet/type’</li>
                </ul>
            </li>
            <li>Lien 4 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">privée</span></li>
                    <li>Empreinte de ‘application/x-pem-file’</li>
                    <li>Empreinte de ‘nebule/objet/type’</li>
                </ul>
            </li>
            <li>Lien 5 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>f</code> ;</li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">privée</span></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">publique</span></li>
                    <li>0.</li>
                </ul>
            </li>
        </ul>
        <p>C’est le minimum vital pour une entité. Ensuite, d’autres propriétés peuvent être ajoutées à l’entité (id clé
            publique) comme sont nom, son type, etc…</p>
        <p>Si le mot de passe de la clé privée est définit par l’utilisateur demandeur de la nouvelle entité, il faut
            supprimer ce mot de passe avec un effacement sécurisé.</p>
        <p>Si le mot de passe de la clé privée a été généré, donc que la nouvelle entité est esclave d’une entité
            maître, le mot de passe doit être stocké dans un objet chiffré pour l’entité maître. Et il faut générer un
            lien reliant l’objet de mot de passe à la clé privée de la nouvelle entité.</p>

        <h3 id="oes">OES / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="oet">OET / Transfert</h3>
        <p>A faire...</p>

        <h3 id="oer">OER / Réservation</h3>
        <p>A faire...</p>

        <h4 id="oeio">OEIO / Implémentation des Options</h4>
        <p>A faire...</p>

        <h4 id="oeia">OEIA / Implémentation des Actions</h4>
        <p>A faire...</p>

        <h3 id="oeo">OEO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}


/**
 * ------------------------------------------------------------------------------------------
 * La classe Localisation.
 * @todo à revoir complètement !
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID de la localisation ou la localisation.
 *
 * La localisation est forcément un texte et est passée automatiquement en minuscule.
 * ------------------------------------------------------------------------------------------
 */
class Localisation extends Node
{
    private $_localisation = '', $_protocol = '', $_communication, $_ioDefaultPrefix = '';

    public function __construct(nebule $nebuleInstance, $id, $localisation = '')
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_ioDefaultPrefix = $this->_io->getDefaultLocalisation();
        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance localisation ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        // Vérifie sommairement la localisation.
        if (is_string($id) && $id != '0' && $id != '' && ctype_xdigit($id)) {
            $this->_id = $id;
            // Extrait la localisation et la convertit en minuscule.
            $this->_localisation = trim(strtolower($this->_io->objectRead($id)));
        } elseif (is_string($id) && $id == '0') {
            // Crée le nouvel objet.
            $this->_createNewObject($localisation);
            // Définit la licalisation, en minuscule.
            $this->_localisation = trim(strtolower($localisation));
        } else {
            // La localisation n'est pas valide.
            $this->_id = '0';
            $this->_localisation = '';
        }

        // Extrait le type de protocole.
        // Si invalide ou non reconnu, la variable du protocole est vide.
        if (substr($this->_localisation, 0, 7) == 'http://' || substr($this->_localisation, 0, 8) == 'https://') {
            // @todo Test la validité de l'adresse.
            $this->_protocol = 'HTTP';
        } elseif (substr($this->_localisation, 0, 5) == 'mail:') {
            // @todo Test la validité de l'adresse.
            $this->_protocol = 'SMTP';
        } elseif (substr($this->_localisation, 0, 5) == 'xmpp:') {
            // @todo Test la validité de l'adresse.
            $this->_protocol = 'XMPP';
        } else {
            $this->_protocol = '';
        }
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_localisation;
    }

    // Synchronise l'objet avec l'ID donné si non présent localement.
    public function syncObjectID($id)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // @todo
    }

    // Synchronise les liens.
    public function syncLinksID($id)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // @todo
    }

    // Synchronise à la fois les liens et l'objet avec l'ID donné.
    public function syncID($id)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->syncLinksID($id);
        $this->syncObjectID($id);
    }


    private function _addPonderate($time)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_nebuleInstance->getOption('permitLocalisationStats')) {
            return false;
        }

        // @todo
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#ol">OL / Localisation</a>
            <ul>
                <li><a href="#oln">OLN / Nommage</a></li>
                <li><a href="#olp">OLP / Protection</a></li>
                <li><a href="#old">OLD / Dissimulation</a></li>
                <li><a href="#oll">OLL / Liens</a></li>
                <li><a href="#olc">OLC / Création</a></li>
                <li><a href="#ols">OLS / Stockage</a></li>
                <li><a href="#olt">OLT / Transfert</a></li>
                <li><a href="#olr">OLR / Réservation</a></li>
                <li><a href="#olio">OLIO / Implémentation des Options</a></li>
                <li><a href="#olia">OLIA / Implémentation des Actions</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h2 id="ol">OL / Localisation</h2>
        <p>A faire...</p>
        <p>Une localisation permet de trouver l’emplacement des objets et liens générés par une entité.</p>
        <p>Un emplacement n’a de sens que pour une entité.</p>
        <p>Une entité peut disposer de plusieurs localisations. Il faut considérer que toute entité qui héberge l’objet
            d’une autre entité devient de fait une localisation valide même si cela n’est pas explicitement définit.</p>

        <h3 id="oln">OLN / Nommage</h3>
        <p>A faire...</p>

        <h3 id="olp">OLP / Protection</h3>
        <p>A faire...</p>

        <h3 id="old">OLD / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="oll">OLL / Liens</h3>
        <p>A faire...</p>

        <h3 id="olc">OLC / Création</h3>
        <p>Liste des liens à générer lors de la création d'une localisation.</p>
        <p>A faire...</p>

        <h3 id="ols">OLS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="olt">OLT / Transfert</h3>
        <p>A faire...</p>

        <h3 id="olr">OLR / Réservation</h3>
        <p>A faire...</p>

        <h4 id="olio">OLIO / Implémentation des Options</h4>
        <p>A faire...</p>

        <h4 id="olia">OLIA / Implémentation des Actions</h4>
        <p>A faire...</p>

        <?php
    }
}


/**
 * ------------------------------------------------------------------------------------------
 * La classe Conversation.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'une conversation ou 'new' ;
 *
 * L'ID d'une conversation est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture de la conversation ou lors de la création, assigne l'ID 0.
 *
 * Tout objet peut devenir une conversation sans avoir été préalablement marqué comme conversation.
 * Le simple faire de faire un lien pour désigner un objet comme membre de la conversation d'un autre objet
 *   suffit à créer la conversation.
 * ------------------------------------------------------------------------------------------
 */
class Conversation extends Group
{
    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_id',
        '_fullname',
        '_cacheProperty',
        '_cacheProperties',
        '_cacheMarkProtected',
        '_idProtected',
        '_idUnprotected',
        '_idProtectedKey',
        '_idUnprotectedKey',
        '_markProtectedChecked',
        '_cacheCurrentEntityUnlocked',
        '_usedUpdate',
        '_isGroup',
        '_isConversation',
        '_isMarkClosed',
        '_isMarkProtected',
        '_isMarkObfuscated',
        '_referenceObject',
        '_referenceObjectClosed',
        '_referenceObjectProtected',
        '_referenceObjectObfuscated',
    );

    /**
     * Constructeur.
     *
     * Toujours transmettre l'instance de la librairie nebule.
     * Si la conversation existe, juste préciser l'ID de celle-ci.
     * Si c'est une nouvelle conversation à créer, mettre l'ID à 'new'.
     *
     * @param nebule $nebuleInstance
     * @param string $id
     * @param boolean $closed si $id == 'new'
     * @param boolean $protected si $id == 'new'
     * @param boolean $obfuscated si $id == 'new'
     */
    public function __construct(nebule $nebuleInstance, $id, $closed = false, $protected = false, $obfuscated = false)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance conversation ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
        ) {
            // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
            $this->_loadConversation($id);
        } elseif (is_string($id)
            && $id == 'new'
        ) {
            // Si c'est une nouvelle conversation à créer, renvoie à la création.
            $this->_createNewConversation($closed, $protected, $obfuscated);
        } else {
            // Sinon, la conversation est invalide, retourne 0.
            $this->_id = '0';
        }

        // Pré-calcul les références.
        $this->getReferenceObject();
        $this->getReferenceObjectClosed();
        $this->getReferenceObjectProtected();
        $this->getReferenceObjectObfuscated();
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_id;
    }

    /**
     * Retourne les variables à sauvegarder dans la session php lors d'une mise en sommeil de l'instance.
     *
     * @return array:string
     */
    public function __sleep()
    {
        return self::SESSION_SAVED_VARS;
    }

    /**
     * Foncion de réveil de l'instance et de ré-initialisation de certaines variables non sauvegardées.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_cacheMarkDanger = false;
        $this->_cacheMarkWarning = false;
        $this->_cacheUpdate = '';
    }

    /**
     *  Chargement d'une conversation existant.
     *
     * @param string $id
     */
    private function _loadConversation($id)
    {
        // Vérifie que c'est bien un objet.
        if (!is_string($id)
            || $id == ''
            || !ctype_xdigit($id)
            || !$this->_io->checkLinkPresent($id)
        ) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrology->addLog('Load conversation ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log
        $this->getIsConversation();
    }

    /**
     * Création d'une nouvelle conversation.
     *
     * @param boolean $closed
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return boolean
     */
    protected function _createNewConversation($closed, $protected, $obfuscated)
    {
        $this->_metrology->addLog('Ask create conversation', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'on puisse créer une conversation.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génère un contenu aléatoire.
            $data = $this->_crypto->getPseudoRandom(32);

            // Si le contenu est valide.
            if ($data != ''
                && $data !== false
            ) {
                // Calcul l'ID référence de la conversation.
                $this->_id = substr($this->_crypto->hash($data), 0, 32)
                    . '0000656e7562656c6f2f6a627465632f6e6f6576737274616f690a6e';
                $this->_metrology->addLog('Create conversation ' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log

                // Mémorise les données.
                $this->_data = $data;
                $this->_haveData = true;
                $data = null;

                $signer = $this->_nebuleInstance->getCurrentEntity();
                $date = date(DATE_ATOM);
                $hashconversation = $this->getReferenceObject();

                // Création lien de hash.
                $date2 = $date;
                if ($obfuscated) {
                    $date2 = '0';
                }
                $action = 'l';
                $source = $this->_id;
                $target = $this->_crypto->hash($this->_crypto->hashAlgorithmName());
                $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_HASH);
                $link = '0_' . $signer . '_' . $date2 . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                $newLink = $this->_nebuleInstance->newLink($link);
                $newLink->signWrite();

                // Création lien de conversation.
                $action = 'l';
                $source = $this->_id;
                $target = $hashconversation;
                $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
                $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                $newLink = $this->_nebuleInstance->newLink($link);
                $newLink->sign();
                if ($obfuscated) {
                    $newLink->obfuscate();
                }
                $newLink->write();

                // Si besoin, marque la conversation comme fermée.
                if ($closed) {
                    $this->_metrology->addLog('Create closed conversation', Metrology::LOG_LEVEL_DEBUG); // Log
                    $action = 'l';
                    $source = $this->_id;
                    $target = $signer;
                    $meta = $this->getReferenceObjectClosed();
                    $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                    $newLink = $this->_nebuleInstance->newLink($link);
                    $newLink->sign();
                    if ($obfuscated) {
                        $newLink->obfuscate();
                    }
                    $newLink->write();
                }

                // Si besoin, marque la conversation comme protégée.
                if ($protected) {
                    $this->_metrology->addLog('Create protected conversation', Metrology::LOG_LEVEL_DEBUG); // Log
                    $action = 'l';
                    $source = $this->_id;
                    $target = $signer;
                    $meta = $this->getReferenceObjectProtected();
                    $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                    $newLink = $this->_nebuleInstance->newLink($link);
                    $newLink->sign();
                    if ($obfuscated) {
                        $newLink->obfuscate();
                    }
                    $newLink->write();
                }

                // Si besoin, marque la conversation comme dissimulée.
                if ($obfuscated) {
                    $this->_metrology->addLog('Create obfuscated conversation', Metrology::LOG_LEVEL_DEBUG); // Log
                    $action = 'l';
                    $source = $this->_id;
                    $target = $signer;
                    $meta = $this->getReferenceObjectObfuscated();
                    $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                    $newLink = $this->_nebuleInstance->newLink($link);
                    $newLink->sign();
                    $newLink->obfuscate();
                    $newLink->write();
                }

                // Création du lien de l'entité originaire de la conversation.
                $action = 'l';
                $source = $signer;
                $target = $this->_id;
                $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE);
                $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
                $newLink = $this->_nebuleInstance->newLink($link);
                $newLink->sign();
                if ($obfuscated) {
                    $newLink->obfuscate();
                }
                $newLink->write();

                $this->_isConversation = true;
            } else {
                $this->_metrology->addLog('Create conversation error on generation', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create conversation error not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }



    // Désactivation des fonctions de protection et autres.

    /**
     * Vérifie la consistance de l'objet.
     *
     * Retourne toujours true pour une conversation.
     * Il n'y a pas de contenu à vérifier pour un objet de référence.
     *
     * @return boolean
     */
    public function checkConsistency()
    {
        return true;
    }

    /**
     * Fonction pour les objets, désactivée pour les conversations.
     *
     * @return boolean
     */
    public function getReloadMarkProtected()
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les conversations.
     *
     * @return string
     */
    public function getProtectedID()
    {
        return '0';
    }

    /**
     * Fonction pour les objets, désactivée pour les conversations.
     *
     * @return string
     */
    public function getUnprotectedID()
    {
        return $this->_id;
    }

    /**
     * Fonction pour les objets, désactivée pour les conversations.
     *
     * @return boolean
     */
    public function setProtected($obfuscated = false)
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les conversations.
     *
     * @return boolean
     */
    public function setUnprotected()
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les conversations.
     *
     * @param string|Entity $entity
     * @return boolean
     */
    public function setProtectedTo($entity)
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les conversations.
     *
     * @return array
     */
    public function getProtectedTo()
    {
        return array();
    }


    /**
     * Retourne si l'entité est à l'écoute du groupe.
     *
     * @param string|Node|Entity $entity
     * @param string $socialClass
     * @param array:string $socialListID
     * @return boolean
     */
    public function getIsFollower($entity, $socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que c'est bien une entité.
        if ($entity == '') {
            return false;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        // Vérifie que c'est bien une entité.
        if ($id == '') {
            return false;
        }

        // Liste tous les liens de définition des entités à l'écoutes du groupe.
        $links = $this->readLinksFilterFull(
            '',
            '',
            'l',
            $id,
            $this->_id,
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE)
        );

        // Fait un tri par pertinance sociale.
        $this->_social->setList($socialListID);
        $this->_social->arraySocialFilter($links, $socialClass);
        $this->_social->unsetList();

        if (sizeof($links) != 0) {
            return true;
        }
        return false;
    }

    /**
     * Ajoute une entité comme à l'écoute du groupe.
     *
     * @param string|Node $entity
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setFollower($entity, $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteConversation')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated('')) {
            $obfuscated = true;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        // Vérifie que c'est bien une entité.
        if ($id == '') {
            return false;
        }

        // Création lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        $source = $id;
        $target = $this->_id;
        $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE);
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();

        // Si besoin, obfuscation du lien.
        if ($obfuscated) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
    }

    /**
     * Retire un entité à l'écoute du groupe.
     * @todo détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @todo retirer la dissimulation déjà faite dans le code.
     *
     * @param string|Node $entity
     * @param boolean $obfuscated
     * @return boolean
     */
    public function unsetFollower($entity = '', $obfuscated = false)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que la création de liens est possible.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
            || !$this->_nebuleInstance->getOption('permitCreateLink')
            || !$this->_nebuleInstance->getOption('permitWriteConversation')
            || !$this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            return false;
        }

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated('')) {
            $obfuscated = true;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        // Vérifie que c'est bien une entité.
        if ($id == '') {
            return false;
        }

        // Création lien de groupe.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'x';
        $source = $id;
        $target = $this->_id;
        $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE);
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        $newLink->sign();

        // Si besoin, obfuscation du lien.
        if ($obfuscated) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
    }


    /**
     * Extrait la liste des liens définissant les entités à l'écoute de la conversation.
     *
     * @param string $socialClass
     * @param array:string $socialListID
     * @return array:Link
     */
    public function getListFollowersLinks($socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_getListFollowersLinks($this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE), $socialClass, $socialListID);
    }

    /**
     * Extrait la liste des ID des entités à l'écoute du groupe.
     *
     * @param string $socialClass
     * @param array:string $socialListID
     * @return array:string
     */
    public function getListFollowersID($socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // Extrait les liens des groupes.
        $links = $this->_getListFollowersLinks($this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE), $socialClass, $socialListID);

        // Extraction des ID cibles.
        $list = array();
        foreach ($links as $link) {
            $list[$link->getHashSource()] = $link->getHashSource();
        }

        return $list;
    }

    /**
     * Retourne le nombre d'entités à l'écoute du groupe.
     *
     * @param string $socialClass
     * @param array:string $socialListID
     * @return float
     */
    public function getCountFollowers($socialClass = '', $socialListID = null)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        return sizeof($this->_getListFollowersLinks($this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE), $socialClass, $socialListID));
    }

    /**
     * Retourne la liste des entités qui ont ajouté l'entité cité comme suiveuse de la conversation.
     *
     * @param string $entity
     * @param string $socialClass
     * @param array:string $socialListID
     * @return array:string
     */
    public function getListFollowerAddedByID($entity, $socialClass = 'all', $socialListID = null)
    {
        // Extrait les liens des groupes.
        $links = $this->_getListFollowersLinks($this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE), $socialClass, $socialListID);

        // Extraction des ID cibles.
        $list = array();
        foreach ($links as $link) {
            if ($link->getHashSource() == $entity) {
                $list[$link->getHashSigner()] = $link->getHashSigner();
            }
        }

        return $list;
    }


    /**
     * ID de référence de l'objet.
     *
     * @var string
     */
    private $_referenceObject = '';

    /**
     * Calcule et retourne la référence de l'objet.
     *
     * @return string
     */
    public function getReferenceObject()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_referenceObject == '') {
            $this->_referenceObject = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }
        return $this->_referenceObject;
    }

    /**
     * ID de référence de l'objet de fermeture.
     *
     * @var string
     */
    private $_referenceObjectClosed = '';

    /**
     * Calcule et retourne la référence de l'objet de fermeture.
     *
     * @return string
     */
    public function getReferenceObjectClosed()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_referenceObjectClosed == '') {
            $this->_referenceObjectClosed = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_FERMEE, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }
        return $this->_referenceObjectClosed;
    }

    /**
     * ID de référence de l'objet de protection des membres.
     *
     * @var string
     */
    private $_referenceObjectProtected = '';

    /**
     * Calcule et retourne la référence de l'objet de protection des membres.
     *
     * @return string
     */
    public function getReferenceObjectProtected()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_referenceObjectProtected == '') {
            $this->_referenceObjectProtected = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_PROTEGEE, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }
        return $this->_referenceObjectProtected;
    }

    /**
     * ID de référence de l'objet de dissimulation des membres.
     *
     * @var string
     */
    private $_referenceObjectObfuscated = '';

    /**
     * Calcule et retourne la référence de l'objet de dissimulation des membres.
     *
     * @return string
     */
    public function getReferenceObjectObfuscated()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_referenceObjectObfuscated == '') {
            $this->_referenceObjectObfuscated = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_DISSIMULEE, nebule::REFERENCE_CRYPTO_HASH_ALGORITHM);
        }
        return $this->_referenceObjectObfuscated;
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#oc">OC / Conversation</a>
            <ul>
                <li><a href="#oco">OCO / Objet</a></li>
                <li><a href="#ocn">OCN / Nommage</a></li>
                <li><a href="#ocp">OCP / Protection</a></li>
                <li><a href="#ocd">OCD / Dissimulation</a></li>
                <li><a href="#ocf">OCF / Fermeture</a></li>
                <li><a href="#ocpm">OCPM / Protection des membres</a></li>
                <li><a href="#ocdm">OCDM / Dissimulation des membres</a></li>
                <li><a href="#ocl">OCL / Liens</a></li>
                <li><a href="#occ">OCC / Création</a></li>
                <li><a href="#ocs">OCS / Stockage</a></li>
                <li><a href="#oct">OCT / Transfert</a></li>
                <li><a href="#ocr">OCR / Réservation</a></li>
                <li><a href="#ocio">OCIO / Implémentation des Options</a></li>
                <li><a href="#ocia">OCIA / Implémentation des Actions</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h2 id="oc">OC / Conversation</h2>
        <p>La conversation est un objet définit comme tel, c’est à dire qu’il doit avoir un type mime <code>nebule/objet/conversation</code>.
        </p>
        <p>Fondamentalement, la conversation est un groupe de plusieurs objets et est donc géré de la même façon qu'un
            groupe. Ainsi, un membre de la conversation n'est pas une entité mais un message, une entité est dite entité
            contributrice. Certains liens générés sont communs avec ceux des groupes et si un objet est marqué comme
            groupe et conversation, ses membres seront les mêmes.</p>
        <p>La conversation va permettre de regrouper, et donc d’associer et de retrouver, des message. L’objet de la
            conversation va avoir des liens vers d’autres objets afin de les définir comme messages (membres) de la
            conversation.</p>
        <p>Une conversation peut avoir des liens de membres vers des objets définis aussi comme conversations. Ces
            objets peuvent être vus comme des sous-conversations. La bibliothèque <em>nebule</em> ne prend en compte
            qu’un seul niveau de conversation, c’est à dire que les sous-conversations sont gérés simplement comme des
            objets.</p>

        <h3 id="oco">OCO / Objet</h3>
        <p>L’objet de la conversation peut être de deux natures.</p>
        <p>Soit c’est un objet existant qui est en plus définit comme une conversation. L’objet peut avoir un contenu et
            a sûrement d’autres types mime propres. Dans ce cas l’identifiant de conversation est l’identifiant de
            l’objet utilisé.</p>
        <p>Soit c’est un objet dit virtuel qui n’a pas et n’aura jamais de contenu. Cela n’empêche pas qu’il puisse
            avoir d’autres types mime. Dans ce cas l’identifiant de conversation a une forme commune aux objets
            virtuels.</p>
        <p>La création d’un objet virtuel comme conversation se fait en créant pour identifiant la concaténation d’un
            hash (<em>sha256</em>) d’une valeur aléatoire de 128bits et de la chaîne <code>006e6562756c652f6f626a65742f636f6e766572736174696f6e</code>.
            Soit un identifiant complet de la taille de 116 caractères.</p>

        <h3 id="ocn">OCN / Nommage</h3>
        <p>Le nommage à l’affichage du nom des conversations repose sur une seule propriété :</p>
        <ol>
            <li>nom</li>
        </ol>
        <p>Cette propriété est matérialisée par un lien de type <code>l</code> avec comme objets méta :</p>
        <ol>
            <li><code>nebule/objet/nom</code></li>
        </ol>
        <p>Par convention, voici le nommage des conversations :</p>
        <ul>
            <li><code>nom</code></li>
        </ul>

        <h3 id="ocp">OCP / Protection</h3>
        <p>En tant que tel la conversation ne nécessite pas de protection puisque soit l’objet de la conversation n’a
            pas de contenu soit on n’utilise pas son contenu directement.</p>
        <p>La gestion de la protection est désactivée dans une instance de conversation.</p>

        <h3 id="ocd">OCD / Dissimulation</h3>
        <p>La conversation peut en tant que tel être dissimulée, c’est à dire que l’on dissimule l’existence de la
            conversation, donc sa création.</p>
        <p>La dissimulation devrait se faire lors de la création de la conversation.</p>
        <p>L’annulation de la dissimulation d’une conversation revient à révéler le lien de création de la
            conversation.</p>
        <p>La dissimulation peut se (re)faire après la création de la conversation mais son efficacité est incertaine si
            les liens de création ont déjà été diffusés. En cas de dissimulation à posteriori, il faut générer un lien
            de suppression de la conversation puis générer un nouveau lien dissimulée de création de la conversation à
            une date postérieure au lien de suppression.</p>

        <h3 id="ocf">OCF / Fermeture</h3>
        <p>La conversation va contenir un certain nombre de membres (messages) ajouter par différentes entités. Il est
            possible de limiter le nombre des membres à utiliser dans une conversation en restreignant artificiellement
            les entités contributrices de la conversation. Ainsi on marque la conversation comme fermée et on filtre sur
            les membres uniquement ajoutés par des entités définies.</p>
        <p>Dans nebule, l’objet réservé <code>nebule/objet/conversation/fermee</code> est dédié à la gestion des
            conversations fermées. Une conversation est considéré fermée quand on a l’objet réservé en champs méta,
            l’entité en cours en champs cible et l’ID de la conversation en champs source. Si au lieu d’utiliser
            l’entité en cours pour le champs cible on utilise une autre entité, cela revient à prendre aussi en compte
            ses liens dans la conversation fermée. Dans ce cas c’est une entité contributrice.</p>
        <p>C’est uniquement un affichage de la conversation que l’on a et non la suppression de membres de la
            conversation.</p>
        <p>Lorsque l’on a marqué une conversation comme fermée, on doit explicitement ajouter des entités que l’on veut
            voir contribuer.</p>
        <p>Il est possible indéfiniment de fermer et ouvrir une conversation.</p>
        <p>Il est possible de fermer une conversation qui ne nous appartient afin par exemple de la rendre plus
            lisible.</p>
        <p>Lorsque l’on a marqué une conversation comme fermée, on peut voir la liste des entités explicitement que l’on
            veut voir contribuer. On peut aussi voir les entités que les autres entités veulent voir contribuer et
            décider ou non de les ajouter.</p>
        <p>Lorsqu’une conversation est marqué comme fermée, l’interface de visualisation de la conversation peut
            permettre de la visualiser temporairement comme une conversation ouvert.</p>
        <p>Le traitement des liens de fermeture d’une conversation doit être fait exclusivement avec le traitement
            social <em>self</em>.</p>

        <h4 id="ocpm">OCPM / Protection des membres</h4>
        <p>La conversation va contenir un certain nombre de membres (messages) ajouter par différentes entités. Il est
            possible de limiter la visibilité du contenu des membres utilisés dans une conversation en restreignant
            artificiellement les entités destinataires qui pourront les consulter.</p>
        <p>Dans nebule, l’objet réservé <code>nebule/objet/conversation/protegee</code> est dédié à la gestion des
            conversations protégées. Une conversation est considéré protégée quand on a l’objet réservé en champs méta,
            l’entité en cours en champs cible et l’ID de la conversation en champs source. Si au lieu d’utiliser
            l’entité en cours pour le champs cible on utilise une autre entité, cela revient à partager aussi les objets
            protégées créés pour cette conversation. Cela ne repartage pas la protection des objets déjà protégés.</p>
        <p>Dans une conversation marqué protégée, tous les nouveaux membres ajoutés à la conversation ont leur contenu
            protégé. Ce n’est valable que pour l’entité en cours et éventuellement celles qui lui font confiance.</p>
        <p>Lorsque l’on a marqué une conversation comme protégée, on doit explicitement ajouter des entités avec qui on
            veut partager les contenus.</p>
        <p>Il est possible indéfiniment de protéger et déprotéger une conversation.</p>
        <p>Il est possible de protéger une conversation qui ne nous appartient afin de masquer le contenu des membres
            que l’on y ajoute.</p>
        <p>Lorsque l’on a marqué une conversation comme protégée, on peut voir la liste des entités explicitement a qui
            on veut partager les contenus. On peut aussi voir les entités a qui les autres entités veulent partager les
            contenus et décider ou non de les ajouter.</p>
        <p>Le traitement des liens de protection d’une conversation doit être fait exclusivement avec le traitement
            social <em>self</em>.</p>

        <h4 id="ocdm">OCDM / Dissimulation des membres</h4>
        <p>La conversation va contenir un certain nombre de membres (messages) ajouter par différentes entités. Il est
            possible de limiter la visibilité de l’appartenance des membres utilisés dans une conversation en
            restreignant artificiellement les entités destinataires qui pourront les voir.</p>
        <p>Dans nebule, l’objet réservé <code>nebule/objet/conversation/dissimulee</code> est dédié à la gestion des
            conversations dissimulées. Une conversation est considéré dissimulée quand on a l’objet réservé en champs
            méta, l’entité en cours en champs cible et l’ID de la conversation en champs source. Si au lieu d’utiliser
            l’entité en cours pour le champs cible on utilise une autre entité, cela revient à partager aussi les objets
            dissimulées créés pour cette conversation. Cela ne repartage pas la dissimulation des objets déjà
            dissimulés.</p>
        <p>Dans une conversation marqué dissimulée, tous les nouveaux membres ajoutés à la conversation sont dissimulés.
            Ce n’est valable que pour l’entité en cours et éventuellement celles qui lui font confiance.</p>
        <p>Lorsque l’on a marqué une conversation comme dissimulée, on doit explicitement ajouter des entités avec qui
            on veut partager les membres de la conversation.</p>
        <p>Il est possible indéfiniment de dissimuler et dé-dissimuler une conversation.</p>
        <p>Il est possible de dissimuler une conversation qui ne nous appartient afin de masquer le contenu des membres
            que l’on y ajoute.</p>
        <p>Lorsque l’on a marqué une conversation comme dissimulée, on peut voir la liste des entités explicitement a
            qui on veut partager les contenus. On peut aussi voir les entités a qui les autres entités veulent partager
            les contenus et décider ou non de les ajouter.</p>
        <p>Le traitement des liens de dissimulation d’une conversation doit être fait exclusivement avec le traitement
            social <em>self</em>.</p>

        <h3 id="ocl">OCL / Liens</h3>
        <p>Une entité doit être déverrouillée pour la création de liens.</p>
        <ul>
            <li>Le lien de définition de la conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : hash(‘nebule/objet/conversation’)</li>
                    <li>méta : hash(‘nebule/objet/type’)</li>
                </ul>
            </li>
            <li>Le lien de suppression d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : hash(‘nebule/objet/conversation’)</li>
                    <li>méta : hash(‘nebule/objet/type’)</li>
                </ul>
            </li>
            <li>Le lien de suivi de la conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de l'entité, par défaut l’entité signataire</li>
                    <li>cible : ID de la conversation</li>
                    <li>méta : hash(‘nebule/objet/conversation/suivie’)</li>
                </ul>
            </li>
            <li>Le lien de suppression de suivi de la conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID de l'entité, par défaut l’entité signataire</li>
                    <li>cible : ID de la conversation</li>
                    <li>méta : hash(‘nebule/objet/conversation/suivie’)</li>
                </ul>
            </li>
            <li>Le lien de dissimulation d’une conversation est le lien de définition caché dans une lien de type <code>c</code>.
            </li>
            <li>Le lien de rattachement d’un membre (message) de la conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’objet</li>
                    <li>méta : ID de la conversation</li>
                </ul>
            </li>
            <li>Le lien de suppression de rattachement d’un membre (message) de la conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’objet</li>
                    <li>méta : ID de la conversation</li>
                </ul>
            </li>
            <li>Le lien de fermeture d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire.</li>
                    <li>méta : hash(‘nebule/objet/conversation/fermee’)</li>
                </ul>
            </li>
            <li>Le lien de suppression de fermeture d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire.</li>
                    <li>méta : hash(‘nebule/objet/conversation/fermee’)</li>
                </ul>
            </li>
            <li>Le lien de protection des membres d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire.</li>
                    <li>méta : hash(‘nebule/objet/conversation/protegee’)</li>
                </ul>
            </li>
            <li>Le lien de suppression de protection des membres d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire.</li>
                    <li>méta : hash(‘nebule/objet/conversation/protegee’)</li>
                </ul>
            </li>
            <li>Le lien de dissimulation des membres d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire.</li>
                    <li>méta : hash(‘nebule/objet/conversation/dissimulee’)</li>
                </ul>
            </li>
            <li>Le lien de suppression de dissimulation des membres d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>x</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire.</li>
                    <li>méta : hash(‘nebule/objet/conversation/dissimulee’)</li>
                </ul>
            </li>
        </ul>

        <h3 id="occ">OCC / Création</h3>
        <p>Liste des liens à générer lors de la création d'une conversation :</p>
        <ul>
            <li>Le lien de définition de la conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : hash(‘nebule/objet/conversation’)</li>
                    <li>méta : hash(‘nebule/objet/type’)</li>
                </ul>
            </li>
            <li>Le lien de nommage de la conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : hash(nom de la conversation)</li>
                    <li>méta : hash(‘nebule/objet/nom’)</li>
                </ul>
            </li>
            <li>Le lien de suivi de la conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de l'entité, par défaut l’entité signataire</li>
                    <li>cible : ID de la conversation</li>
                    <li>méta : hash(‘nebule/objet/conversation/suivie’)</li>
                </ul>
            </li>
        </ul>
        <p>On peut aussi au besoin ajouter ces liens :</p>
        <ul>
            <li>Le lien de fermeture d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/conversation/ferme’)</li>
                </ul>
            </li>
            <li>Le lien de protection des membres d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/conversation/protege’)</li>
                </ul>
            </li>
            <li>Le lien de dissimulation des membres d’une conversation :
                <ul>
                    <li>Signature du lien</li>
                    <li>Identifiant du signataire</li>
                    <li>Horodatage</li>
                    <li>action : <code>l</code></li>
                    <li>source : ID de la conversation</li>
                    <li>cible : ID de l’entité, par défaut l’entité signataire</li>
                    <li>méta : hash(‘nebule/objet/conversation/dissimule’)</li>
                </ul>
            </li>
        </ul>

        <h3 id="ocs">OCS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="oct">OCT / Transfert</h3>
        <p>A faire...</p>

        <h3 id="ocr">OCR / Réservation</h3>
        <p>Les objets réservés spécifiquement pour les conversations :</p>
        <ul>
            <li>nebule/objet/conversation</li>
            <li>nebule/objet/conversation/fermee</li>
            <li>nebule/objet/conversation/protegee</li>
            <li>nebule/objet/conversation/dissimulee</li>
        </ul>

        <h4 id="ocio">OCIO / Implémentation des Options</h4>
        <p>Les options spécifiques aux conversations :</p>
        <ul>
            <li><code>permitWriteConversation</code> : permet toute écriture de conversations.</li>
        </ul>
        <p>Les options qui ont une influence sur les conversations :</p>
        <ul>
            <li><code>permitWrite</code> : permet toute écriture d’objets et de liens ;</li>
            <li><code>permitWriteObject</code> : permet toute écriture d’objets ;</li>
            <li><code>permitCreateObject</code> : permet la création locale d’objets ;</li>
            <li><code>permitWriteLink</code> : permet toute écriture de liens ;</li>
            <li><code>permitCreateLink</code> : permet la création locale de liens.</li>
        </ul>
        <p>Il est nécessaire à la création d’une conversation de pouvoir écrire des objets comme le nom de la
            conversation, même si l’objet de la conversation ne sera pas créé.</p>

        <h4 id="ocia">OCIA / Implémentation des Actions</h4>
        <p>Dans les actions, on retrouve les chaînes :</p>
        <ul>
            <li><code>creagrp</code> : Crée une conversation.</li>
            <li><code>creagrpnam</code> : Nomme la conversation à créer.</li>
            <li><code>creagrpcld</code> : Marque fermée la conversation à créer.</li>
            <li><code>creagrpobf</code> : Dissimule les liens de la conversation à créer.</li>
            <li><code>actdelgrp</code> : Supprime une conversation.</li>
            <li><code>actaddtogrp</code> : Ajoute l’objet courant membre à conversation.</li>
            <li><code>actremtogrp</code> : Retire l’objet courant membre d’une conversation.</li>
            <li><code>actadditogrp</code> : Ajoute un objet membre à la conversation courant.</li>
            <li><code>actremitogrp</code> : Retire un objet membre de la conversation courant.</li>
        </ul>

        <?php
    }
}


/**
 * ------------------------------------------------------------------------------------------
 * La classe Currency.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'une monnaie ou 'new' ;
 * - un tableau des paramètres de la nouvelle monnaie.
 *
 * L'ID d'une monnaie est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture de la monnaie ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class Currency extends Node
{
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
        '_isTransaction',
        '_properties',
        '_propertiesInherited',
        '_propertiesForced',
        '_seed',
        '_inheritedCID',
        '_inheritedPID',
    );

    /**
     * Tableau des propriétés de la monnaie.
     * @var array
     */
    protected $_properties = array();

    /**
     * Tableau des propriétés héritées.
     * @var array
     */
    protected $_propertiesInherited = array();

    /**
     * Tableau des propriétés forcées.
     * @var array
     */
    protected $_propertiesForced = array();

    /**
     * Tableau des noms des propriétés disponibles.
     *
     * Le premier niveau définit les différentes catégories d'objets.
     *
     * Le second niveau nomme la propriété en interne et dispose de sous-propriétés :
     * - key : le nom publique de la clé pour l'objet.
     * - shortname : le nom de la clé pour les échangent en HTML, sans ambiguité par rapport au type d'objet.
     * - type : typage de la valeur attendu.
     * - info : le code de renvoi dans la page de documentation.
     * - force : valeur forcée dans le code, donc non modifiable lors de la création de l'objet.
     * - forceable : la clé:valeur peut être forcée dans l'objet généré. Sinon la valeur peut être surchargée par un lien.
     * - inherite : la valeur est hérité et n'est pas écrite.
     * - calculate : valeur calculée à postériori. Si présent, est équivalent à true.
     * - multivalue : clé à valeurs multiples séparées par des espaces. Si présent, est équivalent à true.
     * - limit : limit de la valeur attendu. Utilisé par les filtres de netoyage des valeurs.
     * - meta : l'objet à utiliser en temps que meta pour les liens.
     * - display : la taille du champs à utiliser pour la saisie de la valeur, non limitatif sur la taille de la valeur saisie.
     * - select : réduit le nombre de réponses possibles via un menu déroulant de taille fixe. Une seule réponse peut être sélectionnée.
     * - checkbox : permet une sélection multiple sur une liste. La valeur manipulée est une chaîne séparée par des espaces.
     *
     * @var array
     */
    protected $_propertiesList = array(
        'currency' => array(
            'CurrencyHaveContent' => array('key' => 'HCT', 'shortname' => 'chct', 'type' => 'boolean', 'info' => 'omcphct', 'force' => 'true',),
            'CurrencyType' => array('key' => 'TYP', 'shortname' => 'ctyp', 'type' => 'string', 'info' => 'omcptyp', 'limit' => '32', 'display' => '16', 'force' => 'cryptocurrency',),
            'CurrencySerialID' => array('key' => 'SID', 'shortname' => 'csid', 'type' => 'hexadecimal', 'info' => 'omcpsid', 'limit' => '1024', 'display' => '64',),
            'CurrencyCapacities' => array('key' => 'CAP', 'shortname' => 'ccap', 'type' => 'string', 'info' => 'omcpcap', 'limit' => '1024', 'display' => '128', 'force' => 'TYP MOD SID CAP AID MID NAM UNI DTA DTC DTD COM CPR IDM IDR IDP VMD VID TRS CID PID FID BID VAL CLB CLD SVC TID', 'multivalue' => true,),
            'CurrencyExploitationMode' => array('key' => 'MOD', 'shortname' => 'cmod', 'type' => 'string', 'info' => 'omcpmod', 'limit' => '3', 'display' => '10', 'force' => 'CTL',),
            'CurrencyAutorityID' => array('key' => 'AID', 'shortname' => 'caid', 'type' => 'hexadecimal', 'info' => 'omcpaid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'CurrencyCurrencyName' => array('key' => 'NAM', 'shortname' => 'cnam', 'type' => 'string', 'info' => 'omcpnam', 'limit' => '256', 'display' => '32', 'forceable' => true,),
            'CurrencyCurrencyUnit' => array('key' => 'UNI', 'shortname' => 'cuni', 'type' => 'string', 'info' => 'omcpuni', 'limit' => '3', 'display' => '6', 'forceable' => true,),
            'CurrencyDateAuthorityID' => array('key' => 'DTA', 'shortname' => 'cdta', 'type' => 'hexadecimal', 'info' => 'omcpdta', 'limit' => '1024', 'display' => '64', 'forceable' => true,),
            'CurrencyDateCreate' => array('key' => 'DTC', 'shortname' => 'cdtc', 'type' => 'date', 'info' => 'omcpdtc', 'display' => '32', 'forceable' => true,),
            'CurrencyDateDelete' => array('key' => 'DTD', 'shortname' => 'cdtd', 'type' => 'date', 'info' => 'omcpdtd', 'display' => '32', 'forceable' => true,),
            'CurrencyComment' => array('key' => 'COM', 'shortname' => 'ccom', 'type' => 'string', 'info' => 'omcpcom', 'limit' => '4096', 'display' => '128', 'forceable' => true,),
            'CurrencyCopyright' => array('key' => 'CPR', 'shortname' => 'ccpr', 'type' => 'string', 'info' => 'omcpcpr', 'limit' => '1024', 'display' => '128', 'forceable' => true,),
            'CurrencyInflateMode' => array('key' => 'IDM', 'shortname' => 'cidm', 'type' => 'string', 'info' => 'omcpidm', 'limit' => '10', 'display' => '12', 'forceable' => true, 'select' => array('disabled', 'creation', 'transaction'),),
            'CurrencyInflateRate' => array('key' => 'IDR', 'shortname' => 'cidr', 'type' => 'number', 'info' => 'omcpidr', 'limit' => '2', 'display' => '10', 'forceable' => true,),
            'CurrencyInflatePeriod' => array('key' => 'IDP', 'shortname' => 'cidp', 'type' => 'number', 'info' => 'omcpidp', 'limit' => '1048576', 'display' => '10', 'forceable' => true,),
            'CurrencyValidationMode' => array('key' => 'VMD', 'shortname' => 'cvmd', 'type' => 'string', 'info' => 'omcpvmd', 'limit' => '10', 'display' => '12', 'forceable' => true, 'select' => array('central'),),
            'CurrencyValidationID' => array('key' => 'VID', 'shortname' => 'cvid', 'type' => 'hexadecimal', 'info' => 'omcpvid', 'limit' => '1024', 'display' => '64', 'forceable' => true, 'multivalue' => true,),
            'CurrencyManageID' => array('key' => 'MID', 'shortname' => 'cmid', 'type' => 'hexadecimal', 'info' => 'omcpmid', 'limit' => '1024', 'display' => '64', 'forceable' => true, 'checkbox' => '', 'multivalue' => true,),
            'CurrencyTransacMethods' => array('key' => 'TRS', 'shortname' => 'ctrs', 'type' => 'string', 'info' => 'omcptrs', 'limit' => '1024', 'display' => '128', 'force' => 'LNS', 'multivalue' => true,),
            'CurrencyID' => array('key' => 'CID', 'shortname' => 'ccid', 'type' => 'hexadecimal', 'info' => 'omcpcid', 'limit' => '1024', 'display' => '64', 'calculate' => true,),
        ),
        'tokenpool' => array(
            'PoolHaveContent' => array('key' => 'HCT', 'shortname' => 'phct', 'type' => 'boolean', 'info' => 'omgcphct', 'force' => 'true',),
            'PoolType' => array('key' => 'TYP', 'shortname' => 'ptyp', 'type' => 'string', 'info' => 'omgcptyp', 'limit' => '32', 'display' => '16', 'force' => 'tokenpool',),
            'PoolSerialID' => array('key' => 'SID', 'shortname' => 'psid', 'type' => 'hexadecimal', 'info' => 'omgcpsid', 'limit' => '1024', 'display' => '64',),
            'PoolCapacities' => array('key' => 'CAP', 'shortname' => 'pcap', 'type' => 'string', 'info' => 'omgcpcap', 'display' => '128', 'inherite' => 'currency', 'multivalue' => true,),
            'PoolCurrencyID' => array('key' => 'CID', 'shortname' => 'pcid', 'type' => 'hexadecimal', 'info' => 'omgcpcid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'PoolForgeID' => array('key' => 'FID', 'shortname' => 'pfid', 'type' => 'hexadecimal', 'info' => 'omgcpfid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'PoolDateAuthorityID' => array('key' => 'DTA', 'shortname' => 'pdta', 'type' => 'hexadecimal', 'info' => 'omgcpdta', 'display' => '64', 'inherite' => 'currency',),
            'PoolDateCreate' => array('key' => 'DTC', 'shortname' => 'pdtc', 'type' => 'date', 'info' => 'omgcpdtc', 'display' => '32', 'forceable' => true,),
            'PoolDateDelete' => array('key' => 'DTD', 'shortname' => 'pdtd', 'type' => 'date', 'info' => 'omgcpdtd', 'display' => '32', 'forceable' => true,),
            'PoolComment' => array('key' => 'COM', 'shortname' => 'pcom', 'type' => 'string', 'info' => 'omgcpcom', 'limit' => '4096', 'display' => '128', 'forceable' => true,),
            'PoolCopyright' => array('key' => 'CPR', 'shortname' => 'pcpr', 'type' => 'string', 'info' => 'omgcpcpr', 'limit' => '1024', 'display' => '128', 'forceable' => true,),
            'PoolManageID' => array('key' => 'MID', 'shortname' => 'pmid', 'type' => 'hexadecimal', 'info' => 'omgcpmid', 'limit' => '1024', 'display' => '64', 'forceable' => true, 'checkbox' => '', 'multivalue' => true,),
            'PoolID' => array('key' => 'PID', 'shortname' => 'ppid', 'type' => 'hexadecimal', 'info' => 'omgcppid', 'limit' => '1024', 'display' => '64', 'calculate' => true,),
        ),
        'token' => array(
            'TokenHaveContent' => array('key' => 'HCT', 'shortname' => 'thct', 'type' => 'boolean', 'info' => 'omjcphct', 'force' => 'true',),
            'TokenType' => array('key' => 'TYP', 'shortname' => 'ttyp', 'type' => 'string', 'info' => 'omjcptyp', 'limit' => '32', 'display' => '16', 'force' => 'cryptoken',),
            'TokenSerialID' => array('key' => 'SID', 'shortname' => 'tsid', 'type' => 'hexadecimal', 'info' => 'omjcpsid', 'limit' => '1024', 'display' => '64',),
            'TokenCapacities' => array('key' => 'CAP', 'shortname' => 'tcap', 'type' => 'string', 'info' => 'omjcpcap', 'display' => '128', 'inherite' => 'currency', 'multivalue' => true,),
            'TokenCurrencyID' => array('key' => 'CID', 'shortname' => 'tcid', 'type' => 'hexadecimal', 'info' => 'omjcpcid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'TokenForgeID' => array('key' => 'FID', 'shortname' => 'tfid', 'type' => 'hexadecimal', 'info' => 'omjcpfid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'TokenPoolID' => array('key' => 'PID', 'shortname' => 'tpid', 'type' => 'hexadecimal', 'info' => 'omjcppid', 'limit' => '1024', 'display' => '64', 'inherite' => 'pool',),
            'TokenBlockID' => array('key' => 'BID', 'shortname' => 'tbid', 'type' => 'hexadecimal', 'info' => 'omjcpbid', 'limit' => '1024', 'display' => '64', 'forceable' => true,),
            'TokenCurrencyName' => array('key' => 'NAM', 'shortname' => 'tnam', 'type' => 'string', 'info' => 'omjcpnam', 'display' => '32', 'inherite' => 'currency',),
            'TokenCurrencyUnit' => array('key' => 'UNI', 'shortname' => 'tuni', 'type' => 'string', 'info' => 'omjcpuni', 'display' => '6', 'inherite' => 'currency',),
            'TokenValue' => array('key' => 'VAL', 'shortname' => 'tval', 'type' => 'number', 'info' => 'omjcpval', 'limit' => '1048576', 'display' => '10', 'forceable' => true,),
            'TokenService' => array('key' => 'SVC', 'shortname' => 'tsvc', 'type' => 'string', 'info' => 'omjcpsvc', 'limit' => '1024', 'display' => '128', 'forceable' => true,),
            'TokenDateAuthorityID' => array('key' => 'DTA', 'shortname' => 'tdta', 'type' => 'hexadecimal', 'info' => 'omjcpdta', 'display' => '64', 'inherite' => 'currency',),
            'TokenDateCreate' => array('key' => 'DTC', 'shortname' => 'tdtc', 'type' => 'date', 'info' => 'omjcpdtc', 'display' => '32', 'forceable' => true,),
            'TokenDateDelete' => array('key' => 'DTD', 'shortname' => 'tdtd', 'type' => 'date', 'info' => 'omjcpdtd', 'display' => '32', 'forceable' => true,),
            'TokenComment' => array('key' => 'COM', 'shortname' => 'tcom', 'type' => 'string', 'info' => 'omjcpcom', 'limit' => '4096', 'display' => '128', 'forceable' => true,),
            'TokenCopyright' => array('key' => 'CPR', 'shortname' => 'tcpr', 'type' => 'string', 'info' => 'omjcpcpr', 'limit' => '1024', 'display' => '128', 'forceable' => true,),
            'TokenInflateMode' => array('key' => 'IDM', 'shortname' => 'tidm', 'type' => 'string', 'info' => 'omjcpidm', 'display' => '12', 'inherite' => 'currency',),
            'TokenInflateRate' => array('key' => 'IDR', 'shortname' => 'tidr', 'type' => 'number', 'info' => 'omjcpidr', 'display' => '10', 'inherite' => 'currency',),
            'TokenInflatePeriod' => array('key' => 'IDP', 'shortname' => 'tidp', 'type' => 'number', 'info' => 'omjcpidp', 'display' => '10', 'inherite' => 'currency',),
            'TokenCancelable' => array('key' => 'CLB', 'shortname' => 'tclb', 'type' => 'boolean', 'info' => 'omjcpclb', 'forceable' => true,),
            'TokenCanceled' => array('key' => 'CLD', 'shortname' => 'tcld', 'type' => 'boolean', 'info' => 'omjcpcld', 'force' => 'false',),
            'TokenTransacMethods' => array('key' => 'TRS', 'shortname' => 'ttrs', 'type' => 'string', 'info' => 'omjcptrs', 'display' => '128', 'inherite' => 'currency', 'multivalue' => true,),
            'TokenID' => array('key' => 'TID', 'shortname' => 'ttid', 'type' => 'hexadecimal', 'info' => 'omjcptid', 'limit' => '1024', 'display' => '64', 'calculate' => true,),
        ),
    );

    /**
     * Compteur interne de génération de l'aléa.
     *
     * @var string
     */
    protected $_seed = '';

    /**
     * Instance de la monnaie dont dépend l'instance.
     * N'est pas utilisé pour une monnaie.
     *
     * @var Currency
     */
    protected $_inheritedCID = null;

    /**
     * Instance de la monnaie dont dépend l'instance.
     * N'est pas utilisé pour une monnaie.
     *
     * @var TokenPool
     */
    protected $_inheritedPID = null;

    /**
     * Tableau des capacités reconnues de la monnaie.
     *
     * @var array:string
     */
    protected $_CAParray = array();

    /**
     * Constructeur.
     *
     * Toujours transmettre l'instance de la librairie nebule.
     * Si la monnaie existe, juste préciser l'ID de celle-ci.
     * Si c'est une nouvelle monnaie à créer, mettre l'ID à 'new'.
     *
     * @param nebule $nebuleInstance
     * @param string $id
     * @param array $param si $id == 'new'
     * @param boolean $protected si $id == 'new'
     * @param boolean $obfuscated si $id == 'new'
     */
    public function __construct(nebule $nebuleInstance, $id, $param = array(), $protected = false, $obfuscated = false)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance currency ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
        ) {
            // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
            $this->_loadCurrency($id);
        } elseif (is_string($id)
            && $id == 'new'
        ) {
            // Si c'est une nouvelle monnaie à créer, renvoie à la création.
            $this->_createNewCurrency($param, $protected, $obfuscated);
        } else {
            // Sinon, la monnaie est invalide, retourne 0.
            $this->_id = '0';
        }
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_id;
    }

    /**
     * Retourne les variables à sauvegarder dans la session php lors d'une mise en sommeil de l'instance.
     *
     * @return array:string
     */
    public function __sleep()
    {
        return self::SESSION_SAVED_VARS;
    }

    /**
     * Foncion de réveil de l'instance et de ré-initialisation de certaines variables non sauvegardées.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_cacheMarkDanger = false;
        $this->_cacheMarkWarning = false;
        $this->_cacheUpdate = '';

        // Complément des paramètres.
//		$this->_propertiesList['currency']['CurrencyAutorityID']['force'] = $this->_nebuleInstance->getCurrentEntity();
    }

    /**
     *  Chargement d'une monnaie existant.
     *
     * @param string $id
     */
    private function _loadCurrency($id)
    {
        // Vérifie que c'est bien un objet.
        if (!is_string($id)
            || $id == ''
            || !ctype_xdigit($id)
            || !$this->_io->checkLinkPresent($id)
            || !$this->_nebuleInstance->getOption('permitCurrency')
        ) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrology->addLog('Load currency ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log

        // On ne recherche pas les paramètres si ce n'est pas une monnaie.
        if ($this->getIsCurrency('myself')) {
            $this->setAID();
            $this->setFID('0');
        }

        // Vérifie la monnaie.
        $TYP = $this->_getParamFromObject('TYP', (int)$this->_propertiesList['currency']['CurrencyType']['limit']);
        $SID = $this->_getParamFromObject('SID', (int)$this->_propertiesList['currency']['CurrencySerialID']['limit']);
        $CAP = $this->_getParamFromObject('CAP', (int)$this->_propertiesList['currency']['CurrencyCapacities']['limit']);
        $MOD = $this->_getParamFromObject('MOD', (int)$this->_propertiesList['currency']['CurrencyExploitationMode']['limit']);
        $AID = $this->_getParamFromObject('AID', (int)$this->_propertiesList['currency']['CurrencyAutorityID']['limit']);
        if ($TYP == ''
            || $SID == ''
            || $CAP == ''
            || $MOD == ''
            || $AID == ''
        ) {
            $this->_id = '0';
        } else {
            $this->_propertiesList['currency']['CurrencyID']['force'] = $id;
            $this->_properties['CID'] = $id;
        }
        $this->_propertiesForced['TYP'] = true;
        $this->_propertiesForced['SID'] = true;
        $this->_propertiesForced['CAP'] = true;
        $this->_propertiesForced['MOD'] = true;
        $this->_propertiesForced['AID'] = true;
        $this->_propertiesForced['CID'] = true;
    }

    /**
     * Création d'une nouvelle monnaie.
     *
     * @param array $param
     * @return boolean
     */
    private function _createNewCurrency($param, $protected = false, $obfuscated = false)
    {
        $this->_metrology->addLog('Ask create currency', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'on puisse créer une monnaie.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCurrency')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getOption('permitCreateCurrency')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génère la nouvelle monnaie.
            $this->_id = $this->_createCurrency($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrology->addLog('Create currency error on generation', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create currency error not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }



    // Désactivation des fonctions de protection et autres.

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return boolean
     */
    public function getReloadMarkProtected()
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return string
     */
    public function getProtectedID()
    {
        return '0';
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return string
     */
    public function getUnprotectedID()
    {
        return $this->_id;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return boolean
     */
    public function setProtected($obfuscated = false)
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return boolean
     */
    public function setUnprotected()
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @param string|Entity $entity
     * @return boolean
     */
    public function setProtectedTo($entity)
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return array
     */
    public function getProtectedTo()
    {
        return array();
    }


    /**
     * Nettoye la table des paramètres en entrée avant exploitation.
     *
     * @param array $param
     * @return void
     * @todo faire le forcage de valeurs.
     *
     * array( 'currency' => array(
     * ...,
     * 'CurrencyType' => array(
     * 'key' => 'TYP',
     * 'shortname' => 'ctyp',
     * 'type' => 'string',
     * 'info' => 'omcptyp',
     * 'limit' => '32',
     * 'force' => 'cryptocurrency', ),
     * 'display' => '64',
     * 'forceable' => true,
     * ...,
     * ),
     * ...,
     * )
     *
     */
    protected function _normalizeInputParam(&$param)
    {
        foreach ($param as $key => $value) {
            foreach ($this->_propertiesList as $type => $nameArray) {
                // Si ce n'est pas le type d'objet en cours de création, continue.
                if (strtolower(get_class($this)) != $type) {
                    continue;
                }

                foreach ($nameArray as $name => $propArray) {
                    if ($key == $name) {
                        $param[$key] = null;

                        if (isset($propArray['force'])) {
                            // Détecte une tentative de modifier une valeur forçée.
                            if ($value != $propArray['force']
                                && $value != ''
                                && $value != null
                            ) {
                                $this->_metrology->addLog(get_class($this) . ' - Try to overwrite forced value ' . $propArray['key'] . ':' . $value, Metrology::LOG_LEVEL_ERROR); // Log
                            }

                            $this->_metrology->addLog(get_class($this) . ' - Normalize force ' . $propArray['key'] . ':' . $propArray['force'], Metrology::LOG_LEVEL_DEBUG); // Log
                            if ($propArray['type'] == 'boolean') {
                                if ($propArray['force'] == 'true') {
                                    $param[$key] = true;
                                } else {
                                    $param[$key] = false;
                                }
                            } elseif ($propArray['type'] == 'number') {
                                $param[$key] = (int)$propArray['force'];
                            } else {
                                $param[$key] = $propArray['force'];
                            }
                        } else {
                            if ($propArray['type'] == 'boolean'
                                && is_string($value)
                            ) {
                                $this->_metrology->addLog(get_class($this) . ' - Normalize bool ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                                if ($value == 'true') {
                                    $param[$key] = true;
                                }
                                if ($value == 'false') {
                                    $param[$key] = false;
                                }
                                break 2;
                            }

                            if ($propArray['type'] == 'string') {
                                $this->_metrology->addLog(get_class($this) . ' - Normalize string ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                                if (isset($propArray['limit'])
                                    && strlen($value) > (int)$propArray['limit']
                                ) {
                                    $param[$key] = substr($value, 0, (int)$propArray['limit']);
                                } else {
                                    $param[$key] = $value;
                                }
                                break 2;
                                // @todo faire validation des selects.
                            }

                            if ($propArray['type'] == 'hexadecimal') {
                                if (isset($propArray['checkbox'])) {
                                    $this->_metrology->addLog(get_class($this) . ' - Normalize multi hex ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                                    $valueArray = explode(' ', $value);
                                    $value = '';
                                    foreach ($valueArray as $item) {
                                        if (ctype_xdigit($item)) {
                                            $value .= ' ' . $item;
                                        }
                                    }
                                    unset($valueArray);

                                    if (isset($propArray['limit'])
                                        && strlen(trim($value)) > (int)$propArray['limit']
                                    ) {
                                        $param[$key] = substr(trim($value), 0, (int)$propArray['limit']);
                                    } else {
                                        $param[$key] = trim($value);
                                    }
                                } else {
                                    $this->_metrology->addLog(get_class($this) . ' - Normalize hex ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                                    if (!ctype_xdigit($value)) {
                                        // Si pas un hexa, supprime la valeur.
                                        $param[$name] = null;
                                    }
                                    if (isset($propArray['limit'])
                                        && strlen($value) > (int)$propArray['limit']
                                    ) {
                                        $param[$key] = substr($value, 0, (int)$propArray['limit']);
                                    } else {
                                        $param[$key] = $value;
                                    }
                                }
                                break 2;
                            }

                            if ($propArray['type'] == 'date') {
                                $this->_metrology->addLog(get_class($this) . ' - Normalize date ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                                if (isset($propArray['limit'])
                                    && strlen($value) > (int)$propArray['limit']
                                ) {
                                    $param[$key] = substr($value, 0, (int)$propArray['limit']);
                                } else {
                                    $param[$key] = $value;
                                }
                                // @todo faire la vérification que c'est une date !
                                break 2;
                            }

                            if ($propArray['type'] == 'number'
                                && is_string($value)
                            ) {
                                $this->_metrology->addLog(get_class($this) . ' - Normalize number ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                                $param[$key] = (double)$value;
                                if (isset($propArray['limit'])
                                    && $value > (double)$propArray['limit']
                                ) {
                                    $param[$key] = (double)$propArray['limit'];
                                } else {
                                    $param[$key] = $value;
                                }
                                break 2;
                            }
                        }
                    }
                    if ($key == 'Force' . $name
                        && isset($propArray['forceable'])
                        && $propArray['forceable']
                    ) {
                        $param[$key] = false;
                        $this->_metrology->addLog(get_class($this) . ' - Normalize force ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                        if ($value === true) {
                            $param[$key] = true;
                        }
                        break 2;
                    }
                }
            }
        }

        // Rempli les paramètres non renseignés.
        foreach ($this->_propertiesList as $nameArray) {
            foreach ($nameArray as $name => $propArray) {
                if (isset($param[$name])) {
                    continue;
                }

                if ($propArray['type'] == 'boolean') {
                    $param[$name] = false;
                    $this->_metrology->addLog(get_class($this) . ' - Normalize add ' . $propArray['key'] . ':false', Metrology::LOG_LEVEL_DEBUG); // Log
                } else {
                    $param[$name] = null;
                    $this->_metrology->addLog(get_class($this) . ' - Normalize add ' . $propArray['key'] . ':null', Metrology::LOG_LEVEL_DEBUG); // Log
                }
            }
        }
    }

    /**
     * Crée une monnaie.
     *
     * Les paramètres force* ne sont utilisés que si currencyHaveContent est à true.
     * Pour l'instant le code commence à prendre en compte currencyHaveContent à false mais le paramètre est forçé à true tant que le code n'est pas prêt.
     *
     * Les options pour la génération d'une monnaie :
     * currencyHaveContent
     * currencySerialID
     * currencyAutorityID
     *
     * forceCurrencyAutorityID
     *
     * Retourne la chaine avec 0 si erreur.
     *
     * array( 'currency' => array(
     * ...,
     * 'CurrencyType' => array(
     * 'key' => 'TYP',
     * 'shortname' => 'ctyp',
     * 'type' => 'string',
     * 'info' => 'omcptyp',
     * 'limit' => '32',
     * 'force' => 'cryptocurrency', ),
     * 'display' => '64',
     * 'forceable' => true,
     * ...,
     * ),
     * ...,
     * )
     *
     * @param array $param
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return string
     */
    private function _createCurrency($param, $protected = false, $obfuscated = false)
    {
        // Identifiant final de la monnaie.
        $this->_id = '0';

        // Normalise les paramètres.
        $this->_normalizeInputParam($param);

        // Force l'écriture de l'objet de la monnaie.
        $param['CurrencyHaveContent'] = true;

        // Force l'écriture du serial.
        $param['ForceCurrencySerialID'] = true;

        // Force le paramètre AID avec l'entité en cours.
        $param['CurrencyAutorityID'] = $this->_nebuleInstance->getCurrentEntity();
        $param['ForceCurrencyAutorityID'] = true;
        $this->_properties['AID'] = $param['CurrencyAutorityID'];

        // Force les capacités.
        $param['ForceCurrencyCapacities'] = true;

        // Détermine si la monnaie a un numéro de série fourni.
        $sid = '';
        if (isset($param['CurrencySerialID'])
            && is_string($param['CurrencySerialID'])
            && $param['CurrencySerialID'] != ''
            && ctype_xdigit($param['CurrencySerialID'])
        ) {
            $sid = $this->_stringFilter($param['CurrencySerialID']);
            $this->_metrology->addLog('Generate currency asked SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        } else {
            // Génération d'un numéro de série de monnaie unique aléatoire.
            $sid = $this->_getPseudoRandom();
            $param['CurrencySerialID'] = $sid;
            $this->_metrology->addLog('Generate currency rand SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        }


        // Détermine si la monnaie doit avoir un contenu.
        if (isset($param['CurrencyHaveContent'])
            && $param['CurrencyHaveContent'] === true
        ) {
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' HCT:true', Metrology::LOG_LEVEL_DEBUG); // Log

            // Le contenu final commence par l'identifiant interne de la monnaie.
            $content = 'TYP:' . $this->_propertiesList['currency']['CurrencyType']['force'] . "\n"; // @todo peut être intégré au reste.
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' TYP:' . $this->_propertiesList['currency']['CurrencyType']['force'], Metrology::LOG_LEVEL_DEBUG); // Log
            $content .= 'SID:' . $sid . "\n";
            $content .= 'CAP:' . $this->_propertiesList['currency']['CurrencyCapacities']['force'] . "\n";
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' CAP:' . $this->_propertiesList['currency']['CurrencyCapacities']['force'], Metrology::LOG_LEVEL_DEBUG); // Log
            $content .= 'MOD:' . $this->_propertiesList['currency']['CurrencyExploitationMode']['force'] . "\n";
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' MOD:' . $this->_propertiesList['currency']['CurrencyExploitationMode']['force'], Metrology::LOG_LEVEL_DEBUG); // Log
            $content .= 'AID:' . $this->_nebuleInstance->getCurrentEntity() . "\n";
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' AID:' . $this->_nebuleInstance->getCurrentEntity(), Metrology::LOG_LEVEL_DEBUG); // Log

            // Pour chaque propriété, si présente et forcée, l'écrit dans l'objet.
            foreach ($this->_propertiesList['currency'] as $name => $property) {
                if ($property['key'] != 'HCT'
                    && $property['key'] != 'TYP'
                    && $property['key'] != 'SID'
                    && $property['key'] != 'CAP'
                    && $property['key'] != 'MOD'
                    && $property['key'] != 'AID'
                    //&& $property['key'] != 'PCN'
                    //&& $property['key'] != 'TCN'
                    && isset($property['forceable'])
                    && $property['forceable'] === true
                    && isset($param['Force' . $name])
                    && $param['Force' . $name] === true
                    && isset($param[$name])
                    && $param[$name] != ''
                    && $param[$name] != null
                ) {
                    $value = null;
                    if ($property['type'] == 'boolean') {
                        if ($param[$name] === true) {
                            $value = true;
                        } else {
                            $value = false;
                        }
                    } elseif ($property['type'] == 'number') {
                        $value = (string)$param[$name];
                    } else {
                        $value = $param[$name];
                    }

                    // Ajoute la ligne.
                    $content .= $property['key'] . ':' . $value . "\n";
                    $this->_metrology->addLog('Generate currency SID:' . $sid . ' force ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                }
            }

            // Crée l'objet avec le contenu et l'écrit.
            $this->_data = $content;
            $this->_haveData = true;
            unset($content);

            // calcul l'ID.
            $this->_id = $this->_crypto->hash($this->_data);

            // Si l'objet doit être protégé.
            if ($protected) {
                $this->setProtected($obfuscated);
            } else {
                // Sinon écrit l'objet directement.
                $this->write();
            }
        } else {
            $this->_id = $sid;
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' HCT:false', Metrology::LOG_LEVEL_DEBUG); // Log
        }

        // La monnaie a maintenant un CID.
        $param['CurrencyID'] = $this->_id;
        $this->_metrology->addLog('Generate currency SID:' . $sid . ' CID:' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log


        // Prépare la génération des liens.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $source = $this->_id;
        $argObf = $obfuscated;

        // Le lien de type.
        $action = 'l';
        $target = $this->_nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE);
        $meta = $this->_nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $this->_createLink($signer, $date, $action, $source, $target, $meta, false);

        // Le lien de nommage si le nom est présent.
        if (isset($param['NAM'])
            && $param['NAM'] != null
            && $param['NAM'] != ''
        ) {
            $this->setName($param['NAM']);
        }

        // Crée les liens associés à la monnaie.
        //$action	= 'l';

        // Pour chaque propriété, si présente et a un méta, écrit le lien.
        foreach ($this->_propertiesList['currency'] as $name => $property) {
            if (isset($param[$name])
                && $param[$name] != null
            ) {
                $value = null;
                if ($property['type'] == 'boolean') {
                    if ($param[$name] === true) {
                        $value = 'true';
                    } else {
                        $value = 'false';
                    }
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'number') {
                    $value = (string)$param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'hexadecimal') {
                    $value = $param[$name];
                    $target = $value;
                } else {
                    $value = $param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                }

                if ($value != null) {
                    $this->_metrology->addLog('Generate currency SID:' . $sid . ' add ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                    $meta = $this->_nebuleInstance->getCrypto()->hash($property['key']);
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $argObf);
                    $this->_metrology->addLog('Generate currency SID:' . $sid . ' link=' . $target . '_' . $meta, Metrology::LOG_LEVEL_DEBUG); // Log
                }
            }
        }


        // Retourne l'identifiant de la monnaie.
        $this->_metrology->addLog('Generate currency end SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        return $this->_id;
    }


    /**
     * Filtrage d'un texte sur les caractères chiffres et lettres uniquement, et sur une seule ligne.
     *
     * @param string $value
     * @return string
     */
    protected function _stringFilter($value, $limit = 1024)
    {
        if ($value == '') {
            return '';
        }

        // Contenu retourné.
        $result = mb_convert_encoding(trim(strtok(filter_var(trim($value), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW | FILTER_FLAG_NO_ENCODE_QUOTES), "\n")), 'UTF-8'); // @todo faire la restriction de caractères plus fine...

        if ($result === false) {
            $result = '';
        }

        if (strlen($result) > $limit) {
            $result = substr($result, 0, $limit);
        }

        return $result;
    }

    /**
     * Retourne la liste des sacs de jetons de la monnaie.
     *
     * @return array:string
     */
    public function getPoolList()
    {
        return $this->_getItemList('CID');
    }

    /**
     * Retourne le nombre de sacs de jetons de la monnaie.
     *
     * @return integer
     */
    public function getPoolCount()
    {
        return sizeof($this->_getItemList('CID'));
    }

    protected function _getItemList($type)
    {
        $links1 = array();
        $links2 = array();
        $links3 = array();
        $list = array();

        // Prépare la recherche des monnaies.
        $referenceType = $this->_nebuleInstance->getCrypto()->hash($type);
        $meta = $this->_nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        if ($type == 'CID') {
            $target = $this->_nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_SAC);
        } elseif ($type == 'PID') {
            $target = $this->_nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_JETON);
        } else {
            return $list;
        }

        // Recherche les monnaies pour l'entité en cours.
        $links1 = $this->readLinksFilterFull(
            '',
            '',
            'l',
            '',
            $this->_id,
            $referenceType
        );

        // Filtrage type recherché. @todo faire filtrage sur MID.
        foreach ($links1 as $i => $link) {
            $instance = $this->_nebuleInstance->newObject($link->getHashSource());
            $links2 = $instance->readLinksFilterFull(
                $link->getHashSigner(),
                '',
                'l',
                $link->getHashSource(),
                $target,
                $meta
            );

            if (sizeof($links2) != 0) {
                $list[$link->getHashSource()] = $link->getHashSource();
            }
        }

        return $list;
    }

    /**
     * Lit une clé et retourne un texte avec la valeur.
     *
     * Cette fonction est héritée par les sacs de jetons et les jetons et doit fonctionner dans les différentes classes.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string|null
     */
    protected function _getParam($key, $maxsize)
    {
        $this->_metrology->addLog(get_class($this) . ' get param start for ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG); // Log

        // La réponse.
        $result = null;

        if (!is_a($this, 'Currency')) {
            return $result;
        }

        // Lit le cache.
        if (isset($this->_properties[$key])) {
            $result = $this->_properties[$key];
        }

        // Traite les paramètres de présence de données forçées à part.
        if ($key == 'HCT'
            && $this->checkPresent()
        ) {
            $this->_propertiesForced['HCT'] = true;
            $result = true;
        }

        // Traite les paramètres de nombre de sacs ou jetons à part.
        /*		if ( $key == 'PCN' )
		{
			$result = sizeof($this->_getItemList('PID'));
		}
		if ( $key == 'TCN' )
		{
			$result = sizeof($this->_getItemList('TID'));
		}*/

        // Cherche l'option dans la monnaie définie (pas pour les monnaies).
        if ($result === null
            && $key != 'HCT'
            && $key != 'TYP'
            && $key != 'SID'
            && $key != 'FID'
            && $key != 'BID'
            && $key != 'COM'
            && $key != 'CPR'
        ) {
            $result = $this->_getParamFromCurrency($key, $maxsize);
        }

        // Cherche l'option dans le sac de jetons (pour les jetons).
        if ($result === null
            && $key != 'HCT'
            && $key != 'TYP'
            && $key != 'SID'
            && $key != 'FID'
            && $key != 'BID'
            && $key != 'COM'
            && $key != 'CPR'
        ) {
            $result = $this->_getParamFromPool($key, $maxsize);
        }

        // Cherche l'option dans l'environnement.
        if ($result === null) {
            $result = $this->_getParamFromObject($key, $maxsize);
        }

        // Si non trouvé, cherche l'option dans les liens.
        if ($result === null) {
            $result = $this->_getParamFromLinks($key, $maxsize);
        }

        $this->_metrology->addLog(get_class($this) . ' return param final ' . $this->_id . ' - ' . $key . ' = ' . (string)$result, Metrology::LOG_LEVEL_DEBUG); // Log

        // Ecrit le cache.
        $this->_properties[$key] = $result;

        return $result;
    }

    /**
     * Lit une clé dans la monnaie définie retourne un texte avec la valeur.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string|null
     */
    protected function _getParamFromCurrency($key, $maxsize)
    {
        $return = null;

        // N'est pas utilisable pour une monnaie.
        if ($this->_inheritedCID === null
            || get_class($this) == 'Currency'
        ) {
            return null;
        }

        $this->_metrology->addLog(get_class($this) . ' get param on currency ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG); // Log

        $return = $this->_inheritedCID->getParam($key);

        $this->_propertiesForced[$key] = $this->_inheritedCID->getParamForced($key);

        if ($return == ''
            && !$this->_propertiesForced[$key]
        ) {
            $return = null;
        }

        if ($return === null) {
            $this->_propertiesInherited[$key] = false;
        } else {
            $this->_propertiesInherited[$key] = true;
            $this->_metrology->addLog(get_class($this) . ' return param inherited on currency ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG); // Log
        }

        $this->_metrology->addLog(get_class($this) . ' return param on currency ' . $this->_id . ' - ' . $key . ':' . (string)$return, Metrology::LOG_LEVEL_DEBUG); // Log
        return $return;
    }

    /**
     * Lit une clé dans le sac de jetons défini et retourne un texte avec la valeur.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string|null
     */
    protected function _getParamFromPool($key, $maxsize)
    {
        $return = null;

        // N'est pas utilisable pour une monnaie ou un sac de jetons.
        if ($this->_inheritedPID === null
            || get_class($this) == 'Currency'
            || get_class($this) == 'TokenPool'
        ) {
            return null;
        }

        $this->_metrology->addLog(get_class($this) . ' get param on pool ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG); // Log

        $return = $this->_inheritedPID->getParam($key);

        $this->_propertiesForced[$key] = $this->_inheritedPID->getParamForced($key);

        if ($return == ''
            && !$this->_propertiesForced[$key]
        ) {
            $return = null;
        }

        if ($return === null) {
            $this->_propertiesInherited[$key] = false;
        } else {
            $this->_propertiesInherited[$key] = true;
            $this->_metrology->addLog(get_class($this) . ' return param inherited on pool ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG); // Log
        }

        $this->_metrology->addLog(get_class($this) . ' return param on pool ' . $this->_id . ' - ' . $key . ':' . (string)$return, Metrology::LOG_LEVEL_DEBUG); // Log
        return $return;
    }

    /**
     * Lit une clé dans le contenu de l'objet et retourne un texte avec la valeur.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string|null
     */
    protected function _getParamFromObject($key, $maxsize)
    {
        $this->_metrology->addLog(get_class($this) . ' get param on object ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG); // Log

        $result = null;

        // Lit le contenu de l'objet.
        $maxLimit = $this->_nebuleInstance->getOption('ioReadMaxData');
        $content = $this->getContent($maxLimit);

        // Si l'objet monnaie a du contenu.
        if ($content != null) {
            // Extrait un tableau avec une ligne par élément.
            $contentArray = explode("\n", $content);
            unset($content);

            foreach ($contentArray as $i => $line) {
                $l = trim($line);

                // Si commentaire, passe à la ligne suivante.
                if (substr($l, 0, 1) == "#"
                    || ($key == 'TYP' && $i != 0)
                    || ($key == 'SID' && $i != 1)
                    || (get_class($this) == 'Currency' && $key == 'CAP' && $i != 2)
                    || (get_class($this) == 'Currency' && $key == 'MOD' && $i != 3)
                    || (get_class($this) == 'Currency' && $key == 'AID' && $i != 4)
                    || (get_class($this) == 'TokenPool' && $key == 'CID' && $i != 2)
                    || (get_class($this) == 'Token' && $key == 'CID' && $i != 2)
                    || (get_class($this) == 'Token' && $key == 'PID' && $i != 3)
                ) {
                    continue;
                }

                // Recherche l'option demandée.
                if (filter_var(trim(strtok($l, ':')), FILTER_SANITIZE_STRING) == $key) {
                    $result = trim(filter_var(trim(substr($l, strpos($l, ':') + 1)), FILTER_SANITIZE_STRING));
                    break;
                }
            }
            unset($contentArray, $line, $l);
        }

        // Ecrit l'état de forçage de la valeur pour la clé.
        if ($result === null) {
            $this->_propertiesForced[$key] = false;
        } else {
            $this->_propertiesForced[$key] = true;
            $this->_metrology->addLog(get_class($this) . ' return param forced on object ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG); // Log
        }

        $this->_metrology->addLog(get_class($this) . ' return param on object ' . $this->_id . ' - ' . $key . ':' . (string)$result, Metrology::LOG_LEVEL_DEBUG); // Log
        return $result;
    }

    /**
     * Lit une clé dans les liens de l'objet et retourne un texte avec la valeur.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string
     */
    protected function _getParamFromLinks($key, $maxsize)
    {
        $this->_metrology->addLog(get_class($this) . ' get param on links ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG); // Log

        $result = '';

        $id = '';
        $instance = null;

        $autorityList = array();

        // Vérifie que l'AID et/ou l'FID sont connus pour vérifier les liens.
        if (isset($this->_properties['AID'])) {
            $autorityList[] = $this->_properties['AID'];
        }
        if (isset($this->_properties['FID'])) {
            $autorityList[] = $this->_properties['FID']; // @todo à vérifier mais ce ne devrait pas être présent ici !?
        }

        $meta = '';
        foreach ($this->_propertiesList as $nameArray) {
            foreach ($nameArray as $name => $propArray) {
                if ($propArray['key'] == $key) {
                    $meta = $propArray['key'];
                    break 2;
                }
            }
        }

        $this->_metrology->addLog(get_class($this) . ' get param on links ' . $this->_id . ' - metatype:' . $meta, Metrology::LOG_LEVEL_DEBUG); // Log

        if ($meta != '') {
            $this->_social->setList($autorityList, 'onlist');
            $id = $this->getPropertyID($meta, 'onlist');
            //$id = $this->getPropertyID($meta, 'all');
            $this->_social->unsetList();
        }

        $this->_metrology->addLog(get_class($this) . ' get param on links ' . $this->_id . ' - meta:' . $id, Metrology::LOG_LEVEL_DEBUG); // Log

        if ($id != '') {
            $instance = $this->_nebuleInstance->newObject($id);
        }

        if (is_a($instance, 'Node')
            && $instance->getID() != '0'
        ) {
            // Extrait la valeur en fonction du type.
            foreach ($this->_propertiesList as $type => $nameArray) {
                // Si ce n'est pas le type d'objet attendu, continue.
                if (strtolower(get_class($this)) != $type) {
                    continue;
                }

                foreach ($nameArray as $name => $propArray) {
                    if ($key == $propArray['key']) {
                        if ($propArray['type'] == 'hexadecimal') {
                            $result = $instance->getID();
                        } else {
                            $result = $instance->getContent($maxsize);
                        }
                    }
                }
            }
        }

        unset($meta, $id, $instance);

        $this->_metrology->addLog(get_class($this) . ' return param on links ' . $this->_id . ' - ' . $key . ':' . (string)$result, Metrology::LOG_LEVEL_DEBUG); // Log
        if ($result == '') {
            return null;
        }
        return $result;
    }

    /**
     * Ecrit un paramètre en lien avec une monnaie ou dérivé.
     * Vérifie que le paramètre n'est pas en lecture seule, càd dans l'objet.
     * Le paramètre est enregistré sous forme d'un lien.
     *
     * @param unknown $key
     * @param unknown $value
     * @return boolean
     */
    protected function _setParam($key, $value)
    {
        // @todo

        return false;
    }

    /**
     * Lit une clé et retourne un texte avec la valeur.
     * La taille maximum dépend de la clé.
     *
     * @param string $key
     * @return string|null
     */
    public function getParam($key)
    {
        // @todo faire les vérifications des variables.

        // Détermine la taille maximum à lire.
        $maxsize = 0;
        foreach ($this->_propertiesList as $nameArray) {
            foreach ($nameArray as $name => $propArray) {
                if ($propArray['key'] == $key) {
                    if ($propArray['type'] == 'boolean') {
                        $maxsize = 5;
                        break 2;
                    } elseif ($propArray['type'] == 'number') {
                        $maxsize = 15;
                        break 2;
                    } elseif (isset($propArray['limit'])) {
                        $maxsize = (float)$propArray['limit'];
                        break 2;
                    }
                }
            }
        }

        if ($maxsize == 0) {
            return null;
        }

        return $this->_getParam($key, $maxsize);
    }

    /**
     * Lit si la valeur d'une clé est forçée par héritage.
     *
     * Pas d'héritage pour une monnaie mais c'est géré lors de la recherche de la clé.
     *
     * @param string $key
     * @return boolean
     */
    public function getParamInherited($key)
    {
        if (get_class($this) == 'Currency') {
            return false;
        }

        // Initalise les caches.
        if (!isset($this->_propertiesInherited[$key])) {
            $this->getParam($key);
        }

        // Retourne l'état d'héritage.
        if (isset($this->_propertiesInherited[$key])) {
            if ($this->_propertiesInherited[$key]) {
                $this->_metrology->addLog(get_class($this) . ' get param inherited ' . $this->_id . ' - ' . $key . ':true', Metrology::LOG_LEVEL_DEBUG); // Log
            } else {
                $this->_metrology->addLog(get_class($this) . ' get param inherited ' . $this->_id . ' - ' . $key . ':false', Metrology::LOG_LEVEL_DEBUG); // Log
            }
            return $this->_propertiesInherited[$key];
        } else {
            $this->_metrology->addLog(get_class($this) . ' get param inherited ' . $this->_id . ' - ' . $key . ':not defined (false)', Metrology::LOG_LEVEL_DEBUG); // Log
            return false;
        }
    }

    /**
     * Lit si la valeur d'une clé est forçée.
     *
     * @param string $key
     * @return boolean
     */
    public function getParamForced($key)
    {
        // Initalise les caches.

        if (!isset($this->_propertiesForced[$key])) {
            $this->getParam($key);
        }

        // Retourne l'état de forçage.
        if (isset($this->_propertiesForced[$key])) {
            if ($this->_propertiesForced[$key]) {
                $this->_metrology->addLog(get_class($this) . ' get param forced ' . $this->_id . ' - ' . $key . ':true', Metrology::LOG_LEVEL_DEBUG); // Log
            } else {
                $this->_metrology->addLog(get_class($this) . ' get param forced ' . $this->_id . ' - ' . $key . ':false', Metrology::LOG_LEVEL_DEBUG); // Log
            }
            return $this->_propertiesForced[$key];
        } else {
            $this->_metrology->addLog(get_class($this) . ' get param forced ' . $this->_id . ' - ' . $key . ':not defined (false)', Metrology::LOG_LEVEL_DEBUG); // Log
            return false;
        }
    }

    /**
     * Ecrit un paramètre en lien avec une monnaie ou dérivé.
     * Vérifie que le paramètre n'est pas en lecture seule, càd dans l'objet.
     * Le paramètre est enregistré sous forme d'un lien.
     * Un paramètre ne peut pas être ajouté à l'objet sans modifier son identifiant.
     *
     * @param unknown $key
     * @param unknown $value
     * @return boolean
     */
    public function setParam($key, $value)
    {
        // @todo faire les vérifications des variables.

        return $this->_setParam($key, $value);
    }

    /**
     * Définit la monnaie de référence utilisée.
     * N'est utilisé que pour les sacs de jetons et les jetons.
     *
     * @param $currency string|Currency
     * @return boolean
     */
    public function setCID($currency = '0')
    {
        if (is_string($currency)
            && $currency != ''
            && $currency != '0'
            && $this->_io->checkLinkPresent($currency)
        ) {
            $currency = new Currency($this->_nebuleInstance, $currency);
        }
        if (!get_class($currency) == 'Currency') {
            return false;
        }
        if (get_class($this) != 'TokenPool'
            && get_class($this) != 'Token'
        ) {
            return false;
        }
        if ($currency->getID() == '0') {
            return false;
        }

        // @todo vérifier que le CID est cohérent pour cet item dans les liens.

        $this->_metrology->addLog('set CID ' . $currency->getID() . ' for ' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log
        $this->_inheritedCID = $currency;

        // Ecrit le cache.
        $this->_properties['CID'] = $currency->getID();

        return true;
    }

    /**
     * Définit le sac de jetons de référence utilisé.
     * N'est utilisé que pour les jetons.
     *
     * @param $pool string|TokenPool
     * @return boolean
     */
    public function setPID($pool = '0')
    {
        if (is_string($pool)
            && $pool != ''
            && $pool != '0'
            && $this->_io->checkLinkPresent($pool)
        ) {
            $pool = new TokenPool($this->_nebuleInstance, $pool);
        }
        if (!get_class($pool) == 'TokenPool') {
            return false;
        }
        if (get_class($this) != 'Token') {
            return false;
        }
        if ($pool->getID() == '0') {
            return false;
        }

        // @todo vérifier que le PID est cohérent pour ce jeton dans les liens.

        $this->_metrology->addLog('set PID ' . $pool->getID() . ' for ' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log
        $this->_inheritedPID = $pool;

        // Ecrit le cache.
        $this->_properties['PID'] = $pool->getID();

        return true;
    }

    /**
     * Initialise l'AID, entité autorité de la monnaie.
     * Si l'instance est une monnaie, recherche le paramètre AID dans l'objet de la monnaie.
     *
     * @return boolean
     */
    public function setAID()
    {
        if (get_class($this) == 'Currency') {
            $id = $this->_getParamFromObject('AID', (int)$this->_propertiesList['currency']['CurrencyAutorityID']['limit']);
            $this->_metrology->addLog('set AID by param - AID:' . $id, Metrology::LOG_LEVEL_DEBUG); // Log
        } else {
            $id = $this->_getParamFromCurrency('AID', (int)$this->_propertiesList['currency']['CurrencyAutorityID']['limit']);
            $this->_metrology->addLog('set AID from currency - AID:' . $id, Metrology::LOG_LEVEL_DEBUG); // Log
        }

        // Vérifie l'ID.
        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
            && $this->_io->checkObjectPresent($id)
//				&& $this->_io->checkLinkPresent($id)
        ) {
            $this->_properties['AID'] = $id;
            $this->_metrology->addLog('set AID ' . $id . ' for ' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log
            return true;
        }
        $this->_metrology->addLog('error set AID for ' . $this->_id, Metrology::LOG_LEVEL_ERROR); // Log
        return false;
    }

    /**
     * Initialise l'FID, entité forge de la monnaie.
     * Si l'instance est une monnaie, recherche le paramètre FID et ne tient pas compte de l'argument de la fonction.
     *
     * @param unknown $id
     * @return boolean
     */
    public function setFID($id)
    {
        if (get_class($this) == 'Currency') {
            //$id = $this->_getParam('FID', (int)$this->_propertiesList['currency']['CurrencyForgeID']['limit']);
            $id = '';
        } elseif (get_class($this) == 'TokenPool') {
            $id = $this->_getParam('FID', (int)$this->_propertiesList['tokenpool']['PoolForgeID']['limit']);
        } elseif (get_class($this) == 'Token') {
            $id = $this->_getParam('FID', (int)$this->_propertiesList['token']['TokenForgeID']['limit']);
        }
        $this->_metrology->addLog('set FID by param', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie l'ID.
        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
            && $this->_io->checkObjectPresent($id)
//				&& $this->_io->checkLinkPresent($id)
        ) {
            $this->_properties['FID'] = $id;
            $this->_metrology->addLog('set FID ' . $id . ' for ' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log
            return true;
        }
        $this->_metrology->addLog('error set FID for ' . $this->_id, Metrology::LOG_LEVEL_ERROR); // Log
        return false;
    }

    /**
     * Crée un texte pseudo-aléatoire.
     * Retourne une chaine de caractères représentant de l'héxadécimale, c'est à dire une suite de chiffres de 0 à f.
     *
     * La fonction gère une graine afin d'améliorer la génératon successivement de l'aléa.
     *
     * @return string
     */
    protected function _getPseudoRandom()
    {
        // Définit l'algorithme de divergence.
        $algo = 'sha256';

        // Résultat à remplir.
        $result = '';

        // Si besoin, génère le compteur interne.
        if ($this->_seed == '') {
            $this->_seed = $this->_nebuleInstance->getCrypto()->getPseudoRandom(32);
            $this->_seed = hash($algo, $this->_seed);
        }

        // Fait évoluer le compteur interne.
        $this->_seed = hash($algo, $this->_seed);

        // Fait diverger le compteur interne pour la sortie.
        // La concaténation avec un texte empêche de remonter à la valeur du compteur interne.
        $result = hash($algo, $this->_seed . 'liberté égalité fraternité');

        return $result;
    }

    /**
     * Crée un lien.
     *
     * @return boolean
     */
    protected function _createLink($signer, $date, $action, $source, $target, $meta, $obfuscate = false)
    {
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);

        // Signe le lien.
        $newLink->sign($signer);

        // Si besoin, obfuscation du lien.
        if ($obfuscate
            && $this->_nebuleInstance->getOption('permitObfuscatedLink')
        ) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
    }

    /**
     * Extrait les capacités de la monnaie dans le champs CAP dans un tableau.
     * Ce tableau doit faciliter la vérification des champs à utiliser.
     *
     * @return void
     */
    private function _extractCAParray()
    {
        $this->_CAParray = array();

        if (!isset($this->_propertiesForced['CAP'])
            || sizeof($this->_propertiesForced['CAP']) == 0
        )
            return;

        $value = $this->_propertiesForced['CAP'];
        $subitems = explode(' ', $value);
        foreach ($subitems as $subitem)
            $this->_CAParray[$subitem] = $subitem;
    }

    /**
     * Retourne la liste des propriétés des objets monnaies et hérités.
     *
     * @return array
     */
    public function getPropertiesList()
    {
        return $this->_propertiesList;
    }


    /**
     * Extrait la valeur relative de la monnaie à un instant donné.
     *
     * @param $date string
     * @return double
     */
    public function getRelativeValue($date)
    {

        // Récupère la liste des jetons.
        $items = $this->_getItemList('CID');

        $total = 0;

        foreach ($items as $item) {
            $instance = $this->_nebuleInstance->newTokenPool($item);
            if ($instance->getID() != '0') {
                $total += $instance->getRelativeValue($date);
            }
        }

        return $total;
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#om">OM / Monnaie</a>
            <ul>
                <li><a href="#omf">OMF / Fonctionnement</a></li>
                <li><a href="#omn">OMN / Nommage</a></li>
                <li><a href="#omp">OMP / Protection</a></li>
                <li><a href="#omd">OMD / Dissimulation</a></li>
                <li><a href="#oml">OML / Liens</a></li>
                <li><a href="#omv">OMV / Valeur</a></li>
                <li><a href="#omc">OMC / Création</a>
                    <ul>
                        <li><a href="#omcl">OMCL / Liens</a></li>
                        <li><a href="#omcp">OMCP / Propriétés</a>
                            <ul>
                                <li><a href="#omcph">OMCPH / Héritage</a></li>
                                <li><a href="#omcphct">OMCPHCT / HCT</a></li>
                                <li><a href="#omcptyp">OMCPTYP / TYP</a></li>
                                <li><a href="#omcpsid">OMCPSID / SID</a></li>
                                <li><a href="#omcpcap">OMCPCAP / CAP</a></li>
                                <li><a href="#omcpmod">OMCPMOD / MOD</a></li>
                                <li><a href="#omcpaid">OMCPAID / AID</a></li>
                                <li><a href="#omcpmid">OMCPMID / MID</a></li>
                                <li><a href="#omcpfid">OMCPFID / FID</a></li>
                                <li><a href="#omcpcid">OMCPCID / CID</a></li>
                                <li><a href="#omcpnam">OMCPNAM / NAM</a></li>
                                <li><a href="#omcpuni">OMCPUNI / UNI</a></li>
                                <li><a href="#omcpdta">OMCPDTA / DTA</a></li>
                                <li><a href="#omcpdtc">OMCPDTC / DTC</a></li>
                                <li><a href="#omcpdtd">OMCPDTD / DTD</a></li>
                                <li><a href="#omcpcom">OMCPCOM / COM</a></li>
                                <li><a href="#omcpcpr">OMCPCPR / CPR</a></li>
                                <li><a href="#omcpidm">OMCPIDM / IDM</a></li>
                                <li><a href="#omcpidr">OMCPIDR / IDR</a></li>
                                <li><a href="#omcpidp">OMCPIDP / IDP</a></li>
                                <li><a href="#omcpvmd">OMCPVMD / VMD</a></li>
                                <li><a href="#omcpvid">OMCPVID / VID</a></li>
                                <li><a href="#omcptrs">OMCPTRS / TRS</a></li>
                                <li><a href="#omcppcn">OMCPPCN / PCN</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#oms">OMS / Stockage</a></li>
                <li><a href="#omt">OMT / Transfert</a></li>
                <li><a href="#omr">OMR / Réservation</a></li>
                <li><a href="#omio">OMIO / Implémentation des Options</a></li>
                <li><a href="#omia">OMIA / Implémentation des Actions</a></li>
                <li><a href="#omo">OMO / Oubli</a></li>
                <li><a href="#omg">OMG / Sac de jetons</a>
                    <ul>
                        <li><a href="#omgf">OMGF / Fonctionnement</a></li>
                        <li><a href="#omgn">OMGN / Nommage</a></li>
                        <li><a href="#omgp">OMGP / Protection</a></li>
                        <li><a href="#omgd">OMGD / Dissimulation</a></li>
                        <li><a href="#omgl">OMGL / Liens</a></li>
                        <li><a href="#omgv">OMGV / Valeur</a></li>
                        <li><a href="#omgc">OMGC / Création</a>
                            <ul>
                                <li><a href="#omgcl">OMGCL / Liens</a></li>
                                <li><a href="#omgcp">OMGCP / Propriétés</a>
                                    <ul>
                                        <li><a href="#omgcph">OMGCPH / Héritage</a></li>
                                        <li><a href="#omgcphct">OMGCPHCT / HCT</a></li>
                                        <li><a href="#omgcptyp">OMGCPTYP / TYP</a></li>
                                        <li><a href="#omgcpsid">OMGCPSID / SID</a></li>
                                        <li><a href="#omgcpcap">OMGCPCAP / CAP</a></li>
                                        <li><a href="#omgcpfid">OMGCPFID / FID</a></li>
                                        <li><a href="#omgcpmid">OMGCPMID / MID</a></li>
                                        <li><a href="#omgcppid">OMGCPPID / PID</a></li>
                                        <li><a href="#omgcpcid">OMGCPCID / CID</a></li>
                                        <li><a href="#omgcpdta">OMGCPDTA / DTA</a></li>
                                        <li><a href="#omgcpdtc">OMGCPDTC / DTC</a></li>
                                        <li><a href="#omgcpdtd">OMGCPDTD / DTD</a></li>
                                        <li><a href="#omgcpcom">OMGCPCOM / COM</a></li>
                                        <li><a href="#omgcpcpr">OMGCPCPR / CPR</a></li>
                                        <li><a href="#omgcpidm">OMGCPIDM / IDM</a></li>
                                        <li><a href="#omgcpidr">OMGCPIDR / IDR</a></li>
                                        <li><a href="#omgcpidp">OMGCPIDP / IDP</a></li>
                                        <li><a href="#omgcptcn">OMGCPTCN / TCN</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#omgs">OMGS / Stockage</a></li>
                        <li><a href="#omgt">OMGT / Transfert</a></li>
                        <li><a href="#omgr">OMGR / Réservation</a></li>
                        <li><a href="#omgo">OMGO / Oubli</a></li>
                    </ul>
                </li>
                <li><a href="#omj">OMJ / Jeton</a>
                    <ul>
                        <li><a href="#omjf">OMJF / Fonctionnement</a></li>
                        <li><a href="#omjn">OMJN / Nommage</a></li>
                        <li><a href="#omjp">OMJP / Protection</a></li>
                        <li><a href="#omjd">OMJD / Dissimulation</a></li>
                        <li><a href="#omjl">OMJL / Liens</a></li>
                        <li><a href="#omjv">OMJV / Valeur</a></li>
                        <li><a href="#omjc">OMJC / Création</a>
                            <ul>
                                <li><a href="#omjcl">OMJCL / Liens</a></li>
                                <li><a href="#omjcp">OMJCP / Propriétés</a>
                                    <ul>
                                        <li><a href="#omjcph">OMJCPH / Héritage</a></li>
                                        <li><a href="#omjcphct">OMJCPHCT / HCT</a></li>
                                        <li><a href="#omjcptyp">OMJCPTYP / TYP</a></li>
                                        <li><a href="#omjcpsid">OMJCPSID / SID</a></li>
                                        <li><a href="#omjcpcap">OMJCPCAP / CAP</a></li>
                                        <li><a href="#omjcpcid">OMJCPCID / CID</a></li>
                                        <li><a href="#omjcppid">OMJCPPID / PID</a></li>
                                        <li><a href="#omjcptid">OMJCPTID / TID</a></li>
                                        <li><a href="#omjcpfid">OMJCPFID / FID</a></li>
                                        <li><a href="#omjcpbid">OMJCPBID / BID</a></li>
                                        <li><a href="#omjcpnam">OMJCPNAM / NAM</a></li>
                                        <li><a href="#omjcpuni">OMJCPUNI / UNI</a></li>
                                        <li><a href="#omjcpval">OMJCPVAL / VAL</a></li>
                                        <li><a href="#omjcpdta">OMJCPDTA / DTA</a></li>
                                        <li><a href="#omjcpdtc">OMJCPDTC / DTC</a></li>
                                        <li><a href="#omjcpdtd">OMJCPDTD / DTD</a></li>
                                        <li><a href="#omjcpcom">OMJCPCOM / COM</a></li>
                                        <li><a href="#omjcpcpr">OMJCPCPR / CPR</a></li>
                                        <li><a href="#omjcpidm">OMJCPIDM / IDM</a></li>
                                        <li><a href="#omjcpidr">OMJCPIDR / IDR</a></li>
                                        <li><a href="#omjcpidp">OMJCPIDP / IDP</a></li>
                                        <li><a href="#omjcpsvc">OMJCPSVC / SVC</a></li>
                                        <li><a href="#omjcpclb">OMJCPCLB / CLB</a></li>
                                        <li><a href="#omjcpcld">OMJCPCLD / CLD</a></li>
                                        <li><a href="#omjcptrs">OMJCPTRS / TRS</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#omjs">OMJS / Stockage</a></li>
                        <li><a href="#omjt">OMJT / Transfert</a>
                            <ul>
                                <li><a href="#omjti">OMJCTI / Transfert d'information</a></li>
                                <li><a href="#omjtv">OMJCTV / Transfert de valeur</a></li>
                            </ul>
                        </li>
                        <li><a href="#omjm">OMJM / Modes de transfert</a>
                            <ul>
                                <li><a href="#omjmlns">OMJMLNS / Mode LNS</a></li>
                            </ul>
                        </li>
                        <li><a href="#omjr">OMJR / Réservation</a></li>
                        <li><a href="#omjo">OMJO / Oubli</a></li>
                    </ul>
                </li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h2 id="om">OM / Monnaie</h2>
        <p>Certains objets permettre de mettre en place et de gérer plusieurs types de monnaies et plusieurs monnaies
            concurrentes.</p>

        <h3 id="omf">OMF / Fonctionnement</h3>
        <p>Une monnaie est un objet de référence qui va gérer des sac de jetons. La gestion se fait par différentes
            entités détenant des rôles spécifiques aux monnaies.</p>
        <p>Une monnaie va disposer de plusieurs propriétés connues par leurs abréviations, voir <a href="#omcp">OMCP</a>.
        </p>
        <p>L'objet de référence de la monnaie peut être virtuel ou non. Aujourd'hui le code ne gère que des monnaies
            avec un objet de référence non virtuel.</p>
        <p>Si l'objet de référence de la monnaie est virtuel, il est forçément généré aléatoirement. Sinon il dépend du
            contenu de l'objet et est rendu unique grâce à la propriété <code>SID</code>.</p>
        <p>Exemple d'objet de monnaie :</p>
        <pre>
TYP:currency
SID:5f3ad5265bb3306b3266e1935d067d9ec15965d0a970554bc6161eb3328907a9
CAP:TYP MOD SID CAP AID MID NAM UNI DTA DTC DTD COM CPR IDM IDR IDP VMD VID TRS CID PID FID BID VAL CLB CLD SVC TID
MOD:CTL
AID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
MID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
NAM:poux
UNI:pou
CPR:(c) nebule/qantion 2020
</pre>

        <h3 id="omn">OMN / Nommage</h3>
        <p>Une monnaie peut disposer d'un nom complet. Ce nom est définit par la propriété <code>NAM</code> et est
            doublé par un lien de nommage classique comme tout objet.</p>
        <p>Une monnaie peut aussi disposer d'une abréviation définit par la propriété <code>UNI</code>.</p>
        <p>Le nom complet d'un objet de type monnaie est uniquement extrait de la propriété <code>NAM</code>. Dans
            certains cas il peut êrte formé de <code>NAM(UNI)</code> mais la propriété <code>UNI</code> a plutôt
            vocation a être utilisée dans un affichage condensé.</p>

        <h3 id="omp">OMP / Protection</h3>
        <p>A faire...</p>

        <h3 id="omd">OMD / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="oml">OML / Liens</h3>
        <p>A faire...</p>

        <h3 id="omv">OMV / Valeur</h3>
        <p>La valeur de la monnaie, à un instant donné, est égale à la somme des sac de jetons de la monnaie au même
            instant.</p>

        <h3 id="omc">OMC / Création</h3>
        <p>A faire...</p>

        <h4 id="omcl">OMCL / Liens</h4>
        <p>Liste des liens à générer lors de la création d'une monnaie.</p>
        <p>A faire...</p>

        <h4 id="omcp">OMCP / Propriétés</h4>
        <p></p>

        <h5 id="omcph">OMCPH / Héritage</h5>
        <p>Certaines propriétés des sacs de jetons et jetons sont héritées de la monnaie, si ces propriétés sont
            définies dans la monnaie. Les héritages sont prioritaires sur les propriétés définies via l'objet et les
            liens des sacs de jetons et jetons.</p>

        <h5 id="omcphct">OMCPHCT / HCT</h5>
        <p>Définit si l'objet de la monnaie a un contenu. Si il n'a pas de contenu l'objet de la monnaie est virtuel et
            correspond à son SID, et les paramètres de la monnaie ne peuvent pas être forcés.</p>
        <p>Ce n'est pas écrit dans l'objet de la monnaie ni enregistré via des liens. Cela sert uniquement au moment de
            la création d'une monnaie.</p>

        <h5 id="omcptyp">OMCPTYP / TYP</h5>
        <p>Le type de monnaie.</p>
        <p>Toujours à la valeur <i>cryptocurrency</i>.</p>
        <p>Présence obligatoire en ligne 1 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpsid">OMCPSID / SID</h5>
        <p>Le numéro de série identifiant de la monnaie (<i>serial</i>).</p>
        <p>Si l'objet de référence de la monnaie est virtuel, l'identifiant de la monnaie <code>CID</code> sera le
            <code>SID</code>.</p>
        <p>Si l'objet de référence de la monnaie n'est pas virtuel, l'identifiant de la monnaie <code>CID</code> dépend
            du contenu de l'objet et est rendu unique grâce à la propriété <code>SID</code>.</p>
        <p>La valeur est de préférence aléatoire mais peut être un compteur à condition d'être unique. L’utilisation
            d’un compteur de faible valeur est fortement déconseillée.</p>
        <p>Si aléatoire, la génération pseudo aléatoire du <code>SID</code> est faite en partant d’un dérivé de la date
            avec quelques valeurs locales. Il n’y a pas de contrainte de sécurité sur cette valeur. Puis une boucle
            interne génère un bon aléa au fur et à mesure de la génération des jetons via une fonction de hash. Le tout
            ne consomme pas du tout de précieux aléa de bonne qualité.</p>
        <p>Présence obligatoire en ligne 2 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpcap">OMCPCAP / CAP</h5>
        <p>Liste des capacités connues de la monnaie.</p>
        <p>Si une capacité n'est pas présente elle ne peut être invoquée, même si elle est forcée.</p>
        <p>Présence obligatoire en ligne 3 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpmod">OMCPMOP / MOP</h5>
        <p>Définit le mode d'exploitation de la monnaie.</p>
        <p>Si une capacité n'est pas présente elle ne peut être invoquée, même si elle est forcée.</p>
        <p>Présence obligatoire en ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpaid">OMCPAID / AID</h5>
        <p>Identifiant de l'entité authorité de la monnaie (<i>autority</i>).</p>
        <p>C'est l'entité qui forge la monnaie et délègue la gestion aux entités gestionnaires (MID).</p>
        <p>Présence obligatoire en ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpmid">OMCPMID / MID</h5>
        <p>Identifiant d'une entité de gestion de la monnaie (<i>manage</i>).</p>
        <p>Une monnaie peut avoir plusieurs entités de gestion.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpfid">OMCPFID / FID</h5>
        <p>Non utilisé !!!</p>
        <p>Identifiant de l'entité ayant forgé la monnaie (<i>forge</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpcid">OMCPCID / CID</h5>
        <p>Identifiant de l’objet de la monnaie (<i>currency</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème. Ce qui va faire la différence c'est l'autorité et ses liens.</p>
        <p>L'objet d'une monnaie ne peut en aucun cas contenir son propre identifiant <code>CID</code>.</p>

        <h5 id="omcpnam">OMCPNAM / NAM</h5>
        <p>Le nom de la monnaie. Limité à 256 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpuni">OMCPUNI / UNI</h5>
        <p>Le nom de l'unité de la monnaie en 3 lettres maximum. Pas de chiffre.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpdta">OMCPDTA / DTA</h5>
        <p>Identifiant de l’entité autorité de temps pour les limites de temps.</p>
        <p>La gestion du temps avec une autorité de temps permet de prendre en compte sérieusement les suppression
            programmées de jeton (<code>DTC</code>/<code>DTD</code>) ainsi que leur inflation/déflation automatique
            (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>).</p>
        <p>L’autorité de temps peut être spécifique pour chaque jeton mais il est plus logique qu’elle soit commune à
            une monnaie ou dans certains cas à un sac de jetons.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpdtc">OMCPDTC / DTC</h5>
        <p>Date de création de la monnaie.</p>
        <p>Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpdtd">OMCPDTD / DTD</h5>
        <p>Date de suppression programmée de la monnaie.</p>
        <p> Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date pour être
            fonctionel.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpcom">OMCPCOM / COM</h5>
        <p>Commentaire texte libre limité à 4096 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpcpr">OMCPCPR / CPR</h5>
        <p>Licence du jeton sous forme d’une texte libre limité à 1024 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpidm">OMCPIDM / IDM</h5>
        <p>Mode de fonctionnement du mécanisme d’inflation/déflation (<i>mode</i>) de tous les jetons de la monnaie.</p>
        <p>Les modes sont <i>creation</i> ou <i>transaction</i> ou <i>disabled</i>.</p>
        <p>Suivant le mode, le mécanisme tient compte du temps passé depuis la dernière transaction ou depuis l’émission
            du jeton.</p>
        <p>Le mécanisme d’inflation/déflation (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>), si activé, avec un
            taux de variation inférieur à 1, donc en déflation, permet de forcer les détenteurs de jeton à les utiliser
            plutôt que de les stocker. Les jetons concernés vont donc perdre de la valeur par rapport aux nouveaux
            jetons ou par rapport à ceux qui circulent.</p>
        <p>Si activé, avec un taux de variation suppérieur à 1, donc en inflation, permet de favoriser la conservation
            des jetons et valorise les vieux jetons sur les nouveaux ou ceux qui circulent beaucoup.</p>
        <p>En ne forçant pas cette propriété dans l'objet des jetons, il est possible d'avoir un taux de variation
            fluctuant en fonction des besoins. En le positionnant forçé à <i>disabled</i> cela désactive définitivement
            ce mécanisme au niveau de la monnaie et donc pour tous les jetons.</p>
        <p>Un jeton peut se déprécier avec le temps mais une entité peut demander à l’autorité émettrice de la monnaie
            un échange de jeton ancien contre un jeton plus jeune, si l’autorité émettrice le permet.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>CF <a href="#omgcpidm">P/IDM</a> et <a href="#omjcpidm">T/IDM</a>.</p>

        <h5 id="omcpidr">OMCPIDR / IDR</h5>
        <p>Taux de variation du mécanisme d’inflation/déflation (<i>rate</i>) de tous les jetons de la monnaie.</p>
        <p>Égal à 1 (un), taux constant donc pas de variation.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpidp">OMCPIDP / IDP</h5>
        <p>Périodicité d’application du taux de variation du mécanisme d’inflation/déflation (<i>period</i>) de tous les
            jetons de la monnaie.</p>
        <p>Unité exprimée en minutes.</p>
        <p>Si à 0 (zéro), la période n'est pas utilisé, donc la variation est non effective.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpvmd">OMCPVMD / VMD</h5>
        <p>Définit le mode de validation des transactions de jetons de la monnaie. C'est le mode de fonctionnement
            global de la monnaie.</p>
        <p>Actuellement seul est supporté le mode centralisé.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpvid">OMCPVID / VID</h5>
        <p>Dans le mode de validation centralisé, c'est l'entité de validation des transactions de jetons de la
            monnaie.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcptrs">OMCPTRS / TRS</h5>
        <p>Liste des méthodes de transaction supportées.</p>
        <p>Le code <code>LNS</code> désigne la méthode de base avec un lien (L) matérialisant une transaction et
            imposant un jeton non sécable (NS).</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcppcn">OMCPPCN / PCN</h5>
        <p>Non utilisé !!!</p>
        <p>Définit le nombre de sacs de jetons à créer avec la monnaie. Ce nombre multiplié par le <a href="#omgcptcn">TCN</a>
            donne le nombre total de jetons créés pour la monnaie.</p>
        <p>Ce n'est pas écrit dans l'objet de la monnaie, ni dans les sacs de jetons ni enregistré via des liens. Cela
            sert uniquement au moment de la création de la monnaie. Cependant un lien de rattachement sera créé pour
            chaque sac de jeton depuis la monnaie avec en meta le <a href="#omgcppid"></a>PID</a>.</p>

        <h3 id="oms">OMS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="omt">OMT/ Transfert</h3>
        <p>A faire...</p>

        <h3 id="omr">OMR / Réservation</h3>
        <p>Les objets réservés spécifiquement pour les monnaies :</p>
        <ul>
            <li>nebule/objet/monnaie</li>
        </ul>

        <h4 id="omio">OMIO / Implémentation des Options</h4>
        <p>A faire...</p>

        <h4 id="omia">OMIA / Implémentation des Actions</h4>
        <p>A faire...</p>

        <h3 id="omo">OMO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>


        <h3 id="omg">OMG / Sac de jetons</h3>
        <p>A faire...</p>

        <h3 id="omgf">OMGF / Fonctionnement</h3>
        <p>A faire...</p>
        <p>Exemple d'objet de sac de jetons :</p>
        <pre>
TYP:tokenpool
SID:5f3ad5265bb3306b3266e1935d067d9ec15965d0a970554bc6161eb3328907a9
CAP:TYP MOD SID CAP AID MID NAM UNI DTA DTC DTD COM CPR IDM IDR IDP VMD VID TRS CID PID FID BID VAL CLB CLD SVC TID
CID:daf832e3042cc849efcd5b6531df835a9c5f6251b2101e20972f9a9db2a8ae24
FID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
MID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
</pre>
        <p>Un sac de jetons va disposer de plusieurs propriétés connues par leurs abréviations, voir <a href="#omgcp">OMGCP</a>.
        </p>
        <p>A faire...</p>

        <h3 id="omgn">OMGN / Nommage</h3>
        <p>Un sac de jetons hérite du nommage de la monnaie à laquelle il est rattaché.</p>
        <p>Le nom complet d'un objet de type sac de jetons est uniquement extrait de la propriété <code>NAM</code>. Dans
            certains cas il peut êrte formé de <code>NAM(UNI)</code> mais la propriété <code>UNI</code> a plutôt
            vocation a être utilisée dans un affichage condensé.</p>

        <h3 id="omgp">OMGP / Protection</h3>
        <p>A faire...</p>

        <h3 id="omgd">OMGD / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="omgl">OMGL / Liens</h3>
        <p>A faire...</p>

        <h3 id="omgv">OMGV / Valeur</h3>
        <p>La valeur du sac de jeton, à un instant donné, est égale à la somme des jetons du sac au même instant.</p>

        <h3 id="omgc">OMGC / Création</h3>
        <p>A faire...</p>

        <h4 id="omgcl">OMGCL / Liens</h4>
        <p>Liste des liens à générer lors de la création d'un sac de jetons.</p>
        <p>A faire...</p>

        <h4 id="omgcp">OMGCP / Propriétés</h4>
        <p></p>

        <h5 id="omgcph">OMGCPH / Héritage</h5>
        <p>Certaines propriétés sont héritées de la monnaie, si ces propriétés sont définies dans la monnaie. Ce doit
            être la monnaie déclarée en cours d'utilisation et le sac de jetons doit dépendre de cette monnaie
            directement. Les héritages sont prioritaires sur les propriétés définies via l'objet et les liens.</p>

        <h5 id="omgcphct">OMGCPHCT / HCT</h5>
        <p>Définit si l'objet du sac de jetons a un contenu. Si il n'a pas de contenu l'objet du sac de jetons est
            virtuel et correspond à son SID, et les paramètres du sac de jetons ne peuvent pas être forcés.</p>
        <p>Ce n'est pas écrit dans l'objet du sac de jetons ni enregistré via des liens. Cela sert uniquement au moment
            de la création d'un sac de jetons.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcptyp">OMGCPTYP / TYP</h5>
        <p>Le type de sac de jetons.</p>
        <p>Toujours à la valeur <i>tokenpool</i>.</p>
        <p>Présence obligatoire en ligne 1 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpsid">OMGCPSID / SID</h5>
        <p>Le numéro de série identifiant du sac de jetons (<i>serial</i>).</p>
        <p>Si l'objet de référence du sac de jetons est virtuel, l'identifiant du sac de jetons <code>PID</code> sera le
            <code>SID</code>.</p>
        <p>Si l'objet de référence du sac de jetons n'est pas virtuel, l'identifiant du sac de jetons <code>PID</code>
            dépend du contenu de l'objet et est rendu unique grâce à la propriété <code>SID</code>.</p>
        <p>La valeur est de préférence aléatoire mais peut être un compteur à condition d'être unique. L’utilisation
            d’un compteur de faible valeur est fortement déconseillée.</p>
        <p>Si aléatoire, la génération pseudo aléatoire du <code>SID</code> est faite en partant d’un dérivé de la date
            avec quelques valeurs locales. Il n’y a pas de contrainte de sécurité sur cette valeur. Puis une boucle
            interne génère un bon aléa au fur et à mesure de la génération des jetons via une fonction de hash. Le tout
            ne consomme pas du tout de précieux aléa de bonne qualité.</p>
        <p>Présence obligatoire en ligne 2 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpcap">OMGCPCAP / CAP</h5>
        <p>Liste des capacités connues du sac de jetons.</p>
        <p>Si une capacité n'est pas présente elle ne peut être invoquée, même si elle est forcée.</p>
        <p>Présence obligatoire en ligne 3 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpcid">OMGCPCID / CID</h5>
        <p>Identifiant de l’objet de la monnaie auquel est rattaché le sac de jetons (<i>currency</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence obligatoire en ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpfid">OMGCPFID / FID</h5>
        <p>Identifiant de l'entité ayant forgé le sac de jetons (<i>forge</i>).</p>
        <p>L'entité forge doit désigner une entité de gestion, par défaut c'est elle-même.</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpmid">OMGCPMID / MID</h5>
        <p>Identifiant de l'entité de gestion du sac de jetons (<i>manage</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcppid">OMGCPPID / PID</h5>
        <p>Identifiant de l’objet du sac de jetons (<i>pool</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>L'objet d'un sac de jetons ne peut en aucun cas contenir son propre identifiant <code>PID</code>.</p>

        <h5 id="omgcpdta">OMGCPDTA / DTA</h5>
        <p>Identifiant de l’entité autorité de temps pour les limites de temps.</p>
        <p>La gestion du temps avec une autorité de temps permet de prendre en compte sérieusement les suppression
            programmées de jeton (<code>DTC</code>/<code>DTD</code>) ainsi que leur inflation/déflation automatique
            (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>).</p>
        <p>L’autorité de temps peut être spécifique pour chaque jeton mais il est plus logique qu’elle soit commune à
            une monnaie ou dans certains cas à un sac de jetons.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpdtc">OMGCPDTC / DTC</h5>
        <p>Date de création du sac de jetons.</p>
        <p>Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpdtd">OMGCPDTD / DTD</h5>
        <p>Date de suppression programmée du sac de jetons.</p>
        <p> Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date pour être
            fonctionel.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpcom">OMGCPCOM / COM</h5>
        <p>Commentaire texte libre limité à 4096 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpcpr">OMGCPCPR / CPR</h5>
        <p>Licence du jeton sous forme d’une texte libre limité à 1024 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpidm">OMGCPIDM / IDM</h5>
        <p>Mode de fonctionnement du mécanisme d’inflation/déflation (<i>mode</i>) des jetons dépendants du sac de
            jetons.</p>
        <p>Les modes sont <i>creation</i> ou <i>transaction</i> ou <i>disabled</i>.</p>
        <p>Suivant le mode, le mécanisme tient compte du temps passé depuis la dernière transaction ou depuis l’émission
            du jeton.</p>
        <p>Le mécanisme d’inflation/déflation (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>), si activé, avec un
            taux de variation inférieur à 1, donc en déflation, permet de forcer les détenteurs de jeton à les utiliser
            plutôt que de les stocker. Les jetons concernés vont donc perdre de la valeur par rapport aux nouveaux
            jetons ou par rapport à ceux qui circulent.</p>
        <p>Si activé, avec un taux de variation suppérieur à 1, donc en inflation, permet de favoriser la conservation
            des jetons et valorise les vieux jetons sur les nouveaux ou ceux qui circulent beaucoup.</p>
        <p>En ne forçant pas cette propriété dans l'objet des jetons, il est possible d'avoir un taux de variation
            fluctuant en fonction des besoins. En le positionnant forçé à <i>disabled</i> cela désactive définitivement
            ce mécanisme au niveau du sac de jetons et de tous les jetons en dépendant.</p>
        <p>Un jeton peut se déprécier avec le temps mais une entité peut demander à l’autorité émettrice de la monnaie
            un échange de jeton ancien contre un jeton plus jeune, si l’autorité émettrice le permet.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>CF <a href="#omcpidm">C/IDM</a>.</p>

        <h5 id="omgcpidr">OMGCPIDR / IDR</h5>
        <p>Taux de variation du mécanisme d’inflation/déflation (<i>rate</i>) des jetons dépendants du sac de jetons.
        </p>
        <p>Égal à 1 (un), taux constant donc pas de variation.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpidp">OMGCPIDP / IDP</h5>
        <p>Périodicité d’application du taux de variation du mécanisme d’inflation/déflation (<i>period</i>) des jetons
            dépendants du sac de jetons.</p>
        <p>Unité exprimée en minutes.</p>
        <p>Si à 0 (zéro), la période n'est pas utilisé, donc la variation est non effective.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcptcn">OMGCPTCN / TCN</h5>
        <p>Non utilisé !!!</p>
        <p>Définit le nombre de jetons à créer dans le pool ou par pools créés (cf <a href="#omcppcn">PCN</a>).</p>
        <p>Ce n'est pas écrit dans l'objet du sac de jetons ni enregistré via des liens. Cela sert uniquement au moment
            de la création d'un sac de jetons. Cependant un lien de rattachement sera créé pour chaque jeton depuis le
            sac de jeton avec en meta le <a href="#omjcptid"></a>TID</a>.</p>

        <h3 id="omgs">OMGS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="omgt">OMGT/ Transfert</h3>
        <p>A faire...</p>

        <h3 id="omgr">OMGR / Réservation</h3>
        <p>Les objets réservés spécifiquement pour les sacs de jetons :</p>
        <ul>
            <li>nebule/objet/monnaie/sac</li>
        </ul>

        <h3 id="omgo">OMGO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>


        <h3 id="omj">OMJ / Jeton</h3>
        <p>A faire...</p>

        <h3 id="omjf">OMJF / Fonctionnement</h3>
        <p>Un jeton est un objet de référence qui va servir de support de transmission de valeur. Il est attaché à un ou
            plusieurs sacs de jetons. Sa gestion se fait dans des monnaies par l'intermédiaire de sacs de jetons
            attachés aux monnaies.</p>
        <p>Le jeton plus simple est un objet virtuel dont l’identifiant (<code>TID</code>) est généré aléatoirement. Ce
            peut être un simple compteur aussi mais chaque identifiant doit être unique par monnaie, et donc par sac de
            jetons aussi. L’utilisation d’un compteur de faible valeur est fortement déconseillé pour le
            <code>TID</code>. Par exemple :</p>
        <code>4d831b11bbf828b9cfd4752223bb8918cbd634c4b858691736afd8b34f1f0c62</code>
        <p>La deuxième forme de jeton est donc un objet dont le contenu va donner par son empreinte cryptographique un
            identifiant de jeton unique (<code>TID</code>). Il n’est dans ce cas pas possible d’avoir un compteur
            puisque les valeurs de identifiant sont assimilées à des valeurs aléatoires.</p>
        <p>Exemple d'objet de jeton :</p>
        <pre>
TYP:cryptoken
SID:5f3ad5265bb3306b3266e1935d067d9ec15965d0a970554bc6161eb3328907a9
CAP:TYP MOD SID CAP AID MID NAM UNI DTA DTC DTD COM CPR IDM IDR IDP VMD VID TRS CID PID FID BID VAL CLB CLD SVC TID
CID:daf832e3042cc849efcd5b6531df835a9c5f6251b2101e20972f9a9db2a8ae24
PID:37aa32a2cec224ae908226eb1c600fbeacd5faf1f84b2e292c0be808c0296333
FID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
NAM:poux
UNI:pou
VAL:100
</pre>
        <p>Un jeton va disposer de plusieurs propriétés connues par leurs abréviations, voir <a href="#omjcp">OMJCP</a>.
        </p>

        <h3 id="omjn">OMJN / Nommage</h3>
        <p>Un jeton hérite du nommage de la monnaie via le sac de jeton auquel il est rattaché.</p>
        <p>Le nom complet d'un objet de type jeton est uniquement extrait de la propriété <code>NAM</code>. Dans
            certains cas il peut êrte formé de <code>NAM(UNI)</code> mais la propriété <code>UNI</code> a plutôt
            vocation a être utilisée dans un affichage condensé avec une valeur.</p>

        <h3 id="omjp">OMJP / Protection</h3>
        <p>A faire...</p>

        <h3 id="omjd">OMJD / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="omjl">OMJL / Liens</h3>
        <p>A faire...</p>

        <h3 id="omjv">OMJV / Valeur</h3>
        <p>C’est une valeur calculée strictement numérique du jeton à un instant donné par rapport à une monnaie. Elle
            s'appelle la valeur relative du jeton.</p>
        <p>Par défaut, si <code>VAL</code> non défini, la valeur de <code>VAL</code> est à interpréter comme équivalente
            à 1 (un).</p>
        <p>Ce n'est pas écrit dans l'objet du jeton ni enregistré via des liens. La valeur relative du jeton est
            recalculée à chaque usage du jeton en fonction des paramêtres <code>DTA</code>, <code>DTC</code>,
            <code>DTD</code>, <code>IDM</code>, <code>IDR</code>, <code>IDP</code>, <code>CLB</code> et <code>CLD</code>.
        </p>
        <p>Si le jeton est désactivé, sa valeur relative est nulle.</p>
        <p>A faire... le détail des calculs de la valeur relative en fonction de chaque propriétés citées.</p>

        <h3 id="omjc">OMJC / Création</h3>
        <p>Si l'objet de référence du jeton est virtuel, il est forçément généré aléatoirement. Sinon il dépend du
            contenu de l'objet et est rendu unique grâce à la propriété <a href="#omjcpsid">SID</a>.</p>
        <p>Un jeton va disposer de plusieurs propriétés connues par leurs abréviations.</p>
        <p>Le contenu des objets des jetons va recevoir plusieurs lignes de type <code>clé:valeur</code>. Chaque ligne
            débute par trois lettre en majuscules définissant le sens sémantique (<i>clé</i>) de la ligne, suivi d’un
            deux-points ( <code>:</code> ) et de la valeur associée. La valeur est un texte en minuscule sans caractères
            spéciaux, l’espace des caractères est limité aux lettres minuscules, aux chiffres, à l’espace (sauf au début
            et à la fin), au point, à la virgule et à l’égal. La valeur est par défaut limité en taille à 1024
            caractères sauf mention contraire pour une propriété. Il ne doit pas y avoir d’espace sur une ligne, ni en
            début et fin de ligne, ni autour du deux-points. Chaque ligne est terminée par un retour chariot type UNIX.
        </p>
        <p>Chaque propriété d’un jeton que l’on retrouve sous forme <code>clé:valeur</code> va être doublé d’un lien.
            Cependant les liens pouvant être annulés, les propriétés à figer sont écrites dans le jeton. Ainsi, une
            <code>clé:valeur</code> inscrite dans le jeton est prioritaire sur un lien équivalent.</p>
        <p>Dans l'objet du jeton, les clés <a href="#omjcptyp">TYP</a> et <a href="#omjcpsid">SID</a> sont obligatoires,
            toujours au début et dans cet ordre.</p>
        <p>Le début de contenu avec <code>TYP:cryptoken</code> permet de marquer un type de contenu facile à vérifier.
        </p>
        <p>La seconde ligne avec le <a href="#omjcpsid">SID</a> permet d’avoir un contenu unique et donc une empreinte
            unique pour chaque jeton.</p>

        <h4 id="omjcl">OMJCL / Liens</h4>
        <p>Liste des liens à générer lors de la création d'un jeton.</p>
        <p>A faire...</p>

        <h4 id="omjcp">OMJCP / Propriétés</h4>
        <p></p>

        <h5 id="omjcph">OMJCPH / Héritage</h5>
        <p>Certaines propriétés sont héritées de la monnaie, si ces propriétés sont définies dans la monnaie. Ce doit
            être la monnaie déclarée en cours d'utilisation et le jeton doit dépendre de cette monnaie via un sac de
            jetons. De la même façon certaines propriétés sont héritées en second lieu du sac de jeton. Les héritages
            sont prioritaires sur les propriétés définies via l'objet et les liens.</p>

        <h5 id="omjcphct">OMJCPHCT / HCT</h5>
        <p>Définit si l'objet du jeton a un contenu. Si il n'a pas de contenu l'objet du jeton est virtuel et correspond
            à son SID, et les paramètres du jeton ne peuvent pas être forcés.</p>
        <p>Ce n'est pas écrit dans l'objet du jeton ni enregistré via des liens. Cela sert uniquement au moment de la
            création d'un jeton.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcptyp">OMJCPTYP / TYP</h5>
        <p>Le type de jeton.</p>
        <p>Toujours à la valeur <i>cryptoken</i>.</p>
        <p>Présence obligatoire en ligne 1 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpsid">OMJCPSID / SID</h5>
        <p>Le numéro de série identifiant le jeton (<i>serial</i>).</p>
        <p>Si l'objet de référence du jeton est virtuel, l'identifiant du jeton <code>TID</code> sera le
            <code>SID</code>.</p>
        <p>Si l'objet de référence du jeton n'est pas virtuel, l'identifiant du jeton <code>TID</code> dépend du contenu
            de l'objet et est rendu unique grâce à la propriété <code>SID</code>.</p>
        <p>La valeur est de préférence aléatoire mais peut être un compteur à condition d'être unique. L’utilisation
            d’un compteur de faible valeur est fortement déconseillée.</p>
        <p>Si aléatoire, la génération pseudo aléatoire du <code>SID</code> est faite en partant d’un dérivé de la date
            avec quelques valeurs locales. Il n’y a pas de contrainte de sécurité sur cette valeur. Puis une boucle
            interne génère un bon aléa au fur et à mesure de la génération des jetons via une fonction de hash. Le tout
            ne consomme pas du tout de précieux aléa de bonne qualité.</p>
        <p>Présence obligatoire en ligne 2 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpcap">OMJCPCAP / CAP</h5>
        <p>Liste des capacités connues du jeton.</p>
        <p>Si une capacité n'est pas présente elle ne peut être invoquée, même si elle est forcée.</p>
        <p>Le contenu de cette propriété est hérité de la monnaie.</p>
        <p>Présence obligatoire en ligne 3 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpcid">OMJCPCID / CID</h5>
        <p>Identifiant de l’objet de la monnaie (<i>currency</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence obligatoire en ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcppid">OMJCPPID / PID</h5>
        <p>Identifiant de l’objet du sac de jetons (<i>pool</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 2 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Présence obligatoire en ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcptid">OMJCPTID / TID</h5>
        <p>Identifiant du jeton.</p>
        <p>Sa valeur est égale à l'identifiant de l'objet.</p>
        <p>L'objet d'un jeton ne peut en aucun cas contenir son propre identifiant <code>TID</code>.</p>

        <h5 id="omjcpfid">OMJCPFID / FID</h5>
        <p>Identifiant de l'entité ayant forgé le jeton (<i>forge</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpbid">OMJCPBID / BID</h5>
        <p>Identifiant du bloc de forge (<i>blockchain</i>).</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpnam">OMJCPNAM / NAM</h5>
        <p>Le nom de la monnaie. Limité à 256 caractères.</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpuni">OMJCPUNI / UNI</h5>
        <p>Le nom de l'unité de la monnaie en 3 lettres maximum. Pas de chiffre.</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpval">OMJCPVAL / VAL</h5>
        <p>Indication de valeur numérique initiale du jeton dans l’unité de la monnaie qui utilise le jeton.</p>
        <p>C’est une valeur strictement numérique.</p>
        <p>Par défaut, si non présent, la valeur est à interpréter comme équivalente à 1 (un).</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpdta">OMJCPDTA / DTA</h5>
        <p>Identifiant de l’entité autorité de temps pour les limites de temps.</p>
        <p>La gestion du temps avec une autorité de temps permet de prendre en compte sérieusement les suppression
            programmées de jeton (<code>DTC</code>/<code>DTD</code>) ainsi que leur inflation/déflation automatique
            (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>).</p>
        <p>L’autorité de temps peut être spécifique pour chaque jeton mais il est plus logique qu’elle soit commune à
            une monnaie ou dans certains cas à un sac de jetons.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpdtc">OMJCPDTC / DTC</h5>
        <p>Date de création du jeton.</p>
        <p>Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpdtd">OMJCPDTD / DTD</h5>
        <p>Date de suppression programmée du jeton.</p>
        <p> Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date pour être
            fonctionel.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpcom">OMJCPCOM / COM</h5>
        <p>Commentaire texte libre limité à 4096 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpcpr">OMJCPCPR / CPR</h5>
        <p>Licence du jeton sous forme d’une texte libre limité à 1024 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpidm">OMJCPIDM / IDM</h5>
        <p>Mode de fonctionnement du mécanisme d’inflation/déflation (<i>mode</i>) du jeton.</p>
        <p>Les modes sont <i>creation</i> ou <i>transaction</i> ou <i>disabled</i>.</p>
        <p>Suivant le mode, le mécanisme tient compte du temps passé depuis la dernière transaction ou depuis l’émission
            du jeton.</p>
        <p>Le mécanisme d’inflation/déflation (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>), si activé, avec un
            taux de variation inférieur à 1, donc en déflation, permet de forcer les détenteurs de jeton à les utiliser
            plutôt que de les stocker. Les jetons concernés vont donc perdre de la valeur par rapport aux nouveaux
            jetons ou par rapport à ceux qui circulent.</p>
        <p>Si activé, avec un taux de variation suppérieur à 1, donc en inflation, permet de favoriser la conservation
            des jetons et valorise les vieux jetons sur les nouveaux ou ceux qui circulent beaucoup.</p>
        <p>En ne forçant pas cette propriété dans l'objet des jetons, il est possible d'avoir un taux de variation
            fluctuant en fonction des besoins. En le positionnant forçé à <i>disabled</i> cela désactive définitivement
            ce mécanisme au niveau du jeton.</p>
        <p>Un jeton peut se déprécier avec le temps mais une entité peut demander à l’autorité émettrice de la monnaie
            un échange de jeton ancien contre un jeton plus jeune, si l’autorité émettrice le permet.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>CF <a href="#omcpidm">C/IDM</a>.</p>

        <h5 id="omjcpidr">OMJCPIDR / IDR</h5>
        <p>Taux de variation du mécanisme d’inflation/déflation (<i>rate</i>) du jeton.</p>
        <p>Égal à 1 (un), taux constant donc pas de variation.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpidp">OMJCPIDP / IDP</h5>
        <p>Périodicité d’application du taux de variation du mécanisme d’inflation/déflation (<i>period</i>) du jeton.
        </p>
        <p>Unité exprimée en minutes.</p>
        <p>Si à 0 (zéro), la période n'est pas utilisé, donc la variation est non effective.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpsvc">OMJCPSVC / SVC</h5>
        <p>Le jeton fait référence à un type de service rendu (<i>service</i>).</p>
        <p>Taille limitée à 1024 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpclb">OMJCPCLB / CLB</h5>
        <p>Le jeton peut être désactivé (<i>cancelable</i>).</p>
        <p>Par défaut un jeton n’est pas désactivable. Si cette option est présente, quelque soit son contenu, cela
            active la capacité de désactivation à la demande du jeton par la propriété <code>CLD</code>.</p>
        <p>Elle n’a pas d’action sur la propriété de désactivation programmée <code>DTD</code>. Un jeton peut avoir une
            date de suppression programmée et être non désactivable avant la date de suppression. Activer la propriété
            <code>CLD</code> de façon forcée dans le contenu du jeton est faisable mais n’a pas de sens. Des jetons
            peuvent être générés désactivés et activés à posteriori.</p>
        <p>Un jeton désactivé ne peut pas faire parti d’une transaction.</p>
        <p>La taille est de un caractère maximum.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpcld">OMJCPCLD / CLD</h5>
        <p>Le jeton est désactivé (<i>canceled</i>).</p>
        <p>Cette propriété n’est d’utilisée que si <code>CLB</code> est activé.</p>
        <p>Si cette option est présente, quelque soit son contenu, cela désactive le jeton.</p>
        <p>Un jeton désactivé ne peut pas faire parti d’une transaction.</p>
        <p>La taille est de un caractère maximum.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcptrs">OMJCPTRS / TRS</h5>
        <p>Liste des méthodes de transaction supportées.</p>
        <p>Les modes sont définis dans le chapitre <a href="omjm">modes de transfert</a>. Les modes supportés sont
            écrits les uns après les autres sur une seule ligne et séparés par un caractère espace.</p>
        <p>Le contenu de cette propriété est hérité de la monnaie.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h3 id="omjs">OMJS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="omjt">OMJT/ Transfert</h3>
        <p>Le jeton peut faire l'objet de deux types de transferts. Il y a la méthode de transfert de l'information
            (liens et objets) et le marquage du transfert de valeur.</p>
        <h4 id="omjti">OMJTI/ Transfert d'information</h4>
        <p>A faire...</p>

        <h4 id="omjtv">OMJTV/ Transfert de valeur</h4>
        <p>Le transfert de valeur est appelé transaction.</p>
        <p>La transaction unitaire marque l'attribution univoque et unidirectionnel d'une valeur appartenant à une
            entité vers une autre entité. Cette transaction unitaire peut être faite en contrepartie d'une autre valeur
            physique ou virtuelle, mais cette autre valeur n'est pas directement représentée dans la transaction
            unitaire traitée.</p>
        <p>Ici nous appelerons transaction soit une transaction unitaire, soit un block de plusieurs transactions
            unitaires. Les valeurs attribuées dans un block de transactions sont soit des jetons complets, soit des
            parties de jetons. Cependant une transaction unitaire ne peut traiter qu'un unique jeton ou qu'une unique
            partie d'un unique jeton. Dans un block de transactions, toutes les transactions unitaires doivent traiter
            de la même monnaie.</p>
        <p>Une contrepartie d'une autre valeur physique ou virtuelle peut au besoin être inscrite dans un jeton comme
            description. Ce mécanisme nécessite un suivi particulier qui n'est pas pris en charge ici.</p>
        <p>Dans le code, la transaction est traité comme un lien. Si le lien est suffisant, dans le mode LNS (cf <a
                    href="omjcptrs">TRS</a>), alors l'attribution du jeton peut être faite sur le lien uniquement. Si le
            lien fait référence à un objet tier, celui est considéré comme un block de transaction et est lu afin d'en
            extraire toutes les transactions unitaires.</p>

        <h4 id="omjm">OMJM/ Modes de transfert</h4>

        <h5 id="omjmlns">OMJMLNS/ Mode LNS</h5>
        <p>Le code <code>LNS</code> désigne la méthode de base avec un lien (L) matérialisant une transaction et
            imposant un jeton non sécable (NS).</p>
        <p>A faire...</p>

        <h3 id="omjr">OMJR / Réservation</h3>
        <p>Les objets réservés spécifiquement pour les jetons :</p>
        <ul>
            <li>nebule/objet/monnaie/jeton</li>
        </ul>

        <h3 id="omjo">OMJO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}


/**
 * ------------------------------------------------------------------------------------------
 * La classe TokenPool.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'un sac de jetons ou 'new' ;
 * - un tableau des paramètres du nouveau sac de jetons.
 *
 * L'ID d'un sac de jetons est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture de le sac de jetons ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class TokenPool extends Currency
{
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
        '_properties',
        '_propertiesInherited',
        '_propertiesForced',
        '_seed',
        '_inheritedCID',
        '_inheritedPID',
    );

    /**
     * Constructeur.
     *
     * Toujours transmettre l'instance de la librairie nebule.
     * Si le sac de jetons existe, juste préciser l'ID de celui-ci.
     * Si c'est un nouveau sac de jetons à créer, mettre l'ID à 'new'.
     *
     * @param nebule $nebuleInstance
     * @param string $id
     * @param array $param si $id == 'new'
     * @param boolean $protected si $id == 'new'
     * @param boolean $obfuscated si $id == 'new'
     */
    public function __construct(nebule $nebuleInstance, $id, $param = array(), $protected = false, $obfuscated = false)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();

        // Complément des paramètres.
        //$this->_propertiesList['currency']['CurrencyForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
//		$this->_propertiesList['tokenpool']['PoolCurrencyID']['force'] = $this->_nebuleInstance->getCurrentCurrency();
//		$this->_propertiesList['tokenpool']['PoolForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
//		$this->_propertiesList['token']['TokenForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance token pool ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
        ) {
            // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
            $this->_loadTokenPool($id);
        } elseif (is_string($id)
            && $id == 'new'
        ) {
            // Si c'est un nouveau sac de jetons à créer, renvoie à la création.
            $this->_createNewTokenPool($param, $protected, $obfuscated);
        } else {
            // Sinon, le sac de jetons est invalide, retourne 0.
            $this->_id = '0';
        }
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     *  Chargement d'un sac de jetons existant.
     *
     * @param string $id
     */
    private function _loadTokenPool($id)
    {
        // Vérifie que c'est bien un objet.
        if (!is_string($id)
            || $id == ''
            || !ctype_xdigit($id)
            || !$this->_io->checkLinkPresent($id)
            || !$this->_nebuleInstance->getOption('permitCurrency')
        ) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrology->addLog('Load token pool ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log

        // On ne recherche pas les paramètres si ce n'est pas un sac de jetons.
        if ($this->getIsTokenPool('myself')) {
            $this->setAID();
            $this->setFID('0');
        }

        // Vérifie le sac de jetons.
        $TYP = $this->_getParamFromObject('TYP', (int)$this->_propertiesList['tokenpool']['PoolType']['limit']);
        $SID = $this->_getParamFromObject('SID', (int)$this->_propertiesList['tokenpool']['PoolSerialID']['limit']);
        $CID = $this->_getParamFromObject('CID', (int)$this->_propertiesList['tokenpool']['PoolCurrencyID']['limit']);
        if ($TYP == ''
            || $SID == ''
            || $CID == ''
        ) {
            $this->_id = '0';
        } else {
            $this->_propertiesList['tokenpool']['PoolID']['force'] = $id;
            $this->_properties['PID'] = $id;
        }
        $this->_propertiesForced['TYP'] = true;
        $this->_propertiesForced['SID'] = true;
        $this->_propertiesForced['CID'] = true;
        $this->_propertiesForced['PID'] = true;
    }

    /**
     * Création d'une nouveau sac de jetons.
     *
     * @param array $param
     * @return boolean
     */
    private function _createNewTokenPool($param, $protected = false, $obfuscated = false)
    {
        $this->_metrology->addLog('Ask create token pool', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'on puisse créer un sac de jetons.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCurrency')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getOption('permitCreateCurrency')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génère la nouveau sac de jetons.
            $this->_id = $this->_createTokenPool($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrology->addLog('Create token pool error on generation', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create token pool error not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }


    /**
     * Crée un sac de jetons.
     *
     * Les paramètres force* ne sont utilisés que si PoolHaveContent est à true.
     * Pour l'instant le code commence à prendre en compte PoolHaveContent à false mais le paramètre est forçé à true tant que le code n'est pas prêt.
     *
     * Les options pour la génération d'un sac de jetons :
     * poolHaveContent
     * poolSerialID
     * poolForgeID
     * poolCurrencyID
     * poolComment
     * poolCopyright
     * poolTokenCount
     *
     * forcePoolForgeID
     * forcePoolCurrencyID
     * forcePoolComment
     * forcePoolCopyright
     *
     * Retourne la chaine avec 0 si erreur.
     *
     * @param array $param
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return string
     */
    private function _createTokenPool($param, $protected = false, $obfuscated = false)
    {
        // Identifiant final du sac de jetons.
        $this->_id = '0';

        // Normalise les paramètres.
        $this->_normalizeInputParam($param);

        // Force l'écriture de l'objet du sac de jetons.
        $param['PoolHaveContent'] = true;

        // Force l'écriture du serial.
        $param['ForcePoolSerialID'] = true;

        // Détermine si le sac de jetons a un numéro de série fourni.
        $sid = '';
        if (isset($param['PoolSerialID'])
            && is_string($param['PoolSerialID'])
            && $param['PoolSerialID'] != ''
            && ctype_xdigit($param['PoolSerialID'])
        ) {
            $sid = $this->_stringFilter($param['PoolSerialID']);
            $this->_metrology->addLog('Generate token pool asked SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        } else {
            // Génération d'un identifiant de sac de jetons unique aléatoire.
            $sid = $this->_getPseudoRandom();
            $param['PoolSerialID'] = $sid;
            $this->_metrology->addLog('Generate token pool rand SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        }

        // Détermine la monnaie associée.
        $instanceCurrency = $this->_nebuleInstance->getCurrentCurrencyInstance();
        if ($instanceCurrency->getID() != '0') {
            $this->_propertiesList['tokenpool']['PoolCurrencyID']['force'] = $instanceCurrency->getID();
            $param['PoolCurrencyID'] = $instanceCurrency->getID();
        } else {
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' - error no valid CID selected', Metrology::LOG_LEVEL_ERROR); // Log
            return '0';
        }

        // Détermine le forgeur.
        $this->_propertiesList['tokenpool']['PoolForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
        $param['PoolForgeID'] = $this->_nebuleInstance->getCurrentEntity();


        // Détermine si le jeton doit avoir un contenu.
        if (isset($param['PoolHaveContent'])
            && $param['PoolHaveContent'] === true
        ) {
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' HCT:true', Metrology::LOG_LEVEL_DEBUG); // Log

            // Le contenu final commence par l'identifiant interne du sac de jetons.
            $content = 'TYP:' . $this->_propertiesList['tokenpool']['PoolType']['force'] . "\n"; // @todo peut être intégré au reste.
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' TYP:' . $this->_propertiesList['tokenpool']['PoolType']['force'], Metrology::LOG_LEVEL_DEBUG); // Log
            $content .= 'SID:' . $sid . "\n";
            $content .= 'CID:' . $param['PoolCurrencyID'] . "\n";
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' CID:' . $param['PoolCurrencyID'], Metrology::LOG_LEVEL_NORMAL); // Log

            // Pour chaque propriété, si présente et forcée, l'écrit dans l'objet.
            foreach ($this->_propertiesList['tokenpool'] as $name => $property) {
                if ($property['key'] != 'HCT'
                    && $property['key'] != 'TYP'
                    && $property['key'] != 'SID'
                    && $property['key'] != 'CID'
                    //&& $property['key'] != 'PCN'
                    //&& $property['key'] != 'TCN'
                    && isset($property['forceable'])
                    && $property['forceable'] === true
                    && isset($param['Force' . $name])
                    && $param['Force' . $name] === true
                    && isset($param[$name])
                    && $param[$name] != ''
                    && $param[$name] != null
                ) {
                    $value = null;
                    if ($property['type'] == 'boolean') {
                        if ($param[$name] === true) {
                            $value = true;
                        } else {
                            $value = false;
                        }
                    } elseif ($property['type'] == 'number') {
                        $value = (string)$param[$name];
                    } else {
                        $value = $param[$name];
                    }

                    // Ajoute la ligne.
                    $content .= $property['key'] . ':' . $value . "\n";
                    $this->_metrology->addLog('Generate token pool SID:' . $sid . ' force ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                }
            }

            // Crée l'objet avec le contenu et l'écrit.
            $this->_data = $content;
            $this->_haveData = true;
            unset($content);

            // calcul l'ID.
            $this->_id = $this->_crypto->hash($this->_data);

            // Si l'objet doit être protégé.
            if ($protected) {
                $this->setProtected($obfuscated);
            } else {
                // Sinon écrit l'objet directement.
                $this->write();
            }
        } else {
            $this->_id = $sid;
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' HCT:false', Metrology::LOG_LEVEL_DEBUG); // Log
        }

        // Le sac de jetons a maintenant un PID.
        $this->_metrology->addLog('Generate token pool SID:' . $sid . ' PID:' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log


        // Prépare la génération des liens.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $source = $this->_id;
        $argObf = $obfuscated;

        // Le lien de type.
        $action = 'l';
        $target = $this->_nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_SAC);
        $meta = $this->_nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $this->_createLink($signer, $date, $action, $source, $target, $meta, false);

        // Crée les liens associés au sac de jetons.
        $action = 'l';

        // Pour chaque propriété, si présente et a un méta, écrit le lien.
        foreach ($this->_propertiesList['tokenpool'] as $name => $property) {
            if (isset($param[$name])
                && $param[$name] != null
                && $property['key'] != 'PCN'
                && $property['key'] != 'TCN'
            ) {
                $value = null;
                if ($property['type'] == 'boolean') {
                    if ($param[$name] === true) {
                        $value = 'true';
                    } else {
                        $value = 'false';
                    }
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'number') {
                    $value = (string)$param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'hexadecimal') {
                    $value = $param[$name];
                    $target = $value;
                } else {
                    $value = $param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                }

                if ($value != null) {
                    $this->_metrology->addLog('Generate token pool SID:' . $sid . ' add ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                    $meta = $this->_nebuleInstance->getCrypto()->hash($property['key']);
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $argObf);
                    $this->_metrology->addLog('Generate token pool SID:' . $sid . ' link=' . $target . '_' . $meta, Metrology::LOG_LEVEL_DEBUG); // Log
                }
            }
        }


        // Retourne l'identifiant du sac de jetons.
        $this->_metrology->addLog('Generate token pool end SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        return $this->_id;
    }

    /**
     * Retourne la liste des jetons du sac.
     *
     * @return array:string
     */
    public function getTokenList()
    {
        return $this->_getItemList('PID');
    }

    /**
     * Retourne le nombre de jetons du sac.
     *
     * @return integer
     */
    public function getTokenCount()
    {
        return sizeof($this->_getItemList('PID'));
    }


    /**
     * Extrait la valeur relative du sac de jetons à un instant donné.
     *
     * @param $date string
     * @return double
     */
    public function getRelativeValue($date)
    {
        // Récupère la liste des jetons.
        $items = $this->_getItemList('PID');

        $total = 0;

        foreach ($items as $item) {
            $instance = $this->_nebuleInstance->newToken($item);
            if ($instance->getID() != '0') {
                $total += $instance->getRelativeValue($date);
            }
        }

        return $total;
    }
}


/**
 * ------------------------------------------------------------------------------------------
 * La classe Token.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'un jeton ou 'new' ;
 * - un tableau des paramètres du nouveau jeton.
 *
 * L'ID d'un jeton est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture de le jeton ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class Token extends TokenPool
{
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
        '_properties',
        '_propertiesInherited',
        '_propertiesForced',
        '_seed',
        '_inheritedCID',
        '_inheritedPID',
    );

    /**
     * Constructeur.
     *
     * Toujours transmettre l'instance de la librairie nebule.
     * Si le jeton existe, juste préciser l'ID de celui-ci.
     * Si c'est un nouveau jeton à créer, mettre l'ID à 'new'.
     *
     * @param nebule $nebuleInstance
     * @param string $id
     * @param array $param si $id == 'new'
     * @param boolean $protected si $id == 'new'
     * @param boolean $obfuscated si $id == 'new'
     */
    public function __construct(nebule $nebuleInstance, $id, $param = array(), $protected = false, $obfuscated = false)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();

        // Complément des paramètres.
        //$this->_propertiesList['currency']['CurrencyForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
//		$this->_propertiesList['tokenpool']['PoolForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
//		$this->_propertiesList['token']['TokenCurrencyID']['force'] = $this->_nebuleInstance->getCurrentCurrency();
//		$this->_propertiesList['token']['TokenForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance token ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
        ) {
            // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
            $this->_loadToken($id);
        } elseif (is_string($id)
            && $id == 'new'
        ) {
            // Si c'est un nouveau jeton à créer, renvoie à la création.
            $this->_createNewToken($param, $protected, $obfuscated);
        } else {
            // Sinon, le jeton est invalide, retourne 0.
            $this->_id = '0';
        }
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     *  Chargement d'un jeton existant.
     *
     * @param string $id
     */
    private function _loadToken($id)
    {
        // Vérifie que c'est bien un objet.
        if (!is_string($id)
            || $id == ''
            || !ctype_xdigit($id)
            || !$this->_io->checkLinkPresent($id)
            || !$this->_nebuleInstance->getOption('permitCurrency')
        ) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrology->addLog('Load token ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log

        // On ne recherche pas les paramètres si ce n'est pas un jeton.
        if ($this->getIsToken('myself')) {
            $this->setAID();
            $this->setFID('0');
        }

        // Vérifie le jeton.
        $TYP = $this->_getParamFromObject('TYP', (int)$this->_propertiesList['token']['TokenType']['limit']);
        $SID = $this->_getParamFromObject('SID', (int)$this->_propertiesList['token']['TokenSerialID']['limit']);
        $CID = $this->_getParamFromObject('CID', (int)$this->_propertiesList['token']['TokenCurrencyID']['limit']);
        $PID = $this->_getParamFromObject('PID', (int)$this->_propertiesList['token']['TokenPoolID']['limit']);
        if ($TYP == ''
            || $SID == ''
            || $CID == ''
            || $PID == ''
        ) {
            $this->_id = '0';
        } else {
            $this->_propertiesList['token']['TokenID']['force'] = $id;
            $this->_properties['TID'] = $id;
        }
        $this->_propertiesForced['TYP'] = true;
        $this->_propertiesForced['SID'] = true;
        $this->_propertiesForced['CID'] = true;
        $this->_propertiesForced['PID'] = true;
        $this->_propertiesForced['TID'] = true;
    }

    /**
     * Création d'une nouveau jeton.
     *
     * @param array $param
     * @return boolean
     */
    private function _createNewToken($param, $protected = false, $obfuscated = false)
    {
        $this->_metrology->addLog('Ask create token', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'on puisse créer un jeton.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCurrency')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getOption('permitCreateCurrency')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génère la nouveau jeton.
            $this->_id = $this->_createToken($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrology->addLog('Create token error on generation', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create token error not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }


    /**
     * Crée un jeton.
     *
     * Les paramètres force* ne sont utilisés que si tokenHaveContent est à true.
     * Pour l'instant le code commence à prendre en compte tokenHaveContent à false mais le paramètre est forçé à true tant que le code n'est pas prêt.
     *
     * Retourne la chaine avec 0 si erreur.
     *
     * @param array $param
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return string
     */
    private function _createToken($param, $protected = false, $obfuscated = false)
    {
        // Identifiant final du sac de jetons.
        $this->_id = '0';

        // Normalise les paramètres.
        $this->_normalizeInputParam($param);

        // Force l'écriture de l'objet du sac de jetons.
        $param['TokenHaveContent'] = true;

        // Force l'écriture du serial.
        $param['ForceTokenSerialID'] = true;

        // Détermine si le sac de jetons a un numéro de série fourni.
        $sid = '';
        if (isset($param['TokenSerialID'])
            && is_string($param['TokenSerialID'])
            && $param['TokenSerialID'] != ''
            && ctype_xdigit($param['TokenSerialID'])
        ) {
            $sid = $this->_stringFilter($param['TokenSerialID']);
            $this->_metrology->addLog('Generate token asked SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        } else {
            // Génération d'un identifiant de sac de jetons unique aléatoire.
            $sid = $this->_getPseudoRandom();
            $param['TokenSerialID'] = $sid;
            $this->_metrology->addLog('Generate token rand SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        }

        // Détermine la monnaie associée.
        $instanceCurrency = $this->_nebuleInstance->getCurrentCurrencyInstance();
        if ($instanceCurrency->getID() != '0') {
            $this->_propertiesList['token']['TokenCurrencyID']['force'] = $instanceCurrency->getID();
            $param['TokenCurrencyID'] = $instanceCurrency->getID();
        } else {
            $this->_metrology->addLog('Generate token SID:' . $sid . ' - error no valid CID selected', Metrology::LOG_LEVEL_ERROR); // Log
            return '0';
        }

        // Détermine le sac de jetons associé.
        $instancePool = $this->_nebuleInstance->getCurrentTokenPoolInstance();
        if ($instancePool->getID() != '0') {
            $this->_propertiesList['token']['TokenPoolID']['force'] = $instancePool->getID();
            $param['TokenPoolID'] = $instancePool->getID();
        } else {
            $this->_metrology->addLog('Generate token SID:' . $sid . ' - error no valid PID selected', Metrology::LOG_LEVEL_ERROR); // Log
            return '0';
        }

        // Détermine le forgeur.
        $this->_propertiesList['token']['TokenForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
        $param['TokenForgeID'] = $this->_nebuleInstance->getCurrentEntity();


        // Détermine si le jeton doit avoir un contenu.
        if (isset($param['TokenHaveContent'])
            && $param['TokenHaveContent'] === true
        ) {
            $this->_metrology->addLog('Generate token SID:' . $sid . ' HCT:true', Metrology::LOG_LEVEL_DEBUG); // Log

            // Le contenu final commence par l'identifiant interne du sac de jetons.
            $content = 'TYP:' . $this->_propertiesList['token']['TokenType']['force'] . "\n"; // @todo peut être intégré au reste.
            $this->_metrology->addLog('Generate token SID:' . $sid . ' TYP:' . $this->_propertiesList['token']['TokenType']['force'], Metrology::LOG_LEVEL_DEBUG); // Log
            $content .= 'SID:' . $sid . "\n";
            $content .= 'CID:' . $param['TokenCurrencyID'] . "\n";
            $this->_metrology->addLog('Generate token SID:' . $sid . ' CID:' . $param['TokenCurrencyID'], Metrology::LOG_LEVEL_NORMAL); // Log
            $content .= 'PID:' . $param['TokenPoolID'] . "\n";
            $this->_metrology->addLog('Generate token SID:' . $sid . ' PID:' . $param['TokenPoolID'], Metrology::LOG_LEVEL_NORMAL); // Log

            // Pour chaque propriété, si présente et forcée, l'écrit dans l'objet.
            foreach ($this->_propertiesList['token'] as $name => $property) {
                if ($property['key'] != 'HCT'
                    && $property['key'] != 'TYP'
                    && $property['key'] != 'SID'
                    && $property['key'] != 'CID'
                    && $property['key'] != 'PID'
                    //&& $property['key'] != 'PCN'
                    //&& $property['key'] != 'TCN'
                    && isset($property['forceable'])
                    && $property['forceable'] === true
                    && isset($param['Force' . $name])
                    && $param['Force' . $name] === true
                    && isset($param[$name])
                    && $param[$name] != ''
                    && $param[$name] != null
                ) {
                    $value = null;
                    if ($property['type'] == 'boolean') {
                        if ($param[$name] === true) {
                            $value = true;
                        } else {
                            $value = false;
                        }
                    } elseif ($property['type'] == 'number') {
                        $value = (string)$param[$name];
                    } else {
                        $value = $param[$name];
                    }

                    // Ajoute la ligne.
                    $content .= $property['key'] . ':' . $value . "\n";
                    $this->_metrology->addLog('Generate token SID:' . $sid . ' force ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                }
            }

            // Crée l'objet avec le contenu et l'écrit.
            $this->_data = $content;
            $this->_haveData = true;
            unset($content);

            // calcul l'ID.
            $this->_id = $this->_crypto->hash($this->_data);

            // Si l'objet doit être protégé.
            if ($protected) {
                $this->setProtected($obfuscated);
            } else {
                // Sinon écrit l'objet directement.
                $this->write();
            }
        } else {
            $this->_id = $sid;
            $this->_metrology->addLog('Generate token SID:' . $sid . ' HCT:false', Metrology::LOG_LEVEL_DEBUG); // Log
        }

        // Le sac de jetons a maintenant un PID.
        $this->_metrology->addLog('Generate token SID:' . $sid . ' TID:' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log


        // Prépare la génération des liens.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $source = $this->_id;
        $argObf = $obfuscated;

        // Le lien de type.
        $action = 'l';
        $target = $this->_nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_JETON);
        $meta = $this->_nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $this->_createLink($signer, $date, $action, $source, $target, $meta, false);

        // Crée les liens associés au sac de jetons.
        $action = 'l';

        // Pour chaque propriété, si présente et a un méta, écrit le lien.
        foreach ($this->_propertiesList['token'] as $name => $property) {
            if (isset($param[$name])
                && $param[$name] != null
                && $property['key'] != 'PCN'
                && $property['key'] != 'TCN'
            ) {
                $value = null;
                if ($property['type'] == 'boolean') {
                    if ($param[$name] === true) {
                        $value = 'true';
                    } else {
                        $value = 'false';
                    }
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'number') {
                    $value = (string)$param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'hexadecimal') {
                    $value = $param[$name];
                    $target = $value;
                } else {
                    $value = $param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                }

                if ($value != null) {
                    $this->_metrology->addLog('Generate token SID:' . $sid . ' add ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                    $meta = $this->_nebuleInstance->getCrypto()->hash($property['key']);
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $argObf);
                    $this->_metrology->addLog('Generate token SID:' . $sid . ' link=' . $target . '_' . $meta, Metrology::LOG_LEVEL_DEBUG); // Log
                }
            }
        }


        // Retourne l'identifiant du sac de jetons.
        $this->_metrology->addLog('Generate token end SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        return $this->_id;
    }


    /**
     * Extrait la valeur relative du jeton à un instant donné.
     *
     * @param $date string
     * @return double
     */
    public function getRelativeValue($date = '')
    {
        // Prépare la date de traitement.
        if (is_string($date)
            && $date != ''
        ) {
            $dateRef = strtotime($date);
        } else {
            $dateRef = microtime(true);
        }

        // Extraction de la valeur d'origine du jeton.
        $result = (double)$this->getParam('VAL');

        // Vérifie si désactivable et désactivé.
        $CLB = $this->getParam('CLB');
        $CLD = $this->getParam('CLD');
        if ($CLB
            && $CLD
        ) {
            $result = 0;
        }

        // Vérifie si dans l'interval de temps prévu.
        $DTA = $this->getParam('DTA');
        $DTC = $this->getParam('DTC');
        $DTD = $this->getParam('DTD');
        if ($DTA !== null) {
            // @todo
        }

        // Si paramétré, calcul l'inflation (ou déflation) de la valeur.
        $IDM = $this->getParam('IDM');
        $IDR = $this->getParam('IDR');
        $IDP = $this->getParam('IDP');
        if ($IDM != 'disabled') {
            // @todo
        }

        return $result;
    }
}


/**
 * ------------------------------------------------------------------------------------------
 * La classe Wallet.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'un jeton ou 'new' ;
 * - un tableau des paramètres du nouveau portefeuille.
 *
 * L'ID d'un portefeuille est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture du portefeuille ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class Wallet extends Entity
{
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
     * Constructeur.
     *
     * Toujours transmettre l'instance de la librairie nebule.
     * Si le portefeuille existe, juste préciser l'ID de celui-ci.
     * Si c'est un nouveau portefeuille à créer, mettre l'ID à 'new'.
     *
     * @param nebule $nebuleInstance
     * @param string $id
     * @param array $param si $id == 'new'
     * @param boolean $protected si $id == 'new'
     * @param boolean $obfuscated si $id == 'new'
     */
    public function __construct(nebule $nebuleInstance, $id, $param = array(), $protected = false, $obfuscated = false)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance wallet ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
        ) {
            // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
            $this->_loadWallet($id);
        } elseif (is_string($id)
            && $id == 'new'
        ) {
            // Si c'est un nouveau portefeuille à créer, renvoie à la création.
            $this->_createNewWallet($param, $protected, $obfuscated);
        } else {
            // Sinon, le portefeuille est invalide, retourne 0.
            $this->_id = '0';
        }
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     *  Chargement d'un portefeuille existant.
     *
     * @param string $id
     */
    private function _loadWallet($id)
    {
        // Vérifie que c'est bien un objet.
        if (!is_string($id)
            || $id == ''
            || !ctype_xdigit($id)
            || !$this->_io->checkLinkPresent($id)
            || !$this->_nebuleInstance->getOption('permitCurrency')
        ) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrology->addLog('Load wallet ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log
    }

    /**
     * Création d'une nouveau portefeuille.
     *
     * @param array $param
     * @return boolean
     */
    private function _createNewWallet($param, $protected = false, $obfuscated = false)
    {
        $this->_metrology->addLog('Ask create wallet', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'on puisse créer un sac de jetons.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCurrency')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génère la nouveau sac de jetons.
            $this->_id = $this->_createWallet($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrology->addLog('Create wallet error on generation', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create wallet error not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }


    /**
     * Crée un portefeuille.
     *
     * Retourne la chaine avec 0 si erreur.
     *
     * @param array $param
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return string
     */
    private function _createTokenPool($param, $protected = false, $obfuscated = false)
    {
        // Identifiant final du portefeuille.
        $this->_id = '0';

        // @todo
    }
}


/**
 * ------------------------------------------------------------------------------------------
 * La classe Transaction.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant le lien ;
 * - un texte contenant la version du lien.
 *
 * Le nombre de champs du lien doit être de 7 exactement.
 * Le champs signature peut être vide ou à 0 si c'est pour un nouveau lien à signer.
 * La signature doit être explicitement demandée par l'appelle de sign() après la création du lien.
 *
 * Un lien peut être valide si sa structure est correcte, et peut être signé si la signature est valide.
 *
 * Une transaction est un lien entre une monnaie, un jeton et un objet contenant la description de la transaction.
 * La description de la transaction peut en fait contenir plusieurs transactions élémentaires.
 * ------------------------------------------------------------------------------------------
 */
class Transaction extends Link
{
    /**
     * Booléen si le lien est une transaction ET qu'elle est valide.
     *
     * @var boolean
     */
    private bool $_isTransaction = false;

    /**
     * ID de l'objet contenant les transactions.
     * Reste à null en mode LNS.
     *
     * @var string
     */
    private string $_transactionsObjectID = '';

    /**
     * Mode de transaction, si valide dans la monnaie.
     *
     * @var string
     */
    private string $_transactionsMode = '';

    /**
     * Date des transactions.
     *
     * @var DateTime|null
     */
    private DateTime|null $_transactionsTimestamp = null;

    /**
     * Table des transactions unitaires.
     *
     * Les transactions unitaires sont incrémentées à partir de 0.
     * Chaque transaction unitaire est un sous-tableau :
     * - CID
     * - D : horodatage
     * - S : ID détenteur
     * - D : ID destinataire
     * - TID
     * - R : ratio du jeton utilisé, de 0 à 1, 1 = tout
     * - TRS : mode de transaction
     *
     * @var array
     */
    private array $_transactionsArray = array();

    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_fullLink',
        '_signe',
        '_signeValue',
        '_signeAlgo',
        '_signed',
        '_hashSigner',
        '_date',
        '_action',
        '_hashSource',
        '_hashTarget',
        '_hashMeta',
        '_obfuscated',
        '_verified',
        '_valid',
        '_validStructure',
        '_verifyNumError',
        '_verifyTextError',
        '_isTransaction',
    );

    /**
     * Initialisation post-constructeur.
     *
     * @return void
     */
    protected function _initialisation()
    {
        // Vérifications de base.
        if ($this->_action != 'f'
            || $this->_hashSource == '0'
            || $this->_hashTarget == '0'
            || $this->_hashMeta == '0'
        ) {
            return;
        }

        $this->_extractByMode();
    }

    /**
     * Tri par mode de transaction détecté.
     *
     * @return void
     */
    private function _extractByMode()
    {
        // Si transaction en mode mode LNS.
        $hashLNS = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_TRANSACTION);
        if ($this->_hashMeta == $hashLNS) {
            $this->_extractModeLNS();
        } else {
            $this->_isTransaction = false;
        }
    }

    /**
     * Extrait une transaction en mode LNS.
     *
     * Une transaction en mode LNS est marquée par un lien :
     * - hashsign : signataire et entité source disposant du jeton.
     * - date : date de la prise en compte de la transaction.
     * - action : type f.
     * - hashsource : ID du jeton.
     * - hashtarget : ID du destinataire.
     * - hashmeta : hash de 'nebule/objet/monnaie/transaction', marque que c'est une transaction.
     * Elle est repérée par rapport au champs méta.
     * Il faut vérifier que la source est un jeton et en extraire la monnaie affairante.
     * Il faut vérifier que la destination est une entité ou un dérivé.
     *
     * @return void
     */
    private function _extractModeLNS()
    {
        $this->_transactionsMode = 'LNS';

        // Vérifie si l'objet source est un jeton.
        $instanceSource = $this->_nebuleInstance->newToken($this->_hashSource);
        $instanceTarget = $this->_nebuleInstance->newWallet($this->_hashTarget);
        if ($instanceSource->getID() != '0'
            && $instanceSource->getIsToken('all') // @todo modifier le filtre social.
            && $instanceSource->getID() != '0'
            && $instanceSource->getIsWallet('all') // @todo modifier le filtre social.
        ) {
            // Extrait la monnaie.
            $CID = $instanceSource->getParam('CID');
            $instanceCID = $this->_nebuleInstance->newCurrency($CID);
            $modesCID = ' ' . $instanceCID->getParam('TRS') . ' ';

            // Vérifie que le mode LNS est présent dans les modes supportés par la monnaie
            if ($modesCID != '  '
                && strpos($modesCID, ' LNS ') !== false
            ) {
                $this->_transactionsArray[0]['CID'] = $CID;
                $this->_transactionsArray[0]['D'] = $this->_date;
                $this->_transactionsArray[0]['S'] = $this->_hashSigner;
                $this->_transactionsArray[0]['T'] = $this->_hashTarget;
                $this->_transactionsArray[0]['TID'] = $this->_hashSource;
                $this->_transactionsArray[0]['R'] = '1';
                $this->_transactionsArray[0]['TRS'] = 'LNS';
                $this->_isTransaction = true;
            } else {
                $this->_isTransaction = false;
            }
        } else {
            $this->_isTransaction = false;
        }
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Retourne si le lien est bien une transaction.
     *
     * @return boolean
     */
    public function getIsTransaction()
    {
        return $this->_isTransaction;
    }

    /**
     * Retourne l'ID de l'objet des transactions.
     * Retourne null en mode LNS
     *
     * @return string
     */
    public function getTransactionsObjetID()
    {
        return $this->_transactionsObjectID;
    }

    /**
     * Retourne la marque de temps des transactions.
     * Retourne null en mode LNS
     *
     * @return string
     */
    public function getTransactionsTimestamp()
    {
        return $this->_transactionsTimestamp;
    }

    /**
     * Retourne la liste des transactions unitaires.
     * Retourne null en mode LNS
     *
     * @return string
     */
    public function getTransactionsArray()
    {
        return $this->_transactionsArray;
    }
}


/**
 * La classe Link.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant le lien ;
 * - un texte contenant la version du lien.
 *
 * Le nombre de champs du lien doit être de 7 exactement.
 * Le champs signature peut être vide ou à 0 si c'est pour un nouveau lien à signer.
 * La signature doit être explicitement demandée par l'appelle de sign() après la création du lien.
 *
 * La version du lien n'est pas prise en compte.
 *
 * Un lien peut être valide si sa structure est correcte, et peut être signé si la signature est valide.
 *
 * Un lien dissimulé peut ne pas pouvoir être lu si l'entité n'est pas destinataire, donc n'a pas accès à la clé de déchiffrement.
 * Il reste dans ce cas géré comme un lien normal mais de type c.
 * Cependant, si l'entité destinataire est déverrouillée mais ne peut déchiffrer le lien, alors le lien est considéré corrompu.
 */
class Link
{
    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_fullLink',
        '_signe',
        '_signeValue',
        '_signeAlgo',
        '_signed',
        '_hashSigner',
        '_date',
        '_action',
        '_hashSource',
        '_hashTarget',
        '_hashMeta',
        '_obfuscated',
        '_verified',
        '_valid',
        '_validStructure',
        '_verifyNumError',
        '_verifyTextError',
    );

    /**
     * Instance nebule en cours.
     *
     * @var nebule
     */
    protected nebule $_nebuleInstance;

    /**
     * Instance io en cours.
     *
     * @var io
     */
    protected ioInterface $_io;

    /**
     * Instance crypto en cours.
     *
     * @var CryptoInterface $_crypto
     */
    protected CryptoInterface $_crypto;

    /**
     * Instance métrologie en cours.
     *
     * @var Metrology
     */
    protected Metrology $_metrology;

    /**
     * Texte lien complet "s.a_s_d_a_s_t_m" .
     *
     * @var string
     */
    protected string $_fullLink = '';

    /**
     * Parsed link contents.
     *
     * @var array $_parsedLink
     */
    protected array $_parsedLink = array();

    /**
     * Texte signature avec algorithme.
     *
     * @var string $_signe
     */
    protected string $_signe = '0';

    /**
     * Texte valeur hexa de la signature.
     *
     * @var string $_signeValue
     */
    protected string $_signeValue = '';

    /**
     * Texte algorithme de signature.
     *
     * @var string $_signeAlgo
     */
    protected string $_signeAlgo = '';

    /**
     * Texte hexa entité signataire.
     *
     * @var string $_hashSigner
     */
    protected string $_hashSigner = '0';

    /**
     * Texte date du lien.
     *
     * @var string $_date
     */
    protected string $_date = '';

    /**
     * Texte action du lien, sur un octet.
     *
     * @var string $_action
     */
    protected string $_action = '';

    /**
     * Texte hexa objet source.
     *
     * @var string $_hashSource
     */
    protected string $_hashSource = '0';

    /**
     * Texte hexa objet destination.
     *
     * @var string $_hashTarget
     */
    protected string $_hashTarget = '0';

    /**
     * Texte hexa objet méta.
     *
     * @var string $_hashMeta
     */
    protected string $_hashMeta = '0';

    /**
     * Booléen si le lien est dissimulé.
     *
     * @var boolean $_obfuscated
     */
    protected bool $_obfuscated = false;

    /**
     * Booléen si le lien a été vérifié.
     *
     * @var boolean $_verified
     */
    protected bool $_verified = false;

    /**
     * Booléen si le lien est vérifié et valide.
     *
     * @var boolean $_valid
     */
    protected bool $_valid = false;

    /**
     * Booléen si le lien a une structure valide.
     *
     * @var boolean $_validStructure
     */
    protected bool $_validStructure = false;

    /**
     * Booléen si le lien est signé.
     *
     * @var boolean $_signed
     */
    protected bool $_signed = false;

    /**
     * Nombre représentant un code d'erreur de vérification.
     *
     * @var integer $_verifyNumError
     */
    protected int $_verifyNumError = 0;

    /**
     * Texte de la description de l'erreur de vérification.
     *
     * @var string $_verifyTextError
     */
    protected string $_verifyTextError = 'Initialisation';

    /**
     * Booléen si la dissimulation de lien est autorisée.
     *
     * @var boolean $_permitObfuscated
     */
    protected bool $_permitObfuscated = false;


    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @param string $link
     * @return boolean
     */
    public function __construct(nebule $nebuleInstance, string $link)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_permitObfuscated = (bool)$nebuleInstance->getOption('permitObfuscatedLink');
        $this->_metrology->addLinkRead(); // Metrologie.

        // Extrait le lien et vérifie sa structure.
        if (!$this->_extract($link))
            return false;

        // Vérifie la validité du lien.
        if (!$this->_verify())
            return false;

        // Détecte si c'est un lien dissimulé.
        $this->_obfuscated = false;
        if ($this->_action == 'c') {
            // Extrait la partie dissimulée du lien si la clé de déchiffrement est accessible à l'entité.
            if (!$this->_extractObfuscated())
                return false;

            // Vérifie la validité de la partie dissimulée du lien.
            if ($this->_obfuscated
                && !$this->_verifyObfuscated()
            )
                return false;
        }

        // Actions supplémentaires pour les dérivés de liens.
        $this->_initialisation();

        return true;
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_fullLink;
    }

    /**
     * Mise en sommeil de l'instance.
     *
     * @return string[]
     */
    public function __sleep()
    {
        return self::SESSION_SAVED_VARS;
    }

    /**
     * Fonction de réveil de l'instance et de ré-initialisation de certaines variables non sauvegardées.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_permitObfuscated = (bool)$nebuleInstance->getOption('permitObfuscatedLink');
    }


    /**
     * Initialisation post-constructeur.
     *
     * @return void
     */
    protected function _initialisation(): void
    {
        // Rien à faire ici.
    }

    /**
     * Retourne le lien complet.
     *
     * @return string
     */
    public function getFullLink(): string
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_fullLink;
    }

    /**
     * Retourne le lien pré-décomposé.
     *
     * @return array
     */
    public function getParsedLink(): array
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_parsedLink;
    }

    /**
     * Retourne la valeur de la signature.
     *
     * @return string
     */
    public function getSigneValueAlgo_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_signeValue . '.' . $this->_signeAlgo;
    }

    /**
     * Retourne la valeur de la signature.
     *
     * @return string
     */
    public function getSigneValue_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_signeValue;
    }

    /**
     * Retourne l'algorithme de la signature.
     *
     * @return string
     */
    public function getSigneAlgo_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_signeAlgo;
    }

    /**
     * Retourne l'entité signataire du lien.
     *
     * @return string
     */
    public function getHashSigner_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_hashSigner;
    }

    /**
     * Retourne l'action du lien.
     *
     * @return string
     */
    public function getAction_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_action;
    }

    /**
     * Retourne la date du lien.
     *
     * @return string
     */
    public function getDate_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_date;
    }

    /**
     * Retourne l'objet source du lien.
     *
     * @return string
     */
    public function getHashSource_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_hashSource;
    }

    /**
     * Retourne l'objet cible du lien.
     *
     * @return string
     */
    public function getHashTarget_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_hashTarget;
    }

    /**
     * Retourne l'objet méta (contextualisation) du lien.
     *
     * @return string
     */
    public function getHashMeta_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_hashMeta;
    }

    /**
     * Retourne l'état de vérification et de validité du lien.
     *
     * @return boolean
     */
    public function getValid(): bool
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_valid;
    }

    /**
     * Retourne l'état de validité de la forme syntaxique du lien.
     *
     * @return boolean
     */
    public function getValidStructure(): bool
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_validStructure;
    }

    /**
     * Retourne si le lien a été vérifié dans sa forme syntaxique.
     *
     * @return boolean
     */
    public function getVerified(): bool
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_verified;
    }

    /**
     * Retourne le code d'erreur de vérification.
     * -1 : option activée de demande de ne pas tester les liens lors de la vérification - DANGER !!!
     * 0 : Le lien est valide.
     * 1 : Le lien a une structure invalide.
     * 2 : La signature a une structure invalide.
     * 3 : Le lien a une structure sale, sa reconstruction ne donne pas le même lien.
     * 11 : La signature est vide.
     * 12 : La valeur de la signature est invalide.
     * 13 : l'algorithme de signature est invalide.
     * 14 : La valeur de la signature est inconnue.
     * 15 : La signature est invalide.
     * 16 : Mode récupération, l'entité signataire n'est pas le puppetmaster.
     * 21 : L'identifiant du signataire est invalide.
     * 22 : L'objet du signataire n'est pas disponible.
     * 31 : La date est vide.
     * 32 : La date contient des carctères invalides.
     * 41 : L'action est invalide.
     * 51 : L'identifiant de l'objet source est invalide.
     * 52 : L'identifiant de l'objet source est null.
     * 61 : L'identifiant de l'objet cible est invalide.
     * 62 : L'identifiant de l'objet cible est null avec une action f/u/e/c/k.
     * 71 : L'identifiant de l'objet méta est invalide.
     * 72 : L'identifiant de l'objet méta est null avec une action c/k.
     *
     * @return number
     */
    public function getVerifyNumError()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_verifyNumError;
    }

    /**
     * Retourne le texte de description de l'erreur de vérification.
     * -1 : Option ask to not permit check sign on verify - DANGER !!!
     * 0 : Link is valid.
     * 1 : Link have invalid structure.
     * 2 : Signe have invalid structure.
     * 3 : Link have insane structure.
     * 11 : Signe is null.
     * 12 : Signe value is invalid.
     * 13 : Signe algorithm is invalid.
     * 14 : Signe value is unknown.
     * 15 : Signe is invalid.
     * 16 : RESCUE mode, signer is not code master.
     * 21 : Signer ID is invalid.
     * 22 : Signer object is not available.
     * 31 : Date is null.
     * 32 : Date have invalid char.
     * 41 : Action is invalid.
     * 51 : Source object ID is invalid.
     * 52 : Source object ID is null.
     * 61 : Target object ID is invalid.
     * 62 : Target object ID is null with action f/u/e/c/k.
     * 71 : Meta object ID is invalid.
     * 72 : Meta object ID is null with action c/k.
     *
     * @return string
     */
    public function getVerifyTextError()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_verifyTextError;
    }

    /**
     * Retourne si le lien est signé et si la signature est valide.
     * @return boolean
     */
    public function getSigned()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_signed;
    }

    /**
     * Retourne si le lien est dissimulé.
     * Dans ce cas les informations retournées sont les informations du lien non dissimulé.
     *
     * @return boolean
     */
    public function getObfuscated()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return $this->_obfuscated;
    }

    /**
     * Retourne la version avec laquelle est exploité le lien.
     * TODO à supprimer !
     * @return string
     */
    public function getVersion_disabled()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        return '';
    }


    protected function _parse(string $link): array
    {
        // Extract blocs from link L : BH_BL_BS
        $bh = strtok(trim($link), '_');
        $bl = strtok('_');
        $bs = strtok('_');

        $bh_rf = strtok($bh, '/');
        $bh_rv = strtok('/');

        // Check items from RF : APP:TYP
        $bh_rf_app = strtok($bh_rf, ':');
        $bh_rf_typ = strtok(':');

        // Check items from RV : VER:SUB
        $bh_rv_ver = strtok($bh_rv, ':');
        $bh_rv_sub = strtok(':');

        $bl_rc = strtok($bl, '/');
        $bl_rl = strtok('/');

        // Check items from RC : MOD>CHR
        $bl_rc_mod = strtok($bl_rc, '>');
        $bl_rc_chr = strtok('>');

        // Extract items from RL 1 : REQ>NID>NID>NID>NID
        $bl_rl_req = strtok($bl_rl, '>');
        $bl_rl_nid1 = strtok('>');
        $bl_rl_nid2 = strtok('>');
        if ($bl_rl_nid2 === false) $bl_rl_nid2 = '';
        $bl_rl_nid3 = strtok('>');
        if ($bl_rl_nid3 === false) $bl_rl_nid3 = '';
        $bl_rl_nid4 = strtok('>');
        if ($bl_rl_nid4 === false) $bl_rl_nid4 = '';

        $bs_rs1 = strtok($bs, '/');

        // Extract items from RS : NID>SIG
        $bs_rs1_nid = strtok($bs_rs1, '>');
        $bs_rs1_sig = strtok('>');

        // Check hash value.
        $bs_rs1_sig_sign = strtok($bs_rs1_sig, '.');

        // Check algo value.
        $bs_rs1_sig_algo = strtok('.');

        // Check size value.
        $bs_rs1_sig_size = strtok('.');

        return array(
            'link' => $link, // original link
            'bh' => $bh,
            'bh/rf' => $bh_rf,
            'bh/rf/app' => $bh_rf_app,
            'bh/rf/typ' => $bh_rf_typ,
            'bh/rv' => $bh_rv,
            'bh/rv/ver' => $bh_rv_ver,
            'bh/rv/sub' => $bh_rv_sub,
            'bl' => $bl,
            'bl/rc' => $bl_rc,
            'bl/rc/mod' => $bl_rc_mod,
            'bl/rc/chr' => $bl_rc_chr,
            'bl/rl' => $bl_rl,
            'bl/rl/req' => $bl_rl_req,
            'bl/rl/nid1' => $bl_rl_nid1,
            'bl/rl/nid2' => $bl_rl_nid2,
            'bl/rl/nid3' => $bl_rl_nid3,
            'bl/rl/nid4' => $bl_rl_nid4,
            'bs' => $bs,
            'bs/rs' => $bs_rs1,
            'bs/rs1/eid' => $bs_rs1_nid,
            'bs/rs1/sig' => $bs_rs1_sig,
            'bs/rs1/sig/sign' => $bs_rs1_sig_sign,
            'bs/rs1/sig/algo' => $bs_rs1_sig_algo,
            'bs/rs1/sig/size' => $bs_rs1_sig_size,
        );
    }

    /**
     * Extraction du lien.
     * Extrait les champs d'un lien après avoir vérifié la cohérence de sa forme.
     * Ne vérifie pas la cohérence ou la validité des champs !
     *
     * Le nombre de champs doit être de 7.
     * Le champs signature peut être vide ou à 0 si c'est pour un nouveau lien à signer.
     *
     * @param string $link
     * @return boolean
     */
    protected function _extract($link)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr(trim($link), 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Doit être un texte.
        if (!is_string($link)) {
            return false;
        }

        $link = trim($link);
        $this->_valid = false;
        $this->_verified = false;

        // Indice du champs lu, de 1 à 7.
        $j = 1;

        // Tableau temporaire des champs du lien.
        $a = array();

        // Vérifie le nombre de champs, doit avoir 7 champs.
        $ok = false;

        // Première lecture des champs, premier champs.
        $e = strtok($link, '_');

        // Extrait le lien.
        while ($e !== false) {
            if ($j == 1) {
                $this->_signe = trim($e);
            } elseif ($j == 2) {
                $this->_hashSigner = trim($e);
            } elseif ($j == 3) {
                $this->_date = trim($e);
            } elseif ($j == 4) {
                $this->_action = trim($e);
            } elseif ($j == 5) {
                $this->_hashSource = trim($e);
            } elseif ($j == 6) {
                $this->_hashTarget = trim($e);
            } elseif ($j == 7) {
                $this->_hashMeta = trim($e);
                $ok = true;
            } else {
                // Ne doit pas avoir plus de 7 champs.
                $ok = false;
            }

            if ($j < 8) {
                // Lecture de la suite des champs, champs suivant.
                $e = strtok('_');
            } else {
                // Ne doit pas avoir plus de 7 champs.
                $e = false;
            }

            $j++;
        }
        unset($j, $a, $e);

        // Si erreur de lecture, quitte immédiatement et retourne le lien en erreur.
        if (!$ok) {
            $this->_verifyNumError = 1;
            $this->_verifyTextError = 'Link have invalid structure.';
            return false;
        }

        // Si le lien n'est pas un nouveau lien à signer.
        if ($this->_signe != '0'
            && $this->_signe != ''
        ) {
            // Extrait la signature et l'algorithme utilisé. Vérifie qu'ils sont présents.
            $this->_signeValue = trim(strtok(trim($this->_signe), '.'));
            $this->_signeAlgo = trim(strtok('.'));
            if ($this->_signeValue == ''
                || $this->_signeAlgo == ''
            ) {
                $this->_verifyNumError = 2;
                $this->_verifyTextError = 'Signe have invalid structure.';
                return false;
            }
        } else {
            $this->_signe = '0';
            $this->_signeValue = '0';
            $this->_signeAlgo = '';
            $this->_verifyNumError = 11;
            $this->_verifyTextError = 'Signe is null.';
        }

        // Reconstitue le lien pour vérification.
        if ($this->_signe == '0') {
            $rebuildLink = '0';
        } else {
            $rebuildLink = $this->_signeValue . '.' . $this->_signeAlgo;
        }
        $rebuildLink .= '_' . $this->_hashSigner;
        $rebuildLink .= '_' . $this->_date;
        $rebuildLink .= '_' . $this->_action;
        $rebuildLink .= '_' . $this->_hashSource;
        $rebuildLink .= '_' . $this->_hashTarget;
        $rebuildLink .= '_' . $this->_hashMeta;

        // Vérifie que le lien initial correspond au lien nettoyé reconstitué, sinon quitte et retourne le lien en erreur.
        if ($rebuildLink != $link) {
            $this->_verifyNumError = 3;
            $this->_verifyTextError = 'Link have insane structure.';
            return false;
        }

        // On mémorise le lien complet.
        $this->_fullLink = $link;

        // La structure du lien est valide.
        $this->_validStructure = true;

        return true;
    }


    /**
     * Vérification du lien.
     * Vérifie la cohérence et la validité des champs du lien.
     *
     * @return boolean
     */
    protected function _verify()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Tant que le lien n'est pas complètement vérifé, il est marqué invalide.
        $this->_valid = false;
        $this->_signed = false;
        $this->_verified = false;

        // Vérifie les différents champs.
        if (!$this->_verifyHashSigner()) {
            return false;
        }
        if (!$this->_verifyDate()) {
            return false;
        }
        if (!$this->_verifyAction()) {
            return false;
        }
        if (!$this->_verifyHashSource()) {
            return false;
        }
        if (!$this->_verifyHashTarget()) {
            return false;
        }
        if (!$this->_verifyHashMeta()) {
            return false;
        }

        // Ce lien est maintenant marqué comme ayant été vérifié et valide dans sa structure même si sa signature n'est pas encore reconnu valide.
        $this->_valid = true;
        $this->_verified = true;

        //         La vérification est-elle permise ?
        //   / \   DANGER !!! Si non permit, c'est très dangereux !!!
        //  / ! \
        //   ---   Is verify permitted ?
        //         DANGER !!! If not permitted, it's very dangerous !!!
        if (!$this->_nebuleInstance->getOption('permitCheckSignOnVerify')) {
            $this->_signed = false;
            $this->_verifyNumError = -1;
            $this->_verifyTextError = 'Option ask to not permit check sign on verify - DANGER !!!';
            return false;
        }

        // En dernier.
        if (!$this->_verifySign()) {
            return false;
        }

        // Fin de vérification.
        // Tout est bon.
        $this->_metrology->addLinkVerify(); // Metrologie.
        $this->_signed = true;     // Le lien est marqué avec signature valide.
        $this->_verifyNumError = 0;
        $this->_verifyTextError = 'Link is valid.';

        return true;
    }

    /**
     * Vérifie l'empreinte de l'objet signataire.
     *
     * @return boolean
     */
    protected function _verifyHashSigner()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // L'ID du signataire doit être en hexadécimal.
        if (!ctype_xdigit($this->_hashSigner)) {
            $this->_verifyNumError = 21;
            $this->_verifyTextError = 'Signer ID is invalid.';
            return false;
        }

        // L'objet du signataire doit être présent pour que la signature puisse être vérifiée.
        if (!$this->_io->checkObjectPresent($this->_hashSigner)) {
            $this->_verifyNumError = 22;
            $this->_verifyTextError = 'Signer object is not available.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie la date.
     *
     * @return boolean
     */
    protected function _verifyDate()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_date == '') {
            $this->_verifyNumError = 31;
            $this->_verifyTextError = 'Date is null.';
            return false;
        }

        $d = strlen($this->_date);
        for ($i = 0; $i < $d; $i++) {
            // Filtre sur les caractères 0-9 T W Z R Y P M D , : + - / et .
            // Spécifique au format de date ISO 8601:2004.
            $a = ord($this->_date[$i]);
            if (($a < 48
                    || $a > 57
                )
                && $a != 84 // T
                && $a != 87 // W
                && $a != 90 // Z
                && $a != 82 // R
                && $a != 89 // Y
                && $a != 80 // P
                && $a != 77 // M
                && $a != 68 // D
                && $a != 44 // ,
                && $a != 58 // :
                && $a != 43 // +
                && $a != 45 // -
                && $a != 47 // /
                && $a != 46 // .
            ) {
                $this->_verifyNumError = 32;
                $this->_verifyTextError = 'Date have invalid char. ' . $i . '(' . $a . '=' . $this->_date[$i] . ')' . $this->_date;
                return false;
            }
        }
        unset($d);

        return true;
    }

    /**
     * Vérifie l'action.
     *
     * @return boolean
     */
    protected function _verifyAction()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie que l'action est d'un type connu.
        if ($this->_action != 'l'
            && $this->_action != 'f'
            && $this->_action != 'u'
            && $this->_action != 'd'
            && $this->_action != 'e'
            && $this->_action != 'c'
            && $this->_action != 'k'
            && $this->_action != 's'
            && $this->_action != 'x'
        ) {
            $this->_verifyNumError = 41;
            $this->_verifyTextError = 'Action is invalid.';
            return false;
        }

        // Vérifie que l'action de dissimulation de lien est autorisée.
        if ($this->_action == 'k'
            && !$this->_nebuleInstance->getOption('permitProtectedObject')
        ) {
            $this->_verifyNumError = 42;
            $this->_verifyTextError = 'Action k is not autorized.';
            return false;
        }

        // Vérifie que l'action de dissimulation de lien est autorisée.
        if ($this->_action == 'c'
            && !$this->_nebuleInstance->getOption('permitObfuscatedLink')
        ) {
            $this->_verifyNumError = 43;
            $this->_verifyTextError = 'Action c is not autorized.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie l'empreinte de l'objet source.
     *
     * @return boolean
     */
    protected function _verifyHashSource()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // L'ID de l'objet source doit être en hexadécimal.
        if (!ctype_xdigit($this->_hashSource)) {
            $this->_verifyNumError = 51;
            $this->_verifyTextError = 'Source object ID is invalid.';
            return false;
        }

        // L'ID de l'objet source ne doit pas être nul.
        if ($this->_hashSource == '0'
            || $this->_hashSource == ''
        ) {
            $this->_verifyNumError = 52;
            $this->_verifyTextError = 'Source object ID is null.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie l'empreinte de l'objet cible.
     *
     * @return boolean
     */
    protected function _verifyHashTarget()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // L'ID de l'objet cible doit être en hexadécimal.
        if (!ctype_xdigit($this->_hashTarget)) {
            $this->_verifyNumError = 61;
            $this->_verifyTextError = 'Target object ID is invalid.';
            return false;
        }

        // L'ID de l'objet cible ne doit pas être nul si c'est un lien f/u/e/c/k.
        if (($this->_hashTarget == '0'
                && ($this->_action == 'f'
                    || $this->_action == 'u'
                    || $this->_action == 'e'
                    || $this->_action == 'c'
                    || $this->_action == 'k'
                )
            )
            || $this->_hashTarget == ''
        ) {
            $this->_verifyNumError = 62;
            $this->_verifyTextError = 'Target object ID is null with action f/u/e/c/k.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie l'empreinte de l'objet méta.
     *
     * @return boolean
     */
    protected function _verifyHashMeta()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // L'ID de l'objet méta doit être en hexadécimal.
        if (!ctype_xdigit($this->_hashMeta)) {
            $this->_verifyNumError = 71;
            $this->_verifyTextError = 'Meta object ID is invalid.';
            return false;
        }

        // L'ID de l'objet méta ne doit pas être nul si c'est un lien c/k.
        if (($this->_hashMeta == '0'
                && ($this->_action == 'c'
                    || $this->_action == 'k'
                )
            )
            || $this->_hashMeta == ''
        ) {
            $this->_verifyNumError = 72;
            $this->_verifyTextError = 'Meta object ID is null with action c/k.';
            return false;
        }

        return true;
    }

    /**
     * Vérifie la signature.
     * Doit être à la fin des vérifications !
     *
     * @return boolean
     */
    protected function _verifySign()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // La valeur de la signature ne doit pas être nulle.
        if ($this->_signe == '0') {
            $this->_verifyNumError = 11;
            $this->_verifyTextError = 'Signe is null.';
            return false;
        }

        // La valeur de la signature doit être en hexadécimal.
        if (!ctype_xdigit($this->_signeValue)) {
            $this->_verifyNumError = 12;
            $this->_verifyTextError = 'Signe value is invalid.';
            return false;
        }

        // La valeur de l'algorithme de signature doit être en alphadécimal.
        $s = strlen($this->_signeAlgo);
        for ($i = 0; $i < $s; $i++) {
            $a = ord($this->_signeAlgo[$i]);
            if ($a < 48
                || $a > 122
                || ($a > 57
                    && $a < 97
                )
            ) {
                $this->_verifyNumError = 13;
                $this->_verifyTextError = 'Signe algorithm is invalid.';
                return false;
            }
        }
        unset($s);

        // L'aglorithme doit être reconnu.
        if (!$this->_crypto->checkHashAlgorithm($this->_signeAlgo)) {
            $this->_verifyNumError = 14;
            $this->_verifyTextError = 'Signe value is unknown.';
            return false;
        }

        // Lit la clé publique.
        $pubkey = $this->_io->objectRead($this->_hashSigner, Entity::ENTITY_MAX_SIZE);

        // Génère le lien sans signature et son hash pour vérification.
        $shortLink = '_' . $this->_hashSigner . '_' . $this->_date . '_' . $this->_action . '_' . $this->_hashSource . '_' . $this->_hashTarget . '_' . $this->_hashMeta;
        $hashShortLink = $this->_crypto->hash($shortLink, $this->_signeAlgo);
        // Vérifie la signature avec la clé publique du signataire.
        if (!$this->_crypto->verify($hashShortLink, $this->_signeValue, $pubkey)) {
            $this->_verifyNumError = 15;
            $this->_verifyTextError = 'Signe is invalid.';
            return false;
        }
        unset($pubkey, $shortLink, $hashShortLink);

        // Si mode rescue, vérifie que le lien est du code master.
        if ($this->_nebuleInstance->getModeRescue()
            && $this->_hashSigner != $this->_nebuleInstance->getCodeAuthority()
        ) {
            $this->_verifyNumError = 16;
            $this->_verifyTextError = 'RESCUE mode, signer is not code master.';
            return false;
        }

        // Tout est bon.
        return true;
    }


    /**
     * Extraction de la partie dissimulée du lien.
     * Ne vérifie pas la cohérence ou la validité des champs !
     *
     * @return boolean
     */
    protected function _extractObfuscated()
    {
        // @todo
    }


    /**
     * Vérification de la partie dissimulée du lien.
     * Vérifie la cohérence et la validité des champs du lien extraits de la partie dissimulée.
     *
     * @return boolean
     */
    protected function _verifyObfuscated()
    {
        // @todo
    }


    /**
     * Signature du lien par l'entité en cours.
     *
     * @param string $publicKey
     * @return boolean
     */
    public function sign($publicKey = '0')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si autorisé à signer.
        if (!$this->_nebuleInstance->getOption('permitCreateLink')) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Can not sign link', Metrology::LOG_LEVEL_DEBUG); // Log
            return false;
        }

        // Si le lien est valide.
        if ($this->_validStructure
            && $this->_verified
            && $this->_valid
            && $this->_verifyNumError == 11
            && $this->_signe == '0'
        ) {
            // Lit la clé publique.
            if (is_a($publicKey, 'Entity')) {
                $pubkeyInstance = $publicKey;
                $pubkeyID = $publicKey->getID();
            } else {
                if ($publicKey == '0') {
                    $pubkeyID = $this->_nebuleInstance->getCurrentEntity();
                    $pubkeyInstance = $this->_nebuleInstance->getCurrentEntityInstance();
                } elseif ($publicKey == $this->_nebuleInstance->getCurrentEntity()) {
                    $pubkeyInstance = $this->_nebuleInstance->getCurrentEntityInstance();
                    $pubkeyID = $publicKey;
                } else {
                    $pubkeyInstance = $this->_nebuleInstance->newEntity($publicKey);
                    $pubkeyID = $publicKey;
                }
            }
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Sign link for ' . $pubkeyID, Metrology::LOG_LEVEL_DEBUG); // Log

            // Récupère l'algorithme de hash.
            $hashAlgo = $this->_crypto->hashAlgorithmName();

            // Génère le lien sans signature et son hash pour vérification.
            $shortLink = '_' . $pubkeyID . '_' . $this->_date . '_' . $this->_action . '_' . $this->_hashSource . '_' . $this->_hashTarget . '_' . $this->_hashMeta;

            // Génère la signature.
            $sign = $pubkeyInstance->signLink($shortLink, $hashAlgo);

            if ($sign !== false) {
                $this->_signeValue = $sign;
                $this->_signeAlgo = $hashAlgo;
                $this->_signe = $sign . '.' . $hashAlgo;
                $this->_hashSigner = $pubkeyID;
                $this->_fullLink = $this->_signe . $shortLink;
                $this->_signed = true;
                $this->_valid = true;
                $this->_verified = true;
                $this->_verifyNumError = 0;
                $this->_verifyTextError = 'Signe is valid.';
                return true;
            }
        } else {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Invalid link', Metrology::LOG_LEVEL_DEBUG); // Log
        }

        return false;
    }

    /**
     * Ecrit le lien pour les objets concernés.
     *
     * @return boolean
     */
    public function write()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Si autorisé à écrire.
        if (!$this->_nebuleInstance->getOption('permitWrite')
            || !$this->_nebuleInstance->getOption('permitWriteLink')
        ) {
            return false;
        }

        // Métrologie.
        $this->_nebuleInstance->getMetrologyInstance()->addAction('addlnk', $this->_fullLink, $this->_verified);

        // Si le lien n'est pas valide, quitte.
        if (!$this->_validStructure
            || !$this->_verified
            || !$this->_valid
            || !$this->_signed
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link unsigned', Metrology::LOG_LEVEL_DEBUG); // Log
            return false;
        }

        // Ecrit l'historique.
        if ($this->_nebuleInstance->getOption('permitHistoryLinksSign')) {
            $history = nebule::NEBULE_LOCAL_HISTORY_FILE;
            $this->_io->linkWrite($history, $this->_fullLink);
        }

        // Ecrit le lien pour l'objet de l'entité signataire.
        if ($this->_nebuleInstance->getOption('permitAddLinkToSigner')) {
            $this->_io->linkWrite($this->_hashSigner, $this->_fullLink);
        }

        if ($this->_action != 'c') {
            // Ecrit le lien pour l'objet source.
            $this->_io->linkWrite($this->_hashSource, $this->_fullLink);

            // Ecrit le lien pour l'objet cible.
            if ($this->_hashTarget != $this->_hashSource
                && $this->_hashTarget != '0'
            ) {
                $this->_io->linkWrite($this->_hashTarget, $this->_fullLink);
            }

            // Ecrit le lien pour l'objet méta.
            if ($this->_hashMeta != $this->_hashSource
                && $this->_hashMeta != $this->_hashTarget
                && $this->_hashMeta != '0'
            ) {
                $this->_io->linkWrite($this->_hashMeta, $this->_fullLink);
            }
        } elseif ($this->_nebuleInstance->getOption('permitObfuscatedLink')) {
            // Ecrit le lien dissimulé.
            $this->_io->linkWrite($this->_hashSigner . '-' . $this->_hashSource, $this->_fullLink); // @todo
        } else {
            return false;
        }

        return true;
    }

    /**
     * Signe et écrit le lien.
     *
     * @param string $publicKey
     * @return boolean
     */
    public function signWrite($publicKey = '0')
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->sign($publicKey)) {
            return $this->write();
        }
        return false;
    }


    /**
     * Offusque le lien. Ne pas oublier de l'écrire.
     * @return boolean
     * @todo
     *
     * Le lien à dissimulé est concaténé avec un bourrage (padding) d'espace de taille aléatoire compris entre 3 et 5 fois la taille du champs source.
     *
     */
    public function obfuscate()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if (!$this->_obfuscated
            && $this->_verified
            && $this->_valid
            && $this->_verifyNumError == 0
            && $this->_permitObfuscated
        ) {
            // @todo
        }

        return false;
    }

    /**
     * Offusque et écrit le lien.
     *
     * @return boolean
     */
    public function obfuscateWrite()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        // Vérifie si autorisé à dissimuler des liens.
        if (!$this->_permitObfuscated)
            return false;

        $this->obfuscate();
        return $this->write();
    }

    /**
     * Désoffusque le lien. Ne pas oublier de l'écrire.
     * @return boolean
     * @todo
     *
     */
    public function deobfuscate()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_obfuscated
            && $this->_verified
            && $this->_valid
            && $this->_verifyNumError == 0
        ) {
            // @todo
        }

        return false;
    }

    /**
     * Désoffusque et écrit le lien.
     *
     * @return boolean
     */
    public function deobfuscateWrite()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->deobfuscate();
        return $this->write();
    }

    /**
     * Extrait le lien offusqué.
     * @return boolean
     * @todo
     *
     */
    public function decrypt()
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . substr($this->_fullLink, 0, 32), Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_obfuscated
            && $this->_verified
            && $this->_valid
            && $this->_verifyNumError == 0
        ) {
            // @todo
        }
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#l">L / Lien</a>
            <ul>
                <li><a href="#lelpo">LELPO / Liens à Propos d’un Objet</a></li>
                <li><a href="#lelco">LELCO / Liens Contenu dans un Objet</a></li>
                <li><a href="#le">LE / Entête</a></li>
                <li><a href="#lr">LR / Registre</a>
                    <ul>
                        <li><a href="#lrsi">LRSI / Le champ <code>Signature</code></a></li>
                        <li><a href="#lrusi">LRHSI / Le champ <code>HashSignataire</code></a></li>
                        <li><a href="#lrt">LRT / Le champ <code>TimeStamp</code></a></li>
                        <li><a href="#lra">LRA / Le champ <code>Action</code></a>
                            <ul>
                                <li><a href="#lral">LRAL / Action <code>l</code> – Lien entre objets</a></li>
                                <li><a href="#lraf">LRAF / Action <code>f</code> – Dérivé d’objet</a></li>
                                <li><a href="#lrau">LRAU / Action <code>u</code> – Mise à jour d’objet</a></li>
                                <li><a href="#lrad">LRAD / Action <code>d</code> – Suppression d’objet</a></li>
                                <li><a href="#lrae">LRAE / Action <code>e</code> – Équivalence d’objets</a></li>
                                <li><a href="#lrac">LRAC / Action <code>c</code> – Chiffrement de lien</a></li>
                                <li><a href="#lrak">LRAK / Action <code>k</code> – Chiffrement d’objet</a></li>
                                <li><a href="#lras">LRAS / Action <code>s</code> – Subdivision d’objet</a></li>
                                <li><a href="#lrax">LRAX / Action <code>x</code> – Suppression de lien</a></li>
                            </ul>
                        </li>
                        <li><a href="#lrhs">LRHS / Le champ <code>HashSource</code></a></li>
                        <li><a href="#lrhc">LRHC / Le champ <code>HashCible</code></a></li>
                        <li><a href="#lrhm">LRHM / Le champ <code>HashMeta</code></a></li>
                    </ul>
                </li>
                <li><a href="#l1">L1 / Lien simple</a></li>
                <li><a href="#l2">L2 / Lien double</a></li>
                <li><a href="#l3">L3 / Lien triple</a></li>
                <li><a href="#ls">LS / Stockage</a>
                    <ul>
                        <li><a href="#lsa">LSA / Arborescence</a></li>
                        <li><a href="#lsd">LSD / Dissimulation</a>
                            <ul>
                                <li><a href="#lsdrp">LSDRP / Registre public</a></li>
                                <li><a href="#lsdrd">LSDRD / Registre dissimulé</a></li>
                                <li><a href="#lsda">LSDA / Attaque sur la dissimulation</a></li>
                                <li><a href="#lsds">LSDS / Stockage et transcodage</a>
                                    <ul>
                                        <li><a href="#lsdst">LSDST / Translation de lien</a></li>
                                        <li><a href="#lsdsp">LSDSP / Protection de translation</a></li>
                                    </ul>
                                </li>
                                <li><a href="#lsdt">LSDT / Transfert et partage</a></li>
                                <li><a href="#lsdc">LSDC / Compromission</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#lt">LT / Transfert</a></li>
                <li><a href="#lv">LV / Vérification</a></li>
                <li><a href="#lo">LO / Oubli</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h1 id="l">L / Lien</h1>
        <p>Le lien est la matérialisation dans un graphe d’une relation entre deux objets pondéré par un troisième
            objet.</p>

        <h5 id="lelpo">LELPO / Liens à Propos d’un Objet</h5>
        <p>Les liens d’un objet sont consultables séquentiellement. Il doivent être perçus comme des méta-données d’un
            objet.</p>
        <p>Les liens sont séparés soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;». Un
            lien est donc une suite de caractères ininterrompue, c’est à dire sans espace ou retour à la ligne.</p>
        <p>La taille du lien dépend de la taille de chaque champs.</p>
        <p>Chaque localisation contenant des liens doit avoir un entête de version.</p>

        <h5 id="lelco">LELCO / Liens Contenu dans un Objet</h5>
        <p>Certains liens d’un objet peuvent être contenus dans un autre objet.</p>
        <p>Cette forme de stockage des liens permet de les transmettre et de les manipuler sous la forme d’un objet. On
            peut ainsi profiter du découpage et du chiffrement. Plusieurs liens peuvent être stockés sans être
            nécessairement en rapport avec les mêmes objets.</p>
        <p>Les liens stockés dans un objet ne peuvent pas faire référence à ce même objet.</p>
        <p>Tout ajout de lien crée implicitement un nouvel objet de mise à jour, c’est à dire lié par un lien de type
            u.</p>
        <p>Chaque fichier contenant des liens doit avoir un entête de version.</p>
        <p>Les objets contenants des liens ne sont pas reconnus et exploités lors de la lecture des liens. Ceux-ci
            doivent d’abord être extraits et injectés dans les liens des objets concernés. En clair, on ne peux pas s’en
            servir facilement pour de l’anonymisation.</p>

        <h2 id="le">LE / Entête</h2>
        <p>L’entête des liens est constitué du texte <code>nebule/liens/version/1.2</code>. Il est séparé du premier
            lien soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Il doit être transmit avec les liens, en premier.</p>

        <h2 id="lr">LR / Registre</h2>
        <p>Le registre du lien décrit la syntaxe du lien :</p>
        <p style="text-align:center">
            <code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>
        <p>Ce registre a un nombre de champs fixe. Chaque champs a une place fixe dans le lien. Les champs ont une
            taille variable. Le séparateur de champs est l’underscore «&nbsp;_&nbsp;». Les champs ne peuvent contenir ni
            l’underscore «&nbsp;_&nbsp;» ni l’espace &nbsp;» &nbsp;» ni le retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Tout lien qui ne respecte pas cette syntaxe est à considérer comme invalide et à supprimer. Tout lien dont la
            <code>Signature</code> est invalide est à considérer comme invalide et à supprimer. La vérification peut
            être réalisée en ré-assemblant les champs après nettoyage.</p>

        <h4 id="lrsi">LRSI / Le champ <code>Signature</code></h4>
        <p>Le champ <code>Signature</code> est représenté en deux parties séparées par un point «&nbsp;.&nbsp;» . La
            première partie contient la valeur de la signature. La deuxième partie contient le nom court de la fonction
            de prise d’empreinte utilisée.</p>
        <p>La signature est calculée sur l’empreinte du lien réalisée avec la fonction de prise d’empreinte désignée
            dans la deuxième partie. L’empreinte du lien est calculée sur tout le lien sauf le champs
            <code>signature</code>, c’est à dire sur «&nbsp;<code>_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code>&nbsp;»
            avec le premier underscore inclus.</p>
        <p>La signature ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;» à «&nbsp;9&nbsp;»
            et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule. La fonction de prise d’empreinte est notée en
            caractères alpha-numériques en minuscule.</p>

        <h5 id="lrusi">LRHSI / Le champ <code>HashSignataire</code></h5>
        <p>Le champ <code>HashSignataire</code> désigne l’objet de l’entité qui génère le lien et le signe.</p>
        <p>Il ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;» à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;»
            à «&nbsp;f&nbsp;» en minuscule.</p>

        <h3 id="lrt">LRT / Le champ <code>TimeStamp</code></h3>
        <p>Le champ <code>TimeStamp</code> est une marque de temps qui donne un ordre temporel aux liens. Ce champs peut
            être une date et une heure au format <a class="external text" title="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    href="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    rel="nofollow">ISO8601</a> ou simplement un compteur incrémental.
        </p>

        <h3 id="lra">LRA / Le champ <code>Action</code></h3>
        <p>Le champ <code>Action</code> détermine la façon dont le lien doit être utilisé.</p>
        <p>Quand on parle du type d’un lien, on fait référence à son champ <code>Action</code>.</p>
        <p>L’interprétation de ce champ est limité au premier caractère. Des caractères alpha-numériques supplémentaires
            sont autorisés mais ignorés.</p>
        <p>Cette interprétation est basée sur un vocabulaire particulier. Ce vocabulaire est spécifique à <i>nebule
                v1.2</i> (et <i>nebule v1.1</i>).</p>
        <p>Le vocabulaire ne reconnaît que les 8 caractères <code>l</code>, <code>f</code>, <code>u</code>,
            <code>d</code>, <code>e</code>, <code>x</code>, <code>k</code> et <code>s</code>, en minuscule.</p>

        <h4 id="lral">LRAL / Action <code>l</code> – Lien entre objets</h4>
        <p>Met en place une relation entre deux objets. Cette relation a un sens de mise en place et peut être pondérée
            par un objet méta.</p>
        <p>Les liens de type <code>l</code> ne devraient avoir ni <code>HashMeta</code> nul ni <code>HashCible</code>
            nul.</p>

        <h4 id="lraf">LRAF / Action <code>f</code> – Dérivé d’objet</h4>
        <p>Le nouvel objet est considéré comme enfant ou parent suivant le sens du lien.</p>
        <p>Le champs <code>ObjetMeta</code> doit être vu comme le contexte du lien. Par exemple, deux objets contenants
            du texte peuvent être reliés simplement sans contexte, c’est à dire reliés de façon simplement hiérarchique.
            Ces deux mêmes textes peuvent être plutôt (ou en plus) reliés avec un contexte comme celui d’une discussion
            dans un blog. Dans ce deuxième cas, la relation entre les deux textes n’a pas de sens en dehors de cette
            discussion sur ce blog. Il est même probable que le blog n’affichera pas les autres textes en relations si
            ils n’ont pas un contexte appartenant à ce blog.</p>
        <p><code>f</code> comme <i>fork</i>.</p>

        <h4 id="lrau">LRAU / Action <code>u</code> – Mise à jour d’objet</h4>
        <p>Mise à jour d’un objet dérivé qui remplace l’objet parent.</p>
        <p><code>u</code> comme <i>update</i>.</p>

        <h4 id="lrad">LRAD / Action <code>d</code> – Suppression d’objet</h4>
        <p>L’objet est marqué comme à supprimer d’un ou de tous ses emplacements de stockage.</p>
        <p><code>d</code> comme <i>delete</i>.</p>
        <p>Le champs <code>HashCible</code> <span style="text-decoration: underline;">peut</span> être nuls, c’est à
            dire égal à <code>0</code>. Si non nul, ce champs doit contenir une entité destinataire de <i>l’ordre</i> de
            suppression. C’est utilisé pour demander à une entité relaie de supprimer un objet spécifique. Cela peut
            être utilisé pour demander à une entité en règle générale de bien vouloir supprimer l’objet, ce qui n’est
            pas forcément exécuté.</p>
        <p>Le champs <code>HashMeta</code> <span style="text-decoration: underline;">doit</span> être nuls, c’est à dire
            égal à <code>0</code>.</p>
        <p>Un lien de suppression sur un objet ne veut pas forcément dire qu’il a été supprimé. Même localement, l’objet
            est peut-être encore présent. Si le lien de suppression vient d’une autre entité, on ne va sûrement pas par
            défaut en tenir compte.</p>
        <p>Lorsque le lien de suppression est généré, le serveur sur lequel est généré le lien doit essayer par défaut
            de supprimer l’objet. Dans le cas d’un serveur hébergeant plusieurs entités, un objet ne sera pas supprimé
            si il est encore utilisé par une autre entité, c’est à dire si une entité a un lien qui le concerne et n’a
            pas de lien de suppression.</p>

        <h4 id="lrae">LRAE / Action <code>e</code> – Équivalence d’objets</h4>
        <p>Définit des objets jugés équivalents, et donc interchangeables par exemple pour une traduction.</p>

        <h4 id="lrac">LRAC / Action <code>c</code> – Chiffrement de lien</h4>
        <p>Ce lien de dissimulation contient un lien dissimulé sans signature. Il permet d’offusquer des liens entre
            objets et donc d’anonymiser certaines actions de l’entité (cf <a href="#ckl">CKL</a>).</p>
        <p>Le champs <code>HashSource</code> fait référence à l’entité destinataire du lien, celle qui peut le
            déchiffrer. A part le champs de l’entité signataire, c’est le seul champs qui fait référence à un objet.</p>
        <p>Le champs <code>HashCible</code> ne contient pas la référence d’un objet mais le lien chiffré et encodé en
            hexadécimal. Le chiffrement est de type symétrique avec la clé de session. Le lien offusqué n’a pas grand
            intérêt en lui même, c’est le lien déchiffré qui en a.</p>
        <p>Le champs <code>HashMeta</code> ne contient pas la référence d’un objet mais la clé de chiffrement du lien,
            dite clé de session. Cette clé est chiffrée (asymétrique) pour l’entité destinataire et encodée en
            hexadécimal. Chaque entités destinataires d'un lien de dissimulé doit disposer d'un lien de dissimulation
            qui lui est propre.</p>
        <p>Lors du traitement des liens, si une entité est déverrouillée, les liens offusqués pour cette entité doivent
            être déchiffrés et utilisés en remplacement des liens offusqués originels. Les liens offusqués doivent être
            vérifiés avant déchiffrement. Les liens déchiffrés doivent être vérifiés avant exploitation.</p>
        <p>Les liens de dissimulations posent un problème pour être efficacement utilisés par les entités émetrices et
            destinataires. Pour résoudre ce problème sans risquer de révéler les identifiants des objets utilisés dans
            un lien dissimulé, les liens de dissimulation sont attachés à des objets virtuels translatés depuis les
            identifiants des objets originaux (cf <a href="#ld">LD</a>).</p>
        <p>L'option <code>permitObfuscatedLink</code> permet de désactiver la dissimulation (offuscation) des liens des
            objets. Dans ce cas le lien de type <code>c</code> est rejeté comme invalide avec le code erreur 43.</p>

        <h4 id="lrak">LRAK / Action <code>k</code> – Chiffrement d’objet</h4>
        <p>Désigne la version chiffrée de l’objet (cf <a href="#cko">CKO</a>).</p>
        <p>L'option <code>permitProtectedObject</code> permet de désactiver la protection (chiffrement) des objets. Dans
            ce cas le lien de type <code>k</code> est rejeté comme invalide avec le code erreur 42.</p>

        <h4 id="lras">LRAS / Action <code>s</code> – Subdivision d’objet</h4>
        <p>Désigne un fragment de l’objet.</p>
        <p>Ce champ nécessite un objet méta qui précise intervalle de contenu de l’objet d’origine. Le contenu de
            l’objet méta doit être de la forme <code>x-y</code> avec :</p>
        <ul>
            <li><code>x</code> et <code>y</code> exprimé en octet sans zéro et sans unité ;</li>
            <li><code>x</code> strictement supérieur à zéro ;</li>
            <li><code>y</code> strictement inférieur ou égal à la taille de l’objet (lien vers
                <i>nebule/objet/taille</i>) ;
            </li>
            <li><code>x</code> inférieur à <code>y</code> ;</li>
            <li>sans espace, tabulation ou retour chariot.</li>
        </ul>

        <h4 id="lrax">LRAX / Action <code>x</code> – Suppression de lien</h4>
        <p>Supprime un ou plusieurs liens précédemment mis en place.</p>
        <p>Les liens concernés par la suppression sont les liens antérieurs de type <code>l</code>, <code>f</code>,
            <code>u</code>, <code>d</code>, <code>e</code>, <code>k</code> et <code>s</code>. Ils sont repérés par les 3
            derniers champs, c’est à dire sur <code>HashSource_HashCible_HashMeta</code>. Les champs nuls sont
            strictement pris en compte.</p>
        <p>Le champ <code>TimeStamp</code> permet de déterminer l’antériorité du lien et donc de déterminer sa
            suppression ou pas.</p>
        <p>C’est la seule action sur les liens et non sur les objets.</p>

        <h4 id="lrhs">LRHS / Le champ <code>HashSource</code></h4>
        <p>Le champ <code>HashSource</code> désigne l’objet source du lien.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>

        <h4 id="lrhc">LRHC / Le champ <code>HashCible</code></h4>
        <p>Le champ <code>HashCible</code> désigne l’objet destination du lien.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c’est à dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <h4 id="lrhm">LRHM / Le champ <code>HashMeta</code></h4>
        <p>Le champ <code>HashMeta</code> désigne l’objet contenant une caractérisation du lien entre l’objet source et
            l’objet destination.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c’est à dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <h2 id="l1">L1 / Lien simple</h2>
        <p>Le registre du lien simple a ses champs <code>HashCible</code> et <code>HashMeta</code> égaux à «&nbsp;0&nbsp;».
        </p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_0_0</code></p>

        <h2 id="l2">L2 / Lien double</h2>
        <p>Le registre du lien double a son champ <code>HashMeta</code> égal à «&nbsp;0&nbsp;».</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_0</code></p>

        <h2 id="l3">L3 / Lien triple</h2>
        <p>Le registre du lien triple est complètement utilisé.</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>

        <h2 id="ls">LS / Stockage</h2>
        <p>Tous les liens sont stockés dans un même emplacement ou sont visible comme étant dans un même emplacement.
            Cet emplacement ne contient pas les contenus des objets (cf <a href="#oos">OOS</a>).</p>
        <p>Le lien dissimulé est stocké dans le même emplacement mais dispose de fichiers de stockages différents du
            fait de la spécificité (cf <a href="#lsds">LSDS</a>).</p>

        <h3 id="lsa">LSA / Arborescence</h3>
        <p>Sur un système de fichiers, tous les liens sont stockés dans des fichiers contenus dans le dossier <code>pub/l/</code>
            (<code>l</code> comme lien).</p>
        <p>A faire...</p>

        <h3 id="lsd">LSD / Dissimulation</h3>
        <p>Le lien de dissimulation, de type <code>c</code>, contient un lien dissimulé sans signature (cf <a
                    href="#lrac">LRAC</a>). Il permet d’offusquer des liens entre objets et donc d’anonymiser certaines
            actions de l’entité (cf <a href="#ckl">CKL</a>).</p>

        <h5 id="lsdrp">LSDRP / Registre public</h5>
        <p>Le registre du lien de dissimulation, public par nature, est conforme au registre des autres liens (cf <a
                    href="#lr">LR</a>). Si ce lien ne respectait pas cette structure il serait automatiquement ignoré ou
            rejeté. Son stockage et sa transmission ont cependant quelques particularités.</p>
        <p>Les champs <code>Signature</code> (cf <a href="#lrsi">LRSI</a>) et <code>HashSignataire</code> (cf <a
                    href="#lrhsi">LRHSI</a>) du registre sont conformes aux autres liens. Ils assurent la protection du
            lien. Le champs signataire fait office d'émeteur du lien dissimulé.</p>
        <p>Le champs <code>TimeStamp</code> (cf <a href="#lrt">LRT</a>) du registre est conformes aux autres liens. Il a
            cependant une valeur non significative et sourtout pas liée au <code>TimeStamp</code> du lien dissimulé.</p>
        <p>Le champs <code>Action</code> (cf <a href="#lrt">LRT</a>) du registre est de type <code>c</code> (cf <a
                    href="#lra">LRA</a> et <a href="#lrac">LRAC</a>).</p>
        <p>Le champs <code>HashSource</code> fait référence à l’entité destinataire du lien, celle qui peut le
            déchiffrer. A part le champs de l’entité signataire, c’est le seul champs qui fait référence à un objet.</p>
        <p>Le champs <code>HashCible</code> ne contient pas la référence d’un objet mais le lien chiffré et encodé en
            hexadécimal. Le chiffrement est de type symétrique avec la clé de session. Le lien offusqué n’a pas grand
            intérêt en lui même, c’est le lien déchiffré qui en a.</p>
        <p>Le champs <code>HashMeta</code> ne contient pas la référence d’un objet mais la clé de chiffrement du lien,
            dite clé de session. Cette clé est chiffrée (asymétrique) pour l’entité destinataire et encodée en
            hexadécimal. Chaque entités destinataires d'un lien de dissimulé doit disposer d'un lien de dissimulation
            qui lui est propre.</p>
        <p>Le registre du lien de dissimulation :</p>
        <ul>
            <li>Signature du lien</li>
            <li>Identifiant du signataire</li>
            <li>Horodatage non significatif</li>
            <li>action : <code>c</code></li>
            <li>source : hash(destinataire)</li>
            <li>cible : Lien dissimulé chiffré</li>
            <li>méta : clé de déchiffrement du lien, chiffrée pour le destinataire</li>
        </ul>

        <h5 id="lsdrd">LSDRD / Registre dissimulé</h5>
        <p>Le registre du lien dissimulé est la partie utile du lien qui est protégée dans le lien de dissimulation.</p>
        <p>L'extraction du lien dissimulé se fait depuis le lien de dissimulation :</p>
        <ol>
            <li>L'entité destinataire vérifie que son identifiant est bien celui présenté par le champs
                <code>HashSource</code>.
            </li>
            <li>Le champs <code>HashMeta</code> est déchiffré (asymétrique) avec la clé privée de l'entité destinataire
                pour obtenir la clé de session.
            </li>
            <li>Le champs <code>HashCible</code> est déchiffré (symétrique) avec la clé de session pour obtenir le lien
                dissimulé.
            </li>
            <li>Le lien dissimulé obtenu ne contient pas les champs <code>Signature</code> et
                <code>HashSignataire</code> mais on peut garder ceux du lien de dissimulation 'pour affichage'.
            </li>
        </ol>
        <p>A faire...</p>
        <p>Le registre du lien dissimulé :</p>
        <ul>
            <li>Horodatage significatif</li>
            <li>action : tout sauf <code>c</code></li>
            <li>source : hash(objet source)</li>
            <li>cible : hash(objet cible)</li>
            <li>méta : hash(objet méta)</li>
        </ul>

        <h4 id="lsda">LSDA / Attaque sur la dissimulation</h4>
        <p>Le fait qu’une entité synchronise des liens dissimulés que d’autres entités partagent et les range dans des
            fichiers transcodés peut révéler l’ID de l’objet transcodé. Et par tâtonnement on peut retourner ainsi le
            transcodage de tous les objets.</p>
        <p>Il suffit qu’une entité attaquante génère un lien dissimulé à destination d’une entité attaquée concernant un
            objet en particulier. L’entité attaquée va alors ranger le lien dissimulé dans le fichier transcodé.
            L’entité attaquante peut alors rechercher quel fichier transcodé contient sont lien dissimulé et en déduire
            que ce fichier transcodé correspond à l’objet.</p>
        <p>En plus, si le lien dissimulé n’a aucune action valable, il ne sera pas exploité, donc pas détecté par
            l’entité attaquée.</p>
        <p>La solution implémentée pour palier à ce problème c'est la méthode dite de translation des liens
            dissimulés.</p>

        <h4 id="lsds">LSDS / Stockage et transcodage</h4>
        <p>Les liens dissimulés sont camouflés dans des liens de dissimulation, ils ne sont donc plus utilisables pour
            assurer le transfert entre entités et le tri dans les fichiers de stockage des liens.</p>
        <p>De plus, les liens de dissimulations ne doivent pas être stockés directement dans des fichiers de stockage
            des liens directement rattachés aux objets concernés, comme les autres liens, sous peine de dévoiler assez
            rapidement les identifiants des objets utilisés... et donc assez facilement le lien dissimulé correspondant.
            Cela poserait en plus un problème lors du nettoyage des liens parce qu'il faut avoir accès aux liens
            dissimulés pour correctement les ranger.</p>
        <p>Le nommage des fichiers contenant ces liens doit aussi être différent des entités signataires et
            destinataires des liens, et ce nommage peut par facilité faire référence simultanément à ces deux entités.
            Ainsi ces fichiers sont stockés dans le dossier des liens. Cette organisation et cette séparation des liens
            dans des fichiers clairement distincts répond au besoin d'utilisation. Et lors du nettoyage des liens, le
            traitement peut être différencié par rapport à la structure du nom des fichiers.</p>

        <h5 id="lsdst">LSDST / Translation de lien</h5>
        <p>La répartition des liens de dissimulation dans des fichiers attachés à l'entité émettrice et l'entité
            destinataire ne permet pas une exmploitation efficace et rapide des liens dissimulés. Il faut trouver un
            moyen d'associer les liens de dissimulations aux objets concernés par les liens dissimulés sans révéler
            publiquement ce lien. Une translation va permettre de camoufler cette association.</p>
        <p>La translation des liens dissimulés signifie la dissimulation par translation des identifiants des objets
            auxquels s'appliquent des liens dissimulés moyennant une clé de translation. Cette translation doit
            permettre de préserver la dissociation entre l'identifiant d'un objet et l'identifiant 'virtuel' auquel sont
            attachés les liens dissimulés.</p>
        <p>Le système de translation est basé sur une clé unique de translation par entité. Cette translation doit être
            une fonction à sens unique, donc à base de prise d’empreinte (hash). Elle doit maintenir la non association
            entre identifiants virtuels et réels des objets, y compris lorsqu’une ou plusieurs translations sont
            connues. Enfin, la translation doit être dépendante de l’entité qui les utilise, c’est à dire qu’une même
            clé peut être commune à plusieurs entités sans donner les mêmes translations.</p>
        <p>A faire...</p>

        <h5 id="lsdsp">LSDSP / Protection de translation</h5>
        <p>A faire...</p>

        <h4 id="lsdt">LSDT / Transfert et partage</h4>
        <p>A faire...</p>

        <h4 id="lsdc">LSDC / Compromission</h4>
        <p>A faire...</p>

        <h2 id="lt">LT / Transfert</h2>
        <p>A faire...</p>

        <h2 id="lv">LV / Vérification</h2>
        <p>La signature d’un lien doit être vérifiée lors de la fin de la réception du lien. La signature d’un lien
            devrait être vérifiée avant chaque utilisation de ce lien. Un lien avec une signature invalide doit être
            supprimé. Lors de la suppression d’un lien, les autres liens de cet objet ne sont pas supprimés et l'objet
            n'est pas supprimé. La vérification de la validité des objets est complètement indépendante de celle des
            liens, et inversement (cf <a href="#cl">CL</a> et <a href="#oov">OOV</a>).</p>
        <p>Toute modification de l’un des champs du lien entraîne l’invalidation de tout le lien.</p>

        <h2 id="lo">LO / Oubli</h2>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}
























