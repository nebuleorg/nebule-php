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
    public static function displayCSS(): void;
    public function getHTML(): string;
    public function display(): void;
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

    public function __construct(Applications $applicationInstance)
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
    protected function _init(): void { $this->setSocial(''); }

    public static function displayCSS(): void {}
    public function getHTML(): string { return '';}
    public function display(): void { echo $this->getHTML(); }

    public function setSocial(string $social = ''): void
    {
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

abstract class DisplayItemIconable extends DisplayItem
{
    protected $_icon = null;
    protected $_iconUpdate = true;

    public function setIcon(?Node $oid, bool $update): void
    {
        if ($oid === null)
            $this->_icon = null; // = disabled
        elseif ($oid->getID() != '0' && is_a($oid, 'Nebule\Library\Node') && $oid->checkPresent())
            $this->_icon = $oid;

        $this->_iconUpdate = $update;
    }

    protected function _getNidIconHTML(?Node $nid, ?Node $icon = null): string
    {
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
        if (is_a($rid, 'Nebule\Library\Node'))
            $oid = $rid::DEFAULT_ICON_RID;
        else
            $oid = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Node::DEFAULT_ICON_RID));
        return $this->_nebuleInstance->newObject($oid);
    }

    protected function _getIconUpdate(?Node $nid): string
    {
        if ($this->_iconUpdate) {
            //$updateIcon = $nid->getUpdateNID(true, false, $this->_social); // FIXME TODO ERROR
            $updateIcon = '94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0.sha2.256'; // FIXME
            return $updateIcon;
        }
        else
            return $nid->getID();
    }
}

abstract class DisplayItemSizeable extends DisplayItemIconable
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
}