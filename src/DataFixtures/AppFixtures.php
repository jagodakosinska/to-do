<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_EN');
        for ($i = 0; $i < 20; ++$i) {
            $project = new Project();
            $project->setTitle($faker->sentence(3));
            $manager->persist($project);

            $numberOfTasks = $faker->numberBetween(5, 15);
            for ($j = 0; $j < $numberOfTasks; ++$j) {
                $task = new Task();
                $task->setProject($project)
                    ->setDescription($faker->text())
                    ->setCompleted($faker->optional()->randomDigit() >= 8)
                    ->setManDay($faker->optional($weight = 0.5)->randomDigit)
                    ->setDueDate($faker->dateTimeInInterval('-1 week', '+3 days'));
                $manager->persist($task);
            }
        }

        $manager->flush();
    }
}
