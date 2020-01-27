<?php

declare(strict_types=1);

namespace App\Question\Cache;

use App\Question\QuestionHelper\OneFromMany\QuestionSelectOneFromMany;

class CacheQuestion extends QuestionSelectOneFromMany
{
    public function getQuestion(): string
    {
        return 'Please select <comment>cache system</comment>';
    }
}
