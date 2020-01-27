<?php

declare(strict_types=1);

namespace App\Generator\UploadFileSize;

use App\Config\ConfigCollection;

class UploadFileSizeHelper
{
    public static function getUploadFileSize(ConfigCollection $configCollection): ?UploadFileSizeInterface
    {
        $configs = $configCollection->get(UploadFileSizeInterface::class);
        if (empty($configs)) {
            return null;
        }
        $uploadFileSize = array_pop($configs);
        if (!$uploadFileSize instanceof UploadFileSizeInterface) {
            return null;
        }

        return $uploadFileSize;
    }
}
