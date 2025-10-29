<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app1
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class App1 extends App0
{
    const APPLICATION_NAME = 'applis';
    const APPLICATION_SURNAME = 'nebule/applis';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250928';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        global $nebuleInstance;

        $this->_metrologyInstance->log_reopen('app1');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '314e6e9b');

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('app 1 - menu');
        $this->_htmlTop();

        echo '<div id="appslist">';

        // Display interrupt page.
        echo '<a href="/?b">';
        echo '<div class="apps" style="background:#000000;">';
        echo '<span class="appstitle">Nb</span><br /><span class="appsname">break</span>';
        echo "</div></a>\n";

        // Display flush page.
        echo '<a href="/?f">';
        echo '<div class="apps" style="background:#111111;">';
        echo '<span class="appstitle">Nf</span><br /><span class="appsname">flush</span>';
        echo "</div></a>\n";

        // Display default page.
        echo '<a href="/?a=0">';
        echo '<div class="apps" style="background:#222222;">';
        echo '<span class="appstitle">N0</span><br /><span class="appsname">defolt</span>';
        echo "</div></a>\n";

        // Display page of technical documentation.
        echo '<a href="/?a=3">';
        echo '<div class="apps" style="background:#333333;">';
        echo '<span class="appstitle">N3</span><br /><span class="appsname">doctec</span>';
        echo "</div></a>\n";

        // List all applications.
        $instanceRID = $this->_cacheInstance->newNode(References::RID_INTERFACE_APPLICATIONS);
        $phpNID = $this->getNidFromData(References::REFERENCE_OBJECT_APP_PHP);
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => References::RID_INTERFACE_APPLICATIONS,
            'bl/rl/nid3' => $phpNID,
            'bl/rl/nid4' => $this->_routerInstance->getCodeBranchNID(),
            'bl/rl/nid5' => '',
        );
        $instanceRID->getLinks($links, $filter, 'authority');
        $appList = array();
        foreach ($links as $i => $link) {
            if (isset($link->getParsed()['bl/rl/nid2']))
                $appList[$link->getParsed()['bl/rl/nid2']] = $link->getParsed()['bl/rl/nid2'];
        }
        foreach ($appList as $application) {
            $nebuleInstance->getMetrologyInstance()->addLog('show application nid=' . $application, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'b4f17c9d');
            $instance = new Node($nebuleInstance, $application);
            $color = '#' . substr($application . '000000', 0, 6);
            $title = $instance->getProperty(References::REFERENCE_NEBULE_OBJET_NOM,'authority');
            if ($title == '')
                $title = $instance->getProperty(References::REFERENCE_NEBULE_OBJET_NOM,'self');
            if ($title == '')
                $title = $instance->getProperty(References::REFERENCE_NEBULE_OBJET_NOM,'all');
            if ($title == '')
                $title = $instance->getID();
            $shortName = $instance->getProperty(References::REFERENCE_NEBULE_OBJET_SURNOM,'authority');
            if ($shortName == '')
                $shortName = $instance->getProperty(References::REFERENCE_NEBULE_OBJET_SURNOM,'self');
            if ($shortName == '')
                $shortName = $instance->getProperty(References::REFERENCE_NEBULE_OBJET_SURNOM,'all');
            $shortName = substr($shortName . '--', 0, 2);
            $subName = strtoupper(substr($shortName, 0, 1)) . strtolower(substr($shortName, 1, 1));
            $nebuleInstance->getMetrologyInstance()->addLog('app=' . $application . ' name=' . $title . ' sname=' . $shortName, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '9715d88e');
            echo '<a href="/?' . References::COMMAND_SWITCH_APPLICATION . '=' . $application . '">';
            echo '<div class="apps" style="background:' . $color . ';">';
            echo '<span class="appstitle">' . $subName . '</span><br /><span class="appsname">' . $title . '</span>';
            echo "</div></a>\n";
        }

        echo "</div>\n";
        echo '<div id="sync">'."\n";
        echo "</div>\n";
        $this->_htmlBottom();
    }
}
