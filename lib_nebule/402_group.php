<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\Node;

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
     * Toujours transmettre l'instance de la librairie nebule.
     * Si le groupe existe, juste préciser l'ID de celle-ci.
     * Si c'est un nouveau groupe à créer, mettre l'ID à 'new'.
     *
     * @param nebule  $nebuleInstance
     * @param string  $id
     * @param boolean $closed
     * @param bool    $obfuscated
     */
    public function __construct(nebule $nebuleInstance, string $id, bool $closed = false, bool $obfuscated = false)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_social = $nebuleInstance->getSocialInstance();

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
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_social = $nebuleInstance->getSocialInstance();
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
        if ($this->_configuration->getOptionAsBoolean('permitWrite')
            && $this->_configuration->getOptionAsBoolean('permitWriteObject')
            && $this->_configuration->getOptionAsBoolean('permitCreateObject')
            && $this->_configuration->getOptionAsBoolean('permitWriteLink')
            && $this->_configuration->getOptionAsBoolean('permitCreateLink')
            && $this->_configuration->getOptionAsBoolean('permitWriteGroup')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // calcul l'ID.
            $this->_id = $this->_nebuleInstance->getCryptoInstance()->hash($this->_nebuleInstance->getCryptoInstance()->getPseudoRandom(128)) . self::DEFAULT_SUFFIX_NEW_GROUP;

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
                $newLink->setObfuscate();
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
                    $newLink->setObfuscate();
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
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
        $links = $this->readLinksFilterFull_disabled(
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
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
            $newLink->setObfuscate();
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
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
        $links = $this->readLinksFilterFull_disabled(
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
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
            $newLink->setObfuscate();
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
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
        if (!$this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
            return false;
        }

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        if ($id == '') {
            $id = $this->_nebuleInstance->getCurrentEntity();
        }

        // Liste tous mes liens de définition de groupe dissimulé.
        $links = $this->readLinksFilterFull_disabled(
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
        if (!$this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
            return false;
        }

        // Vérifie que la création de liens est possible.
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
            $newLink->setObfuscate();
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
        if (!$this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
            return false;
        }

        // Vérifie que la création de liens est possible.
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
        $links = $this->readLinksFilterFull_disabled(
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
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
            $newLink->setObfuscate();
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
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
            $newLink->setObfuscate();
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
        $links = $this->readLinksFilterFull_disabled(
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
        $links = $this->readLinksFilterFull_disabled(
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
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
            $newLink->setObfuscate();
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
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_configuration->getOptionAsBoolean('permitCreateLink')
            || !$this->_configuration->getOptionAsBoolean('permitWriteGroup')
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
            $newLink->setObfuscate();
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
        $links = $this->readLinksFilterFull_disabled(
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
