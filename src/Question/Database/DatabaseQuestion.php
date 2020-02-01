<?php

declare(strict_types=1);

namespace App\Question\Database;

use App\Question\QuestionHelper\QuestionSelectOneFromMany;

class DatabaseQuestion extends QuestionSelectOneFromMany
{
    public function getQuestion(): string
    {
        return 'Please select <comment>database</comment>';
    }
}
