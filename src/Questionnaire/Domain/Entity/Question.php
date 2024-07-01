<?php

namespace App\Questionnaire\Domain\Entity;

use App\Questionnaire\Domain\Exception\AnswerNotFound;
use Ramsey\Uuid\UuidInterface;

final class Question
{
    public function __construct(
        private UuidInterface $id,
        private string $text,
        /** @var array<Answer> */
        private array $answers,// TODO: consider is this necessary in the constructor?
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

    public function answers(): array
    {
        return $this->answers;
    }

    public function hasAnswer(UuidInterface $answerId): bool
    {
        foreach ($this->answers as $answer) {
            if ($answer->id()->equals($answerId)) {
                return true;
            }
        }

        return false;
    }

    public function getAnswer(UuidInterface $answerId): Answer
    {
        if (!$this->hasAnswer($answerId)) {
            throw new AnswerNotFound($answerId, $this->id);
        }

        // TODO: instead of looping here (again), make sure the answers in the constructor are mapped by their ids
        foreach ($this->answers as $answer) {
            if ($answer->id()->equals($answerId)) {
                return $answer;
            }
        }
    }

    public function addAnswer(Answer $answer): void
    {
        $this->answers[] = $answer;
    }
}
