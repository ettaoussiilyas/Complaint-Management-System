<?php

namespace App\EventListener;

use App\Event\ComplaintEvent;
use App\Service\KeywordAnalyzer;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: ComplaintEvent::class)]
class ComplaintEventListener
{
    public function __construct(
        private KeywordAnalyzer $keywordAnalyzer,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(ComplaintEvent $event): void
    {
        $complaint = $event->getComplaint();
        $tags = $this->keywordAnalyzer->analyzeComplaint($complaint);
        
        if (in_array('urgent', $tags)) {
            $this->logger->info('Urgent complaint submitted', [
                'id' => $complaint->getId(),
                'title' => $complaint->getTitle()
            ]);
        }
        
        $this->logger->info('Complaint submitted', [
            'id' => $complaint->getId(),
            'title' => $complaint->getTitle(),
            'tags' => $tags
        ]);
    }
}