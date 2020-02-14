<?php

declare(strict_types=1);

namespace App\Question\Database;

use App\Config\Database\MySQLConfigSql;

class MySQLOption implements DatabaseOptionInterface
{
    private MySQLConfigSql $config;

    public function __construct(MySQLConfigSql $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return 'MySQL';
    }

    public function isDefault(): bool
    {
        return false;
    }

    public function getConfig(): MySQLConfigSql
    {
        return $this->config;
    }
}
