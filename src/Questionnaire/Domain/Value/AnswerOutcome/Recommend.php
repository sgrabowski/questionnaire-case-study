<?php

namespace App\Questionnaire\Domain\Value\AnswerOutcome;

use App\Questionnaire\Domain\Value\Recommendation;

final readonly class Recommend implements AnswerOutcome
{
    public function __construct(
        /** @var array<Recommendation> */
        public array $recommendations,
    ) {
    }
}
