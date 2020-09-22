<?php

namespace app\discountCases;

use app\Order;

class DiscountService
{
    private $discountCases;

    public function addDiscountCase(Discount $discountCase)
    {
        $this->discountCases[] = $discountCase;
    }

    public function calculateDiscountedAmount(Order $order)
    {
        $discountedAmount = 0;
        foreach ($this->discountCases as $discountCase) {
            $discountedSum = $discountCase->calculate();
            $order->setAmount($order->getAmount() - $discountedSum);
            $discountedAmount += $discountedSum;
        }

        return $discountedAmount;
    }
}
