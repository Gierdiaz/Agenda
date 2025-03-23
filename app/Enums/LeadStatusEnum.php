<?php

namespace App\Enums;

enum LeadStatusEnum: string
{
    case NEW          = 'novo'; // Lead recém-criado, sem contato ainda
    case TRADIGNG     = 'negociando';
    case CONTACTED    = 'contatado'; // Primeiro contato feito
    case QUALIFIED    = 'qualificado'; // Interesse confirmado
    case DISQUALIFIED = 'desqualificado'; // Lead inválido ou sem interesse
    case CONVERTED    = 'convertido'; // Se tornou uma Oportunidade

    public function label(): string
    {
        return match ($this) {
            self::NEW          => 'Novo',
            self::TRADIGNG     => 'Negociando',
            self::CONTACTED    => 'Contatado',
            self::QUALIFIED    => 'Qualificado',
            self::DISQUALIFIED => 'Desqualificado',
            self::CONVERTED    => 'Convertido',
        };
    }
}
