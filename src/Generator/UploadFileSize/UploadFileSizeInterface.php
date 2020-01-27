<?php

declare(strict_types=1);

namespace App\Generator\UploadFileSize;

interface UploadFileSizeInterface
{
    public function getMaxUploadFileSize(): int;

    public function getMaxBodySize(): int;
}
