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
class CryptoOpenssl implements CryptoInterface
{
    const HASH_ALGORITHM = array(
        'sha1-128',
        'sha2-224',
        'sha2-256',
        'sha2-384',
        'sha2-512',
    );
    const TRANSLATE_HASH_ALGORITHM = array(
        'sha1-128' => 'sha1',
        'sha2-224' => 'sha224',
        'sha2-256' => 'sha256',
        'sha2-384' => 'sha384',
        'sha2-512' => 'sha512',
    );
    const TEST_HASH_ALGORITHM = array(
        'value'    => 'Bienvenue dans le projet nebule.',
        'sha1-128' => 'd689bc73bbf35e6547e6de4b0ea79a5fd3b83ffa',
        'sha2-224' => '8ee809ef3ec56e4e31273e2ee232697683d260db72d543ce6db4ab64',
        'sha2-256' => '0b8dc4408e7ab1c81716ae978abe1f75d4bd3ea9a7b882b8da6afacdafc0e32b',
        'sha2-384' => 'fef7e57afdbf243a756eae37fa7c556bc71050f555209d78b29d2e8feef56e62ed92da5e291669b6262170cd4f0dd0ba',
        'sha2-512' => 'b9d7b17462c0e2657171975ee0bd37e8dc0cab5d6ebc6496864af2e261f16d35c16642898ba0af5174ad80bada202032c641595be0fc56e4d35599add72f8079',
    );

    const SYMMETRIC_ALGORITHM = array(
        'aes.128.cbc',
        'aes.128.ctr',
        'aes.192.cbc',
        'aes.192.ctr',
        'aes.256.cbc',
        'aes.256.ctr',
    );
    const TRANSLATE_SYMMETRIC_ALGORITHM = array(
        'aes.128.cbc' => 'aes-128-cbc',
        'aes.128.ctr' => 'aes-128-ctr',
        'aes.192.cbc' => 'aes-192-cbc',
        'aes.192.ctr' => 'aes-192-ctr',
        'aes.256.cbc' => 'aes-256-cbc',
        'aes.256.ctr' => 'aes-256-ctr',
    );

    const ASYMMETRIC_ALGORITHM = array(
        'rsa.1024',
        'rsa.2048',
        'rsa.4096',
    );
    const TRANSLATE_ASYMMETRIC_ALGORITHM = array(
        'rsa.1024',
        'rsa.2048',
        'rsa.4096',
    );

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
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkFunction()
     */
    public function checkFunction(string $algo, int $type): bool
    {
        switch ($type) {
            case (Crypto::TYPE_HASH):
                return $this->_checkHashFunction($algo);
            case (Crypto::TYPE_SYMMETRIC):
                return $this->_checkSymmetricFunction($algo);
            case (Crypto::TYPE_ASYMMETRIC):
                return $this->_checkAsymmetricFunction($algo);
        }
        return false;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkValidAlgorithm()
     */
    public function checkValidAlgorithm(string $algo, int $type): bool
    {
        switch ($type) {
            case (Crypto::TYPE_HASH):
                return $this->_checkHashAlgorithm($algo);
            case (Crypto::TYPE_SYMMETRIC):
                return $this->_checkSymmetricAlgorithm($algo);
            case (Crypto::TYPE_ASYMMETRIC):
                return $this->_checkAsymmetricAlgorithm($algo);
        }
        return false;
    }

    private function _getAlgorithmName(string $algo): int
    {
        $v = preg_split('/\./', $algo); // aes.256.ctr
        return (int)$v[0];
    }

    private function _getAlgorithmSize(string $algo): int
    {
        $v = preg_split('/\./', $algo); // aes.256.ctr
        return (int)$v[1];
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_STRONG): string
    {
        if ($quality == Crypto::RANDOM_STRONG)
            return $this->_getStrongRandom($size);
        else
            return '';
    }

    /**
     * Génère de l'aléa avec une source attendue comme fiable.
     * La taille est en octets.
     * Vérifie que générateur se déclare comme retournant une valeur cryptographiquement fiable.
     * Retourne false si ce n'est pas le cas.
     *
     * @param int $size
     * @return string
     */
    private function _getStrongRandom(int $size = 32): string
    {
        if ($size == 0)
            return '';
        $strong = false;
        $data = openssl_random_pseudo_bytes($size, $strong);
        if (!$strong
            || $data === false
        )
            return '';
        return $data;
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::hash()
     */
    public function hash(string $data, string $algo = ''): string
    {
        $algo = $this->_translateHashAlgorithm($algo);
        if ($algo == '')
            return '';
        return hash($algo, $data);
    }

    private function _checkHashFunction(string $algo): bool
    {
        if (!$this->_checkHashAlgorithm($algo))
            return false;
        $hash = $this->hash(self::TEST_HASH_ALGORITHM['value'], $algo);
        if (self::TEST_HASH_ALGORITHM[$algo] == $hash)
            return true;
        return false;
    }

    private function _checkHashAlgorithm(string $algo): bool
    {
        if (isset(self::HASH_ALGORITHM[$algo]))
            return true;
        return false;
    }

    private function _translateHashAlgorithm(string $name): string
    {
        if (isset(self::TRANSLATE_HASH_ALGORITHM[$name]))
            return self::TRANSLATE_HASH_ALGORITHM[$name];
        $this->_metrology->addLog('Invalid hash algorithm ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '43c10796');
        return '';
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encrypt()
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        if ($data == ''
            || $hexKey == ''
            || !$this->_checkSymmetricAlgorithm($algo)
        )
            return '';

        $binIV = $this->_getBinIV($hexIV, $algo);
        $binKey = pack("H*", $hexKey);

        return openssl_encrypt($data, $this->_translateSymmetricAlgorithm($algo), $binKey, OPENSSL_RAW_DATA, pack("H*", $binIV));
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decrypt()
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        if ($data == ''
            || $hexKey == ''
            || !$this->_checkSymmetricAlgorithm($algo)
        )
            return '';

        $binIV = $this->_getBinIV($hexIV, $algo);
        $binKey = pack("H*", $hexKey);

        return openssl_decrypt($data, $this->_translateSymmetricAlgorithm($algo), $binKey, OPENSSL_RAW_DATA, pack("H*", $binIV));
    }

    private function _checkSymmetricFunction(string $algo): bool
    {
        $data = 'Bienvenue dans le projet nebule.';
        $hexKey = "8fdf208b4a79cef62f4e610ef7d409c110cb5d20b0148b9770cad5130106b6a1";
        $hexIV = $this->hash(date(DATE_ATOM) . microtime(false), 'sha1');
        $code = $this->encrypt($data, $algo, $hexKey, $hexIV);
        if ($code == '')
            return false;

        $decode = $this->decrypt($code, $algo, $hexKey, $hexIV);
        if ($decode == '')
            return false;

        return true;
    }

    private function _checkSymmetricAlgorithm(string $algo): bool
    {
        if (isset(self::SYMMETRIC_ALGORITHM[$algo]))
            return true;
        return false;
    }

    private function _translateSymmetricAlgorithm(string $name): string
    {
        if (isset(self::TRANSLATE_SYMMETRIC_ALGORITHM[$name]))
            return self::TRANSLATE_SYMMETRIC_ALGORITHM[$name];
        $this->_metrology->addLog('Invalid symmetric algorithm ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '5f83d258');
        return '';
    }

    /**
     * Generate an empty IV as hexadecimal value full of zero.
     *
     * @param int $length
     * @return string
     */
    private function _getNullIV(int $length): string
    {
        $r = '';
        for ($i = 0; $i < $length; $i++)
            $r = $r . '0';
        return $r;
    }

    /**
     * Convert the IV on hexadecimal value as binary value with max size accepted by the cryptographic algorithm.
     *
     * @param string $hexIV
     * @param string $algo
     * @return string
     */
    private function _getBinIV(string $hexIV, string $algo): string
    {
        if ($hexIV == '')
            $hexIV = $this->_getNullIV($this->_getAlgorithmSize($algo));
        $binIV = pack("H*", $hexIV);
        $maxIV = openssl_cipher_iv_length($this->_translateSymmetricAlgorithm($algo));
        if (strlen($binIV) > $maxIV)
            $binIV = substr($binIV, 0, $maxIV);
        return $binIV;
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::sign()
     */
    public function sign(string $data, string $privateKey, string $privatePassword): string
    {
        $signatureBin = '';
        $ressource = openssl_pkey_get_private($privateKey, $privatePassword);
        if ($ressource === false)
            return '';
        $maxSize = ((int)openssl_pkey_get_details($ressource)['bits']/8) - 11;
        if (strlen($data) > $maxSize)
            $data = substr($data, 0, $maxSize); // for PKCS padding # 1.

        $dataBin = pack('H*', $data);
        if (openssl_private_encrypt($dataBin, $signatureBin, $ressource, OPENSSL_PKCS1_PADDING))
            return bin2hex($signatureBin);
        $this->_metrology->addLog('ERROR crypto sign', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '3c5e617d'); // Log
        return '';
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::verify()
     */
    public function verify(string $data, string $sign, string $publicKey): bool
    {
        $ressource = openssl_pkey_get_public($publicKey);
        if ($ressource === false)
            return false;
        $maxSize = ((int)openssl_pkey_get_details($ressource)['bits']/8) - 11;
        if (strlen($data) > $maxSize)
            $data = substr($data, 0, $maxSize); // for PKCS padding # 1.

        $signBin = pack('H*', $sign);
        $decodeOK = openssl_public_decrypt($signBin, $decrypted, $ressource, OPENSSL_PKCS1_PADDING);
        $decrypted = bin2hex($decrypted);
        if ($decodeOK && $decrypted == $data)
            return true;
        return false;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encryptTo()
     */
    public function encryptTo(string $data, string $publicKey): string
    {
        $ressource = openssl_pkey_get_public($publicKey);
        if ($ressource === false)
            return '';

        $code = '';
        if (openssl_public_encrypt($data, $code, $ressource, OPENSSL_PKCS1_PADDING))
            return $code;
        return '';
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decryptTo()
     */
    public function decryptTo(string $code, string $privateKey, string $password): string
    {
        $ressource = openssl_pkey_get_private($privateKey, $password);
        if ($ressource === false)
            return '';

        $data = '';
        if (openssl_private_decrypt($code, $data, $ressource, OPENSSL_PKCS1_PADDING))
            return $data;
        return '';
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::newAsymmetricKeys()
     */
    public function newAsymmetricKeys(string $password = ''): array
    {
        $algo = $this->_configuration->getOptionAsString('cryptoAsymmetricAlgorithm');
        if (!$this->_checkAsymmetricAlgorithm($algo))
            return array();

        // Prepare configuration for OpenSSL.
        $config = array(
            'digest_alg' => $this->_translateHashAlgorithm($this->_configuration->getOptionAsString('cryptoHashAlgorithm')),
            'private_key_bits' => $this->_getAlgorithmSize($algo),
        );
        switch ($this->_getAlgorithmName($algo)) {
            case 'rsa' :
                $config['private_key_type'] = OPENSSL_KEYTYPE_RSA;
                break;
            case 'dsa' :
                $config['private_key_type'] = OPENSSL_KEYTYPE_DSA;
                break;
            default    :
                return array();
        }

        // Generate new Pkey.
        $pkey = openssl_pkey_new($config);

        // Get public key.
        $pkeyDetail = openssl_pkey_get_details($pkey);
        if ($pkeyDetail === false)
            return array();

        // Get private key with password.
        if ($password == '')
            $password = null;
        if (openssl_pkey_export($pkey, $privateKey, $password) !== true)
            return array();

        unset($pkey);
        return array(
            'public' => $pkeyDetail['key'],
            'private' => $privateKey,
        );
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkPrivateKeyPassword()
     */
    public function checkPrivateKeyPassword(string $privateKey, string $password): bool
    {
        $pkey = openssl_pkey_get_private($privateKey, $password);
        if ($pkey === false)
            return false;
        unset($pkey);
        return true;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::changePrivateKeyPassword()
     */
    public function changePrivateKeyPassword(string $privateKey, string $oldPassword, string $newPassword): string
    {
        $pkey = openssl_pkey_get_private($privateKey, $oldPassword);
        if ($pkey === false)
            return '';
        if (openssl_pkey_export($pkey, $privateKey, $newPassword) !== true)
            return '';
        return $privateKey;
    }

    private function _checkAsymmetricFunction(string $algo): bool
    {
        $private_pem = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: AES-128-CBC,687B57E822A2DA943BFE465B95E9C217

A0Cv5nNdSrFq5R5kGgWlNytpTOlJh5E4+PiZK5L5D2JMwjIogB7ASjf+RDCwWWeJ
pgGBnMDLXyHdC10x0vMSidp6oUHv/fT8hgEKPr+KaUKAhLIQTrh/MiTmmaOlXBED
i1cTv3ZPo4u9m593vJ79dSP4DKNVmVoS2b7iZlPAimHx2CE+raKAttYYZv9yhWti
7HyA/cJcHypsK3WtTzBEkhtkD73wcAn7dmWBVaitrPUzZDJLtiwW4I4TmfMnOvFB
bQRia5vJzGg97rBhi7pc/hPozdQaFDrAvsnB/pqea486iIvVH6u7rEOd0gFSei1H
/O4zjxW/1Nx97cJkqGvaJ4ZMgKIz2t/YXUgZUMLBdaav4D4cUx9s05c9mwop1Vk6
ZEWbQ42aefq9GmU0N2sqCUwdrvxzO6Trf7T554F+kibqkY2YGZrEDD0iLK4ZWWOc
ILu5MNEvDrdBMi4JBr/BhWSOkCDmm6/l9qaWdSQW7x29I3KGcbPdNcbtzoqT+Sqa
T7UHkzbgHcLCjRtyecyLIBdgwJzoS+uS98dlQI+KOuxJk7Iw/+73z8aM5tuPDdtf
V3BAxDAIT6spAjomWgGBtGaOKXVuJjmj177qhY97L79PmFYvPZPVVVKBpbQNcH8z
3sso2/aWy4qotavOM4wWNBa/dmJmXa6kJ/VjwScaUUrTXYnHTr8uIoXqlldDj1sE
A+KwfxWveZxC6IE9XzQQuyk7DgfkHdbwKQ5+IKzDrrmSTjyDY5u6xiEsvLd8oSw6
LfMSjTNzTsdGnojuLQAt4r8t+K3cV6TgF1T+rxt67iXe0xn340KJK8jt31XN//F5
ktUEvdKGM4VuWt9E51bPC5p3znwTUGfP49Aeh2g3vhIJc51FSvMUtjIBytaTwqht
V37UMmkR6LtMOzwdGaoasZ0IZFVu2KvWt31OL9lEtWFAtLGWZ4NfwOVy0yQHeLkV
EtOLBWpMaxkwlTd5XS5TlGoS+/M9JGpHh0LnNrf5VsfixXEyiITyci32HEv/u8pS
zrJ/9cSj8mhj/gT0Tr2yp59YC6+3AoVX/qn8ucZX/Nwtd+XPvGeJ20z7IoJXbtjQ
0nnTnpOKEhjE41+Vc7ZxTeLtZ1dtW9PnoWHznGXYjb+Rj8FXBuRBz9tnsmBbtHs8
vORC2bv1py1GgvVbMuavNx4Y0MzbEfvNlLcctvarcN4zr2CavSmpAgVsmrjDFBWY
YfUKzDiIn3+O5T/nOXXvIjN5dw5tS2KUeZ+TFVQezoYhc6fZM5pNVlnbwa0zkXWZ
DW8RWy+bmB5nJiMliARWxqSSkaI5RG3dAyT5LsCV0U9Aolfr//bqvHWk/49zT0gf
uOUf4HEEslKM/f9RBDkLLOYAJzmq1Be/kWc4MkhVOqBu8qQg841aPsuioJdC6Ib2
mMw+at7hw80kCB6xqSJaSvbaSS4isTSGKxjPFtqXWQc9E2cvStTcoIaiT33JG/TN
U9tOokWpyoUJtPZanhMyBF/A9GAzo7DFuuL/4bGZ5bmoyFfH+wAjQPKqBDTREmHO
sTqIWSkAHD8dEZgukAY7kUsWrYnAqxaKbyAuT4Ni6SUcU2PiF2agvJh7Pe2SZyLj
-----END RSA PRIVATE KEY-----
EOD;
        $public_pem = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvUKNo2kJ5XYg7hh1X6rS
roMdy5d1CAhGzas2PzAwAc/8UdTiaOpQkhVYgyP/oM5ouROaTuALQ4RCY04O14op
wk56EQTPZfnbIIFZYyGZeH8w8S5Nabv2F9XK9eQJL0LgozBWBMAQpsuiqgJiq0Fe
XAUetu3McVlSd9Ro1F0xsjTTff6HIxNvCEwLM748rCXIDLxTxGYG5+YehigzH/at
jRAiRdZxSruYPyQcxWhZei5mSqLr31beZ2HmoNRiqOgx9FRqrJSLlCQSjv0Z9Ubu
17EVQB2iTsdjaNk7GqPdclBnXkaOg/VxIHsVeoUylukOPda+uTkvMiu9Ao9s/+A9
bwIDAQAB
-----END PUBLIC KEY-----
EOD;
        $private_pass = "0000";
        $data = 'Bienvenue dans le projet nebule.';
        $hashData = hash('sha256', $data);
        $signed = $this->sign($hashData, $private_pem, $private_pass);
        return $this->verify($hashData, $signed, $public_pem);
    }

    private function _checkAsymmetricAlgorithm(string $algo): bool
    {
        if (isset(self::ASYMMETRIC_ALGORITHM[$algo]))
            return true;
        return false;
    }

    private function _translateAsymmetricAlgorithm(string $name): string
    {
        if (isset(self::TRANSLATE_ASYMMETRIC_ALGORITHM[$name]))
            return self::TRANSLATE_ASYMMETRIC_ALGORITHM[$name];
        $this->_metrology->addLog('Invalid asymmetric algorithm ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '2b4c9b6b');
        return '';
    }
}
