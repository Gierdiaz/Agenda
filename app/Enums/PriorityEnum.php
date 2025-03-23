<?php

namespace App\Enums;

enum PriorityEnum: string
{
    case LOW    = 'baixa';
    case MEDIUM = 'media';
    case HIGH   = 'alta';

    public function label(): string
    {
        return match ($this) {
            self::LOW    => 'Baixa',
            self::MEDIUM => 'Média',
            self::HIGH   => 'Alta',
        };
    }
}
