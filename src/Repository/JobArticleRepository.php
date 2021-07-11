<?php

namespace App\Repository;

use App\Entity\JobArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobArticle[]    findAll()
 * @method JobArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobArticle::class);
    }

    // /**
    //  * @return JobArticle[] Returns an array of JobArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JobArticle
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
