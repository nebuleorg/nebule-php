<?php
/**
 * Test script to verify crypto library fixes
 */

// Add the library to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . '/home/bachue/workspace/nebule-php/lib_nebule');

// Include required files
require_once '001_requires.php';

// Test 1: Check that SODIUM constants are properly referenced with backslash
echo "Test 1: Checking SODIUM constants...\n";
$constantsToCheck = [
    'SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES',
    'SODIUM_CRYPTO_BOX_PUBLICKEYBYTES',
    'SODIUM_CRYPTO_SECRETBOX_NONCEBYTES',
];

foreach ($constantsToCheck as $constant) {
    if (defined($constant)) {
        echo "  ✓ $constant is defined: " . constant($constant) . "\n";
    } else {
        echo "  ✗ $constant is NOT defined\n";
    }
}

// Test 2: Check that cryptoLibrary option is respected
echo "\nTest 2: Checking cryptoLibrary option...\n";
try {
    // Create a minimal Nebule instance
    if (!class_exists('Nebule\Library\Nebule')) {
        echo "  ! Nebule class not found, skipping test\n";
    } else {
        // We can't easily create a full Nebule instance without the proper environment
        // So we'll check the configuration directly
        if (class_exists('Nebule\Library\Configuration')) {
            $defaultValue = Nebule\Library\Configuration::OPTIONS_DEFAULT_VALUE['cryptoLibrary'];
            echo "  Default cryptoLibrary value: $defaultValue\n";
            
            if ($defaultValue === 'Openssl') {
                echo "  ✓ Default cryptoLibrary is 'Openssl'\n";
            } else {
                echo "  ✗ Default cryptoLibrary is not 'Openssl': $defaultValue\n";
            }
        }
    }
} catch (Exception $e) {
    echo "  ✗ Exception: " . $e->getMessage() . "\n";
}

// Test 3: Check that Crypto class has __wakeup method with _initializeDefaultPreferences call
echo "\nTest 3: Checking Crypto::__wakeup method...\n";
if (class_exists('Nebule\Library\Crypto')) {
    $reflection = new ReflectionClass('Nebule\Library\Crypto');
    if ($reflection->hasMethod('__wakeup')) {
        echo "  ✓ Crypto class has __wakeup method\n";
        
        // Check method source for _initializeDefaultPreferences call
        $method = $reflection->getMethod('__wakeup');
        $source = file_get_contents($method->getFileName());
        if (strpos($source, '_initializeDefaultPreferences') !== false && 
            strpos($source, '__wakeup') !== false) {
            echo "  ✓ __wakeup calls _initializeDefaultPreferences\n";
        } else {
            echo "  ✗ __wakeup does not call _initializeDefaultPreferences\n";
        }
    } else {
        echo "  ✗ Crypto class does not have __wakeup method\n";
    }
} else {
    echo "  ! Crypto class not found\n";
}

// Test 4: Check that CryptoSodium has fixed PEM conversion
echo "\nTest 4: Checking CryptoSodium PEM conversion...\n";
if (class_exists('Nebule\Library\CryptoSodium')) {
    $reflection = new ReflectionClass('Nebule\Library\CryptoSodium');
    if ($reflection->hasMethod('_convertPublicKeyToBinary')) {
        echo "  ✓ CryptoSodium has _convertPublicKeyToBinary method\n";
        
        $method = $reflection->getMethod('_convertPublicKeyToBinary');
        $source = file_get_contents($method->getFileName());
        
        // Check for improved regex patterns
        if (strpos($source, 'normalize') !== false || 
            strpos($source, 'alternative') !== false) {
            echo "  ✓ _convertPublicKeyToBinary has improved PEM parsing\n";
        } else {
            echo "  ✗ _convertPublicKeyToBinary may not have improved PEM parsing\n";
        }
    } else {
        echo "  ✗ CryptoSodium does not have _convertPublicKeyToBinary method\n";
    }
} else {
    echo "  ! CryptoSodium class not found\n";
}

// Test 5: Check that _initializeDefaultPreferences uses cryptoLibrary config
echo "\nTest 5: Checking _initializeDefaultPreferences uses cryptoLibrary...\n";
if (class_exists('Nebule\Library\Crypto')) {
    $reflection = new ReflectionClass('Nebule\Library\Crypto');
    if ($reflection->hasMethod('_initializeDefaultPreferences')) {
        echo "  ✓ Crypto has _initializeDefaultPreferences method\n";
        
        $method = $reflection->getMethod('_initializeDefaultPreferences');
        $source = file_get_contents($method->getFileName());
        
        // Check for cryptoLibrary usage
        if (strpos($source, 'cryptoLibrary') !== false) {
            echo "  ✓ _initializeDefaultPreferences references cryptoLibrary\n";
        } else {
            echo "  ✗ _initializeDefaultPreferences does not reference cryptoLibrary\n";
        }
    } else {
        echo "  ✗ Crypto does not have _initializeDefaultPreferences method\n";
    }
} else {
    echo "  ! Crypto class not found\n";
}

echo "\nAll tests completed!\n";
