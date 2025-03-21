<?php

namespace App\Contracts;

use App\DTOs\LeadDTO;

interface LeadRepositoryInterface
{
    public function insertLead(LeadDTO $leadDTO);
}
