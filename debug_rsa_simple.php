<?php
// Simple debug script to test big integer arithmetic without dependencies

class SimpleBigInt {
    
    public function add(string $a, string $b): string
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

    public function subtract(string $a, string $b): string
    {
        // Handle negative numbers
        $aNeg = strpos($a, '-') === 0;
        $bNeg = strpos($b, '-') === 0;
        
        if ($aNeg && $bNeg) {
            // (-a) - (-b) = b - a
            $a = substr($a, 1);
            $b = substr($b, 1);
            return $this->subtract($b, $a);
        } elseif ($aNeg) {
            // (-a) - b = -(a + b)
            $a = substr($a, 1);
            return '-' . $this->add($a, $b);
        } elseif ($bNeg) {
            // a - (-b) = a + b
            $b = substr($b, 1);
            return $this->add($a, $b);
        }
        
        // Both positive, handle negative results
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

    public function compare(string $a, string $b): int
    {
        $aNeg = strpos($a, '-') === 0;
        $bNeg = strpos($b, '-') === 0;
        
        // Handle negative numbers
        if ($aNeg && !$bNeg) return -1; // Negative < Positive
        if (!$aNeg && $bNeg) return 1;  // Positive > Negative
        if ($aNeg && $bNeg) {
            // Both negative: compare absolute values and reverse
            $a = substr($a, 1);
            $b = substr($b, 1);
            return -1 * $this->compare($a, $b);
        }
        
        // Both positive
        $lenA = strlen($a);
        $lenB = strlen($b);
        
        if ($lenA < $lenB) return -1;
        if ($lenA > $lenB) return 1;
        
        return strcmp($a, $b);
    }

    public function multiply(string $a, string $b): string
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

    public function divide(string $a, string $b): array
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
            while ($this->compare($this->multiply($tempDivisor, '10'), $remainder) <= 0) {
                $tempDivisor = $this->multiply($tempDivisor, '10');
                $tempQuotient = $this->multiply($tempQuotient, '10');
            }
            
            while ($this->compare($remainder, $tempDivisor) >= 0) {
                $remainder = $this->subtract($remainder, $tempDivisor);
                $quotient = $this->add($quotient, $tempQuotient);
            }
        }
        
        return ['quotient' => $quotient, 'remainder' => $remainder];
    }

    public function modulo(string $a, string $b): string
    {
        return $this->divide($a, $b)['remainder'];
    }

    public function modInverse(string $a, string $m): string
    {
        $t0 = '0';
        $t1 = '1';
        $r0 = $m;
        $r1 = $a;
        
        while ($r1 !== '0') {
            $q = $this->divide($r0, $r1)['quotient'];
            
            // t2 = t0 - q * t1
            $t2 = $this->subtract($t0, $this->multiply($q, $t1));
            
            // r2 = r0 - q * r1
            $r2 = $this->subtract($r0, $this->multiply($q, $r1));
            
            // Shift values
            $t0 = $t1;
            $t1 = $t2;
            $r0 = $r1;
            $r1 = $r2;
        }
        
        // If r0 != 1, then a and m are not coprime
        if ($r0 !== '1') {
            throw new Exception('Numbers are not coprime, cannot find modular inverse');
        }
        
        // t0 may be negative, so make it positive
        if ($this->compare($t0, '0') < 0) {
            $t0 = $this->add($t0, $m);
        }
        
        return $t0;
    }
}

$bigInt = new SimpleBigInt();

echo "Testing basic operations:\n\n";

// Test subtract
echo "subtract('20', '10'): " . $bigInt->subtract('20', '10') . "\n";
echo "subtract('10', '20'): " . $bigInt->subtract('10', '20') . "\n";
echo "subtract('3120', '17'): " . $bigInt->subtract('3120', '17') . "\n";

// Test compare
echo "\ncompare('20', '10'): " . $bigInt->compare('20', '10') . "\n";
echo "compare('10', '20'): " . $bigInt->compare('10', '20') . "\n";
echo "compare('17', '3120'): " . $bigInt->compare('17', '3120') . "\n";

// Test divide
echo "\ndivide('3120', '17'): \n";
$result = $bigInt->divide('3120', '17');
echo "  quotient: " . $result['quotient'] . "\n";
echo "  remainder: " . $result['remainder'] . "\n";

// Test modInverse
echo "\nTesting modInverse(17, 3120):\n";
try {
    $result = $bigInt->modInverse('17', '3120');
    echo "Result: " . $result . "\n";
    echo "Expected: 2753\n";
    
    // Verify the result
    $product = $bigInt->multiply('17', $result);
    echo "17 * $result = $product\n";
    
    $mod = $bigInt->modulo($product, '3120');
    echo "17 * $result mod 3120 = $mod (should be 1)\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Manual trace for debugging
echo "\n=== Manual trace of extended Euclidean algorithm for gcd(17, 3120) ===\n";

$r0 = '3120';
$r1 = '17';
$t0 = '0';
$t1 = '1';

$step = 0;
while ($r1 !== '0') {
    $step++;
    echo "\nStep $step:\n";
    echo "  r0 = $r0, r1 = $r1\n";
    echo "  t0 = $t0, t1 = $t1\n";
    
    $q = $bigInt->divide($r0, $r1)['quotient'];
    echo "  q = r0 / r1 = $q\n";
    
    // t2 = t0 - q * t1
    $q_t1 = $bigInt->multiply($q, $t1);
    echo "  q * t1 = $q * $t1 = $q_t1\n";
    $t2 = $bigInt->subtract($t0, $q_t1);
    echo "  t2 = t0 - q * t1 = $t0 - $q_t1 = $t2\n";
    
    // r2 = r0 - q * r1
    $q_r1 = $bigInt->multiply($q, $r1);
    echo "  q * r1 = $q * $r1 = $q_r1\n";
    $r2 = $bigInt->subtract($r0, $q_r1);
    echo "  r2 = r0 - q * r1 = $r0 - $q_r1 = $r2\n";
    
    // Shift values
    $t0 = $t1;
    $t1 = $t2;
    $r0 = $r1;
    $r1 = $r2;
}

echo "\nFinal result:\n";
echo "  r0 = $r0 (should be 1 for coprime numbers)\n";
echo "  t0 = $t0 (this is the modular inverse)\n";