<?php

namespace App\Questionnaire\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class NexQuestionNotFound extends \DomainException
{
    public function __construct(UuidInterface $currentQuestionId)
    {
        parent::__construct(sprintf('No question found after question with id %s', $currentQuestionId));
    }
}
