<?php
declare(strict_types=1);

namespace MiniStore\Modules\Core\Traits;

use MiniStore\Modules\Core\Config;

trait DiscountTrait
{
    protected float $discountPercent = 0.0; // خاص بالطلب مثلاً

    public function setDiscountPercent(float $percent): void
    {
        if ($percent < 0 || $percent > 1) {
            throw new \InvalidArgumentException('Discount percent must be between 0 and 1.');
        }
        $this->discountPercent = $percent;
    }

    public function getDiscountPercent(): float
    {
        return $this->discountPercent;
    }

    protected function applyDiscount(float $amount): float
    {
        $global = (float) Config::get('GLOBAL_DISCOUNT', 0.0);
        $p = $global + $this->discountPercent;
        if ($p > 0.9) { // سقف حماية
            $p = 0.9;
        }
        return max(0.0, $amount * (1 - $p));
    }
}
