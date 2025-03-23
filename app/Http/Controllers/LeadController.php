<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Http\Resources\LeadResource;
use App\Services\LeadService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\{DB};

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

    public function store(LeadRequest $request)
    {
        DB::beginTransaction();

        try {
            $lead = $this->leadService->createLead($request->all());

            DB::commit();

            return response()->json([
                'message' => 'Lead registrado com sucesso.',
                'data'    => new LeadResource($lead),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao registrar lead.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
