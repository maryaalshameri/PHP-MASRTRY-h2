<?php
declare(strict_types=1);

namespace MiniStore\Modules\Users;

final class Admin extends User
{
    public function getRole(): string
    {
        return 'admin';
    }
}
