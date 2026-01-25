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
 * This module can manage blogs with articles, pages, and messages in articles.
 *  - Definition of new blog with 'BlogOID' NID:
 *    - f>RID_BLOG_NODE>BlogOID>RID_BLOG_NODE : FIXME changed to a typed group
 * BlogOID must have content with eid value.
 * BlogOID should have name.
 * BlogOID must not have update.
 *  - Definition of the default blog for an entity :
 *    - l>Entity_EID>BlogOID>RID_BLOG_DEFAULT
 *  - On a blog 'BlogOID', definition of a new post with 'PostNID' NID and link to content 'ContentOID' OID:
 *    - f>BlogOID>PostNID>RID_BLOG_POST :
 * PostNID should not have content.
 * PostNID can have name.
 * PostNID can have update.
 *    - f>PostNID>ContentOID>RID_BLOG_CONTENT>OrderNID :
 * ContentOID must have content.
 * ContentOID should not have name.
 * ContentOID can have update.
 * OrderNID reflect the order of a content on the list of contents to display.
 *  - On a post 'PostNID', definition of a new answer with 'AnswerOID' OID:
 *    - f>PostNID>AnswerOID>RID_BLOG_ANSWER :
 * AnswerOID must have content.
 * AnswerOID should not have name.
 * AnswerOID can have update.
 * Only one level of answer for now. TODO
 *  - On a blog 'BlogOID', definition of a new page with 'PageNID' NID and link to content 'PageOID' OID:
 *    - f>BlogOID>PageNID>RID_BLOG_PAGE :
 * PageNID should not have content.
 * PageNID can have name.
 * PageNID can have update.
 *    - l>PageNID>PageOID>RID_BLOG_CONTENT : FIXME add OrderNID
 * PageOID must have content.
 * PageOID should not have name.
 * PageOID can have update.
 *  - A blog can be linked to entities (EID) or groups (GroupNID) to add rights:
 *    - f>BlogOID>EID>RID_owner
 *    - f>BlogOID>EID>RID_writer
 *    - f>BlogOID>EID>RID_follower
 *    - f>BlogOID>GroupNID>RID_owner TODO
 *    - f>BlogOID>GroupNID>RID_writer TODO
 *    - f>BlogOID>GroupNID>RID_follower TODO
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleNeblog extends Module
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'blog';
    const MODULE_DEFAULT_VIEW = 'blog';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260112';
    const MODULE_AUTHOR = 'Project nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2024-2026';
    const MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'blogs',        // 0
        'blog',         // 1
        'new_blog',     // 2
        'mod_blog',     // 3
        'del_blog',     // 4
        'get_blog',     // 5
        'syn_blog',     // 6
        'rights_blog',  // 7
        'options',      // 8
        'post',         // 9
        'new_post',     // 10
        'mod_post',     // 11
        'del_post',     // 12
        'page',         // 13
        'pages',        // 14
        'new_page',     // 15
        'mod_page',     // 16
        'del_page',     // 17
    );
    const MODULE_REGISTERED_ICONS = array(
        Displays::DEFAULT_ICON_LO,
        Displays::DEFAULT_ICON_LSTOBJ,
        Displays::DEFAULT_ICON_ADDOBJ,
        Displays::DEFAULT_ICON_IMODIFY,
        Displays::DEFAULT_ICON_LD,
        Displays::DEFAULT_ICON_HELP,
        Displays::DEFAULT_ICON_LL,
        Displays::DEFAULT_ICON_LX,
        Displays::DEFAULT_ICON_SYNOBJ,
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('blog');

    const RESTRICTED_TYPE = 'blog';
    const RESTRICTED_CONTEXT = 'ad34c6d9368499b303927c616197f653e3a08d37e7803f3cd46fe163114d91d437d9.none.272';
    const COMMAND_SELECT_BLOG = 'blog';
    const COMMAND_SELECT_ITEM = 'blog';
    const COMMAND_SELECT_POST = 'post';
    const COMMAND_SELECT_ANSWER = 'answ';
    const COMMAND_SELECT_PAGE= 'page';
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

    protected string $_actionAddPostName = '';
    protected string $_actionAddPostContent = '';
    protected string $_actionAddAnswerContent = '';
    protected string $_actionAddPageName = '';
    protected string $_actionAddPageContent = '';
    protected ?\Nebule\Library\node $_instanceBlogNodeRID = null;
    protected ?\Nebule\Library\Group $_instanceCurrentBlog = null;
    protected ?\Nebule\Library\Group $_instanceCurrentBlogPost = null;
    protected ?\Nebule\Library\Group $_instanceCurrentBlogPage = null;

    protected function _initialisation(): void {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_socialClass = $this->getFilterInput(Displays::COMMAND_SOCIAL, FILTER_FLAG_ENCODE_LOW);
        $this->_instanceBlogNodeRID = $this->_cacheInstance->newNodeByType(self::RID_BLOG_NODE);

        $this->_getCurrentItem(self::COMMAND_SELECT_BLOG, 'Blog', $this->_instanceCurrentBlog, $this->_getDefaultItem($this::RID_BLOG_DEFAULT));
        if (! is_a($this->_instanceCurrentBlog, 'Nebule\Library\Node') || $this->_instanceCurrentBlog->getID() == '0')
            $this->_instanceCurrentBlog = null;
        $this->_getCurrentBlogPost();
        $this->_getCurrentBlogPage();
        $this->_getCurrentItemFounders($this->_instanceCurrentBlog);
        $this->_getCurrentItemSocialList($this->_instanceCurrentBlog);
        $this->_instanceCurrentItem = $this->_instanceCurrentBlog;
    }

    protected function _getCurrentBlogPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (! is_a($this->_instanceCurrentBlog, 'Nebule\Library\Node'))
            return;
        $nid = $this->getFilterInput(self::COMMAND_SELECT_POST);
        if ($nid == '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPost');
        if ($nid == '')
            return;
        $this->_instanceCurrentBlogPost = $this->_cacheInstance->newNodeByType($nid);
        $this->_metrologyInstance->addLog('extract current blog post nid=' . $this->_instanceCurrentBlogPost->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'df3fcf87');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlogPost', $nid);
    }

    protected function _getCurrentBlogPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (! is_a($this->_instanceCurrentBlog, 'Nebule\Library\Node'))
            return;
        $nid = $this->getFilterInput(self::COMMAND_SELECT_PAGE);
        if ($nid == '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPage');
        if ($nid == '')
            return;
        $this->_instanceCurrentBlogPage = $this->_cacheInstance->newNodeByType($nid);
        $this->_metrologyInstance->addLog('extract current blog page nid=' . $this->_instanceCurrentBlogPage->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'c7298189');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlogPage', $nid);
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $instance = null): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->_applicationInstance->getCurrentObjectID();
        if ($instance !== null)
            $nid = $instance->getID();
        $blogNID = '0';
        if ($this->_instanceCurrentBlog !== null)
            $blogNID = $this->_instanceCurrentBlog->getID();
        $hookArray = $this->getCommonHookList($hookName, $nid, 'Blogs', 'Blog');
        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuNeblog':
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[] = array(
                            'name' => '::rights',
                            'icon' => Displays::DEFAULT_ICON_IMODIFY,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                    if ($this->_unlocked) {
                        $hookArray[] = array(
                                'name' => '::modifyBlog',
                                'icon' => Displays::DEFAULT_ICON_IMODIFY,
                                'desc' => '',
                                'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                    }
                }
                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[8]
                    && $this->_unlocked) {
                    $hookArray[] = array(
                        'name' => '::removeBlog',
                        'icon' => Displays::DEFAULT_ICON_LX,
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                /*if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[0]) {
                    $hookArray[] = array(
                        'name' => '::page:list',
                        'icon' => $this::MODULE_REGISTERED_ICONS[1],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[14]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[14]
                    && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))
                    && $blogNID != '0'
                ) {
                    $hookArray[] = array(
                        'name' => '::page:new',
                        'icon' => $this::MODULE_REGISTERED_ICONS[2],
                        'desc' => '',
                        'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[15]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }*/
                break;

            case 'typeMenuEntity':
                $hookArray[] = array(
                    'name' => '::myBlogs',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . Displays::COMMAND_SOCIAL . '=myself'
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;

            case 'selfMenuBlogs':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[1]['name'] = '::blog:mod';
                    $hookArray[1]['icon'] = Displays::DEFAULT_ICON_HELP;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    $hookArray[2]['name'] = '::blog:del';
                    $hookArray[2]['icon'] = Displays::DEFAULT_ICON_LD;
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[4]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    if ($this->_configurationInstance->checkBooleanOptions(array('permitSynchronizeLink', 'permitSynchronizeObject'))) {
                        $hookArray[3]['name'] = '::blog:sync';
                        $hookArray[3]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                        $hookArray[3]['desc'] = '';
                        $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                            . '&' . self::COMMAND_ACTION_SYNC_BLOG
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    }
                }
                break;
            case 'selfMenuBlog':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))
                    && $blogNID != '0') {
                    $hookArray[0]['name'] = '::post:new';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_ADDOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[10]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    if ($this->_configurationInstance->checkBooleanOptions(array('permitSynchronizeLink', 'permitSynchronizeObject'))) {
                        $hookArray[1]['name'] = '::blog:sync';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                            . '&' . self::COMMAND_ACTION_SYNC_BLOG
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    }
                }
                if ($blogNID != '0') {
                    $hookArray[3]['name'] = '::page:list';
                    $hookArray[3]['icon'] = Displays::DEFAULT_ICON_LSTOBJ;
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[14]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
            case 'selfMenuPost':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitSynchronizeLink', 'permitSynchronizeObject', 'unlocked'))
                    && $blogNID != '0' && $this->_instanceCurrentBlogPost !== null) {
                    $hookArray[0]['name'] = '::post:sync';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                        . '&' . self::COMMAND_SELECT_POST . '=' . $this->_instanceCurrentBlogPost->getID()
                        . '&' . self::COMMAND_ACTION_SYNC_POST
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
            case 'selfMenuPage':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'permitSynchronizeLink', 'permitSynchronizeObject', 'unlocked'))
                    && $blogNID != '0' && $this->_instanceCurrentBlogPage !== null) {
                    $hookArray[0]['name'] = '::page:sync';
                    $hookArray[0]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[13]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
                        . '&' . self::COMMAND_SELECT_POST . '=' . $this->_instanceCurrentBlogPage->getID()
                        . '&' . self::COMMAND_ACTION_SYNC_PAGE
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
        }
        return $hookArray;
    }
    public function getHookFunction(string $hookName, string $item): ?\Nebule\Library\DisplayItemIconMessageSizeable { return null; }


    public function displayModule(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[1]:
                if ($this->_instanceCurrentBlog !== null && $this->_instanceCurrentBlog->getID() != '0')
                    $this->_displayItem('Blog');
                else
                    $this->_displayListItems('Blog', 'Blogs');
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayItemCreateForm('Blog');
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayModifyItem('Blog');
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayRemoveItem('Blog');
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGetItem('Blog', 'Blogs', $this::COMMAND_ACTION_GET_BLOG_NID, $this::COMMAND_ACTION_GET_BLOG_URL);
                break;
            case $this::MODULE_REGISTERED_VIEWS[6]:
                $this->_displaySynchroItem('Blog', $this::COMMAND_ACTION_GET_BLOG_NID);
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_displayRightsItem('Blog');
                break;
            case $this::MODULE_REGISTERED_VIEWS[8]:
                $this->_displayOptions();
                break;
            case $this::MODULE_REGISTERED_VIEWS[9]:
                $this->_displayPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[10]:
                $this->_displayNewPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[11]:
                $this->_displayModPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[12]:
                $this->_displayDelPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[13]:
                $this->_displayPage();
                break;
            case $this::MODULE_REGISTERED_VIEWS[14]:
                $this->_displayPages();
                break;
            case $this::MODULE_REGISTERED_VIEWS[15]:
                $this->_displayNewPage();
                break;
            case $this::MODULE_REGISTERED_VIEWS[16]:
                $this->_displayModPage();
                break;
            case $this::MODULE_REGISTERED_VIEWS[17]:
                $this->_displayDelPage();
                break;
            default:
                $this->_displayListItems('Blog', 'Blogs');
                break;
        }
    }

    public function displayModuleInline(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyItems('Blogs');
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                if ($this->_instanceCurrentBlog !== null && $this->_instanceCurrentBlog->getID() != '0')
                    $this->_display_InlineBlog();
                else
                    $this->_display_InlineMyItems('Blogs');
                break;
            case $this::MODULE_REGISTERED_VIEWS[9]:
                $this->_display_InlinePost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[13]:
                $this->_display_InlinePage();
                break;
            case $this::MODULE_REGISTERED_VIEWS[14]:
                $this->_display_InlinePages();
                break;
            case $this::MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlineRightsItem('Blog');
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



    private function _display_InlineBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $linksPost = array();
        if ($this->_instanceCurrentBlog !== null) {
            if (sizeof($this->_currentItemListOwners) != 0) {
                $this->_socialInstance->setList(array($this->_currentItemListOwners), 'onlist');
                $linksPost = $this->_getLinksPostNID($this->_instanceCurrentBlog, 'onlist');
                $this->_socialInstance->unsetList('onlist');
            } else
                $linksPost = $this->_getLinksPostNID($this->_instanceCurrentBlog, 'all');
        }

        $this->_displaySimpleTitle('::posts', $this::MODULE_REGISTERED_ICONS[1]);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        foreach ($linksPost as $link) {
            $parsedLink = $link->getParsed();
            $postPostNID = $parsedLink['bl/rl/nid2'];
            $postInstance = $this->_cacheInstance->newNodeByType($postPostNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($postInstance);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
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

    /*private function _display_InlineBlogs(string $socialClass): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $linksBlog2 = array();
        if ($socialClass == 'all')
            $linksBlog1 = $this->_getLinksBlogOID('all');
        else {
            $this->_socialInstance->setList(array($this->_entitiesInstance->getGhostEntityEID()), 'onlist');
            $linksBlog1 = $this->_getLinksBlogOID('onlist');
            $this->_socialInstance->unsetList('onlist');
            $this->_getListByRight($linksBlog2, References::RID_OWNER);
            $this->_getListByRight($linksBlog2, References::RID_WRITER);
            $this->_getListByRight($linksBlog2, References::RID_FOLLOWER);
        }

        $list = array();
        foreach ($linksBlog2 as $link)
            $list[$link->getParsed()['bl/rl/nid1']] = $link->getSignersEID();
        foreach ($linksBlog1 as $link)
            $list[$link->getParsed()['bl/rl/nid2']] = $link->getSignersEID();

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($list as $nid => $signers) {
            $blogInstance = $this->_cacheInstance->newNode($nid);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
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
            $instance->setRefs($signers);
            $instance->setSelfHookName('selfMenuBlogs');
            $instance->setEnableStatus(true);
            $instance->setStatus(
                $this->_translateInstance->getTranslate('::pages') . ':' . $this->_getCountPageNID($blogInstance, $socialClass)
                . ' '
                . $this->_translateInstance->getTranslate('::posts') . ':' . $this->_getCountPostNID($blogInstance, $socialClass)
            );
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }*/

    // Called by Modules::_display_InlineMyItems()
    protected function _displayListOfItems(array $links, string $socialClass = 'all', string $hookName = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $galleriesNID = array();
        $galleriesSigners = array();
        foreach ($links as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            if (!$this->_filterItemByType($nid))
                continue;
            $signers = $link->getSignersEID(); // FIXME get all signers
            $galleriesNID[$nid] = $nid;
            foreach ($signers as $signer) {
                $galleriesSigners[$nid][$signer] = $signer;
            }
        }
        $instanceIcon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[0]);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        foreach ($galleriesNID as $nid) {
            $instanceBlog = $this->_cacheInstance->newNodeByType($nid, \Nebule\Library\Cache::TYPE_GROUP);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setSocial($socialClass);
            $instance->setNID($instanceBlog);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $nid
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
            $instance->setEnableColor(true);
            $instance->setEnableIcon(true);
            $instance->setEnableName(true);
            //$instance->setName($instanceBlog->getName('all'));
            $instance->setEnableFlags(false);
            $instance->setEnableFlagState(false);
            $instance->setEnableFlagEmotions(false);
            $instance->setEnableFlagUnlocked(false);
            $instance->setEnableContent(false);
            $instance->setEnableJS(false);
            $instance->setEnableRefs(true);
            if (isset($galleriesSigners[$nid]) && sizeof($galleriesSigners[$nid]) > 0) {
                $instance->setEnableRefs(true);
                $instance->setRefs($galleriesSigners[$nid]);
            } else
                $instance->setEnableRefs(false);
            //$instance->setSelfHookName($hookName);
            $instance->setEnableStatus(true);
            $instance->setStatus(
                $this->_translateInstance->getTranslate('::pages') . ':' . $this->_getCountPageNID($instanceBlog, $socialClass)
                . ' '
                . $this->_translateInstance->getTranslate('::posts') . ':' . $this->_getCountPostNID($instanceBlog, $socialClass)
            );
            $instance->setIcon($instanceIcon);
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instanceList->setEnableWarnIfEmpty(true);
        $instanceList->display();
    }
    protected function _filterItemByType(string $nid): bool { return true; }

    protected function _displayOptions(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented();
    }

    private function _displayPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::post:disp', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_displayBackOrLoginLocal('::returnBlog', $this::MODULE_REGISTERED_VIEWS[1], true);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('post');
    }

    private function _display_InlinePost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_instanceCurrentBlogPost !== null) {
            $this->_displayContent($this->_instanceCurrentBlogPost, 'selfMenuPost', 'post');
            $this->_displayContentAnswers($this->_instanceCurrentBlogPost);
        } else
            $this->_displayNotSupported();
    }

    private function _displayNewPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::post:new', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLoginLocal('::post:list', $this::MODULE_REGISTERED_VIEWS[1], true);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))
            && $this->_instanceCurrentBlog !== null) {
            ?>

            <div>
                <h1>New post</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
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
        } else
            $this->_displayNotSupported();
    }

    private function _displayModPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::post:mod', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLoginLocal('::returnBlog', $this::MODULE_REGISTERED_VIEWS[9], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayDelPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::post:del', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayBackOrLoginLocal('::returnBlog', $this::MODULE_REGISTERED_VIEWS[1], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:disp', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_displayBackOrLoginLocal('::returnBlog', $this::MODULE_REGISTERED_VIEWS[1], true);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('page');
    }

    private function _display_InlinePage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_instanceCurrentBlogPage !== null) {
            $this->_displayContent($this->_instanceCurrentBlogPage, 'selfMenuPage', 'page');
            $this->_displayContentAnswers($this->_instanceCurrentBlogPage);
        } else
            $this->_displayNotSupported();
    }

    private function _displayPages(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:list', $this::MODULE_REGISTERED_ICONS[4]);

        $blogNID = '0';
        if ($this->_instanceCurrentBlog !== null)
            $blogNID = $this->_instanceCurrentBlog->getID();

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $this->_displayAddButton($instanceList, '::returnBlog', \Nebule\Library\DisplayItemIconMessage::TYPE_BACK,
            '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
            . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNodeByType($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::page:new');
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[15]
                . '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID
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
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('pages');
    }

    private function _display_InlinePages(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);

        $list = array();
        if ($this->_instanceCurrentBlog !== null)
            $list = $this->_getListPageNID($this->_instanceCurrentBlog, 'all');
        foreach ($list as $blogPageNID) {
            $blogInstance = $this->_cacheInstance->newNodeByType($blogPageNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[13]
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
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }

    private function _displayNewPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:new', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLoginLocal('::page:list', $this::MODULE_REGISTERED_VIEWS[14], true);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))
            && $this->_instanceCurrentBlog !== null) {
            ?>

            <div>
                <h1>New page</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[14]
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
        } else
            $this->_displayNotSupported();
    }

    private function _displayModPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:mod', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLoginLocal('::page:list', $this::MODULE_REGISTERED_VIEWS[14], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayDelPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::page:del', $this::MODULE_REGISTERED_ICONS[4]);
        $this->_displayBackOrLoginLocal('::page:list', $this::MODULE_REGISTERED_VIEWS[14], true);
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayRightsBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::rights', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLoginLocal('::returnBlog', $this::MODULE_REGISTERED_VIEWS[1], true);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('blog_rights');
    }



    public function actions(): void {
        if ($this->_extractActionAddPost())
            $this->_setNewBlogPost($this->_actionAddPostName, $this->_actionAddPostContent);
        if ($this->_extractActionAddAnswer())
            $this->_setNewAnswerOID($this->_actionAddAnswerContent);
        if ($this->_extractActionAddPage())
            $this->_setNewPageNID($this->_actionAddPageName, $this->_actionAddPageContent);
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
    private function _getLinksF(array &$links, \Nebule\Library\Node $nid1, string $nid3, bool $withOrder = false, string $socialClass = ''): void {
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
        if ($socialClass == 'onlist')
            $this->_socialInstance->setList(array($this->_entitiesInstance->getGhostEntityEID()), 'onlist');
        $nid1->getLinks($links, $filter, $socialClass);
        if ($socialClass == 'onlist')
            $this->_socialInstance->unsetList('onlist');
    }

    private function _getLinkL(\Nebule\Library\Node $nid1, string $nid3): ?\Nebule\Library\linkInterface {
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

    private function _getOnLinkNID2(?\Nebule\Library\linkInterface $link): string {
        if ($link === null)
            return '';
        return $link->getParsed()['bl/rl/nid2'];
    }

    private function _getContentNID(\Nebule\Library\Node $nid): \Nebule\Library\Node {
        $link = $this->_getLinkL($nid, self::RID_BLOG_CONTENT);
        if ($link !== null)
            $oid = $this->_getOnLinkNID2($link);
        else
            $oid = '0';
        return $this->_cacheInstance->newNodeByType($oid);
    }

    private function _displayBackOrLoginLocal(string $backMessage, string $backView, bool $addBlog = false): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $addURL = '';

        $blogNID = '0';
        if ($this->_instanceCurrentBlog !== null)
            $blogNID = $this->_instanceCurrentBlog->getID();

        if ($addBlog)
            $addURL = '&' . self::COMMAND_SELECT_BLOG . '=' . $blogNID;

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_BACK);
        $instance->setMessage($backMessage);
        $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $backView
            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
            . $addURL);
        $instanceList->addItem($instance);
        if (!$this->_unlocked) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID());
            $instanceList->addItem($instance);
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
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

    private function _displayContent(\Nebule\Library\Node $nid, string $hook, string $type = 'post'): void {
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
            $instance = $this->_cacheInstance->newNodeByType($oid);
            $this->_displayContentBlock($instance);
        }
    }

    private function _displayContentBlock(\Nebule\Library\Node $oid): void {
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

    private function _displayContentText(\Nebule\Library\Node $nid): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $content = \Nebule\Library\DisplayWikiSimple::parse($nid->getContent());
        echo '<div class="text"><p>' . "\n";
        echo $content;
        echo '</p></div>' . "\n";
    }

    private function _displayContentImage(\Nebule\Library\Node $nid): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displayNotImplemented(); // TODO
    }

    private function _displayContentAnswers(\Nebule\Library\Node $nid): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::answ:list', $this::MODULE_REGISTERED_ICONS[1]);
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))
            && $this->_instanceCurrentBlog !== null) { // FIXME add inside the list
            // Add answer query
            ?>

            <div>
                <h1>New answer</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                          action="<?php echo '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                              . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[9]
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
        } else
            $this->_displayNotSupported();

        // List of answers
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setRatio(\Nebule\Library\DisplayItem::RATIO_LONG);
        $list = $this->_getLinksAnswerOID($nid, 'all');
        foreach ($list as $link) {
            $parsedLink = $link->getParsed();
            $blogAnswerNID = $parsedLink['bl/rl/nid2'];
            $blogAnswerSID = $parsedLink['bs/rs1/eid'];
            $blogInstance = $this->_cacheInstance->newNodeByType($blogAnswerNID);
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
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->setOnePerLine(true);
        $instanceList->display();
    }



    /*
     * Posts on blogs
     *
     * On a blog 'BlogOID', definition of a new post with 'PostNID' NID and link to content 'ContentOID' OID:
     *  - f>BlogOID>PostNID>RID_BLOG_POST:
     * PostNID should not have content.
     * PostNID can have a name.
     * PostNID can have an update.
     *  - f>PostNID>ContentOID>RID_BLOG_CONTENT>OrderNID:
     * ContentOID must have content.
     * ContentOID should not have a name.
     * ContentOID can have update.
     * OrderNID reflects the order of a content on the list of contents to display.
     */
    private function _getLinksPostNID(\Nebule\Library\Node $blog, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $blog, self::RID_BLOG_POST, true, $socialClass);
        $this->_metrologyInstance->addLog('size of post list=' . sizeof($links), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'b81aeb71');
        return $links;
    }
    private function _getListPostNID(\Nebule\Library\Node $blog, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksPostNID($blog, $socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountPostNID(\Nebule\Library\Node $blog, string $socialClass = 'self'): int {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return sizeof($this->_getLinksPostNID($blog, $socialClass));
    }
    private function _setNewBlogPost(string $name, string $content): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_instanceCurrentBlog !== null)
            return;
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
    private function _getPostNID(\Nebule\Library\Node $blog, \Nebule\Library\Node $post): void {
        // TODO
    }
    private function _getLinksContentOID(\Nebule\Library\Node $post, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $post, self::RID_BLOG_CONTENT, true, $socialClass);
        $this->_metrologyInstance->addLog('size of content list=' . sizeof($links), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '1ce94445');
        return $links;
    }
    private function _getListContentOID(\Nebule\Library\Node $post, string $socialClass = 'self'): array {
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
    private function _getLinksAnswerOID(\Nebule\Library\Node $post, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $post, self::RID_BLOG_ANSWER, false, $socialClass);
        return $links;
    }
    private function _getListAnswerNID(\Nebule\Library\Node $post, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksAnswerOID($post, $socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountAnswerOID(\Nebule\Library\Node $post, string $socialClass = 'self'): int {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return sizeof($this->_getLinksAnswerOID($post, $socialClass));
    }
    private function _setNewAnswerOID(string $content): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_instanceCurrentBlogPost === null)
            return;
        $instanceNode = new \Nebule\Library\Node($this->_nebuleInstance, 'new');
        $instanceNode->setWriteContent($content);
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $this->_metrologyInstance->addLog('new blog post answer oid=' . $instanceNode->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '89b709e6');
        $instanceBL->addLink('f>' . $this->_instanceCurrentBlogPost->getID() . '>' . $instanceNode->getID() . '>' . self::RID_BLOG_ANSWER);
        $instanceBL->signWrite();
        $instanceNode->setType(\Nebule\Library\References::REFERENCE_OBJECT_TEXT);
    }
    private function _getAnswerOID(\Nebule\Library\Node $blog, \Nebule\Library\Node $post): void {
        // TODO
    }



    /*
     * Pages on blogs
     *
     * On a blog 'BlogOID', definition of a new page with 'PageNID' NID and link to content 'PageOID' OID:
     *  - f>BlogOID>PageNID>RID_BLOG_PAGE:
     * PageNID should not have content.
     * PageNID can have name.
     * PageNID can have update.
     *  - f>PageNID>ContentOID>RID_BLOG_CONTENT>OrderNID:
     * ContentOID must have content.
     * ContentOID should not have name.
     * ContentOID can have update.
     * OrderNID reflects the order of a content on the list of contents to display.
     */
    private function _getLinksPageNID(\Nebule\Library\Node $blog, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $blog, self::RID_BLOG_PAGE, true, $socialClass);
        return $links;
    }
    private function _getListPageNID(\Nebule\Library\Node $blog, string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksPageNID($blog, $socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountPageNID(\Nebule\Library\Node $blog, string $socialClass = 'self'): int {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return sizeof($this->_getLinksPageNID($blog, $socialClass));
    }
    private function _setNewPageNID(string $name, string $content): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_instanceCurrentBlog !== null)
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
    private function _getPageNID(\Nebule\Library\Node $blog, \Nebule\Library\Node $page): void {
        // TODO
    }

    /**
     * @return void
     */
    public function _displayOwner(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::owners', Displays::DEFAULT_ICON_ENT);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceIcon = $this->_cacheInstance->newNodeByType(Displays::DEFAULT_ICON_USER);
        foreach ($this->_currentItemListOwners as $eid) {
            $instanceOwner = $this->_cacheInstance->newNodeByType($eid, \Nebule\Library\Cache::TYPE_ENTITY);
            if (is_a($instanceOwner, '\Nebule\Library\Entity') && $instanceOwner->getID() != '0') {
                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setSocial('all');
                $instance->setNID($instanceOwner);
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instance->setEnableName(true);
                $instance->setEnableRefs(false);
                $instance->setEnableFlags(false);
                $instance->setEnableFlagUnlocked(false);
                $instance->setEnableFlagProtection(false);
                $instance->setEnableFlagObfuscate(false);
                $instance->setEnableFlagState(true);
                $instance->setEnableFlagEmotions(false);
                $instance->setEnableStatus(false);
                $instance->setEnableContent(false);
                $instance->setEnableJS(false);
                $instance->setEnableLink(true);
                $instance->setFlagUnlocked($instanceOwner->getHavePrivateKeyPassword());
                $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
                $instance->setIcon($instanceIcon);
                $instanceList->addItem($instance);
            }
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->setOnePerLine(true);
        $instanceList->display();
    }



    CONST TRANSLATE_TABLE = [
        'en-en' => [
            '::ModuleName' => 'Blogs module',
            '::MenuName' => 'Blogs',
            '::ModuleDescription' => 'Blogs management module',
            '::ModuleHelp' => 'This module permit to see blogs, to manage related and to change of current content of a blog.',
            '::AppTitle1' => 'Blogs',
            '::AppDesc1' => 'Manage blogs',
            '::posts' => 'Posts',
            '::post' => 'Post',
            '::content' => 'Content',
            '::pages' => 'Pages',
            '::page' => 'Page',
            '::answers' => 'Answers',
            '::blog:disp' => 'Display the blog',
            '::blog:list' => 'List of blogs',
            '::blog:listall' => 'List of all blogs',
            '::blog:new' => 'New blog',
            '::CreateBlog' => 'Create the blog',
            '::blog:getExisting' => 'Get an existing blog',
            '::blog:mod' => 'Modify the blog',
            '::blog:del' => 'Delete the blog',
            '::blog:sync' => 'Synchronize the blog',
            '::returnBlog' => 'Return to the blog',
            '::blog:default' => 'Default blog',
            '::blog:rights' => 'Authorizations on blog',
            '::post:list' => 'List of posts',
            '::post:disp' => 'Display post',
            '::post:new' => 'New post',
            '::post:mod' => 'Modify the post',
            '::post:del' => 'Delete the post',
            '::post:sync' => 'Synchronize the post',
            '::post:rights' => 'Authorizations on post',
            '::page:list' => 'List of pages',
            '::page:disp' => 'Display page',
            '::page:new' => 'New page',
            '::page:mod' => 'Modify the page',
            '::page:del' => 'Delete the page',
            '::page:sync' => 'Synchronize the page',
            '::page:rights' => 'Authorizations on page',
            '::answ:list' => 'List of answers',
            '::about:title' => 'About',
        ],
    ];
}
