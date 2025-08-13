<?php
declare(strict_types=1);

namespace MiniStore\Modules\Payments;

use MiniStore\Modules\Core\Contracts\PaymentGateway;
use MiniStore\Modules\Core\Traits\LogTrait;

final class StripeGateway implements PaymentGateway
{
    use LogTrait;

    public function __construct(private string $secretKey) {}

    public function getName(): string
    {
        return 'Stripe';
    }

    public function charge(float $amount, array $meta = []): bool
    {
        // محاكاة دفع (نعتبره ناجح دائماً هنا)
        $this->log("Charging {$amount} via Stripe using key ****" . substr($this->secretKey, -4) . " | meta=" . json_encode($meta));
        return true;
    }
}
