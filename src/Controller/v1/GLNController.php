<?php

namespace App\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rest/v1/gln', name: 'app_v1_gln_create')]
final class GLNController extends AbstractController
{
    #[Route('/generate', name: 'app_v1_gln_create', methods: ['POST'])]
    public function generateGLN(
        Request $request
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Here you would typically handle the creation logic,
            // such as validating the data and saving it to the database.

            return new JsonResponse([
                'code' => '0',
                'message' => 'GLN généré avec succès',
                'data' => [
                    "applicationIdentifier" => "414",
                    "internalReference" => "",
                    "gln" => "",
                    "barcodeValue" => "(414) 1234567890123"
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
