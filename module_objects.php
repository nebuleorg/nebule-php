<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Actions;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * Ce module permet gérer les objets.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleObjects extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::sylabe:module:objects:ModuleName';
    const MODULE_MENU_NAME = '::sylabe:module:objects:MenuName';
    const MODULE_COMMAND_NAME = 'obj';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = '::sylabe:module:objects:ModuleDescription';
    const MODULE_VERSION = '020250307';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2025';
    const MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    const MODULE_HELP = '::sylabe:module:objects:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('disp', 'desc', 'nav', 'prot', 'sprot');
    const MODULE_REGISTERED_ICONS = array(
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 0 : Objet.
        '2e836dd0ca088d84cbc472093a14445e5c81ee0998293b46a479fedc41adf10d.sha2.256',    // 1 : Loupe.
        '06cac4acb887cff2c7ba6653f865d800276a4e9d493a3be4e1b05d107f5ecbaf.sha2.256',    // 2 : Fork.
        '6d1d397afbc0d2f6866acd1a30ac88abce6a6c4c2d495179504c2dcb09d707c1.sha2.256',    // 3 : Protection d'un objet.
        '1c6db1c9b3b52a9b68d19c936d08697b42595bec2f0adf16e8d9223df3a4e7c5.sha2.256',    // 4 : Clé.
    );
    const MODULE_APP_TITLE_LIST = array();
    const MODULE_APP_ICON_LIST = array();
    const MODULE_APP_DESC_LIST = array();
    const MODULE_APP_VIEW_LIST = array();

    const DEFAULT_ATTRIBS_DISPLAY_NUMBER = 10;


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuObject':
                //$instance = $this->_applicationInstance->getCurrentObjectInstance();
                $instance = $this->_cacheInstance->newNode($object);
                $id = $instance->getID();

                // Recherche si l'objet est protégé.
                $protected = $instance->getMarkProtected();
                if ($protected) {
                    $id = $instance->getUnprotectedID();
                    $instance = $this->_cacheInstance->newNode($id);
                }

                // Recherche une mise à jour.
                $update = $instance->getUpdateNID(false, false);

                // Recherche si l'objet est marqué.
                $marked = $this->_applicationInstance->getMarkObject($id); // FIXME ne devrait pas être dans l'app sylabe mais dans Applications !
                $markList = $this->_applicationInstance->getMarkObjectList();
                $mode = $this->_displayInstance->getCurrentDisplayMode();
                $view = $this->_displayInstance->getCurrentDisplayView();

                if ($mode != $this::MODULE_COMMAND_NAME
                    || ($mode == $this::MODULE_COMMAND_NAME
                        && $view != $this::MODULE_REGISTERED_VIEWS[0]
                    )
                ) {
                    // Affichage de l'objet.
                    $hookArray[0]['name'] = '::sylabe:module:objects:DisplayObject';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id;

                    // Si l'objet a une mise à jour.
                    if ($update != $id) {
                        // Affichage de l'objet à jour.
                        $hookArray[1]['name'] = '::sylabe:module:objects:DisplayObjectUpdated';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LU;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $update;
                    }
                }

                // Description de l'objet.
                if ($mode == $this::MODULE_COMMAND_NAME
                    && $view != $this::MODULE_REGISTERED_VIEWS[1]
                ) {
                    $hookArray[2]['name'] = '::sylabe:module:objects:ObjectDescription';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id;
                }

                // Naviguer autour de l'objet.
                if ($mode == $this::MODULE_COMMAND_NAME
                    && $view != $this::MODULE_REGISTERED_VIEWS[2]
                ) {
                    $hookArray[3]['name'] = '::sylabe:module:objects:ObjectRelations';
                    $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id;
                }

                // Si le contenu de l'objet est présent.
                if ($instance->checkPresent()) {
                    // Télécharger l'objet.
                    $hookArray[4]['name'] = '::sylabe:module:objects:ObjectDownload';
                    $hookArray[4]['icon'] = Displays::DEFAULT_ICON_IDOWNLOAD;
                    $hookArray[4]['desc'] = '::sylabe:module:objects:Action:Download';
                    $hookArray[4]['link'] = '?o=' . $id;

                    // Si l'entité est déverrouillée.
                    if ($this->_unlocked
                        && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                        && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                        && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    ) {
                        // Supprimer l'objet.
                        $hookArray[5]['name'] = '::sylabe:module:objects:ObjectDelete';
                        $hookArray[5]['icon'] = Displays::DEFAULT_ICON_LD;
                        $hookArray[5]['desc'] = '';
                        $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_DELETE_OBJECT . '=' . $id
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                        // Protéger l'objet.
                        if ($mode == $this::MODULE_COMMAND_NAME
                            && $view != $this::MODULE_REGISTERED_VIEWS[3]
                        ) {
                            $hookArray[6]['name'] = '::sylabe:module:objects:Protection';
                            $hookArray[6]['icon'] = $this::MODULE_REGISTERED_ICONS[3];
                            $hookArray[6]['desc'] = '';
                            $hookArray[6]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id;
                        }

                        // Partager la protection
                        if ($mode == $this::MODULE_COMMAND_NAME
                            && $view != $this::MODULE_REGISTERED_VIEWS[4]
                            && $protected
                        ) {
                            $hookArray[7]['name'] = '::sylabe:module:objects:ShareProtection';
                            $hookArray[7]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                            $hookArray[7]['desc'] = '';
                            $hookArray[7]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id;
                        }
                    }
                }

                // Si l'objet n'est pas marqué.
                if (!$marked) {
                    // Ajouter la marque de l'objet.
                    $hookArray[8]['name'] = '::MarkAdd';
                    $hookArray[8]['icon'] = Display::DEFAULT_ICON_MARK;
                    $hookArray[8]['desc'] = '';
                    $hookArray[8]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_MARK_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                } else {
                    // Retirer la marque de l'objet.
                    $hookArray[8]['name'] = '::MarkRemove';
                    $hookArray[8]['icon'] = Display::DEFAULT_ICON_UNMARK;
                    $hookArray[8]['desc'] = '';
                    $hookArray[8]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_UNMARK_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }

                // Si la liste des marques n'est pas vide.
                if (sizeof($markList) != 0) {
                    // Retirer la marque de tous les objets.
                    $hookArray[9]['name'] = '::MarkRemoveAll';
                    $hookArray[9]['icon'] = Display::DEFAULT_ICON_UNMARKALL;
                    $hookArray[9]['desc'] = '';
                    $hookArray[9]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_UNMARK_ALL_OBJECT
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                unset($instance, $id, $protected, $update, $markList, $marked);
                break;

            case 'menu':
                // Recherche si il y a des objets marqués.
                $markList = $this->_applicationInstance->getMarkObjectList();

                // Si la liste des marques n'est pas vide.
                if (sizeof($markList) != 0) {
                    // Retirer la marque de tous les objets.
                    $hookArray[0]['name'] = '::MarkRemoveAll';
                    $hookArray[0]['icon'] = Display::DEFAULT_ICON_UNMARKALL;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_UNMARK_ALL_OBJECT
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                unset($markList);
                break;

            case '::sylabe:module:links:MenuNameSelfMenu':
                $hookArray[0]['name'] = '::sylabe:module:objects:DisplayObject';
                $hookArray[0]['icon'] = Display::DEFAULT_ICON_LO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object;
                break;

            case '::sylabe:module:upload:FileUploaded':
                // Si il y a eu le téléchargement d'un fichier.
                if ($this->_applicationInstance->getActionInstance()->getUploadObject()) {
                    // Si pas d'erreur.
                    if (!$this->_applicationInstance->getActionInstance()->getUploadObjectError()) {
                        $hookArray[0]['name'] = '::sylabe:module:objects:DisplayNewObject';
                        $hookArray[0]['icon'] = Displays::DEFAULT_ICON_LO;
                        $hookArray[0]['desc'] = '';
                        $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getActionInstance()->getUploadObjectID();
                    }
                }
                break;

            case 'typeMenuEntity':
            case 'typeMenuCurrency':
            case 'typeMenuTokenPool':
            case 'typeMenuToken':
                // Voir l'objet de l'entité.
                $hookArray[0]['name'] = '::sylabe:module:objects:DisplayAsObject';
                $hookArray[0]['icon'] = Displays::DEFAULT_ICON_LO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object;

                $instance = $this->_entitiesInstance->getCurrentEntityInstance();
                $id = $instance->getID();

                // Recherche si l'objet est protégé.
                if ($instance->getMarkProtected()) {
                    $id = $instance->getUnprotectedID();
                    $instance = $this->_cacheInstance->newNode($id);
                }

                // Recherche si l'objet est marqué.
                $marked = $this->_applicationInstance->getMarkObject($id);

                // Si l'objet n'est pas marqué.
                if (!$marked) {
                    // Ajouter la marque de l'objet.
                    $hookArray[1]['name'] = '::MarkAdd';
                    $hookArray[1]['icon'] = Display::DEFAULT_ICON_MARK;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_ENTITY . '=' . $id
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_MARK_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                } else {
                    // Retirer la marque de l'objet.
                    $hookArray[1]['name'] = '::MarkRemove';
                    $hookArray[1]['icon'] = Display::DEFAULT_ICON_UNMARK;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_ENTITY . '=' . $id
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_UNMARK_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                unset($instance, $id, $marked);
                break;

            case '::sylabe:module:entities:DisplayEntity':
            case '::sylabe:module:filesystem:adminInpoint' :
            case '::sylabe:module:filesystem:adminFolder' :
            case '::sylabe:module:filesystem:adminObject' :
            case 'selfMenuConversation':
                // Voir comme objet simplement.
                $hookArray[0]['name'] = '::sylabe:module:objects:DisplayAsObject';
                $hookArray[0]['icon'] = Displays::DEFAULT_ICON_LO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object;
                break;

            case '::sylabe:module:objet:ProtectionAdd' :
                // Actions sur l'objet lors de l'ajout de la protection.
                break;

            case '::sylabe:module:object:protectShared' :
                // Actions pour retirer le partage de protection de l'objet à l'entité.
                break;

            case '::sylabe:module:object:protectShareTo' :
                // Actions pour partager la protection de l'objet à l'entité.
                break;

            case '::sylabe:module:object:protectShareToGroup' :
                // Actions pour partager la protection de l'objet aux entités du groupe.
                break;

            case '::sylabe:module:objet:ProtectionButtons' :
                // Si l'entité est déverrouillée.
                if ($this->_unlocked
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    && $this->_applicationInstance->getCurrentObjectInstance()->getMarkProtected()
                ) {
                    $hookArray[0]['name'] = '::sylabe:module:objects:ShareProtection';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getCurrentObjectID();
                }
                break;

            case '::sylabe:module:objet:ProtectionShareButtons' :
                // Protéger l'objet.
                $hookArray[0]['name'] = '::sylabe:module:objects:Protection';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[3];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getCurrentObjectID();
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
            /*case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_displayObjectContent();
                break;*/
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayObjectDescription();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayObjectRelations();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayObjectProtection();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayObjectProtectionShare();
                break;
            default:
                $this->_displayObjectContent();
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
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineObjectDescription();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_display_InlineObjectRelations();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_display_InlineObjectProtection();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_display_InlineObjectProtectionShare();
                break;
        }
    }


    /**
     * Affichage de surcharges CSS.
     *
     * @return void
     */
    public function headerStyle(): void
    {
        ?>

        /* Module objets */
        .sylabeModuleObjectsDescList1 { padding:5px; background:rgba(255,255,255,0.5); background-origin:border-box; color:#000000; clear:both; }
        .sylabeModuleObjectsDescList2 { padding:5px; background:rgba(230,230,230,0.5); background-origin:border-box; color:#000000; clear:both; }
        .sylabeModuleObjectsDescError { padding:5px; background:rgba(0,0,0,0.3); background-origin:border-box; clear:both; }
        .sylabeModuleObjectsDescError .sylabeModuleObjectsDescAttrib { font-style:italic; color:#202020; }
        .sylabeModuleObjectsDescIcon { float:left; margin-right:5px; }
        .sylabeModuleObjectsDescContent { min-width:300px; }
        .sylabeModuleObjectsDescDate, .sylabeModuleObjectsDescSigner { float:right; margin-left:10px; }
        .sylabeModuleObjectsDescSigner a { color:#000000; }
        .sylabeModuleObjectsDescValue { font-weight:bold; }
        .sylabeModuleObjectsDescEmotion { font-weight:bold; }
        .sylabeModuleObjectsDescEmotion img { height:16px; width:16px; }
        <?php
    }


    /**
     * Affichage de la vue disp.
     */
    private function _displayObjectContent(): void
    {
        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'flagProtection' => $this->_applicationInstance->getCurrentObjectInstance()->getMarkProtected(),
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => true,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );
        echo $this->_displayInstance->getDisplayObject_DEPRECATED($this->_applicationInstance->getCurrentObjectInstance(), $param);
    }


    /**
     * Affichage de la vue desc.
     */
    private function _displayObjectDescription(): void
    {
        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'flagProtection' => $this->_applicationInstance->getCurrentObjectInstance()->getMarkProtected(),
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
        echo $this->_displayInstance->getDisplayObject_DEPRECATED($this->_applicationInstance->getCurrentObjectInstance(), $param);

        // Affiche les propriétés.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('objprop');
    }

    /**
     * Affichage de la vue desc en ligne.
     */
    private function _display_InlineObjectDescription(): void
    {
        $display = $this->_applicationInstance->getDisplayInstance();

        // Préparation de la gestion de l'affichage par parties.
        $startLinkSigne = $this->_nebuleInstance->getDisplayNextObject();
        $displayCount = 0;
        $okDisplay = false;
        if ($startLinkSigne == '')
            $okDisplay = true;
        $displayNext = false;
        $nextLinkSigne = '';

        // Liste des attributs, càd des liens de type l.
        $links = $this->_applicationInstance->getCurrentObjectInstance()->getLinksOnFields(
            '',
            '',
            '',
            $this->_applicationInstance->getCurrentObjectID(),
            '',
            '');

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
                if ($link->getRaw() == $startLinkSigne)
                    $okDisplay = true;

                // Enregistre le message suivant à afficher si le compteur d'affichage est dépassé.
                if ($displayNext && $nextLinkSigne == '')
                    $nextLinkSigne = $link->getRaw();

                // Si l'affichage est permis.
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
                            // Vérifie le nom.
                            if ($attribName == null) {
                                $attribName = '';
                            }
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
                    }

                    // Si action de type f, vérifie si l'attribut est dans la liste des émotions à afficher.
                    if ($action == 'f'
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
                        $value = $valueInstance->readOneLineAsText();
                        unset($valueInstance);
                        // Vérifie la valeur.
                        if ($value == null) {
                            $value = $this->_applicationInstance->getTranslateInstance()->getTranslate('::noContent');
                        }
                    }

                    if ($showAttrib) {
                        // Affiche l'attribut.
                        ?>

                        <div class="sylabeModuleObjectsDescList<?php echo $bg; ?>">
                            <?php
                            if ($this->_applicationInstance->getApplicationModulesInstance()->getIsModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleObjectsDescIcon">
                                    <?php $display->displayHypertextLink($display->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')::MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . ModuleLinks::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleObjectsDescDate"><?php $display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleObjectsDescSigner"><?php $display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleObjectsDescContent">
                                <span class="sylabeModuleObjectsDescAttrib"><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate($attribName); ?></span>
                                =
                                <span class="sylabeModuleObjectsDescValue"><?php echo $value; ?></span>
                            </div>
                        </div>
                        <?php
                    } elseif ($showEmotion) {
                        // Affiche l'émotion.
                        ?>

                        <div class="sylabeModuleObjectsDescList<?php echo $bg; ?>">
                            <?php
                            if ($this->_applicationInstance->getApplicationModulesInstance()->getIsModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleObjectsDescIcon">
                                    <?php $display->displayHypertextLink($display->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')::MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . ModuleLinks::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleObjectsDescDate"><?php $display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleObjectsDescSigner"><?php $display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleObjectsDescContent">
		<span class="sylabeModuleObjectsDescEmotion">
			<?php $display->displayReferenceImage($emotionsIcons[$emotion], $emotionsList[$hashValue]); ?>
            <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate($emotionsList[$hashValue]); ?>
		</span>
                            </div>
                        </div>
                        <?php
                    } elseif ($action == 'l') {
                        // Affiche une erreur si la propriété n'est pas lisible.
                        ?>

                        <div class="sylabeModuleObjectsDescError">
                            <?php
                            if ($this->_applicationInstance->getApplicationModulesInstance()->getIsModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleObjectsDescIcon">
                                    <?php $display->displayHypertextLink($display->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')::MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . ModuleLinks::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>
                                    &nbsp;
                                    <?php $display->displayInlineIconFace('DEFAULT_ICON_IWARN'); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleObjectsDescDate"><?php $display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleObjectsDescSigner"><?php $display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleObjectsDescContent">
                                <span class="sylabeModuleObjectsDescAttrib"><?php echo $this->_translateInstance->getTranslate('::sylabe:module:objects:AttribNotDisplayable'); ?></span>
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
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $display->getCurrentDisplayView()
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $this->_nebuleInstance->getCurrentObject()
                    . '&' . Displays::DEFAULT_INLINE_COMMAND . '&' . Displays::DEFAULT_INLINE_CONTENT_COMMAND . '=objprop'
                    . '&' . Displays::DEFAULT_NEXT_COMMAND . '=' . $nextLinkSigne;
                $display->displayButtonNextObject($nextLinkSigne, $url, $this->_applicationInstance->getTranslateInstance()->getTranslate('::seeMore'));
            }
            unset($links);
        } else {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'informationType' => 'info',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::EmptyList', $param);
        }
    }


    /**
     * Affichage de la vue nav.
     */
    private function _displayObjectRelations(): void
    {
        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'flagProtection' => $this->_applicationInstance->getCurrentObjectInstance()->getMarkProtected(),
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
        echo $this->_displayInstance->getDisplayObject_DEPRECATED($this->_applicationInstance->getCurrentObjectInstance(), $param);

        // Affiche la navigation.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('objnav');
    }

    /**
     * Affichage de la vue nav en ligne.
     */
    private function _display_InlineObjectRelations(): void
    {
        ?>
        <div class="text">
            <p>
                Nav<br/>
                En cours...
            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la vue de protection.
     */
    private function _displayObjectProtection(): void
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();

        if ($object->getMarkProtected()) {
            // Affiche l'objet seul dans une liste.
            $list = array();
            $list[0]['object'] = $object;
            $list[0]['param'] = array(
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
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'enableDisplayID' => false,
                'flagProtection' => true,
                'enableDisplaySelfHook' => true,
                'enableDisplayTypeHook' => false,
                'enableDisplayJS' => true,
                //'selfHookName' => 'selfMenuObject',
            );
            echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'long');
            unset($list);

            // Affiche en ligne les entités pour qui c'est partagé.
            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('objectprotectionshared');

            // affichage des boutons.
            echo $this->_displayInstance->getDisplayHookMenuList('::sylabe:module:objet:ProtectionButtons', 'medium');
        } else {
            $list = array();

            // Ajout du message de non protection.
            $list[0]['information'] = '::UnprotectedObject';
            $list[0]['param'] = array(
                'enableDisplayIcon' => true,
                'informationType' => 'information',
                'displaySize' => 'small',
                'displayRatio' => 'long',
            );

            // N'affiche un message que si la modification est possible.
            if ($this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            ) {
                // Vérifie la présence de l'objet.
                if ($object->checkPresent()
                    && $this->_applicationInstance->getCurrentObjectID() != $this->_entitiesInstance->getCurrentEntityID()
                ) {
                    // Ajout du message d'avertissement.
                    if ($object->getIsEntity('all')) {
                        $list[1]['information'] = '::WarningDoNotProtectEntity';
                        $list[1]['param'] = array(
                            'enableDisplayIcon' => true,
                            'informationType' => 'warn',
                            'displaySize' => 'small',
                            'displayRatio' => 'long',
                        );
                    } else {
                        $list[1]['information'] = '::WarningProtectObject';
                        $list[1]['param'] = array(
                            'enableDisplayIcon' => true,
                            'informationType' => 'warn',
                            'displaySize' => 'small',
                            'displayRatio' => 'long',
                        );
                    }
                } else {
                    $list[1]['information'] = '::ErrorCantProtectObject';
                    $list[1]['param'] = array(
                        'enableDisplayIcon' => true,
                        'informationType' => 'error',
                        'displaySize' => 'small',
                        'displayRatio' => 'long',
                    );
                }
            }

            // Ajout l'objet.
            $list[2]['object'] = $object->getID();
            $list[2]['param'] = array(
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
                'displaySize' => 'small',
                'displayRatio' => 'long',
                'enableDisplayID' => true,
                'flagProtection' => false,
                'enableDisplaySelfHook' => true,
                'selfHookName' => '::sylabe:module:objet:ProtectionAdd',
                'enableDisplayJS' => false,
            );

            // Ajoute l'action de protection.
            if ($object->checkPresent()
                && $this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                && $this->_applicationInstance->getCurrentObjectID() != $this->_entitiesInstance->getCurrentEntityID()
            ) {
                $list[2]['param']['selfHookList'][0]['name'] = '::ProtectObject';
                $list[2]['param']['selfHookList'][0]['icon'] = $this::MODULE_REGISTERED_ICONS[3];
                $list[2]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                    . '&' . Action::DEFAULT_COMMAND_ACTION_PROTECT_OBJECT . '=' . $object->getID()
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            }

            // Affichage.
            echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');
            unset($list);
        }
    }

    /**
     * Affichage en ligne de la vue de protection.
     *
     * @return void
     */
    private function _display_InlineObjectProtection(): void
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();

        // Affichage les actions possibles.
        $id = $object->getID();

        // Si l'objet est présent.
        if ($object->checkPresent()
            && $object->getMarkProtected()
        ) {
            // Prépare l'affichage.
            $list = array();

            // Ajout du message de protection.
            $list[0]['information'] = '::ProtectedObject';
            $list[0]['param'] = array(
                'enableDisplayIcon' => true,
                'informationType' => 'ok',
                'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                'displayRatio' => 'short',
            );

            // Ajout l'objet non protégé.
            $list[1]['object'] = $object->getUnprotectedID();
            $list[1]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => true,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => false,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'enableDisplayJS' => false,
                'enableDisplaySelfHook' => false,
                'enableDisplayTypeHook' => false,
                'objectName' => $this->_applicationInstance->getTranslateInstance()->getTranslate('::ProtectedID'),
                'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                'displayRatio' => 'short',
            );

            // Ajout l'objet non protégé.
            $list[2]['object'] = $object->getProtectedID();
            $list[2]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => true,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => false,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'enableDisplayJS' => false,
                'enableDisplaySelfHook' => false,
                'enableDisplayTypeHook' => false,
                'objectName' => $this->_applicationInstance->getTranslateInstance()->getTranslate('::UnprotectedID'),
                'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                'displayRatio' => 'short',
            );

            $listOkEntities = array();
            $shareTo = $object->getProtectedTo();
            $i = 3; // Pas 0.
            $instance = null;
            $typeEntity = false;
            foreach ($shareTo as $entity) {
                $instance = $this->_cacheInstance->newNode($entity, \Nebule\Library\Cache::TYPE_ENTITY);
                $typeEntity = $instance->getIsEntity('all');
                if (!isset($listOkEntities[$entity])
                    && $typeEntity
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => true,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => false,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                        'displayRatio' => 'short',
                        'link2Object' => '',
                        'enableDisplaySelfHook' => false,
                        'enableDisplayTypeHook' => false,
                        'selfHookName' => '::sylabe:module:object:protectShared',
                        'typeHookName' => '',
                    );


                    // Ajout l'action de déprotection ou de suppression de partage de protection.
                    if ($this->_unlocked
                        && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                        && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                        && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    ) {
                        if ($entity == $this->_entitiesInstance->getCurrentEntityID()) {
                            // Déprotéger l'objet.
                            $list[$i]['param']['selfHookList'][0]['name'] = '::UnprotectObject';
                            $list[$i]['param']['selfHookList'][0]['icon'] = $this::MODULE_REGISTERED_ICONS[3];
                            $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_ICONS[3]
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_UNPROTECT_OBJECT . '=' . $object->getID()
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        } elseif (!$this->_recoveryInstance->getIsRecoveryEntity($entity)
                            || $this->_configurationInstance->getOptionAsBoolean('permitRecoveryRemoveEntity')
                        ) {
                            // Annuler le partage de protection. Non fiable...
                            $list[$i]['param']['selfHookList'][0]['name'] = '::RemoveShareProtect';
                            $list[$i]['param']['selfHookList'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_ICONS[3]
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_CANCEL_SHARE_PROTECT_TO_ENTITY . '=' . $entity
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        }
                    }


                    // Marque comme vu.
                    $listOkEntities[$entity] = true;
                    $i++;
                }
            }
            unset($instance, $typeEntity, $shareTo, $listOkEntities);

            // Affichage.
            echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');

            unset($list);
        }
    }


    /**
     * Affichage de la vue de protection.
     */
    private function _displayObjectProtectionShare(): void
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();

        if ($object->getMarkProtected()) {
            // Affiche l'objet seul danns une liste.
            $list = array();
            $list[0]['object'] = $object;
            $list[0]['param'] = array(
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
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'enableDisplayID' => false,
                'flagProtection' => true,
                'enableDisplaySelfHook' => true,
                'enableDisplayTypeHook' => false,
                'enableDisplayJS' => true,
            );
            echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'long');

            // affichage des boutons.
            echo $this->_displayInstance->getDisplayHookMenuList('::sylabe:module:objet:ProtectionShareButtons', 'medium');

            if ($this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            ) {
                $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[3]);
                $instance = new DisplayTitle($this->_applicationInstance);
                $instance->setTitle('::sylabe:module:objects:ShareObjectProtection');
                $instance->setIcon($icon);
                $instance->display();

                // Affiche en ligne les entités pour qui c'est partagé.
                $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('objectprotectionshareto');
            }
        }
    }

    /**
     * Affichage en ligne des entités pour lesquelles la protection peut être partagée.
     *
     * @return void
     */
    private function _display_InlineObjectProtectionShare(): void
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();
        $id = $this->_applicationInstance->getCurrentObjectID();

        // Si l'objet est présent et protégé et si l'entité est déverrouillée
        if ($object->getMarkProtected()
            && $this->_unlocked
            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
        ) {
            $listOkEntities = array();
            $listOkGroups = array();
            $list = array();
            $i = 1;
            $instance = null;

            // Ajoute des entités et groupes à ne pas afficher.
            $listOkEntities[$this->_entitiesInstance->getCurrentEntityID()] = true;
            $listOkEntities[$this->_entitiesInstance->getServerEntityID()] = true;
            $listOkEntities[$this->_authoritiesInstance->getPuppetmasterEID()] = true;
            $listOkEntities[$this->_authoritiesInstance->getSecurityMaster()] = true;
            $listOkEntities[$this->_authoritiesInstance->getCodeMaster()] = true;
            $listOkEntities[$this->_authoritiesInstance->getDirectoryMaster()] = true;
            $listOkEntities[$this->_authoritiesInstance->getTimeMaster()] = true;
            $listOkGroups[$this->_entitiesInstance->getCurrentEntityID()] = true;
            $listOkGroups[$this->_entitiesInstance->getServerEntityID()] = true;
            $listOkGroups[$this->_authoritiesInstance->getPuppetmasterEID()] = true;
            $listOkGroups[$this->_authoritiesInstance->getSecurityMaster()] = true;
            $listOkGroups[$this->_authoritiesInstance->getCodeMaster()] = true;
            $listOkGroups[$this->_authoritiesInstance->getDirectoryMaster()] = true;
            $listOkGroups[$this->_authoritiesInstance->getTimeMaster()] = true;

            // Ajout du message de protection.
            $list[0]['information'] = '::WarningSharedProtection';
            $list[0]['param'] = array(
                'enableDisplayIcon' => true,
                'informationType' => 'warn',
                'displaySize' => 'small', // Forcé par getDisplayObjectsList().
                'displayRatio' => 'short',
            );

            // Liste et enlève les entités pour lesquelles la protection est déjà faite.
            $sharedTo = $object->getProtectedTo();
            foreach ($sharedTo as $entity) {
                $listOkEntities[$entity] = true;
            }

            // Liste et ajoute tous les groupes.
            $listGroups = $this->_nebuleInstance->getListGroupsID($this->_entitiesInstance->getCurrentEntityID(), '');
            $typeGroup = false;
            $group = null;
            foreach ($listGroups as $group) {
                // @todo vérifier que le groupe ne contient pas juste des entités pour lesquelles le partage est effectif.

                $instance = $this->_cacheInstance->newNode($group, \Nebule\Library\Cache::TYPE_GROUP);
                $typeGroup = $instance->getIsEntity('all');
                if (!isset($listOkGroups[$group])
                    && $typeGroup
                ) {
                    // Si c'est un groupe fermé.
                    $typeClosed = $instance->getMarkClosed();

                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => true,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => true,
                        'enableDisplayStatus' => true,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                        'displayRatio' => 'short',
                        'link2Object' => '',
                        'enableDisplaySelfHook' => true,
                        'enableDisplayTypeHook' => false,
                        'selfHookName' => '::sylabe:module:object:protectShareToGroup',
                        'typeHookName' => '',
                    );

                    if ($typeClosed) {
                        $list[$i]['param']['status'] = '::GroupeFerme';
                    } else {
                        $list[$i]['param']['status'] = '::GroupeOuvert';
                    }

                    // Ajout l'action de partage d eprotection au groupe.
                    $list[$i]['param']['selfHookList'][0]['name'] = '::sylabe:module:objects:ShareProtectionToGroup';
                    $list[$i]['param']['selfHookList'][0]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    if ($typeClosed) {
                        $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_ICONS[4]
                            . '&' . Action::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_CLOSED . '=' . $group
                            . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    } else {
                        $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_ICONS[4]
                            . '&' . Action::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_OPENED . '=' . $group
                            . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    }

                    // Marque comme vu.
                    $listOkGroups[$group] = true;
                    $i++;
                }
            }
            unset($listGroups, $group, $listOkGroups, $typeGroup, $sharedTo);

            // Liste toutes les autres entités.
            $hashType = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_TYPE);
            $hashEntity = $this->_nebuleInstance->getCryptoInstance()->hash('application/x-pem-file');
            $hashEntityObject = $this->_cacheInstance->newNode($hashEntity);
            $links = $hashEntityObject->getLinksOnFields('', '', 'l', '', $hashEntity, $hashType);

            $typeEntity = false;
            $link = null;
            foreach ($links as $link) {
                $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1'], \Nebule\Library\Cache::TYPE_ENTITY);
                $typeEntity = $instance->getIsEntity('all');
                if (!isset($listOkEntities[$link->getParsed()['bl/rl/nid1']])
                    && $typeEntity
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => true,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => true,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                        'displayRatio' => 'short',
                        'link2Object' => '',
                        'enableDisplaySelfHook' => false,
                        'enableDisplayTypeHook' => false,
                        'selfHookName' => '::sylabe:module:object:protectShareTo',
                        'typeHookName' => '',
                    );

                    // Partager avec cette entité.
                    $list[$i]['param']['selfHookList'][0]['name'] = '::sylabe:module:objects:ShareProtectionToEntity';
                    $list[$i]['param']['selfHookList'][0]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                        . '&' . Action::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_ENTITY . '=' . $link->getParsed()['bl/rl/nid1']
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                    // Marque comme vu.
                    $listOkEntities[$link->getParsed()['bl/rl/nid1']] = true;
                    $i++;
                }
            }
            unset($instance, $link, $typeEntity, $links, $listOkEntities);

            // Affichage.
            echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');

            unset($list);
        }
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::sylabe:module:objects:ModuleName' => 'Module des objets',
            '::sylabe:module:objects:MenuName' => 'Objets',
            '::sylabe:module:objects:ModuleDescription' => 'Module de gestion des objets.',
            '::sylabe:module:objects:ModuleHelp' => "Ce module permet de voir et de gérer les objets.",
            '::sylabe:module:objects:AppTitle1' => 'Objets',
            '::sylabe:module:objects:AppDesc1' => 'Affiche les objets.',
            '::sylabe:module:objects:DisplayObject' => "Afficher l'objet",
            '::sylabe:module:objects:DisplayObjectUpdated' => "Afficher l'objet à jour",
            '::sylabe:module:objects:ObjectDescription' => 'Afficher la description',
            '::sylabe:module:objects:ObjectRelations' => 'Relations',
            '::sylabe:module:objects:ObjectDelete' => 'Supprimer',
            '::sylabe:module:objects:ObjectDownload' => 'Télécharger',
            '::sylabe:module:objects:LinksSrc' => 'Source des liens bruts.',
            '::sylabe:module:objects:DisplayAsObject' => 'Voir comme objet',
            '::sylabe:module:objects:DisplayNewObject' => 'Voir le nouvel objet',
            '::sylabe:module:objects:Actions' => "Actions sur l'objet",
            '::sylabe:module:objects:ActionsDesc' => 'Les actions simples sur cet objet.',
            '::sylabe:module:objects:ExtendedActions' => "Actions étendues sur l'objet",
            '::sylabe:module:objects:ExtendedActionsDesc' => 'Les actions avancées sur cet objet.',
            '::sylabe:module:objects:Description' => "Description de l'objet",
            '::sylabe:module:objects:DescriptionDesc' => "Les propriétés de l'objet.",
            '::sylabe:module:objects:Nothing' => 'Rien à afficher.',
            '::sylabe:module:objects:Action:Download' => "Télécharger l'objet.",
            '::sylabe:module:objects:Desc:Attrib' => 'Propriété',
            '::sylabe:module:objects:Desc:Value' => 'Valeur',
            '::sylabe:module:objects:Desc:Signer' => 'Emetteur',
            '::sylabe:module:objects:Protection' => 'Protection',
            '::sylabe:module:objects:ShareProtection' => 'Partager la protection',
            '::sylabe:module:objects:ShareObjectProtection' => "Partager la protection de l'objet",
            '::sylabe:module:objects:ShareProtectionToGroup' => 'Partager la protection',
            '::sylabe:module:objects:ShareProtectionToEntity' => 'Partager la protection',
            '::WarningSharedProtection' => "Lorsque la protection d'un objet est partagée, son annulation est incertaine !",
            '::RemoveShareProtect' => 'Annuler le partage de protection',
            '::ProtectObject' => "Protéger l'objet",
            '::ProtectedObject' => 'Cet objet est protégé.',
            '::ProtectedID' => 'ID objet en clair',
            '::UnprotectedID' => 'ID objet chiffré',
            '::UnprotectedObject' => "Cet objet n'est pas protégé.",
            '::UnprotectObject' => "Déprotéger l'objet",
            '::WarningProtectObject' => "La protection d'un objet déjà existant est incertaine !",
            '::WarningDoNotProtectEntity' => "La protection d'une entité la rend indisponible !",
            '::ErrorCantProtectObject' => 'Cet objet ne peut pas être protégé.',
            '::sylabe:module:objects:AttribNotDisplayable' => 'Propriété non affichable !',
            '::GroupeFerme' => 'Groupe fermé',
            '::GroupeOuvert' => 'Groupe ouvert',
        ],
        'en-en' => [
            '::sylabe:module:objects:ModuleName' => 'Objects module',
            '::sylabe:module:objects:MenuName' => 'Objects',
            '::sylabe:module:objects:ModuleDescription' => 'Object management module.',
            '::sylabe:module:objects:ModuleHelp' => 'This module permit to see and manage objects.',
            '::sylabe:module:objects:AppTitle1' => 'Objects',
            '::sylabe:module:objects:AppDesc1' => 'Display objects.',
            '::sylabe:module:objects:DisplayObject' => 'Display object',
            '::sylabe:module:objects:DisplayObjectUpdated' => 'Display updated object',
            '::sylabe:module:objects:ObjectDescription' => 'Display description',
            '::sylabe:module:objects:ObjectRelations' => 'Relations',
            '::sylabe:module:objects:ObjectDelete' => 'Delete',
            '::sylabe:module:objects:ObjectDownload' => 'Download',
            '::sylabe:module:objects:LinksSrc' => 'Source of raw links.',
            '::sylabe:module:objects:DisplayAsObject' => 'See as object',
            '::sylabe:module:objects:DisplayNewObject' => 'See the new object',
            '::sylabe:module:objects:Actions' => 'Actions on object',
            '::sylabe:module:objects:ActionsDesc' => 'Simple actions on this object.',
            '::sylabe:module:objects:ExtendedActions' => 'Extended actions on object',
            '::sylabe:module:objects:ExtendedActionsDesc' => 'Advanced actions on this object.',
            '::sylabe:module:objects:Description' => 'Object description',
            '::sylabe:module:objects:DescriptionDesc' => "Object's properties.",
            '::sylabe:module:objects:Nothing' => 'Nothing to display.',
            '::sylabe:module:objects:Action:Download' => 'Download object.',
            '::sylabe:module:objects:Desc:Attrib' => 'Attribut',
            '::sylabe:module:objects:Desc:Value' => 'Value',
            '::sylabe:module:objects:Desc:Signer' => 'Sender',
            '::sylabe:module:objects:Protection' => 'Protection',
            '::sylabe:module:objects:ShareProtection' => 'Share protection',
            '::sylabe:module:objects:ShareObjectProtection' => 'Share protection of this objet',
            '::sylabe:module:objects:ShareProtectionToGroup' => 'Share protection',
            '::sylabe:module:objects:ShareProtectionToEntity' => 'Share protection',
            '::WarningSharedProtection' => 'When protection of an object is shared, its cancellation is uncertain!',
            '::RemoveShareProtect' => 'Cancel share protection',
            '::ProtectObject' => 'Protect the object',
            '::ProtectedObject' => 'This object is protected.',
            '::ProtectedID' => 'Clear object ID',
            '::UnprotectedID' => 'Encrypted object ID',
            '::UnprotectedObject' => 'This object is not protected.',
            '::UnprotectObject' => 'Unprotect the object',
            '::WarningProtectObject' => 'The protection of an existing object is uncertain!',
            '::WarningDoNotProtectEntity' => 'The protection of an entity make it unavailable!',
            '::ErrorCantProtectObject' => "This object can't be protected.",
            '::sylabe:module:objects:AttribNotDisplayable' => 'Attribut not displayable!',
            '::GroupeFerme' => 'Closed group',
            '::GroupeOuvert' => 'Opened group',
        ],
        'es-co' => [
            '::sylabe:module:objects:ModuleName' => 'Módulo de objetos',
            '::sylabe:module:objects:MenuName' => 'Objetos',
            '::sylabe:module:objects:ModuleDescription' => 'Módulo de gestión de objetos.',
            '::sylabe:module:objects:ModuleHelp' => 'This module permit to see and manage objects.',
            '::sylabe:module:objects:AppTitle1' => 'Objetos',
            '::sylabe:module:objects:AppDesc1' => 'Display objects.',
            '::sylabe:module:objects:DisplayObject' => 'Display object',
            '::sylabe:module:objects:DisplayObjectUpdated' => 'Display updated object',
            '::sylabe:module:objects:ObjectDescription' => 'Display description',
            '::sylabe:module:objects:ObjectRelations' => 'Relations',
            '::sylabe:module:objects:ObjectDelete' => 'Delete',
            '::sylabe:module:objects:ObjectDownload' => 'Download',
            '::sylabe:module:objects:LinksSrc' => 'Fuente de enlaces primas.',
            '::sylabe:module:objects:DisplayAsObject' => 'See as object',
            '::sylabe:module:objects:DisplayNewObject' => 'See the new object',
            '::sylabe:module:objects:Actions' => 'Actions on object',
            '::sylabe:module:objects:ActionsDesc' => 'Simple actions on this object.',
            '::sylabe:module:objects:ExtendedActions' => 'Extended actions on object',
            '::sylabe:module:objects:ExtendedActionsDesc' => 'Advanced actions on this object.',
            '::sylabe:module:objects:Description' => 'Object description',
            '::sylabe:module:objects:DescriptionDesc' => "Object's properties.",
            '::sylabe:module:objects:Nothing' => 'Nothing to display.',
            '::sylabe:module:objects:Action:Download' => 'Download object.',
            '::sylabe:module:objects:Desc:Attrib' => 'Attribut',
            '::sylabe:module:objects:Desc:Value' => 'Value',
            '::sylabe:module:objects:Desc:Signer' => 'Sender',
            '::sylabe:module:objects:Protection' => 'Protection',
            '::sylabe:module:objects:ShareProtection' => 'Share protection',
            '::sylabe:module:objects:ShareObjectProtection' => 'Share protection of this objet',
            '::sylabe:module:objects:ShareProtectionToGroup' => 'Share protection',
            '::sylabe:module:objects:ShareProtectionToEntity' => 'Share protection',
            '::WarningSharedProtection' => 'Donde se comparte la protección de un objeto, su cancelación esta incierto!',
            '::RemoveShareProtect' => 'Cancel share protection',
            '::ProtectObject' => 'Protect the object',
            '::ProtectedObject' => 'This object is protected.',
            '::ProtectedID' => 'Clear object ID',
            '::UnprotectedID' => 'Encrypted object ID',
            '::UnprotectedObject' => 'This object is not protected.',
            '::UnprotectObject' => 'Unprotect the object',
            '::WarningProtectObject' => 'The protection of an existing object is uncertain!',
            '::WarningDoNotProtectEntity' => 'The protection of an entity make it unavailable!',
            '::ErrorCantProtectObject' => "This object can't be protected.",
            '::sylabe:module:objects:AttribNotDisplayable' => 'Attribut not displayable!',
            '::GroupeFerme' => 'Closed group',
            '::GroupeOuvert' => 'Opened group',
        ],
    ];
}
