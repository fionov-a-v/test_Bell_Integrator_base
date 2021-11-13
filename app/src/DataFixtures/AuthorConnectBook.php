<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AuthorConnectBook extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $count = min(AuthorFixtures::AUTHOR_COUNT, BookFixtures::BOOK_COUNT);

        for ($i = 0; $i < $count; $i++) {
            /** @var Book $book */
            $book = $this->getReference(sprintf('%s_%d', BookFixtures::class, $i));
            $book->addAuthor($this->getReference(sprintf('%s_%d', AuthorFixtures::class, $i)));

            if ($i % 100 == 0) {
                $manager->flush();
                $manager->clear();
            }
        }
    }

    public function getDependencies()
    {
        return [
            BookFixtures::class,
            AuthorFixtures::class
        ];
    }
}
