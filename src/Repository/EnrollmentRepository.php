<?php

namespace App\Repository;

use App\Entity\Enrollment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enrollment>
 */
class EnrollmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enrollment::class);
    }

    // 📘 All enrollments for one student
    public function findByStudent(int $studentId): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.course', 'c')
            ->addSelect('c')
            ->andWhere('e.student = :studentId')
            ->setParameter('studentId', $studentId)
            ->orderBy('e.enrolledAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function searchByCriteria(?string $studentName, ?int $courseId, ?string $status, string $sort = 'e.enrolledAt', string $direction = 'DESC'): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.student', 's')
            ->leftJoin('e.course', 'c')
            ->addSelect('s', 'c');

        if ($studentName) {
            $qb->andWhere('s.firstName LIKE :name OR s.lastName LIKE :name OR s.email LIKE :name')
               ->setParameter('name', '%' . $studentName . '%');
        }

        if ($courseId) {
            $qb->andWhere('c.id = :courseId')
               ->setParameter('courseId', $courseId);
        }

        if ($status) {
            $qb->andWhere('e.status = :status')
               ->setParameter('status', $status);
        }

        // Đảm bảo tham số sort là hợp lệ để tránh SQL Injection
        $validSorts = ['s.firstName', 'c.name', 'e.enrolledAt', 'e.grade', 'e.status'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'e.enrolledAt';
        }

        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';

        return $qb->orderBy($sort, $direction)
            ->getQuery()
            ->getResult();
    }

    // 📗 All enrollments for one course
    public function findByCourse(int $courseId): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.student', 's')
            ->addSelect('s')
            ->andWhere('e.course = :courseId')
            ->setParameter('courseId', $courseId)
            ->orderBy('s.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}