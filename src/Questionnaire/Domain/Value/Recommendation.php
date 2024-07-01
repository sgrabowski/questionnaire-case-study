<?php

namespace App\Questionnaire\Domain\Value;

use Ramsey\Uuid\UuidInterface;

final readonly class Recommendation
{
    public function __construct(
        public UuidInterface $id,
        public ?UuidInterface $categoryId = null,
    ) {
    }
}
