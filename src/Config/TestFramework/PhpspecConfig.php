<?php

declare(strict_types=1);

namespace App\Config\TestFramework;

use App\Config\ConfigCollection;
use App\Config\ProjectName\ProjectConfig;
use App\Generator\BitbucketPipelines\BitbucketPipelinesRunTestConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use App\Generator\ProjectName\ProjectHelper;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Exception;
use Twig\Environment as Twig;

class PhpspecConfig implements ShelCommandConfigInterface, MakefileConfigInterface, BitbucketPipelinesRunTestConfigInterface
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
            ['composer', 'require', 'phpspec/phpspec', '--working-dir', $this->projectPath, '--dev', '--no-suggest', '--no-scripts'],
        ];
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws Exception
     */
    public function getPhpSpecConfigFile(ConfigCollection $configCollection): string
    {
        $projectConfig = ProjectHelper::getProject($configCollection);
        if ($projectConfig instanceof ProjectConfig) {
            return $this->twig->render('Config/TestFramework/PhpSpec/phpspec.yml.twig', [
                'projectName' => $projectConfig->getName(),
                'projectNameSpace' => $projectConfig->getNameSpace(),
            ]);
        }

        return '';
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws Exception
     */
    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/TestFramework/PhpSpec/Makefile.twig');
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws Exception
     */
    public function getTestToRunOnBitbucketPipelines(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/TestFramework/PhpSpec/bitbucket-pipelines.yml.twig');
    }
}
