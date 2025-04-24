<?php

namespace App\Service;

use App\Entity\Complaint;
use Psr\Log\LoggerInterface;

class ComplaintAnalyzer
{
    private array $urgentKeywords = ['urgent', 'immediately', 'emergency', 'asap', 'critical'];
    private array $refundKeywords = ['refund', 'money back', 'return', 'reimburse'];
    private array $technicalKeywords = ['broken', 'not working', 'error', 'bug', 'crash'];
    
    public function __construct(private LoggerInterface $logger)
    {
    }
    
    public function analyzeComplaint(Complaint $complaint): array
    {
        $text = strtolower($complaint->getTitle() . ' ' . $complaint->getDescription());
        $tags = [];
        
        // Check for urgent keywords
        foreach ($this->urgentKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                $tags[] = 'urgent';
                break;
            }
        }
        
        // Check for refund keywords
        foreach ($this->refundKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                $tags[] = 'refund';
                break;
            }
        }
        
        // Check for technical keywords
        foreach ($this->technicalKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                $tags[] = 'technical';
                break;
            }
        }
        
        if (!empty($tags)) {
            $this->logger->info('Complaint #{id} has been tagged with: {tags}', [
                'id' => $complaint->getId(),
                'tags' => implode(', ', $tags)
            ]);
        }
        
        return $tags;
    }
}