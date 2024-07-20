<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Classe Modules
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Modules implements moduleInterface
{
    /* ---------- ---------- ---------- ---------- ----------
	 * Constantes.
	 *
	 * Leur modification change profondément le comportement de l'application.
	 *
	 * Si déclarées 'const' ou 'static' elles ne sont pas remplacée dans les classes enfants
	 * lorsque l'on appelle des fonctions de la classe parente non écrite dans la classe enfant...
	 */
    /**
     * Le type de module.
     * None : Le module est chargé, mais n'est pas utilisé après.
     * Application : Le module est chargé puis appelé aux emplacements dédiés aux applications.
     *     Il est aussi utilisé pour les traductions qui le concerne.
     * Traduction : Le module est chargé puis utilisé pour toute traduction.
     *
     * @var string
     */
    protected $MODULE_TYPE = 'None'; // None | Application | Traduction

    /**
     * Le nom du module. Ce nom est généralement proche du nom de la classe.
     *
     * @var string
     */
    protected $MODULE_NAME = 'None';

    protected $MODULE_MENU_NAME = 'None';
    protected $MODULE_COMMAND_NAME = 'none';
    protected $MODULE_DEFAULT_VIEW = 'disp';
    protected $MODULE_DESCRIPTION = 'Description';
    protected $MODULE_VERSION = '020160925';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 sylabe 2013-2016';
    protected $MODULE_LOGO = '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c';
    protected $MODULE_HELP = 'Help';
    protected $MODULE_INTERFACE = '2.0';

    protected $MODULE_REGISTERED_VIEWS = array('disp');
    protected $MODULE_REGISTERED_ICONS = array();
    protected $MODULE_APP_TITLE_LIST = array();
    protected $MODULE_APP_ICON_LIST = array();
    protected $MODULE_APP_DESC_LIST = array();
    protected $MODULE_APP_VIEW_LIST = array();

    const DEFAULT_COMMAND_ACTION_DISPLAY_MODULE = 'name';


    /* ---------- ---------- ---------- ---------- ----------
	 * Variables.
	 *
	 * Les valeurs par défaut sont indicatives. Ne pas les replacer.
	 * Les variables sont systématiquement recalculées.
	 */
    /**
     * Instance de l'application.
     *
     * @var Applications
     */
    protected $_applicationInstance = null;

    /**
     * Instance de la librairie nebule.
     *
     * @var nebule
     */
    protected $_nebuleInstance = null;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    protected $_configurationInstance = null;

    /**
     * Instance de la classe d'affichage.
     *
     * @var Displays
     */
    protected $_displayInstance = null;

    /**
     * Instance de la classe de traduction.
     *
     * @var Translates
     */
    protected $_translateInstance = null;

    /**
     * Instance de la métrologie.
     *
     * @var Metrology
     */
    protected $_metrologyInstance = null;

    /**
     * Etat de verrouillage de l'entité en cours.
     *
     * @var boolean
     */
    protected $_unlocked = false;

    /**
     * Table des traductions spécifiques au module.
     *
     * @var array of string
     */
    protected $_table = array();


    /**
     * Constructeur.
     *
     * @param Applications $applicationInstance
     * @return void
     */
    public function __construct(Applications $applicationInstance)
    {
        $this->_applicationInstance = $applicationInstance;
        $this->_configurationInstance = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
    }

    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        $this->_initTable_DEPRECATED();
    }


    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->MODULE_NAME;
    }

    public function getClassName(): string
    {
        return static::class;
    }

    public function getType(): string
    {
        return strtolower($this->MODULE_TYPE);
    }

    public function getName(): string
    {
        return $this->MODULE_NAME;
    }

    public function getMenuName(): string
    {
        return $this->MODULE_MENU_NAME;
    }

    public function getRegisteredViews(): array
    {
        return $this->MODULE_REGISTERED_VIEWS;
    }

    public function getCommandName(): string
    {
        return $this->MODULE_COMMAND_NAME;
    }

    public function getDefaultView(): string
    {
        return $this->MODULE_DEFAULT_VIEW;
    }

    public function getDescription(): string
    {
        return $this->MODULE_DESCRIPTION;
    }

    public function getVersion(): string
    {
        return $this->MODULE_VERSION;
    }

    public function getAuthor(): string
    {
        return $this->MODULE_AUTHOR;
    }

    public function getLicence(): string
    {
        return $this->MODULE_LICENCE;
    }

    public function getLogo(): string
    {
        return $this->MODULE_LOGO;
    }

    public function getHelp(): string
    {
        return $this->MODULE_HELP;
    }

    public function getInterface(): string
    {
        return $this->MODULE_INTERFACE;
    }

    // Gestion de la présence dans le menu des applications.
    public function getAppTitleList(): array
    {
        return $this->MODULE_APP_TITLE_LIST;
    }

    public function getAppIconList(): array
    {
        return $this->MODULE_APP_ICON_LIST;
    }

    public function getAppDescList(): array
    {
        return $this->MODULE_APP_DESC_LIST;
    }

    public function getAppViewList(): array
    {
        return $this->MODULE_APP_VIEW_LIST;
    }


    /**
     * Add functionalities on hooks.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?Node $nid = null): array
    {
        return array();
    }

    /**
     * Add functionalities on hooks, only for translation modules.
     *
     * @param string $hookName
     * @return array
     */
    public function getHookListTranslation(string $hookName): array
    {
        $hookArray = array();
        if ($hookName == 'helpLanguages') {
            $hookArray[0]['name'] = $this->_traduction('::::Bienvenue', $this->MODULE_COMMAND_NAME);
            $hookArray[0]['icon'] = $this->MODULE_LOGO;
            $hookArray[0]['desc'] = $this->_traduction('::translateModule:' . $this->MODULE_COMMAND_NAME . ':ModuleDescription', $this->MODULE_COMMAND_NAME);
            $hookArray[0]['link'] = '?mod=hlp&view=lang&' . Translates::DEFAULT_COMMAND_LANGUAGE . '=' . $this->MODULE_COMMAND_NAME;
        }
        return $hookArray;
    }


    /**
     * Affichage de la page par défaut. FIXME à vider
     */
    public function displayModule(): void
    {
        // Lit si la variable GET existe.
        /*if (filter_has_var(INPUT_GET, Displays::DEFAULT_INLINE_COMMAND))
            $this->_displayInline();
        else
            $this->_displayFull();*/
    }

    /**
     * Affichage en ligne comme élément inserré dans une page web.
     *
     * @return void
     */
    public function displayModuleInline(): void
    {
        // N'affiche rien par défaut.
    }

    /**
     * Cache de la lecture de la commande d'action d'affichage du module.
     *
     * @var string
     */
    private $_commandActionDisplayModuleCache = null;

    /**
     * Extrait en vue d'un affichage dans le module un texte/objet à afficher.
     *
     * @return string
     */
    public function getExtractCommandDisplayModule(): string
    {
        $return = '';

        if ($this->_commandActionDisplayModuleCache != null)
            return $this->_commandActionDisplayModuleCache;

        // Vérifie que l'on est en vue affichage de module.
        if ($this->_displayInstance->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[1]) {
            // Lit et nettoye le contenu de la variable GET.
            $arg = trim(filter_input(INPUT_GET, self::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE, FILTER_SANITIZE_STRING));

            // Ecriture des variables.
            if ($arg != '') {
                $return = $arg;
            }

            unset($arg);
        }

        // Mise en cache.
        $this->_commandActionDisplayModuleCache = $return;

        return $return;
    }

    /**
     * Affichage de surcharges CSS.
     *
     * @return void
     */
    public function getCSS(): void
    {
        echo '<style type="text/css">' . "\n";
        $this->headerStyle();
        echo '</style>' . "\n";
    }

    /**
     * Affichage de surcharges CSS.
     *
     * Obsolète !!!
     *
     * @return void
     */
    public function headerStyle(): void
    {
        // N'affiche rien par défaut.
    }

    /**
     * Affichage de surcharges de java script.
     *
     * @return void
     */
    public function headerScript(): void
    {
        // N'affiche rien par défaut.
    }

    /**
     * Action principale.
     *
     * @return void
     */
    public function action(): void
    {
        // Ne fait rien par défaut.
    }

    /**
     * Traduction.
     * Si besoin, extrait la langue de destination.
     * Si aucune traduction n'est trouvée dans la langue demandée, retourne le texte d'origine.
     *
     * @param string $text
     * @param string $lang
     * @return string
     */
    public function getTranslateInstance(string $text, string $lang = ''): string
    {
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
     * Traduction interne à la classe.
     *
     * @param string $text
     * @param string $lang
     * @return string
     */
    protected function _traduction(string $text, string $lang = ''): string
    {
        return $this->_translateInstance->getTranslate($text, $lang);
    }

    /**
     * Affichage du texte traduit, interne à la classe.
     *
     * @param string $text
     * @return void
     */
    protected function _echoTraduction(string $text): void
    {
        $this->_translateInstance->echoTranslate($text);
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
        // Génère le lien.
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);

        // Signe le lien.
        $newLink->sign($signer);

        // Si besoin, obfuscation du lien.
        if ($obfuscate) {
            $link->obfuscate();
        }

        // Ecrit le lien.
        $newLink->write();
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
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable_DEPRECATED(): void
    {
        $this->_table = self::TRANSLATE_TABLE;
    }


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
                <li><a href="#oamr">OAMR / Réservation</a></li>
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
        <p>Le module est une classe enfant de la classe Modules. Cela permet d'étendre les fonctionnalités d'une
            application. Un module peut être par défaut présent dans une application, c'est-à-dire présent dans l'objet
            de l'application. Dans ce cas son nom doit être présent dans la liste des modules intégrés à l'application.
            </p>
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
        <p>Les modules sont chargés par la classe Applications dont hérite toutes les applications. </p>
        <p>Pour activer les modules, dans la classe Application d'une application, il faut positionner la variable
            <b>$_useModules = true</b>b></p>
        <p>Si des modules sont intégrés (interne) par défaut dans l'objet d'une application, pour être utilisé, ils doivent tous
            être déclarés dans la variable <b>$_listInternalModules</b> sous forme d'une liste.</p>
        <p>Les modules externes, ils peuvent être pris en compte via le lien des modules si <b>$_useExternalModules =
                true</b></p>
        <p>Liste des liens à générer lors de la création d'un module.</p>
        <p>A faire...</p>

        <h4 id="oamu">OAMU / Mise à Jour</h4>
        <p>A faire...</p>

        <h4 id="oams">OAMS / Stockage</h4>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h4 id="oamt">OAMT / Transfert</h4>
        <p>A faire...</p>

        <h4 id="oamr">OAMR / Réservation</h4>
        <p>Les objets réservés spécifiquement pour les modules d'applications :</p>
        <ul>
            <li>nebule/objet/interface/web/php/applications/modules</li>
            <li>nebule/objet/interface/web/php/applications/modules/active</li>
        </ul>

        <?php
    }
}
