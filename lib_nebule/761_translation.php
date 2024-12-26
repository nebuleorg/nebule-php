<?php
declare(strict_types=1);
namespace Nebule\Library;

use Nebule\Application\Autent\Application;
use Nebule\Application\Autent\Translate;

/**
 * Classe Traductions
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Translates extends Functions
{
    const DEFAULT_COMMAND_LANGUAGE = 'lang';
    const DEFAULT_LANGUAGE = 'en-en';
    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::::Welcome' => 'Bienvenue',
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Message',
            '::::WARN' => 'ATTENTION !',
            '::::ERROR' => 'ERREUR !',
            '::::RESCUE' => 'Mode de sauvetage !',
            '::::GO' => 'Avancer',
            '::::BACK' => 'Revenir',
            '::::SecurityChecks' => 'Tests de sécurité',
            '::::icon:DEFAULT_ICON_LO' => 'Objet',
            '::::HtmlHeadDescription' => 'Page web cliente sylabe pour nebule.',
            '::::Experimental' => '[Experimental]',
            '::::Developpement' => '[En cours de développement]',
            '::::help' => 'Aide',
            '::::Password' => 'Mot de passe',
            '::::IDprivateKey' => 'ID privé',
            '::::IDpublicKey' => 'ID public',
            '::::display:content:errorBan' => 'Cet objet est banni, il ne peut pas être affiché !',
            '::::display:content:warningTaggedWarning' => 'Cet objet est marqué comme dangereux, attention à son contenu !',
            '::::display:content:ObjectProctected' => 'Cet objet est protégé !',
            '::::display:content:warningObjectProctected' => 'Cet objet est marqué comme protégé, attention à la divulgation de son contenu en public !!!',
            '::::display:content:OK' => "Le contenu de l'objet est valide.",
            '::::display:content:warningTooBig' => "Cet objet est trop volumineux, son contenu n'a pas été vérifié !",
            '::::display:content:errorNotDisplayable' => 'Cet objet ne peut pas être affiché !',
            '::::display:content:errorNotAvailable' => "Cet objet n'est pas disponible, il ne peut pas être affiché !",
            '::::display:content:notAnObject' => "Cet objet de référence n'a pas de contenu.",
            '::::display:content:ObjectHaveUpdate' => 'Cet objet a été mis à jour vers :',
            '::::display:content:Activated' => 'Cet objet est activé.',
            '::::display:content:NotActivated' => 'Cet objet est désactivé.',
            '::::display:link:OK' => 'Ce lien est valide.',
            '::::display:link:errorInvalid' => "Ce lien n'est pas valide !",
            '::::warn_ServNotPermitWrite' => "Ce serveur n'autorise pas les modifications.",
            '::::warn_flushSessionAndCache' => "Toutes les données de connexion ont été effacées.",
            '::::err_NotPermit' => 'Non autorisé sur ce serveur !',
            '::::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            '::::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            '::::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            '::::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            '::::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            '::::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            '::::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            '::::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            '::::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
            '::::info_OnlySignedLinks' => 'Uniquement des liens signés !',
            '::::info_OnlyLinksFromCodeMaster' => 'Uniquement les liens signés du maître du code !',
            '::::display:object:flag:protected' => 'Cet objet est protégé.',
            '::::display:object:flag:unprotected' => "Cet objet n'est pas protégé.",
            '::::display:object:flag:obfuscated' => 'Cet objet est dissimulé.',
            '::::display:object:flag:unobfuscated' => "Cet objet n'est pas dissimulé.",
            '::::display:object:flag:locked' => 'Cet entité est déverrouillée.',
            '::::display:object:flag:unlocked' => 'Cet entité est verrouillée.',
            '::::display:object:flag:activated' => 'Cet objet est activé.',
            '::::display:object:flag:unactivated' => "Cet objet n'est pas activé.",
            '::::list:empty' => 'Liste vide',
            '::::yes' => 'Oui',
            '::::no' => 'Non',
            '::::lock' => 'Verrouiller',
            '::::unlock' => 'Déverrouiller',
            '::::entity:locked' => 'Entité verrouillée. Déverrouiller ?',
            '::::entity:unlocked' => 'Entité déverrouillée. Verrouiller ?',
        ],
        'en-en' => [
            '::::Welcome' => 'Welcome',
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Message',
            '::::WARN' => 'WARNING!',
            '::::ERROR' => 'ERROR!',
            '::::RESCUE' => 'Rescue mode!',
            '::::GO' => 'Continue',
            '::::BACK' => 'Go back',
            '::::SecurityChecks' => 'Security checks',
            '::::icon:DEFAULT_ICON_LO' => 'Object',
            '::::HtmlHeadDescription' => 'Client web page sylabe for nebule.',
            '::::Experimental' => '[Experimental]',
            '::::Developpement' => '[Under developpement]',
            '::::help' => 'Help',
            '::::Password' => 'Password',
            '::::IDprivateKey' => 'Private ID',
            '::::IDpublicKey' => 'Public ID',
            '::::display:content:errorBan' => "This object is banned, it can't be displayed!",
            '::::display:content:warningTaggedWarning' => "This object is marked as dangerous, be carfull with it's content!",
            '::::display:content:ObjectProctected' => "This object is marked as protected!",
            '::::display:content:warningObjectProctected' => "This object is marked as protected, be careful when it's content is displayed in public!",
            '::::display:content:OK' => 'The object content is valid.',
            '::::display:content:warningTooBig' => "This object is too big, it's content have not been checked!",
            '::::display:content:errorNotDisplayable' => "This object can't be displayed!",
            '::::display:content:errorNotAvailable' => "This object is not available, it can't be displayed!",
            '::::display:content:notAnObject' => 'This reference object do not have content.',
            '::::display:content:ObjectHaveUpdate' => 'This object have been updated to:',
            '::::display:content:Activated' => 'This object is activated.',
            '::::display:content:NotActivated' => 'This object is not activated.',
            '::::display:link:OK' => 'This link is valid.',
            '::::display:link:errorInvalid' => 'This link is invalid!',
            '::::warn_ServNotPermitWrite' => 'This server do not permit modifications.',
            '::::warn_flushSessionAndCache' => 'All datas of this connexion have been flushed.',
            '::::err_NotPermit' => 'Non autorisé sur ce serveur !',
            '::::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            '::::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            '::::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            '::::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            '::::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            '::::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            '::::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            '::::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            '::::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
            '::::info_OnlySignedLinks' => 'Only signed links!',
            '::::info_OnlyLinksFromCodeMaster' => 'Only links signed by the code master!',
            '::::display:object:flag:protected' => 'This object is protected.',
            '::::display:object:flag:unprotected' => 'This object is not protected.',
            '::::display:object:flag:obfuscated' => 'This object is obfuscated.',
            '::::display:object:flag:unobfuscated' => 'This object is not obfuscated.',
            '::::display:object:flag:locked' => 'This entity is unlocked.',
            '::::display:object:flag:unlocked' => 'This entity is locked.',
            '::::display:object:flag:activated' => 'This object is activated.',
            '::::display:object:flag:unactivated' => 'This object is not activated.',
            '::::list:empty' => 'Empty list',
            '::::yes' => 'Yes',
            '::::no' => 'No',
            '::::lock' => 'Locking',
            '::::unlock' => 'Unlocking',
            '::::entity:locked' => 'Entity locked. Unlock?',
            '::::entity:unlocked' => 'Entity unlocked. Lock?',
        ],
        'es-co' => [
            '::::Welcome' => 'Bienvenido',
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Mensaje',
            '::::WARN' => '¡ADVERTENCIA!',
            '::::ERROR' => '¡ERROR!',
            '::::RESCUE' => '¡Modo de rescate!',
            '::::GO' => 'Avanzar',
            '::::BACK' => 'Volver atrás',
            '::::SecurityChecks' => 'Controles de seguridad',
            '::::icon:DEFAULT_ICON_LO' => 'Objeto',
            '::::HtmlHeadDescription' => 'Página web cliente sylabe para nebule.',
            '::::Experimental' => '[Experimental]',
            '::::Developpement' => '[Under developpement]',
            '::::help' => 'Ayuda',
            '::::Password' => 'Contraseña',
            '::::IDprivateKey' => 'ID privé',
            '::::IDpublicKey' => 'ID public',
            '::::display:content:errorBan' => "This object is banned, it can't be displayed!",
            '::::display:content:warningTaggedWarning' => "This object is marked as dangerous, be carfull with it's content!",
            '::::display:content:ObjectProctected' => "This object is marked as protected!",
            '::::display:content:warningObjectProctected' => "This object is marked as protected, be careful when it's content is displayed in public!",
            '::::display:content:OK' => 'The object content is valid.',
            '::::display:content:warningTooBig' => "This object is too big, it's content have not been checked!",
            '::::display:content:errorNotDisplayable' => "This object can't be displayed!",
            '::::display:content:errorNotAvailable' => "This object is not available, it can't be displayed!",
            '::::display:content:notAnObject' => 'This reference object do not have content.',
            '::::display:content:ObjectHaveUpdate' => 'This object have been updated to:',
            '::::display:content:Activated' => 'This object is activated.',
            '::::display:content:NotActivated' => 'This object is not activated.',
            '::::display:link:OK' => 'This link is valid.',
            '::::display:link:errorInvalid' => 'This link is invalid!',
            '::::warn_ServNotPermitWrite' => 'This server do not permit modifications.',
            '::::warn_flushSessionAndCache' => 'All datas of this connexion have been flushed',
            '::::err_NotPermit' => 'Non autorisé sur ce serveur !',
            '::::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            '::::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            '::::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            '::::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            '::::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            '::::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            '::::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            '::::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            '::::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
            '::::info_OnlySignedLinks' => 'Only signed links!',
            '::::info_OnlyLinksFromCodeMaster' => 'Only links signed by the code master!',
            '::::display:object:flag:protected' => 'Este objeto está protegido.',
            '::::display:object:flag:unprotected' => 'Este objeto no está protegido.',
            '::::display:object:flag:obfuscated' => 'Este objeto está oculto.',
            '::::display:object:flag:unobfuscated' => 'Este objeto no está oculto.',
            '::::display:object:flag:locked' => 'Esta entidad está desbloqueada.',
            '::::display:object:flag:unlocked' => 'Esta entidad está bloqueada.',
            '::::display:object:flag:activated' => 'Este objeto está activado.',
            '::::display:object:flag:unactivated' => 'Este objeto no está activado.',
            '::::list:empty' => 'Empty list',
            '::::yes' => 'Si',
            '::::no' => 'No',
            '::::lock' => 'Locking',
            '::::unlock' => 'Unlocking',
            '::::entity:locked' => 'Entidad bloqueada. Desbloquear?',
            '::::entity:unlocked' => 'Entidad desbloqueada. Bloquear?',
        ],
    ];

    protected bool $_useModules = false;
    protected ?ApplicationModules $_applicationModulesInstance = null;
    protected string $_currentLanguage = '';
    protected string $_currentLanguageIcon = '';
    protected string $_defaultLanguage = '';
    protected array $_languageList = array();
    protected ?Translates $_currentLanguageInstance = null;
    protected ?Translates $_defaultLanguageInstance = null;

    /*public function __construct(Applications $applicationInstance) {
        $this->_applicationInstance = $applicationInstance;
    }*/
    public function __toString() { return 'Traduction'; }
    public function __sleep() { return array(); } // TODO do not cache



    protected function _initialisation(): void {
        $this->_metrologyInstance->addLog('initialisation translate', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c845beb4');
        $this->_applicationModulesInstance = $this->_applicationInstance->getApplicationModulesInstance();

        $this->_findDefaultLanguage();
        $this->_findLanguages();
        $this->_findCurrentLanguage();
    }



    protected function _findDefaultLanguage(): void {
        $this->_defaultLanguage = self::DEFAULT_LANGUAGE;

        if ($this->_applicationInstance::USE_MODULES) {
            foreach ($this->_applicationModulesInstance->getModulesTranslateListInstances() as $module) {
                if ($module->getCommandName() == $this->_defaultLanguage) {
                    $this->_defaultLanguageInstance = $module;
                }
            }
        }
        $this->_metrologyInstance->addLog('Find default language : ' . $this->_defaultLanguage, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '376c6fa5');
    }


    protected function _findLanguages(): void {
        if ($this->_applicationInstance::USE_MODULES) {
            foreach ($this->_applicationModulesInstance->getModulesListInstances() as $module) {
                if ($module->getType() == 'traduction') {
                    $this->_languageList[$module->getCommandName()] = $module->getCommandName();
                    $this->_applicationModulesInstance->getModulesTranslateListInstances()[$module->getCommandName()];
                    $this->_metrologyInstance->addLog('Find new language : ' . $module->getCommandName(), Metrology::LOG_LEVEL_DEVELOP, __METHOD__, '7e21187d');
                }
            }
        } else
            $this->_languageList = array(self::DEFAULT_LANGUAGE);
    }


    /**
     * La langue d'affichage de l'interface.
     *
     * La recherche de la langue se fait en lisant la langue demandée dans l'URL,
     *   puis en la comparant aux modules de traductions présents.
     *
     * @return void
     */
    protected function _findCurrentLanguage(): void {
        $arg_lang = filter_input(INPUT_GET, self::DEFAULT_COMMAND_LANGUAGE, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

        if ($arg_lang != ''
            && strlen($arg_lang) != 5
            && substr($arg_lang, 2, 1) != '-'
        )
            $arg_lang = '';

        $ok_lang = false;
        $lang_instance = '';
        foreach ($this->_languageList as $lang) {
            if ($arg_lang == $lang) {
                $ok_lang = true;
                if ($this->_applicationInstance::USE_MODULES)
                    $lang_instance = $this->_applicationModulesInstance->getModulesTranslateListInstances()[$lang];
                break;
            }
        }

        if ($ok_lang) {
            $this->_currentLanguage = $arg_lang;
            $this->_currentLanguageInstance = $lang_instance;
            $this->_sessionInstance->setSessionStore($this->_applicationInstance->getClassName() . 'DisplayLanguage', $arg_lang);
        } else {
            $cache = $this->_sessionInstance->getSessionStore($this->_applicationInstance->getClassName() . 'DisplayLanguage');
            if ($cache !== false
                && $cache != ''
            ) {
                $this->_currentLanguage = $cache;
                $this->_currentLanguageInstance = $this->_applicationModulesInstance->getModulesTranslateListInstances()[$cache]; // FIXME en-en not exist
            } else {
                $this->_currentLanguage = $this->_defaultLanguage;
                $this->_currentLanguageInstance = $this->_applicationModulesInstance->getModulesTranslateListInstances()[$this->_defaultLanguage];
                $this->_sessionInstance->setSessionStore($this->_applicationInstance->getClassName() . 'DisplayLanguage', $this->_defaultLanguage);
            }
            unset($cache);
        }

        $this->_metrologyInstance->addLog('Find current language : ' . $this->_currentLanguage, Metrology::LOG_LEVEL_DEVELOP, __METHOD__, '0edc11b2');
    }

    public function getLanguageList(): array { return $this->_languageList; }
    public function getDefaultLanguage(): string { return $this->_defaultLanguage; }
    public function getCurrentLanguage(): string { return $this->_currentLanguage; }
    public function getCurrentLanguageIcon(): string { return $this->_currentLanguageIcon; }
    public function getLanguageModuleInstanceList(): array { return $this->_applicationModulesInstance->getModulesTranslateListInstances(); }
    public function getCurrentLanguageInstance(): ?Translates { return $this->_currentLanguageInstance; }
    public function getDefaultLanguageInstance(): ?Translates  { return $this->_defaultLanguageInstance; }


    public function getTranslate(string $text, string $lang = ''): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('translate (' . $lang .  ') : ' . substr($text, 0, 250), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if ($lang == '')
            $lang = $this->_currentLanguage;

        if (str_starts_with($text, '<'))
            return $text;

        $classAppTranslate = $this->_applicationInstance->getNamespace() . '\\' . 'Translate';

        if (str_starts_with($text, '::::') && isset(Translates::TRANSLATE_TABLE[$lang][$text]))
            return Translates::TRANSLATE_TABLE[$lang][$text];
        if (str_starts_with($text, ':::') && isset($classAppTranslate::TRANSLATE_TABLE[$lang][$text]))
            return $classAppTranslate::TRANSLATE_TABLE[$lang][$text];
        if (str_starts_with($text, '::')
            && is_a($this->_applicationModulesInstance->getCurrentModuleInstance(), '\Nebule\Library\Translates')
            && isset($this->_applicationModulesInstance->getCurrentModuleInstance()::TRANSLATE_TABLE[$lang][$text]))
            return $this->_applicationModulesInstance->getCurrentModuleInstance()::TRANSLATE_TABLE[$lang][$text];

        $result = $this->_getTranslateFromModules($text, $lang);
        if ($result != '')
            return $result;

        if ($lang == self::DEFAULT_LANGUAGE)
            return $text;

        if (str_starts_with($text, '::::') && isset(Translates::TRANSLATE_TABLE[self::DEFAULT_LANGUAGE][$text]))
            return Translates::TRANSLATE_TABLE[self::DEFAULT_LANGUAGE][$text];
        if (str_starts_with($text, ':::') && isset($classAppTranslate::TRANSLATE_TABLE[self::DEFAULT_LANGUAGE][$text]))
            return $classAppTranslate::TRANSLATE_TABLE[self::DEFAULT_LANGUAGE][$text];
        if (str_starts_with($text, '::')
            && is_a($this->_applicationModulesInstance->getCurrentModuleInstance(), '\Nebule\Library\Translates')
            && isset($this->_applicationModulesInstance->getCurrentModuleInstance()::TRANSLATE_TABLE[self::DEFAULT_LANGUAGE][$text]))
            return $this->_applicationModulesInstance->getCurrentModuleInstance()::TRANSLATE_TABLE[self::DEFAULT_LANGUAGE][$text];

        $this->_metrologyInstance->addLog('no translate, keep same value : ' . $result, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '74bd1f3a');
        return $text;
    }

    private function _getTranslateFromModules(string $text, string $lang): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('translate (' . $lang .  ') : ' . substr($text, 0, 250), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $result = '';
        foreach ($this->_applicationModulesInstance->getModulesTranslateListInstances() as $module) {
            if ($module->getCommandName() == $lang && isset($module::TRANSLATE_TABLE[$lang][$text])) {
                $result = $module::TRANSLATE_TABLE[$lang][$text];
                $this->_metrologyInstance->addLog('find translate : ' . $result, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '65349a7b');
            }
        }
        return $result;
    }

    private function _getTranslateFromDisplayModules(string $text, string $lang): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('translate (' . $lang .  ') : ' . substr($text, 0, 250), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!is_a($this->_applicationModulesInstance->getCurrentModuleInstance(), '\Nebule\Library\Modules'))
            return '';
        $result = $this->_applicationModulesInstance->getCurrentModuleInstance()->getTranslateInstance($text, $lang);
        $this->_metrologyInstance->addLog('module 2 find translate : ' . $result, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8811cb6a');
        if ($result == $text)
            $result = '';
        return $result;
    }

    private function _getTranslateFromModulesWithDefault(string $text, string $lang): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('translate (' . $lang .  ') : ' . substr($text, 0, 250), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($lang == $this->_defaultLanguage)
            return '';
        $result = '';
        foreach ($this->_applicationModulesInstance->getModulesTranslateListInstances() as $module) {
            if ($module->getCommandName() == $this->_defaultLanguage) {
                $result = $module->getTraduction($text, $this->_defaultLanguage);
                $this->_metrologyInstance->addLog('module 3 find translate : ' . $result, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'de331d22');
            }
        }
        if ($result == $text)
            $result = '';
        return $result;
    }

    private function _getTranslateFromDisplayModulesWithDefault(string $text, string $lang): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('translate (' . $lang .  ') : ' . substr($text, 0, 250), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($lang == $this->_defaultLanguage)
            return '';
        if (!is_a($this->_applicationModulesInstance->getCurrentModuleInstance(), '\Nebule\Library\Modules'))
            return '';
        $result = $this->_applicationModulesInstance->getCurrentModuleInstance()->getTranslateInstance($text, $this->_defaultLanguage);
        $this->_metrologyInstance->addLog('module 4 find translate : ' . $result, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c9a49d75');
        if ($result == $text)
            $result = '';
        return $result;
    }

    private function _getTranslateFromAllModules(string $text, string $lang): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('translate (' . $lang .  ') : ' . substr($text, 0, 250), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $result = '';
        foreach ($this->_applicationModulesInstance->getModulesListInstances() as $module) {
            $result = $module->getTranslateInstance($text, $lang);
            $this->_metrologyInstance->addLog('module 5 find translate : ' . $result, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e25be65f');
            if ($result != $text)
                break;
        }
        if ($result == $text)
            $result = '';
        return $result;
    }

    private function _getTranslateFromAllModulesWithDefault(string $text, string $lang): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('translate (' . $lang .  ') : ' . substr($text, 0, 250), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($lang == $this->_defaultLanguage)
            return '';
        $result = '';
        foreach ($this->_applicationModulesInstance->getModulesListInstances() as $module) {
            $result = $module->getTranslateInstance($text, $this->_defaultLanguage);
            $this->_metrologyInstance->addLog('module 6 find translate : "' . $result . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5b607293');
            if ($result != $text)
                break;
        }
        if ($result == $text)
            $result = '';
        return $result;
    }
}
