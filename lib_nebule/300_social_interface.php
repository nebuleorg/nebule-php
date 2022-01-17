<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * L'interface SocialInterface.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface SocialInterface
{
    public function arraySocialFilter(array &$links, string $socialClass = ''): void;

    public function linkSocialScore(Link &$link, string $socialClass = ''): float;
}
