<?php
declare(strict_types=1);
namespace Nebule\Application\Upload;

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
 * Classe Application
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Le coeur de l'application.
 *
 */
class Application extends Applications
{
    const APPLICATION_NAME = 'upload';
    const APPLICATION_SURNAME = 'nebule/upload';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020210510';
    const APPLICATION_LICENCE = 'GNU GPL 2016-2021';
    const APPLICATION_WEBSITE = 'www.nebule.org';

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @return void
     */
    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
    }

    // Tout par défaut.
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
     * Affichage de la page.
     */
    public function display()
    {
        global $applicationVersion, $applicationLevel, $applicationLicence, $applicationWebsite, $applicationName, $applicationSurname, $applicationAuthor;

        $linkApplicationWebsite = $applicationWebsite;
        if (strpos($applicationWebsite, '://') === false) {
            $linkApplicationWebsite = 'http://' . $applicationWebsite;
        }
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
            <title><?php echo $applicationName; ?></title>
            <link rel="icon" type="image/png" href="favicon.png"/>
            <meta name="keywords" content="<?php echo $applicationSurname; ?>"/>
            <meta name="description" content="<?php echo $applicationName; ?>"/>
            <meta name="author" content="<?php echo $applicationAuthor . ' - ' . $applicationWebsite; ?>"/>
            <meta name="licence" content="<?php echo $applicationLicence; ?>"/>
            <?php $this->commonCSS(); ?>
            <style type="text/css">
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
        </head>
        <body>
        <div class="layout-header">
            <div class="header-left">
                <a href="/?<?php echo Display::DEFAULT_BOOTSTRAP_LOGO_LINK; ?>">
                    <img title="App switch" alt="[]" src="<?php echo Display::DEFAULT_APPLICATION_LOGO; ?>"/>
                </a>
            </div>
            <div class="header-right">
                &nbsp;
            </div>
            <div class="header-center">
                <p>
                    <?php
                    $name = $this->_nebuleInstance->getInstanceEntityInstance()->getFullName();
                    if ($name != $this->_nebuleInstance->getInstanceEntity()) {
                        echo $name;
                    } else {
                        echo '/';
                    }
                    echo '<br />' . $this->_nebuleInstance->getInstanceEntity();
                    ?>
                </p>
            </div>
        </div>
        <div class="layout-footer">
            <div class="footer-center">
                <p>
                    <?php echo $applicationName; ?><br/>
                    <?php echo $applicationVersion . ' ' . $applicationLevel; ?><br/>
                    (c) <?php echo $applicationLicence . ' ' . $applicationAuthor; ?> - <a
                            href="<?php echo $linkApplicationWebsite; ?>" target="_blank"
                            style="text-decoration:none;"><?php echo $applicationWebsite; ?></a>
                </p>
            </div>
        </div>
        <div class="layout-main">
            <div class="layout-content">
                <?php
                $param = array(
                    'enableDisplayAlone' => true,
                    'enableDisplayIcon' => true,
                    'informationType' => 'warn',
                    'displayRatio' => 'short',
                );
                if ($this->_nebuleInstance->getModeRescue()) {
                    $param['informationType'] = 'warn';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation('::::RESCUE', $param);
                }
                if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'WARN') {
                    $param['informationType'] = 'warn';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecurityCryptoHashMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'ERROR') {
                    $param['informationType'] = 'error';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecurityCryptoHashMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'WARN') {
                    $param['informationType'] = 'warn';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecurityCryptoSymMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'ERROR') {
                    $param['informationType'] = 'error';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecurityCryptoSymMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'WARN') {
                    $param['informationType'] = 'warn';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecurityCryptoAsymMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'ERROR') {
                    $param['informationType'] = 'error';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecurityCryptoAsymMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'ERROR') {
                    $param['informationType'] = 'error';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecurityBootstrapMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'WARN') {
                    $param['informationType'] = 'warn';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecurityBootstrapMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecuritySign() == 'WARN') {
                    $param['informationType'] = 'warn';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecuritySignMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecuritySign() == 'ERROR') {
                    $param['informationType'] = 'error';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecuritySignMessage(), $param);
                }
                if ($this->_applicationInstance->getCheckSecurityURL() == 'WARN') {
                    $param['informationType'] = 'warn';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation($this->_applicationInstance->getCheckSecurityURLMessage(), $param);
                }
                if (!$this->_nebuleInstance->getOption('permitWrite')) {
                    $param['informationType'] = 'warn';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation(':::warn_ServNotPermitWrite', $param);
                }
                if ($this->_nebuleInstance->getFlushCache()) {
                    $param['informationType'] = 'warn';
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation(':::warn_flushSessionAndCache', $param);
                }

                // Vérifie que la création et le chargement de liens soit autorisé.
                if ($this->_nebuleInstance->getOption('permitWrite')
                    && $this->_nebuleInstance->getOption('permitWriteLink')
                    && $this->_nebuleInstance->getOption('permitUploadLink')
                    && ($this->_nebuleInstance->getOption('permitPublicUploadLink')
                        || $this->_nebuleInstance->getOption('permitPublicUploadCodeMasterLink')
                        || $this->_unlocked
                    )
                ) {
                    // Vérifie si tous les liens pré-signés peuvent être chargés. Sinon par défaut c'est juste ceux du maître du code.
                    if (!$this->_unlocked) {
                        $param = array(
                            'enableDisplayAlone' => true,
                            'enableDisplayIcon' => true,
                            'informationType' => 'warn',
                            'displayRatio' => 'short',
                        );
                        if ($this->_nebuleInstance->getOption('permitPublicUploadLink')) {
                            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation(':::info_OnlySignedLinks', $param);
                        } else {
                            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation(':::info_OnlyLinksFromCodeMaster', $param);
                        }
                    }
                    ?>

                    <div>
                        <h1>One link upload</h1>
                        <div>
                            <form enctype="multipart/form-data" method="post"
                                  action="<?php echo '?' . $this->_nebuleInstance->getActionTicket(); ?>">
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
                                  action="<?php echo '?' . $this->_nebuleInstance->getActionTicket(); ?>">
                                <input type="hidden"
                                       name="MAX_FILE_SIZE"
                                       value="<?php echo $this->_nebuleInstance->getOption('ioReadMaxData'); ?>"/>
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
                    echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation(':::err_NotPermit', $param);
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
     * Traitement des actions génériques.
     */
    public function genericActions()
    {
        $this->_metrology->addLog('Generic actions', Metrology::LOG_LEVEL_DEBUG); // Log

        // Rien.

        $this->_metrology->addLog('Generic actions end', Metrology::LOG_LEVEL_DEBUG); // Log
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
        $this->_metrology->addLog('Special actions', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'action de chargement de lien soit permise.
        if ($this->_nebuleInstance->getOption('permitWrite')
            && $this->_nebuleInstance->getOption('permitWriteLink')
            && $this->_nebuleInstance->getOption('permitUploadLink')
            && ($this->_nebuleInstance->getOption('permitPublicUploadCodeMasterLink')
                || $this->_nebuleInstance->getOption('permitPublicUploadLink')
                || $this->_unlocked
            )
        ) {
            // Extrait les actions.
            $this->_extractActionUploadLink();
            $this->_extractActionUploadFileLinks();

            $this->_metrology->addLog('Router Special actions', Metrology::LOG_LEVEL_DEBUG); // Log

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

        $this->_metrology->addLog('Special actions end', Metrology::LOG_LEVEL_DEBUG); // Log
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
class Traduction extends Traductions
{
    /**
     * Constructeur.
     *
     * @param Application $applicationInstance
     * @return void
     */
    public function __construct(Application $applicationInstance)
    {
        $this->_applicationInstance = $applicationInstance;
    }


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
