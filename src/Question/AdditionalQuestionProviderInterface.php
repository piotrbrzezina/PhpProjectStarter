<?php

declare(strict_types=1);

namespace App\Question;

interface AdditionalQuestionProviderInterface
{
    /**
     * @return QuestionInterface[]|AdditionalQuestionInterface[]
     */
    public function getAdditionalQuestions(): array;
}
