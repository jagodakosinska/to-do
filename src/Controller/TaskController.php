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

    #[Route('/{task}/complete', methods: ['PUT'], name: 'complete')]
    public function complete(Task $task): Response
    {
        $task->setCompleted(true);
        $this->taskRepository->save($task, true);

        return new JsonResponse($task->toArray());
    }

    #[Route('/dueDate', methods: ['POST'], name: 'list-date')]
    public function getForDate(Request $request): Response
    {
        $request = $request->toArray();
        if (!isset($request['dueDate'])) {
            return new JsonResponse(['error' => 'Missing due date', Response::HTTP_BAD_REQUEST]);
        }

        $dueDate = \DateTime::createFromFormat('Y-m-d', $request['dueDate']);
        if (false == $dueDate) {
            $dueDate = new \DateTime();
        }

        $list = $this->taskRepository->findBy(['dueDate' => $dueDate]);
        $list = array_map(fn ($item) => $item->toArray(), $list);

        return new JsonResponse(['tasks' => $list]);
    }

}

