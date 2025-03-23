<?php

namespace App\Contracts;

use App\DTO\LeadDTO;
use App\Models\{Lead, Opportunity};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeadRepositoryInterface
{
    public function paginateLead(): LengthAwarePaginator;

    public function insertLead(LeadDTO $leadDTO);

    public function convertToOpportunity(string $id): Opportunity;

    public function changeStatus(string $id, string $status): Lead;
}
