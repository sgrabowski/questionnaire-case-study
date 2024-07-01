<?php

namespace App\Questionnaire\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class AnswerNotFound extends \DomainException
{
    public function __construct(UuidInterface $answerUuid, UuidInterface $questionUuid)
    {
        parent::__construct(sprintf('Answer with uuid %s not found for question %s', $answerUuid->toString(), $questionUuid->toString()));
    }
}
