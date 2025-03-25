<?php

namespace App\Services;

use App\DTOs\LeadDTO;
use App\Enums\LeadStatusEnum;
use App\Enums\PriorityEnum;
use App\Enums\SegmentEnum;
use App\Enums\ServiceTypeEnum;
use App\Integrations\ViaCepIntegration;
use App\Repositories\LeadRepository;
use App\ValueObjects\Address;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LeadService
{
    public function __construct(private ViaCepIntegration $viaCep, private LeadRepository $leadRepository)
    {
        $this->viaCep = $viaCep;
        $this->leadRepository = $leadRepository;
    }

    public function listLeads()
    {
        Log::info('===================== Listando todos os contatos =====================');

        return $this->leadRepository->listLeads();
    }

    public function findLeadById($id)
    {
        Log::info("===================== Recuperando contato ID: {$id} =====================");

        $lead = $this->leadRepository->findLeadById($id);

        if (empty($lead)) {
            Log::error("Contato não encontrado para ID: {$id}");

            throw new \Exception("Contato não encontrado.");
        }

        return $lead;
    }

    public function createLead(array $data)
    {
        Log::info('===================== Iniciando registro de novo contato =====================', $data);

        $addressData = $this->viaCep->getAddressByCep($data['cep']);

        if (!$addressData || !isset($addressData->logradouro, $addressData->bairro, $addressData->localidade, $addressData->uf)) {
            Log::error("Endereço não encontrado para o CEP: {$data['cep']}");

            throw ValidationException::withMessages(['cep' => 'Endereço não encontrado para o CEP fornecido.']);
        }

        $leadDTO = new LeadDTO(
            $data['name'],
            $data['contact_type'],
            $data['phone'],
            $data['email'],
            $data['company_name'] ?? null,
            $data['position'] ?? null,
            SegmentEnum::tryFrom($data['segment'])->value,
            ServiceTypeEnum::tryFrom($data['services']),
            $data['interests'] ?? [],
            $data['observation'] ?? null,
            $data['source'] ?? null,
            $data['lead_source_details'] ?? null,
            PriorityEnum::tryFrom($data['priority']),
            LeadStatusEnum::tryFrom($data['status']),
            $data['cep'],
            Address::fromArray([
                'cep' => $addressData->cep ?? null,
                'logradouro' => $addressData->logradouro ?? '',
                'numero' => $addressData->numero ?? '',
                'complemento' => $addressData->complemento ?? '',
                'unidade' => $addressData->unidade ?? '',
                'bairro' => $addressData->bairro ?? '',
                'localidade' => $addressData->localidade ?? '',
                'uf' => $addressData->uf ?? '',
                'estado' => $addressData->uf ?? '',
                'regiao' => $addressData->regiao ?? '',
                'ibge' => $addressData->ibge ?? null,
                'gia' => $addressData->gia ?? null,
                'ddd' => $addressData->ddd ?? null,
                'siafi' => $addressData->siafi ?? null,
            ]),
        );

        try {
            $lead = $this->leadRepository->createLead($leadDTO);
            Log::info("Novo contato registrado com sucesso! ID: {$lead->id}");

            return $lead;
        } catch (\Exception $e) {
            Log::error("Erro ao inserir contato: " . $e->getMessage(), ['exception' => $e]);

            throw new \RuntimeException("Erro ao inserir o contato: " . $e->getMessage());
        }
    }

    public function editLeadDetails($id, array $data)
    {
        Log::info("===================== Editando contato ID: {$id} =====================", $data);

        $lead = $this->leadRepository->findLeadById($id);

        if (!$lead) {
            Log::error("Tentativa de edição falhou. Contato não encontrado para ID: {$id}");

            throw new \Exception("Contato não encontrado.");
        }

        if (isset($data['cep'])) {
            $cep = $data['cep'];
            $addressData = $this->viaCep->getAddressByCep($cep);

            if (!$addressData) {
                Log::error("Endereço não encontrado para CEP: {$cep} ao editar contato ID: {$id}");

                throw ValidationException::withMessages(['cep' => 'Endereço não encontrado para o CEP fornecido.']);
            }

            $data['address'] = Address::fromArray([
                'cep' => $addressData->cep ?? null,
                'logradouro' => $addressData->logradouro ?? '',
                'numero' => $addressData->numero ?? '',
                'complemento' => $addressData->complemento ?? '',
                'unidade' => $addressData->unidade ?? '',
                'bairro' => $addressData->bairro ?? '',
                'localidade' => $addressData->localidade ?? '',
                'uf' => $addressData->uf ?? '',
                'estado' => $addressData->uf ?? '',
                'regiao' => $addressData->regiao ?? '',
                'ibge' => $addressData->ibge ?? null,
                'gia' => $addressData->gia ?? null,
                'ddd' => $addressData->ddd ?? null,
                'siafi' => $addressData->siafi ?? null,
            ]);
        }

        try {
            $this->leadRepository->modifyLead($id, $data);
            Log::info("Contato ID: {$id} editado com sucesso.");

            return $lead;
        } catch (\Exception $e) {
            Log::error("Erro ao editar contato ID: {$id}: " . $e->getMessage(), ['exception' => $e]);

            throw new \RuntimeException("Erro ao editar o contato: " . $e->getMessage());
        }
    }

    public function removeLeadById($id)
    {
        Log::info("===================== Removendo contato ID: {$id} =====================");

        try {
            $this->leadRepository->removeLead($id);
            Log::info("Contato ID: {$id} removido com sucesso.");

            return true;
        } catch (\Exception $e) {
            Log::error("Erro ao remover contato ID: {$id}: " . $e->getMessage(), ['exception' => $e]);

            throw new \RuntimeException("Erro ao remover o contato: " . $e->getMessage());
        }
    }
}
