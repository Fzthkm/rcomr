<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationCreateRequest extends FormRequest
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
            'consultation_date' => 'date',
            'institution_id' => ['required', 'exists:medical_institutions,id'],
            'specialist_id' => ['required', 'exists:specialists,id'],
            'diagnosis_id' => ['required', 'exists:diagnoses,id'],
        ];
    }
}
