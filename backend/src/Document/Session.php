<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: "Session")]
class Session
{
    #[ODM\Id]
    private $id;

    #[Assert\NotBlank]
    #[ODM\Field(type: "string")]
    private string $language;

    #[Assert\NotBlank]
    #[ODM\Field(type: "date")]
    private \DateTime $date;

    #[Assert\NotBlank]
    #[ODM\Field(type: "string")]
    private string $location;

    #[Assert\GreaterThanOrEqual(0)]
    #[ODM\Field(type: "int")]
    private int $availablePlaces;

    public function getId(): ?string { return $this->id; }

    public function getLanguage(): string { return $this->language; }
    public function setLanguage(string $language): self { $this->language = $language; return $this; }

    public function getDate(): \DateTime { return $this->date; }
    public function setDate(\DateTime $date): self { $this->date = $date; return $this; }

    public function getLocation(): string { return $this->location; }
    public function setLocation(string $location): self { $this->location = $location; return $this; }

    public function getAvailablePlaces(): int { return $this->availablePlaces; }
    public function setAvailablePlaces(int $availablePlaces): self { $this->availablePlaces = $availablePlaces; return $this; }
}