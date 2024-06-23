<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayObject
 *       ---
 *  FIXME
 *  Retourne la représentation html de l'objet en fonction des paramètres passés.
 *  Les paramètres d'activation de contenus :
 *  - enableDisplayColor : Affiche le carré de couleur.
 *      Par défaut true : affiche le carré de couleur.
 *      enableDisplayIconApp doit être à false.
 *      Boolean
 *  - enableDisplayIcon : Affiche le carré avec l'image attaché à l'objet ou l'icône de son type sur la couleur de l'objet en fond.
 *      Par défaut true : affiche le carré de l'image/icône.
 *      enableDisplayIconApp doit être à false.
 *      Boolean
 *  - enableDisplayIconApp : Affiche le carré de couleur avec le nom long et court d'une application.
 *      Par défaut true : affiche le carré de couleur avec le nom.
 *      Boolean
 *  - enableDisplayRefs : Affiche le(s) référence(s) de l'objet (signataire du lien).
 *      enableDisplayName doit être à true.
 *      Par défaut false : n'affiche pas la référence.
 *      Boolean
 *  - enableDisplayName : Affiche le nom de l'objet.
 *      Par défaut true : affiche le nom.
 *      Boolean
 *  - enableDisplayID : Affiche l'ID de l'objet.
 *      Par défaut false : n'affiche pas l'ID.
 *      Boolean
 *  - enableDisplayFlags : Affiche les icônes d'état de l'objet (protection...).
 *      enableDisplayName doit être à true.
 *      Par défaut false : n'affiche pas les icônes d'état.
 *      Boolean
 *  - enableDisplayFlagEmotions : Affiche les icônes des émotions de l'objet sans les compteurs.
 *      enableDisplayFlags et l'option displayEmotions doivent être à true.
 *      Par défaut false : n'affiche pas les icônes.
 *      Boolean
 *  - enableDisplayFlagProtection : Affiche l'icône de protection de l'objet.
 *      enableDisplayFlags doit être à true.
 *      L'option permitProtectedObject doit être à true.
 *      Par défaut false : n'affiche pas l'icône.
 *      Boolean
 *  - enableDisplayFlagObfuscate : Affiche l'icône de dissimulation de l'objet.
 *      enableDisplayFlags doit être à true.
 *      L'option permitObfuscatedLink doit être à true.
 *      Par défaut false : n'affiche pas l'icône.
 *      Boolean
 *  - enableDisplayFlagUnlocked : Affiche l'icône de déverrouillage de l'entité.
 *      enableDisplayFlags doit être à true.
 *      Par défaut false : n'affiche pas l'icône.
 *      Boolean
 *  - enableDisplayFlagActivated : Affiche l'icône d'activation de l'objet.
 *      enableDisplayFlags doit être à true.
 *      Par défaut false : n'affiche pas l'icône.
 *      Boolean
 *  - enableDisplayFlagState : Affiche l'icône d'état de l'objet.
 *      enableDisplayFlags doit être à true.
 *      Par défaut false : n'affiche pas l'icône.
 *      Boolean
 *  - enableDisplayStatus : Affiche le status de l'objet (indicatif).
 *      Par défaut false : n'affiche pas le status.
 *      Boolean
 *  - enableDisplayContent : Affiche le contenu de l'objet si possible.
 *      Par défaut false : n'affiche pas le contenu.
 *      Boolean
 *  - enableDisplayLink2Object : Affiche le lien HTML vers l'objet :
 *      Sur le nom de l'objet.
 *      Sur le carré de couleur et sur l'image/icône de l'objet, ou si le menu des actions est activé le menu remplace le lien HTML.
 *      Par défaut true : affiche le lien ou le menu.
 *      Boolean
 *  - enableDisplayObjectActions : Affiche le menu des actions liées à l'objet.
 *      Sinon le lien de l'objet est utilisé à la place.
 *      enableDisplayLink2Object doit être à true.
 *      Par défaut true : affiche le menu.
 *      Boolean
 *  - enableDisplayLink2Refs : Affiche le lien HTML vers le(s) référence(s) de l'objet.
 *      enableDisplayRefs doit être à true.
 *      Par défaut true : affiche le lien.
 *      Boolean
 *  - enableDisplaySelfHook : Affiche les actions principales de l'objet utilisé.
 *      enableDisplayObjectActions doit être à true.
 *      Par défaut true : affiche les actions.
 *      Boolean
 *  - enableDisplayTypeHook : Affiche les actions secondaires de l'objet par rapport à son type.
 *      enableDisplayObjectActions doit être à true.
 *      Par défaut true si enableDisplayJS : affiche les actions.
 *      Par défaut false si pas enableDisplayJS : n'affiche pas les actions.
 *      Boolean
 *  - enableDisplayJS : Utilise le Java Script pour le menu des actions.
 *      Si false, le menu n'est pas caché et son contenu s'affiche sous la barre de titre de l'objet.
 *      Par défaut true : utilise le Java Script.
 *      Boolean
 *  Les paramètres de définition de contenus :
 *  - social : Détermine le niveau social de tri des liens.
 *      Par défaut vide : utilise le niveau social par défaut.
 *      String
 *  - objectType : Détermine le type d'objet pour le traitement.
 *      Par défaut null : le type est extrait en fonction du niveau social.
 *      String
 *  - objectName : Détermine le nom de l'objet ou un texte de remplacement.
 *      enableDisplayName doit être à true.
 *      Par défaut null : le nom complet est extrait en fonction du niveau social.
 *      Si enableDisplayIconApp à true, le nom simple est extrait en fonction du niveau social.
 *      String
 *  - objectAppShortName : Détermine le nom de l'objet ou un texte de remplacement.
 *      enableDisplayIconApp doit être à true.
 *      Par défaut null : le nom court (prénom) est extrait en fonction du niveau social.
 *      String
 *  - objectIcon : Détermine l'image de l'objet.
 *      enableDisplayIcon doit être à true.
 *      Le fond est de la couleur de l'objet.
 *      Par défaut null : l'image est une icône représentant le type d'objet.
 *      String
 *  - objectRefs : Détermine la liste des références de l'objet affiché, ou autres entités.
 *      Si c'est un text, affiche juste le texte après un filtre.
 *      Par défaut vide.
 *      Array of string|Object ou string
 *  - link2Object : Détermine le lien HTML vers l'objet.
 *      Par défaut vide : le lien est préparé vers l'objet en fonction de son type.
 *      String
 *  - flagProtection : Détermine l'icône de protection de l'objet.
 *      enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
 *      Par défaut false : icône éteinte.
 *      Boolean
 *  - flagProtectionIcon : Détermine l'icône de protection de l'objet.
 *      Permet de détourner le bouton de son usage primaire.
 *      enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
 *      Par défaut vide : icône de lien de chiffrement LK.
 *      String
 *  - flagProtectionText : Détermine l'icône de protection de l'objet.
 *      Permet de détourner le bouton de son usage primaire.
 *      enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
 *      Par défaut vide : Texte standard traduit.
 *      String
 *  - flagProtectionLink : Détermine le lien HTML de l'icône de protection de l'objet.
 *      Permet de détourner le bouton de son usage primaire.
 *      enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
 *      Par défaut vide.
 *      String
 *  - flagObfuscate : Détermine l'icône de dissimulation de l'objet.
 *      enableDisplayFlags et enableDisplayFlagObfuscate doivent être à true.
 *      Par défaut false : icône éteinte.
 *      Boolean
 *  - flagObfuscateIcon : Détermine l'icône de dissimulation de l'objet.
 *      Permet de détourner le bouton de son usage primaire.
 *      enableDisplayFlags et enableDisplayFlagObfuscate doivent être à true.
 *      Par défaut vide : icône de lien de dissimulation LC.
 *      String
 *  - flagObfuscateText : Détermine l'icône de dissimulation de l'objet.
 *      Permet de détourner le bouton de son usage primaire.
 *      enableDisplayFlags et enableDisplayFlagObfuscate doivent être à true.
 *      Par défaut vide : Texte standard traduit.
 *      String
 *  - flagObfuscateLink : Détermine le lien HTML de l'icône de dissimulation de l'objet.
 *      Permet de détourner le bouton de son usage primaire.
 *      enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
 *      Par défaut vide.
 *      String
 *  - flagUnlocked : Détermine l'icône de déverrouillage de l'entité.
 *      enableDisplayFlags et enableDisplayFlagUnlocked doivent être à true.
 *      Par défaut dépend de l'état de l'entité.
 *      Si pas une entité, par défaut false : icône éteinte.
 *      Boolean
 *  - flagUnlockedIcon : Détermine l'icône de déverrouillage de l'entité.
 *      Permet de détourner le bouton de son usage primaire.
 *      enableDisplayFlags et enableDisplayFlagUnlocked doivent être à true.
 *      Par défaut vide : icône de lien de chiffrement LK.
 *      String
 *  - flagUnlockedText : Détermine l'icône de déverrouillage de l'entité.
 *      Permet de détourner le bouton de son usage primaire.
 *      enableDisplayFlags et enableDisplayFlagUnlocked doivent être à true.
 *      Par défaut vide : Texte standard traduit.
 *      String
 *  - flagUnlockedLink : Détermine le lien HTML de l'icône de déverrouillage de l'entité.
 *      Permet de détourner le bouton de son usage primaire.
 *      enableDisplayFlags et enableDisplayFlagUnlocked doivent être à true.
 *      Par défaut vide.
 *      String
 *  - flagActivated : Détermine l'icône d'activation de l'objet.
 *      enableDisplayFlags et enableDisplayFlagActivateded doivent être à true.
 *      Par défaut false : icône rouge en croix.
 *      Si à true, icône verte validée.
 *      Boolean
 *  - flagActivatedDesc : Détermine le texte de description de l'activation de l'objet.
 *      enableDisplayFlags et enableDisplayFlagActivated doivent être à true.
 *      Par défaut vide : calcul la description de l'état de l'objet.
 *      Le texte de ces état est traduit.
 *      Par défaut est calculé par rapport à flagActivated :
 *        - false : ':::display:content:NotActived'
 *        - true  : ':::display:content:Actived'
 *      String
 *  - flagState : Détermine l'icône de l'état de l'objet.
 *      enableDisplayFlags et enableDisplayFlagState doivent être à true.
 *      Par défaut vide : calcul l'état de l'objet.
 *      Les états possibles sont :
 *        - e : l'objet est taggé 'banni' ;
 *        - w : l'objet est taggé 'warning' ;
 *        - n : l'objet n'est pas présent ;
 *        - o : OK tout va bien.
 *      String
 *  - flagStateDesc : Détermine le texte de description de l'état de l'objet.
 *      enableDisplayFlags et enableDisplayFlagState doivent être à true.
 *      Par défaut vide : calcul la description de l'état de l'objet.
 *      Le texte de ces état est traduit.
 *      Par défaut est calculé par rapport à flagState :
 *        - e : ':::display:content:errorBan'
 *        - w : ':::display:content:warningTaggedWarning'
 *        - n : ':::display:content:errorNotAvailable'
 *        - o : ':::display:content:OK'
 *      String
 *  - flagMessage : Détermine un message à afficher au niveau des flags dépliés.
 *      enableDisplayFlags doit être à true.
 *      N'apparait pas comme icône simple.
 *      Par défaut vide : pas de message.
 *      String
 *  - flagTargetObject : Détermine un objet 'cible' (ou pas) à afficher au niveau des flags dépliés.
 *      enableDisplayFlags doit être à true.
 *      N'apparait pas comme icône simple. L'objet est affiché sous forme tiny.
 *      Par défaut vide : pas d'objet.
 *      String (hex)
 *  - status : Détermine le status de l'objet.
 *      Par défaut vide : pas de status.
 *      String
 *  - displaySize : Détermine la taille de l'affichage de l'élément complet.
 *      Tailles disponibles :
 *      - tiny : très petite taille correspondant à un carré de base de 16 pixels de large.
 *          Certains éléments ne sont pas affichés.
 *      - small : petite taille correspondant à un carré de base de 32 pixels de large.
 *      - medium : taille moyenne correspondant à un carré de base de 64 pixels de large par défaut.
 *      - large : grande taille correspondant à un carré de base de 128 pixels de large par défaut.
 *      - full : très grande taille correspondant à un carré de base de 256 pixels de large par défaut.
 *      Par défaut medium : taille moyenne.
 *      String
 *  - displayRatio : Détermine la forme de l'affichage par son ratio dans la mesure du possible si pas d'affichage du contenu de l'objet.
 *      Ratios disponibles :
 *      - square : forme carrée de 2x2 displaySize.
 *      - short : forme plate courte de 6x1 displaySize.
 *      - long : forme plate longue de toute largeure disponible.
 *      Par défaut short : forme plate courte.
 *      String
 *  - selfHookList : Détermine la liste des point d'encrage à utiliser pour les actions sur l'objet utilisé.
 *      Par défaut vide : est préparé en fonction de selfHookName.
 *      Array
 *  - selfHookName : Détermine le nom du point d'encrage à utiliser pour les actions sur l'objet utilisé.
 *      Par défaut vide : est préparé en fonction du type d'objet :
 *      - objet : selfMenuObject
 *      - entité : selfMenuEntity
 *      - groupe : selfMenuGroup
 *      - conversation : selfMenuConversation
 *      String
 *  - typeHookName : Détermine le nom du point d'encrage à utiliser pour les actions sur l'objet par rapport à son type.
 *      Par défaut vide : est préparé en fonction du type d'objet :
 *      - objet : typeMenuObject
 *      - entité : typeMenuEntity
 *      - groupe : typeMenuGroup
 *      - conversation : typeMenuConversation
 *      String
 *  Exemple de table de paramètres avec les valeurs par défaut :
 *  $param = array(
 *  'enableDisplayColor' => true,
 *  'enableDisplayIcon' => true,
 *  'enableDisplayIconApp' => false,
 *  'enableDisplayRefs' => false,
 *  'enableDisplayName' => true,
 *  'enableDisplayID' => false,
 *  'enableDisplayFlags' => false,
 *  'enableDisplayFlagEmotions' => true,
 *  'enableDisplayFlagProtection' => false,
 *  'enableDisplayFlagObfuscate' => false,
 *  'enableDisplayFlagUnlocked' => false,
 *  'enableDisplayFlagActivated' => false,
 *  'enableDisplayFlagState' => false,
 *  'enableDisplayStatus' => false,
 *  'enableDisplayContent' => false,
 *  'enableDisplayLink2Object' => true,
 *  'enableDisplayObjectActions' => true,
 *  'enableDisplayLink2Refs' => true,
 *  'enableDisplaySelfHook' => true,
 *  'enableDisplayTypeHook' => true,
 *  'enableDisplayJS' => true,
 *  'social' => '',
 *  'objectType' => null,
 *  'objectName' => null,
 *  'objectAppShortName' => null,
 *  'objectIcon' => null,
 *  'objectRefs' => array(),
 *  'link2Object' => '',
 *  'flagProtection' => false,
 *  'flagProtectionIcon' => '',
 *  'flagProtectionText' => '',
 *  'flagProtectionLink' => '',
 *  'flagObfuscate' => false,
 *  'flagObfuscateIcon' => '',
 *  'flagObfuscateText' => '',
 *  'flagObfuscateLink' => '',
 *  'flagUnlocked' => false,
 *  'flagUnlockedIcon' => '',
 *  'flagUnlockedText' => '',
 *  'flagUnlockedLink' => '',
 *  'flagActivated' => false,
 *  'flagActivatedDesc' => '',
 *  'flagState' => '',
 *  'flagStateDesc' => '',
 *  'flagMessage' => '',
 *  'flagTargetObject' => '',
 *  'status' => '',
 *  'displaySize' => 'medium',
 *  'displayRatio' => 'short',
 *  'selfHookList' => array(),
 *  'selfHookName' => '',
 *  'typeHookName' => '',
 *  );
 *      ---
 * Example:
 *  FIXME
 *      ---
 * Usage:
 *  FIXME
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class DisplayObject extends DisplayItemSizeable implements DisplayInterface
{
    private $_nid = null;
    private $_displayColor = true;
    private $_displayIconApp = false;
    private $_displayRefs = false;
    private $_displayName = true;
    private $_displayNID = false;
    private $_displayFlags = false;
    private $_displayFlagEmotions = false;
    private $_displayFlagProtection = false;
    private $_displayFlagObfuscate = false;
    private $_displayFlagUnlocked = false;
    private $_displayFlagActivated = false;
    private $_displayFlagState = false;
    private $_displayStatus = false;
    private $_displayContent = false;
    private $_displayObjectActions = true;
    private $_displayLink2Object = true;
    private $_displayLink2Refs = true;
    private $_displayJS = true;
    private $_displaySelfHook = true;
    private $_displayTypeHook = true;
    private $_social = '';
    private $_type = '';
    private $_name = '';
    private $_appShortname = '';
    private $_flagProtection = false;
    private $_flagProtectionIcon = null;
    private $_flagProtectionText = ':::display:object:flag:unprotected';
    private $_flagProtectionLink = '';
    private $_flagObfuscate = false;
    private $_flagObfuscateIcon = null;
    private $_flagObfuscateText = ':::display:object:flag:unobfuscated';
    private $_flagObfuscateLink = '';
    private $_flagUnlocked = false;
    private $_flagUnlockedIcon = null;
    private $_flagUnlockedText = ':::display:object:flag:locked';
    private $_flagUnlockedLink = '';
    private $_flagActivated = false;
    private $_flagActivatedText = ':::display:object:flag:unactivated';
    private $_flagState = '';
    private $_flagStateIcon = null;
    private $_flagStateText = '';
    private $_flagMessage = '';
    private $_flagTargetObject = null;
    private $_selfHookName = '';
    private $_typeHookName = '';
    private $_selfHookList = array();

    protected function _init(): void
    {
        $this->setSize(self::SIZE_MEDIUM);
        $this->setRatio(self::RATIO_SHORT);
        $this->setSocial('');
    }

    public function getHTML(): string
    {
        if ($this->_nid === null)
            return '';

        $result = '';

        $this->_solveConflicts();
        
        // Prépare les contenus.
        $objectColor = $this->_nid->getPrimaryColor();
        $ObjectActionsID = '0';
        if ($this->_displayObjectActions
            && $this->_displayJS
        )
            $ObjectActionsID = bin2hex($this->_nebuleInstance->getCryptoInstance()->getRandom(8, Crypto::RANDOM_PSEUDO));
        $contentDisplayColor = '';
        if ($this->_displayColor) {
            $contentDisplayColor = '<img title="' . $this->_name;
            $contentDisplayColor .= '" style="background:#' . $objectColor;
            $contentDisplayColor .= ';" alt="[C]" src="o/' . displays::DEFAULT_ICON_ALPHA_COLOR . '" ';
            if ($this->_displayObjectActions
                && $this->_displayJS
            )
                $contentDisplayColor .= "onclick=\"display_menu('objectTitleMenu-" . $ObjectActionsID . "');\" ";
            $contentDisplayColor .= '/>';
        }

        $contentDisplayIcon = '';
        if ($this->_icon !== null) {
            $contentDisplayIcon = '<img title="' . $this->_name;
            $contentDisplayIcon .= '" style="background:#' . $objectColor;
            $contentDisplayIcon .= ';" alt="[I]" src="' . $this->_getObjectIconHTML($this->_nid, $this->_icon) . '" ';
            if ($this->_displayObjectActions
                && $this->_displayJS
            )
                $contentDisplayIcon .= "onclick=\"display_menu('objectTitleMenu-" . $ObjectActionsID . "');\" ";
            $contentDisplayIcon .= '/>';
        }

        if ($this->_displayIconApp) {
            $contentDisplayIcon = '<div class="objectTitleIconsApp" style="background:#' . $objectColor . ';">';
            $contentDisplayIcon .= '<div><span class="objectTitleIconsAppShortname">' . $this->_appShortname . '</span><br /><span class="objectTitleIconsAppTitle">' . $this->_name . '</span></div>';
            $contentDisplayIcon .= '</div>';
        }

        $titleLinkOpenImg = '';
        $titleLinkOpenName = '';
        $titleLinkCloseImg = '';
        $titleLinkCloseName = '';
        if ($this->_displayLink2Object) {
            if ($this->_displayObjectActions
                && $this->_displayJS
            ) {
                if (isset($param['link2Object'])
                    && $param['link2Object'] != null
                )
                    $titleLinkOpenName = '<a href="' . $param['link2Object'] . '">';
                else
                    $titleLinkOpenName = '<a href="' . $this->_displayInstance->prepareDefaultObjectOrGroupOrEntityHtlink($this->_nid) . '">';
                $titleLinkCloseName = '</a>';
            } else {
                if (isset($param['link2Object'])
                    && $param['link2Object'] != null
                )
                    $titleLinkOpenImg = '<a href="' . $param['link2Object'] . '">' . "\n";
                else
                    $titleLinkOpenImg = '<a href="' . $this->_displayInstance->prepareDefaultObjectOrGroupOrEntityHtlink($this->_nid) . '">';
                $titleLinkOpenName = $titleLinkOpenImg;
                $titleLinkCloseImg = '</a>';
                $titleLinkCloseName = $titleLinkCloseImg;
            }
        }

        $status = '';
        if ($this->_displayStatus
            && isset($param['status'])
        ) {
            $status = trim(filter_var($param['status'], FILTER_SANITIZE_STRING));
            if ($status == '')
                $status = $this->_traductionInstance->getTraduction($this->_type);
            if ($status == '')
                $this->_displayStatus = false;
        }

        // Prépare le menu si besoin.
        $divTitleMenuOpen = '';
        $divTitleMenuClose = '';
        $divTitleMenuTitleOpen = '';
        $divTitleMenuTitleClose = '';
        $divTitleMenuIconsOpen = '';
        $divTitleMenuIconsClose = '';
        $divTitleMenuContentOpen = '';
        $divTitleMenuContentClose = '';
        $divTitleMenuActionsOpen = '';
        $divTitleMenuActionsClose = '';
        $menuContent = '';
        $menuActions = '';
        if ($this->_displayLink2Object
            && $this->_displayObjectActions
        ) {
            $menuContent = '   <div class="objectMenuContentMsg objectMenuContentMsgID">ID:';
            $menuContent .= $this->_nid->getID();
            $menuContent .= '</div>' . "\n";
            if ($this->_displayFlags) {
                if ($this->_displayFlagState) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($this->_flagState == 'e')
                        $menuContent .= 'Error';
                    elseif ($this->_flagState == 'w')
                        $menuContent .= 'Warn';
                    elseif ($this->_flagState == 'n')
                        $menuContent .= 'Error';
                    else
                        $menuContent .= 'OK';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        false,
                        $this->_flagStateIcon->getID(),
                        $this->_flagStateText,
                        '');
                    $menuContent .= $this->_traductionInstance->getTraduction($this->_flagStateText);
                    $menuContent .= '</div>' . "\n";
                }
                if ($this->_displayFlagProtection) {
                    if ($this->_flagProtectionLink != '')
                        $menuContent .= '<a href="' . $this->_flagProtectionLink . '">';
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($this->_flagProtection)
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        $this->_flagProtection,
                        $this->_flagProtectionIcon->getID(),
                        $this->_flagProtectionText,
                        $this->_flagProtectionText);
                    $menuContent .= $this->_traductionInstance->getTraduction($this->_flagProtectionText);
                    $menuContent .= '</div>' . "\n";
                    if ($this->_flagProtectionLink != '')
                        $menuContent .= '</a>';
                }
                if ($this->_displayFlagObfuscate) {
                    if ($this->_flagObfuscateLink != '')
                        $menuContent .= '<a href="' . $this->_flagObfuscateLink . '">';
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($this->_flagObfuscate)
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        $this->_flagObfuscate,
                        $this->_flagObfuscateIcon->getID(),
                        $this->_flagObfuscateText,
                        $this->_flagObfuscateText);
                    $menuContent .= $this->_traductionInstance->getTraduction($this->_flagObfuscateText);
                    $menuContent .= '</div>' . "\n";
                    if ($this->_flagObfuscateLink != '')
                        $menuContent .= '</a>';
                }
                if ($this->_displayFlagUnlocked) {
                    if ($this->_flagUnlockedLink != '')
                        $menuContent .= '<a href="' . $this->_flagUnlockedLink . '">';
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($this->_flagUnlocked)
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        $this->_flagUnlocked,
                        $this->_flagUnlockedIcon->getID(),
                        $this->_flagUnlockedText,
                        $this->_flagUnlockedText);
                    $menuContent .= $this->_traductionInstance->getTraduction($this->_flagUnlockedText);
                    $menuContent .= '</div>' . "\n";
                    if ($this->_flagUnlockedLink != '')
                        $menuContent .= '</a>';
                }
                if ($this->_displayFlagActivated) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($this->_flagActivated)
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        $this->_flagActivated,
                        Displays::DEFAULT_ICON_LL,
                        $this->_flagActivatedText,
                        $this->_flagActivatedText);
                    if ($this->_flagActivated)
                        $menuContent .= $this->_traductionInstance->getTraduction(':::display:object:flag:activated');
                    else
                        $menuContent .= $this->_traductionInstance->getTraduction(':::display:object:flag:unactivated');
                    $menuContent .= '</div>' . "\n";
                }
                if ($this->_flagMessage != '') {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsgInfo">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        false,
                        Displays::DEFAULT_ICON_IINFO,
                        '',
                        '-');
                    $menuContent .= $this->_flagMessage;
                    $menuContent .= '</div>' . "\n";
                }
                if ($this->_flagTargetObject != null) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsgtargetObject">';
                    $paramTiny = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => false,
                        'enableDisplayJS' => false,
                        'displaySize' => 'tiny',
                        'displayRatio' => 'short',
                    );
                    // ATTENTION à une possible boucle infinie !
                    $menuContent .= $this->_displayInstance->getDisplayObject($this->_flagTargetObject, $paramTiny);
                    unset($paramTiny);
                    $menuContent .= '</div>' . "\n";
                }
                if ($this->_displayFlagEmotions
                    && $this->_displayJS
                ) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsgEmotions">';
                    $menuContent .= $this->_getObjectFlagEmotionsHTML($this->_nid, true);
                    $menuContent .= '</div>' . "\n";
                }
            }

            if ($this->_displayJS) {
                $divTitleMenuOpen = '  <div class="objectTitleMenuContentLayout" id="objectTitleMenu-' . $ObjectActionsID . '" '
                    . "onclick=\"display_hide('objectTitleMenu-" . $ObjectActionsID . "');\" >\n";
                $divTitleMenuOpen .= '   <div class="objectTitleMenuContent">' . "\n";
                $divTitleMenuTitleOpen = '    <div class="objectTitleMedium objectTitleMediumLong">' . "\n";
                $divTitleMenuIconsOpen = '    <div class="objectTitleIcons">' . "\n";
                $titleMenuIcons = $contentDisplayColor . $contentDisplayIcon . "\n";
                $divTitleMenuIconsClose = '    </div>' . "\n";
                $divTitleMenuTitleClose = '    </div>' . "\n";
                $divTitleMenuContentOpen = '    <div class="objectMenuContent">' . "\n";
                $divTitleMenuContentClose = '    </div>' . "\n";
                $menuActions = $this->_displayInstance->getDisplayObjectHookList(
                    $this->_selfHookName,
                    $this->_typeHookName,
                    $this->_nid,
                    true,
                    $this->_sizeCSS . 'Long',
                    $this->_selfHookList);
                if ($menuActions != '') {
                    $divTitleMenuActionsOpen = '<div class="objectMenuContentActions objectMenuContentActions' . $this->_sizeCSS . 'Long">' . "\n";
                    $divTitleMenuActionsClose = ' <div class="objectMenuContentAction-close"></div>' . "\n</div>\n";
                }
                $divTitleMenuClose = '   </div></div>' . "\n";
            } else {
                $divMenuContentOpen = '  <div class="objectMenuContent objectDisplay' . $this->_sizeCSS . ' objectDisplay' . $this->_sizeCSS . $this->_ratioCSS . '">' . "\n";
                $divMenuContentClose = '  </div>' . "\n";
                $menuActions = $this->_displayInstance->getDisplayObjectHookList(
                    $this->_selfHookName,
                    $this->_typeHookName,
                    $this->_nid,
                    false,
                    $this->_sizeCSS . $this->_ratioCSS,
                    $this->_selfHookList);
                if ($menuActions != '') {
                    $divTitleMenuActionsOpen = '<div class="objectMenuContentActions objectMenuContentActions' . $this->_sizeCSS . $this->_ratioCSS . '">' . "\n";
                    $divTitleMenuActionsClose = ' <div class="objectMenuContentAction-close"></div>' . "\n</div>\n";
                }
            }
        }

        // Assemble les contenus.
        $divDisplayOpen = '';
        $divDisplayClose = '';
        $divTitleOpen = '';
        $divTitleClose = '';
        $titleContent = '';
        $divTitleIconsOpen = '';
        $divTitleIconsClose = '';
        $titleIconsContent = '';
        $divTitleTextOpen = '';
        $divTitleTextClose = '';
        $titleTextContent = '';
        $divTitleRefsOpen = '';
        $divTitleRefsClose = '';
        $titleRefsContent = '';
        $divTitleNameOpen = '';
        $divTitleNameClose = '';
        $titleNameContent = '';
        $divTitleIdOpen = '';
        $divTitleIdClose = '';
        $titleIdContent = '';
        $divTitleFlagsOpen = '';
        $divTitleFlagsClose = '';
        $titleFlagsContent = '';
        $divTitleStatusOpen = '';
        $divTitleStatusClose = '';
        $titleStatusContent = '';
        $divObjectOpen = '';
        $divObjectClose = '';
        $objectContent = '';
        if ($this->_sizeCSS == 'tiny')
            $result = $titleLinkOpenName . '<span style="font-size:1em" class="objectTitleIconsInline">' . $contentDisplayColor . $contentDisplayIcon . '</span>' . $this->_name . $titleLinkCloseName;
        else {
            $divDisplayOpen = '<div class="layoutObject">' . "\n";
            $divDisplayClose = '</div>' . "\n";
            $divTitleOpen = ' <div class="objectTitle objectDisplay' . $this->_sizeCSS . ' objectTitle' . $this->_sizeCSS . ' objectDisplay' . $this->_sizeCSS . $this->_ratioCSS . '">' . "\n";
            $divTitleClose = ' </div>' . "\n";
            $divTitleIconsOpen = '  <div class="objectTitleIcons">';
            $divTitleIconsClose = '</div>' . "\n";
            if ($this->_displayColor
                || $this->_icon !== null
                || $this->_displayIconApp
            )
                $titleIconsContent = $contentDisplayColor . $contentDisplayIcon;
            if ($this->_displayName) {
                $padding = 0;
                if ($this->_displayColor)
                    $padding += 1;
                if ($this->_icon !== null)
                    $padding += 1;
                if ($this->_displayIconApp)
                    $padding += 1;
                $divTitleTextOpen = '  <div class="objectTitleText objectTitle' . $this->_sizeCSS . 'Text objectTitleText' . $padding . '">' . "\n";
                $divTitleTextClose = '  </div>' . "\n";
                $divTitleRefsOpen = '   <div class="objectTitleRefs objectTitle' . $this->_sizeCSS . 'Refs">';
                $divTitleRefsClose = '</div>' . "\n";
                $divTitleNameOpen = '   <div class="objectTitleName objectTitle' . $this->_sizeCSS . 'Name">';
                $divTitleNameClose = '</div>' . "\n";
                $divTitleFlagsOpen = '   <div class="objectTitleFlags objectTitle' . $this->_sizeCSS . 'Flags">' . "\n";
                $divTitleFlagsClose = '   </div>' . "\n";
                $divTitleStatusOpen = '    <div class="objectTitleStatus">';
                $divTitleStatusClose = '</div>' . "\n";
                if ($this->_displayRefs && sizeof($param['objectRefs']) > 0 && $param['objectRefs'] !== null)
                    $titleRefsContent = $this->_getObjectRefsHTML($param['objectRefs']);
                if ($this->_displayNID) {
                    $divTitleIdOpen = '    <div class="objectTitleID">';
                    $divTitleIdClose = '</div>' . "\n";
                    $titleIdContent = $this->_nid->getID();
                }
                $titleNameContent = $this->_name;
                if ($this->_displayFlags) {
                    if ($this->_displayFlagState) {
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            false,
                            $this->_flagStateIcon->getID(),
                            $this->_flagStateText,
                            '');
                    }
                    if ($this->_displayFlagProtection) {
                        if ($this->_flagProtectionLink != '') {
                            $titleFlagsContent .= '<a href="' . $this->_flagProtectionLink . '">';
                        }
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            $this->_flagProtection,
                            $this->_flagProtectionIcon->getID(),
                            $this->_flagProtectionText,
                            $this->_flagProtectionText);
                        if ($this->_flagProtectionLink != '')
                            $titleFlagsContent .= '</a>';
                    }
                    if ($this->_displayFlagObfuscate) {
                        if ($this->_flagObfuscateLink != '')
                            $titleFlagsContent .= '<a href="' . $this->_flagObfuscateLink . '">';
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            $this->_flagObfuscate,
                            $this->_flagObfuscateIcon->getID(),
                            $this->_flagObfuscateText,
                            $this->_flagObfuscateText);
                        if ($this->_flagObfuscateLink != '')
                            $titleFlagsContent .= '</a>';
                    }
                    if ($this->_displayFlagUnlocked) {
                        if ($this->_flagUnlockedLink != '')
                            $titleFlagsContent .= '<a href="' . $this->_flagUnlockedLink . '">';
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            $this->_flagUnlocked,
                            $this->_flagUnlockedIcon->getID(),
                            $this->_flagUnlockedText,
                            $this->_flagUnlockedText);
                        if ($this->_flagUnlockedLink != '')
                            $titleFlagsContent .= '</a>';
                    }
                    if ($this->_displayFlagActivated) {
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            $this->_flagActivated,
                            Displays::DEFAULT_ICON_LL,
                            ':::display:object:flag:unactivated',
                            ':::display:object:flag:activated');
                    }
                    if ($this->_displayFlagEmotions)
                        $titleFlagsContent .= $this->_getObjectFlagEmotionsHTML($this->_nid, false);
                }
                if ($this->_displayStatus)
                    $titleStatusContent = $status;
            }
            $titleContent = $titleLinkOpenImg . "\n" . $divTitleIconsOpen . $titleIconsContent . $divTitleIconsClose . $titleLinkCloseImg . "\n";
            $titleContent .= $divTitleTextOpen;
            $titleContent .= $divTitleRefsOpen . $titleRefsContent . $divTitleRefsClose;
            $titleContent .= $divTitleNameOpen . $titleLinkOpenName . $titleNameContent . $titleLinkCloseName . $divTitleNameClose;
            $titleContent .= $divTitleIdOpen . $titleIdContent . $divTitleIdClose;
            $titleContent .= $divTitleFlagsOpen . $titleFlagsContent;
            $titleContent .= $divTitleStatusOpen . $titleStatusContent . $divTitleStatusClose;
            $titleContent .= $divTitleFlagsClose;
            $titleContent .= $divTitleTextClose;
            if ($this->_displayJS
                && $this->_displayObjectActions
            ) {
                $titleContent .= $divTitleMenuOpen;
                $titleContent .= $divTitleMenuTitleOpen;
                $titleContent .= $divTitleMenuIconsOpen . $titleMenuIcons . $divTitleMenuIconsClose;
                $titleContent .= $divTitleTextOpen;
                $titleContent .= $divTitleRefsOpen . $titleRefsContent . $divTitleRefsClose;
                $titleContent .= $divTitleNameOpen . $titleLinkOpenName . $titleNameContent . $titleLinkCloseName . $divTitleNameClose;
                $titleContent .= $divTitleIdOpen . $titleIdContent . $divTitleIdClose;
                $titleContent .= $divTitleFlagsOpen . $titleFlagsContent;
                $titleContent .= $divTitleStatusOpen . $titleStatusContent . $divTitleStatusClose;
                $titleContent .= $divTitleFlagsClose;
                $titleContent .= $divTitleTextClose;
                $titleContent .= $divTitleMenuTitleClose;
                $titleContent .= $divTitleMenuContentOpen . $menuContent . $divTitleMenuContentClose;
                $titleContent .= $divTitleMenuActionsOpen . $menuActions . $divTitleMenuActionsClose;
                $titleContent .= $divTitleMenuClose;
            }

            if ($this->_displayContent)
                $objectContent = $this->_displayInstance->getDisplayObjectContent($this->_nid, $this->_sizeCSS, $this->_ratioCSS);

            // Prépare le résultat à afficher.
            $result = $divDisplayOpen;
            $result .= $divTitleOpen . $titleContent . $divTitleClose;
            if (!$this->_displayJS
                && $this->_displayObjectActions
            ) {
                $result .= $divMenuContentOpen . $menuContent . $divMenuContentClose;
                $result .= $divTitleMenuActionsOpen . $menuActions . $divTitleMenuActionsClose;
            }
            $result .= $divObjectOpen . $objectContent . $divObjectClose;
            $result .= $divDisplayClose;
        }

        return $result;
    }

    private function _solveConflicts():void
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
            $this->_displayFlagProtection = false;

        if (!$this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
            $this->_displayFlagObfuscate = false;

        if (!$this->_configurationInstance->getOptionAsBoolean('permitJavaScript'))
            $this->_displayJS = false;

        if ($this->_sizeCSS == 'Tiny') {
            $this->_displayLink2Refs = false;
            $this->_displayObjectActions = false;
            $this->_displayRefs = false;
            $this->_displayFlags = false;
            $this->_displayStatus = false;
            $this->_displayContent = false;
        }

        if ($this->_sizeCSS == 'Small') {
            $this->_displayFlags = false;
            $this->_displayStatus = false;
        }

        if ($this->_displayContent
            && $this->_ratioCSS == 'Square'
        ) {
            $this->_ratioCSS = 'Short';
        }

        if ($this->_displayIconApp) {
            $this->_displayColor = false;
            $this->_icon = null;
        }

        if (!$this->_displayColor
            && $this->_icon === null
            && !$this->_displayIconApp
        )
            $this->_displayObjectActions = false;

        if (!$this->_displayName) {
            $this->_displayRefs = false;
            $this->_displayFlags = false;
            $this->_displayStatus = false;
        }

        if (!$this->_configurationInstance->getOptionAsBoolean('displayEmotions'))
            $this->_displayFlagEmotions = false;

        if ($this->_sizeCSS == self::SIZE_TINY
            || $this->_sizeCSS == self::SIZE_SMALL
            || ($this->_sizeCSS == self::SIZE_MEDIUM && $this->_displayFlags)
            || !$this->_displayName
        )
            $this->_displayNID = false;
    }

    /**
     * Pour la fonction getDisplayObject().
     * Prépare l'icône de l'objet.
     * Si une icône est imposée, elle est utilisée.
     * Sinon fait une recherche par référence en fonction du type de l'objet.
     * Une mise à jour éventuelle de l'icône est recherchée.
     * Si l'objet de l'icône est présent, génère un chemin direct pour améliorer les performances.
     *
     * @param Node      $object
     * @param Node|null $icon
     * @return string
     */
    private function _getObjectIconHTML(Node $object, ?Node $icon = null): string
    {
        if ($icon === null
            || !$icon->checkPresent()
        ) {
            if (is_a($object, 'Nebule\Library\Entity'))
                $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newEntity(Displays::REFERENCE_ICON_ENTITY));
            elseif (is_a($object, 'Nebule\Library\Conversation'))
                $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newConversation(Displays::REFERENCE_ICON_CONVERSATION));
            elseif (is_a($object, 'Nebule\Library\Group'))
                $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newGroup(Displays::REFERENCE_ICON_GROUP));
            elseif (is_a($object, 'Nebule\Library\Wallet'))
                $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            elseif (is_a($object, 'Nebule\Library\Transaction'))
                $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            elseif (is_a($object, 'Nebule\Library\Token'))
                $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            elseif (is_a($object, 'Nebule\Library\TokenPool'))
                $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            elseif (is_a($object, 'Nebule\Library\Currency'))
                $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            else
                $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT));
            $icon = $this->_nebuleInstance->newObject($oid);
        }

        $updateIcon = $this->_displayInstance->getImageUpdate($icon); // FIXME TODO ERROR
        //$updateIcon = '94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0.sha2.256'; // FIXME

        if ($this->_nebuleInstance->getIoInstance()->checkObjectPresent($updateIcon))
            return nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $updateIcon;
        return '?' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '=' . $updateIcon;
    }

    /**
     * Pour les fonctions getDisplayObject() et getDisplayMessage().
     * Prépare la liste des références (signataires).
     *
     * Si l'entrée est un texte, retourne le texte (à afficher).
     *
     * @param array $list
     * @return string
     */
    private function _getObjectRefsHTML(array $list): string
    {
        // FIXME add $this->_displayLink2Refs support.
        $result = '';

        if (sizeof($list) == 0)
            return '';

        $size = sizeof($list);
        $count = 0;

        foreach ($list as $object) {
            $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
            $htLink = $this->_displayInstance->prepareDefaultObjectOrGroupOrEntityHtlink($object);
            $color = $this->_displayInstance->prepareObjectColor($object);
            $icon = '';
            if ($size < 11)
                $icon = $this->_displayInstance->prepareObjectFace($object);
            $name = '';
            if ($size < 3)
                $name = $this->_displayInstance->truncateName($object->getFullName('all'));
            $result .= $this->_displayInstance->convertHypertextLink($color . $icon . $name, $htLink);
            if ($size < 11)
                $result .= ' ';

            $count++;
            if ($count > 30) {
                $result .= '+';
                break;
            }
        }

        return $result;
    }

    /**
     * Pour la fonction getDisplayObject().
     * Prépare les icônes des indicateurs (flags).
     *
     * @param boolean $on
     * @param string  $image
     * @param string  $descOff
     * @param string  $descOn
     * @return string
     */
    private function _getObjectFlagHTML(bool $on, string $image, string $descOff, string $descOn): string
    {
        $result = '';

        $image = $this->_displayInstance->prepareIcon($image);
        if ($on)
            $desc = $this->_traductionInstance->getTraduction($descOn);
        else
            $desc = $this->_traductionInstance->getTraduction($descOff);
        $result .= '<img title="' . $desc . '" ';
        if ($on)
            $result .= 'class="objectFlagOn" ';
        $result .= 'alt="[C]" src="' . $image . '" />';

        return $result;
    }

    /**
     * Pour les fonctions getDisplayObject() et getDisplayMessage().
     * Prépare les icônes des émotions avec ou sans les compteurs ($counts).
     *
     * @param Node    $object
     * @param boolean $counts
     * @return string
     */
    private function _getObjectFlagEmotionsHTML(Node $object, bool $counts = false): string
    {
        // Vérifie si les émotions doivent être affichées.
        if (!$this->_configurationInstance->getOptionUntyped('displayEmotions'))
            return '';
        $result = '';

        $listEmotions = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET,
        );
        $listEmotions0 = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET0,
        );
        $listEmotions1 = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET1,
        );

        foreach ($listEmotions as $emotion) {
            // Génère la base du lien html pour revenir au bon endroit en toute situation.
            $httpLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntityID()
                . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroup()
                . '&' . References::COMMAND_SELECT_CONVERSATION . '=' . $this->_nebuleInstance->getCurrentConversation();

            // Préparation du lien.
            $source = $object->getID();
            $target = $this->_nebuleInstance->getCryptoInstance()->hash($emotion);
            $meta = $this->_nebuleInstance->getCurrentEntity();

            // Détermine si l'émotion a été marqué par l'entité en cours.
            if ($object->getMarkEmotion($emotion, 'myself')) {
                $action = 'x';
                $rid = $this->_nebuleInstance->newObject($listEmotions1[$emotion]);
            } else {
                $action = 'f';
                $rid = $this->_nebuleInstance->newObject($listEmotions0[$emotion]);
            }
            $link = $action . '_' . $source . '_' . $target . '_' . $meta;
            $httpLink .= '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=' . $link . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            $icon = $this->_displayInstance->convertReferenceImage($rid, $emotion, 'iconInlineDisplay');

            // Si connecté, l'icône est active.
            if ($this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            )
                $result .= $this->_displayInstance->convertHypertextLink($icon, $httpLink);
            else
                $result .= $icon;

            // Détermine le nombre d'entités qui ont marqué cette émotion.
            if ($counts) {
                $count = $object->getMarkEmotionSize($emotion, 'all');
                if ($count > 0)
                    $result .= $count . ' ';
            }
        }

        return $result;
    }

    public function setNID(?Node $nid): void
    {
        if ($nid === null)
            $this->_nid = null;
        elseif ($nid->getID() != '0' && is_a($nid, 'Nebule\Library\Node') && $nid->checkPresent()) {
            $this->_nid = $nid;
            $this->setType('');
            $this->setName('');
        }
    }

    public function setEnableColor(bool $enable): void
    {
        $this->_displayColor = $enable;
    }

    public function setEnableIconApp(bool $enable): void
    {
        $this->_displayIconApp = $enable;
    }

    public function setEnableRefs(bool $enable): void
    {
        $this->_displayRefs = $enable;
    }

    public function setEnableName(bool $enable): void
    {
        $this->_displayName = $enable;
    }

    public function setEnableNID(bool $enable): void
    {
        $this->_displayNID = $enable;
    }

    public function setEnableFlags(bool $enable): void
    {
        $this->_displayFlags = $enable;
    }

    public function setEnableFlagEmotions(bool $enable): void
    {
        $this->_displayFlagEmotions = $enable;
    }

    public function setEnableStatus(bool $enable): void
    {
        $this->_displayStatus = $enable;
    }

    public function setEnableContent(bool $enable): void
    {
        $this->_displayContent = $enable;
    }

    public function setEnableObjectActions(bool $enable): void
    {
        $this->_displayObjectActions = $enable;
    }

    public function setEnableLink2Object(bool $enable): void
    {
        $this->_displayLink2Object = $enable;
    }

    public function setEnableLink2Refs(bool $enable): void
    {
        $this->_displayLink2Refs = $enable;
    }

    public function setEnableJS(bool $enable): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitJavaScript'))
            $this->_displayJS = $enable;
    }

    public function setSocial(string $social): void
    {
        if ($social == '')
        {
            $this->_social = 'all';
            return;
        }
        $socialList = $this->_nebuleInstance->getSocialInstance()->getSocialNames();
        foreach ($socialList as $s) {
            if ($social == $s) {
                $this->_social = $social;
                break;
            }
        }
        if ($this->_social == '')
            $this->_social = 'all';
    }
    
    public function setType(string $type): void
    {
        if ($type == '')
            $this->_type = $this->_nid->getType($this->_social);
        else
            $this->_type = $type;
    }
    
    public function setName(string $name): void
    {
        if ($name != '')
            $this->_name = trim(filter_var($name, FILTER_SANITIZE_STRING));
        else {
            if ($this->_displayIconApp)
                $this->_name = $this->_nid->getName($this->_social);
            else
                $this->_name = $this->_nid->getFullName($this->_social);
        }
    }

    public function setAppShortName(string $name): void
    {
        if ($name != '')
            $this->_appShortname = trim(filter_var($name, FILTER_SANITIZE_STRING));
        else {
            if ($this->_displayIconApp)
                $this->_appShortname = $this->_nid->getSurname($this->_social);
            else
                $this->_appShortname = '';
        }
    }

    public function setEnableFlagProtection(bool $enable): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
            $this->_displayFlagProtection = $enable;
        
        if ($enable) {
            $this->_flagProtectionIcon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_LK);
        }
    }

    public function setFlagProtection(bool $enable): void
    {
        $this->_flagProtection = $enable;
        $this->_setFlagProtection();
    }

    private function _setFlagProtection(): void
    {
        if ($this->_flagProtection)
            $this->_flagProtectionText = ':::display:object:flag:protected';
        else
            $this->_flagProtectionText = ':::display:object:flag:unprotected';
    }

    public function setFlagProtectionIcon(?Node $oid): void
    {
        if ($oid->getID() != '0' && is_a($oid, 'Nebule\Library\Node') && $oid->checkPresent())
            $this->_flagProtectionIcon = $oid;
    }

    public function setFlagProtectionText(string $text): void
    {
        $this->_flagProtectionText = trim(filter_var($text, FILTER_SANITIZE_STRING));
    }

    public function setFlagProtectionLink(string $link): void
    {
        $this->_flagProtectionLink = trim(filter_var($link, FILTER_SANITIZE_URL));
    }

    public function setEnableFlagObfuscate(bool $enable): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
            $this->_displayFlagObfuscate = $enable;

        if ($enable) {
            $this->_flagObfuscateIcon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_LC);
        }
    }

    public function setFlagObfuscate(bool $enable): void
    {
        $this->_flagObfuscate = $enable;
        $this->_setFlagObfuscate();
    }

    private function _setFlagObfuscate(): void
    {
        if ($this->_flagObfuscate)
            $this->_flagObfuscateText = ':::display:object:flag:obfuscated';
        else
            $this->_flagObfuscateText = ':::display:object:flag:unobfuscated';
    }

    public function setFlagObfuscateIcon(?Node $oid): void
    {
        if ($oid->getID() != '0' && is_a($oid, 'Nebule\Library\Node') && $oid->checkPresent())
            $this->_flagObfuscateIcon = $oid;
    }

    public function setFlagObfuscateText(string $text): void
    {
        $this->_flagObfuscateText = trim(filter_var($text, FILTER_SANITIZE_STRING));
    }

    public function setFlagObfuscateLink(string $link): void
    {
        $this->_flagObfuscateLink = trim(filter_var($link, FILTER_SANITIZE_URL));
    }

    public function setEnableFlagUnlocked(bool $enable): void
    {
        $this->_displayFlagUnlocked = $enable;

        if ($enable){
            $this->_flagUnlockedIcon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_KEY);
        }
    }

    public function setFlagUnlocked(bool $enable): void
    {
        $this->_flagUnlocked = $enable;
        $this->_setFlagUnlocked();
    }

    private function _setFlagUnlocked(): void
    {
        if ($this->_flagUnlocked)
            $this->_flagUnlockedText = ':::display:object:flag:locked';
        else
            $this->_flagUnlockedText = ':::display:object:flag:unlocked';
    }

    public function setFlagUnlockedIcon(?Node $oid): void
    {
        if ($oid->getID() != '0' && is_a($oid, 'Nebule\Library\Node') && $oid->checkPresent())
            $this->_flagUnlockedIcon = $oid;
    }

    public function setFlagUnlockedText(string $text): void
    {
        $this->_flagUnlockedText = trim(filter_var($text, FILTER_SANITIZE_STRING));
    }

    public function setFlagUnlockedLink(string $link): void
    {
        $this->_flagUnlockedLink = trim(filter_var($link, FILTER_SANITIZE_URL));
    }

    public function setEnableFlagActivated(bool $enable): void
    {
        $this->_displayFlagActivated = $enable;
    }

    public function setActivated(bool $enable): void
    {
        $this->_flagActivated = $enable;
        $this->_setActivated();
    }

    private function _setActivated(): void
    {
        if ($this->_flagActivated)
            $this->_flagActivatedText = ':::display:object:flag:activated';
        else
            $this->_flagActivatedText = ':::display:object:flag:unactivated';
    }
    
    public function setActivatedText(string $text): void
    {
        $this->_flagActivatedText = trim(filter_var($text, FILTER_SANITIZE_STRING));
    }

    public function setEnableFlagState(bool $enable): void
    {
        $this->_displayFlagState = $enable;
        $this->_setFlagState();
    }

    private function _setFlagState(): void
    {
        if ($this->_nid->getMarkDanger())
            $this->setFlagState('e');
        elseif ($this->_nid->getMarkWarning())
            $this->setFlagState('w');
        elseif ($this->_nid->checkPresent())
            $this->setFlagState('o');
        else
            $this->setFlagState('n');
    }

    public function setFlagState(string $state): void
    {
        if ($state == 'e') {
            $this->_flagState = 'e';
            $this->_flagStateIcon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IERR);
            $this->_flagStateText = ':::display:content:errorBan';
        } elseif ($state == 'w') {
            $this->_flagState = 'w';
            $this->_flagStateIcon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IWARN);
            $this->_flagStateText = ':::display:content:warningTaggedWarning';
        } elseif ($state == 'o') {
            $this->_flagState = 'o';
            $this->_flagStateIcon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IOK);
            $this->_flagStateText = ':::display:content:OK';
        } else {
            $this->_flagState = 'n';
            $this->_flagStateIcon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IERR);
            $this->_flagStateText = ':::display:content:errorNotAvailable';
        }
    }

    public function setFlagStateText(string $text): void
    {
        $this->_flagStateText = trim(filter_var($text, FILTER_SANITIZE_STRING));
    }

    public function setFlagMessage(string $text): void
    {
        $this->_flagMessage = trim(filter_var($text, FILTER_SANITIZE_STRING));
    }

    public function setFlagTargetObject(?Node $nid): void
    {
        if ($nid === null)
            $this->_flagTargetObject = null;
        elseif ($nid->getID() != '0' && is_a($nid, 'Nebule\Library\Node') && $nid->checkPresent())
            $this->_flagTargetObject = $nid;
    }

    public function setEnableSelfHook(bool $enable): void
    {
        $this->_displaySelfHook = $enable;
        if ($enable)
            $this->_setSelfHookName();
    }

    private function _setSelfHookName(): void
    {
        if (is_a($this->_nid, 'Nebule\Library\Entity'))
            $this->_selfHookName = 'selfMenuEntity';
        elseif (is_a($this->_nid, 'Nebule\Library\Conversation'))
            $this->_selfHookName = 'selfMenuConversation';
        elseif (is_a($this->_nid, 'Nebule\Library\Group'))
            $this->_selfHookName = 'selfMenuGroup';
        elseif (is_a($this->_nid, 'Nebule\Library\Transaction'))
            $this->_selfHookName = 'selfMenuTransaction';
        elseif (is_a($this->_nid, 'Nebule\Library\Wallet'))
            $this->_selfHookName = 'selfMenuWallet';
        elseif (is_a($this->_nid, 'Nebule\Library\Token'))
            $this->_selfHookName = 'selfMenuToken';
        elseif (is_a($this->_nid, 'Nebule\Library\TokenPool'))
            $this->_selfHookName = 'selfMenuTokenPool';
        elseif (is_a($this->_nid, 'Nebule\Library\Currency'))
            $this->_selfHookName = 'selfMenuCurrency';
        else
            $this->_selfHookName = 'selfMenuObject';
    }

    public function setSelfHook(string $name): void
    {
        $this->_selfHookName = trim(filter_var($name, FILTER_SANITIZE_STRING));
    }

    public function setEnableTypeHook(bool $enable): void
    {
        $this->_displayTypeHook = $enable;
        if ($enable)
            $this->_setTypeHookName();
    }

    private function _setTypeHookName(): void
    {
        if (is_a($this->_nid, 'Nebule\Library\Entity'))
            $this->_typeHookName = 'typeMenuEntity';
        elseif (is_a($this->_nid, 'Nebule\Library\Conversation'))
            $this->_typeHookName = 'typeMenuConversation';
        elseif (is_a($this->_nid, 'Nebule\Library\Group'))
            $this->_typeHookName = 'typeMenuGroup';
        elseif (is_a($this->_nid, 'Nebule\Library\Transaction'))
            $this->_typeHookName = 'typeMenuTransaction';
        elseif (is_a($this->_nid, 'Nebule\Library\Wallet'))
            $this->_typeHookName = 'typeMenuWallet';
        elseif (is_a($this->_nid, 'Nebule\Library\Token'))
            $this->_typeHookName = 'typeMenuToken';
        elseif (is_a($this->_nid, 'Nebule\Library\TokenPool'))
            $this->_typeHookName = 'typeMenuTokenPool';
        elseif (is_a($this->_nid, 'Nebule\Library\Currency'))
            $this->_typeHookName = 'typeMenuCurrency';
        else
            $this->_typeHookName = 'typeMenuObject';
    }

    public function setTypeHook(string $name): void
    {
        $this->_typeHookName = trim(filter_var($name, FILTER_SANITIZE_STRING));
    }
    
    public function setHookList(array $list)
    {
        $this->_selfHookList = $list;
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayObject(). */
            .layoutObject {
                margin: 5px 0 0 5px;
                border: 0;
                background: none;
                display: inline-block;
                vertical-align: top;
            }

            .objectDisplayTiny {
                font-size: 16px;
            }

            .objectDisplaySmall {
                font-size: 32px;
            }

            .objectDisplayMedium {
                font-size: 64px;
            }

            .objectDisplayLarge {
                font-size: 128px;
            }

            .objectDisplayFull {
                font-size: 256px;
            }

            .objectTitle a:link, .objectTitle a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .objectTitle a:hover, .objectTitle a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .objectTitleTiny {
                height: 16px;
                font-size: 16px;
                border: 0;
            }

            .objectTitleSmall {
                height: 32px;
                font-size: 32px;
                border: 0;
            }

            .objectTitleMedium {
                height: 64px;
                font-size: 64px;
                border: 0;
            }

            .objectTitleLarge {
                height: 128px;
                font-size: 128px;
                border: 0;
            }

            .objectTitleFull {
                height: 256px;
                font-size: 256px;
                border: 0;
            }

            .objectTitleText {
                background: rgba(255, 255, 255, 0.5);
            }

            .objectTitleTinyText {
                height: 16px;
                background: none;
            }

            .objectTitleSmallText {
                height: 30px;
                text-align: left;
                padding: 1px 0 1px 1px;
                color: #000000;
            }

            .objectTitleMediumText {
                height: 58px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .objectTitleLargeText {
                height: 122px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .objectTitleFullText {
                height: 246px;
                text-align: left;
                padding: 5px 0 5px 5px;
                color: #000000;
            }

            .objectTitleTinyRefs {
                visibility: hidden;
            }

            .objectTitleTinyRefs img {
                visibility: hidden;
            }

            .objectTitleSmallRefs {
                height: 12px;
                line-height: 12px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 9px;
            }

            .objectTitleSmallRefs img {
                height: 12px;
                width: 12px;
            }

            .objectTitleMediumRefs {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
            }

            .objectTitleMediumRefs img {
                height: 16px;
                width: 16px;
            }

            .objectTitleLargeRefs {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
            }

            .objectTitleLargeRefs img {
                height: 16px;
                width: 16px;
            }

            .objectTitleFullRefs {
                height: 32px;
                line-height: 32px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 20px;
            }

            .objectTitleFullRefs img {
                height: 32px;
                width: 32px;
            }

            .objectTitleTinyName {
                height: 1rem;
                line-height: 1rem;
                font-size: 1rem;
            }

            .objectTitleSmallName {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 14px;
            }

            .objectTitleMediumName {
                height: 24px;
                line-height: 24px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 20px;
            }

            .objectTitleLargeName {
                height: 32px;
                line-height: 32px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 28px;
            }

            .objectTitleFullName {
                height: 64px;
                line-height: 64px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 40px;
            }

            .objectTitleID {
                height: 16px;
                font-size: 10px;
                overflow: hidden;
            }

            .objectTitleTinyFlags {
                visibility: hidden;
            }

            .objectTitleTinyFlags img {
                visibility: hidden;
            }

            .objectTitleSmallFlags {
                visibility: hidden;
            }

            .objectTitleSmallFlags img {
                visibility: hidden;
            }

            .objectTitleMediumFlags {
                height: 16px;
                font-size: 16px;
            }

            .objectTitleMediumFlags img {
                height: 16px;
                width: 16px;
                margin: 0 1px 0 0;
                float: left;
            }

            .objectTitleLargeFlags {
                height: 16px;
                font-size: 16px;
            }

            .objectTitleLargeFlags img {
                height: 16px;
                width: 16px;
                margin: 0 2px 0 0;
                float: left;
            }

            .objectTitleFullFlags {
                height: 32px;
                font-size: 32px;
            }

            .objectTitleFullFlags img {
                height: 32px;
                width: 32px;
                margin: 0 4px 0 0;
                float: left;
            }

            .objectTitleIcons img {
                height: 1em;
                width: 1em;
                float: left;
            }

            .objectTitleIconsInline img {
                height: 1em;
                width: 1em;
            }

            .objectTitleIconsApp {
                height: 1em;
                width: 1em;
                float: left;
            }

            .objectTitleIconsApp div {
                overflow: hidden;
                font-size: 12px;
                text-align: left;
                font-weight: normal;
                margin: 3px;
                color: #ffffff;
            }

            .objectTitleIconsAppShortname {
                font-size: 18px;
            }

            .objectTitleIconsAppTitle {
                font-size: 11px;
            }

            .objectTitleText0 {
                margin-left: 0;
            }

            .objectTitleText1 {
                margin-left: 1em;
            }

            .objectTitleText2 {
                margin-left: 2em;
            }

            .objectTitleStatus {
                height: 1em;
                line-height: 1em;
                overflow: hidden;
                white-space: nowrap;
                font-weight: bold;
                text-align: right;
                padding-right: 2px;
            }

            .objectDisplayTinyShort {
            }

            .objectDisplaySmallShort {
                width: 8em;
            }

            .objectDisplayMediumShort {
                width: 6em;
            }

            .objectDisplayLargeShort {
                width: 5em;
            }

            .objectDisplayFullShort {
                width: 4em;
            }

            .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                width: 256px;
            }

            @media screen and (min-width: 320px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 310px;
                }
            }

            @media screen and (min-width: 480px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 470px;
                }
            }

            @media screen and (min-width: 600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 590px;
                }
            }

            @media screen and (min-width: 768px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 758px;
                }
            }

            @media screen and (min-width: 1024px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1014px;
                }
            }

            @media screen and (min-width: 1200px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1190px;
                }
            }

            @media screen and (min-width: 1600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1590px;
                }
            }

            @media screen and (min-width: 1920px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1910px;
                }
            }

            @media screen and (min-width: 2048px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2038px;
                }
            }

            @media screen and (min-width: 2400px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2390px;
                }
            }

            @media screen and (min-width: 3840px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3830px;
                }
            }

            @media screen and (min-width: 4096px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 4086px;
                }
            }

            .objectContent {
                font-size: 0.8rem;
                border: 0;
                padding: 3px;
                margin: 0;
                color: #000000;
                overflow: auto;
            }

            .objectContentShort {
                width: 378px;
                max-height: 378px;
            }

            .objectContentText {
                background: rgba(255, 255, 255, 0.666);
                text-align: left;
            }

            .objectContentImage {
                background: rgba(255, 255, 255, 0.12);
                text-align: center;
            }

            .objectContentImage img {
                height: auto;
                max-width: 100%;
            }

            .objectFlagOn {
                background: #00ff20;
            }

            .objectTitleMenuContentLayout {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.33);
                z-index: 100;
                display: none;
                font-size: 0;
            }

            .objectTitleMenuContent {
                position: fixed;
                top: 10%;
                left: 10%;
                width: 80%;
                background: rgba(240, 240, 240, 0.5);
                padding: 16px;
            }

            .objectTitleMenuContent .objectTitleTinyLong, .objectTitleMenuContent .objectTitleSmallLong, .objectTitleMenuContent .objectTitleMediumLong, .objectTitleMenuContent .objectTitleLargeLong, .objectTitleMenuContent .objectTitleFullLong {
                width: 100%;
            }

            .objectTitleMenuContent .objectTitleText {
                background: rgba(255, 255, 255, 0.66);
            }

            .objectTitleMenuContentIcons {
                height: 64px;
                width: 128px;
            }

            .objectTitleMenuContentIcons img {
                height: 64px;
                width: 64px;
            }

            .objectMenuContent {
                background: rgba(255, 255, 255, 0.2);
                padding-top: 4px;
            }

            .objectMenuContentMsg {
                background-origin: border-box;
                font-size: 14px;
                text-align: left;
                margin-top: 1px;
                width: 100%;
                overflow: hidden;
                white-space: normal;
                min-height: 16px;
            }

            .objectMenuContentMsg img {
                height: 16px;
                width: 16px;
                margin: 0 2px 0 0;
                float: left;
            }

            .objectMenuContentMsgOK {
                background: #103020;
                color: #ffffff;
            }

            .objectMenuContentMsgWarn {
                background: #ffe080;
                color: #ff8000;
            }

            .objectMenuContentMsgError {
                background: #ffa0a0;
                color: #ff0000;
                font-family: monospace;
            }

            .objectMenuContentMsgInfo {
                background: rgba(0, 0, 0, 0.4);
                color: #ffffff;
            }

            .objectMenuContentMsgID {
                background: rgba(255, 255, 255, 0.4);
                color: #000000;
                font-family: monospace;
                font-size: 9px;
                overflow: hidden;
                white-space: nowrap;
                min-height: 4px;
            }

            .objectMenuContentMsgEmotions {
                background: rgba(255, 255, 255, 0.1);
                color: #000000;
                text-align: center;
                min-height: 24px;
            }

            .objectMenuContentMsgEmotions img {
                height: 24px;
                width: 24px;
                margin: 0 1px 0 3px;
                float: none;
            }

            .objectMenuContentMsgtargetObject {
                background: rgba(0, 0, 0, 0.4);
                font-size: 12px;
                color: #ffffff;
                white-space: nowrap;
            }

            .objectMenuContentMsgtargetObject img {
                height: 16px;
                width: 16px;
                margin: 0;
                float: none;
            }

            .objectMenuContentMsgtargetObject a:link, .objectMenuContentMsgtargetObject a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .objectMenuContentMsgtargetObject a:hover, .objectMenuContentMsgtargetObject a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .objectMenuContentActions {
                margin: 1px 0 0 0;
                min-height: 32px;
                padding-top: 5px;
                background: rgba(255, 255, 255, 0.2);
            }

            .objectMenuContentActionsTinyShort {
            }

            .objectMenuContentActionsSmallShort {
                width: 256px;
            }

            .objectMenuContentActionsMediumShort {
                width: 384px;
            }

            .objectMenuContentActionsLargeShort {
                width: 640px;
            }

            .objectMenuContentActionsFullShort {
                width: 1024px;
            }

            .objectMenuContentActionsTinyLong, .objectMenuContentActionsSmallLong, .objectMenuContentActionsMediumLong, .objectMenuContentActionsLargeLong, .objectMenuContentActionsFullLong {
                width: 100%;
            }

            .objectMenuContentAction {
                height: 64px;
                display: inline-block;
                margin-top: 5px;
                margin-left: 5px;
                text-align: left;
            }

            /* Correction à vérifier */
            .objectMenuContentActionNoJS {
                height: 32px;
                display: inline-block;
                margin-bottom: 1px;
                text-align: left;
            }

            .objectMenuContentActionTinyShort {
            }

            .objectMenuContentActionSmallShort {
                width: 256px;
            }

            .objectMenuContentActionMediumShort {
                width: 384px;
            }

            .objectMenuContentActionLargeShort {
                width: 210px;
                margin-right: 5px;
            }

            .objectMenuContentActionFullShort {
                width: 251px;
                margin-right: 5px;
            }

            .objectMenuContentActionTinyLong, .objectMenuContentActionSmallLong, .objectMenuContentActionMediumLong, .objectMenuContentActionLargeLong, .objectMenuContentActionFullLong {
                width: 251px;
            }

            .objectMenuContentActionSelf {
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
            }

            .objectMenuContentActionSelf a:link, .objectMenuContentActionSelf a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .objectMenuContentActionSelf a:hover, .objectMenuContentActionSelf a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .objectMenuContentActionType {
                background: rgba(0, 0, 0, 0.66);
                color: #ffffff;
            }

            .objectMenuContentActionType a:link, .objectMenuContentActionType a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .objectMenuContentActionType a:hover, .objectMenuContentActionType a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .objectMenuContentAction-icon, .objectMenuContentAction-iconNoJS {
                float: left;
                margin-right: 5px;
            }

            .objectMenuContentAction-icon img {
                height: 64px;
                width: 64px;
            }

            .objectMenuContentAction-iconNoJS img {
                height: 32px;
                width: 32px;
            }

            .objectMenuContentAction-modname p {
                font-size: 0.7rem;
                font-style: italic;
                font-weight: normal;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-title p {
                font-size: 1.1rem;
                font-weight: bold;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-text p {
                font-size: 0.8rem;
                font-weight: normal;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-close {
                height: 1px;
                clear: both;
            }
        </style>
        <?php
    }
}
