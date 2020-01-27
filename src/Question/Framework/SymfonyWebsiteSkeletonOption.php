<?php

declare(strict_types=1);

namespace App\Question\Framework;

use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Question\AdditionalQuestionProviderInterface;
use App\Question\UploadFileSize\UploadFileSizeQuestion;

final class SymfonyWebsiteSkeletonOption implements FrameworkOptionInterface, AdditionalQuestionProviderInterface
{
    private UploadFileSizeQuestion $fileSizeQuestion;
    private SymfonyWebsiteSkeletonConfig $config;

    public function __construct(UploadFileSizeQuestion $fileSizeQuestion, SymfonyWebsiteSkeletonConfig $config)
    {
        $this->fileSizeQuestion = $fileSizeQuestion;
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'Symfony website-skeleton';
    }

    public function isDefault(): bool
    {
        return true;
    }

    public function getAdditionalQuestions(): array
    {
        return [
            $this->fileSizeQuestion,
        ];
    }

    public function getConfig(): SymfonyWebsiteSkeletonConfig
    {
        return $this->config;
    }
}
