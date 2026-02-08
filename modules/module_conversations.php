<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Library\nebule;
use Nebule\Library\References;
use Nebule\Library\Metrology;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Actions;
use Nebule\Library\Translates;
use Nebule\Library\ModuleInterface;
use Nebule\Library\Module;
use Nebule\Library\ModelModuleHelp;
use Nebule\Library\ModuleTranslates;

/**
 * This module can manage messengers. Messages can be attached to groups (or objects) in a related conversation.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleConversations extends Module {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'msg';
    const MODULE_DEFAULT_VIEW = 'conversations';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260112';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2016-2026';
    const MODULE_LOGO = '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'conversations',
        'conversation',
        'new_conversation',
        'mod_conversation',
        'del_conversation',
        'get_conversation',
        'syn_conversation',
        'rights_conversation',
        'options',
    );
    const MODULE_REGISTERED_ICONS = array(
        Displays::DEFAULT_ICON_LO,
        Displays::DEFAULT_ICON_LSTOBJ,
        Displays::DEFAULT_ICON_ADDOBJ,
        Displays::DEFAULT_ICON_IMODIFY,
        Displays::DEFAULT_ICON_LD,
        Displays::DEFAULT_ICON_HELP,
        Displays::DEFAULT_ICON_LL,
        Displays::DEFAULT_ICON_LX,
        Displays::DEFAULT_ICON_SYNOBJ,
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    const RESTRICTED_TYPE = 'Conversation';
    const RESTRICTED_CONTEXT = '9176d8c8cf0e89ef48f136a494af9b9c385d275c8d48c498d52206d7d4072eb0fb1f.none.272';
    const COMMAND_SELECT_CONVERSATION = 'cvt';
    const COMMAND_SELECT_ITEM = 'cvt';
    const COMMAND_ACTION_GET_CVT_NID = 'actiongetnid';
    const COMMAND_ACTION_GET_CVT_URL = 'actiongeturl';

    protected ?\Nebule\Library\Group $_instanceCurrentConversation = null;



    protected function _initialisation(): void {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_socialClass = $this->getFilterInput(Displays::COMMAND_SOCIAL, FILTER_FLAG_ENCODE_LOW);
        $this->_getCurrentItem(self::COMMAND_SELECT_CONVERSATION, 'Conversation', $this->_instanceCurrentConversation);
        if (! is_a($this->_instanceCurrentConversation, 'Nebule\Library\Node') || $this->_instanceCurrentConversation->getID() == '0')
            $this->_instanceCurrentConversation = null;
        $this->_getCurrentItemFounders($this->_instanceCurrentConversation);
        $this->_getCurrentItemSocialList($this->_instanceCurrentConversation);
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $instance = null):array {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->_applicationInstance->getCurrentObjectID();
        if ($instance !== null)
            $nid = $instance->getID();
        $hookArray = $this->getCommonHookList($hookName, $nid, 'Conversations', 'Conversation');

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuConversations':
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[] = array(
                        'name' => '::rights',
                        'icon' => Displays::DEFAULT_ICON_IMODIFY,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                            . '&' . self::COMMAND_SELECT_CONVERSATION . '=' . $this->_instanceCurrentConversation->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                    if ($this->_unlocked) {
                        $hookArray[] = array(
                            'name' => '::modifyConversation',
                            'icon' => Displays::DEFAULT_ICON_IMODIFY,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                                . '&' . self::COMMAND_SELECT_CONVERSATION . '=' . $this->_instanceCurrentConversation->getID()
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                    }
                }
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[8]
                    && $this->_unlocked) {
                    $hookArray[] = array(
                        'name' => '::removeConversation',
                        'icon' => Displays::DEFAULT_ICON_LX,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                            . '&' . self::COMMAND_SELECT_CONVERSATION . '=' . $this->_instanceCurrentConversation->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                break;

            /*case 'typeMenuEntity':
                $hookArray[] = array(
                    'name' => '::myConversations',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . Displays::COMMAND_SOCIAL . '=myself'
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;*/

        }
        return $hookArray;
    }



    public function displayModule(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayItem('Conversation');
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayItemCreateForm('Conversation');
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModifyItem('Conversation');
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayRemoveItem('Conversation');
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGetItem('Conversation', 'Conversations', $this::COMMAND_ACTION_GET_CVT_NID, $this::COMMAND_ACTION_GET_CVT_URL);
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displaySynchroItem('Conversation', $this::COMMAND_ACTION_GET_CVT_NID);
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_displayRightsItem('Conversation');
                break;
            case $this::MODULE_REGISTERED_VIEWS[8]:
                $this->_displayOptions();
                break;
            default:
                $this->_displayListItems('Conversation', 'Conversations');
                break;
        }
    }

    public function displayModuleInline(): void {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyItems('Conversations');
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineConversation();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlineRightsItem('Conversation');
                break;
        }
    }



    protected function _display_InlineConversation(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!is_a($this->_instanceCurrentItem, 'Nebule\Library\Group')) {
            $this->_displayNotSupported();
            return;
        }

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        if ($this->_instanceCurrentItem->getMarkClosed())
            $memberLinks = $this->_instanceCurrentItem->getListTypedMembersLinks(References::REFERENCE_NEBULE_OBJET_GROUPE, 'myself');
        else
            $memberLinks = $this->_instanceCurrentItem->getListTypedMembersLinks(References::REFERENCE_NEBULE_OBJET_GROUPE, 'all');


        $this->_displayNotImplemented(); // TODO
    }

    protected function _displayOptions(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented();
    }



    // Called by Modules::_display_InlineMyItems()
    protected function _displayListOfItems(array $links, string $socialClass = 'all', string $hookName = ''): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $conversationsNID = array();
        $conversationsSigners = array();
        foreach ($links as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            if (!$this->_filterItemByType($nid))
                continue;
            $signers = $link->getSignersEID(); // FIXME get all signers
            $conversationsNID[$nid] = $nid;
            foreach ($signers as $signer) {
                $conversationsSigners[$nid][$signer] = $signer;
            }
        }
        $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[0]);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($conversationsNID as $nid) {
            $instanceConversation = $this->_cacheInstance->newGroup($nid);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial($socialClass);
            $instance->setNID($instanceConversation);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_CONVERSATION . '=' . $nid
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            //$instance->setName($instanceConversation->getName('all'));
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableFlagUnlocked(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            if (isset($conversationsSigners[$nid]) && sizeof($conversationsSigners[$nid]) > 0) {
                $instance->setEnableRefs(true);
                $instance->setRefs($conversationsSigners[$nid]);
            } else
                $instance->setEnableRefs(false);
            //$instance->setSelfHookName($hookName);
            $instance->setIcon($instanceIcon);
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty(true);
        $instanceList->display();
    }

    protected function _filterItemByType(string $nid): bool { return true; }



    CONST TRANSLATE_TABLE = [
        'en-en' => [
            '::ModuleName' => 'Messages module',
            '::MenuName' => 'Messages',
            '::ModuleDescription' => 'Messages management module',
            '::ModuleHelp' => 'This module permit to see and manage messages.',
            '::AppTitle1' => 'Messages',
            '::AppDesc1' => 'Manage messages',
            '::myConversations' => 'My conversations',
            '::allConversations' => 'All conversations',
            '::otherConversations' => 'Conversations of other entities',
            '::listMessages' => 'List of messages',
            '::createClosedConversation' => 'Create a closed conversation',
            '::createObfuscatedConversation' => 'Create an obfuscated conversation',
            '::addMarkedObjects' => 'Add marked objects',
            '::addToConversation' => 'Add to conversation',
            '::addMember' => 'Add a member',
            '::deleteConversation' => 'Delete conversation',
            '::createConversation' => 'Create a conversation',
            '::createConversationOK' => 'The conversation have been created',
            '::createConversationNOK' => 'The conversation have not been created! %s',
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
    const DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_PROTECT = 'creaupprot';
    const DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_OBFUSCATE_LINKS = 'creaupobf';

    protected bool $_actionCreateConversation = false;
    protected string $_actionCreateConversationName = '';
    protected string $_actionCreateConversationID = '0';
    protected bool $_actionCreateConversationClosed = false;
    protected bool $_actionCreateConversationProtected = false;
    protected bool $_actionCreateConversationObfuscateLinks = false;
    protected ?\Nebule\Library\Conversation $_actionCreateConversationInstance = null;
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
    public function getCreateConversationInstance(): ?\Nebule\Library\Conversation
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
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateGroup'))
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
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupDeleteGroup'))
            return;

        $this->_metrologyInstance->addLog('extract action delete conversation', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argDelete = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_DELETE_CONVERSATION, FILTER_FLAG_ENCODE_LOW);

        if ($argDelete !== ''
            && strlen($argDelete) >= \Nebule\Library\BlocLink::NID_MIN_HASH_SIZE
            && \Nebule\Library\Node::checkNID($argDelete)
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

        if (\Nebule\Library\Node::checkNID($arg))
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

        if (\Nebule\Library\Node::checkNID($arg))
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

        if (\Nebule\Library\Node::checkNID($arg))
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

        if (\Nebule\Library\Node::checkNID($arg))
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
