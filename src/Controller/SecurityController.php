<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ConfirmationType;
use App\Form\RegistrationType;
use App\Services\UserRegistration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

final class SecurityController extends AbstractController
{
    private const SESSION_PREREGISTERED_CODE = 'preregistered-code';

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, Session $session, Security $security): Response
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
                $session->set(self::SESSION_PREREGISTERED_CODE, $data['usercode']);
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

            return $this->redirectToRoute('app_register_confirm');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/register/confirm', name: 'app_register_confirm')]
    public function confirm(
        Request $request,
        UserRegistration $userRegistration,
        Session $session,
        Security $security,
    ): Response {
        $code = $session->get(self::SESSION_PREREGISTERED_CODE);
        if (!is_string($code) || strlen($code) !== 5) {
            $this->addFlash('danger', 'Erreur technique. Pouvez-vous recommencer svp ?');
            return $this->redirectToRoute('app_register');
        }

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $userRegistration->register($code);
                $security->login($user);

                $this->addFlash('success', 'Merci ! Jouez bien :)');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/confirm.html.twig', [
            'confirmationForm' => $form,
            'usercode' => $code,
        ]);
    }

    #[Route('/logout', name:'app_logout')]
    public function logout(Security $security): Response
    {
        $security->logout(false);

        return $this->redirectToRoute('app_home');
    }
}