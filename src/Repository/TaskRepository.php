<?php

namespace App\Repository;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function save(Task $task, bool $flush = false): void
    {
        $this->getEntityManager()->persist($task);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Task $task, bool $flush = false): void
    {
        $this->getEntityManager()->remove($task);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function addSubTask(Task $task, SubTask $subTask): void
    {
        if (!$task->getSubTasks()->contains($subTask)) {
            $subTask->setTask($task);
            $task->getSubTasks()->add($subTask);
        }
    }


}
