<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $countersByTeam = $this->userRepository->getCountersByTeam();

        return $this->render('admin/dashboard.html.twig', [
            'counters' => $countersByTeam,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AFUP Jeu apéro');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Scores', 'fa fa-trophy');

        yield MenuItem::section('Équipes & Joueur.se.s');
        yield MenuItem::linkToCrud('Équipes', 'fas fa-users', Team::class);
        yield MenuItem::linkToCrud('Joueur.se.s', 'fas fa-user', User::class);
    }
}
