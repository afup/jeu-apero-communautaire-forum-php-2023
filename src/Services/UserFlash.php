<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;

final class UserFlash
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function flashUser(User $connectedUser, string $code): User
    {
        //Est-ce que le code existe? Est-ce que le/la joueur/se est inscrit/e?
        $flashedUser = $this->userRepository->findRegisteredUser($code);

        if (!$flashedUser) {
            throw new EntityNotFoundException('Joueur.se non inscrit.e ou code invalide');
        }

        //Ajouter la connexion en base si inexistante

        //Retourne le joueur flashé et son équipe
        return $flashedUser;
    }
}