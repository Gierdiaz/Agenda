<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LeadService
{
    public function registerNewLead(array $data)
    {
        Log::info('===================== Iniciando registro de novo contato =====================', $data);

        if (!isset($data['contact_id'], $data['segment'], $data['services'], $data['observation'], $data['priority'], $data['status'])) {
            Log::error('Dados incompletos ao tentar registrar um contato', $data);

            throw ValidationException::withMessages(['error' => 'Todos os campos são obrigatórios.']);
        }
    }
}
