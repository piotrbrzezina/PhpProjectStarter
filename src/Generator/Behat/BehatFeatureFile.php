<?php

declare(strict_types=1);

namespace App\Generator\Behat;

class BehatFeatureFile implements BehatFeatureFileInterface
{
    private string $name;
    private string $content;

    public function __construct(string $name, string $content)
    {
        $this->name = $name;
        $this->content = $content;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
