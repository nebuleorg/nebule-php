<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface ActionsInterface
{
    public function __construct(nebule $nebuleInstance);
    public function initialisation(): void;
    public function genericActions(): void;
    public function specialActions(): void;
}
