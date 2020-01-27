<?php

declare(strict_types=1);

namespace App\Generator\PhpIni;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use App\Generator\NginxConfig\NginxConfigInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class PhpIniGenerator implements GeneratorInterface
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
        $output->write($debugFormatter->start(self::class, 'Generate php.ini'));

        $config = [];
        /** @var PhpIniConfigInterface $configurator */
        foreach ($configCollection->get(PhpIniConfigInterface::class) as $configurator) {
            $config[] = $configurator->getPhpIniConfig($configCollection);
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
        }

        if (!file_exists($this->projectPath.'/docker/php-fpm')) {
            mkdir($this->projectPath.'/docker/php-fpm', 0777, true);
        }
        file_put_contents($this->projectPath.'/docker/php-fpm/php.ini', implode(PHP_EOL, $config));

        $output->write($debugFormatter->stop(self::class, 'Generate php.ini finished', true));
    }
}
