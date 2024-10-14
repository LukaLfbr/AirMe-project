<?php

namespace App\Controller;

use App\Entity\CarpoolingOffer;
use App\Form\CarpoolingOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarpoolingController extends AbstractController
{
    private $translator;
    private $security;

    public function __construct(TranslatorInterface $translator, Security $security) {
        $this->translator = $translator;
        $this->security = $security;
    }

    #[Route('/carpooling', name: 'carpooling_')]
    public function index(): Response
    {
        return $this->render('carpooling/carpooling.offers.html.twig', [
            'controller_name' => 'CarpoolingController',
        ]);
    }

    #[Route('/{id}', name: 'info')]
    public function info(CarpoolingOffer $carpoolingOffer): Response
    {
        return $this->render('carpooling/carpooling.info.html.twig', [
            'controller_name' => 'CarpoolingController',
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {

        if (!$this->security->isGranted('ROLE_USER')) {
            $this->addFlash('not_logged_in', $this->translator->trans('flash.login_required'));
            return $this->redirectToRoute('app_login');
        }

        $newCarpoolingOffer = new CarpoolingOffer();
        $form = $this->createForm(CarpoolingOfferType::class, $newCarpoolingOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newCarpoolingOffer);
            $em->flush();
        }

        return $this->render('carpooling/carpooling.edit.html.twig', [
            'controller_name' => 'CarpoolingController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(CarpoolingOffer $carpoolingOffer, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(CarpoolingOfferType::class, $carpoolingOffer);
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
    public function delete(CarpoolingOffer $carpoolingOffer, EntityManagerInterface $em): Response
    {
        $em->remove($carpoolingOffer);
        $em->flush();

        return $this->redirectToRoute('carpooling_');
    }
}
