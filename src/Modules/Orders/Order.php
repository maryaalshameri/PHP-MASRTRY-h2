<?php
declare(strict_types=1);

namespace MiniStore\Modules\Orders;

use MiniStore\Modules\Core\Traits\DiscountTrait;
use MiniStore\Modules\Core\Traits\LogTrait;
use MiniStore\Modules\Core\Traits\TaxTrait;
use MiniStore\Modules\Core\Contracts\PaymentGateway;
use MiniStore\Modules\Products\Product;
use MiniStore\Modules\Users\Customer;

class Order
{
    use LogTrait, DiscountTrait, TaxTrait;

    private static int $counter = 1000; // static مثال
    private int $id;
    private Customer $customer;

    /** @var OrderItem[] */
    private array $items = [];

    private string $status = 'PENDING';

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
        $this->id = self::$counter++;
        $this->log("Order #{$this->id} created for {$customer->getEmail()}");
    }

    public function getId(): int { return $this->id; }
    public function getStatus(): string { return $this->status; }
    public function getItems(): array { return $this->items; }

    public function addItem(Product $product, int $qty): void
    {
        // حجز من المخزون (encapsulation داخل Product)
        $product->reserve($qty);
        $this->items[] = new OrderItem($product, $qty);
        $this->log("Order #{$this->id}: added {$qty} x {$product->getName()}");
    }

    public function getSubtotal(): float
    {
        return array_sum(array_map(fn(OrderItem $i) => $i->getSubtotal(), $this->items));
    }

    public function getTotalAfterDiscountBeforeTax(): float
    {
        return $this->applyDiscount($this->getSubtotal());
    }

    public function getTaxAmount(): float
    {
        return $this->calcTaxOnly($this->getTotalAfterDiscountBeforeTax());
    }

    public function getGrandTotal(): float
    {
        return $this->applyTax($this->getTotalAfterDiscountBeforeTax());
    }

    public function processPayment(PaymentGateway $gateway): bool
    {
        $amount = $this->getGrandTotal();
        $ok     = $gateway->charge($amount, [
            'order_id' => $this->id,
            'customer' => $this->customer->getEmail()
        ]);

        if ($ok) {
            $this->status = 'PAID';
            $this->log("Order #{$this->id} paid via {$gateway->getName()} amount {$amount}");
        } else {
            $this->status = 'FAILED';
            $this->log("Order #{$this->id} payment failed via {$gateway->getName()}");
        }
        return $ok;
    }
}
