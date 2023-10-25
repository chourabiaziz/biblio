<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $pubDate = null;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\ManyToMany(targetEntity: Reader::class, inversedBy: 'books')]
    private Collection $reader;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Author $author = null;

    public function __construct()
    {
        $this->reader = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->getTitle();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPubDate(): ?\DateTimeInterface
    {
        return $this->pubDate;
    }

    public function setPubDate(\DateTimeInterface $pubDate): static
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return Collection<int, Reader>
     */
    public function getReader(): Collection
    {
        return $this->reader;
    }

    public function addReader(Reader $reader): static
    {
        if (!$this->reader->contains($reader)) {
            $this->reader->add($reader);
        }

        return $this;
    }

    public function removeReader(Reader $reader): static
    {
        $this->reader->removeElement($reader);

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }
}
