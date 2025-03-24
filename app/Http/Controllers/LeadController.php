<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Http\Resources\LeadResource;
use App\Services\LeadService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\{DB, Log};

class LeadController extends Controller
{
    public function __construct(private LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function index(): AnonymousResourceCollection
    {
        $leads = $this->leadService->paginateLeads();

        return LeadResource::collection($leads);
    }

    public function store(LeadRequest $request)
    {
        DB::beginTransaction();

        try {
            $lead = $this->leadService->createLead($request->all());

            DB::commit();
            $execute = round(microtime(true) - LARAVEL_START, 4);

            Log::info('Novo Lead registrado com sucesso.', [
                'lead_id'        => $lead->id,
                'execution_time' => "{$execute} segundos",
            ]);

            return response()->json([
                'message' => 'Lead cadastrado com sucesso.',
                'data'    => new LeadResource($lead),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao tentar registrar um novo lead.' . [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Erro ao registrar lead.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeStatus(string $id, string $status)
    {
        Log::info('Iniciando alteração de status do lead.', ['lead_id' => $id, 'new_status' => $status]);

        DB::beginTransaction();

        try {
            $lead = $this->leadService->changeLeadStatus($id, $status);

            DB::commit();

            Log::info('Status do lead atualizado com sucesso.', [
                'lead_id'    => $lead->id,
                'new_status' => $lead->status,
            ]);

            return response()->json([
                'message' => 'Status do lead atualizado com sucesso.',
                'data'    => new LeadResource($lead),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar status do lead.', [
                'lead_id' => $id,
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Erro ao atualizar status do lead.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function convertToOpportunity(string $id)
    {
        Log::info('Iniciando conversão de lead para oportunidade.', ['lead_id' => $id]);

        DB::beginTransaction();

        try {
            $opportunity = $this->leadService->convertLeadToOpportunity($id);

            DB::commit();

            Log::info('Lead convertido para oportunidade com sucesso.', [
                'lead_id'        => $id,
                'opportunity_id' => $opportunity->id,
            ]);

            return response()->json([
                'message' => 'Lead convertido para oportunidade com sucesso.',
                'data'    => $opportunity,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao converter lead para oportunidade.', [
                'lead_id' => $id,
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Erro ao converter lead para oportunidade.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
