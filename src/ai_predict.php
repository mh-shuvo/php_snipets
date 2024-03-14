<?php

//dilo surucu

class Apriori
{
    private array $products = [];

    public function add(array $products):self
    {
        $this->products[] = $products;
        return $this;
    }

    public function predict(string $productName,int $amount=3):array
    {
        $predict = [];
        foreach ($this->products as $products) {
            if (in_array($productName, $products,true)) {
                foreach ($products as $product) {
                    $predict[$product] ??= 0;
                    $predict[$product]++;
                }
            }
        }
        arsort($predict,SORT_NATURAL);
        $predictBasket= array_slice($predict, 0, $amount+1);
        return array_diff_key($predictBasket, [$productName=>null]);
    }
}


$apriori = new Apriori();

$apriori->add(['beer', 'condom', 'cheese', 'napkin']);
$apriori->add(['water', 'meat', 'beer', 'bread']);
$apriori->add(['water', 'bread', 'cheese', 'tea']);
$apriori->add(['beer', 'wine', 'condom', 'cigarette']);
$apriori->add(['beer', 'wine', 'condom', 'water']);


$predict = $apriori->predict('condom');

print_r($predict);//[beer,wine,cheese]
