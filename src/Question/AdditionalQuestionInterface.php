<?php

declare(strict_types=1);

namespace App\Question;

use App\Console\ConsoleStyle;

interface AdditionalQuestionInterface
{
    public function askQuestion(ConsoleStyle $io): void;

    /**
     * @return mixed
     */
    public function getAnswer();
}
