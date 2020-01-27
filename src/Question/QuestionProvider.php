<?php

declare(strict_types=1);

namespace App\Question;

use Psr\Log\InvalidArgumentException;

class QuestionProvider
{
    /**
     * @var QuestionInterface[]
     */
    protected array $questions = [];

    /**
     * @param QuestionInterface[] $questions
     */
    public function __construct(iterable $questions)
    {
        foreach ($questions as $question) {
            if (!$question instanceof QuestionInterface) {
                throw new InvalidArgumentException();
            }
            $this->questions[get_class($question)] = $question;
        }
    }

    /**
     * @return QuestionInterface[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }
}
