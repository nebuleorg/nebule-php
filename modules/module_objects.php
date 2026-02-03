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
 * Ce module permet gérer les objets.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleObjects extends Module {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'obj';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260111';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2026';
    const MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'disp',
        'desc',
        'nav',
        'prot',
        'sprot',
    );
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



    public function getHookList(string $hookName, ?\Nebule\Library\Node $instance = null): array
    {
        $nid = $this->_applicationInstance->getCurrentObjectID();
        if ($instance !== null)
            $nid = $instance->getID();

        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuObject':
                //$instance = $this->_applicationInstance->getCurrentObjectInstance();
                $instance = $this->_cacheInstance->newNodeByType($nid);
                $id = $instance->getID();

                // Recherche si l'objet est protégé.
                $protected = $instance->getMarkProtected();
                if ($protected) {
                    $id = $instance->getUnprotectedID();
                    $instance = $this->_cacheInstance->newNodeByType($id);
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
                    $hookArray[0]['name'] = '::DisplayObject';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    // Si l'objet a une mise à jour.
                    if ($update != $id) {
                        // Affichage de l'objet à jour.
                        $hookArray[1]['name'] = '::DisplayObjectUpdated';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LU;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $update
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    }
                }

                // Description de l'objet.
                if ($mode == $this::MODULE_COMMAND_NAME
                    && $view != $this::MODULE_REGISTERED_VIEWS[1]
                ) {
                    $hookArray[2]['name'] = '::ObjectDescription';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // Naviguer autour de l'objet.
                if ($mode == $this::MODULE_COMMAND_NAME
                    && $view != $this::MODULE_REGISTERED_VIEWS[2]
                ) {
                    $hookArray[3]['name'] = '::ObjectRelations';
                    $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }

                // Si le contenu de l'objet est présent.
                if ($instance->checkPresent()) {
                    // Télécharger l'objet.
                    $hookArray[4]['name'] = '::ObjectDownload';
                    $hookArray[4]['icon'] = Displays::DEFAULT_ICON_IDOWNLOAD;
                    $hookArray[4]['desc'] = '::Action:Download';
                    $hookArray[4]['link'] = '?o=' . $id;

                    // Si l'entité est déverrouillée.
                    if ($this->_unlocked
                        && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                        && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                        && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                    ) {
                        // Supprimer l'objet.
                        $hookArray[5]['name'] = '::ObjectDelete';
                        $hookArray[5]['icon'] = Displays::DEFAULT_ICON_LD;
                        $hookArray[5]['desc'] = '';
                        $hookArray[5]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . \Nebule\Library\ActionsObjects::DELETE . '=' . $id
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

                        // Protéger l'objet.
                        if ($mode == $this::MODULE_COMMAND_NAME
                            && $view != $this::MODULE_REGISTERED_VIEWS[3]
                        ) {
                            $hookArray[6]['name'] = '::Protection';
                            $hookArray[6]['icon'] = $this::MODULE_REGISTERED_ICONS[3];
                            $hookArray[6]['desc'] = '';
                            $hookArray[6]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                        }

                        // Partager la protection
                        if ($mode == $this::MODULE_COMMAND_NAME
                            && $view != $this::MODULE_REGISTERED_VIEWS[4]
                            && $protected
                        ) {
                            $hookArray[7]['name'] = '::ShareProtection';
                            $hookArray[7]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                            $hookArray[7]['desc'] = '';
                            $hookArray[7]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                        }
                    }
                }

                // Si l'objet n'est pas marqué.
                if (!$marked) {
                    // Ajouter la marque de l'objet.
                    $hookArray[8]['name'] = '::MarkAdd';
                    $hookArray[8]['icon'] = References::REF_IMG['mark'];
                    $hookArray[8]['desc'] = '';
                    $hookArray[8]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . \Nebule\Library\ActionsMarks::MARK . '=' . $id
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                } else {
                    // Retirer la marque de l'objet.
                    $hookArray[8]['name'] = '::MarkRemove';
                    $hookArray[8]['icon'] = References::REF_IMG['unmark'];
                    $hookArray[8]['desc'] = '';
                    $hookArray[8]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . \Nebule\Library\ActionsMarks::UNMARK . '=' . $id
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                }

                // Si la liste des marques n'est pas vide.
                if (sizeof($markList) != 0) {
                    // Retirer la marque de tous les objets.
                    $hookArray[9]['name'] = '::MarkRemoveAll';
                    $hookArray[9]['icon'] = References::REF_IMG['unmarkall'];
                    $hookArray[9]['desc'] = '';
                    $hookArray[9]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $nid
                        . '&' . \Nebule\Library\ActionsMarks::UNMARK_ALL
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                }
                unset($instance, $id, $protected, $update, $markList, $marked);
                break;

            case 'typeFile':
                $hookArray[] = array(
                    'name' => '::seeTheFile',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $instance->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
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
        $startLinkSigne = $this->_nebuleInstance->getDisplayNextObject_obsolete();
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
            $attribList = References::NODE_REFERENCES;
            $emotionsList = array(
                $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE) => References::REFERENCE_NEBULE_OBJET_EMOTION_JOIE,
                $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE) => References::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE,
                $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR) => References::REFERENCE_NEBULE_OBJET_EMOTION_PEUR,
                $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE) => References::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE,
                $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE) => References::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE,
                $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT) => References::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT,
                $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE) => References::REFERENCE_NEBULE_OBJET_EMOTION_COLERE,
                $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET) => References::REFERENCE_NEBULE_OBJET_EMOTION_INTERET,
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
                            $attribInstance = $this->_cacheInstance->newNodeByType($hashAttrib);
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
                        $valueInstance = $this->_cacheInstance->newNodeByType($hashValue);
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
                                        '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')::MODULE_COMMAND_NAME
                                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . References::COMMAND_SELECT_LINK
                                        . '&' . References::COMMAND_SELECT_LINK . '=' . $link->getFullLink()); ?>

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
                                        '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')::MODULE_COMMAND_NAME
                                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . References::COMMAND_SELECT_LINK
                                        . '&' . References::COMMAND_SELECT_LINK . '=' . $link->getFullLink()); ?>

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
                                        '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')::MODULE_COMMAND_NAME
                                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . References::COMMAND_SELECT_LINK
                                        . '&' . References::COMMAND_SELECT_LINK . '=' . $link->getFullLink()); ?>
                                    &nbsp;
                                    <?php $display->displayInlineIconFace('DEFAULT_ICON_IWARN'); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleObjectsDescDate"><?php $display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleObjectsDescSigner"><?php $display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleObjectsDescContent">
                                <span class="sylabeModuleObjectsDescAttrib"><?php echo $this->_translateInstance->getTranslate('::AttribNotDisplayable'); ?></span>
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
                $url = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $display->getCurrentDisplayView()
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $this->_nebuleInstance->getCurrentObjectOID()
                    . '&' . Displays::COMMAND_INLINE . '&' . Displays::COMMAND_INLINE_CONTENT . '=objprop'
                    . '&' . Displays::COMMAND_NEXT . '=' . $nextLinkSigne;
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
                    && $this->_applicationInstance->getCurrentObjectID() != $this->_entitiesInstance->getGhostEntityEID()
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
                && $this->_applicationInstance->getCurrentObjectID() != $this->_entitiesInstance->getGhostEntityEID()
            ) {
                $list[2]['param']['selfHookList'][0]['name'] = '::ProtectObject';
                $list[2]['param']['selfHookList'][0]['icon'] = $this::MODULE_REGISTERED_ICONS[3];
                $list[2]['param']['selfHookList'][0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                    . '&' . \Nebule\Library\ActionsObjects::PROTECT . '=' . $object->getID()
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
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
                $instance = $this->_cacheInstance->newNodeByType($entity, \Nebule\Library\Cache::TYPE_ENTITY);
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
                        if ($entity == $this->_entitiesInstance->getGhostEntityEID()) {
                            // Déprotéger l'objet.
                            $list[$i]['param']['selfHookList'][0]['name'] = '::UnprotectObject';
                            $list[$i]['param']['selfHookList'][0]['icon'] = $this::MODULE_REGISTERED_ICONS[3];
                            $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_ICONS[3]
                                . '&' . \Nebule\Library\ActionsObjects::UNPROTECT . '=' . $object->getID()
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                        } elseif (!$this->_recoveryInstance->getIsRecoveryEntity($entity)
                            || $this->_configurationInstance->getOptionAsBoolean('permitRecoveryRemoveEntity')
                        ) {
                            // Annuler le partage de protection. Non fiable...
                            $list[$i]['param']['selfHookList'][0]['name'] = '::RemoveShareProtect';
                            $list[$i]['param']['selfHookList'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_ICONS[3]
                                . '&' . \Nebule\Library\ActionsObjects::CANCEL_SHARE_PROTECT_TO_ENTITY . '=' . $entity
                                . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
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
                $icon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[3]);
                $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
                $instance->setTitle('::ShareObjectProtection');
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
            $listOkEntities[$this->_entitiesInstance->getGhostEntityEID()] = true;
            $listOkEntities[$this->_entitiesInstance->getServerEntityEID()] = true;
            $listOkEntities[$this->_authoritiesInstance->getPuppetmasterEID()] = true;
            $listOkEntities[$this->_authoritiesInstance->getSecurityMaster()] = true;
            $listOkEntities[$this->_authoritiesInstance->getCodeMaster()] = true;
            $listOkEntities[$this->_authoritiesInstance->getDirectoryMaster()] = true;
            $listOkEntities[$this->_authoritiesInstance->getTimeMaster()] = true;
            $listOkGroups[$this->_entitiesInstance->getGhostEntityEID()] = true;
            $listOkGroups[$this->_entitiesInstance->getServerEntityEID()] = true;
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
            $listGroups = $this->_nebuleInstance->getListGroupsID('', 'myself');
            $typeGroup = false;
            $group = null;
            foreach ($listGroups as $group) {
                // @todo vérifier que le groupe ne contient pas juste des entités pour lesquelles le partage est effectif.

                $instance = $this->_cacheInstance->newGroup($group);
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
                        $list[$i]['param']['status'] = '::closeGroup';
                    } else {
                        $list[$i]['param']['status'] = '::openGroup';
                    }

                    // Ajout l'action de partage d eprotection au groupe.
                    $list[$i]['param']['selfHookList'][0]['name'] = '::ShareProtectionToGroup';
                    $list[$i]['param']['selfHookList'][0]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    if ($typeClosed) {
                        $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_ICONS[4]
                            . '&' . \Nebule\Library\ActionsObjects::SHARE_PROTECT_TO_GROUP_CLOSED . '=' . $group
                            . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    } else {
                        $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_ICONS[4]
                            . '&' . \Nebule\Library\ActionsObjects::SHARE_PROTECT_TO_GROUP_OPENED . '=' . $group
                            . '&' . \Nebule\Library\References::COMMAND_SELECT_OBJECT . '=' . $id
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }

                    // Marque comme vu.
                    $listOkGroups[$group] = true;
                    $i++;
                }
            }
            unset($listGroups, $group, $listOkGroups, $typeGroup, $sharedTo);

            // Liste toutes les autres entités.
            $hashType = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_TYPE);
            $hashEntity = $this->getNidFromData('application/x-pem-file');
            $hashEntityObject = $this->_cacheInstance->newNodeByType($hashEntity);
            $links = $hashEntityObject->getLinksOnFields('', '', 'l', '', $hashEntity, $hashType);

            $typeEntity = false;
            $link = null;
            foreach ($links as $link) {
                $instance = $this->_cacheInstance->newNodeByType($link->getParsed()['bl/rl/nid1'], \Nebule\Library\Cache::TYPE_ENTITY);
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
                    $list[$i]['param']['selfHookList'][0]['name'] = '::ShareProtectionToEntity';
                    $list[$i]['param']['selfHookList'][0]['icon'] = $this::MODULE_REGISTERED_ICONS[4];
                    $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                        . '&' . \Nebule\Library\ActionsObjects::SHARE_PROTECT_TO_ENTITY . '=' . $link->getParsed()['bl/rl/nid1']
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                        . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

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
            '::ModuleName' => 'Module des objets',
            '::MenuName' => 'Objets',
            '::ModuleDescription' => 'Module de gestion des objets.',
            '::ModuleHelp' => "Ce module permet de voir et de gérer les objets.",
            '::AppTitle1' => 'Objets',
            '::AppDesc1' => 'Affiche les objets.',
            '::DisplayObject' => "Afficher l'objet",
            '::DisplayObjectUpdated' => "Afficher l'objet à jour",
            '::ObjectDescription' => 'Afficher la description',
            '::ObjectRelations' => 'Relations',
            '::ObjectDelete' => 'Supprimer',
            '::ObjectDownload' => 'Télécharger',
            '::LinksSrc' => 'Source des liens bruts.',
            '::DisplayAsObject' => 'Voir comme objet',
            '::DisplayNewObject' => 'Voir le nouvel objet',
            '::Actions' => "Actions sur l'objet",
            '::ActionsDesc' => 'Les actions simples sur cet objet.',
            '::ExtendedActions' => "Actions étendues sur l'objet",
            '::ExtendedActionsDesc' => 'Les actions avancées sur cet objet.',
            '::Description' => "Description de l'objet",
            '::DescriptionDesc' => "Les propriétés de l'objet.",
            '::Nothing' => 'Rien à afficher.',
            '::Action:Download' => "Télécharger l'objet.",
            '::Desc:Attrib' => 'Propriété',
            '::Desc:Value' => 'Valeur',
            '::Desc:Signer' => 'Emetteur',
            '::Protection' => 'Protection',
            '::ShareProtection' => 'Partager la protection',
            '::ShareObjectProtection' => "Partager la protection de l'objet",
            '::ShareProtectionToGroup' => 'Partager la protection',
            '::ShareProtectionToEntity' => 'Partager la protection',
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
            '::AttribNotDisplayable' => 'Propriété non affichable !',
            '::closeGroup' => 'Groupe fermé',
            '::openGroup' => 'Groupe ouvert',
        ],
        'en-en' => [
            '::ModuleName' => 'Objects module',
            '::MenuName' => 'Objects',
            '::ModuleDescription' => 'Object management module.',
            '::ModuleHelp' => 'This module permit to see and manage objects.',
            '::AppTitle1' => 'Objects',
            '::AppDesc1' => 'Display objects.',
            '::DisplayObject' => 'Display object',
            '::DisplayObjectUpdated' => 'Display updated object',
            '::ObjectDescription' => 'Display description',
            '::ObjectRelations' => 'Relations',
            '::ObjectDelete' => 'Delete',
            '::ObjectDownload' => 'Download',
            '::LinksSrc' => 'Source of raw links.',
            '::DisplayAsObject' => 'See as object',
            '::DisplayNewObject' => 'See the new object',
            '::Actions' => 'Actions on object',
            '::ActionsDesc' => 'Simple actions on this object.',
            '::ExtendedActions' => 'Extended actions on object',
            '::ExtendedActionsDesc' => 'Advanced actions on this object.',
            '::Description' => 'Object description',
            '::DescriptionDesc' => "Object's properties.",
            '::Nothing' => 'Nothing to display.',
            '::Action:Download' => 'Download object.',
            '::Desc:Attrib' => 'Attribut',
            '::Desc:Value' => 'Value',
            '::Desc:Signer' => 'Sender',
            '::Protection' => 'Protection',
            '::ShareProtection' => 'Share protection',
            '::ShareObjectProtection' => 'Share protection of this objet',
            '::ShareProtectionToGroup' => 'Share protection',
            '::ShareProtectionToEntity' => 'Share protection',
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
            '::AttribNotDisplayable' => 'Attribut not displayable!',
            '::closeGroup' => 'Closed group',
            '::openGroup' => 'Opened group',
        ],
        'es-co' => [
            '::ModuleName' => 'Módulo de objetos',
            '::MenuName' => 'Objetos',
            '::ModuleDescription' => 'Módulo de gestión de objetos.',
            '::ModuleHelp' => 'This module permit to see and manage objects.',
            '::AppTitle1' => 'Objetos',
            '::AppDesc1' => 'Display objects.',
            '::DisplayObject' => 'Display object',
            '::DisplayObjectUpdated' => 'Display updated object',
            '::ObjectDescription' => 'Display description',
            '::ObjectRelations' => 'Relations',
            '::ObjectDelete' => 'Delete',
            '::ObjectDownload' => 'Download',
            '::LinksSrc' => 'Fuente de enlaces primas.',
            '::DisplayAsObject' => 'See as object',
            '::DisplayNewObject' => 'See the new object',
            '::Actions' => 'Actions on object',
            '::ActionsDesc' => 'Simple actions on this object.',
            '::ExtendedActions' => 'Extended actions on object',
            '::ExtendedActionsDesc' => 'Advanced actions on this object.',
            '::Description' => 'Object description',
            '::DescriptionDesc' => "Object's properties.",
            '::Nothing' => 'Nothing to display.',
            '::Action:Download' => 'Download object.',
            '::Desc:Attrib' => 'Attribut',
            '::Desc:Value' => 'Value',
            '::Desc:Signer' => 'Sender',
            '::Protection' => 'Protection',
            '::ShareProtection' => 'Share protection',
            '::ShareObjectProtection' => 'Share protection of this objet',
            '::ShareProtectionToGroup' => 'Share protection',
            '::ShareProtectionToEntity' => 'Share protection',
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
            '::AttribNotDisplayable' => 'Attribut not displayable!',
            '::closeGroup' => 'Closed group',
            '::openGroup' => 'Opened group',
        ],
    ];
}
