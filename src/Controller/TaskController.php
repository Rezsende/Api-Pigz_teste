<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TaskRepository;
use App\Entity\Task;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\SubTask;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TaskController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
  

#[Route('/tasks', methods: ['POST'])]
public function createTask(Request $request, TaskRepository $taskRepository): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $task = new Task();
    $task->setTitle($data['title']);
  
    $task->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
    $task->setUpdateAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
  

    foreach ($data['subTasks'] as $subTaskData) {
        $subTask = new SubTask();
        $subTask->setTitle($subTaskData['title']);
        $task->addSubTask($subTask);
    }

    $taskRepository->save($task, true);

    // return new JsonResponse(['message' => 'Tarefa criada com sucesso.'], 201);

    return $this->json([
                        'message' => 'Task Criada com Sucesso',
                        'data' => $task->toArray()
                    ], 201);
}

#[Route('/tasks/sub', name: 'task_list', methods: ['GET'])]
public function listTasks(TaskRepository $taskRepository): JsonResponse
{
    $tasks = $taskRepository->findAll();

    $responseData = [];
    foreach ($tasks as $task) {
        $responseData[] = $task->toArray();
    }

    if (empty($responseData)) {
        $responseData = ['message' => 'no data at the moment..'];
    }

    return new JsonResponse($responseData, 200);
}
#[Route('/tasks/{id}', methods: ['DELETE'])]
public function deleteTask(int $id, TaskRepository $taskRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $task = $taskRepository->find($id);

    if (!$task) {
        return new JsonResponse(['message' => 'Tarefa não encontrada.'], 404);
    }

    $entityManager->remove($task);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Tarefa excluída com sucesso.']);
}

#[Route('/tasks/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(int $id, Request $request, ManagerRegistry $registry): JsonResponse
    {
        $entityManager = $registry->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Tarefa não encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $task->setTitle($data['title']);
        $task->setUpdateAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $entityManager->flush();

        return $this->json([
            'message' => 'Tarefa atualizada com sucesso',
            'data' => $task->toArray(),
        ], 200);
    }



#[Route('/tasks/{id}/subtasks', name: 'create_subtask', methods: ['POST'])]
    public function createSubtask(int $id, Request $request): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Tarefa não encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $subTask = new SubTask();
        $subTask->setTitle($data['title']);
        $subTask->setTask($task);

        $this->entityManager->persist($subTask);
        $this->entityManager->flush();

        // return new JsonResponse(['message' => 'Subtarefa criada com sucesso.'], 201);
        return $this->json([
            'message' => 'Subtarefa criada com sucesso.',
            'title' => $subTask->getTitle(),
        ], 201);
    } 



    #[Route('/tasks/{taskId}/subtasks/{subtaskId}', name: 'delete_subtask', methods: ['DELETE'])]
    public function deleteSubtask(int $taskId, int $subtaskId): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);

        if (!$task) {
            return new JsonResponse(['error' => 'Tarefa não encontrada.'], 404);
        }

        $subTask = $this->entityManager->getRepository(SubTask::class)->find($subtaskId);

        if (!$subTask) {
            return new JsonResponse(['error' => 'Subtarefa não encontrada.'], 404);
        }

       
        if ($subTask->getTask() !== $task) {
            return new JsonResponse(['error' => 'Subtarefa não pertence à tarefa especificada.'], 400);
        }

        $this->entityManager->remove($subTask);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Subtarefa excluída com sucesso.'], 200);
    }




}






