<?php

namespace App\Questionnaire\Domain\Value;

final readonly class QuestionnaireResult
{
    public function __construct(
        public array $productIds,
    ) {
    }
}
