<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
* Classe Modules
*
* @author Projet nebule
* @license GNU GPLv3
* @copyright Projet nebule
* @link www.nebule.org
*/
abstract class ModuleTranslates implements moduleTranslateInterface
{
    const MODULE_TYPE = 'Traduction';
    const MODULE_LANGUAGE = '/';
    const MODULE_NAME = 'None';
    const MODULE_MENU_NAME = 'None';
    const MODULE_DESCRIPTION = 'Description';
    const MODULE_VERSION = '020240721';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 sylabe 2013-2024';
    const MODULE_LOGO = '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c.sha2.256';
    const MODULE_INTERFACE = '3.0';
    
    protected ?Applications $_applicationInstance = null;

    /*public function __construct(Applications $applicationInstance)
    {
        $this->_applicationInstance = $applicationInstance;
    }*/

    public function __construct(nebule $nebuleInstance)
    {
        //$this->_applicationInstance = $nebuleInstance;
    }

    public function __destruct() { return true; }
    public function __toString(): string { return self::MODULE_TYPE; }
}