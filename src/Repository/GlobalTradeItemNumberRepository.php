<?php

namespace App\Repository;

use App\Entity\GlobalTradeItemNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GlobalTradeItemNumber>
 */
class GlobalTradeItemNumberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalTradeItemNumber::class);
    }

    public function findLatest($project): ?GlobalTradeItemNumber
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.project = :project')
            ->setParameter('project', $project)
            ->orderBy('g.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
                ->getOneOrNullResult()
            ;
    }

    //    /**
    //     * @return GlobalTradeItemNumber[] Returns an array of GlobalTradeItemNumber objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GlobalTradeItemNumber
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
