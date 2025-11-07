<?php 

namespace App\Service;

use App\Repository\GlobalDocumentTypeIdentifierRepository as GDTIRepository;

class ExternalReferenceGenerator
{
    /**
     * Generate a unique GDTI external reference
     * @param GDTIRepository $gdtiRepository
     */
    public function createGDTIRef(GDTIRepository $gdtiRepository): string
    {

        //Generate new reference
        do {
            $newReference = bin2hex(random_bytes(8)); 
            $existingGDTI = $gdtiRepository->findOneBy(['externalReference' => $newReference]);
        } while ($existingGDTI !== null);


        return strtoupper($newReference);
    }
}