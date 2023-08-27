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
    private int $score;

    #[ORM\Column]
    private ?\DateTimeImmutable $flashedAt;

    public function __construct(User $flasher, User $flashed, bool $isSuccess, int $score)
    {
        $this->flashedAt = new \DateTimeImmutable();
        $this->flasher = $flasher;
        $this->flashed = $flashed;
        $this->isSuccess = $isSuccess;
        $this->score = $score;
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
}
