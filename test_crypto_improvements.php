<?php
declare(strict_types=1);

// Test des améliorations apportées aux fichiers crypto

require_once 'lib_nebule/200_crypto_interface.php';
require_once 'lib_nebule/201_crypto.php';
require_once 'lib_nebule/202_crypto_openssl.php';
require_once 'lib_nebule/203_crypto_software.php';

echo "=== TEST DES AMÉLIORATIONS CRYPTO ===\n\n";

// Test 1: Vérifier que les constantes sont bien définies dans CryptoSoftware
echo "Test 1: Constantes dans CryptoSoftware\n";
$constants = (new ReflectionClass('Nebule\Library\CryptoSoftware'))->getConstants();
$expectedConstants = ['HASH_ALGORITHM', 'TRANSLATE_HASH_ALGORITHM', 'SYMMETRIC_ALGORITHM', 'TEST_HASH_ALGORITHM'];

foreach ($expectedConstants as $const) {
    if (isset($constants[$const])) {
        echo "  ✓ $const est défini\n";
    } else {
        echo "  ✗ $const n'est pas défini\n";
    }
}

// Test 2: Vérifier getAlgorithmList()
echo "\nTest 2: CryptoSoftware::getAlgorithmList()\n";
try {
    $crypto = new Nebule\Library\CryptoSoftware();
    $crypto->setEnvironmentLibrary(new Nebule\Library\nebule());
    
    $hashAlgos = $crypto->getAlgorithmList(Nebule\Library\Crypto::TYPE_HASH);
    echo "  Algorithmes de hachage: " . implode(', ', $hashAlgos) . "\n";
    
    $symmetricAlgos = $crypto->getAlgorithmList(Nebule\Library\Crypto::TYPE_SYMMETRIC);
    echo "  Algorithmes symétriques: " . implode(', ', $symmetricAlgos) . "\n";
    
    $asymmetricAlgos = $crypto->getAlgorithmList(Nebule\Library\Crypto::TYPE_ASYMMETRIC);
    echo "  Algorithmes asymétriques: " . (empty($asymmetricAlgos) ? 'aucun' : implode(', ', $asymmetricAlgos)) . "\n";
    
    echo "  ✓ getAlgorithmList() fonctionne\n";
} catch (Exception $e) {
    echo "  ✗ Erreur: " . $e->getMessage() . "\n";
}

// Test 3: Vérifier hash() avec différents algorithmes
echo "\nTest 3: CryptoSoftware::hash()\n";
try {
    $data = 'Bienvenue dans le projet nebule.';
    
    // Test avec SHA-256
    $hash256 = $crypto->hash($data, 'sha2.256');
    $expected256 = '0b8dc4408e7ab1c81716ae978abe1f75d4bd3ea9a7b882b8da6afacdafc0e32b';
    if ($hash256 === $expected256) {
        echo "  ✓ SHA-256: OK (correspond à la valeur attendue)\n";
    } else {
        echo "  ✗ SHA-256: Echec (attendu: $expected256, obtenu: $hash256)\n";
    }
    
    // Test avec SHA-1
    $hash1 = $crypto->hash($data, 'sha1.128');
    $expected1 = 'd689bc73bbf35e6547e6de4b0ea79a5fd3b83ffa';
    if ($hash1 === $expected1) {
        echo "  ✓ SHA-1: OK (correspond à la valeur attendue)\n";
    } else {
        echo "  ✗ SHA-1: Echec (attendu: $expected1, obtenu: $hash1)\n";
    }
    
    // Test avec algorithme non supporté
    $hashInvalid = $crypto->hash($data, 'invalid.algo');
    if ($hashInvalid === '') {
        echo "  ✓ Algorithme non supporté: retourne chaîne vide\n";
    } else {
        echo "  ✗ Algorithme non supporté: devrait retourner chaîne vide\n";
    }
    
} catch (Exception $e) {
    echo "  ✗ Erreur: " . $e->getMessage() . "\n";
}

// Test 4: Vérifier checkFunction() et checkValidAlgorithm()
echo "\nTest 4: Vérifications d'algorithmes\n";
try {
    if ($crypto->checkValidAlgorithm('sha2.256', Nebule\Library\Crypto::TYPE_HASH)) {
        echo "  ✓ sha2.256 est un algorithme de hachage valide\n";
    } else {
        echo "  ✗ sha2.256 devrait être valide\n";
    }
    
    if (!$crypto->checkValidAlgorithm('invalid.algo', Nebule\Library\Crypto::TYPE_HASH)) {
        echo "  ✓ invalid.algo n'est pas un algorithme valide\n";
    } else {
        echo "  ✗ invalid.algo ne devrait pas être valide\n";
    }
    
    if ($crypto->checkValidAlgorithm('xor.simple', Nebule\Library\Crypto::TYPE_SYMMETRIC)) {
        echo "  ✓ xor.simple est un algorithme symétrique valide\n";
    } else {
        echo "  ✗ xor.simple devrait être valide\n";
    }
    
} catch (Exception $e) {
    echo "  ✗ Erreur: " . $e->getMessage() . "\n";
}

// Test 5: Vérifier XOR simple (chiffrement symétrique)
echo "\nTest 5: Chiffrement symétrique XOR\n";
try {
    $data = 'Test de chiffrement XOR';
    $hexKey = '0123456789abcdef0123456789abcdef'; // 32 hex chars = 16 bytes
    
    $encrypted = $crypto->encrypt($data, 'xor.simple', $hexKey);
    if ($encrypted !== '') {
        echo "  ✓ Chiffrement XOR: succès\n";
        
        $decrypted = $crypto->decrypt($encrypted, 'xor.simple', $hexKey);
        if ($decrypted === $data) {
            echo "  ✓ Déchiffrement XOR: succès (correspond aux données originales)\n";
        } else {
            echo "  ✗ Déchiffrement XOR: échec (original: '$data', obtenu: '$decrypted')\n";
        }
    } else {
        echo "  ✗ Chiffrement XOR: a retourné une chaîne vide\n";
    }
    
} catch (Exception $e) {
    echo "  ✗ Erreur: " . $e->getMessage() . "\n";
}

// Test 6: Vérifier getRandom()
echo "\nTest 6: Génération aléatoire\n";
try {
    $random = $crypto->getRandom(32, Nebule\Library\Crypto::RANDOM_PSEUDO);
    if (strlen($random) === 32) {
        echo "  ✓ Génération pseudo-aléatoire: succès (32 octets)\n";
    } else {
        echo "  ✗ Génération pseudo-aléatoire: taille incorrecte (attendu: 32, obtenu: " . strlen($random) . ")\n";
    }
    
    // Strong random devrait retourner vide (pas implémenté dans Software)
    $strongRandom = $crypto->getRandom(32, Nebule\Library\Crypto::RANDOM_STRONG);
    if ($strongRandom === '') {
        echo "  ✓ Génération aléatoire forte: retourne vide (comme attendu)\n";
    } else {
        echo "  ✗ Génération aléatoire forte: devrait retourner vide\n";
    }
    
} catch (Exception $e) {
    echo "  ✗ Erreur: " . $e->getMessage() . "\n";
}

// Test 7: Vérifier que les méthodes non supportées retournent des valeurs vides
echo "\nTest 7: Méthodes non supportées\n";
try {
    $sign = $crypto->sign('test', 'private key', 'password');
    if ($sign === '') {
        echo "  ✓ sign(): retourne chaîne vide (non supporté)\n";
    }
    
    $verify = $crypto->verify('test', 'sign', 'public key', 'sha2.256');
    if ($verify === false) {
        echo "  ✓ verify(): retourne false (non supporté)\n";
    }
    
    $encryptTo = $crypto->encryptTo('test', null);
    if ($encryptTo === '') {
        echo "  ✓ encryptTo(): retourne chaîne vide (non supporté)\n";
    }
    
} catch (Exception $e) {
    echo "  ✗ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DES TESTS ===\n";
