<?php


namespace App\Domain\Service;


use App\Domain\Entity\Translatable;

interface TranslatorInterface
{
    const DEFAULT_LANG = 'en';

    /**
     * Translates the text $text into $targetLanguage
     * @param Translatable $object
     * @param string $toLanguage
     * @param string $fromLanguage
     * @return array
     */
    public function translateEntity(Translatable $object, string $toLanguage, string $fromLanguage = self::DEFAULT_LANG): array;
}