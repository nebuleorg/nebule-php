<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for app2
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class App2 extends App0
{
    const APPLICATION_NAME = 'autent';
    const APPLICATION_SURNAME = 'nebule/autent';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250928';
    const APPLICATION_LICENCE = 'GNU GPL v3 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        global $nebuleServerEntity;

        $this->_metrologyInstance->log_reopen('app2');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'cb4450a2');

        if (filter_has_var(INPUT_GET, References::COMMAND_APPLICATION_BACK)) {
            $argBack = trim(filter_input(INPUT_GET, References::COMMAND_APPLICATION_BACK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $this->_metrologyInstance->addLog('input ' . References::COMMAND_APPLICATION_BACK . ' ask come back to application nid=' . $argBack, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'a8a5401d');
        }
        else
            $argBack = '1';
        if (filter_has_var(INPUT_GET, References::COMMAND_SWITCH_GHOST)) {
            $argEnt = trim(filter_input(INPUT_GET, References::COMMAND_SWITCH_GHOST, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            $this->_metrologyInstance->addLog('input ' . References::COMMAND_SWITCH_GHOST . ' ask use entity eid=' . $argEnt, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '425694ce');
        } else
            $argEnt = $nebuleServerEntity;
        $argLogout = (filter_has_var(INPUT_GET, Displays::COMMAND_DISPLAY_VIEW) && filter_input(INPUT_GET, Displays::COMMAND_DISPLAY_VIEW, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW) == References::COMMAND_AUTH_ENTITY_LOGOUT);
        $args = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . References::DEFAULT_REDIRECT_AUTH_APP;
        $args .= '&' . References::COMMAND_APPLICATION_BACK . '=' . $argBack;
        $args .= '&' . References::COMMAND_SWITCH_GHOST . '=' . $argEnt;
        $args .= '&' . Displays::COMMAND_DISPLAY_MODE . '=' . References::COMMAND_AUTH_ENTITY_MOD . '&' . Displays::COMMAND_DISPLAY_VIEW . '=';
        if ($argLogout)
            $args .= References::COMMAND_AUTH_ENTITY_LOGOUT;
        else
            $args .= References::COMMAND_AUTH_ENTITY_LOGIN;

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('app 2', true, 0, $args);
        $this->_htmlTop();

        echo '<div class="layout-main">' . "\n";
        echo ' <div class="layout-content">' . "\n";
        echo '  Redirecting to <a href="' . $args . '">authentication application</a>...' . "\n";
        echo " </div>\n";
        echo "</div>\n";

        $this->_htmlBottom();
    }
}
