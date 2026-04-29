<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    // 🔍 Search by name and department
    public function searchByCriteria(string $name = '', ?int $departmentId = null): array
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.department', 'd')
            ->addSelect('d');

        if ($name !== '') {
            $qb->andWhere('s.firstName LIKE :name OR s.lastName LIKE :name')
               ->setParameter('name', '%' . $name . '%');
        }

        if ($departmentId !== null) {
            $qb->andWhere('s.department = :deptId')
               ->setParameter('deptId', $departmentId);
        }

        return $qb->orderBy('s.lastName', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    // 🏫 Find students by department (F2)
    public function findByDepartment(int $departmentId): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.department = :deptId')
            ->setParameter('deptId', $departmentId)
            ->orderBy('s.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // 📚 Get student with enrollments (optimize query)
    public function findWithEnrollments(int $id): ?Student
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.department', 'd')
            ->leftJoin('s.enrollments', 'e')
            ->leftJoin('e.course', 'c')
            ->addSelect('d', 'e', 'c')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}