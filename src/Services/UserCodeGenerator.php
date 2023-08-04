<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Repository\TeamRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

final class UserCodeGenerator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TeamRepository $teamRepository,
    ) {}

    public function generate(int $first, int $last): void
    {
        $teams = $this->teamRepository->findAll();

        for ($i = $first; $i < $last; $i++) {
            $code = substr(md5($i . 'SALT_AFUPPPP'), 0, 5);
            $team = $teams[$i % count($teams)];

            $user = (new User())
                ->setUsername($code)
                ->setTeam($team);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}