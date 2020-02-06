<?php

declare(strict_types=1);

namespace App\Generator\BitbucketPipelines;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class BitbucketPipelinesGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate bitbucket-pipelines.yml'));

        $config = [];
        /** @var BitbucketPipelinesConfigInterface $configurator */
        foreach ($configCollection->get(BitbucketPipelinesConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getBitbucketPipelines($configCollection);
        }

        /** @var BitbucketPipelinesPullRequestConfigInterface $configurator */
        foreach ($configCollection->get(BitbucketPipelinesPullRequestConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getPullRequestsBeforeTestBitbucketPipelines($configCollection);
        }

        /** @var BitbucketPipelinesCodeQualityConfigInterface $configurator */
        foreach ($configCollection->get(BitbucketPipelinesCodeQualityConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getCodeQualityBitbucketPipelines($configCollection);
        }

        /** @var BitbucketPipelinesRunTestConfigInterface $configurator */
        foreach ($configCollection->get(BitbucketPipelinesRunTestConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getTestToRunOnBitbucketPipelines($configCollection);
        }

        /** @var BitbucketPipelinesPullRequestConfigInterface $configurator */
        foreach ($configCollection->get(BitbucketPipelinesPullRequestConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getPullRequestsAfterTestBitbucketPipelines($configCollection);
        }

        /** @var BitbucketPipelinesTagConfigInterface $configurator */
        foreach ($configCollection->get(BitbucketPipelinesTagConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getTagsBeforeTestBitbucketPipelines($configCollection);
        }

        /** @var BitbucketPipelinesCodeQualityConfigInterface $configurator */
        foreach ($configCollection->get(BitbucketPipelinesCodeQualityConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getCodeQualityBitbucketPipelines($configCollection);
        }

        /** @var BitbucketPipelinesRunTestConfigInterface $configurator */
        foreach ($configCollection->get(BitbucketPipelinesRunTestConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getTestToRunOnBitbucketPipelines($configCollection);
        }

        /** @var BitbucketPipelinesTagConfigInterface $configurator */
        foreach ($configCollection->get(BitbucketPipelinesTagConfigInterface::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getTagsAfterTestBitbucketPipelines($configCollection);
        }

        file_put_contents($this->projectPath.'/bitbucket-pipelines.yml', implode(PHP_EOL, $config));

        $output->write($debugFormatter->stop(self::class, 'Generate bitbucket-pipelines.yml finished', true));
    }
}
