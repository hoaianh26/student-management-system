<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/students/search', name: 'api_student_search', methods: ['GET'])]
    public function searchStudents(Request $request, StudentRepository $studentRepository): JsonResponse
    {
        $searchTerm = $request->query->get('q', '');
        // Limit results for instant search
        $students = $studentRepository->searchByCriteria($searchTerm);

        $isAdmin = $this->isGranted('ROLE_ADMIN');

        $data = array_map(function ($student) use ($isAdmin) {
            return [
                'id' => $student->getId(),
                'firstName' => $student->getFirstName(),
                'lastName' => $student->getLastName(),
                'email' => $student->getEmail(),
                'departmentCode' => $student->getDepartment() ? $student->getDepartment()->getCode() : 'N/A',
                'dateOfBirth' => $student->getDateOfBirth() ? $student->getDateOfBirth()->format('Y-m-d') : 'N/A',
                'showUrl' => $this->generateUrl('student_show', ['id' => $student->getId()]),
                'editUrl' => $isAdmin ? $this->generateUrl('student_edit', ['id' => $student->getId()]) : null,
            ];
        }, $students);

        return new JsonResponse($data);
    }
}
