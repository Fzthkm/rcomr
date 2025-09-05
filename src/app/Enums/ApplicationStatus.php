<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Created = "created";
    case Canceled = "canceled";

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function label(): String
    {
        return match ($this) {
            self::Created => "Создано",
            self::Canceled => "Отменено",
        };
    }
}
