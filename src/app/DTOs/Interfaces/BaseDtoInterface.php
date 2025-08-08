<?php

namespace App\DTOs\Interfaces;

use Illuminate\Foundation\Http\FormRequest;

interface BaseDtoInterface
{
    public static function fromRequest(FormRequest $request): static;
    public function toArray(): array;
}
