<?php

namespace App\Service;

use App\Entity\Complaint;

class KeywordAnalyzer
{
    private array $urgentKeywords = ['urgent', 'immediately', 'emergency', 'asap'];
    private array $refundKeywords = ['refund', 'money back', 'return'];

    public function analyzeComplaint(Complaint $complaint): array
    {
        $text = strtolower($complaint->getTitle() . ' ' . $complaint->getDescription());
        $tags = [];

        if ($this->containsAny($text, $this->urgentKeywords)) {
            $tags[] = 'urgent';
        }

        if ($this->containsAny($text, $this->refundKeywords)) {
            $tags[] = 'refund';
        }

        return $tags;
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
}