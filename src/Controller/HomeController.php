<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // If user is not authenticated, redirect to welcome page
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_welcome');
        }
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/welcome', name: 'app_welcome')]
    public function welcome(): Response
    {
        // If user is already authenticated, redirect to home page
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('home/welcome.html.twig');
    }
}