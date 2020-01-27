<?php

declare(strict_types=1);

namespace App\Question\Database;

use App\Config\Database\MariaDBConfig;

class MariaDBOption implements DatabaseOptionInterface
{
    private MariaDBConfig $config;

    public function __construct(MariaDBConfig $config)
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

    public function getConfig(): MariaDBConfig
    {
        return $this->config;
    }
}
