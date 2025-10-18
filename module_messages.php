<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * This module can manage messengers. Messages can be attached to groups (or objects) in a related conversation.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleMessages extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::module:messages:ModuleName';
    const MODULE_MENU_NAME = '::module:messages:MenuName';
    const MODULE_COMMAND_NAME = 'msg';
    const MODULE_DEFAULT_VIEW = 'groups';
    const MODULE_DESCRIPTION = '::module:messages:ModuleDescription';
    const MODULE_VERSION = '020251018';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2025-2025';
    const MODULE_LOGO = '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256';
    const MODULE_HELP = '::module:messages:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('groups', 'list');
    const MODULE_REGISTERED_ICONS = array(
        '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256',    // 0 : World.
    );
    const MODULE_APP_TITLE_LIST = array('::module:messages:AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::module:messages:AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');



    protected function _initialisation(): void {}



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array
    {
        $hookArray = array();

        switch ($hookName) {
            case 'menu':
                $hookArray[0]['name'] = '::module:messages:AppTitle1';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::module:messages:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::MODULE_DEFAULT_VIEW
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;
        }

        return $hookArray;
    }



    public function displayModule(): void
    {
        //switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
        //    case $this::MODULE_REGISTERED_VIEWS[0]:
        //        $this->_displayLangs();
        //        break;
        //    default:
        $this->_displayList();
        //        break;
        //}
    }

    public function displayModuleInline(): void
    {
        //switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
        //    case $this::MODULE_REGISTERED_VIEWS[0]:
        $this->_display_InlineList();
        //        break;
        //}
    }



    private function _displayList(): void
    {
        $this->_displaySimpleTitle('::module:messages:display:Current', $this::MODULE_LOGO);

        $this->_displayNotImplemented(); // TODO

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('list');
    }

    /**
     * Affiche les groupes de l'entité en cours de visualisation, en ligne.
     *
     * @return void
     */
    private function _display_InlineList(): void
    {
        $this->_displaySimpleTitle('::module:messages:display:List', $this::MODULE_LOGO);

        $this->_displayNotImplemented(); // TODO
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::module:messages:ModuleName' => 'Messages module',
            '::module:messages:MenuName' => 'Messages',
            '::module:messages:ModuleDescription' => 'Messages management module.',
            '::module:messages:ModuleHelp' => 'This module permit to see and manage messages.',
            '::module:messages:AppTitle1' => 'Messages',
            '::module:messages:AppDesc1' => 'Manage messages.',
            '::module:messages:display:Current' => 'Selected messages',
            '::module:messages:display:List' => 'List of messagess',
        ],
        'en-en' => [
            '::module:messages:ModuleName' => 'Messages module',
            '::module:messages:MenuName' => 'Messages',
            '::module:messages:ModuleDescription' => 'Messages management module.',
            '::module:messages:ModuleHelp' => 'This module permit to see and manage messages.',
            '::module:messages:AppTitle1' => 'Messages',
            '::module:messages:AppDesc1' => 'Manage messages.',
            '::module:messages:display:Current' => 'Selected messages',
            '::module:messages:display:List' => 'List of messagess',
        ],
        'es-co' => [
            '::module:messages:ModuleName' => 'Messages module',
            '::module:messages:MenuName' => 'Messages',
            '::module:messages:ModuleDescription' => 'Messages management module.',
            '::module:messages:ModuleHelp' => 'This module permit to see and manage messages.',
            '::module:messages:AppTitle1' => 'Messages',
            '::module:messages:AppDesc1' => 'Manage messages.',
            '::module:messages:display:Current' => 'Selected messages',
            '::module:messages:display:List' => 'List of messagess',
        ],
    ];
}



/**
 * Here only old functions from the Actions class. Will be deleted soon...
 */
class archiveActionsMessages {
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
}
