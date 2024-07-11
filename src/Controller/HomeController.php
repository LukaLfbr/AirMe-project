<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_')]
class HomeController extends AbstractController
{
    private EventsRepository $eventsRepository;
    private EntityManagerInterface $em;
    private ?int $userId;

    public function __construct(EventsRepository $eventsRepository, EntityManagerInterface $em)
    {
        $this->eventsRepository = $eventsRepository;
        $this->em = $em;
        $this->userId = null;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $events = $this->eventsRepository->findAll();

        return $this->render('events/events.html.twig', [
            'events' => $events,
            'user' => $this->getUser(),
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
