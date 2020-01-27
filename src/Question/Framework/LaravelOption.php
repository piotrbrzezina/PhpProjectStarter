<?php

declare(strict_types=1);

namespace App\Question\Framework;

use App\Config\Framework\LaravelConfig;
use App\Question\AdditionalQuestionProviderInterface;
use App\Question\UploadFileSize\UploadFileSizeQuestion;

final class LaravelOption implements AdditionalQuestionProviderInterface, FrameworkOptionInterface
{
    private UploadFileSizeQuestion $fileSizeQuestion;
    private LaravelConfig $config;

    public function __construct(
        UploadFileSizeQuestion $fileSizeQuestion,
        LaravelConfig $laravelConfig
    ) {
        $this->fileSizeQuestion = $fileSizeQuestion;
        $this->config = $laravelConfig;
    }

    public function getName(): string
    {
        return 'Laravel';
    }

    public function isDefault(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdditionalQuestions(): array
    {
        return [
            $this->fileSizeQuestion,
        ];
    }

    public function getConfig(): LaravelConfig
    {
        return $this->config;
    }
}
