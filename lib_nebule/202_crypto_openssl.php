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
        $this->_genHashAlgorithmList();
        $this->_genSymmetricAlgorithmList();
        $this->_genAsymmetricAlgorithmList();
    }

    /*
	 * ------------------------------------------------------------------------------------------
	 * Gestion de la génération pseudo-aléatoire.
	 */

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
     * @return string
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
        if ($size == 0
            || !is_int($size)
        )
            return '';
        $strong = false;
        $data = openssl_random_pseudo_bytes($size, $strong);
        if (!$strong)
            return '';
        return $data;
    }

    /**
     * Calcul l'entropie des données.
     *
     * @param string $data
     * @return float
     */
    public function getEntropy(string &$data): float
    {
        $h = 0;
        $s = strlen($data);
        if ($s == 0)
            return 0;
        foreach (count_chars($data, 1) as $v) {
            $p = $v / $s;
            $h -= $p * log($p) / log(2);
        }
        return $h;
    }


    /*
	 * ------------------------------------------------------------------------------------------
	 * Gestion de la prise d'empreinte cryptographique.
	 */
    private $_hashAlgorithmList = array(), // Liste des fonctions de prise d'empreintes.
        $_hashAlgorithm,               // Fonction de prise d'empreintes.
        $_hashAlgorithmName,           // Fonction de prise d'empreintes.
        $_hashLength,                  // Taille d'une empreinte.
        $_hashStrength;                // Classification de résistance de l'algorithme.

    // Retourne l'algorithme de hash en cours d'utilisation.
    public function hashAlgorithm()
    {
        return $this->_hashAlgorithm;
    }

    public function hashAlgorithmName()
    {
        return $this->_hashAlgorithmName;
    }

    // Retourne la longueur de l'empreinte de l'algorithme de hash en cours d'utilisation.
    public function hashLength()
    {
        return $this->_hashLength;
    }

    // Vérifie le bon fonctionnement de l'algorithme de hash en cours d'utilisation.
    public function checkHashFunction(): bool
    {
        $check = false;
        $data = 'Bienvenue dans le projet nebule.';
        $hash = $this->hash($data);
        switch ($this->_hashAlgorithmName) {
            case 'dss1':
                if ($hash == 'd689bc73bbf35e6547e6de4b0ea79a5fd3b83ffa') {
                    $check = true;
                }
                break;
            case 'sha1':
                if ($hash == 'd689bc73bbf35e6547e6de4b0ea79a5fd3b83ffa') {
                    $check = true;
                }
                break;
            case 'sha224':
                if ($hash == '8ee809ef3ec56e4e31273e2ee232697683d260db72d543ce6db4ab64') {
                    $check = true;
                }
                break;
            case 'sha256':
                if ($hash == '0b8dc4408e7ab1c81716ae978abe1f75d4bd3ea9a7b882b8da6afacdafc0e32b') {
                    $check = true;
                }
                break;
            case 'sha384':
                if ($hash == 'fef7e57afdbf243a756eae37fa7c556bc71050f555209d78b29d2e8feef56e62ed92da5e291669b6262170cd4f0dd0ba') {
                    $check = true;
                }
                break;
            case 'sha512':
                if ($hash == 'b9d7b17462c0e2657171975ee0bd37e8dc0cab5d6ebc6496864af2e261f16d35c16642898ba0af5174ad80bada202032c641595be0fc56e4d35599add72f8079') {
                    $check = true;
                }
                break;
            case 'rmd160':
                if ($hash == '148e5adf9723d612de6bd4d2b9477852d6544ee1') {
                    $check = true;
                }
                break;
            case 'md5':
                if ($hash == 'a6675f8e9ac88030bf106d2801089e6e') {
                    $check = true;
                }
                break;
            case 'md4':
                if ($hash == '3c9aaffef0fc30b9cbc598637e6b64e5') {
                    $check = true;
                }
                break;
        }
        unset($data, $hash);
        return $check;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::setHashAlgorithm()
     */
    public function setHashAlgorithm(string $algo): bool
    {
        $algo = $this->_translateHashAlgorithmName($algo);
        // Vérifie si la liste des algorithmes n'est pas vide.
        if (sizeof($this->_hashAlgorithmList) == 0) {
            return false;
        }
        if (!in_array($algo, $this->_hashAlgorithmList)) {
            return false;
        }
        $this->_hashAlgorithmName = $algo;
        $this->_parseHashAlgorithm();
        return true;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkHashAlgorithm()
     */
    public function checkHashAlgorithm(string $algo): bool
    {
        if ($algo == '') return false;
        elseif ($algo == 'dss1') return true;
        elseif ($algo == 'sha1') return true;
        elseif ($algo == 'sha224') return true;
        elseif ($algo == 'sha256') return true;
        elseif ($algo == 'sha384') return true;
        elseif ($algo == 'sha512') return true;
        elseif ($algo == 'rmd160') return true;
        elseif ($algo == 'md5') return true;
        elseif ($algo == 'md4') return true;
        else                         return false;
        // @todo ... ajouter une option PERMIT pour gérer les algo non sûrs.
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::hash()
     */
    public function hash($data, $algo = '')
    {
        if (strlen($data) == 0)
            return false;
        $algo = $this->_translateHashAlgorithmName($algo);
        if ($algo == '')
            $algo = $this->_hashAlgorithmName;
        if (!$this->checkHashAlgorithm($algo))
            return false;
        return hash($algo, $data);
    }

    /**
     * Génère (récupère) la liste des algorithmes supportés.
     */
    private function _genHashAlgorithmList()
    {
        //$this->_hashAlgorithmList = array('sha128', 'sha256', 'sha512'); // Génère la liste des algorithmes disponibles. @todo A revoir...
        $this->_hashAlgorithmList = openssl_get_md_methods(true); // Génère la liste des algorithmes disponibles.
        $this->_hashAlgorithmName = $this->_configuration->getOptionUntyped('cryptoHashAlgorithm');
        $this->_parseHashAlgorithm();
    }

    private function _translateHashAlgorithmName(string $name): string
    {
        switch ($name) {
            case 'sha1.128':
                $return = 'sha1';
                break;
            case 'sha2.224':
                $return = 'sha224';
                break;
            case 'sha2.256':
                $return = 'sha256';
                break;
            case 'sha2.384':
                $return = 'sha384';
                break;
            case 'sha2.512':
                $return = 'sha512';
                break;
            default:
                $this->_metrology->addLog('Invalid hash algo ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '43c10796');
                $return = '';
        }
        return $return;
    }

    /**
     * Convertit le nom de la fonction de l'algorithme et la taille de hash en fontion du nom court de l'algorithme.
     */
    private function _parseHashAlgorithmName(string $name): string
    {
        switch ($name) {
            case 'sha1':
                $return = 'sha1.128';
                break;
            case 'sha224':
                $return = 'sha2.224';
                break;
            case 'sha256':
                $return = 'sha2.256';
                break;
            case 'sha384':
                $return = 'sha2.384';
                break;
            case 'sha512':
                $return = 'sha2.512';
                break;
            default:
                $this->_metrology->addLog('Invalid hash algo ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'dfcabd6f');
                $return = '';
                break;
        }
        return $return;
    }

    /**
     * Prépare le nom de la fonction de l'algorithme et la taille de hash en fontion du nom court de l'algorithme.
     */
    private function _parseHashAlgorithm(): void
    {
        $name = $this->_parseHashAlgorithmName($this->_hashAlgorithmName);
        if ($name == '') {
            $this->_hashAlgorithm = ' ';
            $this->_hashLength = 0;
        } else {
            $this->_hashAlgorithm = strtok($name, '.');
            $this->_hashLength = (int)strtok('.');
        }
    }


    /*
	 * ------------------------------------------------------------------------------------------
	 * Gestion du chiffrement symétrique.
	 */
    private $_symmetricAlgorithmList = array(), // Liste des fonctions de chiffrement symétrique.
        $_symmetricAlgorithm,               // Fonction de chiffrement symétrique.
        $_symmetricAlgorithmName,           // Fonction de chiffrement symétrique.
        $_symmetricAlgorithmMode,           // Fonction de chiffrement symétrique.
        $_symetricKeyLength;               // Taille d'une clé et d'un bloc de chiffrement.

    // Retourne l'algorithme de chiffrement symétrique en cours d'utilisation.
    public function symmetricAlgorithm()
    {
        return $this->_symmetricAlgorithm;
    }

    public function symmetricAlgorithmName()
    {
        return $this->_symmetricAlgorithmName;
    }

    public function symmetricAlgorithmMode()
    {
        return $this->_symmetricAlgorithmMode;
    }

    // Retourne la longueur de clé de l'algorithme de chiffrement symétrique en cours d'utilisation.
    public function symmetricKeyLength()
    {
        return $this->_symetricKeyLength;
    }

    // Vérifie le bon fonctionnement de l'algorithme de chiffrement symétrique en cours d'utilisation.
    public function checkSymmetricFunction(): bool
    {
        $check = false;

        // Liste tous les algorithmes de chiffrement supportés.
        $l = openssl_get_cipher_methods(true);

        // Pour chacun...
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
                $dcod = openssl_decrypt($code, $this->_symmetricAlgorithmName, $binKey, OPENSSL_RAW_DATA, $binIV);
                // Si les données décodées sont les mêmes que les données sources.
                if ($data == $dcod)
                    $check = true; // Le test est bon.
                unset($data, $binIV, $hexIV, $binKey, $hexKey, $code, $dcod);
            }
        }
        unset($l, $a);
        return $check;
    }

    public function setSymmetricAlgorithm(string $algo): bool
    {
        $algo = (string)$algo;
        if (sizeof($this->_symmetricAlgorithmList) == 0) return false; // Vérifie si la liste des algorithmes n'est pas vide.
        if (!in_array($algo, $this->_symmetricAlgorithmName)) return false;
        $this->_symmetricAlgorithmName = $algo;
        $this->_parseSymmetricAlgorithm();
        return true;
    }

    public function checkSymmetricAlgorithm(string $algo): bool
    {
        if ($algo == '') return false;
        return true;
        // A faire...
    }

    private function _genSymmetricAlgorithmList()
    {
        //$this->_symmetricAlgorithmList = array('aes-256-cbc', 'aes-256-ctr'); // Génère la liste des algorithmes disponibles. A revoir...
        $this->_symmetricAlgorithmList = openssl_get_cipher_methods(true); // Génère la liste des algorithmes disponibles.
        $this->_symmetricAlgorithmName = $this->_configuration->getOptionUntyped('cryptoSymmetricAlgorithm');
        $this->_parseSymmetricAlgorithm();
    }

    private function _parseSymmetricAlgorithmName(string $name): string
    {
        switch ($name) {
            case 'aes-128-ctr':
                $return = 'aes.128.ctr';
                break;
            case 'aes-128-ecb':
                $return = 'aes.128.ecb';
                break;
            case 'aes-128-cbc':
                $return = 'aes.128.cbc';
                break;
            case 'aes-256-ctr':
                $return = 'aes.256.ctr';
                break;
            case 'aes-256-ecb':
                $return = 'aes.256.ecb';
                break;
            case 'aes-256-cbc':
                $return = 'aes.256.cbc';
                break;
            default:
                $this->_metrology->addLog('Invalid symmetric algo ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '9e1ce2f0');
                $return = '';
                break;
        }
        return $return;
    }

    private function _parseSymmetricAlgorithm(): void
    {
        $name = $this->_parseSymmetricAlgorithmName($this->_symmetricAlgorithmName);
        if ($name == '') {
            $this->_symmetricAlgorithm = ' ';
            $this->_symetricKeyLength = 0;
            $this->_symmetricAlgorithmMode = '';
        } else {
            $this->_symmetricAlgorithm = strtok($name, '.');
            $this->_symetricKeyLength = (int)strtok('.');
            $this->_symmetricAlgorithmMode = strtok('.');
        }
    }

    /**
     * Génère un IV nul pour l'algorythme symétrique.
     */
    private function _genSymmetricAlgorithmNullIV(): string
    {
        $l = $this->_symetricKeyLength / 8;
        $r = '';
        for ($i = 0; $i < $l; $i++)
            $r = $r . '0';
        return $r;
    }

    /**
     * Chiffrement de données avec algorithme symétrique.
     * @param string $data
     * @param string $key
     * @param string $hexIV
     * @return string
     */
    public function crypt(string $data, string $key, string $hexIV = '')
    {
        // Vérifications.
        if (strlen($data) == 0) {
            return false;
        }
        if (strlen($key) == 0) {
            return false;
        }

        // Si IV null, en génère un à zéro.
        if ($hexIV == '') {
            $hexIV = $this->_genSymmetricAlgorithmNullIV();
        }

        // Encode l'IV en binaire.
        $binIV = pack("H*", $hexIV);

        // Réalise le chiffrement.
        return openssl_encrypt($data, $this->_symmetricAlgorithmName, $key, OPENSSL_RAW_DATA, $binIV);
    }

    /**
     * Déchiffrement de données avec algorithme symétrique.
     * @param string $data
     * @param string $key
     * @param string $hexIV
     * @return string
     */
    public function decrypt(string $data, string $key, string $hexIV = '')
    {
        // Vérifications.
        if (strlen($data) == 0) {
            return false;
        }
        if (strlen($key) == 0) {
            return false;
        }

        // Si IV null, en génère un à zéro.
        if ($hexIV == '') {
            $hexIV = $this->_genSymmetricAlgorithmNullIV();
        }

        // Encode l'IV en binaire.
        $binIV = pack("H*", $hexIV);

        // Réalise le déchiffrement.
        return openssl_decrypt($data, $this->_symmetricAlgorithmName, $key, OPENSSL_RAW_DATA, $binIV);
    }


    /*
	 * ------------------------------------------------------------------------------------------
	 * Gestion du chiffrement asymétrique.
	 */
    private $_asymmetricAlgorithmList = array(), // Fonction de chiffrement asymétrique (càd avec clé publique/privée).
        $_asymmetricAlgorithm,               // Fonction de chiffrement asymétrique (càd avec clé publique/privée).
        $_asymmetricAlgorithmName,           // Fonction de chiffrement asymétrique (càd avec clé publique/privée).
        $_asymetricKeyLength;               // Taille d'une clé publique/privée.

    // Retourne l'algorithme de chiffrement asymétrique en cours d'utilisation.
    public function asymmetricAlgorithm()
    {
        return $this->_asymmetricAlgorithm;
    }

    public function asymmetricAlgorithmName()
    {
        return $this->_asymmetricAlgorithmName;
    }

    // Retourne la longueur de clé de l'algorithme de chiffrement asymétrique en cours d'utilisation.
    public function asymmetricKeyLength()
    {
        return $this->_asymetricKeyLength;
    }

    // Vérifie le bon fonctionnement de l'algorithme de chiffrement asymétrique en cours d'utilisation.
    public function checkAsymmetricFunction(): bool
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
        $hashdata = hash($this->_hashAlgorithmName, $data);

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

        unset($data, $private_pass, $private_pem, $private_key, $public_pem, $public_key, $ok,
            $data, $hashdata, $binary_signature, $hexsign, $binsign, $decrypted);

        return $check;
    }

    public function setAsymmetricAlgorithm(string $algo): bool
    {
        $algo = (string)$algo;
        if (sizeof($this->_asymmetricAlgorithmList) == 0) return false; // Vérifie si la liste des algorithmes n'est pas vide.
        if (!in_array($algo, $this->_asymmetricAlgorithmName)) return false;
        $this->_asymmetricAlgorithmName = $algo;
        $this->_parseAsymmetricAlgorithm();
        return true;
    }

    public function checkAsymmetricAlgorithm(string $algo): bool
    {
        if ($algo == '') return false;
        return true;
        // A faire...
    }

    private function _genAsymmetricAlgorithmList()
    {
        // Génère la liste des algorithmes disponibles. A revoir...
        $this->_asymmetricAlgorithmList = array('rsa1024', 'rsa2048', 'rsa4096', 'ecdsa');        // A revoir...
        $this->_asymmetricAlgorithmName = $this->_configuration->getOptionUntyped('cryptoAsymmetricAlgorithm');
        $this->_parseAsymmetricAlgorithm();
        /*
OPENSSL_KEYTYPE_RSA (entier)
OPENSSL_KEYTYPE_DSA (entier)
OPENSSL_KEYTYPE_DH (entier)
OPENSSL_KEYTYPE_EC (entier)
		 */
    }

    private function _parseAsymmetricAlgorithmName(string $name): string
    {
        switch ($name) {
            case 'rsa1024':
                $return = 'rsa.1024';
                break;
            case 'rsa2048':
                $return = 'rsa.2048';
                break;
            case 'rsa4096':
                $return = 'rsa.4096';
                break;
            default:
                $this->_metrology->addLog('Invalid asymmetric algo ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '0c2a5398');
                $return = '';
                break;
        }
        return $return;
    }

    private function _parseAsymmetricAlgorithm()
    {
        $name = $this->_parseAsymmetricAlgorithmName($this->_asymmetricAlgorithmName);
        if ($name == '') {
            $this->_asymmetricAlgorithm = ' ';
            $this->_asymetricKeyLength = 0;
        } else {
            $this->_asymmetricAlgorithm = strtok($name, '.');
            $this->_asymetricKeyLength = (int)strtok('.');
        }
    }

    public function sign(string $hash, string $eid, string $privatePassword): ?string
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
        return null;
    }

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

    // Génération d'une pkey pour une entité nebule.
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
        $config['digest_alg'] = $this->_hashAlgorithmName;
        $config['private_key_bits'] = $this->_asymetricKeyLength;

        // Génération d'un bi-clé.
        $newPkey = openssl_pkey_new($config); // @todo Vérifier la bonne génération du bi-clé et refaire si besoin...
        unset($config);
        return $newPkey;
    }

    // Extraction de la clé publique.
    public function getPkeyPublic($pkey)
    {
        $fullPublicKey = openssl_pkey_get_details($pkey);
        return $fullPublicKey['key'];
    }

    // Extraction de la clé privée, de préférence protégée par mot de passe.
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
    public function getPrivateKey(string $privateKey, string $password)
    {
        return openssl_pkey_get_private($privateKey, $password);
    }
}
