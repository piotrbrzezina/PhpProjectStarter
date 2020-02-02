<?php

declare(strict_types=1);

namespace App\Generator\Database;

use App\Config\ConfigCollection;
use App\Config\Database\PostgresConfig;
use App\Config\Framework\SymfonySkeletonConfig;
use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyPostgres implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!($configCollection->has(SymfonyWebsiteSkeletonConfig::class) || $configCollection->has(SymfonySkeletonConfig::class))) {
            return;
        }

        $postgresConfig = array_pop($configCollection->get(PostgresConfig::class));
        if (!$postgresConfig instanceof PostgresConfig) {
            return;
        }

        $envContent = file_get_contents($this->projectPath.'/.env');
        $databaseConnection = $postgresConfig->getConnectionData();
        $pattern = '/DATABASE_URL[a-zA-Z0-9:\/_@.?=]+/m';
        $databaseUrl = 'DATABASE_URL=postgresql://'.$databaseConnection['user'].':'.$databaseConnection['password'].'@'.$databaseConnection['host'].':'.$databaseConnection['port'].'/'.$databaseConnection['databaseName'].'?serverVersion='.$databaseConnection['version'].'&charset=utf8';
        $envContent = preg_replace($pattern, $databaseUrl, $envContent);
        file_put_contents($this->projectPath.'/.env', $envContent);
    }
}
