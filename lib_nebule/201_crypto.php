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
            '_performanceCacheTimestamps',
            '_libraryPriorities',
            '_benchmarkingEnabled',
    );

    public const DEFAULT_CLASS = 'openssl';
    public const PERFORMANCE_CACHE_TTL = 300; // 5 minutes en secondes
    public const BENCHMARK_ITERATIONS = 10; // Nombre d'itérations pour le benchmark
    public const BENCHMARK_WARMUP_ITERATIONS = 3; // Itérations de warm-up

    public const RANDOM_PSEUDO = 1;
    public const RANDOM_STRONG = 2;
    public const TYPE_HASH = 1;
    public const TYPE_SYMMETRIC = 2;
    public const TYPE_ASYMMETRIC = 3;

    private ?CryptoInterface $_defaultInstance = null;
    private array $_functionPreference = [];
    private array $_performanceCache = [];
    private array $_performanceCacheTimestamps = []; // Timestamp par entrée de cache
    private array $_libraryPriorities = [
        'sodium' => 100,
        'openssl' => 80,
        'software' => 10,
    ];
    private bool $_benchmarkingEnabled = true;
    private bool $_initializing = false;

    public function __toString(): string
    {
        return self::TYPE;
    }

    /**
     * Called when unserializing the object.
     * Prevents benchmarking during unserialization to avoid performance impact.
     */
    public function __wakeup(): void
    {
        // Ensure benchmarking is disabled after unserialization
        // Benchmarking should only run during initial initialization
        $this->_initializing = false;
        
        // Restore default preferences after unserialization
        // This ensures function preferences are maintained across serialization
        $this->_initializeDefaultPreferences();
    }

    protected function _initialisation(): void
    {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_initializing = true;
        
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
        $this->_initializing = false;
    }

    /**
     * Initialize default function preferences.
     * Uses the configured cryptoLibrary as the default for all functions.
     */
    private function _initializeDefaultPreferences(): void
    {
        // Get the configured default library
        $defaultLib = $this->_configurationInstance !== null 
            ? strtolower($this->_configurationInstance->getOptionAsString('cryptoLibrary') ?: self::DEFAULT_CLASS)
            : strtolower(self::DEFAULT_CLASS);
        
        // Use the configured default library for all crypto functions
        // This ensures cryptoLibrary option is respected
        $this->setFunctionPreference('getRandom', $defaultLib, 'strong');
        $this->setFunctionPreference('hash', $defaultLib);
        $this->setFunctionPreference('encrypt', $defaultLib);
        $this->setFunctionPreference('decrypt', $defaultLib);
        $this->setFunctionPreference('sign', $defaultLib);
        $this->setFunctionPreference('verify', $defaultLib);
        $this->setFunctionPreference('encryptTo', $defaultLib);
        $this->setFunctionPreference('decryptTo', $defaultLib);
        $this->setFunctionPreference('newAsymmetricKeys', $defaultLib);
        $this->setFunctionPreference('checkPrivateKeyPassword', $defaultLib);
        $this->setFunctionPreference('changePrivateKeyPassword', $defaultLib);
        
        // Use Software for pseudo-random (lighter)
        // Software is always available as fallback
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
        $this->_benchmarkingEnabled = $enabled;
        
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
     * Benchmarking is only enabled during initialization, not during unserialization.
     *
     * @return bool
     */
    public function isBenchmarkingEnabled(): bool
    {
        // Never run benchmarking during unserialization to avoid performance impact
        if (!$this->_initializing) {
            return false;
        }
        
        if ($this->_configurationInstance !== null) {
            return $this->_configurationInstance->getOptionAsBoolean('cryptoEnableBenchmark') ?? $this->_benchmarkingEnabled;
        }
        return $this->_benchmarkingEnabled;
    }

    /**
     * Set library priority for automatic selection.
     * Higher priority means preferred when performance is equal.
     *
     * @param string $libraryName Name of the library (e.g., 'sodium', 'openssl')
     * @param int $priority Priority value (higher = more preferred)
     * @return void
     */
    public function setLibraryPriority(string $libraryName, int $priority): void
    {
        $this->_libraryPriorities[$libraryName] = $priority;
        $this->clearPerformanceCache();
    }

    /**
     * Get current library priorities.
     *
     * @return array Library priorities
     */
    public function getLibraryPriorities(): array
    {
        return $this->_libraryPriorities;
    }

    /**
     * Check if a specific library is available.
     *
     * @param string $libraryName Name of the library
     * @return bool
     */
    public function isLibraryAvailable(string $libraryName): bool
    {
        $className = __NAMESPACE__ . '\Crypto' . ucfirst(strtolower($libraryName));
        return isset($this->_listInstances[$className]);
    }

    /**
     * Check required PHP extensions for a library.
     *
     * @param string $libraryName Name of the library
     * @return array Array of required extensions
     */
    public function getRequiredExtensions(string $libraryName): array
    {
        $extensions = [
            'sodium' => ['sodium'],
            'openssl' => ['openssl'],
            'software' => [], // No extensions required
        ];

        return $extensions[strtolower($libraryName)] ?? [];
    }

    /**
     * Check if all required extensions for a library are loaded.
     *
     * @param string $libraryName Name of the library
     * @return bool
     */
    public function checkLibraryRequirements(string $libraryName): bool
    {
        $required = $this->getRequiredExtensions($libraryName);
        
        foreach ($required as $extension) {
            if (!extension_loaded($extension)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get status of all available crypto libraries.
     *
     * @return array Library status information
     */
    public function getLibraryStatus(): array
    {
        $status = [];
        $libraries = ['sodium', 'openssl', 'software'];
        
        foreach ($libraries as $libName) {
            $className = __NAMESPACE__ . '\Crypto' . ucfirst($libName);
            
            $status[$libName] = [
                'class_exists' => class_exists($className),
                'instance_loaded' => isset($this->_listInstances[$className]),
                'extensions_required' => $this->getRequiredExtensions($libName),
                'extensions_loaded' => $this->checkLibraryRequirements($libName),
                'priority' => $this->_libraryPriorities[$libName] ?? 0,
                'available' => $this->isLibraryAvailable($libName),
            ];
        }
        
        return $status;
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
        // Ensure initialization is complete before selecting instances
        if (!$this->_initialisationSet) {
            $this->initialisation();
        }
        
        $cacheKey = $functionName . ($algo !== '' ? ':' . $algo : '');
        
        // 1. Check explicit preference
        if (isset($this->_functionPreference[$cacheKey])) {
            $preferredClass = $this->_functionPreference[$cacheKey];
            if (isset($this->_listInstances[$preferredClass])) {
                return $this->_listInstances[$preferredClass];
            }
        }
        
        // 2. Check performance cache if enabled and not expired
        if ($this->isBenchmarkingEnabled() && isset($this->_performanceCache[$cacheKey])) {
            $cacheTimestamp = $this->_performanceCacheTimestamps[$cacheKey] ?? 0;
            if ((time() - $cacheTimestamp) < self::PERFORMANCE_CACHE_TTL) {
                $bestClass = $this->_performanceCache[$cacheKey];
                if (isset($this->_listInstances[$bestClass])) {
                    return $this->_listInstances[$bestClass];
                }
            }
        }
        
        // 3. Run benchmark and select best implementation
        if ($this->isBenchmarkingEnabled()) {
            $bestClass = $this->_benchmarkAndSelect($functionName, $algo);
            if ($bestClass !== null && isset($this->_listInstances[$bestClass])) {
                $this->_performanceCache[$cacheKey] = $bestClass;
                $this->_performanceCacheTimestamps[$cacheKey] = time();
                return $this->_listInstances[$bestClass];
            }
        }
        
        // 4. Fallback to configured default instance first
        if ($this->_defaultInstance !== null) {
            return $this->_defaultInstance;
        }
        
        // 5. Fallback using library priorities if default is not available
        if (!empty($this->_libraryPriorities)) {
            // Sort libraries by priority (descending)
            arsort($this->_libraryPriorities);
            
            foreach ($this->_libraryPriorities as $libName => $priority) {
                // Les instances sont stockées avec la clé = strtolower(TYPE)
                // CryptoOpenssl::TYPE = 'Openssl' -> clé = 'openssl'
                // CryptoSodium::TYPE = 'Sodium' -> clé = 'sodium'
                $instanceKey = strtolower($libName);
                if (isset($this->_listInstances[$instanceKey])) {
                    return $this->_listInstances[$instanceKey];
                }
            }
        }
        
        // 6. Absolute fallback
        return $this;
    }

    /**
     * Benchmark all available implementations for a specific function and select the best one.
     * Uses multiple iterations and median calculation for more reliable results.
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

        $results = [];
        $iterations = self::BENCHMARK_ITERATIONS;

        foreach ($this->_listInstances as $className => $instance) {
            $times = [];
            
            try {
                // Warm-up phase
                for ($w = 0; $w < self::BENCHMARK_WARMUP_ITERATIONS; $w++) {
                    call_user_func_array([$instance, $functionName], $testData);
                }

                // Measure phase
                for ($i = 0; $i < $iterations; $i++) {
                    $start = microtime(true);
                    call_user_func_array([$instance, $functionName], $testData);
                    $time = microtime(true) - $start;
                    $times[] = $time;
                }

                if (!empty($times)) {
                    // Use median to avoid outliers
                    sort($times);
                    $medianIndex = (int)(count($times) / 2);
                    $results[$className] = [
                        'median' => $times[$medianIndex],
                        'min' => min($times),
                        'max' => max($times),
                        'priority' => $this->_libraryPriorities[strtolower(str_replace('Nebule\Library\Crypto', '', $className))] ?? 0
                    ];
                }
            } catch (\Throwable $e) {
                $this->_metrologyInstance->addLog(
                    "Benchmark failed for $className::$functionName: " . $e->getMessage(),
                    Metrology::LOG_LEVEL_DEBUG,
                    __METHOD__,
                    'bench001'
                );
                continue;
            }
        }

        if (empty($results)) {
            return null;
        }

        // Sort by median time, then by priority (for ties)
        uasort($results, function($a, $b) {
            // First by median time
            if ($a['median'] !== $b['median']) {
                return $a['median'] <=> $b['median'];
            }
            // Then by priority (higher priority first)
            return $b['priority'] <=> $a['priority'];
        });

        // Return the best class
        return array_key_first($results);
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
        $this->_performanceCacheTimestamps = [];
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
     * Get performance statistics for the cache (including timestamps).
     *
     * @return array Performance cache with timestamps
     */
    public function getPerformanceCacheWithTimestamps(): array
    {
        return [
            'cache' => $this->_performanceCache,
            'timestamps' => $this->_performanceCacheTimestamps,
            'ttl' => self::PERFORMANCE_CACHE_TTL
        ];
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
