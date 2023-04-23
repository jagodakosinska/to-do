<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task', name: 'task-')]
class TaskController extends AbstractController
{
    public function __construct(private TaskRepository $taskRepository)
    {
    }

    #[Route('/{task}', methods: ['GET'], name: 'show')]
    public function show(Task $task): Response
    {
        return new JsonResponse($task->toArray());
    }
}