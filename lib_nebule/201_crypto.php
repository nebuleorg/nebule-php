<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Crypto extends Functions implements CryptoInterface
{
    public const SESSION_SAVED_VARS = array(
            '_defaultInstance',
            '_ready',
            '_listClasses',
            '_listInstances',
            '_listTypes',
    );

    public const DEFAULT_CLASS = 'openssl';

    public const RANDOM_PSEUDO = 1;
    public const RANDOM_STRONG = 2;
    public const TYPE_HASH = 1;
    public const TYPE_SYMMETRIC = 2;
    public const TYPE_ASYMMETRIC = 3;

    private ?CryptoInterface $_defaultInstance = null;

    public function __toString(): string
    {
        return self::TYPE;
    }

    protected function _initialisation(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        foreach (get_declared_classes() as $class) {
            if (str_starts_with($class, get_class($this)) && $class != get_class($this)) {
                $this->_metrologyInstance->addLog('add class ' . $class, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '53556863');
                $this->_initSubInstance($class);
            }
        }
        $this->_defaultInstance = $this->_getDefaultSubInstance('cryptoLibrary');
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getCryptoInstance()
     */
    public function getCryptoInstance(): CryptoInterface
    {
        return $this->_defaultInstance;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getCryptoInstanceName()
     */
    public function getCryptoInstanceName(): string
    {
        return get_class($this->_defaultInstance);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkFunction()
     */
    public function checkFunction(string $algo, int $type): bool
    {
        return $this->_defaultInstance->checkFunction($algo, $type);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkValidAlgorithm()
     */
    public function checkValidAlgorithm(string $algo, int $type): bool
    {
        return $this->_defaultInstance->checkValidAlgorithm($algo, $type);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getAlgorithmList()
     */
    public function getAlgorithmList(int $type): array
    {
        return $this->_defaultInstance->getAlgorithmList($type);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_PSEUDO): string
    {
        // FIXME refaire un sélecteur plus propre !
        if ($quality == Crypto::RANDOM_STRONG)
            return $this->_listInstances['openssl']->getRandom($size);
        else
            return $this->_listInstances['software']->getRandom($size);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getEntropy()
     */
    public function getEntropy(string &$data): float
    {
        return $this->_listInstances['software']->getEntropy($data);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::hash()
     */
    public function hash(string $data, string $algo = ''): string
    {
        if ($algo == '')
            $algo = \Nebule\Library\References::REFERENCE_CRYPTO_HASH_ALGORITHM;
        return $this->_defaultInstance->hash($data, $algo);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encrypt()
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        return $this->_defaultInstance->encrypt($data, $hexKey, $hexIV);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decrypt()
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        return $this->_defaultInstance->decrypt($data, $hexKey, $hexIV);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::sign()
     */
    public function sign(string $data, string $privateKey, string $privatePassword): string
    {
        return $this->_defaultInstance->sign($data, $privateKey, $privatePassword);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::verify()
     */
    public function verify(string $data, string $sign, string $publicKey, string $algo): bool
    {
        return $this->_defaultInstance->verify($data, $sign, $publicKey, $algo);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encryptTo()
     */
    public function encryptTo(string $data, ?string $publicKey): string
    {
        return $this->_defaultInstance->encryptTo($data, $publicKey);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decryptTo()
     */
    public function decryptTo(string $code, ?string $privateKey, ?string $password): string
    {
        return $this->_defaultInstance->decryptTo($code, $privateKey, $password);
    }

    /**
     * {@inheritDoc}
     * @param string $password
     * @param string $algo
     * @param int    $size
     * @see CryptoInterface::newAsymmetricKeys()
     */
    public function newAsymmetricKeys(string $password = '', string $algo = '', int $size = 0): array
    {
        return $this->_defaultInstance->newAsymmetricKeys($password, $algo, $size);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkPrivateKeyPassword()
     */
    public function checkPrivateKeyPassword(?string $privateKey, ?string $password): bool
    {
        return $this->_defaultInstance->checkPrivateKeyPassword($privateKey, $password);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::changePrivateKeyPassword()
     */
    public function changePrivateKeyPassword(?string $privateKey, ?string $oldPassword, ?string $newPassword): string
    {
        return $this->_defaultInstance->changePrivateKeyPassword($privateKey, $oldPassword, $newPassword);
    }
}
