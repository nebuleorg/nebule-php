<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * L'interface ioInterface
 *
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
    public function getType();

    /**
     * Retourne la chaine de filtre de ce type de FS.
     *
     * @return string
     */
    public function getFilterString();

    /**
     * Retourne le mode de lecture/écriture RO/RW.
     *
     * @return string
     */
    public function getMode();

    /**
     * Retourne la chaine de localisation par défaut.
     *
     * @return string
     */
    public function getDefaultLocalisation();

    /**
     * Initialise la clé de transcodage des fichiers de liens dissimulés.
     *
     * @param string $key
     * @return void
     */
    public function setFilesTranscodeKey(&$key);

    /**
     * Supprime la clé de transcodage des fichiers de liens dissimulés.
     *
     * @return void
     */
    public function unsetFilesTranscodeKey();

    /**
     * Retourne l'ID de l'entité locale de l'instance.
     *
     * @param string $localisation
     * @return string
     */
    public function getInstanceEntityID($localisation = '');


    // Fonctions d'auto-test.

    /**
     * Vérifie l'arborescence des liens.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkLinksDirectory($localisation = '');

    /**
     * Vérifie l'arborescence des objets.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkObjectsDirectory($localisation = '');

    /**
     * Vérifie les capacité de lecture de l'arborescence des liens.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkLinksRead($localisation = '');

    /**
     * Vérifie les capacité d'écriture de l'arborescence des liens.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkLinksWrite($localisation = '');

    /**
     * Vérifie les capacité de lecture de l'arborescence des objets.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkObjectsRead($localisation = '');

    /**
     * Vérifie les capacité d'écriture de l'arborescence des objets.
     *
     * @param string $localisation
     * @return boolean
     */
    public function checkObjectsWrite($localisation = '');


    // Fonctions de test de présence.

    /**
     * Indique true si l'objet a des liens, ou false sinon.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param unknown $object
     * @param string $localisation
     * @return boolean
     */
    public function checkLinkPresent(&$object, $localisation = '');

    /**
     * Indique true si l'objet est présent, ou false sinon.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param unknown $object
     * @param string $localisation
     * @return boolean
     */
    public function checkObjectPresent(&$object, $localisation = '');


    // Fonctions de lecture.

    /**
     * Lit les liens de l'objet. Retourne un tableau des liens lus, même vide.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $object
     * @param string $localisation
     * @return array|boolean
     */
    public function linksRead(&$object, $localisation = '');

    /**
     * Lit les liens dissimulés de l'entité dite destinataire. Retourne un tableau des liens lus, même vide.
     * Attend en entrée une chaine avec l'ID de l'entité et si besoin une chaine avec l'ID du signataire source.
     *
     * Si le signataire n'est pas précisé, il doit être positionné à 0.
     * Dans ce cas la recherche se fera sur tous les signataires qui ont générés des liens dissimulés pour l'entité destinataire.
     *
     * @param string $entity
     * @param string $signer
     * @param string $localisation
     * @return array
     */
    public function obfuscatedLinksRead(&$entity, $signer = '0', $localisation = '');

    /**
     * Lit le contenu de l'objet. Retourne le contenu lu ou false si erreur.
     *
     * @param string $object
     * @param number $maxsize
     * @param string $localisation
     * @return string|boolean
     */
    public function objectRead(&$object, $maxsize = 0, $localisation = '');


    // Fonctions d'écriture.

    /**
     * Ecrit un lien de l'objet. Retourne le nombre d'octets écrits ou false si erreur.
     *
     * @param string $object
     * @param string $link
     * @param string $localisation
     * @return number|boolean
     */
    public function linkWrite(&$object, &$link, $localisation = '');

    /**
     * Ecrit des données dans un objet. Retourne l'empreinte de l'objet écrit ou false si erreur.
     *
     * @param string $data
     * @param string $localisation
     * @return string|boolean
     */
    public function objectWrite(&$data, $localisation = '');


    // Fonctions de suppression.

    /**
     * Supprime la ligne d'un lien dans les liens d'un objet.
     *
     * @param string $object
     * @param string $link
     * @param string $localisation
     * @return boolean
     */

    public function linkDelete(&$object, &$link, $localisation = '');

    /**
     * Supprime tous les liens d'un objet.
     *
     * @param string $object
     * @param string $localisation
     * @return boolean
     */

    public function linksDelete(&$object, $localisation = '');

    /**
     * Supprime le contenu d'un objet. Retourne true si la suppression a réussi ou false si erreur.
     * Attend en entrée une chaine avec l'ID de l'objet.
     *
     * @param string $object
     * @param string $localisation
     * @return boolean
     */
    public function objectDelete(&$object, $localisation = '');
}
