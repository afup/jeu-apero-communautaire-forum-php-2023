<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UserRegistration
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private TeamRepository $teamRepository,
    ) {}

    public function register(string $code): User
    {
        $user = $this->userRepository->findOneBy(['username' => $code]);

        if (!$user instanceof User) {
            throw new \InvalidArgumentException(RegistrationType::INVALID_CODE_MESSAGE);
        }

        if ($user->getRegisteredAt() instanceof \DateTimeImmutable) {
            throw new \InvalidArgumentException('Joueur⸱se déjà inscrit⸱e');
        }

        $team = $this->teamRepository->findSmallest();

        $user->setRegisteredAt(new \DateTimeImmutable());
        $user->setTeam($team);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}