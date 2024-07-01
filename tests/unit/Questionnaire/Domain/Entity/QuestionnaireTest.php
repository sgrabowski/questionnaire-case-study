<?php

namespace App\Tests\unit\Questionnaire\Domain\Entity;

use App\Questionnaire\Domain\Entity\Questionnaire;
use App\Questionnaire\Domain\Exception\QuestionnaireStillInProgress;
use App\Tests\Helper\Questionnaire\DefaultQuestionnaireBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class QuestionnaireTest extends TestCase
{
    private Questionnaire $questionnaire;

    protected function setUp(): void
    {
        $builder = DefaultQuestionnaireBuilder::create();
        $this->questionnaire = Questionnaire::fromBuilder($builder, Uuid::uuid4());
    }

    public function testCompletePath()
    {
        // Simulating path through questionnaire based on given answers
        $answers = [
            DefaultQuestionnaireBuilder::ANSWER_1_YES_ID,
            DefaultQuestionnaireBuilder::ANSWER_2_BOTH_ID,
            DefaultQuestionnaireBuilder::ANSWER_2C_NONE_ID,
            DefaultQuestionnaireBuilder::ANSWER_3_YES_ID,
            DefaultQuestionnaireBuilder::ANSWER_4_NONE_ID,
            DefaultQuestionnaireBuilder::ANSWER_5_NONE_ID,
        ];

        foreach ($answers as $answerId) {
            $this->questionnaire->progress(Uuid::fromString($answerId));
        }

        $result = $this->questionnaire->result();
        $expectedRecommendations = [
            DefaultQuestionnaireBuilder::SILDENAFIL_100_ID,
            DefaultQuestionnaireBuilder::TADALAFIL_20_ID,
        ];

        $recommendationIds = array_map(fn ($r) => $r->toString(), $result->productIds);

        // Check if all expected recommendations are in the result
        foreach ($expectedRecommendations as $expected) {
            $this->assertContains($expected, $recommendationIds);
        }
    }

    public function testExceptionIsThrownIfResultIsRequestedBeforeFinishing()
    {
        $this->questionnaire->progress(Uuid::fromString(DefaultQuestionnaireBuilder::ANSWER_1_YES_ID));

        $this->expectException(QuestionnaireStillInProgress::class);
        $this->questionnaire->result();
    }

    public function testResultsEmptyAfterImmediateTermination()
    {
        $this->questionnaire->progress(Uuid::fromString(DefaultQuestionnaireBuilder::ANSWER_1_NO_ID));

        $result = $this->questionnaire->result();

        $this->assertEmpty($result->productIds);
    }
}
