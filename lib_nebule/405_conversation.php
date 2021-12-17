<?php
declare(strict_types=1);
namespace Nebule\Library;

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
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();

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
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();
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
        if ($this->_configuration->getOption('permitWrite')
            && $this->_configuration->getOption('permitWriteObject')
            && $this->_configuration->getOption('permitWriteLink')
            && $this->_configuration->getOption('permitWriteConversation')
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
        if (!$this->_configuration->getOption('permitWrite')
            || !$this->_configuration->getOption('permitWriteLink')
            || !$this->_configuration->getOption('permitCreateLink')
            || !$this->_configuration->getOption('permitWriteConversation')
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
        if (!$this->_configuration->getOption('permitWrite')
            || !$this->_configuration->getOption('permitWriteLink')
            || !$this->_configuration->getOption('permitCreateLink')
            || !$this->_configuration->getOption('permitWriteConversation')
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
