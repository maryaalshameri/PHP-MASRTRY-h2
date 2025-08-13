<?php
declare(strict_types=1);

namespace MiniStore\Modules\Users;

final class Customer extends User
{
    public function getRole(): string
    {
        return 'customer';
    }
}
