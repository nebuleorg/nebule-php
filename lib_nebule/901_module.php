<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe Modules
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Modules extends Functions implements ModuleInterface
{
    const MODULE_TYPE = 'Model'; // Model | Application | Traduction
    const MODULE_NAME = 'None';
    const MODULE_MENU_NAME = 'None';
    const MODULE_COMMAND_NAME = 'none';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = 'Description';
    const MODULE_VERSION = '020250111';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2025';
    const MODULE_LOGO = '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c.sha2.256';
    const MODULE_HELP = 'Help';
    const MODULE_INTERFACE = '3.0';
    const MODULE_REGISTERED_VIEWS = array('disp');
    const MODULE_REGISTERED_ICONS = array();
    const MODULE_APP_TITLE_LIST = array();
    const MODULE_APP_ICON_LIST = array();
    const MODULE_APP_DESC_LIST = array();
    const MODULE_APP_VIEW_LIST = array();

    const DEFAULT_COMMAND_ACTION_DISPLAY_MODULE = 'name';

    protected ?Applications $_applicationInstance = null;
    protected ?Displays $_displayInstance = null;
    protected ?Translates $_translateInstance = null;
    protected bool $_unlocked = false;

    public function __toString(): string { return $this::MODULE_NAME; }

    protected function _initialisation(): void {
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
    }

    public function getClassName(): string { return static::class; }

    /**
     * Add functionalities by hooks on menu (by module) and nodes (by type).
     *  - On menu:
     *    - selfMenu: inside menu on current module, in white;
     *    - selfMenu<View>: inside menu on current module only on 'View', in white;
     *    - <Module>SelfMenu: inside menu on another module with the name 'Module', in white;
     *    - menu: inside menu on every module, on the end of list, in dark;
     *  - On node:
     *    - typeMenu<Type>: inside menu on node with the 'Type', in white;
     *    - ... FIXME
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array { return array(); }


    /**
     * Part from this module to display on browser.
     * @return void
     */
    public function displayModule(): void {}

    /**
     * Inline part from this module to display on browser, called by primary page on displayModule().
     * @return void
     */
    public function displayModuleInline(): void {}

    private ?string $_commandActionDisplayModuleCache = null;

    public function getExtractCommandDisplayModule(): string
    {
        $return = '';

        if ($this->_commandActionDisplayModuleCache != null)
            return $this->_commandActionDisplayModuleCache;

        if ($this->_displayInstance->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[1]) {
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE, FILTER_SANITIZE_STRING));

            if ($arg != '')
                $return = $arg;

            unset($arg);
        }

        $this->_commandActionDisplayModuleCache = $return;

        return $return;
    }

    public function getCSS(): void
    {
        echo '<style type="text/css">' . "\n";
        $this->headerStyle();
        echo '</style>' . "\n";
    }

    /**
     * Part of CSS from this module to display on browser.
     * @return void
     */
    public function headerStyle(): void {}

    /**
     * Part of JS script from this module to display on browser.
     * @return void
     */
    public function headerScript(): void {}

    /**
     * Part of actions from this module to run before display.
     * @return void
     */
    public function actions(): void {}

    public function getTranslate(string $text, string $lang = ''): string {
        $result = $text;
        if ($this->_translateInstance === null)
            $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();

        if ($lang == '')
            $lang = $this->_translateInstance->getCurrentLanguage();

        if (isset(self::TRANSLATE_TABLE[$lang][$text]))
            $result = self::TRANSLATE_TABLE[$lang][$text];
        return $result;
    }


    /**
     * Créer un lien.
     *
     * @param string  $signer
     * @param string  $date
     * @param string  $action
     * @param string  $source
     * @param string  $target
     * @param string  $meta
     * @param boolean $obfuscate
     * @return void
     */
    protected function _createLink_DEPRECATED(string $signer, string $date, string $action, string $source, string $target, string $meta, bool $obfuscate = false): void
    {
        /*// Génère le lien.
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);

        // Signe le lien.
        $newLink->sign($signer);

        // Si besoin, obfuscation du lien.
        if ($obfuscate) {
            $link->obfuscate();
        }

        // Ecrit le lien.
        $newLink->write();*/
    }

    protected function _displaySimpleTitle(string $title, string $icon): void {
        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
        $instance->setTitle($title);
        $instance->setIconRID($icon);
        $instance->display();
    }

    protected function _displayNotImplemented(): void {
        $instance = new \Nebule\Library\DisplayInformation($this->_applicationInstance);
        $instance->setMessage('::notImplemented');
        $instance->setType(\Nebule\Library\DisplayItemIconMessage::TYPE_WARN);
        $instance->setSize(\Nebule\Library\DisplayItem::SIZE_MEDIUM);
        $instance->setRatio(\Nebule\Library\DisplayItem::RATIO_SHORT);
        $instance->display();
    }

    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::nebule:modules::ModuleName' => 'Module des modules',
            '::nebule:modules::MenuName' => 'Modules',
            '::nebule:modules::ModuleDescription' => 'Module de gestion des modules.',
            '::nebule:modules::ModuleHelp' => 'Cette application permet de voir les modules détectés par sylabe.',
            '::nebule:modules::AppTitle1' => 'Modules',
            '::nebule:modules::AppDesc1' => 'Module de gestion des modules.',
        ],
        'en-en' => [
            '::nebule:modules::ModuleName' => 'Module of modules',
            '::nebule:modules::MenuName' => 'Modules',
            '::nebule:modules::ModuleDescription' => 'Module to manage modules.',
            '::nebule:modules::ModuleHelp' => 'This application permit to see modules detected by sylabe.',
            '::nebule:modules::AppTitle1' => 'Modules',
            '::nebule:modules::AppDesc1' => 'Manage modules.',
        ],
        'es-co' => [
            '::nebule:modules::ModuleName' => 'Module of modules',
            '::nebule:modules::MenuName' => 'Modules',
            '::nebule:modules::ModuleDescription' => 'Module to manage modules.',
            '::nebule:modules::ModuleHelp' => 'This application permit to see modules detected by sylabe.',
            '::nebule:modules::AppDesc1' => 'Manage modules.',
            '::nebule:modules::AppTitle1' => 'Modules',
        ],
    ];


    /**
     * Affiche la partie menu de la documentation.
     * Inclu dans les applications.
     *
     * @return void
     */
    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#oam">OAM / Module</a>
            <ul>
                <li><a href="#oamn">OAMN / Nommage</a></li>
                <li><a href="#oamp">OAMP / Protection</a></li>
                <li><a href="#oamd">OAMD / Dissimulation</a></li>
                <li><a href="#oaml">OAML / Liens</a></li>
                <li><a href="#oamc">OAMC / Création</a></li>
                <li><a href="#oamu">OAMU / Mise à Jour</a></li>
                <li><a href="#oams">OAMS / Stockage</a></li>
                <li><a href="#oamt">OAMT / Transfert</a></li>
                <li><a href="#oamr">OAMR / Réservation et références</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     * Inclu dans les applications.
     *
     * @return void
     */
    static public function echoDocumentationCore(): void
    {
        ?>

        <h3 id="oam">OAM / Module</h3>
        <p>Le module est une classe enfant de la classe <i>Modules</i>. Cela permet d'étendre les fonctionnalités d'une
            application. Un module peut être par défaut présent dans une application, c'est-à-dire présent dans l'objet
            de l'application. Dans ce cas son nom doit être présent dans la liste des modules intégrés à l'application.
            </p>
        <p>Il y a plusieurs types de modules dans une application. :</p>
        <ul>
            <li>Le type interne (internal) correspond aux modules intégrés dans l'objet de l'application.</li>
            <li>Le type externe (external) correspond uax modules non intégrés dans l'objet de l'application. Ils sont
                détectés par des liens dédiés et chargés puis instanciés par l'application.</li>
            <li>Le type traduction (translate) correspond aux modules externes à l'application et dédiés à la
                traduction, mais avec des capacités réduites.</li>
        </ul>
        <p>Pour qu'une application puisse utiliser des modules, elle doit permettre l'utilisation des modules. De façon
            globale, des options permettent d'utiliser des modules ou non, elles sont prioritaires sur le choix des
            applications.</p>
        <p>Pour activer les modules, internes et/ou externes, dans la classe <i>Application</i> d'une application, il
            faut positionner la constante <b>USE_MODULES = true</b>. L'option <i>permitApplicationModules</i> doit être
            à true. Les modules internes sont intégrés par défaut dans l'objet d'une application. Pour être utilisé, ils
            doivent tous être déclarés dans la constance <b>LIST_MODULES_INTERNAL</b> sous forme d'une liste.</p>
        <p>Les modules externes peuvent être pris en compte via le lien des modules si la constance
            <b>USE_MODULES_EXTERNAL = true</b> en plus de la <b>USE_MODULES = true</b>. Les options
            <i>permitApplicationModules</i> et <i>permitApplicationModulesExternal</i> doivent être à true.</p>
        <p>Les modules externes de traduction peuvent être pris en compte via le lien des modules si la constance
            <b>USE_MODULES_TRANSLATE = true</b> en plus de la <b>USE_MODULES = true</b>. Ces modules n'ont aucun code
            exécuté et exposent uniquement un tableau avec des traductions. Les options <i>permitApplicationModules</i>
            et <i>permitApplicationModulesTranslate</i> doivent être à true.</p>
        <p>A faire...</p>

        <h4 id="oamn">OAMN / Nommage</h4>
        <p>A faire...</p>

        <h4 id="oamp">OAMP / Protection</h4>
        <p>A faire...</p>

        <h4 id="oamd">OAMD / Dissimulation</h4>
        <p>A faire...</p>

        <h4 id="oaml">OAML / Liens</h4>
        <p>A faire...</p>

        <h4 id="oamc">OAMC / Création</h4>
        <p>Un module est une classe enfant de la classe <i>Modules</i> ou de la classe <i>ModuleTranslates</i>.</p>
        <p>Les modules sont chargés par la classe <i>Applications</i> dont hérite toutes les applications. </p>
        <p>Liste des liens à générer lors de la création d'un module.</p>
        <p>A faire...</p>

        <h4 id="oamu">OAMU / Mise à Jour</h4>
        <p>A faire...</p>

        <h4 id="oams">OAMS / Stockage</h4>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h4 id="oamt">OAMT / Transfert</h4>
        <p>A faire...</p>

        <h4 id="oamr">OAMR / Réservation et références</h4>
        <p>Pas d'objet réservé spécifiquement pour les modules d'applications.</p>
        <p>Les références :</p>
        <ul>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES; ?> : Référence pour retrouver les modules d'une application.</li>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_TRANSLATE=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_TRANSLATE; ?> : Référence pour retrouver les modules de traduction d'une application.</li>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_ACTIVE=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_ACTIVE; ?> : Référence pour retrouver les modules activés.</li>
        </ul>

        <?php
    }
}
