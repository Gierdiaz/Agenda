<?php

namespace App\DTOs;

use App\Enums\{LeadStatusEnum, ServiceTypeEnum};

class LeadDTO
{
    public function __construct(
        public string $contactId,
        public string $segment,
        public array $services = [ServiceTypeEnum::CONSULTING->value],
        public string $observation,
        public string $priority,
        public string $status = LeadStatusEnum::NEW->value
    ) {
    }
}
