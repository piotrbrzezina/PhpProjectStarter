<?php

declare(strict_types=1);

namespace App\Generator\DockerCompose;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class DockerComposeGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!$configCollection->has(DockerComposeConfigInterface::class)) {
            return;
        }
        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate docker-compose.yaml'));

        $config = [];
        /** @var DockerComposeConfigInterface $configurator */
        foreach ($configCollection->get(DockerComposeConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getDockerComposeData($configCollection);
        }

        if (!file_exists($this->projectPath.'/docker')) {
            mkdir($this->projectPath.'/docker');
        }
        file_put_contents($this->projectPath.'/docker-compose.yaml', implode(PHP_EOL, array_filter($config)));

        $config = [];
        /** @var DockerComposeCiConfigInterface $configurator */
        foreach ($configCollection->get(DockerComposeCiConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getDockerComposeCiData($configCollection);
        }

        if (!file_exists($this->projectPath.'/docker')) {
            mkdir($this->projectPath.'/docker');
        }
        file_put_contents($this->projectPath.'/docker/docker-compose-ci.yaml', implode(PHP_EOL, array_filter($config)));

        $output->write($debugFormatter->stop(self::class, 'Generate docker-compose.yaml finished', true));
    }
}
