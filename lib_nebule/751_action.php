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

    protected ?Applications $_applicationInstance = null;
    protected ?Translates $_traductionInstance = null;
    protected ?Displays $_displayInstance = null;
    protected bool $_unlocked = false;

    public function __construct(Applications $applicationInstance)
    {
        $this->_applicationInstance = $applicationInstance;
        $this->_nebuleInstance = $applicationInstance->getNebuleInstance();
        $this->setEnvironment();
        $this->initialisation();
    }

    public function initialisation()
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Load actions', Metrology::LOG_LEVEL_DEBUG);
        $this->_traductionInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_unlocked = $this->_entitiesInstance->getCurrentEntityIsUnlocked();

        // Aucun affichage, aucune traduction, aucune action avant le retour de cette fonction.
        // Les instances interdépendantes doivent être synchronisées.
    }

    public function __destruct()
    {
        return true;
    }

    public function __toString()
    {
        return 'Action';
    }

    public function __sleep() // TODO do not cache
    {
        return array();
    }

    public function __wakeup() // TODO do not cache
    {
        global $applicationInstance;

        $this->_applicationInstance = $applicationInstance;
        $this->_nebuleInstance = $applicationInstance->getNebuleInstance();
        $this->setEnvironment();
        $this->initialisation();
    }


    public function genericActions()
    {
        $this->_metrologyInstance->addLog('Generic actions', Metrology::LOG_LEVEL_DEBUG);

        // Vérifie que l'entité est déverrouillée.
        if (!$this->_unlocked)
            return;

        // Extrait les actions.
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

        $this->_metrologyInstance->addLog('Router generic actions', Metrology::LOG_LEVEL_DEBUG);

        // Gère la dissimulation d'un lien.
        if ($this->_actionObfuscateLinkInstance != ''
            && is_a($this->_actionObfuscateLinkInstance, 'Nebule\Library\Link')
            && $this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')
        )
            $this->_actionObfuscateLink();

        // Gère la suppression d'un objet.
        if ($this->_actionDeleteObject
            && is_a($this->_actionDeleteObjectInstance, 'Nebule\Library\Node')
        )
            $this->_actionDeleteObject();

        // Gère la protection/déprotection d'un objet.
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

        // Gère les synchronisations (toujours les objets avant les liens).
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

        // Gère les marques des objets.
        if ($this->_actionMarkObject != '')
            $this->_actionMarkObject();
        if ($this->_actionUnmarkObject != '')
            $this->_actionUnmarkObject();
        if ($this->_actionUnmarkAllObjects)
            $this->_actionUnmarkAllObjects();

        // Gère les téléchargements.
        if ($this->_actionUploadFile)
            $this->_actionUploadFile();
        if ($this->_actionUploadText)
            $this->_actionUploadText();

        // Gère les groupes.
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

        // Gère les conversations et messages.
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

        // Gère les monnaies.
        if ($this->_actionCreateCurrency)
            $this->_actionCreateCurrency();
        if ($this->_actionCreateTokenPool)
            $this->_actionCreateTokenPool();
        if ($this->_actionCreateTokens)
            $this->_actionCreateTokens();

        $this->_metrologyInstance->addLog('Generic actions end', Metrology::LOG_LEVEL_DEBUG);
    }

    protected function _getOptionAsBoolean(string $name): bool
    {
        return $this->_configurationInstance->getOptionAsBoolean($name);
    }

    protected function _checkBooleanOptions(array $list): bool
    {
        return $this->_configurationInstance->checkBooleanOptions($list);
    }

    const ACTIONS_PERMIT = array(
        'DeleteObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'ProtectObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'UnprotectObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'ShareProtectObjectToEntity' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'ShareProtectObjectToGroupOpened' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'ShareProtectObjectToGroupClosed' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'CancelShareProtectObjectToEntity' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'SynchronizeObject' => ['permitWrite','permitWriteObject'],
        'SynchronizeEntity' => ['permitWrite','permitWriteObject'],
        'SynchronizeObjectLinks' => ['permitWrite','permitWriteLink'],
        'SynchronizeApplication' => ['permitWrite','permitWriteLink','permitWriteObject','permitSynchronizeObject','permitSynchronizeLink','permitSynchronizeApplication'],
        'SynchronizeNewEntity' => ['permitWrite','permitWriteObject','permitSynchronizeObject','permitSynchronizeLink'],
        'UploadFileLinks' => ['permitWrite','permitWriteLink','permitUploadLink'],
        'UploadFile' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'UploadText' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'CreateEntity' => ['permitWrite','permitWriteLink','permitWriteObject','permitWriteEntity'],
        'CreateGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteGroup'],
        'SignLink' => ['unlocked','permitWrite','permitWriteLink','permitCreateLink'],
        'UploadLink' => ['permitWrite','permitWriteLink','permitUploadLink'],
        'ObfuscateLink' => ['unlocked','permitWrite','permitWriteLink','permitObfuscatedLink'],
        'DeleteGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'AddToGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'RemoveFromGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'AddItemToGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'RemoveItemFromGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'CreateConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteConversation'],
        'DeleteConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'AddMessageOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'RemoveMessageOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'AddMemberOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'RemoveMemberOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'CreateMessage' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteConversation'],
        'AddProperty' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'CreateCurrency' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitCurrency','permitWriteCurrency','permitCreateCurrency'],
        'CreateTokenPool' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitCurrency','permitWriteCurrency','permitCreateCurrency'],
        'CreateTokens' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitCurrency','permitWriteCurrency','permitCreateCurrency'],
    );

    protected function _checkPermitAction($name): bool
    {
        if (!isset(self::ACTIONS_PERMIT[$name]))
        {
            $this->_metrologyInstance->addLog('unknown action ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__,'5edb0ddf');
            return false;
        }
        if (!$this->_configurationInstance->checkBooleanOptions(self::ACTIONS_PERMIT[$name]))
        {
            $this->_metrologyInstance->addLog('insuffisant permission for action=' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__,'a5f2e385');
            return false;
        }
        return true;
    }



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
    public function specialActions()
    {
        $this->_metrologyInstance->addLog('Special actions', Metrology::LOG_LEVEL_DEBUG);

        // Vérifie que l'action de création d'entité soit permise entité verrouillée.
        if ($this->_checkBooleanOptions(array('permitPublicCreateEntity'))
            || $this->_unlocked
        )
            $this->_extractActionCreateEntity();

        // Vérifie que l'action de chargement de lien soit permise.
        if ($this->_checkBooleanOptions(array('permitWrite','permitWriteLink','permitUploadLink'))
            || $this->_unlocked
        ) {
            // Extrait les actions.
            $this->_extractActionSignLink1();
            $this->_extractActionSignLink2();
            $this->_extractActionSignLink3();
            $this->_extractActionUploadLink();
            $this->_extractActionUploadFileLinks();
        }

        $this->_metrologyInstance->addLog('Router generic actions', Metrology::LOG_LEVEL_DEBUG);

        // Si l'action de création d'entité est validée.
        if ($this->_actionCreateEntity)
            $this->_actionCreateEntity();

        // Si l'action de chargement de lien est permise y compris entité verrouillée.
        if ($this->_checkBooleanOptions(array('permitWrite','permitWriteLink','permitUploadLink'))
            && ($this->_getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_getOptionAsBoolean('permitPublicUploadLink')
                || $this->_unlocked
            )
        ) {
            // Lien à signer 1.
            if ($this->_checkBooleanOptions(array('unlocked','permitCreateLink'))
                && is_a($this->_actionSignLinkInstance1, 'Nebule\Library\Link')
            )
                $this->_actionSignLink($this->_actionSignLinkInstance1, $this->_actionSignLinkInstance1Obfuscate);

            // Lien à signer 2.
            if ($this->_checkBooleanOptions(array('unlocked','permitCreateLink'))
                && is_a($this->_actionSignLinkInstance2, 'Nebule\Library\Link')
            )
                $this->_actionSignLink($this->_actionSignLinkInstance2, $this->_actionSignLinkInstance2Obfuscate);

            // Lien à signer 3.
            if ($this->_checkBooleanOptions(array('unlocked','permitCreateLink'))
                && is_a($this->_actionSignLinkInstance3, 'Nebule\Library\Link')
            )
                $this->_actionSignLink($this->_actionSignLinkInstance3, $this->_actionSignLinkInstance3Obfuscate);

            // Liens pré-signés.
            if ($this->_actionUploadLinkInstance !== null
                && is_a($this->_actionUploadLinkInstance, 'Nebule\Library\Link')
            )
                $this->_actionUploadLink($this->_actionUploadLinkInstance);

            // Fichier de liens pré-signés.
            if ($this->_actionUploadFileLinks)
                $this->_actionUploadFileLinks();
        }

        $this->_metrologyInstance->addLog('Special actions end', Metrology::LOG_LEVEL_DEBUG);
    }


    /**
     * Extrait pour action si on doit signer un lien (1).
     */
    protected function _extractActionSignLink1()
    {
        if (!$this->_checkPermitAction('SignLink'))
            return;

        $this->_metrologyInstance->addLog('Extract action sign link 1', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK1, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK1_OBFUSCATE);
        if ($arg == '')
            $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_SIGN_LINK1, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if ($arg != ''
            && strlen($arg) != 0
        ) {
            $this->_actionSignLinkInstance1 = $this->flatLinkExtractAsInstance_disabled($arg);
            $this->_actionSignLinkInstance1Obfuscate = $argObfuscate;
        }
    }

    protected $_actionSignLinkInstance1 = null;
    protected $_actionSignLinkInstance1Obfuscate = false;

    /**
     * Extrait pour action si on doit signer un lien (2).
     */
    protected function _extractActionSignLink2()
    {
        if (!$this->_checkPermitAction('SignLink'))
            return;

        $this->_metrologyInstance->addLog('Extract action sign link 2', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK2, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK2_OBFUSCATE);
        if ($arg == '')
            $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_SIGN_LINK2, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if ($arg != ''
            && strlen($arg) != 0
        ) {
            $this->_actionSignLinkInstance2 = $this->flatLinkExtractAsInstance_disabled($arg);
            $this->_actionSignLinkInstance2Obfuscate = $argObfuscate;
        }
    }

    protected $_actionSignLinkInstance2 = null;
    protected $_actionSignLinkInstance2Obfuscate = false;

    /**
     * Extrait pour action si on doit signer un lien (3).
     */
    protected function _extractActionSignLink3()
    {
        if (!$this->_checkPermitAction('SignLink'))
            return;

        $this->_metrologyInstance->addLog('Extract action sign link 3', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK3, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK3_OBFUSCATE);
        if ($arg == '')
            $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_SIGN_LINK3, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if ($arg != ''
            && strlen($arg) != 0
        ) {
            $this->_actionSignLinkInstance3 = $this->flatLinkExtractAsInstance_disabled($arg);
            $this->_actionSignLinkInstance3Obfuscate = $argObfuscate;
        }
    }

    protected $_actionSignLinkInstance3 = null;
    protected $_actionSignLinkInstance3Obfuscate = false;


    /**
     * Définit si il y a une action en cours de chargement de lien pré-signé.
     * Contient le lien à charger ou un texte vide.
     *
     * @var Link|string
     */
    protected $_actionUploadLinkInstance = null;

    /**
     * Extrait un lien pré-signé.
     *
     * Nécessite :
     * - droit d'écriture
     * - droit d'écriture des liens
     * - droit de chargement de liens
     * - droit de chargement public de liens ou du maître du code ou entité déverrouillée
     *
     * @return void
     */
    protected function _extractActionUploadLink()
    {
        if ($this->_checkPermitAction('UploadLink')
            && ($this->_getOptionAsBoolean('permitPublicUploadLink')
                || $this->_getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_unlocked
            )
        ) {
            $this->_metrologyInstance->addLog('Extract action upload signed link', Metrology::LOG_LEVEL_DEBUG);

            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_UPLOAD_SIGNED_LINK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            if ($arg == '')
                $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_SIGNED_LINK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Vérifie si restriction des liens au maître du code. Non par défaut.
            $permitNotCodeMaster = false;
            if ($this->_getOptionAsBoolean('permitPublicUploadLink')
                || $this->_unlocked
            )
                $permitNotCodeMaster = true;

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) != 0
            ) {
                $instance = $this->flatLinkExtractAsInstance_disabled($arg);
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


    /**
     * Extrait pour action si on doit dissimuler un lien.
     */
    protected function _extractActionObfuscateLink()
    {
        if (!$this->_checkPermitAction('ObfuscateLink'))
            return;

        $this->_metrologyInstance->addLog('Extract action obfuscate link', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_OBFUSCATE_LINK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if ($arg == '')
            $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_OBFUSCATE_LINK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (strlen($arg) != 0)
            $this->_actionObfuscateLinkInstance = $this->flatLinkExtractAsInstance_disabled($arg);
    }

    protected $_actionObfuscateLinkInstance = '';


    /**
     * Renvoie si l'action de suppression d'objet est validée.
     */
    public function getDeleteObject(): bool
    {
        return $this->_actionDeleteObject;
    }

    /**
     * Renvoie l'ID de l'objet supprimé.
     */
    public function getDeleteObjectID(): string
    {
        return $this->_actionDeleteObjectID;
    }

    /**
     * Extrait pour action si on doit supprimer l'objet en cours.
     */
    protected function _extractActionDeleteObject()
    {
        if (!$this->_checkPermitAction('DeleteObject'))
            return;

        $this->_metrologyInstance->addLog('Extract action delete object', Metrology::LOG_LEVEL_DEBUG);

        $argObject = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
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
            && $this->_getOptionAsBoolean('permitObfuscatedLink')
        )
            $this->_actionDeleteObjectObfuscate = true;
    }

    protected $_actionDeleteObject = false;
    protected $_actionDeleteObjectID = '0';
    protected $_actionDeleteObjectInstance = '';
    protected $_actionDeleteObjectForce = '';
    protected $_actionDeleteObjectObfuscate = '';


    /**
     * Extrait pour action si on doit protéger l'objet en cours.
     */
    protected function _extractActionProtectObject()
    {
        if (!$this->_checkPermitAction('ProtectObject'))
            return;

        $this->_metrologyInstance->addLog('Extract action protect object', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_PROTECT_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionProtectObjectInstance = $this->_cacheInstance->newNode($arg);
    }

    protected $_actionProtectObjectInstance = '';


    /**
     * Extrait pour action si on doit déprotéger l'objet en cours.
     */
    protected function _extractActionUnprotectObject()
    {
        if (!$this->_checkPermitAction('UnprotectObject'))
            return;

        $this->_metrologyInstance->addLog('Extract action unprotect object', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_UNPROTECT_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionUnprotectObjectInstance = $this->_cacheInstance->newNode($arg);
    }

    protected $_actionUnprotectObjectInstance = '';


    /**
     * Extrait pour action si on doit partager la protection de l'objet à une entité.
     */
    protected function _extractActionShareProtectObjectToEntity()
    {
        if (!$this->_checkPermitAction('ShareProtectObjectToEntity'))
            return;

        $this->_metrologyInstance->addLog('Extract action share protect object to entity', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToEntity = $arg;
    }

    protected $_actionShareProtectObjectToEntity = '';


    /**
     * Extrait pour action si on doit partager la protection de l'objet à un groupe ouvert.
     */
    protected function _extractActionShareProtectObjectToGroupOpened()
    {
        if (!$this->_checkPermitAction('ShareProtectObjectToGroupOpened'))
            return;

        $this->_metrologyInstance->addLog('Extract action share protect object to opened group', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_OPENED, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToGroupOpened = $arg;
    }

    protected $_actionShareProtectObjectToGroupOpened = '';


    /**
     * Extrait pour action si on doit partager la protection de l'objet à un groupe ouvert.
     */
    protected function _extractActionShareProtectObjectToGroupClosed()
    {
        if (!$this->_checkPermitAction('ShareProtectObjectToGroupClosed'))
            return;

        $this->_metrologyInstance->addLog('Extract action share protect object to closed group', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_CLOSED, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToGroupClosed = $arg;
    }

    protected $_actionShareProtectObjectToGroupClosed = '';


    /**
     * Extrait pour action si on doit annuler le partage la protection de l'objet à une entité.
     */
    protected function _extractActionCancelShareProtectObjectToEntity()
    {
        if (!$this->_checkPermitAction('CancelShareProtectObjectToEntity'))
            return;

        $this->_metrologyInstance->addLog('Extract action cancel share protect object to entity', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CANCEL_SHARE_PROTECT_TO_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionCancelShareProtectObjectToEntity = $arg;
    }

    protected $_actionCancelShareProtectObjectToEntity = '';


    /**
     * Retourne l'objet synchronisé.
     * @return string|entity
     */
    public function getSynchronizeObjectInstance()
    {
        return $this->_actionSynchronizeObjectInstance;
    }

    /**
     * Extrait pour action si on doit synchroniser l'objet en cours.
     */
    protected function _extractActionSynchronizeObject()
    {
        if (!$this->_checkPermitAction('SynchronizeObject'))
            return;

        $this->_metrologyInstance->addLog('Extract action synchronize object', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionSynchronizeObjectInstance = $this->_cacheInstance->newNode($arg);
    }

    protected $_actionSynchronizeObjectInstance = '';


    /**
     * Retourne l'entité synchronisée.
     *
     * @return string
     */
    public function getSynchronizeEntityInstance(): string
    {
        return $this->_actionSynchronizeEntityInstance;
    }

    /**
     * Extrait pour action si on doit synchroniser l'objet en cours.
     */
    protected function _extractActionSynchronizeEntity()
    {
        if (!$this->_checkPermitAction('SynchronizeEntity'))
            return;

        $this->_metrologyInstance->addLog('Extract action synchronize entity', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionSynchronizeEntityInstance = $this->_cacheInstance->newEntity($arg);
    }

    protected $_actionSynchronizeEntityInstance = '';


    /**
     * Retourne l'objet synchronisé.
     * @return string|entity
     */
    public function getSynchronizeObjectLinksInstance()
    {
        return $this->_actionSynchronizeObjectLinksInstance;
    }

    /**
     * Extrait pour action si on doit synchroniser les liens de l'objet en cours.
     */
    protected function _extractActionSynchronizeObjectLinks()
    {
        if (!$this->_checkPermitAction('SynchronizeObjectLinks'))
            return;

        $this->_metrologyInstance->addLog('Extract action synchronize object links', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_LINKS, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionSynchronizeObjectLinksInstance = $this->_cacheInstance->newNode($arg);
    }

    protected $_actionSynchronizeObjectLinksInstance = '';


    /**
     * Retourne l'entité synchronisée.
     * @return string|entity
     */
    public function getSynchronizeApplicationInstance()
    {
        return $this->_actionSynchronizeApplicationInstance;
    }

    /**
     * Extrait pour action si on doit synchroniser l'objet en cours.
     */
    protected function _extractActionSynchronizeApplication()
    {
        if ($this->_checkPermitAction('SynchronizeApplication')
            && ($this->_getOptionAsBoolean('permitPublicSynchronizeApplication')
                || $this->_unlocked
            )
        ) {
            $this->_metrologyInstance->addLog('Extract action synchronize entity', Metrology::LOG_LEVEL_DEBUG);

            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            if (Node::checkNID($arg))
                $this->_actionSynchronizeApplicationInstance = $this->_cacheInstance->newNode($arg);
        }
    }

    protected $_actionSynchronizeApplicationInstance = '';


    /**
     * Retourne l'entité synchronisée depuis une URL.
     *
     * @return string
     */
    public function getSynchronizeNewEntityInstance(): string
    {
        return $this->_actionSynchronizeNewEntityInstance;
    }

    /**
     * Extrait pour action si on doit synchroniser l'objet en cours.
     */
    protected function _extractActionSynchronizeNewEntity()
    {
        if (!$this->_checkPermitAction('SynchronizeNewEntity'))
            return;

        $this->_metrologyInstance->addLog('Extract action synchronize new entity', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_NEW_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

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
                $this->_metrologyInstance->addLog('Extract action synchronize new entity - ID=' . $id, Metrology::LOG_LEVEL_DEBUG);
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
                    $this->_metrologyInstance->addLog('Extract action synchronize new entity - URL=' . $this->_actionSynchronizeNewEntityURL, Metrology::LOG_LEVEL_DEBUG);
                }
            }
        }
    }

    protected $_actionSynchronizeNewEntityID = '';
    protected $_actionSynchronizeNewEntityURL = '';
    protected $_actionSynchronizeNewEntityInstance = '';


    /**
     * Extrait pour action si on doit marquer un objet.
     */
    protected function _extractActionMarkObject()
    {
        $this->_metrologyInstance->addLog('Extract action mark object', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_MARK_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionMarkObject = $arg;
    }

    protected $_actionMarkObject = '';


    /**
     * Extrait pour action si on doit retirer la marque d'un objet.
     */
    protected function _extractActionUnmarkObject()
    {
        $this->_metrologyInstance->addLog('Extract action unmark object', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_UNMARK_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionUnmarkObject = $arg;
    }

    protected $_actionUnmarkObject = '';


    /**
     * Extrait pour action si on doit supprimer toutes les marques des objets.
     *
     * @return void
     */
    protected function _extractActionUnmarkAllObjects()
    {
        $this->_metrologyInstance->addLog('Extract action unmark all objects', Metrology::LOG_LEVEL_DEBUG);

        $arg = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_UNMARK_ALL_OBJECT);

        if ($arg !== false)
            $this->_actionUnmarkAllObjects = true;
    }

    protected $_actionUnmarkAllObjects = false;


    /**
     * Définit si il y a une action en cours de téléchargement d'un fichier de liens pré-signés.
     *
     * @var boolean
     */
    protected $_actionUploadFileLinks = false;
    /**
     * Nom du fichier de liens pré-signés.
     *
     * @var string
     */
    protected $_actionUploadFileLinksName = '';
    /**
     * Taille du fichier de liens pré-signés.
     *
     * @var string
     */
    protected $_actionUploadFileLinksSize = '';
    /**
     * Chemin du fichier de liens pré-signés.
     *
     * @var string
     */
    protected $_actionUploadFileLinksPath = '';
    /**
     * Statut d'erreur de traitement.
     *
     * @var boolean
     */
    protected $_actionUploadFileLinksError = false;
    /**
     * Message du statut d'erreur de traitement.
     *
     * @var string
     */
    protected $_actionUploadFileLinksErrorMessage = 'Initialisation du transfert.';

    /**
     * Renvoie si l'action de transfert de fichier de liens pré-signés vers le serveur est validé.
     *
     * @return boolean
     */
    public function getUploadFileSignedLinks(): bool
    {
        return $this->_actionUploadFileLinks;
    }

    /**
     * Extrait pour action si un fichier de liens pré-signés est téléchargé vers le serveur.
     *
     * Nécessite :
     * - droit d'écriture
     * - droit d'écriture des liens
     * - droit de chargement de liens
     * - droit de chargement public de liens ou du maître du code ou entité déverrouillée
     *
     * @return void
     */
    protected function _extractActionUploadFileLinks()
    {
        if ($this->_checkPermitAction('UploadFileLinks')
            && ($this->_getOptionAsBoolean('permitPublicUploadLink')
                || $this->_getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_unlocked
            )
        ) {
            $this->_metrologyInstance->addLog('Extract action upload file of signed links', Metrology::LOG_LEVEL_DEBUG);

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
                        $this->_metrologyInstance->addLog('Action _extractActionUploadFileLinks File size too big', Metrology::LOG_LEVEL_ERROR);
                        $this->_actionUploadFileLinksError = true;
                        $this->_actionUploadFileLinksErrorMessage = 'File size too big';
                    }
                } else {
                    $this->_metrologyInstance->addLog('Action _extractActionUploadFileLinks File upload error', Metrology::LOG_LEVEL_ERROR);
                    $this->_actionUploadFileLinksError = true;
                    $this->_actionUploadFileLinksErrorMessage = 'File upload error';
                }
            }
        }
    }


    /*
	 * Transfert de fichier et nébulisation.
	 */
    protected $_actionUploadFile = false,
        $_actionUploadFileID = '0',
        $_actionUploadFileName = '',
        $_actionUploadFileExtension = '',
        $_actionUploadFileType = '',
        $_actionUploadFileSize = '',
        $_actionUploadFilePath = '',
        $_actionUploadFileUpdate = false,
        $_actionUploadFileProtect = false,
        $_actionUploadFileObfuscateLinks = false,
        $_actionUploadFileError = false,
        $_actionUploadFileErrorMessage = 'Initialisation du transfert.';

    /**
     * Renvoie si l'action de transfert de fichier vers le serveur est validée.
     *
     * @return boolean
     */
    public function getUploadObject(): bool
    {
        return $this->_actionUploadFile;
    }

    /**
     * Renvoie l'ID du fichier téléchargé vers le serveur.
     *
     * @return string
     */
    public function getUploadObjectID(): string
    {
        return $this->_actionUploadFileID;
    }

    /**
     * Renvoie si erreur.
     *
     * @return boolean
     */
    public function getUploadObjectError(): bool
    {
        return $this->_actionUploadFileError;
    }

    /**
     * Renvoie le message d'erreur.
     *
     * @return string
     */
    public function getUploadObjectErrorMessage(): string
    {
        return $this->_actionUploadFileErrorMessage;
    }

    /**
     * Extrait pour action si un fichier est téléchargé vers le serveur.
     *
     * @return void
     */
    protected function _extractActionUploadFile()
    {
        if (!$this->_checkPermitAction('UploadFile'))
            return;

        $this->_metrologyInstance->addLog('Extract action upload file', Metrology::LOG_LEVEL_DEBUG);

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
                        if ($this->_getOptionAsBoolean('permitProtectedObject')) {
                            $this->_actionUploadFileProtect = $argPrt;
                        }
                        if ($this->_getOptionAsBoolean('permitObfuscatedLink')) {
                            $this->_actionUploadFileObfuscateLinks = $argObf;
                        }
                    } else {
                        $this->_metrologyInstance->addLog('Action _extractActionUploadFile ioReadMaxData exeeded', Metrology::LOG_LEVEL_ERROR);
                        $this->_actionUploadFileError = true;
                        $this->_actionUploadFileErrorMessage = 'Le fichier dépasse la taille limite de transfert.';
                    }
                } else {
                    $this->_metrologyInstance->addLog('Action _extractActionUploadFile upload error', Metrology::LOG_LEVEL_ERROR);
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "No uploaded file.";
                }
                unset($upfname, $upinfo, $upext, $upname, $upsize, $uppath, $uptype);
                break;
            case UPLOAD_ERR_INI_SIZE:
                $this->_metrologyInstance->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_INI_SIZE', Metrology::LOG_LEVEL_ERROR);
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->_metrologyInstance->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_FORM_SIZE', Metrology::LOG_LEVEL_ERROR);
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->_metrologyInstance->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_PARTIAL', Metrology::LOG_LEVEL_ERROR);
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->_metrologyInstance->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_NO_FILE', Metrology::LOG_LEVEL_ERROR);
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->_metrologyInstance->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_NO_TMP_DIR', Metrology::LOG_LEVEL_ERROR);
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->_metrologyInstance->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_CANT_WRITE', Metrology::LOG_LEVEL_ERROR);
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $this->_metrologyInstance->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_EXTENSION', Metrology::LOG_LEVEL_ERROR);
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop.";
                break;
        }
    }

    /**
     * Lit le type mime d'un fichier par rapport à son extension et la table de correspondance $nebule_mimepathfile.
     *
     * @param string $f
     * @return string
     */
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


    /*
	 * Transfert de texte et nébulisation.
	 */
    protected $_actionUploadText = false;
    protected $_actionUploadTextName = '';
    protected $_actionUploadTextType = '';
    protected $_actionUploadTextContent = '';
    protected $_actionUploadTextID = '0';
    protected $_actionUploadTextProtect = false;
    protected $_actionUploadTextObfuscateLinks = false;
    protected $_actionUploadTextError = false;
    protected $_actionUploadTextErrorMessage = 'Initialisation du transfert.';

    /**
     * Renvoie si l'action de transfert de texte vers le serveur est validée.
     *
     * @return boolean
     */
    public function getUploadText(): bool
    {
        return $this->_actionUploadText;
    }

    /**
     * Renvoie le nom du texte téléchargé vers le serveur.
     */
    public function getUploadTextName(): string
    {
        return $this->_actionUploadTextName;
    }

    /**
     * Renvoie l'ID du texte téléchargé vers le serveur.
     */
    public function getUploadTextID(): string
    {
        return $this->_actionUploadTextID;
    }

    /**
     * Renvoie le code erreur.
     */
    public function getUploadTextError(): bool
    {
        return $this->_actionUploadTextError;
    }

    /**
     * Renvoie le message d'erreur.
     */
    public function getUploadTextErrorMessage(): string
    {
        return $this->_actionUploadTextErrorMessage;
    }

    /**
     * Extrait pour action si un texte est téléchargé vers le serveur.
     */
    protected function _extractActionUploadText()
    {
        if (!$this->_checkPermitAction('UploadText'))
            return;

        $this->_metrologyInstance->addLog('Extract action upload text', Metrology::LOG_LEVEL_DEBUG);

        $arg = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT);

        // Extraction du lien et stockage pour traitement.
        if ($arg !== false) {
            // Lit et nettoye le contenu des variables POST.
            $argText = filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argName = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_NAME, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
            $argType = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_TYPE, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));

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

                if ($this->_getOptionAsBoolean('permitProtectedObject'))
                    $this->_actionUploadTextProtect = $argPrt;
                if ($this->_getOptionAsBoolean('permitObfuscatedLink'))
                    $this->_actionUploadTextObfuscateLinks = $argObf;
            }
        }
    }


    /*
	 * Création d'entité.
	 */
    protected $_actionCreateEntity = false;
    protected $_actionCreateEntityPrefix = '';
    protected $_actionCreateEntitySuffix = '';
    protected $_actionCreateEntityFirstname = '';
    protected $_actionCreateEntityNikename = '';
    protected $_actionCreateEntityName = '';
    protected $_actionCreateEntityPassword = '';
    protected $_actionCreateEntityType = '';
    protected $_actionCreateEntityID = '0';
    protected $_actionCreateEntityObfuscateLinks = false;
    protected $_actionCreateEntityInstance = '';
    protected $_actionCreateEntityError = false;
    protected $_actionCreateEntityErrorMessage = 'Initialisation de la création.';

    /**
     * Renvoie si l'action de création d'entité a été faite.
     *
     * @return boolean
     */
    public function getCreateEntity(): bool
    {
        return $this->_actionCreateEntity;
    }

    /**
     * Revoie l'ID de la nouvelle entité.
     */
    public function getCreateEntityID(): string
    {
        return $this->_actionCreateEntityID;
    }

    /**
     * Revoie l'instance de la nouvelle entité.
     */
    public function getCreateEntityInstance(): string
    {
        return $this->_actionCreateEntityInstance;
    }

    /**
     * Revoie le code erreur de création de la nouvelle entité.
     *
     * @return boolean
     */
    public function getCreateEntityError(): bool
    {
        return $this->_actionCreateEntityError;
    }

    /**
     * Revoie le message d'erreur de création de la nouvelle entité.
     */
    public function getCreateEntityErrorMessage(): string
    {
        return $this->_actionCreateEntityErrorMessage;
    }

    /**
     * Extrait pour action si une entité doit être créée.
     */
    protected function _extractActionCreateEntity()
    {
        if ((!$this->_unlocked && !$this->_getOptionAsBoolean('permitPublicCreateEntity'))
            || !$this->_checkPermitAction('CreateEntity')
        )
            return;

        $this->_metrologyInstance->addLog('Extract action create entity', Metrology::LOG_LEVEL_DEBUG);

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY);

        if ($argCreate !== false)
            $this->_actionCreateEntity = true;

        // Si on crée une nouvelle entité.
        if ($this->_actionCreateEntity) {
            // Lit et nettoye le contenu des variables GET.
            $argPrefix = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PREFIX, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
            $argSuffix = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_SUFFIX, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
            $argFstnam = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_FIRSTNAME, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
            $argNiknam = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NIKENAME, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
            $argName = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NAME, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
            $argPasswd1 = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD1, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
            $argPasswd2 = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD2, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
            $argType = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_TYPE, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extrait les options de téléchargement.
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            $this->_actionCreateEntityPrefix = $argPrefix;
            $this->_actionCreateEntitySuffix = $argSuffix;
            $this->_actionCreateEntityFirstname = $argFstnam;
            $this->_actionCreateEntityNikename = $argNiknam;
            $this->_actionCreateEntityName = $argName;
            if ($this->_getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateEntityObfuscateLinks = $argObf;

            if ($argPasswd1 == $argPasswd2)
                $this->_actionCreateEntityPassword = $argPasswd1;
            else {
                $this->_metrologyInstance->addLog('Action _extractActionCreateEntity passwords not match', Metrology::LOG_LEVEL_ERROR);
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


    /**
     * Renvoie si l'action de création du groupe a été faite.
     */
    public function getCreateGroup(): bool
    {
        return $this->_actionCreateGroup;
    }

    /**
     * Revoie l'ID du nouveau groupe.
     */
    public function getCreateGroupID(): string
    {
        return $this->_actionCreateGroupID;
    }

    /**
     * Revoie l'instance du nouveau groupe.
     */
    public function getCreateGroupInstance(): string
    {
        return $this->_actionCreateGroupInstance;
    }

    /**
     * Revoie le code erreur de création du nouveau groupe.
     */
    public function getCreateGroupError(): bool
    {
        return $this->_actionCreateGroupError;
    }

    /**
     * Revoie le message d'erreur de création du nouveau groupe.
     */
    public function getCreateGroupErrorMessage(): string
    {
        return $this->_actionCreateGroupErrorMessage;
    }

    /**
     * Extrait pour action si un groupe doit être créé.
     */
    protected function _extractActionCreateGroup()
    {
        if (!$this->_checkPermitAction('CreateGroup'))
            return;

        $this->_metrologyInstance->addLog('Extract action create group', Metrology::LOG_LEVEL_DEBUG);

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_GROUP);

        if ($argCreate !== false)
            $this->_actionCreateGroup = true;

        // Si on crée une nouvelle entité.
        if ($this->_actionCreateGroup) {
            // Lit et nettoye le contenu des variables GET.
            $argName = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_GROUP_NAME, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));

            // Extrait les options de téléchargement.
            $argCld = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_GROUP_CLOSED);
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_GROUP_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            $this->_actionCreateGroupName = $argName;
            $this->_actionCreateGroupClosed = $argCld;
            if ($this->_getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateGroupObfuscateLinks = $argObf;
        }
    }

    protected $_actionCreateGroup = false,
        $_actionCreateGroupName = '',
        $_actionCreateGroupID = '0',
        $_actionCreateGroupClosed = false,
        $_actionCreateGroupObfuscateLinks = false,
        $_actionCreateGroupInstance = '',
        $_actionCreateGroupError = false,
        $_actionCreateGroupErrorMessage = 'Initialisation de la création.';


    /**
     * Renvoie si l'action de suppression du groupe a été faite.
     */
    public function getDeleteGroup(): bool
    {
        return $this->_actionDeleteGroup;
    }

    /**
     * Revoie l'ID du groupe.
     */
    public function getDeleteGroupID(): string
    {
        return $this->_actionDeleteGroupID;
    }

    /**
     * Revoie le code erreur de suppression du groupe.
     */
    public function getDeleteGroupError(): bool
    {
        return $this->_actionDeleteGroupError;
    }

    /**
     * Revoie le message d'erreur de suppression du groupe.
     */
    public function getDeleteGroupErrorMessage(): string
    {
        return $this->_actionDeleteGroupErrorMessage;
    }

    /**
     * Extrait pour action si un groupe doit être supprimé.
     */
    protected function _extractActionDeleteGroup()
    {
        if (!$this->_checkPermitAction('DeleteGroup'))
            return;

        $this->_metrologyInstance->addLog('Extract action delete group', Metrology::LOG_LEVEL_DEBUG);

        $argDelete = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if ($argDelete !== ''
            && strlen($argDelete) >= blocLink::NID_MIN_HASH_SIZE
            && Node::checkNID($argDelete)
        ) {
            $this->_actionDeleteGroup = true;
            $this->_actionDeleteGroupID = $argDelete;
        }
    }

    protected $_actionDeleteGroup = false,
        $_actionDeleteGroupID = '0',
        $_actionDeleteGroupError = false,
        $_actionDeleteGroupErrorMessage = 'Initialisation de la supression.';


    /**
     * Renvoie si l'action d'ajout de objet courant au groupe a été faite.
     */
    public function getAddToGroup(): string
    {
        return $this->_actionAddToGroup;
    }

    /**
     * Extrait pour action si l'objet courant doit être ajouté à un groupe.
     */
    protected function _extractActionAddToGroup()
    {
        if (!$this->_checkPermitAction('AddToGroup'))
            return;

        $this->_metrologyInstance->addLog('Extract action add to group', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_TO_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionAddToGroup = $arg;
    }

    protected $_actionAddToGroup = '';


    /**
     * Renvoie si l'action de suppression de l'objet courant du groupe a été faite.
     */
    public function getRemoveFromGroup(): string
    {
        return $this->_actionRemoveFromGroup;
    }

    /**
     * Extrait pour action si l'objet courant doit être retirer d'un groupe.
     */
    protected function _extractActionRemoveFromGroup()
    {
        if (!$this->_checkPermitAction('RemoveFromGroup'))
            return;

        $this->_metrologyInstance->addLog('Extract action remove from group', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_REMOVE_FROM_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionRemoveFromGroup = $arg;
    }

    protected $_actionRemoveFromGroup = '';


    /**
     * Renvoie si l'action d'ajout d'un objet au groupe courant a été faite.
     */
    public function getAddItemToGroup(): string
    {
        return $this->_actionAddItemToGroup;
    }

    /**
     * Extrait pour action si un objet doit être ajouté au groupe courant.
     */
    protected function _extractActionAddItemToGroup()
    {
        if (!$this->_checkPermitAction('AddItemToGroup'))
            return;

        $this->_metrologyInstance->addLog('Extract action add item to group', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_ITEM_TO_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionAddItemToGroup = $arg;
    }

    protected $_actionAddItemToGroup = '';


    /**
     * Renvoie si l'action de retrait du groupe a été faite.
     */
    public function getRemoveItemFromGroup(): string
    {
        return $this->_actionRemoveItemFromGroup;
    }

    /**
     * Extrait pour action si un objet doit être retiré au groupe courant.
     */
    protected function _extractActionRemoveItemFromGroup()
    {
        if (!$this->_checkPermitAction('RemoveItemFromGroup'))
            return;

        $this->_metrologyInstance->addLog('Extract action remove item from group', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionRemoveItemFromGroup = $arg;
    }

    protected $_actionRemoveItemFromGroup = '';


    /**
     * Renvoie si l'action de création de la conversation a été faite.
     */
    public function getCreateConversation(): bool
    {
        return $this->_actionCreateConversation;
    }

    /**
     * Revoie l'ID de la nouvelle conversation.
     */
    public function getCreateConversationID(): string
    {
        return $this->_actionCreateConversationID;
    }

    /**
     * Revoie l'instance de la nouvelle conversation.
     */
    public function getCreateConversationInstance(): string
    {
        return $this->_actionCreateConversationInstance;
    }

    /**
     * Revoie le code erreur de création de la nouvelle conversation.
     */
    public function getCreateConversationError(): bool
    {
        return $this->_actionCreateConversationError;
    }

    /**
     * Revoie le message d'erreur de création de la nouvelle conversation.
     */
    public function getCreateConversationErrorMessage(): string
    {
        return $this->_actionCreateConversationErrorMessage;
    }

    /**
     * Extrait pour action si une conversation doit être créé.
     */
    protected function _extractActionCreateConversation()
    {
        if (!$this->_checkPermitAction('CreateConversation'))
            return;

        $this->_metrologyInstance->addLog('Extract action create group', Metrology::LOG_LEVEL_DEBUG);

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION);

        if ($argCreate !== false)
            $this->_actionCreateConversation = true;

        // Si on crée une nouvelle conversation.
        if ($this->_actionCreateConversation) {
            // Lit et nettoye le contenu des variables GET.
            $argName = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_NAME, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));

            // Extrait les options de téléchargement.
            $argCld = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_CLOSED);
            $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_PROTECTED);
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            $this->_actionCreateConversationName = $argName;
            $this->_actionCreateConversationClosed = $argCld;
            if ($this->_getOptionAsBoolean('permitProtectedObject'))
                $this->_actionCreateConversationProtected = $argPrt;
            if ($this->_getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateConversationObfuscateLinks = $argObf;
        }
    }

    protected $_actionCreateConversation = false,
        $_actionCreateConversationName = '',
        $_actionCreateConversationID = '0',
        $_actionCreateConversationClosed = false,
        $_actionCreateConversationProtected = false,
        $_actionCreateConversationObfuscateLinks = false,
        $_actionCreateConversationInstance = '',
        $_actionCreateConversationError = false,
        $_actionCreateConversationErrorMessage = 'Initialisation de la création.';


    /**
     * Renvoie si l'action de suppression de la conversation a été faite.
     */
    public function getDeleteConversation(): bool
    {
        return $this->_actionDeleteConversation;
    }

    /**
     * Revoie l'ID de la conversation.
     */
    public function getDeleteConversationID(): string
    {
        return $this->_actionDeleteConversationID;
    }

    /**
     * Revoie le code erreur de suppression de la conversation.
     */
    public function getDeleteConversationError(): bool
    {
        return $this->_actionDeleteConversationError;
    }

    /**
     * Revoie le message d'erreur de suppression de la conversation.
     */
    public function getDeleteConversationErrorMessage(): string
    {
        return $this->_actionDeleteConversationErrorMessage;
    }

    /**
     * Extrait pour action si une conversation doit être supprimé.
     */
    protected function _extractActionDeleteConversation()
    {
        if (!$this->_checkPermitAction('DeleteConversation'))
            return;

        $this->_metrologyInstance->addLog('Extract action delete conversation', Metrology::LOG_LEVEL_DEBUG);

        $argDelete = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if ($argDelete !== ''
            && strlen($argDelete) >= blocLink::NID_MIN_HASH_SIZE
            && Node::checkNID($argDelete)
        ) {
            $this->_actionDeleteConversation = true;
            $this->_actionDeleteConversationID = $argDelete;
        }
    }

    protected $_actionDeleteConversation = false,
        $_actionDeleteConversationID = '0',
        $_actionDeleteConversationError = false,
        $_actionDeleteConversationErrorMessage = 'Initialisation de la supression.';


    /**
     * Renvoie si l'action d'ajout de objet courant à la conversation a été faite.
     */
    public function getAddMessageOnConversation(): string
    {
        return $this->_actionAddMessageOnConversation;
    }

    /**
     * Extrait pour action si l'objet courant doit être ajouté à une conversation.
     */
    protected function _extractActionAddMessageOnConversation()
    {
        if (!$this->_checkPermitAction('AddMessageOnConversation'))
            return;

        $this->_metrologyInstance->addLog('Extract action add to conversation', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_TO_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionAddMessageOnConversation = $arg;
    }

    protected $_actionAddMessageOnConversation = '';


    /**
     * Renvoie si l'action de suppression de l'objet courant de la conversation a été faite.
     */
    public function getRemoveMessageOnConversation(): string
    {
        return $this->_actionRemoveMessageOnConversation;
    }

    /**
     * Extrait pour action si l'objet courant doit être retirer d'une conversation.
     */
    protected function _extractActionRemoveMessageOnConversation()
    {
        if (!$this->_checkPermitAction('RemoveMessageOnConversation'))
            return;

        $this->_metrologyInstance->addLog('Extract action remove from conversation', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_REMOVE_FROM_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionRemoveMessageOnConversation = $arg;
    }

    protected $_actionRemoveMessageOnConversation = '';


    /**
     * Renvoie si l'action d'ajout d'un objet à la conversation courant a été faite.
     */
    public function getAddMemberOnConversation(): string
    {
        return $this->_actionAddMemberOnConversation;
    }

    /**
     * Extrait pour action si un objet doit être ajouté à la conversation courant.
     */
    protected function _extractActionAddMemberOnConversation()
    {
        if (!$this->_checkPermitAction('AddMemberOnConversation'))
            return;

        $this->_metrologyInstance->addLog('Extract action add item to conversation', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_ITEM_TO_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionAddMemberOnConversation = $arg;
    }

    protected $_actionAddMemberOnConversation = '';


    /**
     * Renvoie si l'action de retrait de la conversation a été faite.
     */
    public function getRemoveMemberOnConversation(): string
    {
        return $this->_actionRemoveMemberOnConversation;
    }

    /**
     * Extrait pour action si un objet doit être retiré à la conversation courant.
     */
    protected function _extractActionRemoveMemberOnConversation()
    {
        if (!$this->_checkPermitAction('RemoveMemberOnConversation'))
            return;

        $this->_metrologyInstance->addLog('Extract action remove item from conversation', Metrology::LOG_LEVEL_DEBUG);

        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if (Node::checkNID($arg))
            $this->_actionRemoveMemberOnConversation = $arg;
    }

    protected $_actionRemoveMemberOnConversation = '';

    /**
     * Extrait pour action si une conversation doit être créé.
     *
     * @return void
     */
    protected function _extractActionCreateMessage()
    {
        if (!$this->_checkPermitAction('CreateMessage'))
            return;

        $this->_metrologyInstance->addLog('Extract action create message', Metrology::LOG_LEVEL_DEBUG);

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_MESSAGE);

        if ($argCreate !== false)
            $this->_actionCreateMessage = true;

        // Si on crée un nouveau message.
        if ($this->_actionCreateMessage) {
            // Extrait les options de téléchargement.
            $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_PROTECT);
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            if ($this->_getOptionAsBoolean('permitProtectedObject'))
                $this->_actionCreateMessageProtected = $argPrt;
            if ($this->_getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateMessageObfuscateLinks = $argObf;
        }

        // Le reste des valeurs est récupéré par la partie création d'un texte.
    }

    protected $_actionCreateMessage = false,
        $_actionCreateMessageID = '0',
        $_actionCreateMessageError = false,
        $_actionCreateMessageProtected = false,
        $_actionCreateMessageObfuscateLinks = false,
        $_actionCreateMessageErrorMessage = 'Initialisation de la supression.';


    /**
     * Revoie le code erreur d'ajout de propriété.
     */
    public function getAddPropertyError(): bool
    {
        return $this->_actionAddPropertyError;
    }

    /**
     * Revoie le message d'erreur d'ajout de propriété.
     */
    public function getAddPropertyErrorMessage(): string
    {
        return $this->_actionAddPropertyErrorMessage;
    }

    /**
     * Extrait pour action si une propriété doit être ajoutée à un objet.
     *
     * @return void
     */
    protected function _extractActionAddProperty()
    {
        if (!$this->_checkPermitAction('AddProperty'))
            return;

        $this->_metrologyInstance->addLog('Extract action add property', Metrology::LOG_LEVEL_DEBUG);

        $argAdd = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        if ($argAdd != '')
            $this->_actionAddProperty = $argAdd;

        // Si on crée une nouvelle propriété.
        if ($this->_actionAddProperty != '') {
            // Extrait les autres variables.
            $argObj = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $argVal = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_VALUE, FILTER_SANITIZE_STRING));
            $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_PROTECTED);
            $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_OBFUSCATE_LINKS);

            // Sauvegarde les valeurs.
            if ($argVal != '') {
                if ($argObj == '')
                    $this->_actionAddPropertyObject = $this->_nebuleInstance->getCurrentObject();
                else
                    $this->_actionAddPropertyObject = $argObj;
                $this->_actionAddPropertyValue = $argVal;
                if ($this->_getOptionAsBoolean('permitProtectedObject'))
                    $this->_actionAddPropertyProtected = $argPrt;
                if ($this->_getOptionAsBoolean('permitObfuscatedLink'))
                    $this->_actionAddPropertyObfuscateLinks = $argObf;
            } else {
                $this->_metrologyInstance->addLog('Action _extractActionAddProperty null value', Metrology::LOG_LEVEL_ERROR);
                $this->_actionAddPropertyError = true;
                $this->_actionAddPropertyErrorMessage = 'Valeur vide.';
            }
        }
    }

    protected $_actionAddProperty = '',
        $_actionAddPropertyObject = '',
        $_actionAddPropertyValue = '',
        $_actionAddPropertyProtected = false,
        $_actionAddPropertyObfuscateLinks = false,
        $_actionAddPropertyError = false,
        $_actionAddPropertyErrorMessage = 'Initialisation de la supression.';


    /**
     * Renvoie si l'action de création de la monnaie a été faite.
     *
     * @return boolean
     */
    public function getCreateCurrency(): bool
    {
        return $this->_actionCreateCurrency;
    }

    /**
     * Revoie l'ID du nouveau sac de jetons.
     *
     * @return string
     */
    public function getCreateCurrencyID(): string
    {
        return $this->_actionCreateCurrencyID;
    }

    /**
     * Revoie l'instance du nouveau sac de jetons.
     *
     * @return Currency
     */
    public function getCreateCurrencyInstance(): Currency
    {
        return $this->_actionCreateCurrencyInstance;
    }

    /**
     * Revoie les paramètres de la nouvelle monnaie.
     *
     * @return array
     */
    public function getCreateCurrencyParam(): array
    {
        return $this->_actionCreateCurrencyParam;
    }

    /**
     * Revoie le code erreur de création de la nouvelle monnaie.
     *
     * @return boolean
     */
    public function getCreateCurrencyError(): bool
    {
        return $this->_actionCreateCurrencyError;
    }

    /**
     * Revoie le message d'erreur de création de la nouvelle monnaie.
     *
     * @return string
     */
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
    protected function _extractActionCreateCurrency()
    {
        if (!$this->_checkPermitAction('CreateCurrency'))
            return;

        $this->_metrologyInstance->addLog('Extract action create currency', Metrology::LOG_LEVEL_DEBUG);

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_CURRENCY);

        if ($argCreate !== false)
            $this->_actionCreateCurrency = true;

        if ($this->_actionCreateCurrency) {
            // Récupère la liste des propriétés.
            $instance = $this->_nebuleInstance->getCurrentCurrencyInstance();
            $propertiesList = $instance->getPropertiesList();
            unset($instance);

            foreach ($propertiesList['currency'] as $name => $property) {
                // Extrait une valeur.
                if (isset($property['checkbox'])) {
                    $value = '';
                    $valueArray = filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    foreach ($valueArray as $item)
                        $value .= ' ' . trim($item);
                    $this->_actionCreateCurrencyParam[$name] = trim($value);
                    unset($value, $valueArray);
                } else
                    $this->_actionCreateCurrencyParam[$name] = trim(filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
                $this->_metrologyInstance->addLog('Extract action create currency - _' . $property['shortname'] . ':' . $this->_actionCreateCurrencyParam[$name], Metrology::LOG_LEVEL_DEVELOP);

                // Extrait si forcé.
                if (isset($property['forceable'])) {
                    $this->_actionCreateCurrencyParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                    if ($this->_actionCreateCurrencyParam['Force' . $name])
                        $this->_metrologyInstance->addLog('Extract action create currency - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG);
                }
            }
        }
    }

    protected $_actionCreateCurrency = false;
    protected $_actionCreateCurrencyID = '';
    protected $_actionCreateCurrencyInstance = null;
    protected $_actionCreateCurrencyParam = array();
    protected $_actionCreateCurrencyError = false;
    protected $_actionCreateCurrencyErrorMessage = 'Initialisation de la création.';


    /**
     * Renvoie si l'action de création du sac de jetons a été faite.
     *
     * @return boolean
     */
    public function getCreateTokenPool(): bool
    {
        return $this->_actionCreateTokenPool;
    }

    /**
     * Revoie l'ID du nouveau sac de jetons.
     *
     * @return string
     */
    public function getCreateTokenPoolID(): string
    {
        return $this->_actionCreateTokenPoolID;
    }

    /**
     * Revoie l'instance du nouveau sac de jetons.
     *
     * @return TokenPool
     */
    public function getCreateTokenPoolInstance(): TokenPool
    {
        return $this->_actionCreateTokenPoolInstance;
    }

    /**
     * Revoie les paramètres du nouveau sac de jetons.
     *
     * @return array
     */
    public function getCreateTokenPoolParam(): array
    {
        return $this->_actionCreateTokenPoolParam;
    }

    /**
     * Revoie le code erreur de création du nouveau sac de jetons.
     *
     * @return boolean
     */
    public function getCreateTokenPoolError(): bool
    {
        return $this->_actionCreateTokenPoolError;
    }

    /**
     * Revoie le message d'erreur de création du nouveau sac de jetons.
     *
     * @return string
     */
    public function getCreateTokenPoolErrorMessage(): string
    {
        return $this->_actionCreateTokenPoolErrorMessage;
    }

    /**
     * Extrait pour action si un sac de jetons doit être créé.
     *
     * @return void
     */
    protected function _extractActionCreateTokenPool()
    {
        if (!$this->_checkPermitAction('CreateTokenPool'))
            return;

        $this->_metrologyInstance->addLog('Extract action create token pool', Metrology::LOG_LEVEL_DEBUG);

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_TOKEN_POOL);

        if ($argCreate !== false)
            $this->_actionCreateTokenPool = true;

        if ($this->_actionCreateTokenPool) {
            // Récupère la liste des propriétés.
            $instance = $this->_nebuleInstance->getCurrentTokenPoolInstance();
            $propertiesList = $instance->getPropertiesList();
            unset($instance);

            foreach ($propertiesList['tokenpool'] as $name => $property) {
                // Extrait une valeur.
                if (isset($property['checkbox'])) {
                    $value = '';
                    $valueArray = filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    foreach ($valueArray as $item)
                        $value .= ' ' . trim($item);
                    $this->_actionCreateTokenPoolParam[$name] = trim($value);
                    unset($value, $valueArray);
                } else
                    $this->_actionCreateTokenPoolParam[$name] = trim(filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
                $this->_metrologyInstance->addLog('Extract action create token pool - p' . $property['key'] . ':' . $this->_actionCreateTokenPoolParam[$name], Metrology::LOG_LEVEL_DEBUG);

                // Extrait si forcé.
                if (isset($property['forceable'])) {
                    $this->_actionCreateTokenPoolParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                    if ($this->_actionCreateTokenPoolParam['Force' . $name])
                        $this->_metrologyInstance->addLog('Extract action create token pool - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG);
                }
            }
        }
    }

    protected $_actionCreateTokenPool = false;
    protected $_actionCreateTokenPoolID = '';
    protected $_actionCreateTokenPoolInstance = null;
    protected $_actionCreateTokenPoolParam = array();
    protected $_actionCreateTokenPoolError = false;
    protected $_actionCreateTokenPoolErrorMessage = 'Initialisation de la création.';


    /**
     * Renvoie si l'action de création des jetons a été faite.
     *
     * @return boolean
     */
    public function getCreateTokens(): bool
    {
        return $this->_actionCreateTokens;
    }

    /**
     * Revoie les ID des nouveaux jetons.
     *
     * @return array:string
     */
    public function getCreateTokensID(): array
    {
        return $this->_actionCreateTokensID;
    }

    /**
     * Revoie les instances des nouveaux jetons.
     *
     * @return array:Token
     */
    public function getCreateTokensInstance(): array
    {
        return $this->_actionCreateTokensInstance;
    }

    /**
     * Revoie les paramètres des nouveaux jetons.
     *
     * @return array
     */
    public function getCreateTokensParam(): array
    {
        return $this->_actionCreateTokensParam;
    }

    /**
     * Revoie le code erreur de création des nouveaux jetons.
     *
     * @return boolean
     */
    public function getCreateTokensError(): bool
    {
        return $this->_actionCreateTokensError;
    }

    /**
     * Revoie le message d'erreur de création des nouveaux jetons.
     *
     * @return string
     */
    public function getCreateTokensErrorMessage(): string
    {
        return $this->_actionCreateTokensErrorMessage;
    }

    /**
     * Extrait pour action si des jetons doivent être créés.
     *
     * @return void
     */
    protected function _extractActionCreateTokens()
    {
        if (!$this->_checkPermitAction('CreateTokens'))
            return;

        $this->_metrologyInstance->addLog('Extract action create tokens', Metrology::LOG_LEVEL_DEBUG);

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_TOKENS);

        if ($argCreate !== false)
            $this->_actionCreateTokens = true;

        if ($this->_actionCreateTokens) {
            // Récupère la liste des propriétés.
            $instance = $this->_tokenizingInstance->getCurrentTokenInstance();
            $propertiesList = $instance->getPropertiesList();
            unset($instance);

            foreach ($propertiesList['token'] as $name => $property) {
                // Extrait une valeur.
                if (isset($property['checkbox'])) {
                    $value = '';
                    $valueArray = filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    foreach ($valueArray as $item)
                        $value .= ' ' . trim($item);
                    $this->_actionCreateTokensParam[$name] = trim($value);
                    unset($value, $valueArray);
                } else
                    $this->_actionCreateTokensParam[$name] = trim(filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
                $this->_metrologyInstance->addLog('Extract action create tokens - t' . $property['key'] . ':' . $this->_actionCreateTokensParam[$name], Metrology::LOG_LEVEL_DEBUG);

                // Extrait si forcé.
                if (isset($property['forceable'])) {
                    $this->_actionCreateTokensParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                    if ($this->_actionCreateTokensParam['Force' . $name])
                        $this->_metrologyInstance->addLog('Extract action create tokens - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG);
                }
            }

            // Extrait le nombre de jetons à générer.
            $this->_actionCreateTokensCount = (int)trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_TOKENS_COUNT, FILTER_SANITIZE_NUMBER_INT));
        }
    }

    protected $_actionCreateTokens = false;
    protected $_actionCreateTokensID = array();
    protected $_actionCreateTokensInstance = array();
    protected $_actionCreateTokensParam = array();
    protected $_actionCreateTokensCount = 1;
    protected $_actionCreateTokensError = false;
    protected $_actionCreateTokensErrorMessage = 'Initialisation de la création.';


    /**
     * Signe un lien.
     * @param ?Link $link
     * @param boolean $obfuscate
     * @return void
     * FIXME
     *
     */
    protected function _actionSignLink(?Link $link, $obfuscate = 'default')
    {
        if ($this->_unlocked) {
            $this->_metrologyInstance->addLog('Action sign link', Metrology::LOG_LEVEL_DEBUG);

            // On cache le lien ?
            if ($obfuscate !== false
                && $obfuscate !== true
            )
                $obfuscate = $this->_configurationInstance->getOptionUntyped('defaultObfuscateLinks');
            //...

            // Signature du lien.
            $link->signWrite();
        } elseif ($this->_getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
            || $this->_getOptionAsBoolean('permitPublicUploadLink')
        ) {
            $this->_metrologyInstance->addLog('Action sign link', Metrology::LOG_LEVEL_DEBUG);

            // Affichage du lien.
            echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_ADDLNK') . $this->_displayInstance->convertInlineLinkFace($link);

            // Si signé, écriture du lien.
            if ($link->getSigned())
                $link->write();
        }
        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Ecrit un lien pré-signé.
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
     * @param Link $link
     * @return void
     */
    protected function _actionUploadLink(Link $link)
    {
        if (!is_a($link, 'Nebule\Library\Link')
            || !$link->getValid()
            || true // FIXME
        )
            return;
        
        $this->_metrologyInstance->addLog('Action upload link', Metrology::LOG_LEVEL_DEBUG);

        if ($link->getSigned()
            && (($link->getSigners() == $this->_authoritiesInstance->getCodeAuthoritiesEID()
                    && $this->_getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                )
                || $this->_getOptionAsBoolean('permitPublicUploadLink')
                || $this->_unlocked
            )
        ) {
            $link->write();
            $this->_metrologyInstance->addLog('Action upload link - signed link ' . $link->getRaw(), Metrology::LOG_LEVEL_NORMAL);
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
            $this->_metrologyInstance->addLog('Action upload link - unsigned link ' . $link->getRaw(), Metrology::LOG_LEVEL_NORMAL);
        }

        // Affichage des actions.
        $this->_displayInstance->displayInlineLastAction();
    }


    /**
     * Dissimule un lien.
     *
     * @return void
     */
    protected function _actionObfuscateLink()
    {
        if (!$this->_actionObfuscateLinkInstance->getValid()
            || !$this->_actionObfuscateLinkInstance->getSigned() // FIXME
        )
            return;

        $this->_metrologyInstance->addLog('Action obfuscate link', Metrology::LOG_LEVEL_DEBUG);

        // On dissimule le lien.
        $this->_actionObfuscateLinkInstance->obfuscateWrite();

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Supprime un objet.
     */
    protected function _actionDeleteObject()
    {
        $this->_metrologyInstance->addLog('Action delete object', Metrology::LOG_LEVEL_DEBUG);

        // Suppression de l'objet.
        if ($this->_actionDeleteObjectForce)
            $this->_actionDeleteObjectInstance->deleteForceObject();
        else
            $this->_actionDeleteObjectInstance->deleteObject();

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Protège un objet.
     */
    protected function _actionProtectObject()
    {
        $this->_metrologyInstance->addLog('Action protect object', Metrology::LOG_LEVEL_DEBUG);

        // Demande de protection de l'objet.
        $this->_actionProtectObjectInstance->setProtected();

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Déprotège un objet.
     */
    protected function _actionUnprotectObject()
    {
        $this->_metrologyInstance->addLog('Action unprotect object', Metrology::LOG_LEVEL_DEBUG);

        // Demande de protection de l'objet.
        $this->_actionUnprotectObjectInstance->setUnprotected();

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }

    /**
     * Partage la protection d'un objet pour une entité.
     */
    protected function _actionShareProtectObjectToEntity()
    {
        $this->_metrologyInstance->addLog('Action share protect object to entity ' . $this->_actionShareProtectObjectToEntity, Metrology::LOG_LEVEL_DEBUG);

        // Demande de protection de l'objet.
        $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($this->_actionShareProtectObjectToEntity);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }

    /**
     * Partage la protection d'un objet pour un groupe ouvert.
     */
    protected function _actionShareProtectObjectToGroupOpened()
    {
        $this->_metrologyInstance->addLog('Action share protect object to opened group', Metrology::LOG_LEVEL_DEBUG);

        // Demande de protection de l'objet.
        $group = $this->_cacheInstance->newGroup($this->_actionShareProtectObjectToGroupOpened);
        foreach ($group->getListMembersID('myself', null) as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }

    /**
     * Partage la protection d'un objet pour un groupe fermé.
     */
    protected function _actionShareProtectObjectToGroupClosed()
    {
        $this->_metrologyInstance->addLog('Action share protect object to closed group', Metrology::LOG_LEVEL_DEBUG);

        // Demande de protection de l'objet.
        $group = $this->_cacheInstance->newGroup($this->_actionShareProtectObjectToGroupClosed);
        foreach ($group->getListMembersID('myself', null) as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }

    /**
     * Annule le partage de la protection d'un objet pour une entité.
     */
    protected function _actionCancelShareProtectObjectToEntity()
    {
        $this->_metrologyInstance->addLog('Action cancel share protect object to entity', Metrology::LOG_LEVEL_DEBUG);

        // Demande d'annulation de protection de l'objet.
        $this->_nebuleInstance->getCurrentObjectInstance()->cancelShareProtectionTo($this->_actionCancelShareProtectObjectToEntity);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Synchronise l'objet.
     */
    protected function _actionSynchronizeObject()
    {
        $this->_metrologyInstance->addLog('Action synchronize object', Metrology::LOG_LEVEL_DEBUG);

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNOBJ') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeObjectInstance);

        // Synchronisation.
        $this->_actionSynchronizeObjectInstance->syncObject();

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Synchronise l'entité.
     */
    protected function _actionSynchronizeEntity()
    {
        $this->_metrologyInstance->addLog('Action synchronize entity', Metrology::LOG_LEVEL_DEBUG);

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

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Synchronise les liens de l'objet.
     */
    protected function _actionSynchronizeObjectLinks()
    {
        $this->_metrologyInstance->addLog('Action synchronize object links', Metrology::LOG_LEVEL_DEBUG);

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNLNK') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeObjectLinksInstance);

        // Synchronisation.
        $this->_actionSynchronizeObjectLinksInstance->syncLinks();

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Synchronise l'application. A revoir complètement ce qui est sync TODO
     */
    protected function _actionSynchronizeApplication(): void
    {
        $this->_metrologyInstance->addLog('Action synchronize application', Metrology::LOG_LEVEL_DEBUG);

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

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Synchronise une entité externe depuis une URL.
     */
    protected function _actionSynchronizeNewEntity(): void
    {
        $this->_metrologyInstance->addLog('Action synchronize new entity', Metrology::LOG_LEVEL_DEBUG);

        // Vérifie si l'objet est déjà présent.
        $present = $this->_ioInstance->checkObjectPresent($this->_actionSynchronizeNewEntityID);
        // Lecture de l'objet.
        $data = $this->_ioInstance->getObject($this->_actionSynchronizeNewEntityID, Entity::ENTITY_MAX_SIZE, $this->_actionSynchronizeNewEntityURL);
        // Calcul de l'empreinte.
        $hash = $this->_nebuleInstance->getCryptoInstance()->hash($data);
        if ($hash != $this->_actionSynchronizeNewEntityID) {
            $this->_metrologyInstance->addLog('Action synchronize new entity - Hash error', Metrology::LOG_LEVEL_DEBUG);
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
            $this->_metrologyInstance->addLog('Action synchronize new entity - Not entity', Metrology::LOG_LEVEL_DEBUG);
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

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Marque un objet.
     */
    protected function _actionMarkObject()
    {
        $this->_metrologyInstance->addLog('Action mark object', Metrology::LOG_LEVEL_DEBUG);

        $this->_applicationInstance->setMarkObject($this->_actionMarkObject);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Supprime la marque d'un objet.
     */
    protected function _actionUnmarkObject()
    {
        $this->_metrologyInstance->addLog('Action unmark object', Metrology::LOG_LEVEL_DEBUG);

        $this->_applicationInstance->setUnmarkObject($this->_actionUnmarkObject);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Supprime les marques de tous les objets.
     */
    protected function _actionUnmarkAllObjects()
    {
        $this->_metrologyInstance->addLog('Action unmark all objects', Metrology::LOG_LEVEL_DEBUG);

        $this->_applicationInstance->setUnmarkAllObjects();

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Transfert un fichier et le nebulise.
     *
     * @return void
     */
    protected function _actionUploadFile(): void
    {
        $this->_metrologyInstance->addLog('Action upload file', Metrology::LOG_LEVEL_DEBUG);

        // Lit le contenu du fichier.
        $data = file_get_contents($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['tmp_name']);

        // Ecrit le contenu dans l'objet.
        $instance = new Node($this->_nebuleInstance, '0', $data, $this->_actionUploadFileProtect);
        if ($instance === false) {
            $this->_metrologyInstance->addLog('Action _actionUploadFile cant create object instance', Metrology::LOG_LEVEL_ERROR);
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
            return;
        }

        // Lit l'ID.
        $id = $instance->getID();
        unset($data);
        if ($id == '0') {
            $this->_metrologyInstance->addLog('Action _actionUploadFile cant create object', Metrology::LOG_LEVEL_ERROR);
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
            $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionUploadFileObfuscateLinks);
        }

        unset($date, $signer, $source, $target, $meta, $link, $newLink, $textID, $instance);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Transfert un texte et le nebulise.
     */
    protected function _actionUploadText(): void
    {
        $this->_metrologyInstance->addLog('Action upload text', Metrology::LOG_LEVEL_DEBUG);

        // Crée l'instance de l'objet.
        $instance = new Node($this->_nebuleInstance, '0', $this->_actionUploadTextContent, $this->_actionUploadTextProtect);
        if ($instance === false) {
            $this->_metrologyInstance->addLog('Action _actionUploadText cant create object instance', Metrology::LOG_LEVEL_ERROR);
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
            return;
        }

        // Lit l'ID.
        $id = $instance->getID();
        if ($id == '0') {
            $this->_metrologyInstance->addLog('Action _actionUploadText cant create object', Metrology::LOG_LEVEL_ERROR);
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'objet n'a pas pu être créé.";
            return;
        }
        $this->_actionUploadTextID = $id;

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();

        // Définition de la date et du signataire.
        //$signer	= $this->_nebuleInstance->getCurrentEntity();
        //$date = date(DATE_ATOM);

        // Création du type mime.
        $instance->setType($this->_actionUploadTextType);

        // Crée l'objet du nom.
        $instance->setName($this->_actionUploadTextName);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();

        unset($id, $instance);
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
    protected function _actionUploadFileLinks()
    {
        $this->_metrologyInstance->addLog('Action upload file signed links', Metrology::LOG_LEVEL_DEBUG);

        // Ecrit les liens correctement signés.
        $updata = file($this->_actionUploadFileLinksPath);
        $nbLinks = 0;
        $nbLines = 0;
        foreach ($updata as $line) {
            if (substr($line, 0, 21) != 'nebule/liens/version/') {
                $nbLines++;
                $instance = $this->_cacheInstance->newBlockLink($line);
                if ($instance->getValid()) {
                    if ($instance->getSigned()
                        && (($instance->getSigners() == $this->_authoritiesInstance->getCodeAuthoritiesEID()
                                && $this->_getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                            )
                            || $this->_getOptionAsBoolean('permitPublicUploadLink')
                            || $this->_unlocked
                        )
                    ) {
                        $instance->write();
                        $nbLinks++;
                        $this->_metrologyInstance->addLog('Action upload file links - signed link ' . $instance->getRaw(), Metrology::LOG_LEVEL_NORMAL);
                    } elseif ($this->_unlocked) {
                        $instance = $this->_cacheInstance->newBlockLink(
                            '0_'
                            . $this->_entitiesInstance->getCurrentEntityID() . '_'
                            . $instance->getDate() . '_'
                            . $instance->getParsed()['bl/rl/req'] . '_'
                            . $instance->getParsed()['bl/rl/nid1'] . '_'
                            . $instance->getParsed()['bl/rl/nid2'] . '_'
                            . $instance->getParsed()['bl/rl/nid3']
                        );
                        $instance->signWrite();
                        $nbLinks++;
                        $this->_metrologyInstance->addLog('Action upload file links - unsigned link ' . $instance->getRaw(), Metrology::LOG_LEVEL_NORMAL);
                    }
                }
            }
        }

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
        echo "\n<br />\nRead=$nbLines Valid=$nbLinks\n";
    }


    /**
     * Crée une nouvelle entité.
     *
     * @return void
     */
    protected function _actionCreateEntity()
    {
        $this->_metrologyInstance->addLog('Action create entity', Metrology::LOG_LEVEL_DEBUG);

        // Création de la nouvelle entité nebule.
        $instance = new Entity($this->_nebuleInstance, 'new');

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();

        if (is_a($instance, 'Nebule\Library\Entity') && $instance->getID() != '0') {
            $this->_actionCreateEntityError = false;

            // Enregistre l'instance créée.
            $this->_actionCreateEntityInstance = $instance;
            $this->_actionCreateEntityID = $instance->getID();
            unset($instance);

            // Modifie le mot de passe de clé privée.
            $this->_actionCreateEntityInstance->changePrivateKeyPassword($this->_actionCreateEntityPassword);

            // Affichage des actions.
            $this->_displayInstance->displayInlineAllActions();

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
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
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
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
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
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                }
            }

            // Affichage des actions.
            $this->_displayInstance->displayInlineAllActions();

            if ($this->_actionCreateEntityPrefix != '') {
                // Crée l'objet avec le texte.
                $textID = $this->createTextAsObject($this->_actionCreateEntityPrefix);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $this->_actionCreateEntityID;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/prefix');
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
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
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
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
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                }
            }

            unset($date, $source, $target, $meta, $link, $newLink, $textID);

            // Restaure l'entité d'origine.
            $this->_entitiesInstance->unsetTempCurrentEntity();

            // Efface le cache pour recharger l'entité.
            $this->_nebuleInstance->getCacheInstance()->unsetCacheEntity($this->_actionCreateEntityID);

            // Recrée l'instance de l'objet.
            $this->_actionCreateEntityInstance = $this->_cacheInstance->newEntity($this->_actionCreateEntityID);
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('Action _actionCreateEntity cant generate', Metrology::LOG_LEVEL_ERROR);
            $this->_actionCreateEntityError = true;
            $this->_actionCreateEntityErrorMessage = 'Echec de la génération.';
        }

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Crée un nouveau groupe.
     */
    protected function _actionCreateGroup()
    {
        $this->_metrologyInstance->addLog('Action create group', Metrology::LOG_LEVEL_DEBUG);

        // Création du nouveau groupe.
        $instance = new Group($this->_nebuleInstance, 'new', $this->_actionCreateGroupClosed);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();

        if (is_a($instance, 'Nebule\Library\Group') && $instance->getID() != '0') {
            $this->_actionCreateGroupError = false;
            $instance->setName($this->_actionCreateGroupName);

            $this->_actionCreateGroupInstance = $instance;
            $this->_actionCreateGroupID = $instance->getID();
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('Action _actionCreateGroup cant generate', Metrology::LOG_LEVEL_ERROR);
            $this->_actionCreateGroupError = true;
            $this->_actionCreateGroupErrorMessage = 'Echec de la génération.';
        }

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Supprime un groupe.
     */
    protected function _actionDeleteGroup()
    {
        $this->_metrologyInstance->addLog('Action delete group ' . $this->_actionDeleteGroupID, Metrology::LOG_LEVEL_DEBUG);

        /**
         * Instance du groupe.
         * @var Group $instance
         */
        $instance = $this->_cacheInstance->newGroup($this->_actionDeleteGroupID);

        // Vérification.
        if ($instance->getID() == '0'
            || !$instance->getIsGroup('all')
        ) {
            $this->_actionDeleteGroupError = false;
            $this->_actionDeleteGroupErrorMessage = 'Pas un groupe.';
            $this->_metrologyInstance->addLog('Action delete not a group', Metrology::LOG_LEVEL_DEBUG);
            return;
        }

        // Suppression.
        if ($instance->getMarkClosed()) {
            $this->_metrologyInstance->addLog('Action delete group closed', Metrology::LOG_LEVEL_DEBUG);
            $instance->unsetMarkClosed();
        }
        $instance->unsetGroup();

        // Vérification.
        if ($instance->getIsGroup('myself')) {
            // Si ce n'est pas bon.
            $this->_metrologyInstance->addLog('Action _actionDeleteGroup cant generate', Metrology::LOG_LEVEL_ERROR);
            $this->_actionDeleteGroupError = true;
            $this->_actionDeleteGroupErrorMessage = 'Echec de la génération.';
        }
        unset($instance);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Ajoute l'objet courant à un groupe.
     *
     * @return void
     */
    protected function _actionAddToGroup()
    {
        $this->_metrologyInstance->addLog('Action add to group ' . $this->_actionAddToGroup, Metrology::LOG_LEVEL_DEBUG);
        $instanceGroupe = $this->_cacheInstance->newGroup($this->_actionAddToGroup);
        $instanceGroupe->setMember($this->_nebuleInstance->getCurrentObjectInstance());
        unset($instanceGroupe);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Retire l'objet courant d'un groupe.
     *
     * @return void
     */
    protected function _actionRemoveFromGroup()
    {
        $this->_metrologyInstance->addLog('Action remove from group ' . $this->_actionRemoveFromGroup, Metrology::LOG_LEVEL_DEBUG);
        $instanceGroupe = $this->_cacheInstance->newGroup($this->_actionRemoveFromGroup);
        $instanceGroupe->unsetMember($this->_nebuleInstance->getCurrentObjectInstance());
        unset($instanceGroupe);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Ajoute un objet au groupe courant.
     */
    protected function _actionAddItemToGroup()
    {
        $this->_metrologyInstance->addLog('Action add item to group ' . $this->_actionAddItemToGroup, Metrology::LOG_LEVEL_DEBUG);
        $this->_nebuleInstance->getCurrentGroupInstance()->setMember($this->_actionAddItemToGroup);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Retire un objet courant du groupe courant.
     */
    protected function _actionRemoveItemFromGroup()
    {
        $this->_metrologyInstance->addLog('Action remove item from group ' . $this->_actionRemoveItemFromGroup, Metrology::LOG_LEVEL_DEBUG);
        $this->_nebuleInstance->getCurrentGroupInstance()->unsetMember($this->_actionRemoveItemFromGroup);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Crée une nouvelle conversation.
     */
    protected function _actionCreateConversation()
    {
        $this->_metrologyInstance->addLog('Action create conversation', Metrology::LOG_LEVEL_DEBUG);

        // Création de la nouvelle conversation.
        $instance = new Conversation(
            $this->_nebuleInstance,
            'new',
            $this->_actionCreateConversationClosed,
            $this->_actionCreateConversationProtected,
            $this->_actionCreateConversationObfuscateLinks);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();

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
            $this->_metrologyInstance->addLog('Action _actionCreateConversation cant generate', Metrology::LOG_LEVEL_ERROR);
            $this->_actionCreateConversationError = true;
            $this->_actionCreateConversationErrorMessage = 'Echec de la génération.';
        }

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Supprime un conversation.
     *
     * On fait d'abort la suppression de la conversation fermée puis si rappelé on fait la suppression de la conversation ouvert.
     * On ne fait pas les deux en même temps.
     */
    protected function _actionDeleteConversation()
    {
        $this->_metrologyInstance->addLog('Action delete conversation ' . $this->_actionDeleteConversationID, Metrology::LOG_LEVEL_DEBUG);

        // Suppression.
        $instance = $this->_cacheInstance->newConversation($this->_actionDeleteConversationID);
        if (!is_a($instance, 'Nebule\Library\Conversation')
            || $instance->getID() == '0'
            || !$instance->getIsConversation('myself')
        ) {
            $this->_actionDeleteConversationError = false;
            $this->_actionDeleteConversationErrorMessage = 'Pas un conversation.';
            $this->_metrologyInstance->addLog('Action delete not a group', Metrology::LOG_LEVEL_DEBUG);
            return;
        }
        if ($instance->getIsConversationClosed()) {
            $this->_metrologyInstance->addLog('Action delete conversation closed', Metrology::LOG_LEVEL_DEBUG);
            $instance->setUnmarkConversationClosed();
            $test = $instance->getIsConversationClosed();
        } else {
            $this->_metrologyInstance->addLog('Action delete conversation', Metrology::LOG_LEVEL_DEBUG);
            $instance->setUnmarkConversationOpened();
            $test = $instance->getIsConversationOpened();
        }

        // Vérification.
        if ($test) {
            // Si ce n'est pas bon.
            $this->_metrologyInstance->addLog('Action _actionDeleteConversation cant generate', Metrology::LOG_LEVEL_ERROR);
            $this->_actionDeleteConversationError = true;
            $this->_actionDeleteConversationErrorMessage = 'Echec de la génération.';
        }
        unset($instance, $test);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Ajoute l'objet courant à un conversation.
     */
    protected function _actionAddMessageOnConversation()
    {
        $this->_metrologyInstance->addLog('Action add message to conversation ' . $this->_actionAddMessageOnConversation, Metrology::LOG_LEVEL_DEBUG);

        $instanceConversation = $this->_cacheInstance->newConversation($this->_actionAddMessageOnConversation);
        $instanceConversation->setMember($this->_nebuleInstance->getCurrentObject(), false);
        unset($instanceConversation);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Retire l'objet courant d'un conversation.
     */
    protected function _actionRemoveMessageOnConversation()
    {
        $this->_metrologyInstance->addLog('Action remove message to conversation ' . $this->_actionRemoveMessageOnConversation, Metrology::LOG_LEVEL_DEBUG);

        $instanceConversation = $this->_cacheInstance->newConversation($this->_actionRemoveMessageOnConversation);
        $instanceConversation->unsetMember($this->_nebuleInstance->getCurrentObject());
        unset($instanceConversation);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Ajoute une entité à la conversation courante.
     */
    protected function _actionAddMemberOnConversation()
    {
        $this->_metrologyInstance->addLog('Action add member to conversation ' . $this->_actionAddMemberOnConversation, Metrology::LOG_LEVEL_DEBUG);

        $instanceConversation = $this->_cacheInstance->newConversation($this->_actionAddMemberOnConversation);
        $instanceConversation->setFollower($this->_nebuleInstance->getCurrentObject());
        unset($instanceConversation);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Retire une entité de la conversation courante.
     */
    protected function _actionRemoveMemberOnConversation()
    {
        $this->_metrologyInstance->addLog('Action remove member to conversation ' . $this->_actionRemoveMemberOnConversation, Metrology::LOG_LEVEL_DEBUG);

        $instanceConversation = $this->_cacheInstance->newConversation($this->_actionRemoveMemberOnConversation);
        $instanceConversation->unsetFollower($this->_nebuleInstance->getCurrentObject());
        unset($instanceConversation);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Crée un nouveau message.
     * Se fait à partir d'un texte précédemment chargé via les fonctions uploadText.
     */
    protected function _actionCreateMessage()
    {
        $id = $this->_actionUploadTextID;
        $this->_metrologyInstance->addLog('Action create message ' . $id, Metrology::LOG_LEVEL_DEBUG);
        if ($this->_actionCreateMessageProtected) {
            $this->_metrologyInstance->addLog('Action create message protected', Metrology::LOG_LEVEL_DEBUG);
        }
        if ($this->_actionCreateMessageObfuscateLinks) {
            $this->_metrologyInstance->addLog('Action create message with obfuscated links', Metrology::LOG_LEVEL_DEBUG);
        }

        // Création de l'instance du message.
        $instanceMessage = $this->_cacheInstance->newNode($id);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();

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
            $this->_metrologyInstance->addLog('Action _actionCreateMessage cant generate', Metrology::LOG_LEVEL_ERROR);
            $this->_actionCreateMessageError = true;
            $this->_actionCreateMessageErrorMessage = 'Echec de la génération.';
        }

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Crée une nouvelle propriété pour un objet.
     */
    protected function _actionAddProperty()
    {
        $prop = $this->_actionAddProperty;
        $propID = $this->_nebuleInstance->getCryptoInstance()->hash($prop);
        $this->_metrologyInstance->addLog('Action add property ' . $prop, Metrology::LOG_LEVEL_DEBUG);
        $objectID = $this->_actionAddPropertyObject;
        $this->_metrologyInstance->addLog('Action add property for ' . $objectID, Metrology::LOG_LEVEL_DEBUG);
        $value = $this->_actionAddPropertyValue;
        $valueID = $this->_nebuleInstance->getCryptoInstance()->hash($value);
        $this->_metrologyInstance->addLog('Action add property value : ' . $value, Metrology::LOG_LEVEL_DEBUG);
        $protected = $this->_actionAddPropertyProtected;
        if ($protected) {
            $this->_metrologyInstance->addLog('Action add property protected', Metrology::LOG_LEVEL_DEBUG);
        }
        if ($this->_actionAddPropertyObfuscateLinks) {
            $this->_metrologyInstance->addLog('Action add property with obfuscated links', Metrology::LOG_LEVEL_DEBUG);
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
        $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionAddPropertyObfuscateLinks);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Crée une nouvelle monnaie.
     */
    protected function _actionCreateCurrency()
    {
        $this->_metrologyInstance->addLog('Action create currency', Metrology::LOG_LEVEL_DEBUG);

        // Création de la nouvelle monnaie.
        $instance = new Currency($this->_nebuleInstance, 'new', $this->_actionCreateCurrencyParam, false, false);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();

        if (is_a($instance, 'Nebule\Library\Currency')
            && $instance->getID() != '0'
        ) {
            $this->_actionCreateCurrencyError = false;

            $this->_actionCreateCurrencyInstance = $instance;
            $this->_actionCreateCurrencyID = $instance->getID();

            $this->_metrologyInstance->addLog('Action _actionCreateCurrency generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_DEBUG);
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('Action _actionCreateCurrency cant generate', Metrology::LOG_LEVEL_ERROR);
            $this->_actionCreateCurrencyError = true;
            $this->_actionCreateCurrencyErrorMessage = 'Echec de la génération.';
        }

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Crée un nouveau sac de jetons.
     */
    protected function _actionCreateTokenPool()
    {
        $this->_metrologyInstance->addLog('Action create token pool', Metrology::LOG_LEVEL_DEBUG);

        // Création du nouveau sac de jetons.
        $instance = new TokenPool($this->_nebuleInstance, 'new', $this->_actionCreateTokenPoolParam, false, false);

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();

        if (is_a($instance, 'Nebule\Library\TokenPool')
            && $instance->getID() != '0'
        ) {
            $this->_actionCreateTokenPoolError = false;

            $this->_actionCreateTokenPoolInstance = $instance;
            $this->_actionCreateTokenPoolID = $instance->getID();

            $this->_metrologyInstance->addLog('Action _actionCreateTokenPool generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_DEBUG);
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('Action _actionCreateTokenPool cant generate', Metrology::LOG_LEVEL_ERROR);
            $this->_actionCreateTokenPoolError = true;
            $this->_actionCreateTokenPoolErrorMessage = 'Echec de la génération.';
        }

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
    }


    /**
     * Crée un nouveau sac de jetons.
     */
    protected function _actionCreateTokens()
    {
        $this->_metrologyInstance->addLog('Action create tokens', Metrology::LOG_LEVEL_DEBUG);

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

                $this->_metrologyInstance->addLog('Action _actionCreateTokens generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_DEBUG);
            } else {
                // Si ce n'est pas bon.
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrologyInstance->addLog('Action _actionCreateTokens cant generate', Metrology::LOG_LEVEL_ERROR);
                $this->_actionCreateTokensError = true;
                $this->_actionCreateTokensErrorMessage = 'Echec de la génération.';

                // Quitte le processus de génération.
                break;
            }
        }

        // Affichage des actions.
        $this->_displayInstance->displayInlineAllActions();
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
    protected function _createLink($signer, $date, $action, $source, $target, $meta, $obfuscate = false): bool
    {
        return false; // FIXME

        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);

        // Signe le lien.
        $newLink->sign($signer);

        // Si besoin, obfuscation du lien.
        if ($obfuscate
            && $this->_getOptionAsBoolean('permitObfuscatedLink')
        ) {
            $newLink->setObfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
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
    public function flatLinkExtractAsArray_disabled(string $link): array
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

    /**
     * Extrait et analyse un lien.
     *
     * @param string $link : lien à extraire.
     * @return ?Link : une instance de lien.
     *
     * Accepte une chaine de caractère représentant un lien.
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
    public function flatLinkExtractAsInstance_disabled(string $link): ?Link
    {
        return null; // FIXME
        // Vérifier compatibilité avec liens incomplets...

        // Extrait le lien.
        $linkArray = $this->flatLinkExtractAsArray_disabled($link);

        // Création du lien.
        $flatLink = $linkArray[0] . '_' . $linkArray[1] . '_' . $linkArray[2] . '_' . $linkArray[3] . '_' . $linkArray[4] . '_' . $linkArray[5] . '_' . $linkArray[6];
        $linkInstance = $this->newLink($flatLink);

        unset($linkArray, $flatLink);
        return $linkInstance;
    }
}
