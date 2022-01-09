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
    public function __construct(nebule $nebuleInstance, string $blocLink, string $linkType = Cache::TYPE_LINK);

    /**
     * Get full string of the link.
     *
     * @return string
     */
    public function getRaw(): string;

    /**
     * Get parsed content of the link.
     * array(
     *   'link'
     *   'bh'
     *   'bh/rf'
     *   'bh/rf/app'
     *   'bh/rf/typ'
     *   'bh/rv'
     *   'bh/rv/ver'
     *   'bh/rv/sub'
     *   'bl'
     *   'bl/rc'
     *   'bl/rc/mod'
     *   'bl/rc/chr'
     *   'bl/rl1'
     *   'bl/rl1/req'
     *   'bl/rl1/nid1'
     *   'bl/rl1/nid2'
     *   'bl/rl1/nid3'
     *           ...
     *   'bl/rl2'
     *       ...
     *   'bs' => $bs,
     *   'bs/rs1'
     *   'bs/rs1/eid'
     *   'bs/rs1/sig'
     *   'bs/rs2'
     *       ...
     * )
     *
     * @return array:string
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
    public function __construct(nebule $nebuleInstance, string $rl, bloclinkInterface $blocLink);

    /**
     * Retourne le lien complet.
     *
     * @return string
     */
    public function getRaw(): string;

    /**
     * Get parsed content of the link.
     * array(
     *   'bl/rl'
     *   'bl/rl/req'
     *   'bl/rl/nid1'
     *   'bl/rl/nid2'
     *   'bl/rl/nid3'
     *           ...
     * )
     *
     * @return array:string
     */
    public function getParsed(): array;

    /**
     * Retourne l'état de vérification et de validité du lien.
     *
     * @return boolean
     */
    public function getValid(): bool;

    /**
     * Retourne si le lien est signé et si la signature est valide.
     * @return boolean
     */
    public function getSigned(): bool;

    public function getVersion(): string;

    public function getDate(): string;

    public function getSigners(): array;
}
