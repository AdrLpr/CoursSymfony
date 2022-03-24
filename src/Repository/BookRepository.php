<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Book $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Book $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Book[] Returns an array of Book objects
     */
    public function findExample()
    {
        // Le language utilisé par se query builder: DQL (Doctrine Query Language)
        // Création d'un constructeur de requête nommé "book"
        $qb = $this->createQueryBuilder('book');

        $qb
            // Limiter les résultat à 10
            ->setMaxResults(10)
            // Ordonner mes résultat: par prix croissant
            ->orderBy('book.price', 'ASC');

        // Définir l'offset de mes résultat
        $qb->setFirstResult(10);

        // Ajouter une condition graçe à where
        $qb->andWhere('book.price >= 5');

        // Utiliser les paramètres:
        $search = "Harr";
        $qb->andWhere('book.title LIKE :title');
        $qb->setParameter('title', '%' . $search . '%');

        // Faire des jointures
        $qb->leftJoin('book.author', 'author');
        $qb->andWhere('author.name = "J.K Rowling"');

        // Tout les livres qui posséde la catégorie avec l'identifiant 1
        $qb->leftJoin('book.categories', 'category');
        $qb->andWhere('category.id = 1');

        // On retourne tout les livres de la requête
        return $qb
            // Génére la requête SQL
            ->getQuery()
            // Récupére les résultats de la requête
            ->getResult(); // array de App\Entity\Book
    }

    /**
     * @return Book[] Returns an array of Book objects
     */

     public function lastFive()
     {
         $qb = $this->createQueryBuilder('book');

         $qb
            ->setMaxResults(5)
            ->orderBy('book.id', 'DESC');

         return $qb
            ->getQuery()
            ->getResult();
     }

     /**
     * @return Book[] Returns an array of Book objects
     */

    public function fiveCheapest()
    {
        $qb = $this->createQueryBuilder('book');

        $qb
           ->setMaxResults(5)
           ->orderBy('book.price', 'ASC');

        return $qb
           ->getQuery()
           ->getResult();
    }

        /**
     * @return Book[] Returns an array of Book objects
     */

    public function byAuthor(int $id)
    {
        $qb = $this->createQueryBuilder('book');

        $qb
           ->setMaxResults(5)
           ->orderBy('book.id', 'DESC');

        // Faire des jointures
        $qb
        ->leftJoin('book.Author', 'author')
        ->andWhere("author.id = :id")
        ->setParameter('id', $id);

        

        return $qb
           ->getQuery()
           ->getResult();
    }

        /**
     * @return Book[] Returns an array of Book objects
     */

    public function titleSearch(string $search)
    {
        $qb = $this->createQueryBuilder('book');

        $qb
           ->setMaxResults(5)
           ->orderBy('book.id', 'ASC')
            ->andWhere('book.title LIKE :title')
            ->setParameter('title', '%' . $search . '%');
        

        return $qb
           ->getQuery()
           ->getResult();
    }

        /**
     * @return Book[] Returns an array of Book objects
     */

    public function search (string $title, int $limit, int $page)
    {
        $qb = $this->createQueryBuilder('book');

        $qb
           ->setMaxResults($limit)
           ->orderBy('book.id', 'ASC')
            ->andWhere('book.title LIKE :title')
            ->setParameter('title', '%' . $title . '%');
        

        return $qb
           ->getQuery()
           ->getResult();
    }
    

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
