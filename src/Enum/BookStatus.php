<?php

namespace App\Enum;

enum BookStatus: string
{
    case Disponible = 'Disponible';
    case Emprunté = 'Emprunté';
    case Indisponible = 'Indisponible';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::Disponible => 'Disponible',
            self::Emprunté => 'Emprunté',
            self::Indisponible => 'Indisponible',
        };
    }
}