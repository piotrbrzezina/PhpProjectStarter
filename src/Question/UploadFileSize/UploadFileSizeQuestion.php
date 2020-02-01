<?php

declare(strict_types=1);

namespace App\Question\UploadFileSize;

use App\Config\UploadFileSize\UploadFileSizeConfig;
use App\Console\ConsoleStyle;
use App\Question\QuestionInterface;

class UploadFileSizeQuestion implements QuestionInterface
{
    private UploadFileSizeConfig $uploadFileSize;

    public function askQuestion(ConsoleStyle $io): void
    {
        $maxUploadFileSize = (int) $io->ask('What is the max upload file size (in MB)', '8', function ($answer) {
            if (!preg_match('/^\d+$/', $answer)) {
                throw new \RuntimeException(
                    'The max upload file size should contain only numbers'
                );
            }

            return trim($answer);
        });

        $maxBodySize = (int) $io->ask('What is the max body size (in MB)', (string) ($maxUploadFileSize + 8), function ($answer) {
            if (!preg_match('/^\d+$/', $answer)) {
                throw new \RuntimeException(
                    'The max body size should contain only numbers'
                );
            }

            return trim($answer);
        });

        $this->uploadFileSize = new UploadFileSizeConfig($maxUploadFileSize, $maxBodySize);
    }

    /**
     * {@inheritdoc}
     */
    public function getAnswer(): array
    {
        return [$this->uploadFileSize];
    }
}
