<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * TODO: с PostgreSQL нужно использовать tsvector, но это доп время, представим что у нас мускул
 * @ORM\Table(
 *     indexes={@ORM\Index(columns={"title"}, flags={"fulltext"})}
 * )
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"book:read", "book:create"})
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=255)
     * @Assert\NotBlank()
     * @Groups({"book:read", "book:create"})
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="books")
     * @Assert\Count(min=1)
     * @Groups({"book:read", "book:create"})
     */
    private $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }

    /**
     * Немного извращений
     */
    public function changeAuthor(Author $authorTo, Author $authorFrom): self
    {
        if ($this->authors->contains($authorTo) && !$this->authors->contains($authorFrom)) {
            $this->authors->set($this->authors->indexOf($authorTo), $authorFrom);
        }
        return $this;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function setAuthors(ArrayCollection $authors): void
    {
        $this->authors = $authors;
    }
}
