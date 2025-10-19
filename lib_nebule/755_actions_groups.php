<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Actions on groups.
 * TODO must be refactored!
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ActionsGroups extends Actions implements ActionsInterface {
    // WARNING: contents of constants for actions must be uniq for all actions classes!
    const CREATE = 'creagrp';
    const CREATE_NAME = 'creagrpnam';
    const CREATE_CLOSED = 'creagrpcld';
    const CREATE_OBFUSCATED = 'creagrpobf';
    const DELETE = 'actdelgrp';
    const ADD_TO_GROUP = 'actaddtogrp';
    const REMOVE_FROM_GROUP = 'actremtogrp';
    const ADD_ITEM_TO_GROUP = 'actadditogrp';
    const REMOVE_ITEM_FROM_GROUP = 'actremitogrp';



    public function initialisation(): void {}
    public function genericActions(): void {
        $this->_extractActionCreateGroup();
        $this->_extractActionDeleteGroup();
        $this->_extractActionAddToGroup();
        $this->_extractActionRemoveFromGroup();
        $this->_extractActionAddItemToGroup();

        $this->_extractActionRemoveItemFromGroup();
        if ($this->_actionCreateGroup)
            $this->_actionCreateGroup();
        if ($this->_actionDeleteGroup)
            $this->_actionDeleteGroup();
        if ($this->_actionAddToGroup != '')
            $this->_actionAddToGroup();
        if ($this->_actionRemoveFromGroup != '')
            $this->_actionRemoveFromGroup();
        if ($this->_actionAddItemToGroup != '')
            $this->_actionAddItemToGroup();
        if ($this->_actionRemoveItemFromGroup != '')
            $this->_actionRemoveItemFromGroup();
    }
    public function specialActions(): void {}



    protected bool $_actionCreateGroup = false;
    protected string $_actionCreateGroupName = '';
    protected string $_actionCreateGroupID = '0';
    protected bool $_actionCreateGroupClosed = false;
    protected bool $_actionCreateGroupObfuscateLinks = false;
    protected ?Group $_actionCreateGroupInstance = null;
    protected bool $_actionCreateGroupError = false;
    protected string $_actionCreateGroupErrorMessage = 'initialisation creation';
    public function getCreateGroup(): bool
    {
        return $this->_actionCreateGroup;
    }
    public function getCreateGroupID(): string
    {
        return $this->_actionCreateGroupID;
    }
    public function getCreateGroupInstance(): ?Group
    {
        return $this->_actionCreateGroupInstance;
    }
    public function getCreateGroupError(): bool
    {
        return $this->_actionCreateGroupError;
    }
    public function getCreateGroupErrorMessage(): string
    {
        return $this->_actionCreateGroupErrorMessage;
    }
    protected function _extractActionCreateGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup'))
            return;

        $argCreate = filter_has_var(INPUT_GET, self::CREATE);

        if ($argCreate !== false)
            $this->_actionCreateGroup = true;

        // Si on crée une nouvelle entité.
        if ($this->_actionCreateGroup) {
            $argName = $this->getFilterInput(self::CREATE_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);

            // Extrait les options de téléchargement.
            $argCld = filter_has_var(INPUT_POST, self::CREATE_CLOSED);
            $argObf = filter_has_var(INPUT_POST, self::CREATE_OBFUSCATED);

            // Sauvegarde les valeurs.
            $this->_actionCreateGroupName = $argName;
            $this->_actionCreateGroupClosed = $argCld;
            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateGroupObfuscateLinks = $argObf;
        }
    }
    protected function _actionCreateGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $instance = new Group($this->_nebuleInstance, 'new', $this->_actionCreateGroupClosed);

        if (is_a($instance, 'Nebule\Library\Group') && $instance->getID() != '0') {
            $this->_actionCreateGroupError = false;
            $instance->setName($this->_actionCreateGroupName);

            $this->_actionCreateGroupInstance = $instance;
            $this->_actionCreateGroupID = $instance->getID();
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('action _actionCreateGroup cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionCreateGroupError = true;
            $this->_actionCreateGroupErrorMessage = 'Echec de la génération.';
        }
    }

    protected bool $_actionDeleteGroup = false;
    protected string $_actionDeleteGroupID = '0';
    protected bool $_actionDeleteGroupError = false;
    protected string $_actionDeleteGroupErrorMessage = 'Initialisation de la supression.';
    public function getDeleteGroup(): bool
    {
        return $this->_actionDeleteGroup;
    }
    public function getDeleteGroupID(): string
    {
        return $this->_actionDeleteGroupID;
    }
    public function getDeleteGroupError(): bool
    {
        return $this->_actionDeleteGroupError;
    }
    public function getDeleteGroupErrorMessage(): string
    {
        return $this->_actionDeleteGroupErrorMessage;
    }
    protected function _extractActionDeleteGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupDeleteGroup'))
            return;

        $argDelete = $this->getFilterInput(self::DELETE, FILTER_FLAG_ENCODE_LOW);

        if ($argDelete !== ''
            && strlen($argDelete) >= BlocLink::NID_MIN_HASH_SIZE
            && Node::checkNID($argDelete)
        ) {
            $this->_actionDeleteGroup = true;
            $this->_actionDeleteGroupID = $argDelete;
        }
    }
    protected function _actionDeleteGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $instance = $this->_cacheInstance->newNode($this->_actionDeleteGroupID, \Nebule\Library\Cache::TYPE_GROUP);

        // Vérification.
        if ($instance->getID() == '0'
            || !$instance->getIsGroup('all')
        ) {
            $this->_actionDeleteGroupError = false;
            $this->_actionDeleteGroupErrorMessage = 'Pas un groupe.';
            $this->_metrologyInstance->addLog('action delete not a group', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            return;
        }

        // Suppression.
        if ($instance->getMarkClosed()) {
            $this->_metrologyInstance->addLog('action delete group closed', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            $instance->unsetMarkClosed();
        }
        $instance->unsetGroup();

        // Vérification.
        if ($instance->getIsGroup('myself')) {
            // Si ce n'est pas bon.
            $this->_metrologyInstance->addLog('action _actionDeleteGroup cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionDeleteGroupError = true;
            $this->_actionDeleteGroupErrorMessage = 'Echec de la génération.';
        }
    }

    protected string $_actionAddToGroup = '';
    public function getAddToGroup(): string
    {
        return $this->_actionAddToGroup;
    }
    protected function _extractActionAddToGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddToGroup'))
            return;

        $arg = $this->getFilterInput(self::ADD_TO_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionAddToGroup = $arg;
    }
    protected function _actionAddToGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceGroupe = $this->_cacheInstance->newNode($this->_actionAddToGroup, \Nebule\Library\Cache::TYPE_GROUP);
        $instanceGroupe->setMember($this->_nebuleInstance->getCurrentObjectInstance());
    }

    protected string $_actionRemoveFromGroup = '';
    public function getRemoveFromGroup(): string
    {
        return $this->_actionRemoveFromGroup;
    }
    protected function _extractActionRemoveFromGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupRemoveFromGroup'))
            return;

        $arg = $this->getFilterInput(self::REMOVE_FROM_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionRemoveFromGroup = $arg;
    }
    protected function _actionRemoveFromGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceGroupe = $this->_cacheInstance->newNode($this->_actionRemoveFromGroup, \Nebule\Library\Cache::TYPE_GROUP);
        $instanceGroupe->unsetMember($this->_nebuleInstance->getCurrentObjectInstance());
    }

    protected string $_actionAddItemToGroup = '';
    public function getAddItemToGroup(): string
    {
        return $this->_actionAddItemToGroup;
    }
    protected function _extractActionAddItemToGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddItemToGroup'))
            return;

        $arg = $this->getFilterInput(self::ADD_ITEM_TO_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionAddItemToGroup = $arg;
    }
    protected function _actionAddItemToGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_nebuleInstance->getCurrentGroupInstance()->setMember($this->_actionAddItemToGroup);
    }

    protected string $_actionRemoveItemFromGroup = '';
    public function getRemoveItemFromGroup(): string
    {
        return $this->_actionRemoveItemFromGroup;
    }
    protected function _extractActionRemoveItemFromGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupRemoveItemFromGroup'))
            return;


        $arg = $this->getFilterInput(self::REMOVE_ITEM_FROM_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionRemoveItemFromGroup = $arg;
    }
    protected function _actionRemoveItemFromGroup(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_nebuleInstance->getCurrentGroupInstance()->unsetMember($this->_actionRemoveItemFromGroup);
    }

}