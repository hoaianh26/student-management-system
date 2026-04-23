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
    public function index(EnrollmentRepository $enrollmentRepository): Response
    {
        return $this->render('enrollment/index.html.twig', [
            'enrollments' => $enrollmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'enrollment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $enrollment = new Enrollment();
        
        // Default values if passed via query params (e.g. from student profile)
        $studentId = $request->query->get('student');
        if ($studentId) {
            // ... load student and set to enrollment if needed
        }

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
