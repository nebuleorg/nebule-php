<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app4
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class App4
{
    const APPLICATION_NAME = 'app4';
    const APPLICATION_SURNAME = 'nebule/app4';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250307';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        // Initialisation des logs
        \Nebule\Bootstrap\log_reopen('app4');
        \Nebule\Bootstrap\log_add('Loading', 'info', __FUNCTION__, 'a1613ff2');

        echo 'CHK';
        ob_end_clean();

        \Nebule\Bootstrap\bootstrap_htmlHeader();
        \Nebule\Bootstrap\bootstrap_htmlTop();

        echo '<div class="layout-main">' . "\n";
        echo ' <div class="layout-content">' . "\n";

        $nid = '';
        $arg = '';
        if (filter_has_var(INPUT_GET, \Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER))
            $arg = trim(filter_input(INPUT_GET, \Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if (\Nebule\Bootstrap\nod_checkNID($arg))
            $nid = $arg;

        if ($nid == '')
            echo 'invalid NID' . "\n";
        else {
            if (\Nebule\Bootstrap\io_checkNodeHaveContent($nid))
                echo 'NID=<a href="o/' . $nid . '">' . $nid . '</a>';
            else
                echo 'NID=' . $nid;
            echo '<br />hypergraph:<br /><br />' . "\n";

            $blocLinks = array();
            \Nebule\Bootstrap\io_blockLinksRead($nid, $blocLinks);
            if (sizeof($blocLinks) == 0)
                echo 'not link for NID ' . $nid . "\n";
            else {
                foreach ($blocLinks as $bloc) {
                    if (strlen($bloc) == 0)
                        continue;
                    $parsedBloc = \Nebule\Bootstrap\blk_parse($bloc);

                    echo 'BH / RF=' . $parsedBloc['bh/rf'] . ' RV=' . $parsedBloc['bh/rv'] . "<br />\n";
                    echo 'BL / RC=' . $parsedBloc['bl/rc'] . '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RL=' . $parsedBloc['bl/rl/req'] . '>';
                    \Nebule\Bootstrap\bootstrap_echoLinkNID($parsedBloc['bl/rl/nid1'], substr($parsedBloc['bl/rl/nid1'], 0, 16));
                    $i = 2;
                    while ($parsedBloc['bl/rl/nid'.$i] != '') {
                        echo '>';
                        \Nebule\Bootstrap\bootstrap_echoLinkNID($parsedBloc['bl/rl/nid'.$i], substr($parsedBloc['bl/rl/nid'.$i], 0, 16));
                        $i++;
                        if ($i > \Nebule\Bootstrap\lib_getOption('linkMaxRLUID'))
                            break;
                    }
                    echo "<br />\n";
                    echo 'BS / EID=';
                    \Nebule\Bootstrap\bootstrap_echoLinkNID($parsedBloc['bs/rs1/eid'], substr($parsedBloc['bs/rs1/eid'], 0, 16));
                    echo ' SIG=' . substr($parsedBloc['bs/rs1/sig'], 0, 16) . ' ';
                    if (\Nebule\Bootstrap\blk_verify($bloc))
                        echo 'OK';
                    else
                        echo 'NOK';
                    echo "<br /><br />\n";
                }
            }
        }


        echo " </div>\n";
        echo "</div>\n";

        \Nebule\Bootstrap\bootstrap_htmlBottom();
    }
}
