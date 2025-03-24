<?php

namespace App\Repositories;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\LeadDTO;
use App\Enums\{OpportunityStatusEnum};
use App\Models\{Lead, Opportunity};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeadRepository implements LeadRepositoryInterface
{
    public function paginateLeads(): LengthAwarePaginator
    {
        return Lead::query()->orderBy('created_at', 'desc')->paginate(10);
    }

    public function findLeadById(string $id): Lead
    {
        return Lead::findOrFail($id);
    }

    public function createLead(LeadDTO $leadDTO)
    {
        return Lead::create([
            'contact_id'  => $leadDTO->contactId,
            'segment'     => $leadDTO->segment,
            'services'    => $leadDTO->services,
            'observation' => $leadDTO->observation,
            'priority'    => $leadDTO->priority,
            'status'      => $leadDTO->status,
        ]);
    }

    public function changeLeadStatus(string $id, string $status): Lead
    {
        $lead         = Lead::findOrFail($id);
        $lead->status = $status;
        $lead->save();

        return $lead;
    }

    public function convertLeadToOpportunity(string $id): Opportunity
    {
        $lead = Lead::findOrFail($id);

        $opportunity = Opportunity::create([
            'lead_id'     => $lead->id,
            'title'       => 'Nova Oportunidade',
            'description' => 'Oportunidade gerada a partir do lead.',
            'value'       => 0.00,
            'status'      => OpportunityStatusEnum::ON_HOLD,
        ]);

        $lead->status = OpportunityStatusEnum::OPEN->value;
        $lead->save();

        return $opportunity;

    }
}
