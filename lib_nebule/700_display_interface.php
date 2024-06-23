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
    protected function _init(): void {}

    public static function displayCSS(): void {}
    public function getHTML(): string { return '';}
    public function display(): void { echo $this->getHTML(); }
}

abstract class DisplayItemIconable extends DisplayItem
{
    protected $_icon = null;

    public function setIcon(?Node $oid): void
    {
        if ($oid === null)
            $this->_icon = null; // = disabled
        elseif ($oid->getID() != '0' && is_a($oid, 'Nebule\Library\Node') && $oid->checkPresent())
            $this->_icon = $oid;
    }
}

abstract class DisplayItemSizeable extends DisplayItemIconable
{
    const SIZE_TINY = 'tiny';
    const SIZE_SMALL = 'small';
    const SIZE_MEDIUM = 'medium';
    const SIZE_LARGE = 'large';
    const SIZE_FULL = 'full';
    const RATIO_SHORT = 'short';
    const RATIO_LONG = 'long';
    const RATIO_SQUARE = 'square';

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