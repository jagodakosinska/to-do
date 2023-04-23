<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, options: ['default' => ''])]
    private string $title = '';

    /**
     * @var (Collection|ArrayCollection)&iterable<Task>
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Task::class, orphanRemoval: true, fetch: 'EXTRA_LAZY')]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(bool $attachTasks = false): array
    {
        $result = [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
        ];
        if ($attachTasks) {
            $result['tasks'] = array_map(fn ($item) => $item->toArray(), $this->getTasks()->toArray());
        }

        return $result;
    }

}
