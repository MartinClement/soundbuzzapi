<?php

namespace App\Repository;

use App\Entity\Track;
use App\Utils\TracksUtils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Track|null find($id, $lockMode = null, $lockVersion = null)
 * @method Track|null findOneBy(array $criteria, array $orderBy = null)
 * @method Track[]    findAll()
 * @method Track[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Track::class);
    }

    public function findByOptions($options, $limit = null, $offset = null)
    {

        $query = $this->createQueryBuilder('t');

        foreach ($options as $opt => $value) {

            $query->orderBy('t.' . $opt, $value);
        }

        if (isset($limit)) {

            $query->setMaxResults($limit);

        }

        if (isset($offset)) {

            $query->setFirstResult($offset);
        }

        return $query->getQuery()->getResult();
    }


//    /**
//     * @return Track[] Returns an array of Track objects
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
    public function findOneBySomeField($value): ?Track
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
