<?php

namespace App\Repository;

use App\Entity\TracKCommentary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TracKCommentary|null find($id, $lockMode = null, $lockVersion = null)
 * @method TracKCommentary|null findOneBy(array $criteria, array $orderBy = null)
 * @method TracKCommentary[]    findAll()
 * @method TracKCommentary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TracKCommentaryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TracKCommentary::class);
    }

//    /**
//     * @return TracKCommentary[] Returns an array of TracKCommentary objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TracKCommentary
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
