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

class SymfonyMissingStrictTypesFixer implements GeneratorInterface
{
    private string $projectPath;

    public function __construct( string $projectPath)
    {
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
            $output->write($debugFormatter->start(self::class, 'Add strict_types to files'));

            $fileToFix = [
                '/src/Kernel.php',
            ];
            foreach ($fileToFix as $pathToFile) {
                $consoleContent = file_get_contents($this->projectPath.$pathToFile);
                if (false !== $consoleContent) {
                    file_put_contents($this->projectPath.$pathToFile, str_replace(
                        '<?php',
                        "<?php\n\ndeclare(strict_types=1);\n\n",
                        $consoleContent
                    ));
                }
            }

            $output->write($debugFormatter->stop(self::class, 'Add strict_types to files finished', true));
        }
    }
}
