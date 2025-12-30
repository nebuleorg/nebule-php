<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Library\nebule;
use Nebule\Library\References;
use Nebule\Library\Metrology;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Actions;
use Nebule\Library\Translates;
use Nebule\Library\ModuleInterface;
use Nebule\Library\Modules;
use Nebule\Library\ModelModuleHelp;
use Nebule\Library\ModuleTranslates;

/**
 * This module adds translation in Spanish ES-CO.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleTranslateESCO extends ModuleTranslates
{
    const MODULE_LANGUAGE = 'es-co';
    const MODULE_NAME = '::translateModule:es-co:ModuleName';
    const MODULE_MENU_NAME = '::translateModule:es-co:MenuName';
    const MODULE_DESCRIPTION = '::translateModule:es-co:ModuleDescription';
    const MODULE_VERSION = '020251230';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2025';
    const MODULE_LOGO = '7425a5a9dfdaaa084fba0dff69b3a6267a90ef42cb0fa093d5a4b47a8bc062dd.sha2.256';
    const MODULE_INTERFACE = '3.0';
    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::translateModule:es-co:ModuleName' => 'Espagnol (Colombie)',
            '::translateModule:es-co:MenuName' => 'Espagnol (Colombie)',
            '::translateModule:es-co:ModuleDescription' => "Traduction de l'interface en Espagnol.",
            '::translateModule:es-co:ModuleHelp' => "Ce module permet de mettre en place la traduction de l'interface de sylabe et des applications en Espagnol.",
        ],
        'en-en' => [
            '::translateModule:es-co:ModuleName' => 'Spanish (Colombia)',
            '::translateModule:es-co:MenuName' => 'Spanish (Colombia)',
            '::translateModule:es-co:ModuleDescription' => 'Interface translation in Spanish.',
            '::translateModule:es-co:ModuleHelp' => 'This module permit to translate the sylabe interface in Spanish.',
        ],
        'es-co' => [
            '::translateModule:es-co:ModuleName' => 'Español (Colombia)',
            '::translateModule:es-co:MenuName' => 'Español (Colombia)',
            '::translateModule:es-co:ModuleDescription' => 'Interface translation in Spanish.',
            '::translateModule:es-co:ModuleHelp' => 'This module permit to translate the sylabe interface in Spanish.',

            // Do not include Translates::TRANSLATE_TABLE['es-co']
            'nebule/objet' => 'Objeto',
            'nebule/objet/hash' => 'Typo huella',
            'nebule/objet/type' => 'Tipo MIME',
            'nebule/objet/Taille' => 'Tamaño',
            'nebule/objet/nom' => 'Nombre',
            'nebule/objet/prefix' => 'Prefijo',
            'nebule/objet/prenom' => 'Primer nombre',
            'nebule/objet/suffix' => 'Sufijo',
            'nebule/objet/surnom' => 'Apodo',
            'nebule/objet/postnom' => 'Apodo',
            'nebule/objet/entite' => 'Entidad',
            'nebule/objet/entite/type' => 'Tipo',
            'nebule/objet/date' => 'Dato',
            'nebule/objet/date/annee' => 'Año',
            'nebule/objet/date/mois' => 'Mes',
            'nebule/objet/date/jour' => 'Dia',
            'nebule/objet/date/heure' => 'Hora',
            'nebule/objet/date/minute' => 'Minuto',
            'nebule/objet/date/seconde' => 'Segundo',
            'nebule/objet/date/zone' => 'Zona horaria',
            'nebule/objet/emotion/colere' => 'Molesto',
            'nebule/objet/emotion/degout' => 'Disgustado',
            'nebule/objet/emotion/surprise' => 'Sorprendente',
            'nebule/objet/emotion/peur' => 'Inquietante',
            'nebule/objet/emotion/interet' => 'Interesadas',
            'nebule/objet/emotion/joie' => 'Me gusta',
            'nebule/objet/emotion/confiance' => 'Apruebo',
            'nebule/objet/emotion/tristesse' => 'Tristemente',
            'nebule/objet/entite/localisation' => 'Localisation',
            'nebule/objet/entite/maitre/securite' => 'Maestro de seguridad',
            'nebule/objet/entite/maitre/code' => 'Maestro de codigo',
            'nebule/objet/entite/maitre/annuaire' => 'Maestro de directorio',
            'nebule/objet/entite/maitre/temps' => 'Maestro de tiempo',
            References::REFERENCE_OBJECT_TEXT => 'Texto en bruto',
            'application/x-pem-file' => 'Entidad',
            'image/jpeg' => 'JPEG gráfico',
            'image/png' => 'PNG gráfico',
            'application/x-bzip2' => 'Archivo BZIP2',
            'text/html' => 'Página HTML',
            'application/x-php' => 'Código PHP',
            'application/x-httpd-php' => 'Código PHP',
            'text/x-php' => 'Código PHP',
            'text/css' => 'Hojas de estilo en cascada CSS',
            'audio/mpeg' => 'Audio MP3',
            'audio/x-vorbis+ogg' => 'Audio OGG',
            'application/x-encrypted/rsa' => 'Encriptado',
            'application/x-encrypted/aes-256-ctr' => 'Encriptado',
            'application/x-folder' => 'Archivo',
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
            '::ChangeLanguage' => 'Cambiar el idioma',
            '::SelectUser' => 'Seleccionar un usuario',
            '::MarkAdd' => 'Mark',
            '::MarkRemove' => 'Unmark',
            '::MarkRemoveAll' => 'Unmark all',
            '::Synchronize' => 'Synchronize',
            '::notImplemented' => 'Not yet implemented...',
            '::notSupported' => 'Not supported...',
            '::password' => 'Password',
        ],
    ];
}
