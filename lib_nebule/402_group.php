<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * ------------------------------------------------------------------------------------------
 * Group class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *          To open a node as a group, create an instance with:
 *          - nebule instance;
 *          - valid node ID.
 *          To create a new group, create an instance with:
 *          - nebule instance;
 *          - node ID = '0'
 *          Every node can become a group without having been previously marked as a group.
 *          Create a link from an object to the group node is enough to create the group.
 * ------------------------------------------------------------------------------------------
 */
class Group extends Node implements nodeInterface {
    const DEFAULT_ICON_RID = '6e6562756c652f6f626a65742f67726f757065000000000000000000000000000000.none.272';

    const SESSION_SAVED_VARS = array(
            '_id',
            '_fullName',
            '_cacheProperty',
            '_cacheProperties',
            '_cacheMarkProtected',
            '_idProtected',
            '_idUnprotected',
            '_idProtectedKey',
            '_idUnprotectedKey',
            '_markProtectedChecked',
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

    protected function _initialisation(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->getReferenceObject();
        $this->getReferenceObjectClosed();
        $this->getReferenceObjectProtected();
        $this->getReferenceObjectObfuscated();

        if ($this->_isNew)
            $this->_createNewGroup();
        elseif ($this->_id != '0')
            $this->getIsGroup();
    }

    /**
     * Create a new group.
     *
     * @return void
     */
    protected function _createNewGroup(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked'))) {
            $this->_metrologyInstance->addLog('create group error no authorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, '8613d472');
            return;
        }

        $nid = $this->_cacheInstance->newVirtualNode();
        $this->_id = $nid->getID();
        $this->_metrologyInstance->addLog('create group ' . $this->_id, Metrology::LOG_LEVEL_AUDIT);

        $this->_data = null;
        $this->_haveData = false;
        $this->_isGroup = true;

        $nid2 = $this->_nebuleInstance->getFromDataNID($this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm'));
        $nid3 = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_HASH);
        $link = 'l>' . $this->_id . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link);
        $newBlockLink->signWrite();

        $nid2 = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE);
        $nid3 = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_TYPE);
        $link = 'l>' . $this->_id . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link);
        $newBlockLink->signWrite();
    }

    /**
     * Extrait l'ID de l'entité.
     * Filtre l'entité et s'assure que c'est une entité.
     *
     * @param string|Entity|Node $entity
     * @return string
     */
    protected function _checkExtractEntityID(\Nebule\Library\Node|string|Entity $entity): string {
        $entityInstance = null;
        if (is_string($entity)) {
            if (!Node::checkNID($entity, false, false))
                $id = '';
            else {
                $id = $entity;
                $entityInstance = $this->_cacheInstance->newNode($id, \Nebule\Library\Cache::TYPE_ENTITY);
            }
        } elseif (is_a($entity, 'Node')) {
            $id = $entity->getID();
            if ($id == '0')
                $id = '';
            else
                $entityInstance = $entity;
        } else
            $id = '';

        if ($id == '0')
            $id = '';

        if ($id != '' && !$entityInstance->getIsEntity('all'))
            $id = '';
        unset($entityInstance);

        return $id;
    }

    /**
     * Filtre l'objet.
     *
     * @param string|Node $object
     * @return string
     */
    private function _checkExtractObjectID($object): string {
        if (is_string($object)) {
            if (!Node::checkNID($object))
                $id = '';
            else
                $id = $object;
        } elseif (is_a($object, 'Node')) {
            $id = $object->getID();
            if ($id == '0')
                $id = '';
        } else
            $id = '';

        if ($id == '0')
            $id = '';

        return $id;
    }


    // Disable some functions.
    public function checkConsistency(): bool { return true; }
    public function getReloadMarkProtected(): bool { return false; }
    public function getProtectedID(): string { return '0'; }
    public function getUnprotectedID(): string { return $this->_id; }
    public function setProtected(bool $obfuscated = false, string $socialClass = ''): bool { return false; }
    public function setUnprotected(): bool { return false; }
    public function setProtectedTo($entity): bool { return false; }
    public function getProtectedTo(string $socialClass = ''): array { return array(); }


    /**
     * Ecrit l'objet comme n'étant plus un groupe.
     * TODO détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @return boolean
     */
    public function unsetGroup(): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        // Si déjà marqué, donne le résultat tout de suite.
        if (!$this->_isGroup)
            return true;

        // Création lien de suppression de groupe.
        $req = 'x';
        $nid1 = $this->_id;
        $nid2 = $this->getReferenceObject();
        $nid3 = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_TYPE);
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link);
        $newBlockLink->signWrite();

        $this->_isGroup = false;
        return true;
    }



    protected bool $_isMarkClosed = false;

    /**
     * Retourne si le groupe est marqué comme fermé.
     * En sélectionnant une entité, fait la recherche de marquage pour cette entité comme contributrice.
     *
     * @param Entity|null $entity
     * @return boolean
     */
    public function getMarkClosed(?Entity $entity = null): bool {
        $this->_metrologyInstance->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();

        // Liste tous mes liens de définition de groupe fermé.
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                $id,
                $this->getReferenceObjectClosed()
        );

        // Fait un tri par pertinence sociale. Forcé à myself.
        $this->_socialInstance->arraySocialFilter($links, 'myself');

        // Mémorise le résultat.
        if ($id == $this->_entitiesInstance->getGhostEntityEID()) {
            if (sizeof($links) != 0)
                $this->_isMarkClosed = true;
            else
                $this->_isMarkClosed = false;
        }

        // Retourne le résultat.
        if (sizeof($links) != 0)
            return true;
        return false;
    }

    /**
     * Ecrit l'objet comme un groupe fermé.
     *
     * @param Entity|null $entity
     * @param boolean     $obfuscated
     * @return boolean
     */
    public function setMarkClosed(?Entity $entity = null, bool $obfuscated = false): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated(''))
            $obfuscated = true;

        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->getMarkClosed())
            return true;

        // Création du lien de groupe.
        $req = 'l';
        $nid1 = $this->_id;
        $nid2 = $id;
        $nid3 = $this->getReferenceObjectClosed();
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link, $obfuscated);
        $this->_isMarkClosed = true;
        return $newBlockLink->signWrite();
    }

    /**
     * Ecrit l'objet comme n'étant pas un groupe fermé.
     * TODO détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @param Entity|null $entity
     * @return boolean
     */
    public function unsetMarkClosed(?Entity $entity = null): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();

        // Si déjà marqué, donne le résultat tout de suite.
        if (!$this->getMarkClosed())
            return true;

        // Création lien de suppression de groupe.
        $req = 'x';
        $nid1 = $this->_id;
        $nid2 = $id;
        $nid3 = $this->getReferenceObjectClosed();
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link);
        $this->_isMarkClosed = false;
        return $newBlockLink->signWrite();
    }



    protected bool $_isMarkProtected = false;

    /**
     * Retourne si le groupe est marqué comme protégé.
     * En sélectionnant une entité, fait la recherche de marquage pour cette entité comme contributrice.
     *
     * @param Entity|null $entity
     * @return boolean
     */
    public function getMarkProtected(?Entity $entity = null): bool {
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();

        // Liste tous mes liens de définition de groupe protégé.
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                $id,
                $this->getReferenceObjectProtected()
        );

        // Fait un tri par pertinance sociale. Forcé à myself.
        $this->_socialInstance->arraySocialFilter($links, 'myself');

        // Mémorise le r&sultat.
        if ($id == $this->_entitiesInstance->getGhostEntityEID()) {
            if (sizeof($links) != 0)
                $this->_isMarkProtected = true;
            else
                $this->_isMarkProtected = false;
        }

        // Retourne le résultat.
        if (sizeof($links) != 0)
            return true;
        return false;
    }

    /**
     * Ecrit l'objet comme un groupe protégé.
     *
     * @param Entity|null $entity
     * @param boolean            $obfuscated
     * @return boolean
     */
    public function setMarkProtected(?Entity $entity = null, bool $obfuscated = false): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated($entity))
            $obfuscated = true;

        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->getMarkProtected())
            return true;

        // Création du lien de groupe.
        $req = 'l';
        $nid1 = $this->_id;
        $nid2 = $id;
        $nid3 = $this->getReferenceObjectProtected();
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link, $obfuscated);
        $this->_isMarkProtected = true;
        return $newBlockLink->signWrite();
    }

    /**
     * Ecrit l'objet comme n'étant pas un groupe protégé.
     * TODO détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @param Entity|null $entity
     * @return boolean
     */
    public function unsetMarkProtected(?Entity $entity = null): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();

        // Si déjà marqué, donne le résultat tout de suite.
        if (!$this->getMarkProtected())
            return true;

        // Création lien de suppression de groupe.
        $req = 'x';
        $nid1 = $this->_id;
        $nid2 = $id;
        $nid3 = $this->getReferenceObjectProtected();
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link);
        $this->_isMarkProtected = false;
        return $newBlockLink->signWrite();
    }



    protected bool $_isMarkObfuscated = false;

    /**
     * Retourne si le groupe est marqué comme dissimulé.
     * En sélectionnant une entité, fait la recherche de marquage pour cette entité comme contributrice.
     *
     * @param Entity|null $entity
     * @return boolean
     */
    public function getMarkObfuscated(?Entity $entity = null): bool {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
            return false;

        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();

        // Liste tous mes liens de définition de groupe dissimulé.
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                $id,
                $this->getReferenceObjectObfuscated()
        );

        // Fait un tri par pertinance sociale. Forcé à myself.
        $this->_socialInstance->arraySocialFilter($links, 'myself');

        // Mémorise le r&sultat.
        if ($id == $this->_entitiesInstance->getGhostEntityEID()) {
            if (sizeof($links) != 0)
                $this->_isMarkObfuscated = true;
            else
                $this->_isMarkObfuscated = false;
        }

        // Retourne le résultat.
        if (sizeof($links) != 0)
            return true;
        return false;
    }

    /**
     * Ecrit l'objet comme un groupe dissimulé.
     *
     * @param Entity|null $entity
     * @param boolean     $obfuscated
     * @return boolean
     */
    public function setMarkObfuscated(?Entity $entity = null, bool $obfuscated = false): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitObfuscatedLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated(''))
            $obfuscated = true;

        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();

        // Si déjà marqué, donne le résultat tout de suite.
        if ($this->getMarkObfuscated())
            return true;

        // Création du lien de groupe.
        $req = 'l';
        $nid1 = $this->_id;
        $nid2 = $id;
        $nid3 = $this->getReferenceObjectObfuscated();
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link, $obfuscated);
        $this->_isMarkObfuscated = true;
        return $newBlockLink->signWrite();
    }

    /**
     * Ecrit l'objet comme n'étant pas un groupe dissimulé.
     * TODO détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @param Entity|null $entity
     * @return boolean
     */
    public function unsetMarkObfuscated(?Entity $entity = null): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitObfuscatedLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();

        // Si déjà marqué, donne le résultat tout de suite.
        if (!$this->getMarkObfuscated())
            return true;

        // Création lien de suppression de groupe.
        $req = 'x';
        $nid1 = $this->_id;
        $nid2 = $id;
        $nid3 = $this->getReferenceObjectObfuscated();
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link);
        $this->_isMarkObfuscated = false;
        return $newBlockLink->signWrite();
    }


    /**
     * Retourne si l'objet est membre du groupe.
     *
     * @param string|Node $object
     * @param string      $socialClass
     * @return boolean
     */
    public function getIsMember(\Nebule\Library\Node|string $object, string $socialClass = ''): bool {
        // Extrait l'ID de l'objet.
        $id = $this->_checkExtractObjectID($object);

        // Vérifie que c'est bien un objet.
        if ($id == '')
            return false;

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                $id,
                $this->_id
        );

        // Fait un tri par pertinence sociale.
        $this->_socialInstance->arraySocialFilter($links, $socialClass);

        if (sizeof($links) != 0)
            return true;
        return false;
    }

    /**
     * Ajoute un objet comme membre dans le groupe.
     *
     * @param string|Node $object
     * @param boolean     $obfuscated
     * @return boolean
     */
    public function setMember(\Nebule\Library\Node|string $object, bool $obfuscated = false): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated(''))
            $obfuscated = true;

        // Extrait l'ID de l'objet.
        $id = $this->_checkExtractObjectID($object);

        // Vérifie que c'est bien un objet.
        if ($id == '')
            return false;

        // Création lien de groupe.
        $req = 'l';
        $nid1 = $this->_id;
        $nid2 = $id;
        $nid3 = $this->_id;
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link, $obfuscated);
        $newBlockLink->sign();
        return $newBlockLink->write();
    }

    /**
     * Retire un membre du groupe.
     * TODo détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     * TODO retirer la dissimulation déjà faite dans le code.
     *
     * @param string|Node $object
     * @param boolean     $obfuscated
     * @return boolean
     */
    public function unsetMember(\Nebule\Library\Node|string $object = '', bool $obfuscated = false): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated(''))
            $obfuscated = true;

        // Extrait l'ID de l'objet.
        $id = $this->_checkExtractObjectID($object);

        // Vérifie que c'est bien un objet.
        if ($id == '')
            return false;

        // Création lien de groupe.
        $req = 'x';
        $nid1 = $this->_id;
        $nid2 = $id;
        $nid3 = $this->_id;
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link, $obfuscated);
        return $newBlockLink->signWrite();
    }


    /**
     * Extrait la liste des liens définissant les objets du groupe.
     * Le calcul social se fait par rapport à la classe sociale demandée,
     *   et donc utilise l'entité de _nebuleInstance ou de _applicationInstance en fonction.
     *
     * @param string $socialClass
     * @param array  $socialListID
     * @return array:Link
     */
    public function getListMembersLinks(string $socialClass = '', array $socialListID = array()): array {
        // Liste tous les liens des membres de la conversation.
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                '',
                $this->_id
        );

        // Fait un tri par pertinence sociale.
        $this->_socialInstance->setList($socialListID);
        $this->_socialInstance->arraySocialFilter($links, $socialClass);
        $this->_socialInstance->unsetList();

        return $links;
    }

    /**
     * Extrait la liste des ID des objets du groupe.
     *
     * @param string $socialClass
     * @param array  $socialListID
     * @return array:string
     */
    public function getListMembersID(string $socialClass = '', array $socialListID = array()): array {
        // Extrait les liens des groupes.
        $links = $this->getListMembersLinks($socialClass, $socialListID);

        // Extraction des ID cibles.
        $list = array();
        foreach ($links as $link)
            $list[$link->getParsed()['bl/rl/nid2']] = $link->getParsed()['bl/rl/nid2'];

        return $list;
    }

    /**
     * Retourne le nombre d'objets dans le groupe.
     *
     * @param string $socialClass
     * @param array  $socialListID
     * @return float
     */
    public function getCountMembers(string $socialClass = '', array $socialListID = array()): float {
        return sizeof($this->getListMembersLinks($socialClass, $socialListID));
    }


    /**
     * Retourne si l'entité est à l'écoute du groupe.
     *
     * @param string|Node $entity
     * @param string      $socialClass
     * @param array       $socialListID
     * @return boolean
     */
    public function getIsFollower(\Nebule\Library\Node|string $entity, string $socialClass = '', array $socialListID = array()): bool {
        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        // Vérifie que c'est bien une entité.
        if ($id == '')
            return false;

        // Liste tous les liens de définition des entités à l'écoutes du groupe.
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $id,
                $this->_id,
                $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI)
        );

        // Fait un tri par pertinance sociale.
        $this->_socialInstance->setList($socialListID);
        $this->_socialInstance->arraySocialFilter($links, $socialClass);
        $this->_socialInstance->unsetList();

        if (sizeof($links) != 0)
            return true;
        return false;
    }

    /**
     * Ajoute une entité comme à l'écoute du groupe.
     *
     * @param string  $entity
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setFollower(string $entity, bool $obfuscated = false): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated(''))
            $obfuscated = true;

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        // Vérifie que c'est bien une entité.
        if ($id == '')
            return false;

        // Création lien de groupe.
        $req = 'l';
        $nid1 = $id;
        $nid2 = $this->_id;
        $nid3 = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI);
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3; // FIXME
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link, $obfuscated);
        return $newBlockLink->signWrite();
    }

    /**
     * Retire une entité à l'écoute du groupe.
     * TODO détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     * TODO retirer la dissimulation déjà faite dans le code.
     *
     * @param string  $entity
     * @param boolean $obfuscated
     * @return boolean
     */
    public function unsetFollower(string $entity = '', bool $obfuscated = false): bool {
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;

        // Si la dissimulation est activée, la force.
        if ($this->getMarkObfuscated(''))
            $obfuscated = true;

        // Extrait l'ID de l'entité.
        $id = $this->_checkExtractEntityID($entity);

        // Vérifie que c'est bien une entité.
        if ($id == '')
            return false;

        // Création lien de groupe.
        $req = 'x';
        $nid1 = $id;
        $nid2 = $this->_id;
        $nid3 = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI);
        $link = $req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3;
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new');
        $newBlockLink->addLink($link, $obfuscated);
        return $newBlockLink->signWrite();
    }


    /**
     * Extrait la liste des liens définissant les entités à l'écoute du groupe.
     * On ne peut pas voir un groupe comme fermé si on regarde pour une autre entité.
     * La pertinence sociale n'est pas utilisée pour un groupe fermé.
     *
     * @param string $socialClass
     * @param array  $socialListID
     * @return array:Link
     */
    public function getListFollowersLinks(string $socialClass = '', array $socialListID = array()): array {
        return $this->_getListFollowersLinks($this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI), $socialClass, $socialListID);
    }

    /**
     * Extrait la liste des liens définissant les entités à l'écoute d'un objet et définit par une référence de suivi.
     * L'objet définit par une référence de suivi doit se comporter comme un groupe.
     *
     * @param string $reference
     * @param string $socialClass
     * @param array  $socialListID
     * @return array:Link
     */
    protected function _getListFollowersLinks(string $reference, string $socialClass = '', array $socialListID = array()): array {
        // Vérifie la référence.
        if (!Node::checkNID($reference))
            $reference = $this->_nebuleInstance->getFromDataNID($reference);

        // Liste tous les liens des entités à l'écoutes du groupe.
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                '',
                $this->_id,
                $reference
        );

        // Fait un tri par pertinance sociale.
        $this->_socialInstance->setList($socialListID);
        $this->_socialInstance->arraySocialFilter($links, $socialClass);
        $this->_socialInstance->unsetList();

        return $links;
    }

    /**
     * Extrait la liste des ID des entités à l'écoute du groupe.
     *
     * @param string $socialClass
     * @param array  $socialListID
     * @return array:string
     */
    public function getListFollowersID(string $socialClass = '', array $socialListID = array()): array {
        // Extrait les liens des groupes.
        $links = $this->_getListFollowersLinks($this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI), $socialClass, $socialListID);

        // Extraction des ID cibles.
        $list = array();
        foreach ($links as $link)
            $list[$link->getParsed()['bl/rl/nid1']] = $link->getParsed()['bl/rl/nid1'];

        return $list;
    }

    /**
     * Retourne le nombre d'entités à l'écoute du groupe.
     *
     * @param string $socialClass
     * @param array  $socialListID
     * @return float
     */
    public function getCountFollowers(string $socialClass = '', array $socialListID = array()): float {
        return sizeof($this->_getListFollowersLinks($this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI), $socialClass, $socialListID));
    }

    /**
     * Retourne la liste des entités qui ont ajouté l'entité cité comme suiveuse du groupe.
     *
     * @param string $entity
     * @param string $socialClass
     * @param array  $socialListID
     * @return array:string
     */
    public function getListFollowerAddedByID(string $entity, string $socialClass = 'all', array $socialListID = array()): array {
        // Extrait les liens des groupes.
        $links = $this->_getListFollowersLinks($this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI), $socialClass, $socialListID);

        // Extraction des ID cibles.
        $list = array();
        foreach ($links as $link) {
            if ($link->getParsed()['bl/rl/nid1'] == $entity)
                $list[$link->getParsed()['bs/rs1/eid']] = $link->getParsed()['bs/rs1/eid'];
        }

        return $list;
    }


    /**
     * ID de référence de l'objet.
     *
     * @var string
     */
    private string $_referenceObject = '';

    /**
     * Calcule et retourne la référence de l'objet.
     *
     * @return string
     */
    public function getReferenceObject(): string {
        if ($this->_referenceObject == '')
            $this->_referenceObject = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE);
        return $this->_referenceObject;
    }

    /**
     * ID de référence de l'objet de fermeture.
     *
     * @var string
     */
    private string $_referenceObjectClosed = '';

    /**
     * Calcule et retourne la référence de l'objet de fermeture.
     *
     * @return string
     */
    public function getReferenceObjectClosed(): string {
        if ($this->_referenceObjectClosed == '')
            $this->_referenceObjectClosed = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
        return $this->_referenceObjectClosed;
    }

    /**
     * ID de référence de l'objet de protection des membres.
     *
     * @var string
     */
    private string $_referenceObjectProtected = '';

    /**
     * Calcule et retourne la référence de l'objet de protection des membres.
     *
     * @return string
     */
    public function getReferenceObjectProtected(): string {
        if ($this->_referenceObjectProtected == '')
            $this->_referenceObjectProtected = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_PROTEGE);
        return $this->_referenceObjectProtected;
    }

    /**
     * ID de référence de l'objet de dissimulation des membres.
     *
     * @var string
     */
    private string $_referenceObjectObfuscated = '';

    /**
     * Calcule et retourne la référence de l'objet de dissimulation des membres.
     *
     * @return string
     */
    public function getReferenceObjectObfuscated(): string {
        if ($this->_referenceObjectObfuscated == '')
            $this->_referenceObjectObfuscated = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_DISSIMULE);
        return $this->_referenceObjectObfuscated;
    }
}



abstract class HelpGroup {
    static public function echoDocumentationTitles(): void {
        ?>

        <li><a href="#og">OG / Groupe</a>
            <ul>
                <li><a href="#ogo">OGO / Objet</a></li>
                <li><a href="#ogn">OGN / Nommage</a></li>
                <li><a href="#ogp">OGP / Protection</a></li>
                <li><a href="#ogd">OGD / Dissimulation</a></li>
                <li><a href="#ogf">OGF / Fermeture</a></li>
                <li><a href="#ogm">OGP / Membres</a>
                    <ul>
                <li><a href="#ogmp">OGMP / Protection des membres</a></li>
                <li><a href="#ogmd">OGMD / Dissimulation des membres</a></li>
                    </ul>
                </li>
                <li><a href="#ogl">OGL / Liens</a></li>
                <li><a href="#ogc">OGC / Création</a></li>
                <li><a href="#ogs">OGS / Stockage</a></li>
                <li><a href="#ogt">OGT / Transfert</a></li>
                <li><a href="#ogr">OGR / Réservation</a></li>
                <li><a href="#ogi">OGI / Implémentation</a>
                    <ul>
                        <li><a href="#ogio">OGIO / Implémentation des Options</a></li>
                        <li><a href="#ogia">OGIA / Implémentation des Actions</a></li>
                    </ul>
                </li>
            </ul>
        </li>

        <?php
    }

    static public function echoDocumentationCore(): void {
        ?>

        <?php Displays::docDispTitle(2, 'og', 'Groupe'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <p>Le groupe est un objet définit comme tel, c'est-à-dire qu’il doit avoir un type mime <code>nebule/objet/groupe</code>.
        </p>
        <p>Fondamentalement, le groupe est un ensemble de plusieurs objets. C'est-à-dire, c’est le regroupement d’au
            moins deux objets. Le lien peut donc à ce titre être vu comme la matérialisation d’un groupe. Mais la
            définition du groupe doit être plus restrictive afin que celui-ci soit utilisable. Pour cela, dans <em>nebule</em>,
            le groupe n’est reconnu comme tel uniquement s'il est marqué de son type mime. Il est cependant possible
            d’instancier explicitement un objet comme groupe et de l’utiliser comme tel en cas de besoin.</p>
        <p>Le groupe va permettre de regrouper, et donc d’associer et de retrouver, des objets. L’objet du groupe va
            avoir des liens vers d’autres objets afin de les définir comme membres du groupe.</p>
        <p>Un groupe peut avoir des liens de membres vers des objets définis aussi comme groupes. Ces objets peuvent
            être vus comme des sous-groupes. La bibliothèque <em>nebule</em> ne prend en compte qu’un seul niveau de
            groupe, c'est-à-dire que les sous-groupes sont gérés simplement comme des objets.</p>

        <?php Displays::docDispTitle(3, 'ogo', 'Objet'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’objet du groupe peut être de deux natures.</p>
        <p>Soit c’est un objet existant qui est en plus définit comme un groupe. L’objet peut avoir un contenu et a
            sûrement d’autres types mime propres. Dans ce cas l’identifiant de groupe est l’identifiant de l’objet
            utilisé.</p>
        <p>Soit c’est un objet dit virtuel qui n’a pas et n’aura jamais de contenu. Cela n’empêche pas qu’il puisse
            avoir d’autres types mime. Dans ce cas l’identifiant de groupe a une forme commune aux objets virtuels.</p>
        <p>La création d’un objet virtuel comme groupe se fait en créant pour identifiant la concaténation d’un hash
            (<em>sha256</em>) d’une valeur aléatoire de 128bits et de la chaîne <code>006e6562756c652f6f626a65742f67726f757065</code>.
            Soit un identifiant complet de la taille de 104 caractères.</p>

        <?php Displays::docDispTitle(3, 'ogn', 'Nommage'); ?>
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

        <?php Displays::docDispTitle(3, 'ogp', 'Protection'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>En tant que tel le groupe ne nécessite pas de protection puisque soit l’objet du groupe n’a pas de contenu
            soit on n’utilise pas son contenu directement.</p>
        <p>La gestion de la protection est désactivée dans une instance de groupe.</p>

        <?php Displays::docDispTitle(3, 'ogd', 'Dissimulation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le groupe peut en tant que tel être dissimulé, c'est-à-dire que l’on dissimule l’existence du groupe, donc sa
            création.</p>
        <p>La dissimulation devrait se faire lors de la création du groupe.</p>
        <p>L’annulation de la dissimulation d’un groupe revient à révéler le lien de création du groupe.</p>
        <p>La dissimulation peut se (re)faire après la création du groupe, mais son efficacité est incertaine si les
            liens de création ont déjà été diffusés. En cas de dissimulation à posteriori, il faut générer un lien de
            suppression du groupe puis générer un nouveau lien dissimulé de création du groupe à une date postérieure au
            lien de suppression.</p>

        <?php Displays::docDispTitle(3, 'ogf', 'Fermeture'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le groupe va contenir un certain nombre de membres ajoutés par différentes entités. Il est possible de
            limiter le nombre des membres à utiliser dans un groupe en restreignant artificiellement les entités
            contributrices du groupe. Ainsi, on marque le groupe comme fermé et on filtre sur les membres uniquement
            ajoutés par des entités définies.</p>
        <p>Dans nebule, l’objet réservé <code>nebule/objet/groupe/ferme</code> est dédié à la gestion des groupes
            fermés. Un groupe est considéré comme fermé quand on a l’objet réservé en champs méta, l’entité en cours en
            champs cible et l’ID du groupe en champs source. Si au lieu d’utiliser l’entité en cours pour le champs
            cible, on utilise une autre entité, cela revient à prendre aussi en compte ses liens dans le groupe fermé.
            Dans ce cas, c’est une entité contributrice.</p>
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

        <?php Displays::docDispTitle(3, 'ogm', 'Membres'); ?>
        <?php Displays::docDispTitle(4, 'ogmp', 'Protection des membres'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le groupe va contenir un certain nombre de membres ajoutés par différentes entités. Il est possible de
            limiter la visibilité du contenu des membres utilisés dans un groupe en restreignant artificiellement les
            entités destinataires qui pourront les consulter.</p>
        <p>Dans nebule, l’objet réservé <code>nebule/objet/groupe/protege</code> est dédié à la gestion des groupes
            protégés. Un groupe est considéré protégé quand on a l’objet réservé en champs méta, l’entité en cours en
            champs cible et l’ID du groupe en champs source. Si au lieu d’utiliser l’entité en cours pour le champs
            cible, on utilise une autre entité, cela revient à partager aussi les objets protégés créés pour ce groupe.
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

        <?php Displays::docDispTitle(4, 'ogmd', 'Dissimulation des membres'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le groupe va contenir un certain nombre de membres ajoutés par différentes entités. Il est possible de
            limiter la visibilité de l’appartenance des membres utilisés dans un groupe en restreignant artificiellement
            les entités destinataires qui pourront les voir.</p>
        <p>Dans nebule, l’objet réservé <code>nebule/objet/groupe/dissimule</code> est dédié à la gestion des groupes
            dissimulés. Un groupe est considéré comme dissimulé quand on a l’objet réservé en champs méta, l’entité en
            cours en champs cible et l’ID du groupe en champs source. Si au lieu d’utiliser l’entité en cours pour le
            champs cible, on utilise une autre entité, cela revient à partager aussi les objets dissimulés créés pour ce
            groupe. Cela ne repartage pas la dissimulation des objets déjà dissimulés.</p>
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

        <?php Displays::docDispTitle(3, 'ogl', 'Liens'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
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

        <?php Displays::docDispTitle(3, 'ogc', 'Création'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
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

        <?php Displays::docDispTitle(3, 'ogs', 'Stockage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <?php Displays::docDispTitle(3, 'ogt', 'Transfert'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'ogr', 'Réservation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les objets réservés spécifiquement pour les groupes :</p>
        <ul>
            <li>nebule/objet/groupe</li>
            <li>nebule/objet/groupe/ferme</li>
            <li>nebule/objet/groupe/protege</li>
            <li>nebule/objet/groupe/dissimule</li>
        </ul>

        <?php Displays::docDispTitle(3, 'ogi', 'Implémentation'); ?>
        <?php Displays::docDispTitle(4, 'ogio', 'Implémentation des Options'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
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

        <?php Displays::docDispTitle(4, 'ogia', 'Implémentation des Actions'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
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
