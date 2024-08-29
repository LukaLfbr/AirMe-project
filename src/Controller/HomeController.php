<?php

namespace App\Controller;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_')]
class HomeController extends AbstractController
{
    private EventsRepository $eventsRepository;

    public function __construct(EventsRepository $eventsRepository)
    {
        $this->eventsRepository = $eventsRepository;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $events = $this->eventsRepository->findAll();
        $lastEvents = $this->eventsRepository->getLastTenEvents();

        return $this->render('events/events.html.twig', [
            'events' => $events,
            'user' => $this->getUser(),
            'lastEvents' => $lastEvents,
        ]);
    }

    #[Route('event/{id}', name: 'event')]
    public function show(Events $event): Response
    {
        return $this->render('events/event.infos.html.twig', [
            'event' => $event,
        ]);
    }
}
