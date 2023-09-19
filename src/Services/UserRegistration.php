<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\TeamRepository;
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
        $user = $this->userRepository->findOneBy(['username' => $code]);

        if (!$user instanceof User) {
            throw new \InvalidArgumentException(RegistrationType::INVALID_CODE_MESSAGE);
        }

        if ($user->getRegisteredAt() instanceof \DateTimeImmutable) {
            $user->setRegisteredAt(new \DateTimeImmutable());
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}