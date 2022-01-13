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

    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
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



    /*
	 * ------------------------------------------------------------------------------------------
	 * Gestion du chiffrement symétrique.
	 */
    private function _checkSymmetricFunction(string $algo): bool
    {
        $algo = $this->_translateHashAlgorithm($algo);
        if ($algo == '')
            return false;

        $check = false;

        // Liste tous les algorithmes de chiffrement supportés.
        $l = openssl_get_cipher_methods(true);

        foreach ($l as $a) {
            // Si c'est l'algorithme en cours.
            if ($a == $this->_symmetricAlgorithmName) {
                // Compile les données sources, la clé et l'IV.
                $data = 'Bienvenue dans le projet nebule.';
                $hexIV = $this->_genSymmetricAlgorithmNullIV();
                $binIV = pack("H*", $hexIV);
                $hexKey = "8fdf208b4a79cef62f4e610ef7d409c110cb5d20b0148b9770cad5130106b6a1";
                $binKey = pack("H*", $hexKey);
                // Encode.
                $code = openssl_encrypt($data, $this->_symmetricAlgorithmName, $binKey, OPENSSL_RAW_DATA, $binIV);
                // Décode.
                $decode = openssl_decrypt($code, $this->_symmetricAlgorithmName, $binKey, OPENSSL_RAW_DATA, $binIV);
                // Si les données décodées sont les mêmes que les données sources.
                if ($data == $decode)
                    $check = true; // Le test est bon.
            }
        }
        unset($l, $a);
        return $check;
    }

    private function _checkSymmetricAlgorithm(string $algo): bool
    {
        if (isset(self::SYMMETRIC_ALGORITHM[$algo]))
            return true;
        return false;
    }

    /**
     * Génère un IV nul pour l'algorithme symétrique.
     */
    private function _genSymmetricAlgorithmNullIV(int $length): string
    {
        $r = '';
        for ($i = 0; $i < $length; $i++)
            $r = $r . '0';
        return $r;
    }

    private function _translateSymmetricAlgorithm(string $name): string
    {
        if (isset(self::TRANSLATE_SYMMETRIC_ALGORITHM[$name]))
            return self::TRANSLATE_SYMMETRIC_ALGORITHM[$name];
        $this->_metrology->addLog('Invalid symmetric algorithm ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '5f83d258');
        return '';
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encrypt()
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        if ($data == '' || $hexKey == '')
            return '';

        if ($hexIV == '') {
            $size = preg_split('/./', $algo);
            $hexIV = $this->_genSymmetricAlgorithmNullIV((int)$size[1]/8);
        }

        return openssl_encrypt($data, $this->_translateSymmetricAlgorithm($algo), $hexKey, OPENSSL_RAW_DATA, pack("H*", $hexIV));
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decrypt()
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        if ($data == '' || $hexKey == '')
            return '';

        if ($hexIV == '') {
            $size = preg_split('/./', $algo);
            $hexIV = $this->_genSymmetricAlgorithmNullIV((int)$size[1]/8);
        }

        return openssl_decrypt($data, $this->_symmetricAlgorithmName, $hexKey, OPENSSL_RAW_DATA, pack("H*", $hexIV));
    }


    /*
	 * ------------------------------------------------------------------------------------------
	 * Gestion du chiffrement asymétrique.
	 */
    private $_asymmetricAlgorithmList = array(), // Fonction de chiffrement asymétrique (càd avec clé publique/privée).
        $_asymmetricAlgorithm,               // Fonction de chiffrement asymétrique (càd avec clé publique/privée).
        $_asymmetricAlgorithmName,           // Fonction de chiffrement asymétrique (càd avec clé publique/privée).
        $_asymetricKeyLength;               // Taille d'une clé publique/privée.

    private function _checkAsymmetricFunction(string $algo): bool
    {
        $check = false;
        // Essai avec un couple de clés public/privé de test. mdp=0000
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
        $private_key = openssl_pkey_get_private($private_pem, $private_pass);
        $public_key = openssl_pkey_get_public($public_pem);
        $decrypted = '';
        $hashdata = hash('sha256', $data);

        // Signe les données.
        $binary_signature = '';
        $binhash = pack('H*', $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);

        $hexsign = bin2hex($binary_signature);
        //$binsign = hex2bin($hexsign);
        $binsign = pack('H*', $hexsign);

        // Vérifie la signature avec la clé publique.
        $ok = openssl_public_decrypt($binsign, $decrypted, $public_key, OPENSSL_PKCS1_PADDING);
        $decrypted = (bin2hex($decrypted));
        if ($ok && $decrypted == $hashdata)
            $check = true;

        return $check;
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

    /**
     * {@inheritDoc}
     * @see CryptoInterface::sign()
     */
    public function sign(string $hash, string $eid, string $privatePassword): string
    {
        $instance = $this->_cache->newNode($eid, Cache::TYPE_NODE);
        $privateKey = $instance->getContent();

        $signatureBin = '';
        // Extrait la clé privée déchiffrée.
        $privateKeyBin = openssl_pkey_get_private($privateKey, $privatePassword);
        // Encode la signature en binaire.
        $hashDataBin = pack('H*', $hash);
        // Signe les données.
        $ok = openssl_private_encrypt($hashDataBin, $signatureBin, $privateKeyBin, OPENSSL_PKCS1_PADDING);
        // Nettoyage des variables.
        $privatePassword = '';
        unset($privateKeyBin, $hash, $hashDataBin);
        // Si la fonction de chiffrement a bien fonctionnée.
        if ($ok !== false)
            return bin2hex($signatureBin);
        // Sinon retourne que ça s'est mal passé.
        $this->_metrology->addLog('ERROR crypto sign', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '3c5e617d'); // Log
        return '';
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::verify()
     */
    public function verify(string $hash, string $sign, string $eid): bool
    {
        $instance = $this->_cache->newNode($eid, Cache::TYPE_NODE);
        $publicKey = $instance->getContent();

        // Extrait la clé publique de l'entité signataire.
        $publicKeyID = openssl_pkey_get_public($publicKey);

        // Vérifie la présence et la cohérence de la clé publique.
        if ($publicKeyID === false) {
            return false;
        }

        // Encode la signature pour la vérification.
        $signBin = pack('H*', $sign);
        // Déchiffre la signature avec la clé publique.
        $decodeOK = openssl_public_decrypt($signBin, $decrypted, $publicKeyID, OPENSSL_PKCS1_PADDING);
        // Extrait la signature déchiffrée.
        $decrypted = substr(bin2hex($decrypted), -64, 64);                                        // @todo WARNING A faire pour le cas général.
        // Vérifie la signature.
        if ($decodeOK !== false && $decrypted == $hash) {
            return true;
        }
        //else
        return false;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::cryptTo()
     */
    public function cryptTo(string $data, string $eid)
    {
        $instance = $this->_cache->newNode($eid, Cache::TYPE_NODE);
        $publicKey = $instance->getContent();

        $code = '';
        // Extrait la clé privée déchiffrée.
        $publicKeyID = openssl_pkey_get_public($publicKey);
        // Vérifie la présence et la cohérence de la clé publique.
        if ($publicKeyID === false)
            return false;
        // Chiffre les données.
        $ok = openssl_public_encrypt($data, $code, $publicKeyID, OPENSSL_PKCS1_PADDING);
        // Nettoyage des variables.
        $data = null;
        $publicKey = null;
        unset($publicKeyID);
        // Si la fonction de chiffrement a bien fonctionnée.
        if ($ok !== false)
            return $code;
        // Sinon retourne que ça s'est mal passé.
        return false;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decryptTo()
     */
    public function decryptTo(string $code, string $eid, string $privatePassword)
    {
        $instance = $this->_cache->newNode($eid, Cache::TYPE_NODE);
        $privateKey = $instance->getContent();

        $data = '';
        // Extrait la clé privée déchiffrée.
        $privateKeyBin = openssl_pkey_get_private($privateKey, $privatePassword);
        // Signe les données.
        //$ok = openssl_public_decrypt($code, $data, $privateKeyBin, OPENSSL_PKCS1_PADDING);
        $ok = openssl_private_decrypt($code, $data, $privateKeyBin, OPENSSL_PKCS1_PADDING);
        // Nettoyage des variables.
        $code = null;
        $privatePassword = null;
        unset($privateKeyBin);
        // Si la fonction de déchiffrement a bien fonctionnée.
        if ($ok !== false)
            return $data;
        // Sinon retourne que ça s'est mal passé.
        return false;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::newPkey()
     */
    public function newPkey(): bool
    {
        // Vérifie que l'algorithme est correcte.
        if ($this->_asymmetricAlgorithm == '') return false;

        // Configuration de la génération.
        $config = array();
        switch ($this->_asymmetricAlgorithm) {
            case 'rsa' :
                $config['private_key_type'] = OPENSSL_KEYTYPE_RSA;
                break;
            case 'dsa' :
                $config['private_key_type'] = OPENSSL_KEYTYPE_DSA;
                break;
            default    :
                return false;
        }
        $config['digest_alg'] = $this->_hashAlgorithmName; // TODO
        $config['private_key_bits'] = $this->_asymetricKeyLength;

        // Génération d'un bi-clé.
        $newPkey = openssl_pkey_new($config); // @todo Vérifier la bonne génération du bi-clé et refaire si besoin...
        unset($config);
        return $newPkey;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getPkeyPublic()
     */
    public function getPkeyPublic($pkey)
    {
        $fullPublicKey = openssl_pkey_get_details($pkey);
        return $fullPublicKey['key'];
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getPkeyPrivate()
     */
    public function getPkeyPrivate(string $pkey, string $password = '')
    {
        if ($password == '') {
            openssl_pkey_export($pkey, $privateKey, null);
        } else {
            openssl_pkey_export($pkey, $privateKey, $password);
        }
        return $privateKey;
    }

    // Extrait une clé privée.
    public function getPrivateKey(string $privateKey, string $password): bool
    {
        return openssl_pkey_get_private($privateKey, $password);
    }
}
