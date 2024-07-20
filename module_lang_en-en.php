<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * This module add translation in English EN-EN.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleTranslateENEN extends Modules
{
    protected $MODULE_TYPE = 'Traduction';
    protected $MODULE_NAME = '::translateModule:en-en:ModuleName';
    protected $MODULE_MENU_NAME = '::translateModule:en-en:MenuName';
    protected $MODULE_COMMAND_NAME = 'en-en';
    protected $MODULE_DEFAULT_VIEW = '';
    protected $MODULE_DESCRIPTION = '::translateModule:en-en:ModuleDescription';
    protected $MODULE_VERSION = '020240720';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2024';
    protected $MODULE_LOGO = 'b55cb8774839a5a894cecf77ce5e47db7fc114c2bc92e3dfc77cb9b4a8f488ac.sha2.256';
    protected $MODULE_HELP = '::translateModule:en-en:ModuleHelp';
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
            '::::Bienvenue' => 'Welcome.',
            '::Password' => 'Password',
            '::yes' => 'Yes',
            '::no' => 'No',
            '::::SecurityChecks' => 'Security checks',
            '::Lock' => 'Locking',
            '::Unlock' => 'Unlocking',
            '::EntityLocked' => 'Entity locked. Unlock?',
            '::EntityUnlocked' => 'Entity unlocked. Lock?',
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Message',
            '::::WARN' => 'WARNING!',
            '::::ERROR' => 'ERROR!',
            '::::RESCUE' => 'Rescue mode!',
            '::::icon:DEFAULT_ICON_LO' => 'Object',
            '::::HtmlHeadDescription' => 'Client web page sylabe for nebule.',
            '::::Experimental' => '[Experimental]',
            '::::Developpement' => '[Under developpement]',
            '::::help' => 'Help',
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
            nebule::REFERENCE_OBJECT_TEXT => 'RAW text',
            'application/x-pem-file' => 'Entity',
            'image/jpeg' => 'JPEG picture',
            'image/png' => 'PNG picture',
            'application/x-bzip2' => 'Archive BZIP2',
            'text/html' => 'HTML page',
            'application/x-php' => 'PHP code',
            'text/x-php' => 'PHP code',
            'text/css' => 'Cascading Style Sheet CSS',
            'audio/mpeg' => 'Audio MP3',
            'audio/x-vorbis+ogg' => 'Audio OGG',
            'application/x-encrypted/rsa' => 'Encrypted',
            'application/x-encrypted/aes-256-ctr' => 'Encrypted',
            'application/x-folder' => 'Folder',
            '::::IDprivateKey' => 'Private ID',
            '::::IDpublicKey' => 'Public ID',
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
            ':::warn_flushSessionAndCache' => 'All datas of this connexion have been flushed.',
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
            ':::display:object:flag:protected' => 'This object is protected.',
            ':::display:object:flag:unprotected' => 'This object is not protected.',
            ':::display:object:flag:obfuscated' => 'This object is obfuscated.',
            ':::display:object:flag:unobfuscated' => 'This object is not obfuscated.',
            ':::display:object:flag:locked' => 'This entity is unlocked.',
            ':::display:object:flag:unlocked' => 'This entity is locked.',
            ':::display:object:flag:activated' => 'This object is activated.',
            ':::display:object:flag:unactivated' => 'This object is not activated.',
        ],
        'es-co' => [
            '::translateModule:en-en:ModuleName' => 'Inglés (Inglaterra)',
            '::translateModule:en-en:MenuName' => 'Inglés (Inglaterra)',
            '::translateModule:en-en:ModuleDescription' => 'Interface translation in English.',
            '::translateModule:en-en:ModuleHelp' => 'This module permit to translate the sylabe interface in English.',
        ],
    ];
}
