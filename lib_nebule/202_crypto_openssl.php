<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class CryptoOpenssl extends Crypto implements CryptoInterface
{
    const SESSION_SAVED_VARS = array();

    const TYPE = 'Openssl';

    const HASH_ALGORITHM = array(
        'sha1.128',
        'sha2.224',
        'sha2.256',
        'sha2.384',
        'sha2.512',
    );
    const TRANSLATE_HASH_ALGORITHM = array(
        'sha1.128' => 'sha1',
        'sha2.224' => 'sha224',
        'sha2.256' => 'sha256',
        'sha2.384' => 'sha384',
        'sha2.512' => 'sha512',
        '' => '',
    );
    const TEST_HASH_ALGORITHM = array(
        'value'    => 'Bienvenue dans le projet nebule.',
        'sha1.128' => 'd689bc73bbf35e6547e6de4b0ea79a5fd3b83ffa',
        'sha2.224' => '8ee809ef3ec56e4e31273e2ee232697683d260db72d543ce6db4ab64',
        'sha2.256' => '0b8dc4408e7ab1c81716ae978abe1f75d4bd3ea9a7b882b8da6afacdafc0e32b',
        'sha2.384' => 'fef7e57afdbf243a756eae37fa7c556bc71050f555209d78b29d2e8feef56e62ed92da5e291669b6262170cd4f0dd0ba',
        'sha2.512' => 'b9d7b17462c0e2657171975ee0bd37e8dc0cab5d6ebc6496864af2e261f16d35c16642898ba0af5174ad80bada202032c641595be0fc56e4d35599add72f8079',
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
        '' => '',
    );

    const ASYMMETRIC_ALGORITHM = array(
        'rsa.1024',
        'rsa.2048',
        'rsa.4096',
        'dsa.2048',
        'ec.256',
        //'ed25519.256',
    );
    const TRANSLATE_ASYMMETRIC_ALGORITHM = array(
        'rsa.1024' => 'rsa1024',
        'rsa.2048' => 'rsa2048',
        'rsa.4096' => 'rsa4096',
        'dsa.2048' => 'dsa2048',
        'ec.256' => 'ec',
        //'ed25519.256' => 'ed25519',
    );

    protected function _initialisation(): void {}

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getCryptoInstance()
     */
    public function getCryptoInstance(): CryptoInterface { return $this; }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getCryptoInstanceName()
     */
    public function getCryptoInstanceName(): string { return get_class($this); }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkFunction()
     */
    public function checkFunction(string $algo, int $type): bool {
        return match ($type) {
            Crypto::TYPE_HASH => $this->_checkHashFunction($algo),
            Crypto::TYPE_SYMMETRIC => $this->_checkSymmetricFunction($algo),
            Crypto::TYPE_ASYMMETRIC => $this->_checkAsymmetricFunction(),
            default => false,
        };
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkValidAlgorithm()
     */
    public function checkValidAlgorithm(string $algo, int $type): bool {
        return match ($type) {
            Crypto::TYPE_HASH => $this->_checkHashAlgorithm($algo),
            Crypto::TYPE_SYMMETRIC => $this->_checkSymmetricAlgorithm($algo),
            Crypto::TYPE_ASYMMETRIC => $this->_checkAsymmetricAlgorithm($algo),
            default => false,
        };
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getAlgorithmList()
     */
    public function getAlgorithmList(int $type): array {
        return match ($type) {
            Crypto::TYPE_HASH => self::HASH_ALGORITHM,
            Crypto::TYPE_SYMMETRIC => self::SYMMETRIC_ALGORITHM,
            Crypto::TYPE_ASYMMETRIC => self::ASYMMETRIC_ALGORITHM,
            default => array(),
        };
    }

    private function _getAlgorithmName(string $algo): string {
        $v = preg_split('/\./', $algo); // aes.256.ctr rsa.2048
        return $v[0];
    }

    private function _getAlgorithmSize(string $algo): int {
        $v = preg_split('/\./', $algo); // aes.256.ctr rsa.2048
        return (int)$v[1];
    }

    // --------------------------------------------------------------------------------
    // Validation methods

    /**
     * Validate that a string is a valid hexadecimal string.
     *
     * @param string $hex String to validate
     * @param string $paramName Name of the parameter (for error messages)
     * @return bool
     */
    private function _validateHexString(string $hex, string $paramName): bool
    {
        if ($hex === '') {
            return false;
        }

        // Check if it's a valid hex string (only 0-9, a-f, A-F)
        if (!ctype_xdigit($hex)) {
            $this->_metrologyInstance->addLog(
                "Invalid hex string for $paramName",
                Metrology::LOG_LEVEL_WARNING,
                __METHOD__,
                'val001'
            );
            return false;
        }

        // Check that length is even (each byte is 2 hex chars)
        if (strlen($hex) % 2 !== 0) {
            $this->_metrologyInstance->addLog(
                "Hex string for $paramName has odd length",
                Metrology::LOG_LEVEL_WARNING,
                __METHOD__,
                'val002'
            );
            return false;
        }

        return true;
    }

    /**
     * Validate that a symmetric key has the correct size for the algorithm.
     *
     * @param string $hexKey Hexadecimal key
     * @param string $algo Symmetric algorithm name (e.g., 'aes.256.cbc')
     * @return bool
     */
    private function _validateSymmetricKeySize(string $hexKey, string $algo): bool
    {
        if (!$this->_validateHexString($hexKey, 'key')) {
            return false;
        }

        // Get key size in bytes
        $keySizeBytes = strlen($hexKey) / 2;
        $keySizeBits = $keySizeBytes * 8;

        // Get algorithm name and required key size
        $algoName = $this->_getAlgorithmName($algo);
        $algoSize = $this->_getAlgorithmSize($algo);

        // Required key sizes for common algorithms
        $requiredSizes = [
            'aes' => [
                128 => 16,  // 128 bits = 16 bytes
                192 => 24,  // 192 bits = 24 bytes
                256 => 32,  // 256 bits = 32 bytes
            ],
        ];

        if (isset($requiredSizes[$algoName][$algoSize])) {
            $requiredBytes = $requiredSizes[$algoName][$algoSize];
            if ($keySizeBytes !== $requiredBytes) {
                $this->_metrologyInstance->addLog(
                    "Key size mismatch for $algo: expected {$requiredBytes} bytes ($algoSize bits), got {$keySizeBytes} bytes ({$keySizeBits} bits)",
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'key001'
                );
                return false;
            }
        } else {
            // For unknown algorithms, just check that key is not too small
            if ($keySizeBytes < 16) { // Minimum 128 bits
                $this->_metrologyInstance->addLog(
                    "Key size too small for $algo: {$keySizeBytes} bytes",
                    Metrology::LOG_LEVEL_WARNING,
                    __METHOD__,
                    'key002'
                );
                return false;
            }
        }

        return true;
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_STRONG): string {
        if ($size <= 0) {
            $this->_metrologyInstance->addLog(
                'Invalid size for random generation: ' . $size,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'rand001'
            );
            return '';
        }

        if ($quality == Crypto::RANDOM_STRONG) {
            return $this->_getStrongRandom($size);
        } else {
            // Pseudo-random should use software implementation
            // This is handled by the strategy pattern in the parent Crypto class
            $this->_metrologyInstance->addLog(
                'Strong random requested but quality is not RANDOM_STRONG',
                Metrology::LOG_LEVEL_WARNING,
                __METHOD__,
                'rand002'
            );
            return '';
        }
    }

    /**
     * Get robust random binary content.
     * Size is in octets.
     * If problem, return an empty string.
     * To save precious entropy, you have to use pseudo random in all case where you do not absolutely need strong random.
     *
     * @param int $size
     * @return string
     */
    private function _getStrongRandom(int $size = 32): string {
        if ($size <= 0) {
            $this->_metrologyInstance->addLog(
                'Invalid size for strong random generation',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'rand003'
            );
            return '';
        }

        try {
            $strong = false;
            $data = openssl_random_pseudo_bytes($size, $strong);

            if (!$strong || $data === false) {
                $this->_metrologyInstance->addLog(
                    'openssl_random_pseudo_bytes failed or not cryptographically strong',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'rand004'
                );
                // Fallback to a less secure method if available
                $data = random_bytes($size);
            }

            return $data;
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in random generation: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'rand005'
            );
            return '';
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getEntropy()
     */
    public function getEntropy(string &$data): float { return CryptoSoftware::getEntropyStatic($data); }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::hash()
     */
    public function hash(string $data, string $algo = ''): string {
        $algo = $this->_translateHashAlgorithm($algo);
        if ($algo == '')
            return '';
        return hash($algo, $data);
    }

    private function _checkHashFunction(string $algo): bool {
        if (!$this->_checkHashAlgorithm($algo))
            return false;
        $hash = $this->hash(self::TEST_HASH_ALGORITHM['value'], $algo);
        if (self::TEST_HASH_ALGORITHM[$algo] == $hash)
            return true;
        $this->_metrologyInstance->addLog('Error check hash ' . $algo . ' return ' . $hash, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f462ce6c');
        return false;
    }

    private function _checkHashAlgorithm(string $algo): bool {
        if (isset(self::TRANSLATE_HASH_ALGORITHM[$algo]))
            return true;
        $this->_metrologyInstance->addLog('Unsupported ' . $algo, Metrology::LOG_LEVEL_ERROR, __METHOD__, '965d71cf');
        return false;
    }

    private function _translateHashAlgorithm(string $name): string {
        if (isset(self::TRANSLATE_HASH_ALGORITHM[$name]))
            return self::TRANSLATE_HASH_ALGORITHM[$name];
        $this->_metrologyInstance->addLog('Invalid hash algorithm ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '43c10796');
        return '';
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encrypt()
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string {
        // Validate parameters
        if ($data === '') {
            $this->_metrologyInstance->addLog(
                'Empty data for encryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'enc001'
            );
            return '';
        }

        if ($hexKey === '') {
            $this->_metrologyInstance->addLog(
                'Empty key for encryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'enc002'
            );
            return '';
        }

        if (!$this->_checkSymmetricAlgorithm($algo)) {
            $this->_metrologyInstance->addLog(
                'Unsupported symmetric algorithm for encryption: ' . $algo,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'enc003'
            );
            return '';
        }

        // Validate hex key format
        if (!$this->_validateHexString($hexKey, 'key')) {
            $this->_metrologyInstance->addLog(
                'Invalid hex key format for encryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'enc004'
            );
            return '';
        }

        // Validate key size for algorithm
        if (!$this->_validateSymmetricKeySize($hexKey, $algo)) {
            $this->_metrologyInstance->addLog(
                'Invalid key size for algorithm: ' . $algo,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'enc005'
            );
            return '';
        }

        try {
            $method = $this->_translateSymmetricAlgorithm($algo);
            if ($method === '') {
                $this->_metrologyInstance->addLog(
                    'Failed to translate symmetric algorithm: ' . $algo,
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'enc006'
                );
                return '';
            }

            $binIV = $this->_getBinIV($hexIV, $algo);
            $binKey = pack("H*", $hexKey);

            if ($binKey === false) {
                $this->_metrologyInstance->addLog(
                    'Failed to pack hex key for encryption',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'enc007'
                );
                return '';
            }

            $result = openssl_encrypt($data, $method, $binKey, OPENSSL_RAW_DATA, $binIV);

            if ($result === false) {
                $this->_metrologyInstance->addLog(
                    'openssl_encrypt failed: ' . openssl_error_string(),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'enc008'
                );
                return '';
            }

            return $result;
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in encryption: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'enc009'
            );
            return '';
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decrypt()
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string {
        // Validate parameters
        if ($data === '') {
            $this->_metrologyInstance->addLog(
                'Empty data for decryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec001'
            );
            return '';
        }

        if ($hexKey === '') {
            $this->_metrologyInstance->addLog(
                'Empty key for decryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec002'
            );
            return '';
        }

        if (!$this->_checkSymmetricAlgorithm($algo)) {
            $this->_metrologyInstance->addLog(
                'Unsupported symmetric algorithm for decryption: ' . $algo,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec003'
            );
            return '';
        }

        // Validate hex key format
        if (!$this->_validateHexString($hexKey, 'key')) {
            $this->_metrologyInstance->addLog(
                'Invalid hex key format for decryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec004'
            );
            return '';
        }

        // Validate key size for algorithm
        if (!$this->_validateSymmetricKeySize($hexKey, $algo)) {
            $this->_metrologyInstance->addLog(
                'Invalid key size for algorithm: ' . $algo,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec005'
            );
            return '';
        }

        try {
            $method = $this->_translateSymmetricAlgorithm($algo);
            if ($method === '') {
                $this->_metrologyInstance->addLog(
                    'Failed to translate symmetric algorithm: ' . $algo,
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'dec006'
                );
                return '';
            }

            $binIV = $this->_getBinIV($hexIV, $algo);
            $binKey = pack("H*", $hexKey);

            if ($binKey === false) {
                $this->_metrologyInstance->addLog(
                    'Failed to pack hex key for decryption',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'dec007'
                );
                return '';
            }

            $result = openssl_decrypt($data, $method, $binKey, OPENSSL_RAW_DATA, $binIV);

            if ($result === false) {
                $this->_metrologyInstance->addLog(
                    'openssl_decrypt failed: ' . openssl_error_string(),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'dec008'
                );
                return '';
            }

            return $result;
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in decryption: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec009'
            );
            return '';
        }
    }

    private function _checkSymmetricFunction(string $algo): bool {
        $data = 'Bienvenue dans le projet nebule.';
        $hexKey = "8fdf208b4a79cef62f4e610ef7d409c110cb5d20b0148b9770cad5130106b6a1";
        $hexIV = $this->hash(date(DATE_ATOM) . microtime(false), 'sha1');
        $code = $this->encrypt($data, $algo, $hexKey, $hexIV);
        if ($code == '')
        {
            $this->_metrologyInstance->addLog('Error check symmetric ' . $algo . ' can not encode', Metrology::LOG_LEVEL_ERROR, __METHOD__, '3ab8726b');
            return false;
        }

        $decode = $this->decrypt($code, $algo, $hexKey, $hexIV);
        if ($decode == '')
        {
            $this->_metrologyInstance->addLog('Error check symmetric ' . $algo . ' can not decode ' . $code, Metrology::LOG_LEVEL_ERROR, __METHOD__, '2a6da5e1');
            return false;
        }

        return true;
    }

    private function _checkSymmetricAlgorithm(string $algo): bool {
        if (isset(self::TRANSLATE_SYMMETRIC_ALGORITHM[$algo]))
            return true;
        $this->_metrologyInstance->addLog('Unsupported ' . $algo, Metrology::LOG_LEVEL_ERROR, __METHOD__, '13fab565');
        return false;
    }

    private function _translateSymmetricAlgorithm(string $name): string {
        if (isset(self::TRANSLATE_SYMMETRIC_ALGORITHM[$name]))
            return self::TRANSLATE_SYMMETRIC_ALGORITHM[$name];
        $this->_metrologyInstance->addLog('Invalid symmetric algorithm ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '5f83d258');
        return '';
    }

    /**
     * Generate a secure random IV for cryptographic operations.
     *
     * @param int $size Size in bytes
     * @return string Binary IV
     */
    private function _generateRandomIV(int $size): string
    {
        try {
            $strong = false;
            $iv = openssl_random_pseudo_bytes($size, $strong);
            if ($strong && $iv !== false) {
                return $iv;
            }
            // Fallback to random_bytes if available
            if (function_exists('random_bytes')) {
                return random_bytes($size);
            }
            // Last fallback: use timestamp-based pseudo-random
            $this->_metrologyInstance->addLog(
                'Falling back to weak IV generation',
                Metrology::LOG_LEVEL_WARNING,
                __METHOD__,
                'iv001'
            );
            return pack('H*', substr(hash('sha256', (string)microtime(true) . mt_rand()), 0, $size * 2));
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Failed to generate IV: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'iv002'
            );
            // Absolute fallback: return zero-filled IV
            return str_repeat("\x00", $size);
        }
    }

    /**
     * Convert the IV on hexadecimal value as a binary value with max size accepted by the cryptographic algorithm.
     * If no IV is provided, generates a secure random IV.
     *
     * @param string $hexIV Hexadecimal IV (optional)
     * @param string $algo Symmetric algorithm name
     * @return string Binary IV
     */
    private function _getBinIV(string $hexIV, string $algo): string {
        $method = $this->_translateSymmetricAlgorithm($algo);
        if ($method === '') {
            $this->_metrologyInstance->addLog(
                'Invalid algorithm for IV generation: ' . $algo,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'iv003'
            );
            return '';
        }

        $maxIV = openssl_cipher_iv_length($method);

        if ($hexIV === '') {
            // Generate a secure random IV if none provided
            return $this->_generateRandomIV($maxIV);
        }

        // Validate hex IV format
        if (!ctype_xdigit($hexIV)) {
            $this->_metrologyInstance->addLog(
                'Invalid hex IV format',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'iv004'
            );
            // Generate a secure random IV as fallback
            return $this->_generateRandomIV($maxIV);
        }

        $binIV = pack("H*", $hexIV);

        // Truncate if IV is too long
        if (strlen($binIV) > $maxIV) {
            $binIV = substr($binIV, 0, $maxIV);
        }

        // Pad if IV is too short
        if (strlen($binIV) < $maxIV) {
            $remaining = $maxIV - strlen($binIV);
            $binIV .= $this->_generateRandomIV($remaining);
        }

        return $binIV;
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::sign()
     */
    public function sign(string $data, string $privateKey, string $privatePassword): string {
        if ($data === '') {
            $this->_metrologyInstance->addLog(
                'Empty data for signing',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'sign001'
            );
            return '';
        }

        if ($privateKey === '') {
            $this->_metrologyInstance->addLog(
                'Empty private key for signing',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'sign002'
            );
            return '';
        }

        try {
            $privateKeyBin = openssl_pkey_get_private($privateKey, $privatePassword);
            
            if ($privateKeyBin === false) {
                $this->_metrologyInstance->addLog(
                    'Unable to use private key: ' . openssl_error_string(),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'sign003'
                );
                return '';
            }

            // Determine the hash algorithm for signing
            $hashAlgo = OPENSSL_ALGO_SHA256;
            
            $success = openssl_sign($data, $signatureBin, $privateKeyBin, $hashAlgo);
            unset($privateKeyBin);

            if ($success === false) {
                $this->_metrologyInstance->addLog(
                    'openssl_sign failed: ' . openssl_error_string(),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'sign004'
                );
                return '';
            }

            return bin2hex($signatureBin);
            
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in signing: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'sign005'
            );
            return '';
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::verify()
     */
    public function verify(string $data, string $sign, string $publicKey, string $algo): bool {
        if ($data === '') {
            $this->_metrologyInstance->addLog(
                'Empty data for verification',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'ver001'
            );
            return false;
        }

        if ($sign === '') {
            $this->_metrologyInstance->addLog(
                'Empty signature for verification',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'ver002'
            );
            return false;
        }

        if ($publicKey === '') {
            $this->_metrologyInstance->addLog(
                'Empty public key for verification',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'ver003'
            );
            return false;
        }

        if ($algo === '') {
            $this->_metrologyInstance->addLog(
                'Empty algorithm for verification',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'ver004'
            );
            return false;
        }

        try {
            $publicKeyBin = openssl_pkey_get_public($publicKey);

            if ($publicKeyBin === false) {
                $error = openssl_error_string();
                $this->_metrologyInstance->addLog(
                    'Unable to use public key: ' . ($error !== false ? $error : 'unknown error'),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'ver005'
                );
                return false;
            }

            // Convert hex signature to binary
            $signBin = pack('H*', $sign);
            if ($signBin === false) {
                $this->_metrologyInstance->addLog(
                    'Invalid hex signature format',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'ver006'
                );
                unset($publicKeyBin);
                return false;
            }

            // Map algorithm to OpenSSL constant for openssl_verify
            $opensslAlgo = $this->_translateHashAlgorithmToOpenssl($algo);

            // Try method 1: openssl_verify (standard method)
            // This works when the signature was created by signing the raw data directly
            if ($opensslAlgo !== null) {
                $result = openssl_verify($data, $signBin, $publicKeyBin, $opensslAlgo);

                if ($result === 1) {
                    unset($publicKeyBin);
                    return true;
                } elseif ($result === 0) {
                    // Signature doesn't match with openssl_verify
                    // This might be because the signature was created by signing the hash of the data
                    // Clear error queue and try alternative methods
                    while (openssl_error_string() !== false) {
                        // Clear OpenSSL error queue
                    }
                } else {
                    $this->_metrologyInstance->addLog(
                        'openssl_verify error: ' . openssl_error_string(),
                        Metrology::LOG_LEVEL_DEBUG,
                        __METHOD__,
                        'ver009'
                    );
                }
            }

            // Try method 2: Legacy method from original DEBUG_CRYPTO_SYSTEM code
            // This handles signatures created with openssl_private_encrypt where the data might be truncated
            $decrypted = '';
            $result = openssl_public_decrypt($signBin, $decrypted, $publicKeyBin, OPENSSL_PKCS1_PADDING);

            if ($result !== false) {
                // Get key size for potential truncation (from original code)
                $keyDetails = openssl_pkey_get_details($publicKeyBin);
                $keySizeBytes = (int)($keyDetails['bits'] / 8);
                $maxSize = $keySizeBytes - 11; // For PKCS#1 padding
                
                // If data is too long for this key size, truncate it for comparison (original logic)
                $comparisonData = $data;
                if (strlen($data) > $maxSize) {
                    $comparisonData = substr($data, 0, $maxSize);
                }
                
                // Convert decrypted data to hex and extract the last part for comparison (original logic)
                $decryptedHex = bin2hex($decrypted);
                if (strlen($comparisonData) <= strlen($decryptedHex)) {
                    $extracted = substr($decryptedHex, -strlen($comparisonData), strlen($comparisonData));
                    if ($extracted === $comparisonData) {
                        unset($publicKeyBin);
                        return true;
                    }
                }
                
                // Also try direct binary comparison
                if (hash_equals($decrypted, $data)) {
                    unset($publicKeyBin);
                    return true;
                }
                
                // Also try direct hex comparison
                if (hash_equals(bin2hex($decrypted), $data)) {
                    unset($publicKeyBin);
                    return true;
                }
            } else {
                $this->_metrologyInstance->addLog(
                    'openssl_public_decrypt failed: ' . openssl_error_string(),
                    Metrology::LOG_LEVEL_DEBUG,
                    __METHOD__,
                    'ver011'
                );
            }

            // Try method 3: For signatures created with openssl dgst -sign which signs the hash of data
            // If the data parameter is raw data, hash it and compare with decrypted signature
            $translatedAlgo = $this->_translateHashAlgorithm($algo);
            if ($translatedAlgo !== '') {
                $dataHash = hash($translatedAlgo, $data, true);
                if ($dataHash !== false) {
                    // Try openssl_verify with the hash (in case signature was created by signing the hash)
                    if ($opensslAlgo !== null) {
                        $result = openssl_verify($dataHash, $signBin, $publicKeyBin, $opensslAlgo);
                        if ($result === 1) {
                            unset($publicKeyBin);
                            return true;
                        }
                    }

                    // Try decrypting and comparing with hash
                    $decrypted3 = '';
                    $result3 = openssl_public_decrypt($signBin, $decrypted3, $publicKeyBin, OPENSSL_PKCS1_PADDING);
                    if ($result3 !== false) {
                        // Direct comparison with hash
                        if (hash_equals($decrypted3, $dataHash)) {
                            unset($publicKeyBin);
                            return true;
                        }
                        
                        // Try extracting from hex representation (similar to original DEBUG_CRYPTO_SYSTEM logic)
                        $decryptedHex3 = bin2hex($decrypted3);
                        $dataHashHex = bin2hex($dataHash);
                        if (strlen($dataHashHex) <= strlen($decryptedHex3)) {
                            $extracted3 = substr($decryptedHex3, -strlen($dataHashHex), strlen($dataHashHex));
                            if ($extracted3 === $dataHashHex) {
                                unset($publicKeyBin);
                                return true;
                            }
                        }
                        
                        // Also try direct hex comparison
                        if (hash_equals(bin2hex($decrypted3), $dataHashHex)) {
                            unset($publicKeyBin);
                            return true;
                        }
                    }
                } else {
                    $this->_metrologyInstance->addLog(
                        'Failed to hash data for legacy verification: algorithm ' . $algo,
                        Metrology::LOG_LEVEL_DEBUG,
                        __METHOD__,
                        'ver013'
                    );
                }
            } else {
                $this->_metrologyInstance->addLog(
                    'Unsupported hash algorithm for legacy verification: ' . $algo,
                    Metrology::LOG_LEVEL_DEBUG,
                    __METHOD__,
                    'ver014'
                );
            }

            unset($publicKeyBin);
            
            $this->_metrologyInstance->addLog(
                'Signature verification failed for all methods',
                Metrology::LOG_LEVEL_WARNING,
                __METHOD__,
                'ver012'
            );
            return false;
            
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in verification: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'ver010'
            );
            return false;
        }
    }

    /**
     * Translate hash algorithm name to OpenSSL constant.
     *
     * @param string $algo Hash algorithm name (e.g., 'sha2.256')
     * @return int|null OpenSSL constant or null if not supported
     */
    private function _translateHashAlgorithmToOpenssl(string $algo): ?int
    {
        switch ($algo) {
            case 'sha1.128':
            case 'sha1':
                return OPENSSL_ALGO_SHA1;
            case 'sha2.224':
            case 'sha224':
                return OPENSSL_ALGO_SHA224;
            case 'sha2.256':
            case 'sha256':
                return OPENSSL_ALGO_SHA256;
            case 'sha2.384':
            case 'sha384':
                return OPENSSL_ALGO_SHA384;
            case 'sha2.512':
            case 'sha512':
                return OPENSSL_ALGO_SHA512;
            default:
                return null;
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encryptTo()
     */
    public function encryptTo(string $data, ?string $publicKey): string {
        if ($data === '') {
            $this->_metrologyInstance->addLog(
                'Empty data for asymmetric encryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'encTo001'
            );
            return '';
        }

        if ($publicKey === null) {
            $this->_metrologyInstance->addLog(
                'Null public key for asymmetric encryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'encTo002'
            );
            return '';
        }

        try {
            $ressource = openssl_pkey_get_public($publicKey);

            if ($ressource === false) {
                $error = openssl_error_string();
                $this->_metrologyInstance->addLog(
                    'Unable to load public key: ' . ($error !== false ? $error : 'unknown error'),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'encTo003'
                );
                return '';
            }

            $code = '';
            $result = openssl_public_encrypt($data, $code, $ressource, OPENSSL_PKCS1_PADDING);

            if ($result === false) {
                $this->_metrologyInstance->addLog(
                    'openssl_public_encrypt failed: ' . openssl_error_string(),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'encTo004'
                );
                return '';
            }

            return $code;
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in asymmetric encryption: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'encTo005'
            );
            return '';
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decryptTo()
     */
    public function decryptTo(string $code, ?string $privateKey, ?string $password): string {
        if ($code === '') {
            $this->_metrologyInstance->addLog(
                'Empty data for asymmetric decryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'decTo001'
            );
            return '';
        }

        if ($privateKey === null) {
            $this->_metrologyInstance->addLog(
                'Null private key for asymmetric decryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'decTo002'
            );
            return '';
        }

        if ($password === null) {
            $this->_metrologyInstance->addLog(
                'Null password for asymmetric decryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'decTo003'
            );
            return '';
        }

        try {
            $ressource = openssl_pkey_get_private($privateKey, $password);

            if ($ressource === false) {
                $error = openssl_error_string();
                $this->_metrologyInstance->addLog(
                    'Unable to load private key: ' . ($error !== false ? $error : 'unknown error'),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'decTo004'
                );
                return '';
            }

            $data = '';
            $result = openssl_private_decrypt($code, $data, $ressource, OPENSSL_PKCS1_PADDING);

            if ($result === false) {
                $this->_metrologyInstance->addLog(
                    'openssl_private_decrypt failed: ' . openssl_error_string(),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'decTo005'
                );
                return '';
            }

            return $data;
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in asymmetric decryption: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'decTo006'
            );
            return '';
        }
    }

    /**
     * {@inheritDoc}
     * @param string $password
     * @param string $algo
     * @param int    $size
     * @see CryptoInterface::newAsymmetricKeys()
     */
    public function newAsymmetricKeys(string $password = '', string $algo = '', int $size = 2048): array {
        if ($size <= 0) {
            $this->_metrologyInstance->addLog(
                'Invalid key size for asymmetric key generation: ' . $size,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'keyGen001'
            );
            return array();
        }

        // Build algorithm identifier
        $algoKey = $algo !== '' ? $algo . '.' . $size : '';
        
        if (!$this->_checkAsymmetricAlgorithm($algoKey)) {
            $this->_metrologyInstance->addLog(
                'Unsupported asymmetric algorithm: ' . $algoKey,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'keyGen002'
            );
            return array();
        }

        try {
            // Get hash algorithm for key generation
            $hashAlgo = $this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm');
            $translatedHashAlgo = $this->_translateHashAlgorithm($hashAlgo);

            $config = array(
                'digest_alg' => $translatedHashAlgo,
            );

            // Configure key type and parameters based on algorithm
            switch ($algo) {
                case 'rsa':
                    $config['private_key_type'] = OPENSSL_KEYTYPE_RSA;
                    $config['private_key_bits'] = $size;
                    break;
                case 'dsa':
                    $config['private_key_type'] = OPENSSL_KEYTYPE_DSA;
                    $config['private_key_bits'] = $size;
                    break;
                case 'dh':
                    $config['private_key_type'] = OPENSSL_KEYTYPE_DH;
                    break;
                case 'ec':
                    $config['private_key_type'] = OPENSSL_KEYTYPE_EC;
                    $config['curve_name'] = 'prime256v1';
                    break;
                default:
                    $this->_metrologyInstance->addLog(
                        'Unknown asymmetric algorithm: ' . $algo,
                        Metrology::LOG_LEVEL_ERROR,
                        __METHOD__,
                        'keyGen003'
                    );
                    return array();
            }

            // Generate key pair
            $pkey = openssl_pkey_new($config);
            if ($pkey === false) {
                $error = openssl_error_string();
                $this->_metrologyInstance->addLog(
                    'Failed to generate key pair: ' . ($error !== false ? $error : 'unknown error'),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'keyGen004'
                );
                return array();
            }

            // Get public key details
            $pkeyDetail = openssl_pkey_get_details($pkey);
            if ($pkeyDetail === false) {
                $error = openssl_error_string();
                $this->_metrologyInstance->addLog(
                    'Failed to get key details: ' . ($error !== false ? $error : 'unknown error'),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'keyGen005'
                );
                unset($pkey);
                return array();
            }

            // Export private key with optional password
            $privateKey = '';
            if (openssl_pkey_export($pkey, $privateKey, $password) !== true) {
                $error = openssl_error_string();
                $this->_metrologyInstance->addLog(
                    'Failed to export private key: ' . ($error !== false ? $error : 'unknown error'),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'keyGen006'
                );
                unset($pkey);
                return array();
            }

            unset($pkey);

            return array(
                'public' => $pkeyDetail['key'],
                'private' => $privateKey,
            );
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in key generation: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'keyGen007'
            );
            return array();
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkPrivateKeyPassword()
     */
    public function checkPrivateKeyPassword(?string $privateKey, ?string $password): bool {
        if ($privateKey === null) {
            $this->_metrologyInstance->addLog(
                'Null private key for password check',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'pwd001'
            );
            return false;
        }

        if ($password === null) {
            $this->_metrologyInstance->addLog(
                'Null password for password check',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'pwd002'
            );
            return false;
        }

        try {
            $pkey = openssl_pkey_get_private($privateKey, $password);

            if ($pkey === false) {
                $error = openssl_error_string();
                $this->_metrologyInstance->addLog(
                    'Invalid private key or password: ' . ($error !== false ? $error : 'unknown error'),
                    Metrology::LOG_LEVEL_WARNING,
                    __METHOD__,
                    'pwd003'
                );
                return false;
            }

            unset($pkey);
            return true;
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in password check: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'pwd004'
            );
            return false;
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::changePrivateKeyPassword()
     */
    public function changePrivateKeyPassword(?string $privateKey, ?string $oldPassword, ?string $newPassword): string {
        if ($privateKey === null) {
            $this->_metrologyInstance->addLog(
                'Null private key for password change',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'pwdChg001'
            );
            return '';
        }

        if ($oldPassword === null) {
            $this->_metrologyInstance->addLog(
                'Null old password for password change',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'pwdChg002'
            );
            return '';
        }

        if ($newPassword === null) {
            $this->_metrologyInstance->addLog(
                'Null new password for password change',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'pwdChg003'
            );
            return '';
        }

        try {
            $pkey = openssl_pkey_get_private($privateKey, $oldPassword);

            if ($pkey === false) {
                $error = openssl_error_string();
                $this->_metrologyInstance->addLog(
                    'Invalid private key or old password: ' . ($error !== false ? $error : 'unknown error'),
                    Metrology::LOG_LEVEL_WARNING,
                    __METHOD__,
                    'pwdChg004'
                );
                return '';
            }

            if (openssl_pkey_export($pkey, $privateKey, $newPassword) !== true) {
                $error = openssl_error_string();
                $this->_metrologyInstance->addLog(
                    'Failed to export private key with new password: ' . ($error !== false ? $error : 'unknown error'),
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'pwdChg005'
                );
                unset($pkey);
                return '';
            }

            unset($pkey);
            return $privateKey;
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in password change: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'pwdChg006'
            );
            return '';
        }
    }

    private function _checkAsymmetricFunction(): bool {
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
        if ($this->verify($hashData, $signed, $public_pem, 'sha2.256'))
            return true;
        $this->_metrologyInstance->addLog('Error check asymmetric ' . $hashData . ' can not verify ' . $signed, Metrology::LOG_LEVEL_ERROR, __METHOD__, '3ab8726b');
        return false;
    }

    private function _checkAsymmetricAlgorithm(string $algo): bool {
        if (isset(self::TRANSLATE_ASYMMETRIC_ALGORITHM[$algo]))
            return true;
        $this->_metrologyInstance->addLog('Unsupported ' . $algo, Metrology::LOG_LEVEL_ERROR, __METHOD__, '2a04d29d');
        return false;
    }

    /*private function _translateAsymmetricAlgorithm(string $name): string {
        if (isset(self::TRANSLATE_ASYMMETRIC_ALGORITHM[$name]))
            return self::TRANSLATE_ASYMMETRIC_ALGORITHM[$name];
        $this->_metrology->addLog('Invalid asymmetric algorithm ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__, '2b4c9b6b');
        return '';
    }*/
}
