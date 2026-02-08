<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayNotify
 *     ---
 * Display or prepare a simple message to the interface with the user.
 *     ---
 * Example:
 *  $instance = new DisplayMessage($this->_applicationInstance);
 *  $instance->setMessage('Message to user', 'arg1', 'arg2', 'arg3', 'arg4', 'arg5');
 *  $instance->setType(DisplayInformation::TYPE_MESSAGE);
 *  $instance->setIconText('Alternative text on icon');
 *  $icon = $this->_cacheInstance->newNode(Displays::DEFAULT_ICON_IOK);
 *  $instance->setIcon($icon);
 *  $instance->setLink('https://nebule.org');
 *  $instance->display();
 *     ---
 * Usage:
 *   - setMessage : Define message to display. If empty, nothing will be displayed.
 *       If empty, nothing displayed.
 *       Default: empty.
 *       String
 *   - setType : Détermine le type de message.
 *       Les types disponibles :
 *       - DisplayInformation::TYPE_MESSAGE : affichage d'un message simple en blanc sur fond noir transparent.
 *       - DisplayInformation::TYPE_OK : affichage d'un message de validation en blanc sur fond vert.
 *       - DisplayInformation::TYPE_WARN : affichage d'un message d'avertissement en jaune gras sur fond orange clair.
 *       - DisplayInformation::TYPE_ERROR : affichage d'un message d'erreur en rouge gras sur fond rose clair.
 *       - DisplayInformation::TYPE_INFORMATION : affichage d'un message simple en noir sur fond blanc transparent (style d'affichage des objets).
 *       - DisplayInformation::TYPE_GO : affichage d'un message pour valider une action.
 *       - DisplayInformation::TYPE_BACK : affichage d'un message de retour arrière.
 *       Default: DisplayInformation::TYPE_INFORMATION
 *       String
 *   - setIconText : Détermine le nom du type de message à afficher.
 *       If empty, no alternate text displayed.
 *       Default: empty.
 *       String
 *   - setIcon : Détermine l'icône à utiliser.
 *       Si null, l'icône est sélectionnée automatiquement en fonction du type de message.
 *       Default: null
 *       Node/null
 *   - setLink : Détermine le lien HTML à utiliser.
 *       If empty, no link displayed.
 *       Default: empty.
 *   - getHTML : Get HTML code to use.
 *   - display : Display HTML code.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class DisplayNotify extends DisplayItemIconMessage implements DisplayInterface
{
    protected function _initialisation(): void
    {
        $this->setType(self::TYPE_INFORMATION);
        $this->setIconText('[I]');
    }

    public function getHTML(): string
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_message == '')
            return '';

        $result = '<div class="notify' . $this->_type . ' notifyCommon">';
        if ($this->_link != '')
            $result .= '<a href="' . $this->_link . '">';
        $result .= '<p>';
        if ($this->_icon !== null)
            $result .= $this->_getImageHTML($this->_icon, $this->_iconText);
        $result .= '&nbsp;' . $this->_message . '</p>';
        if ($this->_link != '')
            $result .= '</a>';
        $result .= '</div>';
        $result .= "\n";

        return $result;
    }

    public static function displayCSS(): void // FIXME
    {
        ?>
        <style type="text/css">
            /* CSS for DisplayNotify(). */
            .notifyCommon {
                background-origin: border-box;
                text-align: left;
            }

            .notifyCommon p {
                color: #000000;
                padding: 5px;
            }

            .notifyCommon img {
                height: 32px;
                width: 32px;
            }

            .notifyCommon a:link, .notifyCommon a:visited {
                font-weight: normal;
                text-decoration: none;
            }

            .notifyCommon a:hover, .notifyCommon a:active {
                font-weight: bold;
                text-decoration: none;
            }

            .notifyMessage {
                background: #333333;
            }
            .notifyMessage p {
                color: #ffffff;
            }

            .notifyInformation {
                background: #ababab;
            }
            .notifyInformation p {
                color: #000000;
            }

            .notifyOk {
                background: #103020;
                font-family: monospace;
                font-size: 1.2em;
            }
            .notifyOk p {
                color: #ffffff;
            }

            .notifyError {
                background: #ffa0a0;
                font-family: monospace;
                font-size: 1.2em;
            }
            .notifyError p {
                color: #ff0000;
            }

            .notifyWarn {
                background: #ffe080;
                font-family: monospace;
                font-size: 1.2em;
            }
            .notifyWarn p {
                color: #ff8000;
            }

            .notifyGo {
                background: #abbcab;
            }
            .notifyGo p {
                color: #000000;
            }

            .notifyBack {
                background: #abbccd;
            }
            .notifyBack p {
                color: #000000;
            }
        </style>
        <?php
    }
}
