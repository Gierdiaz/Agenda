<?php

namespace App\DTOs;

use App\ValueObjects\Address;

class ContactDTO
{
    public function __construct(
        public string $name,
        public string $phone,
        public string $email,
        public string $cep,
        public Address $address
    ) {
    }
}
