<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @return Sortie[]
     */
    public function findAllWithinOneMonth(): array
    {
        $dateNowLessOneMonth = date('Y-m-d H:i:s', strtotime('-30 days'));
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\sortie s
            WHERE s.dateHeureDebut > :dateNowLessOneMonth'
            )->setParameter('dateNowLessOneMonth', $dateNowLessOneMonth);
        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Sortie[]
     */
    public function findAllWithinOneMonthAndCampus(Campus $campus): array
    {
        $dateNowLessOneMonth = date('Y-m-d H:i:s', strtotime('-30 days'));
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\sortie s
            WHERE s.dateHeureDebut > :dateNowLessOneMonth
            AND s.campus = :campus')
            ->setParameter('dateNowLessOneMonth', $dateNowLessOneMonth)
            ->setParameter('campus', $campus);
        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Sortie[]
     */
    public function findkeywords($nom): array
    {
        //$dateNowLessOneMonth = date('Y-m-d H:i:s', strtotime('-30 days'));
        $entityManager = $this->getEntityManager();
            $query = $entityManager->createQuery(
                "SELECT s
                FROM App\Entity\sortie s
                WHERE s.nom LIKE :nom")
                //->setParameter('dateNowLessOneMonth', $dateNowLessOneMonth)
                ->setParameter('nom', '%'.$nom.'%');
            // returns an array of Product objects


        return $query->getResult();
    }

    /**
     * @return Sortie[]
     */
    public function findperiod($datedebut, $datefin): array
    //affiche les sorties dont la date de sortie est comprise entre la période choisie
    {
        //$dateNowLessOneMonth = date('Y-m-d H:i:s', strtotime('-30 days'));
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\sortie s
            WHERE s.dateHeureDebut BETWEEN :datedebut 
            AND :datefin' )
            //->setParameter('dateNowLessOneMonth', $dateNowLessOneMonth)
            ->setParameter('datedebut', $datedebut)
            ->setParameter('datefin', $datefin);
        // returns an array of Product objects
        return $query->getResult();
    }
    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
