<?php

namespace App\Http\Requests;

use App\Enums\{LeadStatusEnum, PriorityEnum, SegmentEnum, ServiceTypeEnum};
use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contact_id'  => 'required|uuid|exists:contacts,id',
            'segment'     => 'nullable|string|in:' . implode(',', array_column(SegmentEnum::cases(), 'value')),
            'services'    => 'required|string|in:' . implode(',', array_column(ServiceTypeEnum::cases(), 'value')),
            'observation' => 'nullable|string|max:500',
            'priority'    => 'required|string|in:' . implode(',', array_column(PriorityEnum::cases(), 'value')),
            'status'      => 'required|string|in:' . implode(',', array_column(LeadStatusEnum::cases(), 'value')),
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'contact_id.required' => 'O campo contato é obrigatório.',
            'contact_id.uuid'     => 'O ID do contato deve ser um UUID válido.',
            'contact_id.exists'   => 'O contato fornecido não existe.',

            'segment.string' => 'O segmento deve ser um texto.',
            'segment.max'    => 'O segmento não pode ter mais que 255 caracteres.',

            'services.required' => 'O serviço é obrigatório.',
            'services.in'       => 'O serviço fornecido é inválido.',

            'observation.string' => 'A observação deve ser um texto.',
            'observation.max'    => 'A observação não pode ter mais que 500 caracteres.',

            'priority.required' => 'O campo prioridade é obrigatório.',
            'priority.in'       => 'A prioridade deve ser baixa, média ou alta.',

            'status.required' => 'O campo status é obrigatório.',
            'status.in'       => 'O status fornecido é inválido.',
        ];
    }
}
