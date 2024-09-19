<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use App\Service\FlashMessageHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FlashMessageHelperInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PublicationController extends AbstractController
{
    #[Route('/', name: 'feed', methods: ['GET', 'POST'])]
    public function feed(Request $request, EntityManagerInterface $entityManager, PublicationRepository $publicationRepository): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'utilisateur connecté
            $utilisateur = $this->getUser();

            // Affectation de l'utilisateur comme auteur de la publication
            $publication->setAuteur($utilisateur);

            $entityManager->persist($publication);
            $entityManager->flush();

            // Ajout d'un message flash pour confirmation
            $this->addFlash('success', 'Publication créée avec succès !');

            return $this->redirectToRoute('feed');
        }

        $publications = $publicationRepository->findAllOrderedByDate();

        return $this->render('publication/feed.html.twig', [
            'form' => $form->createView(),
            'publications' => $publications,
        ]);
    }
}