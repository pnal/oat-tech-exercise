<?php


namespace App\Domain\Entity;
use JsonSerializable;

class MultipleChoiceQuestion extends AbstractQuestion implements JsonSerializable
{
    /** @var Choice[] */
    private $choices = [];

    /** @var string[]  */
    protected static $propertiesCanBeTranslated = ['text', 'choices'];

    /**
     * @param string|null $text
     * @param string|null $createdAt
     * @param Choice ...$choices
     */
    public function __construct(string $text = null, string $createdAt = null, Choice ...$choices)
    {
        parent::__construct($text, $createdAt);

        if(count($choices) > 0) {
            foreach ($choices as $choice) {
                $this->addChoice($choice);
            }
        }
    }

    /**
     * @return Choice[]
     */
    public function getChoices(): array
    {
        return $this->choices;
    }

    /**
     * @param Choice $choice
     */
    public function addChoice(Choice $choice): void
    {
        $this->choices[] = $choice;
    }

    public function getAllAsArray(): array {
        return [
            'text' => $this->getText(),
            'createdAt' => $this->getCreatedAt(),
            'choices' => $this->getChoices(),
        ];
    }
}