<?php

namespace App\Questionnaire\Domain\Entity;

use App\Questionnaire\Domain\Exception\QuestionnaireFinished;
use App\Questionnaire\Domain\Exception\QuestionnaireStillInProgress;
use App\Questionnaire\Domain\Exception\UnhandledOutcome;
use App\Questionnaire\Domain\Value\AnswerOutcome\AnswerOutcome;
use App\Questionnaire\Domain\Value\AnswerOutcome\Combined;
use App\Questionnaire\Domain\Value\AnswerOutcome\ExcludeAllAndFinish;
use App\Questionnaire\Domain\Value\AnswerOutcome\ExcludeCategory;
use App\Questionnaire\Domain\Value\AnswerOutcome\NextQuestion;
use App\Questionnaire\Domain\Value\AnswerOutcome\Proceed;
use App\Questionnaire\Domain\Value\AnswerOutcome\Recommend;
use App\Questionnaire\Domain\Value\QuestionnaireResult;
use App\Questionnaire\Domain\Value\Recommendation;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Questionnaire
{
    /** @var array<string, Question> */
    private array $questions = [];
    private ?UuidInterface $currentQuestionId;
    /** @var array<Recommendation> */
    private array $currentRecommendations = [];
    private bool $isFinished = false;

    private function __construct(
        private UuidInterface $id,
        /** @var array<string, array<string>> */
        private readonly array $questionMap,
    ) {
        $this->currentQuestionId = Uuid::fromString(array_key_first($this->questionMap));
    }

    public static function fromBuilder(QuestionnaireBuilder $builder, UuidInterface $id): self
    {
        // todo throw exception if map empty
        $questionnaire = new self($id, $builder->buildMap());

        foreach ($builder->questions() as $question) {
            $questionnaire->questions[$question->id()->toString()] = $question;
        }

        return $questionnaire;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function progress(UuidInterface $answerId): void
    {
        if ($this->isFinished) {
            throw new QuestionnaireFinished($this->id);
        }

        $currentQuestion = $this->currentQuestion();
        $answer = $currentQuestion->getAnswer($answerId);
        $this->processOutcome($answer->outcome());

        if ($answer->outcome() instanceof NextQuestion) {
            return;
        }

        $this->currentQuestionId = $this->findNextQuestionId();
        if (null === $this->currentQuestionId) {
            $this->isFinished = true;
        }
    }

    private function findNextQuestionId(): ?UuidInterface
    {
        // if this is the last question, return immediately. We are done.
        if (array_key_last($this->questionMap) === $this->currentQuestionId->toString()) {
            return null;
        }

        $mainIds = array_keys($this->questionMap);
        // if current question is one of the main ones, just pick the next one
        $currentIndex = array_search($this->currentQuestionId->toString(), $mainIds);
        if (false !== $currentIndex) {
            return Uuid::fromString($mainIds[$currentIndex + 1]);
        }

        // otherwise, if it's a subquestion we need to find its parent and then the next one
        foreach ($this->questionMap as $questionId => $subquestions) {
            foreach ($subquestions as $subquestionId) {
                if ($subquestionId === $this->currentQuestionId->toString()) {
                    $currentIndex = array_search($questionId, $mainIds);

                    return Uuid::fromString($mainIds[$currentIndex + 1]);
                }
            }
        }
    }

    private function processOutcome(AnswerOutcome $outcome): void
    {
        switch ($outcome::class) {
            case ExcludeAllAndFinish::class:
                $this->handleExcludeAllAndFinish();
                break;
            case ExcludeCategory::class:
                $this->handleExcludeCategory($outcome);
                break;
            case NextQuestion::class:
                $this->handleNextQuestion($outcome);
                break;
            case Recommend::class:
                $this->handleRecommend($outcome);
                break;
            case Proceed::class:
                break;
            case Combined::class:
                $this->handleCombined($outcome);
                break;
            default:
                throw new UnhandledOutcome($outcome);
        }
    }

    private function handleCombined(Combined $combinedOutcome): void
    {
        // todo: this case needs more consideration. What if someone passes 2 NextQuestion outcomes? etc etc
        foreach ($combinedOutcome->outcomes as $outcome) {
            if ($outcome instanceof Combined) {
                throw new \RuntimeException('Combined outcome cannot be nested');
            }

            $this->processOutcome($outcome);
        }
    }

    private function handleExcludeAllAndFinish(): void
    {
        $this->currentRecommendations = [];
        $this->isFinished = true;
    }

    private function handleExcludeCategory(ExcludeCategory $outcome): void
    {
        $filtered = [];
        foreach ($this->currentRecommendations as $recommendation) {
            if (!$outcome->categoryId->equals($recommendation->categoryId)) {
                $filtered[] = $recommendation;
            }
        }

        $this->currentRecommendations = $filtered;
    }

    private function handleNextQuestion(NextQuestion $outcome): void
    {
        $this->currentQuestionId = $outcome->questionId;
    }

    private function handleRecommend(Recommend $outcome): void
    {
        foreach ($outcome->recommendations as $recommendationToAdd) {
            $this->currentRecommendations[] = $recommendationToAdd;
        }
    }

    private function currentQuestion(): Question
    {
        return $this->questions[$this->currentQuestionId->toString()];
    }

    public function result(): QuestionnaireResult
    {
        if (!$this->isFinished) {
            throw new QuestionnaireStillInProgress($this->id);
        }

        $results = [];
        foreach ($this->currentRecommendations as $recommendation) {
            $results[] = $recommendation->id;
        }

        return new QuestionnaireResult($results);
    }
}
