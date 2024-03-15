<?php

//dilo surucu


#[AllowDynamicProperties]
/**
 * @property string $name
 */
class Person
{
    private array $properties = [];

    private array $watches = [];

    /**
     * @param string $propertyName
     * @param Closure(mixed $oldValue,mixed $newValue):void $closure()
     * @return void
     */
    public function watch(string $propertyName,Closure $closure):void
    {
        $this->watches[$propertyName] = $closure;
    }

    public function __set(string $name, $value): void
    {
        if (isset($this->watches[$name])) {
            $oldValue = $this->properties[$name] ?? null;
            $this->properties[$name] = $value;
            $this->watches[$name]($oldValue, $value);
        }
    }

    public function __get(string $name):string
    {
        return $this->properties[$name];
    }
}

$person = new Person();

$person->watch('name', function ($oldValue, $newValue){
    echo "$oldValue,$newValue".PHP_EOL;

});

$person->name = 'dilo';
$person->name = 'alex';
$person->name = 'john';

echo $person->name;
