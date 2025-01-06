<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\EventsAutocompleteType;
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

        $autocompleteForm = $this->createForm(EventsAutocompleteType::class);

        return $this->render('events/events.html.twig', [
            'events' => $events,
            'user' => $this->getUser(),
            'lastEvents' => $lastEvents,
            'autocompleteForm' => $autocompleteForm->createView(),
        ]);
    }

    #[Route('event/{id}', name: 'event')]
    public function show(Events $event): Response
    {
        return $this->render('events/event.infos.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('about', name: 'about')]
    public function about(): Response
    {
        return $this->render('site_infos/about.html.twig');
    }

    #[Route('legals', name: 'legals')]
    public function contact(): Response
    {
        return $this->render('site_infos/legals.html.twig');
    }
}
