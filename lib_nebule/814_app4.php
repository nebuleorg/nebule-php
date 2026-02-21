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
class App4 extends App0
{
    const APPLICATION_NAME = 'app4';
    const APPLICATION_SURNAME = 'nebule/app4';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020260221';
    const APPLICATION_LICENCE = 'GNU GPL v3 2024-2026';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    const CUT_NID = 16;
    const CUT_NID3 = 32;

    public function display(): void
    {
        $this->_metrologyInstance->log_reopen('app4');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'a1613ff2');

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('app 4 - links');
        $this->_htmlTop();

        echo '<div class="layout-main">' . "\n";
        echo ' <div class="layout-content">' . "\n";

        $nid = '';
        $arg = '';
        if (filter_has_var(INPUT_GET, \Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER))
            $arg = $this->getFilterInput(\Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER, FILTER_FLAG_ENCODE_LOW);
        if (\Nebule\Bootstrap\nod_checkNID($arg))
            $nid = $arg;

        if ($nid == '')
            echo 'invalid NID' . "\n";
        else {
            if (\Nebule\Bootstrap\io_checkNodeHaveContent($nid))
                echo 'NID=<a href="o/' . $nid . '">' . $nid . '</a>';
            else
                echo 'NID=' . $nid;
            echo '<br />hyper-graph:<br /><br />' . "\n";

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
                    \Nebule\Bootstrap\bootstrap_echoLinkNID($parsedBloc['bl/rl/nid1'], $this->_seqNid($parsedBloc['bl/rl/nid1']));
                    $i = 2;
                    while ($parsedBloc['bl/rl/nid'.$i] != '') {
                        echo '>';
                        \Nebule\Bootstrap\bootstrap_echoLinkNID($parsedBloc['bl/rl/nid'.$i], $this->_seqNid($parsedBloc['bl/rl/nid'.$i]));
                        $i++;
                        if ($i > $this->_configurationInstance->getOptionAsString('linkMaxUIDbyRL'))
                            break;
                    }

                    $translate = $this->_translate($parsedBloc);
                    if ($translate != '')
                        echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;> ' . $translate . "\n";

                    echo "<br />\n";
                    echo 'BS / EID=';
                    \Nebule\Bootstrap\bootstrap_echoLinkNID($parsedBloc['bs/rs1/eid'], $this->_seqNid($parsedBloc['bs/rs1/eid']));
                    echo ' SIG=' . $this->_seqNid($parsedBloc['bs/rs1/sig']) . ' ';
                    if (\Nebule\Bootstrap\blk_verify($bloc)) // FIXME
                        echo 'OK';
                    else
                        echo 'NOK';
                    echo "<br /><br />\n";
                }
            }
        }


        echo " </div>\n";
        echo "</div>\n";

        $this->_htmlBottom();
    }

    private function _translate(array $parsedBloc): string
    {
        $nid1 = $this->_seqNid($parsedBloc['bl/rl/nid1']);
        $nid2 = $this->_seqNid($parsedBloc['bl/rl/nid2']);
        //$nid3 = $this->_seqNid($parsedBloc['bl/rl/nid3']);
        foreach (References::NODE_REFERENCES as $ref) {
            $hash = \nebule\bootstrap\crypto_getDataHash($ref, 'sha2.256') . '.sha2.256';
            $nid3 = $parsedBloc['bl/rl/nid3'];
            if ($nid3 == $hash) {
                $val = \nebule\bootstrap\obj_getAsText1line($parsedBloc['bl/rl/nid2'], self::CUT_NID3);
                if ($val == '')
                    $val = $nid2;
                return "$nid1 have $ref = $val";
            }
        }
        return '';
    }

    private function _seqNid(string $nid): string
    {
        return substr($nid, 0, self::CUT_NID);
    }
}
