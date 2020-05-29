<?php

declare(strict_types=1);

namespace App\Config\TestFramework;

use App\Config\ConfigCollection;
use App\Generator\BitbucketPipelines\BitbucketPipelinesRunTestConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Twig\Environment as Twig;

class PHPUnitConfig implements ShelCommandConfigInterface, MakefileConfigInterface, BitbucketPipelinesRunTestConfigInterface
{
    private string $projectPath;

    private Twig $twig;

    public function __construct(string $projectPath, Twig $twig)
    {
        $this->projectPath = $projectPath;
        $this->twig = $twig;
    }

    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        return [
            ['composer', 'require', 'phpunit/phpunit:^8.3', '--working-dir', $this->projectPath, '--dev', '--no-suggest', '--no-scripts'],
        ];
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/TestFramework/PhpUnit/Makefile.twig');
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getTestToRunOnBitbucketPipelines(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/TestFramework/PhpUnit/bitbucket-pipelines.yml.twig');
    }
}
