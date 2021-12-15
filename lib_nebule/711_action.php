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
abstract class Actions
{
    /* ---------- ---------- ---------- ---------- ----------
	 * Constantes.
	 *
	 * Leur modification change profondément le comportement de l'application.
	 *
	 * Si déclarées 'const' ou 'static' elles ne sont pas remplacée dans les classes enfants
	 *   lorsque l'on appelle des fonctions de la classe parente non écrite dans la classe enfant.
	 */
    const DEFAULT_COMMAND_APPLICATION = 'a';
    const DEFAULT_COMMAND_NEBULE_BOOTSTRAP = 'a=0';
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


    /* ---------- ---------- ---------- ---------- ----------
	 * Variables.
	 *
	 * Les valeurs par défaut sont indicatives. Ne pas les replacer.
	 * Les variables sont systématiquement recalculées.
	 */
    /**
     * Instance nebule.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance sylabe.
     *
     * @var sylabe
     */
    protected $_applicationInstance;

    /**
     * Instance de metrologie.
     *
     * @var metrology
     */
    protected $_metrology;

    /**
     * Instance des entrées/sorties.
     *
     * @var IO
     */
    protected $_io;

    /**
     * Instance de traduction.
     *
     * @var Traductions
     */
    protected $_traduction;

    /**
     * Instance d'affichage.
     *
     * @var Displays
     */
    protected $_display;

    /**
     * Etat de verrouillage de l'entité en cours.
     *
     * @var boolean
     */
    protected $_unlocked = false;

    /**
     * Constructeur.
     *
     * @param Application $applicationInstance
     * @return void
     */
    public function __construct(Application $applicationInstance)
    {
        $this->_applicationInstance = $applicationInstance;
    }

    /**
     * Initialisation des variables et instances interdépendantes.
     *
     * @return void
     */
    public function initialisation()
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Load actions', Metrology::LOG_LEVEL_DEBUG); // Log
        $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        $this->_display = $this->_applicationInstance->getDisplayInstance();
        $this->_metrology = $this->_applicationInstance->getMetrologyInstance();
        $this->_io = $this->_nebuleInstance->getIO();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        // Aucun affichage, aucune traduction, aucune action avant le retour de cette fonction.
        // Les instances interdépendantes doivent être synchronisées.
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return 'Action';
    }

    /**
     * Fonction de mise en sommeil.
     *
     * @return array:string
     */
    public function __sleep()
    {
        return array();
    }

    /**
     * Fonction de réveil.
     *
     * Récupère l'instance de la librairie nebule et de l'application.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $applicationInstance;

        $this->_applicationInstance = $applicationInstance;
    }

    /**
     * Initialisation des variables et instances interdépendantes.
     * @return void
     * @todo à optimiser avec __wakeup et __sleep.
     *
     */
    public function initialisation2()
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Load actions', Metrology::LOG_LEVEL_DEBUG); // Log
        $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        $this->_display = $this->_applicationInstance->getDisplayInstance();
        $this->_metrology = $this->_applicationInstance->getMetrologyInstance();
        $this->_io = $this->_nebuleInstance->getIO();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        // Aucun affichage, aucune traduction, aucune action avant le retour de cette fonction.
        // Les instances interdépendantes doivent être synchronisées.
    }


    /**
     * Traitement des actions génériques.
     *
     * Tout traitement générique doit se faire entité déverrouillée.
     * Les actions nécessitent la création de nouveaux liens.
     *
     * @return void
     */
    public function genericActions()
    {
        $this->_metrology->addLog('Generic actions', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'entité est déverrouillée.
        if (!$this->_unlocked) {
            return;
        }

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

        $this->_metrology->addLog('Router generic actions', Metrology::LOG_LEVEL_DEBUG); // Log

        // Gère la dissimulation d'un lien.
        if ($this->_actionObfuscateLinkInstance != ''
            && is_a($this->_actionObfuscateLinkInstance, 'Link')
            && $this->_nebuleInstance->getOption('permitObfuscatedLink')
        ) {
            $this->_actionObfuscateLink();
        }

        // Gère la suppression d'un objet.
        if ($this->_actionDeleteObject
            && is_a($this->_actionDeleteObjectInstance, 'Node')
        ) {
            $this->_actionDeleteObject();
        }

        // Gère la protection/déprotection d'un objet.
        if ($this->_actionProtectObjectInstance != ''
            && is_a($this->_actionProtectObjectInstance, 'Node')
        ) {
            $this->_actionProtectObject();
        }
        if ($this->_actionUnprotectObjectInstance != ''
            && is_a($this->_actionUnprotectObjectInstance, 'Node')
        ) {
            $this->_actionUnprotectObject();
        }
        if ($this->_actionShareProtectObjectToEntity != '') {
            $this->_actionShareProtectObjectToEntity();
        }
        if ($this->_actionShareProtectObjectToGroupOpened != '') {
            $this->_actionShareProtectObjectToGroupOpened();
        }
        if ($this->_actionShareProtectObjectToGroupClosed != '') {
            $this->_actionShareProtectObjectToGroupClosed();
        }
        if ($this->_actionCancelShareProtectObjectToEntity != '') {
            $this->_actionCancelShareProtectObjectToEntity();
        }

        // Gère les synchronisations (toujours les objets avant les liens).
        if ($this->_actionSynchronizeObjectInstance != '') {
            $this->_actionSynchronizeObject();
        }
        if ($this->_actionSynchronizeEntityInstance != '') {
            $this->_actionSynchronizeEntity();
        }
        if ($this->_actionSynchronizeObjectLinksInstance != '') {
            $this->_actionSynchronizeObjectLinks();
        }
        if ($this->_actionSynchronizeApplicationInstance != '') {
            $this->_actionSynchronizeApplication();
        }
        if ($this->_actionSynchronizeNewEntityID != '') {
            $this->_actionSynchronizeNewEntity();
        }

        // Gère les marques des objets.
        if ($this->_actionMarkObject != '') {
            $this->_actionMarkObject();
        }
        if ($this->_actionUnmarkObject != '') {
            $this->_actionUnmarkObject();
        }
        if ($this->_actionUnmarkAllObjects) {
            $this->_actionUnmarkAllObjects();
        }

        // Gère les téléchargements.
        if ($this->_actionUploadFile) {
            $this->_actionUploadFile();
        }
        if ($this->_actionUploadText) {
            $this->_actionUploadText();
        }

        // Gère les groupes.
        if ($this->_actionCreateGroup) {
            $this->_actionCreateGroup();
        }
        if ($this->_actionDeleteGroup) {
            $this->_actionDeleteGroup();
        }
        if ($this->_actionAddToGroup != '') {
            $this->_actionAddToGroup();
        }
        if ($this->_actionRemoveFromGroup != '') {
            $this->_actionRemoveFromGroup();
        }
        if ($this->_actionAddItemToGroup != '') {
            $this->_actionAddItemToGroup();
        }
        if ($this->_actionRemoveItemFromGroup != '') {
            $this->_actionRemoveItemFromGroup();
        }

        // Gère les conversations et messages.
        if ($this->_actionCreateConversation) {
            $this->_actionCreateConversation();
        }
        if ($this->_actionDeleteConversation) {
            $this->_actionDeleteConversation();
        }
        if ($this->_actionAddMessageOnConversation != '') {
            $this->_actionAddMessageOnConversation();
        }
        if ($this->_actionRemoveMessageOnConversation != '') {
            $this->_actionRemoveMessageOnConversation();
        }
        if ($this->_actionAddMemberOnConversation != '') {
            $this->_actionAddMemberOnConversation();
        }
        if ($this->_actionRemoveMemberOnConversation != '') {
            $this->_actionRemoveMemberOnConversation();
        }
        if ($this->_actionCreateMessage) {
            $this->_actionCreateMessage();
        }
        if ($this->_actionAddProperty != '') {
            $this->_actionAddProperty();
        }

        // Gère les monnaies.
        if ($this->_actionCreateCurrency) {
            $this->_actionCreateCurrency();
        }
        if ($this->_actionCreateTokenPool) {
            $this->_actionCreateTokenPool();
        }
        if ($this->_actionCreateTokens) {
            $this->_actionCreateTokens();
        }

        $this->_metrology->addLog('Generic actions end', Metrology::LOG_LEVEL_DEBUG); // Log
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
        $this->_metrology->addLog('Special actions', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'action de création d'entité soit permise entité verrouillée.
        if ($this->_unlocked
            || $this->_nebuleInstance->getOption('permitPublicCreateEntity')
        ) {
            $this->_extractActionCreateEntity();
        }

        // Vérifie que l'action de chargement de lien soit permise.
        if ($this->_nebuleInstance->getOption('permitUploadLink')
            || $this->_unlocked
        ) {
            // Extrait les actions.
            $this->_extractActionSignLink1();
            $this->_extractActionSignLink2();
            $this->_extractActionSignLink3();
            $this->_extractActionUploadLink();
            $this->_extractActionUploadFileLinks();
        }

        $this->_metrology->addLog('Router generic actions', Metrology::LOG_LEVEL_DEBUG); // Log

        // Si l'action de création d'entité est validée.
        if ($this->_actionCreateEntity) {
            $this->_actionCreateEntity();
        }

        // Si l'action de chargement de lien est permise y compris entité verrouillée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitUploadLink')
            && ($this->_nebuleInstance->getOption('permitPublicUploadCodeAuthoritiesLink')
                || $this->_nebuleInstance->getOption('permitPublicUploadLink')
                || $this->_unlocked
            )
        ) {
            // Lien à signer 1.
            if ($this->_unlocked
                && $this->_nebuleInstance->getOption('permitCreateLink')
                && $this->_actionSignLinkInstance1 != ''
                && is_a($this->_actionSignLinkInstance1, 'Link')
            ) {
                $this->_actionSignLink($this->_actionSignLinkInstance1, $this->_actionSignLinkInstance1Obfuscate);
            }

            // Lien à signer 2.
            if ($this->_unlocked
                && $this->_nebuleInstance->getOption('permitCreateLink')
                && $this->_actionSignLinkInstance2 != ''
                && is_a($this->_actionSignLinkInstance2, 'Link')
            ) {
                $this->_actionSignLink($this->_actionSignLinkInstance2, $this->_actionSignLinkInstance2Obfuscate);
            }

            // Lien à signer 3.
            if ($this->_unlocked
                && $this->_nebuleInstance->getOption('permitCreateLink')
                && $this->_actionSignLinkInstance3 != ''
                && is_a($this->_actionSignLinkInstance3, 'Link')
            ) {
                $this->_actionSignLink($this->_actionSignLinkInstance3, $this->_actionSignLinkInstance3Obfuscate);
            }

            // Liens pré-signés.
            if ($this->_actionUploadLinkInstance != null
                && is_a($this->_actionUploadLinkInstance, 'Link')
            ) {
                $this->_actionUploadLink($this->_actionUploadLinkInstance);
            }

            // Fichier de liens pré-signés.
            if ($this->_actionUploadFileLinks) {
                $this->_actionUploadFileLinks();
            }
        }

        $this->_metrology->addLog('Special actions end', Metrology::LOG_LEVEL_DEBUG); // Log
    }


    /**
     * Extrait pour action si on doit signer un lien (1).
     */
    protected function _extractActionSignLink1()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCreateLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action sign link 1', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK1, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK1_OBFUSCATE);

            // Si le champs est vide, extrait le contenu de la variable POST.
            if ($arg == '') {
                $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_SIGN_LINK1, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            }

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) != 0
            ) {
                $this->_actionSignLinkInstance1 = $this->_nebuleInstance->flatLinkExtractAsInstance($arg);
                $this->_actionSignLinkInstance1Obfuscate = $argObfuscate;
            }
            unset($arg);
        }
    }

    protected $_actionSignLinkInstance1 = '';
    protected $_actionSignLinkInstance1Obfuscate = false;

    /**
     * Extrait pour action si on doit signer un lien (2).
     */
    protected function _extractActionSignLink2()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCreateLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action sign link 2', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK2, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK2_OBFUSCATE);

            // Si le champs est vide, extrait le contenu de la variable POST.
            if ($arg == '') {
                $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_SIGN_LINK2, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            }

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) != 0
            ) {
                $this->_actionSignLinkInstance2 = $this->_nebuleInstance->flatLinkExtractAsInstance($arg);
                $this->_actionSignLinkInstance2Obfuscate = $argObfuscate;
            }
            unset($arg);
        }
    }

    protected $_actionSignLinkInstance2 = '';
    protected $_actionSignLinkInstance2Obfuscate = false;

    /**
     * Extrait pour action si on doit signer un lien (3).
     */
    protected function _extractActionSignLink3()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCreateLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action sign link 3', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK3, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SIGN_LINK3_OBFUSCATE);

            // Si le champs est vide, extrait le contenu de la variable POST.
            if ($arg == '') {
                $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_SIGN_LINK3, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            }

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) != 0
            ) {
                $this->_actionSignLinkInstance3 = $this->_nebuleInstance->flatLinkExtractAsInstance($arg);
                $this->_actionSignLinkInstance3Obfuscate = $argObfuscate;
            }
            unset($arg);
        }
    }

    protected $_actionSignLinkInstance3 = '';
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
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitUploadLink')
            && ($this->_nebuleInstance->getOption('permitPublicUploadLink')
                || $this->_nebuleInstance->getOption('permitPublicUploadCodeAuthoritiesLink')
                || $this->_unlocked
            )
        ) {
            $this->_metrology->addLog('Extract action upload signed link', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_UPLOAD_SIGNED_LINK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Si le champs est vide, extrait le contenu de la variable POST.
            if ($arg == '') {
                $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_SIGNED_LINK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            }

            // Vérifie si restriction des liens au maître du code. Non par défaut.
            $permitNotCodeMaster = false;
            if ($this->_nebuleInstance->getOption('permitPublicUploadLink')
                || $this->_unlocked
            ) {
                $permitNotCodeMaster = true;
            }

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) != 0
            ) {
                $instance = $this->_nebuleInstance->flatLinkExtractAsInstance($arg);
                if ($instance->getVerified()
                    && $instance->getValid()
                    && $instance->getSigned()
                    && ($instance->getHashSigner_disabled() == $this->_nebuleInstance->getCodeAuthority()
                        || $permitNotCodeMaster
                    )
                ) {
                    $this->_actionUploadLinkInstance = $instance;
                }
                unset($instance);
            }
            unset($arg);
        }
    }


    /**
     * Extrait pour action si on doit dissimuler un lien.
     */
    protected function _extractActionObfuscateLink()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitObfuscatedLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action obfuscate link', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_OBFUSCATE_LINK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Si le champs est vide, extrait le contenu de la variable POST.
            if ($arg == '') {
                $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_OBFUSCATE_LINK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            }

            // Extraction du lien et stockage pour traitement.
            if (strlen($arg) != 0) {
                $this->_actionObfuscateLinkInstance = $this->_nebuleInstance->flatLinkExtractAsInstance($arg);
            }
            unset($arg);
        }
    }

    protected $_actionObfuscateLinkInstance = '';


    /**
     * Renvoie si l'action de suppression d'objet est validée.
     */
    public function getDeleteObject()
    {
        return $this->_actionDeleteObject;
    }

    /**
     * Renvoie l'ID de l'objet supprimé.
     */
    public function getDeleteObjectID()
    {
        return $this->_actionDeleteObjectID;
    }

    /**
     * Extrait pour action si on doit supprimer l'objet en cours.
     */
    protected function _extractActionDeleteObject()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action delete object', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $argObject = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $argForce = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_OBJECT_FORCE);
            $argObfuscate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_OBJECT_OBFUSCATE);

            // Extraction de l'objet à supprimer.
            if ($argObject != ''
                && strlen($argObject) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($argObject)
            ) {
                $this->_actionDeleteObjectID = $argObject;
                $this->_actionDeleteObjectInstance = $this->_nebuleInstance->newObject($argObject);
                $this->_actionDeleteObject = true;
            }
            // Extraction si la suppression doit être forcée.
            if ($argForce) {
                $this->_actionDeleteObjectForce = true;
            }
            // Extraction si la suppression doit être cachée.
            if ($argObfuscate
                && $this->_nebuleInstance->getOption('permitObfuscatedLink')
            ) {
                $this->_actionDeleteObjectObfuscate = true;
            }

            unset($argObject, $argForce, $argObfuscate);
        }
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
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action protect object', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_PROTECT_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionProtectObjectInstance = $this->_nebuleInstance->newObject($arg);
            }
            unset($arg);
        }
    }

    protected $_actionProtectObjectInstance = '';


    /**
     * Extrait pour action si on doit déprotéger l'objet en cours.
     */
    protected function _extractActionUnprotectObject()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action unprotect object', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_UNPROTECT_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionUnprotectObjectInstance = $this->_nebuleInstance->newObject($arg);
            }
            unset($arg);
        }
    }

    protected $_actionUnprotectObjectInstance = '';


    /**
     * Extrait pour action si on doit partager la protection de l'objet à une entité.
     */
    protected function _extractActionShareProtectObjectToEntity()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action share protect object to entity', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionShareProtectObjectToEntity = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionShareProtectObjectToEntity = '';


    /**
     * Extrait pour action si on doit partager la protection de l'objet à un groupe ouvert.
     */
    protected function _extractActionShareProtectObjectToGroupOpened()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action share protect object to opened group', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_OPENED, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionShareProtectObjectToGroupOpened = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionShareProtectObjectToGroupOpened = '';


    /**
     * Extrait pour action si on doit partager la protection de l'objet à un groupe ouvert.
     */
    protected function _extractActionShareProtectObjectToGroupClosed()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action share protect object to closed group', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_CLOSED, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionShareProtectObjectToGroupClosed = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionShareProtectObjectToGroupClosed = '';


    /**
     * Extrait pour action si on doit annuler le partage la protection de l'objet à une entité.
     */
    protected function _extractActionCancelShareProtectObjectToEntity()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action cancel share protect object to entity', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CANCEL_SHARE_PROTECT_TO_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionCancelShareProtectObjectToEntity = $arg;
            }
            unset($arg);
        }
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
        // Vérifie que l'écriture d'objets soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
        ) {
            $this->_metrology->addLog('Extract action synchronize object', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction de l'id et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionSynchronizeObjectInstance = $this->_nebuleInstance->newObject($arg);
            }
            unset($arg);
        }
    }

    protected $_actionSynchronizeObjectInstance = '';


    /**
     * Retourne l'entité synchronisée.
     * @return string|entity
     */
    public function getSynchronizeEntityInstance()
    {
        return $this->_actionSynchronizeEntityInstance;
    }

    /**
     * Extrait pour action si on doit synchroniser l'objet en cours.
     */
    protected function _extractActionSynchronizeEntity()
    {
        // Vérifie que l'écriture d'objets soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
        ) {
            $this->_metrology->addLog('Extract action synchronize entity', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction de l'id et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionSynchronizeEntityInstance = $this->_nebuleInstance->newEntity($arg);
            }
            unset($arg);
        }
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
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
        ) {
            $this->_metrology->addLog('Extract action synchronize object links', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_LINKS, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction de l'id et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionSynchronizeObjectLinksInstance = $this->_nebuleInstance->newObject($arg);
            }
            unset($arg);
        }
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
        // Vérifie que l'écriture d'objets soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitSynchronizeObject')
            && $this->_nebuleInstance->getOption('permitSynchronizeLink')
            && $this->_nebuleInstance->getOption('permitSynchronizeApplication')
            && ($this->_nebuleInstance->getOption('permitPublicSynchronizeApplication')
                || $this->_unlocked
            )
        ) {
            $this->_metrology->addLog('Extract action synchronize entity', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction de l'id et stockage pour traitement.
            if ($arg != ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionSynchronizeApplicationInstance = $this->_nebuleInstance->newObject($arg);
            }
            unset($arg);
        }
    }

    protected $_actionSynchronizeApplicationInstance = '';


    /**
     * Retourne l'entité synchronisée depuis une URL.
     * @return string|entity
     */
    public function getSynchronizeNewEntityInstance()
    {
        return $this->_actionSynchronizeNewEntityInstance;
    }

    /**
     * Extrait pour action si on doit synchroniser l'objet en cours.
     */
    protected function _extractActionSynchronizeNewEntity()
    {
        // Vérifie que l'écriture d'objets soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitSynchronizeObject')
            && $this->_nebuleInstance->getOption('permitSynchronizeLink')
        ) {
            $this->_metrology->addLog('Extract action synchronize new entity', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu de la variable GET.
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
                ) {
                    $scheme = $parseURL['scheme'];
                } else {
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
                    $this->_metrology->addLog('Extract action synchronize new entity - ID=' . $id, Metrology::LOG_LEVEL_DEBUG); // Log
                    // Vérifie l'ID.
                    if (!$this->_io->checkObjectPresent($id)
                        && ctype_xdigit($id)
                    ) {
                        // Si c'est bon on prépare pour la synchronisation.
                        $this->_actionSynchronizeNewEntityID = $id;
                        $this->_actionSynchronizeNewEntityURL = $scheme . '://' . $parseURL['host'];
                        if (isset($parseURL['port'])) {
                            $port = $parseURL['port'];
                            $this->_actionSynchronizeNewEntityURL .= ':' . $port;
                        }
                        $this->_metrology->addLog('Extract action synchronize new entity - URL=' . $this->_actionSynchronizeNewEntityURL, Metrology::LOG_LEVEL_DEBUG); // Log
                    }
                }
            }
            unset($arg);
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
        $this->_metrology->addLog('Extract action mark object', Metrology::LOG_LEVEL_DEBUG); // Log

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_MARK_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        // Extraction du lien et stockage pour traitement.
        if ($arg != ''
            && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
            && ctype_xdigit($arg)
        ) {
            $this->_actionMarkObject = $arg;
        }
        unset($arg);
    }

    protected $_actionMarkObject = '';


    /**
     * Extrait pour action si on doit retirer la marque d'un objet.
     */
    protected function _extractActionUnmarkObject()
    {
        $this->_metrology->addLog('Extract action unmark object', Metrology::LOG_LEVEL_DEBUG); // Log

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_UNMARK_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        // Extraction du lien et stockage pour traitement.
        if ($arg != ''
            && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
            && ctype_xdigit($arg)
        ) {
            $this->_actionUnmarkObject = $arg;
        }
        unset($arg);
    }

    protected $_actionUnmarkObject = '';


    /**
     * Extrait pour action si on doit supprimer toutes les marques des objets.
     *
     * @return void
     */
    protected function _extractActionUnmarkAllObjects()
    {
        $this->_metrology->addLog('Extract action unmark all objects', Metrology::LOG_LEVEL_DEBUG); // Log

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        $arg = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_UNMARK_ALL_OBJECT);

        // Extraction du lien et stockage pour traitement.
        if ($arg !== false) {
            $this->_actionUnmarkAllObjects = true;
        }
        unset($arg);
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
    public function getUploadFileSignedLinks()
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
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitUploadLink')
            && ($this->_nebuleInstance->getOption('permitPublicUploadLink')
                || $this->_nebuleInstance->getOption('permitPublicUploadCodeAuthoritiesLink')
                || $this->_unlocked
            )
        ) {
            $this->_metrology->addLog('Extract action upload file of signed links', Metrology::LOG_LEVEL_DEBUG); // Log

            // Lit le contenu de la variable _FILE si un fichier est téléchargé.
            if (isset($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['error'])
                && $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['error'] == UPLOAD_ERR_OK
                && trim($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['name']) != ''
            ) {
                /*
				 *  ------------------------------------------------------------------------------------------
				 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
				 *  ------------------------------------------------------------------------------------------
				 */
                // Extraction des méta données du fichier.
                $upname = mb_convert_encoding(strtok(trim(filter_var($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['name'], FILTER_SANITIZE_STRING)), "\n"), 'UTF-8');
                $upsize = $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['size'];
                $uppath = $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS]['tmp_name'];

                // Si le fichier est bien téléchargé.
                if (file_exists($uppath)) {
                    // Si le fichier n'est pas trop gros.
                    if ($upsize <= $this->_nebuleInstance->getOption('ioReadMaxData')) {
                        // Ecriture des variables.
                        $this->_actionUploadFileLinks = true;
                        $this->_actionUploadFileLinksName = $upname;
                        $this->_actionUploadFileLinksSize = $upsize;
                        $this->_actionUploadFileLinksPath = $uppath;
                    } else {
                        $this->_metrology->addLog('Action _extractActionUploadFileLinks File size too big', Metrology::LOG_LEVEL_ERROR); // Log
                        $this->_actionUploadFileLinksError = true;
                        $this->_actionUploadFileLinksErrorMessage = 'File size too big';
                    }
                } else {
                    $this->_metrology->addLog('Action _extractActionUploadFileLinks File upload error', Metrology::LOG_LEVEL_ERROR); // Log
                    $this->_actionUploadFileLinksError = true;
                    $this->_actionUploadFileLinksErrorMessage = 'File upload error';
                }
                unset($upname, $upsize, $uppath);
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
    public function getUploadObject()
    {
        return $this->_actionUploadFile;
    }

    /**
     * Renvoie l'ID du fichier téléchargé vers le serveur.
     *
     * @return string
     */
    public function getUploadObjectID()
    {
        return $this->_actionUploadFileID;
    }

    /**
     * Renvoie si erreur.
     *
     * @return boolean
     */
    public function getUploadObjectError()
    {
        return $this->_actionUploadFileError;
    }

    /**
     * Renvoie le message d'erreur.
     *
     * @return string
     */
    public function getUploadObjectErrorMessage()
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
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action upload file', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            $uploadArgName = self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE;
            if (!isset($_FILES[$uploadArgName])) {
                return;
            }
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
                        if ($upsize <= $this->_nebuleInstance->getOption('ioReadMaxData')) {
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
                            if ($this->_nebuleInstance->getOption('permitProtectedObject')) {
                                $this->_actionUploadFileProtect = $argPrt;
                            }
                            if ($this->_nebuleInstance->getOption('permitObfuscatedLink')) {
                                $this->_actionUploadFileObfuscateLinks = $argObf;
                            }
                        } else {
                            $this->_metrology->addLog('Action _extractActionUploadFile ioReadMaxData exeeded', Metrology::LOG_LEVEL_ERROR);
                            $this->_actionUploadFileError = true;
                            $this->_actionUploadFileErrorMessage = 'Le fichier dépasse la taille limite de transfert.';
                        }
                    } else {
                        $this->_metrology->addLog('Action _extractActionUploadFile upload error', Metrology::LOG_LEVEL_ERROR);
                        $this->_actionUploadFileError = true;
                        $this->_actionUploadFileErrorMessage = "No uploaded file.";
                    }
                    unset($upfname, $upinfo, $upext, $upname, $upsize, $uppath, $uptype);
                    break;
                case UPLOAD_ERR_INI_SIZE:
                    $this->_metrology->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_INI_SIZE', Metrology::LOG_LEVEL_ERROR);
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $this->_metrology->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_FORM_SIZE', Metrology::LOG_LEVEL_ERROR);
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->_metrology->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_PARTIAL', Metrology::LOG_LEVEL_ERROR);
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "The uploaded file was only partially uploaded.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $this->_metrology->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_NO_FILE', Metrology::LOG_LEVEL_ERROR);
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "No file was uploaded.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->_metrology->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_NO_TMP_DIR', Metrology::LOG_LEVEL_ERROR);
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "Missing a temporary folder.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $this->_metrology->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_CANT_WRITE', Metrology::LOG_LEVEL_ERROR);
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "Failed to write file to disk.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $this->_metrology->addLog('Action _extractActionUploadFile upload PHP error UPLOAD_ERR_EXTENSION', Metrology::LOG_LEVEL_ERROR);
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop.";
                    break;
            }
        }
    }

    /**
     * Lit le type mime d'un fichier par rapport à son extension et la table de correspondance $nebule_mimepathfile.
     *
     * @param string $f
     * @return string
     */
    protected function _getFilenameTypeMime($f)
    {
        $m = '/etc/mime.types'; // Chemin du fichier pour trouver le type mime.
        $e = substr(strrchr($f, '.'), 1);
        if (empty($e)) {
            return 'application/octet-stream';
        }
        $r = "/^([\w\+\-\.\/]+)\s+(\w+\s)*($e\s)/i";
        $ls = file($m);
        foreach ($ls as $l) {
            if (substr($l, 0, 1) == '#') {
                continue;
            }
            $l = rtrim($l) . ' ';
            if (!preg_match($r, $l, $m)) {
                continue;
            }
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
    public function getUploadText()
    {
        return $this->_actionUploadText;
    }

    /**
     * Renvoie le nom du texte téléchargé vers le serveur.
     */
    public function getUploadTextName()
    {
        return $this->_actionUploadTextName;
    }

    /**
     * Renvoie l'ID du texte téléchargé vers le serveur.
     */
    public function getUploadTextID()
    {
        return $this->_actionUploadTextID;
    }

    /**
     * Renvoie le code erreur.
     */
    public function getUploadTextError()
    {
        return $this->_actionUploadTextError;
    }

    /**
     * Renvoie le message d'erreur.
     */
    public function getUploadTextErrorMessage()
    {
        return $this->_actionUploadTextErrorMessage;
    }

    /**
     * Extrait pour action si un texte est téléchargé vers le serveur.
     */
    protected function _extractActionUploadText()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action upload text', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit si la variable POST existe.
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
                    if ($argType != '') {
                        $this->_actionUploadTextType = $argType;
                    } else {
                        $this->_actionUploadTextType = nebule::REFERENCE_OBJECT_TEXT;
                    }

                    if ($this->_nebuleInstance->getOption('permitProtectedObject')) {
                        $this->_actionUploadTextProtect = $argPrt;
                    }
                    if ($this->_nebuleInstance->getOption('permitObfuscatedLink')) {
                        $this->_actionUploadTextObfuscateLinks = $argObf;
                    }
                }
                unset($argText, $argName, $argPrt, $argObf);
            }
            unset($arg);
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
    public function getCreateEntity()
    {
        return $this->_actionCreateEntity;
    }

    /**
     * Revoie l'ID de la nouvelle entité.
     */
    public function getCreateEntityID()
    {
        return $this->_actionCreateEntityID;
    }

    /**
     * Revoie l'instance de la nouvelle entité.
     */
    public function getCreateEntityInstance()
    {
        return $this->_actionCreateEntityInstance;
    }

    /**
     * Revoie le code erreur de création de la nouvelle entité.
     *
     * @return boolean
     */
    public function getCreateEntityError()
    {
        return $this->_actionCreateEntityError;
    }

    /**
     * Revoie le message d'erreur de création de la nouvelle entité.
     */
    public function getCreateEntityErrorMessage()
    {
        return $this->_actionCreateEntityErrorMessage;
    }

    /**
     * Extrait pour action si une entité doit être créée.
     */
    protected function _extractActionCreateEntity()
    {
        // Vérifie que la création de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteEntity')
            && ($this->_unlocked
                || $this->_nebuleInstance->getOption('permitPublicCreateEntity')
            )
        ) {
            $this->_metrology->addLog('Extract action create entity', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_ENTITY);

            // Extraction du lien et stockage pour traitement.
            if ($argCreate !== false) {
                $this->_actionCreateEntity = true;
            }
            unset($argCreate);

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
                if ($this->_nebuleInstance->getOption('permitObfuscatedLink')) {
                    $this->_actionCreateEntityObfuscateLinks = $argObf;
                }

                if ($argPasswd1 == $argPasswd2) {
                    $this->_actionCreateEntityPassword = $argPasswd1;
                } else {
                    $this->_metrology->addLog('Action _extractActionCreateEntity passwords not match', Metrology::LOG_LEVEL_ERROR); // Log
                    $this->_actionCreateEntityError = true;
                    $this->_actionCreateEntityErrorMessage = 'Les mots de passes ne sont pas identiques.';
                }

                if ($argType == 'human'
                    || $argType == 'robot'
                ) {
                    $this->_actionCreateEntityType = $argType;
                } else {
                    $this->_actionCreateEntityType = '';
                }

                unset($argPrefix, $argSuffix, $argFstnam, $argNiknam, $argName, $argPasswd1, $argPasswd2, $argType);
            }
        } else {
            $this->_metrology->addLog('Action _extractActionCreateEntity not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateEntityError = true;
            $this->_actionCreateEntityErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Renvoie si l'action de création du groupe a été faite.
     */
    public function getCreateGroup()
    {
        return $this->_actionCreateGroup;
    }

    /**
     * Revoie l'ID du nouveau groupe.
     */
    public function getCreateGroupID()
    {
        return $this->_actionCreateGroupID;
    }

    /**
     * Revoie l'instance du nouveau groupe.
     */
    public function getCreateGroupInstance()
    {
        return $this->_actionCreateGroupInstance;
    }

    /**
     * Revoie le code erreur de création du nouveau groupe.
     */
    public function getCreateGroupError()
    {
        return $this->_actionCreateGroupError;
    }

    /**
     * Revoie le message d'erreur de création du nouveau groupe.
     */
    public function getCreateGroupErrorMessage()
    {
        return $this->_actionCreateGroupErrorMessage;
    }

    /**
     * Extrait pour action si un groupe doit être créé.
     */
    protected function _extractActionCreateGroup()
    {
        // Vérifie que la création de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action create group', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_GROUP);

            // Extraction du lien et stockage pour traitement.
            if ($argCreate !== false) {
                $this->_actionCreateGroup = true;
            }
            unset($argCreate);

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
                if ($this->_nebuleInstance->getOption('permitObfuscatedLink')) {
                    $this->_actionCreateGroupObfuscateLinks = $argObf;
                }

                unset($argName);
            }
        } else {
            $this->_metrology->addLog('Action _extractActionCreateGroup not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateGroupError = true;
            $this->_actionCreateGroupErrorMessage = 'Non autorisé.';
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
    public function getDeleteGroup()
    {
        return $this->_actionDeleteGroup;
    }

    /**
     * Revoie l'ID du groupe.
     */
    public function getDeleteGroupID()
    {
        return $this->_actionDeleteGroupID;
    }

    /**
     * Revoie le code erreur de suppression du groupe.
     */
    public function getDeleteGroupError()
    {
        return $this->_actionDeleteGroupError;
    }

    /**
     * Revoie le message d'erreur de suppression du groupe.
     */
    public function getDeleteGroupErrorMessage()
    {
        return $this->_actionDeleteGroupErrorMessage;
    }

    /**
     * Extrait pour action si un groupe doit être supprimé.
     */
    protected function _extractActionDeleteGroup()
    {
        // Vérifie que la suppression de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action delete group', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argDelete = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($argDelete !== ''
                && strlen($argDelete) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($argDelete)
            ) {
                $this->_actionDeleteGroup = true;
                $this->_actionDeleteGroupID = $argDelete;
            }
            unset($argDelete);
        } else {
            $this->_metrology->addLog('Action _extractActionDeleteGroup not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionDeleteGroupError = true;
            $this->_actionDeleteGroupErrorMessage = 'Non autorisé.';
        }
    }

    protected $_actionDeleteGroup = false,
        $_actionDeleteGroupID = '0',
        $_actionDeleteGroupError = false,
        $_actionDeleteGroupErrorMessage = 'Initialisation de la supression.';


    /**
     * Renvoie si l'action d'ajout de objet courant au groupe a été faite.
     */
    public function getAddToGroup()
    {
        return $this->_actionAddToGroup;
    }

    /**
     * Extrait pour action si l'objet courant doit être ajouté à un groupe.
     */
    protected function _extractActionAddToGroup()
    {
        // Vérifie que l'ajout de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action add to group', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_TO_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg !== ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionAddToGroup = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionAddToGroup = '';


    /**
     * Renvoie si l'action de suppression de l'objet courant du groupe a été faite.
     */
    public function getRemoveFromGroup()
    {
        return $this->_actionRemoveFromGroup;
    }

    /**
     * Extrait pour action si l'objet courant doit être retirer d'un groupe.
     */
    protected function _extractActionRemoveFromGroup()
    {
        // Vérifie que l'ajout de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action remove from group', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_REMOVE_FROM_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg !== ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionRemoveFromGroup = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionRemoveFromGroup = '';


    /**
     * Renvoie si l'action d'ajout d'un objet au groupe courant a été faite.
     */
    public function getAddItemToGroup()
    {
        return $this->_actionAddItemToGroup;
    }

    /**
     * Extrait pour action si un objet doit être ajouté au groupe courant.
     */
    protected function _extractActionAddItemToGroup()
    {
        // Vérifie que l'ajout de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action add item to group', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_ITEM_TO_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg !== ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionAddItemToGroup = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionAddItemToGroup = '';


    /**
     * Renvoie si l'action de retrait du groupe a été faite.
     */
    public function getRemoveItemFromGroup()
    {
        return $this->_actionRemoveItemFromGroup;
    }

    /**
     * Extrait pour action si un objet doit être retiré au groupe courant.
     */
    protected function _extractActionRemoveItemFromGroup()
    {
        // Vérifie que l'ajout de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action remove item from group', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_GROUP, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg !== ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionRemoveItemFromGroup = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionRemoveItemFromGroup = '';


    /**
     * Renvoie si l'action de création de la conversation a été faite.
     */
    public function getCreateConversation()
    {
        return $this->_actionCreateConversation;
    }

    /**
     * Revoie l'ID de la nouvelle conversation.
     */
    public function getCreateConversationID()
    {
        return $this->_actionCreateConversationID;
    }

    /**
     * Revoie l'instance de la nouvelle conversation.
     */
    public function getCreateConversationInstance()
    {
        return $this->_actionCreateConversationInstance;
    }

    /**
     * Revoie le code erreur de création de la nouvelle conversation.
     */
    public function getCreateConversationError()
    {
        return $this->_actionCreateConversationError;
    }

    /**
     * Revoie le message d'erreur de création de la nouvelle conversation.
     */
    public function getCreateConversationErrorMessage()
    {
        return $this->_actionCreateConversationErrorMessage;
    }

    /**
     * Extrait pour action si une conversation doit être créé.
     */
    protected function _extractActionCreateConversation()
    {
        // Vérifie que la création de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action create group', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION);

            // Extraction du lien et stockage pour traitement.
            if ($argCreate !== false) {
                $this->_actionCreateConversation = true;
            }
            unset($argCreate);

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
                if ($this->_nebuleInstance->getOption('permitProtectedObject')) {
                    $this->_actionCreateConversationProtected = $argPrt;
                }
                if ($this->_nebuleInstance->getOption('permitObfuscatedLink')) {
                    $this->_actionCreateConversationObfuscateLinks = $argObf;
                }

                unset($argName);
            }
        } else {
            $this->_metrology->addLog('Action _extractActionCreateConversation not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateConversationError = true;
            $this->_actionCreateConversationErrorMessage = 'Non autorisé.';
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
    public function getDeleteConversation()
    {
        return $this->_actionDeleteConversation;
    }

    /**
     * Revoie l'ID de la conversation.
     */
    public function getDeleteConversationID()
    {
        return $this->_actionDeleteConversationID;
    }

    /**
     * Revoie le code erreur de suppression de la conversation.
     */
    public function getDeleteConversationError()
    {
        return $this->_actionDeleteConversationError;
    }

    /**
     * Revoie le message d'erreur de suppression de la conversation.
     */
    public function getDeleteConversationErrorMessage()
    {
        return $this->_actionDeleteConversationErrorMessage;
    }

    /**
     * Extrait pour action si une conversation doit être supprimé.
     */
    protected function _extractActionDeleteConversation()
    {
        // Vérifie que la suppression de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action delete conversation', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argDelete = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DELETE_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($argDelete !== ''
                && strlen($argDelete) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($argDelete)
            ) {
                $this->_actionDeleteConversation = true;
                $this->_actionDeleteConversationID = $argDelete;
            }
            unset($argDelete);
        } else {
            $this->_metrology->addLog('Action _extractActionDeleteConversation not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionDeleteConversationError = true;
            $this->_actionDeleteConversationErrorMessage = 'Non autorisé.';
        }
    }

    protected $_actionDeleteConversation = false,
        $_actionDeleteConversationID = '0',
        $_actionDeleteConversationError = false,
        $_actionDeleteConversationErrorMessage = 'Initialisation de la supression.';


    /**
     * Renvoie si l'action d'ajout de objet courant à la conversation a été faite.
     */
    public function getAddMessageOnConversation()
    {
        return $this->_actionAddMessageOnConversation;
    }

    /**
     * Extrait pour action si l'objet courant doit être ajouté à une conversation.
     */
    protected function _extractActionAddMessageOnConversation()
    {
        // Vérifie que l'ajout de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action add to conversation', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_TO_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg !== ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionAddMessageOnConversation = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionAddMessageOnConversation = '';


    /**
     * Renvoie si l'action de suppression de l'objet courant de la conversation a été faite.
     */
    public function getRemoveMessageOnConversation()
    {
        return $this->_actionRemoveMessageOnConversation;
    }

    /**
     * Extrait pour action si l'objet courant doit être retirer d'une conversation.
     */
    protected function _extractActionRemoveMessageOnConversation()
    {
        // Vérifie que l'ajout de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action remove from conversation', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_REMOVE_FROM_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg !== ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionRemoveMessageOnConversation = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionRemoveMessageOnConversation = '';


    /**
     * Renvoie si l'action d'ajout d'un objet à la conversation courant a été faite.
     */
    public function getAddMemberOnConversation()
    {
        return $this->_actionAddMemberOnConversation;
    }

    /**
     * Extrait pour action si un objet doit être ajouté à la conversation courant.
     */
    protected function _extractActionAddMemberOnConversation()
    {
        // Vérifie que l'ajout de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action add item to conversation', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_ITEM_TO_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg !== ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionAddMemberOnConversation = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionAddMemberOnConversation = '';


    /**
     * Renvoie si l'action de retrait de la conversation a été faite.
     */
    public function getRemoveMemberOnConversation()
    {
        return $this->_actionRemoveMemberOnConversation;
    }

    /**
     * Extrait pour action si un objet doit être retiré à la conversation courant.
     */
    protected function _extractActionRemoveMemberOnConversation()
    {
        // Vérifie que l'ajout de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action remove item from conversation', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_CONVERSATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($arg !== ''
                && strlen($arg) >= nebule::NEBULE_MINIMUM_ID_SIZE
                && ctype_xdigit($arg)
            ) {
                $this->_actionRemoveMemberOnConversation = $arg;
            }
            unset($arg);
        }
    }

    protected $_actionRemoveMemberOnConversation = '';

    /**
     * Extrait pour action si une conversation doit être créé.
     *
     * @return void
     */
    protected function _extractActionCreateMessage()
    {
        // Vérifie que la création de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action create message', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_MESSAGE);

            // Extraction du lien et stockage pour traitement.
            if ($argCreate !== false)
                $this->_actionCreateMessage = true;
            unset($argCreate);

            // Si on crée un nouveau message.
            if ($this->_actionCreateMessage) {
                // Extrait les options de téléchargement.
                // !!! Utilise les constantes de upload de texte pour que la protection et la dissimulation soient bien pris en compte.
                //$argPrt	= filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_MESSAGE_PROTECTED);
                $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_PROTECT);
                //$argObf	= filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_MESSAGE_OBFUSCATE_LINKS);
                $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_OBFUSCATE_LINKS);

                // Sauvegarde les valeurs.
                if ($this->_nebuleInstance->getOption('permitProtectedObject')) {
                    $this->_actionCreateMessageProtected = $argPrt;
                }
                if ($this->_nebuleInstance->getOption('permitObfuscatedLink')) {
                    $this->_actionCreateMessageObfuscateLinks = $argObf;
                }
            }

            // Le reste des valeurs est récupéré par la partie création d'un texte.
        } else {
            $this->_metrology->addLog('Action _extractActionCreateMessage not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateMessageError = true;
            $this->_actionCreateMessageErrorMessage = 'Non autorisé.';
        }
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
    public function getAddPropertyError()
    {
        return $this->_actionAddPropertyError;
    }

    /**
     * Revoie le message d'erreur d'ajout de propriété.
     */
    public function getAddPropertyErrorMessage()
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
        // Vérifie que la création de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action add property', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argAdd = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

            // Extraction du lien et stockage pour traitement.
            if ($argAdd != '')
                $this->_actionAddProperty = $argAdd;
            unset($argAdd);

            // Si on crée une nouvelle propriété.
            if ($this->_actionAddProperty != '') {
                // Extrait les autres variables.
                $argObj = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_OBJECT, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
                $argVal = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_VALUE, FILTER_SANITIZE_STRING));
                $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_PROTECTED);
                $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_ADD_PROPERTY_OBFUSCATE_LINKS);

                // Sauvegarde les valeurs.
                if ($argVal != '') {
                    if ($argObj == '') {
                        $this->_actionAddPropertyObject = $this->_nebuleInstance->getCurrentObject();
                    } else {
                        $this->_actionAddPropertyObject = $argObj;
                    }
                    $this->_actionAddPropertyValue = $argVal;
                    if ($this->_nebuleInstance->getOption('permitProtectedObject')) {
                        $this->_actionAddPropertyProtected = $argPrt;
                    }
                    if ($this->_nebuleInstance->getOption('permitObfuscatedLink')) {
                        $this->_actionAddPropertyObfuscateLinks = $argObf;
                    }
                } else {
                    $this->_metrology->addLog('Action _extractActionAddProperty null value', Metrology::LOG_LEVEL_ERROR); // Log
                    $this->_actionAddPropertyError = true;
                    $this->_actionAddPropertyErrorMessage = 'Valeur vide.';
                }
                unset($argObj, $argVal, $argPrt, $argObf);
            }
        } else {
            $this->_metrology->addLog('Action _extractActionAddProperty not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionAddPropertyError = true;
            $this->_actionAddPropertyErrorMessage = 'Non autorisé.';
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
    public function getCreateCurrency()
    {
        return $this->_actionCreateCurrency;
    }

    /**
     * Revoie l'ID du nouveau sac de jetons.
     *
     * @return string
     */
    public function getCreateCurrencyID()
    {
        return $this->_actionCreateCurrencyID;
    }

    /**
     * Revoie l'instance du nouveau sac de jetons.
     *
     * @return Currency
     */
    public function getCreateCurrencyInstance()
    {
        return $this->_actionCreateCurrencyInstance;
    }

    /**
     * Revoie les paramètres de la nouvelle monnaie.
     *
     * @return array
     */
    public function getCreateCurrencyParam()
    {
        return $this->_actionCreateCurrencyParam;
    }

    /**
     * Revoie le code erreur de création de la nouvelle monnaie.
     *
     * @return boolean
     */
    public function getCreateCurrencyError()
    {
        return $this->_actionCreateCurrencyError;
    }

    /**
     * Revoie le message d'erreur de création de la nouvelle monnaie.
     *
     * @return string
     */
    public function getCreateCurrencyErrorMessage()
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
        // Vérifie que la création de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCurrency')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getOption('permitCreateCurrency')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action create currency', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_CURRENCY);

            // Extraction du lien et stockage pour traitement.
            if ($argCreate !== false) {
                $this->_actionCreateCurrency = true;
            }
            unset($argCreate);

            // Si on crée une nouvelle monnaie.
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
                        foreach ($valueArray as $item) {
                            $value .= ' ' . trim($item);
                        }
                        $this->_actionCreateCurrencyParam[$name] = trim($value);
                        unset($value, $valueArray);
                    } else {
                        $this->_actionCreateCurrencyParam[$name] = trim(filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
                    }
                    $this->_metrology->addLog('Extract action create currency - _' . $property['shortname'] . ':' . $this->_actionCreateCurrencyParam[$name], Metrology::LOG_LEVEL_DEVELOP); // Log

                    // Extrait si forcé.
                    if (isset($property['forceable'])) {
                        $this->_actionCreateCurrencyParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                        if ($this->_actionCreateCurrencyParam['Force' . $name]) {
                            $this->_metrology->addLog('Extract action create currency - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG); // Log
                        }
                    }
                }
            }
        } else {
//			$this->_actionCreateCurrencyError = true;
            $this->_actionCreateCurrencyErrorMessage = 'Non autorisé.';
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
    public function getCreateTokenPool()
    {
        return $this->_actionCreateTokenPool;
    }

    /**
     * Revoie l'ID du nouveau sac de jetons.
     *
     * @return string
     */
    public function getCreateTokenPoolID()
    {
        return $this->_actionCreateTokenPoolID;
    }

    /**
     * Revoie l'instance du nouveau sac de jetons.
     *
     * @return TokenPool
     */
    public function getCreateTokenPoolInstance()
    {
        return $this->_actionCreateTokenPoolInstance;
    }

    /**
     * Revoie les paramètres du nouveau sac de jetons.
     *
     * @return array
     */
    public function getCreateTokenPoolParam()
    {
        return $this->_actionCreateTokenPoolParam;
    }

    /**
     * Revoie le code erreur de création du nouveau sac de jetons.
     *
     * @return boolean
     */
    public function getCreateTokenPoolError()
    {
        return $this->_actionCreateTokenPoolError;
    }

    /**
     * Revoie le message d'erreur de création du nouveau sac de jetons.
     *
     * @return string
     */
    public function getCreateTokenPoolErrorMessage()
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
        // Vérifie que la création de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCurrency')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getOption('permitCreateCurrency')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action create token pool', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_TOKEN_POOL);

            // Extraction du lien et stockage pour traitement.
            if ($argCreate !== false) {
                $this->_actionCreateTokenPool = true;
            }
            unset($argCreate);

            // Si on crée un nouveau sac de jetons.
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
                        foreach ($valueArray as $item) {
                            $value .= ' ' . trim($item);
                        }
                        $this->_actionCreateTokenPoolParam[$name] = trim($value);
                        unset($value, $valueArray);
                    } else {
                        $this->_actionCreateTokenPoolParam[$name] = trim(filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
                    }
                    $this->_metrology->addLog('Extract action create token pool - p' . $property['key'] . ':' . $this->_actionCreateTokenPoolParam[$name], Metrology::LOG_LEVEL_DEBUG); // Log

                    // Extrait si forcé.
                    if (isset($property['forceable'])) {
                        $this->_actionCreateTokenPoolParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                        if ($this->_actionCreateTokenPoolParam['Force' . $name]) {
                            $this->_metrology->addLog('Extract action create token pool - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG); // Log
                        }
                    }
                }
            }
        } else {
//			$this->_actionCreateTokenPoolError = true;
            $this->_actionCreateTokenPoolErrorMessage = 'Non autorisé.';
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
    public function getCreateTokens()
    {
        return $this->_actionCreateTokens;
    }

    /**
     * Revoie les ID des nouveaux jetons.
     *
     * @return array:string
     */
    public function getCreateTokensID()
    {
        return $this->_actionCreateTokensID;
    }

    /**
     * Revoie les instances des nouveaux jetons.
     *
     * @return array:Token
     */
    public function getCreateTokensInstance()
    {
        return $this->_actionCreateTokensInstance;
    }

    /**
     * Revoie les paramètres des nouveaux jetons.
     *
     * @return array
     */
    public function getCreateTokensParam()
    {
        return $this->_actionCreateTokensParam;
    }

    /**
     * Revoie le code erreur de création des nouveaux jetons.
     *
     * @return boolean
     */
    public function getCreateTokensError()
    {
        return $this->_actionCreateTokensError;
    }

    /**
     * Revoie le message d'erreur de création des nouveaux jetons.
     *
     * @return string
     */
    public function getCreateTokensErrorMessage()
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
        // Vérifie que la création de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitCurrency')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getOption('permitCreateCurrency')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action create tokens', Metrology::LOG_LEVEL_DEBUG); // Log

            /*
    		 *  ------------------------------------------------------------------------------------------
    		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
    		 *  ------------------------------------------------------------------------------------------
    		 */
            // Lit et nettoye le contenu de la variable GET.
            $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_TOKENS);

            // Extraction du lien et stockage pour traitement.
            if ($argCreate !== false) {
                $this->_actionCreateTokens = true;
            }
            unset($argCreate);

            // Si on crée un nouveau sac de jetons.
            if ($this->_actionCreateTokens) {
                // Récupère la liste des propriétés.
                $instance = $this->_nebuleInstance->getCurrentTokenInstance();
                $propertiesList = $instance->getPropertiesList();
                unset($instance);

                foreach ($propertiesList['token'] as $name => $property) {
                    // Extrait une valeur.
                    if (isset($property['checkbox'])) {
                        $value = '';
                        $valueArray = filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                        foreach ($valueArray as $item) {
                            $value .= ' ' . trim($item);
                        }
                        $this->_actionCreateTokensParam[$name] = trim($value);
                        unset($value, $valueArray);
                    } else {
                        $this->_actionCreateTokensParam[$name] = trim(filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
                    }
                    $this->_metrology->addLog('Extract action create tokens - t' . $property['key'] . ':' . $this->_actionCreateTokensParam[$name], Metrology::LOG_LEVEL_DEBUG); // Log

                    // Extrait si forcé.
                    if (isset($property['forceable'])) {
                        $this->_actionCreateTokensParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                        if ($this->_actionCreateTokensParam['Force' . $name]) {
                            $this->_metrology->addLog('Extract action create tokens - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG); // Log
                        }
                    }
                }

                // Extrait le nombre de jetons à générer.
                $this->_actionCreateTokensCount = (int)trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_CREATE_TOKENS_COUNT, FILTER_SANITIZE_NUMBER_INT));
            }
        } else {
//			$this->_actionCreateTokensError = true;
            $this->_actionCreateTokensErrorMessage = 'Non autorisé.';
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
     * @param Link $link
     * @param boolean $obfuscate
     * @return void
     * @todo
     *
     */
    protected function _actionSignLink(Link $link, $obfuscate = 'default')
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitUploadLink')
        ) {
            if ($this->_unlocked) {
                $this->_metrology->addLog('Action sign link', Metrology::LOG_LEVEL_DEBUG); // Log

                // On cache le lien ?
                if ($obfuscate !== false
                    && $obfuscate !== true
                ) {
                    $obfuscate = $this->_nebuleInstance->getOption('defaultObfuscateLinks');
                }
                //...

                // Signature du lien.
                $link->signWrite();
            } elseif ($this->_nebuleInstance->getOption('permitPublicUploadCodeAuthoritiesLink')
                || $this->_nebuleInstance->getOption('permitPublicUploadLink')
            ) {
                $this->_metrology->addLog('Action sign link', Metrology::LOG_LEVEL_DEBUG); // Log

                // Affichage du lien.
                echo $this->_display->convertInlineIconFace('DEFAULT_ICON_ADDLNK') . $this->_display->convertInlineLinkFace($link);

                // Si signé, écriture du lien.
                if ($link->getSigned()) {
                    $link->write();
                }
            }
            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
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
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitUploadLink')
            && ($this->_nebuleInstance->getOption('permitPublicUploadCodeAuthoritiesLink')
                || $this->_nebuleInstance->getOption('permitPublicUploadLink')
                || $this->_unlocked
            )
            && is_a($link, 'Link')
            && $link->getVerified()
            && $link->getValid()
        ) {
            $this->_metrology->addLog('Action upload link', Metrology::LOG_LEVEL_DEBUG); // Log

            if ($link->getSigned()
                && (($link->getHashSigner_disabled() == $this->_nebuleInstance->getCodeAuthority()
                        && $this->_nebuleInstance->getOption('permitPublicUploadCodeAuthoritiesLink')
                    )
                    || $this->_nebuleInstance->getOption('permitPublicUploadLink')
                    || $this->_unlocked
                )
            ) {
                $link->write();
                $this->_metrology->addLog('Action upload link - signed link ' . $link->getFullLink(), Metrology::LOG_LEVEL_NORMAL); // Log
            } elseif ($this->_unlocked) {
                $link = $this->_nebuleInstance->newLink(
                    '0_'
                    . $this->_nebuleInstance->getCurrentEntity() . '_'
                    . $link->getDate_disabled() . '_'
                    . $link->getAction_disabled() . '_'
                    . $link->getHashSource_disabled() . '_'
                    . $link->getHashTarget_disabled() . '_'
                    . $link->getHashMeta_disabled()
                );
                $link->signWrite();
                $this->_metrology->addLog('Action upload link - unsigned link ' . $link->getFullLink(), Metrology::LOG_LEVEL_NORMAL); // Log
            }

            // Affichage des actions.
            $this->_display->displayInlineLastAction();
        }
    }


    /**
     * Dissimule un lien.
     *
     * @return void
     */
    protected function _actionObfuscateLink()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && ($this->_nebuleInstance->getOption('permitPublicUploadLink')
                || $this->_nebuleInstance->getOption('permitPublicUploadCodeAuthoritiesLink')
                || ($this->_nebuleInstance->getOption('permitUploadLink')
                    && $this->_unlocked
                )
            )
            && $this->_nebuleInstance->getOption('permitObfuscatedLink')
            && $link->getVerified()
            && $link->getValid()
            && $link->getSigned()
        ) {
            $this->_metrology->addLog('Action obfuscate link', Metrology::LOG_LEVEL_DEBUG); // Log

            // On dissimule le lien.
            $this->_actionObfuscateLinkInstance->obfuscateWrite();

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Supprime un objet.
     */
    protected function _actionDeleteObject()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisés.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action delete object', Metrology::LOG_LEVEL_DEBUG); // Log

            // Suppression de l'objet.
            if ($this->_actionDeleteObjectForce)
                $this->_actionDeleteObjectInstance->deleteForceObject();
            else
                $this->_actionDeleteObjectInstance->deleteObject();

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Protège un objet.
     */
    protected function _actionProtectObject()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action protect object', Metrology::LOG_LEVEL_DEBUG); // Log

            // Demande de protection de l'objet.
            $this->_actionProtectObjectInstance->setProtected();

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Déprotège un objet.
     */
    protected function _actionUnprotectObject()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action unprotect object', Metrology::LOG_LEVEL_DEBUG); // Log

            // Demande de protection de l'objet.
            $this->_actionUnprotectObjectInstance->setUnprotected();

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }

    /**
     * Partage la protection d'un objet pour une entité.
     */
    protected function _actionShareProtectObjectToEntity()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action share protect object to entity ' . $this->_actionShareProtectObjectToEntity, Metrology::LOG_LEVEL_DEBUG); // Log

            // Demande de protection de l'objet.
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($this->_actionShareProtectObjectToEntity);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }

    /**
     * Partage la protection d'un objet pour un groupe ouvert.
     */
    protected function _actionShareProtectObjectToGroupOpened()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action share protect object to opened group', Metrology::LOG_LEVEL_DEBUG); // Log

            // Demande de protection de l'objet.
            $group = $this->_nebuleInstance->newGroup($this->_actionShareProtectObjectToGroupOpened);
            foreach ($group->getListMembersID('myself', null) as $id) {
                $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
            }
            unset($group, $id);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }

    /**
     * Partage la protection d'un objet pour un groupe fermé.
     */
    protected function _actionShareProtectObjectToGroupClosed()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action share protect object to closed group', Metrology::LOG_LEVEL_DEBUG); // Log

            // Demande de protection de l'objet.
            $group = $this->_nebuleInstance->newGroup($this->_actionShareProtectObjectToGroupClosed);
            foreach ($group->getListMembersID('myself', null) as $id) {
                $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
            }
            unset($group, $id);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }

    /**
     * Annule le partage de la protection d'un objet pour une entité.
     */
    protected function _actionCancelShareProtectObjectToEntity()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action cancel share protect object to entity', Metrology::LOG_LEVEL_DEBUG); // Log

            // Demande d'annulation de protection de l'objet.
            $this->_nebuleInstance->getCurrentObjectInstance()->cancelShareProtectionTo($this->_actionCancelShareProtectObjectToEntity);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Synchronise l'objet.
     */
    protected function _actionSynchronizeObject()
    {
        // Vérifie que la création d'objet soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitSynchronizeObject')
        ) {
            $this->_metrology->addLog('Action synchronize object', Metrology::LOG_LEVEL_DEBUG); // Log

            echo $this->_display->convertInlineIconFace('DEFAULT_ICON_SYNOBJ') . $this->_display->convertInlineObjectColor($this->_actionSynchronizeObjectInstance);

            // Synchronisation.
            $this->_actionSynchronizeObjectInstance->syncObject();

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Synchronise l'entité.
     */
    protected function _actionSynchronizeEntity()
    {
        // Vérifie que la création d'objet soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitSynchronizeObject')
            && $this->_nebuleInstance->getOption('permitSynchronizeLink')
        ) {
            $this->_metrology->addLog('Action synchronize entity', Metrology::LOG_LEVEL_DEBUG); // Log

            echo $this->_display->convertInlineIconFace('DEFAULT_ICON_SYNENT') . $this->_display->convertInlineObjectColor($this->_actionSynchronizeEntityInstance);

            // Synchronisation des liens (l'objet est forcément présent).
            $this->_actionSynchronizeEntityInstance->syncLinks();

            // Liste des liens l pour l'entité en source.
            $links = $this->_actionSynchronizeEntityInstance->readLinksFilterFull('', '', 'l', $this->_actionSynchronizeEntityInstance->getID(), '', '');
            // Synchronise l'objet cible.
            $object = null;
            foreach ($links as $link) {
                $object = $this->_nebuleInstance->newObject($link->getHashTarget());
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
                $object = $this->_nebuleInstance->newObject($link->getHashSource());
                // Synchronise les liens (avant).
                $object->syncLinks();
                // Synchronise l'objet.
                $object->syncObject();
            }
            unset($links, $link, $object);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Synchronise les liens de l'objet.
     */
    protected function _actionSynchronizeObjectLinks()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitSynchronizeLink')
        ) {
            $this->_metrology->addLog('Action synchronize object links', Metrology::LOG_LEVEL_DEBUG); // Log

            echo $this->_display->convertInlineIconFace('DEFAULT_ICON_SYNLNK') . $this->_display->convertInlineObjectColor($this->_actionSynchronizeObjectLinksInstance);

            // Synchronisation.
            $this->_actionSynchronizeObjectLinksInstance->syncLinks();

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Synchronise l'application. A revoir complètement ce qui est sync @todo
     */
    protected function _actionSynchronizeApplication()
    {
        // Vérifie que la création d'objet soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitSynchronizeObject')
            && $this->_nebuleInstance->getOption('permitSynchronizeLink')
            && $this->_nebuleInstance->getOption('permitSynchronizeApplication')
            && ($this->_nebuleInstance->getOption('permitPublicSynchronizeApplication')
                || $this->_unlocked()
            )
        ) {
            $this->_metrology->addLog('Action synchronize application', Metrology::LOG_LEVEL_DEBUG); // Log

            echo $this->_display->convertInlineIconFace('DEFAULT_ICON_SYNOBJ') . $this->_display->convertInlineObjectColor($this->_actionSynchronizeApplicationInstance);

            // Synchronisation des liens (l'objet est forcément présent).
            $this->_actionSynchronizeApplicationInstance->syncLinks();

            // Liste des liens l pour l'entité en source.
            $links = $this->_actionSynchronizeApplicationInstance->readLinksFilterFull('', '', 'l', $this->_actionSynchronizeApplicationInstance->getID(), '', '');
            // Synchronise l'objet cible.
            $object = null;
            foreach ($links as $link) {
                $object = $this->_nebuleInstance->newObject($link->getHashTarget());
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
                $object = $this->_nebuleInstance->newObject($link->getHashSource());
                // Synchronise les liens (avant).
                $object->syncLinks();
                // Synchronise l'objet.
                $object->syncObject();
            }
            unset($links, $link, $object);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Synchronise une entité externe depuis une URL.
     */
    protected function _actionSynchronizeNewEntity()
    {
        // Vérifie que la création d'objet soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitSynchronizeObject')
            && $this->_nebuleInstance->getOption('permitSynchronizeLink')
        ) {
            $this->_metrology->addLog('Action synchronize new entity', Metrology::LOG_LEVEL_DEBUG); // Log

            // Vérifie si l'objet est déjà présent.
            $present = $this->_io->checkObjectPresent($this->_actionSynchronizeNewEntityID);
            // Lecture de l'objet.
            $data = $this->_io->objectRead($this->_actionSynchronizeNewEntityID, Entity::ENTITY_MAX_SIZE, $this->_actionSynchronizeNewEntityURL);
            // Calcul de l'empreinte.
            $hash = hash($this->_nebuleInstance->getCrypto()->hashAlgorithmName(), $data);
            if ($hash != $this->_actionSynchronizeNewEntityID) {
                $this->_metrology->addLog('Action synchronize new entity - Hash error', Metrology::LOG_LEVEL_DEBUG); // Log
                unset($data);
                echo $this->_display->convertInlineIconFace('DEFAULT_ICON_SYNENT') .
                    $this->_display->convertInlineObjectColor($this->_actionSynchronizeNewEntityID) .
                    $this->_display->convertInlineIconFace('DEFAULT_ICON_IERR');
                $this->_actionSynchronizeNewEntityID = '';
                $this->_actionSynchronizeNewEntityURL = '';
                return false;
            }
            // Ecriture de l'objet.
            $this->_io->objectWrite($data);

            $this->_actionSynchronizeNewEntityInstance = $this->_nebuleInstance->newEntity($this->_actionSynchronizeNewEntityID);

            if (!$this->_actionSynchronizeNewEntityInstance->getTypeVerify) {
                $this->_metrology->addLog('Action synchronize new entity - Not entity', Metrology::LOG_LEVEL_DEBUG); // Log
                if (!$present)
                    $this->_actionSynchronizeNewEntityInstance->deleteObject();
            }

            echo $this->_display->convertInlineIconFace('DEFAULT_ICON_SYNENT') . $this->_display->convertInlineObjectColor($this->_actionSynchronizeNewEntityInstance);

            // Synchronisation des liens.
            $this->_actionSynchronizeNewEntityInstance->syncLinks();

            // Liste des liens l pour l'entité en source.
            $links = $this->_actionSynchronizeNewEntityInstance->readLinksFilterFull('', '', 'l', $hash, '', '');
            // Synchronise l'objet cible.
            $object = null;
            foreach ($links as $link) {
                $object = $this->_nebuleInstance->newObject($link->getHashTarget());
                // Synchronise les liens (avant).
                $object->syncLinks();
                // Synchronise l'objet.
                $object->syncObject();
            }
            // Liste des liens l pour l'entité en cible.
            $links = $this->_actionSynchronizeNewEntityInstance->readLinksFilterFull('', '', 'l', '', $hash, '');
            // Synchronise l'objet source.
            $object = null;
            foreach ($links as $link) {
                $object = $this->_nebuleInstance->newObject($link->getHashSource());
                // Synchronise les liens (avant).
                $object->syncLinks();
                // Synchronise l'objet.
                $object->syncObject();
            }
            unset($links, $link, $object);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Marque un objet.
     */
    protected function _actionMarkObject()
    {
        $this->_metrology->addLog('Action mark object', Metrology::LOG_LEVEL_DEBUG); // Log

        $this->_applicationInstance->setMarkObject($this->_actionMarkObject);

        // Affichage des actions.
        $this->_display->displayInlineAllActions();
    }


    /**
     * Supprime la marque d'un objet.
     */
    protected function _actionUnmarkObject()
    {
        $this->_metrology->addLog('Action unmark object', Metrology::LOG_LEVEL_DEBUG); // Log

        $this->_applicationInstance->setUnmarkObject($this->_actionUnmarkObject);

        // Affichage des actions.
        $this->_display->displayInlineAllActions();
    }


    /**
     * Supprime les marques de tous les objets.
     */
    protected function _actionUnmarkAllObjects()
    {
        $this->_metrology->addLog('Action unmark all objects', Metrology::LOG_LEVEL_DEBUG); // Log

        $this->_applicationInstance->setUnmarkAllObjects();

        // Affichage des actions.
        $this->_display->displayInlineAllActions();
    }


    /**
     * Transfert un fichier et le nebulise.
     *
     * @return void
     */
    protected function _actionUploadFile()
    {
        // Vérifie que la création d'objet soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action upload file', Metrology::LOG_LEVEL_DEBUG); // Log

            // Lit le contenu du fichier.
            $data = file_get_contents($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['tmp_name']);

            // Ecrit le contenu dans l'objet.
            $instance = new Node($this->_nebuleInstance, '0', $data, $this->_actionUploadFileProtect);
            if ($instance === false) {
                $this->_metrology->addLog('Action _actionUploadFile cant create object instance', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
                return false;
            }

            // Lit l'ID.
            $id = $instance->getID();
            unset($data);
            if ($id == '0') {
                $this->_metrology->addLog('Action _actionUploadFile cant create object', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "L'objet n'a pas pu être créé.";
                return false;
            }
            $this->_actionUploadFileID = $id;

            // Définition de la date et le signataire.
            $date = date(DATE_ATOM);
            $signer = $this->_nebuleInstance->getCurrentEntity();

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
                $source = $this->_applicationInstance->getCurrentObject();
                $target = $id;
                $meta = '0';
                $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionUploadFileObfuscateLinks);
            }

            unset($date, $signer, $source, $target, $meta, $link, $newLink, $textID, $instance);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Transfert un texte et le nebulise.
     */
    protected function _actionUploadText()
    {
        // Vérifie que la création d'objet soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action upload text', Metrology::LOG_LEVEL_DEBUG); // Log

            // Crée l'instance de l'objet.
            $instance = new Node($this->_nebuleInstance, '0', $this->_actionUploadTextContent, $this->_actionUploadTextProtect);
            if ($instance === false) {
                $this->_metrology->addLog('Action _actionUploadText cant create object instance', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
                return false;
            }

            // Lit l'ID.
            $id = $instance->getID();
            if ($id == '0') {
                $this->_metrology->addLog('Action _actionUploadText cant create object', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "L'objet n'a pas pu être créé.";
                return false;
            }
            $this->_actionUploadTextID = $id;

            // Affichage des actions.
            $this->_display->displayInlineAllActions();

            // Définition de la date et du signataire.
            //$signer	= $this->_nebuleInstance->getCurrentEntity();
            //$date = date(DATE_ATOM);

            // Création du type mime.
            $instance->setType($this->_actionUploadTextType);

            // Crée l'objet du nom.
            $instance->setName($this->_actionUploadTextName);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();

            unset($id, $instance);
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
    protected function _actionUploadFileLinks()
    {
        // Vérifie que la création d'objet soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitUploadLink')
            && ($this->_nebuleInstance->getOption('permitPublicUploadCodeAuthoritiesLink')
                || $this->_nebuleInstance->getOption('permitPublicUploadLink')
                || $this->_unlocked
            )
        ) {
            $this->_metrology->addLog('Action upload file signed links', Metrology::LOG_LEVEL_DEBUG); // Log

            // Ecrit les liens correctement signés.
            $updata = file($this->_actionUploadFileLinksPath);
            $nbLinks = 0;
            $nbLines = 0;
            foreach ($updata as $line) {
                if (substr($line, 0, 21) != 'nebule/liens/version/') {
                    $nbLines++;
                    $instance = $this->_nebuleInstance->newLink($line);
                    if ($instance->getVerified()
                        && $instance->getValid()
                    ) {
                        if ($instance->getSigned()
                            && (($instance->getHashSigner_disabled() == $this->_nebuleInstance->getCodeAuthority()
                                    && $this->_nebuleInstance->getOption('permitPublicUploadCodeAuthoritiesLink')
                                )
                                || $this->_nebuleInstance->getOption('permitPublicUploadLink')
                                || $this->_unlocked
                            )
                        ) {
                            $instance->write();
                            $nbLinks++;
                            $this->_metrology->addLog('Action upload file links - signed link ' . $instance->getFullLink(), Metrology::LOG_LEVEL_NORMAL); // Log
                        } elseif ($this->_unlocked) {
                            $instance = $this->_nebuleInstance->newLink(
                                '0_'
                                . $this->_nebuleInstance->getCurrentEntity() . '_'
                                . $instance->getDate_disabled() . '_'
                                . $instance->getAction_disabled() . '_'
                                . $instance->getHashSource_disabled() . '_'
                                . $instance->getHashTarget_disabled() . '_'
                                . $instance->getHashMeta_disabled()
                            );
                            $instance->signWrite();
                            $nbLinks++;
                            $this->_metrology->addLog('Action upload file links - unsigned link ' . $instance->getFullLink(), Metrology::LOG_LEVEL_NORMAL); // Log
                        }
                    }
                }
            }

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
            echo "\n<br />\nRead=$nbLines Valid=$nbLinks\n";
        }
    }


    /**
     * Crée une nouvelle entité.
     *
     * @return void
     */
    protected function _actionCreateEntity()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteEntity')
            && ($this->_unlocked
                || $this->_nebuleInstance->getOption('permitPublicCreateEntity')
            )
            && $this->_actionCreateEntity
            && !$this->_actionCreateEntityError
        ) {
            $this->_metrology->addLog('Action create entity', Metrology::LOG_LEVEL_DEBUG); // Log

            // Création de la nouvelle entité nebule.
            $instance = new Entity($this->_nebuleInstance, 'new');

            // Affichage des actions.
            $this->_display->displayInlineAllActions();

            if (is_a($instance, 'Entity') && $instance->getID() != '0') {
                $this->_actionCreateEntityError = false;

                // Enregistre l'instance créée.
                $this->_actionCreateEntityInstance = $instance;
                $this->_actionCreateEntityID = $instance->getID();
                unset($instance);

                // Modifie le mot de passe de clé privée.
                $this->_actionCreateEntityInstance->changePrivateKeyPassword($this->_actionCreateEntityPassword);

                // Affichage des actions.
                $this->_display->displayInlineAllActions();

                // Bascule temporairement sur la nouvelle entité.
                $this->_nebuleInstance->setTempCurrentEntity($this->_actionCreateEntityInstance);
                $this->_nebuleInstance->getCurrentEntityInstance()->setPrivateKeyPassword($this->_actionCreateEntityPassword);

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
                    $textID = $this->_nebuleInstance->createTextAsObject($this->_actionCreateEntityName); // Est fait avec l'entité courante, pas la nouvelle !!!
                    if ($textID !== false) {
                        // Crée le lien.
                        $action = 'l';
                        $source = $this->_actionCreateEntityID;
                        $target = $textID;
                        $meta = $this->_nebuleInstance->getCrypto()->hash('nebule/objet/nom');
                        $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                    }
                }
                if ($this->_actionCreateEntityFirstname != '') {
                    // Crée l'objet avec le texte.
                    $textID = $this->_nebuleInstance->createTextAsObject($this->_actionCreateEntityFirstname);
                    if ($textID !== false) {
                        // Crée le lien.
                        $action = 'l';
                        $source = $this->_actionCreateEntityID;
                        $target = $textID;
                        $meta = $this->_nebuleInstance->getCrypto()->hash('nebule/objet/prenom');
                        $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                    }
                }
                if ($this->_actionCreateEntityNikename != '') {
                    // Crée l'objet avec le texte.
                    $textID = $this->_nebuleInstance->createTextAsObject($this->_actionCreateEntityNikename);
                    if ($textID !== false) {
                        // Crée le lien.
                        $action = 'l';
                        $source = $this->_actionCreateEntityID;
                        $target = $textID;
                        $meta = $this->_nebuleInstance->getCrypto()->hash('nebule/objet/surnom');
                        $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                    }
                }

                // Affichage des actions.
                $this->_display->displayInlineAllActions();

                if ($this->_actionCreateEntityPrefix != '') {
                    // Crée l'objet avec le texte.
                    $textID = $this->_nebuleInstance->createTextAsObject($this->_actionCreateEntityPrefix);
                    if ($textID !== false) {
                        // Crée le lien.
                        $action = 'l';
                        $source = $this->_actionCreateEntityID;
                        $target = $textID;
                        $meta = $this->_nebuleInstance->getCrypto()->hash('nebule/objet/prefix');
                        $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                    }
                }
                if ($this->_actionCreateEntitySuffix != '') {
                    // Crée l'objet avec le texte.
                    $textID = $this->_nebuleInstance->createTextAsObject($this->_actionCreateEntitySuffix);
                    if ($textID !== false) {
                        // Crée le lien.
                        $action = 'l';
                        $source = $this->_actionCreateEntityID;
                        $target = $textID;
                        $meta = $this->_nebuleInstance->getCrypto()->hash('nebule/objet/suffix');
                        $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                    }
                }
                if ($this->_actionCreateEntityType != '') {
                    // Crée l'objet avec le texte.
                    $textID = $this->_nebuleInstance->createTextAsObject($this->_actionCreateEntityType);
                    if ($textID !== false) {
                        // Crée le lien.
                        $action = 'l';
                        $source = $this->_actionCreateEntityID;
                        $target = $textID;
                        $meta = $this->_nebuleInstance->getCrypto()->hash('nebule/objet/entite/type');
                        $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionCreateEntityObfuscateLinks);
                    }
                }

                unset($date, $source, $target, $meta, $link, $newLink, $textID);

                // Restaure l'entité d'origine.
                $this->_nebuleInstance->unsetTempCurrentEntity();

                // Efface le cache pour recharger l'entité.
                $this->_nebuleInstance->unsetEntityCache($this->_actionCreateEntityID);

                // Recrée l'instance de l'objet.
                $this->_actionCreateEntityInstance = $this->_nebuleInstance->newEntity($this->_actionCreateEntityID);
            } else {
                // Si ce n'est pas bon.
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrology->addLog('Action _actionCreateEntity cant generate', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionCreateEntityError = true;
                $this->_actionCreateEntityErrorMessage = 'Echec de la génération.';
            }

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionCreateEntity not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateEntityError = true;
            $this->_actionCreateEntityErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Crée un nouveau groupe.
     */
    protected function _actionCreateGroup()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
            && !$this->_actionCreateGroupError
        ) {
            $this->_metrology->addLog('Action create group', Metrology::LOG_LEVEL_DEBUG); // Log

            // Création du nouveau groupe.
            $instance = new Group($this->_nebuleInstance, 'new', $this->_actionCreateGroupClosed);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();

            if (is_a($instance, 'Group') && $instance->getID() != '0') {
                $this->_actionCreateGroupError = false;
                $instance->setName($this->_actionCreateGroupName);

                $this->_actionCreateGroupInstance = $instance;
                $this->_actionCreateGroupID = $instance->getID();
            } else {
                // Si ce n'est pas bon.
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrology->addLog('Action _actionCreateGroup cant generate', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionCreateGroupError = true;
                $this->_actionCreateGroupErrorMessage = 'Echec de la génération.';
            }

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionCreateGroup not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateGroupError = true;
            $this->_actionCreateGroupErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Supprime un groupe.
     */
    protected function _actionDeleteGroup()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
            && !$this->_actionDeleteGroupError
        ) {
            $this->_metrology->addLog('Action delete group ' . $this->_actionDeleteGroupID, Metrology::LOG_LEVEL_DEBUG); // Log

            /**
             * Instance du groupe.
             * @var Group $instance
             */
            $instance = $this->_nebuleInstance->newGroup($this->_actionDeleteGroupID);

            // Vérification.
            if ($instance->getID() == '0'
                || !$instance->getIsGroup('all')
            ) {
                $this->_actionDeleteGroupError = false;
                $this->_actionDeleteGroupErrorMessage = 'Pas un groupe.';
                $this->_metrology->addLog('Action delete not a group', Metrology::LOG_LEVEL_DEBUG); // Log
                return;
            }

            // Suppression.
            if ($instance->getMarkClosed()) {
                $this->_metrology->addLog('Action delete group closed', Metrology::LOG_LEVEL_DEBUG); // Log
                $instance->unsetMarkClosed();
            }
            $instance->unsetGroup();

            // Vérification.
            if ($instance->getIsGroup('myself')) {
                // Si ce n'est pas bon.
                $this->_metrology->addLog('Action _actionDeleteGroup cant generate', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionDeleteGroupError = true;
                $this->_actionDeleteGroupErrorMessage = 'Echec de la génération.';
            }
            unset($instance);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionDeleteGroup not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionDeleteGroupError = true;
            $this->_actionDeleteGroupErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Ajoute l'objet courant à un groupe.
     *
     * @return void
     */
    protected function _actionAddToGroup()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action add to group ' . $this->_actionAddToGroup, Metrology::LOG_LEVEL_DEBUG); // Log
            $instanceGroupe = $this->_nebuleInstance->newGroup($this->_actionAddToGroup);
            $instanceGroupe->setMember($this->_nebuleInstance->getCurrentObjectInstance());
            unset($instanceGroupe);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Retire l'objet courant d'un groupe.
     *
     * @return void
     */
    protected function _actionRemoveFromGroup()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action remove from group ' . $this->_actionRemoveFromGroup, Metrology::LOG_LEVEL_DEBUG); // Log
            $instanceGroupe = $this->_nebuleInstance->newGroup($this->_actionRemoveFromGroup);
            $instanceGroupe->unsetMember($this->_nebuleInstance->getCurrentObjectInstance());
            unset($instanceGroupe);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Ajoute un objet au groupe courant.
     */
    protected function _actionAddItemToGroup()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action add item to group ' . $this->_actionAddItemToGroup, Metrology::LOG_LEVEL_DEBUG); // Log
            $this->_nebuleInstance->getCurrentGroupInstance()->setMember($this->_actionAddItemToGroup);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Retire un objet courant du groupe courant.
     */
    protected function _actionRemoveItemFromGroup()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteGroup')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action remove item from group ' . $this->_actionRemoveItemFromGroup, Metrology::LOG_LEVEL_DEBUG); // Log
            $this->_nebuleInstance->getCurrentGroupInstance()->unsetMember($this->_actionRemoveItemFromGroup);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Crée une nouvelle conversation.
     */
    protected function _actionCreateConversation()
    {
        // Vérifie que la création de conversation soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
            && !$this->_actionCreateConversationError
        ) {
            $this->_metrology->addLog('Action create conversation', Metrology::LOG_LEVEL_DEBUG); // Log

            // Création de la nouvelle conversation.
            $instance = new Conversation(
                $this->_nebuleInstance,
                'new',
                $this->_actionCreateConversationClosed,
                $this->_actionCreateConversationProtected,
                $this->_actionCreateConversationObfuscateLinks);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();

            if (is_a($instance, 'Conversation')
                && $instance->getID() != '0'
            ) {
                $this->_actionCreateConversationError = false;
                $instance->setName($this->_actionCreateConversationName);

                $this->_actionCreateConversationInstance = $instance;
                $this->_actionCreateConversationID = $instance->getID();
            } else {
                // Si ce n'est pas bon.
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrology->addLog('Action _actionCreateConversation cant generate', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionCreateConversationError = true;
                $this->_actionCreateConversationErrorMessage = 'Echec de la génération.';
            }

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionCreateConversation not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateConversationError = true;
            $this->_actionCreateConversationErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Supprime un conversation.
     *
     * On fait d'abort la suppression de la conversation fermée puis si rappelé on fait la suppression de la conversation ouvert.
     * On ne fait pas les deux en même temps.
     */
    protected function _actionDeleteConversation()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
            && !$this->_actionDeleteConversationError
        ) {
            $this->_metrology->addLog('Action delete conversation ' . $this->_actionDeleteConversationID, Metrology::LOG_LEVEL_DEBUG); // Log

            // Suppression.
            $instance = $this->_nebuleInstance->newConversation($this->_actionDeleteConversationID);
            if (!is_a($instance, 'Conversation')
                || $instance->getID() == '0'
                || !$instance->getIsConversation('myself')
            ) {
                $this->_actionDeleteConversationError = false;
                $this->_actionDeleteConversationErrorMessage = 'Pas un conversation.';
                $this->_metrology->addLog('Action delete not a group', Metrology::LOG_LEVEL_DEBUG); // Log
                return;
            }
            if ($instance->getIsConversationClosed()) {
                $this->_metrology->addLog('Action delete conversation closed', Metrology::LOG_LEVEL_DEBUG); // Log
                $instance->setUnmarkConversationClosed();
                $test = $instance->getIsConversationClosed();
            } else {
                $this->_metrology->addLog('Action delete conversation', Metrology::LOG_LEVEL_DEBUG); // Log
                $instance->setUnmarkConversationOpened();
                $test = $instance->getIsConversationOpened();
            }

            // Vérification.
            if ($test) {
                // Si ce n'est pas bon.
                $this->_metrology->addLog('Action _actionDeleteConversation cant generate', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionDeleteConversationError = true;
                $this->_actionDeleteConversationErrorMessage = 'Echec de la génération.';
            }
            unset($instance, $test);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionDeleteConversation not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionDeleteConversationError = true;
            $this->_actionDeleteConversationErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Ajoute l'objet courant à un conversation.
     */
    protected function _actionAddMessageOnConversation()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action add message to conversation ' . $this->_actionAddMessageOnConversation, Metrology::LOG_LEVEL_DEBUG); // Log

            $instanceConversation = $this->_nebuleInstance->newConversation($this->_actionAddMessageOnConversation);
            $instanceConversation->setMember($this->_nebuleInstance->getCurrentObject(), false);
            unset($instanceConversation);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Retire l'objet courant d'un conversation.
     */
    protected function _actionRemoveMessageOnConversation()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action remove message to conversation ' . $this->_actionRemoveMessageOnConversation, Metrology::LOG_LEVEL_DEBUG); // Log

            $instanceConversation = $this->_nebuleInstance->newConversation($this->_actionRemoveMessageOnConversation);
            $instanceConversation->unsetMember($this->_nebuleInstance->getCurrentObject());
            unset($instanceConversation);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Ajoute une entité à la conversation courante.
     */
    protected function _actionAddMemberOnConversation()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action add member to conversation ' . $this->_actionAddMemberOnConversation, Metrology::LOG_LEVEL_DEBUG); // Log

            $instanceConversation = $this->_nebuleInstance->newConversation($this->_actionAddMemberOnConversation);
            $instanceConversation->setFollower($this->_nebuleInstance->getCurrentObject());
            unset($instanceConversation);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Retire une entité de la conversation courante.
     */
    protected function _actionRemoveMemberOnConversation()
    {
        // Vérifie que la création de liens soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action remove member to conversation ' . $this->_actionRemoveMemberOnConversation, Metrology::LOG_LEVEL_DEBUG); // Log

            $instanceConversation = $this->_nebuleInstance->newConversation($this->_actionRemoveMemberOnConversation);
            $instanceConversation->unsetFollower($this->_nebuleInstance->getCurrentObject());
            unset($instanceConversation);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
    }


    /**
     * Crée un nouveau message.
     * Se fait à partir d'un texte précédemment chargé via les fonctions uploadText.
     */
    protected function _actionCreateMessage()
    {
        // Vérifie que la création de message soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteConversation')
            && $this->_unlocked
            && !$this->_actionCreateMessageError
            && !$this->_actionUploadTextError
        ) {
            $id = $this->_actionUploadTextID;
            $this->_metrology->addLog('Action create message ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log
            if ($this->_actionCreateMessageProtected) {
                $this->_metrology->addLog('Action create message protected', Metrology::LOG_LEVEL_DEBUG); // Log
            }
            if ($this->_actionCreateMessageObfuscateLinks) {
                $this->_metrology->addLog('Action create message with obfuscated links', Metrology::LOG_LEVEL_DEBUG); // Log
            }

            // Création de l'instance du message.
            $instanceMessage = $this->_nebuleInstance->newObject($id, $this->_actionCreateMessageProtected, $this->_actionCreateMessageObfuscateLinks);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();

            if (is_a($instanceMessage, 'Node')
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
                $this->_metrology->addLog('Action _actionCreateMessage cant generate', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionCreateMessageError = true;
                $this->_actionCreateMessageErrorMessage = 'Echec de la génération.';
            }

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionCreateMessage not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateMessageError = true;
            $this->_actionCreateMessageErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Crée une nouvelle propriété pour un objet.
     */
    protected function _actionAddProperty()
    {
        // Vérifie que la création d'objet soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_unlocked
            && !$this->_actionAddPropertyError
        ) {
            $prop = $this->_actionAddProperty;
            $propID = $this->_nebuleInstance->getCrypto()->hash($prop);
            $this->_metrology->addLog('Action add property ' . $prop, Metrology::LOG_LEVEL_DEBUG); // Log
            $objectID = $this->_actionAddPropertyObject;
            $this->_metrology->addLog('Action add property for ' . $objectID, Metrology::LOG_LEVEL_DEBUG); // Log
            $value = $this->_actionAddPropertyValue;
            $valueID = $this->_nebuleInstance->getCrypto()->hash($value);
            $this->_metrology->addLog('Action add property value : ' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
            $protected = $this->_actionAddPropertyProtected;
            if ($protected) {
                $this->_metrology->addLog('Action add property protected', Metrology::LOG_LEVEL_DEBUG); // Log
            }
            if ($this->_actionAddPropertyObfuscateLinks) {
                $this->_metrology->addLog('Action add property with obfuscated links', Metrology::LOG_LEVEL_DEBUG); // Log
            }

            // Création des objets si besoin.
            if (!$this->_nebuleInstance->getIO()->checkObjectPresent($propID)) {
                $this->_nebuleInstance->createTextAsObject($prop);
            }
            if (!$this->_nebuleInstance->getIO()->checkObjectPresent($valueID)) {
                $this->_nebuleInstance->createTextAsObject($value, $protected, $this->_actionAddPropertyObfuscateLinks);
            }

            // Création du lien.
            $date = date(DATE_ATOM);
            $signer = $this->_nebuleInstance->getCurrentEntity();
            $action = 'l';
            $source = $objectID;
            $target = $valueID;
            $meta = $propID;
            $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionAddPropertyObfuscateLinks);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionAddProperty not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionAddPropertyError = true;
            $this->_actionAddPropertyErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Crée une nouvelle monnaie.
     */
    protected function _actionCreateCurrency()
    {
        // Vérifie que la création de monnaie soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getOption('permitCreateCurrency')
            && $this->_unlocked
            && !$this->_actionCreateCurrencyError
        ) {
            $this->_metrology->addLog('Action create currency', Metrology::LOG_LEVEL_DEBUG); // Log

            // Création de la nouvelle monnaie.
            $instance = new Currency($this->_nebuleInstance, 'new', $this->_actionCreateCurrencyParam, false, false);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();

            if (is_a($instance, 'Currency')
                && $instance->getID() != '0'
            ) {
                $this->_actionCreateCurrencyError = false;

                $this->_actionCreateCurrencyInstance = $instance;
                $this->_actionCreateCurrencyID = $instance->getID();

                $this->_metrology->addLog('Action _actionCreateCurrency generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_DEBUG); // Log
            } else {
                // Si ce n'est pas bon.
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrology->addLog('Action _actionCreateCurrency cant generate', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionCreateCurrencyError = true;
                $this->_actionCreateCurrencyErrorMessage = 'Echec de la génération.';
            }

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionCreateCurrency not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateCurrencyError = true;
            $this->_actionCreateCurrencyErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Crée un nouveau sac de jetons.
     */
    protected function _actionCreateTokenPool()
    {
        // Vérifie que la création de sac de jetons soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getOption('permitCreateCurrency')
            && $this->_unlocked
            && !$this->_actionCreateTokenPoolError
        ) {
            $this->_metrology->addLog('Action create token pool', Metrology::LOG_LEVEL_DEBUG); // Log

            // Création du nouveau sac de jetons.
            $instance = new TokenPool($this->_nebuleInstance, 'new', $this->_actionCreateTokenPoolParam, false, false);

            // Affichage des actions.
            $this->_display->displayInlineAllActions();

            if (is_a($instance, 'TokenPool')
                && $instance->getID() != '0'
            ) {
                $this->_actionCreateTokenPoolError = false;

                $this->_actionCreateTokenPoolInstance = $instance;
                $this->_actionCreateTokenPoolID = $instance->getID();

                $this->_metrology->addLog('Action _actionCreateTokenPool generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_DEBUG); // Log
            } else {
                // Si ce n'est pas bon.
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrology->addLog('Action _actionCreateTokenPool cant generate', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_actionCreateTokenPoolError = true;
                $this->_actionCreateTokenPoolErrorMessage = 'Echec de la génération.';
            }

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionCreateTokenPool not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateTokenPoolError = true;
            $this->_actionCreateTokenPoolErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Crée un nouveau sac de jetons.
     */
    protected function _actionCreateTokens()
    {
        // Vérifie que la création de sac de jetons soit authorisée.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteObject')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getOption('permitCreateCurrency')
            && $this->_unlocked
            && !$this->_actionCreateTokensError
            && $this->_actionCreateTokensCount > 0
        ) {
            $this->_metrology->addLog('Action create tokens', Metrology::LOG_LEVEL_DEBUG); // Log

            $instance = $this->_nebuleInstance->getCurrentTokenInstance();
            for ($i = 0; $i < $this->_actionCreateTokensCount; $i++) {
                // Si pas le premier jeton, supprime le SID demandé de façon à ce qu'il soit généré aléatoirement.
                if ($i > 0) {
                    $this->_actionCreateTokensParam['TokenSerialID'] = '';
                }

                // Création du nouveau sac de jetons.
                $instance = new Token($this->_nebuleInstance, 'new', $this->_actionCreateTokensParam, false, false);

                if (is_a($instance, 'Token')
                    && $instance->getID() != '0'
                ) {
                    $this->_actionCreateTokensError = false;

                    $this->_actionCreateTokensInstance[$i] = $instance;
                    $this->_actionCreateTokensID[$i] = $instance->getID();

                    $this->_metrology->addLog('Action _actionCreateTokens generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_DEBUG); // Log
                } else {
                    // Si ce n'est pas bon.
                    $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                    $this->_metrology->addLog('Action _actionCreateTokens cant generate', Metrology::LOG_LEVEL_ERROR); // Log
                    $this->_actionCreateTokensError = true;
                    $this->_actionCreateTokensErrorMessage = 'Echec de la génération.';

                    // Quitte le processus de génération.
                    break;
                }
            }

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        } else {
            $this->_metrology->addLog('Action _actionCreateTokens not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_actionCreateTokensError = true;
            $this->_actionCreateTokensErrorMessage = 'Non autorisé.';
        }
    }


    /**
     * Crée un lien.
     *
     * @return boolean
     */
    protected function _createLink($signer, $date, $action, $source, $target, $meta, $obfuscate = false)
    {
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);

        // Signe le lien.
        $newLink->sign($signer);

        // Si besoin, obfuscation du lien.
        if ($obfuscate
            && $this->_nebuleInstance->getOption('permitObfuscatedLink')
        ) {
            $newLink->obfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
    }
}
