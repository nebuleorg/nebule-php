<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Library\nebule;
use Nebule\Library\References;

/**
 * Ce module permet la traduction en français FR-FR.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleTranslateFRFR extends \Nebule\Library\ModuleTranslates
{
    const MODULE_LANGUAGE = 'fr-fr';
    const MODULE_NAME = '::translateModule:fr-fr:ModuleName';
    const MODULE_MENU_NAME = '::translateModule:fr-fr:MenuName';
    const MODULE_DESCRIPTION = '::translateModule:fr-fr:ModuleDescription';
    const MODULE_VERSION = '020251228';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2025';
    const MODULE_LOGO = 'b55cb8774839a5a894cecf77ce5e47db7fc114c2bc92e3dfc77cb9b4a8f488ac.sha2.256';
    const MODULE_INTERFACE = '3.0';
    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::translateModule:fr-fr:ModuleName' => 'Français (France)',
            '::translateModule:fr-fr:MenuName' => 'Français (France)',
            '::translateModule:fr-fr:ModuleDescription' => "Traduction de l'interface en Français.",
            '::translateModule:fr-fr:ModuleHelp' => "Ce module permet de mettre en place la traduction de l'interface de sylabe en Français.",

            // Do not include Translates::TRANSLATE_TABLE['fr-fr']
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
            References::REFERENCE_OBJECT_TEXT => 'Texte brute',
            'application/x-pem-file' => 'Entité',
            'image/jpeg' => 'Image JPEG',
            'image/png' => 'Image PNG',
            'application/x-bzip2' => 'Archive BZIP2',
            'text/html' => 'Page HTML',
            'application/x-php' => 'Code PHP',
            'application/x-httpd-php' => 'Code PHP',
            'text/x-php' => 'Code PHP',
            'text/css' => 'Feuille de style CSS',
            'audio/mpeg' => 'Audio MP3',
            'audio/x-vorbis+ogg' => 'Audio OGG',
            'application/x-encrypted/rsa' => 'Chiffré',
            'application/x-encrypted/aes-256-ctr' => 'Chiffré',
            'application/x-folder' => 'Dossier',
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
            '::notImplemented' => 'Non encore implémenté...',
            '::notSupported' => 'Non supporté...',
            '::password' => 'Mot de passe',
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
