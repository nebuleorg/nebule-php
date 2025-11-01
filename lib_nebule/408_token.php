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
class Token extends TokenPool implements nodeInterface
{
    const SESSION_SAVED_VARS = array(
        '_id',
        '_fullName',
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

    protected function _initialisation(): void
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_id = '0';
            $this->_isNew = false;
            return;
        }
        if (is_a($this->_entitiesInstance, 'Nebule\Library\Node'))
            $this->_cacheCurrentEntityUnlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();

        if ($this->_id != '0')
            $this->_loadToken($this->_id);
    }

    /**
     *  Chargement d'un jeton existant.
     *
     * @param string $id
     */
    private function _loadToken(string $id): void
    {
        // Vérifie que c'est bien un objet.
        if (!Node::checkNID($id)
            || !$this->_ioInstance->checkLinkPresent($id)
            || !$this->_configurationInstance->getOptionAsBoolean('permitCurrency')
        ) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrologyInstance->addLog('Load token ' . $id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

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
     * Création d'un nouveau jeton.
     *
     * @param array $param
     * @param bool  $protected
     * @param bool  $obfuscated
     * @return boolean
     */
    public function setNewToken(array $param, bool $protected = false, bool $obfuscated = false): bool
    {
        $this->_metrologyInstance->addLog('Ask create token', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        if (!$this->_isNew
            || sizeof($param) == 0
            || (get_class($this) != 'Token'
                && get_class($this) != 'Nebule\Library\Token'
            )
        )
            return false;

        // Vérifie que l'on puisse créer un jeton.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitCurrency')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteCurrency')
            && $this->_configurationInstance->getOptionAsBoolean('permitCreateCurrency')
            && $this->_entitiesInstance->getConnectedEntityIsUnlocked()
        ) {
            // Génère la nouveau jeton.
            $this->_id = $this->_createToken($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrologyInstance->addLog('Create token error on generation', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrologyInstance->addLog('Create token error not autorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_id = '0';
            return false;
        }
        return true;
    }


    /**
     * Crée un jeton.
     * Les paramètres force* ne sont utilisés que si tokenHaveContent est à true.
     * Pour l'instant le code commence à prendre en compte tokenHaveContent à false mais le paramètre est forçé à true tant que le code n'est pas prêt.
     * Retourne la chaine avec 0 si erreur.
     *
     * @param array   $param
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return string
     */
    private function _createToken(array $param, bool $protected = false, bool $obfuscated = false): string
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
            $this->_metrologyInstance->addLog('Generate token asked SID:' . $sid, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
        } else {
            // Génération d'un identifiant de sac de jetons unique aléatoire.
            $sid = $this->_nebuleInstance->getCryptoInstance()->getRandom(128, Crypto::RANDOM_PSEUDO);
            $param['TokenSerialID'] = $sid;
            $this->_metrologyInstance->addLog('Generate token rand SID:' . $sid, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
        }

        // Détermine la monnaie associée.
        $instanceCurrency = $this->_nebuleInstance->getCurrentCurrencyInstance();
        if ($instanceCurrency->getID() != '0') {
            $this->_propertiesList['token']['TokenCurrencyID']['force'] = $instanceCurrency->getID();
            $param['TokenCurrencyID'] = $instanceCurrency->getID();
        } else {
            $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' - error no valid CID selected', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            return '0';
        }

        // Détermine le sac de jetons associé.
        $instancePool = $this->_nebuleInstance->getCurrentTokenPoolInstance();
        if ($instancePool->getID() != '0') {
            $this->_propertiesList['token']['TokenPoolID']['force'] = $instancePool->getID();
            $param['TokenPoolID'] = $instancePool->getID();
        } else {
            $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' - error no valid PID selected', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            return '0';
        }

        // Détermine le forgeur.
        $this->_propertiesList['token']['TokenForgeID']['force'] = $this->_entitiesInstance->getGhostEntityEID();
        $param['TokenForgeID'] = $this->_entitiesInstance->getGhostEntityEID();


        // Détermine si le jeton doit avoir un contenu.
        if (isset($param['TokenHaveContent'])
            && $param['TokenHaveContent'] === true
        ) {
            $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' HCT:true', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

            // Le contenu final commence par l'identifiant interne du sac de jetons.
            $content = 'TYP:' . $this->_propertiesList['token']['TokenType']['force'] . "\n"; // @todo peut être intégré au reste.
            $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' TYP:' . $this->_propertiesList['token']['TokenType']['force'], Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
            $content .= 'SID:' . $sid . "\n";
            $content .= 'CID:' . $param['TokenCurrencyID'] . "\n";
            $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' CID:' . $param['TokenCurrencyID'], Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
            $content .= 'PID:' . $param['TokenPoolID'] . "\n";
            $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' PID:' . $param['TokenPoolID'], Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');

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
                    $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' force ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                }
            }

            // Crée l'objet avec le contenu et l'écrit.
            $this->_data = $content;
            $this->_haveData = true;
            unset($content);

            // calcul l'ID.
            $this->_id = $this->_cryptoInstance->hash($this->_data);

            // Si l'objet doit être protégé.
            if ($protected) {
                $this->setProtected($obfuscated);
            } else {
                // Sinon écrit l'objet directement.
                $this->write();
            }
        } else {
            $this->_id = $sid;
            $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' HCT:false', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
        }

        // Le sac de jetons a maintenant un PID.
        $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' TID:' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');


        // Prépare la génération des liens.
        $signer = $this->_entitiesInstance->getGhostEntityEID();
        $date = date(DATE_ATOM);
        $source = $this->_id;
        $argObf = $obfuscated;

        // Le lien de type.
        $action = 'l';
        $target = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_MONNAIE_JETON);
        $meta = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_TYPE);
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
                    $object->setType(References::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'number') {
                    $value = (string)$param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(References::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                } elseif ($property['type'] == 'hexadecimal') {
                    $value = $param[$name];
                    $target = $value;
                } else {
                    $value = $param[$name];
                    $object = new Node($this->_nebuleInstance, '0', $value, false, false);
                    $object->setType(References::REFERENCE_OBJECT_TEXT);
                    $target = $object->getID();
                }

                if ($value != null) {
                    $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' add ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                    $meta = $this->getNidFromData($property['key']);
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $argObf);
                    $this->_metrologyInstance->addLog('Generate token SID:' . $sid . ' link=' . $target . '_' . $meta, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                }
            }
        }


        // Retourne l'identifiant du sac de jetons.
        $this->_metrologyInstance->addLog('Generate token end SID:' . $sid, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
        return $this->_id;
    }


    /**
     * Extrait la valeur relative du jeton à un instant donné.
     *
     * @param $date string
     * @return float|int
     */
    public function getRelativeValue($date = ''): float|int
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



abstract class HelpToken {
    static public function echoDocumentationTitles(): void {
    }

    static public function echoDocumentationCore(): void {
    }
}
