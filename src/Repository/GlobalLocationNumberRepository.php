<?php

namespace App\Repository;

use App\Entity\GlobalLocationNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GlobalLocationNumber>
 */
class GlobalLocationNumberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalLocationNumber::class);
    }

    public function findLatest($project): ?GlobalLocationNumber
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
    //     * @return GlobalLocationNumber[] Returns an array of GlobalLocationNumber objects
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

    //    public function findOneBySomeField($value): ?GlobalLocationNumber
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
