<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_welcome')]
    public function index(): Response
    {
        // Check if user is already logged in
        if ($this->getUser()) {
            // If user is admin, redirect to admin dashboard
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin_complaints');
            }
            // If regular user, redirect to user dashboard
            return $this->redirectToRoute('app_user_dashboard');
        }
        
        // For non-authenticated users, show the welcome page
        return $this->render('welcome/index.html.twig');
    }
    
    #[Route('/welcome', name: 'app_welcome_page')]
    public function welcomePage(): Response
    {
        // Use the same logic as the index method to avoid duplication
        return $this->index();
    }
}