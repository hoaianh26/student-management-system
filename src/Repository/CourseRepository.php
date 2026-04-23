<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    // 📚 Find courses by department
    public function findByDepartment(int $departmentId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.department = :deptId')
            ->setParameter('deptId', $departmentId)
            ->orderBy('c.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // 🚫 Find courses NOT enrolled by student
    public function findNotEnrolledByStudent(int $studentId): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.enrollments', 'e', 'WITH', 'e.student = :studentId')
            ->andWhere('e.id IS NULL')
            ->setParameter('studentId', $studentId)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}