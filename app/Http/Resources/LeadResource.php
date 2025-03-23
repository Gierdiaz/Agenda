<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

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
            'id'          => $this->resource->id,
            'contact'     => new ContactResource($this->resource->contact),
            'segment'     => $this->resource->segment,
            'services'    => $this->resource->services->value,
            'observation' => $this->resource->observation,
            'priority'    => $this->resource->priority,
            'status'      => $this->resource->status->value,
            'created_at'  => $this->resource->created_at->format('d-m-Y H:i:s'),
            'updated_at'  => $this->resource->updated_at->format('d-m-Y H:i:s'),
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
