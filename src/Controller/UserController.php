<?php

namespace App\Controller;

use App\Entity\Complaint;
use App\Form\ComplaintType;
use App\Repository\ComplaintRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/dashboard', name: 'app_user_dashboard')]
    public function dashboard(ComplaintRepository $complaintRepository): Response
    {
        // Get the current user
        $user = $this->getUser();
        
        // Get complaints for the current user
        $complaints = $complaintRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);
        
        return $this->render('user/dashboard.html.twig', [
            'complaints' => $complaints,
        ]);
    }
    
    #[Route('/complaint/new', name: 'app_user_complaint_new')]
    public function newComplaint(Request $request, EntityManagerInterface $entityManager): Response
    {
        $complaint = new Complaint();
        $complaint->setUser($this->getUser());
        $complaint->setStatus('Pending');
        
        $form = $this->createForm(ComplaintType::class, $complaint);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($complaint);
            $entityManager->flush();
            
            $this->addFlash('success', 'Your complaint has been submitted successfully.');
            return $this->redirectToRoute('app_user_dashboard');
        }
        
        return $this->render('user/new_complaint.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/complaint/{id}', name: 'app_user_complaint_show')]
    public function showComplaint(Complaint $complaint): Response
    {
        // Security check - make sure the user can only see their own complaints
        if ($complaint->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot view this complaint');
        }
        
        return $this->render('user/show_complaint.html.twig', [
            'complaint' => $complaint,
        ]);
    }
}