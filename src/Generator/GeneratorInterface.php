<?php

declare(strict_types=1);

namespace App\Generator;

use App\Config\ConfigCollection;
use Symfony\Component\Console\Output\OutputInterface;

interface GeneratorInterface
{
    public function generate(ConfigCollection $configCollection, OutputInterface $output): void;
}
