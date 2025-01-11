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
class App3
{
    const APPLICATION_NAME = 'doctec';
    const APPLICATION_SURNAME = 'nebule/doctec';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250111';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        global $nebuleInstance, $nebuleLibLevel, $nebuleLibVersion, $nebuleLicence, $nebuleAuthor, $nebuleWebsite;

        // Initialisation des logs
        \Nebule\Bootstrap\log_reopen('app3');
        \Nebule\Bootstrap\log_add('Loading', 'info', __FUNCTION__, 'a4e4acfe');

        echo 'CHK';
        ob_end_clean();

        \Nebule\Bootstrap\bootstrap_htmlHeader();
        \Nebule\Bootstrap\bootstrap_htmlTop();

        // Instancie la classe de la documentation.
        $instance = new \Nebule\Library\Documentation($nebuleInstance);

        // Affiche la documentation.
        echo '<div id="layout_documentation">' . "\n";
        echo ' <div id="title_documentation"><p>Documentation technique de ' . $nebuleInstance->__toString() . '<br />' . "\n";
        echo '  Version ' . $nebuleInstance->getConfigurationInstance()->getOptionAsString('defaultLinksVersion')
            . ' - ' . $nebuleLibVersion . ' ' . $nebuleLibLevel . '<br />' . "\n";
        echo '  (c) ' . $nebuleLicence . ' ' . $nebuleAuthor . ' - <a href="' . $nebuleWebsite . '">' . $nebuleWebsite . "</a></p></div>\n";
        echo ' <div id="content_documentation">' . "\n";
        $instance->display_content();
        echo " </div>\n";
        echo "</div>\n";

        \Nebule\Bootstrap\bootstrap_htmlBottom();
    }
}
