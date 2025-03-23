<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeadResource;
use App\Services\LeadService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function store(): JsonResponse
    {
        
    }
}
