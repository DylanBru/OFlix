<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function add(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupère tous les films et trie le resultat dans l'ordre alphabétique des titres
     * Requêtes customisé avec le Query Builder
     */
    public function findAllOrderByTitleAscQb()
    {
        // Ci dessous on utilise le query builder pour créer une requête DQL similaire a celle ci :
        // SELECT m FROM App\Entity\Movie m ORDER BY m.title ASC
        // La methode setMaxResults(2) permet de limiter le nombre de résultat 
        return $this->createQueryBuilder('m')
            ->add('from', 'App\Entity\Movie m')
            ->add('orderBy', 'm.title ASC')
            ->getQuery()
            ->setMaxResults(2)
            ->getResult();
    }

    /**
     * Get All Movies by search, défault all movies
     */
    public function findAllBySearch(?string $search = null)
    { 
        return $this->createQueryBuilder('m')
            ->orderBy("m.title", "ASC")
            ->where("m.title LIKE :search")
            ->setParameter("search","%$search%")
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * get a random movie
     */
    public function findRandomMovie()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM movie
        ORDER BY RAND()
        LIMIT 1";

        $resultSet = $conn->executeQuery($sql);

         // returns an array of arrays (i.e. a raw data set)
         return $resultSet->fetchAssociative();
    }

    //    public function findOneBySomeField($value): ?Movie
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
