<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin_')]
#[IsGranted(
    "ROLE_ADMIN",
    statusCode: 404,
    message: "Cannot access this panel without Admin privilege"
)]
class AdminPanelController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(
        EntityManagerInterface $em,
        EventsRepository $repository
    ) {
        $this->repository = $repository;
        $this->em = $em;
    }

    #[Route('/', name: 'panel')]
    public function index(): Response
    {
        $events = $this->repository->findAll();

        return $this->render('admin_panel/admin_panel.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/event/{id}', name: 'event')]
    public function show(Events $event): Response
    {
        return $this->render('admin_panel/admin_panel.show.html.twig', [
            'event' => $event,
        ]);
    }

    // #[Route('/add', name: 'add')]
    // public function add(Request $request): Response
    // {
    //     $event = new Events();
    //     $form = $this->createForm(EventsType::class, $event);
    //     $form->handleRequest($request);

    //     $event->setCreatedAt(new DateTimeImmutable());
    //     $event->setUpdatedAt(new DateTimeImmutable());

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->addFlash('success', 'Événement créé avec succès !');
    //         $event->setReferent($this->getUser());
    //         $this->em->persist($event);
    //         $this->em->flush();

    //         return $this->redirectToRoute('admin_panel');
    //     }

    //     return $this->render('admin_panel/admin_add.add.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    #[Route('/event/{id}/edit', name: 'edit')]
    public function edit(Events $event, Request $request): Response
    {
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setUpdatedAt(new DateTimeImmutable());
            $this->em->flush();

            $this->addFlash('success', 'Événement modifié avec succès.');

            return $this->redirectToRoute('admin_panel');
        }

        return $this->render('admin_panel/admin_panel.edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/event/{id}/delete', name: 'delete')]
    public function delete(Events $event): Response
    {
        $this->em->remove($event);
        $this->em->flush();

        $this->addFlash('success', 'Événement supprimé avec succès.');

        return $this->redirectToRoute('admin_panel');
    }
}
