<?php

namespace App\Domain\Services\ScoreRules;

use App\Domain\Services\ScoreRules\ScoreRule;
use App\Models\Contact;

class EmailScoreRule implements ScoreRule
{
    public function calculate(Contact $contact): int
    {
        $score = 0;

        $email = strtolower($contact->email);

        $publicDomains = [
            'gmail.com',
            'hotmail.com',
            'yahoo.com',
        ];

        $domain = substr(
            strrchr($email, "@"),
            1
        );

        if (!in_array($domain, $publicDomains)) {
            $score += 20;
        }

        if (str_ends_with($domain, '.br')) {
            $score += 10;
        }

        return $score;
    }
}
