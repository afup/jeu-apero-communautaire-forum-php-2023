<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Flash;
use App\Entity\User;
use App\Exception\GameException;
use App\Repository\FlashRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UserFlash
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly FlashRepository $flashRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function flashUser(User $currentUser, string $code): Flash
    {
        $flashedUser = $this->userRepository->findRegisteredUser($code);

        if (!$flashedUser) {
            throw new GameException('Joueur.se non inscrit.e ou code invalide');
        }

        if ($flashedUser === $currentUser) {
            throw new GameException('Bien tenté !');
        }

        $flash = $this->flashRepository->findOneBy([
            'flasher' => $currentUser,
            'flashed' => $flashedUser,
        ]);

        if ($flash) {
            throw new GameException('Vous avez déjà flashé ce code !');
        }

        $isSuccess = $currentUser->getTeam() === $flashedUser->getTeam();
        $score = (int) $isSuccess * 100;

        $flash = new Flash($currentUser, $flashedUser, $isSuccess, $score);

        $this->entityManager->persist($flash);
        $this->entityManager->flush();

        return $flash;
    }
}