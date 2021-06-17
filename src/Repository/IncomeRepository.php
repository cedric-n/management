<?php

namespace App\Repository;

use App\Entity\Income;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Income|null find($id, $lockMode = null, $lockVersion = null)
 * @method Income|null findOneBy(array $criteria, array $orderBy = null)
 * @method Income[]    findAll()
 * @method Income[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncomeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Income::class);
    }

    // /**
    //  * @return IncomeFixtures[] Returns an array of IncomeFixtures objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IncomeFixtures
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function totalIncome(int $id):array
    {


        $qb = $this->createQueryBuilder('i')
            ->select('sum(i.price)')
            ->join('i.budget', 'b', ' b.id = i.budget_id')
            ->where('b.id = :stage')
            ->setParameter('stage',$id);



        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * Search name of budget, date, type of incomes where type can be 1 or 0, and get the sum of it
     * @param string $username
     * @param int $type
     * @return array
     */
    public function searchIncomeByType(string $username,int $type):array
    {


        $qb = $this->createQueryBuilder('i')
            ->select('(b.name) as budgetID' ,'(i.dateAt) as date', '(sum(i.price)) as total', '(i.type) as type')
            ->join('i.budget', 'b', ' b.id = i.budget_id')
            ->join('i.owner','u', 'u.id = i.owner_id')
            ->where('u.email = :username and i.type= :genre')
            ->orderBy('i.dateAt', 'ASC')
            ->groupBy('i.id')
            ->setParameter('username',$username)
            ->setParameter('genre',$type);



        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * Search the sum of Income where type are 1 or 0
     * @param string $username
     * @param int $type
     * @return array
     */
    public function searchSumIncomeByType(string $username,int $type):array
    {


        $qb = $this->createQueryBuilder('i')
            ->select('(sum(i.price)) as total')
            ->join('i.budget', 'b', ' b.id = i.budget_id')
            ->join('i.owner','u', 'u.id = i.owner_id')
            ->where('u.email = :username and i.type= :genre')
            ->setParameter('username',$username)
            ->setParameter('genre', $type);



        $query = $qb->getQuery();

        return $query->execute();
    }

}
