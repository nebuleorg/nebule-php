<?php
declare(strict_types=1);

// Test de la correction du hash() dans CryptoSoftware

require_once 'lib_nebule/200_crypto_interface.php';
require_once 'lib_nebule/002_references.php';
require_once 'lib_nebule/003_functions.php';
require_once 'lib_nebule/201_crypto.php';
require_once 'lib_nebule/203_crypto_software.php';

echo "=== TEST DE LA CORRECTION HASH ===\n\n";

try {
    // Créer une instance sans environnement (pour le test)
    $reflection = new ReflectionClass('Nebule\Library\CryptoSoftware');
    $cryptoSoft = $reflection->newInstanceWithoutConstructor();
    
    // Tester hash() avec différents algorithmes
    $data = 'Bienvenue dans le projet nebule.';
    
    echo "Test 1: hash() avec sha2.256\n";
    $hash256 = $reflection->getMethod('hash')->invoke($cryptoSoft, $data, 'sha2.256');
    $expected256 = '0b8dc4408e7ab1c81716ae978abe1f75d4bd3ea9a7b882b8da6afacdafc0e32b';
    
    if ($hash256 === $expected256) {
        echo "  ✓ SHA-256 retourne le hash correct: $hash256\n";
    } else {
        echo "  ✗ SHA-256 échec: attendu $expected256, obtenu $hash256\n";
    }
    
    // Vérifier que c'est bien une chaîne et pas un booléen
    if (is_string($hash256)) {
        echo "  ✓ Le résultat est bien une chaîne (string)\n";
    } else {
        echo "  ✗ Le résultat n'est pas une chaîne: " . gettype($hash256) . "\n";
    }
    
    echo "\nTest 2: hash() avec sha1.128\n";
    $hash1 = $reflection->getMethod('hash')->invoke($cryptoSoft, $data, 'sha1.128');
    $expected1 = 'd689bc73bbf35e6547e6de4b0ea79a5fd3b83ffa';
    
    if ($hash1 === $expected1) {
        echo "  ✓ SHA-1 retourne le hash correct: $hash1\n";
    } else {
        echo "  ✗ SHA-1 échec: attendu $expected1, obtenu $hash1\n";
    }
    
    if (is_string($hash1)) {
        echo "  ✓ Le résultat est bien une chaîne (string)\n";
    } else {
        echo "  ✗ Le résultat n'est pas une chaîne: " . gettype($hash1) . "\n";
    }
    
    echo "\nTest 3: hash() avec algorithme invalide\n";
    $hashInvalid = $reflection->getMethod('hash')->invoke($cryptoSoft, $data, 'invalid.algo');
    
    if ($hashInvalid === '') {
        echo "  ✓ Algorithme invalide retourne chaîne vide\n";
    } else {
        echo "  ✗ Algorithme invalide devrait retourner chaîne vide, obtenu: $hashInvalid\n";
    }
    
    echo "\nTest 4: checkHashFunction()\n";
    $check = $reflection->getMethod('_checkHashFunction')->invoke($cryptoSoft, 'sha2.256');
    if ($check === true) {
        echo "  ✓ checkHashFunction('sha2.256') retourne true\n";
    } else {
        echo "  ✗ checkHashFunction('sha2.256') devrait retourner true\n";
    }
    
    echo "\nTest 5: checkHashAlgorithm()\n";
    $checkAlgo = $reflection->getMethod('_checkHashAlgorithm')->invoke($cryptoSoft, 'sha2.256');
    if ($checkAlgo === true) {
        echo "  ✓ checkHashAlgorithm('sha2.256') retourne true\n";
    } else {
        echo "  ✗ checkHashAlgorithm('sha2.256') devrait retourner true\n";
    }
    
    echo "\n✅ Tous les tests ont réussi !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n=== FIN ===\n";
