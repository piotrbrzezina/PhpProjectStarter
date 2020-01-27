<?php

declare(strict_types=1);

namespace App\ProjectName;

use App\Config\ProjectName\ProjectNameConfig;
use App\Console\ConsoleStyle;
use App\Question\QuestionInterface;
use RuntimeException;

class ProjectNameQuestion implements QuestionInterface
{
    private ProjectNameConfig $projectName;

    public function askQuestion(ConsoleStyle $io): void
    {
        $projectName = $io->ask('What is the project name', null, function ($answer) {
            if (strlen(trim((string) $answer)) < 3) {
                throw new RuntimeException(
                    'The name should contain at least 3 char'
                );
            }

            return trim($answer);
        });

        $this->projectName = new ProjectNameConfig($projectName);
    }

    public function getAnswer(): ?ProjectNameConfig
    {
        return $this->projectName;
    }
}
