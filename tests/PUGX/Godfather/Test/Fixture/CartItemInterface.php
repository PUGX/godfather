<?php

namespace PUGX\Godfather\Test\Fixture;

use PUGX\Godfather\Test\Fixture\Entity\Product;

Interface CartItemInterface
{
    public function addToCart(Product $product, $string);
}
