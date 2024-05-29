<?php
declare(strict_types=1);
namespace Nebule\Application\Autent;
use Nebule\Library\Metrology;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Node;
use Nebule\Library\References;
use Nebule\Library\Traductions;

/*
------------------------------------------------------------------------------------------
 /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
------------------------------------------------------------------------------------------

 [FR] Toute modification de ce code entrainera une modification de son empreinte
      et entrainera donc automatiquement son invalidation !
 [EN] Any changes to this code will cause a change in its footprint and therefore
      automatically result in its invalidation!
 [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo
      tanto lugar automáticamente a su anulación!

------------------------------------------------------------------------------------------
*/



/**
 * Class Application for upload
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications
{
    const APPLICATION_NAME = 'autent';
    const APPLICATION_SURNAME = 'nebule/autent';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020240529';
    const APPLICATION_LICENCE = 'GNU GPL 2023-2024';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    /**
     * {@inheritDoc}
     * @see Applications::_useModules
     * @var boolean
     */
    protected $_useModules = true;

    /**
     * {@inheritDoc}
     * @see Applications::_useExternalModules
     * @var boolean
     */
    protected $_useExternalModules = false;

    /**
     * Liste des noms des modules par défaut.
     *
     * @var array
     */
    protected $_listInternalModules = array(
        'ModuleAutent',
    );

    // All default.
}


/**
 * Classe Display
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Display extends Displays
{
    const DEFAULT_DISPLAY_MODE = 'autent';
    const DEFAULT_DISPLAY_VIEW = 'logout';

    /**
     * Display full page.
     */
    public function _displayFull(): void
    {
        ?>

        <!DOCTYPE html>
        <html lang="">
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
            <title><?php echo Application::APPLICATION_NAME; ?></title>
            <link rel="icon" type="image/png" href="favicon.png"/>
            <meta name="keywords" content="<?php echo Application::APPLICATION_SURNAME; ?>"/>
            <meta name="description" content="<?php echo Application::APPLICATION_NAME; ?>"/>
            <meta name="author" content="<?php echo Application::APPLICATION_AUTHOR . ' - ' . Application::APPLICATION_WEBSITE; ?>"/>
            <meta name="licence" content="<?php echo Application::APPLICATION_LICENCE; ?>"/>
            <?php
            $this->_metrologyInstance->addLog('Display css', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '7fcf1976');
            $this->commonCSS();
            $this->displayCSS();

            $this->_metrologyInstance->addLog('Display vbs', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ecbd188e');
            $this->_displayScripts();
            ?>
        </head>
        <body>
        <?php
        $this->_displayHeader();
        $this->_displayFooter();
        ?>
        <div class="layout-main">
            <div class="layout-content">
                <?php
                $this->_metrologyInstance->addLog('Display content', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '58d043ab');
                $this->_displayContent();
                ?>

            </div>
        </div>
        </body>
        </html>
        <?php
    }

    public function displayCSS(): void
    {
        ?>

        <style>
            .layout-content {
                max-width: 80%;
            }

            .layout-content > div {
                min-width: 400px;
                margin-bottom: 20px;
                text-align: left;
                color: #ababab;
                white-space: normal;
            }

            h1 {
                font-family: monospace;
                font-size: 1.4em;
                font-weight: normal;
                color: #ababab;
            }

            .newlink {
                width: 100%;
            }

            .result img {
                height: 16px;
                width: 16px;
            }
        </style>
        <?php

        // Ajout de la partie CSS du module en cours d'utilisation, si présent.
        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                $module->getCSS();
            }
        }
    }

    protected function _displayScripts(): void
    {
        $this->commonScripts();

        // Ajout de la partie script JS du module en cours d'utilisation, si présent.
        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                $module->headerScript();
                echo "\n";
            }
        }
    }

    private function _displayHeader()
    {
        ?>

        <div class="layout-header">
            <div class="header-left">
                <a href="/?<?php echo Displays::DEFAULT_BOOTSTRAP_LOGO_LINK; ?>">
                    <img title="App switch" alt="[]" src="<?php echo Displays::DEFAULT_APPLICATION_LOGO; ?>"/>
                </a>
            </div>
            <div class="header-right">
                &nbsp;
            </div>
            <div class="header-center">
                <p>
                    <?php
                    $name = $this->_nebuleInstance->getInstanceEntityInstance()->getFullName();
                    if ($name != $this->_nebuleInstance->getInstanceEntity())
                        echo $name;
                    else
                        echo '/';
                    echo '<br />' . $this->_nebuleInstance->getInstanceEntity();
                    ?>
                </p>
            </div>
        </div>
        <?php
    }

    private function _displayMenuApplications()
    {

    }

    private function _displayInternalMenuApplications()
    {

    }

    private function _displayContent()
    {
        $module = $this->_applicationInstance->getModule('ModuleAutent');

        # TODO
        if (filter_has_var(INPUT_GET, References::COMMAND_APPLICATION_BACK))
            $argBack = trim(filter_input(INPUT_GET, References::COMMAND_APPLICATION_BACK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        else
            $argBack = '1';
        $argLogout = filter_has_var(INPUT_GET, References::COMMAND_AUTH_ENTITY_LOGOUT);

        if ($module != null) {
            $module->displayModule();
            $this->_displayInlineContentID();
        } else {
            $this->_metrologyInstance->addLog('Error loading ModuleAutent', Metrology::LOG_LEVEL_ERROR, __METHOD__, '57b017ad');
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'short',
            );
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation('::::ERROR', $param);
        }
    }

    // Affiche la fin de page.
    private function _displayFooter()
    {
        $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
        if (strpos(Application::APPLICATION_WEBSITE, '://') === false)
            $linkApplicationWebsite = 'http://' . Application::APPLICATION_WEBSITE;

        ?>

        <div class="layout-footer">
            <div class="footer-center">
                <p>
                    <?php echo Application::APPLICATION_NAME; ?><br/>
                    <?php echo Application::APPLICATION_VERSION . ' ' . $this->_configurationInstance->getOptionAsString('codeBranch'); ?><br/>
                    (c) <?php echo Application::APPLICATION_LICENCE . ' ' . Application::APPLICATION_AUTHOR; ?> - <a
                            href="<?php echo $linkApplicationWebsite; ?>" target="_blank"
                            style="text-decoration:none;"><?php echo Application::APPLICATION_WEBSITE; ?></a>
                </p>
            </div>
        </div>
        <?php
    }
}


/**
 * Classe Action
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Action extends Actions
{
    // Tout par défaut.
}


/**
 * Classe Traduction
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Traduction extends Traductions
{
    /**
     * La langue d'affichage de l'interface.
     *
     * Dans cette application la langue est forcée à sa valeur par défaut.
     *
     * @return void
     */
    protected function _findCurrentLanguage()
    {
        $this->_currentLanguage = $this->_defaultLanguage;
        $this->_currentLanguageInstance = $this->_defaultLanguageInstance;
    }


    /**
     * Initialisation de la table de traduction.
     */
    protected function _initTable()
    {
        $this->_table['fr-fr']['::::INFO'] = 'Information';
        $this->_table['en-en']['::::INFO'] = 'Information';
        $this->_table['es-co']['::::INFO'] = 'Information';
        $this->_table['fr-fr']['::::OK'] = 'OK';
        $this->_table['en-en']['::::OK'] = 'OK';
        $this->_table['es-co']['::::OK'] = 'OK';
        $this->_table['fr-fr']['::::INFORMATION'] = 'Message';
        $this->_table['en-en']['::::INFORMATION'] = 'Message';
        $this->_table['es-co']['::::INFORMATION'] = 'Mensaje';
        $this->_table['fr-fr']['::::WARN'] = 'ATTENTION !';
        $this->_table['en-en']['::::WARN'] = 'WARNING!';
        $this->_table['es-co']['::::WARN'] = '¡ADVERTENCIA!';
        $this->_table['fr-fr']['::::ERROR'] = 'ERREUR !';
        $this->_table['en-en']['::::ERROR'] = 'ERROR!';
        $this->_table['es-co']['::::ERROR'] = '¡ERROR!';

        $this->_table['fr-fr']['::::RESCUE'] = 'Mode de sauvetage !';
        $this->_table['en-en']['::::RESCUE'] = 'Rescue mode!';
        $this->_table['es-co']['::::RESCUE'] = '¡Modo de rescate!';

        $this->_table['fr-fr']['::::SecurityChecks'] = 'Tests de sécurité';
        $this->_table['fr-fr'][':::warn_ServNotPermitWrite'] = "Ce serveur n'autorise pas les modifications !";
        $this->_table['fr-fr'][':::warn_flushSessionAndCache'] = "Toutes les données de connexion ont été effacées !";
        $this->_table['fr-fr'][':::info_OnlySignedLinks'] = 'Uniquement des liens signés !';
        $this->_table['fr-fr'][':::info_OnlyLinksFromCodeMaster'] = 'Uniquement les liens signés du maître du code !';
        $this->_table['fr-fr'][':::err_NotPermit'] = 'Non autorisé sur ce serveur !';
        $this->_table['fr-fr'][':::act_chk_errCryptHash'] = "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !";
        $this->_table['fr-fr'][':::act_chk_warnCryptHashkey'] = "La taille de l'empreinte cryptographique est trop petite !";
        $this->_table['fr-fr'][':::act_chk_errCryptHashkey'] = "La taille de l'empreinte cryptographique est invalide !";
        $this->_table['fr-fr'][':::act_chk_errCryptSym'] = "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !";
        $this->_table['fr-fr'][':::act_chk_warnCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est trop petite !";
        $this->_table['fr-fr'][':::act_chk_errCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est invalide !";
        $this->_table['fr-fr'][':::act_chk_errCryptAsym'] = "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !";
        $this->_table['fr-fr'][':::act_chk_warnCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est trop petite !";
        $this->_table['fr-fr'][':::act_chk_errCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est invalide !";
        $this->_table['fr-fr'][':::act_chk_errBootstrap'] = "L'empreinte cryptographique du bootstrap est invalide !";
        $this->_table['fr-fr'][':::act_chk_warnSigns'] = 'La vérification des signatures de liens est désactivée !';
        $this->_table['fr-fr'][':::act_chk_errSigns'] = 'La vérification des signatures de liens ne fonctionne pas !';
        $this->_table['en-en']['::::SecurityChecks']='Security checks';
        $this->_table['en-en'][':::warn_ServNotPermitWrite'] = 'This server do not permit modifications!';
        $this->_table['en-en'][':::warn_flushSessionAndCache'] = 'All datas of this connexion have been flushed!';
        $this->_table['en-en'][':::info_OnlySignedLinks'] = 'Only signed links!';
        $this->_table['en-en'][':::info_OnlyLinksFromCodeMaster'] = 'Only links signed by the code master!';
        $this->_table['en-en'][':::err_NotPermit'] = 'Non autorisé sur ce serveur !';
        $this->_table['en-en'][':::act_chk_errCryptHash'] = "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !";
        $this->_table['en-en'][':::act_chk_warnCryptHashkey'] = "La taille de l'empreinte cryptographique est trop petite !";
        $this->_table['en-en'][':::act_chk_errCryptHashkey'] = "La taille de l'empreinte cryptographique est invalide !";
        $this->_table['en-en'][':::act_chk_errCryptSym'] = "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !";
        $this->_table['en-en'][':::act_chk_warnCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est trop petite !";
        $this->_table['en-en'][':::act_chk_errCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est invalide !";
        $this->_table['en-en'][':::act_chk_errCryptAsym'] = "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !";
        $this->_table['en-en'][':::act_chk_warnCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est trop petite !";
        $this->_table['en-en'][':::act_chk_errCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est invalide !";
        $this->_table['en-en'][':::act_chk_errBootstrap'] = "L'empreinte cryptographique du bootstrap est invalide !";
        $this->_table['en-en'][':::act_chk_warnSigns'] = 'La vérification des signatures de liens est désactivée !';
        $this->_table['en-en'][':::act_chk_errSigns'] = 'La vérification des signatures de liens ne fonctionne pas !';
        $this->_table['es-co']['::::SecurityChecks']='Controles de seguridad';
        $this->_table['es-co'][':::warn_ServNotPermitWrite'] = 'This server do not permit modifications!';
        $this->_table['es-co'][':::warn_flushSessionAndCache'] = 'All datas of this connexion have been flushed!';
        $this->_table['es-co'][':::info_OnlySignedLinks'] = 'Only signed links!';
        $this->_table['es-co'][':::info_OnlyLinksFromCodeMaster'] = 'Only links signed by the code master!';
        $this->_table['es-co'][':::err_NotPermit'] = 'Non autorisé sur ce serveur !';
        $this->_table['es-co'][':::act_chk_errCryptHash'] = "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !";
        $this->_table['es-co'][':::act_chk_warnCryptHashkey'] = "La taille de l'empreinte cryptographique est trop petite !";
        $this->_table['es-co'][':::act_chk_errCryptHashkey'] = "La taille de l'empreinte cryptographique est invalide !";
        $this->_table['es-co'][':::act_chk_errCryptSym'] = "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !";
        $this->_table['es-co'][':::act_chk_warnCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est trop petite !";
        $this->_table['es-co'][':::act_chk_errCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est invalide !";
        $this->_table['es-co'][':::act_chk_errCryptAsym'] = "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !";
        $this->_table['es-co'][':::act_chk_warnCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est trop petite !";
        $this->_table['es-co'][':::act_chk_errCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est invalide !";
        $this->_table['es-co'][':::act_chk_errBootstrap'] = "L'empreinte cryptographique du bootstrap est invalide !";
        $this->_table['es-co'][':::act_chk_warnSigns'] = 'La vérification des signatures de liens est désactivée !';
        $this->_table['es-co'][':::act_chk_errSigns'] = 'La vérification des signatures de liens ne fonctionne pas !';
    }
}


/**
 * Ce module permet gérer les objets.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleAutent extends Modules
{
    protected $MODULE_TYPE = 'Application';
    protected $MODULE_NAME = '::sylabe:module:objects:ModuleName';
    protected $MODULE_MENU_NAME = '::sylabe:module:objects:MenuName';
    protected $MODULE_COMMAND_NAME = 'log';
    protected $MODULE_DEFAULT_VIEW = 'blog';
    protected $MODULE_DESCRIPTION = '::sylabe:module:objects:ModuleDescription';
    protected $MODULE_VERSION = '020240529';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2024-2024';
    protected $MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    protected $MODULE_HELP = '::sylabe:module:objects:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array('desc', 'unlock', 'logout');
    protected $MODULE_REGISTERED_ICONS = array(
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 0 : Objet.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 1 : Objet.
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256',    // 2 : Objet.
    );
    protected $MODULE_APP_TITLE_LIST = array();
    protected $MODULE_APP_ICON_LIST = array();
    protected $MODULE_APP_DESC_LIST = array();
    protected $MODULE_APP_VIEW_LIST = array();

    const DEFAULT_ATTRIBS_DISPLAY_NUMBER = 10;


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuBlog':
                //$instance = $this->_applicationInstance->getCurrentObjectInstance();
                $instance = $this->_nebuleInstance->newObject($object);
                $id = $instance->getID();

                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     *
     * @return void
     */
    public function displayModule(): void
    {
        $this->_metrologyInstance->addLog('Display checks', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '28feadb6');
        $this->_displayInstance->displaySecurityAlert('small', true);
        $this->_displayCurrentEntity();

        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_displayInfo();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayLogin();
                break;
            default:
                $this->_displayLogout();
                break;
        }
    }

    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    public function displayModuleInline(): void
    {
        // Nothing
    }


    /**
     * Display view to describe entity state.
     */
    private function _displayInfo(): void
    {
        $this->_metrologyInstance->addLog('Display desc ' . $this->_applicationInstance->getCurrentObjectInstance()->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '1f00a8b1');
        if ($this->_unlocked) {
            echo "Lock"; # FIXME link
        } else {
            echo "Unlock"; # FIXME link
        }
    }

    /**
     * Display view to unlocking entity.
     */
    private function _displayLogin(): void
    {
        $this->_metrologyInstance->addLog('Display login ' . $this->_applicationInstance->getCurrentObjectInstance()->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '61a2b0dd');
        if ($this->_configurationInstance->getOptionAsBoolean('permitAuthenticateEntity')
            && $this->_applicationInstance->getCheckSecurityAll() == 'OK')
        {
            ?>

            <form method="post"
                  action="?<?php echo nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getInstanceEntity() . '&' . nebule::COMMAND_SWITCH_TO_ENTITY; ?>">
                <input type="hidden" name="id" value="<?php echo $this->_nebuleInstance->getInstanceEntity(); ?>">
                <label>
                    <input type="password" name="pwd">
                </label>
                <input type="submit" value="Unlock">
            </form>
            <?php
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'short',
            );
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation(':::err_NotPermit', $param);
        }
    }

    /**
     * Display view to locking entity.
     */
    private function _displayLogout(): void
    {
        $this->_metrologyInstance->addLog('Display logout ' . $this->_applicationInstance->getCurrentObjectInstance()->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '833de289');
        ?>

        <form method="post"
              action="?<?php echo nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getInstanceEntity() . '&' . nebule::COMMAND_SWITCH_TO_ENTITY; ?>">
            <input type="hidden" name="id" value="<?php echo $this->_nebuleInstance->getInstanceEntity(); ?>">
            <label>
                <input type="password" name="pwd">
            </label>
            <input type="submit" value="Unlock">
        </form>
        <?php
    }

    private function _displayCurrentEntity(): void
    {
        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'flagProtection' => $this->_applicationInstance->getCurrentObjectInstance()->getMarkProtected(),
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => true,
            'displaySize' => 'medium',
            'displayRatio' => 'short',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );
        echo $this->_displayInstance->getDisplayObject($this->_applicationInstance->getCurrentObjectInstance(), $param);
    }


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::sylabe:module:objects:ModuleName'] = 'Module des blogs';
        $this->_table['en-en']['::sylabe:module:objects:ModuleName'] = 'Blogs module';
        $this->_table['es-co']['::sylabe:module:objects:ModuleName'] = 'Módulo de blogs';
        $this->_table['fr-fr']['::sylabe:module:objects:MenuName'] = 'Blogs';
        $this->_table['en-en']['::sylabe:module:objects:MenuName'] = 'Blogs';
        $this->_table['es-co']['::sylabe:module:objects:MenuName'] = 'Blogs';
        $this->_table['fr-fr']['::sylabe:module:objects:ModuleDescription'] = 'Module de gestion des blogs.';
        $this->_table['en-en']['::sylabe:module:objects:ModuleDescription'] = 'Blogs management module.';
        $this->_table['es-co']['::sylabe:module:objects:ModuleDescription'] = 'Módulo de gestión de blogs.';
        $this->_table['fr-fr']['::sylabe:module:objects:ModuleHelp'] = "Ce module permet de voir et de gérer les blogs.";
        $this->_table['en-en']['::sylabe:module:objects:ModuleHelp'] = 'This module permit to see and manage blogs.';
        $this->_table['es-co']['::sylabe:module:objects:ModuleHelp'] = 'This module permit to see and manage blogs.';
    }
}
