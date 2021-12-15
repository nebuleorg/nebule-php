<?php
declare(strict_types=1);
namespace Nebule\Library;

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
    public function getPseudoRandom($size = 32);

    public function getStrongRandom($size = 32);

    public function getEntropy(&$data);

    // Fonction de prise d'empreinte.
    public function hashAlgorithm();

    public function hashAlgorithmName();

    public function hashLength();

    public function checkHashFunction();

    public function setHashAlgorithm($algo);

    public function checkHashAlgorithm($algo);

    public function hash($data, $algo = '');

    // Fonction de chiffrement symétrique.
    public function symetricAlgorithm();

    public function symetricAlgorithmName();

    public function symetricAlgorithmMode();

    public function symetricKeyLength();

    public function checkSymetricFunction();

    public function setSymetricAlgorithm($algo);

    public function checkSymetricAlgorithm($algo);

    public function crypt($data, $hexKey, $hexIV = '');

    public function decrypt($data, $hexKey, $hexIV = '');

    // Fonction de chiffrement asymétrique.
    public function asymetricAlgorithm();

    public function asymetricAlgorithmName();

    public function asymetricKeyLength();

    public function checkAsymetricFunction();

    public function setAsymetricAlgorithm($algo);

    public function checkAsymetricAlgorithm($algo);

    public function sign($hash, $privkey, $privatePassword);

    public function verify($hash, $sign, $pubkey);

    public function cryptTo($data, $pubkey);

    public function decryptTo($code, $privateKey, $privatePassword);

    public function newPkey();

    public function getPkeyPublic($pkey);

    public function getPkeyPrivate($pkey, $password = '');
}
