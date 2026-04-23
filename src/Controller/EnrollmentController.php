<?php

namespace App\Controller;

use App\Entity\Enrollment;
use App\Form\EnrollmentType;
use App\Repository\EnrollmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/enrollments')]
class EnrollmentController extends AbstractController
{
    #[Route('/', name: 'enrollment_index', methods: ['GET'])]
    public function index(Request $request, EnrollmentRepository $enrollmentRepository, \App\Repository\CourseRepository $courseRepository): Response
    {
        $studentName = $request->query->get('student');
        $courseId = $request->query->get('course');
        $status = $request->query->get('status');
        $sort = $request->query->get('sort', 'e.enrolledAt');
        $direction = $request->query->get('direction', 'DESC');

        $enrollments = $enrollmentRepository->searchByCriteria(
            $studentName,
            $courseId ? (int) $courseId : null,
            $status,
            $sort,
            $direction
        );

        return $this->render('enrollment/index.html.twig', [
            'enrollments' => $enrollments,
            'courses' => $courseRepository->findAll(),
            'filters' => [
                'student' => $studentName,
                'course' => $courseId,
                'status' => $status,
                'sort' => $sort,
                'direction' => $direction,
            ]
        ]);
    }

    #[Route('/new', name: 'enrollment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $enrollment = new Enrollment();
        $form = $this->createForm(EnrollmentType::class, $enrollment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($enrollment);
            $entityManager->flush();

            $this->addFlash('success', 'Student enrolled successfully.');

            return $this->redirectToRoute('enrollment_index');
        }

        return $this->render('enrollment/new.html.twig', [
            'enrollment' => $enrollment,
            'form' => $form,
        ]);
    }

    #[Route('/bulk-new', name: 'enrollment_bulk_new', methods: ['GET', 'POST'])]
    #[is_granted('ROLE_ADMIN')]
    public function bulkNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(\App\Form\BulkEnrollmentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $course = $data['course'];
            $students = $data['students'];
            $count = 0;

            foreach ($students as $student) {
                // Check if already enrolled to avoid duplicates
                $existing = $entityManager->getRepository(Enrollment::class)->findOneBy([
                    'student' => $student,
                    'course' => $course
                ]);

                if (!$existing) {
                    $enrollment = new Enrollment();
                    $enrollment->setCourse($course);
                    $enrollment->setStudent($student);
                    $entityManager->persist($enrollment);
                    $count++;
                }
            }

            $entityManager->flush();

            if ($count > 0) {
                $this->addFlash('success', sprintf('Successfully enrolled %d students in %s.', $count, $course->getName()));
            } else {
                $this->addFlash('warning', 'No new enrollments were created (students might already be enrolled).');
            }

            return $this->redirectToRoute('enrollment_index');
        }

        return $this->render('enrollment/bulk_new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'enrollment_show', methods: ['GET'])]
    public function show(Enrollment $enrollment): Response
    {
        return $this->render('enrollment/show.html.twig', [
            'enrollment' => $enrollment,
        ]);
    }

    #[Route('/{id}/edit', name: 'enrollment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enrollment $enrollment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EnrollmentType::class, $enrollment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Enrollment updated successfully.');

            return $this->redirectToRoute('enrollment_index');
        }

        return $this->render('enrollment/edit.html.twig', [
            'enrollment' => $enrollment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'enrollment_delete', methods: ['POST'])]
    public function delete(Request $request, Enrollment $enrollment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enrollment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($enrollment);
            $entityManager->flush();
            $this->addFlash('success', 'Enrollment cancelled successfully.');
        }

        return $this->redirectToRoute('enrollment_index');
    }
}
