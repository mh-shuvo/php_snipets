<?php

interface FilterAble
{
    public function filter(array $items): array;
}

readonly class FilterSex implements FilterAble
{
    public function __construct(private readonly string $sex)
    {
    }

    #[\Override]
    public function filter(array $items): array
    {
        return array_filter($items, fn($i) => $i['sex'] === $this->sex);
    }
}

readonly class FilterAdult implements FilterAble
{
    public function __construct(private readonly int $age)
    {
    }

    #[\Override]
    public function filter(array $items): array
    {
        return array_filter($items, fn($i) => $i['age'] >= $this->age);
    }
}

class Filter
{
    /**
     * @var FilterAble[] $filters
     */
    private array $filters = [];


    public function add(FilterAble $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function perform(array $items): array
    {
        $return = $items;
        foreach ($this->filters as $filter) {
            $return = $filter->filter($return);
        }
        return $return;
    }
}

$filter = new Filter();
$filter
    ->add(new FilterAdult(21))
    ->add(new FilterSex('woman'));

$items = [
    ['age' => 32, 'sex' => 'woman'],
    ['age' => 25, 'sex' => 'man']
];
$result = $filter->perform($items);
print_r($result); //['age'=>32,'sex'=>'woman']
