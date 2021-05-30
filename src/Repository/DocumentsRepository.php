<?php

namespace App\Repository;

use App\Entity\Documents;
use App\Entity\Employe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Documents|null find($id, $lockMode = null, $lockVersion = null)
 * @method Documents|null findOneBy(array $criteria, array $orderBy = null)
 * @method Documents[]    findAll()
 * @method Documents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Documents::class);
    }

    public function findByEmploye(Employe $e){
        return $this->createQueryBuilder('d')
        ->andWhere("d.employe = :val")
        ->setParameter('val', $e)
        ->orderBy("d.id", "DESC")
        ->getQuery()
        ->getResult();
    }

    public function findByDestinataire(Employe $e){
        return $this->createQueryBuilder('d')
        ->andWhere("d.destinataire = :val")
        ->setParameter('val', $e)
        ->orderBy("d.id", "DESC")
        ->getQuery()
        ->getResult();
    }
<<<<<<< HEAD

=======
>>>>>>> matthieu
    // /**
    //  * @return Documents[] Returns an array of Documents objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    /*
    public function findOneBySomeField($value): ?Documents
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
