<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Neblog\Action;
use Nebule\Library;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Metrology;
use Nebule\Library\Modules;
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
    protected string $MODULE_TYPE = 'Application';
    protected string $MODULE_NAME = '::neblog:module:objects:ModuleName';
    protected string $MODULE_MENU_NAME = '::neblog:module:objects:MenuName';
    protected string $MODULE_COMMAND_NAME = 'blog';
    protected string $MODULE_DEFAULT_VIEW = 'blog';
    protected string $MODULE_DESCRIPTION = '::neblog:module:objects:ModuleDescription';
    protected string $MODULE_VERSION = '020250111';
    protected string $MODULE_AUTHOR = 'Projet nebule';
    protected string $MODULE_LICENCE = '(c) GLPv3 nebule 2024-2025';
    protected string $MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    protected string $MODULE_HELP = '::neblog:module:objects:ModuleHelp';
    protected string $MODULE_INTERFACE = '3.0';

    const COMMAND_ACTION_NEW_BLOG_NAME = 'actionnewblogname';
    const COMMAND_ACTION_NEW_BLOG_TITLE = 'actionnewblogtitle';
    const RID_BLOG_NODE = 'cd9fd328c6b2aadd42ace4254bd70f90d636600db6ed9079c0138bd80c4347755d98.none.272';
    const RID_BLOG_ITEM = '29d07ad0f843ab88c024811afb74af1590d7c1877c67075c5f4f42e702142baea0fa.none.272';

    protected array $MODULE_REGISTERED_VIEWS = array(
        'blog',
        'list',
        'new',
        'modify',
        'delete',
        'about',
    );
    protected array $MODULE_REGISTERED_ICONS = array(
        Displays::DEFAULT_ICON_LO,
        Displays::DEFAULT_ICON_LSTOBJ,
        Displays::DEFAULT_ICON_ADDOBJ,
        Displays::DEFAULT_ICON_IMODIFY,
        Displays::DEFAULT_ICON_LD,
        Displays::DEFAULT_ICON_HELP,
    );
    protected array $MODULE_APP_TITLE_LIST = array();
    protected array $MODULE_APP_ICON_LIST = array();
    protected array $MODULE_APP_DESC_LIST = array();
    protected array $MODULE_APP_VIEW_LIST = array();

    const DEFAULT_ATTRIBS_DISPLAY_NUMBER = 10;



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
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[1]['name'] = '::neblog:module:list:listblog';
                    $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[2]) {
                    $hookArray[2]['name'] = '::neblog:module:new:newblog';
                    $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[2];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[5]) {
                    $hookArray[5]['name'] = '::neblog:module:about:title';
                    $hookArray[5]['icon'] = $this->MODULE_REGISTERED_ICONS[5];
                    $hookArray[5]['desc'] = '';
                    $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[5];
                }
                break;
        }
        return $hookArray;
    }


    public function displayModule(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_displayBlog();
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
                $this->_displayList();
                break;
        }
    }

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



    public function headerStyle(): void
    {
        /*?> FIXME

        .neblogModuleObjectsDescList1 { padding:5px; background:rgba(255,255,255,0.5); background-origin:border-box; color:#000000; clear:both; }
        .neblogModuleObjectsDescList2 { padding:5px; background:rgba(230,230,230,0.5); background-origin:border-box; color:#000000; clear:both; }
        .neblogModuleObjectsDescError { padding:5px; background:rgba(0,0,0,0.3); background-origin:border-box; clear:both; }
        .neblogModuleObjectsDescError .neblogModuleObjectsDescAttrib { font-style:italic; color:#202020; }
        .neblogModuleObjectsDescIcon { float:left; margin-right:5px; }
        .neblogModuleObjectsDescContent { min-width:300px; }
        .neblogModuleObjectsDescDate, .neblogModuleObjectsDescSigner { float:right; margin-left:10px; }
        .neblogModuleObjectsDescSigner a { color:#000000; }
        .neblogModuleObjectsDescValue { font-weight:bold; }
        .neblogModuleObjectsDescEmotion { font-weight:bold; }
        .neblogModuleObjectsDescEmotion img { height:16px; width:16px; }
        <?php*/
    }



    private function _displayBlog(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[0]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:blog:dispblog');
        $title->setIcon($icon);
        $title->display();
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('dispblog');
    }

    private function _display_InlineBlog(): void
    {
        $instanceObject = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instanceObject->setNID($this->_applicationInstance->getCurrentObjectInstance());
        $instanceObject->setEnableColor(true);
        $instanceObject->setEnableIcon(true);
        $instanceObject->setEnableName(true);
        $instanceObject->setEnableRefs(false);
        $instanceObject->setEnableNID(false);
        $instanceObject->setEnableFlags(true);
        $instanceObject->setEnableFlagProtection(false);
        $instanceObject->setEnableFlagObfuscate(false);
        $instanceObject->setEnableFlagState(true);
        $instanceObject->setEnableFlagEmotions(false);
        $instanceObject->setEnableStatus(true);
        $instanceObject->setEnableContent(false);
        $instanceObject->setEnableJS(false);
        $instanceObject->setEnableLink(true);
        $instanceObject->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceObject->setStatus('');
        $instanceObject->display();
    }

    private function _displayList(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[1]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:blog:listblog');
        $title->setIcon($icon);
        $title->display();
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('displist');
    }

    private function _display_InlineList(): void
    {
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);

        $list = $this->_getListBlogs();

        foreach ($list as $blogNID) {
            $blogInstance = $this->_cacheInstance->newNode($blogNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableStatus(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instanceList->addItem($instance);
        }

        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }

    private function _displayNew(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[2]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::neblog:module:new:newblog', $icon, false);

        ?>

        <div>
            <h1>New blog</h1>
            <div>
                <form enctype="multipart/form-data" method="post"
                      action="<?php echo '?view=' . $this->MODULE_REGISTERED_VIEWS[1]
                          . $this->_nebuleInstance->getTicketingInstance()->getActionTicketCommand(); ?>">
                    <label>
                        <input type="text" class="newblog"
                               name="<?php echo self::COMMAND_ACTION_NEW_BLOG_NAME; ?>"
                               value="Name"/>
                    </label><br/>
                    <label>
                        <input type="text" class="newblog"
                               name="<?php echo self::COMMAND_ACTION_NEW_BLOG_TITLE; ?>"
                               value="Title"/>
                    </label><br/>
                    <input type="submit"
                           value="Create"/>
                </form>
            </div>
        </div>
        <?php
    }

    private function _displayModify(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[3]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:blog:modblog');
        $title->setIcon($icon);
        $title->display();

        // TODO
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => true,
            'informationType' => 'error',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::Developpement', $param);
    }

    private function _displayDelete(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[4]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:delete:delblog');
        $title->setIcon($icon);
        $title->display();

        // TODO
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => true,
            'informationType' => 'error',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::Developpement', $param);
    }

    private function _displayAbout(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[4]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:about:title');
        $title->setIcon($icon);
        $title->display();

        echo '<div>';
        echo '<p>' . $this->_translateInstance->getTranslate('::neblog:module:about:desc') . '</p>';
        echo '</div>';
    }



    private string $_actionAddBlogName = '';
    private string $_actionAddBlogTitle = '';

    public function actions(): void
    {
        $this->_extractActionAddBlog();
        if ($this->_actionAddBlogName != '' && $this->_actionAddBlogTitle != '')
            $this->_actionAddBlog();
    }

    private function _extractActionAddBlog(): void
    {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'ticket'))) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('extract action add blog', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd59dbd21');

            $arg_name = $this->getFilterInput(self::COMMAND_ACTION_NEW_BLOG_NAME);
            if ($arg_name != '') {
                $this->_actionAddBlogName = $arg_name;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('extract action add blog name:' . $arg_name, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2ae0f501');
            }

            $arg_title = $this->getFilterInput(self::COMMAND_ACTION_NEW_BLOG_TITLE);
            if ($arg_title != '') {
                $this->_actionAddBlogTitle = $arg_title;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('extract action add blog title:' . $arg_title, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '3376510b');
            }
        }
    }

    private function _actionAddBlog(): void
    {
        global $bootstrapApplicationIID;

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'ticket'))) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('action add blog', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '047b0bdc');

            // Crée l'objet de la référence de l'application.
            /*$instance = new Node($this->_nebuleInstance, $this->_actionAddModuleRID, '', false, false);

            // Création du type mime.
            $instance->setType($this->_hashModule);

            // Crée le lien de hash.
            $date = date(DATE_ATOM);
            $signer = $this->_nebuleInstance->getCurrentEntity();
            $action = 'l';
            $source = $this->_actionAddModuleRID;
            $target = $this->_nebuleInstance->getCryptoInstance()->hash($this->_configuration->getOptionAsString('cryptoHashAlgorithm'));
            $meta = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_HASH);
            $this->_createLink_DEPRECATED($signer, $date, $action, $source, $target, $meta, false);

            // Crée l'objet du nom.
            $instance->setName($this->_actionAddModuleName);

            // Crée le lien de référence.
            $action = 'f';
            $source = $this->_hashModule;
            $target = $this->_actionAddModuleRID;
            $meta = $this->_hashModule;
            $this->_createLink_DEPRECATED($signer, $date, $action, $source, $target, $meta, false);

            // Crée le lien d'activation dans l'application.
            $source = $bootstrapApplicationIID;
            $this->_createLink_DEPRECATED($signer, $date, $action, $source, $target, $meta, false);*/
        }
    }


    /*
     * Definition of new blog with 'BlogNID' NID:
     *  -  f>RID_BLOG_NODE>BlogNID>RID_BLOG_NODE
     * BlogNID should not have content.
     * BlogNID should have name.
     * BlogNID must not have update.
     *
     * On a blog 'BlogNID', definition of a new entry with 'EntryOID' OID:
     *  -  f>BlogNID>EntryOID>RID_BLOG_ITEM
     * EntryOID must have content.
     * EntryOID can have name.
     * EntryOID can have update.
     *
     * On an entry 'EntryOID', definition of a new answer with 'AnswerOID' OID:
     *  -  f>EntryOID>AnswerOID>BlogNID
     * AnswerOID must have content.
     * AnswerOID should not have name.
     * AnswerOID can have update.
     *
     * Only one level of answer for now. TODO
     */

    // Common functions
    private function _getLinks(array &$links, Node $nid1, string $nid3): void
    {
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $nid1->getID(),
            'bl/rl/nid3' => $nid3,
            'bl/rl/nid4' => '',
        );
        $nid1->getLinks($links, $filter);
    }
    private function _getOnLinksNID2(array &$links): array
    {
        $list = array();
        foreach ($links as $link) {
            $parsedLink = $link->getParsed();
            $oid = $parsedLink['bl/rl/nid2'];
            $list[$oid] = $oid;
        }
        return $list;
    }

    // Blogs
    private function _getListBlogs(): array
    {
        $links = array();
        $this->_getLinks($links, $this->_cacheInstance->newNode(self::RID_BLOG_NODE), self::RID_BLOG_NODE);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountBlogs(): int
    {
        $links = array();
        $this->_getLinks($links, $this->_cacheInstance->newNode(self::RID_BLOG_NODE), self::RID_BLOG_NODE);
        return sizeof($links);
    }
    private function _setNewBlog(): void
    {

    }

    // Entries on blogs
    private function _getListBlogEntries(Node $blog): array
    {
        $links = array();
        $this->_getLinks($links, $blog, self::RID_BLOG_ITEM);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountBlogEntries(Node $blog): int
    {
        $links = array();
        $this->_getLinks($links, $blog, self::RID_BLOG_ITEM);
        return sizeof($links);
    }
    private function _setNewBlogEntry(): void
    {

    }

    // Answers of entry on blogs
    private function _getListBlogEntryAnswers(Node $entry, Node $blog): array
    {
        $links = array();
        $this->_getLinks($links, $entry, $blog->getID());
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountBlogEntryAnswers(Node $entry, Node $blog): int
    {
        $links = array();
        $this->_getLinks($links, $entry, $blog->getID());
        return sizeof($links);
    }
    private function _setNewBlogEntryAnswer(): void
    {

    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::neblog:module:objects:ModuleName' => 'Module des blogs',
            '::neblog:module:objects:MenuName' => 'Blogs',
            '::neblog:module:objects:ModuleDescription' => 'Module de gestion des blogs.',
            '::neblog:module:objects:ModuleHelp' => "Ce module permet de voir et de gérer les blogs.",
            '::neblog:module:blog:dispblog' => 'Display blog',
            '::neblog:module:list:listblog' => 'List blogs',
            '::neblog:module:new:newblog' => 'New blog',
            '::neblog:module:modify:modblog' => 'Modify blog',
            '::neblog:module:delete:delblog' => 'Delete blog',
            '::neblog:module:about:title' => 'A propos',
            '::neblog:module:about:desc' => 'Ceci est un gestionnaire/afficheur de weblog basé sur nebule.',
        ],
        'en-en' => [
            '::neblog:module:objects:ModuleName' => 'Blogs module',
            '::neblog:module:objects:MenuName' => 'Blogs',
            '::neblog:module:objects:ModuleDescription' => 'Blogs management module.',
            '::neblog:module:objects:ModuleHelp' => 'This module permit to see and manage blogs.',
            '::neblog:module:blog:dispblog' => 'Display blog',
            '::neblog:module:list:listblog' => 'List blogs',
            '::neblog:module:new:newblog' => 'New blog',
            '::neblog:module:modify:modblog' => 'Modify blog',
            '::neblog:module:delete:delblog' => 'Delete blog',
            '::neblog:module:about:title' => 'About',
            '::neblog:module:about:desc' => 'This is a manager/reader for weblog based on nebule.',
        ],
        'es-co' => [
            '::neblog:module:objects:ModuleName' => 'Módulo de blogs',
            '::neblog:module:objects:MenuName' => 'Blogs',
            '::neblog:module:objects:ModuleDescription' => 'Módulo de gestión de blogs.',
            '::neblog:module:objects:ModuleHelp' => 'This module permit to see and manage blogs.',
            '::neblog:module:blog:dispblog' => 'Display blog',
            '::neblog:module:list:listblog' => 'List blogs',
            '::neblog:module:new:newblog' => 'New blog',
            '::neblog:module:modify:modblog' => 'Modify blog',
            '::neblog:module:delete:delblog' => 'Delete blog',
            '::neblog:module:about:title' => 'About',
            '::neblog:module:about:desc' => 'This is a manager/reader for weblog based on nebule.',
        ],
    ];
}
