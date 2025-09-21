<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Neblog\Action;
use Nebule\Library;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
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
    const MODULE_NAME = '::neblog:module:objects:ModuleName';
    const MODULE_MENU_NAME = '::neblog:module:objects:MenuName';
    const MODULE_COMMAND_NAME = 'blog';
    const MODULE_DEFAULT_VIEW = 'blog';
    const MODULE_DESCRIPTION = '::neblog:module:objects:ModuleDescription';
    const MODULE_VERSION = '020250920';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2024-2025';
    const MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    const MODULE_HELP = '::neblog:module:objects:ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'blog',    // 0
        'blogs',   // 1
        'newblog', // 2
        'modblog', // 3
        'delblog', // 4
        'getblog', // 5
        'synblog', // 6
        'post',    // 7
        'newpost', // 8
        'modpost', // 9
        'delpost', // 10
        'page',    // 11
        'pages',   // 12
        'newpage', // 13
        'modpage', // 14
        'delpage', // 15
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
    const MODULE_APP_TITLE_LIST = array('::neblog:module:objects:ModuleName');
    const MODULE_APP_ICON_LIST = array('26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256');
    const MODULE_APP_DESC_LIST = array('::neblog:module:objects:ModuleDescription');
    const MODULE_APP_VIEW_LIST = array('blog');

    const COMMAND_SELECT_BLOG = 'blog';
    const COMMAND_SELECT_POST = 'post';
    const COMMAND_SELECT_ANSWER = 'answ';
    const COMMAND_SELECT_PAGE= 'page';
    const COMMAND_ACTION_NEW_BLOG_NAME = 'actionnewblogname';
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
        if ($nid == '') { // Default is first blog
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
                # List blogs
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[1]['name'] = '::neblog:module:blog:list';
                    $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                }
                # New blog
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]
                    && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[2]['name'] = '::neblog:module:blog:new';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                }
                # Get existing blog
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]
                    && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[3]['name'] = '::neblog:module:blog:get';
                    $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                }
                # Come back to blog
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[7]
                    || $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[11]) {
                    $hookArray[4]['name'] = '::neblog:module:blog:disp';
                    $hookArray[4]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[4]['desc'] = '';
                    $hookArray[4]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                }
                # List pages
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[0]
                    && $this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[5]['name'] = '::neblog:module:page:list';
                    $hookArray[5]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[5]['desc'] = '';
                    $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                }
                # New page
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[12]
                    && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[6]['name'] = '::neblog:module:page:new';
                    $hookArray[6]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                    $hookArray[6]['desc'] = '';
                    $hookArray[6]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[13]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                }
            case 'selfMenuBlogs':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[1]['name'] = '::neblog:module:blog:mod';
                    $hookArray[1]['icon'] = Displays::DEFAULT_ICON_HELP;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                    $hookArray[2]['name'] = '::neblog:module:blog:del';
                    $hookArray[2]['icon'] = Displays::DEFAULT_ICON_LD;
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                    if ($this->_configurationInstance->checkBooleanOptions(array('permitSynchronizeLink', 'permitSynchronizeObject'))) {
                        $hookArray[3]['name'] = '::neblog:module:blog:sync';
                        $hookArray[3]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                        $hookArray[3]['desc'] = '';
                        $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                            . '&' . self::COMMAND_ACTION_SYNC_BLOG
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                    }
                }
                break;
            case 'selfMenuBlog':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[0]['name'] = '::neblog:module:post:new';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_ADDOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[8]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                    if ($this->_configurationInstance->checkBooleanOptions(array('permitSynchronizeLink', 'permitSynchronizeObject'))) {
                        $hookArray[1]['name'] = '::neblog:module:blog:sync';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                            . '&' . self::COMMAND_ACTION_SYNC_BLOG
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                    }
                }
                $hookArray[3]['name'] = '::neblog:module:page:list';
                $hookArray[3]['icon'] = Displays::DEFAULT_ICON_LSTOBJ;
                $hookArray[3]['desc'] = '';
                $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                break;
            case 'selfMenuPost':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitSynchronizeLink', 'permitSynchronizeObject', 'unlocked'))) {
                    $hookArray[0]['name'] = '::neblog:module:post:sync';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . self::COMMAND_SELECT_POST . '=' . $this->_instanceCurrentBlogPost->getID()
                        . '&' . self::COMMAND_ACTION_SYNC_POST
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                }
                break;
            case 'selfMenuPage':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitSynchronizeLink', 'permitSynchronizeObject', 'unlocked'))) {
                    $hookArray[0]['name'] = '::neblog:module:page:sync';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . self::COMMAND_SELECT_POST . '=' . $this->_instanceCurrentBlogPage->getID()
                        . '&' . self::COMMAND_ACTION_SYNC_PAGE
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                }
                break;
            case 'menu':
                $hookArray[0]['name'] = '::neblog:module:blog:list';
                $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID();
                break;
        }
        return $hookArray;
    }


    public function displayModule(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                if ($this->_instanceCurrentBlog->getID() != '0')
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
        $this->_displaySimpleTitle('::neblog:module:blog:disp', $this::MODULE_REGISTERED_ICONS[0]);
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
        $instanceObject->setEnableFlagState(false);
        $instanceObject->setEnableFlagEmotions(false);
        $instanceObject->setEnableStatus(false);
        $instanceObject->setEnableContent(false);
        $instanceObject->setEnableJS(false);
        $instanceObject->setEnableLink(true);
        $instanceObject->setSelfHookName('selfMenuBlog');
        $instanceObject->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceObject->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('blog');
    }

    private function _display_InlineBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // TODO sync blog with arg self::COMMAND_ACTION_SYNC_BLOG

        $this->_displaySimpleTitle('::posts', $this::MODULE_REGISTERED_ICONS[1]);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $list = $this->_getLinksPostNID($this->_instanceCurrentBlog);
        foreach ($list as $link) {
            $parsedLink = $link->getParsed();
            $blogPostNID = $parsedLink['bl/rl/nid2'];
            $blogPostSID = $parsedLink['bs/rs1/eid'];
            $blogInstance = $this->_cacheInstance->newNode($blogPostNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                . '&' . self::COMMAND_SELECT_POST . '=' . $blogPostNID
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            $instance->setRefs(array($blogPostSID));
            $instance->setEnableStatus(true);
            $instance->setStatus($this->_translateInstance->getTranslate('::answers') . ':' . $this->_getCountAnswerOID($blogInstance));
            $instanceList->addItem($instance);
        }
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->setOnePerLine(true);
        $instanceList->display();
    }

    private function _displayBlogs(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:blog:list', $this::MODULE_REGISTERED_ICONS[1]);

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::neblog:module:blog:new');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID());
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[6]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::neblog:module:blog:get');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID());
        } else {
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::neblog:module:login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_displayInstance->getCurrentApplicationIID());
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
        $list = $this->_getLinksBlogNID();
        foreach ($list as $link) {
            $parsedLink = $link->getParsed();
            $blogNID = $parsedLink['bl/rl/nid2'];
            $blogSID = $parsedLink['bs/rs1/eid'];
            $blogInstance = $this->_cacheInstance->newNode($blogNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            $instance->setRefs(array($blogSID));
            $instance->setSelfHookName('selfMenuBlogs');
            $instance->setEnableStatus(true);
            $instance->setStatus(
                $this->_translateInstance->getTranslate('::pages') . ':' . $this->_getCountPageNID($blogInstance)
                . ' '
                . $this->_translateInstance->getTranslate('::posts') . ':' . $this->_getCountPostNID($blogInstance)
            );
            $instanceList->addItem($instance);
        }
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }

    private function _displayNewBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:blog:new', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLogin('::neblog:module:blog:list', $this::MODULE_REGISTERED_VIEWS[1]);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            ?>

            <div>
                <h1>New blog</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(); ?>">
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
    }

    private function _displayModBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:blog:mod', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLogin('::neblog:module:blog:disp', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayDelBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:blog:del', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayBackOrLogin('::neblog:module:blog:disp', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayGetBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:blog:get', $this::MODULE_REGISTERED_ICONS[6]);
        $this->_displayBackOrLogin('::neblog:module:blog:list', $this::MODULE_REGISTERED_VIEWS[1]);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            ?>

            <div>
                <h1>New blog</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
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
        $this->_displaySimpleTitle('::neblog:module:blog:sync', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLogin('::neblog:module:blog:list', $this::MODULE_REGISTERED_VIEWS[1]);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:post:disp', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_displayBackOrLogin('::neblog:module:blog:return', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('post');
    }

    private function _display_InlinePost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayContent($this->_instanceCurrentBlogPost, 'selfMenuPost', 'post');
        $this->_displayContentAnswers($this->_instanceCurrentBlogPost);
    }

    private function _displayNewPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:post:new', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLogin('::neblog:module:post:list', $this::MODULE_REGISTERED_VIEWS[0], true);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            ?>

            <div>
                <h1>New post</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
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
        $this->_displaySimpleTitle('::neblog:module:post:mod', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLogin('::neblog:module:blog:return', $this::MODULE_REGISTERED_VIEWS[7], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayDelPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:post:del', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayBackOrLogin('::neblog:module:blog:return', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:page:disp', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_displayBackOrLogin('::neblog:module:blog:return', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('page');
    }

    private function _display_InlinePage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayContent($this->_instanceCurrentBlogPage, 'selfMenuPage', 'page');
        $this->_displayContentAnswers($this->_instanceCurrentBlogPage);
    }

    private function _displayPages(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:page:list', $this::MODULE_REGISTERED_ICONS[4]);

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $this->_displayAddButton($instanceList, '::neblog:module:blog:return', \Nebule\Library\DisplayItemIconMessage::TYPE_BACK,
            '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID());
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::neblog:module:page:new');
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[13]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID());
            $instanceList->addItem($instance);
        } elseif ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject'))) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::neblog:module:login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_displayInstance->getCurrentApplicationIID()
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID());
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

        $list = $this->_getListPageNID($this->_instanceCurrentBlog);
        foreach ($list as $blogPageNID) {
            $blogInstance = $this->_cacheInstance->newNode($blogPageNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                . '&' . self::COMMAND_SELECT_PAGE . '=' . $blogPageNID
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID());
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
        $this->_displaySimpleTitle('::neblog:module:page:new', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLogin('::neblog:module:page:list', $this::MODULE_REGISTERED_VIEWS[12], true);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            ?>

            <div>
                <h1>New page</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
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
        $this->_displaySimpleTitle('::neblog:module:page:mod', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLogin('::neblog:module:page:list', $this::MODULE_REGISTERED_VIEWS[12], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayDelPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::neblog:module:page:del', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayBackOrLogin('::neblog:module:page:list', $this::MODULE_REGISTERED_VIEWS[12], true);
        $this->_displayNotImplemented(); // TODO
    }



    public function actions(): void {
        if ($this->_extractActionAddBlog())
            $this->_setNewBlogNID($this->_actionAddBlogName);
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
            if ($this->_actionAddBlogName != '') {
                $this->_metrologyInstance->addLog('extract action add blog name:' . $this->_actionAddBlogName, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2ae0f501');
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
    private function _getLinksF(array &$links, Node $nid1, string $nid3, bool $withOrder = false): void {
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
        $nid1->getLinks($links, $filter);
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
            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
            . $addURL);
        $instanceList->addItem($instance);
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::neblog:module:login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_displayInstance->getCurrentApplicationIID());
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

        $list = $this->_getListContentOID($nid);
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
        $this->_displaySimpleTitle('::neblog:module:answ:list', $this::MODULE_REGISTERED_ICONS[1]);
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
                              . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_displayInstance->getCurrentApplicationIID()
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
        $list = $this->_getLinksAnswerOID($nid);
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
    private function _getLinksBlogNID(): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $this->_instanceBlogNodeRID, self::RID_BLOG_NODE);
        return $links;
    }
    private function _getListBlogNID(): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksBlogNID();
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountBlogNID(): int {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return sizeof($this->_getLinksBlogNID());
    }
    private function _setNewBlogNID(string $name): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceNode = $this->_cacheInstance->newVirtualNode();
        $link = 'f>' . self::RID_BLOG_NODE . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_NODE;
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $instanceBL->addLink($link);
        $instanceBL->signWrite();
        $this->_metrologyInstance->addLog('new blog nid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '24eb5b6b');
        $instanceNode->setName($name);
    }
    private function _getBlogNID(string $nid, string $url): void {
        // TODO
    }
    private function _setSyncBlogNID(string $nid): void {
        // TODO
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
    private function _getLinksPostNID(Node $blog): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $blog, self::RID_BLOG_POST, true);
        $this->_metrologyInstance->addLog('size of post list=' . sizeof($links), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'b81aeb71');
        return $links;
    }
    private function _getListPostNID(Node $blog): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksPostNID($blog);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountPostNID(Node $blog): int {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return sizeof($this->_getLinksPostNID($blog));
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
    private function _getLinksContentOID(Node $post): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $post, self::RID_BLOG_CONTENT, true);
        $this->_metrologyInstance->addLog('size of content list=' . sizeof($links), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '1ce94445');
        return $links;
    }
    private function _getListContentOID(Node $post): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksContentOID($post);
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
    private function _getLinksAnswerOID(Node $post): array {
        $links = array();
        $this->_getLinksF($links, $post, self::RID_BLOG_ANSWER);
        return $links;
    }
    private function _getListAnswerNID(Node $post): array {
        $links = $this->_getLinksAnswerOID($post);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountAnswerOID(Node $post): int {
        return sizeof($this->_getLinksAnswerOID($post));
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
    private function _getLinksPageNID(Node $blog): array {
        $links = array();
        $this->_getLinksF($links, $blog, self::RID_BLOG_PAGE, true);
        return $links;
    }
    private function _getListPageNID(Node $blog): array {
        $links = $this->_getLinksPageNID($blog);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountPageNID(Node $blog): int {
        return sizeof($this->_getLinksPageNID($blog));
    }
    private function _setNewPageNID(string $name, string $content): void {
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
            '::neblog:module:login' => 'Se connecter',
            '::posts' => 'Posts',
            '::post' => 'Post',
            '::content' => 'Content',
            '::pages' => 'Pages',
            '::page' => 'Page',
            '::answers' => 'Réponses',
            '::neblog:module:objects:ModuleName' => 'Module des blogs',
            '::neblog:module:objects:MenuName' => 'Blogs',
            '::neblog:module:objects:ModuleDescription' => 'Module de gestion des blogs.',
            '::neblog:module:objects:ModuleHelp' => "Ce module permet de voir et de gérer les blogs.",
            '::neblog:module:blog:disp' => 'Affiche le blog',
            '::neblog:module:blog:list' => 'Liste des blogs',
            '::neblog:module:blog:new' => 'Nouveau blog',
            '::neblog:module:blog:get' => 'Prend un blog existant',
            '::neblog:module:blog:mod' => 'Modifier le blog',
            '::neblog:module:blog:del' => 'Supprimer le blog',
            '::neblog:module:blog:sync' => 'Synchronise le blog',
            '::neblog:module:blog:return' => 'Revenir au blog',
            '::neblog:module:post:list' => 'Liste des posts',
            '::neblog:module:post:disp' => 'Affiche le post',
            '::neblog:module:post:new' => 'Nouveau post',
            '::neblog:module:post:mod' => 'Modifier le post',
            '::neblog:module:post:del' => 'Supprimer le post',
            '::neblog:module:post:sync' => 'Synchronise le post',
            '::neblog:module:page:list' => 'Liste des pages',
            '::neblog:module:page:disp' => 'Affiche la page',
            '::neblog:module:page:new' => 'Nouvelle page',
            '::neblog:module:page:mod' => 'Modifier la page',
            '::neblog:module:page:del' => 'Supprimer la page',
            '::neblog:module:page:sync' => 'Synchronise la page',
            '::neblog:module:answ:list' => 'Liste des réponses',
            '::neblog:module:about:title' => 'A propos',
        ],
        'en-en' => [
            '::neblog:module:login' => 'Connecting',
            '::posts' => 'Posts',
            '::post' => 'Post',
            '::content' => 'Content',
            '::pages' => 'Pages',
            '::page' => 'Page',
            '::answers' => 'Answers',
            '::neblog:module:objects:ModuleName' => 'Blogs module',
            '::neblog:module:objects:MenuName' => 'Blogs',
            '::neblog:module:objects:ModuleDescription' => 'Blogs management module.',
            '::neblog:module:objects:ModuleHelp' => 'This module permit to see and manage blogs.',
            '::neblog:module:blog:disp' => 'Display blog',
            '::neblog:module:blog:list' => 'List blogs',
            '::neblog:module:blog:new' => 'New blog',
            '::neblog:module:blog:get' => 'Get existing blog',
            '::neblog:module:blog:mod' => 'Modify blog',
            '::neblog:module:blog:del' => 'Delete blog',
            '::neblog:module:blog:sync' => 'Synchronize blog',
            '::neblog:module:blog:return' => 'Return to blog',
            '::neblog:module:post:list' => 'List posts',
            '::neblog:module:post:disp' => 'Display post',
            '::neblog:module:post:new' => 'New post',
            '::neblog:module:post:mod' => 'Modify post',
            '::neblog:module:post:del' => 'Delete post',
            '::neblog:module:post:sync' => 'Synchronize post',
            '::neblog:module:page:list' => 'List pages',
            '::neblog:module:page:disp' => 'Display page',
            '::neblog:module:page:new' => 'New page',
            '::neblog:module:page:mod' => 'Modify page',
            '::neblog:module:page:del' => 'Delete page',
            '::neblog:module:page:sync' => 'Synchronize page',
            '::neblog:module:answ:list' => 'List answers',
            '::neblog:module:about:title' => 'About',
        ],
        'es-co' => [
            '::neblog:module:login' => 'Connecting',
            '::posts' => 'Posts',
            '::post' => 'Post',
            '::content' => 'Content',
            '::pages' => 'Pages',
            '::page' => 'Page',
            '::answers' => 'Answers',
            '::neblog:module:objects:ModuleName' => 'Módulo de blogs',
            '::neblog:module:objects:MenuName' => 'Blogs',
            '::neblog:module:objects:ModuleDescription' => 'Módulo de gestión de blogs.',
            '::neblog:module:objects:ModuleHelp' => 'This module permit to see and manage blogs.',
            '::neblog:module:blog:disp' => 'Display blog',
            '::neblog:module:blog:list' => 'List blogs',
            '::neblog:module:blog:new' => 'New blog',
            '::neblog:module:blog:get' => 'Get existing blog',
            '::neblog:module:blog:mod' => 'Modify blog',
            '::neblog:module:blog:del' => 'Delete blog',
            '::neblog:module:blog:sync' => 'Synchronize blog',
            '::neblog:module:blog:return' => 'Return to blog',
            '::neblog:module:post:list' => 'List posts',
            '::neblog:module:post:disp' => 'Display post',
            '::neblog:module:post:new' => 'New post',
            '::neblog:module:post:mod' => 'Modify post',
            '::neblog:module:post:del' => 'Delete post',
            '::neblog:module:post:sync' => 'Synchronize post',
            '::neblog:module:page:list' => 'List pages',
            '::neblog:module:page:disp' => 'Display page',
            '::neblog:module:page:new' => 'New page',
            '::neblog:module:page:mod' => 'Modify page',
            '::neblog:module:page:del' => 'Delete page',
            '::neblog:module:page:sync' => 'Synchronize page',
            '::neblog:module:answ:list' => 'List answers',
            '::neblog:module:about:title' => 'About',
        ],
    ];
}
