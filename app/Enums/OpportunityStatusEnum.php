<?php

namespace App\Enums;

enum OpportunityStatusEnum: string
{
    case OPEN        = 'aberta'; // Oportunidade criada, ainda em negociação
    case NEGOTIATION = 'negociacao'; // Em andamento
    case WON         = 'ganha'; // Fechou negócio (venda realizada)
    case LOST        = 'perdida'; // Oportunidade perdida
    case ON_HOLD     = 'em_espera'; // Pausada, sem decisão no momento

    public function label(): string
    {
        return match ($this) {
            self::OPEN        => 'Aberta',
            self::NEGOTIATION => 'Em Negociação',
            self::WON         => 'Ganha',
            self::LOST        => 'Perdida',
            self::ON_HOLD     => 'Em Espera',
        };
    }
}
