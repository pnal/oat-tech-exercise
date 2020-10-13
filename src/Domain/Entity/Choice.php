<?php declare(strict_types=1);


namespace App\Domain\Entity;
use JsonSerializable;

class Choice extends AbstractEntity implements JsonSerializable
{
    /** @var string */
    private $text;

    protected static $propertiesCanBeTranslated = ['text'];

    /**
     * @param string|null $text
     */
    public function __construct(string $text = null)
    {
        if (null !== $text) {
            $this->setText($text);
        }
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getAllAsArray(): array {
        return [
            'text' => $this->getText()
        ];
    }
}