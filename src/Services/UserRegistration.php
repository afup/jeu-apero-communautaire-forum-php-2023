<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Exception\GameException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UserRegistration
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
    ) {}

    public function register(string $code): User
    {
        $user = $this->getUser($code);

        if (!$user->getRegisteredAt() instanceof \DateTimeImmutable) {
            $user->setRegisteredAt(new \DateTimeImmutable());
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function getUser(string $code): User
    {
        $user = $this->userRepository->findOneBy(['username' => $code]);

        if (!$user instanceof User) {
            throw new GameException('Code invalide');
        }

        return $user;
    }
}