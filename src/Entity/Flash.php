<?php

namespace App\Entity;

use App\Repository\FlashRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlashRepository::class)]
class Flash
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $flasher;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $flashed;

    #[ORM\Column]
    private bool $isSuccess;

    #[ORM\Column]
    private ?\DateTimeImmutable $flashedAt;

    #[ORM\Column]
    private int $score;

    #[ORM\Column(length: 20)]
    private FlashType $type;

    public function __construct(User $flasher, User $flashed, bool $isSuccess, int $score, FlashType $type)
    {
        $this->flashedAt = new \DateTimeImmutable();
        $this->flasher = $flasher;
        $this->flashed = $flashed;
        $this->isSuccess = $isSuccess;
        $this->score = $score;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFlasher(): User
    {
        return $this->flasher;
    }

    public function getFlashed(): User
    {
        return $this->flashed;
    }

    public function getFlashedAt(): \DateTimeImmutable
    {
        return $this->flashedAt;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getType(): FlashType
    {
        return $this->type;
    }

    public function setType(FlashType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
