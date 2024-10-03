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
        private readonly int $scoreFlashSuccess,
        private readonly int $scoreFlashFailure,
    ) {}

    public function flashUser(User $currentUser, string $code): Flash
    {
        $flashedUser = $this->userRepository->findRegisteredUser($code);

        if (!$flashedUser) {
            throw new GameException('Ce·tte SuPHPerhero ne participe pas encore au jeu !');
        }

        if ($flashedUser === $currentUser) {
            throw new GameException('Bien tenté !');
        }

        $existingFlash = $this->flashRepository->findByFlasherIdAndFlashedCode($currentUser->getId(), $code);

        $isSuccess = $currentUser->getTeam() === $flashedUser->getTeam();
        $score = $isSuccess ? $this->scoreFlashSuccess : $this->scoreFlashFailure;

        if ($existingFlash) {
            throw new GameException($isSuccess ?
                    'Vous êtes déjà connecté·e avec ce·tte SuPHPerhero !' :
                    'Vous avez déjà flashé ce·tte SuPHPerhero !');
        }

        $flash = new Flash($currentUser, $flashedUser, $isSuccess, $score);

        $this->entityManager->persist($flash);
        $this->entityManager->flush();

        return $flash;
    }
}