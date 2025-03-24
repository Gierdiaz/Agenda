<?php

namespace App\Contracts;

use App\DTOs\LeadDTO;
use App\Models\{Lead, Opportunity};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeadRepositoryInterface
{
    public function paginateLeads(): LengthAwarePaginator;

    public function findLeadById(string $id): Lead;

    public function createLead(LeadDTO $leadDTO);

    public function convertLeadToOpportunity(string $id): Opportunity;

    public function changeLeadStatus(string $id, string $status): Lead;
}
