<?php

namespace App\Controller\v1;

use App\Entity\GlobalDocumentTypeIdentifier;
use App\Repository\GlobalDocumentTypeIdentifierRepository as GDTIRepo;
use App\Repository\ProjectRepository;
use App\Service\CheckDigitCalculator;
use App\Service\ReferenceGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rest/v1/gdti')]
final class GDTIController extends AbstractController
{
    #[Route('/generate', name: 'app_v1_gdti_create', methods: ['POST'])]
    public function generateGDTI(
        Request $request,
        ProjectRepository $projectRepo,
        ReferenceGenerator $referenceGenerator,
        CheckDigitCalculator $checkDigitCalculator,
        GDTIRepo $gdtiRepo,
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
            $required = ['documentName', 'externalReference', 'projectExternalId'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return new JsonResponse(['error' => "Le champ '$field' est obligatoire"], Response::HTTP_BAD_REQUEST);
                }
            }

            if (isset($data["externalReference"]) && strlen($data['externalReference']) > 17) {
                
                return new JsonResponse(
                    [
                        'code'=> "1",
                        'message' => 'La référence externe ne peut pas dépasser 17 caractères'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Vérification de l'existence du projet
            $project = $projectRepo->findOneBy(['externalId' => $data['projectExternalId']]);
            
            if (!$project) {
                return new JsonResponse(
                    [
                        'code'=> "1",
                        'message' => 'Projet non trouvé pour l\'externalId fourni'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Génération du GDTI
            $latestGDTI = $gdtiRepo->findLatest($project);

            $reference = $referenceGenerator->createNumericalReference(
                $project->getCompanyPrefix(),
                12,
                lastReference: $latestGDTI?->getReference() ?? null
            );
            
            $checkDigit = $checkDigitCalculator->calculateCheckDigit(
                $project->getCompanyPrefix() . $reference
            );

            $fullGDTI = $project->getCompanyPrefix() . $reference  . $checkDigit . $data['externalReference'];
            
            //dd($fullGDTI);

            $gdti = new GlobalDocumentTypeIdentifier();
            $gdti->setDocumentName($data['documentName']);
            $gdti->setApplicationIdentifier('253');
            $gdti->setExternalReference($data['externalReference']);
            $gdti->setType($data['type']);
            $gdti->setReference($reference);
            $gdti->setValue($fullGDTI);
            $gdti->setProject($project);

            $em->persist($gdti);
            $em->flush();

            return new JsonResponse([
                'code' => '0',
                'message' => 'GDTI généré avec succès',
                'data' => [
                    "applicationIdentifier" => "253",
                    "internalReference" => $reference,
                    "gdti" => $fullGDTI,
                    "barcodeValue" => "(253) " . $fullGDTI,

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
