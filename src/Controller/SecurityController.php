<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\GameException;
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
    public function register(Request $request, Security $security): Response
    {
        /** @var User $user */
        $user = $security->getUser();
        if ($user instanceof User) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $code64 = base64_encode($data['code']);

            return $this->redirectToRoute('app_register_confirm', ['code64' => $code64]);
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/register/scanner', name: 'app_register_scanner')]
    public function scanner(): Response
    {
        return $this->render('scanner/index.html.twig');
    }

    #[Route('/register/{code64}', name: 'app_register_confirm')]
    public function confirm(UserRegistration $userRegistration, string $code64): Response {
        try {
            $code = base64_decode($code64);
            $user = $userRegistration->getUser($code);
        } catch (GameException $exception) {
            $this->addFlash('danger', $exception->getMessage());
            return $this->redirectToRoute('app_register');
        }

        return $this->render('security/confirm.html.twig', [
            'code' => $user->getUsername(),
            'code64' => $code64,
        ]);
    }

    #[Route('/register/{code64}/validate', name: 'app_register_validate')]
    public function validate(UserRegistration $userRegistration, Security $security, string $code64): Response
    {
        try {
            $code = base64_decode($code64);
            $user = $userRegistration->register($code);
        } catch (GameException $exception) {
            $this->addFlash('danger', $exception->getMessage());
            return $this->redirectToRoute('app_register');
        }

        $security->login($user);
        $this->addFlash('success', 'Merci ! Jouez bien :)');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/logout', name:'app_logout')]
    public function logout(Security $security): Response
    {
        $security->logout(false);

        return $this->redirectToRoute('app_home');
    }
}