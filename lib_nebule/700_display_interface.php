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
    }

    public static function displayCSS(): void {}
    public function getHTML(): string { return '';}
    public function display(): void {}
}
