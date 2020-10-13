<?php


namespace App\Service\Translator;


interface AdapterInterface
{
    /**
     * @param string $text
     * @param string $toLanguage
     * @param string $fromLanguage
     * @return string
     * @throws TranslationException
     */
    public function translate (string $text, string $toLanguage, string $fromLanguage = 'en'): string;
}