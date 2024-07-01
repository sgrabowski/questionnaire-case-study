<?php

namespace App\Tests\unit\Questionnaire\Domain\Entity;

use App\Questionnaire\Domain\Entity\Answer;
use App\Questionnaire\Domain\Entity\Question;
use App\Questionnaire\Domain\Exception\AnswerNotFound;
use App\Questionnaire\Domain\Value\AnswerOutcome\Proceed;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class QuestionTest extends TestCase
{
    private Question $question;
    private Answer $answer;

    protected function setUp(): void
    {
        $this->answer = new Answer(Uuid::uuid4(), 'Sample Answer', new Proceed());
        $this->question = new Question(Uuid::uuid4(), 'Sample Question', [$this->answer]);
    }

    public function testHasAnswerReturnsTrueWhenAnswerExists()
    {
        $result = $this->question->hasAnswer($this->answer->id());
        $this->assertTrue($result);
    }

    public function testHasAnswerReturnsFalseWhenAnswerDoesNotExist()
    {
        $nonExistentUuid = Uuid::uuid4();
        $result = $this->question->hasAnswer($nonExistentUuid);
        $this->assertFalse($result);
    }

    public function testGetAnswerReturnsAnswerWhenAnswerExists()
    {
        $result = $this->question->getAnswer($this->answer->id());
        $this->assertSame($this->answer, $result);
    }

    public function testGetAnswerThrowsAnswerNotFoundWhenAnswerDoesNotExist()
    {
        $this->expectException(AnswerNotFound::class);
        $nonExistentUuid = Uuid::uuid4();
        $this->question->getAnswer($nonExistentUuid);
    }
}
