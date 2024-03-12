<?php
 interface PersonInterface
{
    public function getName(): string;
}

class Human
{
    public function getFullName(): string
    {
        return "MD Mehedi Hasan";
    }
}

readonly class HumanAdaptar implements PersonInterface
{
    private Human $human;

    public function __construct(Human $human)
    {
        $this->human = $human;
    }

    #[\Override]
    public function getName(): string
    {
        return $this->human->getFullName();
    }
}

function get_name(PersonInterface $person): string
{
    return $person->getName();
}

echo get_name(new HumanAdaptar(new Human()));
