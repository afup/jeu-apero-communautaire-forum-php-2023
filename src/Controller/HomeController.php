<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FlashRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Security $security, UserRepository $userRepository, FlashRepository $flashRepository): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        $teamId = $user->getTeam()->getId();
        $teamScore = $flashRepository->getTeamScore($teamId);
        $teamPlayers = $userRepository->countRegisteredUsersByTeamId($teamId);
        $teamPercent = round($teamScore['connexions'] / $teamPlayers * 100);

        return $this->render('home/index.html.twig', [
            'usercode' => $user->getUserIdentifier(),
            'team' => $user->getTeam()->getName(),
            'userScore' => $flashRepository->getUserScore($user->getId()),
            'teamConnexions' => $teamScore['connexions'],
            'teamPlayers' => $teamPlayers,
            'teamPercent' => $teamPercent,
            'teamPoints' => $teamScore['points'],
        ]);
    }
}
