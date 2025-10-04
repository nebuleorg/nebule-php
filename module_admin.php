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
    const MODULE_NAME = '::module:admin:ModuleName';
    const MODULE_MENU_NAME = '::module:admin:MenuName';
    const MODULE_COMMAND_NAME = 'adm';
    const MODULE_DEFAULT_VIEW = 'options';
    const MODULE_DESCRIPTION = '::module:admin:ModuleDescription';
    const MODULE_VERSION = '020251004';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = '(c) GLPv3 nebule 2013-2025';
    const MODULE_LOGO = '1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3.sha2.256';
    const MODULE_HELP = '::module:admin:ModuleHelp';
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
    const MODULE_APP_TITLE_LIST = array('::module:admin:AppTitle1');
    const MODULE_APP_ICON_LIST = array('1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3.sha2.256');
    const MODULE_APP_DESC_LIST = array('::module:admin:AppDesc1');
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
                    $hookArray[0]['name'] = '::module:admin:display:AppOptions';
                    $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[1]) {
                    // Voir les options.
                    $hookArray[1]['name'] = '::module:admin:display:NebOptions';
                    $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[0];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[2]) {
                    // Voir les admins.
                    $hookArray[2]['name'] = '::module:admin:display:seeAdmins';
                    $hookArray[2]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[3]) {
                    // Voir les entités de recouvrement.
                    $hookArray[3]['name'] = '::module:admin:display:seeRecovery';
                    $hookArray[3]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[3]
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
        'sylabeDisplayOnlineHelp' => '::module:admin:option:help:sylabeDisplayOnlineHelp',
        'sylabeDisplayOnlineOptions' => '::module:admin:option:help:sylabeDisplayOnlineOptions',
        'sylabeDisplayMetrology' => '::module:admin:option:help:sylabeDisplayMetrology',
        //'sylabeDisplayUnsecureURL'			=> '::module:admin:option:help:sylabeDisplayUnsecureURL',
        'sylabeDisplayUnverifyLargeContent' => '::module:admin:option:help:sylabeDisplayUnverifyLargeContent',
        'sylabeDisplayNameSize' => '::module:admin:option:help:sylabeDisplayNameSize',
        'sylabeIOReadMaxDataPHP' => '::module:admin:option:help:sylabeIOReadMaxDataPHP',
        'sylabePermitUploadObject' => '::module:admin:option:help:sylabePermitUploadObject',
        'sylabePermitUploadLinks' => '::module:admin:option:help:sylabePermitUploadLinks',
        'sylabePermitPublicUploadObject' => '::module:admin:option:help:sylabePermitPublicUploadObject',
        'sylabePermitPublicUploadLinks' => '::module:admin:option:help:sylabePermitPublicUploadLinks',
        //'sylabeLogUnlockEntity'				=> '::module:admin:option:help:sylabeLogUnlockEntity',
        //'sylabeLogLockEntity'				=> '::module:admin:option:help:sylabeLogLockEntity',
        'sylabeLoadModules' => '::module:admin:option:help:sylabeLoadModules');


    /**
     * Affiche les options de l'application.
     *
     * @return void
     */
    private function _displayAppOptions(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_REGISTERED_ICONS[0]);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::module:admin:display:AppOptions');
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
        $instance->setTitle('::module:admin:display:NebOptions');
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
        $instance->setTitle('::module:admin:display:seeAdmins');
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
                    . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_DEFAULT_VIEW
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_ENTITY . '=' . $id;
                $list[$i]['desc'] = '';
                $list[$i]['actions'] = array();
                $i++;
            }
            unset($instance, $id);
            // Affichage
            if (sizeof($list) != 0) {
                // Affiche les point d'encrage de toutes les entités.
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::module:admin:DisplayLocalAuthorities');

                // Affiche les entités.
                $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation_DEPRECATED('::module:admin:Display:NoLocalAuthority');
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
        $instance->setTitle('::module:admin:display:seeRecovery');
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
                    . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleEntities')::MODULE_DEFAULT_VIEW
                    . '&' . \Nebule\Library\References::COMMAND_SELECT_ENTITY . '=' . $id;
                $list[$i]['desc'] = '';
                $list[$i]['actions'] = array();
                $i++;
            }
            unset($instance, $id);

            // Affichage
            if (sizeof($list) != 0) {
                // Affiche les point d'encrage de toutes les entités.
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::module:admin:DisplayRecoveryEntities');

                // Affiche les entités.
                $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation_DEPRECATED('::module:admin:Display:NoRecoveryEntity');
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
            '::module:admin:ModuleName' => "Module d'administration",
            '::module:admin:MenuName' => 'Options',
            '::module:admin:ModuleDescription' => 'Module de gestion des options de configuration et de personnalisation.',
            '::module:admin:ModuleHelp' => "Ce module permet de voir et de gérer les options.",
            '::module:admin:AppTitle1' => 'Options',
            '::module:admin:AppDesc1' => 'Modifier les options.',
            '::module:admin:display:AppOptions' => "Options de l'application",
            '::module:admin:display:NebOptions' => 'Options de nebule',
            '::module:admin:display:seeAdmins' => 'Autorités locales',
            '::module:admin:Display:NoLocalAuthority' => "Pas d'entité autorité sur ce serveur.",
            '::module:admin:display:seeRecovery' => 'Entités de recouvrement',
            '::module:admin:Display:NoRecoveryEntity' => "Pas d'entité de recouvrement des objets protégés sur ce serveur.",
            '::module:admin:display:defaultValue' => 'Valeur par défaut',
            '::module:admin:option:sylabeDisplayOnlineHelp' => "Affichage de l'aide en ligne",
            '::module:admin:option:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::module:admin:option:sylabeDisplayMetrology' => 'Affichage de la métrologie',
            '::module:admin:option:sylabeDisplayUnsecureURL' => 'Affiche une URL non sécurisée',
            '::module:admin:option:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::module:admin:option:sylabeDisplayNameSize' => 'Taille maximum des noms',
            '::module:admin:option:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::module:admin:option:sylabePermitUploadObject' => 'Autorise la synchronisation des objets',
            '::module:admin:option:sylabePermitUploadLinks' => 'Autorise la synchronisation des liens',
            '::module:admin:option:sylabePermitPublicUploadObject' => 'Autorise la synchronisation publique des objets',
            '::module:admin:option:sylabePermitPublicUploadLinks' => 'Autorise la synchronisation publique des liens',
            '::module:admin:option:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::module:admin:option:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::module:admin:option:sylabeLoadModules' => 'Modules à charger',
            '::module:admin:option:type:int' => 'Entier',
            '::module:admin:option:type:text' => 'Texte',
            '::module:admin:option:type:bool' => 'Booléen',
            '::module:admin:option:help:sylabeDisplayOnlineHelp' => 'sylabeDisplayOnlineHelp',
            '::module:admin:option:help:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::module:admin:option:help:sylabeDisplayMetrology' => 'sylabeDisplayMetrology',
            '::module:admin:option:help:sylabeDisplayUnsecureURL' => 'sylabeDisplayUnsecureURL',
            '::module:admin:option:help:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::module:admin:option:help:sylabeDisplayNameSize' => 'sylabeDisplayNameSize',
            '::module:admin:option:help:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::module:admin:option:help:sylabePermitUploadObject' => 'sylabePermitUploadObject',
            '::module:admin:option:help:sylabePermitUploadLinks' => 'sylabePermitUploadLinks',
            '::module:admin:option:help:sylabePermitPublicUploadObject' => 'sylabePermitPublicUploadObject',
            '::module:admin:option:help:sylabePermitPublicUploadLinks' => 'sylabePermitPublicUploadLinks',
            '::module:admin:option:help:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::module:admin:option:help:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::module:admin:option:help:sylabeLoadModules' => 'sylabeLoadModules',
        ],
        'en-en' => [
            '::module:admin:ModuleName' => 'Administration module',
            '::module:admin:MenuName' => 'Options',
            '::module:admin:ModuleDescription' => 'Options management module for configration and customisation.',
            '::module:admin:ModuleHelp' => 'This module permit to see and manage options.',
            '::module:admin:AppTitle1' => 'Options',
            '::module:admin:AppDesc1' => 'Modify all options.',
            '::module:admin:display:AppOptions' => "Application's options",
            '::module:admin:display:NebOptions' => "nebule's options",
            '::module:admin:display:seeAdmins' => 'Local autorities',
            '::module:admin:Display:NoLocalAuthority' => 'No autority entity on this server.',
            '::module:admin:display:seeRecovery' => 'Recovery entities',
            '::module:admin:Display:NoRecoveryEntity' => 'No recovery entity for protected objects on this server.',
            '::module:admin:display:defaultValue' => 'Default value',
            '::module:admin:option:sylabeDisplayOnlineHelp' => 'Display online help',
            '::module:admin:option:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::module:admin:option:sylabeDisplayMetrology' => 'Display metrology',
            '::module:admin:option:sylabeDisplayUnsecureURL' => 'Display unsecure URL',
            '::module:admin:option:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::module:admin:option:sylabeDisplayNameSize' => 'Maximum name size',
            '::module:admin:option:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::module:admin:option:sylabePermitUploadObject' => 'Permit object synchronisation',
            '::module:admin:option:sylabePermitUploadLinks' => 'Permit links synchronisation',
            '::module:admin:option:sylabePermitPublicUploadObject' => 'Permit public object synchronisation',
            '::module:admin:option:sylabePermitPublicUploadLinks' => 'Permit public links synchronisation',
            '::module:admin:option:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::module:admin:option:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::module:admin:option:sylabeLoadModules' => 'Modules to load',
            '::module:admin:option:type:int' => 'Integer',
            '::module:admin:option:type:text' => 'Text',
            '::module:admin:option:type:bool' => 'Boolean',
            '::module:admin:option:help:sylabeDisplayOnlineHelp' => 'sylabeDisplayOnlineHelp',
            '::module:admin:option:help:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::module:admin:option:help:sylabeDisplayMetrology' => 'sylabeDisplayMetrology',
            '::module:admin:option:help:sylabeDisplayUnsecureURL' => 'sylabeDisplayUnsecureURL',
            '::module:admin:option:help:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::module:admin:option:help:sylabeDisplayNameSize' => 'sylabeDisplayNameSize',
            '::module:admin:option:help:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::module:admin:option:help:sylabePermitUploadObject' => 'sylabePermitUploadObject',
            '::module:admin:option:help:sylabePermitUploadLinks' => 'sylabePermitUploadLinks',
            '::module:admin:option:help:sylabePermitPublicUploadObject' => 'sylabePermitPublicUploadObject',
            '::module:admin:option:help:sylabePermitPublicUploadLinks' => 'sylabePermitPublicUploadLinks',
            '::module:admin:option:help:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::module:admin:option:help:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::module:admin:option:help:sylabeLoadModules' => 'sylabeLoadModules',
        ],
        'es-co' => [
            '::module:admin:ModuleName' => 'Administration module',
            '::module:admin:MenuName' => 'Options',
            '::module:admin:ModuleDescription' => 'Options management module for configration and customisation.',
            '::module:admin:ModuleHelp' => 'This module permit to see and manage options.',
            '::module:admin:AppTitle1' => 'Options',
            '::module:admin:AppDesc1' => 'Modify all options.',
            '::module:admin:display:AppOptions' => "Application's options",
            '::module:admin:display:NebOptions' => "nebule's options",
            '::module:admin:display:seeAdmins' => 'Autoritidad local',
            '::module:admin:Display:NoLocalAuthority' => 'No autority entity on this server.',
            '::module:admin:display:seeRecovery' => 'Recovery entities',
            '::module:admin:Display:NoRecoveryEntity' => 'No recovery entity for protected objects on this server.',
            '::module:admin:display:defaultValue' => 'Default value',
            '::module:admin:option:sylabeDisplayOnlineHelp' => 'Display online help',
            '::module:admin:option:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::module:admin:option:sylabeDisplayMetrology' => 'Display metrology',
            '::module:admin:option:sylabeDisplayUnsecureURL' => 'Display unsecure URL',
            '::module:admin:option:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::module:admin:option:sylabeDisplayNameSize' => 'Maximum name size',
            '::module:admin:option:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::module:admin:option:sylabePermitUploadObject' => 'Permit object synchronisation',
            '::module:admin:option:sylabePermitUploadLinks' => 'Permit links synchronisation',
            '::module:admin:option:sylabePermitPublicUploadObject' => 'Permit public object synchronisation',
            '::module:admin:option:sylabePermitPublicUploadLinks' => 'Permit public links synchronisation',
            '::module:admin:option:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::module:admin:option:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::module:admin:option:sylabeLoadModules' => 'Modules to load',
            '::module:admin:option:type:int' => 'Integer',
            '::module:admin:option:type:text' => 'Text',
            '::module:admin:option:type:bool' => 'Boolean',
            '::module:admin:option:help:sylabeDisplayOnlineHelp' => 'sylabeDisplayOnlineHelp',
            '::module:admin:option:help:sylabeDisplayOnlineOptions' => 'sylabeDisplayOnlineOptions',
            '::module:admin:option:help:sylabeDisplayMetrology' => 'sylabeDisplayMetrology',
            '::module:admin:option:help:sylabeDisplayUnsecureURL' => 'sylabeDisplayUnsecureURL',
            '::module:admin:option:help:sylabeDisplayUnverifyLargeContent' => 'sylabeDisplayUnverifyLargeContent',
            '::module:admin:option:help:sylabeDisplayNameSize' => 'sylabeDisplayNameSize',
            '::module:admin:option:help:sylabeIOReadMaxDataPHP' => 'sylabeIOReadMaxDataPHP',
            '::module:admin:option:help:sylabePermitUploadObject' => 'sylabePermitUploadObject',
            '::module:admin:option:help:sylabePermitUploadLinks' => 'sylabePermitUploadLinks',
            '::module:admin:option:help:sylabePermitPublicUploadObject' => 'sylabePermitPublicUploadObject',
            '::module:admin:option:help:sylabePermitPublicUploadLinks' => 'sylabePermitPublicUploadLinks',
            '::module:admin:option:help:sylabeLogUnlockEntity' => 'sylabeLogUnlockEntity',
            '::module:admin:option:help:sylabeLogLockEntity' => 'sylabeLogLockEntity',
            '::module:admin:option:help:sylabeLoadModules' => 'sylabeLoadModules',
        ],
    ];
}
