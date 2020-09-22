<?php

namespace app\discountCases;

use app\Order;

abstract class Discount
{
    /** @var Order */
    protected $order;
    /**
     * @var mixed
     */
    protected $discountPercent;

    /**
     * @param Order $order
     */
    public function setOrder(Order &$order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    abstract public function calculate();

    /**
     * @param $price
     * @param $percent
     * @return float|int
     */
    protected function substractPercent($price, $percent)
    {
        return ($price * $percent) / 100;
    }

    /**
     * @return mixed
     */
    public function getDiscountPercent()
    {
        return $this->discountPercent;
    }
}
