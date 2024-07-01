<?php

namespace App\Questionnaire\Domain\Entity;

use App\Questionnaire\Domain\Value\AnswerOutcome\NextQuestion;
use Ramsey\Uuid\UuidInterface;

final class QuestionnaireBuilder
{
    /** @var array<Question> */
    private array $questions;

    public function __construct(
        private UuidInterface $id,
    ) {
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function questions(): array
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): void
    {
        $this->questions[] = $question;
    }

    // TODO: create an object representation of this map so it's more readable and traversable
    // TODO: also add answers so the result can be presented to the frontend
    public function buildMap(): array
    {
        $questionMap = [];
        // first assign all ids
        foreach ($this->questions as $question) {
            $questionMap[$question->id()->toString()] = [];
        }

        // then go over all questions again
        foreach ($this->questions as $question) {
            foreach ($question->answers() as $answer) {
                $outcome = $answer->outcome();
                // if any of the answer outcomes is another question
                if ($outcome instanceof NextQuestion) {
                    // the question assigned in outcome cannot be a main question
                    unset($questionMap[$outcome->questionId->toString()]);
                    // so it will be put under the owning question
                    $questionMap[$question->id()->toString()][] = $outcome->questionId->toString();
                }
            }
        }

        return $questionMap;
    }
}
