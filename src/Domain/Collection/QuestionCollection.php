<?php

namespace App\Domain\Collection;

use App\Domain\Entity\AbstractQuestion;

class QuestionCollection extends AbstractCollection
{
    /**
     * @var AbstractQuestion[] $values
     */
    protected $values;

    public function __construct(array $questions = [])
    {
        $this->values = $questions;
    }

    public function add(AbstractQuestion $question): self
    {
        $this->values[] = $question;
        return $this;
    }
}