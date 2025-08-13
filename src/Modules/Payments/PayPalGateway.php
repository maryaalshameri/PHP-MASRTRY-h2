<?php
declare(strict_types=1);

namespace MiniStore\Modules\Payments;

use MiniStore\Modules\Core\Contracts\PaymentGateway;
use MiniStore\Modules\Core\Traits\LogTrait;

final class PayPalGateway implements PaymentGateway
{
    use LogTrait;

    public function __construct(private string $merchantEmail) {}

    public function getName(): string
    {
        return 'PayPal';
    }

    public function charge(float $amount, array $meta = []): bool
    {
        // محاكاة دفع ناجح
        $this->log("Charging {$amount} via PayPal to {$this->merchantEmail} | meta=" . json_encode($meta));
        return true;
    }
}
