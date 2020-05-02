<?php

declare(strict_types=1);

namespace App\Config\Database\Integration;

use App\Config\ConfigCollection;
use App\Config\Database\MariaDBConfigSql;
use App\Config\Database\MongoDBConfig;
use App\Config\Database\MySQLConfigSql;
use App\Config\Database\PostgresConfigSql;
use App\Config\FinishConfigInterface;
use App\Config\Framework\SymfonySkeletonConfig;
use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Generator\Makefile\MakefileSetupProjectConfigInterface;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Twig\Environment as Twig;

class SymfonyIntegrationConfig implements FinishConfigInterface, MakefileSetupProjectConfigInterface, ShelCommandConfigInterface
{
    private Twig $twig;
    private string $projectPath;

    public function __construct(Twig $twig, string $projectPath)
    {
        $this->twig = $twig;
        $this->projectPath = $projectPath;
    }

    public function getSetupProjectStep(ConfigCollection $configCollection): string
    {
        if (!$this->isSymfonySelected($configCollection) || !$this->isSqlDatabaseSelected($configCollection)) {
            return '';
        }

        return $this->twig->render('Config/Database/Integration/SymfonyIntegration/Makefile.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        if (!$this->isSymfonySelected($configCollection) || !($this->isSqlDatabaseSelected($configCollection) || $this->isMongoSelected($configCollection))) {
            return [];
        }

        $libraries = [];
        if ($this->isSqlDatabaseSelected($configCollection)) {
            $libraries[] = ['composer', 'require', '--working-dir', $this->projectPath, 'symfony/orm-pack'];
        } elseif ($this->isMongoSelected($configCollection)) {
            $libraries[] = ['composer', 'remove', '--working-dir', $this->projectPath, 'symfony/odm-pack'];
        }

        $libraries[] = ['composer', 'require', '--working-dir', $this->projectPath, '--dev', 'symfony/maker-bundle'];

        return $libraries;
    }

    private function isSymfonySelected(ConfigCollection $configCollection): bool
    {
        return $configCollection->has(SymfonyWebsiteSkeletonConfig::class) ||
            $configCollection->has(SymfonySkeletonConfig::class);
    }

    private function isSqlDatabaseSelected(ConfigCollection $configCollection): bool
    {
        return $configCollection->has(MySQLConfigSql::class) ||
            $configCollection->has(MariaDBConfigSql::class) ||
            $configCollection->has(PostgresConfigSql::class);
    }

    private function isMongoSelected(ConfigCollection $configCollection): bool
    {
        return $configCollection->has(MongoDBConfig::class);
    }
}
