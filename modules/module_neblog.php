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
use Nebule\Library\Modules;
use Nebule\Library\ModelModuleHelp;
use Nebule\Library\ModuleTranslates;

/**
 * This module can manage blogs with articles, pages, and messages in articles.
 *  - Definition of new blog with 'BlogOID' NID:
 *    - f>RID_BLOG_NODE>BlogOID>RID_BLOG_NODE :
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
class ModuleNeblog extends Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'blog';
    const MODULE_DEFAULT_VIEW = 'blog';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260101';
    const MODULE_AUTHOR = 'Project nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2024-2026';
    const MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
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
        'rblog',    // 17
        'rpost',    // 18
        'rpage',    // 19
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
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
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
    const RID_OWNER = 'cf05011fbc65673e7da4c80072d24d8ecc00c900dc17a8141333e9324379ad6f667f.none.272';
    const RID_WRITER = '419b45938214772de54332940dc8606d99fe8a50961172b0717d960624502614c718.none.272';
    const RID_FOLLOWER = '3679a2435d005752d2698dafaa0a42f57f7bd4022e442a3b0fbb0d6191507b697fbd.none.272';

    private string $_actionAddBlogName = '';
    private string $_actionAddBlogDefault = '';
    private string $_actionGetBlogOID = '';
    private string $_actionGetBlogURL = '';
    private string $_actionSyncBlogOID = '';
    private string $_actionAddPostName = '';
    private string $_actionAddPostContent = '';
    private string $_actionAddAnswerContent = '';
    private string $_actionAddPageName = '';
    private string $_actionAddPageContent = '';
    private ?\Nebule\Library\node $_instanceBlogNodeRID = null;
    private ?\Nebule\Library\node $_instanceBlogPostRID = null;
    private ?\Nebule\Library\node $_instanceBlogAnswerRID = null;
    private ?\Nebule\Library\node $_instanceBlogPageRID = null;
    private ?\Nebule\Library\node $_instanceCurrentBlog = null;
    private ?\Nebule\Library\node $_instanceCurrentBlogPost = null;
    private ?\Nebule\Library\node $_instanceCurrentBlogPage = null;
    private array $_currentBlogListFounders = array();
    private array $_currentBlogListOwners = array();
    private array $_currentBlogWritersList = array();
    private array $_currentBlogFollowersList = array();

    protected function _initialisation(): void {
        $this->_instanceBlogNodeRID = $this->_cacheInstance->newNode(self::RID_BLOG_NODE);
        $this->_instanceBlogPostRID = $this->_cacheInstance->newNode(self::RID_BLOG_POST);
        $this->_instanceBlogAnswerRID = $this->_cacheInstance->newNode(self::RID_BLOG_ANSWER);
        $this->_instanceBlogPageRID = $this->_cacheInstance->newNode(self::RID_BLOG_PAGE);

        $this->_getCurrentBlog();
        $this->_getCurrentBlogPost();
        $this->_getCurrentBlogPage();
        $this->_getCurrentBlogFounders();
        $this->_getCurrentBlogSocialList();
    }

    private function _getCurrentBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->getFilterInput(self::COMMAND_SELECT_BLOG);
        if ($nid == '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlog');
        if ($nid == '')
            $nid = $this->_getDefaultBlogOID();
        if ($nid == '') { // Default is the first blog
            $list = $this->_getListBlogOID();
            if (sizeof($list) != 0) {
                reset($list);
                $nid = current($list);
            }
        }
        $this->_instanceCurrentBlog = $this->_cacheInstance->newNode($nid);
        $this->_metrologyInstance->addLog('extract current blog nid=' . $this->_instanceCurrentBlog->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '184e42c6');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlog', $nid);
    }

    private function _getCurrentBlogPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->getFilterInput(self::COMMAND_SELECT_POST);
        if ($nid == '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPost');
        $this->_instanceCurrentBlogPost = $this->_cacheInstance->newNode($nid);
        $this->_metrologyInstance->addLog('extract current blog nid=' . $this->_instanceCurrentBlog->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'df3fcf87');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlogPost', $nid);
    }

    private function _getCurrentBlogPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->getFilterInput(self::COMMAND_SELECT_PAGE);
        if ($nid == '')
            $nid = $this->_sessionInstance->getSessionStoreAsString('instanceCurrentBlogPage');
        $this->_instanceCurrentBlogPage = $this->_cacheInstance->newNode($nid);
        $this->_metrologyInstance->addLog('extract current blog nid=' . $this->_instanceCurrentBlog->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'c7298189');
        $this->_sessionInstance->setSessionStoreAsString('instanceCurrentBlogPage', $nid);
    }

    private function _getCurrentBlogFounders(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $oid = $this->_instanceCurrentBlog->getID();
        if ($oid == '0')
            return;
        $content = $this->_ioInstance->getObject($oid);
        if ($content == '')
            return;
        $eid = '';
        foreach (explode("\n", $content) as $line) {
            $l = trim($line);

            if ($l == '' || str_starts_with($l, '#'))
                continue;

            $nameOnFile = trim((string)filter_var(strtok($l, '='), FILTER_SANITIZE_STRING));
            $value = trim((string)filter_var(strtok('='), FILTER_SANITIZE_STRING));
            if ($nameOnFile == 'eid')
                $eid = $value;
        }
        if (! \Nebule\Library\Node::checkNID($eid, false, false))
            return;
        $this->_metrologyInstance->addLog('extract current blog owner eid=' . $eid, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '0cdd6bb5');
        $this->_currentBlogListOwners = array($eid => $eid);
        $this->_currentBlogListFounders = array($eid => $eid);
    }

    private function _getCurrentBlogSocialList(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (sizeof($this->_currentBlogListFounders) == 0)
            return;

        $instance = new \Nebule\Library\Group($this->_nebuleInstance, $this->_instanceCurrentBlog->getID());
        //if (!$instance->getMarkClosedGroup())
        //    return;
        $this->_currentBlogListOwners = $instance->getListTypedMembersID(self::RID_OWNER, 'onlist', $this->_currentBlogListFounders);
        foreach ($this->_currentBlogListFounders as $eid)
            $this->_currentBlogListOwners[$eid] = $eid;
foreach ($this->_currentBlogListOwners as $eid)
$this->_metrologyInstance->addLog('DEBUGGING blog owner eid=' . $eid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $this->_currentBlogWritersList = $instance->getListTypedMembersID(self::RID_WRITER, 'onlist', $this->_currentBlogListOwners);
foreach ($this->_currentBlogWritersList as $eid)
$this->_metrologyInstance->addLog('DEBUGGING blog writer eid=' . $eid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $this->_currentBlogFollowersList = $instance->getListTypedMembersID(self::RID_FOLLOWER, 'onlist', $this->_currentBlogListOwners);
foreach ($this->_currentBlogFollowersList as $eid)
$this->_metrologyInstance->addLog('DEBUGGING blog follower eid=' . $eid, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
    }



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array {
        $hookArray = array();
        switch ($hookName) {
            /*case 'menu':
                $hookArray[1]['name'] = '::blog:list';
                $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                $hookArray[1]['desc'] = '';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;*/
            case 'selfMenu':
            case 'selfMenuNeblog':
                # List blogs of ghost entity
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[1]['name'] = '::blog:list';
                    $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # New blog
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]
                    && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[2]['name'] = '::blog:new';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[2];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # Get existing blog
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]
                    && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
                    $hookArray[3]['name'] = '::blog:getExisting';
                    $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[6];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # Come back to the blog
                if (($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[7]
                    || $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[11])
                    && $this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[4]['name'] = '::blog:disp';
                    $hookArray[4]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[4]['desc'] = '';
                    $hookArray[4]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # List pages
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[0]
                    && $this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[5]['name'] = '::page:list';
                    $hookArray[5]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[5]['desc'] = '';
                    $hookArray[5]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
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
                    $hookArray[6]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[13]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                # List all blogs for all entities
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[] = array(
                            'name' => '::blog:listall',
                            'icon' => $this::MODULE_REGISTERED_ICONS[1],
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[16]
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }

                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[0]) {
                    // Blog rights
                    $hookArray[] = array(
                            'name' => '::rights',
                            'icon' => Displays::DEFAULT_ICON_IMODIFY,
                            'desc' => '::blog:rights',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[17]
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }

                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[7]) {
                    // Blog rights
                    $hookArray[] = array(
                            'name' => '::rights',
                            'icon' => Displays::DEFAULT_ICON_IMODIFY,
                            'desc' => '::post:rights',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[18]
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . self::COMMAND_SELECT_POST . '=' . $this->_instanceCurrentBlogPost->getID()
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }

                if ($this->_displayInstance->getCurrentDisplayView() == self::MODULE_REGISTERED_VIEWS[11]) {
                    // Blog rights
                    $hookArray[] = array(
                            'name' => '::rights',
                            'icon' => Displays::DEFAULT_ICON_IMODIFY,
                            'desc' => '::page:rights',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[19]
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . self::COMMAND_SELECT_POST . '=' . $this->_instanceCurrentBlogPost->getID()
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
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
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
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
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[8]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    if ($this->_configurationInstance->checkBooleanOptions(array('permitSynchronizeLink', 'permitSynchronizeObject'))) {
                        $hookArray[1]['name'] = '::blog:sync';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                            . '&' . self::COMMAND_ACTION_SYNC_BLOG
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                    }
                }
                if ($this->_instanceCurrentBlog->getID() != '0') {
                    $hookArray[3]['name'] = '::page:list';
                    $hookArray[3]['icon'] = Displays::DEFAULT_ICON_LSTOBJ;
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
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
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
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
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
                        . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                        . '&' . self::COMMAND_SELECT_POST . '=' . $this->_instanceCurrentBlogPage->getID()
                        . '&' . self::COMMAND_ACTION_SYNC_PAGE
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
            case 'rightsBlogOwner':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'unlocked'))) {
                    $hookArray[] = array(
                            'name' => '::removeAsOwner',
                            'icon' => Displays::DEFAULT_ICON_LX,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . \Nebule\Library\ActionsGroups::REMOVE_MEMBER . '=' . $nid
                                    . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . self::RID_OWNER
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                    . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
                break;
            case 'rightsBlogWriter':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'unlocked')) && isset($this->_currentBlogListOwners[$this->_entitiesInstance->getConnectedEntityEID()])) {
                    $hookArray[] = array(
                            'name' => '::addAsOwner',
                            'icon' => Displays::DEFAULT_ICON_ADDENT,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $nid
                                    . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . self::RID_OWNER
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                    . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                    $hookArray[] = array(
                            'name' => '::removeAsWriter',
                            'icon' => Displays::DEFAULT_ICON_LX,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . \Nebule\Library\ActionsGroups::REMOVE_MEMBER . '=' . $nid
                                    . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . self::RID_WRITER
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                    . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
                break;
            case 'rightsBlogFollower':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'unlocked')) && isset($this->_currentBlogListOwners[$this->_entitiesInstance->getConnectedEntityEID()])) {
                    $hookArray[] = array(
                            'name' => '::addAsWriter',
                            'icon' => Displays::DEFAULT_ICON_ADDENT,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $nid
                                    . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . self::RID_WRITER
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                    . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                    $hookArray[] = array(
                            'name' => '::removeAsFollower',
                            'icon' => Displays::DEFAULT_ICON_LX,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . \Nebule\Library\ActionsGroups::REMOVE_MEMBER . '=' . $nid
                                    . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . self::RID_FOLLOWER
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                    . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
                break;
            case 'rightsBlogAny':
                if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'unlocked')) && isset($this->_currentBlogListOwners[$this->_entitiesInstance->getConnectedEntityEID()])) {
                    $hookArray[] = array(
                            'name' => '::addAsWriter',
                            'icon' => Displays::DEFAULT_ICON_ADDENT,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $nid
                                    . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . self::RID_WRITER
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                    . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                    $hookArray[] = array(
                            'name' => '::addAsFollower',
                            'icon' => Displays::DEFAULT_ICON_ADDOBJ,
                            'desc' => '',
                            'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_displayInstance->getCurrentDisplayView()
                                    . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_instanceCurrentBlog->getID()
                                    . '&' . \Nebule\Library\ActionsGroups::ADD_MEMBER . '=' . $nid
                                    . '&' . \Nebule\Library\ActionsGroups::TYPED_MEMBER . '=' . self::RID_FOLLOWER
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                    . $this->_tokenizeInstance->getActionTokenCommand(),
                    );
                }
                break;
        }
        return $hookArray;
    }


    public function displayModule(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                if ($this->_getDefaultBlogOID() != '' || $this->_instanceCurrentBlog->getID() != '0')
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
            //case $this::MODULE_REGISTERED_VIEWS[16]: // Common parts
            case $this::MODULE_REGISTERED_VIEWS[17]:
                $this->_displayRightsBlog();
                break;
            case $this::MODULE_REGISTERED_VIEWS[18]:
                $this->_displayRightsPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[19]:
                $this->_displayRightsPage();
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
                $this->_display_InlineBlogs('onlist');
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
                $this->_display_InlineBlogs('all');
                break;
            case $this::MODULE_REGISTERED_VIEWS[17]:
                $this->_display_InlineRightsBlog();
                break;
            case $this::MODULE_REGISTERED_VIEWS[18]:
                $this->_display_InlineRightsPost();
                break;
            case $this::MODULE_REGISTERED_VIEWS[19]:
                $this->_display_InlineRightsPage();
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

        $list = $this->_getLinksBlogOID('all');
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

        if (sizeof($this->_currentBlogListOwners) != 0) {
            $this->_socialInstance->setList(array($this->_currentBlogListOwners), 'onlist');
            $linksPost = $this->_getLinksPostNID($this->_instanceCurrentBlog, 'onlist');
            $this->_socialInstance->unsetList('onlist');
        } else
            $linksPost = $this->_getLinksPostNID($this->_instanceCurrentBlog, 'all');

        $this->_displaySimpleTitle('::posts', $this::MODULE_REGISTERED_ICONS[1]);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        foreach ($linksPost as $link) {
            $parsedLink = $link->getParsed();
            $postPostNID = $parsedLink['bl/rl/nid2'];
            $postInstance = $this->_cacheInstance->newNode($postPostNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($postInstance);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
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
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        if ($this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::blog:new');
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[6]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::blog:getExisting');
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
        } else {
            $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_PLAY);
            $instance->setMessage('::login');
            $instance->setLink('?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=2'
                . '&' . References::COMMAND_APPLICATION_BACK . '=' . $this->_routerInstance->getApplicationIID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID());
        }
        $instanceList->addItem($instance);
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('blogs');
    }

    private function _getBlogListByRight(array &$links, string $right): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid2' => $this->_entitiesInstance->getGhostEntityEID(),
            'bl/rl/nid3' => $right,
        );
        $this->_entitiesInstance->getGhostEntityInstance()->getLinks($links, $filter, 'all'); // FIXME $socialClass = self?
    }

    private function _display_InlineBlogs(string $socialClass): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $linksBlog2 = array();
        if ($socialClass == 'all')
            $linksBlog1 = $this->_getLinksBlogOID('all');
        else {
            $this->_socialInstance->setList(array($this->_entitiesInstance->getGhostEntityEID()), 'onlist');
            $linksBlog1 = $this->_getLinksBlogOID('onlist');
            $this->_socialInstance->unsetList('onlist');
            $this->_getBlogListByRight($linksBlog2, self::RID_OWNER);
            $this->_getBlogListByRight($linksBlog2, self::RID_WRITER);
            $this->_getBlogListByRight($linksBlog2, self::RID_FOLLOWER);
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
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
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
    }

    private function _displayNewBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::blog:new', $this::MODULE_REGISTERED_ICONS[2]);
        $this->_displayBackOrLogin('::blog:list', $this::MODULE_REGISTERED_VIEWS[1]);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_STRING);
            $instance->setInputName(self::COMMAND_ACTION_NEW_BLOG_NAME);
            $instance->setIconText('nebule/objet/nom');
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand());
            $instance->setIconRID(\Nebule\Library\DisplayItemIconMessage::ICON_WARN_RID);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_SELECT);
            $instance->setInputName(self::COMMAND_ACTION_NEW_BLOG_DEFAULT);
            $instance->setIconText('::blog:default');
            $instance->setSelectList(array(
                'y' => $this->_translateInstance->getTranslate('::yes'),
                'n' => $this->_translateInstance->getTranslate('::no'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new \Nebule\Library\DisplayQuery($this->_applicationInstance);
            $instance->setType(\Nebule\Library\DisplayQuery::QUERY_TEXT);
            $instance->setMessage('::CreateBlog');
            $instance->setInputValue('');
            $instance->setInputName(self::COMMAND_ACTION_NEW_BLOG_DEFAULT);
            $instance->setIconText('::confirm');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(true);
            $instance->setWithSubmit(true);
            $instanceList->addItem($instance);

            $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
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
                        action="<?php echo '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
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
                        action="<?php echo '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
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
        $this->_displayAddButton($instanceList, '::blog:return', \Nebule\Library\DisplayItemIconMessage::TYPE_BACK,
            '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
            . '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID()
            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID());
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
            $instanceIcon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[2]);
            $instance->setIcon($instanceIcon);
            $instance->setMessage('::page:new');
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[13]
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
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty(false);
        $instanceList->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('pages');
    }

    private function _display_InlinePages(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);

        $list = $this->_getListPageNID($this->_instanceCurrentBlog, 'all');
        foreach ($list as $blogPageNID) {
            $blogInstance = $this->_cacheInstance->newNode($blogPageNID);
            $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
            $instance->setNID($blogInstance);
            $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[11]
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
        $this->_displayBackOrLogin('::page:list', $this::MODULE_REGISTERED_VIEWS[12], true);

        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) {
            ?>

            <div>
                <h1>New page</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[12]
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

    private function _displayRightsBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::rights', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLogin('::blog:return', $this::MODULE_REGISTERED_VIEWS[0], true);
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('blog_rights');
    }

    private function _display_InlineRightsBlog(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
        foreach ($this->_entitiesInstance->getListEntitiesInstances() as $entityInstance) {
            $eid = $entityInstance->getID();
            if (($this->_entitiesInstance->getConnectedEntityIsUnlocked() && isset($this->_currentBlogListOwners[$this->_entitiesInstance->getConnectedEntityEID()]))
                    || isset($this->_currentBlogListOwners[$eid])
                    || isset($this->_currentBlogWritersList[$eid])
                    || isset($this->_currentBlogFollowersList[$eid])
            ) {
                $instance = new \Nebule\Library\DisplayObject($this->_applicationInstance);
                $instance->setNID($entityInstance);
                $instance->setIcon($instanceIcon);
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instance->setEnableName(true);
                $instance->setEnableFlags(false);
                $instance->setEnableFlagState(false);
                $instance->setEnableFlagEmotions(false);
                $instance->setEnableStatus(true);
                $instance->setEnableContent(false);
                $instance->setEnableJS(false);
                if (isset($this->_currentBlogListFounders[$eid])) {
                    $instance->setSelfHookName('rightsBlogFounder');
                    $instance->setStatus('::founder');
                }
                elseif (isset($this->_currentBlogListOwners[$eid])) {
                    $instance->setSelfHookName('rightsBlogOwner');
                    $instance->setStatus('::owner');
                }
                elseif (isset($this->_currentBlogWritersList[$eid])) {
                    $instance->setSelfHookName('rightsBlogWriter');
                    $instance->setStatus('::writer');
                }
                elseif (isset($this->_currentBlogFollowersList[$eid])) {
                    $instance->setSelfHookName('rightsBlogFollower');
                    $instance->setStatus('::follower');
                }
                else
                    $instance->setSelfHookName('rightsBlogAny');
                $instanceList->addItem($instance);
            }
        }
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->display();
    }

    private function _displayRightsPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::rights', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLogin('::post:list', $this::MODULE_REGISTERED_VIEWS[7], true);
        $this->_displayOwner();
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('post_rights');
    }

    private function _display_InlineRightsPost(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_displayNotImplemented(); // TODO
    }

    private function _displayRightsPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::rights', $this::MODULE_REGISTERED_ICONS[3]);
        $this->_displayBackOrLogin('::page:list', $this::MODULE_REGISTERED_VIEWS[11], true);
        $this->_displayOwner();
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('page_rights');
    }

    private function _display_InlineRightsPage(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_displayNotImplemented(); // TODO
    }



    public function actions(): void {
        if ($this->_extractActionAddBlog())
            $this->_setNewBlogOID($this->_actionAddBlogName, $this->_actionAddBlogDefault);
        if ($this->_extractActionGetBlog())
            $this->_getBlogOID($this->_actionGetBlogOID, $this->_actionGetBlogURL);
        if ($this->_extractActionSyncBlog())
            $this->_setSyncBlogOID($this->_actionSyncBlogOID);
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

            $this->_actionGetBlogOID = $this->getFilterInput(self::COMMAND_ACTION_GET_BLOG_NID);
            $this->_actionGetBlogURL = $this->getFilterInput(self::COMMAND_ACTION_GET_BLOG_URL);
            if ($this->_actionGetBlogOID != '' && $this->_actionGetBlogURL != '') {
                $this->_metrologyInstance->addLog('extract action get blog NID:' . $this->_actionGetBlogOID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '8c10a115');
                return true;
            }
        }
        return false;
    }

    private function _extractActionSyncBlog(): bool {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked', 'token'))) {
            $this->_metrologyInstance->addLog('extract action sync blog', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9de747eb');

            $this->_actionSyncBlogOID = $this->getFilterInput(self::COMMAND_ACTION_SYNC_BLOG_NID);
            if ($this->_actionSyncBlogOID != '') {
                $this->_metrologyInstance->addLog('extract action sync blog NID:' . $this->_actionSyncBlogOID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'f53d731c');
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
        return $this->_cacheInstance->newNode($oid);
    }

    private function _displayBackOrLogin(string $backMessage, string $backView, bool $addBlog = false): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $addURL = '';
        if ($addBlog)
            $addURL = '&' . self::COMMAND_SELECT_BLOG . '=' . $this->_instanceCurrentBlog->getID();

        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_BACK);
        $instance->setMessage($backMessage);
        $instance->setLink('?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
            . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $backView
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
            $instance = $this->_cacheInstance->newNode($oid);
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
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitWriteObject', 'unlocked'))) { // FIXME add inside the list
            // Add answer query
            ?>

            <div>
                <h1>New answer</h1>
                <div>
                    <form enctype="multipart/form-data" method="post"
                          action="<?php echo '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                              . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[7]
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
        $instanceList->setSize(\Nebule\Library\DisplayItem::SIZE_SMALL);
        $instanceList->setEnableWarnIfEmpty();
        $instanceList->setOnePerLine(true);
        $instanceList->display();
    }



    /*
     * Blogs
     *
     * Definition of a new blog with 'BlogOID' NID:
     *  - f>RID_BLOG_NODE>BlogOID>RID_BLOG_NODE
     * BlogOID must have content with eid value.
     * BlogOID should have a name.
     * BlogOID must not have an update.
     */
    private function _getLinksBlogOID(string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = array();
        $this->_getLinksF($links, $this->_instanceBlogNodeRID, self::RID_BLOG_NODE, false, $socialClass);
        return $links;
    }
    private function _getListBlogOID(string $socialClass = 'self'): array {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $links = $this->_getLinksBlogOID($socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountBlogOID(string $socialClass = 'self'): int {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return sizeof($this->_getLinksBlogOID($socialClass));
    }
    private function _setNewBlogOID(string $name, string $default): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $content = 'eid=' . $this->_entitiesInstance->getConnectedEntityEID() . "\nsalt=" . bin2hex($this->_cryptoInstance->getRandom(64, \Nebule\Library\Crypto::RANDOM_PSEUDO));
        $instanceNode = new \Nebule\Library\Node($this->_nebuleInstance, '0');
        $instanceNode->setContent($content);
        if ($instanceNode->getID() == '0')
            return;
        $instanceNode->write();
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

        $instanceGroup = new \Nebule\Library\Group($this->_nebuleInstance, $instanceNode->getID());
        $instanceGroup->setMarkClosed();
    }
    private function _getBlogOID(string $nid, string $url): void {
        // TODO
    }
    private function _setSyncBlogOID(string $nid): void {
        // TODO
    }
    private function _getDefaultBlogOID(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (! is_a($this->_entitiesInstance->getGhostEntityInstance(), '\Nebule\Library\Node'))
            return '';
        return $this->_entitiesInstance->getGhostEntityInstance()->getPropertyID(self::RID_BLOG_DEFAULT, 'self');
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
        $links = array();
        $this->_getLinksF($links, $post, self::RID_BLOG_ANSWER, false, $socialClass);
        return $links;
    }
    private function _getListAnswerNID(\Nebule\Library\Node $post, string $socialClass = 'self'): array {
        $links = $this->_getLinksAnswerOID($post, $socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountAnswerOID(\Nebule\Library\Node $post, string $socialClass = 'self'): int {
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
        $links = array();
        $this->_getLinksF($links, $blog, self::RID_BLOG_PAGE, true, $socialClass);
        return $links;
    }
    private function _getListPageNID(\Nebule\Library\Node $blog, string $socialClass = 'self'): array {
        $links = $this->_getLinksPageNID($blog, $socialClass);
        return $this->_getOnLinksNID2($links);
    }
    private function _getCountPageNID(\Nebule\Library\Node $blog, string $socialClass = 'self'): int {
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
    private function _getPageNID(\Nebule\Library\Node $blog, \Nebule\Library\Node $page): void {
        // TODO
    }

    /**
     * @return void
     */
    public function _displayOwner(): void {
        $this->_displaySimpleTitle('::owners', Displays::DEFAULT_ICON_ENT);
        $instanceList = new \Nebule\Library\DisplayList($this->_applicationInstance);
        $instanceIcon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_USER);
        foreach ($this->_currentBlogListOwners as $eid) {
            $instanceOwner = $this->_cacheInstance->newNode($eid, \Nebule\Library\Cache::TYPE_ENTITY);
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
        'fr-fr' => [
            '::ModuleName' => 'Module des blogs',
            '::MenuName' => 'Blogs',
            '::ModuleDescription' => 'Module de gestion des blogs',
            '::ModuleHelp' => 'Ce module permet de voir et de gérer les blogs.',
            '::AppTitle1' => 'Blogs',
            '::AppDesc1' => 'Gestion des blogs',
            '::posts' => 'Posts',
            '::post' => 'Post',
            '::content' => 'Content',
            '::pages' => 'Pages',
            '::page' => 'Page',
            '::answers' => 'Réponses',
            '::rights' => 'Permissions',
            '::owners' => 'Propriétaires',
            '::owner' => 'Propriétaire',
            '::founder' => 'Fondateur',
            '::writers' => 'Écrivains',
            '::writer' => 'Écrivain',
            '::followers' => 'Abonnés',
            '::follower' => 'Abonné',
            '::addAsOwner' => 'Ajouter comme propriétaire',
            '::addAsWriter' => 'Ajouter comme écrivain',
            '::addAsFollower' => 'Ajouter comme abonné',
            '::removeAsOwner' => 'Retirer des propriétaires',
            '::removeAsWriter' => 'Retirer des écrivains',
            '::removeAsFollower' => 'Retirer des abonnés',
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
            '::blog:rights' => 'Permissions sur le blog',
            '::post:list' => 'Liste des posts',
            '::post:disp' => 'Affiche le post',
            '::post:new' => 'Nouveau post',
            '::post:mod' => 'Modifier le post',
            '::post:del' => 'Supprimer le post',
            '::post:sync' => 'Synchronise le post',
            '::post:rights' => 'Permissions sur le post',
            '::page:list' => 'Liste des pages',
            '::page:disp' => 'Affiche la page',
            '::page:new' => 'Nouvelle page',
            '::page:mod' => 'Modifier la page',
            '::page:del' => 'Supprimer la page',
            '::page:sync' => 'Synchronise la page',
            '::page:rights' => 'Permissions sur la page',
            '::answ:list' => 'Liste des réponses',
            '::about:title' => 'A propos',
        ],
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
            '::rights' => 'Authorizations',
            '::owners' => 'Owners',
            '::owner' => 'Owner',
            '::founder' => 'Founder',
            '::writers' => 'Writers',
            '::writer' => 'Writer',
            '::followers' => 'Followers',
            '::follower' => 'Follower',
            '::addAsOwner' => 'Add as owner',
            '::addAsWriter' => 'Add as writer',
            '::addAsFollower' => 'Add as follower',
            '::removeAsOwner' => 'Remove as owner',
            '::removeAsWriter' => 'Remove as writer',
            '::removeAsFollower' => 'Remove as follower',
            '::blog:disp' => 'Display the blog',
            '::blog:list' => 'List of blogs',
            '::blog:listall' => 'List of all blogs',
            '::blog:new' => 'New blog',
            '::CreateBlog' => 'Create the blog',
            '::blog:getExisting' => 'Get an existing blog',
            '::blog:mod' => 'Modify the blog',
            '::blog:del' => 'Delete the blog',
            '::blog:sync' => 'Synchronize the blog',
            '::blog:return' => 'Return to the blog',
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
        'es-co' => [
            '::ModuleName' => 'Módulo de blogs',
            '::MenuName' => 'Blogs',
            '::ModuleDescription' => 'Módulo de gestión de blogs',
            '::ModuleHelp' => 'Este módulo permite ver blogs, gestionar los relacionados y cambiar el contenido actual de un blog.',
            '::AppTitle1' => 'Blogs',
            '::AppDesc1' => 'Administrar Blogs',
            '::posts' => 'Publicaciones',
            '::post' => 'Publicación',
            '::content' => 'Contenido',
            '::pages' => 'Páginas',
            '::page' => 'Página',
            '::answers' => 'Respuestas',
            '::rights' => 'Autorizaciones',
            '::owners' => 'Propietarios',
            '::owner' => 'Propietario',
            '::founder' => 'Fundador',
            '::writers' => 'Escritores',
            '::writer' => 'Escritor',
            '::followers' => 'Seguidores',
            '::follower' => 'Seguidor',
            '::addAsOwner' => 'Añadir como propietario',
            '::addAsWriter' => 'Añadir como escritor',
            '::addAsFollower' => 'Añadir como seguidor',
            '::removeAsOwner' => 'Eliminar como propietario',
            '::removeAsWriter' => 'Eliminar como escritor',
            '::removeAsFollower' => 'Eliminar como seguidor',
            '::blog:disp' => 'Mostrar el blog',
            '::blog:list' => 'Lista de blogs',
            '::blog:listall' => 'Lista de todos los blogs',
            '::blog:new' => 'Nuevo blog',
            '::CreateBlog' => 'Crear el blog',
            '::blog:getExisting' => 'Obtener un blog existente',
            '::blog:mod' => 'Modificar el blog',
            '::blog:del' => 'Eliminar el blog',
            '::blog:sync' => 'Sincronizar el blog',
            '::blog:return' => 'Volver al blog',
            '::blog:default' => 'Blog por defecto',
            '::blog:rights' => 'Autorizaciones en el blog',
            '::post:list' => 'Lista de publicaciones',
            '::post:disp' => 'Mostrar publicación',
            '::post:new' => 'Nueva publicación',
            '::post:mod' => 'Modificar la publicación',
            '::post:del' => 'Eliminar la publicación',
            '::post:sync' => 'Sincronizar la publicación',
            '::post:rights' => 'Autorizaciones en la publicación',
            '::page:list' => 'Lista de páginas',
            '::page:disp' => 'Mostrar página',
            '::page:new' => 'Nueva página',
            '::page:mod' => 'Modificar la página',
            '::page:del' => 'Eliminar la página',
            '::page:sync' => 'Sincronizar la página',
            '::page:rights' => 'Autorizaciones en la página',
            '::answ:list' => 'Lista de respuestas',
            '::about:title' => 'Acerca de',
        ],
    ];
}
