<?php

namespace App\Repository;

use App\Entity\EtudiantId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtudiantId|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtudiantId|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtudiantId[]    findAll()
 * @method EtudiantId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtudiantId::class);
    }

    // /**
    //  * @return EtudiantId[] Returns an array of EtudiantId objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtudiantId
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
