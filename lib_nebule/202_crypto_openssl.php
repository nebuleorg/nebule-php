<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/*
 * ------------------------------------------------------------------------------------------
 * La classe CryptoOpenssl.
 * ------------------------------------------------------------------------------------------
 *
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
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    private $_configuration;

    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_genHashAlgorithmList();
        $this->_genSymetricAlgorithmList();
        $this->_genAsymetricAlgorithmList();
    }

    /*
	 * ------------------------------------------------------------------------------------------
	 * Gestion de la génération pseudo-aléatoire.
	 */

    /**
     * Génère de l'aléa avec une source pas forcément fiable.
     * La taille est en octets.
     *
     * La graine de génération pseudo-aléatoire est un mélange de la date,
     *   de l'heure avec une précision de la micro-seconde,
     *   du nom et de la version de la bibliothèque.
     *
     * Ne doit pas être utilisé pour générer des mots de passes !
     *
     * @param int $size
     * @return string
     */
    public function getPseudoRandom($size = 32)
    {
        global $nebuleSurname, $nebuleLibVersion;

        if ($size == 0
            || !is_int($size)
        ) {
            return '';
        }

        // Résultat à remplir.
        $result = '';

        // Définit l'algorithme de divergence.
        $algo = 'sha256';

        // Génère une graine avec la date pour le compteur interne.
        $intcount = date(DATE_ATOM) . microtime(false) . $nebuleSurname . $nebuleLibVersion . $this->_nebuleInstance->getInstanceEntity();

        // Boucle de remplissage.
        while (strlen($result) < $size) {
            $diffsize = $size - strlen($result);

            // Fait évoluer le compteur interne.
            $intcount = hash($algo, $intcount);

            // Fait diverger le compteur interne pour la sortie.
            // La concaténation avec un texte empêche de remonter à la valeur du compteur interne.
            $outvalue = pack("H*", hash($algo, $intcount . 'liberté égalité fraternité'));

            // Tronc au besoin la taille de la sortie.
            if (strlen($outvalue) > $diffsize) {
                $outvalue = substr($outvalue, 0, $diffsize);
            }

            // Ajoute la sortie au résultat final.
            $result .= $outvalue;
        }

        // Nettoyage.
        unset($intcount, $outvalue, $diffsize);

        $this->_nebuleInstance->getMetrologyInstance()->addLog('Calculated rand : ' . strlen($result) . '/' . $size, Metrology::LOG_LEVEL_DEBUG);
        return $result;
    }

    /**
     * Génère de l'aléa avec une source attendue comme fiable.
     * La taille est en octets.
     *
     * Vérifie que générateur se déclare comme retournant une valeur cryptographiquement fiable.
     * Retourne false si ce n'est pas le cas.
     *
     * @param int $size
     * @return string|boolean
     */
    public function getStrongRandom($size = 32)
    {
        if ($size == 0
            || !is_int($size)
        ) {
            return '';
        }
        $strong = false;
        $data = openssl_random_pseudo_bytes($size, $strong);
        if (!$strong) {
            return false;
        }
        return $data;
    }

    /**
     * Calcul l'entropie des données.
     *
     * @param string $data
     * @return float
     */
    public function getEntropy(&$data)
    {
        $h = 0;
        $s = strlen($data);
        if ($s == 0) {
            return 0;
        }
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
    public function checkHashFunction()
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
    public function setHashAlgorithm($algo)
    {
        $algo = (string)$algo;
        // Vérifie si la liste des algorithmes n'est pas vide.
        if (sizeof($this->_hashAlgorithmList) == 0) {
            return false;
        }
        if (!in_array($algo, $this->_hashAlgorithmList)) {
            return false;
        }
        $this->_hashAlgorithmName = $algo;
        $this->_genHashAlgorithmProperties();
        return true;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkHashAlgorithm()
     */
    public function checkHashAlgorithm($algo)
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
        if (strlen($data) == 0) {
            return false;
        }
        if ($algo == '') {
            $algo = $this->_hashAlgorithmName;
        }
        if (!$this->checkHashAlgorithm($algo)) {
            return false;
        }
        return hash($algo, $data);
    }

    /**
     * Génère (récupère) la liste des algorithmes supportés.
     */
    private function _genHashAlgorithmList()
    {
        //$this->_hashAlgorithmList = array('sha128', 'sha256', 'sha512'); // Génère la liste des algorithmes disponibles. @todo A revoir...
        $this->_hashAlgorithmList = openssl_get_md_methods(true); // Génère la liste des algorithmes disponibles.
        $this->_hashAlgorithmName = $this->_configuration->getOption('cryptoHashAlgorithm');
        $this->_genHashAlgorithmProperties();
    }

    /**
     * Prépare le nom de la fonction de l'algorithme et la taille de hash en fontion du nom court de l'algorithme.
     */
    private function _genHashAlgorithmProperties()
    {
        switch ($this->_hashAlgorithmName) {
            case 'sha1':
                $this->_hashAlgorithm = 'sha1';
                $this->_hashLength = 128;
                break;
            case 'sha224':
                $this->_hashAlgorithm = 'sha2';
                $this->_hashLength = 224;
                break;
            case 'sha256':
                $this->_hashAlgorithm = 'sha2';
                $this->_hashLength = 256;
                break;
            case 'sha384':
                $this->_hashAlgorithm = 'sha2';
                $this->_hashLength = 384;
                break;
            case 'sha512':
                $this->_hashAlgorithm = 'sha2';
                $this->_hashLength = 512;
                break;
            default:
                $this->_hashAlgorithm = ' ';
                $this->_hashLength = 0;
                break;
        }
    }


    /*
	 * ------------------------------------------------------------------------------------------
	 * Gestion du chiffrement symétrique.
	 */
    private $_symetricAlgorithmList = array(), // Liste des fonctions de chiffrement symétrique.
        $_symetricAlgorithm,               // Fonction de chiffrement symétrique.
        $_symetricAlgorithmName,           // Fonction de chiffrement symétrique.
        $_symetricAlgorithmMode,           // Fonction de chiffrement symétrique.
        $_symetricKeyLength;               // Taille d'une clé et d'un bloc de chiffrement.

    // Retourne l'algorithme de chiffrement symétrique en cours d'utilisation.
    public function symetricAlgorithm()
    {
        return $this->_symetricAlgorithm;
    }

    public function symetricAlgorithmName()
    {
        return $this->_symetricAlgorithmName;
    }

    public function symetricAlgorithmMode()
    {
        return $this->_symetricAlgorithmMode;
    }

    // Retourne la longueur de clé de l'algorithme de chiffrement symétrique en cours d'utilisation.
    public function symetricKeyLength()
    {
        return $this->_symetricKeyLength;
    }

    // Vérifie le bon fonctionnement de l'algorithme de chiffrement symétrique en cours d'utilisation.
    public function checkSymetricFunction()
    {
        $check = false;

        // Liste tous les algorithmes de chiffrement supportés.
        $l = openssl_get_cipher_methods(true);

        // Pour chacun...
        foreach ($l as $a) {
            // Si c'est l'algorithme en cours.
            if ($a == $this->_symetricAlgorithmName) {
                // Compile les données sources, la clé et l'IV.
                $data = 'Bienvenue dans le projet nebule.';
                $hexIV = $this->_genSymetricAlgorithmNullIV();
                $binIV = pack("H*", $hexIV);
                $hexKey = "8fdf208b4a79cef62f4e610ef7d409c110cb5d20b0148b9770cad5130106b6a1";
                $binKey = pack("H*", $hexKey);
                // Encode.
                $code = openssl_encrypt($data, $this->_symetricAlgorithmName, $binKey, OPENSSL_RAW_DATA, $binIV);
                // Décode.
                $dcod = openssl_decrypt($code, $this->_symetricAlgorithmName, $binKey, OPENSSL_RAW_DATA, $binIV);
                // Si les données décodées sont les mêmes que les données sources.
                if ($data == $dcod)
                    $check = true; // Le test est bon.
                unset($data, $binIV, $hexIV, $binKey, $hexKey, $code, $dcod);
            }
        }
        unset($l, $a);
        return $check;
    }

    public function setSymetricAlgorithm($algo)
    {
        $algo = (string)$algo;
        if (sizeof($this->_symetricAlgorithmList) == 0) return false; // Vérifie si la liste des algorithmes n'est pas vide.
        if (!in_array($algo, $this->_symetricAlgorithmName)) return false;
        $this->_symetricAlgorithmName = $algo;
        $this->_genSymetricAlgorithmProperties();
        return true;
    }

    public function checkSymetricAlgorithm($algo)
    {
        if ($algo == '') return false;
        // A faire...
    }

    private function _genSymetricAlgorithmList()
    {
        //$this->_symetricAlgorithmList = array('aes-256-cbc', 'aes-256-ctr'); // Génère la liste des algorithmes disponibles. A revoir...
        $this->_symetricAlgorithmList = openssl_get_cipher_methods(true); // Génère la liste des algorithmes disponibles.
        $this->_symetricAlgorithmName = $this->_configuration->getOption('cryptoSymetricAlgorithm');
        $this->_genSymetricAlgorithmProperties();
    }

    private function _genSymetricAlgorithmProperties()
    {
        switch ($this->_symetricAlgorithmName) {
            case 'aes-256-ctr':
                $this->_symetricAlgorithm = 'aes';
                $this->_symetricKeyLength = 256;
                $this->_symetricAlgorithmMode = 'ctr';
                break;
            case 'aes-256-ecb':
                $this->_symetricAlgorithm = 'aes';
                $this->_symetricKeyLength = 256;
                $this->_symetricAlgorithmMode = 'ecb';
                break;
            case 'aes-256-cbc':
                $this->_symetricAlgorithm = 'aes';
                $this->_symetricKeyLength = 256;
                $this->_symetricAlgorithmMode = 'cbc';
                break;
            default:
                $this->_symetricAlgorithm = ' ';
                $this->_symetricKeyLength = 0;
                break;
        }
    }

    /**
     * Génère un IV nul pour l'algorythme symétrique.
     */
    private function _genSymetricAlgorithmNullIV()
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
     * @param string $iv
     * @return string
     */
    public function crypt($data, $key, $hexIV = '')
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
            $hexIV = $this->_genSymetricAlgorithmNullIV();
        }

        // Encode l'IV en binaire.
        $binIV = pack("H*", $hexIV);

        // Réalise le chiffrement.
        return openssl_encrypt($data, $this->_symetricAlgorithmName, $key, OPENSSL_RAW_DATA, $binIV);
    }

    /**
     * Déchiffrement de données avec algorithme symétrique.
     * @param string $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    public function decrypt($data, $key, $hexIV = '')
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
            $hexIV = $this->_genSymetricAlgorithmNullIV();
        }

        // Encode l'IV en binaire.
        $binIV = pack("H*", $hexIV);

        // Réalise le déchiffrement.
        return openssl_decrypt($data, $this->_symetricAlgorithmName, $key, OPENSSL_RAW_DATA, $binIV);
    }


    /*
	 * ------------------------------------------------------------------------------------------
	 * Gestion du chiffrement asymétrique.
	 */
    private $_asymetricAlgorithmList = array(), // Fonction de chiffrement asymétrique (càd avec clé publique/privée).
        $_asymetricAlgorithm,               // Fonction de chiffrement asymétrique (càd avec clé publique/privée).
        $_asymetricAlgorithmName,           // Fonction de chiffrement asymétrique (càd avec clé publique/privée).
        $_asymetricKeyLength;               // Taille d'une clé publique/privée.

    // Retourne l'algorithme de chiffrement asymétrique en cours d'utilisation.
    public function asymetricAlgorithm()
    {
        return $this->_asymetricAlgorithm;
    }

    public function asymetricAlgorithmName()
    {
        return $this->_asymetricAlgorithmName;
    }

    // Retourne la longueur de clé de l'algorithme de chiffrement asymétrique en cours d'utilisation.
    public function asymetricKeyLength()
    {
        return $this->_asymetricKeyLength;
    }

    // Vérifie le bon fonctionnement de l'algorithme de chiffrement asymétrique en cours d'utilisation.
    public function checkAsymetricFunction()
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

    public function setAsymetricAlgorithm($algo)
    {
        $algo = (string)$algo;
        if (sizeof($this->_asymetricAlgorithmList) == 0) return false; // Vérifie si la liste des algorithmes n'est pas vide.
        if (!in_array($algo, $this->_asymetricAlgorithmName)) return false;
        $this->_asymetricAlgorithmName = $algo;
        $this->_genAsymetricAlgorithmProperties();
        return true;
    }

    public function checkAsymetricAlgorithm($algo)
    {
        if ($algo == '') return false;
        // A faire...
    }

    private function _genAsymetricAlgorithmList()
    {
        // Génère la liste des algorithmes disponibles. A revoir...
        $this->_asymetricAlgorithmList = array('rsa1024', 'rsa2048', 'rsa4096', 'ecdsa');        // A revoir...
        $this->_asymetricAlgorithmName = $this->_configuration->getOption('cryptoAsymetricAlgorithm');
        $this->_genAsymetricAlgorithmProperties();
        /*
OPENSSL_KEYTYPE_RSA (entier)
OPENSSL_KEYTYPE_DSA (entier)
OPENSSL_KEYTYPE_DH (entier)
OPENSSL_KEYTYPE_EC (entier)
		 */
    }

    private function _genAsymetricAlgorithmProperties()
    {
        switch ($this->_asymetricAlgorithmName) {
            case 'rsa1024':
                $this->_asymetricAlgorithm = 'rsa';
                $this->_asymetricKeyLength = 1024;
                break;
            case 'rsa2048':
                $this->_asymetricAlgorithm = 'rsa';
                $this->_asymetricKeyLength = 2048;
                break;
            case 'rsa4096':
                $this->_asymetricAlgorithm = 'rsa';
                $this->_asymetricKeyLength = 4096;
                break;
            default:
                $this->_asymetricAlgorithm = ' ';
                $this->_asymetricKeyLength = 0;
                break;
        }
    }

    public function sign($hash, $privateKey, $privatePassword)
    {
        global $nebuleInstance;
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
        if ($ok !== false) {
            // Décode la signature en héxa.
            $signature = bin2hex($signatureBin);
            // Retourne la valeur en héxa de la signature.
            return $signature;
        }
        // Sinon retourne que ça s'est mal passé.
        $nebuleInstance->getMetrologyInstance()->addLog('ERROR crypto sign', Metrology::LOG_LEVEL_NORMAL); // Log
        return false;
    }

    public function verify($hash, $sign, $publicKey)
    {
        // Extrait la clé publique de l'entité signataire.
        $pubkeyid = openssl_pkey_get_public($publicKey);

        // Vérifie la présence et la cohérence de la clé publique.
        if ($pubkeyid === false) {
            return false;
        }

        // Encode la signature pour la vérification.
        $binsign = pack('H*', $sign);
        // Déchiffre la signature avec la clé publique.
        $okdec = openssl_public_decrypt($binsign, $decrypted, $pubkeyid, OPENSSL_PKCS1_PADDING);
        // Extrait la signature déchiffrée.
        $decrypted = substr(bin2hex($decrypted), -64, 64);                                        // @todo WARNING A faire pour le cas général.
        // Vérifie la signature.
        if ($okdec !== false && $decrypted == $hash) {
            return true;
        }
        //else
        return false;
    }

    public function cryptTo($data, $publicKey)
    {
        $code = '';
        // Extrait la clé privée déchiffrée.
        $pubkeyid = openssl_pkey_get_public($publicKey);
        // Vérifie la présence et la cohérence de la clé publique.
        if ($pubkeyid === false)
            return false;
        // Chiffre les données.
        $ok = openssl_public_encrypt($data, $code, $pubkeyid, OPENSSL_PKCS1_PADDING);
        // Nettoyage des variables.
        $data = null;
        $publicKey = null;
        unset($pubkeyid);
        // Si la fonction de chiffrement a bien fonctionnée.
        if ($ok !== false)
            return $code;
        // Sinon retourne que ça s'est mal passé.
        return false;
    }

    public function decryptTo($code, $privateKey, $privatePassword)
    {
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
    public function newPkey()
    {
        // Vérifie que l'algorithme est correcte.
        if ($this->_asymetricAlgorithm == '') return false;

        // Configuration de la génération.
        $config = array();
        switch ($this->_asymetricAlgorithm) {
            case 'rsa' :
                $config['private_key_type'] = OPENSSL_KEYTYPE_RSA;
                break;
            case 'dsa' :
                $config['private_key_type'] = OPENSSL_KEYTYPE_DSA;
                break;
            default    :
                return false;
                break;
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
    public function getPkeyPrivate($pkey, $password = '')
    {
        if ($password == '') {
            openssl_pkey_export($pkey, $privkey, null);
        } else {
            openssl_pkey_export($pkey, $privkey, $password);
        }
        return $privkey;
    }

    // Extrait une clé privée.
    public function getPrivateKey($privateKey, $password)
    {
        return openssl_pkey_get_private($privateKey, $password);
    }
}
