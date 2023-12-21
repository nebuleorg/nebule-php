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
    protected $MODULE_VERSION = '020231221';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2023';
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


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::translateModule:en-en:ModuleName']='Anglais (Angleterre)';
        $this->_table['en-en']['::translateModule:en-en:ModuleName']='English (England)';
        $this->_table['es-co']['::translateModule:en-en:ModuleName']='Inglés (Inglaterra)';
        $this->_table['fr-fr']['::translateModule:en-en:MenuName']='Anglais (Angleterre)';
        $this->_table['en-en']['::translateModule:en-en:MenuName']='English (England)';
        $this->_table['es-co']['::translateModule:en-en:MenuName']='Inglés (Inglaterra)';
        $this->_table['fr-fr']['::translateModule:en-en:ModuleDescription']="Traduction de l'interface en Anglais.";
        $this->_table['en-en']['::translateModule:en-en:ModuleDescription']='Interface translation in English.';
        $this->_table['es-co']['::translateModule:en-en:ModuleDescription']='Interface translation in English.';
        $this->_table['fr-fr']['::translateModule:en-en:ModuleHelp']="Ce module permet de mettre en place la traduction de l'interface de sylabe et des applications en Anglais.";
        $this->_table['en-en']['::translateModule:en-en:ModuleHelp']='This module permit to translate the sylabe interface in English.';
        $this->_table['es-co']['::translateModule:en-en:ModuleHelp']='This module permit to translate the sylabe interface in English.';

        // Salutations.
        $this->_table['en-en']['::::Bienvenue'] = 'Welcome.';

        // Traduction de mots.
        $this->_table['en-en']['::Password']='Password';
        $this->_table['en-en']['::yes']='Yes';
        $this->_table['en-en']['::no']='No';
        $this->_table['en-en']['::::SecurityChecks']='Security checks';
        $this->_table['en-en']['::Lock']='Locking';
        $this->_table['en-en']['::Unlock']='Unlocking';
        $this->_table['en-en']['::EntityLocked']='Entity locked. Unlock?';
        $this->_table['en-en']['::EntityUnlocked']='Entity unlocked. Lock?';
        $this->_table['en-en']['::::INFO']='Information';
        $this->_table['en-en']['::::OK']='OK';
        $this->_table['en-en']['::::INFORMATION']='Message';
        $this->_table['en-en']['::::WARN']='WARNING!';
        $this->_table['en-en']['::::ERROR']='ERROR!';
        $this->_table['en-en']['::::RESCUE']='Rescue mode!';
        $this->_table['en-en']['::::icon:DEFAULT_ICON_LO']='Object';
        $this->_table['en-en']['::::HtmlHeadDescription']='Client web page sylabe for nebule.';
        $this->_table['en-en']['::::Experimental']='[Experimental]';
        $this->_table['en-en']['::::Developpement']='[Under developpement]';
        $this->_table['en-en']['::::help']='Help';
        $this->_table['en-en']['nebule/objet']='Object';
        $this->_table['en-en']['nebule/objet/hash']='Hash type';
        $this->_table['en-en']['nebule/objet/type']='MIME type';
        $this->_table['en-en']['nebule/objet/taille']='Size';
        $this->_table['en-en']['nebule/objet/nom']='Name';
        $this->_table['en-en']['nebule/objet/prefix']='Prefix';
        $this->_table['en-en']['nebule/objet/prenom']='First name';
        $this->_table['en-en']['nebule/objet/suffix']='Suffix';
        $this->_table['en-en']['nebule/objet/surnom']='Nikename';
        $this->_table['en-en']['nebule/objet/postnom']='Nikename';
        $this->_table['en-en']['nebule/objet/entite']='Entity';
        $this->_table['en-en']['nebule/objet/entite/type']='Type';
        $this->_table['en-en']['nebule/objet/date']='Date';
        $this->_table['en-en']['nebule/objet/date/annee']='Year';
        $this->_table['en-en']['nebule/objet/date/mois']='Month';
        $this->_table['en-en']['nebule/objet/date/jour']='Day';
        $this->_table['en-en']['nebule/objet/date/heure']='Hour';
        $this->_table['en-en']['nebule/objet/date/minute']='Minute';
        $this->_table['en-en']['nebule/objet/date/seconde']='Second';
        $this->_table['en-en']['nebule/objet/date/zone']='Zone de temps';
        $this->_table['en-en']['nebule/objet/emotion/colere']='Annoying';
        $this->_table['en-en']['nebule/objet/emotion/degout']='Bummed';
        $this->_table['en-en']['nebule/objet/emotion/surprise']='Surprised';
        $this->_table['en-en']['nebule/objet/emotion/peur']='disturbed';
        $this->_table['en-en']['nebule/objet/emotion/interet']='Interested';
        $this->_table['en-en']['nebule/objet/emotion/joie']='I like';
        $this->_table['en-en']['nebule/objet/emotion/confiance']='Agree';
        $this->_table['en-en']['nebule/objet/emotion/tristesse']='Sadly';
        $this->_table['en-en']['nebule/objet/entite/localisation']='Localisation';
        $this->_table['en-en']['nebule/objet/entite/maitre/securite']='Master of security';
        $this->_table['en-en']['nebule/objet/entite/maitre/code']='Master of code';
        $this->_table['en-en']['nebule/objet/entite/maitre/annuaire']='Master of directory';
        $this->_table['en-en']['nebule/objet/entite/maitre/temps']='Master of time';

        // Type mime
        $this->_table['en-en'][nebule::REFERENCE_OBJECT_TEXT]='RAW text';
        $this->_table['en-en']['application/x-pem-file']='Entity';
        $this->_table['en-en']['image/jpeg']='JPEG picture';
        $this->_table['en-en']['image/png']='PNG picture';
        $this->_table['en-en']['application/x-bzip2']='Archive BZIP2';
        $this->_table['en-en']['text/html']='HTML page';
        $this->_table['en-en']['application/x-php']='PHP code';
        $this->_table['en-en']['text/x-php']='PHP code';
        $this->_table['en-en']['text/css']='Cascading Style Sheet CSS';
        $this->_table['en-en']['audio/mpeg']='Audio MP3';
        $this->_table['en-en']['audio/x-vorbis+ogg']='Audio OGG';
        $this->_table['en-en']['application/x-encrypted/rsa']='Encrypted';
        $this->_table['en-en']['application/x-encrypted/aes-256-ctr']='Encrypted';
        $this->_table['en-en']['application/x-folder']='Folder';

        // Espressions courtes.
        $this->_table['en-en']['::::IDprivateKey']='Private ID';
        $this->_table['en-en']['::::IDpublicKey']='Public ID';
        $this->_table['en-en']['::Version']='Version';
        $this->_table['en-en']['::UniqueID']='Universal identifier : %s';
        $this->_table['en-en']['::GroupeFerme']='Closed group';
        $this->_table['en-en']['::GroupeOuvert']='Opened group';
        $this->_table['en-en']['::ConversationFermee']='Closed conversation';
        $this->_table['en-en']['::ConversationOuverte']='Opened conversation';
        $this->_table['en-en']['::progress']='In progress...';
        $this->_table['en-en']['::seeMore']='See more...';
        $this->_table['en-en']['::noContent']='(content not available)';
        $this->_table['en-en']['::appSwitch']='Switch application';
        $this->_table['en-en']['::menu']='Menu';
        $this->_table['en-en']['::menuDesc']='Page with full menu';
        $this->_table['en-en']['::EmptyList']='Empty list.';
        $this->_table['en-en']['::ChangeLanguage']='Change language';
        $this->_table['en-en']['::SelectUser']='Select user';
        $this->_table['en-en']['::MarkAdd']='Mark';
        $this->_table['en-en']['::MarkRemove']='Unmark';
        $this->_table['en-en']['::MarkRemoveAll']='Unmark all';
        $this->_table['en-en']['::Synchronize']='Synchronize';

        // Phrases complètes.
        $this->_table['en-en'][':::display:content:errorBan']="This object is banned, it can't be displayed!";
        $this->_table['en-en'][':::display:content:warningTaggedWarning']="This object is marked as dangerous, be carfull with it's content!";
        $this->_table['en-en'][':::display:content:ObjectProctected']="This object is marked as protected!";
        $this->_table['en-en'][':::display:content:warningObjectProctected']="This object is marked as protected, be careful when it's content is displayed in public!";
        $this->_table['en-en'][':::display:content:OK']="This object is valid, it's content have been checked.";
        $this->_table['en-en'][':::display:content:warningTooBig']="This object is too big, it's content have not been checked!";
        $this->_table['en-en'][':::display:content:errorNotDisplayable']="This object can't be displayed!";
        $this->_table['en-en'][':::display:content:errorNotAvailable']="This object is not available, it can't be displayed!";
        $this->_table['en-en'][':::display:content:notAnObject']='This reference object do not have content.';
        $this->_table['en-en'][':::display:content:ObjectHaveUpdate']='This object have been updated to:';
        $this->_table['en-en'][':::display:content:Activated']='This object is activated.';
        $this->_table['en-en'][':::display:content:NotActivated']='This object is not activated.';
        $this->_table['en-en'][':::display:link:OK']='This link is valid.';
        $this->_table['en-en'][':::display:link:errorInvalid']='This link is invalid!';
        $this->_table['en-en'][':::warn_ServNotPermitWrite'] = 'This server do not permit modifications.';
        $this->_table['en-en'][':::warn_flushSessionAndCache'] = 'All datas of this connexion have been flushed.';
        $this->_table['en-en'][':::err_NotPermit']='Non autorisé sur ce serveur !';
        $this->_table['en-en'][':::act_chk_errCryptHash']="La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !";
        $this->_table['en-en'][':::act_chk_warnCryptHashkey']="La taille de l'empreinte cryptographique est trop petite !";
        $this->_table['en-en'][':::act_chk_errCryptHashkey']="La taille de l'empreinte cryptographique est invalide !";
        $this->_table['en-en'][':::act_chk_errCryptSym']="La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !";
        $this->_table['en-en'][':::act_chk_warnCryptSymkey']="La taille de clé de chiffrement cryptographique symétrique est trop petite !";
        $this->_table['en-en'][':::act_chk_errCryptSymkey']="La taille de clé de chiffrement cryptographique symétrique est invalide !";
        $this->_table['en-en'][':::act_chk_errCryptAsym']="La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !";
        $this->_table['en-en'][':::act_chk_warnCryptAsymkey']="La taille de clé de chiffrement cryptographique asymétrique est trop petite !";
        $this->_table['en-en'][':::act_chk_errCryptAsymkey']="La taille de clé de chiffrement cryptographique asymétrique est invalide !";
        $this->_table['en-en'][':::act_chk_errBootstrap']="L'empreinte cryptographique du bootstrap est invalide !";
        $this->_table['en-en'][':::act_chk_warnSigns']='La vérification des signatures de liens est désactivée !';
        $this->_table['en-en'][':::act_chk_errSigns']='La vérification des signatures de liens ne fonctionne pas !';

        $this->_table['en-en'][':::display:object:flag:protected'] = 'This object is protected.';
        $this->_table['en-en'][':::display:object:flag:unprotected'] = 'This object is not protected.';
        $this->_table['en-en'][':::display:object:flag:obfuscated'] = 'This object is obfuscated.';
        $this->_table['en-en'][':::display:object:flag:unobfuscated'] = 'This object is not obfuscated.';
        $this->_table['en-en'][':::display:object:flag:locked'] = 'This entity is unlocked.';
        $this->_table['en-en'][':::display:object:flag:unlocked'] = 'This entity is locked.';
        $this->_table['en-en'][':::display:object:flag:activated'] = 'This object is activated.';
        $this->_table['en-en'][':::display:object:flag:unactivated'] = 'This object is not activated.';

        /*
        $this->_table['en-en']['Lien']='Link';
        $this->_table['en-en']['-indéfini-']='-undefined-';
        $this->_table['en-en']['-indéterminé-']='-undetermined-';
        $this->_table['en-en']['Affichage']='Display';
        $this->_table['en-en']['Aide']='Help';
        $this->_table['en-en']['Algorithme']='Algorithm';
        $this->_table['en-en']['Ambiguë']='Ambiguous';
        $this->_table['en-en']['Ambigue']='Ambiguous';
        $this->_table['en-en']['Année']='Year';
        $this->_table['en-en']['Attention !']='Warning!';
        $this->_table['en-en']['Aucun']='None';
        $this->_table['en-en']['Aucune']='None';
        $this->_table['en-en']['Bannissement']='Banishment';
        $this->_table['en-en']['nebule/avis/beau']='Beautiful';
        $this->_table['en-en']['beau']='beautiful';
        $this->_table['en-en']['bits']='bits';
        $this->_table['en-en']['nebule/avis/bon']='Good';
        $this->_table['en-en']['Bon']='Good';
        $this->_table['en-en']['Bootstrap']='Bootstrap';
        $this->_table['en-en']['Caractéristiques']='Specifications';
        $this->_table['en-en']['Charger']='Load';
        $this->_table['en-en']['Chiffré']='Encrypted';
        $this->_table['en-en']['Chiffrement']='Encryption';
        $this->_table['en-en']['nebule/avis/clair']='Clear';
        $this->_table['en-en']['Clair']='Clear';
        $this->_table['en-en']['Commenter']='Comment on';
        $this->_table['en-en']['nebule/avis/complet']='Complete';
        $this->_table['en-en']['Complet']='Complete';
        $this->_table['en-en']['Contrariant']='Annoying';
        $this->_table['en-en']['Cryptographie']='Cryptography';
        $this->_table['en-en']['Date']='Date';
        $this->_table['en-en']['Déchiffrement']='Decrytion';
        $this->_table['en-en']['nebule/avis/ambigue']='Ambiguous';
        $this->_table['en-en']['Dégôuté']='Bummed';
        $this->_table['en-en']['Déprotéger']='Unprotect';
        $this->_table['en-en']['Description']='Description';
        $this->_table['en-en']['Déverrouillage']='Unlocking';
        $this->_table['en-en']['Déverrouiller']='Unlock';
        $this->_table['en-en']["D'accord"]='Agree';
        $this->_table['en-en']['Émotion']='Emotion';
        $this->_table['en-en']['Emotion']='Emotion';
        $this->_table['en-en']['Empreinte']='Footprint';
        $this->_table['en-en']['Entité']='Entity';
        $this->_table['en-en']['Entités']='Entities';
        $this->_table['en-en']['ERREUR !']='ERROR!';
        $this->_table['en-en']['ERROR']='ERROR';
        $this->_table['en-en']['Étonnant']='Surprised';
        $this->_table['en-en']['Etonnant']='Surprised';
        $this->_table['en-en']['Expérimental']='Experimental';
        $this->_table['en-en']['nebule/avis/faux']='False';
        $this->_table['en-en']['Faux']='False';
        $this->_table['en-en']['nebule/avis/génial']='Great';
        $this->_table['en-en']['Génial']='Great';
        $this->_table['en-en']['Genre']='Gender';
        $this->_table['en-en']['Heure']='Hour';
        $this->_table['en-en']['humain']='human';
        $this->_table['en-en']['Identifiant']='Identifier';
        $this->_table['en-en']['nebule/avis/important']='Important';
        $this->_table['en-en']['Important']='Important';
        $this->_table['en-en']['Inconnu']='Unknown';
        $this->_table['en-en']['nebule/avis/incomplet']='Incomplete';
        $this->_table['en-en']['Incomplet']='Incomplete';
        $this->_table['en-en']['nebule/avis/incomprehensible']='Unintelligible';
        $this->_table['en-en']['Incompréhensible']='Unintelligible';
        $this->_table['en-en']['Inquiétant']='Disturbed';
        $this->_table['en-en']['Intéressé']='Interested';
        $this->_table['en-en']['nebule/avis/inutile']='Useless';
        $this->_table['en-en']['Inutile']='Useless';
        $this->_table['en-en']['Invalide']='Invalid';
        $this->_table['en-en']["J'aime"]='I like';
        $this->_table['en-en']["J'approuve"]='Agree';
        $this->_table['en-en']['Jour']='Day';
        $this->_table['en-en']['Lien']='Link';
        $this->_table['en-en']['Liens']='Links';
        $this->_table['en-en']["L'objet"]='The object';
        $this->_table['en-en']['nebule/avis/mauvais']='Bad';
        $this->_table['en-en']['Mauvais']='Bad';
        $this->_table['en-en']['Minute']='Minute';
        $this->_table['en-en']['nebule/avis/moche']='Ugly';
        $this->_table['en-en']['Moche']='Ugly';
        $this->_table['en-en']['Mois']='Month';
        $this->_table['en-en']['nebule/avis/moyen']='Middling';
        $this->_table['en-en']['Moyen']='Middling';
        $this->_table['en-en']['Navigation']='Navigation';
        $this->_table['en-en']['Nœud']='Node';
        $this->_table['en-en']['Noeud']='Node';
        $this->_table['en-en']['Nœuds']='Nodes';
        $this->_table['en-en']['Noeuds']='Nodes';
        $this->_table['en-en']['NOK']='NOK';
        $this->_table['en-en']['nebule/avis/nul']='Zero';
        $this->_table['en-en']['Nul']='Zero';
        $this->_table['en-en']['Objet']='Object';
        $this->_table['en-en']['Objets']='Objects';
        $this->_table['en-en']['octet']='byte';
        $this->_table['en-en']['octets']='bytes';
        $this->_table['en-en']['OK']='OK';
        $this->_table['en-en']['nebule/avis/perime']='Obsolete';
        $this->_table['en-en']['Périmé']='Obsolete';
        $this->_table['en-en']['privée']='private';
        $this->_table['en-en']['Protection']='Protection';
        $this->_table['en-en']['Protéger']='Protect';
        $this->_table['en-en']['publique']='public';
        $this->_table['en-en']['Rafraîchir']='Refresh';
        $this->_table['en-en']['Recherche']='Search';
        $this->_table['en-en']['Rechercher']='Searching';
        $this->_table['en-en']['Régénération']='Regeneration';
        $this->_table['en-en']['Répéter']='Repeat';
        $this->_table['en-en']['robot']='robot';
        $this->_table['en-en']['Seconde']='Second';
        $this->_table['en-en']['Source']='Source';
        $this->_table['en-en']['Synchroniser']='Synchronize';
        $this->_table['en-en']['Suppression']='Suppression';
        $this->_table['en-en']['Supprimer']='Suppress';
        $this->_table['en-en']['Taille']='Size';
        $this->_table['en-en']['Téléchargement']='Downloading';
        $this->_table['en-en']['Télécharger']='Download';
        $this->_table['en-en']['Transfert']='Transfer';
        $this->_table['en-en']['Transmettre']='Transmit';
        $this->_table['en-en']['Tristement']='Sadly';
        $this->_table['en-en']['Valeur']='Value';
        $this->_table['en-en']['Validité']='Validity';
        $this->_table['en-en']['Version']='Version';
        $this->_table['en-en']['Verrouillage']='Locking';
        $this->_table['en-en']['Verrouiller']='Lock on';
        $this->_table['en-en']['Voir']='See';
        $this->_table['en-en']['nebule/avis/vrai']='True';
        $this->_table['en-en']['Vrai']='True';
        */

        /*
        $this->_table['en-en']['Creation nouveau lien']='Creating a new link';
        $this->_table['en-en']['::::GenNewEnt']='Generate new entity';
        $this->_table['en-en']['%01.0f liens lus,']='%01.0f links readed,';
        $this->_table['en-en']['%01.0f liens vérifiés,']='%01.0f links checked,';
        $this->_table['en-en']['%01.0f objets vérifiés.']='%01.0f objects checked.';
        $this->_table['en-en']['Accès au bootstrap.']='Access to the bootstrap.';
        $this->_table['en-en']["Afficher l'objet"]='View object';
        $this->_table['en-en']['Aide en ligne']='Online help';
        $this->_table['en-en']['::::AddNotice2Obj']='Add a comment on this object';
        $this->_table['en-en']['Ajout du nouveau lien non autorisé.']='Adding new link is not allowed.';
        $this->_table['en-en']['::::HashAlgo']='Fingerprinting algorithm';
        $this->_table['en-en']['::::SymCryptAlgo']='Symmetric encryption algorithm';
        $this->_table['en-en']['::::AsymCryptAlgo']='Asymmetric encryption algorithm';
        $this->_table['en-en']['Annuler bannissement']='Cancel ban';
        $this->_table['en-en']['Archive BZIP2']='Archive BZIP2';
        $this->_table['en-en']['Aucun objet à afficher.']='No object to display.';
        $this->_table['en-en']['Aucun objet dérivé à afficher.']='No derived object to display.';
        $this->_table['en-en']['Audio MP3']='Audio MP3';
        $this->_table['en-en']['Audio OGG']='Audio OGG';
        $this->_table['en-en']['::::Switch2Ent']='Switch to this entity';
        $this->_table['en-en']['::::LoadObj2Browser']='Directly load the object code in the browser.';
        $this->_table['en-en']['Chiffré, non affichable.']='Encrypted, can not be displayed.';
        $this->_table['en-en']['Code PHP']='PHP code';
        $this->_table['en-en']['Connexion non sécurisée']='Connexion not secure';
        $this->_table['en-en']['::::AskEntSyncObj']='Ask the entity to kindly synchronize the object.';
        $this->_table['en-en']["Déverrouillage de l'entité"]='Unlocking the entity';
        $this->_table['en-en']['Émotions et avis']='Emotions and opinions';
        $this->_table['en-en']['Empreinte cryptographique du bootstrap']='Cryptographic fingerprint of the bootstrap';
        $this->_table['en-en']['Entité déverrouillée.']='Unlocked entity.';
        $this->_table['en-en']['Entité en cours.']='Current entity.';
        $this->_table['en-en']['Entité verrouillée (non connectée).']='Locked entity (not connected).';
        $this->_table['en-en']['Essayer plutôt']='Try rather';
        $this->_table['en-en']['est à jour.']='is up to date.';
        $this->_table['en-en']['Erreur lors du chiffrement !']='Error encryption!';
        $this->_table['en-en']['Erreur lors du déchiffrement !']='Error decryption!';
        $this->_table['en-en']['Feuille de style CSS']='Cascading Style Sheet CSS';
        $this->_table['en-en']["Fil d'actualités"]='Newsfeed';
        $this->_table['en-en']['Génération de miniatures']='Thumbnail generation';
        $this->_table['en-en']['Identifiant universel']='Universal identifier';
        $this->_table['en-en']['Image JPEG']='JPEG picture';
        $this->_table['en-en']['Image PNG']='PNG picture';
        $this->_table['en-en']['Informations sur le serveur']='Server Informations';
        $this->_table['en-en']['Lien de mise à jour']='Link update';
        $this->_table['en-en']['Lien écrit.']='Link writed.';
        $this->_table['en-en']['Lien invalide']='Invalid link';
        $this->_table['en-en']['Lien non vérifié']='Unaudited link';
        $this->_table['en-en']['Lien valide']='Valid link';
        $this->_table['en-en']['::::EncryptedFor']='The object is encrypted for';
        $this->_table['en-en']['mis à jour vers %s.']='updated to %s.';
        $this->_table['en-en']['Mise à jour']='Update';
        $this->_table['en-en']['Mise à jour de sylabe']='Update for sylabe';
        $this->_table['en-en']['Mise à jour de tous les composants.']='Update all components.';
        $this->_table['en-en']['Mise en place du mot de passe sur la clé privée.']='Implementation of the password on the private key.';
        $this->_table['en-en']["Mode d'affichage"]='Display mode';
        $this->_table['en-en']["Naviguer autour de l'objet"]='Navigate around the object';
        $this->_table['en-en']['Nœuds connus']='known nodes';
        $this->_table['en-en']['Nom complet']='Full name';
        $this->_table['en-en']['Nom de variable']='Variable name';
        $this->_table['en-en']['Non affichable.']='Not displayable.';
        $this->_table['en-en']['Non déverrouillée.']='Not unlocked.';
        $this->_table['en-en']['Non disponible.']='Not available.';
        $this->_table['en-en']['Non fonctionnel.']='Nonfunctional.';
        $this->_table['en-en']['Objet de test']='Test object';
        $this->_table['en-en']['Page HTML']='HTML page';
        $this->_table['en-en']['Objet non disponible localement.']='Object not available locally.';
        $this->_table['en-en']['Pas de mise à jour connue de cet objet.']='No update known for this object.';
        $this->_table['en-en']["Pas d'accord"]='Disagree';
        $this->_table['en-en']["Pas d'action à traiter."]='No action to deal.';
        $this->_table['en-en']['Pas un nœud']='Not a node';
        $this->_table['en-en']['Pas un noeud']='Not a node';
        $this->_table['en-en']["Protection de l'objet"]='Protection of the object';
        $this->_table['en-en']["Protéger l'objet."]='Protect the object.';
        $this->_table['en-en']['Rafraîchir la vue']='Refresh display';
        $this->_table['en-en']['Rafraichir la vue']='Refresh display';
        $this->_table['en-en']['Rafraichir la vue et charger les nouvelles versions.']='Refresh display and download the new versions.';
        $this->_table['en-en']['Recharger la page.']='Reload the page.';
        $this->_table['en-en']['Régénération des composants manquants.']='Regeneration of missing components.';
        $this->_table['en-en']["Revenir au menu des capacités de transfert d'objets et de liens"]='Back to main menu transfer capabilities of objects and links';
        $this->_table['en-en']['Session utilisateur']='User session';
        $this->_table['en-en']["Supprimer l'avis."]='Remove the notice.';
        $this->_table['en-en']["Supprimer l'émotion."]='Remove the emotion.';
        $this->_table['en-en']["Synchronisation d'un objet non reconnu localement"]='Synchronization of an object not recognized locally';
        $this->_table['en-en']['Taille des clés de chiffrement asymétrique']='Key size for asymmetric encryption';
        $this->_table['en-en']['Taille des clés de chiffrement symétrique']='Key size for symmetric encryption';
        $this->_table['en-en']['Taille des empreintes cryptographiques']='Key size for cryptographic fingerprints';
        $this->_table['en-en']['::::DownloadAsFile']='Download object as file.';
        $this->_table['en-en']['Texte brute']='RAW text';
        $this->_table['en-en']["Toutes les capacités de transfert d'objets et de liens"]='All transfer capabilities of objects and links';
        $this->_table['en-en']["Transférer la protection à l'entité"]='Transfer the protection to the entity';
        $this->_table['en-en']['Type de clé']='Key type';
        $this->_table['en-en']['type inconnu']='unknown type';
        $this->_table['en-en']['Type MIME']='MIME type';
        $this->_table['en-en']['URL de connexion']='Connection URL';
        $this->_table['en-en']['::::VerifLinkSign']='Vérification des signatures de liens';
        $this->_table['en-en']["Verrouillage (déconnexion) de l'entité."]='Lock (disconnection) the entity.';
        $this->_table['en-en']["Verrouiller l'entité."]='Lock the entity.';
        $this->_table['en-en']["Variables d'environnement"]='Environment variables';
        $this->_table['en-en']['Voir déchiffré']='See decrypted';
        $this->_table['en-en']['Voir les liens']='See links';
        $this->_table['en-en']['Voir tout']='See all';
        $this->_table['en-en']['Zone de temps']='Timezone';
        */

        /*
        $this->_table['en-en']['Cet objet a été mise à jour vers']='This object have been updated to';
        $this->_table['en-en'][':::warn_InvalidPubKey']='The public key seems to be invalid!';
        $this->_table['en-en'][':::nav_aff_MaxFileSize']='The maximum file size must not exceed %.0f characters (bytes).';
        $this->_table['en-en'][':::nav_aff_MaxTextSize']='The maximum size must not exceed %.0f characters (bytes).';
        $this->_table['en-en']["Le lien n'a pas été écrit !"]="The link can't be writed !";
        $this->_table['en-en']['Le serveur à pris %01.4fs pour calculer la page.']='The computer took %01.4fs to calculate the page.';
        $this->_table['en-en']["L'opération peut prendre un peu de temps."]="L'opération peut prendre un peu de temps.";
        $this->_table['en-en'][':::warn_NoAudioTagSupport']='Your browser does not support the audio tag.';
        $this->_table['en-en'][':::err_CantWriteLink']="Une erreur s'est produite lors de l'écriture d'un lien !";
        $this->_table['en-en'][':::warn_CantGenThumNoGD']="Les miniatures de l'image n'ont pas été générées (lib GD2 no présente).";
        $this->_table['en-en'][':::err_CantAnalysImg']="Erreur lors de l'analyse de l'image.";
        $this->_table['en-en'][':::warn_CantGenThumUnknowImg']="Les miniatures de l'image n'ont pas été générées. Le type d'image n'est pas reconnu.";
        $this->_table['en-en'][':::hlp_DescObjLnk']="Le monde de <i>sylabe</i> est peuplé d'objets et de liens.";
        $this->_table['en-en'][':::ent_create_WarnAutonomNewEnt']='Aucune entité déverrouillée, donc la nouvelle entité est <u>obligatoirement autonome</u>.';
        $this->_table['en-en'][':::ent_create_WarnMustHaveMDP']='Si la nouvelle entité est <b>autonome</b>, un <u>mot de passe est obligatoire</u>. Sinon, le mot de passe est géré automatiquement.';
        $this->_table['en-en'][':::act_MustUnlockEnt']="Il est nécessaire de déverrouiller l'entité pour pouvoir agir sur les objets et les liens.";
        $this->_table['en-en'][':::warn_NoObjDesc']="Pas de description pour ce type d'objet.";
        $this->_table['en-en'][':::warn_LoadObj2Browser']="Charger directement le code de l'objet dans votre navigateur peut être dangereux !!!";
        $this->_table['en-en'][':::aff_protec_Protected']="L'objet est marqué comme protégé.";
        $this->_table['en-en'][':::aff_protec_Unprotected']="L'objet n'est pas marqué comme protégé.";
        $this->_table['en-en'][':::aff_protec_RemProtect']="Retirer la protection de l'objet.";
        $this->_table['en-en'][':::aff_protec_FollowProtTo']="Transférer la protection à l'entité";
        $this->_table['en-en'][':::aff_sync_SyncLnkObj']="Synchronize object's links.";
        $this->_table['en-en'][':::aff_sync_SyncObj']="Synchroniser le contenu de l'objet.";
        $this->_table['en-en'][':::aff_sync_SearchUpdate']="Search object's updates.";
        $this->_table['en-en'][':::aff_supp_SuppObj']='Suppress this object.';
        $this->_table['en-en'][':::aff_supp_RemSuppObj']='Cancel supression for the object.';
        $this->_table['en-en'][':::aff_supp_BanObj']='Suppress and ban this object.';
        $this->_table['en-en'][':::aff_supp_RemBanObj']='Cancel ban for the object.';
        $this->_table['en-en'][':::aff_supp_ForceSuppObj']='Force suppression of this object on the server.';
        $this->_table['en-en'][':::aff_node_IsNode']='This object is a node.';
        $this->_table['en-en'][':::aff_node_IsnotNode']='This object is not a node.';
        $this->_table['en-en'][':::aff_node_DefineNode']='Define this object as a node.';
        $this->_table['en-en'][':::aff_node_RemDefineNode']='Cancel define this object as a node.';

        // Blocs de texte.
        $this->_table['en-en']['::hlp_msgaffok']='Ceci est un message pour une opération se terminant sans erreur.';
        $this->_table['en-en']['::hlp_msgaffwarn']="Ceci est un message d'avertissement.";
        $this->_table['en-en']['::hlp_msgafferror']="Ceci est un message d'erreur.";
        $this->_table['en-en']['::hlp_text']="";
        $this->_table['en-en']['::bloc_hlp_head']='Online help';
        $this->_table['en-en']['::bloc_hlp_head_hlp']='This is le main help page. In progress...';
        $this->_table['en-en']['::bloc_metrolog']='Metrology';
        $this->_table['en-en']['::bloc_metrolog_hlp']="La partie métrologie donne les mesures de temps globaux et partiels pour le traitement et l'affichage de la page web.";
        $this->_table['en-en']['::bloc_aff_head_hlp']='Display the object';
        $this->_table['en-en']['::bloc_aff_chent']='Switch to this entity';
        $this->_table['en-en']['::bloc_aff_chent_hlp']='Switch to this entity';
        $this->_table['en-en']['::bloc_aff_dwload']='Download and transmission';
        $this->_table['en-en']['::bloc_aff_dwload_hlp']='Download and transmission';
        $this->_table['en-en']['::bloc_aff_protec']='Protect';
        $this->_table['en-en']['::bloc_aff_protec_hlp']="<p>1 - The object can be protected or unprotected, This means encrypted or not.</p>\n
        <p>2 - This command is used to protect the object, it will automatically be marked as deleted <u>and</u> locally removed.</p>\n
        <p>3 - This command is used to remove the protection of the object and restore. The deletion mark will be canceled.</p>\n
        <p>4 - This command is used to transmit the protection of the object to another entity. The entity may see this protected object and also cancel or relay this protection.</p>\n
        <p><b>A data which have been transmitted to others, it is a data on which all control is irretrievably lost.</b></p>\n
        <p>An object that has been protected and is normally marked deleted and locally removed at the same time. It should not be publicly available, but it is not mandatory :<br />\n
        - If the object was distributed to other entities prior to its protection, others see it is marked deleted, thus deleting, but do take maybe not count.<br />\n
        - If this instance of <i>sylabe</i> hosts several entities and a local entity uses this object, it can't be locally removed. It will still be marked deleted.
        Only the entity instance owner may locally force the removal of the object.</p>";
        $this->_table['en-en']['::bloc_aff_sync']='Synchronisation and updating';
        $this->_table['en-en']['::bloc_aff_sync_hlp']='Synchronisation and updating';
        $this->_table['en-en']['::bloc_aff_supp']='Removal and banishment';
        $this->_table['en-en']['::bloc_aff_supp_hlp']='Removal and banishment';
        $this->_table['en-en']['::bloc_aff_node']='Node';
        $this->_table['en-en']['::bloc_aff_node_hlp']='Node';
        $this->_table['en-en']['::bloc_aff_deriv']='Derivation';
        $this->_table['en-en']['::bloc_aff_deriv_hlp']='Derivation';
        $this->_table['en-en']['::bloc_aff_maj']='Updates of the object';
        $this->_table['en-en']['::bloc_aff_maj_hlp']='Updates of the object';
        $this->_table['en-en']['::bloc_nav_head_hlp']="<p>In this display mode, the object is displayed in a reduced or truncated.
        This mode only allows you to have a global vision of the object, and focuses on its relations with other objects.</p>";
        $this->_table['en-en']['::bloc_nav_chent']='Switch to this entity';
        $this->_table['en-en']['::bloc_nav_chent_hlp']='Switch to this entity';
        $this->_table['en-en']['::bloc_nav_update']='Update';
        $this->_table['en-en']['::bloc_nav_update_hlp']='Update';
        $this->_table['en-en']['::bloc_nav_actu']='Newsfeed';
        $this->_table['en-en']['::bloc_nav_actu_hlp']='Newsfeed';
        $this->_table['en-en']['::bloc_log_head']="Entity's session";
        $this->_table['en-en']['::bloc_log_head_hlp']="Entity's session";
        $this->_table['en-en']['::bloc_obj_head']='The objects';
        $this->_table['en-en']['::bloc_obj_head_hlp']='The objects';
        $this->_table['en-en']['::bloc_nod_head']='Nodes and entry points';
        $this->_table['en-en']['::bloc_nod_head_hlp']='Nodes and entry points';
        $this->_table['en-en']['::bloc_nod_create']='Create a node';
        $this->_table['en-en']['::bloc_nod_create_hlp']="<p>Le champs attendu est un texte sans caractères spéciaux. Le texte sera transformé en un objet et celui-ci sera définit comme un nœud.
        Il n'est pas recommandé d'avoir des retours à la ligne dans ce texte.<br />
        Si un objet existe déjà avec ce texte, il sera simplement définit comme nœud.</p>";
        $this->_table['en-en']['::bloc_ent_head']='Management entities';
        $this->_table['en-en']['::bloc_ent_head_hlp']='Management entities';
        $this->_table['en-en']['::bloc_ent_known']='Known entities';
        $this->_table['en-en']['::bloc_ent_known_hlp']='Known entities';
        $this->_table['en-en']['::bloc_ent_ctrl']='Entities under control';
        $this->_table['en-en']['::bloc_ent_ctrl_hlp']='Entities under control';
        $this->_table['en-en']['::bloc_ent_unknown']='Unknown entities';
        $this->_table['en-en']['::bloc_ent_unknown_hlp']='Unknown entities';
        $this->_table['en-en']['::bloc_ent_follow']='Recognize an entity';
        $this->_table['en-en']['::bloc_ent_follow_hlp']="     <p>Au moins un des deux champs doit être renseigné.</p>\n
        <p>1 - L'<b>URL de présence</b> est une adresse sur le web (http, rfc2616) hébergeant un serveur nebule capable de délivrer publiquement les informations sur l'entité recherchée.
        Cette adresse web doit être valide, elle a typiquement la forme <i>http://puppetmaster.nebule.org</i> .<br />\n
        Si ce champs n'est pas renseigné, l'adresse sera recherchée automatiquement. Elle est dabort recherchée localement si l'entité est déjà connue sans être reconnue.
        Elle est ensuite par défaut replacée par l'adresse de l'annuaire par défaut, c'est à dire <i>asabiyya</i>. Si ce champs n'est pas renseigné, la recherche peut ne pas aboutir.<br />\n
        L'adresse ne doit pas être une adresse locale, c'est à dire <code>localhost</code>.<br />\n
        Si la valeur renseignée est fausse, la recherche a de bonnes chances de ne pas aboutir.\n
        </p>\n
        <p>2 - L'<b>Objet ID public</b> est <u>le</u> numéro unique identifiant sans ambiguité l'entité recherchée. Ce numéro en héxadécimal est l'empreinte de la clé publique de l'entité.
        Sans ce numéro, il sera impossible de récupérer l'objet contenant la clé publique, et donc il sera impossible de vérifier les liens que cette entité a généré.<br />\n
        Ce champs, si le numéro est connu, doit être très précisément renseigné et de façon complet. Si ce champs n'est pas renseigné, le numéro sera recherché automatiquement à l'adresse web renseignée.
        Si ce champs n'est pas renseigné, la recherche peut ne pas aboutir.<br />\n
        Si la valeur renseignée est fausse, la recherche n'aboutira pas.\n
        </p>";
        $this->_table['en-en']['::bloc_ent_create']='Create an entity';
        $this->_table['en-en']['::bloc_ent_create_hlp']="<p>Si l'entité créé est autonome, le champs <b>Mot de passe</b> doit être renseigné.</p>
        <p>1 - Le champs <b>Prénom</b> permet de donner un prénom à l'entité. Ce champs est facultatif.</p>
        <p>2 - Le champs <b>Nom</b> permet de donner un nom patronymique à l'entité. Ce champs est facultatif.</p>
        <p>3 - Le champs <b>Surnom</b> permet de donner un surnom à l'entité. Ce champs est facultatif.</p>
        <p>4 - Le choix du <b>Type</b> permet de catégoriser l'entité comme humain ou robot. Ce champs est facultatif.</p>
        <p>5 - Le champs <b>URL de présence</b> permet de donner à l'entité une localisation définie (http, rfc2616). Ce champs est facultatif.<br />
        C'est à cette localisation que l'on devra pouvoir synchroniser les liens et objets de l'entité. Si la localisation n'est pas ce serveur, il est de votre ressort de préparer cette localisation.<br />
        Par défaut, ce sera l'adresse web du serveur, sauf si c'est <code>localhost</code>.<br />
        Il est fortement déconseillé d'utiliser une adresse locale, c'est à dire <code>localhost</code>.</p>
        <p>6 - La case à cocher permet de préciser si l'entité créé est une entité autonome, c'est à dire ne dépendant pas de l'entité en cours.<br />
        Si l'entité n'est pas autonome, elle sera automatiquement reconnue comme dépendante de l'entité courante et un mot de passe sera automatiquement généré pour être utilisé par l'entité courante.<br />
        Si l'entité créé est autonome, un mot de passe est obligatoire.</p>
        <p>7 - Le champs <b>Mot de passe</b> permet donner un mot de passe <i>secret</i> pour pouvoir déverrouiller la nouvelle entité.<br />
        Ce mot de passe n'est pas celui de l'entité en cours (si déverrouillée) mais le mot de passe qui sera utilisé par la nouvelle entité.<br />
        Si l'entité créé est autonome, un mot de passe est obligatoire.<br />
        Le mot de passe doit être saisi deux fois pour prévenir toute erreur de saisi.
        <p>8 - La valeur de <b>Taille de clé</b> définit la longueur de la clé générée et donc permet de maîtriser directement sa solidité. Cependant, des clés trop longues pénalisent les performances.<br />
        Il est recommandé en 2014 de choisir une taille de clé au moins égale à <b>2048bits</b>.<br />
        Le choix par défaut est définit pour le serveur.</p>
        <p>9 - Le choix de l'<b>Algo chiffrement</b> définit le type d'algorithme utilisé. Il y a peu de choix actuellement.<br />
        Il est recommandé de choisir l'algorithme <b>RSA</b>.<br />
        Le choix par défaut est définit pour le serveur.</p>
        <p>10 - Le choix de l'<b>Algo empreinte</b> définit l'algorithme de prise d'empreinte et donc permet de maîtriser directement sa solidité. Cependant, les algorithmes les plus élevés pénalisent les performances.<br />
        Il est recommandé en 2014 de choisir l'algorithme <b>sha256</b> ou plus.<br />
        Le choix par défaut est définit pour le serveur.
        </p>";
        $this->_table['en-en']['::bloc_chr_head']='Search';
        $this->_table['en-en']['::bloc_chr_head_hlp']='Search';
        $this->_table['en-en']['::bloc_lnk_head']='Links of the object';
        $this->_table['en-en']['::bloc_lnk_head_hlp']="<p>Le filtrage permet de réduire l'affichage des liens dans la liste ci-dessous.</p>
        <p>1 - Active le filtrage et cache les liens qui ont été marqués comme supprimés, c'est à dire lorsque le même lien a été généré mais avec l'action <code>x</code>.
        Les liens de suppression ne sont pas affichés non plus.</p>
        <p>2 - On peut ne conserver à l'affichage que certains types de liens, c'est en fait l'action qu'ils ont sur l'objet et les autres liens.
        Par exemple on peut ne vouloir que les liens de chiffrement dont le type est <code>k</code>.</p>
        <p>3 - On peut n'afficher que les liens de l'objet courant a avec un autre objet.
        Ce peut être par exemple la description du type mime (5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0) de l'objet.
        </p>";
        //$this->_table['en-en']['::bloc_lnk_list']='List of links';
        $this->_table['en-en']['::bloc_lnk_list_hlp']="";
        $this->_table['en-en']['::bloc_upl_head']='Transfer of objects and links';
        $this->_table['en-en']['::bloc_upl_head_hlp']='Transfer of objects and links';
        $this->_table['en-en']['::bloc_upl_upfile']='Send file as new object';
        $this->_table['en-en']['::bloc_upl_upfile_hlp']="<p>Cette partie permet de transmettre un fichier à nébuliser, c'est à dire à transformer en objet <i>nebule</i>.</p>
        <p>L'empreinte du fichier est automatiquement calculée, elle deviendra l'identifiant (ID) de l'objet.
        En fonction du type de fichier, il est analysé afin d'en extraire certaines caractéristiques personnalisées.</p>";
        $this->_table['en-en']['::bloc_upl_uptxt']='Send new text';
        $this->_table['en-en']['::bloc_upl_uptxt_hlp']="<p>Cette partie permet la création d'un objet à partir d'un texte brute, c'est à dire sans formatage.</p>";
        $this->_table['en-en']['::bloc_upl_synobj']='Synchronization of an object not recognized locally';
        $this->_table['en-en']['::bloc_upl_synobj_hlp']="<p>Cette partie permet de tenter de trouver un objet et ses liens aux différents emplacements connus.
        L'objet est recherche par rapport à son identifiant, c'est à dire son empreinte.</p>";
        $this->_table['en-en']['::bloc_upl_uplnk']='Send a simple link';
        $this->_table['en-en']['::bloc_upl_uplnk_hlp']="Cette partie permet de transmettre un lien à ajouter. Après vérification, le lien est automatiquement attaché aux objets concernés.</p>
        <p>Si une entité n'est pas déverrouillée, le lien doit être signé par l'entité indiqué. C'est dans ce cas un import d'un seul lien.
        Pour transmettre plusieurs liens simultanément, il faut passer par la partie '<i>Envoie d'un fichier de liens pré-signés</i>'.</p>";
        $this->_table['en-en']['::bloc_upl_crlnk']='Create a new link';
        $this->_table['en-en']['::bloc_upl_crlnk_hlp']="<p>Cette partie permet la création d'un nouveau lien et sa signature. Il faut renseigner les différents champs correspondants au registre du lien attendu.</p>";
        $this->_table['en-en']['::bloc_upl_upfilelnk']='Send file with pre-signed links';
        $this->_table['en-en']['::bloc_upl_upfilelnk_hlp']="<p>Cette partie permet de transmettre un fichier contenant des liens à ajouter. Tous les liens doivent être signés pour être analysés.
        Après vérification, les liens sont automatiquement attachés aux objets concernés.</p>";

        // Description des variables
        $this->_table['en-en']['::var_nebule_hashalgo']="Algorithme de prise d'empreinte utilisé par défaut.";
        $this->_table['en-en']['::var_nebule_symalgo']="Algorithme de chiffrement symétrique utilisé par défaut.";
        $this->_table['en-en']['::var_nebule_symkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement symétrique.";
        $this->_table['en-en']['::var_nebule_asymalgo']="Algorithme de chiffrement asymétrique utilisé par défaut.";
        $this->_table['en-en']['::var_nebule_asymkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement asymétrique.";
        $this->_table['en-en']['::var_nebule_io_maxlink']='Limit the number of links to read for an object, the following are ignored. Used by functions <code>_l_ls1</code> and <code>__io_lr</code>.';
        $this->_table['en-en']['::var_nebule_io_maxdata']='Limit the amount of data in bytes to read for an object, the rest is ignored.. Used by functions <code>_o_dl1</code> and <code>__io_or</code>.';
        $this->_table['en-en']['::var_nebule_checksign']='Permit or not links signs inspection. Used by function <code>_l_vr</code>, on links read and load. Should always be <u>true</u>.';
        $this->_table['en-en']['::var_nebule_listchecklinks']='Permit or not on the read verification of links and signs. Used by function <code>_l_ls1</code>. Affect performances.';
        $this->_table['en-en']['::var_nebule_listinvalidlinks']='Permit or not on the read invalids links. For display purpose only, not used. Used by function <code>_l_ls1</code>.';
        $this->_table['en-en']['::var_nebule_permitwrite']="Permit or not writing operations by <code>php</code> code.
        Used by functions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_dl1</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code>,
        <code>_l_wr</code>, <code>_l_gen</code>, <code>__io_lw</code> and <code>__io_ow</code>. On <u>false</u>, it's an global read only lock.";
        $this->_table['en-en']['::var_nebule_permitcreatelink']='Permit or not new link creation by <code>php</code> code.
        Used by functions <code>_e_gen</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_l_wr</code>, <code>_l_gen</code> and <code>__io_lw</code>.';
        $this->_table['en-en']['::var_nebule_permitcreateobj']='Permit or not new object creation by <code>php</code> code.
        Used by functions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code> and <code>__io_ow</code>.';
        $this->_table['en-en']['::var_nebule_permitcreatentity']='Permit or not the create of new entity by <code>php</code> code. Used by function <code>_e_gen</code>.';
        $this->_table['en-en']['::var_nebule_permitsynclink']='Permit or not links download from others nebule servers. Used by function <code>_l_dl1</code>.';
        $this->_table['en-en']['::var_nebule_permitsyncobject']='Permit or not objects download from others nebule servers. Used by function <code>_o_dl1</code>.';
        $this->_table['en-en']['::var_nebule_createhistory']='Permit or not logging about all lasts new links creation. This create file <code>/l/f</code> with links which have to be flush regularly.
        Used to export easily new links generated by an offline entity.';
        $this->_table['en-en']['::var_nebule_curentnotauthority']="Interdit à l'entité courante d'être autorité. Cela l'empêche de charger des composants externes par elle-même. Dans le bootstrap, le comportement est un peu différent.";
        $this->_table['en-en']['::var_nebule_local_authority']="C'est la liste des entités reconnues comme autorités locales. Seules ces entités peuvent signer des modules à charger localement.";
        $this->_table['en-en']['::var_sylabe_affuntrustedsign']='Display or not results of links verify, on <code>lnk</code> display mode only.';
        $this->_table['en-en']['::var_sylabe_hidedevmods']='Switch display between development mode and quiet mode.';
        $this->_table['en-en']['::var_sylabe_permitsendlink']='Permit or not links upload to this server.';
        $this->_table['en-en']['::var_sylabe_permitsendobject']='Permit or not objects upload to this server.';
        $this->_table['en-en']['::var_sylabe_permitpubcreatentity']="Permit or not the create of new entity (autonomous) publicly, event if there's no previously unlocked entity. Must be <u>false</u> on public server.";
        $this->_table['en-en']['::var_nebule_permitcreatentnopwd']="Autorise ou non la création d'une entité sans mot de passe. Devrait toujours être à <u>false</u>.";
        $this->_table['en-en']['::var_sylabe_permitaskbootstrap']='Permit or not the sending of commands to the <i>bootstrap</i> which select sylabe version and library version. Must be <u>false</u> on public server.';
        $this->_table['en-en']['::var_sylabe_affonlinehelp']='Permit or not the online help.';
        $this->_table['en-en']['::var_sylabe_showvars']='Display or not internals variables, on <code>log</code> display mode only.';
        $this->_table['en-en']['::var_sylabe_timedebugg']='Display inline woking times on the flow.';
        $this->_table['en-en']['::var_sylabe_upfile_maxsize']='Define max size in bytes (after uuencode) of uploaded files on this server.';
        $this->_table['en-en']['::var_nebule_followxonsamedate']="Prendre en compte le lien x si la date est identique avec un autre lien, ou pas.";
        $this->_table['en-en']['::var_nebule_maxrecurse']="Définit le maximum de niveaux parcourus pour la recherche des objets enfants d'un objet. Affecte les performances.";
        $this->_table['en-en']['::var_nebule_maxupdates']="Définit le maximum de niveaux parcourus poue la recherche des mises à jours d'un objet. Affecte les performances.";
        $this->_table['en-en']['::var_nebule_linkversion']="Définit la version de nebule utilisée pour les liens.";
        $this->_table['en-en']['::var_nebule_usecache']="Autorise ou non l'utilisation du cache. Affecte les performances.";
        $this->_table['en-en']['::var_sylabe_permitfollowcss']='Permit or not the use of custom style sheets (CSS).';
        $this->_table['en-en']['::var_sylabe_permitphpcss']='Permit or not the use of php code in the style sheets (CSS).';
        */
    }
}
