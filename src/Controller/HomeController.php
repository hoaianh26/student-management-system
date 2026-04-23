<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EnrollmentRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        StudentRepository $studentRepository,
        DepartmentRepository $departmentRepository,
        CourseRepository $courseRepository,
        EnrollmentRepository $enrollmentRepository
    ): Response {
        return $this->render('home/index.html.twig', [
            'stats' => [
                'students'    => $studentRepository->count([]),
                'departments' => $departmentRepository->count([]),
                'courses'     => $courseRepository->count([]),
                'enrollments' => $enrollmentRepository->count([]),
            ],
            'recent_students' => $studentRepository->findBy([], ['createdAt' => 'DESC'], 5),
        ]);
    }

    #[Route('/about', name: 'home_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }
}