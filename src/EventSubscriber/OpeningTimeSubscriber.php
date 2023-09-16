<?php

namespace App\EventSubscriber;

use DateTimeImmutable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class OpeningTimeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly string $gameOpeningDatetime,
        private readonly string $gameClosingDatetime,
        private readonly Environment $twig,
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $openingTime = DateTimeImmutable::createFromFormat(DATE_ATOM, $this->gameOpeningDatetime);
        $closingTime = DateTimeImmutable::createFromFormat(DATE_ATOM, $this->gameClosingDatetime);
        $now = new DateTimeImmutable();

        if ($now < $openingTime || $now > $closingTime) {
            $html = $this->twig->render('event/closed.html.twig', [
                'opening_time' => $openingTime,
                'closing_time' => $closingTime,
                'game_ended' => $now > $closingTime
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
