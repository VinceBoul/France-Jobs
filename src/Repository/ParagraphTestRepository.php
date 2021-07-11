<?php

namespace App\Repository;

use App\Entity\ParagraphTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParagraphTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphTest[]    findAll()
 * @method ParagraphTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphTest::class);
    }

    // /**
    //  * @return ParagraphTest[] Returns an array of ParagraphTest objects
    //  */
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
    public function findOneBySomeField($value): ?ParagraphTest
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
