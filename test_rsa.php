<?php
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

use Nebule\Library\CryptoSoftware;

// Tester la classe CryptoSoftware
try {
    // Créer une instance
    $crypto = new CryptoSoftware();
    
    echo "Testing RSA Software Implementation...\n\n";
    
    // Tester la génération de clés RSA
    echo "1. Testing RSA key generation...\n";
    $start = microtime(true);
    
    // Utiliser une petite taille de clé pour le test
    $keys = $crypto->newAsymmetricKeys('', 'rsa.1024', 1024);
    
    $end = microtime(true);
    if (!empty($keys) && isset($keys['private']) && isset($keys['public'])) {
        echo "   Key generation: SUCCESS (took " . round($end - $start, 2) . " seconds)\n";
        echo "   Private key length: " . strlen($keys['private']) . " bytes\n";
        echo "   Public key length: " . strlen($keys['public']) . " bytes\n";
    } else {
        echo "   Key generation: FAILED\n";
    }
    
    // Tester le chiffrement/déchiffrement
    if (!empty($keys)) {
        echo "\n2. Testing RSA encryption/decryption...\n";
        $testData = "Hello, RSA World!";
        echo "   Original data: '$testData'\n";
        
        $encrypted = $crypto->encryptTo($testData, $keys['public']);
        if ($encrypted !== '') {
            echo "   Encryption: SUCCESS\n";
            echo "   Encrypted length: " . strlen($encrypted) . " bytes\n";
            
            $decrypted = $crypto->decryptTo($encrypted, $keys['private'], '');
            if ($decrypted === $testData) {
                echo "   Decryption: SUCCESS\n";
                echo "   Decrypted data: '$decrypted'\n";
            } else {
                echo "   Decryption: FAILED\n";
                echo "   Decrypted data: '$decrypted'\n";
            }
        } else {
            echo "   Encryption: FAILED\n";
        }
        
        // Tester la signature/vérification
        echo "\n3. Testing RSA signing/verification...\n";
        $signature = $crypto->sign($testData, $keys['private'], '');
        if ($signature !== '') {
            echo "   Signing: SUCCESS\n";
            echo "   Signature length: " . strlen($signature) . " bytes\n";
            
            $verified = $crypto->verify($testData, $signature, $keys['public'], 'rsa.1024');
            echo "   Verification: " . ($verified ? "SUCCESS" : "FAILED") . "\n";
        } else {
            echo "   Signing: FAILED\n";
        }
        
        // Tester avec un mauvais message
        echo "\n4. Testing RSA verification with wrong data...\n";
        $wrongData = "Wrong data";
        $verifiedWrong = $crypto->verify($wrongData, $signature, $keys['public'], 'rsa.1024');
        echo "   Verification with wrong data: " . ($verifiedWrong ? "FAILED (should be false)" : "SUCCESS (correctly failed)") . "\n";
    }
    
    // Tester la vérification des algorithmes
    echo "\n5. Testing algorithm validation...\n";
    $algorithms = ['rsa.1024', 'rsa.2048', 'rsa.4096'];
    foreach ($algorithms as $algo) {
        $valid = $crypto->checkValidAlgorithm($algo, \Nebule\Library\Crypto::TYPE_ASYMMETRIC);
        echo "   $algo: " . ($valid ? "VALID" : "INVALID") . "\n";
    }
    
    echo "\nTest completed.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}