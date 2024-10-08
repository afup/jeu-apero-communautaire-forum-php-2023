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

        return $this->render('home/index.html.twig', [
            'usercode' => $user->getUserIdentifier(),
            'name' => $user->getName(),
            'team' => $user->getTeam()->getName(),
            'userScore' => $flashRepository->getUserScore($user->getId()),
            'hasAtleastOneSuccess' => $flashRepository->hasAtleastOneSuccess($user->getId()),
        ]);
    }
}
