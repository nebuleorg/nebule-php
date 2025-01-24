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
 * This module can manage blogs with articles, pages, and messages in articles.
 *
 * Definition of new blog with 'BlogNID' NID:
 *  -  f>RID_BLOG_NODE>BlogNID>RID_BLOG_NODE
 * BlogNID should not have content.
 * BlogNID should have name.
 * BlogNID must not have update.
 *
 * On a blog 'BlogNID', definition of a new post with 'PostOID' OID:
 *  -  f>BlogNID>PostOID>RID_BLOG_POST
 * PostOID must have content.
 * PostOID can have name.
 * PostOID can have update.
 *
 * On an entry 'EntryOID', definition of a new answer with 'AnswerOID' OID:
 *  -  f>EntryOID>AnswerOID>RID_BLOG_ANSWER
 * AnswerOID must have content.
 * AnswerOID should not have name.
 * AnswerOID can have update.
 * Only one level of answer for now. TODO
 *
 * On a blog 'BlogNID', definition of a new page with 'PageOID' OID:
 *  -  f>BlogNID>PageOID>RID_BLOG_PAGE
 * PageOID must have content.
 * PageOID can have name.
 * PageOID can have update.
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
    protected string $MODULE_VERSION = '020250124';
    protected string $MODULE_AUTHOR = 'Projet nebule';
    protected string $MODULE_LICENCE = '(c) GLPv3 nebule 2024-2025';
    protected string $MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    protected string $MODULE_HELP = '::neblog:module:objects:ModuleHelp';
    protected string $MODULE_INTERFACE = '3.0';

    const COMMAND_SELECT_BLOG = 'blog';
    const COMMAND_SELECT_POST = 'post';
    const COMMAND_SELECT_ANSWER = 'answ';
    const COMMAND_SELECT_PAGE= 'page';
    const COMMAND_ACTION_NEW_BLOG_NAME = 'actionnewblogname';
    const COMMAND_ACTION_NEW_ITEM_NAME = 'actionnewpostname';
    const COMMAND_ACTION_NEW_ITEM_CONTENT = 'actionnewpostcontent';
    const COMMAND_ACTION_NEW_ANSWER = 'actionnewpostcontent';
    const COMMAND_ACTION_NEW_PAGE_NAME = 'actionnewpostname';
    const COMMAND_ACTION_NEW_PAGE_CONTENT = 'actionnewpostcontent';
    const RID_BLOG_NODE = 'cd9fd328c6b2aadd42ace4254bd70f90d636600db6ed9079c0138bd80c4347755d98.none.272';
    const RID_BLOG_POST = '29d07ad0f843ab88c024811afb74af1590d7c1877c67075c5f4f42e702142baea0fa.none.272';
    const RID_BLOG_ANSWER = 'a3fe5534f7c9537145f5f5c7eba4a2c747cb781614f66898a4779a3ffaf6538856c7.none.272';
    const RID_BLOG_PAGE = '0188e8440a7cb80ade4affb0449ae92b089bed48d380024a625ab54826d4a2c2ca67.none.272';

    protected array $MODULE_REGISTERED_VIEWS = array(
        'blog',    // 0
        'blogs',   // 1
        'newblog', // 2
        'modblog', // 3
        'delblog', // 4
        'post',    // 5
        'newpost', // 6
        'modpost', // 7
        'delpost', // 8
        'page',    // 9
        'pages',   // 10
        'newpage', // 11
        'modpage', // 12
        'delpage', // 13
        'about',   // 14
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

    private string $_actionAddBlogName = '';
    private string $_actionAddPostName = '';
    private string $_actionAddPostContent = '';
    private string $_actionAddAnswerContent = '';
    private string $_actionAddPageName = '';
    private string $_actionAddPageContent = '';
    private ?node $_instanceBlogNodeRID = null;
    private ?node $_instanceBlogPostRID = null;
    private ?node $_instanceBlogAnswerRID = null;
    private ?node $_instanceBlogPageRID = null;
    private ?node $_instanceCurrentBlog = null;
    private ?node $_instanceCurrentBlogPost = null;
    private ?node $_instanceCurrentBlogPage = null;

    protected function _initialisation(): void{
        $this->_instanceBlogNodeRID = $this->_cacheInstance->newNode(self::RID_BLOG_NODE);
        $this->_instanceBlogPostRID = $this->_cacheInstance->newNode(self::RID_BLOG_POST);
        $this->_instanceBlogAnswerRID = $this->_cacheInstance->newNode(self::RID_BLOG_ANSWER);
        $this->_instanceBlogPageRID = $this->_cacheInstance->newNode(self::RID_BLOG_PAGE);

        $this->_getCurrentBlog();
        $this->_getCurrentBlogPost();
        $this->_getCurrentBlogPage();
    }

    private function _getCurrentBlog(): void
    {
        if ($this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlog') != '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlog');
        else
            $nid = $this->getFilterInput(self::COMMAND_SELECT_BLOG);
        $this->_instanceCurrentBlog = $this->_cacheInstance->newNode($nid);
        $this->_metrologyInstance->addLog('extract current blog nid=' . $this->_instanceCurrentBlog . ' ' . $nid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '184e42c6');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlog', $nid);
    }

    private function _getCurrentBlogPost(): void
    {
        if ($this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPost') != '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPost');
        else
            $nid = $this->getFilterInput(self::COMMAND_SELECT_POST);
        $this->_instanceCurrentBlogPost = $this->_cacheInstance->newNode($nid);
        $this->_metrologyInstance->addLog('extract current blog nid=' . $this->_instanceCurrentBlog, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'df3fcf87');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlogPost', $nid);
    }

    private function _getCurrentBlogPage(): void
    {
        if ($this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPage') != '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPage');
        else
            $nid = $this->getFilterInput(self::COMMAND_SELECT_PAGE);
        $this->_instanceCurrentBlogPage = $this->_cacheInstance->newNode($nid);
        $this->_metrologyInstance->addLog('extract current blog nid=' . $this->_instanceCurrentBlog, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c7298189');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlogPage', $nid);
    }



    public function getHookList(string $hookName, ?Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuNeblog':
                # List blogs
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[1]['name'] = '::neblog:module:list:blogs';
                    $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];
                }
                # New blog
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[1]
                    && $this->_entitiesInstance->getCurrentEntityIsUnlocked()) {
                    $hookArray[2]['name'] = '::neblog:module:new:blog';
                    $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[2];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];
                }
                # List pages
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[0]) {
                    $hookArray[3]['name'] = '::neblog:module:list:pages';
                    $hookArray[3]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[10]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID();
                }
                # New page
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[10]
                    && $this->_entitiesInstance->getCurrentEntityIsUnlocked()) {
                    $hookArray[4]['name'] = '::neblog:module:new:page';
                    $hookArray[4]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[4]['desc'] = '';
                    $hookArray[4]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[11]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID();
                }
                # About
                $hookArray[5]['name'] = '::neblog:module:about:title';
                $hookArray[5]['icon'] = $this->MODULE_REGISTERED_ICONS[5];
                $hookArray[5]['desc'] = '';
                $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[14];
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
                $this->_displayNewBlog();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModBlog();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_displayDelBlog();
                break;
            case $this->MODULE_REGISTERED_VIEWS[5]:
                $this->_displayPost();
                break;
            case $this->MODULE_REGISTERED_VIEWS[6]:
                $this->_displayNewPost();
                break;
            case $this->MODULE_REGISTERED_VIEWS[7]:
                $this->_displayModPost();
                break;
            case $this->MODULE_REGISTERED_VIEWS[8]:
                $this->_displayDelPost();
                break;
            case $this->MODULE_REGISTERED_VIEWS[9]:
                $this->_displayPage();
                break;
            case $this->MODULE_REGISTERED_VIEWS[10]:
                $this->_displayPages();
                break;
            case $this->MODULE_REGISTERED_VIEWS[11]:
                $this->_displayNewPage();
                break;
            case $this->MODULE_REGISTERED_VIEWS[12]:
                $this->_displayModPage();
                break;
            case $this->MODULE_REGISTERED_VIEWS[13]:
                $this->_displayDelPage();
                break;
            case $this->MODULE_REGISTERED_VIEWS[14]:
                $this->_displayAbout();
                break;
            default:
                $this->_displayBlogs();
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
                $this->_display_InlineBlogs();
                break;
                break;
            case $this->MODULE_REGISTERED_VIEWS[10]:
                $this->_display_InlinePages();
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
        $title->setTitle('::neblog:module:blog:disp');
        $title->setIcon($icon);
        $title->display();
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('blog');
    }

    private function _display_InlineBlog(): void
    {
        $instanceObject = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instanceObject->setNID($this->_instanceCurrentBlog);
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

        // List
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);

        if ($this->_entitiesInstance->getCurrentEntityIsUnlocked()) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::neblog:module:new:post');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[6]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID());
            $instanceList->addItem($instance);
        }

        $list = $this->_getListBlogPosts($this->_instanceCurrentBlog);

        foreach ($list as $blogPostNID) {
            $blogInstance = $this->_cacheInstance->newNode($blogPostNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                . '&' . self::COMMAND_SELECT_POST . '=' . $blogPostNID);
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

    private function _displayBlogs(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[1]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:list:blogs');
        $title->setIcon($icon);
        $title->display();
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('blogs');
    }

    private function _display_InlineBlogs(): void
    {
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);

        if ($this->_entitiesInstance->getCurrentEntityIsUnlocked()) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::neblog:module:new:blog');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2]);
            $instanceList->addItem($instance);
        }

        $list = $this->_getListBlogs();

        foreach ($list as $blogNID) {
            $blogInstance = $this->_cacheInstance->newNode($blogNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID);
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

    private function _displayNewBlog(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[2]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::neblog:module:new:blog', $icon, false);
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
                    <input type="submit" value="Create"/>
                </form>
            </div>
        </div>
        <?php
    }

    private function _displayModBlog(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[3]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:mod:blog');
        $title->setIcon($icon);
        $title->display();
    }

    private function _displayDelBlog(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[4]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:del:blog');
        $title->setIcon($icon);
        $title->display();
    }

    private function _displayPost(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[4]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:show:post');
        $title->setIcon($icon);
        $title->display();
    }

    private function _displayNewPost(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[2]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::neblog:module:new:post', $icon, false);
        ?>

        <div>
            <h1>New post</h1>
            <div>
                <form enctype="multipart/form-data" method="post"
                    action="<?php echo '?view=' . $this->MODULE_REGISTERED_VIEWS[0]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketCommand(); ?>">
                    <label>
                        <input type="text" class="newpost"
                               name="<?php echo self::COMMAND_ACTION_NEW_ITEM_NAME; ?>"
                               value="Name"/>
                    </label><br/>
                    <label>
                        <input type="text" class="newpost"
                               name="<?php echo self::COMMAND_ACTION_NEW_ITEM_CONTENT; ?>"
                               value="Content"/>
                    </label><br/>
                    <input type="submit"
                           value="Create"/>
                </form>
            </div>
        </div>
        <?php
    }

    private function _displayModPost(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[3]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:mod:post');
        $title->setIcon($icon);
        $title->display();
    }

    private function _displayDelPost(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[4]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:delete:post');
        $title->setIcon($icon);
        $title->display();
    }

    private function _displayPage(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[4]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:show:page');
        $title->setIcon($icon);
        $title->display();
    }

    private function _displayPages(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[4]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:list:pages');
        $title->setIcon($icon);
        $title->display();
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('pages');
    }

    private function _display_InlinePages(): void
    {
    }

    private function _displayNewPage(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[2]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::neblog:module:new:page', $icon, false);

        ?>

        <div>
            <h1>New page</h1>
            <div>
                <form enctype="multipart/form-data" method="post"
                    action="<?php echo '?view=' . $this->MODULE_REGISTERED_VIEWS[10]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketCommand(); ?>">
                    <label>
                        <input type="text" class="newpage"
                               name="<?php echo self::COMMAND_ACTION_NEW_PAGE_NAME; ?>"
                               value="Name"/>
                    </label><br/>
                    <label>
                        <input type="text" class="newpage"
                               name="<?php echo self::COMMAND_ACTION_NEW_PAGE_CONTENT; ?>"
                               value="Content"/>
                    </label><br/>
                    <input type="submit" value="Create"/>
                </form>
            </div>
        </div>
        <?php
    }

    private function _displayModPage(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[3]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:mod:page');
        $title->setIcon($icon);
        $title->display();
    }

    private function _displayDelPage(): void
    {
        $icon = $this->_cacheInstance->newNode($this->MODULE_REGISTERED_ICONS[4]);
        $title = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $title->setTitle('::neblog:module:del:page');
        $title->setIcon($icon);
        $title->display();
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



    public function actions(): void
    {
        if ($this->_extractActionAddBlog())
            $this->_setNewBlog($this->_actionAddBlogName);
        if ($this->_extractActionAddPost())
            $this->_setNewBlogPost($this->_actionAddBlogName, $this->_actionAddPostContent);
        if ($this->_extractActionAddComment())
            $this->_setNewPostAnswer($this->_actionAddAnswerContent);
        if ($this->_extractActionAddPage())
            $this->_setNewBlogPage($this->_actionAddPageName, $this->_actionAddPageContent);
    }

    private function _extractActionAddBlog(): bool
    {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'ticket'))) {
            $this->_metrologyInstance->addLog('extract action add blog', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd59dbd21');

            $this->_actionAddBlogName = $this->getFilterInput(self::COMMAND_ACTION_NEW_BLOG_NAME);
            if ($this->_actionAddBlogName != '') {
                $this->_metrologyInstance->addLog('extract action add blog name:' . $this->_actionAddBlogName, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2ae0f501');
                return true;
            }
        }
        return false;
    }

    private function _extractActionAddPost(): bool
    {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'ticket'))) {
            $this->_metrologyInstance->addLog('extract action add blog post', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b54fc74c');

            $this->_actionAddPostName = $this->getFilterInput(self::COMMAND_ACTION_NEW_ITEM_NAME);
            $this->_actionAddPostContent = $this->getFilterInput(self::COMMAND_ACTION_NEW_ITEM_CONTENT);
            if ($this->_actionAddPostContent != '') {
                $this->_metrologyInstance->addLog('extract action add blog post', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '48cdf007');
                return true;
            }
        }
        return false;
    }

    private function _extractActionAddComment(): bool
    {
        return false;
    }

    private function _extractActionAddPage(): bool
    {
        return false;
    }


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
        $this->_getLinks($links, $this->_instanceBlogNodeRID, self::RID_BLOG_NODE);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountBlogs(): int
    {
        $links = array();
        $this->_getLinks($links, $this->_instanceBlogNodeRID, self::RID_BLOG_NODE);
        return sizeof($links);
    }
    private function _setNewBlog(string $name): void
    {
        $instanceNode = $this->_cacheInstance->newVirtualNode();
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $this->_metrologyInstance->addLog('new blog nid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '24eb5b6b');
        $instanceBL->addLink('f>' . self::RID_BLOG_NODE . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_NODE);
        $instanceBL->signWrite();
        $instanceNode->setName($name);
    }

    // Entries on blogs
    private function _getListBlogPosts(Node $blog): array
    {
        $links = array();
        $this->_getLinks($links, $blog, self::RID_BLOG_POST);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountBlogPosts(Node $blog): int
    {
        $links = array();
        $this->_getLinks($links, $blog, self::RID_BLOG_POST);
        return sizeof($links);
    }
    private function _setNewBlogPost(string $name, string $content): void
    {
        $instanceNode = new \Nebule\Library\Node($this->_nebuleInstance, 'new');
        $instanceNode->setContent($content);
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $this->_metrologyInstance->addLog('new blog post oid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '5e3b8f18');
        $instanceBL->addLink('f>' . $this->_instanceCurrentBlog->getID() . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_POST);
        $instanceBL->signWrite();
        $instanceNode->setName($name);
    }

    // Answers of entry on blogs
    private function _getListPostAnswers(Node $entry): array
    {
        $links = array();
        $this->_getLinks($links, $entry, self::RID_BLOG_ANSWER);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountPostAnswers(Node $entry): int
    {
        $links = array();
        $this->_getLinks($links, $entry, self::RID_BLOG_ANSWER);
        return sizeof($links);
    }
    private function _setNewPostAnswer(string $name): void
    {
        // TODO
    }

    // Pages on blogs
    private function _getListBlogPages(Node $blog): array
    {
        $links = array();
        $this->_getLinks($links, $blog, self::RID_BLOG_PAGE);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountBlogPages(Node $blog): int
    {
        $links = array();
        $this->_getLinks($links, $blog, self::RID_BLOG_PAGE);
        return sizeof($links);
    }
    private function _setNewBlogPage(string $name, string $content): void
    {
        $instanceNode = new \Nebule\Library\Node($this->_nebuleInstance, 'new');
        $instanceNode->setContent($content);
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $this->_metrologyInstance->addLog('new blog page oid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '94d38ae6');
        $instanceBL->addLink('f>' . $this->_instanceCurrentBlog->getID() . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_PAGE);
        $instanceBL->signWrite();
        $instanceNode->setName($name);
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::neblog:module:objects:ModuleName' => 'Module des blogs',
            '::neblog:module:objects:MenuName' => 'Blogs',
            '::neblog:module:objects:ModuleDescription' => 'Module de gestion des blogs.',
            '::neblog:module:objects:ModuleHelp' => "Ce module permet de voir et de gérer les blogs.",
            '::neblog:module:blog:dispblog' => 'Display blog',
            '::neblog:module:list:listblogs' => 'List blogs',
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
            '::neblog:module:list:listblogs' => 'List blogs',
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
            '::neblog:module:list:listblogs' => 'List blogs',
            '::neblog:module:new:newblog' => 'New blog',
            '::neblog:module:modify:modblog' => 'Modify blog',
            '::neblog:module:delete:delblog' => 'Delete blog',
            '::neblog:module:about:title' => 'About',
            '::neblog:module:about:desc' => 'This is a manager/reader for weblog based on nebule.',
        ],
    ];
}
