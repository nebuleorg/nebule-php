<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface linkInterface
{
    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance, string $link);

    /**
     * Retourne le lien complet.
     *
     * @return string
     */
    public function getFullLink(): string;

    /**
     * Retourne le lien pré-décomposé.
     *
     * @return array
     */
    public function getParsedLink(): array;

    /**
     * Retourne l'état de vérification et de validité du lien.
     *
     * @return boolean
     */
    public function getValid(): bool;

    /**
     * Retourne l'état de validité de la forme syntaxique du lien.
     *
     * @return boolean
     */
    public function getValidStructure(): bool;

    /**
     * Retourne si le lien a été vérifié dans sa forme syntaxique.
     *
     * @return boolean
     */
    public function getVerified(): bool;

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

    /**
     * Retourne si le lien est dissimulé.
     * Dans ce cas les informations retournées sont les informations du lien non dissimulé.
     *
     * @return boolean
     */
    public function getObfuscated(): bool;

    /**
     * Retourne la version avec laquelle est exploité le lien.
     * TODO à supprimer !
     * @return string
     */
    public function getVersion_disabled(): string;

    /**
     * Signature du lien par l'entité en cours.
     *
     * @param string $publicKey
     * @return boolean
     */
    public function sign(string $publicKey = '0'): bool;
}
