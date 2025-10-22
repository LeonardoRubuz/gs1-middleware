<?php

namespace App\Controller\v1;

use App\Entity\GlobalLocationNumber;
use App\Repository\GlobalLocationNumberRepository as GLNRepo;
use App\Repository\ProjectRepository;
use App\Service\CheckDigitCalculator;
use App\Service\ReferenceGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rest/v1/gln')]
final class GLNController extends AbstractController
{
    #[Route('/generate', name: 'app_v1_gln_create', methods: ['POST'])]
    public function generateGLN(
        Request $request,
        ProjectRepository $projectRepo,
        ReferenceGenerator $referenceGenerator,
        CheckDigitCalculator $checkDigitCalculator,
        GLNRepo $glnRepo,
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
            $required = ['locationName', 'locationAddress', 'projectExternalId'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return new JsonResponse(['error' => "Le champ '$field' est obligatoire"], Response::HTTP_BAD_REQUEST);
                }
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


            // Génération du GLN
            $lastGLN = $glnRepo->findLatest($project);

            $reference = $referenceGenerator->createNumericalReference(
                $project->getCompanyPrefix(),
                12,
                $lastGLN?->getReference() ?? null
            );

            $checkDigit = $checkDigitCalculator->calculateCheckDigit($project->getCompanyPrefix() . $reference);
            $fullGLN = $project->getCompanyPrefix() . $reference . $checkDigit;

            $gln = new GlobalLocationNumber();
            $gln->setApplicationIdentifier('414');
            $gln->setLocationName($data['locationName']);
            $gln->setLocationAddress($data['locationAddress']);
            if (isset($data['gpsLocation'])) {
                
                $gln->setGps($data['gpsLocation']);
            }
            $gln->setReference($reference);
            $gln->setValue($fullGLN);
            $gln->setProject($project);
            
            return new JsonResponse([
                'code' => '0',
                'message' => 'GLN généré avec succès',
                'data' => [
                    "applicationIdentifier" => "414",
                    "internalReference" => $reference,
                    "gln" => $fullGLN,
                    "barcodeValue" => "(414) " . $fullGLN,
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
