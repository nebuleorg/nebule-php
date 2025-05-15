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
    //public function getType(): string;

    /**
     * Retourne l'état de préparation'.
     *
     * @return bool
     */
    public function getReady(): bool;

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
    public function getLocation(): string;

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
     * @param string $url
     * @return string
     */
    public function getInstanceEntityID(string $url = ''): string;



    // Fonctions d'auto-test.

    /**
     * Vérifie l'arborescence des liens.
     *
     * @param string $url
     * @return boolean
     */
    public function checkLinksDirectory(string $url = ''): bool;

    /**
     * Vérifie l'arborescence des objets.
     *
     * @param string $url
     * @return boolean
     */
    public function checkObjectsDirectory(string $url = ''): bool;

    /**
     * Vérifie les capacité de lecture de l'arborescence des liens.
     *
     * @param string $url
     * @return boolean
     */
    public function checkLinksRead(string $url = ''): bool;

    /**
     * Vérifie les capacité d'écriture de l'arborescence des liens.
     *
     * @param string $url
     * @return boolean
     */
    public function checkLinksWrite(string $url = ''): bool;

    /**
     * Vérifie les capacité de lecture de l'arborescence des objets.
     *
     * @param string $url
     * @return boolean
     */
    public function checkObjectsRead(string $url = ''): bool;

    /**
     * Vérifie les capacité d'écriture de l'arborescence des objets.
     *
     * @param string $url
     * @return boolean
     */
    public function checkObjectsWrite(string $url = ''): bool;



    // Fonctions de test de présence.

    /**
     * Indique true si l'objet a des liens, ou false sinon.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $oid
     * @param string $url
     * @return boolean
     */
    public function checkLinkPresent(string $oid, string $url = ''): bool;

    /**
     * Indique true si l'objet est présent, ou false sinon.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $oid
     * @param string $url
     * @return boolean
     */
    public function checkObjectPresent(string $oid, string $url = ''): bool;



    // Fonctions de lecture.

    /**
     * Lit les liens de l'objet. Retourne un tableau des liens lus, même vide.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $oid
     * @param string $url
     * @param int    $offset
     * @return array
     */
    public function getBlockLinks(string $oid, string $url = '', int $offset = 0): array;

    /**
     * Lit les liens dissimulés de l'entité dite destinataire. Retourne un tableau des liens lus, même vide.
     * Attend en entrée une chaine avec l'ID de l'entité et si besoin une chaine avec l'ID du signataire source.
     * Si le signataire n'est pas précisé, il doit être positionné à 0.
     * Dans ce cas la recherche se fera sur tous les signataires qui ont générés des liens dissimulés pour l'entité destinataire.
     *
     * @param string $entity
     * @param string $signer
     * @param string $url
     * @return array
     */
    public function getObfuscatedLinks(string $entity, string $signer = '0', string $url = ''): array;

    /**
     * Lit le contenu de l'objet. Retourne le contenu lu ou false si erreur.
     *
     * @param string $oid
     * @param int    $maxsize
     * @param string $url
     * @return string|boolean
     */
    public function getObject(string $oid, int $maxsize = 0, string $url = '');



    // Fonctions d'écriture.

    /**
     * Write link to one object.
     *
     * @param string $oid
     * @param string $link
     * @param string $url
     * @return boolean
     */
    public function setBlockLink(string $oid, string &$link, string $url = ''): bool;

    /**
     * Write data to an object.
     *
     * @param string $oid
     * @param string $data
     * @param string $url
     * @return boolean
     */
    public function setObject(string $oid, string &$data, string $url = ''): bool;

    

    // Fonctions de suppression.

    /**
     * Supprime la ligne d'un lien dans les liens d'un objet.
     *
     * @param string $oid
     * @param string $link
     * @param string $url
     * @return boolean
     */

    public function unsetLink(string $oid, string &$link, string $url = ''): bool;

    /**
     * Supprime tous les liens d'un objet.
     *
     * @param string $oid
     * @param string $url
     * @return boolean
     */

    public function flushLinks(string $oid, string $url = ''): bool;

    /**
     * Supprime le contenu d'un objet. Retourne true si la suppression a réussi ou false si erreur.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $oid
     * @param string $url
     * @return boolean
     */
    public function unsetObject(string $oid, string $url = ''): bool;
}
