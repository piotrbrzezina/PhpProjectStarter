<?php
declare(strict_types=1);

namespace App\Generator\BitbucketPipelines;


use App\Config\ConfigCollection;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

interface BitbucketPipelinesConfigInterface
{
    public function getCodeQualityBitbucketPipelines(ConfigCollection $configCollection): string;
}
