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
use App\Validator\TaskValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\DBAL\Types\Type;

class TaskController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private $taskValidator;
  
    public function __construct(EntityManagerInterface $entityManager, TaskValidator $taskValidator)
    {
        $this->entityManager = $entityManager;
        $this->taskValidator = $taskValidator;
    }

    #[Route('/tasks/sub/{nPageParams}/{npRegisParams}/{taskFinished}', name: 'task_list', methods: ['GET'])]
    public function listTasks(int $nPageParams, int $npRegisParams, int $taskFinished, Request $request, TaskRepository $taskRepository, PaginatorInterface $paginator): JsonResponse
    {
        $nPage = $nPageParams;
        $npRegis = $npRegisParams;
        $queryBuilder = $taskRepository->createQueryBuilder('t');
        
        if ($taskFinished === 0 || $taskFinished === 1) {
            $queryBuilder->andWhere('t.taskFinished = :taskFinished')
                ->setParameter('taskFinished', $taskFinished);
        }
    
        $query = $queryBuilder->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', $nPage),
            $npRegis
        );
    
        $responseData = [];
        foreach ($pagination->getItems() as $task) {
            $responseData[] = $task->toArray();
        }
    
        if (empty($responseData)) {
            $responseData = ['message' => 'No data at the moment.'];
        }
       
        return $this->json([
            'message' => 'Complete list, check if the last parameter is 0 or 1 which returns 0 not completed 1 completed',
            'Number of pages'=> $nPage,
            'number of records per page'=>$npRegis,
            'response' => $responseData
        ], 201);
    }    
   
    #[Route('/tasks', methods: ['POST'])]
    public function createTask(Request $request, TaskRepository $taskRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $errors = $this->taskValidator->validatePost($data);
        if (!empty($errors)) {
        return $this->json(['errors' => $errors], 400);
        }
    
        $task = new Task();
        $task->setTitle($data['title']);
        $task->setTaskFinished(0);
        $task->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
        $task->setUpdateAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
  
        foreach ($data['subTasks'] as $subTaskData) {

        $subTaskTitleError = $this->taskValidator->validateSubTaskTitle($subTaskData['title']);
        if ($subTaskTitleError !== null) {
            return $this->json(['message' => $subTaskTitleError], 400);
        }
        
        $subTask = new SubTask();
        $subTask->setTitle($subTaskData['title']);
        $task->addSubTask($subTask);
    }

    $taskRepository->save($task, true);

    return $this->json([
                        'message' => 'task created successfully!',
                        'data' => $task->toArray()
                    ], 201);
    }

    #[Route('/tasks/{id}', methods: ['DELETE'])]
    public function deleteTask(int $id, TaskRepository $taskRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = $taskRepository->find($id);

    if (!$task) {
        return new JsonResponse(['message' => 'task not found!'], 404);
    }

    $entityManager->remove($task);
    $entityManager->flush();

    return new JsonResponse(['message' => 'task deleted successfully!']);
    }

    #[Route('/tasks/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(int $id, Request $request, ManagerRegistry $registry): JsonResponse
    {
        $entityManager = $registry->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        
        if (!$task) {
            return new JsonResponse(['error' => 'task not found!'], 404);
        }
        
        $data = json_decode($request->getContent(), true);  
        $titleError = $this->taskValidator->validatePut($data['title'], $data['TaskFinished']);
        if ($titleError !== null) {
            return $this->json(['message' => $titleError], 400);
        }

        $task->setTitle($data['title']);
        $task->setTaskFinished($data['TaskFinished']);
        $task->setUpdateAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $entityManager->flush();

        return $this->json([
            'message' => 'Task updated successfully!',
            'data' => $task->toArray(),
        ], 200);
    }

    #[Route('/tasks/{id}/subtasks', name: 'create_subtask', methods: ['POST'])]
    public function createSubtask(int $id, Request $request): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'task not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

     
        $errors = $this->taskValidator->validatePost($data);
        if (!empty($errors)) {
        return $this->json(['errors' => $errors], 400);
        }
        
      
        $subTask = new SubTask();
        $subTask->setTitle($data['title']);
        $subTask->setTask($task);

        $this->entityManager->persist($subTask);
        $this->entityManager->flush();

        
        return $this->json([
            'message' => 'Subtask created successfully!',
            'title' => $subTask->getTitle(),
        ], 201);
    } 

    #[Route('/tasks/{taskId}/subtasks/{subtaskId}', name: 'delete_subtask', methods: ['DELETE'])]
    public function deleteSubtask(int $taskId, int $subtaskId): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);

        if (!$task) {
            return new JsonResponse(['error' => 'task not found'], 404);
        }

        $subTask = $this->entityManager->getRepository(SubTask::class)->find($subtaskId);

        if (!$subTask) {
            return new JsonResponse(['error' => 'Subtask not found'], 404);
        }

       
        if ($subTask->getTask() !== $task) {
            return new JsonResponse(['error' => 'Subtask does not belong to specified task!'], 400);
        }

        $this->entityManager->remove($subTask);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Subtask deleted successfully!'], 200);
    }

    


}






