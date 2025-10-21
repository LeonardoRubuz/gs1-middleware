<?php

namespace App\Repository;

use App\Entity\GlobalDocumentTypeIdentifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GlobalDocumentTypeIdentifier>
 */
class GlobalDocumentTypeIdentifierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalDocumentTypeIdentifier::class);
    }
    public function findLatest($project): ?GlobalDocumentTypeIdentifier
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
    //     * @return GlobalDocumentTypeIdentifier[] Returns an array of GlobalDocumentTypeIdentifier objects
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

    //    public function findOneBySomeField($value): ?GlobalDocumentTypeIdentifier
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
