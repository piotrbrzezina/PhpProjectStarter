<?php

declare(strict_types=1);

namespace App\Config\ProjectName;

use App\Generator\ProjectName\ProjectInterface;

class ProjectConfig implements ProjectInterface
{
    private string $name;

    private string $clientName;

    public function __construct(string $clientName, string $name)
    {
        $this->name = $name;
        $this->clientName = $clientName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function getNameSpace(): string
    {
        $clientName = str_replace(' ', '', ucwords(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', ' ', $this->clientName)))));
        $projectName = str_replace(' ', '', ucwords(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', ' ', $this->name)))));

        return $clientName.'\\'.$projectName;
    }
}
