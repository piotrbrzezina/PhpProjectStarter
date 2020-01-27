<?php

declare(strict_types=1);

namespace App\Question\Database;

use App\Config\Database\MySQLConfig;

class MySQLOption implements DatabaseOptionInterface
{
    private MySQLConfig $config;

    public function __construct(MySQLConfig $config)
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

    public function getConfig(): MySQLConfig
    {
        return $this->config;
    }
}
