<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name:'task')]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $update_at = null;

   

    #[ORM\Column(length: 255)]
    private ?string $stTask = null;

    #[ORM\Column]
    private ?float $concluded = null;

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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            // Adicione aqui outras propriedades que deseja incluir no array
        ];
    }



    public function getStTask(): ?string
    {
        return $this->stTask;
    }

    public function setStTask(string $stTask): self
    {
        $this->stTask = $stTask;

        return $this;
    }

   
    public function getConcluded(): ?float
    {
        return $this->concluded;
    }

    public function setConcluded(float $concluded): self
    {
        $this->concluded = $concluded;

        return $this;
    }

 

}
