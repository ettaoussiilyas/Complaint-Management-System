<?php

namespace App\Controller;

use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    public function __construct(
        private ComplaintRepository $complaintRepository,
        private CacheInterface $cache
    ) {
    }

    #[Route('/admin/complaints', name: 'app_admin_complaints')]
    public function listComplaints(Request $request): Response
    {
        $status = $request->query->get('status');
        
        if ($status) {
            $complaints = $this->complaintRepository->findByStatus($status);
            return $this->render('admin/complaints.html.twig', [
                'complaints' => $complaints,
                'current_status' => $status
            ]);
        }
        
        // Cache all complaints for 1 hour
        $complaints = $this->cache->get('admin.all_complaints', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return $this->complaintRepository->findAll();
        });
        
        return $this->render('admin/complaints.html.twig', [
            'complaints' => $complaints
        ]);
    }
    
    #[Route('/admin/complaint/{id}/status', name: 'app_admin_update_status', methods: ['POST'])]
    public function updateStatus(Request $request, int $id): Response
    {
        $complaint = $this->complaintRepository->find($id);
        
        if (!$complaint) {
            throw $this->createNotFoundException('Complaint not found');
        }
        
        $newStatus = $request->request->get('status');
        if (in_array($newStatus, ['Pending', 'In Progress', 'Resolved'])) {
            $complaint->setStatus($newStatus);
            $this->complaintRepository->getEntityManager()->flush();
            
            // Clear the cache when a complaint status is updated
            $this->cache->delete('admin.all_complaints');
            
            $this->addFlash('success', 'Complaint status updated successfully');
        }
        
        return $this->redirectToRoute('app_admin_complaints');
    }
}

