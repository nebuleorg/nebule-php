<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * L'interface moduleInterface
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 */
interface moduleInterface
{
    public function __construct(Applications $applicationInstance);
    public function __toString(): string;

    public function initialisation(): void;
    public function display(): void;
}
