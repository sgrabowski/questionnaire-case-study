<?php

namespace App\Questionnaire\Domain\Value\AnswerOutcome;

final readonly class Combined implements AnswerOutcome
{
    public function __construct(
        /** @var array<AnswerOutcome> */
        public array $outcomes,
    ) {
    }
}
