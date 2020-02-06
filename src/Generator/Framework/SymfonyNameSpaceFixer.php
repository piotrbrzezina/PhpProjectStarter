<?php

declare(strict_types=1);

namespace App\Generator\Framework;

use App\Config\ConfigCollection;
use App\Config\Framework\SymfonySkeletonConfig;
use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Config\ProjectName\ProjectConfig;
use App\Generator\GeneratorInterface;
use App\Generator\PhpIni\PhpIniConfigInterface;
use App\Generator\ProjectName\ProjectHelper;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment as Twig;

class SymfonyNameSpaceFixer implements GeneratorInterface
{
    private Twig $twig;

    private string $projectPath;

    public function __construct(Twig $twig, string $projectPath)
    {
        $this->twig = $twig;
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        $projectConfig = ProjectHelper::getProject($configCollection);

        if ($projectConfig instanceof ProjectConfig &&
            (
                $configCollection->has(SymfonySkeletonConfig::class) ||
                $configCollection->has(SymfonyWebsiteSkeletonConfig::class)
            )
        ) {
            $debugFormatter = new DebugFormatterHelper();
            $output->write('', true);
            $output->write('', true);
            $output->write($debugFormatter->start(self::class, 'Change file namespace'));

            $config = [];
            /** @var PhpIniConfigInterface $configurator */
            foreach ($configCollection->get(PhpIniConfigInterface::class) as $configurator) {
                $config[] = $configurator->getPhpIniConfig($configCollection);
                $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            }
            $fileToFix = [
                '/bin/console',
                '/config/routes.yaml',
                '/config/packages/validator.yaml',
                '/config/packages/doctrine.yaml',
                '/config/packages/doctrine_migrations.yaml',
                '/config/routes.yaml',
                '/config/services.yaml',
                '/config/services_test.yaml',
                '/public/index.php',
                '/src/Kernel.php',
            ];
            foreach ($fileToFix as $pathToFile) {
                $consoleContent = file_get_contents($this->projectPath.$pathToFile);
                if (false !== $consoleContent) {
                    file_put_contents($this->projectPath.$pathToFile, str_replace(
                        [
                            'App\\\\',
                            'App\\',
                            'namespace App;',
                            'App:',
                            'alias: App',
                        ],
                        [
                            $projectConfig->getNameSpace().'\\\\',
                            $projectConfig->getNameSpace().'\\',
                            'namespace '.$projectConfig->getNameSpace().';',
                            $projectConfig->getNameSpace().':',
                            'alias: '.$projectConfig->getNameSpace(),
                        ],
                        $consoleContent
                    ));
                }
            }

            if ($configCollection->has(SymfonyWebsiteSkeletonConfig::class)) {
                if (!file_exists($this->projectPath.'/config/packages/dev')) {
                    mkdir($this->projectPath.'/config/packages/dev', 0777, true);
                }
                file_put_contents(
                    $this->projectPath.'/config/packages/dev/maker.yaml',
                    $this->twig->render(
                        'Generator/Framework/Symfony/maker.yaml.twig',
                        ['projectNamespace' => $projectConfig->getNameSpace()]
                    )
                );
            }

            $output->write($debugFormatter->stop(self::class, 'Change file namespace finished', true));
        }
    }
}
