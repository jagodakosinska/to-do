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
    public function __construct(private ProjectRepository $projectRepository, private TaskRepository $taskRepository)
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

    #[Route('/{project}', methods: ['GET'], name: 'show')]
    public function show(Project $project): Response
    {
        return new JsonResponse($project->toArray(attachTasks: true));
    }

    #[Route('/{project}/task', methods: ['POST'], name: 'create-task')]
    public function createTask(Request $request, Project $project): Response
    {
        $request = $request->toArray();
        $description = $request['description'] ?? 'empty description';
        $dueDate = null;
        if (isset($request['dueDate'])) {
            $dueDate = \DateTime::createFromFormat('Y-m-d', $request['dueDate']);
            if (false == $dueDate) {
                $dueDate = null;
            }
        }
        $manDay = isset($request['manDay']) && is_numeric($request['manDay']) ? $request['manDay'] : null;

        $task = new Task();
        $task->setProject($project)
            ->setDescription($description)
            ->setDueDate($dueDate)
            ->setManDay($manDay);

        $this->taskRepository->save($task, true);

        return new JsonResponse(['id' => $task->getId()]);
    }
    
}
