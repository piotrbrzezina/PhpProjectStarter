<?php

declare(strict_types=1);

namespace App\Question\TestFramework;

use App\Question\QuestionHelper\OneFromMany\QuestionSelectOneFromMany;

class TestFrameworkQuestion extends QuestionSelectOneFromMany
{
    public function getQuestion(): string
    {
        return 'Please select <comment>test framework</comment>';
    }
}
