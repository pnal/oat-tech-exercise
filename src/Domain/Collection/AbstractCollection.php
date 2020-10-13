<?php


namespace App\Domain\Collection;


use ArrayIterator;
use Countable;
use IteratorAggregate;

abstract class AbstractCollection implements IteratorAggregate, Countable
{
    protected $values;

    public function toArray() : array {
        return (array)$this->values;
    }

    public function getIterator() {
        return new ArrayIterator($this->values);
    }

    public function count()
    {
        return count($this->values);
    }
}