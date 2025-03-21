<?php

namespace App\Repositories;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\LeadDTO;
use App\Models\Lead;

class LeadRepository implements LeadRepositoryInterface
{
    public function insertLead(LeadDTO $leadDTO)
    {
        return Lead::created([
            'contact_id' => $leadDTO->contactId,
            'segment' => $leadDTO->segment,
            'services' => $leadDTO->services,
            'observation' => $leadDTO->observation,
            'priority' => $leadDTO->priority,
            'status' => $leadDTO->status
        ]);
    }
}