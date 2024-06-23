<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Classe Traductions
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Traductions
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
     * langue par défaut.
     *
     * @var string
     */
    protected $DEFAULT_LANGUAGE = 'en-en';

    /**
     * Commande de sélection de la langue.
     * @var string
     */
    const DEFAULT_COMMAND_LANGUAGE = 'lang';

    /**
     * Liste des languages supportés.
     *
     * @var array
     */
    protected $LANGUAGE_LIST = array('en-en');

    /**
     * Liste des icônes des languages supportés.
     *
     * @var array
     */
    protected $LANGUAGE_ICON_LIST = array('en-en' => '7796077f1b865951946dd40ab852f6f4d21e702e7c4f47bd5fa6cb9ce94a4c5f');


    /* ---------- ---------- ---------- ---------- ----------
	 * Variables.
	 *
	 * Les valeurs par défaut sont indicatives. Ne pas les replacer.
	 * Les variables sont systématiquement recalculées.
	 */
    /**
     * Paramètre d'activation de la gestion des modules.
     *
     * @var boolean
     */
    protected $_useModules = false;

    /**
     * L'instance de la librairie nebule.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * L'instance de l'application.
     *
     * @var Applications
     */
    protected $_applicationInstance;

    /**
     * L'instance de la métrologie.
     *
     * @var Metrology
     */
    protected $_metrologyInstance;

    /**
     * Langue sélectionnée.
     *
     * @var string
     */
    protected $_currentLanguage;

    /**
     * Icône de la langue sélectionnée.
     *
     * @var string
     */
    protected $_currentLanguageIcon;

    /**
     * Langue par défaut.
     *
     * @var string
     */
    protected $_defaultLanguage;

    /**
     * Liste des langues disponibles.
     *
     * @var array of string
     */
    protected $_languageList = array();

    /**
     * Liste des icônes des langues disponibles.
     *
     * @var array of string
     */
    protected $_languageIconList = array();

    protected $_languageInstanceList = array();
    protected $_currentLanguageInstance;
    protected $_defaultLanguageInstance;


    /**
     * Constructeur.
     *
     * @param Applications $applicationInstance
     * @return void
     */
    public function __construct(Applications $applicationInstance)
    {
        $this->_applicationInstance = $applicationInstance;
    }

    /**
     * Initialisation des variables et instances interdépendantes.
     *
     * @return void
     */
    public function initialisation()
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_metrologyInstance->addLog('Load translates', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        // Détecte si les modules sont activés.
        if ($this->_applicationInstance->getUseModules())
            $this->_useModules = true;

        // Recherche les langages.
        $this->_findDefaultLanguage();
        $this->_findLanguages();
        $this->_findCurrentLanguage();
        $this->_findIcons();
        $this->_findCurrentIcon();
        $this->_initTable();

        // Aucun affichage, aucune traduction, aucune action avant le retour de cette fonction.
        // Les instances interdépendantes doivent être synchronisées.
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
    public function __toString()
    {
        return 'Traduction';
    }

    /**
     * Fonction de mise en sommeil.
     *
     * @return array:string
     */
    public function __sleep()
    {
        return array();
    }

    /**
     * Fonction de réveil.
     *
     * Récupère l'instance de la librairie nebule et de l'application.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $applicationInstance;

        $this->_applicationInstance = $applicationInstance;
    }

    /**
     * Initialisation des variables et instances interdépendantes.
     * @return void
     * @todo à optimiser avec __wakeup et __sleep.
     *
     */
    public function initialisation2()
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_metrologyInstance->addLog('Load translates', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        // Détecte si les modules sont activés.
        if ($this->_applicationInstance->getUseModules()) {
            $this->_useModules = true;
        }

        // Recherche les languages.
        $this->_findDefaultLanguage();
        $this->_findLanguages();
        $this->_findCurrentLanguage();
        $this->_findIcons();
        $this->_findCurrentIcon();
        $this->_initTable();

        // Aucun affichage, aucune traduction, aucune action avant le retour de cette fonction.
        // Les instances interdépendantes doivent être synchronisées.
    }


    /**
     * Cherche la langue par défaut.
     *
     * @return void
     */
    protected function _findDefaultLanguage()
    {
        $this->_defaultLanguage = $this->DEFAULT_LANGUAGE;

        if ($this->_useModules) {
            foreach ($this->_languageInstanceList as $module) {
                if ($module->getCommandName() == $this->_defaultLanguage) {
                    $this->_defaultLanguageInstance = $module;
                }
            }
        }

        $this->_metrologyInstance->addLog('Find default language : ' . $this->_defaultLanguage, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
    }


    /**
     * Cherche les langues disponibles.
     *
     * @return void
     */
    protected function _findLanguages()
    {
        if ($this->_useModules) {
            foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                if ($module->getType() == 'traduction') {
                    $this->_languageList[$module->getCommandName()] = $module->getCommandName();
                    $this->_languageInstanceList[$module->getCommandName()] = $module;

                    $this->_metrologyInstance->addLog('Find new language : ' . $module->getCommandName(), Metrology::LOG_LEVEL_DEVELOP, __METHOD__, '00000000');
                }
            }
        } else
            $this->_languageList = $this->LANGUAGE_LIST;
    }


    /**
     * La langue d'affichage de l'interface.
     *
     * La recherche de la langue se fait en lisant la langue demandée dans l'URL,
     *   puis en la comparant aux modules de traductions présents.
     *
     * @return void
     */
    protected function _findCurrentLanguage()
    {
        // Lit et nettoye le contenu de la variable GET.
        $arg_lang = filter_input(INPUT_GET, self::DEFAULT_COMMAND_LANGUAGE, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

        // Test la forme.
        if ($arg_lang != ''
            && strlen($arg_lang) != 5
            && substr($arg_lang, 2, 1) != '-'
        )
            $arg_lang = '';

        // Recherche un langage connu.
        $ok_lang = false;
        $lang_instance = '';
        foreach ($this->_languageList as $lang) {
            if ($arg_lang == $lang) {
                $ok_lang = true;
                if ($this->_useModules)
                    $lang_instance = $this->_languageInstanceList[$lang];
                break;
            }
        }

        // Si le langage est connu.
        if ($ok_lang) {
            $this->_currentLanguage = $arg_lang; // Ecrit le langage dans la variable.
            $this->_currentLanguageInstance = $lang_instance;
            // Ecrit le langage dans la session.
            $this->_nebuleInstance->setSessionStore($this->_applicationInstance->getClassName() . 'DisplayLanguage', $arg_lang);
        } else {
            $cache = $this->_nebuleInstance->getSessionStore($this->_applicationInstance->getClassName() . 'DisplayLanguage');
            // S'il existe une variable de session pour le mode d'affichage, la lit.
            if ($cache !== false
                && $cache != ''
            ) {
                $this->_currentLanguage = $cache;
                $this->_currentLanguageInstance = $this->_languageInstanceList[$cache]; // FIXME en-en not exist
            } else {
                $this->_currentLanguage = $this->_defaultLanguage;
                $this->_currentLanguageInstance = $this->_languageInstanceList[$this->_defaultLanguage];
                $this->_nebuleInstance->setSessionStore($this->_applicationInstance->getClassName() . 'DisplayLanguage', $this->_defaultLanguage);
            }
            unset($cache);
        }

        $this->_metrologyInstance->addLog('Find current language : ' . $this->_currentLanguage, Metrology::LOG_LEVEL_DEVELOP, __METHOD__, '00000000');
    }


    /**
     * Retourne la liste des langues.
     *
     * @return array of string
     */
    public function getLanguageList(): array
    {
        return $this->_languageList;
    }

    public function getDefaultLanguage(): string
    {
        return $this->_defaultLanguage;
    }

    public function getCurrentLanguage(): string
    {
        return $this->_currentLanguage;
    }

    public function getCurrentLanguageIcon(): string
    {
        return $this->_currentLanguageIcon;
    }

    public function getLanguageIcon($lang): string
    {
        $result = $this->_languageIconList[$this->_currentLanguage];
        if (isset($this->_languageIconList[$lang]))
            $result = $this->_languageIconList[$lang];
        return $result;
    }

    public function getLanguageInstanceList()
    {
        return $this->_languageInstanceList;
    }

    public function getCurrentLanguageInstance()
    {
        return $this->_currentLanguageInstance;
    }

    public function getDefaultLanguageInstance()
    {
        return $this->_defaultLanguageInstance;
    }


    /**
     * La traduction de textes recherche une traduction pour le langage en cours.
     * Sans module :
     * 1) Recherche le texte traduit dans le langage courant.
     * 2) Si aucune traduction n'est trouvée, retourne le texte traduit dans le langage par défaut.
     * 3) Si aucune traduction n'est trouvée, retourne le texte non traduit.
     * Avec modules :
     * 1) Recherche le texte traduit le module de traduction pour le langage courant.
     * 2) Si pas trouvé, recherche dans le module utilisé à l'affichage pour le langage courant.
     * 3) Si pas trouvé, recherche dans le module de traduction pour le langage par défaut.
     * 4) Si pas trouvé, recherche dans le module utilisé à l'affichage pour le langage par défaut.
     * 5) Si pas trouvé, recherche dans tous les modules disponibles pour le langage courant.
     * 6) Si pas trouvé, recherche dans tous les modules disponibles pour le langage par défaut.
     * 7) Si pas trouvé, retourne le texte d'origine.
     * Un module, s'il n'est pas dédié à la traduction, ne doit prendre en charge que la translation des textes
     *   qui le concerne. Mais il peut traduire ces textes dans plusieurs langues.
     * Un texte qui commence par '<' est considéré comme une balise et n'est pas traduit.
     *
     * @param string $text
     * @param string $lang
     * @return string
     */
    public function getTraduction(string $text, string $lang = ''): string
    {
        $result = $text;

        // Sélectionne le langage de traduction.
        if ($lang == '')
            $lang = $this->_currentLanguage;

        $this->_metrologyInstance->addLog('Get traduction ' . $lang . ' : "' . substr($text, 0, 250) . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        // Si le texte est une balise, comme par '<', ne fait pas la traduction.
        if (substr($text, 0, 1) == '<')
            return $result;

        if (!$this->_useModules) // Si pas de modules.
        {
            if (isset($this->_table[$lang][$text]))
                $result = $this->_table[$lang][$text];
            elseif (isset($this->_table[$this->_defaultLanguage][$text]))
                $result = $this->_table[$this->_defaultLanguage][$text];
        } else // Si modules.
        {
            // 1) Appel le module de traduction avec la langue en cours.
            foreach ($this->_languageInstanceList as $module) {
                if ($module->getCommandName() == $lang) {
                    $result = $module->getTraduction($text, $lang);
                    $this->_metrologyInstance->addLog('Module 1 find traduction : "' . $result . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                }
            }

            // 2) Si rien trouvé, appel le module d'affichage en cours avec la langue en cours.
            if (($result == '' || $result == $text)
                && is_a($this->_applicationInstance->getCurrentModuleInstance(), '\Nebule\Library\Modules')
            ) {
                $result = $this->_applicationInstance->getCurrentModuleInstance()->getTraductionInstance($text, $lang);
                $this->_metrologyInstance->addLog('Module 2 find traduction : "' . $result . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
            }

            // 3) Si rien trouvé, appel le module de traduction avec la langue par défaut.
            if (($result == '' || $result == $text)
                && $lang != $this->_defaultLanguage
            ) {
                foreach ($this->_languageInstanceList as $module) {
                    if ($module->getCommandName() == $this->_defaultLanguage) {
                        $result = $module->getTraduction($text, $this->_defaultLanguage);
                        $this->_metrologyInstance->addLog('Module 3 find traduction : "' . $result . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                    }
                }
            }

            // 4) Si rien trouvé, appel le module d'affichage en cours avec la langue par défaut.
            if (($result == '' || $result == $text)
                && is_a($this->_applicationInstance->getCurrentModuleInstance(), '\Nebule\Library\Modules')
            ) {
                $result = $this->_applicationInstance->getCurrentModuleInstance()->getTraductionInstance($text, $this->_defaultLanguage);
                $this->_metrologyInstance->addLog('Module 4 find traduction : "' . $result . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
            }

            // 5) Si rien trouvé, appel tous les modules avec la langue en cours.
            if ($result == '' || $result == $text) {
                foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                    $result = $module->getTraductionInstance($text, $lang);
                    $this->_metrologyInstance->addLog('Module 5 find traduction : "' . $result . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                    if ($result != $text)
                        break;
                }
            }

            // 6) Si rien trouvé, appel tous les modules avec la langue par défaut.
            if (($result == '' || $result == $text)
                && $lang != $this->_defaultLanguage
            ) {
                foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                    $result = $module->getTraductionInstance($text, $this->_defaultLanguage);
                    $this->_metrologyInstance->addLog('Module 6 find traduction : "' . $result . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                    if ($result != $text)
                        break;
                }
            }

            // 7) Par défaut si rien trouvé renvoie le texte originel.
            if ($result == '')
                $result = $text;
        }

        // Retourne le résultat.
        $this->_metrologyInstance->addLog('End find traduction : "' . $result . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        return $result;
    }

    /**
     * Affiche un texte préalablement traduit.
     * Le texte peut se voir forcé sa couleur.
     * Certaines séquences peuvent être remplacées par des valeurs, les arguments.
     *
     * @param string $text
     * @param string $color
     * @param string $arg1
     * @param string $arg2
     * @param string $arg3
     * @param string $arg4
     * @param string $arg5
     * @param string $arg6
     * @param string $arg7
     * @param string $arg8
     * @param string $arg9
     * @return void
     */
    public function echoTraduction(string $text, $color = '', $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null, $arg5 = null, $arg6 = null, $arg7 = null, $arg8 = null, $arg9 = null) // FIXME null ou '' ?
    {
        if ($color != '')
            echo "<font color=\"$color\">";
        echo sprintf($this->getTraduction($text), $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9);
        if ($color != '') {
            echo '</font>';
        }
    }

    protected function _findIcons()
    {
        if ($this->_useModules) {
            foreach ($this->_languageInstanceList as $module)
                $this->_languageIconList[$module->getCommandName()] = $module->getLogo();
        } else
            $this->_languageIconList = $this->LANGUAGE_ICON_LIST;
    }

    protected function _findCurrentIcon()
    {
        $this->_currentLanguageIcon = $this->_languageIconList[$this->_currentLanguage];
    }


    /**
     * Table des traductions.
     *
     * @var array
     */
    protected $_table = array();

    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable()
    {
        $this->_table['fr-fr']['::Welcome'] = 'Bienvenue';
        $this->_table['en-en']['::Welcome'] = 'Welcome';
        $this->_table['es-co']['::Welcome'] = 'Bienvenido';
    }
}
