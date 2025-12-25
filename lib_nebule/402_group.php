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
 *          - And call setAsGroup() to write it.
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
        if ($this->_isNew)
            $this->_createNewGroup();
        elseif ($this->_id != '0')
            $this->getIsGroup();
    }

    protected function _createNewGroup(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked'))) {
            $this->_metrologyInstance->addLog('create group error no authorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, '8613d472');
            return;
        }
        $instance = $this->_cacheInstance->newVirtualNode();
        $this->_id = $instance->getID();
        $this->_metrologyInstance->addLog('create group ' . $this->_id, Metrology::LOG_LEVEL_AUDIT);
        if ($this->getIsRID($this->_id)) {
            $this->_data = null;
            $this->_haveData = false;
        }
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
     * Mark as a group with a link.
     * @param bool $obfuscated
     * @return boolean
     */
    public function setAsGroup(bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        $this->_isGroup = true;
        $this->_metrologyInstance->addLog('set nid=' . $this->_id . ' as group', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '763e0c40');
        return $this->addLink(
                'l>' . $this->_id
                . '>' . References::RID_OBJECT_GROUP
                . '>' . References::RID_OBJECT_TYPE,
                $obfuscated);
    }

    /**
     * Remove the mark as a group with a link.
     * @param bool $obfuscated
     * @return boolean
     */
    public function unsetAsGroup(bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        $this->_isGroup = false;
        // TODO detect previously obfuscated link.
        $this->_metrologyInstance->addLog('unset nid=' . $this->_id . ' as group', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'ae79d237');
        return $this->addLink(
                'x>' . $this->_id
                . '>' . References::RID_OBJECT_GROUP
                . '>' . References::RID_OBJECT_TYPE,
                $obfuscated);
    }

    /**
     * Mark as a group of entities with links.
     * @param bool $obfuscated
     * @return boolean
     */
    public function setAsGroupOfEntities(bool $obfuscated = false): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup', 'unlocked')))
            return false;
        $this->_metrologyInstance->addLog('set nid=' . $this->_id . ' as group of entities', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '6636179a');
        return ($this->setAsGroup($obfuscated)
                && $this->addLink(
                        'l>' . $this->_id
                        . '>' . $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_ENTITE)
                        . '>' . References::RID_OBJECT_TYPE,
                        $obfuscated));
    }

    /**
     * Remove the mark as a group of entities with links.
     * @param bool $obfuscated
     * @return boolean
     */
    public function unsetAsGroupOfEntities(bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        $this->_metrologyInstance->addLog('unset nid=' . $this->_id . ' as group of entities', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'de5c0565');
        return ($this->unsetAsGroup($obfuscated)
                && $this->addLink(
                        'x>' . $this->_id
                        . '>' . $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_ENTITE)
                        . '>' . References::RID_OBJECT_TYPE,
                        $obfuscated));
    }



    protected bool $_isMarkClosed = false;

    /**
     * Retourne si le groupe est marqué comme fermé.
     * En sélectionnant une entité, fait la recherche de marquage pour cette entité comme contributrice.
     *
     * @param Entity|null $entity
     * @return boolean
     */
    public function getMarkClosedGroup(?Entity $entity = null): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                $id,
                $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME)
        );
        $this->_socialInstance->arraySocialFilter($links, 'myself');
        if ($id == $this->_entitiesInstance->getGhostEntityEID()) {
            if (sizeof($links) != 0)
                $this->_isMarkClosed = true;
            else
                $this->_isMarkClosed = false;
        } // FIXME _isMarkClosed
        if (sizeof($links) != 0) {
            $this->_metrologyInstance->addLog('group ' . $this->_id . ' is closed', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9ace1a1c');
            return true;
        }
        $this->_metrologyInstance->addLog('group ' . $this->_id . ' is not closed', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '23a4e2d9');
        return false;
    }

    /**
     * Ecrit l'objet comme un groupe fermé.
     *
     * @param Entity|null $entity
     * @param boolean     $obfuscated
     * @return boolean
     */
    public function setMarkClosedGroup(?Entity $entity = null, bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        if ($this->getMarkObfuscatedGroup())
            $obfuscated = true;
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();
        if ($this->getMarkClosedGroup())
            return true;
        $this->_metrologyInstance->addLog('set group=' . $this->_id . ' as close', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'd5a5dcc0');
        return $this->addLink(
                'l>' . $this->_id . '>' . $id . '>' . $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME),
                $obfuscated);
    }

    /**
     * Ecrit l'objet comme n'étant pas un groupe fermé.
     * TODO détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @param Entity|null $entity
     * @param bool        $obfuscated
     * @return boolean
     */
    public function unsetMarkClosedGroup(?Entity $entity = null, bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();
        if (!$this->getMarkClosedGroup())
            return true;
        $this->_metrologyInstance->addLog('set group=' . $this->_id . ' as open', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '27c698ab');
        return $this->addLink('x>' . $this->_id . '>' . $id . '>' . $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME), $obfuscated);
    }



    protected bool $_isMarkProtected = false;

    /**
     * Retourne si le groupe est marqué comme protégé.
     * En sélectionnant une entité, fait la recherche de marquage pour cette entité comme contributrice.
     *
     * @param Entity|null $entity
     * @return boolean
     */
    public function getMarkProtectedGroup(?Entity $entity = null): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                $id,
                $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_PROTEGE)
        );
        $this->_socialInstance->arraySocialFilter($links, 'myself');
        if ($id == $this->_entitiesInstance->getGhostEntityEID()) {
            if (sizeof($links) != 0)
                $this->_isMarkProtected = true;
            else
                $this->_isMarkProtected = false;
        }
        return $this->_isMarkProtected;
    }

    /**
     * Ecrit l'objet comme un groupe protégé.
     *
     * @param Entity|null $entity
     * @param boolean            $obfuscated
     * @return boolean
     */
    public function setMarkProtectedGroup(?Entity $entity = null, bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        if ($this->getMarkObfuscatedGroup($entity))
            $obfuscated = true;
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();
        if ($this->getMarkProtectedGroup())
            return true;
        $this->_isMarkProtected = true;
        return $this->addLink(
                'l>' . $this->_id . '>' . $id . '>' . $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_PROTEGE),
                $obfuscated);
    }

    /**
     * Ecrit l'objet comme n'étant pas un groupe protégé.
     * TODO détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @param Entity|null $entity
     * @param bool        $obfuscated
     * @return boolean
     */
    public function unsetMarkProtectedGroup(?Entity $entity = null, bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();
        if (!$this->getMarkProtectedGroup())
            return true;
        $this->_isMarkProtected = false;
        return $this->addLink('x>' . $this->_id . '>' . $id . '>' . $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_PROTEGE), $obfuscated);
    }



    protected bool $_isMarkObfuscated = false;

    /**
     * Retourne si le groupe est marqué comme dissimulé.
     * En sélectionnant une entité, fait la recherche de marquage pour cette entité comme contributrice.
     *
     * @param Entity|null $entity
     * @return boolean
     */
    public function getMarkObfuscatedGroup(?Entity $entity = null): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
            return false;
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                $id,
                $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_DISSIMULE)
        );
        $this->_socialInstance->arraySocialFilter($links, 'myself');
        if ($id == $this->_entitiesInstance->getGhostEntityEID()) {
            if (sizeof($links) != 0)
                $this->_isMarkObfuscated = true;
            else
                $this->_isMarkObfuscated = false;
        }
        return $this->_isMarkObfuscated;
    }

    /**
     * Ecrit l'objet comme un groupe dissimulé.
     *
     * @param Entity|null $entity
     * @param boolean     $obfuscated
     * @return boolean
     */
    public function setMarkObfuscatedGroup(?Entity $entity = null, bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitObfuscatedLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        if ($this->getMarkObfuscatedGroup())
            $obfuscated = true;
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();
        if ($this->getMarkObfuscatedGroup())
            return true;
        $this->_isMarkObfuscated = true;
        return $this->addLink(
                'l>' . $this->_id . '>' . $id . '>' . $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_DISSIMULE),
                $obfuscated);
    }

    /**
     * Ecrit l'objet comme n'étant pas un groupe dissimulé.
     * TODO détecter le lien dissimulé d'origine, et dissimuler en conséquence.
     *
     * @param Entity|null $entity
     * @param bool        $obfuscated
     * @return boolean
     */
    public function unsetMarkObfuscatedGroup(?Entity $entity = null, bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitObfuscatedLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        if ($entity != null)
            $id = $entity->getID();
        else
            $id = $this->_entitiesInstance->getGhostEntityEID();
        if (!$this->getMarkObfuscatedGroup())
            return true;
        $this->_isMarkObfuscated = false;
        return $this->addLink('x>' . $this->_id . '>' . $id . '>' . $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_DISSIMULE), $obfuscated);
    }


    /**
     * Get if the object is a typed member of the group.
     * @param string $nid
     * @param string $type
     * @param string $socialClass
     * @return boolean
     */
    public function getIsMemberTypedNID(string $nid, string $type, string $socialClass = ''): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!Node::checkNID($nid) || !$this->getIsRID($type))
            return false;
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                $nid,
                $type
        );
        $this->_socialInstance->arraySocialFilter($links, $socialClass);
        if (sizeof($links) != 0)
            return true;
        return false;
    }

    /**
     * Add the object as a typed member of the group.
     * @param string  $nid
     * @param string  $type
     * @param boolean $obfuscated
     * @return boolean
     */
    public function setAsTypedMemberNID(string $nid, string $type, bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        if (!Node::checkNID($nid) || !$this->getIsRID($type))
            return false;
        if ($this->getMarkObfuscatedGroup())
            $obfuscated = true;
        $this->_metrologyInstance->addLog('add member=' . $nid . ' to group=' . $this->_id . ' with type=' . $type, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '695d463e');
        return $this->addLink(
                'l>' . $this->_id . '>' . $nid . '>' . $type,
                $obfuscated);
    }

    /**
     * Remove the object as a member of the group.
     * @param string  $nid
     * @param string  $type
     * @param boolean $obfuscated
     * @return boolean
     */
    public function unsetAsTypedMemberNID(string $nid, string $type, bool $obfuscated = false): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink', 'permitWriteGroup' , 'unlocked')))
            return false;
        if (!Node::checkNID($nid) || !$this->getIsRID($type))
            return false;
        if ($this->getMarkObfuscatedGroup())
            $obfuscated = true;
        // TODO detect previously obfuscated link.
        $this->_metrologyInstance->addLog('remove member=' . $nid . ' to group=' . $this->_id . ' with type=' . $type, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'c23f3303');
        return $this->addLink(
                'x>' . $this->_id . '>' . $nid . '>' . $type,
                $obfuscated);
    }

    public function getIsMemberTyped(Node $object, string $type, string $socialClass = ''): bool { return $this->getIsMemberTypedNID($object->getID(), $type, $socialClass); }
    public function setAsTypedMember(Node $object, string $type, bool $obfuscated = false): bool { return $this->setAsTypedMemberNID($object->getID(), $type, $obfuscated); }
    public function unsetAsTypedMember(Node $object, string $type, bool $obfuscated = false): bool { return $this->unsetAsTypedMemberNID($object->getID(), $type, $obfuscated); }
    public function getIsMember(Node $object, string $socialClass = ''): bool { return $this->getIsMemberTypedNID($object->getID(), $this->_id, $socialClass); }
    public function getIsMemberNID(string $nid, string $socialClass = ''): bool { return $this->getIsMemberTypedNID($nid, $this->_id, $socialClass); }
    public function setAsMember(Node $object, bool $obfuscated = false): bool { return $this->setAsTypedMemberNID($object->getID(), $this->_id, $obfuscated); }
    public function setAsMemberNID(string $nid, bool $obfuscated = false): bool { return $this->setAsTypedMemberNID($nid, $this->_id, $obfuscated); }
    public function unsetAsMember(Node $object, bool $obfuscated = false): bool { return $this->setAsTypedMemberNID($object->getID(), $this->_id, $obfuscated); }
    public function unsetAsMemberNID(string $nid, bool $obfuscated = false): bool { return $this->setAsTypedMemberNID($nid, $this->_id, $obfuscated); }



    /**
     * Get the list of typed member's links on the group.
     * @param string $type
     * @param string $socialClass
     * @param array  $socialListID
     * @return array:Link
     */
    public function getListTypedMembersLinks(string $type, string $socialClass = '', array $socialListID = array()): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->getIsRID($type))
            return array();
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                $this->_id,
                '',
                $type
        );
        $this->_socialInstance->setList($socialListID, $socialClass);
        $this->_socialInstance->arraySocialFilter($links, $socialClass);
        $this->_socialInstance->unsetList($socialClass);
        return $links;
    }
    public function getListMembersLinks(string $socialClass = '', array $socialListID = array()): array { return $this->getListTypedMembersLinks($this->_id, $socialClass, $socialListID); }

    /**
     * Get the list of typed members (NID) on the group.
     * @param string $type
     * @param string $socialClass
     * @param array  $socialListID
     * @return array:string
     */
    public function getListTypedMembersID(string $type,string $socialClass = '', array $socialListID = array()): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->getIsRID($type))
            return array();
        $links = $this->getListTypedMembersLinks($type, $socialClass, $socialListID);
        $list = array();
        foreach ($links as $link)
            $list[$link->getParsed()['bl/rl/nid2']] = $link->getParsed()['bl/rl/nid2'];
        return $list;
    }
    public function getListMembersID(string $socialClass = '', array $socialListID = array()): array { return $this->getListTypedMembersID($this->_id, $socialClass, $socialListID); }
    public function getCountTypedMembers(string $type, string $socialClass = '', array $socialListID = array()): float { return sizeof($this->getListTypedMembersLinks($type, $socialClass, $socialListID)); }
    public function getCountMembers(string $socialClass = '', array $socialListID = array()): float { return sizeof($this->getListMembersLinks($socialClass, $socialListID)); }



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
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!Node::checkNID($reference))
            $reference = $this->_nebuleInstance->getFromDataNID($reference);
        $links = $this->getLinksOnFields(
                '',
                '',
                'l',
                '',
                $this->_id,
                $reference
        );
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
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getListFollowersLinks($this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI), $socialClass, $socialListID);
        $list = array();
        foreach ($links as $link)
            $list[$link->getParsed()['bl/rl/nid1']] = $link->getParsed()['bl/rl/nid1'];
        return $list;
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
                <li><a href="#ogm">OGM / Membres</a>
                    <ul>
                        <li><a href="#ogmt">OGMT / Membres typés</a></li>
                        <li><a href="#ogmp">OGMP / Protection des membres</a></li>
                        <li><a href="#ogmd">OGMD / Dissimulation des membres</a></li>
                    </ul>
                </li>
                <li><a href="#oga">OGA / Abonnés</a>
                    <ul>
                        <li><a href="#ogap">OGAP / Protection des abonnés</a></li>
                        <li><a href="#ogad">OGAD / Dissimulation des abonnés</a></li>
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

        <p>Le groupe en tant que tel est défini comme un objet (cf. <a href="#oo">OO</a>), c'est-à-dire qu’il doit
            avoir un type mime <code><?php echo References::REFERENCE_NEBULE_OBJET_TYPE; ?></code>.</p>
        <p>Fondamentalement, le groupe est un ensemble de plusieurs objets. C'est-à-dire, c’est le regroupement d’au
            moins deux objets. Le lien peut donc à ce titre être vu comme la matérialisation d’un groupe. Mais la
            définition du groupe doit être plus restrictive afin que celui-ci soit utilisable. Pour cela, dans
            <em>nebule</em>, le groupe n’est reconnu comme tel uniquement s'il est marqué de son type mime. Il est
            cependant possible d’instancier explicitement un objet comme groupe et de l’utiliser comme tel en cas de
            besoin.</p>
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
            avoir d’autres types mime. Dans ce cas l’identifiant de groupe a une forme commune aux objets virtuels (cf.
            <a href="#ooa">OOA</a>).</p>

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
        <p>Dans nebule, l’objet réservé <code><?php echo References::REFERENCE_NEBULE_OBJET_GROUPE_FERME; ?></code>
            est dédié à la gestion des groupes
            fermés. Un groupe est considéré comme fermé quand on a l’objet réservé en champs méta, l’entité en cours en
            champs cible et l’ID du groupe en champs source. Si au lieu d’utiliser l’entité en cours pour le champ
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
        <p>Le traitement des liens de fermeture d’un groupe doit être fait exclusivement avec le traitement social
            <em>self</em>.
        </p>

        <?php Displays::docDispTitle(3, 'ogm', 'Membres'); ?>
        <?php Displays::docDispTitle(4, 'ogmt', 'Membres typés'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'ogmp', 'Protection des membres'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le groupe va contenir un certain nombre de membres ajoutés par différentes entités. Il est possible de
            limiter la visibilité du contenu des membres utilisés dans un groupe en restreignant artificiellement les
            entités destinataires qui pourront les consulter.</p>
        <p>Dans nebule, l’objet réservé <code><?php echo References::REFERENCE_NEBULE_OBJET_GROUPE_PROTEGE; ?></code>
            est dédié à la gestion des groupes
            protégés. Un groupe est considéré protégé quand on a l’objet réservé en champs méta, l’entité en cours en
            champs cible et l’ID du groupe en champs source. Si au lieu d’utiliser l’entité en cours pour le champ
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
        <p>Le traitement des liens de protection d’un groupe doit être fait exclusivement avec le traitement social
            <em>self</em>.
        </p>

        <?php Displays::docDispTitle(4, 'ogmd', 'Dissimulation des membres'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le groupe va contenir un certain nombre de membres ajoutés par différentes entités. Il est possible de
            limiter la visibilité de l’appartenance des membres utilisés dans un groupe en restreignant artificiellement
            les entités destinataires qui pourront les voir.</p>
        <p>Dans nebule, l’objet réservé <code><?php echo References::REFERENCE_NEBULE_OBJET_GROUPE_DISSIMULE; ?></code>
            est dédié à la gestion des groupes
            dissimulés. Un groupe est considéré comme dissimulé quand on a l’objet réservé en champs méta, l’entité en
            cours en champs cible et l’ID du groupe en champs source. Si au lieu d’utiliser l’entité en cours pour le
            champ cible, on utilise une autre entité, cela revient à partager aussi les objets dissimulés créés pour ce
            groupe. Cela ne repartage pas la dissimulation des objets déjà dissimulés.</p>
        <p>Dans un groupe marqué dissimulé, tous les nouveaux membres ajoutés au groupe sont dissimulés. Ce n’est
            valable que pour l’entité en cours et éventuellement celles qui lui font confiance.</p>
        <p>Lorsque l’on a marqué un groupe comme dissimulé, on doit explicitement ajouter des entités avec qui on veut
            partager les membres du groupe.</p>
        <p>Il est possible indéfiniment de dissimuler et dé dissimuler un groupe.</p>
        <p>Il est possible de dissimuler un groupe qui ne nous appartient afin de masquer le contenu des membres que
            l’on y ajoute.</p>
        <p>Lorsque l’on a marqué un groupe comme dissimulé, on peut voir la liste des entités explicitement a qui on
            veut partager les contenus. On peut aussi voir les entités a qui les autres entités veulent partager les
            contenus et décider ou non de les ajouter.</p>
        <p>Le traitement des liens de dissimulation d’un groupe doit être fait exclusivement avec le traitement social
            <em>self</em>.</p>

        <?php Displays::docDispTitle(3, 'oga', 'Abonnés'); ?>
        <?php Displays::docDispTitle(4, 'ogap', 'Protection des abonnés'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'ogad', 'Dissimulation des abonnés'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

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
