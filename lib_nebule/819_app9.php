<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app9
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class App9 extends App0
{
    const APPLICATION_NAME = 'app9';
    const APPLICATION_SURNAME = 'nebule/app9';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250928';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        global $lastReferenceSID;

        $this->_metrologyInstance->log_reopen('app9');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'df3680d3');

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('app 9 - test');
        $this->_htmlTop();

        echo '<div class="layout-main">' . "\n";
        echo ' <div class="layout-content">' . "\n";

        $ridList = array(
            \Nebule\Bootstrap\LIB_RID_INTERFACE_BOOTSTRAP,
            \Nebule\Bootstrap\LIB_RID_INTERFACE_LIBRARY,
            \Nebule\Bootstrap\LIB_RID_INTERFACE_APPLICATIONS);

        foreach ($ridList as $rid)
        {
            echo "RID=<a href='?a=4&" . \Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER . "=$rid'>$rid</a><br />\n";
            $appList = \Nebule\Bootstrap\app_getList($rid, false);
            foreach ($appList as $iid) {
                echo "&gt;&nbsp;IID=<a href='?a=4&" . \Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER . "=$iid'>$iid</a><br />\n";
                $links = array();
                \Nebule\Bootstrap\app_getCodeList($iid, $links);
                foreach ($links as $link)
                {
                    $oid = $link['bl/rl/nid2'];
                    $eid = $link['bs/rs1/eid'];
                    $date = $link['bl/rc'];
                    echo "&nbsp;-&nbsp;$date&nbsp;EID=<a href='?a=4&" . \Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER . "=$eid'>$eid</a>a>&nbsp;OID=<a href='?a=4&" . \Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER . "=$oid'>$oid</a><br />\n";
                }
                $oid = \Nebule\Bootstrap\app_getCode($iid);
                echo "&nbsp;+&nbsp;EID=<a href='?a=4&" . \Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER . "=$lastReferenceSID'>$lastReferenceSID</a>&nbsp;OID=<a href='?a=4&" . \Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER . "=$oid'>$oid</a><br />\n";
                echo "<br />\n";
            }
        }

        echo " </div>\n";
        echo "</div>\n";

        $this->_htmlBottom();
    }
}
