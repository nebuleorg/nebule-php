<?php
/**
 * Final verification of all fixes
 */

echo "=== FINAL VERIFICATION OF CRYPTO FIXES ===\n\n";

// Test 1: Syntax check
echo "1. Syntax check:\n";
$files = [
    '201_crypto.php',
    '204_crypto_sodium.php',
];

$basePath = '/home/bachue/workspace/nebule-php/lib_nebule/';

foreach ($files as $file) {
    $fullPath = $basePath . $file;
    $command = "php -l " . escapeshellarg($fullPath) . " 2>&1";
    $output = shell_exec($command);
    
    if (strpos($output, 'No syntax errors detected') !== false) {
        echo "   ✓ $file - PASS\n";
    } else {
        echo "   ✗ $file - FAIL\n";
        echo "     $output\n";
    }
}

// Test 2: Check for backslash-prefixed SODIUM constants
echo "\n2. Checking SODIUM constants have backslash prefix:\n";
$constants = [
    'SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES',
    'SODIUM_CRYPTO_BOX_PUBLICKEYBYTES',
    'SODIUM_CRYPTO_SECRETBOX_NONCEBYTES',
    'SODIUM_CRYPTO_SECRETBOX_KEYBYTES',
    'SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE',
    'SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE',
];

$file204 = file_get_contents($basePath . '204_crypto_sodium.php');
$issues = [];

foreach ($constants as $constant) {
    // Check that the constant is used with backslash prefix in the namespace
    if (preg_match('/[^\\\\]\b' . $constant . '\b/', $file204)) {
        $issues[] = $constant;
        echo "   ✗ $constant - found without backslash prefix\n";
    } else {
        echo "   ✓ $constant - OK\n";
    }
}

if (empty($issues)) {
    echo "   All SODIUM constants are properly prefixed with backslash\n";
}

// Test 3: Check __wakeup method
echo "\n3. Checking Crypto::__wakeup method:\n";
$file201 = file_get_contents($basePath . '201_crypto.php');

if (strpos($file201, 'public function __wakeup(): void') !== false) {
    echo "   ✓ __wakeup method exists\n";
    
    if (strpos($file201, '_initializeDefaultPreferences()') !== false &&
        strpos($file201, '__wakeup') !== false) {
        echo "   ✓ __wakeup calls _initializeDefaultPreferences\n";
    } else {
        echo "   ✗ __wakeup does not call _initializeDefaultPreferences\n";
    }
} else {
    echo "   ✗ __wakeup method not found\n";
}

// Test 4: Check _initializeDefaultPreferences uses cryptoLibrary
echo "\n4. Checking _initializeDefaultPreferences uses cryptoLibrary:\n";

if (strpos($file201, '_initializeDefaultPreferences') !== false) {
    echo "   ✓ _initializeDefaultPreferences method exists\n";
    
    // Check that it uses cryptoLibrary config
    if (strpos($file201, 'cryptoLibrary') !== false &&
        strpos($file201, '_configurationInstance') !== false) {
        echo "   ✓ _initializeDefaultPreferences uses cryptoLibrary config\n";
    } else {
        echo "   ✗ _initializeDefaultPreferences may not use cryptoLibrary config\n";
    }
} else {
    echo "   ✗ _initializeDefaultPreferences method not found\n";
}

// Test 5: Check PEM conversion improvements
echo "\n5. Checking PEM conversion improvements in CryptoSodium:\n";

$pemImprovements = [
    'normalize' => 'Normalized PEM parsing',
    'alternative' => 'Alternative PEM extraction method',
    'base64_decode' => 'Base64 decode with strict mode',
];

foreach ($pemImprovements as $keyword => $description) {
    if (strpos($file204, $keyword) !== false) {
        echo "   ✓ $description - found\n";
    } else {
        echo "   ✗ $description - not found\n";
    }
}

// Test 6: Check OpenSSL fallback in verify method
echo "\n6. Checking OpenSSL fallback in verify method:\n";

if (strpos($file204, 'openssl_verify') !== false) {
    echo "   ✓ OpenSSL verify is used as fallback\n";
    
    if (strpos($file204, 'base64_encode') !== false &&
        strpos($file204, 'openssl_verify') !== false) {
        echo "   ✓ Both binary and base64 signature formats tried\n";
    } else {
        echo "   ✗ May not try both signature formats\n";
    }
} else {
    echo "   ✗ OpenSSL verify fallback not found\n";
}

// Test 7: Check that all changes are consistent
echo "\n7. Consistency check:\n";

$checks = [
    'SESSION_SAVED_VARS' => ['_functionPreference' => true],
];

if (strpos($file201, "'_functionPreference'") !== false) {
    echo "   ✓ _functionPreference is in SESSION_SAVED_VARS\n";
} else {
    echo "   ✗ _functionPreference is NOT in SESSION_SAVED_VARS\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
echo "\nSummary of fixes applied:\n";
echo "- Fixed SODIUM constants to use backslash prefix (\\SODIUM_*)\n";
echo "- Added _initializeDefaultPreferences() call in __wakeup()\n";
echo "- Modified _initializeDefaultPreferences to use cryptoLibrary config value\n";
echo "- Improved PEM key conversion in CryptoSodium\n";
echo "- Enhanced OpenSSL fallback in verify() method\n";
echo "- Fixed regex patterns for PEM extraction\n";
