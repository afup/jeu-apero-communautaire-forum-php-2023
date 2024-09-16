<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Flash;
use App\Entity\FlashType;
use App\Entity\User;
use App\Exception\GameException;
use App\Repository\FlashRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UserFlash
{
    public function __construct(
        private UserRepository $userRepository,
        private FlashRepository $flashRepository,
        private EntityManagerInterface $entityManager,
        private int $scoreFlashSuccess,
        private int $scoreFlashFailure,
        private int $scoreGoldenTicket,
        private int $scoreFatalErrorTicket,
        private int $moduloGoldenTicket,
        private int $moduloFatalErrorTicket,
    ) {
    }

    /**
     * @throws GameException
     */
    public function flashUser(User $currentUser, string $code): Flash
    {
        $flashedUser = $this->userRepository->findRegisteredUser($code);

        if (!$flashedUser) {
            throw new GameException('Ce·tte SuPHPerhero ne participe pas encore au jeu !');
        }

        if ($flashedUser === $currentUser) {
            throw new GameException('Bien tenté !');
        }

        $flash = $this->createStandardFlash($currentUser, $flashedUser);
        $this->updateFlashIfInstantWin($flash);

        return $flash;
    }

    private function createStandardFlash(User $currentUser, User $flashedUser): Flash
    {
        $existingFlash = $this->flashRepository->findByUsers($currentUser, $flashedUser);
        $sameTeam = $currentUser->getTeam() === $flashedUser->getTeam();

        if ($existingFlash) {
            throw new GameException($sameTeam ?
                'Vous êtes déjà connecté·e avec ce·tte SuPHPerhero !' :
                'Vous avez déjà flashé ce·tte SuPHPerhero !');
        }

        $score = $sameTeam ? $this->scoreFlashSuccess : $this->scoreFlashFailure;
        $flash = new Flash($currentUser, $flashedUser, $sameTeam, $score);

        $this->entityManager->persist($flash);
        $this->entityManager->flush();

        return $flash;
    }

    private function updateFlashIfInstantWin(Flash $flash): void
    {
        if ($flash->getId() % $this->moduloGoldenTicket === 0) {
            $this->updateFlash($flash, FlashType::GOLDEN_TICKET, $this->scoreGoldenTicket);
        } elseif ($flash->getId() % $this->moduloFatalErrorTicket === 0) {
            $this->updateFlash($flash, FlashType::FATAL_ERROR, $this->scoreFatalErrorTicket);
        }
    }

    private function updateFlash(Flash $flash, FlashType $type, int $score): void
    {
        if ($this->flashRepository->findByUserAndType($flash->getFlasher(), $type) instanceof Flash) {
            return;
        }

        $flash->setScore($score);
        $flash->setType($type);

        $this->entityManager->persist($flash);
        $this->entityManager->flush();
    }
}