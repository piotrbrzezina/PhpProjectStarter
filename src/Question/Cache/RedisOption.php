<?php

declare(strict_types=1);

namespace App\Question\Cache;

use App\Config\Cache\RedisConfig;

class RedisOption implements CacheOptionInterface
{
    private RedisConfig $config;

    public function __construct(RedisConfig $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'Redis';
    }

    public function isDefault(): bool
    {
        return false;
    }

    public function getConfig(): RedisConfig
    {
        return $this->config;
    }
}
