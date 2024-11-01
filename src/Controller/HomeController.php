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

        $phone_number = $event->getReferent()->getPhoneNumber();
        return $this->render('events/event.infos.html.twig', [
            'event' => $event,
        ]);
    }
}
