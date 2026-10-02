<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Crypto extends Functions implements CryptoInterface
{
    public const SESSION_SAVED_VARS = array(
            '_defaultInstance',
            '_ready',
            '_listClasses',
            '_listInstances',
            '_listTypes',
            '_functionPreference',
            '_performanceCache',
            '_performanceCacheTime',
    );

    public const DEFAULT_CLASS = 'openssl';
    public const PERFORMANCE_CACHE_TTL = 300; // 5 minutes en secondes

    public const RANDOM_PSEUDO = 1;
    public const RANDOM_STRONG = 2;
    public const TYPE_HASH = 1;
    public const TYPE_SYMMETRIC = 2;
    public const TYPE_ASYMMETRIC = 3;

    private ?CryptoInterface $_defaultInstance = null;
    private array $_functionPreference = [];
    private array $_performanceCache = [];
    private int $_performanceCacheTime = 0;

    public function __toString(): string
    {
        return self::TYPE;
    }

    protected function _initialisation(): void
    {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        foreach (get_declared_classes() as $class) {
            if (str_starts_with($class, __NAMESPACE__ . '\\Crypto')
                && $class != get_class($this)
                && is_subclass_of($class, CryptoInterface::class)) {
                $this->_metrologyInstance->addLog('add crypto class ' . $class, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '53556863');
                $this->_initSubInstance($class);
            }
        }
        
        $defaultClass = $this->_configurationInstance->getOptionAsString('cryptoLibrary') ?: self::DEFAULT_CLASS;
        $this->_defaultInstance = $this->_getDefaultSubInstance($defaultClass);
        
        $this->_initializeDefaultPreferences();
    }

    /**
     * Initialize default function preferences.
     */
    private function _initializeDefaultPreferences(): void
    {
        // Use OpenSSL for strong random and cryptographic operations
        $this->setFunctionPreference('getRandom', 'openssl', 'strong');
        $this->setFunctionPreference('hash', 'openssl');
        $this->setFunctionPreference('encrypt', 'openssl');
        $this->setFunctionPreference('decrypt', 'openssl');
        $this->setFunctionPreference('sign', 'openssl');
        $this->setFunctionPreference('verify', 'openssl');
        $this->setFunctionPreference('encryptTo', 'openssl');
        $this->setFunctionPreference('decryptTo', 'openssl');
        $this->setFunctionPreference('newAsymmetricKeys', 'openssl');
        $this->setFunctionPreference('checkPrivateKeyPassword', 'openssl');
        $this->setFunctionPreference('changePrivateKeyPassword', 'openssl');
        
        // Use Software for pseudo-random (lighter)
        $this->setFunctionPreference('getRandom', 'software', 'pseudo');
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getCryptoInstance()
     */
    public function getCryptoInstance(): CryptoInterface
    {
        return $this->_defaultInstance;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getCryptoInstanceName()
     */
    public function getCryptoInstanceName(): string
    {
        return get_class($this->_defaultInstance);
    }

    /**
     * Set performance preference for a specific function.
     * This overrides the automatic benchmarking selection.
     *
     * @param string $functionName Name of the crypto function
     * @param string $className Name of the preferred implementation class
     * @param string $algo Algorithm to use (optional)
     * @return void
     */
    public function setFunctionPreference(string $functionName, string $className, string $algo = ''): void
    {
        $cacheKey = $functionName . ($algo !== '' ? ':' . $algo : '');
        $this->_functionPreference[$cacheKey] = $className;
        
        // Clear performance cache for this function to force use of preference
        if (isset($this->_performanceCache[$cacheKey])) {
            unset($this->_performanceCache[$cacheKey]);
        }
    }

    /**
     * Enable or disable automatic performance-based selection.
     *
     * @param bool $enabled Whether to enable automatic benchmarking
     * @return void
     */
    public function setBenchmarkingEnabled(bool $enabled): void
    {
        // Store in configuration if available
        if ($this->_configurationInstance !== null) {
            $this->_configurationInstance->setOption('cryptoEnableBenchmark', $enabled ? 'true' : 'false');
        }
        
        // Clear cache when disabling to avoid using stale data
        if (!$enabled) {
            $this->clearPerformanceCache();
        }
    }

    /**
     * Check if benchmarking is currently enabled.
     *
     * @return bool
     */
    public function isBenchmarkingEnabled(): bool
    {
        return $this->_configurationInstance->getOptionAsBoolean('cryptoEnableBenchmark') ?? true;
    }

    /**
     * Get the best crypto implementation for a specific function.
     * Uses cached performance results if available, otherwise runs benchmark.
     *
     * @param string $functionName Name of the crypto function
     * @param string $algo Algorithm to use (optional)
     * @return CryptoInterface
     */
    private function _getBestInstanceForFunction(string $functionName, string $algo = ''): CryptoInterface
    {
        $cacheKey = $functionName . ($algo !== '' ? ':' . $algo : '');
        
        // Check if we have a preference for this function
        if (isset($this->_functionPreference[$cacheKey])) {
            $preferredClass = $this->_functionPreference[$cacheKey];
            if (isset($this->_listInstances[$preferredClass])) {
                return $this->_listInstances[$preferredClass];
            }
        }
        
        // Check performance cache if enabled and not expired
        $enableBenchmark = $this->_configurationInstance->getOptionAsBoolean('cryptoEnableBenchmark') ?? true;
        if ($enableBenchmark && isset($this->_performanceCache[$cacheKey])) {
            $cacheTimestamp = $this->_performanceCacheTime;
            if ((time() - $cacheTimestamp) < self::PERFORMANCE_CACHE_TTL) {
                $bestClass = $this->_performanceCache[$cacheKey];
                if (isset($this->_listInstances[$bestClass])) {
                    return $this->_listInstances[$bestClass];
                }
            }
        }
        
        // Run benchmark and select best implementation
        if ($enableBenchmark) {
            $bestClass = $this->_benchmarkAndSelect($functionName, $algo);
            if ($bestClass !== null && isset($this->_listInstances[$bestClass])) {
                $this->_performanceCache[$cacheKey] = $bestClass;
                $this->_performanceCacheTime = time();
                return $this->_listInstances[$bestClass];
            }
        }
        
        // Fallback to default instance
        return $this->_defaultInstance;
    }

    /**
     * Benchmark all available implementations for a specific function and select the best one.
     *
     * @param string $functionName Name of the crypto function
     * @param string $algo Algorithm to use (optional)
     * @return string|null Name of the best class or null if no valid implementation found
     */
    private function _benchmarkAndSelect(string $functionName, string $algo = ''): ?string
    {
        // Skip benchmarking if we don't have multiple implementations
        if (count($this->_listInstances) <= 1) {
            return null;
        }

        // Define test data for each function
        $testData = $this->_getBenchmarkTestData($functionName, $algo);
        if ($testData === null) {
            return null;
        }

        $bestTime = PHP_FLOAT_MAX;
        $bestClass = null;

        foreach ($this->_listInstances as $className => $instance) {
            try {
                $start = microtime(true);
                call_user_func_array([$instance, $functionName], $testData);
                $time = microtime(true) - $start;

                if ($time < $bestTime) {
                    $bestTime = $time;
                    $bestClass = $className;
                }
            } catch (\Throwable $e) {
                // Skip failed implementations
                continue;
            }
        }

        return $bestClass;
    }

    /**
     * Get test data for benchmarking a specific function.
     *
     * @param string $functionName Name of the crypto function
     * @param string $algo Algorithm to use (optional)
     * @return array|null Test data for the function or null if not benchmarkable
     */
    private function _getBenchmarkTestData(string $functionName, string $algo = ''): ?array
    {
        $testDataMap = [
            'getRandom' => [
                'strong' => [32, Crypto::RANDOM_STRONG],
                'pseudo' => [32, Crypto::RANDOM_PSEUDO],
                '' => [32, Crypto::RANDOM_PSEUDO],
            ],
            'getEntropy' => [
                '' => ['test data for entropy calculation'],
            ],
            'hash' => [
                '' => ['Bienvenue dans le projet nebule.'],
                'sha2.256' => ['Bienvenue dans le projet nebule.'],
                'sha1.128' => ['Bienvenue dans le projet nebule.'],
            ],
            'encrypt' => [
                '' => ['Test encryption data', 'aes.256.cbc', '0123456789abcdef0123456789abcdef', ''],
                'aes.256.cbc' => ['Test encryption data', 'aes.256.cbc', '0123456789abcdef0123456789abcdef', ''],
            ],
            'decrypt' => [
                '' => ['', 'aes.256.cbc', '0123456789abcdef0123456789abcdef', ''],
                'aes.256.cbc' => ['', 'aes.256.cbc', '0123456789abcdef0123456789abcdef', ''],
            ],
            'sign' => [
                '' => ['Test data for signing', '', ''],
            ],
            'verify' => [
                '' => ['Test data', '', '', 'sha2.256'],
            ],
            'encryptTo' => [
                '' => ['Test data', null],
            ],
            'decryptTo' => [
                '' => ['', null, null],
            ],
            'newAsymmetricKeys' => [
                '' => ['', '', 2048],
            ],
            'checkPrivateKeyPassword' => [
                '' => [null, null],
            ],
            'changePrivateKeyPassword' => [
                '' => [null, null, null],
            ],
        ];

        if (isset($testDataMap[$functionName])) {
            if ($algo !== '' && isset($testDataMap[$functionName][$algo])) {
                return $testDataMap[$functionName][$algo];
            }
            if (isset($testDataMap[$functionName][''])) {
                return $testDataMap[$functionName][''];
            }
        }

        // For unknown functions, try with empty string
        return [''];
    }

    /**
     * Run benchmark for all functions and update performance cache.
     * This can be called manually to refresh the cache.
     *
     * @return array Results of the benchmark for each function
     */
    public function runFullBenchmark(): array
    {
        $results = [];
        $functions = [
            'getRandom',
            'getEntropy',
            'hash',
            'encrypt',
            'decrypt',
            'sign',
            'verify',
            'encryptTo',
            'decryptTo',
            'newAsymmetricKeys',
            'checkPrivateKeyPassword',
            'changePrivateKeyPassword',
        ];

        foreach ($functions as $function) {
            $results[$function] = $this->_benchmarkAndSelect($function, '');
        }

        // Update cache timestamp
        $this->_performanceCacheTime = time();

        return $results;
    }

    /**
     * Clear the performance cache to force re-benchmarking.
     *
     * @return void
     */
    public function clearPerformanceCache(): void
    {
        $this->_performanceCache = [];
        $this->_performanceCacheTime = 0;
    }

    /**
     * Get the current performance cache.
     *
     * @return array
     */
    public function getPerformanceCache(): array
    {
        return $this->_performanceCache;
    }

    /**
     * Get performance statistics for all available implementations.
     * Runs benchmark for each function and returns timings.
     *
     * @return array Performance statistics [functionName => [className => time]]
     */
    public function getPerformanceStatistics(): array
    {
        $stats = [];
        $functions = [
            'getRandom',
            'getEntropy',
            'hash',
            'encrypt',
            'decrypt',
            'sign',
            'verify',
            'encryptTo',
            'decryptTo',
            'newAsymmetricKeys',
            'checkPrivateKeyPassword',
            'changePrivateKeyPassword',
        ];

        foreach ($functions as $functionName) {
            $stats[$functionName] = [];
            $testData = $this->_getBenchmarkTestData($functionName, '');
            
            if ($testData === null) {
                continue;
            }

            foreach ($this->_listInstances as $className => $instance) {
                try {
                    $start = microtime(true);
                    call_user_func_array([$instance, $functionName], $testData);
                    $time = microtime(true) - $start;
                    
                    $stats[$functionName][$className] = $time;
                } catch (\Throwable $e) {
                    $stats[$functionName][$className] = null;
                }
            }
        }

        return $stats;
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkFunction()
     */
    public function checkFunction(string $algo, int $type): bool
    {
        return $this->_defaultInstance->checkFunction($algo, $type);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkValidAlgorithm()
     */
    public function checkValidAlgorithm(string $algo, int $type): bool
    {
        return $this->_defaultInstance->checkValidAlgorithm($algo, $type);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getAlgorithmList()
     */
    public function getAlgorithmList(int $type): array
    {
        return $this->_defaultInstance->getAlgorithmList($type);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_PSEUDO): string
    {
        $qualityStr = $quality === Crypto::RANDOM_STRONG ? 'strong' : 'pseudo';
        $instance = $this->_getBestInstanceForFunction('getRandom', $qualityStr);
        return $instance->getRandom($size, $quality);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getEntropy()
     */
    public function getEntropy(string &$data): float
    {
        $instance = $this->_getBestInstanceForFunction('getEntropy');
        return $instance->getEntropy($data);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::hash()
     */
    public function hash(string $data, string $algo = ''): string
    {
        if ($algo == '')
            $algo = \Nebule\Library\References::REFERENCE_CRYPTO_HASH_ALGORITHM;
        $instance = $this->_getBestInstanceForFunction('hash', $algo);
        return $instance->hash($data, $algo);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encrypt()
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        $instance = $this->_getBestInstanceForFunction('encrypt', $algo);
        return $instance->encrypt($data, $algo, $hexKey, $hexIV);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decrypt()
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string
    {
        $instance = $this->_getBestInstanceForFunction('decrypt', $algo);
        return $instance->decrypt($data, $algo, $hexKey, $hexIV);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::sign()
     */
    public function sign(string $data, string $privateKey, string $privatePassword): string
    {
        $instance = $this->_getBestInstanceForFunction('sign');
        return $instance->sign($data, $privateKey, $privatePassword);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::verify()
     */
    public function verify(string $data, string $sign, string $publicKey, string $algo): bool
    {
        $instance = $this->_getBestInstanceForFunction('verify', $algo);
        return $instance->verify($data, $sign, $publicKey, $algo);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encryptTo()
     */
    public function encryptTo(string $data, ?string $publicKey): string
    {
        $instance = $this->_getBestInstanceForFunction('encryptTo');
        return $instance->encryptTo($data, $publicKey);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decryptTo()
     */
    public function decryptTo(string $code, ?string $privateKey, ?string $password): string
    {
        $instance = $this->_getBestInstanceForFunction('decryptTo');
        return $instance->decryptTo($code, $privateKey, $password);
    }

    /**
     * {@inheritDoc}
     * @param string $password
     * @param string $algo
     * @param int    $size
     * @see CryptoInterface::newAsymmetricKeys()
     */
    public function newAsymmetricKeys(string $password = '', string $algo = '', int $size = 0): array
    {
        $algoKey = $algo !== '' ? $algo . '.' . $size : '';
        $instance = $this->_getBestInstanceForFunction('newAsymmetricKeys', $algoKey);
        return $instance->newAsymmetricKeys($password, $algo, $size);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkPrivateKeyPassword()
     */
    public function checkPrivateKeyPassword(?string $privateKey, ?string $password): bool
    {
        $instance = $this->_getBestInstanceForFunction('checkPrivateKeyPassword');
        return $instance->checkPrivateKeyPassword($privateKey, $password);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::changePrivateKeyPassword()
     */
    public function changePrivateKeyPassword(?string $privateKey, ?string $oldPassword, ?string $newPassword): string
    {
        $instance = $this->_getBestInstanceForFunction('changePrivateKeyPassword');
        return $instance->changePrivateKeyPassword($privateKey, $oldPassword, $newPassword);
    }
}
