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

    /**
     * Get $size octets of random string in hexadecimal form.
     * Quality of random sequence can be selected with strong or pseudo random.
     * But, to save precious entropy, you have to use pseudo random in all case where you do not absolutely need strong random.
     *
     * @param int $size
     * @param int $quality
     * @return string
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_PSEUDO): string;

    /**
     * @param string $data
     * @param string $algo
     * @return string
     */
    public function hash(string $data, string $algo = ''): string;

    /**
     * @param string $data
     * @param string $hexKey
     * @param string $hexIV
     * @return mixed
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string;

    /**
     * @param string $data
     * @param string $hexKey
     * @param string $hexIV
     * @return mixed
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string;

    /**
     * @param string $hash
     * @param string $eid
     * @param string $privatePassword
     * @return string
     */
    public function sign(string $hash, string $eid, string $privatePassword): string;

    /**
     * @param string $hash
     * @param string $sign
     * @param string $eid
     * @return bool
     */
    public function verify(string $hash, string $sign, string $eid): bool;

    /**
     * @param string $data
     * @param string $eid
     * @return mixed
     */
    public function cryptTo(string $data, string $eid);

    /**
     * @param string $code
     * @param string $privateKey
     * @param string $privatePassword
     * @return mixed
     */
    public function decryptTo(string $code, string $privateKey, string $privatePassword);

    /**
     * @return mixed
     */
    public function newPkey();

    /**
     * @param $pkey
     * @return mixed
     */
    public function getPkeyPublic($pkey);

    /**
     * @param string $pkey
     * @param string $password
     * @return mixed
     */
    public function getPkeyPrivate(string $pkey, string $password = '');
}
