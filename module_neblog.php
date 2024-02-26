<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Neblog\Action;
use Nebule\Application\Neblog\Display;
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
    protected $MODULE_NAME = '::neblog:module:objects:ModuleName';
    protected $MODULE_MENU_NAME = '::neblog:module:objects:MenuName';
    protected $MODULE_COMMAND_NAME = 'log';
    protected $MODULE_DEFAULT_VIEW = 'blog';
    protected $MODULE_DESCRIPTION = '::neblog:module:objects:ModuleDescription';
    protected $MODULE_VERSION = '020240225';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2024-2024';
    protected $MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    protected $MODULE_HELP = '::neblog:module:objects:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array('blog', 'list', 'new', 'modify', 'delete', 'about');
    protected $MODULE_REGISTERED_ICONS = array(
        '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256',    // 0 : Group.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 1 : Objet.
        '819babe3072d50f126a90c982722568a7ce2ddd2b294235f40679f9d220e8a0a.sha2.256',    // 2 : Create.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 3 : Objet.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 4 : Objet.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 5 : Objet.
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
            case 'selfMenuNeblog':
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[0]) {
                    $hookArray[0]['name'] = '::neblog:module:blog:dispblog';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_LO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[0]['name'] = '::neblog:module:blog:listblog';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_LSTOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[2]) {
                    $hookArray[0]['name'] = '::neblog:module:blog:newblog';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_ADDOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[5]) {
                    $hookArray[0]['name'] = '::neblog:module:blog:title';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_HELP;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[5];
                }
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
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayNew();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModify();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_displayDelete();
                break;
            case $this->MODULE_REGISTERED_VIEWS[5]:
                $this->_displayAbout();
                break;
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
        }
    }


    /**
     * Affichage de surcharges CSS.
     *
     * @return void
     */
    public function headerStyle(): void
    {
        /*?> FIXME

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
        <?php*/
    }


    /**
     * Display view of blog.
     */
    private function _displayBlog(): void
    {
        // Titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[0]);
        echo $this->_display->getDisplayTitle('::neblog:module:blog:dispblog', $icon, false);

        // Affichage le blog.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('dispblog');
    }

    /**
     * Display inline view of blog.
     */
    private function _display_InlineBlog(): void
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
     * Display view of list of blog.
     */
    private function _displayList(): void
    {
        // Titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[1]);
        echo $this->_display->getDisplayTitle('::neblog:module:list:listblog', $icon, false);

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
     * Display view of blog.
     */
    private function _displayNew(): void
    {
        // Titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[2]);
        echo $this->_display->getDisplayTitle('::neblog:module:new:newblog', $icon, false);

        ?>

        <div>
            <h1>New blog</h1>
            <div>
                <form enctype="multipart/form-data" method="post"
                      action="<?php echo '?' . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                    <label>
                        <input type="text" class="newblog"
                               name="<?php echo Action::COMMAND_ACTION_NEW_BLOG_NAME; ?>"
                               value="Name"/>
                    </label><br/>
                    <label>
                        <input type="text" class="newblog"
                               name="<?php echo Action::COMMAND_ACTION_NEW_BLOG_TITLE; ?>"
                               value="Title"/>
                    </label><br/>
                    <input type="submit"
                           value="Create"/>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Display view of blog.
     */
    private function _displayModify(): void
    {
        // Titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[3]);
        echo $this->_display->getDisplayTitle('::neblog:module:modify:modblog', $icon, false);

        // TODO
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => true,
            'informationType' => 'error',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        echo $this->_display->getDisplayInformation('::::Developpement', $param);
    }

    /**
     * Display view of blog.
     */
    private function _displayDelete(): void
    {
        // Titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[4]);
        echo $this->_display->getDisplayTitle('::neblog:module:delete:delblog', $icon, false);

        // TODO
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => true,
            'informationType' => 'error',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        echo $this->_display->getDisplayInformation('::::Developpement', $param);
    }

    /**
     * Display view of blog.
     */
    private function _displayAbout(): void
    {
        // Titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[4]);
        echo $this->_display->getDisplayTitle('::neblog:module:about:title', $icon, false);

        echo '<div>';
        echo '<p>' . $this->_traduction('::neblog:module:about:desc') . '</p>';
        echo '</div>';
    }



    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::neblog:module:objects:ModuleName'] = 'Module des blogs';
        $this->_table['en-en']['::neblog:module:objects:ModuleName'] = 'Blogs module';
        $this->_table['es-co']['::neblog:module:objects:ModuleName'] = 'Módulo de blogs';
        $this->_table['fr-fr']['::neblog:module:objects:MenuName'] = 'Blogs';
        $this->_table['en-en']['::neblog:module:objects:MenuName'] = 'Blogs';
        $this->_table['es-co']['::neblog:module:objects:MenuName'] = 'Blogs';
        $this->_table['fr-fr']['::neblog:module:objects:ModuleDescription'] = 'Module de gestion des blogs.';
        $this->_table['en-en']['::neblog:module:objects:ModuleDescription'] = 'Blogs management module.';
        $this->_table['es-co']['::neblog:module:objects:ModuleDescription'] = 'Módulo de gestión de blogs.';
        $this->_table['fr-fr']['::neblog:module:objects:ModuleHelp'] = "Ce module permet de voir et de gérer les blogs.";
        $this->_table['en-en']['::neblog:module:objects:ModuleHelp'] = 'This module permit to see and manage blogs.';
        $this->_table['es-co']['::neblog:module:objects:ModuleHelp'] = 'This module permit to see and manage blogs.';

        $this->_table['fr-fr']['::neblog:module:blog:dispblog'] = 'Display blog';
        $this->_table['en-en']['::neblog:module:blog:dispblog'] = 'Display blog';
        $this->_table['es-co']['::neblog:module:blog:dispblog'] = 'Display blog';
        $this->_table['fr-fr']['::neblog:module:list:listblog'] = 'List blogs';
        $this->_table['en-en']['::neblog:module:list:listblog'] = 'List blogs';
        $this->_table['es-co']['::neblog:module:list:listblog'] = 'List blogs';
        $this->_table['fr-fr']['::neblog:module:new:newblog'] = 'New blog';
        $this->_table['en-en']['::neblog:module:new:newblog'] = 'New blog';
        $this->_table['es-co']['::neblog:module:new:newblog'] = 'New blog';
        $this->_table['fr-fr']['::neblog:module:modify:modblog'] = 'Modify blog';
        $this->_table['en-en']['::neblog:module:modify:modblog'] = 'Modify blog';
        $this->_table['es-co']['::neblog:module:modify:modblog'] = 'Modify blog';
        $this->_table['fr-fr']['::neblog:module:delete:delblog'] = 'Delete blog';
        $this->_table['en-en']['::neblog:module:delete:delblog'] = 'Delete blog';
        $this->_table['es-co']['::neblog:module:delete:delblog'] = 'Delete blog';
        $this->_table['fr-fr']['::neblog:module:about:title'] = 'A propos';
        $this->_table['en-en']['::neblog:module:about:title'] = 'About';
        $this->_table['es-co']['::neblog:module:about:title'] = 'About';
        $this->_table['fr-fr']['::neblog:module:about:desc'] = 'Ceci est un gestionnaire/afficheur de weblog basé sur nebule.';
        $this->_table['en-en']['::neblog:module:about:desc'] = 'This is a manager/reader for weblog based on nebule.';
        $this->_table['es-co']['::neblog:module:about:desc'] = 'This is a manager/reader for weblog based on nebule.';
    }
}
