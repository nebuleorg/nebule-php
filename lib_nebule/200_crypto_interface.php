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
interface CryptoInterface
{
    /**
     * Check if the cryptographique function have correct return value.
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

    // --------------------------------------------------------------------------------

    /**
     * Get $size octets of random string in raw form (strong) or in hexadecimal form (pseudo).
     * Quality of random sequence can be selected with strong or pseudo random.
     * But, to save precious entropy, you have to use pseudo random in all case where you do not absolutely need strong random.
     *
     * @param int $size
     * @param int $quality
     * @return string
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_PSEUDO): string;

    // --------------------------------------------------------------------------------

    /**
     * Get the hash value for $data with a specified hash algorithm.
     *
     * @param string $data
     * @param string $algo
     * @return string
     */
    public function hash(string $data, string $algo = ''): string;

    // --------------------------------------------------------------------------------

    /**
     * @param string $data
     * @param string $algo
     * @param string $hexKey
     * @param string $hexIV
     * @return string
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string;

    /**
     * @param string $data
     * @param string $algo
     * @param string $hexKey
     * @param string $hexIV
     * @return string
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string;

    // --------------------------------------------------------------------------------

    /**
     * @param string $data
     * @param string $privateKey
     * @param string $privatePassword
     * @return string
     */
    public function sign(string $data, string $privateKey, string $privatePassword): string;

    /**
     * @param string $data
     * @param string $sign
     * @param string $publicKey
     * @return bool
     */
    public function verify(string $data, string $sign, string $publicKey): bool;

    /**
     * @param string $data
     * @param string $publicKey
     * @return string
     */
    public function encryptTo(string $data, string $publicKey): string;

    /**
     * @param string $code
     * @param string $privateKey
     * @param string $password
     * @return string
     */
    public function decryptTo(string $code, string $privateKey, string $password): string;

    /**
     * @param string $password
     * @return array
     */
    public function newAsymmetricKeys(string $password = ''): array;

    /**
     * @param string $privateKey
     * @param string $password
     * @return bool
     */
    public function checkPrivateKeyPassword(string $privateKey, string $password): bool;

    /**
     * @param string $privateKey
     * @param string $oldPassword
     * @param string $newPassword
     * @return string
     */
    public function changePrivateKeyPassword(string $privateKey, string $oldPassword, string $newPassword): string;
}
