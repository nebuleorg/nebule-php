<?php
declare(strict_types=1);
namespace Nebule\Library;

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
    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance);

    public function arraySocialFilter(array &$links, $socialClass = '');

    public function linkSocialScore(Link &$link, $socialClass = '');
}
