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
 * Ce module permet de gérer les groupes.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleLang extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::sylabe:module:lang:ModuleName';
    const MODULE_MENU_NAME = '::sylabe:module:lang:MenuName';
    const MODULE_COMMAND_NAME = 'lang';
    const MODULE_DEFAULT_VIEW = 'list';
    const MODULE_DESCRIPTION = '::sylabe:module:lang:ModuleDescription';
    const MODULE_VERSION = '020250220';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2025-2025';
    const MODULE_LOGO = '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256';
    const MODULE_HELP = '::sylabe:module:lang:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('list');
    const MODULE_REGISTERED_ICONS = array(
        '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256',    // 0 : World.
    );
    const MODULE_APP_TITLE_LIST = array('::sylabe:module:lang:AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::sylabe:module:lang:AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    private string $_hashGroup;



    protected function _initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getCurrentEntityIsUnlocked();
    }



    public function getHookList(string $hookName, ?Node $nid = null):array
    {
        $hookArray = array();

        switch ($hookName) {
            case 'menu':
                $hookArray[0]['name'] = '::sylabe:module:lang:AppTitle1';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::sylabe:module:lang:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0];
                break;
            case 'selfMenu':
                $hookArray[1]['name'] = '::sylabe:module:lang:AppTitle1';
                $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $hookArray[1]['desc'] = '::sylabe:module:lang:AppDesc1';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0];
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
                $this->_displayLanguages();
        //        break;
        //}
    }

    public function displayModuleInline(): void
    {
        //switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
        //    case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineLanguages();
        //        break;
        //}
    }



    private function _displayLanguages(): void
    {
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle('Current language');
        $instance->setIconRID($this::MODULE_LOGO);
        $instance->display();

        // TODO

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('langs');
    }

    /**
     * Affiche les groupes de l'entité en cours de visualisation, en ligne.
     *
     * @return void
     */
    private function _display_InlineLanguages(): void
    {
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle('Available languages');
        $instance->setIconRID($this::MODULE_LOGO);
        $instance->display();

        // TODO
    }

    private function _displayTitle(string $title, string $icon): void {
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle($title);
        $instance->setIconRID($icon);
        $instance->display();
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::sylabe:module:lang:ModuleName' => 'Module des langues',
            '::sylabe:module:lang:MenuName' => 'Langages',
            '::sylabe:module:lang:ModuleDescription' => 'Module de gestion des langues.',
            '::sylabe:module:lang:ModuleHelp' => "Ce module permet de sélectionner une langue pour les applications.",
            '::sylabe:module:lang:AppTitle1' => 'Langues',
            '::sylabe:module:lang:AppDesc1' => 'Gestion des langues.',
            '::sylabe:module:lang:display:Groups' => 'Les groupes',
            '::sylabe:module:lang:display:MyGroups' => 'Mes groupes',
            '::sylabe:module:lang:display:seeAsGroup' => 'Voir comme groupe',
            '::sylabe:module:lang:display:seenFromOthers' => 'Vu depuis les autres entités',
            '::sylabe:module:lang:display:otherGroups' => 'Les groupes des autres entités',
            '::sylabe:module:lang:display:createGroup' => 'Créer un groupe',
            '::sylabe:module:lang:display:AddMarkedObjects' => 'Ajouter les objets marqués',
            '::sylabe:module:lang:display:deleteGroup' => 'Supprimer le groupe',
            '::sylabe:module:lang:display:createTheGroup' => 'Créer le groupe',
            '::sylabe:module:lang:display:nom' => 'Nom',
            '::sylabe:module:lang:display:OKCreateGroup' => 'Le groupe a été créé.',
            '::sylabe:module:lang:display:notOKCreateGroup' => "Le groupe n'a pas été créé ! %s",
            '::sylabe:module:lang:display:noGroup' => 'Pas de groupe.',
            '::sylabe:module:lang:display:noGroupMember' => 'Pas de membre.',
            '::sylabe:module:lang:display:makeGroup' => 'Faire de cet objet un groupe',
            '::sylabe:module:lang:display:makeGroupMe' => 'Faire de cet objet un groupe pour moi aussi',
            '::sylabe:module:lang:display:unmakeGroup' => 'Ne plus faire de cet objet un groupe',
            '::sylabe:module:lang:display:useAsGroupOpened' => 'Utiliser comme groupe ouvert',
            '::sylabe:module:lang:display:useAsGroupClosed' => 'Utiliser comme groupe fermé',
            '::sylabe:module:lang:display:refuseGroup' => 'Refuser cet objet comme un groupe',
            '::sylabe:module:lang:display:removeFromGroup' => 'Retirer du groupe',
            '::sylabe:module:lang:display:isGroup' => 'est un groupe.',
            '::sylabe:module:lang:display:isGroupToOther' => 'est un groupe de',
            '::sylabe:module:lang:display:isNotGroup' => "n'est pas un groupe.",
            '::sylabe:module:lang:display:thisIsGroup' => "C'est un groupe.",
            '::sylabe:module:lang:display:thisIsNotGroup' => "Ce n'est pas un groupe.",
        ],
        'en-en' => [
            '::sylabe:module:lang:ModuleName' => 'Groups module',
            '::sylabe:module:lang:MenuName' => 'Groups',
            '::sylabe:module:lang:ModuleDescription' => 'Groups management module.',
            '::sylabe:module:lang:ModuleHelp' => 'This module permit to see and manage groups.',
            '::sylabe:module:lang:AppTitle1' => 'Groups',
            '::sylabe:module:lang:AppDesc1' => 'Manage groups.',
            '::sylabe:module:lang:display:Groups' => 'The groups',
            '::sylabe:module:lang:display:MyGroups' => 'My groups',
            '::sylabe:module:lang:display:seeAsGroup' => 'See as group',
            '::sylabe:module:lang:display:seenFromOthers' => 'Seen from others entities',
            '::sylabe:module:lang:display:otherGroups' => 'Groups of other entities',
            '::sylabe:module:lang:display:createGroup' => 'Create a group',
            '::sylabe:module:lang:display:AddMarkedObjects' => 'Add marked objects',
            '::sylabe:module:lang:display:deleteGroup' => 'Delete group',
            '::sylabe:module:lang:display:createTheGroup' => 'Create the group',
            '::sylabe:module:lang:display:nom' => 'Name',
            '::sylabe:module:lang:display:OKCreateGroup' => 'The group have been created.',
            '::sylabe:module:lang:display:notOKCreateGroup' => 'The group have not been created! %s',
            '::sylabe:module:lang:display:noGroup' => 'No group.',
            '::sylabe:module:lang:display:noGroupMember' => 'No member.',
            '::sylabe:module:lang:display:makeGroup' => 'Make this object a group',
            '::sylabe:module:lang:display:makeGroupMe' => 'Make this object a group for me too',
            '::sylabe:module:lang:display:unmakeGroup' => 'Unmake this object a group',
            '::sylabe:module:lang:display:useAsGroupOpened' => 'Use as group opened',
            '::sylabe:module:lang:display:useAsGroupClosed' => 'Use as group closed',
            '::sylabe:module:lang:display:refuseGroup' => 'Refuse this object as group',
            '::sylabe:module:lang:display:removeFromGroup' => 'Remove from group',
            '::sylabe:module:lang:display:isGroup' => 'is a group.',
            '::sylabe:module:lang:display:isGroupToOther' => 'is a group of',
            '::sylabe:module:lang:display:isNotGroup' => 'is not a group.',
            '::sylabe:module:lang:display:thisIsGroup' => 'This is a group.',
            '::sylabe:module:lang:display:thisIsNotGroup' => 'This is not a group.',
        ],
        'es-co' => [
            '::sylabe:module:lang:ModuleName' => 'Groups module',
            '::sylabe:module:lang:MenuName' => 'Groups',
            '::sylabe:module:lang:ModuleDescription' => 'Groups management module.',
            '::sylabe:module:lang:ModuleHelp' => 'This module permit to see and manage groups.',
            '::sylabe:module:lang:AppTitle1' => 'Groups',
            '::sylabe:module:lang:AppDesc1' => 'Manage groups.',
            '::sylabe:module:lang:display:Groups' => 'The groups',
            '::sylabe:module:lang:display:MyGroups' => 'My groups',
            '::sylabe:module:lang:display:seeAsGroup' => 'See as group',
            '::sylabe:module:lang:display:seenFromOthers' => 'Seen from others entities',
            '::sylabe:module:lang:display:otherGroups' => 'Groups of other entities',
            '::sylabe:module:lang:display:createGroup' => 'Create a group',
            '::sylabe:module:lang:display:AddMarkedObjects' => 'Add marked objects',
            '::sylabe:module:lang:display:deleteGroup' => 'Delete group',
            '::sylabe:module:lang:display:createTheGroup' => 'Create the group',
            '::sylabe:module:lang:display:nom' => 'Name',
            '::sylabe:module:lang:display:OKCreateGroup' => 'The group have been created.',
            '::sylabe:module:lang:display:notOKCreateGroup' => 'The group have not been created! %s',
            '::sylabe:module:lang:display:noGroup' => 'No group.',
            '::sylabe:module:lang:display:noGroupMember' => 'No member.',
            '::sylabe:module:lang:display:makeGroup' => 'Make this object a group',
            '::sylabe:module:lang:display:makeGroupMe' => 'Make this object a group for me too',
            '::sylabe:module:lang:display:unmakeGroup' => 'Unmake this object a group',
            '::sylabe:module:lang:display:useAsGroupOpened' => 'Use as group opened',
            '::sylabe:module:lang:display:useAsGroupClosed' => 'Use as group closed',
            '::sylabe:module:lang:display:refuseGroup' => 'Refuse this object as group',
            '::sylabe:module:lang:display:removeFromGroup' => 'Remove from group',
            '::sylabe:module:lang:display:isGroup' => 'is a group.',
            '::sylabe:module:lang:display:isGroupToOther' => 'is a group of',
            '::sylabe:module:lang:display:isNotGroup' => 'is not a group.',
            '::sylabe:module:lang:display:thisIsGroup' => 'This is a group.',
            '::sylabe:module:lang:display:thisIsNotGroup' => 'This is not a group.',
        ],
    ];
}
