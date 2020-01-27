<?php

declare(strict_types=1);

namespace App\Generator\ShellCommand;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use Closure;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ShellCommandGenerator implements GeneratorInterface
{
    private DebugFormatterHelper $debugFormatter;

    public function __construct()
    {
        $this->debugFormatter = new DebugFormatterHelper();
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        /** @var ShelCommandConfigInterface $config */
        foreach ($configCollection->get(ShelCommandConfigInterface::class) as $config) {
            foreach ($config->getShelCommandToRun($configCollection) as $command) {
                $output->write('', true);
                $output->write('', true);
                $process = new Process($command, null, null, null, null);
                $processId = spl_object_hash($process);
                $message = $this->consoleEscape($process->getCommandLine());
                $output->write($this->debugFormatter->start($processId, $message));
                $process->run($this->printProgressClosure($output, $processId));

                if (!$process->isSuccessful()) {
                    $message = $this->consoleEscape($process->getErrorOutput());
                    $output->write($this->debugFormatter->progress($processId, $message, true));
                }
            }
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
