<?php

namespace App\Services;

use App\DTOs\LeadDTO;
use App\Enums\LeadStatusEnum;
use App\Repositories\LeadRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class LeadService
{
    public function __construct(private LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function paginateLeads(): LengthAwarePaginator
    {
        Log::info('===================== Listando todos os leads =====================');

        return $this->leadRepository->paginateLeads();
    }

    public function createLead(array $data)
    {
        Log::info('===================== Iniciando registro de novo contato =====================', ['data' => $data]);

        $leadDTO = new LeadDTO(
            $data['contact_id'],
            $data['segment'],
            $data['services'],
            $data['observation'],
            $data['priority'],
            $data['status'],
        );

        try {
            $lead = $this->leadRepository->createLead($leadDTO);
            Log::info("Novo lead registrado com sucesso!", ['lead_id' => $lead->id]);

            return $lead;
        } catch (\Exception $e) {
            Log::error('Erro ao registrar lead', ['message' => $e->getMessage()]);

            throw new \RuntimeException("Erro ao registrar o lead: " . $e->getMessage());
        }
    }

    public function changeLeadStatus(string $id, string $status)
    {
        Log::info('===================== Iniciando alteração de status do lead =====================', ['lead_id' => $id, 'new_status' => $status]);

        return $this->leadRepository->changeLeadStatus($id, $status);
    }

    public function convertLeadToOpportunity(string $id)
    {
        Log::info('===================== Iniciando conversão de lead para oportunidade =====================', ['lead_id' => $id]);

        $lead = $this->leadRepository->findLeadById($id);

        if ($lead->status !== LeadStatusEnum::TRADIGNG->value) {
            throw new \Exception('Lead nao pode ser convertido para oportunidade');
        }

        return $this->leadRepository->convertLeadToOpportunity($id);
    }
}
