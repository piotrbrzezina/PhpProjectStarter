<?php

declare(strict_types=1);

namespace App\Config\UploadFileSize;

use App\Generator\UploadFileSize\UploadFileSizeInterface;

class UploadFileSizeConfig implements UploadFileSizeInterface
{
    private int $maxUploadFileSize;
    private int $maxBodySize;

    public function __construct(int $maxUploadFileSize, int $maxBodySize)
    {
        $this->maxUploadFileSize = $maxUploadFileSize;
        $this->maxBodySize = $maxBodySize;
    }

    public function getMaxUploadFileSize(): int
    {
        return $this->maxUploadFileSize;
    }

    public function getMaxBodySize(): int
    {
        return $this->maxBodySize;
    }
}
