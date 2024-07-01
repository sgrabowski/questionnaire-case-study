<?php

namespace App\Questionnaire\Domain\Value\AnswerOutcome;

use Ramsey\Uuid\UuidInterface;

final readonly class NextQuestion implements AnswerOutcome
{
    public function __construct(
        public UuidInterface $questionId,
    ) {
    }
}
