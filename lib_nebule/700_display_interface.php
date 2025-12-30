<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface DisplayInterface
{
    public function __construct(Applications $applicationInstance);
    public function getHTML(): string;
    public function display(): void;
    public static function displayCSS(): void;
}

abstract class DisplayItem extends Functions implements DisplayInterface
{
    public const SIZE_SMALL = 'small';
    public const SIZE_LARGE = 'large';
    public const SIZE_TINY = 'tiny';
    public const SIZE_MEDIUM = 'medium';
    public const SIZE_FULL = 'full';
    public const RATIO_SQUARE = 'square';
    public const RATIO_SHORT = 'short';
    public const RATIO_LONG = 'long';

    protected string $_social = '';
    protected string $_sizeCSS = '';
    protected string $_ratioCSS = '';

    public function __construct(Applications $applicationInstance) // Should not be overridden by children's classes.
    {
        parent::__construct($applicationInstance->getNebuleInstance());
        $this->setEnvironmentLibrary($applicationInstance->getNebuleInstance());
        $this->setEnvironmentApplication($applicationInstance);
        $this->initialisation();
    }
    protected function _initialisation(): void { $this->setSocial('authority'); } // Should be overridden by children's classes.

    public static function displayCSS(): void {} // Must be overridden by children's classes.

    public function getHTML(): string { // Must be overridden by children's classes.
        $this->_metrologyInstance->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return '';
    }

    public function display(): void { // Should not be overridden by children's classes.
        $this->_metrologyInstance->addLog('display HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        echo $this->getHTML();
    }

    public function setSocial(string $social = 'authority'): void // Should not be overridden by children classes.
    {
        $this->_metrologyInstance->addLog('set social ' . $social, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        foreach ($this->_nebuleInstance->getSocialInstance()->getSocialNames() as $s) {
            if ($social == $s) {
                $this->_social = $social;
                break;
            }
        }
        if ($this->_social == '')
            $this->_social = 'authority';
    }

    public function setSize(string $size = ''): void
    {
        $this->_metrologyInstance->addLog('set size ' . $size, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_sizeCSS = match (strtolower($size)) {
            DisplayItem::SIZE_TINY => 'Tiny',
            DisplayItem::SIZE_SMALL => 'Small',
            DisplayItem::SIZE_LARGE => 'Large',
            DisplayItem::SIZE_FULL => 'Full',
            default => 'Medium',
        };
    }

    public function setRatio(string $ratio = ''): void
    {
        $this->_metrologyInstance->addLog('Set ratio ' . $ratio, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_ratioCSS = match (strtolower($ratio)) {
            DisplayItem::RATIO_SQUARE => 'Square',
            DisplayItem::RATIO_LONG => 'Long',
            default => 'Short',
        };
    }
}

abstract class DisplayItemCSS extends DisplayItem
{
    protected string $_classCSS = '';
    protected string $_idCSS = '';
    protected string $_styleCSS = '';

    public function setClassCSS(string $class = ''): void
    {
        $this->_metrologyInstance->addLog('set class CSS ' . $class, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_classCSS = trim((string)filter_var($class, FILTER_SANITIZE_STRING));
    }

    public function setIdCSS(string $id = ''): void
    {
        $this->_metrologyInstance->addLog('set id CSS ' . $id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_idCSS = trim((string)filter_var($id, FILTER_SANITIZE_STRING));
    }

    public function setStyleCSS(string $style = ''): void
    {
        $this->_metrologyInstance->addLog('set style CSS ' . $style, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_styleCSS = ' style="'
            . trim((string)filter_var($style, FILTER_SANITIZE_STRING))
            . ';"';
    }
}

abstract class DisplayItemIconable extends DisplayItemCSS
{
    protected ?Node $_icon = null;
    protected bool $_iconUpdate = true;
    protected string $_iconText = '';

    public function setIconRID(string $rid, bool $update = true): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->_cacheInstance->newNode($rid);
        if ($this->_nebuleInstance->getNodeIsRID($nid))
            $oid = $nid->getReferencedObjectInstance(References::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE, 'authority');
        else
            $oid = $nid;
        $this->setIcon($oid, $update);
    }

    public function setIcon(?Node $oid, bool $update = true): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (is_a($oid, 'Nebule\Library\Node')
            && $oid->getID() != '0'
            && $oid->checkPresent()
        )
            $this->_icon = $oid;
        else
            $this->_icon = null;

        $this->_iconUpdate = $update;
    }

    /**
     * Set the alternate text of the icon and the text of the box if not on tiny size.
     * Must be called after setType() because this changes the icon alternate name too.
     * The text is translated.
     * @param String $text
     * @return void
     */
    public function setIconText(String $text): void { $this->_iconText = $this->_translateInstance->getTranslate($text);}

    protected function _getNidIconHTML(?Node $nid, ?Node $icon = null): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($nid === null
            || $icon === null
            || !$icon->checkPresent()
        )
            $icon = $this->_getNidDefaultIcon($nid);

        $iconOid = $this->_getIconUpdate($icon);

        if ($this->_nebuleInstance->getIoInstance()->checkObjectPresent($iconOid))
            return References::OBJECTS_FOLDER . '/' . $iconOid;
        return '?' . References::OBJECTS_FOLDER . '=' . $iconOid;
    }

    protected function _getNidDefaultIcon(?Node $rid): Node
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (is_a($rid, 'Nebule\Library\Node'))
            $oid = $rid::DEFAULT_ICON_RID;
        else
            $oid = $this->_displayInstance->getImageByReference($this->_cacheInstance->newNode(Node::DEFAULT_ICON_RID))->getID();
        return $this->_cacheInstance->newNode($oid);
    }

    protected function _getIconUpdate(?Node $nid): string
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_iconUpdate) {
            $updateIcon = $nid->getUpdateNID(true, false, $this->_social);
            if ($updateIcon == '')
                $updateIcon = $nid->getID();
            if ($this->_nebuleInstance->getNodeIsRID($nid))
                $updateIcon = Displays::DEFAULT_ICON_LO;
            return $updateIcon;
        }
        else
            return $nid->getID();
    }

    protected function _getImageHTML(Node $oid, string $alt = '', string $class = '', string $id = '', string $args = ''): string
    {
        if ($oid->getID() == '0')
            return '';

        $result = '<img src="/' . References::OBJECTS_FOLDER . '/' . $oid->getID() . '"';

        if ($alt == '')
            $alt = $oid->getID();
        else
            $alt = $this->_translateInstance->getTranslate($alt);
        $result .= ' alt="' . $alt . '" title="' . $alt . '"';

        if ($class != '')
            $result .= ' class="' . $class . '"';

        if ($id != '')
            $result .= ' id="' . $id . '"';

        if ($args != '')
            $result .= ' ' . $args;

        $result .= ' />';
        return $result;
    }
}

abstract class DisplayItemIconMessage extends DisplayItemIconable
{
    public const TYPE_MESSAGE = 'message';
    public const TYPE_INFORMATION = 'information';
    public const TYPE_OK = 'ok';
    public const TYPE_WARN = 'warn';
    public const TYPE_ERROR = 'error';
    public const TYPE_PLAY = 'play';
    public const TYPE_BACK = 'back';
    public const ICON_INFORMATION_RID = '69636f6e20696e666f726d6174696f6e000000000000000000000000000000000000.none.272';
    public const ICON_OK_RID = '69636f6e206f6b000000000000000000000000000000000000000000000000000000.none.272';
    public const ICON_WARN_RID = '69636f6e207761726e696e6700000000000000000000000000000000000000000000.none.272';
    public const ICON_ERROR_RID = '69636f6e206572726f72000000000000000000000000000000000000000000000000.none.272';
    public const ICON_PLAY_RID = '73745872cd2b46a40992470eaa6dd1e5ca1face3c38a1da7650d4040d82193b9021d.none.272';
    public const ICON_BACK_RID = '8ade584d3aa420335a7af82da4438654b891985777cc05bf6cbe86ebe328e31f1cc4.none.272';

    protected string $_message = '';
    protected string $_link = '';
    protected bool $_linkEnable = true;
    protected string $_type = '';

    public function setMessage(string $message,
                               string $arg1 = '',
                               string $arg2 = '',
                               string $arg3 = '',
                               string $arg4 = '',
                               string $arg5 = ''): void
    {
        $this->_metrologyInstance->addLog('set message ' . $message, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_message = sprintf($this->_translateInstance->getTranslate($message), $arg1, $arg2, $arg3, $arg4, $arg5);
    }

    public function setBrutMessage(string $message,
                               string $arg1 = '',
                               string $arg2 = '',
                               string $arg3 = '',
                               string $arg4 = '',
                               string $arg5 = ''): void
    {
        $this->_metrologyInstance->addLog('set brut message ' . $message, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_message = sprintf($message, $arg1, $arg2, $arg3, $arg4, $arg5);
    }

    public function setLink(string $link): void { $this->_link = trim((string)filter_var($link, FILTER_SANITIZE_URL)); }
    public function setLinkEnable(bool $enable): void { $this->_linkEnable = $enable; }

    public function setType(string $type): void
    {
        $this->_metrologyInstance->addLog('set type ' . $type, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch (strtolower($type)) {
            case self::TYPE_OK:
                $this->_type = 'Ok';
                $this->_iconText = '::OK';
                $icon = self::ICON_OK_RID;
                break;
            case self::TYPE_WARN:
                $this->_type = 'Warn';
                $this->_iconText = '::WARN';
                $icon = self::ICON_WARN_RID;
                break;
            case self::TYPE_ERROR:
                $this->_type = 'Error';
                $this->_iconText = '::ERROR';
                $icon = self::ICON_ERROR_RID;
                break;
            case self::TYPE_MESSAGE:
                $this->_type = 'Message';
                $this->_iconText = '::INFORMATION';
                $icon = self::ICON_INFORMATION_RID;
                break;
            case self::TYPE_PLAY:
                $this->_type = 'Go';
                $this->_iconText = '::GO';
                $icon = self::ICON_PLAY_RID;
                break;
            case self::TYPE_BACK:
                $this->_type = 'Back';
                $this->_iconText = '::BACK';
                $icon = self::ICON_BACK_RID;
                break;
            default:
                $this->_type = 'Information';
                $this->_iconText = '::INFORMATION';
                $icon = self::ICON_INFORMATION_RID;
                break;
        }
        $rid = $this->_cacheInstance->newNode($icon);
        $this->_icon = $rid->getReferencedObjectInstance(References::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE, $this->_social);
    }
}

abstract class DisplayItemIconMessageSizeable extends DisplayItemIconMessage
{
    protected string $_sizeCSS = '';
    protected string $_ratioCSS = '';

    public function setSize(string $size = ''): void
    {
        $this->_metrologyInstance->addLog('set size ' . $size, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_sizeCSS = match (strtolower($size)) {
            self::SIZE_TINY => 'Tiny',
            self::SIZE_SMALL => 'Small',
            self::SIZE_LARGE => 'Large',
            self::SIZE_FULL => 'Full',
            default => 'Medium',
        };
    }

    public function setRatio(string $ratio = ''): void
    {
        $this->_metrologyInstance->addLog('Set ratio ' . $ratio, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_ratioCSS = match (strtolower($ratio)) {
            self::RATIO_SQUARE => 'Square',
            self::RATIO_LONG => 'Long',
            default => 'Short',
        };
    }
}
