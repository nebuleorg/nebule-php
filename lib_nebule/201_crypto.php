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
    const TYPE_HASH = 1;
    const TYPE_SYMMETRIC = 2;
    const TYPE_ASYMMETRIC = 3;

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

    /**
     * @var CryptoInterface
     */
    private $_defaultCryptoLibraryInstance;

    public function __construct(nebule $nebuleInstance)
    {
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();

        $this->_opensslInstance = new CryptoOpenssl($nebuleInstance);
        $this->_softwareInstance = new CryptoSoftware($nebuleInstance);

        if ($this->_configuration->getOptionAsString('cryptoLibrary') == 'software')
            $this->_defaultCryptoLibraryInstance = $this->_softwareInstance;
        else
            $this->_defaultCryptoLibraryInstance = $this->_opensslInstance;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkFunction()
     */
    public function checkFunction(string $algo, int $type): bool
    {
        return $this->_defaultCryptoLibraryInstance->checkFunction($algo, $type);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkValidAlgorithm()
     */
    public function checkValidAlgorithm(string $algo, int $type): bool
    {
        return $this->_defaultCryptoLibraryInstance->checkValidAlgorithm($algo, $type);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
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

    public function hash(string $data, string $algo = ''): string
    {
        return $this->_opensslInstance->hash($data, $algo);
    }

    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        return $this->_opensslInstance->encrypt($data, $hexKey, $hexIV);
    }

    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        return $this->_opensslInstance->decrypt($data, $hexKey, $hexIV);
    }

    public function sign(string $hash, string $eid, string $privatePassword): string
    {
        return $this->_opensslInstance->sign($hash, $eid, $privatePassword);
    }

    public function verify(string $hash, string $sign, string $eid): bool
    {
        return $this->_opensslInstance->verify($hash, $sign, $eid);
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