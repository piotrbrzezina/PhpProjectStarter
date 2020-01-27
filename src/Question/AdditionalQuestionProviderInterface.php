<?php

declare(strict_types=1);

namespace App\Question;

interface AdditionalQuestionProviderInterface
{
    /**
     * @return AdditionalQuestionInterface[]
     */
    public function getAdditionalQuestions(): array;
}
