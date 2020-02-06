<?php

declare(strict_types=1);

namespace App\Generator\Behat;

interface BehatFeatureFileInterface
{
    public function getName(): string;

    public function getContent(): string;
}
