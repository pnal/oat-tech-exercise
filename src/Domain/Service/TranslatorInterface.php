<?php


namespace App\Domain\Service;


use App\Domain\Entity\TranslatableInterface;

interface TranslatorInterface
{
    const DEFAULT_LANG = 'en';

    /**
     * Translates the text $text into $targetLanguage
     * @param TranslatableInterface $object
     * @param string $toLanguage
     * @param string $fromLanguage
     * @return array
     */
    public function translateEntity(
        TranslatableInterface $object,
        string $toLanguage,
        string $fromLanguage = self::DEFAULT_LANG
    ): array;
}