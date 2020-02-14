<?php

declare(strict_types=1);

namespace App\Question\Database;

use App\Config\Database\MongoDBConfig;

class MongoDBOption implements DatabaseOptionInterface
{
    private MongoDBConfig $config;

    public function __construct(MongoDBConfig $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'MongoDB';
    }

    public function isDefault(): bool
    {
        return false;
    }

    public function getConfig(): MongoDBConfig
    {
        return $this->config;
    }
}
