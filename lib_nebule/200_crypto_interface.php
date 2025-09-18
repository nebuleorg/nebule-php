<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface CryptoInterface
{
    /**
     * Return default crypto instance.
     *
     * @return CryptoInterface
     */
    public function getCryptoInstance(): CryptoInterface;

    /**
     * Return the default crypto class name.
     *
     * @return string
     */
    public function getCryptoInstanceName(): string;

    /**
     * Retourne le type de lib crypto.
     *
     * @return string
     */
    //public function getType(): string;

    /**
     * Retourne l'état de préparation'.
     *
     * @return bool
     */
    public function getReady(): bool;

    /**
     * Check if the cryptographique function has the correct return value.
     *
     * @param string $algo
     * @param int $type
     * @return bool
     */
    public function checkFunction(string $algo, int $type): bool;

    /**
     * Check if the cryptographique function is supported.
     *
     * @param string $algo
     * @param int $type
     * @return bool
     */
    public function checkValidAlgorithm(string $algo, int $type): bool;

    /**
     * Get a list of supported cryptographique function by type.
     *
     * @param int $type
     * @return array
     */
    public function getAlgorithmList(int $type): array;

    // --------------------------------------------------------------------------------

    /**
     * Get $size octets of a random string in raw form (strong) or in hexadecimal form (pseudo).
     * The quality of a random sequence can be selected with strong or pseudo random.
     * But to save precious entropy, you have to use pseudo random in all cases where you do not absolutely need strong random.
     * If problem, return an empty string.
     *
     * @param int $size
     * @param int $quality
     * @return string
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_PSEUDO): string;

    /**
     * Get the value of the data entropy.
     *
     * @param string $data
     * @return float
     */
    public function getEntropy(string &$data): float;

    // --------------------------------------------------------------------------------

    /**
     * Get the hash value for $data with a specified hash algorithm.
     * If problem, return an empty string.
     *
     * @param string $data
     * @param string $algo
     * @return string
     */
    public function hash(string $data, string $algo = ''): string;

    // --------------------------------------------------------------------------------

    /**
     * Get encrypted $data with specified cryptographic algorithm, key in hexadecimal and IV in hexadecimal.
     * If problem, return an empty string.
     *
     * @param string $data
     * @param string $algo
     * @param string $hexKey
     * @param string $hexIV
     * @return string
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string;

    /**
     * Get unencrypted data with specified cryptographic algorithm, key in hexadecimal and IV in hexadecimal.
     * If problem, return an empty string.
     *
     * @param string $data
     * @param string $algo
     * @param string $hexKey
     * @param string $hexIV
     * @return string
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string;

    // --------------------------------------------------------------------------------

    /**
     * Get sign value of $data with a private key (not EID).
     * If problem, return an empty string.
     *
     * @param string $data
     * @param string $privateKey
     * @param string $privatePassword
     * @return string
     */
    public function sign(string $data, string $privateKey, string $privatePassword): string;

    /**
     * Verify sign value of data with a public key (not EID).
     * If problem, return false.
     *
     * @param string $data
     * @param string $sign
     * @param string $publicKey
     * @return bool
     */
    public function verify(string $data, string $sign, string $publicKey): bool;

    /**
     * Encode $data with a public key (not EID).
     * Data has a maximum size minus padding to pass without a split.
     * If problem, return an empty string.
     *
     * @param string $data
     * @param ?string $publicKey
     * @return string
     */
    public function encryptTo(string $data, ?string $publicKey): string;

    /**
     * Decode $data with a private key (not EID).
     * Data has a maximum size minus padding to pass without a split.
     * If problem, return an empty string.
     *
     * @param string $code
     * @param ?string $privateKey
     * @param ?string $password
     * @return string
     */
    public function decryptTo(string $code, ?string $privateKey, ?string $password): string;

    /**
     * Generate a new asymmetric cryptographic bi-key.
     * If problem, return an empty array.
     *
     * @param string $password
     * @param string $algo
     * @param string $size
     * @return array
     */
    public function newAsymmetricKeys(string $password = '', string $algo='', string $size=''): array;

    /**
     * Check the password on a private key (not EID).
     *
     * @param ?string $privateKey
     * @param ?string $password
     * @return bool
     */
    public function checkPrivateKeyPassword(?string $privateKey, ?string $password): bool;

    /**
     * Change the password on a private key (not EID).
     * If problem, return an empty string.
     *
     * @param ?string $privateKey
     * @param ?string $oldPassword
     * @param ?string $newPassword
     * @return string
     */
    public function changePrivateKeyPassword(?string $privateKey, ?string $oldPassword, ?string $newPassword): string;
}
