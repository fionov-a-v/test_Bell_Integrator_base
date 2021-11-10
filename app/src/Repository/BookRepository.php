<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM;

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
     * @param Book $book
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     */
    public function save(Book $book): void
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function searchByTitle(string $title)
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->where('lower(b.title) LIKE lower(:search)')
            ->setParameter('search', "%{$title}%")
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
