<?php

declare(strict_types=1);

namespace App\Question\QuestionHelper\OneFromMany;

use App\Console\ConsoleStyle;
use App\Question\AdditionalQuestionProviderInterface;
use App\Question\QuestionInterface;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\Console\Question\Question;

abstract class QuestionSelectOneFromMany implements QuestionInterface, AdditionalQuestionProviderInterface
{
    protected QuestionOptionInterface $selectedOption;

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
        while (null === $selectedValue) {
            $question = new Question($this->getQuestion().' (enter <comment>?</comment> to see all types)', $this->getDefaultOptionName());
            $question->setAutocompleterValues($this->getAvailableOptions());
            $selectedValue = $io->askQuestion($question);

            if ('?' === $selectedValue) {
                $this->printAvailableTypes($io, $this->getAvailableOptions());
                $io->writeln('');

                $selectedValue = null;
            } elseif (!in_array($selectedValue, $this->getAvailableOptions(), true)) {
                $this->printAvailableTypes($io, $this->getAvailableOptions());
                $io->error(sprintf('Invalid type "%s".', $selectedValue));
                $io->writeln('');

                $selectedValue = null;
            }
        }
        $this->selectedOption = $this->getConfig((string) $selectedValue);
    }

    /**
     * {@inheritdoc}
     */
    public function getAnswer()
    {
        return $this->selectedOption->getConfig();
    }

    public function getAdditionalQuestions(): array
    {
        if ($this->selectedOption instanceof AdditionalQuestionProviderInterface) {
            return $this->selectedOption->getAdditionalQuestions();
        }

        return [];
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

    private function getDefaultOptionName(): ?string
    {
        foreach ($this->options as $configurator) {
            if ($configurator->isDefault()) {
                return $configurator->getName();
            }
        }

        return null;
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
}
