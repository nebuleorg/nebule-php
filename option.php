<?php
declare(strict_types=1);
namespace Nebule\Application\Option;
use Nebule\Library\Configuration;
use Nebule\Library\Crypto;use Nebule\Library\DisplayBlankLine;use Nebule\Library\DisplayColor;
use Nebule\Library\DisplayInformation;
use Nebule\Library\DisplayItem;
use Nebule\Library\DisplayItemIconMessage;
use Nebule\Library\DisplayList;
use Nebule\Library\DisplayNotify;
use Nebule\Library\DisplayObject;
use Nebule\Library\DisplayQuery;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Entity;
use Nebule\Library\Metrology;
use Nebule\Library\nebule;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Node;
use Nebule\Library\References;
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
    const APPLICATION_VERSION = '020250220';
    const APPLICATION_LICENCE = 'GNU GPL 2016-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '555555712c23ff20740c50e6f15e275f695fe95728142c3f8ba2afa3b5a89b3cd0879211.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = false;
    const USE_MODULES_TRANSLATE = false;
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
    const DEFAULT_LOGO_APP0 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAAAmJLR0QA/4ePzL8AAAA
JcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQfgDA0VKApKb6LRAAAEwklEQVRo3u2Zb2hbVRTAz0uWpFvSZmYlbdpXaiZz1jVtYxO2NYRMq5S1tJQNHGwy/FL
wSwduUMSgzj/ph4IdpiDC8EuRDZVJXe1mYJ3Emq4l0TRLaofBxrF0GSFGEhtsmmXxQ7HvvvdukpeXp1bs/XYP557ze+/de+455xFwHv7VIQLYBvjPAJAye7u
zb1hH5NUgYFjn7LO3kzLuVglum/C45ozR1CYmAGLxi07r7RzWlK1lwFKtAsjmXAt295WIYAC3jh3SUTM8AuX+rzHnP/yFQAC5t+jz1VQ4KiZUSrlcIgHIZFK
peCKbI9UKOcP428Vt7+CzcRTyp7TUTCytkO55bPsY/o0Ao3q+xrmsLLoJb/R0Gvg/37Tn+akyNmGlePZE8z62PBzxhjwrnthi8n4aoE52oMpQbajXa0kNU7P
T4Fd2fPp7ltcbqBR//9K+x+my9YzD/ZHvWhS/olv9SmuXUSqhS4O/tH+SH6EAgP8k8+knZwdn7q4VfqWNFWPm3g66LBDUXcqnL4Yj+b49Gv0AwpHBz1/zJh4
W++qJh5eXQ8vG2qpKSqbeY1aMB0sCGNWffBadz/ufufRDguvW8yU/9L6gImsoyd46ZdLxoOgnoMd8atz0dE7xOAE9z2HPD/2OEKE3Ht79vJ+Pe4DOqXk/Tn5
Id1yDBThjxKmHI5YJvlHAMhHGXsmoJxGVbpjacMrW6+lHfAHSj6zXcXJTG5WybAIMNYsxqc7k7Pi9ciL9+L3JWczOJ4aaWQCt9WzF9czgTLmXzeDMeoYtpbx
tArhCsThTzeEuFnaKj7trDjdTFou7Qqw4cDO6e62pZtdOVPHsV8FU+RduYvWUke7+otPqZ72BHLx+236Dvv/zxfzSxrUoehZWU/SMkpYPvLd0J0TNvCGhkg7
UUjhKT2hpADlAT4JnRSgA1JKYyBXKiFRKZFlMMIAY3gMGQI4k1otJoQBQS3J5QQAJkkzcTwsFgFqSSLZ2VpxBoladTCgnqKVMpiBACgk8B6qEAkAtpVIFAeJ
I1mOoFgoAtRRPFAAgIIscUkO9YACIpWyOyAdAgK2FVFNzvVYoANQSqba1EDiAjfoeLbBJTbdaCPfdarRgUcgHLCjC5m04rBs4grYXAACqMpeXywd4v2N/Azr
ftbOpZvcf01HGG+htYboH6DI2VpTrvrGii5VrVqtMWtYn+OYOe7FUMmYuF2DMzCzVAAB8KyyAkUAW03nq7TjdUI770w3MMm3jJIwEWADhtGsBZ8J2VMY7XMt
EtqM4uWshnMYcQ7sbp0xqnP18AZz97IKd6QkBuBKZw1YyB3XTPbyaEz0HdfjSDO0g5inPR/Wv9tHLM8tEKQWKTOTsp7u/cPWst4Tq2PHArNhbh3yImpef/DX
qS3Ldel+eePoJeqvm1LdbtEGxlVs0QjSpAAJB3k2qf6ZNl7dHtFnfBpXJw/v5ub9wNd/WKykpxR8fLoPLyu1m9Q5+y378WSKm/7DIZOmhR1CAOT+9f/bmZ+8
usbXeaHrnRfoqLraLngIAgI+XAj/VishaEQEQi3/w9flFnNZMTPrbRosjm/tu4dzkOTcXAIL7r1tSNtTcWu8KWf25vMZsOpPWtzISCHOuK4ntf8f/e4A/AcO
8sjeKv7mpAAAAAElFTkSuQmCC';
    const DEFAULT_LOGO_MENU = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAABAElEQVR42u3XvQmEQBC
G4e8OYxuwEvvYMu1DBDESw2VRtwLB0L30ghMM5Eb0nVTx52Fm9Hs555IeXG89vAAAAAAAAAAAAAAAAAAAAAB4YmVnXMQ5Z/LwVVXZd4DVy591b3YAAAAAAID
1p8jy3tlVHoQRAAAAsgBZgCzADgAAAADIAmQBRgAAAMgCZAGyADsAAAAAIAuQBRgBAAC4fhY4UkVRqCzLQ+eGENS27b06IMaopmm0LMvuOdu2yXuvYRjuOQL
TNKnv+93j4ziq6zqt63rfHRBjVF3Xpm1vvgS/x8Gi7U2W4K9xSCkpz3OFEP7a9pcAkKR5nvkPAAAAAACwrA/2026oVwXU/wAAAABJRU5ErkJggg==';

    // Les vues.
    const VIEW_MENU = 'menu';
    const VIEW_GLOBAL_AUTHORITIES = 'gauth';
    const VIEW_LOCAL_AUTHORITIES = 'lauth';
    const VIEW_OPTIONS = 'opts';
    const VIEW_APPLICATIONS = 'apps';
    const VIEW_RECOVERY = 'recv';

    // Application de référence pour les visualisations d'objets.
    const REFERENCE_DISPLAY_APPLICATION = 'dd11110000000000006e6562756c65206170706c69636174696f6e73000000000000dd1111';
    const REFERENCE_AUTHENTICATION_APPLICATION = '2';

    protected array $_listDisplayModes = array('disp');
    protected array $_listDisplayViews = array(
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
        if (!str_contains(Application::APPLICATION_WEBSITE, '://'))
            $linkApplicationWebsite = 'http' . '://' . Application::APPLICATION_WEBSITE;

        // Récupère l'entité déverrouillée ou l'entité instance du serveur.
        if ($this->_unlocked) {
            $username = $this->_entitiesInstance->getCurrentEntityInstance()->getFullName();
            $userID = $this->_entitiesInstance->getCurrentEntityID();
        } else {
            $username = $this->_entitiesInstance->getServerEntityInstance()->getFullName();
            $userID = $this->_entitiesInstance->getServerEntityID();
        }
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
        </head>
        <body>
        <div class="layout-header header<?php if ($this->_unlocked) {
            echo 'unlock';
            if ($this->_entitiesInstance->getCurrentEntityID() != $this->_entitiesInstance->getServerEntityID()) echo 'other';
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
                &nbsp;
            </div>
        </div>
        <div class="layout-footer footer<?php if ($this->_unlocked) {
            echo 'unlock';
            if ($this->_entitiesInstance->getCurrentEntityID() != $this->_entitiesInstance->getServerEntityID()) echo 'other';
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
            if ($this->_rescueInstance->getModeRescue())
                $this->displayMessageWarning_DEPRECATED('::::RESCUE');
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
                    $this->_displayRecoveryEntities();
                    break;
                default:
                    $this->_displayMenu();
            }
            $this->_displayEnd();
        } else
            $this->_displayChecks();
        $this->_htmlEnd();

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
        <?php
    }


    private function _displayActions(): void
    {
        ?>

        <div class="layoutAloneItem" id="actions">
            <div class="aloneItemContent" id="actionscontent">
                <?php
                $this->_actionInstance->getDisplayActions();
                ?>

            </div>
        </div>
        <?php
    }


    private function _displayMenu(): void
    {
        $list = array();
        $list[0]['icon'] = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_LL);
        $list[0]['title'] = 'Options';
        $list[0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_OPTIONS;
        $list[1]['icon'] = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_LF);
        $list[1]['title'] = 'Global authorities';
        $list[1]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_GLOBAL_AUTHORITIES;
        $list[2]['icon'] = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_LF);
        $list[2]['title'] = 'Local authorities';
        $list[2]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_LOCAL_AUTHORITIES;
        $list[4]['icon'] = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_LS);
        $list[4]['title'] = 'Applications';
        $list[4]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::VIEW_APPLICATIONS;
        $list[5]['icon'] = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_LK);
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
        echo $this->getDisplayInformation_DEPRECATED($message, $param);
    }


    /**
     * Affichage des autorités globales.
     * Ces autorités sont définies par le maître du tout puppetmaster, et par le code.
     *
     * @return void
     */
    private function _displayGlobalAuthorities(): void
    {
        $instanceTitle = new DisplayTitle($this->_applicationInstance);
        $instanceTitle->setTitle('Global authorities');
        $instanceTitle->setIcon(null);
        $instanceTitle->setEnableEntity(true);
        $instanceTitle->display();

        $instanceList = new DisplayList($this->_applicationInstance);
        $instanceEntity = new DisplayObject($this->_applicationInstance);
        $this->_setCommonEntityFlags($instanceEntity, $this->_authoritiesInstance->getPuppetmasterInstance());
        $instanceEntity->setFlagProtection(true);
        $instanceEntity->setEnableRefs(false);
        $instanceList->addItem($instanceEntity);
        foreach (array_merge($this->_authoritiesInstance->getSecurityAuthoritiesInstance(), $this->_authoritiesInstance->getCodeAuthoritiesInstance()) as $instance)
        {
            $instanceEntity = new DisplayObject($this->_applicationInstance);
            $this->_setCommonEntityFlags($instanceEntity, $instance);
            $instanceEntity->setEnableRefs(true);
            $instanceEntity->setRefs(array('0' => $this->_authoritiesInstance->getPuppetmasterInstance(),));
            $instanceList->addItem($instanceEntity);
        }
        $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
        $instanceList->display();

        $instanceMessage = new DisplayInformation($this->_applicationInstance);
        $instanceMessage->setMessage("The global authorities are entities with specials capabilities on common
            things like code. Global authorities can't be removed or disabled.<br/>&nbsp;");
        $instanceMessage->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage->setDisplayAlone(true);
        $instanceMessage->setSize(DisplayItem::SIZE_SMALL);
        $instanceMessage->display();
    }

    private function _setCommonEntityFlags(DisplayObject $instance, Entity $eid):void {
        $instance->setNID($eid);
        $instance->setEnableNID(false);
        $instance->setEnableName(true);
        $instance->setEnableColor(true);
        $instance->setEnableIcon(true);
        $instance->setEnableFlags(true);
        $instance->setEnableFlagProtection(true);
        $instance->setEnableFlagObfuscate(false);
        $instance->setEnableFlagUnlocked(true);
        $instance->setEnableFlagState(true);
        $instance->setEnableFlagEmotions(false);
        $instance->setEnableStatus(true);
        $instance->setEnableContent(false);
        $instance->setEnableActions(true);
        $instance->setEnableJS(false);
        $instance->setEnableLink2Refs(false);
        $instance->setEnableLink(true);
        $instance->setStatus('');
        $instance->setFlagMessage($this->_getIsInstanceOrDefault($eid)); // FIXME me fonctionne pas
        $instance->setRatio(DisplayItem::RATIO_SHORT);
        $instance->setSize(DisplayItem::SIZE_MEDIUM);
    }

    private function _getIsInstanceOrDefault(Entity $eid): string {
        $message = '';
        if ($eid->getID() == $this->_entitiesInstance->getServerEntityID())
            $message = 'Instance entity';
        if ($eid->getID() == $this->_entitiesInstance->getDefaultEntityID()) {
            if ($message != '')
                $message .= ', ';
            $message .= 'Default entity';
        }
        return $message;
    }


    /**
     * Affichage des autorités locales.
     * Ces autorités sont définies par des options pour les autorités locales primaires,
     *   et par des liens pour les autorités locales secondaires.
     *
     * @return void
     */
    private function _displayLocalAuthorities(): void
    {
        $refAuthority = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_ENTITE_AUTORITE_LOCALE, References::REFERENCE_CRYPTO_HASH_ALGORITHM) . '.' . References::REFERENCE_CRYPTO_HASH_ALGORITHM;

        // Primary local authorities
        $instanceTitle = new DisplayTitle($this->_applicationInstance);
        $instanceTitle->setTitle('Primary local authorities');
        $instanceTitle->setIcon(null);
        $instanceTitle->setEnableEntity(true);
        $instanceTitle->display();

        $listOkEntities = array(
            $this->_authoritiesInstance->getPuppetmasterEID() => true,
        );
        foreach (array_merge(
                $this->_authoritiesInstance->getSecurityAuthoritiesEID(),
                $this->_authoritiesInstance->getCodeAuthoritiesEID(),
                $this->_authoritiesInstance->getDirectoryAuthoritiesEID(),
                $this->_authoritiesInstance->getTimeAuthoritiesEID()) as $id)
            $listOkEntities[$id] = true;

        $instanceList = new DisplayList($this->_applicationInstance);
        foreach ($this->_authoritiesInstance->getLocalPrimaryAuthoritiesInstance() as $instance) {
            if (!isset($listOkEntities[$instance->getID()])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $instanceEntity = new DisplayObject($this->_applicationInstance);
                $this->_setCommonEntityFlags($instanceEntity, $instance);
                $instanceEntity->setFlagProtection(true);
                $instanceEntity->setEnableRefs(false);
                $instanceList->addItem($instanceEntity);
            }
            $listOkEntities[$instance->getID()] = true;
        }
        $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
        $instanceList->display();

        // Secondary local authorities
        $instanceTitle->setTitle('Secondary local authorities');
        $instanceTitle->display();

        if ($this->_configurationInstance->getOptionAsBoolean('permitLocalSecondaryAuthorities')) {
            $instanceList = new DisplayList($this->_applicationInstance);
            foreach ($this->_authoritiesInstance->getLocalPrimaryAuthoritiesInstance() as $instance) {
                if (!isset($listOkEntities[$instance->getID()])
                    && $instance->getType('all') == Entity::ENTITY_TYPE
                    && $instance->getIsPublicKey()
                ) {
                    $instanceEntity = new DisplayObject($this->_applicationInstance);
                    $this->_setCommonEntityFlags($instanceEntity, $instance);
                    $instanceEntity->setFlagProtection(true);
                    $instanceEntity->setEnableRefs(true);
                    $instanceEntity->setRefs($this->_authoritiesInstance->getLocalAuthoritiesSigners()[$instance->getID()]);
                    if ($this->_permitAction($instance)
                        && $this->_authoritiesInstance->getLocalAuthoritiesSigners()[$instance->getID()] == $this->_entitiesInstance->getCurrentEntityID()
                    ) {
                        $list[0]['name'] = 'Remove';
                        $list[0]['icon'] = Displays::DEFAULT_ICON_LX;
                        $list[0]['link'] = '/?'
                            . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_entitiesInstance->getServerEntityID() . '_' . $instance->getID() . '_' . $refAuthority
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        $instanceEntity->setSelfHookList($list);
                    }
                    $instanceList->addItem($instanceEntity);
                }
                $listOkEntities[$instance->getID()] = true;
            }
            if ($this->_unlocked) {
                $instanceWarn = new DisplayInformation($this->_applicationInstance);
                $instanceWarn->setType(DisplayItemIconMessage::TYPE_MESSAGE);
                $instanceWarn->setMessage('On authority removing, need a second page reload to update the liste.');
                $instanceWarn->setRatio(DisplayItem::RATIO_SHORT);
                $instanceList->addItem($instanceWarn);
            }
            $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
            $instanceList->setEnableWarnIfEmpty(true);
            $instanceList->display();
        } else {
            $instanceWarn = new DisplayInformation($this->_applicationInstance);
            $instanceWarn->setType(DisplayItemIconMessage::TYPE_WARN);
            $instanceWarn->setMessage('::::err_NotPermit');
            $instanceWarn->setRatio(DisplayItem::RATIO_SHORT);
            $instanceWarn->display();
        }

        // Affiche les entités à ajouter.
        if ($this->_permitAction($this->_entitiesInstance->getCurrentEntityInstance())
            && $this->_configurationInstance->getOptionAsBoolean('permitLocalSecondaryAuthorities')
        ) {
            $icon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_ADD);
            $instance = new DisplayTitle($this->_applicationInstance);
            $instance->setTitle('Add entities as local authority');
            $instance->setIcon($icon);
            $instance->display();

            // Lister les entités.
            $listEntities = $this->_entitiesInstance->getListEntitiesInstances();
            $listAuthorities = $this->_authoritiesInstance->getAuthoritiesID();

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
                            . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_' . $this->_entitiesInstance->getServerEntityID() . '_' . $id . '_' . $refAuthority
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
            echo $this->getDisplayObjectsList_DEPRECATED($list, 'medium');
            unset($list, $listEntities, $listSigners);
        }

        $instanceList = new DisplayList($this->_applicationInstance);
        $instanceMessage1 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage1->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage1->setMessage("The local authorities are entities with specials capabilities on local
            server.<br/>&nbsp;");
        $instanceMessage1->setRatio(DisplayItem::SIZE_SMALL);
        $instanceMessage1->setIconText('Type');
        $instanceList->addItem($instanceMessage1);
        $instanceMessage2 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage2->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage2->setMessage("There are two levels of local authorities:<br />
            1: Local forced authorities defined by options, known as primary local authorities.<br />
            2: Local authorities defined by links from primary local authorities, known as secondary local authorities.
            <br/>&nbsp;");
        $instanceMessage2->setRatio(DisplayItem::SIZE_SMALL);
        $instanceMessage2->setIconText('Type');
        $instanceList->addItem($instanceMessage2);
        $instanceMessage3 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage3->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage3->setMessage("An entity can be added as secondary authority and later can be removed.
            <br />
            Primary authorities can't be removed, they are forced by two options on the environment file :
            'permitInstanceEntityAsAuthority' and 'permitDefaultEntityAsAuthority'.<br/>&nbsp;");
        $instanceMessage3->setRatio(DisplayItem::SIZE_SMALL);
        $instanceMessage3->setIconText('Type');
        $instanceList->addItem($instanceMessage3);
        $instanceList->setSize(DisplayItem::SIZE_SMALL);
        $instanceList->display();
    }

    private function _permitAction(Entity $eid): bool {
        if ($this->_unlocked
            && (
                ($eid->getID() == $this->_entitiesInstance->getServerEntityID()
                    && $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority')
                )
                ||
                ($eid->getID() == $this->_entitiesInstance->getDefaultEntityID()
                    && $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')
                )
            )
            && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitUploadLink'))
        )
            return true;
        return false;
    }


    private function _displayOptions(): void
    {
        $instanceTitle = new DisplayTitle($this->_applicationInstance);
        $instanceTitle->setTitle('Options');
        $instanceTitle->setIcon(null);
        $instanceTitle->setEnableEntity(true);
        $instanceTitle->display();
        ?>

        <div id="options">
            <?php
            foreach (Configuration::OPTIONS_CATEGORIES as $optionCategory) {
                $instanceTitle->setTitle($optionCategory);
                $icon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_LL);
                $instanceTitle->setIcon($icon);
                $instanceTitle->setEnableEntity(false);
                $instanceTitle->display();

                foreach (Configuration::OPTIONS_LIST as $optionName) {
                    if (Configuration::OPTIONS_CATEGORY[$optionName] != $optionCategory)
                        continue;

                    $instanceList = new DisplayList($this->_applicationInstance);

                    $instanceID = $this->_nebuleInstance->getCacheInstance()->newNode('0');
                    $instanceID->setContent($optionName);
                    $instanceOption = new DisplayObject($this->_applicationInstance);
                    $instanceOption->setNID($instanceID);
                    $instanceOption->setEnableNID(false);
                    $instanceOption->setEnableName(true);
                    $instanceOption->setName($optionName);
                    $instanceOption->setEnableColor(true);
                    $instanceOption->setEnableIcon(false);
                    $instanceOption->setEnableLink(false);
                    $instanceOption->setEnableJS(false);
                    $instanceOption->setRatio(DisplayItem::RATIO_LONG);
                    $instanceList->addItem($instanceOption);

                    $instanceState = new DisplayInformation($this->_applicationInstance);
                    if ($this->_configurationInstance->getOptionAsString($optionName) == Configuration::OPTIONS_DEFAULT_VALUE[$optionName]) {
                        $instanceState->setType(DisplayItemIconMessage::TYPE_OK);
                        $instanceState->setMessage(Configuration::OPTIONS_CRITICALITY[$optionName] . ', by default');
                    } elseif (Configuration::OPTIONS_CRITICALITY[$optionName] == 'critical') {
                        $instanceState->setType(DisplayItemIconMessage::TYPE_ERROR);
                        $instanceState->setMessage(Configuration::OPTIONS_CRITICALITY[$optionName] . ', changed');
                    } elseif (Configuration::OPTIONS_CRITICALITY[$optionName] == 'careful') {
                        $instanceState->setType(DisplayItemIconMessage::TYPE_WARN);
                        $instanceState->setMessage(Configuration::OPTIONS_CRITICALITY[$optionName] . ', changed');
                    } else {
                        $instanceState->setType(DisplayItemIconMessage::TYPE_PLAY);
                        $instanceState->setMessage(Configuration::OPTIONS_CRITICALITY[$optionName] . ', changed');
                    }
                    $instanceState->setRatio(DisplayItem::RATIO_SHORT);
                    $instanceState->setIconText('Status');
                    $instanceList->addItem($instanceState);

                    $instanceList->addItem($this->_addInfoShort(
                        DisplayItemIconMessage::TYPE_MESSAGE,
                        $optionCategory,
                        DisplayItem::RATIO_SHORT,
                        'Category'));

                    $instanceList->addItem($this->_addInfoShort(
                        DisplayItemIconMessage::TYPE_MESSAGE,
                        Configuration::OPTIONS_TYPE[$optionName],
                        DisplayItem::RATIO_SHORT,
                        'Type'));

                    if (!Configuration::OPTIONS_WRITABLE[$optionName]) {
                        $instanceQuery = new DisplayInformation($this->_applicationInstance);
                        $instanceQuery->setType(DisplayItemIconMessage::TYPE_WARN);
                        $instanceQuery->setMessage('Not writeable');
                    } elseif (Configuration::getOptionFromEnvironmentAsStringStatic($optionName) != '') { // FIXME ne fonctionne pas
                        $instanceQuery = new DisplayInformation($this->_applicationInstance);
                        $instanceQuery->setType(DisplayItemIconMessage::TYPE_WARN);
                        $instanceQuery->setMessage('Forced on config file');
                    } elseif ($this->_unlocked
                        && $this->_entitiesInstance->getCurrentEntityID() == $this->_entitiesInstance->getServerEntityID() // FIXME doit être dans la liste des entités autorisées
                    ) {
                        $instanceQuery = new DisplayQuery($this->_applicationInstance);
                        $instanceQuery->setType(DisplayQuery::QUERY_STRING);
                        $instanceQuery->setMessage('Change value');
                    } else {
                        $instanceQuery = new DisplayInformation($this->_applicationInstance);
                        $instanceQuery->setType(DisplayItemIconMessage::TYPE_BACK);
                        $instanceQuery->setLink('?'
                            . References::COMMAND_SWITCH_APPLICATION . '=' . self::REFERENCE_AUTHENTICATION_APPLICATION
                            . '&' . References::COMMAND_APPLICATION_BACK . '=' . Application::APPLICATION_NODE);
                        $instanceQuery->setMessage('Not connected');
                    }
                    $instanceQuery->setRatio(DisplayItem::RATIO_SHORT);
                    $instanceQuery->setIconText('Change value');
                    $instanceList->addItem($instanceQuery);

                    if ($this->_configurationInstance->getOptionAsString($optionName) == Configuration::OPTIONS_DEFAULT_VALUE[$optionName])
                        $instanceList->addItem($this->_addInfoShort(
                            DisplayItemIconMessage::TYPE_OK,
                            'Is default value',
                            DisplayItem::RATIO_SHORT,
                            'Curent value'));
                    else
                        $instanceList->addItem($this->_addInfoShort(
                            DisplayItemIconMessage::TYPE_INFORMATION,
                            '"' . $this->_configurationInstance->getOptionAsString($optionName) . '"',
                            DisplayItem::RATIO_LONG,
                            'Curent value'));

                    $instanceList->addItem($this->_addInfoShort(
                        DisplayItemIconMessage::TYPE_MESSAGE,
                        '"' . Configuration::OPTIONS_DEFAULT_VALUE[$optionName] . '"',
                        DisplayItem::RATIO_LONG,
                        'Default value'));

                    $instanceList->addItem($this->_addInfoShort(
                        DisplayItemIconMessage::TYPE_MESSAGE,
                        Configuration::OPTIONS_DESCRIPTION[$optionName],
                        DisplayItem::RATIO_LONG,
                        'Description'));

                    $instanceList->setSize(DisplayItem::SIZE_SMALL);
                    $instanceList->display();

                    echo "<br /><br />\n";

                    unset ($instanceList, $instanceOption, $instanceID, $instanceState, $instanceCategory, $instanceType, $instanceQuery, $instanceValue, $instanceDefault, $instanceDesc);
                }
            }

            $instanceList = new DisplayList($this->_applicationInstance);

            $instanceMessage1 = new DisplayInformation($this->_applicationInstance);
            $instanceMessage1->setMessage("The type of option can be 'string', 'boolean' or 'integer'. The type
                is defined by construct and can't be changed.<br/>&nbsp;");
            $instanceMessage1->setType(DisplayItemIconMessage::TYPE_MESSAGE);
            $instanceMessage1->setRatio(DisplayItem::RATIO_LONG);
            $instanceList->addItem($instanceMessage1);

            $instanceMessage2 = new DisplayInformation($this->_applicationInstance);
            $instanceMessage2->setMessage("Status of an option can be :<br/>
                - useful : it's value have to be changed for normal operation.<br/>
                - careful : it's value can be changed for normal operation but be careful this may reduce availability
                or stability.<br/>
                - critical : it's value should not be changed for normal operation, it's critical for the security. Be
                sure it's not an error.<br/>&nbsp;");
            $instanceMessage2->setType(DisplayItemIconMessage::TYPE_MESSAGE);
            $instanceMessage2->setRatio(DisplayItem::RATIO_LONG);
            $instanceList->addItem($instanceMessage2);

            $instanceMessage3 = new DisplayInformation($this->_applicationInstance);
            $instanceMessage3->setMessage("Protection of an option can be :<br/>
                - writable : the value can be changed with a link.<br/>
                - forced : the value have been changed in the local environment file. It can't be changed by link.<br/>
                - locked : the option is defined as it's value can't be changed by link. It can be overridden in the
                local environment file.<br/>&nbsp;");
            $instanceMessage3->setType(DisplayItemIconMessage::TYPE_MESSAGE);
            $instanceMessage3->setRatio(DisplayItem::RATIO_LONG);
            $instanceList->addItem($instanceMessage3);

            $instanceList->setSize(DisplayItem::SIZE_SMALL);
            $instanceList->display();
    }

    private function _addInfoShort(string $type, string $message, string $ratio, string $iconText):DisplayInformation {
                    $instanceType = new DisplayInformation($this->_applicationInstance);
                    $instanceType->setType($type);
                    $instanceType->setMessage($message);
                    $instanceType->setRatio($ratio);
                    $instanceType->setIconText($iconText);
                    return $instanceType;
    }


    private function _getInfoAppNoPreload(string $application):DisplayInformation {
        $instance = new Node($this->_nebuleInstance, $application);
        $linksList = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $application,
            'bl/rl/nid2' => $this->_referenceNoPreload, // FIXME
            'bl/rl/nid3' => $application,
        );
        $instance->getLinks($linksList, $filter);
        foreach ($linksList as $link) {
            $signer = $link->getParsed()['bs/rs1/eid'];
            if (in_array($signer, $this->_authoritiesInstance->getLocalAuthoritiesID()))
                return $this->_addInfoShort(DisplayItemIconMessage::TYPE_PLAY, ':::NoPreload', DisplayItem::RATIO_SHORT, 'Preload');
        }
        return $this->_addInfoShort(DisplayItemIconMessage::TYPE_INFORMATION, ':::OkPreload', DisplayItem::RATIO_SHORT, 'Preload');
    }

    private function _getInfoAppActivated(string $application):DisplayInformation {
        $instance = new Node($this->_nebuleInstance, $application);
        $linksList = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $application,
            'bl/rl/nid2' => $this->_referenceActivated, // FIXME
            'bl/rl/nid3' => $application,
        );
        $instance->getLinks($linksList, $filter);
        foreach ($linksList as $link) {
            $signer = $link->getParsed()['bs/rs1/eid'];
            if (in_array($signer, $this->_authoritiesInstance->getLocalAuthoritiesID()))
                return $this->_addInfoShort(DisplayItemIconMessage::TYPE_PLAY, ':::NotActivated', DisplayItem::RATIO_SHORT, 'Activation');
        }
        return $this->_addInfoShort(DisplayItemIconMessage::TYPE_INFORMATION, ':::Activated', DisplayItem::RATIO_SHORT, 'Activation');
    }

    private function _getInfoAppRun(string $application):DisplayInformation {
        $instance = new Node($this->_nebuleInstance, $application);
        $linksList = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $application,
            'bl/rl/nid2' => $this->_referenceActivated, // FIXME
            'bl/rl/nid3' => $application,
        );
        $instance->getLinks($linksList, $filter);
        foreach ($linksList as $link) {
            $signer = $link->getParsed()['bs/rs1/eid'];
            if (in_array($signer, $this->_authoritiesInstance->getLocalAuthoritiesID()))
                return $this->_addInfoShort(DisplayItemIconMessage::TYPE_PLAY, ':::NotActivated', DisplayItem::RATIO_SHORT, 'Activation');
        }
        return $this->_addInfoShort(DisplayItemIconMessage::TYPE_INFORMATION, ':::Activated', DisplayItem::RATIO_SHORT, 'Activation');
    }


    
    private string $_referenceAppID = \Nebule\Bootstrap\LIB_RID_INTERFACE_APPLICATIONS;
    private string $_referenceNoPreload = '';
    private string $_referenceActivated = '';

    private function _displayApplications(): void
    {
        $referencePHP = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_OBJECT_APP_PHP, References::REFERENCE_CRYPTO_HASH_ALGORITHM) . '.' . References::REFERENCE_CRYPTO_HASH_ALGORITHM;
        $this->_referenceNoPreload = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_DIRECT, References::REFERENCE_CRYPTO_HASH_ALGORITHM) . '.' . References::REFERENCE_CRYPTO_HASH_ALGORITHM;
        $this->_referenceActivated = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_ACTIVE, References::REFERENCE_CRYPTO_HASH_ALGORITHM) . '.' . References::REFERENCE_CRYPTO_HASH_ALGORITHM;
        
        $instanceTitle = new DisplayTitle($this->_applicationInstance);
        $instanceTitle->setTitle('Applications');
        $instanceTitle->setIcon(null);
        $instanceTitle->setEnableEntity(true);
        $instanceTitle->display();

        echo '<div id="apps">' . "\n";

        $instanceAppsID = $this->_cacheInstance->newNode($this->_referenceAppID);
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK2 refAppsID=' . $this->_referenceAppID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK3 refPHP=' . $referencePHP, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK4 nid=' . $instanceAppsID->getID(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');

        $instanceList = new DisplayList($this->_applicationInstance);

        $appListed = array();
        $linksList = array();
        foreach ($this->_authoritiesInstance->getCodeAuthoritiesEID() as $eid) {
            $linksList = $instanceAppsID->getLinksOnFields($eid, '', 'f', $this->_referenceAppID, '', $referencePHP);
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK5 size=' . sizeof($linksList), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
            foreach ($linksList as $link) {
                $hashTarget = $link->getParsed()['bl/rl/nid2'];
                if (isset($appListed[$hashTarget]))
                    continue;
                $appListed[$hashTarget] = true;
                $hashTargetInstance = new Node($this->_nebuleInstance, $hashTarget);
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK6 target=' . $hashTarget, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');

                $instanceApplication = new DisplayObject($this->_applicationInstance);
                $instanceApplication->setNID($hashTargetInstance);
                $instanceApplication->setEnableNID(false);
                $instanceApplication->setEnableName(true);
                //$instanceApplication->setName($optionName);
                $instanceApplication->setEnableColor(true);
                $instanceApplication->setEnableIcon(false);
                $instanceApplication->setEnableLink(false);
                $instanceApplication->setEnableJS(false);
                $instanceApplication->setRatio(DisplayItem::RATIO_LONG);
                $instanceList->addItem($instanceApplication);

                $instanceList->addItem($this->_addInfoShort(
                    DisplayItemIconMessage::TYPE_MESSAGE,
                    $eid,
                    DisplayItem::RATIO_SHORT,
                    'Signer EID'));
                $instanceList->addItem($this->_getInfoAppNoPreload($hashTarget));
                $instanceList->addItem($this->_getInfoAppActivated($hashTarget));

                $instanceOpen = new DisplayInformation($this->_applicationInstance);
                $instanceOpen->setType(DisplayItemIconMessage::TYPE_PLAY);
                $instanceOpen->setLink('?' . References::COMMAND_SWITCH_APPLICATION . '=' . $hashTarget);
                $instanceOpen->setMessage(':::Open');
                $instanceList->addItem($instanceOpen);

                $instanceLB = new DisplayBlankLine($this->_applicationInstance);
                $instanceList->addItem($instanceLB);
            }
        }

        // Liste les applications reconnues par l'entité instance du serveur, si autorité locale et pas en mode de récupération.
        if ($this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority')
            && !$this->_rescueInstance->getModeRescue()
        ) {
            $linksList = $instanceAppsID->getLinksOnFields($this->_entitiesInstance->getServerEntityID(), '', 'f', $this->_referenceAppID, '', $referencePHP);
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK7 size=' . sizeof($linksList), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
            foreach ($linksList as $link) {
                $hashTarget = $link->getParsed()['bl/rl/nid2'];
                if (isset($appListed[$hashTarget]))
                    continue;
                $appListed[$hashTarget] = true;
                $hashTargetInstance = new Node($this->_nebuleInstance, $hashTarget);
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK8 target=' . $hashTarget, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');

                $instanceApplication = new DisplayObject($this->_applicationInstance);
                $instanceApplication->setNID($hashTargetInstance);
                $instanceApplication->setEnableNID(false);
                $instanceApplication->setEnableName(true);
                //$instanceApplication->setName($optionName);
                $instanceApplication->setEnableColor(true);
                $instanceApplication->setEnableIcon(false);
                $instanceApplication->setEnableLink(false);
                $instanceApplication->setEnableJS(false);
                $instanceApplication->setRatio(DisplayItem::RATIO_LONG);
                $instanceList->addItem($instanceApplication);

                $instanceList->addItem($this->_addInfoShort(
                    DisplayItemIconMessage::TYPE_MESSAGE,
                    $this->_entitiesInstance->getServerEntityID(),
                    DisplayItem::RATIO_SHORT,
                    'Signer EID'));
                $instanceList->addItem($this->_getInfoAppNoPreload($hashTarget));
                $instanceList->addItem($this->_getInfoAppActivated($hashTarget));

                $instanceLB = new DisplayBlankLine($this->_applicationInstance);
                $instanceList->addItem($instanceLB);
            }
        }

        // Liste les applications reconnues par l'entité par défaut, si autorité locale et pas en mode de récupération.
        if ($this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')
            && !$this->_rescueInstance->getModeRescue()
        ) {
            $linksList = $instanceAppsID->getLinksOnFields($this->_entitiesInstance->getDefaultEntityID(), '', 'f', $this->_referenceAppID, '', $referencePHP);
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK9 size=' . sizeof($linksList), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
            foreach ($linksList as $link) {
                $hashTarget = $link->getParsed()['bl/rl/nid2'];
                if (isset($appListed[$hashTarget]))
                    continue;
                $appListed[$hashTarget] = true;
                $hashTargetInstance = new Node($this->_nebuleInstance, $hashTarget);
$this->_nebuleInstance->getMetrologyInstance()->addLog('MARK10 target=' . $hashTarget, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');

                $instanceApplication = new DisplayObject($this->_applicationInstance);
                $instanceApplication->setNID($hashTargetInstance);
                $instanceApplication->setEnableNID(false);
                $instanceApplication->setEnableName(true);
                //$instanceApplication->setName($optionName);
                $instanceApplication->setEnableColor(true);
                $instanceApplication->setEnableIcon(false);
                $instanceApplication->setEnableLink(false);
                $instanceApplication->setEnableJS(false);
                $instanceApplication->setRatio(DisplayItem::RATIO_LONG);
                $instanceList->addItem($instanceApplication);

                $instanceList->addItem($this->_addInfoShort(
                    DisplayItemIconMessage::TYPE_MESSAGE,
                    $this->_entitiesInstance->getDefaultEntityID(),
                    DisplayItem::RATIO_SHORT,
                    'Signer EID'));
                $instanceList->addItem($this->_getInfoAppNoPreload($hashTarget));
                $instanceList->addItem($this->_getInfoAppActivated($hashTarget));

                $instanceLB = new DisplayBlankLine($this->_applicationInstance);
                $instanceList->addItem($instanceLB);
            }
        }
        unset($linksList);

        $instanceList->setSize(DisplayItem::SIZE_SMALL);
        $instanceList->display();

        /* TODO synchro all apps updates
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteObject',
                    'permitWriteLink', 'permitSynchronizeObject', 'permitSynchronizeLink',
                    'permitSynchronizeApplication', 'permitPublicSynchronizeApplication'))
                || $this->_entitiesInstance->getCurrentEntityIsUnlocked()
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
        }*/

        $instanceList = new DisplayList($this->_applicationInstance);
        $instanceMessage1 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage1->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage1->setMessage('Many different applications can be used. The application to use can
            be selected from the list on <a href="?a=1">application 1</a> or here on this list.<br/>
            At any time you can switch to another application and come back later to the first one without losing work
            in progress or authentication.<br/>&nbsp;');
        $instanceMessage1->setRatio(DisplayItem::RATIO_LONG);
        $instanceList->addItem($instanceMessage1);

        $instanceMessage2 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage2->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage2->setMessage("An application can be enabled or disabled.<br/>
            To be usable, each application have to be enabled on the list here. Without activation, the bootstrap
            refuse to load an application.<br/>
            To be enabled, an application have to be activated with a link generated by a local authority.<br/>
            By default, the application 'option' is automatically enabled and can't be disabled.<br/>&nbsp;");
        $instanceMessage2->setRatio(DisplayItem::RATIO_LONG);
        $instanceList->addItem($instanceMessage2);

        $instanceMessage3 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage3->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage3->setMessage('All applications can be synchronized to get last updates.<br/>
            Synchronization must be enabled with the option <i>permitSynchronizeApplication</i> and if needed with
            the option <i>permitPublicSynchronizeApplication</i>.<br/>&nbsp;');
        $instanceMessage3->setRatio(DisplayItem::RATIO_LONG);
        $instanceList->addItem($instanceMessage3);

        $instanceMessage4 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage4->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage4->setMessage('Each application is declared by the
            <a href="http://code.master.nebule.org">code master</a> or by a local authority and can be updated by a
            local authority.<br/>
            On first loading of an application, a preload permit to enhance the user experience. The preload can be
            disabled for an application by the code master or by a local authority.<br/>&nbsp;');
        $instanceMessage4->setRatio(DisplayItem::RATIO_LONG);
        $instanceList->addItem($instanceMessage4);

        $instanceList->setSize(DisplayItem::SIZE_SMALL);
        $instanceList->display();

        echo "</div>\n";
    }


    /**
     * Affichage des entités de recouvrement.
     *
     * @return void
     */
    private function _displayRecoveryEntities(): void
    {
        $instanceTitle = new DisplayTitle($this->_applicationInstance);
        $instanceTitle->setTitle('Local recovery');
        $instanceTitle->setIcon(null);
        $instanceTitle->setEnableEntity(true);
        $instanceTitle->display();

        $listAuthorities = $this->_authoritiesInstance->getAuthoritiesID();
        $listOkEntities = array();
        foreach ($listAuthorities as $eid)
            $listOkEntities[$eid] = true;

        if ($this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities')) {
            $refRecovery = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT, References::REFERENCE_CRYPTO_HASH_ALGORITHM) . '.' . References::REFERENCE_CRYPTO_HASH_ALGORITHM;

            $instanceList = new DisplayList($this->_applicationInstance);
            foreach ($this->_recoveryInstance->getRecoveryEntitiesInstance() as $instance) {
                if (!isset($listOkEntities[$instance->getID()])
                    && $instance->getType('all') == Entity::ENTITY_TYPE
                    && $instance->getIsPublicKey()
                ) {
                    $instanceEntity = new DisplayObject($this->_applicationInstance);
                    $this->_setCommonEntityFlags($instanceEntity, $instance);
                    $instanceEntity->setFlagProtection(true);
                    $instanceEntity->setEnableRefs(true);
                    $instanceEntity->setRefs($this->_recoveryInstance->getRecoveryEntitiesSigners()[$instance->getID()]);
                    if ($this->_permitAction($instance)
                        && $this->_authoritiesInstance->getLocalAuthoritiesSigners()[$instance->getID()] == $this->_entitiesInstance->getCurrentEntityID()
                    ) {
                        $list[0]['name'] = 'Remove';
                        $list[0]['icon'] = Displays::DEFAULT_ICON_LX;
                        $list[0]['link'] = '/?'
                            . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_entitiesInstance->getServerEntityID() . '_' . $instance->getID() . '_' . $refRecovery
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        $instanceEntity->setSelfHookList($list);
                    }
                    $instanceList->addItem($instanceEntity);
                }
                $listOkEntities[$instance->getID()] = true;
            }
            if ($this->_unlocked) {
                $instanceWarn = new DisplayInformation($this->_applicationInstance);
                $instanceWarn->setType(DisplayItemIconMessage::TYPE_MESSAGE);
                $instanceWarn->setMessage('On authority removing, need a second page reload to update the liste.');
                $instanceList->addItem($instanceWarn);
            }
            $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
            $instanceList->setEnableWarnIfEmpty(true);
            $instanceList->display();
        } else {
            $instanceWarn = new DisplayInformation($this->_applicationInstance);
            $instanceWarn->setType(DisplayItemIconMessage::TYPE_WARN);
            $instanceWarn->setMessage('::::err_NotPermit');
            $instanceWarn->setRatio(DisplayItem::RATIO_SHORT);
            $instanceWarn->display();
        }

        // Affiche les entités à ajouter.
        if ($this->_permitAction($this->_entitiesInstance->getCurrentEntityInstance())
            && $this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities')
        ) {
            $icon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_ADD);
            $instance = new DisplayTitle($this->_applicationInstance);
            $instance->setTitle('Add entities as recovery');
            $instance->setIcon($icon);
            $instance->display();

            // Lister les entités.
            $listEntities = $this->_entitiesInstance->getListEntitiesInstances();

            // Affiche les entités.
            $refRecovery = $this->_nebuleInstance->getCryptoInstance()->hash(References::REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT, References::REFERENCE_CRYPTO_HASH_ALGORITHM) . '.' . References::REFERENCE_CRYPTO_HASH_ALGORITHM;
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
                            . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_' . $this->_entitiesInstance->getServerEntityID() . '_' . $id . '_' . $refRecovery
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
            echo $this->getDisplayObjectsList_DEPRECATED($list, 'medium');
            unset($list);
        }

        $instanceList = new DisplayList($this->_applicationInstance);
        $instanceMessage1 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage1->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage1->setMessage("The recovery entities can, if needed, unprotect an object. This can be a security choose in an enterprise or legal contraint in some countries.");
        $instanceMessage1->setRatio(DisplayItem::SIZE_SMALL);
        $instanceMessage1->setIconText('Type');
        $instanceList->addItem($instanceMessage1);
        $instanceMessage2 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage2->setType(DisplayItemIconMessage::TYPE_WARN);
        $instanceMessage2->setMessage("Whatever the entity that protect an object, the protection is automatically and silently shared with recovery entities. All recovery entities are displayed here, none are hidden.");
        $instanceMessage2->setRatio(DisplayItem::SIZE_SMALL);
        $instanceMessage2->setIconText('Type');
        $instanceList->addItem($instanceMessage2);
        $instanceMessage3 = new DisplayInformation($this->_applicationInstance);
        $instanceMessage3->setType(DisplayItemIconMessage::TYPE_MESSAGE);
        $instanceMessage3->setMessage("An entity can be added as recovery entity and later can be removed. Entities marqued as instance entity or default entity can't be removed, they are forced by two options on the environment file : 'permitInstanceEntityAsRecovery' and 'permitDefaultEntityAsRecovery'.");
        $instanceMessage3->setRatio(DisplayItem::SIZE_SMALL);
        $instanceMessage3->setIconText('Type');
        $instanceList->addItem($instanceMessage3);
        $instanceList->setSize(DisplayItem::SIZE_SMALL);
        $instanceList->display();
    }


    private function _displayEnd(): void
    {
    }


    private function _displayChecks(): void
    {
        ?>

        <div id="check">
            <?php
            if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'WARN')
                $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'ERROR')
                $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'WARN')
                $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'ERROR')
                $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'WARN')
                $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
            if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'ERROR')
                $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
            if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'ERROR')
                $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityBootstrapMessage());
            if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'WARN')
                $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityBootstrapMessage());
            if ($this->_applicationInstance->getCheckSecuritySign() == 'WARN')
                $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecuritySignMessage());
            if ($this->_applicationInstance->getCheckSecuritySign() == 'ERROR')
                $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecuritySignMessage());
            if ($this->_applicationInstance->getCheckSecurityURL() == 'WARN')
                $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityURLMessage());
            if (!$this->_configurationInstance->getOptionAsBoolean('permitWrite'))
                $this->displayMessageWarning_DEPRECATED('::::warn_ServNotPermitWrite');
            if ($this->_nebuleInstance->getCacheInstance()->getFlushCache())
                $this->displayMessageWarning_DEPRECATED('::::warn_flushSessionAndCache');
            ?>

        </div>
        <?php
    }


    private function _htmlEnd(): void
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
    const COMMAND_OPTION_NAME = 'name';
    const COMMAND_OPTION_VALUE = 'value';

    public function genericActions():void
    {
        $this->_metrologyInstance->addLog('generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1f5dd135');

        if ($this->_unlocked
            && $this->_entitiesInstance->getCurrentEntityID() == $this->_entitiesInstance->getServerEntityID()
            && $this->_nebuleInstance->getTicketingInstance()->checkActionTicket()
            && $this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink', 'permitCreateLink'))
        ) {
            $this->_extractActionChangeOption();
            $this->_extractActionSignLink1();

            if ($this->_actionOptionName != '')
                $this->_actionChangeOption();

            if ($this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitUploadLink')
                && $this->_actionSignLinkInstance1 != ''
                && is_a($this->_actionSignLinkInstance1, 'Link') // FIXME la classe
            )
                $this->_actionSignLink($this->_actionSignLinkInstance1);
        }

        $this->_metrologyInstance->addLog('generic actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bb221384');
    }


    public function specialActions():void
    {
        $this->_metrologyInstance->addLog('special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '4e9ebfc1');

        if ($this->_nebuleInstance->getTicketingInstance()->checkActionTicket()) {
            $this->_extractActionSynchronizeApplication();

            if ($this->_actionSynchronizeApplicationInstance != '')
                $this->_actionSynchronizeApplication();
        }

        $this->_metrologyInstance->addLog('special actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1bb0ef71');
    }



    protected string $_actionOptionName = '';
    protected string $_actionOptionValue = '';

    protected function _extractActionChangeOption():void
    {
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink'))
            && $this->_unlocked
        ) {
            $this->_metrologyInstance->addLog('extract action change option', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '62a03a08');

            $argName = '';
            if (filter_has_var(INPUT_GET, self::COMMAND_OPTION_NAME)) {
                $argName = trim(filter_input(INPUT_GET, self::COMMAND_OPTION_NAME, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
                $this->_metrologyInstance->addLog('input ' . self::COMMAND_OPTION_NAME . ' ask change option name=' . $argName, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '64c852fb');
            }
            $argValue = '';
            if (filter_has_var(INPUT_GET, self::COMMAND_OPTION_VALUE)) {
                $argValue = trim(filter_input(INPUT_GET, self::COMMAND_OPTION_VALUE, FILTER_SANITIZE_STRING));
                $this->_metrologyInstance->addLog('input ' . self::COMMAND_OPTION_NAME . ' ask change option value=' . $argValue, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'cfebc367');
            }

            $okOption = false;
            foreach (Configuration::OPTIONS_LIST as $option) {
                if ($argName == $option) {
                    $okOption = true;
                    break;
                }
            }

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


    protected function _actionChangeOption():void
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisés.
        if ($this->_configurationInstance->checkBooleanOptions(array('permitWrite', 'permitWriteLink'))
            && $this->_actionOptionName != ''
            && $this->_unlocked
        ) {
            $this->_metrologyInstance->addLog('Action change option ' . $this->_actionOptionName . ' = ' . $this->_actionOptionValue, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ae2be3f7');

            // Vérifie que l'option est du bon type.
            $listOptionsType = $this->_configurationInstance->getListOptionsType();
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
                $this->_configurationInstance->setOptionEnvironment($this->_actionOptionName, $value);

                $this->_displayInstance->displayInlineAllActions();

                $this->_metrologyInstance->addLog('Action change option ok', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0db1fc8f');
            }

            // Affichage des actions.
            $this->_displayInstance->displayInlineAllActions();
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
class Translate extends Translates
{
    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::INFO' => 'Information',
            ':::NoPreload' => 'Non préchargé',
            ':::OkPreload' => 'Préchargé',
            ':::Activated' => 'Activé',
            ':::NotActivated' => 'Désactivé',
            ':::Open' => "Lancer l'application",
        ],
        'en-en' => [
            '::INFO' => 'Information',
            ':::NoPreload' => 'Not preloaded',
            ':::OkPreload' => 'Preloaded',
            ':::Activated' => 'Activated',
            ':::NotActivated' => 'Not activated',
            ':::Open' => 'Open application',
        ],
        'es-co' => [
            '::INFO' => 'Information',
            ':::NoPreload' => 'Not preloaded',
            ':::OkPreload' => 'Preloaded',
            ':::Activated' => 'Activated',
            ':::NotActivated' => 'Not activated',
            ':::Open' => 'Open application',
        ],
    ];
}
