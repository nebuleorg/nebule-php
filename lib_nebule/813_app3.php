<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app3
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class App3 extends App0
{
    const APPLICATION_NAME = 'doctec';
    const APPLICATION_SURNAME = 'nebule/doctec';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020251103';
    const APPLICATION_LICENCE = 'GNU GPL v3 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        $this->_metrologyInstance->log_reopen('app3');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'a4e4acfe');

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('app 3 - doctec');
        $this->_htmlTop();

        $instance = new \Nebule\Library\Documentation($this->_nebuleInstance);

        echo '<div id="layout_documentation">' . "\n";
        echo ' <div id="title_documentation"><p>Documentation technique de ' . $this->_nebuleInstance->__toString()
            . '<br />' . "\n";
        echo 'Version ' . $this->_configurationInstance->getOptionAsString('defaultLinksVersion')
            . ' - ' . nebule::NEBULE_VERSION
            . ' - ' . $this->_configurationInstance->getOptionAsString('codeBranch')
            . '<br />' . "\n";
        echo '(c) ' . nebule::NEBULE_LICENCE . ' ' . nebule::NEBULE_AUTHOR
            . ' - <a href="' . nebule::NEBULE_WEBSITE . '">' . nebule::NEBULE_WEBSITE . "</a></p></div>\n";
        echo ' <div id="content_documentation">' . "\n";
        $instance->display_content();
        echo " </div>\n";
        echo "</div>\n";

        $this->_htmlBottom();
    }
}
