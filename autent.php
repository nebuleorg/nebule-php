<?php
declare(strict_types=1);
namespace Nebule\Application\Autent;
use Nebule\Library\DisplayItemIconMessage; // For application and module autent
use Nebule\Library\DisplayNotify;
use Nebule\Library\Metrology;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\References;
use Nebule\Library\Translates;
use Nebule\Library\Node; // For module autent
use Nebule\Library\Modules; // For module autent
use Nebule\Library\DisplayInformation; // For module autent
use Nebule\Library\DisplayItem; // For module autent
use Nebule\Library\DisplayList; // For module autent
use Nebule\Library\DisplayObject; // For module autent
use Nebule\Library\DisplayQuery; // For module autent
use Nebule\Library\DisplaySecurity; // For module autent
use Nebule\Library\DisplayTitle; // For module autent

/*
|------------------------------------------------------------------------------------------
| /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
|------------------------------------------------------------------------------------------
|
|  [FR] Toute modification de ce code entraînera une modification de son empreinte
|       et entraînera donc automatiquement son invalidation !
|  [EN] Any modification of this code will result in a modification of its hash digest
|       and will therefore automatically result in its invalidation!
|  [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo
|       tanto lugar automáticamente a su anulación!
|  [UA] Будь-яка модифікація цього коду призведе до зміни його відбитку пальця і,
|       відповідно, автоматично призведе до його анулювання!
|
|------------------------------------------------------------------------------------------
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
    const APPLICATION_VERSION = '020240825';
    const APPLICATION_LICENCE = 'GNU GPL 2023-2024';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '9020606a70985a00f1cf73e6aed5cfd46399868871bd26d6c0bd7a202e01759c3d91b97e.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = true;
    const LIST_MODULES_INTERNAL = array('ModuleAutent');
    const LIST_MODULES_EXTERNAL = array();
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
    const DEFAULT_DISPLAY_VIEW = 'view';

    protected function _findCurrentDisplayMode(): void
    {
        // Do not suppress mode on session for application.
        $this->_currentDisplayMode = self::DEFAULT_DISPLAY_MODE;
    }

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
                max-width: 86%;
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

        if ($module != null) {
            $module->displayModule();
            $this->_displayInlineContentID();
        } else {
            $this->_metrologyInstance->addLog('Error loading ModuleAutent', Metrology::LOG_LEVEL_ERROR, __METHOD__, '57b017ad');
            $notify = new DisplayNotify($this->_applicationInstance);
            $notify->setType(DisplayItemIconMessage::TYPE_ERROR);
            $notify->setMessage('::::ERROR');
            $notify->display();
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
class Translate extends Translates
{
    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            ':::login' => 'Se connecter',
            ':::logout' => 'Se déconnecter',
            ':::return' => "Revenir à l'application",
            '::::warn_ServNotPermitWrite' => "Ce serveur n'autorise pas les modifications !",
            '::::warn_flushSessionAndCache' => "Toutes les données de connexion ont été effacées !",
            '::::info_OnlySignedLinks' => 'Uniquement des liens signés !',
            '::::info_OnlyLinksFromCodeMaster' => 'Uniquement les liens signés du maître du code !',
            '::::err_NotPermit' => 'Non autorisé sur ce serveur !',
            '::::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            '::::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            '::::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            '::::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            '::::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            '::::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            '::::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            '::::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            '::::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
        ],
        'en-en' => [
            ':::login' => 'Connecting',
            ':::logout' => 'Disconnecting',
            ':::return' => 'Return to application',
            '::::warn_ServNotPermitWrite' => 'This server do not permit modifications!',
            '::::warn_flushSessionAndCache' => 'All datas of this connexion have been flushed!',
            '::::info_OnlySignedLinks' => 'Only signed links!',
            '::::info_OnlyLinksFromCodeMaster' => 'Only links signed by the code master!',
            '::::err_NotPermit' => 'Non autorisé sur ce serveur !',
            '::::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            '::::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            '::::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            '::::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            '::::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            '::::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            '::::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            '::::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            '::::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
        ],
        'es-co' => [
            ':::login' => 'Connecting',
            ':::logout' => 'Disconnecting',
            ':::return' => 'Return to application',
            '::::warn_ServNotPermitWrite' => 'This server do not permit modifications!',
            '::::warn_flushSessionAndCache' => 'All datas of this connexion have been flushed!',
            '::::info_OnlySignedLinks' => 'Only signed links!',
            '::::info_OnlyLinksFromCodeMaster' => 'Only links signed by the code master!',
            '::::err_NotPermit' => 'Non autorisé sur ce serveur !',
            '::::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            '::::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            '::::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            '::::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            '::::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            '::::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            '::::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            '::::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            '::::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
        ],
    ];
}
