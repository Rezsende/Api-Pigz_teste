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

class TaskController extends AbstractController
{
    private $paginator;
    #[Route('/task', name: 'task_list', methods:['GET'])]
    public function index(TaskRepository $taskRepository): JsonResponse
    {
        return $this->json([
            'data' => $taskRepository->findAll(),
        ], 200);
    }

  
    #[Route('/task', name: 'task_create', methods: ['POST'])]
    public function create(Request $request, TaskRepository $taskRepository): JsonResponse
    {
        $content = $request->getContent();
    
        
        if (!empty($content)) {
            $data = json_decode($content, true);
    
           
            if (isset($data['title']) && !empty($data['title'])) {
                $task = new Task();
                $task->setTitle($data['title']);
                $task->setStTask("Pendente");
                $task->setConcluded(0);
                $task->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
                $task->setUpdateAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
    
                $taskRepository->save($task, true);
    
                return $this->json([
                    'message' => 'Task Criada com Sucesso',
                    'data' => $task->toArray()
                ], 201);
            }
        }
    
        return $this->json([
            'message' => 'Dados inválidos',
        ], 400);
    }
    

    #[Route('/tasks/{pgnum}/{rgnum}', name: 'task_pagination', methods:['GET'])]
public function paginateTasks(int $pgnum,int $rgnum, Request $request, TaskRepository $taskRepository, PaginatorInterface $paginator): JsonResponse
{

    $number = $pgnum;
    $numberg = $rgnum;
    $page = $request->query->getInt('page', $number); 
    $perPage = $request->query->getInt('per_page', $numberg); 

    $pagination = $paginator->paginate(
        $taskRepository->createQueryBuilder('t'),
        $page,
        $perPage
    );

    $tasks = $pagination->getItems();
    $totalItems = $pagination->getTotalItemCount();
    $totalPages = $pagination->getPageCount();

    return $this->json([
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => $totalPages,
        'total_items' => $totalItems,
        'tasks' => $tasks,
    ], 200);
}


#[Route('/task/{taskId}', name: 'task_update', methods:['PUT'])]
public function update(int $taskId, Request $request, ManagerRegistry $doctrine, TaskRepository $taskRepository): JsonResponse
{
    $content = $request->getContent();
    $data = json_decode($content, true);

    if (isset($data['title']) && !empty($data['title'])) {
        $task = $taskRepository->find($taskId);

        if (!$task) {
            return $this->json([
                'message' => 'Tarefa não encontrada',
            ], 404);
        }

        $task->setTitle($data['title']);
        $task->setConcluded($data['concluded']);
        $task->setUpdateAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $doctrine->getManager()->flush();

        return $this->json([
            'message' => 'Tarefa atualizada com sucesso',
            'data'=> $task->toArray(),
        ], 200);
    } else {
        return $this->json([
            'message' => 'O campo "title" é obrigatório',
        ], 400);
    }
}

#[Route('/task/{taskId}', name: 'task_delete', methods:['DELETE'])]
public function delete(int $taskId, TaskRepository $taskRepository, ManagerRegistry $doctrine): JsonResponse
{
    $task = $taskRepository->find($taskId);

    if (!$task) {
        return $this->json([
            'message' => 'Tarefa não encontrada',
        ], 404);
    }

    $entityManager = $doctrine->getManager();
    $entityManager->remove($task);
    $entityManager->flush();

    return $this->json([
        'message' => 'Tarefa excluída com sucesso',
    ], 200);
}


}
