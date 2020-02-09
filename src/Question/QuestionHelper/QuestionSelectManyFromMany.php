<?php

declare(strict_types=1);

namespace App\Question\QuestionHelper;

use App\Console\ConsoleStyle;
use App\Question\AdditionalQuestionProviderInterface;
use App\Question\QuestionInterface;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\Console\Question\Question;

abstract class QuestionSelectManyFromMany implements QuestionInterface, AdditionalQuestionProviderInterface
{
    /**
     * @var QuestionOptionInterface[]
     */
    protected array $selectedOptions = [];

    /**
     * @var QuestionOptionInterface[]
     */
    private array $options = [];

    /**
     * @param QuestionOptionInterface[] $options
     */
    public function __construct(iterable $options)
    {
        foreach ($options as $option) {
            if (!$option instanceof QuestionOptionInterface) {
                throw new InvalidArgumentException();
            }
            $this->options[$option->getName()] = $option;
        }
    }

    public function askQuestion(ConsoleStyle $io): void
    {
        if (0 === count($this->getAvailableOptions())) {
            return;
        }

        $selectedValue = null;
        while (true) {
            if (0 === count($this->selectedOptions)) {
                $question = new Question($this->getQuestion().' (enter <comment>?</comment> to see all types, leave it <comment>blank</comment> to exit)', $this->getDefaultOptionsName());
            } else {
                $question = new Question($this->getQuestion().' (enter <comment>?</comment> to see all types, leave it <comment>blank</comment> to exit)');
            }
            $question->setAutocompleterValues($this->getAvailableOptions());
            $selectedValue = $io->askQuestion($question);

            if ($this->getDefaultOptionsName() === $selectedValue) {
                $this->selectedOptions = $this->getDefaultOptions();

                break;
            } elseif ('' === trim((string) $selectedValue)) {
                break;
            } elseif ('?' === trim((string) $selectedValue)) {
                $this->printAvailableTypes($io, $this->getAvailableOptions());
                $io->writeln('');

                $selectedValue = null;
            } elseif (!in_array($selectedValue, $this->getAvailableOptions(), true)) {
                $this->printAvailableTypes($io, $this->getAvailableOptions());
                $io->error(sprintf('Invalid type "%s".', $selectedValue));
                $io->writeln('');

                $selectedValue = null;
            } else {
                $this->selectedOptions[] = $this->getConfig((string) $selectedValue);
                $selectedValue = null;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAnswer(): array
    {
        $configs = [];
        foreach ($this->selectedOptions as $option) {
            $selectedConfig = $option->getConfig();
            if (null !== $selectedConfig) {
                $configs[] = $selectedConfig;
            }
        }

        return $configs;
    }

    public function getAdditionalQuestions(): array
    {
        $additionalQuestions = [];
        foreach ($this->selectedOptions as $selectedOption) {
            if ($selectedOption instanceof AdditionalQuestionProviderInterface) {
                $additionalQuestions = array_merge($additionalQuestions, $selectedOption->getAdditionalQuestions());
            }
        }

        return $additionalQuestions;
    }

    abstract protected function getQuestion(): string;

    private function getConfig(string $name): QuestionOptionInterface
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }

        throw new InvalidArgumentException();
    }

    /**
     * @return string[]
     */
    private function getAvailableOptions(): array
    {
        $options = [];
        foreach ($this->options as $configurator) {
            $options[] = $configurator->getName();
        }

        return $options;
    }

    private function getDefaultOptionsName(): string
    {
        $names = [];
        foreach ($this->getDefaultOptions() as $option) {
            $names[] = $option->getName();
        }

        return join(', ', $names);
    }

    /**
     * @param ConsoleStyle $io
     * @param string[]     $getAvailableOptions
     */
    private function printAvailableTypes(ConsoleStyle $io, array $getAvailableOptions): void
    {
        foreach ($getAvailableOptions as $type) {
            $io->writeln(sprintf('  * <comment>%s</comment>', $type));
        }
    }

    /**
     * @return QuestionOptionInterface[]
     */
    private function getDefaultOptions(): array
    {
        $defaultOptions = [];
        foreach ($this->options as $option) {
            if ($option->isDefault()) {
                $defaultOptions[] = $option;
            }
        }

        return $defaultOptions;
    }
}
