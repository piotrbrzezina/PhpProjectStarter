<?php

declare(strict_types=1);

namespace App\Generator\Composer;

use App\Config\ConfigCollection;
use App\Config\ProjectName\ProjectConfig;
use App\Generator\GeneratorInterface;
use App\Generator\PhpIni\PhpIniConfigInterface;
use App\Generator\ProjectName\ProjectHelper;
use Closure;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ComposerAutoloadNameSpaceFixer implements GeneratorInterface
{
    private string $projectPath;

    private DebugFormatterHelper $debugFormatter;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->debugFormatter = new DebugFormatterHelper();
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        $projectConfig = ProjectHelper::getProject($configCollection);
        if ($projectConfig instanceof ProjectConfig) {
            $debugFormatter = new DebugFormatterHelper();
            $output->write('', true);
            $output->write('', true);
            $output->write($debugFormatter->start(self::class, 'Change autoload namespace'));

            $config = [];
            /** @var PhpIniConfigInterface $configurator */
            foreach ($configCollection->get(PhpIniConfigInterface::class) as $configurator) {
                $config[] = $configurator->getPhpIniConfig($configCollection);
                $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            }
            $composerJsonContent = (string) file_get_contents($this->projectPath.'/composer.json');
            file_put_contents($this->projectPath.'/composer.json', str_replace(
                [
                    '"App\\\\"',
                    '"App\\\\Tests\\\\"',
                ],
                [
                    '"'.str_replace('\\', '\\\\', $projectConfig->getNameSpace()).'\\\\"',
                    '"'.str_replace('\\', '\\\\', $projectConfig->getNameSpace()).'\\\\Tests\\\\"',
                ],
                $composerJsonContent
            ));

            $output->write('', true);
            $process = new Process(['composer', 'install', '--working-dir', $this->projectPath, '--ignore-platform-reqs'], null, null, null, null);
            $processId = spl_object_hash($process);
            $message = $this->consoleEscape($process->getCommandLine());
            $output->write($this->debugFormatter->start($processId, $message));
            $process->run($this->printProgressClosure($output, $processId));

            if (!$process->isSuccessful()) {
                $message = $this->consoleEscape($process->getErrorOutput());
                $output->write($this->debugFormatter->progress($processId, $message, true));
            }
            $process->run();

            $output->write($debugFormatter->stop(self::class, 'Change autoload namespace finished', true));
        }
    }

    private function consoleEscape(string $data): string
    {
        return str_replace('<', '\\<', $data);
    }

    /**
     * @param OutputInterface $output
     * @param string          $processId
     *
     * @return Closure
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @noinspection PhpUnusedParameterInspection
     */
    private function printProgressClosure(OutputInterface $output, string $processId): Closure
    {
        return function ($type, $data) use ($output, $processId): void {
            $output->write($this->debugFormatter->progress($processId, $this->consoleEscape($data)));
        };
    }
}
