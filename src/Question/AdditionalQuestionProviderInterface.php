<?php

declare(strict_types=1);

namespace App\Question;

interface AdditionalQuestionProviderInterface
{
    /**
     * @return QuestionInterface[]
     */
    public function getAdditionalQuestions(): array;
}
