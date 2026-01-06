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
use Nebule\Library\Module;
use Nebule\Library\ModelModuleHelp;
use Nebule\Library\ModuleTranslates;

/**
 * Ce module permet de gérer les groupes.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleQantion extends Module
{
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'qantion';
    const MODULE_DEFAULT_VIEW = 'list';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020260106';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2019-2026';
    const MODULE_LOGO = '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('list');
    const MODULE_REGISTERED_ICONS = array(
        '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256',    // 0 : World.
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');



    protected function _initialisation(): void {}



    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array
    {
        $hookArray = array();

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuQantion':
                $hookArray[] = array(
                    'name' => '::AppTitle1',
                    'icon' => $this::MODULE_LOGO,
                    'desc' => '::AppDesc1',
                    'link' => '?' . Displays::COMMAND_DISPLAY_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::COMMAND_DISPLAY_VIEW . '=' . self::MODULE_DEFAULT_VIEW
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                );
                break;
        }

        return $hookArray;
    }



    public function displayModule(): void
    {
        //switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
        //    case $this::MODULE_REGISTERED_VIEWS[0]:
        //        $this->_displayLangs();
        //        break;
        //    default:
        $this->_displayList();
        //        break;
        //}
    }

    public function displayModuleInline(): void
    {
        //switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
        //    case $this::MODULE_REGISTERED_VIEWS[0]:
        $this->_display_InlineList();
        //        break;
        //}
    }



    private function _displayList(): void
    {
        $this->_displaySimpleTitle('::display:Current', $this::MODULE_LOGO);

        $this->_displayNotImplemented(); // TODO

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('list');
    }

    /**
     * Affiche les groupes de l'entité en cours de visualisation, en ligne.
     *
     * @return void
     */
    private function _display_InlineList(): void
    {
        $this->_displaySimpleTitle('::display:List', $this::MODULE_LOGO);

        $this->_displayNotImplemented(); // TODO
    }



    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Qantion module',
            '::MenuName' => 'Qantion',
            '::ModuleDescription' => 'Qantion management module.',
            '::ModuleHelp' => 'This module permit to see and manage qantion.',
            '::AppTitle1' => 'Qantion',
            '::AppDesc1' => 'Manage qantion.',
            '::display:Current' => 'Selected qantion',
            '::display:List' => 'List of qantions',
        ],
        'en-en' => [
            '::ModuleName' => 'Qantion module',
            '::MenuName' => 'Qantion',
            '::ModuleDescription' => 'Qantion management module.',
            '::ModuleHelp' => 'This module permit to see and manage qantion.',
            '::AppTitle1' => 'Qantion',
            '::AppDesc1' => 'Manage qantion.',
            '::display:Current' => 'Selected qantion',
            '::display:List' => 'List of qantions',
        ],
        'es-co' => [
            '::ModuleName' => 'Qantion module',
            '::MenuName' => 'Qantion',
            '::ModuleDescription' => 'Qantion management module.',
            '::ModuleHelp' => 'This module permit to see and manage qantion.',
            '::AppTitle1' => 'Qantion',
            '::AppDesc1' => 'Manage qantion.',
            '::display:Current' => 'Selected qantion',
            '::display:List' => 'List of qantions',
        ],
    ];
}



/**
 * Here only old functions from the Actions class. Will be deleted soon...
 */
class archiveActionsQantion {
    const DEFAULT_COMMAND_ACTION_CREATE_CURRENCY = 'creacur';
    const DEFAULT_COMMAND_ACTION_CREATE_TOKEN_POOL = 'creactp';
    const DEFAULT_COMMAND_ACTION_CREATE_TOKENS = 'creactk';
    const DEFAULT_COMMAND_ACTION_CREATE_TOKENS_COUNT = 'creactkcnt';

    protected bool $_actionCreateCurrency = false;
    protected string $_actionCreateCurrencyID = '';
    protected ?\Nebule\Library\Currency $_actionCreateCurrencyInstance = null;
    protected array $_actionCreateCurrencyParam = array();
    protected bool $_actionCreateCurrencyError = false;
    protected string $_actionCreateCurrencyErrorMessage = 'Initialisation de la création.';
    public function getCreateCurrency(): bool
    {
        return $this->_actionCreateCurrency;
    }
    public function getCreateCurrencyID(): string
    {
        return $this->_actionCreateCurrencyID;
    }
    public function getCreateCurrencyInstance(): ?\Nebule\Library\Currency
    {
        return $this->_actionCreateCurrencyInstance;
    }
    public function getCreateCurrencyParam(): array
    {
        return $this->_actionCreateCurrencyParam;
    }
    public function getCreateCurrencyError(): bool
    {
        return $this->_actionCreateCurrencyError;
    }
    public function getCreateCurrencyErrorMessage(): string
    {
        return $this->_actionCreateCurrencyErrorMessage;
    }
    /**
     * Extrait pour action si une monnaie doit être créé.
     *
     * array( 'currency' => array(
     * ...,
     * 'CurrencyType' => array(
     * 'key' => 'TYP',
     * 'shortname' => 'ctyp',
     * 'type' => 'string',
     * 'info' => 'omcptyp',
     * 'limit' => '32',
     * 'force' => 'cryptocurrency', ),
     * 'display' => '64',
     * 'forceable' => true,
     * ...,
     * ),
     * ...,
     * )
     *
     * @return void
     */
    protected function _extractActionCreateCurrency(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateCurrency'))
            return;

        $this->_metrologyInstance->addLog('extract action create currency', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_CURRENCY);

        if ($argCreate !== false)
            $this->_actionCreateCurrency = true;

        if ($this->_actionCreateCurrency) {
            // Récupère la liste des propriétés.
            $instance = $this->_nebuleInstance->getCurrentCurrencyInstance();
            $propertiesList = $instance->getPropertiesList();
            unset($instance);

            /*foreach ($propertiesList['currency'] as $name => $property) {
                // Extrait une valeur.
                if (isset($property['checkbox'])) {
                    $value = '';
                    try {
                        $valueArray = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    } catch (\Exception $e) {
                        $valueArray = '';
                    }
                    $valueArray = $this->getFilterInput($property['shortname'], FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    foreach ($valueArray as $item)
                        $value .= ' ' . trim($item);
                    $this->_actionCreateCurrencyParam[$name] = trim($value);
                    unset($value, $valueArray);
                } else {
                    try {
                        $this->_actionCreateCurrencyParam[$name] = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    } catch (\Exception $e) {
                        $this->_actionCreateCurrencyParam[$name] = '';
                    }
                    $this->_actionCreateCurrencyParam[$name] = trim($this->_actionCreateCurrencyParam[$name]);
                }
                $this->_metrologyInstance->addLog('extract action create currency - _' . $property['shortname'] . ':' . $this->_actionCreateCurrencyParam[$name], Metrology::LOG_LEVEL_DEVELOP, __METHOD__, '00000000');

                // Extrait si forcé.
                if (isset($property['forceable'])) {
                    $this->_actionCreateCurrencyParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                    if ($this->_actionCreateCurrencyParam['Force' . $name])
                        $this->_metrologyInstance->addLog('extract action create currency - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                }
            }*/
        }
    }
    protected function _actionCreateCurrency(): void
    {
        $this->_metrologyInstance->addLog('action create currency', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instance = new \Nebule\Library\Currency($this->_nebuleInstance, 'new', $this->_actionCreateCurrencyParam, false, false);

        if (is_a($instance, 'Nebule\Library\Currency')
            && $instance->getID() != '0'
        ) {
            $this->_actionCreateCurrencyError = false;

            $this->_actionCreateCurrencyInstance = $instance;
            $this->_actionCreateCurrencyID = $instance->getID();

            $this->_metrologyInstance->addLog('action _actionCreateCurrency generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('action _actionCreateCurrency cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionCreateCurrencyError = true;
            $this->_actionCreateCurrencyErrorMessage = 'Echec de la génération.';
        }
    }

    protected bool $_actionCreateTokenPool = false;
    protected string $_actionCreateTokenPoolID = '';
    protected ?\Nebule\Library\TokenPool $_actionCreateTokenPoolInstance = null;
    protected array $_actionCreateTokenPoolParam = array();
    protected bool $_actionCreateTokenPoolError = false;
    protected string $_actionCreateTokenPoolErrorMessage = 'Initialisation de la création.';
    public function getCreateTokenPool(): bool
    {
        return $this->_actionCreateTokenPool;
    }
    public function getCreateTokenPoolID(): string
    {
        return $this->_actionCreateTokenPoolID;
    }
    public function getCreateTokenPoolInstance(): \Nebule\Library\TokenPool
    {
        return $this->_actionCreateTokenPoolInstance;
    }
    public function getCreateTokenPoolParam(): array
    {
        return $this->_actionCreateTokenPoolParam;
    }
    public function getCreateTokenPoolError(): bool
    {
        return $this->_actionCreateTokenPoolError;
    }
    public function getCreateTokenPoolErrorMessage(): string
    {
        return $this->_actionCreateTokenPoolErrorMessage;
    }
    protected function _extractActionCreateTokenPool(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateTokenPool'))
            return;

        $this->_metrologyInstance->addLog('extract action create token pool', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_TOKEN_POOL);

        if ($argCreate !== false)
            $this->_actionCreateTokenPool = true;

        if ($this->_actionCreateTokenPool) {
            // Récupère la liste des propriétés.
            $instance = $this->_nebuleInstance->getCurrentTokenPoolInstance();
            $propertiesList = $instance->getPropertiesList();
            unset($instance);

            /*foreach ($propertiesList['tokenpool'] as $name => $property) {
                // Extrait une valeur.
                if (isset($property['checkbox'])) {
                    $value = '';
                    try {
                        $valueArray = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    } catch (\Exception $e) {
                        $valueArray = '';
                    }
                    foreach ($valueArray as $item)
                        $value .= ' ' . trim($item);
                    $this->_actionCreateTokenPoolParam[$name] = trim($value);
                    unset($value, $valueArray);
                } else {
                    try {
                        $this->_actionCreateTokenPoolParam[$name] = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    } catch (\Exception $e) {
                        $this->_actionCreateTokenPoolParam[$name] = '';
                    }
                    $this->_actionCreateTokenPoolParam[$name] = trim($this->_actionCreateTokenPoolParam[$name]);
                }
                $this->_metrologyInstance->addLog('extract action create token pool - p' . $property['key'] . ':' . $this->_actionCreateTokenPoolParam[$name], Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

                // Extrait si forcé.
                if (isset($property['forceable'])) {
                    $this->_actionCreateTokenPoolParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                    if ($this->_actionCreateTokenPoolParam['Force' . $name])
                        $this->_metrologyInstance->addLog('extract action create token pool - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                }
            }*/
        }
    }
    protected function _actionCreateTokenPool(): void
    {
        $this->_metrologyInstance->addLog('action create token pool', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        $instance = new \Nebule\Library\TokenPool($this->_nebuleInstance, 'new', $this->_actionCreateTokenPoolParam, false, false);

        if (is_a($instance, 'Nebule\Library\TokenPool')
            && $instance->getID() != '0'
        ) {
            $this->_actionCreateTokenPoolError = false;

            $this->_actionCreateTokenPoolInstance = $instance;
            $this->_actionCreateTokenPoolID = $instance->getID();

            $this->_metrologyInstance->addLog('action _actionCreateTokenPool generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
        } else {
            // Si ce n'est pas bon.
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('action _actionCreateTokenPool cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionCreateTokenPoolError = true;
            $this->_actionCreateTokenPoolErrorMessage = 'Echec de la génération.';
        }
    }

    protected bool $_actionCreateTokens = false;
    protected array $_actionCreateTokensID = array();
    protected array $_actionCreateTokensInstance = array();
    protected array $_actionCreateTokensParam = array();
    protected int $_actionCreateTokensCount = 1;
    protected bool $_actionCreateTokensError = false;
    protected string $_actionCreateTokensErrorMessage = 'Initialisation de la création.';
    public function getCreateTokens(): bool
    {
        return $this->_actionCreateTokens;
    }
    public function getCreateTokensID(): array
    {
        return $this->_actionCreateTokensID;
    }
    public function getCreateTokensInstance(): array
    {
        return $this->_actionCreateTokensInstance;
    }
    public function getCreateTokensParam(): array
    {
        return $this->_actionCreateTokensParam;
    }
    public function getCreateTokensError(): bool
    {
        return $this->_actionCreateTokensError;
    }
    public function getCreateTokensErrorMessage(): string
    {
        return $this->_actionCreateTokensErrorMessage;
    }
    protected function _extractActionCreateTokens(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateTokens'))
            return;

        $this->_metrologyInstance->addLog('extract action create tokens', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $argCreate = filter_has_var(INPUT_GET, self::DEFAULT_COMMAND_ACTION_CREATE_TOKENS);

        if ($argCreate !== false)
            $this->_actionCreateTokens = true;

        if ($this->_actionCreateTokens) {
            // Récupère la liste des propriétés.
            $instance = $this->_tokenizeInstance->getCurrentTokenInstance();
            $propertiesList = $instance->getPropertiesList();
            unset($instance);

            /*foreach ($propertiesList['token'] as $name => $property) {
                // Extrait une valeur.
                if (isset($property['checkbox'])) {
                    $value = '';
                    try {
                        $valueArray = (string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FORCE_ARRAY);
                    } catch (\Exception $e) {
                        $valueArray = '';
                    }
                    foreach ($valueArray as $item)
                        $value .= ' ' . trim($item);
                    $this->_actionCreateTokensParam[$name] = trim($value);
                    unset($value, $valueArray);
                } else {
                    try {
                        $this->_actionCreateTokensParam[$name] = trim((string)filter_input(INPUT_POST, $property['shortname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
                    } catch (\Exception $e) {
                        $this->_actionCreateTokensParam[$name] = '';
                    }
                    $this->_actionCreateTokensParam[$name] = trim($this->_actionCreateTokensParam[$name]);
                }
                $this->_metrologyInstance->addLog('extract action create tokens - t' . $property['key'] . ':' . $this->_actionCreateTokensParam[$name], Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

                // Extrait si forcé.
                if (isset($property['forceable'])) {
                    $this->_actionCreateTokensParam['Force' . $name] = filter_has_var(INPUT_POST, 'f' . $property['shortname']);
                    if ($this->_actionCreateTokensParam['Force' . $name])
                        $this->_metrologyInstance->addLog('extract action create tokens - f' . $property['shortname'] . ':true', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                }
            }*/

            // Extrait le nombre de jetons à générer.
            $this->_actionCreateTokensCount = (int)$this->getFilterInput(self::DEFAULT_COMMAND_ACTION_CREATE_TOKENS_COUNT, FILTER_SANITIZE_NUMBER_INT);
        }
    }
    protected function _actionCreateTokens(): void
    {
        $this->_metrologyInstance->addLog('action create tokens', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        /*$instance = $this->_tokenizeInstance->getCurrentTokenInstance(); FIXME
        for ($i = 0; $i < $this->_actionCreateTokensCount; $i++) {
            if ($i > 0) {
                $this->_actionCreateTokensParam['TokenSerialID'] = '';
            }

            $instance = new Token($this->_nebuleInstance, 'new', $this->_actionCreateTokensParam, false, false);

            if (is_a($instance, 'Nebule\Library\Token')
                && $instance->getID() != '0'
            ) {
                $this->_actionCreateTokensError = false;

                $this->_actionCreateTokensInstance[$i] = $instance;
                $this->_actionCreateTokensID[$i] = $instance->getID();

                $this->_metrologyInstance->addLog('action _actionCreateTokens generated ID=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrologyInstance->addLog('action _actionCreateTokens cant generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionCreateTokensError = true;
                $this->_actionCreateTokensErrorMessage = 'Echec de la génération.';

                break;
            }
        }*/
    }
}
