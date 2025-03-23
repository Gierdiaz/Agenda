<?php

namespace App\Repositories;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\LeadDTO;
use App\Enums\LeadStatusEnum;
use App\Enums\OpportunityStatusEnum;
use App\Models\Lead;
use App\Models\Opportunity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeadRepository implements LeadRepositoryInterface
{

    public function paginateLead(): LengthAwarePaginator
    {
        return Lead::query()->orderBy('created_at', 'desc')->paginate(10);
    }

    public function insertLead(LeadDTO $leadDTO)
    {
        return Lead::created([
            'contact_id'  => $leadDTO->contactId,
            'segment'     => $leadDTO->segment,
            'services'    => $leadDTO->services,
            'observation' => $leadDTO->observation,
            'priority'    => $leadDTO->priority,
            'status'      => $leadDTO->status,
        ]);
    }

    public function changeStatus(string $id, string $status): Lead
    {
        $lead = Lead::findOrFail($id);
        $lead->status = $status;
        $lead->save();
        return $lead;
    }

    public function convertToOpportunity(string $id): Opportunity
    {
        $lead = Lead::findOrFail($id);

        if ($lead->status !== LeadStatusEnum::TRADIGNG->value) {
            throw new \Exception('Lead nao pode ser convertido para oportunidade');
        }

        $opportunity = Opportunity::create([
            'lead_id' => $lead->id,
            'title' => 'Nova Oportunidade',
            'description' => 'Oportunidade gerada a partir do lead.',
            'value' => 0.00,
            'status' => OpportunityStatusEnum::ON_HOLD,
        ]);

        $lead->status = OpportunityStatusEnum::OPEN->value;
        $lead->save();

        return $opportunity;

    }
}
