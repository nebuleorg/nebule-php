<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app9 - sleeping mode
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
    const APPLICATION_VERSION = '020251103';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        $this->_metrologyInstance->log_reopen('app9');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'df3680d3');

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('app 9 - sleeping');
        $this->_htmlTop();

        echo '<div class="layout-main">' . "\n";
        echo ' <div class="layout-content">' . "\n";
        echo '  <p style="text-align:center">' . "\n";

        if ($this->getHaveInput(\Nebule\Library\References::COMMAND_SLEEP)) {
            $action = $this->getFilterInput(\Nebule\Library\References::COMMAND_SLEEP, FILTER_FLAG_NO_ENCODE_QUOTES);
            if ($action == 'true' && !file_exists(\Nebule\Library\References::OBJECTS_FOLDER . '/' . \Nebule\Library\References::INTERNAL_SLEEP_STATE)) {
                if (file_put_contents(\Nebule\Library\References::OBJECTS_FOLDER . '/' . \Nebule\Library\References::INTERNAL_SLEEP_STATE, 'sleep') === false)
                    echo '<b>Error creating sleeping flag file!</b><br /><br />' . "\n";
            }
            if ($action == 'false' && file_exists(\Nebule\Library\References::OBJECTS_FOLDER . '/' . \Nebule\Library\References::INTERNAL_SLEEP_STATE)) {
                if (unlink(\Nebule\Library\References::OBJECTS_FOLDER . '/' . \Nebule\Library\References::INTERNAL_SLEEP_STATE) === false)
                    echo '<b>Error removing sleeping flag file!</b><br /><br />' . "\n";
            }
        }

        $baseUrl = '?' . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . '=9&' . \Nebule\Library\References::COMMAND_SLEEP . '=';
        if (file_exists(\Nebule\Library\References::OBJECTS_FOLDER . '/' . \Nebule\Library\References::EXTERNAL_SLEEP_STATE)) {
            echo 'Status: deep sleeping<br /><br />' . "\n";
            if (file_exists(\Nebule\Library\References::OBJECTS_FOLDER . '/' . \Nebule\Library\References::INTERNAL_SLEEP_STATE))
                echo "<a href='" . $baseUrl . "false'>&gt;&gt;&gt; Ask exit sleep mode &lt;&lt;&lt;</a>";
            else
                echo 'Waiting exit deep sleeping mode...';
        } elseif (file_exists(\Nebule\Library\References::OBJECTS_FOLDER . '/' . \Nebule\Library\References::INTERNAL_SLEEP_STATE)) {
            echo 'Status: soft sleeping<br /><br />' . "\n";
            echo "<a href='" . $baseUrl . "false'>&gt;&gt;&gt; Exit sleep mode &lt;&lt;&lt;</a>";
        } else {
            echo 'Status: ready<br /><br />' . "\n";
            echo "<a href='" . $baseUrl . "true'>&gt;&gt;&gt; Enter sleep mode &lt;&lt;&lt;</a><br /><br />\n";
            echo "<a href='?" . \Nebule\Library\References::COMMAND_SWITCH_APPLICATION . "=1'>&gt; Return to application 1 &lt;</a>";
        }

        echo "  </p>\n";
        echo " </div>\n";
        echo "</div>\n";

        $this->_htmlBottom();
    }
}
