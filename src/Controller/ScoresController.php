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
    public function index(FlashRepository $flashRepository, Security $security): Response
    {
        /** @var User $user */
        $user = $security->getUser();
        $individualScoreTable = $flashRepository->getScoresByUser($user);

        $scoreByTeam = $flashRepository->getScoreByTeam();

        return $this->render('scores/index.html.twig', [
            'teamScores' => $scoreByTeam,
            'individualScores' => $individualScoreTable,
        ]);
    }


}
