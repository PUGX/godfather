<?php

namespace PUGX\Godfather\Test\Fixture;

use PUGX\Godfather\Test\Fixture\Entity\Product;

Class StandardCartItem implements CartItemInterface
{
    public function addToCart(Product $product, $string)
    {
        return 'standard.' . $string;
    }
}