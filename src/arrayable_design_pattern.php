<?php
//dilo surucu
 
/**
 *
 */
interface ArrayAbleInterface
{
    /**
     * @param BuilderArrayInterface $builder
     * @return void
     */
    public function add(BuilderArrayInterface $builder): void;
}
 
 
/**
 *
 */
interface BuilderArrayInterface
{
    /**
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function add(mixed $key, mixed $value): void;
}
 
/**
 *
 */
class City implements ArrayAbleInterface
{
 
    /**
     * @param BuilderArrayInterface $builder
     * @return void
     */
    #[\Override]
    public function add(BuilderArrayInterface $builder): void
    {
        $builder->add('city', 'new york');
    }
}
 
/**
 *
 */
class Name implements ArrayAbleInterface
{
 
    /**
     * @param BuilderArrayInterface $builder
     * @return void
     */
    #[\Override]
    public function add(BuilderArrayInterface $builder): void
    {
        $builder->add('name', 'dilo surucu');
    }
}
 
 
/**
 *
 */
class Info implements ArrayAbleInterface
{
 
    /**
     * @param BuilderArrayInterface $builder
     * @return void
     */
    #[\Override]
    public function add(BuilderArrayInterface $builder): void
    {
        $builder->add('info', ['foo' => 'bar']);
    }
}
 
/**
 *
 */
class Country implements ArrayAbleInterface
{
 
    /**
     * @param BuilderArrayInterface $builder
     * @return void
     */
    #[\Override]
    public function add(BuilderArrayInterface $builder): void
    {
        $builder->add('country', 'Turkey');
    }
}
 
 
/**
 *
 */
class BuilderArray implements BuilderArrayInterface
{
    /**
     * @var array
     */
    private array $array = [];
 
    /**
     * @param mixed $key
     * @param mixed $value
     */
 
    #[\Override]
    public function add(mixed $key, mixed $value): void
    {
        $this->array = array_merge($this->array, [$key => $value]);
    }
 
    /**
     * @param ArrayAbleInterface $arrayAble
     * @return $this
     */
    public function build(ArrayAbleInterface $arrayAble): self
    {
        $arrayAble->add($this);
        return $this;
    }
 
    /**
     * @return array
     */
    public function get(): array
    {
        return $this->array;
    }
}
 
 
$builderArray = new BuilderArray();
$builderArray
    ->build(new City())
    ->build(new Name())
    ->build(new Country())
    ->build(new Info());
 
 
print_r($builderArray->get());
Laravel.io Logo
New 
 Fork Raw
Please note that all pasted data is publicly available.

Twitter
GitHub
Use setting
