<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Neblog\Action;
use Nebule\Library;
use Nebule\Library\DisplayItem;
use Nebule\Library\DisplayList;
use Nebule\Library\DisplayQuery;
use Nebule\Library\Displays;
use nebule\Library\linkInterface;
use Nebule\Library\Metrology;
use Nebule\Library\Modules;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * This module can manage blogs with articles, pages, and messages in articles.
 *
 *  - Definition of new blog with 'BlogNID' NID:
 *    - f>RID_BLOG_NODE>BlogNID>RID_BLOG_NODE :
 * BlogNID should not have content.
 * BlogNID should have name.
 * BlogNID must not have update.
 *  - Definition of the default blog for an entity :
 *    - l>Entity_EID>BlogNID>RID_BLOG_DEFAULT
 *  - On a blog 'BlogNID', definition of a new post with 'PostNID' NID and link to content 'ContentOID' OID:
 *    - f>BlogNID>PostNID>RID_BLOG_POST :
 * PostNID should not have content.
 * PostNID can have name.
 * PostNID can have update.
 *    - f>PostNID>ContentOID>RID_BLOG_CONTENT>OrderNID :
 * ContentOID must have content.
 * ContentOID should not have name.
 * ContentOID can have update.
 * OrderNID reflect the order of a content on the list of contents to display.
 *  - On an post 'PostNID', definition of a new answer with 'AnswerOID' OID:
 *    - f>PostNID>AnswerOID>RID_BLOG_ANSWER :
 * AnswerOID must have content.
 * AnswerOID should not have name.
 * AnswerOID can have update.
 * Only one level of answer for now. TODO
 *  - On a blog 'BlogNID', definition of a new page with 'PageNID' NID and link to content 'PageOID' OID:
 *    - f>BlogNID>PageNID>RID_BLOG_PAGE :
 * PageNID should not have content.
 * PageNID can have name.
 * PageNID can have update.
 *    -  l>PageNID>PageOID>RID_BLOG_CONTENT : FIXME add OrderNID
 * PageOID must have content.
 * PageOID should not have name.
 * PageOID can have update.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleNeblog extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::objects:ModuleName';
    const MODULE_MENU_NAME = '::objects:MenuName';
    const MODULE_COMMAND_NAME = 'blog';
    const MODULE_DEFAULT_VIEW = 'blog';
    const MODULE_DESCRIPTION = '::objects:ModuleDescription';
    const MODULE_VERSION = '020251116';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2024-2025';
    const MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    const MODULE_HELP = '::objects:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'blog',     // 0
        'blogs',    // 1
        'newblog',  // 2
        'modblog',  // 3
        'delblog',  // 4
        'getblog',  // 5
        'synblog',  // 6
        'post',     // 7
        'newpost',  // 8
        'modpost',  // 9
        'delpost',  // 10
        'page',     // 11
        'pages',    // 12
        'newpage',  // 13
        'modpage',  // 14
        'delpage',  // 15
        'allblogs', // 16
    );
    const MODULE_REGISTERED_ICONS = array(
        Displays::DEFAULT_ICON_LO,
        Displays::DEFAULT_ICON_LSTOBJ,
        Displays::DEFAULT_ICON_ADDOBJ,
        Displays::DEFAULT_ICON_IMODIFY,
        Displays::DEFAULT_ICON_LD,
        Displays::DEFAULT_ICON_HELP,
        Displays::DEFAULT_ICON_LL,
    );
    const MODULE_APP_TITLE_LIST = array('::objects:ModuleName');
    const MODULE_APP_ICON_LIST = array('26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256');
    const MODULE_APP_DESC_LIST = array('::objects:ModuleDescription');
    const MODULE_APP_VIEW_LIST = array('blog');

    const COMMAND_SELECT_BLOG = 'blog';
    const COMMAND_SELECT_POST = 'post';
    const COMMAND_SELECT_ANSWER = 'answ';
    const COMMAND_SELECT_PAGE= 'page';
    const COMMAND_ACTION_NEW_BLOG_NAME = 'actionnewblogname';
    const COMMAND_ACTION_NEW_BLOG_DEFAULT = 'actionnewblogdefault';
    const COMMAND_ACTION_GET_BLOG_NID = 'actiongetblognid';
    const COMMAND_ACTION_GET_BLOG_URL = 'actiongetblogurl';
    const COMMAND_ACTION_SYNC_BLOG_NID = 'actionsyncblognid';
    const COMMAND_ACTION_SYNC_BLOG = 'actionsyncblog';
    const COMMAND_ACTION_NEW_POST_NAME = 'actionnewpostname';
    const COMMAND_ACTION_NEW_POST_CONTENT = 'actionnewpostcontent';
    const COMMAND_ACTION_SYNC_POST = 'actionsyncpost';
    const COMMAND_ACTION_NEW_ANSWER = 'actionnewanswcontent';
    const COMMAND_ACTION_NEW_PAGE_NAME = 'actionnewpagename';
    const COMMAND_ACTION_NEW_PAGE_CONTENT = 'actionnewpagecontent';
    const COMMAND_ACTION_SYNC_PAGE = 'actionsyncpage';
    const RID_BLOG_NODE = 'cd9fd328c6b2aadd42ace4254bd70f90d636600db6ed9079c0138bd80c4347755d98.none.272';
    const RID_BLOG_DEFAULT = 'dd7a63bd324437ac05e82f5e278844f9a260082270f7cb18f37066f9ed83a5a06be4.none.272';
    const RID_BLOG_POST = '29d07ad0f843ab88c024811afb74af1590d7c1877c67075c5f4f42e702142baea0fa.none.272';
    const RID_BLOG_ANSWER = 'a3fe5534f7c9537145f5f5c7eba4a2c747cb781614f66898a4779a3ffaf6538856c7.none.272';
    const RID_BLOG_PAGE = '0188e8440a7cb80ade4affb0449ae92b089bed48d380024a625ab54826d4a2c2ca67.none.272';
    const RID_BLOG_CONTENT = '6178f3de25e2acad0a4dfe5b8bffabec8e5eac50898a8efcb52d2c635697f25d680a.none.272';

    private string $_actionAddBlogName = '';
    private string $_actionGetBlogNID = '';
    private string $_actionGetBlogURL = '';
    private string $_actionSyncBlogNID = '';
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

    protected function _initialisation(): void {
        $this->_instanceBlogNodeRID = $this->_cacheInstance->newNode(self::RID_BLOG_NODE);
        $this->_instanceBlogPostRID = $this->_cacheInstance->newNode(self::RID_BLOG_POST);
        $this->_instanceBlogAnswerRID = $this->_cacheInstance->newNode(self::RID_BLOG_ANSWER);
        $this->_instanceBlogPageRID = $this->_cacheInstance->newNode(self::RID_BLOG_PAGE);

        $this->_getCurrentBlog();
        $this->_getCurrentBlogPost();
        $this->_getCurrentBlogPage();
    }

    private function _getCurrentBlog(): void {
        $nid = $this->getFilterInput(self::COMMAND_SELECT_BLOG);
        if ($nid == '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlog');
        if ($nid == '')
            $nid = $this->_getDefaultBlogNID();
        if ($nid == '') { // Default is the first blog
            $list = $this->_getListBlogNID();
            if (sizeof($list) != 0) {
                reset($list);
                $nid = current($list);
            }
        }
        $this->_instanceCurrentBlog = $this->_cacheInstance->newNode($nid);
        $this->_metrologyInstance->addLog('extract current blog nid=' . $this->_instanceCurrentBlog, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '184e42c6');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlog', $nid);
    }

    private function _getCurrentBlogPost(): void {
        $nid = $this->getFilterInput(self::COMMAND_SELECT_POST);
        if ($nid == '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPost');
        $this->_instanceCurrentBlogPost = $this->_cacheInstance->newNode($nid);
        $this->_metrologyInstance->addLog('extract current blog nid=' . $this->_instanceCurrentBlog, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'df3fcf87');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlogPost', $nid);
    }

    private function _getCurrentBlogPage(): void {
        $nid = $this->getFilterInput(self::COMMAND_SELECT_PAGE);
        if ($nid == '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPage');
        $this->_instanceCurrentBlogPage = $this->_cacheInstance->newNode($nid);
        $this->_metrologyInstance->addLog('extract current blog nid=' . $this->_instanceCurrentBlog, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c7298189');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlogPage', $nid);
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array {
        $hookArray = array();
        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuNeblog':
                # List blogs of ghost entity
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[1]['name'] = '::blog:list';
                    $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # New blog
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]
                    && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[2]['name'] = '::blog:new';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # Get existing blog
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]
                    && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[3]['name'] = '::blog:getExisting';
                    $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # Come back to blog
                if (($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[7]
                    || $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[11])
                    && $this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[4]['name'] = '::blog:disp';
                    $hookArray[4]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[4]['desc'] = '';
                    $hookArray[4]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # List pages
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[0]
                    && $this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[5]['name'] = '::page:list';
                    $hookArray[5]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[5]['desc'] = '';
                    $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # New page
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[12]
                    && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))
                    && $this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[6]['name'] = '::page:new';
                    $hookArray[6]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                    $hookArray[6]['desc'] = '';
                    $hookArray[6]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[13]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # List all blogs for all entities
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[7]['name'] = '::blog:list';
                    $hookArray[7]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[7]['desc'] = '';
                    $hookArray[7]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
            case 'selfMenuBlogs':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[1]['name'] = '::blog:mod';
                    $hookArray[1]['icon'] = Displays::DEFAULT_ICON_HELP;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    $hookArray[2]['name'] = '::blog:del';
                    $hookArray[2]['icon'] = Displays::DEFAULT_ICON_LD;
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    if ($this->_configurationInstance->checkBooleanOptions(array('permitSynchronizeLink', 'permitSynchronizeObject'))) {
                        $hookArray[3]['name'] = '::blog:sync';
                        $hookArray[3]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                        $hookArray[3]['desc'] = '';
                        $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                            . '&' . self::COMMAND_ACTION_SYNC_BLOG
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    }
                }
                break;
            case 'selfMenuBlog':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))
                    && $this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[0]['name'] = '::post:new';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_ADDOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[8]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    if ($this->_configurationInstance->checkBooleanOptions(array('permitSynchronizeLink', 'permitSynchronizeObject'))) {
                        $hookArray[1]['name'] = '::blog:sync';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                            . '&' . self::COMMAND_ACTION_SYNC_BLOG
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    }
                }
                if ($this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[3]['name'] = '::page:list';
                    $hookArray[3]['icon'] = Displays::DEFAULT_ICON_LSTOBJ;
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
            case 'selfMenuPost':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitSynchronizeLink', 'permitSynchronizeObject', 'unlocked'))
                    && $this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[0]['name'] = '::post:sync';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . self::COMMAND_SELECT_POST . '=' . $this->_instanceCurrentBlogPost->getID()
                        . '&' . self::COMMAND_ACTION_SYNC_POST
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
            case 'selfMenuPage':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitSynchronizeLink', 'permitSynchronizeObject', 'unlocked'))
                    && $this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[0]['name'] = '::page:sync';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . self::COMMAND_SELECT_POST . '=' . $this->_instanceCurrentBlogPage->getID()
                        . '&' . self::COMMAND_ACTION_SYNC_PAGE
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
            case 'menu':
                $hookArray[0]['name'] = '::blog:list';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;
        }
        return $hookArray;
    }


    public function displayModule(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                if ($this->_getDefaultBlogNID() != '')
                    $this->_displayBlog();
                else
                    $this->_displayBlogs();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayNewBlog();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModBlog();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayDelBlog();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGetBlog();
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displaySyncBlog();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_displayPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[8]:
                $this->_displayNewPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[9]:
                $this->_displayModPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[10]:
                $this->_displayDelPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[11]:
                $this->_displayPage();
                break;
            case $this::MODULE_REGISTERED_VIEWS[12]:
                $this->_displayPages();
                break;
            case $this::MODULE_REGISTERED_VIEWS[13]:
                $this->_displayNewPage();
                break;
            case $this::MODULE_REGISTERED_VIEWS[14]:
                $this->_displayModPage();
                break;
            case $this::MODULE_REGISTERED_VIEWS[15]:
                $this->_displayDelPage();
                break;
            case $this::MODULE_REGISTERED_VIEWS[16]:
                $this->_displayAllBlogs();
                break;
            default:
                $this->_displayBlogs();
                break;
        }
    }

    public function displayModuleInline(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineBlog();
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineBlogs();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlinePost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[11]:
                $this->_display_InlinePage();
                break;
            case $this::MODULE_REGISTERED_VIEWS[12]:
                $this->_display_InlinePages();
                break;
            case $this::MODULE_REGISTERED_VIEWS[16]:
                $this->_display_InlineAllBlogs();
                break;
        }
    }



    public function headerStyle(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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



    private function _displayBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::blog:disp', $this::MODULE_REGISTERED_ICONS[0]);

        $list = $this->_getLinksBlogNID('all');
        $refs = array();
        foreach ($list as $link) {
            $parsedLink = $link->getParsed();
            $blogNID = $parsedLink['bl/rl/nid2'];
            if ($blogNID == $this->_instanceCurrentBlog->getID())
                $refs = $link->getSignersEID();
        }

        $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instance->setNID($this->_instanceCurrentBlog);
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setEnableName(true);
        $instance->setName($this->_instanceCurrentBlog->getName('all'));
        $instance->setEnableRefs(true);
        $instance->setRefs($refs);
        $instance->setEnableNID(false);
        $instance->setEnableFlags(true);
        $instance->setEnableFlagProtection(false);
        $instance->setEnableFlagObfuscate(false);
        $instance->setEnableFlagState(false);
        $instance->setEnableFlagEmotions(false);
        $instance->setEnableStatus(false);
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        $instance->setEnableLink(true);
        $instance->setSelfHookName('selfMenuBlog');
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('blog');
    }

    private function _display_InlineBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // TODO sync blog with arg self::COMMAND_ACTION_SYNC_BLOG

        $this->_displaySimpleTitle('::posts', $this::MODULE_REGISTERED_ICONS[1]);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $list = $this->_getLinksPostNID($this->_instanceCurrentBlog, 'all');
        foreach ($list as $link) {
            $parsedLink = $link->getParsed();
            $postPostNID = $parsedLink['bl/rl/nid2'];
            $postInstance = $this->_cacheInstance->newNode($postPostNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($postInstance);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                . '&' . self::COMMAND_SELECT_POST . '=' . $postPostNID
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setName($postInstance->getName('all'));
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            $instance->setRefs($link->getSignersEID());
            $instance->setEnableStatus(true);
            $instance->setStatus($this->_translateInstance->getTranslate('::answers') . ':' . $this->_getCountAnswerOID($postInstance, 'all'));
            $instanceList->addItem($instance);
        }
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->setOnePerLine(true);
        $instanceList->display();
    }

    private function _displayBlogs(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::blog:list', $this::MODULE_REGISTERED_ICONS[1]);

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::blog:new');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[6]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::blog:getExisting');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
        } else {
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID());
        }
        $instanceList->addItem($instance);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('blogs');
    }

    private function _display_InlineBlogs(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $this->_socialInstance->setList(array($this->_entitiesInstance->getGhostEntityEID()), 'onlist'); // FIXME
        $list = $this->_getLinksBlogNID('onlist');
        //$list = $this->_getLinksBlogNID('all');
        foreach ($list as $link) {
            $parsedLink = $link->getParsed();
            $blogNID = $parsedLink['bl/rl/nid2'];
            $blogInstance = $this->_cacheInstance->newNode($blogNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setName($blogInstance->getName('all'));
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            $instance->setRefs($link->getSignersEID());
            $instance->setSelfHookName('selfMenuBlogs');
            $instance->setEnableStatus(true);
            $instance->setStatus(
                $this->_translateInstance->getTranslate('::pages') . ':' . $this->_getCountPageNID($blogInstance, 'all')
                . ' '
                . $this->_translateInstance->getTranslate('::posts') . ':' . $this->_getCountPostNID($blogInstance, 'all')
            );
            $instanceList->addItem($instance);
        }
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }

    private function _displayAllBlogs(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::blog:list', $this::MODULE_REGISTERED_ICONS[1]);

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::blog:new');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[6]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::blog:getExisting');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
        } else {
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID());
        }
        $instanceList->addItem($instance);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('blogs');
    }

    private function _display_InlineAllBlogs(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $list = $this->_getLinksBlogNID('all');
        foreach ($list as $link) {
            $parsedLink = $link->getParsed();
            $blogNID = $parsedLink['bl/rl/nid2'];
            $blogInstance = $this->_cacheInstance->newNode($blogNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setName($blogInstance->getName('all'));
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            $instance->setRefs($link->getSignersEID());
            $instance->setSelfHookName('selfMenuBlogs');
            $instance->setEnableStatus(true);
            $instance->setStatus(
                $this->_translateInstance->getTranslate('::pages') . ':' . $this->_getCountPageNID($blogInstance, 'all')
                . ' '
                . $this->_translateInstance->getTranslate('::posts') . ':' . $this->_getCountPostNID($blogInstance, 'all')
            );
            $instanceList->addItem($instance);
        }
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }

    private function _displayNewBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::blog:new', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLogin('::blog:list', $this::MODULE_REGISTERED_VIEWS[1]);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            $instanceList = new DisplayList($this->_applicationInstance);
            $instanceList->setSize(DisplayItem::SIZE_MEDIUM);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_STRING);
            $instance->setInputName(self::COMMAND_ACTION_NEW_BLOG_NAME);
            $instance->setIconText('nebule/objet/nom');
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand());
            $instanceList->addItem($instance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_SELECT);
            $instance->setInputName(self::COMMAND_ACTION_NEW_BLOG_DEFAULT);
            $instance->setIconText('::blog:default');
            $instance->setSelectList(array(
                'y' => $this->_translateInstance->getTranslate('::::yes'),
                'n' => $this->_translateInstance->getTranslate('::::no'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_TEXT);
            $instance->setMessage('::CreateBlog');
            $instance->setInputValue('');
            $instance->setInputName(self::COMMAND_ACTION_NEW_BLOG_DEFAULT);
            $instance->setIconText('::confirm');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(true);
            $instance->setWithSubmit(true);
            $instanceList->addItem($instance);

            $instanceList->setOnePerLine();
            $instanceList->display();
        }
    }

    private function _displayModBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::blog:mod', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLogin('::blog:disp', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayDelBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::blog:del', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayBackOrLogin('::blog:disp', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayGetBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::blog:getExisting', $this::MODULE_REGISTERED_ICONS[6]);
        $this->_displayBackOrLogin('::blog:list', $this::MODULE_REGISTERED_VIEWS[1]);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            ?>

            <div>
                <h1>New blog</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(); ?>">
                        <label>
                            <input type="text" class="getblog"
                                   name="<?php echo self::COMMAND_ACTION_GET_BLOG_NID; ?>"
                                   value="NID"/>
                        </label><br/>
                        <label>
                            <input type="text" class="getblog"
                                   name="<?php echo self::COMMAND_ACTION_GET_BLOG_URL; ?>"
                                   value="URL"/>
                        </label><br/>
                        <input type="submit" value="Create"/>
                    </form>
                </div>
            </div>
            <?php
        }
    }

    private function _displaySyncBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::blog:sync', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLogin('::blog:list', $this::MODULE_REGISTERED_VIEWS[1]);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::post:disp', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_displayBackOrLogin('::blog:return', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('post');
    }

    private function _display_InlinePost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayContent($this->_instanceCurrentBlogPost, 'selfMenuPost', 'post');
        $this->_displayContentAnswers($this->_instanceCurrentBlogPost);
    }

    private function _displayNewPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::post:new', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLogin('::post:list', $this::MODULE_REGISTERED_VIEWS[0], true);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            ?>

            <div>
                <h1>New post</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(); ?>">
                        <label>
                            <input type="text" class="newpost"
                                   name="<?php echo self::COMMAND_ACTION_NEW_POST_NAME; ?>"
                                   value="Name"/>
                        </label><br/>
                        <label>
                            <textarea class="newpost" rows="20" cols="50"
                                      name="<?php echo self::COMMAND_ACTION_NEW_POST_CONTENT; ?>"></textarea>
                        </label><br/>
                        <input type="submit"
                               value="Create"/>
                    </form>
                </div>
            </div>
            <?php
        }
    }

    private function _displayModPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::post:mod', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLogin('::blog:return', $this::MODULE_REGISTERED_VIEWS[7], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayDelPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::post:del', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayBackOrLogin('::blog:return', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:disp', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_displayBackOrLogin('::blog:return', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('page');
    }

    private function _display_InlinePage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayContent($this->_instanceCurrentBlogPage, 'selfMenuPage', 'page');
        $this->_displayContentAnswers($this->_instanceCurrentBlogPage);
    }

    private function _displayPages(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:list', $this::MODULE_REGISTERED_ICONS[4]);

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $this->_displayAddButton($instanceList, '::blog:return', \Nebule\Library\DisplayItemIconMessage::TYPE_BACK,
            '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::page:new');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[13]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instanceList->addItem($instance);
        } elseif ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject'))) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instanceList->addItem($instance);
        }
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('pages');
    }

    private function _display_InlinePages(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);

        $list = $this->_getListPageNID($this->_instanceCurrentBlog, 'all');
        foreach ($list as $blogPageNID) {
            $blogInstance = $this->_cacheInstance->newNode($blogPageNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                . '&' . self::COMMAND_SELECT_PAGE . '=' . $blogPageNID
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
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

    private function _displayNewPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:new', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLogin('::page:list', $this::MODULE_REGISTERED_VIEWS[12], true);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            ?>

            <div>
                <h1>New page</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(); ?>">
                        <label>
                            <input type="text" class="newpage"
                                   name="<?php echo self::COMMAND_ACTION_NEW_PAGE_NAME; ?>"
                                   value="Name"/>
                        </label><br/>
                        <label>
                            <textarea class="newpage" rows="20" cols="50"
                                   name="<?php echo self::COMMAND_ACTION_NEW_PAGE_CONTENT; ?>"></textarea>
                        </label><br/>
                        <input type="submit" value="Create"/>
                    </form>
                </div>
            </div>
            <?php
        }
    }

    private function _displayModPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:mod', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLogin('::page:list', $this::MODULE_REGISTERED_VIEWS[12], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayDelPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:del', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayBackOrLogin('::page:list', $this::MODULE_REGISTERED_VIEWS[12], true);
        $this->_displayNotImplemented(); // TODO
    }



    public function actions(): void {
        if ($this->_extractActionAddBlog())
            $this->_setNewBlogNID($this->_actionAddBlogName, $this->_actionAddBlogDefault);
        if ($this->_extractActionGetBlog())
            $this->_getBlogNID($this->_actionGetBlogNID, $this->_actionGetBlogURL);
        if ($this->_extractActionSyncBlog())
            $this->_setSyncBlogNID($this->_actionSyncBlogNID);
        if ($this->_extractActionAddPost())
            $this->_setNewBlogPost($this->_actionAddPostName, $this->_actionAddPostContent);
        if ($this->_extractActionAddAnswer())
            $this->_setNewAnswerOID($this->_actionAddAnswerContent);
        if ($this->_extractActionAddPage())
            $this->_setNewPageNID($this->_actionAddPageName, $this->_actionAddPageContent);
    }

    private function _extractActionAddBlog(): bool {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'token'))) {
            $this->_metrologyInstance->addLog('extract action add blog', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd59dbd21');

            $this->_actionAddBlogName = $this->getFilterInput(self::COMMAND_ACTION_NEW_BLOG_NAME);
            $this->_actionAddBlogDefault = $this->getFilterInput(self::COMMAND_ACTION_NEW_BLOG_DEFAULT);
            if ($this->_actionAddBlogName != '') {
                $this->_metrologyInstance->addLog('extract action add blog name:' . $this->_actionAddBlogName, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2ae0f501');
                if ($this->_actionAddBlogDefault == 'y')
                    $this->_metrologyInstance->addLog('extract action add blog default:' . $this->_actionAddBlogDefault, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '0e084ad5');
                return true;
            }
        }
        return false;
    }

    private function _extractActionGetBlog(): bool {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'token'))) {
            $this->_metrologyInstance->addLog('extract action get blog', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'dba518d4');

            $this->_actionGetBlogNID = $this->getFilterInput(self::COMMAND_ACTION_GET_BLOG_NID);
            $this->_actionGetBlogURL = $this->getFilterInput(self::COMMAND_ACTION_GET_BLOG_URL);
            if ($this->_actionGetBlogNID != '' && $this->_actionGetBlogURL != '') {
                $this->_metrologyInstance->addLog('extract action get blog NID:' . $this->_actionGetBlogNID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '8c10a115');
                return true;
            }
        }
        return false;
    }

    private function _extractActionSyncBlog(): bool {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'token'))) {
            $this->_metrologyInstance->addLog('extract action sync blog', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9de747eb');

            $this->_actionSyncBlogNID = $this->getFilterInput(self::COMMAND_ACTION_SYNC_BLOG_NID);
            if ($this->_actionSyncBlogNID != '') {
                $this->_metrologyInstance->addLog('extract action sync blog NID:' . $this->_actionSyncBlogNID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'f53d731c');
                return true;
            }
        }
        return false;
    }

    private function _extractActionAddPost(): bool {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'token'))) {
            $this->_metrologyInstance->addLog('extract action add blog post', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b54fc74c');

            $this->_actionAddPostName = $this->getFilterInput(self::COMMAND_ACTION_NEW_POST_NAME);
            $this->_actionAddPostContent = $this->getFilterInput(self::COMMAND_ACTION_NEW_POST_CONTENT);
            if ($this->_actionAddPostContent != '') {
                $this->_metrologyInstance->addLog('extract action add blog post', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '48cdf007');
                return true;
            }
        }
        return false;
    }

    private function _extractActionAddAnswer(): bool {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'token'))) {
            $this->_metrologyInstance->addLog('extract action add answer', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8e1f227c');

            $this->_actionAddAnswerContent = $this->getFilterInput(self::COMMAND_ACTION_NEW_ANSWER);
            if ($this->_actionAddAnswerContent != '') {
                $this->_metrologyInstance->addLog('extract action add answer:' . $this->_actionAddAnswerContent, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '1645ca53');
                return true;
            }
        }
        return false;
    }

    private function _extractActionAddPage(): bool {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'token'))) {
            $this->_metrologyInstance->addLog('extract action add blog page', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b7e2f6c2');

            $this->_actionAddPageName = $this->getFilterInput(self::COMMAND_ACTION_NEW_PAGE_NAME);
            $this->_actionAddPageContent = $this->getFilterInput(self::COMMAND_ACTION_NEW_PAGE_CONTENT);
            if ($this->_actionAddPageContent != '') {
                $this->_metrologyInstance->addLog('extract action add blog page', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '074a9b57');
                return true;
            }
        }
        return false;
    }


    // Common functions
    private function _getLinksF(array &$links, Node $nid1, string $nid3, bool $withOrder = false, string $socialClass = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $nid1->getID(),
            'bl/rl/nid3' => $nid3,
            'bl/rl/nid4' => '',
        );
        if ($withOrder) {
            unset($filter['bl/rl/nid4']);
            $filter['bl/rl/nid5'] = '';
        }
        $nid1->getLinks($links, $filter, $socialClass);
    }

    private function _getLinkL(Node $nid1, string $nid3): ?linkInterface {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $nid1->getID(),
            'bl/rl/nid3' => $nid3,
            'bl/rl/nid4' => '',
        );
        $nid1->getLinks($links, $filter);

        if (sizeof($links) == 0)
            return null;

        return $links[0];
    }

    private function _getOnLinksNID2(array &$links): array {
        $list = array();
        foreach ($links as $link) {
            $oid = $link->getParsed()['bl/rl/nid2'];
            $nid = $oid;
            if (isset($link->getParsed()['bl/rl/nid4']))
                $nid = $link->getParsed()['bl/rl/nid4'];
            $list[$nid] = $oid;
        }
        return $list;
    }

    private function _getOnLinkNID2(?linkInterface $link): string {
        if ($link === null)
            return '';
        return $link->getParsed()['bl/rl/nid2'];
    }

    private function _getContentNID(Node $nid): Node {
        $link = $this->_getLinkL($nid, self::RID_BLOG_CONTENT);
        if ($link !== null)
            $oid = $this->_getOnLinkNID2($link);
        else
            $oid = '0';
        return $this->_cacheInstance->newNode($oid);
    }

    private function _displayBackOrLogin(string $backMessage, string $backView, bool $addBlog = false): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $addURL = '';
        if ($addBlog)
            $addURL = '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID();

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_BACK);
        $instance->setMessage($backMessage);
        $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $backView
            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
            . $addURL);
        $instanceList->addItem($instance);
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID());
            $instanceList->addItem($instance);
        }
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();
    }

    private function _displayAddButton(\Nebule\Library\DisplayList $instanceList, string $message, string $type, string $link, string $title = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage($message);
        $instance->setType($type);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->setLink($link);
        if ($title != '')
            $instance->setIconText($title);
        $instanceList->addItem($instance);
    }

    private function _displayContent(Node $nid, string $hook, string $type = 'post'): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instance->setNID($nid);
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setEnableName(true);
        $instance->setEnableRefs(false);
        $instance->setEnableNID(false);
        $instance->setEnableFlags(true);
        $instance->setEnableFlagProtection(false);
        $instance->setEnableFlagObfuscate(true);
        $instance->setEnableFlagState(false);
        $instance->setEnableFlagEmotions(false);
        $instance->setEnableStatus(true);
        $typeName = ($type == 'post') ? '::post' : '::page';
        $instance->setStatus($typeName);
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        $instance->setEnableLink(true);
        $instance->setSelfHookName($hook);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->display();

        $list = $this->_getListContentOID($nid, 'all');
        foreach ($list as $i => $oid) {
            $instance = $this->_cacheInstance->newNode($oid);
            $this->_displayContentBlock($instance);
        }
    }

    private function _displayContentBlock(Node $oid): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
        $instance->setNID($oid);
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setEnableName(true);
        $instance->setEnableRefs(false);
        $instance->setEnableNID(false);
        $instance->setEnableFlags(true);
        $instance->setEnableFlagProtection(true);
        $instance->setEnableFlagObfuscate(true);
        $instance->setEnableFlagState(true);
        $instance->setEnableFlagEmotions(false);
        $instance->setEnableStatus(true);
        $instance->setStatus('::content');
        $instance->setEnableContent(false);
        $instance->setEnableJS(false);
        $instance->setEnableLink(true);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->display();

        switch ($oid->getType()) {
            case References::REFERENCE_OBJECT_TEXT:
                $this->_displayContentText($oid);
                break;
            case References::REFERENCE_OBJECT_PNG:
            case References::REFERENCE_OBJECT_JPEG:
                $this->_displayContentImage($oid);
                break;
            default:
                $this->_displayNotSupported();
        }
    }

    private function _displayContentText(Node $nid): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $content = \Nebule\Library\DisplayWikiSimple::parse($nid->getContent());
        echo '<div class="text"><p>' . "\n";
        echo $content;
        echo '</p></div>' . "\n";
    }

    private function _displayContentImage(Node $nid): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayContentAnswers(Node $nid): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::answ:list', $this::MODULE_REGISTERED_ICONS[1]);
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) { // FIXME add inside the list
            // Add answer query
            ?>

            <div>
                <h1>New answer</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                          action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                              . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                              . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                              . '&' . self::COMMAND_SELECT_POST . '=' . $nid->getID()
                              . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                              . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(); ?>">
                        <label>
                            <input type="text" class="newanswer"
                                   name="<?php echo self::COMMAND_ACTION_NEW_ANSWER; ?>"
                                   value="Answer"/>
                        </label><br/>
                        <input type="submit"
                               value="Create"/>
                    </form>
                </div>
            </div>
            <?php
        }

        // List of answers
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_LONG);
        $list = $this->_getLinksAnswerOID($nid, 'all');
        foreach ($list as $link) {
            $parsedLink = $link->getParsed();
            $blogAnswerNID = $parsedLink['bl/rl/nid2'];
            $blogAnswerSID = $parsedLink['bs/rs1/eid'];
            $blogInstance = $this->_cacheInstance->newNode($blogAnswerNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setEnableLink(false);
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableStatus(false);
            $instance->setEnableContent(true);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            $instance->setRefs(array($blogAnswerSID));
            $instanceList->addItem($instance);
        }
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->setOnePerLine(true);
        $instanceList->display();
    }



    /*
     * Blogs
     *
     * Definition of a new blog with 'BlogNID' NID:
     *  - f>RID_BLOG_NODE>BlogNID>RID_BLOG_NODE
     * BlogNID should not have content.
     * BlogNID should have a name.
     * BlogNID must not have an update.
     */
    private function _getLinksBlogNID(string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $this->_instanceBlogNodeRID, self::RID_BLOG_NODE, false, $socialClass);
        return $links;
    }
    private function _getListBlogNID(string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksBlogNID($socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountBlogNID(string $socialClass = 'self'): int {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return sizeof($this->_getLinksBlogNID($socialClass));
    }
    private function _setNewBlogNID(string $name, string $default): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceNode = $this->_cacheInstance->newVirtualNode();
        $link = 'f>' . self::RID_BLOG_NODE . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_NODE;
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $instanceBL->addLink($link);
        $instanceBL->signWrite();
        $this->_metrologyInstance->addLog('new blog nid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '24eb5b6b');
        $instanceNode->setName($name);

        if ($default == 'y') {
            $link = 'f>' . $this->_entitiesInstance->getConnectedEntityEID() . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_DEFAULT;
            $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
            $instanceBL->addLink($link);
            $instanceBL->signWrite();
            $this->_metrologyInstance->addLog('new blog is default for eid=' . $this->_entitiesInstance->getConnectedEntityEID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '582ac390');
        }
    }
    private function _getBlogNID(string $nid, string $url): void {
        // TODO
    }
    private function _setSyncBlogNID(string $nid): void {
        // TODO
    }
    private function _getDefaultBlogNID(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (! is_a($this->_entitiesInstance->getGhostEntityInstance(), '\Nebule\Library\Node'))
            return '';
        return $this->_entitiesInstance->getGhostEntityInstance()->getPropertyID(self::RID_BLOG_DEFAULT, 'self');
    }



    /*
     * Posts on blogs
     *
     * On a blog 'BlogNID', definition of a new post with 'PostNID' NID and link to content 'ContentOID' OID:
     *  - f>BlogNID>PostNID>RID_BLOG_POST:
     * PostNID should not have content.
     * PostNID can have a name.
     * PostNID can have an update.
     *  - f>PostNID>ContentOID>RID_BLOG_CONTENT>OrderNID:
     * ContentOID must have content.
     * ContentOID should not have a name.
     * ContentOID can have update.
     * OrderNID reflects the order of a content on the list of contents to display.
     */
    private function _getLinksPostNID(Node $blog, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $blog, self::RID_BLOG_POST, true, $socialClass);
        $this->_metrologyInstance->addLog('size of post list=' . sizeof($links), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'b81aeb71');
        return $links;
    }
    private function _getListPostNID(Node $blog, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksPostNID($blog, $socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountPostNID(Node $blog, string $socialClass = 'self'): int {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return sizeof($this->_getLinksPostNID($blog, $socialClass));
    }
    private function _setNewBlogPost(string $name, string $content): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Create PostNID
        $instanceNode = $this->_cacheInstance->newVirtualNode();
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $this->_metrologyInstance->addLog('new blog post nid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '5e3b8f18');
        $instanceBL->addLink('f>' . $this->_instanceCurrentBlog->getID() . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_POST);
        $instanceBL->signWrite();

        // Create name
        $instanceNode->setName($name);

        // Create PostOID
        $instanceObject = new \Nebule\Library\Node($this->_nebuleInstance, 'new');
        $instanceObject->setWriteContent($content);
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $this->_metrologyInstance->addLog('new blog post nid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'fd1f9a24');
        $instanceBL->addLink('f>' . $instanceNode->getID() . '>' . $instanceObject->getID() . '>' . self::RID_BLOG_CONTENT . '>0010000000000000.none.64');
        $instanceBL->signWrite();

        // Create type
        $instanceObject->setType(\Nebule\Library\References::REFERENCE_OBJECT_TEXT);
    }
    private function _getPostNID(Node $blog, Node $post): void {
        // TODO
    }
    private function _getLinksContentOID(Node $post, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $post, self::RID_BLOG_CONTENT, true, $socialClass);
        $this->_metrologyInstance->addLog('size of content list=' . sizeof($links), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '1ce94445');
        return $links;
    }
    private function _getListContentOID(Node $post, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksContentOID($post, $socialClass);
        return $this->_getOnLinksNID2($links);
    }



    /*
     * Answers of posts on blogs
     *
     * On an post 'PostNID', definition of a new answer with 'AnswerOID' OID:
     *  - f>PostNID>AnswerOID>RID_BLOG_ANSWER
     * AnswerOID must have content.
     * AnswerOID should not have name.
     * AnswerOID can have update.
     * Only one level of answer for now. TODO
     */
    private function _getLinksAnswerOID(Node $post, string $socialClass = 'self'): array {
        $links = array();
        $this->_getLinksF($links, $post, self::RID_BLOG_ANSWER, false, $socialClass);
        return $links;
    }
    private function _getListAnswerNID(Node $post, string $socialClass = 'self'): array {
        $links = $this->_getLinksAnswerOID($post, $socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountAnswerOID(Node $post, string $socialClass = 'self'): int {
        return sizeof($this->_getLinksAnswerOID($post, $socialClass));
    }
    private function _setNewAnswerOID(string $content): void {
        $instanceNode = new \Nebule\Library\Node($this->_nebuleInstance, 'new');
        $instanceNode->setWriteContent($content);
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $this->_metrologyInstance->addLog('new blog post answer oid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '89b709e6');
        $instanceBL->addLink('f>' . $this->_instanceCurrentBlogPost->getID() . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_ANSWER);
        $instanceBL->signWrite();
        $instanceNode->setType(\Nebule\Library\References::REFERENCE_OBJECT_TEXT);
    }
    private function _getAnswerOID(Node $blog, Node $post): void {
        // TODO
    }



    /*
     * Pages on blogs
     *
     * On a blog 'BlogNID', definition of a new page with 'PageNID' NID and link to content 'PageOID' OID:
     *  - f>BlogNID>PageNID>RID_BLOG_PAGE:
     * PageNID should not have content.
     * PageNID can have name.
     * PageNID can have update.
     *  - f>PageNID>ContentOID>RID_BLOG_CONTENT>OrderNID:
     * ContentOID must have content.
     * ContentOID should not have name.
     * ContentOID can have update.
     * OrderNID reflects the order of a content on the list of contents to display.
     */
    private function _getLinksPageNID(Node $blog, string $socialClass = 'self'): array {
        $links = array();
        $this->_getLinksF($links, $blog, self::RID_BLOG_PAGE, true, $socialClass);
        return $links;
    }
    private function _getListPageNID(Node $blog, string $socialClass = 'self'): array {
        $links = $this->_getLinksPageNID($blog, $socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountPageNID(Node $blog, string $socialClass = 'self'): int {
        return sizeof($this->_getLinksPageNID($blog, $socialClass));
    }
    private function _setNewPageNID(string $name, string $content): void {
        if ($this->_instanceCurrentBlog->getID() == '0')
            return;

        // Create PageNID
        $instanceNode = $this->_cacheInstance->newVirtualNode();
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $this->_metrologyInstance->addLog('new blog page nid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '94d38ae6');
        $instanceBL->addLink('f>' . $this->_instanceCurrentBlog->getID() . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_PAGE);
        $instanceBL->signWrite();
        $instanceNode->setName($name);

        // Create PageOID
        $instanceObject = new \Nebule\Library\Node($this->_nebuleInstance, 'new');
        $instanceObject->setWriteContent($content);
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $this->_metrologyInstance->addLog('new blog page nid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'fc4f137c');
        $instanceBL->addLink('f>' . $instanceNode->getID() . '>' . $instanceObject->getID() . '>' . self::RID_BLOG_CONTENT . '>0010000000000000.none.64');
        $instanceBL->signWrite();
        $instanceObject->setType(\Nebule\Library\References::REFERENCE_OBJECT_TEXT);
    }
    private function _getPageNID(Node $blog, Node $page): void {
        // TODO
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::login' => 'Se connecter',
            '::posts' => 'Posts',
            '::post' => 'Post',
            '::content' => 'Content',
            '::pages' => 'Pages',
            '::page' => 'Page',
            '::answers' => 'Réponses',
            '::confirm' => 'Confirmation',
            '::objects:ModuleName' => 'Module des blogs',
            '::objects:MenuName' => 'Blogs',
            '::objects:ModuleDescription' => 'Module de gestion des blogs.',
            '::objects:ModuleHelp' => "Ce module permet de voir et de gérer les blogs.",
            '::blog:disp' => 'Affiche le blog',
            '::blog:list' => 'Liste des blogs',
            '::blog:listall' => 'Liste tous les blogs',
            '::blog:new' => 'Nouveau blog',
            '::CreateBlog' => 'Créer le blog',
            '::blog:getExisting' => 'Prend un blog existant',
            '::blog:mod' => 'Modifier le blog',
            '::blog:del' => 'Supprimer le blog',
            '::blog:sync' => 'Synchronise le blog',
            '::blog:return' => 'Revenir au blog',
            '::blog:default' => 'Blog par défaut',
            '::post:list' => 'Liste des posts',
            '::post:disp' => 'Affiche le post',
            '::post:new' => 'Nouveau post',
            '::post:mod' => 'Modifier le post',
            '::post:del' => 'Supprimer le post',
            '::post:sync' => 'Synchronise le post',
            '::page:list' => 'Liste des pages',
            '::page:disp' => 'Affiche la page',
            '::page:new' => 'Nouvelle page',
            '::page:mod' => 'Modifier la page',
            '::page:del' => 'Supprimer la page',
            '::page:sync' => 'Synchronise la page',
            '::answ:list' => 'Liste des réponses',
            '::about:title' => 'A propos',
        ],
        'en-en' => [
            '::login' => 'Connecting',
            '::posts' => 'Posts',
            '::post' => 'Post',
            '::content' => 'Content',
            '::pages' => 'Pages',
            '::page' => 'Page',
            '::answers' => 'Answers',
            '::confirm' => 'Confirmation',
            '::objects:ModuleName' => 'Blogs module',
            '::objects:MenuName' => 'Blogs',
            '::objects:ModuleDescription' => 'Blogs management module.',
            '::objects:ModuleHelp' => 'This module permit to see and manage blogs.',
            '::blog:disp' => 'Display blog',
            '::blog:list' => 'List blogs',
            '::blog:listall' => 'List all blogs',
            '::blog:new' => 'New blog',
            '::CreateBlog' => 'Create the blog',
            '::blog:getExisting' => 'Get existing blog',
            '::blog:mod' => 'Modify blog',
            '::blog:del' => 'Delete blog',
            '::blog:sync' => 'Synchronize blog',
            '::blog:return' => 'Return to blog',
            '::blog:default' => 'Default blog',
            '::post:list' => 'List posts',
            '::post:disp' => 'Display post',
            '::post:new' => 'New post',
            '::post:mod' => 'Modify post',
            '::post:del' => 'Delete post',
            '::post:sync' => 'Synchronize post',
            '::page:list' => 'List pages',
            '::page:disp' => 'Display page',
            '::page:new' => 'New page',
            '::page:mod' => 'Modify page',
            '::page:del' => 'Delete page',
            '::page:sync' => 'Synchronize page',
            '::answ:list' => 'List answers',
            '::about:title' => 'About',
        ],
        'es-co' => [
            '::login' => 'Connecting',
            '::posts' => 'Posts',
            '::post' => 'Post',
            '::content' => 'Content',
            '::pages' => 'Pages',
            '::page' => 'Page',
            '::answers' => 'Answers',
            '::confirm' => 'Confirmation',
            '::objects:ModuleName' => 'Módulo de blogs',
            '::objects:MenuName' => 'Blogs',
            '::objects:ModuleDescription' => 'Módulo de gestión de blogs.',
            '::objects:ModuleHelp' => 'This module permit to see and manage blogs.',
            '::blog:disp' => 'Display blog',
            '::blog:list' => 'List blogs',
            '::blog:listall' => 'List all blogs',
            '::blog:new' => 'New blog',
            '::CreateBlog' => 'Create the blog',
            '::blog:getExisting' => 'Get existing blog',
            '::blog:mod' => 'Modify blog',
            '::blog:del' => 'Delete blog',
            '::blog:sync' => 'Synchronize blog',
            '::blog:return' => 'Return to blog',
            '::blog:default' => 'Default blog',
            '::post:list' => 'List posts',
            '::post:disp' => 'Display post',
            '::post:new' => 'New post',
            '::post:mod' => 'Modify post',
            '::post:del' => 'Delete post',
            '::post:sync' => 'Synchronize post',
            '::page:list' => 'List pages',
            '::page:disp' => 'Display page',
            '::page:new' => 'New page',
            '::page:mod' => 'Modify page',
            '::page:del' => 'Delete page',
            '::page:sync' => 'Synchronize page',
            '::answ:list' => 'List answers',
            '::about:title' => 'About',
        ],
    ];
}
