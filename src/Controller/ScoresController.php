<?php

namespace App\Controller;

use App\Repository\FlashRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoresController extends AbstractController
{
    #[Route('/scores', name: 'app_scores')]
    public function index(FlashRepository $flashRepository, UserRepository $userRepository): Response
    {
        $scores = $flashRepository->getScoresByTeam();
        $players = $userRepository->countRegisteredUsersByTeam();

        $scoreTable = [];

        foreach ($scores as $team => $score) {
            $scoreTable[] = [
                'team' => $team,
                'connexions' => $score['connexions'],
                'points' => $score['points'],
                'percent' => round($score['connexions'] / $players[$team] * 100),
                'players' => $players[$team],
            ];
        }

        return $this->render('scores/index.html.twig', [
            'scores' => $scoreTable,
        ]);
    }
}
