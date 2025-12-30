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
use Nebule\Library\Modules;
use Nebule\Library\ModelModuleHelp;
use Nebule\Library\ModuleTranslates;

/**
 * Ce module permet de gérer les entités.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleEntities extends Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'ent';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020251230';
    const MODULE_AUTHOR = 'Project nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2025';
    const MODULE_LOGO = '94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
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
        'keys',
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
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    const COMMAND_SYNC_KNOWN_ENTITIES = 'synknownent';
    const COMMAND_SYNC_NEBULE_ENTITIES = 'synnebent';
    const DEFAULT_ENTITIES_DISPLAY_NUMBER = 12;
    const DEFAULT_ATTRIBS_DISPLAY_NUMBER = 10;

    private string $_displayEntityEID = '';
    private ?\Nebule\Library\Entity $_displayEntityInstance = null;
    private string $_hashEntity;
    private \Nebule\Library\Node $_hashEntityObject;
    private string $_hashType;



    protected function _initialisation(): void {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_findDisplayEntity();
        $this->_hashType = $this->getNidFromData('nebule/objet/type');
        $this->_hashEntity = $this->getNidFromData('application/x-pem-file');
        $this->_hashEntityObject = $this->_cacheInstance->newNode($this->_hashEntity);
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array {
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
            /*case 'menu':
                $hookArray[2]['name'] = '::MyEntities';
                $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                $hookArray[2]['desc'] = '::MyEntitiesDesc';
                $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[8]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;*/
            case 'selfMenu':
            case 'selfMenuEntity':
                // List entities I know.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[0]) {
                    $hookArray[0]['name'] = '::KnownEntities';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[0]['desc'] = '::KnownEntitiesDesc';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List entities know me.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[11]) {
                    $hookArray[1]['name'] = '::KnownByEntities';
                    $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[1]['desc'] = '::KnownByEntitiesDesc';
                    $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List my entities.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[8]) {
                    $hookArray[2]['name'] = '::MyEntities';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[2]['desc'] = '::MyEntitiesDesc';
                    $hookArray[2]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[8]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List unknown entities.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[9]) {
                    $hookArray[3]['name'] = '::UnknownEntities';
                    $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[3]['desc'] = '::UnknownEntitiesDesc';
                    $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List special entities.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[10]) {
                    $hookArray[4]['name'] = '::SpecialEntities';
                    $hookArray[4]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[4]['desc'] = '::SpecialEntitiesDesc';
                    $hookArray[4]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[10]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // List all entities.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[12]) {
                    $hookArray[5]['name'] = '::allEntities';
                    $hookArray[5]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[5]['desc'] = '::allEntitiesDesc';
                    $hookArray[5]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // See entity properties.
                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[7]) {
                    $hookArray[6]['name'] = '::DescriptionEntity';
                    $hookArray[6]['icon'] = $this::MODULE_REGISTERED_ICONS[10];
                    $hookArray[6]['desc'] = '::DescriptionEntityDesc';
                    $hookArray[6]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_displayEntityEID
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                if ($this->_configurationInstance->checkBooleanOptions(['permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitWriteEntity'])
                    && ($this->_unlocked || $this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity'))
                ) {
                    // Create entity.
                    $hookArray[10]['name'] = '::CreateEntity';
                    $hookArray[10]['icon'] = $this::MODULE_REGISTERED_ICONS[5];
                    $hookArray[10]['desc'] = '::CreateEntityDesc';
                    $hookArray[10]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                if ($this->_configurationInstance->checkBooleanOptions(['permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject', 'permitSynchronizeLink', 'unlocked'])) {
                    // Search entity.
                    $hookArray[20]['name'] = '::SearchEntity';
                    $hookArray[20]['icon'] = Displays::DEFAULT_ICON_LF;
                    $hookArray[20]['desc'] = '::SearchEntityDesc';
                    $hookArray[20]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_displayEntityEID
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                if ($this->_displayInstance->getCurrentDisplayView() != self::MODULE_REGISTERED_VIEWS[7]) {
                    // Show keys
                    $hookArray[] = array(
                            'name' => '::EntityKeys',
                            'icon' => $this::MODULE_REGISTERED_ICONS[10],
                            'desc' => '::EntityKeys',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[13]
                                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_displayEntityEID
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                break;

            case 'selfMenuObject':
                if ($nid instanceof \Nebule\Library\Entity) {
                    $hookArray[0]['name'] = '::ShowEntity';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[10];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;

            case 'typeMenuEntity':
                if ($unlocked) {
                    // Lock entity.
                    $hookArray[0]['name'] = '::lock';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                        . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID()
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . References::COMMAND_AUTH_ENTITY_LOGOUT
                        . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;
                    if ($object != $this->_entitiesInstance->getConnectedEntityEID()) {
                        // Switch to this entity.
                        $hookArray[1]['name'] = '::seeAs';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_CONNECTED . '=' . $object;
                    }
                    // Modify entity.
                    $hookArray[2]['name'] = '::modify';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;
                } elseif ($this->_configurationInstance->getOptionAsBoolean('permitAuthenticateEntity')) {
                    // Unlock entity.
                    $hookArray[0]['name'] = '::unlock';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                        . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID()
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=login'
                        . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object;
                    if ($object != $this->_entitiesInstance->getConnectedEntityEID()) {
                        // Switch and connect to this entity.
                        $hookArray[1]['name'] = '::connectWith';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[11];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . References::COMMAND_SWITCH_GHOST . '=' . $object
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_CONNECTED . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    }
                }

                // Synchronise entity.
                $hookArray[3]['name'] = '::SynchronizeEntity';
                $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                $hookArray[3]['desc'] = '';
                $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . \Nebule\Library\ActionsEntities::SYNCHRONIZE
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[1]) {
                    // See entity.
                    $hookArray[4]['name'] = '::ShowEntity';
                    $hookArray[4]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[4]['desc'] = '';
                    $hookArray[4]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    // See entity properties.
                    $hookArray[5]['name'] = '::DescriptionEntity';
                    $hookArray[5]['icon'] = $this::MODULE_REGISTERED_ICONS[10];
                    $hookArray[5]['desc'] = '::DescriptionEntityDesc';
                    $hookArray[5]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                /*if (!$this->_applicationInstance->getMarkObject($object)) {
                    // Mark entity.
                    $hookArray[5]['name'] = '::MarkAdd';
                    $hookArray[5]['icon'] = Display::DEFAULT_ICON_MARK;
                    $hookArray[5]['desc'] = '';
                    $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $object
                        . '&' . ActionsMarks::MARK . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                }*/
                break;

            case '::DisplayMyEntities':
            case '::DisplayKnownEntity':
                // Synchroniser les entités connues.
                $hookArray[0]['name'] = '::SynchronizeKnownEntities';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . \Nebule\Library\ActionsEntities::SYNCHRONIZE
                    . '&' . self::COMMAND_SYNC_KNOWN_ENTITIES
                    . '&' . References::COMMAND_SWITCH_GHOST . '=' . $this->_entitiesInstance->getGhostEntityEID()
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                break;

            case '::DisplayAuthorityEntities':
                if (($this->_authoritiesInstance->getIsPuppetMaster($this->_entitiesInstance->getConnectedEntityInstance())
                                || $this->_configurationInstance->getOptionAsBoolean('permitActAsMaster'))
                        && $this->_entitiesInstance->getConnectedEntityIsUnlocked()
                        && $object != $this->_entitiesInstance->getConnectedEntityEID()
                        && $this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[10]
                ) {
                    // Promote as master of security.
                    if ($this->_authoritiesInstance->getIsSecurityMasterEID($object)) {
                        $req = 'x';
                        $hookArray[0]['name'] = '::UnsetSecurityMaster';
                        $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                        $hookArray[0]['desc'] = '::UnsetSecurityMaster';
                    } else {
                        $req = 'l';
                        $hookArray[0]['name'] = '::SetSecurityMaster';
                        $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                        $hookArray[0]['desc'] = '::SetSecurityMaster';
                    }
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=' . $req . '>' . References::RID_SECURITY_AUTHORITY . '>' . $object . '>' . References::RID_SECURITY_AUTHORITY
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    // Promote as master of code.
                    if ($this->_authoritiesInstance->getIsCodeMasterEID($object)) {
                        $req = 'x';
                        $hookArray[1]['name'] = '::UnsetCodeMaster';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                        $hookArray[1]['desc'] = '::UnsetCodeMaster';
                    } else {
                        $req = 'l';
                        $hookArray[1]['name'] = '::SetCodeMaster';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                        $hookArray[1]['desc'] = '::SetCodeMaster';
                    }
                    $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=' . $req . '>' . References::RID_CODE_AUTHORITY . '>' . $object . '>' . References::RID_CODE_AUTHORITY
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    // Promote as master of the directory.
                    if ($this->_authoritiesInstance->getIsDirectoryMasterEID($object)) {
                        $req = 'x';
                        $hookArray[2]['name'] = '::UnsetCodeMaster';
                        $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                        $hookArray[2]['desc'] = '::UnsetCodeMaster';
                    } else {
                        $req = 'l';
                        $hookArray[2]['name'] = '::SetCodeMaster';
                        $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                        $hookArray[2]['desc'] = '::SetCodeMaster';
                    }
                    $hookArray[2]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=' . $req . '>' . References::RID_DIRECTORY_AUTHORITY . '>' . $object . '>' . References::RID_DIRECTORY_AUTHORITY
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    // Promote as master of time.
                    if ($this->_authoritiesInstance->getIsTimeMasterEID($object)) {
                        $req = 'x';
                        $hookArray[3]['name'] = '::UnsetCodeMaster';
                        $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                        $hookArray[3]['desc'] = '::UnsetCodeMaster';
                    } else {
                        $req = 'l';
                        $hookArray[3]['name'] = '::SetCodeMaster';
                        $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                        $hookArray[3]['desc'] = '::SetCodeMaster';
                    }
                    $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . '&' . \Nebule\Library\ActionsLinks::SIGN1 . '=' . $req . '>' . References::RID_TIME_AUTHORITY . '>' . $object . '>' . References::RID_TIME_AUTHORITY
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                } else {
                    $hookArray[0]['name'] = '::SynchronizeEntity';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . \Nebule\Library\ActionsEntities::SYNCHRONIZE
                            . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                }
                break;
        }
        return $hookArray;
    }



    public function displayModule(): void {
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
            case $this::MODULE_REGISTERED_VIEWS[13]:
                $this->_displayEntityKeys();
                break;
            default:
                $this->_displayEntityDisplay();
                break;
        }
    }

    public function displayModuleInline(): void {
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
            case $this::MODULE_REGISTERED_VIEWS[13]:
                $this->_display_InlineEntityKeys();
                break;
        }
    }

    public function getCSS(): void {
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

    public function headerStyle(): void {}

    public function actions(): void { // TODO refactor
        $this->_findSynchronizeEntity();
        $this->_actionSynchronizeEntity();
        $this->_findSearchEntity();
        $this->_actionSearchEntity();
    }



    private function _findDisplayEntity(): void {
        $this->_displayEntityEID = $this->_nebuleInstance->getCurrentEntityEID();
        if ($this->_displayEntityEID != '0')
            $this->_displayEntityInstance = $this->_nebuleInstance->getCurrentEntityInstance();
        else {
            $this->_displayEntityEID = $this->_entitiesInstance->getGhostEntityEID();
            $this->_displayEntityInstance = $this->_entitiesInstance->getGhostEntityInstance();
        }
    }



    private bool $_synchronizeEntity = false;

    private function _findSynchronizeEntity(): void { // TODO refactor
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $arg = $this->getFilterInput(\Nebule\Library\ActionsEntities::SYNCHRONIZE, FILTER_FLAG_NO_ENCODE_QUOTES);

        if ($arg != ''
            && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject', 'permitSynchronizeLink', 'unlocked'))
        )
            $this->_synchronizeEntity = true;
        unset($arg);
    }

    private function _actionSynchronizeEntity(): void { // TODO refactor
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
    private ?\Nebule\Library\Node $_searchEntityInstance = null;

    private function _findSearchEntity(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $arg_url = trim((string)filter_input(INPUT_GET, 'srchurl', FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if ($arg_url != ''
            && strlen($arg_url) >= 8
        )
            $this->_searchEntityURL = $arg_url;

        $arg_id = trim((string)filter_input(INPUT_GET, 'srchid', FILTER_SANITIZE_URL, FILTER_FLAG_ENCODE_LOW));
        if (\Nebule\Library\Node::checkNID($arg_id)
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
            $this->_searchEntityInstance = $this->_cacheInstance->newNode($arg_url, \Nebule\Library\Cache::TYPE_ENTITY);
        }
    }

    private function _actionSearchEntity(): void {
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



    private function _displayEntityDisplay(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsEntities()->getCreate()) {
            $this->_displaySimpleTitle('::CreateEntity', $this::MODULE_REGISTERED_ICONS[5]);
            $this->_displayEntityCreateNew();
        } else {
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
            $instance->setSelfHookName('typeMenuEntity');
            $instance->setEnableStatus(false);
            $instance->setSize(\Nebule\Library\DisplayItem::SIZE_LARGE);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_LONG);
            $instance->display();

            echo '</div>' . "\n";
            echo '</div>' . "\n";
        }
    }



    private function _displayEntityChange(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        echo '<div class="layout-list">' . "\n";
        echo '<div class="textListObjects">' . "\n";

        $entity = $this->_displayEntityInstance;
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);

        $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
        $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instance->setSocial('all');
        $instance->setNID($entity);
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setEnableName(true);
        $instance->setEnableRefs(false);
        $instance->setEnableFlags(true);
        $instance->setEnableFlagUnlocked(true);
        $instance->setEnableFlagProtection(false);
        $instance->setEnableFlagObfuscate(false);
        $instance->setEnableFlagState(true);
        $instance->setEnableFlagEmotions(false);
        $instance->setEnableStatus(false);
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        $instance->setEnableLink(true);
        $instance->setFlagUnlocked($entity->getHavePrivateKeyPassword());
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->setIcon($instanceIcon);
        $instanceList->addItem($instance);

        if ($this->_actionInstance->getInstanceActionsEntities()->getChangeNameTry()
                || $this->_actionInstance->getInstanceActionsEntities()->getChangePrefixTry()
                || $this->_actionInstance->getInstanceActionsEntities()->getChangeSuffixTry()
                || $this->_actionInstance->getInstanceActionsEntities()->getChangeFirstnameTry()
                || $this->_actionInstance->getInstanceActionsEntities()->getChangeNicknameTry()
        ) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            if (($this->_actionInstance->getInstanceActionsEntities()->getChangeNameTry() && $this->_actionInstance->getInstanceActionsEntities()->getChangeNameOk())
                    || ($this->_actionInstance->getInstanceActionsEntities()->getChangePrefixTry() && $this->_actionInstance->getInstanceActionsEntities()->getChangePrefixOk())
                    || ($this->_actionInstance->getInstanceActionsEntities()->getChangeSuffixTry() && $this->_actionInstance->getInstanceActionsEntities()->getChangeSuffixOk())
                    || ($this->_actionInstance->getInstanceActionsEntities()->getChangeFirstnameTry() && $this->_actionInstance->getInstanceActionsEntities()->getChangeFirstnameOk())
                    || ($this->_actionInstance->getInstanceActionsEntities()->getChangeNicknameTry() && $this->_actionInstance->getInstanceActionsEntities()->getChangeNicknameOk())
            ) {
                $instance->setMessage('::EntityModified');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_OK);
            } else {
                $instance->setMessage('::EntityNotModified');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
            }
            $instance->setSocial('all');
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            $instanceList->addItem($instance);
        }

        if ($this->_actionInstance->getInstanceActionsEntities()->getChangePasswordTry()) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setSocial('all');
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            if ($this->_actionInstance->getInstanceActionsEntities()->getChangePasswordOk()) {
                $instance->setMessage('::PasswordModified');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_OK);
            } else {
                $instance->setMessage('::PasswordNotModified');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
            }
            $instanceList->addItem($instance);
        }
        $commonLink = '?'
                . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

        if ( $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitWriteEntity', 'unlocked'))) {
            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue($entity->getPrefixName('self'));
            $instance->setInputName(\Nebule\Library\ActionsEntities::CHANGE_PREFIX);
            $instance->setLink($commonLink);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_PREFIX);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue($entity->getFirstname('self'));
            $instance->setInputName(\Nebule\Library\ActionsEntities::CHANGE_FIRSTNAME);
            $instance->setLink($commonLink);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_PRENOM);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue($entity->getSurname('self'));
            $instance->setInputName(\Nebule\Library\ActionsEntities::CHANGE_NICKNAME);
            $instance->setLink($commonLink);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_SURNOM);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue($entity->getName('self'));
            $instance->setInputName(\Nebule\Library\ActionsEntities::CHANGE_NAME);
            $instance->setLink($commonLink);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_NOM);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue($entity->getSuffixName('self'));
            $instance->setInputName(\Nebule\Library\ActionsEntities::CHANGE_SUFFIX);
            $instance->setLink($commonLink);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_SUFFIX);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_PASSWORD);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsEntities::CHANGE_PASSWORD1);
            $instance->setLink($commonLink . '&' . \Nebule\Library\ActionsEntities::CHANGE_PASSWORD);
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instance->setIconText('::Password');
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_PASSWORD);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsEntities::CHANGE_PASSWORD2);
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(true);
            $instance->setWithSubmit(true);
            $instance->setIconText('::confirm');
        } else {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setMessage('::err_NotPermit');
            $instance->setSocial('all');
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        }
        $instanceList->addItem($instance);

        $instanceList->setOnePerLine();
        $instanceList->display();

        /*if ( $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitWriteEntity', 'unlocked'))) {
            $instanceList = new DisplayList($this->_applicationInstance);
            $instanceList->setSize(DisplayItem::SIZE_MEDIUM);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_PASSWORD);
            $instance->setInputValue($entity->getSuffixName());
            $instance->setInputName(ActionsEntities::CHANGE_PASSWORD1);
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instance->setIconText('::Password');
            $instanceList->addItem($instance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_PASSWORD);
            $instance->setInputValue($entity->getSuffixName());
            $instance->setInputName(ActionsEntities::CHANGE_PASSWORD2);
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(true);
            $instance->setWithSubmit(true);
            $instance->setIconText('::confirm');
            $instanceList->addItem($instance);

            $instanceList->setOnePerLine();
            echo '<form method="post" action="' . $commonLink . '&' . ActionsEntities::CHANGE_PASSWORD . '">' . "\n";
            $instanceList->display();
            echo '</form>' . "\n";
        }*/

        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }



    private function _displayEntityLogs(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_entitiesInstance->getGhostEntityEID() != $this->_entitiesInstance->getConnectedEntityEID()) {
            $this->_displayInstance->displayObjectDivHeaderH1($this->_displayEntityInstance, '', $this->_displayEntityEID);
        }
        $this->_displaySimpleTitle('::ObjectTitle1', $this::MODULE_REGISTERED_ICONS[7]);

        // Extrait des propriétés de l'objet.
        $entity = $this->_displayEntityEID;
        $instance = $this->_displayEntityInstance;
        ?>

        <div class="moduleEntitiesActionText">
            <p>
                <?php
                if ($entity == $this->_entitiesInstance->getGhostEntityEID() && $this->_unlocked) {
                    echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                        '::DisplayEntityMessages',
                        $this->_displayInstance->convertInlineObjectColorIconName($instance));
                    $dispWarn = false;
                } else {
                    echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                        '::DisplayEntityPublicMessages',
                        $this->_displayInstance->convertInlineObjectColorIconName($instance));
                    $dispWarn = true;
                }
                ?>

            </p>
        </div>
        <?php
        // Si besoin, affiche le message d'information.
        if ($dispWarn) {
            $this->_displayInstance->displayMessageInformation(
                $this->_translateInstance->getTranslate('::DisplayEntityPublicMessagesWarning'));
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
                        $objectInstance = new \Nebule\Library\Node($this->_nebuleInstance, $object);
                        ?>

                        <div class="moduleEntitiesActionDivIcon">
                            <?php $instance = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_LC); $this->_displayInstance->displayUpdateImage($instance); ?>
                        </div>
                        <div>
                            <p class="moduleEntitiesActionDate">
                                <?php $this->_displayInstance->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="moduleEntitiesActionTitle">
                                <?php echo $this->_translateInstance->getTranslate('::Obfuscated'); ?>
                            </p>
                            <p class="moduleEntitiesActionFromTo">
                                <?php echo $this->_translateInstance->getTranslate('::From'); ?>
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
                        $objectInstance = new \Nebule\Library\Node($this->_nebuleInstance, $object);
                        ?>

                        <div class="moduleEntitiesActionDivIcon">
                            <?php $instance = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_LK); $this->_displayInstance->displayUpdateImage($instance); ?>
                        </div>
                        <div>
                            <p class="moduleEntitiesActionDate">
                                <?php $this->_displayInstance->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="moduleEntitiesActionTitle">
                                <?php echo $this->_translateInstance->getTranslate('::Protected'); ?>
                            </p>
                            <p class="moduleEntitiesActionFromTo">
                                <?php echo $this->_translateInstance->getTranslate('::From'); ?>
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
                        $objectInstance = new \Nebule\Library\Node($this->_nebuleInstance, $object);
                        ?>

                        <div class="moduleEntitiesActionDivIcon">
                            <?php $this->_displayInstance->displayObjectColorIcon(
                                $objectInstance, References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                                . '&' . References::COMMAND_SELECT_OBJECT . '=' . $link->getParsed()['bl/rl/nid2']); ?>
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
                                <?php echo $this->_translateInstance->getTranslate('::From'); ?>
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



    private function _displayEntityActs(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_entitiesInstance->getGhostEntityEID() != $this->_entitiesInstance->getConnectedEntityEID()) {
            $this->_displayInstance->displayObjectDivHeaderH1($this->_displayEntityInstance, '', $this->_displayEntityEID);
        }
        $this->_displaySimpleTitle('::ObjectTitle2', $this::MODULE_REGISTERED_ICONS[8]);

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
                        '::DisplayEntityPublicMessages',
                        $this->_displayInstance->convertInlineObjectColorIconName($entity));
                    $dispWarn = true;
                } // Sinon, affiche les messages de l'entité courante.
                else {
                    $entity = $this->_entitiesInstance->getGhostEntityInstance();
                    $id = $this->_entitiesInstance->getGhostEntityEID();
                    $owned = true;
                    if ($this->_unlocked) {
                        echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                            '::DisplayEntityMessages',
                            $this->_displayInstance->convertInlineObjectColorIconName($entity));
                    } else {
                        echo $this->_applicationInstance->getTranslateInstance()->getTranslate(
                            '::DisplayEntityPublicMessages',
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
            $this->_displayInstance->displayMessageInformation(
                $this->_translateInstance->getTranslate('::DisplayEntityPublicMessagesWarning'));
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
                            <?php echo $this->_translateInstance->getTranslate('::To'); ?>
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



    private function _displayMyEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::MyEntities', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayInstance->registerInlineContentID('my_entities');
    }

    private function _display_InlineMyEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $entities = array();
        $this->_displayEntitiesList($entities);
    }



    private function _displayKnownEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::KnownEntities', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayInstance->registerInlineContentID('known_entities');
    }

    private function _display_InlineKnownEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_entitiesInstance->getGhostEntityEID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter, 'self');

        $entities = array();
        foreach ($links as $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_ENTITY);
            $entities[$link->getParsed()['bl/rl/nid2']] = $instance;
        }

        $this->_displayEntitiesList($entities, \Nebule\Library\DisplayItem::RATIO_LONG, $listOkEntities);
    }



    private function _displayKnownByEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::KnownByEntities', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayInstance->registerInlineContentID('knownentities');
    }

    private function _display_InlineKnownByEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid2' => $this->_entitiesInstance->getGhostEntityEID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter, 'all');

        $entities = array();
        foreach ($links as $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_ENTITY);
            $entities[$link->getParsed()['bl/rl/nid2']] = $instance;
        }

        $this->_displayEntitiesList($entities, \Nebule\Library\DisplayItem::RATIO_LONG, $listOkEntities);
    }



    private function _displayUnknownEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::UnknownEntities', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayInstance->registerInlineContentID('unknownentities');
    }

    private function _display_InlineUnknownEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $listOkEntities = $this->_authoritiesInstance->getSpecialEntitiesID();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_entitiesInstance->getGhostEntityEID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter, 'all');
        foreach ($links as $link) {
            $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
        }

        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid2' => $this->_entitiesInstance->getGhostEntityEID(),
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter, 'all');

        foreach ($links as $link) {
            $listOkEntities[$link->getParsed()['bl/rl/nid1']] = true;
        }

        $this->_displayEntitiesList($this->_entitiesInstance->getListEntitiesInstances(), \Nebule\Library\DisplayItem::SIZE_LARGE, $listOkEntities, array(), array(), false, true, true);
    }



    private function _displayAllEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::allEntities', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayInstance->registerInlineContentID('allentities');
    }

    private function _display_InlineAllEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayEntitiesList($this->_entitiesInstance->getListEntitiesInstances());
    }



    /**
     * Affiche la liste des entités spéciales.
     *
     * @return void
     */
    private function _displaySpecialEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::SpecialEntities', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayInstance->registerInlineContentID('specialentities');
    }

    private function _getDisplaySpecialEntities(\Nebule\Library\Entity $entity, \Nebule\Library\Node $instanceIcon, array $refs): \Nebule\Library\DisplayObject {
        $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instance->setSocial('self');
        $instance->setNID($entity);
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setIcon($instanceIcon);
        $instance->setEnableName(true);
        $instance->setEnableFlags(true);
        $instance->setEnableFlagState(true);
        $instance->setEnableFlagEmotions(false);
        $instance->setEnableFlagUnlocked(true);
        $instance->setFlagUnlocked($entity->getHavePrivateKeyPassword());
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        if (sizeof($refs) != 0) {
            $instance->setEnableRefs(true);
            $instance->setRefs($refs[$entity->getID()]);
        }
        else
            $instance->setEnableRefs(false);
        $instance->setSelfHookName('::DisplayAuthorityEntities');
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
        if ($this->_entitiesInstance->getServerEntityEID() == $entity->getID())
            $messages[] = 'Server entity';
        if ($this->_entitiesInstance->getDefaultEntityEID() == $entity->getID())
            $messages[] = 'Default entity';
        //if ($this->_entitiesInstance->getGhostEntityEID() == $entity->getID())
        //    $messages[] = 'Ghost entity';
        //if ($this->_entitiesInstance->getConnectedEntityEID() == $entity->getID())
        //    $messages[] = 'Connected entity';
        if (sizeof($messages) > 0)
            $instance->setFlagMessageList($messages);
        return $instance;
    }

    private function _display_InlineSpecialEntitiesList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $listOkEntities = array();

        $instanceList->addItem($this->_getDisplaySpecialEntities($this->_authoritiesInstance->getPuppetmasterInstance(), $instanceIcon, array()));
        $listOkEntities[$this->_authoritiesInstance->getPuppetmasterEID()] = $this->_authoritiesInstance->getPuppetmasterEID();

        foreach ($this->_authoritiesInstance->getSecurityAuthoritiesInstance() as $entity) {
            $instanceList->addItem($this->_getDisplaySpecialEntities($entity, $instanceIcon, $this->_authoritiesInstance->getSecuritySignersInstance()));
            $listOkEntities[$entity->getID()] = $entity->getID();
        }

        foreach ($this->_authoritiesInstance->getCodeAuthoritiesInstance() as $entity) {
            $instanceList->addItem($this->_getDisplaySpecialEntities($entity, $instanceIcon, $this->_authoritiesInstance->getCodeSignersInstance()));
            $listOkEntities[$entity->getID()] = $entity->getID();
        }

        foreach ($this->_authoritiesInstance->getDirectoryAuthoritiesInstance() as $entity) {
            $instanceList->addItem($this->_getDisplaySpecialEntities($entity, $instanceIcon, $this->_authoritiesInstance->getDirectorySignersInstance()));
            $listOkEntities[$entity->getID()] = $entity->getID();
        }

        foreach ($this->_authoritiesInstance->getTimeAuthoritiesInstance() as $entity) {
            $instanceList->addItem($this->_getDisplaySpecialEntities($entity, $instanceIcon, $this->_authoritiesInstance->getTimeSignersInstance()));
            $listOkEntities[$entity->getID()] = $entity->getID();
        }

        $instanceList->addItem($this->_getDisplaySpecialEntities($this->_entitiesInstance->getServerEntityInstance(), $instanceIcon, array()));
        $listOkEntities[$this->_entitiesInstance->getServerEntityEID()] = $this->_entitiesInstance->getServerEntityEID();

        $listOkEntities[$this->_entitiesInstance->getConnectedEntityEID()] = $this->_entitiesInstance->getConnectedEntityEID();

        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();

        if (($this->_authoritiesInstance->getIsPuppetMaster($this->_entitiesInstance->getConnectedEntityInstance())
                        || $this->_configurationInstance->getOptionAsBoolean('permitActAsMaster'))
                && $this->_entitiesInstance->getConnectedEntityIsUnlocked()
        ) {
            $this->_displaySimpleTitle('::KnownEntitiesDesc', $this::MODULE_REGISTERED_ICONS[4]);
            $this->_displayEntitiesList($this->_entitiesInstance->getListEntitiesInstances(),
                    \Nebule\Library\DisplayItem::RATIO_SHORT,
                    $listOkEntities,
                    array(),
                    array(),
                    false,
                    true,
                    false,
                    '::DisplayAuthorityEntities');
        }
    }



    /**
     * Affiche la création d'une entité.
     */
    private function _displayEntityCreate(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::CreateEntity', $this::MODULE_REGISTERED_ICONS[5]);
        $this->_displayEntityCreateForm();
        // MyEntities() view displays the result of the creation
    }

    private function _displayEntityCreateNew(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsEntities()->getCreate()) {
            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
            if (!$this->_applicationInstance->getActionInstance()->getInstanceActionsEntities()->getCreateError()) {
                $this->_displayEntityInstance = $this->_applicationInstance->getActionInstance()->getInstanceActionsEntities()->getCreateInstance();
                $this->_displayEntityEID = $this->_applicationInstance->getActionInstance()->getInstanceActionsEntities()->getCreateEID();

                $instance->setMessage('::EntityCreated');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_OK);
                $instance->setIconText('::OK');
                $instanceList->addItem($instance);

                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('self');
                $instance->setNID($this->_displayEntityInstance);
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
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setStatus('');
                $instance->setEnableFlagUnlocked(true);
                $instance->setSelfHookName('typeMenuEntity');
                $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['oent']); // FIXME
                $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
                $instance->setIcon($instanceIcon2);
                $instanceList->addItem($instance);

                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('all');
                $instance->setNID($this->_applicationInstance->getActionInstance()->getInstanceActionsEntities()->getCreateKeyInstance());
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
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setStatus('');
                $instance->setEnableFlagUnlocked(false);
                $instance->setName($this->_translateInstance->getTranslate('::privateKey'));
                $instance->setType(References::REFERENCE_OBJECT_TEXT);
                $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['key']); // FIXME
                $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
                $instance->setIcon($instanceIcon2);
            } else {
                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::EntityNotCreated');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setIconText('::ERROR');
                $instanceList->addItem($instance);

                $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::EntityNotCreated');
                $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setIconText($this->_applicationInstance->getActionInstance()->getInstanceActionsEntities()->getCreateErrorMessage());
            }
            $instanceList->addItem($instance);
            $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
            $instanceList->setOnePerLine();
            $instanceList->display();
        }
        echo '<br />' . "\n";
    }

    private function _displayEntityCreateForm(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ( $this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateEntity')
            && ($this->_unlocked
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity')
            )
        ) {
            $commonLink = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . '&' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . \Nebule\Library\ActionsEntities::CREATE
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_PREFIX);
            $instance->setLink($commonLink);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_PREFIX);
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_FIRSTNAME);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_PRENOM);
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_NICKNAME);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_SURNOM);
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_NAME);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_NOM);
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_WARN_RID);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_SUFFIX);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_SUFFIX);
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_ALGORITHM);
            $instance->setIconText('::CreateEntityAlgorithm');
            $list = array();
            foreach ($this->_cryptoInstance->getAlgorithmList(\Nebule\Library\Crypto::TYPE_ASYMMETRIC) as $algo)
                $list[$algo] = $algo;
            $instance->setSelectList($list);
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_TYPE);
            $instance->setIconText('nebule/objet/entite/type');
            $instance->setSelectList(array(
                    'undef' => $this->_translateInstance->getTranslate('::CreateEntityTypeUndefined'),
                    'human' => $this->_translateInstance->getTranslate('::CreateEntityTypeHuman'),
                    'robot' => $this->_translateInstance->getTranslate('::CreateEntityTypeRobot'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
                $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
                $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
                $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_AUTONOMY);
                $instance->setIconText('::CreateEntityAutonomy');
                $instance->setSelectList(array(
                        'y' => $this->_translateInstance->getTranslate('::yes'),
                        'n' => $this->_translateInstance->getTranslate('::no'),
                ));
                $instance->setWithFormOpen(false);
                $instance->setWithFormClose(false);
                $instance->setWithSubmit(false);
                $instanceList->addItem($instance);
            }

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_PASSWORD);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_PASSWORD1);
            $instance->setIconText('::Password');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_PASSWORD);
            $instance->setInputValue('');
            $instance->setInputName(\Nebule\Library\ActionsEntities::CREATE_PASSWORD2);
            $instance->setIconText('::confirm');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            //$instance->setLink($commonLink);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_TEXT);
            $instance->setMessage('::CreateTheEntity');
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::CreateTheEntity'));
            $instance->setIconText('::confirm');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(true);
            $instance->setWithSubmit(true);
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_PLAY_RID);
            //$instance->setLink($commonLink);
            $instanceList->addItem($instance);

            $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
            $instanceList->setOnePerLine();
            $instanceList->display();
        } else {
            $instance = new \Nebule\Library\DisplayNotify($this->_applicationInstance);
            $instance->setMessage('::err_NotPermit');
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_ERROR);
            $instance->display();
        }
    }



    private function _displayEntitySearch(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::SearchEntity', Displays::DEFAULT_ICON_LF);

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
                            <td align="right"><?php echo $this->_translateInstance->getTranslate('::Search:URL') ?>
                            </td>
                            <td>:</td>
                            <td><input type="text" name="srchurl" size="80"
                                       value="<?php echo $this->_searchEntityURL; ?>"></td>
                        </tr>
                        <tr>
                            <td align="right"><?php echo $this->_translateInstance->getTranslate('::Search:AndOr') ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align="right"><?php echo $this->_translateInstance->getTranslate('::Search:ID') ?>
                            </td>
                            <td>:</td>
                            <td><input type="text" name="srchid" size="80"
                                       value="<?php echo $this->_searchEntityID; ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><input type="submit"
                                       value="<?php echo $this->_translateInstance->getTranslate('::Search:Submit'); ?>">
                            </td>
                        </tr>
                    </table>
                </form>
                <?php

                ?>

                </p>
            </div>
            <?php
            $this->_displayInstance->displayMessageInformation('::SearchEntityLongTime');
        } else {
            $this->_displayInstance->displayMessageWarning('::SearchEntityNotAllowed');
        }
    }


    private function _display_InlineEntitySearch(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // TODO
    }



    private function _displayEntityProp(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::Desc:AttribsTitle', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayInstance->registerInlineContentID('properties');
    }

    private function _display_InlineEntityProp(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $entity = $this->_displayEntityInstance;
        $this->_metrologyInstance->addLog('DEBUGGING entity EID=' . $entity->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
        $links = array();
        $filter = array(
                'bl/rl/req' => 'l',
                'bl/rl/nid1' => $entity->getID(),
                'bl/rl/nid4' => '',
        );
        $entity->getLinks($links, $filter, 'myself');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
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

            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
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
        $entity->getLinks($links, $filter, 'myself');
        foreach ($links as $link) {
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
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

        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->display();
    }

    private function _displayEntityKeys(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::EntityKeys', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayInstance->registerInlineContentID('keys');
    }

    private function _display_InlineEntityKeys(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $entity = $this->_displayEntityInstance;
        $links = array();
        $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $entity->getID(),
                'bl/rl/nid3' => $this->_nebuleInstance->getFromDataNID(References::REFERENCE_PRIVATE_KEY),
        );
        $entity->getLinks($links, $filter, 'self', false);

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($links as $link) {
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instanceNode = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
            $instance->setNID($instanceNode);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableLink(true);
            $instance->setEnableRefs(true);
            $instance->setRefs($link->getSignersEID());
            $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['key']);
            $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
            $instance->setIcon($instanceIcon2);
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->display();
    }



    private function _displayEntitiesList(
            array  $listEntities,
            string $ratio = \Nebule\Library\DisplayItem::RATIO_SHORT,
            array  $listOkEntities = array(),
            array  $listDesc = array(),
            array  $listSigners = array(),
            bool   $allowDouble = false,
            bool   $showState = true,
            bool   $showEmotions = false,
            string $hookName = 'typeMenuEntity'): void
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
            $instance->setSelfHookName($hookName);
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
            '::ModuleName' => 'Module des entités',
            '::MenuName' => 'Entités',
            '::ModuleDescription' => 'Module de gestion des entités',
            '::ModuleHelp' => "Ce module permet de voir les entités, de gérer les relations et de changer l'entité en cours d'utilisation.",
            '::AppTitle1' => 'Entités',
            '::AppDesc1' => 'Gestion des entités',
            '::display:ListEntities' => 'Lister les entités',
            '::EntityCreated' => 'Nouvelle entité créée',
            '::EntityNotCreated' => "La nouvelle entité n'a pas pu être créée",
            '::ShowEntity' => "Voir l'entité",
            '::DescriptionEntity' => "L'entité",
            '::DescriptionEntityDesc' => "Description de l'entité",
            '::allEntities' => 'Toutes les entités',
            '::allEntitiesDesc' => 'Toutes les entités',
            '::allEntitiesHelp' => 'La liste de toutes les entités',
            '::MyEntities' => 'Mes entités',
            '::MyEntitiesDesc' => 'Toutes les entités sous contrôle',
            '::MyEntitiesHelp' => "La liste des entités sous contrôle, c'est à dire avec lesquelles ont peut instantanément basculer",
            '::KnownEntities' => 'Entités connues',
            '::KnownEntitiesDesc' => 'Toutes les entités connues',
            '::KnownEntitiesHelp' => "La liste des entités que l'on connait, amies ou pas",
            '::KnownEntity' => 'Je connais cette entité',
            '::SpecialEntities' => 'entités spéciales',
            '::SpecialEntitiesDesc' => 'Les entités spécifiques à <i>nebule</i>',
            '::SpecialEntitiesHelp' => 'La liste des entités spécifiques à <i>nebule</i>',
            '::UnknownEntities' => 'Entités inconnues',
            '::UnknownEntitiesDesc' => 'Toutes les autres entités, non connues',
            '::UnknownEntitiesHelp' => "La liste des entités que l'on connait pas",
            '::KnownByEntities' => 'Connus de ces entités',
            '::KnownByEntitiesDesc' => 'Toutes les entités qui me connaissent',
            '::KnownByEntitiesHelp' => "La liste des entités qui me connaissent",
            '::SynchronizeEntity' => 'Synchroniser',
            '::SynchronizeKnownEntities' => 'Synchroniser toutes les entités',
            '::AttribNotDisplayable' => 'Propriété non affichable !',
            '::ListEntities' => 'Lister',
            '::ListEntitiesDesc' => 'Lister les entités',
            '::CreateEntity' => 'Créer',
            '::naming' => 'Nommage',
            '::CreateEntityOther' => 'Autre',
            '::CreateEntityAlgorithm' => 'Algorithme',
            '::CreateEntityTypeUndefined' => '(Indéfini)',
            '::CreateEntityTypeHuman' => 'Humain',
            '::CreateEntityTypeRobot' => 'Robot',
            '::CreateEntityAutonomy' => 'Entité indépendante',
            '::CreateTheEntity' => "Créer l'entité",
            '::CreateEntityDesc' => 'Créer une entité',
            '::SearchEntity' => 'Chercher',
            '::SearchEntityDesc' => 'Rechercher une entité',
            '::SearchEntityHelp' => 'Rechercher une entité connue',
            '::SearchEntityNotAllowed' => "La recherche d'entité est désactivée !",
            '::SearchEntityLongTime' => 'La recherche sur identifiant uniquement peut prendre du temps...',
            '::Search:URL' => 'Adresse de présence',
            '::Search:AndOr' => 'et/ou',
            '::Search:ID' => 'Identifiant',
            '::Search:Submit' => 'Rechercher',
            '::connectWith' => 'Se connecter avec cette entité',
            '::unlock' => 'Déverrouiller cette entité',
            '::switchTo' => 'Se connecter avec cette entité',
            '::lock' => "Verrouiller l'entité",
            '::seeAs' => 'Voir en tant que',
            '::modify' => 'Modifier',
            '::ModifyTheEntity' => "Modifier l'entité",
            '::EntityModified' => 'Entité modifiée',
            '::EntityNotModified' => 'Entité non modifiée',
            '::PasswordModified' => 'Mot de passe modifié',
            '::PasswordNotModified' => 'Mot de passe non modifié',
            '::puppetmaster' => "L'entité de référence de <i>nebule</i>, le maître des clés",
            '::SecurityMaster' => "L'entité maîtresse de la sécurité",
            '::CodeMaster' => "L'entité maîtresse du code",
            '::DirectoryMaster' => "L'entité maîtresse de l'annuaire",
            '::TimeMaster' => "L'entité maîtresse du temps",
            '::SetSecurityMaster' => 'Promeut entité maîtresse de la sécurité',
            '::UnsetSecurityMaster' => 'Révoque entité maîtresse de la sécurité',
            '::SetCodeMaster' => 'Promeut entité maîtresse du code',
            '::UnsetCodeMaster' => 'Révoque entité maîtresse du code',
            '::SetDirectoryMaster' => "Promeut entité maîtresse de l'annuaire",
            '::UnsetDirectoryMaster' => "Révoque entité maîtresse de l'annuaire",
            '::SetTimeMaster' => 'Promeut entité maîtresse du temps',
            '::UnsetTimeMaster' => 'Révoque entité maîtresse du temps',
            '::From' => 'De',
            '::To' => 'Pour',
            '::DisplayEntityMessages' => 'Liste des messages de %s',
            '::DisplayEntityPublicMessages' => 'Liste des messages publics de %s',
            '::DisplayEntityPublicMessagesWarning' => 'Les messages protégés ou dissimulés ne sont pas accessibles',
            '::AuthLockHelp' => 'Se déconnecter...',
            '::AuthUnlockHelp' => 'Se connecter...',
            '::Protected' => 'Message protégé',
            '::Obfuscated' => 'Message dissimulé',
            '::Desc:AttribsTitle' => "Propriétés de l'entité",
            '::Desc:AttribsDesc' => "Toutes les propriétés de l'objet de l'entité",
            '::Desc:AttribsHelp' => "Toutes les propriétés de l'objet",
            '::Desc:Attrib' => 'Propriété',
            '::Desc:Value' => 'Valeur',
            '::Desc:Signer' => 'Émetteur',
            '::Display:NoEntity' => "Pas d'entité à afficher",
            '::EntityKeys' => "Clés de l'entité",
        ],
        'en-en' => [
            '::ModuleName' => 'Entities module',
            '::MenuName' => 'Entities',
            '::ModuleDescription' => 'Module to manage entities',
            '::ModuleHelp' => 'This module permit to see entities, to manage related and to change of current entity.',
            '::AppTitle1' => 'Entities',
            '::AppDesc1' => 'Manage entities',
            '::display:SynchronizeEntities' => 'Synchronize current entity',
            '::display:ListEntities' => 'Show list of entities',
            '::EntityCreated' => 'New entity created',
            '::EntityNotCreated' => "The new entity can't be created",
            '::ShowEntity' => 'See the entity',
            '::DescriptionEntity' => 'This entity',
            '::DescriptionEntityDesc' => 'About this entity',
            '::allEntities' => 'All entities',
            '::allEntitiesDesc' => 'All entities',
            '::allEntitiesHelp' => 'The list of all entities',
            '::MyEntities' => 'My entities',
            '::MyEntitiesDesc' => 'All entities under control',
            '::MyEntitiesHelp' => 'The list of all entities under control',
            '::KnownEntities' => 'Known entities',
            '::KnownEntitiesDesc' => 'All known entities',
            '::KnownEntitiesHelp' => 'The list of all entities we known, friends or not',
            '::KnownEntity' => 'I known this entity',
            '::SpecialEntities' => 'Specials entities',
            '::SpecialEntitiesDesc' => 'Specifics entities to <i>nebule</i>',
            '::SpecialEntitiesHelp' => 'The list of specific entities to <i>nebule</i>',
            '::UnknownEntities' => 'Unknown entities',
            '::UnknownEntitiesDesc' => 'All unknown entities',
            '::UnknownEntitiesHelp' => 'The list of all others entities',
            '::KnownByEntities' => 'Known by entities',
            '::KnownByEntitiesDesc' => 'All known by entities',
            '::KnownByEntitiesHelp' => 'The list of all entities who known me',
            '::SynchronizeEntity' => 'Synchronize',
            '::SynchronizeKnownEntities' => 'Synchronize all entities',
            '::AttribNotDisplayable' => 'Attribut not displayable!',
            '::ListEntities' => 'List',
            '::ListEntitiesDesc' => 'Show list of entities',
            '::CreateEntity' => 'Create',
            '::naming' => 'Naming',
            '::CreateEntityOther' => 'Other',
            '::CreateEntityAlgorithm' => 'Algorithm',
            '::CreateEntityTypeUndefined' => '(Undefined)',
            '::CreateEntityTypeHuman' => 'Human',
            '::CreateEntityTypeRobot' => 'Robot',
            '::CreateEntityAutonomy' => 'Independent entity',
            '::CreateTheEntity' => 'Create the entity',
            '::CreateEntityDesc' => 'Create entity',
            '::SearchEntity' => 'Search',
            '::SearchEntityDesc' => 'Search entity',
            '::SearchEntityHelp' => 'Search a known entity',
            '::SearchEntityNotAllowed' => 'Entity search is disabled!',
            '::SearchEntityLongTime' => 'The search on identifier only can take some time...',
            '::Search:URL' => 'Address of localisation',
            '::Search:AndOr' => 'and/or',
            '::Search:ID' => 'Identifier',
            '::Search:Submit' => 'Submit',
            '::connectWith' => 'Connect with this entity',
            '::unlock' => 'Unlock the entity',
            '::switchTo' => 'Switch to this entity',
            '::lock' => 'Lock entity',
            '::seeAs' => 'See as',
            '::modify' => 'Modify',
            '::ModifyTheEntity' => 'Modify the entity',
            '::EntityModified' => 'Entity modified',
            '::EntityNotModified' => 'Entity not modified',
            '::PasswordModified' => 'Password modified',
            '::PasswordNotModified' => 'Password not modified',
            '::puppetmaster' => 'The reference entity of <i>nebule</i>, the master of keys',
            '::SecurityMaster' => 'The master entity of security',
            '::CodeMaster' => 'The master entity of code',
            '::DirectoryMaster' => 'The master entity of directory',
            '::TimeMaster' => 'The master entity of time',
            '::SetSecurityMaster' => 'Promote as master entity of security',
            '::UnsetSecurityMaster' => 'Fire as master entity of security',
            '::SetCodeMaster' => 'Promote as master entity of code',
            '::UnsetCodeMaster' => 'Fire as master entity of code',
            '::SetDirectoryMaster' => 'Promote as master entity of directory',
            '::UnsetDirectoryMaster' => 'Fire as master entity of directory',
            '::SetTimeMaster' => 'Promote as master entity of time',
            '::UnsetTimeMaster' => 'Fire as master entity of time',
            '::From' => 'From',
            '::To' => 'To',
            '::DisplayEntityMessages' => 'List of messages for %s',
            '::DisplayEntityPublicMessages' => 'List of public messages for %s',
            '::DisplayEntityPublicMessagesWarning' => 'Protected ou hidden messages are not available',
            '::AuthLockHelp' => 'Unconnecting...',
            '::AuthUnlockHelp' => 'Connecting...',
            '::Protected' => 'Message protected',
            '::Obfuscated' => 'Message obfuscated',
            '::Desc:AttribsTitle' => "Entity's attributs",
            '::Desc:AttribsDesc' => "All attributs of the entity's object",
            '::Desc:AttribsHelp' => 'All attributs of the object',
            '::Desc:Attrib' => 'Attribut',
            '::Desc:Value' => 'Value',
            '::Desc:Signer' => 'Sender',
            '::Display:NoEntity' => 'No entity to display',
            '::EntityKeys' => "Entity's keys",
        ],
        'es-co' => [
            '::ModuleName' => 'Módulo de entidades',
            '::MenuName' => 'Entidades',
            '::ModuleDescription' => 'Módulo para gestionar entidades',
            '::ModuleHelp' => 'Este módulo permite ver entidades, gestionar las relacionadas y cambiar de entidad actual.',
            '::AppTitle1' => 'Entidades',
            '::AppDesc1' => 'Gestionar entidades',
            '::display:SynchronizeEntities' => 'Sincronizar entidad actual',
            '::display:ListEntities' => 'Mostrar lista de entidades',
            '::EntityCreated' => 'Nueva entidad creada',
            '::EntityNotCreated' => 'No se pudo crear la nueva entidad',
            '::ShowEntity' => 'Ver la entidad',
            '::DescriptionEntity' => 'Esta entidad',
            '::DescriptionEntityDesc' => 'Acerca de esta entidad',
            '::allEntities' => 'Todas las entidades',
            '::allEntitiesDesc' => 'Todas las entidades',
            '::allEntitiesHelp' => 'La lista de todas las entidades',
            '::MyEntities' => 'Mis entidades',
            '::MyEntitiesDesc' => 'Todas las entidades bajo control',
            '::MyEntitiesHelp' => 'La lista de todas las entidades bajo control',
            '::KnownEntities' => 'Entidades conocidas',
            '::KnownEntitiesDesc' => 'Todas las entidades conocidas',
            '::KnownEntitiesHelp' => 'La lista de todas las entidades que conocemos, sean amigas o no',
            '::KnownEntity' => 'Conozco esta entidad',
            '::SpecialEntities' => 'Entidades especiales',
            '::SpecialEntitiesDesc' => 'Entidades específicas de <i>nebule</i>',
            '::SpecialEntitiesHelp' => 'La lista de entidades específicas de <i>nebule</i>',
            '::UnknownEntities' => 'Entidades desconocidas',
            '::UnknownEntitiesDesc' => 'Todas las entidades desconocidas',
            '::UnknownEntitiesHelp' => 'La lista de todas las demás entidades',
            '::KnownByEntities' => 'Conocido por entidades',
            '::KnownByEntitiesDesc' => 'Todas las entidades por las que se es conocido',
            '::KnownByEntitiesHelp' => 'La lista de todas las entidades que me conocen',
            '::SynchronizeEntity' => 'Sincronizar',
            '::SynchronizeKnownEntities' => 'Sincronizar todas las entidades',
            '::AttribNotDisplayable' => '¡Atributo no visualizable!',
            '::ListEntities' => 'Lista',
            '::ListEntitiesDesc' => 'Mostrar lista de entidades',
            '::CreateEntity' => 'Crear',
            '::naming' => 'Nombramiento',
            '::CreateEntityOther' => 'Otro',
            '::CreateEntityAlgorithm' => 'Algoritmo',
            '::CreateEntityTypeUndefined' => '(Indefinido)',
            '::CreateEntityTypeHuman' => 'Humano',
            '::CreateEntityTypeRobot' => 'Robot',
            '::CreateEntityAutonomy' => 'Entidad independiente',
            '::CreateTheEntity' => 'Crear la entidad',
            '::CreateEntityDesc' => 'Crear entidad',
            '::SearchEntity' => 'Buscar',
            '::SearchEntityDesc' => 'Buscar entidad',
            '::SearchEntityHelp' => 'Buscar una entidad conocida',
            '::SearchEntityNotAllowed' => '¡La búsqueda de entidades está desactivada!',
            '::SearchEntityLongTime' => 'La búsqueda solo por identificador puede tardar un poco...',
            '::Search:URL' => 'Dirección de localización',
            '::Search:AndOr' => 'y/o',
            '::Search:ID' => 'Identificador',
            '::Search:Submit' => 'Enviar',
            '::connectWith' => 'Conectar con esta entidad',
            '::unlock' => 'Desbloquear la entidad',
            '::switchTo' => 'Cambiar a esta entidad',
            '::lock' => 'Bloquear entidad',
            '::seeAs' => 'Ver como',
            '::modify' => 'Modificar',
            '::ModifyTheEntity' => 'Modificar la entidad',
            '::EntityModified' => 'Entidad modificada',
            '::EntityNotModified' => 'Entidad no modificada',
            '::PasswordModified' => 'Contraseña modificada',
            '::PasswordNotModified' => 'Contraseña no modificada',
            '::puppetmaster' => 'La entidad de referencia de <i>nebule</i>, el maestro de llaves',
            '::SecurityMaster' => 'La entidad maestra de seguridad',
            '::CodeMaster' => 'La entidad maestra de código',
            '::DirectoryMaster' => 'La entidad maestra de directorio',
            '::TimeMaster' => 'La entidad maestra de tiempo',
            '::SetSecurityMaster' => 'Promover como entidad maestra de seguridad',
            '::UnsetSecurityMaster' => 'Destituir como entidad maestra de seguridad',
            '::SetCodeMaster' => 'Promover como entidad maestra de código',
            '::UnsetCodeMaster' => 'Destituir como entidad maestra de código',
            '::SetDirectoryMaster' => 'Promover como entidad maestra de directorio',
            '::UnsetDirectoryMaster' => 'Destituir como entidad maestra de directorio',
            '::SetTimeMaster' => 'Promover como entidad maestra de tiempo',
            '::UnsetTimeMaster' => 'Destituir como entidad maestra de tiempo',
            '::From' => 'De',
            '::To' => 'Para',
            '::DisplayEntityMessages' => 'Lista de mensajes para %s',
            '::DisplayEntityPublicMessages' => 'Lista de mensajes públicos para %s',
            '::DisplayEntityPublicMessagesWarning' => 'Los mensajes protegidos u ocultos no están disponibles',
            '::AuthLockHelp' => 'Desconectando...',
            '::AuthUnlockHelp' => 'Conectando...',
            '::Protected' => 'Mensaje protegido',
            '::Obfuscated' => 'Mensaje ofuscado',
            '::Desc:AttribsTitle' => 'Atributos de la entidad',
            '::Desc:AttribsDesc' => 'Todos los atributos del objeto de la entidad',
            '::Desc:AttribsHelp' => 'Todos los atributos del objeto',
            '::Desc:Attrib' => 'Atributo',
            '::Desc:Value' => 'Valor',
            '::Desc:Signer' => 'Remitente',
            '::Display:NoEntity' => 'No hay entidad para mostrar',
            '::EntityKeys' => 'Claves de la entidad',
        ],
    ];
}
