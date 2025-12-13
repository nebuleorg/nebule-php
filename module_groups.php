<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Actions;
use Nebule\Library\ActionsGroups;
use Nebule\Library\ActionsLinks;
use Nebule\Library\Cache;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * This module can manage groups of everything.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleGroups extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'grp';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020251213';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2025';
    const MODULE_LOGO = '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('list', 'listall', 'setgroup', 'unsetgroup', 'addmarked', 'disp');
    const MODULE_REGISTERED_ICONS = array(
        '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256',    // 0 : Icône des groupes.
        '819babe3072d50f126a90c982722568a7ce2ddd2b294235f40679f9d220e8a0a.sha2.256',    // 1 : Créer un groupe.
        'a269514d2b940d8269993a6f0138f38bbb86e5ac387dcfe7b810bf871002edf3.sha2.256',    // 2 : Ajouter objets marqués.
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    private string $_hashGroup;
    private string $_hashGroupClosed;
    private Node $_hashGroupObject;
    private Node $_hashGroupClosedObject;


    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    protected function _initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_hashGroup = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE);
        $this->_hashGroupObject = $this->_cacheInstance->newNode($this->_hashGroup);
        $this->_hashGroupClosed = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
        $this->_hashGroupClosedObject = $this->_cacheInstance->newNode($this->_hashGroupClosed);
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'typeMenuGroup':
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[0]) {
                    // Voir les groupes de l'entité.
                    $hookArray[0]['name'] = '::display:MyGroups';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                } else {
                    // Voir les groupes des autres entités.
                    $hookArray[0]['name'] = '::display:Groups';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                // Si l'entité est déverrouillée.
                if ($this->_unlocked) {
                    if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[0]) {
                        // Créer un groupe.
                        $hookArray[1]['name'] = '::display:createGroup';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                        // Recherche si il y a des objets marqués.
                        $markList = $this->_applicationInstance->getMarkObjectList();

                        // Si la liste des marques n'est pas vide.
                        if (sizeof($markList) != 0) {
                            // Ajouter les objets marqués.
                            $hookArray[2]['name'] = '::display:AddMarkedObjects';
                            $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                            $hookArray[2]['desc'] = '';
                            $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                                . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                        }
                        unset($markList);
                    }
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[5]) {
                    // Refuser l'objet comme un groupe.
                    $hookArray[1]['name'] = '::display:unmakeGroup';
                    $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                }
                break;

            case 'selfMenuGroup':
                // Refuser l'objet comme un groupe.
                $hookArray[1]['name'] = '::display:unmakeGroup';
                $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                $hookArray[1]['desc'] = '';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                    . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                break;

            case '::sylabe:module:group:remove':
                // Si l'entité est déverrouillée.
                if ($this->_unlocked && $this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
                    // Retourner à la liste des groupes.
                    $hookArray[0]['name'] = '::display:MyGroups';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    // Supprimer le groupe.
                    $hookArray[1]['name'] = '::display:deleteGroup';
                    $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['css'] = 'oneAction-bg-warn';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . ActionsGroups::DELETE . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                }
                break;

            case '::sylabe:module:entities:MenuNameSelfMenu':
                $hookArray[0]['name'] = '::display:MyGroups';
                $hookArray[0]['icon'] = $this::MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;

            case 'selfMenuObject':
                // Affiche si l'objet courant est un groupe.
                if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('myself')) {
                    // Voir comme groupe.
                    $hookArray[0]['name'] = '::display:seeAsGroup';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    if ($this->_unlocked) {
                        // Refuser l'objet comme un groupe.
                        $hookArray[1]['name'] = '::display:unmakeGroup';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                            . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                } // Ou si c'est un groupe pour une autre entité.
                elseif ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
                    // Voir comme groupe.
                    $hookArray[0]['name'] = '::display:seeAsGroup';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    if ($this->_unlocked) {
                        // Faire de l'objet un groupe pour moi aussi.
                        $hookArray[1]['name'] = '::display:makeGroupMe';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . ActionsLinks::SIGN1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

                        // Refuser l'objet comme un groupe.
                        $hookArray[2]['name'] = '::display:refuseGroup';
                        $hookArray[2]['icon'] = Displays::DEFAULT_ICON_LX;
                        $hookArray[2]['desc'] = '';
                        $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                            . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                } // Ou si ce n'est pas un groupe.
                else {
                    if ($this->_unlocked) {
                        // Faire de l'objet un groupe.
                        $hookArray[0]['name'] = '::display:makeGroup';
                        $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                        $hookArray[0]['desc'] = '';
                        $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . ActionsLinks::SIGN1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                }
                break;

            case 'selfMenuEntity':
                $hookArray[0]['name'] = '::display:MyGroups';
                $hookArray[0]['icon'] = $this::MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;

            case 'typeMenuEntity':
                $hookArray[0]['name'] = '::display:Groups';
                $hookArray[0]['icon'] = $this::MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     *
     * @return void
     */
    public function displayModule(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_displayGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayAllGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayCreateGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayRemoveGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayAddMarkedObjects();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGroup();
                break;
            default:
                $this->_displayGroups();
                break;
        }
    }


    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    public function displayModuleInline(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineAllGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_display_InlineAddMarkedObjects();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_display_InlineGroup();
                break;
        }
    }


    /**
     * Affiche les groupes de l'entité en cours de visualisation.
     *
     * @return void
     */
    private function _displayGroups(): void
    {
        // Si un groupe a été créé.
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateGroup()) {
            $createGroupID = $this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateGroupID();
            $createGroupInstance = $this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateGroupInstance();
            $createGroupError = $this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateGroupOK();
            $createGroupErrorMessage = $this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateGroupErrorMessage();

            // Si la création à réussi.
            if (!$createGroupError
                && is_a($createGroupInstance, 'Group')
            ) {
                $instance = $this->_cacheInstance->newNode($createGroupID);

                $list = array();

                // Ajout du message de création.
                $list[0]['information'] = '::display:OKCreateGroup';
                $list[0]['param'] = array(
                    'enableDisplayIcon' => true,
                    'informationType' => 'ok',
                    'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                    'displayRatio' => 'short',
                );

                // Affiche l'objet référence.
                $list[1]['object'] = $instance;
                $list[1]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => true,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => false,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                    'displayRatio' => 'short',
                    'enableDisplayID' => false,
                    'flagProtection' => $instance->getMarkProtectedGroup(),
                    'enableDisplaySelfHook' => true,
                    'selfHookName' => 'selfMenuObject',
                    'enableDisplayTypeHook' => false,
                    'enableDisplayJS' => true,
                    'link2Object' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $createGroupID,
                );

                // Affiche la liste de l'objet et du message.
                echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');
                unset($list);
            } else {
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'informationType' => 'error',
                );
                echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::display:notOKCreateGroup', $param);
            }
        }

        $icon = $this->_cacheInstance->newNode($this::MODULE_LOGO);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::display:MyGroups');
        $instance->setIcon($icon);
        $instance->display();

        // Affiche le contenu.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('groups');
    }

    /**
     * Affiche les groupes de l'entité en cours de visualisation, en ligne.
     *
     * @return void
     */
    private function _display_InlineGroups(): void
    {
        // Liste tous les groupes.
        $listGroups = $this->_nebuleInstance->getListGroupsID($this->_entitiesInstance->getGhostEntityInstance(), '');

        // Prépare l'affichage.
        $list = array();
        $listOkGroups = array();
        $i = 0;
        foreach ($listGroups as $group) {
            $instance = $this->_cacheInstance->newNode($group, \Nebule\Library\Cache::TYPE_GROUP);

            // Extraction des entités signataires.
            $signers = $instance->getPropertySigners(References::REFERENCE_NEBULE_OBJET_GROUPE);

            if (!isset($listOkGroups[$group])) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'objectRefs' => $signers,
                );

                // Marque comme vu.
                $listOkGroups[$group] = true;
                $i++;
            }
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');

        unset($list, $links, $listOkGroups);
    }


    /**
     * Affiche les groupes de toutes les entités.
     *
     * @return void
     */
    private function _displayAllGroups(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_LOGO);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::display:otherGroups');
        $instance->setIcon($icon);
        $instance->display();

        // Affiche le contenu.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('allgroups');
    }

    /**
     * Affiche les groupes de toutes les entités, en ligne.
     *
     * @todo filtrer sur tout sauf l'entité en cours.
     *
     * @return void
     */
    private function _display_InlineAllGroups(): void
    {
        // Liste tous les groupes.
        $listGroups = $this->_nebuleInstance->getListGroupsID('', '');

        // Prépare l'affichage.
        $list = array();
        $listOkGroups = array();
        $i = 0;
        foreach ($listGroups as $group) {
            $instance = $this->_cacheInstance->newNode($group, \Nebule\Library\Cache::TYPE_GROUP);

            // Extraction des entités signataires.
            $signers = $instance->getPropertySigners(References::REFERENCE_NEBULE_OBJET_GROUPE);

            if (!isset($listOkGroups[$group])
                && !isset($signers[$this->_entitiesInstance->getGhostEntityEID()])
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'objectRefs' => $signers,
                );

                // Marque comme vu.
                $listOkGroups[$group] = true;
                $i++;
            }
            unset($signers);
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');

        unset($list, $links, $listOkGroups);


        /*		// Liste tous les groupes.
		$links = $this->_nebuleInstance->getListGroupsLinks('', '');
		if ( sizeof($links) != 0 )
		{
			$list = array();
			$i=0;
			foreach ( $links as $i => $link )
			{
				if ( $link->getParsed()['bs/rs1/eid'] != $this->_nebuleInstance->getCurrentEntity() )
				{
					$instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1']);   erreur de fonction !!!
					$instanceEntity = $this->_nebuleInstance->newNode($link->getParsed()['bs/rs1/eid'], \Nebule\Library\Cache::TYPE_ENTITY);
					$closed = '::GroupeOuvert';
					if ( $instance->getMarkClosed() )
						$closed = '::GroupeFerme';
					$list[$i]['object'] = $instance;
					$list[$i]['entity'] = $instanceEntity;
					$list[$i]['icon'] = '';
					$list[$i]['htlink'] = '';
					$list[$i]['desc'] = $this->_translateInstance->getTranslate($closed);
					$list[$i]['actions'] = array();
					if ( $this->_unlocked )
					{
						// Supprimer le groupe.
						$list[$i]['actions'][0]['name'] = '::display:unmakeGroup';
						$list[$i]['actions'][0]['icon'] = Display::DEFAULT_ICON_LX;
						$list[$i]['actions'][0]['htlink'] = '?'.Displays::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this::MODULE_COMMAND_NAME
							.'&'.Displays::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this::MODULE_REGISTERED_VIEWS[3]
							.'&'.References::COMMAND_SELECT_OBJECT.'='.$link->getParsed()['bl/rl/nid1'];
						// Utiliser comme groupe ouvert.
						$list[$i]['actions'][1]['name'] = '::display:useAsGroupOpened';
						$list[$i]['actions'][1]['icon'] = Display::DEFAULT_ICON_LL;
						$list[$i]['actions'][1]['htlink'] = '?'.Displays::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this::MODULE_COMMAND_NAME
							.'&'.Displays::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this::MODULE_REGISTERED_VIEWS[1]
							.'&'.Action::DEFAULT_COMMAND_ACTION_SIGN_LINK1.'=f_'.$this->_hashGroup.'_'.$link->getParsed()['bl/rl/nid1'].'_0'
							.$this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
						// Utiliser comme groupe fermé.
						$list[$i]['actions'][2]['name'] = '::display:useAsGroupClosed';
						$list[$i]['actions'][2]['icon'] = Display::DEFAULT_ICON_LL;
						$list[$i]['actions'][2]['htlink'] = '?'.Displays::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this::MODULE_COMMAND_NAME
							.'&'.Displays::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this::MODULE_REGISTERED_VIEWS[1]
							.'&'.Action::DEFAULT_COMMAND_ACTION_SIGN_LINK1.'=f_'.$this->_hashGroupClosed.'_'.$link->getParsed()['bl/rl/nid1'].'_0'
							.$this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
					}
					$i++;
				}
			}
			unset($link, $type, $instance, $instanceEntity, $closed);
			if ( sizeof($list) != 0 )
				$this->_applicationInstance->getDisplayInstance()->displayItemList($list);
			else
				// Pas de groupe.
				$this->_applicationInstance->getDisplayInstance()->displayMessageOk('::display:noGroup');
			unset($list);
		}
		else
		{
			// Pas de groupe.
			$this->_applicationInstance->getDisplayInstance()->displayMessageOk('::display:noGroup');
		}
		unset($links, $okobj, $count);*/
    }

    /**
     *
     *
     * @return void
     */
    private function _displayAllGroupsOld(): void
    {
        // Affiche le titre.
        $this->_applicationInstance->getDisplayInstance()->displayObjectDivHeaderH1($this->_applicationInstance->getCurrentObjectInstance(), 'test');

        // Affiche si l'objet courant est un groupe.
        if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('myself')) {
            ?>
            <div class="text">
                <p>
                    <?php
                    $this->_applicationInstance->getDisplayInstance()->displayInlineObjectColorName($this->_applicationInstance->getCurrentObjectInstance());
                    echo ' ';
                    echo $this->_translateInstance->getTranslate('::display:isGroup');
                    ?>
                </p>
            </div>
            <?php
        } // Ou si c'est un groupe pour une autre entité.
        elseif ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
            ?>
            <div class="text">
                <p>
                    <?php
                    $this->_applicationInstance->getDisplayInstance()->displayInlineObjectColorName($this->_applicationInstance->getCurrentObjectInstance());
                    echo ' ';
                    echo $this->_translateInstance->getTranslate('::display:isGroupToOther');
                    echo ' ';
                    $this->_applicationInstance->getDisplayInstance()->displayInlineObjectColorIconName($this->_applicationInstance->getCurrentObjectInstance()); // Modifié !!!
                    echo '.';
                    ?>
                </p>
            </div>
            <?php
        } // Ou si ce n'est pas un groupe.
        else {
            ?>
            <div class="text">
                <p>
                    <?php
                    $this->_applicationInstance->getDisplayInstance()->displayInlineObjectColorName($this->_applicationInstance->getCurrentObjectInstance());
                    echo ' ';
                    echo $this->_translateInstance->getTranslate('::display:isNotGroup');
                    ?>
                </p>
            </div>
            <?php
        }
    }


    /**
     * Création d'un groupe.
     *
     * @return void
     */
    private function _displayCreateGroup(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[1]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::display:createGroup');
        $instance->setIcon($icon);
        $instance->display();

        // Si autorisé à créer un groupe.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
            && $this->_unlocked
        ) {
            ?>

            <div class="text">
                <p>
                <form method="post"
                      action="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                          . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                          . '&' . ActionsGroups::CREATE
                          . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(); ?>">
                    <div class="floatRight textAlignRight">
                        <input type="checkbox"
                               name="<?php echo ActionsGroups::CREATE_CLOSED; ?>"
                               value="y" checked>
                        <?php echo $this->_translateInstance->getTranslate('::GroupeFerme'); ?>
                    </div>
                    <?php echo $this->_translateInstance->getTranslate('::display:nom'); ?>
                    <input type="text"
                           name="<?php echo ActionsGroups::CREATE_NAME; ?>"
                           size="20" value="" class="klictyModuleEntityInput"><br/>
                    <input type="submit"
                           value="<?php echo $this->_translateInstance->getTranslate('::display:createTheGroup'); ?>"
                           class="klictyModuleEntityInput">
                </form>
                </p>
            </div>
            <?php
        } else {
            $this->_applicationInstance->getDisplayInstance()->displayMessageError('::::err_NotPermit');
        }
    }

    private function _displayRemoveGroup(): void
    {
        // Affichage de l'entête.
        $this->_applicationInstance->getDisplayInstance()->displayObjectDivHeaderH1($this->_applicationInstance->getCurrentObjectInstance());

        if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
            // Affichage les actions possibles.
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::sylabe:module:group:remove');
        } else {
            // Ce n'est pas un groupe.
            $this->_applicationInstance->getDisplayInstance()->displayMessageError('::display:thisIsNotGroup');
        }
    }


    /**
     * Permet d'ajouter des objets marqués à un groupe.
     *
     * @return void
     */
    private function _displayAddMarkedObjects(): void
    {
    }

    /**
     * Affichage en ligne de l'ajout des objets à un groupe.
     *
     * @return void
     */
    private function _display_InlineAddMarkedObjects(): void
    {
    }


    /**
     * Affiche le groupe.
     *
     * @return void
     */
    private function _displayGroup(): void
    {
        $instance = $this->_nebuleInstance->getCurrentGroupInstance();

        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => true,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );
        $param['objectRefs'] = $instance->getPropertySigners(References::REFERENCE_NEBULE_OBJET_GROUPE);
        echo $this->_displayInstance->getDisplayObject_DEPRECATED($instance, $param);
        unset($instance);

        // Affichage des membres du groupe.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('grpdisp');
    }

    /**
     * Affiche le groupe, en ligne.
     *
     * @return void
     */
    private function _display_InlineGroup(): void
    {
        // Détermine si c'est un groupe fermé.
        if ($this->_nebuleInstance->getCurrentGroupInstance()->getMarkClosedGroup()) {
            // Liste tous les objets du groupe fermé.
            $groupListID = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersID('self', null);

            //Prépare l'affichage.
            if (sizeof($groupListID) != 0) {
                $list = array();
                $listOkItems = array($this->_hashGroup => true, $this->_hashGroupClosed => true);
                $i = 0;
                foreach ($groupListID as $item) {
                    if (!isset($listOkItems[$item])) {
                        $instance = $this->_applicationInstance->getTypedInstanceFromNID($item);

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = '';
                        $list[$i]['icon'] = '';
                        $list[$i]['htlink'] = '';
                        $list[$i]['desc'] = '';
                        $list[$i]['actions'] = array();

                        // Supprimer le groupe.
                        if ($this->_unlocked
                            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                        ) {
                            $list[$i]['actions'][0]['name'] = '::display:removeFromGroup';
                            $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                                . '&' . $this->_nebuleInstance->getCurrentGroupOID()
                                . '&' . ActionsGroups::REMOVE_MEMBER . '=' . $item
                                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                        }

                        // Marque comme vu.
                        $listOkItems[$item] = true;
                        $i++;
                    }
                }
                unset($groupListID, $listOkItems, $item, $instance);
                // Affichage
                if (sizeof($list) != 0)
                    $this->_applicationInstance->getDisplayInstance()->displayItemList($list);

                // Liste tous les objets du groupe ouvert.
                $groupListLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersLinks('self'); // @todo à vérifier self.

                //Prépare l'affichage.
                if (sizeof($groupListLinks) != 0) {
                    $hashGroupPriv = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
                    $list = array();
                    $listOkItems = array();
                    $i = 0;
                    foreach ($groupListLinks as $item) {
                        // Vérifie si le couple membre/signataire n'est pas déjà pris en compte.
                        // Vérifie si le signataire n'est pas l'entité en cours.
                        if (!isset($listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']])
                            && $item->getParsed()['bs/rs1/eid'] != $this->_entitiesInstance->getGhostEntityEID()
                        ) {
                            $instance = $this->_applicationInstance->getTypedInstanceFromNID($item->getParsed()['bl/rl/nid1']);
                            $instanceSigner = $this->_cacheInstance->newNode($item->getParsed()['bs/rs1/eid'], \Nebule\Library\Cache::TYPE_ENTITY);
                            $closed = '::GroupeOuvert';
                            if ($item->getParsed()['bl/rl/nid3'] == $hashGroupPriv)
                                $closed = '::GroupeFerme';

                            $list[$i]['object'] = $instance;
                            $list[$i]['entity'] = $instanceSigner;
                            $list[$i]['icon'] = '';
                            $list[$i]['htlink'] = '';
                            $list[$i]['desc'] = $this->_translateInstance->getTranslate($closed);
                            $list[$i]['actions'] = array();

                            // Supprimer le groupe.
                            if ($this->_unlocked
                                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                                && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                            ) {
                                $list[$i]['actions'][0]['name'] = '::display:removeFromGroup';
                                $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                                $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroupOID()
                                    . '&' . ActionsGroups::REMOVE_MEMBER . '=' . $item->getParsed()['bl/rl/nid1']
                                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                            }

                            // Marque comme vu.
                            $listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']] = true;
                            $i++;
                        }
                    }
                    unset($groupListLinks, $listOkItems, $item, $instance, $hashGroupPriv, $closed);
                    // Affichage
                    if (sizeof($list) != 0) {
                        echo "<div class=\"sequence\"></div>\n";
                        $iconNID = $this->_cacheInstance->newNode($this::MODULE_LOGO);
                        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
                        $instance->setTitle('::display:seenFromOthers');
                        $instance->setIcon($iconNID);
                        $instance->display();
                        $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
                    }
                }
                unset($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::display:noGroupMember');
            }
        } // Sinon c'est un groupe ouvert.
        else {
            // Liste tous les groupes.
            $groupListLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersLinks('self'); // @todo à vérifier self.

            //Prépare l'affichage.
            if (sizeof($groupListLinks) != 0) {
                $hashGroupPriv = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
                $list = array();
                $listOkItems = array();
                $i = 0;
                foreach ($groupListLinks as $item) {
                    // Vérifie si le couple membre/signataire n'est pas déjà pris en compte.
                    if (!isset($listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']])) {
                        $instance = $this->_applicationInstance->getTypedInstanceFromNID($item->getParsed()['bl/rl/nid1']);
                        $instanceSigner = $this->_cacheInstance->newNode($item->getParsed()['bs/rs1/eid'], \Nebule\Library\Cache::TYPE_ENTITY);
                        $closed = '::GroupeOuvert';
                        if ($item->getParsed()['bl/rl/nid3'] == $hashGroupPriv)
                            $closed = '::GroupeFerme';

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = $instanceSigner;
                        $list[$i]['icon'] = '';
                        $list[$i]['htlink'] = '';
                        $list[$i]['desc'] = $this->_translateInstance->getTranslate($closed);
                        $list[$i]['actions'] = array();

                        // Supprimer le groupe.
                        if ($this->_unlocked
                            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                        ) {
                            $list[$i]['actions'][0]['name'] = '::display:removeFromGroup';
                            $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                                . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroupOID()
                                . '&' . ActionsGroups::REMOVE_MEMBER . '=' . $item->getParsed()['bl/rl/nid1']
                                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                        }

                        // Marque comme vu.
                        $listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']] = true;
                        $i++;
                    }
                }
                unset($groupListLinks, $listOkItems, $item, $instance, $hashGroupPriv, $closed);
                // Affichage
                if (sizeof($list) != 0)
                    $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
                unset($list);
            } else {
                // Pas d'entité.
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::display:noGroupMember');
            }
        }
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des groupes',
            '::MenuName' => 'Groupes',
            '::ModuleDescription' => 'Module de gestion des groupes.',
            '::ModuleHelp' => "Ce module permet de voir et de gérer les groupes.",
            '::AppTitle1' => 'Groupes',
            '::AppDesc1' => 'Gestion des groupes.',
            '::display:Groups' => 'Les groupes',
            '::display:MyGroups' => 'Mes groupes',
            '::display:seeAsGroup' => 'Voir comme groupe',
            '::display:seenFromOthers' => 'Vu depuis les autres entités',
            '::display:otherGroups' => 'Les groupes des autres entités',
            '::display:createGroup' => 'Créer un groupe',
            '::display:AddMarkedObjects' => 'Ajouter les objets marqués',
            '::display:deleteGroup' => 'Supprimer le groupe',
            '::display:createTheGroup' => 'Créer le groupe',
            '::display:nom' => 'Nom',
            '::display:OKCreateGroup' => 'Le groupe a été créé.',
            '::display:notOKCreateGroup' => "Le groupe n'a pas été créé ! %s",
            '::display:noGroup' => 'Pas de groupe.',
            '::display:noGroupMember' => 'Pas de membre.',
            '::display:makeGroup' => 'Faire de cet objet un groupe',
            '::display:makeGroupMe' => 'Faire de cet objet un groupe pour moi aussi',
            '::display:unmakeGroup' => 'Ne plus faire de cet objet un groupe',
            '::display:useAsGroupOpened' => 'Utiliser comme groupe ouvert',
            '::display:useAsGroupClosed' => 'Utiliser comme groupe fermé',
            '::display:refuseGroup' => 'Refuser cet objet comme un groupe',
            '::display:removeFromGroup' => 'Retirer du groupe',
            '::display:isGroup' => 'est un groupe.',
            '::display:isGroupToOther' => 'est un groupe de',
            '::display:isNotGroup' => "n'est pas un groupe.",
            '::display:thisIsGroup' => "C'est un groupe.",
            '::display:thisIsNotGroup' => "Ce n'est pas un groupe.",
        ],
        'en-en' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module.',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups.',
            '::display:Groups' => 'The groups',
            '::display:MyGroups' => 'My groups',
            '::display:seeAsGroup' => 'See as group',
            '::display:seenFromOthers' => 'Seen from others entities',
            '::display:otherGroups' => 'Groups of other entities',
            '::display:createGroup' => 'Create a group',
            '::display:AddMarkedObjects' => 'Add marked objects',
            '::display:deleteGroup' => 'Delete group',
            '::display:createTheGroup' => 'Create the group',
            '::display:nom' => 'Name',
            '::display:OKCreateGroup' => 'The group have been created.',
            '::display:notOKCreateGroup' => 'The group have not been created! %s',
            '::display:noGroup' => 'No group.',
            '::display:noGroupMember' => 'No member.',
            '::display:makeGroup' => 'Make this object a group',
            '::display:makeGroupMe' => 'Make this object a group for me too',
            '::display:unmakeGroup' => 'Unmake this object a group',
            '::display:useAsGroupOpened' => 'Use as group opened',
            '::display:useAsGroupClosed' => 'Use as group closed',
            '::display:refuseGroup' => 'Refuse this object as group',
            '::display:removeFromGroup' => 'Remove from group',
            '::display:isGroup' => 'is a group.',
            '::display:isGroupToOther' => 'is a group of',
            '::display:isNotGroup' => 'is not a group.',
            '::display:thisIsGroup' => 'This is a group.',
            '::display:thisIsNotGroup' => 'This is not a group.',
        ],
        'es-co' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module.',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups.',
            '::display:Groups' => 'The groups',
            '::display:MyGroups' => 'My groups',
            '::display:seeAsGroup' => 'See as group',
            '::display:seenFromOthers' => 'Seen from others entities',
            '::display:otherGroups' => 'Groups of other entities',
            '::display:createGroup' => 'Create a group',
            '::display:AddMarkedObjects' => 'Add marked objects',
            '::display:deleteGroup' => 'Delete group',
            '::display:createTheGroup' => 'Create the group',
            '::display:nom' => 'Name',
            '::display:OKCreateGroup' => 'The group have been created.',
            '::display:notOKCreateGroup' => 'The group have not been created! %s',
            '::display:noGroup' => 'No group.',
            '::display:noGroupMember' => 'No member.',
            '::display:makeGroup' => 'Make this object a group',
            '::display:makeGroupMe' => 'Make this object a group for me too',
            '::display:unmakeGroup' => 'Unmake this object a group',
            '::display:useAsGroupOpened' => 'Use as group opened',
            '::display:useAsGroupClosed' => 'Use as group closed',
            '::display:refuseGroup' => 'Refuse this object as group',
            '::display:removeFromGroup' => 'Remove from group',
            '::display:isGroup' => 'is a group.',
            '::display:isGroupToOther' => 'is a group of',
            '::display:isNotGroup' => 'is not a group.',
            '::display:thisIsGroup' => 'This is a group.',
            '::display:thisIsNotGroup' => 'This is not a group.',
        ],
    ];
}
