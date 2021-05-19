<?php

namespace App\Repository;

use App\Entity\Absences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Absences|null find($id, $lockMode = null, $lockVersion = null)
 * @method Absences|null findOneBy(array $criteria, array $orderBy = null)
 * @method Absences[]    findAll()
 * @method Absences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Absences::class);
    }

    // /**
    //  * @return Absences[] Returns an array of Absences objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Absences
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
