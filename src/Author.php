<?php

declare(strict_types=1);

namespace App;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "author")]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\OneToMany(mappedBy: "author", targetEntity: Book::class)]
    private Collection $books;

    public function getBooks(): Collection
    {
        return $this->books;
    }
}
