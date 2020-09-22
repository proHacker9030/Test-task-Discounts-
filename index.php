<?php

spl_autoload_register(function ($class) {
    $class = str_replace('app\\', '', $class);
    $class = str_replace('\\', '/', $class);
    include __DIR__ . '/src/' . $class . '.php';
});

use app\discountCases\DiscountBySets;
use app\discountCases\DiscountByDependedProducts;
use app\discountCases\DiscountByCount;
use app\discountCases\DiscountService;
use app\Order;
use app\Product;

$products = [];
$letters = [];
foreach (range('A', 'M') as $letter) {
    $products[$letter] = new Product($letter, 500);
    $letters[] = $letter;
}


$order = new Order();
$i = 8;
while ($i > 0) {
    $randomLetter = array_rand($letters, 1);
    $order->addProduct(clone $products[$letters[$randomLetter]]);
    $i--;
}

//$order->addProduct(clone $products['E']);
//$order->addProduct(clone $products['D']);
//$order->addProduct(clone $products['A']);
//$order->addProduct(clone $products['B']);
//$order->addProduct(clone $products['A']);
//$order->addProduct(clone $products['K']);
//$order->addProduct(clone $products['I']);
//$order->addProduct(clone $products['J']);
//$order->addProduct(clone $products['J']);
//$order->addProduct(clone $products['J']);
//$order->addProduct(clone $products['E']);
//$order->addProduct(clone $products['F']);
//$order->addProduct(clone $products['G']);

echo 'Products in order: ';
foreach ($order->getProducts() as $product) {
    echo 'product' . $product->getName() . ', ';
}
echo PHP_EOL;

echo 'Order amount before discount = ' . $order->getAmount() . PHP_EOL;

$discountSets1 = new DiscountBySets($products['A'], $products['B']);
$discountSets1->setOrder($order);
$discountSets1->setDiscountPercent(10);

$discountSets2 = new DiscountBySets($products['D'], $products['E']);
$discountSets2->setOrder($order);
$discountSets2->setDiscountPercent(6);

$discountSets3 = new DiscountBySets($products['E'], $products['F'], $products['G']);
$discountSets3->setOrder($order);
$discountSets3->setDiscountPercent(3);

$discountDependent = new DiscountByDependedProducts($products['A']);
$discountDependent->setOrder($order);
$discountDependent->setDiscountPercent(5);
$discountDependent->addDependedProduct($products['K']);
$discountDependent->addDependedProduct($products['L']);
$discountDependent->addDependedProduct($products['M']);

$discountByCount = new DiscountByCount();
$discountByCount->setOrder($order);
$discountByCount->setDiscountPercent(5, 3);
$discountByCount->setDiscountPercent(10, 4);
$discountByCount->setDiscountPercent(20, 5);
$discountByCount->addExceptedProduct($products['A']);
$discountByCount->addExceptedProduct($products['C']);

$discountService = new DiscountService();
$discountService->addDiscountCase($discountSets1);
$discountService->addDiscountCase($discountSets2);
$discountService->addDiscountCase($discountSets3);
$discountService->addDiscountCase($discountDependent);
$discountService->addDiscountCase($discountByCount);

$totalDiscountedAmount = $discountService->calculateDiscountedAmount($order);
echo 'Discounted amount = ' . $totalDiscountedAmount . PHP_EOL;
echo 'Order amount after discount = '.$order->getAmount(). PHP_EOL;
