<?php
declare(strict_types=1);
namespace Nebule\Library;

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

    // --------------------------------------------------------------------------------

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

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::hash()
     */
    public function hash(string $data, string $algo = ''): string
    {
        return $this->_opensslInstance->hash($data, $algo);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encrypt()
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        return $this->_opensslInstance->encrypt($data, $hexKey, $hexIV);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decrypt()
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        return $this->_opensslInstance->decrypt($data, $hexKey, $hexIV);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::sign()
     */
    public function sign(string $data, string $privateKey, string $privatePassword): string
    {
        return $this->_opensslInstance->sign($data, $privateKey, $privatePassword);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::verify()
     */
    public function verify(string $data, string $sign, string $publicKey): bool
    {
        return $this->_opensslInstance->verify($data, $sign, $publicKey);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encryptTo()
     */
    public function encryptTo(string $data, string $publicKey): string
    {
        return $this->_opensslInstance->encryptTo($data, $publicKey);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decryptTo()
     */
    public function decryptTo(string $code, string $privateKey, string $password): string
    {
        return $this->_opensslInstance->decryptTo($code, $privateKey, $password);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::newAsymmetricKeys()
     */
    public function newAsymmetricKeys(string $password = ''): array
    {
        return $this->_opensslInstance->newAsymmetricKeys($password);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkPrivateKeyPassword()
     */
    public function checkPrivateKeyPassword(string $privateKey, string $password): bool
    {
        return $this->_opensslInstance->checkPrivateKeyPassword($privateKey, $password);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::changePrivateKeyPassword()
     */
    public function changePrivateKeyPassword(string $privateKey, string $oldPassword, string $newPassword): string
    {
        return $this->_opensslInstance->changePrivateKeyPassword($privateKey, $oldPassword, $newPassword);
    }
}