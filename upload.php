<?php
declare(strict_types=1);
namespace Nebule\Application\Upload;
use Nebule\Library\Metrology;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Translates;

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
    const APPLICATION_NAME = 'upload';
    const APPLICATION_SURNAME = 'nebule/upload';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020240815';
    const APPLICATION_LICENCE = 'GNU GPL 2016-2024';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '6666661d0923f08d50de4d70be7dc3014e73de3325b6c7b16efd1a6f5a12f5957b68336d.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = false;
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
    /**
     * Display full page.
     */
    public function _displayFull(): void
    {
        $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
        if (strpos(Application::APPLICATION_WEBSITE, '://') === false)
            $linkApplicationWebsite = 'http://' . Application::APPLICATION_WEBSITE;
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
            <?php $this->commonCSS(); ?>
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
        </head>
        <body>
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
                    $name = $this->_entitiesInstance->getInstanceEntityInstance()->getFullName();
                    if ($name != $this->_entitiesInstance->getInstanceEntity())
                        echo $name;
                    else
                        echo '/';
                    echo '<br />' . $this->_entitiesInstance->getInstanceEntity();
                    ?>
                </p>
            </div>
        </div>
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
        <div class="layout-main">
            <div class="layout-content">
                <?php
                $this->displaySecurityAlert('medium');

                // Vérifie que la création et le chargement de liens soit autorisé.
                if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitUploadLink')
                    && ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                        || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                        || $this->_unlocked
                    )
                ) {
                    // Vérifie si tous les liens pré-signés peuvent être chargés. Sinon par défaut, c'est juste ceux du maître du code.
                    if (!$this->_unlocked) {
                        $param = array(
                            'enableDisplayAlone' => true,
                            'enableDisplayIcon' => true,
                            'informationType' => 'warn',
                            'displayRatio' => 'short',
                        );
                        if ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')) {
                            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::::info_OnlySignedLinks', $param);
                        } else {
                            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::::info_OnlyLinksFromCodeMaster', $param);
                        }
                    }
                    ?>

                    <div>
                        <h1>One link upload</h1>
                        <div>
                            <form enctype="multipart/form-data" method="post"
                                  action="<?php echo '?' . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                                <input type="text" class="newlink"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_SIGNED_LINK; ?>"
                                       value=""/><br/>
                                <input type="submit"
                                       value="Upload"/>
                            </form>
                        </div>
                    </div>
                    <div>
                        <h1>Link's file upload</h1>
                        <div>
                            <form enctype="multipart/form-data" method="post"
                                  action="<?php echo '?' . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                                <input type="hidden"
                                       name="MAX_FILE_SIZE"
                                       value="<?php echo $this->_configurationInstance->getOptionUntyped('ioReadMaxData'); ?>"/>
                                <input type="file"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LINKS; ?>"/><br/>
                                <input type="submit"
                                       value="Upload"/>
                            </form>
                        </div>
                    </div>
                    <div>
                        <h1>Upload result</h1>
                        <div class="result">
                            <?php
                            $this->_actionInstance->genericActions();
                            $this->_actionInstance->specialActions();

                            ?>

                        </div>
                    </div>
                    <?php
                } else {
                    $param = array(
                        'enableDisplayAlone' => true,
                        'enableDisplayIcon' => true,
                        'informationType' => 'error',
                        'displayRatio' => 'short',
                    );
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
                }
                ?>
            </div>
        </div>
        </body>
        </html>
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
    const ACTION_APPLY_DELAY = 5;

    /**
     * Traitement des actions génériques.
     */
    public function genericActions()
    {
        $this->_metrologyInstance->addLog('Generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        // Rien.

        $this->_metrologyInstance->addLog('Generic actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
    }


    /**
     * Traitement des actions spéciales, qui peuvent être réalisées sans entité déverrouillée.
     *
     * Utilise les fonctions d'action _actionUploadLink() et _actionUploadFileLinks() de la bibliothèque nebule.
     * Le fonctionnement est identique à celui du module module_upload de l'application sylabe.
     *
     * @return void
     */
    public function specialActions()
    {
        $this->_metrologyInstance->addLog('Special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        // Vérifie que l'action de chargement de lien soit permise.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitUploadLink')
            && ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_unlocked
            )
        ) {
            // Extrait les actions.
            $this->_extractActionUploadLink();
            $this->_extractActionUploadFileLinks();

            $this->_metrologyInstance->addLog('Router Special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

            // Liens pré-signés.
            if ($this->_actionUploadLinkInstance != ''
                && is_a($this->_actionUploadLinkInstance, 'Link')
            ) {
                sleep(self::ACTION_APPLY_DELAY);
                $this->_actionUploadLink($this->_actionUploadLinkInstance);
            }

            // Fichier de liens pré-signés.
            if ($this->_actionUploadFileLinks) {
                sleep(self::ACTION_APPLY_DELAY);
                $this->_actionUploadFileLinks();
            }
        }

        $this->_metrologyInstance->addLog('Special actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
    }
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
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Message',
            '::::WARN' => 'ATTENTION !',
            '::::ERROR' => 'ERREUR !',
            '::::RESCUE' => 'Mode de sauvetage !',
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
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Message',
            '::::WARN' => 'WARNING!',
            '::::ERROR' => 'ERROR!',
            '::::RESCUE' => 'Rescue mode!',
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
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Mensaje',
            '::::WARN' => '¡ADVERTENCIA!',
            '::::ERROR' => '¡ERROR!',
            '::::RESCUE' => '¡Modo de rescate!',
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
