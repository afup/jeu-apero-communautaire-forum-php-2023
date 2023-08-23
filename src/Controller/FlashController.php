<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\UserFlash;
use Doctrine\ORM\EntityNotFoundException;
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

        try {
            $what = $userFlash->flashUser($user, $code);
        } catch (EntityNotFoundException $e) {
            
        }

        return $this->render('flash/index.html.twig', [
            'flashedCode' => $code,
        ]);
    }
}
