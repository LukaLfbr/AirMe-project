<?php

namespace App\Controller;

use App\Entity\CarPoolingOffer;
use App\Form\CarPoolingOfferType;
use App\Repository\CarPoolingOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/carpooling', name: 'carpooling_')]
class CarpoolingController extends AbstractController
{
    private $translator;
    private $security;

    public function __construct(TranslatorInterface $translator, Security $security) {
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
        'page' => $page
    ]);
}


    #[Route('/{id}', name: 'info')]
    public function info(CarPoolingOffer $carpoolingOffer): Response
    {
        return $this->render('carpooling/carpooling.infos.html.twig', [
            'offer' => $carpoolingOffer
        ]);  
    }

    #[Route('/add', name: 'add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        if (!$this->security->isGranted('ROLE_USER')) {
            $this->addFlash('not_logged_in', $this->translator->trans('flash.login_required'));
            return $this->redirectToRoute('app_login');
        }

        $newCarPoolingOffer = new CarPoolingOffer();
        $form = $this->createForm(CarPoolingOfferType::class, $newCarPoolingOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newCarPoolingOffer);
            $em->flush();
        }

        return $this->render('carpooling/carpooling.add.html.twig', [
            'controller_name' => 'CarpoolingController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(CarPoolingOffer $carpoolingOffer, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(CarPoolingOfferType::class, $carpoolingOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->render('carpooling/carpooling.edit.html.twig', [
            'controller_name' => 'CarpoolingController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(CarPoolingOffer $carpoolingOffer, EntityManagerInterface $em): Response
    {
        $em->remove($carpoolingOffer);
        $em->flush();

        return $this->redirectToRoute('carpooling_');
    }
}
