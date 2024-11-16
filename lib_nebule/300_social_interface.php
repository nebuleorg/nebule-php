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
    public function __toString();

    /**
     * Retourne le type de système de fichiers.
     *
     * @return string
     */
    //public function getType(): string;

    /**
     * Retourne l'état de préparation'.
     *
     * @return bool
     */
    public function getReady(): bool;

    public function arraySocialFilter(array &$links, string $socialClass = ''): void;
    public function linkSocialScore(LinkRegister &$link, string $socialClass = ''): float;
    public function setList(array $listID): bool;
    public function unsetList(): bool;
}
