<?php

namespace App\Enums;

enum SegmentEnum: string {
case TECHNOLOGY = 'tecnologia';
case HEALTH = 'saude';
case FINANCE = 'financas';
case EDUCATION = 'educacao';
case OTHER = 'outros';

    public function label(): string
    {
        return match ($this) {
            self::TECHNOLOGY => 'tecnologia',
            self::HEALTH => 'Saude',
            self::FINANCE => 'Financas',
            self::EDUCATION => 'Educacao',
            self::OTHER => 'Outros',
        };
    }
}
