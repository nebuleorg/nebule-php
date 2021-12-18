<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\Entity;

/**
 * ------------------------------------------------------------------------------------------
 * La classe Wallet.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'un jeton ou 'new' ;
 * - un tableau des paramètres du nouveau portefeuille.
 *
 * L'ID d'un portefeuille est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture du portefeuille ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class Wallet extends Entity
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
    );

    /**
     * Constructeur.
     * Toujours transmettre l'instance de la librairie nebule.
     * Si le portefeuille existe, juste préciser l'ID de celui-ci.
     * Si c'est un nouveau portefeuille à créer, mettre l'ID à 'new'.
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
        $this->_io = $nebuleInstance->getIO();
        $this->_crypto = $nebuleInstance->getCrypto();
        $this->_social = $nebuleInstance->getSocial();

        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance wallet ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        if (is_string($id)
            && $id != ''
            && ctype_xdigit($id)
        ) {
            // Si l'ID est cohérent et l'objet nebule présent, c'est bon.
            $this->_loadWallet($id);
        } elseif (is_string($id)
            && $id == 'new'
        ) {
            // Si c'est un nouveau portefeuille à créer, renvoie à la création.
            $this->_createNewWallet($param, $protected, $obfuscated);
        } else {
            // Sinon, le portefeuille est invalide, retourne 0.
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
     *  Chargement d'un portefeuille existant.
     *
     * @param string $id
     */
    private function _loadWallet($id)
    {
        // Vérifie que c'est bien un objet.
        if (!is_string($id)
            || $id == ''
            || !ctype_xdigit($id)
            || !$this->_io->checkLinkPresent($id)
            || !$this->_configuration->getOption('permitCurrency')
        ) {
            $id = '0';
        }

        $this->_id = $id;
        $this->_metrology->addLog('Load wallet ' . $id, Metrology::LOG_LEVEL_DEBUG); // Log
    }

    /**
     * Création d'une nouveau portefeuille.
     *
     * @param array $param
     * @return boolean
     */
    private function _createNewWallet($param, $protected = false, $obfuscated = false)
    {
        $this->_metrology->addLog('Ask create wallet', Metrology::LOG_LEVEL_DEBUG); // Log

        // Vérifie que l'on puisse créer un sac de jetons.
        if ($this->_configuration->getOption('permitWrite')
            && $this->_configuration->getOption('permitWriteObject')
            && $this->_configuration->getOption('permitWriteLink')
            && $this->_configuration->getOption('permitCurrency')
            && $this->_configuration->getOption('permitWriteCurrency')
            && $this->_nebuleInstance->getCurrentEntityUnlocked()
        ) {
            // Génère la nouveau sac de jetons.
            $this->_id = $this->_createWallet($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrology->addLog('Create wallet error on generation', Metrology::LOG_LEVEL_ERROR); // Log
                $this->_id = '0';
                return false;
            }
        } else {
            $this->_metrology->addLog('Create wallet error not autorized', Metrology::LOG_LEVEL_ERROR); // Log
            $this->_id = '0';
            return false;
        }
    }


    /**
     * Crée un portefeuille.
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
        // Identifiant final du portefeuille.
        $this->_id = '0';

        // @todo
    }
}
