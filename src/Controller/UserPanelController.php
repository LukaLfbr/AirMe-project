<?php

namespace App\Controller;

use App\Entity\Coordinates;
use App\Entity\Events;
use App\Form\EventsType;
use App\Repository\CarPoolingOfferRepository;
use App\Repository\EventsRepository;
use App\Service\GeocodingService;
use App\Service\CheckUserService;
use App\Service\HtmlPurifierService;
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
    use UserAwareTrait;

    private $repository;
    private $em;
    private $translator;
    private $checkUserService;

    public function __construct(
        EventsRepository $repository,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        CheckUserService $checkUserService
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->translator = $translator;
        $this->checkUserService = $checkUserService;
    }

    #[Route('/{id}/panel', name: 'panel')]
    public function index(
        Security $security, 
        CarPoolingOfferRepository $carPoolingRepository
    ): Response {
        $this->initializeUser($security);
        $this->checkUserService->checkUser($security);

        $carPoolingOffers = $carPoolingRepository->findCarPoolingOffersByCreator(
            $security->getUser()
        );
        $events = $this->repository->findGamesByReferent(
            $security->getUser()
        );

        return $this->render('user_panel/user.panel.html.twig', [
            'events' => $events,
            'carpoolingOffers' => $carPoolingOffers,
        ]);
    }

    #[Route('/add', name: 'add_event')]
    public function add(
        Request $request, 
        Security $security, 
        GeocodingService $geocodingService,
        HtmlPurifierService $htmlPurifierService
    ): Response {
        $this->initializeUser($security);
        $this->checkUserService->checkUser($security);

        if (!$security->getUser()->getPhoneNumber()) {
            $this->addFlash(
                'not_creator flash',
                $this->translator->trans('user.creator_account_required')
            );
            return $this->redirectToRoute('user_panel', ['id' => $this->getUserId()]);
        }

        $event = new Events();
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        $event->setCreatedAt(new DateTimeImmutable());
        $event->setUpdatedAt(new DateTimeImmutable());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $purifiedData = $htmlPurifierService->purifyArray((array) $data);

            foreach ($purifiedData as $key => $value) {
                $setter = 'set' . ucfirst($key);
                if (method_exists($event, $setter)) {
                    $event->$setter($value);
                }
            }

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

            $this->addFlash('success', $this->translator->trans('flash.event.created_success'));

            return $this->redirectToRoute('user_panel', ['id' => $this->getUserId()]);
        }

        return $this->render('events/event.add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/event/{id}/edit', name: 'edit_event')]
    public function edit(
        Request $request, 
        Events $event, 
        Security $security,
        GeocodingService $geocodingService,
        HtmlPurifierService $htmlPurifierService
    ): Response {
        $this->initializeUser($security);

        if (($this->getUser() !== $event->getReferent()) && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash(
                'unauthorized_edit_request', 
                $this->translator->trans('flash.event.unauthorized_edit')
            );
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $purifiedData = $htmlPurifierService->purifyArray((array) $data);

            foreach ($purifiedData as $key => $value) {
                $setter = 'set' . ucfirst($key);
                if (method_exists($event, $setter)) {
                    $event->$setter($value);
                }
            }

            $event->setUpdatedAt(new DateTimeImmutable());
            $this->em->flush();

            $this->addFlash('success', $this->translator->trans('flash.event.updated_success'));
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

            $this->addFlash('success', $this->translator->trans('flash.event.deleted_success'));
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

