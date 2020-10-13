<?php


namespace App\Domain\Entity;


abstract class AbstractQuestion extends AbstractEntity
{
    /** @var string[] */
    protected static $propertiesCanBeTranslated = ['text'];
    /** @var string */
    private $text;
    /** @var string */
    private $createdAt;

    /**
     * @param string|null $text
     * @param string|null $createdAt
     */
    public function __construct(string $text = null, string $createdAt = null)
    {
        if (null !== $text) {
            $this->setText($text);
        }

        if (null !== $createdAt) {
            $this->setCreatedAt($createdAt);
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

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

}