<?php

declare(strict_types=1);

namespace App\Config\TestFramework;

use App\Config\ConfigCollection;
use App\Config\ProjectName\ProjectConfig;
use App\Generator\ProjectName\ProjectNameHelper;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Exception;
use Twig\Environment as Twig;

class PhpspecConfig implements ShelCommandConfigInterface
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
        $projectConfig = ProjectNameHelper::getProjectName($configCollection);
        if ($projectConfig instanceof ProjectConfig) {
            return $this->twig->render('Config/TestFramework/PhpSpec/phpspec.yml.twig', [
                'projectName' => $projectConfig->getName(),
                'projectNameSpace' => $projectConfig->getNameSpace(),
            ]);
        }

        return '';
    }
}
