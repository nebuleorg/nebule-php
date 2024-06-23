<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayInformation
 *     ---
 * Display or prepare a message to the interface with the user.
 *     ---
 * Example:
 *  $instance = new DisplayInformation($this->_applicationInstance);
 *  $instance->setMessage('Message to user', 'arg1', 'arg2', 'arg3', 'arg4', 'arg5');
 *  $instance->setDisplayAlone(true);
 *  $instance->setType(DisplayInformation::TYPE_MESSAGE);
 *  $instance->setIconText('Alternative text on icon');
 *  $instance->setSize(DisplayInformation::SIZE_MEDIUM);
 *  $instance->setRatio(DisplayInformation::RATIO_SHORT);
 *  $icon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IOK);
 *  $instance->setIcon($icon);
 *  $instance->setLink('https://everywhere.net');
 *  $instance->display();
 *     ---
 * Usage:
 *   - setMessage : Define message to display. If empty, nothing will be displayed.
 *       If empty, nothing displayed.
 *       Default: empty.
 *       String
 *   - setDisplayAlone : Affiche le message dans une position identique à un titre. C'est utilisé pour un message isolé.
 *       Default: true
 *       Boolean
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
 *   - setSize : Détermine la taille de l'affichage de l'élément complet.
 *       Tailles disponibles :
 *       - DisplayInformation::SIZE_TINY : très petite taille correspondant à un carré de base de 16 pixels de large.
 *           Certains éléments ne sont pas affichés.
 *       - DisplayInformation::SIZE_SMALL : petite taille correspondant à un carré de base de 32 pixels de large.
 *       - DisplayInformation::SIZE_MEDIUM : taille moyenne correspondant à un carré de base de 64 pixels de large par défaut.
 *       - DisplayInformation::SIZE_LARGE : grande taille correspondant à un carré de base de 128 pixels de large par défaut.
 *       - DisplayInformation::SIZE_FULL : très grande taille correspondant à un carré de base de 256 pixels de large par défaut.
 *       Default: DisplayInformation::SIZE_MEDIUM
 *       String
 *   - setRatio : Détermine la forme de l'affichage par son ratio dans la mesure du possible si pas d'affichage du contenu de l'objet.
 *       Ratios disponibles :
 *       - DisplayInformation::RATIO_SQUARE : forme carrée de 2x2 displaySize.
 *       - DisplayInformation::RATIO_SHORT : forme plate courte de 6x1 displaySize.
 *       - DisplayInformation::RATIO_LONG : forme plate longue de toute largeure disponible.
 *       Default: DisplayInformation::RATIO_SHORT
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
class DisplayInformation extends DisplayItem implements DisplayInterface
{
    const TYPE_MESSAGE = 'message';
    const TYPE_INFORMATION = 'information';
    const TYPE_OK = 'ok';
    const TYPE_WARN = 'warn';
    const TYPE_ERROR = 'error';
    const TYPE_GO = 'go';
    const TYPE_BACK = 'back';
    const SIZE_TINY = 'tiny';
    const SIZE_SMALL = 'small';
    const SIZE_MEDIUM = 'medium';
    const SIZE_LARGE = 'large';
    const SIZE_FULL = 'full';
    const RATIO_SHORT = 'short';
    const RATIO_LONG = 'long';
    const RATIO_SQUARE = 'square';

    private $_message = '';
    private $_displayAlone = false;
    private $_link = '';
    private $_type = '';
    private $_icon = null;
    private $_iconText = '';
    private $_sizeCSS = '';
    private $_ratioCSS = '';

    protected function _init(): void
    {
        $this->setType(self::TYPE_INFORMATION);
        $this->setSize(self::SIZE_MEDIUM);
        $this->setRatio(self::RATIO_SHORT);
    }

    public function getHTML(): string
    {
        if ($this->_message == '')
            return '';

        $result = '';
        $this->_getAloneStartHTML($result);
        $this->_getLinkStartHTML($result);
        if ($this->_sizeCSS == self::SIZE_TINY)
            $this->_getTinyHTML($result);
        else
            $this->_getNotTinyHTML($result);
        $this->_getLinkEndHTML($result);
        $this->_getAloneEndHTML($result);
        $result .= "\n";

        return $result;
    }

    private function _getAloneStartHTML(&$result)
    {
        if ($this->_displayAlone)
            $result .= '<div class="layoutAloneItem"><div class="aloneItemContent">';
    }

    private function _getAloneEndHTML(&$result)
    {
        if ($this->_displayAlone)
            $result .= '</div></div>';
    }

    private function _getLinkStartHTML(&$result)
    {
        if ($this->_link != '')
            $result .= '<a href="' . $this->_link . '">';
    }

    private function _getLinkEndHTML(&$result)
    {
        if ($this->_link != '')
            $result .= '</a>';
    }

    private function _getTinyHTML(&$result)
    {
        if ($this->_icon !== null)
            $result .= '<span style="font-size:1em" class="objectTitleIconsInline">' . $this->_displayInstance->convertUpdateImage($this->_icon, $this->_iconText) . '</span>';
        $result .= $this->_message;
    }

    private function _getNotTinyHTML(&$result)
    {
        $padding = 0;
        $result .= '<div class="layoutObject layoutInformation">';
        $result .= '<div class="objectTitle objectDisplay' . $this->_sizeCSS . $this->_ratioCSS . ' informationDisplay informationDisplay' . $this->_sizeCSS . ' informationDisplay' . $this->_type . '">';
        $result .= '<div class="objectTitleIcons informationTitleIcons informationTitleIcons' . $this->_type . '">';
        if ($this->_icon !== null) {
            $result .= $this->_displayInstance->convertUpdateImage($this->_icon, $this->_iconText);
            $padding = 1;
        }
        $result .= '</div>';
        $result .= '<div class="objectTitleText' . $padding . ' informationTitleText informationTitle' . $this->_sizeCSS . 'Text">';
        $result .= '<div class="objectTitleRefs objectTitle' . $this->_sizeCSS . 'Refs informationTitleRefs informationTitleRefs' . $this->_type . '">' . $this->_traductionInstance->getTraduction($this->_iconText) . '</div>';
        $result .= '<div class="informationTitleName informationTitleName' . $this->_type . ' informationTitle' . $this->_sizeCSS . 'Name">' . $this->_message . '</div>';
        $result .= '</div></div></div>';
    }

    public function setMessage(string $message,
                               string $arg1 = '',
                               string $arg2 = '',
                               string $arg3 = '',
                               string $arg4 = '',
                               string $arg5 = ''): void
    {
        $this->_message = sprintf($this->_traductionInstance->getTraduction($message), $arg1, $arg2, $arg3, $arg4, $arg5);
    }

    public function setLink(string $link): void
    {
        $this->_link = $link;
    }

    public function setType(string $type): void
    {
        switch (strtolower($type)) {
            case self::TYPE_OK:
                $this->_type = 'Ok';
                break;
            case self::TYPE_WARN:
                $this->_type = 'Warn';
                break;
            case self::TYPE_ERROR:
                $this->_type = 'Error';
                break;
            case self::TYPE_MESSAGE:
                $this->_type = 'Message';
                break;
            case self::TYPE_GO:
                $this->_type = 'Go';
                break;
            case self::TYPE_BACK:
                $this->_type = 'Back';
                break;
            default:
                $this->_type = 'Information';
                break;
        }

        switch ($this->_type) {
            case 'Ok':
                $this->_iconText = '::::OK';
                $this->_icon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IOK);
                break;
            case 'Warn':
                $this->_iconText = '::::WARN';
                $this->_icon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IWARN);
                break;
            case 'Error':
                $this->_iconText = '::::ERROR';
                $this->_icon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IERR);
                break;
            case 'Go':
                $this->_iconText = '::::GO';
                $this->_icon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IPLAY);
                break;
            case 'Back':
                $this->_iconText = '::::BACK';
                $this->_icon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IBACK);
                break;
            default:
                $this->_iconText = '::::INFORMATION';
                $this->_icon = $this->_nebuleInstance->newObject(Displays::DEFAULT_ICON_IINFO);
                break;
        }
    }

    public function setIcon(?Node $oid)
    {
        if ($oid === null)
            $this->_icon = null;
        elseif ($oid->getID() != '0' && $oid->checkPresent())
            $this->_icon = $oid;
    }
    
    public function setIconText(String $text)
    {
        $this->_iconText = $this->_traductionInstance->getTraduction($text);
    }

    public function setDisplayAlone(bool $DisplayAlone): void
    {
        $this->_displayAlone = $DisplayAlone;
    }

    public function setSize(string $size): void
    {
        switch (strtolower($size)) {
            case self::SIZE_TINY:
                $this->_sizeCSS = 'Tiny';
                break;
            case self::SIZE_SMALL:
                $this->_sizeCSS = 'Small';
                break;
            case self::SIZE_LARGE:
                $this->_sizeCSS = 'Large';
                break;
            case self::SIZE_FULL:
                $this->_sizeCSS = 'Full';
                break;
            default:
                $this->_sizeCSS = 'Medium';
                break;
        }
    }

    public function setRatio(string $ratio): void
    {
        switch (strtolower($ratio)) {
            case self::RATIO_SQUARE:
                $this->_ratioCSS = 'Square';
                break;
            case self::RATIO_LONG:
                $this->_ratioCSS = 'Long';
                break;
            default:
                $this->_ratioCSS = 'Short';
                break;
        }
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayInformation(). */
            .informationTitleIcons img {
                background: none;
            }

            .informationDisplay {
                height: auto;
            }

            .informationDisplayMessage {
                background: #333333;
            }

            .informationDisplayOk {
                background: #103020;
            }

            .informationDisplayWarn {
                background: #ffe080;
            }

            .informationDisplayError {
                background: #ffa0a0;
            }

            .informationDisplayInformation {
                background: #ababab;
            }

            .informationDisplayGo {
                background: #abbcab;
            }

            .informationDisplayBack {
                background: #abbccd;
            }

            .informationTitleText {
                background: none;
                height: auto;
            }

            .informationDisplayTiny {
            }

            .informationDisplaySmall {
                min-height: 32px;
                font-size: 32px;
                border: 0;
            }

            .informationDisplayMedium {
                min-height: 64px;
                font-size: 64px;
                border: 0;
            }

            .informationDisplayLarge {
                min-height: 128px;
                font-size: 128px;
                border: 0;
            }

            .informationDisplayFull {
                min-height: 256px;
                font-size: 256px;
                border: 0;
            }

            .informationTitleTinyText {
                min-height: 16px;
                background: none;
            }

            .informationTitleSmallText {
                min-height: 30px;
                text-align: left;
                padding: 1px 0 1px 1px;
                color: #000000;
            }

            .informationTitleMediumText {
                min-height: 58px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .informationTitleLargeText {
                min-height: 122px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .informationTitleFullText {
                min-height: 246px;
                text-align: left;
                padding: 5px 0 5px 5px;
                color: #000000;
            }

            .informationTitleName {
                font-weight: normal;
                overflow: hidden;
                height: auto;
            }

            .informationTitleNameMessage, .informationTitleRefsMessage {
                color: #ffffff;
            }

            .informationTitleNameOk, .informationTitleRefsOk {
                color: #ffffff;
            }

            .informationTitleNameWarn, .informationTitleRefsWarn {
                color: #ff8000;
            }

            .informationTitleNameGo, .informationTitleRefsGo {
                color: #ffffff;
            }

            .informationTitleNameBack, .informationTitleRefsBack {
                color: #ffffff;
            }

            .informationTitleNameWarn {
                font-weight: bold;
            }

            .informationTitleNameError, .informationTitleRefsError {
                color: #ff0000;
            }

            .informationTitleNameError {
                font-weight: bold;
            }

            .informationTitleNameInformation, .informationTitleRefsInformation {
                color: #000000;
            }

            .informationTitleTinyName {
                height: 1rem;
                line-height: 1rem;
                font-size: 1rem;
            }

            .informationTitleSmallName {
                line-height: 14px;
                overflow: hidden;
                white-space: normal;
                font-size: 1rem;
            }

            .informationTitleMediumName {
                line-height: 22px;
                overflow: hidden;
                white-space: normal;
                font-size: 1.2rem;
            }

            .informationTitleLargeName {
                line-height: 30px;
                overflow: hidden;
                white-space: normal;
                font-size: 1.5rem;
            }

            .informationTitleFullName {
                line-height: 62px;
                overflow: hidden;
                white-space: normal;
                font-size: 2rem;
            }
        </style>
        <?php
    }
}