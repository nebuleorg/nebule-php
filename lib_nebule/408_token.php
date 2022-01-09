<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\TokenPool;
use Nebule\Library\Node;

/**
 * ------------------------------------------------------------------------------------------
 * La classe Token.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'un jeton ou 'new' ;
 * - un tableau des paramètres du nouveau jeton.
 *
 * L'ID d'un jeton est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture de le jeton ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class Token extends TokenPool
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
        '_properties',
        '_propertiesInherited',
        '_propertiesForced',
        '_seed',
        '_inheritedCID',
        '_inheritedPID',
    );

    /**
     * Constructeur.
     * Toujours transmettre l'instance de la librairie nebule.
     * Si le jeton existe, juste préciser l'ID de celui-ci.
     * Si c'est un nouveau jeton à créer, mettre l'ID à 'new'.
     *
     * @param nebule  $nebuleInstance
     * @param string  $id
     * @param array   $param      si $id == 'new'
     * @param boolean $protected  si $id == 'new'
     * @param boolean $obfuscated si $id == 'new'
     */
    public function __construct(nebule $nebuleInstance, string $id, array $param = array(), bool $protected = false, bool $obfuscated = false)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_social = $nebuleInstance->getSocialInstance();

        // Complément des paramètres.
        //$this->_propertiesList['currency']['CurrencyForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
//		$this->_propertiesList['tokenpool']['PoolForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
//		$this->_propertiesList['token']['TokenCurrencyID']['force'] = $this->_nebuleInstance->getCurrentCurrency();
//		$this->_propertiesList['token']['TokenForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance token ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
        ) {
            // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
            $this->_loadToken($id);
        } elseif (is_string($id)
            && $id == 'new'
        ) {
            // Si c'est un nouveau jeton à créer, renvoie à la création.
            $this->_createNewToken($param, $protected, $obfuscated);
        } else {
            // Sinon, le jeton est invalide, retourne 0.
            $this->_id = '0';
        }
    }

    /**
     * Fonction de suppression de l'instance.
     *
     * @return boolean
     */
    public function __destruct()
    {
        return true;
    }

    /**
     *  Chargement d'un jeton existant.
     *
     * @param string $id
     */
    private function _loadToken($id)
    {
        // Vérifie que c'est bien un objet.
        if (!is_string($id)
            || $id == ''
            || !ctype_xdigit($id)
            || !$this->_io->checkLinkPresent($id)
            || !$this->_configuration->getOptionAsBoolean('permitCurrency')
        ) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrology->addLog('Load token ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log

        // On ne recherche pas les paramètres si ce n'est pas un jeton.
        if ($this->getIsToken('myself')) {
            $this->setAID();
            $this->setFID('0');
        }

        // Vérifie le jeton.
        $TYP = $this->_getParamFromObject('TYP', (int)$this->_propertiesList['token']['TokenType']['limit']);
        $SID = $this->_getParamFromObject('SID', (int)$this->_propertiesList['token']['TokenSerialID']['limit']);
        $CID = $this->_getParamFromObject('CID', (int)$this->_propertiesList['token']['TokenCurrencyID']['limit']);
        $PID = $this->_getParamFromObject('PID', (int)$this->_propertiesList['token']['TokenPoolID']['limit']);
        if ($TYP == ''
            || $SID == ''
            || $CID == ''
            || $PID == ''
        ) {
            $this->_id = '0';
        } else {
            $this->_propertiesList['token']['TokenID']['force'] = $id;
            $this->_properties['TID'] = $id;
        }
        $this->_propertiesForced['TYP'] = true;
        $this->_propertiesForced['SID'] = true;
        $this->_propertiesForced['CID'] = true;
        $this->_propertiesForced['PID'] = true;
        $this->_propertiesForced['TID'] = true;
    }

    /**
     * Création d'une nouveau jeton.
     *
     * @param array $param
     * @return boolean
     */
    private function _createNewToken($param, $protected = false, $obfuscated = false)
    {
        $this->_metrology->addLog('Ask create token', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'on puisse créer un jeton.
        if ($this->_configuration->getOptionAsBoolean('permitWrite')
            && $this->_configuration->getOptionAsBoolean('permitWriteObject')
            && $this->_configuration->getOptionAsBoolean('permitWriteLink')
            && $this->_configuration->getOptionAsBoolean('permitCurrency')
            && $this->_configuration->getOptionAsBoolean('permitWriteCurrency')
            && $this->_configuration->getOptionAsBoolean('permitCreateCurrency')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génère la nouveau jeton.
            $this->_id = $this->_createToken($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrology->addLog('Create token error on generation', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create token error not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }


    /**
     * Crée un jeton.
     *
     * Les paramètres force* ne sont utilisés que si tokenHaveContent est à true.
     * Pour l'instant le code commence à prendre en compte tokenHaveContent à false mais le paramètre est forçé à true tant que le code n'est pas prêt.
     *
     * Retourne la chaine avec 0 si erreur.
     *
     * @param array $param
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return string
     */
    private function _createToken($param, $protected = false, $obfuscated = false)
    {
        // Identifiant final du sac de jetons.
        $this->_id = '0';

        // Normalise les paramètres.
        $this->_normalizeInputParam($param);

        // Force l'écriture de l'objet du sac de jetons.
        $param['TokenHaveContent'] = true;

        // Force l'écriture du serial.
        $param['ForceTokenSerialID'] = true;

        // Détermine si le sac de jetons a un numéro de série fourni.
        $sid = '';
        if (isset($param['TokenSerialID'])
            && is_string($param['TokenSerialID'])
            && $param['TokenSerialID'] != ''
            && ctype_xdigit($param['TokenSerialID'])
        ) {
            $sid = $this->_stringFilter($param['TokenSerialID']);
            $this->_metrology->addLog('Generate token asked SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        } else {
            // Génération d'un identifiant de sac de jetons unique aléatoire.
            $sid = $this->_getPseudoRandom();
            $param['TokenSerialID'] = $sid;
            $this->_metrology->addLog('Generate token rand SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        }

        // Détermine la monnaie associée.
        $instanceCurrency = $this->_nebuleInstance->getCurrentCurrencyInstance();
        if ($instanceCurrency->getID() != '0') {
            $this->_propertiesList['token']['TokenCurrencyID']['force'] = $instanceCurrency->getID();
            $param['TokenCurrencyID'] = $instanceCurrency->getID();
        } else {
            $this->_metrology->addLog('Generate token SID:' . $sid . ' - error no valid CID selected', Metrology::LOG_LEVEL_ERROR); // Log
            return '0';
        }

        // Détermine le sac de jetons associé.
        $instancePool = $this->_nebuleInstance->getCurrentTokenPoolInstance();
        if ($instancePool->getID() != '0') {
            $this->_propertiesList['token']['TokenPoolID']['force'] = $instancePool->getID();
            $param['TokenPoolID'] = $instancePool->getID();
        } else {
            $this->_metrology->addLog('Generate token SID:' . $sid . ' - error no valid PID selected', Metrology::LOG_LEVEL_ERROR); // Log
            return '0';
        }

        // Détermine le forgeur.
        $this->_propertiesList['token']['TokenForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
        $param['TokenForgeID'] = $this->_nebuleInstance->getCurrentEntity();


        // Détermine si le jeton doit avoir un contenu.
        if (isset($param['TokenHaveContent'])
            && $param['TokenHaveContent'] === true
        ) {
            $this->_metrology->addLog('Generate token SID:' . $sid . ' HCT:true', Metrology::LOG_LEVEL_DEBUG); // Log

            // Le contenu final commence par l'identifiant interne du sac de jetons.
            $content = 'TYP:' . $this->_propertiesList['token']['TokenType']['force'] . "\n"; // @todo peut être intégré au reste.
            $this->_metrology->addLog('Generate token SID:' . $sid . ' TYP:' . $this->_propertiesList['token']['TokenType']['force'], Metrology::LOG_LEVEL_DEBUG); // Log
            $content .= 'SID:' . $sid . "\n";
            $content .= 'CID:' . $param['TokenCurrencyID'] . "\n";
            $this->_metrology->addLog('Generate token SID:' . $sid . ' CID:' . $param['TokenCurrencyID'], Metrology::LOG_LEVEL_NORMAL); // Log
            $content .= 'PID:' . $param['TokenPoolID'] . "\n";
            $this->_metrology->addLog('Generate token SID:' . $sid . ' PID:' . $param['TokenPoolID'], Metrology::LOG_LEVEL_NORMAL); // Log

            // Pour chaque propriété, si présente et forcée, l'écrit dans l'objet.
            foreach ($this->_propertiesList['token'] as $name => $property) {
                if ($property['key'] != 'HCT'
                    && $property['key'] != 'TYP'
                    && $property['key'] != 'SID'
                    && $property['key'] != 'CID'
                    && $property['key'] != 'PID'
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
                    $this->_metrology->addLog('Generate token SID:' . $sid . ' force ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
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
            $this->_metrology->addLog('Generate token SID:' . $sid . ' HCT:false', Metrology::LOG_LEVEL_DEBUG); // Log
        }

        // Le sac de jetons a maintenant un PID.
        $this->_metrology->addLog('Generate token SID:' . $sid . ' TID:' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log


        // Prépare la génération des liens.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $source = $this->_id;
        $argObf = $obfuscated;

        // Le lien de type.
        $action = 'l';
        $target = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_JETON);
        $meta = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $this->_createLink($signer, $date, $action, $source, $target, $meta, false);

        // Crée les liens associés au sac de jetons.
        $action = 'l';

        // Pour chaque propriété, si présente et a un méta, écrit le lien.
        foreach ($this->_propertiesList['token'] as $name => $property) {
            if (isset($param[$name])
                && $param[$name] != null
                && $property['key'] != 'PCN'
                && $property['key'] != 'TCN'
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
                    $this->_metrology->addLog('Generate token SID:' . $sid . ' add ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash($property['key']);
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $argObf);
                    $this->_metrology->addLog('Generate token SID:' . $sid . ' link=' . $target . '_' . $meta, Metrology::LOG_LEVEL_DEBUG); // Log
                }
            }
        }


        // Retourne l'identifiant du sac de jetons.
        $this->_metrology->addLog('Generate token end SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        return $this->_id;
    }


    /**
     * Extrait la valeur relative du jeton à un instant donné.
     *
     * @param $date string
     * @return double
     */
    public function getRelativeValue($date = '')
    {
        // Prépare la date de traitement.
        if (is_string($date)
            && $date != ''
        ) {
            $dateRef = strtotime($date);
        } else {
            $dateRef = microtime(true);
        }

        // Extraction de la valeur d'origine du jeton.
        $result = (double)$this->getParam('VAL');

        // Vérifie si désactivable et désactivé.
        $CLB = $this->getParam('CLB');
        $CLD = $this->getParam('CLD');
        if ($CLB
            && $CLD
        ) {
            $result = 0;
        }

        // Vérifie si dans l'interval de temps prévu.
        $DTA = $this->getParam('DTA');
        $DTC = $this->getParam('DTC');
        $DTD = $this->getParam('DTD');
        if ($DTA !== null) {
            // @todo
        }

        // Si paramétré, calcul l'inflation (ou déflation) de la valeur.
        $IDM = $this->getParam('IDM');
        $IDR = $this->getParam('IDR');
        $IDP = $this->getParam('IDP');
        if ($IDM != 'disabled') {
            // @todo
        }

        return $result;
    }
}
