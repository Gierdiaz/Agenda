<?php

namespace App\DTOs;

use App\Enums\{LeadStatusEnum, PriorityEnum, SegmentEnum, ServiceTypeEnum};
use App\ValueObjects\Address;

class LeadDTO
{
    public function __construct(
        public string $name,
        public string $contact_type,
        public string $phone,
        public string $email,
        public ?string $company_name,
        public ?string $position,
        public SegmentEnum $segment,
        public ServiceTypeEnum $services,
        public array $interests,
        public ?string $observation,
        public ?string $source,
        public ?string $lead_source_details,
        public PriorityEnum $priority,
        public LeadStatusEnum $status,
        public string $cep,
        public Address $address
    ) {
    }
}
