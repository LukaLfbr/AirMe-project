<?php

namespace App\Controller;

use App\Entity\Coordinates;
use App\Entity\Events;
use App\Form\EventsType;
use App\Repository\CarPoolingOfferRepository;
use App\Repository\EventsRepository;
use App\Service\GeocodingService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Traits\UserAwareTrait;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/user', name: 'user_')]
#[IsGranted('ROLE_USER')]
class UserPanelController extends AbstractController
{
    // Used to get the current User associated with current ID without error
    // TODO modify this trait
    use UserAwareTrait;

    private $repository;
    private $em;
    private $translator;

    public function __construct(
        EventsRepository $repository,
        EntityManagerInterface $em,
        TranslatorInterface $translator
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->translator = $translator;
    }

    #[Route('/{id}/panel', name: 'panel')]
    public function index(Security $security, CarPoolingOfferRepository $carPoolingRepository): Response
    {
        if (!$this->getUser()) {
            $this->addFlash(
                'login-error',
                $this->translator->trans('flash.login.error')
            );
            return $this->redirectToRoute('app_event');
        }
        $carPoolingOffers = $carPoolingRepository->findCarPoolingOffersByCreator(
            $security->getUser()
        );
        $events = $this->repository->findGamesByReferent(
            $security->getUser()
        );

        return $this->render('user_panel/user.panel.html.twig', [
            'events' => $events,
            'carpoolingOffers' => $carPoolingOffers
        ]);
    }

    #[Route('/add', name: 'add_event')]
    public function add(Request $request, Security $security, GeocodingService $geocodingService): Response
    {
        $this->initializeUser($security);

        if (!$security->isGranted('ROLE_USER')) {
            $this->addFlash('not_logged_in', $this->translator->trans('flash.login_required'));
            return $this->redirectToRoute('app_login');
        }

        $event = new Events();
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        $event->setCreatedAt(new DateTimeImmutable());
        $event->setUpdatedAt(new DateTimeImmutable());

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setReferent($this->getUser());

            try {
                $coordinates = $geocodingService->getCoordinates($event->getLocation());

                $coordinatesEntity = new Coordinates();
                $coordinatesEntity->setLongitude($coordinates['longitude']);
                $coordinatesEntity->setLatitude($coordinates['latitude']);
                $this->em->persist($coordinatesEntity);

                $event->setCoordinates($coordinatesEntity);
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('flash.geocoding_error'));
                return $this->render('events/event.add.html.twig', [
                    'form' => $form,
                ]);
            }

            $this->em->persist($event);
            $this->em->flush();

            $this->addFlash('success', $this->translator->trans('flash.event_created_success'));

            return $this->redirectToRoute('user_panel', ['id' => $this->getUserId()]);
        }

        return $this->render('events/event.add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/event/{id}/edit', name: 'edit_event')]
    public function edit(Request $request, Events $event, Security $security): Response
    {
        $this->initializeUser($security);

        if (($this->getUser() !== $event->getReferent()) && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('unauthorized_edit_request', $this->translator->trans('flash.event.unauthorized_edit'));
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setUpdatedAt(new DateTimeImmutable());
            $this->em->flush();

            $this->addFlash('success', $this->translator->trans('flash.event_updated_success'));
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

            $this->addFlash('success', $this->translator->trans('flash.event_deleted_success'));
        } else {
            $this->addFlash('error', $this->translator->trans('flash.no_permission'));
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_panel');
        } else {
            return $this->redirectToRoute('user_panel', ['id' => $this->getUserId()]);
        }
    }
}
