<?php

namespace App\Domain\Services\ScoreRules;

use App\Models\Contact;

class PhoneExistsScoreRule implements ScoreRule
{
    public function calculate(Contact $contact): int
    {
        if (!$contact->phone) {
            return 0;
        }

        $phone = preg_replace('/\D/', '', $contact->phone);

        $ddd = substr($phone, 0, 2);

        if ($ddd >= 11 && $ddd <= 19) {
            return 20;
        }

        return 10;
    }
}
