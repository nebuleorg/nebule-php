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
    protected $MODULE_VERSION = '020231221';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2023';
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


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::translateModule:es-co:ModuleName']='Espagnol (Colombie)';
        $this->_table['en-en']['::translateModule:es-co:ModuleName']='Spanish (Colombia)';
        $this->_table['es-co']['::translateModule:es-co:ModuleName']='Español (Colombia)';
        $this->_table['fr-fr']['::translateModule:es-co:MenuName']='Espagnol (Colombie)';
        $this->_table['en-en']['::translateModule:es-co:MenuName']='Spanish (Colombia)';
        $this->_table['es-co']['::translateModule:es-co:MenuName']='Español (Colombia)';
        $this->_table['fr-fr']['::translateModule:es-co:ModuleDescription']="Traduction de l'interface en Espagnol.";
        $this->_table['en-en']['::translateModule:es-co:ModuleDescription']='Interface translation in Spanish.';
        $this->_table['es-co']['::translateModule:es-co:ModuleDescription']='Interface translation in Spanish.';
        $this->_table['fr-fr']['::translateModule:es-co:ModuleHelp']="Ce module permet de mettre en place la traduction de l'interface de sylabe et des applications en Espagnol.";
        $this->_table['en-en']['::translateModule:es-co:ModuleHelp']='This module permit to translate the sylabe interface in Spanish.';
        $this->_table['es-co']['::translateModule:es-co:ModuleHelp']='This module permit to translate the sylabe interface in Spanish.';

        // Salutations.
        $this->_table['es-co']['::::Bienvenue'] = 'Bienvenido.';

        // Traduction de mots.
        $this->_table['es-co']['::Password']='Contraseña';
        $this->_table['es-co']['::yes']='Si';
        $this->_table['es-co']['::no']='No';
        $this->_table['es-co']['::::SecurityChecks']='Controles de seguridad';
        $this->_table['es-co']['::Lock']='Locking';
        $this->_table['es-co']['::Unlock']='Unlocking';
        $this->_table['es-co']['::EntityLocked']='Entidad bloqueada. Desbloquear?';
        $this->_table['es-co']['::EntityUnlocked']='Entidad desbloqueada. Bloquear?';
        $this->_table['es-co']['::::INFO']='Information';
        $this->_table['es-co']['::::OK']='OK';
        $this->_table['es-co']['::::INFORMATION']='Mensaje';
        $this->_table['es-co']['::::WARN']='¡ADVERTENCIA!';
        $this->_table['es-co']['::::ERROR']='¡ERROR!';
        $this->_table['es-co']['::::RESCUE']='¡Modo de rescate!';
        $this->_table['es-co']['::::icon:DEFAULT_ICON_LO']='Objeto';
        $this->_table['es-co']['::::HtmlHeadDescription']='Página web cliente sylabe para nebule.';
        $this->_table['es-co']['::::Experimental']='[Experimental]';
        $this->_table['es-co']['::::Developpement']='[Under developpement]';
        $this->_table['es-co']['::::help']='Ayuda';
        $this->_table['es-co']['nebule/objet']='Objeto';
        $this->_table['es-co']['nebule/objet/hash']='Typo huella';
        $this->_table['es-co']['nebule/objet/type']='Tipo MIME';
        $this->_table['es-co']['nebule/objet/Taille']='Tamaño';
        $this->_table['es-co']['nebule/objet/nom']='Nombre';
        $this->_table['es-co']['nebule/objet/prefix']='Prefijo';
        $this->_table['es-co']['nebule/objet/prenom']='Primer nombre';
        $this->_table['es-co']['nebule/objet/suffix']='Sufijo';
        $this->_table['es-co']['nebule/objet/surnom']='Apodo';
        $this->_table['es-co']['nebule/objet/postnom']='Apodo';
        $this->_table['es-co']['nebule/objet/entite']='Entidad';
        $this->_table['es-co']['nebule/objet/entite/type']='Tipo';
        $this->_table['es-co']['nebule/objet/date']='Dato';
        $this->_table['es-co']['nebule/objet/date/annee']='Año';
        $this->_table['es-co']['nebule/objet/date/mois']='Mes';
        $this->_table['es-co']['nebule/objet/date/jour']='Dia';
        $this->_table['es-co']['nebule/objet/date/heure']='Hora';
        $this->_table['es-co']['nebule/objet/date/minute']='Minuto';
        $this->_table['es-co']['nebule/objet/date/seconde']='Segundo';
        $this->_table['es-co']['nebule/objet/date/zone']='Zona horaria';
        $this->_table['es-co']['nebule/objet/emotion/colere']='Molesto';
        $this->_table['es-co']['nebule/objet/emotion/degout']='Disgustado';
        $this->_table['es-co']['nebule/objet/emotion/surprise']='Sorprendente';
        $this->_table['es-co']['nebule/objet/emotion/peur']='Inquietante';
        $this->_table['es-co']['nebule/objet/emotion/interet']='Interesadas';
        $this->_table['es-co']['nebule/objet/emotion/joie']='Me gusta';
        $this->_table['es-co']['nebule/objet/emotion/confiance']='Apruebo';
        $this->_table['es-co']['nebule/objet/emotion/tristesse']='Tristemente';
        $this->_table['es-co']['nebule/objet/entite/localisation']='Localisation';
        $this->_table['es-co']['nebule/objet/entite/maitre/securite']='Maestro de seguridad';
        $this->_table['es-co']['nebule/objet/entite/maitre/code']='Maestro de codigo';
        $this->_table['es-co']['nebule/objet/entite/maitre/annuaire']='Maestro de directorio';
        $this->_table['es-co']['nebule/objet/entite/maitre/temps']='Maestro de tiempo';

        // Type mime
        $this->_table['es-co'][nebule::REFERENCE_OBJECT_TEXT]='Texto en bruto';
        $this->_table['es-co']['application/x-pem-file']='Entidad';
        $this->_table['es-co']['image/jpeg']='JPEG gráfico';
        $this->_table['es-co']['image/png']='PNG gráfico';
        $this->_table['es-co']['application/x-bzip2']='Archivo BZIP2';
        $this->_table['es-co']['text/html']='Página HTML';
        $this->_table['es-co']['application/x-php']='Código PHP';
        $this->_table['es-co']['text/x-php']='Código PHP';
        $this->_table['es-co']['text/css']='Hojas de estilo en cascada CSS';
        $this->_table['es-co']['audio/mpeg']='Audio MP3';
        $this->_table['es-co']['audio/x-vorbis+ogg']='Audio OGG';
        $this->_table['es-co']['application/x-encrypted/rsa']='Encriptado';
        $this->_table['es-co']['application/x-encrypted/aes-256-ctr']='Encriptado';
        $this->_table['es-co']['application/x-folder']='Archivo';

        // Espressions courtes.
        $this->_table['es-co']['::::IDprivateKey']='ID privé';
        $this->_table['es-co']['::::IDpublicKey']='ID public';
        $this->_table['es-co']['::Version']='Version';
        $this->_table['es-co']['::UniqueID']='Universal identifier : %s';
        $this->_table['es-co']['::GroupeFerme']='Closed group';
        $this->_table['es-co']['::GroupeOuvert']='Opened group';
        $this->_table['es-co']['::ConversationFermee']='Closed conversation';
        $this->_table['es-co']['::ConversationOuverte']='Opened conversation';
        $this->_table['es-co']['::progress']='In progress...';
        $this->_table['es-co']['::seeMore']='See more...';
        $this->_table['es-co']['::noContent']='(content not available)';
        $this->_table['es-co']['::appSwitch']='Switch application';
        $this->_table['es-co']['::menu']='Menu';
        $this->_table['es-co']['::menuDesc']='Page with full menu';
        $this->_table['es-co']['::EmptyList']='Empty list.';
        $this->_table['es-co']['::ChangeLanguage']='Cambiar el idioma';
        $this->_table['es-co']['::SelectUser']='Seleccionar un usuario';
        $this->_table['es-co']['::MarkAdd']='Mark';
        $this->_table['es-co']['::MarkRemove']='Unmark';
        $this->_table['es-co']['::MarkRemoveAll']='Unmark all';
        $this->_table['es-co']['::Synchronize']='Synchronize';

        // Phrases complètes.
        $this->_table['es-co'][':::display:content:errorBan']="This object is banned, it can't be displayed!";
        $this->_table['es-co'][':::display:content:warningTaggedWarning']="This object is marked as dangerous, be carfull with it's content!";
        $this->_table['es-co'][':::display:content:ObjectProctected']="This object is marked as protected!";
        $this->_table['es-co'][':::display:content:warningObjectProctected']="This object is marked as protected, be careful when it's content is displayed in public!";
        $this->_table['es-co'][':::display:content:OK']="This object is valid, it's content have been checked.";
        $this->_table['es-co'][':::display:content:warningTooBig']="This object is too big, it's content have not been checked!";
        $this->_table['es-co'][':::display:content:errorNotDisplayable']="This object can't be displayed!";
        $this->_table['es-co'][':::display:content:errorNotAvailable']="This object is not available, it can't be displayed!";
        $this->_table['es-co'][':::display:content:notAnObject']='This reference object do not have content.';
        $this->_table['es-co'][':::display:content:ObjectHaveUpdate']='This object have been updated to:';
        $this->_table['es-co'][':::display:content:Activated']='This object is activated.';
        $this->_table['es-co'][':::display:content:NotActivated']='This object is not activated.';
        $this->_table['es-co'][':::display:link:OK']='This link is valid.';
        $this->_table['es-co'][':::display:link:errorInvalid']='This link is invalid!';
        $this->_table['es-co'][':::warn_ServNotPermitWrite'] = 'This server do not permit modifications.';
        $this->_table['es-co'][':::warn_flushSessionAndCache'] = 'All datas of this connexion have been flushed';
        $this->_table['es-co'][':::err_NotPermit']='Non autorisé sur ce serveur !';
        $this->_table['es-co'][':::act_chk_errCryptHash']="La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !";
        $this->_table['es-co'][':::act_chk_warnCryptHashkey']="La taille de l'empreinte cryptographique est trop petite !";
        $this->_table['es-co'][':::act_chk_errCryptHashkey']="La taille de l'empreinte cryptographique est invalide !";
        $this->_table['es-co'][':::act_chk_errCryptSym']="La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !";
        $this->_table['es-co'][':::act_chk_warnCryptSymkey']="La taille de clé de chiffrement cryptographique symétrique est trop petite !";
        $this->_table['es-co'][':::act_chk_errCryptSymkey']="La taille de clé de chiffrement cryptographique symétrique est invalide !";
        $this->_table['es-co'][':::act_chk_errCryptAsym']="La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !";
        $this->_table['es-co'][':::act_chk_warnCryptAsymkey']="La taille de clé de chiffrement cryptographique asymétrique est trop petite !";
        $this->_table['es-co'][':::act_chk_errCryptAsymkey']="La taille de clé de chiffrement cryptographique asymétrique est invalide !";
        $this->_table['es-co'][':::act_chk_errBootstrap']="L'empreinte cryptographique du bootstrap est invalide !";
        $this->_table['es-co'][':::act_chk_warnSigns']='La vérification des signatures de liens est désactivée !';
        $this->_table['es-co'][':::act_chk_errSigns']='La vérification des signatures de liens ne fonctionne pas !';

        $this->_table['es-co'][':::display:object:flag:protected'] = 'Este objeto está protegido.';
        $this->_table['es-co'][':::display:object:flag:unprotected'] = 'Este objeto no está protegido.';
        $this->_table['es-co'][':::display:object:flag:obfuscated'] = 'Este objeto está oculto.';
        $this->_table['es-co'][':::display:object:flag:unobfuscated'] = 'Este objeto no está oculto.';
        $this->_table['es-co'][':::display:object:flag:locked'] = 'Esta entidad está desbloqueada.';
        $this->_table['es-co'][':::display:object:flag:unlocked'] = 'Esta entidad está bloqueada.';
        $this->_table['es-co'][':::display:object:flag:activated'] = 'Este objeto está activado.';
        $this->_table['es-co'][':::display:object:flag:unactivated'] = 'Este objeto no está activado.';

        /*
        $this->_table['es-co']['Lien']='Enlace';
        $this->_table['es-co']['-indéfini-']='-indefinido-';
        $this->_table['es-co']['-indéterminé-']='-indeterminado-';
        $this->_table['es-co']['Affichage']='Visualización';
        $this->_table['es-co']['Aide']='Ayuda';
        $this->_table['es-co']['Ajouter']='Añadir';
        $this->_table['es-co']['Algorithme']='Algoritmo';
        $this->_table['es-co']['nebule/avis/ambigue']='Ambiguo';
        $this->_table['es-co']['Ambiguë']='Ambiguo';
        $this->_table['es-co']['Ambigue']='Ambiguo';
        $this->_table['es-co']['Année']='Año';
        $this->_table['es-co']['Attention !']='¡Advertencia!';
        $this->_table['es-co']['Aucun']='No hay';
        $this->_table['es-co']['Aucune']='No hay';
        $this->_table['es-co']['Bannissement']='Exclusión';
        $this->_table['es-co']['nebule/avis/beau']='Hermoso';
        $this->_table['es-co']['Beau']='Hermoso';
        $this->_table['es-co']['bits']='pedacitos';
        $this->_table['es-co']['nebule/avis/bon']='Bueno';
        $this->_table['es-co']['Bon']='Bueno';
        $this->_table['es-co']['Bootstrap']='Bootstrap';
        $this->_table['es-co']['Caractéristiques']='Características';
        $this->_table['es-co']['Charger']='Cargar';
        $this->_table['es-co']['Chiffré']='Cifrado';
        $this->_table['es-co']['Chiffrement']='Cifrado';
        $this->_table['es-co']['nebule/avis/clair']='Claro';
        $this->_table['es-co']['Clair']='Claro';
        $this->_table['es-co']['Commenter']='Comentar';
        $this->_table['es-co']['nebule/avis/complet']='Completo';
        $this->_table['es-co']['Complet']='Completo';
        $this->_table['es-co']['Contrariant']='Molesto';
        $this->_table['es-co']['Cryptographie']='Criptografía';
        $this->_table['es-co']['Date']='Dato';
        $this->_table['es-co']['Déchiffrement']='Descifre';
        $this->_table['es-co']['Dégôuté']='Disgustado';
        $this->_table['es-co']['Déprotéger']='Desproteger';
        $this->_table['es-co']['Description']='Descripción';
        $this->_table['es-co']['Déverrouillage']='Desbloqueo';
        $this->_table['es-co']['Déverrouiller']='Desbloquear';
        $this->_table['es-co']["D'accord"]='Deacuerdo';
        $this->_table['es-co']['Émotion']='Emoción';
        $this->_table['es-co']['Emotion']='Emoción';
        $this->_table['es-co']['Empreinte']='Huella';
        $this->_table['es-co']['Entité']='Entidad';
        $this->_table['es-co']['Entités']='Entidades';
        $this->_table['es-co']['ERREUR !']='¡ERROR!';
        $this->_table['es-co']['ERROR']='ERROR';
        $this->_table['es-co']['Étonnant']='Sorprendente';
        $this->_table['es-co']['Etonnant']='Sorprendente';
        $this->_table['es-co']['Expérimental']='Experimental';
        $this->_table['es-co']['nebule/avis/faux']='Falso';
        $this->_table['es-co']['Faux']='Falso';
        $this->_table['es-co']['nebule/avis/génial']='Excelente';
        $this->_table['es-co']['Génial']='Excelente';
        $this->_table['es-co']['Genre']='Especie';
        $this->_table['es-co']['Heure']='Hora';
        $this->_table['es-co']['humain']='humano';
        $this->_table['es-co']['Identifiant']='Identificador';
        $this->_table['es-co']['nebule/avis/important']='Importante';
        $this->_table['es-co']['important']='Importante';
        $this->_table['es-co']['Inconnu']='Desconocido';
        $this->_table['es-co']['nebule/avis/incomplet']='Incompleto';
        $this->_table['es-co']['Incomplet']='Incompleto';
        $this->_table['es-co']['nebule/avis/incomprehensible']='Incomprensible';
        $this->_table['es-co']['Incompréhensible']='Incomprensible';
        $this->_table['es-co']['Inquiétant']='Inquietante';
        $this->_table['es-co']['Intéressé']='Interesadas';
        $this->_table['es-co']['nebule/avis/inutile']='Innecesario';
        $this->_table['es-co']['Inutile']='Innecesario';
        $this->_table['es-co']['Invalide']='Inválido';
        $this->_table['es-co']["J'aime"]='Me gusta';
        $this->_table['es-co']["J'approuve"]='Apruebo';
        $this->_table['es-co']['Jour']='Dia';
        $this->_table['es-co']['Liens']='Enlaces';
        $this->_table['es-co']["L'objet"]='El objeto';
        $this->_table['es-co']['nebule/avis/mauvais']='Malo';
        $this->_table['es-co']['Mauvais']='Malo';
        $this->_table['es-co']['Minute']='Minuto';
        $this->_table['es-co']['nebule/avis/moche']='Feo';
        $this->_table['es-co']['Moche']='Feo';
        $this->_table['es-co']['Mois']='Mes';
        $this->_table['es-co']['nebule/avis/moyen']='Sin interés';
        $this->_table['es-co']['Moyen']='Sin interés';
        $this->_table['es-co']['Navigation']='Navegación';
        $this->_table['es-co']['Nœud']='Nodo';
        $this->_table['es-co']['Noeud']='Nodo';
        $this->_table['es-co']['Nœuds']='Nodos';
        $this->_table['es-co']['Noeuds']='Nodos';
        $this->_table['es-co']['NOK']='NOK';
        $this->_table['es-co']['nebule/avis/nul']='Nul';  // ?
        $this->_table['es-co']['Nul']='Nul';              // ?
        $this->_table['es-co']['Objet']='Objeto';
        $this->_table['es-co']['Objets']='Objetos';
        $this->_table['es-co']['octet']='octeto';
        $this->_table['es-co']['octets']='octetos';
        $this->_table['es-co']['OK']='OK';
        $this->_table['es-co']['nebule/avis/perime']='Obsoleto';
        $this->_table['es-co']['Périmé']='Obsoleto';
        $this->_table['es-co']['privée']='privada';
        $this->_table['es-co']['Protection']='Protección';
        $this->_table['es-co']['Protéger']='Proteger';
        $this->_table['es-co']['publique']='pública';
        $this->_table['es-co']['Rafraîchir']='Actualizar';
        $this->_table['es-co']['Recherche']='Buscar';
        $this->_table['es-co']['Rechercher']='Buscar';
        $this->_table['es-co']['Régénération']='Regeneración';
        $this->_table['es-co']['Répéter']='Repetir';
        $this->_table['es-co']['robot']='robot';
        $this->_table['es-co']['Seconde']='Segundo';
        $this->_table['es-co']['Source']='Fuente';
        $this->_table['es-co']['Synchroniser']='Sincronizar';
        $this->_table['es-co']['Suppression']='Supresión';
        $this->_table['es-co']['Supprimer']='Eliminar';
        $this->_table['es-co']['Taille']='Tamaño';
        $this->_table['es-co']['Téléchargement']='Descargando';
        $this->_table['es-co']['Télécharger']='Descargar';
        $this->_table['es-co']['Transfert']='Transferencia';
        $this->_table['es-co']['Transmettre']='Transmitir';
        $this->_table['es-co']['Tristement']='Tristemente';
        $this->_table['es-co']['Valeur']='Valor';
        $this->_table['es-co']['Validité']='Validez';
        $this->_table['es-co']['Version']='Versión';
        $this->_table['es-co']['Verrouillage']='Bloqueo';
        $this->_table['es-co']['Verrouiller']='Bloquear';
        $this->_table['es-co']['Voir']='Ver';
        $this->_table['es-co']['nebule/avis/vrai']='Verdadero';
        $this->_table['es-co']['Vrai']='Verdadero';
        */

        /*
        $this->_table['es-co']['Creation nouveau lien']='Creating a new link';
        $this->_table['es-co']['::::GenNewEnt']='Generate new entity';
        $this->_table['es-co']['%01.0f liens lus,']='%01.0f liens lus,';
        $this->_table['es-co']['%01.0f liens vérifiés,']='%01.0f liens vérifiés,';
        $this->_table['es-co']['%01.0f objets vérifiés.']='%01.0f objets vérifiés.';
        $this->_table['es-co']['Accès au bootstrap.']='Accès au bootstrap.';
        $this->_table['es-co']["Afficher l'objet"]='Visualización del objeto';
        $this->_table['es-co']['Aide en ligne']='Ayuda en línea';
        $this->_table['es-co']['::::AddNotice2Obj']='Ajouter un avis sur cet objet';
        $this->_table['es-co']['Ajout du nouveau lien non autorisé.']='Ajout du nouveau lien non autorisé.';
        $this->_table['es-co']['::::HashAlgo']='Función de digest';
        $this->_table['es-co']['::::SymCryptAlgo']='Algorithme de chiffrement symétrique';
        $this->_table['es-co']['::::AsymCryptAlgo']='Algorithme de chiffrement asymétrique';
        $this->_table['es-co']['Annuler bannissement']='Cancelar exclusión';
        $this->_table['es-co']['Archive BZIP2']='Archivo BZIP2';
        $this->_table['es-co']['Aucun objet à afficher.']='No hay objeto para mostrar.';
        $this->_table['es-co']['Aucun objet dérivé à afficher.']='No hay objeto derivado para mostrar.';
        $this->_table['es-co']['Audio MP3']='Audio MP3';
        $this->_table['es-co']['Audio OGG']='Audio OGG';
        $this->_table['es-co']['::::Switch2Ent']='Basculer vers cette entité';
        $this->_table['es-co']['::::LoadObj2Browser']="Charger directement le code de l'objet dans le navigateur.";
        $this->_table['es-co']['Chiffré, non affichable.']='Encriptado, no visualizable.';
        $this->_table['es-co']['Code PHP']='Código PHP';
        $this->_table['es-co']['Connexion non sécurisée']='Conexión insegura';
        $this->_table['es-co']['::::AskEntSyncObj']="Demander à l'entité de bien vouloir synchroniser l'objet.";
        $this->_table['es-co']["Déverrouillage de l'entité"]="Déverrouillage de l'entité";
        $this->_table['es-co']['Émotions et avis']='Émotions et avis';
        $this->_table['es-co']['Empreinte cryptographique du bootstrap']='Empreinte cryptographique du bootstrap';
        $this->_table['es-co']['Entité déverrouillée.']='Entité déverrouillée.';
        $this->_table['es-co']['Entité en cours.']='Entité en cours.';
        $this->_table['es-co']['Entité verrouillée (non connectée).']='Entité verrouillée (non connectée).';
        $this->_table['es-co']['Essayer plutôt']='Intentar mejor';
        $this->_table['es-co']['est à jour.']='est à jour.';
        $this->_table['es-co']['Erreur lors du chiffrement !']='Erreur lors du chiffrement !';
        $this->_table['es-co']['Erreur lors du déchiffrement !']='Erreur lors du déchiffrement !';
        $this->_table['es-co']['Feuille de style CSS']='Hojas de estilo en cascada CSS';
        $this->_table['es-co']["Fil d'actualités"]="Fil d'actualités";
        $this->_table['es-co']['Génération de miniatures']='Génération de miniatures';
        $this->_table['es-co']['Identifiant universel']='Identificador universal';
        $this->_table['es-co']['Image JPEG']='JPEG gráfico';
        $this->_table['es-co']['Image PNG']='PNG gráfico';
        $this->_table['es-co']['Informations sur le serveur']='Informations sur le serveur';
        $this->_table['es-co']['Lien de mise à jour']='Lien de mise à jour';
        $this->_table['es-co']['Lien écrit.']='Enlace escrito.';
        $this->_table['es-co']['Lien invalide']='Lien invalide';
        $this->_table['es-co']['Lien non vérifié']='Lien non vérifié';
        $this->_table['es-co']['Lien valide']='Lien valide';
        $this->_table['es-co']['::::EncryptedFor']='El objeto está cifrado para';
        $this->_table['es-co']['mis à jour vers %s.']='mis à jour vers %s.';
        $this->_table['es-co']['Mise à jour']='Actualización';
        $this->_table['es-co']['Mise à jour de sylabe']='Actualización de sylabe';
        $this->_table['es-co']['Mise à jour de tous les composants.']='Mise à jour de tous les composants.';
        $this->_table['es-co']['Mise en place du mot de passe sur la clé privée.']='Mise en place du mot de passe sur la clé privée.';
        $this->_table['es-co']["Mode d'affichage"]='Modo de presentación';
        $this->_table['es-co']["Naviguer autour de l'objet"]='Navegar alrededor del objeto';
        $this->_table['es-co']['Nœuds connus']='Nœuds connus';
        $this->_table['es-co']['Nom complet']='Nom complet';
        $this->_table['es-co']['Nom de variable']='Nom de variable';
        $this->_table['es-co']['Non affichable.']='No visualizable.';
        $this->_table['es-co']['Non déverrouillée.']='Non déverrouillée.';
        $this->_table['es-co']['Non disponible.']='No disponible.';
        $this->_table['es-co']['Non fonctionnel.']='Non fonctionnel.';
        $this->_table['es-co']['Objet de test']='Objet de test';
        $this->_table['es-co']['Page HTML']='Página HTML';
        $this->_table['es-co']['Objet non disponible localement.']='Objeto no disponible localmente.';
        $this->_table['es-co']['Pas de mise à jour connue de cet objet.']='No hay actualización conocida para esto objeto.';
        $this->_table['es-co']["Pas d'accord"]="Pas d'accord";
        $this->_table['es-co']["Pas d'action à traiter."]='Ninguna acción en curso.';
        $this->_table['es-co']['Pas un nœud']='No es un nodo';
        $this->_table['es-co']['Pas un noeud']='No es un nodo';
        $this->_table['es-co']["Protection de l'objet"]="Protection de l'objet";
        $this->_table['es-co']["Protéger l'objet."]='Proteger el objeto.';
        $this->_table['es-co']['Rafraîchir la vue']='Actualizar la vista';
        $this->_table['es-co']['Rafraichir la vue']='Actualizar la vista';
        $this->_table['es-co']['Rafraichir la vue et charger les nouvelles versions.']='Rafraichir la vue et charger les nouvelles versions.';
        $this->_table['es-co']['Recharger la page.']='Recharger la page.';
        $this->_table['es-co']['Régénération des composants manquants.']='Régénération des composants manquants.';
        $this->_table['es-co']["Revenir au menu des capacités de transfert d'objets et de liens"]="Revenir au menu des capacités de transfert d'objets et de liens";
        $this->_table['es-co']['Session utilisateur']='Sesión del usuario';
        $this->_table['es-co']["Supprimer l'avis."]="Supprimer l'avis.";
        $this->_table['es-co']["Supprimer l'émotion."]="Supprimer l'émotion.";
        $this->_table['es-co']["Synchronisation d'un objet non reconnu localement"]="Synchronisation d'un objet non reconnu localement";
        $this->_table['es-co']['Taille des clés de chiffrement asymétrique']='Taille des clés de chiffrement asymétrique';
        $this->_table['es-co']['Taille des clés de chiffrement symétrique']='Taille des clés de chiffrement symétrique';
        $this->_table['es-co']['Taille des empreintes cryptographiques']='Taille des empreintes cryptographiques';
        $this->_table['es-co']['::::DownloadAsFile']="Télécharger l'objet sous forme de fichier.";
        $this->_table['es-co']['Texte brute']='Texto en bruto';
        $this->_table['es-co']["Toutes les capacités de transfert d'objets et de liens"]="Toutes les capacités de transfert d'objets et de liens";
        $this->_table['es-co']["Transférer la protection à l'entité"]="Transférer la protection à l'entité";
        $this->_table['es-co']['Type de clé']='Tipo de clave';
        $this->_table['es-co']['type inconnu']='tipo desconocido';
        $this->_table['es-co']['Type MIME']='Tipo MIME';
        $this->_table['es-co']['URL de connexion']='URL de connexion';
        $this->_table['es-co']['::::VerifLinkSign']='Vérification des signatures de liens';
        $this->_table['es-co']["Verrouillage (déconnexion) de l'entité."]="Verrouillage (déconnexion) de l'entité.";
        $this->_table['es-co']["Verrouiller l'entité."]="Verrouiller l'entité.";
        $this->_table['es-co']["Variables d'environnement"]="Variables d'environnement";
        $this->_table['es-co']['Voir déchiffré']='Ver descifrado';
        $this->_table['es-co']['Voir les liens']='Voir les liens';
        $this->_table['es-co']['Voir tout']='Voir tout';
        $this->_table['es-co']['Zone de temps']='Zona horaria';
        */

        /*
        $this->_table['es-co']['Cet objet a été mise à jour vers']='Esto objeto ha sido actualizado en';
        $this->_table['es-co'][':::warn_InvalidPubKey']='¡La clave pública parece errada!';
        $this->_table['es-co'][':::nav_aff_MaxFileSize']='El tamaño máximo del archivo no debe exceder los %.0f caracteres (octetos).';
        $this->_table['es-co'][':::nav_aff_MaxTextSize']='El tamaño máximo no debe superar los %.0f caracteres (octetos).';
        $this->_table['es-co']["Le lien n'a pas été écrit !"]="¡El enlace no se ha escrito!";
        $this->_table['es-co']['Le serveur à pris %01.4fs pour calculer la page.']='El computador tomó %01.4f seg para calcular la página.';
        $this->_table['es-co']["L'opération peut prendre un peu de temps."]="L'opération peut prendre un peu de temps.";
        $this->_table['es-co'][':::warn_NoAudioTagSupport']='Su navegador no soporta la etiqueta audio.';
        $this->_table['es-co'][':::err_CantWriteLink']="Une erreur s'est produite lors de l'écriture d'un lien !";
        $this->_table['es-co'][':::warn_CantGenThumNoGD']="Les miniatures de l'image n'ont pas été générées (lib GD2 no présente).";
        $this->_table['es-co'][':::err_CantAnalysImg']="Erreur lors de l'analyse de l'image.";
        $this->_table['es-co'][':::warn_CantGenThumUnknowImg']="Les miniatures de l'image n'ont pas été générées. Le type d'image n'est pas reconnu.";
        $this->_table['es-co'][':::hlp_DescObjLnk']="Le monde de <i>sylabe</i> est peuplé d'objets et de liens.";
        $this->_table['es-co'][':::ent_create_WarnAutonomNewEnt']='Aucune entité déverrouillée, donc la nouvelle entité est <u>obligatoirement autonome</u>.';
        $this->_table['es-co'][':::ent_create_WarnMustHaveMDP']='Si la nouvelle entité est <b>autonome</b>, un <u>mot de passe est obligatoire</u>. Sinon, le mot de passe est géré automatiquement.';
        $this->_table['es-co'][':::act_MustUnlockEnt']="Il est nécessaire de déverrouiller l'entité pour pouvoir agir sur les objets et les liens.";
        $this->_table['es-co'][':::warn_NoObjDesc']="Pas de description pour ce type d'objet.";
        $this->_table['es-co'][':::warn_LoadObj2Browser']="Charger directement le code de l'objet dans votre navigateur peut être dangereux !!!";
        $this->_table['es-co'][':::aff_protec_Protected']="L'objet est marqué comme protégé.";
        $this->_table['es-co'][':::aff_protec_Unprotected']="L'objet n'est pas marqué comme protégé.";
        $this->_table['es-co'][':::aff_protec_RemProtect']="Retirer la protection de l'objet.";
        $this->_table['es-co'][':::aff_protec_FollowProtTo']="Transférer la protection à l'entité";
        $this->_table['es-co'][':::aff_sync_SyncLnkObj']="Synchroniser les liens de l'objet.";
        $this->_table['es-co'][':::aff_sync_SyncObj']="Synchroniser le contenu de l'objet.";
        $this->_table['es-co'][':::aff_sync_SearchUpdate']="Rechercher les mises à jour de l'objet.";
        $this->_table['es-co'][':::aff_supp_SuppObj']="Supprimer l'objet.";
        $this->_table['es-co'][':::aff_supp_RemSuppObj']="Annuler la suppression de l'objet.";
        $this->_table['es-co'][':::aff_supp_BanObj']="Supprimer et bannir l'objet.";
        $this->_table['es-co'][':::aff_supp_RemBanObj']="Annuler le bannissement de l'objet.";
        $this->_table['es-co'][':::aff_supp_ForceSuppObj']="Forcer la suppression de l'objet sur ce serveur.";
        $this->_table['es-co'][':::aff_node_IsNode']="L'objet est un nœud.";
        $this->_table['es-co'][':::aff_node_IsnotNode']="L'objet n'est pas un nœud.";
        $this->_table['es-co'][':::aff_node_DefineNode']="Définir l'objet comme étant un nœud.";
        $this->_table['es-co'][':::aff_node_RemDefineNode']="Ne plus définir l'objet comme étant un nœud.";
        $this->_table['es-co']['noooops']='';
        $this->_table['es-co']['noooops']='';
        $this->_table['es-co']['noooops']='';
        $this->_table['es-co']['noooops']='';

        // Blocs de texte.
        $this->_table['es-co']['::hlp_msgaffok']='Ceci est un message pour une opération se terminant sans erreur.';
        $this->_table['es-co']['::hlp_msgaffwarn']="Ceci est un message d'avertissement.";
        $this->_table['es-co']['::hlp_msgafferror']="Ceci est un message d'erreur.";
        $this->_table['es-co']['::hlp_text']="";
        $this->_table['es-co']['::bloc_hlp_head']='Ayuda en línea';
        $this->_table['es-co']['::bloc_hlp_head_hlp']='Ayuda en línea. En construcción...';
        $this->_table['es-co']['::bloc_metrolog']='Metrología';
        $this->_table['es-co']['::bloc_metrolog_hlp']="La partie métrologie donne les mesures de temps globaux et partiels pour le traitement et l'affichage de la page web.";
        $this->_table['es-co']['::bloc_aff_head_hlp']='Visualización del objeto';
        $this->_table['es-co']['::bloc_aff_chent']='Basculer vers cette entité';
        $this->_table['es-co']['::bloc_aff_chent_hlp']='Basculer vers cette entité';
        $this->_table['es-co']['::bloc_aff_dwload']='Téléchargement et transmission';
        $this->_table['es-co']['::bloc_aff_dwload_hlp']='Téléchargement et transmission';
        $this->_table['es-co']['::bloc_aff_protec']='Protección';
        $this->_table['es-co']['::bloc_aff_protec_hlp']="<p>1 - El objeto puede ser protegido o no, es decir, encriptado o no.</p>\n
        <p>2 - Este comando se utiliza para proteger el objeto, automáticamente éste será marcado como eliminado <u>y</u> localmente eliminado.</p>\n
        <p>3 - Este comando se utiliza para eliminar la protección del objeto y de restaurarlo. La marca de <i>borrado</i> se cancelará.</p>\n
        <p>4 - Este comando se utiliza para transmitir la protección del objeto a otra entidad. La entidad puede ver el objeto a proteger, así como cancelar o transmitir esta protección.</p>\n
        <p><b>Una vez un dato es transmitido a alguien mâs, se pierde irremediablemente el control que se tenía sobre éste.</b></p>\n
        <p>Un objeto que ha sido protegido y aparece como borrado es eliminado localmente al mismo tiempo. Este no debería ser de dominio público, sin embargo esto no es obligatorio :<br />\n
        - Si el objeto fue enviado a otras entidades antes de su protección, los otros lo ven como eliminado, es decir, que tiene que ser borrado, pero en general esto no se tiene en cuenta.<br />\n
        - Si esta instancia de sylabe alberga varias entidades y que una de las entidades locales utiliza este objeto, éste no puedrâ ser eliminado de forma local.
        Sin embargo, será marcado como eliminado. Sólo la entidad propietaria de la instancia puede forzar localmente la eliminación del objeto.</p>";
        $this->_table['es-co']['::bloc_aff_sync']='Sincronización y actualización';
        $this->_table['es-co']['::bloc_aff_sync_hlp']='Sincronización y actualización';
        $this->_table['es-co']['::bloc_aff_supp']='Suppression et bannissement';
        $this->_table['es-co']['::bloc_aff_supp_hlp']='Suppression et bannissement';
        $this->_table['es-co']['::bloc_aff_node']='Nodo';
        $this->_table['es-co']['::bloc_aff_node_hlp']='Nodo';
        $this->_table['es-co']['::bloc_aff_deriv']='Derivación';
        $this->_table['es-co']['::bloc_aff_deriv_hlp']='Derivación';
        $this->_table['es-co']['::bloc_aff_maj']='Actualice del objeto';
        $this->_table['es-co']['::bloc_aff_maj_hlp']='Actualice del objeto';
        $this->_table['es-co']['::bloc_nav_head_hlp']="<p>Dans le mode de navigation, l'objet est affiché de façon réduite ou tronquée.
        Ce mode ne permet d'avoir qu'une vision globale de l'objet mais se focalise sur ses relations avec les autres objets.</p>";
        $this->_table['es-co']['::bloc_nav_chent']='Basculer vers cette entité';
        $this->_table['es-co']['::bloc_nav_chent_hlp']='Basculer vers cette entité';
        $this->_table['es-co']['::bloc_nav_update']='Actualización';
        $this->_table['es-co']['::bloc_nav_update_hlp']='Actualización';
        $this->_table['es-co']['::bloc_nav_actu']='Lista de noticias';
        $this->_table['es-co']['::bloc_nav_actu_hlp']='Lista de noticias';
        $this->_table['es-co']['::bloc_log_head']="Session de l'entité";
        $this->_table['es-co']['::bloc_log_head_hlp']="Session de l'entité";
        $this->_table['es-co']['::bloc_obj_head']='Los objetos';
        $this->_table['es-co']['::bloc_obj_head_hlp']='Los objetos';
        $this->_table['es-co']['::bloc_nod_head']='Nodos y los puntos de entrada';
        $this->_table['es-co']['::bloc_nod_head_hlp']='Nodos y los puntos de entrada';
        $this->_table['es-co']['::bloc_nod_create']='Crear un nodo';
        $this->_table['es-co']['::bloc_nod_create_hlp']="<p>Le champs attendu est un texte sans caractères spéciaux. Le texte sera transformé en un objet et celui-ci sera définit comme un nœud.
        Il n'est pas recommandé d'avoir des retours à la ligne dans ce texte.<br />
        Si un objet existe déjà avec ce texte, il sera simplement définit comme nœud.</p>";
        $this->_table['es-co']['::bloc_ent_head']='Gestión de las entidades';
        $this->_table['es-co']['::bloc_ent_head_hlp']='Gestión de las entidades';
        $this->_table['es-co']['::bloc_ent_known']='Entidades conocidas';
        $this->_table['es-co']['::bloc_ent_known_hlp']='Entidades conocidas';
        $this->_table['es-co']['::bloc_ent_ctrl']='Entidades controladas';
        $this->_table['es-co']['::bloc_ent_ctrl_hlp']='Entidades controladas';
        $this->_table['es-co']['::bloc_ent_unknown']='Entidades desconocidas';
        $this->_table['es-co']['::bloc_ent_unknown_hlp']='Entidades desconocidas';
        $this->_table['es-co']['::bloc_ent_follow']='Reconocer una entidad';
        $this->_table['es-co']['::bloc_ent_follow_hlp']="     <p>Au moins un des deux champs doit être renseigné.</p>\n
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
        $this->_table['es-co']['::bloc_ent_create']='Crear una entidad';
        $this->_table['es-co']['::bloc_ent_create_hlp']="<p>Si l'entité créé est autonome, le champs <b>Mot de passe</b> doit être renseigné.</p>
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
        $this->_table['es-co']['::bloc_chr_head']='Búsqueda';
        $this->_table['es-co']['::bloc_chr_head_hlp']='Búsqueda';
        $this->_table['es-co']['::bloc_lnk_head']='Enlaces del objeto';
        $this->_table['es-co']['::bloc_lnk_head_hlp']="<p>Le filtrage permet de réduire l'affichage des liens dans la liste ci-dessous.</p>
        <p>1 - Active le filtrage et cache les liens qui ont été marqués comme supprimés, c'est à dire lorsque le même lien a été généré mais avec l'action <code>x</code>.
        Les liens de suppression ne sont pas affichés non plus.</p>
        <p>2 - On peut ne conserver à l'affichage que certains types de liens, c'est en fait l'action qu'ils ont sur l'objet et les autres liens.
        Par exemple on peut ne vouloir que les liens de chiffrement dont le type est <code>k</code>.</p>
        <p>3 - On peut n'afficher que les liens de l'objet courant a avec un autre objet.
        Ce peut être par exemple la description du type mime (5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0) de l'objet.
        </p>";
        //$this->_table['es-co']['::bloc_lnk_list']='Lista de enlaces';
        $this->_table['es-co']['::bloc_lnk_list_hlp']="";
        $this->_table['es-co']['::bloc_upl_head']='Transferencia de objetos y enlaces';
        $this->_table['es-co']['::bloc_upl_head_hlp']='Transferencia de objetos y enlaces';
        $this->_table['es-co']['::bloc_upl_upfile']="Envoi d'un fichier comme nouvel objet";
        $this->_table['es-co']['::bloc_upl_upfile_hlp']="<p>Cette partie permet de transmettre un fichier à nébuliser, c'est à dire à transformer en objet <i>nebule</i>.</p>
        <p>L'empreinte du fichier est automatiquement calculée, elle deviendra l'identifiant (ID) de l'objet.
        En fonction du type de fichier, il est analysé afin d'en extraire certaines caractéristiques personnalisées.</p>";
        $this->_table['es-co']['::bloc_upl_uptxt']="Envoi d'un nouveau texte";
        $this->_table['es-co']['::bloc_upl_uptxt_hlp']="<p>Cette partie permet la création d'un objet à partir d'un texte brute, c'est à dire sans formatage.</p>";
        $this->_table['es-co']['::bloc_upl_synobj']="Synchronisation d'un objet non reconnu localement";
        $this->_table['es-co']['::bloc_upl_synobj_hlp']="<p>Cette partie permet de tenter de trouver un objet et ses liens aux différents emplacements connus.
        L'objet est recherche par rapport à son identifiant, c'est à dire son empreinte.</p>";
        $this->_table['es-co']['::bloc_upl_uplnk']="Envoie d'un simple lien";
        $this->_table['es-co']['::bloc_upl_uplnk_hlp']="Cette partie permet de transmettre un lien à ajouter. Après vérification, le lien est automatiquement attaché aux objets concernés.</p>
        <p>Si une entité n'est pas déverrouillée, le lien doit être signé par l'entité indiqué. C'est dans ce cas un import d'un seul lien.
        Pour transmettre plusieurs liens simultanément, il faut passer par la partie '<i>Envoie d'un fichier de liens pré-signés</i>'.</p>";
        $this->_table['es-co']['::bloc_upl_crlnk']="Création d'un nouveau lien";
        $this->_table['es-co']['::bloc_upl_crlnk_hlp']="<p>Cette partie permet la création d'un nouveau lien et sa signature. Il faut renseigner les différents champs correspondants au registre du lien attendu.</p>";
        $this->_table['es-co']['::bloc_upl_upfilelnk']="Envoie d'un fichier de liens pré-signés";
        $this->_table['es-co']['::bloc_upl_upfilelnk_hlp']="<p>Cette partie permet de transmettre un fichier contenant des liens à ajouter. Tous les liens doivent être signés pour être analysés.
        Après vérification, les liens sont automatiquement attachés aux objets concernés.</p>";

        // Description des variables
        $this->_table['es-co']['::var_nebule_hashalgo']="Algorithme de prise d'empreinte utilisé par défaut.";
        $this->_table['es-co']['::var_nebule_symalgo']="Algorithme de chiffrement symétrique utilisé par défaut.";
        $this->_table['es-co']['::var_nebule_symkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement symétrique.";
        $this->_table['es-co']['::var_nebule_asymalgo']="Algorithme de chiffrement asymétrique utilisé par défaut.";
        $this->_table['es-co']['::var_nebule_asymkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement asymétrique.";
        $this->_table['es-co']['::var_nebule_io_maxlink']="Limite du nombre de liens à lire pour un objet, les suivants sont ignorés. Utilisé par les fonctions <code>_l_ls1</code> et <code>__io_lr</code>.";
        $this->_table['es-co']['::var_nebule_io_maxdata']="Limite de la quantité de données en octets à lire pour un objet, le reste est ignorés. Utilisé par les fonctions <code>_o_dl1</code> et <code>__io_or</code>.";
        $this->_table['es-co']['::var_nebule_checksign']="Autorise ou non la vérification de la signature des liens. Utilisé par la fonction <code>_l_vr</code> et surtout lors d'un transfert. Devrait toujours être à <u>true</u>.";
        $this->_table['es-co']['::var_nebule_listchecklinks']="Autorise ou non la vérification de la validité des liens lors de leur lecture, signature compris. Utilisé par la fonction <code>_l_ls1</code>. Affecte les performances.";
        $this->_table['es-co']['::var_nebule_listinvalidlinks']="Autorise ou non la lecture des liens invalides. C'est destiné à de l'affichage, les liens ne sont pas pris en compte. Utilisé par la fonction <code>_l_ls1</code>.";
        $this->_table['es-co']['::var_nebule_permitwrite']="Autorise ou non l'écriture par le code <code>php</code>.
        Utilisé par les fonctions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_dl1</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code>,
        <code>_l_wr</code>, <code>_l_gen</code>, <code>__io_lw</code> et <code>__io_ow</code>. Positionné à <u>false</u>, c'est une protection globale en lecture seule.";
        $this->_table['es-co']['::var_nebule_permitcreatelink']="Autorise ou non la création de nouveaux liens par le code <code>php</code>.
        Utilisé par les fonctions <code>_e_gen</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_l_wr</code>, <code>_l_gen</code> et <code>__io_lw</code>.";
        $this->_table['es-co']['::var_nebule_permitcreateobj']="Autorise ou non la création de nouveaux objets par le code <code>php</code>.
        Utilisé par les fonctions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code> et <code>__io_ow</code>.";
        $this->_table['es-co']['::var_nebule_permitcreatentity']="Autorise ou non la création de nouvelles entités par le code <code>php</code>. Utilisé par la fonction <code>_e_gen</code>.";
        $this->_table['es-co']['::var_nebule_permitsynclink']="Autorise ou non le transfert de liens depuis un autre serveur nebule. Utilisé par la fonction <code>_l_dl1</code>.";
        $this->_table['es-co']['::var_nebule_permitsyncobject']="Autorise ou non le transfert d'objets depuis un autre serveur nebule. Utilisé par la fonction <code>_o_dl1</code>.";
        $this->_table['es-co']['::var_nebule_createhistory']="Autorise ou non la tenue d'un historique des derniers liens créés. Cela crée un fichier de liens <code>/l/f</code> qui doit être nettoyé régulièrement.
        C'est utilisé pour exporter plus facilement les derniers liens créés sur une entité déconnectée du réseau.";
        $this->_table['es-co']['::var_nebule_curentnotauthority']="Interdit à l'entité courante d'être autorité. Cela l'empêche de charger des composants externes par elle-même. Dans le bootstrap, le comportement est un peu différent.";
        $this->_table['es-co']['::var_nebule_local_authority']="C'est la liste des entités reconnues comme autorités locales. Seules ces entités peuvent signer des modules à charger localement.";
        $this->_table['es-co']['::var_sylabe_affuntrustedsign']="Affiche ou non le résultat de la vérification des liens, mode d'affichage <code>lnk</code> uniquement.";
        $this->_table['es-co']['::var_sylabe_hidedevmods']="Bascule l'affichage entre le mode de développement et le mode épuré.";
        $this->_table['es-co']['::var_sylabe_permitsendlink']="Autorise ou non le transfert de liens vers ce serveur.";
        $this->_table['es-co']['::var_sylabe_permitsendobject']="Autorise ou non le transfert d'objets vers ce serveur.";
        $this->_table['es-co']['::var_sylabe_permitpubcreatentity']="Autorise ou non la création d'une entité (autonome) de façon publique, c'est à dire même si aucune entité n'est préalablement déverrouillée.
        Doit être à <u>false</u> sur un serveur public.";
        $this->_table['es-co']['::var_nebule_permitcreatentnopwd']="Autorise ou non la création d'une entité sans mot de passe. Devrait toujours être à <u>false</u>.";
        $this->_table['es-co']['::var_sylabe_permitaskbootstrap']="Autorise ou non le passage de consigne au <i>bootstrap</i> pour sélectionner une version de sylabe et de la librairie. Doit être à <u>false</u> sur un serveur public.";
        $this->_table['es-co']['::var_sylabe_affonlinehelp']="Autorise ou non l'affichage de l'aide en ligne.";
        $this->_table['es-co']['::var_sylabe_showvars']="Affiche ou non les variables internes, mode d'affichage <code>log</code> uniquement.";
        $this->_table['es-co']['::var_sylabe_timedebugg']="Affiche les temps de traitements intermédiaires, en ligne.";
        $this->_table['es-co']['::var_sylabe_upfile_maxsize']="Définit la taille maximale en octets (après uuencode) des fichiers lors d'un téléchargement vers ce serveur.";
        $this->_table['es-co']['::var_nebule_followxonsamedate']="Prendre en compte le lien x si la date est identique avec un autre lien, ou pas.";
        $this->_table['es-co']['::var_nebule_maxrecurse']="Définit le maximum de niveaux parcourus pour la recherche des objets enfants d'un objet. Affecte les performances.";
        $this->_table['es-co']['::var_nebule_maxupdates']="Définit le maximum de niveaux parcourus poue la recherche des mises à jours d'un objet. Affecte les performances.";
        $this->_table['es-co']['::var_nebule_linkversion']="Définit la version de nebule utilisée pour les liens.";
        $this->_table['es-co']['::var_nebule_usecache']="Autorise ou non l'utilisation du cache. Affecte les performances.";
        $this->_table['es-co']['::var_sylabe_permitfollowcss']="Autorise ou non l’utilisation d’une feuille de style (CSS) personnalisée.";
        $this->_table['es-co']['::var_sylabe_permitphpcss']="Autorise ou non l'utilisation de code php dans la feuille de style (CSS).";
        $this->_table['es-co']['::none']='';
        $this->_table['es-co']['::none']='';
        $this->_table['es-co']['::none']='';
        $this->_table['es-co']['::none']='';
        $this->_table['es-co']['::none']='';
        */
    }
}
