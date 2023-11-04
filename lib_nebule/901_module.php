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
	 *   lorsque l'on appelle des fonctions de la classe parente non écrite dans la classe enfant...
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
    protected $_applicationInstance;

    /**
     * Instance de la librairie nebule.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    protected $_configuration;

    /**
     * Instance de la classe d'affichage.
     *
     * @var Displays
     */
    protected $_display;

    /**
     * Instance de la classe de traduction.
     *
     * @var Traductions
     */
    protected $_traduction;

    /**
     * Etat de verrouillage de l'entité en cours.
     *
     * @var boolean
     */
    protected $_unlocked;

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
        $this->_configuration = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
    }

    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_display = $this->_applicationInstance->getDisplayInstance();
        $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        $this->_initTable();
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
     * Ajout de fonctionnalités à des points d'ancrage.
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
     * Affichage de la page par défaut. FIXME à vider
     */
    public function display(): void
    {
        // Lit si la variable GET existe.
        /*if (filter_has_var(INPUT_GET, Displays::DEFAULT_INLINE_COMMAND))
            $this->_displayInline();
        else
            $this->_displayFull();*/
    }

    /*
     * Affichage principale.
     *
     * @return void
     */
    /*protected function _displayFull(): void
    {
        // N'affiche rien par défaut.
    }*/

    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    public function displayInline(): void
    {
        // N'affiche rien par défaut.
    }

    /*
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    /*protected function _displayInline(): void
    {
        // N'affiche rien par défaut.
    }*/

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
        if ($this->_display->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[1]) {
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
    public function getTraduction(string $text, string $lang = ''): string
    {
        $result = $text;
        if ($this->_traduction == null) {
            $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        }

        if ($lang == '') {
            $lang = $this->_traduction->getCurrentLanguage();
        }

        if (isset($this->_table[$lang][$text])) {
            $result = $this->_table[$lang][$text];
        }
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
        return $this->_traduction->getTraduction($text, $lang);
    }

    /**
     * Affichage du texte traduit, interne à la classe.
     *
     * @param string $text
     * @return void
     */
    protected function _echoTraduction(string $text): void
    {
        $this->_traduction->echoTraduction($text);
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


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::nebule:modules::ModuleName'] = 'Module des modules';
        $this->_table['en-en']['::nebule:modules::ModuleName'] = 'Module of modules';
        $this->_table['es-co']['::nebule:modules::ModuleName'] = 'Module of modules';
        $this->_table['fr-fr']['::nebule:modules::MenuName'] = 'Modules';
        $this->_table['en-en']['::nebule:modules::MenuName'] = 'Modules';
        $this->_table['es-co']['::nebule:modules::MenuName'] = 'Modules';
        $this->_table['fr-fr']['::nebule:modules::ModuleDescription'] = 'Module de gestion des modules.';
        $this->_table['en-en']['::nebule:modules::ModuleDescription'] = 'Module to manage modules.';
        $this->_table['es-co']['::nebule:modules::ModuleDescription'] = 'Module to manage modules.';
        $this->_table['fr-fr']['::nebule:modules::ModuleHelp'] = 'Cette application permet de voir les modules détectés par sylabe.';
        $this->_table['en-en']['::nebule:modules::ModuleHelp'] = 'This application permit to see modules detected by sylabe.';
        $this->_table['es-co']['::nebule:modules::ModuleHelp'] = 'This application permit to see modules detected by sylabe.';

        $this->_table['fr-fr']['::nebule:modules::AppTitle1'] = 'Modules';
        $this->_table['en-en']['::nebule:modules::AppTitle1'] = 'Modules';
        $this->_table['es-co']['::nebule:modules::AppTitle1'] = 'Modules';
        $this->_table['fr-fr']['::nebule:modules::AppDesc1'] = 'Module de gestion des modules.';
        $this->_table['en-en']['::nebule:modules::AppDesc1'] = 'Manage modules.';
        $this->_table['es-co']['::nebule:modules::AppDesc1'] = 'Manage modules.';
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
