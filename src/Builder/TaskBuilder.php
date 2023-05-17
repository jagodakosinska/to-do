<?php

namespace App\Builder;

use App\Entity\Project;
use App\Entity\Task;

class TaskBuilder
{
    private Project $project;
    private string $description;
    private \DateTime $dueDate;
    private string $manDay;



    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->description = '';
        $this->dueDate = new \DateTime('2days');
        $this->manDay = '1';
    }

    public function withDescription(string $text): self
    {
        $this->description = $text;
        return $this;
}

    public function withManDay(string $text)
    {
        $this->manDay = $text;
        return $this;
    }
    public function build(): Task
    {
        $task = (new Task)->setProject($this->project)->setManDay($this->manDay)->setDescription($this->description)->setDueDate($this->dueDate);

        return $task;
    }
}