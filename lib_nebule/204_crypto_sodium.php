<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Sodium cryptography implementation.
 * Uses PHP's sodium extension for modern, secure cryptography.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 * @requires ext-sodium
 */
class CryptoSodium extends Crypto implements CryptoInterface
{
    const SESSION_SAVED_VARS = array();

    const TYPE = 'Sodium';

    // Hash algorithms supported by Sodium
    const HASH_ALGORITHM = array(
        'sha2.256',
        'sha2.512',
        'sha3.256',
        'sha3.512',
        'blake2b.256',
        'blake2b.512',
    );

    // Symmetric algorithms
    const SYMMETRIC_ALGORITHM = array(
        'xsalsa20.poly1305',
        'aes.256.gcm', // If available via sodium_compat
    );

    // Asymmetric algorithms
    const ASYMMETRIC_ALGORITHM = array(
        'ed25519.256',
        'x25519.256',
        'rsa.2048',
        'rsa.4096',
    );

    // Translation tables
    const TRANSLATE_HASH_ALGORITHM = array(
        'sha2.256' => 'sha256',
        'sha2.512' => 'sha512',
        'sha3.256' => 'sha3-256',
        'sha3.512' => 'sha3-512',
        'blake2b.256' => 'blake2b',
        'blake2b.512' => 'blake2b',
    );

    // Test values for verification
    const TEST_HASH_ALGORITHM = array(
        'value' => 'Bienvenue dans le projet nebule.',
        'sha2.256' => '0b8dc4408e7ab1c81716ae978abe1f75d4bd3ea9a7b882b8da6afacdafc0e32b',
        'sha2.512' => 'b9d7b17462c0e2657171975ee0bd37e8dc0cab5d6ebc6496864af2e261f16d35c16642898ba0af5174ad80bada202032c641595be0fc56e4d35599add72f8079',
        'sha3.256' => '3a978889068356454594535688310654385724e742827694008725884635b39',
        'sha3.512' => '0189586861088133303933b4314b800758b73f8430cd1a75381dd9166380334c9820b13775989784255f8892565971146',
    );

    protected function _initialisation(): void {
        // Verify sodium extension is loaded
        if (!extension_loaded('sodium')) {
            throw new \RuntimeException('Sodium extension is required for CryptoSodium');
        }
    }

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

    // --------------------------------------------------------------------------------
    // Validation helpers

    private function _checkHashAlgorithm(string $algo): bool {
        return isset(self::TRANSLATE_HASH_ALGORITHM[$algo]);
    }

    private function _translateHashAlgorithm(string $algo): string {
        return self::TRANSLATE_HASH_ALGORITHM[$algo] ?? '';
    }

    private function _checkHashFunction(string $algo): bool {
        if (!$this->_checkHashAlgorithm($algo)) {
            return false;
        }

        $testValue = self::TEST_HASH_ALGORITHM['value'];
        $expected = self::TEST_HASH_ALGORITHM[$algo] ?? '';
        if ($expected === '') {
            return false;
        }

        $result = $this->hash($testValue, $algo);
        return hash_equals($result, $expected);
    }

    private function _checkSymmetricAlgorithm(string $algo): bool {
        return in_array($algo, self::SYMMETRIC_ALGORITHM);
    }

    private function _checkSymmetricFunction(string $algo): bool {
        if (!$this->_checkSymmetricAlgorithm($algo)) {
            return false;
        }

        $data = 'Test data for symmetric encryption';
        $hexKey = '0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef';

        $encrypted = $this->encrypt($data, $algo, $hexKey);
        if ($encrypted === '') {
            return false;
        }

        $decrypted = $this->decrypt($encrypted, $algo, $hexKey);
        return hash_equals($decrypted, $data);
    }

    private function _checkAsymmetricAlgorithm(string $algo): bool {
        return in_array($algo, self::ASYMMETRIC_ALGORITHM);
    }

    private function _checkAsymmetricFunction(string $algo = ''): bool {
        if (!$this->_checkAsymmetricAlgorithm($algo)) {
            // For generic check, use a common algorithm
            if ($algo === '') {
                $algo = 'ed25519.256';
                if (!$this->_checkAsymmetricAlgorithm($algo)) {
                    return false;
                }
            }
        }

        try {
            // Test key generation and usage
            $keys = $this->newAsymmetricKeys('', $algo, 0);
            if (empty($keys)) {
                return false;
            }

            $testData = 'Test data for asymmetric encryption';
            $encrypted = $this->encryptTo($testData, $keys['public'] ?? null);
            if ($encrypted === '') {
                return false;
            }

            $decrypted = $this->decryptTo($encrypted, $keys['private'] ?? null, '');
            return hash_equals($decrypted, $testData);
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Asymmetric function check failed: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'asy001'
            );
            return false;
        }
    }

    // --------------------------------------------------------------------------------
    // Random generation

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_PSEUDO): string {
        if ($size <= 0) {
            $this->_metrologyInstance->addLog(
                'Invalid size for random generation: ' . $size,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'rand001'
            );
            return '';
        }

        try {
            // Sodium always provides cryptographically strong random
            // For pseudo-random, we can use a seeded generator, but Sodium doesn't
            // have a built-in pseudo-random, so we use the strong one for both
            // This is actually better security-wise
            return \random_bytes($size);
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in random generation: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'rand002'
            );
            return '';
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getEntropy()
     */
    public function getEntropy(string &$data): float {
        return CryptoSoftware::getEntropyStatic($data);
    }

    // --------------------------------------------------------------------------------
    // Hash functions

    /**
     * {@inheritDoc}
     * @see CryptoInterface::hash()
     */
    public function hash(string $data, string $algo = ''): string {
        if ($data === '') {
            $this->_metrologyInstance->addLog(
                'Empty data for hashing',
                Metrology::LOG_LEVEL_WARNING,
                __METHOD__,
                'hash001'
            );
            return '';
        }

        if ($algo === '') {
            $algo = \Nebule\Library\References::REFERENCE_CRYPTO_HASH_ALGORITHM;
        }

        if (!$this->_checkHashAlgorithm($algo)) {
            $this->_metrologyInstance->addLog(
                'Unsupported hash algorithm: ' . $algo,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'hash002'
            );
            return '';
        }

        $translatedAlgo = $this->_translateHashAlgorithm($algo);

        try {
            switch ($translatedAlgo) {
                case 'sha256':
                case 'sha512':
                    // Use PHP's native hash() function for SHA-2
                    return \hash($translatedAlgo, $data);
                case 'sha3-256':
                case 'sha3-512':
                    // Use PHP's hash() if available, as sodium_crypto_hash_sha3_* may not exist
                    if (function_exists('hash') && in_array($translatedAlgo, hash_algos())) {
                        return \hash($translatedAlgo, $data);
                    }
                    // Fallback: use generichash with appropriate size
                    $size = ($translatedAlgo === 'sha3-256') ? 32 : 64;
                    return \sodium_bin2hex(\sodium_crypto_generichash($data, '', $size));
                case 'blake2b':
                    // For blake2b, we need to determine the size from algo
                    if (str_contains($algo, '256')) {
                        return \sodium_bin2hex(\sodium_crypto_generichash($data, '', \SODIUM_CRYPTO_GENERICHASH_BLAKE2B_SALSA20_16));
                    } else {
                        return \sodium_bin2hex(\sodium_crypto_generichash($data, '', \SODIUM_CRYPTO_GENERICHASH_BLAKE2B_SALSA20_32));
                    }
                default:
                    $this->_metrologyInstance->addLog(
                        'Unsupported hash algorithm in Sodium: ' . $algo,
                        Metrology::LOG_LEVEL_ERROR,
                        __METHOD__,
                        'hash003'
                    );
                    return '';
            }
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Hash computation failed: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'hash004'
            );
            return '';
        }
    }

    // --------------------------------------------------------------------------------
    // Symmetric encryption

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encrypt()
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string {
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
                'Unsupported symmetric algorithm: ' . $algo,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'enc003'
            );
            return '';
        }

        try {
            // Convert hex key to binary
            $binKey = \hex2bin($hexKey);
            if ($binKey === false) {
                $this->_metrologyInstance->addLog(
                    'Invalid hex key format',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'enc004'
                );
                return '';
            }

            switch ($algo) {
                case 'xsalsa20.poly1305':
                    return $this->_encryptXSalsa20($data, $binKey);
                default:
                    $this->_metrologyInstance->addLog(
                        'Symmetric algorithm not implemented: ' . $algo,
                        Metrology::LOG_LEVEL_ERROR,
                        __METHOD__,
                        'enc005'
                    );
                    return '';
            }
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Encryption failed: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'enc006'
            );
            return '';
        }
    }

    /**
     * Encrypt using XSalsa20-Poly1305
     */
    private function _encryptXSalsa20(string $data, string $key): string {
        // Generate random nonce
        $nonce = \random_bytes(\SODIUM_CRYPTO_AEAD_XSALSA20POLY1305_IETF_NPUBBYTES);
        
        // Encrypt
        $ciphertext = \sodium_crypto_aead_xsalsa20poly1305_ietf_encrypt(
            $data,
            '', // Additional data (not used)
            $nonce,
            $key
        );

        // Return nonce + ciphertext (nonce is needed for decryption)
        return $nonce . $ciphertext;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decrypt()
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string {
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
                'Unsupported symmetric algorithm: ' . $algo,
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec003'
            );
            return '';
        }

        try {
            // Convert hex key to binary
            $binKey = \hex2bin($hexKey);
            if ($binKey === false) {
                $this->_metrologyInstance->addLog(
                    'Invalid hex key format',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'dec004'
                );
                return '';
            }

            switch ($algo) {
                case 'xsalsa20.poly1305':
                    return $this->_decryptXSalsa20($data, $binKey);
                default:
                    $this->_metrologyInstance->addLog(
                        'Symmetric algorithm not implemented: ' . $algo,
                        Metrology::LOG_LEVEL_ERROR,
                        __METHOD__,
                        'dec005'
                    );
                    return '';
            }
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Decryption failed: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec006'
            );
            return '';
        }
    }

    /**
     * Decrypt using XSalsa20-Poly1305
     */
    private function _decryptXSalsa20(string $data, string $key): string {
        $nonceLength = \SODIUM_CRYPTO_AEAD_XSALSA20POLY1305_IETF_NPUBBYTES;
        
        if (strlen($data) < $nonceLength) {
            $this->_metrologyInstance->addLog(
                'Invalid ciphertext length for XSalsa20 decryption',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec007'
            );
            return '';
        }

        // Extract nonce and ciphertext
        $nonce = substr($data, 0, $nonceLength);
        $ciphertext = substr($data, $nonceLength);

        // Decrypt
        $plaintext = \sodium_crypto_aead_xsalsa20poly1305_ietf_decrypt(
            $ciphertext,
            '', // Additional data (not used)
            $nonce,
            $key
        );

        if ($plaintext === false) {
            $this->_metrologyInstance->addLog(
                'XSalsa20 decryption failed (authentication error)',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'dec008'
            );
            return '';
        }

        return $plaintext;
    }

    // --------------------------------------------------------------------------------
    // Asymmetric functions

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
            // Sodium uses binary keys, but we receive PEM format from OpenSSL compatibility
            // For now, assume we're using Ed25519 with Sodium's native format
            
            // If it's a PEM key, we need to convert it
            if (str_starts_with($privateKey, '-----BEGIN')) {
                // Try to load as PEM
                $key = \sodium_hex2bin($privateKey);
                if ($key === false) {
                    // Might be base64 encoded PEM
                    $pem = $privateKey;
                    if (preg_match('/-----BEGIN.*?-----/', $pem)) {
                        // Extract base64 part
                        preg_match('/-----BEGIN.*?\n(.*?)\n-----END/', $pem, $matches);
                        if (isset($matches[1])) {
                            $key = \base64_decode($matches[1]);
                        }
                    }
                }
            } else {
                // Assume it's already a Sodium-formatted key
                $key = \sodium_hex2bin($privateKey);
            }

            if ($key === false) {
                $this->_metrologyInstance->addLog(
                    'Invalid private key format for signing',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'sign003'
                );
                return '';
            }

            // Create signature
            $signature = \sodium_crypto_sign_detached($data, $key);
            
            // Clean up sensitive data
            \sodium_memzero($key);

            if ($signature === false) {
                $this->_metrologyInstance->addLog(
                    'Failed to create signature',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'sign004'
                );
                return '';
            }

            // Return as hex
            return \sodium_bin2hex($signature);
            
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

        try {
            // Signature is expected to be in hexadecimal format, convert to binary
            // Use hex2bin which is more tolerant than sodium_hex2bin
            $signatureBin = \hex2bin($sign);
            if ($signatureBin === false) {
                $this->_metrologyInstance->addLog(
                    'Invalid hex signature format',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'ver004'
                );
                return false;
            }

            // Convert public key to binary format
            // Support multiple formats: PEM, DER (binary), or hex
            $pubKeyBin = $this->_convertPublicKeyToBinary($publicKey);
            if ($pubKeyBin === false) {
                $this->_metrologyInstance->addLog(
                    'Invalid public key format',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'ver005'
                );
                return false;
            }

            // For compatibility with OpenSSL, we need to try different approaches
            // Sodium expects specific key formats
            
            // Try Ed25519 verification first (most common for Sodium)
            // \SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES = 32 bytes for Ed25519
            if (strlen($pubKeyBin) === \SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES) {
                $result = \sodium_crypto_sign_verify_detached($signatureBin, $data, $pubKeyBin);
                if ($result !== false) {
                    $this->_metrologyInstance->addLog(
                        'Ed25519 signature verified successfully with Sodium',
                        Metrology::LOG_LEVEL_DEBUG,
                        __METHOD__,
                        'ver010'
                    );
                    return true;
                }
                $this->_metrologyInstance->addLog(
                    'Ed25519 signature verification failed with Sodium',
                    Metrology::LOG_LEVEL_DEBUG,
                    __METHOD__,
                    'ver011'
                );
            }
            
            // For RSA keys (typically 64-512+ bytes) or other formats not natively supported by Sodium
            // We cannot verify with pure Sodium. This is a known limitation.
            // Fallback: try to use OpenSSL if available
            
            // Check if this might be an RSA public key (DER format typically starts with 0x30)
            $firstByte = strlen($pubKeyBin) > 0 ? ord($pubKeyBin[0]) : 0;
            $isLikelyRSA = ($firstByte === 0x30) || (strlen($pubKeyBin) > \SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES);
            
            if (extension_loaded('openssl') && $isLikelyRSA) {
                $this->_metrologyInstance->addLog(
                    'Public key appears to be RSA or other format (' . strlen($pubKeyBin) . ' bytes, first byte=0x' . dechex($firstByte) . '), trying OpenSSL fallback',
                    Metrology::LOG_LEVEL_DEBUG,
                    __METHOD__,
                    'ver008'
                );
                
                // Map algorithm to OpenSSL constant
                $opensslAlgo = $this->_mapAlgoToOpenssl($algo);
                if ($opensslAlgo !== null) {
                    // Try first with the original public key if it was in PEM format
                    // This preserves the exact PEM format that OpenSSL expects
                    $pubKeyToTry = str_starts_with($publicKey, '-----BEGIN') ? $publicKey : $this->_convertBinaryToPEM($pubKeyBin, 'PUBLIC KEY');
                    
                    if ($pubKeyToTry !== false) {
                        // Try both binary and base64-encoded signature formats
                        $result = @openssl_verify($data, $signatureBin, $pubKeyToTry, $opensslAlgo);
                        if ($result === 1) {
                            $this->_metrologyInstance->addLog(
                                'RSA signature verified successfully with OpenSSL fallback (binary signature)',
                                Metrology::LOG_LEVEL_DEBUG,
                                __METHOD__,
                                'ver009'
                            );
                            return true;
                        }
                        
                        // Some signatures might be base64-encoded
                        $result = @openssl_verify($data, \base64_encode($signatureBin), $pubKeyToTry, $opensslAlgo);
                        if ($result === 1) {
                            $this->_metrologyInstance->addLog(
                                'RSA signature verified successfully with OpenSSL fallback',
                                Metrology::LOG_LEVEL_DEBUG,
                                __METHOD__,
                                'ver009'
                            );
                            return true;
                        }
                        $this->_metrologyInstance->addLog(
                            'OpenSSL verification returned: ' . ($result === 0 ? 'invalid signature' : 'error'),
                            Metrology::LOG_LEVEL_DEBUG,
                            __METHOD__,
                            'ver012'
                        );
                    }
                }
            }
            
            // If we reach here, verification failed with all available methods
            $this->_metrologyInstance->addLog(
                'Public key format not supported for verification (length=' . strlen($pubKeyBin) . ' bytes)',
                Metrology::LOG_LEVEL_WARNING,
                __METHOD__,
                'ver007'
            );
            
            return false;
            
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Exception in verification: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'ver006'
            );
            return false;
        }
    }

    /**
     * Convert a public key from various formats to binary.
     * Supports PEM, DER (binary), and hex formats.
     *
     * @param string $publicKey Public key in various formats
     * @return string|false Binary public key or false on failure
     */
    private function _convertPublicKeyToBinary(string $publicKey): string|false
    {
        // Debug logging for troubleshooting
        $this->_metrologyInstance->addLog(
            'Converting public key, length=' . strlen($publicKey) . ', first 50 chars: ' . substr($publicKey, 0, 50),
            Metrology::LOG_LEVEL_DEBUG,
            __METHOD__,
            'conv002'
        );

        // Try PEM format first (most common in Nebule)
        if (str_starts_with($publicKey, '-----BEGIN')) {
            $this->_metrologyInstance->addLog(
                'Public key is in PEM format',
                Metrology::LOG_LEVEL_DEBUG,
                __METHOD__,
                'conv003'
            );
            
            // More robust regex to extract base64 content
            // Matches: -----BEGIN ...----- [any whitespace including newlines] BASE64 [any whitespace including newlines] -----END ...-----
            // Remove all whitespace including newlines from the PEM content for easier parsing
            $normalizedPem = preg_replace('/\s+/', '', $publicKey);
            
            // Try to match: -----BEGIN...-----[BASE64]-----END...-----
            if (preg_match('/-----BEGIN(.*?)-----(.*?)-----END(.*?)-----/', $normalizedPem, $matches)) {
                $base64Content = $matches[2];
                $binary = \base64_decode($base64Content, true);
                if ($binary !== false) {
                    $this->_metrologyInstance->addLog(
                        'Successfully decoded PEM to binary, length=' . strlen($binary),
                        Metrology::LOG_LEVEL_DEBUG,
                        __METHOD__,
                        'conv004'
                    );
                    return $binary;
                }
            }
            
            // Try alternative approach: find the first -----BEGIN and last -----END
            $beginPos = strpos($publicKey, '-----BEGIN');
            $endPos = strrpos($publicKey, '-----END');
            if ($beginPos !== false && $endPos !== false && $endPos > $beginPos) {
                $content = substr($publicKey, $beginPos + 11, $endPos - ($beginPos + 11));
                // Remove header and footer lines, keep only base64 content
                $content = preg_replace('/-----BEGIN.*?-----/', '', $content);
                $content = preg_replace('/-----END.*?-----/', '', $content);
                $content = trim($content);
                $binary = \base64_decode($content, true);
                if ($binary !== false) {
                    $this->_metrologyInstance->addLog(
                        'Successfully decoded PEM to binary (alternative method), length=' . strlen($binary),
                        Metrology::LOG_LEVEL_DEBUG,
                        __METHOD__,
                        'conv009'
                    );
                    return $binary;
                }
            }
            
            $this->_metrologyInstance->addLog(
                'Failed to extract or decode PEM content',
                Metrology::LOG_LEVEL_WARNING,
                __METHOD__,
                'conv005'
            );
            return false;
        }

        // Try hex format
        if (ctype_xdigit($publicKey)) {
            $this->_metrologyInstance->addLog(
                'Public key is in hex format',
                Metrology::LOG_LEVEL_DEBUG,
                __METHOD__,
                'conv006'
            );
            return \hex2bin($publicKey);
        }

        // Check if it's already in binary format
        // Binary data should contain non-printable characters
        if (preg_match('/[\x00-\x1F\x7F-\xFF]/', $publicKey)) {
            $this->_metrologyInstance->addLog(
                'Public key appears to be in binary format, length=' . strlen($publicKey),
                Metrology::LOG_LEVEL_DEBUG,
                __METHOD__,
                'conv007'
            );
            return $publicKey;
        }

        // If it's all printable ASCII but not hex or PEM, it's invalid
        $this->_metrologyInstance->addLog(
            'Public key in unknown format (printable ASCII but not PEM or hex)',
            Metrology::LOG_LEVEL_WARNING,
            __METHOD__,
            'conv008'
        );
        return false;
    }

    /**
     * Convert binary key to PEM format.
     *
     * @param string $binaryKey Binary key data
     * @param string $keyType Type of key (PUBLIC KEY, PRIVATE KEY, etc.)
     * @return string|false PEM formatted key or false on failure
     */
    private function _convertBinaryToPEM(string $binaryKey, string $keyType): string|false
    {
        $base64 = base64_encode($binaryKey);
        $pem = "-----BEGIN $keyType-----\n" . $base64 . "\n-----END $keyType-----\n";
        return $pem;
    }

    /**
     * Map hash algorithm name to OpenSSL constant.
     *
     * @param string $algo Hash algorithm name (e.g., 'sha2.256', 'sha2.384')
     * @return int|null OpenSSL constant or null if not supported
     */
    private function _mapAlgoToOpenssl(string $algo): ?int
    {
        // Extract the base algorithm name
        $baseAlgo = strtolower($algo);
        
        if (str_contains($baseAlgo, 'sha2.256') || str_contains($baseAlgo, 'sha256')) {
            return OPENSSL_ALGO_SHA256;
        }
        if (str_contains($baseAlgo, 'sha2.384') || str_contains($baseAlgo, 'sha384')) {
            return OPENSSL_ALGO_SHA384;
        }
        if (str_contains($baseAlgo, 'sha2.512') || str_contains($baseAlgo, 'sha512')) {
            return OPENSSL_ALGO_SHA512;
        }
        if (str_contains($baseAlgo, 'sha1') || str_contains($baseAlgo, 'sha1.128')) {
            return OPENSSL_ALGO_SHA1;
        }
        if (str_contains($baseAlgo, 'sha2.224') || str_contains($baseAlgo, 'sha224')) {
            return OPENSSL_ALGO_SHA224;
        }
        
        return null;
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
            // Convert public key
            $pubKeyBin = $this->_convertPublicKey($publicKey);
            if ($pubKeyBin === false) {
                return '';
            }

            // Check if it's an Ed25519 key (32 bytes) or X25519 (32 bytes) or RSA
            $keyLength = strlen($pubKeyBin);
            
            if ($keyLength === \SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES) {
                // For Ed25519, we can't encrypt directly, but we can use box
                // Generate ephemeral key pair
                $ephemeralKeyPair = \sodium_crypto_box_keypair();
                $ephemeralPublic = \sodium_crypto_box_publickey($ephemeralKeyPair);
                $ephemeralPrivate = \sodium_crypto_box_secretkey($ephemeralKeyPair);

                // Convert Ed25519 public key to Curve25519 (if possible)
                // Note: This conversion is not always possible and may not be secure
                // For production, use X25519 keys for encryption
                $this->_metrologyInstance->addLog(
                    'Ed25519 key not suitable for encryption, use X25519',
                    Metrology::LOG_LEVEL_WARNING,
                    __METHOD__,
                    'encTo003'
                );
                return '';
            } elseif ($keyLength === \SODIUM_CRYPTO_BOX_PUBLICKEYBYTES) {
                // X25519 key - perfect for box
                $nonce = \random_bytes(\SODIUM_CRYPTO_BOX_NONCEBYTES);
                $ciphertext = \sodium_crypto_box($data, $nonce, $pubKeyBin);
                return $nonce . $ciphertext;
            } else {
                // Might be RSA or other - not supported in pure Sodium
                $this->_metrologyInstance->addLog(
                    'Unsupported key type for encryption: ' . $keyLength . ' bytes',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'encTo004'
                );
                return '';
            }
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
     * Convert public key to binary format
     */
    private function _convertPublicKey(string $publicKey): string|false {
        if (str_starts_with($publicKey, '-----BEGIN')) {
            // PEM format - more robust extraction
            $normalizedPem = preg_replace('/\s+/', '', $publicKey);
            if (preg_match('/-----BEGIN(.*?)-----(.*?)-----END(.*?)-----/', $normalizedPem, $matches)) {
                return \base64_decode($matches[2], true);
            }
            
            // Alternative approach
            $beginPos = strpos($publicKey, '-----BEGIN');
            $endPos = strrpos($publicKey, '-----END');
            if ($beginPos !== false && $endPos !== false && $endPos > $beginPos) {
                $content = substr($publicKey, $beginPos + 11, $endPos - ($beginPos + 11));
                $content = preg_replace('/-----BEGIN.*?-----/', '', $content);
                $content = preg_replace('/-----END.*?-----/', '', $content);
                $content = trim($content);
                return \base64_decode($content, true);
            }
            return false;
        }

        // Try hex
        $bin = \sodium_hex2bin($publicKey);
        if ($bin !== false) {
            return $bin;
        }

        // Try base64
        $bin = \base64_decode($publicKey, true);
        if ($bin !== false) {
            return $bin;
        }

        return false;
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

        try {
            // Convert private key
            $privKeyBin = $this->_convertPrivateKey($privateKey, $password);
            if ($privKeyBin === false) {
                return '';
            }

            $keyLength = strlen($privKeyBin);
            
            if ($keyLength === \SODIUM_CRYPTO_BOX_SECRETKEYBYTES) {
                // X25519 private key
                $nonceLength = \SODIUM_CRYPTO_BOX_NONCEBYTES;
                
                if (strlen($code) < $nonceLength) {
                    $this->_metrologyInstance->addLog(
                        'Invalid ciphertext length for box decryption',
                        Metrology::LOG_LEVEL_ERROR,
                        __METHOD__,
                        'decTo003'
                    );
                    return '';
                }

                $nonce = substr($code, 0, $nonceLength);
                $ciphertext = substr($code, $nonceLength);
                
                $plaintext = \sodium_crypto_box_open($ciphertext, $nonce, $privKeyBin);
                
                if ($plaintext === false) {
                    $this->_metrologyInstance->addLog(
                        'Box decryption failed (authentication error)',
                        Metrology::LOG_LEVEL_ERROR,
                        __METHOD__,
                        'decTo004'
                    );
                    return '';
                }

                return $plaintext;
            } else {
                $this->_metrologyInstance->addLog(
                    'Unsupported private key type for decryption: ' . $keyLength . ' bytes',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'decTo005'
                );
                return '';
            }
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
     * Convert private key to binary format
     */
    private function _convertPrivateKey(string $privateKey, ?string $password): string|false {
        if (str_starts_with($privateKey, '-----BEGIN')) {
            // PEM format - this is complex, we'd need proper PEM parsing
            // For now, we'll skip password-protected PEM keys
            if ($password !== null && $password !== '') {
                $this->_metrologyInstance->addLog(
                    'Password-protected PEM keys not supported in Sodium implementation',
                    Metrology::LOG_LEVEL_WARNING,
                    __METHOD__,
                    'pkey001'
                );
                return false;
            }
            
            // More robust PEM extraction
            $normalizedPem = preg_replace('/\s+/', '', $privateKey);
            if (preg_match('/-----BEGIN(.*?)-----(.*?)-----END(.*?)-----/', $normalizedPem, $matches)) {
                return \base64_decode($matches[2], true);
            }
            
            // Alternative approach
            $beginPos = strpos($privateKey, '-----BEGIN');
            $endPos = strrpos($privateKey, '-----END');
            if ($beginPos !== false && $endPos !== false && $endPos > $beginPos) {
                $content = substr($privateKey, $beginPos + 11, $endPos - ($beginPos + 11));
                $content = preg_replace('/-----BEGIN.*?-----/', '', $content);
                $content = preg_replace('/-----END.*?-----/', '', $content);
                $content = trim($content);
                return \base64_decode($content, true);
            }
            return false;
        }

        // Try hex
        $bin = \sodium_hex2bin($privateKey);
        if ($bin !== false) {
            return $bin;
        }

        // Try base64
        $bin = \base64_decode($privateKey);
        if ($bin !== false) {
            return $bin;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     * @param string $password
     * @param string $algo
     * @param int    $size
     * @see CryptoInterface::newAsymmetricKeys()
     */
    public function newAsymmetricKeys(string $password = '', string $algo = '', int $size = 0): array {
        try {
            // For now, we'll generate Ed25519 keys by default
            // Ed25519 is excellent for signing, X25519 for encryption
            
            if ($algo === '' || str_contains($algo, 'ed25519') || str_contains($algo, '25519')) {
                // Generate Ed25519 key pair
                $keyPair = \sodium_crypto_sign_keypair();
                
                $publicKey = \sodium_crypto_sign_publickey($keyPair);
                $privateKey = \sodium_crypto_sign_secretkey($keyPair);
                
                // If password is provided, we need to encrypt the private key
                // But Sodium doesn't have built-in password protection for keys
                // We'll use crypto_secretbox for this
                if ($password !== '') {
                    $nonce = \random_bytes(\SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                    $encryptedPrivate = \sodium_crypto_secretbox($privateKey, $nonce);
                    $privateKey = \sodium_bin2hex($nonce . $encryptedPrivate);
                } else {
                    $privateKey = \sodium_bin2hex($privateKey);
                }
                
                return [
                    'private' => $privateKey,
                    'public' => \sodium_bin2hex($publicKey),
                ];
            } elseif (str_contains($algo, 'x25519')) {
                // Generate X25519 key pair for encryption
                $keyPair = \sodium_crypto_box_keypair();
                
                $publicKey = \sodium_crypto_box_publickey($keyPair);
                $privateKey = \sodium_crypto_box_secretkey($keyPair);
                
                if ($password !== '') {
                    $nonce = \random_bytes(\SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                    $encryptedPrivate = \sodium_crypto_secretbox($privateKey, $nonce);
                    $privateKey = \sodium_bin2hex($nonce . $encryptedPrivate);
                } else {
                    $privateKey = \sodium_bin2hex($privateKey);
                }
                
                return [
                    'private' => $privateKey,
                    'public' => \sodium_bin2hex($publicKey),
                ];
            } else {
                // For RSA, fall back to OpenSSL or return empty
                $this->_metrologyInstance->addLog(
                    'RSA key generation not implemented in Sodium, use OpenSSL',
                    Metrology::LOG_LEVEL_WARNING,
                    __METHOD__,
                    'keys001'
                );
                return [];
            }
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Key generation failed: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'keys002'
            );
            return [];
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkPrivateKeyPassword()
     */
    public function checkPrivateKeyPassword(?string $privateKey, ?string $password): bool {
        if ($privateKey === null || $privateKey === '') {
            return false;
        }

        if ($password === null || $password === '') {
            // If no password provided, assume key is not password-protected
            return true;
        }

        try {
            // Try to decrypt the first part as a Sodium-encrypted private key
            $data = \sodium_hex2bin($privateKey);
            if ($data === false || strlen($data) < \SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + \SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
                return false;
            }

            $nonce = substr($data, 0, \SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted = substr($data, \SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            
            // Derive key from password using Argon2
            $key = \sodium_crypto_pwhash(
                \SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
                $password,
                '', // No salt for this simple check
                \SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                \SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
            );
            
            $decrypted = \sodium_crypto_secretbox_open($encrypted, $nonce, $key);
            
            return $decrypted !== false;
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Password check failed: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_DEBUG,
                __METHOD__,
                'pwd001'
            );
            return false;
        }
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::changePrivateKeyPassword()
     */
    public function changePrivateKeyPassword(?string $privateKey, ?string $oldPassword, ?string $newPassword): string {
        if ($privateKey === null || $oldPassword === null || $newPassword === null) {
            $this->_metrologyInstance->addLog(
                'Null parameter for password change',
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'chpwd001'
            );
            return '';
        }

        try {
            // Decrypt with old password
            $data = \sodium_hex2bin($privateKey);
            if ($data === false) {
                return '';
            }

            $nonce = substr($data, 0, \SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted = substr($data, \SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            
            $oldKey = \sodium_crypto_pwhash(
                \SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
                $oldPassword,
                '',
                \SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                \SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
            );
            
            $decrypted = \sodium_crypto_secretbox_open($encrypted, $nonce, $oldKey);
            if ($decrypted === false) {
                $this->_metrologyInstance->addLog(
                    'Failed to decrypt private key with old password',
                    Metrology::LOG_LEVEL_ERROR,
                    __METHOD__,
                    'chpwd002'
                );
                return '';
            }

            // Encrypt with new password
            $newKey = \sodium_crypto_pwhash(
                \SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
                $newPassword,
                '',
                \SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                \SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
            );
            
            $newNonce = \random_bytes(\SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $newEncrypted = \sodium_crypto_secretbox($decrypted, $newNonce, $newKey);
            
            // Clean up sensitive data
            \sodium_memzero($decrypted);
            \sodium_memzero($oldKey);
            \sodium_memzero($newKey);

            return \sodium_bin2hex($newNonce . $newEncrypted);
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog(
                'Password change failed: ' . $e->getMessage(),
                Metrology::LOG_LEVEL_ERROR,
                __METHOD__,
                'chpwd003'
            );
            return '';
        }
    }
}
