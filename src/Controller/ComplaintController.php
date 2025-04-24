<?php

namespace App\Controller;

use App\Entity\Complaint;
use App\Event\ComplaintEvent;
use App\Form\ComplaintType;
use App\Repository\ComplaintRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ComplaintController extends AbstractController
{
    #[Route('/complaint/new', name: 'app_complaint_new')]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ): Response
    {
        $complaint = new Complaint();
        $form = $this->createForm(ComplaintType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $complaint->setUser($this->getUser());
            
            $entityManager->persist($complaint);
            $entityManager->flush();

            // Dispatch event
            $event = new ComplaintEvent($complaint);
            $eventDispatcher->dispatch($event, ComplaintEvent::COMPLAINT_SUBMITTED);

            $this->addFlash('success', 'Your complaint has been submitted successfully!');
            return $this->redirectToRoute('app_user_dashboard');
        }

        return $this->render('complaint/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/user/dashboard', name: 'app_user_dashboard')]
    public function dashboard(ComplaintRepository $complaintRepository): Response
    {
        $complaints = $complaintRepository->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);
        
        return $this->render('complaint/dashboard.html.twig', [
            'complaints' => $complaints,
        ]);
    }
}