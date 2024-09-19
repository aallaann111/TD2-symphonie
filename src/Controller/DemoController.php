<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends AbstractController
{
    #[Route('/hello', name: 'hello_get', methods: ["GET"])]
    public function hello(): Response
    {
        return $this->render('demo/demo1.html.twig');
    }

    #[Route('/hello/{nom}', name: 'hello_get2', methods: ["GET"])]
    public function helloWithName(string $nom): Response
    {
        // Ajouter un message flash de succès
        $this->addFlash('success', 'Bienvenue ' . htmlspecialchars($nom) . '!');

        return $this->render('demo/demo2.html.twig', ['nom' => htmlspecialchars($nom)]);
    }

    #[Route('/courses', name: 'courses_get', methods: ["GET"])]
    public function courses(): Response
    {
        $listeCourses = ['lait', 'pain', 'œufs', 'Livre sur Symfony'];
        return $this->render('demo/demo3.html.twig', ['listeCourses' => $listeCourses]);
    }
}


