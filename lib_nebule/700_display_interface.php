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

abstract class DisplayItem implements DisplayInterface
{
    public const SIZE_SMALL = 'small';
    public const SIZE_LARGE = 'large';
    public const SIZE_TINY = 'tiny';
    public const SIZE_MEDIUM = 'medium';
    public const SIZE_FULL = 'full';
    public const RATIO_SQUARE = 'square';
    public const RATIO_SHORT = 'short';
    public const RATIO_LONG = 'long';

    protected ?nebule $_nebuleInstance = null;
    protected ?Configuration $_configurationInstance = null;
    protected ?Rescue $_rescueInstance = null;
    protected ?Metrology $_metrologyInstance = null;
    protected ?Cache $_cacheInstance = null;
    protected ?Entities $_entitiesInstance = null;
    protected ?Applications $_applicationInstance = null;
    protected ?Translates $_translateInstance = null;
    protected ?Displays $_displayInstance = null;
    protected bool $_unlocked = false;
    protected string $_social = '';
    protected string $_sizeCSS = '';
    protected string $_ratioCSS = '';

    public function __construct(Applications $applicationInstance) // Should not be overridden by children classes.
    {
        $this->_applicationInstance = $applicationInstance;
        $this->_nebuleInstance = $applicationInstance->getNebuleInstance();
        $this->_configurationInstance = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
        $this->_rescueInstance = $this->_nebuleInstance->getRescueInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_cacheInstance = $this->_nebuleInstance->getCacheInstance();
        $this->_entitiesInstance = $this->_nebuleInstance->getEntitiesInstance();
        $this->_displayInstance = $applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getCurrentEntityIsUnlocked();
        $this->_nebuleInstance->getMetrologyInstance()->addLog('init instance ' . get_class($this),
            Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'f631657');
        $this->_init();
    }
    protected function _init(): void { $this->setSocial('authority'); } // Should be overridden by children classes.

    public static function displayCSS(): void {} // Must be overridden by children classes.

    public function getHTML(): string { // Must be overridden by children classes.
        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return '';
    }

    public function display(): void { // Should not be overridden by children classes.
        $this->_nebuleInstance->getMetrologyInstance()->addLog('display HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        echo $this->getHTML();
    }

    public function setSocial(string $social = 'authority'): void // Should not be overridden by children classes.
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set social ' . $social, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set size ' . $size, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch (strtolower($size)) {
            case DisplayItem::SIZE_TINY:
                $this->_sizeCSS = 'Tiny';
                break;
            case DisplayItem::SIZE_SMALL:
                $this->_sizeCSS = 'Small';
                break;
            case DisplayItem::SIZE_LARGE:
                $this->_sizeCSS = 'Large';
                break;
            case DisplayItem::SIZE_FULL:
                $this->_sizeCSS = 'Full';
                break;
            default:
                $this->_sizeCSS = 'Medium';
                break;
        }
    }

    public function setRatio(string $ratio = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Set ratio ' . $ratio, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch (strtolower($ratio)) {
            case DisplayItem::RATIO_SQUARE:
                $this->_ratioCSS = 'Square';
                break;
            case DisplayItem::RATIO_LONG:
                $this->_ratioCSS = 'Long';
                break;
            default:
                $this->_ratioCSS = 'Short';
                break;
        }
    }

    protected function _getIsRID(Node $nid): bool {
        return str_contains($nid->getID(), '.none');
    }
}

abstract class DisplayItemCSS extends DisplayItem
{
    protected string $_classCSS = '';
    protected string $_idCSS = '';
    protected string $_styleCSS = '';

    public function setClassCSS(string $class = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set class CSS ' . $class, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_classCSS = trim((string)filter_var($class, FILTER_SANITIZE_STRING));
    }

    public function setIdCSS(string $id = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set id CSS ' . $id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_idCSS = trim((string)filter_var($id, FILTER_SANITIZE_STRING));
    }

    public function setStyleCSS(string $style = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set style CSS ' . $style, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $nid = $this->_cacheInstance->newNode($rid);
        if ($this->_getIsRID($nid)) {
            $oid = $nid->getReferencedObjectInstance(References::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE, 'authority');
        } else
            $oid = $nid;
        $this->setIcon($oid, $update);
    }

    public function setIcon(?Node $oid, bool $update = true): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($oid === null) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('DEBUGGING null icon', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
            $this->_icon = null;
        }
        elseif ($oid->getID() != '0'
            && is_a($oid, 'Nebule\Library\Node')
            && $oid->checkPresent()
        )
            $this->_icon = $oid;

        $this->_nebuleInstance->getMetrologyInstance()->addLog('DEBUGGING interface icon=' . (string)$this->_icon, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $this->_iconUpdate = $update;
    }

    public function setIconText(String $text): void {
        $this->_iconText = $this->_translateInstance->getTranslate($text);
    }

    protected function _getNidIconHTML(?Node $nid, ?Node $icon = null): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($nid === null
            || $icon === null
            || !$icon->checkPresent()
        )
            $icon = $this->_getNidDefaultIcon($nid);

        $iconOid = $this->_getIconUpdate($icon);

        if ($this->_nebuleInstance->getIoInstance()->checkObjectPresent($iconOid))
            return nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $iconOid;
        return '?' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '=' . $iconOid;
    }

    protected function _getNidDefaultIcon(?Node $rid): Node
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (is_a($rid, 'Nebule\Library\Node'))
            $oid = $rid::DEFAULT_ICON_RID;
        else
            $oid = $this->_displayInstance->getImageByReference($this->_cacheInstance->newNode(Node::DEFAULT_ICON_RID))->getID();
        return $this->_cacheInstance->newNode($oid);
    }

    protected function _getIconUpdate(?Node $nid): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_iconUpdate) {
            $updateIcon = $nid->getUpdateNID(true, false, $this->_social);
            if ($updateIcon == '')
                $updateIcon = $nid->getID();
            if ($this->_getIsRID($nid))
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

        $result = '<img src="/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $oid->getID() . '"';

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
    protected string $_type = '';

    public function setMessage(string $message,
                               string $arg1 = '',
                               string $arg2 = '',
                               string $arg3 = '',
                               string $arg4 = '',
                               string $arg5 = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set message ' . $message, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_message = sprintf($this->_translateInstance->getTranslate($message), $arg1, $arg2, $arg3, $arg4, $arg5);
    }

    public function setBrutMessage(string $message,
                               string $arg1 = '',
                               string $arg2 = '',
                               string $arg3 = '',
                               string $arg4 = '',
                               string $arg5 = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set brut message ' . $message, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_message = sprintf($message, $arg1, $arg2, $arg3, $arg4, $arg5);
    }

    public function setLink(string $link): void
    {
        $this->_link = trim((string)filter_var($link, FILTER_SANITIZE_URL));
    }

    public function setType(string $type): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set type ' . $type, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch (strtolower($type)) {
            case self::TYPE_OK:
                $this->_type = 'Ok';
                $this->_iconText = '::::OK';
                $icon = self::ICON_OK_RID;
                break;
            case self::TYPE_WARN:
                $this->_type = 'Warn';
                $this->_iconText = '::::WARN';
                $icon = self::ICON_WARN_RID;
                break;
            case self::TYPE_ERROR:
                $this->_type = 'Error';
                $this->_iconText = '::::ERROR';
                $icon = self::ICON_ERROR_RID;
                break;
            case self::TYPE_MESSAGE:
                $this->_type = 'Message';
                $this->_iconText = '::::INFORMATION';
                $icon = self::ICON_INFORMATION_RID;
                break;
            case self::TYPE_PLAY:
                $this->_type = 'Go';
                $this->_iconText = '::::GO';
                $icon = self::ICON_PLAY_RID;
                break;
            case self::TYPE_BACK:
                $this->_type = 'Back';
                $this->_iconText = '::::BACK';
                $icon = self::ICON_BACK_RID;
                break;
            default:
                $this->_type = 'Information';
                $this->_iconText = '::::INFORMATION';
                $icon = self::ICON_INFORMATION_RID;
                break;
        }
        $rid = $this->_cacheInstance->newNode($icon);
        $this->_icon = $rid->getReferencedObjectInstance(References::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE);
        $this->_nebuleInstance->getMetrologyInstance()->addLog('DEBUGGING _type=' . $this->_type, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $this->_nebuleInstance->getMetrologyInstance()->addLog('DEBUGGING _iconText=' . $this->_iconText, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        $this->_nebuleInstance->getMetrologyInstance()->addLog('DEBUGGING _icon=' . $this->_icon->getID(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
    }
}

abstract class DisplayItemIconMessageSizeable extends DisplayItemIconMessage
{
    protected string $_sizeCSS = '';
    protected string $_ratioCSS = '';

    public function setSize(string $size = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set size ' . $size, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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

    public function setRatio(string $ratio = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Set ratio ' . $ratio, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
}
