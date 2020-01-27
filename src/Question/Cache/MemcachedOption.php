<?php

declare(strict_types=1);

namespace App\Question\Cache;

use App\Config\Cache\MemcachedConfig;

class MemcachedOption implements CacheOptionInterface
{
    private MemcachedConfig $config;

    public function __construct(MemcachedConfig $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'Memcached';
    }

    public function isDefault(): bool
    {
        return false;
    }

    public function getConfig(): MemcachedConfig
    {
        return $this->config;
    }
}
