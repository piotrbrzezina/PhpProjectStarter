<?php

declare(strict_types=1);

namespace App\Config\Framework;

use App\Config\ConfigCollection;
use App\Config\ProjectName\ProjectConfig;
use App\Generator\Behat\BehatConfigInterface;
use App\Generator\Behat\BehatFeatureFile;
use App\Generator\BitbucketPipelines\BitbucketPipelinesPullRequestConfigInterface;
use App\Generator\BitbucketPipelines\BitbucketPipelinesTagConfigInterface;
use App\Generator\DockerCompose\DockerComposeCiConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\DockerCompose\DockerComposeOverrideConfigInterface;
use App\Generator\Dockerfile\DockerfileBuildStepsConfigInterface;
use App\Generator\Git\GitIgnoreConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use App\Generator\NginxConfig\NginxConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsHelper;
use App\Generator\PhpIni\PhpIniConfigInterface;
use App\Generator\ProjectName\ProjectHelper;
use App\Generator\UploadFileSize\UploadFileSizeHelper;
use Twig\Environment as Twig;

abstract class SymfonyConfig implements PhpExtensionsConfigInterface, DockerfileBuildStepsConfigInterface, DockerComposeConfigInterface, DockerComposeCiConfigInterface, DockerComposeOverrideConfigInterface, NginxConfigInterface, PhpIniConfigInterface, MakefileConfigInterface, BitbucketPipelinesPullRequestConfigInterface, BitbucketPipelinesTagConfigInterface, BehatConfigInterface, GitIgnoreConfigInterface
{
    private Twig $twig;
    private string $dockerPhpBasieImage;
    private string $dockerWriteRepository;
    private string $dockerReadRepository;
    private string $dockerRepositoryPrefix;

    public function __construct(
        Twig $twig,
        string $dockerPhpBasieImage,
        string $dockerWriteRepository,
        string $dockerReadRepository,
        string $dockerRepositoryPrefix
    ) {
        $this->twig = $twig;
        $this->dockerPhpBasieImage = $dockerPhpBasieImage;
        $this->dockerWriteRepository = $dockerWriteRepository;
        $this->dockerReadRepository = $dockerReadRepository;
        $this->dockerRepositoryPrefix = $dockerRepositoryPrefix;
    }

    public function getPhpExtensions(ConfigCollection $configCollection): array
    {
        return ['php-fpm', 'php'];
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getDockerfileBuildSteps(ConfigCollection $configCollection): string
    {
        $phpExtensions = PhpExtensionsHelper::getPhpExtensions($configCollection);
        $dockerPhpBasieImage = $this->dockerPhpBasieImage;

        return $this->twig->render(
            'Config/Framework/Symfony/Dockerfile.twig',
            compact('phpExtensions', 'dockerPhpBasieImage')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getDockerComposeData(ConfigCollection $configCollection): string
    {
        $projectName = 'defaultProjectName';
        $clientName = 'defaultClientName';

        if ($configurator = ProjectHelper::getProject($configCollection)) {
            $projectName = $configurator->getName();
            $clientName = $configurator->getClientName();
        }

        return $this->twig->render(
            'Config/Framework/Symfony/docker-compose.yaml.twig',
            compact('projectName', 'clientName')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getDockerComposeCiData(ConfigCollection $configCollection): string
    {
        $projectName = 'defaultProjectName';
        $clientName = 'defaultClientName';
        $dockerReadRepository = $this->dockerReadRepository;
        $dockerRepositoryPrefix = $this->dockerRepositoryPrefix;

        if ($configurator = ProjectHelper::getProject($configCollection)) {
            $projectName = $configurator->getName();
            $clientName = $configurator->getClientName();
        }

        return $this->twig->render(
            'Config/Framework/Symfony/docker-compose-ci.yaml.twig',
            compact('projectName', 'clientName', 'dockerReadRepository', 'dockerRepositoryPrefix')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getDockerComposeOverrideData(ConfigCollection $configCollection): string
    {
        $projectName = 'defaultProjectName';
        $clientName = 'defaultClientName';
        $dockerReadRepository = $this->dockerReadRepository;
        $dockerRepositoryPrefix = $this->dockerRepositoryPrefix;

        if ($configurator = ProjectHelper::getProject($configCollection)) {
            $projectName = $configurator->getName();
            $clientName = $configurator->getClientName();
        }

        return $this->twig->render(
            'Config/Framework/Symfony/docker-compose.override.yaml.twig',
            compact('projectName', 'clientName', 'dockerReadRepository', 'dockerRepositoryPrefix')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getNginxConfig(ConfigCollection $configCollection): string
    {
        $clientMaxBodySize = 16;
        if ($configurator = UploadFileSizeHelper::getUploadFileSize($configCollection)) {
            $clientMaxBodySize = $configurator->getMaxBodySize();
        }

        return $this->twig->render(
            'Config/Framework/Symfony/nginx.conf.twig',
            compact('clientMaxBodySize')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getPhpIniConfig(ConfigCollection $configCollection): string
    {
        $uploadMaxFileSize = 8;
        $clientMaxBodySize = 16;
        if ($configurator = UploadFileSizeHelper::getUploadFileSize($configCollection)) {
            $clientMaxBodySize = $configurator->getMaxBodySize();
            $uploadMaxFileSize = $configurator->getMaxUploadFileSize();
        }

        return $this->twig->render(
            'Config/Framework/Symfony/php.ini.twig',
            compact('clientMaxBodySize', 'uploadMaxFileSize')
        );
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
        $projectName = 'defaultProjectName';
        $clientName = 'defaultClientName';
        $dockerWriteRepository = $this->dockerWriteRepository;
        $dockerRepositoryPrefix = $this->dockerRepositoryPrefix;

        if ($configurator = ProjectHelper::getProject($configCollection)) {
            $projectName = $configurator->getName();
            $clientName = $configurator->getClientName();
        }

        return $this->twig->render(
            'Config/Framework/Symfony/Makefile.twig',
            compact('projectName', 'clientName', 'dockerWriteRepository', 'dockerRepositoryPrefix')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getPullRequestsBeforeTestBitbucketPipelines(ConfigCollection $configCollection): string
    {
        $projectName = 'defaultProjectName';
        $clientName = 'defaultClientName';
        $dockerReadRepository = $this->dockerReadRepository;
        $dockerRepositoryPrefix = $this->dockerRepositoryPrefix;

        if ($configurator = ProjectHelper::getProject($configCollection)) {
            $projectName = $configurator->getName();
            $clientName = $configurator->getClientName();
        }

        return $this->twig->render(
            'Config/Framework/Symfony/BitbucketPipelines/before-pull-request.yml.twig',
            compact('projectName', 'clientName', 'dockerReadRepository', 'dockerRepositoryPrefix')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getPullRequestsAfterTestBitbucketPipelines(ConfigCollection $configCollection): string
    {
        $dockerWriteRepository = $this->dockerWriteRepository;
        $dockerReadRepository = $this->dockerReadRepository;
        $dockerRepositoryPrefix = $this->dockerRepositoryPrefix;

        return $this->twig->render(
            'Config/Framework/Symfony/BitbucketPipelines/after-pull-request.yml.twig',
            compact('dockerWriteRepository', 'dockerReadRepository', 'dockerRepositoryPrefix')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getTagsBeforeTestBitbucketPipelines(ConfigCollection $configCollection): string
    {
        $projectName = 'defaultProjectName';
        $clientName = 'defaultClientName';
        $dockerReadRepository = $this->dockerReadRepository;
        $dockerRepositoryPrefix = $this->dockerRepositoryPrefix;

        if ($configurator = ProjectHelper::getProject($configCollection)) {
            $projectName = $configurator->getName();
            $clientName = $configurator->getClientName();
        }

        return $this->twig->render(
            'Config/Framework/Symfony/BitbucketPipelines/before-tag.yml.twig',
            compact('projectName', 'clientName', 'dockerReadRepository', 'dockerRepositoryPrefix')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getTagsAfterTestBitbucketPipelines(ConfigCollection $configCollection): string
    {
        $dockerWriteRepository = $this->dockerWriteRepository;
        $dockerReadRepository = $this->dockerReadRepository;
        $dockerRepositoryPrefix = $this->dockerRepositoryPrefix;

        return $this->twig->render(
            'Config/Framework/Symfony/BitbucketPipelines/after-tag.yml.twig',
            compact('dockerWriteRepository', 'dockerReadRepository', 'dockerRepositoryPrefix')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getBehatConfig(ConfigCollection $configCollection): string
    {
        $projectConfig = ProjectHelper::getProject($configCollection);
        if ($projectConfig instanceof ProjectConfig) {
            return $this->twig->render(
                'Config/Framework/Symfony/Behat/behat.yml.twig',
                ['projectNameSpace' => $projectConfig->getNameSpace()]
            );
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getBehatFeatureFile(ConfigCollection $configCollection): array
    {
        return [
            new BehatFeatureFile(
                'FeatureContext.php',
                $this->twig->render('Config/Framework/Symfony/Behat/FeatureContext.php.twig')
            ),
        ];
    }

    public function getGitIgnoreContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Framework/Symfony/gitignore.twig');
    }
}
