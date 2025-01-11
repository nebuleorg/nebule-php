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
class App2
{
    const APPLICATION_NAME = 'autent';
    const APPLICATION_SURNAME = 'nebule/autent';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250111';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    const DEFAULT_REDIRECT_AUTH_APP = '9020606a70985a00f1cf73e6aed5cfd46399868871bd26d6c0bd7a202e01759c3d91b97e.none.288';

    public function display(): void
    {
        global $nebuleServerEntity;

        \Nebule\Bootstrap\log_reopen('app2');
        \Nebule\Bootstrap\log_add('Loading', 'info', __FUNCTION__, 'cb4450a2');

        if (filter_has_var(INPUT_GET, References::COMMAND_APPLICATION_BACK)) {
            $argBack = trim(filter_input(INPUT_GET, References::COMMAND_APPLICATION_BACK, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            \Nebule\Bootstrap\log_add('input ' . References::COMMAND_APPLICATION_BACK . ' ask come back to application nid=' . $argBack, 'info', __FUNCTION__, 'a8a5401d');
        }
        else
            $argBack = '1';
        if (filter_has_var(INPUT_GET, References::COMMAND_SELECT_ENTITY)) {
            $argEnt = trim(filter_input(INPUT_GET, References::COMMAND_SELECT_ENTITY, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            \Nebule\Bootstrap\log_add('input ' . References::COMMAND_SELECT_ENTITY . ' ask use entity eid=' . $argEnt, 'info', __FUNCTION__, '425694ce');
        }
        else
            $argEnt = $nebuleServerEntity;
        $argLogout = filter_has_var(INPUT_GET, References::COMMAND_AUTH_ENTITY_LOGOUT);
        $args = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . self::DEFAULT_REDIRECT_AUTH_APP;
        $args .= '&' . References::COMMAND_APPLICATION_BACK . '=' . $argBack;
        $args .= '&' . References::COMMAND_SELECT_ENTITY . '=' . $argEnt;
        $args .= '&mod=auth&view=';
        if ($argLogout)
            $args .= References::COMMAND_AUTH_ENTITY_LOGOUT . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT;
        else
            $args .= References::COMMAND_AUTH_ENTITY_LOGIN;

        echo 'CHK';
        ob_end_clean();

        ?>
<!DOCTYPE html>
<html lang="">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <link rel="icon" type="image/png" href="favicon.png"/>
        <meta http-equiv="refresh" content="0; url=<?php echo $args; ?>" />
        <title>Redirect</title>
    </head>
    <body>
        Redirect...
    </body>
</html>
        <?php
    }
}
