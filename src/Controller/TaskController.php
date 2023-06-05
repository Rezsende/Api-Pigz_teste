<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TaskRepository;
use App\Entity\Task;
use Knp\Component\Pager\PaginatorInterface;

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

    #[Route('/task', name: 'task_create', methods:['POST'])]
    public function create(Request $request, TaskRepository $taskRepository): JsonResponse
    {
        $data = $request->request->all();
        
        // Verificar se o campo 'title' está presente e não está vazio
        if (isset($data['title']) && !empty($data['title'])) {
            $task = new Task();
            $task->setTitle($data['title']);
            $task->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
            $task->setUpdateAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
       
            $taskRepository->save($task, true);
       
            return $this->json([
                'message' => 'Task Criada com Sucesso',
                'data'=> $task
            ], 201);
        } else {
            return $this->json([
                'message' => 'O campo "title" é obrigatório',
            ], 400);
        }
    }



    
    
    


    #[Route('/tasks/{pgnum}', name: 'task_pagination', methods:['GET'])]
public function paginateTasks(int $pgnum, Request $request, TaskRepository $taskRepository, PaginatorInterface $paginator): JsonResponse
{

    $number = $pgnum;
    $page = $request->query->getInt('page', $number); 
    $perPage = $request->query->getInt('per_page', 5); 

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



}
