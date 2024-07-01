<?php

namespace App\Questionnaire\Domain\Exception;

use App\Questionnaire\Domain\Value\AnswerOutcome\AnswerOutcome;

class UnhandledOutcome extends \DomainException
{
    public function __construct(AnswerOutcome $outcome)
    {
        parent::__construct(sprintf('Unhandled outcome class: %s', $outcome::class));
    }
}
