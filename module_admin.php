<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Application;
use Nebule\Library\Configuration;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * Ce module permet de gérer les options de l'application ainsi que certains rôles d'entités.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleAdmin extends \Nebule\Library\Modules
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'adm';
    const MODULE_DEFAULT_VIEW = 'options';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020251116';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2025';
    const MODULE_LOGO = '1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array(
        'appopt',
        'nebopt',
        'admins',
        'recovery',
    );
    const MODULE_REGISTERED_ICONS = array(
        '1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3.sha2.256',    // 0 : Icône admin.
        '3edf52669e7284e4cefbdbb00a8b015460271765e97a0d6ce6496b11fe530ce1.sha2.256',    // 1 : Icône liste entités.
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('options');


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();
        switch ($hookName) {
            case 'selfMenu':
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[0]) {
                    // Voir les options.
                    $hookArray[0]['name'] = '::display:AppOptions';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[1]) {
                    // Voir les options.
                    $hookArray[1]['name'] = '::display:NebOptions';
                    $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[2]) {
                    // Voir les admins.
                    $hookArray[2]['name'] = '::display:seeAdmins';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[3]) {
                    // Voir les entités de recouvrement.
                    $hookArray[3]['name'] = '::display:seeRecovery';
                    $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     *
     * @return void
     */
    public function displayModule(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
//            case $this::MODULE_REGISTERED_VIEWS[0]:
//                $this->_displayAppOptions();
//                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayNebOptions();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayAdmins();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayRecoveryEntities();
                break;
            default:
                $this->_displayAppOptions();
                break;
        }
    }


    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    public function displayModuleInline(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayInlineAdmins();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayInlineRecoveryEntities();
                break;
        }
    }


    private $_listOptions = array(
        'sylabeDisplayOnlineHelp',
        'sylabeDisplayOnlineOptions',
        'sylabeDisplayMetrology',
        //'sylabeDisplayUnsecureURL',
        'sylabeDisplayUnverifyLargeContent',
        'sylabeDisplayNameSize',
        'sylabeIOReadMaxDataPHP',
        'sylabePermitUploadObject',
        'sylabePermitUploadLinks',
        'sylabePermitPublicUploadObject',
        'sylabePermitPublicUploadLinks',
        //'sylabeLogUnlockEntity',
        //'sylabeLogLockEntity',
        'sylabeLoadModules');
    private $_listOptionsType = array(
        'sylabeDisplayOnlineHelp' => 'boolean',
        'sylabeDisplayOnlineOptions' => 'boolean',
        'sylabeDisplayMetrology' => 'boolean',
        //'sylabeDisplayUnsecureURL'		=> 'boolean',
        'sylabeDisplayUnverifyLargeContent' => 'boolean',
        'sylabeDisplayNameSize' => 'integer',
        'sylabeIOReadMaxDataPHP' => 'integer',
        'sylabePermitUploadObject' => 'boolean',
        'sylabePermitUploadLinks' => 'boolean',
        'sylabePermitPublicUploadObject' => 'boolean',
        'sylabePermitPublicUploadLinks' => 'boolean',
        //'sylabeLogUnlockEntity'			=> 'boolean',
        //'sylabeLogLockEntity'				=> 'boolean',
        'sylabeLoadModules' => 'string');
    private $_listOptionsDefault = array(
        'sylabeDisplayOnlineHelp' => Application::APPLICATION_DEFAULT_DISPLAY_ONLINE_HELP,
        'sylabeDisplayOnlineOptions' => Application::APPLICATION_DEFAULT_DISPLAY_ONLINE_OPTIONS,
        'sylabeDisplayMetrology' => Application::APPLICATION_DEFAULT_DISPLAY_METROLOGY,
        //'sylabeDisplayUnsecureURL'			=> Application::APPLICATION_DEFAULT_DISPLAY_UNSECURE_URL,
        'sylabeDisplayUnverifyLargeContent' => Application::APPLICATION_DEFAULT_DISPLAY_UNVERIFY_LARGE_CONTENT,
        'sylabeDisplayNameSize' => Application::APPLICATION_DEFAULT_DISPLAY_NAME_SIZE,
        'sylabeIOReadMaxDataPHP' => Application::APPLICATION_DEFAULT_IO_READ_MAX_DATA,
        'sylabePermitUploadObject' => Application::APPLICATION_DEFAULT_PERMIT_UPLOAD_OBJECT,
        'sylabePermitUploadLinks' => Application::APPLICATION_DEFAULT_PERMIT_UPLOAD_LINKS,
        'sylabePermitPublicUploadObject' => Application::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_OBJECT,
        'sylabePermitPublicUploadLinks' => Application::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_LINKS,
        //'sylabeLogUnlockEntity'				=> Application::APPLICATION_DEFAULT_LOG_UNLOCK_ENTITY,
        //'sylabeLogLockEntity'				=> Application::APPLICATION_DEFAULT_LOG_LOCK_ENTITY,
        'sylabeLoadModules' => Application::APPLICATION_DEFAULT_LOAD_MODULES);
    private $_listOptionsHelp = array(
        'sylabeDisplayOnlineHelp' => '::option:help:sylabeDisplayOnlineHelp',
        'sylabeDisplayOnlineOptions' => '::option:help:sylabeDisplayOnlineOptions',
        'sylabeDisplayMetrology' => '::option:help:sylabeDisplayMetrology',
        //'sylabeDisplayUnsecureURL'			=> '::option:help:sylabeDisplayUnsecureURL',
        'sylabeDisplayUnverifyLargeContent' => '::option:help:sylabeDisplayUnverifyLargeContent',
        'sylabeDisplayNameSize' => '::option:help:sylabeDisplayNameSize',
        'sylabeIOReadMaxDataPHP' => '::option:help:sylabeIOReadMaxDataPHP',
        'sylabePermitUploadObject' => '::option:help:sylabePermitUploadObject',
        'sylabePermitUploadLinks' => '::option:help:sylabePermitUploadLinks',
        'sylabePermitPublicUploadObject' => '::option:help:sylabePermitPublicUploadObject',
        'sylabePermitPublicUploadLinks' => '::option:help:sylabePermitPublicUploadLinks',
        //'sylabeLogUnlockEntity'				=> '::option:help:sylabeLogUnlockEntity',
        //'sylabeLogLockEntity'				=> '::option:help:sylabeLogLockEntity',
        'sylabeLoadModules' => '::option:help:sylabeLoadModules');


    /**
     * Affiche les options de l'application.
     *
     * @return void
     */
    private function _displayAppOptions(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[0]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::display:AppOptions');
        $instance->setIcon($icon);
        $instance->display();

        if ($this->_unlocked) {
            $listOptions = $this->_listOptions;
            $listOptionsType = $this->_listOptionsType;
            $listOptionsDefaultValue = $this->_listOptionsDefault;
            $listOptionsDescription = $this->_listOptionsHelp;

            // Prépare le rendu des options.
            $param = array(
                'enableDisplayIcon' => true,
                'enableDisplayAlone' => false,
                'informationType' => 'information',
                'displaySize' => 'medium',
                'displayRatio' => 'long',
            );

            // Prépare l'affichage des options.
            $list = array();
            $i = 0;
            foreach ($listOptions as $optionName) {
                // Extrait les propriétés de l'option.
                $optionValue = $this->_configurationInstance->getOptionUntyped($optionName);
                $optionID = $this->getNidFromData($optionName);
                $optionValueDisplay = (string)$optionValue;
                $optionType = $listOptionsType[$optionName];
                $optionDefaultValue = $listOptionsDefaultValue[$optionName];
                $optionDefaultDisplay = (string)$optionDefaultValue;
                $optionDescription = $this->_translateInstance->getTranslate($listOptionsDescription[$optionName]);

                $list[$i]['information'] = $optionName . ' = ' . $optionValueDisplay;//.'<br />'.$optionDescription;
                $list[$i]['param'] = $param;
                $list[$i]['param']['informationTypeName'] = $optionType;
                $list[$i]['param']['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $i++;
            }

            // Affichage.
            echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'Medium', false);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
        }
    }

    /**
     * Affiche les options de la bibliothèque nebule.
     *
     * @return void
     */
    private function _displayNebOptions(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[0]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::display:NebOptions');
        $instance->setIcon($icon);
        $instance->display();

        if ($this->_unlocked) {
            $listOptions = Configuration::OPTIONS_LIST;
            $listCategoriesOptions = Configuration::OPTIONS_CATEGORIES;
            $listOptionsCategory = Configuration::OPTIONS_CATEGORY;
            $listOptionsType = Configuration::OPTIONS_TYPE;
            $listOptionsWritable = Configuration::OPTIONS_WRITABLE;
            $listOptionsDefaultValue = Configuration::OPTIONS_DEFAULT_VALUE;
            $listOptionsCriticality = Configuration::OPTIONS_CRITICALITY;
            $listOptionsDescription = Configuration::OPTIONS_DESCRIPTION;

            // Prépare le rendu des options.
            $param = array(
                'enableDisplayIcon' => true,
                'enableDisplayAlone' => false,
                'informationType' => 'information',
                'displaySize' => 'medium',
                'displayRatio' => 'long',
            );

            // Prépare l'affichage des options.
            $list = array();
            $i = 0;
            foreach ($listOptions as $optionName) {
                // Extrait les propriétés de l'option.
                $optionValue = $this->_configurationInstance->getOptionUntyped($optionName);
                $optionID = $this->getNidFromData($optionName);
                $optionValueDisplay = (string)$optionValue;
                $optionType = $listOptionsType[$optionName];
                $optionWritable = $listOptionsWritable[$optionName];
                $optionWritableDisplay = 'writable';
                $optionDefaultValue = $listOptionsDefaultValue[$optionName];
                $optionDefaultDisplay = (string)$optionDefaultValue;
                $optionCriticality = $listOptionsCriticality[$optionName];
                $optionDescription = $listOptionsDescription[$optionName];
                //$optionLocked = ($this->_configurationInstance->getOptionFromEnvironmentUntyped($optionName) !== null);

                $list[$i]['information'] = $optionName . ' = ' . $optionValueDisplay . '<br />' . $optionDescription;
                $list[$i]['param'] = $param;
                $list[$i]['param']['informationTypeName'] = $optionType;
                $list[$i]['param']['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                $i++;
            }

            // Affichage.
            echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'Medium', false);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
        }
    }

    /**
     * Affichage des autorités locales.
     *
     * @return void
     */
    private function _displayAdmins(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[1]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::display:seeAdmins');
        $instance->setIcon($icon);
        $instance->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('adminlist');
    }

    /**
     * Affichage en ligne des autorités locales.
     *
     * @return void
     */
    private function _displayInlineAdmins(): void
    {
        if ($this->_unlocked) {
            // Liste les entités que j'ai marqué comme connues.
            $listEntities = $this->_authoritiesInstance->getLocalAuthoritiesInstance();
            $listSigners = $this->_authoritiesInstance->getLocalAuthoritiesSigners();

            // Prépare l'affichage.
            $list = array();
            $i = 0;
            foreach ($listEntities as $instance) {
                $id = $instance->getID();
                $list[$i]['object'] = $instance;
                $list[$i]['entity'] = '';
                if ($listSigners[$id] != '0'
                    && $listSigners[$id] != ''
                ) {
                    $list[$i]['entity'] = $listSigners[$id];
                }
                $list[$i]['icon'] = '';
                $list[$i]['htlink'] = '?'
                    . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_DEFAULT_VIEW
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_ENTITY . '=' . $id;
                $list[$i]['desc'] = '';
                $list[$i]['actions'] = array();
                $i++;
            }
            unset($instance, $id);
            // Affichage
            if (sizeof($list) != 0) {
                // Affiche les point d'encrage de toutes les entités.
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::DisplayLocalAuthorities');

                // Affiche les entités.
                $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::Display:NoLocalAuthority');
            }
            unset($list, $listEntities, $listSigners);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
        }
    }

    /**
     * Affichage des entités de recouvrement.
     *
     * @return void
     */
    private function _displayRecoveryEntities(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[1]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::display:seeRecovery');
        $instance->setIcon($icon);
        $instance->display();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('recoverylist');
    }

    /**
     * Affichage en ligne des entités de recouvrement.
     *
     * @return void
     */
    private function _displayInlineRecoveryEntities(): void
    {
        if ($this->_unlocked) {
            // Liste les entités marquées comme entités de recouvrement.
            $listEntities = $this->_recoveryInstance->getRecoveryEntitiesInstance();
            $listSigners = $this->_recoveryInstance->getRecoveryEntitiesSigners();

            // Prépare l'affichage.
            $list = array();
            $i = 0;
            foreach ($listEntities as $instance) {
                $id = $instance->getID();
                $list[$i]['object'] = $instance;
                $list[$i]['entity'] = '';
                if ($listSigners[$id] != '0'
                    && $listSigners[$id] != ''
                ) {
                    $list[$i]['entity'] = $listSigners[$id];
                }
                $list[$i]['icon'] = '';
                $list[$i]['htlink'] = '?'
                    . Displays::COMMAND_DISPLAY_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_COMMAND_NAME
                    . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_DEFAULT_VIEW
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_ENTITY . '=' . $id;
                $list[$i]['desc'] = '';
                $list[$i]['actions'] = array();
                $i++;
            }
            unset($instance, $id);

            // Affichage
            if (sizeof($list) != 0) {
                // Affiche les point d'encrage de toutes les entités.
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::DisplayRecoveryEntities');

                // Affiche les entités.
                $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::Display:NoRecoveryEntity');
            }
            unset($list, $listEntities, $listSigners);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
        }
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => "Module d'administration",
            '::MenuName' => 'Options',
            '::ModuleDescription' => 'Module de gestion des options de configuration et de personnalisation.',
            '::ModuleHelp' => "Ce module permet de voir et de gérer les options.",
            '::AppTitle1' => 'Options',
            '::AppDesc1' => 'Modifier les options.',
            '::display:AppOptions' => "Options de l'application",
            '::display:NebOptions' => 'Options de nebule',
            '::display:seeAdmins' => 'Autorités locales',
            '::Display:NoLocalAuthority' => "Pas d'entité autorité sur ce serveur.",
            '::display:seeRecovery' => 'Entités de recouvrement',
            '::Display:NoRecoveryEntity' => "Pas d'entité de recouvrement des objets protégés sur ce serveur.",
            '::display:defaultValue' => 'Valeur par défaut',
            '::option:sylabeDisplayOnlineHelp' => "Affichage de l'aide en ligne",
            '::option:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::option:sylabeDisplayMetrology' => 'Affichage de la métrologie',
            '::option:sylabeDisplayUnsecureURL' => 'Affiche une URL non sécurisée',
            '::option:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::option:sylabeDisplayNameSize' => 'Taille maximum des noms',
            '::option:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::option:sylabePermitUploadObject' => 'Autorise la synchronisation des objets',
            '::option:sylabePermitUploadLinks' => 'Autorise la synchronisation des liens',
            '::option:sylabePermitPublicUploadObject' => 'Autorise la synchronisation publique des objets',
            '::option:sylabePermitPublicUploadLinks' => 'Autorise la synchronisation publique des liens',
            '::option:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::option:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::option:sylabeLoadModules' => 'Modules à charger',
            '::option:type:int' => 'Entier',
            '::option:type:text' => 'Texte',
            '::option:type:bool' => 'Booléen',
            '::option:help:sylabeDisplayOnlineHelp' => 'sylabeDisplayOnlineHelp',
            '::option:help:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::option:help:sylabeDisplayMetrology' => 'sylabeDisplayMetrology',
            '::option:help:sylabeDisplayUnsecureURL' => 'sylabeDisplayUnsecureURL',
            '::option:help:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::option:help:sylabeDisplayNameSize' => 'sylabeDisplayNameSize',
            '::option:help:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::option:help:sylabePermitUploadObject' => 'sylabePermitUploadObject',
            '::option:help:sylabePermitUploadLinks' => 'sylabePermitUploadLinks',
            '::option:help:sylabePermitPublicUploadObject' => 'sylabePermitPublicUploadObject',
            '::option:help:sylabePermitPublicUploadLinks' => 'sylabePermitPublicUploadLinks',
            '::option:help:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::option:help:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::option:help:sylabeLoadModules' => 'sylabeLoadModules',
        ],
        'en-en' => [
            '::ModuleName' => 'Administration module',
            '::MenuName' => 'Options',
            '::ModuleDescription' => 'Options management module for configration and customisation.',
            '::ModuleHelp' => 'This module permit to see and manage options.',
            '::AppTitle1' => 'Options',
            '::AppDesc1' => 'Modify all options.',
            '::display:AppOptions' => "Application's options",
            '::display:NebOptions' => "nebule's options",
            '::display:seeAdmins' => 'Local autorities',
            '::Display:NoLocalAuthority' => 'No autority entity on this server.',
            '::display:seeRecovery' => 'Recovery entities',
            '::Display:NoRecoveryEntity' => 'No recovery entity for protected objects on this server.',
            '::display:defaultValue' => 'Default value',
            '::option:sylabeDisplayOnlineHelp' => 'Display online help',
            '::option:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::option:sylabeDisplayMetrology' => 'Display metrology',
            '::option:sylabeDisplayUnsecureURL' => 'Display unsecure URL',
            '::option:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::option:sylabeDisplayNameSize' => 'Maximum name size',
            '::option:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::option:sylabePermitUploadObject' => 'Permit object synchronisation',
            '::option:sylabePermitUploadLinks' => 'Permit links synchronisation',
            '::option:sylabePermitPublicUploadObject' => 'Permit public object synchronisation',
            '::option:sylabePermitPublicUploadLinks' => 'Permit public links synchronisation',
            '::option:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::option:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::option:sylabeLoadModules' => 'Modules to load',
            '::option:type:int' => 'Integer',
            '::option:type:text' => 'Text',
            '::option:type:bool' => 'Boolean',
            '::option:help:sylabeDisplayOnlineHelp' => 'sylabeDisplayOnlineHelp',
            '::option:help:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::option:help:sylabeDisplayMetrology' => 'sylabeDisplayMetrology',
            '::option:help:sylabeDisplayUnsecureURL' => 'sylabeDisplayUnsecureURL',
            '::option:help:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::option:help:sylabeDisplayNameSize' => 'sylabeDisplayNameSize',
            '::option:help:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::option:help:sylabePermitUploadObject' => 'sylabePermitUploadObject',
            '::option:help:sylabePermitUploadLinks' => 'sylabePermitUploadLinks',
            '::option:help:sylabePermitPublicUploadObject' => 'sylabePermitPublicUploadObject',
            '::option:help:sylabePermitPublicUploadLinks' => 'sylabePermitPublicUploadLinks',
            '::option:help:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::option:help:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::option:help:sylabeLoadModules' => 'sylabeLoadModules',
        ],
        'es-co' => [
            '::ModuleName' => 'Administration module',
            '::MenuName' => 'Options',
            '::ModuleDescription' => 'Options management module for configration and customisation.',
            '::ModuleHelp' => 'This module permit to see and manage options.',
            '::AppTitle1' => 'Options',
            '::AppDesc1' => 'Modify all options.',
            '::display:AppOptions' => "Application's options",
            '::display:NebOptions' => "nebule's options",
            '::display:seeAdmins' => 'Autoritidad local',
            '::Display:NoLocalAuthority' => 'No autority entity on this server.',
            '::display:seeRecovery' => 'Recovery entities',
            '::Display:NoRecoveryEntity' => 'No recovery entity for protected objects on this server.',
            '::display:defaultValue' => 'Default value',
            '::option:sylabeDisplayOnlineHelp' => 'Display online help',
            '::option:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::option:sylabeDisplayMetrology' => 'Display metrology',
            '::option:sylabeDisplayUnsecureURL' => 'Display unsecure URL',
            '::option:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::option:sylabeDisplayNameSize' => 'Maximum name size',
            '::option:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::option:sylabePermitUploadObject' => 'Permit object synchronisation',
            '::option:sylabePermitUploadLinks' => 'Permit links synchronisation',
            '::option:sylabePermitPublicUploadObject' => 'Permit public object synchronisation',
            '::option:sylabePermitPublicUploadLinks' => 'Permit public links synchronisation',
            '::option:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::option:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::option:sylabeLoadModules' => 'Modules to load',
            '::option:type:int' => 'Integer',
            '::option:type:text' => 'Text',
            '::option:type:bool' => 'Boolean',
            '::option:help:sylabeDisplayOnlineHelp' => 'sylabeDisplayOnlineHelp',
            '::option:help:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::option:help:sylabeDisplayMetrology' => 'sylabeDisplayMetrology',
            '::option:help:sylabeDisplayUnsecureURL' => 'sylabeDisplayUnsecureURL',
            '::option:help:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::option:help:sylabeDisplayNameSize' => 'sylabeDisplayNameSize',
            '::option:help:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::option:help:sylabePermitUploadObject' => 'sylabePermitUploadObject',
            '::option:help:sylabePermitUploadLinks' => 'sylabePermitUploadLinks',
            '::option:help:sylabePermitPublicUploadObject' => 'sylabePermitPublicUploadObject',
            '::option:help:sylabePermitPublicUploadLinks' => 'sylabePermitPublicUploadLinks',
            '::option:help:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::option:help:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::option:help:sylabeLoadModules' => 'sylabeLoadModules',
        ],
    ];
}
