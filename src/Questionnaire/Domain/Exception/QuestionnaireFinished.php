<?php

namespace App\Questionnaire\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class QuestionnaireFinished extends \DomainException
{
    public function __construct(UuidInterface $questionnaireId)
    {
        parent::__construct(sprintf('Questionnaire finished: %s', $questionnaireId->toString()));
    }
}
