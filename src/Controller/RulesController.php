<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RulesController extends AbstractController
{
    #[Route('/rules', name: 'app_rules')]
    public function index(): Response
    {
        return $this->render('rules/index.html.twig');
    }
}
