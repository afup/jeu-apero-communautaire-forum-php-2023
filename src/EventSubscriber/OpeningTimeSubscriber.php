<?php

namespace App\EventSubscriber;

use App\Controller\ScoresController;
use App\Entity\User;
use App\Repository\FlashRepository;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class OpeningTimeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly string $gameOpeningDatetime,
        private readonly string $gameClosingDatetime,
        private readonly Environment $twig,
        private readonly FlashRepository $flashRepository,
        private readonly Security $security
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $openingTime = DateTimeImmutable::createFromFormat(DATE_ATOM, $this->gameOpeningDatetime);
        $closingTime = DateTimeImmutable::createFromFormat(DATE_ATOM, $this->gameClosingDatetime);
        $now = new DateTimeImmutable();

        if ($now < $openingTime) {
            $html = $this->twig->render('event/closed.html.twig', [
                'opening_time' => $openingTime,
            ]);

            $event->setResponse(new Response($html));
        }

        if ($now >= $closingTime) {
            /** @var User $user */
            $user = $this->security->getUser();
            $individualScoreTable = $this->flashRepository->getScoresByUser($user);

            $scoreByTeam = $this->flashRepository->getScoreByTeam();

            $html = $this->twig->render('event/ended.html.twig', [
                'opening_time' => $openingTime,
                'teamScores' => $scoreByTeam,
                'individualScores' => $individualScoreTable,
            ]);

            $event->setResponse(new Response($html));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
