<?php

declare(strict_types=1);

namespace App\Question\Framework;

use App\Question\QuestionHelper\OneFromMany\QuestionSelectOneFromMany;

final class FrameworkQuestion extends QuestionSelectOneFromMany
{
    public function getQuestion(): string
    {
        return 'Please select <comment>framework</comment>';
    }
}
