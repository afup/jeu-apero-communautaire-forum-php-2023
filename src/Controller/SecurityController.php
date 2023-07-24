<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Services\UserRegistration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SecurityController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserRegistration $userRegistration, Security $security): Response
    {
        /** @var User $user */
        $user = $security->getUser();
        if ($user instanceof User) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $user = $userRegistration->register($data['usercode']);

                $security->login($user);

                $this->addFlash('success', 'Inscription confirmée ! Jouez bien :)');

            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/logout', name:'app_logout')]
    public function logout(Security $security): Response
    {
        $security->logout(false);

        return $this->redirectToRoute('app_home');
    }
}