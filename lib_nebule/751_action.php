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
abstract class Actions extends Functions
{
    const DEFAULT_COMMAND_APPLICATION = 'a';
    const DEFAULT_COMMAND_NEBULE_BOOTSTRAP = 'a=1';
    const DEFAULT_COMMAND_NEBULE_FLUSH = 'f';
    const DEFAULT_COMMAND_NEBULE_RESCUE = 'r';
    const DEFAULT_COMMAND_ACTION_SIGN_LINK1 = 'actsiglnk1';
    const DEFAULT_COMMAND_ACTION_SIGN_LINK1_OBFUSCATE = 'actsiglnk1o';
    const DEFAULT_COMMAND_ACTION_SIGN_LINK2 = 'actsiglnk2';
    const DEFAULT_COMMAND_ACTION_SIGN_LINK2_OBFUSCATE = 'actsiglnk2o';
    const DEFAULT_COMMAND_ACTION_SIGN_LINK3 = 'actsiglnk3';
    const DEFAULT_COMMAND_ACTION_SIGN_LINK3_OBFUSCATE = 'actsiglnk3o';
    const DEFAULT_COMMAND_ACTION_UPLOAD_SIGNED_LINK = 'actupsl';
    const DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS = 'actupfl';
    const DEFAULT_COMMAND_ACTION_OBFUSCATE_LINK = 'actobflnk';
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
    const DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY = 'actsynent';
    const DEFAULT_COMMAND_ACTION_SYNCHRONIZE_NEW_ENTITY = 'actsynnewent';
    const DEFAULT_COMMAND_ACTION_SYNCHRONIZE_LINKS = 'actsynlnk';
    const DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION = 'actsynapp';
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
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY = 'creaent';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PREFIX = 'creaentprefix';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_SUFFIX = 'creaentsuffix';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_FIRSTNAME = 'creaentfstnam';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NIKENAME = 'creaentniknam';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NAME = 'creaentnam';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD1 = 'creaentpwd1';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD2 = 'creaentpwd2';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_ALGORITHM = 'creaentalgo';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_TYPE = 'creaenttype';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_AUTONOMY = 'creaentaut';
    const DEFAULT_COMMAND_ACTION_CREATE_ENTITY_OBFUSCATE_LINKS = 'creaentobf';
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

    public function __destruct() { return true; }
    public function __toString() { return 'Action'; }
    public function __sleep() { return array(); } // TODO do not cache



    protected function _initialisation(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('initialisation action', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4b67de69');
        $this->_unlocked = $this->_entitiesInstance->getCurrentEntityIsUnlocked();
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

        $this->modulesActions(); // try catch inside
    }

    public function genericActions(): void {
        $this->_metrologyInstance->addLog('generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '84b627f1');

        if (!$this->_ticketingInstance->checkActionTicket() || !$this->_unlocked)
            return;

        $this->_extractActionObfuscateLink();
        $this->_extractActionDeleteObject();
        $this->_extractActionProtectObject();
        $this->_extractActionUnprotectObject();
        $this->_extractActionShareProtectObjectToEntity();
        $this->_extractActionShareProtectObjectToGroupOpened();
        $this->_extractActionShareProtectObjectToGroupClosed();
        $this->_extractActionCancelShareProtectObjectToEntity();
        $this->_extractActionSynchronizeObject();
        $this->_extractActionSynchronizeEntity();
        $this->_extractActionSynchronizeObjectLinks();
        $this->_extractActionSynchronizeApplication();
        $this->_extractActionSynchronizeNewEntity();
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

        if ($this->_actionObfuscateLinkInstance !== null
            && $this->_actionObfuscateLinkInstance != ''
            && is_a($this->_actionObfuscateLinkInstance, 'Nebule\Library\LinkRegister')
            && $this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')
        )
            $this->_actionObfuscateLink();

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
        if ($this->_actionSynchronizeEntityInstance != '')
            $this->_actionSynchronizeEntity();
        if ($this->_actionSynchronizeObjectLinksInstance != '')
            $this->_actionSynchronizeObjectLinks();
        if ($this->_actionSynchronizeApplicationInstance != '')
            $this->_actionSynchronizeApplication();
        if ($this->_actionSynchronizeNewEntityID != '')
            $this->_actionSynchronizeNewEntity();

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
        if (!$this->_ticketingInstance->checkActionTicket() || !$this->_unlocked)
            return;
        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module->getCommandName() == $this->_displayInstance->getCurrentDisplayMode()) {
                try {
                    $this->_metrologyInstance->addLog('actions for module ' . $module->getCommandName(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '55fba077');
                    $module->actions();
                } catch (\Exception $e) {
                    $this->_metrologyInstance->addLog('error actions for module ' . $module->getCommandName()
                        . ' ('  . $e->getCode() . ') : ' . $e->getFile()
                        . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                        . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c48b1d8c');
                }
            }
        }
    }

    protected function _checkPermitGroupAction($name): bool
    {
        if (!isset(self::GROUP_ACTIONS_PERMIT[$name]))
        {
            $this->_metrologyInstance->addLog('unknown group action ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__,'5edb0ddf');
            return false;
        }
        return $this->_configurationInstance->checkBooleanOptions(self::GROUP_ACTIONS_PERMIT[$name]);
    }

    const GROUP_ACTIONS_PERMIT = array(
        'GroupCreateObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupCreateLink' => ['unlocked','permitWrite','permitCreateLink'],
        'GroupDeleteObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupProtectObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupUnprotectObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupShareProtectObjectToEntity' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupShareProtectObjectToGroupOpened' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupShareProtectObjectToGroupClosed' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupCancelShareProtectObjectToEntity' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupSynchronizeObject' => ['permitWrite','permitWriteObject'],
        'GroupSynchronizeEntity' => ['permitWrite','permitWriteObject'],
        'GroupSynchronizeObjectLinks' => ['permitWrite','permitWriteLink'],
        'GroupSynchronizeApplication' => ['permitWrite','permitWriteLink','permitWriteObject','permitSynchronizeObject','permitSynchronizeLink','permitSynchronizeApplication'],
        'GroupSynchronizeNewEntity' => ['permitWrite','permitWriteObject','permitSynchronizeObject','permitSynchronizeLink'],
        'GroupUploadFileLinks' => ['permitWrite','permitWriteLink','permitUploadLink'],
        'GroupUploadFile' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupUploadText' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupCreateEntity' => ['permitWrite','permitWriteLink','permitWriteObject','permitWriteEntity'],
        'GroupCreateGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteGroup'],
        'GroupSignLink' => ['unlocked','permitWrite','permitWriteLink','permitCreateLink'],
        'GroupUploadLink' => ['permitWrite','permitWriteLink','permitUploadLink'],
        'GroupObfuscateLink' => ['unlocked','permitWrite','permitWriteLink','permitObfuscatedLink'],
        'GroupDeleteGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupAddToGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupRemoveFromGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupAddItemToGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupRemoveItemFromGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupCreateConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteConversation'],
        'GroupDeleteConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupAddMessageOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupRemoveMessageOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupAddMemberOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupRemoveMemberOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupCreateMessage' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteConversation'],
        'GroupAddProperty' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupCreateCurrency' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitCurrency','permitWriteCurrency','permitCreateCurrency'],
        'GroupCreateTokenPool' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitCurrency','permitWriteCurrency','permitCreateCurrency'],
        'GroupCreateTokens' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitCurrency','permitWriteCurrency','permitCreateCurrency'],
    );



    /**
     * Traitement des actions spéciales.
     *
     * Les actions peuvent être réalisées sans entité déverrouillée, mais pas nécessairement.
     * Certaines actions ne nécessitent pas la création de nouveaux liens. C'est par exemple
     *   le cas de la génération d'une nouvelle entité qui va générer ses propres liens.
     * Certaines actions peuvent avoir un comportement restreint si elles ne peuvent générer
     *   de nouveaux liens mais permettre de prendre en compte des liens déjà signés.
     *
     * Les actions spéciales doivent avoir des pré-requis bien ciblés pour éviter tout contournement.
     *
     * @return void
     */
    public function specialActions(): void
    {
        $this->_metrologyInstance->addLog('extract special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6f9dfb64');

        // Vérifie que l'action de création d'entité soit permise entité verrouillée.
        if ($this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity')
            || $this->_unlocked
        )
            $this->_extractActionCreateEntity();

        // Vérifie que l'action de chargement de lien soit permise.
        if ($this->_configurationInstance->checkBooleanOptions(self::GROUP_ACTIONS_PERMIT['GroupUploadLink'])
            || $this->_unlocked
        ) {
            // Extrait les actions.
            $this->_extractActionSignLink1();
            $this->_extractActionSignLink2();
            $this->_extractActionSignLink3();
            $this->_extractActionUploadLink();
            $this->_extractActionUploadFileLinks();
        }

        $this->_metrologyInstance->addLog('router special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0b4f22ef');

        // Si l'action de création d'entité est validée.
        if ($this->_actionCreateEntity)
            $this->_actionCreateEntity();

        // Si l'action de chargement de lien est permise y compris entité verrouillée.
        if ($this->_configurationInstance->checkBooleanOptions(self::GROUP_ACTIONS_PERMIT['GroupUploadLink'])
            && ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_unlocked
            )
        ) {
            // Lien à signer 1.
            if ($this->_checkPermitGroupAction('GroupCreateLink')
                && is_a($this->_actionSignLinkInstance1, 'Nebule\Library\LinkRegister')
            )
                $this->_actionSignLink($this->_actionSignLinkInstance1, $this->_actionSignLinkInstance1Obfuscate);

            // Lien à signer 2.
            if ($this->_checkPermitGroupAction('GroupCreateLink')
                && is_a($this->_actionSignLinkInstance2, 'Nebule\Library\LinkRegister')
            )
                $this->_actionSignLink($this->_actionSignLinkInstance2, $this->_actionSignLinkInstance2Obfuscate);

            // Lien à signer 3.
            if ($this->_checkPermitGroupAction('GroupCreateLink')
                && is_a($this->_actionSignLinkInstance3, 'Nebule\Library\LinkRegister')
            )
                $this->_actionSignLink($this->_actionSignLinkInstance3, $this->_actionSignLinkInstance3Obfuscate);

            // Liens pré-signés.
            if ($this->_actionUploadLinkInstance !== null
                && is_a($this->_actionUploadLinkInstance, 'Nebule\Library\LinkRegister')
            )
                $this->_actionUploadLink_DISABLED($this->_actionUploadLinkInstance);

            // Fichier de liens pré-signés.
            if ($this->_actionUploadFileLinks)
                $this->_actionUploadFileLinks();
        }

        $this->_metrologyInstance->addLog('special actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '88ff0291');
    }

    public function getDisplayActions(): void
    {
        $this->_displayInstance->displayInlineLastAction(); // FIXME
    }

    protected string $_actionSignLinkInstance1 = '';
    protected bool $_actionSignLinkInstance1Obfuscate = false;
    protected function _extractActionSignLink1(): void
    {
        if (!$this->_checkPermitGroupAction('GroupSignLink'))
            return;

        $this->_metrologyInstance->addLog('extract action sign link 1', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c5415d94');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SIGN_LINK1);
        $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK1_OBFUSCATE);

        if ($arg == '')
            return ;
        $this->_actionSignLinkInstance1 = $arg;
        $this->_actionSignLinkInstance1Obfuscate = $argObfuscate;

    }
    protected function _actionSignLink(string $link, bool $obfuscate = false): void
    {
        if ($this->_unlocked) {
            $this->_metrologyInstance->addLog('action sign link', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '8baa9fae');

            $blockLinkInstance = new BlocLink($this->_nebuleInstance, 'new');
            $blockLinkInstance->addLink($link);


            // On cache le lien ? // FIXME
            if ($obfuscate !== false
                && $obfuscate !== true
            )
                $obfuscate = $this->_configurationInstance->getOptionUntyped('defaultObfuscateLinks');
            //...

            $link->signWrite();
        } elseif ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
            || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
        ) {
            $this->_metrologyInstance->addLog('action sign link', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'be97740a');

            if ($link->getSigned())
                $link->write();
        }
    }

    protected string $_actionSignLinkInstance2 = '';
    protected bool $_actionSignLinkInstance2Obfuscate = false;
    protected function _extractActionSignLink2(): void
    {
        if (!$this->_checkPermitGroupAction('GroupSignLink'))
            return;

        $this->_metrologyInstance->addLog('extract action sign link 2', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e1059b93');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SIGN_LINK2);
        $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK2_OBFUSCATE);

        if ($arg == '')
            return ;
        $this->_actionSignLinkInstance2 = $arg;
        $this->_actionSignLinkInstance2Obfuscate = $argObfuscate;
    }

    protected string $_actionSignLinkInstance3 = '';
    protected bool $_actionSignLinkInstance3Obfuscate = false;
    protected function _extractActionSignLink3(): void
    {
        if (!$this->_checkPermitGroupAction('GroupSignLink'))
            return;

        $this->_metrologyInstance->addLog('extract action sign link 3', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cc145716');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SIGN_LINK3);
        $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK3_OBFUSCATE);

        if ($arg == '')
            return ;
            $this->_actionSignLinkInstance3 = $arg;
            $this->_actionSignLinkInstance3Obfuscate = $argObfuscate;
    }

    protected ?LinkRegister $_actionUploadLinkInstance = null;
    protected function _extractActionUploadLink(): void
    {
        if ($this->_checkPermitGroupAction('GroupUploadLink')
            && ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_unlocked
            )
        ) {
            $this->_metrologyInstance->addLog('extract action upload signed link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0e682f22');

            $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_UPLOAD_SIGNED_LINK);

            // Vérifie si restriction des liens au maître du code. Non par défaut.
            $permitNotCodeMaster = false;
            if ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_unlocked
            )
                $permitNotCodeMaster = true;

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) != 0
            ) {
                $instance = $this->flatLinkExtractAsInstance_DISABLED($arg);
                if ($instance->getValid()
                    && $instance->getSigned()
                    && ($instance->getSigners() == $this->_authoritiesInstance->getCodeAuthoritiesEID()
                        || $permitNotCodeMaster
                    )
                )
                    $this->_actionUploadLinkInstance = $instance;
                unset($instance);
            }
        }
    }

    protected ?LinkRegister $_actionObfuscateLinkInstance = null;
    protected function _extractActionObfuscateLink(): void
    {
        if (!$this->_checkPermitGroupAction('GroupObfuscateLink'))
            return;

        $this->_metrologyInstance->addLog('extract action obfuscate link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c22677ad');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_OBFUSCATE_LINK);

        if (strlen($arg) != 0)
            $this->_actionObfuscateLinkInstance = $this->flatLinkExtractAsInstance_DISABLED($arg);
    }
    protected function _actionObfuscateLink(): void
    {
        if ($this->_actionObfuscateLinkInstance === null
            || !$this->_actionObfuscateLinkInstance->getValid()
            || !$this->_actionObfuscateLinkInstance->getSigned() // FIXME
        )
            return;

        $this->_metrologyInstance->addLog('action obfuscate link', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'b3bf62a9');

        // On dissimule le lien.
        $this->_actionObfuscateLinkInstance->obfuscateWrite();
    }

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
        if (!$this->_checkPermitGroupAction('GroupDeleteObject'))
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
        if (!$this->_checkPermitGroupAction('GroupProtectObject'))
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
        if (!$this->_checkPermitGroupAction('GroupUnprotectObject'))
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
        if (!$this->_checkPermitGroupAction('GroupShareProtectObjectToEntity'))
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
        if (!$this->_checkPermitGroupAction('GroupShareProtectObjectToGroupOpened'))
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
        $group = $this->_cacheInstance->newGroup($this->_actionShareProtectObjectToGroupOpened);
        foreach ($group->getListMembersID('myself', null) as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);
    }

    protected string $_actionShareProtectObjectToGroupClosed = '';
    protected function _extractActionShareProtectObjectToGroupClosed(): void
    {
        if (!$this->_checkPermitGroupAction('GroupShareProtectObjectToGroupClosed'))
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
        $group = $this->_cacheInstance->newGroup($this->_actionShareProtectObjectToGroupClosed);
        foreach ($group->getListMembersID('myself', null) as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);
    }

    protected string $_actionCancelShareProtectObjectToEntity = '';
    protected function _extractActionCancelShareProtectObjectToEntity(): void
    {
        if (!$this->_checkPermitGroupAction('GroupCancelShareProtectObjectToEntity'))
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
        if (!$this->_checkPermitGroupAction('GroupSynchronizeObject'))
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

    protected ?Entity $_actionSynchronizeEntityInstance = null;
    public function getSynchronizeEntityInstance(): ?Entity
    {
        return $this->_actionSynchronizeEntityInstance;
    }
    protected function _extractActionSynchronizeEntity(): void
    {
        if (!$this->_checkPermitGroupAction('GroupSynchronizeEntity'))
            return;

        $this->_metrologyInstance->addLog('extract action synchronize entity', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '2ec437bf');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionSynchronizeEntityInstance = $this->_cacheInstance->newEntity($arg);
    }
    protected function _actionSynchronizeEntity(): void
    {
        $this->_metrologyInstance->addLog('action synchronize entity', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'f41d4b64');

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNENT') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeEntityInstance);

        // Synchronisation des liens (l'objet est forcément présent).
        $this->_actionSynchronizeEntityInstance->syncLinks();

        // Liste des liens l pour l'entité en source.
        $links = $this->_actionSynchronizeEntityInstance->readLinksFilterFull('', '', 'l', $this->_actionSynchronizeEntityInstance->getID(), '', '');
        // Synchronise l'objet cible.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        // Liste des liens l pour l'entité en cible.
        $links = $this->_actionSynchronizeEntityInstance->readLinksFilterFull('', '', 'l', '', $this->_actionSynchronizeEntityInstance->getID(), '');
        // Synchronise l'objet source.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        unset($links, $link, $object);
    }

    protected ?Node $_actionSynchronizeObjectLinksInstance = null;
    public function getSynchronizeObjectLinksInstance(): ?Node
    {
        return $this->_actionSynchronizeObjectLinksInstance;
    }
    protected function _extractActionSynchronizeObjectLinks(): void
    {
        if (!$this->_checkPermitGroupAction('GroupSynchronizeObjectLinks'))
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

    protected ?Node $_actionSynchronizeApplicationInstance = null;
    public function getSynchronizeApplicationInstance(): ?Node
    {
        return $this->_actionSynchronizeApplicationInstance;
    }
    protected function _extractActionSynchronizeApplication(): void
    {
        if ($this->_checkPermitGroupAction('GroupSynchronizeApplication')
            && ($this->_configurationInstance->getOptionAsBoolean('permitPublicSynchronizeApplication')
                || $this->_unlocked
            )
        ) {
            $this->_metrologyInstance->addLog('extract action synchronize entity', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '2692acb5');

            $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION, FILTER_FLAG_ENCODE_LOW);

            if (Node::checkNID($arg))
                $this->_actionSynchronizeApplicationInstance = $this->_cacheInstance->newNode($arg);
        }
    }
    protected function _actionSynchronizeApplication(): void
    {
        $this->_metrologyInstance->addLog('action synchronize application', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'c5d52f3e');

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNOBJ') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeApplicationInstance);

        // Synchronisation des liens (l'objet est forcément présent).
        $this->_actionSynchronizeApplicationInstance->syncLinks();

        // Liste des liens l pour l'entité en source.
        $links = $this->_actionSynchronizeApplicationInstance->readLinksFilterFull('', '', 'l', $this->_actionSynchronizeApplicationInstance->getID(), '', '');
        // Synchronise l'objet cible.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        // Liste des liens l pour l'entité en cible.
        $links = $this->_actionSynchronizeApplicationInstance->readLinksFilterFull('', '', 'l', '', $this->_actionSynchronizeApplicationInstance->getID(), '');
        // Synchronise l'objet source.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        unset($links, $link, $object);
    }

    protected string $_actionSynchronizeNewEntityID = '';
    protected string $_actionSynchronizeNewEntityURL = '';
    protected ?Entity $_actionSynchronizeNewEntityInstance = null;
    public function getSynchronizeNewEntityInstance(): ?Entity
    {
        return $this->_actionSynchronizeNewEntityInstance;
    }
    protected function _extractActionSynchronizeNewEntity(): void
    {
        if (!$this->_checkPermitGroupAction('GroupSynchronizeNewEntity'))
            return;

        $this->_metrologyInstance->addLog('extract action synchronize new entity', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_NEW_ENTITY, FILTER_FLAG_ENCODE_LOW);

        // Extraction de l'URL et stockage pour traitement.
        if ($arg != ''
            && strlen($arg) >= 9
            && ctype_print($arg)
        ) {
            // Décompose l'URL.
            $parseURL = parse_url($arg);
            // Extrait le protocol.
            if (isset($parseURL['scheme'])
                && ($parseURL['scheme'] == 'http'
                    || $parseURL['scheme'] == 'https'
                )
            )
                $scheme = $parseURL['scheme'];
            else {
                // Il manque le protocol, suppose que c'est http.
                $scheme = 'http';
                $parseURL = parse_url($scheme . '://' . $arg);
            }
            // Vérifie les champs de l'URL.
            if ($parseURL['host'] != ''
                && $parseURL['path'] != ''
                && substr($parseURL['path'], 0, 3) == '/o/'
            ) {
                // Extrait l'ID de l'objet de l'entité à synchroniser.
                $id = substr($parseURL['path'], 3);
                $this->_metrologyInstance->addLog('extract action synchronize new entity - ID=' . $id, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
                // Vérifie l'ID.
                if (!$this->_ioInstance->checkObjectPresent($id)
                    && Node::checkNID($id)
                ) {
                    // Si c'est bon on prépare pour la synchronisation.
                    $this->_actionSynchronizeNewEntityID = $id;
                    $this->_actionSynchronizeNewEntityURL = $scheme . '://' . $parseURL['host'];
                    if (isset($parseURL['port'])) {
                        $port = $parseURL['port'];
                        $this->_actionSynchronizeNewEntityURL .= ':' . $port;
                    }
                    $this->_metrologyInstance->addLog('extract action synchronize new entity - URL=' . $this->_actionSynchronizeNewEntityURL, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
                }
            }
        }
    }
    protected function _actionSynchronizeNewEntity(): void
    {
        $this->_metrologyInstance->addLog('action synchronize new entity', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        // Vérifie si l'objet est déjà présent.
        $present = $this->_ioInstance->checkObjectPresent($this->_actionSynchronizeNewEntityID);
        // Lecture de l'objet.
        $data = $this->_ioInstance->getObject($this->_actionSynchronizeNewEntityID, Entity::ENTITY_MAX_SIZE, $this->_actionSynchronizeNewEntityURL);
        // Calcul de l'empreinte.
        $hash = $this->_nebuleInstance->getCryptoInstance()->hash($data);
        if ($hash != $this->_actionSynchronizeNewEntityID) {
            $this->_metrologyInstance->addLog('action synchronize new entity - Hash error', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            unset($data);
            echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNENT') .
                $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeNewEntityID) .
                $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_IERR');
            $this->_actionSynchronizeNewEntityID = '';
            $this->_actionSynchronizeNewEntityURL = '';
            return;
        }
        // Ecriture de l'objet.
        $this->_ioInstance->setObject($this->_actionSynchronizeNewEntityID, $data);

        $this->_actionSynchronizeNewEntityInstance = $this->_cacheInstance->newEntity($this->_actionSynchronizeNewEntityID);

        if (!$this->_actionSynchronizeNewEntityInstance->getTypeVerify()) {
            $this->_metrologyInstance->addLog('action synchronize new entity - Not entity', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            if (!$present)
                $this->_actionSynchronizeNewEntityInstance->deleteObject();
        }

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNENT') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeNewEntityInstance);

        // Synchronisation des liens.
        $this->_actionSynchronizeNewEntityInstance->syncLinks();

        // Liste des liens l pour l'entité en source.
        $links = $this->_actionSynchronizeNewEntityInstance->getLinksOnFields('', '', 'l', $hash, '', '');
        // Synchronise l'objet cible.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        // Liste des liens l pour l'entité en cible.
        $links = $this->_actionSynchronizeNewEntityInstance->getLinksOnFields('', '', 'l', '', $hash, '');
        // Synchronise l'objet source.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        unset($links, $link, $object);
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

    protected bool $_actionUploadFileLinks = false;
    protected string $_actionUploadFileLinksName = '';
    protected string $_actionUploadFileLinksSize = '';
    protected string $_actionUploadFileLinksPath = '';
    protected bool $_actionUploadFileLinksError = false;
    protected string $_actionUploadFileLinksErrorMessage = 'Initialisation du transfert.';
    public function getUploadFileSignedLinks(): bool
    {
        return $this->_actionUploadFileLinks;
    }
    protected function _extractActionUploadFileLinks(): void
    {
        if ($this->_checkPermitGroupAction('GroupUploadFileLinks')
            && ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_unlocked
            )
        ) {
            $this->_metrologyInstance->addLog('extract action upload file of signed links', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

            // Lit le contenu de la variable _FILE si un fichier est téléchargé.
            if (isset($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['error'])
                && $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['error'] == UPLOAD_ERR_OK
                && trim($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['name']) != ''
            ) {
                // Extraction des méta données du fichier.
                $upname = mb_convert_encoding(strtok(trim(filter_var($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['name'], FILTER_SANITIZE_STRING)), "\n"), 'UTF-8');
                $upsize = $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['size'];
                $uppath = $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['tmp_name'];

                // Si le fichier est bien téléchargé.
                if (file_exists($uppath)) {
                    // Si le fichier n'est pas trop gros.
                    if ($upsize <= $this->_configurationInstance->getOptionUntyped('ioReadMaxData')) {
                        // Ecriture des variables.
                        $this->_actionUploadFileLinks = true;
                        $this->_actionUploadFileLinksName = $upname;
                        $this->_actionUploadFileLinksSize = $upsize;
                        $this->_actionUploadFileLinksPath = $uppath;
                    } else {
                        $this->_metrologyInstance->addLog('action _extractActionUploadFileLinks File size too big', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                        $this->_actionUploadFileLinksError = true;
                        $this->_actionUploadFileLinksErrorMessage = 'File size too big';
                    }
                } else {
                    $this->_metrologyInstance->addLog('action _extractActionUploadFileLinks File upload error', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                    $this->_actionUploadFileLinksError = true;
                    $this->_actionUploadFileLinksErrorMessage = 'File upload error';
                }
            }
        }
    }
    /**
     * Transfert un fichier de liens pré-signés et les ajoute.
     *
     * Cette fonction est appelée par la fonction specialActions().
     * Elle est utilisée par l'application upload et le module module_upload de l'application sylabe.
     * Le fonctioneement est identique dans ces deux usages même si l'affichage ne le montre pas.
     *
     * La fonction nécessite au minimum les droits :
     *   - permitWrite
     *   - permitWriteLink
     *   - permitUploadLink
     * L'activation de la fonction est ensuite conditionnée par une conbinaison d'autres droits ou facteurs.
     *
     * Si le droit permitPublicUploadCodeAuthoritiesLink est activé :
     *   les liens signés du maître du code sont acceptés ;
     *   les liens des autres entités sont ignorés avec seulement ce droit.
     *
     * Si le droit permitPublicUploadLink est activé :
     *   tous les liens signés sont acceptés ;
     *   les entités signataires doivent exister localement pour la vérification les signatures.
     *
     * Si l'entité en cours est déverrouillée, this->_unlocked :
     *   la réception de liens est prise comme une action légitime ;
     *   les liens signés de toutes les entités sont acceptés ;
     *   les liens non signés sont signés par l'entité en cours.
     * Si un lien est structurellement valide mais non signé, il est régénéré et signé par l'entité en cours.
     *
     * Les liens ne sont écris que si leurs signatures sont valides.
     *
     * @return void
     */
    protected function _actionUploadFileLinks(): void
    {
        $this->_metrologyInstance->addLog('action upload file signed links', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        // Ecrit les liens correctement signés.
        $updata = file($this->_actionUploadFileLinksPath);
        $nbLinks = 0; // FIXME unused
        $nbLines = 0; // FIXME unused
        foreach ($updata as $line) {
            if (substr($line, 0, 21) != 'nebule/liens/version/') {
                $nbLines++;
                $instance = $this->_cacheInstance->newBlockLink($line);
                if ($instance->getValid()) {
                    if ($instance->getSigned()
                        && (($instance->getSigners() == $this->_authoritiesInstance->getCodeAuthoritiesEID()
                                && $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                            )
                            || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                            || $this->_unlocked
                        )
                    ) {
                        $instance->write();
                        $nbLinks++;
                        $this->_metrologyInstance->addLog('action upload file links - signed link ' . $instance->getRaw(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
                    } elseif ($this->_unlocked) {
                        /*$instance = $this->_cacheInstance->newBlockLink( FIXME
                            '0_'
                            . $this->_entitiesInstance->getCurrentEntityID() . '_'
                            . $instance->getDate() . '_'
                            . $instance->getParsed()['bl/rl/req'] . '_'
                            . $instance->getParsed()['bl/rl/nid1'] . '_'
                            . $instance->getParsed()['bl/rl/nid2'] . '_'
                            . $instance->getParsed()['bl/rl/nid3']
                        );
                        $instance->signWrite();*/
                        $nbLinks++;
                        $this->_metrologyInstance->addLog('action upload file links - unsigned link ' . $instance->getRaw(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
                    }
                }
            }
        }
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
        if (!$this->_checkPermitGroupAction('GroupUploadFile'))
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
                $upfname = mb_convert_encoding(strtok(trim(filter_var($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['name'], FILTER_SANITIZE_STRING)), "\n"), 'UTF-8');
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
        $signer = $this->_entitiesInstance->getCurrentEntityID();

        // Création du type mime.
        $instance->setType($this->_actionUploadFileType);

        // Crée l'objet du nom.
        $instance->setName($this->_actionUploadFileName);

        // Crée l'objet de l'extension.
        $instance->setSuffix($this->_actionUploadFileExtension);

        // Si mise à jour de l'objet en cours.
        if ($this->_actionUploadFileUpdate) {
            // Crée le lien.
            $action = 'u';
            $source = $this->_applicationInstance->getCurrentObjectID();
            $target = $id;
            $meta = '0';
            $this->_createLink_DISABLED($signer, $date, $action, $source, $target, $meta, $this->_actionUploadFileObfuscateLinks);
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
    public function getUploadText(): bool
    {
        return $this->_actionUploadText;
    }
    public function getUploadTextName(): string
    {
        return $this->_actionUploadTextName;
    }
    public function getUploadTextID(): string
    {
        return $this->_actionUploadTextID;
    }
    public function getUploadTextError(): bool
    {
        return $this->_actionUploadTextError;
    }
    public function getUploadTextErrorMessage(): string
    {
        return $this->_actionUploadTextErrorMessage;
    }
    protected function _extractActionUploadText(): void
    {
        if (!$this->_checkPermitGroupAction('GroupUploadText'))
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
                    $this->_actionUploadTextType = nebule::REFERENCE_OBJECT_TEXT;

                if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
                    $this->_actionUploadTextProtect = $argPrt;
                if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                    $this->_actionUploadTextObfuscateLinks = $argObf;
            }
        }
    }
    protected function _actionUploadText(): void
    {
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

    protected bool $_actionCreateEntity = false;
    protected string $_actionCreateEntityPrefix = '';
    protected string $_actionCreateEntitySuffix = '';
    protected string $_actionCreateEntityFirstname = '';
    protected string $_actionCreateEntityNikename = '';
    protected string $_actionCreateEntityName = '';
    protected string $_actionCreateEntityPassword = '';
    protected string $_actionCreateEntityType = '';
    protected string $_actionCreateEntityID = '0';
    protected bool $_actionCreateEntityObfuscateLinks = false;
    protected ?Entity $_actionCreateEntityInstance = null;
    protected bool $_actionCreateEntityError = false;
    protected string $_actionCreateEntityErrorMessage = 'Initialisation de la création.';
    public function getCreateEntity(): bool
    {
        return $this->_actionCreateEntity;
    }
    public function getCreateEntityID(): string
    {
        return $this->_actionCreateEntityID;
    }
    public function getCreateEntityInstance(): ?Entity
    {
        return $this->_actionCreateEntityInstance;
    }
    public function getCreateEntityError(): bool
    {
        return $this->_actionCreateEntityError;
    }
    public function getCreateEntityErrorMessage(): string
    {
        return $this->_actionCreateEntityErrorMessage;
    }
    protected function _extractActionCreateEntity(): void
    {
        if ((!$this->_unlocked && !$this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity'))
            || !$this->_checkPermitGroupAction('GroupCreateEntity')
        )
            return;

        $this->_metrologyInstance->addLog('extract action create entity', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY);

        if ($argCreate !== false)
            $this->_actionCreateEntity = true;

        // Si on crée une nouvelle entité.
        if ($this->_actionCreateEntity) {
            $argPrefix = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PREFIX, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argSuffix = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_SUFFIX, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argFstnam = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_FIRSTNAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argNiknam = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NIKENAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argName = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argPasswd1 = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD1, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argPasswd2 = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD2, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argType = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_TYPE, FILTER_FLAG_NO_ENCODE_QUOTES);

            // Extrait les options de téléchargement.
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            $this->_actionCreateEntityPrefix = $argPrefix;
            $this->_actionCreateEntitySuffix = $argSuffix;
            $this->_actionCreateEntityFirstname = $argFstnam;
            $this->_actionCreateEntityNikename = $argNiknam;
            $this->_actionCreateEntityName = $argName;
            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateEntityObfuscateLinks = $argObf;

            if ($argPasswd1 == $argPasswd2)
                $this->_actionCreateEntityPassword = $argPasswd1;
            else {
                $this->_metrologyInstance->addLog('action _extractActionCreateEntity passwords not match', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionCreateEntityError = true;
                $this->_actionCreateEntityErrorMessage = 'Les mots de passes ne sont pas identiques.';
            }

            if ($argType == 'human' || $argType == 'robot')
                $this->_actionCreateEntityType = $argType;
            else
                $this->_actionCreateEntityType = '';

            unset($argPrefix, $argSuffix, $argFstnam, $argNiknam, $argName, $argPasswd1, $argPasswd2, $argType);
        }
    }
    protected function _actionCreateEntity(): void
    {
        $this->_metrologyInstance->addLog('action create entity', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instance = new Entity($this->_nebuleInstance, 'new');

        if (is_a($instance, 'Nebule\Library\Entity') && $instance->getID() != '0') {
            $this->_actionCreateEntityError = false;

            // Enregistre l'instance créée.
            $this->_actionCreateEntityInstance = $instance;
            $this->_actionCreateEntityID = $instance->getID();
            unset($instance);

            // Modifie le mot de passe de clé privée.
            $this->_actionCreateEntityInstance->changePrivateKeyPassword($this->_actionCreateEntityPassword);

            // Bascule temporairement sur la nouvelle entité.
            $this->_entitiesInstance->setTempCurrentEntity($this->_actionCreateEntityInstance);
            $this->_entitiesInstance->getCurrentEntityInstance()->setPrivateKeyPassword($this->_actionCreateEntityPassword);

            // Définition de la date et du signataire.
            $date = date(DATE_ATOM);
            $signer = $this->_actionCreateEntityID;

            // Si prénom et pas de nom, le prénom devient le nom.
            if ($this->_actionCreateEntityName == '' && $this->_actionCreateEntityFirstname != '') {
                $this->_actionCreateEntityName = $this->_actionCreateEntityFirstname;
                $this->_actionCreateEntityFirstname = '';
            }

            // Nomme l'entité.
            if ($this->_actionCreateEntityName != '') {
                // Crée l'objet avec le texte.
                $textID = $this->createTextAsObject($this->_actionCreateEntityName); // Est fait avec l'entité courante, pas la nouvelle !!!
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $this->_actionCreateEntityID;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/nom');
                    //$this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks); FIXME
                }
            }
            if ($this->_actionCreateEntityFirstname != '') {
                // Crée l'objet avec le texte.
                $textID = $this->createTextAsObject($this->_actionCreateEntityFirstname);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $this->_actionCreateEntityID;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/prenom');
                    //$this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks); FIXME
                }
            }
            if ($this->_actionCreateEntityNikename != '') {
                // Crée l'objet avec le texte.
                $textID = $this->createTextAsObject($this->_actionCreateEntityNikename);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $this->_actionCreateEntityID;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/surnom');
                    $this->_createLink_DISABLED($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                }
            }

            if ($this->_actionCreateEntityPrefix != '') {
                // Crée l'objet avec le texte.
                $textID = $this->createTextAsObject($this->_actionCreateEntityPrefix);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $this->_actionCreateEntityID;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/prefix');
                    $this->_createLink_DISABLED($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                }
            }
            if ($this->_actionCreateEntitySuffix != '') {
                // Crée l'objet avec le texte.
                $textID = $this->createTextAsObject($this->_actionCreateEntitySuffix);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $this->_actionCreateEntityID;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/suffix');
                    $this->_createLink_DISABLED($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                }
            }
            if ($this->_actionCreateEntityType != '') {
                // Crée l'objet avec le texte.
                $textID = $this->createTextAsObject($this->_actionCreateEntityType);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $this->_actionCreateEntityID;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/entite/type');
                    $this->_createLink_DISABLED($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                }
            }

            unset($date, $source, $target, $meta, $link, $newLink, $textID);

            // Restaure l'entité d'origine.
            $this->_entitiesInstance->unsetTempCurrentEntity();

            // Efface le cache pour recharger l'entité.
            $this->_nebuleInstance->getCacheInstance()->unsetEntityOnCache($this->_actionCreateEntityID);

            // Recrée l'instance de l'objet.
            $this->_actionCreateEntityInstance = $this->_cacheInstance->newEntity($this->_actionCreateEntityID);
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('action _actionCreateEntity cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionCreateEntityError = true;
            $this->_actionCreateEntityErrorMessage = 'Echec de la génération.';
        }
    }

    protected bool $_actionCreateGroup = false;
    protected string $_actionCreateGroupName = '';
    protected string $_actionCreateGroupID = '0';
    protected bool $_actionCreateGroupClosed = false;
    protected bool $_actionCreateGroupObfuscateLinks = false;
    protected ?Group $_actionCreateGroupInstance = null;
    protected bool $_actionCreateGroupError = false;
    protected string $_actionCreateGroupErrorMessage = 'Initialisation de la création.';
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
        if (!$this->_checkPermitGroupAction('GroupCreateGroup'))
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
        if (!$this->_checkPermitGroupAction('GroupDeleteGroup'))
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

        $instance = $this->_cacheInstance->newGroup($this->_actionDeleteGroupID);

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
        if (!$this->_checkPermitGroupAction('GroupAddToGroup'))
            return;

        $this->_metrologyInstance->addLog('extract action add to group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_TO_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionAddToGroup = $arg;
    }
    protected function _actionAddToGroup(): void
    {
        $this->_metrologyInstance->addLog('action add to group ' . $this->_actionAddToGroup, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $instanceGroupe = $this->_cacheInstance->newGroup($this->_actionAddToGroup);
        $instanceGroupe->setMember($this->_nebuleInstance->getCurrentObjectInstance());
    }

    protected string $_actionRemoveFromGroup = '';
    public function getRemoveFromGroup(): string
    {
        return $this->_actionRemoveFromGroup;
    }
    protected function _extractActionRemoveFromGroup(): void
    {
        if (!$this->_checkPermitGroupAction('GroupRemoveFromGroup'))
            return;

        $this->_metrologyInstance->addLog('extract action remove from group', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_REMOVE_FROM_GROUP, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionRemoveFromGroup = $arg;
    }
    protected function _actionRemoveFromGroup(): void
    {
        $this->_metrologyInstance->addLog('action remove from group ' . $this->_actionRemoveFromGroup, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $instanceGroupe = $this->_cacheInstance->newGroup($this->_actionRemoveFromGroup);
        $instanceGroupe->unsetMember($this->_nebuleInstance->getCurrentObjectInstance());
    }

    protected string $_actionAddItemToGroup = '';
    public function getAddItemToGroup(): string
    {
        return $this->_actionAddItemToGroup;
    }
    protected function _extractActionAddItemToGroup(): void
    {
        if (!$this->_checkPermitGroupAction('GroupAddItemToGroup'))
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
        if (!$this->_checkPermitGroupAction('GroupRemoveItemFromGroup'))
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
        if (!$this->_checkPermitGroupAction('GroupCreateConversation'))
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
        if (!$this->_checkPermitGroupAction('GroupDeleteConversation'))
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
        $instance = $this->_cacheInstance->newConversation($this->_actionDeleteConversationID);
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
        if (!$this->_checkPermitGroupAction('GroupAddMessageOnConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action add to conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_TO_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionAddMessageOnConversation = $arg;
    }
    protected function _actionAddMessageOnConversation(): void
    {
        $this->_metrologyInstance->addLog('action add message to conversation ' . $this->_actionAddMessageOnConversation, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instanceConversation = $this->_cacheInstance->newConversation($this->_actionAddMessageOnConversation);
        $instanceConversation->setMember($this->_nebuleInstance->getCurrentObject(), false);
    }

    protected string $_actionRemoveMessageOnConversation = '';
    public function getRemoveMessageOnConversation(): string
    {
        return $this->_actionRemoveMessageOnConversation;
    }
    protected function _extractActionRemoveMessageOnConversation(): void
    {
        if (!$this->_checkPermitGroupAction('GroupRemoveMessageOnConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action remove from conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_REMOVE_FROM_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionRemoveMessageOnConversation = $arg;
    }
    protected function _actionRemoveMessageOnConversation(): void
    {
        $this->_metrologyInstance->addLog('action remove message to conversation ' . $this->_actionRemoveMessageOnConversation, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instanceConversation = $this->_cacheInstance->newConversation($this->_actionRemoveMessageOnConversation);
        $instanceConversation->unsetMember($this->_nebuleInstance->getCurrentObject());
    }

    protected string $_actionAddMemberOnConversation = '';
    public function getAddMemberOnConversation(): string
    {
        return $this->_actionAddMemberOnConversation;
    }
    protected function _extractActionAddMemberOnConversation(): void
    {
        if (!$this->_checkPermitGroupAction('GroupAddMemberOnConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action add item to conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_ADD_ITEM_TO_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionAddMemberOnConversation = $arg;
    }
    protected function _actionAddMemberOnConversation(): void
    {
        $this->_metrologyInstance->addLog('action add member to conversation ' . $this->_actionAddMemberOnConversation, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instanceConversation = $this->_cacheInstance->newConversation($this->_actionAddMemberOnConversation);
        $instanceConversation->setFollower($this->_nebuleInstance->getCurrentObject());
    }

    protected string $_actionRemoveMemberOnConversation = '';
    public function getRemoveMemberOnConversation(): string
    {
        return $this->_actionRemoveMemberOnConversation;
    }
    protected function _extractActionRemoveMemberOnConversation(): void
    {
        if (!$this->_checkPermitGroupAction('GroupRemoveMemberOnConversation'))
            return;

        $this->_metrologyInstance->addLog('extract action remove item from conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionRemoveMemberOnConversation = $arg;
    }
    protected function _actionRemoveMemberOnConversation(): void
    {
        $this->_metrologyInstance->addLog('action remove member to conversation ' . $this->_actionRemoveMemberOnConversation, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instanceConversation = $this->_cacheInstance->newConversation($this->_actionRemoveMemberOnConversation);
        $instanceConversation->unsetFollower($this->_nebuleInstance->getCurrentObject());
    }

    protected bool $_actionCreateMessage = false;
    protected string $_actionCreateMessageID = '0';
    protected bool $_actionCreateMessageError = false;
    protected bool $_actionCreateMessageProtected = false;
    protected bool $_actionCreateMessageObfuscateLinks = false;
    protected string $_actionCreateMessageErrorMessage = 'Initialisation de la suppression.';
    protected function _extractActionCreateMessage(): void
    {
        if (!$this->_checkPermitGroupAction('GroupCreateMessage'))
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
        if (!$this->_checkPermitGroupAction('GroupAddProperty'))
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
                    $this->_actionAddPropertyObject = $this->_nebuleInstance->getCurrentObject();
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
        $propID = $this->_nebuleInstance->getCryptoInstance()->hash($prop);
        $this->_metrologyInstance->addLog('action add property ' . $prop, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $objectID = $this->_actionAddPropertyObject;
        $this->_metrologyInstance->addLog('action add property for ' . $objectID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $value = $this->_actionAddPropertyValue;
        $valueID = $this->_nebuleInstance->getCryptoInstance()->hash($value);
        $this->_metrologyInstance->addLog('action add property value : ' . $value, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        $protected = $this->_actionAddPropertyProtected;
        if ($protected) {
            $this->_metrologyInstance->addLog('action add property protected', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        }
        if ($this->_actionAddPropertyObfuscateLinks) {
            $this->_metrologyInstance->addLog('action add property with obfuscated links', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        }

        // Création des objets si besoin.
        if (!$this->_nebuleInstance->getIoInstance()->checkObjectPresent($propID)) {
            $this->createTextAsObject($prop);
        }
        if (!$this->_nebuleInstance->getIoInstance()->checkObjectPresent($valueID)) {
            $this->createTextAsObject($value, $protected, $this->_actionAddPropertyObfuscateLinks);
        }

        // Création du lien.
        $date = date(DATE_ATOM);
        $signer = $this->_entitiesInstance->getCurrentEntityID();
        $action = 'l';
        $source = $objectID;
        $target = $valueID;
        $meta = $propID;
        $this->_createLink_DISABLED($signer, $date, $action, $source, $target, $meta, $this->_actionAddPropertyObfuscateLinks);
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
        if (!$this->_checkPermitGroupAction('GroupCreateCurrency'))
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
        if (!$this->_checkPermitGroupAction('GroupCreateTokenPool'))
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
        if (!$this->_checkPermitGroupAction('GroupCreateTokens'))
            return;

        $this->_metrologyInstance->addLog('extract action create tokens', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_TOKENS);

        if ($argCreate !== false)
            $this->_actionCreateTokens = true;

        if ($this->_actionCreateTokens) {
            // Récupère la liste des propriétés.
            $instance = $this->_tokenizingInstance->getCurrentTokenInstance();
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

        $instance = $this->_tokenizingInstance->getCurrentTokenInstance();
        for ($i = 0; $i < $this->_actionCreateTokensCount; $i++) {
            // Si pas le premier jeton, supprime le SID demandé de façon à ce qu'il soit généré aléatoirement.
            if ($i > 0) {
                $this->_actionCreateTokensParam['TokenSerialID'] = '';
            }

            // Création du nouveau sac de jetons.
            $instance = new Token($this->_nebuleInstance, 'new', $this->_actionCreateTokensParam, false, false);

            if (is_a($instance, 'Nebule\Library\Token')
                && $instance->getID() != '0'
            ) {
                $this->_actionCreateTokensError = false;

                $this->_actionCreateTokensInstance[$i] = $instance;
                $this->_actionCreateTokensID[$i] = $instance->getID();

                $this->_metrologyInstance->addLog('action _actionCreateTokens generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            } else {
                // Si ce n'est pas bon.
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrologyInstance->addLog('action _actionCreateTokens cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionCreateTokensError = true;
                $this->_actionCreateTokensErrorMessage = 'Echec de la génération.';

                // Quitte le processus de génération.
                break;
            }
        }
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
            && (($link->getSigners() == $this->_authoritiesInstance->getCodeAuthoritiesEID()
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
     * Crée un lien.
     *
     * @param      $signer
     * @param      $date
     * @param      $action
     * @param      $source
     * @param      $target
     * @param      $meta
     * @param bool $obfuscate
     * @return boolean
     */
    protected function _createLink_DISABLED($signer, $date, $action, $source, $target, $meta, bool $obfuscate = false): bool
    {
        return false; // FIXME

        /*$link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new LinkRegister($this->_nebuleInstance, $link);

        // Signe le lien.
        $newLink->sign($signer);

        // Si besoin, obfuscation du lien.
        if ($obfuscate
            && $this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')
        ) {
            $newLink->setObfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();*/
    }




    /**
     * Extrait et analyse un lien.
     * Accepte une chaine de caractère représentant un lien.
     * En fonction du nombre de champs, c'est interprété :
     * 2 champs : 0_0_0_action_source_0_0
     * 3 champs : 0_0_0_action_source_target_0
     * 4 champs : 0_0_0_action_source_target_meta
     * 5 champs : 0_0_date_action_source_target_meta
     * 6 champs : 0_signer_date_action_source_target_meta
     * 7 champs : signe_signer_date_action_source_target_meta
     * Sinon    : 0_0_0_0_0_0_0
     * Retourne un tableau des constituants du lien :
     * [signature, signataire, date, action, source, destination, méta]
     * Les champs non renseignés sont à '0'.
     *
     * @param string $link : lien à extraire.
     * @return array:string : un tableau des champs (signature, signataire, date, action, source, destination, méta).
     */
    public function flatLinkExtractAsArray_DISABLED(string $link): array
    {
        return array('0', '0', '0', '0', '0', '0', '0');
        /*    // Variables.
            $list = array();
            $date = date(DATE_ATOM);
            $ent = $this->_currentEntity;
    
            // Extraction du lien.
            $arg1 = strtok($link, '_');
            $arg2 = strtok('_');
            $arg3 = strtok('_');
            $arg4 = strtok('_');
            $arg5 = strtok('_');
            $arg6 = strtok('_');
            $arg7 = strtok('_');
    
            // Nettoyage du lien.
            if ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 != '' && $arg5 != '' && $arg6 != '' && $arg7 != '') {
                // Forme : signe_signer_date_action_source_target_meta
                $list = array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7);
            } elseif ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 != '' && $arg5 != '' && $arg6 != '' && $arg7 == '') {
                // Forme : 0_signer_date_action_source_target_meta
                $list = array('0', $arg1, $arg2, $arg3, $arg4, $arg5, $arg6);
            } elseif ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 != '' && $arg5 != '' && $arg6 == '' && $arg7 == '') {
                // Forme : 0_0_date_action_source_target_meta
                $list = array('0', $ent, $arg1, $arg2, $arg3, $arg4, $arg5);
            } elseif ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 != '' && $arg5 == '' && $arg6 == '' && $arg7 == '') {
                // Forme : 0_0_0_action_source_target_meta
                $list = array('0', $ent, $date, $arg1, $arg2, $arg3, $arg4);
            } elseif ($arg1 != '' && $arg2 != '' && $arg3 != '' && $arg4 == '' && $arg5 == '' && $arg6 == '' && $arg7 == '') {
                // Forme : 0_0_0_action_source_target_0
                $list = array('0', $ent, $date, $arg1, $arg2, $arg3, '0');
            } elseif ($arg1 != '' && $arg2 != '' && $arg3 == '' && $arg4 == '' && $arg5 == '' && $arg6 == '' && $arg7 == '') {
                // Forme : 0_0_0_action_source_0_0 : le minimum !
                $list = array('0', $ent, $date, $arg1, $arg2, '0', '0');
            } else {
                $list = array('0', '0', '0', '0', '0', '0', '0');
            }
    
            unset($date, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7);
            return $list;*/
    }

    public function flatLinkExtractAsArray(string $link): array {
        $list = array();

        $blockLinkInstance = new BlocLink($this->_nebuleInstance, 'new');
        $blockLinkInstance->addLink($link);

        return $list;
    }

    /**
     * Extrait et analyse un lien.
     *
     * @param string $link : lien à extraire.
     * @return ?LinkRegister : une instance de lien.
     *                     Accepte une chaine de caractère représentant un lien.
     * En fonction du nombre de champs, c'est interprété :
     * 2 champs : 0_0_0_action_source_0_0
     * 3 champs : 0_0_0_action_source_target_0
     * 4 champs : 0_0_0_action_source_target_meta
     * 5 champs : 0_0_date_action_source_target_meta
     * 6 champs : 0_signer_date_action_source_target_meta
     * 7 champs : signe_signer_date_action_source_target_meta
     * Sinon    : 0_0_0_0_0_0_0
     * Retourne une instance du lien.
     */
    public function flatLinkExtractAsInstance_DISABLED(string $link): ?LinkRegister
    {
        return null; // FIXME
        /*// Vérifier compatibilité avec liens incomplets...

        // Extrait le lien.
        $linkArray = $this->flatLinkExtractAsArray_DISABLED($link);

        // Création du lien.
        $flatLink = $linkArray[0] . '_' . $linkArray[1] . '_' . $linkArray[2] . '_' . $linkArray[3] . '_' . $linkArray[4] . '_' . $linkArray[5] . '_' . $linkArray[6];
        $linkInstance = $this->newLink($flatLink);

        unset($linkArray, $flatLink);
        return $linkInstance;*/
    }
}
