<?php

namespace App\Repository;

use App\Entity\TrackComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TrackComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrackComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrackComment[]    findAll()
 * @method TrackComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrackComment::class);
    }

//    /**
//     * @return TrackComment[] Returns an array of TrackComment objects
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
    public function findOneBySomeField($value): ?TrackComment
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
