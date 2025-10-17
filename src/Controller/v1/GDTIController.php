<?php

namespace App\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rest/v1/gdti')]
final class GDTIController extends AbstractController
{
    #[Route('', name: 'app_v1_gdti_create', methods: ['POST'])]
    public function generateGDTI(
        Request $request
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Here you would typically handle the creation logic,
            // such as validating the data and saving it to the database.

            return new JsonResponse([
                'code' => '0',
                'message' => 'GDTI généré avec succès',
                'data' => [
                    "applicationIdentifier" => "253",
                    "internalReference" => "",
                    "gdti" => "",
                    "barcodeValue" => "(253) 1234567890123"

                ] 
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return new JsonResponse([
                'code' => '2',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
