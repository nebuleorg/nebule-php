<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * ------------------------------------------------------------------------------------------
 * La classe Currency.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'une monnaie ou 'new' ;
 * - un tableau des paramètres de la nouvelle monnaie.
 *
 * L'ID d'une monnaie est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture de la monnaie ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class Currency extends Node implements nodeInterface
{
    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_id',
        '_fullname',
        '_cachePropertyLink',
        '_cachePropertiesLinks',
        '_cachePropertyID',
        '_cachePropertiesID',
        '_cacheProperty',
        '_cacheProperties',
        '_cacheMarkDanger',
        '_cacheMarkWarning',
        '_cacheMarkProtected',
        '_idProtected',
        '_idUnprotected',
        '_idProtectedKey',
        '_idUnprotectedKey',
        '_markProtectedChecked',
        '_cacheCurrentEntityUnlocked',
        '_usedUpdate',
        '_isEntity',
        '_isGroup',
        '_isConversation',
        '_isCurrency',
        '_isTokenPool',
        '_isToken',
        '_isWallet',
        '_isTransaction',
        '_properties',
        '_propertiesInherited',
        '_propertiesForced',
        '_seed',
        '_inheritedCID',
        '_inheritedPID',
    );

    /**
     * Tableau des propriétés de la monnaie.
     * @var array
     */
    protected $_properties = array();

    /**
     * Tableau des propriétés héritées.
     * @var array
     */
    protected $_propertiesInherited = array();

    /**
     * Tableau des propriétés forcées.
     * @var array
     */
    protected $_propertiesForced = array();

    /**
     * Tableau des noms des propriétés disponibles.
     *
     * Le premier niveau définit les différentes catégories d'objets.
     *
     * Le second niveau nomme la propriété en interne et dispose de sous-propriétés :
     * - key : le nom publique de la clé pour l'objet.
     * - shortname : le nom de la clé pour les échangent en HTML, sans ambiguité par rapport au type d'objet.
     * - type : typage de la valeur attendu.
     * - info : le code de renvoi dans la page de documentation.
     * - force : valeur forcée dans le code, donc non modifiable lors de la création de l'objet.
     * - forceable : la clé:valeur peut être forcée dans l'objet généré. Sinon la valeur peut être surchargée par un lien.
     * - inherite : la valeur est hérité et n'est pas écrite.
     * - calculate : valeur calculée à postériori. Si présent, est équivalent à true.
     * - multivalue : clé à valeurs multiples séparées par des espaces. Si présent, est équivalent à true.
     * - limit : limit de la valeur attendu. Utilisé par les filtres de netoyage des valeurs.
     * - meta : l'objet à utiliser en temps que meta pour les liens.
     * - display : la taille du champs à utiliser pour la saisie de la valeur, non limitatif sur la taille de la valeur saisie.
     * - select : réduit le nombre de réponses possibles via un menu déroulant de taille fixe. Une seule réponse peut être sélectionnée.
     * - checkbox : permet une sélection multiple sur une liste. La valeur manipulée est une chaîne séparée par des espaces.
     *
     * @var array
     */
    protected $_propertiesList = array(
        'currency' => array(
            'CurrencyHaveContent' => array('key' => 'HCT', 'shortname' => 'chct', 'type' => 'boolean', 'info' => 'omcphct', 'force' => 'true',),
            'CurrencyType' => array('key' => 'TYP', 'shortname' => 'ctyp', 'type' => 'string', 'info' => 'omcptyp', 'limit' => '32', 'display' => '16', 'force' => 'cryptocurrency',),
            'CurrencySerialID' => array('key' => 'SID', 'shortname' => 'csid', 'type' => 'hexadecimal', 'info' => 'omcpsid', 'limit' => '1024', 'display' => '64',),
            'CurrencyCapacities' => array('key' => 'CAP', 'shortname' => 'ccap', 'type' => 'string', 'info' => 'omcpcap', 'limit' => '1024', 'display' => '128', 'force' => 'TYP MOD SID CAP AID MID NAM UNI DTA DTC DTD COM CPR IDM IDR IDP VMD VID TRS CID PID FID BID VAL CLB CLD SVC TID', 'multivalue' => true,),
            'CurrencyExploitationMode' => array('key' => 'MOD', 'shortname' => 'cmod', 'type' => 'string', 'info' => 'omcpmod', 'limit' => '3', 'display' => '10', 'force' => 'CTL',),
            'CurrencyAutorityID' => array('key' => 'AID', 'shortname' => 'caid', 'type' => 'hexadecimal', 'info' => 'omcpaid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'CurrencyCurrencyName' => array('key' => 'NAM', 'shortname' => 'cnam', 'type' => 'string', 'info' => 'omcpnam', 'limit' => '256', 'display' => '32', 'forceable' => true,),
            'CurrencyCurrencyUnit' => array('key' => 'UNI', 'shortname' => 'cuni', 'type' => 'string', 'info' => 'omcpuni', 'limit' => '3', 'display' => '6', 'forceable' => true,),
            'CurrencyDateAuthorityID' => array('key' => 'DTA', 'shortname' => 'cdta', 'type' => 'hexadecimal', 'info' => 'omcpdta', 'limit' => '1024', 'display' => '64', 'forceable' => true,),
            'CurrencyDateCreate' => array('key' => 'DTC', 'shortname' => 'cdtc', 'type' => 'date', 'info' => 'omcpdtc', 'display' => '32', 'forceable' => true,),
            'CurrencyDateDelete' => array('key' => 'DTD', 'shortname' => 'cdtd', 'type' => 'date', 'info' => 'omcpdtd', 'display' => '32', 'forceable' => true,),
            'CurrencyComment' => array('key' => 'COM', 'shortname' => 'ccom', 'type' => 'string', 'info' => 'omcpcom', 'limit' => '4096', 'display' => '128', 'forceable' => true,),
            'CurrencyCopyright' => array('key' => 'CPR', 'shortname' => 'ccpr', 'type' => 'string', 'info' => 'omcpcpr', 'limit' => '1024', 'display' => '128', 'forceable' => true,),
            'CurrencyInflateMode' => array('key' => 'IDM', 'shortname' => 'cidm', 'type' => 'string', 'info' => 'omcpidm', 'limit' => '10', 'display' => '12', 'forceable' => true, 'select' => array('disabled', 'creation', 'transaction'),),
            'CurrencyInflateRate' => array('key' => 'IDR', 'shortname' => 'cidr', 'type' => 'number', 'info' => 'omcpidr', 'limit' => '2', 'display' => '10', 'forceable' => true,),
            'CurrencyInflatePeriod' => array('key' => 'IDP', 'shortname' => 'cidp', 'type' => 'number', 'info' => 'omcpidp', 'limit' => '1048576', 'display' => '10', 'forceable' => true,),
            'CurrencyValidationMode' => array('key' => 'VMD', 'shortname' => 'cvmd', 'type' => 'string', 'info' => 'omcpvmd', 'limit' => '10', 'display' => '12', 'forceable' => true, 'select' => array('central'),),
            'CurrencyValidationID' => array('key' => 'VID', 'shortname' => 'cvid', 'type' => 'hexadecimal', 'info' => 'omcpvid', 'limit' => '1024', 'display' => '64', 'forceable' => true, 'multivalue' => true,),
            'CurrencyManageID' => array('key' => 'MID', 'shortname' => 'cmid', 'type' => 'hexadecimal', 'info' => 'omcpmid', 'limit' => '1024', 'display' => '64', 'forceable' => true, 'checkbox' => '', 'multivalue' => true,),
            'CurrencyTransacMethods' => array('key' => 'TRS', 'shortname' => 'ctrs', 'type' => 'string', 'info' => 'omcptrs', 'limit' => '1024', 'display' => '128', 'force' => 'LNS', 'multivalue' => true,),
            'CurrencyID' => array('key' => 'CID', 'shortname' => 'ccid', 'type' => 'hexadecimal', 'info' => 'omcpcid', 'limit' => '1024', 'display' => '64', 'calculate' => true,),
        ),
        'tokenpool' => array(
            'PoolHaveContent' => array('key' => 'HCT', 'shortname' => 'phct', 'type' => 'boolean', 'info' => 'omgcphct', 'force' => 'true',),
            'PoolType' => array('key' => 'TYP', 'shortname' => 'ptyp', 'type' => 'string', 'info' => 'omgcptyp', 'limit' => '32', 'display' => '16', 'force' => 'tokenpool',),
            'PoolSerialID' => array('key' => 'SID', 'shortname' => 'psid', 'type' => 'hexadecimal', 'info' => 'omgcpsid', 'limit' => '1024', 'display' => '64',),
            'PoolCapacities' => array('key' => 'CAP', 'shortname' => 'pcap', 'type' => 'string', 'info' => 'omgcpcap', 'display' => '128', 'inherite' => 'currency', 'multivalue' => true,),
            'PoolCurrencyID' => array('key' => 'CID', 'shortname' => 'pcid', 'type' => 'hexadecimal', 'info' => 'omgcpcid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'PoolForgeID' => array('key' => 'FID', 'shortname' => 'pfid', 'type' => 'hexadecimal', 'info' => 'omgcpfid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'PoolDateAuthorityID' => array('key' => 'DTA', 'shortname' => 'pdta', 'type' => 'hexadecimal', 'info' => 'omgcpdta', 'display' => '64', 'inherite' => 'currency',),
            'PoolDateCreate' => array('key' => 'DTC', 'shortname' => 'pdtc', 'type' => 'date', 'info' => 'omgcpdtc', 'display' => '32', 'forceable' => true,),
            'PoolDateDelete' => array('key' => 'DTD', 'shortname' => 'pdtd', 'type' => 'date', 'info' => 'omgcpdtd', 'display' => '32', 'forceable' => true,),
            'PoolComment' => array('key' => 'COM', 'shortname' => 'pcom', 'type' => 'string', 'info' => 'omgcpcom', 'limit' => '4096', 'display' => '128', 'forceable' => true,),
            'PoolCopyright' => array('key' => 'CPR', 'shortname' => 'pcpr', 'type' => 'string', 'info' => 'omgcpcpr', 'limit' => '1024', 'display' => '128', 'forceable' => true,),
            'PoolManageID' => array('key' => 'MID', 'shortname' => 'pmid', 'type' => 'hexadecimal', 'info' => 'omgcpmid', 'limit' => '1024', 'display' => '64', 'forceable' => true, 'checkbox' => '', 'multivalue' => true,),
            'PoolID' => array('key' => 'PID', 'shortname' => 'ppid', 'type' => 'hexadecimal', 'info' => 'omgcppid', 'limit' => '1024', 'display' => '64', 'calculate' => true,),
        ),
        'token' => array(
            'TokenHaveContent' => array('key' => 'HCT', 'shortname' => 'thct', 'type' => 'boolean', 'info' => 'omjcphct', 'force' => 'true',),
            'TokenType' => array('key' => 'TYP', 'shortname' => 'ttyp', 'type' => 'string', 'info' => 'omjcptyp', 'limit' => '32', 'display' => '16', 'force' => 'cryptoken',),
            'TokenSerialID' => array('key' => 'SID', 'shortname' => 'tsid', 'type' => 'hexadecimal', 'info' => 'omjcpsid', 'limit' => '1024', 'display' => '64',),
            'TokenCapacities' => array('key' => 'CAP', 'shortname' => 'tcap', 'type' => 'string', 'info' => 'omjcpcap', 'display' => '128', 'inherite' => 'currency', 'multivalue' => true,),
            'TokenCurrencyID' => array('key' => 'CID', 'shortname' => 'tcid', 'type' => 'hexadecimal', 'info' => 'omjcpcid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'TokenForgeID' => array('key' => 'FID', 'shortname' => 'tfid', 'type' => 'hexadecimal', 'info' => 'omjcpfid', 'limit' => '1024', 'display' => '64', 'force' => '',),
            'TokenPoolID' => array('key' => 'PID', 'shortname' => 'tpid', 'type' => 'hexadecimal', 'info' => 'omjcppid', 'limit' => '1024', 'display' => '64', 'inherite' => 'pool',),
            'TokenBlockID' => array('key' => 'BID', 'shortname' => 'tbid', 'type' => 'hexadecimal', 'info' => 'omjcpbid', 'limit' => '1024', 'display' => '64', 'forceable' => true,),
            'TokenCurrencyName' => array('key' => 'NAM', 'shortname' => 'tnam', 'type' => 'string', 'info' => 'omjcpnam', 'display' => '32', 'inherite' => 'currency',),
            'TokenCurrencyUnit' => array('key' => 'UNI', 'shortname' => 'tuni', 'type' => 'string', 'info' => 'omjcpuni', 'display' => '6', 'inherite' => 'currency',),
            'TokenValue' => array('key' => 'VAL', 'shortname' => 'tval', 'type' => 'number', 'info' => 'omjcpval', 'limit' => '1048576', 'display' => '10', 'forceable' => true,),
            'TokenService' => array('key' => 'SVC', 'shortname' => 'tsvc', 'type' => 'string', 'info' => 'omjcpsvc', 'limit' => '1024', 'display' => '128', 'forceable' => true,),
            'TokenDateAuthorityID' => array('key' => 'DTA', 'shortname' => 'tdta', 'type' => 'hexadecimal', 'info' => 'omjcpdta', 'display' => '64', 'inherite' => 'currency',),
            'TokenDateCreate' => array('key' => 'DTC', 'shortname' => 'tdtc', 'type' => 'date', 'info' => 'omjcpdtc', 'display' => '32', 'forceable' => true,),
            'TokenDateDelete' => array('key' => 'DTD', 'shortname' => 'tdtd', 'type' => 'date', 'info' => 'omjcpdtd', 'display' => '32', 'forceable' => true,),
            'TokenComment' => array('key' => 'COM', 'shortname' => 'tcom', 'type' => 'string', 'info' => 'omjcpcom', 'limit' => '4096', 'display' => '128', 'forceable' => true,),
            'TokenCopyright' => array('key' => 'CPR', 'shortname' => 'tcpr', 'type' => 'string', 'info' => 'omjcpcpr', 'limit' => '1024', 'display' => '128', 'forceable' => true,),
            'TokenInflateMode' => array('key' => 'IDM', 'shortname' => 'tidm', 'type' => 'string', 'info' => 'omjcpidm', 'display' => '12', 'inherite' => 'currency',),
            'TokenInflateRate' => array('key' => 'IDR', 'shortname' => 'tidr', 'type' => 'number', 'info' => 'omjcpidr', 'display' => '10', 'inherite' => 'currency',),
            'TokenInflatePeriod' => array('key' => 'IDP', 'shortname' => 'tidp', 'type' => 'number', 'info' => 'omjcpidp', 'display' => '10', 'inherite' => 'currency',),
            'TokenCancelable' => array('key' => 'CLB', 'shortname' => 'tclb', 'type' => 'boolean', 'info' => 'omjcpclb', 'forceable' => true,),
            'TokenCanceled' => array('key' => 'CLD', 'shortname' => 'tcld', 'type' => 'boolean', 'info' => 'omjcpcld', 'force' => 'false',),
            'TokenTransacMethods' => array('key' => 'TRS', 'shortname' => 'ttrs', 'type' => 'string', 'info' => 'omjcptrs', 'display' => '128', 'inherite' => 'currency', 'multivalue' => true,),
            'TokenID' => array('key' => 'TID', 'shortname' => 'ttid', 'type' => 'hexadecimal', 'info' => 'omjcptid', 'limit' => '1024', 'display' => '64', 'calculate' => true,),
        ),
    );

    /**
     * Compteur interne de génération de l'aléa.
     *
     * @var string
     */
    protected $_seed = '';

    /**
     * Instance de la monnaie dont dépend l'instance.
     * N'est pas utilisé pour une monnaie.
     *
     * @var Currency
     */
    protected $_inheritedCID = null;

    /**
     * Instance de la monnaie dont dépend l'instance.
     * N'est pas utilisé pour une monnaie.
     *
     * @var TokenPool
     */
    protected $_inheritedPID = null;

    /**
     * Tableau des capacités reconnues de la monnaie.
     *
     * @var array:string
     */
    protected $_CAParray = array();

    /**
     * Specific part of constructor for a currency.
     * @return void
     */
    protected function _localConstruct(): void
    {
        if ($this->_configuration->getOptionAsBoolean('permitCurrency'))
        {
            $this->_id = '0';
            $this->_isNew = false;
            return;
        }
        $this->_cacheCurrentEntityUnlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        if ($this->_id != '0')
            $this->_loadCurrency($this->_id);
    }

    /**
     *  Chargement d'une monnaie existant.
     *
     * @param string $id
     */
    private function _loadCurrency(string $id)
    {
        // Vérifie que c'est bien un objet.
        if ($id == ''
            || !ctype_xdigit($id)
            || !$this->_io->checkLinkPresent($id)
            || !$this->_configuration->getOptionAsBoolean('permitCurrency')
        ) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrology->addLog('Load currency ' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        // On ne recherche pas les paramètres si ce n'est pas une monnaie.
        if ($this->getIsCurrency('myself')) {
            $this->setAID();
            $this->setFID('0');
        }

        // Vérifie la monnaie.
        $TYP = $this->_getParamFromObject('TYP', (int)$this->_propertiesList['currency']['CurrencyType']['limit']);
        $SID = $this->_getParamFromObject('SID', (int)$this->_propertiesList['currency']['CurrencySerialID']['limit']);
        $CAP = $this->_getParamFromObject('CAP', (int)$this->_propertiesList['currency']['CurrencyCapacities']['limit']);
        $MOD = $this->_getParamFromObject('MOD', (int)$this->_propertiesList['currency']['CurrencyExploitationMode']['limit']);
        $AID = $this->_getParamFromObject('AID', (int)$this->_propertiesList['currency']['CurrencyAutorityID']['limit']);
        if ($TYP == ''
            || $SID == ''
            || $CAP == ''
            || $MOD == ''
            || $AID == ''
        ) {
            $this->_id = '0';
        } else {
            $this->_propertiesList['currency']['CurrencyID']['force'] = $id;
            $this->_properties['CID'] = $id;
        }
        $this->_propertiesForced['TYP'] = true;
        $this->_propertiesForced['SID'] = true;
        $this->_propertiesForced['CAP'] = true;
        $this->_propertiesForced['MOD'] = true;
        $this->_propertiesForced['AID'] = true;
        $this->_propertiesForced['CID'] = true;
    }

    /**
     * Création d'une nouvelle monnaie.
     *
     * @param array $param
     * @param bool  $protected
     * @param bool  $obfuscated
     * @return boolean
     */
    public function setNewCurrency(array $param, bool $protected = false, bool $obfuscated = false): bool
    {
        $this->_metrology->addLog('Ask create currency', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        if (!$this->_isNew
            || sizeof($param) == 0
            || ( get_class($this) != 'Currency'
                && get_class($this) != 'Nebule\Library\Currency'
            )
        )
            return false;

        // Vérifie que l'on puisse créer une monnaie.
        if ($this->_configuration->getOptionAsBoolean('permitWrite')
            && $this->_configuration->getOptionAsBoolean('permitWriteObject')
            && $this->_configuration->getOptionAsBoolean('permitWriteLink')
            && $this->_configuration->getOptionAsBoolean('permitCurrency')
            && $this->_configuration->getOptionAsBoolean('permitWriteCurrency')
            && $this->_configuration->getOptionAsBoolean('permitCreateCurrency')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génère la nouvelle monnaie.
            $this->_id = $this->_createCurrency($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrology->addLog('Create currency error on generation', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create currency error not autorized', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
            $this->_id = '0';
            return false;
        }
        return true;
    }



    // Désactivation des fonctions de protection et autres.

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return boolean
     */
    public function getReloadMarkProtected(): bool
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return string
     */
    public function getProtectedID(): string
    {
        return '0';
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return string
     */
    public function getUnprotectedID(): string
    {
        return $this->_id;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @param bool $obfuscated
     * @return boolean
     */
    public function setProtected(bool $obfuscated = false): bool
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return boolean
     */
    public function setUnprotected(): bool
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @param string|Entity $entity
     * @return boolean
     */
    public function setProtectedTo($entity): bool
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les monnaies.
     *
     * @return array
     */
    public function getProtectedTo(): array
    {
        return array();
    }


    /**
     * Nettoye la table des paramètres en entrée avant exploitation.
     *
     * @param array $param
     * @return void
     * @todo faire le forcage de valeurs.
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
     */
    protected function _normalizeInputParam(&$param)
    {
        foreach ($param as $key => $value) {
            foreach ($this->_propertiesList as $type => $nameArray) {
                // Si ce n'est pas le type d'objet en cours de création, continue.
                if (strtolower(get_class($this)) != $type) {
                    continue;
                }

                foreach ($nameArray as $name => $propArray) {
                    if ($key == $name) {
                        $param[$key] = null;

                        if (isset($propArray['force'])) {
                            // Détecte une tentative de modifier une valeur forçée.
                            if ($value != $propArray['force']
                                && $value != ''
                                && $value != null
                            ) {
                                $this->_metrology->addLog(get_class($this) . ' - Try to overwrite forced value ' . $propArray['key'] . ':' . $value, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
                            }

                            $this->_metrology->addLog(get_class($this) . ' - Normalize force ' . $propArray['key'] . ':' . $propArray['force'], Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                            if ($propArray['type'] == 'boolean') {
                                if ($propArray['force'] == 'true') {
                                    $param[$key] = true;
                                } else {
                                    $param[$key] = false;
                                }
                            } elseif ($propArray['type'] == 'number') {
                                $param[$key] = (int)$propArray['force'];
                            } else {
                                $param[$key] = $propArray['force'];
                            }
                        } else {
                            if ($propArray['type'] == 'boolean'
                                && is_string($value)
                            ) {
                                $this->_metrology->addLog(get_class($this) . ' - Normalize bool ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                                if ($value == 'true') {
                                    $param[$key] = true;
                                }
                                if ($value == 'false') {
                                    $param[$key] = false;
                                }
                                break 2;
                            }

                            if ($propArray['type'] == 'string') {
                                $this->_metrology->addLog(get_class($this) . ' - Normalize string ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                                if (isset($propArray['limit'])
                                    && strlen($value) > (int)$propArray['limit']
                                ) {
                                    $param[$key] = substr($value, 0, (int)$propArray['limit']);
                                } else {
                                    $param[$key] = $value;
                                }
                                break 2;
                                // @todo faire validation des selects.
                            }

                            if ($propArray['type'] == 'hexadecimal') {
                                if (isset($propArray['checkbox'])) {
                                    $this->_metrology->addLog(get_class($this) . ' - Normalize multi hex ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                                    $valueArray = explode(' ', $value);
                                    $value = '';
                                    foreach ($valueArray as $item) {
                                        if (ctype_xdigit($item)) {
                                            $value .= ' ' . $item;
                                        }
                                    }
                                    unset($valueArray);

                                    if (isset($propArray['limit'])
                                        && strlen(trim($value)) > (int)$propArray['limit']
                                    ) {
                                        $param[$key] = substr(trim($value), 0, (int)$propArray['limit']);
                                    } else {
                                        $param[$key] = trim($value);
                                    }
                                } else {
                                    $this->_metrology->addLog(get_class($this) . ' - Normalize hex ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                                    if (!ctype_xdigit($value)) {
                                        // Si pas un hexa, supprime la valeur.
                                        $param[$name] = null;
                                    }
                                    if (isset($propArray['limit'])
                                        && strlen($value) > (int)$propArray['limit']
                                    ) {
                                        $param[$key] = substr($value, 0, (int)$propArray['limit']);
                                    } else {
                                        $param[$key] = $value;
                                    }
                                }
                                break 2;
                            }

                            if ($propArray['type'] == 'date') {
                                $this->_metrology->addLog(get_class($this) . ' - Normalize date ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                                if (isset($propArray['limit'])
                                    && strlen($value) > (int)$propArray['limit']
                                ) {
                                    $param[$key] = substr($value, 0, (int)$propArray['limit']);
                                } else {
                                    $param[$key] = $value;
                                }
                                // @todo faire la vérification que c'est une date !
                                break 2;
                            }

                            if ($propArray['type'] == 'number'
                                && is_string($value)
                            ) {
                                $this->_metrology->addLog(get_class($this) . ' - Normalize number ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                                $param[$key] = (double)$value;
                                if (isset($propArray['limit'])
                                    && $value > (double)$propArray['limit']
                                ) {
                                    $param[$key] = (double)$propArray['limit'];
                                } else {
                                    $param[$key] = $value;
                                }
                                break 2;
                            }
                        }
                    }
                    if ($key == 'Force' . $name
                        && isset($propArray['forceable'])
                        && $propArray['forceable']
                    ) {
                        $param[$key] = false;
                        $this->_metrology->addLog(get_class($this) . ' - Normalize force ' . $key . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                        if ($value === true) {
                            $param[$key] = true;
                        }
                        break 2;
                    }
                }
            }
        }

        // Rempli les paramètres non renseignés.
        foreach ($this->_propertiesList as $nameArray) {
            foreach ($nameArray as $name => $propArray) {
                if (isset($param[$name])) {
                    continue;
                }

                if ($propArray['type'] == 'boolean') {
                    $param[$name] = false;
                    $this->_metrology->addLog(get_class($this) . ' - Normalize add ' . $propArray['key'] . ':false', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                } else {
                    $param[$name] = null;
                    $this->_metrology->addLog(get_class($this) . ' - Normalize add ' . $propArray['key'] . ':null', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                }
            }
        }
    }

    /**
     * Crée une monnaie.
     *
     * Les paramètres force* ne sont utilisés que si currencyHaveContent est à true.
     * Pour l'instant le code commence à prendre en compte currencyHaveContent à false mais le paramètre est forçé à true tant que le code n'est pas prêt.
     *
     * Les options pour la génération d'une monnaie :
     * currencyHaveContent
     * currencySerialID
     * currencyAutorityID
     *
     * forceCurrencyAutorityID
     *
     * Retourne la chaine avec 0 si erreur.
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
     * @param array $param
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return string
     */
    private function _createCurrency($param, $protected = false, $obfuscated = false)
    {
        // Identifiant final de la monnaie.
        $this->_id = '0';

        // Normalise les paramètres.
        $this->_normalizeInputParam($param);

        // Force l'écriture de l'objet de la monnaie.
        $param['CurrencyHaveContent'] = true;

        // Force l'écriture du serial.
        $param['ForceCurrencySerialID'] = true;

        // Force le paramètre AID avec l'entité en cours.
        $param['CurrencyAutorityID'] = $this->_nebuleInstance->getCurrentEntity();
        $param['ForceCurrencyAutorityID'] = true;
        $this->_properties['AID'] = $param['CurrencyAutorityID'];

        // Force les capacités.
        $param['ForceCurrencyCapacities'] = true;

        // Détermine si la monnaie a un numéro de série fourni.
        $sid = '';
        if (isset($param['CurrencySerialID'])
            && is_string($param['CurrencySerialID'])
            && $param['CurrencySerialID'] != ''
            && ctype_xdigit($param['CurrencySerialID'])
        ) {
            $sid = $this->_stringFilter($param['CurrencySerialID']);
            $this->_metrology->addLog('Generate currency asked SID:' . $sid, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        } else {
            // Génération d'un numéro de série de monnaie unique aléatoire.
            $sid = $this->_nebuleInstance->getCryptoInstance()->getRandom(128, Crypto::RANDOM_PSEUDO);
            $param['CurrencySerialID'] = $sid;
            $this->_metrology->addLog('Generate currency rand SID:' . $sid, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        }


        // Détermine si la monnaie doit avoir un contenu.
        if (isset($param['CurrencyHaveContent'])
            && $param['CurrencyHaveContent'] === true
        ) {
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' HCT:true', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

            // Le contenu final commence par l'identifiant interne de la monnaie.
            $content = 'TYP:' . $this->_propertiesList['currency']['CurrencyType']['force'] . "\n"; // @todo peut être intégré au reste.
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' TYP:' . $this->_propertiesList['currency']['CurrencyType']['force'], Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            $content .= 'SID:' . $sid . "\n";
            $content .= 'CAP:' . $this->_propertiesList['currency']['CurrencyCapacities']['force'] . "\n";
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' CAP:' . $this->_propertiesList['currency']['CurrencyCapacities']['force'], Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            $content .= 'MOD:' . $this->_propertiesList['currency']['CurrencyExploitationMode']['force'] . "\n";
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' MOD:' . $this->_propertiesList['currency']['CurrencyExploitationMode']['force'], Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            $content .= 'AID:' . $this->_nebuleInstance->getCurrentEntity() . "\n";
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' AID:' . $this->_nebuleInstance->getCurrentEntity(), Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

            // Pour chaque propriété, si présente et forcée, l'écrit dans l'objet.
            foreach ($this->_propertiesList['currency'] as $name => $property) {
                if ($property['key'] != 'HCT'
                    && $property['key'] != 'TYP'
                    && $property['key'] != 'SID'
                    && $property['key'] != 'CAP'
                    && $property['key'] != 'MOD'
                    && $property['key'] != 'AID'
                    //&& $property['key'] != 'PCN'
                    //&& $property['key'] != 'TCN'
                    && isset($property['forceable'])
                    && $property['forceable'] === true
                    && isset($param['Force' . $name])
                    && $param['Force' . $name] === true
                    && isset($param[$name])
                    && $param[$name] != ''
                    && $param[$name] != null
                ) {
                    $value = null;
                    if ($property['type'] == 'boolean') {
                        if ($param[$name] === true) {
                            $value = true;
                        } else {
                            $value = false;
                        }
                    } elseif ($property['type'] == 'number') {
                        $value = (string)$param[$name];
                    } else {
                        $value = $param[$name];
                    }

                    // Ajoute la ligne.
                    $content .= $property['key'] . ':' . $value . "\n";
                    $this->_metrology->addLog('Generate currency SID:' . $sid . ' force ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                }
            }

            // Crée l'objet avec le contenu et l'écrit.
            $this->_data = $content;
            $this->_haveData = true;
            unset($content);

            // calcul l'ID.
            $this->_id = $this->_crypto->hash($this->_data);

            // Si l'objet doit être protégé.
            if ($protected) {
                $this->setProtected($obfuscated);
            } else {
                // Sinon écrit l'objet directement.
                $this->write();
            }
        } else {
            $this->_id = $sid;
            $this->_metrology->addLog('Generate currency SID:' . $sid . ' HCT:false', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        }

        // La monnaie a maintenant un CID.
        $param['CurrencyID'] = $this->_id;
        $this->_metrology->addLog('Generate currency SID:' . $sid . ' CID:' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');


        // Prépare la génération des liens.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $source = $this->_id;
        $argObf = $obfuscated;

        // Le lien de type.
        $action = 'l';
        $target = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE);
        $meta = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $this->_createLink($signer, $date, $action, $source, $target, $meta, false);

        // Le lien de nommage si le nom est présent.
        if (isset($param['NAM'])
            && $param['NAM'] != null
            && $param['NAM'] != ''
        ) {
            $this->setName($param['NAM']);
        }

        // Crée les liens associés à la monnaie.
        //$action	= 'l';

        // Pour chaque propriété, si présente et a un méta, écrit le lien.
        foreach ($this->_propertiesList['currency'] as $name => $property) {
            if (isset($param[$name])
                && $param[$name] != null
            ) {
                $value = null;
                if ($property['type'] == 'boolean') {
                    if ($param[$name] === true) {
                        $value = 'true';
                    } else {
                        $value = 'false';
                    }
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'number') {
                    $value = (string)$param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'hexadecimal') {
                    $value = $param[$name];
                    $target = $value;
                } else {
                    $value = $param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(nebule::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                }

                if ($value != null) {
                    $this->_metrology->addLog('Generate currency SID:' . $sid . ' add ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash($property['key']);
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $argObf);
                    $this->_metrology->addLog('Generate currency SID:' . $sid . ' link=' . $target . '_' . $meta, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
                }
            }
        }


        // Retourne l'identifiant de la monnaie.
        $this->_metrology->addLog('Generate currency end SID:' . $sid, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        return $this->_id;
    }


    /**
     * Filtrage d'un texte sur les caractères chiffres et lettres uniquement, et sur une seule ligne.
     *
     * @param string $value
     * @return string
     */
    protected function _stringFilter($value, $limit = 1024)
    {
        if ($value == '') {
            return '';
        }

        // Contenu retourné.
        $result = mb_convert_encoding(trim(strtok(filter_var(trim($value), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW | FILTER_FLAG_NO_ENCODE_QUOTES), "\n")), 'UTF-8'); // @todo faire la restriction de caractères plus fine...

        if ($result === false) {
            $result = '';
        }

        if (strlen($result) > $limit) {
            $result = substr($result, 0, $limit);
        }

        return $result;
    }

    /**
     * Retourne la liste des sacs de jetons de la monnaie.
     *
     * @return array:string
     */
    public function getPoolList()
    {
        return $this->_getItemList('CID');
    }

    /**
     * Retourne le nombre de sacs de jetons de la monnaie.
     *
     * @return integer
     */
    public function getPoolCount()
    {
        return sizeof($this->_getItemList('CID'));
    }

    protected function _getItemList($type)
    {
        $links1 = array();
        $links2 = array();
        $links3 = array();
        $list = array();

        // Prépare la recherche des monnaies.
        $referenceType = $this->_nebuleInstance->getCryptoInstance()->hash($type);
        $meta = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        if ($type == 'CID') {
            $target = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_SAC);
        } elseif ($type == 'PID') {
            $target = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_JETON);
        } else {
            return $list;
        }

        // Recherche les monnaies pour l'entité en cours.
        $links1 = $this->readLinksFilterFull(
            '',
            '',
            'l',
            '',
            $this->_id,
            $referenceType
        );

        // Filtrage type recherché. @todo faire filtrage sur MID.
        foreach ($links1 as $i => $link) {
            $instance = $this->_nebuleInstance->newObject($link->getHashSource());
            $links2 = $instance->readLinksFilterFull(
                $link->getHashSigner(),
                '',
                'l',
                $link->getHashSource(),
                $target,
                $meta
            );

            if (sizeof($links2) != 0) {
                $list[$link->getHashSource()] = $link->getHashSource();
            }
        }

        return $list;
    }

    /**
     * Lit une clé et retourne un texte avec la valeur.
     *
     * Cette fonction est héritée par les sacs de jetons et les jetons et doit fonctionner dans les différentes classes.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string|null
     */
    protected function _getParam($key, $maxsize)
    {
        // La réponse.
        $result = null;

        if (!is_a($this, 'Currency')) {
            return $result;
        }

        // Lit le cache.
        if (isset($this->_properties[$key])) {
            $result = $this->_properties[$key];
        }

        // Traite les paramètres de présence de données forçées à part.
        if ($key == 'HCT'
            && $this->checkPresent()
        ) {
            $this->_propertiesForced['HCT'] = true;
            $result = true;
        }

        // Traite les paramètres de nombre de sacs ou jetons à part.
        /*		if ( $key == 'PCN' )
		{
			$result = sizeof($this->_getItemList('PID'));
		}
		if ( $key == 'TCN' )
		{
			$result = sizeof($this->_getItemList('TID'));
		}*/

        // Cherche l'option dans la monnaie définie (pas pour les monnaies).
        if ($result === null
            && $key != 'HCT'
            && $key != 'TYP'
            && $key != 'SID'
            && $key != 'FID'
            && $key != 'BID'
            && $key != 'COM'
            && $key != 'CPR'
        ) {
            $result = $this->_getParamFromCurrency($key, $maxsize);
        }

        // Cherche l'option dans le sac de jetons (pour les jetons).
        if ($result === null
            && $key != 'HCT'
            && $key != 'TYP'
            && $key != 'SID'
            && $key != 'FID'
            && $key != 'BID'
            && $key != 'COM'
            && $key != 'CPR'
        ) {
            $result = $this->_getParamFromPool($key, $maxsize);
        }

        // Cherche l'option dans l'environnement.
        if ($result === null) {
            $result = $this->_getParamFromObject($key, $maxsize);
        }

        // Si non trouvé, cherche l'option dans les liens.
        if ($result === null) {
            $result = $this->_getParamFromLinks($key, $maxsize);
        }

        $this->_metrology->addLog(get_class($this) . ' return param final ' . $this->_id . ' - ' . $key . ' = ' . (string)$result, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        // Ecrit le cache.
        $this->_properties[$key] = $result;

        return $result;
    }

    /**
     * Lit une clé dans la monnaie définie retourne un texte avec la valeur.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string|null
     */
    protected function _getParamFromCurrency($key, $maxsize)
    {
        $return = null;

        // N'est pas utilisable pour une monnaie.
        if ($this->_inheritedCID === null
            || get_class($this) == 'Currency'
        ) {
            return null;
        }

        $this->_metrology->addLog(get_class($this) . ' get param on currency ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        $return = $this->_inheritedCID->getParam($key);

        $this->_propertiesForced[$key] = $this->_inheritedCID->getParamForced($key);

        if ($return == ''
            && !$this->_propertiesForced[$key]
        ) {
            $return = null;
        }

        if ($return === null) {
            $this->_propertiesInherited[$key] = false;
        } else {
            $this->_propertiesInherited[$key] = true;
            $this->_metrology->addLog(get_class($this) . ' return param inherited on currency ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        }

        $this->_metrology->addLog(get_class($this) . ' return param on currency ' . $this->_id . ' - ' . $key . ':' . (string)$return, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        return $return;
    }

    /**
     * Lit une clé dans le sac de jetons défini et retourne un texte avec la valeur.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string|null
     */
    protected function _getParamFromPool($key, $maxsize)
    {
        $return = null;

        // N'est pas utilisable pour une monnaie ou un sac de jetons.
        if ($this->_inheritedPID === null
            || get_class($this) == 'Currency'
            || get_class($this) == 'TokenPool'
        ) {
            return null;
        }

        $this->_metrology->addLog(get_class($this) . ' get param on pool ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        $return = $this->_inheritedPID->getParam($key);

        $this->_propertiesForced[$key] = $this->_inheritedPID->getParamForced($key);

        if ($return == ''
            && !$this->_propertiesForced[$key]
        ) {
            $return = null;
        }

        if ($return === null) {
            $this->_propertiesInherited[$key] = false;
        } else {
            $this->_propertiesInherited[$key] = true;
            $this->_metrology->addLog(get_class($this) . ' return param inherited on pool ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        }

        $this->_metrology->addLog(get_class($this) . ' return param on pool ' . $this->_id . ' - ' . $key . ':' . (string)$return, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        return $return;
    }

    /**
     * Lit une clé dans le contenu de l'objet et retourne un texte avec la valeur.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string|null
     */
    protected function _getParamFromObject($key, $maxsize)
    {
        $result = null;

        // Lit le contenu de l'objet.
        $maxLimit = $this->_configuration->getOptionUntyped('ioReadMaxData');
        $content = $this->getContent($maxLimit);

        // Si l'objet monnaie a du contenu.
        if ($content != null) {
            // Extrait un tableau avec une ligne par élément.
            $contentArray = explode("\n", $content);
            unset($content);

            foreach ($contentArray as $i => $line) {
                $l = trim($line);

                // Si commentaire, passe à la ligne suivante.
                if (substr($l, 0, 1) == "#"
                    || ($key == 'TYP' && $i != 0)
                    || ($key == 'SID' && $i != 1)
                    || (get_class($this) == 'Currency' && $key == 'CAP' && $i != 2)
                    || (get_class($this) == 'Currency' && $key == 'MOD' && $i != 3)
                    || (get_class($this) == 'Currency' && $key == 'AID' && $i != 4)
                    || (get_class($this) == 'TokenPool' && $key == 'CID' && $i != 2)
                    || (get_class($this) == 'Token' && $key == 'CID' && $i != 2)
                    || (get_class($this) == 'Token' && $key == 'PID' && $i != 3)
                ) {
                    continue;
                }

                // Recherche l'option demandée.
                if (filter_var(trim(strtok($l, ':')), FILTER_SANITIZE_STRING) == $key) {
                    $result = trim(filter_var(trim(substr($l, strpos($l, ':') + 1)), FILTER_SANITIZE_STRING));
                    break;
                }
            }
            unset($contentArray, $line, $l);
        }

        // Ecrit l'état de forçage de la valeur pour la clé.
        if ($result === null) {
            $this->_propertiesForced[$key] = false;
        } else {
            $this->_propertiesForced[$key] = true;
            $this->_metrology->addLog(get_class($this) . ' return param forced on object ' . $this->_id . ' - ' . $key, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        }

        $this->_metrology->addLog(get_class($this) . ' return param on object ' . $this->_id . ' - ' . $key . ':' . (string)$result, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        return $result;
    }

    /**
     * Lit une clé dans les liens de l'objet et retourne un texte avec la valeur.
     *
     * @param string $key
     * @param integer $maxsize
     * @return string
     */
    protected function _getParamFromLinks($key, $maxsize)
    {
        $result = '';

        $id = '';
        $instance = null;

        $autorityList = array();

        // Vérifie que l'AID et/ou l'FID sont connus pour vérifier les liens.
        if (isset($this->_properties['AID'])) {
            $autorityList[] = $this->_properties['AID'];
        }
        if (isset($this->_properties['FID'])) {
            $autorityList[] = $this->_properties['FID']; // @todo à vérifier mais ce ne devrait pas être présent ici !?
        }

        $meta = '';
        foreach ($this->_propertiesList as $nameArray) {
            foreach ($nameArray as $name => $propArray) {
                if ($propArray['key'] == $key) {
                    $meta = $propArray['key'];
                    break 2;
                }
            }
        }

        $this->_metrology->addLog(get_class($this) . ' get param on links ' . $this->_id . ' - metatype:' . $meta, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        if ($meta != '') {
            $this->_social->setList($autorityList, 'onlist');
            $id = $this->getPropertyID($meta, 'onlist');
            //$id = $this->getPropertyID($meta, 'all');
            $this->_social->unsetList();
        }

        $this->_metrology->addLog(get_class($this) . ' get param on links ' . $this->_id . ' - meta:' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        if ($id != '') {
            $instance = $this->_nebuleInstance->newObject($id);
        }

        if (is_a($instance, 'Node')
            && $instance->getID() != '0'
        ) {
            // Extrait la valeur en fonction du type.
            foreach ($this->_propertiesList as $type => $nameArray) {
                // Si ce n'est pas le type d'objet attendu, continue.
                if (strtolower(get_class($this)) != $type) {
                    continue;
                }

                foreach ($nameArray as $name => $propArray) {
                    if ($key == $propArray['key']) {
                        if ($propArray['type'] == 'hexadecimal') {
                            $result = $instance->getID();
                        } else {
                            $result = $instance->getContent($maxsize);
                        }
                    }
                }
            }
        }

        unset($meta, $id, $instance);

        $this->_metrology->addLog(get_class($this) . ' return param on links ' . $this->_id . ' - ' . $key . ':' . (string)$result, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        if ($result == '') {
            return null;
        }
        return $result;
    }

    /**
     * Ecrit un paramètre en lien avec une monnaie ou dérivé.
     * Vérifie que le paramètre n'est pas en lecture seule, càd dans l'objet.
     * Le paramètre est enregistré sous forme d'un lien.
     *
     * @param unknown $key
     * @param unknown $value
     * @return boolean
     */
    protected function _setParam($key, $value)
    {
        // @todo

        return false;
    }

    /**
     * Lit une clé et retourne un texte avec la valeur.
     * La taille maximum dépend de la clé.
     *
     * @param string $key
     * @return string|null
     */
    public function getParam($key)
    {
        // @todo faire les vérifications des variables.

        // Détermine la taille maximum à lire.
        $maxsize = 0;
        foreach ($this->_propertiesList as $nameArray) {
            foreach ($nameArray as $name => $propArray) {
                if ($propArray['key'] == $key) {
                    if ($propArray['type'] == 'boolean') {
                        $maxsize = 5;
                        break 2;
                    } elseif ($propArray['type'] == 'number') {
                        $maxsize = 15;
                        break 2;
                    } elseif (isset($propArray['limit'])) {
                        $maxsize = (float)$propArray['limit'];
                        break 2;
                    }
                }
            }
        }

        if ($maxsize == 0) {
            return null;
        }

        return $this->_getParam($key, $maxsize);
    }

    /**
     * Lit si la valeur d'une clé est forçée par héritage.
     *
     * Pas d'héritage pour une monnaie mais c'est géré lors de la recherche de la clé.
     *
     * @param string $key
     * @return boolean
     */
    public function getParamInherited($key)
    {
        if (get_class($this) == 'Currency') {
            return false;
        }

        // Initalise les caches.
        if (!isset($this->_propertiesInherited[$key])) {
            $this->getParam($key);
        }

        // Retourne l'état d'héritage.
        if (isset($this->_propertiesInherited[$key])) {
            if ($this->_propertiesInherited[$key]) {
                $this->_metrology->addLog(get_class($this) . ' get param inherited ' . $this->_id . ' - ' . $key . ':true', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            } else {
                $this->_metrology->addLog(get_class($this) . ' get param inherited ' . $this->_id . ' - ' . $key . ':false', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            }
            return $this->_propertiesInherited[$key];
        } else {
            $this->_metrology->addLog(get_class($this) . ' get param inherited ' . $this->_id . ' - ' . $key . ':not defined (false)', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            return false;
        }
    }

    /**
     * Lit si la valeur d'une clé est forçée.
     *
     * @param string $key
     * @return boolean
     */
    public function getParamForced($key)
    {
        // Initalise les caches.

        if (!isset($this->_propertiesForced[$key])) {
            $this->getParam($key);
        }

        // Retourne l'état de forçage.
        if (isset($this->_propertiesForced[$key])) {
            if ($this->_propertiesForced[$key]) {
                $this->_metrology->addLog(get_class($this) . ' get param forced ' . $this->_id . ' - ' . $key . ':true', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            } else {
                $this->_metrology->addLog(get_class($this) . ' get param forced ' . $this->_id . ' - ' . $key . ':false', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            }
            return $this->_propertiesForced[$key];
        } else {
            $this->_metrology->addLog(get_class($this) . ' get param forced ' . $this->_id . ' - ' . $key . ':not defined (false)', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            return false;
        }
    }

    /**
     * Ecrit un paramètre en lien avec une monnaie ou dérivé.
     * Vérifie que le paramètre n'est pas en lecture seule, càd dans l'objet.
     * Le paramètre est enregistré sous forme d'un lien.
     * Un paramètre ne peut pas être ajouté à l'objet sans modifier son identifiant.
     *
     * @param unknown $key
     * @param unknown $value
     * @return boolean
     */
    public function setParam($key, $value)
    {
        // @todo faire les vérifications des variables.

        return $this->_setParam($key, $value);
    }

    /**
     * Définit la monnaie de référence utilisée.
     * N'est utilisé que pour les sacs de jetons et les jetons.
     *
     * @param $currency string|Currency
     * @return boolean
     */
    public function setCID($currency = '0')
    {
        if (is_string($currency)
            && $currency != ''
            && $currency != '0'
            && $this->_io->checkLinkPresent($currency)
        ) {
            $currency = new Currency($this->_nebuleInstance, $currency);
        }
        if (!get_class($currency) == 'Currency') {
            return false;
        }
        if (get_class($this) != 'TokenPool'
            && get_class($this) != 'Token'
        ) {
            return false;
        }
        if ($currency->getID() == '0') {
            return false;
        }

        // @todo vérifier que le CID est cohérent pour cet item dans les liens.

        $this->_metrology->addLog('set CID ' . $currency->getID() . ' for ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        $this->_inheritedCID = $currency;

        // Ecrit le cache.
        $this->_properties['CID'] = $currency->getID();

        return true;
    }

    /**
     * Définit le sac de jetons de référence utilisé.
     * N'est utilisé que pour les jetons.
     *
     * @param $pool string|TokenPool
     * @return boolean
     */
    public function setPID($pool = '0')
    {
        if (is_string($pool)
            && $pool != ''
            && $pool != '0'
            && $this->_io->checkLinkPresent($pool)
        ) {
            $pool = new TokenPool($this->_nebuleInstance, $pool);
        }
        if (!get_class($pool) == 'TokenPool') {
            return false;
        }
        if (get_class($this) != 'Token') {
            return false;
        }
        if ($pool->getID() == '0') {
            return false;
        }

        // @todo vérifier que le PID est cohérent pour ce jeton dans les liens.

        $this->_metrology->addLog('set PID ' . $pool->getID() . ' for ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        $this->_inheritedPID = $pool;

        // Ecrit le cache.
        $this->_properties['PID'] = $pool->getID();

        return true;
    }

    /**
     * Initialise l'AID, entité autorité de la monnaie.
     * Si l'instance est une monnaie, recherche le paramètre AID dans l'objet de la monnaie.
     *
     * @return boolean
     */
    public function setAID()
    {
        if (get_class($this) == 'Currency') {
            $id = $this->_getParamFromObject('AID', (int)$this->_propertiesList['currency']['CurrencyAutorityID']['limit']);
            $this->_metrology->addLog('set AID by param - AID:' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        } else {
            $id = $this->_getParamFromCurrency('AID', (int)$this->_propertiesList['currency']['CurrencyAutorityID']['limit']);
            $this->_metrology->addLog('set AID from currency - AID:' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
        }

        // Vérifie l'ID.
        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
            && $this->_io->checkObjectPresent($id)
//				&& $this->_io->checkLinkPresent($id)
        ) {
            $this->_properties['AID'] = $id;
            $this->_metrology->addLog('set AID ' . $id . ' for ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            return true;
        }
        $this->_metrology->addLog('error set AID for ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
        return false;
    }

    /**
     * Initialise l'FID, entité forge de la monnaie.
     * Si l'instance est une monnaie, recherche le paramètre FID et ne tient pas compte de l'argument de la fonction.
     *
     * @param string $id
     * @return boolean
     */
    public function setFID(string $id): bool
    {
        if (get_class($this) == 'Currency') {
            //$id = $this->_getParam('FID', (int)$this->_propertiesList['currency']['CurrencyForgeID']['limit']);
            $id = '';
        } elseif (get_class($this) == 'TokenPool') {
            $id = $this->_getParam('FID', (int)$this->_propertiesList['tokenpool']['PoolForgeID']['limit']);
        } elseif (get_class($this) == 'Token') {
            $id = $this->_getParam('FID', (int)$this->_propertiesList['token']['TokenForgeID']['limit']);
        }
        $this->_metrology->addLog('set FID by param', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');

        // Vérifie l'ID.
        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
            && $this->_io->checkObjectPresent($id)
//				&& $this->_io->checkLinkPresent($id)
        ) {
            $this->_properties['FID'] = $id;
            $this->_metrology->addLog('set FID ' . $id . ' for ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000');
            return true;
        }
        $this->_metrology->addLog('error set FID for ' . $this->_id, Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000');
        return false;
    }

    /**
     * Crée un lien.
     *
     * @return boolean
     */
    protected function _createLink($signer, $date, $action, $source, $target, $meta, $obfuscate = false): bool
    {
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new BlocLink($this->_nebuleInstance, $link);

        // Signe le lien.
        $newLink->sign($signer);

        // Si besoin, obfuscation du lien.
        if ($obfuscate
            && $this->_configuration->getOptionAsBoolean('permitObfuscatedLink')
        ) {
            $newLink->setObfuscate();
        }

        // Ecrit le lien.
        return $newLink->write();
    }

    /**
     * Extrait les capacités de la monnaie dans le champs CAP dans un tableau.
     * Ce tableau doit faciliter la vérification des champs à utiliser.
     *
     * @return void
     */
    private function _extractCAParray()
    {
        $this->_CAParray = array();

        if (!isset($this->_propertiesForced['CAP'])
            || sizeof($this->_propertiesForced['CAP']) == 0
        )
            return;

        $value = $this->_propertiesForced['CAP'];
        $subitems = explode(' ', $value);
        foreach ($subitems as $subitem)
            $this->_CAParray[$subitem] = $subitem;
    }

    /**
     * Retourne la liste des propriétés des objets monnaies et hérités.
     *
     * @return array
     */
    public function getPropertiesList()
    {
        return $this->_propertiesList;
    }


    /**
     * Extrait la valeur relative de la monnaie à un instant donné.
     *
     * @param $date string
     * @return double
     */
    public function getRelativeValue($date)
    {

        // Récupère la liste des jetons.
        $items = $this->_getItemList('CID');

        $total = 0;

        foreach ($items as $item) {
            $instance = $this->_nebuleInstance->newTokenPool_DEPRECATED($item);
            if ($instance->getID() != '0') {
                $total += $instance->getRelativeValue($date);
            }
        }

        return $total;
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#om">OM / Monnaie</a>
            <ul>
                <li><a href="#omf">OMF / Fonctionnement</a></li>
                <li><a href="#omn">OMN / Nommage</a></li>
                <li><a href="#omp">OMP / Protection</a></li>
                <li><a href="#omd">OMD / Dissimulation</a></li>
                <li><a href="#oml">OML / Liens</a></li>
                <li><a href="#omv">OMV / Valeur</a></li>
                <li><a href="#omc">OMC / Création</a>
                    <ul>
                        <li><a href="#omcl">OMCL / Liens</a></li>
                        <li><a href="#omcp">OMCP / Propriétés</a>
                            <ul>
                                <li><a href="#omcph">OMCPH / Héritage</a></li>
                                <li><a href="#omcphct">OMCPHCT / HCT</a></li>
                                <li><a href="#omcptyp">OMCPTYP / TYP</a></li>
                                <li><a href="#omcpsid">OMCPSID / SID</a></li>
                                <li><a href="#omcpcap">OMCPCAP / CAP</a></li>
                                <li><a href="#omcpmod">OMCPMOD / MOD</a></li>
                                <li><a href="#omcpaid">OMCPAID / AID</a></li>
                                <li><a href="#omcpmid">OMCPMID / MID</a></li>
                                <li><a href="#omcpfid">OMCPFID / FID</a></li>
                                <li><a href="#omcpcid">OMCPCID / CID</a></li>
                                <li><a href="#omcpnam">OMCPNAM / NAM</a></li>
                                <li><a href="#omcpuni">OMCPUNI / UNI</a></li>
                                <li><a href="#omcpdta">OMCPDTA / DTA</a></li>
                                <li><a href="#omcpdtc">OMCPDTC / DTC</a></li>
                                <li><a href="#omcpdtd">OMCPDTD / DTD</a></li>
                                <li><a href="#omcpcom">OMCPCOM / COM</a></li>
                                <li><a href="#omcpcpr">OMCPCPR / CPR</a></li>
                                <li><a href="#omcpidm">OMCPIDM / IDM</a></li>
                                <li><a href="#omcpidr">OMCPIDR / IDR</a></li>
                                <li><a href="#omcpidp">OMCPIDP / IDP</a></li>
                                <li><a href="#omcpvmd">OMCPVMD / VMD</a></li>
                                <li><a href="#omcpvid">OMCPVID / VID</a></li>
                                <li><a href="#omcptrs">OMCPTRS / TRS</a></li>
                                <li><a href="#omcppcn">OMCPPCN / PCN</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#oms">OMS / Stockage</a></li>
                <li><a href="#omt">OMT / Transfert</a></li>
                <li><a href="#omr">OMR / Réservation</a></li>
                <li><a href="#omio">OMIO / Implémentation des Options</a></li>
                <li><a href="#omia">OMIA / Implémentation des Actions</a></li>
                <li><a href="#omo">OMO / Oubli</a></li>
                <li><a href="#omg">OMG / Sac de jetons</a>
                    <ul>
                        <li><a href="#omgf">OMGF / Fonctionnement</a></li>
                        <li><a href="#omgn">OMGN / Nommage</a></li>
                        <li><a href="#omgp">OMGP / Protection</a></li>
                        <li><a href="#omgd">OMGD / Dissimulation</a></li>
                        <li><a href="#omgl">OMGL / Liens</a></li>
                        <li><a href="#omgv">OMGV / Valeur</a></li>
                        <li><a href="#omgc">OMGC / Création</a>
                            <ul>
                                <li><a href="#omgcl">OMGCL / Liens</a></li>
                                <li><a href="#omgcp">OMGCP / Propriétés</a>
                                    <ul>
                                        <li><a href="#omgcph">OMGCPH / Héritage</a></li>
                                        <li><a href="#omgcphct">OMGCPHCT / HCT</a></li>
                                        <li><a href="#omgcptyp">OMGCPTYP / TYP</a></li>
                                        <li><a href="#omgcpsid">OMGCPSID / SID</a></li>
                                        <li><a href="#omgcpcap">OMGCPCAP / CAP</a></li>
                                        <li><a href="#omgcpfid">OMGCPFID / FID</a></li>
                                        <li><a href="#omgcpmid">OMGCPMID / MID</a></li>
                                        <li><a href="#omgcppid">OMGCPPID / PID</a></li>
                                        <li><a href="#omgcpcid">OMGCPCID / CID</a></li>
                                        <li><a href="#omgcpdta">OMGCPDTA / DTA</a></li>
                                        <li><a href="#omgcpdtc">OMGCPDTC / DTC</a></li>
                                        <li><a href="#omgcpdtd">OMGCPDTD / DTD</a></li>
                                        <li><a href="#omgcpcom">OMGCPCOM / COM</a></li>
                                        <li><a href="#omgcpcpr">OMGCPCPR / CPR</a></li>
                                        <li><a href="#omgcpidm">OMGCPIDM / IDM</a></li>
                                        <li><a href="#omgcpidr">OMGCPIDR / IDR</a></li>
                                        <li><a href="#omgcpidp">OMGCPIDP / IDP</a></li>
                                        <li><a href="#omgcptcn">OMGCPTCN / TCN</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#omgs">OMGS / Stockage</a></li>
                        <li><a href="#omgt">OMGT / Transfert</a></li>
                        <li><a href="#omgr">OMGR / Réservation</a></li>
                        <li><a href="#omgo">OMGO / Oubli</a></li>
                    </ul>
                </li>
                <li><a href="#omj">OMJ / Jeton</a>
                    <ul>
                        <li><a href="#omjf">OMJF / Fonctionnement</a></li>
                        <li><a href="#omjn">OMJN / Nommage</a></li>
                        <li><a href="#omjp">OMJP / Protection</a></li>
                        <li><a href="#omjd">OMJD / Dissimulation</a></li>
                        <li><a href="#omjl">OMJL / Liens</a></li>
                        <li><a href="#omjv">OMJV / Valeur</a></li>
                        <li><a href="#omjc">OMJC / Création</a>
                            <ul>
                                <li><a href="#omjcl">OMJCL / Liens</a></li>
                                <li><a href="#omjcp">OMJCP / Propriétés</a>
                                    <ul>
                                        <li><a href="#omjcph">OMJCPH / Héritage</a></li>
                                        <li><a href="#omjcphct">OMJCPHCT / HCT</a></li>
                                        <li><a href="#omjcptyp">OMJCPTYP / TYP</a></li>
                                        <li><a href="#omjcpsid">OMJCPSID / SID</a></li>
                                        <li><a href="#omjcpcap">OMJCPCAP / CAP</a></li>
                                        <li><a href="#omjcpcid">OMJCPCID / CID</a></li>
                                        <li><a href="#omjcppid">OMJCPPID / PID</a></li>
                                        <li><a href="#omjcptid">OMJCPTID / TID</a></li>
                                        <li><a href="#omjcpfid">OMJCPFID / FID</a></li>
                                        <li><a href="#omjcpbid">OMJCPBID / BID</a></li>
                                        <li><a href="#omjcpnam">OMJCPNAM / NAM</a></li>
                                        <li><a href="#omjcpuni">OMJCPUNI / UNI</a></li>
                                        <li><a href="#omjcpval">OMJCPVAL / VAL</a></li>
                                        <li><a href="#omjcpdta">OMJCPDTA / DTA</a></li>
                                        <li><a href="#omjcpdtc">OMJCPDTC / DTC</a></li>
                                        <li><a href="#omjcpdtd">OMJCPDTD / DTD</a></li>
                                        <li><a href="#omjcpcom">OMJCPCOM / COM</a></li>
                                        <li><a href="#omjcpcpr">OMJCPCPR / CPR</a></li>
                                        <li><a href="#omjcpidm">OMJCPIDM / IDM</a></li>
                                        <li><a href="#omjcpidr">OMJCPIDR / IDR</a></li>
                                        <li><a href="#omjcpidp">OMJCPIDP / IDP</a></li>
                                        <li><a href="#omjcpsvc">OMJCPSVC / SVC</a></li>
                                        <li><a href="#omjcpclb">OMJCPCLB / CLB</a></li>
                                        <li><a href="#omjcpcld">OMJCPCLD / CLD</a></li>
                                        <li><a href="#omjcptrs">OMJCPTRS / TRS</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#omjs">OMJS / Stockage</a></li>
                        <li><a href="#omjt">OMJT / Transfert</a>
                            <ul>
                                <li><a href="#omjti">OMJCTI / Transfert d'information</a></li>
                                <li><a href="#omjtv">OMJCTV / Transfert de valeur</a></li>
                            </ul>
                        </li>
                        <li><a href="#omjm">OMJM / Modes de transfert</a>
                            <ul>
                                <li><a href="#omjmlns">OMJMLNS / Mode LNS</a></li>
                            </ul>
                        </li>
                        <li><a href="#omjr">OMJR / Réservation</a></li>
                        <li><a href="#omjo">OMJO / Oubli</a></li>
                    </ul>
                </li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h2 id="om">OM / Monnaie</h2>
        <p>Certains objets permettre de mettre en place et de gérer plusieurs types de monnaies et plusieurs monnaies
            concurrentes.</p>

        <h3 id="omf">OMF / Fonctionnement</h3>
        <p>Une monnaie est un objet de référence qui va gérer des sac de jetons. La gestion se fait par différentes
            entités détenant des rôles spécifiques aux monnaies.</p>
        <p>Une monnaie va disposer de plusieurs propriétés connues par leurs abréviations, voir <a href="#omcp">OMCP</a>.
        </p>
        <p>L'objet de référence de la monnaie peut être virtuel ou non. Aujourd'hui le code ne gère que des monnaies
            avec un objet de référence non virtuel.</p>
        <p>Si l'objet de référence de la monnaie est virtuel, il est forçément généré aléatoirement. Sinon il dépend du
            contenu de l'objet et est rendu unique grâce à la propriété <code>SID</code>.</p>
        <p>Exemple d'objet de monnaie :</p>
        <pre>
TYP:currency
SID:5f3ad5265bb3306b3266e1935d067d9ec15965d0a970554bc6161eb3328907a9
CAP:TYP MOD SID CAP AID MID NAM UNI DTA DTC DTD COM CPR IDM IDR IDP VMD VID TRS CID PID FID BID VAL CLB CLD SVC TID
MOD:CTL
AID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
MID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
NAM:poux
UNI:pou
CPR:(c) nebule/qantion 2020
</pre>

        <h3 id="omn">OMN / Nommage</h3>
        <p>Une monnaie peut disposer d'un nom complet. Ce nom est définit par la propriété <code>NAM</code> et est
            doublé par un lien de nommage classique comme tout objet.</p>
        <p>Une monnaie peut aussi disposer d'une abréviation définit par la propriété <code>UNI</code>.</p>
        <p>Le nom complet d'un objet de type monnaie est uniquement extrait de la propriété <code>NAM</code>. Dans
            certains cas il peut êrte formé de <code>NAM(UNI)</code> mais la propriété <code>UNI</code> a plutôt
            vocation a être utilisée dans un affichage condensé.</p>

        <h3 id="omp">OMP / Protection</h3>
        <p>A faire...</p>

        <h3 id="omd">OMD / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="oml">OML / Liens</h3>
        <p>A faire...</p>

        <h3 id="omv">OMV / Valeur</h3>
        <p>La valeur de la monnaie, à un instant donné, est égale à la somme des sac de jetons de la monnaie au même
            instant.</p>

        <h3 id="omc">OMC / Création</h3>
        <p>A faire...</p>

        <h4 id="omcl">OMCL / Liens</h4>
        <p>Liste des liens à générer lors de la création d'une monnaie.</p>
        <p>A faire...</p>

        <h4 id="omcp">OMCP / Propriétés</h4>
        <p></p>

        <h5 id="omcph">OMCPH / Héritage</h5>
        <p>Certaines propriétés des sacs de jetons et jetons sont héritées de la monnaie, si ces propriétés sont
            définies dans la monnaie. Les héritages sont prioritaires sur les propriétés définies via l'objet et les
            liens des sacs de jetons et jetons.</p>

        <h5 id="omcphct">OMCPHCT / HCT</h5>
        <p>Définit si l'objet de la monnaie a un contenu. Si il n'a pas de contenu l'objet de la monnaie est virtuel et
            correspond à son SID, et les paramètres de la monnaie ne peuvent pas être forcés.</p>
        <p>Ce n'est pas écrit dans l'objet de la monnaie ni enregistré via des liens. Cela sert uniquement au moment de
            la création d'une monnaie.</p>

        <h5 id="omcptyp">OMCPTYP / TYP</h5>
        <p>Le type de monnaie.</p>
        <p>Toujours à la valeur <i>cryptocurrency</i>.</p>
        <p>Présence obligatoire en ligne 1 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpsid">OMCPSID / SID</h5>
        <p>Le numéro de série identifiant de la monnaie (<i>serial</i>).</p>
        <p>Si l'objet de référence de la monnaie est virtuel, l'identifiant de la monnaie <code>CID</code> sera le
            <code>SID</code>.</p>
        <p>Si l'objet de référence de la monnaie n'est pas virtuel, l'identifiant de la monnaie <code>CID</code> dépend
            du contenu de l'objet et est rendu unique grâce à la propriété <code>SID</code>.</p>
        <p>La valeur est de préférence aléatoire mais peut être un compteur à condition d'être unique. L’utilisation
            d’un compteur de faible valeur est fortement déconseillée.</p>
        <p>Si aléatoire, la génération pseudo aléatoire du <code>SID</code> est faite en partant d’un dérivé de la date
            avec quelques valeurs locales. Il n’y a pas de contrainte de sécurité sur cette valeur. Puis une boucle
            interne génère un bon aléa au fur et à mesure de la génération des jetons via une fonction de hash. Le tout
            ne consomme pas du tout de précieux aléa de bonne qualité.</p>
        <p>Présence obligatoire en ligne 2 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpcap">OMCPCAP / CAP</h5>
        <p>Liste des capacités connues de la monnaie.</p>
        <p>Si une capacité n'est pas présente elle ne peut être invoquée, même si elle est forcée.</p>
        <p>Présence obligatoire en ligne 3 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpmod">OMCPMOP / MOP</h5>
        <p>Définit le mode d'exploitation de la monnaie.</p>
        <p>Si une capacité n'est pas présente elle ne peut être invoquée, même si elle est forcée.</p>
        <p>Présence obligatoire en ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpaid">OMCPAID / AID</h5>
        <p>Identifiant de l'entité authorité de la monnaie (<i>autority</i>).</p>
        <p>C'est l'entité qui forge la monnaie et délègue la gestion aux entités gestionnaires (MID).</p>
        <p>Présence obligatoire en ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpmid">OMCPMID / MID</h5>
        <p>Identifiant d'une entité de gestion de la monnaie (<i>manage</i>).</p>
        <p>Une monnaie peut avoir plusieurs entités de gestion.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpfid">OMCPFID / FID</h5>
        <p>Non utilisé !!!</p>
        <p>Identifiant de l'entité ayant forgé la monnaie (<i>forge</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpcid">OMCPCID / CID</h5>
        <p>Identifiant de l’objet de la monnaie (<i>currency</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème. Ce qui va faire la différence c'est l'autorité et ses liens.</p>
        <p>L'objet d'une monnaie ne peut en aucun cas contenir son propre identifiant <code>CID</code>.</p>

        <h5 id="omcpnam">OMCPNAM / NAM</h5>
        <p>Le nom de la monnaie. Limité à 256 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpuni">OMCPUNI / UNI</h5>
        <p>Le nom de l'unité de la monnaie en 3 lettres maximum. Pas de chiffre.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpdta">OMCPDTA / DTA</h5>
        <p>Identifiant de l’entité autorité de temps pour les limites de temps.</p>
        <p>La gestion du temps avec une autorité de temps permet de prendre en compte sérieusement les suppression
            programmées de jeton (<code>DTC</code>/<code>DTD</code>) ainsi que leur inflation/déflation automatique
            (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>).</p>
        <p>L’autorité de temps peut être spécifique pour chaque jeton mais il est plus logique qu’elle soit commune à
            une monnaie ou dans certains cas à un sac de jetons.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpdtc">OMCPDTC / DTC</h5>
        <p>Date de création de la monnaie.</p>
        <p>Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpdtd">OMCPDTD / DTD</h5>
        <p>Date de suppression programmée de la monnaie.</p>
        <p> Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date pour être
            fonctionel.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpcom">OMCPCOM / COM</h5>
        <p>Commentaire texte libre limité à 4096 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpcpr">OMCPCPR / CPR</h5>
        <p>Licence du jeton sous forme d’une texte libre limité à 1024 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpidm">OMCPIDM / IDM</h5>
        <p>Mode de fonctionnement du mécanisme d’inflation/déflation (<i>mode</i>) de tous les jetons de la monnaie.</p>
        <p>Les modes sont <i>creation</i> ou <i>transaction</i> ou <i>disabled</i>.</p>
        <p>Suivant le mode, le mécanisme tient compte du temps passé depuis la dernière transaction ou depuis l’émission
            du jeton.</p>
        <p>Le mécanisme d’inflation/déflation (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>), si activé, avec un
            taux de variation inférieur à 1, donc en déflation, permet de forcer les détenteurs de jeton à les utiliser
            plutôt que de les stocker. Les jetons concernés vont donc perdre de la valeur par rapport aux nouveaux
            jetons ou par rapport à ceux qui circulent.</p>
        <p>Si activé, avec un taux de variation suppérieur à 1, donc en inflation, permet de favoriser la conservation
            des jetons et valorise les vieux jetons sur les nouveaux ou ceux qui circulent beaucoup.</p>
        <p>En ne forçant pas cette propriété dans l'objet des jetons, il est possible d'avoir un taux de variation
            fluctuant en fonction des besoins. En le positionnant forçé à <i>disabled</i> cela désactive définitivement
            ce mécanisme au niveau de la monnaie et donc pour tous les jetons.</p>
        <p>Un jeton peut se déprécier avec le temps mais une entité peut demander à l’autorité émettrice de la monnaie
            un échange de jeton ancien contre un jeton plus jeune, si l’autorité émettrice le permet.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>CF <a href="#omgcpidm">P/IDM</a> et <a href="#omjcpidm">T/IDM</a>.</p>

        <h5 id="omcpidr">OMCPIDR / IDR</h5>
        <p>Taux de variation du mécanisme d’inflation/déflation (<i>rate</i>) de tous les jetons de la monnaie.</p>
        <p>Égal à 1 (un), taux constant donc pas de variation.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpidp">OMCPIDP / IDP</h5>
        <p>Périodicité d’application du taux de variation du mécanisme d’inflation/déflation (<i>period</i>) de tous les
            jetons de la monnaie.</p>
        <p>Unité exprimée en minutes.</p>
        <p>Si à 0 (zéro), la période n'est pas utilisé, donc la variation est non effective.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpvmd">OMCPVMD / VMD</h5>
        <p>Définit le mode de validation des transactions de jetons de la monnaie. C'est le mode de fonctionnement
            global de la monnaie.</p>
        <p>Actuellement seul est supporté le mode centralisé.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcpvid">OMCPVID / VID</h5>
        <p>Dans le mode de validation centralisé, c'est l'entité de validation des transactions de jetons de la
            monnaie.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcptrs">OMCPTRS / TRS</h5>
        <p>Liste des méthodes de transaction supportées.</p>
        <p>Le code <code>LNS</code> désigne la méthode de base avec un lien (L) matérialisant une transaction et
            imposant un jeton non sécable (NS).</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omcppcn">OMCPPCN / PCN</h5>
        <p>Non utilisé !!!</p>
        <p>Définit le nombre de sacs de jetons à créer avec la monnaie. Ce nombre multiplié par le <a href="#omgcptcn">TCN</a>
            donne le nombre total de jetons créés pour la monnaie.</p>
        <p>Ce n'est pas écrit dans l'objet de la monnaie, ni dans les sacs de jetons ni enregistré via des liens. Cela
            sert uniquement au moment de la création de la monnaie. Cependant un lien de rattachement sera créé pour
            chaque sac de jeton depuis la monnaie avec en meta le <a href="#omgcppid"></a>PID</a>.</p>

        <h3 id="oms">OMS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="omt">OMT/ Transfert</h3>
        <p>A faire...</p>

        <h3 id="omr">OMR / Réservation</h3>
        <p>Les objets réservés spécifiquement pour les monnaies :</p>
        <ul>
            <li>nebule/objet/monnaie</li>
        </ul>

        <h4 id="omio">OMIO / Implémentation des Options</h4>
        <p>A faire...</p>

        <h4 id="omia">OMIA / Implémentation des Actions</h4>
        <p>A faire...</p>

        <h3 id="omo">OMO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>


        <h3 id="omg">OMG / Sac de jetons</h3>
        <p>A faire...</p>

        <h3 id="omgf">OMGF / Fonctionnement</h3>
        <p>A faire...</p>
        <p>Exemple d'objet de sac de jetons :</p>
        <pre>
TYP:tokenpool
SID:5f3ad5265bb3306b3266e1935d067d9ec15965d0a970554bc6161eb3328907a9
CAP:TYP MOD SID CAP AID MID NAM UNI DTA DTC DTD COM CPR IDM IDR IDP VMD VID TRS CID PID FID BID VAL CLB CLD SVC TID
CID:daf832e3042cc849efcd5b6531df835a9c5f6251b2101e20972f9a9db2a8ae24
FID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
MID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
</pre>
        <p>Un sac de jetons va disposer de plusieurs propriétés connues par leurs abréviations, voir <a href="#omgcp">OMGCP</a>.
        </p>
        <p>A faire...</p>

        <h3 id="omgn">OMGN / Nommage</h3>
        <p>Un sac de jetons hérite du nommage de la monnaie à laquelle il est rattaché.</p>
        <p>Le nom complet d'un objet de type sac de jetons est uniquement extrait de la propriété <code>NAM</code>. Dans
            certains cas il peut êrte formé de <code>NAM(UNI)</code> mais la propriété <code>UNI</code> a plutôt
            vocation a être utilisée dans un affichage condensé.</p>

        <h3 id="omgp">OMGP / Protection</h3>
        <p>A faire...</p>

        <h3 id="omgd">OMGD / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="omgl">OMGL / Liens</h3>
        <p>A faire...</p>

        <h3 id="omgv">OMGV / Valeur</h3>
        <p>La valeur du sac de jeton, à un instant donné, est égale à la somme des jetons du sac au même instant.</p>

        <h3 id="omgc">OMGC / Création</h3>
        <p>A faire...</p>

        <h4 id="omgcl">OMGCL / Liens</h4>
        <p>Liste des liens à générer lors de la création d'un sac de jetons.</p>
        <p>A faire...</p>

        <h4 id="omgcp">OMGCP / Propriétés</h4>
        <p></p>

        <h5 id="omgcph">OMGCPH / Héritage</h5>
        <p>Certaines propriétés sont héritées de la monnaie, si ces propriétés sont définies dans la monnaie. Ce doit
            être la monnaie déclarée en cours d'utilisation et le sac de jetons doit dépendre de cette monnaie
            directement. Les héritages sont prioritaires sur les propriétés définies via l'objet et les liens.</p>

        <h5 id="omgcphct">OMGCPHCT / HCT</h5>
        <p>Définit si l'objet du sac de jetons a un contenu. Si il n'a pas de contenu l'objet du sac de jetons est
            virtuel et correspond à son SID, et les paramètres du sac de jetons ne peuvent pas être forcés.</p>
        <p>Ce n'est pas écrit dans l'objet du sac de jetons ni enregistré via des liens. Cela sert uniquement au moment
            de la création d'un sac de jetons.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcptyp">OMGCPTYP / TYP</h5>
        <p>Le type de sac de jetons.</p>
        <p>Toujours à la valeur <i>tokenpool</i>.</p>
        <p>Présence obligatoire en ligne 1 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpsid">OMGCPSID / SID</h5>
        <p>Le numéro de série identifiant du sac de jetons (<i>serial</i>).</p>
        <p>Si l'objet de référence du sac de jetons est virtuel, l'identifiant du sac de jetons <code>PID</code> sera le
            <code>SID</code>.</p>
        <p>Si l'objet de référence du sac de jetons n'est pas virtuel, l'identifiant du sac de jetons <code>PID</code>
            dépend du contenu de l'objet et est rendu unique grâce à la propriété <code>SID</code>.</p>
        <p>La valeur est de préférence aléatoire mais peut être un compteur à condition d'être unique. L’utilisation
            d’un compteur de faible valeur est fortement déconseillée.</p>
        <p>Si aléatoire, la génération pseudo aléatoire du <code>SID</code> est faite en partant d’un dérivé de la date
            avec quelques valeurs locales. Il n’y a pas de contrainte de sécurité sur cette valeur. Puis une boucle
            interne génère un bon aléa au fur et à mesure de la génération des jetons via une fonction de hash. Le tout
            ne consomme pas du tout de précieux aléa de bonne qualité.</p>
        <p>Présence obligatoire en ligne 2 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpcap">OMGCPCAP / CAP</h5>
        <p>Liste des capacités connues du sac de jetons.</p>
        <p>Si une capacité n'est pas présente elle ne peut être invoquée, même si elle est forcée.</p>
        <p>Présence obligatoire en ligne 3 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpcid">OMGCPCID / CID</h5>
        <p>Identifiant de l’objet de la monnaie auquel est rattaché le sac de jetons (<i>currency</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence obligatoire en ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpfid">OMGCPFID / FID</h5>
        <p>Identifiant de l'entité ayant forgé le sac de jetons (<i>forge</i>).</p>
        <p>L'entité forge doit désigner une entité de gestion, par défaut c'est elle-même.</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpmid">OMGCPMID / MID</h5>
        <p>Identifiant de l'entité de gestion du sac de jetons (<i>manage</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcppid">OMGCPPID / PID</h5>
        <p>Identifiant de l’objet du sac de jetons (<i>pool</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>L'objet d'un sac de jetons ne peut en aucun cas contenir son propre identifiant <code>PID</code>.</p>

        <h5 id="omgcpdta">OMGCPDTA / DTA</h5>
        <p>Identifiant de l’entité autorité de temps pour les limites de temps.</p>
        <p>La gestion du temps avec une autorité de temps permet de prendre en compte sérieusement les suppression
            programmées de jeton (<code>DTC</code>/<code>DTD</code>) ainsi que leur inflation/déflation automatique
            (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>).</p>
        <p>L’autorité de temps peut être spécifique pour chaque jeton mais il est plus logique qu’elle soit commune à
            une monnaie ou dans certains cas à un sac de jetons.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpdtc">OMGCPDTC / DTC</h5>
        <p>Date de création du sac de jetons.</p>
        <p>Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpdtd">OMGCPDTD / DTD</h5>
        <p>Date de suppression programmée du sac de jetons.</p>
        <p> Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date pour être
            fonctionel.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpcom">OMGCPCOM / COM</h5>
        <p>Commentaire texte libre limité à 4096 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpcpr">OMGCPCPR / CPR</h5>
        <p>Licence du jeton sous forme d’une texte libre limité à 1024 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie.</p>

        <h5 id="omgcpidm">OMGCPIDM / IDM</h5>
        <p>Mode de fonctionnement du mécanisme d’inflation/déflation (<i>mode</i>) des jetons dépendants du sac de
            jetons.</p>
        <p>Les modes sont <i>creation</i> ou <i>transaction</i> ou <i>disabled</i>.</p>
        <p>Suivant le mode, le mécanisme tient compte du temps passé depuis la dernière transaction ou depuis l’émission
            du jeton.</p>
        <p>Le mécanisme d’inflation/déflation (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>), si activé, avec un
            taux de variation inférieur à 1, donc en déflation, permet de forcer les détenteurs de jeton à les utiliser
            plutôt que de les stocker. Les jetons concernés vont donc perdre de la valeur par rapport aux nouveaux
            jetons ou par rapport à ceux qui circulent.</p>
        <p>Si activé, avec un taux de variation suppérieur à 1, donc en inflation, permet de favoriser la conservation
            des jetons et valorise les vieux jetons sur les nouveaux ou ceux qui circulent beaucoup.</p>
        <p>En ne forçant pas cette propriété dans l'objet des jetons, il est possible d'avoir un taux de variation
            fluctuant en fonction des besoins. En le positionnant forçé à <i>disabled</i> cela désactive définitivement
            ce mécanisme au niveau du sac de jetons et de tous les jetons en dépendant.</p>
        <p>Un jeton peut se déprécier avec le temps mais une entité peut demander à l’autorité émettrice de la monnaie
            un échange de jeton ancien contre un jeton plus jeune, si l’autorité émettrice le permet.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>CF <a href="#omcpidm">C/IDM</a>.</p>

        <h5 id="omgcpidr">OMGCPIDR / IDR</h5>
        <p>Taux de variation du mécanisme d’inflation/déflation (<i>rate</i>) des jetons dépendants du sac de jetons.
        </p>
        <p>Égal à 1 (un), taux constant donc pas de variation.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcpidp">OMGCPIDP / IDP</h5>
        <p>Périodicité d’application du taux de variation du mécanisme d’inflation/déflation (<i>period</i>) des jetons
            dépendants du sac de jetons.</p>
        <p>Unité exprimée en minutes.</p>
        <p>Si à 0 (zéro), la période n'est pas utilisé, donc la variation est non effective.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omgcptcn">OMGCPTCN / TCN</h5>
        <p>Non utilisé !!!</p>
        <p>Définit le nombre de jetons à créer dans le pool ou par pools créés (cf <a href="#omcppcn">PCN</a>).</p>
        <p>Ce n'est pas écrit dans l'objet du sac de jetons ni enregistré via des liens. Cela sert uniquement au moment
            de la création d'un sac de jetons. Cependant un lien de rattachement sera créé pour chaque jeton depuis le
            sac de jeton avec en meta le <a href="#omjcptid"></a>TID</a>.</p>

        <h3 id="omgs">OMGS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="omgt">OMGT/ Transfert</h3>
        <p>A faire...</p>

        <h3 id="omgr">OMGR / Réservation</h3>
        <p>Les objets réservés spécifiquement pour les sacs de jetons :</p>
        <ul>
            <li>nebule/objet/monnaie/sac</li>
        </ul>

        <h3 id="omgo">OMGO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>


        <h3 id="omj">OMJ / Jeton</h3>
        <p>A faire...</p>

        <h3 id="omjf">OMJF / Fonctionnement</h3>
        <p>Un jeton est un objet de référence qui va servir de support de transmission de valeur. Il est attaché à un ou
            plusieurs sacs de jetons. Sa gestion se fait dans des monnaies par l'intermédiaire de sacs de jetons
            attachés aux monnaies.</p>
        <p>Le jeton plus simple est un objet virtuel dont l’identifiant (<code>TID</code>) est généré aléatoirement. Ce
            peut être un simple compteur aussi mais chaque identifiant doit être unique par monnaie, et donc par sac de
            jetons aussi. L’utilisation d’un compteur de faible valeur est fortement déconseillé pour le
            <code>TID</code>. Par exemple :</p>
        <code>4d831b11bbf828b9cfd4752223bb8918cbd634c4b858691736afd8b34f1f0c62</code>
        <p>La deuxième forme de jeton est donc un objet dont le contenu va donner par son empreinte cryptographique un
            identifiant de jeton unique (<code>TID</code>). Il n’est dans ce cas pas possible d’avoir un compteur
            puisque les valeurs de identifiant sont assimilées à des valeurs aléatoires.</p>
        <p>Exemple d'objet de jeton :</p>
        <pre>
TYP:cryptoken
SID:5f3ad5265bb3306b3266e1935d067d9ec15965d0a970554bc6161eb3328907a9
CAP:TYP MOD SID CAP AID MID NAM UNI DTA DTC DTD COM CPR IDM IDR IDP VMD VID TRS CID PID FID BID VAL CLB CLD SVC TID
CID:daf832e3042cc849efcd5b6531df835a9c5f6251b2101e20972f9a9db2a8ae24
PID:37aa32a2cec224ae908226eb1c600fbeacd5faf1f84b2e292c0be808c0296333
FID:f0f7cf5c921320b97daedeb7c53f2417921c747c77b696f8a25ff29277661d2f
NAM:poux
UNI:pou
VAL:100
</pre>
        <p>Un jeton va disposer de plusieurs propriétés connues par leurs abréviations, voir <a href="#omjcp">OMJCP</a>.
        </p>

        <h3 id="omjn">OMJN / Nommage</h3>
        <p>Un jeton hérite du nommage de la monnaie via le sac de jeton auquel il est rattaché.</p>
        <p>Le nom complet d'un objet de type jeton est uniquement extrait de la propriété <code>NAM</code>. Dans
            certains cas il peut êrte formé de <code>NAM(UNI)</code> mais la propriété <code>UNI</code> a plutôt
            vocation a être utilisée dans un affichage condensé avec une valeur.</p>

        <h3 id="omjp">OMJP / Protection</h3>
        <p>A faire...</p>

        <h3 id="omjd">OMJD / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="omjl">OMJL / Liens</h3>
        <p>A faire...</p>

        <h3 id="omjv">OMJV / Valeur</h3>
        <p>C’est une valeur calculée strictement numérique du jeton à un instant donné par rapport à une monnaie. Elle
            s'appelle la valeur relative du jeton.</p>
        <p>Par défaut, si <code>VAL</code> non défini, la valeur de <code>VAL</code> est à interpréter comme équivalente
            à 1 (un).</p>
        <p>Ce n'est pas écrit dans l'objet du jeton ni enregistré via des liens. La valeur relative du jeton est
            recalculée à chaque usage du jeton en fonction des paramêtres <code>DTA</code>, <code>DTC</code>,
            <code>DTD</code>, <code>IDM</code>, <code>IDR</code>, <code>IDP</code>, <code>CLB</code> et <code>CLD</code>.
        </p>
        <p>Si le jeton est désactivé, sa valeur relative est nulle.</p>
        <p>A faire... le détail des calculs de la valeur relative en fonction de chaque propriétés citées.</p>

        <h3 id="omjc">OMJC / Création</h3>
        <p>Si l'objet de référence du jeton est virtuel, il est forçément généré aléatoirement. Sinon il dépend du
            contenu de l'objet et est rendu unique grâce à la propriété <a href="#omjcpsid">SID</a>.</p>
        <p>Un jeton va disposer de plusieurs propriétés connues par leurs abréviations.</p>
        <p>Le contenu des objets des jetons va recevoir plusieurs lignes de type <code>clé:valeur</code>. Chaque ligne
            débute par trois lettre en majuscules définissant le sens sémantique (<i>clé</i>) de la ligne, suivi d’un
            deux-points ( <code>:</code> ) et de la valeur associée. La valeur est un texte en minuscule sans caractères
            spéciaux, l’espace des caractères est limité aux lettres minuscules, aux chiffres, à l’espace (sauf au début
            et à la fin), au point, à la virgule et à l’égal. La valeur est par défaut limité en taille à 1024
            caractères sauf mention contraire pour une propriété. Il ne doit pas y avoir d’espace sur une ligne, ni en
            début et fin de ligne, ni autour du deux-points. Chaque ligne est terminée par un retour chariot type UNIX.
        </p>
        <p>Chaque propriété d’un jeton que l’on retrouve sous forme <code>clé:valeur</code> va être doublé d’un lien.
            Cependant les liens pouvant être annulés, les propriétés à figer sont écrites dans le jeton. Ainsi, une
            <code>clé:valeur</code> inscrite dans le jeton est prioritaire sur un lien équivalent.</p>
        <p>Dans l'objet du jeton, les clés <a href="#omjcptyp">TYP</a> et <a href="#omjcpsid">SID</a> sont obligatoires,
            toujours au début et dans cet ordre.</p>
        <p>Le début de contenu avec <code>TYP:cryptoken</code> permet de marquer un type de contenu facile à vérifier.
        </p>
        <p>La seconde ligne avec le <a href="#omjcpsid">SID</a> permet d’avoir un contenu unique et donc une empreinte
            unique pour chaque jeton.</p>

        <h4 id="omjcl">OMJCL / Liens</h4>
        <p>Liste des liens à générer lors de la création d'un jeton.</p>
        <p>A faire...</p>

        <h4 id="omjcp">OMJCP / Propriétés</h4>
        <p></p>

        <h5 id="omjcph">OMJCPH / Héritage</h5>
        <p>Certaines propriétés sont héritées de la monnaie, si ces propriétés sont définies dans la monnaie. Ce doit
            être la monnaie déclarée en cours d'utilisation et le jeton doit dépendre de cette monnaie via un sac de
            jetons. De la même façon certaines propriétés sont héritées en second lieu du sac de jeton. Les héritages
            sont prioritaires sur les propriétés définies via l'objet et les liens.</p>

        <h5 id="omjcphct">OMJCPHCT / HCT</h5>
        <p>Définit si l'objet du jeton a un contenu. Si il n'a pas de contenu l'objet du jeton est virtuel et correspond
            à son SID, et les paramètres du jeton ne peuvent pas être forcés.</p>
        <p>Ce n'est pas écrit dans l'objet du jeton ni enregistré via des liens. Cela sert uniquement au moment de la
            création d'un jeton.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcptyp">OMJCPTYP / TYP</h5>
        <p>Le type de jeton.</p>
        <p>Toujours à la valeur <i>cryptoken</i>.</p>
        <p>Présence obligatoire en ligne 1 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpsid">OMJCPSID / SID</h5>
        <p>Le numéro de série identifiant le jeton (<i>serial</i>).</p>
        <p>Si l'objet de référence du jeton est virtuel, l'identifiant du jeton <code>TID</code> sera le
            <code>SID</code>.</p>
        <p>Si l'objet de référence du jeton n'est pas virtuel, l'identifiant du jeton <code>TID</code> dépend du contenu
            de l'objet et est rendu unique grâce à la propriété <code>SID</code>.</p>
        <p>La valeur est de préférence aléatoire mais peut être un compteur à condition d'être unique. L’utilisation
            d’un compteur de faible valeur est fortement déconseillée.</p>
        <p>Si aléatoire, la génération pseudo aléatoire du <code>SID</code> est faite en partant d’un dérivé de la date
            avec quelques valeurs locales. Il n’y a pas de contrainte de sécurité sur cette valeur. Puis une boucle
            interne génère un bon aléa au fur et à mesure de la génération des jetons via une fonction de hash. Le tout
            ne consomme pas du tout de précieux aléa de bonne qualité.</p>
        <p>Présence obligatoire en ligne 2 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpcap">OMJCPCAP / CAP</h5>
        <p>Liste des capacités connues du jeton.</p>
        <p>Si une capacité n'est pas présente elle ne peut être invoquée, même si elle est forcée.</p>
        <p>Le contenu de cette propriété est hérité de la monnaie.</p>
        <p>Présence obligatoire en ligne 3 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpcid">OMJCPCID / CID</h5>
        <p>Identifiant de l’objet de la monnaie (<i>currency</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence obligatoire en ligne 4 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcppid">OMJCPPID / PID</h5>
        <p>Identifiant de l’objet du sac de jetons (<i>pool</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 2 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Présence obligatoire en ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcptid">OMJCPTID / TID</h5>
        <p>Identifiant du jeton.</p>
        <p>Sa valeur est égale à l'identifiant de l'objet.</p>
        <p>L'objet d'un jeton ne peut en aucun cas contenir son propre identifiant <code>TID</code>.</p>

        <h5 id="omjcpfid">OMJCPFID / FID</h5>
        <p>Identifiant de l'entité ayant forgé le jeton (<i>forge</i>).</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpbid">OMJCPBID / BID</h5>
        <p>Identifiant du bloc de forge (<i>blockchain</i>).</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpnam">OMJCPNAM / NAM</h5>
        <p>Le nom de la monnaie. Limité à 256 caractères.</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpuni">OMJCPUNI / UNI</h5>
        <p>Le nom de l'unité de la monnaie en 3 lettres maximum. Pas de chiffre.</p>
        <p>Une monnaie peut tout à fait réutiliser un sac de jetons et des propriétés d’une autre monnaie sans qu’il y
            ai conflit dans la gestion des jetons et de leurs transactions. Les valeurs associées peuvent être copiées
            sans que cela ne pose de problème.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpval">OMJCPVAL / VAL</h5>
        <p>Indication de valeur numérique initiale du jeton dans l’unité de la monnaie qui utilise le jeton.</p>
        <p>C’est une valeur strictement numérique.</p>
        <p>Par défaut, si non présent, la valeur est à interpréter comme équivalente à 1 (un).</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpdta">OMJCPDTA / DTA</h5>
        <p>Identifiant de l’entité autorité de temps pour les limites de temps.</p>
        <p>La gestion du temps avec une autorité de temps permet de prendre en compte sérieusement les suppression
            programmées de jeton (<code>DTC</code>/<code>DTD</code>) ainsi que leur inflation/déflation automatique
            (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>).</p>
        <p>L’autorité de temps peut être spécifique pour chaque jeton mais il est plus logique qu’elle soit commune à
            une monnaie ou dans certains cas à un sac de jetons.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpdtc">OMJCPDTC / DTC</h5>
        <p>Date de création du jeton.</p>
        <p>Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpdtd">OMJCPDTD / DTD</h5>
        <p>Date de suppression programmée du jeton.</p>
        <p> Forme texte libre limitée à 128 caractères. Doit pourvoir être interprété comme une date pour être
            fonctionel.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpcom">OMJCPCOM / COM</h5>
        <p>Commentaire texte libre limité à 4096 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpcpr">OMJCPCPR / CPR</h5>
        <p>Licence du jeton sous forme d’une texte libre limité à 1024 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>Cette propriété ne peut pas être hérité de la monnaie ou du sac de jetons.</p>

        <h5 id="omjcpidm">OMJCPIDM / IDM</h5>
        <p>Mode de fonctionnement du mécanisme d’inflation/déflation (<i>mode</i>) du jeton.</p>
        <p>Les modes sont <i>creation</i> ou <i>transaction</i> ou <i>disabled</i>.</p>
        <p>Suivant le mode, le mécanisme tient compte du temps passé depuis la dernière transaction ou depuis l’émission
            du jeton.</p>
        <p>Le mécanisme d’inflation/déflation (<code>IDM</code>/<code>IDR</code>/<code>IDP</code>), si activé, avec un
            taux de variation inférieur à 1, donc en déflation, permet de forcer les détenteurs de jeton à les utiliser
            plutôt que de les stocker. Les jetons concernés vont donc perdre de la valeur par rapport aux nouveaux
            jetons ou par rapport à ceux qui circulent.</p>
        <p>Si activé, avec un taux de variation suppérieur à 1, donc en inflation, permet de favoriser la conservation
            des jetons et valorise les vieux jetons sur les nouveaux ou ceux qui circulent beaucoup.</p>
        <p>En ne forçant pas cette propriété dans l'objet des jetons, il est possible d'avoir un taux de variation
            fluctuant en fonction des besoins. En le positionnant forçé à <i>disabled</i> cela désactive définitivement
            ce mécanisme au niveau du jeton.</p>
        <p>Un jeton peut se déprécier avec le temps mais une entité peut demander à l’autorité émettrice de la monnaie
            un échange de jeton ancien contre un jeton plus jeune, si l’autorité émettrice le permet.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>
        <p>CF <a href="#omcpidm">C/IDM</a>.</p>

        <h5 id="omjcpidr">OMJCPIDR / IDR</h5>
        <p>Taux de variation du mécanisme d’inflation/déflation (<i>rate</i>) du jeton.</p>
        <p>Égal à 1 (un), taux constant donc pas de variation.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpidp">OMJCPIDP / IDP</h5>
        <p>Périodicité d’application du taux de variation du mécanisme d’inflation/déflation (<i>period</i>) du jeton.
        </p>
        <p>Unité exprimée en minutes.</p>
        <p>Si à 0 (zéro), la période n'est pas utilisé, donc la variation est non effective.</p>
        <p>Doit être activé par <code>IDM</code>.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpsvc">OMJCPSVC / SVC</h5>
        <p>Le jeton fait référence à un type de service rendu (<i>service</i>).</p>
        <p>Taille limitée à 1024 caractères.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpclb">OMJCPCLB / CLB</h5>
        <p>Le jeton peut être désactivé (<i>cancelable</i>).</p>
        <p>Par défaut un jeton n’est pas désactivable. Si cette option est présente, quelque soit son contenu, cela
            active la capacité de désactivation à la demande du jeton par la propriété <code>CLD</code>.</p>
        <p>Elle n’a pas d’action sur la propriété de désactivation programmée <code>DTD</code>. Un jeton peut avoir une
            date de suppression programmée et être non désactivable avant la date de suppression. Activer la propriété
            <code>CLD</code> de façon forcée dans le contenu du jeton est faisable mais n’a pas de sens. Des jetons
            peuvent être générés désactivés et activés à posteriori.</p>
        <p>Un jeton désactivé ne peut pas faire parti d’une transaction.</p>
        <p>La taille est de un caractère maximum.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcpcld">OMJCPCLD / CLD</h5>
        <p>Le jeton est désactivé (<i>canceled</i>).</p>
        <p>Cette propriété n’est d’utilisée que si <code>CLB</code> est activé.</p>
        <p>Si cette option est présente, quelque soit son contenu, cela désactive le jeton.</p>
        <p>Un jeton désactivé ne peut pas faire parti d’une transaction.</p>
        <p>La taille est de un caractère maximum.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h5 id="omjcptrs">OMJCPTRS / TRS</h5>
        <p>Liste des méthodes de transaction supportées.</p>
        <p>Les modes sont définis dans le chapitre <a href="omjm">modes de transfert</a>. Les modes supportés sont
            écrits les uns après les autres sur une seule ligne et séparés par un caractère espace.</p>
        <p>Le contenu de cette propriété est hérité de la monnaie.</p>
        <p>Présence facultative sans ordre après la ligne 5 dans l'objet si l'objet n'est pas virtuel.</p>

        <h3 id="omjs">OMJS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="omjt">OMJT/ Transfert</h3>
        <p>Le jeton peut faire l'objet de deux types de transferts. Il y a la méthode de transfert de l'information
            (liens et objets) et le marquage du transfert de valeur.</p>
        <h4 id="omjti">OMJTI/ Transfert d'information</h4>
        <p>A faire...</p>

        <h4 id="omjtv">OMJTV/ Transfert de valeur</h4>
        <p>Le transfert de valeur est appelé transaction.</p>
        <p>La transaction unitaire marque l'attribution univoque et unidirectionnel d'une valeur appartenant à une
            entité vers une autre entité. Cette transaction unitaire peut être faite en contrepartie d'une autre valeur
            physique ou virtuelle, mais cette autre valeur n'est pas directement représentée dans la transaction
            unitaire traitée.</p>
        <p>Ici nous appelerons transaction soit une transaction unitaire, soit un block de plusieurs transactions
            unitaires. Les valeurs attribuées dans un block de transactions sont soit des jetons complets, soit des
            parties de jetons. Cependant une transaction unitaire ne peut traiter qu'un unique jeton ou qu'une unique
            partie d'un unique jeton. Dans un block de transactions, toutes les transactions unitaires doivent traiter
            de la même monnaie.</p>
        <p>Une contrepartie d'une autre valeur physique ou virtuelle peut au besoin être inscrite dans un jeton comme
            description. Ce mécanisme nécessite un suivi particulier qui n'est pas pris en charge ici.</p>
        <p>Dans le code, la transaction est traité comme un lien. Si le lien est suffisant, dans le mode LNS (cf <a
                href="omjcptrs">TRS</a>), alors l'attribution du jeton peut être faite sur le lien uniquement. Si le
            lien fait référence à un objet tier, celui est considéré comme un block de transaction et est lu afin d'en
            extraire toutes les transactions unitaires.</p>

        <h4 id="omjm">OMJM/ Modes de transfert</h4>

        <h5 id="omjmlns">OMJMLNS/ Mode LNS</h5>
        <p>Le code <code>LNS</code> désigne la méthode de base avec un lien (L) matérialisant une transaction et
            imposant un jeton non sécable (NS).</p>
        <p>A faire...</p>

        <h3 id="omjr">OMJR / Réservation</h3>
        <p>Les objets réservés spécifiquement pour les jetons :</p>
        <ul>
            <li>nebule/objet/monnaie/jeton</li>
        </ul>

        <h3 id="omjo">OMJO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}
