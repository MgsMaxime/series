<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    const SERIE_LIMIT = 50;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function save(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBestSeries(int $page)
    {


        //page 1 ->0 - 49
        //page 2 -> 50 - 99

        $offset = ($page - 1) * self::SERIE_LIMIT;

        //En QueryuBuilder
        $qb = $this->createQueryBuilder('s');
        $qb
            //Le leftjoin va permettre de faire la jointure sur les éléments d'instance
            ->leftJoin("s.seasons", "seasons")
            //récupéraation des colonnes de la jointure
            ->addSelect("seasons")
            ->addOrderBy('s.popularity', 'DESC')
            // ->andWhere('s.vote > 8')
            //->andWhere('s.popularity > 100');
            ->setFirstResult($offset)
            ->setMaxResults(self::SERIE_LIMIT);

        $query = $qb->getQuery();
        //Permet de gérer les offset avec jointures
        $paginator = new Paginator($query);

        return $paginator;
    }

//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
