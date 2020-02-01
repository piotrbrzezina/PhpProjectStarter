<?php

declare(strict_types=1);

namespace App\Question\QuestionHelper;

interface QuestionOptionInterface
{
    public function getName(): string;

    public function isDefault(): bool;

    /**
     * @return mixed
     */
    public function getConfig();
}
