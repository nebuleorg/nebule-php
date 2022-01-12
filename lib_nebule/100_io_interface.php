<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Les classes qui implémentent l'interface ioInterface permettent l'accès bas niveau aux
 *   objets nebule et à leurs liens.
 *
 * Elles permettent de vérifier la présence des objets nebule et leurs liens,
 *   de les lire, de les écrire, et de supprimer leurs contenus.
 *
 * Chaque objet nebule est référencé par une chaine de caractères contenant l'ID en
 *   hexadécimal de l'objet.
 *
 * On ne peut utiliser directement un objet (instance) ici, il faut
 *   préalablement en extraire l'ID hexadécimal sous forme de texte.
 */
interface ioInterface
{
    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance);

    // Fonctions de gestion des modules.
    /**
     * Retourne le type de système de fichiers.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Retourne la chaine de filtre de ce type de FS.
     *
     * @return string
     */
    public function getFilterString(): string;

    /**
     * Retourne le mode de lecture/écriture RO/RW.
     *
     * @return string
     */
    public function getMode(): string;

    /**
     * Retourne la chaine de localisation par défaut.
     *
     * @return string
     */
    public function getDefaultLocalisation(): string;

    /**
     * Initialise la clé de transcodage des fichiers de liens dissimulés.
     *
     * @param string $key
     * @return void
     */
    public function setFilesTranscodeKey(string &$key): void;

    /**
     * Supprime la clé de transcodage des fichiers de liens dissimulés.
     *
     * @return void
     */
    public function unsetFilesTranscodeKey(): void;

    /**
     * Retourne l'ID de l'entité locale de l'instance.
     *
     * @param string $localisation
     * @return string
     */
    public function getInstanceEntityID(string $localisation = ''): string;


    // Fonctions d'auto-test.

    /**
     * Vérifie l'arborescence des liens.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkLinksDirectory(string $localisation = ''): bool;

    /**
     * Vérifie l'arborescence des objets.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkObjectsDirectory(string $localisation = ''): bool;

    /**
     * Vérifie les capacité de lecture de l'arborescence des liens.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkLinksRead(string $localisation = ''): bool;

    /**
     * Vérifie les capacité d'écriture de l'arborescence des liens.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkLinksWrite(string $localisation = ''): bool;

    /**
     * Vérifie les capacité de lecture de l'arborescence des objets.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkObjectsRead(string $localisation = ''): bool;

    /**
     * Vérifie les capacité d'écriture de l'arborescence des objets.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkObjectsWrite(string $localisation = ''): bool;


    // Fonctions de test de présence.

    /**
     * Indique true si l'objet a des liens, ou false sinon.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $object
     * @param string $localisation
     * @return boolean
     */
    public function checkLinkPresent(string $object, string $localisation = ''): bool;

    /**
     * Indique true si l'objet est présent, ou false sinon.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $object
     * @param string $localisation
     * @return boolean
     */
    public function checkObjectPresent(string $object, string $localisation = ''): bool;


    // Fonctions de lecture.

    /**
     * Lit les liens de l'objet. Retourne un tableau des liens lus, même vide.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $object
     * @param string $localisation
     * @return array|boolean
     */
    public function linksRead(string $object, string $localisation = '');

    /**
     * Lit les liens dissimulés de l'entité dite destinataire. Retourne un tableau des liens lus, même vide.
     * Attend en entrée une chaine avec l'ID de l'entité et si besoin une chaine avec l'ID du signataire source.
     * Si le signataire n'est pas précisé, il doit être positionné à 0.
     * Dans ce cas la recherche se fera sur tous les signataires qui ont générés des liens dissimulés pour l'entité destinataire.
     *
     * @param string $entity
     * @param string $signer
     * @param string $localisation
     * @return array
     */
    public function obfuscatedLinksRead(string $entity, string $signer = '0', string $localisation = ''): array;

    /**
     * Lit le contenu de l'objet. Retourne le contenu lu ou false si erreur.
     *
     * @param string $object
     * @param int    $maxsize
     * @param string $localisation
     * @return string|boolean
     */
    public function objectRead(string $object, int $maxsize = 0, string $localisation = '');


    // Fonctions d'écriture.

    /**
     * Ecrit un lien de l'objet. Retourne le nombre d'octets écrits ou false si erreur.
     *
     * @param string $object
     * @param string $link
     * @param string $localisation
     * @return number|boolean
     */
    public function writeLink(string $object, string &$link, string $localisation = '');

    /**
     * Ecrit des données dans un objet. Retourne l'empreinte de l'objet écrit ou false si erreur.
     *
     * @param string $data
     * @param string $localisation
     * @return string|boolean
     */
    public function writeObject(string &$data, string $localisation = '');


    // Fonctions de suppression.

    /**
     * Supprime la ligne d'un lien dans les liens d'un objet.
     *
     * @param string $object
     * @param string $link
     * @param string $localisation
     * @return boolean
     */

    public function deleteLink(string $object, string &$link, string $localisation = ''): bool;

    /**
     * Supprime tous les liens d'un objet.
     *
     * @param string $object
     * @param string $localisation
     * @return boolean
     */

    public function flushLinks(string $object, string $localisation = ''): bool;

    /**
     * Supprime le contenu d'un objet. Retourne true si la suppression a réussi ou false si erreur.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $object
     * @param string $localisation
     * @return boolean
     */
    public function deleteObject(string $object, string $localisation = ''): bool;
}
