<?php

namespace App\Domain\Services\ScoreRules;

use App\Domain\Services\ScoreRules\ScoreRule;
use App\Models\Contact;

class PhoneScoreRule implements ScoreRule
{
    public function calculate(Contact $contact): int
    {
        if (!$contact->phone) {
            return 0;
        }

        $phone = preg_replace(
            '/\D/',
            '',
            $contact->phone
        );

        $ddd = (int) substr($phone, 0, 2);

        return $ddd >= 11 && $ddd <= 19
            ? 20
            : 10;
    }
}
