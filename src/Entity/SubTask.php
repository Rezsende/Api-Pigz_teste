<?php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'sub_task')]
class SubTask
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'subTasks')]
    private ?Task $task = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
        ];
    }

    public function getFinaldateTask(): ?\DateTimeImmutable
    {
        return $this->finaldateTask;
    }

    public function setFinaldateTask(\DateTimeImmutable $finaldateTask): self
    {
        $this->finaldateTask = $finaldateTask;

        return $this;
    }
}
