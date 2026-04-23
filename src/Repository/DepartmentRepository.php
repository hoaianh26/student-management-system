<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Department>
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    // 📦 Load department with students + courses (F2)
    public function findWithDetails(int $id): ?Department
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.students', 's')
            ->leftJoin('d.courses', 'c')
            ->addSelect('s', 'c')
            ->andWhere('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}