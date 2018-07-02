<?php

namespace App\Repository;

use App\Entity\PlaylistCommentary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlaylistCommentary|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlaylistCommentary|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlaylistCommentary[]    findAll()
 * @method PlaylistCommentary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistCommentaryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlaylistCommentary::class);
    }

//    /**
//     * @return PlaylistCommentary[] Returns an array of PlaylistCommentary objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlaylistCommentary
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
