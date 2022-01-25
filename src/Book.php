<?php

declare(strict_types=1);

namespace App;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "book")]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: "books")]
    private Author $author;

    #[ORM\ManyToOne(targetEntity: User::class, fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $owner;

    #[ORM\Column(type: "string", unique: true)]
    private string $isbn;

    public function __construct(Author $author, string $isbn)
    {
        $this->author = $author;
        $this->isbn = $isbn;
    }

    public function test(User $owner)
    {
        $this->owner = $owner;
        $this->author->getBooks()->toArray();
        assert(null !== $this->owner);
    }
}
