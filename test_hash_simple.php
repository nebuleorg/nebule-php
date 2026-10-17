<?php
declare(strict_types=1);

// Test simple de hash() sans environnement complet

require_once 'lib_nebule/200_crypto_interface.php';
require_once 'lib_nebule/203_crypto_software.php';

echo "=== TEST SIMPLE HASH ===\n\n";

// Tester directement la classe SHA256 qui est statique
echo "Test 1: SHA256::hashing() directe\n";
$result = Nebule\Library\SHA256::hashing('Bienvenue dans le projet nebule.', 'hex');
echo "  Résultat: " . (is_bool($result) ? 'bool:' . var_export($result, true) : 'string:"' . $result . '"') . "\n";
echo "  Type: " . gettype($result) . "\n";

// Tester hash() natif de PHP
echo "\nTest 2: hash() natif de PHP\n";
$hash1 = hash('sha256', 'Bienvenue dans le projet nebule.');
echo "  SHA-256 natif: $hash1\n";
$expected256 = '0b8dc4408e7ab1c81716ae978abe1f75d4bd3ea9a7b882b8da6afacdafc0e32b';
echo "  Attendu: $expected256\n";
echo "  Match: " . ($hash1 === $expected256 ? '✓' : '✗') . "\n";

$hash1_2 = hash('sha1', 'Bienvenue dans le projet nebule.');
echo "  SHA-1 natif: $hash1_2\n";
$expected1 = 'd689bc73bbf35e6547e6de4b0ea79a5fd3b83ffa';
echo "  Attendu: $expected1\n";
echo "  Match: " . ($hash1_2 === $expected1 ? '✓' : '✗') . "\n";

echo "\n=== FIN ===\n";
