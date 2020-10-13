<?php

namespace App\Domain\Repository;


use App\Domain\Collection\QuestionCollection;
use App\Domain\Entity\AbstractQuestion;

interface QuestionRepositoryInterface
{
    public function storeOne(AbstractQuestion $question): AbstractQuestion;

    public function getAll(): QuestionCollection;
}