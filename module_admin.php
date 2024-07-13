<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Application;
use Nebule\Library\Displays;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * Ce module permet de gérer les options de l'application ainsi que certains rôles d'entités.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleAdmin extends Modules
{
    protected $MODULE_TYPE = 'Application';
    protected $MODULE_NAME = '::sylabe:module:admin:ModuleName';
    protected $MODULE_MENU_NAME = '::sylabe:module:admin:MenuName';
    protected $MODULE_COMMAND_NAME = 'adm';
    protected $MODULE_DEFAULT_VIEW = 'options';
    protected $MODULE_DESCRIPTION = '::sylabe:module:admin:ModuleDescription';
    protected $MODULE_VERSION = '020240606';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2024';
    protected $MODULE_LOGO = '1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3.sha2.256';
    protected $MODULE_HELP = '::sylabe:module:admin:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array(
        'appopt',
        'nebopt',
        'admins',
        'recovery',
    );
    protected $MODULE_REGISTERED_ICONS = array(
        '1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3.sha2.256',    // 0 : Icône admin.
        '3edf52669e7284e4cefbdbb00a8b015460271765e97a0d6ce6496b11fe530ce1.sha2.256',    // 1 : Icône liste entités.
    );
    protected $MODULE_APP_TITLE_LIST = array('::sylabe:module:admin:AppTitle1');
    protected $MODULE_APP_ICON_LIST = array('1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3.sha2.256');
    protected $MODULE_APP_DESC_LIST = array('::sylabe:module:admin:AppDesc1');
    protected $MODULE_APP_VIEW_LIST = array('options');


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();
        switch ($hookName) {
            case 'selfMenu':
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[0]) {
                    // Voir les options.
                    $hookArray[0]['name'] = '::sylabe:module:admin:display:AppOptions';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[1]) {
                    // Voir les options.
                    $hookArray[1]['name'] = '::sylabe:module:admin:display:NebOptions';
                    $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[2]) {
                    // Voir les admins.
                    $hookArray[2]['name'] = '::sylabe:module:admin:display:seeAdmins';
                    $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[3]) {
                    // Voir les entités de recouvrement.
                    $hookArray[3]['name'] = '::sylabe:module:admin:display:seeRecovery';
                    $hookArray[3]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3];
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
//            case $this->MODULE_REGISTERED_VIEWS[0]:
//                $this->_displayAppOptions();
//                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayNebOptions();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayAdmins();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
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
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayInlineAdmins();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
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
        'sylabeDisplayOnlineHelp' => '::sylabe:module:admin:option:help:sylabeDisplayOnlineHelp',
        'sylabeDisplayOnlineOptions' => '::sylabe:module:admin:option:help:sylabeDisplayOnlineOptions',
        'sylabeDisplayMetrology' => '::sylabe:module:admin:option:help:sylabeDisplayMetrology',
        //'sylabeDisplayUnsecureURL'			=> '::sylabe:module:admin:option:help:sylabeDisplayUnsecureURL',
        'sylabeDisplayUnverifyLargeContent' => '::sylabe:module:admin:option:help:sylabeDisplayUnverifyLargeContent',
        'sylabeDisplayNameSize' => '::sylabe:module:admin:option:help:sylabeDisplayNameSize',
        'sylabeIOReadMaxDataPHP' => '::sylabe:module:admin:option:help:sylabeIOReadMaxDataPHP',
        'sylabePermitUploadObject' => '::sylabe:module:admin:option:help:sylabePermitUploadObject',
        'sylabePermitUploadLinks' => '::sylabe:module:admin:option:help:sylabePermitUploadLinks',
        'sylabePermitPublicUploadObject' => '::sylabe:module:admin:option:help:sylabePermitPublicUploadObject',
        'sylabePermitPublicUploadLinks' => '::sylabe:module:admin:option:help:sylabePermitPublicUploadLinks',
        //'sylabeLogUnlockEntity'				=> '::sylabe:module:admin:option:help:sylabeLogUnlockEntity',
        //'sylabeLogLockEntity'				=> '::sylabe:module:admin:option:help:sylabeLogLockEntity',
        'sylabeLoadModules' => '::sylabe:module:admin:option:help:sylabeLoadModules');


    /**
     * Affiche les options de l'application.
     *
     * @return void
     */
    private function _displayAppOptions(): void
    {
        // Affiche le titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[0]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::sylabe:module:admin:display:AppOptions', $icon, false);

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
                $optionID = $this->_nebuleInstance->getCryptoInstance()->hash($optionName);
                $optionValueDisplay = (string)$optionValue;
                $optionType = $listOptionsType[$optionName];
                $optionDefaultValue = $listOptionsDefaultValue[$optionName];
                $optionDefaultDisplay = (string)$optionDefaultValue;
                $optionDescription = $this->_traduction($listOptionsDescription[$optionName]);

                $list[$i]['information'] = $optionName . ' = ' . $optionValueDisplay;//.'<br />'.$optionDescription;
                $list[$i]['param'] = $param;
                $list[$i]['param']['informationTypeName'] = $optionType;
                $list[$i]['param']['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $i++;
            }

            // Affichage.
            echo $this->_displayInstance->getDisplayObjectsList($list, 'Medium', false);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED(':::err_NotPermit', $param);
        }
    }

    /**
     * Affiche les options de la bibliothèque nebule.
     *
     * @return void
     */
    private function _displayNebOptions(): void
    {
        // Affiche le titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[0]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::sylabe:module:admin:display:NebOptions', $icon, false);

        if ($this->_unlocked) {
            $listOptions = nebule::getListOptions();
            $listCategoriesOptions = nebule::getListCategoriesOptions();
            $listOptionsCategory = nebule::getListOptionsCategory();
            $listOptionsType = nebule::getListOptionsType();
            $listOptionsWritable = nebule::getListOptionsWritable();
            $listOptionsDefaultValue = nebule::getListOptionsDefaultValue();
            $listOptionsCriticality = nebule::getListOptionsCriticality();
            $listOptionsDescription = nebule::getListOptionsDescription();

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
                $optionID = $this->_nebuleInstance->getCryptoInstance()->hash($optionName);
                $optionValueDisplay = (string)$optionValue;
                $optionType = $listOptionsType[$optionName];
                $optionWritable = $listOptionsWritable[$optionName];
                $optionWritableDisplay = 'writable';
                $optionDefaultValue = $listOptionsDefaultValue[$optionName];
                $optionDefaultDisplay = (string)$optionDefaultValue;
                $optionCriticality = $listOptionsCriticality[$optionName];
                $optionDescription = $listOptionsDescription[$optionName];
                $optionLocked = ($this->_configurationInstance->getOptionFromEnvironmentUntyped($optionName) !== null);

                $list[$i]['information'] = $optionName . ' = ' . $optionValueDisplay . '<br />' . $optionDescription;
                $list[$i]['param'] = $param;
                $list[$i]['param']['informationTypeName'] = $optionType;
                $list[$i]['param']['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $i++;
            }

            // Affichage.
            echo $this->_displayInstance->getDisplayObjectsList($list, 'Medium', false);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED(':::err_NotPermit', $param);
        }
    }

    /**
     * Affichage des autorités locales.
     *
     * @return void
     */
    private function _displayAdmins(): void
    {
        // Affiche le titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[1]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::sylabe:module:admin:display:seeAdmins', $icon, false);

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
            $listEntities = $this->_nebuleInstance->getLocalAuthoritiesInstance();
            $listSigners = $this->_nebuleInstance->getLocalAuthoritiesSigners();

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
                    . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getDefaultView()
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $id;
                $list[$i]['desc'] = '';
                $list[$i]['actions'] = array();
                $i++;
            }
            unset($instance, $id);
            // Affichage
            if (sizeof($list) != 0) {
                // Affiche les point d'encrage de toutes les entités.
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::sylabe:module:admin:DisplayLocalAuthorities');

                // Affiche les entités.
                $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation_DEPRECATED('::sylabe:module:admin:Display:NoLocalAuthority');
            }
            unset($list, $listEntities, $listSigners);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED(':::err_NotPermit', $param);
        }
    }

    /**
     * Affichage des entités de recouvrement.
     *
     * @return void
     */
    private function _displayRecoveryEntities(): void
    {
        // Affiche le titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[1]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED('::sylabe:module:admin:display:seeRecovery', $icon, false);

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
            $listEntities = $this->_nebuleInstance->getRecoveryEntitiesInstance();
            $listSigners = $this->_nebuleInstance->getRecoveryEntitiesSigners();

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
                    . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getDefaultView()
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $id;
                $list[$i]['desc'] = '';
                $list[$i]['actions'] = array();
                $i++;
            }
            unset($instance, $id);

            // Affichage
            if (sizeof($list) != 0) {
                // Affiche les point d'encrage de toutes les entités.
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::sylabe:module:admin:DisplayRecoveryEntities');

                // Affiche les entités.
                $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation_DEPRECATED('::sylabe:module:admin:Display:NoRecoveryEntity');
            }
            unset($list, $listEntities, $listSigners);
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->_displayInstance->getDisplayInformation_DEPRECATED(':::err_NotPermit', $param);
        }
    }


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::sylabe:module:admin:ModuleName'] = "Module d'administration";
        $this->_table['en-en']['::sylabe:module:admin:ModuleName'] = 'Administration module';
        $this->_table['es-co']['::sylabe:module:admin:ModuleName'] = 'Administration module';
        $this->_table['fr-fr']['::sylabe:module:admin:MenuName'] = 'Options';
        $this->_table['en-en']['::sylabe:module:admin:MenuName'] = 'Options';
        $this->_table['es-co']['::sylabe:module:admin:MenuName'] = 'Options';
        $this->_table['fr-fr']['::sylabe:module:admin:ModuleDescription'] = 'Module de gestion des options de configuration et de personnalisation.';
        $this->_table['en-en']['::sylabe:module:admin:ModuleDescription'] = 'Options management module for configration and customisation.';
        $this->_table['es-co']['::sylabe:module:admin:ModuleDescription'] = 'Options management module for configration and customisation.';
        $this->_table['fr-fr']['::sylabe:module:admin:ModuleHelp'] = "Ce module permet de voir et de gérer les options.";
        $this->_table['en-en']['::sylabe:module:admin:ModuleHelp'] = 'This module permit to see and manage options.';
        $this->_table['es-co']['::sylabe:module:admin:ModuleHelp'] = 'This module permit to see and manage options.';

        $this->_table['fr-fr']['::sylabe:module:admin:AppTitle1'] = 'Options';
        $this->_table['en-en']['::sylabe:module:admin:AppTitle1'] = 'Options';
        $this->_table['es-co']['::sylabe:module:admin:AppTitle1'] = 'Options';
        $this->_table['fr-fr']['::sylabe:module:admin:AppDesc1'] = 'Modifier les options.';
        $this->_table['en-en']['::sylabe:module:admin:AppDesc1'] = 'Modify all options.';
        $this->_table['es-co']['::sylabe:module:admin:AppDesc1'] = 'Modify all options.';

        $this->_table['fr-fr']['::sylabe:module:admin:display:AppOptions'] = "Options de l'application";
        $this->_table['en-en']['::sylabe:module:admin:display:AppOptions'] = "Application's options";
        $this->_table['es-co']['::sylabe:module:admin:display:AppOptions'] = "Application's options";
        $this->_table['fr-fr']['::sylabe:module:admin:display:NebOptions'] = 'Options de nebule';
        $this->_table['en-en']['::sylabe:module:admin:display:NebOptions'] = "nebule's options";
        $this->_table['es-co']['::sylabe:module:admin:display:NebOptions'] = "nebule's options";

        $this->_table['fr-fr']['::sylabe:module:admin:display:seeAdmins'] = 'Autorités locales';
        $this->_table['en-en']['::sylabe:module:admin:display:seeAdmins'] = 'Local autorities';
        $this->_table['es-co']['::sylabe:module:admin:display:seeAdmins'] = 'Autoritidad local';
        $this->_table['fr-fr']['::sylabe:module:admin:Display:NoLocalAuthority'] = "Pas d'entité autorité sur ce serveur.";
        $this->_table['en-en']['::sylabe:module:admin:Display:NoLocalAuthority'] = 'No autority entity on this server.';
        $this->_table['es-co']['::sylabe:module:admin:Display:NoLocalAuthority'] = 'No autority entity on this server.';

        $this->_table['fr-fr']['::sylabe:module:admin:display:seeRecovery'] = 'Entités de recouvrement';
        $this->_table['en-en']['::sylabe:module:admin:display:seeRecovery'] = 'Recovery entities';
        $this->_table['es-co']['::sylabe:module:admin:display:seeRecovery'] = 'Recovery entities';
        $this->_table['fr-fr']['::sylabe:module:admin:Display:NoRecoveryEntity'] = "Pas d'entité de recouvrement des objets protégés sur ce serveur.";
        $this->_table['en-en']['::sylabe:module:admin:Display:NoRecoveryEntity'] = 'No recovery entity for protected objects on this server.';
        $this->_table['es-co']['::sylabe:module:admin:Display:NoRecoveryEntity'] = 'No recovery entity for protected objects on this server.';

        $this->_table['fr-fr']['::sylabe:module:admin:display:defaultValue'] = 'Valeur par défaut';
        $this->_table['en-en']['::sylabe:module:admin:display:defaultValue'] = 'Default value';
        $this->_table['es-co']['::sylabe:module:admin:display:defaultValue'] = 'Default value';

        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeDisplayOnlineHelp'] = "Affichage de l'aide en ligne";
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeDisplayOnlineHelp'] = 'Display online help';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeDisplayOnlineHelp'] = 'Display online help';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeDisplayOnlineOptions'] = 'sylabeDisplayOnlineOptions';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeDisplayOnlineOptions'] = 'sylabeDisplayOnlineOptions';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeDisplayOnlineOptions'] = 'sylabeDisplayOnlineOptions';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeDisplayMetrology'] = 'Affichage de la métrologie';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeDisplayMetrology'] = 'Display metrology';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeDisplayMetrology'] = 'Display metrology';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeDisplayUnsecureURL'] = 'Affiche une URL non sécurisée';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeDisplayUnsecureURL'] = 'Display unsecure URL';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeDisplayUnsecureURL'] = 'Display unsecure URL';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeDisplayUnverifyLargeContent'] = 'sylabeDisplayUnverifyLargeContent';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeDisplayUnverifyLargeContent'] = 'sylabeDisplayUnverifyLargeContent';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeDisplayUnverifyLargeContent'] = 'sylabeDisplayUnverifyLargeContent';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeDisplayNameSize'] = 'Taille maximum des noms';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeDisplayNameSize'] = 'Maximum name size';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeDisplayNameSize'] = 'Maximum name size';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeIOReadMaxDataPHP'] = 'sylabeIOReadMaxDataPHP';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeIOReadMaxDataPHP'] = 'sylabeIOReadMaxDataPHP';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeIOReadMaxDataPHP'] = 'sylabeIOReadMaxDataPHP';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabePermitUploadObject'] = 'Autorise la synchronisation des objets';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabePermitUploadObject'] = 'Permit object synchronisation';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabePermitUploadObject'] = 'Permit object synchronisation';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabePermitUploadLinks'] = 'Autorise la synchronisation des liens';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabePermitUploadLinks'] = 'Permit links synchronisation';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabePermitUploadLinks'] = 'Permit links synchronisation';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabePermitPublicUploadObject'] = 'Autorise la synchronisation publique des objets';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabePermitPublicUploadObject'] = 'Permit public object synchronisation';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabePermitPublicUploadObject'] = 'Permit public object synchronisation';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabePermitPublicUploadLinks'] = 'Autorise la synchronisation publique des liens';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabePermitPublicUploadLinks'] = 'Permit public links synchronisation';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabePermitPublicUploadLinks'] = 'Permit public links synchronisation';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeLogUnlockEntity'] = 'sylabeLogUnlockEntity';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeLogUnlockEntity'] = 'sylabeLogUnlockEntity';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeLogUnlockEntity'] = 'sylabeLogUnlockEntity';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeLogLockEntity'] = 'sylabeLogLockEntity';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeLogLockEntity'] = 'sylabeLogLockEntity';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeLogLockEntity'] = 'sylabeLogLockEntity';
        $this->_table['fr-fr']['::sylabe:module:admin:option:sylabeLoadModules'] = 'Modules à charger';
        $this->_table['en-en']['::sylabe:module:admin:option:sylabeLoadModules'] = 'Modules to load';
        $this->_table['es-co']['::sylabe:module:admin:option:sylabeLoadModules'] = 'Modules to load';

        $this->_table['fr-fr']['::sylabe:module:admin:option:type:int'] = 'Entier';
        $this->_table['en-en']['::sylabe:module:admin:option:type:int'] = 'Integer';
        $this->_table['es-co']['::sylabe:module:admin:option:type:int'] = 'Integer';
        $this->_table['fr-fr']['::sylabe:module:admin:option:type:text'] = 'Texte';
        $this->_table['en-en']['::sylabe:module:admin:option:type:text'] = 'Text';
        $this->_table['es-co']['::sylabe:module:admin:option:type:text'] = 'Text';
        $this->_table['fr-fr']['::sylabe:module:admin:option:type:bool'] = 'Booléen';
        $this->_table['en-en']['::sylabe:module:admin:option:type:bool'] = 'Boolean';
        $this->_table['es-co']['::sylabe:module:admin:option:type:bool'] = 'Boolean';

        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeDisplayOnlineHelp'] = 'sylabeDisplayOnlineHelp';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeDisplayOnlineHelp'] = 'sylabeDisplayOnlineHelp';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeDisplayOnlineHelp'] = 'sylabeDisplayOnlineHelp';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeDisplayOnlineOptions'] = 'sylabeDisplayOnlineOptions';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeDisplayOnlineOptions'] = 'sylabeDisplayOnlineOptions';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeDisplayOnlineOptions'] = 'sylabeDisplayOnlineOptions';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeDisplayMetrology'] = 'sylabeDisplayMetrology';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeDisplayMetrology'] = 'sylabeDisplayMetrology';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeDisplayMetrology'] = 'sylabeDisplayMetrology';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeDisplayUnsecureURL'] = 'sylabeDisplayUnsecureURL';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeDisplayUnsecureURL'] = 'sylabeDisplayUnsecureURL';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeDisplayUnsecureURL'] = 'sylabeDisplayUnsecureURL';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeDisplayUnverifyLargeContent'] = 'sylabeDisplayUnverifyLargeContent';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeDisplayUnverifyLargeContent'] = 'sylabeDisplayUnverifyLargeContent';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeDisplayUnverifyLargeContent'] = 'sylabeDisplayUnverifyLargeContent';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeDisplayNameSize'] = 'sylabeDisplayNameSize';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeDisplayNameSize'] = 'sylabeDisplayNameSize';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeDisplayNameSize'] = 'sylabeDisplayNameSize';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeIOReadMaxDataPHP'] = 'sylabeIOReadMaxDataPHP';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeIOReadMaxDataPHP'] = 'sylabeIOReadMaxDataPHP';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeIOReadMaxDataPHP'] = 'sylabeIOReadMaxDataPHP';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabePermitUploadObject'] = 'sylabePermitUploadObject';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabePermitUploadObject'] = 'sylabePermitUploadObject';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabePermitUploadObject'] = 'sylabePermitUploadObject';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabePermitUploadLinks'] = 'sylabePermitUploadLinks';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabePermitUploadLinks'] = 'sylabePermitUploadLinks';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabePermitUploadLinks'] = 'sylabePermitUploadLinks';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabePermitPublicUploadObject'] = 'sylabePermitPublicUploadObject';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabePermitPublicUploadObject'] = 'sylabePermitPublicUploadObject';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabePermitPublicUploadObject'] = 'sylabePermitPublicUploadObject';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabePermitPublicUploadLinks'] = 'sylabePermitPublicUploadLinks';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabePermitPublicUploadLinks'] = 'sylabePermitPublicUploadLinks';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabePermitPublicUploadLinks'] = 'sylabePermitPublicUploadLinks';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeLogUnlockEntity'] = 'sylabeLogUnlockEntity';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeLogUnlockEntity'] = 'sylabeLogUnlockEntity';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeLogUnlockEntity'] = 'sylabeLogUnlockEntity';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeLogLockEntity'] = 'sylabeLogLockEntity';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeLogLockEntity'] = 'sylabeLogLockEntity';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeLogLockEntity'] = 'sylabeLogLockEntity';
        $this->_table['fr-fr']['::sylabe:module:admin:option:help:sylabeLoadModules'] = 'sylabeLoadModules';
        $this->_table['en-en']['::sylabe:module:admin:option:help:sylabeLoadModules'] = 'sylabeLoadModules';
        $this->_table['es-co']['::sylabe:module:admin:option:help:sylabeLoadModules'] = 'sylabeLoadModules';

    }
}
