<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface blocLinkInterface
{
    public function __construct(nebule $nebuleInstance, string $blocLink, string $linkType = Cache::TYPE_LINK);

    /**
     * Get full string of the link.
     *
     * @return string
     */
    public function getRaw(): string;

    /**
     * Get list of links in the bloc of links.
     *
     * @return array:linkInterface
     */
    public function getLinks(): array;

    /**
     * Get list of signers of the bloc of links.
     *
     * @return array
     */
    public function getSignersEID(): array;

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
     * Get validity state of the link.
     * At this state, the link structure and the signs have been checked. The link is fully usable.
     * @return boolean
     */
    public function getValid(): bool;

    /**
     * Get validity state of the structure of the link.
     * At this state, the link is fully understand but signs are not necessary checked.
     * @return boolean
     */
    public function getValidStructure(): bool;

    /**
     * Get the status of the signs of the link.
     * If we have many, all signs are not necessary valid but one of them have been checked as valid.
     * @return boolean
     */
    public function getSigned(): bool;

    /**
     * Get version of the link.
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Get date of the link.
     *
     * @return string
     */
    public function getDate(): string;

    /**
     * Add a link (RL) on new bloc of links (BL).
     *
     * @param string $rl
     * @return bool
     */
    public function addLink(string $rl): bool;

    /**
     * Sign the link.
     *
     * @param string $publicKey
     * @return boolean
     */
    public function sign(string $publicKey = '0'): bool;

    /**
     * Write the link.
     *
     * @return boolean
     */
    public function write(): bool;

    /**
     * Sign and write the link.
     *
     * @param string $publicKey
     * @return boolean
     */
    public function signWrite(string $publicKey = '0'): bool;
}

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface linkInterface
{
    public function __construct(nebule $nebuleInstance, string $rl, blocLinkInterface $blocLink);

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

    public function getSignersEID(): array;
}
