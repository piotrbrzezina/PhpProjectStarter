<?php

declare(strict_types=1);

namespace App\Generator\Database;

use App\Config\ConfigCollection;

interface DatabaseMongoConnectionConfigInterface
{
    public function getConnectionData(ConfigCollection $configCollection): DatabaseConnectionConfig;
}
