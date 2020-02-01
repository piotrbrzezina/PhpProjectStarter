<?php

declare(strict_types=1);

namespace App\Question\TestFramework;

use App\Question\QuestionHelper\QuestionSelectManyFromMany;

class TestFrameworkQuestion extends QuestionSelectManyFromMany
{
    public function getQuestion(): string
    {
        return 'Please select <comment>test framework</comment>';
    }
}
