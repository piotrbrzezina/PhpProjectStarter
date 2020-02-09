<?php

declare(strict_types=1);

namespace App\Config\Database\Integration;

use App\Config\ConfigCollection;
use App\Config\Database\MariaDBConfig;
use App\Config\Database\MySQLConfig;
use App\Config\Database\PostgresConfig;
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
        if (!$this->isSymfonySelected($configCollection) && !$this->isSqlDatabaseSelected($configCollection)) {
            return '';
        }

        return $this->twig->render('Config/Database/Integration/SymfonyIntegration/Makefile.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        if (!$this->isSymfonySelected($configCollection) && !$this->isSqlDatabaseSelected($configCollection)) {
            return [];
        }

        return [
            ['composer', 'require', '--working-dir', $this->projectPath, 'symfony/orm-pack'],
            ['composer', 'require', '--working-dir', $this->projectPath, '--dev', 'symfony/maker-bundle'],
        ];
    }

    private function isSymfonySelected(ConfigCollection $configCollection): bool
    {
        return $configCollection->has(SymfonyWebsiteSkeletonConfig::class) ||
            $configCollection->has(SymfonySkeletonConfig::class);
    }

    private function isSqlDatabaseSelected(ConfigCollection $configCollection): bool
    {
        return $configCollection->has(MySQLConfig::class) ||
            $configCollection->has(MariaDBConfig::class) ||
            $configCollection->has(PostgresConfig::class);
    }
}
