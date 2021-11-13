<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TranslateBookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < BookFixtures::BOOK_COUNT; $i++) {
            /** @var Book $book */
            $book = $this->getReference(sprintf('%s_%d', BookFixtures::class, $i));
            $book->setTranslatableLocale('ru');
            $book->setTitle(sprintf('Книга %d', $i));

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
        ];
    }
}
