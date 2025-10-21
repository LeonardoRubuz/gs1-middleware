<?php

namespace App\Controller\v1;

use App\Entity\GlobalServiceRelationNumber;
use App\Repository\GlobalServiceRelationNumberRepository as GSRNRepo;
use App\Repository\ProjectRepository;
use App\Service\CheckDigitCalculator;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ReferenceGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rest/v1/gsrn')]
final class GSRNController extends AbstractController
{
    #[Route('/generate', name: 'app_v1_gsrn_create', methods: ['POST'])]
    public function generateGSRN(
        Request $request,
        ProjectRepository $projectRepo,
        ReferenceGenerator $referenceGenerator,
        CheckDigitCalculator $checkDigitCalculator,
        GSRNRepo $gsrnRepo,
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
            $required = ['firstname', 'lastname', 'gender', 'phone', 'birthdate', 'projectExternalId'];
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


            
            

            // Génération du GSRN
            $lastGSRN = $gsrnRepo->findLatest();
            
            $reference = $referenceGenerator->createNumericalReference(
                $project->getCompanyPrefix(),
                17,
                $lastGSRN?->getReference() ?? null
            );

            $checkDigit = $checkDigitCalculator->calculateCheckDigit($project->getCompanyPrefix() . $reference);
            $fullGSRN = $project->getCompanyPrefix() . $reference . $checkDigit;

            // dd($fullGSRN);

            $gsrn = new GlobalServiceRelationNumber();
            $gsrn->setFirstname($data['firstname']);
            $gsrn->setLastname($data['lastname']);
            if (isset($data['middlename'])) {
                $gsrn->setMiddlename($data['middlename']);
            }
            $gsrn->setGender($data['gender']);
            $gsrn->setPhone($data['phone']);
            
            // Gestion du format de date flexible
            $birthdate = \DateTime::createFromFormat('d/m/Y', $data['birthdate']) 
                ?? \DateTime::createFromFormat('Y-m-d', $data['birthdate'])
                ?? new \DateTime($data['birthdate']);
            
            $gsrn->setBirthdate($birthdate);
            $gsrn->setTitle($data['title'] ?? null);
            $gsrn->setApplicationIdentifier("8018");
            $gsrn->setReference($reference);
            $gsrn->setValue($fullGSRN);
            $gsrn->setProject($project);

            $em->persist($gsrn);
            $em->flush();


            return new JsonResponse([
                'code' => '0',
                'message' => 'GSRN généré avec succès',
                'data' => [
                    "applicationIdentifier" => "8018",
                    "internalReference" => $reference,
                    "gsrn" => $fullGSRN,
                    "barcodeValue" => "(8018) " . $fullGSRN
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
