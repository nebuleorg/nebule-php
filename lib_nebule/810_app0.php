<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app0
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class App0 extends Functions
{
    const APPLICATION_NAME = 'defolt';
    const APPLICATION_SURNAME = 'nebule/defolt';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250928';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    const DEFAULT_FIRST_RELOAD_DELAY = 3000;

    public function display(): void
    {
        $this->_metrologyInstance->log_reopen('app0');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '3a5c4178');

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('defolt');
        $this->_htmlTop();

        echo '<div class="layout-main">' . "\n";
        echo ' <div class="layout-content">' . "\n";
        echo '  <img alt="nebule" id="logo" src="' . References::NEBULE_LOGO_LIGHT . '"/>' . "\n";
        echo " </div>\n";
        echo "</div>\n";

        $this->_htmlBottom();
    }

    protected function _htmlHeader(string $title = '', bool $refresh = false, int $refreshTimer = 0, string $refreshLink = ''): void {
        global $bootstrapFlush, $libraryRescueMode;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1111c0de');

        ?>
        <!DOCTYPE html>
        <html lang="">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title><?php echo nebule::NEBULE_NAME;
            if ($title != '') echo ' - ' . $title;
            if ($bootstrapFlush) echo ' - flush mode';
            if ($libraryRescueMode) echo ' - rescue mode'; ?></title>
        <link rel="icon" type="image/png" href="favicon.png"/>
        <?php if ($refresh) { ?>
            <meta http-equiv="refresh" content="<?php echo $refreshTimer; ?>; url=<?php echo $refreshLink; ?>">
        <?php } ?>
        <meta name="author"
              content="<?php echo nebule::NEBULE_AUTHOR . ' - ' . nebule::NEBULE_WEBSITE . ' - ' . nebule::NEBULE_VERSION; ?>"/>
        <meta name="licence" content="<?php echo nebule::NEBULE_LICENCE . ' ' . nebule::NEBULE_AUTHOR; ?>"/>
        <style>
            /* CSS reset. http://meyerweb.com/eric/tools/css/reset/ v2.0 20110126. Public domain */
            * {
                margin: 0;
                padding: 0;
            }

            html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre,
            a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp,
            small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li,
            fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td,
            article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup,
            menu, nav, output, ruby, section, summary, time, mark, audio, video {
                border: 0;
                font: inherit;
                font-size: 100%;
                vertical-align: baseline;
            }

            article, aside, details, figure, figcaption, footer, header, hgroup, menu, nav, section {
                display: block;
            }

            body {
                line-height: 1;
            }

            ol, ul {
                list-style: none;
            }

            blockquote, q {
                quotes: none;
            }

            blockquote:before, blockquote:after, q:before, q:after {
                content: none;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
            }

            /* Balises communes. */
            html {
                height: 100%;
                width: 100%;
            }

            body {
                color: #ababab;
                font-family: monospace;
                background: #454545;
                height: 100%;
                width: 100%;
                min-height: 480px;
                min-width: 640px;
            }

            img, embed, canvas, video, audio, picture {
                max-width: 100%;
                height: auto;
            }

            img {
                border: 0;
                vertical-align: middle;
            }

            a:link, a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ababab;
            }

            a:hover, a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            input {
                background: #ffffff;
                color: #000000;
                margin: 0;
                margin-top: 5px;
                border: 0;
                box-shadow: none;
                padding: 5px;
                background-origin: border-box;
            }

            input[type=submit] {
                font-weight: bold;
            }

            input[type=password], input[type=text], input[type=email] {
                padding: 6px;
            }

            /* Le bloc d'entête */
            .layout-header {
                position: fixed;
                top: 0;
                width: 100%;
                text-align: center;
            }

            .layout-header {
                height: 68px;
                background: #ababab;
                border-bottom-style: solid;
                border-bottom-color: #c8c8c8;
                border-bottom-width: 1px;
            }

            .header-left {
                height: 64px;
                width: 64px;
                margin: 2px;
                float: left;
            }

            .header-left img {
                height: 64px;
                width: 64px;
            }

            .header-right {
                height: 64px;
                width: 64px;
                margin: 2px;
                float: right;
            }

            .header-center {
                height: 100%;
                display: inline-flex;
            }

            .header-center p {
                margin: auto 3px 3px 3px;
                overflow: hidden;
                white-space: nowrap;
                color: #454545;
                text-align: center;
            }

            /* Le bloc de bas de page */
            .layout-footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
            }

            .layout-footer {
                height: 68px;
                background: #ababab;
                border-top-style: solid;
                border-top-color: #c8c8c8;
                border-top-width: 1px;
            }

            .footer-center p {
                margin: 3px;
                overflow: hidden;
                white-space: nowrap;
                color: #454545;
                text-align: center;
            }

            .footer-center a:link, .footer-center a:visited {
                font-weight: normal;
                text-decoration: none;
                color: #454545;
            }

            .footer-center a:hover, .footer-center a:active {
                font-weight: normal;
                text-decoration: underline;
                color: #ffffff;
            }

            /* Le corps de la page qui contient le contenu. Permet le centrage vertical universel */
            .layout-main {
                width: 100%;
                height: 100%;
                display: flex;
            }

            /* Le centre de la page avec le contenu utile. Centrage vertical */
            .layout-content {
                margin: auto;
                padding: 74px 0 74px 0;
            }

            /* Spécifique bootstrap et app 0. */
            .parts {
                margin-bottom: 12px;
            }

            .partstitle {
                font-weight: bold;
            }

            .preload {
                clear: both;
                margin-bottom: 12px;
                min-height: 64px;
                width: 600px;
            }

            .preload img {
                height: 64px;
                width: 64px;
                float: left;
                margin-right: 8px;
            }

            .preloadsync img {
                height: 16px;
                width: 16px;
                float: none;
                margin-left: 0;
                margin-right: 1px;
            }

            .preloadstitle {
                font-weight: bold;
                color: #ffffff;
                font-size: 1.4em;
            }

            #appslist {
            }

            #appslist a:link, #appslist a:visited {
                font-weight: normal;
                text-decoration: none;
                color: #ffffff;
            }

            #appslist a:hover, #appslist a:active {
                font-weight: normal;
                text-decoration: none;
                color: #ffff80;
            }

            .apps {
                float: left;
                margin: 4px;
                height: 96px;
                width: 96px;
                padding: 8px;
                color: #ffffff;
                overflow: hidden;
            }

            .appstitle {
                font-size: 2em;
                font-weight: normal;
                text-decoration: none;
                color: #ffffff;
                margin: 0;
            }

            .appsname {
                font-weight: bold;
            }

            .appssigner {
                float: right;
                height: 24px;
                width: 24px;
            }

            .appssigner img {
                height: 24px;
                width: 24px;
            }

            #sync {
                clear: both;
                width: 100%;
                height: 50px;
            }

            #footer {
                position: fixed;
                bottom: 0;
                text-align: center;
                width: 100%;
                padding: 3px;
                background: #ababab;
                border-top-style: solid;
                border-top-color: #c8c8c8;
                border-top-width: 1px;
                margin: 0;
            }

            .error, .important {
                color: #ffffff;
                font-weight: bold;
            }

            .diverror {
                color: #ffffff;
                padding-top: 6px;
                padding-bottom: 6px;
            }

            .diverror pre {
                padding-left: 6px;
                margin: 3px;
                border-left-style: solid;
                border-left-color: #ababab;
                border-left-width: 1px;
            }

            #reload {
                padding-top: 32px;
                clear: both;
            }

            #reload a {
                color: #ffffff;
                font-weight: bold;
            }

            .important {
                background: #ffffff;
                color: #000000;
                font-weight: bold;
                margin: 10px;
                padding: 10px;
            }

            /* Spécifique app 1. */
            #layout_documentation {
                background: #ffffff;
                padding: 20px;
                text-align: left;
                color: #000000;
                font-size: 0.8rem;
                font-family: sans-serif;
                min-width: 400px;
                max-width: 1200px;
            }

            #title_documentation {
                margin-bottom: 30px;
            }

            #title_documentation p {
                text-align: center;
                color: #000000;
                font-size: 0.7em;
            }

            #title_documentation p a:link, #title_documentation p a:visited {
                font-weight: normal;
                text-decoration: none;
                color: #000000;
            }

            #title_documentation p a:hover, #title_documentation p a:active {
                font-weight: normal;
                text-decoration: underline;
                color: #000000;
            }

            #content_documentation {
                text-align: justify;
                color: #000000;
                font-size: 1em;
            }

            #content_documentation h1 {
                text-align: left;
                color: #454545;
                font-size: 2em;
                font-weight: bold;
                margin-left: 10px;
                margin-top: 80px;
                margin-bottom: 5px;
            }

            #content_documentation h2 {
                text-align: left;
                color: #454545;
                font-size: 1.8em;
                font-weight: bold;
                margin-left: 10px;
                margin-top: 60px;
                margin-bottom: 5px;
            }

            #content_documentation h3 {
                text-align: left;
                color: #454545;
                font-size: 1.6em;
                font-weight: bold;
                margin-left: 10px;
                margin-top: 40px;
                margin-bottom: 5px;
            }

            #content_documentation h4 {
                text-align: left;
                color: #454545;
                font-size: 1.4em;
                font-weight: bold;
                margin-left: 10px;
                margin-top: 30px;
                margin-bottom: 5px;
            }

            #content_documentation h5 {
                text-align: left;
                color: #454545;
                font-size: 1.2em;
                font-weight: bold;
                margin-left: 10px;
                margin-top: 20px;
                margin-bottom: 5px;
            }

            #content_documentation h6 {
                text-align: left;
                color: #454545;
                font-size: 1.1em;
                font-weight: bold;
                margin-left: 10px;
                margin-top: 20px;
                margin-bottom: 5px;
            }

            #content_documentation p {
                text-align: justify;
                margin-top: 5px;
            }

            .pcenter {
                text-align: center;
            }

            #content_documentation a:link, #content_documentation a:visited {
                font-weight: normal;
                text-decoration: underline;
                color: #000000;
            }

            #content_documentation a:hover, #content_documentation a:active {
                font-weight: normal;
                text-decoration: underline;
                color: #0000ab;
            }

            code {
                font-family: monospace;
            }

            #content_documentation pre {
                font-family: monospace;
                text-align: left;
                border-left-style: solid;
                border-left-color: #c8c8c8;
                border-left-width: 1px;
            }

            #content_documentation ol li, #content_documentation ul li {
                text-align: left;
                list-style-position: inside;
                margin-left: 10px;
            }

            #content_documentation ol {
                list-style-type: decimal-leading-zero;
            }

            #content_documentation ul {
                list-style-type: disc;
            }
        </style>
        <script language="javascript" type="text/javascript">
            <!--
            function replaceInlineContentFromURL(id, url) {
                document.getElementById(id).innerHTML = '<object class="inline" type="text/html" data="' + url + '" ></object>';
            }

            function followHref(url) {
                window.location.href = url;
            }

            //-->
        </script>
    </head>
        <?php
    }

    protected function _htmlTop(): void {
        global $nebuleServerEntity;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1111c0de');

        $name = \Nebule\Bootstrap\ent_getFullName($nebuleServerEntity);
        if ($name == $nebuleServerEntity)
            $name = '/';
        $defaultApp = '0';
        if ($this->_configurationInstance->getOptionAsBoolean('permitApplication1'))
            $defaultApp = '1';

        ?>
        <body>
    <div class="layout-header">
        <div class="header-left">
            <a href="/?<?php echo References::COMMAND_SWITCH_APPLICATION . '=' . $defaultApp; ?>">
                <img title="App switch" alt="[]" src="data:image/png;base64,<?php echo References::OBJ_IMG['nebule']; ?>" />
            </a>
        </div>
        <div class="header-right">
            &nbsp;
        </div>
        <div class="header-center">
            <p>
                <?php echo $name . '<br />' . $nebuleServerEntity; ?>
            </p>
        </div>
    </div>
    <div class="layout-footer">
        <div class="footer-center">
            <p>
                <?php echo nebule::NEBULE_NAME; ?><br/>
                <?php echo nebule::NEBULE_VERSION; ?><br/>
                (c) <?php echo nebule::NEBULE_LICENCE . ' ' . nebule::NEBULE_AUTHOR; ?> - <a
                    href="http://<?php echo nebule::NEBULE_WEBSITE; ?>" target="_blank"
                    style="text-decoration:none;"><?php echo nebule::NEBULE_WEBSITE; ?></a>
            </p>
        </div>
    </div>

        <?php
        echo '<div class="layout-main">' . "\n"; // TODO à revoir...
        echo '    <div class="layout-content">';
    }

    protected function _partDisplayReloadPage(bool $ok = true, int $delay = 0): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1111c0de');
        if ($delay == 0)
            $delay = self::DEFAULT_FIRST_RELOAD_DELAY;

        echo '<div id="reload">' . "\n";
        if ($ok) {
            ?>

            &gt; <a onclick="javascript:window.location.reload();">reloading <?php echo nebule::NEBULE_NAME; ?></a> ...
            <script type="text/javascript">
                <!--
                setTimeout(function () {
                    window.location.reload()
                }, <?php echo $delay; ?>);
                //-->
            </script>
        <?php
        } else {
        ?>

            <button onclick="javascript:window.location.reload();">when ready,
                reload <?php echo nebule::NEBULE_NAME; ?></button>
            <?php
        }
        echo "</div>\n";
    }

    protected function _htmlBottom(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1111c0de');
        echo '    </div>' . "\n";
        echo '</div>';
        ?>

        </body>
        </html>
        <?php
    }
}
