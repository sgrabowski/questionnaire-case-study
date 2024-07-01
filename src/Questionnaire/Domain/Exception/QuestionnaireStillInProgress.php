<?php

namespace App\Questionnaire\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class QuestionnaireStillInProgress extends \DomainException
{
    public function __construct(UuidInterface $questionnaireId)
    {
        parent::__construct(sprintf('Questionnaire %s is still in progress', $questionnaireId->toString()));
    }
}
