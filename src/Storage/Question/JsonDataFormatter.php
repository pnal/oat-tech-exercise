<?php


namespace App\Storage\Question;

use App\Domain\Collection\QuestionCollection;
use App\Domain\Entity\Choice;
use App\Domain\Entity\MultipleChoiceQuestion;

class JsonDataFormatter implements DataFormatterInterface
{
    protected const FORMAT_FILE_EXTENSION = '.json';

    public function formatForStorage(QuestionCollection $questions): string
    {
        return $this->encodeData($questions->toArray());
    }

    private function encodeData(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public function getCollectionFromStorageFormat(string $data): ?QuestionCollection
    {
        $collection = new QuestionCollection();
        foreach ($this->decodeData($data) as $questionData) {
            $question = new MultipleChoiceQuestion($questionData->text, $questionData->createdAt);
            foreach ($questionData->choices as $choice) {
                $question->addChoice(new Choice($choice->text));
            }
            $collection->add($question);
        }
        return $collection;
    }

    private function decodeData(string $data): array
    {
        return json_decode($data) ? (array)json_decode($data) : [];
    }

    public function getFormatFileExtension(): string
    {
        return static::FORMAT_FILE_EXTENSION;
    }


}