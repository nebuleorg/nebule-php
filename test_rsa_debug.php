<?php
echo "Testing RSASoftware directly...\n\n";

// Inclure seulement le fichier RSASoftware
// Pour contourner les dépendances, je vais extraire la classe RSASoftware
// et la tester de manière isolée

// Lire le fichier
\$content = file_get_contents('/home/bachue/workspace/nebule-php/lib_nebule/203_crypto_software.php');

// Extraire seulement la classe RSASoftware
if (preg_match('/class RSASoftware\s*\{(.*)\}/s', \$content, \$matches)) {
    echo "Found RSASoftware class\n";
    
    // Créer un script avec seulement la classe RSASoftware
    \$rsaCode = '<?php' . "\n\n" . 'class RSASoftwareTest {' . "\n" . \$matches[1] . "\n" . '}' . "\n\n";
    
    // Sauvegarder dans un fichier temporaire pour test
    file_put_contents('/tmp/rsa_test.php', \$rsaCode);
    
    echo "Extracted RSASoftware class to /tmp/rsa_test.php\n";
    
    // Maintenant essayer de l'inclure et de l'instancier
    require_once '/tmp/rsa_test.php';
    
    try {
        \$rsa = new RSASoftwareTest();
        echo "RSASoftware instance created\n";
        
        // Tester avec une petite clé
        echo "Testing key generation with 128 bits...\n";
        \$start = microtime(true);
        \$keys = \$rsa->generateKeyPair(128);
        \$end = microtime(true);
        
        echo "Key generation took " . round(\$end - \$start, 2) . " seconds\n";
        
        if (!empty(\$keys['private'])) {
            echo "Private key: " . substr(\$keys['private'], 0, 50) . "...\n";
        }
        
    } catch (Exception \$e) {
        echo "Error: " . \$e->getMessage() . "\n";
        echo "File: " . \$e->getFile() . ":" . \$e->getLine() . "\n";
    }
    
} else {
    echo "Could not find RSASoftware class\n";
}

echo "Debug test completed.\n";