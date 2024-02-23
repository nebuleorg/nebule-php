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
    protected $MODULE_VERSION = '020240207';
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


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::translateModule:fr-fr:ModuleName'] = 'Français (France)';
        $this->_table['en-en']['::translateModule:fr-fr:ModuleName'] = 'French (France)';
        $this->_table['es-co']['::translateModule:fr-fr:ModuleName'] = 'Francés (Francia)';
        $this->_table['fr-fr']['::translateModule:fr-fr:MenuName'] = 'Français (France)';
        $this->_table['en-en']['::translateModule:fr-fr:MenuName'] = 'French (France)';
        $this->_table['es-co']['::translateModule:fr-fr:MenuName'] = 'Francés (Francia)';
        $this->_table['fr-fr']['::translateModule:fr-fr:ModuleDescription'] = "Traduction de l'interface en Français.";
        $this->_table['en-en']['::translateModule:fr-fr:ModuleDescription'] = 'Interface translation in French.';
        $this->_table['es-co']['::translateModule:fr-fr:ModuleDescription'] = 'Interface translation in French.';
        $this->_table['fr-fr']['::translateModule:fr-fr:ModuleHelp'] = "Ce module permet de mettre en place la traduction de l'interface de sylabe en Français.";
        $this->_table['en-en']['::translateModule:fr-fr:ModuleHelp'] = 'This module permit to translate the sylabe interface in French.';
        $this->_table['es-co']['::translateModule:fr-fr:ModuleHelp'] = 'This module permit to translate the sylabe interface in French.';

        // Salutations.
        $this->_table['fr-fr']['::::Bienvenue'] = 'Bienvenue.';

        // Traduction de mots.
        $this->_table['fr-fr']['::Password'] = 'Mot de passe';
        $this->_table['fr-fr']['::yes'] = 'Oui';
        $this->_table['fr-fr']['::no'] = 'Non';
        $this->_table['fr-fr']['::::SecurityChecks'] = 'Tests de sécurité';
        $this->_table['fr-fr']['::Lock'] = 'Verrouiller';
        $this->_table['fr-fr']['::Unlock'] = 'Déverrouiller';
        $this->_table['fr-fr']['::EntityLocked'] = 'Entité verrouillée. Déverrouiller ?';
        $this->_table['fr-fr']['::EntityUnlocked'] = 'Entité déverrouillée. Verrouiller ?';
        $this->_table['fr-fr']['::::INFO'] = 'Information';
        $this->_table['fr-fr']['::::OK'] = 'OK';
        $this->_table['fr-fr']['::::INFORMATION'] = 'Message';
        $this->_table['fr-fr']['::::WARN'] = 'ATTENTION !';
        $this->_table['fr-fr']['::::ERROR'] = 'ERREUR !';
        $this->_table['fr-fr']['::::RESCUE'] = 'Mode de sauvetage !';
        $this->_table['fr-fr']['::::icon:DEFAULT_ICON_LO'] = 'Objet';
        $this->_table['fr-fr']['::::HtmlHeadDescription'] = 'Page web cliente sylabe pour nebule.';
        $this->_table['fr-fr']['::::Experimental'] = '[Experimental]';
        $this->_table['fr-fr']['::::Developpement'] = '[En cours de développement]';
        $this->_table['fr-fr']['::::help'] = 'Aide';
        $this->_table['fr-fr']['nebule/objet'] = 'Objet';
        $this->_table['fr-fr']['nebule/objet/hash'] = "Type d'empreinte";
        $this->_table['fr-fr']['nebule/objet/type'] = 'Type MIME';
        $this->_table['fr-fr']['nebule/objet/taille'] = 'Taille';
        $this->_table['fr-fr']['nebule/objet/nom'] = 'Nom';
        $this->_table['fr-fr']['nebule/objet/prefix'] = 'Préfix';
        $this->_table['fr-fr']['nebule/objet/prenom'] = 'Prénom';
        $this->_table['fr-fr']['nebule/objet/suffix'] = 'Suffix';
        $this->_table['fr-fr']['nebule/objet/surnom'] = 'Surnom';
        $this->_table['fr-fr']['nebule/objet/postnom'] = 'Surnom';
        $this->_table['fr-fr']['nebule/objet/entite'] = 'Entité';
        $this->_table['fr-fr']['nebule/objet/entite/type'] = 'Type';
        $this->_table['fr-fr']['nebule/objet/date'] = 'Date';
        $this->_table['fr-fr']['nebule/objet/date/annee'] = 'Année';
        $this->_table['fr-fr']['nebule/objet/date/mois'] = 'Mois';
        $this->_table['fr-fr']['nebule/objet/date/jour'] = 'Jour';
        $this->_table['fr-fr']['nebule/objet/date/heure'] = 'Heure';
        $this->_table['fr-fr']['nebule/objet/date/minute'] = 'Minute';
        $this->_table['fr-fr']['nebule/objet/date/seconde'] = 'Seconde';
        $this->_table['fr-fr']['nebule/objet/date/zone'] = 'Zone de temps';
        $this->_table['fr-fr']['nebule/objet/emotion/colere'] = 'Contrariant';
        $this->_table['fr-fr']['nebule/objet/emotion/degout'] = 'Dégôuté';
        $this->_table['fr-fr']['nebule/objet/emotion/surprise'] = 'Étonnant';
        $this->_table['fr-fr']['nebule/objet/emotion/peur'] = 'Inquiétant';
        $this->_table['fr-fr']['nebule/objet/emotion/interet'] = 'Intéressé';
        $this->_table['fr-fr']['nebule/objet/emotion/joie'] = "J'aime";
        $this->_table['fr-fr']['nebule/objet/emotion/confiance'] = "J'approuve";
        $this->_table['fr-fr']['nebule/objet/emotion/tristesse'] = 'Tristement';
        $this->_table['fr-fr']['nebule/objet/entite/localisation'] = 'Localisation';
        $this->_table['fr-fr']['nebule/objet/entite/maitre/securite'] = 'Maître de la sécurité';
        $this->_table['fr-fr']['nebule/objet/entite/maitre/code'] = 'Maître du code';
        $this->_table['fr-fr']['nebule/objet/entite/maitre/annuaire'] = "Maître de l'annuaire";
        $this->_table['fr-fr']['nebule/objet/entite/maitre/temps'] = 'Maître du temps';

        // Type mime
        $this->_table['fr-fr'][nebule::REFERENCE_OBJECT_TEXT] = 'Texte brute';
        $this->_table['fr-fr']['application/x-pem-file'] = 'Entité';
        $this->_table['fr-fr']['image/jpeg'] = 'Image JPEG';
        $this->_table['fr-fr']['image/png'] = 'Image PNG';
        $this->_table['fr-fr']['application/x-bzip2'] = 'Archive BZIP2';
        $this->_table['fr-fr']['text/html'] = 'Page HTML';
        $this->_table['fr-fr']['application/x-php'] = 'Code PHP';
        $this->_table['fr-fr']['text/x-php'] = 'Code PHP';
        $this->_table['fr-fr']['text/css'] = 'Feuille de style CSS';
        $this->_table['fr-fr']['audio/mpeg'] = 'Audio MP3';
        $this->_table['fr-fr']['audio/x-vorbis+ogg'] = 'Audio OGG';
        $this->_table['fr-fr']['application/x-encrypted/rsa'] = 'Chiffré';
        $this->_table['fr-fr']['application/x-encrypted/aes-256-ctr'] = 'Chiffré';
        $this->_table['fr-fr']['application/x-folder'] = 'Dossier';

        // Espressions courtes.
        $this->_table['fr-fr']['::::IDprivateKey'] = 'ID privé';
        $this->_table['fr-fr']['::::IDpublicKey'] = 'ID public';
        $this->_table['fr-fr']['::Version'] = 'Version';
        $this->_table['fr-fr']['::UniqueID'] = 'Identifiant universel : %s';
        $this->_table['fr-fr']['::GroupeFerme'] = 'Groupe fermé';
        $this->_table['fr-fr']['::GroupeOuvert'] = 'Groupe ouvert';
        $this->_table['fr-fr']['::ConversationFermee'] = 'Conversation fermée';
        $this->_table['fr-fr']['::ConversationOuverte'] = 'Conversation ouverte';
        $this->_table['fr-fr']['::progress'] = 'Chargement en cours...';
        $this->_table['fr-fr']['::seeMore'] = 'Voir plus...';
        $this->_table['fr-fr']['::noContent'] = '(contenu indisponible)';
        $this->_table['fr-fr']['::appSwitch'] = "Changer d'application";
        $this->_table['fr-fr']['::menu'] = 'Menu';
        $this->_table['fr-fr']['::menuDesc'] = 'Page du menu complet';
        $this->_table['fr-fr']['::EmptyList'] = 'Liste vide.';
        $this->_table['fr-fr']['::ChangeLanguage'] = 'Changer de langue';
        $this->_table['fr-fr']['::SelectUser'] = 'Sélectionner un utilisateur';
        $this->_table['fr-fr']['::MarkAdd'] = 'Marquer';
        $this->_table['fr-fr']['::MarkRemove'] = 'Démarquer';
        $this->_table['fr-fr']['::MarkRemoveAll'] = 'Démarquer tout';
        $this->_table['fr-fr']['::Synchronize'] = 'Synchroniser';

        // Phrases complètes.
        $this->_table['fr-fr'][':::display:content:errorBan'] = 'Cet objet est banni, il ne peut pas être affiché !';
        $this->_table['fr-fr'][':::display:content:warningTaggedWarning'] = 'Cet objet est marqué comme dangereux, attention à son contenu !';
        $this->_table['fr-fr'][':::display:content:ObjectProctected'] = 'Cet objet est protégé !';
        $this->_table['fr-fr'][':::display:content:warningObjectProctected'] = 'Cet objet est marqué comme protégé, attention à la divulgation de son contenu en public !!!';
        $this->_table['fr-fr'][':::display:content:OK'] = 'Cet objet est valide, son contenu a été vérifié.';
        $this->_table['fr-fr'][':::display:content:warningTooBig'] = "Cet objet est trop volumineux, son contenu n'a pas été vérifié !";
        $this->_table['fr-fr'][':::display:content:errorNotDisplayable'] = 'Cet objet ne peut pas être affiché !';
        $this->_table['fr-fr'][':::display:content:errorNotAvailable'] = "Cet objet n'est pas disponible, il ne peut pas être affiché !";
        $this->_table['fr-fr'][':::display:content:notAnObject'] = "Cet objet de référence n'a pas de contenu.";
        $this->_table['fr-fr'][':::display:content:ObjectHaveUpdate'] = 'Cet objet a été mis à jour vers :';
        $this->_table['fr-fr'][':::display:content:Activated'] = 'Cet objet est activé.';
        $this->_table['fr-fr'][':::display:content:NotActivated'] = 'Cet objet est désactivé.';
        $this->_table['fr-fr'][':::display:link:OK'] = 'Ce lien est valide.';
        $this->_table['fr-fr'][':::display:link:errorInvalid'] = "Ce lien n'est pas valide !";
        $this->_table['fr-fr'][':::warn_ServNotPermitWrite'] = "Ce serveur n'autorise pas les modifications.";
        $this->_table['fr-fr'][':::warn_flushSessionAndCache'] = "Toutes les données de connexion ont été effacées.";
        $this->_table['fr-fr'][':::err_NotPermit'] = 'Non autorisé sur ce serveur !';
        $this->_table['fr-fr'][':::act_chk_errCryptHash'] = "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !";
        $this->_table['fr-fr'][':::act_chk_warnCryptHashkey'] = "La taille de l'empreinte cryptographique est trop petite !";
        $this->_table['fr-fr'][':::act_chk_errCryptHashkey'] = "La taille de l'empreinte cryptographique est invalide !";
        $this->_table['fr-fr'][':::act_chk_errCryptSym'] = "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !";
        $this->_table['fr-fr'][':::act_chk_warnCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est trop petite !";
        $this->_table['fr-fr'][':::act_chk_errCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est invalide !";
        $this->_table['fr-fr'][':::act_chk_errCryptAsym'] = "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !";
        $this->_table['fr-fr'][':::act_chk_warnCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est trop petite !";
        $this->_table['fr-fr'][':::act_chk_errCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est invalide !";
        $this->_table['fr-fr'][':::act_chk_errBootstrap'] = "L'empreinte cryptographique du bootstrap est invalide !";
        $this->_table['fr-fr'][':::act_chk_warnSigns'] = 'La vérification des signatures de liens est désactivée !';
        $this->_table['fr-fr'][':::act_chk_errSigns'] = 'La vérification des signatures de liens ne fonctionne pas !';
        $this->_table['fr-fr'][':::info_OnlySignedLinks'] = 'Uniquement des liens signés !';
        $this->_table['fr-fr'][':::info_OnlyLinksFromCodeMaster'] = 'Uniquement les liens signés du maître du code !';

        $this->_table['fr-fr'][':::display:object:flag:protected'] = 'Cet objet est protégé.';
        $this->_table['fr-fr'][':::display:object:flag:unprotected'] = "Cet objet n'est pas protégé.";
        $this->_table['fr-fr'][':::display:object:flag:obfuscated'] = 'Cet objet est dissimulé.';
        $this->_table['fr-fr'][':::display:object:flag:unobfuscated'] = "Cet objet n'est pas dissimulé.";
        $this->_table['fr-fr'][':::display:object:flag:locked'] = 'Cet entité est déverrouillée.';
        $this->_table['fr-fr'][':::display:object:flag:unlocked'] = 'Cet entité est verrouillée.';
        $this->_table['fr-fr'][':::display:object:flag:activated'] = 'Cet objet est activé.';
        $this->_table['fr-fr'][':::display:object:flag:unactivated'] = "Cet objet n'est pas activé.";

        /*
		$this->_table['fr-fr']['Lien']='Lien';
		$this->_table['fr-fr']['-indéfini-']='-indéfini-';
		$this->_table['fr-fr']['-indéterminé-']='-indéterminé-';
		$this->_table['fr-fr']['Affichage']='Affichage';
		$this->_table['fr-fr']['Aide']='Aide';
		$this->_table['fr-fr']['Algorithme']='Algorithme';
		$this->_table['fr-fr']['nebule/avis/ambigue']='Ambiguë';
		$this->_table['fr-fr']['Ambiguë']='Ambiguë';
		$this->_table['fr-fr']['Ambigue']='Ambiguë';
		$this->_table['fr-fr']['Année']='Année';
		$this->_table['fr-fr']['Attention !']='Attention !';
		$this->_table['fr-fr']['Aucun']='Aucun';
		$this->_table['fr-fr']['Aucune']='Aucune';
		$this->_table['fr-fr']['Bannissement']='Bannissement';
		$this->_table['fr-fr']['nebule/avis/beau']='Beau';
		$this->_table['fr-fr']['Beau']='Beau';
		$this->_table['fr-fr']['bits']='bits';
		$this->_table['fr-fr']['nebule/avis/bon']='Bon';
		$this->_table['fr-fr']['Bon']='Bon';
		$this->_table['fr-fr']['Bootstrap']='Bootstrap';
		$this->_table['fr-fr']['Caractéristiques']='Caractéristiques';
		$this->_table['fr-fr']['Charger']='Charger';
		$this->_table['fr-fr']['Chiffré']='Chiffré';
		$this->_table['fr-fr']['Chiffrement']='Chiffrement';
		$this->_table['fr-fr']['nebule/avis/clair']='Clair';
		$this->_table['fr-fr']['Clair']='Clair';
		$this->_table['fr-fr']['Commenter']='Commenter';
		$this->_table['fr-fr']['nebule/avis/complet']='Complet';
		$this->_table['fr-fr']['Complet']='Complet';
		$this->_table['fr-fr']['Contrariant']='Contrariant';
		$this->_table['fr-fr']['Cryptographie']='Cryptographie';
		$this->_table['fr-fr']['Date']='Date';
		$this->_table['fr-fr']['Déchiffrement']='Déchiffrement';
		$this->_table['fr-fr']['Dégôuté']='Dégôuté';
		$this->_table['fr-fr']['Déprotéger']='Déprotéger';
		$this->_table['fr-fr']['Description']='Description';
		$this->_table['fr-fr']['Déverrouillage']='Déverrouillage';
		$this->_table['fr-fr']['Déverrouiller']='Déverrouiller';
		$this->_table['fr-fr']["D'accord"]="D'accord";
		$this->_table['fr-fr']['Émotion']='Émotion';
		$this->_table['fr-fr']['Emotion']='Émotion';
		$this->_table['fr-fr']['Empreinte']='Empreinte';
		$this->_table['fr-fr']['Entité']='Entité';
		$this->_table['fr-fr']['Entités']='Entités';
		$this->_table['fr-fr']['ERREUR !']='ERREUR !';
		$this->_table['fr-fr']['ERROR']='ERREUR';
		$this->_table['fr-fr']['Étonnant']='Étonnant';
		$this->_table['fr-fr']['Etonnant']='Étonnant';
		$this->_table['fr-fr']['Expérimental']='Expérimental';
		$this->_table['fr-fr']['nebule/avis/faux']='Faux';
		$this->_table['fr-fr']['Faux']='Faux';
		$this->_table['fr-fr']['nebule/avis/génial']='Génial';
		$this->_table['fr-fr']['Génial']='Génial';
		$this->_table['fr-fr']['Genre']='Genre';
		$this->_table['fr-fr']['Heure']='Heure';
		$this->_table['fr-fr']['humain']='humain';
		$this->_table['fr-fr']['Identifiant']='Identifiant';
		$this->_table['fr-fr']['nebule/avis/important']='Important';
		$this->_table['fr-fr']['important']='Important';
		$this->_table['fr-fr']['Inconnu']='Inconnu';
		$this->_table['fr-fr']['nebule/avis/incomplet']='Incomplet';
		$this->_table['fr-fr']['Incomplet']='Incomplet';
		$this->_table['fr-fr']['nebule/avis/incomprehensible']='Incompréhensible';
		$this->_table['fr-fr']['Incompréhensible']='Incompréhensible';
		$this->_table['fr-fr']['Inquiétant']='Inquiétant';
		$this->_table['fr-fr']['Intéressé']='Intéressé';
		$this->_table['fr-fr']['nebule/avis/inutile']='Inutile';
		$this->_table['fr-fr']['Inutile']='Inutile';
		$this->_table['fr-fr']['Invalide']='Invalide';
		$this->_table['fr-fr']["J'aime"]="J'aime";
		$this->_table['fr-fr']["J'approuve"]="J'approuve";
		$this->_table['fr-fr']['Jour']='Jour';
		$this->_table['fr-fr']['Liens']='Liens';
		$this->_table['fr-fr']["L'objet"]="L'objet";
		$this->_table['fr-fr']['nebule/avis/mauvais']='Mauvais';
		$this->_table['fr-fr']['Mauvais']='Mauvais';
		$this->_table['fr-fr']['Minute']='Minute';
		$this->_table['fr-fr']['nebule/avis/moche']='Moche';
		$this->_table['fr-fr']['Moche']='Moche';
		$this->_table['fr-fr']['Mois']='Mois';
		$this->_table['fr-fr']['nebule/avis/moyen']='Moyen';
		$this->_table['fr-fr']['Moyen']='Moyen';
		$this->_table['fr-fr']['Navigation']='Navigation';
		$this->_table['fr-fr']['Nœud']='Nœud';
		$this->_table['fr-fr']['Noeud']='Nœud';
		$this->_table['fr-fr']['Nœuds']='Nœuds';
		$this->_table['fr-fr']['Noeuds']='Nœuds';
		$this->_table['fr-fr']['NOK']='NOK';
		$this->_table['fr-fr']['nebule/avis/nul']='Nul';
		$this->_table['fr-fr']['Nul']='Nul';
		$this->_table['fr-fr']['Objet']='Objet';
		$this->_table['fr-fr']['Objets']='Objets';
		$this->_table['fr-fr']['octet']='octet';
		$this->_table['fr-fr']['octets']='octets';
		$this->_table['fr-fr']['OK']='OK';
		$this->_table['fr-fr']['nebule/avis/perime']='Périmé';
		$this->_table['fr-fr']['Périmé']='Périmé';
		$this->_table['fr-fr']['privée']='privée';
		$this->_table['fr-fr']['Protection']='Protection';
		$this->_table['fr-fr']['Protéger']='Protéger';
		$this->_table['fr-fr']['publique']='publique';
		$this->_table['fr-fr']['Rafraîchir']='Rafraîchir';
		$this->_table['fr-fr']['Recherche']='Recherche';
		$this->_table['fr-fr']['Rechercher']='Rechercher';
		$this->_table['fr-fr']['Régénération']='Régénération';
		$this->_table['fr-fr']['Répéter']='Répéter';
		$this->_table['fr-fr']['robot']='robot';
		$this->_table['fr-fr']['Seconde']='Seconde';
		$this->_table['fr-fr']['Source']='Source';
		$this->_table['fr-fr']['Synchroniser']='Synchroniser';
		$this->_table['fr-fr']['Suppression']='Suppression';
		$this->_table['fr-fr']['Supprimer']='Supprimer';
		$this->_table['fr-fr']['Taille']='Taille';
		$this->_table['fr-fr']['Téléchargement']='Téléchargement';
		$this->_table['fr-fr']['Télécharger']='Télécharger';
		$this->_table['fr-fr']['Transfert']='Transfert';
		$this->_table['fr-fr']['Transmettre']='Transmettre';
		$this->_table['fr-fr']['Tristement']='Tristement';
		$this->_table['fr-fr']['Valeur']='Valeur';
		$this->_table['fr-fr']['Validité']='Validité';
		$this->_table['fr-fr']['Version']='Version';
		$this->_table['fr-fr']['Verrouillage']='Verrouillage';
		$this->_table['fr-fr']['Verrouiller']='Verrouiller';
		$this->_table['fr-fr']['Voir']='Voir';				// ok
		$this->_table['fr-fr']['nebule/avis/vrai']='Vrai';
		$this->_table['fr-fr']['Vrai']='Vrai';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		*/

        /*
		$this->_table['fr-fr']['Creation nouveau lien']="Création d'un nouveau lien";
		$this->_table['fr-fr']['::::GenNewEnt']='Génération nouvelle entité';
		$this->_table['fr-fr']['%01.0f liens lus,']='%01.0f liens lus,';
		$this->_table['fr-fr']['%01.0f liens vérifiés,']='%01.0f liens vérifiés,';
		$this->_table['fr-fr']['%01.0f objets vérifiés.']='%01.0f objets vérifiés.';
		$this->_table['fr-fr']['Accès au bootstrap.']='Accès au bootstrap.';
		$this->_table['fr-fr']["Afficher l'objet"]="Afficher l'objet";
		$this->_table['fr-fr']['Aide en ligne']='Aide en ligne';
		$this->_table['fr-fr']['::::AddNotice2Obj']='Ajouter un avis sur cet objet';
		$this->_table['fr-fr']['Ajout du nouveau lien non autorisé.']='Ajout du nouveau lien non autorisé.';
		$this->_table['fr-fr']['::::HashAlgo']="Algorithme de prise d'empreinte";
		$this->_table['fr-fr']['::::SymCryptAlgo']='Algorithme de chiffrement symétrique';
		$this->_table['fr-fr']['::::AsymCryptAlgo']='Algorithme de chiffrement asymétrique';
		$this->_table['fr-fr']['Annuler bannissement']='Annuler bannissement';
		$this->_table['fr-fr']['Archive BZIP2']='Archive BZIP2';
		$this->_table['fr-fr']['Aucun objet à afficher.']='Aucun objet à afficher.';
		$this->_table['fr-fr']['Aucun objet dérivé à afficher.']='Aucun objet dérivé à afficher.';
		$this->_table['fr-fr']['Audio MP3']='Audio MP3';
		$this->_table['fr-fr']['Audio OGG']='Audio OGG';
		$this->_table['fr-fr']['::::Switch2Ent']='Basculer vers cette entité';
		$this->_table['fr-fr']['::::LoadObj2Browser']="Charger directement le code de l'objet dans le navigateur.";
		$this->_table['fr-fr']['Chiffré, non affichable.']='Chiffré, non affichable.';
		$this->_table['fr-fr']['Code PHP']='Code PHP';
		$this->_table['fr-fr']['Connexion non sécurisée']='Connexion non sécurisée';
		$this->_table['fr-fr']['::::AskEntSyncObj']="Demander à l'entité de bien vouloir synchroniser l'objet.";
		$this->_table['fr-fr']["Déverrouillage de l'entité"]="Déverrouillage de l'entité";
		$this->_table['fr-fr']['Émotions et avis']='Émotions et avis';
		$this->_table['fr-fr']['Empreinte cryptographique du bootstrap']='Empreinte cryptographique du bootstrap';
		$this->_table['fr-fr']['Entité déverrouillée.']='Entité déverrouillée.';
		$this->_table['fr-fr']['Entité en cours.']='Entité en cours.';
		$this->_table['fr-fr']['Entité verrouillée (non connectée).']='Entité verrouillée (non connectée).';
		$this->_table['fr-fr']['Essayer plutôt']='Essayer plutôt';
		$this->_table['fr-fr']['est à jour.']='est à jour.';
		$this->_table['fr-fr']['Erreur lors du chiffrement !']='Erreur lors du chiffrement !';
		$this->_table['fr-fr']['Erreur lors du déchiffrement !']='Erreur lors du déchiffrement !';
		$this->_table['fr-fr']['Feuille de style CSS']='Feuille de style CSS';
		$this->_table['fr-fr']["Fil d'actualités"]="Fil d'actualités";
		$this->_table['fr-fr']['Génération de miniatures']='Génération de miniatures';
		$this->_table['fr-fr']['Identifiant universel']='Identifiant universel';
		$this->_table['fr-fr']['Image JPEG']='Image JPEG';
		$this->_table['fr-fr']['Image PNG']='Image PNG';
		$this->_table['fr-fr']['Informations sur le serveur']='Informations sur le serveur';
		$this->_table['fr-fr']['Lien de mise à jour']='Lien de mise à jour';
		$this->_table['fr-fr']['Lien écrit.']='Lien écrit.';
		$this->_table['fr-fr']['Lien invalide']='Lien invalide';
		$this->_table['fr-fr']['Lien non vérifié']='Lien non vérifié';
		$this->_table['fr-fr']['Lien valide']='Lien valide';
		$this->_table['fr-fr']['::::EncryptedFor']="L'objet est chiffré pour";
		$this->_table['fr-fr']['mis à jour vers %s.']='mis à jour vers %s.';
		$this->_table['fr-fr']['Mise à jour']='Mise à jour';
		$this->_table['fr-fr']['Mise à jour de sylabe']='Mise à jour de sylabe';
		$this->_table['fr-fr']['Mise à jour de tous les composants.']='Mise à jour de tous les composants.';
		$this->_table['fr-fr']['Mise en place du mot de passe sur la clé privée.']='Mise en place du mot de passe sur la clé privée.';
		$this->_table['fr-fr']["Mode d'affichage"]="Mode d'affichage";
		$this->_table['fr-fr']["Naviguer autour de l'objet"]="Naviguer autour de l'objet";
		$this->_table['fr-fr']['Nœuds connus']='Nœuds connus';
		$this->_table['fr-fr']['Nom complet']='Nom complet';
		$this->_table['fr-fr']['Nom de variable']='Nom de variable';
		$this->_table['fr-fr']['Non affichable.']='Non affichable.';
		$this->_table['fr-fr']['Non déverrouillée.']='Non déverrouillée.';
		$this->_table['fr-fr']['Non disponible.']='Non disponible.';
		$this->_table['fr-fr']['Non fonctionnel.']='Non fonctionnel.';
		$this->_table['fr-fr']['Objet de test']='Objet de test';
		$this->_table['fr-fr']['Page HTML']='Page HTML';
		$this->_table['fr-fr']['Objet non disponible localement.']='Objet non disponible localement.';
		$this->_table['fr-fr']['Pas de mise à jour connue de cet objet.']='Pas de mise à jour connue de cet objet.';
		$this->_table['fr-fr']["Pas d'accord"]="Pas d'accord";
		$this->_table['fr-fr']["Pas d'action à traiter."]="Pas d'action à traiter.";
		$this->_table['fr-fr']['Pas un nœud']='Pas un nœud';
		$this->_table['fr-fr']['Pas un noeud']='Pas un nœud';
		$this->_table['fr-fr']["Protection de l'objet"]="Protection de l'objet";
		$this->_table['fr-fr']["Protéger l'objet."]="Protéger l'objet.";
		$this->_table['fr-fr']['Rafraîchir la vue']='Rafraîchir la vue';
		$this->_table['fr-fr']['Rafraichir la vue']='Rafraîchir la vue';
		$this->_table['fr-fr']['Rafraichir la vue et charger les nouvelles versions.']='Rafraichir la vue et charger les nouvelles versions.';
		$this->_table['fr-fr']['Recharger la page.']='Recharger la page.';
		$this->_table['fr-fr']['Régénération des composants manquants.']='Régénération des composants manquants.';
		$this->_table['fr-fr']["Revenir au menu des capacités de transfert d'objets et de liens"]="Revenir au menu des capacités de transfert d'objets et de liens";
		$this->_table['fr-fr']['Session utilisateur']='Session utilisateur';
		$this->_table['fr-fr']["Supprimer l'avis."]="Supprimer l'avis.";
		$this->_table['fr-fr']["Supprimer l'émotion."]="Supprimer l'émotion.";
		$this->_table['fr-fr']["Synchronisation d'un objet non reconnu localement"]="Synchronisation d'un objet non reconnu localement";
		$this->_table['fr-fr']['Taille des clés de chiffrement asymétrique']='Taille des clés de chiffrement asymétrique';
		$this->_table['fr-fr']['Taille des clés de chiffrement symétrique']='Taille des clés de chiffrement symétrique';
		$this->_table['fr-fr']['Taille des empreintes cryptographiques']='Taille des empreintes cryptographiques';
		$this->_table['fr-fr']['::::DownloadAsFile']="Télécharger l'objet sous forme de fichier.";
		$this->_table['fr-fr']["Toutes les capacités de transfert d'objets et de liens"]="Toutes les capacités de transfert d'objets et de liens";
		$this->_table['fr-fr']["Transférer la protection à l'entité"]="Transférer la protection à l'entité";
		$this->_table['fr-fr']['Type de clé']='Type de clé';
		$this->_table['fr-fr']['type inconnu']='type inconnu';
		$this->_table['fr-fr']['Type MIME']='Type MIME';
		$this->_table['fr-fr']['URL de connexion']='URL de connexion';
		$this->_table['fr-fr']['::::VerifLinkSign']='Vérification des signatures de liens';
		$this->_table['fr-fr']["Verrouillage (déconnexion) de l'entité."]="Verrouillage (déconnexion) de l'entité.";
		$this->_table['fr-fr']["Verrouiller l'entité."]="Verrouiller l'entité.";
		$this->_table['fr-fr']["Variables d'environnement"]="Variables d'environnement";
		$this->_table['fr-fr']['Voir déchiffré']='Voir déchiffré';
		$this->_table['fr-fr']['Voir les liens']='Voir les liens';
		$this->_table['fr-fr']['Voir tout']='Voir tout';
		$this->_table['fr-fr']['Zone de temps']='Zone de temps';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';



		$this->_table['fr-fr']['Cet objet a été mise à jour vers']='Cet objet a été mise à jour vers';
		$this->_table['fr-fr'][':::warn_InvalidPubKey']='La clé publique semble invalide !';
		$this->_table['fr-fr'][':::nav_aff_MaxFileSize']='La taille maximum du fichier ne doit pas dépasser %.0f caractères (octets).';
		$this->_table['fr-fr'][':::nav_aff_MaxTextSize']='La taille maximum du texte ne doit pas dépasser %.0f caractères (octets).';
		$this->_table['fr-fr']["Le lien n'a pas été écrit !"]="Le lien n'a pas été écrit !";
		$this->_table['fr-fr']['Le serveur à pris %01.4fs pour calculer la page.']='Le serveur à pris %01.4fs pour calculer la page.';
		$this->_table['fr-fr']["L'opération peut prendre un peu de temps."]="L'opération peut prendre un peu de temps.";
		$this->_table['fr-fr'][':::warn_NoAudioTagSupport']='Votre navigateur ne supporte pas le tag audio.';
		$this->_table['fr-fr'][':::err_CantWriteLink']="Une erreur s'est produite lors de l'écriture d'un lien !";
		$this->_table['fr-fr'][':::warn_CantGenThumNoGD']="Les miniatures de l'image n'ont pas été générées (lib GD2 no présente).";
		$this->_table['fr-fr'][':::err_CantAnalysImg']="Erreur lors de l'analyse de l'image.";
		$this->_table['fr-fr'][':::warn_CantGenThumUnknowImg']="Les miniatures de l'image n'ont pas été générées. Le type d'image n'est pas reconnu.";
		$this->_table['fr-fr'][':::hlp_DescObjLnk']="Le monde de <i>sylabe</i> est peuplé d'objets et de liens.";
		$this->_table['fr-fr'][':::ent_create_WarnAutonomNewEnt']='Aucune entité déverrouillée, donc la nouvelle entité est <u>obligatoirement autonome</u>.';
		$this->_table['fr-fr'][':::ent_create_WarnMustHaveMDP']='Si la nouvelle entité est <b>autonome</b>, un <u>mot de passe est obligatoire</u>. Sinon, le mot de passe est géré automatiquement.';
		$this->_table['fr-fr'][':::act_MustUnlockEnt']="Il est nécessaire de déverrouiller l'entité pour pouvoir agir sur les objets et les liens.";
		$this->_table['fr-fr'][':::warn_NoObjDesc']="Pas de description pour ce type d'objet.";
		$this->_table['fr-fr'][':::warn_LoadObj2Browser']="Charger directement le code de l'objet dans votre navigateur peut être dangereux !!!";
		$this->_table['fr-fr'][':::aff_protec_Protected']="L'objet est marqué comme protégé.";
		$this->_table['fr-fr'][':::aff_protec_Unprotected']="L'objet n'est pas marqué comme protégé.";
		$this->_table['fr-fr'][':::aff_protec_RemProtect']="Retirer la protection de l'objet.";
		$this->_table['fr-fr'][':::aff_protec_FollowProtTo']="Transférer la protection à l'entité";
		$this->_table['fr-fr'][':::aff_sync_SyncLnkObj']="Synchroniser les liens de l'objet.";
		$this->_table['fr-fr'][':::aff_sync_SyncObj']="Synchroniser le contenu de l'objet.";
		$this->_table['fr-fr'][':::aff_sync_SearchUpdate']="Rechercher les mises à jour de l'objet.";
		$this->_table['fr-fr'][':::aff_supp_SuppObj']="Supprimer l'objet.";
		$this->_table['fr-fr'][':::aff_supp_RemSuppObj']="Annuler la suppression de l'objet.";
		$this->_table['fr-fr'][':::aff_supp_BanObj']="Supprimer et bannir l'objet.";
		$this->_table['fr-fr'][':::aff_supp_RemBanObj']="Annuler le bannissement de l'objet.";
		$this->_table['fr-fr'][':::aff_supp_ForceSuppObj']="Forcer la suppression de l'objet sur ce serveur.";
		$this->_table['fr-fr'][':::aff_node_IsNode']="L'objet est un nœud.";
		$this->_table['fr-fr'][':::aff_node_IsnotNode']="L'objet n'est pas un nœud.";
		$this->_table['fr-fr'][':::aff_node_DefineNode']="Définir l'objet comme étant un nœud.";
		$this->_table['fr-fr'][':::aff_node_RemDefineNode']="Ne plus définir l'objet comme étant un nœud.";
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';



		// Blocs de texte.
		$this->_table['fr-fr']['::hlp_msgaffok']='Ceci est un message pour une opération se terminant sans erreur.';
		$this->_table['fr-fr']['::hlp_msgaffwarn']="Ceci est un message d'avertissement.";
		$this->_table['fr-fr']['::hlp_msgafferror']="Ceci est un message d'erreur.";
		$this->_table['fr-fr']['::hlp_text']="";
		$this->_table['fr-fr']['::bloc_hlp_head']='Aide en ligne';
		$this->_table['fr-fr']['::bloc_hlp_head_hlp']="C'est la page de l'aide en ligne. En cours de rédactions...";
		$this->_table['fr-fr']['::bloc_metrolog']='Métrologie';
		$this->_table['fr-fr']['::bloc_metrolog_hlp']="La partie métrologie donne les mesures de temps globaux et partiels pour le traitement et l'affichage de la page web.";
		$this->_table['fr-fr']['::bloc_aff_head_hlp']="Affichage de l'objet";
		$this->_table['fr-fr']['::bloc_aff_chent']='Basculer vers cette entité';
		$this->_table['fr-fr']['::bloc_aff_chent_hlp']='Basculer vers cette entité';
		$this->_table['fr-fr']['::bloc_aff_dwload']='Téléchargement et transmission';
		$this->_table['fr-fr']['::bloc_aff_dwload_hlp']='Téléchargement et transmission';
		$this->_table['fr-fr']['::bloc_aff_protec']='Protection';
		$this->_table['fr-fr']['::bloc_aff_protec_hlp']="<p>1 - L'objet peut être protégé ou non protégé, c'est à dire chiffré ou non.</p>\n
		<p>2 - Cette commande permet de protéger l'objet, il sera automatiquement marqué comme supprimé <u>et</u> supprimé localement.</p>\n
		<p>3 - Cette commande permet de lever la protection de l'objet et de le restaurer. La marque de suppression sera annulée.</p>\n
		<p>4 - Cette commande permet de transmettre la protection de l'objet à une autre entité. L'entité pourra voir l'objet protégé mais aussi annuler ou retransmettre cette protection.</p>\n
		<p><b>Une donnée que l’on transmet à autrui, c’est une donnée sur laquelle on perd irrémédiablement tout contrôle.</b></p>\n
		<p>Un objet qui a été protégé est normalement marqué supprimé et localement supprimé en même temps. Il devrait donc ne plus être disponible publiquement, mais ce n'est pas obligatoire :<br />\n
		- Si l'objet a été diffusé à d'autres entités préalablement à sa protection, les autres entités verront qu'il est marqué supprimé, donc a supprimer, mais n'en tiendront peut-être pas compte.<br />\n
		- Si cette instance de <i>sylabe</i> héberge plusieurs entités et qu'une des entité locale utilise cet objet, il ne pourra pas être localement supprimé.
		Il sera malgré tout marqué supprimé. Seule l'entité propriétaire de l'instance pourra forcer localement la suppression de l'objet.</p>";
		$this->_table['fr-fr']['::bloc_aff_sync']='Synchronisation et mise à jour';
		$this->_table['fr-fr']['::bloc_aff_sync_hlp']='Synchronisation et mise à jour';
		$this->_table['fr-fr']['::bloc_aff_supp']='Suppression et bannissement';
		$this->_table['fr-fr']['::bloc_aff_supp_hlp']='Suppression et bannissement';
		$this->_table['fr-fr']['::bloc_aff_node']='Nœud';
		$this->_table['fr-fr']['::bloc_aff_node_hlp']='Nœud';
		$this->_table['fr-fr']['::bloc_aff_deriv']='Dérivation';
		$this->_table['fr-fr']['::bloc_aff_deriv_hlp']='Dérivation';
		$this->_table['fr-fr']['::bloc_aff_maj']="Mise à jour de l'objet";
		$this->_table['fr-fr']['::bloc_aff_maj_hlp']="Mise à jour de l'objet";
		$this->_table['fr-fr']['::bloc_nav_head_hlp']="<p>Dans le mode de navigation, l'objet est affiché de façon réduite ou tronquée.
		Ce mode ne permet d'avoir qu'une vision globale de l'objet mais se focalise sur ses relations avec les autres objets.</p>";
		$this->_table['fr-fr']['::bloc_nav_chent']='Basculer vers cette entité';
		$this->_table['fr-fr']['::bloc_nav_chent_hlp']='Basculer vers cette entité';
		$this->_table['fr-fr']['::bloc_nav_update']='Mise à jour';
		$this->_table['fr-fr']['::bloc_nav_update_hlp']='Mise à jour';
		$this->_table['fr-fr']['::bloc_nav_actu']="Fil d'actualités";
		$this->_table['fr-fr']['::bloc_nav_actu_hlp']="Fil d'actualités";
		$this->_table['fr-fr']['::bloc_log_head']="Session de l'entité";
		$this->_table['fr-fr']['::bloc_log_head_hlp']="Session de l'entité";
		$this->_table['fr-fr']['::bloc_obj_head']='Les objets';
		$this->_table['fr-fr']['::bloc_obj_head_hlp']='Les objets';
		$this->_table['fr-fr']['::bloc_nod_head']="Nœuds et points d'entrée";
		$this->_table['fr-fr']['::bloc_nod_head_hlp']="Nœuds et points d'entrée";
		$this->_table['fr-fr']['::bloc_nod_create']='Créer un nœud';
		$this->_table['fr-fr']['::bloc_nod_create_hlp']="<p>Le champs attendu est un texte sans caractères spéciaux. Le texte sera transformé en un objet et celui-ci sera définit comme un nœud.
		Il n'est pas recommandé d'avoir des retours à la ligne dans ce texte.<br />
		Si un objet existe déjà avec ce texte, il sera simplement définit comme nœud.</p>";
		$this->_table['fr-fr']['::bloc_ent_head']='Gestion des entités';
		$this->_table['fr-fr']['::bloc_ent_head_hlp']='Gestion des entités';
		$this->_table['fr-fr']['::bloc_ent_known']='Entités connues';
		$this->_table['fr-fr']['::bloc_ent_known_hlp']='Entités connues';
		$this->_table['fr-fr']['::bloc_ent_ctrl']='Entités sous contrôle';
		$this->_table['fr-fr']['::bloc_ent_ctrl_hlp']='Entités sous contrôle';
		$this->_table['fr-fr']['::bloc_ent_unknown']='Entités inconnues';
		$this->_table['fr-fr']['::bloc_ent_unknown_hlp']='Entités inconnues';
		$this->_table['fr-fr']['::bloc_ent_follow']='Reconnaître une entité';
		$this->_table['fr-fr']['::bloc_ent_follow_hlp']="     <p>Au moins un des deux champs doit être renseigné.</p>\n
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
		$this->_table['fr-fr']['::bloc_ent_create']='Créer une entité';
		$this->_table['fr-fr']['::bloc_ent_create_hlp']="<p>Si l'entité créé est autonome, le champs <b>Mot de passe</b> doit être renseigné.</p>
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
		$this->_table['fr-fr']['::bloc_chr_head']='Recherche';
		$this->_table['fr-fr']['::bloc_chr_head_hlp']='Recherche';
		$this->_table['fr-fr']['::bloc_lnk_head']="Liens de l'objet";
		$this->_table['fr-fr']['::bloc_lnk_head_hlp']="<p>Le filtrage permet de réduire l'affichage des liens dans la liste ci-dessous.</p>
		<p>1 - Active le filtrage et cache les liens qui ont été marqués comme supprimés, c'est à dire lorsque le même lien a été généré mais avec l'action <code>x</code>.
		Les liens de suppression ne sont pas affichés non plus.</p>
		<p>2 - On peut ne conserver à l'affichage que certains types de liens, c'est en fait l'action qu'ils ont sur l'objet et les autres liens.
		Par exemple on peut ne vouloir que les liens de chiffrement dont le type est <code>k</code>.</p>
		<p>3 - On peut n'afficher que les liens de l'objet courant a avec un autre objet.
		Ce peut être par exemple la description du type mime (5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0) de l'objet.
		</p>";
		//$this->_table['fr-fr']['::bloc_lnk_list']='Liste des liens';
		$this->_table['fr-fr']['::bloc_lnk_list_hlp']="";
		$this->_table['fr-fr']['::bloc_upl_head']="Transfert d'objets et de liens";
		$this->_table['fr-fr']['::bloc_upl_head_hlp']="Transfert d'objets et de liens";
		$this->_table['fr-fr']['::bloc_upl_upfile']="Envoi d'un fichier comme nouvel objet";
		$this->_table['fr-fr']['::bloc_upl_upfile_hlp']="<p>Cette partie permet de transmettre un fichier à nébuliser, c'est à dire à transformer en objet <i>nebule</i>.</p>
		<p>L'empreinte du fichier est automatiquement calculée, elle deviendra l'identifiant (ID) de l'objet.
		En fonction du type de fichier, il est analysé afin d'en extraire certaines caractéristiques personnalisées.</p>";
		$this->_table['fr-fr']['::bloc_upl_uptxt']="Envoi d'un nouveau texte";
		$this->_table['fr-fr']['::bloc_upl_uptxt_hlp']="<p>Cette partie permet la création d'un objet à partir d'un texte brute, c'est à dire sans formatage.</p>";
		$this->_table['fr-fr']['::bloc_upl_synobj']="Synchronisation d'un objet non reconnu localement";
		$this->_table['fr-fr']['::bloc_upl_synobj_hlp']="<p>Cette partie permet de tenter de trouver un objet et ses liens aux différents emplacements connus.
		L'objet est recherche par rapport à son identifiant, c'est à dire son empreinte.</p>";
		$this->_table['fr-fr']['::bloc_upl_uplnk']="Envoie d'un simple lien";
		$this->_table['fr-fr']['::bloc_upl_uplnk_hlp']="Cette partie permet de transmettre un lien à ajouter. Après vérification, le lien est automatiquement attaché aux objets concernés.</p>
		<p>Si une entité n'est pas déverrouillée, le lien doit être signé par l'entité indiqué. C'est dans ce cas un import d'un seul lien.
		Pour transmettre plusieurs liens simultanément, il faut passer par la partie '<i>Envoie d'un fichier de liens pré-signés</i>'.</p>";
		$this->_table['fr-fr']['::bloc_upl_crlnk']="Création d'un nouveau lien";
		$this->_table['fr-fr']['::bloc_upl_crlnk_hlp']="<p>Cette partie permet la création d'un nouveau lien et sa signature. Il faut renseigner les différents champs correspondants au registre du lien attendu.</p>";
		$this->_table['fr-fr']['::bloc_upl_upfilelnk']="Envoie d'un fichier de liens pré-signés";
		$this->_table['fr-fr']['::bloc_upl_upfilelnk_hlp']="<p>Cette partie permet de transmettre un fichier contenant des liens à ajouter. Tous les liens doivent être signés pour être analysés.
		Après vérification, les liens sont automatiquement attachés aux objets concernés.</p>";

		// Description des variables
		$this->_table['fr-fr']['::var_nebule_hashalgo']="Algorithme de prise d'empreinte utilisé par défaut.";
		$this->_table['fr-fr']['::var_nebule_symalgo']="Algorithme de chiffrement symétrique utilisé par défaut.";
		$this->_table['fr-fr']['::var_nebule_symkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement symétrique.";
		$this->_table['fr-fr']['::var_nebule_asymalgo']="Algorithme de chiffrement asymétrique utilisé par défaut.";
		$this->_table['fr-fr']['::var_nebule_asymkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement asymétrique.";
		$this->_table['fr-fr']['::var_nebule_io_maxlink']="Limite du nombre de liens à lire pour un objet, les suivants sont ignorés. Utilisé par les fonctions <code>_l_ls1</code> et <code>__io_lr</code>.";
		$this->_table['fr-fr']['::var_nebule_io_maxdata']="Limite de la quantité de données en octets à lire pour un objet, le reste est ignorés. Utilisé par les fonctions <code>_o_dl1</code> et <code>__io_or</code>.";
		$this->_table['fr-fr']['::var_nebule_checksign']="Autorise ou non la vérification de la signature des liens. Utilisé par la fonction <code>_l_vr</code> et surtout lors d'un transfert. Devrait toujours être à <u>true</u>.";
		$this->_table['fr-fr']['::var_nebule_listchecklinks']="Autorise ou non la vérification de la validité des liens lors de leur lecture, signature compris. Utilisé par la fonction <code>_l_ls1</code>. Affecte les performances.";
		$this->_table['fr-fr']['::var_nebule_listinvalidlinks']="Autorise ou non la lecture des liens invalides. C'est destiné à de l'affichage, les liens ne sont pas pris en compte. Utilisé par la fonction <code>_l_ls1</code>.";
		$this->_table['fr-fr']['::var_nebule_permitwrite']="Autorise ou non l'écriture par le code <code>php</code>.
		Utilisé par les fonctions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_dl1</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code>,
		<code>_l_wr</code>, <code>_l_gen</code>, <code>__io_lw</code> et <code>__io_ow</code>. Positionné à <u>false</u>, c'est une protection globale en lecture seule.";
		$this->_table['fr-fr']['::var_nebule_permitcreatelink']="Autorise ou non la création de nouveaux liens par le code <code>php</code>.
		Utilisé par les fonctions <code>_e_gen</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_l_wr</code>, <code>_l_gen</code> et <code>__io_lw</code>.";
		$this->_table['fr-fr']['::var_nebule_permitcreateobj']="Autorise ou non la création de nouveaux objets par le code <code>php</code>.
		Utilisé par les fonctions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code> et <code>__io_ow</code>.";
		$this->_table['fr-fr']['::var_nebule_permitcreatentity']="Autorise ou non la création de nouvelles entités par le code <code>php</code>. Utilisé par la fonction <code>_e_gen</code>.";
		$this->_table['fr-fr']['::var_nebule_permitsynclink']="Autorise ou non le transfert de liens depuis un autre serveur nebule. Utilisé par la fonction <code>_l_dl1</code>.";
		$this->_table['fr-fr']['::var_nebule_permitsyncobject']="Autorise ou non le transfert d'objets depuis un autre serveur nebule. Utilisé par la fonction <code>_o_dl1</code>.";
		$this->_table['fr-fr']['::var_nebule_createhistory']="Autorise ou non la tenue d'un historique des derniers liens créés. Cela crée un fichier de liens <code>/l/f</code> qui doit être nettoyé régulièrement.
		C'est utilisé pour exporter plus facilement les derniers liens créés sur une entité déconnectée du réseau.";
		$this->_table['fr-fr']['::var_nebule_curentnotauthority']="Interdit à l'entité courante d'être autorité. Cela l'empêche de charger des composants externes par elle-même. Dans le bootstrap, le comportement est un peu différent.";
		$this->_table['fr-fr']['::var_nebule_local_authority']="C'est la liste des entités reconnues comme autorités locales. Seules ces entités peuvent signer des modules à charger localement.";
		$this->_table['fr-fr']['::var_sylabe_affuntrustedsign']="Affiche ou non le résultat de la vérification des liens, mode d'affichage <code>lnk</code> uniquement.";
		$this->_table['fr-fr']['::var_sylabe_hidedevmods']="Bascule l'affichage entre le mode de développement et le mode épuré.";
		$this->_table['fr-fr']['::var_sylabe_permitsendlink']="Autorise ou non le transfert de liens vers ce serveur.";
		$this->_table['fr-fr']['::var_sylabe_permitsendobject']="Autorise ou non le transfert d'objets vers ce serveur.";
		$this->_table['fr-fr']['::var_sylabe_permitpubcreatentity']="Autorise ou non la création d'une entité (autonome) de façon publique, c'est à dire même si aucune entité n'est préalablement déverrouillée.
		Doit être à <u>false</u> sur un serveur public.";
		$this->_table['fr-fr']['::var_nebule_permitcreatentnopwd']="Autorise ou non la création d'une entité sans mot de passe. Devrait toujours être à <u>false</u>.";
		$this->_table['fr-fr']['::var_sylabe_permitaskbootstrap']="Autorise ou non le passage de consigne au <i>bootstrap</i> pour sélectionner une version de sylabe et de la librairie. Doit être à <u>false</u> sur un serveur public.";
		$this->_table['fr-fr']['::var_sylabe_affonlinehelp']="Autorise ou non l'affichage de l'aide en ligne.";
		$this->_table['fr-fr']['::var_sylabe_showvars']="Affiche ou non les variables internes, mode d'affichage <code>log</code> uniquement.";
		$this->_table['fr-fr']['::var_sylabe_timedebugg']="Affiche les temps de traitements intermédiaires, en ligne.";
		$this->_table['fr-fr']['::var_sylabe_upfile_maxsize']="Définit la taille maximale en octets (après uuencode) des fichiers lors d'un téléchargement vers ce serveur.";
		$this->_table['fr-fr']['::var_nebule_followxonsamedate']="Prendre en compte le lien x si la date est identique avec un autre lien, ou pas.";
		$this->_table['fr-fr']['::var_nebule_maxrecurse']="Définit le maximum de niveaux parcourus pour la recherche des objets enfants d'un objet. Affecte les performances.";
		$this->_table['fr-fr']['::var_nebule_maxupdates']="Définit le maximum de niveaux parcourus poue la recherche des mises à jours d'un objet. Affecte les performances.";
		$this->_table['fr-fr']['::var_nebule_linkversion']="Définit la version de nebule utilisée pour les liens.";
		$this->_table['fr-fr']['::var_nebule_usecache']="Autorise ou non l'utilisation du cache. Affecte les performances.";
		$this->_table['fr-fr']['::var_sylabe_permitfollowcss']="Autorise ou non l’utilisation d’une feuille de style (CSS) personnalisée.";
		$this->_table['fr-fr']['::var_sylabe_permitphpcss']="Autorise ou non l'utilisation de code php dans la feuille de style (CSS).";
		$this->_table['fr-fr']['::none']='';
		$this->_table['fr-fr']['::none']='';
		$this->_table['fr-fr']['::none']='';
		$this->_table['fr-fr']['::none']='';
		$this->_table['fr-fr']['::none']='';
		*/

        /* à faire...

		Ajout des propriétés de l'entité
		Aucun mot de passe n'est définit
		Aucune entité déverrouillée, donc la nouvelle entité est <u>obligatoirement autonome</u>.
		Ce nouveau texte va remplacer un objet existant!
		Ce texte remplace un objet existant.
		Création d'une nouvelle entité dépendante de l'entité courante.
		Création d'une nouvelle entité indépendante.
		La clé publique %s a été créé.
		La clé publique n'a pas été créé !!!
		La clé privée %s a été créé.
		La clé privée n'a pas été créé !!!
		La génération de la nouvelle entité a échouée !
		Lancer une mise à jour de tous les composants.
		Lancer une régénération des composants manquants.
		Le mot de passe n'est pas valide !!!
		Le mot de passe est valide.
		Le transfert a échoué.
		Les mots de passe ne sont pas identiques.
		L'affichage de l'objet a été tronqué.
		L'opération peut prendre un peu de temps.
		Pas de dossier temporaire, les téléchargements vont échouer !
		Rafraichir la vue et charger les nouvelles versions.
		Rechercher les mises à jour de l'objet.
		Restreint à des liens <u>signés</u> par l'entité <i>%s</i> !
		Si la nouvelle entité est <b>autonome</b>, un <u>mot de passe est obligatoire</u>. Sinon, le mot de passe est géré automatiquement.
		Transmettre la protection de l'objet %s à l'entité %s.
		Voir les propriétés de l'objet.
		Voir les propriétés et l'intégralité de l'objet.
		(1) Impossible d'écrire dans le dossier temporaire, les téléchargements vont échouer !
		(2) Impossible d'écrire dans le dossier temporaire, les téléchargements vont échouer !!
		L'affichage de la liste des objets est désactivé pour les entités non déverrouillées.

		*/
    }
}
