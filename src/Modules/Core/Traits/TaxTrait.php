<?php
declare(strict_types=1);

namespace MiniStore\Modules\Core\Traits;

use MiniStore\Modules\Core\Config;

trait TaxTrait
{
    protected function calcTaxOnly(float $amount): float
    {
        $rate = (float) Config::get('TAX_RATE', 0.0);
        return $amount * $rate;
    }

    protected function applyTax(float $amount): float
    {
        $rate = (float) Config::get('TAX_RATE', 0.0);
        return $amount * (1 + $rate);
    }
}
