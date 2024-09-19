<?php

// src/Controller/UtilisateurController.php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UtilisateurManagerInterface;
use App\Service\FlashMessageHelperInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UtilisateurController extends AbstractController
{
    #[Route('/inscription', name: 'inscription', methods: ['GET', 'POST'])]
    public function inscription(

        Request $request,
        EntityManagerInterface $entityManager,
        UtilisateurManagerInterface $utilisateurManager,
        FlashMessageHelperInterface $flashMessageHelper
    ): Response {

        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('feed');
        }

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur, [
            'method' => 'POST',
            'action' => $this->generateUrl('inscription')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $fichierPhotoProfil = $form->get('fichierPhotoProfil')->getData();

            // Process user with UtilisateurManager
            $utilisateurManager->processNewUtilisateur($utilisateur, $plainPassword, $fichierPhotoProfil);

            // Persist user in database
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie !');
            return $this->redirectToRoute('feed');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $flashMessageHelper->addFormErrorsAsFlash($form);
        }

        return $this->render('utilisateur/inscription.html.twig', [
            'utilisateurForm' => $form->createView(),
        ]);
    }

    #[Route('/connexion', name: 'connexion', methods: ['GET', 'POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('feed');
        }

        // Récupération du dernier login saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Rendu du template de connexion avec le dernier login
        return $this->render('utilisateur/connexion.html.twig', [
            'last_username' => $lastUsername,
        ]);
    }

    #[Route('/utilisateurs/{login}/feed', name: 'page_perso', methods: ['GET'])]
    public function pagePerso(?Utilisateur $utilisateur): Response
    {
        if ($utilisateur === null) {
            $this->addFlash('error', 'Utilisateur inexistant');
            return $this->redirectToRoute('feed');
        }

        return $this->render('utilisateur/page_perso.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

}

