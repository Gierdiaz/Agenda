<?php

namespace App\Contracts;

use App\DTOs\LeadDTO;
use App\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeadRepositoryInterface
{
    public function listLeads(): LengthAwarePaginator;

    public function findLeadById($id);

    public function createLead(LeadDTO $leadDTO);

    public function modifyLead($id, array $data);

    public function removeLead($id);

    public function changeLeadStatus(string $id, string $status): Lead;
}
