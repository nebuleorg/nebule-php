<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * Ce module permet la traduction en français.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleTranslateFRFR extends Modules
{
    protected $MODULE_TYPE = 'Traduction';
    protected $MODULE_NAME = '::translateModule:fr-fr:ModuleName';
    protected $MODULE_MENU_NAME = '::translateModule:fr-fr:MenuName';
    protected $MODULE_COMMAND_NAME = 'fr-fr';
    protected $MODULE_DEFAULT_VIEW = '';
    protected $MODULE_DESCRIPTION = '::translateModule:fr-fr:ModuleDescription';
    protected $MODULE_VERSION = '020240720';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2024';
    protected $MODULE_LOGO = 'b55cb8774839a5a894cecf77ce5e47db7fc114c2bc92e3dfc77cb9b4a8f488ac.sha2.256';
    protected $MODULE_HELP = '::translateModule:fr-fr:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array();
    protected $MODULE_REGISTERED_ICONS = array('b55cb8774839a5a894cecf77ce5e47db7fc114c2bc92e3dfc77cb9b4a8f488ac.sha2.256');
    protected $MODULE_APP_TITLE_LIST = array();
    protected $MODULE_APP_ICON_LIST = array();
    protected $MODULE_APP_DESC_LIST = array();
    protected $MODULE_APP_VIEW_LIST = array();


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?Node $nid = null): array
    {
        return $this->getHookListTranslation($hookName);
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::translateModule:fr-fr:ModuleName' => 'Français (France)',
            '::translateModule:fr-fr:MenuName' => 'Français (France)',
            '::translateModule:fr-fr:ModuleDescription' => "Traduction de l'interface en Français.",
            '::translateModule:fr-fr:ModuleHelp' => "Ce module permet de mettre en place la traduction de l'interface de sylabe en Français.",
            '::::Bienvenue' => 'Bienvenue.',
            '::Password' => 'Mot de passe',
            '::yes' => 'Oui',
            '::no' => 'Non',
            '::::SecurityChecks' => 'Tests de sécurité',
            '::Lock' => 'Verrouiller',
            '::Unlock' => 'Déverrouiller',
            '::EntityLocked' => 'Entité verrouillée. Déverrouiller ?',
            '::EntityUnlocked' => 'Entité déverrouillée. Verrouiller ?',
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Message',
            '::::WARN' => 'ATTENTION !',
            '::::ERROR' => 'ERREUR !',
            '::::RESCUE' => 'Mode de sauvetage !',
            '::::icon:DEFAULT_ICON_LO' => 'Objet',
            '::::HtmlHeadDescription' => 'Page web cliente sylabe pour nebule.',
            '::::Experimental' => '[Experimental]',
            '::::Developpement' => '[En cours de développement]',
            '::::help' => 'Aide',
            'nebule/objet' => 'Objet',
            'nebule/objet/hash' => "Type d'empreinte",
            'nebule/objet/type' => 'Type MIME',
            'nebule/objet/taille' => 'Taille',
            'nebule/objet/nom' => 'Nom',
            'nebule/objet/prefix' => 'Préfix',
            'nebule/objet/prenom' => 'Prénom',
            'nebule/objet/suffix' => 'Suffix',
            'nebule/objet/surnom' => 'Surnom',
            'nebule/objet/postnom' => 'Surnom',
            'nebule/objet/entite' => 'Entité',
            'nebule/objet/entite/type' => 'Type',
            'nebule/objet/date' => 'Date',
            'nebule/objet/date/annee' => 'Année',
            'nebule/objet/date/mois' => 'Mois',
            'nebule/objet/date/jour' => 'Jour',
            'nebule/objet/date/heure' => 'Heure',
            'nebule/objet/date/minute' => 'Minute',
            'nebule/objet/date/seconde' => 'Seconde',
            'nebule/objet/date/zone' => 'Zone de temps',
            'nebule/objet/emotion/colere' => 'Contrariant',
            'nebule/objet/emotion/degout' => 'Dégôuté',
            'nebule/objet/emotion/surprise' => 'Étonnant',
            'nebule/objet/emotion/peur' => 'Inquiétant',
            'nebule/objet/emotion/interet' => 'Intéressé',
            'nebule/objet/emotion/joie' => "J'aime",
            'nebule/objet/emotion/confiance' => "J'approuve",
            'nebule/objet/emotion/tristesse' => 'Tristement',
            'nebule/objet/entite/localisation' => 'Localisation',
            'nebule/objet/entite/maitre/securite' => 'Maître de la sécurité',
            'nebule/objet/entite/maitre/code' => 'Maître du code',
            'nebule/objet/entite/maitre/annuaire' => "Maître de l'annuaire",
            'nebule/objet/entite/maitre/temps' => 'Maître du temps',
            nebule::REFERENCE_OBJECT_TEXT => 'Texte brute',
            'application/x-pem-file' => 'Entité',
            'image/jpeg' => 'Image JPEG',
            'image/png' => 'Image PNG',
            'application/x-bzip2' => 'Archive BZIP2',
            'text/html' => 'Page HTML',
            'application/x-php' => 'Code PHP',
            'text/x-php' => 'Code PHP',
            'text/css' => 'Feuille de style CSS',
            'audio/mpeg' => 'Audio MP3',
            'audio/x-vorbis+ogg' => 'Audio OGG',
            'application/x-encrypted/rsa' => 'Chiffré',
            'application/x-encrypted/aes-256-ctr' => 'Chiffré',
            'application/x-folder' => 'Dossier',
            '::::IDprivateKey' => 'ID privé',
            '::::IDpublicKey' => 'ID public',
            '::Version' => 'Version',
            '::UniqueID' => 'Identifiant universel : %s',
            '::GroupeFerme' => 'Groupe fermé',
            '::GroupeOuvert' => 'Groupe ouvert',
            '::ConversationFermee' => 'Conversation fermée',
            '::ConversationOuverte' => 'Conversation ouverte',
            '::progress' => 'Chargement en cours...',
            '::seeMore' => 'Voir plus...',
            '::noContent' => '(contenu indisponible)',
            '::appSwitch' => "Changer d'application",
            '::menu' => 'Menu',
            '::menuDesc' => 'Page du menu complet',
            '::EmptyList' => 'Liste vide.',
            '::ChangeLanguage' => 'Changer de langue',
            '::SelectUser' => 'Sélectionner un utilisateur',
            '::MarkAdd' => 'Marquer',
            '::MarkRemove' => 'Démarquer',
            '::MarkRemoveAll' => 'Démarquer tout',
            '::Synchronize' => 'Synchroniser',
            ':::display:content:errorBan' => 'Cet objet est banni, il ne peut pas être affiché !',
            ':::display:content:warningTaggedWarning' => 'Cet objet est marqué comme dangereux, attention à son contenu !',
            ':::display:content:ObjectProctected' => 'Cet objet est protégé !',
            ':::display:content:warningObjectProctected' => 'Cet objet est marqué comme protégé, attention à la divulgation de son contenu en public !!!',
            ':::display:content:OK' => 'Cet objet est valide, son contenu a été vérifié.',
            ':::display:content:warningTooBig' => "Cet objet est trop volumineux, son contenu n'a pas été vérifié !",
            ':::display:content:errorNotDisplayable' => 'Cet objet ne peut pas être affiché !',
            ':::display:content:errorNotAvailable' => "Cet objet n'est pas disponible, il ne peut pas être affiché !",
            ':::display:content:notAnObject' => "Cet objet de référence n'a pas de contenu.",
            ':::display:content:ObjectHaveUpdate' => 'Cet objet a été mis à jour vers :',
            ':::display:content:Activated' => 'Cet objet est activé.',
            ':::display:content:NotActivated' => 'Cet objet est désactivé.',
            ':::display:link:OK' => 'Ce lien est valide.',
            ':::display:link:errorInvalid' => "Ce lien n'est pas valide !",
            ':::warn_ServNotPermitWrite' => "Ce serveur n'autorise pas les modifications.",
            ':::warn_flushSessionAndCache' => "Toutes les données de connexion ont été effacées.",
            ':::err_NotPermit' => 'Non autorisé sur ce serveur !',
            ':::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            ':::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            ':::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            ':::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            ':::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            ':::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            ':::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            ':::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            ':::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            ':::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            ':::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            ':::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
            ':::info_OnlySignedLinks' => 'Uniquement des liens signés !',
            ':::info_OnlyLinksFromCodeMaster' => 'Uniquement les liens signés du maître du code !',
            ':::display:object:flag:protected' => 'Cet objet est protégé.',
            ':::display:object:flag:unprotected' => "Cet objet n'est pas protégé.",
            ':::display:object:flag:obfuscated' => 'Cet objet est dissimulé.',
            ':::display:object:flag:unobfuscated' => "Cet objet n'est pas dissimulé.",
            ':::display:object:flag:locked' => 'Cet entité est déverrouillée.',
            ':::display:object:flag:unlocked' => 'Cet entité est verrouillée.',
            ':::display:object:flag:activated' => 'Cet objet est activé.',
            ':::display:object:flag:unactivated' => "Cet objet n'est pas activé.",
        ],
        'en-en' => [
            '::translateModule:fr-fr:ModuleName' => 'French (France)',
            '::translateModule:fr-fr:MenuName' => 'French (France)',
            '::translateModule:fr-fr:ModuleDescription' => 'Interface translation in French.',
            '::translateModule:fr-fr:ModuleHelp' => 'This module permit to translate the sylabe interface in French.',
        ],
        'es-co' => [
            '::translateModule:fr-fr:ModuleName' => 'Francés (Francia)',
            '::translateModule:fr-fr:MenuName' => 'Francés (Francia)',
            '::translateModule:fr-fr:ModuleDescription' => 'Interface translation in French.',
            '::translateModule:fr-fr:ModuleHelp' => 'This module permit to translate the sylabe interface in French.',
        ],
    ];
}
