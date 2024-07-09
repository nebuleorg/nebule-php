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
    protected $_nebuleInstance;
    protected $_displayInstance;
    protected $_applicationInstance;
    protected $_configurationInstance;
    protected $_metrologyInstance;
    protected $_traductionInstance;
    protected $_unlocked;
    protected $_social = '';

    public function __construct(Applications $applicationInstance) // Should not be overridden by children classes.
    {
        $this->_applicationInstance = $applicationInstance;
        $this->_nebuleInstance = $applicationInstance->getNebuleInstance();
        $this->_displayInstance = $applicationInstance->getDisplayInstance();
        $this->_configurationInstance = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_traductionInstance = $this->_applicationInstance->getTraductionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        $this->_init();
    }
    protected function _init(): void { $this->setSocial(''); } // Should be overridden by children classes.

    public static function displayCSS(): void {} // Must be overridden by children classes.

    public function getHTML(): string { // Must be overridden by children classes.
        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return '';
    }

    public function display(): void { // Should not be overridden by children classes.
        $this->_nebuleInstance->getMetrologyInstance()->addLog('display HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        echo $this->getHTML();
    }

    public function setSocial(string $social = ''): void // Should not be overridden by children classes.
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set social ' . $social, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($social == '')
        {
            $this->_social = 'all';
            return;
        }
        $socialList = $this->_nebuleInstance->getSocialInstance()->getSocialNames();
        foreach ($socialList as $s) {
            if ($social == $s) {
                $this->_social = $social;
                break;
            }
        }
        if ($this->_social == '')
            $this->_social = 'all';
    }
}

abstract class DisplayItemCSS extends DisplayItem
{
    protected $_classCSS = '';
    protected $_idCSS = '';
    protected $_styleCSS = '';

    public function setClassCSS(string $class = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set class CSS ' . $class, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_classCSS = trim(filter_var($class, FILTER_SANITIZE_STRING));
    }

    public function setIdCSS(string $id = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set id CSS ' . $id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_idCSS = trim(filter_var($id, FILTER_SANITIZE_STRING));
    }

    public function setStyleCSS(string $style = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set style CSS ' . $style, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_styleCSS = ' style="'
            . trim(filter_var($style, FILTER_SANITIZE_STRING))
            . ';"';
    }
}

abstract class DisplayItemIconable extends DisplayItemCSS
{
    protected $_icon = null;
    protected $_iconUpdate = true;
    protected $_iconText = '';

    public function setIcon(?Node $oid, bool $update = true): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($oid === null)
            $this->_icon = null;
        elseif ($oid->getID() != '0' && is_a($oid, 'Nebule\Library\Node') && $oid->checkPresent())
            $this->_icon = $oid;

        $this->_iconUpdate = $update;
    }

    public function setIconText(String $text)
    {
        $this->_iconText = $this->_traductionInstance->getTraduction($text);
    }

    protected function _getNidIconHTML(?Node $nid, ?Node $icon = null): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (is_a($rid, 'Nebule\Library\Node'))
            $oid = $rid::DEFAULT_ICON_RID;
        else
            $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Node::DEFAULT_ICON_RID));
        return $this->_nebuleInstance->newObject($oid);
    }

    protected function _getIconUpdate(?Node $nid): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_iconUpdate) {
            //$updateIcon = $nid->getUpdateNID(true, false, $this->_social); // FIXME TODO ERROR
            $updateIcon = '94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0.sha2.256'; // FIXME
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
        $alt = $this->_traductionInstance->getTraduction($alt);
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
    public const TYPE_GO = 'go';
    public const TYPE_BACK = 'back';
    public const ICON_INFORMATION_RID = '69636f6e20696e666f726d6174696f6e000000000000000000000000000000000000.none.272'; // FIXME unused ?
    public const ICON_OK_RID = '69636f6e206f6b000000000000000000000000000000000000000000000000000000.none.272';
    public const ICON_WARN_RID = '69636f6e207761726e696e6700000000000000000000000000000000000000000000.none.272';
    public const ICON_ERROR_RID = '69636f6e206572726f72000000000000000000000000000000000000000000000000.none.272';
    public const ICON_GO_RID = '69636f6e20696e666f726d6174696f6e000000000000000000000000000000000000.none.272'; // FIXME
    public const ICON_BACK_RID = '69636f6e20696e666f726d6174696f6e000000000000000000000000000000000000.none.272'; // FIXME

    protected $_message = '';
    protected $_link = '';
    protected $_type = '';

    public function setMessage(string $message,
                               string $arg1 = '',
                               string $arg2 = '',
                               string $arg3 = '',
                               string $arg4 = '',
                               string $arg5 = ''): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set message ' . $message, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_message = sprintf($this->_traductionInstance->getTraduction($message), $arg1, $arg2, $arg3, $arg4, $arg5);
    }

    public function setLink(string $link): void
    {
        $this->_link = trim(filter_var($link, FILTER_SANITIZE_URL));
    }

    public function setType(string $type): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('set type ' . $type, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        switch (strtolower($type)) {
            case self::TYPE_OK:
                $this->_type = 'Ok';
                $this->_iconText = '::::OK';
                $icon = Displays::DEFAULT_ICON_IOK;
                break;
            case self::TYPE_WARN:
                $this->_type = 'Warn';
                $this->_iconText = '::::WARN';
                $icon = Displays::DEFAULT_ICON_IWARN;
                break;
            case self::TYPE_ERROR:
                $this->_type = 'Error';
                $this->_iconText = '::::ERROR';
                $icon = Displays::DEFAULT_ICON_IERR;
                break;
            case self::TYPE_MESSAGE:
                $this->_type = 'Message';
                $this->_iconText = '::::INFORMATION';
                $icon = Displays::DEFAULT_ICON_IINFO;
                break;
            case self::TYPE_GO:
                $this->_type = 'Go';
                $this->_iconText = '::::GO';
                $icon = Displays::DEFAULT_ICON_IPLAY;
                break;
            case self::TYPE_BACK:
                $this->_type = 'Back';
                $this->_iconText = '::::BACK';
                $icon = Displays::DEFAULT_ICON_IBACK;
                break;
            default:
                $this->_type = 'Information';
                $this->_iconText = '::::INFORMATION';
                $icon = Displays::DEFAULT_ICON_IINFO;
                break;
        }
        $this->_icon = $this->_nebuleInstance->newObject($icon);
    }
}

abstract class DisplayItemSizeable extends DisplayItemIconMessage
{
    public const SIZE_TINY = 'tiny';
    public const SIZE_SMALL = 'small';
    public const SIZE_MEDIUM = 'medium';
    public const SIZE_LARGE = 'large';
    public const SIZE_FULL = 'full';
    public const RATIO_SHORT = 'short';
    public const RATIO_LONG = 'long';
    public const RATIO_SQUARE = 'square';

    protected $_sizeCSS = '';
    protected $_ratioCSS = '';

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
