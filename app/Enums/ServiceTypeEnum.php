<?php

namespace App\Enums;

enum ServiceTypeEnum: string
{
    case CONSULTING           = 'Consultoria';
    case SOFTWARE_DEVELOPMENT = 'Desenvolvimento de Software';
    case TECHNICAL_SUPPORT    = 'Suporte Técnico';
    case TRAINING             = 'Treinamento';
    case DIGITAL_MARKETING    = 'Marketing Digital';
    case PROJECT_MANAGEMENT   = 'Gestão de Projetos';
    case SALES                = 'Vendas';
    case FINANCIAL_SERVICES   = 'Serviços Financeiros';
    case OTHERS               = 'Outros';

    public function label(): string
    {
        return match ($this) {
            self::CONSULTING           => 'Consultoria',
            self::SOFTWARE_DEVELOPMENT => 'Desenvolvimento de Software',
            self::TECHNICAL_SUPPORT    => 'Suporte Técnico',
            self::TRAINING             => 'Treinamento',
            self::DIGITAL_MARKETING    => 'Marketing Digital',
            self::PROJECT_MANAGEMENT   => 'Gestão de Projetos',
            self::SALES                => 'Vendas',
            self::FINANCIAL_SERVICES   => 'Serviços Financeiros',
            self::OTHERS               => 'Outros',
        };
    }
}
