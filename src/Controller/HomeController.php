<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    // Remove the root route from here to avoid conflict
    // #[Route('/', name: 'app_home')]
    
    // Add a new dashboard route instead
    #[Route('/dashboard', name: 'app_home')]
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

    #[Route('/home/welcome', name: 'app_home_welcome')]
    public function welcome(): Response
    {
        // If user is already authenticated, redirect to home page
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('home/welcome.html.twig');
    }
}