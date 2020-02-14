<?php

declare(strict_types=1);

namespace App\Question\Database;

use App\Config\Database\PostgresConfigSql;

class PostgresOption implements DatabaseOptionInterface
{
    private PostgresConfigSql $config;

    public function __construct(PostgresConfigSql $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'Postgres';
    }

    public function isDefault(): bool
    {
        return true;
    }

    public function getConfig(): PostgresConfigSql
    {
        return $this->config;
    }
}
