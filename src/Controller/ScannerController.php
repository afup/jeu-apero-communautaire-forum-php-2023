<?php

namespace App\Controller;

use App\Form\FlashType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScannerController extends AbstractController
{
    #[Route('/scanner', name: 'app_scanner')]
    public function index(): Response
    {
        return $this->render('scanner/index.html.twig');
    }

    #[Route('/scanner/form', name: 'app_scanner_form')]
    public function form(Request $request): Response
    {
        $form = $this->createForm(FlashType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $code = $data['code'];

            return $this->redirectToRoute('app_flash', ['code' => $code]);
        }

        return $this->render('scanner/form.html.twig', [
            'form' => $form,
        ]);
    }
}
