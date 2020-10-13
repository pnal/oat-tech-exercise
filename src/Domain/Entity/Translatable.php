<?php


namespace App\Domain\Entity;


interface Translatable
{
    public static function getTranslatablePropertyNames(): array;

    public function getAllAsArray(): array;
}