<?php
// Test minimal en contournant les dépendances

echo "Testing RSA implementation without framework dependencies...\n\n";

// On va inclure le fichier directement et utiliser reflection pour contourner les dépendances
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

// Vérifions que les classes sont définies
if (class_exists('Nebule\Library\RSASoftware')) {
    echo "RSASoftware class found!\n";
    
    try {
        $rsa = new ReflectionClass('Nebule\Library\RSASoftware');
        $instance = $rsa->newInstanceWithoutConstructor();
        
        // Test des méthodes de base
        if (method_exists($instance, 'add')) {
            echo "RSASoftware has add method.\n";
        }
        
        // Tester la génération de clés avec une petite taille
        echo "Testing key generation...\n";
        
        // Appeler generateKeyPair directement
        $start = microtime(true);
        $keys = $rsa->getMethod('generateKeyPair')->invoke($instance, 128);
        $end = microtime(true);
        
        if (!empty($keys)) {
            echo "Key generation completed in " . round($end - $start, 2) . " seconds\n";
            echo "Private key: " . substr($keys['private'], 0, 50) . "...\n";
            echo "Public key: " . substr($keys['public'], 0, 50) . "...\n";
        } else {
            echo "Key generation failed.\n";
        }
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "RSASoftware class not found.\n";
}

// Tester l'autoloading
spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

echo "Test completed.\n";