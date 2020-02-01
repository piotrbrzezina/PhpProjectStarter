<?php

declare(strict_types=1);

namespace App\Command;

use App\Config\ConfigCollection;
use App\Config\DefaultConfigCollection;
use App\Console\ConsoleStyle;
use App\Generator\GeneratorProvider;
use App\Question\AdditionalQuestionProviderInterface;
use App\Question\QuestionInterface;
use App\Question\QuestionProvider;
use FilesystemIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateProject extends Command
{
    protected static $defaultName = 'project:generate';
    private FormatterHelper $formatter;
    private ConsoleStyle $io;
    private QuestionProvider $questionProvider;
    private GeneratorProvider $generatorProvider;
    private ConfigCollection $configCollection;
    private DefaultConfigCollection $finalConfigCollection;
    private DefaultConfigCollection $initialConfigCollection;

    public function __construct(
        QuestionProvider $questionProvider,
        GeneratorProvider $generatorProvider,
        ConfigCollection $configCollection,
        DefaultConfigCollection $finalConfigCollection,
        DefaultConfigCollection $initialConfigCollection,
        ?string $name = null
    ) {
        $this->questionProvider = $questionProvider;
        $this->configCollection = $configCollection;
        $this->generatorProvider = $generatorProvider;
        $this->finalConfigCollection = $finalConfigCollection;
        $this->initialConfigCollection = $initialConfigCollection;

        parent::__construct($name);
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->io = new ConsoleStyle($input, $output);
        $this->formatter = $this->getHelper('formatter');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new project')
            ->setHelp('This command allows you to create a new php project');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isProjectDirectoryEmpty()) {
            $message = $this->formatter->formatBlock('Project directory is not empty!', 'error', true);
            $output->writeln($message);

            return 0;
        }
        $this->initialConfigCollection->addConfigsToCollection($this->configCollection);
        foreach ($this->questionProvider->getQuestions() as $question) {
            $this->askQuestion($question);
        }
        $this->finalConfigCollection->addConfigsToCollection($this->configCollection);
    }

    /**
     * @param object $question
     */
    protected function askQuestion($question): void
    {
        if ($question instanceof QuestionInterface) {
            $question->askQuestion($this->io);
            if (null !== $question->getAnswer()) {
                $this->configCollection->add($question->getAnswer());
            }
            if ($question instanceof AdditionalQuestionProviderInterface) {
                foreach ($question->getAdditionalQuestions() as $additionalQuestion) {
                    $this->askQuestion($additionalQuestion);
                }
            }
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isProjectDirectoryEmpty()) {
            return 1;
        }

        foreach ($this->generatorProvider->getGenerators() as $generator) {
            $generator->generate($this->configCollection, $output);
        }

        return 0;
    }

    private function isProjectDirectoryEmpty(): bool
    {
        return !(new FilesystemIterator('./project'))->valid();
    }
}
