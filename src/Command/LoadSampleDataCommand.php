<?php

namespace App\Command;

use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Student;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:load-sample-data',
    description: 'Loads sample data into the database',
)]
class LoadSampleDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Loading Sample Data');

        // 1. Create Users
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $this->entityManager->persist($admin);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $this->entityManager->persist($user);

        // 2. Create Departments
        $depts = [];
        $deptData = [
            ['IT', 'Information Technology', 'Department of IT'],
            ['BUS', 'Business', 'School of Business'],
            ['ENG', 'Engineering', 'Faculty of Engineering'],
            ['ART', 'Arts & Design', 'Creative Arts'],
        ];

        foreach ($deptData as $data) {
            $dept = new Department();
            $dept->setCode($data[0]);
            $dept->setName($data[1]);
            $dept->setDescription($data[2]);
            $this->entityManager->persist($dept);
            $depts[] = $dept;
        }

        // 3. Create Courses
        $courseData = [
            ['IT101', 'Introduction to Programming', 3, $depts[0]],
            ['IT202', 'Web Development with Symfony', 4, $depts[0]],
            ['BUS101', 'Principles of Management', 3, $depts[1]],
            ['ENG101', 'Advanced Mathematics', 4, $depts[2]],
        ];

        foreach ($courseData as $data) {
            $course = new Course();
            $course->setCode($data[0]);
            $course->setName($data[1]);
            $course->setCredits($data[2]);
            $course->setDepartment($data[3]);
            $this->entityManager->persist($course);
        }

        // 4. Create Students
        $studentNames = [
            ['John', 'Doe'], ['Jane', 'Smith'], ['Michael', 'Brown'],
            ['Emily', 'Davis'], ['William', 'Wilson'], ['Olivia', 'Taylor']
        ];

        foreach ($studentNames as $i => $name) {
            $student = new Student();
            $student->setFirstName($name[0]);
            $student->setLastName($name[1]);
            $student->setEmail(strtolower($name[0] . '.' . $name[1] . $i . '@student.edu'));
            $student->setDepartment($depts[array_rand($depts)]);
            $student->setDateOfBirth(new \DateTime('-' . rand(18, 25) . ' years'));
            $this->entityManager->persist($student);
        }

        $this->entityManager->flush();

        $io->success('Sample data loaded successfully!');
        $io->note('Admin Login: admin@example.com / admin123');
        $io->note('User Login: user@example.com / user123');

        return Command::SUCCESS;
    }
}
