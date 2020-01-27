<?php

declare(strict_types=1);

namespace App\Console;

use Symfony\Component\Console\Style\SymfonyStyle;

final class ConsoleStyle extends SymfonyStyle
{
    /**
     * @param string $message
     */
    public function success($message): void
    {
        $this->writeln('<fg=green;options=bold,underscore>OK</> '.$message);
    }

    /**
     * @param string $message
     */
    public function comment($message): void
    {
        $this->text($message);
    }
}
