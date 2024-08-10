<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FlashRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoresController extends AbstractController
{
    #[Route('/scores', name: 'app_scores')]
    public function index(FlashRepository $flashRepository, UserRepository $userRepository, Security $security): Response
    {
        $connexionsByTeam = $flashRepository->countConnexionsByTeam();
        $scoreByTeam = $flashRepository->getScoreByTeam();
        $players = $userRepository->countRegisteredUsersByTeam();

        $scoreTable = [];

        foreach ($scoreByTeam as $team => $score) {
            $scoreTable[] = [
                'team' => $team,
                'connexions' => $connexionsByTeam[$team],
                'points' => $score,
                'percent' => round($connexionsByTeam[$team] / $players[$team] * 100),
                'players' => $players[$team],
            ];
        }

        /** @var User $user */
        $user = $security->getUser();
        $individualScoreTable = $flashRepository->getScoresByUser($user);

        return $this->render('scores/index.html.twig', [
            'teamScores' => $scoreTable,
            'individualScores' => $individualScoreTable,
        ]);
    }


}
