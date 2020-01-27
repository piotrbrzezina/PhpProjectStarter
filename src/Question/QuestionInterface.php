<?php

declare(strict_types=1);

namespace App\Question;

use App\Console\ConsoleStyle;

interface QuestionInterface
{
    public function askQuestion(ConsoleStyle $io): void;

    /**
     * @return mixed
     */
    public function getAnswer();
}
