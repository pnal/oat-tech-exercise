<?php


namespace App\Service\Translator;


use App\Domain\Entity\TranslatableInterface;
use App\Domain\Service\TranslatorInterface;
use Matriphe\ISO639\ISO639;

class TranslatorService implements TranslatorInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * TranslatorService constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritDoc
     * @throws TranslationException
     */
    public function translateEntity(
        TranslatableInterface $object,
        string $toLanguage,
        string $fromLanguage = TranslatorInterface::DEFAULT_LANG
    ): array {
        if (!$this->langCodeExists($toLanguage)) {
            throw new TranslationException("Language code is not compliant with ISO-639-1", 400);
        }

        $translated = [];
        foreach ($object::getTranslatablePropertyNames() as $translatablePropertyName) {
            $getter = 'get' . ucfirst($translatablePropertyName);
            if (method_exists($object, $getter)) {
                $propertyValue = $object->{$getter}();
                if (is_string($propertyValue)) {
                    $translated[$translatablePropertyName] = $this->adapter->translate(
                        $propertyValue,
                        $toLanguage,
                        $fromLanguage
                    );
                    continue;
                } elseif (is_array($propertyValue)) {
                    $translated[$translatablePropertyName] = self::iterateHelper(
                        $propertyValue,
                        $toLanguage,
                        $fromLanguage
                    );
                    continue;
                } elseif ($propertyValue instanceof TranslatableInterface) {
                    $translated[$translatablePropertyName] = self::translateEntity(
                        $propertyValue,
                        $toLanguage,
                        $fromLanguage
                    );
                    continue;
                }
            }
            throw new TranslationException(
                "TranslatableInterface property not found or property type is not string/TranslatableInterface[]"
            );
        }

        return array_merge($object->getAllAsArray(), $translated);
    }

    /**
     * @param string $langCode
     * @return bool
     */
    private function langCodeExists(string $langCode): bool
    {
        $iso = new ISO639();
        return $iso->languageByCode1($langCode) !== '';
    }

    /**
     * @param string $toLanguage
     * @param string $fromLanguage
     * @param TranslatableInterface[] $array
     * @return array
     * @throws TranslationException
     */
    private function iterateHelper(
        array $array,
        string $toLanguage,
        string $fromLanguage = TranslatorInterface::DEFAULT_LANG
    ): array {
        $result = [];
        foreach ($array as $item) {
            $result[] = $this->translateEntity($item, $toLanguage, $fromLanguage);
        }

        return $result;
    }
}