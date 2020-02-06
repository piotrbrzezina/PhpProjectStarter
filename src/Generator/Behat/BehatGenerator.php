<?php

declare(strict_types=1);

namespace App\Generator\Behat;

use App\Config\ConfigCollection;
use App\Config\TestFramework\BehatConfig;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class BehatGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!$configCollection->has(BehatConfig::class)) {
            return;
        }

        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate Behat'));

        if (!file_exists($this->projectPath.'/features/bootstrap')) {
            mkdir($this->projectPath.'/features/bootstrap', 0777, true);
        }

        $config = [];
        /** @var BehatConfigInterface $configurator */
        foreach ($configCollection->get(BehatConfigInterface::class) as $configurator) {
            $config[] = $configurator->getBehatConfig($configCollection);
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
        }
        file_put_contents($this->projectPath.'/behat.yml', implode(PHP_EOL, $config));

        /** @var BehatConfigInterface $configurator */
        foreach ($configCollection->get(BehatConfigInterface::class) as $configurator) {
            $featureFiles = $configurator->getBehatFeatureFile($configCollection);
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            foreach ($featureFiles as $file) {
                file_put_contents($this->projectPath.'/features/bootstrap/'.$file->getName(), $file->getContent());
            }
        }

        $output->write($debugFormatter->stop(self::class, 'Generate Behat finished', true));
    }
}
