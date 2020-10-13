<?php


namespace App\Storage\Question;


use App\Domain\Collection\QuestionCollection;
use App\Domain\Entity\Choice;
use App\Domain\Entity\MultipleChoiceQuestion;

class CsvDataFormatter implements DataFormatterInterface
{
    protected const CSV_HEADER_QUESTION_TEXT = 'Question text';
    protected const CSV_HEADER_CREATED_AT = 'Created At';
    protected const CSV_HEADER_CHOICE_TEXT = 'Choice';
    protected const CSV_DELIMITER = ';';

    protected const FORMAT_FILE_EXTENSION = '.csv';

    public function formatForStorage(QuestionCollection $questions): string
    {
        $maxChoicesCount = 0;

        $dataArray = [];
        foreach ($questions->toArray() as $item) {
            $choices = $item->getChoices();
            $maxChoicesCount = count($choices) > $maxChoicesCount ? count($choices) : $maxChoicesCount;
            $dataArray[] = $this->prepareDataRow(
                $item->getText(),
                $item->getCreatedAt(),
                ... array_map(function ($choice): string {
                    return $choice->getText();
                }, $choices)
            );
        }

        $headerRow = $this->prepareHeaderRow($maxChoicesCount);

        return $this->getCsvString($headerRow, ...$dataArray);
    }

    public function getCollectionFromStorageFormat(string $data): ?QuestionCollection
    {
        $questions = new QuestionCollection();
        foreach (explode(PHP_EOL, $data) as $i => $row) {
            $dataCols = str_getcsv($row, self::CSV_DELIMITER);
            if ($i === 0 || count($dataCols) < 2) {
                /* We don't need a header or empty line */
                continue;
            }
            $dataCols = str_getcsv($row, self::CSV_DELIMITER);
            $question = new MultipleChoiceQuestion($dataCols[0], $dataCols[1]);
            /* getting choices skip columns 0,1*/
            for ($j = 2; $j < count($dataCols); $j++) {
                $choiceText = $dataCols[$j];
                if (strlen($choiceText) > 0) {
                    $question->addChoice(new Choice($choiceText));
                }
            }
            $questions->add($question);
        }

        return $questions;
    }

    public function getFormatFileExtension(): string
    {
        return static::FORMAT_FILE_EXTENSION;
    }

    /**
     * Creates CSV file contents as string (used temporary file approach to skip work with character escaping)
     *
     * @param array ...$rows
     * @return string
     */
    private function getCsvString(array ...$rows): string
    {
        $fileHandle = fopen('php://temp', 'r+b');
        foreach ($rows as $row) {
            fputcsv($fileHandle, $row, self::CSV_DELIMITER);
        }
        rewind($fileHandle);

        $resultCsvString = stream_get_contents($fileHandle);
        fclose($fileHandle);

        return $resultCsvString;
    }

    private function prepareDataRow(string ...$props): array
    {
        return $props;
    }

    private function prepareHeaderRow($maxChoicesCount): array
    {
        $headerArray = [self::CSV_HEADER_QUESTION_TEXT, self::CSV_HEADER_CREATED_AT];
        for ($i = 1; $i <= $maxChoicesCount; $i++) {
            array_push($headerArray, sprintf('%s %d', self::CSV_HEADER_CHOICE_TEXT, $i));
        }
        return $headerArray;
    }


}