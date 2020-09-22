<?php

namespace app;

class Product
{
    private $name;
    private $price;
    private $isDiscounted = false;

    /**
     * Product constructor.
     * @param $name
     * @param $price
     */
    public function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function isDiscounted()
    {
        return $this->isDiscounted;
    }

    public function setDiscounted()
    {
        $this->isDiscounted = true;
    }
}
