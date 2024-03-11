<?php

//dilo surucu

class ReverseChainClosures
{
    private Closure $closure;

    public function __construct()
    {
        $this->closure = static fn($data) => $data;
    }

    public function add(Closure $closure): void
    {
        $oldClosure = $this->closure;
        $this->closure = static function ($value) use ($oldClosure, $closure) {
           return $closure($value, $oldClosure);
        };
    }

    public function execute(mixed $value): mixed
    {
        return call_user_func($this->closure, $value);
    }
}


$reverseChainClosures = new ReverseChainClosures();

$reverseChainClosures->add(
    fn(int $value) => print $value
);

$reverseChainClosures->add(
    fn(mixed $value, Closure $back) => $back($value * 4)
);

$reverseChainClosures->execute(5); //20
