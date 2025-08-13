<?php
declare(strict_types=1);

namespace MiniStore\Modules\Users;

abstract class User
{
    protected int $id;
    protected string $name;
    protected string $email;

    public function __construct(int $id, string $name, string $email)
    {
        $name = trim($name);
        if ($name === '') {
            throw new \InvalidArgumentException('Name is required.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email.');
        }

        $this->id    = $id;
        $this->name  = $name;
        $this->email = $email;
    }

    final public function getId(): int
    {
        return $this->id; // final كمثال
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    abstract public function getRole(): string;
}
