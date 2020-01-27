<?php

declare(strict_types=1);

namespace App\Question\TestFramework;

use App\Config\TestFramework\BehatConfig;

class BehatOption implements TestFrameworkOptionInterface
{
    private BehatConfig $config;

    public function __construct(BehatConfig $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'Behat';
    }

    public function isDefault(): bool
    {
        return true;
    }

    public function getConfig(): ?BehatConfig
    {
        return $this->config;
    }
}
