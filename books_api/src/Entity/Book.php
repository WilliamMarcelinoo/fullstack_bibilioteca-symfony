<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "books")]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $author = null;

    #[ORM\Column(type: "integer")]
    private ?int $year = null;

    // Getters e Setters
    public function getId(): ?int { return $this->id; }
    
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }

    public function getAuthor(): ?string { return $this->author; }
    public function setAuthor(string $author): self { $this->author = $author; return $this; }

    public function getYear(): ?int { return $this->year; }
    public function setYear(int $year): self { $this->year = $year; return $this; }
}
