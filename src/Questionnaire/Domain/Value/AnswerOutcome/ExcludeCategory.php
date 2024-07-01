<?php

namespace App\Questionnaire\Domain\Value\AnswerOutcome;

use Ramsey\Uuid\UuidInterface;

final readonly class ExcludeCategory implements AnswerOutcome
{
    public function __construct(
        public UuidInterface $categoryId,
    ) {
    }
}
