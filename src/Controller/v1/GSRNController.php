<?php

namespace App\Controller\v1;

use App\Entity\GlobalServiceRelationNumber;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            $required = ['firstname', 'lastname', 'gender', 'phone', 'birthdate', 'projectExternalId', 'reference'];
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


            // Vérifier le format de la date de naissance
            $birthdate = \DateTime::createFromFormat('d/m/Y', $data['birthdate']);
            if (!$birthdate || $birthdate->format('d/m/Y') !== $data['birthdate']) {
                return new JsonResponse(
                    [
                        'code'=> "1",
                        'message' => 'Format de date de naissance invalide. Utilisez le format JJ/MM/AAAA.'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
            // Vérifier la taille de la référence
            if (strlen($data['reference']) > 17) {
                return new JsonResponse(
                    [
                        'code'=> "1",
                        'message' => 'La référence ne doit pas dépasser 17 caractères.'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Génération du GSRN
            
            $gsrn = new GlobalServiceRelationNumber();
            $gsrn->setFirstname($data['firstname']);
            $gsrn->setLastname($data['lastname']);
            $gsrn->setGender($data['gender']);
            $gsrn->setPhone($data['phone']);
            $gsrn->setBirthdate(new \DateTime($data['birthdate']));

            return new JsonResponse([
                'code' => '0',
                'message' => 'GSRN généré avec succès',
                'data' => [
                    "applicationIdentifier" => "414",
                    "internalReference" => "",
                    "gsrn" => "",
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
