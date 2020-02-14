<?php

declare(strict_types=1);

namespace App\Generator\Framework;

use App\Config\ConfigCollection;
use App\Config\Framework\SymfonySkeletonConfig;
use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Generator\Database\DatabaseMongoConnectionConfigInterface;
use App\Generator\Database\DatabaseSqlConnectionConfigInterface;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyMongoDatabaseConnectionSetup implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if ($configCollection->has(DatabaseSqlConnectionConfigInterface::class) &&
            (
                $configCollection->has(SymfonySkeletonConfig::class) ||
                $configCollection->has(SymfonyWebsiteSkeletonConfig::class)
            )
        ) {
            $configs = $configCollection->get(DatabaseMongoConnectionConfigInterface::class);
            $config = array_pop($configs);
            if (!$config instanceof DatabaseMongoConnectionConfigInterface) {
                return;
            }

            $debugFormatter = new DebugFormatterHelper();
            $output->write('', true);
            $output->write('', true);
            $output->write($debugFormatter->start(self::class, 'Configure mongo connection to database'));

            $envContent = (string) file_get_contents($this->projectPath.'/.env');
            $databaseConnection = $config->getConnectionData($configCollection);
            $pattern = '/MONGODB_URL[a-zA-Z0-9:\/_@.?=]+/m';
            $databaseUrl = 'MONGODB_URL='.$databaseConnection->getProtocol().'://'.$databaseConnection->getUser()
                .':'.$databaseConnection->getPassword().'@'.$databaseConnection->getHost().':'
                .$databaseConnection->getPort();
            $envContent = preg_replace($pattern, $databaseUrl, $envContent);

            $pattern = '/MONGODB_DB[a-zA-Z0-9:\/_@.?=]+/m';
            $databaseUrl = 'MONGODB_DB='.$databaseConnection->getDatabaseName();
            $envContent = preg_replace($pattern, $databaseUrl, $envContent);

            file_put_contents($this->projectPath.'/.env', $envContent);

            $output->write($debugFormatter->stop(self::class, 'Configure mongo connection to database finished', true));
        }
    }
}
