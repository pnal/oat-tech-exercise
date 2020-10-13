<?php


namespace App\Storage\Question;

use App\Domain\Collection\QuestionCollection;

interface DataFormatterInterface
{
    /**
     * Serialize Question Collection to string in proper format
     *
     * @param QuestionCollection $question
     * @return string
     */
    public function formatForStorage(QuestionCollection $question): string;

    /**
     * Deserialize string data from proper format to Question Collection
     *
     * @param string $data
     * @return QuestionCollection
     */
    public function getCollectionFromStorageFormat(string $data): QuestionCollection;

    /**
     * Returns file extension according to format
     *
     * @return string
     */
    public function getFormatFileExtension(): string;
}