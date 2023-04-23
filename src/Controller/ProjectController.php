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

}