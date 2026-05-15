<?php

namespace App\Domain\Services\ScoreRules;

use App\Models\Contact;

interface ScoreRule
{
    public function calculate(Contact $contact): int;
}
