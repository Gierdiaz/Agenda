<?php

namespace App\Http\Requests;

use App\Enums\{SegmentEnum, ServiceTypeEnum};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            // Identificação do lead
            'name'         => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'contact_type' => 'required|string',
            'phone'        => 'nullable|string|regex:/^(\+55)?\d{10,11}$/',
            'email'        => 'nullable|email|max:255',

            // Informações da empresa
            'company_name' => 'nullable|string|max:255',
            'position'     => 'nullable|string|max:255',

            // Interesses
            'segment'     => 'required|string|in:' . Rule::in(array_column(SegmentEnum::cases(), 'value')),
            'services'    => 'required|string|in:' . Rule::in(array_column(ServiceTypeEnum::cases(), 'value')),
            'interests'   => 'nullable|array',
            'interests.*' => 'string|max:255',
            'observation' => 'nullable|string|max:5000',

            // Origem do lead
            'source'              => 'required|string|max:255',
            'lead_source_details' => 'nullable|string|max:255',

            // Status e acompanhamento
            'priority' => 'required|string|in:baixa,media,alta',
            'status'   => 'required|string|in:novo,em_andamento,convertido,perdido',

            // Endereço
            'cep'     => 'required|string|regex:/^\d{5}-?\d{3}$/',
            'address' => 'nullable|array',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.regex'    => 'O nome só pode conter letras, espaços e traços.',
            'name.max'      => 'O nome não pode ter mais que 255 caracteres.',

            'contact_type.required' => 'O tipo de contato é obrigatório.',
            'contact_type.in'       => 'O tipo de contato deve ser email, phone ou whatsapp.',

            'phone.regex' => 'O telefone deve conter apenas números e pode iniciar com "+". Exemplo: +5511998765432',

            'email.email' => 'Forneça um endereço de e-mail válido.',
            'email.max'   => 'O e-mail não pode ter mais que 255 caracteres.',

            'segment.required' => 'O segmento é obrigatório.',
            'segment.in'       => 'O segmento deve ser um dos seguintes: tecnologia, saude, financas, educacao ou outros.',

            'services.required' => 'Os serviços são obrigatórios.',
            'services.array'    => 'Os serviços devem ser uma lista.',
            'services.*.in'     => 'Serviço inválido selecionado.',

            'priority.required' => 'A prioridade é obrigatória.',
            'priority.in'       => 'A prioridade deve ser baixa, média ou alta.',

            'status.required' => 'O status é obrigatório.',
            'status.in'       => 'O status deve ser novo, em andamento, convertido ou perdido.',

            'cep.required' => 'O campo CEP é obrigatório.',
            'cep.regex'    => 'O CEP deve estar no formato 00000-000 ou 00000000.',

            'address.array' => 'O endereço deve ser um objeto.',
        ];
    }
}
