<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe Actions communes.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Actions extends Functions implements ActionsInterface {
    const DEFAULT_COMMAND_ACTION_DELETE_OBJECT = 'actdelobj';
    const DEFAULT_COMMAND_ACTION_DELETE_OBJECT_FORCE = 'actdelobjf';
    const DEFAULT_COMMAND_ACTION_DELETE_OBJECT_OBFUSCATE = 'actdelobjo';
    const DEFAULT_COMMAND_ACTION_PROTECT_OBJECT = 'actprtobj';
    const DEFAULT_COMMAND_ACTION_UNPROTECT_OBJECT = 'actuprtobj';
    const DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_ENTITY = 'actshrprtent';
    const DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_OPENED = 'actshrprtgrpo';
    const DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_CLOSED = 'actshrprtgrpc';
    const DEFAULT_COMMAND_ACTION_CANCEL_SHARE_PROTECT_TO_ENTITY = 'actushrprtent';
    const DEFAULT_COMMAND_ACTION_SYNCHRONIZE_OBJECT = 'actsynobj';
    const DEFAULT_COMMAND_ACTION_SYNCHRONIZE_LINKS = 'actsynlnk';
    const DEFAULT_COMMAND_ACTION_UPLOAD_FILE = 'upfile';
    const DEFAULT_COMMAND_ACTION_UPLOAD_FILE_UPDATE = 'upfilemaj';
    const DEFAULT_COMMAND_ACTION_UPLOAD_FILE_ASK_UPDATE = 'upfileaskmaj';
    const DEFAULT_COMMAND_ACTION_UPLOAD_FILE_PROTECT = 'upfileprt';
    const DEFAULT_COMMAND_ACTION_UPLOAD_FILE_OBFUSCATE_LINKS = 'upfileobf';
    const DEFAULT_COMMAND_ACTION_UPLOAD_TEXT = 'uptext';
    const DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_NAME = 'uptextname';
    const DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_TYPE = 'uptexttype';
    const DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_PROTECT = 'uptextprt';
    const DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_OBFUSCATE_LINKS = 'uptextobf';
    const DEFAULT_COMMAND_ACTION_CREATE_GROUP = 'creagrp';
    const DEFAULT_COMMAND_ACTION_CREATE_GROUP_NAME = 'creagrpnam';
    const DEFAULT_COMMAND_ACTION_CREATE_GROUP_CLOSED = 'creagrpcld';
    const DEFAULT_COMMAND_ACTION_CREATE_GROUP_OBFUSCATE_LINKS = 'creagrpobf';
    const DEFAULT_COMMAND_ACTION_DELETE_GROUP = 'actdelgrp';
    const DEFAULT_COMMAND_ACTION_ADD_TO_GROUP = 'actaddtogrp';
    const DEFAULT_COMMAND_ACTION_REMOVE_FROM_GROUP = 'actremtogrp';
    const DEFAULT_COMMAND_ACTION_ADD_ITEM_TO_GROUP = 'actadditogrp';
    const DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_GROUP = 'actremitogrp';
    const DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION = 'creacvt';
    const DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_NAME = 'creacvtnam';
    const DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_CLOSED = 'creacvtcld';
    const DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_PROTECTED = 'creacvtprt';
    const DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_OBFUSCATE_LINKS = 'creacvtobf';
    const DEFAULT_COMMAND_ACTION_DELETE_CONVERSATION = 'actdelcvt';
    const DEFAULT_COMMAND_ACTION_ADD_TO_CONVERSATION = 'actaddtocvt';
    const DEFAULT_COMMAND_ACTION_REMOVE_FROM_CONVERSATION = 'actremtocvt';
    const DEFAULT_COMMAND_ACTION_ADD_ITEM_TO_CONVERSATION = 'actadditocvt';
    const DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_CONVERSATION = 'actremitocvt';
    const DEFAULT_COMMAND_ACTION_CREATE_MESSAGE = 'creamsg';
    const DEFAULT_COMMAND_ACTION_CREATE_MESSAGE_PROTECTED = 'creamsgprt';
    const DEFAULT_COMMAND_ACTION_CREATE_MESSAGE_OBFUSCATE_LINKS = 'creamsgobf';
    const DEFAULT_COMMAND_ACTION_MARK_OBJECT = 'actmarkobj';
    const DEFAULT_COMMAND_ACTION_UNMARK_OBJECT = 'actunmarkobj';
    const DEFAULT_COMMAND_ACTION_UNMARK_ALL_OBJECT = 'actunmarkallobj';
    const DEFAULT_COMMAND_ACTION_ADD_PROPERTY = 'actaddprop';
    const DEFAULT_COMMAND_ACTION_ADD_PROPERTY_OBJECT = 'actaddpropobj';
    const DEFAULT_COMMAND_ACTION_ADD_PROPERTY_VALUE = 'actaddpropval';
    const DEFAULT_COMMAND_ACTION_ADD_PROPERTY_PROTECTED = 'actaddpropprf';
    const DEFAULT_COMMAND_ACTION_ADD_PROPERTY_OBFUSCATE_LINKS = 'actaddpropobf';
    const DEFAULT_COMMAND_ACTION_CREATE_CURRENCY = 'creacur';
    const DEFAULT_COMMAND_ACTION_CREATE_TOKEN_POOL = 'creactp';
    const DEFAULT_COMMAND_ACTION_CREATE_TOKENS = 'creactk';
    const DEFAULT_COMMAND_ACTION_CREATE_TOKENS_COUNT = 'creactkcnt';

    protected bool $_unlocked = false;
    private ?ActionsLinks $_instanceActionsLinks = null;
    private ?ActionsObjects $_instanceActionsObjects = null;
    private ?ActionsEntities $_instanceActionsEntities = null;
    private ?ActionsApplications $_instanceActionsApplications = null;

    public function __construct(nebule $nebuleInstance) { parent::__construct($nebuleInstance); }
    public function __toString() { return 'Action'; }
    public function __sleep() { return array(); } // TODO do not cache



    protected function _initialisation(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('initialisation action', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4b67de69');
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_initSubActions();
    }

    private function _initSubActions(): void {
        $this->_instanceActionsLinks = new ActionsLinks($this->_nebuleInstance);
        $this->_instanceActionsLinks->setEnvironmentLibrary($this->_nebuleInstance);
        $this->_instanceActionsLinks->setEnvironmentApplication($this->_applicationInstance);
        //$this->_instanceActionsLinks->initialisation();
        $this->_instanceActionsObjects = new ActionsObjects($this->_nebuleInstance);
        $this->_instanceActionsObjects->setEnvironmentLibrary($this->_nebuleInstance);
        $this->_instanceActionsObjects->setEnvironmentApplication($this->_applicationInstance);
        //$this->_instanceActionsObjects->initialisation();
        $this->_instanceActionsEntities = new ActionsEntities($this->_nebuleInstance);
        $this->_instanceActionsEntities->setEnvironmentLibrary($this->_nebuleInstance);
        $this->_instanceActionsEntities->setEnvironmentApplication($this->_applicationInstance);
        //$this->_instanceActionsEntities->initialisation();
        $this->_instanceActionsApplications = new ActionsApplications($this->_nebuleInstance);
        $this->_instanceActionsApplications->setEnvironmentLibrary($this->_nebuleInstance);
        $this->_instanceActionsApplications->setEnvironmentApplication($this->_applicationInstance);
        //$this->_instanceActionsApplications->initialisation();
    }


    public function getActions(): void {
        try {
            $this->_metrologyInstance->addLog('call special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00da4388');
            $this->specialActions();
        } catch (\Exception $e) {
            $this->_metrologyInstance->addLog('error special actions ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '8a263023');
        }

        try {
            $this->_metrologyInstance->addLog('call generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5f58e568');
            $this->genericActions();
        } catch (\Exception $e) {
            $this->_metrologyInstance->addLog('error generic actions ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd27f51b8');
        }

        try {
            $this->_metrologyInstance->addLog('call application actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '285f18cf');
            $this->applicationActions();
        } catch (\Exception $e) {
            $this->_metrologyInstance->addLog('error actions for application ' . $this->_applicationInstance->getName()
                . ' ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f90d32f4');
        }

        $this->modulesActions(); // Try/catch inside
    }

    public function genericActions(): void {
        $this->_metrologyInstance->addLog('generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '84b627f1');

        if (!$this->_tokenizeInstance->checkActionToken() || !$this->_unlocked)
            return; // Do not accept module action without a valid token or an unlocked entity.

        $this->_instanceActionsLinks->genericActions();
        $this->_instanceActionsObjects->genericActions();
        $this->_instanceActionsEntities->genericActions();
        $this->_instanceActionsApplications->genericActions();
        $this->_extractActionDeleteObject();
        $this->_extractActionProtectObject();
        $this->_extractActionUnprotectObject();
        $this->_extractActionShareProtectObjectToEntity();
        $this->_extractActionShareProtectObjectToGroupOpened();
        $this->_extractActionShareProtectObjectToGroupClosed();
        $this->_extractActionCancelShareProtectObjectToEntity();
        $this->_extractActionSynchronizeObject();
        $this->_extractActionSynchronizeObjectLinks();
        $this->_extractActionMarkObject();
        $this->_extractActionUnmarkObject();
        $this->_extractActionUnmarkAllObjects();
        $this->_extractActionUploadFile();
        $this->_extractActionUploadText();
        $this->_extractActionCreateGroup();
        $this->_extractActionDeleteGroup();
        $this->_extractActionAddToGroup();
        $this->_extractActionRemoveFromGroup();
        $this->_extractActionAddItemToGroup();
        $this->_extractActionRemoveItemFromGroup();
        $this->_extractActionCreateConversation();
        $this->_extractActionDeleteConversation();
        $this->_extractActionAddMessageOnConversation();
        $this->_extractActionRemoveMessageOnConversation();
        $this->_extractActionAddMemberOnConversation();
        $this->_extractActionRemoveMemberOnConversation();
        $this->_extractActionCreateMessage();
        $this->_extractActionCreateCurrency();
        $this->_extractActionCreateTokenPool();
        $this->_extractActionCreateTokens();
        $this->_extractActionAddProperty();

        $this->_metrologyInstance->addLog('router generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '59d4a536');

        if ($this->_actionDeleteObject
            && is_a($this->_actionDeleteObjectInstance, 'Nebule\Library\Node')
        )
            $this->_actionDeleteObject();

        if ($this->_actionProtectObjectInstance != ''
            && is_a($this->_actionProtectObjectInstance, 'Nebule\Library\Node')
        )
            $this->_actionProtectObject();
        if ($this->_actionUnprotectObjectInstance != ''
            && is_a($this->_actionUnprotectObjectInstance, 'Nebule\Library\Node')
        )
            $this->_actionUnprotectObject();
        if ($this->_actionShareProtectObjectToEntity != '')
            $this->_actionShareProtectObjectToEntity();
        if ($this->_actionShareProtectObjectToGroupOpened != '')
            $this->_actionShareProtectObjectToGroupOpened();
        if ($this->_actionShareProtectObjectToGroupClosed != '')
            $this->_actionShareProtectObjectToGroupClosed();
        if ($this->_actionCancelShareProtectObjectToEntity != '')
            $this->_actionCancelShareProtectObjectToEntity();

        if ($this->_actionSynchronizeObjectInstance != '')
            $this->_actionSynchronizeObject();
        if ($this->_actionSynchronizeObjectLinksInstance != '')
            $this->_actionSynchronizeObjectLinks();

        if ($this->_actionMarkObject != '')
            $this->_actionMarkObject();
        if ($this->_actionUnmarkObject != '')
            $this->_actionUnmarkObject();
        if ($this->_actionUnmarkAllObjects)
            $this->_actionUnmarkAllObjects();

        if ($this->_actionUploadFile)
            $this->_actionUploadFile();
        if ($this->_actionUploadText)
            $this->_actionUploadText();

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

        if ($this->_actionCreateConversation)
            $this->_actionCreateConversation();
        if ($this->_actionDeleteConversation)
            $this->_actionDeleteConversation();
        if ($this->_actionAddMessageOnConversation != '')
            $this->_actionAddMessageOnConversation();
        if ($this->_actionRemoveMessageOnConversation != '')
            $this->_actionRemoveMessageOnConversation();
        if ($this->_actionAddMemberOnConversation != '')
            $this->_actionAddMemberOnConversation();
        if ($this->_actionRemoveMemberOnConversation != '')
            $this->_actionRemoveMemberOnConversation();
        if ($this->_actionCreateMessage)
            $this->_actionCreateMessage();
        if ($this->_actionAddProperty != '')
            $this->_actionAddProperty();

        if ($this->_actionCreateCurrency)
            $this->_actionCreateCurrency();
        if ($this->_actionCreateTokenPool)
            $this->_actionCreateTokenPool();
        if ($this->_actionCreateTokens)
            $this->_actionCreateTokens();

        $this->_metrologyInstance->addLog('generic actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b2046b14');
    }

    public function applicationActions():void {}

    public function modulesActions():void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('call modules actions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_tokenizeInstance->checkActionToken())
            return; // Do not accept module action without a valid token.
        $module = $this->_applicationInstance->getApplicationModulesInstance()->getCurrentModuleInstance();
        if ($module::MODULE_COMMAND_NAME == $this->_displayInstance->getCurrentDisplayMode()) {
            try {
                $this->_metrologyInstance->addLog('actions for module ' . $module::MODULE_COMMAND_NAME, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '55fba077');
                $module->actions();
            } catch (\Exception $e) {
                $this->_metrologyInstance->addLog('error actions for module ' . $module::MODULE_COMMAND_NAME
                    . ' ('  . $e->getCode() . ') : ' . $e->getFile()
                    . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                    . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c48b1d8c');
            }
        }
    }



    /**
     * Traitement des actions spéciales.
     *
     * Les actions peuvent être réalisées sans entité déverrouillée, mais pas nécessairement.
     * Certaines actions ne nécessitent pas la création de nouveaux liens. C'est par exemple
     *   le cas de la génération d'une nouvelle entité qui va générer ses propres liens.
     * Certaines actions peuvent avoir un comportement restreint si elles ne peuvent générer
     *   de nouveaux liens, mais permettre de prendre en compte des liens déjà signés.
     *
     * Les actions spéciales doivent avoir des pré-requis bien ciblés pour éviter tout contournement.
     *
     * @return void
     */
    public function specialActions(): void {
        $this->_metrologyInstance->addLog('extract special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6f9dfb64');
        $this->_instanceActionsLinks->specialActions();
        $this->_instanceActionsObjects->specialActions();
        $this->_instanceActionsEntities->specialActions();
        $this->_instanceActionsApplications->specialActions();
        //$this->_metrologyInstance->addLog('router special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0b4f22ef');
        $this->_metrologyInstance->addLog('special actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '88ff0291');
    }

    public function getDisplayActions(): void
    {
        $this->_displayInstance->displayInlineLastAction(); // FIXME
    }



    public function getUploadFileSignedLinks(): bool { return $this->_instanceActionsLinks->getUploadFileSignedLinks(); }
    public function getSynchronizeEntityInstance(): ?Entity { return $this->_instanceActionsEntities->getSynchronizeEntityInstance(); }
    public function getSynchronizeNewEntityInstance(): ?Entity { return $this->_instanceActionsEntities->getSynchronizeNewEntityInstance(); }
    public function getCreateEntity(): bool { return $this->_instanceActionsEntities->getCreateEntity(); }
    public function getCreateEntityID(): string { return $this->_instanceActionsEntities->getCreateEntityID(); }
    public function getCreateEntityKeyID(): string { return $this->_instanceActionsEntities->getCreateEntityKeyID(); }
    public function getCreateEntityInstance(): ?Entity { return $this->_instanceActionsEntities->getCreateEntityInstance(); }
    public function getCreateEntityKeyInstance(): ?Node { return $this->_instanceActionsEntities->getCreateEntityKeyInstance(); }
    public function getCreateEntityError(): bool { return $this->_instanceActionsEntities->getCreateEntityError(); }
    public function getCreateEntityErrorMessage(): string { return $this->_instanceActionsEntities->getCreateEntityErrorMessage(); }
    public function getSynchronizeApplicationInstance(): ?Node { return $this->_instanceActionsApplications->getSynchronizeApplicationInstance(); }



    protected bool $_actionDeleteObject = false;
    protected string $_actionDeleteObjectID = '0';
    protected ?Node $_actionDeleteObjectInstance = null;
    protected bool $_actionDeleteObjectForce = false;
    protected bool $_actionDeleteObjectObfuscate = false;
    public function getDeleteObject(): bool
    {
        return $this->_actionDeleteObject;
    }
    public function getDeleteObjectID(): string
    {
        return $this->_actionDeleteObjectID;
    }
    protected function _extractActionDeleteObject(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupDeleteObject'))
            return;

        $this->_metrologyInstance->addLog('extract action delete object', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '19096242');

        $argObject = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_DELETE_OBJECT, FILTER_FLAG_ENCODE_LOW);
        $argForce = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_OBJECT_FORCE);
        $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_OBJECT_OBFUSCATE);

        // Extraction de l'objet à supprimer.
        if (Node::checkNID($argObject)) {
            $this->_actionDeleteObjectID = $argObject;
            $this->_actionDeleteObjectInstance = $this->_cacheInstance->newNode($argObject);
            $this->_actionDeleteObject = true;
        }

        // Extraction si la suppression doit être forcée.
        if ($argForce)
            $this->_actionDeleteObjectForce = true;

        // Extraction si la suppression doit être cachée.
        if ($argObfuscate
            && $this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')
        )
            $this->_actionDeleteObjectObfuscate = true;
    }
    protected function _actionDeleteObject(): void
    {
        $this->_metrologyInstance->addLog('action delete object', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '234f6195');

        // Suppression de l'objet.
        if ($this->_actionDeleteObjectForce)
            $this->_actionDeleteObjectInstance->deleteForceObject();
        else
            $this->_actionDeleteObjectInstance->deleteObject();
    }

    protected ?Node $_actionProtectObjectInstance = null;
    protected function _extractActionProtectObject(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupProtectObject'))
            return;

        $this->_metrologyInstance->addLog('extract action protect object', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd4d5b6f4');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_PROTECT_OBJECT, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionProtectObjectInstance = $this->_cacheInstance->newNode($arg);
    }
    protected function _actionProtectObject(): void
    {
        $this->_metrologyInstance->addLog('action protect object', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '0aff7e01');

        // Demande de protection de l'objet.
        $this->_actionProtectObjectInstance->setProtected();
    }

    protected ?Node $_actionUnprotectObjectInstance = null;
    protected function _extractActionUnprotectObject(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupUnprotectObject'))
            return;

        $this->_metrologyInstance->addLog('extract action unprotect object', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4a15018f');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_UNPROTECT_OBJECT, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionUnprotectObjectInstance = $this->_cacheInstance->newNode($arg);
    }
    protected function _actionUnprotectObject(): void
    {
        $this->_metrologyInstance->addLog('action unprotect object', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '2a652083');

        // Demande de protection de l'objet.
        $this->_actionUnprotectObjectInstance->setUnprotected();
    }

    protected string $_actionShareProtectObjectToEntity = '';
    protected function _extractActionShareProtectObjectToEntity(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupShareProtectObjectToEntity'))
            return;

        $this->_metrologyInstance->addLog('extract action share protect object to entity', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ae098953');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_ENTITY, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToEntity = $arg;
    }
    protected function _actionShareProtectObjectToEntity(): void
    {
        $this->_metrologyInstance->addLog('action share protect object to entity ' . $this->_actionShareProtectObjectToEntity, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '27bac517');

        // Demande de protection de l'objet.
        $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($this->_actionShareProtectObjectToEntity);
    }

    protected string $_actionShareProtectObjectToGroupOpened = '';
    protected function _extractActionShareProtectObjectToGroupOpened(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupShareProtectObjectToGroupOpened'))
            return;

        $this->_metrologyInstance->addLog('extract action share protect object to opened group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '302775bd');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_OPENED, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToGroupOpened = $arg;
    }
    protected function _actionShareProtectObjectToGroupOpened(): void
    {
        $this->_metrologyInstance->addLog('action share protect object to opened group', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '3ae7ee74');

        // Demande de protection de l'objet.
        $group = $this->_cacheInstance->newNode($this->_actionShareProtectObjectToGroupOpened, \Nebule\Library\Cache::TYPE_GROUP);
        foreach ($group->getListMembersID('myself') as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);
    }

    protected string $_actionShareProtectObjectToGroupClosed = '';
    protected function _extractActionShareProtectObjectToGroupClosed(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupShareProtectObjectToGroupClosed'))
            return;

        $this->_metrologyInstance->addLog('extract action share protect object to closed group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c6c42849');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_CLOSED, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToGroupClosed = $arg;
    }
    protected function _actionShareProtectObjectToGroupClosed(): void
    {
        $this->_metrologyInstance->addLog('action share protect object to closed group', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'b217dcb1');

        // Demande de protection de l'objet.
        $group = $this->_cacheInstance->newNode($this->_actionShareProtectObjectToGroupClosed, \Nebule\Library\Cache::TYPE_GROUP);
        foreach ($group->getListMembersID('myself') as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);
    }

    protected string $_actionCancelShareProtectObjectToEntity = '';
    protected function _extractActionCancelShareProtectObjectToEntity(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCancelShareProtectObjectToEntity'))
            return;

        $this->_metrologyInstance->addLog('extract action cancel share protect object to entity', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bf5ed8c7');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CANCEL_SHARE_PROTECT_TO_ENTITY, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionCancelShareProtectObjectToEntity = $arg;
    }
    protected function _actionCancelShareProtectObjectToEntity(): void
    {
        $this->_metrologyInstance->addLog('action cancel share protect object to entity', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '71313220');

        // Demande d'annulation de protection de l'objet.
        $this->_nebuleInstance->getCurrentObjectInstance()->cancelShareProtectionTo($this->_actionCancelShareProtectObjectToEntity);
    }

    protected ?Node $_actionSynchronizeObjectInstance = null;
    public function getSynchronizeObjectInstance(): ?Node
    {
        return $this->_actionSynchronizeObjectInstance;
    }
    protected function _extractActionSynchronizeObject(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeObject'))
            return;

        $this->_metrologyInstance->addLog('extract action synchronize object', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd9e5454c');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_OBJECT, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionSynchronizeObjectInstance = $this->_cacheInstance->newNode($arg);
    }
    protected function _actionSynchronizeObject(): void
    {
        $this->_metrologyInstance->addLog('action synchronize object', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '74dfb558');

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNOBJ') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeObjectInstance);

        // Synchronisation.
        $this->_actionSynchronizeObjectInstance->syncObject();
    }

    protected ?Node $_actionSynchronizeObjectLinksInstance = null;
    public function getSynchronizeObjectLinksInstance(): ?Node
    {
        return $this->_actionSynchronizeObjectLinksInstance;
    }
    protected function _extractActionSynchronizeObjectLinks(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeObjectLinks'))
            return;

        $this->_metrologyInstance->addLog('extract action synchronize object links', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1ec3ab52');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_LINKS, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionSynchronizeObjectLinksInstance = $this->_cacheInstance->newNode($arg);
    }
    protected function _actionSynchronizeObjectLinks(): void
    {
        $this->_metrologyInstance->addLog('action synchronize object links', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '4dc338f4');

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNLNK') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeObjectLinksInstance);

        // Synchronisation.
        $this->_actionSynchronizeObjectLinksInstance->syncLinks();
    }

    protected string $_actionMarkObject = '';
    protected function _extractActionMarkObject(): void
    {
        $this->_metrologyInstance->addLog('extract action mark object', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_MARK_OBJECT, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionMarkObject = $arg;
    }
    protected function _actionMarkObject(): void
    {
        $this->_metrologyInstance->addLog('action mark object', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $this->_applicationInstance->setMarkObject($this->_actionMarkObject);
    }

    protected string $_actionUnmarkObject = '';
    protected function _extractActionUnmarkObject(): void
    {
        $this->_metrologyInstance->addLog('extract action unmark object', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_UNMARK_OBJECT, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionUnmarkObject = $arg;
    }
    protected function _actionUnmarkObject(): void
    {
        $this->_metrologyInstance->addLog('action unmark object', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $this->_applicationInstance->setUnmarkObject($this->_actionUnmarkObject);
    }

    protected bool $_actionUnmarkAllObjects = false;
    protected function _extractActionUnmarkAllObjects(): void
    {
        $this->_metrologyInstance->addLog('extract action unmark all objects', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_UNMARK_ALL_OBJECT);

        if ($arg !== false)
            $this->_actionUnmarkAllObjects = true;
    }
    protected function _actionUnmarkAllObjects(): void
    {
        $this->_metrologyInstance->addLog('action unmark all objects', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $this->_applicationInstance->setUnmarkAllObjects();
    }

    protected bool $_actionUploadFile = false;
    protected string $_actionUploadFileID = '0';
    protected string $_actionUploadFileName = '';
    protected string $_actionUploadFileExtension = '';
    protected string $_actionUploadFileType = '';
    protected string $_actionUploadFileSize = '';
    protected string $_actionUploadFilePath = '';
    protected bool $_actionUploadFileUpdate = false;
    protected bool $_actionUploadFileProtect = false;
    protected bool $_actionUploadFileObfuscateLinks = false;
    protected bool $_actionUploadFileError = false;
    protected string $_actionUploadFileErrorMessage = 'Initialisation du transfert.';
    public function getUploadObject(): bool
    {
        return $this->_actionUploadFile;
    }
    public function getUploadObjectID(): string
    {
        return $this->_actionUploadFileID;
    }
    public function getUploadObjectError(): bool
    {
        return $this->_actionUploadFileError;
    }
    public function getUploadObjectErrorMessage(): string
    {
        return $this->_actionUploadFileErrorMessage;
    }
    protected function _extractActionUploadFile(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupUploadFile'))
            return;

        $this->_metrologyInstance->addLog('extract action upload file', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $uploadArgName = self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE;
        if (!isset($_FILES[$uploadArgName]))
            return;
        $uploadRawName = $_FILES[$uploadArgName]['name'];
        $uploadError = $_FILES[$uploadArgName]['error'];

        switch ($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['error']) {
            case UPLOAD_ERR_OK:
                // Extraction des méta données du fichier.
                $upfname = mb_convert_encoding(strtok(trim((string)filter_var($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['name'], FILTER_SANITIZE_STRING)), "\n"), 'UTF-8');
                $upinfo = pathinfo($upfname);
                $upext = $upinfo['extension'];
                $upname = basename($upfname, '.' . $upext);
                $upsize = $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['size'];
                $uppath = $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['tmp_name'];
                $uptype = '';
                // Si le fichier est bien téléchargé.
                if (file_exists($uppath)) {
                    // Si le fichier n'est pas trop gros.
                    if ($upsize <= $this->_configurationInstance->getOptionUntyped('ioReadMaxData')) {
                        // Lit le type mime.
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $uptype = finfo_file($finfo, $uppath);
                        finfo_close($finfo);
                        if ($uptype == 'application/octet-stream') {
                            $uptype = $this->_getFilenameTypeMime("$upname.$upext");
                        }

                        // Extrait les options de téléchargement.
                        $argUpd = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_UPDATE);
                        $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_PROTECT);
                        $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_OBFUSCATE_LINKS);

                        // Ecriture des variables.
                        $this->_actionUploadFile = true;
                        $this->_actionUploadFileName = $upname;
                        $this->_actionUploadFileExtension = $upext;
                        $this->_actionUploadFileType = $uptype;
                        $this->_actionUploadFileSize = $upsize;
                        $this->_actionUploadFilePath = $uppath;
                        $this->_actionUploadFileUpdate = $argUpd;
                        if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject')) {
                            $this->_actionUploadFileProtect = $argPrt;
                        }
                        if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')) {
                            $this->_actionUploadFileObfuscateLinks = $argObf;
                        }
                    } else {
                        $this->_metrologyInstance->addLog('action _extractActionUploadFile ioReadMaxData exeeded', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                        $this->_actionUploadFileError = true;
                        $this->_actionUploadFileErrorMessage = 'Le fichier dépasse la taille limite de transfert.';
                    }
                } else {
                    $this->_metrologyInstance->addLog('action _extractActionUploadFile upload error', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "No uploaded file.";
                }
                unset($upfname, $upinfo, $upext, $upname, $upsize, $uppath, $uptype);
                break;
            case UPLOAD_ERR_INI_SIZE:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_INI_SIZE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_FORM_SIZE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_PARTIAL', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_NO_FILE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_NO_TMP_DIR', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_CANT_WRITE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_EXTENSION', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop.";
                break;
        }
    }
    protected function _getFilenameTypeMime(string $f): string
    {
        $m = '/etc/mime.types'; // Chemin du fichier pour trouver le type mime.
        $e = substr(strrchr($f, '.'), 1);
        if (empty($e))
            return 'application/octet-stream';
        $r = "/^([\w\+\-\.\/]+)\s+(\w+\s)*($e\s)/i";
        $ls = file($m);
        foreach ($ls as $l) {
            if (substr($l, 0, 1) == '#')
                continue;
            $l = rtrim($l) . ' ';
            if (!preg_match($r, $l, $m))
                continue;
            return $m[1];
        }
        return 'application/octet-stream';
    }
    protected function _actionUploadFile(): void
    {
        $this->_metrologyInstance->addLog('action upload file', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        // Lit le contenu du fichier.
        $data = file_get_contents($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['tmp_name']);

        // Ecrit le contenu dans l'objet.
        $instance = new Node($this->_nebuleInstance, '0', $data, $this->_actionUploadFileProtect);
        if ($instance === false) {
            $this->_metrologyInstance->addLog('action _actionUploadFile cant create object instance', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
            return;
        }

        // Lit l'ID.
        $id = $instance->getID();
        unset($data);
        if ($id == '0') {
            $this->_metrologyInstance->addLog('action _actionUploadFile cant create object', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'objet n'a pas pu être créé.";
            return;
        }
        $this->_actionUploadFileID = $id;

        // Définition de la date et le signataire.
        $date = date(DATE_ATOM);
        $signer = $this->_entitiesInstance->getGhostEntityEID();

        // Création du type mime.
        $instance->setType($this->_actionUploadFileType);

        // Crée l'objet du nom.
        $instance->setName($this->_actionUploadFileName);

        // Crée l'objet de l'extension.
        $instance->setSuffixName($this->_actionUploadFileExtension);

        // Si mise à jour de l'objet en cours.
        if ($this->_actionUploadFileUpdate) {
            $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
            $instanceBL->addLink('u>' . $this->_applicationInstance->getCurrentObjectID() . '>' . $id);
            $instanceBL->signWrite($this->_entitiesInstance->getGhostEntityInstance(), '');
        }
    }

    protected bool $_actionUploadText = false;
    protected string $_actionUploadTextName = '';
    protected string $_actionUploadTextType = '';
    protected string $_actionUploadTextContent = '';
    protected string $_actionUploadTextID = '0';
    protected bool $_actionUploadTextProtect = false;
    protected bool $_actionUploadTextObfuscateLinks = false;
    protected bool $_actionUploadTextError = false;
    protected string $_actionUploadTextErrorMessage = 'Initialisation du transfert.';
    public function getUploadText(): bool { return $this->_actionUploadText; }
    public function getUploadTextName(): string { return $this->_actionUploadTextName; }
    public function getUploadTextID(): string { return $this->_actionUploadTextID; }
    public function getUploadTextError(): bool { return $this->_actionUploadTextError; }
    public function getUploadTextErrorMessage(): string { return $this->_actionUploadTextErrorMessage; }
    protected function _extractActionUploadText(): void {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupUploadText'))
            return;

        $this->_metrologyInstance->addLog('extract action upload text', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT);

        // Extraction du lien et stockage pour traitement.
        if ($arg !== false) {
            $argText = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argName = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argType = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_TYPE, FILTER_FLAG_NO_ENCODE_QUOTES);

            // Extrait les options de téléchargement.
            $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_PROTECT);
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_OBFUSCATE_LINKS);

            if (strlen($argText) != 0) {
                $this->_actionUploadText = true;
                $this->_actionUploadTextContent = $argText;
                $this->_actionUploadTextName = $argName;
                if ($argType != '')
                    $this->_actionUploadTextType = $argType;
                else
                    $this->_actionUploadTextType = References::REFERENCE_OBJECT_TEXT;

                if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
                    $this->_actionUploadTextProtect = $argPrt;
                if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                    $this->_actionUploadTextObfuscateLinks = $argObf;
            }
        }
    }
    protected function _actionUploadText(): void {
        $this->_metrologyInstance->addLog('action upload text', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        // Crée l'instance de l'objet.
        $instance = new Node($this->_nebuleInstance, '0', $this->_actionUploadTextContent, $this->_actionUploadTextProtect);
        if ($instance === false) {
            $this->_metrologyInstance->addLog('action _actionUploadText cant create object instance', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
            return;
        }

        // Lit l'ID.
        $id = $instance->getID();
        if ($id == '0') {
            $this->_metrologyInstance->addLog('action _actionUploadText cant create object', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'objet n'a pas pu être créé.";
            return;
        }
        $this->_actionUploadTextID = $id;

        // Définition de la date et du signataire.
        //$signer	= $this->_nebuleInstance->getCurrentEntity();
        //$date = date(DATE_ATOM);

        $instance->setType($this->_actionUploadTextType);
        $instance->setName($this->_actionUploadTextName);

        unset($id, $instance);
    }



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
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup'))
            return;

        $this->_metrologyInstance->addLog('extract action create group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_GROUP);

        if ($argCreate !== false)
            $this->_actionCreateGroup = true;

        // Si on crée une nouvelle entité.
        if ($this->_actionCreateGroup) {
            $argName = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_GROUP_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);

            // Extrait les options de téléchargement.
            $argCld = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_GROUP_CLOSED);
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_GROUP_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            $this->_actionCreateGroupName = $argName;
            $this->_actionCreateGroupClosed = $argCld;
            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateGroupObfuscateLinks = $argObf;
        }
    }
    protected function _actionCreateGroup(): void
    {
        $this->_metrologyInstance->addLog('action create group', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

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
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupDeleteGroup'))
            return;

        $this->_metrologyInstance->addLog('extract action delete group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argDelete = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_DELETE_GROUP, FILTER_FLAG_ENCODE_LOW);

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
        $this->_metrologyInstance->addLog('action delete group ' . $this->_actionDeleteGroupID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

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
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddToGroup'))
            return;

        $this->_metrologyInstance->addLog('extract action add to group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_TO_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionAddToGroup = $arg;
    }
    protected function _actionAddToGroup(): void
    {
        $this->_metrologyInstance->addLog('action add to group ' . $this->_actionAddToGroup, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
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
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupRemoveFromGroup'))
            return;

        $this->_metrologyInstance->addLog('extract action remove from group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_REMOVE_FROM_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionRemoveFromGroup = $arg;
    }
    protected function _actionRemoveFromGroup(): void
    {
        $this->_metrologyInstance->addLog('action remove from group ' . $this->_actionRemoveFromGroup, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
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
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddItemToGroup'))
            return;

        $this->_metrologyInstance->addLog('extract action add item to group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_ITEM_TO_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionAddItemToGroup = $arg;
    }
    protected function _actionAddItemToGroup(): void
    {
        $this->_metrologyInstance->addLog('action add item to group ' . $this->_actionAddItemToGroup, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $this->_nebuleInstance->getCurrentGroupInstance()->setMember($this->_actionAddItemToGroup);
    }

    protected string $_actionRemoveItemFromGroup = '';
    public function getRemoveItemFromGroup(): string
    {
        return $this->_actionRemoveItemFromGroup;
    }
    protected function _extractActionRemoveItemFromGroup(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupRemoveItemFromGroup'))
            return;

        $this->_metrologyInstance->addLog('extract action remove item from group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionRemoveItemFromGroup = $arg;
    }
    protected function _actionRemoveItemFromGroup(): void
    {
        $this->_metrologyInstance->addLog('action remove item from group ' . $this->_actionRemoveItemFromGroup, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $this->_nebuleInstance->getCurrentGroupInstance()->unsetMember($this->_actionRemoveItemFromGroup);
    }

    protected bool $_actionCreateConversation = false;
    protected string $_actionCreateConversationName = '';
    protected string $_actionCreateConversationID = '0';
    protected bool $_actionCreateConversationClosed = false;
    protected bool $_actionCreateConversationProtected = false;
    protected bool $_actionCreateConversationObfuscateLinks = false;
    protected ?Conversation $_actionCreateConversationInstance = null;
    protected bool $_actionCreateConversationError = false;
    protected string $_actionCreateConversationErrorMessage = 'Initialisation de la création.';
    public function getCreateConversation(): bool
    {
        return $this->_actionCreateConversation;
    }
    public function getCreateConversationID(): string
    {
        return $this->_actionCreateConversationID;
    }
    public function getCreateConversationInstance(): ?Conversation
    {
        return $this->_actionCreateConversationInstance;
    }
    public function getCreateConversationError(): bool
    {
        return $this->_actionCreateConversationError;
    }
    public function getCreateConversationErrorMessage(): string
    {
        return $this->_actionCreateConversationErrorMessage;
    }
    protected function _extractActionCreateConversation(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action create group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION);

        if ($argCreate !== false)
            $this->_actionCreateConversation = true;

        // Si on crée une nouvelle conversation.
        if ($this->_actionCreateConversation) {
            $argName = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);

            // Extrait les options de téléchargement.
            $argCld = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_CLOSED);
            $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_PROTECTED);
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            $this->_actionCreateConversationName = $argName;
            $this->_actionCreateConversationClosed = $argCld;
            if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
                $this->_actionCreateConversationProtected = $argPrt;
            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateConversationObfuscateLinks = $argObf;
        }
    }
    protected function _actionCreateConversation(): void
    {
        $this->_metrologyInstance->addLog('action create conversation', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        // Création de la nouvelle conversation.
        $instance = new Conversation(
            $this->_nebuleInstance,
            'new',
            $this->_actionCreateConversationClosed,
            $this->_actionCreateConversationProtected,
            $this->_actionCreateConversationObfuscateLinks);

        if (is_a($instance, 'Nebule\Library\Conversation')
            && $instance->getID() != '0'
        ) {
            $this->_actionCreateConversationError = false;
            $instance->setName($this->_actionCreateConversationName);

            $this->_actionCreateConversationInstance = $instance;
            $this->_actionCreateConversationID = $instance->getID();
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('action _actionCreateConversation cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionCreateConversationError = true;
            $this->_actionCreateConversationErrorMessage = 'Echec de la génération.';
        }
    }

    protected bool $_actionDeleteConversation = false;
    protected string $_actionDeleteConversationID = '0';
    protected bool $_actionDeleteConversationError = false;
    protected string $_actionDeleteConversationErrorMessage = 'Initialisation de la supression.';
    public function getDeleteConversation(): bool
    {
        return $this->_actionDeleteConversation;
    }
    public function getDeleteConversationID(): string
    {
        return $this->_actionDeleteConversationID;
    }
    public function getDeleteConversationError(): bool
    {
        return $this->_actionDeleteConversationError;
    }
    public function getDeleteConversationErrorMessage(): string
    {
        return $this->_actionDeleteConversationErrorMessage;
    }
    protected function _extractActionDeleteConversation(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupDeleteConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action delete conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argDelete = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_DELETE_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if ($argDelete !== ''
            && strlen($argDelete) >= BlocLink::NID_MIN_HASH_SIZE
            && Node::checkNID($argDelete)
        ) {
            $this->_actionDeleteConversation = true;
            $this->_actionDeleteConversationID = $argDelete;
        }
    }
    protected function _actionDeleteConversation(): void
    {
        $this->_metrologyInstance->addLog('action delete conversation ' . $this->_actionDeleteConversationID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        // Suppression.
        $instance = $this->_cacheInstance->newNode($this->_actionDeleteConversationID, \Nebule\Library\Cache::TYPE_CONVERSATION);
        if (!is_a($instance, 'Nebule\Library\Conversation')
            || $instance->getID() == '0'
            || !$instance->getIsConversation('myself')
        ) {
            $this->_actionDeleteConversationError = false;
            $this->_actionDeleteConversationErrorMessage = 'Pas un conversation.';
            $this->_metrologyInstance->addLog('action delete not a group', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            return;
        }
        if ($instance->getIsConversationClosed()) {
            $this->_metrologyInstance->addLog('action delete conversation closed', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            $instance->setUnmarkConversationClosed();
            $test = $instance->getIsConversationClosed();
        } else {
            $this->_metrologyInstance->addLog('action delete conversation', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            $instance->setUnmarkConversationOpened();
            $test = $instance->getIsConversationOpened();
        }

        // Vérification.
        if ($test) {
            // Si ce n'est pas bon.
            $this->_metrologyInstance->addLog('action _actionDeleteConversation cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionDeleteConversationError = true;
            $this->_actionDeleteConversationErrorMessage = 'Echec de la génération.';
        }
    }

    protected string $_actionAddMessageOnConversation = '';
    public function getAddMessageOnConversation(): string
    {
        return $this->_actionAddMessageOnConversation;
    }
    protected function _extractActionAddMessageOnConversation(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddMessageOnConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action add to conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_TO_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionAddMessageOnConversation = $arg;
    }
    protected function _actionAddMessageOnConversation(): void
    {
        $this->_metrologyInstance->addLog('action add message to conversation ' . $this->_actionAddMessageOnConversation, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instanceConversation = $this->_cacheInstance->newNode($this->_actionAddMessageOnConversation, \Nebule\Library\Cache::TYPE_CONVERSATION);
        $instanceConversation->setMember($this->_nebuleInstance->getCurrentObjectOID(), false);
    }

    protected string $_actionRemoveMessageOnConversation = '';
    public function getRemoveMessageOnConversation(): string
    {
        return $this->_actionRemoveMessageOnConversation;
    }
    protected function _extractActionRemoveMessageOnConversation(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupRemoveMessageOnConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action remove from conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_REMOVE_FROM_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionRemoveMessageOnConversation = $arg;
    }
    protected function _actionRemoveMessageOnConversation(): void
    {
        $this->_metrologyInstance->addLog('action remove message to conversation ' . $this->_actionRemoveMessageOnConversation, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instanceConversation = $this->_cacheInstance->newNode($this->_actionRemoveMessageOnConversation, \Nebule\Library\Cache::TYPE_CONVERSATION);
        $instanceConversation->unsetMember($this->_nebuleInstance->getCurrentObjectOID());
    }

    protected string $_actionAddMemberOnConversation = '';
    public function getAddMemberOnConversation(): string
    {
        return $this->_actionAddMemberOnConversation;
    }
    protected function _extractActionAddMemberOnConversation(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddMemberOnConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action add item to conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_ITEM_TO_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionAddMemberOnConversation = $arg;
    }
    protected function _actionAddMemberOnConversation(): void
    {
        $this->_metrologyInstance->addLog('action add member to conversation ' . $this->_actionAddMemberOnConversation, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instanceConversation = $this->_cacheInstance->newNode($this->_actionAddMemberOnConversation, \Nebule\Library\Cache::TYPE_CONVERSATION);
        $instanceConversation->setFollower($this->_nebuleInstance->getCurrentObjectOID());
    }

    protected string $_actionRemoveMemberOnConversation = '';
    public function getRemoveMemberOnConversation(): string
    {
        return $this->_actionRemoveMemberOnConversation;
    }
    protected function _extractActionRemoveMemberOnConversation(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupRemoveMemberOnConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action remove item from conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionRemoveMemberOnConversation = $arg;
    }
    protected function _actionRemoveMemberOnConversation(): void
    {
        $this->_metrologyInstance->addLog('action remove member to conversation ' . $this->_actionRemoveMemberOnConversation, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instanceConversation = $this->_cacheInstance->newNode($this->_actionRemoveMemberOnConversation, \Nebule\Library\Cache::TYPE_CONVERSATION);
        $instanceConversation->unsetFollower($this->_nebuleInstance->getCurrentObjectOID());
    }

    protected bool $_actionCreateMessage = false;
    protected string $_actionCreateMessageID = '0';
    protected bool $_actionCreateMessageError = false;
    protected bool $_actionCreateMessageProtected = false;
    protected bool $_actionCreateMessageObfuscateLinks = false;
    protected string $_actionCreateMessageErrorMessage = 'Initialisation de la suppression.';
    protected function _extractActionCreateMessage(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateMessage'))
            return;

        $this->_metrologyInstance->addLog('extract action create message', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_MESSAGE);

        if ($argCreate !== false)
            $this->_actionCreateMessage = true;

        // Si on crée un nouveau message.
        if ($this->_actionCreateMessage) {
            // Extrait les options de téléchargement.
            $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_PROTECT);
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
                $this->_actionCreateMessageProtected = $argPrt;
            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateMessageObfuscateLinks = $argObf;
        }

        // Le reste des valeurs est récupéré par la partie création d'un texte.
    }
    protected function _actionCreateMessage(): void
    {
        $id = $this->_actionUploadTextID;
        $this->_metrologyInstance->addLog('action create message ' . $id, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        if ($this->_actionCreateMessageProtected) {
            $this->_metrologyInstance->addLog('action create message protected', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        }
        if ($this->_actionCreateMessageObfuscateLinks) {
            $this->_metrologyInstance->addLog('action create message with obfuscated links', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        }

        $instanceMessage = $this->_cacheInstance->newNode($id);

        if (is_a($instanceMessage, 'Nebule\Library\Node')
            && $instanceMessage->getID() != '0'
        ) {
            $this->_actionCreateConversationError = false;

            $instanceConversation = $this->_nebuleInstance->getCurrentConversationInstance();
            $instanceConversation->setMember($id, $this->_actionCreateMessageObfuscateLinks);

            unset($instanceConversation);

            $this->_actionCreateMessageID = $id;
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('action _actionCreateMessage cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionCreateMessageError = true;
            $this->_actionCreateMessageErrorMessage = 'Echec de la génération.';
        }
    }

    protected string $_actionAddProperty = '';
    protected string $_actionAddPropertyObject = '';
    protected string $_actionAddPropertyValue = '';
    protected bool $_actionAddPropertyProtected = false;
    protected bool $_actionAddPropertyObfuscateLinks = false;
    protected bool $_actionAddPropertyError = false;
    protected string $_actionAddPropertyErrorMessage = 'Initialisation de la supression.';
    public function getAddPropertyError(): bool
    {
        return $this->_actionAddPropertyError;
    }
    public function getAddPropertyErrorMessage(): string
    {
        return $this->_actionAddPropertyErrorMessage;
    }
    protected function _extractActionAddProperty(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupAddProperty'))
            return;

        $this->_metrologyInstance->addLog('extract action add property', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argAdd = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY, FILTER_FLAG_ENCODE_LOW);

        if ($argAdd != '')
            $this->_actionAddProperty = $argAdd;

        // Si on crée une nouvelle propriété.
        if ($this->_actionAddProperty != '') {
            $argObj = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_OBJECT, FILTER_FLAG_ENCODE_LOW);
            $argVal = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_VALUE);
            $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_PROTECTED);
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            if ($argVal != '') {
                if ($argObj == '')
                    $this->_actionAddPropertyObject = $this->_nebuleInstance->getCurrentObjectOID();
                else
                    $this->_actionAddPropertyObject = $argObj;
                $this->_actionAddPropertyValue = $argVal;
                if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
                    $this->_actionAddPropertyProtected = $argPrt;
                if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                    $this->_actionAddPropertyObfuscateLinks = $argObf;
            } else {
                $this->_metrologyInstance->addLog('action _extractActionAddProperty null value', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionAddPropertyError = true;
                $this->_actionAddPropertyErrorMessage = 'Valeur vide.';
            }
        }
    }
    protected function _actionAddProperty(): void
    {
        $prop = $this->_actionAddProperty;
        $propID = $this->getNidFromData($prop);
        $this->_metrologyInstance->addLog('action add property ' . $prop, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $objectID = $this->_actionAddPropertyObject;
        $this->_metrologyInstance->addLog('action add property for ' . $objectID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $value = $this->_actionAddPropertyValue;
        $valueID = $this->getNidFromData($value);
        $this->_metrologyInstance->addLog('action add property value : ' . $value, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $protected = $this->_actionAddPropertyProtected;
        if ($protected) {
            $this->_metrologyInstance->addLog('action add property protected', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        }
        if ($this->_actionAddPropertyObfuscateLinks) {
            $this->_metrologyInstance->addLog('action add property with obfuscated links', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        }

        if (!$this->_nebuleInstance->getIoInstance()->checkObjectPresent($propID)) {
            $this->createTextAsObject($prop);
        }
        if (!$this->_nebuleInstance->getIoInstance()->checkObjectPresent($valueID)) {
            $this->createTextAsObject($value, $protected, $this->_actionAddPropertyObfuscateLinks);
        }

        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $instanceBL->addLink('l>' . $objectID . '>' . $valueID . '>' . $propID);
        $instanceBL->signWrite($this->_entitiesInstance->getGhostEntityInstance(), '');
    }

    protected bool $_actionCreateCurrency = false;
    protected string $_actionCreateCurrencyID = '';
    protected ?Currency $_actionCreateCurrencyInstance = null;
    protected array $_actionCreateCurrencyParam = array();
    protected bool $_actionCreateCurrencyError = false;
    protected string $_actionCreateCurrencyErrorMessage = 'Initialisation de la création.';
    public function getCreateCurrency(): bool
    {
        return $this->_actionCreateCurrency;
    }
    public function getCreateCurrencyID(): string
    {
        return $this->_actionCreateCurrencyID;
    }
    public function getCreateCurrencyInstance(): ?Currency
    {
        return $this->_actionCreateCurrencyInstance;
    }
    public function getCreateCurrencyParam(): array
    {
        return $this->_actionCreateCurrencyParam;
    }
    public function getCreateCurrencyError(): bool
    {
        return $this->_actionCreateCurrencyError;
    }
    public function getCreateCurrencyErrorMessage(): string
    {
        return $this->_actionCreateCurrencyErrorMessage;
    }
    /**
     * Extrait pour action si une monnaie doit être créé.
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
     * @return void
     */
    protected function _extractActionCreateCurrency(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateCurrency'))
            return;

        $this->_metrologyInstance->addLog('extract action create currency', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_CURRENCY);

        if ($argCreate !== false)
            $this->_actionCreateCurrency = true;

        if ($this->_actionCreateCurrency) {
            // Récupère la liste des propriétés.
            $instance = $this->_nebuleInstance->getCurrentCurrencyInstance();
            $propertiesList = $instance->getPropertiesList();
            unset($instance);

            /*foreach ($propertiesList['currency'] as $name => $property) {
                // Extrait une valeur.
                if (isset($property['checkbox'])) {
                    $value = '';
                    try {
                        $valueArray = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    } catch (\Exception $e) {
                        $valueArray = '';
                    }
                    $valueArray = $this->getFilterInput($property['shortname'], FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    foreach ($valueArray as $item)
                        $value .= ' ' . trim($item);
                    $this->_actionCreateCurrencyParam[$name] = trim($value);
                    unset($value, $valueArray);
                } else {
                    try {
                        $this->_actionCreateCurrencyParam[$name] = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    } catch (\Exception $e) {
                        $this->_actionCreateCurrencyParam[$name] = '';
                    }
                    $this->_actionCreateCurrencyParam[$name] = trim($this->_actionCreateCurrencyParam[$name]);
                }
                $this->_metrologyInstance->addLog('extract action create currency - _' . $property['shortname'] . ':' . $this->_actionCreateCurrencyParam[$name], Metrology::LOG_LEVEL_DEVELOP, __METHOD__, '00000000');

                // Extrait si forcé.
                if (isset($property['forceable'])) {
                    $this->_actionCreateCurrencyParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                    if ($this->_actionCreateCurrencyParam['Force' . $name])
                        $this->_metrologyInstance->addLog('extract action create currency - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                }
            }*/
        }
    }
    protected function _actionCreateCurrency(): void
    {
        $this->_metrologyInstance->addLog('action create currency', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instance = new Currency($this->_nebuleInstance, 'new', $this->_actionCreateCurrencyParam, false, false);

        if (is_a($instance, 'Nebule\Library\Currency')
            && $instance->getID() != '0'
        ) {
            $this->_actionCreateCurrencyError = false;

            $this->_actionCreateCurrencyInstance = $instance;
            $this->_actionCreateCurrencyID = $instance->getID();

            $this->_metrologyInstance->addLog('action _actionCreateCurrency generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('action _actionCreateCurrency cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionCreateCurrencyError = true;
            $this->_actionCreateCurrencyErrorMessage = 'Echec de la génération.';
        }
    }

    protected bool $_actionCreateTokenPool = false;
    protected string $_actionCreateTokenPoolID = '';
    protected ?TokenPool $_actionCreateTokenPoolInstance = null;
    protected array $_actionCreateTokenPoolParam = array();
    protected bool $_actionCreateTokenPoolError = false;
    protected string $_actionCreateTokenPoolErrorMessage = 'Initialisation de la création.';
    public function getCreateTokenPool(): bool
    {
        return $this->_actionCreateTokenPool;
    }
    public function getCreateTokenPoolID(): string
    {
        return $this->_actionCreateTokenPoolID;
    }
    public function getCreateTokenPoolInstance(): TokenPool
    {
        return $this->_actionCreateTokenPoolInstance;
    }
    public function getCreateTokenPoolParam(): array
    {
        return $this->_actionCreateTokenPoolParam;
    }
    public function getCreateTokenPoolError(): bool
    {
        return $this->_actionCreateTokenPoolError;
    }
    public function getCreateTokenPoolErrorMessage(): string
    {
        return $this->_actionCreateTokenPoolErrorMessage;
    }
    protected function _extractActionCreateTokenPool(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateTokenPool'))
            return;

        $this->_metrologyInstance->addLog('extract action create token pool', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_TOKEN_POOL);

        if ($argCreate !== false)
            $this->_actionCreateTokenPool = true;

        if ($this->_actionCreateTokenPool) {
            // Récupère la liste des propriétés.
            $instance = $this->_nebuleInstance->getCurrentTokenPoolInstance();
            $propertiesList = $instance->getPropertiesList();
            unset($instance);

            /*foreach ($propertiesList['tokenpool'] as $name => $property) {
                // Extrait une valeur.
                if (isset($property['checkbox'])) {
                    $value = '';
                    try {
                        $valueArray = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    } catch (\Exception $e) {
                        $valueArray = '';
                    }
                    foreach ($valueArray as $item)
                        $value .= ' ' . trim($item);
                    $this->_actionCreateTokenPoolParam[$name] = trim($value);
                    unset($value, $valueArray);
                } else {
                    try {
                        $this->_actionCreateTokenPoolParam[$name] = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    } catch (\Exception $e) {
                        $this->_actionCreateTokenPoolParam[$name] = '';
                    }
                    $this->_actionCreateTokenPoolParam[$name] = trim($this->_actionCreateTokenPoolParam[$name]);
                }
                $this->_metrologyInstance->addLog('extract action create token pool - p' . $property['key'] . ':' . $this->_actionCreateTokenPoolParam[$name], Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

                // Extrait si forcé.
                if (isset($property['forceable'])) {
                    $this->_actionCreateTokenPoolParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                    if ($this->_actionCreateTokenPoolParam['Force' . $name])
                        $this->_metrologyInstance->addLog('extract action create token pool - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                }
            }*/
        }
    }
    protected function _actionCreateTokenPool(): void
    {
        $this->_metrologyInstance->addLog('action create token pool', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instance = new TokenPool($this->_nebuleInstance, 'new', $this->_actionCreateTokenPoolParam, false, false);

        if (is_a($instance, 'Nebule\Library\TokenPool')
            && $instance->getID() != '0'
        ) {
            $this->_actionCreateTokenPoolError = false;

            $this->_actionCreateTokenPoolInstance = $instance;
            $this->_actionCreateTokenPoolID = $instance->getID();

            $this->_metrologyInstance->addLog('action _actionCreateTokenPool generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('action _actionCreateTokenPool cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionCreateTokenPoolError = true;
            $this->_actionCreateTokenPoolErrorMessage = 'Echec de la génération.';
        }
    }

    protected bool $_actionCreateTokens = false;
    protected array $_actionCreateTokensID = array();
    protected array $_actionCreateTokensInstance = array();
    protected array $_actionCreateTokensParam = array();
    protected int $_actionCreateTokensCount = 1;
    protected bool $_actionCreateTokensError = false;
    protected string $_actionCreateTokensErrorMessage = 'Initialisation de la création.';
    public function getCreateTokens(): bool
    {
        return $this->_actionCreateTokens;
    }
    public function getCreateTokensID(): array
    {
        return $this->_actionCreateTokensID;
    }
    public function getCreateTokensInstance(): array
    {
        return $this->_actionCreateTokensInstance;
    }
    public function getCreateTokensParam(): array
    {
        return $this->_actionCreateTokensParam;
    }
    public function getCreateTokensError(): bool
    {
        return $this->_actionCreateTokensError;
    }
    public function getCreateTokensErrorMessage(): string
    {
        return $this->_actionCreateTokensErrorMessage;
    }
    protected function _extractActionCreateTokens(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateTokens'))
            return;

        $this->_metrologyInstance->addLog('extract action create tokens', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_TOKENS);

        if ($argCreate !== false)
            $this->_actionCreateTokens = true;

        if ($this->_actionCreateTokens) {
            // Récupère la liste des propriétés.
            $instance = $this->_tokenizeInstance->getCurrentTokenInstance();
            $propertiesList = $instance->getPropertiesList();
            unset($instance);

            /*foreach ($propertiesList['token'] as $name => $property) {
                // Extrait une valeur.
                if (isset($property['checkbox'])) {
                    $value = '';
                    try {
                        $valueArray = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    } catch (\Exception $e) {
                        $valueArray = '';
                    }
                    foreach ($valueArray as $item)
                        $value .= ' ' . trim($item);
                    $this->_actionCreateTokensParam[$name] = trim($value);
                    unset($value, $valueArray);
                } else {
                    try {
                        $this->_actionCreateTokensParam[$name] = trim((string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
                    } catch (\Exception $e) {
                        $this->_actionCreateTokensParam[$name] = '';
                    }
                    $this->_actionCreateTokensParam[$name] = trim($this->_actionCreateTokensParam[$name]);
                }
                $this->_metrologyInstance->addLog('extract action create tokens - t' . $property['key'] . ':' . $this->_actionCreateTokensParam[$name], Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

                // Extrait si forcé.
                if (isset($property['forceable'])) {
                    $this->_actionCreateTokensParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                    if ($this->_actionCreateTokensParam['Force' . $name])
                        $this->_metrologyInstance->addLog('extract action create tokens - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                }
            }*/

            // Extrait le nombre de jetons à générer.
            $this->_actionCreateTokensCount = (int)$this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_TOKENS_COUNT, FILTER_SANITIZE_NUMBER_INT);
        }
    }
    protected function _actionCreateTokens(): void
    {
        $this->_metrologyInstance->addLog('action create tokens', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        /*$instance = $this->_tokenizeInstance->getCurrentTokenInstance(); FIXME
        for ($i = 0; $i < $this->_actionCreateTokensCount; $i++) {
            if ($i > 0) {
                $this->_actionCreateTokensParam['TokenSerialID'] = '';
            }

            $instance = new Token($this->_nebuleInstance, 'new', $this->_actionCreateTokensParam, false, false);

            if (is_a($instance, 'Nebule\Library\Token')
                && $instance->getID() != '0'
            ) {
                $this->_actionCreateTokensError = false;

                $this->_actionCreateTokensInstance[$i] = $instance;
                $this->_actionCreateTokensID[$i] = $instance->getID();

                $this->_metrologyInstance->addLog('action _actionCreateTokens generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrologyInstance->addLog('action _actionCreateTokens cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionCreateTokensError = true;
                $this->_actionCreateTokensErrorMessage = 'Echec de la génération.';

                break;
            }
        }*/
    }



    /**
     * Ecrit un lien pré-signé.
     * Cette fonction est appelée par la fonction specialActions().
     * Elle est utilisée par l'application upload et le module module_upload de l'application sylabe.
     * Le fonctioneement est identique dans ces deux usages même si l'affichage ne le montre pas.
     * La fonction nécessite au minimum les droits :
     *   - permitWrite
     *   - permitWriteLink
     *   - permitUploadLink
     * L'activation de la fonction est ensuite conditionnée par une conbinaison d'autres droits ou facteurs.
     * Si le droit permitPublicUploadCodeAuthoritiesLink est activé :
     *   les liens signés du maître du code sont acceptés ;
     *   les liens des autres entités sont ignorés avec seulement ce droit.
     * Si le droit permitPublicUploadLink est activé :
     *   tous les liens signés sont acceptés ;
     *   les entités signataires doivent exister localement pour la vérification les signatures.
     * Si l'entité en cours est déverrouillée, this->_unlocked :
     *   la réception de liens est prise comme une action légitime ;
     *   les liens signés de toutes les entités sont acceptés ;
     *   les liens non signés sont signés par l'entité en cours.
     * Si un lien est structurellement valide mais non signé, il est régénéré et signé par l'entité en cours.
     * Les liens ne sont écris que si leurs signatures sont valides.
     *
     * @param LinkRegister $link
     * @return void
     */
    protected function _actionUploadLink_DISABLED(LinkRegister $link): void
    {
        /*if (!$link->getValid()
            || true // FIXME
        )
            return;
        
        $this->_metrologyInstance->addLog('action upload link', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        if ($link->getSigned()
            && (($link->getSignersEID() == $this->_authoritiesInstance->getCodeAuthoritiesEID()
                    && $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                )
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_unlocked
            )
        ) {
            $link->write();
            $this->_metrologyInstance->addLog('action upload link - signed link ' . $link->getRaw(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
        } elseif ($this->_unlocked) {
            $link = $this->_cacheInstance->newBlockLink(
                '0_'
                . $this->_entitiesInstance->getCurrentEntityID() . '_'
                . $link->getDate() . '_'
                . $link->getParsed()['bl/rl/req'] . '_'
                . $link->getParsed()['bl/rl/nid1'] . '_'
                . $link->getParsed()['bl/rl/nid2'] . '_'
                . $link->getParsed()['bl/rl/nid3']
            );
            $link->signWrite();
            $this->_metrologyInstance->addLog('action upload link - unsigned link ' . $link->getRaw(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
        }*/
    }


    /**
     * Create new link.
     *
     * @param Entity $eid1
     * @param string $req
     * @param string $nid1
     * @param string $nid2
     * @param string $nid3
     * @param bool   $obfuscate
     * @return boolean
     */
    protected function _createLink(Entity $eid1, string $req, string $nid1, string $nid2, string $nid3, bool $obfuscate = false): bool
    {
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $instanceBL->addLink($req . '>' . $nid1 . '>' . $nid2 . '>' . $nid3);
        return $instanceBL->signWrite($eid1, '');
    }
}
