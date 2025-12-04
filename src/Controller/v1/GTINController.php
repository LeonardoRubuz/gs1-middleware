<?php

namespace App\Controller\v1;

use App\Entity\GlobalTradeItemNumber;
use App\Repository\GlobalTradeItemNumberRepository as GTINRepo;
use App\Repository\ProjectRepository;
use App\Service\CheckDigitCalculator;
use App\Service\ExternalReferenceGenerator;
use App\Service\ReferenceGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rest/v1/gtin', name: 'app_v1_gtin')]
final class GTINController extends AbstractController
{
    #[Route('/generate', name: '_create', methods: ['POST'])]
    public function generateGTIN(
        Request $request,
        ProjectRepository $projectRepo,
        ReferenceGenerator $referenceGenerator,
        CheckDigitCalculator $checkDigitCalculator,
        GTINRepo $gtinRepo,
        ExternalReferenceGenerator $extRef,
        EntityManagerInterface $em
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Vérification de l existence des données
            if (!$data) {
                
                return new JsonResponse(
                    [
                        'code'=> "1",
                        'message' => 'Données manquantes ou invalides'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Vérification des champs obligatoires
            $required = ['itemName',  'projectExternalId'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return new JsonResponse(['error' => "Le champ '$field' est obligatoire"], Response::HTTP_BAD_REQUEST);
                }
            }

            

            // Vérification de l'existence du projet
            $project = $projectRepo->findByExternalIdOrGCP($data['projectExternalId']);
            
            if (!$project) {
                return new JsonResponse(
                    [
                        'code'=> "1",
                        'message' => 'Projet non trouvé pour l\'externalId fourni'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Génération du GTIN
            $latestGTIN = $gtinRepo->findLatest($project);

            $reference = $referenceGenerator->createNumericalReference(
                $project->getCompanyPrefix(),
                14,
                lastReference: $latestGTIN?->getReference() ?? null
            );
            
            $checkDigit = $checkDigitCalculator->calculateCheckDigit(
                $project->getCompanyPrefix() . $reference
            );

            $fullGTIN = $project->getCompanyPrefix() . $reference  . $checkDigit;
            
            // dd($fullGTIN);

            $gtin = new GlobalTradeItemNumber();
            $gtin->setItemName($data['itemName']);
            $gtin->setApplicationIdentifier('01');
            $gtin->setReference($reference);
            $gtin->setValue($fullGTIN);
            $gtin->setProject($project);

            $em->persist($gtin);
            $em->flush();

            return new JsonResponse([
                'code' => '0',
                'message' => 'GTIN généré avec succès',
                'data' => [
                    "applicationIdentifier" => "01",
                    "internalReference" => $reference,
                    "gtin" => $fullGTIN,
                    "barcodeValue" => "(01) " . $fullGTIN,

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
