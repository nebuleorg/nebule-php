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



    protected bool $_create = false;
    protected string $_createName = '';
    protected string $_createGID = '0';
    protected bool $_createClosed = false;
    protected bool $_createObfuscated = false;
    protected ?Group $_createInstance = null;
    protected bool $_createError = false;
    public function getCreate(): bool { return $this->_create; }
    public function getCreateGID(): string { return $this->_createGID; }
    public function getCreateInstance(): ?Group { return $this->_createInstance; }
    public function getCreateError(): bool { return $this->_createError; }
    protected function _createGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup')) {
            $this->_metrologyInstance->addLog('unauthorized to use groups', Metrology::LOG_LEVEL_ERROR, __METHOD__, '44f2509d');
            return;
        }
        $this->_create = true;
        $this->_createName = $this->getFilterInput(self::CREATE_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
        $this->_createClosed = $this->getHaveInput(self::CREATE_CLOSED);
        $this->_createObfuscated = ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink') && $this->getHaveInput(self::CREATE_OBFUSCATED));
        $this->_createInstance = new Group($this->_nebuleInstance, '');
        $this->_metrologyInstance->addLog('create group name=' . $this->_createName . ' gid=' . $this->_createInstance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'cf18d77f');
        $this->_createInstance->setAsGroup($this->_createObfuscated);
        $this->_createInstance->setName($this->_createName);
        if ($this->_createClosed)
            $this->_createInstance->setMarkClosedGroup();
        $this->_createGID = $this->_createInstance->getID();
        $this->_createError = ($this->_createInstance->getID() == '0');
    }

    protected bool $_deleteGroup = false;
    protected bool $_deleteGroupError = false;
    public function getDeleteGroup(): bool { return $this->_deleteGroup; }
    public function getDeleteGroupError(): bool { return $this->_deleteGroupError; }
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
        if ($instance->getMarkClosedGroup())
            $instance->unsetMarkClosedGroup();
        $this->_deleteGroupError = (!$instance->unsetAsGroup());
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
        $typed = $this->getFilterInput(self::TYPED, FILTER_FLAG_ENCODE_LOW);
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
        $typed = $this->getFilterInput(self::TYPED, FILTER_FLAG_ENCODE_LOW);
        if ($typed == '')
            $this->_actionRemoveMemberError = (!$instance->unsetAsMemberNID($this->_actionRemoveMember));
        else
            $this->_actionRemoveMemberError = (!$instance->unsetAsTypedMemberNID($this->_actionRemoveMember, $typed));
    }
}