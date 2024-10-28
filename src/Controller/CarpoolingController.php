<?php

namespace App\Controller;

use App\Entity\CarPoolingOffer;
use App\Form\CarPoolingOfferType;
use App\Repository\CarPoolingOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/carpooling', name: 'carpooling_')]
class CarpoolingController extends AbstractController
{
    private TranslatorInterface $translator;
    private Security $security;

    public function __construct(TranslatorInterface $translator, Security $security)
    {
        $this->translator = $translator;
        $this->security = $security;
    }

    #[Route('/offers', name: 'index')]
    public function index(CarPoolingOfferRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 4;

        $paginatedOffers = $repository->paginateCarPoolingOffers($request, $page, $limit);
        $maxPages = ceil(count($paginatedOffers) / $limit);

        return $this->render('carpooling/carpooling.offers.html.twig', [
            'carPoolingOffers' => $paginatedOffers,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }

    #[Route('/{id}/info', name: 'info')]
    public function info(CarPoolingOffer $carpoolingOffer): Response
    {
        return $this->render('carpooling/carpooling.infos.html.twig', [
            'offer' => $carpoolingOffer,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->security->isGranted('ROLE_USER')) {
            $this->addFlash(
                'not_logged_in',
                $this->translator->trans('flash.login.error')
            );
            return $this->redirectToRoute('app_register');
        }

        $offer = new CarPoolingOffer();
        $offer->setCreator($this->security->getUser());

        $form = $this->createForm(CarPoolingOfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offer);
            $em->flush();

            return $this->redirectToRoute('carpooling_index');
        }

        return $this->render('carpooling/carpooling.add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

