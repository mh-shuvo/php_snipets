<?php
/**
	Have you ever tried using multiple closures while doing array filtering with PHP? If not, here's an example for you.
**/
 
class FilterClosure
{
    /**
     * @var array<Closure(mixed $value):bool> $filters
     */
    private array $filters = [];
 
    /**
     * @param Closure(mixed $value):bool $closure
     * @return FilterClosure
     */
    public function add(Closure $closure): self
    {
        $this->filters[] = $closure;
        return $this;
    }
 
    public function apply(array $array): array
    {
        return array_filter(
            $array,
            fn($item) => array_reduce(
            $this->filters,
            static fn($boolean, $next) => $boolean && $next($item),
            true
        ));
    }
}
 
$filterClosure = new FilterClosure();
 
$filterClosure
    ->add(fn($value) => $value % 2 === 0)
    ->add(fn($value) => $value < 15)
    ->add(fn($value) => $value > 8);
 
 
$result=$filterClosure->apply(range(1, 20));
 
print_r($result);