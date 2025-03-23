<?php

namespace App\Services;

use App\DTOs\LeadDTO;
use App\Repositories\LeadRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LeadService
{

    public function __construct(private LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function listLeads(): LengthAwarePaginator
    {
        Log::info('===================== Listando todos os leads =====================');

        return $this->leadRepository->paginateLead();
    }

    public function createLead(array $data)
    {
        Log::info('===================== Iniciando registro de novo contato =====================', $data);

        if (!isset($data['contact_id'], $data['segment'], $data['services'], $data['observation'], $data['priority'], $data['status'])) {
            Log::error('Dados incompletos ao tentar registrar um contato', $data);

            throw ValidationException::withMessages(['error' => 'Todos os campos são obrigatórios.']);
        }

        $leadDTO = new LeadDTO(
            $data['contact_id'],
            $data['segment'],
            $data['services'],
            $data['observation'],
            $data['priority'],
            $data['status'],
        );

        try {
            return $this->leadRepository->insertLead($leadDTO);
            Log::info("Novo lead registrado com sucesso! ID: {$lead->id}");

            return $lead;
        } catch (\Exception $e) {
            Log::error('Erro ao registrar lead', ['message' => $e->getMessage()]);

            throw new \RuntimeException("Erro ao registrar o lead: " . $e->getMessage());
        }
    }

    public function changeLeadStatus(string $id, string $status)
    {
        Log::info('===================== Iniciando alteração de status do lead =====================', ['id' => $id, 'status' => $status]);

        return $this->leadRepository->changeStatus($id, $status);
    }

    public function convertLeadToOpportunity(string $id)
    {
        Log::info('===================== Iniciando conversão de lead para oportunidade =====================', ['id' => $id]);

        return $this->leadRepository->convertToOpportunity($id);
    }
}
