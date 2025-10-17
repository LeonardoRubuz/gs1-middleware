<?php 

namespace App\Service;

class CheckDigitCalculator
{
    public function calculateCheckDigit(
        string $input
    ): int
    {
        $sum = 0;
        $length = strlen($input);
        
        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$input[$i];
            // Even position (from the right) gets multiplied by 3 only if length is odd
            
            if ($length % 2 === 1) {
                if (($length - $i) % 2 === 0) {
                    $sum += $digit * 3;
                } else {
                    $sum += $digit;
                }
            } else {
                if (($length - $i) % 2 === 0) {
                    $sum += $digit;
                } else {
                    $sum += $digit * 3;
                }
            }
        }
        
        $modulo = $sum % 10;
        return ($modulo === 0) ? 0 : 10 - $modulo;
    }
}