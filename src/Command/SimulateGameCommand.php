<?php

declare(strict_types=1);

namespace App\Command;

use App\Exception\GameException;
use App\Repository\UserRepository;
use App\Services\UserFlash;
use App\Services\UserRegistration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:dev:simulate',
    description: "[DEV] Simule l'inscription de tous les badges, et certains flashs pour avoir des données dans le classement",
)]
class SimulateGameCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserRegistration $userRegistration,
        private readonly UserFlash $userFlash,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Simulation en cours...');

        $unregisteredUsers = $this->userRepository->findBy(['registeredAt' => null]);

        foreach ($unregisteredUsers as $user) {
            $user->setName('User ' . $user->getId());
            $this->userRegistration->register($user);
        }

        $registeredUsers = $this->userRepository->findBy([], ['id' => 'ASC'], 200);

        foreach ($registeredUsers as $user) {
            $numberOfFlashs = random_int(0,20);
            for ($i = 0; $i < $numberOfFlashs; $i++) {
                $randomFlash = random_int(0, count($registeredUsers) - 1);

                if ($registeredUsers[$randomFlash]->getTeam() !== $user->getTeam() && $i % 5 !== 0) {
                    // On simule que les flashs vers une autre équipe sont moins fréquents que les bons flashs
                    continue;
                }

                try {
                    $this->userFlash->flashUser($user, $registeredUsers[$randomFlash]->getUsername());
                } catch (GameException $e) {
                    // Do nothing
                }
            }
        }

        $output->writeln('Simulation terminée');

        return Command::SUCCESS;
    }
}