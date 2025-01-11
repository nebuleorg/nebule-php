<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app7
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class App7
{
    const APPLICATION_NAME = 'defolt';
    const APPLICATION_SURNAME = 'nebule/defolt';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250111';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        // Initialisation des logs
        \Nebule\Bootstrap\log_reopen('app0');
        \Nebule\Bootstrap\log_add('Loading', 'info', __FUNCTION__, '3a5c4178');

        echo 'CHK';
        ob_end_clean();

        \Nebule\Bootstrap\bootstrap_htmlHeader();
        \Nebule\Bootstrap\bootstrap_htmlTop();

        echo '<div class="layout-main">' . "\n";
        echo ' <div class="layout-content">' . "\n";
        echo '  <img alt="nebule" id="logo" src="' . \Nebule\Bootstrap\LIB_APPLICATION_LOGO_LIGHT . '"/>' . "\n";
        echo " </div>\n";
        echo "</div>\n";

        \Nebule\Bootstrap\bootstrap_htmlBottom();
    }
}
