<?php

namespace App\Controller;

use App\Repository\ComplaintRepository;
use App\Entity\Complaint;
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
            // Cache complaints by status
            $cacheKey = 'admin.complaints_by_status.' . $status;
            $complaints = $this->cache->get($cacheKey, function (ItemInterface $item) use ($status) {
                $item->expiresAfter(1800); // Cache for 30 minutes
                return $this->complaintRepository->findByStatus($status);
            });
            
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
        $oldStatus = $complaint->getStatus();
        
        if (in_array($newStatus, ['Pending', 'In Progress', 'Resolved'])) {
            $complaint->setStatus($newStatus);
            $this->complaintRepository->getEntityManager()->flush();
            
            // Clear all relevant caches when a complaint status is updated
            $this->cache->delete('admin.all_complaints');
            
            // Clear the old status cache if it exists
            if ($oldStatus) {
                $this->cache->delete('admin.complaints_by_status.' . $oldStatus);
            }
            
            // Clear the new status cache
            $this->cache->delete('admin.complaints_by_status.' . $newStatus);
            
            $this->addFlash('success', 'Complaint status updated successfully');
        }
        
        return $this->redirectToRoute('app_admin_complaints');
    }
}

