<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    const AUTHOR_COUNT=10000;

    public function load(ObjectManager $manager): void
    {
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        for ($i = 0; $i < self::AUTHOR_COUNT; $i++) {
            $author = new Author();
            $author->setName(sprintf('Author %d', $i));

            $this->addReference(sprintf('%s_%d', self::class, $i), $author);

            $manager->persist($author);

            if ($i % 100 == 0) {
                $manager->flush();
                $manager->clear();
            }
        }
    }

}