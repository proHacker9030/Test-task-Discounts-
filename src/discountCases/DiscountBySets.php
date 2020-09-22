<?php

namespace app\discountCases;

use app\Product;

/**
 * 1. Если одновременно выбраны А и B, то их суммарная стоимость уменьшается на 10% (для каждой пары А и B)
 * 2. Если одновременно выбраны D и E, то их суммарная стоимость уменьшается на 6% (для каждой пары D и E)
 * 3. Если одновременно выбраны E, F, G, то их суммарная стоимость уменьшается на 3% (для каждой тройки E, F, G)
 */
class DiscountBySets extends Discount
{
    /** @var Product */
    private $product1;
    /** @var Product */
    private $product2;
    /** @var Product */
    private $product3;

    /**
     * DiscountBySets constructor.
     * @param $product1
     * @param $product2
     * @param $product3
     */
    public function __construct(Product $product1, Product $product2, Product $product3 = null)
    {
        $this->product1 = $product1;
        $this->product2 = $product2;
        $this->product3 = $product3;
    }

    public function calculate()
    {
        $allProducts = $this->order->getProducts();
        $needProductSet = [$this->product1, $this->product2];
        if (isset($this->product3)) {
            $needProductSet[] = $this->product3;
        }
        $productsSets = $this->findSets($needProductSet, $allProducts);

        $discountedSum = 0;
        foreach ($productsSets as $productsSet) {
            $sum = 0;
            /** @var Product $product */
            foreach ($productsSet as &$product) {
                $sum += $product->getPrice();
            }
            $discountedSum += $this->substractPercent($sum, $this->discountPercent);
        }

        return $discountedSum;
    }

    private function findSets($needProductSet, $allProducts, &$resultSets = [])
    {
        $set = [];
        foreach ($needProductSet as $needProduct) {
            foreach ($allProducts as $product) {
                if (!$product->isDiscounted() && $product->getName() == $needProduct->getName()) {
                    $set[] = $product;
                    break;
                }
            }
        }

        if (count($set) != count($needProductSet)) {
            return [];
        } else {
            foreach ($set as $product) {
                $product->setDiscounted();
            }
            $resultSets[] = $set;
        }

        $this->findSets($needProductSet, $allProducts, $resultSets);

        return $resultSets;
    }

    /**
     * @param mixed $discountPercent
     */
    public function setDiscountPercent($discountPercent)
    {
        $this->discountPercent = $discountPercent;
    }
}
