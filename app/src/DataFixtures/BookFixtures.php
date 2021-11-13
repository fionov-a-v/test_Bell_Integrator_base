<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    const BOOK_COUNT = 10000;

    public function load(ObjectManager $manager): void
    {
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        for ($i = 0; $i < self::BOOK_COUNT; $i++) {
            $book = new Book();
            $book->setTitle(sprintf('Book %d', $i));

            $this->addReference(sprintf('%s_%d', self::class, $i), $book);

            $manager->persist($book);

            if ($i % 100 == 0) {
                $manager->flush();
                $manager->clear();
            }
        }
    }
}
