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

    public function dataSumByType1(string $username):array
    {


        $qb = $this->createQueryBuilder('i')
            ->select('(b.name) as budgetID' ,'(b.createdAt) as date', '(sum(i.price)) as total', '(b.type) as type')
            ->join('i.budget', 'b', ' b.id = i.budget_id')
            ->join('i.owner','u', 'u.id = i.owner_id')
            ->where('u.email = :username and b.type=1')
            ->orderBy('b.createdAt', 'ASC')
            ->groupBy('b.id')
            ->setParameter('username',$username);



        $query = $qb->getQuery();

        return $query->execute();
    }
    public function dataSumByType12(string $username):array
    {


        $qb = $this->createQueryBuilder('i')
            ->select('(b.name) as budgetID' ,'(i.dateAt) as date', '(sum(i.price)) as total', '(i.type) as type')
            ->join('i.budget', 'b', ' b.id = i.budget_id')
            ->join('i.owner','u', 'u.id = i.owner_id')
            ->where('u.email = :username and i.type=1')
            ->orderBy('i.dateAt', 'ASC')
            ->groupBy('i.id')
            ->setParameter('username',$username);



        $query = $qb->getQuery();

        return $query->execute();
    }

    public function dataSumByType02(string $username):array
    {


        $qb = $this->createQueryBuilder('i')
            ->select('(b.name) as budgetID' ,'(i.dateAt) as date', '(sum(i.price)) as total', '(i.type) as type')
            ->join('i.budget', 'b', ' b.id = i.budget_id')
            ->join('i.owner','u', 'u.id = i.owner_id')
            ->where('u.email = :username and i.type=0')
            ->orderBy('i.dateAt', 'ASC')
            ->groupBy('i.id')
            ->setParameter('username',$username);



        $query = $qb->getQuery();

        return $query->execute();
    }

    public function dataSumByType2(string $username):array
    {


        $qb = $this->createQueryBuilder('i')
            ->select('(b.name) as budgetID' ,'(b.createdAt) as date', '(sum(i.price)) as total', '(b.type) as type')
            ->join('i.budget', 'b', ' b.id = i.budget_id')
            ->join('i.owner','u', 'u.id = i.owner_id')
            ->where('u.email = :username and b.type=0')
            ->orderBy('b.createdAt', 'ASC')
            ->groupBy('b.id')
            ->setParameter('username',$username);



        $query = $qb->getQuery();

        return $query->execute();
    }

    public function totalSumByType0(string $username,int $type):array
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
