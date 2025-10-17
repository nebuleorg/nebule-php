<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Actions;
use Nebule\Library\ActionsEntities;
use Nebule\Library\Cache;
use Nebule\Library\DisplayInformation;
use Nebule\Library\DisplayItemIconMessage;
use Nebule\Library\DisplayLink;
use Nebule\Library\DisplayList;
use Nebule\Library\DisplayNotify;
use Nebule\Library\DisplayObject;
use Nebule\Library\DisplayQuery;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\DisplayItem;
use Nebule\Library\Entity;
use Nebule\Library\Metrology;
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
    const MODULE_NAME = '::module:entities:ModuleName';
    const MODULE_MENU_NAME = '::module:entities:MenuName';
    const MODULE_COMMAND_NAME = 'ent';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = '::module:entities:ModuleDescription';
    const MODULE_VERSION = '020251017';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2025';
    const MODULE_LOGO = '94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60.sha2.256';
    const MODULE_HELP = '::module:entities:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'list',
        'disp',
        'chng',
        'crea',
        'srch',
        'logs',
        'acts',
        'prop',
        'klst',
        'ulst',
        'slst',
        'kblst',
        'alst',
    );
    const MODULE_REGISTERED_ICONS = array(
        '94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0.sha2.256',    // 0 : entité (personnage)
        '6d1d397afbc0d2f6866acd1a30ac88abce6a6c4c2d495179504c2dcb09d707c1.sha2.256',    // 1 : lien de chiffrement/protection
        '7e9726b5aec1b2ab45c70f882f56ea0687c27d0739022e907c50feb87dfaf37d.sha2.256',    // 2 : lien de mise à jour
        'cc2a24b13d8e03a5de238a79a8adda1a9744507b8870d59448a23b8c8eeb5588.sha2.256',    // 3 : lister les objets
        '3edf52669e7284e4cefbdbb00a8b015460271765e97a0d6ce6496b11fe530ce1.sha2.256',    // 4 : lister les entités
        'cba3712128bbdd5243af372884eb647595103bb4c1f1b4d2e2bf62f0eba3d6e6.sha2.256',    // 5 : ajouter une entité
        '468f2e420371343c58dcdb49c4db9f00b81cce029a5ee1de627b9486994ee199.sha2.256',    // 6 : synchroniser une entité
        '4de7b15b364506d693ce0cd078398fa38ff941bf58c5f556a68a1dcd7209a2fc.sha2.256',    // 7 : messagerie down
        'a16490f9b25b2d3d055e50a2593ceda10c9d1608505e27acf15a5e2ecc314b52.sha2.256',    // 8 : messagerie up
        '1c6db1c9b3b52a9b68d19c936d08697b42595bec2f0adf16e8d9223df3a4e7c5.sha2.256',    // 9 : clé
        '94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60.sha2.256',    // 10 : entité (objet)
        'de62640d07ac4cb2f50169fa361e062ed3595be1e973c55eb3ef623ed5661947.sha2.256',    // 11 : verrouillage entité.
    );
    const MODULE_APP_TITLE_LIST = array('::module:entities:AppTitle1');
    const MODULE_APP_ICON_LIST = array('94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60.sha2.256');
    const MODULE_APP_DESC_LIST = array('::module:entities:AppDesc1');
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
        $this->_hashType = $this->getNidFromData('nebule/objet/type');
        $this->_hashEntity = $this->getNidFromData('application/x-pem-file');
        $this->_hashEntityObject = $this->_cacheInstance->newNode($this->_hashEntity);
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($nid !== null) {
            $object = $nid->getID();
            if (!$nid instanceof \Nebule\Library\Entity && $nid->getIsEntity())
                $nid = $this->_cacheInstance->newNode($object, \Nebule\Library\cache::TYPE_ENTITY);
        } else
            $object = $this->_nebuleInstance->getCurrentObjectOID();
        if ($nid instanceof \Nebule\Library\Entity)
            $unlocked = $nid->getHavePrivateKeyPassword();
        else
            $unlocked = false;

        $hookArray = array();
        switch ($hookName) {
            case 'selfMenu':
            case 'typeMenuEntity':
                // List entities I know.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[0]) {
                    $hookArray[0]['name'] = '::module:entities:KnownEntities';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[0]['desc'] = '::module:entities:KnownEntitiesDesc';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List entities know me.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[11]) {
                    $hookArray[1]['name'] = '::module:entities:KnownByEntities';
                    $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[1]['desc'] = '::module:entities:KnownByEntitiesDesc';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List my entities.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[8]) {
                    $hookArray[2]['name'] = '::module:entities:MyEntities';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[2]['desc'] = '::module:entities:MyEntitiesDesc';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[8]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List unknown entities.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[9]) {
                    $hookArray[3]['name'] = '::module:entities:UnknownEntities';
                    $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[3]['desc'] = '::module:entities:UnknownEntitiesDesc';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List special entities.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[10]) {
                    $hookArray[4]['name'] = '::module:entities:SpecialEntities';
                    $hookArray[4]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[4]['desc'] = '::module:entities:SpecialEntitiesDesc';
                    $hookArray[4]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[10]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List all entities.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[12]) {
                    $hookArray[5]['name'] = '::module:entities:allEntities';
                    $hookArray[5]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[5]['desc'] = '::module:entities:allEntitiesDesc';
                    $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // See entity properties.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[7]) {
                    $hookArray[6]['name'] = '::module:entities:DescriptionEntity';
                    $hookArray[6]['icon'] = $this::MODULE_REGISTERED_ICONS[10];
                    $hookArray[6]['desc'] = '::module:entities:DescriptionEntityDesc';
                    $hookArray[6]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                if ($this->_configurationInstance->checkBooleanOptions(['permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitWriteEntity'])
                    && ($this->_unlocked || $this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity'))
                ) {
                    // Create entity.
                    $hookArray[10]['name'] = '::module:entities:CreateEntity';
                    $hookArray[10]['icon'] = $this::MODULE_REGISTERED_ICONS[5];
                    $hookArray[10]['desc'] = '::module:entities:CreateEntityDesc';
                    $hookArray[10]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                if ($this->_configurationInstance->checkBooleanOptions(['permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject', 'permitSynchronizeLink', 'unlocked'])) {
                    // Search entity.
                    $hookArray[20]['name'] = '::module:entities:SearchEntity';
                    $hookArray[20]['icon'] = Displays::DEFAULT_ICON_LF;
                    $hookArray[20]['desc'] = '::module:entities:SearchEntityDesc';
                    $hookArray[20]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;

            case 'selfMenuObject':
                if ($nid instanceof \Nebule\Library\Entity) {
                    $hookArray[0]['name'] = '::module:entities:ShowEntity';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[10];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;

            case 'selfMenuEntity':
                if ($unlocked) {
                    // Lock entity.
                    $hookArray[0]['name'] = '::module:entities:lock';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                        . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_AUTH_ENTITY_LOGOUT
                        . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;
                    if ($object != $this->_entitiesInstance->getConnectedEntityEID()) {
                        // Switch to this entity.
                        $hookArray[1]['name'] = '::module:entities:seeAs';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_CONNECTED . '=' . $object;
                    }
                    // Change entity.
                    $hookArray[2]['name'] = '::module:entities:change';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;
                } elseif ($this->_configurationInstance->getOptionAsBoolean('permitAuthenticateEntity')) {
                    // Unlock entity.
                    $hookArray[0]['name'] = '::module:entities:unlock';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                        . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=login'
                        . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;
                    if ($object != $this->_entitiesInstance->getConnectedEntityEID()) {
                        // Switch and connect to this entity.
                        $hookArray[1]['name'] = '::module:entities:connectWith';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_CONNECTED . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    }
                }

                // Synchronise entity.
                $hookArray[3]['name'] = '::module:entities:SynchronizeEntity';
                $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                $hookArray[3]['desc'] = '';
                $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . ActionsEntities::SYNCHRONIZE
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

                // See entity.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[4]['name'] = '::module:entities:ShowEntity';
                    $hookArray[4]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[4]['desc'] = '';
                    $hookArray[4]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                /*if (!$this->_applicationInstance->getMarkObject($object)) {
                    // Mark entity.
                    $hookArray[5]['name'] = '::MarkAdd';
                    $hookArray[5]['icon'] = Display::DEFAULT_ICON_MARK;
                    $hookArray[5]['desc'] = '';
                    $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $object
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_MARK_OBJECT . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                }*/
                break;

            case '::module:entities:DisplayMyEntities':
            case '::module:entities:DisplayKnownEntity':
                // Synchroniser les entités connues.
                $hookArray[0]['name'] = '::module:entities:SynchronizeKnownEntities';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . ActionsEntities::SYNCHRONIZE
                    . '&' . self::COMMAND_SYNC_KNOWN_ENTITIES
                    . '&' . References::COMMAND_SWITCH_GHOST . '=' . $this->_entitiesInstance->getGhostEntityEID()
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                break;

            case '::module:entities:DisplayNebuleEntity':
                // Synchroniser les entités connues.
                $hookArray[0]['name'] = '::module:entities:SynchronizeKnownEntities';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . ActionsEntities::SYNCHRONIZE
                    . '&' . self::COMMAND_SYNC_NEBULE_ENTITIES
                    . '&' . References::COMMAND_SWITCH_GHOST . '=' . $this->_entitiesInstance->getGhostEntityEID()
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
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
                $this->_displayEntityChange();
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
            case $this::MODULE_REGISTERED_VIEWS[12]:
                $this->_displayAllEntitiesList();
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
            case $this::MODULE_REGISTERED_VIEWS[12]:
                $this->_display_InlineAllEntitiesList();
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

            .moduleEntitiesCreate {
                margin-bottom: 60px;
                clear: both;
            }

            .moduleEntitiesCreateHeader p {
                font-weight: bold;
                margin: 0;
                padding: 0;
            }

            .moduleEntitiesCreateProperty {
                clear: both;
            }

            .moduleEntitiesCreatePropertyName {
                float: left;
                width: 25%;
                text-align: right;
                padding-top: 10px;
            }

            .moduleEntitiesCreatePropertyEntry {
                margin-top: 2px;
                margin-bottom: 2px;
                float: right;
                width: 70%;
            }

            .moduleEntitiesCreateSubmit {
                clear: both;
            }

            .moduleEntitiesCreateSubmitEntry {
                width: 100%;
                text-align: center;
            }

            #moduleEntitiesCreatePropertyEntryNom {
                background: #ffffff;
            }

            #moduleEntitiesCreatePropertyNamePWD1 {
                font-weight: bold;
                text-align: left;
            }

            #moduleEntitiesCreatePropertyEntryPWD1 {
                background: #ffffff;
            }

            #moduleEntitiesCreatePropertyEntryPWD2 {
                background: #ffffff;
            }

            /* Les logs et acts */
            .moduleEntitiesActionText {
                padding: 20px;
                padding-left: 74px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .moduleEntitiesActionTextList1 {
                padding: 10px;
                padding-left: 74px;
                min-height: 64px;
                background: rgba(230, 230, 230, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .moduleEntitiesActionTextList2 {
                padding: 10px;
                padding-left: 74px;
                min-height: 64px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .moduleEntitiesActionDivIcon {
                float: left;
                margin-right: 5px;
            }

            .moduleEntitiesActionDate {
                float: right;
                margin-left: 5px;
                font-family: monospace;
            }

            .moduleEntitiesActionTitle {
                font-weight: bold;
                font-size: 1.2em;
            }

            .moduleEntitiesActionType {
                font-style: italic;
                font-size: 0.8em;
                margin-bottom: 10px;
            }

            .moduleEntitiesActionFromTo {
            }

            /* Les propriétés */
            .moduleEntitiesDescList1 {
                padding: 5px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .moduleEntitiesDescList2 {
                padding: 5px;
                background: rgba(230, 230, 230, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .moduleEntitiesDescError {
                padding: 5px;
                background: rgba(0, 0, 0, 0.3);
                background-origin: border-box;
                clear: both;
            }

            .moduleEntitiesDescError .moduleEntitiesDescAttrib {
                font-style: italic;
                color: #202020;
            }

            .moduleEntitiesDescIcon {
                float: left;
                margin-right: 5px;
            }

            .moduleEntitiesDescContent {
                min-width: 300px;
            }

            .moduleEntitiesDescDate, .moduleEntitiesDescSigner {
                float: right;
                margin-left: 10px;
            }

            .moduleEntitiesDescValue {
                font-weight: bold;
            }

            .moduleEntitiesDescEmotion {
                font-weight: bold;
            }

            .moduleEntitiesDescEmotion img {
                height: 16px;
                width: 16px;
            }

            /* Connexion */
            #moduleEntitiesConnect {
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
    }



    private function _findDisplayEntity(): void
    {
        //$this->_displayEntity = $this->_entitiesInstance->getGhostEntityEID();
        //$this->_displayEntityInstance = $this->_entitiesInstance->getGhostEntityInstance();
        $this->_displayEntity = $this->_nebuleInstance->getCurrentEntityEID();
        $this->_displayEntityInstance = $this->_nebuleInstance->getCurrentEntityInstance();
    }



    private bool $_synchronizeEntity = false;

    private function _findSynchronizeEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $arg = $this->getFilterInput(ActionsEntities::SYNCHRONIZE, FILTER_FLAG_NO_ENCODE_QUOTES);

        if ($arg != ''
            && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject', 'permitSynchronizeLink', 'unlocked'))
        )
            $this->_synchronizeEntity = true;
        unset($arg);
    }

    private function _actionSynchronizeEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject', 'permitSynchronizeLink', 'unlocked'))
            && $this->_synchronizeEntity
        ) {
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

    private function _findSearchEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $arg_url = trim((string)filter_input(INPUT_GET, 'srchurl', FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if ($arg_url != ''
            && strlen($arg_url) >= 8
        )
            $this->_searchEntityURL = $arg_url;

        $arg_id = trim((string)filter_input(INPUT_GET, 'srchid', FILTER_SANITIZE_URL, FILTER_FLAG_ENCODE_LOW));
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
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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



    private function _displayEntityDisp(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        echo '<div class="layout-list">' . "\n";
        echo '<div class="textListObjects">' . "\n";

        $entity = $this->_displayEntityInstance;
        $messages = array();
        $entityType = $entity->getKeyType();
        if ($entityType != '')
            $messages[] = 'Type: ' . $entityType;
        if ($this->_authoritiesInstance->getIsGlobalAuthority($entity))
            $messages[] = 'Global authority';
        if ($this->_authoritiesInstance->getIsLocalAuthority($entity))
            $messages[] = 'Local authority';
        if ($this->_authoritiesInstance->getIsPuppetMaster($entity))
            $messages[] = 'Master of all';
        if ($this->_authoritiesInstance->getIsSecurityMaster($entity))
            $messages[] = 'Master of security';
        if ($this->_authoritiesInstance->getIsCodeMaster($entity))
            $messages[] = 'Master of code';
        if ($this->_authoritiesInstance->getIsDirectoryMaster($entity))
            $messages[] = 'Master of directory';
        if ($this->_authoritiesInstance->getIsTimeMaster($entity))
            $messages[] = 'Master of time';
        $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
        $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instance->setSocial('self');
        $instance->setNID($entity);
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setIcon($instanceIcon);
        $instance->setEnableName(true);
        $instance->setEnableFlags(true);
        $instance->setEnableFlagUnlocked(true);
        $instance->setFlagUnlocked($entity->getHavePrivateKeyPassword());
        $instance->setEnableFlagState(true);
        $instance->setEnableFlagEmotions(true);
        if (sizeof($messages) > 0)
            $instance->setFlagMessageList($messages);
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        $instance->setSelfHookName('selfMenuEntity');
        $instance->setEnableStatus(false);
        $instance->setSize(\Nebule\Library\DisplayItem::SIZE_LARGE);
        $instance->setRatio(DisplayItem::RATIO_LONG);
        $instance->display();


        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }



    private function _displayEntityChange(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        echo '<div class="layout-list">' . "\n";
        echo '<div class="textListObjects">' . "\n";

        $entity = $this->_displayEntityInstance;
        $instanceList = new DisplayList($this->_applicationInstance);
        $instanceList->setSize(DisplayItem::SIZE_MEDIUM);

        $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
        $instance = new DisplayObject($this->_applicationInstance);
        $instance->setSocial('all');
        $instance->setNID($entity);
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setEnableName(true);
        $instance->setEnableRefs(false);
        $instance->setEnableFlags(true);
        $instance->setEnableFlagProtection(false);
        $instance->setEnableFlagObfuscate(false);
        $instance->setEnableFlagState(true);
        $instance->setEnableFlagEmotions(false);
        $instance->setEnableStatus(false);
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        $instance->setEnableLink(true);
        $instance->setRatio(DisplayItem::RATIO_SHORT);
        $instance->setEnableFlagUnlocked(true);
        $instance->setIcon($instanceIcon);
        $instanceList->addItem($instance);

        if ($entity->getHavePrivateKeyPassword()) {
            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setMessage('::::Password');
            $instance->setSocial('all'); // FIXME ne marche pas
            $instance->setType(DisplayQuery::QUERY_PASSWORD);
            $instance->setLink('?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                    . '&' . References::COMMAND_SWITCH_GHOST . '=' . $this->_entitiesInstance->getGhostEntityEID()
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
            $instance->setHiddenName('id');
            $instance->setHiddenValue($this->_entitiesInstance->getServerEntityEID());
            $instanceList->addItem($instance);
        }

        $instance = new DisplayInformation($this->_applicationInstance);
        $instance->setMessage('::::err_NotPermit');
        $instance->setSocial('all');
        $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
        $instance->setRatio(DisplayItem::RATIO_SHORT);
        $instanceList->addItem($instance);

        $instanceList->setOnePerLine();
        $instanceList->display();

        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }



    private function _displayEntityLogs(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Entité en cours.
        if ($this->_entitiesInstance->getGhostEntityEID() != $this->_entitiesInstance->getConnectedEntityEID()) {
            $this->_displayInstance->displayObjectDivHeaderH1($this->_displayEntityInstance, '', $this->_displayEntity);
        }

        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[7]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:ObjectTitle1');
        $instance->setIcon($icon);
        $instance->display();

        // Extrait des propriétés de l'objet.
        $entity = $this->_displayEntity;
        $instance = $this->_displayEntityInstance;
        ?>

        <div class="moduleEntitiesActionText">
            <p>
                <?php
                if ($entity == $this->_entitiesInstance->getGhostEntityEID() && $this->_unlocked) {
                    echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                        '::module:entities:DisplayEntityMessages',
                        $this->_displayInstance->convertInlineObjectColorIconName($instance));
                    $dispWarn = false;
                } else {
                    echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                        '::module:entities:DisplayEntityPublicMessages',
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
                $this->_translateInstance->getTranslate('::module:entities:DisplayEntityPublicMessagesWarning'));
        }
        unset($dispWarn);

        // liste les liens pour l'entité.
        $linksUnprotected = $instance->getLinksOnFields(
            '',
            '',
            'f',
            $entity,
            '',
            $entity);
        $linksProtected = $instance->getLinksOnFields(
            '',
            '',
            'k',
            '',
            '',
            $entity);
        $linksObfuscated = $instance->getLinksOnFields(
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

                <div class="moduleEntitiesActionTextList<?php echo $bg; ?>">
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

                        <div class="moduleEntitiesActionDivIcon">
                            <?php $this->_displayInstance->displayUpdateImage(Display::DEFAULT_ICON_LC); ?>
                        </div>
                        <div>
                            <p class="moduleEntitiesActionDate">
                                <?php $this->_displayInstance->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="moduleEntitiesActionTitle">
                                <?php echo $this->_translateInstance->getTranslate('::module:entities:Obfuscated'); ?>
                            </p>
                            <p class="moduleEntitiesActionFromTo">
                                <?php echo $this->_translateInstance->getTranslate('::module:entities:From'); ?>
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

                        <div class="moduleEntitiesActionDivIcon">
                            <?php $this->_displayInstance->displayUpdateImage(Display::DEFAULT_ICON_LK); ?>
                        </div>
                        <div>
                            <p class="moduleEntitiesActionDate">
                                <?php $this->_displayInstance->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="moduleEntitiesActionTitle">
                                <?php echo $this->_translateInstance->getTranslate('::module:entities:Protected'); ?>
                            </p>
                            <p class="moduleEntitiesActionFromTo">
                                <?php echo $this->_translateInstance->getTranslate('::module:entities:From'); ?>
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

                        <div class="moduleEntitiesActionDivIcon">
                            <?php $this->_displayInstance->displayObjectColorIcon(
                                $objectInstance, Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $link->getParsed()['bl/rl/nid2']); ?>
                        </div>
                        <div>
                            <p class="moduleEntitiesActionDate">
                                <?php $this->_displayInstance->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="moduleEntitiesActionTitle">
                                <?php echo $objectInstance->getFullName('all'); ?>
                            </p>
                            <p class="moduleEntitiesActionType">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate($objectInstance->getType('all')); ?>
                            </p>
                            <p class="moduleEntitiesActionFromTo">
                                <?php echo $this->_translateInstance->getTranslate('::module:entities:From'); ?>
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



    private function _displayEntityActs(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Entité en cours.
        if ($this->_entitiesInstance->getGhostEntityEID() != $this->_entitiesInstance->getConnectedEntityEID()) {
            $this->_displayInstance->displayObjectDivHeaderH1($this->_displayEntityInstance, '', $this->_displayEntity);
        }

        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[8]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:ObjectTitle2');
        $instance->setIcon($icon);
        $instance->display();

        // Extrait des propriétés de l'objet.
        $id = $this->_applicationInstance->getCurrentObjectInstance()->getID();
        $typemime = $this->_applicationInstance->getCurrentObjectInstance()->getType('all');
        $ispresent = $this->_nebuleInstance->getIoInstance()->checkObjectPresent($id);
        $owned = false;
        ?>

        <div class="moduleEntitiesActionText">
            <p>
                <?php
                $dispWarn = false;
                // Vérifie si l'objet courant est une entité, affiche les messages de cette entité.
                if ($typemime == 'application/x-pem-file' && $ispresent) {
                    $entity = $this->_cacheInstance->newNode($id, \Nebule\Library\Cache::TYPE_ENTITY);
                    echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                        '::module:entities:DisplayEntityPublicMessages',
                        $this->_displayInstance->convertInlineObjectColorIconName($entity));
                    $dispWarn = true;
                } // Sinon, affiche les messages de l'entité courante.
                else {
                    $entity = $this->_entitiesInstance->getGhostEntityInstance();
                    $id = $this->_entitiesInstance->getGhostEntityEID();
                    $owned = true;
                    if ($this->_unlocked) {
                        echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                            '::module:entities:DisplayEntityMessages',
                            $this->_displayInstance->convertInlineObjectColorIconName($entity));
                    } else {
                        echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                            '::module:entities:DisplayEntityPublicMessages',
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
                $this->_translateInstance->getTranslate('::module:entities:DisplayEntityPublicMessagesWarning'));
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

                <div class="moduleEntitiesActionTextList<?php echo $bg; ?>">
                    <div class="moduleEntitiesActionDivIcon">
                        <?php $this->_displayInstance->displayObjectColorIcon($objectInstance); ?>
                    </div>
                    <div>
                        <p class="moduleEntitiesActionDate">
                            <?php $this->_displayInstance->displayDate($date);
                            echo "\n"; ?>
                        </p>
                        <p class="moduleEntitiesActionTitle">
                            <?php echo $objectInstance->getFullName('all'); ?>
                        </p>
                        <p class="moduleEntitiesActionType">
                            <?php echo $objectInstance->getType('all'); ?>
                        </p>
                        <p class="moduleEntitiesActionFromTo">
                            <?php echo $this->_translateInstance->getTranslate('::module:entities:To'); ?>
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



    private function _displayMyEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:MyEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('myentities');
    }

    private function _display_InlineMyEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $entities = array();
        $this->_displayEntitiesList($entities);
    }



    private function _displayKnownEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:KnownEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('knownentities');
    }

    private function _display_InlineKnownEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_entitiesInstance->getGhostEntityEID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter);

        $entities = array();
        foreach ($links as $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_ENTITY);
            $entities[$link->getParsed()['bl/rl/nid2']] = $instance;
        }

        $this->_displayEntitiesList($entities, \Nebule\Library\DisplayItem::RATIO_LONG, $listOkEntities);
    }



    private function _displayKnownByEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:KnownByEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('knownentities');
    }

    private function _display_InlineKnownByEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid2' => $this->_entitiesInstance->getGhostEntityEID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter);

        $entities = array();
        foreach ($links as $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_ENTITY);
            $entities[$link->getParsed()['bl/rl/nid2']] = $instance;
        }

        $this->_displayEntitiesList($entities, \Nebule\Library\DisplayItem::RATIO_LONG, $listOkEntities);
    }



    private function _displayUnknownEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:UnknownEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('unknownentities');
    }

    private function _display_InlineUnknownEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_entitiesInstance->getGhostEntityEID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter);
        foreach ($links as $link) {
            $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
        }

        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid2' => $this->_entitiesInstance->getGhostEntityEID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter);

        foreach ($links as $link) {
            $listOkEntities[$link->getParsed()['bl/rl/nid1']] = true;
        }

        $this->_displayEntitiesList($this->_entitiesInstance->getListEntitiesInstances(), \Nebule\Library\DisplayItem::SIZE_LARGE, $listOkEntities, array(), array(), false, true, true);
    }



    private function _displayAllEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:allEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('allentities');
    }

    private function _display_InlineAllEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayEntitiesList($this->_entitiesInstance->getListEntitiesInstances());
    }



    /**
     * Affiche la liste des entités spéciales.
     *
     * @return void
     */
    private function _displaySpecialEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[4]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:SpecialEntities');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('specialentities');
    }

    private function _display_InlineSpecialEntitiesList(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Liste des entités.
        $entities = array();
        $masters = array();
        $signers = array();
        $id = $this->_authoritiesInstance->getPuppetmasterEID();
        $entities[$id] = $this->_authoritiesInstance->getPuppetmasterInstance();
        $masters[$id] = '';
        $signers[$id] = array();
        foreach ($this->_authoritiesInstance->getSecurityAuthoritiesInstance() as $instance)
        {
            $id = $instance->getID();
            $entities[$id] = $instance;
            $masters[$id] = References::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_SECURITE;
            $signers[$id] = $this->_authoritiesInstance->getSecuritySignersInstance()[$instance->getID()];
        }
        foreach ($this->_authoritiesInstance->getCodeAuthoritiesInstance() as $instance)
        {
            $id = $instance->getID();
            $entities[$id] = $instance;
            $masters[$id] = References::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_CODE;
            $signers[$id] = $this->_authoritiesInstance->getCodeSignersInstance()[$instance->getID()];
        }
        foreach ($this->_authoritiesInstance->getDirectoryAuthoritiesInstance() as $instance)
        {
            $id = $instance->getID();
            $entities[$id] = $instance;
            $masters[$id] = References::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_ANNUAIRE;
            $signers[$id] = $this->_authoritiesInstance->getDirectorySignersInstance()[$instance->getID()];
        }
        foreach ($this->_authoritiesInstance->getTimeAuthoritiesInstance() as $instance)
        {
            $id = $instance->getID();
            $entities[$id] = $instance;
            $masters[$id] = References::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_TEMPS;
            $signers[$id] = $this->_authoritiesInstance->getTimeSignersInstance()[$instance->getID()];
        }
        $id = $this->_entitiesInstance->getServerEntityEID() . ' se';
        $entities[$id] = $this->_entitiesInstance->getServerEntityInstance();
        $masters[$id] = 'Server entity';
        $signers[$id] = array();
        $id = $this->_entitiesInstance->getDefaultEntityEID() . ' de';
        $entities[$id] = $this->_entitiesInstance->getDefaultEntityInstance();
        if (isset($masters[$id]))
            $masters[$id] .= ', ';
        $masters[$id] .= 'Default entity';
        $signers[$id] = array();
        $id = $this->_entitiesInstance->getGhostEntityEID() . ' ge';
        $entities[$id] = $this->_entitiesInstance->getGhostEntityInstance();
        if (isset($masters[$id]))
            $masters[$id] .= ', ';
        $masters[$id] .= 'Ghost entity';
        $signers[$id] = array();
        $id = $this->_entitiesInstance->getConnectedEntityEID() . ' ce';
        $entities[$id] = $this->_entitiesInstance->getConnectedEntityInstance();
        if (isset($masters[$id]))
            $masters[$id] .= ', ';
        $masters[$id] .= 'Connected entity';
        $signers[$id] = array();

        $this->_displayEntitiesList($entities, \Nebule\Library\DisplayItem::RATIO_LONG, array(), $masters, $signers, true, true, false);
    }



    private bool $_createEntityAction = false;
    private ?Entity $_createEntityInstance = Null;
    private ?Node $_createEntityKeyInstance = Null;
    private bool $_createEntityError = false;
    private string $_createEntityErrorMessage = '';

    /**
     * Affiche la création d'une entité.
     */
    private function _displayEntityCreate(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_createEntityAction = $this->_applicationInstance->getActionInstance()->getCreateEntity();
        if ($this->_createEntityAction)
            $this->_displayEntityCreateNew();

        if ($this->_createEntityAction && !$this->_createEntityError)
            $this->_displayEntityDisp();
        else
            $this->_displayEntityCreateForm();
    }

    private function _displayEntityCreateNew(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_createEntityAction = $this->_applicationInstance->getActionInstance()->getCreateEntity();
        $this->_createEntityInstance = $this->_applicationInstance->getActionInstance()->getCreateEntityInstance();
        $this->_createEntityKeyInstance = $this->_applicationInstance->getActionInstance()->getCreateEntityKeyInstance();
        $this->_createEntityError = $this->_applicationInstance->getActionInstance()->getCreateEntityError();
        $this->_createEntityErrorMessage = $this->_applicationInstance->getActionInstance()->getCreateEntityErrorMessage();

        if ($this->_createEntityAction) {
            $instanceList = new DisplayList($this->_applicationInstance);
            $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
            $instance = new DisplayInformation($this->_applicationInstance);
            $instance->setRatio(DisplayItem::RATIO_SHORT);
            if (!$this->_createEntityError) {
                $this->_displayEntityInstance = $this->_createEntityInstance;
                $this->_displayEntity = $this->_createEntityInstance->getID();

                $instance->setMessage('::module:entities:EntityCreated');
                $instance->setType(DisplayItemIconMessage::TYPE_OK);
                $instance->setIconText('::::OK');
                $instanceList->addItem($instance);

                $instance = new DisplayObject($this->_applicationInstance);
                $instance->setSocial('self');
                $instance->setNID($this->_createEntityInstance);
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instance->setEnableName(true);
                $instance->setEnableRefs(false);
                $instance->setEnableNID(false);
                $instance->setEnableFlags(true);
                $instance->setEnableFlagProtection(false);
                $instance->setEnableFlagObfuscate(false);
                $instance->setEnableFlagState(true);
                $instance->setEnableFlagEmotions(false);
                $instance->setEnableStatus(true);
                $instance->setEnableContent(false);
                $instance->setEnableJS(false);
                $instance->setEnableLink(true);
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setStatus('');
                $instance->setEnableFlagUnlocked(false);
                //$instance->setFlagUnlocked($this->_createEntityInstance->getHavePrivateKeyPassword());
                $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['oent']); // FIXME
                $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
                //$instance->setSelfHookName('selfMenuEntity');
                $instance->setIcon($instanceIcon2);
                $instanceList->addItem($instance);

                $instance = new DisplayObject($this->_applicationInstance);
                $instance->setSocial('all');
                $instance->setNID($this->_createEntityKeyInstance);
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instance->setEnableName(true);
                $instance->setEnableRefs(false);
                $instance->setEnableNID(false);
                $instance->setEnableFlags(true);
                $instance->setEnableFlagProtection(false);
                $instance->setEnableFlagObfuscate(false);
                $instance->setEnableFlagState(true);
                $instance->setEnableFlagEmotions(false);
                $instance->setEnableStatus(true);
                $instance->setEnableContent(false);
                $instance->setEnableJS(false);
                $instance->setEnableLink(true);
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setStatus('');
                $instance->setEnableFlagUnlocked(false);
                $instance->setName($this->_translateInstance->getTranslate('::privateKey'));
                $instance->setType(References::REFERENCE_OBJECT_TEXT);
                $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['key']); // FIXME
                $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
                $instance->setIcon($instanceIcon2);
            } else {
                $instance = new DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::module:entities:EntityNotCreated');
                $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setIconText('::::ERROR');
                $instanceList->addItem($instance);

                $instance = new DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::module:entities:EntityNotCreated');
                $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setIconText('$this->_createEntityErrorMessage');
            }
            $instanceList->addItem($instance);
            $instanceList->setOnePerLine();
            $instanceList->display();
        }
        echo '<br />' . "\n";
    }

    private function _displayEntityCreateForm(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[5]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:CreateEntity');
        $instance->setIcon($icon);
        $instance->display();

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
                              . '&' . ActionsEntities::CREATE
                              . '&' . \Nebule\Library\References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                              . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(); ?>">
                        <div class="moduleEntitiesCreate" id="moduleEntitiesCreateNames">
                            <div class="moduleEntitiesCreateHeader">
                                <p>
                                    <?php echo $this->_translateInstance->getTranslate('::module:entities:CreateEntityNommage'); ?>

                                </p>
                            </div>
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/prefix'); ?>

                                </div>
                                <label for="moduleEntitiesCreatePropertyEntryPrefix"><?php echo $this->_translateInstance->getTranslate('nebule/objet/prefix'); ?></label>
                                <input type="text"
                                        name="<?php echo ActionsEntities::CREATE_PREFIX; ?>"
                                        size="10" value=""
                                        class="moduleEntitiesCreatePropertyEntry"
                                        id="moduleEntitiesCreatePropertyEntryPrefix"/>
                            </div>
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/prenom'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo ActionsEntities::CREATE_FIRSTNAME; ?>"
                                       size="20" value=""
                                       class="moduleEntitiesCreatePropertyEntry"
                                       id="moduleEntitiesCreatePropertyEntryPrenom"/>
                            </div>
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/surnom'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo ActionsEntities::CREATE_NICKNAME; ?>"
                                       size="10" value=""
                                       class="moduleEntitiesCreatePropertyEntry"
                                       id="moduleEntitiesCreatePropertyEntrySurnom"/>
                            </div>
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/nom'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo ActionsEntities::CREATE_NAME; ?>"
                                       size="20" value=""
                                       class="moduleEntitiesCreatePropertyEntry"
                                       id="moduleEntitiesCreatePropertyEntryNom"/>
                            </div>
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/suffix'); ?>
                                </div>
                                <input type="text"
                                       name="<?php echo ActionsEntities::CREATE_SUFFIX; ?>"
                                       size="10" value=""
                                       class="moduleEntitiesCreatePropertyEntry"
                                       id="moduleEntitiesCreatePropertyEntrySuffix"/>
                            </div>
                        </div>
                        <div class="moduleEntitiesCreate" id="moduleEntitiesCreatePassword">
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName"
                                     id="moduleEntitiesCreatePropertyNamePWD1">
                                    <?php echo $this->_translateInstance->getTranslate('::::Password'); ?>

                                </div>
                                <input type="password"
                                       name="<?php echo ActionsEntities::CREATE_PASSWORD1; ?>"
                                       size="30" value=""
                                       class="moduleEntitiesCreatePropertyEntry"
                                       id="moduleEntitiesCreatePropertyEntryPWD1"/>
                            </div>
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName"
                                     id="moduleEntitiesCreatePropertyNamePWD2">
                                    <?php echo $this->_translateInstance->getTranslate('::module:entities:CreateEntityConfirm'); ?>

                                </div>
                                <input type="password"
                                       name="<?php echo ActionsEntities::CREATE_PASSWORD2; ?>"
                                       size="30" value=""
                                       class="moduleEntitiesCreatePropertyEntry"
                                       id="moduleEntitiesCreatePropertyEntryPWD2"/>
                            </div>
                        </div>
                        <div class="moduleEntitiesCreate" id="moduleEntitiesCreateOther">
                            <div class="moduleEntitiesCreateHeader">
                                <p>
                                    <?php echo $this->_translateInstance->getTranslate('::module:entities:CreateEntityOther'); ?>

                                </p>
                            </div>
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('::module:entities:CreateEntityAlgorithm'); ?>

                                </div>
                                <select
                                    name="<?php echo ActionsEntities::CREATE_ALGORITHM; ?>"
                                    class="moduleEntitiesCreatePropertyEntry">
                                    <?php
                                    $defaultAlgo = $this->_configurationInstance->getOptionAsString('cryptoAsymmetricAlgorithm');
                                    foreach ($this->_cryptoInstance->getAlgorithmList(\Nebule\Library\Crypto::TYPE_ASYMMETRIC) as $algo) {
                                        echo '<option value="' . $algo . '"';
                                        if ($defaultAlgo === $algo)
                                            echo ' selected';
                                        echo '>' . $algo . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('nebule/objet/entite/type'); ?>
                                </div>
                                 <select
                                    name="<?php echo ActionsEntities::CREATE_TYPE; ?>"
                                    class="moduleEntitiesCreatePropertyEntry">
                                    <option value="undef" selected>
                                        <?php echo $this->_translateInstance->getTranslate('::module:entities:CreateEntityTypeUndefined'); ?>

                                    </option>
                                    <option value="human">
                                        <?php echo $this->_translateInstance->getTranslate('::module:entities:CreateEntityTypeHuman'); ?>

                                    </option>
                                    <option value="robot">
                                        <?php echo $this->_translateInstance->getTranslate('::module:entities:CreateEntityTypeRobot'); ?>

                                    </option>
                                </select>
                            </div>

                            <?php if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) { ?>
                            <div class="moduleEntitiesCreateProperty">
                                <div class="moduleEntitiesCreatePropertyName">
                                    <?php echo $this->_translateInstance->getTranslate('::module:entities:CreateEntityAutonomy'); ?>

                                </div>
                                <select
                                        name="<?php echo ActionsEntities::CREATE_AUTONOMY; ?>"
                                        class="moduleEntitiesCreatePropertyEntry">
                                    <option value="y" selected>
                                        <?php echo $this->_translateInstance->getTranslate('::::yes'); ?>

                                    </option>
                                </select>
                            </div>
                            <?php } ?>

                        </div>
                        <div class="moduleEntitiesCreateSubmit">
                            <input type="submit"
                                   value="<?php echo $this->_translateInstance->getTranslate('::module:entities:CreateTheEntity'); ?>"
                                   class="moduleEntitiesCreateSubmitEntry"/>
                        </div>
                    </form>
                </div>
            </div>
            <?php
        } else {
            $instance = new DisplayNotify($this->_applicationInstance);
            $instance->setMessage('::::err_NotPermit');
            $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
            $instance->display();
        }
    }



    /**
     * Affiche la recherche d'une entité.
     */
    private function _displayEntitySearch(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Affiche la création d'une entité.
        $iconNID = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_LF);
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:SearchEntity');
        $instance->setIcon($iconNID);
        $instance->display();

        // Vérifie que la création soit autorisée.
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
                           value="<?php echo $this->_entitiesInstance->getGhostEntityEID(); ?>">
                    <table border="0" padding="2px">
                        <tr>
                            <td align="right"><?php echo $this->_translateInstance->getTranslate('::module:entities:Search:URL') ?>
                            </td>
                            <td>:</td>
                            <td><input type="text" name="srchurl" size="80"
                                       value="<?php echo $this->_searchEntityURL; ?>"></td>
                        </tr>
                        <tr>
                            <td align="right"><?php echo $this->_translateInstance->getTranslate('::module:entities:Search:AndOr') ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align="right"><?php echo $this->_translateInstance->getTranslate('::module:entities:Search:ID') ?>
                            </td>
                            <td>:</td>
                            <td><input type="text" name="srchid" size="80"
                                       value="<?php echo $this->_searchEntityID; ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><input type="submit"
                                       value="<?php echo $this->_translateInstance->getTranslate('::module:entities:Search:Submit'); ?>">
                            </td>
                        </tr>
                    </table>
                </form>
                <?php

                ?>

                </p>
            </div>
            <?php
            $this->_displayInstance->displayMessageInformation_DEPRECATED('::module:entities:SearchEntityLongTime');
        } else {
            $this->_displayInstance->displayMessageWarning_DEPRECATED('::module:entities:SearchEntityNotAllowed');
        }
    }


    private function _display_InlineEntitySearch(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
    }


    /**
     * Affiche les propriétés de l'entité.
     */
    private function _displayEntityProp(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[3]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:entities:Desc:AttribsTitle');
        $instance->setIcon($icon);
        $instance->display();

        $this->_displayInstance->registerInlineContentID('properties');
    }

    private function _display_InlineEntityProp(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $entity = $this->_displayEntityInstance;
        $this->_metrologyInstance->addLog('DEBUGGING entity EID=' . $entity->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
        $links = array();
        $filter = array(
                'bl/rl/req' => 'l',
                'bl/rl/nid1' => $entity->getID(),
                'bl/rl/nid4' => '',
        );
        $entity->getLinks($links, $filter);
        $instanceList = new DisplayList($this->_applicationInstance);
        $instance = new DisplayObject($this->_applicationInstance);
        $instance->setNID($entity);
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setEnableName(true);
        $instance->setEnableFlags(false);
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        $instanceList->addItem($instance);
        foreach ($links as $link) {
            $ok = true;
            $contentOID = $link->getParsed()['bl/rl/nid2'];
            $content = $this->_ioInstance->getObject($contentOID);
            if ($content === false) {
                $ok = false;
                $content = $contentOID;
            }
            $propertyOID = $link->getParsed()['bl/rl/nid3'];
            $property = $this->_ioInstance->getObject($propertyOID);
            if ($property === false) {
                $ok = false;
                $property = $propertyOID;
            }

            /*$instance = new DisplayInformation($this->_applicationInstance);
            if ($ok)
                $instance->setType(DisplayItemIconMessage::TYPE_INFORMATION);
            else
                $instance->setType(DisplayItemIconMessage::TYPE_WARN);
            $instance->setMessage($property . " :<br />\n" . $content);
            $instance->setIconText($property);
            $instanceList->addItem($instance);*/

            $instance = new DisplayObject($this->_applicationInstance);
            $instanceNode = $this->_cacheInstance->newNode($contentOID);
            $instance->setNID($instanceNode);
            $instance->setEnableColor(false);
            $instance->setEnableIcon(false);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableContent(true);
            $instance->setEnableJS(false);
            $instance->setEnableLink(false);
            $instance->setEnableRefs(true);
            $instance->setName($property);
            $instance->setRefs($link->getSignersEID());
            $instanceList->addItem($instance);
        }

        $links = array();
        $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $entity->getID(),
                'bl/rl/nid3' => $this->_nebuleInstance->getFromDataNID(References::REFERENCE_PRIVATE_KEY),
                'bl/rl/nid4' => '',
        );
        $entity->getLinks($links, $filter);
        foreach ($links as $link) {
            $instance = new DisplayObject($this->_applicationInstance);
            $instanceNode = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
            $instance->setNID($instanceNode);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableLink(true);
            $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['lo']);
            $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
            $instance->setIcon($instanceIcon2);
            $instanceList->addItem($instance);
        }

        $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(DisplayItem::RATIO_SHORT);
        $instanceList->display();
    }



    private function _displayEntitiesList(
            array  $listEntities,
            string $ratio=\Nebule\Library\DisplayItem::RATIO_SHORT,
            array  $listOkEntities = array(),
            array  $listDesc = array(),
            array  $listSigners = array(),
            bool   $allowDouble = false,
            bool   $showState = true,
            bool   $showEmotions = false): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($listEntities as $i => $entity) {
            $eid = $entity->getID();
            if ((isset($listOkEntities[$eid]) && !$allowDouble)
                || !$entity->getTypeVerify()
                || !$entity->getIsPublicKey()
            )
                continue;
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial('self');
            $instance->setNID($entity);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setIcon($instanceIcon);
            $instance->setEnableName(true);
            $instance->setEnableFlags($showState || $showEmotions);
            $instance->setEnableFlagState($showState);
            $instance->setEnableFlagEmotions($showEmotions);
            $instance->setEnableFlagUnlocked($showState);
            $instance->setFlagUnlocked($entity->getHavePrivateKeyPassword());
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            if (isset($listSigners[$i]))
                $instance->setRefs($listSigners[$i]);
            $instance->setSelfHookName('selfMenuEntity');
            if (isset($listDesc[$i])) {
                $instance->setEnableStatus(true);
                $instance->setStatus($listDesc[$i]);
            } else
                $instance->setEnableStatus(false);
            $instanceList->addItem($instance);
            $listOkEntities[$entity->getID()] = true;
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio($ratio);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::module:entities:ModuleName' => 'Module des entités',
            '::module:entities:MenuName' => 'Entités',
            '::module:entities:ModuleDescription' => 'Module de gestion des entités.',
            '::module:entities:ModuleHelp' => "Ce module permet de voir les entités, de gérer les relations et de changer l'entité en cours d'utilisation.",
            '::module:entities:AppTitle1' => 'Entités',
            '::module:entities:AppDesc1' => 'Gestion des entités.',
            '::module:entities:display:ListEntities' => 'Lister les entités',
            '::module:entities:EntityCreated' => 'Nouvelle entité créée',
            '::module:entities:EntityNotCreated' => "La nouvelle entité n'a pas pu être créée",
            '::module:entities:ShowEntity' => "Voir l'entité",
            '::module:entities:DescriptionEntity' => "L'entité",
            '::module:entities:DescriptionEntityDesc' => "Description de l'entité.",
            '::module:entities:allEntities' => 'Toutes les entités',
            '::module:entities:allEntitiesDesc' => 'Toutes les entités.',
            '::module:entities:allEntitiesHelp' => 'La liste de toutes less entités.',
            '::module:entities:MyEntities' => 'Mes entités',
            '::module:entities:MyEntitiesDesc' => 'Toutes les entités sous contrôle.',
            '::module:entities:MyEntitiesHelp' => "La liste des entités sous contrôle, c'est à dire avec lequelles ont peut instantanément basculer.",
            '::module:entities:KnownEntities' => 'Entités connues',
            '::module:entities:KnownEntitiesDesc' => 'Toutes les entités connues.',
            '::module:entities:KnownEntitiesHelp' => "La liste des entités que l'on connait, amies ou pas.",
            '::module:entities:KnownEntity' => 'Je connais cette entité',
            '::module:entities:SpecialEntities' => 'entités spéciales',
            '::module:entities:SpecialEntitiesDesc' => 'Les entités spécifiques à <i>nebule</i>.',
            '::module:entities:SpecialEntitiesHelp' => 'La liste des entités spécifiques à <i>nebule</i>.',
            '::module:entities:UnknownEntities' => 'Entités inconnues',
            '::module:entities:UnknownEntitiesDesc' => 'Toutes les autres entités, non connues.',
            '::module:entities:UnknownEntitiesHelp' => "La liste des entités que l'on connait pas.",
            '::module:entities:KnownByEntities' => 'Connu de ces entités',
            '::module:entities:KnownByEntitiesDesc' => 'Toutes les entités qui me connaissent.',
            '::module:entities:KnownByEntitiesHelp' => "La liste des entités qui me connaissent.",
            '::module:entities:SynchronizeEntity' => 'Synchroniser',
            '::module:entities:SynchronizeKnownEntities' => 'Synchroniser toutes les entités',
            '::module:entities:AttribNotDisplayable' => 'Propriété non affichable !',
            '::module:entities:ListEntities' => 'Lister',
            '::module:entities:ListEntitiesDesc' => 'Lister les entités',
            '::module:entities:CreateEntity' => 'Créer',
            '::module:entities:CreateEntityNommage' => 'Nommage',
            '::module:entities:CreateEntityConfirm' => 'Confirmation',
            '::module:entities:CreateEntityOther' => 'Autre',
            '::module:entities:CreateEntityAlgorithm' => 'Algorithme',
            '::module:entities:CreateEntityTypeUndefined' => '(Indéfini)',
            '::module:entities:CreateEntityTypeHuman' => 'Humain',
            '::module:entities:CreateEntityTypeRobot' => 'Robot',
            '::module:entities:CreateEntityAutonomy' => 'Entité indépendante',
            '::module:entities:CreateTheEntity' => "Créer l'entité",
            '::module:entities:CreateEntityDesc' => 'Créer une entité.',
            '::module:entities:SearchEntity' => 'Chercher',
            '::module:entities:SearchEntityDesc' => 'Rechercher une entité.',
            '::module:entities:SearchEntityHelp' => 'Rechercher une entité connue.',
            '::module:entities:SearchEntityNotAllowed' => "La recherche d'entité est désactivée !",
            '::module:entities:SearchEntityLongTime' => 'La recherche sur identifiant uniquement peut prendre du temps...',
            '::module:entities:Search:URL' => 'Adresse de présence',
            '::module:entities:Search:AndOr' => 'et/ou',
            '::module:entities:Search:ID' => 'Identifiant',
            '::module:entities:Search:Submit' => 'Rechercher',
            '::module:entities:connectWith' => 'Se connecter avec cette entité',
            '::module:entities:unlock' => 'Déverrouiller cette entité',
            '::module:entities:switchTo' => 'Se connecter avec cette entité',
            '::module:entities:lock' => "Verrouiller l'entité",
            '::module:entities:seeAs' => "Voir en tant que",
            '::module:entities:puppetmaster' => "L'entité de référence de <i>nebule</i>, le maître des clés.",
            '::module:entities:SecurityMaster' => "L'entité maîtresse de la sécurité.",
            '::module:entities:CodeMaster' => "L'entité maîtresse du code.",
            '::module:entities:DirectoryMaster' => "L'entité maîtresse de l'annuaire.",
            '::module:entities:TimeMaster' => "L'entité maîtresse du temps.",
            '::module:entities:From' => 'De',
            '::module:entities:To' => 'Pour',
            '::module:entities:DisplayEntityMessages' => 'Liste des messages de %s.',
            '::module:entities:DisplayEntityPublicMessages' => 'Liste des messages publics de %s.',
            '::module:entities:DisplayEntityPublicMessagesWarning' => 'Les messages protégés ou dissimulés ne sont pas accessibles.',
            '::module:entities:AuthLockHelp' => 'Se déconnecter...',
            '::module:entities:AuthUnlockHelp' => 'Se connecter...',
            '::module:entities:Protected' => 'Message protégé.',
            '::module:entities:Obfuscated' => 'Message dissimulé.',
            '::module:entities:Desc:AttribsTitle' => "Propriétés de l'entité",
            '::module:entities:Desc:AttribsDesc' => "Toutes les propriétés de l'objet de l'entité.",
            '::module:entities:Desc:AttribsHelp' => "Toutes les propriétés de l'objet.",
            '::module:entities:Desc:Attrib' => 'Propriété',
            '::module:entities:Desc:Value' => 'Valeur',
            '::module:entities:Desc:Signer' => 'Emetteur',
            '::module:entities:Display:NoEntity' => "Pas d'entité à afficher.",
        ],
        'en-en' => [
            '::module:entities:ModuleName' => 'Entities module',
            '::module:entities:MenuName' => 'Entities',
            '::module:entities:ModuleDescription' => 'Module to manage entities.',
            '::module:entities:ModuleHelp' => 'This module permit to see entites, to manage related and to change of corrent entity.',
            '::module:entities:AppTitle1' => 'Entities',
            '::module:entities:AppDesc1' => 'Manage entities.',
            '::module:entities:display:SynchronizeEntities' => 'Synchronize current entity',
            '::module:entities:display:ListEntities' => 'Show list of entities',
            '::module:entities:EntityCreated' => 'New entity created',
            '::module:entities:EntityNotCreated' => "The new entity can't be created",
            '::module:entities:ShowEntity' => 'See the entity',
            '::module:entities:DescriptionEntity' => 'This entity',
            '::module:entities:DescriptionEntityDesc' => 'About this entity.',
            '::module:entities:allEntities' => 'All entities',
            '::module:entities:allEntitiesDesc' => 'All entities.',
            '::module:entities:allEntitiesHelp' => 'The list of all entities.',
            '::module:entities:MyEntities' => 'My entities',
            '::module:entities:MyEntitiesDesc' => 'All entities under control.',
            '::module:entities:MyEntitiesHelp' => 'The list of all entities under control.',
            '::module:entities:KnownEntities' => 'Known entities',
            '::module:entities:KnownEntitiesDesc' => 'All known entities.',
            '::module:entities:KnownEntitiesHelp' => 'The list of all entities we known, friends or not.',
            '::module:entities:KnownEntity' => 'I known this entity',
            '::module:entities:SpecialEntities' => 'Specials entities',
            '::module:entities:SpecialEntitiesDesc' => 'Specifics entities to <i>nebule</i>.',
            '::module:entities:SpecialEntitiesHelp' => 'The list of specifics entities to <i>nebule</i>.',
            '::module:entities:UnknownEntities' => 'Unknown entities',
            '::module:entities:UnknownEntitiesDesc' => 'All unknown entities.',
            '::module:entities:UnknownEntitiesHelp' => 'The list of all others entities.',
            '::module:entities:KnownByEntities' => 'Known by entities',
            '::module:entities:KnownByEntitiesDesc' => 'All known by entities.',
            '::module:entities:KnownByEntitiesHelp' => 'The list of all entities who known me.',
            '::module:entities:SynchronizeEntity' => 'Synchronize',
            '::module:entities:SynchronizeKnownEntities' => 'Synchronize all entities',
            '::module:entities:AttribNotDisplayable' => 'Attribut not displayable!',
            '::module:entities:ListEntities' => 'List',
            '::module:entities:ListEntitiesDesc' => 'Show list of entities',
            '::module:entities:CreateEntity' => 'Create',
            '::module:entities:CreateEntityNommage' => 'Naming',
            '::module:entities:CreateEntityConfirm' => 'Confirm',
            '::module:entities:CreateEntityOther' => 'Other',
            '::module:entities:CreateEntityAlgorithm' => 'Algorithm',
            '::module:entities:CreateEntityTypeUndefined' => '(Undefined)',
            '::module:entities:CreateEntityTypeHuman' => 'Human',
            '::module:entities:CreateEntityTypeRobot' => 'Robot',
            '::module:entities:CreateEntityAutonomy' => 'Independent entity',
            '::module:entities:CreateTheEntity' => 'Create the entity',
            '::module:entities:CreateEntityDesc' => 'Create entity.',
            '::module:entities:SearchEntity' => 'Search',
            '::module:entities:SearchEntityDesc' => 'Search entity.',
            '::module:entities:SearchEntityHelp' => 'Search a known entity.',
            '::module:entities:SearchEntityNotAllowed' => 'Entity search is disabled!',
            '::module:entities:SearchEntityLongTime' => 'The search on identifier only can take some time...',
            '::module:entities:Search:URL' => 'Address of localisation',
            '::module:entities:Search:AndOr' => 'and/or',
            '::module:entities:Search:ID' => 'Identifier',
            '::module:entities:Search:Submit' => 'Submit',
            '::module:entities:connectWith' => 'Connect with this entity',
            '::module:entities:unlock' => 'Unlock the entity',
            '::module:entities:switchTo' => 'Switch to this entity',
            '::module:entities:lock' => 'Lock entity',
            '::module:entities:seeAs' => "See as",
            '::module:entities:puppetmaster' => 'The reference entity of <i>nebule</i>, the master of keys.',
            '::module:entities:SecurityMaster' => 'The master entity of security.',
            '::module:entities:CodeMaster' => 'The master entity of code.',
            '::module:entities:DirectoryMaster' => 'The master entity of directory.',
            '::module:entities:TimeMaster' => 'The master entity of time.',
            '::module:entities:From' => 'From',
            '::module:entities:To' => 'To',
            '::module:entities:DisplayEntityMessages' => 'List of messages for %s.',
            '::module:entities:DisplayEntityPublicMessages' => 'List of public messages for %s.',
            '::module:entities:DisplayEntityPublicMessagesWarning' => 'Protected ou hidden messages are not availables.',
            '::module:entities:AuthLockHelp' => 'Unconnecting...',
            '::module:entities:AuthUnlockHelp' => 'Connecting...',
            '::module:entities:Protected' => 'Message protected.',
            '::module:entities:Obfuscated' => 'Message obfuscated.',
            '::module:entities:Desc:AttribsTitle' => "Entity's attributs",
            '::module:entities:Desc:AttribsDesc' => "All attributs of the entity's object.",
            '::module:entities:Desc:AttribsHelp' => 'All attributs of the object.',
            '::module:entities:Desc:Attrib' => 'Attribut',
            '::module:entities:Desc:Value' => 'Value',
            '::module:entities:Desc:Signer' => 'Sender',
            '::module:entities:Display:NoEntity' => 'No entity to display.',
        ],
        'es-co' => [
            '::module:entities:ModuleName' => 'Entities module',
            '::module:entities:MenuName' => 'Entities',
            '::module:entities:ModuleDescription' => 'Module to manage entities.',
            '::module:entities:ModuleHelp' => 'This module permit to see entites, to manage related and to change of corrent entity.',
            '::module:entities:AppTitle1' => 'Entities',
            '::module:entities:AppDesc1' => 'Manage entities.',
            '::module:entities:display:SynchronizeEntities' => 'Synchronize current entity',
            '::module:entities:display:ListEntities' => 'Show list of entities',
            '::module:entities:EntityCreated' => 'New entity created',
            '::module:entities:EntityNotCreated' => "The new entity can't be created",
            '::module:entities:ShowEntity' => 'See the entity',
            '::module:entities:DescriptionEntity' => 'This entity',
            '::module:entities:DescriptionEntityDesc' => 'About this entity.',
            '::module:entities:allEntities' => 'All entities',
            '::module:entities:allEntitiesDesc' => 'All entities.',
            '::module:entities:allEntitiesHelp' => 'The list of all entities.',
            '::module:entities:MyEntities' => 'My entities',
            '::module:entities:MyEntitiesDesc' => 'All entities under control.',
            '::module:entities:MyEntitiesHelp' => 'The list of all entities under control.',
            '::module:entities:KnownEntities' => 'Known entities',
            '::module:entities:KnownEntitiesDesc' => 'All known entities.',
            '::module:entities:KnownEntitiesHelp' => 'The list of all entities we known, friends or not.',
            '::module:entities:KnownEntity' => 'I known this entity',
            '::module:entities:SpecialEntities' => 'Specials entities',
            '::module:entities:SpecialEntitiesDesc' => 'Specifics entities to <i>nebule</i>.',
            '::module:entities:SpecialEntitiesHelp' => 'The list of specifics entities to <i>nebule</i>.',
            '::module:entities:UnknownEntities' => 'Unknown entities',
            '::module:entities:UnknownEntitiesDesc' => 'All unknown entities.',
            '::module:entities:UnknownEntitiesHelp' => 'The list of all others entities.',
            '::module:entities:KnownByEntities' => 'Known by entities',
            '::module:entities:KnownByEntitiesDesc' => 'All known by entities.',
            '::module:entities:KnownByEntitiesHelp' => 'The list of all entities who known me.',
            '::module:entities:SynchronizeEntity' => 'Synchronize',
            '::module:entities:SynchronizeKnownEntities' => 'Synchronize all entities',
            '::module:entities:AttribNotDisplayable' => 'Attribut not displayable!',
            '::module:entities:ListEntities' => 'List',
            '::module:entities:ListEntitiesDesc' => 'Show list of entities',
            '::module:entities:CreateEntity' => 'Create',
            '::module:entities:CreateEntityNommage' => 'Naming',
            '::module:entities:CreateEntityConfirm' => 'Confirm',
            '::module:entities:CreateEntityOther' => 'Otro',
            '::module:entities:CreateEntityAlgorithm' => 'Algoritmo',
            '::module:entities:CreateEntityTypeUndefined' => '(Undefined)',
            '::module:entities:CreateEntityTypeHuman' => 'Humano',
            '::module:entities:CreateEntityTypeRobot' => 'Robot',
            '::module:entities:CreateEntityAutonomy' => 'Entidad independiente',
            '::module:entities:CreateTheEntity' => 'Create the entity',
            '::module:entities:CreateEntityDesc' => 'Create entity.',
            '::module:entities:SearchEntity' => 'Search',
            '::module:entities:SearchEntityDesc' => 'Search entity.',
            '::module:entities:SearchEntityHelp' => 'Search a known entity.',
            '::module:entities:SearchEntityNotAllowed' => 'Entity search is disabled!',
            '::module:entities:SearchEntityLongTime' => 'The search on identifier only can take some time...',
            '::module:entities:Search:URL' => 'Address of localisation',
            '::module:entities:Search:AndOr' => 'y/o',
            '::module:entities:Search:ID' => 'Identifier',
            '::module:entities:Search:Submit' => 'Submit',
            '::module:entities:connectWith' => 'Connect with this entity',
            '::module:entities:unlock' => 'Unlock the entity',
            '::module:entities:switchTo' => 'Switch to this entity',
            '::module:entities:lock' => 'Lock entity',
            '::module:entities:seeAs' => "See as",
            '::module:entities:puppetmaster' => 'The reference entity of <i>nebule</i>, the master of keys.',
            '::module:entities:SecurityMaster' => 'The master entity of security.',
            '::module:entities:CodeMaster' => 'The master entity of code.',
            '::module:entities:DirectoryMaster' => 'The master entity of directory.',
            '::module:entities:TimeMaster' => 'The master entity of time.',
            '::module:entities:From' => 'From',
            '::module:entities:To' => 'To',
            '::module:entities:DisplayEntityMessages' => 'List of messages for %s.',
            '::module:entities:DisplayEntityPublicMessages' => 'List of public messages for %s.',
            '::module:entities:DisplayEntityPublicMessagesWarning' => 'Protected ou hidden messages are not availables.',
            '::module:entities:AuthLockHelp' => 'Unconnecting...',
            '::module:entities:AuthUnlockHelp' => 'Connecting...',
            '::module:entities:Protected' => 'Message protected.',
            '::module:entities:Obfuscated' => 'Message obfuscated.',
            '::module:entities:Desc:AttribsTitle' => "Entity's attributs",
            '::module:entities:Desc:AttribsDesc' => "All attributs of the entity's object.",
            '::module:entities:Desc:AttribsHelp' => 'All attributs of the object.',
            '::module:entities:Desc:Attrib' => 'Attribut',
            '::module:entities:Desc:Value' => 'Value',
            '::module:entities:Desc:Signer' => 'Sender',
            '::module:entities:Display:NoEntity' => 'No entity to display.',
        ],
    ];
}
