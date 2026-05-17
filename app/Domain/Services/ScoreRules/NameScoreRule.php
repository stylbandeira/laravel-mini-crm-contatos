<?php

namespace App\Domain\Services\ScoreRules;

use App\Models\Contact;

class NameScoreRule implements ScoreRule
{
    public function calculate(Contact $contact): int
    {
        $name = trim($contact->name);

        return str_contains($name, ' ')
            ? 10
            : 0;
    }
}
