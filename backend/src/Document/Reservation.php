<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: "Reservation")]
class Reservation
{
    #[ODM\Id]
    private $id;

    #[ODM\ReferenceOne(targetDocument: User::class)]
    private User $user;

    #[ODM\ReferenceOne(targetDocument: Session::class)]
    private Session $session;

    #[ODM\Field(type: "date")]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?string { return $this->id; }

    public function getUser(): User { return $this->user; }
    public function setUser(User $user): self { $this->user = $user; return $this; }

    public function getSession(): Session { return $this->session; }
    public function setSession(Session $session): self { $this->session = $session; return $this; }

    public function getCreatedAt(): \DateTime { return $this->createdAt; }
}