<?php


namespace App\Storage\Question;

use App\Domain\Collection\QuestionCollection;

class JsonDataFormatter implements DataFormatterInterface
{
    protected const FORMAT_FILE_EXTENSION = '.json';

    public function formatForStorage(QuestionCollection $questions): string
    {
        return $this->encodeData($questions->toArray());
    }

    public function getCollectionFromStorageFormat(string $data): ?QuestionCollection
    {
        return new QuestionCollection($this->decodeData($data));
    }

    public function getFormatFileExtension(): string
    {
        return static::FORMAT_FILE_EXTENSION;
    }

    private function encodeData(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    private function decodeData(string $data): array
    {
        return json_decode($data) ? (array)json_decode($data) : [];
    }


}