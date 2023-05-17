<?php

namespace App\Command;

use App\Builder\TaskBuilder;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:task-build',
    description: 'Add a short description for your command',
)]
class TaskBuildCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('Choose project from list!');

        $projects = $this->em->getRepository(Project::class)->getList();
        $choices = array_column($projects, 'title', 'id');
        ksort($choices);

        $answer = $io->choice('Select your project', $choices);
        $projectId = array_search($answer, $choices);

        $description = $io->ask('Description of task!');
        $manDay = $io->ask('How many man days does it take?');
        $task = (new TaskBuilder($this->em->getReference(Project::class, $projectId)))
            ->withDescription($description)
            ->withManDay($manDay)
            ->build();
        $this->em->persist($task);
        $this->em->flush();
        $io->success('done');

        return Command::SUCCESS;
    }
}
