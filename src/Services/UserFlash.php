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
        private int $scoreNullTicket,
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

        $existingFlash = $this->flashRepository->findByFlasherIdAndFlashedCode($currentUser->getId(), $code);
        $sameTeam = $currentUser->getTeam() === $flashedUser->getTeam();

        if ($existingFlash) {
            throw new GameException($sameTeam ?
                'Vous êtes déjà connecté·e avec ce·tte SuPHPerhero !' :
                'Vous avez déjà flashé ce·tte SuPHPerhero !');
        }

        $type = $this->getFlashType($currentUser, $flashedUser);

        $score = match($type) {
            FlashType::GOLDEN_TICKET => $this->scoreGoldenTicket,
            FlashType::NULL_TICKET => $this->scoreNullTicket,
            default => $sameTeam ? $this->scoreFlashSuccess : $this->scoreFlashFailure,
        };

        $flash = new Flash($currentUser, $flashedUser, $sameTeam, $score, $type);

        $this->entityManager->persist($flash);
        $this->entityManager->flush();

        return $flash;
    }

    public function getFlashType(User $currentUser, User $flashedUser): FlashType
    {
        return $currentUser->getGoldenUsername() === $flashedUser->getUsername()
            ? FlashType::GOLDEN_TICKET
            : ($currentUser->getNullUsername() === $flashedUser->getUsername()
                ? FlashType::NULL_TICKET
                : FlashType::STANDARD);
    }
}