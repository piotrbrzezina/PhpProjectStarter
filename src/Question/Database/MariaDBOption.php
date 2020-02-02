<?php

declare(strict_types=1);

namespace App\Question\Database;

use App\Config\Database\MongoDBConfig;

class MariaDBOption implements DatabaseOptionInterface
{
    private MongoDBConfig $config;

    public function __construct(MongoDBConfig $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'MariaDB';
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
