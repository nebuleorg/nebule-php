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
class Wallet extends Entity implements nodeInterface
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
    );

    protected function _initialisation(): void
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->getOptionAsBoolean('permitCurrency')) {
            $this->_id = '0';
            $this->_isNew = false;
            return;
        }
        if (is_a($this->_entitiesInstance, 'Nebule\Library\Node'))
            $this->_cacheCurrentEntityUnlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();

        if ($this->_isNew)
            $this->_createNewWallet();
        elseif ($this->_id != '0')
            $this->getIsWallet();
    }

    /**
     * Création d'un nouveau portefeuille.
     *
     * @param array $param
     * @param bool  $protected
     * @param bool  $obfuscated
     * @return void
     */
    private function _createNewWallet(array $param, bool $protected = false, bool $obfuscated = false): void
    {
        $this->_metrologyInstance->addLog('Ask create wallet', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        // Vérifie que l'on puisse créer un sac de jetons.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitCurrency')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteCurrency')
            && $this->_entitiesInstance->getConnectedEntityIsUnlocked()
        ) {
            // Génère la nouveau sac de jetons.
            $this->_id = $this->_createWallet($param, $protected, $obfuscated);

            // Si la génération s'est mal passée.
            if ($this->_id == '0') {
                $this->_metrologyInstance->addLog('Create wallet error on generation', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_id = '0';
                return;
            }
        } else {
            $this->_metrologyInstance->addLog('Create wallet error not autorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_id = '0';
            return;
        }
    }


    /**
     * Crée un portefeuille.
     * Retourne la chaine avec 0 si erreur.
     *
     * @param array   $param
     * @param boolean $protected
     * @param boolean $obfuscated
     * @return string
     */
    private function _createWallet(array $param, bool $protected = false, bool $obfuscated = false): string
    {
        // Identifiant final du portefeuille.
        $this->_id = '0';

        // @todo
        return '';
    }
}



abstract class HelpWallet {
    static public function echoDocumentationTitles(): void {
    }

    static public function echoDocumentationCore(): void {
    }
}
