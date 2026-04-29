<?php

namespace App\Command;

use App\Entity\Department;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-more-students',
    description: 'Adds 10 more random students to the database',
)]
class AddMoreStudentsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $departments = $this->entityManager->getRepository(Department::class)->findAll();
        if (empty($departments)) {
            $io->error('No departments found. Please run app:load-sample-data first.');
            return Command::FAILURE;
        }

        $firstNames = ['James', 'Mary', 'Robert', 'Patricia', 'Jennifer', 'Linda', 'Thomas', 'Barbara', 'Christopher', 'Susan'];
        $lastNames = ['Johnson', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez'];

        for ($i = 0; $i < 10; $i++) {
            $student = new Student();
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $student->setFirstName($firstName);
            $student->setLastName($lastName);
            
            // Random unique email
            $email = strtolower($firstName . '.' . $lastName . '.' . rand(1000, 9999) . '@example.edu');
            $student->setEmail($email);
            
            $student->setDepartment($departments[array_rand($departments)]);
            $student->setDateOfBirth(new \DateTime('-' . rand(18, 25) . ' years'));
            
            $this->entityManager->persist($student);
        }

        $this->entityManager->flush();

        $io->success('Added 10 more students successfully!');

        return Command::SUCCESS;
    }
}
