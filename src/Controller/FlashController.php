<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\GameException;
use App\Services\UserFlash;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlashController extends AbstractController
{
    #[Route('/flash/{code}', name: 'app_flash')]
    public function index(string $code, Security $security, UserFlash $userFlash): Response
    {
        /** @var User $user */
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_register_confirm', ['code64' => base64_encode($code)]);
        }

        try {
            $flash = $userFlash->flashUser($user, $code);
        } catch (GameException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->render('flash/index.html.twig', [
            'code' => $code,
            'flash' => $flash ?? false,
        ]);
    }
}
