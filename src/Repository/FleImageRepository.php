<?php

namespace App\Repository;

use App\Entity\FleImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FleImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method FleImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method FleImage[]    findAll()
 * @method FleImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FleImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FleImage::class);
    }

    // /**
    //  * @return FleImage[] Returns an array of FleImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FleImage
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
