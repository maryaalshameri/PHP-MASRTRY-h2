<?php
declare(strict_types=1);

namespace MiniStore\Modules\Core\Contracts;

interface PaymentGateway
{
    public function charge(float $amount, array $meta = []): bool;
    public function getName(): string;
}
