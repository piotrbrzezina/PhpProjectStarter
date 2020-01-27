<?php

declare(strict_types=1);

namespace App\Question\Framework;

use App\Config\Framework\SymfonySkeletonConfig;
use App\Question\AdditionalQuestionProviderInterface;
use App\Question\UploadFileSize\UploadFileSizeQuestion;

final class SymfonySkeletonOption implements FrameworkOptionInterface, AdditionalQuestionProviderInterface
{
    private UploadFileSizeQuestion $fileSizeQuestion;
    private SymfonySkeletonConfig $config;

    public function __construct(UploadFileSizeQuestion $fileSizeQuestion, SymfonySkeletonConfig $config)
    {
        $this->fileSizeQuestion = $fileSizeQuestion;
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'Symfony skeleton';
    }

    public function isDefault(): bool
    {
        return false;
    }

    public function getAdditionalQuestions(): array
    {
        return [
            $this->fileSizeQuestion,
        ];
    }

    public function getConfig(): SymfonySkeletonConfig
    {
        return $this->config;
    }
}
