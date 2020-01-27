<?php

declare(strict_types=1);

namespace App\Generator\Dockerfile;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class DockerfileGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!$configCollection->has(DockerfileBuildStepsConfigInterface::class)) {
            return;
        }
        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate nginx.conf'));

        $config = [];
        /** @var DockerfileBuildStepsConfigInterface $configurator */
        foreach ($configCollection->get(DockerfileBuildStepsConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getDockerfileBuildSteps($configCollection);
        }
        if (!file_exists($this->projectPath.'/docker')) {
            mkdir($this->projectPath.'/docker');
        }
        file_put_contents($this->projectPath.'/docker/Dockerfile', implode(PHP_EOL, $config));

        $output->write($debugFormatter->stop(self::class, 'Generate Dockerfile finished', true));
    }
}
