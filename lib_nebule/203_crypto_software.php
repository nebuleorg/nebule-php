<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class CryptoSoftware implements CryptoInterface
{
    /**
     * Instance de la bibliothèque nebule.
     *
     * @var nebule
     */
    private $_nebuleInstance;

    /**
     * Instance métrologie en cours.
     *
     * @var Metrology
     */
    protected $_metrology;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    private $_configuration;

    /**
     * Instance de gestion du cache.
     *
     * @var Cache
     */
    protected $_cache;

    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
     * @return string
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_PSEUDO): string
    {
        if ($quality == Crypto::RANDOM_PSEUDO)
            return $this->_getPseudoRandom($size);
        else
            return '';
    }

    /**
     * Génère de l'aléa avec une source pas forcément fiable.
     * La taille est en octets.
     *
     * La graine de génération pseudo-aléatoire est un mélange de la date,
     *   de l'heure avec une précision de la micro-seconde,
     *   du nom et de la version de la bibliothèque.
     *
     * Ne doit pas être utilisé pour générer des mots de passes !
     *
     * @param int $size
     * @return string
     */
    private function _getPseudoRandom(int $size = 32): string
    {
        global $nebuleSurname, $nebuleLibVersion;

        if ($size == 0
            || !is_int($size)
        )
            return '';

        // Résultat à remplir.
        $result = '';

        // Définit l'algorithme de divergence.
        $algo = 'sha256';

        // Génère une graine avec la date pour le compteur interne.
        $intcount = date(DATE_ATOM) . microtime(false) . $nebuleSurname . $nebuleLibVersion . $this->_nebuleInstance->getInstanceEntity();

        // Boucle de remplissage.
        while (strlen($result) < $size) {
            $diffsize = $size - strlen($result);

            // Fait évoluer le compteur interne.
            $intcount = hash($algo, $intcount);

            // Fait diverger le compteur interne pour la sortie.
            // La concaténation avec un texte empêche de remonter à la valeur du compteur interne.
            $outvalue = pack("H*", hash($algo, $intcount . 'liberté égalité fraternité'));

            // Tronc au besoin la taille de la sortie.
            if (strlen($outvalue) > $diffsize) {
                $outvalue = substr($outvalue, 0, $diffsize);
            }

            // Ajoute la sortie au résultat final.
            $result .= $outvalue;
        }

        // Nettoyage.
        unset($intcount, $outvalue, $diffsize);

        $this->_nebuleInstance->getMetrologyInstance()->addLog('Calculated rand : ' . strlen($result) . '/' . $size, Metrology::LOG_LEVEL_DEBUG);
        return $result;
    }

    /**
     * Get a value of the data entropy.
     *
     * @param string $data
     * @return float
     */
    public function getEntropy(string &$data): float
    {
        $h = 0;
        $s = strlen($data);
        if ($s == 0)
            return 0;
        foreach (count_chars($data, 1) as $v) {
            $p = $v / $s;
            $h -= $p * log($p) / log(2);
        }
        return $h;
    }

    public function hashAlgorithm()
    {
        // TODO: Implement hashAlgorithm() method.
    }

    public function hashAlgorithmName()
    {
        // TODO: Implement hashAlgorithmName() method.
    }

    public function hashLength()
    {
        // TODO: Implement hashLength() method.
    }

    public function checkHashFunction()
    {
        // TODO: Implement checkHashFunction() method.
    }

    public function setHashAlgorithm(string $algo)
    {
        // TODO: Implement setHashAlgorithm() method.
    }

    public function checkHashAlgorithm(string $algo)
    {
        // TODO: Implement checkHashAlgorithm() method.
    }

    public function hash(string $data, string $algo = '')
    {
        // TODO: Implement hash() method.
    }

    public function symmetricAlgorithm()
    {
        // TODO: Implement symmetricAlgorithm() method.
    }

    public function symmetricAlgorithmName()
    {
        // TODO: Implement symmetricAlgorithmName() method.
    }

    public function symmetricAlgorithmMode()
    {
        // TODO: Implement symmetricAlgorithmMode() method.
    }

    public function symmetricKeyLength()
    {
        // TODO: Implement symmetricKeyLength() method.
    }

    public function checkSymmetricFunction()
    {
        // TODO: Implement checkSymmetricFunction() method.
    }

    public function setSymmetricAlgorithm(string $algo)
    {
        // TODO: Implement setSymmetricAlgorithm() method.
    }

    public function checkSymmetricAlgorithm(string $algo)
    {
        // TODO: Implement checkSymmetricAlgorithm() method.
    }

    public function crypt(string $data, string $hexKey, string $hexIV = '')
    {
        // TODO: Implement crypt() method.
    }

    public function decrypt(string $data, string $hexKey, string $hexIV = '')
    {
        // TODO: Implement decrypt() method.
    }

    public function asymmetricAlgorithm()
    {
        // TODO: Implement asymmetricAlgorithm() method.
    }

    public function asymmetricAlgorithmName()
    {
        // TODO: Implement asymmetricAlgorithmName() method.
    }

    public function asymmetricKeyLength()
    {
        // TODO: Implement asymmetricKeyLength() method.
    }

    public function checkAsymmetricFunction()
    {
        // TODO: Implement checkAsymmetricFunction() method.
    }

    public function setAsymmetricAlgorithm(string $algo)
    {
        // TODO: Implement setAsymmetricAlgorithm() method.
    }

    public function checkAsymmetricAlgorithm(string $algo)
    {
        // TODO: Implement checkAsymmetricAlgorithm() method.
    }

    public function sign(string $hash, string $eid, string $privatePassword): ?string
    {
        // TODO: Implement sign() method.
    }

    public function verify(string $hash, string $sign, string $eid): bool
    {
        // TODO: Implement verify() method.
    }

    public function cryptTo(string $data, string $eid)
    {
        // TODO: Implement cryptTo() method.
    }

    public function decryptTo(string $code, string $privateKey, string $privatePassword)
    {
        // TODO: Implement decryptTo() method.
    }

    public function newPkey()
    {
        // TODO: Implement newPkey() method.
    }

    public function getPkeyPublic($pkey)
    {
        // TODO: Implement getPkeyPublic() method.
    }

    public function getPkeyPrivate(string $pkey, string $password = '')
    {
        // TODO: Implement getPkeyPrivate() method.
    }
}