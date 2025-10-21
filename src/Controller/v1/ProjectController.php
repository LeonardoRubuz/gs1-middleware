<?php

namespace App\Controller\v1;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rest/v1/projects')]
final class ProjectController extends AbstractController
{
    /* private ProjectRepository $repo;

    public function __construct(ProjectRepository $repo_) {
        $this->repo = $repo_;
    } */
    
    #[Route('', name: 'app_v1_project_create', methods: ['POST'])]
    public function createProject(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Here you would typically handle the creation logic,
            if (!isset($data['name']) || trim($data['name']) === '') {
                
                return new JsonResponse([
                    'code' => '1',
                    'message' => 'Le nom du projet est requis.'
                ], Response::HTTP_BAD_REQUEST);
            }
            
            if (!isset($data['companyPrefix']) || trim($data['companyPrefix']) === '') {
                
                return new JsonResponse([
                    'code' => '1',
                    'message' => 'Le préfixe de l\'entreprise est requis.'
                ], Response::HTTP_BAD_REQUEST);
            }

            if (!isset($data['customer']) || trim($data['customer']) === '') {

                return new JsonResponse([
                    'code' => '1',
                    'message' => 'Le client est requis.'
                ], Response::HTTP_BAD_REQUEST);
            }

            $project = new Project();
            $project->setName($data['name']);
            $project->setCustomer($data['customer']);
            $project->setCompanyPrefix($data['companyPrefix']);
            if (isset($data['description'])) {
                $project->setDescription($data['description']);
            }

            $em->persist($project);
            $em->flush();

            return new JsonResponse([
                'code' => '0',
                'message' => 'Projet ' . $data['name'] . ' créé avec succès',
                'externalId' => $project->getExternalId()
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            
            return new JsonResponse([
                'code' => '2',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{projectExternalId}', name: 'app_v1_project_details', methods: ['GET'])]
    public function showDetails(
        Request $request,
        string $projectExternalId,
        ProjectRepository $repo
    ): JsonResponse
    {
        try {
            $project = $repo->findOneBy(['externalId' => $projectExternalId]);

            if (!$project) {
                return new JsonResponse([
                    'code' => '1',
                    'message' => 'Projet non trouvé.'
                ], Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse([
                'name' => $project->getName(),
                'customer' => $project->getCustomer(),
                'description' => $project->getDescription(),
                'externalId' => $project->getExternalId(),
                'datas' => [
                    'gln' => $project->getGlns()->count(),
                    'gdti' => $project->getGdtis()->count(),
                    'gsrn' => $project->getGsrns()->count(),
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {

            return new JsonResponse([
                'code' => '2',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

