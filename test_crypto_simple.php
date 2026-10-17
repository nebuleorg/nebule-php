<?php
declare(strict_types=1);

// Test simple des améliorations sans initialisation complète

echo "=== TEST SIMPLE DES AMÉLIORATIONS CRYPTO ===\n\n";

// Test 1: Vérifier la syntaxe des fichiers
echo "Test 1: Vérification de la syntaxe PHP\n";
$files = [
    'lib_nebule/201_crypto.php',
    'lib_nebule/203_crypto_software.php'
];

foreach ($files as $file) {
    $output = [];
    $return = 0;
    exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $return);
    if ($return === 0) {
        echo "  ✓ $file: Syntaxe valide\n";
    } else {
        echo "  ✗ $file: " . implode("\n", $output) . "\n";
    }
}

// Test 2: Vérifier que les constantes sont définies
echo "\nTest 2: Vérification des constantes dans CryptoSoftware\n";
require_once 'lib_nebule/200_crypto_interface.php';
require_once 'lib_nebule/203_crypto_software.php';

$class = new ReflectionClass('Nebule\Library\CryptoSoftware');
$constants = $class->getConstants();

$expectedConstants = [
    'HASH_ALGORITHM',
    'TRANSLATE_HASH_ALGORITHM', 
    'SYMMETRIC_ALGORITHM',
    'TEST_HASH_ALGORITHM'
];

foreach ($expectedConstants as $const) {
    if (isset($constants[$const])) {
        echo "  ✓ $const est défini\n";
    } else {
        echo "  ✗ $const n'est PAS défini\n";
    }
}

// Test 3: Vérifier la structure de la classe Crypto
echo "\nTest 3: Vérification de la structure de Crypto\n";
require_once 'lib_nebule/201_crypto.php';

$cryptoClass = new ReflectionClass('Nebule\Library\Crypto');
$methods = $cryptoClass->getMethods();

$expectedMethods = [
    'setFunctionPreference',
    '_getBestInstanceForFunction',
    '_initializeDefaultPreferences'
];

foreach ($expectedMethods as $method) {
    $found = false;
    foreach ($methods as $m) {
        if ($m->getName() === $method) {
            $found = true;
            break;
        }
    }
    if ($found) {
        echo "  ✓ Méthode $method existe\n";
    } else {
        echo "  ✗ Méthode $method n'existe PAS\n";
    }
}

// Test 4: Vérifier les propriétés
echo "\nTest 4: Vérification des propriétés de Crypto\n";
$properties = $cryptoClass->getProperties();
$propertyNames = [];
foreach ($properties as $p) {
    $propertyNames[$p->getName()] = true;
}

$expectedProperties = ['_functionPreference'];

foreach ($expectedProperties as $prop) {
    if (isset($propertyNames[$prop])) {
        echo "  ✓ Propriété $prop existe\n";
    } else {
        echo "  ✗ Propriété $prop n'existe PAS\n";
    }
}

// Test 5: Vérifier getAlgorithmList dans CryptoSoftware
echo "\nTest 5: Test de getAlgorithmList()\n";
try {
    // Créer une instance basique (sans environnement)
    $cryptoSoft = new ReflectionClass('Nebule\Library\CryptoSoftware');
    $instance = $cryptoSoft->newInstanceWithoutConstructor();
    
    // Appeler getAlgorithmList directement
    $hashAlgos = $cryptoSoft->getMethod('getAlgorithmList')->invoke($instance, 1); // TYPE_HASH = 1
    $symmetricAlgos = $cryptoSoft->getMethod('getAlgorithmList')->invoke($instance, 2); // TYPE_SYMMETRIC = 2
    
    echo "  Algorithmes de hachage: " . implode(', ', $hashAlgos) . "\n";
    echo "  Algorithmes symétriques: " . implode(', ', $symmetricAlgos) . "\n";
    
    if (!empty($hashAlgos) && in_array('sha2.256', $hashAlgos)) {
        echo "  ✓ SHA-256 est dans la liste\n";
    } else {
        echo "  ✗ SHA-256 devrait être dans la liste\n";
    }
    
    if (!empty($symmetricAlgos) && in_array('xor.simple', $symmetricAlgos)) {
        echo "  ✓ xor.simple est dans la liste\n";
    } else {
        echo "  ✗ xor.simple devrait être dans la liste\n";
    }
    
} catch (Exception $e) {
    echo "  ✗ Erreur: " . $e->getMessage() . "\n";
}

// Test 6: Vérifier checkValidAlgorithm
echo "\nTest 6: Test de checkValidAlgorithm()\n";
try {
    $checkHashAlgo = $cryptoSoft->getMethod('checkValidAlgorithm')->invoke($instance, 'sha2.256', 1);
    if ($checkHashAlgo) {
        echo "  ✓ sha2.256 est un algorithme valide\n";
    } else {
        echo "  ✗ sha2.256 devrait être valide\n";
    }
    
    $checkSymmetricAlgo = $cryptoSoft->getMethod('checkValidAlgorithm')->invoke($instance, 'xor.simple', 2);
    if ($checkSymmetricAlgo) {
        echo "  ✓ xor.simple est un algorithme valide\n";
    } else {
        echo "  ✗ xor.simple devrait être valide\n";
    }
    
} catch (Exception $e) {
    echo "  ✗ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DES TESTS ===\n";
