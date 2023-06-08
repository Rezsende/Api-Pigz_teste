<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\SubTask;


#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: 'task')]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $update_at = null;
    
    #[ORM\OneToMany(targetEntity: SubTask::class, mappedBy: 'task', cascade: ['persist', 'remove'])]
    private Collection $subTasks;

    #[ORM\Column]
    private ?int $taskFinished = null;

    public function __construct()
    {
        $this->subTasks = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->update_at = new \DateTimeImmutable();
    }

    // Getters and Setters

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->update_at;
    }

    public function setUpdateAt(\DateTimeImmutable $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function addSubTask(SubTask $subTask): self
    {
        if (!$this->subTasks->contains($subTask)) {
            $subTask->setTask($this);
            $this->subTasks->add($subTask);
        }

        return $this;
    }

    /**
     * @return Collection|SubTask[]
     */
    public function getSubTasks(): Collection
    {
        return $this->subTasks;
    }
    public function toArray(): array
    {
        $subTasksData = [];
    foreach ($this->getSubTasks() as $subTask) {
        $subTasksData[] = $subTask->toArray();
    }
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'TaskFinished'=> $this->getTaskFinished(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updateAt' => $this->getUpdateAt()->format('Y-m-d H:i:s'),
            'subTasks' => $subTasksData,
        ];
    }

    public function getTaskFinished(): ?int
    {
        return $this->taskFinished;
    }

    public function setTaskFinished(int $taskFinished): self
    {
        $this->taskFinished = $taskFinished;

        return $this;
    }
}
