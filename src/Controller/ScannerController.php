<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScannerController extends AbstractController
{
    #[Route('/scanner', name: 'app_scanner')]
    public function index(): Response
    {
        return $this->render('scanner/index.html.twig');
    }
}
