<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Actions on groups.
 * This class must not be used directly but via the entry point Actions->getInstanceActionsGroups().
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ActionsGroups extends Actions implements ActionsInterface {
    // WARNING: contents of constants for actions must be uniq for all actions classes!
    const CREATE = 'grpcrea';
    const CREATE_NAME = 'grpcreanam';
    const CREATE_CLOSED = 'grpcreacld';
    const CREATE_OBFUSCATED = 'grpcreaobf';
    const DELETE = 'grpdel';
    const ADD_MEMBER = 'grpaddmbr';
    const REMOVE_MEMBER = 'grpsupmbr';
    const TYPED = 'grptyp';



    public function initialisation(): void {}
    public function genericActions(): void {
        if ($this->getHaveInput(self::CREATE))
            $this->_createGroup();
        if ($this->getHaveInput(self::DELETE))
            $this->_deleteGroup();
        if ($this->getHaveInput(self::ADD_MEMBER))
            $this->_addMember();
        if ($this->getHaveInput(self::REMOVE_MEMBER))
            $this->_removeMember();
    }
    public function specialActions(): void {}



    protected bool $_createGroup = false;
    protected string $_createGroupName = '';
    protected string $_createGroupID = '0';
    protected bool $_createGroupClosed = false;
    protected bool $_createGroupObfuscateLinks = false;
    protected ?Group $_createGroupInstance = null;
    protected bool $_createGroupOK = false;
    public function getCreateGroup(): bool { return $this->_createGroup; }
    public function getCreateGroupID(): string { return $this->_createGroupID; }
    public function getCreateGroupInstance(): ?Group { return $this->_createGroupInstance; }
    public function getCreateGroupOK(): bool { return $this->_createGroupOK; }
    protected function _createGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $this->_metrologyInstance->addLog('unauthorized to use groups', Metrology::LOG_LEVEL_ERROR, __METHOD__, '44f2509d');
            return;
        }
        $this->_metrologyInstance->addLog('create group', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $this->_createGroup = true;
        $this->_createGroupName = $this->getFilterInput(self::CREATE_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
        $this->_createGroupClosed = $this->getHaveInput(self::CREATE_CLOSED);
        $this->_createGroupObfuscateLinks = ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink') && $this->getHaveInput(self::CREATE_OBFUSCATED));
        $this->_createGroupInstance = new Group($this->_nebuleInstance, '0');
        $this->_createGroupInstance->setName($this->_createGroupName);
        $this->_createGroupID = $this->_createGroupInstance->getID();
        $this->_createGroupOK = ($this->_createGroupInstance->getID() != '0');
    }

    protected bool $_deleteGroup = false;
    protected bool $_deleteGroupOK = false;
    public function getDeleteGroup(): bool { return $this->_deleteGroup; }
    public function getDeleteGroupOK(): bool { return $this->_deleteGroupOK; }
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
        $this->_metrologyInstance->addLog('delete group', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        if ($instance->getMarkClosedGroup())
            $instance->unsetMarkClosedGroup();
        $this->_deleteGroupOK = $instance->unsetAsGroup();
    }



    protected string $_actionAddMember = '';
    protected bool $_actionAddMemberOK = true;
    public function getAddMember(): string { return $this->_actionAddMember; }
    public function getAddMemberOK(): bool { return $this->_actionAddMemberOK; }
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
        $typed = $this->getFilterInput(self::TYPED, FILTER_FLAG_ENCODE_LOW);
        if ($typed == '')
            $this->_actionAddMemberOK = $instance->setAsMemberNID($this->_actionAddMember);
        else
            $this->_actionAddMemberOK = $instance->setAsTypedMemberNID($this->_actionAddMember, $typed);
    }

    protected string $_actionRemoveMember = '';
    protected bool $_actionRemoveMemberOK = false;
    public function getRemoveMember(): string { return $this->_actionRemoveMember; }
    public function getRemoveMemberOK(): bool { return $this->_actionRemoveMemberOK; }
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
        $typed = $this->getFilterInput(self::TYPED, FILTER_FLAG_ENCODE_LOW);
        if ($typed == '')
            $this->_actionRemoveMemberOK = $instance->unsetAsMemberNID($this->_actionRemoveMember);
        else
            $this->_actionRemoveMemberOK = $instance->unsetAsTypedMemberNID($this->_actionRemoveMember, $typed);
    }
}