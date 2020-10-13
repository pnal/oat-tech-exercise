<?php


namespace App\Domain\Entity;


abstract class AbstractEntity Implements TranslatableInterface
{
    protected static $propertiesCanBeTranslated = [];

    public static function getTranslatablePropertyNames():array {
        return static::$propertiesCanBeTranslated;
    }

    public function jsonSerialize():array
    {
        return $this->getAllAsArray();
    }
}