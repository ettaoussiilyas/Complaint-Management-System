<?php

namespace App\EventListener;

use App\Entity\Complaint;
use App\Service\ComplaintAnalyzer;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class ComplaintEventListener implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private ComplaintAnalyzer $complaintAnalyzer
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Complaint) {
            $this->logger->info('New complaint submitted: {title}', [
                'id' => $entity->getId(),
                'title' => $entity->getTitle(),
                'status' => $entity->getStatus()
            ]);
            
            // Analyze the complaint for keywords
            $tags = $this->complaintAnalyzer->analyzeComplaint($entity);
            
            if (in_array('urgent', $tags)) {
                // You could set a flag or send a notification for urgent complaints
                $this->logger->alert('URGENT complaint detected: {title}', [
                    'id' => $entity->getId(),
                    'title' => $entity->getTitle()
                ]);
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Complaint) {
            $this->logger->info('Complaint updated: {title}', [
                'id' => $entity->getId(),
                'title' => $entity->getTitle(),
                'status' => $entity->getStatus()
            ]);
        }
    }
}