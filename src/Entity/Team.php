<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getRegisteredAt(): Collection
    {
        return $this->users;
    }

    public function addRegisteredAt(User $registeredAt): static
    {
        if (!$this->users->contains($registeredAt)) {
            $this->users->add($registeredAt);
            $registeredAt->setTeam($this);
        }

        return $this;
    }

    public function removeRegisteredAt(User $registeredAt): static
    {
        if ($this->users->removeElement($registeredAt)) {
            // set the owning side to null (unless already changed)
            if ($registeredAt->getTeam() === $this) {
                $registeredAt->setTeam(null);
            }
        }

        return $this;
    }
}
