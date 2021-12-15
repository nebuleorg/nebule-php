<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * ------------------------------------------------------------------------------------------
 * La classe Transaction.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant le lien ;
 * - un texte contenant la version du lien.
 *
 * Le nombre de champs du lien doit être de 7 exactement.
 * Le champs signature peut être vide ou à 0 si c'est pour un nouveau lien à signer.
 * La signature doit être explicitement demandée par l'appelle de sign() après la création du lien.
 *
 * Un lien peut être valide si sa structure est correcte, et peut être signé si la signature est valide.
 *
 * Une transaction est un lien entre une monnaie, un jeton et un objet contenant la description de la transaction.
 * La description de la transaction peut en fait contenir plusieurs transactions élémentaires.
 * ------------------------------------------------------------------------------------------
 */
class Transaction extends Link
{
    /**
     * Booléen si le lien est une transaction ET qu'elle est valide.
     *
     * @var boolean
     */
    private bool $_isTransaction = false;

    /**
     * ID de l'objet contenant les transactions.
     * Reste à null en mode LNS.
     *
     * @var string
     */
    private string $_transactionsObjectID = '';

    /**
     * Mode de transaction, si valide dans la monnaie.
     *
     * @var string
     */
    private string $_transactionsMode = '';

    /**
     * Date des transactions.
     *
     * @var DateTime|null
     */
    private DateTime|null $_transactionsTimestamp = null;

    /**
     * Table des transactions unitaires.
     *
     * Les transactions unitaires sont incrémentées à partir de 0.
     * Chaque transaction unitaire est un sous-tableau :
     * - CID
     * - D : horodatage
     * - S : ID détenteur
     * - D : ID destinataire
     * - TID
     * - R : ratio du jeton utilisé, de 0 à 1, 1 = tout
     * - TRS : mode de transaction
     *
     * @var array
     */
    private array $_transactionsArray = array();

    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_fullLink',
        '_signe',
        '_signeValue',
        '_signeAlgo',
        '_signed',
        '_hashSigner',
        '_date',
        '_action',
        '_hashSource',
        '_hashTarget',
        '_hashMeta',
        '_obfuscated',
        '_verified',
        '_valid',
        '_validStructure',
        '_verifyNumError',
        '_verifyTextError',
        '_isTransaction',
    );

    /**
     * Initialisation post-constructeur.
     *
     * @return void
     */
    protected function _initialisation()
    {
        // Vérifications de base.
        if ($this->_action != 'f'
            || $this->_hashSource == '0'
            || $this->_hashTarget == '0'
            || $this->_hashMeta == '0'
        ) {
            return;
        }

        $this->_extractByMode();
    }

    /**
     * Tri par mode de transaction détecté.
     *
     * @return void
     */
    private function _extractByMode()
    {
        // Si transaction en mode mode LNS.
        $hashLNS = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_MONNAIE_TRANSACTION);
        if ($this->_hashMeta == $hashLNS) {
            $this->_extractModeLNS();
        } else {
            $this->_isTransaction = false;
        }
    }

    /**
     * Extrait une transaction en mode LNS.
     *
     * Une transaction en mode LNS est marquée par un lien :
     * - hashsign : signataire et entité source disposant du jeton.
     * - date : date de la prise en compte de la transaction.
     * - action : type f.
     * - hashsource : ID du jeton.
     * - hashtarget : ID du destinataire.
     * - hashmeta : hash de 'nebule/objet/monnaie/transaction', marque que c'est une transaction.
     * Elle est repérée par rapport au champs méta.
     * Il faut vérifier que la source est un jeton et en extraire la monnaie affairante.
     * Il faut vérifier que la destination est une entité ou un dérivé.
     *
     * @return void
     */
    private function _extractModeLNS()
    {
        $this->_transactionsMode = 'LNS';

        // Vérifie si l'objet source est un jeton.
        $instanceSource = $this->_nebuleInstance->newToken($this->_hashSource);
        $instanceTarget = $this->_nebuleInstance->newWallet($this->_hashTarget);
        if ($instanceSource->getID() != '0'
            && $instanceSource->getIsToken('all') // @todo modifier le filtre social.
            && $instanceSource->getID() != '0'
            && $instanceSource->getIsWallet('all') // @todo modifier le filtre social.
        ) {
            // Extrait la monnaie.
            $CID = $instanceSource->getParam('CID');
            $instanceCID = $this->_nebuleInstance->newCurrency($CID);
            $modesCID = ' ' . $instanceCID->getParam('TRS') . ' ';

            // Vérifie que le mode LNS est présent dans les modes supportés par la monnaie
            if ($modesCID != '  '
                && strpos($modesCID, ' LNS ') !== false
            ) {
                $this->_transactionsArray[0]['CID'] = $CID;
                $this->_transactionsArray[0]['D'] = $this->_date;
                $this->_transactionsArray[0]['S'] = $this->_hashSigner;
                $this->_transactionsArray[0]['T'] = $this->_hashTarget;
                $this->_transactionsArray[0]['TID'] = $this->_hashSource;
                $this->_transactionsArray[0]['R'] = '1';
                $this->_transactionsArray[0]['TRS'] = 'LNS';
                $this->_isTransaction = true;
            } else {
                $this->_isTransaction = false;
            }
        } else {
            $this->_isTransaction = false;
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
     * Retourne si le lien est bien une transaction.
     *
     * @return boolean
     */
    public function getIsTransaction()
    {
        return $this->_isTransaction;
    }

    /**
     * Retourne l'ID de l'objet des transactions.
     * Retourne null en mode LNS
     *
     * @return string
     */
    public function getTransactionsObjetID()
    {
        return $this->_transactionsObjectID;
    }

    /**
     * Retourne la marque de temps des transactions.
     * Retourne null en mode LNS
     *
     * @return string
     */
    public function getTransactionsTimestamp()
    {
        return $this->_transactionsTimestamp;
    }

    /**
     * Retourne la liste des transactions unitaires.
     * Retourne null en mode LNS
     *
     * @return string
     */
    public function getTransactionsArray()
    {
        return $this->_transactionsArray;
    }
}
