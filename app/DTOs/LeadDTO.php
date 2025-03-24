<?php

namespace App\DTOs;

class LeadDTO
{
    public function __construct(
        public string $contactId,
        public string $segment,
        public string $services,
        public string $observation,
        public string $priority,
        public string $status,
    ) {
    }
}
