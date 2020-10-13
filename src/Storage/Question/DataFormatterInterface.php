<?php


namespace App\Storage\Question;

use App\Domain\Collection\QuestionCollection;

interface DataFormatterInterface
{
    public function formatForStorage(QuestionCollection $question): string;

    public function getCollectionFromStorageFormat(string $data): ?QuestionCollection;

    public function getFormatFileExtension(): string;
}