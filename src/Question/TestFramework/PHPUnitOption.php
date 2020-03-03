<?php

declare(strict_types=1);

namespace App\Question\TestFramework;

use App\Config\TestFramework\PHPUnitConfig;

class PHPUnitOption //implements TestFrameworkOptionInterface
{
    private PHPUnitConfig $config;

    public function __construct(PHPUnitConfig $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'PHPUnit';
    }

    public function isDefault(): bool
    {
        return false;
    }

    public function getConfig(): ?PHPUnitConfig
    {
        return $this->config;
    }
}
