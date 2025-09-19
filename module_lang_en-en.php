<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Library\nebule;
use Nebule\Library\References;

/**
 * This module add translation in English EN-EN.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleTranslateENEN extends \Nebule\Library\ModuleTranslates
{
    const MODULE_LANGUAGE = 'en-en';
    const MODULE_NAME = '::translateModule:en-en:ModuleName';
    const MODULE_MENU_NAME = '::translateModule:en-en:MenuName';
    const MODULE_DESCRIPTION = '::translateModule:en-en:ModuleDescription';
    const MODULE_VERSION = '020250919';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2025';
    const MODULE_LOGO = 'b55cb8774839a5a894cecf77ce5e47db7fc114c2bc92e3dfc77cb9b4a8f488ac.sha2.256';
    const MODULE_INTERFACE = '3.0';
    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::translateModule:en-en:ModuleName' => 'Anglais (Angleterre)',
            '::translateModule:en-en:MenuName' => 'Anglais (Angleterre)',
            '::translateModule:en-en:ModuleDescription' => "Traduction de l'interface en Anglais.",
            '::translateModule:en-en:ModuleHelp' => "Ce module permet de mettre en place la traduction de l'interface de sylabe et des applications en Anglais.",
        ],
        'en-en' => [
            '::translateModule:en-en:ModuleName' => 'English (England)',
            '::translateModule:en-en:MenuName' => 'English (England)',
            '::translateModule:en-en:ModuleDescription' => 'Interface translation in English.',
            '::translateModule:en-en:ModuleHelp' => 'This module permit to translate the sylabe interface in English.',

            // Do not include Translates::TRANSLATE_TABLE['en-en']
            'nebule/objet' => 'Object',
            'nebule/objet/hash' => 'Hash type',
            'nebule/objet/type' => 'MIME type',
            'nebule/objet/taille' => 'Size',
            'nebule/objet/nom' => 'Name',
            'nebule/objet/prefix' => 'Prefix',
            'nebule/objet/prenom' => 'First name',
            'nebule/objet/suffix' => 'Suffix',
            'nebule/objet/surnom' => 'Nikename',
            'nebule/objet/postnom' => 'Nikename',
            'nebule/objet/entite' => 'Entity',
            'nebule/objet/entite/type' => 'Type',
            'nebule/objet/date' => 'Date',
            'nebule/objet/date/annee' => 'Year',
            'nebule/objet/date/mois' => 'Month',
            'nebule/objet/date/jour' => 'Day',
            'nebule/objet/date/heure' => 'Hour',
            'nebule/objet/date/minute' => 'Minute',
            'nebule/objet/date/seconde' => 'Second',
            'nebule/objet/date/zone' => 'Zone de temps',
            'nebule/objet/emotion/colere' => 'Annoying',
            'nebule/objet/emotion/degout' => 'Bummed',
            'nebule/objet/emotion/surprise' => 'Surprised',
            'nebule/objet/emotion/peur' => 'disturbed',
            'nebule/objet/emotion/interet' => 'Interested',
            'nebule/objet/emotion/joie' => 'I like',
            'nebule/objet/emotion/confiance' => 'Agree',
            'nebule/objet/emotion/tristesse' => 'Sadly',
            'nebule/objet/entite/localisation' => 'Localisation',
            'nebule/objet/entite/maitre/securite' => 'Master of security',
            'nebule/objet/entite/maitre/code' => 'Master of code',
            'nebule/objet/entite/maitre/annuaire' => 'Master of directory',
            'nebule/objet/entite/maitre/temps' => 'Master of time',
            References::REFERENCE_OBJECT_TEXT => 'RAW text',
            'application/x-pem-file' => 'Entity',
            'image/jpeg' => 'JPEG picture',
            'image/png' => 'PNG picture',
            'application/x-bzip2' => 'Archive BZIP2',
            'text/html' => 'HTML page',
            'application/x-php' => 'PHP code',
            'application/x-httpd-php' => 'PHP code',
            'text/x-php' => 'PHP code',
            'text/css' => 'Cascading Style Sheet CSS',
            'audio/mpeg' => 'Audio MP3',
            'audio/x-vorbis+ogg' => 'Audio OGG',
            'application/x-encrypted/rsa' => 'Encrypted',
            'application/x-encrypted/aes-256-ctr' => 'Encrypted',
            'application/x-folder' => 'Folder',
            '::Version' => 'Version',
            '::UniqueID' => 'Universal identifier : %s',
            '::GroupeFerme' => 'Closed group',
            '::GroupeOuvert' => 'Opened group',
            '::ConversationFermee' => 'Closed conversation',
            '::ConversationOuverte' => 'Opened conversation',
            '::progress' => 'In progress...',
            '::seeMore' => 'See more...',
            '::noContent' => '(content not available)',
            '::appSwitch' => 'Switch application',
            '::menu' => 'Menu',
            '::menuDesc' => 'Page with full menu',
            '::EmptyList' => 'Empty list.',
            '::ChangeLanguage' => 'Change language',
            '::SelectUser' => 'Select user',
            '::MarkAdd' => 'Mark',
            '::MarkRemove' => 'Unmark',
            '::MarkRemoveAll' => 'Unmark all',
            '::Synchronize' => 'Synchronize',
            '::notImplemented' => 'Not yet implemented...',
            '::notSupported' => 'Not supported...',
        ],
        'es-co' => [
            '::translateModule:en-en:ModuleName' => 'Inglés (Inglaterra)',
            '::translateModule:en-en:MenuName' => 'Inglés (Inglaterra)',
            '::translateModule:en-en:ModuleDescription' => 'Interface translation in English.',
            '::translateModule:en-en:ModuleHelp' => 'This module permit to translate the sylabe interface in English.',
        ],
    ];
}
