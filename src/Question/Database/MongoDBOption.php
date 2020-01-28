<?php

declare(strict_types=1);

namespace App\Question\Database;

use App\Config\Database\PostgresConfig;

class MongoDBOption implements DatabaseOptionInterface
{
    private PostgresConfig $config;

    public function __construct(PostgresConfig $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'MongoDB';
    }

    public function isDefault(): bool
    {
        return true;
    }

    public function getConfig(): PostgresConfig
    {
        return $this->config;
    }
}
