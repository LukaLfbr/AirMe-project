<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Traits\UserAwareTrait;

#[Route('/user', name: 'user_')]
#[IsGranted('ROLE_USER')]
class UserPanelController extends AbstractController
{
    use UserAwareTrait;

    private $repository;
    private $em;

    public function __construct(EventsRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    #[Route('/{id}/panel', name: 'panel')]
    public function index(Security $security): Response
    {
        $events = $this->repository->findByReferent(
            $security->getUser()
        );

        return $this->render('user_panel/user_panel.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/add', name: 'add_event')]
    public function add(Request $request, Security $security): Response
    {
        $this->initializeUser($security);

        if (!$security->isGranted('ROLE_USER')) {
            $this->addFlash('not_logged_in', 'Vous devez être connecté pour ajouter un événement.');
            return $this->redirectToRoute('app_login');
        }

        $event = new Events();
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        $event->setCreatedAt(new DateTimeImmutable());
        $event->setUpdatedAt(new DateTimeImmutable());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Événement créé avec succès !');
            $event->setReferent($this->getUser());

            $this->em->persist($event);
            $this->em->flush();

            return $this->redirectToRoute('user_panel', ['id' => $this->getUserId()]);
        }

        return $this->render('events/event.add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/event/{id}/edit', name: 'edit_event')]
    public function edit(Request $request, Events $event, Security $security): Response
    {
        $this->initializeUser($security);

        if (($this->getUser() !== $event->getReferent()) && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('unauthorized_edit_request', 'Vous ne pouvez pas modifier l\'événement si vous n\'en êtes pas le propriétaire');
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setUpdatedAt(new DateTimeImmutable());
            $this->em->flush();

            $this->addFlash('success', 'Événement modifié avec succès.');
            return $this->redirectToRoute('user_panel', ['id' => $this->getUserId()]);
        }

        return $this->render('events/event.edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/event/{id}/remove', name: 'delete_event')]
    public function remove(Events $event, Security $security): Response
    {
        $this->initializeUser($security);

        if (($this->getUser() === $event->getReferent()) || $this->isGranted('ROLE_ADMIN')) {
            $this->em->remove($event);
            $this->em->flush();

            $this->addFlash('success', 'Événement supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Vous n\'avez pas les permissions nécessaires pour cette action.');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_panel');
        } else {
            return $this->redirectToRoute('user_panel', ['id' => $this->getUserId()]);
        }
    }
}
