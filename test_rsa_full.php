<?php
// Test avec toutes les dépendances

echo "Testing RSA with full dependencies...\n\n";

// Inclure les fichiers dans le bon ordre
require_once __DIR__ . '/lib_nebule/200_crypto_interface.php';
require_once __DIR__ . '/lib_nebule/201_crypto.php';

echo "1. Testing basic class loading...\n";

// Vérifions que les interfaces et classes de base existent
if (interface_exists('Nebule\Library\CryptoInterface')) {
    echo "   CryptoInterface: FOUND\n";
} else {
    echo "   CryptoInterface: NOT FOUND\n";
}

if (class_exists('Nebule\Library\Crypto')) {
    echo "   Crypto class: FOUND\n";
} else {
    echo "   Crypto class: NOT FOUND\n";
}

// Maintenant essayer de charger la classe Software
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

if (class_exists('Nebule\Library\CryptoSoftware')) {
    echo "   CryptoSoftware class: FOUND\n";
} else {
    echo "   CryptoSoftware class: NOT FOUND\n";
}

if (class_exists('Nebule\Library\RSASoftware')) {
    echo "   RSASoftware class: FOUND\n";
} else {
    echo "   RSASoftware class: NOT FOUND\n";
}

echo "\n2. Testing RSASoftware directly...\n";

try {
    // Utiliser reflection pour créer RSASoftware sans dépendances
    if (class_exists('Nebule\Library\RSASoftware')) {
        $rsaClass = new ReflectionClass('Nebule\Library\RSASoftware');
        $rsa = $rsaClass->newInstance();
        
        // Tester les opérations mathématiques de base
        echo "   Testing basic math...\n";
        
        $sum = $rsaClass->getMethod('add')->invoke($rsa, "123", "456");
        echo "   Addition (123+456): " . ($sum === "579" ? "PASS" : "FAIL (got: $sum)") . "\n";
        
        $diff = $rsaClass->getMethod('subtract')->invoke($rsa, "456", "123");
        echo "   Subtraction (456-123): " . ($diff === "333" ? "PASS" : "FAIL (got: $diff)") . "\n";
        
        $prod = $rsaClass->getMethod('multiply')->invoke($rsa, "12", "12");
        echo "   Multiplication (12*12): " . ($prod === "144" ? "PASS" : "FAIL (got: $prod)") . "\n";
        
        // Tester la génération de nombres premiers
        echo "\n   Testing prime generation (small)...\n";
        $start = microtime(true);
        $prime = $rsaClass->getMethod('generatePrime')->invoke($rsa, 64); // 64-bit prime
        $end = microtime(true);
        echo "   Generated 64-bit prime: " . (strlen($prime) > 10 ? "PASS" : "FAIL") . "\n";
        echo "   Prime length: " . strlen($prime) . " digits\n";
        echo "   Time taken: " . round($end - $start, 2) . " seconds\n";
        
        // Tester la génération de clés RSA
        echo "\n   Testing RSA key generation (small)...\n";
        $start = microtime(true);
        $keys = $rsaClass->getMethod('generateKeyPair')->invoke($rsa, 128); // 128-bit for testing
        $end = microtime(true);
        
        if (!empty($keys['private']) && !empty($keys['public'])) {
            echo "   Key generation: PASS\n";
            echo "   Time taken: " . round($end - $start, 2) . " seconds\n";
        } else {
            echo "   Key generation: FAIL\n";
        }
        
    } else {
        echo "   Cannot test RSASoftware: class not available\n";
    }
    
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\nTest completed.\n";