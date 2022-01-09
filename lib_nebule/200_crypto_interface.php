<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * ------------------------------------------------------------------------------------------
 * L'interface CryptoInterface
 * ------------------------------------------------------------------------------------------
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface CryptoInterface
{
    // Fonction de génération.
    public function getPseudoRandom(int $size = 32): string;

    public function getStrongRandom(int $size = 32): string;

    public function getEntropy(string &$data): float;

    // Fonction de prise d'empreinte.
    public function hashAlgorithm();

    public function hashAlgorithmName();

    public function hashLength();

    public function checkHashFunction();

    public function setHashAlgorithm(string $algo);

    public function checkHashAlgorithm(string $algo);

    public function hash(string $data, string $algo = '');

    // Fonction de chiffrement symétrique.
    public function symmetricAlgorithm();

    public function symmetricAlgorithmName();

    public function symmetricAlgorithmMode();

    public function symmetricKeyLength();

    public function checkSymmetricFunction();

    public function setSymmetricAlgorithm(string $algo);

    public function checkSymmetricAlgorithm(string $algo);

    public function crypt(string $data, string $hexKey, string $hexIV = '');

    public function decrypt(string $data, string $hexKey, string $hexIV = '');

    // Fonction de chiffrement asymétrique.
    public function asymmetricAlgorithm();

    public function asymmetricAlgorithmName();

    public function asymmetricKeyLength();

    public function checkAsymmetricFunction();

    public function setAsymmetricAlgorithm(string $algo);

    public function checkAsymmetricAlgorithm(string $algo);

    public function sign(string $hash, string $eid, string $privatePassword): ?string;

    public function verify(string $hash, string $sign, string $eid): bool;

    public function cryptTo(string $data, string $eid);

    public function decryptTo(string $code, string $privateKey, string $privatePassword);

    public function newPkey();

    public function getPkeyPublic($pkey);

    public function getPkeyPrivate(string $pkey, string $password = '');
}
