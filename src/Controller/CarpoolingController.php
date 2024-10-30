<?php

namespace App\Controller;

use App\Entity\CarPoolingOffer;
use App\Form\CarPoolingOfferType;
use App\Repository\CarPoolingOfferRepository;
use App\Traits\UserAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/carpooling', name: 'carpooling_')]
class CarpoolingController extends AbstractController
{
    use UserAwareTrait;

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
    #[IsGranted('ROLE_USER')]
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
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'edit')]
    public function edit(CarPoolingOffer $carpoolingOffer, Request $request, EntityManagerInterface $em): Response
    {
        if (($this->getUser() !== $carpoolingOffer->getCreator()) && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('unauthorized_edit_request', $this->translator->trans('flash.event.unauthorized_edit'));
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(CarPoolingOfferType::class, $carpoolingOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($carpoolingOffer);
            $em->flush();

            $this->initializeUser($this->security);

            return $this->redirectToRoute('user_panel', ['id' => $this->getUserId()]);
        }

        return $this->render('carpooling/carpooling.edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
   
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/delete', name: 'delete')]
    public function delete(EntityManagerInterface $em, CarPoolingOffer $carPoolingOffer, Security $security)
    {
        if ($security->getUser() == $carPoolingOffer->getCreator() || $this->isGranted('ROLE_ADMIN')) {
            $em->remove($carPoolingOffer);
            $em->flush();
            $this->addFlash(
               'success',
               'user.carpooling.offer.delete-seccess'
            );
            $this->initializeUser($security);
        }else {
            $this->addFlash(
               'failure',
               $this->translator->trans('flash.event.unauthorized_edit')
            );
        };

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_panel');
        } else {
            return $this->redirectToRoute('user_panel', ['id' => $this->getUserId()]);
        }
    }

    #[Route('/{id}/related-offers', name: 'related-offers')]
    public function relatedOffers(int $id, CarPoolingOfferRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 4;

        // Utilisation de la nouvelle méthode pour paginer les offres liées à l'événement
        $paginatedOffers = $repository->paginateCarPoolingOffersByEvent($id, $page, $limit);
        $maxPages = ceil(count($paginatedOffers) / $limit);

        return $this->render('carpooling/carpooling.event.offers.html.twig', [
            'relatedOffers' => $paginatedOffers,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
}


}

