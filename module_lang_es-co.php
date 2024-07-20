<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * This module add translation in Español ES-CO.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleTranslateESCO extends Modules
{
    protected $MODULE_TYPE = 'Traduction';
    protected $MODULE_NAME = '::translateModule:es-co:ModuleName';
    protected $MODULE_MENU_NAME = '::translateModule:es-co:MenuName';
    protected $MODULE_COMMAND_NAME = 'es-co';
    protected $MODULE_DEFAULT_VIEW = '';
    protected $MODULE_DESCRIPTION = '::translateModule:es-co:ModuleDescription';
    protected $MODULE_VERSION = '020240720';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2024';
    protected $MODULE_LOGO = 'b55cb8774839a5a894cecf77ce5e47db7fc114c2bc92e3dfc77cb9b4a8f488ac.sha2.256';
    protected $MODULE_HELP = '::translateModule:es-co:ModuleHelp';
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
            '::::Bienvenue' => 'Bienvenido.',
            '::Password' => 'Contraseña',
            '::yes' => 'Si',
            '::no' => 'No',
            '::::SecurityChecks' => 'Controles de seguridad',
            '::Lock' => 'Locking',
            '::Unlock' => 'Unlocking',
            '::EntityLocked' => 'Entidad bloqueada. Desbloquear?',
            '::EntityUnlocked' => 'Entidad desbloqueada. Bloquear?',
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Mensaje',
            '::::WARN' => '¡ADVERTENCIA!',
            '::::ERROR' => '¡ERROR!',
            '::::RESCUE' => '¡Modo de rescate!',
            '::::icon:DEFAULT_ICON_LO' => 'Objeto',
            '::::HtmlHeadDescription' => 'Página web cliente sylabe para nebule.',
            '::::Experimental' => '[Experimental]',
            '::::Developpement' => '[Under developpement]',
            '::::help' => 'Ayuda',
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
            nebule::REFERENCE_OBJECT_TEXT => 'Texto en bruto',
            'application/x-pem-file' => 'Entidad',
            'image/jpeg' => 'JPEG gráfico',
            'image/png' => 'PNG gráfico',
            'application/x-bzip2' => 'Archivo BZIP2',
            'text/html' => 'Página HTML',
            'application/x-php' => 'Código PHP',
            'text/x-php' => 'Código PHP',
            'text/css' => 'Hojas de estilo en cascada CSS',
            'audio/mpeg' => 'Audio MP3',
            'audio/x-vorbis+ogg' => 'Audio OGG',
            'application/x-encrypted/rsa' => 'Encriptado',
            'application/x-encrypted/aes-256-ctr' => 'Encriptado',
            'application/x-folder' => 'Archivo',
            '::::IDprivateKey' => 'ID privé',
            '::::IDpublicKey' => 'ID public',
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
            ':::display:content:errorBan' => "This object is banned, it can't be displayed!",
            ':::display:content:warningTaggedWarning' => "This object is marked as dangerous, be carfull with it's content!",
            ':::display:content:ObjectProctected' => "This object is marked as protected!",
            ':::display:content:warningObjectProctected' => "This object is marked as protected, be careful when it's content is displayed in public!",
            ':::display:content:OK' => "This object is valid, it's content have been checked.",
            ':::display:content:warningTooBig' => "This object is too big, it's content have not been checked!",
            ':::display:content:errorNotDisplayable' => "This object can't be displayed!",
            ':::display:content:errorNotAvailable' => "This object is not available, it can't be displayed!",
            ':::display:content:notAnObject' => 'This reference object do not have content.',
            ':::display:content:ObjectHaveUpdate' => 'This object have been updated to:',
            ':::display:content:Activated' => 'This object is activated.',
            ':::display:content:NotActivated' => 'This object is not activated.',
            ':::display:link:OK' => 'This link is valid.',
            ':::display:link:errorInvalid' => 'This link is invalid!',
            ':::warn_ServNotPermitWrite' => 'This server do not permit modifications.',
            ':::warn_flushSessionAndCache' => 'All datas of this connexion have been flushed',
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
            ':::info_OnlySignedLinks' => 'Only signed links!',
            ':::info_OnlyLinksFromCodeMaster' => 'Only links signed by the code master!',
            ':::display:object:flag:protected' => 'Este objeto está protegido.',
            ':::display:object:flag:unprotected' => 'Este objeto no está protegido.',
            ':::display:object:flag:obfuscated' => 'Este objeto está oculto.',
            ':::display:object:flag:unobfuscated' => 'Este objeto no está oculto.',
            ':::display:object:flag:locked' => 'Esta entidad está desbloqueada.',
            ':::display:object:flag:unlocked' => 'Esta entidad está bloqueada.',
            ':::display:object:flag:activated' => 'Este objeto está activado.',
            ':::display:object:flag:unactivated' => 'Este objeto no está activado.',
        ],
    ];
}
