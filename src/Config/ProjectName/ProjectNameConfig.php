<?php

declare(strict_types=1);

namespace App\Config\ProjectName;

use App\Generator\ProjectName\ProjectNameInterface;

class ProjectNameConfig implements ProjectNameInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
