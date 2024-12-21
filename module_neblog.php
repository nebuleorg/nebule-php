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
    protected string $MODULE_COMMAND_NAME = 'log';
    protected string $MODULE_DEFAULT_VIEW = 'blog';
    protected string $MODULE_DESCRIPTION = '::neblog:module:objects:ModuleDescription';
    protected string $MODULE_VERSION = '020241221';
    protected string $MODULE_AUTHOR = 'Projet nebule';
    protected string $MODULE_LICENCE = '(c) GLPv3 nebule 2024-2024';
    protected string $MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    protected string $MODULE_HELP = '::neblog:module:objects:ModuleHelp';
    protected string $MODULE_INTERFACE = '3.0';

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


    const DEFAULT_COMMAND_ACTION_NOM = 'actaddnam';
    const DEFAULT_COMMAND_ACTION_RID = 'actaddnam';
    const COMMAND_ACTION_NEW_BLOG_NAME = 'actnewblogname';
    const COMMAND_ACTION_NEW_BLOG_TITLE = 'actnewblogtitle';
    private $_actionAddBlogName = '';
    private $_actionAddBlogTitle = '';
    private $_actionChangeBlog = false;

    public function action(): void
    {
        $this->_extractActionAddBlog();
        if ($this->_actionChangeBlog) {
            $this->_actionAddBlog();
        }
    }

    private function _extractActionAddBlog(): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add blog', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd59dbd21');

            $arg_name = trim(filter_input(INPUT_POST, self::COMMAND_ACTION_NEW_BLOG_NAME, FILTER_SANITIZE_STRING));
            $arg_title = trim(filter_input(INPUT_POST, self::COMMAND_ACTION_NEW_BLOG_TITLE, FILTER_SANITIZE_STRING));

            if ($arg_name != '') {
                $this->_actionAddBlogName = $arg_name;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add blog name:' . $arg_name, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '2ae0f501');
            }
            if (Node::checkNID($arg_title)) {
                $this->_actionAddBlogTitle = $arg_title;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Extract action add blog title:' . $arg_title, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '3376510b');
            }

            if ($this->_actionAddBlogName != ''
                && $this->_actionAddBlogTitle != ''
            ) {
                $this->_actionChangeBlog = true;
            }
        }
    }

    private function _actionAddBlog(): void
    {
        global $bootstrapApplicationIID;

        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_unlocked
        ) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Action add blog', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '047b0bdc');

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



    private function _getInstanceBlogRID(): Node
    {
        return $this->_cacheInstance->newNode(self::RID_BLOG_NODE);
    }

    private function _getInstanceBlogItemRID(): Node
    {
        return $this->_cacheInstance->newNode(self::RID_BLOG_ITEM);
    }

    private function _getListBlogsOID(): array
    {
        $instance = $this->_getInstanceBlogRID();
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => self::RID_BLOG_NODE,
            'bl/rl/nid3' => self::RID_BLOG_NODE,
            'bl/rl/nid4' => '',
        );
        $instance->getLinks($links, $filter);
        $list = array();
        foreach ($links as $link) {
            $parsedLink = $link->getParsed();
            $oid = $parsedLink['bl/rl/nid2'];
            $list[$oid] = $oid;
        }
        return $list;
    }

    private function _getCountBlogs(): int
    {
        $instance = $this->_getInstanceBlogRID();
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => self::RID_BLOG_NODE,
            'bl/rl/nid3' => self::RID_BLOG_NODE,
            'bl/rl/nid4' => '',
        );
        $instance->getLinks($links, $filter);
        return sizeof($links);
    }

    private function _getListBlogItemOID(): array
    {
        $instance = $this->_getInstanceBlogItemRID();
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => self::RID_BLOG_NODE, // FIXME
            'bl/rl/nid3' => self::RID_BLOG_NODE, // FIXME
            'bl/rl/nid4' => '',
        );
        $instance->getLinks($links, $filter);
        $list = array();
        foreach ($links as $link) {
            $parsedLink = $link->getParsed();
            $oid = $parsedLink['bl/rl/nid2'];
            $list[$oid] = $oid;
        }
        return $list;
    }

    private function _getCountBlogItem(): int
    {
        $instance = $this->_getInstanceBlogItemRID();
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => self::RID_BLOG_NODE, // FIXME
            'bl/rl/nid3' => self::RID_BLOG_NODE, // FIXME
            'bl/rl/nid4' => '',
        );
        $instance->getLinks($links, $filter);
        return sizeof($links);
    }

    private function _setNewBlog(): void
    {

    }

    private function _setNewBlogItem(): void
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
