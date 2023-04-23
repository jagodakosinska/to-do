<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project', name: 'project-')]
class ProjectController extends AbstractController
{
    public function __construct(private ProjectRepository $projectRepository)
    {
    }

    #[Route('/', methods: ['GET'], name: 'list')]
    public function list(): Response
    {
        $projects = $this->projectRepository->findAll();
        $projects = array_map(fn ($item) => $item->toArray(), $projects);

        return new JsonResponse($projects);
    }

    #[Route('/', methods: ['POST'], name: 'create')]
    public function create(Request $request): Response
    {
        $request = $request->toArray();
        $title = $request['title'] ?? 'empty title';
        $project = new Project();
        $project->setTitle($title);

        $this->projectRepository->save($project, true);

        return new JsonResponse(['id' => $project->getId()]);
    }

}