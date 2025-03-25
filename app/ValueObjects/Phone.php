<?php

namespace App\ValueObjects;

class Phone
{
    public function __construct(private string $phone)
    {
        $this->ensureIsValidPhoneNumber($phone);
        $this->phone = $phone;
    }

    public static function fromString(string $phone): self
    {
        return new self($phone);
    }

    public function __toString(): string
    {
        return $this->phone;
    }

    private function ensureIsValidPhoneNumber(string $phone): void
    {
        if (!preg_match('/^(\+55)?\d{10,11}$/', $phone)) {
            throw new \InvalidArgumentException("Número de telefone inválido: $phone");
        }
    }

    public function equals(Phone $other): bool
    {
        return $this->phone === (string) $other;
    }
}
