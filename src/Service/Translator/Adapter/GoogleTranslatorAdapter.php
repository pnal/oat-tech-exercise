<?php


namespace App\Service\Translator\Adapter;


use App\Domain\Service\TranslatorInterface;
use App\Service\Translator\AdapterInterface;
use Psr\Cache\InvalidArgumentException;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class GoogleTranslatorAdapter implements AdapterInterface
{

    /**
     * @param string $text
     * @param string $toLanguage
     * @param string $fromLanguage
     * @return string
     * @throws InvalidArgumentException
     */
    public function translate(string $text, string $toLanguage, string $fromLanguage = TranslatorInterface::DEFAULT_LANG): string
    {
        // file cache so slow but today it is enough
        $cache = new FilesystemAdapter();

        return $cache->get($fromLanguage . $toLanguage . $text, function (ItemInterface $item) use ($text, $toLanguage, $fromLanguage) {
            $item->expiresAfter(3600);
            return GoogleTranslate::trans($text, $toLanguage, $fromLanguage);
        });
    }
}