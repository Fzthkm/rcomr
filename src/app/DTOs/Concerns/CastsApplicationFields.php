<?php
// app/DTO/Concerns/CastsApplicationFields
namespace App\DTOs\Concerns;

use Carbon\Carbon;

trait CastsApplicationFields
{
    protected function str(?string $v): ?string
    {
        if ($v === null) return null;
        $t = trim($v);
        return $t === '' ? null : $t;
    }

    protected function intOrNull($v): ?int
    {
        return $v === null || $v === '' ? null : (int)$v;
    }

    protected function dateDMY(?string $v): ?Carbon
    {
        return $v ? Carbon::createFromFormat('d.m.Y', $v)->startOfDay() : null;
    }
}
