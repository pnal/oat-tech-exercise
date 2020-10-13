<?php


namespace App\Repository\Question;


use App\Domain\Collection\QuestionCollection;
use App\Domain\Entity\AbstractQuestion;
use App\Domain\Repository\QuestionRepositoryInterface;
use App\Repository\StorageInterface;
use App\Storage\Question\DataFormatterInterface;

class QuestionRepository implements QuestionRepositoryInterface
{
    private const STORAGE_FILENAME = 'MCQuestions';

    /**
     * @var StorageInterface
     */
    private $storage;
    /**
     * @var DataFormatterInterface
     */
    private $formatter;

    public function __construct(StorageInterface $storage, DataFormatterInterface $formatter)
    {
        $this->storage = $storage;
        $this->formatter = $formatter;
        $this->storage->setFilename(self::STORAGE_FILENAME . $this->formatter->getFormatFileExtension());
    }

    public function getAll(): QuestionCollection
    {
        $questionsData = $this->storage->get();

        return $this->formatter->getCollectionFromStorageFormat($questionsData);
    }

    public function storeOne(AbstractQuestion $question): AbstractQuestion
    {
        $storedData = $this->storage->get();
        $questions = $storedData ? $this->formatter->getCollectionFromStorageFormat($storedData) : new QuestionCollection();

        $questions->add($question);

        $formatted = $this->formatter->formatForStorage($questions);
        $this->storage->save($formatted);
        return $question;
    }
}