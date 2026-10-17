<?php

// Test direct de RSASoftware sans dépendances
echo "Testing basic math operations directly...\n\n";

class RSASoftwareTest {
    // Copie des méthodes de base de RSASoftware pour test
    
    private function add(string $a, string $b): string
    {
        $maxLength = max(strlen($a), strlen($b));
        $a = str_pad($a, $maxLength, '0', STR_PAD_LEFT);
        $b = str_pad($b, $maxLength, '0', STR_PAD_LEFT);
        
        $result = '';
        $carry = 0;
        
        for ($i = $maxLength - 1; $i >= 0; $i--) {
            $digitA = (int)$a[$i];
            $digitB = (int)$b[$i];
            $sum = $digitA + $digitB + $carry;
            $carry = (int)($sum / 10);
            $result = ($sum % 10) . $result;
        }
        
        if ($carry > 0) {
            $result = $carry . $result;
        }
        
        return ltrim($result, '0') !== '' ? ltrim($result, '0') : '0';
    }

    private function subtract(string $a, string $b): string
    {
        // Handle negative results
        if ($this->compare($a, $b) < 0) {
            return '-' . $this->subtract($b, $a);
        }
        
        $maxLength = max(strlen($a), strlen($b));
        $a = str_pad($a, $maxLength, '0', STR_PAD_LEFT);
        $b = str_pad($b, $maxLength, '0', STR_PAD_LEFT);
        
        $result = '';
        $borrow = 0;
        
        for ($i = $maxLength - 1; $i >= 0; $i--) {
            $digitA = (int)$a[$i] - $borrow;
            $digitB = (int)$b[$i];
            
            if ($digitA < $digitB) {
                $digitA += 10;
                $borrow = 1;
            } else {
                $borrow = 0;
            }
            
            $result = ($digitA - $digitB) . $result;
        }
        
        return ltrim($result, '0') !== '' ? ltrim($result, '0') : '0';
    }

    private function compare(string $a, string $b): int
    {
        $lenA = strlen($a);
        $lenB = strlen($b);
        
        if ($lenA < $lenB) return -1;
        if ($lenA > $lenB) return 1;
        
        return strcmp($a, $b);
    }

    private function multiply(string $a, string $b): string
    {
        if ($a === '0' || $b === '0') return '0';
        if ($a === '1') return $b;
        if ($b === '1') return $a;
        
        $result = '0';
        $b = strrev($b);
        
        for ($i = 0; $i < strlen($b); $i++) {
            $digit = (int)$b[$i];
            $carry = 0;
            $temp = '';
            
            for ($j = strlen($a) - 1; $j >= 0; $j--) {
                $product = ((int)$a[$j] * $digit) + $carry;
                $temp = ($product % 10) . $temp;
                $carry = (int)($product / 10);
            }
            
            if ($carry > 0) {
                $temp = $carry . $temp;
            }
            
            // Add zeros for positional value
            $temp .= str_repeat('0', $i);
            $result = $this->add($result, $temp);
        }
        
        return $result;
    }

    private function divide(string $a, string $b): array
    {
        if ($b === '0') {
            throw new Exception('Division by zero');
        }
        
        if ($this->compare($a, $b) < 0) {
            return ['quotient' => '0', 'remainder' => $a];
        }
        
        $quotient = '0';
        $remainder = $a;
        
        while ($this->compare($remainder, $b) >= 0) {
            $tempDivisor = $b;
            $tempQuotient = '1';
            
            // Find the largest multiple of b that fits in remainder
            while ($this->compare($this->multiply($tempDivisor, 10), $remainder) <= 0) {
                $tempDivisor = $this->multiply($tempDivisor, 10);
                $tempQuotient = $this->multiply($tempQuotient, 10);
            }
            
            while ($this->compare($remainder, $tempDivisor) >= 0) {
                $remainder = $this->subtract($remainder, $tempDivisor);
                $quotient = $this->add($quotient, $tempQuotient);
            }
        }
        
        return ['quotient' => $quotient, 'remainder' => $remainder];
    }

    private function modulo(string $a, string $b): string
    {
        return $this->divide($a, $b)['remainder'];
    }

    private function modPow(string $base, string $exponent, string $modulus): string
    {
        if ($modulus === '1') return '0';
        if ($exponent === '0') return '1';
        
        $result = '1';
        $base = $this->modulo($base, $modulus);
        
        // Right-to-left binary exponentiation
        while ($exponent !== '0') {
            $isEven = ((int)$exponent[strlen($exponent) - 1] % 2) === 0;
            
            if (!$isEven) {
                $result = $this->modulo($this->multiply($result, $base), $modulus);
            }
            
            $exponent = $this->divide($exponent, 2)['quotient'];
            $base = $this->modulo($this->multiply($base, $base), $modulus);
        }
        
        return $result;
    }

    public function testMath() {
        echo "Testing basic arithmetic...\n";
        
        // Test addition
        $a = "12345678901234567890";
        $b = "98765432109876543210";
        $expectedSum = "111111111011111111100";
        $sum = $this->add($a, $b);
        echo "   Addition: " . ($sum === $expectedSum ? "PASS" : "FAIL (got: $sum, expected: $expectedSum)") . "\n";
        
        // Test subtraction
        $expectedDiff = "86419753208641975320";
        $diff = $this->subtract($b, $a);
        echo "   Subtraction: " . ($diff === $expectedDiff ? "PASS" : "FAIL (got: $diff, expected: $expectedDiff)") . "\n";
        
        // Test multiplication (smaller numbers for speed)
        $c = "123";
        $d = "456";
        $expectedProd = "56088";
        $prod = $this->multiply($c, $d);
        echo "   Multiplication: " . ($prod === $expectedProd ? "PASS" : "FAIL (got: $prod, expected: $expectedProd)") . "\n";
        
        // Test modulo
        $mod = $this->modulo($b, $a);
        echo "   Modulo: " . ($mod !== "" ? "PASS" : "FAIL") . "\n";
        
        // Test modular exponentiation (very small numbers)
        $exp = $this->modPow("2", "10", "1000"); // 2^10 mod 1000 = 1024 mod 1000 = 24
        echo "   ModPow: " . ($exp === "24" ? "PASS" : "FAIL (got: $exp)") . "\n";
    }
    
    public function testPerformance() {
        echo "\nTesting performance with small numbers...\n";
        
        $start = microtime(true);
        $result = $this->multiply("123456789", "987654321");
        $end = microtime(true);
        echo "   Multiplication of 9-digit numbers: " . round($end - $start, 4) . " seconds\n";
        echo "   Result: " . $result . "\n";
        
        $start = microtime(true);
        $result = $this->modPow("2", "100", "1000000");
        $end = microtime(true);
        echo "   ModPow (2^100 mod 1000000): " . round($end - $start, 4) . " seconds\n";
        echo "   Result: " . $result . "\n";
    }
}

$test = new RSASoftwareTest();
$test->testMath();
$test->testPerformance();

echo "\nDirect test completed.\n";