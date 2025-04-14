<?php

namespace App\Repository;

use App\Entity\EmployeAudit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmployeAudit>
 */
class EmployeAuditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeAudit::class);
    }


    public function CountByTypeAction(string $type_action): int
   {
     return $this->createQueryBuilder('p')
        ->select('Count(p.id)')
        ->where('p.type_action = :type_action')
        ->setParameter('type_action', $type_action)
        ->getQuery()
        ->getSingleScalarResult();
   }

    //    /**
    //     * @return EmployeAudit[] Returns an array of EmployeAudit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EmployeAudit
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
