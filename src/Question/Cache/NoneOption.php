<?php

declare(strict_types=1);

namespace App\Question\Cache;

class NoneOption implements CacheOptionInterface
{
    public function getName(): string
    {
        return 'None';
    }

    public function isDefault(): bool
    {
        return true;
    }

    public function getConfig()
    {
        return null;
    }
}
