<?php

declare(strict_types=1);

namespace App\Generator\ProjectName;

interface ProjectInterface
{
    public function getClientName(): string;

    public function getName(): string;

    public function getNameSpace(): string;
}
