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
class Crypto implements CryptoInterface
{
    const RANDOM_PSEUDO = 1;
    const RANDOM_STRONG = 2;

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

    private $_opensslInstance;
    private $_softwareInstance;

    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();
        $this->_opensslInstance = new CryptoOpenssl($nebuleInstance);
        $this->_softwareInstance = new CryptoSoftware($nebuleInstance);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
     * @return string
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_PSEUDO): string
    {
        if ($quality == Crypto::RANDOM_STRONG)
            return $this->_opensslInstance->getRandom($size);
        else
            return $this->_softwareInstance->getRandom($size);
    }

    /**
     * Get a value of the data entropy.
     *
     * @param string $data
     * @return float
     */
    public function getEntropy(string &$data): float
    {
        return $this->_softwareInstance->getEntropy($data);
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