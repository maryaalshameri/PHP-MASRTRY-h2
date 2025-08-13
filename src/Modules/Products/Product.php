<?php
declare(strict_types=1);

namespace MiniStore\Modules\Products;

use MiniStore\Modules\Core\Traits\LogTrait;

class Product
{
    use LogTrait;

    private string $sku;
    private string $name;
    private float $price;
    private int $stock;

    public function __construct(string $sku, string $name, float $price, int $stock)
    {
        $this->setSku($sku);
        $this->setName($name);
        $this->setPrice($price);
        $this->setStock($stock);
    }

    private function setSku(string $sku): void
    {
        if (!preg_match('/^[A-Z0-9\-]{3,}$/', $sku)) {
            throw new \InvalidArgumentException('Invalid SKU format.');
        }
        $this->sku = $sku;
    }

    private function setName(string $name): void
    {
        $name = trim($name);
        if ($name === '') {
            throw new \InvalidArgumentException('Product name is required.');
        }
        $this->name = $name;
    }

    private function setPrice(float $price): void
    {
        if ($price < 0) {
            throw new \InvalidArgumentException('Price cannot be negative.');
        }
        $this->price = round($price, 2);
    }

    private function setStock(int $stock): void
    {
        if ($stock < 0) {
            throw new \InvalidArgumentException('Stock cannot be negative.');
        }
        $this->stock = $stock;
    }

    // تغليف/Encapsulation للمخزون
    public function reserve(int $qty): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be positive.');
        }
        if ($qty > $this->stock) {
            throw new \RuntimeException('Not enough stock.');
        }
        $this->stock -= $qty;
        $this->log("Reserved {$qty} of {$this->sku}. Remaining: {$this->stock}");
    }

    public function release(int $qty): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be positive.');
        }
        $this->stock += $qty;
        $this->log("Released {$qty} of {$this->sku}. Now: {$this->stock}");
    }

    // Getters
    public function getSku(): string { return $this->sku; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
    public function getStock(): int { return $this->stock; }
}
