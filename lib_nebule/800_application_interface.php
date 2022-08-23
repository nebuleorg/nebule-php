<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * L'interface applicationInterface
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 */
interface applicationInterface
{
    public function __construct(nebule $nebuleInstance);
    public function __toString(): string;
    public function __sleep(): array;
    public function __wakeup();

    public function initialisation(): void;
    public function getClassName(): string;
    public function getName(): string;
    public function getNebuleInstance(): nebule;
    public function getDisplayInstance(): Displays;
    public function getTraductionInstance(): Traductions;
    public function getMetrologyInstance(): Metrology;
    public function getActionInstance(): Actions;
    // TODO
}
