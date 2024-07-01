<?php

namespace App\Tests\unit\Questionnaire\Domain\Entity;

use App\Questionnaire\Domain\Entity\QuestionnaireBuilder;
use App\Tests\Helper\Questionnaire\DefaultQuestionnaireBuilder;
use PHPUnit\Framework\TestCase;

class QuestionnaireBuilderTest extends TestCase
{
    private QuestionnaireBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = DefaultQuestionnaireBuilder::create();
    }

    public function testBuildMapNestsSubquestionsCorrectly(): void
    {
        $map = $this->builder->buildMap();

        $mainQuestionId = DefaultQuestionnaireBuilder::QUESTION_2_ID;
        $subQuestion2aId = DefaultQuestionnaireBuilder::QUESTION_2A_ID;
        $subQuestion2bId = DefaultQuestionnaireBuilder::QUESTION_2B_ID;
        $subQuestion2cId = DefaultQuestionnaireBuilder::QUESTION_2C_ID;

        // Main question should include subquestions
        $this->assertContains($subQuestion2aId, $map[$mainQuestionId]);
        $this->assertContains($subQuestion2bId, $map[$mainQuestionId]);
        $this->assertContains($subQuestion2cId, $map[$mainQuestionId]);

        // Subquestions should not be top-level keys
        $this->assertArrayNotHasKey($subQuestion2aId, $map);
        $this->assertArrayNotHasKey($subQuestion2bId, $map);
        $this->assertArrayNotHasKey($subQuestion2cId, $map);
    }

    public function testContainsAllMainQuestions(): void
    {
        $map = $this->builder->buildMap();

        $this->assertArrayHasKey(DefaultQuestionnaireBuilder::QUESTION_1_ID, $map);
        $this->assertArrayHasKey(DefaultQuestionnaireBuilder::QUESTION_2_ID, $map);
        $this->assertArrayHasKey(DefaultQuestionnaireBuilder::QUESTION_3_ID, $map);
        $this->assertArrayHasKey(DefaultQuestionnaireBuilder::QUESTION_4_ID, $map);
        $this->assertArrayHasKey(DefaultQuestionnaireBuilder::QUESTION_5_ID, $map);
    }
}
