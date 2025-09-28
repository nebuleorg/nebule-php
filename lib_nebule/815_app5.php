<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app5
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class App5 extends App0
{
    const APPLICATION_NAME = 'defolt';
    const APPLICATION_SURNAME = 'nebule/defolt';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250928';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        $this->_metrologyInstance->log_reopen('app5');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '7c70c571');

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('app 5');
        $this->_htmlTop();

        echo '<div class="layout-main">' . "\n";
        echo ' <div class="layout-content">' . "\n";
        echo '  <img alt="nebule" id="logo" src="' . References::NEBULE_LOGO_LIGHT . '"/>' . "\n";
        echo " </div>\n";
        echo "</div>\n";

        $this->_htmlBottom();
    }
}
