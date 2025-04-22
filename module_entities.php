<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Actions;
use Nebule\Library\Cache;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\DisplayItem;
use Nebule\Library\Entity;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * Ce module permet de gérer les entités.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleEntities extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::sylabe:module:entities:ModuleName';
    const MODULE_MENU_NAME = '::sylabe:module:entities:MenuName';
    const MODULE_COMMAND_NAME = 'ent';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = '::sylabe:module:entities:ModuleDescription';
    const MODULE_VERSION = '020250307';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2025';
    const MODULE_LOGO = '94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60.sha2.256';
    const MODULE_HELP = '::sylabe:module:entities:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'list',
        'disp',
        'auth',
        'crea',
        'srch',
        'logs',
        'acts',
        'prop',
        'klst',
        'ulst',
        'slst',
        'kblst',
    );
    const MODULE_REGISTERED_ICONS = array(
        '94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0.sha2.256',    // 0 entité (personnage)
        '6d1d397afbc0d2f6866acd1a30ac88abce6a6c4c2d495179504c2dcb09d707c1.sha2.256',    // 1 lien chiffrement/protection
        '7e9726b5aec1b2ab45c70f882f56ea0687c27d0739022e907c50feb87dfaf37d.sha2.256',    // 2 lien mise à jour
        'cc2a24b13d8e03a5de238a79a8adda1a9744507b8870d59448a23b8c8eeb5588.sha2.256',    // 3 lister objets
        '3edf52669e7284e4cefbdbb00a8b015460271765e97a0d6ce6496b11fe530ce1.sha2.256',    // 4 lister entités
        'cba3712128bbdd5243af372884eb647595103bb4c1f1b4d2e2bf62f0eba3d6e6.sha2.256',    // 5 ajouter entité
        '468f2e420371343c58dcdb49c4db9f00b81cce029a5ee1de627b9486994ee199.sha2.256',    // 6 sync entité
        '4de7b15b364506d693ce0cd078398fa38ff941bf58c5f556a68a1dcd7209a2fc.sha2.256',    // 7 messagerie down
        'a16490f9b25b2d3d055e50a2593ceda10c9d1608505e27acf15a5e2ecc314b52.sha2.256',    // 8 messagerie up
        '1c6db1c9b3b52a9b68d19c936d08697b42595bec2f0adf16e8d9223df3a4e7c5.sha2.256',    // 9 clé
        '94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60.sha2.256',    // 10 entité (objet)
        'de62640d07ac4cb2f50169fa361e062ed3595be1e973c55eb3ef623ed5661947.sha2.256',    // 11 verrouillage entité.
    );
    const MODULE_APP_TITLE_LIST = array('::sylabe:module:entities:AppTitle1');
    const MODULE_APP_ICON_LIST = array('94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60.sha2.256');
    const MODULE_APP_DESC_LIST = array('::sylabe:module:entities:AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    const COMMAND_SYNC_KNOWN_ENTITIES = 'synknownent';
    const COMMAND_SYNC_NEBULE_ENTITIES = 'synnebent';
    const DEFAULT_ENTITIES_DISPLAY_NUMBER = 12;
    const DEFAULT_ATTRIBS_DISPLAY_NUMBER = 10;

    private string $_displayEntity;
    private Entity $_displayEntityInstance;
    private string $_hashEntity;
    private Node $_hashEntityObject;
    private string $_hashType;



    protected function _initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_findDisplayEntity();
        $this->_hashType = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/type');
        $this->_hashEntity = $this->_nebuleInstance->getCryptoInstance()->hash('application/x-pem-file');
        $this->_hashEntityObject = $this->_cacheInstance->newNode($this->_hashEntity);
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();
        switch ($hookName) {
            case 'selfMenu':
            case 'typeMenuEntity':
                // Lister des entités connues.
                $hookArray[0]['name'] = '::sylabe:module:entities:KnownEntities';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                $hookArray[0]['desc'] = '::sylabe:module:entities:KnownEntitiesDesc';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;

                // Lister des entités qui me connuaissent.
                $hookArray[1]['name'] = '::sylabe:module:entities:KnownByEntities';
                $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                $hookArray[1]['desc'] = '::sylabe:module:entities:KnownByEntitiesDesc';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;

                // Lister de mes entités.
                $hookArray[2]['name'] = '::sylabe:module:entities:MyEntities';
                $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                $hookArray[2]['desc'] = '::sylabe:module:entities:MyEntitiesDesc';
                $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[8]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;

                // Lister des entités inconnues.
                $hookArray[3]['name'] = '::sylabe:module:entities:UnknownEntities';
                $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                $hookArray[3]['desc'] = '::sylabe:module:entities:UnknownEntitiesDesc';
                $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;

                // Lister des entités spéciales.
                $hookArray[4]['name'] = '::sylabe:module:entities:SpecialEntities';
                $hookArray[4]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                $hookArray[4]['desc'] = '::sylabe:module:entities:SpecialEntitiesDesc';
                $hookArray[4]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[10]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;

                // Voir les propriétés de l'entité.
                $hookArray[5]['name'] = '::sylabe:module:entities:DescriptionEntity';
                $hookArray[5]['icon'] = $this::MODULE_REGISTERED_ICONS[10];
                $hookArray[5]['desc'] = '::sylabe:module:entities:DescriptionEntityDesc';
                $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;

                // Vérifie que la création soit authorisée.
                if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteEntity')
                    && ($this->_unlocked || $this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity'))
                ) {
                    // Créer une nouvelle entité.
                    $hookArray[6]['name'] = '::sylabe:module:entities:CreateEntity';
                    $hookArray[6]['icon'] = $this::MODULE_REGISTERED_ICONS[5];
                    $hookArray[6]['desc'] = '::sylabe:module:entities:CreateEntityDesc';
                    $hookArray[6]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityID();
                }

                // Vérifie que la synchronisation soit authorisée.
                if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeObject')
                    && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeLink')
                    && $this->_unlocked
                ) {
                    // Rechercher une entité.
                    $hookArray[7]['name'] = '::sylabe:module:entities:SearchEntity';
                    $hookArray[7]['icon'] = Display::DEFAULT_ICON_LF;
                    $hookArray[7]['desc'] = '::sylabe:module:entities:SearchEntityDesc';
                    $hookArray[7]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityID();
                }
                break;

            case 'selfMenuObject':
                $instance = $this->_applicationInstance->getCurrentObjectInstance();
                $id = $instance->getID();
                $protected = $instance->getMarkProtected();
                if ($protected) {
                    $id = $instance->getUnprotectedID();
                    $instance = $this->_cacheInstance->newNode($id);
                }

                // Si l'objet est une entité.
                if ($instance->getType('all') == 'application/x-pem-file') {
                    // Voir l'entité.
                    $hookArray[0]['name'] = '::sylabe:module:entities:ShowEntity';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[10];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $id;
                }
                break;

            case 'selfMenuEntity':
                if ($object != $this->_entitiesInstance->getGhostEntityID()) {
                    // Basculer et se connecter avec cette entité.
                    $hookArray[0]['name'] = '::sylabe:module:entities:disp:ConnectWith';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;
                } elseif (!$this->_unlocked) {
                    // Se connecter avec l'entité.
                    $hookArray[0]['name'] = '::sylabe:module:entities:disp:ConnectWith';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;
                } else {
                    // Se déconnecter de l'entité.
                    $hookArray[0]['name'] = '::sylabe:module:entities:disp:Disconnect';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                        . '&' . References::COMMAND_FLUSH;
                }

                // Synchroniser l'entité.
                $hookArray[2]['name'] = '::sylabe:module:entities:SynchronizeEntity';
                $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                $hookArray[2]['desc'] = '';
                $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . Action::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                // Voir l'entité.
                $hookArray[3]['name'] = '::sylabe:module:entities:ShowEntity';
                $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[3]['desc'] = '';
                $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;

                // Recherche si l'objet est marqué.
                if (!$this->_applicationInstance->getMarkObject($object)) {
                    // Ajouter la marque de l'objet.
                    $hookArray[4]['name'] = '::MarkAdd';
                    $hookArray[4]['icon'] = Display::DEFAULT_ICON_MARK;
                    $hookArray[4]['desc'] = '';
                    $hookArray[4]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $object
                        . '&' . Action::DEFAULT_COMMAND_ACTION_MARK_OBJECT . '=' . $object
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                break;

            case '::sylabe:module:entities:DisplayMyEntities':
            case '::sylabe:module:entities:DisplayKnownEntity':
                // Synchroniser les entités connues.
                $hookArray[0]['name'] = '::sylabe:module:entities:SynchronizeKnownEntities';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . Action::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY
                    . '&' . self::COMMAND_SYNC_KNOWN_ENTITIES
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityID()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                break;

            case '::sylabe:module:entities:DisplayNebuleEntity':
                // Synchroniser les entités connues.
                $hookArray[0]['name'] = '::sylabe:module:entities:SynchronizeKnownEntities';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . Action::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY
                    . '&' . self::COMMAND_SYNC_NEBULE_ENTITIES
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityID()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                break;
        }
        return $hookArray;
    }



    public function displayModule(): void
    {
        switch ($this->_displayInstance->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_displayKnownEntitiesList();
                break;
//            case $this::MODULE_REGISTERED_VIEWS[1]:
//                $this->_displayEntityDisp();
//                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayEntityAuth();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayEntityCreate();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayEntitySearch();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayEntityLogs();
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displayEntityActs();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_displayEntityProp();
                break;
            case $this::MODULE_REGISTERED_VIEWS[8]:
                $this->_displayMyEntitiesList();
                break;
            case $this::MODULE_REGISTERED_VIEWS[9]:
                $this->_displayUnknownEntitiesList();
                break;
            case $this::MODULE_REGISTERED_VIEWS[10]:
                $this->_displaySpecialEntitiesList();
                break;
            case $this::MODULE_REGISTERED_VIEWS[11]:
                $this->_displayKnownByEntitiesList();
                break;
            default:
                $this->_displayEntityDisp();
                break;
        }
    }

    public function displayModuleInline(): void
    {
        switch ($this->_displayInstance->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineKnownEntitiesList();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_display_InlineEntitySearch();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlineEntityProp();
                break;
            case $this::MODULE_REGISTERED_VIEWS[8]:
                $this->_display_InlineMyEntitiesList();
                break;
            case $this::MODULE_REGISTERED_VIEWS[9]:
                $this->_display_InlineUnknownEntitiesList();
                break;
            case $this::MODULE_REGISTERED_VIEWS[10]:
                $this->_display_InlineSpecialEntitiesList();
                break;
            case $this::MODULE_REGISTERED_VIEWS[11]:
                $this->_display_InlineKnownByEntitiesList();
                break;
        }
    }

    public function getCSS(): void
    {
        ?>

        <style type="text/css">
            /* Création d'entité */
            input {
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
                margin: 0;
                margin-top: 5px;
                border: 0;
                box-shadow: initial;
                padding: 5px;
                background-origin: border-box;
                text-align: left;
            }

            .sylabeModuleEntityCreate {
                margin-bottom: 60px;
                clear: both;
            }

            .sylabeModuleEntityCreateHeader p {
                font-weight: bold;
                margin: 0;
                padding: 0;
            }

            .sylabeModuleEntityCreateProperty {
                clear: both;
            }

            .sylabeModuleEntityCreatePropertyName {
                float: left;
                width: 25%;
                text-align: right;
                padding-top: 10px;
            }

            .sylabeModuleEntityCreatePropertyEntry {
                margin-top: 2px;
                margin-bottom: 2px;
                float: right;
                width: 70%;
            }

            .sylabeModuleEntityCreateSubmit {
                clear: both;
            }

            .sylabeModuleEntityCreateSubmitEntry {
                width: 100%;
                text-align: center;
            }

            #sylabeModuleEntityCreatePropertyEntryNom {
                background: #ffffff;
            }

            #sylabeModuleEntityCreatePropertyNamePWD1 {
                font-weight: bold;
                text-align: left;
            }

            #sylabeModuleEntityCreatePropertyEntryPWD1 {
                background: #ffffff;
            }

            #sylabeModuleEntityCreatePropertyEntryPWD2 {
                background: #ffffff;
            }

            /* Les logs et acts */
            .sylabeModuleEntityActionText {
                padding: 20px;
                padding-left: 74px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityActionTextList1 {
                padding: 10px;
                padding-left: 74px;
                min-height: 64px;
                background: rgba(230, 230, 230, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityActionTextList2 {
                padding: 10px;
                padding-left: 74px;
                min-height: 64px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityActionDivIcon {
                float: left;
                margin-right: 5px;
            }

            .sylabeModuleEntityActionDate {
                float: right;
                margin-left: 5px;
                font-family: monospace;
            }

            .sylabeModuleEntityActionTitle {
                font-weight: bold;
                font-size: 1.2em;
            }

            .sylabeModuleEntityActionType {
                font-style: italic;
                font-size: 0.8em;
                margin-bottom: 10px;
            }

            .sylabeModuleEntityActionFromTo {
            }

            /* Les propriétés */
            .sylabeModuleEntityDescList1 {
                padding: 5px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityDescList2 {
                padding: 5px;
                background: rgba(230, 230, 230, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityDescError {
                padding: 5px;
                background: rgba(0, 0, 0, 0.3);
                background-origin: border-box;
                clear: both;
            }

            .sylabeModuleEntityDescError .sylabeModuleEntityDescAttrib {
                font-style: italic;
                color: #202020;
            }

            .sylabeModuleEntityDescIcon {
                float: left;
                margin-right: 5px;
            }

            .sylabeModuleEntityDescContent {
                min-width: 300px;
            }

            .sylabeModuleEntityDescDate, .sylabeModuleEntityDescSigner {
                float: right;
                margin-left: 10px;
            }

            .sylabeModuleEntityDescValue {
                font-weight: bold;
            }

            .sylabeModuleEntityDescEmotion {
                font-weight: bold;
            }

            .sylabeModuleEntityDescEmotion img {
                height: 16px;
                width: 16px;
            }

            /* Connexion */
            #sylabeModuleEntityConnect {
                text-align: center;
            }
        </style>
        <?php
    }

    public function headerStyle(): void
    {
    }

    public function actions(): void
    {
        $this->_findSynchronizeEntity();
        $this->_actionSynchronizeEntity();
        $this->_findSearchEntity();
        $this->_actionSearchEntity();
        $this->_findCreateEntity();
        $this->_actionCreateEntity();
    }



    private function _findDisplayEntity(): void
    {
        $this->_displayEntity = $this->_entitiesInstance->getGhostEntityID();
        $this->_displayEntityInstance = $this->_entitiesInstance->getGhostEntityInstance();
    }



    private bool $_synchronizeEntity = false;

    /**
     * Détermine si l'entité doit être synchronisée.
     *
     * @return void
     */
    private function _findSynchronizeEntity(): void
    {
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        $arg = filter_has_var(INPUT_GET, Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY);

        // Vérifie que la création de liens et d'objets est authorisée et que l'action soit demandée.
        if ($arg !== false
            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeLink')
            && $this->_unlocked
        ) {
            $this->_synchronizeEntity = true;
        }
        unset($arg);
    }

    /**
     * Réalise la synchronisation de l'entité.
     * @return void
     * @todo
     *
     */
    private function _actionSynchronizeEntity(): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeLink')
            && $this->_unlocked
            && $this->_synchronizeEntity
        ) {
            // Synchronize l'entité.
            echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNLNK')
                . $this->_displayInstance->displayInlineObjectColorIconName($this->_displayEntityInstance);
            echo ' &nbsp;&nbsp;';
            echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNOBJ')
                . $this->_displayInstance->displayInlineObjectColorIconName($this->_displayEntityInstance);
            echo ' &nbsp;&nbsp;';
            echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNENT')
                . $this->_displayInstance->displayInlineObjectColorIconName($this->_displayEntityInstance);

            // A faire...

        }
    }


    private string $_searchEntityURL = '';
    private string $_searchEntityID = '';
    private ?Node $_searchEntityInstance = null;

    /**
     * Recherche une entité sur ID connu et/ou URL connue.
     *
     * @return void
     */
    private function _findSearchEntity(): void
    {
        $arg_url = trim(filter_input(INPUT_GET, 'srchurl', FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if ($arg_url != ''
            && strlen($arg_url) >= 8
        )
            $this->_searchEntityURL = $arg_url;

        $arg_id = trim(filter_input(INPUT_GET, 'srchid', FILTER_SANITIZE_URL, FILTER_FLAG_ENCODE_LOW));
        if (Node::checkNID($arg_id)
            && $arg_url != 'http://localhost'
            && $arg_url != 'http://127.0.0.1'
            && $arg_url != 'http://localhost/'
            && $arg_url != 'http://127.0.0.1/'
            && $arg_url != 'https://localhost'
            && $arg_url != 'https://127.0.0.1'
            && $arg_url != 'https://localhost/'
            && $arg_url != 'https://127.0.0.1/'
        ) {
            $this->_searchEntityID = $arg_id;
            $this->_searchEntityInstance = $this->_cacheInstance->newNode($arg_url, Cache::TYPE_ENTITY);
        }
    }

    private function _actionSearchEntity(): void
    {
        if ( $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitSynchronizeLink', 'permitSynchronizeObject', 'unlocked'))
            && ($this->_searchEntityID != ''
                || $this->_searchEntityURL != ''
            )
        ) {
            // Recherche l'entité.
            if ($this->_searchEntityID != ''
                && $this->_searchEntityURL != ''
            ) {
                // Si recherche sur ID et URL.
                echo $this->_applicationInstance->getTranslateInstance()->getTranslate('Recherche')
                    . ' ' . $this->_searchEntityURL
                    . ' ' . $this->_displayInstance->displayInlineObjectColorIconName($this->_searchEntityInstance);
            } elseif ($this->_searchEntityID != ''
                && $this->_searchEntityURL == ''
            ) {
                // Sinon recherche sur ID.
                echo $this->_applicationInstance->getTranslateInstance()->getTranslate('Recherche')
                    . ' ' . $this->_displayInstance->displayInlineObjectColorIconName($this->_searchEntityInstance);
            } elseif ($this->_searchEntityID == ''
                && $this->_searchEntityURL != ''
            ) {
                // Sinon recherche sur URL.
                echo $this->_applicationInstance->getTranslateInstance()->getTranslate('Recherche')
                    . ' ' . $this->_searchEntityURL;
            }

            // A faire...

        }
    }


    // Crée une entité.
    private bool $_createEntityAction = false;
    private string $_createEntityID = '0';
    private ?Node $_createEntityInstance = Null;
    private bool $_createEntityError = false;
    private string $_createEntityErrorMessage = '';

    private function _findCreateEntity(): void
    {
        // Regarde si une entité a été créée lors des actions.
        $this->_createEntityAction = $this->_applicationInstance->getActionInstance()->getCreateEntity();
        $this->_createEntityID = $this->_applicationInstance->getActionInstance()->getCreateEntityID();
        $this->_createEntityInstance = $this->_applicationInstance->getActionInstance()->getCreateEntityInstance();
        $this->_createEntityError = $this->_applicationInstance->getActionInstance()->getCreateEntityError();
        $this->_createEntityErrorMessage = $this->_applicationInstance->getActionInstance()->getCreateEntityErrorMessage();
    }

    private function _actionCreateEntity(): void
    {
        $this->_createEntityAction = $this->_applicationInstance->getActionInstance()->getCreateEntity();
        $this->_createEntityID = $this->_applicationInstance->getActionInstance()->getCreateEntityID();
        $this->_createEntityInstance = $this->_applicationInstance->getActionInstance()->getCreateEntityInstance();
        $this->_createEntityError = $this->_applicationInstance->getActionInstance()->getCreateEntityError();
        $this->_createEntityErrorMessage = $this->_applicationInstance->getActionInstance()->getCreateEntityErrorMessage();
    }


    /**
     * Affiche les caractéristiques de l'entité.
     */
    private function _displayEntityDisp(): void
    {
        echo '<div class="layout-list">' . "\n";
        echo '<div class="textListObjects">' . "\n";

        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => true,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            'flagUnlocked' => $this->_unlocked,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'large',
            'displayRatio' => 'short',
        );
        echo $this->_displayInstance->getDisplayObject_DEPRECATED($this->_displayEntityInstance, $param);

        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }


    /**
     * Affiche l'authentification pour une entité.
     */
    private function _displayEntityAuth(): void
    {
        echo '<div class="layoutAloneItem">' . "\n";
        echo '<div class="aloneItemContent">' . "\n";

        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => true,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            //'flagUnlocked' => $this->_unlocked,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => false,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        echo $this->_displayInstance->getDisplayObject_DEPRECATED($this->_displayEntityInstance, $param);

        echo '</div>' . "\n";
        echo '</div>' . "\n";

        $instance = new DisplayTitle($this->_applicationInstance);
        if ($this->_displayEntityInstance->getHavePrivateKeyPassword()
            || ($this->_displayEntity == $this->_entitiesInstance->getGhostEntityID()
                && $this->_unlocked
            )
        ) {
            $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[9]);
            $instance->setTitle('::::entity:unlocked');
        } else {
            $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[11]);
            $instance->setTitle('::::entity:locked');
        }
        $instance->setIcon($icon);
        $instance->display();

        // Extrait les états de tests en warning ou en erreur.
        $idCheck = 'Error';
        if ($this->_applicationInstance->getCheckSecurityAll() == 'OK')
            $idCheck = 'Ok';
        elseif ($this->_applicationInstance->getCheckSecurityAll() == 'WARN')
            $idCheck = 'Warn';
        // Affiche les tests.
        if ($idCheck != 'Ok') {
            $list = array();
            $check = array(
                $this->_applicationInstance->getCheckSecurityBootstrap(),
                $this->_applicationInstance->getCheckSecurityCryptoHash(),
                $this->_applicationInstance->getCheckSecurityCryptoSym(),
                $this->_applicationInstance->getCheckSecurityCryptoAsym(),
                $this->_applicationInstance->getCheckSecuritySign(),
                $this->_applicationInstance->getCheckSecurityURL(),
            );
            $chnam = array('Bootstrap', 'Crypto Hash', 'Crypto Sym', 'Crypto Asym', 'Link Sign', 'URL');
            for ($i = 0; $i < sizeof($check); $i++) {
                $list[$i]['param'] = array(
                    'enableDisplayIcon' => true,
                    'enableDisplayAlone' => false,
                    'displayRatio' => 'short',
                );
                $list[$i]['information'] = $chnam[$i];
                $list[$i]['object'] = '1';
                $list[$i]['param']['informationType'] = 'error';
                if ($check[$i] == 'OK')
                    $list[$i]['param']['informationType'] = 'ok';
                elseif ($check[$i] == 'WARN')
                    $list[$i]['param']['informationType'] = 'warn';
            }
            echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'small');
        } else {
            $param = array(
                'enableDisplayIcon' => true,
                'enableDisplayAlone' => true,
                'informationType' => 'ok',
                'displaySize' => 'small',
                'displayRatio' => 'short',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::SecurityChecks', $param);
        }

        // Affiche le champ de mot de passe.
        if ($this->_displayEntityInstance->getHavePrivateKeyPassword()
            || ($this->_displayEntity == $this->_entitiesInstance->getGhostEntityID()
                && $this->_unlocked
            )
        ) {
            // Propose de la verrouiller.
            $list = array();
            $list[0]['title'] = $this->_translateInstance->getTranslate('::::lock');
            $list[0]['desc'] = $this->_translateInstance->getTranslate('::::entity:unlocked');
            $list[0]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
            $list[0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                . '&' . \Nebule\Library\References::COMMAND_AUTH_ENTITY_LOGOUT
                . '&' . \Nebule\Library\References::COMMAND_FLUSH;
            echo $this->_displayInstance->getDisplayMenuList($list, 'Medium');
        } else {
            if ($idCheck != 'Error') {
                echo '<div class="layoutAloneItem">' . "\n";
                echo '<div class="aloneItemContent">' . "\n";
                $param['displaySize'] = 'small';
                $param['displayRatio'] = 'long';
                $param['objectIcon'] = $this::MODULE_REGISTERED_ICONS[9];
                echo $this->_displayInstance->getDisplayObject_DEPRECATED($this->_entitiesInstance->getGhostEntityPrivateKeyInstance(), $param);
                echo '</div>' . "\n";
                echo '</div>' . "\n";

                echo '<div class="layoutAloneItem">' . "\n";
                echo '<div class="aloneItemContent">' . "\n";

                echo '<div class="layoutObject layoutInformation">' . "\n";
                echo '<div class="objectTitle objectDisplayMediumShort objectTitleMedium objectDisplayShortMedium informationDisplay informationDisplayMedium informationDisplay' . $idCheck . '">' . "\n";

                echo '<div class="objectTitleText objectTitleMediumText objectTitleText0 informationTitleText">' . "\n";

                echo '<div class="objectTitleRefs objectTitleMediumRefs informationTitleRefs informationTitleRefs' . $idCheck . '" id="sylabeModuleEntityConnect">' . "\n";
                echo $this->_translateInstance->getTranslate('::::Password') . "<br />\n";
                echo '</div>' . "\n";

                echo '<div class="objectTitleName objectTitleMediumName informationTitleName informationTitleName' . $idCheck . ' informationTitleMediumName" id="sylabeModuleEntityConnect">' . "\n";
                ?>
                <form method="post"
                      action="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                          . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                          . '&' . \Nebule\Library\References::COMMAND_SELECT_ENTITY . '=' . $this->_displayEntity; ?>">
                    <input type="hidden" name="ent" value="<?php echo $this->_displayEntity; ?>">
                    <input type="password" name="<?php echo \Nebule\Library\References::COMMAND_SELECT_PASSWORD; ?>">
                    <input type="submit" value="<?php echo $this->_translateInstance->getTranslate('::::unlock'); ?>">
                </form>
                <?php
                echo '</div>' . "\n";

                echo '</div>' . "\n";

                echo '</div>' . "\n";
                echo '</div>' . "\n";

                echo '</div>' . "\n";
                echo '</div>' . "\n";
            } else {
                // Affiche un message d'erreur.
                $param = array(
                    'enableDisplayIcon' => true,
                    'enableDisplayAlone' => true,
                    'informationType' => 'error',
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
            }
        }
    }


    /**
     * Affiche les activités vers l'entité.
     */
    private function _displayEntityLogs(): void
    {
        // Entité en cours.
        if ($this->_entitiesInstance->getGhostEntityID() != $this->_entitiesInstance->getConnectedEntityID()) {
            $this->_displayInstance->displayObjectDivHeaderH1($this->_displayEntityInstance, '', $this->_displayEntity);
        }

        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[7]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:ObjectTitle1');
        $instance->setIcon($icon);
        $instance->display();

        // Extrait des propriétés de l'objet.
        $entity = $this->_displayEntity;
        $instance = $this->_displayEntityInstance;
        ?>

        <div class="sylabeModuleEntityActionText">
            <p>
                <?php
                if ($entity == $this->_entitiesInstance->getGhostEntityID() && $this->_unlocked) {
                    echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                        '::sylabe:module:entities:DisplayEntityMessages',
                        $this->_displayInstance->convertInlineObjectColorIconName($instance));
                    $dispWarn = false;
                } else {
                    echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                        '::sylabe:module:entities:DisplayEntityPublicMessages',
                        $this->_displayInstance->convertInlineObjectColorIconName($instance));
                    $dispWarn = true;
                }
                ?>

            </p>
        </div>
        <?php
        // Si besoin, affiche le message d'information.
        if ($dispWarn) {
            $this->_displayInstance->displayMessageInformation_DEPRECATED(
                $this->_translateInstance->getTranslate('::sylabe:module:entities:DisplayEntityPublicMessagesWarning'));
        }
        unset($dispWarn);

        // liste les liens pour l'entité.
        $linksUnprotected = $instance->readLinksFilterFull(
            '',
            '',
            'f',
            $entity,
            '',
            $entity);
        $linksProtected = $instance->readLinksFilterFull(
            '',
            '',
            'k',
            '',
            '',
            $entity);
        $linksObfuscated = $instance->readLinksFilterFull(
            '',
            '',
            'c',
            $entity,
            '',
            '');

        // Reconstitue une seule liste.
        $links = array();
        $i = 0;
        foreach ($linksUnprotected as $link) {
            $links[$i] = $link;
            $i++;
        }
        unset($linksUnprotected);
        foreach ($linksProtected as $link) {
            $links[$i] = $link;
            $i++;
        }
        unset($linksProtected);
        foreach ($linksObfuscated as $link) {
            $links[$i] = $link;
            $i++;
        }
        unset($linksObfuscated);

        // Tri les liens par date.
        if (sizeof($links) != 0) {
            foreach ($links as $n => $t) {
                $linkdate[$n] = $t->getDate();
            }
            array_multisort($linkdate, SORT_STRING, SORT_ASC, $links);
            unset($linkdate, $n, $t);
        }

        if (sizeof($links) != 0) {
            // Indice de fond paire ou impaire.
            $bg = 1;
            // Pour chaque lien.
            foreach ($links as $link) {
                ?>

                <div class="sylabeModuleEntityActionTextList<?php echo $bg; ?>">
                    <?php
                    // Extrait l'action.
                    $action = $link->getParsed()['bl/rl/req'];

                    if ($action == 'c') {
                        // Extrait nom et ID pour affichage.
                        $signer = $link->getParsed()['bs/rs1/eid'];
                        $date = $link->getDate();
                        $object = $link->getParsed()['bl/rl/nid2'];
                        $objectInstance = new Node($this->_nebuleInstance, $object);
                        ?>

                        <div class="sylabeModuleEntityActionDivIcon">
                            <?php $this->_displayInstance->displayUpdateImage(Display::DEFAULT_ICON_LC); ?>
                        </div>
                        <div>
                            <p class="sylabeModuleEntityActionDate">
                                <?php $this->_displayInstance->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="sylabeModuleEntityActionTitle">
                                <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:Obfuscated'); ?>
                            </p>
                            <p class="sylabeModuleEntityActionFromTo">
                                <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:From'); ?>
                                &nbsp;<?php $this->_displayInstance->displayInlineObjectColorIconName($signer); ?><br/>
                            </p>
                        </div>
                        <?php
                        unset($signer, $date, $object, $objectInstance);
                    } elseif ($action == 'k') {
                        // Extrait nom et ID pour affichage.
                        $signer = $link->getParsed()['bs/rs1/eid'];
                        $date = $link->getDate();
                        $object = $link->getParsed()['bl/rl/nid2'];
                        $objectInstance = new Node($this->_nebuleInstance, $object);
                        ?>

                        <div class="sylabeModuleEntityActionDivIcon">
                            <?php $this->_displayInstance->displayUpdateImage(Display::DEFAULT_ICON_LK); ?>
                        </div>
                        <div>
                            <p class="sylabeModuleEntityActionDate">
                                <?php $this->_displayInstance->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="sylabeModuleEntityActionTitle">
                                <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:Protected'); ?>
                            </p>
                            <p class="sylabeModuleEntityActionFromTo">
                                <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:From'); ?>
                                &nbsp;<?php $this->_displayInstance->displayInlineObjectColorIconName($signer); ?><br/>
                            </p>
                        </div>
                        <?php
                        unset($signer, $date, $object, $objectInstance);
                    } elseif ($action == 'f') {
                        // Extrait nom et ID pour affichage.
                        $signer = $link->getParsed()['bs/rs1/eid'];
                        $date = $link->getDate();
                        $object = $link->getParsed()['bl/rl/nid2'];
                        $objectInstance = new Node($this->_nebuleInstance, $object);
                        ?>

                        <div class="sylabeModuleEntityActionDivIcon">
                            <?php $this->_displayInstance->displayObjectColorIcon(
                                $objectInstance, Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $link->getParsed()['bl/rl/nid2']); ?>
                        </div>
                        <div>
                            <p class="sylabeModuleEntityActionDate">
                                <?php $this->_displayInstance->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="sylabeModuleEntityActionTitle">
                                <?php echo $objectInstance->getFullName('all'); ?>
                            </p>
                            <p class="sylabeModuleEntityActionType">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate($objectInstance->getType('all')); ?>
                            </p>
                            <p class="sylabeModuleEntityActionFromTo">
                                <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:From'); ?>
                                &nbsp;<?php $this->_displayInstance->displayInlineObjectColorIconName($signer); ?><br/>
                            </p>
                        </div>
                        <?php
                        unset($signer, $date, $object, $objectInstance);
                    }
                    unset($action);

                    // Permutation de l'indice de fond.
                    $bg = 3 - $bg;
                    ?>

                </div>
                <?php
            }
            unset($link, $bg);
        }
    }


    /**
     * Affiche les activités depuis l'entité.
     */
    private function _displayEntityActs(): void
    {
        // Entité en cours.
        if ($this->_entitiesInstance->getGhostEntityID() != $this->_entitiesInstance->getConnectedEntityID()) {
            $this->_displayInstance->displayObjectDivHeaderH1($this->_displayEntityInstance, '', $this->_displayEntity);
        }

        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[8]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:ObjectTitle2');
        $instance->setIcon($icon);
        $instance->display();

        // Extrait des propriétés de l'objet.
        $id = $this->_applicationInstance->getCurrentObjectInstance()->getID();
        $typemime = $this->_applicationInstance->getCurrentObjectInstance()->getType('all');
        $ispresent = $this->_nebuleInstance->getIoInstance()->checkObjectPresent($id);
        $owned = false;
        ?>

        <div class="sylabeModuleEntityActionText">
            <p>
                <?php
                $dispWarn = false;
                // Vérifie si l'objet courant est une entité, affiche les messages de cette entité.
                if ($typemime == 'application/x-pem-file' && $ispresent) {
                    $entity = $this->_cacheInstance->newNode($id, \Nebule\Library\Cache::TYPE_ENTITY);
                    echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                        '::sylabe:module:entities:DisplayEntityPublicMessages',
                        $this->_displayInstance->convertInlineObjectColorIconName($entity));
                    $dispWarn = true;
                } // Sinon, affiche les messages de l'entité courante.
                else {
                    $entity = $this->_entitiesInstance->getGhostEntityInstance();
                    $id = $this->_entitiesInstance->getGhostEntityID();
                    $owned = true;
                    if ($this->_unlocked) {
                        echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                            '::sylabe:module:entities:DisplayEntityMessages',
                            $this->_displayInstance->convertInlineObjectColorIconName($entity));
                    } else {
                        echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                            '::sylabe:module:entities:DisplayEntityPublicMessages',
                            $this->_displayInstance->convertInlineObjectColorIconName($entity));
                        $dispWarn = true;
                    }
                }
                ?>

            </p>
        </div>
        <?php
        // Si besoin, affiche le message d'information.
        if ($dispWarn) {
            $this->_displayInstance->displayMessageInformation_DEPRECATED(
                $this->_translateInstance->getTranslate('::sylabe:module:entities:DisplayEntityPublicMessagesWarning'));
        }
        unset($dispWarn);

        // liste les liens pour l'entité.
        $links = $entity->getLinksOnFields($entity->getID(), '', 'f', '', '', '');

        if (sizeof($links) != 0) {
            // Indice de fond paire ou impaire.
            $bg = 1;
            // Pour chaque lien.
            foreach ($links as $link) {
                // Extrait nom et ID pour affichage.
                $source = $link->getParsed()['bl/rl/nid1'];
                $date = $link->getDate();
                $object = $link->getParsed()['bl/rl/nid2'];
                $objectInstance = $this->_cacheInstance->newNode($object);

                ?>

                <div class="sylabeModuleEntityActionTextList<?php echo $bg; ?>">
                    <div class="sylabeModuleEntityActionDivIcon">
                        <?php $this->_displayInstance->displayObjectColorIcon($objectInstance); ?>
                    </div>
                    <div>
                        <p class="sylabeModuleEntityActionDate">
                            <?php $this->_displayInstance->displayDate($date);
                            echo "\n"; ?>
                        </p>
                        <p class="sylabeModuleEntityActionTitle">
                            <?php echo $objectInstance->getFullName('all'); ?>
                        </p>
                        <p class="sylabeModuleEntityActionType">
                            <?php echo $objectInstance->getType('all'); ?>
                        </p>
                        <p class="sylabeModuleEntityActionFromTo">
                            <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:To'); ?>
                            &nbsp;<?php $this->_displayInstance->displayInlineObjectColorIconName($source); ?><br/>
                        </p>
                    </div>
                </div>
                <?php

                unset($source, $date, $object, $objectInstance);

                // Permutation de l'indice de fond.
                $bg = 3 - $bg;
            }
            unset($link, $bg);
        }
    }


    /**
     * Affiche la liste des entités.
     *
     * @return void
     */
    private function _displayMyEntitiesList(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:MyEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('myentities');
    }

    /**
     * Affiche en ligne la liste des entités.
     *
     * @return void
     */
    private function _display_InlineMyEntitiesList(): void
    {
        $list = array();
        $i = 0;
        $list[$i]['object'] = $this->_entitiesInstance->getGhostEntityInstance();
        $list[$i]['param'] = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => true,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => false,
            'enableDisplayContent' => false,
            'enableDisplayJS' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        $list[$i]['param']['objectRefs'][0] = $this->_entitiesInstance->getGhostEntityInstance();

        // Marque comme vu.
        //$listOkEntities[$this->_applicationInstance->getCurrentEntity()] = true;
        //$i++;

        // @todo pour les autres entités...

        //$this->_display->displayMessageInformation('A faire...');


        // Affichage
        if ($this->_unlocked) {
            echo $this->_displayInstance->getDisplayHookMenuList('::sylabe:module:entities:DisplayMyEntities');
        }

        // Affiche les entités.
        echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');
    }


    /**
     * Affiche la liste des entités connues.
     *
     * @return void
     */
    private function _displayKnownEntitiesList(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:KnownEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('knownentities');
    }

    private function _display_InlineKnownEntitiesList(): void
    {
        // Liste des entités déjà affichées.
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_entitiesInstance->getGhostEntityID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter);

        // Prépare l'affichage.
        $list = array();
        $i = 0;
        foreach ($links as $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_ENTITY);
            if (!isset($listOkEntities[$link->getParsed()['bl/rl/nid2']])
                && $instance->getType('all') == \Nebule\Library\Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );

                // Marque comme vu.
                $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
                $i++;
            }
        }

        // Affichage.
        echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');
    }


    /**
     * Affiche la liste des entités qui me connaissent.
     *
     * @return void
     */
    private function _displayKnownByEntitiesList(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:KnownByEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('knownentities');
    }

    private function _display_InlineKnownByEntitiesList(): void
    {
        // Liste des entités déjà affichées.
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid2' => $this->_entitiesInstance->getGhostEntityID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter);

        // Prépare l'affichage.
        $list = array();
        $i = 0;
        foreach ($links as $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_ENTITY);
            if (!isset($listOkEntities[$link->getParsed()['bl/rl/nid2']])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );

                // Marque comme vu.
                $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
                $i++;
            }
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');
    }


    /**
     * Affiche la liste des entités inconnues.
     *
     * @return void
     */
    private function _displayUnknownEntitiesList(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:UnknownEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('unknownentities');
    }

    private function _display_InlineUnknownEntitiesList(): void
    {
        // Liste des entités déjà affichées.
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_entitiesInstance->getGhostEntityID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter);
        if (sizeof($links) != 0) {
            foreach ($links as $link) {
                $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
            }
        }

        // Liste les entités dont je suis marqué comme connu.
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid2' => $this->_entitiesInstance->getGhostEntityID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter);

        // Prépare l'affichage.
        if (sizeof($links) != 0) {
            foreach ($links as $link) {
                $listOkEntities[$link->getParsed()['bl/rl/nid1']] = true;
            }
        }

        // Liste toutes les autres entités.
        $links = $this->_hashEntityObject->getLinksOnFields(
            '',
            '',
            'l',
            '',
            $this->_hashEntity,
            $this->_hashType);

        //Prépare l'affichage.
        if (sizeof($links) != 0) {
            $list = array();
            $i = 0;
            foreach ($links as $link) {
                $id = $link->getParsed()['bl/rl/nid1'];
                $instance = $this->_cacheInstance->newNode($id, \Nebule\Library\Cache::TYPE_ENTITY);
                if (!isset($listOkEntities[$id])
                    && $instance->getType('all') == Entity::ENTITY_TYPE
                    && $instance->getIsPublicKey()
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => true,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                    );

                    // Marque comme vu.
                    $listOkEntities[$id] = true;
                    $i++;
                }
            }
            unset($link, $instance, $id);
            // Affichage
            if (sizeof($list) != 0) {
                echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');
            }
            unset($list);
        } else {
            // Pas d'entité.
            $this->_displayInstance->displayMessageInformation_DEPRECATED(
                '::sylabe:module:entities:Display:NoEntity');
        }
    }


    /**
     * Affiche la liste des entités spéciales.
     *
     * @return void
     */
    private function _displaySpecialEntitiesList(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:SpecialEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('specialentities');
    }

    private function _display_InlineSpecialEntitiesList(): void
    {
        // Liste des entités.
        $entities = array();
        $masters = array();
        $signers = array();
        $entities[] = $this->_authoritiesInstance->getPuppetmasterInstance();
        $masters[] = '';
        $signers[] = array();
        foreach ($this->_authoritiesInstance->getSecurityAuthoritiesInstance() as $instance)
        {
            $entities[] = $instance;
            $masters[] = References::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_SECURITE;
            $signers[] = $this->_authoritiesInstance->getSecuritySignersInstance()[$instance->getID()];
        }
        foreach ($this->_authoritiesInstance->getCodeAuthoritiesInstance() as $instance)
        {
            $entities[] = $instance;
            $masters[] = References::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_CODE;
            $signers[] = $this->_authoritiesInstance->getCodeSignersInstance()[$instance->getID()];
        }
        foreach ($this->_authoritiesInstance->getDirectoryAuthoritiesInstance() as $instance)
        {
            $entities[] = $instance;
            $masters[] = References::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_ANNUAIRE;
            $signers[] = $this->_authoritiesInstance->getDirectorySignersInstance()[$instance->getID()];
        }
        foreach ($this->_authoritiesInstance->getTimeAuthoritiesInstance() as $instance)
        {
            $entities[] = $instance;
            $masters[] = References::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_TEMPS;
            $signers[] = $this->_authoritiesInstance->getTimeSignersInstance()[$instance->getID()];
        }
        $entities[] = $this->_entitiesInstance->getServerEntityInstance();
        $masters[] = 'Hôte';
        $signers[] = array();

        // Prépare l'affichage.
        $list = array();
        foreach ($entities as $i => $entity) {
            $list[$i]['object'] = $entity;
            $list[$i]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => false,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => true,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => true,
                'status' => $this->_applicationInstance->getTranslateInstance()->getTranslate($masters[$i]),
                'enableDisplayContent' => false,
                'enableDisplayObjectActions' => false,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
            );
            /*echo 'Entity=' . $entity->getID() . "<br />\n";
            var_dump($signers); echo "<br />\n";
            var_dump($signers[$i]); echo "<br /><br />\n"; ob_flush();
            foreach ($signers[$i] as $j => $eid)
            {
                $e = $this->_nebuleInstance->getCacheInstance()->newNode($eid, \Nebule\Library\Cache::TYPE_ENTITY);
                $signers[$i][$j] = $e;
            }*/
            if (sizeof($signers[$i]) != 0) {
                $list[$i]['param']['enableDisplayRefs'] = true;
                $list[$i]['param']['objectRefs'] = $signers[$i];
            }
        }

        // Affiche les entités.
        echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');
    }


    /**
     * Affiche la création d'une entité.
     */
    private function _displayEntityCreate(): void
    {
        // Si une nouvelle entité vient d'être créée par l'instance des actions.
        if ($this->_createEntityAction) {
            // Prépare l'affichage.
            $list = array();

            if (!$this->_createEntityError && is_a($this->_createEntityInstance, 'Entity')) {
                // Message de bonne création.
                $list[0]['information'] = '::sylabe:module:entities:EntityCreated';
                $list[0]['param']['informationType'] = 'ok';
                $list[0]['param']['displayRatio'] = 'long';

                // Ajoute l'ID public de l'entité.
                $list[1]['object'] = $this->_createEntityInstance;
                $list[1]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => false,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    //'enableDisplayObjectActions' => false,
                    'enableDisplayJS' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'objectIcon' => $this::MODULE_REGISTERED_ICONS[0],
                    'status' => $this->_translateInstance->getTranslate('ID public'),
                );

                // Ajoute l'ID privé de l'entité.
                $privInstance = $this->_cacheInstance->newNode($this->_createEntityInstance->getPrivateKeyOID());
                $list[2]['object'] = $privInstance;
                $list[2]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => false,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    //'enableDisplayObjectActions' => false,
                    'enableDisplayJS' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'objectIcon' => $this::MODULE_REGISTERED_ICONS[9],
                    'status' => $this->_translateInstance->getTranslate('ID prive'),
                );
                unset($privInstance);
            } else {
                // Affiche un message d'erreur.
                $list[0]['information'] = $this->_translateInstance->getTranslate('::sylabe:module:entities:EntityNotCreated') . ' : "' . $this->_createEntityErrorMessage . '"';
                $list[0]['param']['informationType'] = 'error';
                $list[0]['param']['displayRatio'] = 'long';
            }

            // Affiche le message et les objets créés.
            echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');
            unset($list);
        }

        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[5]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:CreateEntity');
        $instance->setIcon($icon);
        $instance->display();

        // Vérifie que la création soit authorisée.
        if ( $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitWriteEntity'))
            && ($this->_unlocked
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity')
            )
        ) {
            ?>

            <div class="layoutAloneItem">
                <div class="aloneTextItemContent">
                    <form method="post"
                          action="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                              . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                              . '&' . Action::DEFAULT_COMMAND_ACTION_CREATE_ENTITY
                              . '&' . \Nebule\Library\References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityID()
                              . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                        <div class="sylabeModuleEntityCreate" id="sylabeModuleEntityCreateNames">
                            <div class="sylabeModuleEntityCreateHeader">
                                <p>
                                    <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:CreateEntityNommage'); ?>

                                </p>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/prefix'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo Action::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PREFIX; ?>"
                                       size="10" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryPrefix"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/prenom'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo Action::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_FIRSTNAME; ?>"
                                       size="20" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryPrenom"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/surnom'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo Action::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NIKENAME; ?>"
                                       size="10" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntrySurnom"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/nom'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo Action::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NAME; ?>"
                                       size="20" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryNom"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/suffix'); ?>
                                </div>
                                <input type="text"
                                       name="<?php echo Action::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_SUFFIX; ?>"
                                       size="10" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntrySuffix"/>
                            </div>
                        </div>
                        <div class="sylabeModuleEntityCreate" id="sylabeModuleEntityCreatePassword">
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName"
                                     id="sylabeModuleEntityCreatePropertyNamePWD1">
                                    <?php echo $this->_translateInstance->getTranslate('::::Password'); ?>

                                </div>
                                <input type="password"
                                       name="<?php echo Action::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD1; ?>"
                                       size="30" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryPWD1"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName"
                                     id="sylabeModuleEntityCreatePropertyNamePWD2">
                                    <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:CreateEntityConfirm'); ?>

                                </div>
                                <input type="password"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD2; ?>"
                                       size="30" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryPWD2"/>
                            </div>
                        </div>
                        <div class="sylabeModuleEntityCreate" id="sylabeModuleEntityCreateOther">
                            <div class="sylabeModuleEntityCreateHeader">
                                <p>
                                    <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:CreateEntityOther'); ?>

                                </p>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:CreateEntityAlgorithm'); ?>

                                </div>
                                <select
                                    name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_ALGORITHM; ?>"
                                    class="sylabeModuleEntityCreatePropertyEntry">
                                    <option value="<?php echo $this->_configurationInstance->getOptionAsString('cryptoAsymmetricAlgorithm'); ?>"
                                            selected>
                                        <?php echo $this->_nebuleInstance->getConfigurationInstance()->getOptionAsString('cryptoAsymmetricAlgorithm'); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/entite/type'); ?>

                                </div>
                                <select
                                    name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_TYPE; ?>"
                                    class="sylabeModuleEntityCreatePropertyEntry">
                                    <option value="undef" selected>
                                        <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:CreateEntityTypeUndefined'); ?>

                                    </option>
                                    <option value="human">
                                        <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:CreateEntityTypeHuman'); ?>

                                    </option>
                                    <option value="robot">
                                        <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:CreateEntityTypeRobot'); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:CreateEntityAutonomy'); ?>

                                </div>
                                <select
                                    name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_AUTONOMY; ?>"
                                    class="sylabeModuleEntityCreatePropertyEntry">
                                    <option value="y" selected>
                                        <?php echo $this->_translateInstance->getTranslate('::::yes'); ?>

                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="sylabeModuleEntityCreateSubmit">
                            <input type="submit"
                                   value="<?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:CreateTheEntity'); ?>"
                                   class="sylabeModuleEntityCreateSubmitEntry"/>
                        </div>
                    </form>
                </div>
            </div>
            <?php
        } else {
            $this->_displayInstance->displayMessageWarning_DEPRECATED('::sylabe:module:entities:CreateEntityNotAllowed');
        }
    }


    /**
     * Affiche la recherche d'une entité.
     */
    private function _displayEntitySearch(): void
    {
        // Affiche la création d'une entité.
        $iconNID = $this->_cacheInstance->newNode(Display::DEFAULT_ICON_LF);
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:SearchEntity');
        $instance->setIcon($iconNID);
        $instance->display();

        // Vérifie que la création soit authorisée.
        if ( $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitSynchronizeLink', 'permitSynchronizeObject', 'unlocked')) ) {
            ?>
            <div class="text">
                <p>


                <form method="get" action="">
                    <input type="hidden" name="mod" value="ent">
                    <input type="hidden" name="view" value="srch">
                    <input type="hidden" name="obj"
                           value="<?php echo $this->_applicationInstance->getCurrentObjectID(); ?>">
                    <input type="hidden" name="ent"
                           value="<?php echo $this->_entitiesInstance->getGhostEntityID(); ?>">
                    <table border="0" padding="2px">
                        <tr>
                            <td align="right"><?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:Search:URL') ?>
                            </td>
                            <td>:</td>
                            <td><input type="text" name="srchurl" size="80"
                                       value="<?php echo $this->_searchEntityURL; ?>"></td>
                        </tr>
                        <tr>
                            <td align="right"><?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:Search:AndOr') ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align="right"><?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:Search:ID') ?>
                            </td>
                            <td>:</td>
                            <td><input type="text" name="srchid" size="80"
                                       value="<?php echo $this->_searchEntityID; ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><input type="submit"
                                       value="<?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:Search:Submit'); ?>">
                            </td>
                        </tr>
                    </table>
                </form>
                <?php

                ?>

                </p>
            </div>
            <?php
            $this->_displayInstance->displayMessageInformation_DEPRECATED('::sylabe:module:entities:SearchEntityLongTime');
        } else {
            $this->_displayInstance->displayMessageWarning_DEPRECATED('::sylabe:module:entities:SearchEntityNotAllowed');
        }
    }


    private function _display_InlineEntitySearch(): void
    {
    }


    /**
     * Affiche les propriétés de l'entité.
     */
    private function _displayEntityProp(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[3]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::sylabe:module:entities:Desc:AttribsTitle');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('properties');
    }

    private function _display_InlineEntityProp(): void
    {
        // Préparation de la gestion de l'affichage par parties.
        $startLinkSigne = $this->_nebuleInstance->getDisplayNextObject();
        $displayCount = 0;
        $okDisplay = false;
        if ($startLinkSigne == '') {
            $okDisplay = true;
        }
        $displayNext = false;
        $nextLinkSigne = '';

        $list = array(); // @todo refaire la liste d'affichage avec les nouvelles fonctions.
        $i = 0;

        // Recherche si l'objet a une mise à jour.
        $update = $this->_displayEntityInstance->getUpdateNID(false, false);
        if ($update != $this->_displayEntity) {
            // A affiner...
            //
            $this->_displayInstance->displayMessageWarning_DEPRECATED(
                $this->_translateInstance->getTranslate('::sylabe:module:objects:warning:ObjectHaveUpdate'));
        }
        unset($update);

        // Liste des attributs, càd des liens de type l.
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid3' => $this->_displayEntityInstance->getID(),
            'bl/rl/nid4' => '',
        );
        $this->_displayEntityInstance->getLinks($links, $filter);

        // Affichage des attributs de base.
        if (sizeof($links) != 0) {
            // Indice de fond paire ou impaire.
            $bg = 1;
            $attribList = References::RESERVED_OBJECTS_LIST;
            $emotionsList = array(
                $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE) => References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE,
                $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE) => References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE,
                $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR) => References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR,
                $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE) => References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE,
                $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE) => References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE,
                $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT) => References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT,
                $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE) => References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE,
                $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET) => References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET,
            );
            $emotionsIcons = array(
                References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE1,
                References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE1,
                References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR1,
                References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE1,
                References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE1,
                References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT1,
                References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE1,
                References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET1,
            );

            foreach ($links as $i => $link) {
                // Vérifie si la signature de lien est celle de départ de l'affichage.
                if ($link->getSigneValue() == $startLinkSigne) {
                    $okDisplay = true;
                }

                // Enregistre le message suivant à afficher si le compteur d'affichage est dépassé.
                if ($displayNext
                    && $nextLinkSigne == ''
                ) {
                    $nextLinkSigne = $link->getSigneValue();
                }

                // Si l'affichage est permit.
                if ($okDisplay) {
                    // Extraction des attributs.
                    $action = $link->getParsed()['bl/rl/req'];
                    $showAttrib = false;
                    $showEmotion = false;
                    $hashAttrib = $link->getParsed()['bl/rl/nid3'];
                    $attribName = '';
                    $attribTraduction = '';
                    $hashValue = $link->getParsed()['bl/rl/nid2'];
                    $value = '';
                    $attribValue = '';
                    $emotion = '';

                    // Si action type l.
                    if ($action == 'l') {
                        // Extrait le nom.
                        if ($hashAttrib != '0'
                            && $hashAttrib != ''
                            && $hashValue != '0'
                            && $hashValue != ''
                        ) {
                            $attribInstance = $this->_cacheInstance->newNode($hashAttrib);
                            $attribName = $attribInstance->readOneLineAsText();
                            unset($attribInstance);
                        }

                        // Vérifie si l'attribut est dans la liste des objets réservés à afficher.
                        if ($attribName != '') {
                            foreach ($attribList as $attribItem) {
                                if ($attribItem == $attribName) {
                                    $showAttrib = true;
                                    break;
                                }
                            }
                        }
                    } // Si action de type f, vérifie si l'attribut est dans la liste des émotions à afficher.
                    elseif ($action == 'f'
                        && $hashValue != '0'
                    ) {
                        foreach ($emotionsList as $item => $emotionItem) {
                            if ($item == $hashValue) {
                                $showEmotion = true;
                                $emotion = $emotionItem;
                                break;
                            }
                        }
                    }

                    // Extrait la valeur.
                    if ($showAttrib
                        && $attribName != ''
                    ) {
                        $valueInstance = $this->_cacheInstance->newNode($hashValue);
                        $attribValue = $valueInstance->readOneLineAsText();
                        unset($valueInstance);
                        // Vérifie la valeur.
                        if ($attribValue == null) {
                            $attribValue = $this->_applicationInstance->getTranslateInstance()->getTranslate('::noContent');
                        }
                    }

                    if ($showAttrib) {
                        // Affiche l'attribut.
                        ?>

                        <div class="sylabeModuleEntityDescList<?php echo $bg; ?>">
                            <?php
                            if ($this->_applicationInstance->getApplicationModulesInstance()->getIsModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleEntityDescIcon">
                                    <?php $this->_displayInstance->displayHypertextLink($this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')::MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . Display::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleEntityDescDate"><?php $this->_displayInstance->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleEntityDescSigner"><?php $this->_displayInstance->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleEntityDescContent">
                                <span class="sylabeModuleEntityDescAttrib"><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate($attribName); ?></span>
                                =
                                <span class="sylabeModuleEntityDescValue"><?php echo $attribValue; ?></span>
                            </div>
                        </div>
                        <?php
                    } elseif ($showEmotion) {
                        // Affiche l'émotion.
                        ?>

                        <div class="sylabeModuleEntityDescList<?php echo $bg; ?>">
                            <?php
                            if ($this->_applicationInstance->getApplicationModulesInstance()->getIsModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleEntityDescIcon">
                                    <?php $this->_displayInstance->displayHypertextLink($this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')::MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . Display::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleEntityDescDate"><?php $this->_displayInstance->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleEntityDescSigner"><?php $this->_displayInstance->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleEntityDescContent">
		<span class="sylabeModuleEntityDescEmotion">
			<?php $this->_displayInstance->displayReferenceImage($emotionsIcons[$emotion], $emotionsList[$hashValue]); ?>
            <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate($emotionsList[$hashValue]); ?>
		</span>
                            </div>
                        </div>
                        <?php
                    } elseif ($action == 'l') {
                        // Affiche une erreur si la propriété n'est pas lisible.
                        ?>

                        <div class="sylabeModuleEntityDescError">
                            <?php
                            if ($this->_applicationInstance->getApplicationModulesInstance()->getIsModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleEntityDescIcon">
                                    <?php $this->_displayInstance->displayHypertextLink($this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')::MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . Display::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>
                                    &nbsp;
                                    <?php $this->_displayInstance->displayInlineIconFace('DEFAULT_ICON_IWARN'); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleEntityDescDate"><?php $this->_displayInstance->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleEntityDescSigner"><?php $this->_displayInstance->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleEntityDescContent">
                                <span class="sylabeModuleEntityDescAttrib"><?php echo $this->_translateInstance->getTranslate('::sylabe:module:entities:AttribNotDisplayable'); ?></span>
                            </div>
                        </div>
                        <?php
                    } else {
                        // Si non affichable et lien de type autre que l, annule la permutation de l'indice de fond.
                        $bg = 3 - $bg;
                    }

                    // Actualise le compteur d'affichage.
                    $displayCount++;
                    if ($displayCount >= self::DEFAULT_ATTRIBS_DISPLAY_NUMBER) {
                        $okDisplay = false;
                        $displayNext = true;
                    }
                }

                // Permutation de l'indice de fond.
                $bg = 3 - $bg;
            }

            // Affiche au besoin le bouton pour afficher les objets suivants.
            if ($displayNext
                && $nextLinkSigne != ''
            ) {
                $url = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityID()
                    . '&' . Displays::DEFAULT_INLINE_COMMAND . '&' . Displays::DEFAULT_INLINE_CONTENT_COMMAND . '=properties'
                    . '&' . Displays::DEFAULT_NEXT_COMMAND . '=' . $nextLinkSigne;
                $this->_displayInstance->displayButtonNextObject($nextLinkSigne, $url, $this->_applicationInstance->getTranslateInstance()->getTranslate('::seeMore'));
            }
            unset($links);
        }
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::sylabe:module:entities:ModuleName' => 'Module des entités',
            '::sylabe:module:entities:MenuName' => 'Entités',
            '::sylabe:module:entities:ModuleDescription' => 'Module de gestion des entités.',
            '::sylabe:module:entities:ModuleHelp' => "Ce module permet de voir les entités, de gérer les relations et de changer l'entité en cours d'utilisation.",
            '::sylabe:module:entities:AppTitle1' => 'Entités',
            '::sylabe:module:entities:AppDesc1' => 'Gestion des entités.',
            '::sylabe:module:entities:display:ListEntities' => 'Lister les entités',
            '::sylabe:module:entities:EntityCreated' => 'Nouvelle entité créée',
            '::sylabe:module:entities:EntityNotCreated' => "La nouvelle entité n'a pas pu être créée",
            '::sylabe:module:entities:ShowEntity' => "L'entité",
            '::sylabe:module:entities:DescriptionEntity' => "L'entité",
            '::sylabe:module:entities:DescriptionEntityDesc' => "Description de l'entité.",
            '::sylabe:module:entities:MyEntities' => 'Mes entités',
            '::sylabe:module:entities:MyEntitiesDesc' => 'Toutes les entités sous contrôle.',
            '::sylabe:module:entities:MyEntitiesHelp' => "La liste des entités sous contrôle, c'est à dire avec lequelles ont peut instantanément basculer.",
            '::sylabe:module:entities:KnownEntities' => 'Entités connues',
            '::sylabe:module:entities:KnownEntitiesDesc' => 'Toutes les entités connues.',
            '::sylabe:module:entities:KnownEntitiesHelp' => "La liste des entités que l'on connait, amies ou pas.",
            '::sylabe:module:entities:KnownEntity' => 'Je connais cette entité',
            '::sylabe:module:entities:SpecialEntities' => 'entités spéciales',
            '::sylabe:module:entities:SpecialEntitiesDesc' => 'Les entités spécifiques à <i>nebule</i>.',
            '::sylabe:module:entities:SpecialEntitiesHelp' => 'La liste des entités spécifiques à <i>nebule</i>.',
            '::sylabe:module:entities:UnknownEntities' => 'Entités inconnues',
            '::sylabe:module:entities:UnknownEntitiesDesc' => 'Toutes les autres entités, non connues.',
            '::sylabe:module:entities:UnknownEntitiesHelp' => "La liste des entités que l'on connait pas.",
            '::sylabe:module:entities:KnownByEntities' => 'Connu de ces entités',
            '::sylabe:module:entities:KnownByEntitiesDesc' => 'Toutes les entités qui me connaissent.',
            '::sylabe:module:entities:KnownByEntitiesHelp' => "La liste des entités qui me connaissent.",
            '::sylabe:module:entities:SynchronizeEntity' => 'Synchroniser',
            '::sylabe:module:entities:SynchronizeKnownEntities' => 'Synchroniser toutes les entités',
            '::sylabe:module:entities:AttribNotDisplayable' => 'Propriété non affichable !',
            '::sylabe:module:entities:ListEntities' => 'Lister',
            '::sylabe:module:entities:ListEntitiesDesc' => 'Lister les entités',
            '::sylabe:module:entities:CreateEntity' => 'Créer',
            '::sylabe:module:entities:CreateEntityNommage' => 'Nommage',
            '::sylabe:module:entities:CreateEntityConfirm' => 'Confirmation',
            '::sylabe:module:entities:CreateEntityOther' => 'Autre',
            '::sylabe:module:entities:CreateEntityAlgorithm' => 'Algorithme',
            '::sylabe:module:entities:CreateEntityTypeUndefined' => '(Indéfini)',
            '::sylabe:module:entities:CreateEntityTypeHuman' => 'Humain',
            '::sylabe:module:entities:CreateEntityTypeRobot' => 'Robot',
            '::sylabe:module:entities:CreateEntityAutonomy' => 'Entité indépendante',
            '::sylabe:module:entities:CreateTheEntity' => "Créer l'entité",
            '::sylabe:module:entities:CreateEntityDesc' => 'Créer une entité.',
            '::sylabe:module:entities:CreateEntityNotAllowed' => "La création d'entité est désactivée !",
            '::sylabe:module:entities:SearchEntity' => 'Chercher',
            '::sylabe:module:entities:SearchEntityDesc' => 'Rechercher une entité.',
            '::sylabe:module:entities:SearchEntityHelp' => 'Rechercher une entité connue.',
            '::sylabe:module:entities:SearchEntityNotAllowed' => "La recherche d'entité est désactivée !",
            '::sylabe:module:entities:SearchEntityLongTime' => 'La recherche sur identifiant uniquement peut prendre du temps...',
            '::sylabe:module:entities:Search:URL' => 'Adresse de présence',
            '::sylabe:module:entities:Search:AndOr' => 'et/ou',
            '::sylabe:module:entities:Search:ID' => 'Identifiant',
            '::sylabe:module:entities:Search:Submit' => 'Rechercher',
            '::sylabe:module:entities:disp:ConnectWith' => 'Se connecter avec cette entité',
            '::sylabe:module:entities:disp:Disconnect' => "Verrouiller l'entité",
            '::sylabe:module:entities:puppetmaster' => "l'entité de référence de <i>nebule</i>, le maître des clés.",
            '::sylabe:module:entities:SecurityMaster' => "l'entité maîtresse de la sécurité.",
            '::sylabe:module:entities:CodeMaster' => "l'entité maîtresse du code.",
            '::sylabe:module:entities:DirectoryMaster' => "l'entité maîtresse de l'annuaire.",
            '::sylabe:module:entities:TimeMaster' => "l'entité maîtresse du temps.",
            '::sylabe:module:entities:From' => 'De',
            '::sylabe:module:entities:To' => 'Pour',
            '::sylabe:module:entities:DisplayEntityMessages' => 'Liste des messages de %s.',
            '::sylabe:module:entities:DisplayEntityPublicMessages' => 'Liste des messages publics de %s.',
            '::sylabe:module:entities:DisplayEntityPublicMessagesWarning' => 'Les messages protégés ou dissimulés ne sont pas accessibles.',
            '::sylabe:module:entities:AuthLockHelp' => 'Se déconnecter...',
            '::sylabe:module:entities:AuthUnlockHelp' => 'Se connecter...',
            '::sylabe:module:entities:Protected' => 'Message protégé.',
            '::sylabe:module:entities:Obfuscated' => 'Message dissimulé.',
            '::sylabe:module:entities:Desc:AttribsTitle' => "Propriétés de l'objet",
            '::sylabe:module:entities:Desc:AttribsDesc' => "Toutes les propriétés de l'objet de l'entité.",
            '::sylabe:module:entities:Desc:AttribsHelp' => "Toutes les propriétés de l'objet.",
            '::sylabe:module:entities:Desc:Attrib' => 'Propriété',
            '::sylabe:module:entities:Desc:Value' => 'Valeur',
            '::sylabe:module:entities:Desc:Signer' => 'Emetteur',
            '::sylabe:module:entities:Display:NoEntity' => "Pas d'entité à afficher.",
        ],
        'en-en' => [
            '::sylabe:module:entities:ModuleName' => 'Entities module',
            '::sylabe:module:entities:MenuName' => 'Entities',
            '::sylabe:module:entities:ModuleDescription' => 'Module to manage entities.',
            '::sylabe:module:entities:ModuleHelp' => 'This module permit to see entites, to manage related and to change of corrent entity.',
            '::sylabe:module:entities:AppTitle1' => 'Entities',
            '::sylabe:module:entities:AppDesc1' => 'Manage entities.',
            '::sylabe:module:entities:display:SynchronizeEntities' => 'Synchronize current entity',
            '::sylabe:module:entities:display:ListEntities' => 'Show list of entities',
            '::sylabe:module:entities:EntityCreated' => 'New entity created',
            '::sylabe:module:entities:EntityNotCreated' => "The new entity can't be created",
            '::sylabe:module:entities:ShowEntity' => 'The entity',
            '::sylabe:module:entities:DescriptionEntity' => 'This entity',
            '::sylabe:module:entities:DescriptionEntityDesc' => 'About this entity.',
            '::sylabe:module:entities:MyEntities' => 'My entities',
            '::sylabe:module:entities:MyEntitiesDesc' => 'All entities under control.',
            '::sylabe:module:entities:MyEntitiesHelp' => 'The list of all entities under control.',
            '::sylabe:module:entities:KnownEntities' => 'Known entities',
            '::sylabe:module:entities:KnownEntitiesDesc' => 'All known entities.',
            '::sylabe:module:entities:KnownEntitiesHelp' => 'The list of all entities we known, friends or not.',
            '::sylabe:module:entities:KnownEntity' => 'I known this entity',
            '::sylabe:module:entities:SpecialEntities' => 'Specials entities',
            '::sylabe:module:entities:SpecialEntitiesDesc' => 'Specifics entities to <i>nebule</i>.',
            '::sylabe:module:entities:SpecialEntitiesHelp' => 'The list of specifics entities to <i>nebule</i>.',
            '::sylabe:module:entities:UnknownEntities' => 'Unknown entities',
            '::sylabe:module:entities:UnknownEntitiesDesc' => 'All unknown entities.',
            '::sylabe:module:entities:UnknownEntitiesHelp' => 'The list of all others entities.',
            '::sylabe:module:entities:KnownByEntities' => 'Known by entities',
            '::sylabe:module:entities:KnownByEntitiesDesc' => 'All known by entities.',
            '::sylabe:module:entities:KnownByEntitiesHelp' => 'The list of all entities who known me.',
            '::sylabe:module:entities:SynchronizeEntity' => 'Synchronize',
            '::sylabe:module:entities:SynchronizeKnownEntities' => 'Synchronize all entities',
            '::sylabe:module:entities:AttribNotDisplayable' => 'Attribut not displayable!',
            '::sylabe:module:entities:ListEntities' => 'List',
            '::sylabe:module:entities:ListEntitiesDesc' => 'Show list of entities',
            '::sylabe:module:entities:CreateEntity' => 'Create',
            '::sylabe:module:entities:CreateEntityNommage' => 'Naming',
            '::sylabe:module:entities:CreateEntityConfirm' => 'Confirm',
            '::sylabe:module:entities:CreateEntityOther' => 'Other',
            '::sylabe:module:entities:CreateEntityAlgorithm' => 'Algorithm',
            '::sylabe:module:entities:CreateEntityTypeUndefined' => '(Undefined)',
            '::sylabe:module:entities:CreateEntityTypeHuman' => 'Human',
            '::sylabe:module:entities:CreateEntityTypeRobot' => 'Robot',
            '::sylabe:module:entities:CreateEntityAutonomy' => 'Independent entity',
            '::sylabe:module:entities:CreateTheEntity' => 'Create the entity',
            '::sylabe:module:entities:CreateEntityDesc' => 'Create entity.',
            '::sylabe:module:entities:CreateEntityNotAllowed' => 'Entity creation is disabled!',
            '::sylabe:module:entities:SearchEntity' => 'Search',
            '::sylabe:module:entities:SearchEntityDesc' => 'Search entity.',
            '::sylabe:module:entities:SearchEntityHelp' => 'Search a known entity.',
            '::sylabe:module:entities:SearchEntityNotAllowed' => 'Entity search is disabled!',
            '::sylabe:module:entities:SearchEntityLongTime' => 'The search on identifier only can take some time...',
            '::sylabe:module:entities:Search:URL' => 'Address of localisation',
            '::sylabe:module:entities:Search:AndOr' => 'and/or',
            '::sylabe:module:entities:Search:ID' => 'Identifier',
            '::sylabe:module:entities:Search:Submit' => 'Submit',
            '::sylabe:module:entities:disp:ConnectWith' => 'Connect with this entity',
            '::sylabe:module:entities:disp:Disconnect' => 'Lock entity',
            '::sylabe:module:entities:puppetmaster' => 'The reference entity of <i>nebule</i>, the master of keys.',
            '::sylabe:module:entities:SecurityMaster' => 'The master entity of security.',
            '::sylabe:module:entities:CodeMaster' => 'The master entity of code.',
            '::sylabe:module:entities:DirectoryMaster' => 'The master entity of directory.',
            '::sylabe:module:entities:TimeMaster' => 'The master entity of time.',
            '::sylabe:module:entities:From' => 'From',
            '::sylabe:module:entities:To' => 'To',
            '::sylabe:module:entities:DisplayEntityMessages' => 'List of messages for %s.',
            '::sylabe:module:entities:DisplayEntityPublicMessages' => 'List of public messages for %s.',
            '::sylabe:module:entities:DisplayEntityPublicMessagesWarning' => 'Protected ou hidden messages are not availables.',
            '::sylabe:module:entities:AuthLockHelp' => 'Unconnecting...',
            '::sylabe:module:entities:AuthUnlockHelp' => 'Connecting...',
            '::sylabe:module:entities:Protected' => 'Message protected.',
            '::sylabe:module:entities:Obfuscated' => 'Message obfuscated.',
            '::sylabe:module:entities:Desc:AttribsTitle' => "Object's attributs",
            '::sylabe:module:entities:Desc:AttribsDesc' => "All attributs of the entity's object.",
            '::sylabe:module:entities:Desc:AttribsHelp' => 'All attributs of the object.',
            '::sylabe:module:entities:Desc:Attrib' => 'Attribut',
            '::sylabe:module:entities:Desc:Value' => 'Value',
            '::sylabe:module:entities:Desc:Signer' => 'Sender',
            '::sylabe:module:entities:Display:NoEntity' => 'No entity to display.',
        ],
        'es-co' => [
            '::sylabe:module:entities:ModuleName' => 'Entities module',
            '::sylabe:module:entities:MenuName' => 'Entities',
            '::sylabe:module:entities:ModuleDescription' => 'Module to manage entities.',
            '::sylabe:module:entities:ModuleHelp' => 'This module permit to see entites, to manage related and to change of corrent entity.',
            '::sylabe:module:entities:AppTitle1' => 'Entities',
            '::sylabe:module:entities:AppDesc1' => 'Manage entities.',
            '::sylabe:module:entities:display:SynchronizeEntities' => 'Synchronize current entity',
            '::sylabe:module:entities:display:ListEntities' => 'Show list of entities',
            '::sylabe:module:entities:EntityCreated' => 'New entity created',
            '::sylabe:module:entities:EntityNotCreated' => "The new entity can't be created",
            '::sylabe:module:entities:ShowEntity' => 'The entity',
            '::sylabe:module:entities:DescriptionEntity' => 'This entity',
            '::sylabe:module:entities:DescriptionEntityDesc' => 'About this entity.',
            '::sylabe:module:entities:MyEntities' => 'My entities',
            '::sylabe:module:entities:MyEntitiesDesc' => 'All entities under control.',
            '::sylabe:module:entities:MyEntitiesHelp' => 'The list of all entities under control.',
            '::sylabe:module:entities:KnownEntities' => 'Known entities',
            '::sylabe:module:entities:KnownEntitiesDesc' => 'All known entities.',
            '::sylabe:module:entities:KnownEntitiesHelp' => 'The list of all entities we known, friends or not.',
            '::sylabe:module:entities:KnownEntity' => 'I known this entity',
            '::sylabe:module:entities:SpecialEntities' => 'Specials entities',
            '::sylabe:module:entities:SpecialEntitiesDesc' => 'Specifics entities to <i>nebule</i>.',
            '::sylabe:module:entities:SpecialEntitiesHelp' => 'The list of specifics entities to <i>nebule</i>.',
            '::sylabe:module:entities:UnknownEntities' => 'Unknown entities',
            '::sylabe:module:entities:UnknownEntitiesDesc' => 'All unknown entities.',
            '::sylabe:module:entities:UnknownEntitiesHelp' => 'The list of all others entities.',
            '::sylabe:module:entities:KnownByEntities' => 'Known by entities',
            '::sylabe:module:entities:KnownByEntitiesDesc' => 'All known by entities.',
            '::sylabe:module:entities:KnownByEntitiesHelp' => 'The list of all entities who known me.',
            '::sylabe:module:entities:SynchronizeEntity' => 'Synchronize',
            '::sylabe:module:entities:SynchronizeKnownEntities' => 'Synchronize all entities',
            '::sylabe:module:entities:AttribNotDisplayable' => 'Attribut not displayable!',
            '::sylabe:module:entities:ListEntities' => 'List',
            '::sylabe:module:entities:ListEntitiesDesc' => 'Show list of entities',
            '::sylabe:module:entities:CreateEntity' => 'Create',
            '::sylabe:module:entities:CreateEntityNommage' => 'Naming',
            '::sylabe:module:entities:CreateEntityConfirm' => 'Confirm',
            '::sylabe:module:entities:CreateEntityOther' => 'Otro',
            '::sylabe:module:entities:CreateEntityAlgorithm' => 'Algoritmo',
            '::sylabe:module:entities:CreateEntityTypeUndefined' => '(Undefined)',
            '::sylabe:module:entities:CreateEntityTypeHuman' => 'Humano',
            '::sylabe:module:entities:CreateEntityTypeRobot' => 'Robot',
            '::sylabe:module:entities:CreateEntityAutonomy' => 'Entidad independiente',
            '::sylabe:module:entities:CreateTheEntity' => 'Create the entity',
            '::sylabe:module:entities:CreateEntityDesc' => 'Create entity.',
            '::sylabe:module:entities:CreateEntityNotAllowed' => 'Entity creation is disabled!',
            '::sylabe:module:entities:SearchEntity' => 'Search',
            '::sylabe:module:entities:SearchEntityDesc' => 'Search entity.',
            '::sylabe:module:entities:SearchEntityHelp' => 'Search a known entity.',
            '::sylabe:module:entities:SearchEntityNotAllowed' => 'Entity search is disabled!',
            '::sylabe:module:entities:SearchEntityLongTime' => 'The search on identifier only can take some time...',
            '::sylabe:module:entities:Search:URL' => 'Address of localisation',
            '::sylabe:module:entities:Search:AndOr' => 'y/o',
            '::sylabe:module:entities:Search:ID' => 'Identifier',
            '::sylabe:module:entities:Search:Submit' => 'Submit',
            '::sylabe:module:entities:disp:ConnectWith' => 'Connect with this entity',
            '::sylabe:module:entities:disp:Disconnect' => 'Lock entity',
            '::sylabe:module:entities:puppetmaster' => 'The reference entity of <i>nebule</i>, the master of keys.',
            '::sylabe:module:entities:SecurityMaster' => 'The master entity of security.',
            '::sylabe:module:entities:CodeMaster' => 'The master entity of code.',
            '::sylabe:module:entities:DirectoryMaster' => 'The master entity of directory.',
            '::sylabe:module:entities:TimeMaster' => 'The master entity of time.',
            '::sylabe:module:entities:From' => 'From',
            '::sylabe:module:entities:To' => 'To',
            '::sylabe:module:entities:DisplayEntityMessages' => 'List of messages for %s.',
            '::sylabe:module:entities:DisplayEntityPublicMessages' => 'List of public messages for %s.',
            '::sylabe:module:entities:DisplayEntityPublicMessagesWarning' => 'Protected ou hidden messages are not availables.',
            '::sylabe:module:entities:AuthLockHelp' => 'Unconnecting...',
            '::sylabe:module:entities:AuthUnlockHelp' => 'Connecting...',
            '::sylabe:module:entities:Protected' => 'Message protected.',
            '::sylabe:module:entities:Obfuscated' => 'Message obfuscated.',
            '::sylabe:module:entities:Desc:AttribsTitle' => "Object's attributs",
            '::sylabe:module:entities:Desc:AttribsDesc' => "All attributs of the entity's object.",
            '::sylabe:module:entities:Desc:AttribsHelp' => 'All attributs of the object.',
            '::sylabe:module:entities:Desc:Attrib' => 'Attribut',
            '::sylabe:module:entities:Desc:Value' => 'Value',
            '::sylabe:module:entities:Desc:Signer' => 'Sender',
            '::sylabe:module:entities:Display:NoEntity' => 'No entity to display.',
        ],
    ];
}
