<?php

namespace App\Questionnaire\Domain\Entity;

use App\Questionnaire\Domain\Value\AnswerOutcome\AnswerOutcome;
use Ramsey\Uuid\UuidInterface;

final class Answer
{
    public function __construct(
        private UuidInterface $id,
        private string $text,
        private AnswerOutcome $outcome,
    ) {
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function outcome(): AnswerOutcome
    {
        return $this->outcome;
    }
}
