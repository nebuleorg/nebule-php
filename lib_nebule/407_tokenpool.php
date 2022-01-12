<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\Currency;

/**
 * ------------------------------------------------------------------------------------------
 * La classe TokenPool.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'un sac de jetons ou 'new' ;
 * - un tableau des paramètres du nouveau sac de jetons.
 *
 * L'ID d'un sac de jetons est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture de le sac de jetons ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class TokenPool extends Currency
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
     * Si le sac de jetons existe, juste préciser l'ID de celui-ci.
     * Si c'est un nouveau sac de jetons à créer, mettre l'ID à 'new'.
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
//		$this->_propertiesList['tokenpool']['PoolCurrencyID']['force'] = $this->_nebuleInstance->getCurrentCurrency();
//		$this->_propertiesList['tokenpool']['PoolForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
//		$this->_propertiesList['token']['TokenForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance token pool ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
        ) {
            // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
            $this->_loadTokenPool($id);
        } elseif (is_string($id)
            && $id == 'new'
        ) {
            // Si c'est un nouveau sac de jetons à créer, renvoie à la création.
            $this->_createNewTokenPool($param, $protected, $obfuscated);
        } else {
            // Sinon, le sac de jetons est invalide, retourne 0.
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
     *  Chargement d'un sac de jetons existant.
     *
     * @param string $id
     */
    private function _loadTokenPool($id)
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
        $this->_metrology->addLog('Load token pool ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log

        // On ne recherche pas les paramètres si ce n'est pas un sac de jetons.
        if ($this->getIsTokenPool('myself')) {
            $this->setAID();
            $this->setFID('0');
        }

        // Vérifie le sac de jetons.
        $TYP = $this->_getParamFromObject('TYP', (int)$this->_propertiesList['tokenpool']['PoolType']['limit']);
        $SID = $this->_getParamFromObject('SID', (int)$this->_propertiesList['tokenpool']['PoolSerialID']['limit']);
        $CID = $this->_getParamFromObject('CID', (int)$this->_propertiesList['tokenpool']['PoolCurrencyID']['limit']);
        if ($TYP == ''
            || $SID == ''
            || $CID == ''
        ) {
            $this->_id = '0';
        } else {
            $this->_propertiesList['tokenpool']['PoolID']['force'] = $id;
            $this->_properties['PID'] = $id;
        }
        $this->_propertiesForced['TYP'] = true;
        $this->_propertiesForced['SID'] = true;
        $this->_propertiesForced['CID'] = true;
        $this->_propertiesForced['PID'] = true;
    }

    /**
     * Création d'une nouveau sac de jetons.
     *
     * @param array $param
     * @return boolean
     */
    private function _createNewTokenPool($param, $protected = false, $obfuscated = false)
    {
        $this->_metrology->addLog('Ask create token pool', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'on puisse créer un sac de jetons.
        if ($this->_configuration->getOptionAsBoolean('permitWrite')
            && $this->_configuration->getOptionAsBoolean('permitWriteObject')
            && $this->_configuration->getOptionAsBoolean('permitWriteLink')
            && $this->_configuration->getOptionAsBoolean('permitCurrency')
            && $this->_configuration->getOptionAsBoolean('permitWriteCurrency')
            && $this->_configuration->getOptionAsBoolean('permitCreateCurrency')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génère la nouveau sac de jetons.
            $this->_id = $this->_createTokenPool($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrology->addLog('Create token pool error on generation', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create token pool error not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }


    /**
     * Crée un sac de jetons.
     *
     * Les paramètres force* ne sont utilisés que si PoolHaveContent est à true.
     * Pour l'instant le code commence à prendre en compte PoolHaveContent à false mais le paramètre est forçé à true tant que le code n'est pas prêt.
     *
     * Les options pour la génération d'un sac de jetons :
     * poolHaveContent
     * poolSerialID
     * poolForgeID
     * poolCurrencyID
     * poolComment
     * poolCopyright
     * poolTokenCount
     *
     * forcePoolForgeID
     * forcePoolCurrencyID
     * forcePoolComment
     * forcePoolCopyright
     *
     * Retourne la chaine avec 0 si erreur.
     *
     * @param array $param
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return string
     */
    private function _createTokenPool($param, $protected = false, $obfuscated = false)
    {
        // Identifiant final du sac de jetons.
        $this->_id = '0';

        // Normalise les paramètres.
        $this->_normalizeInputParam($param);

        // Force l'écriture de l'objet du sac de jetons.
        $param['PoolHaveContent'] = true;

        // Force l'écriture du serial.
        $param['ForcePoolSerialID'] = true;

        // Détermine si le sac de jetons a un numéro de série fourni.
        $sid = '';
        if (isset($param['PoolSerialID'])
            && is_string($param['PoolSerialID'])
            && $param['PoolSerialID'] != ''
            && ctype_xdigit($param['PoolSerialID'])
        ) {
            $sid = $this->_stringFilter($param['PoolSerialID']);
            $this->_metrology->addLog('Generate token pool asked SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        } else {
            // Génération d'un identifiant de sac de jetons unique aléatoire.
            $sid = $this->_nebuleInstance->getCryptoInstance()->getRandom(128, Crypto::RANDOM_PSEUDO);
            $param['PoolSerialID'] = $sid;
            $this->_metrology->addLog('Generate token pool rand SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        }

        // Détermine la monnaie associée.
        $instanceCurrency = $this->_nebuleInstance->getCurrentCurrencyInstance();
        if ($instanceCurrency->getID() != '0') {
            $this->_propertiesList['tokenpool']['PoolCurrencyID']['force'] = $instanceCurrency->getID();
            $param['PoolCurrencyID'] = $instanceCurrency->getID();
        } else {
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' - error no valid CID selected', Metrology::LOG_LEVEL_ERROR); // Log
            return '0';
        }

        // Détermine le forgeur.
        $this->_propertiesList['tokenpool']['PoolForgeID']['force'] = $this->_nebuleInstance->getCurrentEntity();
        $param['PoolForgeID'] = $this->_nebuleInstance->getCurrentEntity();


        // Détermine si le jeton doit avoir un contenu.
        if (isset($param['PoolHaveContent'])
            && $param['PoolHaveContent'] === true
        ) {
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' HCT:true', Metrology::LOG_LEVEL_DEBUG); // Log

            // Le contenu final commence par l'identifiant interne du sac de jetons.
            $content = 'TYP:' . $this->_propertiesList['tokenpool']['PoolType']['force'] . "\n"; // @todo peut être intégré au reste.
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' TYP:' . $this->_propertiesList['tokenpool']['PoolType']['force'], Metrology::LOG_LEVEL_DEBUG); // Log
            $content .= 'SID:' . $sid . "\n";
            $content .= 'CID:' . $param['PoolCurrencyID'] . "\n";
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' CID:' . $param['PoolCurrencyID'], Metrology::LOG_LEVEL_NORMAL); // Log

            // Pour chaque propriété, si présente et forcée, l'écrit dans l'objet.
            foreach ($this->_propertiesList['tokenpool'] as $name => $property) {
                if ($property['key'] != 'HCT'
                    && $property['key'] != 'TYP'
                    && $property['key'] != 'SID'
                    && $property['key'] != 'CID'
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
                    $this->_metrology->addLog('Generate token pool SID:' . $sid . ' force ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
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
            $this->_metrology->addLog('Generate token pool SID:' . $sid . ' HCT:false', Metrology::LOG_LEVEL_DEBUG); // Log
        }

        // Le sac de jetons a maintenant un PID.
        $this->_metrology->addLog('Generate token pool SID:' . $sid . ' PID:' . $this->_id, Metrology::LOG_LEVEL_DEBUG); // Log


        // Prépare la génération des liens.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $source = $this->_id;
        $argObf = $obfuscated;

        // Le lien de type.
        $action = 'l';
        $target = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_SAC);
        $meta = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $this->_createLink($signer, $date, $action, $source, $target, $meta, false);

        // Crée les liens associés au sac de jetons.
        $action = 'l';

        // Pour chaque propriété, si présente et a un méta, écrit le lien.
        foreach ($this->_propertiesList['tokenpool'] as $name => $property) {
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
                    $this->_metrology->addLog('Generate token pool SID:' . $sid . ' add ' . $property['key'] . ':' . $value, Metrology::LOG_LEVEL_DEBUG); // Log
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash($property['key']);
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $argObf);
                    $this->_metrology->addLog('Generate token pool SID:' . $sid . ' link=' . $target . '_' . $meta, Metrology::LOG_LEVEL_DEBUG); // Log
                }
            }
        }


        // Retourne l'identifiant du sac de jetons.
        $this->_metrology->addLog('Generate token pool end SID:' . $sid, Metrology::LOG_LEVEL_NORMAL); // Log
        return $this->_id;
    }

    /**
     * Retourne la liste des jetons du sac.
     *
     * @return array:string
     */
    public function getTokenList()
    {
        return $this->_getItemList('PID');
    }

    /**
     * Retourne le nombre de jetons du sac.
     *
     * @return integer
     */
    public function getTokenCount()
    {
        return sizeof($this->_getItemList('PID'));
    }


    /**
     * Extrait la valeur relative du sac de jetons à un instant donné.
     *
     * @param $date string
     * @return double
     */
    public function getRelativeValue($date)
    {
        // Récupère la liste des jetons.
        $items = $this->_getItemList('PID');

        $total = 0;

        foreach ($items as $item) {
            $instance = $this->_nebuleInstance->newToken($item);
            if ($instance->getID() != '0') {
                $total += $instance->getRelativeValue($date);
            }
        }

        return $total;
    }
}
