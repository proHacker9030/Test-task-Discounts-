<?php

namespace app\discountCases;

use app\Product;

/**
 * 5. Если пользователь выбрал одновременно 3 продукта, он получает скидку 5% от суммы заказа
 * 6. Если пользователь выбрал одновременно 4 продукта, он получает скидку 10% от суммы заказа
 * 7. Если пользователь выбрал одновременно 5 продуктов, он получает скидку 20% от суммы заказа
 * 8. Описанные скидки 5,6,7 не суммируются, применяется только одна из них
 * 9. Продукты A и C не участвуют в скидках 5,6,7
 */
class DiscountByCount extends Discount
{
    private $exceptedProducts = [];

    public function addExceptedProduct(Product $product)
    {
        $this->exceptedProducts[] = $product;
    }

    /**
     * @param mixed $discountPercent
     */
    public function setDiscountPercent($discountPercent, $count)
    {
        $this->discountPercent[$count] = $discountPercent;
    }


    public function calculate()
    {
        $needProducts = [];
        /** @var \Product $product */
        foreach ($this->order->getProducts() as $product) {
            if ($product->isDiscounted() || in_array($product, $this->exceptedProducts)) {
                continue;
            }
            $needProducts[] = $product;
        }

        $discountedSum = 0;
        foreach ($this->discountPercent as $count => $percent) {
            if (count($needProducts) >= $count) {
                $discountedSum = $this->substractPercent($this->order->getAmount(), $percent);
            }
        }

        return $discountedSum;
    }
}
