<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * L'interface nodeInterface
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 */
interface nodeInterface
{
    public function __construct(nebule $nebuleInstance, string $id);
    public function __toString(): string;
    public function __sleep(): array;
    public function __wakeup();

    public function getID(): string;
    static public function checkNID(string &$nid, bool $permitNull = false): bool;
}
