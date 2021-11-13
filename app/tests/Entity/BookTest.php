<?php

namespace App\Tests\Entity;

use App\Entity\Author;
use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{

    private function getBook(): Book
    {
        $book = new Book();
        $book->setTitle('Title');

        return $book;
    }

    public function testGetTitle()
    {
        $this->assertEquals('Title', $this->getBook()->getTitle());
    }

    public function testAddAuthor()
    {
        $book = $this->getBook();
        $author = new Author();
        $author->setId(1);

        $this->assertEmpty($book->getAuthors());

        $book->addAuthor($author);
        $this->assertNotEmpty($book->getAuthors());

        $book->removeAuthor($author);
        $this->assertEmpty($book->getAuthors());
    }

    public function testGetId()
    {
        $this->assertEquals(null, $this->getBook()->getId());
    }
}
