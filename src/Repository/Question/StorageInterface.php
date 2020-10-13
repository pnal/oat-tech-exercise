<?php
namespace App\Repository\Question;

interface StorageInterface
{
    /**
     * @return string|null
     */
    public function get(): ?string;

    /**
     * @param string $data
     */
    public function save(string $data): void;

    /**
     * @param string $filenameWithExtension
     */
    public function setFilename(string $filenameWithExtension): void;
}