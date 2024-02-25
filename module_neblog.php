<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Actions;
use Nebule\Library\Displays;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * Ce module permet gérer les objets.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleNeblog extends Modules
{
    protected $MODULE_TYPE = 'Application';
    protected $MODULE_NAME = '::sylabe:module:objects:ModuleName';
    protected $MODULE_MENU_NAME = '::sylabe:module:objects:MenuName';
    protected $MODULE_COMMAND_NAME = 'log';
    protected $MODULE_DEFAULT_VIEW = 'blog';
    protected $MODULE_DESCRIPTION = '::sylabe:module:objects:ModuleDescription';
    protected $MODULE_VERSION = '020240225';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2024-2024';
    protected $MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    protected $MODULE_HELP = '::sylabe:module:objects:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array('blog', 'list');
    protected $MODULE_REGISTERED_ICONS = array(
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 0 : Objet.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 1 : Objet.
    );
    protected $MODULE_APP_TITLE_LIST = array();
    protected $MODULE_APP_ICON_LIST = array();
    protected $MODULE_APP_DESC_LIST = array();
    protected $MODULE_APP_VIEW_LIST = array();

    const DEFAULT_ATTRIBS_DISPLAY_NUMBER = 10;


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuBlog':
                //$instance = $this->_applicationInstance->getCurrentObjectInstance();
                $instance = $this->_nebuleInstance->newObject($object);
                $id = $instance->getID();

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
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_displayList();
                break;
            /*case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayObjectDescription();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayObjectRelations();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_displayObjectProtection();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_displayObjectProtectionShare();
                break;*/
            default:
                $this->_displayBlog();
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
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineBlog();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineList();
                break;
            /*case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_display_InlineObjectProtection();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_display_InlineObjectProtectionShare();
                break;*/
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
     * Display view of blog.
     */
    private function _displayBlog(): void
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
        echo $this->_display->getDisplayObject($this->_applicationInstance->getCurrentObjectInstance(), $param);
    }

    /**
     * Display inline view of blog.
     */
    private function _display_InlineBlog(): void
    {
        // TODO
    }

    /**
     * Display view of list of blog.
     */
    private function _displayList(): void
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
        echo $this->_display->getDisplayObject($this->_applicationInstance->getCurrentObjectInstance(), $param);
    }

    /**
     * Display inline view of list of blog.
     */
    private function _display_InlineList(): void
    {
        // TODO
    }



    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::sylabe:module:objects:ModuleName'] = 'Module des blogs';
        $this->_table['en-en']['::sylabe:module:objects:ModuleName'] = 'Blogs module';
        $this->_table['es-co']['::sylabe:module:objects:ModuleName'] = 'Módulo de blogs';
        $this->_table['fr-fr']['::sylabe:module:objects:MenuName'] = 'Blogs';
        $this->_table['en-en']['::sylabe:module:objects:MenuName'] = 'Blogs';
        $this->_table['es-co']['::sylabe:module:objects:MenuName'] = 'Blogs';
        $this->_table['fr-fr']['::sylabe:module:objects:ModuleDescription'] = 'Module de gestion des blogs.';
        $this->_table['en-en']['::sylabe:module:objects:ModuleDescription'] = 'Blogs management module.';
        $this->_table['es-co']['::sylabe:module:objects:ModuleDescription'] = 'Módulo de gestión de blogs.';
        $this->_table['fr-fr']['::sylabe:module:objects:ModuleHelp'] = "Ce module permet de voir et de gérer les blogs.";
        $this->_table['en-en']['::sylabe:module:objects:ModuleHelp'] = 'This module permit to see and manage blogs.';
        $this->_table['es-co']['::sylabe:module:objects:ModuleHelp'] = 'This module permit to see and manage blogs.';
    }
}
