<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Actions on applications.
 * This class must not be used directly but via the entry point Actions->getInstanceActionsLocations().
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ActionsLocations extends Actions implements ActionsInterface {
    // WARNING: contents of constants for actions must be uniq for all actions classes!



    public function initialisation(): void {}
    public function genericActions(): void {}
    public function specialActions(): void {}



}