<?php


namespace App\Domain\Entity;


interface TranslatableInterface
{
    public static function getTranslatablePropertyNames(): array;

    public function getAllAsArray(): array;
}