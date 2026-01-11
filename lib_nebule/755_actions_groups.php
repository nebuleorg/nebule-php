<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Actions on groups, groups of entities, and conversations.
 * This class must not be used directly but via the entry point Actions->getInstanceActionsGroups().
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ActionsGroups extends Actions implements ActionsInterface {
    // WARNING: contents of constants for actions must be uniq for all actions classes!
    const CREATE = 'action_group_create';
    const CREATE_NAME = 'action_group_create_name';
    const CREATE_CLOSED = 'action_group_create_close';
    const CREATE_OBFUSCATED = 'action_group_create_obf';
    const CREATE_CONTEXT = 'action_group_create_context';
    const CREATE_WITH_CONTENT = 'action_group_with_content';
    const SYNCHRO = 'action_group_synchro';
    const SYNCHRO_NID = 'action_group_synchro_nid';
    const SYNCHRO_URL = 'action_group_synchro_url';
    const DELETE = 'action_group_del';
    const ADD_MEMBER = 'action_group_add_membre';
    const REMOVE_MEMBER = 'action_group_del_member';
    const TYPED_MEMBER = 'action_group_typ_member';
    const CREATE_MEMBER = 'action_group_create_membre';
    const CREATE_MEMBER_NAME = 'action_group_create_membre_name';
    const CREATE_MEMBER_TYPED = 'action_group_create_membre_type';
    const CREATE_MEMBER_CONTEXT = 'action_group_create_membre_context';
    const CREATE_MEMBER_OBFUSCATED = 'action_group_create_membre_obf';
    const CREATE_MEMBER_IS_GROUP = 'action_group_create_membre_is_group';



    public function initialisation(): void {}
    public function genericActions(): void {
        if ($this->getHaveInput(self::CREATE))
            $this->_createGroup();
        if ($this->getHaveInput(self::DELETE))
            $this->_deleteGroup();
        if ($this->getHaveInput(self::SYNCHRO))
            $this->_synchroGroup();
        if ($this->getHaveInput(self::ADD_MEMBER))
            $this->_addMember();
        if ($this->getHaveInput(self::REMOVE_MEMBER))
            $this->_removeMember();
        if ($this->getHaveInput(self::CREATE_MEMBER))
            $this->_addCreateMember();
    }
    public function specialActions(): void {}



    protected bool $_create = false;
    protected string $_createName = '';
    protected string $_createGID = '0';
    protected bool $_createClosed = false;
    protected bool $_createObfuscated = false;
    protected bool $_createWithContent = false;
    protected ?Group $_createInstance = null;
    protected bool $_createError = false;
    public function getCreate(): bool { return $this->_create; }
    public function getCreateGID(): string { return $this->_createGID; }
    public function getCreateInstance(): ?Group { return $this->_createInstance; }
    public function getCreateError(): bool { return $this->_createError; }
    protected function _createGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroupAction')) {
            $this->_metrologyInstance->addLog('unauthorised to use groups', Metrology::LOG_LEVEL_ERROR, __METHOD__, '44f2509d');
            return;
        }
        $this->_create = true;

        $this->_createName = $this->getFilterInput(self::CREATE_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
        if ($this->_createName == '') {
            $this->_createError = true;
            return;
        }
        $createContext = $this->getFilterInput(self::CREATE_CONTEXT, FILTER_FLAG_NO_ENCODE_QUOTES);
        if (! Node::checkNID($createContext))
            $createContext = '';
        if ($createContext == '')
            $this->_metrologyInstance->addLog('create group context=' . $createContext, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'a4031625');

        $this->_createClosed = $this->getHaveInput(self::CREATE_CLOSED);
        $this->_createObfuscated = ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink') && $this->getHaveInput(self::CREATE_OBFUSCATED));
        $this->_createWithContent = $this->getHaveInput(self::CREATE_WITH_CONTENT);

        $nid = '';
        if ($this->_createWithContent) {
            $instance = new Node($this->_nebuleInstance, '');
            $eid = $this->_entitiesInstance->getConnectedEntityEID();
            $salt = bin2hex($this->_cryptoInstance->getRandom(64, \Nebule\Library\Crypto::RANDOM_PSEUDO));
            $date = '0' . date('YmdHis');
            $branch = $this->_configurationInstance->getOptionAsString('codeBranch');
            $content = 'library=' . nebule::NEBULE_SURNAME . "\nbranch=" . $branch . "\nversion=" . nebule::NEBULE_VERSION . "\ntimestamp=" . $date . "\ntype=group\ncontext=" . $createContext . "\neid=" . $eid . "\nsalt=" . $salt;
            $instance->setContent($content);
            $instance->write();
            $nid = $instance->getID();
            $this->_metrologyInstance->addLog('create group with content nid=' . $nid . ' eid=' . $eid . ' salt=' . $salt, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '4c921139');
        }

        $this->_createInstance = new Group($this->_nebuleInstance, $nid);
        $this->_metrologyInstance->addLog('create group name=' . $this->_createName . ' gid=' . $this->_createInstance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'cf18d77f');
        $this->_createInstance->setAsGroup($this->_createObfuscated, $createContext);
        $this->_createInstance->setName($this->_createName);
        if ($this->_createClosed)
            $this->_createInstance->setMarkClosed(null, $this->_createObfuscated);

        $this->_createGID = $this->_createInstance->getID();
        $this->_createError = ($this->_createInstance->getID() == '0');
    }

    protected bool $_deleteGroup = false;
    protected bool $_deleteError = false;
    public function getDelete(): bool { return $this->_deleteGroup; }
    public function getDeleteError(): bool { return $this->_deleteError; }
    protected function _deleteGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupDeleteGroup')) {
            $this->_metrologyInstance->addLog('unauthorized to use groups', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'db9a2f07');
            return;
        }
        $gid = $this->getFilterInput(self::DELETE, FILTER_FLAG_ENCODE_LOW);
        $instance = $this->_cacheInstance->newNode($gid, \Nebule\Library\Cache::TYPE_GROUP);
        if ($instance->getID() == '0')
            return;
        $this->_metrologyInstance->addLog('delete group gid=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '03a28196');
        if ($instance->getMarkClosed())
            $instance->unsetMarkClosed();
        $this->_deleteGroup = true;
        $this->_deleteError = (!$instance->unsetAsGroup());
    }



    protected bool $_synchroGroup = false;
    protected string $_synchroNID = '';
    protected bool $_synchroError = false;
    public function getSynchro(): bool { return $this->_synchroGroup; }
    public function getSynchroNID(): string { return $this->_synchroNID; }
    public function getSynchroError(): bool { return $this->_synchroError; }
    protected function _synchroGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeItem')) {
            $this->_metrologyInstance->addLog('unauthorized to use groups', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'ece92f46');
            return;
        }
        $gid = $this->getFilterInput(self::SYNCHRO_NID, FILTER_FLAG_ENCODE_LOW);
        $url = $this->getFilterInput(self::SYNCHRO_URL, FILTER_FLAG_ENCODE_LOW);
        $this->_synchroGroup = true;
        $this->_synchroNID = $gid;
        // TODO
    }




    protected string $_actionAddMember = '';
    protected bool $_actionAddMemberError = true;
    public function getAddMember(): string { return $this->_actionAddMember; }
    public function getAddMemberError(): bool { return $this->_actionAddMemberError; }
    protected function _addMember(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddToGroup')) {
            $this->_metrologyInstance->addLog('unauthorized to use groups', Metrology::LOG_LEVEL_ERROR, __METHOD__, '3e508b5f');
            return;
        }
        $instance = $this->_nebuleInstance->getCurrentGroupInstance();
        if ($instance === null || $instance->getID() == '0')
            return;
        $this->_actionAddMember = $this->getFilterInput(self::ADD_MEMBER, FILTER_FLAG_ENCODE_LOW);
        $typed = $this->getFilterInput(self::TYPED_MEMBER, FILTER_FLAG_ENCODE_LOW);
        if ($typed == '')
            $this->_actionAddMemberError = (!$instance->setAsMemberNID($this->_actionAddMember));
        else
            $this->_actionAddMemberError = (!$instance->setAsTypedMemberNID($this->_actionAddMember, $typed));
    }

    protected string $_actionRemoveMember = '';
    protected bool $_actionRemoveMemberError = false;
    public function getRemoveMember(): string { return $this->_actionRemoveMember; }
    public function getRemoveMemberError(): bool { return $this->_actionRemoveMemberError; }
    protected function _removeMember(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddToGroup')) {
            $this->_metrologyInstance->addLog('unauthorized to use groups', Metrology::LOG_LEVEL_ERROR, __METHOD__, '60c4aa2a');
            return;
        }
        $instance = $this->_nebuleInstance->getCurrentGroupInstance();
        if ($instance === null || $instance->getID() == '0')
            return;
        $this->_actionRemoveMember = $this->getFilterInput(self::REMOVE_MEMBER, FILTER_FLAG_ENCODE_LOW);
        $typed = $this->getFilterInput(self::TYPED_MEMBER, FILTER_FLAG_ENCODE_LOW);
        if ($typed == '')
            $this->_actionRemoveMemberError = (!$instance->unsetAsMemberNID($this->_actionRemoveMember));
        else
            $this->_actionRemoveMemberError = (!$instance->unsetAsTypedMemberNID($this->_actionRemoveMember, $typed));
    }

    protected string $_actionAddCreateMember = '';
    protected bool $_actionAddCreateMemberError = true;
    public function getAddCreateMember(): string { return $this->_actionAddCreateMember; }
    public function getAddCreateMemberError(): bool { return $this->_actionAddCreateMemberError; }
    protected function _addCreateMember(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddToGroup')) {
            $this->_metrologyInstance->addLog('unauthorized to use groups', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c10459a6');
            return;
        }
        $instanceGroup = $this->_nebuleInstance->getCurrentGroupInstance();
        if ($instanceGroup === null || $instanceGroup->getID() == '0')
            return;
        $this->_actionAddCreateMember = $this->getFilterInput(self::CREATE_MEMBER, FILTER_FLAG_ENCODE_LOW);
        $name = $this->getFilterInput(self::CREATE_MEMBER_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
        $context = $this->getFilterInput(self::CREATE_MEMBER_CONTEXT, FILTER_FLAG_NO_ENCODE_QUOTES);
        $typed = $this->getFilterInput(self::CREATE_MEMBER_TYPED, FILTER_FLAG_NO_ENCODE_QUOTES);
        $obfuscated = ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink') && $this->getHaveInput(self::CREATE_MEMBER_OBFUSCATED));
        $isGroup = $this->getHaveInput(self::CREATE_MEMBER_IS_GROUP);

        $this->_createInstance = new Group($this->_nebuleInstance, '');
        $this->_metrologyInstance->addLog('create group name=' . $name . ' gid=' . $this->_createInstance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'eff13303');
        if ($name != '')
            $this->_createInstance->setName($name);
        if ($isGroup)
            $this->_createInstance->setAsGroup($this->_createObfuscated, $context);

        if ($typed == '')
            $this->_actionAddMemberError = (!$instanceGroup->setAsMemberNID($this->_actionAddMember));
        else
            $this->_actionAddMemberError = (!$instanceGroup->setAsTypedMemberNID($this->_actionAddMember, $typed));
    }
}