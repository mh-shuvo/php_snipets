<?php

class ParallelArrayIterator implements Iterator
{
    private array $arrays = [];

    private int $index = 0;

    private int $maxIndex;


    public function add(array $array): self
    {
        $this->arrays[] = $array;
        return $this;
    }

    public function current(): array
    {
        $return = [];
        foreach ($this->arrays as $array) {
            $return[] = $array[$this->index] ?? null;
        }
        return $return;
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return $this->index <= $this->maxIndex;
    }

    public function rewind(): void
    {
        $this->index = 0;
        $counts = array_map('\count', $this->arrays);
        $this->maxIndex = max($counts) - 1;
    }
}

$parallelArrayIterator = new ParallelArrayIterator();

$parallelArrayIterator
    ->add([1, 2, 3])
    ->add(['a', 'b', 'c','d','e'])
    ->add(['istanbul', 'london', 'paris','roma']);


foreach ($parallelArrayIterator as [$number, $letter, $city]) {
    echo $number;
    echo $letter;
    echo $city;
}
