<?php 

namespace App\Service;

class ReferenceGenerator 
{
    /**
     * Génère une référence numérique en fonction
     * du préfixe, de la dernière référence et de la longueur totale souhaitée.
     */
    public function createNumericalReference(
        string $prefix,
        int $expectedLength,
        ?string $lastReference
    ): string
    {
        $referenceLength = $expectedLength - strlen($prefix);
        
        if ($lastReference) {
            // La lastReference est déjà la partie numérique sans préfixe
            // Retirer tous les "0" initiaux pour obtenir la partie numérique
            $numericPart = ltrim($lastReference, '0');
            // Si la partie numérique est vide (que des zéros), on commence à 1
            $nextNumber = $numericPart === '' ? 1 : (int)$numericPart + 1;
        } else {
            $nextNumber = 1;
        }
        
        // Formater le numéro avec des zéros à gauche selon la longueur requise
        $formattedNumber = str_pad($nextNumber, $referenceLength, '0', STR_PAD_LEFT);
        
        return $formattedNumber;
    }
}