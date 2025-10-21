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
        
        // Parcourir de droite à gauche selon l'algorithme GS1 standard
        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = (int)$input[$i];
            $position = $length - $i; // Position depuis la droite (1, 2, 3...)
            
            if ($position % 2 === 1) {
                // Position impaire depuis la droite: multiplier par 3
                $sum += $digit * 3;
            } else {
                // Position paire depuis la droite: multiplier par 1
                $sum += $digit * 1;
            }
        }
        
        $modulo = $sum % 10;
        return ($modulo === 0) ? 0 : 10 - $modulo;
    }
}