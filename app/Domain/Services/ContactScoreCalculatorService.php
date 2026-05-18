<?php

namespace App\Domain\Services;

use App\Domain\Services\ScoreRules\ScoreRule;
use App\Models\Contact;

class ContactScoreCalculatorService
{
    public function __construct(
        private array $rules
    ) {}

    public function calculate(Contact $contact): int
    {
        return collect($this->rules)
            ->sum(function (ScoreRule $rule) use ($contact) {
                $points = $rule->calculate($contact);

                return $points;
            });
    }
}
