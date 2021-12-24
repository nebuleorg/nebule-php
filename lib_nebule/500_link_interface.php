<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface bloclinkInterface
{
    public function __construct(nebule $nebuleInstance, string $bloclink, string $linkType = Cache::TYPE_LINK);

    /**
     * Retourne le lien complet.
     *
     * @return string
     */
    public function getRaw(): string;

    /**
     * Retourne le lien pré-décomposé.
     *
     * @return array
     */
    public function getParsed(): array;

    /**
     * Retourne l'état de vérification et de validité du lien.
     *
     * @return boolean
     */
    public function getValid(): bool;

    public function getLinks(): array;

    /**
     * Retourne le code d'erreur de vérification.
     *
     * @return int
     */
    public function getVerifyNumError(): int;

    /**
     * Retourne le texte de description de l'erreur de vérification.
     *
     * @return string
     */
    public function getVerifyTextError(): string;

    /**
     * Retourne si le lien est signé et si la signature est valide.
     * @return boolean
     */
    public function getSigned(): bool;

    public function getVersion(): string;

    public function getDate(): string;

    public function getSigners(): array;
}

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface linkInterface
{
    public function __construct(nebule $nebuleInstance, string $rl, bloclinkInterface $bloclink);

    /**
     * Retourne le lien complet.
     *
     * @return string
     */
    public function getRaw(): string;

    /**
     * Retourne le lien pré-décomposé.
     *
     * @return array
     */
    public function getParsed(): array;

    /**
     * Retourne l'état de vérification et de validité du lien.
     *
     * @return boolean
     */
    public function getValid(): bool;

    /**
     * Retourne le code d'erreur de vérification.
     *
     * @return int
     */
    public function getVerifyNumError(): int;

    /**
     * Retourne le texte de description de l'erreur de vérification.
     *
     * @return string
     */
    public function getVerifyTextError(): string;

    /**
     * Retourne si le lien est signé et si la signature est valide.
     * @return boolean
     */
    public function getSigned(): bool;

    public function getVersion(): string;

    public function getDate(): string;

    public function getSigners(): array;
}
