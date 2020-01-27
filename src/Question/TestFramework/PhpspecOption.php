<?php

declare(strict_types=1);

namespace App\Question\TestFramework;

use App\Config\TestFramework\PhpspecConfig;

class PhpspecOption implements TestFrameworkOptionInterface
{
    private PhpspecConfig $config;

    public function __construct(PhpspecConfig $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'Phpspec';
    }

    public function isDefault(): bool
    {
        return true;
    }

    public function getConfig(): ?PhpspecConfig
    {
        return $this->config;
    }
}
