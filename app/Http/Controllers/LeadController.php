<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Http\Resources\LeadResource;
use App\Services\LeadService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function __construct(private LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function index(): AnonymousResourceCollection
    {
        $leads = $this->leadService->listLeads();

        return LeadResource::collection($leads);
    }

    public function show($id)
    {
        try {
            $lead = $this->leadService->findLeadById($id);

            return LeadResource::make($lead);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar contato.',
                'details' => $e->getMessage(),
            ], $e->getCode() ?: Response::HTTP_NOT_FOUND);
        }
    }

    public function store(LeadRequest $request)
    {
        DB::beginTransaction();

        try {
            $lead = $this->leadService->createLead($request->all());

            DB::commit();
            $execute = round(microtime(true) - LARAVEL_START, 4);

            Log::info('Novo Lead registrado com sucesso.', [
                'lead_id' => $lead->id,
                'execution_time' => "{$execute} segundos",
            ]);

            return response()->json([
                'message' => 'Lead cadastrado com sucesso.',
                'data' => new LeadResource($lead),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao tentar registrar um novo lead:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Erro ao registrar lead.',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
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
                'lead_id' => $lead->id,
                'new_status' => $lead->status,
            ]);

            return response()->json([
                'message' => 'Status do lead atualizado com sucesso.',
                'data' => new LeadResource($lead),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar status do lead.', [
                'lead_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Erro ao atualizar status do lead.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(LeadRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();

        try {

            $this->leadService->editLeadDetails($id, $request->all());

            DB::commit();

            return response()->json([
                'message' => 'Contato atualizado com sucesso.',
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao atualizar contato.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->leadService->removeLeadById($id);

            return response()->json([
                'message' => 'Contato removido com sucesso.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir contato.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
