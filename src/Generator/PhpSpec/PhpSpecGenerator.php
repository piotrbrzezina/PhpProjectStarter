<?php

declare(strict_types=1);

namespace App\Generator\PhpSpec;

use App\Config\ConfigCollection;
use App\Config\TestFramework\PhpspecConfig;
use App\Generator\GeneratorInterface;
use App\Generator\PhpIni\PhpIniConfigInterface;
use Exception;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class PhpSpecGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    /**
     * @param ConfigCollection $configCollection
     * @param OutputInterface  $output
     *
     * @throws Exception
     */
    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!$configCollection->has(PhpspecConfig::class)) {
            return;
        }

        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate PhpSpec'));

        $config = [];
        /** @var PhpIniConfigInterface $configurator */
        foreach ($configCollection->get(PhpIniConfigInterface::class) as $configurator) {
            $config[] = $configurator->getPhpIniConfig($configCollection);
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
        }

        if (!file_exists($this->projectPath.'/.phpspec')) {
            mkdir($this->projectPath.'/.phpspec', 0777, true);
        }
        file_put_contents($this->projectPath.'/.phpspec/class.tpl', file_get_contents('templates/Config/TestFramework/PhpSpec/Templates/class.tpl'));
        file_put_contents($this->projectPath.'/.phpspec/interface.tpl', file_get_contents('templates/Config/TestFramework/PhpSpec/Templates/interface.tpl'));
        file_put_contents($this->projectPath.'/.phpspec/interface_method_signature.tpl', file_get_contents('templates/Config/TestFramework/PhpSpec/Templates/interface_method_signature.tpl'));
        file_put_contents($this->projectPath.'/.phpspec/method.tpl', file_get_contents('templates/Config/TestFramework/PhpSpec/Templates/method.tpl'));
        file_put_contents($this->projectPath.'/.phpspec/named_constructor_create_object.tpl', file_get_contents('templates/Config/TestFramework/PhpSpec/Templates/named_constructor_create_object.tpl'));
        file_put_contents($this->projectPath.'/.phpspec/named_constructor_exception.tpl', file_get_contents('templates/Config/TestFramework/PhpSpec/Templates/named_constructor_exception.tpl'));
        file_put_contents($this->projectPath.'/.phpspec/private-constructor.tpl', file_get_contents('templates/Config/TestFramework/PhpSpec/Templates/private-constructor.tpl'));
        file_put_contents($this->projectPath.'/.phpspec/returnconstant.tpl', file_get_contents('templates/Config/TestFramework/PhpSpec/Templates/returnconstant.tpl'));
        file_put_contents($this->projectPath.'/.phpspec/specification.tpl', file_get_contents('templates/Config/TestFramework/PhpSpec/Templates/specification.tpl'));

        $config = [];
        /** @var PhpspecConfig $configurator */
        foreach ($configCollection->get(PhpspecConfig::class) as $configurator) {
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
            $config[] = $configurator->getPhpSpecConfigFile($configCollection);
        }

        file_put_contents($this->projectPath.'/phpspec.yml', implode(PHP_EOL, $config));
        $output->write($debugFormatter->stop(self::class, 'Generate PhpSpec finished', true));
    }
}
