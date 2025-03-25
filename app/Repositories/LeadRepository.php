<?php

namespace App\Repositories;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\LeadDTO;
use App\Models\Lead;
use App\ValueObjects\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeadRepository implements LeadRepositoryInterface
{
    public function listLeads(): LengthAwarePaginator
    {
        return Lead::query()->orderBy('created_at', 'desc')->paginate(10);
    }

    public function findLeadById($id)
    {
        return Lead::findOrFail($id);
    }

    public function createLead(LeadDTO $leadDTO)
    {
        return Lead::create([
            'name'    => $leadDTO->name,
            'phone'   => $leadDTO->phone,
            'email'   => $leadDTO->email,
            'cep'     => $leadDTO->cep,
            'address' => $leadDTO->address->toArray(),
        ]);
    }

    public function modifyLead($id, array $data)
    {
        if (isset($data['address']) && $data['address'] instanceof Address) {
            $data['address'] = $data['address']->toArray();
        }

        return Lead::where('id', $id)->update($data);
    }

    public function removeLead($id)
    {
        return Lead::destroy($id);
    }

    public function changeLeadStatus(string $id, string $status): Lead
    {
        $lead         = Lead::findOrFail($id);
        $lead->status = $status;
        $lead->save();

        return $lead;
    }
}
