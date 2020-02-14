<?php

declare(strict_types=1);

namespace App\Question\Database;

use App\Config\Database\MariaDBConfigSql;

class MariaDBOption implements DatabaseOptionInterface
{
    private MariaDBConfigSql $config;

    public function __construct(MariaDBConfigSql $config)
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

    public function getConfig(): MariaDBConfigSql
    {
        return $this->config;
    }
}
