<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app6
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class App6 extends App0
{
    const APPLICATION_NAME = 'defolt';
    const APPLICATION_SURNAME = 'nebule/defolt';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020260101';
    const APPLICATION_LICENCE = 'GNU GPL v3 2024-2026';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        $this->_metrologyInstance->log_reopen('app6');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'f096bcb8');

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('app 6');
        $this->_htmlTop();

        echo '<div class="layout-main">' . "\n";
        echo ' <div class="layout-content">' . "\n";
        echo '  <img alt="nebule" id="logo" src="' . References::NEBULE_LOGO_LIGHT . '"/>' . "\n";
        echo " </div>\n";
        echo "</div>\n";

        $this->_htmlBottom();
    }
}
