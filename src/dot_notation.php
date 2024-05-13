<?php
//dilo surucu
 
 
class DotNotation
{
    private array $array;
 
    public static function fromArray(array $array = []): static
    {
        $static = new static();
        $static->array = $array;
        return $static;
    }
 
    public function get(string $dotNotation, mixed $default = null): mixed
    {
        $keys = explode('.', $dotNotation);
        $return = $this->array;
        foreach ($keys as $key) {
            if (!isset($return[$key])) {
                return $default;
            }
 
            $return = $return[$key];
        }
        return $return;
    }
 
    public function set(string $dotNotation, mixed $value): void
    {
        $keys = explode('.', $dotNotation);
        $lastKey = array_pop($keys);
        $data = &$this->array;
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                $data[$key] = [];
            }
           $data=&$data[$key];
        }
 
        $data[$lastKey] = $value;
    }
 
    public function getArray(): array
    {
        return $this->array;
    }
}
 
 
$array = [
    'user' => [
        'city' => [
            'name' => 'new york'
        ],
        'job' => 'developer'
    ]
];
 
$dotNotation = DotNotation::fromArray($array);
 
echo $dotNotation->get('user.city.name'); //new york
echo $dotNotation->get('user.job'); //developer
 
$dotNotation->set('user.city.country', 'USA');
echo $dotNotation->get('user.city.country');//USA
 
print_r($dotNotation->getArray());
Laravel.io Logo
New 
 Fork Raw
Please note that all pasted data is publicly available.

Twitter
GitHub
Use setting
