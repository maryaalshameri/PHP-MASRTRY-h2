<?php
declare(strict_types=1);

namespace MiniStore\Modules\Orders;

use MiniStore\Modules\Products\Product;

class OrderItem
{
    public function __construct(
        private Product $product,
        private int $quantity
    ) {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be positive.');
        }
    }

    public function getProduct(): Product { return $this->product; }
    public function getQuantity(): int { return $this->quantity; }
    public function getSubtotal(): float
    {
        return $this->product->getPrice() * $this->quantity;
    }
}
