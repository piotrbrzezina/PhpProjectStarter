<?php

declare(strict_types=1);

namespace App\Generator\NginxConfig;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class NginxConfigGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!$configCollection->has(NginxConfigInterface::class)) {
            return;
        }
        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate nginx.conf'));
        $config = [];
        /** @var NginxConfigInterface $configurator */
        foreach ($configCollection->get(NginxConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getNginxConfig($configCollection);
        }

        if (!file_exists($this->projectPath.'/docker/nginx')) {
            mkdir($this->projectPath.'/docker/nginx', 0777, true);
        }
        file_put_contents($this->projectPath.'/docker/nginx/nginx.conf', implode(PHP_EOL, $config));
        $output->write($debugFormatter->stop(self::class, 'Generate nginx.conf finished', true));
    }
}
