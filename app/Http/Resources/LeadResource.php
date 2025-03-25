<?php

namespace App\Http\Resources;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

/**
 * @property Lead $resource
 */
class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isIndex = Route::currentRouteName() === 'leads.index';
        $isShow  = Route::currentRouteName() === 'leads.show';

        $resourceArray = [
            'id'                  => $this->resource->id,
            'name'                => $this->resource->name,
            'contact_type'        => $this->resource->contact_type,
            'phone'               => $this->resource->phone,
            'email'               => $this->resource->email,
            'company_name'        => $this->resource->company_name,
            'position'            => $this->resource->position,
            'segment'             => $this->resource->segment->value,
            'services'            => $this->resource->services->value,
            'interests'           => $this->resource->interests,
            'observation'         => $this->resource->observation,
            'source'              => $this->resource->source,
            'lead_source_details' => $this->resource->lead_source_details,
            'priority'            => $this->resource->priority->value,
            'status'              => $this->resource->status->value,
            'cep'                 => $this->resource->cep,
            'address'             => $this->resource->address,
        ];

        if ($isShow) {
            $resourceArray['links'] = [
                'index' => route('leads.index'),
            ];
        } elseif ($isIndex) {
            $resourceArray['links'] = [
                'show' => route('leads.show', $this->resource->id),
            ];
        }

        return $resourceArray;
    }
}
