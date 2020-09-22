<?php


namespace app\discountCases;

use app\Product;

/**
 * 4. Если одновременно выбраны А и один из [K, L, M], то стоимость выбранного продукта уменьшается на 5%
 */
class DiscountByDependedProducts extends Discount
{
    private $mainProduct;
    private $dependedProducts;

    public function __construct(Product $mainProduct)
    {
        $this->mainProduct = $mainProduct;
    }

    public function addDependedProduct(Product $product)
    {
        $this->dependedProducts[$product->getName()] = $product;
    }

    public function calculate()
    {
        $discountedSum = 0;
        $isInOrderDependedProduct = false;
        $orderProducts = $this->order->getProducts();
        foreach ($this->dependedProducts as $dependedProduct) {
            foreach ($orderProducts as $orderProduct) {
                if (!$orderProduct->isDiscounted() && $dependedProduct->getName() == $orderProduct->getName()) {
                    $isInOrderDependedProduct = true;
                    break;
                }
            }
            if ($isInOrderDependedProduct) {
                break;
            }
        }
        $mainProductKey = array_search($this->mainProduct, $orderProducts);
        if ($mainProductKey && !$orderProducts[$mainProductKey]->isDiscounted() && $isInOrderDependedProduct) {
            $discountedSum = $this->substractPercent($this->mainProduct->getPrice(), $this->discountPercent);
            $orderProducts[$mainProductKey]->setDiscounted();
        }

        return $discountedSum;
    }

    /**
     * @param mixed $discountPercent
     */
    public function setDiscountPercent($discountPercent)
    {
        $this->discountPercent = $discountPercent;
    }
}
