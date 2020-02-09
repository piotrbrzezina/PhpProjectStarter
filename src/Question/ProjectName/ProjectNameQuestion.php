<?php

declare(strict_types=1);

namespace App\Question\ProjectName;

use App\Config\ProjectName\ProjectConfig;
use App\Console\ConsoleStyle;
use App\Question\QuestionInterface;
use RuntimeException;

class ProjectNameQuestion implements QuestionInterface
{
    private ProjectConfig $projectName;

    public function askQuestion(ConsoleStyle $io): void
    {
        $io->writeln('Client name and project name will be used as <comment>namespace</comment> for you project');
        $clientName = $io->ask('What is the client name ', null, function ($answer) {
            if (strlen(trim((string) $answer)) < 3) {
                throw new RuntimeException(
                    'The name should contain at least 3 char'
                );
            }

            if (!preg_match('/^[a-zA-Z]+$/i', trim($answer))) {
                throw new RuntimeException(
                    'The name should contains only letters a-z A-Z'
                );
            }

            return trim($answer);
        });

        $projectName = $io->ask('What is the project name', null, function ($answer) {
            if (strlen(trim((string) $answer)) < 3) {
                throw new RuntimeException(
                    'The name should contain at least 3 char'
                );
            }

            if (!preg_match('/^[a-zA-Z]+$/i', trim($answer))) {
                throw new RuntimeException(
                    'The name should contains only letters a-z A-Z'
                );
            }

            return trim($answer);
        });

        $this->projectName = new ProjectConfig($clientName, $projectName);
    }

    public function getAnswer(): array
    {
        return [$this->projectName];
    }
}
