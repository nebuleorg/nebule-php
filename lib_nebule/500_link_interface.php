<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Interface pour la gestion des blocs de liens.
 *
 * Structure des blocs de liens :
 * -------------------------------
 * Un bloc de liens est une structure hiérarchisée permettant d'agréger, sécuriser et transporter
 * un ou plusieurs registres de liens. Il se compose de trois parties principales séparées par
 * le caractère underscore "_" :
 *
 *   BH_BL_BS
 *
 * Où :
 * - BH (Bloc Header) : En-tête du bloc contenant les métadonnées de forme et de version
 * - BL (Bloc Links) : Conteneur des registres de liens et de la chronologie
 * - BS (Bloc Signature) : Signatures cryptographiques pour sécuriser le bloc
 *
 * Détail de chaque partie :
 *
 * BH : RF/RV
 *   - RF (Registre de Forme) : APP:TYP où APP="nebule" et TYP="link"
 *   - RV (Registre de Version) : VER:SUB où VER=version majeure (ex: "2") et SUB=version mineure (ex: "0")
 *
 * BL : RC/RL1/RL2/...
 *   - RC (Registre de Chronologie) : MOD>CHR
 *     * MOD : Mode, actuellement toujours "0"
 *     * CHR : Valeur numérique correspondant au résultat de la commande
 *            shell date "+0%Y%m%d%H%M%S" (ex: "020261017143052")
 *   - RL (Registre de Lien) : REQ>NID1>NID2>NID3>...
 *     * REQ : Type de requête/action (1 caractère) :
 *       - l : Lien entre objets (link)
 *       - f : Dérivé d'objet (fork)
 *       - u : Mise à jour d'objet (update)
 *       - d : Suppression d'objet (delete)
 *       - e : Équivalence d'objets (equivalent)
 *       - c : Chiffrement de lien / Lien dissimulé (concealed/obfuscated link)
 *       - k : Chiffrement d'objet (encrypted object)
 *       - s : Subdivision d'objet (subdivision)
 *       - x : Suppression de lien (link deletion)
 *     * NID : Identifiant de nœud (hash hexadécimal) - au moins 1, jusqu'à linkMaxUIDbyRL (défaut: 4)
 *
 * BS : RS1/RS2/...
 *   - RS (Registre de Signature) : EID>SIG
 *     * EID : Identifiant de l'entité signataire (hash hexadécimal)
 *     * SIG : Signature cryptographique (hexadécimal)
 *   - Plusieurs signatures possibles, jusqu'à linkMaxRS
 *
 *
 * Structure des registres de liens :
 * ----------------------------------
 * Un registre de lien (RL) est une chaîne de caractères représentant une relation entre nœuds.
 * Format : REQ>NID1>NID2>NID3>...
 *
 * Le séparateur entre champs est le caractère ">".
 * Le nombre maximum de NID par registre est défini par l'option linkMaxUIDbyRL (défaut: 4).
 *
 * Exemples :
 * - Lien simple (l) : l>NID1>NID2>0
 * - Lien avec méta (l) : l>NID1>NID2>NID_meta
 * - Dérivé (f) : f>NID_source>NID_dérivé>NID_contexte
 * - Suppression (d) : d>NID_à_supprimer>0>0
 * - Lien dissimulé (c) : c>NID_destinataire>lien_chiffré>clé_chiffrée
 *
 *
 * Stockage dans le dossier l/ :
 * -----------------------------
 * Les blocs de liens sont stockés dans le système de fichiers sous le dossier "l/" (défini par
 * References::LINKS_FOLDER = 'l'). Chaque fichier correspond à un nœud source et contient
 * les blocs de liens où ce nœud apparaît.
 *
 * Organisation :
 * - Dossier principal : pub/l/
 * - Un fichier par NID source : pub/l/{NID}
 *   * Chaque ligne du fichier est un bloc de liens complet (format BH_BL_BS)
 *   * Plusieurs blocs peuvent exister pour un même nœud source
 * - Fichier historique : pub/l/f
 *   * Contient l'historique de tous les blocs de liens créés
 * - Fichiers de liens dissimulés : pub/l/{EID}-{SIGNER}
 *   * Contient les blocs de liens dissimulés destinés à une entité spécifique
 *   * Utilisé pour les liens de type 'c' (chiffrement/dissimulation)
 *
 * Les fonctions setBlockLink() et getBlockLinks() de ioInterface gèrent la lecture/écriture
 * dans ces fichiers. Un bloc de liens est écrit pour chaque nœud référencé dans le bloc.
 *
 * Exemple de stockage :
 *   pub/l/abc123... (fichier contenant les liens où abc123 est source)
 *   pub/l/def456... (fichier contenant les liens où def456 est source ou cible)
 *   pub/l/f          (fichier historique)
 *   pub/l/entity123-signer456 (fichier de liens dissimulés)
 *
 *
 * Sémantique des types de liens :
 * --------------------------------
 * - l (link) : Relation orientée entre deux objets, éventuellement pondérée par un méta
 * - f (fork) : Relation de dérivation/parenté entre objets
 * - u (update) : Mise à jour d'un objet par un autre
 * - d (delete) : Marque un objet comme à supprimer
 * - e (equivalent) : Définit des objets interchangeables (ex: traductions)
 * - c (concealed) : Lien dissimulé/chiffré pour anonymisation
 * - k (encrypted) : Désigne la version chiffrée d'un objet
 * - s (subdivision) : Fragment d'un objet (avec méta contenant l'intervalle x-y)
 * - x (delete link) : Suppression d'un lien existant
 *
 * Les limites de taille sont définies par :
 * - BlocLink::LINK_MAX_BL_SIZE : Taille max du bloc BL (16384 octets)
 * - BlocLink::LINK_MAX_BS_SIZE : Taille max du bloc BS (16384 octets)
 * - BlocLink::LINK_MAX_RL_SIZE : Taille max d'un registre RL (4096 octets)
 * - linkMaxRL : Nombre max de registres par bloc
 * - linkMaxUIDbyRL : Nombre max de NID par registre
 * - linkMaxRS : Nombre max de signatures par bloc
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
     * @param Entity|null $publicKey
     * @return boolean
     */
    public function sign(?Entity $publicKey = null): bool;

    /**
     * Write the link.
     *
     * @return boolean
     */
    public function write(): bool;

    /**
     * Sign and write the link.
     *
     * @param Entity|null $publicKey
     * @return boolean
     */
    public function signWrite(?Entity $publicKey = null): bool;
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
