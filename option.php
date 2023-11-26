<?php
declare(strict_types=1);
namespace Nebule\Application\Option;
use Nebule\Library\Configuration;
use Nebule\Library\Entity;
use Nebule\Library\Metrology;
use Nebule\Library\nebule;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
//use Nebule\Library\Modules;
use Nebule\Library\Node;
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
 * Class Application for option
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications
{
    const APPLICATION_NAME = 'option';
    const APPLICATION_SURNAME = 'nebule/option';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020231126';
    const APPLICATION_LICENCE = 'GNU GPL 2016-2023';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

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
    // Logo de l'application.
    const DEFAULT_LOGO_APP0 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQfgDA0VKApKb6LRAAAEwklEQVRo3u2Zb2hbVRTAz0uWpFvSZmYlbdpXaiZz1jVtYxO2NYRMq5S1tJQNHGwy/FLwSwduUMSgzj/ph4IdpiDC8EuRDZVJXe1mYJ3Emq4l0TRLaofBxrF0GSFGEhtsmmXxQ7HvvvdukpeXp1bs/XYP557ze+/de+455xFwHv7VIQLYBvjPAJAye7uzb1hH5NUgYFjn7LO3kzLuVglum/C45ozR1CYmAGLxi07r7RzWlK1lwFKtAsjmXAt295WIYAC3jh3SUTM8AuX+rzHnP/yFQAC5t+jz1VQ4KiZUSrlcIgHIZFKpeCKbI9UKOcP428Vt7+CzcRTyp7TUTCytkO55bPsY/o0Ao3q+xrmsLLoJ
b/R0Gvg/37Tn+akyNmGlePZE8z62PBzxhjwrnthi8n4aoE52oMpQbajXa0kNU7PT4Fd2fPp7ltcbqBR//9K+x+my9YzD/ZHvWhS/olv9SmuXUSqhS4O/tH+SH6EAgP8k8+knZwdn7q4VfqWNFWPm3g66LBDUXcqnL4Yj+b49Gv0AwpHBz1/zJh4W++qJh5eXQ8vG2qpKSqbeY1aMB0sCGNWffBadz/ufufRDguvW8yU/9L6gImsoyd46ZdLxoOgnoMd8atz0dE7xOAE9z2HPD/2OEKE3Ht79vJ+Pe4DOqXk/Tn5Id1yDBThjxKmHI5YJvlHAMhHGXsmoJxGVbpjacMrW6+lHfAHSj6zXcXJTG5WybAIMNYsxqc7k7Pi9ciL9+L3JWczOJ4aaWQCt9WzF9czgTLmXzeDMeoYtpbxtArhCsThTzeEuFnaKj7trDjdTFou7Qqw4cDO6e62pZtdOVPHsV8FU+RduYvWUke7+otPqZ72BHLx+236Dvv/zxfzSxrUoehZWU/SMkpYPvLd0J0TNv
CGhkg7UUjhKT2hpADlAT4JnRSgA1JKYyBXKiFRKZFlMMIAY3gMGQI4k1otJoQBQS3J5QQAJkkzcTwsFgFqSSLZ2VpxBoladTCgnqKVMpiBACgk8B6qEAkAtpVIFAeJI1mOoFgoAtRRPFAAgIIscUkO9YACIpWyOyAdAgK2FVFNzvVYoANQSqba1EDiAjfoeLbBJTbdaCPfdarRgUcgHLCjC5m04rBs4grYXAACqMpeXywd4v2N/AzrftbOpZvcf01HGG+htYboH6DI2VpTrvrGii5VrVqtMWtYn+OYOe7FUMmYuF2DMzCzVAAB8KyyAkUAW03nq7TjdUI770w3MMm3jJIwEWADhtGsBZ8J2VMY7XMtEtqM4uWshnMYcQ7sbp0xqnP18AZz97IKd6QkBuBKZw1YyB3XTPbyaEz0HdfjSDO0g5inPR/Wv9tHLM8tEKQWKTOTsp7u/cPWst4Tq2PHArNhbh3yImpef/DXqS3Ldel+eePoJeqvm1LdbtEGxlVs0QjSpAAJB3k2qf6ZNl7dHtF
nfBpXJw/v5ub9wNd/WKykpxR8fLoPLyu1m9Q5+y378WSKm/7DIZOmhR1CAOT+9f/bmZ+8usbXeaHrnRfoqLraLngIAgI+XAj/VishaEQEQi3/w9flFnNZMTPrbRosjm/tu4dzkOTcXAIL7r1tSNtTcWu8KWf25vMZsOpPWtzISCHOuK4ntf8f/e4A/AcO8sjeKv7mpAAAAAElFTkSuQmCC';
    const DEFAULT_LOGO_MENU = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAABAElEQVR42u3XvQmEQBCG4e8OYxuwEvvYMu1DBDESw2VRtwLB0L30ghMM5Eb0nVTx52Fm9Hs555IeXG89vAAAAAAAAAAAAAAAAAAAAAB4YmVnXMQ5Z/LwVVXZd4DVy591b3YAAAAAAID1p8jy3tlVHoQRAAAAsgBZgCzADgAAAADIAmQBRgAAAMgCZAGyADsAAAAAIAuQBRgBAAC4fhY4UkVRqCzLQ+eGENS27b06IMaopmm0LMvuOdu2yXuvYRjuOQLTNKnv+93j4ziq6zqt63rfHRBjVF3Xpm1vvgS/x8Gi7U2W4K9xSCkpz3OFEP7a9pcAkKR5nvkPAAAAAACwrA/2026oVwXU/wAAAABJRU5ErkJggg==';

    // Icône transparente.
    const DEFAULT_ICON_ALPHA_COLOR = '87b260416aa0f50736d3ca51bcb6aae3eff373bf471d5662883b8b6797e73e85.sha2.256';

    // Couleurs des icônes.
    const DEFAULT_ICON_COLOR = '#537053';
    const CRITICAL_ICON_COLOR = '#ff0000';
    const CAREFUL_ICON_COLOR = '#ffaa00';
    const USEFUL_ICON_COLOR = '#535370';

    // Les vues.
    const VIEW_MENU = 'menu';
    const VIEW_GLOBAL_AUTHORITIES = 'gauth';
    const VIEW_LOCAL_AUTHORITIES = 'lauth';
    const VIEW_OPTIONS = 'opts';
    const VIEW_APPLICATIONS = 'apps';
    const VIEW_RECOVERY = 'recv';

    // Application de référence pour les visualisations d'objets.
    const REFERENCE_DISPLAY_APPLICATION = 'dd11110000000000006e6562756c65206170706c69636174696f6e73000000000000dd1111';

    /**
     * Liste des vues disponibles.
     *
     * @var array of string
     */
    protected $_listDisplayViews = array(
        self::VIEW_MENU,
        self::VIEW_OPTIONS,
        self::VIEW_APPLICATIONS,
        self::VIEW_RECOVERY,
        self::VIEW_LOCAL_AUTHORITIES,
        self::VIEW_GLOBAL_AUTHORITIES,
    );


    /**
     * Display full page.
     */
    protected function _displayFull(): void
    {
        $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
        if (strpos(Application::APPLICATION_WEBSITE, '://') === false)
            $linkApplicationWebsite = 'http' . '://' . Application::APPLICATION_WEBSITE;

        // Récupère l'entité déverrouillée ou l'entité instance du serveur.
        if ($this->_unlocked) {
            $username = $this->_nebuleInstance->getCurrentEntityInstance()->getFullName();
            $userID = $this->_nebuleInstance->getCurrentEntity();
        } else {
            $username = $this->_nebuleInstance->getInstanceEntityInstance()->getFullName();
            $userID = $this->_nebuleInstance->getInstanceEntity();
        }
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
            <title><?php echo Application::APPLICATION_NAME; ?></title>
            <link rel="icon" type="image/png" href="favicon.png"/>
            <meta name="keywords" content="<?php echo Application::APPLICATION_SURNAME; ?>"/>
            <meta name="description" content="<?php echo Application::APPLICATION_NAME; ?>"/>
            <meta name="author" content="<?php echo Application::APPLICATION_AUTHOR . ' - ' . Application::APPLICATION_WEBSITE; ?>"/>
            <meta name="licence" content="<?php echo Application::APPLICATION_LICENCE; ?>"/>
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

                .header-center > div {
                    margin: auto 3px 3px 3px;
                    overflow: hidden;
                    white-space: nowrap;
                    color: #454545;
                    text-align: center;
                }

                h1 {
                    font-family: monospace;
                    font-size: 1.8em;
                    font-weight: normal;
                    color: #ababab;
                    margin-top: 10px;
                }

                h2 {
                    font-family: monospace;
                    font-size: 1.4em;
                    color: #ababab;
                    font-weight: normal;
                    clear: both;
                    margin-top: 24px;
                    margin-bottom: 8px;
                    margin-left: 32px;
                }

                .layout-header {
                    border-bottom-style: solid;
                    border-bottom-color: #222222;
                    border-bottom-width: 3px;
                }

                .header-right {
                    width: 136px;
                }

                .headerunlock {
                    position: fixed;
                    top: 0;
                    text-align: center;
                    width: 100%;
                    padding: 3px;
                    background: #ababab;
                    border-bottom-style: solid;
                    border-bottom-color: #e0000e;
                    border-bottom-width: 3px;
                    margin: 0px;
                }

                .headerunlock a:link, .headerunlock a:visited {
                    font-weight: bold;
                    text-decoration: none;
                    color: #ff4000;
                    font-size: 1.4em;
                }

                .headerunlock a:hover, .headerunlock a:active {
                    font-weight: bold;
                    text-decoration: underline;
                    color: #ff4000;
                    font-size: 1.4em;
                }

                .headerunlockother {
                    position: fixed;
                    top: 0;
                    text-align: center;
                    width: 100%;
                    padding: 3px;
                    background: #ababab;
                    border-bottom-style: solid;
                    border-bottom-color: #0e00e0;
                    border-bottom-width: 3px;
                    margin: 0px;
                }

                .headerunlockother a:link, .headerunlockother a:visited {
                    font-weight: bold;
                    text-decoration: none;
                    color: #ff4000;
                    font-size: 1.4em;
                }

                .headerunlockother a:hover, .headerunlockother a:active {
                    font-weight: bold;
                    text-decoration: underline;
                    color: #ff4000;
                    font-size: 1.4em;
                }

                .layout-footer {
                    border-top-style: solid;
                    border-top-color: #222222;
                    border-top-width: 3px;
                }

                .footerunlock {
                    position: fixed;
                    bottom: 0;
                    text-align: center;
                    width: 100%;
                    padding: 3px;
                    background: #ababab;
                    border-top-style: solid;
                    border-top-color: #e0000e;
                    border-top-width: 3px;
                    margin: 0px;
                }

                .footerunlockother {
                    position: fixed;
                    bottom: 0;
                    text-align: center;
                    width: 100%;
                    padding: 3px;
                    background: #ababab;
                    border-top-style: solid;
                    border-top-color: #0e00e0;
                    border-top-width: 3px;
                    margin: 0px;
                }

                .textListObjects {
                    background: none;
                }

                .objectDisplayMediumLong {
                    width: 100%;
                }

                #sync {
                    clear: both;
                    width: 100%;
                    height: 100px;
                }

                #error {
                    color: #ffffff;
                    font-weight: bold;
                }

                #actions, #apps, #recoveries, #authorities, #options, #help, #select, #menuSelect, #end {
                    clear: both;
                    padding: 10px;
                    padding-right: 20px;
                    padding-left: 20px;
                }

                .optionItem, .recoveryItem, .appItem {
                    width: 98%;
                    min-height: 74px;
                    margin: 5px;
                    margin-left: 1%;
                    margin-right: 1%;
                    padding: 0;
                    background: #ababab;
                    text-align: left;
                    color: #454545;
                }

                .optionLogo, .appLogo {
                    float: left;
                    width: 64px;
                    height: 64px;
                    margin: 0;
                }

                .optionInfos, .recoveryInfos, .authorityInfos, .appInfos {
                    padding: 5px;
                    overflow: hidden;
                }

                .optionInfos .name {
                    margin-bottom: 5px;
                }

                .optionInfos .namevalue {
                    font-weight: bold;
                    color: #000000;
                }

                .optionInfos .type {
                    font-style: italic;
                    float: right;
                    margin-left: 10px;
                }

                .optionInfos .type img {
                    height: 16px;
                    width: 16px;
                }

                .optionInfos .currentvalue {
                    font-weight: bold;
                }

                .optionInfos .currentvaluechanged {
                    font-weight: bold;
                    color: #ffffff;
                }

                .optionItem .desc {
                    clear: both;
                    padding: 5px;
                }

                .appItemList {
                    clear: both;
                    background: #ababab;
                    color: #454545;
                    margin: 8px;
                    min-height: 80px;
                }

                .appLink {
                    float: left;
                    margin-right: 8px;
                    height: 64px;
                    width: 64px;
                    padding: 8px;
                    color: #ffffff;
                    overflow: hidden;
                }

                .appInfos a:link, .appInfos a:visited {
                    font-weight: normal;
                    text-decoration: none;
                    color: #454545;
                }

                .appInfos a:hover, .appInfos a:active {
                    font-weight: normal;
                    text-decoration: underline;
                    color: #ffffff;
                }

                .appRef, .authorityID, .recoveryID {
                    font-size: 0.6em;
                }

                .appSigner {
                    margin-bottom: 5px;
                }

                .appName, .authorityName, .recoveryName {
                    font-weight: bold;
                    font-size: 1.4em;
                }

                .appShortname {
                    font-size: 1.6em;
                    font-weight: normal;
                    text-decoration: none;
                    color: #ffffff;
                    margin: 0;
                }

                .appTitle {
                    font-weight: bold;
                }

                .appActions, .recoveryActions, .authorityActions {
                    float: right;
                    margin-right: 8px;
                }

                .appActions img {
                    margin: 3px;
                }

                .authorityItemList, .recoveryItemList {
                    clear: both;
                    background: #ababab;
                    color: #454545;
                    margin: 8px;
                    padding: 0;
                    min-height: 64px;
                }

                .authorityLogo, .recoveryLogo {
                    float: left;
                    width: 128px;
                    height: 64px;
                    margin: 0;
                }

                #helppart {
                    clear: both;
                    padding-top: 10px
                }

                #helppart img {
                    width: 16px;
                    height: 16px;
                    margin: 0;
                    margin: 2px;
                    margin-right: 5px;
                }

                #syncallapps {
                    margin: 10px;
                    clear: both;
                }

                #syncallapps img {
                    margin-right: 5px;
                }

                #select a:link, #select a:visited, #syncallapps a:link, #syncallapps a:visited, #help a:link, #help a:visited {
                    color: #ababab;
                }

                #select a:hover, #select a:active, #syncallapps a:hover, #syncallapps a:active, #help a:hover, #help a:active {
                    color: #ffffff;
                }

                #check {
                    clear: both;
                    padding: 0;
                    padding-top: 40px;
                    padding-bottom: 20px;
                }

                #menuSelect a:link, #menuSelect a:visited {
                    color: #454545;
                }

                #menuSelect a:hover, #menuSelect a:active {
                    color: #ffffff;
                }

                .oneAction {
                    margin: 2px;
                    padding: 5px;
                    float: left;
                    background: #ababab;
                    width: 206px;
                    min-height: 64px;
                }

                @media screen and (max-height: 500px) {
                    .oneAction {
                        padding: 0;
                        padding-top: 0;
                        padding-bottom: 0;
                        width: 186px;
                        min-height: 32px;
                    }
                }

                @media screen and (max-width: 575px) {
                    .oneAction {
                        padding: 0;
                        padding-top: 0;
                        padding-bottom: 0;
                        width: 186px;
                        min-height: 32px;
                    }
                }

                .oneAction-icon {
                    float: left;
                    margin-right: 5px;
                }

                .oneAction-icon img {
                    height: 64px;
                    width: 64px;
                }

                @media screen and (max-height: 500px) {
                    .oneAction-icon img {
                        height: 32px;
                        width: 32px;
                    }
                }

                @media screen and (max-width: 575px) {
                    .oneAction-icon img {
                        height: 32px;
                        width: 32px;
                    }
                }

                .oneAction-title p {
                    font-size: 1.1em;
                    font-weight: bold;
                }

                input {
                    background: #bbbbbb;
                    color: #454545;
                    margin: 2px;
                    border: 0;
                    box-shadow: unset;
                    padding: 1px;
                    background-origin: border-box;
                }

                input[type=submit] {
                    font-weight: bold;
                }

                input[type=password], input[type=text], input[type=email] {
                    padding: 2px;
                }
            </style>
        </head>
        <body>
        <div class="layout-header header<?php if ($this->_unlocked) {
            echo 'unlock';
            if ($this->_nebuleInstance->getCurrentEntity() != $this->_nebuleInstance->getInstanceEntity()) echo 'other';
        } ?>">
            <div class="header-left">
                <a href="/?<?php echo Displays::DEFAULT_BOOTSTRAP_LOGO_LINK; ?>">
                    <img title="App switch" alt="[]" src="<?php echo Displays::DEFAULT_APPLICATION_LOGO; ?>"/>
                </a>
            </div>
            <div class="header-left">
                <a href="/?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_MENU; ?>">
                    <img title="Menu" alt="[]" src="<?php echo self::DEFAULT_LOGO_MENU; ?>"/>
                </a>
            </div>
            <div class="header-right">
                &nbsp;
            </div>
            <div class="header-center">
                <div>
                    <?php
                    if ($username != $userID) {
                        echo $username;
                    } else {
                        echo '/';
                    }
                    echo '<br />' . $userID . '<br />';

                    if ($this->_unlocked) {
                        echo '&gt;&nbsp;';
                        $this->_applicationInstance->getDisplayInstance()->displayHypertextLink('Lock', '?' . nebule::COMMAND_LOGOUT_ENTITY . '&' . nebule::COMMAND_FLUSH);
                        echo '&nbsp;&lt;';
                    } else {
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
                    ?>
                </div>
            </div>
        </div>
        <div class="layout-footer footer<?php if ($this->_unlocked) {
            echo 'unlock';
            if ($this->_nebuleInstance->getCurrentEntity() != $this->_nebuleInstance->getInstanceEntity()) echo 'other';
        } ?>">
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
        // Vérifie les tests de sécurité. Pas d'affichage des options si problème.
        if ($this->_applicationInstance->getCheckSecurityAll() == 'OK') {
            if ($this->_nebuleInstance->getModeRescue()) {
                $this->displayMessageWarning('::::RESCUE');
            }

            $this->_displayActions();

            switch ($this->getCurrentDisplayView()) {
                case self::VIEW_GLOBAL_AUTHORITIES:
                    $this->_displayGlobalAuthorities();
                    break;
                case self::VIEW_LOCAL_AUTHORITIES:
                    $this->_displayLocalAuthorities();
                    break;
                case self::VIEW_OPTIONS:
                    $this->_displayOptions();
                    break;
                case self::VIEW_APPLICATIONS:
                    $this->_displayApplications();
                    break;
                case self::VIEW_RECOVERY:
                    $this->_displayRecovery();
                    break;
                default:
                    $this->_displayMenu();
            }
            $this->_displayEnd();
        } else
            $this->_displayChecks();
        $this->_htmlEnd();
    }


    private function _displayActions()
    {
        ?>

        <div class="layoutAloneItem" id="actions">
            <div class="aloneItemContent" id="actionscontent">
                <?php
                $this->_actionInstance->genericActions();
                $this->_actionInstance->specialActions();
                ?>

            </div>
        </div>
        <?php
    }


    private function _displayMenu()
    {
        $list = array();
        $list[0]['icon'] = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_LL);
        $list[0]['title'] = 'Options';
        $list[0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_OPTIONS;
        $list[1]['icon'] = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_LF);
        $list[1]['title'] = 'Global authorities';
        $list[1]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_GLOBAL_AUTHORITIES;
        $list[2]['icon'] = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_LF);
        $list[2]['title'] = 'Local authorities';
        $list[2]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_LOCAL_AUTHORITIES;
        $list[4]['icon'] = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_LS);
        $list[4]['title'] = 'Applications';
        $list[4]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_APPLICATIONS;
        $list[5]['icon'] = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_LK);
        $list[5]['title'] = 'Local recovery';
        $list[5]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_RECOVERY;
        ?>

        <div id="menuSelect">
            <?php
            echo $this->getDisplayMenuList($list, 'Medium');
            ?>

        </div>
        <?php

        $param = array(
            'enableDisplayAlone' => true,
            'enableDisplayIcon' => true,
            'informationType' => 'information',
            'displayRatio' => 'long',
        );
        $message = "The 'option' application display current configurations such as options, applications and entities with special capabilities.";
        $message .= " Local authorities can modify some of those configurations.";
        echo $this->getDisplayInformation($message, $param);
    }


    /**
     * Affichage des autorités globales.
     * Ces autorités sont définies par le maître du tout puppetmaster, et par le code.
     *
     * @return void
     */
    private function _displayGlobalAuthorities()
    {
        // Titre
        echo $this->getDisplayTitle('Global authorities', null, false);

        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => false,
            'enableDisplayStatus' => false,
            'enableDisplayContent' => false,
            'enableDisplayJS' => false,
            'enableDisplayLink2Object' => true,
            'displaySize' => 'medium',
            'displayRatio' => 'short',
            'status' => '',
            'flagProtection' => false,
        );

        $list = array();
        $i = 0;

        // Puppetmaster
        $list[$i]['object'] = $this->_nebuleInstance->getPuppetmasterInstance();
        $list[$i]['param'] = $param;
        $list[$i]['param']['flagProtection'] = true;
        if ($this->_nebuleInstance->getPuppetmaster() == $this->_nebuleInstance->getInstanceEntity())
            $list[$i]['param']['flagMessage'] = 'Instance entity';
        if ($this->_nebuleInstance->getPuppetmaster() == $this->_nebuleInstance->getDefaultEntity()) {
            if ($list[$i]['param']['flagMessage'] != '')
                $list[$i]['param']['flagMessage'] .= ', ';
            $list[$i]['param']['flagMessage'] .= 'Default entity';
        }

        // Security and code masters
        foreach (array_merge($this->_nebuleInstance->getSecurityAuthoritiesInstance(), $this->_nebuleInstance->getCodeAuthoritiesInstance()) as $instance)
        {
            $i++;
            $list[$i]['object'] = $instance;
            $list[$i]['param'] = $param;
            $list[$i]['param']['enableDisplayRefs'] = true;
            $list[$i]['param']['objectRefs'][0] = $this->_nebuleInstance->getPuppetmasterInstance();
            if ($instance->getID() == $this->_nebuleInstance->getInstanceEntity())
                $list[$i]['param']['flagMessage'] = 'Instance entity';
            if ($instance->getID() == $this->_nebuleInstance->getDefaultEntity()) {
                if ($list[$i]['param']['flagMessage'] != '')
                    $list[$i]['param']['flagMessage'] .= ', ';
                $list[$i]['param']['flagMessage'] .= 'Default entity';
            }
        }

        // Display list.
        echo $this->getDisplayObjectsList($list, 'medium');

        // Display information.
        $param = array(
            'enableDisplayAlone' => true,
            'enableDisplayIcon' => true,
            'informationType' => 'information',
            'displayRatio' => 'long',
        );
        $message = "The global authorities are entities with specials capabilities on common things like code.";
        $message .= " Global authorities can't be removed or disabled.";
        echo $this->getDisplayInformation($message, $param);
    }


    /**
     * Affichage des autorités locales.
     * Ces autorités sont définies par des options pour les autorités locales primaires,
     *   et par des liens pour les autorités locales secondaires.
     *
     * @return void
     */
    private function _displayLocalAuthorities()
    {
        $refAuthority = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_ENTITE_AUTORITE_LOCALE);

        // Titre
        echo $this->getDisplayTitle('Primary local authorities', null, false);

        $listEntities = $this->_nebuleInstance->getLocalPrimaryAuthoritiesInstance();
        $listOkEntities = array(
            $this->_nebuleInstance->getPuppetmaster() => true,
        /*    $this->_nebuleInstance->getSecurityMaster() => true,
            $this->_nebuleInstance->getCodeMaster() => true,
            $this->_nebuleInstance->getDirectoryMaster() => true,
            $this->_nebuleInstance->getTimeMaster() => true,*/
        );
        foreach (array_merge(
                $this->_nebuleInstance->getSecurityAuthorities(),
                $this->_nebuleInstance->getCodeAuthorities(),
                $this->_nebuleInstance->getDirectoryAuthorities(),
                $this->_nebuleInstance->getTimeAuthorities())
            as $id)
        {
            $listOkEntities[$id] = true;
        }

        // Affiche les entités autorités.
        $list = array();
        $i = 0;

        foreach ($listEntities as $instance) {
            $id = $instance->getID();
            if (!isset($listOkEntities[$id])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => true,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => false,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'enableDisplayLink2Object' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'status' => '',
                    'flagProtection' => false,
                );

                if ($id == $this->_nebuleInstance->getInstanceEntity()
                    && $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority')
                ) {
                    $list[$i]['param']['flagMessage'] = 'Instance entity';
                    $list[$i]['param']['flagProtection'] = true;
                }
                if ($id == $this->_nebuleInstance->getDefaultEntity()
                    && $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')
                ) {
                    if ($list[$i]['param']['flagMessage'] != '') {
                        $list[$i]['param']['flagMessage'] .= ', ';
                    }
                    $list[$i]['param']['flagMessage'] .= 'Default entity';
                    $list[$i]['param']['flagProtection'] = true;
                }

                // Marque comme vu.
                $listOkEntities[$id] = true;
                $i++;
            }
        }
        unset($instance, $id);

        // Affichage
        echo $this->getDisplayObjectsList($list, 'medium');
        unset($list);

        // Titre des entités secondaires.
        echo $this->getDisplayTitle('Secondary local authorities', null, false);

        if ($this->_configurationInstance->getOptionAsBoolean('permitLocalSecondaryAuthorities')) {
            // Liste les entités marquées comme entités de recouvrement.
            $listEntities = $this->_nebuleInstance->getLocalAuthoritiesInstance();
            $listSigners = $this->_nebuleInstance->getLocalAuthoritiesSigners();

            // Affiche les entités autorités.
            $list = array();
            $i = 0;

            foreach ($listEntities as $instance) {
                $id = $instance->getID();
                if (!isset($listOkEntities[$id])
                    && $instance->getType('all') == Entity::ENTITY_TYPE
                    && $instance->getIsPublicKey()
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => true,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => false,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'enableDisplayLink2Object' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                        'status' => '',
                        'flagProtection' => false,
                    );

                    if ($this->_unlocked
                        && $listSigners[$id] == $this->_nebuleInstance->getCurrentEntity()
                        && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitUploadLink'))
                        && ($id != $this->_nebuleInstance->getInstanceEntity()
                            || !$this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority')
                        )
                        && ($id != $this->_nebuleInstance->getDefaultEntity()
                            || !$this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')
                        )
                    ) {
                        $list[$i]['param']['selfHookList'][0]['name'] = 'Remove';
                        $list[$i]['param']['selfHookList'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                        $list[$i]['param']['selfHookList'][0]['link'] = '/?'
                            . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_nebuleInstance->getInstanceEntity() . '_' . $id . '_' . $refAuthority
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    }

                    // Marque comme vu.
                    $listOkEntities[$id] = true;
                    $i++;
                }
            }
            unset($instance, $id);

            if ($this->_unlocked) {
                // Ajoute le message.
                $list[$i]['information'] = 'On authority removing, need a second page reload to update the liste.';
                $list[$i]['param']['enableDisplayIcon'] = true;
                $list[$i]['param']['informationType'] = 'information';
            }

            // Affichage
            echo $this->getDisplayObjectsList($list, 'medium');
            unset($list, $listEntities, $listSigners);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'warn',
            );
            echo $this->getDisplayInformation('Not authorized!', $param);
        }

        // Affiche les entités à ajouter.
        if ($this->_unlocked
            && $this->_configurationInstance->getOptionAsBoolean('permitLocalSecondaryAuthorities')
            && (
                ($this->_nebuleInstance->getCurrentEntity() == $this->_nebuleInstance->getInstanceEntity()
                    && $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority')
                )
                ||
                ($this->_nebuleInstance->getCurrentEntity() == $this->_nebuleInstance->getDefaultEntity()
                    && $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')
                )
            )
            && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitUploadLink'))
            //&& $this->_configuration->getOption('permitRecoveryEntities')
        ) {

            // Titre
            echo $this->getDisplayTitle('Add entities as local authority', null, false);

            // Lister les entités.
            $listEntities = $this->_nebuleInstance->getListEntitiesInstances();
            $listAuthorities = $this->_nebuleInstance->getAuthorities();

            // Affiche les entités à ajouter.
            $list = array();
            $i = 0;

            foreach ($listEntities as $instance) {
                $id = $instance->getID();
                if (!isset($listOkEntities[$id])
                    && $instance->getType('all') == Entity::ENTITY_TYPE
                    && $instance->getIsPublicKey()
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => true,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => false,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'enableDisplayLink2Object' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                        'status' => '',
                        'flagProtection' => false,
                    );

                    if ($this->_unlocked
                        && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitUploadLink'))
                    ) {
                        $list[$i]['param']['selfHookList'][0]['name'] = 'Add';
                        $list[$i]['param']['selfHookList'][0]['icon'] = Displays::DEFAULT_ICON_LL;
                        $list[$i]['param']['selfHookList'][0]['link'] = '/?'
                            . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_' . $this->_nebuleInstance->getInstanceEntity() . '_' . $id . '_' . $refAuthority
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    }

                    // Marque comme vu.
                    $listOkEntities[$id] = true;
                    $i++;
                }
            }
            unset($instance, $id);

            if ($this->_unlocked) {
                // Ajoute le message.
                $list[$i]['information'] = 'On new authority add, need a second page reload to update the liste.';
                $list[$i]['param']['enableDisplayIcon'] = true;
                $list[$i]['param']['informationType'] = 'information';
            }

            // Affichage
            echo $this->getDisplayObjectsList($list, 'medium');
            unset($list, $listEntities, $listSigners);
        }

        $param = array(
            'enableDisplayAlone' => true,
            'enableDisplayIcon' => true,
            'informationType' => 'information',
            'displayRatio' => 'long',
        );
        $message = "The local authorities are entities with specials capabilities on local server.<br /><br />\n";
        $message .= "There are two levels of local authorities:<br />\n";
        $message .= " 1: Local forced authorities defined by options, known as primary local authorities.<br />\n";
        $message .= " 2: Local authorities defined by links from primary local authorities, known as secondary local authorities.<br /><br />\n";
        $message .= "An entity can be added as secondary authority and later can be removed.<br />\n";
        $message .= " Primary authorities can't be removed, they are forced by two options on the environment file : 'permitInstanceEntityAsAuthority' and 'permitDefaultEntityAsAuthority'.";
        echo $this->getDisplayInformation($message, $param);
    }


    /**
     * Affiche les options par catégorie.
     *
     * @return void
     */
    private function _displayOptions()
    {
        // Titre
        echo $this->getDisplayTitle('Options', null, false);
        ?>

        <div id="options">
            <?php
            $listOptions = Configuration::getListOptions();
            $listCategoriesOptions = Configuration::getListCategoriesOptions();
            $listOptionsCategory = Configuration::getListOptionsCategory();
            $listOptionsType = Configuration::getListOptionsType();
            $listOptionsWritable = Configuration::getListOptionsWritable();
            $listOptionsDefaultValue = Configuration::getListOptionsDefaultValue();
            $listOptionsCriticality = Configuration::getListOptionsCriticality();
            $listOptionsDescription = Configuration::getListOptionsDescription();

            // Liste toutes les catégories.
            foreach ($listCategoriesOptions as $optionCategory) {
                ?>

                <h2>&gt; <?php echo $optionCategory; ?></h2>
                <?php
                // Liste toutes lies options de la catégorie.
                foreach ($listOptions as $optionName) {
                    // Vérifie que l'option est dans la catégorie en cours.
                    if ($listOptionsCategory[$optionName] != $optionCategory)
                        continue;

                    // Extrait les propriétés de l'option.
                    $optionValue = $this->_configurationInstance->getOptionUntyped($optionName);
                    $optionID = $this->_nebuleInstance->newObject($this->_nebuleInstance->getNIDfromData($optionName, 'sha2.256'));
                    $optionValueDisplay = (string)$optionValue;
                    $optionType = $listOptionsType[$optionName];
                    $optionWritable = $listOptionsWritable[$optionName];
                    $optionWritableDisplay = 'writable';
                    $optionDefaultValue = $listOptionsDefaultValue[$optionName];
                    $optionDefaultDisplay = (string)$optionDefaultValue;
                    $optionCriticality = $listOptionsCriticality[$optionName];
                    $optionDescription = $listOptionsDescription[$optionName];
                    $optionLocked = ($this->_configurationInstance->getOptionFromEnvironmentUntyped($optionName) !== null);

                    // Prépare l'affichage du status de verrouillage ou de lecture seule.
                    if ($optionLocked)
                        $optionWritableDisplay = 'forced';
                    elseif (!$optionWritable)
                        $optionWritableDisplay = 'locked';

                    // Prépare l'affichage des booléens.
                    if ($optionType == 'boolean') {
                        $optionValueDisplay = 'false';
                        if ($optionValue)
                            $optionValueDisplay = 'true';
                        $optionDefaultDisplay = 'false';
                        if ($optionDefaultValue)
                            $optionDefaultDisplay = 'true';
                    }

                    // Prépare la couleur du status de l'option.
                    if ($optionCriticality == 'critical')
                        $optionColorOnChange = self::CRITICAL_ICON_COLOR;
                    elseif ($optionCriticality == 'careful')
                        $optionColorOnChange = self::CAREFUL_ICON_COLOR;
                    else // $optionCriticity == 'useful'
                    {
                        $optionColorOnChange = self::USEFUL_ICON_COLOR;
                        $optionCriticality = 'useful';
                    }

                    // Détermine si l'option a sa valeur par défaut.
                    $isDefault = true;
                    if ($optionValue != $optionDefaultValue)
                        $isDefault = false;
                    ?>

                    <div class="optionItem">
                        <a name="<?php echo $optionName; ?>"></a>
                        <div class="optionLogo"><?php $this->displayObjectColor($optionID, '/'); ?></div>
                        <div class="optionInfos">
                            <div class="type"><?php echo $optionCriticality; ?>, <?php echo $optionWritableDisplay; ?>
                                <img src="o/<?php echo self::DEFAULT_ICON_ALPHA_COLOR; ?>"
                                     style="background:<?php if ($isDefault) echo self::DEFAULT_ICON_COLOR; else echo $optionColorOnChange; ?>"/>
                            </div>
                            <div class="name">Option <span class="namevalue"><?php echo $optionName; ?></span></div>
                            <div class="type">Type <?php echo $optionType; ?></div>
                            <div class="current">Current = <span
                                        class="currentvalue<?php if (!$isDefault) echo 'changed'; ?>"><?php echo $optionValueDisplay; ?></span>
                            </div>
                            <?php
                            if (!$isDefault) {
                                ?>

                                <div class="default">Default = <?php echo $optionDefaultDisplay; ?></div>
                                <?php
                            } else {
                                ?>

                                <div class="default">Default.</div>
                                <?php
                            }
                            ?>

                            <div class="change">
                                <?php
                                // Si on ne peut écrire.
                                if (!$optionWritable
                                    || $optionLocked
                                ) {
                                    ?>

                                    &nbsp;
                                    <?php
                                } elseif (!$this->_configurationInstance->getOptionAsBoolean('permitWrite')) {
                                    ?>

                                    Global write lock.
                                    <?php
                                } elseif (!$this->_unlocked
                                    || $this->_nebuleInstance->getCurrentEntity() != $this->_nebuleInstance->getInstanceEntity()
                                ) {
                                    ?>

                                    Need local instance entity unlocked to modify this option.
                                    <?php
                                } else {
                                    ?>

                                    <form method="get" action="">
                                        <input type="hidden" name="<?php echo Action::COMMAND_OPTION_NAME; ?>"
                                               value="<?php echo $optionName; ?>">
                                        <input type="hidden" name="<?php echo nebule::COMMAND_SELECT_TICKET; ?>"
                                               value="<?php echo $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                                        <?php
                                        if ($optionType == 'string') {
                                            ?>

                                            <input type="text" name="<?php echo Action::COMMAND_OPTION_VALUE; ?>"
                                                   size="40">
                                            <?php
                                        } elseif ($optionType == 'boolean') {
                                            ?>

                                            <input type="hidden" name="<?php echo Action::COMMAND_OPTION_VALUE; ?>"
                                                   value="<?php if ($optionValue === false) echo 'true'; else echo 'false'; ?>">
                                            <?php
                                        } elseif ($optionType == 'integer') {
                                            ?>

                                            <input type="text" name="<?php echo Action::COMMAND_OPTION_VALUE; ?>"
                                                   size="8">
                                            <?php
                                        } else {
                                            ?>

                                            What!?
                                            <?php
                                        }
                                        ?>

                                        <input type="submit" value="<?php echo 'Change'; ?>">
                                    </form>
                                    <?php
                                }
                                ?>

                            </div>
                        </div>
                        <div class="desc">
                            <?php echo $optionDescription; ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>

        </div>
        <div style="clear:both;"></div>

        <div id="help">
            <div id="helppart">
                <img src="o/<?php echo self::DEFAULT_ICON_ALPHA_COLOR; ?>"
                     style="background:<?php echo self::DEFAULT_ICON_COLOR; ?>">Option have it's default value.<br/>
                <img src="o/<?php echo self::DEFAULT_ICON_ALPHA_COLOR; ?>"
                     style="background:<?php echo self::USEFUL_ICON_COLOR; ?>">Option don't have it's default value and
                it's useful.<br/>
                <img src="o/<?php echo self::DEFAULT_ICON_ALPHA_COLOR; ?>"
                     style="background:<?php echo self::CAREFUL_ICON_COLOR; ?>">Option don't have it's default value but
                it's important. Change the default value may reduce availability or stability.<br/>
                <img src="o/<?php echo self::DEFAULT_ICON_ALPHA_COLOR; ?>"
                     style="background:<?php echo self::CRITICAL_ICON_COLOR; ?>">Option don't have it's default value
                but it's critical for the security. Be sure it's not a error.
            </div>
            <div id="helppart">
                Type of an option can be 'string', 'boolean' or 'integer'. The type is defined by construct and can't be
                changed.
            </div>
            <div id="helppart">
                Status of an option can be :<br/>
                - useful : it's value have to be changed for normal operation.<br/>
                - carful : it's value can be changed for normal operation but be careful this may reduce availability or
                stability.<br/>
                - critical : it's value should not be changed for normal operation, it's critical for the security. Be
                sure it's not a error.
            </div>
            <div id="helppart">
                Protection of an option can be :<br/>
                - writable : the value can be change with a link.<br/>
                - forced : the value have been changed in the local environment file. It can't be change by link.<br/>
                - locked : the option is defined as it's value can't be changed by link. It can be overridden in the
                local environment file.
            </div>
        </div>
        <?php
    }


    private function _displayApplications()
    {
        // Titre
        echo $this->getDisplayTitle('Applications', null, false);
        ?>

        <div id="apps">
            <?php
            // Extraire la liste des applications disponibles.
            $refAppsID = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APPLICATIONS);
            $instanceAppsID = $this->_nebuleInstance->newObject($refAppsID);
            $i = 0;
            $applicationsList = array();
            $signersList = array();

            // Liste les applications reconnues par les maîtres du code.
            $listCodeAuthorities = $this->_nebuleInstance->getCodeAuthorities();
            $linksList = array();
            foreach ($listCodeAuthorities as $instance) {
                $linksList = $instanceAppsID->getLinksOnFields($instance, '', 'f', $refAppsID, '', $refAppsID);
                foreach ($linksList as $link) {
                    $hashTarget = $link->getParsed()['bl/rl/nid2'];
                    $applicationsList[$hashTarget] = $hashTarget;
                    $signersList[$hashTarget] = $link->getParsed()['bs/rs1/eid'];
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK6 target=' . $hashTarget, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
                }
            }

            // Liste les applications reconnues par l'entité instance du serveur, si autorité locale et pas en mode de récupération.
            if ($this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority')
                && !$this->_nebuleInstance->getModeRescue()
            ) {
                $linksList = $instanceAppsID->getLinksOnFields($this->_nebuleInstance->getInstanceEntity(), '', 'f', $refAppsID, '', $refAppsID);
                foreach ($linksList as $link) {
                    $hashTarget = $link->getParsed()['bl/rl/nid2'];
                    $applicationsList[$hashTarget] = $hashTarget;
                    $signersList[$hashTarget] = $link->getParsed()['bs/rs1/eid'];
                }
            }

            // Liste les applications reconnues par l'entité par défaut, si autorité locale et pas en mode de récupération.
            if ($this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')
                && !$this->_nebuleInstance->getModeRescue()
            ) {
                $linksList = $instanceAppsID->getLinksOnFields($this->_nebuleInstance->getDefaultEntity(), '', 'f', $refAppsID, '', $refAppsID);
                foreach ($linksList as $link) {
                    $hashTarget = $link->getParsed()['bl/rl/nid2'];
                    $applicationsList[$hashTarget] = $hashTarget;
                    $signersList[$hashTarget] = $link->getParsed()['bs/rs1/eid'];
                }
            }
            unset($linksList);

            // Lister les applications.
            $application = '';
            foreach ($applicationsList as $application) {
                $this->_nebuleInstance->getMetrologyInstance()->addLog('add application ' . $application, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'de6e59f5');

                $color = '#' . substr($application . '000000', 0, 6);
                //$colorSigner = '#'.substr($signersList[$application].'000000',0,6);
                $instance = new Node($this->_nebuleInstance, $application);
                $title = $instance->getName('authority');
                $shortName = substr($instance->getSurname('authority') . '--', 0, 2);
                $shortName = strtoupper(substr($shortName, 0, 1)) . strtolower(substr($shortName, 1, 1));

                // Recherche si l'application ne doit pas être pré-chargée.
                $noPreloadSigner = '';
                $refNoPreload = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APP_DIRECT);
//                $linksList = $instance->readLinksFilterFull('', '', 'f', $application, $refNoPreload, $application); // FIXME error !!!
                $filter = array(
                    'bl/rl/req' => 'f',
                    'bl/rl/nid1' => $application,
                    'bl/rl/nid2' => $refNoPreload,
                    'bl/rl/nid3' => $application,
                );
                $instance->getLinks($linksList, $filter);
                if (sizeof($linksList) != 0) {
                    $signer = '';
                    $authority = '';
                    foreach ($linksList as $link) {
                        $signer = $link->getParsed()['bs/rs1/eid'];
                        foreach ($this->_nebuleInstance->getLocalAuthorities() as $authority) {
                            if ($signer == $authority) {
                                // Si le lien est valide, active le chargement direct de l'application.
                                $noPreloadSigner = $signer;
                                break 2;
                            }
                        }
                    }
                    unset($signer, $authority);
                }

                // Recherche si l'application est activée par l'entité instance de serveur.
                // Ou si l'application est en liste blanche.
                // Ou si c'est l'application par défaut.
                $activable = true;
                $activated = false;
                foreach (nebule::ACTIVE_APPLICATIONS_WHITELIST as $item) {
                    if ($application == $item) {
                        $activated = true;
                        $activable = false;
                    }
                }
                if ($application == $this->_configurationInstance->getOptionUntyped('defaultApplication'))
                    $activated = true;
                if (!$activated) {
                    $refActivated = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APP_ACTIVE);
//                    $linksList = $instance->readLinksFilterFull($this->_nebuleInstance->getInstanceEntity(), '', 'f', $application, $refActivated, $application);
                    $filter = array(
                        'bs/rs1/eid' => $this->_nebuleInstance->getInstanceEntity(),
                        'bl/rl/req' => 'f',
                        'bl/rl/nid1' => $application,
                        'bl/rl/nid2' => $refActivated,
                        'bl/rl/nid3' => $application,
                    );
                    $instance->getLinks($linksList, $filter);
                    if (sizeof($linksList) != 0)
                        $activated = true;
                }

                // Recherche de la dernière mise à jour.
                $updater = $signersList[$application];
                $linksResult = array();
                foreach ($listCodeAuthorities as $instance) {
//                    $linksResult = $instance->readLinksFilterFull($this->_nebuleInstance->getCodeMaster(), '', 'f', $application, '', $refAppsID);
                    $filter = array(
                        'bs/rs1/eid' => $instance,
                        'bl/rl/req' => 'f',
                        'bl/rl/nid1' => $application,
                        'bl/rl/nid2' => '',
                        'bl/rl/nid3' => $refAppsID,
                    );
                    $instance->getLinks($linksResult, $filter);
                }
                $linksList = array();
                if ($this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority')
                    && !$this->_nebuleInstance->getModeRescue()
                ) {
                    $linksList = $instance->readLinksFilterFull($this->_nebuleInstance->getInstanceEntity(), '', 'f', $application, '', $refAppsID);
                    foreach ($linksList as $link)
                        $linksResult[] = $link;
                }
                if ($this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')
                    && !$this->_nebuleInstance->getModeRescue()
                ) {
                    $linksList = $instance->readLinksFilterFull($this->_nebuleInstance->getDefaultEntity(), '', 'f', $application, '', $refAppsID);
                    foreach ($linksList as $link)
                        $linksResult[] = $link;
                }
                unset($linksList);
                // Trie les liens par date.
                if (sizeof($linksResult) != 0) {
                    $linkdate = array();
                    foreach ($linksResult as $n => $t)
                        $linkdate[$n] = $t->getDate();
                    array_multisort($linkdate, SORT_STRING, SORT_ASC, $linksResult);
                    unset($linkdate, $n, $t);
                }
                // Prend le dernier.
                if (sizeof($linksResult) > 0) {
                    $link = end($linksResult);
                    $updater = $link->getParsed()['bs/rs1/eid'];
                }
                unset($linksResult);
                ?>

                <div class="appItemList">
                    <a href="/?<?php echo nebule::COMMAND_SWITCH_APPLICATION . '=' . $application; ?>">
                        <div class="appLink" style="background:<?php echo $color; ?>;">
                            <span class="appShortname"><?php echo $shortName; ?></span><br/><span
                                    class="appTitle"><?php echo $title; ?></span>
                        </div>
                    </a>
                    <div class="appInfos">
                        <div class="appActions">
                            <?php
                            if ($activated)
                                echo $this->convertInlineIconFace(Displays::DEFAULT_ICON_IOK) . 'Enabled';
                            else
                                echo $this->convertInlineIconFace(Displays::DEFAULT_ICON_IERR) . 'Disabled';

                            echo "<br />\n";

                            if ($activable
                                && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink'))
                                && $this->_nebuleInstance->getCurrentEntityUnlocked()
                                && $this->_nebuleInstance->getCurrentEntity() == $this->_nebuleInstance->getInstanceEntity()
                            ) {
                                if ($activated) {
                                    $this->displayHypertextLink(
                                        $this->convertInlineIconFace(Displays::DEFAULT_ICON_LX) . 'Disable',
                                        '/?' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $application . '_' . $refActivated . '_' . $application
                                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                                    );
                                } else {
                                    $this->displayHypertextLink(
                                        $this->convertInlineIconFace(Displays::DEFAULT_ICON_LL) . 'Enable',
                                        '/?' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_' . $application . '_' . $refActivated . '_' . $application
                                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                                    );
                                }
                                echo "<br />\n";
                            }

                            if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteObject',
                                        'permitWriteLink', 'permitSynchronizeObject', 'permitSynchronizeLink',
                                        'permitSynchronizeApplication', 'permitPublicSynchronizeApplication'))
                                    || $this->_nebuleInstance->getCurrentEntityUnlocked()
                            ) {
                                $this->displayHypertextLink(
                                    $this->convertInlineIconFace(Displays::DEFAULT_ICON_SYNOBJ) . 'Synchronize',
                                    '/?' . Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION . '=' . $application
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                                );
                            }
                            ?>

                        </div>
                        <div class="appName"><?php echo $instance->getName('authority'); ?></div>
                        <div class="appRef"><?php echo $instance->getID(); ?></div>
                        <div class="appSigner">Declared by <?php $this->displayInlineObjectColorIconName(
                                $signersList[$application],
                                '?' . nebule::COMMAND_SWITCH_APPLICATION . '=' . self::REFERENCE_DISPLAY_APPLICATION
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . nebule::COMMAND_SELECT_ENTITY
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=default'
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $signersList[$application]); ?>
                            <?php
                            if ($updater != $signersList[$application]) {
                                ?>
                                and updated by <?php $this->displayInlineObjectColorIconName(
                                    $updater,
                                    '?' . nebule::COMMAND_SWITCH_APPLICATION . '=' . self::REFERENCE_DISPLAY_APPLICATION
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . nebule::COMMAND_SELECT_ENTITY
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=default'
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $updater); ?>
                                <?php
                            }
                            ?>

                        </div>
                        <?php
                        if ($noPreloadSigner != '') {
                            ?>

                            <div class="appNoPreload">No preload by <?php $this->displayInlineObjectColorIconName(
                                    $noPreloadSigner,
                                    '?' . nebule::COMMAND_SWITCH_APPLICATION . '=' . self::REFERENCE_DISPLAY_APPLICATION
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . nebule::COMMAND_SELECT_ENTITY
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=default'
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $noPreloadSigner); ?>.
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
                <?php
            }
            if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteObject',
                        'permitWriteLink', 'permitSynchronizeObject', 'permitSynchronizeLink',
                        'permitSynchronizeApplication', 'permitPublicSynchronizeApplication'))
                    || $this->_nebuleInstance->getCurrentEntityUnlocked()
            ) {
                ?>

                <div id="syncallapps">
                    <?php
                    $this->displayHypertextLink(
                        $this->convertInlineIconFace(Displays::DEFAULT_ICON_SYNOBJ) . 'Synchronize all applications',
                        '/?' . Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_APPLICATION . '=0'
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                    );
                    ?>

                </div>
                <?php
            }
            unset($application, $applicationsList, $instance, $updater, $color, $title, $shortName, $noPreloadSigner);
            ?>

        </div>

        <div id="help">
            Many differents applications can be used. The application to use can be selected from the list on <a
                    href="?a=0">application 0</a> or here on the list.<br/>
            At any time you can switch to another application and come back later to the first one without losing work
            in progress or authentication.<br/>
            <br/>
            An application can be <?php echo $this->convertInlineIconFace(Displays::DEFAULT_ICON_IOK) . 'enabled'; ?>
            or <?php echo $this->convertInlineIconFace(Displays::DEFAULT_ICON_IERR) . 'disabled'; ?>.<br/>
            To be usable, each application have to be enabled on the list here. Without activation, the bootstrap refuse
            to load an application.<br/>
            To be enabled, an application have to be activated with a link generated by a local authority.<br/>
            By default, the application 'option' is automaticaly enabled and can't be disabled.<br/>
            <br/>
            All applications can
            be <?php echo $this->convertInlineIconFace(Displays::DEFAULT_ICON_SYNOBJ) . 'synchronized'; ?> to get last
            updates.<br/>
            Synchronization must be enabled with the option <i>permitSynchronizeApplication</i> and if needed with the
            option <i>permitPublicSynchronizeApplication</i>.<br/>
            <br/>
            Each application is declared by the <a href="http://code.master.nebule.org">code master</a> or by a local
            authority and can be updated by a local authority.<br/>
            On first loading of an application, a preload permit to enhance the user experience. The preload can be
            disabled for an application by the code master or by a local authority.
        </div>
        <?php
    }


    /**
     * Affichage des entités de recouvrement.
     *
     * @return void
     */
    private function _displayRecovery()
    {
        // Titre
        echo $this->getDisplayTitle('Local recovery', null, false);

        $listAuthorities = $this->_nebuleInstance->getAuthorities();
        $listOkEntities = array();
        foreach ($listAuthorities as $eid)
            $listOkEntities[$eid] = true;

        // Liste les entités marquées comme entités de recouvrement.
        $listEntities = $this->_nebuleInstance->getRecoveryEntitiesInstance();
        $listSigners = $this->_nebuleInstance->getRecoveryEntitiesSigners();

        // Affiche les entités de recouvrement.
        if ($this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities')) {
            $refRecovery = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT);
            $list = array();
            $i = 0;

            foreach ($listEntities as $instance) {
                $id = $instance->getID();
                if (!isset($listOkEntities[$id])
                    && $instance->getType('all') == Entity::ENTITY_TYPE
                    && $instance->getIsPublicKey()
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => true,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => false,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'enableDisplayLink2Object' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                        'status' => '',
                        'flagProtection' => false,
                    );

                    if ($id == $this->_nebuleInstance->getInstanceEntity()
                        && $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsRecovery')
                    ) {
                        $list[$i]['param']['flagMessage'] = 'Instance entity';
                        $list[$i]['param']['flagProtection'] = true;
                    }
                    if ($id == $this->_nebuleInstance->getDefaultEntity()
                        && $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsRecovery')
                    ) {
                        if ($list[$i]['param']['flagMessage'] != '') {
                            $list[$i]['param']['flagMessage'] .= ', ';
                        }
                        $list[$i]['param']['flagMessage'] .= 'Default entity';
                        $list[$i]['param']['flagProtection'] = true;
                    }

                    if ($this->_unlocked
                        && $listSigners[$id] == $this->_nebuleInstance->getCurrentEntity()
                        && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitUploadLink'))
                        && ($id != $this->_nebuleInstance->getInstanceEntity()
                            || !$this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsRecovery')
                        )
                        && ($id != $this->_nebuleInstance->getDefaultEntity()
                            || !$this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsRecovery')
                        )
                    ) {
                        $list[$i]['param']['selfHookList'][0]['name'] = 'Remove';
                        $list[$i]['param']['selfHookList'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                        $list[$i]['param']['selfHookList'][0]['link'] = '/?'
                            . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_nebuleInstance->getInstanceEntity() . '_' . $id . '_' . $refRecovery
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    }

                    // Marque comme vu.
                    $listOkEntities[$id] = true;
                    $i++;
                }
            }
            unset($instance, $id);

            if ($this->_unlocked) {
                // Ajoute le message.
                $list[$i]['information'] = 'On recovery removing, need a second page reload to update the liste.';
                $list[$i]['param']['enableDisplayIcon'] = true;
                $list[$i]['param']['informationType'] = 'information';
            }

            // Affichage
            echo $this->getDisplayObjectsList($list, 'medium');
            unset($list);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'warn',
            );
            echo $this->getDisplayInformation('Not authorized!', $param);
        }

        // Affiche les entités à ajouter.
        if ($this->_unlocked
            && (
                ($this->_nebuleInstance->getCurrentEntity() == $this->_nebuleInstance->getInstanceEntity()
                    && $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority')
                )
                ||
                ($this->_nebuleInstance->getCurrentEntity() == $this->_nebuleInstance->getDefaultEntity()
                    && $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')
                )
            )
            && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitUploadLink', 'permitRecoveryEntities'))
        ) {
            // Titre
            echo $this->getDisplayTitle('Add entities as recovery', null, false);

            // Lister les entités.
            $listEntities = $this->_nebuleInstance->getListEntitiesInstances();

            // Affiche les entités.
            $refRecovery = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT);
            $list = array();
            $i = 0;

            foreach ($listEntities as $instance) {
                $id = $instance->getID();
                if (!isset($listOkEntities[$id])
                    && $instance->getType('all') == Entity::ENTITY_TYPE
                    && $instance->getIsPublicKey()
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => false,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'enableDisplayLink2Object' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                    );

                    if ($this->_unlocked
                        && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitUploadLink'))
                    ) {
                        $list[$i]['param']['selfHookList'][0]['name'] = 'Add';
                        $list[$i]['param']['selfHookList'][0]['icon'] = Displays::DEFAULT_ICON_LL;
                        $list[$i]['param']['selfHookList'][0]['link'] = '/?'
                            . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_' . $this->_nebuleInstance->getInstanceEntity() . '_' . $id . '_' . $refRecovery
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    }

                    // Marque comme vu.
                    $listOkEntities[$id] = true;
                    $i++;
                }
            }
            unset($instance, $id);

            // Ajoute le message.
            $list[$i]['information'] = 'On new recovery add, need a second page reload to update the liste.';
            $list[$i]['param']['enableDisplayIcon'] = true;
            $list[$i]['param']['informationType'] = 'information';

            // Affichage
            echo $this->getDisplayObjectsList($list, 'medium');
            unset($list);
        }

        $param = array(
            'enableDisplayAlone' => true,
            'enableDisplayIcon' => true,
            'informationType' => 'information',
            'displayRatio' => 'long',
        );
        $message = "The recovery entities can, if needed, unprotect an object.";
        $message .= " This can be a security choise in an enterprise or legal contraint in some contries.";
        echo $this->getDisplayInformation($message, $param);
        $param['informationType'] = 'warn';
        $message = "Whatever the entity that protect an object, the protection is automatically and silently shared with recovery entities.";
        $message .= " All recovery entities are displayed here, none are hidden.";
        echo $this->getDisplayInformation($message, $param);
        $param['informationType'] = 'information';
        $message = "An entity can be added as recovery entity and later can be removed.";
        $message .= " Entities marqued as instance entity or default entity can't be removed, they are forced by two options on the environment file : 'permitInstanceEntityAsRecovery' and 'permitDefaultEntityAsRecovery'.";
        echo $this->getDisplayInformation($message, $param);
    }


    private function _displayEnd()
    {
    }


    private function _displayChecks()
    {
        ?>

        <div id="check">
            <?php
            if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'WARN')
                $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'ERROR')
                $this->displayMessageError($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'WARN')
                $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'ERROR')
                $this->displayMessageError($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'WARN')
                $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'ERROR')
                $this->displayMessageError($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
            if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'ERROR')
                $this->displayMessageError($this->_applicationInstance->getCheckSecurityBootstrapMessage());
            if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'WARN')
                $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityBootstrapMessage());
            if ($this->_applicationInstance->getCheckSecuritySign() == 'WARN')
                $this->displayMessageWarning($this->_applicationInstance->getCheckSecuritySignMessage());
            if ($this->_applicationInstance->getCheckSecuritySign() == 'ERROR')
                $this->displayMessageError($this->_applicationInstance->getCheckSecuritySignMessage());
            if ($this->_applicationInstance->getCheckSecurityURL() == 'WARN')
                $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityURLMessage());
            if (!$this->_configurationInstance->getOptionAsBoolean('permitWrite'))
                $this->displayMessageWarning(':::warn_ServNotPermitWrite');
            if ($this->_nebuleInstance->getFlushCache())
                $this->displayMessageWarning(':::warn_flushSessionAndCache');
            ?>

        </div>
        <?php
    }


    private function _htmlEnd()
    {
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
    // Les commandes.
    const COMMAND_OPTION_NAME = 'name';
    const COMMAND_OPTION_VALUE = 'value';

    /**
     * Traitement des actions génériques.
     */
    public function genericActions()
    {
        $this->_metrology->addLog('Generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1f5dd135');

        // Vérifie que l'entité instance locale du serveur est déverrouillée et que le ticket est valide.
        if ($this->_unlocked
            && $this->_nebuleInstance->getCurrentEntity() == $this->_nebuleInstance->getInstanceEntity()
            && $this->_nebuleInstance->getTicketingInstance()->checkActionTicket()
            && $this->_configuration->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink'))
        ) {
            // Extrait les actions.
            $this->_extractActionChangeOption();
            $this->_extractActionSignLink1();

            // Traite les options.
            if ($this->_actionOptionName != '')
                $this->_actionChangeOption();

            // Traite les liens.
            if ($this->_unlocked
                && $this->_configuration->getOptionAsBoolean('permitUploadLink')
                && $this->_actionSignLinkInstance1 != ''
                && is_a($this->_actionSignLinkInstance1, 'Link') // FIXME la classe
            )
                $this->_actionSignLink($this->_actionSignLinkInstance1);
        }

        $this->_metrology->addLog('Generic actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bb221384');
    }


    /**
     * Traitement des actions spéciales, qui peuvent être réalisées sans entité déverrouillée.
     */
    public function specialActions()
    {
        $this->_metrology->addLog('Special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4e9ebfc1');

        // Vérifie que le ticket est valide.
        if ($this->_nebuleInstance->getTicketingInstance()->checkActionTicket()) {
            $this->_extractActionSynchronizeApplication();

            if ($this->_actionSynchronizeApplicationInstance != '')
                $this->_actionSynchronizeApplication();
        }

        $this->_metrology->addLog('Special actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1bb0ef71');
    }


    /**
     * Nom de l'option à modifier.
     *
     * @var string
     */
    protected $_actionOptionName = '';
    /**
     * Valeur de l'option à modifier.
     *
     * @var string
     */
    protected $_actionOptionValue = '';

    /**
     * Extrait pour action si on doit modifier une option.
     *
     * @return void
     */
    protected function _extractActionChangeOption()
    {
        // Vérifie que l'écriture d'objets soit authorisée.
        if ($this->_configuration->checkBooleanOptions(array('permitWrite', 'permitWriteLink'))
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Extract action change option', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '62a03a08');

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit et nettoye le contenu des variables GET.
            $argName = trim(filter_input(INPUT_GET, self::COMMAND_OPTION_NAME, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $argValue = trim(filter_input(INPUT_GET, self::COMMAND_OPTION_VALUE, FILTER_SANITIZE_STRING));

            // Récupère les noms des options connues et vérifie que l'option demandée en fait partie.
            $listOptions = $this->_configuration->getListOptions();
            $okOption = false;
            foreach ($listOptions as $option) {
                if ($argName == $option) {
                    $okOption = true;
                    break;
                }
            }

            // Enregistre le nom et la valeur.
            if ($argName != ''
                && $argValue != ''
                && $okOption
            ) {
                $this->_actionOptionName = $argName;
                $this->_actionOptionValue = $argValue;
            }
            unset($arg);
        }
    }


    /**
     * Modification de l'option.
     *
     * @return void
     */
    protected function _actionChangeOption()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisés.
        if ($this->_configuration->checkBooleanOptions(array('permitWrite', 'permitWriteLink'))
            && $this->_actionOptionName != ''
            && $this->_unlocked
        ) {
            $this->_metrology->addLog('Action change option ' . $this->_actionOptionName . ' = ' . $this->_actionOptionValue, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ae2be3f7');

            // Vérifie que l'option est du bon type.
            $listOptionsType = $this->_configuration->getListOptionsType();
            $okOption = false;
            $value = null;
            $type = $listOptionsType[$this->_actionOptionName];
            if ($type == 'string') {
                $value = $this->_actionOptionValue;
                $okOption = true;
            } elseif ($type == 'boolean') {
                $value = false;
                if ($this->_actionOptionValue == 'true')
                    $value = true;
                $okOption = true;
            } elseif ($type == 'integer') {
                $value = (int)$this->_actionOptionValue;
                $okOption = true;
            }

            // Change l'option.
            if ($okOption
                && $value !== null
            ) {
                $this->_configuration->setOptionEnvironment($this->_actionOptionName, $value);

                $this->_display->displayInlineAllActions();

                $this->_metrology->addLog('Action change option ok', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0db1fc8f');
            }

            // Affichage des actions.
            $this->_display->displayInlineAllActions();
        }
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
        parent::__construct($applicationInstance);
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

        $this->_table['fr-fr'][':::warn_ServNotPermitWrite'] = "Ce serveur n'autorise pas les modifications.";
        $this->_table['fr-fr'][':::warn_flushSessionAndCache'] = "Toutes les données de connexion ont été effacées.";
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
        $this->_table['en-en'][':::warn_ServNotPermitWrite'] = 'This server do not permit modifications.';
        $this->_table['en-en'][':::warn_flushSessionAndCache'] = 'All datas of this connexion have been flushed.';
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
        $this->_table['es-co'][':::warn_ServNotPermitWrite'] = 'This server do not permit modifications.';
        $this->_table['es-co'][':::warn_flushSessionAndCache'] = 'All datas of this connexion have been flushed';
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

        $this->_table['fr-fr'][':::display:object:flag:protected'] = 'Forced on environment file.';
        $this->_table['en-en'][':::display:object:flag:protected'] = 'Forced on environment file.';
        $this->_table['es-co'][':::display:object:flag:protected'] = 'Forced on environment file.';
        $this->_table['fr-fr'][':::display:object:flag:unprotected'] = 'Not forced.';
        $this->_table['en-en'][':::display:object:flag:unprotected'] = 'Not forced.';
        $this->_table['es-co'][':::display:object:flag:unprotected'] = 'Not forced.';

        $this->_table['fr-fr'][':::display:object:flag:obfuscated'] = 'Cet objet est dissimulé.';
        $this->_table['en-en'][':::display:object:flag:obfuscated'] = 'This object is obfuscated.';
        $this->_table['es-co'][':::display:object:flag:obfuscated'] = 'Este objeto está oculto.';
        $this->_table['fr-fr'][':::display:object:flag:unobfuscated'] = "Cet objet n'est pas dissimulé.";
        $this->_table['en-en'][':::display:object:flag:unobfuscated'] = 'This object is not obfuscated.';
        $this->_table['es-co'][':::display:object:flag:unobfuscated'] = 'Este objeto no está oculto.';
        $this->_table['fr-fr'][':::display:object:flag:locked'] = 'Cet entité est déverrouillée.';
        $this->_table['en-en'][':::display:object:flag:locked'] = 'This entity is unlocked.';
        $this->_table['es-co'][':::display:object:flag:locked'] = 'Esta entidad está desbloqueada.';
        $this->_table['fr-fr'][':::display:object:flag:unlocked'] = 'Cet entité est verrouillée.';
        $this->_table['en-en'][':::display:object:flag:unlocked'] = 'This entity is locked.';
        $this->_table['es-co'][':::display:object:flag:unlocked'] = 'Esta entidad está bloqueada.';

        $this->_table['fr-fr'][':::display:content:OK'] = "Le contenu de l'objet est valide.";
        $this->_table['en-en'][':::display:content:OK'] = 'The object content is valid.';
        $this->_table['es-co'][':::display:content:OK'] = 'The object content is valid.';

        $this->_table['fr-fr']['::EmptyList'] = 'Liste vide.';
        $this->_table['en-en']['::EmptyList'] = 'Empty list.';
        $this->_table['es-co']['::EmptyList'] = 'Empty list.';

    }
}
